<?php

define("WURFL_DIR", dirname(__FILE__) . '/../../../WURFL/');
define("RESOURCES_DIR", dirname(__FILE__) . "/../../resources/");

set_time_limit(300);

require_once WURFL_DIR . 'Application.php';

// Uncomment the follwoing lines to use the xml configuration file
$wurflConfigFile = RESOURCES_DIR . 'wurfl-config.xml';
$wurflConfig = new WURFL_Configuration_XmlConfig($wurflConfigFile);


$wurflManagerFactory = new WURFL_WURFLManagerFactory($wurflConfig);

$wurflManager = $wurflManagerFactory->create(true);
$wurflInfo = $wurflManager->getWURFLInfo();

?>

<h3>WURFL XML INFO</h3>
<ul>
    <li><h4>VERSION: <?php echo $wurflInfo->version; ?> </h4></li>
</ul>
