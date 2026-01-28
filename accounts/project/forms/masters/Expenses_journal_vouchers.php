<?php
require_once  '../../config/config.php';
require_once '../../library/account_head_balance.php';

class ExpenseJournalVoucher  extends ConfigClass
{

    public $page_token = "Expenses_Journal_Vouchers";
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
        $lbcode = $this->getCurrentLocalBodyCode();
        $lang_code_2d = $this->getCurrentUserLanguage2D();
        $fin_year = $this->getFinYear();
        $voucher_type="2";
        $sel_date_qry="select TO_CHAR(bpv_date, 'DD-MM-YYYY') as bpv_date from accounts_master.t_bank_payment_voucher where voucher_type=:voucher_type and dcode=:dcode and lbcode=:lbcode and del_flag is null and fin_year=:fin_year order by bpv_id desc limit 1;";
		$sel_date_qry_res=$this->prepare($sel_date_qry, array(":voucher_type"=>$voucher_type,":dcode"=>$dcode, ":lbcode"=>$lbcode, ":fin_year" =>$fin_year),4);
       
        if(isset($sel_date_qry_res['bpv_date']) && $sel_date_qry_res['bpv_date'] != ''){            
            $bpv_date= $sel_date_qry_res['bpv_date'];
        }else{
            $sel_date_qry="select * from public.sp_date_from_fin_year(:fin_year);";
            $sel_date_qry_res=$this->prepare($sel_date_qry, array(":fin_year" =>$fin_year),4);
            $bpv_date= json_decode($sel_date_qry_res['sp_date_from_fin_year'])->from_date;
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function() {	
				$(document).on('change', '#debit_bank_code', function() {
                    // if($("#date").val() ==''){
                    //     alert('Select BRV Date');
                    //     return false;
                    // }
                    
                    if($("#debit_bank_code").val() != ''){
                        var bank_code = $("#debit_bank_code").val();
                    }else{
                        alert("Select Bank Code");
                    }
					
                });	
				$(document).on('click', '#btn_debit_edit', function() {
                    var id = $(this).parent().parent().find('.bank_id').val();
                    $.ajax({
						url: "Expenses_journal_vouchers.php",
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
                                $('#debit_edit_id').val(Result_Data['ejv_breakupid']);
							}
						},
						dataType: 'html'
					});	
                });	
                $(document).on('click', '#btn_debit_delete', function() {
                    var id = $(this).parent().parent().find('.bank_id').val();
                    $.ajax({
						url: "Expenses_journal_vouchers.php",
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
                                $('#debit_delete_id').val(Result_Data['ejv_breakupid']);
                                $('#debit_edit_id').val('');
							}
						},
                        error:function(xhr,error,status)
                        {
                            console.log(`debit delete error:${error}`);
                            console.log(`debit delete error status:${status}`);
                        },
						dataType: 'html'
					});	
                });	
                $(document).on('click', '#btn_credit_edit', function() {
                    var id = $(this).parent().parent().find('.bank_id').val();
                    $.ajax({
						url: "Expenses_journal_vouchers.php",
						type: "post",
						data: {
                            "account_type":btoa(1),
                            "id":btoa(id),
							"cmd": btoa(8)
						},
						success: function(data) {
							if (data != '') {
								var Result_Data = JSON.parse(data);
                                $("#btn_credit_add").val("Edit Credit");
								$('#credit_bank_code').val(Result_Data['bank_code']);
                                $('#credit_bank_head').val(Result_Data['bank_head']);
                                $('#credit_amount').val(Result_Data['credit_amount']);
                                $('#credit_delete_id').val('');
                                $('#credit_edit_id').val(Result_Data['ejv_breakupid']);
							}
						},
						dataType: 'html'
					});	
                });	
                $(document).on('click', '#btn_credit_delete', function() {
                    var id = $(this).parent().parent().find('.bank_id').val();
                    $.ajax({
						url: "Expenses_journal_vouchers.php",
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
                                $('#credit_bank_head').val(Result_Data['bank_head']);
                                $('#credit_amount').val(Result_Data['credit_amount']);
                                $('#credit_delete_id').val(Result_Data['ejv_breakupid']);
                                $('#credit_edit_id').val('');
							}
						},
						dataType: 'html'
					});	
                });	
                $(document).on('click', '#btn_debit_add', function() {
                    try {
                        if ($("#ejv_serial_no").val().length == '') {
                            throw {
                                msg: "Missing Serial Number",
                                foc: "#ejv_serial_no"
                            }
                        }else{
                            var ejv_chalan_no = $("#ejv_chalan_no").val();
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
                        alert(e.msg);
                        $('#' + Current_Field_id).show();
                        $(e.foc).focus();
                        return false;
                    }
                    var edit_id = $("#debit_edit_id").val();
                    var delete_id = $("#debit_delete_id").val();
                    $.ajax({
						url: "Expenses_journal_vouchers.php",
						type: "post",
						data: {
                            
                            "ejv_chalan_no": btoa(ejv_chalan_no),
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
                                    } 
                                    else if (edit_id != '') {
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
                    }
				);	
                });
                $(document).on('click', '#btn_credit_add', function() {
                    try {
                        if ($("#ejv_serial_no").val().length == '') {
                            throw {
                                msg: "Missing Serial Number",
                                foc: "#ejv_serial_no"
                            }
                        }else{
                            var ejv_chalan_no = $("#ejv_chalan_no").val();
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

                        // alert(credit_bank_head);
                    } catch (e) {
                        alert(e.msg);
                        $('#' + Current_Field_id).show();
                        $(e.foc).focus();
                        return false;
                    }
                    var edit_id = $("#credit_edit_id").val();
                    var delete_id = $("#credit_delete_id").val();
                    // var credit_bank_head = $("#credit_bank_head").html();
                    $.ajax({
						url: "Expenses_journal_vouchers.php",
						type: "post",
						data: {
                            
                            "ejv_chalan_no": btoa(ejv_chalan_no),
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
                                    $('#credit_bank_head').val('');
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

                $(document).on('change', '#acc_code', function() {
                    if($("#date").val() ==''){
                        alert('Select Date');
                        return false;
                    }
                  
                    if($("#acc_code").val() != ''){
                        var acc_code = $("#acc_code").val();
                    }else{
                        alert("Select Account Code");
                    }
					if(acc_code != ''){
                        var acc_desc = $('#acc_code option:selected').data('desc'); 
                        $("#acc_head").html(acc_desc);
                    }
                });
                let date = new Date('<?php echo date("Y-m-d", strtotime($bpv_date)); ?>');
                date.setDate(date.getDate() + 1);
                date=date.toISOString().slice(0, 10);
                $('#date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd-mm-yyyy',
                    minDate: new Date(date),
                    maxDate: new Date()
                }).on('change', function (e) {  
            const chalan_date = $(this).val();
            $.ajax({
                url: "Expenses_journal_vouchers.php",
                    type: "post",
                    data: {
                        "ejv_date": btoa(chalan_date),
                        "type":btoa(2),
                        "cmd": btoa(10),
                    },
                    success: function (response) {
                    $('#loading-image').hide();

                    const res = JSON.parse(response);

            if (res.STATUS === "ERROR") {
                alert(res.MESSAGE);  
                $('#date').val(''); 
            } 
        }
            });
        });


        $('input, select, textarea').not("#date,#navigation_dropdown").on('focus click', function() {
          if ($('#date').val().trim() === '') {
            alert("Please Select Date");
            $('#date').focus();
          }
        });
                <?php if (!isset($post_data_array['del_id'])) { ?>
                    $(document).on('click', "#btn_save", function() {
                        var Current_Field_id = $(this).attr('id');
                        $('#' + Current_Field_id).hide();
                        try {
                            if ($("#ejv_serial_no").val().length == '') {
                                throw {
                                    msg: "Enter Serial Number",
                                    foc: "#ejv_serial_no"
                                }
                            }
                            if ($("#date").val().length == '') {
                                throw {
                                    msg: "Select Date",
                                    foc: "#date"
                                }
                            }
                           
                            
                            if ($("#credit_total_amount").val().length == '') {
                                throw {
                                    msg: "Enter Credit Amount",
                                    foc: "#credit_amount"
                                }
                            }
                            if ($("#debit_total_amount").val().length == '') {
                                throw {
                                    msg: "Enter Debit Amount",
                                    foc: "#debit_amount"
                                }
                            }
                            if(parseFloat($("#credit_total_amount").val()) != parseFloat($("#debit_total_amount").val())){
                                throw {
                                    msg: "Debit Amount And Credit Amount Must Be Same"
                                }
                            }
                                                  
                            if ($("#narration").val().length == '') {
                                throw {
                                    msg: "Enter Narration",
                                    foc: "#narration"
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
                width: 50%;
            }
			table.table-bordered > tbody > tr > td, table.table-bordered > tfoot > tr > td {
				width: 50%!important;
			}
        </style>

        <div class="container mt-3">            
            <form action="Expenses_journal_vouchers.php" method="post" enctype="multipart/form-data" autocomplete="off">
                <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>" name="<?php echo htmlentities($this->page_token); ?>" value="<?php echo htmlentities($this->token($this->page_token)); ?>">
                <div class="card">
                    <div class="card-body pl-5 pr-5">
                        <?php
                        if (isset($post_data_array["STATUS"])) {
                            echo $this->ShowMessage($post_data_array["STATUS"], $post_data_array["MESSAGE"]);
                        }
                        ?>
                        <table class="table table-bordered m-0 p-0 table-striped tndtp_form_table">
                            <thead class="bg-th-form-dsg">
                                <tr>
                                    <th align="center" scope="col" colspan="12">Expenses Journal Voucher</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Serial No</span></td>
                                    <td  scope="col">
                                    <?php 
                                        $sel_qry="select max(ejv_serial_no) as id from accounts_master.t_ej_voucher where dcode=:dcode and lbcode=:lbcode and del_flag is null and fin_year=:fin_year;";
                                        $sel_qry_res=$this->prepare($sel_qry, array(":dcode"=>$dcode, ":lbcode"=>$lbcode, ":fin_year"=>$fin_year),4);
                                        $chalan_no=$sel_qry_res['id']+1 . '/' .$fin_year;
                                        /*$del_qry="delete from accounts_master.t_ej_voucher_breakup 
                                                where dcode=:dcode 
                                                    and lbcode=:lbcode 
                                                    and fin_year=:fin_year 
                                                    and ejv_chalan_no=:ejv_serial_no 
                                                    and ejv_id is null;";
                                                    */
                                        $del_qry="update accounts_master.t_ej_voucher_breakup 
                                                    set del_flag='Y',
                                                        del_username=:username,
                                                        del_upd_date=NOW()::timestamp,
                                                        del_ipaddress=:ipaddress 
                                                    where dcode=:dcode 
                                                        and lbcode=:lbcode 
                                                        and fin_year=:fin_year 
                                                        and ejv_chalan_no=:ejv_chalan_no 
                                                        and ejv_id is null;";
            
                                        $del_qry_res=$this->prepare($del_qry, array(
                                            ":dcode"=>$dcode, 
                                            ":lbcode"=>$lbcode, 
                                            ":fin_year"=>$fin_year, 
                                            ":gjv_chalan_no"=>$chalan_no,
                                            ":username"=>$this->getCurrentUser(),
                                            ":ipaddress"=>$this->getIpAddress()
                                        ),4);
                                        echo $chalan_no;
                                        ?>
                                        <input type="hidden" id="ejv_chalan_no" name="ejv_chalan_no" class="form-control w-50 form-control-sm" value="<?php echo $chalan_no; ?>"/>
                                        <input type="hidden" id="ejv_serial_no" name="ejv_serial_no" class="form-control w-50 form-control-sm" value="<?php echo $sel_qry_res['id']+1 ; ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td  class="text-left font-weight-bold"><span DisplayLabelID="484">Ejv Date</span></td>
                                    <td  scope="col">
                                        <input type="text" id="date" name="date" value="" class="form-control form-control-sm user_enter_date w-50" />
                                    </td>
                                </tr></table><br>
   <div class="row">
    <div class="col-md-6">
    <table  class="table table-bordered m-0 p-0 table-striped tndtp_form_table">
    <tr>
                                    <th align="center" scope="col" style="text-align:center;background-color:darkslateblue;color:white" colspan="12">Debit</th>
                                </tr>
                                            <tr>
                                                <td class="text-left font-weight-bold"><span>Account Code & Head</span></td>
                                                <td scope="col">

<select id="debit_bank_code" name="debit_bank_code" class="form-control form-control-sm mb-2">
    <option value="">Choose</option>
    <?php $res = $this->Select_Account_Head_Code(1,2);
    // $query = "
    // SELECT 
    //     li.account_headid as account_head_id,
    //     acc_head.account_code,
    //     acc_head.account_head_name_en, old_account_head_code
    // FROM 
    // accounts_master.m_accounthead_link as li
    // LEFT JOIN (
    //     SELECT 
    //         account_head_id,
    //         old_account_head_code AS account_code,
    //         account_head_name_en, old_account_head_code
    //     FROM 
    //         accounts_master.m_account_head
    //     WHERE 
    //         /*account_type_head_id = :account_type_head_id
    //         AND*/ isactive = :isactive
    // ) AS acc_head 
    //     ON acc_head.account_head_id = li.account_headid
    // WHERE 
    //     /*li.lbcode = :lbcode
    //     AND li.dcode = :dcode
    //     AND */li.voucher_id=:voucher_id
    //     AND li.account_type_id=:account_type_id
    //     AND li.isactive=1
    //     AND li.del_flag is null
    //     AND acc_head.account_head_id IS NOT NULL";
    //  $res = $this->prepare($query, [
    //     ":isactive"=>1,":voucher_id"=>2,":account_type_id"=>2
    // ],2);
    foreach ($res as $row): ?>
        <option value="<?= $row['account_head_id']; ?>"
                data-code="<?= htmlentities($row['old_code']); ?>"
                data-name="<?= htmlentities($row['account_head_name_en']); ?>">
            <?= htmlentities($row['old_code'] . ' - ' . $row['account_head_name_en'] . '('.$row['new_code'] .')'); ?>
        </option>
    <?php endforeach; ?>
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
                                    <th align="center" scope="col" style="text-align:center;background-color:darkslateblue;color:white" colspan="12">Credit</th>
                                </tr>
                                            <tr>
                                                <td class="text-left font-weight-bold"><span>Account Code & Head</span></td>
                                                <td scope="col" >
                                                


<select id="credit_bank_code" name="credit_bank_code" class="form-control form-control-sm mb-2">
    <option value="">Choose</option>
    <?php $res = $this->Select_Account_Head_Code(2,2);
    // $query = "
    //                 SELECT 
    //                     li.account_headid as account_head_id,
    //                     acc_head.account_code,
    //                     acc_head.account_head_name_en
    //                 FROM 
    //                 accounts_master.m_accounthead_link as li
    //                 LEFT JOIN (
    //                     SELECT 
    //                         account_head_id,
    //                         old_account_head_code AS account_code,
    //                         account_head_name_en, old_account_head_code
    //                     FROM 
    //                         accounts_master.m_account_head
    //                     WHERE 
    //                         /*account_type_head_id = :account_type_head_id
    //                         AND */ isactive = :isactive and del_flag is null
    //                 ) AS acc_head 
    //                     ON acc_head.account_head_id = li.account_headid
    //                 WHERE 
    //                 /*
    //                     li.lbcode = :lbcode
    //                     AND li.dcode = :dcode 
    //                     AND */ li.voucher_id=:voucher_id
    //                     AND li.account_type_id=:account_type_id
    //                     AND li.isactive=1
    //                     AND li.del_flag is null
    //                     AND acc_head.account_head_id IS NOT NULL
    //                     AND li.del_flag IS NULL
    //             ";
                        /*
                        $res = $this->prepare($query, [
                                ":account_type_head_id"=>2,":isactive"=>1,":lbcode"=>$lbcode,":dcode"=>$dcode
                            ], 2);
                            
                        $res = $this->prepare($query, [
                                ":isactive"=>1,":voucher_id"=>2,":account_type_id"=>1
                            ], 2);*/
    foreach ($res as $row): ?>
        <option value="<?= $row['account_head_id']; ?>"
                data-code="<?= htmlentities($row['old_code']); ?>"
                data-name="<?= htmlentities($row['account_head_name_en']); ?>">
            <?= htmlentities($row['old_code'] . ' - ' . $row['account_head_name_en'] . '('.$row['new_code'].')'); ?>
        </option>
    <?php endforeach; ?>
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
                            <table  class="table table-bordered m-0 p-0 table-striped tndtp_form_table">
                                <tr>
                                    <td align="center" >
                                        <span DisplayLabelID="484">Narration</span>
                                    </td>
                                    <td align="left" colspan="2">
                                        <textarea id="narration" name="narration" rows="4" cols="50" class="form-control w-50 form-control-sm"></textarea>
                                        <span>Max 250 Characters</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" align="center">
                                        <input type="submit" id="btn_save" name="btn_save" value="Save" class="btn btn-md text-white font-weight-bold  btn-success" />
                                        <input type="button" id="btn_reset" name="btn_reset" value="Cancel" class="btn btn-md text-white font-weight-bold btn-secondary" />
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
        $statecode = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();

        if (isset($save_data['ejv_chalan_no']) && $save_data['ejv_chalan_no']!='') {
            $ejv_chalan_no = $save_data['ejv_chalan_no'];
            $ejv_serial_no_Validation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number_slash_hyphen',
                    'Field_Value' => $save_data['ejv_chalan_no'],
                    'Field_Name' => 'BRV Chalan Number',
                    'Field_Label_Name' => 'BRV Chalan Number',
                )
            );

            if ($ejv_serial_no_Validation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "ejv_serial_no",
                    "MESSAGE" => $ejv_serial_no_Validation['Message']
                ), $save_data));
                exit;
            }
        }else{
            $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "ejv_serial_no",
                "MESSAGE" => 'Missing BRV Chalan Number'
            ), $save_data));
            exit;
        }
        if (isset($save_data['ejv_serial_no']) && $save_data['ejv_serial_no']!='') {
            $ejv_serial_no = $save_data['ejv_serial_no'];
            $ejv_serial_no_Validation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $save_data['ejv_serial_no'],
                    'Field_Name' => 'BRV Chalan Number',
                    'Field_Label_Name' => 'BRV Chalan Number',
                )
            );

            if ($ejv_serial_no_Validation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "ejv_serial_no",
                    "MESSAGE" => $ejv_serial_no_Validation['Message']
                ), $save_data));
                exit;
            }
        }else{
            $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "ejv_serial_no",
                "MESSAGE" => 'Missing BRV Chalan Number'
            ), $save_data));
            exit;
        }
        if (isset($save_data['date']) && $save_data['date']!='') {
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
        else{
            $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "date",
                "MESSAGE" => 'Select Date'
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
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "debit_amount",
                    "MESSAGE" => $debit_amountValidation['Message']
                ), $save_data));
                exit;
            }
        }else{
            $this->main_content(array_merge(array(
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
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "credit_amount",
                    "MESSAGE" => $credit_amountValidation['Message']
                ), $save_data));
                exit;
            }
        }else{
            $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "credit_amount",
                "MESSAGE" => 'Enter Credit Amount'
            ), $save_data));
            exit;
        }
        if (isset($save_data['narration']) && $save_data['narration']!='') {
            $narration = $save_data['narration'];
            // $narrationValidation = $this->Field_Validation(
            //     array(
            //         'Field_Type' => 'text_space',
            //         'Field_Value' => $narration,
            //         'Field_Name' => 'Narration',
            //         'Field_Max_length' => '300',
            //         'Field_Label_Name' => 'Narration',
            //     )
            // );
            // if ($narrationValidation['Status'] == "Error") {
            //     $this->main_content(array_merge(array(
            //         "STATUS" => "ERROR",
            //         "STATUS_TYPE" => "FIELD",
            //         "FIELD_NAME" => "Narration",
            //         "MESSAGE" => $narrationValidation['Message']
            //     ), $save_data));
            //     exit;
            // }
        }else{
            $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "Narration",
                "MESSAGE" => 'Enter Narration'
            ), $save_data));
            exit;
        }
        
        if($credit_amount != $debit_amount){
             $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "MESSAGE" => 'Debit Amount And Credit Amount Must Be Same'
            ), $save_data));
            exit;
        }
        $fin_year=$this->getFinYear();        
        $Result_Message = "Data Saved SuccessFully";
        $this->beginTransaction();
        $sp_bank_receicpt_voucher = "accounts_master.sp_ej_voucher";
        $user_name = $this->getCurrentUser();
        $ip_address = $this->getIpAddress();       
        $save_query = "select * from " . $sp_bank_receicpt_voucher . "(:dcode, :lbcode, :ejv_chalan_no, :ejv_date,:debit_amount, :credit_amount, :total_amount, :narration, :fin_year, :user_name, :ip_address, :edit_id, :del_id)";
        $res1 = $this->prepare($save_query, array(":dcode" => $dcode, ":lbcode" => $lbcode, ":ejv_chalan_no" => $ejv_serial_no, ":ejv_date" => $date, ":fin_year" => $fin_year, ":debit_amount" => $debit_amount, ":credit_amount" => $credit_amount, ":total_amount" => $credit_amount, ":narration" => $narration, ":user_name" => $user_name, ":ip_address" => $ip_address, ":edit_id" => 0, ":del_id" => 0), 4);
        if (!isset($res1->errorInfo)) {
            $inserted_id = $res1['sp_ej_voucher'];
            $latest_date_query = "update accounts_master.t_ej_voucher_breakup set ejv_id=:inserted_id WHERE del_flag IS NULL and ejv_chalan_no=:ejv_chalan_no and dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year";
            $sel_dname_res=$this->prepare($latest_date_query,array(":ejv_chalan_no"=> $ejv_chalan_no,":dcode"=> $dcode,":lbcode"=> $lbcode,":inserted_id"=> $inserted_id, ":fin_year"=>$fin_year),4); 
            $account_head_balance_obj=new Account_head_balance();
            
            $this->beginTransaction();
            try{
                $account_head_balance_obj->update_voucher_head_amount(2,$ejv_chalan_no,False);
                $this->commit();
            }
            catch(Exception $e)
            {
                $this->rollBack();
                 $this->main_content(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Data Save Failed"
            ));
            exit;
            } 
            $site_data = $this->siteData();
            $url = $site_data->website_url . '/project/forms/masters/Expense_Journal_Voucher_Reciept.php?ejvno=' . base64_encode($inserted_id);
            header("Location: $url");
            exit;
        }
        else {
            $this->rollBack();
            $this->main_content(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Data Save Failed Due To Duplicate Entry"
            ));
            exit;
        }
    }
} 
$ExpenseJournalVoucher = new ExpenseJournalVoucher();
if (!isset($_POST['cmd'])) {
    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        $ExpenseJournalVoucher->data_save(array_merge($_POST, $_GET));
    } else {
        $ExpenseJournalVoucher->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
    }
} else if (isset($_POST['cmd'])) {
    $cmd = base64_decode($_POST['cmd']);
    if ($cmd == 2) {
        $bank_code = base64_decode($_POST['bank_code']);
        $dcode = $ExpenseJournalVoucher->getCurrentDistrictCode();
        $lbcode = $ExpenseJournalVoucher->getCurrentLocalBodyCode();
		$sel_qry = "select b.bank_code, account_no, ifsc_code, fundname from (select bank_id, bank_code, bankbranch_id, account_no, fund_id, ifsc_code from accounts_master.t_bank_account where del_flag is null and isactive = :isactive and bankaccount_id=:bank_code and dcode=:dcode and lbcode=:lbcode) a left join 
        (select bank_id, bank_name_en, bank_code from accounts_master.m_bank) as b on a.bank_id=b.bank_id
        left join 
        accounts_master.m_fund as e on a.fund_id=e.fundid;";		
		$sel_qry_res=$ExpenseJournalVoucher->prepare($sel_qry,array(":bank_code"=>$bank_code, ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1),4);
        $Result['STATUS'] = 'SUCCESS';
        $Result['DATA'] = $sel_qry_res['bank_code'] .' '. $sel_qry_res['account_no'];
        echo json_encode($Result);
        exit;}
    if ($cmd == 3) {
        // $collection_amount = base64_decode($_POST['collection_amount']);
        $ejv_chalan_no = base64_decode($_POST['ejv_chalan_no']);
        $bank_code = base64_decode($_POST['bank_code']);
        $bank_head = base64_decode($_POST['bank_head']);        
        $debit_edit_id = isset($_POST['edit_id']) && $_POST['edit_id']!=''?base64_decode($_POST['edit_id']):0;
        $debit_delete_id = isset($_POST['delete_id']) && $_POST['delete_id']!=''?base64_decode($_POST['delete_id']):0;
        $amount = base64_decode($_POST['amount']);
        $dcode = $ExpenseJournalVoucher->getCurrentDistrictCode();
        $lbcode = $ExpenseJournalVoucher->getCurrentLocalBodyCode();
        $user_name = $ExpenseJournalVoucher->getCurrentUser();
        $ip_address = $ExpenseJournalVoucher->getIpAddress();
        $fin_year = $ExpenseJournalVoucher->getFinYear();
        $Result_Data=array();
        $ExpenseJournalVoucher->beginTransaction();
        if($debit_delete_id == 0 && $debit_edit_id == 0){
             $save_query = "SELECT accounts_master.sp_ej_voucher_breakup(:acc_type,:acc_code,:debit_acc_head,:amount, :fin_year, :ejv_chalan_no,:dcode,:lbcode,:statecode,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
        $res1 = $ExpenseJournalVoucher->prepare($save_query, [":acc_type" => 2,":acc_code" => $bank_code,":debit_acc_head" => $bank_head,
":amount" => $amount, ":fin_year"=>$fin_year, ":ejv_chalan_no" =>$ejv_chalan_no,":dcode" => $dcode,":lbcode" => $lbcode,":statecode" => 33,":getCurrentUser" => $user_name,":getIpAddress" => $ip_address,":edit_id" => $debit_edit_id,":delete_id" => $debit_delete_id], 4);
        }else if($debit_delete_id == 0 && $debit_edit_id != 0){
            $save_query = "SELECT accounts_master.sp_ej_voucher_breakup(:acc_type,:acc_code,:debit_acc_head,:amount, :fin_year, :ejv_chalan_no,:dcode,:lbcode,:statecode,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
        $res1 = $ExpenseJournalVoucher->prepare($save_query, [":acc_type" => 2,":acc_code" => $bank_code,":debit_acc_head" => $bank_head,
":amount" => $amount, ":fin_year"=>$fin_year, ":ejv_chalan_no" =>$ejv_chalan_no,":dcode" => $dcode,":lbcode" => $lbcode,":statecode" => 33,":getCurrentUser" => $user_name,":getIpAddress" => $ip_address,":edit_id" => $debit_edit_id,":delete_id" => $debit_delete_id], 4);
        }  if($debit_delete_id != 0 && $debit_edit_id == 0){
            $save_query = "SELECT accounts_master.sp_ej_voucher_breakup(:acc_type,:acc_code,:debit_acc_head,:amount, :fin_year, :ejv_chalan_no,:dcode,:lbcode,:statecode,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
        $res1 = $ExpenseJournalVoucher->prepare($save_query, [":acc_type" => 2,":acc_code" => $bank_code,":debit_acc_head" => $bank_head,
":amount" => $amount, ":fin_year"=>$fin_year, ":ejv_chalan_no" =>$ejv_chalan_no,":dcode" => $dcode,":lbcode" => $lbcode,":statecode" => 33,":getCurrentUser" => $user_name,":getIpAddress" => $ip_address,":edit_id" => $debit_edit_id,":delete_id" => $debit_delete_id], 4);
        }
        if (!isset($res1->errorInfo)) {
            $ExpenseJournalVoucher->commit();
            $sel_qry = "select ejv_breakupid,debit_account_id, debit_account_head, debit_amount, b.account_head_name_en, account_code from (select ejv_breakupid, debit_account_id, debit_account_head, debit_amount from accounts_master.t_ej_voucher_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and ejv_chalan_no=:ejv_chalan_no  and account_type=:account_type and fin_year=:fin_year)a left join (SELECT account_head_id, old_account_head_code as account_code, account_head_name_en FROM accounts_master.m_account_head)b on a.debit_account_id=b.account_head_id;";		
		    $sel_qry_res=$ExpenseJournalVoucher->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":ejv_chalan_no"=>$ejv_chalan_no, ":account_type"=>2, ":fin_year"=>$fin_year),2);
            ob_start();
            foreach($sel_qry_res as $sel_qry_row){
                ?>
                <tr>
                    <td><?php echo htmlentities($sel_qry_row['account_code']); ?></td>
                    <td><?php echo htmlentities($sel_qry_row['debit_account_head']); ?></td>
                    <td><?php echo htmlentities($sel_qry_row['debit_amount']); ?>
                        <input type="hidden" name="debit_bank_id" value="<?php echo htmlentities($sel_qry_row['ejv_breakupid']);?>" class="bank_id" />
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
            $Result_Data['STATUS']='SUCCESS';
            $Result_Data['debit_data_table']=$ob_contents;
            $Result_Data['debit_amount'] = $debit_amount;
        }else{
            $ExpenseJournalVoucher->rollBack();
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
        $dcode = $ExpenseJournalVoucher->getCurrentDistrictCode();
        $lbcode = $ExpenseJournalVoucher->getCurrentLocalBodyCode();
		$sel_qry = "select ejv_breakupid, debit_account_id, debit_account_head, debit_amount, credit_amount from accounts_master.t_ej_voucher_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and account_type=:account_type and ejv_breakupid=:ejv_breakupid;";		
		$sel_qry_res=$ExpenseJournalVoucher->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":account_type"=>$account_type, ":ejv_breakupid"=>$id),4);
        $Result['STATUS'] = 'SUCCESS';
        $Result['bank_code'] = $sel_qry_res['debit_account_id'];
        $Result['bank_head'] = $sel_qry_res['debit_account_head'];
        $Result['debit_amount'] = $sel_qry_res['debit_amount'];  
        $Result['credit_amount'] = $sel_qry_res['credit_amount'];         
        $Result['ejv_breakupid'] = $sel_qry_res['ejv_breakupid'];
        echo json_encode($Result);
        exit;
    }
    if ($cmd == 8) {
        $Result=array();
        $id = base64_decode($_POST['id']);
        $account_type = base64_decode($_POST['account_type']);
        $dcode = $ExpenseJournalVoucher->getCurrentDistrictCode();
        $lbcode = $ExpenseJournalVoucher->getCurrentLocalBodyCode();
        $sel_qry = "select ejv_breakupid, credit_account_id, credit_account_head, credit_amount from accounts_master.t_ej_voucher_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and account_type=:account_type and ejv_breakupid=:ejv_breakupid;";      
        $sel_qry_res=$ExpenseJournalVoucher->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":account_type"=>$account_type, ":ejv_breakupid"=>$id),4);
        $Result['STATUS'] = 'SUCCESS';
        $Result['bank_code'] = $sel_qry_res['credit_account_id'];
        $Result['bank_head'] = $sel_qry_res['credit_account_head'];
        $Result['credit_amount'] = $sel_qry_res['credit_amount'];         
        $Result['ejv_breakupid'] = $sel_qry_res['ejv_breakupid'];
        echo json_encode($Result);
        exit;
    }
    if ($cmd == 5) {
        $Result=array();
        $id = base64_decode($_POST['id']);
        $account_type = base64_decode($_POST['account_type']);
        $dcode = $ExpenseJournalVoucher->getCurrentDistrictCode();
        $lbcode = $ExpenseJournalVoucher->getCurrentLocalBodyCode();
		$sel_qry = "select  ejv_breakupid, debit_account_id, debit_account_head, debit_amount, credit_amount from accounts_master.t_ej_voucher_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and account_type=:account_type and ejv_breakupid=:ejv_breakupid;";		
		$sel_qry_res=$ExpenseJournalVoucher->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":account_type"=>$account_type, ":ejv_breakupid"=>$id),4);
        $Result['STATUS'] = 'SUCCESS';
        $Result['bank_code'] = $sel_qry_res['debit_account_id'];
        $Result['bank_head'] = $sel_qry_res['debit_account_head'];
        $Result['debit_amount'] = $sel_qry_res['debit_amount'];  
        $Result['credit_amount'] = $sel_qry_res['credit_amount'];       
        $Result['ejv_breakupid'] = $sel_qry_res['ejv_breakupid'];
        echo json_encode($Result);
        exit;
    }
    if ($cmd == 9) {
        $Result=array();
        $id = base64_decode($_POST['id']);
        $account_type = base64_decode($_POST['account_type']);
        $dcode = $ExpenseJournalVoucher->getCurrentDistrictCode();
        $lbcode = $ExpenseJournalVoucher->getCurrentLocalBodyCode();
        $sel_qry = "select  ejv_breakupid, credit_account_id, credit_account_head, credit_amount from accounts_master.t_ej_voucher_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and account_type=:account_type and ejv_breakupid=:ejv_breakupid;";     
        $sel_qry_res=$ExpenseJournalVoucher->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":account_type"=>$account_type, ":ejv_breakupid"=>$id),4);
        $Result['STATUS'] = 'SUCCESS';
        $Result['bank_code'] = $sel_qry_res['credit_account_id'];
        $Result['bank_head'] = $sel_qry_res['credit_account_head'];
        $Result['credit_amount'] = $sel_qry_res['credit_amount'];       
        $Result['ejv_breakupid'] = $sel_qry_res['ejv_breakupid'];
        echo json_encode($Result);
        exit;
    }
    if ($cmd == 6) {
        $ejv_chalan_no = base64_decode($_POST['ejv_chalan_no']);
        $credit_acc_code = base64_decode($_POST['credit_bank_code']); 
        $credit_acc_head = base64_decode($_POST['credit_bank_head']);   
        $credit_edit_id = isset($_POST['edit_id']) && $_POST['edit_id']!=''?base64_decode($_POST['edit_id']):0;
        $credit_delete_id = isset($_POST['delete_id']) && $_POST['delete_id']!=''?base64_decode($_POST['delete_id']):0;
        $amount = base64_decode($_POST['amount']);
        $dcode = $ExpenseJournalVoucher->getCurrentDistrictCode();
        $lbcode = $ExpenseJournalVoucher->getCurrentLocalBodyCode();
        $user_name = $ExpenseJournalVoucher->getCurrentUser();
        $ip_address = $ExpenseJournalVoucher->getIpAddress();
        $fin_year = $ExpenseJournalVoucher->getFinYear();
        $ExpenseJournalVoucher->beginTransaction();
        if($credit_delete_id == 0 && $credit_edit_id == 0){
            $save_query = "SELECT accounts_master.sp_ej_voucher_breakup(:acc_type,:acc_code,:credit_acc_head,:amount, :fin_year, :ejv_chalan_no,:dcode ,:lbcode,:statecode,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
            $res1 = $ExpenseJournalVoucher->prepare($save_query, [":acc_type" => 1,":acc_code" => $credit_acc_code,":credit_acc_head" => $credit_acc_head,
                ":amount" => $amount, ":fin_year"=>$fin_year, ":ejv_chalan_no" =>$ejv_chalan_no,":dcode" => $dcode,":lbcode" => $lbcode,":statecode" => 33,":getCurrentUser" => $user_name,":getIpAddress" => $ip_address,":edit_id" => $credit_edit_id,":delete_id" => $credit_delete_id], 4);
        }else if($credit_delete_id == 0 && $credit_edit_id != 0){
             $save_query = "SELECT accounts_master.sp_ej_voucher_breakup(:acc_type,:acc_code,:credit_acc_head,:amount, :fin_year, :ejv_chalan_no,:dcode ,:lbcode,:statecode,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
            $res1 = $ExpenseJournalVoucher->prepare($save_query, [":acc_type" => 1,":acc_code" => $credit_acc_code,":credit_acc_head" => $credit_acc_head,
                ":amount" => $amount, ":fin_year"=>$fin_year, ":ejv_chalan_no" =>$ejv_chalan_no,":dcode" => $dcode,":lbcode" => $lbcode,":statecode" => 33,":getCurrentUser" => $user_name,":getIpAddress" => $ip_address,":edit_id" => $credit_edit_id,":delete_id" => $credit_delete_id], 4);
        }  if($credit_delete_id != 0 && $credit_edit_id == 0){
            $save_query = "SELECT accounts_master.sp_ej_voucher_breakup(:acc_type,:acc_code,:credit_acc_head,:amount, :fin_year, :ejv_chalan_no,:dcode ,:lbcode,:statecode,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
            $res1 = $ExpenseJournalVoucher->prepare($save_query, [":acc_type" => 1,":acc_code" => $credit_acc_code,":credit_acc_head" => $credit_acc_head,
                ":amount" => $amount, ":fin_year"=>$fin_year, ":ejv_chalan_no" =>$ejv_chalan_no,":dcode" => $dcode,":lbcode" => $lbcode,":statecode" => 33,":getCurrentUser" => $user_name,":getIpAddress" => $ip_address,":edit_id" => $credit_edit_id,":delete_id" => $credit_delete_id], 4);
        }
        if (!isset($res1->errorInfo)) {
            $ExpenseJournalVoucher->commit();
            $sel_qry = "select ejv_breakupid,credit_account_id, credit_account_head, credit_amount, b.account_head_name_en, account_code from (select ejv_breakupid, credit_account_id, credit_account_head, credit_amount from accounts_master.t_ej_voucher_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and ejv_chalan_no=:ejv_chalan_no  and account_type=:account_type and fin_year=:fin_year)a left join (SELECT account_head_id, old_account_head_code as account_code, account_head_name_en FROM accounts_master.m_account_head)b on a.credit_account_id=b.account_head_id;";		
            $sel_qry_res=$ExpenseJournalVoucher->prepare($sel_qry,array( ":dcode"=>$dcode,  ":lbcode"=>$lbcode, ":isactive"=>1, ":ejv_chalan_no"=>$ejv_chalan_no, ":account_type"=>1, ":fin_year"=>$fin_year),2);
            ob_start();
            foreach($sel_qry_res as $sel_qry_row){
                ?>
                <tr>
                    <td><?php echo htmlentities($sel_qry_row['account_code']); ?></td>
                    <td><?php echo htmlentities($sel_qry_row['credit_account_head']); ?></td>
                    <td><?php echo htmlentities($sel_qry_row['credit_amount']); ?>
                        <input type="hidden" name="credit_bank_id" value="<?php echo htmlentities($sel_qry_row['ejv_breakupid']);?>" class="bank_id" />
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
            $Result_Data['STATUS']='SUCCESS';
            $Result_Data['credit_data_table']=$ob_contents;
            $Result_Data['credit_amount'] = $credit_amount; 
        }else{
            $Result_Data['STATUS']='FAIL';
            $Result_Data['MESSAGE']='Data Save Failed';
        }
		       
        echo json_encode($Result_Data);
        exit;
    }
    if ($cmd == 10) {
        $dcode = $ExpenseJournalVoucher->getCurrentDistrictCode();
        $lbcode = $ExpenseJournalVoucher->getCurrentLocalBodyCode(); 
        $fin_year =  $ExpenseJournalVoucher->getFinYear(); 
        $voucher_type=base64_decode($_POST['type']);
        list($date_dateofreceived,$month_dateofreceived,$year_dateofreceived)=explode('-',base64_decode($_POST['ejv_date']));
            $chalan_date=$year_dateofreceived.'-'.$month_dateofreceived.'-'.$date_dateofreceived;
       
        
       $bpv_check_query = "SELECT COUNT(*) as cnt FROM accounts_master.t_bank_payment_voucher 
                    WHERE del_flag IS NULL AND dcode = :dcode AND lbcode = :lbcode 
                    AND voucher_date = :voucher_date AND voucher_type=:voucher_type";

        $bpv_check_res = $ExpenseJournalVoucher->prepare($bpv_check_query, array(
            ":dcode" => $dcode,
            ":lbcode" => $lbcode,
            ":voucher_date" => $chalan_date,
            ":voucher_type"=>$voucher_type
        ), 4);

        if ($bpv_check_res['cnt'] > 0) {
            echo json_encode(array(
                "STATUS" => "ERROR",
                "MESSAGE" => "You cannot add new entries for $chalan_date. BPV has already been processed."
            ));
            exit;
        }
        ?>
		<?php 
        $latest_date_query = "SELECT ejv_date 
        FROM accounts_master.t_ej_voucher
        WHERE del_flag IS NULL  and dcode=:dcode and lbcode=:lbcode
        ORDER BY ejv_date DESC 
        LIMIT 1";
        $sel_dname_res=$ExpenseJournalVoucher->prepare($latest_date_query,array(":dcode"=> $dcode,":lbcode"=> $lbcode),4);
        $chalan_date_raw = base64_decode($_POST['ejv_date']); 
        $chalan_date = DateTime::createFromFormat('d-m-Y', $chalan_date_raw)->format('Y-m-d');
        if(isset($sel_dname_res['ejv_date']) && $sel_dname_res['ejv_date']!=''){ 
            $latest_date = date('Y-m-d', strtotime($sel_dname_res['ejv_date']));
        }else{
            $query = "SELECT * from public.sp_date_from_fin_year(:fin_year)";
            $query_res=$ExpenseJournalVoucher->prepare($query,array(":fin_year"=> $fin_year),4);
            $latest_date = json_decode($query_res['sp_date_from_fin_year'])->from_date;
        }
            

        if (strtotime($chalan_date) < strtotime($latest_date)) {
            $display_date = date('d-m-Y', strtotime($latest_date));
            echo json_encode(array(
                "STATUS" => "ERROR",
                "MESSAGE" => "You cannot select a past skipped date. The last Voucher Entered date is: $display_date."
            ));
            exit;
        }
        echo json_encode(["STATUS"=>"SUCCESS"]);
        }
}

?>