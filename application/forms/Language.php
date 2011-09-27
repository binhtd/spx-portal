<?php

class Application_Form_Language extends Zend_Form
{

    public function init()
    {
		$this->setName("Languages");
		
		$LanguageID = new Zend_Form_Element_Hidden("LanguageID");
		$LanguageID->addFilter("int");
		
		$LanguageName = new Zend_Form_Element_Text("LanguageName");
		$LanguageName->setLabel("Language Name")
					 ->setRequired(true)
					 ->setAttrib("maxlength", "100")
					 ->setAttrib("size", "40")
					 ->addFilter("StripTags")
					 ->addFilter("StringTrim")
					 ->addValidator("NotEmpty");
					 
		$LanguageIsActive = new Zend_Form_Element_CheckBox("LanguageIsActive");
		$LanguageIsActive->setLabel("Language is active")
					 ->setUncheckedValue("N")
					 ->setCheckedValue("Y")
					 ->setValue("Y");
		$LanguageIsShowInSourceList = new Zend_Form_Element_CheckBox("LanguageIsShowInSourceList");
		$LanguageIsShowInSourceList->setLabel("Present in Source List")
					 ->setUncheckedValue("N")
					 ->setCheckedValue("Y")
					 ->setValue("Y");
		$LanguageIsShowInTargetList = new Zend_Form_Element_CheckBox("LanguageIsShowInTargetList");
		$LanguageIsShowInTargetList->setLabel("Present in Target List")
					 ->setUncheckedValue("N")
					 ->setCheckedValue("Y")
					 ->setValue("Y");			 
		$LanguageIsShowInTranslatorList = new Zend_Form_Element_CheckBox("LanguageIsShowInTranslatorList");
		$LanguageIsShowInTranslatorList->setLabel("Present in Translator List")
					 ->setUncheckedValue("N")
					 ->setCheckedValue("Y")
					 ->setValue("Y");			 
		
		
		$submit = new Zend_Form_Element_Submit("submit");
		$submit->setAttrib("id","submitbutton");
		
		$this->addElements(array($LanguageName, $LanguageIsActive, $LanguageIsShowInSourceList, $LanguageIsShowInTargetList, $LanguageIsShowInTranslatorList, $submit, $LanguageID));
    }
}
?>

