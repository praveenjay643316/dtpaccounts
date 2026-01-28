<?php

trait UserLoginServices
{

    public function CheckUserNameExists($user_name = "")
    {
        try {

            $response_data = array();

            if ($user_name == "" && $password == "") {
                echo '{"LOGINSTATUS":"FAIL","RESPONSE":"INVALID_REQUEST"}';
                exit();
            }

            $sql = "SELECT 1 FROM security.t_accounts_users as a where  user_name=:user_name";

            $res = $this->prepare($sql, array(
                ":user_name" => $user_name
            ));
            $response_data = array();
            if (count($res) == 1) {
                $response_data['STATUS'] = 'SUCCESS';
                $response_data['RESPONSE'] = 'SUCCESS';
                $response_data['FLAG'] = 'EXISTS';
                $response_data['MESSAGE'] = 'This user name already exists';
            } else {
                $response_data['STATUS'] = 'SUCCESS';
                $response_data['RESPONSE'] = 'SUCCESS';
                $response_data['FLAG'] = 'NOTEXIST';
                $response_data['MESSAGE'] = 'This User name not exists';
            }

            return $response_data;
        } catch (Exception $e) {
            // print_r($e);
            $response_data['STATUS'] = 'SUCCESS';
            $response_data['RESPONSE'] = 'SUCCESS';
            $response_data['FLAG'] = 'NOTEXIST';
            $response_data['MESSAGE'] = 'This User name not exists';

            return $response_data;
        }
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

            $sql = "SELECT *,DATEDIFF('day',last_password_modified::date,now()::date) as no_of_days_from_update FROM security.t_accounts_users as a where  user_name=:user_name";

            $res = $this->prepare($sql, array(
                ":user_name" => $user_name
            ));

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

                            $sql_activity = "select security.sp_users_login_history(:sp_security_id,:sp_activity_id ,:sp_activity_ip);";
                            $res = $this->prepare($sql_activity, array(
                                ":sp_security_id" => $login['security_id'],
                                ":sp_activity_id" => 1,
                                ":sp_activity_ip" => $_SERVER["REMOTE_ADDR"]
                            ));
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
            // print_r($e);
            $response_data = array();
            $response_data['LOGINSTATUS'] = 'ERROR';
            $response_data['RESPONSE'] = 'LOGIN_FAILED';
            $response_data['MESSAGE'] = 'LOGIN FAILED 1';

            return $response_data;
        }
    }

    public function userLogout($user_name = "")
    {
        session_destroy();
        unset($_SESSION);
        return true;
    }
}
?>