<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once  '../../config/config.php';

class BankEntry_Master_Form  extends ConfigClass
{

    public $page_token = "Trade_Entry_Form";
    public function __construct()
    {
        if (!isset($this->db)) {
        }
    }

    public function main_content($post_data_array = array())
    {
        $site_data = $this->siteData();

        if (!isset($post_data_array["edit_id"]) && !isset($post_data_array["del_id"])) {
            $post_data_array["mode_name"] = "Save";
            $post_data_array["mode_class"] = "btn-success";
        } else if (isset($post_data_array["edit_id"])) {
            $post_data_array["mode_name"] = "Update";
            $post_data_array["mode_class"] = "btn-warning";
        } else if (isset($post_data_array["del_id"])) {
            $post_data_array["mode_name"] = "Delete";
            $post_data_array["mode_class"] = "btn-danger";
        }


        ob_start();

        // #############

        // PAGE CONTENT START

        // #############

?>
        <input type="hidden" id="page_lable_id" name="page_lable_id" value="198" />

        <?php

        $state_code = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $tpcode = $this->getCurrentLocalBodyCode();
		//$pageLables=$this->GetPageLables(198);
        $lang_code_2d = $this->getCurrentUserLanguage2D();
		//print_r($pageLables);
        ?>
        <script type="text/javascript">
            $(document).ready(function() {
                <?php if (!isset($post_data_array['del_id'])) { ?>				
					$(document).on('keyup', "#bank_code", function() {
							$(this).val($(this).val().toUpperCase());
					});
                    $(document).on('click', "#btn_save", function() {

                        var Current_Field_id = $(this).attr('id');
                        $('#' + Current_Field_id).hide();
                        try {

                            if ($("#bank_code").val().length == '') {
                                throw {
                                    msg: "Enter Bank Code",
                                    foc: "#bank_code"
                                }
                            }

                            if ($("#bank_name_en").val().length == '') {
                                throw {
                                    msg: "Enter Bank Name in English",
                                    foc: "#bank_name_en"
                                }
                            }

                            if ($("#bank_name_ta").val().length == '') {
                                throw {
                                    msg: "Enter bank Name in Tamil.",
                                    foc: "#bank_name_ta"
                                }
                            }

                            return true;
                        } catch (e) {
                            alert(e.msg);
                            $('#' + Current_Field_id).show();
                            $(e.foc).focus();
                            return false;
                        }

                    });

                <?php } ?>
            });
        </script>

        <style type="text/css">
            .hidden_field_element_value {
                display: none;
            }

            .gj-datepicker {
                width: 80%;
            }
        </style>


        <?php
        if (isset($post_data_array["edit_id"]) || isset($post_data_array["del_id"])) {
            if (isset($post_data_array["edit_id"])) {
                $exemption_category_data_id = base64_decode($post_data_array["edit_id"]);
                $exemption_category_data_id_nameValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $exemption_category_data_id,
                        'Field_Name' => 'edit_id',
                        'Field_Max_length' => '6',
                        'Field_Label_Name' => 'Edit ID',
                    )
                );

                if ($exemption_category_data_id_nameValidation['Status'] == "Error") {
                    echo 'Invalide Request';
                    exit;
                }
            } else if (isset($post_data_array["del_id"])) {
                $exemption_category_data_id = base64_decode($post_data_array["del_id"]);

                $exemption_category_data_id_nameValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $exemption_category_data_id,
                        'Field_Name' => 'del_id',
                        'Field_Max_length' => '6',
                        'Field_Label_Name' => 'Delete ID',
                    )
                );

                if ($exemption_category_data_id_nameValidation['Status'] == "Error") {
                    echo 'Invalide Request';
                    exit;
                }
            }

            $sel_exemption_cat_data_upd_details = "SELECT bank_id,bank_code,bank_name_en,bank_name_ta FROM accounts_master.m_bank WHERE bank_id=:exemption_category_data_id";
            $data_array_val = $this->prepare($sel_exemption_cat_data_upd_details, array(":exemption_category_data_id" => $exemption_category_data_id), 4);
        }

        ?>
        <div class="container pt-3"> 
        <form action="" method="post" class="" enctype="multipart/form-data"  autocomplete="off">
            <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>" name="<?php echo htmlentities($this->page_token); ?>" value="<?php echo htmlentities($this->token($this->page_token)); ?>">
            <div class="col-md-12">
            <div class="card">
                <div class="card-body pl-5 pr-5">
                    <?php
                    if (isset($post_data_array["STATUS"])) {
                        echo $this->ShowMessage($post_data_array["STATUS"], $post_data_array["MESSAGE"]);
                        header("refresh: 3; url=Bank_Entry_Form.php");
                    }
                    ?>
                    <table class="table table-bordered m-0 p-0 table-striped tndtp_report_table">
                        <thead class="bg-th-form-dsg">
                            <tr>
                                <th align="center" scope="col" colspan="12"><span><?php echo htmlentities('Bank Details Entry Form '); ?></span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td align="center" style="width:50%;"><span><?php echo htmlentities('Bank Code '); ?></span></td>
                                <td>
                                    <input type="text" id="bank_code" name="bank_code" value="<?php if (isset($data_array_val['bank_code'])) {
                                        echo htmlentities($data_array_val['bank_code']);
                                        } ?>" class="form-control w-50 form-control-sm name_eng_without_space w-50" <?php /*?>readonly="readonly"
                                <?php */ ?> />
                                </td>
                            </tr>


                            <tr>
                                <td align="center" style="width:50%;"><spanBank Name In English ><?php echo htmlentities('Bank Name In English'); ?></span></td>
                                <td>
                                    <input type="text" id="bank_name_en" name="bank_name_en" value="<?php if (isset($data_array_val['bank_name_en'])) {       echo htmlentities($data_array_val['bank_name_en']);    } ?>" class="form-control form-control-sm name_eng_with_space w-50" />
                                </td>
                            </tr>

                            <tr>
                                <td align="center" style="width:50%;"><span><?php echo htmlentities('Bank Name In Tamil '); ?></span></td>
                                <td>
                                    <input type="text" id="bank_name_ta" name="bank_name_ta" value="<?php if (isset($data_array_val['bank_name_ta'])) { echo htmlentities($data_array_val['bank_name_ta']);    } ?>" class="form-control form-control-sm name_tamil_comma_dot w-50" />
                                </td>
                            </tr>


                            <tr>
                                <td colspan="4" align="center">
                                    <center>
                                        <input type="submit" id="btn_save" name="btn_save" value="<?php echo htmlentities($post_data_array['mode_name']); ?>" class="btn btn-sm text-white font-weight-bold <?php echo htmlentities($post_data_array['mode_class']); ?>" />
                                        <input type="button" id="btn_reset" name="btn_reset" value="Cancel" class="btn btn-sm text-white font-weight-bold btn-secondary" onclick="window.location='Bank_Entry_Form.php'" />
                                    </center>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
       <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-body" style=" background-color:white;border:1px solid;border-color:white">
                    <div class="single-table">
                        <table class="table table-bordered text-center table-striped tndtp_report_table" id="dataTable2">
                            <thead class="text-left">
                                <tr>
                                    <th scope="col"><span><?php echo htmlentities('Sl. No '); ?></span></th>
                                    <th scope="col"><span><?php echo htmlentities('Bank Code '); ?></span></th>
                                    <th scope="col"><span><?php echo htmlentities('Bank Name In English '); ?></span></th>
                                    <th scope="col"><span><?php echo htmlentities('Bank Name In Tamil '); ?></span></th>
                                    <th scope="col"><span><?php echo htmlentities('Action'); ?></span></th>
                                </tr>
                            </thead>
                            <tbody id="tradedetails_data">
                                <?php
                                $sel_bankmaster_details = "SELECT bank_id as edit_id,bank_code,bank_name_en,bank_name_ta,isactive FROM accounts_master.m_bank WHERE isactive=:isactive AND del_flag IS NULL ORDER BY bank_id DESC;";
                                $sel_bankmaster_details_res = $this->prepare($sel_bankmaster_details, array(":isactive" => 1), 2);
                                if (count($sel_bankmaster_details_res) > 0) {
                                    foreach ($sel_bankmaster_details_res as $sel_bankmaster_details_key => $sel_bankmaster_details_row) { ?>
                                        <tr>
                                            <td class="text-center"><?php echo htmlentities($sel_bankmaster_details_key + 1); ?></td>
                                            <td class="text-left">
                                                <?php echo htmlentities($sel_bankmaster_details_row['bank_code']); ?>
                                            </td>
                                            <td class="text-left">
                                                <?php echo htmlentities($sel_bankmaster_details_row['bank_name_en']); ?>
                                            </td>
                                            <td class="text-left">
                                                <?php echo htmlentities($sel_bankmaster_details_row['bank_name_ta']); ?>
                                            </td>
                                <?php /* if ($sel_bankmaster_details_row['isactive'] == 1) {
                                    echo 'Active';
                                } else {
                                    echo 'Deactive';
                                } */ ?>
                                            <td align="center"><a href="?edit_id=<?php echo htmlentities(base64_encode($sel_bankmaster_details_row['edit_id'])); ?>" class="btn btn-warning btn-sm"><?php /* ?><i class="fa fa-pencil pr-1"
                                        aria-hidden="true"></i><?php */ ?>Edit</a>
                                                <a href="?del_id=<?php echo htmlentities(base64_encode($sel_bankmaster_details_row['edit_id'])); ?>" class="btn btn-danger btn-sm">Delete</a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td align="center" colspan="6" style="color:#F00;" class="font-weight-bold"><?php echo htmlentities('No Records Found'); ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </form>
        </div>
<?php

        // #############

        // PAGE CONTENT END

        // #############

        $ob_output_main_contents = ob_get_contents();
        ob_clean();
        $this->Template($this->getCurrentUserTemplate() != "" ? $this->getCurrentUserTemplate() : "Template1", "Property Tax - New Assessment", $ob_output_main_contents, array(), array('page_id' => 12));
    }

    public function data_save($save_data)
    {
        // TOKEN VALIDATE
        if (!$this->validateToken($this->page_token, $save_data[$this->page_token])) {
            $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => $this->page_token,
                "MESSAGE" => "Invalid Token"
            ), $save_data));
            exit;
        }
		else
		{
			unset($_SESSION[$this->page_token]);
		}

        $statecode = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();


        $edit_id = isset($save_data['edit_id']) ? base64_decode($save_data['edit_id']) : 0;
        $del_id = isset($save_data['del_id']) ? base64_decode($save_data['del_id']) : 0;

        if ($del_id == 0) {

            if (isset($save_data['bank_code']) && $save_data['bank_code']!='') {
                $bank_code = $save_data['bank_code'];
                $bank_Code_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $save_data['bank_code'],
                        'Field_Name' => 'bank_code',
						"Field_Max_length" => 10,
						"Field_Min_length" => 0,
                        'Field_Label_Name' => 'Invalid bank Code',
                    )
                );

                if ($bank_Code_Validation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "bank_code",
                        "MESSAGE" => $bank_Code_Validation['Message']
                    ), $save_data));
                    exit;
                }
            }else{
				$this->main_content(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "bank_code",
					"MESSAGE" => 'Enter Bank Code'
				), $save_data));
				exit;
			}

            if (isset($save_data['bank_name_en']) && $save_data['bank_name_en']!='') {
                $bank_name_en = $save_data['bank_name_en'];
                $Bank_Name_en_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $bank_name_en,
                        'Field_Name' => 'bank_name_en',
                        'Field_Max_length' => '250',
                        'Field_Label_Name' => 'Bank Name English',
                    )
                );

                if ($Bank_Name_en_Validation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "bank_name_en",
                        "MESSAGE" => $Bank_Name_en_Validation['Message']
                    ), $save_data));
                    exit;
                }
            }else{
				$this->main_content(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "bank_name_en",
					"MESSAGE" => 'Enter Bank Name English'
				), $save_data));
				exit;
			}

            if (isset($save_data['bank_name_ta']) && $save_data['bank_name_ta']!='') {
                $bank_name_ta = $save_data['bank_name_ta'];
                 $Bank_Name_Ta_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text_ta',
                        'Field_Value' => $bank_name_ta,
                        'Field_Name' => 'bank_name_ta',
                        'Field_Label_Name' => 'Bank Name Tamil',
                    )
                );

                if ($Bank_Name_Ta_Validation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "bank_name_ta",
                        "MESSAGE" => $Bank_Name_Ta_Validation['Message']
                    ), $save_data));
                    exit;
                } 
            }
        }else{
			$this->main_content(array_merge(array(
				"STATUS" => "ERROR",
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "bank_name_ta",
				"MESSAGE" => 'Enter Bank Name Tamil'
			), $save_data));
			exit;
		}

        $Result_Message = "Data Saved SuccessFully";

        if ($edit_id > 0) {
            $Result_Message = "Data Updated SuccessFully";
        } else if ($del_id > 0) {
            $Result_Message = "Data Deleted SuccessFully";
        }

        $this->beginTransaction();

        $acc_bankmaster_initiation = "accounts_master.sp_bank_master_entry";
        $user_name = $this->getCurrentUser();
        $ip_address = $this->getIpAddress();
        //$date = $this->getCurrentDate();

        if (isset($save_data["edit_id"])) {

            $save_query = "select * from " . $acc_bankmaster_initiation . "(:bank_code,:bank_name_en,:bank_name_ta,:isactive,:user_name,:ip_address,:edit_id,:del_id)";

            $res1 = $this->prepare($save_query, array(":bank_code" => $bank_code, ":bank_name_en" => $bank_name_en, ":bank_name_ta" => $bank_name_ta, ":isactive" => 1, ":user_name" => $user_name, ":ip_address" => $ip_address, ":edit_id" => $edit_id, ":del_id" => $del_id), 4);
        } else if (isset($save_data["del_id"])) {

            $save_query = "select * from " . $acc_bankmaster_initiation . "(:bank_code,:bank_name_en,:bank_name_ta,:isactive,:user_name,:ip_address,:edit_id,:del_id)";

            $res1 = $this->prepare($save_query, array(":bank_code" => NULL, ":bank_name_en" => NULL, ":bank_name_ta" => NULL, ":isactive" => 0, ":user_name" => $user_name, ":ip_address" => $ip_address, ":edit_id" => $edit_id, ":del_id" => $del_id), 4);
        } else {
            $save_query = "select * from " . $acc_bankmaster_initiation . "(:bank_code,:bank_name_en,:bank_name_ta,:isactive,:user_name,:ip_address,:edit_id,:del_id)";

            $res1 = $this->prepare($save_query, array(":bank_code" => $bank_code, ":bank_name_en" => $bank_name_en, ":bank_name_ta" => $bank_name_ta, ":isactive" => 1, ":user_name" => $user_name, ":ip_address" => $ip_address, ":edit_id" => $edit_id, ":del_id" => $del_id), 4);
        }

        $this->commit();

        if (!isset($res1->errorInfo)) {
            $this->main_content(array(
                "STATUS" => "SUCCESS",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => $Result_Message
            ));
            exit;
        } else {
            $this->main_content(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Data Save Failed Due To Duplicate Entry"
            ));
            exit;
        }
    }
}
$propertyassessment = new BankEntry_Master_Form();
if (!isset($_POST['cmd'])) {
    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        $propertyassessment->data_save(array_merge($_POST, $_GET));
    } else {
        $propertyassessment->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
    }
}
?>