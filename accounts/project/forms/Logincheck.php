<?php
require_once __DIR__ . '/../config/configPublic.php';
require_once __DIR__ . '/../internalservices/ServiceLogin.php';
require_once __DIR__ . '/../internalservices/UserProfileDetails.php';
//require_once __DIR__ . '/admin/ChangePassword.php';

class LoginCheck extends ConfigClass
{
    //use ChangePassword;
	public $ServiceLogin;
	public $UserProfileDetails;
	public $siteData;
    public function __construct()
    {
        $this->ServiceLogin = new ServiceLogin();
        $this->UserProfileDetails = new UserProfileDetails();
        $this->siteData = $this->siteData();
    }
    public function regenerateSession()
    {
        session_regenerate_id();
    }
    public function CheckLogin($data_array_post = array(), $data_array_get = array())
    { 
        $redirect = "Location:";
        $login_captcha = $data_array_post['captchaval'];
        if (!isset($_SESSION['login_captcha'])) {
            header($redirect . $this->siteData()->website_url . "?loginState=" . base64_encode("fail1"));
        }
        if (!isset($data_array_post['encpwd'])) {
            header($redirect . $this->siteData()->website_url . "?loginState=" . base64_encode("fail2"));
        }
        if ($_SESSION['login_captcha'] == $login_captcha) {
           $user_name = base64_decode($data_array_post["user_name"]);
		   $password = $data_array_post["encpwd"];			
			$check_user_type="SELECT user_profile_id from security.t_accounts_users where user_name=:user_first_name";
			$sel_check_user_type_res = $this->prepare($check_user_type, array(":user_first_name" =>base64_decode($_POST["user_name"]) ), 4);
			if(isset($sel_check_user_type_res['user_profile_id']) && $sel_check_user_type_res['user_profile_id'] != ''){
				$login_data1 = $this->UserProfileDetails->getUserRoleDetails($sel_check_user_type_res['user_profile_id']);
				$login_data = $this->ServiceLogin->checkLogin( $user_name, $password);
				
			}else{
				header($redirect . $this->siteData()->website_url . "?loginState=" . base64_encode("fail3"));
			}
        $login_status = $login_data['LOGINSTATUS'];
		if ($login_status == "SUCCESS") {
			$this->regenerateSession();
			if (!$this->siteData()->allow_multiple_login) {
				$session_id = session_id();
				$this->ServiceLogin->setLoginStatus($user_name, $session_id, "Y");
			}
			$user_details = array();
			$PROFILE_DETAILS = $this->UserProfileDetails->getUserDetails($login_data['PROFILE_ID']);
			
			//$OFFICE_DETAILS = $this->UserProfileDetails->getUserOfficeDetails($PROFILE_DETAILS['office_id']);
			$USER_ROLE = $this->UserProfileDetails->getUserRoleDetails($login_data['PROFILE_ID']);
			$user_details['USER_PROFILE'] = array_merge($PROFILE_DETAILS, array(
				"user_name" => $user_name,
				"security_id" => $login_data['SECURITY_ID'],
			));
			$user_details['USER_ROLE'] = $USER_ROLE;
			$_SESSION['LAST_ACTIVITY'] = time(); 
			$_SESSION['USER_DETAILS'] = $user_details;
			$_SESSION['USER_DETAILS']['language_id'] = isset($PROFILE_DETAILS['language_id']) ? $PROFILE_DETAILS['language_id'] : 2;		
			$this->getLastLoginActivity($this->getCurrentUserSecurityID(), 1);
			$this->activityLog(1, array("session_id" => session_id()));
    		if(isset($_SESSION['USER_DETAILS']['USER_PROFILE']['OFFICE_DETAILS']['tpcode'])&&$_SESSION['USER_DETAILS']['USER_PROFILE']['OFFICE_DETAILS']['tpcode']!=''){
				$tpcode = $_SESSION['USER_DETAILS']['USER_PROFILE']['OFFICE_DETAILS']['tpcode'];
			    $dcode = $_SESSION['USER_DETAILS']['USER_PROFILE']['OFFICE_DETAILS']['dcode'];
				$eo_activity="select count(1) as eo_activitys, dcode,lbcode,update_activate from master.eo_otp_active_update where dcode=:dcode and lbcode=:lbcode and update_activate=:update_activate group by dcode,lbcode,update_activate ";
				$eo_activity_res=$this->prepare($eo_activity,array(":dcode"=>$dcode,":lbcode"=>$tpcode,":update_activate"=>'Y'),4);
				if(isset($eo_activity_res['eo_activitys'])>0 && isset($eo_activity_res['eo_activitys'])!=''){
					if(isset($login_data1[0]['role_code']) && $login_data1[0]['role_code']==28 ){
						$eo_data = $this->ServiceLogin->eo_login();
						if($eo_data['STATUS']=='SUCCESS'){
							$mobilenumber=$eo_data['mobilenumber'];
							$id=$eo_data['id'];
							$iv = '534D5367700114E6';
							$secret='otppublic#$dfguhdgh';
							$sign = hash_hmac('sha256', implode('-',array(($mobilenumber),($iv))), $secret);		
							$_SESSION['otp_id_safe']=$sign;
							$_SESSION['id']=base64_encode($id);
							$_SESSION['serviceid']=base64_encode(8);
							$redirect = "Location:";
							$eoredirect=header($redirect . $this->siteData()->website_url . "project/forms/UserPublicOtpVerifyEo.php");
							exit;		
						}
					}
				}
			}
			if ($login_data["no_of_days_from_update"] < $this->siteData()->FORCE_PASSWORD_CHANGE) {
                    $_SESSION['change_password_required']='N';
                    header($redirect . $this->siteData()->website_url . "project/home.php");
                    exit;
                } else {
                    $_SESSION['change_password_required']='Y';
					header($redirect . $this->siteData()->website_url . "project/forms/admin/ForceChangePassword.php");
					exit;
                }
            } else if ($login_status == "FAIL") {

                header($redirect . $this->siteData()->website_url . "?loginState=" . base64_encode("fail4"));
            } else if ($login_status == "ERROR") {
                header($redirect . $this->siteData()->website_url . "?loginState=" . base64_encode("fail5"));
            }
        }else {
            header($redirect . $this->siteData()->website_url . "?loginState=" . base64_encode("fail6"));
        }
    }
}

