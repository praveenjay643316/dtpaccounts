<?php 
error_reporting(E_ERROR);
require_once  __DIR__ . '/../../config/configPublic.php';
require_once __DIR__ . '/../../templates/HtmlHelper.php';
require_once __DIR__ . '/../../library/aes_mobile_app/AesCipher.php';
require_once __DIR__ . '/../JWTFunction.php';

class service_login   extends ConfigClass
{

	private AesCipher $Aes;
    public $app_key = NULL; 
	public $domain_name = "tndtp.tn.gov.in";
	use JWTFunction;
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

				//Testing mode
		//  $this->app_key=$c_l_response['KEY'];
		//  $header_data=array();
		// $header_data['response_data']=$data_receive_json;
		//  $header_data['user_name']=$c_l_response['USER_DATA']['user_name'];
		//  $this->CreateHeader($header_data); 

			$data_content=$data_receive_json->data_content;
			if ($c_l_response['STATUS'] == 'OK') {
				$this->app_key = $c_l_response['KEY'];
				$headers = apache_request_headers();
				if (!isset($headers['authorization']) || !preg_match('/Bearer\s(\S+)/', $headers['authorization'], $matches)) {
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
				echo json_encode($c_l_response);
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

        $sql="SELECT user_name,password,active,app_key,dcode,lbcode FROM security.t_users where user_name=:user_name"; 
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
                        return json_encode($response_data);
		}	 		
				
			
		}
		else
		{
			            $response_data['STATUS']='OK'; 
                        $response_data['STATUS_CODE']='400';
                        $response_data['RESPONSE']='LOGIN_FAILED';
                        $response_data['MESSAGE']='LOGIN FAILED';
                        $response_data['ERROR_ID']=2;
                        return json_encode($response_data);
		}
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
	public function rural_urban_work_list_dashboard($decrypted_data_json)
	{
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

		$scheme_id_arr=$decrypted_data_json->scheme_id;
		$sql="SELECT a.*, c.district_name_en as dname,to_char(d.upd_date,'DD-MM-YYYY') as upd_date, case when no_of_file_found>0 then 'Y' else 'N' end as image_available, f.lbcode as townpanchayat_id, f.lbody_name_en as townpanchayat_name from 
			( SELECT  work_id, max(ins_date::date) as upd_date,sum(case when file_url is not null and file_url<>'null' then 1 else 0 end) as no_of_file_found 
						FROM works.t_scheme_work_physical_progress WHERE stage_id  not in (10,11)  and work_id in (select work_id FROM master.view_workdetails WHERE   agency_group_id in (select agency_group_id from master.m_agency_group where del_flag is null)  and dcode=:dcode and lbcode=:lbcode and (yn_completed is null OR yn_completed='N') and current_stage_of_work not in (10,11)  and fin_year=:fin_year_arr and  scheme_id=:scheme_id_arr ) and cd_prot_workid=0 GROUP BY work_id)d
						left join
						(SELECT dcode,lbcode, work_id, scheme_group_id, scheme_id , scheme_group_name_en as schemegrp_name, scheme_name_en as scheme_name, fin_year, agency_name_en as agency_name , wrkgrpname, work_name_en as worktypname,work_group_id,work_type_id as work_type, work_id as mworkid, current_stage_of_work, as_value, amount_spent_sofar, stage_name,yn_completed,cd_prot_work_yn, work_name,to_char(as_date,'DD-MM-YYYY') as as_date, to_char(ts_date,'DD-MM-YYYY') as ts_date,lbcode as townpanchayat_code  
						FROM master.view_workdetails WHERE  
			agency_group_id in (select agency_group_id from master.m_agency_group where del_flag is null) and dcode=:dcode and lbcode=:lbcode and (yn_completed is null OR yn_completed='N') and current_stage_of_work not in (10,11)  and fin_year=:fin_year_arr and  scheme_id=:scheme_id_arr)a 
			ON a.work_id = d.work_id :: NUMERIC 
			LEFT JOIN 
			(SELECT * from master.m_district where dcode = :dcode)c 
			on a.dcode=c.dcode 
			LEFT JOIN 
			(SELECT * from master.m_localbodies where dcode = :dcode)f 
			on a.dcode=c.dcode and a.lbcode=f.lbcode
			WHERE ((yn_completed is null) OR (yn_completed='N')) and current_stage_of_work not in (10,11) AND a.dcode = :dcode and a.lbcode=:lbcode ORDER BY work_id ";
		$res = $this->prepare($sql, array(":dcode" => $dcode,":lbcode" => $lbcode,":fin_year_arr" => $fin_year_arr,":scheme_id_arr" => $scheme_id_arr), 2);
		if(count($res)>0)
		{
			$response_data['STATUS']='OK';
			$response_data['RESPONSE']='OK';
			$response_data['JSON_DATA']=array('main_work'=>$res); 			
		}
		else
		{
			$response_data['STATUS']='OK';
			$response_data['RESPONSE']='NO_RECORD';
			$response_data['MESSAGE']='NO_RECORD';
			
		}

		// var_dump($response_data); exit;
		$header_data['user_name'] = $decrypted_data_json->user_data['user_name'];
		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
	}
	public function rural_urban_work_list($decrypted_data_json)
	{	
	//print_r($decrypted_data_json);die;
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

		// Handling Array list Finyear
				
		$fin_year_arr=$decrypted_data_json->fin_year;

		$fin_year_cond=" and fin_year in ('".implode("','", $fin_year_arr)."')";

		$on_fin_year_cond=" and a.fin_year in ('".implode("','",$fin_year_arr)."')";


		// Handling Array list Scheme ID

		$scheme_id_arr=$decrypted_data_json->scheme_id;

		$scheme_id_cond=" and scheme_id in ('".implode("','", $scheme_id_arr)."')";

		$on_scheme_id_cond=" and a.scheme_id in ('".implode("','",$scheme_id_arr)."')";

		
		

		// var_dump($rural_urban); die;

		    //   $sql = "SELECT a.*, c.dname,c.bname,c.pvname, e.community_name, e.sex,e.gender_text, to_char(d.upd_date,'DD-MM-YYYY') as upd_date, case when no_of_file_found>0 then 'Y' else 'N' end as image_available, tp.townpanchayat_id, tp.townpanchayat_name, mun.municipality_id, mun.municipality_name, cor.corporation_id, cor.corporation_name from 
			// (SELECT dcode, bcode, pvcode, work_id, scheme_group_id, scheme_id , schemegrp_name, scheme_name, fin_year, agency_name, wrkgrpname, work_name,work_group_id,mwork_id as work_type, mwork_id as mworkid, current_stage_of_work, as_value, amount_spent_sofar, stage_name, hai_beneficiary_name, hai_beneficiary_fhname, worktypname, yn_completed,cd_prot_work_yn,is_group_work,group_works_completed,case when is_group_work='Y' then 'M' else null end as group_work_type,to_char(as_date,'DD-MM-YYYY') as as_date, to_char(ts_date,'DD-MM-YYYY') as ts_date, rural_urban, townpanchayat_code, municipality_code, corporation_code 
			// FROM view_workdetails WHERE  agency_group_id in (select agency_group_id from m_agency_group where rd_line_department=1) and rural_urban='$rural_urban' and dcode=$dcode $rural_urban_cond and (yn_completed is null OR yn_completed='N') and current_stage_of_work not in (10,11) $fin_year_cond $scheme_id_cond )a 
			// LEFT JOIN 
			// (SELECT * from viewldbvnames where dcode = $dcode)c on a.dcode=c.dcode and a.bcode=c.bcode and a.pvcode=c.pvcode 
			// LEFT JOIN
			// (SELECT dcode, townpanchayat_id, townpanchayat_name from public.m_townpanchayats where delete_flag is null and dcode=$dcode)tp
			// on a.dcode = tp.dcode and a.townpanchayat_code = tp.townpanchayat_id
			// LEFT JOIN
			// (SELECT  dcode, municipality_id, municipality_name  FROM public.m_municipality where delete_flag is null and dcode=$dcode)mun
			// on a.dcode = mun.dcode and a.municipality_code = mun.municipality_id
			// LEFT JOIN
			// (SELECT  dcode, corporation_id, corporation_name  FROM public.m_corporation where dcode=$dcode)cor
			// on a.dcode = cor.dcode and a.corporation_code = cor.corporation_id
			// LEFT JOIN
			// (SELECT wid,community_name,sex,gender_text 
			// FROM (SELECT work_id as wid,community_code,sex,case when sex=1 then 'Male' when sex=2 then 'Female' when sex=3 then 'Transgender' end as gender_text  FROM t_housing_additional_info)aa 
			// LEFT JOIN 
			// (SELECT * FROM m_community)bb ON aa.community_code = bb.community_code::NUMERIC)e ON a.work_id = e.wid 
			// LEFT JOIN 
			// (SELECT  work_id, max(upd_date::date) as upd_date,sum(case when file_url is not null and file_url<>'null' then 1 else 0 end) as no_of_file_found 
			// FROM t_scheme_work_physical_progress WHERE stage_id  not in (10,11)  and work_id in (select work_id FROM view_workdetails WHERE   agency_group_id in (select agency_group_id from m_agency_group where rd_line_department=1) and  rural_urban='$rural_urban' and dcode=$dcode $rural_urban_cond and (yn_completed is null OR yn_completed='N') and current_stage_of_work not in (10,11) $fin_year_cond $scheme_id_cond ) and cd_prot_workid=0 GROUP BY work_id)d ON a.work_id = d.work_id :: NUMERIC WHERE ((yn_completed is null) OR (yn_completed='N')) and current_stage_of_work not in (10,11) AND a.dcode = $dcode $on_rural_urban_cond ORDER BY work_id"; 
			
			$sql="SELECT a.*, c.district_name_en as dname,to_char(d.upd_date,'DD-MM-YYYY') as upd_date, case when no_of_file_found>0 then 'Y' else 'N' end as image_available, f.lbcode as townpanchayat_id, f.lbody_name_en as townpanchayat_name from 
			(SELECT dcode,lbcode, work_id, scheme_group_id, scheme_id , scheme_group_name_en as schemegrp_name, scheme_name_en as scheme_name, fin_year, agency_name_en as agency_name , wrkgrpname, work_name_en as worktypname,work_group_id,work_type_id as work_type, work_id as mworkid, current_stage_of_work, as_value, amount_spent_sofar, stage_name,yn_completed,cd_prot_work_yn, work_name,to_char(as_date,'DD-MM-YYYY') as as_date, to_char(ts_date,'DD-MM-YYYY') as ts_date,lbcode as townpanchayat_code  
						FROM master.view_workdetails WHERE  
			agency_group_id in (select agency_group_id from master.m_agency_group where del_flag is null) and dcode=:dcode and lbcode=:lbcode and (yn_completed is null OR yn_completed='N') and current_stage_of_work not in (10,11) $fin_year_cond $scheme_id_cond )a 
						LEFT JOIN 
						(SELECT * from master.m_district where dcode = :dcode)c on a.dcode=c.dcode 
			LEFT JOIN 
			(SELECT * from master.m_localbodies where dcode = :dcode)f on a.dcode=c.dcode and a.lbcode=f.lbcode
						LEFT JOIN
						(SELECT  work_id, max(ins_date::date) as upd_date,sum(case when file_url is not null and file_url<>'null' then 1 else 0 end) as no_of_file_found 
						FROM works.t_scheme_work_physical_progress WHERE stage_id  not in (10,11)  and work_id in (select work_id FROM master.view_workdetails WHERE   agency_group_id in (select agency_group_id from master.m_agency_group where del_flag is null)  and dcode=:dcode and lbcode=:lbcode and (yn_completed is null OR yn_completed='N') and current_stage_of_work not in (10,11) $fin_year_cond $scheme_id_cond ) and cd_prot_workid=0 GROUP BY work_id)d ON a.work_id = d.work_id :: NUMERIC WHERE ((yn_completed is null) OR (yn_completed='N')) and current_stage_of_work not in (10,11) AND a.dcode = :dcode and a.lbcode=:lbcode ORDER BY work_id "; 
 			$res = $this->prepare($sql, array(":dcode" => $dcode,":lbcode" => $lbcode), 2);

			//$res = $this->obj->selfn($sql, $this->db);
			//var_dump($res); exit;
			 //echo $sql; die;
			 
			 			 
	
		//  $sql_add = "select a.*,b.cd_work_no,b.cd_code,b.cd_type_id,b.cd_name,b.chainage_meter,case when b.work_type_flag='C' then 'CD Works' when b.work_type_flag='P' then 'Protective Works' end as work_type_flag, b.work_type_flag as work_type_flag_le ,case when a.road_category=1 then 'PUR' when a.road_category=2 then 'VPR' end as road_category_name,d.current_stage_of_work ,case when d.current_stage_of_work=1 then 'Not Started' else d.work_stage_name end  as work_stage_name,case when no_of_file_found>0 then 'Y' else 'N' end as image_available from (select dcode, bcode, pvcode,rural_urban,scheme_id,fin_year,work_id,work_group_id,mwork_id,work_name as roadname,road_reg_id,road_category,current_stage_of_work from t_works where   agency_group_id in (select agency_group_id from m_agency_group where rd_line_department=1) and  rural_urban='$rural_urban' and dcode=$dcode $rural_urban_cond $fin_year_cond and cd_prot_work_yn='Y' and  (yn_completed is null OR yn_completed='N') and current_stage_of_work not in (10,11)  $scheme_id_cond  )a LEFT JOIN (select aa.*,bb.cd_code,bb.cd_name from 
		//  (select work_id,cd_work_no,cd_type_id,chainage_meter,work_type_flag from t_works_additional_info)aa LEFT JOIN (select cd_code,cd_name from m_cross_drainage_works ORDER BY cd_code)bb on aa.cd_type_id=bb.cd_code )b on a.work_id=b.work_id LEFT JOIN(select a.work_id,a.cd_prot_workid,a.cd_type_flag,first(stage_id  order by work_stage_order desc nulls last) as current_stage_of_work,first(work_stage_name  order by work_stage_order desc nulls last)  as work_stage_name,sum(case when file_url is not null and file_url<>'null' then 1 else 0 end) as no_of_file_found  FROM t_works_additional_info as x right join t_scheme_work_physical_progress as a on x.work_id=a.work_id and x.cd_work_no=a.cd_prot_workid  and x.work_type_flag=a.cd_type_flag left join m_cdwork_stage_link as b on  a.stage_id=b.work_stage_code  and x.cd_type_id=b.work_type_code and a.cd_type_flag=b.cd_type_flag  and x.work_type_flag=b.cd_type_flag where cd_prot_workid!=0 and x.work_id in	(select work_id from t_works where   agency_group_id in (select agency_group_id from m_agency_group where rd_line_department=1) and  rural_urban='$rural_urban'and dcode=$dcode $rural_urban_cond $fin_year_cond and cd_prot_work_yn='Y' and  (yn_completed is null OR yn_completed='N') and current_stage_of_work not in (10,11)  $scheme_id_cond ) group by a.work_id,a.cd_prot_workid,a.cd_type_flag) as d on a.work_id=d.work_id and  d.cd_prot_workid=b.cd_work_no  and b.work_type_flag=d.cd_type_flag";  

		//  $sql_add =	"select a.*,b.cd_work_no,b.cd_code,b.cd_type_id,b.cd_name,b.chainage_meter,case when b.work_type_flag='C' then 'CD Works' when b.work_type_flag='P' then 'Protective Works' end as work_type_flag, b.work_type_flag as work_type_flag_le ,case when a.road_category=1 then 'PUR' when a.road_category=2 then 'VPR' end as road_category_name,d.current_stage_of_work ,case when d.current_stage_of_work=1 then 'Not Started' else d.work_stage_name end  as work_stage_name,case when no_of_file_found>0 then 'Y' else 'N' end as image_available from 
		// 	 (select dcode,lbcode,scheme_id,fin_year,work_id,work_group_id,work_name as roadname,road_reg_id,road_category,current_stage_of_work from works.t_works where   agency_group_id in (select agency_group_id from master.m_agency_group where del_flag is null) and dcode=:dcode and lbcode=:lbcode $fin_year_cond and cd_prot_work_yn='Y' and  (yn_completed is null OR yn_completed='N') and current_stage_of_work not in (10,11)  $scheme_id_cond  )a 
		// 	 LEFT JOIN 
		// 	 (select aa.*,bb.cd_code,bb.cd_name from 
		// 			  (select work_id,cd_work_no,cd_type_id,chainage_meter,work_type_flag from works.t_works_additional_info)aa 
		// 	 LEFT JOIN 
		// 	 (select cd_code,cd_name from master.m_cross_drainage_works ORDER BY cd_code)bb on aa.cd_type_id=bb.cd_code )b on a.work_id=b.work_id 
		// 	 LEFT JOIN
		// 	 (select a.work_id,a.cd_prot_workid,a.cd_type_flag,first(stage_id  order by work_stage_order desc nulls last) as current_stage_of_work,first(work_stage_name  order by work_stage_order desc nulls last)  as work_stage_name,sum(case when file_url is not null and file_url<>'null' then 1 else 0 end) as no_of_file_found  FROM works.t_works_additional_info as x 
		// 	 right join 
		// 	 works.t_scheme_work_physical_progress as a on x.work_id=a.work_id and x.cd_work_no=a.cd_prot_workid  and x.work_type_flag=a.cd_type_flag 
		// 	 left join 
		// 	 master.m_cdwork_stage_link as b on  a.stage_id=b.work_stage_code  and x.cd_type_id=b.work_type_code and a.cd_type_flag=b.cd_type_flag  and x.work_type_flag=b.cd_type_flag where cd_prot_workid!=0 and x.work_id in	(select work_id from works.t_works where   agency_group_id in (select agency_group_id from master.m_agency_group where del_flag is null) and dcode=:dcode and lbcode=:lbcode $fin_year_cond and cd_prot_work_yn='Y' and  (yn_completed is null OR yn_completed='N') and current_stage_of_work not in (10,11)  $scheme_id_cond ) group by a.work_id,a.cd_prot_workid,a.cd_type_flag) as d on a.work_id=d.work_id and  d.cd_prot_workid=b.cd_work_no  and b.work_type_flag=d.cd_type_flag";

		// //  echo "$sql_add"; die;
		// $sql_add_res = $this->prepare($sql_add, array(":dcode" => $dcode,":lbcode" => $lbcode), 2);
		//print_r($sql_add_res);die;
		//$sql_add_res = $this->obj->selfn($sql_add, $this->db); 

		


// 	   $sql_group_work = "SELECT A.*,b.work_type_link_id, d.current_stage_of_work,d.group_work_type,b.work_name as work_type_name, CASE WHEN d.current_stage_of_work = 1 THEN 'Not Started' ELSE d.work_stage_name END AS work_stage_name, CASE WHEN no_of_file_found > 0 THEN 'Y' ELSE 'N' END AS image_available FROM ( SELECT dcode, bcode, pvcode,rural_urban,scheme_id,
// 	   fin_year, work_id, work_group_id, work_name ,	is_group_work, group_works_completed FROM t_works WHERE agency_group_id IN( SELECT agency_group_id FROM m_agency_group WHERE rd_line_department = 1 ) AND rural_urban='$rural_urban' AND dcode=$dcode $rural_urban_cond  $fin_year_cond AND is_group_work = 'Y' AND( yn_completed IS NULL OR yn_completed = 'N' ) AND current_stage_of_work NOT IN(10, 11) $scheme_id_cond )A LEFT JOIN( SELECT aa.*, bb.work_name FROM ( SELECT work_id, work_type_link_id, current_stage_of_work FROM t_works_additional_info )aa LEFT JOIN( SELECT work_id,work_name FROM m_work_type_name
// 	   )bb ON aa.work_type_link_id = bb.work_id )b ON A.work_id = b.work_id and b.work_type_link_id is not null LEFT JOIN (SELECT
// 		x .work_id,x.work_type_link_id,	
// 		FIRST(
// 			A.group_work_type
// 			ORDER BY
// 				work_stage_order DESC NULLS LAST
// 		)AS group_work_type,
// 		FIRST(
// 			A.stage_id
// 			ORDER BY
// 				work_stage_order DESC NULLS LAST
// 		)AS current_stage_of_work,
// 		FIRST(
// 			work_stage_name
// 			ORDER BY
// 				work_stage_order DESC NULLS LAST
// 		)AS work_stage_name,
// 		SUM(
// 			CASE
// 			WHEN file_url IS NOT NULL
// 			AND file_url <> 'null' THEN
// 				1
// 			ELSE
// 				0
// 			END
// 		)AS no_of_file_found
// 	FROM
// 		t_works as tw left join 
// 		t_works_additional_info AS x on tw.work_id=x.work_id  
// 	RIGHT JOIN t_scheme_work_physical_progress AS A ON x.work_id = A .work_id
// 	AND x.work_type_link_id=A.work_type_link_id
// 	LEFT JOIN m_work_stage_link AS b ON A .stage_id = b.work_stage_code and tw.work_group_id=b.work_group_code
// 	WHERE
// 		x.is_group_work='Y' 
// 	AND x.work_id IN(
// 		SELECT
// 			work_id
// 		FROM
// 			t_works
// 		WHERE
// 			agency_group_id IN(
// 				SELECT
// 					agency_group_id
// 				FROM
// 					m_agency_group
// 				WHERE
// 					rd_line_department = 1
// 			)
// 	AND rural_urban='$rural_urban'
// 	AND dcode=$dcode $rural_urban_cond  
// 	$fin_year_cond
// 		AND is_group_work = 'Y'
// 		AND(
// 			yn_completed IS NULL
// 			OR yn_completed = 'N'
// 		)
// 		AND current_stage_of_work NOT IN(10, 11)
// 		$scheme_id_cond
// 	)
// 	GROUP BY
// 		x .work_id,x.work_type_link_id
// )AS d ON A .work_id = d.work_id
// AND b.work_type_link_id = d.work_type_link_id"; 

// $sql_group_work ="SELECT A.*,b.work_type_link_id, b.current_stage_of_work,b.work_name_en as work_type_name, CASE WHEN b.current_stage_of_work = 1 THEN 'Not Started' ELSE d.stage_name END AS work_stage_name, CASE WHEN no_of_file_found > 0 THEN 'Y' ELSE 'N' END AS image_available FROM
// 		( SELECT dcode, lbcode,scheme_id,fin_year, work_id, work_group_id, work_name FROM works.t_works WHERE agency_group_id IN( SELECT agency_group_id FROM master.m_agency_group where del_flag is null) AND dcode=:dcode and lbcode=:lbcode  $fin_year_cond  AND( yn_completed IS NULL OR yn_completed = 'N' ) AND current_stage_of_work NOT IN(10, 11) and scheme_id=9 )a 
// 	   LEFT JOIN
// 	   ( SELECT aa.*, bb.work_name_en FROM ( SELECT work_id, work_type_link_id, current_stage_of_work FROM works.t_works_additional_info where del_flag is null )aa 
// 	   LEFT JOIN
// 	   ( SELECT work_type_id,work_name_en FROM master.m_work_type_name)bb 
// 	   ON aa.work_type_link_id = bb.work_type_id )b 
// 	   ON a .work_id = b.work_id and b.work_type_link_id is not null 
// 	   LEFT JOIN 
// 	   (SELECT
// 			   x .work_id,x.work_type_link_id,stage_name,
// 			   SUM(
// 				   CASE
// 				   WHEN file_url IS NOT NULL
// 				   AND file_url <> 'null' THEN
// 					   1
// 				   ELSE
// 					   0
// 				   END
// 			   )AS no_of_file_found
// 		   FROM
// 			   works.t_works as tw left join 
// 			   works.t_works_additional_info AS x on tw.work_id=x.work_id  
// 		   RIGHT JOIN works.t_scheme_work_physical_progress AS A ON x.work_id = a.work_id
// 		   LEFT JOIN 
// 		   (select * from master.m_work_stage_link where del_flag is null) AS b 
// 		   ON a.stage_id = b.work_stage_id and tw.work_group_id=b.work_group_id
// 		   left join
// 		   (select * from master.m_stage where del_flag is null) as f
// 		   on f.stage_id=b.work_stage_id
// 		   WHERE
// 			   x.is_group_work='Y' 
// 		   AND x.work_id IN(
// 			   SELECT
// 				   work_id
// 			   FROM
// 				   works.t_works
// 			   WHERE
// 				   agency_group_id IN(
// 					   SELECT
// 						   agency_group_id
// 					   FROM
// 						   master.m_agency_group
// 					   WHERE
// 						   del_flag is null
// 				   )
// 				   AND dcode=:dcode and lbcode=:lbcode  $fin_year_cond
// 			   AND is_group_work = 'Y'
// 			   AND(
// 				   yn_completed IS NULL
// 				   OR yn_completed = 'N'
// 			   )
// 			   AND current_stage_of_work NOT IN(10, 11)
// 			   $scheme_id_cond
// 		   )
// 		   GROUP BY
// 			   x .work_id,x.work_type_link_id,stage_name
// 	   )AS d ON a.work_id = d.work_id
// 	   AND b.work_type_link_id = d.work_type_link_id";
// 	// echo $sql_group_work; die;
// 	$sql_group_work_res = $this->prepare($sql_group_work, array(":dcode" => $dcode,":lbcode" => $lbcode), 2);
	//print_r($sql_group_work_res);die;
		//$sql_group_work_res = $this->obj->selfn($sql_group_work, $this->db); 
	
		

	//  $sql_add_details = "SELECT a.rural_urban, a.dcode, a.bcode, a.pvcode, a.townpanchayat_code, a.municipality_code, a.corporation_code, a.work_id, a.scheme_group_id, a.scheme_id , a.work_group_id,a.mwork_id as work_type, b.length_or_diameter_in_m, b.width_or_diameter_in_m, b.depth_in_m, b.breadth_in_m, b.height_of_weir_in_m, b.length_of_water_storage_in_upstream_side_of_channel_in_m, b.no_of_trenches_per_one_work_site, b.no_of_units_per_waterbody, b.no_of_units, b.area_covered_in_hect, b.no_of_plantation_per_site, b.is_storage_structure_or_sump_available, b.soakpit_type, b.observation_premonsoon_gw_depth_in_m, b.latitude, b.longitude FROM t_works as a left join public.t_works_additional_info as b on a.work_id=b.work_id where   a.agency_group_id in (select agency_group_id from m_agency_group where rd_line_department=1) and a.rural_urban = '$rural_urban' and  a.dcode = $dcode $on_rural_urban_cond $on_fin_year_cond  $on_scheme_id_cond";  

	 $sql_add_details = "SELECT  a.dcode, a.lbcode as townpanchayat_code, a.work_id, a.scheme_group_id, a.scheme_id , a.work_group_id,a.work_id as work_type, b.length_or_diameter_in_m, b.width_or_diameter_in_m, b.depth_in_m, b.breadth_in_m, b.height_of_weir_in_m, b.length_of_water_storage_in_upstream_side_of_channel_in_m, b.no_of_trenches_per_one_work_site, b.no_of_units_per_waterbody, b.no_of_units, b.area_covered_in_hect, b.no_of_plantation_per_site, b.is_storage_structure_or_sump_available, b.soakpit_type, b.observation_premonsoon_gw_depth_in_m, b.latitude, b.longitude FROM works.t_works as a left join works.t_works_additional_info as b on a.work_id=b.work_id where   a.agency_group_id in (select agency_group_id from master.m_agency_group where del_flag is null)  and  a.dcode = :dcode and a.lbcode=:lbcode  $on_fin_year_cond  $on_scheme_id_cond";

	$sql_add_details_res = $this->prepare($sql_add_details, array(":dcode" => $dcode,":lbcode" => $lbcode), 2);
	//print_r($sql_add_details_res);die;
	
	// echo"$sql_add_details"; die;
	//print_r($sql_add_details_res);die;
	//print_r($work_count);die;
		if(count($res)>0)
		{
			$response_data['STATUS']='OK';
			$response_data['RESPONSE']='OK';
			// $response_data['JSON_DATA']=array('main_work'=>$res,'additional_work'=>count($sql_add_res)==0?array():$sql_add_res,'group_work'=>count($sql_group_work_res)==0?array():$sql_group_work_res,'work_additional_details'=>count($sql_add_details_res)==0?array():$sql_add_details_res); 	
			$response_data['JSON_DATA']=array('main_work'=>$res); 		
			
					
			
		}
		else
		{
			$response_data['STATUS']='OK';
			$response_data['RESPONSE']='NO_RECORD';
			$response_data['MESSAGE']='NO_RECORD';
			
		}

		// var_dump($response_data); exit;
		$header_data['user_name'] = $decrypted_data_json->user_data['user_name'];
		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);
	
	}
	public function work_progress_detail($decrypted_data_json)
	{
		$work_id=$decrypted_data_json->work_id;

			$sql_days_cacl="SELECT a.work_phy_prog_seq_id,
				work_id,
				to_char(a.ins_date, 'DD-MM-YYYY') AS date,
				a.stage_id AS current_stage_of_work,
				CASE WHEN a.stage_id = 1 THEN 'Not Started' ELSE b.stage_name END AS stage_name,
				COALESCE(
				EXTRACT(DAY FROM (a.ins_date - LEAD(a.ins_date) OVER (PARTITION BY a.work_id ORDER BY a.ins_date DESC))) , 0
			) AS days
			FROM works.t_scheme_work_physical_progress AS a
			LEFT JOIN master.m_stage AS b ON a.stage_id = b.stage_id
			WHERE work_id = :work_id
			ORDER BY a.work_phy_prog_seq_id DESC;";
		// echo $sql_days_cacl; die;
		//$res = $this->obj->selfn($sql_days_cacl, $this->db); 
		$res = $this->prepare($sql_days_cacl, array(":work_id" => $work_id), 2); 
		
		
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
		$header_data['user_name'] = $decrypted_data_json->user_data['user_name'];
		$header_data['response_data'] = $response_data;
		$this->CreateHeader($header_data);		
		//return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));		
		
	}
	public function current_finyear_wise_work_status_count($decrypted_data_json)
	{
		$user_name=$decrypted_data_json->user_data['user_name'];


		$sql_days_cacl="select scheme_id,scheme_name_en,work_count,fin_year from 
		(select scheme_id,COUNT(DISTINCT work_id) as work_count,fin_year from works.t_scheme_work_physical_progress where ins_user_name= :user_name GROUP BY scheme_id,fin_year)a
		left join
		(select scheme_seq_id,scheme_name_en from master.m_scheme where del_flag is null)b
		on a.scheme_id = b.scheme_seq_id";
		$res = $this->prepare($sql_days_cacl, array(":user_name" => $user_name), 2); 
		if (count($res) > 0) {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$grouped_data = [];
		
			foreach ($res as $work) {
				if (!isset($grouped_data[$work['fin_year']])) {
					$grouped_data[$work['fin_year']] = [
						'fin_year' => $work['fin_year'],
						'work_count' => 0, 
						'scheme_wise_data' => []
					];
				}
				$grouped_data[$work['fin_year']]['work_count'] += $work['work_count'];
				$grouped_data[$work['fin_year']]['scheme_wise_data'][] = [
					'scheme_id' => $work['scheme_id'],
					'scheme_name_en' => $work['scheme_name_en'],
					'work_count' => $work['work_count'],
					'fin_year' => $work['fin_year']
				];
			}
			$response_data['JSON_DATA'] = array_values($grouped_data);
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
		//return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));		
		
	}

	

	public function work_phy_stage_save($decrypted_data_json)
{	
	$this->beginTransaction();
$response_data=array();
$req_params=array();
$user_name=$decrypted_data_json->user_data['user_name'];
$ins_rec=0;
foreach($decrypted_data_json->track_data as $key_data => $val_data)
{
if(isset($decrypted_data_json->user_data['dcode']) && $decrypted_data_json->user_data['dcode']!="")
{
$dcode=$decrypted_data_json->user_data['dcode'];
}
else
{
$dcode=$val_data->dcode;
}

if(isset($decrypted_data_json->user_data['lbcode']) && $decrypted_data_json->user_data['lbcode']!="")
{
$lbcode=$decrypted_data_json->user_data['lbcode'];
}
else
{
$lbcode=$val_data->lbcode;
}	
$work_id=$val_data->work_id;

if(isset($val_data->cd_work_no))
$cd_work_no=$val_data->cd_work_no;
else
$cd_work_no="";

if(isset($val_data->is_group_work))
$is_group_work=$val_data->is_group_work;
else
$is_group_work="";

if(isset($val_data->work_type_link_id))
$work_type_link_id=$val_data->work_type_link_id;
else
$work_type_link_id="";

if(isset($val_data->group_work_type))
$group_work_type=$val_data->group_work_type;
else
$group_work_type="";

if(isset($val_data->work_type_flag_le))
$cd_type_flag=$val_data->work_type_flag_le;
else
$cd_type_flag="";

$work_stage_code=$val_data->work_stage_code;
$images=$val_data->images;
$latitude=$val_data->latitude;
$longitude=$val_data->longitude;

$insert_phy_progress=array('work_type_id'=>'','work_group_id'=>'','scheme_group_id'=>'','scheme_id'=>'', 'fin_year'=>'', 'work_id'=>'', 'stage_id'=>'', 'upd_date'=>'', 'user_name'=>'', 'ipaddress'=>'', 'file_url'=>'', 'mobile_upd_time'=>'', 'cd_prot_workid'=>'', 'cd_type_flag'=>'', 'cd_chainage'=>'', 'photo_captured_latitude'=>'', 'photo_captured_longitude'=>'', 'upd_time'=>'', 'pho_upddate'=>'', 'pho_updtime'=>'','work_type_link_id'=>'','works_additional_info_id'=>'','group_work_type'=>'');

$insert_phy_progress['photo_captured_latitude']=$latitude;
$insert_phy_progress['photo_captured_longitude']=$longitude;
// Removed Bcode condition for Urban Save

    $sql_find_work="select work_type_id, work_group_id, scheme_group_id, scheme_id, fin_year, cd_prot_work_yn, current_stage_of_work, work_group_id, work_id,work_type_id, scheme_id from master.view_workdetails where dcode=:dcode and lbcode=:lbcode and  work_id=:work_id";
$sql_find_work_res = $this->prepare($sql_find_work, array(":dcode" => $dcode,":work_id" => $work_id,":lbcode" => $lbcode), 4); 
//print_r($sql_find_work_res);die;
//$sql_find_work_res = $this->obj->selonefn($sql_find_work, $this->db); 

$insert_phy_progress['scheme_id']=$sql_find_work_res['scheme_id'];
$finyear=$insert_phy_progress['fin_year']=$sql_find_work_res['fin_year'];
$insert_phy_progress['work_type_id']=$sql_find_work_res['work_type_id'];
$insert_phy_progress['work_group_id']=$sql_find_work_res['work_group_id'];	
$insert_phy_progress['scheme_group_id']=$sql_find_work_res['scheme_group_id'];

if($is_group_work=='Y' && $group_work_type=="S")
{	
$sql_find_phy_current="SELECT tw.work_group_id,a.work_type_link_id,a.works_additional_info_id,a.work_id,work_stage_order,b.stage_id,b.group_work_type FROM works.t_works as tw 
left join 
works.t_works_additional_info as a  
on tw.work_id=a.work_id 
left join 
works.t_scheme_work_physical_progress as b 
on a.work_id=b.work_id and a.work_type_link_id=b.work_type_link_id and a.is_group_work='Y' 
left join 
master.m_work_stage_link as c 
on  b.stage_id=c.work_stage_id and  tw.work_group_id=tw.work_group_id 
where tw.work_id=:work_id and tw.is_group_work='Y' and a.work_type_link_id=1 order by a.work_type_link_id,work_stage_order desc nulls last limit 1"; 
$sql_find_phy_current_res = $this->prepare($sql_find_phy_current, array(":work_type_link_id" => $work_type_link_id,":work_id" => $work_id), 4); 
//$sql_find_phy_current_res = $this->obj->selonefn($sql_find_phy_current, $this->db);	

$current_stage_of_work=$sql_find_phy_current_res['stage_id'];
$work_group_id=$sql_find_phy_current_res['work_group_id'];
$mwork_id=$sql_find_phy_current_res['work_type_link_id'];	

$insert_phy_progress['work_type_link_id']=$sql_find_phy_current_res['work_type_link_id'];
$insert_phy_progress['works_additional_info_id']=$sql_find_phy_current_res['works_additional_info_id'];
$insert_phy_progress['group_work_type']=$sql_find_phy_current_res['group_work_type'];

$insert_phy_progress['cd_prot_workid']=0;
$insert_phy_progress['cd_type_flag']='N';
$insert_phy_progress['cd_chainage']='0.0';

$sql_find_curr_work_stage_order="select work_stage_order  from master.m_work_stage_link where  work_stage_code=:current_stage_of_work and work_group_id=:work_group_id and work_id=:work_id "; 
$sql_find_curr_work_stage_order_res = $this->prepare($sql_find_curr_work_stage_order, array(":current_stage_of_work" => $current_stage_of_work,":work_id" => $mwork_id,":work_group_id" => $work_group_id), 4);
//$sql_find_curr_work_stage_order_res = $this->obj->selonefn($sql_find_curr_work_stage_order, $this->db); 

$sql_find_new_work_stage_order="select work_stage_order  from master.m_work_stage_link where  work_stage_id=:work_stage_code and work_group_id=:work_group_id and work_id=:work_id"; 
$sql_find_new_work_stage_order_res = $this->prepare($sql_find_new_work_stage_order, array(":work_stage_code" => $work_stage_code,":work_id" => $mwork_id,":work_group_id" => $work_group_id), 4);

// $sql_find_new_work_stage_order_res = $this->obj->selonefn($sql_find_new_work_stage_order, $this->db); 

$current_stage_order_no=$sql_find_curr_work_stage_order_res['work_stage_order'];
$new_stage_order_no=$sql_find_new_work_stage_order_res['work_stage_order'];

if($current_stage_order_no!="" && $current_stage_order_no>$new_stage_order_no)
{
	$this->rollback();
$response_data['STATUS']='OK';  
$response_data['RESPONSE']='FAIL';
$response_data['MESSAGE']='HIGHER STAGE ALREADY EXISTS, Unable to save your Lower Stage details.';
$header_data['user_name'] = $decrypted_data_json->user_data['user_name'];
$header_data['response_data'] = $response_data;
$this->CreateHeader($header_data);
exit;
//return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
}
//  $CHECK_UPD_INS_STATUS="select * from works.t_scheme_work_physical_progress as a where work_id=".$work_id." and (stage_id=".$work_stage_code.") and work_type_link_id=$mwork_id  ";  
// $CHECK_UPD_INS_STATUS_RES = $this->obj->selfn($CHECK_UPD_INS_STATUS, $this->db);

$CHECK_UPD_INS_STATUS="select * from works.t_scheme_work_physical_progress as a where work_id=:work_id and (stage_id=:work_stage_code) and work_type_link_id=:work_group_id  "; 
$CHECK_UPD_INS_STATUS_RES = $this->prepare($CHECK_UPD_INS_STATUS, array(":work_stage_code" => $work_stage_code,":work_id" => $work_id,":work_group_id" => $mwork_id), 2);	
if(count($CHECK_UPD_INS_STATUS_RES)!=0)
{
	$this->rollback();
$response_data['STATUS']='OK';  
$response_data['RESPONSE']='FAIL';
$response_data['MESSAGE']='STAGE ALREADY EXISTS';
$header_data['user_name'] = $decrypted_data_json->user_data['user_name'];
$header_data['response_data'] = $response_data;
$this->CreateHeader($header_data);
exit;
// return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
// exit;
}	
}
else if($cd_work_no!="")
{	

$is_cd_work="Y";

$insert_phy_progress['work_type_link_id']='NULL';
$insert_phy_progress['works_additional_info_id']='NULL';

$sql_find_phy_current="SELECT a.work_id,a.cd_work_no,a.cd_type_id,a.work_type_flag,work_stage_order,b.stage_id FROM works.t_works_additional_info as a left join works.t_scheme_work_physical_progress as b on a.work_id=b.work_id and a.cd_work_no=b.cd_prot_workid and a.work_type_flag=b.cd_type_flag left join master.m_cdwork_stage_link as c on a.work_type_flag=c.cd_type_flag and a.cd_type_id=c.work_type_code and b.stage_id=c.work_stage_code where a.work_id=:work_id and a.cd_work_no=:cd_work_no and a.work_type_flag=:cd_type_flag order by a.work_type_flag,a.cd_work_no,work_stage_order desc nulls last limit 1;"; 
$sql_find_phy_current_res = $this->prepare($sql_find_phy_current, array("cd_work_no"=>$cd_work_no, ":cd_type_flag"=>$cd_type_flag, ":work_id"=>$work_id),4);	

$current_stage_of_work=$sql_find_phy_current_res['stage_id'];

$sql_find_work_add="select work_type_flag,chainage_meter,cd_type_id,work_type_flag from works.t_works_additional_info where work_id=:work_id and cd_work_no=:cd_work_no and work_type_flag=:cd_type_flag;";
$sql_find_work_add_res = $this->prepare($sql_find_work_add, array(":work_id" => $work_id, ":cd_work_no" => $cd_work_no, ":cd_type_flag" => $cd_type_flag),4);  	

$insert_phy_progress['cd_type_flag']=$sql_find_work_add_res['work_type_flag'];

$insert_phy_progress['cd_prot_workid']=$cd_work_no;

$insert_phy_progress['cd_chainage']=$sql_find_work_add_res['chainage_meter'];

$cd_type_id=$sql_find_work_add_res['cd_type_id'];
$work_type_flag=$sql_find_work_add_res['work_type_flag'];

$sql_find_curr_work_stage_order="select work_stage_order from master.m_cdwork_stage_link where work_stage_code=:current_stage_of_work and work_type_code=:cd_type_id and cd_type_flag=:work_type_flag;"; 
$sql_find_curr_work_stage_order_res = $this->prepare($sql_find_curr_work_stage_order, array(":current_stage_of_work" => $current_stage_of_work, ":cd_type_id" => $cd_type_id, ":work_type_flag" => $work_type_flag),4); 


$sql_find_new_work_stage_order="select work_stage_order  from m_cdwork_stage_link where  work_stage_code=:work_stage_code and work_type_code=:cd_type_id and cd_type_flag=:work_type_flag"; 

$sql_find_new_work_stage_order_res = $this->prepare($sql_find_new_work_stage_order, array(":work_stage_code" => $work_stage_code, ":cd_type_id" => $cd_type_id, ":work_type_flag" => $work_type_flag),4); 

$current_stage_order_no=$sql_find_curr_work_stage_order_res['work_stage_order'];
$new_stage_order_no=$sql_find_new_work_stage_order_res['work_stage_order'];

if($current_stage_order_no!="" && $current_stage_order_no>$new_stage_order_no)
{
	$this->rollback();
$response_data['STATUS']='OK';  
$response_data['RESPONSE']='FAIL';
$response_data['MESSAGE']='HIGHER STAGE ALREADY EXISTS, Unable to save your Lower Stage details.';
$header_data['user_name'] = $decrypted_data_json->user_data['user_name'];
$header_data['response_data'] = $response_data;
$this->CreateHeader($header_data);
exit;
//return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
//exit;
}

$CHECK_UPD_INS_STATUS="select * from works.t_scheme_work_physical_progress as a where work_id=:work_id and (stage_id=:work_stage_code and cd_prot_workid=:cd_work_no and cd_type_flag=:work_type_flag;";  
$CHECK_UPD_INS_STATUS_RES = $this->prepare($CHECK_UPD_INS_STATUS, array(":work_id" => $work_id, ":work_stage_code" => $work_stage_code, ":cd_work_no" => $cd_work_no, ":work_type_flag" => $work_type_flag),2);	

if(count($CHECK_UPD_INS_STATUS_RES)!=0)
{
	$this->rollback();
$response_data['STATUS']='OK';  
$response_data['RESPONSE']='FAIL';
$response_data['MESSAGE']='STAGE ALREADY EXISTS';
$header_data['user_name'] = $decrypted_data_json->user_data['user_name'];
$header_data['response_data'] = $response_data;
$this->CreateHeader($header_data);
exit;
//return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
//exit;
}
}
else
{
$is_cd_work="N";

$current_stage_of_work=$sql_find_work_res['current_stage_of_work'];
$work_group_id=$sql_find_work_res['work_group_id'];
$mwork_id=$sql_find_work_res['work_type_id'];	
$scheme_id=$sql_find_work_res['scheme_id'];

if($is_group_work=='Y' && $group_work_type=="M")
{
$insert_phy_progress['group_work_type']='M';
}	

$insert_phy_progress['work_type_link_id']='NULL';
$insert_phy_progress['works_additional_info_id']='NULL';


$insert_phy_progress['cd_prot_workid']=0;
$insert_phy_progress['cd_type_flag']='N';
$insert_phy_progress['cd_chainage']='0.0';

 	$sql_find_curr_work_stage_order="select work_stage_order from master.m_work_stage_link where work_stage_id=:current_stage_of_work and work_group_id=:work_group_id and work_id=:mwork_id "; 
$sql_find_curr_work_stage_order_res = $this->prepare($sql_find_curr_work_stage_order, array(":current_stage_of_work" => $current_stage_of_work, ":work_group_id" => $work_group_id, ":mwork_id" => $mwork_id), 4); 


$sql_find_new_work_stage_order="select work_stage_order  from master.m_work_stage_link where  work_stage_id=:work_stage_code and work_group_id=:work_group_id and work_id=:mwork_id"; 

$sql_find_new_work_stage_order_res = $this->prepare($sql_find_new_work_stage_order, array(":work_stage_code" => $work_stage_code, ":work_group_id" => $work_group_id, ":mwork_id" => $mwork_id), 4); 

//$sql_find_new_work_stage_order_res = $this->obj->selonefn($sql_find_new_work_stage_order, $this->db); 

$current_stage_order_no=$sql_find_curr_work_stage_order_res['work_stage_order'];
$new_stage_order_no=$sql_find_new_work_stage_order_res['work_stage_order'];

if($current_stage_order_no!="" && $current_stage_order_no>$new_stage_order_no)
{
$response_data['STATUS']='OK';  
$response_data['RESPONSE']='FAIL';
$response_data['MESSAGE']='HIGHER STAGE ALREADY EXISTS, Unable to save your Lower Stage details.';
$header_data['user_name'] = $decrypted_data_json->user_data['user_name'];
$header_data['response_data'] = $response_data;
$this->CreateHeader($header_data);
exit;
//return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
//exit;
}

$chk_breakup_mandate="select * from master.m_stage_wise_amount_breakup where scheme_id=:scheme_id and del_flag is null; "; 
 
$chk_breakup_mandate_res = $this->prepare($chk_breakup_mandate, array(":scheme_id" => $scheme_id),4); 

if(count($chk_breakup_mandate_res)>0)
{
 $check_mandatory_stage="SELECT sum(case when (release_percentage::int >0 or release_amount::int >0) and file_url  is null then 1 else 0 end) as is_any_req_stage_not_ok  FROM works.t_works as tw left join 
master.m_work_stage_link as wsl on  tw.work_id=wsl.work_id left join 
master.m_stage_wise_amount_breakup as amb on amb.del_flag is null and  tw.scheme_id=amb.scheme_id and   tw.work_id=amb.work_type_id and wsl.work_stage_id=amb.stage_id left join 
works.t_scheme_work_physical_progress as pp on tw.work_id=pp.work_id and wsl.work_stage_id=pp.stage_id 
where tw.work_id=:work_id  and work_stage_order<(select work_stage_order from master.m_work_stage_link where work_code=:work_id and work_stage_id=:work_stage_code)"; 
$check_mandatory_stage_res = $this->prepare($check_mandatory_stage, array(":work_id" => $work_id, ":mwork_id" => $mwork_id, ":work_stage_code" => $work_stage_code),4); 

if($check_mandatory_stage_res['is_any_req_stage_not_ok']>0)
{
	$this->rollback();
$response_data['STATUS']='OK';  
$response_data['RESPONSE']='FAIL';
$response_data['MESSAGE']='Some Mandatory Stage Missing, Logout and login Thittam app and Try again. Stage Not Saved!!!';
$header_data['user_name'] = $decrypted_data_json->user_data['user_name'];
$header_data['response_data'] = $response_data;
$this->CreateHeader($header_data);
exit;
//return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
//exit;
}

}


if($work_stage_code=="14")
{
// CD Work Incomplete Error Solved.
$sql_find_work_add_incomplete="select a.work_type_flag,a.chainage_meter,a.cd_type_id,a.work_type_flag from works.t_works_additional_info as a left join  works.t_scheme_work_physical_progress as b on a.work_id=b.work_id  and a.cd_work_no=b.cd_prot_workid and a.work_type_flag=b.cd_type_flag  and b.stage_id =15 where a.cd_work_no is not null and a.work_id=:work_id and b.work_id is null "; 
$sql_find_work_add_incomplete_res = $this->prepare($sql_find_work_add_incomplete, array(":work_id" => $work_id),2); 

if(count($sql_find_work_add_incomplete_res)!=0)
{
$response_data['STATUS']='OK';  
$response_data['RESPONSE']='FAIL';
$response_data['MESSAGE']='CD Work Incomplete';
$header_data['user_name'] = $decrypted_data_json->user_data['user_name'];
$header_data['response_data'] = $response_data;
$this->CreateHeader($header_data);
exit;
//return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
//exit;
}	


// Check Group Works Completed 

$sql_find_work_add_incomplete="select a.work_type_flag,a.chainage_meter,a.cd_type_id,a.work_type_flag from works.t_works_additional_info as a left join  works.t_scheme_work_physical_progress as b on a.work_id=b.work_id  and a.work_type_link_id=b.work_type_link_id and b.stage_id =15 where a.work_type_link_id is not null  and a.is_group_work='Y' and a.work_id=:work_id and b.work_id is null "; 
$sql_find_work_add_incomplete_res = $this->prepare($sql_find_work_add_incomplete, array(":work_id" => $work_id),2); 

if(count($sql_find_work_add_incomplete_res)!=0)
{
	$this->rollback();
$response_data['STATUS']='OK';  
$response_data['RESPONSE']='FAIL';
$response_data['MESSAGE']='Group Work Incomplete';
$header_data['user_name'] = $decrypted_data_json->user_data['user_name'];
$header_data['response_data'] = $response_data;
$this->CreateHeader($header_data);
exit;
//return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
//exit;
}	


}

if($insert_phy_progress['group_work_type']=='M')
{
$CHECK_UPD_INS_STATUS="select * from works.t_scheme_work_physical_progress as a where work_id=:work_id and (stage_id=:work_stage_code) and cd_prot_workid=0  and group_work_type='M'";  
}
else
{	
$CHECK_UPD_INS_STATUS="select * from works.t_scheme_work_physical_progress as a where work_id=:work_id and (stage_id=:work_stage_code) and cd_prot_workid=0 ";  	
}
$CHECK_UPD_INS_STATUS_RES = $this->prepare($CHECK_UPD_INS_STATUS, array(":work_id" => $work_id, ":work_stage_code" => $work_stage_code),2);	


if(count($CHECK_UPD_INS_STATUS_RES)!=0)
{
	$this->rollback();
$response_data['STATUS']='OK';  
$response_data['RESPONSE']='FAIL';
$response_data['MESSAGE']='STAGE ALREADY EXISTS';
$header_data['user_name'] = $decrypted_data_json->user_data['user_name'];
$header_data['response_data'] = $response_data;
$this->CreateHeader($header_data);
exit;
//return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
//exit;
}


}	
$path_to_save='';
$Base_path = $this->getStoragePath() . "Document/work/work_monitoring_image";
$Temp_Base_path = $Base_path . '/' . $dcode . '/' . $lbcode . '/';	
$path_to_save=$Temp_Base_path;

if (!file_exists($path_to_save.'/'.$finyear)) 
{
mkdir($path_to_save.'/'.$finyear,0777,true);	
}	

if($is_group_work!="" && $group_work_type=="S")
{
$file = "group_work_stage_" . $work_id . '_' .$is_group_work.'_'. $work_stage_code .'_'.date("Y_m_d_H_i_s").'.jpg'; 	
}
else if($cd_work_no!="")
{
$file = "cd_work_stage_" . $work_id . '_' .$cd_work_no.'_'. $work_stage_code .'_'.date("Y_m_d_H_i_s").'.jpg'; 	
}
else
{	
$file = "main_work_stage_" . $work_id . '_' . $work_stage_code .'_'.date("Y_m_d_H_i_s"). '.jpg'; 
}

     $dirnam = $path_to_save.'/'.$finyear.'/'.$file; 	
 

$img_data2 = base64_decode($images); 	
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
$response_data['MESSAGE']='UNABLE TO SAVE IMAGE';
$header_data['user_name'] = $decrypted_data_json->user_data['user_name'];
$header_data['response_data'] = $response_data;
$this->CreateHeader($header_data);
exit;
//return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
//exit;
}	
$cd_work_no=0;
$getIpAddress = $this->getIpAddress();
if (file_exists($dirnam) && count($CHECK_UPD_INS_STATUS_RES)==0) {
$sel_in_qry="SELECT works.sp_scheme_work_physical_progress(
:scheme_group_id,
:scheme_id,
:fin_year,
:work_group_id,
:work_type_id,
:work_id,
:stage_id,
:user_name,
:ipaddress,
:file_url,
:cd_prot_workid,
:cd_type_flag,
:cd_chainage,
:photo_captured_latitude,
:photo_captured_longitude,
:work_type_link_id,
:works_additional_info_id,
:cd_work_no)";
$sel_qry_res=$this->prepare($sel_in_qry, array(
":scheme_group_id"=>$insert_phy_progress['scheme_group_id'],
":scheme_id"=>$insert_phy_progress['scheme_id'],
":fin_year"=>$insert_phy_progress['fin_year'],
":work_group_id"=>$insert_phy_progress['work_group_id'],
":work_type_id"=>$insert_phy_progress['work_type_id'],
":work_id"=>$work_id,
":stage_id"=>$work_stage_code,
":user_name"=>$user_name,
":ipaddress"=>$getIpAddress,
":file_url"=>$file,
":cd_prot_workid"=>$insert_phy_progress['cd_prot_workid'],
":cd_type_flag"=>$insert_phy_progress['cd_type_flag'],
":cd_chainage"=>$insert_phy_progress['cd_chainage'],
":photo_captured_latitude"=>$insert_phy_progress['photo_captured_latitude'],
":photo_captured_longitude"=>$insert_phy_progress['photo_captured_longitude'],
":work_type_link_id"=>0,
":works_additional_info_id"=>0,
":cd_work_no" =>$cd_work_no
),4);

$json_string = $sel_qry_res['sp_scheme_work_physical_progress'];

$response_data = json_decode($json_string, true);

//$response_data = json_decode($sel_qry_res, true);	

if($response_data['RESPONSE'] == 'SUCCESS'){
$ins_rec++;
}
}
else
{
	$this->rollback();
$response_data['STATUS']='OK';  
$response_data['RESPONSE']='FAIL';
$response_data['MESSAGE']='UNABLE TO SAVE IMAGE OR STAGE ALREADY EXISTS';
$header_data['user_name'] = $decrypted_data_json->user_data['user_name'];
$header_data['response_data'] = $response_data;
$this->CreateHeader($header_data);
exit;
//return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
//exit;
}	
}	
if(count($decrypted_data_json->track_data)==$ins_rec)
{
$this->commit();

$response_data['STATUS']='OK';
$response_data['RESPONSE']='OK';
$response_data['JSON_DATA']="SUCCESS"; 	
}
else
{
	$this->rollback();
$response_data['STATUS']='OK';
$response_data['RESPONSE']='ERROR';
$response_data['MESSAGE']='Unable to Save Data';

}
$header_data['user_name'] = $decrypted_data_json->user_data['user_name'];	
$header_data['response_data'] = $response_data;
$this->CreateHeader($header_data);
exit;
//return json_encode(array('enc_data'=>AesCipher::encrypt($this->app_key,"",json_encode($response_data))));
}

