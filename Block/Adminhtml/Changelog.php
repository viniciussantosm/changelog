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
        $data = Mage::helper("changelog/data");
        $collection = $data->getResource("post");
        $authorCollection = $data->getResource("author");
        $categoryCollection = $data->getResource("category");
        
        foreach($collection as $post) {
            $authorData = $this->prepareAuthor($post->getAuthor(), $authorCollection);
            $post->setAuthor($authorData);
            $categoryData = $this->prepareCategories($post->getCategories(), $categoryCollection);
            $post->setCategories($categoryData);
            $post->setTitle($post->getTitle()["rendered"]);
        }

        $collection->setOrder("modified", "DESC");
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
}