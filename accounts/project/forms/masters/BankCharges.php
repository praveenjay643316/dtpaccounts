<?php
require_once  '../../config/config.php';
class InterBankTransfer  extends ConfigClass
{

    public $page_token = "inter_bank_transfer";

    function __construct()
    {
        // $this->pageRoleAccessCheck(array(1));
    }

    public function main_form($data_array = array())
    {


        ob_start();

        // #############

        // PAGE CONTENT START

        // #############

        // PLACE YOUR CODE HERE
?>
        <input type="hidden" id="page_lable_id" name="page_lable_id" value="48" />
        <?php


        $state_code = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $tpcode = $this->getCurrentLocalBodyCode();



        $lang_code_2d = $this->getCurrentUserLanguage2D();

        ?>
        <script type="text/javascript">
            $(document).ready(function() {

                 function updateTextInput() {
        var accounts = document.querySelector('input[name="cash_from_type"]:checked').value;
if(accounts != ''){
                        $.ajax({
                            url: "BankCharges.php",
                            type: "post",
                            data: {
                                "accounts": btoa(accounts),
                                "cmd": btoa(3)
                            },
                            success: function(data) {
                              if (data != '') {
                                $('#acc_code').html(data);
                            }
                            },
                            dataType: 'html'
                        }); 
                    }
                    else{
                        alert('Select Accounts');
                    }
        
    }

    document.querySelectorAll('input[name="cash_from_type"]').forEach(function(radio) {
        radio.addEventListener('change', updateTextInput);
    });

    window.addEventListener('load', updateTextInput);

              
				$(document).on('change', '#bank_code', function() {
                    var bank_code = $("#bank_code").val();
					if(bank_code != ''){
						$.ajax({
							url: "BankCharges.php",
							type: "post",
							data: {
								"bank_code": btoa(bank_code),
								"cmd": btoa(2)
							},
							success: function(data) {
								if (data != '') {
									var Result_Data = JSON.parse(data);
									$('#bank_name').val(Result_Data['DATA']);
								}
							},
							dataType: 'html'
						});	
					}
					else{
						alert('Select Bank Code');
					}
                });
            

                $('#cash_coll_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'mm-dd-yyyy',
                    //minDate:  '12-12-2014',
                    minDate: new Date('01-01-1970'),
                    //maxDate: new Date() 
                    maxDate: new Date()

                });

                $('#chalan_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'mm-dd-yyyy',
                    //minDate:  '12-12-2014',
                    minDate: new Date('01-01-1970'),
                    //maxDate: new Date() 
                    maxDate: new Date()

                });
             

                    $(document).on('click', "#btn_save", function() {

                        var Current_Field_id = $(this).attr('id');
                        $('#' + Current_Field_id).hide();
                        try {

                            if ($("#chalan_no").val().length == '') {
                                throw {
                                    msg: "Enter Chalan Number",
                                    foc: "#chalan_no"
                                }
                            }

                            if ($("#chalan_date").val().length == '') {
                                throw {
                                    msg: "Enter Chalan Date",
                                    foc: "#chalan_date"
                                }
                            }

                            if ($("#bank_code").val().length == '') {
                                throw {
                                    msg: "Select Bank Code",
                                    foc: "#bank_code"
                                }
                            }

                            if ($("#closing_balance").val().length == '') {
                                throw {
                                    msg: "Enter Closing Balance",
                                    foc: "#closing_balance"
                                }
                            }
							
							
                            if ($('input:radio[name=cash_from_type]:checked').length == 0) {
                                throw {
                                    msg: "Choose Cash From",
                                    foc: "input:radio[name=cash_from_type]"
                                }
                            }


                            if ($("#cash_coll_date").val().length == '') {
                                throw {
                                    msg: "Select Cash Collection Date",
                                    foc: "#cash_coll_date"
                                }
                            }

                            if ($("#acc_code").val().length == '') {
                                throw {
                                    msg: "Enter Account COde",
                                    foc: "#acc_code"
                                }
                            }
							
						
							
							if ($("#amount").val().length == '') {
                                throw {
                                    msg: "Enter Amount",
                                    foc: "#amount"
                                }
                            }
							if ($("#remark").val().length == '') {
                                throw {
                                    msg: "Enter Remark",
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

               
            });
        </script>

        <style type="text/css">
            .hidden_field_element_value {
                display: none;
            }

            .gj-datepicker {
                width: 50%;
            }
			table.table-bordered > tbody > tr > td, table.table-bordered > tfoot > tr > td {
				width: 50%!important;
			}
        </style>

<div class="container">
       
        <form action="" method="post" class="" enctype="multipart/form-data">
            <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>" name="<?php echo htmlentities($this->page_token); ?>" value="<?php echo htmlentities($this->token($this->page_token)); ?>">
            <div class="card">
                <div class="card-body pl-5 pr-5">
                  <?php
                    if (isset($data_array["STATUS"])) {
                        echo $this->ShowMessage($data_array["STATUS"], $data_array["MESSAGE"]);
                        header("refresh: 3; url=BankCharges.php");
                    }
                    ?>



                    <table class="table table-bordered m-0 p-0 tndtp_form_table">
                        <thead class="bg-th-form-dsg">
                            <tr>
                                <th align="center" scope="col" colspan="12">Bank Charges</th>
                            </tr>


                        </thead>

                        <tbody>
                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Chalan Number</span></td>

                                <td  scope="col">
                                    <input type="text" id="chalan_no" name="chalan_no" class="form-control w-50 form-control-sm" />
                                </td>
                            </tr>

                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483"> Chalan Date</span></td>

                                <td  scope="col">
                                    <input type="text" id="chalan_date" name="chalan_date" value="" class="form-control w-50 form-control-sm user_enter_date" />
                                </td>
                            </tr>

                           
							<tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Bank Code </span></td>
                                <td  scope="col">
                                    <select id="bank_code" name="bank_code" class="form-control form-control-sm w-50">
											<option value="">Choose</option>
											<?php
											   $sel_qry = "select bank_id, bank_code, bank_name_en from accounts_master.m_bank where del_flag is null and isactive = 1";
												$sel_qry_res=$this->prepare($sel_qry,array(),2);
												foreach($sel_qry_res as $sel_qry_key=>$sel_qry_row)
												{
											?>
												<option value="<?php echo htmlentities($sel_qry_row['bank_id']);?>"><?php echo htmlentities($sel_qry_row['bank_code']);?></option>
													
											<?php }?>
									   </select>
                                   <?php /*?> <script>
                                        document.getElementById('fin_year').value =
                                            '<?php if (isset($data_array_val['finyear'])) {
                                                    echo htmlentities($data_array_val['finyear']);
                                                } ?>';
                                    </script><?php */?>
                                </td>
                            </tr>
							<tr id="bank_name_row">
                                <td colspan="1" class="text-left font-weight-bold"><span DisplayLabelID="483">Bank Name</span></td>

                                <td colspan="3" scope="col">
                                    <input type="text" id="bank_name" name="bank_name" maxlength="500" value="" class="form-control  form-control-sm Tax_Form_English_Ownername_Property_Tax first_letter_uppercase w-50" />
                                </td>
                            </tr>

							<tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Bank Closing Balance</span></td>

                                <td  scope="col">
                                    <input type="text" id="closing_balance" name="closing_balance" class="form-control w-50 form-control-sm" />
                                </td>
                            </tr>
                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Cash From</span></td>
                                <td  scope="col">

                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="accounts" name="cash_from_type" value="1" class="custom-control-input">
                                        <label class="custom-control-label" for="accounts"><span DisplayLabelID="371">Expense</span></label>
                                    </div> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="collection" name="cash_from_type" value="2" class="custom-control-input">
                                        <label class="custom-control-label" for="collection"><span DisplayLabelID="372">Income</span></label>
                                    </div>

                                </td>
                            </tr>

                            <tr id="cash_coll_date_row" >
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Date</span></td>
                                <td  scope="col">
                                    <input type="text" id="cash_coll_date" name="cash_coll_date" value="" class="form-control form-control-sm user_enter_date" />
                                </td>
                            </tr>

                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Account Code</span></td>
                                <td  scope="col">
                                    <select id="acc_code" name="acc_code" class="form-control form-control-sm w-50">
                                        <option value="">Choose</option>
                                        <?php
                                        $sel_fin_year_id = "SELECT bank_payment_voucher_account_codes_id,acc_code as account_code,acc_head FROM accounts_master.bank_payment_voucher_account_codes ORDER BY account_code DESC";

                                        $sel_fin_yearid_res = $this->prepare($sel_fin_year_id, array(),2);

                                        foreach ($sel_fin_yearid_res as $sel_fin_yearid_key => $sel_fin_yearid_row) {

                                        ?>
                                            <option value="<?php echo htmlentities($sel_fin_yearid_row['bank_payment_voucher_account_codes_id']); ?>">
                                                <?php echo htmlentities($sel_fin_yearid_row['account_code']); ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                   
                                </td>
                            </tr>

                            <!-- <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Account Head</span></td>
                                <td  scope="col">
                                    <textarea id="acc_head" name="acc_head" rows="4" cols="50" class="form-control w-50 form-control-sm"></textarea>
                                </td>
                            </tr> -->



                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Amount</span></td>
                                <td  scope="col">
                                    <input type="text" id="amount" name="amount" class="form-control form-control-sm w-50" /></td>
                            </tr>

                           
                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Narration</span></td>
                                <td  scope="col">
                                    <textarea id="remark" name="remark" rows="4" cols="50" class="form-control w-50 form-control-sm"></textarea></td>
                            </tr>

                            <tr>
                                <td colspan="4" align="center">
                                    <center>
                                        <input type="submit" id="btn_save" name="btn_save" value="Save" class="btn btn-md text-white font-weight-bold  btn-success" />

                                    </center>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

           
            </div>



        </form>
        </div>
<?php

        // #############

        // PAGE CONTENT END

        // #############

        $ob_output_main_forms = ob_get_contents();
        ob_clean();

        $this->Template("Template1", "User Role", $ob_output_main_forms, array(
            array(
                "name" => "User Role"
            )
        ));
        exit();
    }

    public function data_save($save_data)
    {

        // TOKEN VALIDATE
        if (!$this->validateToken($this->page_token, $save_data[$this->page_token])) {
            $this->main_form(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => $this->page_token,
                "MESSAGE" => "Invalid Token"
            ), $save_data));
            exit;
        } else {
            unset($_SESSION[$this->page_token]);
        }
		//print_r($save_data);exit;
       // if (!isset($save_data["del_id"])) {


			
            $chalan_no = $save_data['chalan_no'];
            $chalan_date = $save_data['chalan_date'];
            $bank_code = $save_data['bank_code'];
            $bank_name = $save_data['bank_name'];
            $closing_balance = $save_data['closing_balance'];
			$cash_from_type = $save_data['cash_from_type'];
            $cash_coll_date = $save_data['cash_coll_date'];
            $acc_code = $save_data['acc_code'];
            $acc_head = NULL;
            $remark = $save_data['remark'];
            $amount = $save_data['amount'];


		 $message = 'Data Deleted SccessFully';
        if (isset($save_data["del_id"])) {
			$del_id = base64_decode($save_data["del_id"]);
            $role_name_Validation = $this->Field_Validation(array(
                "Field_Type" => "number",
                "Field_Value" => $save_data["del_id"],
                "Field_Max_length" => 100,
                "Field_Min_length" => 0
            ));
            if ($role_name_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "del_id",
                    "MESSAGE" => "Invalid data"
                ), $save_data));
            }
			$message = 'Data Deleted SccessFully';
        }else{
			$del_id = 0;	
		}

