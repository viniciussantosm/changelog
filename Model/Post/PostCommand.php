<?php

class BIS2BIS_Changelog_Model_Post_PostCommand {

    public function execute()
    {
        $collection = Mage::getModel("changelog/post_fetch")->fetchPosts();
        return $this->prepareCollection($collection);
    }

    public function prepareCollection($collection)
    {
        $collection = unserialize($collection);
        $helper = Mage::helper("changelog/config");
        $filteredCollection = $collection->getItemsByColumnValue("id_cat", $helper->getActiveCategory());

        $postsCollection = new Varien_Data_Collection();
        foreach($filteredCollection as $postObject) {
            $postsCollection->addItem(new Varien_Object($postObject->getPost()));
        }

        return $postsCollection;
    }
    
    public function getItemsByColumnValues($column, $values, $collection)
    {
        $data = $collection->load();

        $res = array();
        foreach ($data as $item) {
            if (in_array($item->getData($column), $values)) {
                $res[] = $item;
            }
        }
        return $res;
    }
}