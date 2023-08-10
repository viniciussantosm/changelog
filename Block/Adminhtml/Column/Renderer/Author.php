<?php

class BIS2BIS_Changelog_Block_Adminhtml_Column_Renderer_Author extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row)
    {
        echo "<pre>";
        if($row->getData('yoast_head_json')) {
            $columnData = '<p class="a-center">'.ucwords($row->getData('yoast_head_json/author')).'</p>';
            return $columnData;
        }
        
        return '<p class="a-center">Não encontrado</p>';
    }
}