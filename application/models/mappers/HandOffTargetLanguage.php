<?php

class Application_Model_Mapper_HandOffTargetLanguage{
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
            $this->setDbTable('Application_Model_DbTable_HandOffTargetLanguages');
        }
        return $this->_dbTable;
    }
		
	public function getArrayTargetLanguages($handOffID){
		$handofftargetlanguages = $this->getDbTable()->fetchAll($this->getDbTable()->select()->from($this->getDbTable(), array("LanguageID"))->where("HandOffID = ?", (int)$handOffID));		
		$entries   = array();		
		foreach ($handofftargetlanguages as $row) {
			$entries[] = $row->LanguageID;
		}
		
		return $entries;		
	}

	public function getTargetLanguages($handOffID){
		$handofftargetlanguages = $this->getDbTable()->fetchAll($this->getDbTable()->select()
																	 ->where('HandOffID = ?', (int)$handOffID));
		return $this->CreateArrayResult($handofftargetlanguages);
	}
	
	public function getTargetLanguageNames($handOffID){
		$languageMapper = new Application_Model_Mapper_Language();
		$language = new Application_Model_Language();
		$listLanguage = array();
		$handofftargetlanguages = $this->getDbTable()->fetchAll($this->getDbTable()->select()
																	 ->where('HandOffID = ?', (int)$handOffID));

		foreach($handofftargetlanguages as $handofftargetlanguage){
			$languageMapper->find($handofftargetlanguage->LanguageID, $language);
			$listLanguage[] = $language->LanguageName;
		}
		return implode(", ", $listLanguage);
	}
	
	public function getTotalLanguageNeedLocalized($handOffID) 
    {
		if($handOffID == null){
			return 0;
		}
		
        $select = $this->getDbTable()->select();
        $select->from($this->getDbTable(), array('count(*) as amount'));
		$select->where("HandOffID = ?", $handOffID);        
		$rows = $this->getDbTable()->fetchAll($select);        
		
        return(count($rows) > 0 ? $rows[0]->amount : 0);        
    }
	
	public function getTotalLanguageFinishedLocalized($handOffID){
		if($handOffID == null){
			return 0;
		}
	
		$select = $this->getDbTable()->select();
        $select->from($this->getDbTable(), array('count(*) as amount'));
		$select->where("HandOffID = ? and HandBackStringLocalization is not null", $handOffID);        		
		$rows = $this->getDbTable()->fetchAll($select);
        
		return(count($rows) > 0 ? $rows[0]->amount : 0);      		
	}
	
	public function save($handOffID, $languageID, $handBackStringLocalization, $translatedByTranslator){			
		$data = array(
            'HandOffID'   => $handOffID,
            'LanguageID' => $languageID,
			'TranslatedByTranslator' => $translatedByTranslator
        );
		
		if($handBackStringLocalization == null){
			return $this->getDbTable()->insert($data);
		}
		$data['HandBackStringLocalization'] = $handBackStringLocalization;
		
		$where = array();
		$where[] = $this->getDbTable()->getAdapter()->quoteInto(" HandOffID = ?", $handOffID);
		$where[] = $this->getDbTable()->getAdapter()->quoteInto(" LanguageID = ?", $languageID);
		
		return $this->getDbTable()->update($data, $where);
	}
	
	
	public function delete($handOffID, $languageID){		
		$languages = $this->getDbTable()->fetchAll($this->getDbTable()->select()
														->where(' HandOffID = ?', (int)$handOffID)
														->where(' LanguageID = ?', (int)$languageID));		
		if(count($languages) == 0){
			return;
		}		
		
		$languages[0]->delete();		
	}
		
	private function CreateArrayResult($resultSet){
		$entries   = array();
		$hoMapper = new Application_Model_Mapper_HO();		
		$languageMapper = new Application_Model_Mapper_Language();
		$language = new Application_Model_Language();
		$ho = new Application_Model_HO();
		
		foreach ($resultSet as $row) {			
			$hoMapper->find($row->HandOffID, $ho);
			$entry = new Application_Model_HandoffTargetLanguage();
			$languageMapper->find($row->LanguageID, $language);
			
			$entry->setHandOffID($row->HandOffID)
				  ->setLanguageID($row->LanguageID)
				  ->setLanguageName($language->LanguageName)
				  ->setHandBackStringLocalization($row->HandBackStringLocalization)
				  ->setTranslatedByTranslator($row->TranslatedByTranslator)
				  ->setAllowEdit($hoMapper->isAllowEditTargetLocalizationString($ho->HandOffSourceLanguageID, $row->LanguageID));
			
			$entries[] = $entry;
		}
		
		return $entries;
	}
}