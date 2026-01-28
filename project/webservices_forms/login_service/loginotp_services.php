<?php 


require_once  __DIR__ . '/../../config/configPublic.php';
require_once __DIR__ . '/../../templates/HtmlHelper.php';
require_once __DIR__ . '/../../library/aes_mobile_app/AesCipher.php';


class service_login   extends ConfigClass
{

	private AesCipher $Aes;

	function __construct(){
		$this->Aes = new AesCipher();
		$data_receive = file_get_contents('php://input');
		$data_receive_json = json_decode($data_receive);
		$function_name = preg_replace("/[^A-Za-z0-9?![:space:]_]/", "", $data_receive_json->service_id);

		 if (method_exists($this, $function_name)) {
			echo $this->$function_name($data_receive_json);				
		}
		else
		{
			echo '{"STATUS":"FAIL"}';
		}
		
	}	
	
	public function genOTPNumber()
	{

		return $six_digit_random_number = mt_rand(100000, 999999);
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
			$response_data['STATUS_CODE'] = '200';
			$result['DATA']=$res[0];
			echo json_encode($result);
		}
		else
		{
			echo '{"STATUS":"FAIL","STATUS_CODE:"400",","RESPONSE":"INVALID_REQUEST 1"}';
			exit;
		}
	}
	
	

	public function SendOtp($decrypted_data_json)
	{	
		$response_data = array();
		$mobile = $decrypted_data_json->mobile_number;


		$verify_mobile = "select user_profile_id from(
							(select user_profile_id,mobile_no from security.t_user_profile where del_flag is null )a
							left join
							(select user_profile_id as user_id from security.t_users where del_flag is null)b
							on a.user_profile_id=b.user_id
							) where a.mobile_no=:mobile_no";
		$res_verify_mobile = $this->prepare($verify_mobile, array(":mobile_no" => $mobile), 4);

		if (count($res_verify_mobile) > 0) {

			$verify_otp = "select user_profile_id from(
							(select user_profile_id,mobile_no from security.t_user_profile where del_flag is null )a
							left join
							(select user_profile_id as user_id,otp_verify_flag from security.t_users where del_flag is null)b
							on a.user_profile_id=b.user_id
							) where a.mobile_no=:mobile_no ";
			$res_verify = $this->prepare($verify_otp, array(":mobile_no" => $mobile), 4);

			$user_profile_id = $res_verify['user_profile_id'];
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['STATUS_CODE'] = '400';
			$response_data['RESPONSE'] = 'FAIL';
			$response_data['MESSAGE'] = "Mobile Number Not Registered";

			return json_encode($response_data);
		}

		if (count($res_verify) > 0) {

			$mobile_otp = $this->genOTPNumber();
			$upd_send_otp = "UPDATE security.t_users
								SET login_password_otp = :mobile_otp,
									otp_created_date = NOW()
								FROM security.t_user_profile
								WHERE security.t_users.user_profile_id = security.t_user_profile.user_profile_id
								AND security.t_user_profile.del_flag IS NULL
								AND security.t_user_profile.mobile_no = :mobile_no
								AND security.t_users.user_profile_id = :user_profile_id";
			$result = $this->prepare($upd_send_otp, array(":mobile_otp" => $mobile_otp, ":mobile_no" => $mobile, ":user_profile_id" => $user_profile_id), 4);


			$message = "TNDTP Public services Verification OTP $mobile_otp-Directorate of Town Panchayats";
			$send_msg = $this->send(11, $mobile, $message, 'English');


			if ($this->prepareStatus($result) === true && $send_msg == true) {
				$response_data['STATUS'] = 'OK';
				$response_data['STATUS_CODE'] = '200';
				$response_data['RESPONSE'] = 'OK';
				$response_data['MESSAGE'] = "OTP Send Successfully";
			} else {
				$response_data['STATUS'] = 'OK';
				$response_data['STATUS_CODE'] = '400';
				$response_data['RESPONSE'] = 'FAIL';
				$response_data['MESSAGE'] = "Resend OTP Failed";
			}
			return json_encode($response_data);
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['STATUS_CODE'] = '400';
			$response_data['RESPONSE'] = 'FAIL';
			$response_data['MESSAGE'] = "Already OTP Verified So Can't Resend the OTP";

			return json_encode($response_data);
		}
	}
	public function VerifyOtp($decrypted_data_json)
	{
		$response_data = array();
		$error_check = array();
		$mobile = $decrypted_data_json->mobile_number;
		$login_password_otp = $decrypted_data_json->mobile_otp;
		$ip_address=$this->getIpAddress();

		$verify = "select user_profile_id from(
			(select user_profile_id,mobile_no from security.t_user_profile where del_flag is null )a
			left join
			(select user_profile_id as user_id,otp_verify_flag,login_password_otp from security.t_users where del_flag is null)b
			on a.user_profile_id=b.user_id
			) where a.mobile_no=:mobile_no and login_password_otp=:login_password_otp";
		$res_verify_otp = $this->prepare($verify, array(":mobile_no" => $mobile, ":login_password_otp" => $login_password_otp), 4);

		if (count($res_verify_otp) > 0) {

			$verify_otp = "select user_profile_id from(
				(select user_profile_id,mobile_no from security.t_user_profile where del_flag is null )a
				left join
				(select user_profile_id as user_id,otp_verify_flag,login_password_otp from security.t_users where del_flag is null)b
				on a.user_profile_id=b.user_id
				) where a.mobile_no=:mobile_no and login_password_otp=:mobile_otp ";
			$res_verify = $this->prepare($verify_otp, array(":mobile_no" => $mobile, ":mobile_otp" => $login_password_otp), 4);
			$user_profile_id = $res_verify['user_profile_id'];
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['STATUS_CODE'] = '400';
			$response_data['RESPONSE'] = 'FAIL';
			$response_data['MESSAGE'] = "OTP you entered is Incorrect";

			return json_encode($response_data);
		}

		if (count($res_verify) > 0) {
			$upd_verify = "UPDATE security.t_users
			SET 
			otp_verify_date = NOW(),
			otp_verify_flag='Y',
			otp_verify_ipaddress=:ip_address
			FROM security.t_user_profile
			WHERE security.t_users.user_profile_id = security.t_user_profile.user_profile_id
			AND security.t_user_profile.del_flag IS NULL
			AND security.t_user_profile.mobile_no = :mobile_no
			AND security.t_users.user_profile_id = :user_profile_id";
			$res_upd_verify = $this->prepare($upd_verify, array(":ip_address" => $ip_address, ":user_profile_id" => $user_profile_id, ":mobile_no" => $mobile), 4);

			if ($this->prepareStatus($res_upd_verify) === true) {
		
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
 		where  mobile_no=:mobile_no"; 
		$res = $this->prepare($sql,array(":mobile_no"=>$mobile),2);
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
					
						$response_data['RESPONSE']='LOGIN_SUCCESS';
						$response_data['MESSAGE']='OTP VERIFIED SUCCESS';
                        $response_data['KEY']=$this->Aes->encrypt(($login_password_otp),'',$login['app_key']);
						$response_values=array();
						$response_values['state_code']=$login['state_code'];
						$response_values['dcode']=$login['dcode'];
						$response_values['lbtype']=$login['lbtype'];
						$response_values['lbcode']=$login['lbcode'];
						$response_values['dname']=$login['district_name_en'];
						$response_values['lbname']=$login['lbody_name_en'];
                        $response_values['user_name']=$login['user_name'];
						$response_values['role_code']=$login['role_code'];
						$response_values['role_name']=$login['role_name'];
						$response_values['desig_name']=$login['desig_name'];
						$response_values['desig_code']=$login['desig_code'];
						$response_values['user_type']=$login['user_type'];
						$response_values['level']=$login['level'];
						$response_values['user_first_name']=$login['user_first_name'];
						$response_values['user_last_name']=$login['user_last_name'];
                        $response_data['user_data']=$this->Aes->encrypt($login_password_otp,'',json_encode($response_values));			
					
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
			} else {
				$response_data['STATUS'] = 'OK';
				$response_data['STATUS_CODE'] = '400';
				$response_data['RESPONSE'] = 'FAIL';
				$response_data['MESSAGE'] = "Failed to Verify OTP";
				return json_encode($response_data);
			}
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['STATUS_CODE'] = '400';
			$response_data['RESPONSE'] = 'FAIL';
			$response_data['MESSAGE'] = "Already OTP Verified";

			return json_encode($response_data);
		}
	}
	public function ResendOtp($decrypted_data_json)
	{	
		$response_data = array();
		$mobile = $decrypted_data_json->mobile_number;


		$verify_mobile = "select user_profile_id from(
							(select user_profile_id,mobile_no from security.t_user_profile where del_flag is null )a
							left join
							(select user_profile_id as user_id from security.t_users where del_flag is null)b
							on a.user_profile_id=b.user_id
							) where a.mobile_no=:mobile_no";
		$res_verify_mobile = $this->prepare($verify_mobile, array(":mobile_no" => $mobile), 4);

		if (count($res_verify_mobile) > 0) {

			$verify_otp = "select user_profile_id from(
							(select user_profile_id,mobile_no from security.t_user_profile where del_flag is null )a
							left join
							(select user_profile_id as user_id,otp_verify_flag from security.t_users where del_flag is null)b
							on a.user_profile_id=b.user_id
							) where a.mobile_no=:mobile_no ";
			$res_verify = $this->prepare($verify_otp, array(":mobile_no" => $mobile), 4);

			$user_profile_id = $res_verify['user_profile_id'];
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['STATUS_CODE'] = '400';
			$response_data['RESPONSE'] = 'FAIL';
			$response_data['MESSAGE'] = "Mobile Number Not Registered";

			return json_encode($response_data);
		}

		if (count($res_verify) > 0) {

			$mobile_otp = $this->genOTPNumber();
			$upd_send_otp = "UPDATE security.t_users
								SET login_password_otp = :mobile_otp,
									otp_created_date = NOW()
								FROM security.t_user_profile
								WHERE security.t_users.user_profile_id = security.t_user_profile.user_profile_id
								AND security.t_user_profile.del_flag IS NULL
								AND security.t_user_profile.mobile_no = :mobile_no
								AND security.t_users.user_profile_id = :user_profile_id";
			$result = $this->prepare($upd_send_otp, array(":mobile_otp" => $mobile_otp, ":mobile_no" => $mobile, ":user_profile_id" => $user_profile_id), 4);
			$message = "TNDTP Public Services Reset OTP $mobile_otp Validity 20 Minutes - Directorate of Town Panchayats";
			
			$send_msg = $this->send(10, $mobile, $message, 'English');


			if ($this->prepareStatus($result) === true && $send_msg == true) {
				$response_data['STATUS'] = 'OK';
				$response_data['STATUS_CODE'] = '200';
				$response_data['RESPONSE'] = 'OK';
				$response_data['MESSAGE'] = "OTP Send Successfully";
			} else {
				$response_data['STATUS'] = 'OK';
				$response_data['STATUS_CODE'] = '400';
				$response_data['RESPONSE'] = 'FAIL';
				$response_data['MESSAGE'] = "Resend OTP Failed";
			}
			return json_encode($response_data);
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['STATUS_CODE'] = '400';
			$response_data['RESPONSE'] = 'FAIL';
			$response_data['MESSAGE'] = "Already OTP Verified So Can't Resend the OTP";

			return json_encode($response_data);
		}
	}
}
$service_login = new service_login();

?>