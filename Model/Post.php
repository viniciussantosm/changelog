<?php

class BIS2BIS_Changelog_Model_Post extends Varien_Object{

    public function getGeneric($field){
        return $this->getData($field);
    }
}