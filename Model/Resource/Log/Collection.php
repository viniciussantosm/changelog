<?php

class BIS2BIS_Changelog_Model_Resource_Log_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init(
            BIS2BIS_Changelog_Interfaces_LogInterface::LOGRESOURCE
        );
    }
}