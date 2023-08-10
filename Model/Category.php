<?php

class BIS2BIS_Changelog_Model_Category extends Varien_Object {

    public function getGeneric($field){
        return $this->getData($field);
    }

    public function load($id)
    {
        $categoryCollection = $this->getCollection();
        return $categoryCollection->addFilter("include", $id)->getFirstItem();
    }

    public function getCollection()
    {
        return Mage::getModel("changelog/resource_category_collection");
    }
}