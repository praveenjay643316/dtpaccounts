<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../internalservices/ServiceLogin.php';

class logout extends configClass
{

    function __construct()
    {
        try {
            $ServiceLogin = new ServiceLogin();

            $this->activityLog(2,array("session_id"=>session_id()));
           /* $sql_activity = "select security.sp_users_login_history(:sp_security_id,:sp_activity_id ,:sp_activity_ip)";
            $this->prepare($sql_activity, array(
                ":sp_security_id" => $this->getCurrentUserSecurityID(),
                ":sp_activity_id" => 2,
                ":sp_activity_ip" => $this->getIpAddress()
            ));*/
           
            if ($ServiceLogin->userLogout($this->getCurrentUser())) {
				session_start();
				 $this->regenerateSession();
                header("Location:" . $this->siteData()->website_url);
            }
			else
			{
				 session_destroy();
			}
        } catch (Exception $e) {
            print_r($e);
        }
		
    }

    
	public function regenerateSession()
	{
		
		// Simply calling session_regenerate_id() may result in lost session, etc.
		// See next example.
		session_regenerate_id();
	}
}

$logout = new logout();
?>