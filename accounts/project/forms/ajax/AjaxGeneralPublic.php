<?php
require_once __DIR__ . '/../config/configPublic.php';
require_once __DIR__ . '/../templates/HtmlHelper.php';

require_once __DIR__ . '/../../library/captcha.php';



class AjaxGeneral extends ConfigClass
{

    use HtmlHelper;
	public $captcha;
    public function __construct()
    {
		$this->captcha = new captcha();
        $this->language_name = $this->getCurrentUserLanguage2D();
    }

    public function District_Name($state_code = '33')
    {
        $lang_code_2d = $this->getCurrentUserLanguage2D();
		$lang_code_2d_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'text',
				'Field_Value'=>$lang_code_2d,
				'Field_Name'=>'lang_code_2d',
				'Field_Label_Name'=>'Invalid Language',
				'Field_Max_length'=>'2'
				)
			);		
			
			if ($lang_code_2d_Validation['Status'] == "Error") {
				echo json_encode(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "lang_code_2d",
					"MESSAGE" => "Invalid Language"
				));
				exit;			
			}

        $query = "SELECT state_code,dcode,district_name_" . $lang_code_2d . " FROM master.m_district where state_code=:state_code order by dist_order_no;";
        $m_lgd_district_list = $this->prepare($query, array(
            ":state_code" => $state_code
        ), 2);
        return $this->CreateSelectBox(array(
            'data' => array(
                'option_only' => 'Y',
                'field_attr' => array(
                    'id' => 'dcode',
                    'name' => 'dcode',
                    'class' => 'custom-select'
                ),
                'field_option' => array(
                    'default_value' => array(
                        "value" => "",
                        "text" => "Choose",
                        "field_attr" => array(
                            'DisplayLabelID' => '19'
                        )
                    ),
                    'option' => array(
                        'dcode',
                        "district_name_" . $lang_code_2d
                    )
                )
            ),
            'option_data' => $m_lgd_district_list
        ));
    }

    // CMD = 6
    public function LocalBodyName($LocalBodyType = "", $state_code = "33", $dcode = "")
    {
        $lang_code_2d = $this->getCurrentUserLanguage2D();
		$lang_code_2d_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'text',
				'Field_Value'=>$lang_code_2d,
				'Field_Name'=>'lang_code_2d',
				'Field_Label_Name'=>'Invalid Language',
				'Field_Max_length'=>'2'
				)
			);		
			
			if ($lang_code_2d_Validation['Status'] == "Error") {
				echo json_encode(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "lang_code_2d",
					"MESSAGE" => "Invalid Language"
				));
				exit;			
			}
			
        if ($LocalBodyType == 'TP') {
            $query = "SELECT dcode, lbtype, lbcode, lbody_name_" . $lang_code_2d . " FROM master.m_localbodies where state_code=:state_code and  dcode=:dcode and lbtype=:lbtype and isactive=:isactive order by lbody_name_" . $lang_code_2d;
            $m_lgd_lblist_list = $this->prepare($query, array(
                ":state_code" => $state_code,
                ":dcode" => $dcode,
                ":lbtype" => 'TP',
                ":isactive" => 1
            ), 2);

            return $this->CreateSelectBox(array(
                'data' => array(
                    'option_only' => 'Y',
                    'field_attr' => array(
                        'id' => 'lbcode',
                        'name' => 'lbcode',
                        'class' => 'custom-select'
                    ),
                    'field_option' => array(
                        'default_value' => array(
                            "value" => "",
                            "text" => "Choose",
                            "field_attr" => array(
                                'DisplayLabelID' => '21'
                            )
                        ),
                        'option' => array(
                            'lbcode',
                            "lbody_name_" . $lang_code_2d
                        )
                    )
                ),
                'option_data' => $m_lgd_lblist_list
            ));
        }
    }

    // CMD = 7
    public function BuildingLocationName($LocalBodyType = "", $state_code = "33", $dcode = "", $lbcode = "")
    {
        $lang_code_2d = $this->getCurrentUserLanguage2D();
		$lang_code_2d_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'text',
				'Field_Value'=>$lang_code_2d,
				'Field_Name'=>'lang_code_2d',
				'Field_Label_Name'=>'Invalid Language',
				'Field_Max_length'=>'2'
				)
			);		
			
			if ($lang_code_2d_Validation['Status'] == "Error") {
				echo json_encode(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "lang_code_2d",
					"MESSAGE" => "Invalid Language"
				));
				exit;			
			}
        $query = "SELECT a.type_category_value_id as taxlocationvalueid, type_category_data_name_" . $lang_code_2d . " as taxlocationdesc_" . $lang_code_2d . ",ratevalue FROM propertytax.m_pp_type_category_value as a left join 
