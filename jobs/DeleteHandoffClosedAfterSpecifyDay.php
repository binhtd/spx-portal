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
date_default_timezone_set($config->default_time_zone);
$transport = new Zend_Mail_Transport_Smtp($configXml->mail->server->host, array('auth' => 'login',
					'username' => $configXml->mail->server->username,
					'password' => $configXml->mail->server->password));		
Zend_Mail::setDefaultTransport($transport);
$ho = new Application_Model_Mapper_HO();
$bodyHtml = "Total space before delete:" . getFreeSpace() . "<br/>";
$bodyHtml .= "Total record was deleted:" . $ho->deleteHandoffClosedAfterSpecifyDay(1). "<br/>";
$bodyHtml .= "Total space after delete:" . getFreeSpace() . "<br/>";
$mail = new Zend_Mail('UTF-8');				
$mail->setFrom($configXml->mail->server->sender);		
$mail->addTo($configXml->mail->admin->alias);		
$mail->setSubject("BENTO PORTAL delete handoff was closed after specify number of day");
$mail->setBodyHtml($bodyHtml);

try { 	
	$mail->send();
}catch(Exception $e) {
   $logger = new Zend_Log();
   $writer = new Zend_Log_Writer_Stream(realpath(dirname(__FILE__)) . "/DeleteHandoffClosedAfterSpecifyDay.log");
   $logger->addWriter($writer);
	
   $msg = $e->getMessage();
   $str = $e->__toString();
   $trace =  preg_replace('/(\d\d?\.)/', '\1\r', $str);
   $logger->log("message:" . $msg . " | trace:" . $trace, 6);
}

function getFreeSpace(){
	$bytes = disk_free_space("c:"); 
	$s = array('B', 'Kb', 'MB', 'GB', 'TB', 'PB');
	$e = floor(log($bytes)/log(1024));
	$freeSpace = sprintf('%.2f '.$s[$e], ($bytes/pow(1024, floor($e)))) ;
	if (($bytes/pow(1024, floor($e))) < 10){
		$freeSpace = "<span style='color:red'>" . $freeSpace . "</span>";
	}
	
	return $freeSpace;
}