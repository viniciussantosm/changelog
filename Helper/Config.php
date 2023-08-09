<?php

class BIS2BIS_Changelog_Helper_Config extends Mage_Core_Helper_Abstract {

    public function isAllowed()
    {
        return Mage::getStoreConfig("changelog/settings/enabled");
    }

    public function getActiveCategory()
    {
        $activeCategory = explode(",", Mage::getStoreConfig("changelog/settings/category"));
        return $activeCategory;
    }

    public function getBlogUrl()
    {
        return Mage::getStoreConfig("changelog/settings/url");
    }

    public function getWpUser()
    {
        return Mage::getStoreConfig("changelog/settings/wplogin");
    }

    public function getWpPass()
    {
        return Mage::getStoreConfig("changelog/settings/wppassword");
    }

    public function getActiveColumns()
    {
        return Mage::getStoreConfig("changelog/settings/gridoptions");
    }
}