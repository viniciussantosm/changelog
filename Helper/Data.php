<?php

class BIS2BIS_Changelog_Helper_Data extends Mage_Core_Helper_Abstract {

	public function makeLog($log)
	{		
        try{
                $changelog = Mage::getModel('changelog/log');
                if($changelog)
                {
                    $changelog->setLog($log);
                    
                    if($changelog->save())
                    {
                        return true;
                    }
                }
                return false;
                
            } catch (Exception $e){
                Mage::log($e->getMessage(), null, 'Changelog-error.log', true);
        }
	}

}