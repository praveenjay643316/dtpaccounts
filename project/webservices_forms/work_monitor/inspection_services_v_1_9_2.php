<?php 
error_reporting(E_ERROR);
require_once  __DIR__ . '/../../config/configPublic.php';
require_once __DIR__ . '/../../templates/HtmlHelper.php';
require_once __DIR__ . '/../../library/aes_mobile_app/AesCipher.php';
require_once __DIR__ . '/../JWTFunction.php';
require_once __DIR__ . '/../../library/mpdf8/vendor/autoload.php';

require_once __DIR__ . '/work_inspection_functions_v_1_6.php';
class service_login   extends ConfigClass
{
	private $mpdf;
	private AesCipher $Aes;
    public $app_key = NULL; 
	public $domain_name = "tndtp.tn.gov.in";
	use JWTFunction;
	use WorkInspectionFunction;
	
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
			$this->mpdf = new \Mpdf\Mpdf();
			//print_r($this->mpdf);die;
				//Testing mode
		//  $this->app_key=$c_l_response['KEY'];
		//  $header_data=array();
		// $header_data['response_data']=$data_receive_json;
		//  $header_data['user_name']=$c_l_response['USER_DATA']['user_name'];
		//  $this->CreateHeader($header_data); 

			$data_content=$data_receive_json->data_content;
			//print_r($c_l_response['KEY']);die;
			if ($c_l_response['STATUS'] == 'OK' ) {
				$this->app_key = $c_l_response['KEY'];
				$headers = apache_request_headers();
				if (!isset($headers['Authorization']) || !preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
        		    header('HTTP/1.0 400 Bad Request');
        		    echo 'Token not found in request';
        		    exit;
        		}
				$jwt_token = $matches[1];
				
				if (!$jwt_token) {
					header('HTTP/1.0 400 Bad Request');
					exit;
				}
	
				$verify_jwt_token = $this->VerifyJWT($jwt_token, $data_receive);
				//print_r($verify_jwt_token); die;
				if ($verify_jwt_token['RESPONSE'] == 'SUCCESS') {
					$data_content->user_data = $c_l_response['USER_DATA'];
					
					$function_name = preg_replace("/[^A-Za-z0-9?![:space:]_]/", "", $data_content->service_id);
	
					if (method_exists($this, $function_name)) {
						if (is_array($data_content) || is_object($data_content)) {
							print_r($this->$function_name($data_content));
						} else {
							echo $this->$function_name($data_content);
						}
					} else {
						$response_data['STATUS'] = 'OK';
						$response_data['RESPONSE'] = 'FAIL';
						$response_data['MESSAGE'] = "Service id not exist";
						$header_data['response_data'] = $response_data;
					}
				} else {
					$header_data['response_data'] = $verify_jwt_token;
				}
	
				$header_data['user_name'] = $data_content->user_data['user_name'];
				//$header_data['response_data'] = 'hi';
	//print_r($header_data);die;
				$this->CreateHeader($header_data);
			} else {
				$response_data = array();
						$response_data['STATUS'] = 'OK';
						$response_data['RESPONSE'] = 'FAIL';
						$response_data['MESSAGE'] = "LOGIN FAILED";
						$header_data['response_data'] = $response_data;
						echo json_encode($response_data);
						exit;

						
			}
		
	}
    public function service_list()
	{
		/***********************************************************************************
		Available Service List
		************************************************************************************/	
		$service_list=array();
		$service_list[]=array('service_id'=>'scheme_list_district_finyear_wise','arguments'=>array("dcode(*)[]","finyear[]"));
		$service_list[]=array("finyear_village_wise_work_list",'arguments'=>array('fin_year','scheme_id','pvcode'));
		$service_list[]=array("work_additional_details_save",'arguments'=>array());	
		$service_list[]=array('service_id'=>'get_calculate_distance','arguments'=>array('latitude','longitude','distance'));
		$service_list[]=array("work_phy_stage_save",'arguments'=>array());	
		$service_list[]=array("work_phy_stage_image",'arguments'=>array("dcode","bcode","pvcode","work_id"));	
		$service_list[]=array("cd_work_phy_stage_image",'arguments'=>array("dcode","bcode","pvcode","work_id","cd_prot_workid"));
		$service_list[]=array("rural_urban_work_list",'arguments'=>array());
		$service_list[]=array("scheme_list_rural_urban_wise",'arguments'=>array());
			
		 
		return json_encode($service_list);
	}	
	public function check_login($user)
	{
// 		select user_name,password,active,app_key,dcode,lbcode,security_id,b.user_profile_id,state_code,a.designation_id as desig_code,c.designation_name as desig_name from 
// 		(select user_name,password,active,app_key,dcode,lbcode,security_id,user_profile_id,state_code from security.t_users where  del_flag is null and user_name=:user_name)b
// 		left join
// 				(select user_first_name,gender,mobile_no,designation_id,email_address,user_address,role_id,user_profile_id from security.t_user_profile where  del_flag is null)a 
// on a.user_profile_id=b.user_profile_id
// left join 
// (SELECT designation_name,designation_id FROM security.m_designation where del_flag is null)c
// on a.designation_id=c.designation_id

        $sql="SELECT user_name,password,active,app_key,dcode,lbcode,security_id,user_profile_id,state_code FROM security.t_users where user_name=:user_name"; 
		$res = $this->prepare($sql,array(":user_name"=>$user),2);
		if(count($res)==1)
		{
            $login=$res[0];
				
				  if($login['active']=='1')
				{		
					        $response_data['STATUS']='OK'; 
                            $response_data['STATUS_CODE']='200';
                            $response_data['RESPONSE']='SUCCESS';
                            $response_data['MESSAGE']='SUCCESS';
							$response_data['KEY']=$login['app_key'];
							$response_data['USER_DATA']=$res[0];
							return ($response_data);			
					
				}
                else
		{
                        $response_data['STATUS']='OK'; 
                        $response_data['STATUS_CODE']='400';
                        $response_data['RESPONSE']='LOGIN_FAILED';
                        $response_data['MESSAGE']='LOGIN FAILED';

                        $response_data['ERROR_ID']=1;
                        return ($response_data);
		}	 		
				
			
		}
		else
		{
			            $response_data['STATUS']='OK'; 
                        $response_data['STATUS_CODE']='400';
                        $response_data['RESPONSE']='LOGIN_FAILED';
                        $response_data['MESSAGE']='LOGIN FAILED';

                        $response_data['ERROR_ID']=2;
                        return ($response_data);
		}
	}
    public function current_finyear_wise_status_count($data_content)
	{

		$header_data = array();
		$header_data['user_name'] = $data_content->user_data['user_name'];
		$response_data = array();
		$req_params = array();
		$security_id = $data_content->user_data['security_id'];
		$user_profile_id = $data_content->user_data['user_profile_id'];
		
		

		$status_wise_count = "
		select coalesce(sum(satisfied),0) as satisfied,coalesce(sum(unsatisfied),0) as unsatisfied,coalesce(sum(need_improvement),0) as need_improvement,fin_year,inspection_type from
		(select sum(case when status_id=1 then 1 else 0 end) as satisfied,sum(case when status_id=2 then 1 else 0 end) as unsatisfied,sum(case when status_id=3 then 1 else 0 end) as need_improvement,(select master.sp_fin_year_from_date(current_date)) as fin_year,'tndtp' as inspection_type from works.t_work_inspection_details where del_flag is null and security_id=:security_id and user_profile_id=:user_profile_id  and (select master.sp_fin_year_from_date(inspection_date::date))=(select master.sp_fin_year_from_date(current_date))
		union all
		select sum(case when status_id=1 then 1 else 0 end) as satisfied,sum(case when status_id=2 then 1 else 0 end) as unsatisfied,sum(case when status_id=3 then 1 else 0 end) as need_improvement,(select master.sp_fin_year_from_date(current_date)) as fin_year,'other' as inspection_type from works.t_other_work_inspection_details where del_flag is null and security_id=:security_id and user_profile_id=:user_profile_id  and (select master.sp_fin_year_from_date(inspection_date::date))=(select master.sp_fin_year_from_date(current_date))) as a
		group by fin_year,inspection_type";
		$res_status_wise_count = $this->prepare($status_wise_count,array(":security_id" => $security_id, ":user_profile_id" => $user_profile_id), 2);

		if (count($res_status_wise_count) > 0) {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$response_data['JSON_DATA'] = $res_status_wise_count;
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'NO_RECORD';
			$response_data['MESSAGE'] = 'NO_RECORD';
		}
		//echo (json_encode($response_data));		
		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
	}
    public function photo_count($data_content)
	{
		$header_data = array();
		$header_data['user_name'] = $data_content->user_data['user_name'];
		$response_data['STATUS'] = 'OK';
		$response_data['RESPONSE'] = 'OK';
		$response_data['COUNT'] = 4;

		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
	}
    public function fin_year($data_content)
	{
		/***********************************************************************************
		scheme_finyear_list
		 ************************************************************************************/

		$header_data = array();
		$header_data['user_name'] = $data_content->user_data['user_name'];
		$response_data = array();
		$req_params = array();

		$sql = "select fin_year from master.m_fin_year where del_flag is null order by fin_year desc limit 5";
		$res = $this->prepare($sql, array(), 2);

		if (count($res) > 0) {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$response_data['JSON_DATA'] = $res;
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'NO_RECORD';
			$response_data['MESSAGE'] = 'NO_RECORD';
		}

		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
	}
    public function other_work_category_list($data_content)
	{
		$header_data = array();
		$header_data['user_name'] = $data_content->user_data['user_name'];
		$response_data = array();

		$qry = "SELECT other_work_category_id,other_work_category_name from master.m_other_work_category where del_flag is null";
		$res = $this->prepare($qry, array(), 2);

		if (count($res) > 0) {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$response_data['JSON_DATA'] = $res;
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'NO_RECORD';
			$response_data['MESSAGE'] = 'NO_RECORD';
		}

		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
	}
    public function scheme_list_townpanchayat_wise($data_content)
	{
		/***********************************************************************************
		scheme_list_townpanchayat_wise
		 ************************************************************************************/
		$header_data = array();
		$header_data['user_name'] = $data_content->user_data['user_name'];
		$response_data = array();

		if(isset($data_content->dcode) && $data_content->dcode!="")
		{
			$dcode=$data_content->dcode;
		}
		else
		{
			$dcode=$data_content->user_data['dcode'];
		}

		if(isset($data_content->lbcode) && $data_content->lbcode!="")
		{
			$lbcode=$data_content->lbcode;
		}
		else
		{
			$lbcode=$data_content->user_data['lbcode'];
		}

		$fin_year_array = $data_content->fin_year;
		$fin_year_array = array_combine(
			array_map(function ($i) {
				return ':fin_year' . $i;
			}, array_keys($fin_year_array)),
			$fin_year_array
		);
		$fin_year_cond = " and fin_year in (" . implode(',', array_keys($fin_year_array)) . ")";

		$sql = "select scheme_seq_id as scheme_id,scheme_name from master.m_scheme_district_link where (scheme_seq_id,scheme_group_id,dcode) in (select scheme_id,scheme_group_id,dcode from works.t_works where dcode=:dcode and lbcode=:lbcode $fin_year_cond) order by scheme_name";
		$res = $this->prepare($sql, array_merge(array(":dcode" => $dcode, ":lbcode" => $lbcode), $fin_year_array), 2);

		if (count($res) > 0) {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$response_data['JSON_DATA'] = $res;
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'NO_RECORD';
			$response_data['MESSAGE'] = 'NO_RECORD';
		}
		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
	}

    public function scheme_list_tp_wise($decrypted_data_json)
	{	
		
		$response_data=array();
		$req_params=array();
		

		if(isset($decrypted_data_json->dcode) && $decrypted_data_json->dcode!="")
		{
			$dcode=$decrypted_data_json->dcode;
		}
		else
		{
			$dcode=$decrypted_data_json->user_data['dcode'];
		}

		if(isset($decrypted_data_json->lbcode) && $decrypted_data_json->lbcode!="")
		{
			$lbcode=$decrypted_data_json->lbcode;
		}
		else
		{
			$lbcode=$decrypted_data_json->user_data['lbcode'];
		}
		$fin_year_arr=$decrypted_data_json->fin_year;

		$fin_year_cond=" and fin_year in ('".implode("','", $fin_year_arr)."')";		

	
		if(count(array_intersect(array_keys($_POST),$req_params))!=count($req_params))
		{
			echo '{"STATUS":"FAIL","RESPONSE":"INVALID_REQUEST"}';
			exit;
		}		
		$sql="select count(work_id) as works_count, a.scheme_id,d.scheme_name_en as scheme_name from
				(select scheme_id,scheme_group_id,dcode,agency_group_id,work_id from works.t_works where  dcode=:dcode and lbcode=:lbcode $fin_year_cond)a
				left join
				(select scheme_id,scheme_group_id,dcode  from master.m_scheme_district_link ) b
				on a.scheme_id=b.scheme_id and a.scheme_group_id=b.scheme_group_id and a.dcode=b.dcode
				left join 
				(select agency_group_id from master.m_agency_group where del_flag is null)c
				on a.agency_group_id=c.agency_group_id
				left join 
				(select scheme_seq_id,scheme_name_en from master.m_scheme where del_flag is null )d
				on b.scheme_id=d.scheme_seq_id group by a.scheme_id,d.scheme_name_en";
		$res = $this->prepare($sql,array(":dcode"=>$dcode,":lbcode"=>$lbcode),2); 
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
		$header_data['user_name'] = $decrypted_data_json->user_data['user_name'];
		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
		
	}
	public function scheme_list_district_finyear_wise($decrypted_data_json)
	{	
		/***********************************************************************************
		scheme_list_district_wise
		************************************************************************************/
		
		$response_data=array();
		$req_params=array();
		
		if($decrypted_data_json->user_data['dcode']=="")
		{
			if(is_array($decrypted_data_json->dcode))
			$dcode=" dcode in (".implode(',',$decrypted_data_json->dcode).") AND "; 
			else
			$dcode=" dcode=".$decrypted_data_json->dcode." AND"; 
		}
		else
		{
			$dcode=" dcode=".$decrypted_data_json->user_data['dcode']." AND";  
		}
		
		$finyear=$decrypted_data_json->finyear;
		if(isset($decrypted_data_json->finyear))
		{
		 $where = " a.fin_year in ('".implode("','",$finyear)."') and";
		}
		else
		{
			
			$where = "";
		}
		if(count(array_intersect(array_keys($_POST),$req_params))!=count($req_params))
		{
			echo '{"STATUS":"FAIL","RESPONSE":"INVALID_REQUEST"}';
			exit;
		}
		// $sql="select distinct scheme_seq_id,scheme_name,fin_year,thittam_app_additional_details_required from m_finyear_scheme_link as a,m_scheme_district_link as b where  a.scheme_group_id=b.scheme_group_id and a.scheme_id=b.scheme_seq_id and  $where $dcode  (b.disp_in_entry_screen='Y' or b.scheme_seq_id=6) and a.thittam_app='Y' order by b.scheme_name";

		$sql="select distinct b.distict_scheme_id,c.scheme_name,fin_year,thittam_app_additional_details_required,thittam_app from(
			(select scheme_group_id,scheme_id,fin_year,thittam_app_additional_details_required,thittam_app from master.m_finyear_scheme_link where del_flag is null and thittam_app='Y')
			a
			left join
			(select scheme_group_id,scheme_id as distict_scheme_id,disp_in_entry_screen,scheme_id from master.m_scheme_district_link where del_flag is null )b
			on a.scheme_group_id=b.scheme_group_id and a.scheme_id=b.distict_scheme_id
			left join 
			(select scheme_seq_id,scheme_name_en as scheme_name from master.m_scheme)c
			on b.distict_scheme_id=c.scheme_seq_id $where $dcode
			)";
			$res = $this->prepare($sql,array(),2); 
		//$res = $this->obj->selfn($sql, $this->db); 
	
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
		$header_data['user_name'] = $decrypted_data_json->user_data['user_name'];
		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
		//echo (json_encode($response_data));		
		//return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
	}
	public function work_type_stage_link($data_content)
	{
		/***********************************************************************************
		work_type_stage_link
		 ************************************************************************************/
		$header_data = array();
		$header_data['user_name'] = $data_content->user_data['user_name'];
		$response_data = array();
		$req_params = array();

		$sql = "select a.work_group_id as work_group_id,a.work_id as work_type_id,a.work_stage_order,a.work_stage_id,a.work_stage_name,x.thittam_app_additional_details_required,1 as min_photos,1 as max_photos 
		from master.m_work_stage_link as a 
		left join 
		master.m_stage as b on a.work_stage_id=b.stage_id 
		left join 
		(select distinct work_group_id,work_id,thittam_app_additional_details_required from  master.m_scheme_worktype_link where thittam_app_additional_details_required='Y') as x on a.work_group_id=x.work_group_id and a.work_id=x.work_id";
		$res = $this->prepare($sql, array(), 2);

		if (count($res) > 0) {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$response_data['JSON_DATA'] = $res;
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'NO_RECORD';
			$response_data['MESSAGE'] = 'NO_RECORD';
		}
		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
	}
	public function get_inspection_work_details($decrypted_data_json)
	{
		
		$header_data = array();
		$header_data['user_name'] = $decrypted_data_json->user_data['user_name'];
		$response_data = array();
		$req_params = array();
		$inspection_work_details = $decrypted_data_json;
		if(isset($decrypted_data_json->inspection_work_details->dcode) && $decrypted_data_json->inspection_work_details->dcode!="")
		{
			$dcode=$decrypted_data_json->inspection_work_details->dcode;
		}
		else
		{
			$dcode=$decrypted_data_json->user_data['dcode'];
		}
		if(isset($decrypted_data_json->inspection_work_details->lbcode) && $decrypted_data_json->inspection_work_details->lbcode!="")
		{
			$lbcode=$decrypted_data_json->inspection_work_details->lbcode;
		}
		else
		{
			$lbcode=$decrypted_data_json->user_data['lbcode'];
		}
		//$flag = 1;
		$flag=$decrypted_data_json->inspection_work_details->flag;
		//print_r($flag);die;
		$fin_year_array=$decrypted_data_json->inspection_work_details->fin_year;
	
		
		// If fin_year_array is not empty, proceed with array_combine
		if (!empty($fin_year_array)) {
			
			$fin_year_array = array_combine(
				array_map(function ($i) {
					return ':fin_year' . $i;
				}, array_keys($fin_year_array)),
				$fin_year_array
			);
		
			// Construct the condition for SQL
			$fin_year_cond = " and fin_year in (" . implode(',', array_keys($fin_year_array)) . ")"; 
		} else {
			// Handle the case when fin_year_array is empty
			$fin_year_cond = ""; // or handle as appropriate for your logic
		}

		//print_r($fin_year_cond);die;
		if($flag == 1){

			$inspection_scheme_details="select a.scheme_id,d.scheme_name_en, count(1) as total_count from 
			(select * from works.t_works where dcode=:dcode and lbcode=:lbcode $fin_year_cond) as a  
			left join 
			(SELECT work_group_id as work_group ,work_type_id as work_type_id , min_asvalue as as_value_amt from works.t_works_high_value_project_specification where del_flag is null) as b  on b.work_group=a.work_group_id and b.work_type_id=a.work_type_id and b.as_value_amt < a.as_value
			
			LEFT JOIN
	(SELECT  work_id, max(ins_date::date) as upd_date,sum(case when file_url is not null and file_url<>'null' then 1 else 0 end) as no_of_file_found FROM works.t_scheme_work_physical_progress WHERE stage_id  not in (10,11)  and work_id in (select work_id from works.t_works where dcode=:dcode and lbcode=:lbcode $fin_year_cond)  and cd_prot_workid=0  $fin_year_cond GROUP BY work_id)dd
	ON a.work_id = dd.work_id :: NUMERIC
			left join
			(select scheme_seq_id,scheme_group_code,scheme_name_en from master.m_scheme) as d
			on a.scheme_id=d.scheme_seq_id and a.scheme_group_id=d.scheme_group_code group by a.scheme_id, d.scheme_name_en";
			$res=$this->prepare($inspection_scheme_details, array_merge(array(':dcode' => $dcode,':lbcode' => $lbcode), $fin_year_array),2);
			
			
			if (count($res) > 0) {
				$response_data['STATUS'] = 'OK';
				$response_data['RESPONSE'] = 'OK';
				$response_data['JSON_DATA'] = $res;
			} else {
				$response_data['STATUS'] = 'OK';
				$response_data['RESPONSE'] = 'NO_RECORD';
				$response_data['MESSAGE'] = 'NO_RECORD';
			}
		}
        else if($flag == 'all' || $flag == 2){
				
		$scheme_id_array = $decrypted_data_json->inspection_work_details->scheme_id;
		$scheme_id_array = array_combine(
			array_map(function ($i) {
				return ':scheme_id' . $i;
			}, array_keys($scheme_id_array)),
			$scheme_id_array
		);
		$scheme_id_cond = " and scheme_id in (" . implode(',', array_keys($scheme_id_array)) . ")";


		$inspection_work_details_list = "select a.dcode,a.lbcode,scheme_group_id,scheme_id,a.work_group_id,a.work_type_id as work_type_id,fin_year,a.work_id,work_name,as_value,ts_value,current_stage_of_work,case when b.work_group is not null then 'Y' else NULL end as is_high_value,stage_name,to_char(a.as_date::date,'DD-MM-YYYY') as as_date,
		to_char(a.ts_date::date,'DD-MM-YYYY') as ts_date,
		TO_CHAR(TO_DATE(a.agreement_date, 'YYYY-MM-DD'), 'DD-MM-YYYY') AS work_order_date,
		wtype.work_type_name as work_type_name,d.scheme_name_en,district_name_en,lbody_name_en,
		to_char(dd.upd_date,'DD-MM-YYYY') as upd_date
 		from (select * from works.t_works where dcode=:dcode and lbcode=:lbcode  $fin_year_cond  $scheme_id_cond) as a  left join (SELECT work_group_id as work_group ,work_type_id as work_type_id , min_asvalue as as_value_amt from works.t_works_high_value_project_specification where del_flag is null) as b  on b.work_group=a.work_group_id and b.work_type_id=a.work_type_id and b.as_value_amt < a.as_value
		LEFT JOIN
		(SELECT  work_id, max(ins_date::date) as upd_date,sum(case when file_url is not null and file_url<>'null' then 1 else 0 end) as no_of_file_found FROM works.t_scheme_work_physical_progress WHERE stage_id  not in (10,11)  and work_id in (select work_id from works.t_works where dcode=:dcode and lbcode=:lbcode $fin_year_cond  $scheme_id_cond)  and cd_prot_workid=0  $fin_year_cond  $scheme_id_cond GROUP BY work_id)dd
		ON a.work_id = dd.work_id :: NUMERIC
		left join
		(select stage_id,stage_name from master.m_stage) as c
		on a.current_stage_of_work=c.stage_id
		LEFT JOIN (SELECT work_type_id,work_name_en as work_type_name FROM master.m_work_type_name ) as wtype on a.work_type_id=wtype.work_type_id
		left join
		(select scheme_seq_id,scheme_group_code,scheme_name_en from master.m_scheme) as d
		on a.scheme_id=d.scheme_seq_id and a.scheme_group_id=d.scheme_group_code
		left join 
		(select dcode,district_name_en from master.m_district) as e on a.dcode=e.dcode
		left join 
		(SELECT dcode,lbcode,lbody_name_en FROM master.m_localbodies ) as f on a.dcode=f.dcode and a.lbcode=f.lbcode";
		$res = $this->prepare($inspection_work_details_list, array_merge(array(':dcode' => $dcode, ':lbcode' => $lbcode), $fin_year_array, $scheme_id_array), 2);
		
		if (count($res) > 0) {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$response_data['JSON_DATA'] = $res;
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'NO_RECORD';
			$response_data['MESSAGE'] = 'NO_RECORD';
		}
	}	
		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
	}
	public function work_progress_detail($data_content)
	{
		$header_data = array();
		$header_data['user_name'] = $data_content->user_data['user_name'];
		$work_id = $data_content->work_id;

		$sql_days_cacl = "SELECT 
    a.work_phy_prog_seq_id,
    a.work_id,
    TO_CHAR(a.ins_date, 'DD-MM-YYYY') AS date,
    a.stage_id AS current_stage_of_work,
    b.stage_name, 
    COALESCE(
        EXTRACT(DAY FROM (a.ins_date - LEAD(a.ins_date) OVER (PARTITION BY a.work_id ORDER BY a.ins_date DESC))) , 0
    ) AS days
		FROM 
			works.t_scheme_work_physical_progress AS a
		LEFT JOIN 
			master.m_stage AS b 
			ON a.stage_id = b.stage_id 
		WHERE 
			a.cd_prot_workid = 0 
			AND a.work_id = :work_id
		ORDER BY 
			a.work_phy_prog_seq_id DESC;
		";
		$res = $this->prepare($sql_days_cacl, array(':work_id' => $work_id), 2);
		// $res = $this->obj->selfn($sql_days_cacl, $this->db); 


		if (count($res) > 0) {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$response_data['JSON_DATA'] = $res;
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'NO_RECORD';
			$response_data['MESSAGE'] = 'NO_RECORD';
		}

		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
	}
	public function date_wise_inspection_details_view($data_content)
	{

		$header_data = array();
		$header_data['user_name'] = $data_content->user_data['user_name'];
		$response_data = array();
		$req_params = array();

		//$levels = $data_content->user_data['levels'];
		$security_id = $data_content->user_data['security_id'];
		$profile_id = $data_content->user_data['user_profile_id'];
		$work_id = isset($data_content->work_id) ? $data_content->work_id : '';
		$from_date = isset($data_content->from_date) ? implode('-', array_reverse(explode('-', $data_content->from_date))) : '';
		$to_date = isset($data_content->to_date) ? implode('-', array_reverse(explode('-', $data_content->to_date))) : '';


		$type = $data_content->type;
		$cond = "";
		

		$cond1 = "";
		$cond_arr1 = array();
		if ($type == 1) {
			$cond .= " and work_id=:work_id";
			$cond_arr[':work_id'] = $work_id;
			$cond1 = "where work_id=:work_id";
			$cond_arr1[':work_id'] = $work_id;
		} else if ($type == 2) {
			$cond .= " and inspection_date::Date between :from_date and :to_date";
			$cond_arr[':from_date'] = $from_date;
			$cond_arr[':to_date'] = $to_date;
		}

		$final_arr = array();
		

		$inspection_details = "select dcode,lbcode,a.inspection_id,to_char(inspection_date,'dd-mm-yyyy') as inspection_date,to_char(ins_date,'dd-mm-yyyy HH24:MI:SS') as ins_date,a.status_id,b.status,a.description,a.work_id,c.work_name,rural_urban,action_taken_id,case when action_taken_id is not null then 'Completed' else 'Pending' end as action_taken_status from 
		(select work_id,dcode,lbcode,inspection_id,inspection_date,status_id,description,rural_urban,ins_date from works.t_work_inspection_details where del_flag is null and security_id=:security_id and user_profile_id=:profile_id $cond) as a 
		left join 
		(select status_id,status from master.m_inspection_status) as b 
		on a.status_id=b.status_id 
		left join 
		(select work_id,work_name from works.t_works $cond1) as c on a.work_id=c.work_id
left join
(select inspection_id,action_taken_id,description from works.t_work_inspection_action_taken_details where del_flag is null) d
on d.inspection_id=a.inspection_id
		order by a.work_id,inspection_date asc";
		$res_inspection_details = $this->prepare($inspection_details, array_merge(array(":security_id" => $security_id, ":profile_id" => $profile_id), $cond_arr, $cond_arr1), 2);
		$final_arr['inspection_details'] = $res_inspection_details;

		$status_wise_count = "select sum(case when status_id=1 then 1 else 0 end) as satisfied,sum(case when status_id=2 then 1 else 0 end) as unsatisfied,sum(case when status_id=3 then 1 else 0 end) as need_improvement from works.t_work_inspection_details where del_flag is null and security_id=:security_id and user_profile_id=:profile_id $cond";
		$res_status_wise_count = $this->prepare($status_wise_count, array_merge(array(":security_id" => $security_id, ":profile_id" => $profile_id), $cond_arr, $cond_arr1), 2);

		$final_arr['status_wise_count'] = $res_status_wise_count;
		if (count($res_inspection_details) > 0) {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$response_data['JSON_DATA'] = $final_arr;
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'NO_RECORD';
			$response_data['MESSAGE'] = 'NO_RECORD';
		}
		//echo (json_encode($response_data));		
		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
	}
	public function work_inspection_details_save($data_content)
	{
		$header_data = array();
		$header_data['user_name'] = $data_content->user_data['user_name'];
		$security_id = $data_content->user_data['security_id'];
		$profile_id = $data_content->user_data['user_profile_id'];
		$statecode = $data_content->user_data['state_code'] ?? 33;
		//$inspection_work_details = $data_content;
		$inspection_work_details = $data_content->inspection_work_details;

		if(isset($inspection_work_details->dcode) && $inspection_work_details->dcode!="")
		{
			$dcode=$inspection_work_details->dcode;
		}
		else
		{
			$dcode=$data_content->user_data['dcode'];
		}
		if(isset($inspection_work_details->lbcode) && $inspection_work_details->lbcode!="")
		{
			$lbcode=$inspection_work_details->lbcode;
		}
		else
		{
			$lbcode=$data_content->user_data['lbcode'];
		}
		$this->beginTransaction();
		$response_data = array();
		$user_name = $data_content->user_data['user_name'];
		$ip_address = $this->getIpAddress();

		$error_check = array();

		foreach ($inspection_work_details as $key => $val) {
			$work_id = $val->work_id;
			$status_id = $val->status_id ?? '';
			$description = $val->description;
			$image_details = $val->image_details;
			$inspection_edit_id = isset($val->inspection_id) && $val->inspection_id != '' ? $val->inspection_id : 0;
			//print_r($inspection_edit_id);die;
			$work_group_id = $val->work_group_id ?? '';
			$work_type_id = $val->work_type_id ?? '';
			$work_stage_code = $val->work_stage_code ?? '';
//print_r($status_id);die;
			$cond = "";
			$cond_arr = array();
			 if ($inspection_edit_id != 0) {
				$cond = "and inspection_id=:inspection_id and now() between ins_date and ins_date + INTERVAL '48 hours' ";
				$cond_arr[':inspection_id'] = $inspection_edit_id;
			} else {
				$cond = "and inspection_date=current_date";
		 } 
			$sel_inspection = "select inspection_id from works.t_work_inspection_details where del_flag is null  and dcode=:dcode and lbcode=:lbcode and security_id=:security_id and user_profile_id=:profile_id and work_id=:work_id $cond";
			$check_inspection = $this->prepare($sel_inspection, array_merge(array(
				':dcode' => $dcode,
				':security_id' => $security_id,
				':profile_id' => $profile_id,
				':work_id' => $work_id,
				':lbcode' => $lbcode
			), $cond_arr), 4);

			if ($inspection_edit_id != 0) {
				if (!isset($check_inspection['inspection_id']) || $check_inspection['inspection_id'] == '') {
					$error_check[] = 1;
					$this->rollBack();
					$response_data['STATUS'] = 'OK';
					$response_data['RESPONSE'] = 'FAIL';
					$response_data['MESSAGE'] = "This Inspection details can't be edited since it was captured 48 hours back.";
					$header_data['response_data'] = $response_data;
					$this->CreateHeader($header_data);
				}
			}

			//$current_date_inspection_id = $check_inspection['inspection_id'];
			$current_date_inspection_id = (isset($check_inspection['inspection_id']) && $check_inspection['inspection_id'] !== 0) ? 
                               $check_inspection['inspection_id'] : 
                               0;
			$sel_profile = "select user_first_name,gender,mobile_no,dcode,lbcode,designation_id,email_address,user_address,role_id from 
			(select user_first_name,gender,mobile_no,designation_id,email_address,user_address,role_id,user_profile_id from security.t_user_profile where user_profile_id=:profile_id and del_flag is null)a 
			left join
			(select dcode,lbcode,user_profile_id from security.t_users where user_profile_id=:profile_id and security_id=:security_id and del_flag is null)b
			on a.user_profile_id=b.user_profile_id";
			$res_profile = $this->prepare($sel_profile, array(":profile_id" => $profile_id, ":security_id" => $security_id), 4);
//print_r($res_profile);
			$name = $res_profile['user_first_name'];
			$gender = $res_profile['gender'];
			$mobile = $res_profile['mobile_no'];
			$level = 1;
			$desig_code = $res_profile['designation_id'];
			$email = $res_profile['email_address'];
			$office_address = $res_profile['user_address'];
			$role_code = $res_profile['role_id'];
			$rural_urban='T';


			if ($current_date_inspection_id == 0) {
				$insert = "INSERT INTO works.t_work_inspection_details(statecode, dcode, lbcode, security_id, user_profile_id, work_id, inspection_date, status_id, description, ins_username, ins_ipaddress, ins_date, name,gender,mobile,level,designation_id,email,office_address,role_id,work_group_id,work_type_id,work_stage_id,rural_urban)
				VALUES (:statecode, :dcode, :lbcode,:security_id, :profile_id, :work_id, now(), :status_id, :description, :ins_username, :ins_ipaddress, now(), :name,:gender,:mobile,:level,
				:desig_code,:email,:office_address,:role_code,:work_group_id,:work_type_id,:work_stage_code,:rural_urban) returning inspection_id;";
				$flag1 = $this->prepare($insert, array(

					':statecode' => $statecode,
					':dcode' => $dcode,
					':lbcode' => $lbcode,
					':security_id' => $security_id,
					':profile_id' => $profile_id,
					':work_id' => $work_id,
					':status_id' => $status_id,
					':description' => $description,
					':ins_username' => $user_name,
					':ins_ipaddress' => $ip_address,
					':name' => $name,
					':gender' => $gender,
					':mobile' => $mobile,
					':level' => $level,
					':desig_code' => $desig_code,
					':email' => $email,
					':office_address' => $office_address,
					':role_code' => $role_code,
					':rural_urban' => $rural_urban,
					':work_group_id' => $work_group_id,
					':work_type_id' => $work_type_id,
					':work_stage_code' => $work_stage_code
				), 4);
				//var_dump($flag1);exit;
			} else {

				$status_cond = "";
				$status_cond_arr = array();
				if ($inspection_edit_id == 0) {
					$status_cond = "status_id=:status_id,";
					$status_cond_arr[':status_id'] = $status_id;
				}

				$update = "update works.t_work_inspection_details set $status_cond description=:description, upd_username=:upd_username,upd_ipaddress=:upd_ipaddress,upd_date=now() where dcode=:dcode and lbcode=:lbcode and security_id=:security_id and user_profile_id=:profile_id and work_id=:work_id and inspection_id=:inspection_id returning inspection_id;";
				$flag1 = $this->prepare($update, array_merge(array(
					':dcode' => $dcode,
					':lbcode' => $lbcode,
					':security_id' => $security_id,
					':profile_id' => $profile_id,
					':work_id' => $work_id,
					':description' => $description,
					':upd_username' => $user_name,
					':upd_ipaddress' => $ip_address,
					':inspection_id' => $current_date_inspection_id
				), $status_cond_arr), 4);
			}

			if ($this->prepareStatus($flag1) === false) {
				$error_check[] = 1;
				$this->rollBack();
				$response_data['STATUS'] = 'OK';
				$response_data['RESPONSE'] = 'FAIL';
				$response_data['MESSAGE'] = "Failed to Save for Inspection Details";
				$header_data['response_data'] = $response_data;
				$this->CreateHeader($header_data);
			}

			$inspection_max_id = $flag1['inspection_id'];

			foreach ($image_details as $img_key => $img_val) {
				$image_file_name = "";
				$image_storage_path = "";
				$latitude = $img_val->latitude;
				$longitude = $img_val->longitude;
				$serial_no = $img_val->serial_no;
				$image_description = ($img_val->image_description != '') ? $img_val->image_description : NULL;
				$image = $img_val->image;

				$sel_inspection_img = "select count(1) as cnt from works.t_work_inspection_details_images where del_flag is null  and dcode=:dcode and lbcode=:lbcode and work_id=:work_id and inspection_id=:inspection_id and serial_no=:serial_no";
				$sel_inspection_img_res = $this->prepare($sel_inspection_img, array(

					
					':dcode' => $dcode,
					':lbcode' => $lbcode,
					':work_id' => $work_id,
					':inspection_id' => $current_date_inspection_id,
					':serial_no' => $serial_no
				), 4);
//print_r($sel_inspection_img_res);die;
				if ($sel_inspection_img_res['cnt'] == 0 || $inspection_edit_id == 0) {

					$del_inspection_img = "delete from works.t_work_inspection_details_images where del_flag is null  and dcode=:dcode and lbcode=:lbcode and work_id=:work_id and inspection_id=:inspection_id and serial_no=:serial_no";
					$del_inspection_img_res = $this->prepare($del_inspection_img, array(

						
						':dcode' => $dcode,
						':lbcode' => $lbcode,
						':work_id' => $work_id,
						':inspection_id' => $current_date_inspection_id,
						':serial_no' => $serial_no
					), 4);


					$path_to_save='';
					$Base_path = $this->getStoragePath() . "Document/work/work_inspection_photos";
					$Temp_Base_path = $Base_path . '/' . $dcode . '/' . $lbcode . '/';	
					$path_to_save=$Temp_Base_path;

					if (!file_exists($path_to_save)) 
					{
					mkdir($path_to_save,0777,true);	
					}	

						
					$file = "inspection_work_stage_" . $work_id . '_' . $work_stage_code . $serial_no . '_' . date("Y_m_d_H_i_s") . '.jpg';


						$dirnam = $path_to_save.'/'.$file; 	
					

					$img_data2 = base64_decode($image); 	
					$img_data3 = imagecreatefromstring($img_data2);	

					if(!$img_data3==false)
					{	

					imagejpeg($img_data3, $dirnam,100);	
					} 
					else
					{
						$this->rollback();
					$response_data['STATUS']='OK'; 
					$response_data['RESPONSE']='FAIL';
					$response_data['MESSAGE']='Failed to Image Upload';
					$header_data['user_name'] = $data_content->user_data['user_name'];
					$header_data['response_data'] = $response_data;
					$this->CreateHeader($header_data);
					exit;
					}

					$insert = "INSERT INTO works.t_work_inspection_details_images(statecode, dcode, lbcode, work_id, inspection_id, file_name, latitude, longitude, serial_no, image_description, ins_username, ins_ipaddress, ins_date,rural_urban)
				VALUES (:statecode, :dcode, :lbcode, :work_id, :inspection_id, :file_name, :latitude, :longitude, :serial_no, :image_description, :ins_username, :ins_ipaddress, now(), :rural_urban) returning inspection_details_image_id;";
					$flag2 = $this->prepare($insert, array(

						':statecode' => $statecode,
						':dcode' => $dcode,
						':lbcode' => $lbcode,
						':work_id' => $work_id,
						':inspection_id' => $inspection_max_id,
						':file_name' => $file,
						':latitude' => $latitude,
						':longitude' => $longitude,
						':serial_no' => $serial_no,
						':image_description' => $image_description,
						':ins_username' => $user_name,
						':ins_ipaddress' => $ip_address,
						':rural_urban' => $rural_urban
					), 4);
					//var_dump($flag2);exit;
				} else if ($inspection_edit_id > 0) {
					$update = "update works.t_work_inspection_details_images set image_description=:image_description, upd_username=:upd_username,upd_ipaddress=:upd_ipaddress,upd_date=now() where  dcode=:dcode and lbcode=:lbcode and work_id=:work_id and inspection_id=:inspection_id and serial_no=:serial_no returning inspection_details_image_id;";
					$flag2 = $this->prepare($update, array(
						
						':dcode' => $dcode,
						':lbcode' => $lbcode,
						':work_id' => $work_id,
						':inspection_id' => $current_date_inspection_id,
						':serial_no' => $serial_no,
						':image_description' => $image_description,
						':upd_username' => $user_name,
						':upd_ipaddress' => $ip_address
					), 4);
				}

				if ($this->prepareStatus($flag2) === false) {
					$error_check[] = 1;
					$this->rollBack();
					$response_data['STATUS'] = 'OK';
					$response_data['RESPONSE'] = 'FAIL';
					$response_data['MESSAGE'] = "Failed to Save for Work Inspection photos";
					$header_data['response_data'] = $response_data;
					$this->CreateHeader($header_data);
				}
			}
			//}
		}

		if (count($error_check) > 0) {

			$this->rollBack();
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'FAIL';
			$response_data['MESSAGE'] = "Failed to Save for Work Inspection Details";
			$header_data['response_data'] = $response_data;
			$this->CreateHeader($header_data);
		} else {
			$this->commit();
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$response_data['MESSAGE'] = "Data Saved Successfully";
		}
		//echo (json_encode($response_data));		
		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
	}
	public function date_wise_inspection_action_taken_details_view($data_content)
	{

		$header_data = array();
		$header_data['user_name'] = $data_content->user_data['user_name'];
		$response_data = array();
		$security_id = $data_content->user_data['security_id'];
		$profile_id = $data_content->user_data['user_profile_id'];
		
		
		$type = $data_content->type;


		$cond = "";
		$cond_arr = array();
		
		$cond1 = "";
		$cond_arr1 = array();
		if ($type == 1) {
			$work_id = $data_content->work_id;
			$cond .= " and work_id=:work_id";
			$cond_arr[':work_id'] = $work_id;
			$cond1 = "where work_id=:work_id";
			$cond_arr1[':work_id'] = $work_id;
		} else if ($type == 2) {
			$from_date = implode('-', array_reverse(explode('-', $data_content->from_date)));
			$to_date = implode('-', array_reverse(explode('-', $data_content->to_date)));

			$cond .= " and inspection_date::Date between :from_date and :to_date";
			$cond_arr[':from_date'] = $from_date;
			$cond_arr[':to_date'] = $to_date;
		}
		
		$final_arr = array();
		$inspection_action_taken_details = "select dcode,lbcode,inspection_id,action_taken_id,to_char(inspection_date,'dd-mm-yyyy') as action_taken_date,to_char(ins_date,'dd-mm-yyyy HH24:MI:SS') as ins_date,description,a.work_id,c.work_name from 
		(select work_id,dcode,lbcode,inspection_id,action_taken_id,inspection_date,description,ins_date from works.t_work_inspection_action_taken_details where del_flag is null and security_id=:security_id and user_profile_id=:profile_id  $cond) as a
		left join 
		(select work_id,work_name from works.t_works $cond1) as c on a.work_id=c.work_id 
		order by a.work_id,inspection_date asc";
		$res_inspection_action_taken_details = $this->prepare($inspection_action_taken_details, array_merge(array(":security_id" => $security_id, ":profile_id" => $profile_id), $cond_arr, $cond_arr1), 2);
		$final_arr['inspection_action_taken_details'] = $res_inspection_action_taken_details;

		if (count($res_inspection_action_taken_details) > 0) {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$response_data['JSON_DATA'] = $final_arr;
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'NO_RECORD';
			$response_data['MESSAGE'] = 'NO_RECORD';
		}
		//echo (json_encode($response_data));		
		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
	}
	public function work_id_wise_inspection_details_view($data_content)
	{

		$header_data = array();
		$header_data['user_name'] = $data_content->user_data['user_name'];
		$response_data = array();
		$security_id = $data_content->user_data['security_id'];
		$profile_id = $data_content->user_data['user_profile_id'];
		$work_id = $data_content->work_id;
		$inspection_id = $data_content->inspection_id;
		$rural_urban = 'T';

		
		

		$inspection_details = "select a.work_id,dcode,lbcode,inspection_id,to_char(inspection_date,'dd-mm-yyyy') as inspection_date,a.status_id,b.status,description,c.work_name,a.work_stage_id,d.work_stage_name,a.rural_urban from works.t_work_inspection_details as a left join master.m_inspection_status as b on a.status_id=b.status_id left join (select work_id,work_name from works.t_works where work_id=:work_id) as c on a.work_id=c.work_id left join master.m_work_stage_link as d on a.work_group_id=d.work_group_id and a.work_type_id=d.work_id and a.work_stage_id=d.work_stage_id where a.del_flag is null and a.work_id=:work_id and a.security_id=:security_id and a.user_profile_id=:profile_id  and a.inspection_id=:inspection_id order by a.work_id,inspection_date asc
";
		$res_inspection_details = $this->prepare($inspection_details, array(":work_id" => $work_id, ":security_id" => $security_id, ":profile_id" => $profile_id, ":inspection_id" => $inspection_id), 2);

		foreach ($res_inspection_details as $res_inspection_details_key => $res_inspection_details_val) {

			$inspection_id = $res_inspection_details_val['inspection_id'];
			$inspection_details_photos = "select file_name as image,image_description,serial_no from works.t_work_inspection_details_images where del_flag is null and inspection_id=:inspection_id";
			$res_inspection_details_photos = $this->prepare($inspection_details_photos, array(":inspection_id" => $inspection_id), 2);

			foreach ($res_inspection_details_photos as $res_inspection_details_photos_key => $res_inspection_details_photos_val) {
				//print_r($res_inspection_details_photos_val);die;
				$Base_path = $this->getStoragePath() . "Document/work/work_inspection_photos/";
				$Temp_Base_path = $Base_path  . $res_inspection_details_val['dcode'] . '/' . $res_inspection_details_val['lbcode'];
				$path_to_save_file=$Temp_Base_path."/".$res_inspection_details_photos_val['image'];
				//print_r($path_to_save_file);die;
				 $res_inspection_details_photos[$res_inspection_details_photos_key]['image'] = $this->getDataURI_RAW($path_to_save_file);
				 $res_inspection_details_photos[$res_inspection_details_photos_key]['image_description'] = $res_inspection_details_photos_val['image_description'];
			}
			$res_inspection_details[$res_inspection_details_key]['inspection_image'] = $res_inspection_details_photos;
		}

		if (count($res_inspection_details) > 0) {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$response_data['JSON_DATA'] = $res_inspection_details;
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'NO_RECORD';
			$response_data['MESSAGE'] = 'NO_RECORD';
		}
		//echo (json_encode($response_data));		
		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
	}
	public function getDataURI_RAW($imagePath) {
		//echo 	$finfo = new finfo(FILEINFO_MIME_TYPE);
		//echo	$type = $finfo->file($imagePath);
			return base64_encode(file_get_contents($imagePath));
		}
	public function get_inspection_details_for_atr($data_content)
	{
		$header_data = array();
		$header_data['user_name'] = $data_content->user_data['user_name'];
		$response_data = array();
		$from_date = implode('-', array_reverse(explode('-', $data_content->from_date)));
		$to_date = implode('-', array_reverse(explode('-', $data_content->to_date)));

		

		if(isset($data_content->dcode) && $data_content->dcode!="")
			{
				$dcode=$data_content->dcode;
			}
			else
			{
				$dcode=$data_content->user_data['dcode'];
			}
			if(isset($data_content->lbcode) && $data_content->lbcode!="")
			{
				$lbcode=$data_content->lbcode;
			}
			else
			{
				$lbcode=$data_content->user_data['lbcode'];
			}

		$final_arr = array();
		$inspection_details = "select a.dcode,a.lbcode,inspection_id,to_char(inspection_date,'dd-mm-yyyy') as inspection_date,a.status_id,b.status,description,a.work_id,c.work_name,a.name,d.work_type_name,e.district_name_en,f.lbody_name_en,a.designation_id,a.rural_urban from
		(select work_id,dcode,lbcode,inspection_id,inspection_date,status_id,description,name,designation_id,rural_urban from works.t_work_inspection_details where del_flag is null and status_id!=1 and dcode=:dcode and lbcode=:lbcode and inspection_date::date between :from_date and :to_date and inspection_id not in (select inspection_id from works.t_work_inspection_action_taken_details where del_flag is null)) as a 
		left join 
		(select status_id,status from master.m_inspection_status) as b 
		on a.status_id=b.status_id 
		left join 
		(select work_id,work_name,work_group_id,work_type_id from works.t_works) as c on a.work_id=c.work_id
		left join 
		(SELECT work_id AS work_type_id,work_group_id,work_name as work_type_name FROM master.m_work_type ) as d on c.work_group_id=d.work_group_id and c.work_type_id=d.work_type_id
		left join 
		(select dcode,district_name_en from master.m_district) as e on a.dcode=e.dcode
		left join 
		(SELECT dcode,lbcode,lbody_name_en FROM master.m_localbodies ) as f on a.dcode=f.dcode and a.lbcode=f.lbcode
		order by a.work_id,inspection_date asc";
		$res_inspection_details = $this->prepare($inspection_details, array(":from_date" => $from_date, ":to_date" => $to_date, ":dcode" => $dcode, ":lbcode" => $lbcode), 2);

		$final_arr['inspection_details'] = $res_inspection_details;

		$status_wise_count = "select sum(case when status_id=1 then 1 else 0 end) as satisfied,sum(case when status_id=2 then 1 else 0 end) as unsatisfied,sum(case when status_id=3 then 1 else 0 end) as need_improvement from works.t_work_inspection_details where del_flag is null and dcode=:dcode and lbcode=:lbcode and inspection_date::date between :from_date and :to_date";
		$res_status_wise_count = $this->prepare($status_wise_count, array(":from_date" => $from_date, ":to_date" => $to_date, ":dcode" => $dcode, ":lbcode" => $lbcode), 2);

		$final_arr['status_wise_count'] = $res_status_wise_count;
		if (count($res_inspection_details) > 0) {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$response_data['JSON_DATA'] = $final_arr;
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'NO_RECORD';
			$response_data['MESSAGE'] = 'NO_RECORD';
		}		
		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
	}
	public function action_taken_details_save($data_content)
	{
		$security_id = $data_content->user_data['security_id'];
		$profile_id = $data_content->user_data['user_profile_id'];
		$inspection_work_details = $data_content->inspection_work_details;

		$this->beginTransaction();
		$header_data = array();
		$header_data['user_name'] = $data_content->user_data['user_name'];
		$response_data = array();
		$user_name = $data_content->user_data['user_name'];
		$ip_address = $this->getIpAddress();
		$statecode = $data_content->user_data['state_code'];
		if(isset($inspection_work_details->dcode) && $inspection_work_details->dcode!="")
		{
			$dcode=$inspection_work_details->dcode;
		}
		else
		{
			$dcode=$data_content->user_data['dcode'];
		}
		if(isset($inspection_work_details->lbcode) && $inspection_work_details->lbcode!="")
		{
			$lbcode=$inspection_work_details->lbcode;
		}
		else
		{
			$lbcode=$data_content->user_data['lbcode'];
		}
		$error_check = array();

		foreach ($inspection_work_details as $key => $val) {
			$work_id = $val->work_id;
			$inspection_id = $val->inspection_id;
			$description = $val->description;
			$image_details = $val->image_details;
			//$action_taken_edit_id = ($val->action_taken_id != '') ? $val->action_taken_id : 0;

			$action_taken_edit_id = isset($val->action_taken_id) && $val->action_taken_id != '' ? $val->action_taken_id : 0;

			$cond = "";
			$cond_arr = array();
			if ($action_taken_edit_id != 0) {
				$cond = "and action_taken_id=:action_taken_id and now() between ins_date and ins_date + INTERVAL '48 hours' ";
				$cond_arr[':action_taken_id'] = $action_taken_edit_id;
			} else {
				$cond = "and inspection_date=current_date";
			}
			$sel_inspection = "select action_taken_id from works.t_work_inspection_action_taken_details where del_flag is null  and statecode=:statecode and dcode=:dcode and lbcode=:lbcode and security_id=:security_id and user_profile_id=:profile_id and work_id=:work_id and inspection_id=:inspection_id $cond";
			$check_inspection = $this->prepare($sel_inspection, array_merge(array(
				':dcode' => $dcode,
				':security_id' => $security_id,
				':profile_id' => $profile_id,
				':work_id' => $work_id,
				':inspection_id' => $inspection_id,
				':lbcode' => $lbcode,
				':statecode' => $statecode
			), $cond_arr), 4);
//print_r($check_inspection);die;
			if ($action_taken_edit_id != 0) {
				if (isset($check_inspection['action_taken_id']) && $check_inspection['action_taken_id'] == '') {
					$error_check[] = 1;
					$this->rollBack();
					$response_data['STATUS'] = 'OK';
					$response_data['RESPONSE'] = 'FAIL';
					$response_data['MESSAGE'] = "This Action taken details can't be edited since it was captured 48 hours back.";
					$header_data['response_data'] = $response_data;
					$this->CreateHeader($header_data);
				}
			}
			$current_date_action_taken_id = isset($check_inspection['action_taken_id']) ? $check_inspection['action_taken_id'] : 0;;
			
			$sel_profile = "select user_first_name,gender,mobile_no,dcode,lbcode,designation_id,email_address,user_address,role_id from 
			(select user_first_name,gender,mobile_no,designation_id,email_address,user_address,role_id,user_profile_id from security.t_user_profile where user_profile_id=:profile_id and del_flag is null)a 
			left join
			(select dcode,lbcode,user_profile_id from security.t_users where user_profile_id=:profile_id and security_id=:security_id and del_flag is null)b
			on a.user_profile_id=b.user_profile_id";
			$res_profile = $this->prepare($sel_profile, array(":profile_id" => $profile_id, ":security_id" => $security_id), 4);
//print_r($res_profile);die;
			$name = $res_profile['user_first_name'];
			$gender = $res_profile['gender'];
			$mobile = $res_profile['mobile_no'];
			$level = 1;
			$desig_code = $res_profile['designation_id'];
			$email = $res_profile['email_address'];
			$office_address = $res_profile['user_address'];
			$role_code = $res_profile['role_id'];
			$rural_urban='T';



			$rural_urban='T';
			if ($current_date_action_taken_id == 0) {
				$insert = "INSERT INTO works.t_work_inspection_action_taken_details(statecode, dcode, lbcode, security_id, user_profile_id, work_id, inspection_date, description, ins_username, ins_ipaddress, ins_date, name,gender,mobile,level,designation_id,email,office_address,role_id,inspection_id,rural_urban)
				VALUES (:statecode,:dcode, :lbcode, :security_id, :profile_id, :work_id, now(), :description, :ins_username, :ins_ipaddress, now(), :name,:gender,:mobile,:level,
				:desig_code,:email,:office_address,:role_code,:inspection_id,:rural_urban) returning action_taken_id;";
				$flag1 = $this->prepare($insert, array(
					':statecode' => $statecode,
					':dcode' => $dcode,
					':lbcode' => $lbcode,
					':security_id' => $security_id,
					':profile_id' => $profile_id,
					':rural_urban' => $rural_urban,
					':work_id' => $work_id,
					':inspection_id' => $inspection_id,
					':description' => $description,
					':ins_username' => $user_name,
					':ins_ipaddress' => $ip_address,
					':name' => $name,
					':gender' => $gender,
					':mobile' => $mobile,
					':level' => $level,
					':desig_code' => $desig_code,
					':email' => $email,
					':office_address' => $office_address,
					':role_code' => $role_code
				), 4);
				//var_dump($flag1);exit;
			} else {

				$update = "update works.t_work_inspection_action_taken_details set description=:description, upd_username=:upd_username,upd_ipaddress=:upd_ipaddress,upd_date=now() where  dcode=:dcode and lbcode=:lbcode and security_id=:security_id and user_profile_id=:profile_id and work_id=:work_id and inspection_id=:inspection_id and action_taken_id=:action_taken_id returning action_taken_id;";
				$flag1 = $this->prepare($update, array(
					':dcode' => $dcode,
					':lbcode' => $lbcode,
					':security_id' => $security_id,
					':profile_id' => $profile_id,
					':inspection_id' => $inspection_id,
					':work_id' => $work_id,
					':description' => $description,
					':upd_username' => $user_name,
					':upd_ipaddress' => $ip_address,
					':action_taken_id' => $current_date_action_taken_id
				), 4);
				
			}
			//var_dump($flag1);exit;
			if ($this->prepareStatus($flag1) === false) {
				$error_check[] = 1;
				$this->rollBack();
				$response_data['STATUS'] = 'OK';
				$response_data['RESPONSE'] = 'FAIL';
				$response_data['MESSAGE'] = "Failed to Save for Action Taken Details";
				$header_data['response_data'] = $response_data;
				$this->CreateHeader($header_data);
			}

			$action_taken_max_id = $flag1['action_taken_id'];

			foreach ($image_details as $img_key => $img_val) {
				$image_file_name = "";
				$image_storage_path = "";
				$latitude = $img_val->latitude;
				$longitude = $img_val->longitude;
				$serial_no = $img_val->serial_no;
				$image_description = ($img_val->image_description != '') ? $img_val->image_description : NULL;
				$image = $img_val->image;
//print_r($image);die;
				$sel_action_taken_img = "select count(1) as cnt from works.t_work_inspection_action_taken_details_images where del_flag is null  and dcode=:dcode and lbcode=:lbcode and  work_id=:work_id and inspection_id=:inspection_id and action_taken_id=:action_taken_id and serial_no=:serial_no";
				$sel_action_taken_img_res = $this->prepare($sel_action_taken_img, array(
					':dcode' => $dcode,
					':lbcode' => $lbcode,
					':work_id' => $work_id,
					':inspection_id' => $inspection_id,
					':action_taken_id' => $current_date_action_taken_id,
					':serial_no' => $serial_no
				), 4);
//print_r($sel_action_taken_img_res);die;
				if ($sel_action_taken_img_res['cnt'] == 0 || $action_taken_edit_id == 0) {

					$del_action_taken_img = "delete from works.t_work_inspection_action_taken_details_images where del_flag is null  and dcode=:dcode and lbcode=:lbcode  and work_id=:work_id and inspection_id=:inspection_id and action_taken_id=:action_taken_id and serial_no=:serial_no";
					$del_action_taken_img_res = $this->prepare($del_action_taken_img, array(
						':dcode' => $dcode,
						':lbcode' => $lbcode,
						':work_id' => $work_id,
						':inspection_id' => $inspection_id,
						':action_taken_id' => $current_date_action_taken_id,
						':serial_no' => $serial_no
					), 4);
					$path_to_save='';
					$Base_path = $this->getStoragePath() . "Document/work/work_inspection_action_taken_photos";
					$Temp_Base_path = $Base_path . '/' . $dcode . '/' . $lbcode . '/'  ;	
					$path_to_save=$Temp_Base_path;

					if (!file_exists($path_to_save)) 
					{
					mkdir($path_to_save,0777,true);	
					}	

						
					$file = "work_inspection_action_" . $serial_no . '_' . $action_taken_max_id .'_'.date("Y_m_d_H_i_s"). '.jpg'; 


						$dirnam = $path_to_save.'/'.$file; 	
					

					$img_data2 = base64_decode($image); 	
					//print_r($img_data2);die;
					$img_data3 = imagecreatefromstring($img_data2);	
//print_r($img_data3);die;
					if(!$img_data3==false)
					{	

					imagejpeg($img_data3, $dirnam,100);	
					} 
					else
					{
						$this->rollback();
					$response_data['STATUS']='OK'; 
					$response_data['RESPONSE']='FAIL';
					$response_data['MESSAGE']='Failed to Image Upload';
					$header_data['user_name'] = $data_content->user_data['user_name'];
					$header_data['response_data'] = $response_data;
					$this->CreateHeader($header_data);
					exit;
					}


					$insert = "INSERT INTO works.t_work_inspection_action_taken_details_images(statecode, dcode, lbcode, work_id, action_taken_id, file_name, latitude, longitude, serial_no, image_description, ins_username, ins_ipaddress, ins_date,inspection_id, rural_urban)
			VALUES (:statecode,:dcode, :lbcode, :work_id, :action_taken_id, :file_name, :latitude, :longitude, :serial_no, :image_description, :ins_username, :ins_ipaddress, now(), :inspection_id, :rural_urban) returning atr_image_id;";
					$flag2 = $this->prepare($insert, array(
						':statecode' => $statecode,
						':dcode' => $dcode,
						':lbcode' => $lbcode,
						':work_id' => $work_id,
						':inspection_id' => $inspection_id,
						':action_taken_id' => $action_taken_max_id,
						':file_name' => $file,
						':latitude' => $latitude,
						':longitude' => $longitude,
						':serial_no' => $serial_no,
						':image_description' => $image_description,
						':ins_username' => $user_name,
						':ins_ipaddress' => $ip_address,
						':rural_urban' => $rural_urban
					), 4);
					//var_dump($flag2);exit;
				} else if ($action_taken_edit_id > 0) {
					$update = "update works.t_work_inspection_action_taken_details_images set image_description=:image_description, upd_username=:upd_username,upd_ipaddress=:upd_ipaddress,upd_date=now() where  dcode=:dcode and lbcode=:lbcode and work_id=:work_id and inspection_id=:inspection_id and action_taken_id=:action_taken_id and serial_no=:serial_no returning atr_image_id;";
					$flag2 = $this->prepare($update, array(
						':dcode' => $dcode,
						':lbcode' => $lbcode,
						':inspection_id' => $inspection_id,
						':work_id' => $work_id,
						':action_taken_id' => $current_date_action_taken_id,
						':serial_no' => $serial_no,
						':image_description' => $image_description,
						':upd_username' => $user_name,
						':upd_ipaddress' => $ip_address
					), 4);
				}
				//print_r($flag2);exit;
				if ($this->prepareStatus($flag2) === false) {
					$error_check[] = 1;
					$this->rollBack();
					$response_data['STATUS'] = 'OK';
					$response_data['RESPONSE'] = 'FAIL';
					$response_data['MESSAGE'] = "Failed to Save for Work Inspection photos";
					$header_data['response_data'] = $response_data;
					$this->CreateHeader($header_data);
				}
			}
			//}
		}
		
		if (count($error_check) > 0) {

			$this->rollBack();
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'FAIL';
			$response_data['MESSAGE'] = "Failed to Save for Work Action Taken Details";
			$header_data['response_data'] = $response_data;
			$this->CreateHeader($header_data);
		} else {
			$this->commit();
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$response_data['MESSAGE'] = "Data Saved Successfully";
		}
		//echo (json_encode($response_data));		
		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
	}
	public function work_id_wise_inspection_action_taken_details_view($data_content)
	{

		$header_data = array();
		$header_data['user_name'] = $data_content->user_data['user_name'];
		$response_data = array();
		$security_id = $data_content->user_data['security_id'];
		$profile_id = $data_content->user_data['user_profile_id'];
		$work_id = $data_content->work_id;
		$inspection_id = $data_content->inspection_id;
		$action_taken_id = $data_content->action_taken_id;

		$cond = "";
		$cond_arr = array();

		if ($action_taken_id != '') {
			$cond .= " and action_taken_id=:action_taken_id";
			$cond_arr[':action_taken_id'] = $action_taken_id;
		}
		$inspection_action_taken_details = "select workid as work_id,work_name,dcode,lbcode,inspection_id,action_taken_id,action_taken_date,description from
		(select work_id as workid,dcode,lbcode,inspection_id,action_taken_id,to_char(inspection_date,'dd-mm-yyyy') as action_taken_date,description from works.t_work_inspection_action_taken_details where del_flag is null and work_id=:work_id and security_id=:security_id and user_profile_id=:profile_id and inspection_id=:inspection_id  $cond)a left join (select work_id,work_name from works.t_works)b on a.workid=b.work_id order by workid,action_taken_date asc";
		$res_inspection_action_taken_details = $this->prepare($inspection_action_taken_details, array_merge(array(":work_id" => $work_id, ":security_id" => $security_id, ":profile_id" => $profile_id, ":inspection_id" => $inspection_id), $cond_arr), 2);

		foreach ($res_inspection_action_taken_details as $res_inspection_action_taken_details_key => $res_inspection_action_taken_details_val) {

			$inspection_id = $res_inspection_action_taken_details_val['inspection_id'];
			$action_taken_id = $res_inspection_action_taken_details_val['action_taken_id'];

			$inspection_action_taken_details_photos = "select file_name as image,image_description,serial_no from works.t_work_inspection_action_taken_details_images where del_flag is null and inspection_id=:inspection_id and action_taken_id=:action_taken_id";
			$res_inspection_action_taken_details_photos = $this->prepare($inspection_action_taken_details_photos, array(":inspection_id" => $inspection_id, ":action_taken_id" => $action_taken_id), 2);

			foreach ($res_inspection_action_taken_details_photos as $res_inspection_action_taken_details_photos_key => $res_inspection_action_taken_details_photos_val) {
//print_r($res_inspection_action_taken_details_photos[$res_inspection_action_taken_details_photos_key]['file_name']);die;
				$Base_path = $this->getStoragePath() . "Document/work/work_inspection_action_taken_photos/";
				$Temp_Base_path = $Base_path  . $res_inspection_action_taken_details_val['dcode'] . '/' . $res_inspection_action_taken_details_val['lbcode'];
				$path_to_save_file=$Temp_Base_path."/".$res_inspection_action_taken_details_photos_val['image'];
				
				 $res_inspection_action_taken_details_photos[$res_inspection_action_taken_details_photos_key]['image'] = $this->getDataURI_RAW($path_to_save_file);
				 $res_inspection_action_taken_details_photos[$res_inspection_action_taken_details_photos_key]['image_description'] = $res_inspection_action_taken_details_photos_val['image_description'];

			}
			$res_inspection_action_taken_details[$res_inspection_action_taken_details_key]['inspection_image'] = $res_inspection_action_taken_details_photos;
		}

		if (count($res_inspection_action_taken_details) > 0) {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$response_data['JSON_DATA'] = $res_inspection_action_taken_details;
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'NO_RECORD';
			$response_data['MESSAGE'] = 'NO_RECORD';
		}
		//echo (json_encode($response_data));		
		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
	}
	public function overall_report_for_atr($decrypted_data_json)
	{
		$header_data = array();
		$header_data['user_name'] = $decrypted_data_json->user_data['user_name'];
		$response_data = array();

		$from_date = implode('-', array_reverse(explode('-', $decrypted_data_json->from_date)));
		$to_date = implode('-', array_reverse(explode('-', $decrypted_data_json->to_date)));

		if(isset($decrypted_data_json->dcode) && $decrypted_data_json->dcode!="")
		{
			$dcode=$decrypted_data_json->dcode;
		}
		else
		{
			$dcode=$decrypted_data_json->user_data['dcode'];
		}
		
		if(isset($decrypted_data_json->lbcode) && $decrypted_data_json->lbcode!="")
		{
			$lbcode=$decrypted_data_json->lbcode;
		}
		else
		{
			$lbcode=$decrypted_data_json->user_data['lbcode'];
		}
		$status_id = $decrypted_data_json->status_id;


		$final_arr = array();
		$inspection_details = "select a.dcode,a.bcode,a.pvcode,inspection_id,to_char(inspection_date,'dd-mm-yyyy') as inspection_date,a.status_id,b.status,description,a.work_id,c.work_name,a.name,d.work_type_name,e.dname,f.bname,g.pvname,a.desig_code,h.desig_name, action_taken_id, 
		case when action_taken_id is not null then 'Y' else 'N' end as action_status,i.name as reported_by,a.lbcode,a.muncode,a.corcode,a.rural_urban,a.town_type,j.townpanchayat_name,k.municipality_name,l.corporation_name from (
		(select work_id,dcode,bcode,pvcode,inspection_id,inspection_date,status_id,description,name,desig_code,lbcode,muncode,corcode,rural_urban,town_type from osms.t_work_inspection_details where del_flag is null and dcode=:dcode and lbcode=:lbcode and status_id=:status_id and inspection_date between :from_date and :to_date) as a 
		left join 
		(select status_id,status from m_inspection_status) as b 
		on a.status_id=b.status_id 
		left join 
		(select work_id,work_name,work_group_id,mwork_id from t_works) as c on a.work_id=c.work_id
		left join 
		(SELECT work_id AS work_type_id,work_group_id,work_name as work_type_name FROM m_work_type ) as d on c.work_group_id=d.work_group_id and c.mwork_id=d.work_type_id
		left join 
		(select dcode,dname from m_district) as e on a.dcode=e.dcode
		left join 
		(SELECT dcode,bcode,bname FROM m_block ) as f on a.dcode=f.dcode and a.bcode=f.bcode
		left join 
		(SELECT dcode,bcode,pvcode,pvname FROM m_village ) as g on a.dcode=g.dcode and a.bcode=g.bcode and a.pvcode=g.pvcode
		left join
		(select desig_code,desig_name from public.m_mobile_desig)as h on a.desig_code=h.desig_code
		left join
		(select action_taken_id, inspection_id as atr_inspection_id,name from osms.t_work_inspection_action_taken_details)as i on a.inspection_id=i.atr_inspection_id
		left join
		(SELECT  dcode, townpanchayat_id, townpanchayat_name  FROM public.m_townpanchayats where delete_flag is null)as j on a.dcode=j.dcode and a.tpcode=j.townpanchayat_id
		left join
		(SELECT  dcode, municipality_id, municipality_name  FROM public.m_municipality where delete_flag is null)as k on a.dcode=k.dcode and a.muncode=k.municipality_id
		left join
		(SELECT  dcode, corporation_id, corporation_name  FROM public.m_corporation)as l on a.dcode=l.dcode and a.corcode=l.corporation_id
		) order by a.work_id,inspection_date asc";
		$res_inspection_details = $this->prepare($inspection_details, array_merge(array(":from_date" => $from_date, ":to_date" => $to_date,":dcode" => $dcode, ":lbcode" => $lbcode, ":status_id" => $status_id)), 2);
		$final_arr['inspection_details'] = $res_inspection_details;

		if (count($res_inspection_details) > 0) {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$response_data['JSON_DATA'] = $final_arr;
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'NO_RECORD';
			$response_data['MESSAGE'] = 'NO_RECORD';
		}
		//echo (json_encode($response_data));		
		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
	}
	public function get_action_taken_work_pdf($data_content)
	{
		$header_data = array();
		$header_data['user_name'] = $data_content->user_data['user_name'];
		$response_data = $this->action_taken_pdf_download($data_content);
		//echo base64_decode($response_data["JSON_DATA"]["pdf_string"]);
		//echo json_encode($response_data);
		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
	}
	public function get_pdf($data_content)
	{
		$header_data = array();
		$header_data['user_name'] = $data_content->user_data['user_name'];
		$response_data = $this->pdf_download($data_content);
		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
	}

}
$service_login = new service_login();

?>