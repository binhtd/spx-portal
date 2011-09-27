<?php

class HOController extends Zend_Controller_Action
{
	const SAVE_HO = "Save";
	const SAVE_ADD_NEW_HO = "Send to Jonckers";
	const CANCEL = "Cancel";
	const HIDE_CLOSED_HOS = "HideClosedHos";
	const EXPORT_IN_EXCEL = "Export In Excel";
	const BACK = "Back";
	
    public function init()
    {        				
		$this->view->CANCEL = self::CANCEL;		
		$this->view->HIDE_CLOSED_HOS = self::HIDE_CLOSED_HOS; 
		
		$languageMapper = new Application_Model_Mapper_Language();		
		$hoMapper = new Application_Model_Mapper_HO();
		$this->view->sourceListlanguage = $languageMapper->getSourceLanguageActive();
		$this->view->targetListlanguage = $languageMapper->getTargetLanguageActive();				
		$this->view->activeHo = true;
		$this->view->exportInExcel = self::EXPORT_IN_EXCEL;
		$this->view->back = self::BACK;
    }

    public function indexAction()
    {
		$hoMapper= new Application_Model_Mapper_HO();
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
		$this->view->hideClosedHoCheckedButton = false;
		$this->view->showAddNewHoButton = $hoMapper->isAllowAddNewHo();
				
		if($this->_getParam( self::HIDE_CLOSED_HOS, $default="") == self::HIDE_CLOSED_HOS){			
			$this->view->hideClosedHoCheckedButton = true;
		}
		
		$this->view->paginator = $hoMapper->getPaginator($this->getRequest()->getParam('page', 1), $this->getRequest()->getParam("limit", (int) $config->paginator->itemCountPerPage), $this->view->hideClosedHoCheckedButton);
		$this->view->resultSet = $hoMapper->getPaginatorData($this->getRequest()->getParam('page', 1), $this->getRequest()->getParam("limit", (int) $config->paginator->itemCountPerPage), $this->view->hideClosedHoCheckedButton);
    }

