<?php

class BIS2BIS_Changelog_Model_Category_Fetch {

    private $obj;
    private $helper;

    public function __construct() {
        $this->helper = Mage::helper("changelog/config");
    }

    public function fetchCategoriesIds()
    {
        return $this->treatCategories($this->curlHandler());
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

    function curlHandler()
    {
        $curl = curl_init();

        $params = [
            "per_page" => 100,
            "orderby" => "id",
        ];

        $url = sprintf("https://%s/wp-json/wp/v2/categories?%s", $this->helper->getBlogUrl(), http_build_query($params));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($curl);
        curl_close($curl);

        return $content;
    }
}