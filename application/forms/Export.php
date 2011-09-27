<?php

class Application_Form_Export extends Zend_Form
{
	const EXPORT_TYPE_EXCEL = "Excel";
	const EXPORT_TYPE_CSV = "Csv";
	const REPORTTYPE_CLIENT_INVOICE = "Client invoice";
	const REPORTTYPE_TRANSLATOR_PO = "Translators PO";
    public function init()
    {
        $this->setName('Export');
		
		$HandOffStartProject = new Zend_Form_Element_Text('HandOffStartProject');
		$HandOffStartProject->setLabel('Start Date')
				  ->addFilter('StripTags')
				  ->addFilter('StringTrim')
				  ->addValidator('NotEmpty')
				  ->addValidator('date', array('YYYY-mm-dd'));
		
		$HandOffEndProject = new Zend_Form_Element_Text('HandOffEndProject');
		$HandOffEndProject->setLabel('End Date')
				  ->addFilter('StripTags')
				  ->addFilter('StringTrim')
				  ->addValidator('NotEmpty')
				  ->addValidator('date', array('YYYY-mm-dd'));	
		$ReportType = new Zend_Form_Element_Radio("ReportType"); 						
		$ReportType->setLabel("Export type:")
			 ->addMultiOptions(array(
			 Application_Form_Export::REPORTTYPE_CLIENT_INVOICE => Application_Form_Export::REPORTTYPE_CLIENT_INVOICE,
			 Application_Form_Export::REPORTTYPE_TRANSLATOR_PO => Application_Form_Export::REPORTTYPE_TRANSLATOR_PO))
			->setSeparator("")
			->setValue(Application_Form_Export::REPORTTYPE_CLIENT_INVOICE);	
		
		
		$ExportType = new Zend_Form_Element_Radio("ExportType"); 						
		$ExportType->setLabel("Export format:")
			 ->addMultiOptions(array(
			 Application_Form_Export::EXPORT_TYPE_EXCEL => Application_Form_Export::EXPORT_TYPE_EXCEL,
			 Application_Form_Export::EXPORT_TYPE_CSV => Application_Form_Export::EXPORT_TYPE_CSV))
			->setSeparator("")
			->setValue(Application_Form_Export::EXPORT_TYPE_EXCEL);	
		
		$submit = new Zend_Form_Element_Submit("Export");
		$submit->setAttrib("id","submitbutton")
			   ->setLabel('Export');
			
		$this->addElements(array($HandOffStartProject, $HandOffEndProject, $ReportType , $ExportType, $submit));		
    }
}

