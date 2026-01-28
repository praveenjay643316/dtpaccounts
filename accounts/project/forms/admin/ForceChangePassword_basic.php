<?php
require_once __DIR__ . '/../../config/config.php';


class ForceChangePassword extends ConfigClass
{


    public function __construct()
    {
        if (isset($_POST['newpass'])) {
			 return $this->change_password_save($_POST, $_GET);
           
			 exit;
		}
        else{
          
            $this->changePassword();
            exit;
            
        }
    }

	public function changePassword()
	{
		$user_name=$this->getCurrentUser();
		$sql = "SELECT *,DATEDIFF('day',last_password_modified::date,now()::date) as no_of_days_from_update FROM security.t_users as a where user_name=:user_name";
		$res = $this->prepare($sql, array(":user_name" => $user_name), 4);
		if(isset($_SESSION["USER_DETAILS"]["USER_ROLE"][0]["role_code"]) && $_SESSION["USER_DETAILS"]["USER_ROLE"][0]["role_code"] !=''){
			$rolecode=$_SESSION["USER_DETAILS"]["USER_ROLE"][0]["role_code"];
		}
		if($rolecode == 30 && $_SESSION['change_password_required']=='Y'){
			$this->Template("Plaintemplate", "User Role",  $this->change_password(array(),array()), array(
                        array(
                            "name" => "User Role",
                        ),
                    ));
					exit;
		}
		else if ($res["no_of_days_from_update"] >= $this->siteData()->FORCE_PASSWORD_CHANGE) 
		{			
			$_SESSION['change_password_required']='Y';				
			$this->Template("Plaintemplate", "User Role",  $this->change_password(array(),array()), array(
                        array(
                            "name" => "User Role",
                        ),
                    ));
					exit;
		}
		else
		{
			$redirect = "Location:";
			$_SESSION['change_password_required']='N';	           
			header($redirect . $this->siteData()->website_url . "project/forms/logout.php");
			exit;
		}
	}



	public function change_password($data_array_post = array(), $data_array_get = array())
    {             
            $this->Template("Plaintemplate", "User Role",  $this->change_password_view($data_array_post, $data_array_get), array(
                array(
                    "name" => "User Role",
                ),
            ));
            exit;
    }


public function change_password_save($data_array_post = array(), $data_array_get = array())
    {
                // print_r($data_array_post);exit;
        $user = $this->getCurrentUser();
       
        $password1 = $data_array_post['currpass'];
		/*$password1_Validation = $this->Field_Validation(array(
                "Field_Type" => "text_number",
                "Field_Value" => $password1,
                "Field_Label_Name"=>"Current Password"
            ));
			if ($password1_Validation['Status'] == "Error") {
               return $this->change_password(array(
                    "STATUS" => "FAIL",
                    "STATUS_TYPE" => "FORM",
                    "MESSAGE" => "Invalid Current Password"
                ));
                exit();
            }*/
        $pwd = $data_array_post["conpass"];
		/*$pwd_Validation = $this->Field_Validation(array(
                "Field_Type" => "text_number",
                "Field_Value" => $pwd,
                "Field_Label_Name"=>"Confirm Password"
            ));
			if ($pwd_Validation['Status'] == "Error") {
                return $this->change_password(array(
                    "STATUS" => "FAIL",
                    "STATUS_TYPE" => "FORM",
                    "MESSAGE" => "Invalid Confirm Password"
                ));
                exit();
            }*/
        $newpwd = $data_array_post["newpass"];
		/*$newpwd_Validation = $this->Field_Validation(array(
                "Field_Type" => "text_number",
                "Field_Value" => $newpwd,
                "Field_Label_Name"=>"New Password"
            ));
			if ($newpwd_Validation['Status'] == "Error") {
                return $this->change_password(array(
                    "STATUS" => "FAIL",
                    "STATUS_TYPE" => "FORM",
                    "MESSAGE" => "Invalid New Password"
                ));
                exit();
            }*/
      
       
       
		  
        if(strlen($password1)<20 || strlen($pwd)<20 || strlen($newpwd)<20  || trim($data_array_post["newpass"])=="" || trim($data_array_post["conpass"])=="" /* || trim($data_array_post['chngpass_otp'])=="" */ || trim($data_array_post['currpass'])=="" /* || $user!=$data_array_post["username"]*/)
        {
                ob_clean();
                header("400 Bad Request ");
                echo "Invalid Request";
                exit;
        }
        
        if (!$this->validateToken("change_password_page_token", $data_array_post['change_password_page_token'])) {
            return $this->change_password(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Password Change Failed"
            ));
        }
       
       
       
