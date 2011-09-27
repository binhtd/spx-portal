<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initSwfuploadSession(){
		if (isset($_POST['PHPSESSID']) && strpos($_SERVER['REQUEST_URI'], "/upload")!==false) {
			session_id($_POST['PHPSESSID']);
		}
	}
	
	protected function _initSessionDataHandler(){
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
		$db = Zend_Db::factory($config->resources->db->adapter, array(
			'host'        => $config->resources->db->params->host,
			'username'    => $config->resources->db->params->username,
			'password'    => $config->resources->db->params->password,
			'dbname'    => $config->resources->db->params->dbname
		));
				
		Zend_Db_Table_Abstract::setDefaultAdapter($db);
		$config = array(
			'name'           => 'sessions',
			'primary'        => 'id',
			'modifiedColumn' => 'modified',
			'dataColumn'     => 'data',
			'lifetimeColumn' => 'lifetime'
		);
		
		Zend_Session::setSaveHandler(new Zend_Session_SaveHandler_DbTable($config));
		Zend_Session::start();
	}
}

