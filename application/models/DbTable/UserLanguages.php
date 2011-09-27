<?php

class Application_Model_DbTable_UserLanguages extends Zend_Db_Table_Abstract
{
    protected $_name = 'userlanguages';
	protected $_referenceMap = array(
        'userlanguages_user' => array(
            'columns'           => array('UserID'),
            'refTableClass'     => 'Application_Model_DbTable_Users',
            'refColumns'        => array('UserID'),
            'onDelete'          => self::CASCADE
        ),
		'userlanguages_languages' => array(
            'columns'           => array('SourceLanguageID'),
            'refTableClass'     => 'Application_Model_DbTable_Languages',
            'refColumns'        => array('LanguageID'),
            'onDelete'          => self::CASCADE
        ),
		'userlanguages_languages1' => array(
            'columns'           => array('TargetLanguageID'),
            'refTableClass'     => 'Application_Model_DbTable_Languages',
            'refColumns'        => array('LanguageID'),
            'onDelete'          => self::CASCADE
        )
		);	
}

