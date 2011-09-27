<?php

class Application_Model_Mapper_TranslatorEmailTemplate extends Application_Model_Mapper_EmailTemplate
{		
	public function getListEmailTranslator($handoffId){
		$list = array();
		$listTranslatorForOneHo = $this->getData($handoffId);
		
		foreach($listTranslatorForOneHo as $translator){
			foreach($translator as $email){
				if(!in_array($email, $list)){
					$list[] = $email;
				}
			}
		}		
		
		return $list;
	}
			
	public function sendingEmailReportListTranslator($handoffId){
		$configXml = new Zend_Config_Xml(APPLICATION_PATH . '/configs/smtp.xml', APPLICATION_ENV);		
		$sendingEmail = new Application_Model_Mapper_SendingEmail();
		
		$sendingEmail->sendingEmail($configXml->mail->server->sender, $this->getRecipientTo($handoffId), null, $configXml->mail->admin->alias, "Email to Translators  - Report for HO " . $handoffId, $this->getEmailBody($handoffId));	
	}
	
	private function getRecipientTo($handoffId){
		$hoMapper = new Application_Model_Mapper_HO();
		$ho = new Application_Model_HO();
		$hoMapper->find($handoffId, $ho);
		
		$userMapper = new Application_Model_Mapper_User();
		$user = new Application_Model_User();		
		$userMapper->find($ho->UserID, $user);		
					
		return $user->JtepmEmail;
		
	}	
		
	private function getEmailBody($handoffId){
		
		$listTranslatorForOneHo = $this->getListEmailTranslator($handoffId);		
		$hoMapper = new Application_Model_Mapper_HO();
		$ho = new Application_Model_HO();		
		$hoMapper->find($handoffId, $ho);
		
		$languageMapper = new Application_Model_Mapper_Language();
		$language = new Application_Model_Language();
		$languageMapper->find($ho->HandOffSourceLanguageID, $language);

		
		$strBody = 'Dear Jonckers PM, <br/>For your information, The String-based Handoff "' . $ho->HandOffTitle . '" has been sent to the following Translators:<br/>';		
		$strBody .= join(", ", $listTranslatorForOneHo) . "<br/><br/>";
		
		$tableString = "<table border='1' cellspacing='0' width='100%'>
							<tr>
								<td>Source Language</td>
								<td>Target Language</td>
								<td>Informed Translator</td>
							</tr>";
							
		foreach($this->getData($handoffId) as $key => $value){
			$tableString .= "<tr><td>" . $language->LanguageName . "</td><td>" . $key . "</td><td>" . join(", ", $value) . "</td></tr>";
		}
		
		$tableString .= "</table> <br/><br/>";
		$strBody .= $tableString;
		$strBody .= "Link to handoff: " . " <a href='" . parent::fullUrl("/ho/viewdetail/handoffid/" . $handoffId) . "' >" . parent::fullUrl("/ho/viewdetail/handoffid/" . $handoffId) . "</a><br/><br/>";
		
			
			
		$strBody .= '<font class="Apple-style-span" size="2" face="verdana"><p><strong><span style="font-family: Verdana, sans-serif; color: rgb(11, 119, 145); font-size: 10pt; ">Bento</span></strong><strong><span style="font-family: Verdana, sans-serif; color: gray; font-size: 10pt; ">|</span></strong><span style="font-family: Verdana, sans-serif; color: gray; font-size: 10pt; ">&nbsp;Automatic email</span><span style="font-family: Verdana, sans-serif; color: rgb(127, 127, 127); font-size: 10pt; ">, Customer Solutions Department&nbsp;</span><span style="font-family: Verdana, sans-serif; color: gray; font-size: 10pt; ">| Jonckers Translation &amp; Engineering |</span><a href="http://www.jonckers.com/"><span style="font-family: Verdana, sans-serif; color: purple; font-size: 10pt; ">www.jonckers.com</span></a><o:p></o:p></p><p><b><i><span style="font-family: Arial, sans-serif; color: rgb(74, 68, 42); font-size: 8pt; ">The information contained in this entire e-mail is confidential and/or privileged. This e-mail is intended to be read or used solely by the addressee. If the reader of this e-mail is not the intended recipient, you are hereby notified that any use, dissemination, distribution, publication or copying of this e-mail is prohibited. Please do not reply to this unmonitored email address. If you receive this e-mail in error, please destroy it and notify&nbsp;</span></i></b><a href="mailto:admin-fms@jonckers.be"><b><i><span style="font-family: Arial, sans-serif; font-size: 8pt; ">admin-fms@jonckers.be</span></i></b></a><b><i><span style="font-family: Arial, sans-serif; color: rgb(74, 68, 42); font-size: 8pt; ">.</span></i></b></p></font><p></p><p></p>';
		
		return $strBody;
	}
	
	private function  getData($handoffId){
		$select = parent::getDbTable()->getAdapter()->select();	
		$select->from(array("h" => "handoffs"), array())
			   ->joinInner(array("hl" => "handofftargetlanguages"), "h.HandOffID = hl.HandOffID", "hl.LanguageID")
			   ->joinInner(array("l" => "languages"), "l.LanguageID = hl.LanguageID", "l.LanguageName")
			   ->joinInner(array("ul" => "userLanguages"), "h.HandOffSourceLanguageID = ul.SourceLanguageID and hl.LanguageID = ul.TargetLanguageID", array())
			   ->joinInner(array("u" => "users"), " u.UserID = ul.UserID", "u.UserEmail")
			   ->where("h.HandOffID = ?", $handoffId );
		
		
		$allTranslatorExistenceForOneHo = parent::getDbTable()->getAdapter()->fetchAll($select);	
		
		$select = parent::getDbTable()->getAdapter()->select()
													->from(array("l" => "languages" ) , array("l.LanguageID", "l.LanguageName"))
													->join( array("hl" => "handofftargetlanguages"), "hl.LanguageID = l.LanguageID")
													->where("HandOffID=?", $handoffId);	
		$listTargetLanguage = 	parent::getDbTable()->getAdapter()->fetchAll($select);			
		$listTranslatorForOneHo = array();
		
		foreach($listTargetLanguage as $targetLanguage){
			$listTranslatorForOneHo[$targetLanguage["LanguageName"]] = Array();
		}
		
		
		foreach($allTranslatorExistenceForOneHo as $translator){			
			$listTranslatorForOneHo[utf8_encode($translator["LanguageName"])] = array_merge(
			$listTranslatorForOneHo[utf8_encode($translator["LanguageName"])], array($translator["UserEmail"]));			
		}	
		
		return $listTranslatorForOneHo;
	}
}

