<?php
require_once  '../../config/config.php';
class Adjust_Bank_Receipt_Voucher  extends ConfigClass
{

    public $page_token = "Adjust_Bank_Receipt_Voucher";

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
				
				$('input[type="radio"]').on('click', function() {
				   
				   var type=$(this).val();
				   if(type == 'D'){
					  $("#debit_amt").removeAttr("style");
					  $("#credit_amt").attr("style", "display:none");  
				  }
				  else if(type == 'C'){
					$("#credit_amt").removeAttr("style");
					$("#debit_amt").attr("style","display:none");  
			    }else{
					$("#debit_amt").attr("style","display:none");
					$("#credit_amt").attr("style","display:none");  
			}
				   
		   });
				
				 $(document).on('change', '#bank_code', function() {

                    var bank_code = $("#bank_code").val();
					if(bank_code != ''){
						$.ajax({
							url: "AdjustBankReceiptVoucher.php",
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

                $('#date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd-mm-yyyy',
                    //minDate:  '12-12-2014',
                    minDate: new Date('01-01-1970'),
                    //maxDate: new Date() 
                    maxDate: new Date()

                });

                $('#cheque_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd-mm-yyyy',
                    //minDate:  '12-12-2014',
                    minDate: new Date('01-01-1970'),
                    //maxDate: new Date() 
                    maxDate: new Date()

                });


                $('#cash_coll_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd-mm-yyyy',
                    //minDate:  '12-12-2014',
                    minDate: new Date('01-01-1970'),
                    //maxDate: new Date() 
                    maxDate: new Date()

                });

                $('#adjust_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd-mm-yyyy',
                    //minDate:  '12-12-2014',
                    minDate: new Date('01-01-1970'),
                    //maxDate: new Date() 
                    maxDate: new Date()

                });

                $('#dd_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd-mm-yyyy',
                    //minDate:  '12-12-2014',
                    minDate: new Date('01-01-1970'),
                    //maxDate: new Date() 
                    maxDate: new Date()

                });

