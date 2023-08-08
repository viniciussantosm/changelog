<?php

class BIS2BIS_Changelog_Block_Adminhtml_Column_Renderer_Author extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row)
    {
        $rowData = $row->getData();
        $author = $rowData->getAuthor();
        $authorLink = $rowData->getAuthorLink();

        if(!isset($author)) {
            return "Não encontrado";
        }

        return '<a href="'.$authorLink.'" target="_blank">'.ucwords($author).'</a>';
    }
}