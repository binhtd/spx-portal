<?php
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap();
$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
$configXml = new Zend_Config_Xml(APPLICATION_PATH . '/configs/smtp.xml', APPLICATION_ENV);
$transport = new Zend_Mail_Transport_Smtp($configXml->mail->server->host, array('auth' => 'login',
					'username' => $configXml->mail->server->username,
					'password' => $configXml->mail->server->password));		
Zend_Mail::setDefaultTransport($transport);
$queueAdapterOptions =  array('driverOptions' => array(
								'host'      => $config->resources->db->params->host,
								'username'  => $config->resources->db->params->username,
								'password'  => $config->resources->db->params->password,
								'dbname'    => $config->resources->db->params->dbname,
								'type'      => $config->resources->db->adapter),
								'name' => "SendingEmail");						
$queue = new Zend_Queue('Db', $queueAdapterOptions);
$messages = $queue->receive(10); 

foreach($messages as $message) {
    $email = unserialize($message->body);
	
	try {
		$email->send();
	}catch(Zend_Mail_Exception $e) {
	   $logger = new Zend_Log();
	   $writer = new Zend_Log_Writer_Stream(realpath(dirname(__FILE__)) . "/LogSendingEmail.log");
       $logger->addWriter($writer);
		
	   $msg = $e->getMessage();
	   $str = $e->__toString();
	   $trace =  preg_replace('/(\d\d?\.)/', '\1\r', $str);
	   $logger->log("message:" . $msg . " | trace:" . $trace, 6);
	}
	
	$queue->deleteMessage($message);
}