<?php

class SecurityCheck
{

    function __construct()
    {}


    public function REMOVE_CODE()
    {

        return;
        $get_string =  file_get_contents('php://input');

            if($get_string!="")
            {
        $x=explode("&",$get_string);

        if(is_array($x))
        {
        foreach($x as $y)
        {
        $z=explode("=",$y);
        if (is_array($z) && !preg_match('/^[a-zA-Z_][a-zA-Z0-9_\[\]]*$/',urldecode($z[0])))
            {
              
           echo "Invalid Request Error ID:X1Key";    
          exit; 
            }
        }
    }
}


            foreach($_POST as $key=>$val)
            {

                if(is_array($val))
                continue; 

                if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/',$key))
                {
                    echo "Invalid Request Error ID:1";
                        exit(); 
                }

                   /* if(strpos(strtolower($key), '.') !== false)
                    { 
                        echo "Invalid Request Error ID:2";
                        exit();  
                    }*/

                    if((!is_array($val)) && strpos(strtolower($val), 'alert') !== false)
                    {
                        echo "Invalid Request Error ID:3";
                        exit();  
                    }

                    if((!is_array($val)) && strpos(strtolower($val), 'script') !== false)
                    {
                        echo "Invalid Request Error ID:4";
                        exit();  
                    }

                    if((!is_array($val)) && strpos(strtolower($val), 'confirm') !== false)
                    {
                        echo "Invalid Request Error ID:5";
                        exit();  
                    }

                    if((!is_array($val)) && strpos(strtolower($val), '<img') !== false)
                    {
                        echo "Invalid Request Error ID:6";
                        exit();  
                    }

                    if((!is_array($val)) && strpos(strtolower($val), 'src=') !== false)
                    {
                        echo "Invalid Request Error ID:7";
                        exit();  
                    }

                    if((!is_array($val)) && strpos(strtolower($val), 'window') !== false)
                    {
                        echo "Invalid Request Error ID:8";
                        exit();  
                    }

                    if((!is_array($val)) && strpos(strtolower($val), 'iframe') !== false)
                    {
                        echo "Invalid Request Error ID:9";
                        exit();  
                    }

                

                    if((!is_array($val)) && strpos(strtolower($val), '"') !== false)
                    {
                        echo "Invalid Request Error ID:10";
                        exit();  
                    }

                    if((!is_array($val)) &&  substr_count($val,"'")>1)
                    {
                        echo "Invalid Request Error ID:11";
                        exit();  
                    }

                    if((!is_array($val)) &&  substr_count($val,"`")>0)
                    {
                        echo "Invalid Request Error ID:12";
                        exit();  
                    }

                    if((!is_array($val)) &&  substr_count($val,'"')>1)
                    {
                        echo "Invalid Request Error ID:13";
                        exit();  
                    }

                    if((!is_array($val)) &&  substr_count($val,'%')>0)
                    {
                        echo "Invalid Request Error ID:14";
                        exit();  
                    }                    

                    if((!is_array($val)) &&  substr_count($val,'<')>0)
                    {
                        echo "Invalid Request Error ID:15";
                        exit();  
                    }

                    if( (!is_array($val)) && substr_count($val,'>')>0)
                    {
                        echo "Invalid Request Error ID:16";
                        exit();  
                    }
                    
                    if((!is_array($val)) && preg_match("/upload|script|delete|alert|window|location|onmouseover|source|style|click/",$val)) {

                        echo "Invalid Request Error ID:17";
                        exit();  
                    }

                    $badChars = array("select", "drop", ";", "--", "insert", "script", "delete", "xp_", "union", "|", ";", "&", "%", "href");
                  
                    for ($i = 0; $i < count($badChars); $i++) {
                        if((!is_array($val)) &&  substr_count($val,$badChars[$i])>0)
                        {
                            echo "Invalid Request Error ID:18";
                            exit();  
                        }
                    }
                   
                   

            }
    }
	
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

    public function GET_CHECK()
    {

        if(!$this->is_cli())
	   {

        if (!in_array($_SERVER['REQUEST_METHOD'],array('GET','POST'))) 
            {
            header('HTTP/1.1 400 Bad Request');
            exit;
            }

            

        if (count($_GET) > 0) {
            foreach ($_GET as $getkey => $get) {

               

                if (! (base64_encode(base64_decode($get, true)) === $get)) {
					
                    echo 'SECURITY CHECK : MESSAGE : PLAIN TEXT ON GET : <span style="color:red">' . /* htmlentities($getkey).. */  " => " . /* htmlentities($get) .*/ "</span> NOT ALLOWED USE BASE64 ENCODE";
                    exit();
                }

                if(strpos(strtolower($getkey), '.') !== false)
                {
                    echo "Invalid Request Error ID:G1 ";
                    exit();  
                }

                if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/',$getkey))
                {
                    echo "Invalid Request Error ID:G2";
                        exit(); 
                }
            }
        }
		
		/* if (count($_POST) > 0) {
			 
				 $get_key=array_keys($_POST);
				 $found = false;
					foreach ($get_key as $key) {
						//If the key is found in your string, set $found to true
						if (preg_match("/_token/", $key)) {
							$found = true;
						}
					}
					
					if($found==false)
					{
						 echo "SECURITY CHECK : MESSAGE : No TOKEN FOUND";
                    	exit();
					}
			 
			 }*/
			 
	   }
		
    }

    public function STOP_REQUEST()
    {
        //$_REQUEST = array();
    }
}
?>