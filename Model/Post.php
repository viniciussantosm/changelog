<?php

class BIS2BIS_Changelog_Model_Post extends Varien_Object{

    private $author;
    private $categories;

    public function getGeneric($field){
        return $this->getData($field);
    }

    public function _construct(){
        $author = Mage::getModel("changelog/author")->load(parent::getAuthor());
        $this->author = $author;
        $categories = Mage::getModel("changelog/category")
            ->getCollection()
            ->addFilter("include", implode(",", parent::getCategories()));
        $this->categories = $categories;
    }

    public function getCollection()
    {
        return Mage::getModel("changelog/resource_post_collection");
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getCategories()
    {
        return $this->categories;
    }
}