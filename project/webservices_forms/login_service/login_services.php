<?php 


require_once  __DIR__ . '/../../config/configPublic.php';
require_once __DIR__ . '/../../templates/HtmlHelper.php';
require_once __DIR__ . '/../../library/aes_mobile_app/AesCipher.php';


class service_login   extends ConfigClass
{

	private AesCipher $Aes;

	function __construct($function_name){
		
		
		
		$this->Aes = new AesCipher();
		
		
		 if (method_exists($this, $function_name)) {
			echo $this->$function_name();				
		}
		else
		{
			echo '{"STATUS":"FAIL"}';
		}
		
	}	
	
	
	public function version_check()
	{	
		$req_params=array('appcode');
	
		$appcode=$_POST['appcode'];		
			
		$sql="SELECT appcode,version FROM app.m_mobile_version where appcode=:appcode"; 
		$res = $this->prepare($sql,array(":appcode"=>$appcode),2);
		
		if(count($res)>0)
		{
			$result=array();
			$result['STATUS']='SUCCESS';
			//$vcheck=$res[0];
			$result['DATA']=$res[0];

			echo json_encode($result);
		}
		else
		{
			echo '{"STATUS":"FAIL","RESPONSE":"INVALID_REQUEST 1"}';
			exit;
		}
	}
	
	
	public function login()
	{		
		
		try {
		
		$response_data=array();
		$req_params=array('user_login_key','user_name','user_pwd','appcode');
		
		
		$user_login_key=$_POST['user_login_key'];
		$user_name=$_POST['user_name'];
		$user_pwd=$_POST['user_pwd'];
		$appcode=$_POST['appcode'];
		
		
		$sql="SELECT a.dcode,a.lbcode,e.district_name_en,d.lbody_name_en,g.role_name,user_name,password,a.app_key,d.lbtype,a.active,b.user_first_name,b.user_last_name,b.role_id as role_code,e.state_code,h.designation_name as desig_name,b.designation_id as desig_code,user_type,level
		FROM security.t_users as a  
		left join 
		security.t_user_profile as b 
		on a.user_profile_id=b.user_profile_id 
		left join 
		master.m_localbodies as d 
		on a.dcode=d.dcode and d.lbcode=a.lbcode and d.lbtype='TP' 
		left join 
		master.m_district as e 
		on a.dcode=e.dcode
 		left join 
		security.m_role as g 
		on g.role_code=b.designation_id 
		left join 
		security.m_designation as h 
		on h.designation_id=b.designation_id 
 		where  user_name=:user_name"; 
		$res = $this->prepare($sql,array(":user_name"=>$user_name),2);
	//print_r($res);
		if(count($res)==1)
		{			
			$response_data['STATUS']='OK';
			
			foreach ($res as $login) 
			{ 	

			//print_r($login);
					$check_level_where=array();
					$check_level_where_array=array();
					if($login['state_code']!="")
					{
						$check_level_where[]=" state_code=:state_code ";
						$check_level_where_array[':state_code']=$login['state_code'];
					}
					
					if($login['dcode']!="")
					{
						$check_level_where[]="  dcode=:dcode ";
						$check_level_where_array[':dcode']=$login['dcode'];
					}
					
					if($login['lbcode']!="")
					{
						$login['lbtype']='TP';
						$check_level_where[]="  lbcode=:lbcode and lbtype=:lbtype  ";
						$check_level_where_array[':lbcode']=$login['lbcode'];
						$check_level_where_array[':lbtype']=trim($login['lbtype']);
					}
										
					$check_level_where[]="	app_code=:app_code";
					$check_level_where_array[':app_code']=$appcode;
					
			
					  $sql_lock_user="SELECT id,state_code, dcode, lbcode,lbtype, app_code, app_id, inactive, ins_username, ins_ipaddress, ins_date, upd_username, upd_ipaddress, upd_date, del_username, del_ipaddress, del_upd_date, del_flag
					  FROM app.m_mobile_app_lock where ".implode(' and ',$check_level_where)." ;"; 	
	
					$sql_lock_user_res = $this->prepare($sql_lock_user,$check_level_where_array,2);	
					
					if(count($sql_lock_user_res)>0)
					{
						$sql_lock_user_res_arr=$sql_lock_user_res[0];
						
						
						if($sql_lock_user_res_arr['inactive']=='Y')
						{
							$response_data['STATUS']='OK'; 
							$response_data['RESPONSE']='LOGIN_FAILED';
							$response_data['MESSAGE']='User Locked by State Level Admin';
							$response_data['ERROR_ID']=1;
							return json_encode($response_data);
						}
						
					}	
			
				
				 if($login['active']!='1')
				{
					$response_data['STATUS']='OK'; 
					$response_data['RESPONSE']='LOGIN_FAILED';
					$response_data['MESSAGE']='LOGIN FAILED';
					$response_data['ERROR_ID']=2;
				}
				if( $login['app_key']=='')
				{
					$response_data['STATUS']='OK'; 
					$response_data['RESPONSE']='LOGIN_FAILED';
					$response_data['MESSAGE']='APP KEY NOT FOUND';
					$response_data['ERROR_ID']=3;
				}
				else if($login['active']=='1')
				{
					//$db_pwd=$login['password'];
					$sha_pwd=hash('sha256', $login['password'].$user_login_key);
					
					if(strtolower($sha_pwd)==strtolower($user_pwd))
					{
						$response_data['RESPONSE']='LOGIN_SUCCESS';
						$response_data['MESSAGE']='LOGIN SUCCESS';
						$response_data['KEY']=$this->Aes->encrypt(($login['password']),'',$login['app_key']);
						$response_values=array();
						$response_values['state_code']=$login['state_code'];
						$response_values['dcode']=$login['dcode'];
						$response_values['lbtype']=$login['lbtype'];
						$response_values['lbcode']=$login['lbcode'];
						$response_values['dname']=$login['district_name_en'];
						$response_values['lbname']=$login['lbody_name_en'];
						$response_values['role_code']=$login['role_code'];
						$response_values['role_name']=$login['role_name'];
						$response_values['desig_name']=$login['desig_name'];
						$response_values['desig_code']=$login['desig_code'];
						$response_values['user_type']=$login['user_type'];
						$response_values['level']=$login['level'];
						$response_values['user_first_name']=$login['user_first_name'];
						$response_values['user_last_name']=$login['user_last_name'];
						
					$response_data['user_data']=$this->Aes->encrypt($login['password'],'',json_encode($response_values));
						
						
								
					}
					else
					{
						$response_data['STATUS']='OK';
						$response_data['RESPONSE']='LOGIN_FAILED';
						$response_data['MESSAGE']='LOGIN FAILED';
						$response_data['ERROR_ID']=4;
					}
					
				}	 		
				
			}

		}
		else
		{
			$response_data['STATUS']='OK';
			$response_data['RESPONSE']='LOGIN_FAILED';
			$response_data['MESSAGE']='LOGIN FAILED';
			$response_data['ERROR_ID']=5;
			
		}
				
		return json_encode($response_data);
		
	}

//catch exception
catch(Exception $e) 
{
			$response_data=array();
				$response_data['STATUS']='ERROR';
				$response_data['RESPONSE']='LOGIN_FAILED';
				$response_data['MESSAGE']='LOGIN FAILED';				
				return json_encode($response_data);
}	
		
		
		
	}
}

$service_login=new service_login(preg_replace("/[^A-Za-z0-9?![:space:]_]/","",$_POST['service_id']));

?>