        if (isset($save_data["edit_id"])) {
			
            $edit_id = base64_decode($save_data["edit_id"]);
            $role_name_Validation = $this->Field_Validation(array(
                "Field_Type" => "number",
                "Field_Value" => $edit_id,
                "Field_Max_length" => 100,
                "Field_Min_length" => 0
            ));

            if ($role_name_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "edit_id",
                    "MESSAGE" => "Invalid data"
                ), $save_data));
            }
			$message = 'Data Updated SccessFully';
        }else{
			$edit_id = 0;	
		}


        $statecode = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();

        $getCurrentUser = $this->getCurrentUser();
        $getIpAddress = $this->getIpAddress();

		$save_query = "select accounts_master.t_bank_charges(:statecode,:dcode,:lbcode,:chalan_no::TEXT,:chalan_date::timestamp without time zone,:bank_code::integer,:bank_name::text,:closing_balance::integer,:cash_from_type::text,:cash_coll_date::timestamp without time zone,:acc_code::text,:acc_head::text,:remark::text,:amount::INTEGER,:getCurrentUser::text,:getIpAddress::text,:edit_id,:delete_id);";
$result=$this->prepare($save_query,array(":statecode"=>$statecode,":dcode"=>$dcode,":lbcode"=>$lbcode,":chalan_no"=>$chalan_no,":chalan_date"=>$chalan_date,":closing_balance"=>$closing_balance,":bank_code"=>$bank_code,":bank_name"=>$bank_name,":cash_from_type"=>$cash_from_type,":cash_coll_date"=>$cash_coll_date,":acc_code"=>$acc_code,":acc_head"=>$acc_head,":remark"=>$remark,":amount"=>$amount,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>$edit_id,":delete_id"=>$del_id),4);

 $this->commit();
        if ($this->prepareStatus($result) == true) {
            $this->main_form(array(
                "STATUS" => "SUCCESS",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => $message
            ));
        } else {
            $this->main_form(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Data Save Failed Due To Duplicate Entry"
            ));
        }
    }
}