propertytax.m_pp_type_category_data as b on a.type_category_data_id=b.type_category_data_id  left join propertytax.m_pp_type_category as c on b.type_category_id=c.type_category_id where a.type_category_id=:cate_id and
a.isactive=:isactive and dcode=:dcode and lbcode=:lbcode order by type_category_data_name_" . $lang_code_2d;
        $m_lgd_lblist_list = $this->prepare($query, array(
            ":lbcode" => $lbcode,
            ":dcode" => $dcode,
            ":cate_id" => 4,
            ":isactive" => 1
        ), 2);

        return $this->CreateSelectBox(array(
            'data' => array(
                'option_only' => 'Y',
                'field_attr' => array(
                    'id' => 'taxlocationvalueid',
                    'name' => 'taxlocationvalueid',
                    'class' => 'custom-select'
                ),
                'field_option' => array(
                    'default_value' => array(
                        "value" => "",
                        "text" => "Choose",
                        "field_attr" => array(
                            'DisplayLabelID' => '31'
                        )
                    ),
                    'option' => array(
                        'taxlocationvalueid',
                        "taxlocationdesc_" . $lang_code_2d
                    )
                )
            ),
            'option_data' => $m_lgd_lblist_list
        ));
    }

    // CMD = 8
    public function BuildingUsageName($LocalBodyType = "", $state_code = "33", $dcode = "", $lbcode = "")
    {
        $lang_code_2d = $this->getCurrentUserLanguage2D();
		$lang_code_2d_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'text',
				'Field_Value'=>$lang_code_2d,
				'Field_Name'=>'lang_code_2d',
				'Field_Label_Name'=>'Invalid Language',
				'Field_Max_length'=>'2'
				)
			);		
			
			if ($lang_code_2d_Validation['Status'] == "Error") {
				echo json_encode(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "lang_code_2d",
					"MESSAGE" => "Invalid Language"
				));
				exit;			
			}
        $query = "SELECT a.type_category_value_id as buildusagevalueid, type_category_data_name_" . $lang_code_2d . " as buildusagetype_" . $lang_code_2d . ",ratevalue FROM propertytax.m_pp_type_category_value as a left join 
propertytax.m_pp_type_category_data as b on a.type_category_data_id=b.type_category_data_id  left join propertytax.m_pp_type_category as c on b.type_category_id=c.type_category_id where a.type_category_id=:cate_id and
a.isactive=:isactive and dcode=:dcode and lbcode=:lbcode order by type_category_data_name_" . $lang_code_2d;
        $m_lgd_lblist_list = $this->prepare($query, array(
            ":cate_id" => 3,
            ":isactive" => 1,
            ":dcode" => $dcode,
            ":lbcode" => $lbcode
        ), 2);

        return $this->CreateSelectBox(array(
            'data' => array(
                'option_only' => 'Y',
                'field_attr' => array(
                    'id' => 'buildusagevalueid',
                    'name' => 'buildusagevalueid',
                    'class' => 'custom-select'
                ),
                'field_option' => array(
                    'default_value' => array(
                        "value" => "",
                        "text" => "Choose",
                        "field_attr" => array(
                            'DisplayLabelID' => '32'
                        )
                    ),
                    'option' => array(
                        'buildusagevalueid',
                        "buildusagetype_" . $lang_code_2d
                    )
                )
            ),
            'option_data' => $m_lgd_lblist_list
        ));
    }

    // CMD = 9
    public function BuildingTypeName($LocalBodyType = "", $state_code = "33", $dcode = "", $lbcode = "")
    {
        $lang_code_2d = $this->getCurrentUserLanguage2D();
		$lang_code_2d_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'text',
				'Field_Value'=>$lang_code_2d,
				'Field_Name'=>'lang_code_2d',
				'Field_Label_Name'=>'Invalid Language',
				'Field_Max_length'=>'2'
				)
			);		
			
			if ($lang_code_2d_Validation['Status'] == "Error") {
				echo json_encode(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "lang_code_2d",
					"MESSAGE" => "Invalid Language"
				));
				exit;			
			}
        $query = "SELECT a.type_category_value_id as buildstructurevalueid, type_category_data_name_" . $lang_code_2d . " as buildstructuretype_" . $lang_code_2d . ",ratevalue FROM propertytax.m_pp_type_category_value as a left join 
