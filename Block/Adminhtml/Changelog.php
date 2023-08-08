<?php

class BIS2BIS_Changelog_Block_Adminhtml_Changelog extends Mage_Adminhtml_Block_Dashboard_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('changelog');
        $this->_emptyText = Mage::helper('changelog')->__('No posts found.');
    }

    protected function _prepareCollection()
    {
        // $result = Mage::getModel("changelog/post_fetch")->fetchPosts();
        // echo "<pre>";
        // print_r(unserialize($collection));
        // exit();
        // $helper = Mage::helper("changelog/config");
        // $ActiveColumns = $helper->getActiveColumns();

        $helper = Mage::helper("changelog/config");
        if(!$helper->isAllowed()) {
            return parent::_prepareCollection();
        }
        $collection = Mage::getModel("changelog/post_postCommand")->execute();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $helper = Mage::helper("changelog/config");
        $data = explode(",", $helper->getActiveColumns());
        
        foreach($data as $index => $column) {

            if($column === 'modified') {
                $this->addColumn($column, array(
                    'header'    => $this->__(ucfirst($column)),
                    'sortable'  => false,
                    'index'     => $column,
                    'type' => 'datetime'
                ));

                continue;
            }

            $this->addColumn($column, array(
                'header'    => $this->__(ucfirst($column)),
                'sortable'  => false,
                'index'     => $column,
                'renderer' => "BIS2BIS_Changelog_Block_Adminhtml_Column_Renderer_$column"
            ));
        }

        $this->setFilterVisibility(false);
        $this->setPagerVisibility(false);

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        // return $this->getUrl('*/catalog_search/edit', array('id'=>$row->getId()));
        return false;
    }
}