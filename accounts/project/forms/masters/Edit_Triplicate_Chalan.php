<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once '../../config/config.php';


if($_SERVER['REQUEST_METHOD']=='GET')
{

    if( !isset($_GET['query_chalan_no']))
    {
        echo ("No Chalan Number has been sent along this request for editing ");
        die();
    }
    else{
        $conn=new ConfigClass();
        $query_chalan_no=base64_decode($_GET['query_chalan_no']);
        $dcode=$conn->getCurrentDistrictCode();
        $lbcode=$conn->getCurrentLocalBodyCode();
        $fin_year=$conn->getFinYear();
        $res=$conn->prepare('select count(*) as "count" from accounts_master.t_triplicate_chalan_details where tc_serial_no=:chalan_no and dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year and del_flag is null',[":chalan_no"=>$query_chalan_no,
        ":dcode"=>$dcode,
        ":lbcode"=>$lbcode,
        ":fin_year"=>$fin_year        
    ],4);
        if($res["count"]==0)
        {
            echo("Chalan Number".$query_chalan_no." Does not exist");
            die();
        }
    }
}
class Adjust_Triplicate_Chalan extends ConfigClass
{

    public $page_token = "triplicate_challan";
    public function __construct()
    {
        if (!isset($this->db)) {
        }
    }

    public function main_content($post_data_array = array())
    {
         //print_r($post_data_array);
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
        ?>
        <script type="text/javascript">
            //ready function
            $(document).ready(function () {




                //disabling pay_mode through css  :

                $("#pay_mode").css("pointer-events", "none");
                $("#pay_mode").css("background-color", "#e9ecef");
                var rc_serial_no = $('#rc_serial_no').val();
                // $.ajax({
                //     url: "",
                //     type: "POST",
                //     data: {
                //         rc_serial_no: btoa(rc_serial_no),
                //         cmd: btoa(7)
                //     },
                //     success: function (response) {
                //         console.log("Cleanup response:", response);

                //     }
                // });

                $('input[name=cash_from_type]').click(function () {
                    var type = $(this).val();
                    $.ajax({
                        url: "Edit_Triplicate_Chalan.php?query_chalan_no=<?=$_GET['query_chalan_no']?>",
                        type: "post",
                        data: {
                            "type": btoa(type),
                            "cmd": btoa(1)
                        },
                        success: function (data) {
                            if (type == "Collection") {
                                $("#cash_coll_date_row").show();
                                $("#cash_coll_amt_row").show();
                                $("#cash_amt_row").hide();
                            } else if (type == "Accounts") {
                                $("#cash_coll_date_row").hide();
                                $("#cash_coll_amt_row").hide();
                                $("#cash_amt_row").show();
                            }
                            $('#account_code').html(data);
                        },
                        dataType: 'html'
                    });
                });

                //set date initially if collection is selected as default

                /*
 
 
                if($('input:radio[name=cash_from_type]:checked').val() == 'Collection')
                {
                    $.ajax({
                        url:'Adjust_Triplicate_Chalan.php',
                        data:{cmd:btoa(10)},
                        success:function(data)
                        {
                            let disabledDates=JSON.parse(data);
                            $("#cash_coll_date").datepicker({beforeShowDay:function(date)
                                {
                                    let curr_date= date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate() 
                                    return [disabledDates.indexOf(curr_date)===-1] 
                                }
                            })
                        }
    
                    })
                }
 
 
                */





                $('#loading-image').hide();
                $('#rc_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd-mm-yyyy',
                    minDate: new Date('01-01-1970'),
                    maxDate: new Date()
                }).on('change', function (e) {
                    const chalan_date = $(this).val();
                    $.ajax({
                        url: "<?php echo $_SERVER['REQUEST_URI'];?>",
                        type: "post",
                        data: {
                            "chalan_date": btoa(chalan_date),
                            "cmd": btoa(3),
                        },
                        success: function (response) {
                            $('#loading-image').hide();
                            //console.log(`data from chalan_date change : ${response}`)
                            const res = JSON.parse(response);

                            if (res.STATUS === "ERROR") {
                                alert(res.MESSAGE);
                                $('#rc_date').val('');
                            }
                        }
                    });
                });
                $('#cheque_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd-mm-yyyy',
                    minDate: new Date('01-01-1970'),
                    maxDate: new Date()
                });
                $('#cash_coll_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd-mm-yyyy',
                    maxDate: function () {
                        const today = new Date();
                        today.setDate(today.getDate() - 1); // set max date to yesterday
                        return today;
                    }
                }).on('change', function (e) {
                    const pay_mode = $("#pay_mode").val();
                    if (pay_mode === "") {
                        $(this).val("");
                        alert("Please select Payment Mode.");
                        return false;
                    }
                    $('#loading-image').show();
                    const collection_date = $(this).val();


                    /*
                    $.ajax({
                            url: "../ajax/AjaxGetTax_Rate.php",
                            type: "post",
                            data: {
                                "pay_mode": btoa(pay_mode),
                                "cmd": btoa(2),
                                "collection_date": btoa(collection_date)
                            },
                            success: function(response) {
                                try {
                                    const data = JSON.parse(response);
                                    if (data.grand_total !== undefined) {
                                        $('#loading-image').hide();
                                        $('#amount').text(data.grand_total);
                                        $('#amount_hidden').val(data.grand_total);
                                    }
                                } catch (e) {
                                    console.error("Invalid JSON response:",
                                        response);
                                    $('#amount').text('');
                                    $('#amount_hidden').val('');
                                }
                            }
                        });
                    */
                    $.ajax({
                        url: "<?php echo $_SERVER['REQUEST_URI']; ?>",
                        type: 'post',
                        data: {
                            cmd: btoa(10),
                            collection_date: btoa(collection_date),
                            pay_mode: btoa(pay_mode),
                            type: btoa('Collection')
                        },
                        success: function (data) {
                            let response = JSON.parse(data);
                            if (response.STATUS == 'FAILED') {
                                $('#cash_coll_date').val('');
                                $("#amount").html('');
                                $("#coll_amount_hidden").val("");
                                alert('collection already entered for this date');
                                $('#loading-image').hide();
                            } else {
                                $.ajax({
                                    url: "../ajax/AjaxGetTax_Rate.php",
                                    type: "post",
                                    data: {
                                        "pay_mode": btoa(pay_mode),
                                        "cmd": btoa(2),
                                        "collection_date": btoa(collection_date)
                                    },
                                    success: function (response) {
                                        try {
                                            const data = JSON.parse(response);
                                            if (data.grand_total !== undefined) {
                                                $('#loading-image').hide();
                                                $('#amount').text(data.grand_total);
                                                $('#amount_hidden').val(data.grand_total);
                                            }
                                        } catch (e) {
                                            console.error("Invalid JSON response:",
                                                response);
                                            $('#amount').text('');
                                            $('#amount_hidden').val('');
                                        }
                                    }
                                });
                            }
                        }
                    })
                })