    public function editAction()
    {
		$activityMapper = new Application_Model_Mapper_Activity();
		$activity = new Application_Model_Activity();
			
	    $form = new Application_Form_HO();		
		$hoMapper = new Application_Model_Mapper_HO();
		$handOffTargetLanguageMapper = new Application_Model_Mapper_HandOffTargetLanguage();
		$ho = new Application_Model_HO();
		$auth = Zend_Auth::getInstance();
		$this->view->form = $form;
		$this->view->SAVE_HO = self::SAVE_HO;	
		$hoMapper->find($this->_getParam("handoffid", 0), $ho);
		if($ho->HandOffID == null){
			throw new Exception("Please choose correct handoffid for edit ho");
		}						
		
		if(!$hoMapper->isAllowEditHo($ho->HandOffStatus, $ho->UserID) || !$hoMapper->isAllowShowEditDepentOnRoleAndHoStatus($ho->HandOffStatus)){
			throw new Exception("you do not have a permission to edit this ho record, please login with different account");
		}
		
		$this->view->allowEditFieldInHoRecord = $hoMapper->isAllowEditFieldInHoRecord($ho->HandOffStatus); 		
		$this->view->showTargetLocalizationStrings = !($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_CLIENT);
		$this->view->showHoStatusDropDown = !($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_TRANSLATOR);
		
		$handOffTargetLanguage = new Application_Model_Mapper_HandOffTargetLanguage();
		$this->view->handOffTargetLanguages = $handOffTargetLanguage->getTargetLanguages($ho->HandOffID);		
		$this->view->hostatus = $hoMapper->getHOStatus($ho->HandOffStatus);
		
		if(!$this->getRequest()->isPost()){					
			$form->populate(array("HandOffID" => $ho->HandOffID, "UserID"=> $ho->UserID, "HandOffTitle" => $ho->HandOffTitle,
							"HandOffStartProject" => $ho->HandOffStartProject,
							"HandOffStatus" => $ho->HandOffStatus, "HandOffSourceLanguageID" => $ho->HandOffSourceLanguageID, "HandOffListTargetLanguageID" => $handOffTargetLanguageMapper->getArrayTargetLanguages($ho->HandOffID),
							"SignatureName" => $ho->SignatureName
							));
			
			$form->content->setValue($ho->HandOffInstruction);
			return;
		}				
		
		if($this->getRequest()->getPost("action") == self::CANCEL){
			$this->_helper->redirector("index");
			return;
		}	
				
		$formData = $this->getRequest()->getPost();
		$formData["content"] = $this->_helper->WebEditorContentFilter(trim($formData["content"]));							
		
		if(!$hoMapper->isAllowEditFieldInHoRecord($ho->HandOffStatus)){
			$formData = array("HandOffID" => $ho->HandOffID, "UserID"=> $ho->UserID, "HandOffTitle" => $ho->HandOffTitle,
							"HandOffStartProject" => $ho->HandOffStartProject,
							"HandOffStatus" => $this->_getParam("HandOffStatus", ""),
							"HandOffSourceLanguageID" => $ho->HandOffSourceLanguageID, "HandOffListTargetLanguageID" => $handOffTargetLanguageMapper->getArrayTargetLanguages($ho->HandOffID), "content" => $ho->HandOffInstruction,
							"SignatureName" => $ho->SignatureName);
		}
		
		$this->view->hoStatusDependOnNumberOfTargetStringLocalization = $this->getHoStatusDependOnNumberOfTargetStringLocalization($ho->HandOffID, $this->_getParam("HandOffStatus", ""));

		if (!$form->isValid($formData) || !$hoMapper->isValidHOStatusSetup($ho, $this->getRequest()->getPost("HandOffStatus", ""))
			|| ( strlen($this->view->hoStatusDependOnNumberOfTargetStringLocalization) > 0)){	
			if($auth->getIdentity()->UserRole != Application_Model_DbTable_Users::USER_TRANSLATOR){
				return $form->populate($formData);
			}
		}
		
		if( in_array($form->getValue("HandOffSourceLanguageID"), $form->getValue("HandOffListTargetLanguageID"))){
			$this->view->duplicateSourceAndTargetLanguageErrorMessage = "One language is duplicated in source language and target language";			
			$form->populate($formData);
			return;
		}
		
		$results = $hoMapper->getHandoffByHandoffStringLocalization($form->getValue("HandOffTitle"), $ho->HandOffID);	
		if(0 <> count($results)){
			$this->view->editErrorMessage = "This Source string has already been submitted for localization by " . $results[0]->SignatureName ." in <a class='errors' href='" . $this->view->url( array('controller' => 'ho', 'action' => 'viewdetail', 'handoffid' => $results[0]->HandOffID)) . "'>Bento" . str_pad($results[0]->HandOffID, 8, "0", STR_PAD_LEFT) . "<a/>";			
			$formData = array("HandOffID" => $ho->HandOffID, "UserID"=> $ho->UserID, "HandOffTitle" => $ho->HandOffTitle,
							"HandOffStartProject" => $ho->HandOffStartProject,
							"HandOffStatus" => $this->_getParam("HandOffStatus", ""),
							"HandOffSourceLanguageID" => $ho->HandOffSourceLanguageID, "HandOffListTargetLanguageID" => $handOffTargetLanguageMapper->getArrayTargetLanguages($ho->HandOffID),
							"content" => $ho->HandOffInstruction,
							"SignatureName" => $ho->SignatureName);
			return;
		}
				
		if($hoMapper->isAllowEditFieldInHoRecord($ho->HandOffStatus)){
			$ho->HandOffID = (int)$this->_getParam("handoffid", 0);
			$ho->HandOffTitle = $form->getValue("HandOffTitle");			
			$ho->HandOffStartProject = $form->getValue("HandOffStartProject") == "" ? date("Y-m-d : H:i:s", time()) : $form->getValue("HandOffStartProject");			
			$ho->HandOffSourceLanguageID = $form->getValue("HandOffSourceLanguageID");
			$ho->HandOffInstruction = htmlspecialchars($form->getValue("content"));
			$ho->SignatureName = $form->getValue("SignatureName");
		}
		
		$ho->HandOffClosedDate = $form->getValue("HandOffStatus") == Application_Model_DbTable_HOs::HO_CLOSED ? date("Y-m-d : H:i:s", time()) : null;
		if(!($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_TRANSLATOR)){	
			$ho->HandOffStatus =  $form->getValue("HandOffStatus");			
		}

		$this->updateHandoffAndLanguageIDForHandoffTargetLanguagesTable($ho->HandOffID, $form);
		$this->updateHandBackStringLocalization($ho->HandOffID);
		$hoMapper->save($ho);
				
		$activity->setUserName($auth->getIdentity()->UserLoginName)
				  ->setUserActivity("User Edit HandOffID " .  $ho->HandOffID)
				  ->setUserActivityDateTime(date("Y-m-d : H:i:s", time()));
		$activityMapper->save($activity);		
		
		$hoMapper->sendEmailNotifyHo($this->_getParam("handoffid", 0), $form->getValue("HandOffStatus"), ($this->getRequest()->getParam("NotifyHoStatusChange", "") != "") && ($form->getValue("HandOffStatus")!= Application_Model_DbTable_HOs::HO_CREATED) ? true : false);
					
		$this->_helper->redirector("index");			
    }

