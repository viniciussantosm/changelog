<?php

class BIS2BIS_Changelog_Model_Resource_Log extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init(
            BIS2BIS_Changelog_Interfaces_LogInterface::LOGRESOURCE,
            BIS2BIS_Changelog_Interfaces_LogInterface::ID
        );
    }
}