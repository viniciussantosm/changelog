<?php

class BIS2BIS_Changelog_Block_Adminhtml_Changelog extends Mage_Core_Block_Template
{
    protected $_template = "BIS2BIS_Changelog::web/admin/Changelog.phtml";

    public function getPostsCollection()
    {
        $collection = Mage::getModel("changelog/post")
            ->getCollection()
            ->getResource()
            ->addFilter("per_page", 5)
            ->addFilter("categories", implode(",", $this->getConfig()->getActiveCategory()))
            ->addFilter("orderby", "modified")
            ->setOrder("modified", "DESC");

        return $collection;
    }

    public function getLogsCollection()
    {
        $collection = Mage::getModel("changelog/log")
            ->getCollection();

        return $collection;
    }

    /**
     * @return BIS2BIS_Changelog_Helper_Config
     */
    public function getConfig()
    {
        return Mage::helper("changelog/config");
    }

    public function getFirstParagraph($post)
    {
        $postContent = $post->getData("content/rendered");
        if(strlen($postContent) >= 300) {
            $postContent = explode(PHP_EOL, strip_tags($postContent));
            $postContent = sprintf("<p>%s</p>", str_replace(" ", "", $postContent[0]) == "" ? $postContent[1] : $postContent[0]);
        }

        return $postContent;
    }

    public function treatDate($date)
    {
        $timestamp = strtotime($date);
        $finalDate = date("d/m/Y H:i", $timestamp);
        return sprintf("%s", $finalDate);
    }
}