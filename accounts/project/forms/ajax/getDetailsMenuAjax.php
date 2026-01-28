<?php 
require_once  __DIR__ . '/../config/configPublic.php';
require_once __DIR__ . '/../templates/HtmlHelper.php';





class getDetailsMenuAjax extends ConfigClass
{

	function __construct(){
		
		
		
		
	}	
	
	public function getSubMenuEdit($menuid){
		
		
		$get_menu_list="select * from master.mst_menu_development where submenuid=:menuid and del_flag is null order by menu_order_no desc";
		$result = $this->prepare($get_menu_list,array(":menuid"=>$menuid),2);
		if(count($result)==0){
			return NULL;
		} else {	
    	$str="<ul>";
    
    	foreach($result as $menu_list_key => $menu_list_row){
			$menuid=$menu_list_row["menuid"];
			$menu_desc=$menu_list_row["menu_desc"];
			$menu_no=$menu_list_row["menu_no"];
			$submenu= $this->getSubMenuEdit($menuid);
			
			if($submenu==NULL){

    		$str.="<li><a onClick='manageMenu(".$menuid.")'>$menu_no - $menu_desc</a></li>";
			
			} else {
    		$str.="<li><a onClick='manageMenu(".$menuid.")'>$menu_no - $menu_desc </a>$submenu</li>";
		
			}
		}		
    	$str.="</ul>";
		return $str;
		}			
			
	}
	
	public function getMenuno($menuid="",$str='',$flag=""){

		$get_menu_list="select submenuid,menu_sl_no from master.mst_menu_development where menuid=:menuid and del_flag is null";
		$result = $this->prepare($get_menu_list,array(":menuid"=>$menuid),4);
		if(!isset($result['submenuid'])){
			return NULL;
		} else {
		$menuid=$result['submenuid'];
		$menu_sl_no=$result['menu_sl_no'];			
		$str=$menu_sl_no.$str;	
		$menu_slno='';
		
		$get_menuslno=$this->getMenuno($menuid,$str,'Y');
		if($get_menuslno != NULL){	
		$menu_slno= $get_menuslno;
		}
		
		if($menu_slno!=''){
			$str=$get_menuslno;
		}
		
		return $str;
		}			
			
	}
	
	public function getSubMenuConfig($menuid){
		$get_menu_list="select * from master.mst_menu_development where submenuid =:menuid and del_flag is null order by menu_order_no desc";
		$result = $this->prepare($get_menu_list,array(":menuid"=>$menuid),2);
		if(count($result)==0){
			return NULL;
		} else {
			$str="<ul>";
    	foreach($result as $menu_list_key => $menu_list_row){
			$smenuid=$menu_list_row["menuid"];
			$desc=$menu_list_row["menu_desc"];
			$url=trim($menu_list_row["url"]);
			$menu_no=$menu_list_row["menu_no"];

			$submenu= $this->getSubMenuConfig($smenuid);
			
			if($submenu==NULL){

    		$str.="<li><a><input type='checkbox' value='".$smenuid.",".$menuid."' onChange='checkParent(this)'> $menu_no - $desc</a></li>";
			
			} else {
    		$str.="<li><a><input type='checkbox' value='".$smenuid.",".$menuid."' onChange='checkParent(this)'>  $menu_no - $desc</a>$submenu</li>";		
			}			
		}	
		$str.="</ul>";	
		return $str;	
		}
	}

