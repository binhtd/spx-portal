<?php

class Application_Model_HO
{
	private $_handOffID;
	private $_userID;
	private $_userName;
	private $_handOffTitle;
	private $_handOffStartProject;
	private $_handOffClosedDate;
	private $_handOffStatus;
	private $_handOffSourceLanguageID;
	private $_handOffSourceLanguageName;
	private $_handOffTargetLanguageName;
	private $_handOffInstruction;
	private $_totalLanguageNeedLocalized;
	private $_totalLanguageFinishedLocalized;
	private $_allowEditHoRecord;
	private $_allowDeleteHoRecord;
	private $_allowShowEditDepentOnRoleAndHoStatus;
	private $_signatureName;
	
	public function __construct(array $options = null){
		if(is_array($options)){
			$this->setOptions($options);
		}
	}
	
	public function __set($name, $value){
		$method = 'set' . $name;
		if ('mapper' == $name || !method_exists($this, $method)){
			throw new Exception('Invalid ho property');
		}
		
		$this->$method($value);
	}
		
	public function __get($name){
		$method = 'get' . $name;

		if (('mapper' == $name) || !method_exists($this, $method)){
			throw new Exception('Invalid ho property');
		}
		
		return $this->$method();
	}
	
	public function setOptions(array $options){
		$methods = get_class_methods($this);
		foreach($methods as $key => $value){
			$method = 'set' . ucfirst($key);			
			if(in_array($method, $methods)){
				$this->$method($value);
			}
		}
		return $this;
	}
	
	public function setHandOffID($handOffID){
		$this->_handOffID = (int)$handOffID;
		return $this;
	}
	
	public function getHandOffID(){
		return $this->_handOffID;
	}
	
	public function setUserID($userID){
		$this->_userID = (int)$userID;
		
		return $this;
	}
	
	public function getUserID(){
		return $this->_userID;
	}

	public function setHandOffTitle($handOffTitle){
		$this->_handOffTitle = (string)$handOffTitle;
		
		return $this;
	}
	
	public function getHandOffTitle(){
		return $this->_handOffTitle;
	}
		
	public function setHandOffStartProject($handOffStartProject){
		$this->_handOffStartProject = (string)$handOffStartProject;
		
		return $this;
	}
	
	public function getHandOffStartProject(){
		return $this->_handOffStartProject;
	}
	
	public function setHandOffClosedDate($handOffClosedDate){
		$this->_handOffClosedDate = $handOffClosedDate;
		
		return $this;
	}
	
	public function getHandOffClosedDate(){
		return $this->_handOffClosedDate;
	}
	
	public function setHandOffStatus($handOffStatus){
		$this->_handOffStatus = (string)$handOffStatus;
		
		return $this;
	}
	
	public function getHandOffStatus(){
		return $this->_handOffStatus;
	}
	
	public function setHandOffSourceLanguageID($handOffSourceLanguageID){
		$this->_handOffSourceLanguageID = (int)$handOffSourceLanguageID;
		
		return $this;
	}
	
	public function getHandOffSourceLanguageID(){
		return $this->_handOffSourceLanguageID;
	}
	
	public function setHandOffSourceLanguageName($handOffSourceLanguageName){
		$this->_handOffSourceLanguageName = (string)$handOffSourceLanguageName;
		
		return $this;
	}
	
	public function getHandOffSourceLanguageName(){
		return $this->_handOffSourceLanguageName;
	}
	
	public function setHandOffTargetLanguageName($handOffTargetLanguageName){
		$this->_handOffTargetLanguageName = (string)$handOffTargetLanguageName;
		
		return $this;
	}
	
	public function getHandOffTargetLanguageName(){
		return $this->_handOffTargetLanguageName;
	}
			
	public function setHandOffInstruction($handOffInstruction){
		$this->_handOffInstruction = (string)$handOffInstruction;
		
		return $this;
	}
	
	public function getHandOffInstruction(){
		return $this->_handOffInstruction;
	}
	
	public function setTotalLanguageNeedLocalized($totalLanguageNeedLocalized){
		$this->_totalLanguageNeedLocalized = (int)$totalLanguageNeedLocalized;
		
		return $this;
	}
	
	public function getTotalLanguageNeedLocalized(){
		return $this->_totalLanguageNeedLocalized;
	}
	
	public function setTotalLanguageFinishedLocalized($totalLanguageFinishedLocalized){
		$this->_totalLanguageFinishedLocalized = (int)$totalLanguageFinishedLocalized;
		
		return $this;
	}
	
	public function getTotalLanguageFinishedLocalized(){
		return $this->_totalLanguageFinishedLocalized;
	}
	
	
	public function setAllowEditHoRecord($allowEditHoRecord){
		$this->_allowEditHoRecord = (bool)$allowEditHoRecord;
		
		return $this;
	}
	
	public function getAllowEditHoRecord(){
		return $this->_allowEditHoRecord;
	}
	
	public function setAllowDeleteHoRecord($allowDeleteHoRecord){
		$this->_allowDeleteHoRecord = (bool)$allowDeleteHoRecord;
		
		return $this;
	}
	
	public function getAllowDeleteHoRecord(){
		return $this->_allowDeleteHoRecord;
	}
	
	public function setAllowShowEditDepentOnRoleAndHoStatus($allowShowEditDepentOnRoleAndHoStatus){
		$this->_allowShowEditDepentOnRoleAndHoStatus = (bool)$allowShowEditDepentOnRoleAndHoStatus;
		
		return $this;
	}
	
	public function getAllowShowEditDepentOnRoleAndHoStatus(){
		return $this->_allowShowEditDepentOnRoleAndHoStatus;
	}
	
	public function setUserName($userName){
		$this->_userName = (string)$userName;
		
		return $this;
	}
	
	public function getUserName(){
		return $this->_userName;
	}
	
	public function setSignatureName($signatureName){
		$this->_signatureName = (string)$signatureName;
		
		return $this;
	}
	
	public function getSignatureName(){
		return $this->_signatureName;
	}
	
}

