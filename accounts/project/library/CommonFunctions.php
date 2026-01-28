<?php
include __DIR__."/plugin_functions.php";
include __DIR__."/PageAccessControlCheck.php";
require_once __DIR__ . '/num2words.php';
require_once __DIR__ . '/mpdf/vendor/autoload.php';
//include __DIR__."/mailSender.php";
trait CommonFunctions
{

    use plugin_functions;
    use PageAccessControlCheck;

    public $hash_hmac_password = 'sdjhsdjhdjjg&^%&^%&^5skaafdfjdnfdskj';
    // **********************************************DATABASE CONNECTION***********************************************

	public function activityLog($sp_activity_id="",$activityData=array())
	{
		$security_id=$this->getCurrentUserSecurityID();
		$activity_ip=$this->getIpAddress();
		$sql_activity = "select security.sp_users_activity(:sp_security_id,:sp_activity_id ,:sp_activity_ip,:sp_activity_data);";
		$res = $this->prepare($sql_activity, array(
			":sp_security_id" => $security_id,
			":sp_activity_id" => $sp_activity_id,
			":sp_activity_ip" => $activity_ip,
			":sp_activity_data"=>json_encode($activityData,JSON_FORCE_OBJECT)
		),4);
		if($this->prepareStatus($res)==true)
		{
			return true;
		}
		else{
			return false;
		}
	}

    public function getLanguage2DCode($language_id = "")
    {
        return $this->prepare("SELECT lang_id, lang_name_en, lang_name_lc, lang_code_2d, lang_code_3d, ins_user_name, ins_ip_address, ins_upd_date, upd_user_name, upd_ip_address, upd_upd_date, del_user_name, del_ip_address, del_upd_date, del_flag FROM master.m_langauage where lang_id=:language_id;", array(
            ":language_id" => $language_id
        ), 4);
    }

    public function getDepartmentName($dept_id = "")
    {
        return $this->prepare("SELECT dept_id,dept_name,dept_desc FROM security.m_department where dept_id=:dept_id and del_flag is null", array(
            ":dept_id" => $dept_id
        ), 4);
    }
	
    public function arrayToSignedURL_onlyfive($url = "", $data = array(), $password = null)
    {
        if ($password == null)
            $password = $this->hash_hmac_password;
    
        $sign = base64_encode(hash_hmac('sha256', http_build_query($data,'','~'), $password));      
        $sign = substr($sign, 0, 5);        
        $sign = base64_encode($sign); // Encode the sign as base64
        return $url . "?" . http_build_query(array_merge($data, array("sign" => $sign)));
    }
<<<<<<< HEAD:accounts/project/library/CommonFunctions.php
    public function getUserLanguage()
    {
        if (! isset($_SESSION))
           session_start();

        return isset($_SESSION['USER_DETAILS']['USER_PROFILE']['language_id']) ? $_SESSION['USER_DETAILS']['USER_PROFILE']['language_id'] : '2';
    }

	public function getFinYear()
    {
        if (! isset($_SESSION))
            session_start();

        if (isset($_SESSION["financial_year"])) {
            return $_SESSION["financial_year"];
        } else {
            return false;
        }
    }
=======
	
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/library/CommonFunctions.php
    public function verifyQueryString_onlyfive($queryString = array(), $password = null)
    {
  
        if ($password == null)
            $password = $this->hash_hmac_password;
    
        if (count($queryString) == 0) {
            $queryString = $_GET;
        }    
        if (count($queryString) != 0) {
            $sign = $queryString['sign'];         
            unset($queryString['sign']);
            $sign_created = base64_encode(hash_hmac('sha256', http_build_query($queryString, '', '~'), $password));
            $sign_created = substr($sign_created, 0, 5);
            $sign_decoded = base64_decode($sign);
            $sign_decoded = substr($sign_decoded, 0, 5);          
            return $sign_created === $sign_decoded;
        } else {
            return true;
        }
    }
    public function issetFinYear()
    {
        if (! isset($_SESSION))
            session_start();

        return isset($_SESSION['financial_year']) ? $_SESSION['financial_year'] : NULL;
    }