	public function displayContent($post_data_array){		
		$mode=base64_decode($post_data_array['mode']);
		$mode_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'text',
			'Field_Value'=>$mode,
			'Field_Name'=>'mode',
			'Field_Max_Length'=>25,
			'Field_Label_Name'=>'mode ID'
			)
		);			
		if ($mode_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "mode",
				"MESSAGE" => "Invalid mode ID"
			));
			exit;			
		}	
		if($mode == 'menuModify'){		
			$get_menu_list="select * from master.mst_menu_development where submenuid=:submenuid and del_flag is null order by report_form_no::integer asc";
			$result = $this->prepare($get_menu_list,array(":submenuid"=>0),2);	
			$str="<div align='center' ><h4>Click on any menu to Add Sub-Menu / Edit / Remove... </h4></div><br />";
			$str.="<table align='center'><tr><td>";
			$str.="<ul class='sf-menu'>";	
			if(count($result)>0){
				foreach($result as $menu_list_key => $menu_list_row){
					$menuid=$menu_list_row["menuid"];
					$menu_desc=$menu_list_row["menu_desc"];
					$menu_no=$menu_list_row["menu_no"];
		
					$submenu= $this->getSubMenuEdit($menuid);
					if($submenu==NULL){
		
					$str.="<li><a onClick='manageMenu(".$menuid.")'>$menu_no - $menu_desc</a></li>";  
		
					} else {
					$str.="<li><a onClick='manageMenu(".$menuid.")'>$menu_no - $menu_desc</a>$submenu</li>";        		
					}
				}
			}
	
		$str.="<li><a onclick='addNewMenu(0)'><span style='color:#03967D'> + Add New</span></a></li>";
		$str.="</ul>";
		$str.="</td></tr></table>";	
			
		}
	else if($mode == 'menuRoleConfig'){				
		$str="<div class='row'><div class='col-md-12'><div class='text-center'><h4>Select role and Menus options </h4></div></div><br><br>";		
		$str.="<div class='col-md-12'><div class='row'><div class='col-md-6'>";
		$str.="<div class='form-group row'><label class='col-sm-6 col-form-label'><b>Role</b> </label><div class='col-sm-6'> ";
		$str.= "<select id='roleid' class='form-control form-control-sm'>";	
		$str.= "<option value='' >Select Role</option>";	
		$sel_role="select role_code,role_name,role_desc,role_type_name from security.m_role where del_flag is null order by role_name";
		$sel_role_res = $this->prepare($sel_role,array(),2);
		foreach($sel_role_res as $sel_role_key=>$sel_role_row){
			$str.= "<option value='".$sel_role_row["role_code"]."' data-roletype='".$sel_role_row["role_type_name"]."'>".$sel_role_row["role_name"]."</option>";
		}
		$str.= " </select></div></div>";				
		$str.="<div class='form-group row' id='dcode_div' style='display:none;'><label class='col-sm-6 col-form-label'><b>District</b></label> <div class='col-sm-6 form-inline'> ";
		$str.= "<select id='dcode' name='dcode[]' class='form-control form-control-sm' multiple='multiple' style='height: 145px;width:180px;'>";	
		//$str.= "<option value='' >Select District Name</option>";		
		$sel_dist_detail="SELECT state_code,dcode,district_name_en FROM master.m_district where state_code=:state_code ORDER BY dcode";
		$sel_dist_detail_res = $this->prepare($sel_dist_detail,array(":state_code"=>'33'),2);
		foreach($sel_dist_detail_res as $sel_dist_detail_key=>$sel_dist_detail_row){
			$str.= "<option  value='".$sel_dist_detail_row["dcode"]."' >".$sel_dist_detail_row["district_name_en"]."</option>";
		}
		$str.= " </select><input type='checkbox' id='chk_all_dist'  name='chk_all_dist' value='Y' class='ml-3' />All District</div></div>";		
		$str.="<div class='form-group row' id='lbcode_div' style='display:none;'><label class='col-sm-6 col-form-label'><b>Town Panchayat</b></label><div class='col-sm-6 form-inline'> ";
		$str.= "<select id='lbcode' name='lbcode[]' class='form-control form-control-sm' multiple='multiple' style='height: 145px;width:180px;'>";
		//$str.= "<option value='' >Select Town Panchayat</option>";
		$str.= " </select><input type='checkbox' id='chk_all_village'  name='chk_all_village' value='Y' class='ml-2' />All Town Panchayat</div></div>";		
		$str.="<div class='form-group row' id='enable_disable_div' style='display:none;'><div class='col-sm-6'></div><div class='col-sm-6 text-left'><input type='radio' id='enable' name='rad_enable_disable' value='1'/>&nbsp;&nbsp;Enable&nbsp;&nbsp;&nbsp;<input type='radio' id='disable' name='rad_enable_disable' value='0'/>&nbsp;&nbsp;Disable</div></div>";		
		$str.="<div class='form-group row'><div class='col-sm-12 text-center'><input type='button' value='Assign Menus' onClick='assignMenus()' class='btn btn-sm btn-success'></div></div>";
		$str.= "</div>";		
		$get_menu_list="select * from master.mst_menu_development where submenuid=:submenuid and del_flag is null order by menu_order_no desc";
		$result = $this->prepare($get_menu_list,array(":submenuid"=>0),2);		
		if(count($result)>0){
			$menuscript="<ul class='sf-view' id='chcekmenu'>";
			foreach($result as $menu_list_key => $menu_list_row){
  				$desc=trim($menu_list_row["menu_desc"]);
  				$url=trim($menu_list_row["url"]); 
  				$menuid=$menu_list_row["menuid"];	
				$menu_no=$menu_list_row["menu_no"];	 				
				$submenu= $this->getSubMenuConfig($menuid);	
				if($submenu==NULL){
	 				$menuscript.="<li><a> <input type='checkbox' value='".$menuid.",0' onChange='checkParent(this)'> $menu_no - $desc</a></li>"; //$url
				} else {
					$menuscript.="<li><a> <input type='checkbox' value='".$menuid.",0' onChange='checkParent(this)'> $menu_no - $desc</a>$submenu</li>";	
				}
			}
			$menuscript.="</ul>";
		} else {
			$menuscript="Menu Table is Empty !";
		}
		$temp="<a class='link3' >Click Here to : </a>[ <a class='link3' onClick='checkAll(1)'>Check All</a>]
				   [ <a class='link3' onClick='checkAll(0)'>UnCheck All</a>]";
		$str.="<div class='col-md-6'><table align='center' class='table table-borderless' border=0>
		<tr><td align='right' bgcolor='#1a5901 '>$temp</td></tr>";		
		$str.="<tr><td>$menuscript</td></tr>	</table>";		
		$str.="</div></div></div></div>";									
	}
			
			echo json_encode(array(
				"STATUS" => "SUCCESS", 
				"DATA" => $str
			));
			exit;	
	}
	
	public function addNewMenu($post_data_array){
		$menuid=base64_decode($post_data_array['menuid']);
		$menuid_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$menuid,
			'Field_Name'=>'menuid',
			'Field_Label_Name'=>'Menu ID'
			)
		);			
		if ($menuid_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "menuid",
				"MESSAGE" => "Invalid Menu ID"
			));
			exit;			
		}
		$sql_order="select max(menu_order_no) from master.mst_menu_development";
		$result_order = $this->prepare($sql_order,array(),4);
		$menu_order_no=$result_order['max']+1;
		
		$sql_report="select max(report_form_no) from master.mst_menu_development";
		$result_report = $this->prepare($sql_report,array(),4);
		$report_form_no=$result_report['max']+1;
				
		$str="<table class='table table-bordered'>
				<tr><th bgcolor='#9cedc4' colspan='2' >ADD MENU </th></tr>
				<tr><td class='w-50 text-left'>Menu Name</td>	<td><input type='text' id='menu_desc' value='' class='form-control form-control-sm'/> </td></tr>	
				<tr><td class='w-50 text-left'>Menu Name (Local Language)</td>	<td><input type='text' id='menu_desc_ta' value='' class='form-control form-control-sm'/> </td></tr>	
				<tr><td class='text-left'> URL</td> <td><input type='text' id='menu_url' value='' class='form-control form-control-sm' /></td> </tr>
				<tr><td class='text-left'> Table/View/Stored Procedure Name</td> <td><input type='text' id='table_name' value='' class='form-control form-control-sm' /></td></tr>";
		$str.="<tr><td class='text-left'>Programmer Name</td> <td style='text-align:left;'>";
		$str.= "<select id='who_sec_code_added' class='form-control form-control-sm'>";	
		$str.= "<option value='' >Select Programmer Name</option>";		
		$sql="select programmer_id, programmer_name from master.m_programmer WHERE current_employee=:cur_emp order by programmer_id";
		$res = $this->prepare($sql,array(":cur_emp"=>'Y'),2);
		foreach($res as $res_key=>$res_row){
			$str.= "<option value='".$res_row["programmer_id"]."' >".$res_row["programmer_name"]."</option>";
		}
		$str.= " </select></td></tr>";				
				
		$str.=" <tr><td class='text-left'>Menu Update Date</td> <td><input type='text' id='last_modify_date' value='' class='form-control form-control-sm' /></td></tr>
				<tr><td class='text-left'>Menu Order Number</td>	<td><input type='text' id='menu_order_no' value='".$menu_order_no."' class='form-control form-control-sm number_field'/></td></tr>";
		$str.="<tr><td class='text-left'>Form - F Report - R</td> <td style='text-align:left;'>";
		$str.= "<select id='report_form_flag' class='form-control form-control-sm'><option value='' >Select Type</option><option value='F'>Form</option><option value='R'>Report</option></select></td></tr>";	
						
		$str.= "<tr><td class='text-left'>Form No/Report No Name</td> <td><input type='text' id='report_form_no' value='".$report_form_no."' class='form-control form-control-sm number_field' readonly/></td></tr>
				<tr><td class='text-left'>Flag ON/ OFF Name</td> <td class='text-left'><input name='rad_on_off' id='rad_on' type='radio' value='1' />&nbsp;On&nbsp;&nbsp;<input name='rad_on_off' id='rad_off' type='radio' value='0' />&nbsp;Off</td></tr>
				<tr><td class='text-left'>Purpose of Form or Report</td> <td><textarea rows='2' cols='30' name='purpose_of_form_or_report' id='purpose_of_form_or_report' class='form-control' /></textarea></td></tr>";
		$str.="<tr><td class='text-left'>Module</td> <td style='text-align:left;'>";
		$str.= "<select id='module_id' class='form-control form-control-sm'>";	
		$str.= "<option value='' >Select Module Name</option>";		
		$sql="select module_id,module_name from master.m_module order by module_id";
		$res = $this->prepare($sql,array(),2);
		foreach($res as $res_key=>$res_row){
			$str.= "<option value='".$res_row["module_id"]."' >".$res_row["module_name"]."</option>";
		}
		$str.= " </select></td></tr>";
		$str.="<tr><td class='text-left'>Responsive Support</td>	<td class='text-left'><select id='responsive_support' class='form-control form-control-sm'><option value='' >Select Type</option><option value='A'>All</option><option value='M'>Select App</option><option value='W'>Select Web</option></select></td></tr> 
			</tr>
			<input type='hidden' id='submenuid' value='".$menuid."'></input>";
			
		$str.='<tr><th bgcolor="#9cedc4" colspan="2">			
				[ <a class="link3" id="ladd" onClick="addMenu()">ADD </a>]
				[ <a class="link3" id="ledit" onClick="funCancel('."'div_newmenu'".')"> CANCEL </a>]	</td></tr></table>';	
				
		//return $str;	
		$Result_Data['STATUS']=	"SUCCESS";
		$Result_Data['DATA']=$str;
		
		return json_encode($Result_Data);					
	}
	
	public function addMenu($post_data_array){
		
		$submenuid=base64_decode($post_data_array['submenuid']);
		$submenuid_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$submenuid,
			'Field_Name'=>'submenuid',
			'Field_Label_Name'=>'Sub Menu ID'
			)
		);			
		if ($submenuid_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "submenuid",
				"MESSAGE" => "Invalid Sub Menu ID"
			));
			exit;			
		}
		$menudesc=base64_decode($post_data_array['menudesc']);
		
		$menudesc=(trim($menudesc)=='')? 'Menu':$menudesc;
		$menudesc_ta=urldecode(base64_decode($post_data_array['menudesc_ta']));
		
		$menudesc_ta=(trim($menudesc_ta)=='')? 'Menu':$menudesc_ta;
		$menuurl=base64_decode($post_data_array['menuurl']);
		$menuurl=(trim($menuurl)=='')? null :$menuurl;
		
		
		$table_name=base64_decode($post_data_array['table_name']);
		$table_name=(trim($table_name)=='')? null: $table_name;
		
		$table_name_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'table_name',
			'Field_Value'=>$table_name,
			'Field_Name'=>'table_name',
			'Field_Label_Name'=>'Table Name'
			)
		);			
		if ($table_name_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "table_name",
				"MESSAGE" => "Invalid Table Name"
			));
			exit;			
		}
		
		$who_sec_code_added=base64_decode($post_data_array['who_sec_code_added']);
		$sec_code_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$who_sec_code_added,
			'Field_Name'=>'who_sec_code_added',
			'Field_Label_Name'=>'Who sec code '
			)
		);			
		if ($sec_code_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "who_sec_code_added",
				"MESSAGE" => "Invalid who_sec_code_added"
			));
			exit;			
		}
		$who_sec_code_added=(trim($who_sec_code_added)=='')? null :$who_sec_code_added;	
		$menu_order_no=base64_decode($post_data_array['menu_order_no']);
		$menu_order_no_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$menu_order_no,
			'Field_Name'=>'menu_order_no',
			'Field_Label_Name'=>'Who sec code '
			)
		);			
		if ($menu_order_no_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "menu_order_no",
				"MESSAGE" => "Invalid menu_order_no"
			));
			exit;			
		}
		$menu_order_no=(trim($menu_order_no)=='')? null :$menu_order_no;
		$report_form_flag=base64_decode($post_data_array['report_form_flag']);
		$report_form_flag_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'text',
			'Field_Value'=>$report_form_flag,
			'Field_Name'=>'report_form_flag',
			'Field_Label_Name'=>'report_form_flag'
			)
		);			
		if ($report_form_flag_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "report_form_flag",
				"MESSAGE" => "Invalid report_form_flag"
			));
			exit;			
		}
		$report_form_flag=(trim($report_form_flag)=='')? null :$report_form_flag;
		$report_form_no=base64_decode($post_data_array['report_form_no']);
		$report_form_no_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$report_form_no,
			'Field_Name'=>'report_form_no',
			'Field_Label_Name'=>'report_form_no'
			)
		);			
		if ($report_form_no_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "report_form_no",
				"MESSAGE" => "Invalid report_form_no"
			));
			exit;			
		}
		$report_form_no=(trim($report_form_no)=='')? null :$report_form_no;
		$rad_on_off=base64_decode($post_data_array['rad_on_off']);
		
		$rad_on_off=(trim($rad_on_off)=='')? null :$rad_on_off;
		$purpose_of_form_or_report=base64_decode($post_data_array['purpose_of_form_or_report']);
		$purpose_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'text_number_space',
			'Field_Value'=>$purpose_of_form_or_report,
			'Field_Name'=>'purpose_Validation',
			'Field_Label_Name'=>'purpose_Validation'
			)
		);			
		if ($purpose_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "purpose_Validation",
				"MESSAGE" => "Invalid purpose_Validation"
			));
			exit;			
		}
		$purpose_of_form_or_report=(trim($purpose_of_form_or_report)=='')? null :$purpose_of_form_or_report;
		$module_id=base64_decode($post_data_array['module_id']);
		$module_id_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$module_id,
			'Field_Name'=>'module_id',
			'Field_Label_Name'=>'module_id'
			)
		);			
		if ($module_id_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "module_id",
				"MESSAGE" => "Invalid module_id"
			));
			exit;			
		}
		$module_id=(trim($module_id)=='')? null :$module_id;
		$responsive_support=base64_decode($post_data_array['responsive_support']);
		$responsive_support_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'text_number',
			'Field_Value'=>$responsive_support,
			'Field_Name'=>'responsive_support',
			'Field_Label_Name'=>'responsive_support'
			)
		);			
		if ($responsive_support_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "responsive_support",
				"MESSAGE" => "Invalid responsive support"
			));
			exit;			
		}
		$responsive_support=(trim($responsive_support)=='')? null :$responsive_support;
		$date_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'date',
			'Field_Value'=>base64_decode($post_data_array['last_modify_date']),
			'Field_Name'=>'date',
			'Field_Label_Name'=>'Date',
			 'Field_Format'=>'dd-mm-yyyy'
			)
		);			
		if ($date_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "date",
				"MESSAGE" => "Invalid Date"
			));
			exit;			
		}
		list($date_licence,$month_licence,$year_licence)=explode('-',base64_decode($post_data_array['last_modify_date']));
		
		$last_modify_date=$year_licence.'-'.$month_licence.'-'.$date_licence;
		$last_modify_date=(trim($last_modify_date)=='')? null :$last_modify_date;

        $user_name = $this->getCurrentUser();
        $ip_address = $this->getIpAddress();
		$date = $this->getCurrentDate();
						
		$sql="select max(menuid) from master.mst_menu_development";
		$result = $this->prepare($sql,array(),4);
		$mid=$result['max']+1;
		
		$menu_slno_qry="select max(menu_sl_no) from master.mst_menu_development where submenuid=:submenuid";
		$res_menu_slno = $this->prepare($menu_slno_qry,array(":submenuid"=>$submenuid),4);
		$menu_sl_no=$res_menu_slno['max']+1;
		
		$sql="insert into master.mst_menu_development (menuid,submenuid,menu_desc,menu_desc_ta,url,rflag,report_no,menu_order_no,report_form_no,responsive_support,module_id,table_name,last_modify_date,who_sec_code_added,purpose_of_form_or_report,menu_sl_no,ins_username,ins_ipaddress,ins_date) values (:mid,:submenuid,:menudesc,:menudesc_ta,:menuurl,:rad_on_off,:report_form_flag,:menu_order_no,:report_form_no,:responsive_support,:module_id,:table_name,:last_modify_date,:who_sec_code_added,:purpose_of_form_or_report,:menu_sl_no,:user_name,:ip_address,:date)";
		$result = $this->prepare($sql,array(":mid"=>$mid,":submenuid"=>$submenuid,":menudesc"=>$menudesc,":menudesc_ta"=>$menudesc_ta,":menuurl"=>$menuurl,":rad_on_off"=>$rad_on_off,":report_form_flag"=>$report_form_flag,":menu_order_no"=>$menu_order_no,":report_form_no"=>$report_form_no,":responsive_support"=>$responsive_support,":module_id"=>$module_id,":table_name"=>$table_name,":last_modify_date"=>$last_modify_date,":who_sec_code_added"=>$who_sec_code_added,":purpose_of_form_or_report"=>$purpose_of_form_or_report,":menu_sl_no"=>$menu_sl_no,":user_name"=>$user_name,":ip_address"=>$ip_address,":date"=>$date),4);

		$sql="select max(menuid) from master.mst_menu_development";
		$result = $this->prepare($sql,array(),4);
		$menu_id=$result['max'];
				
		$menu_no= $this->getMenuno($menu_id,'','N');
		$sql="update master.mst_menu_development set menu_no=:menu_no,upd_username=:user_name,upd_ipaddress=:ip_address,upd_date=:date where menuid=:menu_id";
		$result = $this->prepare($sql,array(":menu_no"=>$menu_no,":user_name"=>$user_name,":ip_address"=>$ip_address,":date"=>$date,":menu_id"=>$menu_id),4);
		
		if (!isset($result->errorInfo)) {
			$str="Saved";
		} else {
			$str="Error";
		}
		
		//return $str;
		$Result_Data['STATUS']=	"SUCCESS";
		$Result_Data['DATA']=$str;
		
		return json_encode($Result_Data);
	}
	
	public function getMenuDetails($post_data_array){
		$menuid=base64_decode($post_data_array['menuid']);
		$menuid_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$menuid,
			'Field_Name'=>'menuid',
			'Field_Label_Name'=>'Menu ID'
			)
		);			
		if ($menuid_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "menuid",
				"MESSAGE" => "Invalid Menu ID"
			));
			exit;			
		}
		$menuid_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$menuid,
			'Field_Name'=>'menuid',
			'Field_Label_Name'=>'Menu ID'
			)
		);			
		if ($menuid_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "menuid",
				"MESSAGE" => "Invalid Menu ID"
			));
			exit;			
		}
		$get_menu_list="select * from master.mst_menu_development where menuid=:menuid and del_flag is null";
		$result = $this->prepare($get_menu_list,array(":menuid"=>$menuid),4);	
		
			$str="<br> <table align='center' width='600px'><tr><th colspan='4' >MENU DETAILS</th></tr>
			<tr bgcolor='#9cedc4'><th>Menu</th><th>Menu (Local language)</th><th>URL</th>	<th>Menu Order no</th> <th>SUB MENUS</th><th>Description</th><th>Remarks</th></tr>";
			$menuid=$result['menuid'];
			$smd=$this->getSubMenus($menuid);
			if($smd==NULL){
				$smdt="NULL";
			} else {
				$smdt=$smd;
			}
			$str.="<tr align='center'>
					<td>".$result['menu_desc']."</td>	
					<td>".$result['menu_desc_ta']."</td>	
					<td>".$result['url']."</td> 
					<td>".$result['menu_order_no']."</td>
					<td>".$smdt."</td>
					<td>".$result['description']."</td>
					<td>".$result['remarks']."</td>
					</tr>";												

		if(trim($result['url'])==''){
			$addsubmenulink='[ <a class="link3" id="ladd" onClick="addNewMenu('.$menuid.')">ADD SUB MENU</a>]';
		} else {
			$addsubmenulink='[ <a class="link3" id="ladd" onClick="javascript:alert('."'Please Check it has direct link (url:".$result['url'].")'".') ">ADD SUB MENU</a>]';	
		}
		
		if($smd==NULL){
			$str.='<tr><th bgcolor="#9cedc4" colspan="6">	
				'.$addsubmenulink.'		
				[ <a class="link3" id="ledit" onClick="editMenu('.$menuid.')">EDIT</a>]	
				[ <a class="link3" id="ldel" onClick="var r = confirm('."'Are you sure want to delete'".');if (r == true) { deleteMenu('.$menuid.'); }">DELETE</a>]</th></tr>';
		}else{
			$str.='<tr><th bgcolor="#9cedc4" colspan="6">			
				'.$addsubmenulink.'		
				[ <a class="link3" id="ledit" onClick="editMenu('.$menuid.')">EDIT</a>]	
		[ <a class="link3" id="ldel" onClick="javascript:alert('."'Please check it has sub menus so unable to delete it'".')">DELETE</a>]</th></tr>';
		}
		
		$str.="<tr><td colspan='6'><br><div align='center' id='edit_div_res' ><td></tr></div>";
		
		//return $str;	
		$Result_Data['STATUS']=	"SUCCESS";
		$Result_Data['DATA']=$str;
		
		return json_encode($Result_Data);	
	}
	
	public function getSubMenus($menuid){
		$get_menu_list="select * from master.mst_menu_development where submenuid=:menuid and del_flag is null";
		$result = $this->prepare($get_menu_list,array(":menuid"=>$menuid),2);	
		if(count($result)>0){
		$str="";	
		foreach($result as $menu_list_key => $menu_list_row){
			$str.=$menu_list_row['menu_desc']."<br>";
		}
		return $str;
		
		}else{
			return NULL;
		}
	}
	
	public function editMenu($post_data_array){
		$menuid=base64_decode($post_data_array['menuid']);
		$menuid_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$menuid,
			'Field_Name'=>'menuid',
			'Field_Label_Name'=>'Menu ID'
			)
		);			
		if ($menuid_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "menuid",
				"MESSAGE" => "Invalid Menu ID"
			));
			exit;			
		}
		$get_menu_list="select * from master.mst_menu_development where menuid=:menuid and del_flag is null";
		$result = $this->prepare($get_menu_list,array(":menuid"=>$menuid),4);		
		$smd=$this->getSubMenus($menuid);
		$str="";
		list($year_licence,$month_licence,$date_licence)=explode('-',$result['last_modify_date']);
		$last_modify_date=$date_licence.'-'.$month_licence.'-'.$year_licence;
		$str="<table class='table table-bordered'>
				<tr><th bgcolor='#9cedc4' colspan='2' >ADD MENU </th></tr>
				<tr><td class='w-50 text-left'>Menu Name</td>	<td><input type='text' id='menu_desc' value='".$result['menu_desc']."' class='form-control form-control-sm'/> </td>
				<tr><td class='w-50 text-left'>Menu Name (Local Language)</td>	<td><input type='text' id='menu_desc_ta' value='".$result['menu_desc_ta']."' class='form-control form-control-sm'/> </td></tr>";	
		if($smd==NULL){		
		$str.="<tr><td class='text-left'> URL</td> <td><input type='text' id='menu_url' value='".$result['url']."' class='form-control form-control-sm' /></td> </tr>";
		} else {
		$str.="<input type='hidden' id='menu_url' value='".$result['url']."' class='form-control form-control-sm' />";
		}
		$str.="
				<tr><td class='text-left'> Table/View/Stored Procedure Name</td> <td><input type='text' id='table_name' value='".$result['table_name']."' class='form-control form-control-sm' /></td></tr>";
		$str.="<tr><td class='text-left'>Programmer Name</td> <td style='text-align:left;'>";
		$str.= "<select id='who_sec_code_added' class='form-control form-control-sm'>";	
		$str.= "<option value='' >Select Programmer Name</option>";		
		$sql="select programmer_id, programmer_name from master.m_programmer WHERE current_employee=:cur_emp order by programmer_id";
		$res = $this->prepare($sql,array(":cur_emp"=>'Y'),2);
		foreach($res as $res_key=>$res_row){
			$str.= "<option value='".$res_row["programmer_id"]."' >".$res_row["programmer_name"]."</option>";
		}
		$str.= " </select><script>document.getElementById('who_sec_code_added').value='".$result['who_sec_code_added']."';</script></td></tr>";				
				
		$str.=" <tr><td class='text-left'>Menu Update Date</td> <td><input type='text' id='last_modify_date' value='".$last_modify_date."' class='form-control form-control-sm' /></td></tr>
				<tr><td class='text-left'>Menu Order Number</td>	<td><input type='text' id='menu_order_no' value='".$result['menu_order_no']."' class='form-control form-control-sm number_field' /></td></tr>";
		$str.="<tr><td class='text-left'>Form - F Report - R</td> <td style='text-align:left;'>";
		$str.= "<select id='report_form_flag' class='form-control form-control-sm'><option value='' >Select Type</option><option value='F'>Form</option><option value='R'>Report</option></select><script>document.getElementById('report_form_flag').value='".$result['report_no']."';</script></td></tr>";	
						
		$str.= "<tr><td class='text-left'>Form No/Report No Name</td> <td><input type='text' id='report_form_no' value='".$result['report_form_no']."' class='form-control form-control-sm number_field' /></td></tr>
				<tr><td class='text-left'>Flag ON/ OFF Name</td> <td class='text-left'><input name='rad_on_off' id='rad_on' type='radio' value='1' ";
		if($result['rflag'] == 1){ $check='checked'; } else { $check=''; }
		$str.= "$check";		
		$str.=" />&nbsp;On&nbsp;&nbsp;<input name='rad_on_off' id='rad_off' type='radio' value='0' "; 
		if($result['rflag'] == 0){ $check='checked'; } else { $check=''; }
		$str.= "$check";
		$str.=" />&nbsp;Off</td></tr><tr><td class='text-left'>Purpose of Form or Report</td> <td><textarea rows='2' cols='30' name='purpose_of_form_or_report' id='purpose_of_form_or_report' class='form-control' >".$result['purpose_of_form_or_report']."</textarea></td></tr>";
		$str.="<tr><td class='text-left'>Module</td> <td style='text-align:left;'>";
		$str.= "<select id='module_id' class='form-control form-control-sm'>";	
		$str.= "<option value='' >Select Module Name</option>";		
		$sql="select module_id,module_name from master.m_module order by module_id";
		$res = $this->prepare($sql,array(),2);
		foreach($res as $res_key=>$res_row){
			$str.= "<option value='".$res_row["module_id"]."' >".$res_row["module_name"]."</option>";
		}
		$str.= " </select><script>document.getElementById('module_id').value='".$result['module_id']."';</script></td></tr>";
		$str.="<tr><td class='text-left'>Responsive Support</td>	<td class='text-left'><select id='responsive_support' class='form-control form-control-sm'><option value='' >Select Type</option><option value='A'>All</option><option value='M'>Select App</option><option value='W'>Select Web</option></select><script>document.getElementById('responsive_support').value='".$result['responsive_support']."';</script></td></tr> 
			</tr>";
		if($smd!=NULL){		
			$str.="<input type='hidden' id='submenuid' value='".$result["submenuid"]."'></input>";	
		}

			$str.=" <input type='hidden' id='menuid' value='".$menuid."'></input>".'
			
			<tr><th bgcolor="#9cedc4" colspan="2">			
				[ <a class="link3" id="ladd" onClick="updateMenu()">UPDATE</a>]
				[ <a class="link3" id="ledit" onClick="funCancel('."'edit_div_res'".')">CANCEL</a>]	</td></tr>';
						
		//return $str;
		$Result_Data['STATUS']=	"SUCCESS";
		$Result_Data['DATA']=$str;
		
		return json_encode($Result_Data);
	}
	
	public function updateMenu($post_data_array){
		$menuid=base64_decode($post_data_array['menuid']);
		$menuid_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$menuid,
			'Field_Name'=>'menuid',
			'Field_Label_Name'=>'Menu ID'
			)
		);			
		if ($menuid_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "menuid",
				"MESSAGE" => "Invalid Menu ID"
			));
			exit;			
		}
		$menudesc=base64_decode($post_data_array['menudesc']);
		
		$menudesc=(trim($menudesc)=='')? 'Menu':$menudesc;
		$menudesc_ta=urldecode(base64_decode($post_data_array['menudesc_ta']));
		$menudesc_ta=(trim($menudesc_ta)=='')? 'Menu':$menudesc_ta;
		$menuurl=base64_decode($post_data_array['menuurl']);
		$menuurl=(trim($menuurl)=='')? null :$menuurl;
		
		
		
		$table_name=base64_decode($post_data_array['table_name']);
		$table_name=(trim($table_name)=='')? null: $table_name;
		
		if($table_name!=null)
		{
			$table_name_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'table_name',
				'Field_Value'=>$table_name,
				'Field_Name'=>'table_name',
				'Field_Label_Name'=>'Table Name'
				)
			);	
			if ($table_name_Validation['Status'] == "Error") {
				echo json_encode(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "table_name",
					"MESSAGE" => "Invalid Table Name"
				));
				exit;			
			}
		}
		
		$who_sec_code_added=base64_decode($post_data_array['who_sec_code_added']);
		$sec_code_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$who_sec_code_added,
			'Field_Name'=>'who_sec_code_added',
			'Field_Label_Name'=>'Who sec code '
			)
		);			
		if ($sec_code_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "who_sec_code_added",
				"MESSAGE" => "Invalid who_sec_code_added"
			));
			exit;			
		}
		$who_sec_code_added=(trim($who_sec_code_added)=='')? null:$who_sec_code_added;	
		$menu_order_no=base64_decode($post_data_array['menu_order_no']);
		$menu_order_no_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$menu_order_no,
			'Field_Name'=>'menu_order_no',
			'Field_Label_Name'=>'Who sec code '
			)
		);			
		if ($menu_order_no_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "menu_order_no",
				"MESSAGE" => "Invalid menu_order_no"
			));
			exit;			
		}
		$menu_order_no=(trim($menu_order_no)=='')? null :$menu_order_no;
		$report_form_flag=base64_decode($post_data_array['report_form_flag']);
		$report_form_flag_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'text',
			'Field_Value'=>$report_form_flag,
			'Field_Name'=>'report_form_flag',
			'Field_Label_Name'=>'report_form_flag'
			)
		);			
		if ($report_form_flag_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "report_form_flag",
				"MESSAGE" => "Invalid report_form_flag"
			));
			exit;			
		}
		$report_form_flag=(trim($report_form_flag)=='')? null :$report_form_flag;
		$report_form_no=base64_decode($post_data_array['report_form_no']);
		$report_form_no_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$report_form_no,
			'Field_Name'=>'report_form_no',
			'Field_Label_Name'=>'report_form_no'
			)
		);			
		if ($report_form_no_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "report_form_no",
				"MESSAGE" => "Invalid report_form_no"
			));
			exit;			
		}
		$report_form_no=(trim($report_form_no)=='')? null :$report_form_no;
		$rad_on_off=base64_decode($post_data_array['rad_on_off']);
		
		$rad_on_off=(trim($rad_on_off)=='')? null :$rad_on_off;
		
		$purpose_of_form_or_report=base64_decode($post_data_array['purpose_of_form_or_report']);
		
		if($purpose_of_form_or_report!='')
		{
			$purpose_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'text_number_space',
				'Field_Value'=>$purpose_of_form_or_report,
				'Field_Name'=>'purpose_Validation',
				'Field_Label_Name'=>'purpose_Validation'
				)
			);			
	
			if ($purpose_Validation['Status'] == "Error") {
				echo json_encode(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "purpose_Validation",
					"MESSAGE" => "Invalid purpose_Validation"
				));
				exit;			
			}
		}
		
		$purpose_of_form_or_report=(trim($purpose_of_form_or_report)=='')? null :$purpose_of_form_or_report;
		$module_id=base64_decode($post_data_array['module_id']);
		$module_id_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$module_id,
			'Field_Name'=>'module_id',
			'Field_Label_Name'=>'module_id'
			)
		);			
		if ($module_id_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "module_id",
				"MESSAGE" => "Invalid module_id"
			));
			exit;			
		}
		$module_id=(trim($module_id)=='')? null :$module_id;
		$responsive_support=base64_decode($post_data_array['responsive_support']);
		$responsive_support_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'text_number',
			'Field_Value'=>$responsive_support,
			'Field_Name'=>'responsive_support',
			'Field_Label_Name'=>'responsive_support'
			)
		);			
		if ($responsive_support_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "responsive_support",
				"MESSAGE" => "Invalid responsive support"
			));
			exit;			
		}
		$responsive_support=(trim($responsive_support)=='')? null :$responsive_support;
		
        $user_name = $this->getCurrentUser();
        $ip_address = $this->getIpAddress();
		$date = $this->getCurrentDate();		
		$date_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'date',
			'Field_Value'=>base64_decode($post_data_array['last_modify_date']),
			'Field_Name'=>'date',
			'Field_Label_Name'=>'Date',
			 'Field_Format'=>'dd-mm-yyyy'
			)
		);			
		if ($date_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "date",
				"MESSAGE" => "Invalid Date"
			));
			exit;			
		}
		list($date_licence,$month_licence,$year_licence)=explode('-',base64_decode($post_data_array['last_modify_date']));
		$last_modify_date=$year_licence.'-'.$month_licence.'-'.$date_licence;
		$last_modify_date=(trim($last_modify_date)=='')? "null" :"'".$last_modify_date."'";

		$sql="update master.mst_menu_development set menu_desc=:menudesc,menu_desc_ta=:menudesc_ta,url=:menuurl,table_name=:table_name,who_sec_code_added=:who_sec_code_added ,last_modify_date=:last_modify_date,menu_order_no=:menu_order_no, report_no=:report_no, report_form_no=:report_form_no, rflag=:rflag,purpose_of_form_or_report=:purpose_of_form_or_report, module_id=:module_id, responsive_support=:responsive_support,upd_username=:user_name,upd_ipaddress=:ip_address, upd_date=:date where menuid=:menuid";		
		$result = $this->prepare($sql,array(":menudesc"=>$menudesc,":menudesc_ta"=>$menudesc_ta,":menuurl"=>$menuurl,":table_name"=>$table_name,":who_sec_code_added"=>$who_sec_code_added,":last_modify_date"=>$last_modify_date,":menu_order_no"=>$menu_order_no,":report_no"=>$report_form_flag,":report_form_no"=>$report_form_no,":rflag"=>$rad_on_off,":purpose_of_form_or_report"=>$purpose_of_form_or_report,":module_id"=>$module_id,":responsive_support"=>$responsive_support,":user_name"=>$user_name,":ip_address"=>$ip_address,":date"=>$date,":menuid"=>$menuid),4);
		
		if (!isset($result->errorInfo)) {
			$str="Saved";
		} else {
			$str="Error";
		}
		
		//return $str;
		$Result_Data['STATUS']=	"SUCCESS";
		$Result_Data['DATA']=$str;
		
		return json_encode($Result_Data);
				
	}
	
	public function deleteMenu($post_data_array){
		
		$menuid=base64_decode($post_data_array['menuid']);
		$menuid_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$menuid,
			'Field_Name'=>'menuid',
			'Field_Label_Name'=>'Menu ID'
			)
		);			
		if ($menuid_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "menuid",
				"MESSAGE" => "Invalid Menu ID"
			));
			exit;			
		}
		$smd=$this->getSubMenus($menuid);
		
        $user_name = $this->getCurrentUser();
        $ip_address = $this->getIpAddress();
		$date = $this->getCurrentDate();
				
		if($smd==NULL){
			$sql="update master.mst_menu_development set del_flag=:del_flag,del_username=:user_name,del_ipaddress=:ip_address,del_date=:date where menuid=:menuid ";
			$result = $this->prepare($sql,array(":del_flag"=>'Y',":user_name"=>$user_name,":ip_address"=>$ip_address,":date"=>$date,":menuid"=>$menuid),4);
			
			
			$str="Deleted";
		} else {
			$str="Unable to delete";
		}
		//return $str;
		$Result_Data['STATUS']=	"SUCCESS";
		$Result_Data['DATA']=$str;
		
		return json_encode($Result_Data);
	}
	
	public function loadTownName($post_data_array){
		ob_start();
		$dcode=base64_decode($_POST['dcode']);
		$dcode=explode(',',$dcode);
			$dcode= array_combine(
				array_map(function($i){ return ':dcode'.$i; }, array_keys($dcode)),
				$dcode
			);			
		$dcode_cond=" and dcode in (".implode(',',array_keys($dcode)).")";
		$statecode=33;
	
		$str = "<option value=''>Select Town panchayat</option>";
    
		$sel_town_details="SELECT lbcode,lbody_name_en FROM master.m_localbodies WHERE state_code=:statecode $dcode_cond AND lbtype=:lbtype order by dcode,lbody_name_en";
		$sel_town_details_res=$this->prepare($sel_town_details,array_merge(array(":statecode"=>$statecode,":lbtype"=>'TP'),$dcode),2);
		foreach($sel_town_details_res as $sel_town_details_key=>$sel_town_details_row)
		{
		
			$str .= "<option value='".$sel_town_details_row['lbcode']."'>".$sel_town_details_row['lbody_name_en']."</option>";
		
		}
	
		$ob_output_main_forms = ob_get_contents();
		ob_clean();
			
		$Result_Data['STATUS']=	"SUCCESS";
		$Result_Data['DATA']=$str;
		
		return json_encode($Result_Data);	
			
	}

	public function getSubMenuid($menuid="",$dcode="",$lbcode="",$roleid=""){
		
		//$sql="select * from master.mst_menu_development where submenuid ='".$menuid."' order by menu_order_no desc";
		
		$sql="select * from master.mst_menu_development a , master.mst_menuconfig b  where b.state_code=:state_code and trim(b.dcode)=:dcode and b.roleid=:roleid and b.lbcode=:lbcode and a.menuid=b.menuid and submenuid =:menuid and a.del_flag is null  order by menu_order_no desc";
		$result = $this->prepare($sql,array(":state_code"=>'33',":dcode"=>$dcode,":roleid"=>$roleid,":lbcode"=>$lbcode,":menuid"=>$menuid),2);
		if(count($result)==0){
			return NULL;
		}else{
			$smenu="";
			foreach($result as $menu_list_key => $menu_list_row){
  				$menuid=$menu_list_row["menuid"];
  				$submenu= $this->getSubMenuid($menuid,$dcode,$lbcode,$roleid);
					$smenu.=$menuid.",". $submenu;
			}
		 //return $smenu;
		 $Result_Data['STATUS']="SUCCESS";
		$Result_Data['DATA']=$smenu;
		
		return json_encode($Result_Data);
		}
	}
		
	public function menuRoleConfigonchangeevent($post_data_array){
		$dcode=base64_decode($post_data_array['dcode']);
		$lbcode=base64_decode($post_data_array['lbcode']);
		$roleid=base64_decode($post_data_array['roleid']);
		$cond="";
		if($dcode=='' && $lbcode==''){
			$cond="";
		} else if($dcode!='' && $lbcode==''){
			$cond="and dcode='$dcode'";
		} else if($dcode!='' && $lbcode!=''){
			$cond="and dcode='$dcode' and lbcode='$lbcode'";
		}
		
		$sql="select distinct menuid from master.mst_menuconfig where trim(roleid)=:roleid and isactive=:isactive and state_code=:state_code $cond";
		$result = $this->prepare($sql,array(":roleid"=>$roleid,":isactive"=>1,":state_code"=>'33'),2);	
		
		if(count($result)>0){
			$menuscript="";	
			foreach($result as $menu_list_key => $menu_list_row){
				$menuid=$menu_list_row["menuid"];
				//$submenu= $this->getSubMenuid($menuid,$dcode,$lbcode,$roleid);
				$submenu='';
				$menuscript.= $menuid.",".$submenu;
			}
		} else {
			$menuscript="No Menus Found for This role";
		}
		//return $menuscript;
		 $Result_Data['STATUS']="SUCCESS";
		$Result_Data['DATA']=$menuscript;
		
		return json_encode($Result_Data);
		
	}
	
	public function assignMenus($post_data_array){
		//print_r($post_data_array);exit;
	$this->beginTransaction();	
	$result_data=array();

        $user_name = $this->getCurrentUser();
        $ip_address = $this->getIpAddress();
		$date = $this->getCurrentDate();
		$state_code=33;
			
		$dcode=base64_decode($post_data_array['dcode']);
		$dcode_array=array_filter(explode(',',$dcode));//print_r($dcode_array);exit;
				
		$lbcode=base64_decode($post_data_array['lbcode']);
		$lbcode_array=array_filter(explode(',',$lbcode));//print_r($lbcode_array);exit;

		$rad_enable_disable=NULL;
		if($post_data_array['rad_enable_disable'] != ''){
			$rad_enable_disable=base64_decode($post_data_array['rad_enable_disable']);	
		}
		
	
	
	if($post_data_array['roleid'] != '')
	{
		$roleid=base64_decode($post_data_array['roleid']);
		$roleid_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$roleid,
			'Field_Name'=>'roleid',
			'Field_Label_Name'=>'roleid'
			
			)
		);			
		if ($roleid_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "roleid",
				"MESSAGE" => "Invalid roleid"
			));
			exit;			
		}
	}
	else
	{
		$result_data['STATUS']='ERROR';
		$result_data['MESSAGE']='Select Role Name';
		$result_data['MENU_COUNT']='';
		echo json_encode($result_data);
		exit;
	}
	
		$menuids=base64_decode($post_data_array['menuids']); 
		$menuid_array=array_filter(explode(',',$menuids)); //print_r($menuid);exit;

		
		$dcode_array= array_combine(
			array_map(function($i){ return ':dcode'.$i; }, array_keys($dcode_array)),
			$dcode_array
		);			
		$dcode_cond=" ARRAY[".implode(',',array_keys($dcode_array))."]::integer[]";

		$lbcode_array= array_combine(
			array_map(function($i){ return ':lbcode'.$i; }, array_keys($lbcode_array)),
			$lbcode_array
		);			
		$lbcode_cond=" ARRAY[".implode(',',array_keys($lbcode_array))."]::integer[]";

		$menuid_array= array_combine(
			array_map(function($i){ return ':menuid'.$i; }, array_keys($menuid_array)),
			$menuid_array
		);			
		$menuid_cond=" ARRAY[".implode(',',array_keys($menuid_array))."]::varchar[]"; 
	
		$qry = "select * FROM master.sp_menu_config(:roleid,:statecode,$dcode_cond,$lbcode_cond,$menuid_cond,:rad_enable_disable,:user_name,:ipaddress)";  
		 $result = $this->prepare($qry,array_merge(array(":roleid"=>$roleid,":statecode"=>$state_code,":rad_enable_disable"=>$rad_enable_disable,":user_name"=>$user_name,":ipaddress"=>$ip_address),$dcode_array,$lbcode_array,$menuid_array),4);
		//var_dump($result);exit;

		 if ($this->prepareStatus($result)==true) {

			$sp_menu_config=json_decode($result['sp_menu_config'],TRUE);
	
			if($sp_menu_config['STATUS']=='SUCCESS'){
				$this->commit();
				$result_array['STATUS']='SUCCESS';
				$result_array['MESSAGE']=$sp_menu_config['MESSAGE'];
			} else {
				$this->rollBack();
				$result_array['STATUS']='ERROR';
				$result_array['MESSAGE']=$sp_menu_config['MESSAGE'];
			}
		} else {
			$this->rollBack();
			$result_array['STATUS']='ERROR';
			$result_array['MESSAGE']='Menu could not be configured';
		}
		echo json_encode($result_array);
		exit;
	}
	
}