public 	function work_phy_stage_image($decrypted_data_json)
{


if(isset($decrypted_data_json->user_data['dcode']) && $decrypted_data_json->user_data['dcode']!="")
{
$dcode=$decrypted_data_json->user_data['dcode'];
}
else
{
$dcode=$decrypted_data_json->dcode;
}

if(isset($decrypted_data_json->user_data['lbcode']) && $decrypted_data_json->user_data['lbcode']!="")
{
$lbcode=$decrypted_data_json->user_data['lbcode'];
}
else
{
$lbcode=$decrypted_data_json->lbcode;
}

$work_id=$decrypted_data_json->work_id;  	
$Base_path = $this->getStoragePath() . "Document/work/work_monitoring_image/";
$Temp_Base_path = $Base_path . '/' . $dcode . '/' . $lbcode . '/';

$path_to_save=$Temp_Base_path; 
$data_return=array();    
$data_return["work_phy_stage"]=array();	  

// $work_id_phy="SELECT a.dcode, a.lbcode, a.fin_year, a.work_id, b.stage_id, c.work_stage_name, b.file_url as image, case when b.file_url is not null and file_url<>'null' then 'Y' else 'N' end image_available FROM master.view_workdetails as a left join works.t_scheme_work_physical_progress as b on a.work_id=b.work_id left join master.m_work_stage_link as c on b.stage_id=c.work_stage_id and c.work_group_id=a.work_group_id and c.work_id=a.work_id WHERE a.dcode=:dcode and lbcode=:lbcode and a.work_id=:work_id and cd_prot_workid=0 and (b.group_work_type is null or b.group_work_type='M');";


$work_id_phy="SELECT a.dcode, a.lbcode, a.fin_year, a.work_id, b.stage_id, b.file_url as image, case when b.file_url is not null and file_url<>'null' then 'Y' else 'N' end image_available,d.stage_name as work_stage_name FROM master.view_workdetails as a 
left join 
works.t_scheme_work_physical_progress as b 
on a.work_id=b.work_id 
left join 
master.m_work_stage_link as c 
on b.stage_id=c.work_stage_id and c.work_group_id=a.work_group_id and c.work_id=a.work_id
left join
master.m_stage as d
on b.stage_id = d.stage_id  WHERE a.dcode=:dcode and lbcode=:lbcode and a.work_id=:work_id";

$work_phy_stage = $this->prepare($work_id_phy, array(":dcode" => $dcode, ":lbcode" =>$lbcode, ":work_id" =>$work_id), 2);   

foreach($work_phy_stage as $key=>$work_phy_stage_val){

if($work_phy_stage_val['image_available']=='Y' ){
$path_to_save_file=$path_to_save."/".$work_phy_stage_val['fin_year']."/".$work_phy_stage_val['image'];
if(file_exists($path_to_save_file))
{
$work_phy_stage[$key]['image']=$this->getDataURI_RAW($path_to_save_file);
}
else
{
$work_phy_stage[$key]['image']="";
$work_phy_stage[$key]['image_available']='N';
}
}
else
{
$work_phy_stage[$key]['image']="";
}
}  

if(count($work_phy_stage)>0)
{
$data_return["work_phy_stage"]=$work_phy_stage;
}
else
{
$data_return["work_phy_stage"]=array();
}
   

$response_data['STATUS']='OK';
$response_data['RESPONSE']='OK';
$response_data['JSON_DATA']=$data_return["work_phy_stage"]; 
$header_data['user_name'] = $decrypted_data_json->user_data['user_name'];	
$header_data['response_data'] = $response_data;
$this->CreateHeader($header_data);
exit;
}
public function getDataURI_RAW($imagePath) {
	//echo 	$finfo = new finfo(FILEINFO_MIME_TYPE);
	//echo	$type = $finfo->file($imagePath);
		return base64_encode(file_get_contents($imagePath));
	}

	

}
$service_login = new service_login();

?>