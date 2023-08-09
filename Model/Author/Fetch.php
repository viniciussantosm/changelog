<?php

class BIS2BIS_Changelog_Model_Author_Fetch {

    private $obj;
    private $helper;

    public function __construct() {
        $this->helper = Mage::helper("changelog/config");
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

    public function fetchAuthors()
    {
        $content = $this->curlHandler();

        if(is_array($content)) {
            return $content;
        }

        return null;
    }

    function curlHandler()
    {
        $curl = curl_init();

        $url = sprintf("https://%s/wp-json/wp/v2/users", $this->helper->getBlogUrl());
        curl_setopt($curl, CURLOPT_URL, $url);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: {$this->helper->getWpUser()}:{$this->helper->getWpPass()}",
            "Content-Type: application/json",
         ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($curl);
        curl_close($curl);

        return json_decode($content, true);
    }
}