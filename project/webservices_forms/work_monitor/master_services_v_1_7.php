<?php 
require_once  __DIR__ . '/../../config/configPublic.php';
require_once __DIR__ . '/../../templates/HtmlHelper.php';
require_once __DIR__ . '/../../library/aes_mobile_app/AesCipher.php';

class master_service extends ConfigClass
{

	private AesCipher $Aes;
    public $app_key = NULL; 

	function __construct(){	
		
		$data_receive = file_get_contents('php://input');
		$data_receive_json=json_decode($data_receive);
		if (is_null($data_receive_json)) {
			echo json_encode(['STATUS'=>'FAIL', 'MESSAGE' => 'INVALID OR EMPTY INPUT']);
			exit;
		}else{
			$user=$data_receive_json->user_name;
			$data_content=$data_receive_json->data_content;
		}

			
			$c_l_response=$this->check_login($user);
			$data_content=$data_receive_json->data_content;
			if($c_l_response['STATUS']=='OK')
			{
				$this->app_key=$c_l_response['KEY'];
          
				
				$decrypted_data=AesCipher::decrypt($this->app_key,$data_content);
					$decrypted_data_json=json_decode($decrypted_data);
					$decrypted_data_json->user_data=$c_l_response['USER_DATA'];
				   $function_name=preg_replace("/[^A-Za-z0-9?![:space:]_]/","",$decrypted_data_json->service_id);
                  //print_r($decrypted_data_json);die;
                  if (method_exists($this, $function_name)) 
	   				{
						echo $this->$function_name($decrypted_data_json);
					}
					else
					{
						echo '{"STATUS":"FAIL"}';
					}				
			}
		
	}
    public function service_list()
	{
		/***********************************************************************************
		Available Service List
		************************************************************************************/	
		$service_list=array();
		$service_list[]=array('service_id'=>'district_list_all','arguments'=>array());	
		$service_list[]=array('service_id'=>'local_body_list_district_wise','arguments'=>array('dcode'));
		$service_list[]=array('service_id'=>'local_body_list_all','arguments'=>array('dcode'));
		$service_list[]=array('service_id'=>'scheme_finyear_list_last_nyears','arguments'=>array("nyear"));
		$service_list[]=array('service_id'=>'thittam_finyear_list','arguments'=>array());
		$service_list[]=array('service_id'=>'work_type_stage_link','arguments'=>array());

		
			
		 
		return json_encode($service_list);
	}	
	public function check_login($user)
	{

        $sql="SELECT user_name,password,active,app_key FROM security.t_users where user_name=:user_name"; 
		$res = $this->prepare($sql,array(":user_name"=>$user),2);
		if(count($res)==1)
		{
            $login=$res[0];
			if($login["active"]=="Y") {
				if($login['active']=='1')
				{
					return array("STATUS"=>"OK","STATUS_CODE"=>"200","RESPONSE"=>"SUCCESS","MESSAGE"=>"SUCCESS","KEY"=>$login["app_key"],"USER_DATA"=>$res[0]);	
				}
                else
				{
                    return array("STATUS"=>"FAIL","STATUS_CODE"=>"400","RESPONSE"=>"LOGIN_FAILED","MESSAGE"=>"LOGIN FAILED","ERROR_ID"=>"1");	
				}
			}
		}
		else
		{
			return array("STATUS"=>"FAIL","STATUS_CODE"=>"400","RESPONSE"=>"LOGIN_FAILED","MESSAGE"=>"LOGIN FAILED","ERROR_ID"=>"2");
		}
	}
    public function district_list_all($decrypted_data_json)
	{	
		
		
		$response_data=array();
		$req_params=array();
		if(count(array_intersect(array_keys($_POST),$req_params))!=count($req_params))
		{
			            $response_data['STATUS']='OK'; 
                        $response_data['STATUS_CODE']='400';
                        $response_data['RESPONSE']='FAILED';
                        $response_data['MESSAGE']='FAILED';
                        $response_data['ERROR_ID']=1;
                        
		}		
		
		$sql="SELECT  dcode,district_name_en, district_name_ta  FROM master.m_district order by dcode";
		$res = $this->prepare($sql,array(),2); 
	
		if(count($res)>0)
		{
                        $response_data['STATUS']='OK'; 
                        $response_data['STATUS_CODE']='200';
                        $response_data['RESPONSE']='SUCCESS';
                        $response_data['MESSAGE']='SUCCESS';
                        $response_data['JSON_DATA']=$res;
                        

		}
		else
		{
			$response_data['STATUS']='OK';
            $response_data['STATUS_CODE']='400';
			$response_data['RESPONSE']='NO_RECORD';
			$response_data['MESSAGE']='NO_RECORD';
           
			
		}		
		return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
	}
    public function local_body_list_district_wise($decrypted_data_json)
	{	
		
		
		$response_data=array();
		$req_params=array();
        $dcode=$decrypted_data_json->dcode;
		if(count(array_intersect(array_keys($_POST),$req_params))!=count($req_params))
		{
			            $response_data['STATUS']='OK'; 
                        $response_data['STATUS_CODE']='400';
                        $response_data['RESPONSE']='FAILED';
                        $response_data['MESSAGE']='FAILED';
                        $response_data['ERROR_ID']=1;
                        
		}		
		
		$sql="SELECT  dcode,lbcode,lbody_name_en, lbody_name_ta  FROM master.m_localbodies  where dcode=:dcode order by lbcode";
		$res = $this->prepare($sql,array(":dcode"=>$dcode),2); 
	
		if(count($res)>0)
		{
                        $response_data['STATUS']='OK'; 
                        $response_data['STATUS_CODE']='200';
                        $response_data['RESPONSE']='SUCCESS';
                        $response_data['MESSAGE']='SUCCESS';
                        $response_data['JSON_DATA']=$res;
                       

		}
		else
		{
			$response_data['STATUS']='OK';
            $response_data['STATUS_CODE']='400';
			$response_data['RESPONSE']='NO_RECORD';
			$response_data['MESSAGE']='NO_RECORD';
           
			
		}		
		return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
	}
	public function townpanchayat_list_district_wise($decrypted_data_json)
	{	
		/***********************************************************************************
		townpanchayat_list_district_wise
		************************************************************************************/
		
		$response_data=array();
		$req_params=array();
		$dcode=$decrypted_data_json->dcode;
		if(count(array_intersect(array_keys($_POST),$req_params))!=count($req_params))
		{
			echo '{"STATUS":"FAIL","RESPONSE":"INVALID_REQUEST"}'; 
			exit;
		}		
		
		$sql="SELECT  dcode, lbcode,lbody_name_en as lbname_en, lbody_name_ta as lbname_ta  FROM master.m_localbodies where del_flag is null and dcode=:dcode";
		$res = $this->prepare($sql,array(":dcode"=>$dcode),2);  
	
		if(count($res)>0)
		{
			$response_data['STATUS']='OK';
			$response_data['RESPONSE']='OK';
			$response_data['JSON_DATA']=$res;			

		}
		else
		{
			$response_data['STATUS']='OK';
			$response_data['RESPONSE']='NO_RECORD';
			$response_data['MESSAGE']='NO_RECORD';
			
		}
		//echo (json_encode($response_data));		
		return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
	}
    public function local_body_list_all($decrypted_data_json)
	{	
		
		
		$response_data=array();
		$req_params=array();
        //$dcode=$decrypted_data_json->dcode;
		if(count(array_intersect(array_keys($_POST),$req_params))!=count($req_params))
		{
			            $response_data['STATUS']='OK'; 
                        $response_data['STATUS_CODE']='400';
                        $response_data['RESPONSE']='FAILED';
                        $response_data['MESSAGE']='FAILED';
                        $response_data['ERROR_ID']=1;
                        
		}		
		
		$sql="SELECT  dcode,lbcode,lbody_name_en as lbname_en, lbody_name_ta as lbname_ta FROM master.m_localbodies  order by lbcode";
		$res = $this->prepare($sql,array(),2); 
	
		if(count($res)>0)
		{
                        $response_data['STATUS']='OK'; 
                        $response_data['STATUS_CODE']='200';
                        $response_data['RESPONSE']='SUCCESS';
                        $response_data['MESSAGE']='SUCCESS';
                        $response_data['JSON_DATA']=$res;
                       

		}
		else
		{
			$response_data['STATUS']='OK';
            $response_data['STATUS_CODE']='400';
			$response_data['RESPONSE']='NO_RECORD';
			$response_data['MESSAGE']='NO_RECORD';
           
			
		}		
		return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
	}
    
