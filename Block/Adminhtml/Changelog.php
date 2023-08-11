<?php

class BIS2BIS_Changelog_Block_Adminhtml_Changelog extends Mage_Adminhtml_Block_Dashboard_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->_emptyText = Mage::helper('changelog')->__('No posts found.');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel("changelog/post")
            ->getCollection()
            ->getResource()
            ->addFilter("per_page", 5)
            ->addFilter("categories", implode(",", $this->getConfig()->getActiveCategory()))
            ->addFilter("orderby", "modified")
            ->setOrder("modified", "DESC");

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $helper = Mage::helper("changelog/config");
        $data = explode(",", $helper->getActiveColumns());
        
        foreach($data as $index => $column) {
            $this->addColumn($column, array(
                'header'    => $this->__(ucfirst($column)),
                'sortable'  => false,
                'index'     => $column,
                'renderer' => "BIS2BIS_Changelog_Block_Adminhtml_Column_Renderer_$column",
                'header_css_class'=>'a-center'
            ));
        }

        $this->setFilterVisibility(false);
        $this->setPagerVisibility(false);

        return parent::_prepareColumns();
    }

    public function prepareCategories($categories, $collection)
    {
        $categoryInfo = array();
        foreach($categories as $category) {
            $category = $collection->getItemByColumnValue("id", $category);
            $categoryInfo[] = [$category->getName(), $category->getLink()];
        }

        return $categoryInfo;
    }

    public function prepareAuthor($authorId, $collection)
    {
        $author = $collection->getItemByColumnValue("id", $authorId);
        $authorInfo = array();
        if($author) {
            $authorInfo = [$author->getName(), $author->getLink()];
            return $authorInfo;
        }

        return null;
    }

    /**
     * @return BIS2BIS_Changelog_Helper_Config
     */
    public function getConfig()
    {
        return Mage::helper("changelog/config");
    }
}