    public function getOfficelevelName($office_level_id = "")
    {
        return $this->prepare("SELECT office_level_id,office_level_name FROM security.m_office_level where office_level_id=:office_level_id and del_flag is null", array(
            ":office_level_id" => $office_level_id
        ), 4);
    }

    public function getStateName($state_code = "")
    {
        return $this->prepare("SELECT state_code,state_name_en FROM master.m_state where state_code=:state_code", array(
            ":state_code" => $state_code
        ), 4);
    }

    public function getLGDTPCode($dcode = "",$lbcode = "")
    {
        $lgd_tpcode_new_res=$this->prepare("SELECT lgd_tpcode FROM master.m_localbodies where dcode=:dcode and lbcode=:lbcode ", array(
            ":dcode" => $dcode,  ":lbcode" => $lbcode
        ), 4);
		
		return $lgd_tpcode_new_res["lgd_tpcode"];
		
    }

    public function getDistrictName($state_code = "", $dcode = "", $lang_code_2d = 'en')
    {
        return $this->prepare("SELECT dcode,district_name_" . $lang_code_2d . " as district_name FROM master.m_district where state_code=:state_code and dcode=:dcode", array(
            ":state_code" => $state_code,
            ":dcode" => $dcode
        ), 4);
    }
	
	public function getZoneName($state_code = "", $zonecode = "", $lang_code_2d = 'en')
    {
        return $this->prepare("SELECT zone_id as zonecode,zone_name  FROM master.m_zone_name where  zone_id=:zonecode", array(
          /*  ":state_code" => $state_code,*/
            ":zonecode" => $zonecode
        ), 4);
    }

    public function getTownPanchayatName($state_code = "", $dcode = "", $lbcode = "", $lang_code_2d = 'en')
    {
        return $this->prepare("SELECT dcode, lbtype, lbcode,CASE WHEN town_type != 'TP' THEN lb_display_" . $lang_code_2d . " ELSE lbody_name_" . $lang_code_2d . " END as lbody_name_" . $lang_code_2d . " FROM master.m_localbodies where state_code=:state_code and  dcode=:dcode and lbcode=:lbcode  and lbtype='TP' order by lbody_name_" . $lang_code_2d . "", array(
            ":state_code" => $state_code,
            ":dcode" => $dcode,
            ":lbcode" => $lbcode
        ), 4);
    }

    public function getStateNameList()
    {
        return $this->prepare("SELECT state_code,state_name_en FROM master.m_state order by state_name_en", array(), 2);
    }

    public function getDistrictNameList($state_code = "33", $lang_code_2d = "en")
    {
        return $this->prepare("SELECT state_code,dcode,district_name_" . $lang_code_2d . " FROM master.m_district where state_code=:state_code order by district_name_" . $lang_code_2d . "", array(
            ":state_code" => $state_code
        ), 2);
    }
	
	 public function getZoneNameList($state_code = "33", $lang_code_2d = "en")
    {
        return $this->prepare("SELECT zone_id as zonecode,zone_name FROM master.m_zone_name where del_flag is null /*state_code=:state_code*/ order by zone_name", array(
            /*":state_code" => $state_code*/
        ), 2);
    }

