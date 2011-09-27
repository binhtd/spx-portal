<?php

class ExportController extends Zend_Controller_Action
{
    public function init()
    {        		
		$this->view->activeExport = true;
    }

    public function indexAction()
    {
		$form = new Application_Form_Export();
		$this->view->form = $form;
		
		if(!$this->getRequest()->isPost()){								
			return;
		}
		
		$formData = $this->getRequest()->getPost();
		if(!$form->isValid($formData)){
			$form->populate($formData);
			return;
		}			
		
		
		if($form->getValue("ReportType") == Application_Form_Export::REPORTTYPE_CLIENT_INVOICE){
			$exportHoMapper = new Application_Model_Mapper_ExportHO();
			if($form->getValue("ExportType") == Application_Form_Export::EXPORT_TYPE_EXCEL){
				$exportHoMapper->ExportToExcel(0, $form->getValue("HandOffStartProject"), $form->getValue("HandOffEndProject"));
			}
			
			if($form->getValue("ExportType") == Application_Form_Export::EXPORT_TYPE_CSV){
				$exportHoMapper->ExportToCSV($form->getValue("HandOffStartProject"), $form->getValue("HandOffEndProject"));
			}
			
			return;			
		}
		
		if($form->getValue("ReportType") == Application_Form_Export::REPORTTYPE_TRANSLATOR_PO){
			$exportTranslationForTranslatorMapper = new Application_Model_Mapper_ExportTranslationForTranslator();
			
			if($form->getValue("ExportType") == Application_Form_Export::EXPORT_TYPE_EXCEL){
				$exportTranslationForTranslatorMapper->ExportToExcel($form->getValue("HandOffStartProject"), $form->getValue("HandOffEndProject"));
			}
			
			if($form->getValue("ExportType") == Application_Form_Export::EXPORT_TYPE_CSV){
				$exportTranslationForTranslatorMapper->ExportToCSV($form->getValue("HandOffStartProject"), $form->getValue("HandOffEndProject"));
			}
			
			return;
		}		
    }
}