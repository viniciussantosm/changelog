<?php

class BIS2BIS_Changelog_Model_Resource_Post_Collection extends Varien_Data_Collection {

    /**
     * Item object class name
     *
     * @var string
     */
    protected $_itemObjectClass = BIS2BIS_Changelog_Model_Post::class;

    /**
     * Load data
     *
     * @return  Varien_Data_Collection
     */
    public function loadData($printQuery = false, $logQuery = false)
    {
        if($this->isLoaded()) {
            return $this;
        }

        $items = $this->fetch();
        if(!is_array($items)) {
            return $items;
        }

        foreach ($items as $item) {
            $this->addItem((new BIS2BIS_Changelog_Model_Post($item)));
        }
        $this->_renderFilters();
        $this->_setIsLoaded(true);

        return $this;
    }


    public function load($printQuery = false, $logQuery = false){
        // We add the renders
        $this->_renderFilters()->_renderOrders()->_renderLimit();
        parent::load($printQuery,$logQuery);
    }

    public function addFieldToFilter($field, $condition = null)
    {
        $keyFilter = key($condition);
        $valueFilter = $condition[$keyFilter]->__toString();
        $this->addFilter($field,$valueFilter,'and');
        return $this;
    }

    /**
     * Add collection filter
     *
     * @param string $field
     * @param string $value
     * @param string $type and|or|string
     */
    public function addFilter($field, $value, $type = 'and')
    {
        $filter = new Varien_Object(); // implements ArrayAccess
        $filter['field']   = $field;
        $filter['value']   = $value;
        $filter['type']    = strtolower($type);

        $this->_filters[] = $filter;
        $this->_isFiltersRendered = false;
        return $this;
    }

    protected function _renderFilters()
    {
        // If elements are already filtered, return this
        if ($this->_isFiltersRendered) {
            return $this;
        }
 
        foreach($this->_filters AS $filter){
            $keyFilter = $filter->getData()['field'];
            $valueFilter = substr($filter->getData()['value'],2,-2); // Delete '% AND %' of the string
            $condFilter = $filter->getData()['type']; // not used in this example
 
            // Loop you're item collection
            foreach($this->_items AS $key => $item){
                // NOTE : $item->getGeneric is a function in your object, i gave you the code at the top 
                //of the article
 
                // If it's not an array, we use the search term to compare with the value of our item
                if(!is_array($item->getGeneric($keyFilter))){
                    if(!(strpos(strtolower($item->getGeneric($keyFilter)),strtolower($valueFilter)) !== FALSE)){
                        unset($this->_items[$key]); 
                        // If search term not founded, unset the item to 
                        //not display it!
                    }
                } else {
                    // If it's an array
                    $founded = false;
                    foreach($item->getGeneric($keyFilter) AS $valeur){
                        if(strpos(strtolower($valeur),strtolower($valueFilter)) !== FALSE){
                            $founded = true;
                        }
                    }
                    if(!$founded)
                        unset($this->_items[$key]); // Not founded in the array, so unset the item
                }
            }
 
        }
 
        $this->_isFiltersRendered = true;
        return $this;
    }

    protected function _renderOrders()
    {       
        $keySort = key($this->_orders);
        $keyDirection = $this->_orders[$keySort];
        // We sort our items tab with a custom function AT THE BOTTOM OF THIS CODE
        usort($this->_items, $this->_build_sorter($keySort,$keyDirection));       
        return $this;
    }

    protected function _build_sorter($key,$direction) {
        return function ($a, $b) use ($key,$direction) {
            if ($direction == self::SORT_ORDER_ASC)
                return strnatcmp($a[$key], $b[$key]); // Natural comparaison of string
            else
                return -1 * strnatcmp($a[$key], $b[$key]); // reverse the result if sort order desc !
        };
    }

    protected function _renderLimit()
    {
        $this->_totalRecords = sizeof($this->_items);

        if($this->_pageSize){
            $this->_items = array_slice($this->_items, ($this->getCurPage()-1) * $this->_pageSize, $this->_pageSize);
        }

        return $this;
    }

    /**
     * @return BIS2BIS_Changelog_Helper_Config
     */
    public function getConfig()
    {
        return Mage::helper("changelog/config");
    }

    public function fetch()
    {
        $curl = curl_init();

        $params = [];
        foreach($this->_filters as $value) {
            $params[$value->getField()] = $value->getValue();
        }

        $url = sprintf(
            "%s/wp-json/wp/v2/posts%s",
            $this->getConfig()->getBlogUrl(),
            empty($params) ? "" : "?" . http_build_query($params)
        );

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        curl_close($curl);

        
        return json_decode($response, 1);
    }

    public function getResource()
    {
        if($this->checkCache("postCollection")) {
            return unserialize(Mage::app()->getCache()->load("postCollection"));
        }
        $this->saveCache($this, "postCollection", [Mage_Core_Model_Config::CACHE_TAG]);
        return $this;
    }
    
    public function saveCache($data, $key, $tag)
    {
        Mage::app()->getCache()->save(serialize($data), $key, [$tag], 3600);
    }

    public function checkCache($key)
    {
        return Mage::app()->getCache()->load($key);
    }
}