                $('#cheque_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd-mm-yyyy',
                    minDate: new Date('01-01-1970'),
                    maxDate: new Date()
                });
                $('#dd_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd-mm-yyyy',
                    minDate: new Date('01-01-1970'),
                    maxDate: new Date()
                });
                $('#ecs_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd-mm-yyyy',
                    minDate: new Date('01-01-1970'),
                    maxDate: new Date()
                });
                $('#account_code').change(function () {
                    $('#account_head').val($('option:selected', this).attr('data-desc'));
                });
                //bank_Code_On_Change
                $('#bank_code').change(function (event, isEditMode, editBranchId) {
                    let bank_code = $(this).val();
                    $.ajax({
                        url: "<?php echo $_SERVER['REQUEST_URI']; ?>",
                        type: "post",
                        data: {
                            cmd: btoa(8),
                            bank_code: btoa(bank_code)
                        },
                        dataType: 'text',
                        success: function (data) {
                            let branch_lists_html = "<option value>choose</option>";

                            let branch_lists = JSON.parse(data);
                            //console.log(branch_lists);
                            //console.log(typeof branch_lists);

                            branch_lists.forEach((branch) => {
                                let curr =
                                    `<option value=${branch["branch_id"]}>${branch["branch_name"]}</option>`;
                                branch_lists_html += curr;
                            });
                            $("#bank_branch").html(branch_lists_html);
                            if(isEditMode)
                            {
                                //console.log('setting branch id');
                                //console.log(editBranchId);
                                $("#bank_branch").val(editBranchId);
                            }

                        },
                        error: function (xhr, error, status) {
                            //console.log(error);
                            //console.log(status);
                        }
                    });
                    $('#bank_name').val($('option:selected', this).attr('data-desc'));
                });
                //bank_branch_On_Change
                /*
                $('#bank_branch').change(function () {
                    let bank_branch_id = $("#bank_branch").val();
                    let bank_id = $('#bank_code').val();
                    $.ajax({
                        url: "Adjust_Triplicate_Chalan.php",
                        type: "post",
                        data: {

                            cmd: btoa(9),
                            bank_branch_id: btoa(bank_branch_id),
                            bank_id: btoa(bank_id)
                        },
                        success: function (data) {
                            //let cheque_number=JSON.parse(data);
                            //console.log(data);

                            $("#cheque_no").val(JSON.parse(data));
                        },
                        error: function (xhr, error, status) {
                            //console.log(error);
                            //console.log(status);
                        },
                        dataType: 'text'
                    });

                });
                */
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

                $(document).on('click', '#btn_debit_add', function() {
                                        //console.log('debit bank head:'+$('#debit_bank_head').val());
                    try {
                        
                        if ($("#debit_bank_code").val().length == 0) {
                            throw {
                                msg: "Select Bank Code",
                                foc: "#bank_code"
                            }
                        }else{
                            var bank_code = $("#debit_bank_code").val();
                        }
                        if ($("#debit_bank_head").val().length == 0) {
                            throw {
                                msg: "Enter Bank Head",
                                foc: "#debit_bank_head"
                            }
                        }else{
                            var bank_head = $("#debit_bank_head").val();
                        }

                        if ($("#debit_amount").val().length == '' && /^[+-]?\d+(\.\d+)?$/.test($("#debit_amount").val().trim())) {
                            throw {
                                msg: "Enter Debit Amount",
                                foc: "#bank_head"
                            }
                        }else{
                            var amount = $("#debit_amount").val();
                        }
                        if($('#rc_date').val()=='')
                        {
                            throw{
                                msg:'Select Date',foc:'#rc_date'
                            }
                        }
                        else{
                            var challan_date=$("#rc_date").val();
                        }
                        //console.log($('input[name="cash_from_type"]:checked').val());
                        if($('input[name="cash_from_type"]:checked').length == 0)
                        {
                            throw{
                                msg:'Select Cash From Type',foc:''
                            }
                        }
                        else{
                            var cash_from_type = $('input[name="cash_from_type"]:checked').val();
                        }
                    } catch (e) {
                        alert(e);
                        alert(e.msg);
                        $('#' + Current_Field_id).show();
                        $(e.foc).focus();
                        return false;
                    }
                    var edit_id = $("#debit_edit_id").val()==''?0:$("#debit_edit_id").val();
                    var delete_id = $("#debit_delete_id").val()==''?0:$("#debit_delete_id").val();
                    let rc_serial_no=$("#rc_serial_no").val();
                    
                    if(Number($('#span_debit_total_amount').html())+Number(amount)>Number($('#span_credit_total_amount').html()) && edit_id=='' )
                    {
                        alert(' Total Debit Amount should be lesser than Total Credit Amount');
                        return false;
                    }
                    $.ajax({
						url: "<?php echo $_SERVER['REQUEST_URI']; ?>",
						type: "post",
						data: {
                            
                            "rc_serial_no": btoa(rc_serial_no),
							"bank_code": btoa(bank_code),
                            "bank_head": btoa(bank_head),
                            "challan_date":btoa(challan_date),
                            "cash_from_type":btoa(cash_from_type),
                            "amount": btoa(amount),
                            "edit_id":btoa(edit_id),
                            "delete_id":btoa(delete_id),
							"cmd": btoa(12)
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
                $(document).on('click', '#btn_credit_add', function(){
                    try {
                        if ($("#rc_serial_no").val().length == '') {
                            throw {
                                msg: "Missing Serial Number",
                                foc: "#rc_serial_no"
                            }
                        }else{
                            var rc_serial_no = $("#rc_serial_no").val();
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
                        if ($("#credit_amount").val().length == '' && /^[+-]?\d+(\.\d+)?$/
.test($("#credit_amount").val().trim())) {
                            throw {
                                msg: "Enter Credit Amount",
                                foc: "#credit_amount"
                            }
                        }else{
                            var credit_amount = $("#credit_amount").val();
                        }
                        if($('#rc_date').val()=='')
                        {
                            throw{
                                msg:'Select Date',foc:'#rc_date'
                            }
                        }
                        else{
                            var challan_date=$("#rc_date").val();
                        }
                      //  console.log($('input[name="cash_from_type"]:checked').val());
                        if($('input[name="cash_from_type"]:checked').length == 0)
                        {
                            throw{
                                msg:'Select Cash From Type',foc:''
                            }
                        }
                        else{
                            var cash_from_type = $('input[name="cash_from_type"]:checked').val();
                        }
                        if($('#pay_mode').val()=='')
                        {
                            
                            throw{
                                msg:'Select pay mode',foc:'#pay_mode'
                            }
                        }
                        else{var paymode=$('#pay_mode').val();}
                        
                        // alert(credit_bank_head);
                    } catch (e) {
                        alert(e.msg);
                        $('#' + Current_Field_id).show();
                        $(e.foc).focus();
                        return false;
                    }
                    var edit_id = $("#credit_edit_id").val()==''?0:$("#credit_edit_id").val();
                    var delete_id = $("#credit_delete_id").val()==''?0:$("#credit_delete_id").val();
                    // var credit_bank_head = $("#credit_bank_head").html();
                    var credit_total_amount=0;
                    $.ajax({
						url: "<?php echo $_SERVER['REQUEST_URI']; ?>",
						type: "post",
						data: {
                            
                            "rc_serial_no": btoa(rc_serial_no),
							"credit_bank_code": btoa(credit_bank_code),
                            "credit_bank_head": btoa(credit_bank_head),
                            "challan_date":btoa(challan_date),
                            "cash_from_type":btoa(cash_from_type),
                            'pay_mode':btoa($('#pay_mode').val()),
                            "amount": btoa(credit_amount),
                            "edit_id":btoa(edit_id),
                            "delete_id":btoa(delete_id),
							"cmd": btoa(13)
						},
						success: function(data) {
							if (data != '') {
								var Result_Data = JSON.parse(data);
                                if(Result_Data['STATUS'] == 'SUCCESS'){
                                    alert('Successfully Added');
                                    $("#credit_table_result tbody").html(Result_Data['credit_data_table']);
                                    $('#credit_total_amount').val(Result_Data['credit_amount']);
                                    credit_total_amount=Result_Data['credit_amount'];
                                    $('#span_credit_total_amount').html(Result_Data['credit_amount']);
                                    $('#credit_delete_id').val('');
                                    $('#credit_edit_id').val('');
                                    $('#credit_bank_code').val('');
                                    $('#credit_bank_head').html('');
                                    $('#credit_amount').val('');
                                    $("#btn_credit_add").val('Add Credit');

                                    if(paymode==1)
                                    {
                                        //et debit_breakup_id=$('#debit_breakup_id').val();
                                        let debit_breakup_id=$("[name='debit_breakup_id']").val();
                                        let row=`<tr><td>${3059}</td><td>3059 - General Account <td>${credit_total_amount}
                                        <input type="hidden" name="debit_breakup_id" value="${debit_breakup_id}" class="bank_id" />
                                        </td></tr>`;
                                        if(credit_total_amount<=0)
                                        {
                                                                                  $("#debit_table_result tbody").html(`<tr><td><input type="hidden" name="debit_breakup_id" value="${debit_breakup_id}" class="bank_id" /><td></tr>`);
                                                                                  $('#debit_total_amount').val('');
                                        $('#span_debit_total_amount').html('');
  
                                        }
                                        else{
                                            $("#debit_table_result tbody").html(row);
                                        //$("#debit_bank_code").val('2');
                                        $('#debit_total_amount').val(credit_total_amount);
                                        $('#span_debit_total_amount').html(credit_total_amount);
                                        }
                                        
                                        $('#debit_delete_id').val('');
                                        $('#debit_edit_id').val('');
                                        //$('#debit_bank_code').val('');
                                        //$('#debit_bank_head').val('');
                                        $('#debit_amount').val('');
                                    }

                                }else{
                                    alert(Result_Data['MESSAGE'] );
                                }
							}
						},
						dataType: 'html'
					});	
                
                    
                });	
                $(document).on('click', '.btn_debit_edit', function() {
                var id = $(this).closest('tr').find('.bank_id').val();
                    $.ajax({
						url: "<?php echo $_SERVER['REQUEST_URI'];?>",
						type: "post",
						data: {
                            "account_type":btoa(2),
                            "id":btoa(id),
							"cmd": btoa(11)
						},
						success: function(data) {
							if (data != '') {
								var Result_Data = JSON.parse(data);
                                $("#btn_debit_add").val("Edit Debit");
								$('#debit_bank_code').val(Result_Data['bank_code']);
                                $('#debit_bank_head').val(Result_Data['bank_head']);
                                $('#debit_amount').val(Result_Data['debit_amount']);
                                $('#debit_delete_id').val('');
                                $('#debit_edit_id').val(Result_Data['accounthead_breakup_id']);
							}
						},
						dataType: 'html'
					});	
                });	
                $(document).on('click', '.btn_debit_delete', function() {
                    var id = $(this).parent().parent().find('.bank_id').val();
                    $.ajax({
						url: "<?php echo $_SERVER['REQUEST_URI'] ; ?>",
						type: "post",
						data: {
                            "account_type":btoa(2),
                            "id":btoa(id),
							"cmd": btoa(11)
						},
						success: function(data) {
							if (data != '') {
								var Result_Data = JSON.parse(data);
                                $("#btn_debit_add").val('Delete Debit');
								$('#debit_bank_code').val(Result_Data['bank_code']);
                                $('#debit_bank_head').val(Result_Data['bank_head']);
                                $('#debit_amount').val(Result_Data['debit_amount']);
                                $('#debit_delete_id').val(Result_Data['accounthead_breakup_id']);
                                $('#debit_edit_id').val('');
							}
						},
						dataType: 'html'
					});	
                });	
                $(document).on('click', '.btn_credit_edit', function() {
                var id = $(this).closest('tr').find('.bank_id').val();
                    $.ajax({
						url: "<?php echo $_SERVER['REQUEST_URI']; ?>",
						type: "post",
						data: {
                            "account_type":btoa(1),
                            "id":btoa(id),
							"cmd": btoa(14)
						},
						success: function(data) {
							if (data != '') {
								var Result_Data = JSON.parse(data);
                                $("#btn_credit_add").val("Edit Credit");
								$('#credit_bank_code').val(Result_Data['bank_code']);
                                $('#credit_bank_head').val(Result_Data['bank_head']);
                                $('#credit_amount').val(Result_Data['credit_amount']);
                                $('#credit_delete_id').val('');
                                $('#credit_edit_id').val(Result_Data['accounthead_breakup_id']);
							}
						},
						dataType: 'html'
					});	
                });	
                $(document).on('click', '.btn_credit_delete', function(){
                var id = $(this).parent().parent().find('.bank_id').val();
                    $.ajax({
						url: "<?php echo $_SERVER['PHP_SELF']; ?>",
						type: "post",
						data: {
                            "account_type":btoa(1),
                            "id":btoa(id),
							"cmd": btoa(14)
						},
						success: function(data) {
							if (data != '') {
								var Result_Data = JSON.parse(data);
                                $("#btn_credit_add").val("Delete Credit");
								$('#credit_bank_code').val(Result_Data['bank_code']);
                                $('#credit_bank_head').val(Result_Data['bank_head']);
                                $('#credit_amount').val(Result_Data['credit_amount']);
                                $('#credit_delete_id').val(Result_Data['accounthead_breakup_id']);
                                $('#credit_edit_id').val('');
							}
						},
						dataType: 'html'
					});	
                     });
               

                
                
                $('#pay_mode').change(function () {
                                $("#debit_current_amount_tr").show();
                                $('#debit_bank_code').prop('disabled', false);
                                $('#debit_bank_code').val('');
                                $('#debit_bank_code option[value="2"]').hide();
                                $('#debit_amount').show();
                                 $('#bank_code_row').show();
                                $("#btn_debit_add").show();
                            
                                /*        
                                if($('#current_pay_mode').val()==1 && $(this).val!=1)
                                {
                                    console.log('current_pay_mode');
                                    $('#debit_table_result tbody').html('');
                                    $('#debit_total_amount').val('');
                                    $('#span_debit_total_amount').html('');
                                    $.ajax({
                                        data:{cmd:btoa(18),chalan_no:btoa($("#rc_serial_no").val())},
                                        url:"<?php #$_SERVER['REQUEST_URI']; ?>",
                                        type:"post",
                                        success(data)
                                        {

                                        }
                                    });
                                }
                                    */
                                    
                                if ($(this).val() == '2') {
                                    $('.pay_mode_dd').hide();
                                    $('.pay_mode_ecs').hide();
                                    $('.pay_mode_cheque').show();
                                    $('#bank_name_row').show();
                                    $('#bank_code_row').show();
                                    $('#bank_branch_row').show();
                                } else if ($(this).val() == '3') {
                                    $('.pay_mode_cheque').hide();
                                    $('.pay_mode_ecs').hide();
                                    $('.pay_mode_dd').show();
                                    $('#bank_name_row').show();
                                    $('#bank_code_row').show();
                                } else if ($(this).val() == '4') {
                                    $('.pay_mode_dd').hide();
                                    $('.pay_mode_cheque').hide();
                                    $('.pay_mode_ecs').show();
                                    $('#bank_name_row').show();
                                    $('#bank_code_row').show();
                                } else {
                                    $('.pay_mode_dd').hide();
                                    $('.pay_mode_cheque').hide();
                                    $('.pay_mode_ecs').hide();
                                    $('#bank_name_row').hide();
                                    $('#bank_branch_row').hide();
                                    $('#bank_code_row').hide();
                                    $("#btn_debit_add").hide();
                                    //$("#debit_amount").hide();
                                    $("#debit_current_amount_tr").hide();
                                    
                                    /*DEBIT ACCOUNT HEAD CODE ---- change account head id below to make it static*/
                                    $('#debit_bank_code option[value="865"]').show();

                                    $('#debit_bank_code').val(865);//id of account head no 3059 in local db
                                    $('#debit_bank_code').prop('disabled', true);
                                    
                                }
                                $('#current_pay_mode').val($(this).val())
                            });
                
                
                $(document).on('click', "#btn_save", function () {
                    var Current_Field_id = $(this).attr('id');
                    $('#' + Current_Field_id).hide();
                    try {
                        if ($("#rc_serial_no").val().length == '') {
                            throw {
                                msg: "Enter Chalan Serial No",
                                foc: "#rc_serial_no"
                            }
                        }
                        if ($("#rc_date").val().length == '') {
                            throw {
                                msg: "Enter Chalan Date",
                                foc: "#rc_date"
                            }
                        }
                        if ($("#pay_mode").val().length == '') {
                            throw {
                                msg: "Select Payment Mode",
                                foc: "#pay_mode"
                            }
                        } else if ($("#pay_mode").val() == '2') {
                            if ($("#cheque_no").val().length=='') {
                                throw {
                                    msg: "Enter Cheque No.",
                                    foc: "#cheque_no"
                                }
                            }else if(!(/^\d{6}$/.test($('#cheque_no').val())))
                            {
                                    throw {
                                    msg: "Cheque No. Should be of 6 digit number",
                                    foc: "#cheque_no"
                                }
                            }
                            else if ($("#cheque_no").val() == "-") {
                                throw {
                                    msg: "there is no available cheques , please select any other bank or branch",
                                    foc: "#cheque_no"
                                }
                            }
                            if ($("#cheque_date").val().length == '') {
                                throw {
                                    msg: "Enter Cheque Date",
                                    foc: "#cheque_date"
                                }
                            }
                            if ($("#bank_name").val().length == '') {
                                throw {
                                    msg: "Enter Bank Name",
                                    foc: "#bank_name"
                                }
                            }
                            if($("#bank_branch").val().length=='')
                            {
                                throw {
                                    msg: "Choose Bank Branch",
                                    foc: "#bank_branch"
                                }
                            }
                        } else if ($("#pay_mode").val() == '3') {
                            if ($("#dd_no").val().length == '') {
                                throw {
                                    msg: "Enter DD No.",
                                    foc: "#dd_no"
                                }
                            }
                            if ($("#dd_date").val().length == '') {
                                throw {
                                    msg: "Select DD Date",
                                    foc: "#dd_date"
                                }
                            }
                            if ($("#bank_name").val().length == '') {
                                throw {
                                    msg: "Enter Bank Name",
                                    foc: "#bank_name"
                                }
                            }
                        } else if ($("#pay_mode").val() == '4') {
                            if ($("#ecs_no").val().length == '') {
                                throw {
                                    msg: "Enter ECS No.",
                                    foc: "#ecs_no"
                                }
                            }
                            if ($("#ecs_date").val().length == '') {
                                throw {
                                    msg: "Select ECS Date",
                                    foc: "#ecs_date"
                                }
                            }
                            if ($("#bank_name").val().length == '') {
                                throw {
                                    msg: "Enter Bank Name",
                                    foc: "#bank_name"
                                }
                            }
                        }
                        if ($("#remitter_name_address").val().length == '') {
                            throw {
                                msg: "Enter Name and Address of Remitter",
                                foc: "#remitter_name_address"
                            }
                        }
                        if ($('input:radio[name=cash_from_type]:checked').length == 0) {
                            throw {
                                msg: "Choose Cash From",
                                foc: "#amount_hidden"
                            }
                            if ($('input[value="amount_hidden"]').prop("checked", false)) {
                                if ($('input[value="Collection"]').prop("checked", true)) {
                                    if ($("#cash_coll_date").val().length == '') {
                                        throw {
                                            msg: "Enter Cash Collection Date",
                                            foc: "#cash_coll_date"
                                        }
                                    }
                                }
                            }
                        }
                        // if ($("#account_code").val().length == '') {
                        //     throw {
                        //         msg: "Select Account Code",
                        //         foc: "#account_code"
                        //     }
                        // }      
                        // if ($("#amount_hidden").val().length == '') {
                        //     throw {
                        //         msg: "Enter Amount",
                        //         foc: "#amount_hidden"
                        //     }
                        // }   
                        if ($("#narration").val().length == '') {
                            throw {
                                msg: "Enter Narration",
                                foc: "#narration"
                            }
                        }
                        let selectedType = $('input[name=cash_from_type]:checked').val();
                        //let enteredAmount = 0;
                        let creditAmount=0;let debitAmount=0;

                        if (selectedType === 'Collection') {
                            //enteredAmount = parseFloat($("#coll_amount_hidden").val() || 0);
                        } else if (selectedType === 'Accounts') {
                            creditAmount = parseFloat($("#span_credit_total_amount").html() || 0);
                            debitAmount=parseFloat($("#span_debit_total_amount").html() || 0);
                        }

                        //console.log(`total amount : ${totalAmount} entered amount : ${enteredAmount}`)

                        if (creditAmount !== debitAmount) {
                            throw {
                                msg: "Total Amount mismatch. Please verify.",
                                foc: ""
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



                $(document).on('click', "#add_amount", function () {
                    var accountCode = $("#account_code").val();
                    var accountAmount = $("#account_amount").val();
                    var rc_serial_no = $("#rc_serial_no").val();
                    var rc_date = $("#rc_date").val();
                    if (rc_date === "") {
                        alert("Please select Chalan Date.");
                        return false;
                    }

                    var cash_from_type = $("input[name='cash_from_type']:checked").val();
                    var edit_id = $("#edit_id").val();
                    if (edit_id === "" || edit_id === null || typeof edit_id === "undefined") {
                        edit_id = 0;
                    }

                    if (accountCode === "" || accountAmount === "") {
                        alert("Please select account code and enter amount.");
                        return false;
                    }
                    let selectedType = $('input[name=cash_from_type]:checked').val();
                    /*
                    if(selectedType==='Collection')
                    {
                        $('#coll_amount_hidden').val(accountAmount);
                    }
                    else if(selectedType==='Accounts')
                    {
                        $('#acc_amount_hidden').val(accountAmount);
                    }*/
                    $.ajax({
                        url: '<?php echo $_SERVER['REQUEST_URI']; ?>', // your PHP file
                        type: 'POST',
                        dataType: "html",
                        data: {
                            account_code: btoa(accountCode),
                            account_amount: btoa(accountAmount),
                            rc_serial_no: btoa(rc_serial_no),
                            rc_date: btoa(rc_date),
                            edit_id: btoa(edit_id),
                            cash_from_type: btoa(cash_from_type),
                            "cmd": btoa(4)
                        },
                        success: function (response) {
                            // Check if response is valid JSON
                            if (isJson(response)) {
                                let res = JSON.parse(response);

                                if (res.status === "duplicate") {
                                    alert(res.message); // Duplicate entry alert
                                } else if (res.status === "success") {
                                    $("#acc_code_table").html(res.html);
                                    $("#account_amount").val('');
                                    $("#account_code").val('');
                                    $("#edit_id").val('');
                                    //$("#amount_total").val(res.total_amount);
                                    if (selectedType === 'Collection') {
                                        $('#coll_amount_hidden').val(res.total_amount);
                                    }
                                    else if (selectedType === 'Accounts') {
                                        $('#acc_amount_hidden').val(res.total_amount);
                                    }
                                    $("#acc_codes_hidden").val(res.acc_codes);
                                    $("#add_amount").text("Add Amount");
                                    edit_id != '' ? alert("account head successfully changed") : alert(res.message); // Entry added alert                
                                }
                            } else {
                                console.error("Invalid JSON response:", response);
                            }
                        }
                    });

                    function isJson(str) {
                        return typeof str === "string" &&
                            str.trim().startsWith("{") &&
                            str.trim().endsWith("}");
                    }
                });


                $(document).on('click', "#btn_edit", function () {
                    var account_head_id = $(this).val();
                    $.ajax({
                        url: '<?php echo $_SERVER['REQUEST_URI']; ?>',
                        type: 'POST',
                        dataType: "json",
                        data: {
                            account_head_id: btoa(account_head_id),
                            "cmd": btoa(5)
                        },
                        success: function (response) {
                            if (response.STATUS === "success") {
                                $('#account_code').val(response.account_head_code);
                                $('#account_amount').val(response.account_amount);
                                $('#edit_id').val(response.accounthead_breakup_id);
                                $("#add_amount").text('Edit Amount');

                            } else {
                                alert("Insert failed!");
                            }
                        }
                    });
                });
                $(document).on('click', "#btn_del", function () {
                    var del_id = $(this).val();
                    var chalan_no = $("").val()
                    $.ajax({
                        url: '<?php echo $_SERVER['REQUEST_URI']; ?>',
                        type: 'POST',
                        data: {
                            del_id: btoa(del_id),
                            chalan_no: btoa($("#rc_serial_no").val()),
                            chalan_date: btoa($("#rc_date").val()),
                            cmd: btoa(6)
                        },
                        success: function (response) {
                            // Check if response is valid JSON
                            if (isJson(response)) {
                                let res = JSON.parse(response);
                                if (res.status === "success") {
                                    $("#acc_code_table").html(res.html);
                                    //$("#amount_total").val(res.total_amount);
                                    $("#acc_codes_hidden").val(res.acc_codes);
                                    $("#add_amount").text("Add Amount");
                                    $("#account_code").val('');
                                    $("#account_amount").val('');


                                    let selectedType = $('input[name=cash_from_type]:checked').val();
                                    if (selectedType === 'Collection') {
                                        $('#coll_amount_hidden').val(res.total_amount);
                                    }
                                    else if (selectedType === 'Accounts') {
                                        $('#acc_amount_hidden').val(res.total_amount);
                                    }





                                    alert(res.message);
                                }
                            } else {
                                console.error("Invalid JSON response:", response);
                            }
                        }
                    });

                    function isJson(str) {
                        return typeof str === "string" &&
                            str.trim().startsWith("{") &&
                            str.trim().endsWith("}");
                    }
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
                
        function initialSetUp() {
    $.ajax({
        // get triplicate chalan details
        url: "Edit_Triplicate_Chalan.php?query_chalan_no=<?= $_GET['query_chalan_no'] ?>",
        data: {
            cmd:btoa(16),
            chalan_no: btoa(<?= base64_decode($_GET['query_chalan_no']) ?>)
        },
        type: "post",
        dataType: 'json',
        success: function (data) {
            //console.log('success');
            //console.log(data);

            // set chalan date:
            let curr_date = data.chalan_date.split(" ")[0].split("-");
            let formatted_date=curr_date[2]+'-'+curr_date[1]+'-'+curr_date[0];
            //console.log(formatted_date);
            $('#rc_date').val(formatted_date);
            //$('#rc_date').datepicker('value', formatted_date);
            //$("#rc_date").trigger('change');
            
            //$("#rc_date").datepicker("setDate",curr_date);
            $("#pay_mode").val(data.paymentmode);
            $("#pay_mode").trigger("change");
            $("#remitter_name_address").val(data.remitter_name);

            switch (data.paymentmode) {
                case 2:
                    $("#bank_code").val(data.bank_code);
                    //console.log("bank code: " + $("#bank_code").val());
                    console.log("edit_bank_branch_id : "+data.bank_branch_id);
                    $("#bank_code").trigger("change",[true,data.bank_branch_id]);
                    $("#cheque_no").val(data.chequeno);
                    let cheque_date=data.chequedate.split(" ")[0].split("-");
                    let formatted_cheque_date=cheque_date[2]+"-"+cheque_date[1]+"-"+cheque_date[0];
                    $("#cheque_date").val(formatted_cheque_date);
                    $("#bank_branch").val(data.bank_branch_id);
                    //console.log("Bank code: " + $("#bank_code").val() + " Bank Branch Id: " + $("#bank_branch").val());
                    break;
                case 3:
                    $("#dd_no").val(data.dd_no);
                    $("#dd_date").val(data.dd_date);
                    $("#bank_code").val(data.bank_code);
                    $("#bank_code").trigger("change",[false,'']);
                    break;
                case 4:
                    $("#bank_code").val(data.bank_code);
                    $("#bank_code").trigger("change",[false,'']);
                    $("#ecs_no").val(data.ecs_no);
                    $("#ecs_date").val(data.ecs_date);
                    break;
            }

            // cash from type 
            $(`input[name=cash_from_type][value=${data.collectiontype}]`).prop('checked', true);
            $(`input[name=cash_from_type][value=${data.collectiontype}]`).trigger('click');

            // narration
            $("#narration").val(data.narration);

            // account head debit 
            $.ajax({
                url: "Edit_Triplicate_Chalan.php?query_chalan_no=<?= $_GET['query_chalan_no'] ?>",
                data: { cmd: btoa(17),account_type:btoa(2),pay_mode:btoa($("#pay_mode").val()) },
                type: 'post',
                dataType: 'json',
                success: function (res) {
                    //console.log(res.html);
                    /*
                    $("#credit_table_result body").html(res.html);
                    $("#account_amount").val('');
                    $("#account_code").val('');
                    $("#edit_id").val('');
                    $("#span_credit_total_amount").val(res.total_amount);
                    $('#credit_total_amount').val(res.total_amount);
                    */
                    $("#debit_table_result tbody").html(res.html);
                    $('#debit_total_amount').val(res.total_amount);
                    $('#span_debit_total_amount').html(res.total_amount);
                    //$("#acc_amount_hidden").val(res.total_amount);
                    //$("#acc_codes_hidden").val(res.acc_codes);
                },
                error: function (xhr, error, status) {
                    console.error(error);
                    console.error(status);
                }
            });


            //account head credit

            $.ajax({
                url: "Edit_Triplicate_Chalan.php?query_chalan_no=<?= $_GET['query_chalan_no'] ?>",
                data: { cmd: btoa(17),account_type:btoa(1),pay_mode:btoa($("#pay_mode").val())},
                type: 'post',
                dataType: 'json',
                success: function (res) {
                    //console.log(res.html);
                    /*
                    $("#credit_table_result body").html(res.html);
                    $("#credit_amount").val('');
                    $("#credit_bank_code").val('');
                    $("#edit_id").val('');
                    $("#amount_total").val(res.total_amount);
                    $("#acc_amount_hidden").val(res.total_amount);
                    $("#acc_codes_hidden").val(res.acc_codes);
                    */

                    $("#credit_table_result tbody").html(res.html);
                    $('#credit_total_amount').val(res.total_amount);
                    $('#span_credit_total_amount').html(res.total_amount);
                },
                error: function (xhr, error, status) {
                    console.error(error);
                    console.error(status);
                }
            });
        }
    });
}

initialSetUp();
        
        </script>
        <style type="text/css">
            .hidden_field_element_value {
                display: none;
            }

            .gj-datepicker {
                width: 50%;
            }
        </style>
        <style>
            #loading-image {
                position: absolute;
                -moz-border-radius: 9px;
                -webkit-border-radius: 9px;
                border-radius: 9px;
                /* future proofing */
                -khtml-border-radius: 9px;
                width: 50px;
                height: 20px;
                overflow: visible;
            }
        </style>
        <div class="container mt-3">
            <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                <input class="form-control form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>"
                    name="<?php echo htmlentities($this->page_token); ?>"
                    value="<?php echo htmlentities($this->token($this->page_token)); ?>">

                <div class="card">
                    <div class="card-body">

                        <div id="loading-image" align="center" style="padding-left:500px">
                            <img src="<?php echo htmlentities($site_data->website_url); ?>/images/ajax_loader_blue_256.gif"
                                alt="Loading..." /><br />
                        </div>

                        <?php
                        if (isset($post_data_array["STATUS"])) {
                            echo $this->ShowMessage($post_data_array["STATUS"], $post_data_array["MESSAGE"]);
                            //header("refresh: 3; url=Adjust_triplicate_Chalan.php");
                        }
                        ?>

                        <!-- First table start -->
                        <table class="table table-bordered m-0 p-0 tndtp_form_table">
                            <thead class="bg-th-form-dsg">
                                <tr>
                                    <th align="center" scope="col" colspan="2">Edit Triplicate Chalan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Chalan Serial No -->
                                <tr>
                                    <td class="text-left font-weight-bold"><span>Chalan Serial No</span></td>
                                    <td>
                                <?php
                                    $query_chalan_no=base64_decode($_GET['query_chalan_no']); 
                                    //$sel_qry="select chalan_no as id from accounts_master.t_triplicate_chalan_details where dcode=:dcode and lbcode=:lbcode and chalan_no=:chalan_no and isactive=:isactive and del_flag is null;";
                                    //$sel_qry_res=$this->prepare($sel_qry, array(":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1 , ":chalan_no"=>$query_chalan_no),4);
                                    $fin_year=$this->getFinYear();
                                    //print_r($ids);die();
                                   
                                    $del_qry="update accounts_master.t_triplicate_accounthead_breakup set del_flag='Y' where dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year and triplicate_chalan_no=:chalan_no and challan_id is null";
                                    $del_qry_res=$this->prepare($del_qry, array(":dcode"=>$dcode, ":lbcode"=>$lbcode, ":fin_year"=>$fin_year, ":chalan_no"=>$query_chalan_no),4);
                                    echo $query_chalan_no;
                                ?>
                                <input type="hidden" id="rc_serial_no" name="rc_serial_no"
                                    value="<?php echo $query_chalan_no; ?>"
                                    class="form-control w-50 form-control-sm" />
                            </td>
                                </tr>

                                <!-- Chalan Date -->
                                <tr>
                                    <td class="text-left font-weight-bold"><span>Chalan Date</span></td>
                                    <td>
                                        <input type="text" id="rc_date" name="rc_date"
                                            value="<?php echo isset($post_data_array['rc_date']) ? $post_data_array['rc_date'] : '' ?>"
                                            class="form-control form-control-sm user_enter_date w-50" />
                                    </td>
                                </tr>

                                <!-- Payment Mode -->
                                <tr>
                                    <td class="text-left font-weight-bold"><span>Payment Mode</span></td>
                                    <td>
                                        <select id="pay_mode" name="pay_mode" class="form-control form-control-sm w-50">
                                            <option value="">Choose</option>
                                            <?php
                                            $sel_payment_type = "select paymenttypeid, paymenttype as paymenttype_en, paymenttype_ta from master.m_paymenttype where del_flag is null and paymenttypeid not in (5,6);";
                                            $sel_payment_type_res = $this->prepare($sel_payment_type, array(), 2);
                                            foreach ($sel_payment_type_res as $sel_payment_type_row) {
                                                ?>
                                                <option value="<?php echo $sel_payment_type_row['paymenttypeid']; ?>">
                                                    <?php echo $sel_payment_type_row['paymenttype_' . $lang_code_2d]; ?>
                                                </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <script type="text/javascript">
                                            document.getElementById('pay_mode').value =
                                                '<?php echo htmlentities((isset($post_data_array['pay_mode']) && $post_data_array['pay_mode'] != '') ? $post_data_array['pay_mode'] : ''); ?>';
                                            $("#pay_mode").trigger('change');
                                        </script>
                                    </td>
                                </tr>

                                <!-- Cheque / DD / ECS fields -->


                                <tr class="pay_mode_dd" style="display:none;">
                                    <td class="text-left font-weight-bold"><span>DD No</span></td>
                                    <td><input type="text" id="dd_no" name="dd_no" class="form-control form-control-sm w-50" />
                                    </td>
                                </tr>
                                <tr class="pay_mode_dd" style="display:none;">
                                    <td class="text-left font-weight-bold"><span>DD Date</span></td>
                                    <td><input type="text" id="dd_date" name="dd_date"
                                            class="form-control form-control-sm user_enter_date w-50" /></td>
                                </tr>
                                <!-- Bank code and name -->
                                <tr id="bank_code_row" style="display:none;">
                                    <td class="text-left font-weight-bold"><span>Bank Code</span></td>
                                    <td>
                                        <select id="bank_code" name="bank_code" class="form-control form-control-sm w-50">
                                            <option value="">Choose</option>
                                            <?php
                                            $sel_bank_new_id = "SELECT bank_id, bank_code, bank_name_" . $lang_code_2d . " FROM accounts_master.m_bank WHERE isactive = :isactive AND del_flag IS NULL AND bank_id in (select bank_id from accounts_master.m_bankbranch where lbcode=:lbcode and dcode=:dcode) ORDER BY bank_code ASC";
                                            $sel_bank_newid_res = $this->prepare($sel_bank_new_id, array(":isactive" => 1, ":lbcode" => $lbcode, ":dcode" => $dcode), 2);
                                            foreach ($sel_bank_newid_res as $sel_bank_newid_row) {
                                                ?>
                                                <option value="<?php echo htmlentities($sel_bank_newid_row['bank_id']); ?>"
                                                    data-desc="<?php echo htmlentities($sel_bank_newid_row['bank_name_' . $lang_code_2d]); ?>">
                                                    <?php echo htmlentities($sel_bank_newid_row['bank_code']); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <script type="text/javascript">
                                            document.getElementById('bank_code').value =
                                                '<?php echo htmlentities(isset($post_data_array['bank_code']) && $post_data_array['bank_code'] != '' ? $post_data_array['bank_code'] : ''); ?>';
                                            $('#bank_code').trigger('change',[false,'']);
                                        </script>
                                    </td>
                                </tr>
                                <!-- bank_name row -->
                                <tr id="bank_name_row" style="display:none;">
                                    <td class="text-left font-weight-bold"><span>Bank Name</span></td>
                                    <td><input type="text" id="bank_name" name="bank_name"
                                            class="form-control form-control-sm w-50"
                                            value="<?php echo isset($post_data_array['bank_name']) && $post_data_array['bank_name'] != '' ? $post_data_array['bank_name'] : "" ?>"
                                            readonly /></td>
                                </tr>
                                <tr id="bank_branch_row" style="display:none;">
                                    <td class="text-left font-weight-bold"><span>Bank Branch</span></td>
                                    <!-- <td><input type="text" id="bank_brname" name="bank_brname"
                                    class="form-control form-control-sm w-50" readonly /></td> -->
                                    <td class="text-left font-weight-bold">
                                        <select id="bank_branch" class="form-control form-control-sm w-50" name="bank_branch">

                                        </select>
                                        <script type="text/javascript">
                                            document.getElementById('bank_branch').value =
                                                '<?php echo htmlentities(isset($post_data_array['bank_branch']) && $post_data_array['bank_branch'] != '' ? $post_data_array['bank_branch'] : ''); ?>';
                                            //$("#bank_branch").trigger('change');
                                        </script>
                                    </td>
                                </tr>

                                <tr class="pay_mode_ecs" style="display:none;">
                                    <td class="text-left font-weight-bold"><span>ECS No</span></td>
                                    <td><input type="text" id="ecs_no" name="ecs_no"
                                            class="form-control form-control-sm w-50" /></td>
                                </tr>
                                <tr class="pay_mode_ecs" style="display:none;">
                                    <td class="text-left font-weight-bold"><span>ECS Date</span></td>
                                    <td><input type="text" id="ecs_date" name="ecs_date"
                                            class="form-control form-control-sm user_enter_date w-50" /></td>
                                </tr>
                                <!-- cheque details -->
                                <!-- Cheque_No -->
                                <tr class="pay_mode_cheque" style="display:none;">
                                    <td class="text-left font-weight-bold"><span>Cheque No</span></td>
                                    <td><input type="text" id="cheque_no" name="cheque_no"
                                            class="form-control form-control-sm w-50"
                                            value="<?php echo htmlspecialchars(isset($post_data_array['cheque_no']) && $post_data_array['cheque_no'] != '' ? $post_data_array['cheque_no'] : "") ?>"
                                            /></td>
                                </tr>
                                <!-- Cheque Date -->
                                <tr class="pay_mode_cheque" style="display:none;">
                                    <td class="text-left font-weight-bold"><span>Cheque Date</span></td>
                                    <td><input type="text" id="cheque_date" name="cheque_date"
                                            class="form-control form-control-sm user_enter_date w-50"
                                            value="<?php echo isset($post_data_array['cheque_date']) && $post_data_array['cheque_date'] != '' ? $post_data_array['cheque_date'] : '' ?>" />
                                    </td>
                                </tr>

                                <!-- Remitter Name and Address -->
                                <tr>
                                    <td class="text-left font-weight-bold"><span>Name and Address of Remitter</span></td>
                                    <td>
                                        <textarea id="remitter_name_address" name="remitter_name_address" rows="4" cols="50"
                                            class="form-control w-50 form-control-sm" value=""></textarea>
                                        <span>Max 250 Characters</span>
                                        <script>
                                            $("#remitter_name_address").val('<?php echo ((isset($post_data_array['remitter_name_address']) && $post_data_array['remitter_name_address'] != '') ? $post_data_array['remitter_name_address'] : "") ?>');
                                        </script>
                                    </td>
                                </tr>

                                <!-- Cash From -->
                                <tr id="cash_from_type_container">
                                    <td class="text-left font-weight-bold"><span>Cash From</span></td>
                                    <td>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="accounts" name="cash_from_type" value="Accounts"
                                                class="custom-control-input" <?php
                                                if (isset($post_data_array['cash_from_type']) && $post_data_array['cash_from_type'] != '' && $post_data_array['cash_from_type'] == 'Accounts') {
                                                    ?> checked <?php
                                                }
                                                ?>>
                                            <label class="custom-control-label" for="accounts">Accounts (Other than Tax
                                                Receipts)</label>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="collection" name="cash_from_type" value="Collection"
                                                class="custom-control-input" <?php
                                                if (isset($post_data_array['cash_from_type']) && $post_data_array['cash_from_type'] != '' && $post_data_array['cash_from_type'] == 'Collection') {
                                                    ?> checked <?php
                                                }
                                                ?>>
                                            <label class="custom-control-label" for="collection">Collection</label>
                                        </div>
                                    </td>

                                </tr>
                                <?php
                                if (isset($post_data_array['cash_from_type']) && $post_data_array['cash_from_type'] != '') { ?>
                                    <script>
                                        $(document).ready(function () {
                                            $(`input[name=cash_from_type][value='<?= $post_data_array['cash_from_type'] ?>']`).trigger('click');
                                        }
                                        )

                                    </script>
                                <?php
                                }
                                ?>

                                <tr id="cash_coll_date_row">
                                    <td class="text-left font-weight-bold"><span>Cash Collection Date</span></td>
                                    <td><input type="text" id="cash_coll_date" name="cash_coll_date"
                                            class="form-control form-control-sm user_enter_date w-50"
                                            value="<?php echo isset($post_data_array['cas_coll_date']) && $post_data_array['cas_coll_date'] != '' ? $post_data_array['cas_coll_date'] : "" ?>" />
                                    </td>
                                </tr>

                                <!-- Amount -->


                                <tr id="cash_coll_amt_row">
                                    <td class="text-left font-weight-bold"><span>Chalan Amount (Chalan Serial No.)</span></td>
                                    <td>
                                        <input type="hidden" id="coll_amount_hidden" name="amount_hidden"
                                            class="form-control form-control-sm w-50" value="<?php
                                            if (isset($post_data_array['cash_from_type']) && $post_data_array['cash_from_type'] != '' && $post_data_array['cash_from_type'] == 'Collection')
                                                echo $post_data_array['amount_hidden'];
                                            ?>" />
                                        <span id="amount"></span>
                                    </td>
                                </tr>
                                

                                <input type="hidden" id="acc_codes_hidden" name="acc_codes_hidden"
                                    class="form-control form-control-sm w-50"
                                    value="<?php echo ((isset($post_data_array['acc_codes_hidden']) && $post_data_array['acc_codes_hidden'] != '' && $post_data_array['acc_codes_hidden'] != '' ? $post_data_array['acc_codes_hidden'] : "")) ?>" />
                                <!-- Amount hidden -->
                                
                            </tbody>
                        </table>
                        <!-- First table end -->
                        <br />
                        <!-- Second table for account details -->
                        <div class="container">
                            <div class="row">
                              
                                <div class=col-md-6>
                                    <!-- credit  -->

                                    <table class="table table-bordered m-0 p-0 table-striped tndtp_form_table">
                                        <tr>
                                            <th align="center" scope="col"
                                                style="text-align:center;background-color:darkslateblue;color:white"
                                                colspan="12">Credit</th>
                                        </tr>
                                        <tr>
                                            <td class="text-left font-weight-bold"><span>Account Code & Head</span></td>
                                            <td scope="col">



                                                <select id="credit_bank_code" name="credit_bank_code"
                                                    class="form-control form-control-sm mb-2">
                                                    <option value="">Choose</option>
                                                    <?php
													$query = "
													SELECT 
														li.account_headid as account_head_id,
														acc_head.account_code,
														acc_head.account_head_name_en
													FROM 
													accounts_master.m_accounthead_link as li
													LEFT JOIN (
														SELECT 
															account_head_id,
															old_account_head_code AS account_code,
															account_head_name_en
														FROM 
															accounts_master.m_account_head
														WHERE 
															/*account_type_head_id = :account_type_head_id
															AND*/ isactive = :isactive
													) AS acc_head 
														ON acc_head.account_head_id = li.account_headid
													WHERE 
														/*li.lbcode = :lbcode
														AND li.dcode = :dcode
														AND */li.voucher_id=:voucher_id
														 AND li.isactive=1
														AND li.del_flag is null
														AND li.account_type_id=:account_type_id
														AND acc_head.account_head_id IS NOT NULL
												";

												   /* $res = $this->prepare($query, [
														":account_type_head_id"=>1,":isactive"=>1,":lbcode"=>$lbcode,":dcode"=>$dcode
													],2);
													*/
													 $sel_bank_newid_res = $this->prepare($query, [
														":isactive"=>1,":voucher_id"=>7,":account_type_id"=>1
													],2);
                                                    foreach ($sel_bank_newid_res as $row): ?>
                                                        <option value="<?= $row['account_head_id']; ?>"
                                                            data-code="<?= htmlentities($row['account_code']); ?>"
                                                            data-name="<?= htmlentities($row['account_head_name_en']); ?>">
                                                            <?= htmlentities($row['account_code'] . ' - ' . $row['account_head_name_en']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>



                                                <input type="hidden" id="credit_bank_head" name="credit_bank_head"
                                                    class="form-control form-control-sm number_field" />

                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-text-right font-weight-bold"><span
                                                    DisplayLabelID="483">Amount</span></td>
                                            <td scope="col">
                                                <input type="text" id="credit_amount" name="credit_amount"
                                                    class="form-control form-control-sm number_field" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-left font-weight-bold" colspan="2" align="center">
                                                <input type="button" id="btn_credit_add" name="btn_credit_add"
                                                    value="Add Credit"
                                                    class="btn btn-md text-white font-weight-bold btn-success" />
                                                <input type="hidden" id="credit_edit_id" name="credit_edit_id"
                                                    class="form-control form-control-sm number_field" value="" />
                                                <input type="hidden" id="credit_delete_id" name="credit_delete_id"
                                                    class="form-control form-control-sm number_field" value="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-left font-weight-bold"><span DisplayLabelID="483">Credit
                                                    Amount</span></td>
                                            <td scope="col">
                                                <span id="span_credit_total_amount"></span>
                                                <input type="hidden" id="credit_total_amount" name="credit_total_amount"
                                                    class="form-control form-control-sm number_field" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <table id="credit_table_result"
                                                    class="table table-bordered m-0 p-0 table-striped tndtp_form_table">
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
                                  <div class="col-md-6">
                                    <table class="table table-bordered m-0 p-0 table-striped tndtp_form_table">
                                        <tr>
                                            <th align="center" scope="col"
                                                style="text-align:center;background-color:darkslateblue;color:white"
                                                colspan="12">Debit</th>
                                        </tr>
                                        <tr>
                                            <td class="text-left font-weight-bold"><span>Account Code & Head</span></td>
                                            <td scope="col">

                                                <select id="debit_bank_code" name="debit_bank_code"
                                                    class="form-control form-control-sm mb-2">
                                                    <option value="">Choose</option>
                                                   <?php
													$query = "
													SELECT 
														li.account_headid as account_head_id,
														acc_head.account_code,
														acc_head.account_head_name_en
													FROM 
													accounts_master.m_accounthead_link as li
													LEFT JOIN (
														SELECT 
															account_head_id,
															old_account_head_code AS account_code,
															account_head_name_en
														FROM 
															accounts_master.m_account_head
														WHERE 
															/*account_type_head_id = :account_type_head_id
															AND*/ isactive = :isactive
													) AS acc_head 
														ON acc_head.account_head_id = li.account_headid
													WHERE 
														/*li.lbcode = :lbcode
														AND li.dcode = :dcode
														AND */li.voucher_id=:voucher_id
														 AND li.isactive=1
														AND li.del_flag is null
														AND li.account_type_id=:account_type_id
														AND acc_head.account_head_id IS NOT NULL
												";

												   /* $res = $this->prepare($query, [
														":account_type_head_id"=>1,":isactive"=>1,":lbcode"=>$lbcode,":dcode"=>$dcode
													],2);
													*/
													 $sel_bank_newid_res = $this->prepare($query, [
														":isactive"=>1,":voucher_id"=>7,":account_type_id"=>2
													],2);
                                                    //print_r()
                                                    foreach ($sel_bank_newid_res as $row): ?>
                                                        <option value="<?= $row['account_head_id']; ?>"
                                                            data-code="<?= htmlentities($row['account_code']); ?>"
                                                            data-name="<?= htmlentities($row['account_head_name_en']); ?>">
                                                            <?= htmlentities($row['account_code'] . ' - ' . $row['account_head_name_en']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>


                                                <input type="hidden" id="debit_bank_head" name="debit_bank_head"
                                                    class="form-control form-control-sm number_field" />


                                            </td>
                                        </tr>



                                        <tr id="debit_current_amount_tr">
                                            <td class="text-left font-weight-bold"><span DisplayLabelID="483">Amount</span></td>
                                            <td scope="col">
                                                <input type="text" id="debit_amount" name="debit_amount"
                                                    class="form-control form-control-sm number_field" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-left font-weight-bold" colspan="2" align="center">
                                                <input type="button" id="btn_debit_add" name="btn_debit_add" value="Add Debit"
                                                    class="btn btn-md text-white font-weight-bold btn-success" />
                                                <input type="hidden" name="debit_edit_id" value="" class="bank_id"
                                                    id="debit_edit_id" />
                                                <input type="hidden" name="debit_delete_id" value="" class="bank_id"
                                                    id="debit_delete_id" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right font-weight-bold"><span DisplayLabelID="483">Debit
                                                    Amount</span></td>
                                            <td scope="col">
                                                <span id="span_debit_total_amount"></span>
                                                <input type="hidden" id="debit_total_amount" name="debit_total_amount"
                                                    class="form-control form-control-sm number_field" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <table id="debit_table_result"
                                                    class="table table-bordered m-0 p-0 table-striped tndtp_form_table">
                                                    <thead>
                                                        <tr>
                                                            <td> Account Code </td>
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
                            </div>
                        </div>
                        <br />
                        <!-- Third table for Narration, Print and Save -->
                        <table class="table table-bordered m-0 p-0 tndtp_form_table">
                            <tbody>
                                <tr>
                                    <td class="text-left font-weight-bold"><span>Narration</span></td>
                                    <td>
                                        <textarea id="narration" name="narration" rows="4" cols="50"
                                            class="form-control w-50 form-control-sm" value=""></textarea>
                                        <span>Max 250 Characters</span>
                                    </td>
                                </tr>
                                <script>
                                    $("#narration").val("<?php echo isset($post_data_array['narration']) ? $post_data_array['narration'] : ''; ?>");
                                </script>

                                <!-- <tr>
                                    <td><span>Print</span></td>
                                    <td align="left">
                                        <input type="checkbox" id="print" name="print" value="1" checked />
                                    </td>
                                </tr> -->

                                <tr>
                                    <td colspan="2" align="center">
                                        <input type="submit" id="btn_save" name="btn_save" value="Save"
                                            class="btn btn-md text-white font-weight-bold btn-success" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- Third table end -->

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
        //print_r(['current_template'=>$this->getCurrentUserTemplate()]);
        $this->Template($this->getCurrentUserTemplate() != "" ? $this->getCurrentUserTemplate() : "Template1", "Adjust Triplicate Challan Form", $ob_output_main_contents, array(), array('page_id' => 12));
    }
    public function data_save($save_data)
    {   //print_r($save_data);die();
        // TOKEN VALIDATE
        if (!$this->validateToken($this->page_token, $save_data[$this->page_token])) {
            $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => $this->page_token,
                "MESSAGE" => "Invalid Token"
            ), $save_data));
            exit;
        } else {
            unset($_SESSION[$this->page_token]);
        }
        $statecode = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();
        $fin_year=$this->getFinYear();
        $cheque_no = $cheque_date = $bank_name = $bank_code = $dd_no = $dd_date = $ecs_no = $ecs_date = $tax_type = NULL;
        if (isset($save_data['rc_serial_no']) && $save_data['rc_serial_no'] != '') {
            $rc_serial_no = $save_data['rc_serial_no'];
        } else {
            $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "rc_serial_no",
                "MESSAGE" => 'Missing Serail Number'
            ), $save_data));
            exit;
        }
        if (isset($save_data['rc_date']) && $save_data['rc_date'] != '') {
            list($date_dateofreceived, $month_dateofreceived, $year_dateofreceived) = explode('-', $save_data['rc_date']);
            $rc_date = $year_dateofreceived . '-' . $month_dateofreceived . '-' . $date_dateofreceived;
            $rc_dateValidation = $this->Field_Validation(
                array(
                    'Field_Type' => 'date',
                    'Field_Value' => $save_data['rc_date'],
                    'Field_Name' => 'rc_date',
                    'Field_Format' => 'dd-mm-yyyy',
                    'Field_Label_Name' => 'Chalan Date',
                )
            );
            if ($rc_dateValidation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "rc_date",
                    "MESSAGE" => $rc_dateValidation['Message']
                ), $save_data));
                exit;
            }
        } else {
            $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "rc_date",
                "MESSAGE" => 'Invalid Challan Date'
            ), $save_data));
            exit;
        }
        if (isset($save_data['pay_mode']) && $save_data['pay_mode'] != '') {
            $pay_mode = $save_data['pay_mode'];
            $pay_modeValidation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $pay_mode,
                    'Field_Name' => 'pay_mode',
                    'Field_Max_length' => '2',
                    'Field_Label_Name' => 'Payment Mode',
                )
            );
            if ($pay_modeValidation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "pay_mode",
                    "MESSAGE" => $pay_modeValidation['Message']
                ), $save_data));
                exit;
            }

            $triplicate_chalan_query = "select paymentmode from accounts_master.t_triplicate_chalan_details where chalan_no=:chalan_no and del_flag is null and fin_year=:fin_year and dcode=:dcode and lbcode=:lbcode";
            $res = $this->prepare($triplicate_chalan_query, [":chalan_no" => $save_data['rc_serial_no'], ":fin_year" => $fin_year, ":dcode" => $dcode, ":lbcode" => $lbcode], 4);

            if($res['paymentmode']!=$save_data['pay_mode'])
            {
                $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "pay_mode",
                "MESSAGE" =>'Payment Mode Cannot be Changed.Delete this chalan and create a new one'
            ), $save_data));
            exit;
            }
        } else {
            $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "pay_mode",
                "MESSAGE" => 'Select Payment Mode'
            ), $save_data));
            exit;
        }
       

        if (isset($save_data['pay_mode']) && $save_data['pay_mode'] == "2") {
            //bank_branch
            if (isset($save_data['cheque_no']) && $save_data['cheque_no'] != '') {
                $cheque_no = $save_data['cheque_no'];
                $cheque_noValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text_number',
                        'Field_Value' => $cheque_no,
                        'Field_Name' => 'cheque_no',
                        'Field_Max_length' => '10',
                        'Field_Label_Name' => 'Cheque Number',
                    )
                );
                if ($cheque_noValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "cheque_no",
                        "MESSAGE" => $cheque_noValidation['Message']
                    ), $save_data));
                    exit;
                }
            } else {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "cheque_no",
                    "MESSAGE" => 'Enter Cheque Number'
                ), $save_data));
                exit;
            }
            if (isset($save_data['bank_branch']) && $save_data['bank_branch'] != '') {
                $bank_branch = $save_data['cheque_no'];
                $bank_branchValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text_number',
                        'Field_Value' => $bank_branch,
                        'Field_Name' => 'bank_branch',
                        'Field_Max_length' => '10',
                        'Field_Label_Name' => 'bank_branch',
                    )
                );
                if ($cheque_noValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "bank_branch",
                        "MESSAGE" => $bank_branchValidation['Message']
                    ), $save_data));
                    exit;
                }
            } else {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "bank_branch",
                    "MESSAGE" => 'Choose Bank Branch'
                ), $save_data));
                exit;
            }
            if (isset($save_data['cheque_date']) && $save_data['cheque_date'] != '') {
                list($date_dateofreceived, $month_dateofreceived, $year_dateofreceived) = explode('-', $save_data['cheque_date']);
                $cheque_date = $year_dateofreceived . '-' . $month_dateofreceived . '-' . $date_dateofreceived;
                $cheque_dateValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'date',
                        'Field_Value' => $save_data['cheque_date'],
                        'Field_Name' => 'cheque_date',
                        'Field_Format' => 'dd-mm-yyyy',
                        'Field_Label_Name' => 'Cheque Date',
                    )
                );
                if ($cheque_dateValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "cheque_date",
                        "MESSAGE" => $cheque_dateValidation['Message']
                    ), $save_data));
                    exit;
                }
            } else {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "cheque_date",
                    "MESSAGE" => 'Select Cheque Date'
                ), $save_data));
                exit;
            }
            $dd_no = $dd_date = $ecs_no = $ecs_date = NULL;
        }
        if (isset($save_data['pay_mode']) && $save_data['pay_mode'] == "3") {
            if (isset($save_data['dd_no']) && $save_data['dd_no'] != '') {
                $dd_no = $save_data['dd_no'];
                $dd_noValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text_number',
                        'Field_Value' => $dd_no,
                        'Field_Name' => 'dd_no',
                        'Field_Max_length' => '10',
                        'Field_Label_Name' => 'DD Number',
                    )
                );
                if ($dd_noValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "dd_no",
                        "MESSAGE" => $dd_noValidation['Message']
                    ), $save_data));
                    exit;
                }
            } else {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "dd_no",
                    "MESSAGE" => 'Enter DD Number'
                ), $save_data));
                exit;
            }
            if (isset($save_data['dd_date']) && $save_data['dd_date'] != '') {
                list($date_dateofreceived, $month_dateofreceived, $year_dateofreceived) = explode('-', $save_data['dd_date']);
                $dd_date = $year_dateofreceived . '-' . $month_dateofreceived . '-' . $date_dateofreceived;
                $dd_dateValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'date',
                        'Field_Value' => $save_data['dd_date'],
                        'Field_Name' => 'dd_date',
                        'Field_Format' => 'dd-mm-yyyy',
                        'Field_Label_Name' => 'DD Date',
                    )
                );
                if ($dd_dateValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "dd_date",
                        "MESSAGE" => $dd_dateValidation['Message']
                    ), $save_data));
                    exit;
                }
            } else {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "dd_date",
                    "MESSAGE" => 'Select DD Date'
                ), $save_data));
                exit;
            }
            $cheque_no = $cheque_date = $ecs_no = $ecs_date = NULL;
        }
        if (isset($save_data['pay_mode']) && $save_data['pay_mode'] == "4") {
            if (isset($save_data['ecs_no']) && $save_data['ecs_no'] != '') {
                $ecs_no = $save_data['ecs_no'];
                $ecs_noValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text_number',
                        'Field_Value' => $ecs_no,
                        'Field_Name' => 'ecs_no',
                        'Field_Max_length' => '10',
                        'Field_Label_Name' => 'ECS Number',
                    )
                );
                if ($ecs_noValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "ecs_no",
                        "MESSAGE" => $ecs_noValidation['Message']
                    ), $save_data));
                    exit;
                }
            } else {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "ecs_no",
                    "MESSAGE" => 'Enter ECS Number'
                ), $save_data));
                exit;
            }
            if (isset($save_data['ecs_date']) && $save_data['ecs_date'] != '') {
                list($date_dateofreceived, $month_dateofreceived, $year_dateofreceived) = explode('-', $save_data['ecs_date']);
                $ecs_date = $year_dateofreceived . '-' . $month_dateofreceived . '-' . $date_dateofreceived;
                $ecs_dateValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'date',
                        'Field_Value' => $save_data['ecs_date'],
                        'Field_Name' => 'ecs_date',
                        'Field_Format' => 'dd-mm-yyyy',
                        'Field_Label_Name' => 'ECS Date',
                    )
                );
                if ($ecs_dateValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "ecs_date",
                        "MESSAGE" => $ecs_dateValidation['Message']
                    ), $save_data));
                    exit;
                }
            } else {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "ecs_date",
                    "MESSAGE" => 'Select ECS Date'
                ), $save_data));
                exit;
            }
            $cheque_no = $cheque_date = $dd_no = $dd_date = NULL;
        }
        // if (isset($save_data['bank_code']) && $save_data['bank_code']!='') {
        // 	$bank_code = $save_data['bank_code'];
        // 	$bank_codeValidation = $this->Field_Validation(
        // 		array(
        // 			'Field_Type' => 'number',
        // 			'Field_Value' => $bank_code,
        // 			'Field_Name' => 'bank_code',
        // 			'Field_Max_length' => '60',
        // 			'Field_Label_Name' => 'Bank Code',
        // 		)
        // 	);
        // 	if ($bank_codeValidation['Status'] == "Error") {
        // 		$this->main_content(array_merge(array(
        // 			"STATUS" => "ERROR",
        // 			"STATUS_TYPE" => "FIELD",
        // 			"FIELD_NAME" => "bank_code",
        // 			"MESSAGE" => $bank_codeValidation['Message']
        // 		), $save_data));
        // 		exit;
        // 	}
        // }else{
        // 	$this->main_content(array_merge(array(
        // 		"STATUS" => "ERROR",
        // 		"STATUS_TYPE" => "FIELD",
        // 		"FIELD_NAME" => "bank_code",
        // 		"MESSAGE" => 'Select Bank Code'
        // 	), $save_data));
        // 	exit;
        // }
        if (isset($save_data['remitter_name_address']) && $save_data['remitter_name_address'] != '') {
            $remitter_name_address = $save_data['remitter_name_address'];
            $remitter_name_addressValidation = $this->Field_Validation(
                array(
                    'Field_Type' => 'text_space',
                    'Field_Value' => $remitter_name_address,
                    'Field_Name' => 'remitter_name_address',
                    'Field_Max_length' => '60',
                    'Field_Label_Name' => 'Name and Address of Remitter',
                )
            );
            if ($remitter_name_addressValidation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "remitter_name_address",
                    "MESSAGE" => $remitter_name_addressValidation['Message']
                ), $save_data));
                exit;
            }
        } else {
            $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "remitter_name_address",
                "MESSAGE" => 'Enter Name and Address of Remitter'
            ), $save_data));
            exit;
        }
        if (isset($save_data['cash_from_type']) && $save_data['cash_from_type'] != '') {
            $cash_from_type = $save_data['cash_from_type'];
            $cash_from_typeValidation = $this->Field_Validation(
                array(
                    'Field_Type' => 'text',
                    'Field_Value' => $cash_from_type,
                    'Field_Name' => 'cash_from_type',
                    'Field_Max_length' => '10',
                    'Field_Label_Name' => 'Cash From Type',
                )
            );
            if ($cash_from_typeValidation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "cash_from_type",
                    "MESSAGE" => $cash_from_typeValidation['Message']
                ), $save_data));
                exit;
            }
            if ($cash_from_type == "Collection") {
                if (isset($save_data['cash_coll_date']) && $save_data['cash_coll_date'] != '') {
                    list($date_dateofreceived, $month_dateofreceived, $year_dateofreceived) = explode('-', $save_data['cash_coll_date']);
                    $cash_coll_date = $year_dateofreceived . '-' . $month_dateofreceived . '-' . $date_dateofreceived;
                    $cash_coll_dateValidation = $this->Field_Validation(
                        array(
                            'Field_Type' => 'date',
                            'Field_Value' => $save_data['cash_coll_date'],
                            'Field_Name' => 'cash_coll_date',
                            'Field_Format' => 'dd-mm-yyyy',
                            'Field_Label_Name' => 'Cash Collection Date',
                        )
                    );
                    if ($cash_coll_dateValidation['Status'] == "Error") {
                        $this->main_content(array_merge(array(
                            "STATUS" => "ERROR",
                            "STATUS_TYPE" => "FIELD",
                            "FIELD_NAME" => "cash_coll_date",
                            "MESSAGE" => $cash_coll_dateValidation['Message']
                        ), $save_data));
                        exit;
                    }
                } else {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "cash_coll_date",
                        "MESSAGE" => 'Enter Cash Collection Date'
                    ), $save_data));
                    exit;
                }
            } else {
                $cash_coll_date = NULL;
            }
        } else {
            $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "cash_from_type",
                "MESSAGE" => 'Select Cash From Type'
            ), $save_data));
            exit;
        }
        // if (isset($save_data['account_code']) && $save_data['account_code']!='') {
        // 	$account_code = $save_data['account_code'];
        // 	$account_codeValidation = $this->Field_Validation(
        // 		array(
        // 			'Field_Type' => 'number',
        // 			'Field_Value' => $account_code,
        // 			'Field_Name' => 'account_code',
        // 			'Field_Max_length' => '60',
        // 			'Field_Label_Name' => 'Invalid Account Code',
        // 		)
        // 	);
        // 	if ($account_codeValidation['Status'] == "Error") {
        // 		$this->main_content(array_merge(array(
        // 			"STATUS" => "ERROR",
        // 			"STATUS_TYPE" => "FIELD",
        // 			"FIELD_NAME" => "account_code",
        // 			"MESSAGE" => $account_codeValidation['Message']
        // 		), $save_data));
        // 		exit;
        // 	}
        // }else{
        // 	$this->main_content(array_merge(array(
        // 		"STATUS" => "ERROR",
        // 		"STATUS_TYPE" => "FIELD",
        // 		"FIELD_NAME" => "account_code",
        // 		"MESSAGE" => 'Select Account Code'
        // 	), $save_data));
        // 	exit;
        // }
        // if (isset($save_data['amount_hidden']) && $save_data['amount_hidden']!='') {
        // 	$amount = $save_data['amount_hidden'];
        // 	$amountValidation = $this->Field_Validation(
        // 		array(
        // 			'Field_Type' => 'number',
        // 			'Field_Value' => $amount,
        // 			'Field_Name' => 'amount',
        // 			//'Field_Max_length'=>'30',
        // 			'Field_Label_Name' => 'Invalid Amount',
        // 		)
        // 	);
        // 	if ($amountValidation['Status'] == "Error") {
        // 		$this->main_content(array_merge(array(
        // 			"STATUS" => "ERROR",
        // 			"STATUS_TYPE" => "FIELD",
        // 			"FIELD_NAME" => "amount",
        // 			"MESSAGE" => $amountValidation['Message']
        // 		), $save_data));
        // 		exit;
        // 	}
        // }else{
        // 	$this->main_content(array_merge(array(
        // 		"STATUS" => "ERROR",
        // 		"STATUS_TYPE" => "FIELD",
        // 		"FIELD_NAME" => "amount",
        // 		"MESSAGE" => 'Enter Amount'
        // 	), $save_data));
        // 	exit;
        // }
        //$amount = $save_data['amount_total'];
        //$account_code = $save_data['acc_codes_hidden'];

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
        if($credit_amount!=$debit_amount)
        {   
             $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "",
                "MESSAGE" => 'Total Credit Amount and Total Debit Amount is not equal'
            ), $save_data));
            exit;
        }

        if (isset($save_data['narration']) && $save_data['narration'] != '') {
            $narration = $save_data['narration'];
            $narrationValidation = $this->Field_Validation(
                array(
                    'Field_Type' => 'text_space',
                    'Field_Value' => $narration,
                    'Field_Name' => 'narration',
                    'Field_Max_length' => '60',
                    'Field_Label_Name' => 'Invalid Narration',
                )
            );
            if ($narrationValidation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "narration",
                    "MESSAGE" => $narrationValidation['Message']
                ), $save_data));
                exit;
            }
        } else {
            $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "narration",
                "MESSAGE" => 'Enter Narration'
            ), $save_data));
            exit;
        }

        //code for adding debit account breakup when paymode is cash:

         if (isset($save_data['pay_mode']) && $save_data['pay_mode'] == "1") {

                $fin_year=$this->getFinYear();
                $dcode=$this->getCurrentDistrictCode();$lbcode=$this->getCurrentLocalBodyCode();$user_name=$this->getCurrentUser();$ip_address=$this->getIPAddress();
                $save_query = "SELECT accounts_master.sp_triplicate_accounthead_breakup(:dcode,:lbcode,:acc_type,:acc_code,:debit_acc_head,:amount,:cash_from_type, :rc_serial_no,:challan_date,1,:getCurrentUser,:getIpAddress,:edit_id,:delete_id,:fin_year);";
                
            $res1 = $this->prepare($save_query, [
                ":acc_type" => 2,
                ":acc_code" => 2,//static account_head_id , taken from local db , change this value if needed
                ":debit_acc_head" => 'General Account',//static account_head_name , change this value if needed
                ":amount" => $credit_amount,
                ":fin_year" => $fin_year,
                ":rc_serial_no" => $rc_serial_no,
                ":dcode" => $dcode,
                ":cash_from_type"=>$cash_from_type,
                ":challan_date"=>$rc_date,
                ":lbcode" => $lbcode,
                ":getCurrentUser" => $user_name,
                ":getIpAddress" => $ip_address,
                ":edit_id" => $save_data['debit_breakup_id'],
                ":delete_id" => 0
            ], 4);

        }



        $Result_Message = "Data Saved SuccessFully";
        $this->beginTransaction();
        $site_data = $this->siteData();
        $pp_assessment_initiation = "accounts_master.sp_adjust_triplicate_chalan";
        $edit_id = $del_id = 0;
        $user_name = $this->getCurrentUser();
        $ip_address = $this->getIpAddress();
        $fin_year = $this->getFinYear();
        if (isset($save_data['bank_branch'])) {
            $bank_branch_id = $save_data['bank_branch'];
        } else {
            $bank_branch_id = null;
        }
        if (isset($save_data['bank_code'])) {
            $bank_code = (int) $save_data['bank_code'];
        } else {
            $bank_code = NULL;
        }
        //final_save_query
        $save_query = "select * from " . $pp_assessment_initiation . "( :dcode, :lbcode, :rc_serial_no,:rc_date, :cash_from_type, :cash_coll_date, :tax_type, :bank_code,:bank_branch_id, :cheque_no, :cheque_date, :ecs_no, :ecs_date, :dd_no, :dd_date, :pay_mode, :remitter_name_address, :narration, :isactive, :user_name, :ip_address, :edit_id, :del_id, :fin_year,:total_amount,:credit_tot_amount,:debit_tot_amount)";
        $res1 = $this->prepare($save_query, array(":dcode" => $dcode, ":lbcode" => $lbcode, ":rc_serial_no" => $rc_serial_no, ":rc_date" => $rc_date, ":pay_mode" => $pay_mode, ":tax_type" => $tax_type, ":cheque_no" => $cheque_no, ":cheque_date" => $cheque_date, ":bank_code" => $bank_code, ":bank_branch_id" => $bank_branch_id, ":dd_no" => $dd_no, ":dd_date" => $dd_date, ":ecs_no" => $ecs_no, ":fin_year" => $fin_year, ":ecs_date" => $ecs_date, ":remitter_name_address" => $remitter_name_address, ":cash_from_type" => $cash_from_type, ":cash_coll_date" => $cash_coll_date, ":narration" => $narration, ":isactive" => 1, ":user_name" => $user_name, ":ip_address" => $ip_address, ":edit_id" => 1, ":del_id" => 0,":total_amount"=>$credit_amount,":credit_tot_amount"=>$credit_amount,":debit_tot_amount"=>$debit_amount), 4);

        if (!isset($res1->errorInfo)) {
            $inserted_id = $res1['sp_adjust_triplicate_chalan'];

            $latest_date_query = "update accounts_master.t_triplicate_accounthead_breakup set challan_id=:inserted_id   WHERE del_flag IS NULL and triplicate_chalan_no=:triplicate_chalan_no and dcode=:dcode and lbcode=:lbcode";
            $sel_dname_res = $this->prepare($latest_date_query, array(":triplicate_chalan_no" => $rc_serial_no, ":dcode" => $dcode, ":lbcode" => $lbcode, ":inserted_id" => $inserted_id), 4);


            $this->commit();

            if (isset($save_data['print']) && $save_data['print'] != '') {
                ?>
                <script>
                    alert("Data Saved SuccessFully");
                </script>
                <?php
                header("Location: " . $site_data->website_url . "/project/forms/masters/triplicate.php?id=" . base64_encode($res1['sp_adjust_triplicate_chalan']));
                exit();
            } else {
                $this->main_content(array(
                    "STATUS" => "SUCCESS",
                    "STATUS_TYPE" => "FORM",
                    "MESSAGE" => $Result_Message
                ));
                exit;
            }
        } else {
            $this->rollback();
            /*
             */
            $this->main_content(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Data Save Failed Due To Duplicate Entry"
            ));
            exit;
        }
    }
}
$propertyassessment = new Adjust_Triplicate_Chalan();
if (!isset($_POST['cmd'])) {
    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        //print_r($_POST);die();
        $propertyassessment->data_save(array_merge($_POST, $_GET));
    } else {
        $propertyassessment->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
    }
} else if (isset($_POST['cmd'])) {
    $cmd = base64_decode($_POST['cmd']);
    if ($cmd == 1) {
        if (isset($_POST['type']) && $_POST['type'] != '') {
            $type = base64_decode($_POST['type']);
            $type_validation = $propertyassessment->Field_Validation(
                array(
                    'Field_Type' => 'text',
                    'Field_Value' => $type,
                    'Field_Name' => 'account_type',
                    'Field_Max_length' => '60',
                    'Field_Label_Name' => 'Account Type',
                )
            );
            if ($type_validation['Status'] == "Error") {
                echo json_encode(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "account_type",
                    "MESSAGE" => $type_validation['Message']
                ), $_POST));
                exit;
            }
        } else {
            echo json_encode(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "account_type",
                "MESSAGE" => "Select Account Type"
            ), $_POST));
            exit;
        }
        if ($type == 'Accounts') {
            $account_type = 1;
        } else {
            $account_type = 2;
        }
        ?>
            <option value="" DisplayLabelID="255">Choose</option>
            <?php
            $sel_dname = "SELECT account_head_id, new_account_head_code,old_account_head_code,account_head_name_en,account_head_name_ta,isactive, account_type_head_id FROM accounts_master.m_account_head where del_flag is null /*and account_type_head_id=:account_type*/ and isactive=:isactrive;";
            $sel_dname_res = $propertyassessment->prepare($sel_dname, array(/* ":account_type"=>$account_type,*/ ":isactrive" => 1), 2);
            foreach ($sel_dname_res as $sel_dname_key => $sel_dname_row) {
                ?>
                <option value="<?php echo htmlentities($sel_dname_row['account_head_id']); ?>">
                <?php echo htmlentities($sel_dname_row['old_account_head_code'] . " - " . $sel_dname_row['account_head_name_en']); ?>
                </option>
            <?php
            }
            exit;
    }
    if ($cmd == 3) {
        $dcode = $propertyassessment->getCurrentDistrictCode();
        $lbcode = $propertyassessment->getCurrentLocalBodyCode();

        list($date_dateofreceived, $month_dateofreceived, $year_dateofreceived) = explode('-', base64_decode($_POST['chalan_date']));
        $chalan_date = $year_dateofreceived . '-' . $month_dateofreceived . '-' . $date_dateofreceived;


        $bpv_check_query = "SELECT COUNT(*) as cnt FROM accounts_master.t_bank_receipt_voucher 
                    WHERE del_flag IS NULL AND dcode = :dcode AND lbcode = :lbcode 
                    AND brv_date = :chalan_date";

        $bpv_check_res = $propertyassessment->prepare($bpv_check_query, array(
            ":dcode" => $dcode,
            ":lbcode" => $lbcode,
            ":chalan_date" => $chalan_date,
        ), 4);

        if ($bpv_check_res['cnt'] > 0) {
            echo json_encode(array(
                "STATUS" => "ERROR",
                "MESSAGE" => "You cannot add new entries for $chalan_date. BRV has already been processed."
            ));
            exit;
        }
        ?>
            <?php
            $latest_date_query = "SELECT chalan_date 
        FROM accounts_master.t_triplicate_chalan_details 
        WHERE del_flag IS NULL  and dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year
        ORDER BY chalan_date DESC 
        LIMIT 1";
            $sel_dname_res = $propertyassessment->prepare($latest_date_query, array(":dcode" => $dcode, ":lbcode" => $lbcode, ":fin_year" => $propertyassessment->getFinYear()), 4);
            $chalan_date_raw = base64_decode($_POST['chalan_date']);

            $chalan_date = DateTime::createFromFormat('d-m-Y', $chalan_date_raw)->format('Y-m-d');

            $latest_date = date('Y-m-d', strtotime($sel_dname_res['chalan_date']));

            if (strtotime($chalan_date) < strtotime($latest_date)) {
                $display_date = date('d-m-Y', strtotime($latest_date));
                echo json_encode(array(
                    "STATUS" => "ERROR",
                    "MESSAGE" => "You cannot select a past skipped date. The last Chalan Entered date is: $display_date."
                ));
                exit;
            } else {
                echo json_encode(["STATUS" => "SUCCESS"]);
            }
    }
    if ($cmd == 4) {
        $accountCode = base64_decode($_POST['account_code']);

        $accountAmount = base64_decode($_POST['account_amount']);
        $cash_from_type = base64_decode($_POST['cash_from_type']);
        $rc_serial_no = base64_decode($_POST['rc_serial_no']);
        list($date_dateofreceived, $month_dateofreceived, $year_dateofreceived) = explode('-', base64_decode($_POST['rc_date']));
        $rc_date = $year_dateofreceived . '-' . $month_dateofreceived . '-' . $date_dateofreceived;
        $user_name = $propertyassessment->getCurrentUser();
        $ip_address = $propertyassessment->getIpAddress();
        $edit_id = isset($_POST["edit_id"]) ? base64_decode($_POST["edit_id"]) : 0;
        $del_id = isset($_POST["del_id"]) ? base64_decode($_POST["del_id"]) : 0;
        $dcode = $propertyassessment->getCurrentDistrictCode();
        $lbcode = $propertyassessment->getCurrentLocalBodyCode();
        if (!isset($_POST["edit_id"])) {
            $latest_date_query = "SELECT count(*) as cnt FROM accounts_master.t_triplicate_accounthead_breakup WHERE del_flag IS NULL and triplicate_chalan_no=:triplicate_chalan_no and acc_code=:account_code and dcode=:dcode and lbcode=:lbcode";
            $sel_dname_res = $propertyassessment->prepare($latest_date_query, array(":triplicate_chalan_no" => $rc_serial_no, ":account_code" => $accountCode, ":dcode" => $dcode, ":lbcode" => $lbcode), 4);
            //print_r($sel_dname_res);die();
            if ($sel_dname_res['cnt'] > 0) {
                echo json_encode([
                    "status" => "duplicate",
                    "message" => "Account Head already exists and cannot be added twice."
                ]);
                exit;
            }

        }


        $pp_assessment_initiation = "accounts_master.sp_triplicate_accounthead_breakup";
        ?>
            <?php
            $fin_year = $propertyassessment->getFinYear();
            $save_query = "SELECT * FROM " . $pp_assessment_initiation . "(:statecode, :dcode, :lbcode, :acc_code, :acc_amount, :cash_from_type, :rc_serial_no,:rc_date, :isactive, :user_name, :ip_address, :edit_id, :del_id, :fin_year)";

            $res1 = $propertyassessment->prepare($save_query, array(
                ":statecode" => 33,
                ":dcode" => $dcode,
                ":lbcode" => $lbcode,
                ":acc_code" => $accountCode,
                ":acc_amount" => $accountAmount,
                ":cash_from_type" => $cash_from_type,
                ":rc_serial_no" => $rc_serial_no,
                ":rc_date" => $rc_date,
                ":isactive" => 1,
                ":user_name" => $user_name,
                ":ip_address" => $ip_address,
                ":edit_id" => $edit_id,
                ":del_id" => $del_id,
                ":fin_year" => $fin_year
            ), 4);
            if (!empty($res1)) {

                $decoded = json_decode($res1['sp_triplicate_accounthead_breakup'], true);
                $triplicate_chalan_no = $decoded['accounthead_breakup_id']['triplicate_chalan_no'];



                $latest_date_query = "SELECT accounthead_breakup_id,acc_amount,acc_code FROM accounts_master.t_triplicate_accounthead_breakup WHERE del_flag IS NULL and triplicate_chalan_no=:triplicate_chalan_no and dcode=:dcode and lbcode=:lbcode";
                $sel_dname_res = $propertyassessment->prepare($latest_date_query, array(":triplicate_chalan_no" => $triplicate_chalan_no, ":dcode" => $dcode, ":lbcode" => $lbcode), 2);
                $output = '';
                $total_amount = 0;
                $acc_codes = [];
                foreach ($sel_dname_res as $sel_dname_key => $sel_dname_row) {
                    $acc_code = $sel_dname_row['acc_code'];

                    $latest_date_query = "SELECT account_head_id,account_head_name_en,old_account_head_code FROM accounts_master.m_account_head WHERE del_flag IS NULL and account_head_id=:accountCode_id";
                    $acc_data = $propertyassessment->prepare($latest_date_query, array(":accountCode_id" => $acc_code), 4);

                    $account_head_name_en = $acc_data['account_head_name_en'];
                    $account_head_code = $acc_data['old_account_head_code'];
                    $acc_amount = $sel_dname_row['acc_amount'];
                    $total_amount += $acc_amount;
                    $acc_codes[] = $acc_code;
                    $output .= "<tr>
                    <td>{$account_head_code}</td>
                    <td>{$account_head_name_en}</td>
                    <td>{$acc_amount}</td>
                    <td><button type='button' class='btn-edit' name='btn_edit' id='btn_edit' value='{$sel_dname_row['accounthead_breakup_id']}'>Edit</button></td>
                    <td><button type='button' class='btn-del' name='btn_del' id='btn_del' value='{$sel_dname_row['accounthead_breakup_id']}'>Delete</button></td>
                </tr>";
                }
                $output .= "<tr>
    <td colspan='2'><strong>Total</strong></td>
    <td><strong>{$total_amount}</strong></td>
    <td colspan='2'></td>
        </tr>";
                $acc_codes_json = json_encode($acc_codes);
                echo json_encode([
                    "status" => "success",
                    "message" => "Account Head added successfully.",
                    "total_amount" => $total_amount,
                    "acc_codes" => $acc_codes_json,
                    "html" => $output
                ]);
                exit;
            }
    }

    if ($cmd == 5) {
        $accounthead_breakup_id = base64_decode($_POST['account_head_id']);
        $dcode = $propertyassessment->getCurrentDistrictCode();
        $lbcode = $propertyassessment->getCurrentLocalBodyCode();

        ?>
            <?php
            $latest_date_query = "SELECT acc_code,acc_amount,accounthead_breakup_id FROM accounts_master.t_triplicate_accounthead_breakup WHERE del_flag IS NULL and accounthead_breakup_id=:accounthead_breakup_id and dcode=:dcode and lbcode=:lbcode";
            $sel_dname_res = $propertyassessment->prepare($latest_date_query, array(":accounthead_breakup_id" => $accounthead_breakup_id, ":dcode" => $dcode, ":lbcode" => $lbcode), 2);
            if (!empty($sel_dname_res)) {
                $acc_code = $sel_dname_res['acc_code'];
                $acc_amount = $sel_dname_res['acc_amount'];
                $accounthead_breakup_id = $sel_dname_res['accounthead_breakup_id'];
                echo json_encode([
                    'STATUS' => 'success',
                    'accounthead_breakup_id' => $accounthead_breakup_id,
                    'account_head_code' => $acc_code,
                    'account_amount' => $acc_amount,
                ]);
                exit;
            } else {
                echo json_encode([
                    'STATUS' => 'fail',
                    'message' => 'No data returned'
                ]);
                exit;
            }


    }
    if ($cmd == 6) {
        $dcode = $propertyassessment->getCurrentDistrictCode();
        $lbcode = $propertyassessment->getCurrentLocalBodyCode();
        //print_r('asda');die;

        list($date_dateofreceived, $month_dateofreceived, $year_dateofreceived) = explode('-', base64_decode($_POST['chalan_date']));
        $chalan_date = $year_dateofreceived . '-' . $month_dateofreceived . '-' . $date_dateofreceived;

        $user_name = $propertyassessment->getCurrentUser();
        $ip_address = $propertyassessment->getIpAddress();
        $del_id = isset($_POST["del_id"]) ? base64_decode($_POST["del_id"]) : 0;
        $pp_assessment_initiation = "accounts_master.sp_triplicate_accounthead_breakup";
        ?>
            <?php
            $save_query = "SELECT * FROM " . $pp_assessment_initiation . "(:statecode, :dcode, :lbcode, :acc_code, :acc_amount, :cash_from_type, :rc_serial_no,:rc_date, :isactive, :user_name, :ip_address, :edit_id, :del_id,:fin_year)";
            $fin_year = $propertyassessment->getFinYear();
            $res1 = $propertyassessment->prepare($save_query, array(
                ":statecode" => 33,
                ":dcode" => $dcode,
                ":lbcode" => $lbcode,
                ":acc_code" => '',
                ":acc_amount" => 0,
                ":cash_from_type" => '',
                ":rc_serial_no" => '',
                ":rc_date" => $chalan_date,
                ":isactive" => 1,
                ":user_name" => $user_name,
                ":ip_address" => $ip_address,
                ":edit_id" => 0,
                ":del_id" => (int) $del_id,
                ":fin_year" => $fin_year
            ), 4);
            if (!empty($res1)) {

                $decoded = json_decode($res1['sp_triplicate_accounthead_breakup'], true);
                //print_r($decoded);die;
                $triplicate_chalan_no = $decoded['accounthead_breakup_id']['triplicate_chalan_no'];
                //$triplicate_chalan_no=base64_decode($_POST['chalan_no']);
    

                $latest_date_query = "SELECT accounthead_breakup_id,acc_amount,acc_code FROM accounts_master.t_triplicate_accounthead_breakup WHERE del_flag IS NULL and triplicate_chalan_no=:triplicate_chalan_no and dcode=:dcode and lbcode=:lbcode ";
                $sel_dname_res = $propertyassessment->prepare($latest_date_query, array(":triplicate_chalan_no" => $triplicate_chalan_no, ":dcode" => $dcode, ":lbcode" => $lbcode), 2);
                $output = '';
                $total_amount = 0;
                $acc_codes = [];
                foreach ($sel_dname_res as $sel_dname_key => $sel_dname_row) {
                    $acc_code = $sel_dname_row['acc_code'];
                    $acc_amount = $sel_dname_row['acc_amount'];
                    $total_amount += $acc_amount;
                    $acc_codes[] = $acc_code;
                    $latest_date_query = "SELECT account_head_id,account_head_name_en,old_account_head_code FROM accounts_master.m_account_head WHERE del_flag IS NULL and account_head_id=:accountCode_id";
                    $acc_data = $propertyassessment->prepare($latest_date_query, array(":accountCode_id" => $acc_code), 4);

                    $account_head_name_en = $acc_data['account_head_name_en'];
                    $account_head_code = $acc_data['old_account_head_code'];

                    $output .= "<tr>
                                    <td>{$account_head_code}</td>
                                    <td>{$account_head_name_en}</td>
                                    <td>{$sel_dname_row['acc_amount']}</td>
                                    <td><button type='button' class='btn-edit' name='btn_edit' id='btn_edit' value='{$sel_dname_row['accounthead_breakup_id']}'>Edit</button></td>
                                    <td><button type='button' class='btn-del' name='btn_del' id='btn_del' value='{$sel_dname_row['accounthead_breakup_id']}'>Delete</button></td>
                                </tr>";
                }
                $output .= "<tr>
                            <td colspan='2'><strong>Total</strong></td>
                            <td><strong>{$total_amount}</strong></td>
                            <td colspan='2'></td>
                                </tr>";
                $acc_codes_json = json_encode($acc_codes);
                echo json_encode([
                    "status" => "success",
                    "message" => "Account Deleted successfully.",
                    "total_amount" => $total_amount,
                    "acc_codes" => $acc_codes_json,
                    "html" => $output
                ]);
                exit;
            }
    }
    /*
    if ($cmd == 7) {
        $rc_serial_no = base64_decode($_POST['rc_serial_no']);
        $dcode = $propertyassessment->getCurrentDistrictCode();
        $lbcode = $propertyassessment->getCurrentLocalBodyCode();
        ?>
            <?php
            $save_query = "delete from  accounts_master.t_triplicate_accounthead_breakup where triplicate_chalan_no=:triplicate_chalan_no and dcode=:dcode and lbcode=:lbcode and del_flag is null and challan_id is null ";

            $res1 = $propertyassessment->prepare($save_query, array(
                ":triplicate_chalan_no" => $rc_serial_no,
                ":dcode" => $dcode,
                ":lbcode" => $lbcode
            ), 7);
            exit;
    }
            */
    if ($cmd == 8) {
        $bank_id = base64_decode($_POST["bank_code"]);
        $dcode = $propertyassessment->getCurrentDistrictCode();
        $lbcode = $propertyassessment->getCurrentLocalBodyCode();
        //echo ($bank_code);


        $get_branch_list_query = "select bankbranch_id as branch_id ,bankbranch_name as branch_name from accounts_master.m_bankbranch where bank_id=:bank_id and lbcode=:lbcode and dcode=:dcode and del_flag is null and isactive=1;";
        $bank_branch_list = $propertyassessment->prepare($get_branch_list_query, array(
            ":bank_id" => $bank_id,
            ":lbcode" => $lbcode,
            ":dcode" => $dcode
        ), 2);
        $branch_list = [];
        foreach ($bank_branch_list as $key => $val) {
            $branch_list[] = ["branch_id" => $val["branch_id"], "branch_name" => $val["branch_name"]];
        }

        echo json_encode($bank_branch_list);
    }
    if ($cmd == 9) {
        $bank_branch_id = base64_decode($_POST["bank_branch_id"]);
        $bank_id = base64_decode($_POST["bank_id"]);

        $query = "select cheque_number from accounts_master.t_bank_cheque_leaves where bank_branch_id=:branch_id and bank_id=:bank_id and isused='N' and del_flag is null order by cheque_number LIMIT 1;";


        $res = $propertyassessment->prepare($query, array(
            ":bank_id" => $bank_id,
            ":branch_id" => $bank_branch_id
        ), 4);
        //print_r($res);

        //echo ($res['cheque_number']);
        /*

        print_r($res);
        die();
        */
        if (count($res) > 0) {
            echo json_encode($res['cheque_number']);
        } else {
            echo json_encode('-');
        }
    }
    if ($cmd == 10) {
        $type = base64_decode($_POST['type']);
        $collection_date = base64_decode($_POST['collection_date']);
        $collection_date = date('Y-m-d', strtotime($collection_date));
        $pay_mode = base64_decode($_POST['pay_mode']);
        $query = 'select count(*) as "count" from accounts_master.t_triplicate_chalan_details where  collectiontype=:type and paymentmode=:pay_mode and collectiondate=:collection_date';
        $res = $propertyassessment->prepare($query, [":collection_date" => $collection_date, ":type" => $type, ":pay_mode" => $pay_mode], 4);
        //print_r($res);die();

        if ($res["count"] == 0) {
            echo json_encode(['STATUS' => 'SUCCESS']);
        } else {
            echo json_encode(['STATUS' => 'FAILED']);
        }
    }

