<?php

class Application_Model_Mapper_HO
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
            $this->setDbTable('Application_Model_DbTable_HOs');
        }
        return $this->_dbTable;
    }
		
	public function getPaginator($page, $itemsPerPage, $hideCloseHO){	
		$paginator = Zend_Paginator::factory($this->getDbTable()->select()->where($this->buildPaginatorWhereQuery($hideCloseHO)));
		
		$paginator->setCurrentPageNumber($page);
		
		$paginator->setItemCountPerPage($itemsPerPage);

		$paginator->setPageRange(5);
		
		return $paginator; 
	}
	
	public function getPaginatorData($page, $itemsPerPage, $hideCloseHO){														
		$resultSet = $this->getDbTable()->fetchAll($this->getDbTable()->select()->where($this->buildPaginatorWhereQuery($hideCloseHO))->order("HandOffID desc")
														->limit($itemsPerPage, ($page-1) * $itemsPerPage));
		
		return $this->CreateResultSetEntity($resultSet);
	}
	
	public function getHOStatus($currentHoStatus){
		switch($currentHoStatus){
			case Application_Model_DbTable_HOs::HO_CREATED:
				return array(Application_Model_DbTable_HOs::HO_CREATED => Application_Model_DbTable_HOs::HO_CREATED, Application_Model_DbTable_HOs::HO_CANCELLED => Application_Model_DbTable_HOs::HO_CANCELLED, Application_Model_DbTable_HOs::HO_UPLOADED => Application_Model_DbTable_HOs::HO_UPLOADED);
			case Application_Model_DbTable_HOs::HO_UPLOADED:
				return array(Application_Model_DbTable_HOs::HO_RECEIVED => Application_Model_DbTable_HOs::HO_RECEIVED);
			case Application_Model_DbTable_HOs::HO_RECEIVED:	
				return array(Application_Model_DbTable_HOs::HO_RECEIVED => Application_Model_DbTable_HOs::HO_RECEIVED, Application_Model_DbTable_HOs::HB_COMPLETED => Application_Model_DbTable_HOs::HB_COMPLETED);	
			case Application_Model_DbTable_HOs::HB_COMPLETED:	
				return array(Application_Model_DbTable_HOs::HO_CLOSED => Application_Model_DbTable_HOs::HO_CLOSED);	
		}
	}
	
	public function isAllowShowEditDepentOnRoleAndHoStatus($currentHoStatus){
		$auth = Zend_Auth::getInstance();
						
		if ($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_ADMIN){
			return true;
		}
	
		switch($currentHoStatus){
			case Application_Model_DbTable_HOs::HO_CREATED:
				return ($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_CLIENT);
			case Application_Model_DbTable_HOs::HO_UPLOADED:
				return ($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_JTEPM);	
			case Application_Model_DbTable_HOs::HO_RECEIVED:
				return (($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_TRANSLATOR)||
						($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_JTEPM));		
			case Application_Model_DbTable_HOs::HB_COMPLETED:
				return ($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_CLIENT);										
			default:
				return false;
		}
	}
	
	public function find($handOffID, Application_Model_HO $ho){
		$result = $this->getDbTable()->find($handOffID); 
		$languageMapper = new Application_Model_Mapper_Language();
		$language = new Application_Model_Language();
		$HandOffTargetLanguageMapper = new Application_Model_Mapper_HandOffTargetLanguage();
				
		$row = $result->current();
        $languageMapper->find($row->HandOffSourceLanguageID, $language);
		$ho->setHandOffID($row->HandOffID)
		   ->setUserID($row->UserID)
		   ->setHandOffTitle($row->HandOffStringLocalization)
		   ->setHandOffStartProject($row->HandOffStartProject)
		   ->setHandOffClosedDate($row->HandOffClosedDate)
		   ->setHandOffStatus($row->HandOffStatus)
		   ->setHandOffSourceLanguageID($row->HandOffSourceLanguageID)
		   ->setHandOffSourceLanguageName($language->LanguageName)
		   ->setHandOffTargetLanguageName($HandOffTargetLanguageMapper->getTargetLanguageNames($row->HandOffID))		   
		   ->setHandOffInstruction($row->HandOffInstruction)
		   ->setSignatureName($row->SignatureName);
	}
		
	public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        
		return $this->CreateResultSetEntity($resultSet);
    }

	public function save(Application_Model_HO $ho){			
		$data = array(
            "HandOffID" => $ho->HandOffID, "UserID" => $ho->UserID,
		    "HandOffStringLocalization" => $ho->HandOffTitle,"HandOffStartProject" => $ho->HandOffStartProject,
			"HandOffClosedDate" => $ho->HandOffClosedDate,
		    "HandOffStatus" => $ho->HandOffStatus, "HandOffSourceLanguageID" => $ho->HandOffSourceLanguageID,  
			"HandOffInstruction" => $ho->HandOffInstruction,
			"SignatureName" => $ho->SignatureName
        );
 
        if (null === ($handOffID = $ho->getHandOffID())) {
            unset($data['HandOffID']);
            return $this->getDbTable()->insert($data);
        } 

		return $this->getDbTable()->update($data, array('HandOffID = ?' => $handOffID));
	}	
	
	public function deleteHO($handOffID){
		$hos = $this->getDbTable()->find( (int)$handOffID);
		$hos[0]->delete();
	}
			
	public function getAvailabeHOForOneUser($userID){
	   $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
	   $userMapper = new Application_Model_Mapper_User();	   
	   $adminAccount = $userMapper->getUserByUserLoginName( 0, $config->fmsadmin->username);	   
	   $adminId = $adminAccount[0]->UserID;
	   
	   $resultSet = $this->getDbTable()->fetchAll($this->getDbTable()->select()
													  ->where(" UserID = ?", $userID)
													  ->where(" HandOffStatus <> ?", Application_Model_DbTable_HOs::HO_CLOSED)
													  ->where(" HandOffStatus <> ?", Application_Model_DbTable_HOs::HO_CANCELLED)
													  ->where("	not exists ( select HandBackID from handbacks where handbacks.HandOffID = handoffs.HandOffID)")
													  ->where(" HandOffStatus = ?", Application_Model_DbTable_HOs::HO_RECEIVED)
													  ->order("HandOffID desc"));

		return $this->createArrayData($resultSet);
	}
	
	public function isValidHOStatusSetup($ho, $hoStatus){				
		if((Application_Model_DbTable_HOs::getHOStatusOrder($ho->HandOffStatus) == Application_Model_DbTable_HOs::getHOStatusOrder(Application_Model_DbTable_HOs::HO_CREATED)) && (Application_Model_DbTable_HOs::getHOStatusOrder($hoStatus) == Application_Model_DbTable_HOs::getHOStatusOrder(Application_Model_DbTable_HOs::HO_UPLOADED))){
			return true;
		}

		if(Application_Model_DbTable_HOs::getHOStatusOrder($ho->HandOffStatus) == Application_Model_DbTable_HOs::getHOStatusOrder($hoStatus)){
			return true;
		}
		
		if(Application_Model_DbTable_HOs::getHOStatusOrder($ho->HandOffStatus) + 1 == Application_Model_DbTable_HOs::getHOStatusOrder($hoStatus)){
			return true;
		}
		
		return false;
	}
	
	public function isAllowEditHo($hoStatus, $userID){
		$auth = Zend_Auth::getInstance();
		
		return !(($hoStatus == Application_Model_DbTable_HOs::HO_CANCELLED) || 
				 ($hoStatus == Application_Model_DbTable_HOs::HO_CLOSED) || 
		         (($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_CLIENT) && 
				  ($userID != $auth->getIdentity()->UserID)));
	}
			
	public function isAllowDeleteHo($hoStatus, $userID){
		$auth = Zend_Auth::getInstance();
		
		if ($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_ADMIN){
			return true;
		}
		
		return !((($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_CLIENT) && 
				 ($hoStatus != Application_Model_DbTable_HOs::HO_CREATED))  || 
				 ($userID!= $auth->getIdentity()->UserID)); 			
	}
	
	public function isAllowViewDetail($userID){
		$auth = Zend_Auth::getInstance();
		
		if ($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_ADMIN){
			return true;
		}
		
		return !(($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_CLIENT) && ($userID != $auth->getIdentity()->UserID));		
	}
	
	public function isAllowAddNewHo(){
		$auth = Zend_Auth::getInstance();
		
		return !($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_JTEPM || 
				$auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_TRANSLATOR);			
	}
	
	public function isAllowEditFieldInHoRecord($handOffStatus){
		$auth = Zend_Auth::getInstance();
		
		if ($auth->getIdentity()->UserRole ==  Application_Model_DbTable_Users::USER_ADMIN){
			return true;	
		}
		
		if (($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_CLIENT) && ($handOffStatus!= Application_Model_DbTable_HOs::HB_COMPLETED)){
			return true;	
		}
		
		return false;
	}
	
	public function isAllowEditTargetLocalizationString($sourceLanguageID, $targetLanguageID){
		$auth = Zend_Auth::getInstance();
		if ($auth->getIdentity()->UserRole ==  Application_Model_DbTable_Users::USER_ADMIN || 
		    $auth->getIdentity()->UserRole ==  Application_Model_DbTable_Users::USER_JTEPM){
			return true;	
		}
				
		if ($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_TRANSLATOR){
			$userLanguageMapper = new Application_Model_Mapper_UserLanguage();
			$result = $userLanguageMapper->getUserLanguageByUserIDAndLanguageID( $auth->getIdentity()->UserID, $sourceLanguageID, $targetLanguageID);	
			return count($result) > 0;
		}
		
		return false;
	}
	
	public function sendEmailNotifyHo($handOffID, $handOffStatus, $notifyHoStatusChange){
		$emailTemplateMapper = new Application_Model_Mapper_EmailTemplate();
		$auth = Zend_Auth::getInstance();
		if (Application_Model_DbTable_HOs::HO_UPLOADED == $handOffStatus){			
			$emailTemplateMapper->sendEmail((int)$handOffID, $handOffStatus);
			return;
		}
				
		if ((Application_Model_DbTable_HOs::HB_COMPLETED == $handOffStatus) && 
			(($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_ADMIN) || 
			 ($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_JTEPM))){
			$emailTemplateMapper->sendEmail( (int)$handOffID, $handOffStatus);
			return;
		}

		if($notifyHoStatusChange){
			$emailTemplateMapper->sendEmail( (int)$handOffID, $handOffStatus);
		}
	}
	
	public function deleteHandoffClosedAfterSpecifyDay($numberOfDay){
		$mileStoneDate = date("Y-m-d : H:i:s", (time()-(24*60*60*$numberOfDay)));
		
		$hos = $this->getDbTable()->fetchAll($this->getDbTable()->select()->where("(HandOffStatus = '" . Application_Model_DbTable_HOs::HO_CLOSED . "') and (HandOffStatus <>'" . Application_Model_DbTable_HOs::HO_CANCELLED . "') and (HandOffClosedDate >= '$mileStoneDate')"));
		
		foreach($hos as $ho){
			$fm = new Application_Model_Mapper_FM();
			$fm->unlinkRecursive($ho->HandOffFolderLocation, true);		
			$ho->delete();
		}
		
		return count($hos);
	}
	
	public function getHandoffByHandoffStringLocalization($handOffStringLocalization, $handoffID){														
		$select = $this->getDbTable()->getAdapter()->select();
		$resultSet = $this->getDbTable()->getAdapter()->fetchAll(
														$select->from(array('h' => 'handoffs'))
													    ->join(array('hl' => 'handofftargetlanguages'), 'h.HandOffID = hl.HandOffID', array())
														->where('h.HandOffStringLocalization =?',$handOffStringLocalization)
														->where('h.HandOffID <> ?',$handoffID)
														->orWhere('hl.HandBackStringLocalization =?',$handOffStringLocalization));
																																								
		$entries   = array();				
		foreach ($resultSet as $row) {			
			if( $handoffID != $row["HandOffID"]){
				$entry = new Application_Model_HO();			
				$entry->setHandOffID($row["HandOffID"])				  
					  ->setSignatureName($row["SignatureName"]);
				
				$entries[] = $entry;
			}
		}
		
		return $entries;
	}
	
	private function createArrayData($resultSet){
		$entries = array();
		
		foreach ($resultSet as $row) {
			$entry = array("HandOffID" => $row->HandOffID, "UserID" => $row->UserID, "HandOffTitle" => $row->HandOffTitle,
					"HandOffTotalNumberOfUploadFiles" =>  $row->HandOffTotalNumberOfUploadFiles,
					"HandOffUploadDate" => $row->HandOffUploadDate, "HandOffStartProject" => $row->HandOffStartProject,
					"HandOffClosedDate" => $row->HandOffClosedDate,
		            "HandOffStatus" => $row->HandOffStatus, "HandOffSourceLanguageID" => $row->HandOffSourceLanguageID,
					"HandOffFolderLocation" => $row->HandOffFolderLocation, "HandOffInstruction" => $row->HandOffInstruction,
					"SignatureName" => $row->SignatureName);

			$entries[] = $entry;
		}
		
		return $entries;
	}
	
	private function CreateResultSetEntity($resultSet){
		$entries   = array();
		$userMapper = new Application_Model_Mapper_User();
		$user = new Application_Model_User();	
		$handoffTargetLanguageMapper = new Application_Model_Mapper_HandOffTargetLanguage();
		
		foreach ($resultSet as $row) {

			$userMapper->find($row->UserID, $user);
			$entry = new Application_Model_HO();			
			$entry->setHandOffID($row->HandOffID)
				  ->setUserID($row->UserID)
				  ->setHandOffTitle($row->HandOffStringLocalization)				  				  
				  ->setHandOffStartProject($row->HandOffStartProject)
				  ->setHandOffClosedDate($row->HandOffClosedDate)
				  ->setHandOffStatus($row->HandOffStatus)
		          ->setHandOffSourceLanguageID($row->HandOffSourceLanguageID)
				  ->setHandOffInstruction($row->HandOffInstruction)
				  ->setTotalLanguageNeedLocalized($handoffTargetLanguageMapper->getTotalLanguageNeedLocalized($row->HandOffID))
				  ->setTotalLanguageFinishedLocalized($handoffTargetLanguageMapper->getTotalLanguageFinishedLocalized($row->HandOffID))
				  ->setAllowEditHoRecord($this->isAllowEditHO($row->HandOffStatus, $row->UserID))
				  ->setAllowDeleteHoRecord($this->isAllowDeleteHO($row->HandOffStatus, $row->UserID))
				  ->setAllowShowEditDepentOnRoleAndHoStatus($this->isAllowShowEditDepentOnRoleAndHoStatus($row->HandOffStatus))
				  ->setUserName($user->UserName)
				  ->setSignatureName($row->SignatureName);
			
			$entries[] = $entry;
		}
		
		return $entries;
	}		
			
	private function buildPaginatorWhereQuery($hideCloseHO){
		$auth = Zend_Auth::getInstance();
		
		if($auth->getIdentity()->UserRole == Application_Model_DbTable_Users::USER_CLIENT){
			return $this->buildPaginatorWhereQueryForClient($hideCloseHO);
		}
		
		return $this->buildPaginatorWhereQueryForAdmin($hideCloseHO);
	}
	
	private function buildPaginatorWhereQueryForClient($hideCloseHO){
		$auth = Zend_Auth::getInstance();
		$whereStatement = " UserID = " . $auth->getIdentity()->UserID;
		
		if($hideCloseHO){			
			$whereStatement = $whereStatement ." and HandOffStatus <> '" . Application_Model_DbTable_HOs::HO_CLOSED ."'";
		}

		return $whereStatement;
	}
	
	private function buildPaginatorWhereQueryForAdmin($hideCloseHO){
		$whereStatement = ' 1 = 1';
		if($hideCloseHO){			
			$whereStatement = $whereStatement ." and HandOffStatus <> '" . Application_Model_DbTable_HOs::HO_CLOSED ."'";
		}
		
		return $whereStatement;
	}
		
}