    public function scheme_finyear_list_last_nyears($decrypted_data_json)
	{	
		
		$response_data=array();
		$req_params=array();
		$nyear=$decrypted_data_json->nyear;
		if(count(array_intersect(array_keys($_POST),$req_params))!=count($req_params))
		{
			echo '{"STATUS":"FAIL","RESPONSE":"INVALID_REQUEST"}';
			exit;
		}		
		
		$sql = "SELECT fin_year FROM master.m_fin_year where del_flag is null ORDER BY fin_year LIMIT :fin_count";
        $res = $this->prepare($sql, array(":fin_count" => $nyear), 2);
	
		if(count($res)>0)
		{
			$response_data['STATUS']='OK';
			$response_data['RESPONSE']='OK';
            $response_data['STATUS_CODE']='200';
			$response_data['JSON_DATA']=$res; 	
           		

		}
		else
		{
			$response_data['STATUS']='OK';
            $response_data['STATUS_CODE']='400';
			$response_data['RESPONSE']='NO_RECORD';
			$response_data['MESSAGE']='NO_RECORD';
           
			
		}
		
		return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
	}
    public function thittam_finyear_list($decrypted_data_json)
	{	
		
		$response_data=array();
		$req_params=array();
		//$nyear=$decrypted_data_json->nyear;
		if(count(array_intersect(array_keys($_POST),$req_params))!=count($req_params))
		{
			echo '{"STATUS":"FAIL","RESPONSE":"INVALID_REQUEST"}';
			exit;
		}		
		
		$sql = "SELECT fin_year FROM master.m_fin_year where del_flag is null  and fin_yearid > :fin_count ORDER BY fin_year";
        $res = $this->prepare($sql, array(":fin_count" => 39), 2);
	
		if(count($res)>0)
		{
			$response_data['STATUS']='OK';
			$response_data['RESPONSE']='OK';
            $response_data['STATUS_CODE']='200';
			$response_data['JSON_DATA']=$res; 	
           	

		}
		else
		{
			$response_data['STATUS']='OK';
            $response_data['STATUS_CODE']='400';
			$response_data['RESPONSE']='NO_RECORD';
			$response_data['MESSAGE']='NO_RECORD';
            
			
		}
		
		return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
	}
    public function work_type_stage_link($decrypted_data_json)
	{		
		$response_data=array();
		$req_params=array();
		if(count(array_intersect(array_keys($_POST),$req_params))!=count($req_params))
		{
			echo '{"STATUS":"FAIL","RESPONSE":"INVALID_REQUEST"}';
			exit;
		}		
		
		$sql="select a.work_group_id as work_group_id,a.work_id as work_type_id,a.work_stage_order,a.work_stage_id as work_stage_code ,b.stage_name AS work_stage_name,1 as min_photos,1 as max_photos 
            from master.m_work_stage_link as a 
            left join 
            master.m_stage as b 
            on a.work_stage_id=b.stage_id 
            left join 
            (select distinct work_group_id,work_id from  master.m_scheme_worktype_link ) as x 
            on a.work_group_id=x.work_group_id and a.work_id=x.work_id order by a.work_stage_link_id";
        $res = $this->prepare($sql, array(), 2);
		if(count($res)>0)
		{
			$response_data['STATUS']='OK';
			$response_data['RESPONSE']='OK';
            $response_data['STATUS_CODE']='200';
			$response_data['JSON_DATA']=$res; 	
           

		}
		else
		{
			$response_data['STATUS']='OK';
            $response_data['STATUS_CODE']='400';
			$response_data['RESPONSE']='NO_RECORD';
			$response_data['MESSAGE']='NO_RECORD';
          
			
		}	
		return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
	}
	public function inspection_status($data_content)
	{	
		/***********************************************************************************
		inspection_status
		************************************************************************************/
		
		$response_data=array();
		$req_params=array();		
		
		$sql="select status_id,status from master.m_inspection_status order by status_id";
		$res = $this->prepare($sql, array(), 2);
	
		if(count($res)>0)
		{
			$response_data['STATUS']='OK';
			$response_data['RESPONSE']='OK';
			$response_data['JSON_DATA']=$res; 			

		}
		else
		{
			$response_data['STATUS']='OK';
			$response_data['RESPONSE']='NO_RECORD';
			$response_data['MESSAGE']='NO_RECORD';
			
		}
		//echo (json_encode($response_data));
		return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
	}

}

$master_service = new master_service();

?>