propertytax.m_pp_type_category_data as b on a.type_category_data_id=b.type_category_data_id  left join propertytax.m_pp_type_category as c on b.type_category_id=c.type_category_id where a.type_category_id=:cate_id and
a.isactive=:isactive and dcode=:dcode and lbcode=:lbcode order by type_category_data_name_" . $lang_code_2d;
        $m_lgd_lblist_list = $this->prepare($query, array(
            ":cate_id" => 2,
            ":isactive" => 1,
            ":dcode" => $dcode,
            ":lbcode" => $lbcode
        ), 2);

        return $this->CreateSelectBox(array(
            'data' => array(
                'option_only' => 'Y',
                'field_attr' => array(
                    'id' => 'buildstructurevalueid',
                    'name' => 'buildstructurevalueid',
                    'class' => 'custom-select'
                ),
                'field_option' => array(
                    'default_value' => array(
                        "value" => "",
                        "text" => "Choose",
                        "field_attr" => array(
                            'DisplayLabelID' => '33'
                        )
                    ),
                    'option' => array(
                        'buildstructurevalueid',
                        "buildstructuretype_" . $lang_code_2d
                    )
                )
            ),
            'option_data' => $m_lgd_lblist_list
        ));
    }

    // CMD = 10
    public function BuildingAgeName($LocalBodyType = "", $state_code = "33", $dcode = "", $lbcode = "")
    {
        $lang_code_2d = $this->getCurrentUserLanguage2D();
		$lang_code_2d_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'text',
				'Field_Value'=>$lang_code_2d,
				'Field_Name'=>'lang_code_2d',
				'Field_Label_Name'=>'Invalid Language',
				'Field_Max_length'=>'2'
				)
			);		
			
			if ($lang_code_2d_Validation['Status'] == "Error") {
				echo json_encode(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "lang_code_2d",
					"MESSAGE" => "Invalid Language"
				));
				exit;			
			}
        $query = "SELECT a.type_category_value_id as buildagevalueid, type_category_data_name_" . $lang_code_2d . " as buildagetype_" . $lang_code_2d . ",ratevalue FROM propertytax.m_pp_type_category_value as a left join 
