<?php

class BIS2BIS_Changelog_Model_Post_Fetch {

    private $obj;
    private $helper;

    public function __construct() {
        $this->helper = Mage::helper("changelog/config");
    }

    public function fetchPosts()
    {
        if($this->checkCache()) {
            return Mage::app()->getCache()->load("changelog");
        }
        
        $categories = $this->fetchCategoriesIds();
        $this->obj = $this->createPostsCollection($categories);
        $this->saveCache();
        return Mage::app()->getCache()->load("changelog");
    }

    public function fetchCategoriesIds()
    {
        $resource = fopen(sprintf("https://%s/wp-json/wp/v2/categories?per_page=100&orderby=id", $this->helper->getBlogUrl()), "r");
        $content = stream_get_contents($resource);
        fclose($resource);

        return $this->treatCategories($content);
    }

    public function fetchPostsByCategory($category)
    {
        $resource = fopen(sprintf("https://%s/wp-json/wp/v2/posts?categories=%s&orderby=modified&per_page=5", $this->helper->getBlogUrl(), $category), "r");
        $content = stream_get_contents($resource);
        fclose($resource);
        $content = $this->treatPosts($content);
        return $content;
    }

    public function treatPosts($resource)
    {
        $posts = json_decode($resource, true);
        $postData = Array();
        foreach($posts as $post) {
            $postData[] = [
                "title" => $post["title"]["rendered"],
                "modified" => $post["modified"],
                "link" => $post["link"],
                "author_id" => $post["author"],
            ];
        }
        return $postData;
    }    

    public function saveCache()
    {
        $cache = Mage::app()->getCache();
        $cache->save(serialize($this->obj),"changelog", ["CHANGELOG"], 3600);
    }

    public function checkCache()
    {
        return false;
        return Mage::app()->getCache()->load("changelog") ? true : false;
    }

    public function prepareTitle($title)
    {
        return ucwords($title);
    }

    public function treatCategories($resource)
    {
        $categories = json_decode($resource, true);
        $categoriesData = [];
        foreach($categories as $category) {
            $categoriesData[$category["id"]] = [
                        "name" => $category["name"],
                        "link" => $category["link"],
                    ];
        }

        return $categoriesData;
    }

    public function createPostsCollection($categories)
    {
        $postsCollection = new Varien_Data_Collection();
        $authors = $this->getAuthors();
        foreach($categories as $id => $categoryData) {
            $posts = $this->fetchPostsByCategory($id);

            foreach($posts as $post) {
                $author = $authors->getItemByColumnValue("id", $post["author_id"]);

                $data = [
                    'category' => $categoryData["name"],
                    'category_link' => $categoryData["link"],
                    'author' => null,
                    'author_link' => null
                ];

                if($author) {
                    $data["author"] = $author->getName();
                    $data["author_link"] = $author->getLink();
                }

                $post = array_merge($post, $data);

                $post = new Varien_Object($post);
                $postsCollection->addItem(new Varien_Object(["post" => $post, 'id_cat' => $id]));
            }
        }
        return $postsCollection;
    }

    public function fetchAuthors()
    {
        $resource = fopen(sprintf("https://%s/wp-json/wp/v2/users", $this->helper->getBlogUrl()), "r", false, $this->buildWpContext());
        $content = json_decode(stream_get_contents($resource), true);
        fclose($resource);

        if(is_array($content)) {
            return $content;
        }

        return null;
    }

    public function getAuthors()
    {
        $authors = $this->fetchAuthors();
        $authorsCollection = new Varien_Data_Collection();
        foreach($authors as $author) {
            $authorsCollection->addItem(new Varien_Object($author));
        }

        return $authorsCollection;
    }

    public function buildWpContext()
    {
        $helper = Mage::helper("changelog/config");
        $options = ["http" => [
            "method" => "GET",
            "header" => [
                "Authorization: {$helper->getWpUser()}:{$helper->getWpPass()}",
                "Content-Type: application/json"
                ]
            ]
        ];
        return stream_context_create($options);
    }
}