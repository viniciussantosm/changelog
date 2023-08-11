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
            $rowContent = explode(PHP_EOL, strip_tags($rowContent));
            $rowContent = sprintf("<p>%s</p>", str_replace(" ", "", $rowContent[0]) == "" ? $rowContent[1] : $rowContent[0]);
        }

        $showMoreLink = sprintf('<a class="see-more-link" href="%s" target="_blank">Ver mais</a>',
            $row->getLink());
        
        return sprintf(
            '<div class="changelog-title-container"><a href="%s" target="_blank" class="changelog-title">%s</a></div>
            <table class="content-table">
                <tbody>
                    <tr>
                        <td><span class="post-info-tag">Categoria </span> </td>
                        <td><div class="changelog-category-container"><p>%s</p></div></td>
                    </tr>
                    <tr>
                        <td><span class="post-info-tag">Data </span> </td>
                        <td><div class="changelog-date-container"><p>%s</p></div></td>
                    </tr>
                    <tr>
                        <td><span class="post-info-tag">Conteúdo </span></td>
                        <td><div class="changelog-content">%s</div></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="changelog-see-more-container">%s</div>
                        </td>
                    </tr>
                </tbody>
            </table>',
            $row->getLink(),
            ucwords($row->getData("title/rendered")),
            trim($columnData, " / "),
            $this->treatDate($row->getModified()),
            $rowContent,
            $showMoreLink);
    }

    public function treatDate($date)
    {
        $timestamp = strtotime($date);
        $finalDate = date("d/m/Y H:i", $timestamp);
        return sprintf("%s", $finalDate);
    }
}