<?php
class BIS2BIS_Changelog_Model_System_Config_Source_LoadCategories
{
    public function toOptionArray()
    {
        $collection = Mage::getModel("changelog/category")
            ->getCollection()
            ->getResource()
            ->addFilter("per_page", 100);
        $selectData = [];
        foreach($collection as $category) {
            $selectData[] = ["value" => $category->getId(), "label" => $category->getName()];
        }
        return $selectData;
    }
}
