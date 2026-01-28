<?php
require_once __DIR__ . '/../config/configPublic.php';
class ServiceLogin extends ConfigClass
{
    public $db = NULL;

    public $obj = NULL;
	
    function __construct()
    {
	}

    public function checkLogin($user_name = "", $password = "")
    {
		
        if (! isset($_SESSION)) {
            session_start();
        }

        try {

            $response_data = array();

            if ($user_name == "" && $password == "") {
                echo '{"LOGINSTATUS":"FAIL","RESPONSE":"INVALID_REQUEST"}';
                exit();
            }

           $sql = "SELECT *, (now()::date - last_password_modified::date) AS no_of_days_from_update FROM security.t_accounts_users as a where user_name=:user_name";

            $res = $this->prepare($sql, array(
                ":user_name" => $user_name
            ), 2);

            if (count($res) == 1) {

                $response_data['STATUS'] = 'OK';

                foreach ($res as $login) {
                    $login_active = $login['active'];

                    if ($login_active == '0' || $login_active == '') {
                        $response_data['STATUS'] = 'FAIL';
                        $response_data['RESPONSE'] = 'LOGIN_INACTIVE';
                        $response_data['MESSAGE'] = 'LOGIN_INACTIVE';
                    } else if ($login_active == '1') {

                        $sha_pwd = hash('sha512', $_SESSION['salt'] . $login['password']);

                        if (strtolower($sha_pwd) == strtolower($password)) {
                            $response_data['LOGINSTATUS'] = 'SUCCESS';
                            $response_data['RESPONSE'] = 'LOGIN_SUCCESS';
                            $response_data['MESSAGE'] = 'LOGIN SUCCESS';
                            $response_data['SECURITY_ID'] = $login['security_id'];
                            $response_data['PROFILE_ID'] = $login['user_profile_id'];
                            $response_data['no_of_days_from_update'] = $login['no_of_days_from_update'];

                            
                            /*$sql_activity = "select security.sp_users_login_history(:sp_security_id,:sp_activity_id ,:sp_activity_ip);";
                            $res = $this->prepare($sql_activity, array(
                                ":sp_security_id" => $login['security_id'],
                                ":sp_activity_id" => 1,
                                ":sp_activity_ip" => $_SERVER["REMOTE_ADDR"]
                            ));*/
                        } else {
                            $response_data['LOGINSTATUS'] = 'FAIL';
                            $response_data['RESPONSE'] = 'LOGIN_FAILED';
                            $response_data['MESSAGE'] = 'LOGIN FAILED 3';
                        }
                    }
                }
            } else {
                $response_data['LOGINSTATUS'] = 'FAIL';
                $response_data['RESPONSE'] = 'LOGIN_FAILED';
                $response_data['MESSAGE'] = 'LOGIN FAILED 2';
            }

            return $response_data;
        } catch (Exception $e) {
            print_r($e);
            $response_data = array();
            $response_data['LOGINSTATUS'] = 'ERROR';
            $response_data['RESPONSE'] = 'LOGIN_FAILED';
            $response_data['MESSAGE'] = 'LOGIN FAILED 1';

            return $response_data;
        }
    }
    public function eo_login()
    {
       
    
        if (! isset($_SESSION)) {
            session_start();
        }
        $response_data = array();
        $eo_rolecode=$_SESSION["USER_DETAILS"]["USER_ROLE"][0]["role_code"];
            $mobile_number=$_SESSION["USER_DETAILS"]["USER_PROFILE"]["eo_mobile_no_official"];
            $getIpAddress = $this->getIpAddress();
            $user_name = $this->getCurrentUser();     
            $save_query = "select * from master.sp_eo_otp_entry(:mobile_number,:sp_user_name,:getIpAddress)";
            $res = $this->prepare($save_query, array(":mobile_number" => $mobile_number,":sp_user_name" => $user_name,
            ":getIpAddress" => $getIpAddress,
            ), 4);
            if (!isset($res->errorInfo)) {
                $user_otp_entry=json_decode($res['sp_eo_otp_entry'],TRUE); 
                $id = $user_otp_entry['otp_details']['id'];
                $mobilenumber = $user_otp_entry['otp_details']['mobile_number'];
                $sel_otp_query = "SELECT otp  from master.m_eo_otp_registration where id=:id";
                $sel_otp_query_res = $this->prepare($sel_otp_query, array(":id" => $id), 4);
            
                //$eo_sms=  $this->sendSMS(1, $mobilenumber, "Registration Verification OTP is " . $sel_otp_query_res['otp'] . "- Directorate of Town Panchayats" , 'English', "INSTANT"); 
				$eo_sms=  $this->send(1, $mobilenumber, "Registration Verification OTP is " . $sel_otp_query_res['otp'] . "- Directorate of Town Panchayats" , 'English'); 				
				if ($eo_sms == true) {
					$response_data['mobilenumber'] =$mobilenumber;
					$response_data['id'] =$id;
					$response_data['STATUS'] = 'SUCCESS';
				}else{
					$response_data['mobilenumber'] =$mobilenumber;
					$response_data['id'] =$id;
					$response_data['STATUS'] = 'SMS Sending Failed';
				}

                return $response_data;
                exit;

            }else{
                $response_data['STATUS'] = 'FAILED';
                return $response_data;
                exit;

            }


  
    }

