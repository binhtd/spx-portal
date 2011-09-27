<?php

class Application_Model_User
{
	private $_userID;
	private $_userName;
	private $_userEmail;
	private $_userLoginName;
	private $_userPassword;	
	private $_userIsActive;
	private $_userRole;
	private $_jtepmEmail;
	private $_allowModifyUserRecord;
		
	public function __construct(array $options = null){
		if(is_array($options)){
			$this->setOptions($options);
		}
	}
	
	public function __set($name, $value){
		$method = 'set' . $name;
		if ('mapper' == $name || !method_exists($this, $method)){
			throw new Exception('Invalid user property');
		}
		
		$this->$method($value);
	}
		
	public function __get($name){
		$method = 'get' . $name;

		if (('mapper' == $name) || !method_exists($this, $method)){
			throw new Exception('Invalid user property');
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
	
	public function setUserID($userID){
		$this->_userID = (int)$userID;
		return $this;
	}
	
	public function getUserID(){
		return $this->_userID;
	}
	
	public function setUserName($userName){
		$this->_userName = (string)$userName;
		
		return $this;
	}
	
	public function getUserName(){
		return $this->_userName;
	}
	
	public function setUserEmail($userEmail){
		$this->_userEmail = (string)$userEmail;
		return $this;
	}
	
	public function getUserEmail(){
		return $this->_userEmail;
	}

	public function setUserLoginName($userLoginName){
		$this->_userLoginName = (string)$userLoginName;
		return $this;
	}
	
	public function getUserLoginName(){
		return $this->_userLoginName;
	}
	
	public function setUserPassword($userPassword){
		$this->_userPassword = (string)$userPassword;
		return $this;
	}
	
	public function getUserPassword(){
		return $this->_userPassword;
	}
	
	public function setUserRole($userRole){
		$this->_userRole = (string)$userRole;
		return $this;
	}
	
	public function getUserRole(){
		return $this->_userRole;
	}
	
	public function setUserIsActive($userIsActive){
		$this->_userIsActive = (string)$userIsActive;
		return $this;
	}
	
	public function getUserIsActive(){
		return $this->_userIsActive;
	}
		
	public function setJtepmEmail($jtepmEmail){
		$this->_jtepmEmail = $jtepmEmail;
		return $this;
	}
	
	public function getJtepmEmail(){
		return $this->_jtepmEmail;
	}
	
	public function setAllowModifyUserRecord($allowModifyUserRecord){
		$this->_allowModifyUserRecord = (bool)$allowModifyUserRecord;
		return $this;
	}
	
	public function getAllowModifyUserRecord(){
		return $this->_allowModifyUserRecord;
	}	
}

