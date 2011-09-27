<?php

class Application_Model_DbTable_HandoffTargetLanguagesHistory extends Zend_Db_Table_Abstract
{
    protected $_name = 'handofftargetlanguageshistory';
	
	protected $_referenceMap = array(
	 'HandoffTargetLanguagesHistory_ho' => array(
		'columns'           => array('HandOffID'),
		'refTableClass'     => 'Application_Model_DbTable_HOs',
		'refColumns'        => array('HandOffID'),
		'onDelete'          => self::CASCADE
	),'HandoffTargetLanguagesHistory_languages' => array(
		'columns'           => array('LanguageID'),
		'refTableClass'     => 'Application_Model_DbTable_Languages',
		'refColumns'        => array('LanguageID'),
		'onDelete'          => self::CASCADE
	));	
}	