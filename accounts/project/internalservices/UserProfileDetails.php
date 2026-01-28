<?php
//require_once __DIR__ . '/../config/config.php';

class UserProfileDetails  extends ConfigClass
{

    public $db = NULL;

    public $obj = NULL;

    function __construct()
    {
        if (! isset($this->db)) {
            
        }
    }

    public function getUserDetails($PROFILE_ID = "")
    {
        if (! isset($_SESSION)) {
            session_start();
        }

        if (! is_numeric($PROFILE_ID)) {
            exit();
        }

        try {
            
            $sql = "select * from (
<<<<<<< HEAD:accounts/project/internalservices/UserProfileDetails.php
                (SELECT user_profile_id, user_first_name, user_last_name, gender, mobile_no, email_address, role_id, office_id,user_setting->>'user_template' as user_template,user_setting->>'menu_type' as menu_type,user_setting->>'language_id' as language_id  FROM security.t_accounts_user_profile where user_profile_id=:PROFILE_ID) as a 
=======
                (SELECT user_profile_id, user_first_name, user_last_name, gender, mobile_no, email_address, role_id, office_id,user_setting->>'user_template' as user_template,user_setting->>'menu_type' as menu_type,user_setting->>'language_id' as language_id  FROM security.t_user_profile where user_profile_id=:PROFILE_ID) as a 
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/internalservices/UserProfileDetails.php
                left join
                (select profile_id,eo_mobile_no_official from master.m_eo_details where del_flag is null and isactive=1)as b
                on a.user_profile_id=b.profile_id
				left join
<<<<<<< HEAD:accounts/project/internalservices/UserProfileDetails.php
				(select user_profile_id as profile_id, dcode, lbcode from security.t_accounts_users where del_flag is null)c on a.user_profile_id=c.profile_id
=======
				(select user_profile_id as profile_id, dcode, lbcode from security.t_users where del_flag is null)c on a.user_profile_id=c.profile_id
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/internalservices/UserProfileDetails.php
                )";
            return $this->prepare($sql,array(":PROFILE_ID"=>$PROFILE_ID),4);
        } catch (Exception $e) {
            $response_data = array();
            $response_data['STATUS'] = 'ERROR';
            return $response_data;
        }
    }

    public function getUserRoleDetails($PROFILE_ID = "")
    {
        if (! isset($_SESSION)) {
            session_start();
        }

        if (! is_numeric($PROFILE_ID)) {
            exit();
        }

        try {

<<<<<<< HEAD:accounts/project/internalservices/UserProfileDetails.php
            $sql = "SELECT  role_id as role_code FROM security.t_accounts_user_profile where user_profile_id=:PROFILE_ID";
=======
            $sql = "SELECT  role_id as role_code FROM security.t_user_profile where user_profile_id=:PROFILE_ID";
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/internalservices/UserProfileDetails.php
            return $this->prepare($sql,array(":PROFILE_ID"=>$PROFILE_ID),2);
        } catch (Exception $e) {
            $response_data = array();
            $response_data['STATUS'] = 'ERROR';
            return $response_data;
        }
    }

    public function getUserOfficeDetails($office_id = "")
    {
        if (! isset($_SESSION)) {
            session_start();
        }

        if (! is_numeric($office_id)) {
            exit();
        }

        try {

           $sql = "SELECT  a.office_id,a.dept_id,b.dept_name,office_level_id, a.state_code, a.dcode, a.sub_division_code, a.block_code, a.village_code, a.habitation_code, a.corporation_code, a.corporation_zone_code, a.tpcode, a.municipality_code,c.district_name_en,c.district_name_ta,d.lbody_name_en,d.lbody_name_ta, a.zone_code  as zonecode , z.zone_name FROM security.t_office as a left join security.m_department as b on a.dept_id=b.dept_id LEFT JOIN master.m_district c on a.dcode=c.dcode LEFT JOIN master.m_localbodies d ON a.dcode=d.dcode AND a.tpcode=d.lbcode AND lbtype=:lbtype left join master.m_zone_name z on  z.zone_id = a.zone_code  where a.office_id=:office_id";
            return $this->prepare($sql,array(":lbtype"=>'TP',":office_id"=>$office_id),4);
        } catch (Exception $e) {
            $response_data = array();
            $response_data['STATUS'] = 'ERROR';
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