<?php

class BIS2BIS_Changelog_Block_Adminhtml_Rewrite_Dashboard extends Mage_Adminhtml_Block_Dashboard {

    protected function _prepareLayout()
    {   
        $this->setChild('changelog',
            $this->getLayout()->createBlock('changelog/adminhtml_changelog')
        );
        
        parent::_prepareLayout();
    }
}