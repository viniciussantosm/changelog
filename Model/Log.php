<?php

class BIS2BIS_Changelog_Model_Log extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->model = new Varien_Data_Collection_Db();
        $this->model->__construct(
            Mage::getSingleTon('core/resource')
                ->getConnection('changelog_write')
        );
        $this->_init(BIS2BIS_Changelog_Interfaces_LogInterface::LOGRESOURCE);
    }
}