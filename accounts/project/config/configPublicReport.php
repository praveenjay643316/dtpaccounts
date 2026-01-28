<?php

if (! isset($_SESSION))
{
    session_name('tndtp_sessionid');
    session_start();
}

date_default_timezone_set('Asia/Kolkata');
header('Cache-Control: no-cache');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
ini_set('memory_limit', '2048M');
require_once __DIR__ . '/DBReport.php';
require_once __DIR__ . '/siteInfo.php';
require_once __DIR__ . '/../internalservices/SecurityCheck.php';
require_once __DIR__ . '/../library/ErrorHandler.php';
require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../library/PageLables.php';
require_once __DIR__ . '/../library/CommonFunctions.php';
require_once __DIR__ . '/../library/ServerValidation.php';
require_once __DIR__ . '/../templates/Templates.php';
require_once __DIR__ . '/../library/aes/aes.class.php';
require_once __DIR__ . '/../library/aes/aesctr.class.php';


/*if ((isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))) {
    if (strtolower(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST)) != strtolower($_SERVER['HTTP_HOST'])) {
        header('HTTP/1.0 403 Forbidden');
        include(__DIR__."/../templates/header_401.php");
        exit;
    }
}*/

if(!isset($_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code']))
$_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code']=32;

class preSet
{
    use DBReport {
        DBReport::dbData insteadof siteInfo;
    }
    use siteInfo;
    use CommonFunctions;
}
$preSet = new preSet();

//$preSet->setUserLanguage(2);
//$preSet->setCurrentUserLanguage2D('en');


if (! class_exists('ConfigClass')) {


    $SecurityCheck = new SecurityCheck();
	$SecurityCheck->GET_CHECK();
	$SecurityCheck->STOP_REQUEST();
	$SecurityCheck->REMOVE_CODE();
	
	require_once __DIR__ . '/../library/smsSender.php';
	require_once __DIR__ . '/../cron/class_cron_sms_sender.php';
	
	require_once __DIR__ . '/../library/sms_sender_quick_sms.php';
	require_once __DIR__ . '/../library/quick_sms.php';

    class ConfigClass
    {
        public $db;
        use DBReport {
            DBReport::dbData insteadof siteInfo;
        }
        use siteInfo;
        use ConnectionClass;
        use CommonFunctions;
        use ServerValidation;
        use PageLables;
        use ClassTemplate;
     	use sms_sender;
		use quick_sms;
    }
}
