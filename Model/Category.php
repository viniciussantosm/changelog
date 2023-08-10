<?php

class BIS2BIS_Changelog_Model_Category extends Varien_Object {

    public function getGeneric($field){
        return $this->getData($field);
    }
}