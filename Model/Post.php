<?php

class BIS2BIS_Changelog_Model_Post extends Varien_Object{
    private $categories;

    public function _construct(){
        $categories = Mage::getModel("changelog/category")
            ->getCollection()
            ->getResource()
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