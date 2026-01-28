<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once  '../../config/config.php';

class VoucherTypeDetails  extends ConfigClass
{

    public $page_token = "Voucher_Type_Details";
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
            function updateTextInput() {
        var accounts = document.querySelector('input[name="fund_mode"]:checked').value;
if(accounts != ''){
                        $.ajax({
                            url: "Fund_Entry_Form.php",
                            type: "post",
                            data: {
                                "accounts": btoa(accounts),
                                "cmd": btoa(3)
                            },
                            success: function(data) {
                              if (data != '') {
                                $('#account_code').html(data);
                            }
                            },
                            dataType: 'html'
                        }); 
                    }
                    else{
                        alert('Select Accounts');
                    }
        
    }

    document.querySelectorAll('input[name="fund_mode"]').forEach(function(radio) {
        radio.addEventListener('change', updateTextInput);
    });

    window.addEventListener('load', updateTextInput);


   <?php if (!isset($post_data_array["del_id"])) { ?>
                    $(document).on('click', "#btn_save", function() {
                        try {

                            if ($("#fundname").val().length == '') {
                                throw {
                                    msg: "Enter Fund Name",
                                    foc: "#fundname"
                                }
                            }
                            if ($("#account_code").val().length == '') {
                                throw {
                                    msg: "Select account code",
                                    foc: "#account_code"
                                }
                            }
                            if ($("#fundamount").val().length == '') {
                                throw {
                                    msg: "Enter Fund Amount",
                                    foc: "#fundamount"
                                }
                            }
                            if ($("#fund_type").val().length == '') {
                                throw {
                                    msg: "Enter Fund Type",
                                    foc: "#fund_type"
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
                <?php }?>
           });
        </script>
 <?php
        if (isset($post_data_array["edit_id"]) || isset($post_data_array["del_id"])) {
            if (isset($post_data_array["edit_id"])) {
                $fundid = base64_decode($post_data_array["edit_id"]);

                $fundid_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $fundid,
                        'Field_Name' => 'edit_id',
                        'Field_Max_length' => '6',
                        'Field_Label_Name' => 'Edit ID',
                    )
                );

                if ($fundid_Validation['Status'] == "Error") {
                    echo 'Invalide Request';
                    exit;
                }
            } else if (isset($post_data_array["del_id"])) {
                $fundid = base64_decode($post_data_array["del_id"]);

                $fundid_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $fundid,
                        'Field_Name' => 'del_id',
                        'Field_Max_length' => '6',
                        'Field_Label_Name' => 'Delete ID',
                    )
                );

                if ($fundid_Validation['Status'] == "Error") {
                    echo 'Invalide Request';
                    exit;
                }
            }

            $sel_exemption_cat_data_upd_details = "SELECT fundid, fundname, fundcategory,allotment,fundtype,account_code FROM accounts_master.m_fund WHERE  fundid=:fundid";

