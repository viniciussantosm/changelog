<?php
class BIS2BIS_Changelog_Model_System_Config_Source_LoadCategories extends BIS2BIS_Changelog_Model_Category_Fetch
{
    public function toOptionArray()
    {
        $categories = $this->fetchCategoriesIds();
        $selectData = [];
        foreach($categories as $id => $categoryData) {
            $selectData[] = ["value" => $id, "label" => $categoryData["name"]];
        }
        
        return $selectData;
    }
}
