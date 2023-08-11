<?php

class BIS2BIS_Changelog_Block_Adminhtml_Column_Renderer_Updates extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row)
    {
        $category = $row->getCategories();
        $columnData = "";
        foreach($category as $item) {
            $columnData .= sprintf('<a class="changelog-category-link" href="%s" target="_blank">%s</a> / ',
                $item->getLink(),
                $item->getName());
        }
        $rowContent = $row->getData("content/rendered");

        if(strlen($rowContent) >= 300) {
            $rowContent = substr($rowContent, 0, 500);
            $rowContent = explode(" ", $rowContent);
            array_pop($rowContent);
            $rowContent = implode(" ", $rowContent);
            $rowContent .= "...";
        }

        $showMoreLink = sprintf(' <a class="changelog-category-link" href="%s" target="_blank">Continue lendo</a>',
            $row->getLink());
        
        return sprintf(
            '<div class="changelog-title-container"><a class="changelog-title" href="%s" target="_blank">%s</a></div><div class="changelog-content-container"><div class="changelog-content">%s%s</div></div><div class="changelog-info-container"><div class="changelog-category-container">%s</div><div class="changelog-date-container"><b>%s</b></div></div>',
            $row->getLink(),
            ucwords($row->getData("title/rendered")),
            $rowContent,
            $showMoreLink,
            trim($columnData, " / "),
            $this->treatDate($row->getModified()));
    }

    public function treatDate($date)
    {
        $timestamp = strtotime($date);
        $finalDate = date("H:i d/m/Y", $timestamp);
        return $finalDate;
    }
}