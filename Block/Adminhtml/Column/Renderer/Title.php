<?php

class BIS2BIS_Changelog_Block_Adminhtml_Column_Renderer_Title extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row)
    {
        $rowData = $row->getData();
        $title =  $row->getTitle();
        $link =  $rowData->getLink();
        return '<a href="'.$link.'" target="_blank">'. ucwords($title).'</a>';
    }
}