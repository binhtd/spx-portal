<?php

class Application_Model_Mapper_HandoffTargetLanguagesHistory{
	protected $_dbTable;
 
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_HandoffTargetLanguagesHistory');
        }
        return $this->_dbTable;
    }
			
	public function save(Application_Model_HandoffTargetLanguagesHistory $handoffTargetLanguagesHistory){			
		$data = array(
           	"HandOffTargetLanguageTrackingID" => $handoffTargetLanguagesHistory->HandOffTargetLanguageTrackingID,
			"HandOffID" => $handoffTargetLanguagesHistory->HandOffID,
			"LanguageID" => $handoffTargetLanguagesHistory->LanguageID,
			"HandBackStringLocalization" => $handoffTargetLanguagesHistory->HandBackStringLocalization,
			"UserID" => $handoffTargetLanguagesHistory->UserID,
			"DateCreated" => $handoffTargetLanguagesHistory->DateCreated
        );	
 
		if (null === ($handOffTargetLanguageTrackingID = $handoffTargetLanguagesHistory->getHandOffTargetLanguageTrackingID())) {
            unset($data['HandOffTargetLanguageTrackingID']);
            return $this->getDbTable()->insert($data);
        } 

		return $this->getDbTable()->update($data, array('HandOffTargetLanguageTrackingID = ?' => $handOffTargetLanguageTrackingID));
	}
}