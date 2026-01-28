<?php

ini_set('max_execution_time', '300');
if (!isset($_SESSION)) {
    session_name('tndtp_sessionid');
    session_start();
}


date_default_timezone_set('Asia/Kolkata');
header(
    "Cache-Control: private, must-revalidate,max-age=0, no-store, no-cache, must-revalidate, post-check=0, pre-check=0"
);
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
// require_once __DIR__ . '/../library/aes/aes.class.php';
// require_once __DIR__ . '/../library/aes/aesctr.class.php';
// require_once __DIR__ . '/../library/sms_sender_quick_sms.php';
// require_once __DIR__ . '/../library/quick_sms.php';


if (!class_exists('ConfigClass')) {

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
		// use sms_sender;
		// use quick_sms;
    }
}


$checkLogin = new ConfigClass();



if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $checkLogin->siteData()->session_expire_idle_time)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

//print_r($_SESSION);die;
if(isset($_SESSION["USER_DETAILS"]["USER_ROLE"][0]["role_code"]) && $_SESSION["USER_DETAILS"]["USER_ROLE"][0]["role_code"] !=''){
	$eo_rolecode=$_SESSION["USER_DETAILS"]["USER_ROLE"][0]["role_code"];
	
}

if (!$checkLogin->isLoggedIn()) {

    $website_url = $checkLogin->siteData()->website_url;
    //$ServiceLogin = new ServiceLogin();   
    //$ServiceLogin->userLogout();
    header('HTTP/1.0 401 Unauthorized');
    include(__DIR__ . "/../templates/header_401.php");
   
    exit();
}
else if(((!isset($_SESSION['change_password_required'])) || $_SESSION['change_password_required']=='Y' ) && !in_array(basename($_SERVER['PHP_SELF']),array("ForceChangePassword_basic.php","logout.php") ) ){

    $redirect = "Location:";
    header($redirect . $checkLogin->siteData()->website_url . "project/forms/admin/ForceChangePassword_basic.php");
    exit;   
}
// else if($eo_rolecode==28 && !isset($_SESSION['id'])){
//     $mobile_number=$_SESSION["USER_DETAILS"]["USER_PROFILE"]["eo_mobile_no_official"];
//     //print_r($_SESSION);die;
//     $getIpAddress = $checkLogin->getIpAddress();
//     $user_name = $checkLogin->getCurrentUser();     
//     $save_query = "select * from master.sp_eo_otp_entry(:mobile_number,:sp_user_name,:getIpAddress)";
//     $res = $checkLogin->prepare($save_query, array(":mobile_number" => $mobile_number,":sp_user_name" => $user_name,
// ":getIpAddress" => $getIpAddress,
//     ), 4);
//     if (!isset($res->errorInfo)) {
//         $user_otp_entry=json_decode($res['sp_eo_otp_entry'],TRUE); 
//         $id = $user_otp_entry['otp_details']['id'];
//         $mobilenumber = $user_otp_entry['otp_details']['mobile_number'];
//         $sel_otp_query = "SELECT otp  from master.m_eo_otp_registration where id=:id";
//         $sel_otp_query_res = $checkLogin->prepare($sel_otp_query, array(":id" => $id), 4);
//         $checkLogin->sendSMS(1, $mobilenumber, "Registration Verification OTP is " . $sel_otp_query_res['otp'] . "- Directorate of Town Panchayats" , 'English', "INSTANT");   

//         $iv = '534D5367700114E6';
//          $secret='otppublic#$dfguhdgh';
//          $sign = hash_hmac('sha256', implode('-',array(($mobilenumber),($iv))), $secret);
//          $_SESSION['otp_id_safe']=$sign;
//          $_SESSION['id']=base64_encode($id);
//          $_SESSION['serviceid']=base64_encode(8);
//          $redirect = "Location:";
//          header($redirect . $checkLogin->siteData()->website_url . "project/forms/Public/UserPublicOtpVerify.php");
//          exit;
//     }
       
// }

// require_once __DIR__ . '/../library/smsSender.php';
// require_once __DIR__ . '/../cron/class_cron_sms_sender.php';
// require_once __DIR__ . '/../internalservices/ServiceLogin.php';
//require_once __DIR__ . '/../library/sms_sender_quick_sms.php';
//require_once __DIR__ . '/../library/quick_sms.php';


/*if(!$checkLogin->issetCurrentUserLanguage2D())
{
	$checkLogin->setUserLanguage(2);
	$checkLogin->setCurrentUserLanguage2D('en');
}*/

$SecurityCheck = new SecurityCheck();
$SecurityCheck->GET_CHECK();
$SecurityCheck->STOP_REQUEST();
$SecurityCheck->REMOVE_CODE();


//$checkLogin->getRequestDetails();

if (!$checkLogin->isAjaxRequest()) {
    $checkLogin->PageAccessControlCheck();
}