propertytax.m_pp_type_category_data as b on a.type_category_data_id=b.type_category_data_id  left join propertytax.m_pp_type_category as c on b.type_category_id=c.type_category_id where a.type_category_id=:cate_id and
a.isactive=:isactive and dcode=:dcode and lbcode=:lbcode order by buildagevalueid";
        $m_lgd_lblist_list = $this->prepare($query, array(
            ":cate_id" => 1,
            ":isactive" => 1,
            ":dcode" => $dcode,
            ":lbcode" => $lbcode
        ), 2);

        return $this->CreateSelectBox(array(
            'data' => array(
                'option_only' => 'Y',
                'field_attr' => array(
                    'id' => 'buildagevalueid',
                    'name' => 'buildagevalueid',
                    'class' => 'custom-select'
                ),
                'field_option' => array(
                    'default_value' => array(
                        "value" => "",
                        "text" => "Choose",
                        "field_attr" => array(
                            'DisplayLabelID' => '34'
                        )
                    ),
                    'option' => array(
                        'buildagevalueid',
                        "buildagetype_" . $lang_code_2d
                    )
                )
            ),
            'option_data' => $m_lgd_lblist_list
        ));
    }

    // CMD = 11
    public function TaxTypeName($LocalBodyType = "", $state_code = "33", $dcode = "", $lbcode = "")
    {
        $lang_code_2d = $this->getCurrentUserLanguage2D();
		$lang_code_2d_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'text',
				'Field_Value'=>$lang_code_2d,
				'Field_Name'=>'lang_code_2d',
				'Field_Label_Name'=>'Invalid Language',
				'Field_Max_length'=>'2'
				)
			);		
			
			if ($lang_code_2d_Validation['Status'] == "Error") {
				echo json_encode(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "lang_code_2d",
					"MESSAGE" => "Invalid Language"
				));
				exit;			
			}
        $query = "SELECT a.buildagevalueid, buildagetype_" . $lang_code_2d . " FROM propertytax.m_pp_buildusagevalue as a left join propertytax.m_pp_buildagetype as b on a.buildagetypeid=b.buildagetypeid where a.isactive=:isactive and dcode=:dcode and lbcode=:lbcode order by buildagetype_" . $lang_code_2d;
        $m_lgd_lblist_list = $this->prepare($query, array(
            ":isactive" => 1,
            ":dcode" => $dcode,
            ":lbcode" => $lbcode
        ), 2);

        return $this->CreateSelectBox(array(
            'data' => array(
                'option_only' => 'Y',
                'field_attr' => array(
                    'id' => 'buildagevalueid',
                    'name' => 'buildagevalueid',
                    'class' => 'custom-select'
                ),
                'field_option' => array(
                    'default_value' => array(
                        "value" => "",
                        "text" => "Choose",
                        "field_attr" => array(
                            'DisplayLabelID' => '35'
                        )
                    ),
                    'option' => array(
                        'buildagevalueid',
                        "buildagetype_" . $lang_code_2d
                    )
                )
            ),
            'option_data' => $m_lgd_lblist_list
        ));
    }

    public function Subdivision_Name($state_code = '33', $district_code = '')
    {
        $query = "SELECT state_code,district_code,sub_district_code,sub_district_name FROM master.m_lgd_sub_district where state_code=:state_code and district_code=:district_code ORDER BY state_code,district_code,sub_district_code";
        $m_lgd_sub_district_list = $this->prepare($query, array(
            ":state_code" => $state_code,
            ":district_code" => $district_code
        ), 2);
        echo $this->CreateSelectBox(array(
            'data' => array(
                'option_only' => 'N',
                'field_attr' => array(
                    'id' => 'sub_division_code',
                    'name' => 'sub_division_code',
                    'class' => 'custom-select'
                ),
                'field_option' => array(
                    'default_value' => 'Choose',
                    'option' => array(
                        'sub_district_code',
                        'sub_district_name'
                    )
                )
            ),
            'option_data' => $m_lgd_sub_district_list
        ));
    }

    public function Block_Name($state_code = '', $district_code = '', $sub_district_code = '')
    {
        $query = "SELECT state_code,district_code,sub_district_code,block_code,block_name FROM master.m_lgd_block where state_code=:state_code and district_code=:district_code and sub_district_code=:sub_district_code ORDER BY state_code,district_code,block_code";
        $m_lgd_block_list = $this->prepare($query, array(
            ":state_code" => $state_code,
            ":district_code" => $district_code,
            ":sub_district_code" => $sub_district_code
        ), 2);
        echo $this->CreateSelectBox(array(
            'data' => array(
                'option_only' => 'N',
                'field_attr' => array(
                    'id' => 'block_code',
                    'name' => 'block_code',
                    'class' => 'custom-select'
                ),
                'field_option' => array(
                    'default_value' => 'Choose',
                    'option' => array(
                        'block_code',
                        'block_name'
                    )
                )
            ),
            'option_data' => $m_lgd_block_list
        ));
    }

    public function Village_Name($state_code = '', $district_code = '', $sub_district_code = '', $block_code = '')
    {
        $query = "SELECT state_code,district_code,sub_district_code,block_code,village_code,village_name FROM master.m_lgd_village where state_code=:state_code and district_code=:district_code and sub_district_code=:sub_district_code and block_code=:block_code ORDER BY state_code,district_code,block_code,village_code";
        $m_lgd_village_list = $this->prepare($query, array(
            ":state_code" => $state_code,
            ":district_code" => $district_code,
            ":sub_district_code" => $sub_district_code,
            ":block_code" => $block_code
        ), 2);
        echo $this->CreateSelectBox(array(
            'data' => array(
                'option_only' => 'N',
                'field_attr' => array(
                    'id' => 'village_code',
                    'name' => 'village_code',
                    'class' => 'custom-select'
                ),
                'field_option' => array(
                    'default_value' => 'Choose',
                    'option' => array(
                        'village_code',
                        'village_name'
                    )
                )
            ),
            'option_data' => $m_lgd_village_list
        ));
    }

    public function language_set($lang_id = '')
    {
        $_SESSION['USER_DETAILS']['USER_PROFILE']['language_id'] = $lang_id;
        $query = "select lang_code_2d from master.m_langauage where lang_id=:lang_id";
        $languge_2d_name = $this->prepare($query, array(
            ":lang_id" => $lang_id
        ), 4);

        $_SESSION['USER_DETAILS']['language_id'] = $lang_id;
        $this->setCurrentUserLanguage2D($languge_2d_name['lang_code_2d']);

        echo 'success';
    }

    public function language_code_set($lang_code_2d = '')
    {
        $query = "select lang_id,lang_code_2d from master.m_langauage where lower(lang_code_2d)=lower(:lang_code_2d)";
        $languge_2d_name = $this->prepare($query, array(
            ":lang_code_2d" => $lang_code_2d
        ), 4);

		if (! isset($_SESSION))
           session_start();
		//echo 'before';
		//print_r($_SESSION);
		//echo '<br>';
        $_SESSION['USER_DETAILS']['USER_PROFILE']['language_id'] = $languge_2d_name['lang_id'];

        $_SESSION['USER_DETAILS']['language_id'] = $languge_2d_name['lang_id'];
		
      	//$this->setCurrentUserLanguage2D($languge_2d_name['lang_code_2d']);
		
		$this->setCurrentUserLanguage2D($languge_2d_name['lang_code_2d']);
	  	//$_SESSION['USER_DETAILS']['language_name']=$languge_2d_name['lang_code_2d'];
		//echo 'after';
		//print_r($_SESSION);
		//echo $lang_code_2d;
        echo 'success';
    }
}

