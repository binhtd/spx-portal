<?php

class Application_Model_Mapper_EmailTemplate
{
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
            $this->setDbTable('Application_Model_DbTable_EmailTemplates');
        }
        return $this->_dbTable;
    }
		
	public function getPaginator($page, $itemsPerPage){	
		$paginator = Zend_Paginator::factory($this->getDbTable()->select());
		
		$paginator->setCurrentPageNumber($page);
		
		$paginator->setItemCountPerPage($itemsPerPage);

		$paginator->setPageRange(5);
		
		return $paginator; 
	}
	
	public function getPaginatorData($page, $itemsPerPage){
		$resultSet = $this->getDbTable()->fetchAll($this->getDbTable()->select()->order("EmailTemplateID desc")->limit($itemsPerPage, ($page-1) * $itemsPerPage));
		
		return $this->createResultSetEntity($resultSet);
	}
	
	public function find($emailTemplateID, Application_Model_EmailTemplate $emailTemplate){
		$result = $this->getDbTable()->find($emailTemplateID); 
		if (0 == count($result)){
			return;
		}				
		$row = $result->current();

        $emailTemplate->setEmailTemplateID($row->EmailTemplateID)
					  ->setEmailTemplateContent($row->EmailTemplateContent)                      
				      ->setEmailTemplateStatus($row->EmailTemplateStatus)
				      ->setEmailTemplateIsActive($row->EmailTemplateIsActive)
					  ->setEmailTemplateSubject($row->EmailTemplateSubject);
	}
	
	public function findEmailTemplateByEmailTemplateStatus($emailTemplateStatus){
		$resultSet = $this->getDbTable()->fetchAll($this->getDbTable()->select()
														->where(" EmailTemplateStatus = ?", $emailTemplateStatus) 
														->where(" EmailTemplateIsActive =?", 'Y')
														->order("EmailTemplateID desc"));
		
		return $this->createResultSetEntity($resultSet);
	}
	
	public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        
		return $this->createResultSetEntity($resultSet);
    }
	
	public function save(Application_Model_EmailTemplate $emailTemplate){			
		$data = array(
				"EmailTemplateID" => $emailTemplate->EmailTemplateID,
				"EmailTemplateContent" => $emailTemplate->EmailTemplateContent,
				"EmailTemplateStatus" => $emailTemplate->EmailTemplateStatus,
				"EmailTemplateIsActive" => $emailTemplate->EmailTemplateIsActive,
				"EmailTemplateSubject" => $emailTemplate->EmailTemplateSubject
        );
 		
		$this->automaticChangeStateOfEmailTemplateWhenDuplicateEmailTemplateStatus($emailTemplate->EmailTemplateStatus);
        if (0 === ($emailTemplateID = $emailTemplate->getEmailTemplateID())) {
            unset($data['EmailTemplateID']);			
            return $this->getDbTable()->insert($data);			
		}
		
		return $this->getDbTable()->update($data, array('EmailTemplateID = ?' => $emailTemplateID));        
	}	
		
	public function deleteEmailTemplate($emailTemplateID){		
		$this->getDbTable()->delete("EmailTemplateID = ?", (int)$emailTemplateID);
	}

	public function sendEmail($handOffId, $handOffStatus){
		$activityMapper = new Application_Model_Mapper_Activity();
		$activity = new Application_Model_Activity();
		$auth = Zend_Auth::getInstance();
				
		$configXml = new Zend_Config_Xml(APPLICATION_PATH . '/configs/smtp.xml', APPLICATION_ENV);
		$sendingEmail = new Application_Model_Mapper_SendingEmail();
		$emailTemplates = $this->findEmailTemplateByEmailTemplateStatus($handOffStatus);
		if(0 == count($emailTemplates)){
			throw new Exception("Please set emailtemplate for $handOffStatus");
		}
		$mail = new Zend_Mail('UTF-8');		
		
		$sendingEmail->sendingEmail($configXml->mail->server->sender, $this->getRecipientTo($handOffId, $handOffStatus), $this->getRecipientCc($handOffId, $handOffStatus), $this->getRecipientBcc($handOffStatus, $handOffId), $emailTemplates[0]->EmailTemplateSubject,
		$this->getEmailBody($emailTemplates[0]->EmailTemplateContent, $handOffId, $handOffStatus));		

		if ($handOffStatus == Application_Model_DbTable_HOs::HO_RECEIVED){
			$translatorEmailTemplateMapper = new Application_Model_Mapper_TranslatorEmailTemplate();
			$translatorEmailTemplateMapper->sendingEmailReportListTranslator($handOffId);
		}
		
		$activity->setUserName($auth->getIdentity()->UserLoginName)
				  ->setUserActivity("Email Sending<$handOffStatus> To:" . join(", ", $this->getRecipientTo($handOffId, $handOffStatus)). " CC:" . join(", ", $this->getRecipientCc($handOffId, $handOffStatus)))
				  ->setModule(Application_Model_DbTable_Activities::SENDING_EMAIL)
				  ->setUserActivityDateTime(date("Y-m-d : H:i:s", time()));
				
		$activityMapper->save($activity);			
	}

	private function getRecipientTo($handoffId, $handoffStatus){
		$user = $this->findUserByHandoffID($handoffId);		
		$recipientTo = array();
		
		if (($handoffStatus == Application_Model_DbTable_HOs::HO_UPLOADED) || ($handoffStatus == Application_Model_DbTable_HOs::HO_CLOSED) || ($handoffStatus == Application_Model_DbTable_HOs::HO_RECEIVED)){				
			$recipientTo[] = $user->JtepmEmail;
		}
		
		if ($handoffStatus == Application_Model_DbTable_HOs::HB_COMPLETED){
			$recipientTo[] = $user->UserEmail;		
		}

		return $recipientTo;
	}	

	private function getRecipientCc($handOffID, $handoffStatus){		
		$user = $this->findUserByHandoffID($handOffID);				
		$recipientCc  = Array();
		
		if (($handoffStatus == Application_Model_DbTable_HOs::HO_UPLOADED) || ($handoffStatus == Application_Model_DbTable_HOs::HO_CLOSED)){
			$recipientCc[] = $user->UserEmail;
			return $recipientCc;			
		}

		$recipientCc[] = $user->JtepmEmail;

		return $recipientCc;				
	}
		
	private function getRecipientBcc($handoffStatus, $handoffId){
			$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
			$configXml = new Zend_Config_Xml(APPLICATION_PATH . '/configs/smtp.xml', APPLICATION_ENV);
			$recipientBcc = Array();
			$recipientBcc[] = $configXml->mail->admin->alias;
			
			if ($handoffStatus == Application_Model_DbTable_HOs::HO_RECEIVED){
				$translatorEmailTemplateMapper = new Application_Model_Mapper_TranslatorEmailTemplate();
				
				foreach($translatorEmailTemplateMapper->getListEmailTranslator($handoffId) as $email){
					$recipientBcc[] = $email;
				}
				
				if( count($recipientBcc)==0){
					$recipientBcc[] = $config->mail->translators->alias;			
				}
			}		
					
			return $recipientBcc;
		}
	
	private function findUserByHandoffID($handOffID){
		$hoMapper = new Application_Model_Mapper_HO();
		$ho = new Application_Model_HO();
		$hoMapper->find($handOffID, $ho);
		
		$userMapper = new Application_Model_Mapper_User();
		$user = new Application_Model_User();		
		$userMapper->find($ho->UserID, $user);
		
		return $user;
	}
		
	private function getEmailBody($emailTemplateContent, $handOffID, $handOffStatus){
		$hoMapper = new Application_Model_Mapper_HO();
		$ho = new Application_Model_HO();		
		$hoMapper->find($handOffID, $ho);
		$userMapper = new Application_Model_Mapper_User();
		$user = new Application_Model_User();		
		$auth = Zend_Auth::getInstance();
		$languageMapper = new Application_Model_Mapper_Language();
		$language = new Application_Model_Language();
					
		if($ho->HandOffID == null){
			throw new Exception("Please input correct handoffID to get emailbody");
		}
		$languageMapper->find($ho->HandOffSourceLanguageID, $language);
				
		$htmlBody = $emailTemplateContent;					
		$htmlBody = str_replace("{HOTITLE}", $ho->HandOffTitle , $htmlBody);
		$htmlBody = str_replace("{SRCLang}", $language->LanguageName , $htmlBody);
		$htmlBody = str_replace("{TGTLang}", $ho->HandOffTargetLanguageName , $htmlBody);
		$htmlBody = str_replace("{LocalizedStringsDetails}", $this->setLocalizedStringsDetails($handOffID, $handOffStatus) , $htmlBody);               
		
		
		$htmlBody = str_replace("{LinkToHODetails}", str_replace("{LinkToHODetails}", 
		($handOffStatus == Application_Model_DbTable_HOs::HO_UPLOADED ? $this->fullUrl("/ho/viewdetail/handoffid/") .$handOffID :
		$this->fullUrl($this->getActionPath($handOffStatus) .$handOffID)), "<a href='{LinkToHODetails}' >{LinkToHODetails}</a>") , $htmlBody);
		
		$htmlBody = str_replace("{LinkToHOEDIT}", str_replace("{LinkToHOEDIT}",
		($handOffStatus == Application_Model_DbTable_HOs::HB_COMPLETED ?  $this->fullUrl("/ho/edit/handoffid/") .$handOffID :
		$this->fullUrl($this->getActionPath($handOffStatus) .$handOffID)), "<a href='{LinkToHOEDIT}' >{LinkToHOEDIT}</a>") , $htmlBody);	
 		
		$htmlBody = str_replace("{HandoffInstructions}", $ho->HandOffInstruction, $htmlBody);
				
		switch($handOffStatus){
			case Application_Model_DbTable_HOs::HO_UPLOADED:
			case Application_Model_DbTable_HOs:: HO_CLOSED:
				$htmlBody = str_replace("{UserName}", utf8_encode($auth->getIdentity()->UserName) , $htmlBody);								
				break;
			case Application_Model_DbTable_HOs::HO_RECEIVED:	
			case Application_Model_DbTable_HOs::HB_COMPLETED:								
				$user = $this->findUserByHandoffID($handOffID);				
				$htmlBody = str_replace("{UserName}", utf8_encode($user->UserName) , $htmlBody);					
				$htmlBody = str_replace("{JTEPMEmail}", $auth->getIdentity()->UserEmail , $htmlBody);
				break;
			default:
				break;
		}
											
		return html_entity_decode($htmlBody);
	}
	
	private function getActionPath($handOffStatus){
		switch($handOffStatus){
			case Application_Model_DbTable_HOs::HO_UPLOADED:
			case Application_Model_DbTable_HOs::HO_RECEIVED:	
				return "/ho/edit/handoffid/";
			case Application_Model_DbTable_HOs::HB_COMPLETED:
			case Application_Model_DbTable_HOs:: HO_CLOSED:
				return "/ho/viewdetail/handoffid/";
		}
		return "";
	}
	
	
	private function setLocalizedStringsDetails($handOffID, $handOffStatus){
		$handOffTargetLanguageMapper = new Application_Model_Mapper_HandOffTargetLanguage();
		
		if( $handOffStatus == Application_Model_DbTable_HOs::HB_COMPLETED){
			$handOffTargetLanguages = $handOffTargetLanguageMapper->getTargetLanguages($handOffID);
			$tableString = "<table>";
			foreach($handOffTargetLanguages as $handOffTargetLanguage){
				$tableString .= "<tr><td>" . $handOffTargetLanguage->LanguageName . "</td><td>" . $handOffTargetLanguage->HandBackStringLocalization . "</td></tr>";
			}
			$tableString .= "</table>";
			return $tableString;
		}
		
		return "";
	}
	
	public function fullUrl($url){		
		$request = Zend_Controller_Front::getInstance()->getRequest();
        $url = $request->getScheme() . '://' . $request->getHttpHost() . $url;
        
		return $url;
	}
	
	private function createResultSetEntity($resultSet){
		$entries   = array();
		
		foreach ($resultSet as $row) {
			$entry = new Application_Model_EmailTemplate();
			$entry->setEmailTemplateID($row->EmailTemplateID)
				  ->setEmailTemplateContent($row->EmailTemplateContent)                  
				  ->setEmailTemplateStatus($row->EmailTemplateStatus)
				  ->setEmailTemplateIsActive($row->EmailTemplateIsActive)
				  ->setEmailTemplateSubject($row->EmailTemplateSubject);

			$entries[] = $entry;
		}
		
		return $entries;
	}	
	
	private function automaticChangeStateOfEmailTemplateWhenDuplicateEmailTemplateStatus($handOffStatus){
		$emailTemplate = $this->getDbTable();
		$emailTemplate->update(array("EmailTemplateIsActive" =>"N"), "EmailTemplateStatus='$handOffStatus'");
	}
}