$LoginCheck = new LoginCheck();

if (!isset($_POST['cmd'])) {
    $LoginCheck->CheckLogin($_POST, $_GET);
} else {
    $user_profile_id = $LoginCheck->getCurrentUserProfileID();
    $user_security_id = $LoginCheck->getCurrentUserSecurityID();
    $cmd = base64_decode($_POST['cmd']);	
    if ($cmd == 1 && $LoginCheck->isLoggedIn()) {
        $digits = 6;
        $generated_otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $generated_otp_sha = $LoginCheck->sha512($generated_otp);

        $sel_user_mobile = "SELECT mobile_no FROM security.t_accounts_user_profile WHERE user_profile_id=:user_profile_id";
        $sel_user_mobile_res = $LoginCheck->prepare($sel_user_mobile, array(":user_profile_id" => $user_profile_id), 4);
        if ($LoginCheck->prepareStatus($sel_user_mobile_res) != false && $sel_user_mobile_res['mobile_no'] != '') {
            $message = "Your login reset OTP is:$generated_otp. Validity 5 minutes - Directorate of Town Panchayats";
			$send_msg = $LoginCheck->send(3, $sel_user_mobile_res['mobile_no'], $message, 'English');
            if ($send_msg == true) {
                $upd_user_otp = "UPDATE security.t_accounts_users SET change_password_otp=:change_password_otp,change_password_count=(SELECT (coalesce(change_password_count,0)+1) FROM security.t_accounts_users WHERE security_id=:security_id AND user_profile_id=:user_profile_id),change_password_date=NOW() WHERE security_id=:security_id AND user_profile_id=:user_profile_id;";
                $upd_user_otp_res = $LoginCheck->prepare($upd_user_otp, array(":change_password_otp" => $generated_otp_sha, ":security_id" => $user_security_id, ":user_profile_id" => $user_profile_id), 4);
                if ($LoginCheck->prepareStatus($upd_user_otp_res) == false) {
                    $result_array['STATUS'] = 'ERROR';
                    $result_array['FIELD_TYPE'] = 'PWD';
                    $result_array['MESSAGE'] = 'Please try after sometime';
                } else {
                    $sel_otp_count = "SELECT (coalesce(change_password_count,0)+1)as otp_count FROM security.t_accounts_users WHERE security_id=:security_id AND user_profile_id=:user_profile_id;";
                    $sel_otp_count_res = $LoginCheck->prepare($sel_otp_count, array(":security_id" => $user_security_id, ":user_profile_id" => $user_profile_id), 4);
                    $result_array['STATUS'] = 'SUCCESS';
                    $result_array['FIELD_TYPE'] = '';
                    $result_array['MESSAGE'] = 'OTP sent to your Registered Mobile Number';
                    $result_array['resent_btn_flag'] = $sel_otp_count_res['otp_count'] > 10 ? true : false;
                }
            } else {
                $result_array['STATUS'] = 'ERROR';
                $result_array['FIELD_TYPE'] = 'SMS';
                $result_array['MESSAGE'] = 'Please try after sometime';
            }
        } else {
            $result_array['STATUS'] = 'ERROR';
            $result_array['FIELD_TYPE'] = 'MBNO';
            $result_array['MESSAGE'] = 'Please try after sometime';
        }
        echo json_encode($result_array);
    } else if ($cmd == 1 && !$LoginCheck->isLoggedIn()) {
        $ServiceLogin = new ServiceLogin();
        $ServiceLogin->userLogout();
        $result_array['STATUS'] = 'authorised';
        $result_array['FIELD_TYPE'] = 'logout';
        $result_array['MESSAGE'] = 'Authorised User';
        echo json_encode($result_array);
    }
    if ($cmd == 2 && $LoginCheck->isLoggedIn()) {
        $user_otp = base64_decode($_POST['user_otp']);
        $user_otp_sha = $LoginCheck->sha512($user_otp);
        $sel_sha_otp = "SELECT change_password_otp FROM security.t_accounts_users WHERE security_id=:security_id AND user_profile_id=:user_profile_id;";
        $sel_sha_otp_res = $LoginCheck->prepare($sel_sha_otp, array(":security_id" => $user_security_id, ":user_profile_id" => $user_profile_id), 4);
        if ($sel_sha_otp_res['change_password_otp'] == $user_otp_sha) {
            $result_array['STATUS'] = 'SUCCESS';
            $result_array['FIELD_TYPE'] = '';
            $result_array['MESSAGE'] = 'OTP Verified';
            $result_array['DATA'] = $LoginCheck->change_password_otp_verify();
            echo json_encode($result_array);
        } else {
            $result_array['STATUS'] = 'ERROR';
            $result_array['FIELD_TYPE'] = 'error_otp';
            $result_array['MESSAGE'] = 'Invalid OTP';
            echo json_encode($result_array);
        }
    } else if ($cmd == 2 && !$LoginCheck->isLoggedIn()) {
        $ServiceLogin = new ServiceLogin();
        $ServiceLogin->userLogout();
        $result_array['STATUS'] = 'authorised';
        $result_array['FIELD_TYPE'] = 'logout';
        $result_array['MESSAGE'] = 'Authorised User';
        echo json_encode($result_array);
    }
}