if($cmd == 11)
{
        $Result=array();
        $id = base64_decode($_POST['id']);
        $account_type = base64_decode($_POST['account_type']);
        $dcode = $propertyassessment->getCurrentDistrictCode();
        $lbcode = $propertyassessment->getCurrentLocalBodyCode();
		$sel_qry = "select accounthead_breakup_id, debit_account_id, debit_account_head, debit_amount, credit_amount from accounts_master.t_triplicate_accounthead_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and account_type=:account_type and accounthead_breakup_id=:accounthead_breakupid;";		
		$sel_qry_res=$propertyassessment->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":account_type"=>$account_type, ":accounthead_breakupid"=>$id),4);
        $Result['STATUS'] = 'SUCCESS';
        $Result['bank_code'] = $sel_qry_res['debit_account_id'];
        $Result['bank_head'] = $sel_qry_res['debit_account_head'];
        $Result['debit_amount'] = $sel_qry_res['debit_amount'];  
        $Result['credit_amount'] = $sel_qry_res['credit_amount'];         
        $Result['accounthead_breakup_id'] = $sel_qry_res['accounthead_breakup_id'];
        echo json_encode($Result);
        exit;
}
    
if ($cmd == 12) {
        $rc_serial_no = base64_decode($_POST['rc_serial_no']);
        $bank_code = base64_decode($_POST['bank_code']);
        $bank_head = base64_decode($_POST['bank_head']);
        $debit_edit_id = isset($_POST['edit_id']) && $_POST['edit_id'] != '' ? base64_decode($_POST['edit_id']) : 0;
        $debit_delete_id = isset($_POST['delete_id']) && $_POST['delete_id'] != '' ? base64_decode($_POST['delete_id']) : 0;
        $amount = base64_decode($_POST['amount']);
        $dcode = $propertyassessment->getCurrentDistrictCode();
        $lbcode = $propertyassessment->getCurrentLocalBodyCode();
        $user_name = $propertyassessment->getCurrentUser();
        $ip_address = $propertyassessment->getIpAddress();
        $fin_year = $propertyassessment->getFinYear();
        $propertyassessment->beginTransaction();
        $raw_challan_date=base64_decode($_POST['challan_date']);
        $date_obj = DateTime::createFromFormat('d-m-Y', $raw_challan_date);
        $challan_date=$date_obj->format('Y-m-d');
        
        $cash_from_type=base64_decode($_POST['cash_from_type']);
        
            // $save_query = "select * from accounts_master.sp_Purchase_Journal_Vouchers_breakup(:dcode, :lbcode, :account_type,:bank_code, :bank_head, :debit_amount, :credit_amount, :fin_year, :pjv_serial_no, :user_name, :ip_address, :edit_id, :del_id)";
            // $res1 = $AdjustBankReceiptVoucher->prepare($save_query, array(":dcode" => $dcode, ":lbcode" => $lbcode, ":account_type" => 1, ":bank_code" => $bank_code, ":bank_head" => $bank_head, ":debit_amount" => $amount, ":credit_amount"=>NULL, ":user_name" => $user_name, ":ip_address" => $ip_address, ":edit_id" => $debit_edit_id, ":del_id" => $debit_delete_id, ":fin_year"=>$fin_year, ":pjv_serial_no" =>$pjv_serial_no),4);

            $save_query = "SELECT accounts_master.sp_triplicate_accounthead_breakup(:dcode,:lbcode,:acc_type,:acc_code,:debit_acc_head,:amount,:cash_from_type, :rc_serial_no,:challan_date,1,:getCurrentUser,:getIpAddress,:edit_id,:delete_id,:fin_year);";
            $res1 = $propertyassessment->prepare($save_query, [
                ":acc_type" => 2,
                ":acc_code" => $bank_code,
                ":debit_acc_head" => $bank_head,
                ":amount" => $amount,
                ":fin_year" => $fin_year,
                ":rc_serial_no" => $rc_serial_no,
                ":dcode" => $dcode,
                ":cash_from_type"=>$cash_from_type,
                ":challan_date"=>$challan_date,
                ":lbcode" => $lbcode,
                ":getCurrentUser" => $user_name,
                ":getIpAddress" => $ip_address,
                ":edit_id" => $debit_edit_id,
                ":delete_id" => $debit_delete_id
            ], 4);


            


/*
        else if ($debit_delete_id == 0 && $debit_edit_id != 0) {
            $save_query = "SELECT accounts_master.sp_pj_voucher_breakup(:acc_type,:acc_code,:debit_acc_head,:amount, :fin_year, :pjv_serial_no,:dcode,:lbcode,:statecode,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
            $res1 = $AdjustBankReceiptVoucher->prepare($save_query, [
                ":acc_type" => 2,
                ":acc_code" => $bank_code,
                ":debit_acc_head" => $bank_head,
                ":amount" => $amount,
                ":fin_year" => $fin_year,
                ":pjv_serial_no" => $pjv_serial_no,
                ":dcode" => $dcode,
                ":lbcode" => $lbcode,
                ":statecode" => 33,
                ":getCurrentUser" => $user_name,
                ":getIpAddress" => $ip_address,
                ":edit_id" => $debit_edit_id,
                ":delete_id" => $debit_delete_id
            ], 4);
        }
        if ($debit_delete_id != 0 && $debit_edit_id == 0) {
            $save_query = "SELECT accounts_master.sp_pj_voucher_breakup(:acc_type,:acc_code,:debit_acc_head,:amount, :fin_year, :pjv_serial_no,:dcode,:lbcode,:statecode,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
            $res1 = $AdjustBankReceiptVoucher->prepare($save_query, [
                ":acc_type" => 2,
                ":acc_code" => $bank_code,
                ":debit_acc_head" => $bank_head,
                ":amount" => $amount,
                ":fin_year" => $fin_year,
                ":pjv_serial_no" => $pjv_serial_no,
                ":dcode" => $dcode,
                ":lbcode" => $lbcode,
                ":statecode" => 33,
                ":getCurrentUser" => $user_name,
                ":getIpAddress" => $ip_address,
                ":edit_id" => $debit_edit_id,
                ":delete_id" => $debit_delete_id
            ], 4);
        }
*/
        $sel_qry = "
SELECT 
    accounthead_breakup_id,
    debit_account_id,
    b.account_code,
    debit_account_head,
    debit_amount,
    b.account_head_name_en
FROM (
    SELECT 
        accounthead_breakup_id, 
        debit_account_id, 
        debit_account_head, 
        debit_amount
    FROM 
        accounts_master.t_triplicate_accounthead_breakup
    WHERE 
        dcode = :dcode
        AND lbcode = :lbcode
        AND isactive = :isactive
        AND del_flag IS NULL
        AND triplicate_chalan_no = :rc_serial_no
        AND account_type = :account_type
        AND fin_year = :fin_year
) a
LEFT JOIN (
    SELECT 
        account_head_id, 
        old_account_head_code AS account_code, 
        account_head_name_en
    FROM 
        accounts_master.m_account_head
) b 
    ON a.debit_account_id = b.account_head_id;
";
	
		$sel_qry_res=$propertyassessment->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":rc_serial_no"=>$rc_serial_no, ":account_type"=>2, ":fin_year"=>$fin_year),2);
        ob_start();
        foreach($sel_qry_res as $sel_qry_row){
            ?>
            <tr>
                <td><?php echo htmlentities($sel_qry_row['account_code']); ?></td>
                <td><?php echo htmlentities($sel_qry_row['debit_account_head']); ?></td>
                <td><?php echo htmlentities($sel_qry_row['debit_amount']); ?>
                    <input type="hidden" name="debit_breakup_id" value="<?php echo htmlentities($sel_qry_row['accounthead_breakup_id']);?>" class="bank_id" />
                </td>
                <td>
                    <input type="button"  name="btn_debit_edit" value="Edit" class="btn btn_debit_edit btn-md text-white font-weight-bold btn-success" style="font-size: small;">
                   
                    <input type="button"  name="btn_debit_delete" value="Delete" class="btn btn_debit_delete btn-md text-white font-weight-bold btn-danger" style="font-size: small;">
                </td>
            </tr> 
            <?php
        }
         $debit_amount = array_sum(array_column($sel_qry_res, 'debit_amount'));
   
            $ob_contents = ob_get_contents();
		    ob_clean();
            $propertyassessment->commit();
		    $Result_Data['STATUS']='SUCCESS';
		    $Result_Data['debit_data_table']=$ob_contents;
            $Result_Data['debit_amount'] = $debit_amount;
        
        echo json_encode($Result_Data);


        exit;
    }
    /*
    if($cmd==14)
  {
        $Result=array();
        $id = base64_decode($_POST['id']);
        $account_type = base64_decode($_POST['account_type']);
        $dcode = $AdjustBankReceiptVoucher->getCurrentDistrictCode();
        $lbcode = $AdjustBankReceiptVoucher->getCurrentLocalBodyCode();
		$sel_qry = "select  pjv_breakupid, debit_account_id, debit_account_head, debit_amount, credit_amount from accounts_master.t_pj_voucher_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and account_type=:account_type and pjv_breakupid=:pjv_breakupid;";		
		$sel_qry_res=$AdjustBankReceiptVoucher->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":account_type"=>$account_type, ":pjv_breakupid"=>$id),4);
        $Result['STATUS'] = 'SUCCESS';
        $Result['bank_code'] = $sel_qry_res['debit_account_id'];
        $Result['bank_head'] = $sel_qry_res['debit_account_head'];
        $Result['debit_amount'] = $sel_qry_res['debit_amount'];  
        $Result['credit_amount'] = $sel_qry_res['credit_amount'];       
        $Result['pjv_breakupid'] = $sel_qry_res['pjv_breakupid'];
        echo json_encode($Result);
        exit;
  }
        */
