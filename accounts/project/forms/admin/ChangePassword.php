<?php

trait ChangePassword
{

    public $page_token = "";

    public $siteData = "";

    public function change_password($data_array_post = array(), $data_array_get = array())
    {      
        if (!isset($data_array_post['newpass'])) {
            return $this->change_password_view($data_array_post, $data_array_get);
        } else {
            return $this->change_password_save($data_array_post, $data_array_get);
        }
    }

    public function change_password_save($data_array_post = array(), $data_array_get = array())
    {

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
      
       
       
		  
        if(strlen($password1)<20 || strlen($pwd)<20 || strlen($newpwd)<20  || trim($data_array_post["newpass"])=="" || trim($data_array_post["conpass"])=="" || /*trim($data_array_post['chngpass_otp'])=="" ||*/ trim($data_array_post['currpass'])=="" /* || $user!=$data_array_post["username"]*/)
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
       
       
       
		/*$chn_otp = $data_array_post['chngpass_otp'];
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
        $chngpass_otp = $this->sha512($chn_otp);*/
       

<<<<<<< HEAD:accounts/project/forms/admin/ChangePassword.php
        $pass_sql = "SELECT password,change_password_otp FROM security.t_accounts_users WHERE user_name=:user";
=======
        $pass_sql = "SELECT password,change_password_otp FROM security.t_users WHERE user_name=:user";
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/admin/ChangePassword.php
        $res_pass = $this->prepare($pass_sql, array(
            ":user" => $user
        ), 2);

        foreach ($res_pass as $pass) {
            $ency_pass = $_SESSION['change_password_salt'] . $pass['password'];
            $new_pass =   $this->sha512($ency_pass);
			$change_password_otp=$pass['change_password_otp'];
           // $chngpass_otp_sha = $chngpass_otp;
        }
		
        //echo $new_pass."==". $password1;exit;

        if ($new_pass != "" && $new_pass == $password1 /*&& $change_password_otp == $chngpass_otp_sha*/) {
             $pwd = $data_array_post["conpass"];
             $newpwd = $data_array_post["newpass"];
		
             $password = $this->cryptoJsAesDecrypt($_SESSION['change_password_salt'], base64_decode($pwd));
             $newpwd_password = $this->cryptoJsAesDecrypt($_SESSION['change_password_salt'], base64_decode($newpwd));
          
             if ( $this->sha512($_SESSION['change_password_salt'].$password)==$password1) {
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




<<<<<<< HEAD:accounts/project/forms/admin/ChangePassword.php
            $sql = "select * from security.sp_security_t_accounts_user(:password,:user,:sp_activity_ip,:sp_current_user)";
=======
            $sql = "select * from security.sp_security_t_user(:password,:user,:sp_activity_ip,:sp_current_user)";
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/admin/ChangePassword.php
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
                    "MESSAGE" => "Password Change Failed"
                ));
                exit();
            }
        } else {
            return $this->change_password(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Password Change Failed "
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
        <div class="card-body">
        <div class="row justify-content-center card-body">
            <div class="col-lg-5">
                <form name="passchange" id="passchange" method="post" action="<?php

                                                                                echo $this->siteData()->website_form_path;
                                                                                ?>admin/UserSetting.php?page=<?php

                                                                                                                echo base64_encode("change_password");
                                                                                                                ?>" autocomplete='off'>
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
                        <table align="center" class="table table-stripped">
                            <tr>
                                <td height="32" colspan="3" class="rowhead">
                                    <div align="center" class="style3">
                                        <h3 class="m-2">Change Password </h3>
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
<<<<<<< HEAD:accounts/project/forms/admin/ChangePassword.php
                                $sel_user_mobile_no="SELECT mobile_no FROM security.t_accounts_user_profile WHERE user_profile_id=:user_profile_id;";
=======
                                $sel_user_mobile_no="SELECT mobile_no FROM security.t_user_profile WHERE user_profile_id=:user_profile_id;";
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/admin/ChangePassword.php
                                $sel_user_mobile_no_res=$this->prepare($sel_user_mobile_no,array(":user_profile_id"=>$user_profile_id),4);
                                $user_mobile_no=$sel_user_mobile_no_res['mobile_no'];
                                ?>
                                <span>xxxxx<?php echo substr($user_mobile_no, -5); ?></span>
                              </td>
                            </tr>
                            <tr>
                                <td width="199" class="lnheadmid_repodata">Current Password</td>
                                <td class="lnheadmid_repodata"><input class="form-control form-control-sm" type="hidden" id="<?php

 echo htmlentities($this->page_token);?>" name="<?php
echo htmlentities($this->page_token); ?>" value="<?php
echo htmlentities($this->token($this->page_token));
                                                                                                                                                        ?>">

                                    <input name="currpass" id="currpass" class="form-control  form-control-sm" onKeyPress="return noenter()" title="Please enter your current password" type="password" />
                                </td>
                            </tr>
<?php echo $this->change_password_otp_verify(); ?>
                           

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
        <table align="center" class="table table-stripped">
            <tr class="mainrow2">
                <td class="lnheadmid_repodata" style="width: 199px;">New Password</td>
                <td colspan="2" align="left" class="lnheadmid_repodata"><input name="newpass" id="newpass" class="form-control  form-control-sm" onKeyPress="return noenter()" title="Please enter your new password" type="password" /></td>
            </tr>

            <tr class="mainrow2">
                <td class="lnheadmid_repodata" style="width: 199px;">Confirm Password</td>
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
        <table align="center" class="table table-stripped">
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
                   
                </td>
            </tr>

        </table>
<?php
        $ob_output_main_forms = ob_get_contents();
        ob_end_clean();

        return $ob_output_main_forms;
    }	


}

?>