$AjaxGeneral = new AjaxGeneral();

if(isset($_POST['cmd']))
{
$cmd = base64_decode($_POST['cmd']);
$cmd_Validation = $AjaxGeneral->Field_Validation(array(
    'Field_Type' => 'number',
    'Field_Value' => $cmd,
    'Field_Name' => 'cmd',
    'Field_Label_Name' => 'Commend ID'
));
if ($cmd_Validation['Status'] == "Error") {
    echo json_encode(array(
        "STATUS" => "ERROR",
        "STATUS_TYPE" => "FIELD",
        "FIELD_NAME" => "cmd",
        "MESSAGE" => "Invalid Commend ID"
    ));
    exit();
}

if ($cmd == 1) {
    $state_code = base64_decode($_POST['state_code']);
    $state_code_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $state_code,
        'Field_Name' => 'state_code',
        'Field_Label_Name' => 'State Code'
    ));
    if ($state_code_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "state_code",
            "MESSAGE" => "Invalid State Code"
        ));
        exit();
    }
    echo $AjaxGeneral->District_Name($state_code);
    exit();
}

if ($cmd == 2) {
    $state_code = base64_decode($_POST['state_code']);
    $state_code_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $state_code,
        'Field_Name' => 'state_code',
        'Field_Label_Name' => 'State Code'
    ));
    if ($state_code_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "state_code",
            "MESSAGE" => "Invalid State Code"
        ));
        exit();
    }
    $district_code = base64_decode($_POST['district_code']);
    $dcode_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $district_code,
        'Field_Name' => 'dcode',
        'Field_Label_Name' => 'District Code'
    ));
    if ($dcode_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "dcode",
            "MESSAGE" => "Invalid District Code"
        ));
        exit();
    }
    echo $AjaxGeneral->Subdivision_Name($state_code, $district_code);
    exit();
}

if ($cmd == 3) {
    $state_code = base64_decode($_POST['state_code']);
    $state_code_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $state_code,
        'Field_Name' => 'state_code',
        'Field_Label_Name' => 'State Code'
    ));
    if ($state_code_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "state_code",
            "MESSAGE" => "Invalid State Code"
        ));
        exit();
    }
    $district_code = base64_decode($_POST['district_code']);
    $dcode_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $district_code,
        'Field_Name' => 'dcode',
        'Field_Label_Name' => 'District Code'
    ));
    if ($dcode_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "dcode",
            "MESSAGE" => "Invalid District Code"
        ));
        exit();
    }
    $sub_division_code = base64_decode($_POST['sub_division_code']);
    $sub_division_code_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $sub_division_code,
        'Field_Name' => 'sub_division_code',
        'Field_Label_Name' => 'Town Panchayat Code'
    ));
    if ($sub_division_code_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "sub_division_code",
            "MESSAGE" => "Invalid Town Panchayat Code"
        ));
        exit();
    }
    echo $AjaxGeneral->Block_Name($state_code, $district_code, $sub_division_code);
    exit();
}