    public function getLocalbodyNameList($state_code = "33", $dcode = "", $lang_code_2d = "en")
    {
        return $this->prepare("SELECT dcode, lbtype, lbcode, lbody_name_" . $lang_code_2d . " FROM master.m_localbodies where state_code=:state_code and  dcode=:dcode and isactive=:isactive order by lbody_name_" . $lang_code_2d . "", array(
            ":state_code" => $state_code,
            ":dcode" => $dcode,
            ":isactive" => 1
        ), 2);
    }
    public function getWardNameList( $dcode = "",$lbcode = "", $lang_code_2d = "en")
    {
        return $this->prepare("SELECT ward_id, ward_code,COALESCE(ward_name_en, ward_name_ta) AS ward_name   FROM master.m_warddetails where  dcode=:dcode and lbcode=:lbcode and isactive=:isactive ", array(
            
            ":dcode" => $dcode,
            ":lbcode" => $lbcode,
            ":isactive" => 1
        ), 2);
    }
    public function getStreetNameList( $dcode = "",$lbcode = "",$ward="", $lang_code_2d = "en")
    {
        return $this->prepare("SELECT streetid, street_code,COALESCE(street_name_en, street_name_ta) AS street_name   FROM master.m_streetdetails where  dcode=:dcode and lbcode=:lbcode and wardid=:wardid ", array(
            
            ":dcode" => $dcode,
            ":lbcode" => $lbcode,
            ":wardid" => $ward
        ), 2);
    }

    public function getTownPanchayatNameList($state_code = "", $dcode = "", $lbcode = "", $lang_code_2d = 'en')
    {
        return $this->prepare("SELECT 33 as state_code,dcode, lbtype, lbcode as tpcode, lbody_name_" . $lang_code_2d . " FROM master.m_localbodies where state_code=:state_code and  dcode=:dcode and lbtype='TP' order by lbody_name_" . $lang_code_2d . "", array(
            ":state_code" => $state_code,
            ":dcode" => $dcode
        ), 2);
    }

    public function getMonth($monthCode = "", $lang_code_2d = 'en')
    {
        $month_res= $this->prepare("SELECT month_id, month_name    FROM master.m_month where month_id=:month_id;", array(":month_id"=>$monthCode
        ), 4);

        return $month_res['month_name'];
    }

    public function pageRoleAccessCheck($AllowedRoles = array(), $check_and_return = false)
    {
        $user_session_roles = array_column($this->getCurrentUserRoles(), "role_code");

        if (count(array_intersect($AllowedRoles, $user_session_roles)) >= 0) {
            return true;
        } else if ($check_and_return == true) {
            return false;
        } else {
            header("Location:" . $this->siteData()->website_logout);
            exit();
        }
    }

    public function getStoragePath()
    {
        return $this->siteData()->data_storage_path;
    }
	
    public function getRole()
    {
        return $this->prepare("select * from security.m_role", array(), 2);
    }

    public function getIpAddress()
    {
        return $_SERVER["REMOTE_ADDR"];
    }

    public function getCurrentDate()
    {
        return date("Y-m-d H:i:s");
    }

    public function getCurrentUser()
    {
        if (! isset($_SESSION))
            session_start();
        return isset($_SESSION["USER_DETAILS"]["USER_PROFILE"]["user_name"]) ? $_SESSION["USER_DETAILS"]["USER_PROFILE"]["user_name"] : "";
    }

    public function issetCurrentUser()
    {
        if (! isset($_SESSION))
            session_start();
        return isset($_SESSION["USER_DETAILS"]["USER_PROFILE"]["user_name"]) ? true : false;
    }

    public function getCurrentUserProfileID()
    {
        if (! isset($_SESSION))
            session_start();
        return isset($_SESSION['USER_DETAILS']['USER_PROFILE']['user_profile_id']) ? $_SESSION['USER_DETAILS']['USER_PROFILE']['user_profile_id'] : NULL;
    }

    public function issetCurrentUserProfileID()
    {
        if (! isset($_SESSION))
            session_start();
        return isset($_SESSION['USER_DETAILS']['USER_PROFILE']['user_profile_id']) ? true : false;
    }

    public function getCurrentUserSecurityID()
    {
        if (! isset($_SESSION))
            session_start();
        return isset($_SESSION['USER_DETAILS']['USER_PROFILE']['security_id']) ? $_SESSION['USER_DETAILS']['USER_PROFILE']['security_id'] : NULL;
        ;
    }

    public function isLoggedIn()
    {
        if (! isset($_SESSION))
            session_start();
        return isset($_SESSION['USER_DETAILS']['USER_PROFILE']['security_id']) ? true : false;
    }

