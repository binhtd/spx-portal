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
$activity = new Application_Model_Mapper_Activity();

$bytes = disk_free_space("c:"); 
$s = array('B', 'Kb', 'MB', 'GB', 'TB', 'PB');
$e = floor(log($bytes)/log(1024));
$freeSpace = sprintf('%.2f '.$s[$e], ($bytes/pow(1024, floor($e)))) ;
if (($bytes/pow(1024, floor($e))) < 10){
	$freeSpace = "<span style='color:red'>" . $freeSpace . "</span>";
}

$records = $activity->getDailyReport();
$totalRecord = count($records);
$tableString = "<table border='1' cellspacing='0' width='100%' style='background:#00ACCA'>
				<tr>
					<td align='center'>
						<b>UserName</b>
					</td>
					<td align='center'>
						<b>UserActivity</b>
					</td>
					<td align='center'>
						<b>Timestamp</b>
					</td>
				</tr>";
foreach($records as $record){
	$userName = htmlentities($record->UserName);
	$userActivity = htmlentities ($record->UserActivity);
	$timeStamp = htmlentities($record->UserActivityDateTime);
	$tableString .= "<tr>
						<td align='center'>
							$userName	
						</td>
						<td align='center'>
							$userActivity
						</td>
						<td align='center'>
							$timeStamp	
						</td>
					</tr>";
}
	$tableString .= "<tr>
						<td colspan='3'>
							<b>TOTAL EMAILS SENT:</b> $totalRecord
						</td>
					</tr>
					<tr>
						<td colspan='3'>
							<b>TOTAL FREE SPACE:</b> $freeSpace
						</td>
					</tr>
				</table>
				";

$mail = new Zend_Mail('UTF-8');				
$mail->setFrom($configXml->mail->server->sender);		
$mail->addTo($configXml->mail->admin->alias);		

$mail->setSubject("BENTO PORTAL Daily Email Activity report");
$mail->setBodyHtml($tableString);

try { 	
	$mail->send();
}catch(Zend_Mail_Exception $e) {
   $logger = new Zend_Log();
   $writer = new Zend_Log_Writer_Stream(realpath(dirname(__FILE__)) . "/LogSendingDailyEmail.log");
   $logger->addWriter($writer);
	
   $msg = $e->getMessage();
   $str = $e->__toString();
   $trace =  preg_replace('/(\d\d?\.)/', '\1\r', $str);
   $logger->log("message:" . $msg . " | trace:" . $trace, 6);
}