<?php

class Application_Form_HO extends Zend_Form
{

    public function init()
    {
        $this->setName('HO');
		$HandOffID = new Zend_Form_Element_Hidden('HandOffID');
		$HandOffID->addFilter('Int');
		
		$UserID = new Zend_Form_Element_Hidden('UserID');
		$UserID->addFilter('Int');
		
		$HandOffTitle = new Zend_Form_Element_Text('HandOffTitle');
		$HandOffTitle->setLabel('Source String')
				 ->setRequired(true)
				 ->addFilter('StripTags')	
				 ->addFilter('StringTrim')
				 ->addValidator('NotEmpty');
				 
		$HandOffTotalNumberOfUploadFiles = new Zend_Form_Element_Hidden('HandOffTotalNumberOfUploadFiles');		 
		$HandOffTotalNumberOfUploadFiles->addFilter('Int');
		
		$HandOffStartProject = new Zend_Form_Element_Text('HandOffStartProject');
		$HandOffStartProject->setLabel('Hand Off Start Project')
				  ->addFilter('StripTags')
				  ->addFilter('StringTrim')
				  ->addValidator('NotEmpty')
				  ->addValidator('date', array('YYYY-mm-dd'));
				  	
		$HandOffStatus = new Zend_Form_Element_Select('HandOffStatus');		
		$HandOffStatus->setLabel('Hand Off Status')
				 ->setMultiOptions(array(
				 Application_Model_DbTable_HOs::HO_CREATED=> Application_Model_DbTable_HOs::HO_CREATED,
				 Application_Model_DbTable_HOs::HO_CANCELLED=> Application_Model_DbTable_HOs::HO_CANCELLED,
				 Application_Model_DbTable_HOs::HO_UPLOADED=> Application_Model_DbTable_HOs::HO_UPLOADED,
				 Application_Model_DbTable_HOs::HO_RECEIVED=> Application_Model_DbTable_HOs::HO_RECEIVED,
				 Application_Model_DbTable_HOs::HB_COMPLETED=> Application_Model_DbTable_HOs::HB_COMPLETED,
				 Application_Model_DbTable_HOs::HO_CLOSED=> Application_Model_DbTable_HOs::HO_CLOSED))
				 ->addFilter('StripTags')
			     ->addFilter('StringTrim');
	
		$languageMapper = new Application_Model_Mapper_Language();		
		$sourceListLanguage = $languageMapper->getSourceLanguageActive();
	
		$HandOffSourceLanguageID = new Zend_Form_Element_Select('HandOffSourceLanguageID');		
		$HandOffSourceLanguageID->setLabel('Hand Off Source LanguageID')
				 ->setRequired(true)
				 ->setRegisterInArrayValidator(false)
				 ->addFilter('StripTags')
			     ->addFilter('StringTrim');
		
		foreach($sourceListLanguage as $language){
			$HandOffSourceLanguageID->addMultiOption( $language->LanguageID, $language->LanguageName);
		}
		
		$HandOffListTargetLanguageID = new Zend_Form_Element_MultiCheckbox('HandOffListTargetLanguageID');		
		$HandOffListTargetLanguageID->setLabel('Hand Off List Target LanguageID')
				 ->setName("HandOffListTargetLanguageID[]")
				 ->setRequired(true)
				 ->setRegisterInArrayValidator(false)
				 ->addFilter('StripTags')
			     ->addFilter('StringTrim');	
		$targetListLanguage = $languageMapper->getTargetLanguageActive();		 
		foreach($targetListLanguage as $language){
			$HandOffListTargetLanguageID->addMultiOption($language->LanguageID, $language->LanguageName);
		}
		
		$HandOffFolderLocation = new Zend_Form_Element_Hidden('HandOffFolderLocation');
		
		$content = new Zend_Form_Element_Textarea('content');
		$content->setLabel('Hand Off Instruction')
								->addFilter('StringTrim')
								->addValidator('NotEmpty');	
		$SignatureName = new Zend_Form_Element_Text('SignatureName');
		$SignatureName->setLabel('Signature/Name')
				 ->setRequired(true)
				 ->addFilter('StripTags')	
				 ->addFilter('StringTrim')
				 ->addValidator('NotEmpty');
				 					 
		$saveupload = new Zend_Form_Element_Submit('saveupload');
		$saveupload->setAttrib('id','saveuploadlaterbutton');
		
		$saveuploadlater = new Zend_Form_Element_Submit('saveuploadlater');
		$saveuploadlater->setAttrib('id','submitbutton');
		
		$cancel = new Zend_Form_Element_Submit('cancel');
		$cancel->setAttrib('id','cancelbutton');
		
		$this->addElements(array($HandOffTitle, $HandOffStartProject, $HandOffStatus, $HandOffSourceLanguageID, 
				$HandOffListTargetLanguageID, $content, $SignatureName, $saveupload, $saveuploadlater, $cancel, $HandOffID, $UserID, $HandOffTotalNumberOfUploadFiles, $HandOffFolderLocation));
    }


}