    public function issetCurrentUserSecurityID()
    {
        if (! isset($_SESSION))
            session_start();
        return isset($_SESSION['USER_DETAILS']['USER_PROFILE']['security_id']) ? true : false;
    }

    public function getCurrentRole()
    {
        if (! isset($_SESSION))
            session_start();
        return isset($_SESSION["USER_DETAILS"]["USER_ROLE"][0]['role_code']) ? $_SESSION["USER_DETAILS"]["USER_ROLE"][0]['role_code'] : NULL;
    }

    public function issetCurrentRole()
    {
        if (! isset($_SESSION))
            session_start();
        return isset($_SESSION["USER_DETAILS"]["USER_ROLE"][0]['role_code']) ? true : false;
    }

    public function getCurrentUserRoles()
    {
        if (! isset($_SESSION))
            session_start();

        return isset($_SESSION["USER_DETAILS"]["USER_ROLE"]) ? $_SESSION["USER_DETAILS"]["USER_ROLE"] : NULL;
    }

    public function issetCurrentUserRoles()
    {
        if (! isset($_SESSION))
            session_start();

        return isset($_SESSION["USER_DETAILS"]["USER_ROLE"]) ? true : false;
    }

    public function getCurrentStateCode()
    {
        if (! isset($_SESSION))
            session_start();

        return isset($_SESSION['USER_DETAILS']['USER_PROFILE']['state_code']) ? $_SESSION['USER_DETAILS']['USER_PROFILE']['state_code'] : NULL;
    }

    public function issetCurrentStateCode()
    {
        if (! isset($_SESSION))
            session_start();

        return isset($_SESSION['USER_DETAILS']['USER_PROFILE']['state_code']) ? true : false;
    }

    public function getCurrentDistrictCode()
    {
        if (! isset($_SESSION))
            session_start();

        return isset($_SESSION['USER_DETAILS']['USER_PROFILE']['dcode']) ? $_SESSION['USER_DETAILS']['USER_PROFILE']['dcode'] : NULL;
    }
	
	 public function getCurrentZoneCode()
    {
        if (! isset($_SESSION))
            session_start();

        return isset($_SESSION['USER_DETAILS']['USER_PROFILE']['zonecode']) ? $_SESSION['USER_DETAILS']['USER_PROFILE']['zonecode'] : NULL;
    }

    public function issetCurrentDistrictCode()
    {
        if (! isset($_SESSION))
            session_start();

        return isset($_SESSION['USER_DETAILS']['USER_PROFILE']['dcode']) ? true : false;
    }
	
	public function issetCurrentZoneCode()
    {
        if (! isset($_SESSION))
            session_start();

        return isset($_SESSION['USER_DETAILS']['USER_PROFILE']['zonecode']) ? true : false;
    }

    public function getCurrentLocalBodyCode()
    {
        if (! isset($_SESSION))
            session_start();

        return isset($_SESSION['USER_DETAILS']['USER_PROFILE']['lbcode']) ? $_SESSION['USER_DETAILS']['USER_PROFILE']['lbcode'] : NULL;
    }

    public function issetCurrentLocalBodyCode()
    {
        if (! isset($_SESSION))
            session_start();

        return isset($_SESSION['USER_DETAILS']['USER_PROFILE']['lbcode']) ? true : false;
    }
    
    /**
     * getCurrentUserLanguage2D
     *
     * @return string  return user 2D language code from Session 
     */
    public function getCurrentUserLanguage2D()
    {
        if (! isset($_SESSION))
            session_start();

        return isset($_SESSION['USER_DETAILS']['language_name']) ? $_SESSION['USER_DETAILS']['language_name'] : 'en';
    }



    public function issetCurrentUserLanguage2D()
    {
        if (! isset($_SESSION))
            session_start();

        return isset($_SESSION['USER_DETAILS']['language_name']) ? true : false;
    }

