<?php
class BIS2BIS_Changelog_Model_System_Config_Source_LoadCategories
{
    public function toOptionArray()
    {
        $collection = Mage::getModel("changelog/resource_category_collection");
        $collection->loadData();
        $selectData = [];
        foreach($collection as $category) {
            $selectData[] = ["value" => $category->getId(), "label" => $category->getName()];
        }
        return $selectData;
    }
}
