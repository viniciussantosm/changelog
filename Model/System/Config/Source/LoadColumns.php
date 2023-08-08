<?php

class BIS2BIS_Changelog_Model_System_Config_Source_LoadColumns {

    public function toOptionArray()
    {
        $collection = Mage::getModel("changelog/post_fetch")->fetchPosts();
        $collection = unserialize($collection)->getFirstItem()->getPost();
        
        if(!$collection) {
            return false;
        }
        return $this->collectionToColumns($collection);
    }

    public function collectionToColumns($collection)
    {
        $data = $collection->toArray();

        $columnKeys = array_keys($data);
        $selectData = [];
        foreach($columnKeys as $index => $key) {
            if(strpos($key, "link") || strpos($key, "id") || $key === "link" || $key === "id") {
                continue;
            }

            $selectData[] = ["value" => $key, "label" => ucwords($key)];
        }
        
        return $selectData;
    }
}