    public function deleteAction()
    {
		$activityMapper = new Application_Model_Mapper_Activity();
		$activity = new Application_Model_Activity();
		$auth = Zend_Auth::getInstance();
			
		$hoMapper = new Application_Model_Mapper_HO();		
		$ho = new Application_Model_HO();
		$handOffID = $this->_getParam("handoffid", 0);
		$hoMapper->find($handOffID, $ho);
		
		if($ho->HandOffID == null){
			throw new Exception("Please choose correct handoffid to delete record");
		}						
		
		if(!$hoMapper->isAllowDeleteHo($ho->HandOffStatus, $ho->UserID)){
			throw new Exception("you do not have a permission to delete this ho record");
		}
		
        if(!$this->getRequest()->isPost()){		
			$this->view->ho = $ho;
			return;
		}

		if($this->getRequest()->getPost("del") == "Yes"){
			$handOffID = $this->getRequest()->getPost("handoffid");			
						
			$hoMapper->deleteHO($handOffID);
			
			$activity->setUserName($auth->getIdentity()->UserLoginName)
				  ->setUserActivity("User Delete HandOffID " .  $handOffID)
				  ->setUserActivityDateTime(date("Y-m-d : H:i:s", time()));
			$activityMapper->save($activity);		  
		}		
		
		$this->_helper->redirector("index");
    }

    public function viewdetailAction()
    {        
		$hoMapper = new Application_Model_Mapper_HO();
		$ho = new Application_Model_HO();
		$hoMapper->find($this->_getParam("handoffid", 0), $ho);
		
		if($ho->HandOffID == null){
			throw new Exception("Please choose correct handoffid for viewdetail this ho record");
		}
		
		if(!$hoMapper->isAllowViewDetail($ho->UserID)){
			throw new Exception("You do not have a permission to viewdetail this ho record");
		}
		
		if($this->getRequest()->getPost("submit") == self::BACK){
			$this->_helper->redirector("index");
			return;
		}
		
		if($this->getRequest()->getPost("submit") == self::EXPORT_IN_EXCEL){
			$exportHoMapper = new Application_Model_Mapper_ExportHO();
			$exportHoMapper->ExportToExcel($ho->HandOffID, null, null);
			return;
		}
		
		$this->view->ho = $ho;
		$handOffTargetLanguage = new Application_Model_Mapper_HandOffTargetLanguage();
		$this->view->handOffTargetLanguages = $handOffTargetLanguage->getTargetLanguages($ho->HandOffID);
    }