		/* $chn_otp = $data_array_post['chngpass_otp'];
		$chn_otp_Validation = $this->Field_Validation(array(
                "Field_Type" => "text_number",
                "Field_Value" => $chn_otp,
                "Field_Label_Name"=>"OTP"
            ));
			if ($chn_otp_Validation['Status'] == "Error") {
               return $this->change_password(array(
                    "STATUS" => "FAIL",
                    "STATUS_TYPE" => "FORM",
                    "MESSAGE" => "Invalid Password OTP"
                ));
                exit();
            }
        $chngpass_otp = $this->sha512($chn_otp); */
       

        $pass_sql = "SELECT password,change_password_otp FROM security.t_users WHERE user_name=:user";
        $res_pass = $this->prepare($pass_sql, array(
            ":user" => $user
        ), 2);

        foreach ($res_pass as $pass) {
            $ency_pass = $_SESSION['change_password_salt'] . $pass['password'];
            $new_pass =   $this->sha512($ency_pass);
			$change_password_otp=$pass['change_password_otp'];
            // $chngpass_otp_sha = $chngpass_otp;
        }
		

        if ($new_pass != "" && $new_pass == $password1 /* && $change_password_otp == $chngpass_otp_sha */) {
             $pwd = $data_array_post["conpass"];
             $newpwd = $data_array_post["newpass"];
		
             $password = $this->cryptoJsAesDecrypt($_SESSION['change_password_salt'], base64_decode($pwd));
             $newpwd_password = $this->cryptoJsAesDecrypt($_SESSION['change_password_salt'], base64_decode($newpwd));
          
             if ($password==$password1) {
                return $this->change_password(array(
                    "STATUS" => "FAIL",
                    "STATUS_TYPE" => "FORM",
                    "MESSAGE" => "New password should not be Current password"
                ));
            }   

             if ($password!=$newpwd_password) {
                return $this->change_password(array(
                    "STATUS" => "FAIL",
                    "STATUS_TYPE" => "FORM",
                    "MESSAGE" => "New and Confirm password Not Match!!"
                ));
            }   




            $sql = "select * from security.sp_security_t_user(:password,:user,:sp_activity_ip,:sp_current_user)";
            $rs1 = $this->prepare($sql, array(
                ":password" => $password,
                ":user" => $user,
                ":sp_activity_ip"=> $this->getIpAddress(),
                ":sp_current_user"=>$this->getCurrentUser()
            ), 4);

           // var_dump($rs1);

            
            if ($this->prepareStatus($rs1)) {
                $_SESSION['change_password_required']='N';
                $this->activityLog(3,array("session_id"=>session_id()));

               /*$sql_activity = "select security.sp_users_login_history(:sp_security_id,:sp_activity_id ,:sp_activity_ip)";
                $res = $this->prepare($sql_activity, array(
                    ":sp_security_id" => $this->getCurrentUserSecurityID(),
                    ":sp_activity_id" => 3,
                    ":sp_activity_ip" => $this->getIpAddress()
                ));*/

                /*return $this->change_password(array(
                    "STATUS" => "SUCCESS",
                    "STATUS_TYPE" => "FORM",
                    "MESSAGE" => "Password Changed SccessFully"
                ));*/
				
				ob_clean();
			?>
            	<script type="text/javascript">
					alert('Password Changed SccessFully');
					window.location='<?php echo $this->siteData()->website_form_path; ?>logout.php';
				</script>
            <?php
                exit();
            } else {
                return $this->change_password(array(
                    "STATUS" => "FAIL",
                    "STATUS_TYPE" => "FORM",
                    "MESSAGE" => "Password Change Failed 1"
                ));
                exit();
            }
        } else {
            return $this->change_password(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Password Change Failed 2 "
            ));
            exit();
        }
    }

    public function change_password_view($data_array_post = array(), $data_array_get = array())
    {
        $this->page_token = "change_password_salt";

        ob_start();

?>
        <script type="text/javascript" src="<?php

                                            echo $this->siteData()->website_js_path ?>UserSetting.js"></script>
        <script type="text/javascript" src="<?php

                                            echo $this->siteData()->website_js_path ?>sha512.js"></script>
        <script type="text/javascript" src="<?php

                                            echo $this->siteData()->website_js_path ?>aes-json-format.js"></script>
        <script type="text/javascript" src="<?php

                                            echo $this->siteData()->website_js_path ?>aes.js"></script>

        <?php
        if (isset($data_array_post["STATUS"])) {
            echo $this->ShowMessage($data_array_post["STATUS"], $data_array_post["MESSAGE"]);
        }
        ?>
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <form name="passchange" id="passchange" method="post" action="" autocomplete='off'>
                    <input class="form-control form-control-sm" type="hidden" id="<?php

                                                                                    echo "change_password_page_token";
                                                                                    ?>" name="<?php

                                                                                                echo "change_password_page_token";
                                                                                                ?>" value="<?php

                                                                                                            echo $this->token("change_password_page_token");
                                                                                                            ?>">


                    <input type="text" style="display: none" name="mode" id="mode" value="0" />
                    <div align="center">
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <table align="center" class="table table-stripped table-bordered m-0">
                            <tr>
                                <td height="32" colspan="3" class="rowhead">
                                    <div align="center" class="style3">
                                        <h3>Change Password </h3>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="199" class="lnheadmid_repodata">User Name</td>
                                <td colspan="2" align="left"><?php

                                                                                                                                                                                            echo htmlentities($this->getCurrentUser());
                                                                                                                                                                                            ?></td>
                            </tr>
                            <tr>
                              <td class="lnheadmid_repodata">Mobile Number</td>
                              <td class="lnheadmid_repodata">
								<?php
								$user_profile_id=$this->issetCurrentUserProfileID()?$this->getCurrentUserProfileID():NULL;
                                $sel_user_mobile_no="SELECT mobile_no FROM security.t_user_profile WHERE user_profile_id=:user_profile_id;";
                                $sel_user_mobile_no_res=$this->prepare($sel_user_mobile_no,array(":user_profile_id"=>$user_profile_id),4);
                                $user_mobile_no=$sel_user_mobile_no_res['mobile_no'];
                                ?>
                                <span>xxxxx<?php echo substr($user_mobile_no, -5); ?></span>
                              </td>
                            </tr>
                            <tr>
                                <td width="199" class="lnheadmid_repodata">Current Password</td>
                                <td class="lnheadmid_repodata"><input class="form-control form-control-sm" type="hidden" id="<?php

                                                                                                                                echo htmlentities($this->page_token);
                                                                                                                                ?>" name="<?php

                                                                                                                                            echo htmlentities($this->page_token);
                                                                                                                                            ?>" value="<?php

                                                                                                                                                        echo htmlentities($this->token($this->page_token));
                                                                                                                                                        ?>">

                                    <input name="currpass" id="currpass" class="form-control  form-control-sm" onKeyPress="return noenter()" title="Please enter your current password" type="password" />
                                </td>
                            </tr>
                            <?php echo $this->change_password_otp_verify();?>
                            

                            <?php /* ?><tr class="mainrow2">
                                <td class="lnheadmid_repodata">OTP</td>
                                <td colspan="2" align="left" class="lnheadmid_repodata w-75">

                                    <span class="form-inline">
                                        <input name="chngpass_otp" id="chngpass_otp" class="form-control  form-control-sm mr-1" title="Please enter your OTP" type="text" />
                                        <span id="resent_timer">
                                            <?php
											$user_profile_id=$this->getCurrentUserProfileID();
											$user_security_id=$this->getCurrentUserSecurityID();
											
											$upd_otp_cnt="UPDATE security.t_users SET change_password_count=NULL WHERE (extract(hour from (now()::TIMESTAMP-change_password_date::TIMESTAMP)))>=1 AND user_name = :user_name;";
											$upd_otp_cnt_res=$this->prepare($upd_otp_cnt,array(":user_name"=>$this->getCurrentUser()),4);
											
											$sel_chnpass_count="SELECT change_password_count FROM security.t_users WHERE security_id=:security_id AND user_profile_id=:user_profile_id";
											$sel_chnpass_count_res=$this->prepare($sel_chnpass_count,array(":security_id"=>$user_security_id,":user_profile_id"=>$user_profile_id),4);

if($sel_chnpass_count_res['change_password_count']<=10 && !isset($data_array_post["STATUS"]))
{

	$user_profile_id=$this->getCurrentUserProfileID();
	$user_security_id=$this->getCurrentUserSecurityID();
											
		$digits = 6;
		$generated_otp=rand(pow(10, $digits-1), pow(10, $digits)-1);
		$generated_otp_sha= $this->sha512($generated_otp);
		
		$sel_user_mobile="SELECT mobile_no FROM security.t_user_profile WHERE user_profile_id=:user_profile_id";
		$sel_user_mobile_res=$this->prepare($sel_user_mobile,array(":user_profile_id"=>$user_profile_id),4);
		if($this->prepareStatus($sel_user_mobile_res)!=false && $sel_user_mobile_res['mobile_no']!='')
		{
			//$message="Your Change Password OTP is $generated_otp";
			$message="Your login reset OTP is:$generated_otp. Validity 5 minutes - Directorate of Town Panchayats";
			
			$send_msg=$this->sendSMS(3,$sel_user_mobile_res['mobile_no'],$message,'English','INSTANT');
			if($this->prepareStatus($send_msg)!=false)
			{
				$upd_user_otp="UPDATE security.t_users SET change_password_otp=:change_password_otp,change_password_count=(SELECT (coalesce(change_password_count,0)+1) FROM security.t_users WHERE security_id=:security_id AND user_profile_id=:user_profile_id),change_password_date=NOW() WHERE security_id=:security_id AND user_profile_id=:user_profile_id;";
				$upd_user_otp_res=$this->prepare($upd_user_otp,array(":change_password_otp"=>$generated_otp_sha,":security_id"=>$user_security_id,":user_profile_id"=>$user_profile_id),4);
				
				if($this->prepareStatus($upd_user_otp_res)==false)
				{
					$result_array['STATUS']='ERROR';
					$result_array['FIELD_TYPE']='PWD';
					$result_array['MESSAGE']='Please try after sometime';
				}
			}
		}
}

if(isset($data_array_post["STATUS"]))
{
?>
<script type="text/javascript">
$(document).ready(function(){
	$('#timer').html('').hide();  
	$('#resent_otp').removeAttr('disabled');
	sessionStorage.setItem('resent_otp_timer',0);
});
</script>
<?php	
}
											

											if($sel_chnpass_count_res['change_password_count']<=3)
											{
                                            ?>
                                            	<input type="button" id="resent_otp" name="resent_otp" value="Send/Resend OTP" class="btn btn-sm btn-info ml-1" />
                                                <span id="timer" class="text-danger font-weight-bold" style="display:none;">
                                                <span id="time">10</span>Seconds      
                                                </span>
                                           <?php
                                            }
                                            ?>
                                        </span>
                                    </span>
                                    <br />
                                    <input type="button" id="verify_otp" name="verify_otp" value="Verify OTP" class="btn btn-sm btn-primary" />

                                </td>
                            </tr><?php */ ?>

                            <tr>
                                <td id="td_newpassword_field" colspan="2" class="p-0 border-0">

                                </td>
                            </tr>

                        </table>
                        <p>&nbsp;</p>
                    </div>
                </form>
            </div>
        </div>
    <?php
        $ob_output_main_forms = ob_get_contents();
        ob_end_clean();

        return $ob_output_main_forms;
    }


    public function change_password_otp_verify($data_array_post = array(), $data_array_get = array())
    {
        ob_start();
    ?>
        <table align="center" class="table table-stripped table-bordered">
            <tr class="mainrow2">
                <td class="lnheadmid_repodata" style="width: 199px;">New Password</td>
                <td colspan="2" align="left" class="lnheadmid_repodata"><input name="newpass" id="newpass" class="form-control  form-control-sm" onKeyPress="return noenter()" title="Please enter your new password" type="password" /></td>
            </tr>

            <tr class="mainrow2">
                <td class="lnheadmid_repodata"  style="width: 199px;">Confirm Password</td>
                <td colspan="2" align="left" class="lnheadmid_repodata"><input name="conpass" id="conpass" class="form-control  form-control-sm" onKeyPress="return noenter()" title="Please retype your new password" type="password" /></td>
            </tr>

            <tr class="MainRow">
                <td colspan="3" align="center" class="lnheadmid_repodata p-3"><input class="btn btn-primary" type="button" name="cmdAdd" id="cmdAdd" value="CHANGE" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input name="cmdEdit" type="reset" class="btn btn-danger" value="CLEAR">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a class="btn btn-warning" href="<?php

                                                        echo $this->siteData()->website_form_path;
                                                        ?>logout.php">Logout</a>
                </td>
            </tr>

        </table>
<?php
        $ob_output_main_forms = ob_get_contents();
        ob_end_clean();

        return $ob_output_main_forms;
    }
	
	
	 public function forgot_password_otp_verify($data_array_post = array(), $data_array_get = array())
	 {
        ob_start();
    ?>
        <table align="center" class="table table-stripped table-bordered">
            <tr class="mainrow2">
                <td class="lnheadmid_repodata">New Password</td>
                <td colspan="2" align="left" class="lnheadmid_repodata"><input name="newpass" id="newpass" class="form-control  form-control-sm" onKeyPress="return noenter()" title="Please enter your new password" type="password" /></td>
            </tr>

            <tr class="mainrow2">
                <td class="lnheadmid_repodata">Confirm Password</td>
                <td colspan="2" align="left" class="lnheadmid_repodata"><input name="conpass" id="conpass" class="form-control  form-control-sm" onKeyPress="return noenter()" title="Please retype your new password" type="password" /></td>
            </tr>

            <tr class="MainRow">
                <td colspan="3" align="center" class="lnheadmid_repodata p-3"><input class="btn btn-primary" type="submit" name="cmdAdd" id="cmdAdd" value="CHANGE" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input name="cmdEdit" type="reset" class="btn btn-danger" value="CLEAR" onclick="location.reload();">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php /*?><a class="btn btn-warning" href="<?php echo $this->siteData()->website_form_path; ?>logout.php">Logout</a><?php */?>
                </td>
            </tr>

        </table>
<?php
        $ob_output_main_forms = ob_get_contents();
        ob_end_clean();

        return $ob_output_main_forms;
    }

}

$ForceChangePassword=new ForceChangePassword();
//$ForceChangePassword->changePassword();
?>