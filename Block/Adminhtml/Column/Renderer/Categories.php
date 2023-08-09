<?php

class BIS2BIS_Changelog_block_Adminhtml_Column_Renderer_Categories extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row)
    {
        $category = $row->getCategories();
        $columnData = "";
        foreach($category as $item) {
            $columnData .= '<a href="'.$item[1].'" target="_blank">'.$item[0].'</a> / ';
        }

        return trim($columnData, " / ");
    }
}