$getDetailsMenuAjax=new getDetailsMenuAjax(); 

$cmd=base64_decode($_POST['cmd']);
$cmd_Validation = $getDetailsMenuAjax->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$cmd,
			'Field_Name'=>'cmd',
			'Field_Label_Name'=>'Commend ID'
			)
		);			
		if ($cmd_Validation['Status'] == "Error") {
			echo json_encode(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "cmd",
				"MESSAGE" => "Invalid Commend ID"
			));
			exit;			
		}

if($cmd==1)
{
	echo $getDetailsMenuAjax->displayContent($_POST);
exit;	
}

if($cmd==2)
{
	echo $getDetailsMenuAjax->addNewMenu($_POST);
exit;	
}

if($cmd==3)
{
	echo $getDetailsMenuAjax->addMenu($_POST);
exit;	
}

if($cmd==4)
{
	echo $getDetailsMenuAjax->getMenuDetails($_POST);
exit;	
}

if($cmd==5)
{
	echo $getDetailsMenuAjax->editMenu($_POST);
exit;	
}

if($cmd==6)
{
	echo $getDetailsMenuAjax->updateMenu($_POST);
exit;	
}

if($cmd==7)
{
	echo $getDetailsMenuAjax->deleteMenu($_POST);
exit;	
}

if($cmd==8)
{
	echo $getDetailsMenuAjax->loadTownName($_POST);
exit;	
}

if($cmd==9)
{
	echo $getDetailsMenuAjax->menuRoleConfigonchangeevent($_POST);
exit;	
}

if($cmd==10)
{
	echo $getDetailsMenuAjax->assignMenus($_POST);
exit;	
}
?>	