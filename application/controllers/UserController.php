<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
		$auth = Zend_Auth::getInstance();
		$userMapper = new Application_Model_Mapper_User();		
		$this->view->activeUser = true;
		$this->view->allowModifyUser = ($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_ADMIN || $auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_JTEPM);
		
		$this->view->jtepmEmailList = $userMapper->getAvailableJtepmEmail();		
    }

    public function indexAction()
    {
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);			
        $userMapper = new Application_Model_Mapper_User();
		
		$this->view->paginator = $userMapper->getPaginator($this->getRequest()->getParam('page', 1), $this->getRequest()->getParam("limit", (int) $config->paginator->itemCountPerPage));	
		$this->view->resultSet = $userMapper->getPaginatorData($this->getRequest()->getParam('page', 1), $this->getRequest()->getParam("limit", (int) $config->paginator->itemCountPerPage));	 	
	}

    public function addAction()
    {
		$activityMapper = new Application_Model_Mapper_Activity();
		$activity = new Application_Model_Activity();
		$auth = Zend_Auth::getInstance();
		$userLanguageMapper = new Application_Model_Mapper_UserLanguage();
	
		$form = new Application_Form_User();		
		$form->submit->setLabel("Save");
		$this->view->form = $form;
		$userMapper = new Application_Model_Mapper_User();
		$user = new Application_Model_User();
		$languageMapper = new Application_Model_Mapper_Language();
		$this->view->languageIsShowInTranslatorList =  $languageMapper->getLanguageIsShowInTranslatorListActive();
		if(!($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_ADMIN || $auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_JTEPM)){
			throw new Exception("you do not have a permission to add new account");
		}
		
		if(!$this->getRequest()->isPost()){
			return;			
		}			

		if($this->getRequest()->getPost("UserRole") == Application_Model_DbTable_Users::USER_JTEPM){
			$form->JtepmEmail->setRequired(false);	
			$form->SourcelanguageID->setRequired(false);
		}
		
		if($this->getRequest()->getPost("UserRole") == Application_Model_DbTable_Users::USER_CLIENT){
			$form->SourcelanguageID->setRequired(false);
		}
		
		if($this->getRequest()->getPost("UserRole") == Application_Model_DbTable_Users::USER_TRANSLATOR){
			$form->JtepmEmail->setRequired(false);	
		}
		
		$formData = $this->getRequest()->getPost();
		if(!$form->isValid($formData)){
			$this->createSourceTargetLanguage($form);
			$form->populate($formData);
			return;
		}				
		
		if(0 <> count($userMapper->getUserByUserLoginName(0, $form->getValue("UserLoginName"))) ||  $userMapper->isEqualWithDefaultAdmin($form->getValue("UserLoginName"))){
			$this->view->insertErrorMessage = "can not insert duplicate user login name";			
			$form->populate($formData);
			$this->createSourceTargetLanguage($form);
			return;
		}
		
		if(0 <> count($userMapper->getUserByUserEmail($form->getValue("UserEmail"), 0))){
			$this->view->insertErrorMessage = "can not insert duplicate email address";
			$form->populate($formData);
			$this->createSourceTargetLanguage($form);
			return;
		}
				
			
		$user->UserName = $form->getValue("UserName");			
		$user->UserEmail = $form->getValue("UserEmail");			
		$user->UserLoginName = $form->getValue("UserLoginName");			
		$user->UserPassword = $form->getValue("UserPassword");		
		$user->UserRole = $form->getValue("UserRole");		
		$user->UserIsActive = $form->getValue("UserIsActive") == "Y" ? "Y" : "N";		
		if($form->getValue("UserRole") == Application_Model_DbTable_Users::USER_CLIENT){
			$user->JtepmEmail = $form->getValue("JtepmEmail");
		}
		
		$userID = $userMapper->save($user);
		
		if($form->getValue("UserRole") == Application_Model_DbTable_Users::USER_TRANSLATOR){
			foreach($form->getValue("SourcelanguageID") as $languageID){
				if($this->_getParam("hiddenSourceTargetLanguage_$languageID", "0") !="0"){
					$targetLanguage = split(",", $this->_getParam("hiddenSourceTargetLanguage_$languageID", 0));
				
					for($i=0; $i< count($targetLanguage); $i++){
						$userLanguageMapper->save(null, $userID, $languageID, $targetLanguage[$i]);	
					}
				}				
			}
		}
		
		$activity->setUserName($auth->getIdentity()->UserLoginName)
				  ->setUserActivity("User add UserID " .  $userID)
				  ->setUserActivityDateTime(date("Y-m-d : H:i:s", time()));
		$activityMapper->save($activity);	
		
		$this->_helper->redirector("index");
    }
	
	private function createTargetLanguageWhenEdit($form, $userID){
		$userLanguageMapper = new Application_Model_Mapper_UserLanguage;
		
		foreach($form->getValue("SourcelanguageID") as $sourceLanguageID){
			$targetLanguages = $userLanguageMapper->getTargetLanguages($userID, $sourceLanguageID);	
			$targetArray = Array();
			foreach($targetLanguages as $language){							
				$targetArray[] = $language->LanguageID;
			}	
			
			$form->addElement("hidden" , "hiddenSourceTargetLanguage_".$sourceLanguageID, array("value" => implode(",", $targetArray)));
			$form->getElement("hiddenSourceTargetLanguage_".$sourceLanguageID)
				 ->setDecorators(array('ViewHelper'));				
		}
		
		
		
	}
		
	private function createSourceTargetLanguage($form){
		foreach($form->getValue("SourcelanguageID") as $languageID){
			if($this->_getParam("hiddenSourceTargetLanguage_$languageID", "0") !="0"){
				$form->addElement("hidden" , "hiddenSourceTargetLanguage_".$languageID, array("value" => $this->_getParam("hiddenSourceTargetLanguage_$languageID", 0)));
				$form->getElement("hiddenSourceTargetLanguage_".$languageID)
					 ->setDecorators(array('ViewHelper'));
			}				
		}
	}

    public function editAction()
    {
		$activityMapper = new Application_Model_Mapper_Activity();
		$activity = new Application_Model_Activity();
		$auth = Zend_Auth::getInstance();
		$userLanguageMapper = new Application_Model_Mapper_UserLanguage();
		
		$form = new Application_Form_User();			
		$form->submit->setLabel("Save");
		$this->view->form = $form;
		$userMapper = new Application_Model_Mapper_User();					
		$user = new Application_Model_User();
		if(!$userMapper->isAllowModifyUser($this->_getParam("userID", 0))){
			throw new Exception("you do not have a permission to edit this account");
		}
		
		if($this->_getParam("userID", 0) <= 0){
			return ;
		}	
		
		$userMapper->find($this->_getParam("userID", 0), $user);
		$languageMapper = new Application_Model_Mapper_Language();
		$this->view->languageIsShowInTranslatorList =  $languageMapper->getLanguageIsShowInTranslatorListActive();	
		if($user->UserRole == Application_Model_DbTable_Users::USER_ADMIN){
			$form->removeElement("UserRole");
			$form->removeElement("JtepmEmail");
			$form->removeElement("SourcelanguageID");				
		}
				
		if(!$this->getRequest()->isPost()){						
			$formMapping = array("UserID" => $user->UserID, "UserName" => utf8_encode($user->UserName) ,"UserEmail" => $user->UserEmail,
				"UserLoginName" => $user->UserLoginName,"UserPassword" => $user->UserPassword,
				"UserRole" => $user->UserRole, "UserIsActive" => $user->UserIsActive);
			
			if($user->UserRole == Application_Model_DbTable_Users::USER_CLIENT){
				$formMapping["JtepmEmail"] = $user->JtepmEmail;
			}
			
			if($user->UserRole == Application_Model_DbTable_Users::USER_TRANSLATOR){
				$userLanguageMapper = new Application_Model_Mapper_UserLanguage();
				$formMapping["SourcelanguageID"] = $userLanguageMapper->getSourceLanguages($user->UserID);
			}
						
			$form->populate($formMapping);
			$this->createTargetLanguageWhenEdit($form, $user->UserID);	
			return;
		}				
				
		if($this->getRequest()->getPost("UserPassword") == ""){
			$form->UserPassword->setRequired(false);	
			$form->UserConfirmPassword->setRequired(false);				
		}
		
		if($this->getRequest()->getPost("UserRole") == Application_Model_DbTable_Users::USER_JTEPM || 
			$this->getRequest()->getPost("UserRole") == Application_Model_DbTable_Users::USER_ADMIN){
			$form->JtepmEmail->setRequired(false);	
			$form->SourcelanguageID->setRequired(false);
		}
		
		if($this->getRequest()->getPost("UserRole") == Application_Model_DbTable_Users::USER_CLIENT){
			$form->SourcelanguageID->setRequired(false);
		}
		
		if($this->getRequest()->getPost("UserRole") == Application_Model_DbTable_Users::USER_TRANSLATOR){
			$form->JtepmEmail->setRequired(false);	
		}
		
		
		$formData = $this->getRequest()->getPost();			
		if(!$form->isValid($formData)){
			$form->populate($formData);	
			$this->createTargetLanguageWhenEdit($form, $user->UserID);
			return;
		}
		
		
		if(0 <> count($userMapper->getUserByUserLoginName($form->getValue("UserID"), $form->getValue("UserLoginName")))){
			$this->view->updateErrorMessage = "can not update duplicate user login name";
			$form->populate($formData);
			$this->createTargetLanguageWhenEdit($form, $user->UserID);
			return;
		}
		
		if(0 <> count($userMapper->getUserByUserEmail($form->getValue("UserEmail"), $form->getValue("UserID")))){
			$this->view->updateErrorMessage = "can not update duplicate email address";
			$form->populate($formData);
			$this->createTargetLanguageWhenEdit($form, $user->UserID);			
			return;
		}
		
		$user->UserID = (int)$form->getValue("UserID");	
		$user->UserName = $form->getValue("UserName");			
		$user->UserEmail = $form->getValue("UserEmail");			
		$user->UserLoginName = $form->getValue("UserLoginName");			
		$user->UserPassword = $form->getValue("UserPassword");		
		$user->UserRole = $form->getValue("UserRole") == null ? Application_Model_DbTable_Users::USER_ADMIN : $form->getValue("UserRole") ;
		$user->UserIsActive = $form->getValue("UserIsActive") == "Y" ? "Y" : "N";
		$user->JtepmEmail = null;
		
		if($form->getValue("UserRole") == Application_Model_DbTable_Users::USER_CLIENT){
			$user->JtepmEmail = $form->getValue("JtepmEmail");
		}
	
		$userID = $userMapper->save($user);		
		if($form->getValue("UserRole") == Application_Model_DbTable_Users::USER_TRANSLATOR){
			$userLanguageMapper->delete($user->UserID);
			foreach($form->getValue("SourcelanguageID") as $languageID){
				if($this->_getParam("hiddenSourceTargetLanguage_$languageID", "0") !="0"){
					$targetLanguage = split(",", $this->_getParam("hiddenSourceTargetLanguage_$languageID", 0));
				
					for($i=0; $i< count($targetLanguage); $i++){
						$userLanguage = new Application_Model_Mapper_UserLanguage();
						$result = $userLanguage->getUserLanguageByUserIDAndLanguageID($userID, $languageID, $targetLanguage[$i]);
						$userLanguageMapper->save( count($result) ==0 ? null : $result[0]->UserLanguageID, $user->UserID, $languageID, $targetLanguage[$i]);	
					}
				}				
			}
		}
		
		$activity->setUserName($auth->getIdentity()->UserLoginName)
				  ->setUserActivity("User Edit UserID " .  $userID)
				  ->setUserActivityDateTime(date("Y-m-d : H:i:s", time()));
		$activityMapper->save($activity);	
		
		$this->_helper->redirector("index");		
    }

    public function deleteAction()
    {
		$activityMapper = new Application_Model_Mapper_Activity();
		$activity = new Application_Model_Activity();
		$auth = Zend_Auth::getInstance();
		
		$userMapper = new Application_Model_Mapper_User();	
		$user = new Application_Model_User();
		if(!$userMapper->isAllowModifyUser($this->_getParam("userID", 0))){
			return $this->_helper->redirector("index");		
		}
		
		if(!$this->getRequest()->isPost()){
			$userID = $this->_getParam("userID", 0);
			$userMapper->find($userID, $user);
			$this->view->user = $user;				
			return;
		}

		if($this->getRequest()->getPost("del") == "Yes"){
			$userID = $this->getRequest()->getPost("userID");						
			$userMapper->deleteUser($userID);
			
			$activity->setUserName($auth->getIdentity()->UserLoginName)
				  ->setUserActivity("User Delete UserID " .  $userID)
				  ->setUserActivityDateTime(date("Y-m-d : H:i:s", time()));
			$activityMapper->save($activity);	
		}		
		
		$this->_helper->redirector("index");
    }

    public function viewdetailAction()
    {
        $userID = $this->_getParam("userID", 0);
		
		if($userID > 0){
			$userMapper = new Application_Model_Mapper_User();	
			$user = new Application_Model_User();
			$userMapper->find($userID, $user);
			
			if($user->UserRole == Application_Model_DbTable_Users::USER_TRANSLATOR){
				$userLanguageMapper = new Application_Model_Mapper_UserLanguage();
				$targetlanguageID = $userLanguageMapper->getTargetLanguageNames($userID);
				$this->view->targetlanguageID = $targetlanguageID;	
			}
			
			$this->view->user = $user;
		}		
    }


}









