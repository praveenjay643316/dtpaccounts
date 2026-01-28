<?php
require_once  '../../config/config.php';
class Inter_Fund_Transfer_Voucher  extends ConfigClass
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
        $lbcode = $this->getCurrentLocalBodyCode();
        $lang_code_2d = $this->getCurrentUserLanguage2D();
        $fin_year = $this->getFinYear();
        ?>
        <input type="hidden" id="page_lable_id" name="page_lable_id" value="48" />
        <script type="text/javascript">
            $(document).ready(function() {
				$(document).on('change', '#bank_code', function() {
                    var transfer_mode = $('#transfer_mode').val();
                    var bank_code = $(this).val();
                    var bank_name = $(this).find(':selected').data('name');
                    if(transfer_mode == 2){
                        $.ajax({
                            url: "Inter_Fund_Transfer_Voucher.php",
                            type: "post",
                            data: {
                                cmd: btoa(1),
                                bank_code: btoa(bank_code),
                                transfer_mode: btoa(transfer_mode)
                            },
                            success: function(data) {
                                if (JSON.parse(data) == '-') {
                                    alert('no cheque available , please select other bank or branch');
                                    $("#cheque_no").val('');
                                } else {
                                    data1=JSON.parse(data) ; 
                                    console.log(data1);                                  
                                    $("#cheque_no").val(data1.cheque_id);
                                    $("#cheque_no_text").text(data1.cheque_number);
                                }
                            },
                            error: function(xhr, error, status) {
                                console.log(error);
                                console.log(status);
                            },
                            dataType: 'html'
                        });
                    }
                });

                $('#chalan_date').datepicker({
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

                $('#transfer_mode').change(function() {
                    if ($(this).val() == 2) {
                        $('.pay_mode_cheque').show();
                        $('.pay_mode_ecs').hide();
                        $('.pay_mode_dd').hide();
                    }
                    else if ($(this).val() == 4) {
                        $('.pay_mode_dd').hide();
                        $('.pay_mode_cheque').hide();
                        $('.pay_mode_ecs').show();
                    }
                    $('.balance_tr').show();
                    $('.bank_code_tr').show();
                });
                $(document).on('click', "#btn_save", function() {
                    var Current_Field_id = $(this).attr('id');
                    $('#' + Current_Field_id).hide();
                    try {

                        if ($("#ift_chalan_no").val().length == '') {
                            throw {
                                msg: "Enter Chalan Number",
                                foc: "#ift_chalan_no"
                            }
                        }
                        if ($("#ift_serial_no").val().length == '') {
                            throw {
                                msg: "Enter Chalan Number",
                                foc: "#ift_serial_no"
                            }
                        }

                        if ($("#chalan_date").val().length == '') {
                            throw {
                                msg: "Enter Chalan Date",
                                foc: "#chalan_date"
                            }
                        }

                        if ($("#transfer_mode").val().length == '') {
                            throw {
                                msg: "Select Bank Code",
                                foc: "#transfer_mode"
                            }
                        }else{
                            var transfer_mode = $("#transfer_mode").val();
                        }
                        if(transfer_mode == 2){
                            if ($("#bank_code").val().length == '') {
                                throw {
                                    msg: "Select Bank Code",
                                    foc: "#bank_code"
                                }
                            }

                            if ($("#cheque_no").val().length == '') {
                                throw {
                                    msg: "Enter Cheque Number",
                                    foc: "#cheque_no"
                                }
                            }

                            if ($("#amount").val().length == '') {
                                throw {
                                    msg: "Enter Amount",
                                    foc: "#amount"
                                }
                            }
                        }else if(transfer_mode == 4){
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
                            if ($("#amount").val().length == '') {
                                throw {
                                    msg: "Enter Amount",
                                    foc: "#amount"
                                }
                            }                         
                        }
                        if ($("#remark").val().length == '') {
                            throw {
                                msg: "Enter Remark",
                                foc: "#remark"
                            }
                        }
                        var debit_amount = $("#debit_total_amount").val();
                        var credit_amount = $("#credit_total_amount").val()
                        if(debit_amount != credit_amount){
                            throw {
                                    msg: "Debit Amount And Credit Amount Must Be Same",
                                    foc: "#debit_amount"
                                };
                        }
                        return true;
                    } catch (e) {
                        alert(e.msg);
                        $('#' + Current_Field_id).show();
                        $(e.foc).focus();
                        return false;
                    }
                    
                });     
                $(document).on('click', '#btn_debit_add', function() {
                    try {
                        if ($("#ift_serial_no").val().length == '') {
                            throw {
                                msg: "Missing Serial Number",
                                foc: "#iftv_serial_no"
                            }
                        }else{
                            var iftv_serial_no = $("#ift_serial_no").val();
                        }
                      
                        if ($("#debit_bank_code").val().length == '') {
                            throw {
                                msg: "Select Bank Code",
                                foc: "#bank_code"
                            }
                        }else{
                            var bank_code = $("#debit_bank_code").val();
                        }

                        if ($("#debit_bank_head").val().length == '') {
                            throw {
                                msg: "Enter Bank Head",
                                foc: "#debit_bank_head"
                            }
                        }else{
                            var bank_head = $("#debit_bank_head").val();
                        }

                        if ($("#debit_amount").val().length == '') {
                            throw {
                                msg: "Enter Debit Amount",
                                foc: "#bank_head"
                            }
                        }else{
                            var amount = $("#debit_amount").val();
                        }
                       
                    } catch (e) {
                        $(e.foc).focus();
                        return false;
                    }
                    var edit_id = $("#debit_edit_id").val();
                    var delete_id = $("#debit_delete_id").val();
                    
                    $.ajax({
						url: "Inter_Fund_Transfer_Voucher.php",
						type: "post",
						data: {
                            
                            "iftv_serial_no": btoa(iftv_serial_no),
							"bank_code": btoa(bank_code),
                            "bank_head": btoa(bank_head),
                            "amount": btoa(amount),
                            "edit_id":btoa(edit_id),
                            "delete_id":btoa(delete_id),
							"cmd": btoa(3)
						},
						success: function(data) {
							if (data != '') {
								var Result_Data = JSON.parse(data);
                                if(Result_Data['STATUS'] == 'SUCCESS'){
                                    let message = "Successfully Added";
                                    if (delete_id != '') {
                                        message = "Successfully Deleted";
                                    } else if (edit_id != '') {
                                        message = "Successfully Changed";
                                    }
                                    alert(message);
                                    $("#debit_table_result tbody").html(Result_Data['debit_data_table']);
                                    $('#debit_total_amount').val(Result_Data['debit_amount']);
                                    $('#span_debit_total_amount').html(Result_Data['debit_amount']);
                                    $('#debit_delete_id').val('');
                                    $('#debit_edit_id').val('');
                                    $('#debit_bank_code').val('');
                                    $('#debit_bank_head').val('');
                                    $('#debit_amount').val('');
                                    $("#btn_debit_add").val('Add Debit');
                                }else{
                                    alert(Result_Data['MESSAGE'] );
                                }
							}
						},
						dataType: 'html'
					});	
                });
                $(document).on('click', '#btn_credit_add', function() {
                    try {
                        if ($("#ift_serial_no").val().length == '') {
                            throw {
                                msg: "Missing Serial Number",
                                foc: "#ift_serial_no"
                            }
                        }else{
                            var ift_serial_no = $("#ift_serial_no").val();
                        }
                        
                        if ($("#credit_bank_code").val().length == '') {
                            throw {
                                msg: "Select Account Code",
                                foc: "#acc_code"
                            }
                        }else{
                            var credit_bank_code = $("#credit_bank_code").val();
                        }

                      
                        if ($("#credit_bank_head").val().length == '') {
                            throw {
                                msg: "Enter Bank Head",
                                foc: "#credit_bank_head"
                            }
                        }else{
                            var credit_bank_head = $("#credit_bank_head").val();
                        }
                        if ($("#credit_amount").val().length == '') {
                            throw {
                                msg: "Enter Credit Amount",
                                foc: "#credit_amount"
                            }
                        }else{
                            var credit_amount = $("#credit_amount").val();
                        }
                    } catch (e) {
                        $(e.foc).focus();
                        return false;
                    }
                    var edit_id = $("#credit_edit_id").val();
                    var delete_id = $("#credit_delete_id").val();
                    $.ajax({
						url: "Inter_Fund_Transfer_Voucher.php",
						type: "post",
						data: {
                            
                            "ift_serial_no": btoa(ift_serial_no),
							"credit_bank_code": btoa(credit_bank_code),
                            "credit_bank_head": btoa(credit_bank_head),
                            "amount": btoa(credit_amount),
                            "edit_id":btoa(edit_id),
                            "delete_id":btoa(delete_id),
							"cmd": btoa(6)
						},
						success: function(data) {
							if (data != '') {
								var Result_Data = JSON.parse(data);
                                if(Result_Data['STATUS'] == 'SUCCESS'){
                                    let message = "Successfully Added";
                                    if (delete_id != '') {
                                        message = "Successfully Deleted";
                                    } else if (edit_id != '') {
                                        message = "Successfully Changed";
                                    }
                                    alert(message);
                                    $("#credit_table_result tbody").html(Result_Data['credit_data_table']);
                                    $('#credit_total_amount').val(Result_Data['credit_amount']);
                                    $('#span_credit_total_amount').html(Result_Data['credit_amount']);
                                    $('#credit_delete_id').val('');
                                    $('#credit_edit_id').val('');
                                    $('#credit_bank_code').val('');
                                    $('#credit_bank_head').html('');
                                    $('#credit_amount').val('');
                                    $("#btn_credit_add").val('Add Credit');
                                }else{
                                    alert(Result_Data['MESSAGE'] );
                                }
							}
						},
						dataType: 'html'
					});	
                });	  
                $(document).on('click', '#btn_debit_edit', function() {
                    var id = $(this).parent().parent().find('.bank_id').val();
                    $.ajax({
						url: "Inter_Fund_Transfer_Voucher.php",
						type: "post",
						data: {
                            "account_type":btoa(2),
                            "id":btoa(id),
							"cmd": btoa(4)
						},
						success: function(data) {
							if (data != '') {
								var Result_Data = JSON.parse(data);
                                $("#btn_debit_add").val("Edit Debit");
								$('#debit_bank_code').val(Result_Data['bank_code']);
                                $('#debit_bank_head').val(Result_Data['bank_head']);
                                $('#debit_amount').val(Result_Data['debit_amount']);
                                $('#debit_delete_id').val('');
                                $('#debit_edit_id').val(Result_Data['iftv_breakupid']);
							}
						},
						dataType: 'html'
					});	
                });	
                $(document).on('click', '#btn_debit_delete', function() {
                    var id = $(this).parent().parent().find('.bank_id').val();
                    $.ajax({
						url: "Inter_Fund_Transfer_Voucher.php",
						type: "post",
						data: {
                            "account_type":btoa(2),
                            "id":btoa(id),
							"cmd": btoa(5)
						},
						success: function(data) {
							if (data != '') {
								var Result_Data = JSON.parse(data);
                                $("#btn_debit_add").val('Delete Debit');
								$('#debit_bank_code').val(Result_Data['bank_code']);
                                $('#debit_bank_head').val(Result_Data['bank_head']);
                                $('#debit_amount').val(Result_Data['debit_amount']);
                                $('#debit_delete_id').val(Result_Data['iftv_breakupid']);
                                $('#debit_edit_id').val('');
							}
						},
						dataType: 'html'
					});	
                });	
                $(document).on('click', '#btn_credit_edit', function() {
                    var id = $(this).parent().parent().find('.bank_id').val();
                    $.ajax({
						url: "Inter_Fund_Transfer_Voucher.php",
						type: "post",
						data: {
                            "account_type":btoa(1),
                            "id":btoa(id),
							"cmd": btoa(8)
						},
						success: function(data) {
							if (data != '') {
								var Result_Data = JSON.parse(data);
                                $("#btn_credit_add").val('Edit Debit');
                                $('#credit_bank_code').val(Result_Data['bank_code']);
                                $('#credit_bank_head').val(Result_Data['bank_head']);
                                $('#credit_amount').val(Result_Data['credit_amount']);
                                $('#credit_delete_id').val('');
                                $('#credit_edit_id').val(Result_Data['iftv_breakupid']);
							}
						},
						dataType: 'html'
					});	
                });	
                $(document).on('click', '#btn_credit_delete', function() {
                    var id = $(this).parent().parent().find('.bank_id').val();
                    $.ajax({
						url: "Inter_Fund_Transfer_Voucher.php",
						type: "post",
						data: {
                            "account_type":btoa(1),
                            "id":btoa(id),
							"cmd": btoa(9)
						},
						success: function(data) {
							if (data != '') {
								var Result_Data = JSON.parse(data);
                                $("#btn_credit_add").val("Delete Credit");
								$('#credit_bank_code').val(Result_Data['bank_code']);
                                $('#credit_bank_head').html(Result_Data['bank_head']);
                                $('#credit_amount').val(Result_Data['credit_amount']);
                                $('#credit_delete_id').val(Result_Data['iftv_breakupid']);
                                $('#credit_edit_id').val('');
							}
						},
						dataType: 'html'
					});	
                });	 
                document.getElementById('debit_bank_code').addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const accountName = selectedOption.getAttribute('data-name') || '';
                    document.getElementById('debit_bank_head').value = accountName;
                });
                document.getElementById('credit_bank_code').addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const accountName = selectedOption.getAttribute('data-name') || '';
                    document.getElementById('credit_bank_head').value = accountName;
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
                            window.location.href = "<?php echo $site_data->website_url;?>/project/forms/masters/Inter_Fund_Transfer_Voucher.php";
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
                            window.location.href = "<?php echo $site_data->website_url;?>/project/forms/masters/Inter_Fund_Transfer_Voucher.php";
                        </script>
                        <?php 
                    }
                }
            ?>
        <form action="Inter_Fund_Transfer_Voucher.php" method="post" class="" enctype="multipart/form-data" autocomplete="off">
            <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>" name="<?php echo htmlentities($this->page_token); ?>" value="<?php echo htmlentities($this->token($this->page_token)); ?>">
            <div class="card">
                <div class="card-body pl-5 pr-5">
                    <?php
                    if (isset($data_array["STATUS"])) {
                        echo $this->ShowMessage($data_array["STATUS"], $data_array["MESSAGE"]);
                    }
                    ?>
                    <table class="table table-bordered m-0 p-0 table-striped tndtp_form_table">
                        <thead class="bg-th-form-dsg">
                            <tr>
                                <th align="center" scope="col" colspan="2">Inter Fund Transfer Voucher</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Chalan Number</span></td>
                                <td  scope="col">
                                    <?php 
                                        $sel_qry="select max(iftv_id) as id from accounts_master.t_ift_voucher where dcode=:dcode and lbcode=:lbcode and del_flag is null and fin_year=:fin_year;";
                                        $sel_qry_res=$this->prepare($sel_qry, array(":dcode"=>$dcode, ":lbcode"=>$lbcode, ":fin_year"=>$fin_year),4);
                                        $chalan_no=$sel_qry_res['id']+1 . '/' .$fin_year;
                                        $del_qry="delete from accounts_master.t_ift_voucher_breakup
                                                where dcode=:dcode 
                                                    and lbcode=:lbcode 
                                                    and fin_year=:fin_year 
                                                    and iftv_chalan_no=:ift_chalan_no 
                                                    and iftv_id is null;";            
                                        $del_qry_res=$this->prepare($del_qry, array(
                                            ":dcode"=>$dcode, 
                                            ":lbcode"=>$lbcode, 
                                            ":fin_year"=>$fin_year, 
                                            ":ift_chalan_no"=>$chalan_no
                                        ),4);
                                        echo $chalan_no;
                                        ?>
                                        <input type="hidden" id="ift_chalan_no" name="ift_chalan_no" class="form-control w-50 form-control-sm" value="<?php echo $chalan_no; ?>"/>
                                        <input type="hidden" id="ift_serial_no" name="ift_serial_no" class="form-control w-50 form-control-sm" value="<?php echo $sel_qry_res['id']+1 ; ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483"> Chalan Date</span></td>
                                <td  scope="col">
                                    <input type="text" id="chalan_date" name="chalan_date" value="" class="form-control form-control-sm user_enter_date" />
                                </td>
                            </tr>
                            <tr>
                                <td   class="text-left font-weight-bold"><span DisplayLabelID="483">Transfer Mode</span></td>                            
                                <td  scope="col">
                                    <select id="transfer_mode" name="transfer_mode" class="form-control form-control-sm w-50">
                                       <option value="">Choose</option>
                                        <?php
                                        $sel_account_code_id = "SELECT paymenttypeid,paymenttype ,paymenttype_ta FROM master.m_paymenttype where paymenttypeid in (2,4) ORDER BY paymenttypeid ASC"; 
                                        $sel_account_codeid_res = $this->prepare($sel_account_code_id, array(), 2);
                                        foreach ($sel_account_codeid_res as $sel_account_codeid_key => $sel_account_codeid_row) {                                        
                                        ?>
                                            <option value="<?php echo htmlentities($sel_account_codeid_row['paymenttypeid']); ?>"><?php echo htmlentities($sel_account_codeid_row['paymenttype']); ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <script>
                                        document.getElementById('transfer_mode').value = '<?php 
                                                if (isset($data_array['transfer_mode']) && isset($data_array['transfer_mode'])) {
                                                    echo htmlentities($data_array['transfer_mode']);
                                                }
                                            ?>';
                                    </script>
                                </td>
                            </tr>
                            <tr class="bank_code_tr" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Bank Code</span></td>
                                <td  scope="col">
                                    <select id="bank_code" name="bank_code" class="form-control form-control-sm w-50">
											<option value="">Choose</option>
											<?php
											   $sel_qry = "SELECT account_head_id, old_account_head_code AS account_code, account_head_name_en, new_account_head_code FROM  accounts_master.m_account_head where old_account_head_code like '3%' /* and old_account_head_code::int > 3060 */ order by old_account_head_code asc";
												$sel_qry_res=$this->prepare($sel_qry,array(),2);
												foreach($sel_qry_res as $sel_qry_key=>$sel_qry_row)
												{
											    ?>
												<option value="<?php echo htmlentities($sel_qry_row['account_head_id']); ?>" data-name="<?php echo htmlentities($sel_qry_row['account_code'] . ' - ' . $sel_qry_row['account_head_name_en'] . '(' . $sel_qry_row['new_account_head_code'] . ')'); ?>">
                                                    <?php echo htmlentities($sel_qry_row['account_code'] . ' - ' . $sel_qry_row['account_head_name_en'] . '(' . $sel_qry_row['new_account_head_code'] . ')'); ?>
                                                </option>
											<?php }?>
									   </select>
                                   <?php ?> <script>
                                        document.getElementById('bank_code').value = '<?php if (isset($data_array['bank_code'])) {
                                                    echo htmlentities($data_array['bank_code']);
                                                } ?>';
                                    </script><?php ?>
                                </td>
                            </tr>
                            <tr class="pay_mode_cheque" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Cheque No</span></td>
                                <td  scope="col">
                                    <span id="cheque_no_text"></span>
                                    <input type="hidden" id="cheque_no" name="cheque_no" class="form-control form-control-sm  w-50"  value="" />
                            </td>								
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
                            <tr class="balance_tr" style="display: none;">
                                <td class="text-left font-weight-bold"><span DisplayLabelID="483">Bank Closing Balance</span></td>
                                <td scope="col">
                                    <input type="text" id="amount" name="amount" class="form-control form-control-sm w-50" /></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <table  class="table table-bordered m-0 p-0 table-striped tndtp_form_table">
                                <tr>
                                    <th align="center" scope="col" style="text-align:center;background-color:darkslateblue;color:white" colspan="2">Debit</th>
                                </tr>
                                <tr> 
                                    <td class="text-left font-weight-bold"><span>Bank Code</span></td>
                                    <td scope="col">
                                        <select id="debit_bank_code" name="debit_bank_code" class="form-control form-control-sm mb-2">
                                            <option value="">Choose</option>
                                            <?php 
                                            $res = $this->Select_Account_Head_Code(1,13);
                                            foreach ($res as $row): ?>
                                                <option value="<?= $row['account_head_id']; ?>"
                                                        data-code="<?= htmlentities($row['old_code']); ?>"
                                                        data-name="<?= htmlentities($row['account_head_name_en']); ?>">
                                                    <?= htmlentities($row['old_code'] . ' - ' . $row['account_head_name_en'] . '('.$row['new_code'] .')'); ?>
                                                </option>
                                            <?php endforeach; 
                                             ?>
                                        </select>
                                        <input type="hidden" id="debit_bank_head" name="debit_bank_head" class="form-control form-control-sm number_field" />
                                    </td>
                                </tr>
                                <tr>
                                    <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Amount</span></td>
                                    <td  scope="col">
                                        <input type="text" id="debit_amount" name="debit_amount" class="form-control form-control-sm number_field" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left font-weight-bold" colspan="2" align="center">
                                       <input type="button" id="btn_debit_add" name="btn_debit_add" value="Add Debit" class="btn btn-md text-white font-weight-bold btn-success" />
                                        <input type="hidden" name="debit_edit_id" value="" class="bank_id" id="debit_edit_id"/>
                                        <input type="hidden" name="debit_delete_id" value="" class="bank_id" id="debit_delete_id"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right font-weight-bold"><span DisplayLabelID="483">Debit Amount</span></td>
                                    <td  scope="col">
                                        <span id="span_debit_total_amount"></span>
                                        <input type="hidden" id="debit_total_amount" name="debit_total_amount" class="form-control form-control-sm number_field" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <table id="debit_table_result" class="table table-bordered m-0 p-0 table-striped tndtp_form_table">
                                            <thead>
                                                <tr>
                                                    <td> Account Code  </td>
                                                        <td> Account Head</td>
                                                    <td> Amount </td>
                                                    <td> Edit / Delete </td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>   
                        <div class=col-md-6>
                            <table  class="table table-bordered m-0 p-0 table-striped tndtp_form_table">
                                <tr>
                                    <th align="center" scope="col" style="text-align:center;background-color:darkslateblue;color:white" colspan="2">Credit</th>
                                </tr>
                                <tr>
                                    <td class="text-left font-weight-bold"><span>Bank Code</span></td>
                                    <td scope="col" >
                                        <select id="credit_bank_code" name="credit_bank_code" class="form-control form-control-sm mb-2">
                                            <option value="">Choose</option>
                                            <?php   $res = $this->Select_Account_Head_Code(2,13);
                                             foreach ($res as $row): ?>
                                                <option value="<?= $row['account_head_id']; ?>"
                                                        data-code="<?= htmlentities($row['old_code']); ?>"
                                                        data-name="<?= htmlentities($row['account_head_name_en']); ?>">
                                                    <?= htmlentities($row['old_code'] . ' - ' . $row['account_head_name_en'] . '('.$row['new_code'].')'); ?>
                                                </option>
                                            <?php endforeach;  ?>
                                        </select>
                                        <input type="hidden" id="credit_bank_head" name="credit_bank_head" class="form-control form-control-sm number_field" />
                                    </td>
                                </tr>                                          
                                <tr>
                                    <td  class="text-text-right font-weight-bold"><span DisplayLabelID="483">Amount</span></td>
                                    <td  scope="col">
                                        <input type="text" id="credit_amount" name="credit_amount" class="form-control form-control-sm number_field" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left font-weight-bold" colspan="2" align="center">
                                        <input type="button" id="btn_credit_add" name="btn_credit_add" value="Add Credit" class="btn btn-md text-white font-weight-bold btn-success" />
                                        <input type="hidden" id="credit_edit_id" name="credit_edit_id" class="form-control form-control-sm number_field" value=""/>
                                        <input type="hidden" id="credit_delete_id" name="credit_delete_id" class="form-control form-control-sm number_field" value=""/>
                                    </td>
                                </tr>
                                <tr>
                                    <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Credit Amount</span></td>
                                    <td  scope="col">
                                        <span id="span_credit_total_amount"></span>
                                        <input type="hidden" id="credit_total_amount" name="credit_total_amount" class="form-control form-control-sm number_field" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <table id="credit_table_result" class="table table-bordered m-0 p-0 table-striped tndtp_form_table">
                                            <thead>
                                                <tr>
                                                    <td> Account Code </td>
                                                    <td> Account Head </td>
                                                    <td> Amount </td>
                                                    <td> Edit / Delete </td>
                                                </tr>
                                            </thead>
                                            <tbody>                                                
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>   
                    </div><br>
                    <table class="table table-bordered m-0 p-0 table-striped tndtp_form_table">
                        <tbody>   
                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Narration</span></td>
                                <td  scope="col"><textarea id="remark" name="remark" rows="4" cols="50" class="form-control w-50 form-control-sm"></textarea></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
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
		if (isset($save_data['ift_serial_no']) && $save_data['ift_serial_no']!='') {
            $ift_serial_no = $save_data['ift_serial_no'];
            $ift_serial_no_Validation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $save_data['ift_serial_no'],
                    'Field_Name' => 'IFTV Chalan Number',
                    'Field_Label_Name' => 'IFTV Chalan Number',
                )
            );

            if ($ift_serial_no_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "ift_serial_no",
                    "MESSAGE" => $ift_serial_no_Validation['Message']
                ), $save_data));
                exit;
            }
        }else{
            $this->main_form(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "ift_serial_no",
                "MESSAGE" => 'Missing IFTV Chalan Number'
            ), $save_data));
            exit;
        }
        if (isset($save_data['ift_chalan_no']) && $save_data['ift_chalan_no']!='') {
            $ift_chalan_no = $save_data['ift_chalan_no'];
            $ift_chalan_no_Validation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number_slash_hyphen',
                    'Field_Value' => $save_data['ift_chalan_no'],
                    'Field_Name' => 'IFTV Chalan Number',
                    'Field_Label_Name' => 'IFTV Chalan Number',
                )
            );

            if ($ift_chalan_no_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "ift_chalan_no",
                    "MESSAGE" => $ift_chalan_no_Validation['Message']
                ), $save_data));
                exit;
            }
        }else{
            $this->main_form(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "ift_chalan_no",
                "MESSAGE" => 'Missing IFTV Chalan Number'
            ), $save_data));
            exit;
        }
        if (isset($save_data['chalan_date']) && $save_data['chalan_date']!='') {
            $chalan_date = $save_data['chalan_date'];
            list($date_completion, $month_completion, $year_completion) = explode('-', $chalan_date);
            $chalan_date = $year_completion . '-' . $month_completion . '-' . $date_completion;
            $dateValidation = $this->Field_Validation(
                array(
                    'Field_Type' => 'date',
                    'Field_Value' => $save_data['chalan_date'],
                    'Field_Name' => 'date',
                    'Field_Format' => 'dd-mm-yyyy',
                    'Field_Label_Name' => 'Invalid Chalan Date',
                )
            );
            if ($dateValidation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "chalan_date",
                    "MESSAGE" => $dateValidation['Message']
                ), $save_data));
                exit;
            }
        }
        else{
            $this->main_form(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "chalan_date",
                "MESSAGE" => 'Select Chalan Date'
            ), $save_data));
            exit;
        }

        if (isset($save_data['transfer_mode']) && $save_data['transfer_mode']!='') {
            $transfer_mode = $save_data['transfer_mode'];
            $transfer_mode_Validation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $save_data['transfer_mode'],
                    'Field_Name' => 'transfer_mode',
                    'Field_Label_Name' => 'Transfer Mode',
                )
            );

            if ($transfer_mode_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "transfer_mode",
                    "MESSAGE" => $transfer_mode_Validation['Message']
                ), $save_data));
                exit;
            }
        }else{
            $this->main_form(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "transfer_mode",
                "MESSAGE" => 'Select Transfer Mode'
            ), $save_data));
            exit;
        }
          
		if($transfer_mode == 2){
            if(isset($save_data['cheque_no']) && $save_data['cheque_no']!=''){
                $cheque_no = $save_data['cheque_no'];
                $cheque_no_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $save_data['cheque_no'],
                        'Field_Name' => 'cheque_no',
                        'Field_Label_Name' => 'Cheque Number',
                    )
                );

                if ($cheque_no_Validation['Status'] == "Error") {
                    $this->main_form(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "cheque_no",
                        "MESSAGE" => $cheque_no_Validation['Message']
                    ), $save_data));
                    exit;
                }
            }else{
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "cheque_no",
                    "MESSAGE" => "Enter Cheque Number"
                ), $save_data));
                exit;
            }
             $ecs_date=$ecs_no=NULL;
		}else if($transfer_mode == 4){
            if(isset($save_data['ecs_no']) && $save_data['ecs_no']!=''){
                $ecs_no = $save_data['ecs_no'];
                $ecs_no_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $save_data['ecs_no'],
                        'Field_Name' => 'ecs_no',
                        'Field_Label_Name' => 'ECS Number',
                    )
                );

                if ($ecs_no_Validation['Status'] == "Error") {
                    $this->main_form(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "ecs_no",
                        "MESSAGE" => $ecs_no_Validation['Message']
                    ), $save_data));
                    exit;
                }
            }else{
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "ecs_no",
                    "MESSAGE" => "Enter ECS Number"
                ), $save_data));
                exit;
            }

            if (isset($save_data['ecs_date']) && $save_data['ecs_date']!='') {
                $ecs_date = $save_data['ecs_date'];
                list($date_completion, $month_completion, $year_completion) = explode('-', $ecs_date);
                $ecs_date = $year_completion . '-' . $month_completion . '-' . $date_completion;
                $ecs_dateValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'date',
                        'Field_Value' => $save_data['ecs_date'],
                        'Field_Name' => 'date',
                        'Field_Format' => 'dd-mm-yyyy',
                        'Field_Label_Name' => 'Invalid ECS Date',
                    )
                );
                if ($ecs_dateValidation['Status'] == "Error") {
                    $this->main_form(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "ecs_date",
                        "MESSAGE" => $ecs_dateValidation['Message']
                    ), $save_data));
                    exit;
                }
            }
            else{
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "ecs_date",
                    "MESSAGE" => 'Select ECS Date'
                ), $save_data));
                exit;
            }
             $cheque_no = NULL;
		}
        if(isset($save_data['bank_code']) && $save_data['bank_code']!=''){
            $bank_code = $save_data['bank_code'];
            $bank_code_Validation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $save_data['bank_code'],
                    'Field_Name' => 'bank_code',
                    'Field_Label_Name' => 'Bank Code',
                )
            );

            if ($bank_code_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "bank_code",
                    "MESSAGE" => $bank_code_Validation['Message']
                ), $save_data));
                exit;
            }
        }else{
            $this->main_form(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "bank_code",
                "MESSAGE" => "Select Bank Code"
            ), $save_data));
            exit;
        }
        if(isset($save_data['amount']) && $save_data['amount']!=''){
            $amount = $save_data['amount'];
            $amount_Validation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $save_data['amount'],
                    'Field_Name' => 'amount',
                    'Field_Label_Name' => 'Amount',
                )
            );

            if ($amount_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "amount",
                    "MESSAGE" => $amount_Validation['Message']
                ), $save_data));
                exit;
            }
        }else{
            $this->main_form(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "amount",
                "MESSAGE" => "Select Amount"
            ), $save_data));
            exit;
        }
        if(isset($save_data['remark']) && $save_data['remark']!=''){
            $narration = $save_data['remark'];
        }else{
             $this->main_form(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "remark",
                "MESSAGE" => "Enter Narration"
            ), $save_data));
            exit;
        }
        if (isset($save_data['debit_total_amount']) && $save_data['debit_total_amount']!='') {
            $debit_amount = $save_data['debit_total_amount'];
            $debit_amountValidation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $debit_amount,
                    'Field_Name' => 'debit_amount',
                    'Field_Label_Name' => 'Invalid Debit Amount',
                )
            );
            if ($debit_amountValidation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "debit_amount",
                    "MESSAGE" => $debit_amountValidation['Message']
                ), $save_data));
                exit;
            }
        }else{
            $this->main_form(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "debit_amount",
                "MESSAGE" => 'Enter Debit Amount'
            ), $save_data));
            exit;
        }
        if (isset($save_data['credit_total_amount']) && $save_data['credit_total_amount']!='') {
            $credit_amount = $save_data['credit_total_amount'];
            $credit_amountValidation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $credit_amount,
                    'Field_Name' => 'credit_amount',
                    'Field_Label_Name' => 'Invalid Credit Amount',
                )
            );
            if ($credit_amountValidation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "credit_amount",
                    "MESSAGE" => $credit_amountValidation['Message']
                ), $save_data));
                exit;
            }
        }else{
            $this->main_form(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "credit_amount",
                "MESSAGE" => 'Enter Credit Amount'
            ), $save_data));
            exit;
        }
		$message = 'Data Deleted SccessFully';
        if (isset($save_data["del_id"])) {
			$del_id = base64_decode($save_data["del_id"]);
            $role_name_Validation = $this->Field_Validation(array(
                "Field_Type" => "number",
                "Field_Value" => $save_data["del_id"],
                "Field_Max_length" => 5,
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
                "Field_Max_length" => 5,
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
        if($debit_amount != $credit_amount){
            $this->main_form(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "MESSAGE" => "Debit Amount And Credit Amount Must Be Same"
            ), $save_data));
        }
                
        $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();
        $site_data = $this->siteData();
        $getCurrentUser = $this->getCurrentUser();
        $getIpAddress = $this->getIpAddress();
        $fin_year = $this->getFinYear();
		$save_query = "select accounts_master.sp_inter_bank_transfer(:statecode,:dcode,:lbcode,:chalan_no,:serial_no,:chalan_date,:transfer_mode,:cheque_no,:ecs_no,:ecs_date,:bank_code,:narration,:amount,:debit_amount,:credit_amount,:total_amount,:fin_year,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
        $result=$this->prepare($save_query,array(":statecode"=>33,":dcode"=>$dcode,":lbcode"=>$lbcode,":chalan_no"=>$ift_chalan_no,":serial_no"=>$ift_serial_no,":chalan_date"=>$chalan_date,":transfer_mode"=>$transfer_mode,":cheque_no"=>$cheque_no,":ecs_no"=>$ecs_no,":ecs_date"=>$ecs_date,":bank_code"=>$bank_code,":narration"=>$narration,":amount"=>$amount,":debit_amount"=>$debit_amount,":credit_amount"=>$credit_amount,":total_amount"=>$credit_amount,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>$edit_id,":delete_id"=>$del_id, ":fin_year"=>$fin_year),4);

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

$home = new Inter_Fund_Transfer_Voucher();
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
            ), $_GET));
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
            ), $_GET));
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
    $dcode=$home->getCurrentDistrictCode();
    $lbcode=$home->getCurrentLocalBodyCode();
    if($cmd==1) {
        $accounthead_id=base64_decode($_POST["bank_code"]);
        $query="select cheque_number, cheque_id from
(select bank_id, accounthead_id from accounts_master.t_bank_account where del_flag is null and accounthead_id=:accounthead_id)a left join
(select bank_cheque_id, cheque_number, bank_id, cheque_id from accounts_master.t_bank_cheque_leaves where dcode=:dcode and lbcode=:lbcode and del_flag is null and isused=:isused)b on a.bank_id=b.bank_id order by bank_cheque_id asc, cheque_number asc limit 1;";
        $res=$home->prepare($query, array(":accounthead_id"=> $accounthead_id, ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isused"=>'N'), 4);
        if(count($res)>0){
            echo json_encode($res);
        }
        else{
             echo json_encode('-');
        }
    }
    if ($cmd == 2) {
        $bank_code = base64_decode($_POST['bank_code']);
        $dcode = $AdjustBankReceiptVoucher->getCurrentDistrictCode();
        $lbcode = $AdjustBankReceiptVoucher->getCurrentLocalBodyCode();
		$sel_qry = "select b.bank_code, account_no, ifsc_code, fundname from (select bank_id, bank_code, bankbranch_id, account_no, fund_id, ifsc_code from accounts_master.t_bank_account where del_flag is null and isactive = :isactive and bankaccount_id=:bank_code and dcode=:dcode and lbcode=:lbcode) a left join 
        (select bank_id, bank_name_en, bank_code from accounts_master.m_bank) as b on a.bank_id=b.bank_id
        left join 
        accounts_master.m_fund as e on a.fund_id=e.fundid;";		
		$sel_qry_res=$AdjustBankReceiptVoucher->prepare($sel_qry,array(":bank_code"=>$bank_code, ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1),4);
        $Result['STATUS'] = 'SUCCESS';
        $Result['DATA'] = $sel_qry_res['bank_code'] .' '. $sel_qry_res['account_no'];
        echo json_encode($Result);
        exit;
    }
    if ($cmd == 3) {
        $iftv_serial_no = base64_decode($_POST['iftv_serial_no']);
        $bank_code = base64_decode($_POST['bank_code']); 
        $bank_head = base64_decode($_POST['bank_head']);     
        $debit_edit_id = isset($_POST['edit_id']) && $_POST['edit_id']!=''?base64_decode($_POST['edit_id']):0;
        $debit_delete_id = isset($_POST['delete_id']) && $_POST['delete_id']!=''?base64_decode($_POST['delete_id']):0;
        $amount = base64_decode($_POST['amount']);
        $dcode = $home->getCurrentDistrictCode();
        $lbcode = $home->getCurrentLocalBodyCode();
        $user_name = $home->getCurrentUser();
        $ip_address = $home->getIpAddress();
        $fin_year = $home->getFinYear();
        $home->beginTransaction();
        if($debit_delete_id == 0 && $debit_edit_id == 0){
            $save_query = "SELECT accounts_master.sp_ift_voucher_breakup(:acc_type,:acc_code,:debit_acc_head,:amount, :fin_year, :iftv_serial_no,:dcode,:lbcode,:statecode,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
            $res1 = $home->prepare($save_query, [":acc_type" => 2,":acc_code" => $bank_code,":debit_acc_head" => $bank_head,
":amount" => $amount, ":fin_year"=>$fin_year, ":iftv_serial_no" =>$iftv_serial_no,":dcode" => $dcode,":lbcode" => $lbcode,":statecode" => 33,":getCurrentUser" => $user_name,":getIpAddress" => $ip_address,":edit_id" => $debit_edit_id,":delete_id" => $debit_delete_id], 4);
        }else if($debit_delete_id == 0 && $debit_edit_id != 0){
            $save_query = "SELECT accounts_master.sp_ift_voucher_breakup(:acc_type,:acc_code,:debit_acc_head,:amount, :fin_year, :iftv_serial_no,:dcode,:lbcode,:statecode,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
            $res1 = $home->prepare($save_query, [":acc_type" => 2,":acc_code" => $bank_code,":debit_acc_head" => $bank_head,
":amount" => $amount, ":fin_year"=>$fin_year, ":iftv_serial_no" =>$iftv_serial_no,":dcode" => $dcode,":lbcode" => $lbcode,":statecode" => 33,":getCurrentUser" => $user_name,":getIpAddress" => $ip_address,":edit_id" => $debit_edit_id,":delete_id" => $debit_delete_id], 4);
        }  if($debit_delete_id != 0 && $debit_edit_id == 0){
            $save_query = "SELECT accounts_master.sp_ift_voucher_breakup(:acc_type,:acc_code,:debit_acc_head,:amount, :fin_year, :iftv_serial_no,:dcode,:lbcode,:statecode,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
            $res1 = $home->prepare($save_query, [":acc_type" => 2,":acc_code" => $bank_code,":debit_acc_head" => $bank_head,
":amount" => $amount, ":fin_year"=>$fin_year, ":iftv_serial_no" =>$iftv_serial_no,":dcode" => $dcode,":lbcode" => $lbcode,":statecode" => 33,":getCurrentUser" => $user_name,":getIpAddress" => $ip_address,":edit_id" => $debit_edit_id,":delete_id" => $debit_delete_id], 4);
        }  
        if (!isset($res1->errorInfo)) {
            $sel_qry = "select iftv_breakupid,debit_account_id, debit_account_head, debit_amount, b.account_head_name_en,b.account_code from (select iftv_breakupid, debit_account_id, debit_account_head, debit_amount from accounts_master.t_ift_voucher_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and iftv_serial_no=:iftv_serial_no  and account_type=:account_type and fin_year=:fin_year)a left join (SELECT account_head_id, old_account_head_code as account_code, account_head_name_en FROM accounts_master.m_account_head)b on a.debit_account_id=b.account_head_id;";		
            $sel_qry_res=$home->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":iftv_serial_no"=>$iftv_serial_no, ":account_type"=>2, ":fin_year"=>$fin_year),2);
            ob_start();
            foreach($sel_qry_res as $sel_qry_row){
                ?>
                <tr>
                    <td><?php echo htmlentities($sel_qry_row['account_code']); ?></td>
                    <td><?php echo htmlentities($sel_qry_row['debit_account_head']); ?></td>
                    <td><?php echo htmlentities($sel_qry_row['debit_amount']); ?>
                        <input type="hidden" name="debit_bank_id" value="<?php echo htmlentities($sel_qry_row['iftv_breakupid']);?>" class="bank_id" />
                    </td>
                    <td>
                        <input type="button" id="btn_debit_edit" name="btn_debit_edit" value="Edit" class="btn btn-md text-white font-weight-bold btn-success" style="font-size: small;">                    
                        <input type="button" id="btn_debit_delete" name="btn_debit_delete" value="Delete" class="btn btn-md text-white font-weight-bold btn-danger" style="font-size: small;">
                    </td>
                </tr> 
                <?php
            }
            $debit_amount = array_sum(array_column($sel_qry_res, 'debit_amount'));    
            $ob_contents = ob_get_contents();
            ob_clean();
            $home->commit();
            $Result_Data['STATUS']='SUCCESS';
            $Result_Data['debit_data_table']=$ob_contents;
            $Result_Data['debit_amount'] = $debit_amount;
        }else{
            $home->rollBack();
            $Result_Data['STATUS']='FAIL';
            $Result_Data['MESSAGE']='Data Save Failed';
        }
        echo json_encode($Result_Data);
        exit;
    }
    if ($cmd == 4) {
        $Result=array();
        $id = base64_decode($_POST['id']);
        $account_type = base64_decode($_POST['account_type']);
        $dcode = $home->getCurrentDistrictCode();
        $lbcode = $home->getCurrentLocalBodyCode();
		$sel_qry = "select iftv_breakupid, debit_account_id, debit_account_head, debit_amount, credit_amount from accounts_master.t_ift_voucher_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and account_type=:account_type and iftv_breakupid=:iftv_breakupid;";		
		$sel_qry_res=$home->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":account_type"=>$account_type, ":iftv_breakupid"=>$id),4);
        $Result['STATUS'] = 'SUCCESS';
        $Result['bank_code'] = $sel_qry_res['debit_account_id'];
        $Result['bank_head'] = $sel_qry_res['debit_account_head'];
        $Result['debit_amount'] = $sel_qry_res['debit_amount'];  
        $Result['credit_amount'] = $sel_qry_res['credit_amount'];         
        $Result['iftv_breakupid'] = $sel_qry_res['iftv_breakupid'];
        echo json_encode($Result);
        exit;
    }
    if ($cmd == 5) {
        $Result=array();
        $id = base64_decode($_POST['id']);
        $account_type = base64_decode($_POST['account_type']);
        $dcode = $home->getCurrentDistrictCode();
        $lbcode = $home->getCurrentLocalBodyCode();
		$sel_qry = "select iftv_breakupid, debit_account_id, debit_account_head, debit_amount, credit_amount from accounts_master.t_ift_voucher_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and account_type=:account_type and iftv_breakupid=:iftv_breakupid;";		
		$sel_qry_res=$home->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":account_type"=>$account_type, ":iftv_breakupid"=>$id),4);
        $Result['STATUS'] = 'SUCCESS';
        $Result['bank_code'] = $sel_qry_res['debit_account_id'];
        $Result['bank_head'] = $sel_qry_res['debit_account_head'];
        $Result['debit_amount'] = $sel_qry_res['debit_amount'];  
        $Result['credit_amount'] = $sel_qry_res['credit_amount'];       
        $Result['iftv_breakupid'] = $sel_qry_res['iftv_breakupid'];
        echo json_encode($Result);
        exit;
    }
     if ($cmd == 6) {
        $iftv_serial_no = base64_decode($_POST['ift_serial_no']);
        $credit_acc_code = base64_decode($_POST['credit_bank_code']); 
        $credit_acc_head = base64_decode($_POST['credit_bank_head']);   
        $credit_edit_id = isset($_POST['edit_id']) && $_POST['edit_id']!=''?base64_decode($_POST['edit_id']):0;
        $credit_delete_id = isset($_POST['delete_id']) && $_POST['delete_id']!=''?base64_decode($_POST['delete_id']):0;
        $amount = base64_decode($_POST['amount']);
        $dcode = $home->getCurrentDistrictCode();
        $lbcode = $home->getCurrentLocalBodyCode();
        $user_name = $home->getCurrentUser();
        $ip_address = $home->getIpAddress();
        $fin_year = $home->getFinYear();
        $home->beginTransaction();
        if($credit_delete_id == 0 && $credit_edit_id == 0){
            $save_query = "SELECT accounts_master.sp_ift_voucher_breakup(:acc_type,:acc_code,:credit_acc_head,:amount, :fin_year, :iftv_serial_no,:dcode ,:lbcode,:statecode,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
            $res1 = $home->prepare($save_query, [":acc_type" => 1,":acc_code" => $credit_acc_code,":credit_acc_head" => $credit_acc_head,
                ":amount" => $amount, ":fin_year"=>$fin_year, ":iftv_serial_no" =>$iftv_serial_no,":dcode" => $dcode,":lbcode" => $lbcode,":statecode" => 33,":getCurrentUser" => $user_name,":getIpAddress" => $ip_address,":edit_id" => $credit_edit_id,":delete_id" => $credit_delete_id], 4);
        }else if($credit_delete_id == 0 && $credit_edit_id != 0){
             $save_query = "SELECT accounts_master.sp_ift_voucher_breakup(:acc_type,:acc_code,:credit_acc_head,:amount, :fin_year, :iftv_serial_no,:dcode ,:lbcode,:statecode,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
            $res1 = $home->prepare($save_query, [":acc_type" => 1,":acc_code" => $credit_acc_code,":credit_acc_head" => $credit_acc_head,
                ":amount" => $amount, ":fin_year"=>$fin_year, ":iftv_serial_no" =>$iftv_serial_no,":dcode" => $dcode,":lbcode" => $lbcode,":statecode" => 33,":getCurrentUser" => $user_name,":getIpAddress" => $ip_address,":edit_id" => $credit_edit_id,":delete_id" => $credit_delete_id], 4);
        }  if($credit_delete_id != 0 && $credit_edit_id == 0){
            $save_query = "SELECT accounts_master.sp_ift_voucher_breakup(:acc_type,:acc_code,:credit_acc_head,:amount, :fin_year, :iftv_serial_no,:dcode ,:lbcode,:statecode,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
            $res1 = $home->prepare($save_query, [":acc_type" => 1,":acc_code" => $credit_acc_code,":credit_acc_head" => $credit_acc_head,
                ":amount" => $amount, ":fin_year"=>$fin_year, ":iftv_serial_no" =>$iftv_serial_no,":dcode" => $dcode,":lbcode" => $lbcode,":statecode" => 33,":getCurrentUser" => $user_name,":getIpAddress" => $ip_address,":edit_id" => $credit_edit_id,":delete_id" => $credit_delete_id], 4);
        }
		$sel_qry = "select iftv_breakupid,credit_account_id, credit_account_head, credit_amount, b.account_head_name_en,b.account_code from (select iftv_breakupid, credit_account_id, credit_account_head, credit_amount from accounts_master.t_ift_voucher_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and iftv_serial_no=:iftv_serial_no  and account_type=:account_type and fin_year=:fin_year)a left join (SELECT account_head_id, old_account_head_code as account_code, account_head_name_en FROM accounts_master.m_account_head)b on a.credit_account_id=b.account_head_id;";		
		$sel_qry_res=$home->prepare($sel_qry,array( ":dcode"=>$dcode,  ":lbcode"=>$lbcode, ":isactive"=>1, ":iftv_serial_no"=>$iftv_serial_no, ":account_type"=>1, ":fin_year"=>$fin_year),2);
        ob_start();
        foreach($sel_qry_res as $sel_qry_row){
            ?>
            
            <tr>
                <td><?php echo htmlentities($sel_qry_row['account_code']); ?></td>
                <td><?php echo htmlentities($sel_qry_row['credit_account_head']); ?></td>
                <td><?php echo htmlentities($sel_qry_row['credit_amount']); ?>
                    <input type="hidden" name="credit_bank_id" value="<?php echo htmlentities($sel_qry_row['iftv_breakupid']);?>" class="bank_id" />
                </td>
                <td>
                    <input type="button" id="btn_credit_edit" name="btn_credit_edit" value="Edit" class="btn btn-md text-white font-weight-bold btn-success" style="font-size: small;">
                    
                    <input type="button" id="btn_credit_delete" name="btn_credit_delete" value="Delete" class="btn btn-md text-white font-weight-bold btn-danger" style="font-size: small;">
                </td>
            </tr> 
            <?php
        }
        $credit_amount = array_sum(array_column($sel_qry_res, 'credit_amount'));
        $ob_contents = ob_get_contents();
		ob_clean();
        $home->commit();
        $Result_Data['STATUS']='SUCCESS';
        $Result_Data['credit_data_table']=$ob_contents;
        $Result_Data['credit_amount'] = $credit_amount;
        echo json_encode($Result_Data);
        exit;
    }
    if ($cmd == 8) {
        $Result=array();
        $id = base64_decode($_POST['id']);
        $account_type = base64_decode($_POST['account_type']);
        $dcode = $home->getCurrentDistrictCode();
        $lbcode = $home->getCurrentLocalBodyCode();
        $sel_qry = "select iftv_breakupid, credit_account_id, credit_account_head, credit_amount from accounts_master.t_ift_voucher_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and account_type=:account_type and iftv_breakupid=:iftv_breakupid;";      
        $sel_qry_res=$home->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":account_type"=>$account_type, ":iftv_breakupid"=>$id),4);
        $Result['STATUS'] = 'SUCCESS';
        $Result['bank_code'] = $sel_qry_res['credit_account_id'];
        $Result['bank_head'] = $sel_qry_res['credit_account_head'];
        $Result['credit_amount'] = $sel_qry_res['credit_amount'];         
        $Result['iftv_breakupid'] = $sel_qry_res['iftv_breakupid'];
        echo json_encode($Result);
        exit;
    }
    if ($cmd == 9) {
        $Result=array();
        $id = base64_decode($_POST['id']);
        $account_type = base64_decode($_POST['account_type']);
        $dcode = $home->getCurrentDistrictCode();
        $lbcode = $home->getCurrentLocalBodyCode();
        $sel_qry = "select  iftv_breakupid, credit_account_id, credit_account_head, credit_amount from accounts_master.t_ift_voucher_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and account_type=:account_type and iftv_breakupid=:iftv_breakupid;";     
        $sel_qry_res=$home->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":account_type"=>$account_type, ":iftv_breakupid"=>$id),4);
        $Result['STATUS'] = 'SUCCESS';
        $Result['bank_code'] = $sel_qry_res['credit_account_id'];
        $Result['bank_head'] = $sel_qry_res['credit_account_head'];
        $Result['credit_amount'] = $sel_qry_res['credit_amount'];       
        $Result['iftv_breakupid'] = $sel_qry_res['iftv_breakupid'];
        echo json_encode($Result);
        exit;
    }
}

?>