    public function getCurrentUserTemplate()
    {
        if (! isset($_SESSION))
            session_start();

        if (isset($_SESSION["USER_DETAILS"]["USER_PROFILE"]["user_template"])) {
            return $_SESSION["USER_DETAILS"]["USER_PROFILE"]["user_template"];
        } else {
            return false;
        }
    }

    public function pgDateConvert($date_value = "", $from_format = "", $to_format = "")
    {
        if ($from_format == "DD-MM-YYYY" and $to_format == "YYYY-MM-DD") {
            return implode('-', array_reverse(explode('-', $date_value)));
        }
    }

    public function phpHeaders($header_type = "")
    {
        if (strtolower($header_type) == "json") {
            header('Content-Type: application/json');
            return 1;
        }
        return 0;
    }

    public function array_to_json($array = "")
    {
        return json_encode($array);
    }

    public function json_to_array($json = "")
    {
        return json_decode($json);
    }

    public function getNewCaptcha($captchaSessionName = "")
    {
        ob_start();
        if(!isset($_SESSION))
		{
			session_start();
		}
        if (isset($_SESSION[$captchaSessionName])) {
            unset($_SESSION[$captchaSessionName]); // destroy the session if already there
        }
		
        // ////Part 1 Random string generation ////////
        $string1 = "abcdefghijklmnopqrstuvwxyz";
        $string = $string1;
        $string = str_shuffle($string);
        $random_text = substr($string, 0, 6); // change the number to change number of chars
        // ///End of Part 1 ///////////

        $_SESSION[$captchaSessionName] = $random_text; // Assign the random text to session variable

        // /// Create the image ////////
        $im = @ImageCreate(120, 20) or die("Cannot Initialize new GD image stream");
        ImageColorAllocate($im, 255, 255, 255); // Assign background color
        $text_color = ImageColorAllocate($im, 0, 0, 0); // text color is given
        ImageString($im, 5, 5, 3, $_SESSION[$captchaSessionName], $text_color); // Random string from session added

        ImagePng($im); // image displayed

        $image = ob_get_contents();
        ob_clean();
        imagedestroy($im); // Memory allocation for the image is removed.
        return 'data:image/png;base64,' . base64_encode($image);
    }

    public function randomPrefix($length)
    {
        $random = "";
        srand((double) microtime() * 1000000);
        $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
        $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
        $data .= "0FGH45OP89";
        for ($i = 0; $i < $length; $i ++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }
        return $random;
    }

    public function token($tokenName = "")
    {
        if (! isset($_SESSION)) {
            session_start();
        }
        $pagetoken = $this->randomPrefix(20);
        $_SESSION[$tokenName] = $pagetoken;
        return $pagetoken;
    }

    public function validateToken($tokenName = "", $tokenValue = "")
    {
        if (! isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION[$tokenName]) && $_SESSION[$tokenName] == $tokenValue) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Decrypt data from a CryptoJS json encoding string
     *
     * @param mixed $passphrase
     * @param mixed $jsonString
     * @return mixed
     */
    /*
     * if (function_exists('openssl_decrypt')) {
     * echo "functions are available.<br />\n";
     * } else {
     * echo "functions are not available.<br />\n";
     * }
     */
    public function cryptoJsAesDecrypt($passphrase, $jsonString)
    {
        $jsondata = json_decode($jsonString, true);
        try {
            $salt = hex2bin($jsondata["s"]);
            $iv = hex2bin($jsondata["iv"]);
        } catch (Exception $e) {
            return null;
        }
        $ct = base64_decode($jsondata["ct"]);
        $concatedPassphrase = $passphrase . $salt;
        $md5 = array();
        $md5[0] = md5($concatedPassphrase, true);
        $result = $md5[0];
        for ($i = 1; $i < 3; $i ++) {
            $md5[$i] = md5($md5[$i - 1] . $concatedPassphrase, true);
            $result .= $md5[$i];
        }
        $key = substr($result, 0, 32);
        return $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
    }