$home = new InterBankTransfer();


if (!isset($_POST['cmd'])) {
	
    if (isset($_POST["btn_save"])) {
		
        $home->data_save($_POST) ;
    }
    if (isset($_GET["edit_id"])) {
        $edit_id = base64_decode($_GET["edit_id"]);
        /***********************  Check *****************************/

        $edit_id_Validation = $home->Field_Validation(
            array(
                'Field_Type' => 'number',
                'Field_Value' => $edit_id,
                'Field_Name' => 'otax_two_txt',
                //'Field_Max_length'=>'30',
                'Field_Label_Name' => 'Invalied Edit ID',
            )
        );

        if ($edit_id_Validation['Status'] == "Error") {
            $home->main_form(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "otax_two_txt",
                "MESSAGE" => $edit_id_Validation['Message']
            ), $edit_id));
            exit;
        }

        /*********************** End Check *****************************/
        $home->main_form(array_merge(array(
            "mode" => "edit",
            "mode_name" => "Update",
            "mode_class" => "btn-warning",
            "mode_icon" => "fa fa-pencil",
            "edit_id" => $edit_id
        ), $_POST, $_GET));
    }
    if (isset($_GET["del_id"])) {
        $del_id = base64_decode($_GET["del_id"]);

        /***********************  Check *****************************/

        $delete_id_Validation = $home->Field_Validation(
            array(
                'Field_Type' => 'number',
                'Field_Value' => $del_id,
                'Field_Name' => 'otax_two_txt',
                //'Field_Max_length'=>'30',
                'Field_Label_Name' => 'Invalied Edit ID',
            )
        );

        if ($delete_id_Validation['Status'] == "Error") {
            $home->main_form(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "otax_two_txt",
                "MESSAGE" => $delete_id_Validation['Message']
            ), $del_id));
            exit;
        }

        /*********************** End Check *****************************/
        $home->main_form(array_merge(array(
            "mode" => "delete",
            "mode_name" => "Delete",
            "mode_class" => "btn-danger",
            "mode_icon" => "fa fa-trash-o",
            "del_id" => $del_id
        ), $_POST, $_GET));
    } else {
        $home->main_form(array(
            "mode" => "save", "mode_name" => "Save", "mode_class" => "btn-success", "mode_icon" => "fa fa-floppy-o"
        ));
    }
	
} 
else if (isset($_POST['cmd'])) {
     $cmd = base64_decode($_POST['cmd']);
	 if ($cmd == 2) {
			$bank_code = base64_decode($_POST['bank_code']);
			$sel_qry = "select bank_code, bank_name_en from accounts_master.bank_new where del_flag is null and isactive = 1 and bank_id=:bank_code";
			
			$sel_qry_res=$home->prepare($sel_qry,array(":bank_code"=>$bank_code),4);
													
			$Result['STATUS'] = 'SUCCESS';
			$Result['DATA'] = $sel_qry_res['bank_name_en'];
			echo json_encode($Result);
			exit;
	}
    $cmd = base64_decode($_POST['cmd']);
    if ($cmd == 3) {

         $accounts = base64_decode($_POST['accounts']);
        ?>
        <option value="" DisplayLabelID="255">Choose </option>
        <?php
        $sel_street_details = "SELECT account_head_id,old_account_head_code as account_code,account_head_name_en FROM accounts_master.m_account_head where account_type=:account ORDER BY account_code DESC";
        $sel_street_details_res =$home->prepare($sel_street_details,array(":account"=>$accounts),2);
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