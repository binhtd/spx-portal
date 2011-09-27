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
$config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/smtp.xml', APPLICATION_ENV,
									array('skipExtends'        => true,
										  'allowModifications' => true));

fwrite(STDOUT, "Enter SMTP server address:");
$host = trim(fgets(STDIN));
fwrite(STDOUT, "Enter domain account:");
$username = trim(fgets(STDIN));
fwrite(STDOUT, "Enter domain account password:");
$password = trim(fgets(STDIN));

$config->mail->server->host = $host;
$config->mail->server->username = $username;
$config->mail->server->password = $password;	

$writer = new Zend_Config_Writer_Xml(array('config' => $config, 
                                           'filename' => APPLICATION_PATH . '/configs/smtp.xml'));
$writer->write();


