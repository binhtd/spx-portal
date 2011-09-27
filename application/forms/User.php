<?php

class Application_Form_User extends Zend_Form
{	
    public function init()
    {
        $this->setName('User');
		$UserID = new Zend_Form_Element_Hidden("UserID");
		$UserID->addFilter('Int');
		
		$UserName = new Zend_Form_Element_Text("UserName");
		$UserName->setLabel("User Name")
				 ->setRequired(true)
				 ->setAttrib("size", "40")
				 ->setAttrib("style", "width:370px")
				 ->setAttrib("maxlength", "50")
				 ->addFilter("StripTags")	
				 ->addFilter("StringTrim")
				 ->addValidator("NotEmpty");
				 
		$UserEmail = new Zend_Form_Element_Text("UserEmail");
		$UserEmail->setLabel("User Email")
				  ->setRequired(true)
				  ->setAttrib("size", "40")
				  ->setAttrib("style", "width:370px")
				  ->setAttrib("maxlength", "50")
				  ->addFilter("StripTags")
				  ->addFilter("StringTrim")
				  ->addValidator("NotEmpty")
				  ->addValidator('EmailAddress'); 
				  
		$UserLoginName = new Zend_Form_Element_Text("UserLoginName");
		$UserLoginName->setLabel("User Login Name")
					  ->setRequired(true)
					  ->setAttrib("style", "width:204px")
					  ->setAttrib("size", "30")
					  ->setAttrib("maxlength", "30")
					  ->addFilter("StripTags")
					  ->addFilter("StringTrim")
					  ->addValidator("NotEmpty");		
					  
		$UserPassword = new Zend_Form_Element_Password('UserPassword');		
		$UserPassword->setLabel("Password")
				 ->setRequired(true)
				 ->setAttrib("style", "width:204px")
				 ->setAttrib("size", "30")
				 ->setAttrib("maxlength", "30")
				 ->addFilter("StripTags")
				 ->addFilter("StringTrim")
				 ->addValidator('StringLength', false, array(8,30));
				 
		$UserConfirmPassword = new Zend_Form_Element_Password('UserConfirmPassword');		
		$UserConfirmPassword->setLabel("Confirm Password")
				 ->setRequired(true)
				 ->setAttrib("size", "30")
				 ->setAttrib("style", "width:204px")
				 ->setAttrib("maxlength", "30")
				 ->addFilter("StripTags")
				 ->addFilter("StringTrim")
				 ->addValidator('StringLength', false, array(8,30))
				 ->addValidator('Identical', false, array('token' => 'UserPassword'));
				 
				 
						
		$Role = new Zend_Form_Element_Radio("UserRole"); 						
		$Role->setLabel("Roles")
			 ->addMultiOptions(array(Application_Model_DbTable_Users::USER_JTEPM => "User Is Jonckers PM",
								Application_Model_DbTable_Users::USER_TRANSLATOR => "User Is Translator",
								Application_Model_DbTable_Users::USER_CLIENT => "User Is Client"))
			->setSeparator("")
			->setValue(Application_Model_DbTable_Users::USER_CLIENT);
					
		$JtepmEmail = new Zend_Form_Element_Text("JtepmEmail");
		$JtepmEmail->setLabel("JTE Project Manager Email")
					  ->setRequired(true)
					  ->setAttrib("size", "40")
					  ->setAttrib("style", "width:370px")
					  ->setAttrib("maxlength", "50")
					  ->addFilter("StripTags")
					  ->addFilter("StringTrim")
					  ->addValidator("NotEmpty")
					  ->addValidator('EmailAddress'); 
			
		$UserIsActive = new Zend_Form_Element_CheckBox("UserIsActive");	
		$UserIsActive->setLabel("User Is Active")
					 ->setUncheckedValue("N")
					 ->setCheckedValue("Y")
					 ->setValue("Y");
		
		$SourcelanguageID = new Zend_Form_Element_MultiCheckbox('SourcelanguageID');		
		$SourcelanguageID->setLabel('Source languageID')
				 ->setName("SourcelanguageID[]")
				 ->setRequired(true)
				 ->setRegisterInArrayValidator(false)
				 ->addFilter('StripTags')
			     ->addFilter('StringTrim');	
		$SourcelanguageID->setDecorators(array(
			'ViewHelper',
			'Description',
			array( array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'style'=>'width: 400px; height:167px; overflow:scroll; border: 1px solid #7F9DB9;', "id" => "TranslatorTargetlanguageID")),
			'Errors',
			array( array('dd' => 'HtmlTag'), array('tag' => 'dd')),			
			array('Label', array('tag' => 'dt')),
		));		 
		
		$languageMapper = new Application_Model_Mapper_Language();			 
		$sourceListLanguage = $languageMapper->getSourceLanguageActive();		 
		foreach($sourceListLanguage as $language){
			$SourcelanguageID->addMultiOption($language->LanguageID, $language->LanguageName);			
		}	
		
		$submit = new Zend_Form_Element_Submit("submit");
		$submit->setAttrib("id","submitbutton");
	
		$this->addElements(array($UserName, $UserEmail, $UserLoginName, $UserPassword, $UserConfirmPassword, 
				$Role, $JtepmEmail, $SourcelanguageID, $UserIsActive, $submit, $UserID));
    }


}

