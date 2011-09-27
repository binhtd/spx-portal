<?php
class Application_Model_DbTable_TranslationForTranslators extends Zend_Db_Table_Abstract
{
    protected $_name = 'translationfortranslators';
	protected $_referenceMap = array(
        'TranslationForTranslators_ho' => array(
            'columns'           => array('HandOffID'),
            'refTableClass'     => 'Application_Model_DbTable_HOs',
            'refColumns'        => array('HandOffID'),
            'onDelete'          => self::CASCADE
        ),
		'TranslationForTranslators_user' => array(
            'columns'           => array('UserID'),
            'refTableClass'     => 'Application_Model_DbTable_Users',
            'refColumns'        => array('UserID'),
            'onDelete'          => self::CASCADE
        )
		);
}