                $('#ecs_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd-mm-yyyy',
                    //minDate:  '12-12-2014',
                    minDate: new Date('01-01-1970'),
                    //maxDate: new Date() 
                    maxDate: new Date()

                });

                $('#pay_mode').change(function() {
                    if ($(this).val() == 1) {
                        $('.pay_mode_dd').hide();
                        $('.pay_mode_ecs').hide();
                        $('.pay_mode_cheque').show();
                        $('#bank_name_row').show();

                    } 
                    else if ($(this).val() == 2) {
                        $('.pay_mode_cheque').hide();
                        $('.pay_mode_ecs').hide();
                        $('.pay_mode_dd').show();
                        $('#bank_name_row').show();

                    }
                    else if ($(this).val() == 3) {
                        $('.pay_mode_dd').hide();
                        $('.pay_mode_cheque').hide();
                        $('.pay_mode_ecs').show();
                        $('#bank_name_row').show();

                    }
                    else
                    {
                        $('.pay_mode_dd').hide();
                        $('.pay_mode_cheque').hide();
                        $('.pay_mode_ecs').hide();
                        $('#bank_name_row').hide();
                    }
                });



                $('input[name=cash_from_type]').click(function() {
                    if (this.id == "collection") {
                        $("#cash_coll_date_row").show();
                    } else {
                        $("#cash_coll_date_row").hide();
                    }
                });

                /* 
                $(document).on('change', '#fin_year', function() {

                    var fin_year = $("#fin_year").val();
                    $.ajax({
                        url: "Trade_Entry_Form.php",
                        type: "post",
                        data: {
                            "fin_year": btoa(fin_year),
                            "cmd": btoa(2)
                        },
                        success: function(data) {
                            if (data != '') {
                                var Result_Data = JSON.parse(data);
                                $('#tradedetails_data').html(Result_Data['DATA']);
                            }
                        },
                        dataType: 'html'
                    });

                }); */

			 $(document).on('click', "#btn_save", function() {

                        var Current_Field_id = $(this).attr('id');
                        $('#' + Current_Field_id).hide();
                        try {

                            if ($("#serial_no").val().length == '') {
                                throw {
                                    msg: "Enter Serial Number",
                                    foc: "#serial_no"
                                }
                            }

                            if ($("#adjust_date").val().length == '') {
                                throw {
                                    msg: "Enter Adjust Date",
                                    foc: "#adjust_date"
                                }
                            }

                            if ($("#bank_code").val().length == '') {
                                throw {
                                    msg: "Select Bank Code",
                                    foc: "#bank_code"
                                }
                            }

                            if ($("#pay_mode").val().length == '') {
                                throw {
                                    msg: "Enter Payment Mode",
                                    foc: "#pay_mode"
                                }
                            }
							
							var pay_mode = $("#pay_mode").val();
							if(pay_mode == 1){
								if ($("#cheque_no").val().length == '') {
									throw {
										msg: "Enter Cheque Number",
										foc: "#cheque_no"
									}
								}
	
								if ($("#cheque_date").val().length == '') {
									throw {
										msg: "Enter Cheque Date",
										foc: "#cheque_date"
									}
								}
							}else if(pay_mode == 2){
								if ($("#dd_no").val().length == '') {
									throw {
										msg: "Enter DD Number",
										foc: "#dd_no"
									}
								}
	
								if ($("#dd_date").val().length == '') {
									throw {
										msg: "Enter DD Date",
										foc: "#dd_date"
									}
								}
							}else if(pay_mode == 3){
								if ($("#ecs_no").val().length == '') {
									throw {
										msg: "Enter DD Number",
										foc: "#ecs_no"
									}
								}
	
								if ($("#ecs_date").val().length == '') {
									throw {
										msg: "Enter ECS Date",
										foc: "#ecs_date"
									}
								}
								
							}
							
							
                            if ($('input:radio[name=cash_from_type]:checked').length == 0) {
                                throw {
                                    msg: "Choose Cash From",
                                    foc: "input:radio[name=cash_from_type]"
                                }
                            }


                            

                            if ($("#acc_code").val().length == '') {
                                throw {
                                    msg: "Enter Account COde",
                                    foc: "#acc_code"
                                }
                            }
							
							if ($("#acc_head").val().length == '') {
                                throw {
                                    msg: "Enter Account Head",
                                    foc: "#acc_head"
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


       
        <form action="" method="post" class="" enctype="multipart/form-data">
            <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>" name="<?php echo htmlentities($this->page_token); ?>" value="<?php echo htmlentities($this->token($this->page_token)); ?>">
            <div class="card">
                <div class="card-body pl-5 pr-5">
                    <?php
                    if (isset($post_data_array["STATUS"])) {
                        echo $this->ShowMessage($post_data_array["STATUS"], $post_data_array["MESSAGE"]);
                        
                    }
                    ?>



                    <table class="table table-bordered m-0 p-0 tndtp_form_table">
                        <thead class="bg-th-form-dsg">
                            <tr>
                                <th align="center" scope="col" colspan="12">Adjust Bank Receipt Voucher</th>
                            </tr>


                        </thead>

                        <tbody>
                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Serial Number</span></td>

                                <td  scope="col">
                                    <input type="text" id="serial_no" name="serial_no" class="form-control w-50 form-control-sm" />
                                </td>
                            </tr>

                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Date</span></td>

                                <td  scope="col">
                                    <input type="text" id="adjust_date" name="adjust_date" value="" class="form-control form-control-sm user_enter_date" />
                                </td>
                            </tr>

                            <tr>
                                <td   class="text-left font-weight-bold"><span DisplayLabelID="483">Transfer Mode</span></td>
                                <td   scope="col">
                                    <select id="pay_mode" name="pay_mode" class="form-control form-control-sm  w-50">
                                        <option value="">Choose</option>
                                        <option value="1">Cheque</option>
                                        <option value="2">DD</option>
                                        <option value="3">ECS</option>
                                    </select>
                                </td>
                            </tr>

                            <tr class="pay_mode_cheque" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Cheque No</span></td>
                                <td  scope="col">
                                    <input type="text" id="cheque_no" name="cheque_no" class="form-control form-control-sm  w-50" />
                             </td>
								</tr>
								<tr  class="pay_mode_cheque" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="484">Cheque Date</span></td>
                                <td  scope="col">
                                    <input type="text" id="cheque_date" name="cheque_date" value="" class="form-control form-control-sm user_enter_date  w-50" />
                                </td>
                            </tr>

                            <tr class="pay_mode_dd" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">DD No</span></td>
                                <td  scope="col">
                                    <input type="text" id="dd_no" name="dd_no" class="form-control form-control-sm  w-50" />
                                </td>
								</tr>
								<tr  class="pay_mode_dd" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="484">DD Date</span></td>
                                <td  scope="col">
                                    <input type="text" id="dd_date" name="dd_date" value="" class="form-control form-control-sm user_enter_date" />
                                </td>
                            </tr>

                            <tr class="pay_mode_ecs" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">ECS No</span></td>
                                <td  scope="col">
                                    <input type="text" id="ecs_no" name="ecs_no" class="form-control form-control-sm  w-50" />
                                </td>
								</tr>
								 <tr class="pay_mode_ecs" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="484">ECS Date</span></td>
                                <td  scope="col">
                                    <input type="text" id="ecs_date" name="ecs_date" value="" class="form-control form-control-sm user_enter_date" />
                                </td>
                            </tr>
                           	<tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Bank Code </span></td>
                                <td  scope="col">
                                    <select id="bank_code" name="bank_code" class="form-control form-control-sm w-50">
											<option value="">Choose</option>
											<?php
											   $sel_qry = "select bank_id,bank_code,bank_name_en from accounts_master.bank_new where del_flag is null and isactive = 1";
												//$sel_qry_res=$this->prepare($sel_qry,array(":district"=>$_SESSION['USER_DETAILS']['USER_PROFILE']['OFFICE_DETAILS']['district_name_en'],":panchayat"=>$_SESSION['USER_DETAILS']['USER_PROFILE']['OFFICE_DETAILS']['lbody_name_en']),2);
												$sel_qry_res=$this->prepare($sel_qry,array(),2);
												foreach($sel_qry_res as $sel_qry_key=>$sel_qry_row)
												{
											?>
												<option value="<?php echo htmlentities($sel_qry_row['bank_id']);?>" ><?php echo htmlentities($sel_qry_row['bank_code']);?></option>
													
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
                            <tr id="bank_name_row" >
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Bank Name</span></td>

                                <td  scope="col">
                                    <input type="text" id="bank_name" name="bank_name" maxlength="500" value="" class="form-control  form-control-sm Tax_Form_English_Ownername_Property_Tax first_letter_uppercase  w-50" />
                                </td>
                            </tr>

                            

                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Cash From</span></td>
                                <td  scope="col">

                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="accounts" name="cash_from_type" value="D" class="custom-control-input">
                                        <label class="custom-control-label" for="accounts"><span DisplayLabelID="371">Debit</span></label>
                                    </div> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="collection" name="cash_from_type" value="C" class="custom-control-input">
                                        <label class="custom-control-label" for="collection"><span DisplayLabelID="372">Credit</span></label>
                                    </div>

                                </td>
                            </tr>
                            <tr id="debit_amt" style="display:none">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Debit Amount</span></td>
                                <td  scope="col">
                                    <input type="text" id="debit_amount" name="debit_amount" class="form-control form-control-sm w-50 Number_Field" /></td>
                            </tr>
                            <tr id="credit_amt" style="display:none">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Credit Amount</span></td>
                                <td  scope="col">
                                    <input type="text" id="credit_amount" name="credit_amount" class="form-control form-control-sm w-50 Number_Field"/></td>
                            </tr>
                            <tr id="cash_coll_date_row" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Cash Collection Date</span></td>
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
                                        $sel_account_code_id = "SELECT bank_payment_voucher_account_codes_id,acc_code as account_code,acc_head FROM accounts_master.bank_payment_voucher_account_codes ORDER BY account_code DESC";

                                        $sel_account_codeid_res = $this->prepare($sel_account_code_id, array(), 2);

                                        foreach ($sel_account_codeid_res as $sel_account_codeid_key => $sel_account_codeid_row) {
                                            // $sel = "";
                                            // if(isset($data_array_val['finyear']) && $data_array_val['finyear']==$sel_account_codeid_row['fin_yearid'])
                                            // {
                                            // 	$sel="selected";
                                            // }
                                        ?>
                                            <option value="<?php echo htmlentities($sel_account_codeid_row['bank_payment_voucher_account_codes_id']); ?>">
                                                <?php echo htmlentities($sel_account_codeid_row['account_code']); ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <script>
                                        document.getElementById('acc_code').value =
                                            '<?php if (isset($data_array_val['acc_code'])) {
                                                    echo htmlentities($data_array_val['acc_code']);
                                                } ?>';
                                    </script>
                                </td>
                            </tr>

                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Account Head</span></td>
                                <td  scope="col">
                                    <textarea id="acc_head" name="acc_head" rows="4" cols="50" class="form-control w-50 form-control-sm"></textarea>
                                </td>
                            </tr>



                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Amount</span></td>
                                <td  scope="col">
                                    <input type="text" id="amount" name="amount" class="form-control form-control-sm w-50 Number_Field" /></td>
                            </tr>

                           

                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Rupees (In Words)</span></td>
                                <td  scope="col">
                                    <textarea id="amt_in_word" name="amt_in_word" rows="4" cols="50" class="form-control w-50 form-control-sm Text_Field"></textarea>
                                </td>
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

            



        </form>
<?php

        // #############

        // PAGE CONTENT END

        // #############

        $ob_output_main_forms = ob_get_contents();
        ob_clean();

        $this->Template($this->getCurrentUserTemplate(), "User Role", $ob_output_main_forms, array(
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
		// print_r($save_data);exit;
       // if (!isset($save_data["del_id"])) {
		   $cheque_no = null;
			$cheque_date = null;
			$dd_no = null;
			$dd_date = null;
			$ecs_no = null;
			$ecs_date = null;
			$cash_coll_date = null;
			$serial_no = $save_data['serial_no'];
            $adjust_date = $save_data['adjust_date'];
            list($date_completion, $month_completion, $year_completion) = explode('-', $adjust_date);
            $adjust_date = $year_completion . '-' . $month_completion . '-' . $date_completion;
            $pay_mode = $save_data['pay_mode'];
                   
			
            $bank_code = $save_data['bank_code'];
            $bank_name = $save_data['bank_name'];
			$cash_from_type = $save_data['cash_from_type'];
            
            $acc_code = $save_data['acc_code'];
            $remark = $save_data['remark'];
            $amount = $save_data['amount'];
			if($cash_from_type == 0){
				$cash_coll_date = $save_data['cash_coll_date'];
                list($date_completion, $month_completion, $year_completion) = explode('-', $cash_coll_date);
            $cash_coll_date = $year_completion . '-' . $month_completion . '-' . $date_completion;
			}
			if($pay_mode == 1){
				$cheque_no = $save_data['cheque_no'];
				$cheque_date = $save_data['cheque_date'];
                list($date_completion, $month_completion, $year_completion) = explode('-', $cheque_date);
                $cheque_date = $year_completion . '-' . $month_completion . '-' . $date_completion;
			}elseif($pay_mode == 2){
				$dd_no = $save_data['dd_no'];
	            $dd_date = $save_data['dd_date'];
                list($date_completion, $month_completion, $year_completion) = explode('-', $dd_date);
                $dd_date = $year_completion . '-' . $month_completion . '-' . $date_completion;
			}elseif($pay_mode == 3){
				$ecs_no = $save_data['ecs_no'];
	            $ecs_date = $save_data['ecs_date'];
                list($date_completion, $month_completion, $year_completion) = explode('-', $ecs_date);
                $ecs_date = $year_completion . '-' . $month_completion . '-' . $date_completion;
			}

           /* $min_value_Validation = $this->Field_Validation(array(
                "Field_Type" => "number",
                "Field_Value" => $save_data["min_value"],
                "Field_Max_length" => 250,
                "Field_Min_length" => 0,
                "Field_Label_Name" => "Minimum Value"
            ));

            if ($min_value_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "min_value",
                    "MESSAGE" => "Invalid Minimum Value"
                ), $save_data));
            }

            $max_value_Validation = $this->Field_Validation(array(
                "Field_Type" => "number",
                "Field_Value" => $save_data["max_value"],
                "Field_Max_length" => 250,
                "Field_Min_length" => 0,
                "Field_Label_Name" => "Maximum Value"
            ));

            if ($max_value_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "max_value",
                    "MESSAGE" => "Invalid Maximum Value"
                ), $save_data));
            }

            $slab_rate_Validation = $this->Field_Validation(array(
                "Field_Type" => "float",
                "Field_Value" => $slab_rate,
                "Field_Max_length" => 250,
                "Field_Min_length" => 0,
                "Field_Label_Name" => "Slab Rate value"
            ));

            if ($slab_rate_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "slab_rate",
                    "MESSAGE" => "Invalid Slab Rate value"
                ), $save_data));
            }

            $resolutionno_Validation = $this->Field_Validation(array(
                "Field_Type" => "number",
                "Field_Value" => $resolution_no,
                "Field_Max_length" => 250,
                "Field_Min_length" => 0,
                "Field_Label_Name" => "Resolution No"
            ));

            if ($resolutionno_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "resolution_no",
                    "MESSAGE" => "Invalid Resolution No"
                ), $save_data));
            }




            $doc_link_id_Validation = $this->Field_Validation(array(
                "Field_Type" => "number",
                "Field_Value" => $doc_link_id,
                "Field_Max_length" => 250,
                "Field_Min_length" => 0,
                "Field_Label_Name" => "Document Link"
            ));

            if ($doc_link_id_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "doc_link_id",
                    "MESSAGE" => "Choose Document Link"
                ), $save_data));
            }



            $isactive_Validation = $this->Field_Validation(array(
                "Field_Type" => "number",
                "Field_Value" => $isactive,
                "Field_Max_length" => 1,
                "Field_Min_length" => 1,
                "Field_Label_Name" => "Is active"
            ));

            if ($isactive_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "isactive",
                    "MESSAGE" => "Invalid is active"
                ), $save_data));
            }
        }
*/
		 $message = 'Data Saved SuccessFully';
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
			$message = 'Data Deleted SuccessFully';
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
                    "MESSAGE" => "Invalid data 1"
                ), $save_data));
            }
			$message = 'Data Updated SuccessFully';
        }else{
			$edit_id = 0;	
		}


        $statecode = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();

        $getCurrentUser = $this->getCurrentUser();
        $getIpAddress = $this->getIpAddress();

		$save_query = "select accounts_master.t_adjust_bank_receipt_voucher(:statecode,:dcode,:lbcode,:serial_no,:adjust_date,:pay_mode,:cheque_no,:cheque_date,:dd_no,:dd_date,:ecs_no,:ecs_date,:bank_code,:bank_name,:cash_from_type,:cash_coll_date,:acc_code,:remark,:amount,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
$result=$this->prepare($save_query,array(":statecode"=>$statecode,":dcode"=>$dcode,":lbcode"=>$lbcode,":serial_no"=>$serial_no,":adjust_date"=>$adjust_date,":pay_mode"=>$pay_mode,":cheque_no"=>$cheque_no,":cheque_date"=>$cheque_date,":dd_no"=>$dd_no,":dd_date"=>$dd_date,":ecs_no"=>$ecs_no,":ecs_date"=>$ecs_date,":bank_code"=>$bank_code,":bank_name"=>$bank_name,":cash_from_type"=>$cash_from_type,":cash_coll_date"=>$cash_coll_date,":acc_code"=>$acc_code,":remark"=>$remark,":amount"=>$amount,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>$edit_id,":delete_id"=>$del_id),4);

// var_dump($result);exit;
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

$home = new Adjust_Bank_Receipt_Voucher();


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
}else if (isset($_POST['cmd'])) {
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
}

?>