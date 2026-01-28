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
				) where a.mobile_no=:mobile_no and login_password_otp=:mobile_otp and otp_verify_flag IS NULL";
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
				$response_data['STATUS'] = 'OK';
				$response_data['STATUS_CODE'] = '200';
				$response_data['RESPONSE'] = 'SUCCESS';
				$response_data['MESSAGE'] = "OTP Verified Successfully";
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