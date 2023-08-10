<?php

class BIS2BIS_Changelog_Block_Adminhtml_Column_Renderer_Title extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row)
    {
        $category = $row->getCategories();
        $columnData = "";
        foreach($category as $item) {
            $columnData .= '<a href="'.$item->getLink().'" target="_blank">'.$item->getName().'</a> / ';
        }
        
        return '<a href="'.$row->getLink().'" target="_blank">'. ucwords($row->getTitle()["rendered"]).'</a><div><b>'. $this->treatDate($row->getModified()) .'</b></div><div>'. trim($columnData, " / ") .'</div>';
    }

    public function treatDate($date)
    {
        $timestamp = strtotime($date);
        $finalDate = date("H:i d/m/Y", $timestamp);
        return $finalDate;
    }
}