if ($cmd == 4) {
    $state_code = base64_decode($_POST['state_code']);
    $state_code_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $state_code,
        'Field_Name' => 'state_code',
        'Field_Label_Name' => 'State Code'
    ));
    if ($state_code_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "state_code",
            "MESSAGE" => "Invalid State Code"
        ));
        exit();
    }
    $district_code = base64_decode($_POST['district_code']);
    $dcode_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $district_code,
        'Field_Name' => 'dcode',
        'Field_Label_Name' => 'District Code'
    ));
    if ($dcode_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "dcode",
            "MESSAGE" => "Invalid District Code"
        ));
        exit();
    }
    $sub_division_code = base64_decode($_POST['sub_division_code']);
    $sub_division_code_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $sub_division_code,
        'Field_Name' => 'sub_division_code',
        'Field_Label_Name' => 'Town Panchayat Code'
    ));
    if ($sub_division_code_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "sub_division_code",
            "MESSAGE" => "Invalid Town Panchayat Code"
        ));
        exit();
    }
    $block_code = base64_decode($_POST['block_code']);
	$block_code_code_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $block_code,
        'Field_Name' => 'block_code',
        'Field_Label_Name' => 'Block Code'
    ));
    if ($block_code_code_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "block_code",
            "MESSAGE" => "Invalid Block Code"
        ));
        exit();
    }
    echo $AjaxGeneral->Village_Name($state_code, $district_code, $sub_division_code, $block_code);
    exit();
}
if ($cmd == 5) {
    $lang_id = base64_decode($_POST['lang_id']);
	$lang_id_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'number',
				'Field_Value'=>$lang_id,
				'Field_Name'=>'lang_code',
				'Field_Label_Name'=>'Invalid Language'
				)
			);		
			
			if ($lang_id_Validation['Status'] == "Error") {
				echo json_encode(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "lang_code",
					"MESSAGE" => "Invalid Language"
				));
				exit;			
			}
    echo $AjaxGeneral->language_set($lang_id);
    exit();
}
if ($cmd == 6) {
    $state_code = isset($_POST['state_code']) ? base64_decode($_POST['state_code']) : "";
    $dcode = base64_decode($_POST['dcode']);
    $dcode_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $dcode,
        'Field_Name' => 'dcode',
        'Field_Label_Name' => 'District Code'
    ));
    if ($dcode_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "dcode",
            "MESSAGE" => "Invalid District Code"
        ));
        exit();
    }
    echo $AjaxGeneral->LocalBodyName('TP', '33', $dcode);
    exit();
}
if ($cmd == 7) {
    $state_code = isset($_POST['state_code']) ? base64_decode($_POST['state_code']) : "";
    $dcode = base64_decode($_POST['dcode']);
    $dcode_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $dcode,
        'Field_Name' => 'dcode',
        'Field_Label_Name' => 'District Code'
    ));
    if ($dcode_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "dcode",
            "MESSAGE" => "Invalid District Code"
        ));
        exit();
    }
    $lbcode = base64_decode($_POST['lbcode']);
    $lbcode_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $lbcode,
        'Field_Name' => 'lbcode',
        'Field_Label_Name' => 'Town Panchayat Code'
    ));
    if ($lbcode_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "lbcode",
            "MESSAGE" => "Invalid Town Panchayat Code"
        ));
        exit();
    }
    echo $AjaxGeneral->BuildingLocationName('TP', '33', $dcode, $lbcode);
    exit();
}
if ($cmd == 8) {
    $state_code = isset($_POST['state_code']) ? base64_decode($_POST['state_code']) : "";
    $dcode = base64_decode($_POST['dcode']);
    $dcode_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $dcode,
        'Field_Name' => 'dcode',
        'Field_Label_Name' => 'District Code'
    ));
    if ($dcode_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "dcode",
            "MESSAGE" => "Invalid District Code"
        ));
        exit();
    }
    $lbcode = base64_decode($_POST['lbcode']);
    $lbcode_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $lbcode,
        'Field_Name' => 'lbcode',
        'Field_Label_Name' => 'Town Panchayat Code'
    ));
    if ($lbcode_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "lbcode",
            "MESSAGE" => "Invalid Town Panchayat Code"
        ));
        exit();
    }
    echo $AjaxGeneral->BuildingUsageName('TP', '33', $dcode, $lbcode);
    exit();
}
if ($cmd == 9) {
    $state_code = isset($_POST['state_code']) ? base64_decode($_POST['state_code']) : "";
    $dcode = base64_decode($_POST['dcode']);
    $dcode_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $dcode,
        'Field_Name' => 'dcode',
        'Field_Label_Name' => 'District Code'
    ));
    if ($dcode_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "dcode",
            "MESSAGE" => "Invalid District Code"
        ));
        exit();
    }
    $lbcode = base64_decode($_POST['lbcode']);
    $lbcode_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $lbcode,
        'Field_Name' => 'lbcode',
        'Field_Label_Name' => 'Town Panchayat Code'
    ));
    if ($lbcode_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "lbcode",
            "MESSAGE" => "Invalid Town Panchayat Code"
        ));
        exit();
    }
    echo $AjaxGeneral->BuildingTypeName('TP', '33', $dcode, $lbcode);
    exit();
}
if ($cmd == 10) {
    $state_code = isset($_POST['state_code']) ? base64_decode($_POST['state_code']) : "";
    $dcode = base64_decode($_POST['dcode']);
    $dcode_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $dcode,
        'Field_Name' => 'dcode',
        'Field_Label_Name' => 'District Code'
    ));
    if ($dcode_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "dcode",
            "MESSAGE" => "Invalid District Code"
        ));
        exit();
    }
    $lbcode = base64_decode($_POST['lbcode']);
    $lbcode_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $lbcode,
        'Field_Name' => 'lbcode',
        'Field_Label_Name' => 'Town Panchayat Code'
    ));
    if ($lbcode_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "lbcode",
            "MESSAGE" => "Invalid Town Panchayat Code"
        ));
        exit();
    }
    echo $AjaxGeneral->BuildingAgeName('TP', '33', $dcode, $lbcode);
    exit();
}
if ($cmd == 11) {
    $state_code = isset($_POST['state_code']) ? base64_decode($_POST['state_code']) : "";
    $dcode = base64_decode($_POST['dcode']);
    $dcode_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $dcode,
        'Field_Name' => 'dcode',
        'Field_Label_Name' => 'District Code'
    ));
    if ($dcode_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "dcode",
            "MESSAGE" => "Invalid District Code"
        ));
        exit();
    }
    $lbcode = base64_decode($_POST['lbcode']);
    $lbcode_Validation = $AjaxGeneral->Field_Validation(array(
        'Field_Type' => 'number',
        'Field_Value' => $lbcode,
        'Field_Name' => 'lbcode',
        'Field_Label_Name' => 'Town Panchayat Code'
    ));
    if ($lbcode_Validation['Status'] == "Error") {
        echo json_encode(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "lbcode",
            "MESSAGE" => "Invalid Town Panchayat Code"
        ));
        exit();
    }
    echo $AjaxGeneral->TaxTypeName('TP', '33', $dcode, $lbcode);
    exit();
}
if ($cmd == 12) {
     $lang_code_2d = base64_decode($_POST['lang_code_2d']);
	 $lang_code_2d_Validation = $AjaxGeneral->Field_Validation(
				array
				(
				'Field_Type'=>'text',
				'Field_Value'=>$lang_code_2d,
				'Field_Name'=>'lang_code_2d',
				'Field_Label_Name'=>'Invalid Language',
				'Field_Max_length'=>'2'
				)
			);		
			
			if ($lang_code_2d_Validation['Status'] == "Error") {
				echo json_encode(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "lang_code_2d",
					"MESSAGE" => "Invalid Language"
				));
				exit;			
			}
    echo $AjaxGeneral->language_code_set($lang_code_2d);
    exit();
}

if ($cmd == 13) {
    echo $AjaxGeneral->captcha->generateNewCaptcha('login_captcha');	
    exit();
}

}
else{
    echo "Invalid Request";
}

?>