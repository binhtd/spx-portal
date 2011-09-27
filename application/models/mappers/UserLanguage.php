<?php

class Application_Model_Mapper_UserLanguage{
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
            $this->setDbTable('Application_Model_DbTable_UserLanguages');
        }
        return $this->_dbTable;
    }
		
	
	public function getSourceLanguages($userID){
		$userLanguages = $this->getDbTable()->fetchAll($this->getDbTable()->select()->where('UserID = ?', (int)$userID));
				
		$entries   = array();		
		foreach ($userLanguages as $row) {
			$entries[] = $row->SourceLanguageID;
		}
		
		return $entries;				
	}
	
	public function getTargetLanguages($userID, $SourceLanguageID){
		$userLanguages = $this->getDbTable()->fetchAll($this->getDbTable()
															->select()
															->where('UserID = ?', (int)$userID)
															->where('SourceLanguageID=?', (int)$SourceLanguageID));		
		$targetLanguageList   = array();		
		
		
		$select = $this->getDbTable()->getAdapter()->select();				 
		$select = $select->from(array('l' => 'languages'));
		foreach ($userLanguages as $row) {		
			$select = $select->orWhere(" l.LanguageID = (?)", (int)$row->TargetLanguageID);
		}
		
		$resutls = $this->getDbTable()->getAdapter()->fetchAll($select);		
		$entry = array();	
		foreach($resutls as $row){
			
			$language = new Application_Model_Language();
			$language->setLanguageID($row["LanguageID"])
                     ->setLanguageName($row["LanguageName"]);	
			
			$entry[] = $language;	
		}
		
		return $entry;
	}

	public function getUserLanguageByUserIDAndArrayLanguageID($languageIDs){
		$userLanguages = $this->getDbTable()->fetchAll($this->getDbTable()->select()->from("userlanguages", "UserID")->where(
		"(LanguageID in (?)", implode(",", $languageIDs))->group("UserID"));
		$entries   = array();
		
		foreach ($userLanguages as $row) {
			$entries[] = $row->UserID;
		}
		
		return $entries;
	}
		
	public function getUserLanguageByUserIDAndLanguageID($userID, $sourceLanguageID, $targetLanguageID){	
		$userLanguages = $this->getDbTable()->fetchAll($this->getDbTable()->select()
															->where(' UserID = ?',(int)$userID )
															->where(' SourceLanguageID = ?' , (int)$sourceLanguageID)
															->where(' TargetLanguageID = ?' , (int)$targetLanguageID));
		return $this->CreateArrayResult($userLanguages);
	}
	
	public function getTargetLanguageNames($userID){
		$languageMapper = new Application_Model_Mapper_Language();		
		$listLanguage = array();
		$handofftargetlanguages = $this->getDbTable()->fetchAll($this->getDbTable()->select()->where('UserID = ?',(int)$userID));
		if (0 == count($handofftargetlanguages)){
			return;
		}
				
		foreach($handofftargetlanguages as $handofftargetlanguage){
			$languageSource = new Application_Model_Language();
			$languageTarget = new Application_Model_Language();
			$languageMapper->find($handofftargetlanguage->SourceLanguageID, $languageSource);
			$languageMapper->find($handofftargetlanguage->TargetLanguageID, $languageTarget);			
			$listLanguage[] = "[" . $languageSource->LanguageName . " => " . (string)$languageTarget->LanguageName . "]";
		}
		
		return implode(", ", $listLanguage);
	}
	
	public function findUserLanguageByUserIDAndSourceLanguageIDAndTargetLanguageID($userID, $sourceLanguageID, $targetLanguageID){
		$result = $this->getDbTable()->where("UserID=?", (int)$userID)
									 ->where("SourceLanguageID = ?", (int)$sourceLanguageID)
									 ->where("TargetLanguageID = ?", (int)$targetLanguageID); 
		
		if (0 == count($result)){
			return array();
		}		
		
		return $result;
	}
	
	public function save($userLanguageID, $userID, $sourceLanguageID, $targetLanguageID){			
		$data = array(
			'UserLanguageID' => $userLanguageID,
            'UserID'   => (int)$userID,
            'SourceLanguageID' => (int)$sourceLanguageID,
			'TargetLanguageID' => (int)$targetLanguageID
        );
		
		 if (null === ($ID = $userLanguageID)) {
            unset($data['UserLanguageID']);
            return $this->getDbTable()->insert($data);
        }
		
        return   $this->getDbTable()->update($data, array('UserLanguageID = ?' => $ID));				
	}
	
	
	public function delete($userID){		
		$languages = $this->getDbTable()->fetchAll($this->getDbTable()->select()->where('UserID = ?', (int)$userID));
		
		foreach($languages as $language){
			$language->delete();
		}
	}
		
	private function CreateArrayResult($resultSet){
		$entries   = array();
		
		foreach ($resultSet as $row) {
			$entries[] = $row->SourceLanguageID;
		}
		
		return $entries;
	}
}