if($cmd == 13) 
{
    $rc_serial_no=base64_decode($_POST['rc_serial_no']);
    $bank_code=base64_decode($_POST['credit_bank_code']);
    $bank_head=base64_decode($_POST['credit_bank_head']);
    $amount=base64_decode($_POST['amount']);
    $edit_id=base64_decode($_POST['edit_id']);
    $delete_id=base64_decode($_POST['delete_id']);
    $pay_mode=base64_decode($_POST['pay_mode']);
    $dcode = $propertyassessment->getCurrentDistrictCode();
    $lbcode = $propertyassessment->getCurrentLocalBodyCode();
    $user_name = $propertyassessment->getCurrentUser();
    $ip_address = $propertyassessment->getIpAddress();
    $fin_year = $propertyassessment->getFinYear();
    $raw_challan_date=base64_decode($_POST['challan_date']);
        $date_obj = DateTime::createFromFormat('d-m-Y', $raw_challan_date);
        $challan_date=$date_obj->format('Y-m-d');
      $cash_from_type=base64_decode($_POST['cash_from_type']);
        

    $propertyassessment->beginTransaction();

     $save_query = "SELECT accounts_master.sp_triplicate_accounthead_breakup(:dcode,:lbcode,:acc_type,:acc_code,:credit_acc_head,:amount,:cash_from_type, :rc_serial_no,:challan_date,1,:getCurrentUser,:getIpAddress,:edit_id,:delete_id,:fin_year);";
            $res1 = $propertyassessment->prepare($save_query, [
                ":acc_type" => 1,
                ":acc_code" => $bank_code,
                ":credit_acc_head" => $bank_head,
                ":amount" => $amount,
                ":fin_year" => $fin_year,
                ":rc_serial_no" => $rc_serial_no,
                ":dcode" => $dcode,
                ":cash_from_type"=>$cash_from_type,
                ":challan_date"=>$challan_date,
                ":lbcode" => $lbcode,
                ":getCurrentUser" => $user_name,
                ":getIpAddress" => $ip_address,
                ":edit_id" => $edit_id,
                ":delete_id" => $delete_id
            ], 4);

   $sel_qry = "
    SELECT 
        accounthead_breakup_id,
        credit_account_id,
        account_code,
        credit_account_head,
        credit_amount,
        b.account_head_name_en
    FROM (
        SELECT 
            accounthead_breakup_id, 
            credit_account_id, 
            credit_account_head, 
            credit_amount
        FROM 
            accounts_master.t_triplicate_accounthead_breakup
        WHERE 
            dcode = :dcode
            AND lbcode = :lbcode
            AND isactive = :isactive
            AND del_flag IS NULL
            AND triplicate_chalan_no = :rc_serial_no
            AND account_type = :account_type
            AND fin_year = :fin_year
    ) a
    LEFT JOIN (
        SELECT 
            account_head_id, 
            old_account_head_code AS account_code, 
            account_head_name_en
        FROM 
            accounts_master.m_account_head
    ) b 
        ON a.credit_account_id = b.account_head_id;
    ";

    $sel_qry_res=$propertyassessment->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":rc_serial_no"=>$rc_serial_no, ":account_type"=>1, ":fin_year"=>$fin_year),2);
        ob_start();
        foreach($sel_qry_res as $sel_qry_row){
            ?>
            <tr>
                <td><?php echo htmlentities($sel_qry_row['account_code']); ?></td>
                <td><?php echo htmlentities($sel_qry_row['credit_account_head']); ?></td>
                <td><?php echo htmlentities($sel_qry_row['credit_amount']); ?>
                    <input type="hidden" name="credit_breakup_id" value="<?php echo htmlentities($sel_qry_row['accounthead_breakup_id']);?>" class="bank_id" />
                </td>
                <td>
                    <input type="button"  name="btn_credit_edit" value="Edit" class="btn btn_credit_edit btn-md text-white font-weight-bold btn-success" style="font-size: small;">
                   
                    <input type="button"  name="btn_credit_delete" value="Delete" class="btn btn_credit_delete btn-md text-white font-weight-bold btn-danger" style="font-size: small;">
                </td>
            </tr> 
            <?php
        }
         $credit_amount = array_sum(array_column($sel_qry_res, 'credit_amount'));
         /*
         if($credit_amount<=0 && $pay_mode==1)
         {
            $query="UPDATE accounts_master.t_triplicate_accounthead_breakup set del_flag='Y',del_username=:user_name,del_upd_date=NOW(),del_ipaddress=:ipaddress
            WHERE del_flag is null and triplicate_chalan_no=:rc_serial_no and account_type=2";
            $params=[":user_name"=>$user_name,":ipaddress"=>$ip_address,":rc_serial_no"=>$rc_serial_no];
            $res= $propertyassessment->prepare($query,$params,4);
         }
        */
            $ob_contents = ob_get_contents();
		    ob_clean();
            $propertyassessment->commit();
		    $Result_Data['STATUS']='SUCCESS';
		    $Result_Data['credit_data_table']=$ob_contents;
            $Result_Data['credit_amount'] = $credit_amount;
        
        echo json_encode($Result_Data);


        exit;
    }
