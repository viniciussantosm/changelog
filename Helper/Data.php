<?php

class BIS2BIS_Changelog_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getResource($name)
    {
        if(!$this->checkForChanges()) {
            if($this->checkCache($this->getConfig()->getCacheKey($name))) {
                return unserialize(Mage::app()->getCache()->load($this->getConfig()->getCacheKey($name)));
            }
        }
        $collection = Mage::getModel(sprintf("changelog/resource_%s_collection", $name));
        $collection = $collection->loadData();
        $this->saveCache($collection, $this->getConfig()->getCacheKey($name), [Mage_Core_Model_App::CACHE_TAG]);
        return $collection;
    }
    
    public function saveCache($data, $key, $tag)
    {
        Mage::app()->getCache()->save(serialize($data), $key, [$tag], 3600);
    }

    public function checkCache($key)
    {
        return Mage::app()->getCache()->load($key);
    }

    /**
     * @return BIS2BIS_Changelog_Helper_Config
     */
    public function getConfig()
    {
        return Mage::helper("changelog/config");
    }

    public function checkForChanges()
    {
        $activeCategory = $this->getConfig()->getActiveCategory();
        $cachedActiveCategory = unserialize($this->checkCache($this->getConfig()->getCacheKey("activeCategory")));
        $blogUrl = $this->getConfig()->getBlogUrl();
        $cachedBlogUrl = unserialize($this->checkCache($this->getConfig()->getCacheKey("blogUrl")));

        if($blogUrl == $cachedBlogUrl && $activeCategory == $cachedActiveCategory) {
            return false;
        }
        $this->saveCache($activeCategory, $this->getConfig()->getCacheKey("activeCategory"), [Mage_Core_Model_App::CACHE_TAG]);
        $this->saveCache($blogUrl, $this->getConfig()->getCacheKey("blogUrl"), [Mage_Core_Model_App::CACHE_TAG]);
    
        return true;
    }
}