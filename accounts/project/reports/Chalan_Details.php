<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once  '../config/config.php';

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



    $(document).on('click', "#redirect_chalan", function(event) {

        var Current_Field_id = $(this).attr('id');
       
        try {

            if ($("#chalon_type").val().length == '') {
                throw({
                    msg: "Select Voucher Type",
                    foc: "#chalon_type"
                });
            }
            let chalan_no=$("#chalan_no").val()
            if (chalan_no.length=='') {
                throw ({
                    msg: "Select Chalan No.",
                    foc: "#chalan_no"
                });
            }
            else if(isNaN(chalan_no))
            {   
                console.log(chalan_no);
                console.log(typeof chalan_no);
                throw({
                    msg:"chalan number should be in digits",
                    foc:"#chalan_no"
                })
            }
        } catch (e) {
            alert(e.msg);
            $('#' + Current_Field_id).show();
            $(e.foc).focus();
            event.preventDefault();
            return false;
        }

    });

});

</script>

<style type="text/css">
    /*
.hidden_field_element_value {
    display: none;
}

.gj-datepicker {
    width: 80%;
}
    */
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
    <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>"
        name="<?php echo htmlentities($this->page_token); ?>"
        value="<?php echo htmlentities($this->token($this->page_token)); ?>">
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
                        <th align="center" scope="col" colspan="12">Chalan Details</th>
                    </tr>


                </thead>

                <tbody>
                    <tr>
                        <td align="center" style="width:50%;"><span DisplayLabelID="186">Chalan Details</span></td>
                        <td>
                            <select id="chalon_type" name="chalon_type" class="form-control form-control-sm w-50">
                                <option value="">Choose</option>
                                <option value="1">Triplicate Chalan</option>
                                <option value="2">Bank Payment Voucher</option>
                                <option value="3">Bank Reciept Voucher</option>

                            </select>

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
                                                                                                } ?>"
                                class="form-control w-50 form-control-sm Number_Field" />

                        </td>
                    </tr>



                    <tr>
                        <td colspan="4" align="center">
                            <center>
                                <a href="" id="redirect_chalan" name="redirect_chalan" class="btn btn-success"
                                    target="_blank">Show</a>
                            </center>
                            <script>

                                            
                                                $(document).on("blur","#chalan_no",async function(){
                                                    //alert('entered blur')
                                            let chalon_no=$("#chalan_no").val();
                                            let chalon_type_id=$("#chalon_type").val();
                                            // console.log("Entered chalon_no event handler");
                                            // console.log(`chalon_no=${chalon_no} chalon_type_id=${chalon_type_id}`);
                                            await $.ajax({
                                                url:"Chalan_Details.php",
                                                data:{cmd:btoa(1),chalon_no:btoa(chalon_no),chalon_type_id:btoa(chalon_type_id)},
                                                type:"post",
                                                success:function(data)
                                                {   
                                                    //console.log(data);
                                                    let url=data["url"];        
                                                    //set the url
                                                    $("#redirect_chalan").attr("href",url);
                                                },
                                                error:function(xhr,status,message)
                                                {
                                                    console.log(`status=${status} message=${message}`);
                                                },
                                                dataType:"json"
                                            })
                                        })
                                            
    
                                    </script>
                        </td>

                    </tr>
                </tbody>
            </table>



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
$lbcode=$propertyassessment->getCurrentLocalBodyCode();
$dcode=$propertyassessment->getCurrentDistrictCode();
if (!isset($_POST['cmd'])) {

    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        // print_r(array_merge($_POST, $_GET));exit();
        $propertyassessment->data_save(array_merge($_POST, $_GET));
    } else {
        $propertyassessment->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
    }
}

else{
    $cmd=base64_decode($_POST["cmd"]);
    //echo json_encode($_POST); 
    if($cmd==="1")
    {
        $url_arr=[
            "1"=>"/tndtp_egov/accounts/project/forms/masters/triplicate.php",
            "2"=>"/tndtp_egov/accounts/project/forms/masters/bank_payment_voucher_receipt.php",
            "3"=>"/tndtp_egov/accounts/project/forms/masters/Bank_Receipt_Voucher_Receipt.php"
        ];
        $chalan_no=$_POST["chalon_no"];
        $chalan_type_id=base64_decode($_POST["chalon_type_id"]);
        $id=$chalan_no;
        if($chalan_type_id== "1")
        {
            $query="select chalan_details_id from accounts_master.t_triplicate_chalan_details where del_flag is null and dcode=:dcode and lbcode=:lbcode and chalan_no=:chalan_no";
            $params=[":lbcode"=>$lbcode,":dcode"=>$dcode,":chalan_no"=>base64_decode($chalan_no)];
            $res=$propertyassessment->prepare($query, $params,4);
            //print_r($res);die();
            $id=base64_encode($res['chalan_details_id']);
        }
        
            $data=["url"=>$url_arr[(string)$chalan_type_id]."?id={$id}"];
        
        echo json_encode($data);
        exit;
    }
}
    
?>