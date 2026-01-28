<?php

require_once  '../../config/config.php';


if($_SERVER['REQUEST_METHOD']=='GET')
{

    if( !isset($_GET['id']))
    {
        echo ("No Chalan Number has been sent along this request for editing ");
        die();
    }
    else{
        $conn=new ConfigClass();
        $lbcode=$conn->getCurrentLocalBodyCode();
        $dcode=$conn->getCurrentDistrictCode();
        $fin_year=$conn->getFinYear();
        $cjv_id=base64_decode($_GET['id']);
        $res=$conn->prepare('select count(*) as "count" from accounts_master.t_cj_voucher where cjv_id=:cjv_id and dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year and del_flag is null',[":dcode"=>$dcode,":lbcode"=>$lbcode,":fin_year"=>$fin_year,":cjv_id"=>$cjv_id],4);
        if($res["count"]==0)
        {
            echo("Voucher Number Does not exist");
            die();
        }
        $voucher_res=$conn->prepare('select cjv_no from accounts_master.t_cj_voucher where cjv_id=:cjv_id and dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year and del_flag is null',[":dcode"=>$dcode,":lbcode"=>$lbcode,":fin_year"=>$fin_year,":cjv_id"=>$cjv_id],4);
        $voucher_no=$voucher_res['cjv_no'];
    }
}
 
class EditContractJournalVoucher  extends ConfigClass
{

    public $page_token = "Contract_Journal_Vouchers";
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
        $voucher_type="1";
        $sel_date_qry="select TO_CHAR(bpv_date, 'DD-MM-YYYY') as bpv_date from accounts_master.t_bank_payment_voucher where voucher_type=:voucher_type and dcode=:dcode and lbcode=:lbcode and del_flag is null and fin_year=:fin_year order by bpv_id desc limit 1;";
		$sel_date_qry_res=$this->prepare($sel_date_qry, array(":voucher_type"=>$voucher_type,":dcode"=>$dcode, ":lbcode"=>$lbcode, ":fin_year" =>$fin_year),4);
        $cjv_id=base64_decode($_GET['id']);
        $res=$this->prepare('select count(*) as "count" from accounts_master.t_cj_voucher where cjv_id=:cjv_id and dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year and del_flag is null',[":dcode"=>$dcode,":lbcode"=>$lbcode,":fin_year"=>$fin_year,":cjv_id"=>$cjv_id],4);
        $voucher_res=$this->prepare('select cjv_no from accounts_master.t_cj_voucher where cjv_id=:cjv_id and dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year and del_flag is null',[":dcode"=>$dcode,":lbcode"=>$lbcode,":fin_year"=>$fin_year,":cjv_id"=>$cjv_id],4);
        $voucher_no=$voucher_res['cjv_no'];
       