if($cmd == 14)
{
    $Result=array();
        $id = base64_decode($_POST['id']);
        $account_type = base64_decode($_POST['account_type']);
        $dcode = $propertyassessment->getCurrentDistrictCode();
        $lbcode = $propertyassessment->getCurrentLocalBodyCode();
		$sel_qry = "select accounthead_breakup_id, credit_account_id, credit_account_head, debit_amount, credit_amount from accounts_master.t_triplicate_accounthead_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and account_type=:account_type and accounthead_breakup_id=:accounthead_breakupid;";		
		$sel_qry_res=$propertyassessment->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":account_type"=>$account_type, ":accounthead_breakupid"=>$id),4);
        $Result['STATUS'] = 'SUCCESS';
        $Result['bank_code'] = $sel_qry_res['credit_account_id'];
        $Result['bank_head'] = $sel_qry_res['credit_account_head'];
        $Result['debit_amount'] = $sel_qry_res['debit_amount'];  
        $Result['credit_amount'] = $sel_qry_res['credit_amount'];         
        $Result['accounthead_breakup_id'] = $sel_qry_res['accounthead_breakup_id'];
        echo json_encode($Result);
        exit;
}
if($cmd == 15)
{
    //check if debit breakup already exists when click add credit on paymode=cash

    /*
     data: {
                                cmd:btoa(15),
                                chalan_no:btoa(rc_serial_no),
                                bank_code:btoa(bank_code),bank_head:btoa(bank_head)
                            },
    */



    $chalan_no=base64_decode($_POST['chalan_no']);
    $fin_year=$propertyassessment->getFinYear();
    $dcode= $propertyassessment->getCurrentDistrictCode();$lbcode= $propertyassessment->getCurrentLocalBodyCode();
    $bank_code=base64_decode($_POST['bank_code']);
    $bank_head=base64_decode($_POST['bank_head']);
    $challan_date=base64_decode($_POST['challan_date']);
            $user_name = $propertyassessment->getCurrentUser();
                    $ip_address = $propertyassessment->getIpAddress();


    $query="SELECT accounthead_breakup_id,debit_amount from accounts_master.t_triplicate_accounthead_breakup where triplicate_chalan_no=:chalan_no and fin_year=:fin_year and lbcode=:lbcode and dcode=:dcode and del_flag is NULL and isactive=1";

    $result=$propertyassessment->prepare($query,[":fin_year"=>$fin_year,":lbcode"=>$lbcode,":dcode"=>$dcode,":chalan_no"=>$chalan_no],4);
    $save_query = "SELECT accounts_master.sp_triplicate_accounthead_breakup(:dcode,:lbcode,:acc_type,:acc_code,:credit_acc_head,:amount,:cash_from_type, :chalan_no,:challan_date,1,:getCurrentUser,:getIpAddress,:edit_id,:delete_id,:fin_year);";

    if(count($result)==0)
    {
        
            /*

                chalan_no:btoa(rc_serial_no),
                bank_code:btoa(bank_code),
                bank_head:btoa(bank_head),
                "challan_date":btoa(challan_date),
                "cash_from_type":btoa(cash_from_type),
                "amount": btoa(amount),

             */ 
            $edit=0;$delete_id= 0;
            
    }
    else{
        $edit=(int)$result['accounthead_breakup_id'];$delete_id= 0;$amount+=int($result['debit_amount']);
    }
    $res1 = $propertyassessment->prepare($save_query, [
                ":acc_type" => 2,
                ":acc_code" => $bank_code,
                ":credit_acc_head" => $bank_head,
                ":amount" => $amount,
                ":fin_year" => $fin_year,
                ":chalan_no" => $chalan_no,
                ":dcode" => $dcode,
                ":cash_from_type"=>$cash_from_type,
                ":challan_date"=>$challan_date,
                ":lbcode" => $lbcode,
                ":getCurrentUser" => $user_name,
                ":getIpAddress" => $ip_address,
                ":edit_id" => 0,
                ":delete_id" => 0
            ], 4);

    $sel_qry = "
SELECT 
    accounthead_breakup_id,
    debit_account_id,
    debit_account_head,
    debit_amount,
    b.account_head_name_en
FROM (
    SELECT 
        accounthead_breakup_id, 
        debit_account_id, 
        debit_account_head, 
        debit_amount
    FROM 
        accounts_master.t_triplicate_accounthead_breakup
    WHERE 
        dcode = :dcode
        AND lbcode = :lbcode
        AND isactive = :isactive
        AND del_flag IS NULL
        AND triplicate_chalan_no = :rc_serial_no
        AND account_type = :account_type
        AND fin_year = :fin_year
) a
LEFT JOIN (
    SELECT 
        account_head_id, 
        old_account_head_code AS account_code, 
        account_head_name_en
    FROM 
        accounts_master.m_account_head
) b 
    ON a.debit_account_id = b.account_head_id;
";
	
		$sel_qry_res=$propertyassessment->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":rc_serial_no"=>$chalan_no, ":account_type"=>2, ":fin_year"=>$fin_year),2);
        ob_start();
        foreach($sel_qry_res as $sel_qry_row){
            ?>
            <tr>
                <td><?php echo htmlentities($sel_qry_row['debit_account_id']); ?></td>
                <td><?php echo htmlentities($sel_qry_row['debit_account_head']); ?></td>
                <td><?php echo htmlentities($sel_qry_row['debit_amount']); ?>
                    <input type="hidden" name="debit_breakup_id" value="<?php echo htmlentities($sel_qry_row['accounthead_breakup_id']);?>" class="bank_id" />
                </td>
                
            </tr> 
            <?php
        }
         $debit_amount = array_sum(array_column($sel_qry_res, 'debit_amount'));
   
            $ob_contents = ob_get_contents();
		    ob_clean();
            $propertyassessment->commit();
		    $Result_Data['STATUS']='SUCCESS';
		    $Result_Data['debit_data_table']=$ob_contents;
            $Result_Data['debit_amount'] = $debit_amount;
        
        echo json_encode($Result_Data);
        exit;
}
if($cmd == 16)
{
    $fin_year=$propertyassessment->getFinYear();
    $query_chalan_no=base64_decode($_GET['query_chalan_no']);
    $lbcode=$propertyassessment->getCurrentLocalBodyCode();
    $dcode=$propertyassessment->getCurrentDistrictCode();
    $triplicate_chalan_query="select * from accounts_master.t_triplicate_chalan_details where chalan_no=:chalan_no and del_flag is null and fin_year=:fin_year and dcode=:dcode and lbcode=:lbcode";
    $res=$propertyassessment->prepare($triplicate_chalan_query,[":chalan_no"=>$query_chalan_no,":fin_year"=>$fin_year,":dcode"=>$dcode,":lbcode"=>$lbcode],4);

    echo json_encode($res);
    exit;
}
if($cmd==17)
{

$fin_year=$propertyassessment->getFinYear();
                                            $triplicate_chalan_no=base64_decode($_GET['query_chalan_no']);
                                            $account_type=base64_decode($_POST['account_type']);
                                            $account_breakup_field=$account_type==1?"credit_breakup_id":"debit_breakup_id";
                                            $amount_type_field=$account_type==1?'credit_amount':'debit_amount';
                                            $pay_mode=base64_decode($_POST['pay_mode']);
                                            $acc_code_field=$account_type==1?'credit_account_id':'debit_account_id';
                                            $btn_edit_field=$btn_del_field=null;
                                            if($account_type== 1)
                                            {
                                                $btn_edit_field='btn_credit_edit';
                                                $btn_del_field= 'btn_credit_delete';
                                            }
                                            else{
                                                $btn_edit_field='btn_debit_edit';
                                                $btn_del_field= 'btn_debit_delete';
                                            }
                                            
                                            $dcode = $propertyassessment->getCurrentDistrictCode();
                                            $lbcode = $propertyassessment->getCurrentLocalBodyCode();
                                            $latest_date_query = "SELECT accounthead_breakup_id,".$amount_type_field." as acc_amount,".$acc_code_field." as acc_code FROM accounts_master.t_triplicate_accounthead_breakup WHERE del_flag IS NULL and triplicate_chalan_no=:triplicate_chalan_no and dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year and account_type=:account_type";
                                            $sel_dname_res=$propertyassessment->prepare($latest_date_query,array(":triplicate_chalan_no"=> $triplicate_chalan_no,":dcode"=> $dcode,":lbcode"=> $lbcode,":fin_year"=>$fin_year,":account_type"=>$account_type),2); 
                                            $output=''; 
                                            $total_amount=0;
                                            $acc_codes = [];
                                            foreach($sel_dname_res as $sel_dname_key => $sel_dname_row) {
                                                $acc_code = $sel_dname_row['acc_code'];
                                        
                                            $latest_date_query = "SELECT account_head_id,account_head_name_en,old_account_head_code FROM accounts_master.m_account_head WHERE del_flag IS NULL and account_head_id=:accountCode_id";
                                            $acc_data = $propertyassessment->prepare($latest_date_query, array(":accountCode_id" => $acc_code), 4);
                                        
                                            $account_head_name_en = $acc_data['account_head_name_en'];
                                            $account_head_code = $acc_data['old_account_head_code'];
                                            $acc_amount = $sel_dname_row['acc_amount'];
                                            $total_amount += $acc_amount;
                                            $acc_codes[] = $acc_code;
                                           $output .= "<tr>
                <td>{$account_head_code}</td>
                <td>{$account_head_name_en}</td>
                <td>{$acc_amount}
                <input type='hidden' name='".$account_breakup_field. "'value=".$sel_dname_row['accounthead_breakup_id']." class='bank_id' />
                </td>" .
                (($pay_mode == 1 && $account_type==2)
                    ? ""
                    : "<td>
                        <input type='button' name='".$btn_edit_field."' value='Edit' class='btn ".$btn_edit_field." btn-md text-white font-weight-bold btn-success' style='font-size: small;' />
                        <input type='button'  name='".$btn_del_field."' value='Delete' class='btn ".$btn_del_field." btn-md text-white font-weight-bold btn-danger' style='font-size: small;' />
                       </td>"
                ) .
            "</tr>";

                                        }
                                        $output .= "<tr>
                                        <td colspan='2'><strong>Total</strong></td>
                                        <td><strong>{$total_amount}</strong></td>
                                        <td colspan='2'></td>
                                            </tr>";
                                            $acc_codes_json = json_encode($acc_codes);
                                            echo json_encode([
                                                "total_amount" => $total_amount,
                                                "acc_codes" => $acc_codes_json,
                                                "html" => $output
                                            ]);
                                            exit;
    }
