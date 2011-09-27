<?php

require_once ('Zend/Controller/Plugin/Abstract.php');

class Application_Plugin_CheckACL extends  Zend_Controller_Plugin_Abstract
{
	public function postDispatch(Zend_Controller_Request_Abstract $request) {
		parent::postDispatch($request);	
	}

	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {						
		$auth = Zend_Auth::getInstance();					
		if(!$auth->hasIdentity() || strtolower($request->getControllerName())=="auth"){			
			return;
		}
		
		$acl = Zend_Registry::get('acl');	
		
		switch($auth->getIdentity()->UserRole){
			case Application_Model_DbTable_Users::USER_ADMIN:			
				$roleName = "jonckersadmin";
				break;
			case Application_Model_DbTable_Users::USER_JTEPM:
				$roleName = "jonckerspm";
				break;
			case Application_Model_DbTable_Users::USER_TRANSLATOR:
				$roleName = "translator";
				break;
			case Application_Model_DbTable_Users::USER_CLIENT:
				$roleName = "client";
				break;
			default:
				$roleName = "anonymous";
				break;
		}

		if(!$acl->isAllowed($roleName, strtolower($request->getControllerName()), strtolower($request->getActionName()))){		
			$request->setControllerName('Error');
			$request->setActionName('index');
		}		
	} 	  
}