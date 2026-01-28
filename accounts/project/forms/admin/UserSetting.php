<?php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/ChangePassword.php';





class UserSetting extends ConfigClass
{
    use ChangePassword;

    function __construct()
    {    
        $this->siteData = $this->siteData();
    }

    public function main_form($data_array_post = array(), $data_array_get = array())
    {
        if (! isset($data_array_get['page'])) {
            $data_array_get['page'] = base64_encode("change_password");
        }

        ob_start();
        ?>
		<style>
		.settings-tab > ul > li > a.hover, .settings-tab > ul > li > a.focus{
			color: #495057!important;
			background-color: #fff!important;
			border-color: #dee2e6 #dee2e6 #fff!important;
		}
		
		</style>



        <?php

        switch (base64_decode($data_array_get['page'])) {
            case "change_password":
                echo $this->change_password($data_array_post, $data_array_get);
                break;
            default:
                echo "Page Not Found !!!";
        }

        ?>
       


<?php

        $ob_output_main_forms = ob_get_contents();
        ob_end_clean();

       

        $this->Template("Template1", "Work Creation Form", $ob_output_main_forms, array(
            array(
                "name" => "Work Creation Form"
            )
        ));
		
        exit();
    }

 
}

$UserSetting = new UserSetting();

$UserSetting->main_form($_POST, $_GET);

?>           