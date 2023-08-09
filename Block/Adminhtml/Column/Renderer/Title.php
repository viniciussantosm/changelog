<?php

class BIS2BIS_Changelog_Block_Adminhtml_Column_Renderer_Title extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row)
    {
        $title =  $row->getTitle();
        $link =  $row->getLink();
        $modified = $row->getModified();

        $category = $row->getCategories();
        $columnData = "";
        foreach($category as $item) {
            $columnData .= '<a href="'.$item[1].'" target="_blank">'.$item[0].'</a> / ';
        }
        
        return '<a href="'.$link.'" target="_blank">'. ucwords($title).'</a><div>'. $this->treatDate($modified) .'</div><div>'. trim($columnData, " / ") .'</div>';
    }

    public function treatDate($date)
    {
        $timestamp = strtotime($date);
        $finalDate = date("H:i:s d/m/Y", $timestamp);
        return $finalDate;
    }
}