        if(isset($sel_date_qry_res['bpv_date']) && $sel_date_qry_res['bpv_date'] != ''){
            
            $bpv_date= $sel_date_qry_res['bpv_date'];
        }else{
            $sel_date_qry="select * from public.sp_date_from_fin_year(:fin_year);";
            $sel_date_qry_res=$this->prepare($sel_date_qry, array(":fin_year" =>$fin_year),4);
            $bpv_date= json_decode($sel_date_qry_res['sp_date_from_fin_year'])->from_date;
        }
        ?>
<script type="text/javascript">
//uncomment
/*
 */
$(document).ready(function() {
    $(document).on('change', '#debit_bank_code', function() {
        // if($("#date").val() ==''){
        //     alert('Select BRV Date');
        //     return false;
        // }

        if ($("#debit_bank_code").val() != '') {
            var bank_code = $("#debit_bank_code").val();
        } else {
            alert("Select Bank Code");

        }

    });
    $(document).on('click', '#btn_debit_edit', function() {
        var id = $(this).parent().parent().find('.bank_id').val();
        $.ajax({
            url: "<?php echo $_SERVER['REQUEST_URI']; ?>",
            type: "post",
            data: {
                "account_type": btoa(2),
                "id": btoa(id),
                "cmd": btoa(4)
            },
            success: function(data) {
                if (data != '') {
                    var Result_Data = JSON.parse(data);
                    $("#btn_debit_add").show();
                    $("#btn_debit_add").val("Edit Debit");
                    $('#debit_bank_code').val(Result_Data['bank_code']);
                    $('#debit_bank_head').val(Result_Data['bank_head']);
                    $('#debit_amount').val(Result_Data['debit_amount']);
                    $('#debit_delete_id').val('');
                    $('#debit_edit_id').val(Result_Data['cjv_breakupid']);
                }
            },
            dataType: 'html'
        });
    });
    $(document).on('click', '#btn_debit_delete', function() {
        var id = $(this).parent().parent().find('.bank_id').val();
        $.ajax({
            url: "<?php echo $_SERVER['REQUEST_URI'];?>",
            type: "post",
            data: {
                "account_type": btoa(2),
                "id": btoa(id),
                "cmd": btoa(5)
            },
            success: function(data) {
                if (data != '') {
                    var Result_Data = JSON.parse(data);
                    $("#btn_debit_add").val('Delete Debit');
                    $('#debit_bank_code').val(Result_Data['bank_code']);
                    $('#debit_bank_head').val(Result_Data['bank_head']);
                    $('#debit_amount').val(Result_Data['debit_amount']);
                    $('#debit_delete_id').val(Result_Data['cjv_breakupid']);
                    $('#debit_edit_id').val('');
                }
            },
            error: function(xhr, error, status) {
                console.log(`debit delete error:${error}`);
                console.log(`debit delete error status:${status}`);
            },
            dataType: 'html'
        });
    });
    $(document).on('click', '#btn_credit_edit', function() {
        var id = $(this).parent().parent().find('.bank_id').val();
        $.ajax({
            url: "<?php echo $_SERVER['REQUEST_URI'];?>",
            type: "post",
            data: {
                "account_type": btoa(1),
                "id": btoa(id),
                "cmd": btoa(8)
            },
            success: function(data) {
                if (data != '') {
                    var Result_Data = JSON.parse(data);
                    $("#btn_credit_add").val("Edit Credit");
                    $("#btn_credit_add").show();
                    $('#credit_bank_code').val(Result_Data['bank_code']);
                    $('#credit_bank_head').val(Result_Data['bank_head']);
                    $('#credit_amount').val(Result_Data['credit_amount']);
                    $('#credit_delete_id').val('');
                    $('#credit_edit_id').val(Result_Data['cjv_breakupid']);
                }
            },
            dataType: 'html'
        });
    });
    $(document).on('click', '#btn_credit_delete', function() {
        var id = $(this).parent().parent().find('.bank_id').val();
        $.ajax({
            url: "<?php echo $_SERVER['REQUEST_URI'];?>",
            type: "post",
            data: {
                "account_type": btoa(1),
                "id": btoa(id),
                "cmd": btoa(9)
            },
            success: function(data) {
                if (data != '') {
                    var Result_Data = JSON.parse(data);
                    $("#btn_credit_add").val("Delete Credit");
                    $('#credit_bank_code').val(Result_Data['bank_code']);
                    $('#credit_bank_head').html(Result_Data['bank_head']);
                    $('#credit_amount').val(Result_Data['credit_amount']);
                    $('#credit_delete_id').val(Result_Data['cjv_breakupid']);
                    $('#credit_edit_id').val('');
                }
            },
            dataType: 'html'
        });
    });
    $(document).on('click', '#btn_debit_add', function() {
        try {
            if ($("#cjv_serial_no").val().length == '') {
                throw {
                    msg: "Missing Serial Number",
                    foc: "#cjv_serial_no"
                }
            } else {
                var cjv_serial_no = $("#cjv_serial_no").val();
            }

            if ($("#debit_bank_code").val().length == '') {
                throw {
                    msg: "Select Bank Code",
                    foc: "#bank_code"
                }
            } else {
                var bank_code = $("#debit_bank_code").val();
            }
            if ($("#debit_bank_head").val().length == '') {
                throw {
                    msg: "Enter Bank Head",
                    foc: "#debit_bank_head"
                }
            } else {
                var bank_head = $("#debit_bank_head").val();
            }

            if ($("#debit_amount").val().length == '') {
                throw {
                    msg: "Enter Debit Amount",
                    foc: "#bank_head"
                }
            } else {
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
            url: "<?php echo $_SERVER['REQUEST_URI']; ?>",
            type: "post",
            data: {

                "cjv_serial_no": btoa(cjv_serial_no),
                "bank_code": btoa(bank_code),
                "bank_head": btoa(bank_head),
                "amount": btoa(amount),
                "edit_id": btoa(edit_id),
                "delete_id": btoa(delete_id),
                "cmd": btoa(3)
            },
            success: function(data) {
                if (data != '') {
                    var Result_Data = JSON.parse(data);
                    if (Result_Data['STATUS'] == 'SUCCESS') {
                        let message = "Successfully Added";
                        if (delete_id != '') {
                            message = "Successfully Deleted";
                        } else if (edit_id != '') {
                            message = "Successfully Changed";
                        }
                        alert(message);
                        $("#debit_table_result tbody").html(Result_Data[
                            'debit_data_table']);
                        $('#debit_total_amount').val(Result_Data['debit_amount']);
                        $('#span_debit_total_amount').html(Result_Data['debit_amount']);
                        $('#debit_delete_id').val('');
                        $('#debit_edit_id').val('');
                        $('#debit_bank_code').val('');
                        $('#debit_bank_head').val('');
                        $('#debit_amount').val('');
                        //$("#btn_debit_add").val('Add Debit');
                        $("#btn_debit_add").hide();
                    } else {
                        alert(Result_Data['MESSAGE']);
                    }
                }
            },
            dataType: 'html'
        });
    });
    $(document).on('click', '#btn_credit_add', function() {
        try {
            if ($("#cjv_serial_no").val().length == '') {
                throw {
                    msg: "Missing Serial Number",
                    foc: "#cjv_serial_no"
                }
            } else {
                var cjv_serial_no = $("#cjv_serial_no").val();
            }

            if ($("#credit_bank_code").val().length == '') {
                throw {
                    msg: "Select Account Code",
                    foc: "#acc_code"
                }
            } else {
                var credit_bank_code = $("#credit_bank_code").val();
            }


            if ($("#credit_bank_head").val().length == '') {
                throw {
                    msg: "Enter Bank Head",
                    foc: "#credit_bank_head"
                }
            } else {
                var credit_bank_head = $("#credit_bank_head").val();
            }
            if ($("#credit_amount").val().length == '') {
                throw {
                    msg: "Enter Credit Amount",
                    foc: "#credit_amount"
                }
            } else {
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
            url: "<?php echo $_SERVER['REQUEST_URI']; ?>",
            type: "post",
            data: {

                "cjv_serial_no": btoa(cjv_serial_no),
                "credit_bank_code": btoa(credit_bank_code),
                "credit_bank_head": btoa(credit_bank_head),
                "amount": btoa(credit_amount),
                "edit_id": btoa(edit_id),
                "delete_id": btoa(delete_id),
                "cmd": btoa(6)
            },
            success: function(data) {
                if (data != '') {
                    var Result_Data = JSON.parse(data);
                    if (Result_Data['STATUS'] == 'SUCCESS') {
                        let message = "Successfully Added";
                        if (delete_id != '') {
                            message = "Successfully Deleted";
                        } else if (edit_id != '') {
                            message = "Successfully Changed";
                        }
                        alert(message);
                        $("#credit_table_result tbody").html(Result_Data[
                            'credit_data_table']);
                        $('#credit_total_amount').val(Result_Data['credit_amount']);
                        $('#span_credit_total_amount').html(Result_Data['credit_amount']);
                        $('#credit_delete_id').val('');
                        $('#credit_edit_id').val('');
                        $('#credit_bank_code').val('');
                        $('#credit_bank_head').html('');
                        $('#credit_amount').val('');
                        //$("#btn_credit_add").val('Add Credit');
                        $("#btn_credit_add").hide();

                    } else {
                        alert(Result_Data['MESSAGE']);
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
        if ($("#date").val() == '') {
            alert('Select Date');
            return false;
        }

        if ($("#acc_code").val() != '') {
            var acc_code = $("#acc_code").val();
        } else {
            alert("Select Account Code");
        }
        if (acc_code != '') {
            var acc_desc = $('#acc_code option:selected').data('desc');
            $("#acc_head").html(acc_desc);
        }
    });
    let date = new Date('<?php echo date("Y-m-d", strtotime($bpv_date)); ?>');
    date.setDate(date.getDate() + 1);
    date = date.toISOString().slice(0, 10);
    $('#date').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'dd-mm-yyyy',
        minDate: new Date(date),
        maxDate: new Date()
    }).on('change', function(e) {
        const chalan_date = $(this).val();
        $.ajax({
            url: "<?php echo $_SERVER['REQUEST_URI']; ?>",
            type: "post",
            data: {
                "cjv_date": btoa(chalan_date),
                "type": btoa(1),
                "cmd": btoa(10),
            },
            success: function(response) {
                $('#loading-image').hide();
                //console.log(`response = ${response}`);
                const res = JSON.parse(response);

                if (res.STATUS === "ERROR") {
                    alert(res.MESSAGE);
                    $('#date').val('');
                }
            }
        });
    });


    $('input, select, textarea').not('#date').on('focus click', function() {
        if ($('#date').val().trim() === '') {
            alert("Please Select Date");
            $('#date').focus();
        }
    });
    /*
    $('#date').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'dd-mm-yyyy',
        minDate: new Date('<?php #echo date("Y-m-d", strtotime($challan_date)); ?>'),
        maxDate: new Date()
    }).on('change', function(e) {
        const chalan_date = $(this).val();
        if ($("#date").val() != '') {
            var date = $("#date").val();
            if (chalan_date > date) {
                alert('Chalan Collection Date Must Be Lesss Than BRV Date.');
                $(this).val('');
                return false;
            }
        } else {
            $(this).val('');
            // alert('Select BRV Date');
            return false;
        }
        $.ajax({
            url: "Edit_Contract_Journal_Vouchers.php?query_voucher_no=,
            type: "post",
            data: {
                "chl_date": btoa(chalan_date),
                "cmd": btoa(7),
            },
            success: function(response) {
                $('#collection_amount').val(response);
                $('#span_collection_amount').html(response);
            }
        });
    });

    
    */
    $(document).on('change',"#name-of-work",function()
        {
            let cwd_id=$(this).val();
            if(cwd_id!='')
            {
                $.ajax({
            url: "<?php echo $_SERVER['REQUEST_URI']; ?>",
            type: "post",
            data: {
                "cmd": btoa(14),
                "cwd_id":btoa(cwd_id)
            },
            success: function(response) {
                if(response!='')
                {
                    $('#scheme_name').html(response.scheme_name);
                    $('#scheme').val(response.scheme_id);
                }
            },
            dataType:'json'
        });
            }
        }   
        );


    <?php if (!isset($post_data_array['del_id'])) { ?>
    $(document).on('click', "#btn_save", function() {
        var Current_Field_id = $(this).attr('id');
        $('#' + Current_Field_id).hide();
        try {
            if ($("#cjv_serial_no").val().length == '') {
                throw {
                    msg: "Enter Serial Number",
                    foc: "#cjv_serial_no"
                }
            }
            if ($("#date").val().length == '') {
                throw {
                    msg: "Select Date",
                    foc: "#date"
                }
            }

            if ($("#pay_mode").val().length == '') {
                throw {
                    msg: "Payment Mode missing",
                    foc: "#pay_mode"
                }
            }
            if ($("#contractor-name").val().length == '') {
                throw {
                    msg: "Enter Contractor Name",
                    foc: "#contractor-name"
                }
            }

            if ($("#MBR-No").val().length == '') {
                throw {
                    msg: "Enter M.Book Reference No",
                    foc: "#MBR-No"
                }
            }

            if ($("#name-of-work").val().length == '') {
                throw {
                    msg: "Enter Name of Work",
                    foc: "#name-of-work"
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
            if (parseFloat($("#credit_total_amount").val()) != parseFloat($("#debit_total_amount")
                    .val())) {
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

    function initialSetUp() {
        let credit_id = "";
        let debit_id = "";



        $.ajax({
            url: "<?php echo $_SERVER['REQUEST_URI']; ?>",
            type: "post",
            data: {
                cmd: btoa(11)
            },
            success: function(data) {
                //console.log(data)
                //cjv_date
                console.log(data.cjv_date);
                let curr_date = data.cjv_date.split(" ")[0].split("-");
                let formatted_date = curr_date[2] + "-" + curr_date[1] + "-" + curr_date[0];
                $(".cjv_date").val(formatted_date);


                //debit head
                $.ajax({
                    url: "<?php echo $_SERVER['REQUEST_URI']; ?>",
                    type: "post",
                    data: {
                        cmd: btoa(12),
                        id: btoa(data.cjv_id)
                    },
                    success: function(debit_head_data) {
                        $("#debit_table_result tbody").html(debit_head_data);
                        $('#span_debit_total_amount').html(data.debit_tot_amount);
                        $('#debit_total_amount').val(Number(data.debit_tot_amount));  

                    },
                    dataType: "text"
                })

                //credit head
                $.ajax({
                    url: "<?php echo $_SERVER['REQUEST_URI']; ?>",
                    type: "post",
                    data: {
                        cmd: btoa(13),
                        voucher_no: btoa(data.cjv_id)
                    },
                    success: function(credit_head_data) {
                        $("#credit_table_result tbody").html(credit_head_data);
                        $('#span_credit_total_amount').html(data.credit_tot_amount);
                        $('#credit_total_amount').val(Number(data.credit_tot_amount));  

                    },
                    dataType: "html"
                });
                //narration.
                $("#narration").val(data.narration);
                //pay_mode
                $(".pay_mode").val(data.payment_mode);
                //contractor-name
                $("#contractor-name").val(data.name_of_contractor
                 );
                //mbookno
                $("#MBR-No").val(data.m_book_ref_no);
                $(".name-of-work").val(data.name_of_work);
                $("#name-of-work").trigger('change');
                $("#btn_debit_add").hide();
                $("#btn_credit_add").hide();
                //$("#scheme").val(data.scheme);

            },
            dataType: "json"
        });

    }
    initialSetUp();
});
</script>
<style type="text/css">
.hidden_field_element_value {
    display: none;
}

.gj-datepicker {
    width: 50%;
}

table.table-bordered>tbody>tr>td,
table.table-bordered>tfoot>tr>td {
    width: 50% !important;
}
</style>
<div class="container mt-3">
    <form action="" method="post" class="" enctype="multipart/form-data">
        <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>"
            name="<?php echo htmlentities($this->page_token); ?>"
            value="<?php echo htmlentities($this->token($this->page_token)); ?>">
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
                            <th align="center" scope="col" colspan="12"> Edit Contract Journal Voucher</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-left font-weight-bold"><span DisplayLabelID="483">Serial No</span></td>
                            <td scope="col">
                                <?php   
                                        /*
                                        $sel_qry="select max(cjv_no) as id from accounts_master.t_cj_voucher where dcode=:dcode and lbcode=:lbcode and del_flag is null  and fin_year=:fin_year;";
                                        $sel_qry_res=$this->prepare($sel_qry, array(":dcode"=>$dcode, ":lbcode"=>$lbcode, ":fin_year"=>$fin_year),4);
                                        $get_cur_fin_year="select * from public.sp_fin_year_from_date(current_date);";
                                        $cur_fin_year=$this->prepare($get_cur_fin_year, array(),4);
                                        */
                                        $fin_year=$this->getFinYear();
                                         $del_qry="update accounts_master.t_cj_voucher_breakup set del_flag='Y',                  del_username=:username,del_upd_date=NOW()::timestamp,del_ipaddress=:ipaddress where dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year and cjv_chalan_no=:cjv_chalan_no and cjv_id is null;";
                                         
                                         $del_qry_res=$this->prepare($del_qry, array(":dcode"=>$dcode, ":lbcode"=>$lbcode, ":fin_year"=>$fin_year, ":cjv_chalan_no"=>$voucher_no, ":fin_year"=>$fin_year,":username"=>$this->getCurrentUser(),
                                            ":ipaddress"=>$this->getIpAddress()),4);
                                        echo $voucher_no;
                                       
                                        
                                     
                                        ?>
                                <input type="hidden" id="cjv_serial_no" name="cjv_serial_no"
                                    class="form-control w-50 form-control-sm"
                                    value="<?php echo $voucher_no; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-weight-bold"><span DisplayLabelID="484">Cjv Date</span></td>
                            <td scope="col">
                                <input type="text" id="date" name="date" value="<?php echo htmlentities(isset($post_data_array['date'])?$post_data_array['date']:'')?>"
                                    class="form-control form-control-sm user_enter_date w-50 cjv_date" disabled/>
                                    <input type="hidden" class="cjv_date" name="date" value=""/>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-weight-bold"><span DisplayLabelID="484">Payment Mode:</span></td>
                            <td scope="col">
                                <select id="pay_mode" name="pay_mode" class="form-control form-control-sm mb-2 pay_mode" disabled>
                                    <option>choose</option>
                                    <option value="part-bill">Part-Bill</option>
                                    <option value="final-bill">Final-Bill</option>
                                </select>
                                <input name="pay_mode" id="pay_mode_hidden" class="pay_mode" type="hidden" value=""/>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-weight-bold"><span DisplayLabelID="483">Name Of Contractor</span>
                            </td>
                            <td scope="col">
                                <textarea type="text" id="contractor-name" name="contractor-name"
                                    class="form-control form-control-sm number_field" readonly></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-weight-bold"><span DisplayLabelID="483">M.Book Reference No</span>
                            </td>
                            <td scope="col">
                                <input type="text" id="MBR-No" name="MBR-No"
                                    class="form-control form-control-sm number_field" readonly/>
                                    <?php if (isset($post_data_array['MBR-No'])) { ?>
                                    <script>
                                        $('#MBR-No').val('<?= $post_data_array['MBR-No'] ?>');
                                    </script>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-weight-bold w-50"><span DisplayLabelID="483">Name of Work</span></td>
                            <td scope="col">

                                    <select id="name-of-work" name="name-of-work"
                                    class="form-control form-control-sm w-50 name-of-work" disabled>
                                    <option>
                                            choose work name
                                        </option>
                                        <?php
                                        $query="SELECT contractor_work_details_id , name_of_work_".$lang_code_2d." FROM accounts_master.t_contractor_work_details WHERE lbcode=:lbcode AND dcode=:dcode AND fin_year=:fin_year AND del_flag IS NULL";

                                        $res=$this->prepare($query,[":lbcode"=>$lbcode,":dcode"=>$dcode,":fin_year"=>$fin_year],2);
                                        foreach($res as $key=>$row)
                                        {?>

                                            <option value="<?php echo ($row['contractor_work_details_id']); ?>"  ><?php echo $row['name_of_work_'.$lang_code_2d] ?></option>
                                        
                                        <?php
                                        }
                                        ?>
                                        
                                    </select>
                                    <input type="hidden" name="name-of-work" class="name-of-work" id="name-of-work-hidden" />
                                <?php if (isset($post_data_array['name-of-work'])) { ?>
                                    <script>
                                        $('#name-of-work').val('<?= $post_data_array['name-of-work'] ?>');
                                        $('#name-of-work').trigger('change');
                                    </script>
                                <?php } ?>
                            </td>
                        </tr>

                </table><br>

                <!-- <tr>
                                    <td align="center">
                                        Debit
                                    </td>
                                    <td align="center">
                                        Credit
                                    </td>
                                </tr> -->

                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered m-0 p-0 table-striped tndtp_form_table">
                            <tr>
                                <th align="center" scope="col"
                                    style="text-align:center;background-color:darkslateblue;color:white" colspan="12">
                                    Debit</th>
                            </tr>
                            <tr>
                                <td class="text-left font-weight-bold"><span>Account Code & Head</span></td>
                                <td scope="col">

                                    <select id="debit_bank_code" name="debit_bank_code"
                                        class="form-control form-control-sm mb-2">
                                        <option value="">Choose</option>

                                        <?php 
    //uncomment
    /*
    */ 
    $sel_bank_new_id = "SELECT account_head_id, old_account_head_code as account_code, account_head_name_en FROM accounts_master.m_account_head ORDER BY account_code DESC;";
    $sel_bank_newid_res = $this->prepare($sel_bank_new_id, array(), 2);
    foreach ($sel_bank_newid_res as $row): ?>
                                        <option value="<?= $row['account_head_id']; ?>"
                                            data-code="<?= htmlentities($row['account_code']); ?>"
                                            data-name="<?= htmlentities($row['account_head_name_en']); ?>">
                                            <?= htmlentities($row['account_code'] . ' - ' . $row['account_head_name_en']); ?>
                                        </option>
                                        <?php endforeach;
    ?>
                                    </select>


                                    <input type="hidden" id="debit_bank_head" name="debit_bank_head"
                                        class="form-control form-control-sm number_field" 
                                        value="<?php
                                        if(isset($post_data_array['debit_bank_head']))
                                        {
                                            echo htmlentities($post_data_array['debit_bank_head']);
                                        }
                                            
                                        ?>"
                                        />


                                </td>
                            </tr>


                            </tr>

                            <tr>
                                <td class="text-left font-weight-bold"><span DisplayLabelID="483">Amount</span></td>
                                <td scope="col">
                                    <input type="text" id="debit_amount" name="debit_amount"
                                        class="form-control form-control-sm number_field"   
                                         readonly/>
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
                                <td class="text-right font-weight-bold"><span DisplayLabelID="483">Debit Amount</span>
                                </td>
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
                    <div class=col-md-6>
                        <!-- credit  -->

                        <table class="table table-bordered m-0 p-0 table-striped tndtp_form_table">
                            <tr>
                                <th align="center" scope="col"
                                    style="text-align:center;background-color:darkslateblue;color:white" colspan="12">
                                    Credit</th>
                            </tr>
                            <tr>
                                <td class="text-left font-weight-bold"><span>Account Code & Head</span></td>
                                <td scope="col">



                                    <select id="credit_bank_code" name="credit_bank_code"
                                        class="form-control form-control-sm mb-2">
                                        <option value="">Choose</option>
                                        <?php
    //uncomment
    /*
    */
    $sel_bank_new_id = "SELECT account_head_id, old_account_head_code as account_code, account_head_name_en FROM accounts_master.m_account_head ORDER BY account_code DESC;";
$sel_bank_newid_res = $this->prepare($sel_bank_new_id, array(), 2);
foreach ($sel_bank_newid_res as $row): ?>
                                        <option value="<?= $row['account_head_id']; ?>"
                                            data-code="<?= htmlentities($row['account_code']); ?>"
                                            data-name="<?= htmlentities($row['account_head_name_en']); ?>">
                                            <?= htmlentities($row['account_code'] . ' - ' . $row['account_head_name_en']); ?>
                                        </option>
                                        <?php endforeach;     
    
        ?>
                                    </select>



                                    <input type="hidden" id="credit_bank_head" name="credit_bank_head"
                                        class="form-control form-control-sm number_field" />

                                </td>
                            </tr>


                            <tr>
                                <td class="text-text-right font-weight-bold"><span DisplayLabelID="483">Amount</span>
                                </td>
                                <td scope="col">
                                    <input type="text" id="credit_amount" name="credit_amount"
                                        class="form-control form-control-sm number_field" readonly/>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left font-weight-bold" colspan="2" align="center">
                                    <input type="button" id="btn_credit_add" name="btn_credit_add" value="Add Credit"
                                        class="btn btn-md text-white font-weight-bold btn-success" />
                                    <input type="hidden" id="credit_edit_id" name="credit_edit_id"
                                        class="form-control form-control-sm number_field" value="" />
                                    <input type="hidden" id="credit_delete_id" name="credit_delete_id"
                                        class="form-control form-control-sm number_field" value="" />
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left font-weight-bold"><span DisplayLabelID="483">Credit Amount</span>
                                </td>
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
                </div><br>









                <table class="table table-bordered m-0 p-0 table-striped tndtp_form_table">
                    <tr>
                        <td align="center">
                            <span DisplayLabelID="484">Scheme</span>
                        </td>
                        <td align="left" colspan="2">
                            <span id="scheme_name">-</span>
                            <input type="hidden" value="" id="scheme" name="scheme" />
                            <?php if (isset($post_data_array['scheme'])) { ?>
                                    <script>
                                        $('#scheme').val('<?= $post_data_array['scheme'] ?>');
                                    </script>
                                <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <span DisplayLabelID="484">Narration</span>
                        </td>
                        <td align="left" colspan="2">
                            <textarea id="narration" name="narration" rows="4" cols="50"
                                class="form-control w-50 form-control-sm" readonly></textarea>
                            <span>Max 250 Characters</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" align="center">
                            <input type="submit" id="btn_save" name="btn_save" value="Save"
                                class="btn btn-md text-white font-weight-bold  btn-success" />
                            <input type="button" id="btn_reset" name="btn_reset" value="Cancel"
                                class="btn btn-md text-white font-weight-bold btn-secondary"
                                onclick="window.location='Contract_journal_vouchers.php'" />
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
        // var_dump($save_data);exit();
        // TOKEN VALIDATE
        //print_r($save_data);die();
        if (!$this->validateToken($this->page_token, $save_data[$this->page_token])) {
            $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => $this->page_token,
                "MESSAGE" => "Invalid Token"
            ), $save_data));
            exit;
        }
// echo "<pre>";
// print_r($save_data);
        $statecode = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();

        if (isset($save_data['cjv_serial_no']) && $save_data['cjv_serial_no']!='') {
            $cjv_serial_no = $save_data['cjv_serial_no'];
            $cjv_serial_no_Validation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number_slash_hyphen',
                    'Field_Value' => $save_data['cjv_serial_no'],
                    'Field_Name' => 'cjv serial no',
                    'Field_Label_Name' => 'cjv serial no',
                )
            );

            if ($cjv_serial_no_Validation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "cjv_serial_no",
                    "MESSAGE" => $cjv_serial_no_Validation['Message']
                ), $save_data));
                exit;
            }
        }else{
            $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "cjv_serial_no",
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
         
        if (isset($save_data['contractor-name']) && $save_data['contractor-name']!='') {
            $contractor_name = $save_data['contractor-name'];
            $contractor_name_Validation = $this->Field_Validation(
                array(
                    'Field_Type' => 'text_space',
                    'Field_Value' => $save_data['contractor-name'],
                    'Field_Name' => 'Contractor Name',
                    'Field_Label_Name' => 'Contractor Name',
                )
            );

            if ($contractor_name_Validation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "Contractor Name",
                    "MESSAGE" => $contractor_name_Validation['Message']
                ), $save_data));
                exit;
            }
        }else{
            $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "Contractor Name",
                "MESSAGE" => 'Contractor Name is required'
            ), $save_data));
            exit;
        }

        if (isset($save_data['name-of-work']) && $save_data['name-of-work']!='') {
            $name_of_work = $save_data['name-of-work'];
            $name_of_work_Validation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $save_data['name-of-work'],
                    'Field_Name' => 'Name of Work',
                    'Field_Label_Name' => 'Name of Work',
                )
            );

            if ($name_of_work_Validation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "Name of Work",
                    "MESSAGE" => $name_of_work_Validation['Message']
                ), $save_data));
                exit;
            }
        }else{
            $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "Name of Work",
                "MESSAGE" => 'Name of Work is required'
            ), $save_data));
            exit;
        }


        if (isset($save_data['MBR-No']) && $save_data['MBR-No']!='') {
            $MBR_No = $save_data['MBR-No'];
            $MBR_No_Validation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $save_data['MBR-No'],
                    'Field_Name' => 'M.Book Reference No',
                    'Field_Label_Name' => 'M.Book Reference No	',
                )
            );

            if ($MBR_No_Validation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "MBook Reference Number",
                    "MESSAGE" => $MBR_No_Validation['Message']
                ), $save_data));
                exit;
            }
        }else{
            $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "M.Book Reference No",
                "MESSAGE" => 'M.Book Reference No is required'
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
            $narrationValidation = $this->Field_Validation(
                array(
                    'Field_Type' => 'text_space',
                    'Field_Value' => $narration,
                    'Field_Name' => 'Narration',
                    'Field_Max_length' => '300',
                    'Field_Label_Name' => 'Narration',
                )
            );
            if ($narrationValidation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "Narration",
                    "MESSAGE" => $narrationValidation['Message']
                ), $save_data));
                exit;
            }
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
        $Result_Message = "Data Edited SuccessFully";
        $this->beginTransaction();
        $sp_bank_receicpt_voucher = "accounts_master.sp_cj_voucher";
        $user_name = $this->getCurrentUser();
        $ip_address = $this->getIpAddress();
        $pay_mode=$save_data['pay_mode'];
        //$name_of_contractor=$save_data['contractor-name'];
        $name_of_work=$save_data['name-of-work'];
        $scheme=$save_data['scheme'];
        $save_query = "select * from " . $sp_bank_receicpt_voucher . "(:dcode, :lbcode, :cjv_chalan_no, :cjv_date,:pay_mode,:contractor_name,:mbr_no,:name_of_work,:debit_amount, :credit_amount, :total_amount, :narration,:scheme, :fin_year, :user_name,:ip_address, :edit_id, :del_id)";
        /*select * from accounts_master.sp_cj_voucher(1, 200105, '1', '2025-05-26','100', '100', '100', 'TEst', '2024-2025', 'goku@gmail.com', '10.163.2.39', 0, 0)*/
        $res1 = $this->prepare($save_query, array(":dcode" => (int)$dcode, ":lbcode" => (int)$lbcode, ":cjv_chalan_no" => $cjv_serial_no, ":cjv_date" => $date,":pay_mode"=>$pay_mode,":contractor_name"=>$contractor_name,":mbr_no"=>$MBR_No,":name_of_work"=>$name_of_work, ":debit_amount" => (int)$debit_amount,":credit_amount" => (int)$credit_amount,":total_amount" => (int)$credit_amount,":narration" => $narration,":scheme"=>(int)$scheme,":fin_year" => $fin_year,":user_name" => $user_name, ":ip_address" => $ip_address, ":edit_id" => 1, ":del_id" => 0), 4);
        if (!isset($res1->errorInfo)) {

            $inserted_id = $res1['sp_cj_voucher'];

            $latest_date_query = "update accounts_master.t_cj_voucher_breakup set voucher_no=:inserted_id   WHERE del_flag IS NULL and cjv_serial_no=:cjv_serial_no and dcode=:dcode and lbcode=:lbcode";
            $sel_dname_res=$this->prepare($latest_date_query,array(":cjv_serial_no"=> $cjv_serial_no,":dcode"=> $dcode,":lbcode"=> $lbcode,":inserted_id"=> $inserted_id),4); 


            $this->commit();
            $site_data = $this->siteData();
            $this->main_content(array(
                "STATUS" => "SUCCESS",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => $Result_Message
            ));
            
            $redirect_url=$site_data->website_url . "/project/forms/masters/List_Journal_Vouchers.php";
            //delay for redirection so that SAVED message gets displayed 
            echo "<script>
            setTimeout(function() {
                window.location.href = '$redirect_url';
            }, 1500);
            </script>";
            // header("Location: ".$site_data->website_url."/project/forms/masters/List_Journal_Vouchers.php");
            
            
            exit;
        } else {
            $this->rollback();
            $this->main_content(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Data Save Failed Due To Duplicate Entry"
            ));
            exit;
        }
    }
}
$EditContractJournalVoucher = new EditContractJournalVoucher();
if (!isset($_POST['cmd'])) {
    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        $EditContractJournalVoucher->data_save(array_merge($_POST, $_GET));
    } else {
        $EditContractJournalVoucher->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
    }
} else if (isset($_POST['cmd'])) {
    $cmd = base64_decode($_POST['cmd']);
    if ($cmd == 2) {
        $bank_code = base64_decode($_POST['bank_code']);
        $dcode = $EditContractJournalVoucher->getCurrentDistrictCode();
        $lbcode = $EditContractJournalVoucher->getCurrentLocalBodyCode();
		$sel_qry = "select b.bank_code, account_no, ifsc_code, fundname from (select bank_id, bank_code, bankbranch_id, account_no, fund_id, ifsc_code from accounts_master.t_bank_account where del_flag is null and isactive = :isactive and bankaccount_id=:bank_code and dcode=:dcode and lbcode=:lbcode) a left join 
        (select bank_id, bank_name_en, bank_code from accounts_master.m_bank) as b on a.bank_id=b.bank_id
        left join 
        accounts_master.m_fund as e on a.fund_id=e.fundid;";		
		$sel_qry_res=$EditContractJournalVoucher->prepare($sel_qry,array(":bank_code"=>$bank_code, ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1),4);
        $Result['STATUS'] = 'SUCCESS';
        $Result['DATA'] = $sel_qry_res['bank_code'] .' '. $sel_qry_res['account_no'];
        echo json_encode($Result);
        exit;}
    if ($cmd == 3) {
        // $collection_amount = base64_decode($_POST['collection_amount']);
        $cjv_serial_no = base64_decode($_POST['cjv_serial_no']);
        $bank_code = base64_decode($_POST['bank_code']);
        $bank_head = base64_decode($_POST['bank_head']);        
        $debit_edit_id = isset($_POST['edit_id']) && $_POST['edit_id']!=''?base64_decode($_POST['edit_id']):0;
        $debit_delete_id = isset($_POST['delete_id']) && $_POST['delete_id']!=''?base64_decode($_POST['delete_id']):0;
        $amount = base64_decode($_POST['amount']);
        $dcode = $EditContractJournalVoucher->getCurrentDistrictCode();
        $lbcode = $EditContractJournalVoucher->getCurrentLocalBodyCode();
        $user_name = $EditContractJournalVoucher->getCurrentUser();
        $ip_address = $EditContractJournalVoucher->getIpAddress();
        $fin_year = $EditContractJournalVoucher->getFinYear();
        //$fin_year='2024-2025';
        $EditContractJournalVoucher->beginTransaction();
        if($debit_delete_id == 0 && $debit_edit_id == 0){
            // $save_query = "select * from accounts_master.sp_Contract_Journal_Vouchers_breakup(:dcode, :lbcode, :account_type,:bank_code, :bank_head, :debit_amount, :credit_amount, :fin_year, :cjv_serial_no, :user_name, :ip_address, :edit_id, :del_id)";
            // $res1 = $EditContractJournalVoucher->prepare($save_query, array(":dcode" => $dcode, ":lbcode" => $lbcode, ":account_type" => 1, ":bank_code" => $bank_code, ":bank_head" => $bank_head, ":debit_amount" => $amount, ":credit_amount"=>NULL, ":user_name" => $user_name, ":ip_address" => $ip_address, ":edit_id" => $debit_edit_id, ":del_id" => $debit_delete_id, ":fin_year"=>$fin_year, ":cjv_serial_no" =>$cjv_serial_no),4);

             $save_query = "SELECT accounts_master.sp_cj_voucher_breakup(:acc_type,:acc_code,:debit_acc_head,:amount, :fin_year, :cjv_serial_no,:dcode,:lbcode,:statecode,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
        $res1 = $EditContractJournalVoucher->prepare($save_query, [":acc_type" => 2,":acc_code" => $bank_code,":debit_acc_head" => $bank_head,
":amount" => $amount, ":fin_year"=>$fin_year, ":cjv_serial_no" =>$cjv_serial_no,":dcode" => $dcode,":lbcode" => $lbcode,":statecode" => 33,":getCurrentUser" => $user_name,":getIpAddress" => $ip_address,":edit_id" => $debit_edit_id,":delete_id" => $debit_delete_id], 4);





        }else if($debit_delete_id == 0 && $debit_edit_id != 0){
            $save_query = "SELECT accounts_master.sp_cj_voucher_breakup(:acc_type,:acc_code,:debit_acc_head,:amount, :fin_year, :cjv_serial_no,:dcode,:lbcode,:statecode,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
        $res1 = $EditContractJournalVoucher->prepare($save_query, [":acc_type" => 2,":acc_code" => $bank_code,":debit_acc_head" => $bank_head,
":amount" => $amount, ":fin_year"=>$fin_year, ":cjv_serial_no" =>$cjv_serial_no,":dcode" => $dcode,":lbcode" => $lbcode,":statecode" => 33,":getCurrentUser" => $user_name,":getIpAddress" => $ip_address,":edit_id" => $debit_edit_id,":delete_id" => $debit_delete_id], 4);
        }  if($debit_delete_id != 0 && $debit_edit_id == 0){
            $save_query = "SELECT accounts_master.sp_cj_voucher_breakup(:acc_type,:acc_code,:debit_acc_head,:amount, :fin_year, :cjv_serial_no,:dcode,:lbcode,:statecode,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
        $res1 = $EditContractJournalVoucher->prepare($save_query, [":acc_type" => 2,":acc_code" => $bank_code,":debit_acc_head" => $bank_head,
":amount" => $amount, ":fin_year"=>$fin_year, ":cjv_serial_no" =>$cjv_serial_no,":dcode" => $dcode,":lbcode" => $lbcode,":statecode" => 33,":getCurrentUser" => $user_name,":getIpAddress" => $ip_address,":edit_id" => $debit_edit_id,":delete_id" => $debit_delete_id], 4);
        }
       

		$sel_qry = "select cjv_breakupid,debit_account_id, debit_account_head,account_code, debit_amount, b.account_head_name_en from (select cjv_breakupid, debit_account_id, debit_account_head, debit_amount from accounts_master.t_cj_voucher_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and cjv_chalan_no=:cjv_serial_no  and account_type=:account_type and fin_year=:fin_year)a left join (SELECT account_head_id, old_account_head_code as account_code, account_head_name_en FROM accounts_master.m_account_head)b on a.debit_account_id=b.account_head_id;";		
		$sel_qry_res=$EditContractJournalVoucher->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":cjv_serial_no"=>$cjv_serial_no, ":account_type"=>2, ":fin_year"=>$fin_year),2);
        ob_start();
        foreach($sel_qry_res as $sel_qry_row){
            ?>
<tr>
    <td><?php echo htmlentities($sel_qry_row['account_code']); ?></td>
    <td><?php echo htmlentities($sel_qry_row['debit_account_head']); ?></td>
    <td><?php echo htmlentities($sel_qry_row['debit_amount']); ?>
        <input type="hidden" name="debit_bank_id" value="<?php echo htmlentities($sel_qry_row['cjv_breakupid']);?>"
            class="bank_id" />
    </td>
    <td>
        <input type="button" id="btn_debit_edit" name="btn_debit_edit" value="Edit"
            class="btn btn-md text-white font-weight-bold btn-success" style="font-size: small;">

        <!-- <input type="button" id="btn_debit_delete" name="btn_debit_delete" value="Delete"
            class="btn btn-md text-white font-weight-bold btn-danger" style="font-size: small;"> -->
    </td>
</tr>
<?php
        }
         $debit_amount = array_sum(array_column($sel_qry_res, 'debit_amount'));
   
            $ob_contents = ob_get_contents();
		    ob_clean();
            $EditContractJournalVoucher->commit();
		    $Result_Data['STATUS']='SUCCESS';
		    $Result_Data['debit_data_table']=$ob_contents;
            $Result_Data['debit_amount'] = $debit_amount;
        
        echo json_encode($Result_Data);
        exit;
    }
    if ($cmd == 4) {
        $Result=array();
        $id = base64_decode($_POST['id']);
        $account_type = base64_decode($_POST['account_type']);
        $dcode = $EditContractJournalVoucher->getCurrentDistrictCode();
        $lbcode = $EditContractJournalVoucher->getCurrentLocalBodyCode();
		$sel_qry = "select cjv_breakupid, debit_account_id, debit_account_head, debit_amount, credit_amount from accounts_master.t_cj_voucher_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and account_type=:account_type and cjv_breakupid=:cjv_breakupid;";		
		$sel_qry_res=$EditContractJournalVoucher->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":account_type"=>$account_type, ":cjv_breakupid"=>$id),4);
        $Result['STATUS'] = 'SUCCESS';
        $Result['bank_code'] = $sel_qry_res['debit_account_id'];
        $Result['bank_head'] = $sel_qry_res['debit_account_head'];
        $Result['debit_amount'] = $sel_qry_res['debit_amount'];  
        $Result['credit_amount'] = $sel_qry_res['credit_amount'];         
        $Result['cjv_breakupid'] = $sel_qry_res['cjv_breakupid'];
        echo json_encode($Result);
        exit;
    }
    if ($cmd == 8) {
        $Result=array();
        $id = base64_decode($_POST['id']);
        $account_type = base64_decode($_POST['account_type']);
        $dcode = $EditContractJournalVoucher->getCurrentDistrictCode();
        $lbcode = $EditContractJournalVoucher->getCurrentLocalBodyCode();
        $sel_qry = "select cjv_breakupid, credit_account_id, credit_account_head, credit_amount from accounts_master.t_cj_voucher_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and account_type=:account_type and cjv_breakupid=:cjv_breakupid;";      
        $sel_qry_res=$EditContractJournalVoucher->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":account_type"=>$account_type, ":cjv_breakupid"=>$id),4);
        $Result['STATUS'] = 'SUCCESS';
        $Result['bank_code'] = $sel_qry_res['credit_account_id'];
        $Result['bank_head'] = $sel_qry_res['credit_account_head'];
        $Result['credit_amount'] = $sel_qry_res['credit_amount'];         
        $Result['cjv_breakupid'] = $sel_qry_res['cjv_breakupid'];
        echo json_encode($Result);
        exit;
    }
    if ($cmd == 5) {
        $Result=array();
        $id = base64_decode($_POST['id']);
        $account_type = base64_decode($_POST['account_type']);
        $dcode = $EditContractJournalVoucher->getCurrentDistrictCode();
        $lbcode = $EditContractJournalVoucher->getCurrentLocalBodyCode();
		$sel_qry = "select  cjv_breakupid, debit_account_id, debit_account_head, debit_amount, credit_amount from accounts_master.t_cj_voucher_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and account_type=:account_type and cjv_breakupid=:cjv_breakupid;";		
		$sel_qry_res=$EditContractJournalVoucher->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":account_type"=>$account_type, ":cjv_breakupid"=>$id),4);
        $Result['STATUS'] = 'SUCCESS';
        $Result['bank_code'] = $sel_qry_res['debit_account_id'];
        $Result['bank_head'] = $sel_qry_res['debit_account_head'];
        $Result['debit_amount'] = $sel_qry_res['debit_amount'];  
        $Result['credit_amount'] = $sel_qry_res['credit_amount'];       
        $Result['cjv_breakupid'] = $sel_qry_res['cjv_breakupid'];
        echo json_encode($Result);
        exit;
    }
    if ($cmd == 9) {
        $Result=array();
        $id = base64_decode($_POST['id']);
        $account_type = base64_decode($_POST['account_type']);
        $dcode = $EditContractJournalVoucher->getCurrentDistrictCode();
        $lbcode = $EditContractJournalVoucher->getCurrentLocalBodyCode();
        $sel_qry = "select  cjv_breakupid, credit_account_id, credit_account_head, credit_amount from accounts_master.t_cj_voucher_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and account_type=:account_type and cjv_breakupid=:cjv_breakupid;";     
        $sel_qry_res=$EditContractJournalVoucher->prepare($sel_qry,array( ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":account_type"=>$account_type, ":cjv_breakupid"=>$id),4);
        $Result['STATUS'] = 'SUCCESS';
        $Result['bank_code'] = $sel_qry_res['credit_account_id'];
        $Result['bank_head'] = $sel_qry_res['credit_account_head'];
        $Result['credit_amount'] = $sel_qry_res['credit_amount'];       
        $Result['cjv_breakupid'] = $sel_qry_res['cjv_breakupid'];
        echo json_encode($Result);
        exit;
    }
    if ($cmd == 6) {
        $cjv_serial_no = base64_decode($_POST['cjv_serial_no']);
        $credit_acc_code = base64_decode($_POST['credit_bank_code']); 
        $credit_acc_head = base64_decode($_POST['credit_bank_head']);   
        $credit_edit_id = isset($_POST['edit_id']) && $_POST['edit_id']!=''?base64_decode($_POST['edit_id']):0;
        $credit_delete_id = isset($_POST['delete_id']) && $_POST['delete_id']!=''?base64_decode($_POST['delete_id']):0;
        $amount = base64_decode($_POST['amount']);
        $dcode = $EditContractJournalVoucher->getCurrentDistrictCode();
        $lbcode = $EditContractJournalVoucher->getCurrentLocalBodyCode();
        $user_name = $EditContractJournalVoucher->getCurrentUser();
        $ip_address = $EditContractJournalVoucher->getIpAddress();
        $fin_year = $EditContractJournalVoucher->getFinYear();
        //$fin_year='2024-2025';
        $EditContractJournalVoucher->beginTransaction();
        if($credit_delete_id == 0 && $credit_edit_id == 0){
       

            $save_query = "SELECT accounts_master.sp_cj_voucher_breakup(:acc_type,:acc_code,:credit_acc_head,:amount, :fin_year, :cjv_serial_no,:dcode ,:lbcode,:statecode,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
            $res1 = $EditContractJournalVoucher->prepare($save_query, [":acc_type" => 1,":acc_code" => $credit_acc_code,":credit_acc_head" => $credit_acc_head,
                ":amount" => $amount, ":fin_year"=>$fin_year, ":cjv_serial_no" =>$cjv_serial_no,":dcode" => $dcode,":lbcode" => $lbcode,":statecode" => 33,":getCurrentUser" => $user_name,":getIpAddress" => $ip_address,":edit_id" => $credit_edit_id,":delete_id" => $credit_delete_id], 4);


        }else if($credit_delete_id == 0 && $credit_edit_id != 0){
             $save_query = "SELECT accounts_master.sp_cj_voucher_breakup(:acc_type,:acc_code,:credit_acc_head,:amount, :fin_year, :cjv_serial_no,:dcode ,:lbcode,:statecode,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
            $res1 = $EditContractJournalVoucher->prepare($save_query, [":acc_type" => 1,":acc_code" => $credit_acc_code,":credit_acc_head" => $credit_acc_head,
                ":amount" => $amount, ":fin_year"=>$fin_year, ":cjv_serial_no" =>$cjv_serial_no,":dcode" => $dcode,":lbcode" => $lbcode,":statecode" => 33,":getCurrentUser" => $user_name,":getIpAddress" => $ip_address,":edit_id" => $credit_edit_id,":delete_id" => $credit_delete_id], 4);
        }  if($credit_delete_id != 0 && $credit_edit_id == 0){
            $save_query = "SELECT accounts_master.sp_cj_voucher_breakup(:acc_type,:acc_code,:credit_acc_head,:amount, :fin_year, :cjv_serial_no,:dcode ,:lbcode,:statecode,:getCurrentUser,:getIpAddress,:edit_id,:delete_id);";
            $res1 = $EditContractJournalVoucher->prepare($save_query, [":acc_type" => 1,":acc_code" => $credit_acc_code,":credit_acc_head" => $credit_acc_head,
                ":amount" => $amount, ":fin_year"=>$fin_year, ":cjv_serial_no" =>$cjv_serial_no,":dcode" => $dcode,":lbcode" => $lbcode,":statecode" => 33,":getCurrentUser" => $user_name,":getIpAddress" => $ip_address,":edit_id" => $credit_edit_id,":delete_id" => $credit_delete_id], 4);
        }
		$sel_qry = "select cjv_breakupid,credit_account_id, credit_account_head,account_code, credit_amount, b.account_head_name_en from (select cjv_breakupid, credit_account_id, credit_account_head, credit_amount from accounts_master.t_cj_voucher_breakup where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and cjv_chalan_no=:cjv_serial_no  and account_type=:account_type and fin_year=:fin_year)a left join (SELECT account_head_id, old_account_head_code as account_code, account_head_name_en FROM accounts_master.m_account_head)b on a.credit_account_id=b.account_head_id;";		
		$sel_qry_res=$EditContractJournalVoucher->prepare($sel_qry,array( ":dcode"=>$dcode,  ":lbcode"=>$lbcode, ":isactive"=>1, ":cjv_serial_no"=>$cjv_serial_no, ":account_type"=>1, ":fin_year"=>$fin_year),2);
        ob_start();
        foreach($sel_qry_res as $sel_qry_row){
            ?>

<tr>
    <td><?php echo htmlentities($sel_qry_row['account_code']); ?></td>
    <td><?php echo htmlentities($sel_qry_row['credit_account_head']); ?></td>
    <td><?php echo htmlentities($sel_qry_row['credit_amount']); ?>
        <input type="hidden" name="credit_bank_id" value="<?php echo htmlentities($sel_qry_row['cjv_breakupid']);?>"
            class="bank_id" />
    </td>
    <td>
        <input type="button" id="btn_credit_edit" name="btn_credit_edit" value="Edit"
            class="btn btn-md text-white font-weight-bold btn-success" style="font-size: small;">

        <!-- <input type="button" id="btn_credit_delete" name="btn_credit_delete" value="Delete"
            class="btn btn-md text-white font-weight-bold btn-danger" style="font-size: small;"> -->
    </td>
</tr>
<?php
        }
        $credit_amount = array_sum(array_column($sel_qry_res, 'credit_amount'));
        $ob_contents = ob_get_contents();
		ob_clean();
        
            $EditContractJournalVoucher->commit();
            $Result_Data['STATUS']='SUCCESS';
            $Result_Data['credit_data_table']=$ob_contents;
            $Result_Data['credit_amount'] = $credit_amount;
        
        echo json_encode($Result_Data);
        exit;
    }
    /*
     if ($cmd == 7) {
        $chl_date = base64_decode($_POST['chl_date']);
        $dcode = $EditContractJournalVoucher->getCurrentDistrictCode();
        $lbcode = $EditContractJournalVoucher->getCurrentLocalBodyCode();
        $save_query = "select sum(amount) as total_amount from accounts_master.t_triplicate_chalan_details where dcode=:dcode and lbcode=:lbcode and del_flag is null and isactive=:isactive and TO_CHAR(chalan_date, 'DD-MM-YYYY')=:chalan_date;";
        $res1 = $EditContractJournalVoucher->prepare($save_query, array(":dcode" => $dcode, ":lbcode" => $lbcode, ":chalan_date" => $chl_date, ":isactive" => 1),4);
        echo $res1['total_amount'];
        exit;
    }
    */
   
    if ($cmd == 10) {
        $dcode = $EditContractJournalVoucher->getCurrentDistrictCode();
        $lbcode = $EditContractJournalVoucher->getCurrentLocalBodyCode(); 
        $voucher_type=base64_decode($_POST['type']);
        list($date_dateofreceived,$month_dateofreceived,$year_dateofreceived)=explode('-',base64_decode($_POST['cjv_date']));
            $chalan_date=$year_dateofreceived.'-'.$month_dateofreceived.'-'.$date_dateofreceived;
       
        
          
      $bpv_check_query = "SELECT COUNT(*) as cnt FROM accounts_master.t_bank_payment_voucher 
                    WHERE del_flag IS NULL AND dcode = :dcode AND lbcode = :lbcode 
                    AND voucher_date = :voucher_date AND voucher_type=:voucher_type";

        $bpv_check_res = $EditContractJournalVoucher->prepare($bpv_check_query, array(
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
     else{
         $latest_date_query = 'SELECT cjv_date FROM accounts_master.t_cj_voucher WHERE del_flag IS NULL  and dcode=:dcode and lbcode=:lbcode ORDER BY cjv_date DESC LIMIT 1';
         //$latest_date_query = 'SELECT COUNT(*) FROM accounts_master.t_cj_voucher WHERE del_flag IS NULL  and dcode=:dcode and lbcode=:lbcode';
            $sel_dname_res=$EditContractJournalVoucher->prepare($latest_date_query,array(":dcode"=> $dcode,":lbcode"=> $lbcode),4);
            //print_r($sel_dname_res);die();
                if(isset($sel_dname_res['cjv_date']))
                {
                    $chalan_date_raw = base64_decode($_POST['cjv_date']); 
           
                       $chalan_date = DateTime::createFromFormat('d-m-Y', $chalan_date_raw)->format('Y-m-d');
           
                       $latest_date = date('Y-m-d', strtotime($sel_dname_res['cjv_date']));
           
                   if (strtotime($chalan_date) < strtotime($latest_date)) {
                       $display_date = date('d-m-Y', strtotime($latest_date));
                       echo json_encode(array(
                           "STATUS" => "ERROR",
                           "MESSAGE" => "You cannot select a past skipped date. The last Voucher Entered date is: $display_date."
                       ));
                       exit;
                   }
                   else{
                    echo json_encode(["STATUS"=>"SUCCESS"]);
                    }
                }
                else{
                    echo json_encode(["STATUS"=>"SUCCESS"]);
                }
     }
        

        
        }
           $lbcode=$EditContractJournalVoucher->getCurrentLocalBodyCode();
        $dcode=$EditContractJournalVoucher->getCurrentDistrictCode();
        $fin_year=$EditContractJournalVoucher->getFinYear();
        if($cmd==11)
        {
            $cjv_id=base64_decode($_GET['id']);

            $query="select * from accounts_master.t_cj_voucher where cjv_id=:cjv_id and del_flag is null and lbcode=:lbcode and dcode=:dcode and fin_year=:fin_year";
            $params=[":cjv_id"=>$cjv_id,":lbcode"=> $lbcode,
                    ":dcode"=>$dcode,":fin_year"=>$fin_year];
            $res=$EditContractJournalVoucher->prepare($query,$params,4);

            echo json_encode($res);
            exit;

        }
       
        if($cmd==12)
        {   
            $cjv_id=base64_decode($_POST['id']);
            
            $query="select a.*,b.account_code from accounts_master.t_cj_voucher_breakup as a left join (SELECT account_head_id, old_account_head_code as account_code, account_head_name_en FROM accounts_master.m_account_head)b on a.debit_account_id=b.account_head_id where credit_amount is null and cjv_id=:cjv_id and del_flag is null and lbcode=:lbcode and dcode=:dcode and fin_year=:fin_year;";

            $params=[":cjv_id"=>$cjv_id,":lbcode"=>$lbcode,":dcode"=>$dcode,":fin_year"=>$fin_year];

            $res=$EditContractJournalVoucher->prepare($query,$params,2);
            $html="";
            foreach($res as $sel_qry_row)
            {
                $curr="<tr>".
                "<td>{$sel_qry_row['account_code']}</td>".
                "<td>{$sel_qry_row['debit_account_head']}</td>".
                "<td>{$sel_qry_row['debit_amount']}
                    <input type='hidden' name='debit_bank_id' value='{$sel_qry_row['cjv_breakupid']}' class='bank_id' />
                </td>"
                . "<td>
    <input type=\"button\" id=\"btn_debit_edit\" name=\"btn_debit_edit\" value=\"Edit\" class=\"btn btn-md text-white font-weight-bold btn-success\" style=\"font-size: small;\">
</td>".
                "</tr>";
                // <input type=\"button\" id=\"btn_debit_delete\" name=\"btn_debit_delete\" value=\"Delete\" class=\"btn btn-md text-white font-weight-bold btn-danger\" style=\"font-size: small;\">
                $html.=$curr;
            }
            echo $html;
            exit;
            /*
             <tr>
                <td><?php echo htmlentities($sel_qry_row['debit_account_id']); ?></td>
                <td><?php echo htmlentities($sel_qry_row['debit_account_head']); ?></td>
                <td><?php echo htmlentities($sel_qry_row['debit_amount']); ?>
                    <input type="hidden" name="debit_bank_id" value="<?php echo htmlentities($sel_qry_row['cjv_breakupid']);?>" class="bank_id" />
                </td>
                <td>
                    <input type="button" id="btn_debit_edit" name="btn_debit_edit" value="Edit" class="btn btn-md text-white font-weight-bold btn-success" style="font-size: small;">
                   
                    <input type="button" id="btn_debit_delete" name="btn_debit_delete" value="Delete" class="btn btn-md text-white font-weight-bold btn-danger" style="font-size: small;">
                </td>
            </tr> 
            
            */
        }
        if($cmd==13)
        {

            $cjv_id=base64_decode($_POST['voucher_no']);
            
            $query="select a.*,b.account_code from accounts_master.t_cj_voucher_breakup  as a left join (SELECT account_head_id, old_account_head_code as account_code, account_head_name_en FROM accounts_master.m_account_head)b on a.credit_account_id=b.account_head_id where debit_amount is null and cjv_id=:cjv_id and del_flag is null and lbcode=:lbcode and dcode=:dcode and fin_year=:fin_year;";

           $params=[":cjv_id"=>(int)$cjv_id,":lbcode"=>$lbcode,":dcode"=>$dcode ,":fin_year"=>$fin_year];

            $res=$EditContractJournalVoucher->prepare($query,$params,2);

            $html="";
            foreach($res as $sel_qry_row)
            {
                $curr="<tr>
                <td>" . htmlentities($sel_qry_row['account_code']) . "</td>
                <td>" . htmlentities($sel_qry_row['credit_account_head']) . "</td>
                <td>" . htmlentities($sel_qry_row['credit_amount']) . "
                    <input type=\"hidden\" name=\"credit_bank_id\" value=\"" . htmlentities($sel_qry_row['cjv_breakupid']) . "\" class=\"bank_id\" />
                </td>
                <td>
                    <input type=\"button\" id=\"btn_credit_edit\" name=\"btn_credit_edit\" value=\"Edit\" class=\"btn btn-md text-white font-weight-bold btn-success\" style=\"font-size: small;\">
                    
                </td>
            </tr>";
            // <input type=\"button\" id=\"btn_credit_delete\" name=\"btn_credit_delete\" value=\"Delete\" class=\"btn btn-md text-white font-weight-bold btn-danger\" style=\"font-size: small;\">

            $html.=$curr;
                        

            }
            echo $html;
            exit;
            /*
                <tr>
                <td><?php echo htmlentities($sel_qry_row['credit_account_id']); ?></td>
                <td><?php echo htmlentities($sel_qry_row['credit_account_head']); ?></td>
                <td><?php echo htmlentities($sel_qry_row['credit_amount']); ?>
                    <input type="hidden" name="credit_bank_id" value="<?php echo htmlentities($sel_qry_row['cjv_breakupid']);?>" class="bank_id" />
                </td>
                <td>
                    <input type="button" id="btn_credit_edit" name="btn_credit_edit" value="Edit" class="btn btn-md text-white font-weight-bold btn-success" style="font-size: small;">
                    
                    <input type="button" id="btn_credit_delete" name="btn_credit_delete" value="Delete" class="btn btn-md text-white font-weight-bold btn-danger" style="font-size: small;">
                </td>
            </tr> 
            */
        }
        if($cmd == 14)
    {   
        $cwd_id=base64_decode($_POST['cwd_id']);
        $lang_code_2d = $EditContractJournalVoucher->getCurrentUserLanguage2D();
        $query="SELECT 
                cwd.scheme_id,
                sch.scheme_name_".$lang_code_2d." AS scheme_name
            FROM accounts_master.t_contractor_work_details AS cwd
            LEFT JOIN master.m_scheme AS sch 
                ON sch.scheme_seq_id = cwd.scheme_id
            WHERE 
                cwd.del_flag IS NULL
                AND sch.del_flag IS NULL
                AND cwd.contractor_work_details_id = :cwd_id
                AND cwd.lbcode = :lbcode
                AND cwd.dcode = :dcode;
            ";
        $res=$EditContractJournalVoucher->prepare($query,[":cwd_id"=>$cwd_id,":lbcode"=>$lbcode,":dcode"=>$dcode],4);
        echo json_encode($res);
    }
}

?>