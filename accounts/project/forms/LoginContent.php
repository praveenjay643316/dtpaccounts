<?php
require_once __DIR__ . '/../config/configPublic.php';
require_once __DIR__ . '/../../library/captcha.php';

class LoginContent extends ConfigClass
{

    

    public function nav()
    {
        ob_start();
        $NodbCommonFunctions = new NodbCommonFunctions();
        $captcha = new captcha();
        $site_data = $this->siteData();
		

        if(isset($_POST['password']))
        {
          header('HTTP/1.1 400 Bad Request');
          exit;
        }         
        ?>
<style>
.screen {
    /* background: linear-gradient(150deg, #1B394D 33%, #2D9DA7 34%, #2D9DA7 66%, #EC5F20 67%); */
    /* background: linear-gradient(150deg, #1B394D 33%, #2D9DA7 34%, #2D9DA7 66%, #1B394D 67%); */
<<<<<<< HEAD:accounts/project/forms/LoginContent.php
    /* background: linear-gradient(150deg, #2C2B5E 33%, #ee2b31 34%, #ee2b31 66%, #2C2B5E 67%); */
    /* background: linear-gradient(150deg, #75e3e3 33%, #6decf9 34%, #04bebe 66%, #04bebe 67%); */
    
background: linear-gradient(378deg, #006a6a 34%, #c1c1c1 66%, #006c6c 98%, #026a6a 56%);

=======
    background: linear-gradient(150deg, #2C2B5E 33%, #ee2b31 34%, #ee2b31 66%, #2C2B5E 67%);
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/LoginContent.php
    border-radius: 10px;
    position: relative;
    font-family: 'Raleway', sans-serif;
    text-align: center;
    /* padding: 54px 36px 56px; */
    padding: 30px 20px 50px;

}

.screen__content {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
    /* background: linear-gradient(90deg, #003d62, #0c2a3b); */
    position: relative;
    width: 100%;
    box-shadow: 0px 0px 24px #5c5696;
    /* z-index: 1;
    position: relative;
    height: 100%; */
}

/* .screen__background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 0;
    -webkit-clip-path: inset(0 0 0 0);
    clip-path: inset(0 0 0 0);
} */

/* .screen__background__shape {
    transform: rotate(45deg);
    position: absolute;
} */
.close-icon {
    cursor: pointer;
    color: white;
    position: absolute;
    top: 8px;
    right: 36px;
    font-size: 20px;
}

/* .screen__background__shape1 {
    height: 560px;
    width: 540px;
    background: #fff;
    top: -50px;
    right: 150px;
    border-radius: 0 72px 0 0;
}

.screen__background__shape2 {
    height: 225px;
    width: 250px;
    background: #154766;
    top: -200px;
    right: 20px;
    border-radius: 32px;
} */

/* .screen__background__shape3 {
    height: 490px;
    width: 160px;
    background: rgb(22, 77, 110);
    background: linear-gradient(90deg, rgba(22, 77, 110, 1) 0%, rgba(20, 68, 98, 1) 50%, rgba(17, 60, 86, 1) 100%);
    top: -55px;
    right: 0;
    border-radius: 32px;
}

.screen__background__shape4 {
    height: 375px;
    width: 140px;
    background: #2f6688;
    top: 320px;
    right: 50px;
    border-radius: 60px;
} */

.login {
    width: 285px;
    padding: 16px;
    padding-top: 25px;
    /* width: 350px;
    padding: 30px;
    padding-top: 45px; */
}

.login__field {
    padding: 20px 0px;
    position: relative;
}

.login__icon {
    position: absolute;
    top: 32px;
    /* color: #154766; */
<<<<<<< HEAD:accounts/project/forms/LoginContent.php
    /* color: #2C2B5E; */
    color:#037e7e;
=======
    color: #2C2B5E;
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/LoginContent.php
}

.login__input {
    border: none;
    border-bottom: 2px solid #d1d1d4;
    background: none;
    padding: 10px;
    padding-left: 30px;
    font-weight: 700;
    width: 90%;
    transition: 0.2s;
}

.login__input:active,
.login__input:focus,
.login__input:hover {
    outline: none;
    /* border-bottom-color: #1B394D; */
    border-bottom-color: #2C2B5E;
    /* border-bottom-color: #EC5F20; */
}

.login__submit {
    border-radius: 5px;
    color: #fff;
    /* background-color: #1B394D; */
<<<<<<< HEAD:accounts/project/forms/LoginContent.php
    /* background-color: #2C2B5E; */
    background-color:#068989;
=======
    background-color: #2C2B5E;
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/LoginContent.php
    /* background-color: #EC5F20; */
    font-size: 17px;
    text-transform: capitalize;
    letter-spacing: 2px;
    width: 51%;
    padding: 10px;
    /* width: 100%;
    padding: 12px; */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    transition: all 0.4s ease 0s;
    border: none;
}

/* .login__submit:hover,
.login__submit:focus {
    font-weight: 600;
    letter-spacing: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3) inset;
} */


/* .login__submit:active,
.login__submit:focus,
.login__submit:hover {
    border-color: #154766;
    outline: none;
} */

.button__icon {
    /* font-size: 24px; */
    font-size: 20px;
    margin-left: auto;
    color: white;
    border: none;
}

.social-login {
    position: absolute;
    height: 140px;
    width: 160px;
    text-align: center;
    bottom: 0px;
    right: 0px;
    color: #fff;
}

.social-icons {
    display: flex;
    align-items: center;
    justify-content: center;
}

.social-login__icon {
    padding: 20px 10px;
    color: #fff;
    text-decoration: none;
    text-shadow: 0px 0px 8px #7875b5;
}

.social-login__icon:hover {
    transform: scale(1.5);
}



#login-nav {
    background: none !important;

}



.form-icon {
    top: 24px;
    left: 111px;
    /* left: 140px; */
    position: absolute;
    color: #fff;
    /* background-color: #1B394D;#2C2B5E */
<<<<<<< HEAD:accounts/project/forms/LoginContent.php
    /* background-color: #2C2B5E; */
    /* background-color:#04bebe; */
    background: linear-gradient(9deg, #006a6a 17%, #9b9393 66%, #006c6c 98%, #026a6a 56%);
=======
    background-color: #2C2B5E;
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/LoginContent.php
    font-size: 75px;
    line-height: 92px;
    height: 90px;
    width: 90px;
    margin: -65px auto 10px;
    border-radius: 50%;
}



.tab .nav-tabs {
    padding: 0;
    margin: 0;
    border: none;
}

.tab .nav-tabs li a {
    color: #fff;
    background: #096fa4 !important;
    font-weight: 600;
    text-align: center;

    padding: 15px;
    margin: 0 10px 10px 0;
    border: none;
    border-radius: 15px;
    z-index: 1;
    transition: all 0.3s ease 0s;
}

.tab .nav-tabs li.active a,
.tab .nav-tabs li a:hover,
.tab .nav-tabs li.active a:hover {
    color: #144563 !important;
    background: #383838;
    border: none;
}

.tab .nav-tabs li a:before,
.tab .nav-tabs li a:after {
    content: '';
    background-color: #144563;
    height: 100%;
    width: 0;
    border-radius: 15px;
    position: absolute;
    right: 0;
    top: 0;
    z-index: -1;
    clip-path: polygon(100% 0, 0% 100%, 100% 100%);
    transition: all 0.3s ease 0s;
}

.tab .nav-tabs li a:after {
    background-color: #fff;

    box-shadow: 0 0 10px rgba(0, 0, 0, 0.7);
    transform: scale(0);
    clip-path: none;

    width: 110%;

    left: -10px;
}

.tab .nav-tabs li.active a:before,
.tab .nav-tabs li a:hover:before,
.tab .nav-tabs li.active a:hover:before {
    width: 100%;
}

.tab .nav-tabs li.active a:after,
.tab .nav-tabs li a:hover:after,
.tab .nav-tabs li.active a:hover:after {
    transform: scale(0.88, 0.73);
}

@media only screen and (max-width: 479px) {
    .tab .nav-tabs {
        padding: 0;
        margin: 0 0 5px;
    }

    .tab .nav-tabs li {
        width: 100%;
        text-align: center;
    }

    .tab .nav-tabs li a {
        margin: 0 0 5px;
    }
}

.accountsheader{
    /* color:#04bebe; */
    color:#068989;
    padding:10px;
}

</style>

<form runat="server" method="POST" class="appointment-form" id="login_form" style="display:none" autoComplete="off">
    <input type="text" name="user_name" id="user_name" autoComplete="off">
    <input type="text" name="encpwd" id="encpwd" autoComplete="off">
    <input type="hidden" value="1" name="hidden_public_users" id="hidden_public_users">
    <input name="captchaval" id="captchaval" type="text" autoComplete="off">
</form>
<div class="container login-form px-0" style="width: auto;">



    <?php
		if(isset($_POST['loginState']) && base64_decode($_POST['loginState'])=='fail')
		{
		  ?>
    <div class="alert alert_login_fail alert-danger mt-3" role="alert" id="Alert">
        Login failed, Invalid Username or Password.
    </div>
    <?php 
		}
	?>
    <div class="screen">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="showAlert()">
            <span class="close-icon"><i class="fa fa-times"></i></span>
        </button>
        <div class="screen__content">
            <form class="login">
                <div class="form-icon">
                    <i class="fa fa-user-circle"></i>
                </div>
                 <h5 class="accountsheader">Accounts</h5>
                <div class="login__field">
                    <i class="login__icon fa fa-envelope fa-lg"></i>
                    <input type="text" autoComplete="off" class="login__input" placeholder="User name / Email"
                        name="user_name_temp" id="user_name_temp" autofocus>
                </div>
                <div class="login__field">
                    <i class="login__icon fa fa-unlock-alt fa-lg"></i>
                    <input type="password" autoComplete="off" class="login__input" placeholder="Password"
                        name="password_temp" id="password_temp">
                </div>
                <div class="login__field">
                    <i class="login__icon fa fa-refresh fa-lg"></i>
                    <input type="text" class="login__input texboxval" placeholder="Enter Captcha" name="captchaval_temp"
                        id="captchaval_temp" autocomplete='off' required />
                    <img border="0" id="captcha" src="<?php echo $captcha->generateNewCaptcha('login_captcha'); ?>"
                        alt="" style="height: 30px; width: 175px;" />
                    <img class="d-inline crossRotate"
                        src="<?php echo htmlentities($site_data->website_url); ?>images/reload.png" alt="Mountain View"
                        onClick="reload();" title="Refresh Captcha"
                        style="width:30px;height:30px;margin:3px;margin-top:5px">
                    <input style="display: none;" name="salt" id="salt"
                        value="<?php echo $NodbCommonFunctions->token("salt"); ?>" />
                    <?php /*?><a href="javascript:void(0);"
                        onclick="$(this).closest('div').find('#captcha-audio-section').css({'display':'block'});return false;"
                        class="captchaLink" alt="Captcha Audio" title="Captcha Audio"><i class="fa fa-headphones" style="font-size: 20px;position: relative;
    top: 3px;"></i></a>
                    <br>
                    <div id="captcha-audio-section" style="display:none;    margin-bottom: 5%;    float: right;">
                        <div style="clear: both;"></div>

                        <audio id="captchademo" style="display:block!important;    width: 280px;" controls="controls">
                            <source
                                src="<?php echo htmlentities($site_data->website_url); ?>library/login_string_audio.php?aud=<?php echo base64_encode(time()); ?>"
                                type="audio/mpeg" />
                            Your browser does not support the audio element.
                        </audio>
                    </div><?php */?>
                </div>
                <button class="button login__submit" name="submit" id="submit">
                    <span class="button__text">Sign In</span>
                    <i class="button__icon fa fa-sign-in"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="form-submit col-md-12 d-none" style="text-align:center;">
    <div class="row">
        <div class="text-left col-md-6">
            <a href="<?php echo $site_data->website_form_path; ?>Public/UserForgotPassword.php" class="indexmore"
                name="btn_forgot_pwd"
                onClick="window.location='<?php echo $site_data->website_form_path; ?>Public/UserForgotPassword.php'">Forgot
                Password?</a>
        </div>
        <div class="text-right col-md-6" id="registration" style="display:none;">
            &nbsp;&nbsp;<a href="<?php echo $site_data->website_form_path; ?>Public/UserRegistration.php"
                style="text-align:right;" class="indexmore" name="public_register" id="public_register"
                onClick="window.location='<?php echo $site_data->website_form_path; ?>Public/UserRegistration.php'">New
                User? Register</a>
        </div>
    </div>
</div>


</div>
</div>

<?php

        $ob_output_main_forms = ob_get_contents();
        ob_clean();

        echo $ob_output_main_forms;
        exit();
    }
}

$LoginContent = new LoginContent();

if (isset($_POST['code']) && $_POST['code'] == "nav")
    $LoginContent->nav();

?>