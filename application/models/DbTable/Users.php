<?php

class Application_Model_DbTable_Users extends Zend_Db_Table_Abstract
{
    protected $_name = 'users';
	const USER_ADMIN = "Super admin";
	const USER_JTEPM = "Jtepm";
	const USER_TRANSLATOR = "Translator";
	const USER_CLIENT = "Client";
	protected $_dependentTables = array('Application_Model_DbTable_HOs', 'Application_Model_DbTable_UserLanguages' ,'Application_Model_DbTable_TranslationForTranslators');	
}

