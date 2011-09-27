<?php
require_once APPLICATION_PATH. '/../library/Export/PHPExcel.php';

class Application_Model_Mapper_ExportTranslationForTranslator extends Application_Model_Mapper_TranslationForTranslator{
	public function ExportToExcel($startDate, $endDate){
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
		$objPHPExcel->getActiveSheet()->setTitle('Tranlation Translators');
		$this->setExcelExportDataInRange($startDate, $endDate, $objPHPExcel, $startColumn, $startRow);				
		$startRow = 3;
		$this->setExportCountTranslationStringForEachTranslator($startDate, $endDate, $objPHPExcel, $startColumn, $startRow);
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=". "XLSX_Tranlation_Strings_For_Translators_Export_" . date("Y-m-d : H:i:s", time()) . ".xlsx");
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
	
	public function ExportToCSV($startDate, $endDate){
		$csv = $this->csvHead();
		$rows = $this->getExportDataInRange($startDate, $endDate);
				
		foreach($rows as $row){
			$csv .= $row["HandOffStartProject"] . "\t" . $row["SignatureName"] . '\tBento' . str_pad($row["HandOffID"], 8, "0", STR_PAD_LEFT) . "\t";
			$csv .= $row["HandBackStringLocalization"] . "\t" . utf8_encode($row["UserName"]) . "\r\n";	
		}
			
		$csv = chr(255).chr(254).mb_convert_encoding($csv, "UTF-16LE", "UTF-8");
 
		header("Content-type: application/x-msdownload");
		header("Content-disposition: csv; filename=" . "CSV_Tranlation_Strings_For_Translators_Export_" . date("Y-m-d : H:i:s", time()) . ".csv; size=".strlen($csv));
		echo $csv;
		exit();	
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
					->setCellValueByColumnAndRow( $startColumn++, $startRow, "Handoff Name");
		
		$objPHPExcel->getActiveSheet()			
					->setCellValueByColumnAndRow( $startColumn++, $startRow, "Localized Strings");
					
		$objPHPExcel->getActiveSheet()			
					->setCellValueByColumnAndRow( $startColumn++, $startRow, "Translator Name");		
	}
	
	private function setExcelExportDataInRange($startDate, $endDate, $objPHPExcel, $startColumn, $startRow){
		$bodyString = "";
		$rows = $this->getExportDataInRange($startDate, $endDate);
		$column = $startColumn;
		
		foreach($rows as $row){						
			$startRow++;
			$objPHPExcel->getActiveSheet()
					->setCellValueByColumnAndRow( $startColumn++, $startRow, $row["HandOffStartProject"]);
					
			$objPHPExcel->getActiveSheet()
					->setCellValueByColumnAndRow( $startColumn++, $startRow, $row["SignatureName"]);
					
			$objPHPExcel->getActiveSheet()
					->setCellValueByColumnAndRow( $startColumn++, $startRow, "Bento" . str_pad($row["HandOffID"], 8, "0", STR_PAD_LEFT));
			$objPHPExcel->getActiveSheet()
					->setCellValueByColumnAndRow( $startColumn++, $startRow, $row["HandBackStringLocalization"]);					
			$objPHPExcel->getActiveSheet()
					->setCellValueByColumnAndRow( $startColumn++, $startRow, utf8_encode($row["UserName"]));	
			$startColumn = $column;			
		}													
	}
	
	private function getExportDataInRange($startDate, $endDate){
		$select = parent::getDbTable()->getAdapter()->select();	
		
		$rows = parent::getDbTable()->getAdapter()->fetchAll(
								$select->from(array('t' => 'translationfortranslators'), 't.HandBackStringLocalization')
										->join(array('u' => 'users'), 't.UserID = u.UserID', "u.UserName")
										->join(array('h' => 'handoffs'), 't.HandOffID = h.HandOffID', array("h.HandOffStartProject", "h.HandOffID", "h.SignatureName"))
										->where($this->getExportDataInRangeWhereCondition($startDate, $endDate))
										->order("h.HandOffID desc"));		
		return $rows;
	}
	
	private function setExportCountTranslationStringForEachTranslator($startDate, $endDate, $objPHPExcel, $startColumn, $startRow){
		$countTranslation = $objPHPExcel->createSheet();
		$countTranslation->setTitle('Count Translation');
		$this->setExportCountTranslationStringForEachTranslatorHeader($countTranslation, $startColumn, $startRow);
		$this->setExportCountTranslationStringForEachTranslatorBody($startDate, $endDate, $countTranslation, $startColumn, $startRow);		
	}
	
	private function setExportCountTranslationStringForEachTranslatorHeader($objPHPSheet, $startColumn, $startRow){
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
					
		$objPHPSheet->setSharedStyle($sharedStyle1, "A" .$startRow. ":B" .$startRow);
		$objPHPSheet->setCellValueByColumnAndRow( $startColumn++, $startRow, "Row Labels");
		$objPHPSheet->setCellValueByColumnAndRow( $startColumn++, $startRow, "Count of Localized Strings");
	}
	
	private function setExportCountTranslationStringForEachTranslatorBody($startDate, $endDate, $objPHPSheet, $startColumn, $startRow){
		$rows = $this->getCountTranslationStringForEachTranslator($startDate, $endDate);
		$column = $startColumn;
						
		foreach($rows as $row){						
			$startRow++;
			$objPHPSheet->setCellValueByColumnAndRow( $startColumn++, $startRow, utf8_encode($row["UserName"]));	
			$objPHPSheet->setCellValueByColumnAndRow( $startColumn++, $startRow, $row["count"]);					
			
			$startColumn = $column;			
		}
		$startColumn = $column;	
		$this->setExportCountTranslationStringForEachTranslatorFooter($objPHPSheet, $startColumn, $startRow, $startDate, $endDate);
	}
	
	private function setExportCountTranslationStringForEachTranslatorFooter($objPHPSheet, $startColumn, $startRow, $startDate, $endDate){
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
		
		$startRow++;
		$objPHPSheet->setSharedStyle($sharedStyle1, "A" .$startRow. ":B" .$startRow);
		$objPHPSheet->setCellValueByColumnAndRow( $startColumn++, $startRow, "Grand Total");
		$objPHPSheet->setCellValueByColumnAndRow( $startColumn++, $startRow, count($this->getExportDataInRange($startDate, $endDate)));
	}
	
	private function getCountTranslationStringForEachTranslator($startDate, $endDate){
		$select = parent::getDbTable()->getAdapter()->select();	
		$rows = parent::getDbTable()->getAdapter()->fetchAll(
								 $select->from( array('u' => 'users'), array("u.UserName", "COUNT(u.UserName) as count"))
										->join( array('t' => 'translationfortranslators'), 't.UserID = u.UserID', array())
										->join( array('h' => 'handoffs'), 'h.HandOffID = t.HandOffID', array())
										->where($this->getExportDataInRangeWhereCondition($startDate, $endDate))
										->group("u.UserName"));		
		return $rows;
	}
	
	
	private function getExportDataInRangeWhereCondition($startDate, $endDate){
		$where = " (1 = 1)";
		if($startDate == null && $endDate !=null ){
			$where .= parent::getDbTable()->getAdapter()->quoteInto(" and h.HandOffStartProject < ?", $endDate);	
		}
		
		if($startDate != null && $endDate ==null ){
			$where .= parent::getDbTable()->getAdapter()->quoteInto(" and h.HandOffStartProject > ?", $startDate);		
		}
		
		if($startDate != null && $endDate !=null ){
			$where .= parent::getDbTable()->getAdapter()->quoteInto(" and h.HandOffStartProject <= ?", $endDate);		
			$where .= parent::getDbTable()->getAdapter()->quoteInto(" and h.HandOffStartProject >= ?", $startDate);			
		}
		
		return $where;
	}
	private function csvHead(){
		return "Handoff Date\tSignatureName\tHandoff Name\tLocalized Strings\tTranslator Name\r\n";		
	}	
}