    /**
     * Encrypt value to a cryptojs compatiable json encoding string
     *
     * @param mixed $passphrase
     * @param mixed $value
     * @return string
     */
    public function cryptoJsAesEncrypt($passphrase, $value)
    {
        $salt = openssl_random_pseudo_bytes(8);
        $salted = '';
        $dx = '';
        while (strlen($salted) < 48) {
            $dx = md5($dx . $passphrase . $salt, true);
            $salted .= $dx;
        }
        $key = substr($salted, 0, 32);
        $iv = substr($salted, 32, 16);
        $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
        $data = array(
            "ct" => base64_encode($encrypted_data),
            "iv" => bin2hex($iv),
            "s" => bin2hex($salt)
        );
        return json_encode($data);
    }

    public function sha512($string = "")
    {
        return hash('sha512', $string);
    }

    public function sha1($string = "")
    {
        return hash('sha1', $string);
    }

    public function getLastLoginActivity($security_id, $activity_id)
    {
		$last_activity=array();		
		if (! isset($_SESSION))
            session_start();
		if(isset($_SESSION['USER_DETAILS']['last_login_activity']))	
		{
			$last_activity['last_login_activity_date']=$_SESSION['USER_DETAILS']['last_login_activity']['last_login_activity_date'];
			$last_activity['last_login_activity_ip']=$_SESSION['USER_DETAILS']['last_login_activity']['last_login_activity_ip'];
		} else {
			$last_activity_arr= $this->prepare("SELECT to_char(activity_date,'dd-mm-yyyy HH12:MI AM') as activity_date_disp,activity_ip FROM security.t_users_login_history where security_id=:security_id and activity_id=:activity_id order by activity_date desc offset 1 limit 1", array(
            ":security_id" => $security_id,
            ":activity_id" => $activity_id
        ), 4);      
		if(isset($last_activity_arr['activity_date_disp']))
		{
			$_SESSION['USER_DETAILS']['last_login_activity']= array('last_login_activity_date'=>$last_activity_arr['activity_date_disp'],'last_login_activity_ip'=>$last_activity_arr['activity_ip']);
			}
			else {
				$_SESSION['USER_DETAILS']['last_login_activity']= array('last_login_activity_date'=>null,'last_login_activity_ip'=>null);
			}
			$last_activity['last_login_activity_date']=$_SESSION['USER_DETAILS']['last_login_activity']['last_login_activity_date'];
			$last_activity['last_login_activity_ip']=$_SESSION['USER_DETAILS']['last_login_activity']['last_login_activity_ip'];
		}
		return (object)$last_activity;
		
    }
	public function getschemelist($fin_year, $scheme_group)
    {
		 return $this->prepare("select Distinct scheme_seq_id, scheme_name_en from (select scheme_group_id, scheme_id from master.m_finyear_scheme_link where fin_year=:fin_year and isactive=:isactive and del_flag is null and fin_year=:fin_year)a left join (select scheme_seq_id, scheme_name_en, scheme_group_code from master.m_scheme where del_flag is null and scheme_group_code=:scheme_group)b on a.scheme_id=b.scheme_seq_id and  a.scheme_group_id=b.scheme_group_code where scheme_seq_id is not null;", array(
            ":scheme_group" => $scheme_group,
			":isactive" => 1,
			":fin_year" => $fin_year
        ), 2);		
    }
	public function get_work_group_list($scheme_group, $scheme)
    {
		 return $this->prepare("select Distinct a.work_group_id, wrkgrpname_en from (select work_group_id, work_id from master.m_scheme_worktype_link where scheme_group_id=:scheme_group_id and scheme_seq_id=:scheme_id and del_flag is null)a left join (select work_group_id, work_id from master.m_work_type where del_flag is null)b on a.work_id=b.work_id and a.work_group_id=b.work_group_id left join (select wrkgrp_id, wrkgrpname_en from master.m_workgroup where del_flag is null)c on b.work_group_id=c.wrkgrp_id ;;", array(
            ":scheme_group_id" => $scheme_group,
			":scheme_id" => $scheme
        ), 2);		
    }
	public function get_scheme_group_list($fin_year)
    {
		 return $this->prepare("select Distinct a.scheme_group_id, scheme_group_name_en from (select scheme_group_id, scheme_id from master.m_finyear_scheme_link where fin_year=:fin_year and isactive=:isactive and del_flag is null)a left join (select scheme_group_id, scheme_group_name_en from master.m_scheme_group where del_flag is null and isactive=:isactive)b on a.scheme_group_id=b.scheme_group_id;", array(
            ":fin_year" => $fin_year,
			":isactive" => 1
        ), 2);		
    }
	public function get_scheme_group_name($scheme_group)
    {
		$scheme_group_name =  $this->prepare("select scheme_group_name_en from master.m_scheme_group where del_flag is null and isactive=:isactive and scheme_group_id=:scheme_group_id;", array(
            ":scheme_group_id" => $scheme_group,
			":isactive" => 1
        ), 4);
		return $scheme_group_name['scheme_group_name_en'];
    }
	public function get_scheme_name($scheme)
    {
		$scheme_name = $this->prepare("select scheme_name_en from master.m_scheme where del_flag is null and scheme_seq_id=:scheme_seq_id ;", array(
            ":scheme_seq_id" => $scheme
        ), 4);
		return $scheme_name['scheme_name_en'];		
    }
	public function get_work_group_name($work_group)
    {
		$work_group_name = $this->prepare("select wrkgrp_id, wrkgrpname_en from master.m_workgroup where del_flag is null and wrkgrp_id=:wrkgrp_id ;", array(
            ":wrkgrp_id" => $work_group
        ), 4);	
		return $work_group_name['wrkgrpname_en'];		
    }
<<<<<<< HEAD:accounts/project/library/CommonFunctions.php
    public function Select_Account_Head_Code($account_type_id = '', $voucher_id = '')
    {
        if($account_type_id == 0){
            $sel_account_head_qry="SELECT li.account_headid as account_head_id, acc_head.old_code, acc_head.new_code, acc_head.account_head_name_en FROM accounts_master.m_accounthead_link as li
LEFT JOIN (SELECT account_head_id, old_account_head_code AS old_code, new_account_head_code AS new_code, account_head_name_en FROM accounts_master.m_account_head WHERE isactive = :isactive ) AS acc_head 
ON acc_head.account_head_id = li.account_headid WHERE li.voucher_id=:voucher_id  AND li.isactive=:isactive AND li.del_flag is null
AND acc_head.account_head_id IS NOT NULL order by old_code asc, new_code asc;";
            return $this->prepare($sel_account_head_qry, array(":isactive"=>1, ":voucher_id"=>$voucher_id ),2);            
        }else{
            $sel_account_head_qry="SELECT li.account_headid as account_head_id, acc_head.old_code, acc_head.new_code, acc_head.account_head_name_en FROM accounts_master.m_accounthead_link as li
LEFT JOIN (SELECT account_head_id, old_account_head_code AS old_code, new_account_head_code AS new_code, account_head_name_en from  accounts_master.m_account_head WHERE  isactive = :isactive ) AS acc_head 
ON acc_head.account_head_id = li.account_headid WHERE li.voucher_id=:voucher_id  AND li.isactive=1 AND li.del_flag is null AND li.account_type_id=:account_type_id
AND acc_head.account_head_id IS NOT NULL order by old_code asc, new_code asc;";
            return  $this->prepare($sel_account_head_qry, array(":isactive"=>1, ":account_type_id"=>$account_type_id, ":voucher_id"=>$voucher_id ),2);
        }
    }
    public function convertToIndianCurrency($number = '', $language_name = '')
    {
        if ($language_name == 'ta') {
        $numtowords = new numtowords($number, 1);
        } else {
        $numtowords = new numtowords($number);
        }
        return $numtowords->flushData();
    }
    
  
=======
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/library/CommonFunctions.php
}

