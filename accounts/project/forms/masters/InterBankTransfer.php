<?php
require_once  '../../config/config.php';
class InterBankTransfer  extends ConfigClass
{

    public $page_token = "inter_bank_transfer";

    function __construct()
    {
        //$this->pageRoleAccessCheck(array(1));
    }

    public function main_form($data_array = array())
    {


        ob_start();

        // #############

        // PAGE CONTENT START

        // #############

        // PLACE YOUR CODE HERE
        $state_code = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $tpcode = $this->getCurrentLocalBodyCode();
        $lang_code_2d = $this->getCurrentUserLanguage2D();
        ?>
        <input type="hidden" id="page_lable_id" name="page_lable_id" value="48" />
        <script type="text/javascript">
            $(document).ready(function() {
				$(document).on('change', '#bank_code', function() {
                    var bank_code = $("#bank_code").val();
					if(bank_code != ''){
						$.ajax({
							url: "InterBankTransfer.php",
							type: "post",
							data: {
								"bank_code": btoa(bank_code),
								"cmd": btoa(2)
							},
							success: function(data) {
								if (data != '') {
									var Result_Data = JSON.parse(data);
									$('#bank_name_en').val(Result_Data['DATA']);
								}
							},
							dataType: 'html'
						});	
					}
					else{
						alert('Select Bank Code');
					}

                });
                function updateTextInput() {
                    var accounts = document.querySelector('input[name="cash_from_type"]:checked').value;
                    if(accounts != ''){
                        $.ajax({
                            url: "InterBankTransfer.php",
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

                $('#date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'mm-dd-yyyy',
                    //minDate:  '12-12-2014',
                    minDate: new Date('01-01-1970'),
                    //maxDate: new Date() 
                    maxDate: new Date()

                });

                $('#cheque_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'mm-dd-yyyy',
                    //minDate:  '12-12-2014',
                    minDate: new Date('01-01-1970'),
                    //maxDate: new Date() 
                    maxDate: new Date()

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

                $('#dd_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'mm-dd-yyyy',
                    //minDate:  '12-12-2014',
                    minDate: new Date('01-01-1970'),
                    //maxDate: new Date() 
                    maxDate: new Date()

                });

                $('#ecs_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'mm-dd-yyyy',
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
                        // $('#bank_name_row').show();

                    } 
                    else if ($(this).val() == 2) {
                        $('.pay_mode_cheque').hide();
                        $('.pay_mode_ecs').hide();
                        $('.pay_mode_dd').show();
                        // $('#bank_name_row').show();

                    }
                    else if ($(this).val() == 3) {
                        $('.pay_mode_dd').hide();
                        $('.pay_mode_cheque').hide();
                        $('.pay_mode_ecs').show();
                        // $('#bank_name_row').show();

                    }
                    else
                    {
                        $('.pay_mode_dd').hide();
                        $('.pay_mode_cheque').hide();
                        $('.pay_mode_ecs').hide();
                        // $('#bank_name_row').hide();
                    }
                });

                $('input[name=cash_from_type]').click(function() {
                    if (this.id == "collection") {
                        $("#cash_coll_date_row").show();
                    } else {
                        $("#cash_coll_date_row").hide();
                    }
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
			
        </style>

        <div class="container pt-3"> 
            <?php
                if (isset($data_array["mode"]) && $data_array["mode"] == "edit") {
                    ?>
                 <input class="form-control form-control-sm" type="hidden"
                                id="edit_id" name='edit_id'
                                value="<?php echo htmlentities($data_array["edit_id"]); ?>">
                 <?php
                     $data_array_edit_new ="select  id,chalan_no,chalan_date,pay_mode,cheque_no, cheque_date, dd_no, dd_date, ecs_no, ecs_date,cash_from_type,amount,bank_code,bank_name,remark,statecode,cash_coll_date, acc_code from accounts_master.t_inter_bank_transfer where id=:edit_id";
                    $data_array_edit = $this->prepare($data_array_edit_new,array(":edit_id"=>$data_array["edit_id"]),4);
                    if(isset($data_array_edit['id']) &&  $data_array_edit['id']!=''){
                        $data_array = array_merge($data_array, $data_array_edit);
                    }else{
                        ?>
                        <script>
                            alert('Invalid Id');
                            window.location.href = "<?php echo $site_data->website_url;?>/project/forms/masters/InterBankTransfer.php";
                        </script>
                        <?php 
                    }
                    
                } else if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                    ?>
                 <input class="form-control form-control-sm" type="hidden"
                                id="del_id" name='del_id'
                                value="<?php echo htmlentities($data_array["del_id"]); ?>">
                 <?php
                    $data_array_delete_new ="select  id,chalan_no,chalan_date,pay_mode,cheque_no, cheque_date, dd_no, dd_date, ecs_no, ecs_date,cash_from_type,amount,bank_code,bank_name,remark,statecode,cash_coll_date, acc_code from accounts_master.t_inter_bank_transfer where id=:del_id";
                     $data_array_delete = $this->prepare($data_array_delete_new,array(":del_id"=>$data_array["del_id"]),4);
                     if(isset($data_array_delete['id']) &&  $data_array_delete['id']!=''){
                         $data_array = array_merge($data_array, $data_array_delete);
                    }else{
                        ?>
                        <script>
                            alert('Invalid Id');
                            window.location.href = "<?php echo $site_data->website_url;?>/project/forms/masters/InterBankTransfer.php";
                        </script>
                        <?php 
                    }
                }
                if (isset($data_array["STATUS"])) {
                    echo $this->ShowMessage($data_array["STATUS"], $data_array["MESSAGE"]);
                }
                ?>
        <form action="InterBankTransfer.php" method="post" class="" enctype="multipart/form-data" autocomplete="off">
            <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>" name="<?php echo htmlentities($this->page_token); ?>" value="<?php echo htmlentities($this->token($this->page_token)); ?>">
            <div class="card">
                <div class="card-body pl-5 pr-5">
                    <?php
                    if (isset($data_array["STATUS"])) {
                        echo $this->ShowMessage($data_array["STATUS"], $data_array["MESSAGE"]);
                    }
                    ?>
                    <table class="table table-bordered m-0 p-0 tndtp_form_table">
                        <thead class="bg-th-form-dsg">
                            <tr>
                                <th align="center" scope="col" colspan="12">Inter Fund Transfer Voucher</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Chalan Number</span></td>
                                <td  scope="col">
                                    <input type="text" id="chalan_no" name="chalan_no" class="form-control w-50 form-control-sm" value="<?php if(isset($data_array['chalan_no'])) { echo htmlentities($data_array['chalan_no']); }?>" />
                                </td>
                            </tr>
                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483"> Chalan Date</span></td>
                                <td  scope="col">
                                    <input type="text" id="chalan_date" name="chalan_date" value="<?php if(isset($data_array['chalan_date'])) { echo htmlentities($data_array['chalan_date']); }?>" class="form-control form-control-sm user_enter_date" />
                                </td>
                            </tr>
                            <tr>
                                <td   class="text-left font-weight-bold"><span DisplayLabelID="483">Transfer  Mode</span></td>                            
                                <td  scope="col">
                                    <select id="pay_mode" name="pay_mode" class="form-control form-control-sm w-50">
                                       <option value="">Choose</option>
                                        <?php
                                        $sel_account_code_id = "SELECT paymenttypeid,paymenttype ,paymenttype_ta FROM master.m_paymenttype ORDER BY paymenttypeid ASC"; 
                                        $sel_account_codeid_res = $this->prepare($sel_account_code_id, array(), 2);
                                        foreach ($sel_account_codeid_res as $sel_account_codeid_key => $sel_account_codeid_row) {                                        
                                        ?>
                                            <option value="<?php echo htmlentities($sel_account_codeid_row['paymenttypeid']); ?>"><?php echo htmlentities($sel_account_codeid_row['paymenttype']); ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <script>
                                        document.getElementById('pay_mode').value = 
                                            '<?php 
                                                if (isset($data_array['pay_mode']) && isset($data_array['pay_mode'])) {
                                                    echo htmlentities($data_array['pay_mode']);
                                                }
                                            ?>';
                                    </script>
                                </td>
                            </tr>
                            <tr class="pay_mode_cheque" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Cheque No</span></td>
                                <td  scope="col">
                                    <input type="text" id="cheque_no" name="cheque_no" class="form-control form-control-sm  w-50"  value="<?php if(isset($data_array['cheque_no'])) { echo htmlentities($data_array['cheque_no']); }?>" />
                             </td>
								</tr>
								<tr  class="pay_mode_cheque" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="484">Cheque Date</span></td>
                                <td  scope="col">
                                    <input type="text" id="cheque_date" name="cheque_date"  value="<?php if(isset($data_array['cheque_date'])) { echo htmlentities($data_array['cheque_date']); }?>" class="form-control form-control-sm user_enter_date  w-50" />
                                </td>
                            </tr>
                            <tr class="pay_mode_dd" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">DD No</span></td>
                                <td  scope="col">
                                    <input type="text" id="dd_no" name="dd_no" class="form-control form-control-sm  w-50"  value="<?php if(isset($data_array['dd_no'])) { echo htmlentities($data_array['dd_no']); }?>" />
                                </td>
								</tr>
								<tr  class="pay_mode_dd" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="484">DD Date</span></td>
                                <td  scope="col">
                                    <input type="text" id="dd_date" name="dd_date" value="" class="form-control form-control-sm user_enter_date"  value="<?php if(isset($data_array['dd_date'])) { echo htmlentities($data_array['dd_date']); }?>" />
                                </td>
                            </tr>

                            <tr class="pay_mode_ecs" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">ECS No</span></td>
                                <td  scope="col">
                                    <input type="text" id="ecs_no" name="ecs_no" class="form-control form-control-sm  w-50"  value="<?php if(isset($data_array['ecs_no'])) { echo htmlentities($data_array['ecs_no']); }?>" />
                                </td>
								</tr>
								 <tr class="pay_mode_ecs" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="484">ECS Date</span></td>
                                <td  scope="col">
                                    <input type="text" id="ecs_date" name="ecs_date" value="" class="form-control form-control-sm user_enter_date"  value="<?php if(isset($data_array['ecs_date'])) { echo htmlentities($data_array['ecs_date']); }?>" />
                                </td>
                            </tr>
							<tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Bank Code & Bank Name </span></td>
                                <td  scope="col">
                                    <select id="bank_code" name="bank_code" class="form-control form-control-sm w-50">
											<option value="">Choose</option>
											<?php
											   $sel_qry = "select bank_id,bank_code,bank_name_en from accounts_master.m_bank where del_flag is null and isactive = 1";
												//$sel_qry_res=$this->prepare($sel_qry,array(":district"=>$_SESSION['USER_DETAILS']['USER_PROFILE']['OFFICE_DETAILS']['district_name_en'],":panchayat"=>$_SESSION['USER_DETAILS']['USER_PROFILE']['OFFICE_DETAILS']['lbody_name_en']),2);
												$sel_qry_res=$this->prepare($sel_qry,array(),2);
												foreach($sel_qry_res as $sel_qry_key=>$sel_qry_row)
												{
											?>
												<option value="<?php echo htmlentities($sel_qry_row['bank_id']); ?>">
    <?php echo htmlentities($sel_qry_row['bank_code']) . ' - ' . htmlentities($sel_qry_row['bank_name_en']); ?>
</option>

													
											<?php }?>
									   </select>
                                   <?php ?> <script>
                                        document.getElementById('bank_code').value =
                                            '<?php if (isset($data_array['bank_code'])) {
                                                    echo htmlentities($data_array['bank_code']);
                                                } ?>';
                                    </script><?php ?>
                                </td>
                            </tr>
                         <!--    <tr id="bank_name_row" >
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Bank Name</span></td>

                                <td  scope="col">
                                    <input type="text" id="bank_name" name="bank_name" maxlength="500" value="" class="form-control  form-control-sm Tax_Form_English_Ownername_Property_Tax first_letter_uppercase  w-50" />
                                </td>
                            </tr>-->
                            <tr>
    <td class="text-left font-weight-bold">
        <span DisplayLabelID="483">Cash From</span>
    </td>
    <td scope="col">
        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="accounts" name="cash_from_type" value="2" class="custom-control-input"
                <?php echo (isset($data_array['cash_from_type']) && $data_array['cash_from_type'] == 2) ? 'checked' : ''; ?>>
            <label class="custom-control-label" for="accounts">
                <span DisplayLabelID="371">Expense</span>
            </label>
        </div> 
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="collection" name="cash_from_type" value="1" class="custom-control-input"
                <?php echo (isset($data_array['cash_from_type']) && $data_array['cash_from_type'] == 1) ? 'checked' : ''; ?>>
            <label class="custom-control-label" for="collection">
                <span DisplayLabelID="372">Income</span>
            </label>
        </div>
    </td>
</tr>


                            <tr id="cash_coll_date_row" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Cash Collection Date</span></td>
                                <td  scope="col">
                                    <input type="text" id="cash_coll_date" name="cash_coll_date" value="" class="form-control form-control-sm user_enter_date" />
                                </td>
                            </tr>

                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Account Code & Account Head</span></td>
                                <td  scope="col">
                                    <select id="acc_code" name="acc_code" class="form-control form-control-sm w-50">
                                       <option value="">Choose</option>
                                        <?php
                                        $sel_account_code_id = "SELECT account_head_id,old_account_head_code as account_code,account_head_name_en FROM accounts_master.m_account_head ORDER BY account_code DESC";

                                        $sel_account_codeid_res = $this->prepare($sel_account_code_id, array(), 2);

                                        foreach ($sel_account_codeid_res as $sel_account_codeid_key => $sel_account_codeid_row) {
                                        ?>
                                            <option value="<?php echo htmlentities($sel_account_codeid_row['account_head_id']); ?>">
                                <?php echo htmlentities($sel_account_codeid_row['account_code']) . ' - ' . htmlentities($sel_account_codeid_row['account_head_name_en']); ?>
                            </option>

                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <script>
    document.getElementById('acc_code').value = 
        '<?php 
            if (isset($data_array_val['account_code']) && isset($data_array_val['account_head_name_en'])) {
                echo htmlentities($data_array_val['account_code']) . ' - ' . htmlentities($data_array_val['account_head_name_en']);
            }
        ?>';
</script>

                                </td>
                            </tr>
<!-- 
                            <tr>
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
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Rupees (In Words)</span></td>
                                <td  scope="col">
                                    <textarea id="amt_in_word" name="amt_in_word" rows="4" cols="50" class="form-control w-50 form-control-sm"></textarea>
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

          <div class="card">
        <div class="card-body" style=" background-color:#e1f9ff;border:1px solid;border-color:#94f0f1">

            <div class="single-table">
                <table class="table table-bordered text-center table-striped tndtp_report_table" id="dataTable2">
                    <thead class="text-left">

                        <tr>
                            <th scope="col"><span DisplayLabelID="311">S.No</span></th>
                             <th scope="col"><span DisplayLabelID="671">Chalan Number</span></th>
                              <th scope="col"><span DisplayLabelID="671">Pay Mode</span></th>
                               <th scope="col"><span DisplayLabelID="671">Cash Type</span></th>
                                <th scope="col"><span DisplayLabelID="671">Amount</span></th>
                            <th scope="col"><span DisplayLabelID="329">Bank Code</span></th>
                            <th scope="col"><span DisplayLabelID="186">Bank Name</span></th>
                            <th scope="col"><span DisplayLabelID="671">Remark</span></th>
                        
                            <th scope="col"><span DisplayLabelID="354">Action</span></th>
                        </tr>
                    </thead>
                    <tbody id="tradedetails_data">
                        <?php
                        $sel_tradedetails_details = "SELECT id,chalan_no,pay_mode,cash_from_type,amount,bank_id,a.bank_code, bank_name_en,remark from 
(select  id,chalan_no,pay_mode,cash_from_type,amount,bank_code,bank_name,remark,statecode from accounts_master.t_inter_bank_transfer where dcode=:dcode and lbcode=:lbcode  )
as a
left join
(select bank_id,bank_code,bank_name_en from accounts_master.m_bank where del_flag is null and isactive = :isactive ) as b
on a.id=b.bank_id";

                        $sel_tradedetails_details_res = $this->prepare($sel_tradedetails_details, array(":dcode" => $dcode, ":lbcode" => $tpcode, ":isactive" => 1), 2);
                        if (count($sel_tradedetails_details_res) > 0) {
                            foreach ($sel_tradedetails_details_res as $sel_tradedetails_details_key => $sel_tradedetails_details_row) {
                        ?>
                        <tr>
                            <td class="text-center"><?php echo htmlentities($sel_tradedetails_details_key + 1); ?></td>
                            <td class="text-left">
                                <?php echo htmlentities($sel_tradedetails_details_row['chalan_no']); ?>
                            </td>
                            <td class="text-left">
                                <?php echo htmlentities($sel_tradedetails_details_row['pay_mode']); ?>
                            </td>
                            
                               <td align="center">
                                <?php if ($sel_tradedetails_details_row['cash_from_type'] == 1) {
                                    echo 'Credit';
                                } else {
                                    echo 'Debit';
                                } ?>
                            </td>
                            <td class="text-left">
                                <?php echo htmlentities($sel_tradedetails_details_row['amount']); ?>
                            </td>
                            <td class="text-left">
                                <?php echo htmlentities($sel_tradedetails_details_row['bank_name_en']); ?>
                            </td>
                            <td class="text-left">
                                <?php echo htmlentities($sel_tradedetails_details_row['bank_code']); ?>
                            </td>
                            <td class="text-left">
                                <?php echo htmlentities($sel_tradedetails_details_row['remark']); ?></td>
                   
                            <td align="center"><a
                                    href="?edit_id=<?php echo htmlentities(base64_encode($sel_tradedetails_details_row['id'])); ?>"
                                    class="btn btn-warning btn-sm"><?php /* ?><i class="fa fa-pencil pr-1"
                                        aria-hidden="true"></i><?php */ ?>Edit</a>
                                <a href="?del_id=<?php echo htmlentities(base64_encode($sel_tradedetails_details_row['id'])); ?>"
                                    class="btn btn-danger btn-sm">Delete</a>
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
		   $cheque_no = null;
			$cheque_date = null;
			$dd_no = null;
			$dd_date = null;
			$ecs_no = null;
			$ecs_date = null;
			$cash_coll_date = null;
			$chalan_no = $save_data['chalan_no'];
            $chalan_date = $save_data['chalan_date'];
            $pay_mode = $save_data['pay_mode'];
                 
			// print_r($save_data);die;
            $bank_code = $save_data['bank_code'];
            // $bank_name = $save_data['bank_name_en'];
			$cash_from_type = $save_data['cash_from_type'];
            $acc_code = $save_data['acc_code'];
            $remark = $save_data['remark'];
            $amount = $save_data['amount'];
			if($cash_from_type == 0){
				$cash_coll_date = $save_data['cash_coll_date'];
			}
			if($pay_mode == 1){
				$cheque_no = $save_data['cheque_no'];
				$cheque_date = $save_data['cheque_date'];
			}elseif($pay_mode == 2){
				$dd_no = $save_data['dd_no'];
	            $dd_date = $save_data['dd_date'];
			}elseif($pay_mode == 3){
				$ecs_no = $save_data['ecs_no'];
	            $ecs_date = $save_data['ecs_date'];
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
                    "MESSAGE" => "Invalid data 1"
                ), $save_data));
            }
			$message = 'Data Updated SccessFully';
        }else{
			$edit_id = 0;	
		}


        $statecode = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();
        $site_data = $this->siteData();
        $getCurrentUser = $this->getCurrentUser();
        $getIpAddress = $this->getIpAddress();

		$save_query = "select accounts_master.sp_inter_bank_transfer(:statecode,:dcode,:lbcode,:chalan_no,:chalan_date,:pay_mode,:cheque_no,:cheque_date,:dd_no,:dd_date,:ecs_no,:ecs_date,:bank_code,:bank_name,:cash_from_type,:cash_coll_date,:acc_code,:remark,:amount,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
$result=$this->prepare($save_query,array(":statecode"=>$statecode,":dcode"=>$dcode,":lbcode"=>$lbcode,":chalan_no"=>$chalan_no,":chalan_date"=>$chalan_date,":pay_mode"=>$pay_mode,":cheque_no"=>$cheque_no,":cheque_date"=>$cheque_date,":dd_no"=>$dd_no,":dd_date"=>$dd_date,":ecs_no"=>$ecs_no,":ecs_date"=>$ecs_date,":bank_code"=>$bank_code,":bank_name"=>NULL,":cash_from_type"=>$cash_from_type,":cash_coll_date"=>$cash_coll_date,":acc_code"=>$acc_code,":remark"=>$remark,":amount"=>$amount,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>$edit_id,":delete_id"=>$del_id),4);

        if ($this->prepareStatus($result) == true) {
             $this->main_form(array(
                 "STATUS" => "SUCCESS",
                 "STATUS_TYPE" => "FORM",
                 "MESSAGE" => $message
             ));				 
            exit;
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
if (isset($_GET["edit_id"])) {
    $edit_id = base64_decode($_GET["edit_id"]);
    $edit_id_Validation = $home->Field_Validation(
    array
    (
    'Field_Type'=>'number',
    'Field_Value'=>$edit_id,
    'Field_Name'=>'edit_id',
    'Field_Label_Name'=>'Invalid Edit Id',
    )
    );
    
    if ($edit_id_Validation['Status'] == "Error") {
        $home->main_form(array_merge(array(
            "STATUS" => "ERROR", 
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "isactive",
            "MESSAGE" => $edit_id_Validation['Message']
        ), $save_data));
    exit;           
    }   
    $home->main_form(array(
        "mode" => "edit",
        "mode_name" => "Update",
        "mode_class" => "btn-warning",
        "mode_icon" => "fa fa-pencil",
        "edit_id" => $edit_id
    ));
}
if (isset($_GET["del_id"])) {
    $del_id = base64_decode($_GET["del_id"]);
    $del_id_Validation = $home->Field_Validation(
    array
    (
    'Field_Type'=>'number',
    'Field_Value'=>$del_id,
    'Field_Name'=>'del_id',
    'Field_Label_Name'=>'Invalid Delete Id',
    )
    );
    
    if ($del_id_Validation['Status'] == "Error") {
        $home->main_form(array_merge(array(
            "STATUS" => "ERROR", 
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "del_id",
            "MESSAGE" => $del_id_Validation['Message']
        ), $save_data));
        exit;           
    }   
    $home->main_form(array(
        "mode" => "delete",
        "mode_name" => "Delete",
        "mode_class" => "btn-danger",
        "mode_icon" => "fa fa-trash-o",
        "del_id" => $del_id
    ));
} 


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
        $bank_id = base64_decode($_POST['bank_code']);
		$sel_qry = "select bank_id,bank_code,bank_name_en from accounts_master.m_bank where bank_id=:bank_id and  del_flag is null and isactive = 1";
		
		$sel_qry_res=$home->prepare($sel_qry,array(":bank_id"=>$bank_id),4);
												
        $Result['STATUS'] = 'SUCCESS';
        $Result['DATA'] = $sel_qry_res['bank_name_en'];
        echo json_encode($Result);
        exit;
    }

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




    // if ($cmd == 3) {
    //     $accounts = base64_decode($_POST['accounts']);
    //     $sel_qry = "SELECT account_head_id,old_account_head_code as account_code,account_head_name_en FROM accounts_master.m_account_head where account_type=:account ORDER BY account_code DESC";
        
    //     $sel_qry_res=$home->prepare($sel_qry,array(":account"=>$accounts),4);
                                                
    //     $Result['STATUS'] = 'SUCCESS';
    //     $Result['DATA'] = $sel_qry_res['account_head_name_en'];
    //     echo json_encode($Result);
    //     exit;
    // }
}

?>