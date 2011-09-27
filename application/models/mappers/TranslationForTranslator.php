<?php

class Application_Model_Mapper_TranslationForTranslator{
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
            $this->setDbTable('Application_Model_DbTable_TranslationForTranslators');
        }
        return $this->_dbTable;
    }
		
	
	public function find($translationForTranslatorsID, Application_Model_TranslationForTranslator $translationForTranslator){
		$result = $this->getDbTable()->find($translationForTranslatorsID); 
		if (0 == count($result)){
			return;
		}		
		
		$row = $result->current();
        $translationForTranslator->setTranslationForTranslatorsID($row->TranslationForTranslatorsID)
                  ->setUserID($row->UserID)
                  ->setHandBackStringLocalization($row->HandBackStringLocalization)
				  ->setHandOffID($row->HandOffID);
	}
	

	public function save(Application_Model_TranslationForTranslator $translationForTranslator){			
		$data = array(
            'TranslationForTranslatorsID'   => $translationForTranslator->getTranslationForTranslatorsID(),
            'UserID' => $translationForTranslator->getUserID(),
            'HandBackStringLocalization' => $translationForTranslator->getHandBackStringLocalization(),
			'HandOffID' => $translationForTranslator->getHandOffID()			
        );

        if (null === ($translationForTranslatorsID = $translationForTranslator->getTranslationForTranslatorsID())) {
            unset($data['TranslationForTranslatorsID']);
            return $this->getDbTable()->insert($data);
        }
		
        return  $this->getDbTable()->update($data, array('TranslationForTranslatorsID = ?' => $translationForTranslatorsID));
	}
	
	
	public function deleteTranslationForTranslators($translationForTranslatorsID){		
		$translationForTranslators = $this->getDbTable()->find((int)$translationForTranslatorsID);
		$translationForTranslators[0]->delete();
	}
	
	private function CreateResultSetEntity($resultSet){
		$entries   = array();
		
		foreach ($resultSet as $row) {
			$entry = new Application_Model_TranslationForTranslator();
			$entry->setTranslationForTranslatorsID($row->TranslationForTranslatorsID)
				  ->setUserID($row->UserID)
				  ->setHandBackStringLocalization($row->HandBackStringLocalization)
				  ->setHandOffID($row->HandOffID);
				  
			$entries[] = $entry;
		}
		
		return $entries;
	}
}