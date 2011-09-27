<?php

class Application_Model_HandoffTargetLanguagesHistory
{
	private $_handOffTargetLanguageTrackingID;
	private $_handOffID;
	private $_languageID;
	private $_handBackStringLocalization;
	private $_userID;
	private $_dateCreated;

	public function __construct(array $options = null){
		if(is_array($options)){
			$this->setOptions($options);
		}
	}
	
	public function __set($name, $value){
		$method = 'set' . $name;
		if ('mapper' == $name || !method_exists($this, $method)){
			throw new Exception('Invalid handofftargetlanguageshistory property');
		}
		
		$this->$method($value);
	}
		
	public function __get($name){
		$method = 'get' . $name;

		if (('mapper' == $name) || !method_exists($this, $method)){
			throw new Exception('Invalid handofftargetlanguageshistory property');
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

	public function setHandOffTargetLanguageTrackingID($handOffTargetLanguageTrackingID){
		$this->_handOffTargetLanguageTrackingID = (int)$handOffTargetLanguageTrackingID;
		return $this;
	}
	
	public function getHandOffTargetLanguageTrackingID(){
		return $this->_handOffTargetLanguageTrackingID;
	}
	
	public function setHandOffID($_handOffID){
		$this->_handOffID = (int)$_handOffID;
		
		return $this;
	}
	
	public function getHandOffID(){
		return $this->_handOffID;
	}	

	public function setLanguageID($languageID){
		$this->_languageID = (int)$languageID;
		
		return $this;
	}
	
	public function getLanguageID(){
		return $this->_languageID;
	}

	public function setHandBackStringLocalization($handBackStringLocalization){
		$this->_handBackStringLocalization = (string)$handBackStringLocalization;
		
		return $this;
	}
	
	public function getHandBackStringLocalization(){
		return $this->_handBackStringLocalization;
	}
			
	public function setUserID($userID){
		$this->_userID = (int)$userID;
		
		return $this;
	}
	
	public function getUserID(){
		return $this->_userID;
	}
	
	public function getDateCreated(){
		return $this->_dateCreated;
	}
		
	public function setDateCreated($dateCreated){
		$this->_dateCreated = $dateCreated;
		return $this;
	}
}