    public function addAction()
    {
		$activityMapper = new Application_Model_Mapper_Activity();
		$activity = new Application_Model_Activity();
		$auth = Zend_Auth::getInstance();
	
        $form = new Application_Form_HO();		
		$handOffTargetLanguageMapper = new Application_Model_Mapper_HandOffTargetLanguage();
		$auth = Zend_Auth::getInstance();
		$this->view->form = $form;
		$this->view->SAVE_HO = self::SAVE_ADD_NEW_HO;	
		
		if(!$this->getRequest()->isPost()){
			$form->HandOffStartProject->setValue(date("Y-m-d", time()));
			return;			
		}			
		
		if($this->getRequest()->getPost("action") == self::CANCEL){
			$this->_helper->redirector("index");
			return;
		}
			
		$formData = $this->getRequest()->getPost();
		$formData["content"] = $this->_helper->WebEditorContentFilter(trim($formData["content"]));
		if(!$form->isValid($formData)){
			$form->populate($formData);
			return;
		}
		
		$hoMapper = new Application_Model_Mapper_HO();
		$ho = new Application_Model_HO();		
		
		if( in_array($form->getValue("HandOffSourceLanguageID"), $form->getValue("HandOffListTargetLanguageID"))){
			$this->view->duplicateSourceAndTargetLanguageErrorMessage = "One language is duplicated in source language and target language";			
			$form->populate($formData);
			return;
		}
		
		$results = $hoMapper->getHandoffByHandoffStringLocalization($form->getValue("HandOffTitle"), 0);
		if(0 <> count($results)){
			$this->view->insertErrorMessage = "This Source string has already been submitted for localization by " . $results[0]->SignatureName . " in <a class='errors' href='" . $this->view->url( array('controller' => 'ho', 'action' => 'viewdetail', 'handoffid' => $results[0]->HandOffID)) . "'>Bento" . str_pad($results[0]->HandOffID, 8, "0", STR_PAD_LEFT) . "<a/>";			
			$form->populate($formData);
			return;
		}
				
		$ho->UserID = $auth->getIdentity()->UserID;
		$ho->HandOffTitle = $form->getValue("HandOffTitle");				
		$ho->HandOffStartProject = $form->getValue("HandOffStartProject") == "" ? date("Y-m-d : H:i:s", time()) : $form->getValue("HandOffStartProject");
		$ho->HandOffStatus =  Application_Model_DbTable_HOs::HO_UPLOADED;
		$ho->HandOffSourceLanguageID = $form->getValue("HandOffSourceLanguageID");		
		$ho->HandOffInstruction = htmlspecialchars($form->getValue("content"));
		$ho->SignatureName = $form->getValue("SignatureName");
				
		$handOffID = $hoMapper->save($ho);		
		foreach($form->getValue("HandOffListTargetLanguageID") as $languageID){
			$handOffTargetLanguageMapper->save($handOffID, $languageID, null, 'N');
		}
		
		$hoMapper->sendEmailNotifyHo($handOffID, Application_Model_DbTable_HOs::HO_UPLOADED,  true);
		$activity->setUserName($auth->getIdentity()->UserLoginName)
				  ->setUserActivity("User Add New HandOffID " .  $handOffID)
				  ->setUserActivityDateTime(date("Y-m-d : H:i:s", time()));
		$activityMapper->save($activity);	
						
		$this->_helper->redirector("index");	
    }
	
	private function getHoStatusDependOnNumberOfTargetStringLocalization($handoffID, $handoffStatus){
		$auth = Zend_Auth::getInstance();
		$handOffTargetLanguageMapper = new Application_Model_Mapper_HandOffTargetLanguage();				
		$handofftargetlanguages =  $handOffTargetLanguageMapper->getTargetLanguages($handoffID);
				
		if (!(($handoffStatus == Application_Model_DbTable_HOs::HB_COMPLETED ) && 
		(($auth->getIdentity()->UserRole ==  Application_Model_DbTable_Users::USER_ADMIN) || ($auth->getIdentity()->UserRole ==  Application_Model_DbTable_Users::USER_JTEPM)))){
			return null;
		}
		
		foreach($handofftargetlanguages as $handOffTargetLanguage){
			if($this->_getParam("HandOffTargetLanguage-Element-input-".$handOffTargetLanguage->LanguageID , "") == "")
			{
				 return "Please translate all target languages";
			}
		}
		
		return null;		
	}
	
	
	
