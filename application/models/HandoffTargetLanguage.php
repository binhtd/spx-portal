<?php

class Application_Model_HandoffTargetLanguage
{
	private $_handOffID;
	private $_languageID;
	private $_languageName;
	private $_handBackStringLocalization;
	private $_translatedByTranslator;
	private $_allowEdit;
	

	public function __construct(array $options = null){
		if(is_array($options)){
			$this->setOptions($options);
		}
	}
	
	public function __set($name, $value){
		$method = 'set' . $name;
		if ('mapper' == $name || !method_exists($this, $method)){
			throw new Exception('Invalid HandoffTargetLanguage property');
		}
		
		$this->$method($value);
	}
		
	public function __get($name){
		$method = 'get' . $name;

		if (('mapper' == $name) || !method_exists($this, $method)){
			throw new Exception('Invalid HandoffTargetLanguage property');
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
	
	public function setLanguageID($languageID){
		$this->_languageID = (int)$languageID;
		
		return $this;
	}
	
	public function getLanguageID(){
		return $this->_languageID;
	}
	
	public function setLanguageName($languageName){
		$this->_languageName = (string)$languageName;
		
		return $this;
	}
	
	public function getLanguageName(){
		return $this->_languageName;
	}
		
	public function setHandBackStringLocalization($handBackStringLocalization){
		$this->_handBackStringLocalization = $handBackStringLocalization;
		return $this;
	}
	
	public function getHandBackStringLocalization(){
		return $this->_handBackStringLocalization;
	}
	
	public function setTranslatedByTranslator($translatedByTranslator){
		$this->_translatedByTranslator = (string)$translatedByTranslator;
		return $this;
	}
	
	public function getTranslatedByTranslator(){
		return $this->_translatedByTranslator;
	}
	
	public function setallowEdit($allowEdit){
		$this->_allowEdit = (bool)$allowEdit;
		
		return $this;
	}
	
	public function getAllowEdit(){
		return $this->_allowEdit;
	}
}

