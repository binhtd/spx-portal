<?php

class Application_Model_TranslationForTranslator
{
	private $_translationForTranslatorsID;
	private $_userID;
	private $_handBackStringLocalization;
	private $_handOffID;
	
	public function __construct(array $options = null){
		if (is_array($options)){
			$this->setOptions($options);
		}
	}
	
	public function __set($name, $value){
		$method = 'set' . $name;
		
		if (('mapper' == $name)  || !method_exists($this, $method)){
			throw new Exception('Invalid language property');
		}
		
		$this->$method($value);
	}
	
	public function __get($name){
		$method = 'get' . $name;
		
		if (('mapper' == $name) || !method_exists($this, $method)){
			throw new Exception('Invalid language property');
		}
		
		return $this->$method();
	}
	
	public function setOptions(array $options){
		$methods = get_class_methods($this);
		foreach($options as $key => $value){
			$method = 'set' . ucfirst($key);
			if(in_array($method, $methods)){
				$this->$method($value);
			}
		}
		return $this;
	}
	
	public function setTranslationForTranslatorsID($translationForTranslatorsID){
		$this->_translationForTranslatorsID = (int)$translationForTranslatorsID;
		return $this;
	}
	
	public function getTranslationForTranslatorsID(){
		return $this->_translationForTranslatorsID;
	}
	
	public function setUserID($userID){
		$this->_userID = (int)$userID;
		return $this;
	}
	
	public function getUserID(){
		return $this->_userID;
	}

	public function setHandBackStringLocalization($handBackStringLocalization){
		$this->_handBackStringLocalization = (string)$handBackStringLocalization;;
		return $this;
	}	
	
	public function getHandBackStringLocalization(){
		return $this->_handBackStringLocalization;
	}	
	
	public function setHandOffID($handOffID){
		$this->_handOffID = (int)$handOffID;
		return $this;
	}
	
	public function getHandOffID(){
		return $this->_handOffID;
	}
}