if($cmd==18)
{


    $dcode=$propertyassessment->getCurrentDistrictCode();
    $lbcode=$propertyassessment->getCurrentLocalBodyCode();
    $fin_year=$propertyassessment->getFinYear();
    $chalan_no=base64_decode($_POST['chalan_no']);
    $sel_qry = "
SELECT 
    accounthead_breakup_id,
    debit_account_id,
    b.account_code,
    debit_account_head,
    debit_amount,
    b.account_head_name_en
FROM (
    SELECT 
        accounthead_breakup_id, 
        debit_account_id, 
        debit_account_head, 
        debit_amount
    FROM 
        accounts_master.t_triplicate_accounthead_breakup
    WHERE 
        dcode = :dcode
        AND lbcode = :lbcode
        AND isactive = :isactive
        AND del_flag IS NULL
        AND triplicate_chalan_no = :chalan_no
        AND account_type = :account_type
        AND fin_year = :fin_year
) a
LEFT JOIN (
    SELECT 
        account_head_id, 
        old_account_head_code AS account_code, 
        account_head_name_en
    FROM 
        accounts_master.m_account_head
) b 
    ON a.debit_account_id = b.account_head_id;
";
	
		$sel_qry_res=$propertyassessment->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":chalan_no"=>$chalan_no, ":account_type"=>2, ":fin_year"=>$fin_year),2);
        ob_start();
        foreach($sel_qry_res as $sel_qry_row){
            ?>
            <tr>
                <td><?php echo htmlentities($sel_qry_row['account_code']); ?></td>
                <td><?php echo htmlentities($sel_qry_row['debit_account_head']); ?></td>
                <td><?php echo htmlentities($sel_qry_row['debit_amount']); ?>
                    <input type="hidden" name="debit_breakup_id" value="<?php echo htmlentities($sel_qry_row['accounthead_breakup_id']);?>" class="bank_id" />
                </td>
                <td>
                    <input type="button"  name="btn_debit_edit" value="Edit" class="btn btn_debit_edit btn-md text-white font-weight-bold btn-success" style="font-size: small;">
                   
                    <input type="button"  name="btn_debit_delete" value="Delete" class="btn btn_debit_delete btn-md text-white font-weight-bold btn-danger" style="font-size: small;">
                </td>
            </tr> 
            <?php
        }
         $debit_amount = array_sum(array_column($sel_qry_res, 'debit_amount'));
   
            $ob_contents = ob_get_contents();
		    ob_clean();
            $propertyassessment->commit();
		    $Result_Data['STATUS']='SUCCESS';
		    $Result_Data['debit_data_table']=$ob_contents;
            $Result_Data['debit_amount'] = $debit_amount;
        
        echo json_encode($Result_Data);


        exit;
}
}
?>