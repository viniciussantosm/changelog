<?php

class BIS2BIS_Changelog_Model_Author extends Varien_Object {

    public function getGeneric($field){
        return $this->getData($field);
    }

    public function load($id)
    {
        $authorCollection = Mage::getModel("changelog/resource_author_collection")->addFilter("id", $id);
        return $authorCollection->getFirstItem();
    }
}