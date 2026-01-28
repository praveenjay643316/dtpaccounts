<?php

// ########################################################################
// SITE INFO
// ########################################################################
trait siteInfo
{
	
	function is_cli()
	{
		if ( defined('STDIN') )
		{
			return true;
		}
	
		if ( php_sapi_name() === 'cli' )
		{
			return true;
		}
	
		if ( array_key_exists('SHELL', $_ENV) ) {
			return true;
		}
	
		if ( empty($_SERVER['REMOTE_ADDR']) and !isset($_SERVER['HTTP_USER_AGENT']) and count($_SERVER['argv']) > 0) 
		{
			return true;
		} 
	
		if ( !array_key_exists('REQUEST_METHOD', $_SERVER) )
		{
			return true;
		}
	
		return false;
	}

    public function serverDetails()
    {
         $ip = isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:(isset($_SERVER['LOCAL_ADDR'])?$_SERVER['LOCAL_ADDR']:null);

        $development_ip_list = array(
            '10.163.19.140',   //ARUL
			'10.163.19.139',   //KARTHIK
			'10.163.19.133',  //POOJA
            '10.163.19.174',		//VIGNESH
			'10.163.19.158',  //MUTHU
			'10.163.19.159',  //PRASANTH
			'10.163.19.177',		//SUVEDHA
            '10.163.19.170',	      //Sivasankari	
			'10.163.19.157', 	// Kaviyaa	
			'10.163.2.94', // Ramya
			'10.163.2.93', // Praveen
			'10.163.2.95' ,// Rahul
			'10.163.2.39' // Mahaprabu			
				
        );
        $production_ip_list = array(
             '10.163.31.18',
            '14.139.183.34','10.163.2.122','10.163.0.196','127.0.0.1','10.163.0.197','10.163.77.59','10.163.77.60','164.100.167.197','10.236.211.233' // training	
        );

        return (object) array(
            "SERVER_ADDR" => $ip,
            "development_ip_list" => $development_ip_list,
            "production_ip_list" => $production_ip_list
        );
    }

    public function dbData()
    {
        $serverDetails = $this->serverDetails();

        if (in_array($serverDetails->SERVER_ADDR, $serverDetails->development_ip_list)) {

            return (object) (array(
                "dbserver" => "pgsql",
                "dbuser" => "postgres",
                "dbpass" => "postgres",
                "dbname" => "intra_dtp_03",
                "dbport" => "5432",
                "dbhost" => "10.163.31.21"
            ));
        } else if (in_array($serverDetails->SERVER_ADDR, $serverDetails->production_ip_list) || $this->is_cli()) {
            return (object) (array(
                "dbserver" => "pgsql",
                "dbuser" => "postgres",
                "dbpass" => "postgres",
                //"dbname" => "intra_dtp_1",
                "dbname" => "tndtp_audit",
                "dbhost" => "10.163.31.23",
                "dbport" => "5433"
            ));
        }
    }

