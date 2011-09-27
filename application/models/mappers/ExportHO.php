<?php
require_once APPLICATION_PATH. '/../library/Export/PHPExcel.php';
class Application_Model_Mapper_ExportHO extends Application_Model_Mapper_HO{
	public function ExportToExcel($handoffID, $startDate, $endDate){
		$objPHPExcel = new PHPExcel();
		$startColumn = 0;
		$startRow = 1;
		$objPHPExcel->getProperties()->setCreator("BinhTD")
									 ->setLastModifiedBy("BinhTD")
									 ->setTitle("Office 2007 XLSX")
									 ->setSubject("Office 2007 XLSX")
									 ->setDescription("Generate when using spx portal")
									 ->setKeywords("spx portal")
									 ->setCategory("localization");
		
		$this->setExcelHeaderColumn($objPHPExcel, $startColumn, $startRow);	
		$startRow++;
		if($startDate == null && $endDate ==null ){			
			$this->setExcelExportDataForSpecifyHandoff($handoffID, $objPHPExcel, $startColumn, $startRow);
		}
		else{
			$this->getExcelExportDataInRange($startDate, $endDate, $objPHPExcel, $startColumn, $startRow);
		}
				
		$objPHPExcel->getActiveSheet()->setTitle('Translation Strings');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=". "XLSX_String_Handoff_Export_" . date("Y-m-d : H:i:s", time()) . ".xlsx");
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
	
	private function setExcelHeaderColumn($objPHPExcel, $startColumn, $startRow){
		$languageMapper = new Application_Model_Mapper_Language();
		$languages = $languageMapper->getTargetLanguageActive();
		
		$sharedStyle1 = new PHPExcel_Style();
		$sharedStyle1->applyFromArray(
		array('fill' 	=> array(
									'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
									'color'		=> array('argb' => 'FFCCFFCC')
								),
			  'borders' => array(
									'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
									'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
								)
			 ));
			 
		$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle1, "A" .$startRow. ":FZ" .$startRow);
		$objPHPExcel->getActiveSheet()
					->setCellValueByColumnAndRow( $startColumn++, $startRow, "Handoff Date");
					
		$objPHPExcel->getActiveSheet()
					->setCellValueByColumnAndRow( $startColumn++, $startRow, "Signature/Name");			
		 
		$objPHPExcel->getActiveSheet()			
					->setCellValueByColumnAndRow( $startColumn++, $startRow, "Loc Item Count");
		
		foreach($languages as $language){
			$objPHPExcel->getActiveSheet()
					->setCellValueByColumnAndRow( $startColumn++, $startRow, $language->LanguageName);					
		}
		
	}
	
	private function getExcelExportDataInRange($startDate, $endDate, $objPHPExcel, $startColumn, $startRow){
		$bodyString = "";
		$rows = $this->getExportDataInRange($startDate, $endDate);		
		foreach($rows as $row){
			$this->setExcelExportDataForSpecifyHandoff($row->HandOffID, $objPHPExcel, $startColumn, $startRow);
			$startRow++;
		}													
	}
	
	private function getExportDataInRange($startDate, $endDate){
		$where = " (1 = 1)";
		
		if($startDate == null && $endDate !=null ){
			$where .= parent::getDbTable()->getAdapter()->quoteInto(" and HandOffStartProject < ?", $endDate);	
		}
		
		if($startDate != null && $endDate ==null ){
			$where .= parent::getDbTable()->getAdapter()->quoteInto(" and HandOffStartProject > ?", $startDate);		
		}
		
		if($startDate != null && $endDate !=null ){
			$where .= parent::getDbTable()->getAdapter()->quoteInto(" and HandOffStartProject <= ?", $endDate);		
			$where .= parent::getDbTable()->getAdapter()->quoteInto(" and HandOffStartProject >= ?", $startDate);			
		}
		
		$rows = parent::getDbTable()->fetchAll(parent::getDbTable()->select()
													->where($where)
													->order("HandOffID desc"));
		return $rows;
	}

