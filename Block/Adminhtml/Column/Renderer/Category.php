<?php

class BIS2BIS_Changelog_block_Adminhtml_Column_Renderer_Category extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row)
    {
        $rowData = $row->getData();
        $category = $rowData->getCategory();
        $categoryLink = $rowData->getCategoryLink();
        // return "<pre>" . print_r($rowData, true);
        return '<a href="'.$categoryLink.'" target="_blank">'.$category.'</a>';
    }
}