    public function siteData()
    {
        $serverDetails = $this->serverDetails();

        if (in_array($serverDetails->SERVER_ADDR, $serverDetails->development_ip_list)) {

            //$website_url = "https://10.163.19.140/tndtp/";
            // $website_url = "http://10.163.19.140/tndtp_stag/";

           // $physical_path = "c:/BitNami/wappstack-5.6.18-1/apache2/htdocs/tndtp/";
		   
		     $DOCUMENT_ROOT=str_replace('/', '\\',$_SERVER['DOCUMENT_ROOT']);			
			$folder_path = ltrim(str_replace('\\', '/',str_replace($DOCUMENT_ROOT,'',(dirname( dirname(__FILE__),2 )))),"/");
			if($folder_path!=''){
                $website_url = "/".$folder_path."/";
            }else{
                $website_url = "/";
            }

            $physical_path = dirname( dirname(__FILE__),2 )."/";//"c:/BitNami/wappstack-5.6.18-1/apache2/htdocs/vptax_new/";


            $website_name = "TAX ONLINE";

            return (object) (array(

                "website_name" => $website_name,

                "website_url" => $website_url,

                "physical_path"=>$physical_path,

                "website_logout" => $website_url . "project/forms/logout.php",

                //"qr_website_url" => "http://10.163.2.93/tndtp/",
                //"data_storage_path" => "c:/home/apache24/htdocs/tnrd/data/tndtp_stag/data_storage/",
<<<<<<< HEAD:accounts/project/config/siteInfo.php
				"data_storage_path" => dirname( dirname(__FILE__),3 )."/data/tndtp_egov/",
=======
				"qr_website_url" => "http://10.163.2.94/tndtp_work_monitoring/accounts/",
				"data_storage_path" => dirname( dirname(__FILE__),3 )."/data/tndtp_work_monitoring/accounts/",
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/config/siteInfo.php

                "website_form_path" => $website_url . "project/forms/",
                "physical_form_path" => $physical_path . "project/forms/",

                "website_report_path" => $website_url . "project/reports/",
                "physical_report_path" => $physical_path . "project/reports/",

                "website_css_path" => $website_url . "css/",
                "physical_css_path" => $physical_path . "css/",

                "website_pdf_path" => $website_url . "files/pdf/",
                "physical_pdf_path" => $physical_path . "files/pdf/",

                "website_js_path" => $website_url . "js/",
                "physical_js_path" => $physical_path . "js/",

                "website_image_path" => $website_url . "images/",
                "physical_image_path" => $physical_path . "images/",

                "website_library_path" => $website_url . "library/",
                "physical_library_path" => $physical_path . "library/",

                "website_files_path" => $website_url . "files/",
                "physical_files_path" => $physical_path . "files/",

                "mode" =>"DEVELOPMENT",

                "allow_multiple_login"=>true,

                "session_expire_idle_time"=>60*60,
                
                /* FORCE_PASSWORD_CHANGE 15 Days */
                "FORCE_PASSWORD_CHANGE" => 1000000,
                /* USED in SMS Sender in library Files smsSender.php */
              //  "SMSUrl" => "https://tnsec.tn.nic.in/tndtp_message_service/sms_service_testing.php"
            ));
        } else if (in_array($serverDetails->SERVER_ADDR, $serverDetails->production_ip_list) || $serverDetails->SERVER_ADDR=="" || $this->is_cli()) {

             $website_url = "https://training.tnrd.tn.gov.in:8443/dtpworks/accounts/";

            $physical_path = "/home2/nginx/html1/dtpworks/accounts/";

            $website_name = "TAX ONLINE";

            return (object) (array(

                "website_name" => $website_name,

                "website_url" => $website_url,

                "physical_path"=>$physical_path,

                "website_logout" => $website_url . "project/forms/logout.php",
                //"data_storage_path" => "c:/home/apache24/htdocs/tnrd/data/tndtp_stag/data_storage/",
                "data_storage_path" => "/home2/nginx/html1/dtpworks/accounts/data/",
                //"data_storage_path" => dirname(dirname(__FILE__),3 )."/data/tndtp/",

                "qr_website_url" => "https://training.tnrd.tn.gov.in:8443/dtpworks/",
                "website_form_path" => $website_url . "project/forms/",
                "physical_form_path" => $physical_path . "project/forms/",

                "website_report_path" => $website_url . "project/reports/",
                "physical_report_path" => $physical_path . "project/reports/",

                "website_css_path" => $website_url . "css/",
                "physical_css_path" => $physical_path . "css/",

                "website_pdf_path" => $website_url . "files/pdf/",
                "physical_pdf_path" => $physical_path . "files/pdf/",

                "website_js_path" => $website_url . "js/",
                "physical_js_path" => $physical_path . "js/",

                "website_image_path" => $website_url . "images/",
                "physical_image_path" => $physical_path . "images/",

                "website_library_path" => $website_url . "library/",
                "physical_library_path" => $physical_path . "library/",

                "website_files_path" => $website_url . "files/",
                "physical_files_path" => $physical_path . "files/",

                //"mode" =>"PRODUCTION",
		"mode" =>"DEVELOPMENT",

                "allow_multiple_login"=>true,

                "session_expire_idle_time"=>20*60,
                
                /* FORCE_PASSWORD_CHANGE 15 Days */
                "FORCE_PASSWORD_CHANGE" => 800000,
                /* USED in SMS Sender in library Files smsSender.php */
                "SMSUrl" =>  $website_url."project/library/sms_service_testing.php"
            ));
        }
    }
}