	private function setExcelExportDataForSpecifyHandoff($handoffID, $objPHPExcel, $startColumn, $startRow){	
		$languageMapper = new Application_Model_Mapper_Language();
		$languages = $languageMapper->getTargetLanguageActive();
		
		$ho = new Application_Model_HO();
		parent::find($handoffID, $ho);
		
		$handOffTargetLanguageMapper = new Application_Model_Mapper_HandOffTargetLanguage();			
		$handOffTargetLanguages = $handOffTargetLanguageMapper->getTargetLanguages($handoffID);
		$objPHPExcel->getActiveSheet()
					->setCellValueByColumnAndRow( $startColumn++, $startRow, $ho->HandOffStartProject);		
		$objPHPExcel->getActiveSheet()
					->setCellValueByColumnAndRow( $startColumn++, $startRow, $ho->SignatureName);		
		$objPHPExcel->getActiveSheet()
					->setCellValueByColumnAndRow( $startColumn++, $startRow, $this->getTotalLocalizationItem($handOffTargetLanguages));
					
		foreach($languages as $language){			
			if($language->LanguageID == $ho->HandOffSourceLanguageID){
				$objPHPExcel->getActiveSheet()
				->getStyleByColumnAndRow($startColumn, $startRow)
				->getFont()
				->setBold(true);			
				
				$objPHPExcel->getActiveSheet()
				->setCellValueByColumnAndRow( $startColumn++, $startRow, ($ho->HandOffTitle));
				continue;
			}
			
			$objPHPExcel->getActiveSheet()
				->setCellValueByColumnAndRow( $startColumn++, $startRow, ($this->getLocalizationString($language->LanguageID, $handOffTargetLanguages)));
			
		}
	}
	
	private function getTotalLocalizationItem($handOffTargetLanguages){
		$count = 0;
		foreach($handOffTargetLanguages as $targetLanguage){
			if( (string)$targetLanguage->HandBackStringLocalization != "" ){
				$count++;
			}
		}
		
		return $count;
	}
	
	private function getLocalizationString($languageID, $handOffTargetLanguages){
		foreach($handOffTargetLanguages as $targetLanguage){
			if($languageID == $targetLanguage->LanguageID){
				return (string)$targetLanguage->HandBackStringLocalization;
			}		
		}
		
		return "";
	}
	
	public function ExportToCSV($startDate, $endDate){
		$csv = $this->csvHead();
		$rows = $this->getExportDataInRange($startDate, $endDate);
				
		foreach($rows as $row){
			$csv .= $this->getCSVExportDataForSpecifyHandoff($row->HandOffID);
		}
			
		$csv = chr(255).chr(254).mb_convert_encoding($csv, "UTF-16LE", "UTF-8");
 
		header("Content-type: application/x-msdownload");
		header("Content-disposition: csv; filename=" . "CSV_String_Handoff_Export_" . date("Y-m-d : H:i:s", time()) . ".csv; size=".strlen($csv));
		echo $csv;
		exit();	
	}
	
	private function csvHead(){
		$languageMapper = new Application_Model_Mapper_Language();
		$languages = $languageMapper->getTargetLanguageActive();
		
		$headString = "Handoff Date\tSignature/Name\tLoc Item Count\t";
		foreach($languages as $language){
			$headString .= $language->LanguageName . "\t";
		}	
		$headString .= "\r\n";	
		return $headString;
	}
	
	private function getCSVExportDataForSpecifyHandoff($handoffID){	
		$ho = new Application_Model_HO();
		parent::find($handoffID, $ho);
		$handOffTargetLanguageMapper = new Application_Model_Mapper_HandOffTargetLanguage();			
		$handOffTargetLanguages = $handOffTargetLanguageMapper->getTargetLanguages($handoffID);
		
		$languageMapper = new Application_Model_Mapper_Language();
		$languages = $languageMapper->getTargetLanguageActive();
		
		$bodyString =  $ho->HandOffStartProject. "\t" . $ho->SignatureName . "\t" . $this->getTotalLocalizationItem($handOffTargetLanguages) . "\t";
		
		foreach($languages as $language){			
			if($language->LanguageID == $ho->HandOffSourceLanguageID){
				$bodyString .= $ho->HandOffTitle . "\t";
				continue;
			}
			
			$bodyString .= $this->getLocalizationString($language->LanguageID, $handOffTargetLanguages). "\t";			
		}	
		
		$bodyString .= "\r\n";
		return $bodyString;
	}	
}