	private function updateHandoffAndLanguageIDForHandoffTargetLanguagesTable($handoffID, $form){
		$handOffTargetLanguageMapper = new Application_Model_Mapper_HandOffTargetLanguage();
		$savedInHandoffTargetLanguagesTable = $handOffTargetLanguageMapper->getArrayTargetLanguages($handoffID);	
		
		foreach($savedInHandoffTargetLanguagesTable as $languageID){
			if (!in_array($languageID, $form->getValue("HandOffListTargetLanguageID"))){
				$handOffTargetLanguageMapper->delete($handoffID, $languageID);
			}
		}
			
		foreach($form->getValue("HandOffListTargetLanguageID") as $languageID){
			if( !in_array($languageID, $savedInHandoffTargetLanguagesTable)){
				$handOffTargetLanguageMapper->save($handoffID, $languageID, null, 'N');
			}			
		}		
	}

	private function updateHandBackStringLocalization($handoffID){	
		$handOffTargetLanguageMapper = new Application_Model_Mapper_HandOffTargetLanguage();				
		$handofftargetlanguages =  $handOffTargetLanguageMapper->getTargetLanguages($handoffID);		
		$auth = Zend_Auth::getInstance();
		foreach($handofftargetlanguages as $handOffTargetLanguage){			
			
			if ($handOffTargetLanguage->AllowEdit){				
				
				if($handOffTargetLanguage->HandBackStringLocalization != null && $this->_getParam("HandOffTargetLanguage-Element-input-".$handOffTargetLanguage->LanguageID , null) != $handOffTargetLanguage->HandBackStringLocalization){
					$handoffTargetLanguagesHistoryMapper = new Application_Model_Mapper_HandoffTargetLanguagesHistory();
					$handoffTargetLanguagesHistory = new Application_Model_HandoffTargetLanguagesHistory();
					
					$handoffTargetLanguagesHistory->HandOffID = $handoffID;
					$handoffTargetLanguagesHistory->LanguageID = $handOffTargetLanguage->LanguageID;
					$handoffTargetLanguagesHistory->HandBackStringLocalization = $handOffTargetLanguage->HandBackStringLocalization;
					$handoffTargetLanguagesHistory->UserID = $auth->getIdentity()->UserID;
					$handoffTargetLanguagesHistory->DateCreated = date("Y-m-d : H:i:s", time());					
					$handoffTargetLanguagesHistoryMapper->save($handoffTargetLanguagesHistory);
				}
				
				if(($handOffTargetLanguage->getTranslatedByTranslator() == "N") && ($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_TRANSLATOR)){
					$translationForTranslatorMapper = new Application_Model_Mapper_TranslationForTranslator();
					$translationForTranslator = new Application_Model_TranslationForTranslator;
					
					$translationForTranslator->setUserID($auth->getIdentity()->UserID)
 											 ->setHandBackStringLocalization($this->_getParam("HandOffTargetLanguage-Element-input-".$handOffTargetLanguage->LanguageID , null))
										     ->setHandOffID($handoffID);
											 
					$translationForTranslatorMapper->save($translationForTranslator);						 
				}
								
				if($this->_getParam("HandOffTargetLanguage-Element-input-".$handOffTargetLanguage->LanguageID , null) !=null){
					$handOffTargetLanguageMapper->save($handOffTargetLanguage->HandOffID, $handOffTargetLanguage->LanguageID, 
					$this->_getParam("HandOffTargetLanguage-Element-input-".$handOffTargetLanguage->LanguageID, null),
					($handOffTargetLanguage->getTranslatedByTranslator() == "Y") || ($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_TRANSLATOR) ? "Y" : "N");
				}
			}
		}
	}	
}