            $data_array_val = $this->prepare($sel_exemption_cat_data_upd_details, array(":fundid" => $fundid), 4);
            // var_dump($data_array_val);exit;
        }

        ?>
      <div class="container pt-3"> 
        <form action="" method="post" class="" enctype="multipart/form-data">
            <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>" name="<?php echo htmlentities($this->page_token); ?>" value="<?php echo htmlentities($this->token($this->page_token)); ?>">
                <?php
                    if (isset($post_data_array["STATUS"])) {
                        echo $this->ShowMessage($post_data_array["STATUS"], $post_data_array["MESSAGE"]);
                        header("refresh: 3; url=Fund_Entry_Form.php");
                    }
                    ?>
                    <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                   <table class="table table-bordered m-0 p-0 tndtp_form_table">
                        <thead class="bg-th-form-dsg">
                            <tr>
                                <th align="center" scope="col" colspan="12">Fund Entry Form</th>
                            </tr>


                        </thead>

                        <tbody>
                            <tr>
                                <td class="text-left font-weight-bold"><span DisplayLabelID="">Fund Name</span></td>
                                <td>
                                       <input type="text" name="fundname" id="fundname" class="form-control form-control-sm w-50" value="<?php if (isset($data_array_val['fundname'])) { echo htmlentities($data_array_val['fundname']); } ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left font-weight-bold"><span DisplayLabelID="345">Fund Mode</span></td>
                                <td>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="credit" name="fund_mode" value="1" class="custom-control-input" <?php if(isset($data_array_val['fundtype']) && $data_array_val['fundtype']==1){ ?>checked<?php } ?>>
                                        <label class="custom-control-label" for="credit"><span DisplayLabelID="371">Income</span></label>
                                    </div> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="debit" name="fund_mode" value="2" class="custom-control-input" <?php if(isset($data_array_val['fundtype']) && $data_array_val['fundtype']==2){ ?>checked<?php } ?>>
                                        <label class="custom-control-label" for="debit"><span DisplayLabelID="372">Expense</span></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left font-weight-bold"><span DisplayLabelID="186">Account Code</span></td>
                                <td>
                                    <select id="account_code" name="account_code" class="form-control form-control-sm w-50">
                                        <option value="">Choose</option>
                                        <?php
                                        $sel_account_code_id = "SELECT account_head_id, account_head_name_en, account_head_name_ta FROM accounts_master.m_account_head where del_flag is null and isactive=:isactive;";
                                        $sel_account_codeid_res = $this->prepare($sel_account_code_id, array(':isactive'=>1), 2);
                                        foreach ($sel_account_codeid_res as $sel_account_codeid_key => $sel_account_codeid_row) {
                                        ?>
                                            <option value="<?php echo htmlentities($sel_account_codeid_row['account_head_id']); ?>" data-desc="<?php echo htmlentities($sel_account_codeid_row['account_head_id']); ?>">
                                                <?php echo htmlentities($sel_account_codeid_row['account_head_name_en']); ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <script>
                                        $(document).ready(function(){
                                       $("#account_code").val(
                                            <?php if (isset($data_array_val['account_code'])) {
                                                    echo htmlentities($data_array_val['account_code']);
                                                } ?>
                                       )
                                        });
                                        
                                    </script>
                                </td>
                            </tr>
                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="">Fund Amount</span></td>
                                <td>
                                     <input type="text" name="fundamount" id="fundamount" class="form-control form-control-sm w-50 "  value="<?php if (isset($data_array_val['allotment'])) { echo htmlentities($data_array_val['allotment']); } ?>"/>
                                </td>
                            </tr>

                            <tr>
                                <td   class="text-left font-weight-bold"><span DisplayLabelID="483">Fund Type</span></td>                            
                                <td  scope="col">
                                    <select id="fund_type" name="fund_type" class="form-control form-control-sm w-50">
                                       <option value="">Choose</option>
                                       <option value="state">State Goverment</option>
                                       <option value="central">Central Goverment</option>
                                       
                                    </select>
                                
                                    <script>
                                    document.getElementById("fund_type").value =
                                        '<?php echo isset($data_array_val['fundcategory'])?$data_array_val['fundcategory']:'';?>';
                                    </script>
                                </td>
                            </tr>
                           

                            <tr align="center">
                                <td scope="row" colspan="2" align="center" class="text-center"> 
								
										<input type="submit" id="btn_save" name="btn_save" value="<?php echo htmlentities($post_data_array['mode_name']); ?>" class="btn btn-md text-white font-weight-bold <?php echo htmlentities($post_data_array['mode_class']); ?>" />  
                                        <input type="button" id="btn_reset" name="btn_reset" value="Cancel" class="btn btn-md text-white font-weight-bold btn-secondary" onclick="window.location='Fund_Entry_Form.php'" />       
										
                                </td>
                                
                            </tr>
                        </tbody>
                    </table>



                </div>
            </div>
            </div>
            <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-body" >

                    <div class="single-table">
                        <table class="table table-bordered text-center table-striped tndtp_report_table" id="dataTable2">
                            <thead class="text-left">

                                <tr>
                                    <th scope="col"><span DisplayLabelID="311">S.No</span></th>
                                    <th scope="col"><span DisplayLabelID="186">Fund Name</span></th>
                                    <th scope="col"><span DisplayLabelID="186">Fund Amount</span></th>
                                     <th scope="col"><span DisplayLabelID="186">Fund Type</span></th>
                                      <th scope="col"><span DisplayLabelID="186">Fund Mode</span></th>
                                       <th scope="col"><span DisplayLabelID="186">Account Code</span></th>
                                    <th scope="col"><span DisplayLabelID="354">Action</span></th>
                                </tr>
                            </thead>
                            <tbody id="tradedetails_data">
                                <?php
                                /*
                                $sel_vouchermaster_details = "select fundid as edit_id, fundname, fundcategory,allotment,fundtype,account_code from accounts_master.m_fund where isactive=:isactive and del_flag IS NULL order by fundid";
                                */

                                $sel_vouchermaster_details="select a.fundid as edit_id,
                                    a.fundname,
                                    a.fundcategory,
                                    a.allotment,
                                    b.account_head_type_en,
                                    c.account_head_name_en
                                from accounts_master.m_fund a
                                left join accounts_master.m_account_head_type b 
                                    on a.fundtype = b.account_head_type_id
                                left join accounts_master.m_account_head c 
                                    on a.account_code = c.account_head_id
                                where a.isactive = :isactive
                                and a.del_flag IS NULL
                                order by a.fundid;
                                ";

                                $sel_vouchermaster_details_res = $this->prepare($sel_vouchermaster_details, array(":isactive" => 1), 2);
                                // var_dump($sel_vouchermaster_details_res);exit();

                                if (count($sel_vouchermaster_details_res) > 0) {
                                    foreach ($sel_vouchermaster_details_res as $sel_vouchermaster_details_key => $sel_vouchermaster_details_row) {
                                ?>
                                        <tr>
                                            <td class="text-center"><?php echo htmlentities($sel_vouchermaster_details_key + 1); ?></td>
                                           
                                            <td class="text-left">
                                                <?php echo htmlentities($sel_vouchermaster_details_row['fundname']); ?>
                                            </td>
                                            <td class="text-left">
                                                <?php echo htmlentities($sel_vouchermaster_details_row['allotment']); ?>
                                            </td>
                                              <td class="text-left">
                                                <?php echo htmlentities($sel_vouchermaster_details_row['fundcategory']); ?>
                                            </td>
                                             <td class="text-left">
                                                <?php echo htmlentities($sel_vouchermaster_details_row['account_head_type_en']); ?>
                                            </td>
                                             <td class="text-left">
                                                <?php echo htmlentities($sel_vouchermaster_details_row['account_head_name_en']); ?>
                                            </td>
                                         
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
         </div>   

        </form>
        <div class="container pt-3"> 
        <?php

        // #############

        // PAGE CONTENT END

        // #############

        $ob_output_main_contents = ob_get_contents();
        ob_clean();
        $this->Template($this->getCurrentUserTemplate() != "" ? $this->getCurrentUserTemplate() : "Template1", "Voucher Type", $ob_output_main_contents, array(), array('page_id' => 12));
    }

 
   public function data_save($save_data)
    {
		// echo "<pre>";
  //       print_r($save_data);exit();
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

       // if ($del_id == 0) {

            if (isset($save_data['fundname'])) {
                $fundname = $save_data['fundname'];

                $fundname_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $fundname,
                        'Field_Name' => 'fundname',
                        // 'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Invalid Voucher Type',
                    )
                );

                if ($fundname_Validation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "fundname",
                        "MESSAGE" => $fundname_Validation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['fund_mode']) && $save_data['fund_mode']!='') {
                $fund_mode = $save_data['fund_mode'];

                /* $voucher_type_taValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $voucher_type_ta,
                        'Field_Name' => 'voucher_type_ta',
                        // 'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Invalid voucher type',
                    )
                );

                if ($voucher_type_taValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "voucher_type_ta",
                        "MESSAGE" => $voucher_type_taValidation['Message']
                    ), $save_data));
                    exit;
                } */
            }else{
				$fund_mode="";
			}

      //  }

             if (isset($save_data['fundamount']) && $save_data['fundamount']!='') {
                $fundamount = $save_data['fundamount'];
             }else{
                $fundamount="";
            } 

            if (isset($save_data['fund_type']) && $save_data['fund_type']!='') {
                $fund_type = $save_data['fund_type'];
             }else{
                $fund_type="";
            }

             if (isset($save_data['account_code']) && $save_data['account_code']!='') {
                $account_code = $save_data['account_code'];
             }else{
                $account_code="";
            }
      //       echo "<pre>";
      // print_r($save_data);die;
       
	   $Result_Message = "Data Saved SuccessFully"; 

        if ($edit_id > 0) {
            $Result_Message = "Data Updated SuccessFully";
        } else if ($del_id > 0) {
            $Result_Message = "Data Deleted SuccessFully";
        }

        $this->beginTransaction();

      $VoucherTypeFunction = "accounts_master.sp_fund_type";
        $getCurrentUser = $this->getCurrentUser();
        $getIpAddress = $this->getIpAddress();
        //$date = $this->getCurrentDate();


/*
        if (isset($save_data["edit_id"])) {

            $save_query = "select " . $VoucherTypeFunction . "(:fundname,:fund_mode,:fundamount,:fund_type,:account_code,:getCurrentUser,now()::timestamp without time zone,:getIpAddress,:edit_id,:del_id);";  
			   $res = $this->prepare($save_query,array(":fundname"=>$fundname,":fund_mode"=>$fund_mode,":fundamount"=>$fundamount,":fund_type"=>$fund_type,":account_code"=>$account_code,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>$edit_id, ":del_id" => $del_id),4);
        } else if (isset($save_data["del_id"])) {

            $save_query = "select " . $VoucherTypeFunction . "(:fundname,:fund_mode,:fundamount,:fund_type,:getCurrentUser,now()::timestamp without time zone,:account_code,:getIpAddress,:edit_id,:del_id);";  
			 $res = $this->prepare($save_query,array(":fundname"=>$fundname,":fund_mode"=>$fund_mode,":fundamount"=>$fundamount,":fund_type"=>$fund_type,":account_code"=>$account_code,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>$edit_id, ":del_id" => $del_id),4);
        } else {
          
            $save_query = "select " . $VoucherTypeFunction . "(:fundname,:fund_mode,:fundamount,:fund_type,:getCurrentUser,now()::timestamp without time zone,:account_code,:getIpAddress,:edit_id,:del_id);"; 
			   
			   $res = $this->prepare($save_query,array(":fundname"=>$fundname,":fund_mode"=>$fund_mode,":fundamount"=>$fundamount,":fund_type"=>$fund_type,":account_code"=>$account_code,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>$edit_id, ":del_id" => $del_id),7);  
            
        }
*/
        $save_query = "select " . $VoucherTypeFunction . "(:fundname,:fund_mode,:fundamount,:fund_type,:account_code,:getCurrentUser,now()::timestamp without time zone,:getIpAddress,:edit_id,:del_id);";  
			 $res = $this->prepare($save_query,array(":fundname"=>$fundname,":fund_mode"=>$fund_mode,":fundamount"=>$fundamount,":fund_type"=>$fund_type,":account_code"=>$account_code,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>$edit_id, ":del_id" => $del_id),4);

        $this->commit();

        if (!isset($res->errorInfo)) {
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

$VoucherTypeDetails = new VoucherTypeDetails();

if (!isset($_POST['cmd'])) {

    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        //print_r(array_merge($_POST, $_GET));exit();
        $VoucherTypeDetails->data_save(array_merge($_POST, $_GET));
    } else {
        $VoucherTypeDetails->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
    }
}else if (isset($_POST['cmd'])) {
    $cmd = base64_decode($_POST['cmd']);
    if ($cmd == 3) {

         $accounts = base64_decode($_POST['accounts']);
        ?>
        <option value="" DisplayLabelID="255">Choose </option>
        <?php
        $sel_street_details = "SELECT account_head_id,old_account_head_code as account_code,account_head_name_en FROM accounts_master.m_account_head where account_type_head_id=:account ORDER BY account_code DESC";
        $sel_street_details_res =$VoucherTypeDetails->prepare($sel_street_details,array(":account"=>$accounts),2);
        foreach ($sel_street_details_res as $sel_street_details_key => $sel_street_details_row) {
        ?>


         <option value="<?php echo htmlentities($sel_street_details_row['account_head_id']); ?>">
                        <?php echo htmlentities($sel_street_details_row['account_code']) . ' - ' . htmlentities($sel_street_details_row['account_head_name_en']); ?>
                    </option>
        <?php
        }

        exit;
    }
}    
?>