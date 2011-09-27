<?php

class Application_Model_DbTable_Languages extends Zend_Db_Table_Abstract
{
    protected $_name = 'languages';

	protected $_dependentTables = array('Application_Model_DbTable_HOs', 'Application_Model_DbTable_HandOffTargetLanguages', 'Application_Model_DbTable_UserLanguages', 'Application_Model_DbTable_HandoffTargetLanguagesHistory');
}

