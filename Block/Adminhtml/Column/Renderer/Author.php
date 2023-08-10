<?php

class BIS2BIS_Changelog_Block_Adminhtml_Column_Renderer_Author extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row)
    {
        $author = $row->getAuthor();
        if(is_array($author)) {
            $columnData = '<p class="a-center"><a href="'.$author[1].'" target="_blank">'.ucwords($author[0]).'</a></p>';
            return $columnData;
        }
        
        return '<p class="a-center">Não encontrado</p>';
    }
}