    public function dashboardLogin($user_name = "", $password = "")
    {
	
        if (! isset($_SESSION)) {
            session_start();
        }

        try {

            $response_data = array();

            if ($user_name == "" && $password == "") {
                echo '{"LOGINSTATUS":"FAIL","RESPONSE":"INVALID_REQUEST"}';
                exit();
            }

            $sql = "SELECT *,DATEDIFF('day',last_password_modified::date,now()::date) as no_of_days_from_update FROM security.t_user_dashboard as a where user_name=:user_name";

            $res = $this->prepare($sql, array(
                ":user_name" => $user_name
            ), 2);

            if (count($res) == 1) {

                $response_data['STATUS'] = 'OK';

                foreach ($res as $login) {
                    $login_active = $login['active'];

                    if ($login_active == '0' || $login_active == '') {
                        $response_data['STATUS'] = 'FAIL';
                        $response_data['RESPONSE'] = 'LOGIN_INACTIVE';
                        $response_data['MESSAGE'] = 'LOGIN_INACTIVE';
                    } else if ($login_active == '1') {

                        $sha_pwd = hash('sha512', $_SESSION['salt'] . $login['password']);

                        if (strtolower($sha_pwd) == strtolower($password)) {
                            $response_data['LOGINSTATUS'] = 'SUCCESS';
                            $response_data['RESPONSE'] = 'LOGIN_SUCCESS';
                            $response_data['MESSAGE'] = 'LOGIN SUCCESS';
                            $response_data['SECURITY_ID'] = $login['security_id'];
                            $response_data['PROFILE_ID'] = $login['user_profile_id'];
                            $response_data['no_of_days_from_update'] = $login['no_of_days_from_update'];

                            
                            /*$sql_activity = "select security.sp_users_login_history(:sp_security_id,:sp_activity_id ,:sp_activity_ip);";
                            $res = $this->prepare($sql_activity, array(
                                ":sp_security_id" => $login['security_id'],
                                ":sp_activity_id" => 1,
                                ":sp_activity_ip" => $_SERVER["REMOTE_ADDR"]
                            ));*/
                        } else {
                            $response_data['LOGINSTATUS'] = 'FAIL';
                            $response_data['RESPONSE'] = 'LOGIN_FAILED';
                            $response_data['MESSAGE'] = 'LOGIN FAILED 3';
                        }
                    }
                }
            } else {
                $response_data['LOGINSTATUS'] = 'FAIL';
                $response_data['RESPONSE'] = 'LOGIN_FAILED';
                $response_data['MESSAGE'] = 'LOGIN FAILED 2';
            }

            return $response_data;
        } catch (Exception $e) {
            print_r($e);
            $response_data = array();
            $response_data['LOGINSTATUS'] = 'ERROR';
            $response_data['RESPONSE'] = 'LOGIN_FAILED';
            $response_data['MESSAGE'] = 'LOGIN FAILED 1';

            return $response_data;
        }
    }


    public function lastLoginActiveCheck($user_name = "")
    {
        $check_already_logged_in="SELECT currently_logged_in,last_login_session_id FROM security.t_accounts_users WHERE user_name=:user_name ";	
        $check_already_logged_in_res=$this->prepare($check_already_logged_in,array(":user_name"=>$user_name),4);

        if($check_already_logged_in_res['currently_logged_in']=="Y")
        {            
            return true;
        }
        else if($check_already_logged_in_res['currently_logged_in']=="N" || $check_already_logged_in_res['currently_logged_in']=="")
        {            
            return false;
        }
    }


    public function setLoginStatus($user_name = "",$session_id="",$LoginState="")
    {    
        
       
        $check_already_logged_in="SELECT currently_logged_in,last_login_session_id FROM security.t_accounts_users WHERE user_name=:user_name ";	
        $check_already_logged_in_res=$this->prepare($check_already_logged_in,array(":user_name"=>$user_name),4);

        if(count( $check_already_logged_in_res)>0 && $check_already_logged_in_res['currently_logged_in']=="Y" && $LoginState="Y")
        {

            $last_login_session_id=$check_already_logged_in_res['last_login_session_id'];

            $session_id_to_destroy = $last_login_session_id;
            // 1. commit session if it's started.
            if (session_id()) {
                session_commit();
            }
            
            // 2. store current session id
           // session_start();
         
            $current_session_id = session_id();
            $session_values=$_SESSION;         
            session_commit();
            
            // 3. hijack then destroy session specified.
           
            session_id($session_id_to_destroy);
            session_start();
            session_destroy();
            session_commit();
            
            // 4. restore current session id. If don't restore it, your current session will refer to the session you just destroyed!
           
            session_id($current_session_id);
            session_start();
            $_SESSION=$session_values;              

        }


        $check_already_logged_in="UPDATE security.t_accounts_users SET currently_logged_in=:currently_logged_in,last_login_session_id=:last_login_session_id WHERE user_name=:user_name ";	
        $check_already_logged_in_res=$this->prepare($check_already_logged_in,array(":user_name"=>$user_name,":last_login_session_id"=>$session_id,":currently_logged_in"=>$LoginState),4);

        if($this->prepareStatus($check_already_logged_in_res)==true)
        {
              return true;    
        }
        else
        {
            return false;
        }
      
    }


    public function userLogout($user_name = "")
    {
        $session_id=session_id();
        $this->setLoginStatus($user_name,$session_id,"N");
        session_destroy();
        unset($_SESSION);
        return true;
    }
}
?>