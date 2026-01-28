<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once  '../../config/config.php';

class Voucher_Master_Form  extends ConfigClass
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
        <input type="hidden" id="page_lable_id" name="page_lable_id" value="38" />

        <?php

        $state_code = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $tpcode = $this->getCurrentLocalBodyCode();

        $lang_code_2d = $this->getCurrentUserLanguage2D();

        ?>
        <script type="text/javascript">
            $(document).ready(function() {

                $('#date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd-mm-yyyy',
                    minDate: new Date('01-01-1970'),
                    maxDate: new Date()
                });
           
                <?php if (!isset($post_data_array['del_id'])) { ?>

                    $(document).on('click', "#btn_save", function() {

                        var Current_Field_id = $(this).attr('id');
                        $('#' + Current_Field_id).hide();
                        try {

                            if ($("#date").val().length == '') {
                                throw {
                                    msg: "Select Date",
                                    foc: "#date"
                                }
                            }

                            if ($("#voucher_type").val().length == '') {
                                throw {
                                    msg: "Select Voucher Type",
                                    foc: "#voucher_type"
                                }
                            }

                            if ($("#chalan_no").val().length == '') {
                                throw {
                                    msg: "Select Chalan No.",
                                    foc: "#chalan_no"
                                }
                            }

                            if ($("#remark").val().length == '') {
                                throw {
                                    msg: "Enter Remarks",
                                    foc: "#remark"
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

            $sel_exemption_cat_data_upd_details = "SELECT voucher_master_id,voucher_type_id,date,chalan_no,remarks FROM accounts_master.voucher_master WHERE  voucher_master_id=:exemption_category_data_id";
            $data_array_val = $this->prepare($sel_exemption_cat_data_upd_details, array(":exemption_category_data_id" => $exemption_category_data_id), 4);
            // var_dump($data_array_val);exit;
        }

        ?>
        <form action="" method="post" class="" enctype="multipart/form-data">
            <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>" name="<?php echo htmlentities($this->page_token); ?>" value="<?php echo htmlentities($this->token($this->page_token)); ?>">
            <div class="card">
                <div class="card-body pl-5 pr-5">
                    <?php
                    if (isset($post_data_array["STATUS"])) {
                        echo $this->ShowMessage($post_data_array["STATUS"], $post_data_array["MESSAGE"]);
                        header("refresh: 3; url=Voucher_Master.php");
                    }
                    ?>



                    <table class="table table-bordered m-0 p-0 tndtp_form_table">
                        <thead class="bg-th-form-dsg">
                            <tr>
                                <th align="center" scope="col" colspan="12">Voucher Master</th>
                            </tr>


                        </thead>

                        <tbody>

                            <tr>
                                <td align="center" style="width:50%;"><span DisplayLabelID="186">Date</span></td>
                                <td>
                                    <input type="text" id="date" name="date" value="<?php if (isset($data_array_val['date'])) {
                                                                                        $date = $data_array_val['date'];
                                                                                        list($year_completion, $month_completion, $date_completion) = explode('-', $date);
                                                                                        $date = $date_completion . '-' . $month_completion . '-' . $year_completion;
                                                                                                                                        echo htmlentities($date);
                                                                                    } ?>" class="form-control w-50 form-control-sm  user_enter_date" <?php /*?>readonly="readonly"
                                <?php */ ?> />
                                </td>
                            </tr>
                            <tr>
                                <td align="center" style="width:50%;"><span DisplayLabelID="186">Voucher Type</span></td>
                                <td>
                                    <select id="voucher_type" name="voucher_type" class="form-control form-control-sm w-50">
                                        <option value="">Choose</option>
                                        <?php
                                        $sel_voucher_type = "SELECT voucher_id,voucher_type_en,voucher_type_ta FROM accounts_master.m_voucher_type WHERE isactive=:isactive and del_flag is null order by voucher_type_ta";
                                        $sel_voucher_type_res = $this->prepare($sel_voucher_type, array(":isactive" => 1), 2);
                                        //	var_dump($sel_voucher_type_res);exit;
                                        foreach ($sel_voucher_type_res as $sel_voucher_type_key => $sel_voucher_type_row) {
                                        ?>
                                            <option value="<?php echo htmlentities($sel_voucher_type_row['voucher_id']); ?>"><?php echo htmlentities($sel_voucher_type_row['voucher_type_en']); ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <script>
                                        document.getElementById('voucher_type').value =
                                            '<?php if (isset($data_array_val['voucher_type_id'])) {
                                                    echo htmlentities($data_array_val['voucher_type_id']);
                                                } ?>';
                                    </script>
                                </td>
                            </tr>

                            <tr>
                                <td align="center" style="width:50%;"><span DisplayLabelID="186">Chalan No</span></td>
                                <td>
                                    <!-- <select id="chalan_no" name="chalan_no" class="form-control form-control-sm w-50">
                                        <option value="">Choose</option>
                                        <option value="">test 1</option>
                                        <option value="">test 2</option>
                                    </select> -->
                                    <input type="text" id="chalan_no" name="chalan_no" value="<?php if (isset($data_array_val['chalan_no'])) {
                                                                                                    echo htmlentities($data_array_val['chalan_no']);
                                                                                                } ?>" class="form-control w-50 form-control-sm Number_Field" />
                                </td>
                            </tr>

                            <tr>
                                <td align="center" style="width:50%;"><span DisplayLabelID="186">Remarks</span></td>
                                <td>
                                    <textarea id="remark" name="remark" rows="4" cols="50" class="form-control form-control-sm w-50"><?php if (isset($data_array_val['remarks'])) {
                                                                                        echo htmlentities($data_array_val['remarks']);
                                                                                    } ?></textarea>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="4" align="center">
                                    <center>
                                        <input type="submit" id="btn_save" name="btn_save" value="<?php echo htmlentities($post_data_array['mode_name']); ?>" class="btn btn-md text-white font-weight-bold <?php echo htmlentities($post_data_array['mode_class']); ?>" />
                                        <input type="button" id="btn_reset" name="btn_reset" value="Cancel" class="btn btn-md text-white font-weight-bold btn-secondary" onclick="window.location='Voucher_Master.php'" />
                                    </center>
                                </td>
                            </tr>
                        </tbody>
                    </table>



                </div>
            </div>

            <div class="card">
                <div class="card-body">

                    <div class="single-table">
                        <table class="table table-bordered text-center table-striped tndtp_report_table" id="dataTable2">
                            <thead class="text-left">

                                <tr>
                                    <th scope="col"><span DisplayLabelID="311">S.No</span></th>
                                    <th scope="col"><span DisplayLabelID="329">Date</span></th>
                                    <th scope="col"><span DisplayLabelID="186">Voucher Type English</span></th>
                                     <th scope="col"><span DisplayLabelID="186">Voucher Type Tamil</span></th>
                                    <th scope="col"><span DisplayLabelID="671">Chalan No</span></th>
                                    <th scope="col"><span DisplayLabelID="388">Remarks</span></th>
                                    <th scope="col"><span DisplayLabelID="354">Action</span></th>
                                </tr>
                            </thead>
                            <tbody id="tradedetails_data">
                                <?php
                                $sel_vouchermaster_details = "SELECT a.voucher_master_id as edit_id,b.voucher_type_en, b.voucher_type_ta, a.chalan_no, a.date,a.remarks FROM 
                                (SELECT voucher_master_id,voucher_type_id,chalan_no,date,remarks FROM accounts_master.voucher_master WHERE  dcode=:dcode AND lbcode=:tpcode AND isactive=:isactive AND del_flag IS NULL AND statecode=:state_code)a 
                                LEFT JOIN (SELECT voucher_id,voucher_type_en,voucher_type_ta FROM accounts_master.m_voucher_type WHERE isactive=:isactive AND del_flag IS NULL)b ON a.voucher_type_id=b.voucher_id 
                                order by a.voucher_master_id desc";

                                $sel_vouchermaster_details_res = $this->prepare($sel_vouchermaster_details, array(":state_code" => 33, ":dcode" => $dcode, ":tpcode" => $tpcode, ":isactive" => 1), 2);
                                // var_dump($sel_vouchermaster_details_res);exit();

                                if (count($sel_vouchermaster_details_res) > 0) {
                                    foreach ($sel_vouchermaster_details_res as $sel_vouchermaster_details_key => $sel_vouchermaster_details_row) {
                                ?>
                                        <tr>
                                            <td class="text-center"><?php echo htmlentities($sel_vouchermaster_details_key + 1); ?></td>
                                            <td class="text-left">
                                                <?php echo htmlentities($sel_vouchermaster_details_row['date']); ?>
                                            </td>
                                              <td class="text-left">
                                                <?php echo htmlentities($sel_vouchermaster_details_row['voucher_type_en']); ?>
                                            </td>
                                            <td class="text-left">
                                                <?php echo htmlentities($sel_vouchermaster_details_row['voucher_type_ta']); ?>
                                            </td>
                                            <td class="text-left">
                                                <?php echo htmlentities($sel_vouchermaster_details_row['chalan_no']); ?></td>
                                            <td class="text-left">
                                                <?php echo htmlentities($sel_vouchermaster_details_row['remarks']); ?></td>

                                            <!-- <td align="center">
                                <?php /* if ($sel_vouchermaster_details_row['isactive'] == 1) {
                                    echo 'Active';
                                } else {
                                    echo 'Deactive';
                                } */ ?>
                            </td> -->
                                            <td align="center"><a href="?edit_id=<?php echo htmlentities(base64_encode($sel_vouchermaster_details_row['edit_id'])); ?>" class="btn btn-warning btn-sm"><?php /* ?><i class="fa fa-pencil pr-1"
                                        aria-hidden="true"></i><?php */ ?>Edit</a>
                                                <a href="?del_id=<?php echo htmlentities(base64_encode($sel_vouchermaster_details_row['edit_id'])); ?>" class="btn btn-danger btn-sm">Delete</a>
                                            </td>

                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td align="center" colspan="6" style="color:#F00;" class="font-weight-bold">No Record Found
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
        // var_dump($save_data);exit();
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


        $statecode = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();


        $edit_id = isset($save_data['edit_id']) ? base64_decode($save_data['edit_id']) : 0;
        $del_id = isset($save_data['del_id']) ? base64_decode($save_data['del_id']) : 0;

        if ($del_id == 0) {

            if (isset($save_data['date'])) {
                $date = $save_data['date'];
                list($date_completion, $month_completion, $year_completion) = explode('-', $date);
                $date = $year_completion . '-' . $month_completion . '-' . $date_completion;

                $dateValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'date',
                        'Field_Value' => $save_data['date'],
                        'Field_Name' => 'date',
                        'Field_Format' => 'dd-mm-yyyy',
                        'Field_Label_Name' => 'Invalid Date',
                    )
                );

                if ($dateValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "date",
                        "MESSAGE" => $dateValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['voucher_type'])) {
                $voucher_type = $save_data['voucher_type'];

                $voucher_typeValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $voucher_type,
                        'Field_Name' => 'voucher_type',
                        // 'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Invalid Voucher Type',
                    )
                );

                if ($voucher_typeValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "voucher_type",
                        "MESSAGE" => $voucher_typeValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['chalan_no'])) {
                $chalan_no = $save_data['chalan_no'];

                $chalan_noValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $chalan_no,
                        'Field_Name' => 'chalan_no',
                        // 'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Invalid Chalan No',
                    )
                );

                if ($chalan_noValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "chalan_no",
                        "MESSAGE" => $chalan_noValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['remark'])) {
                $remark = $save_data['remark'];

                $remarkValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $remark,
                        'Field_Name' => 'remark',
                        'Field_Max_length' => '150',
                        'Field_Label_Name' => 'Invalid Remarks',
                    )
                );

                if ($remarkValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "remark",
                        "MESSAGE" => $remarkValidation['Message']
                    ), $save_data));
                    exit;
                }
            }
        }

        $Result_Message = "Data Saved SuccessFully";

        if ($edit_id > 0) {
            $Result_Message = "Data Updated SuccessFully";
        } else if ($del_id > 0) {
            $Result_Message = "Data Deleted SuccessFully";
        }

        $this->beginTransaction();

        $acc_vouchermaster_initiation = "accounts_master.sp_voucher_master";
        $user_name = $this->getCurrentUser();
        $ip_address = $this->getIpAddress();
        //$date = $this->getCurrentDate();

        if (isset($save_data["edit_id"])) {

            $save_query = "select * from " . $acc_vouchermaster_initiation . "(:statecode,:dcode,:lbcode,:date,:voucher_type,:chalan_no,:remarks,:isactive,:user_name,:ip_address,:edit_id,:del_id)";

            $res1 = $this->prepare($save_query, array(":statecode" => $statecode, ":dcode" => $dcode, ":lbcode" => $lbcode, ":date" => $date, ":voucher_type" => $voucher_type, ":chalan_no" => $chalan_no, ":remarks" => $remark, ":isactive" => 1, ":user_name" => $user_name, ":ip_address" => $ip_address, ":edit_id" => $edit_id, ":del_id" => $del_id), 4);
        } else if (isset($save_data["del_id"])) {

            $save_query = "select * from " . $acc_vouchermaster_initiation . "(:statecode,:dcode,:lbcode,:date,:voucher_type,:chalan_no,:remarks,:isactive,:user_name,:ip_address,:edit_id,:del_id)";

            $res1 = $this->prepare($save_query, array(":statecode" => $statecode, ":dcode" => $dcode, ":lbcode" => $lbcode, ":date" => NULL, ":voucher_type" => NULL, ":chalan_no" => NULL, ":remarks" => NULL, ":isactive" => NULL, ":user_name" => $user_name, ":ip_address" => $ip_address, ":edit_id" => $edit_id, ":del_id" => $del_id), 4);
        } else {
            // print_r(array($statecode,$dcode,$lbcode,$licencetypeid,$lb_tradecode,$trade_name,$edscription_en,$edscription_ta,$isactive,$edit_id,$del_id));
            // exit();
            $save_query = "select * from " . $acc_vouchermaster_initiation . "(:statecode,:dcode,:lbcode,:date,:voucher_type,:chalan_no,:remarks,:isactive,:user_name,:ip_address,:edit_id,:del_id)";

            $res1 = $this->prepare($save_query, array(":statecode" => 33, ":dcode" => $dcode, ":lbcode" => $lbcode, ":date" => $date, ":voucher_type" => $voucher_type, ":chalan_no" => $chalan_no, ":remarks" => $remark, ":isactive" => 1, ":user_name" => $user_name, ":ip_address" => $ip_address, ":edit_id" => $edit_id, ":del_id" => $del_id), 4);
            // var_dump($res1);exit();
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

$propertyassessment = new Voucher_Master_Form();

if (!isset($_POST['cmd'])) {

    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        // print_r(array_merge($_POST, $_GET));exit();
        $propertyassessment->data_save(array_merge($_POST, $_GET));
    } else {
        $propertyassessment->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
    }
}
?>