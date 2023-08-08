<?php

class BIS2BIS_Changelog_Model_Post_Fetch {

    private $obj;
    private $helper;

    public function __construct() {
        $this->helper = Mage::helper("changelog/config");
    }

    public function fetchPosts(BIS2BIS_Changelog_Model_Category_Fetch $categoryFetch, BIS2BIS_Changelog_Model_Author_Fetch $authorFetch)
    {
        // echo unserialize(Mage::app()->getCache()->load("changelog_url")) . PHP_EOL;
        // echo $this->helper->getBlogUrl();
        // echo $this->helper->getActiveCategory();
        // exit();
        
        if($this->checkCache()) {
            return Mage::app()->getCache()->load("changelog");
        }

        $categories = $categoryFetch->fetchCategoriesIds();
        
        $this->obj = $this->createPostsCollection($categories, $authorFetch);
        $this->saveCache();
        return Mage::app()->getCache()->load("changelog");
    }

    public function fetchPostsByCategory($category)
    {
        $response = $this->curlHandler($category);
        $content = $this->treatPosts($response);
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
        $cache->save(serialize($this->helper->getActiveCategory()),"changelog_cat", ["CHANGELOG_CAT"], 3600);
        $cache->save(serialize($this->helper->getBlogUrl()),"changelog_url", ["CHANGELOG_URL"], 3600);
    }

    public function checkCache()
    {
        if(unserialize(Mage::app()->getCache()->load("changelog_cat")) !== $this->helper->getActiveCategory()){
            return false;
        }

        if(unserialize(Mage::app()->getCache()->load("changelog_url")) !== $this->helper->getBlogUrl()){
            return false;
        }

        return Mage::app()->getCache()->load("changelog") ? true : false;
    }

    public function prepareTitle($title)
    {
        return ucwords($title);
    }

    public function createPostsCollection($categories, BIS2BIS_Changelog_Model_Author_Fetch $authorFetch)
    {
        $postsCollection = new Varien_Data_Collection();
        $authors = $authorFetch->getAuthors();
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

    function curlHandler($category)
    {
        $curl = curl_init();

        $params = [
            "per_page" => 5,
            "orderby" => "modified",
            "categories" => $category,
        ];

        $url = sprintf("https://%s/wp-json/wp/v2/posts?%s", $this->helper->getBlogUrl(), http_build_query($params));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}