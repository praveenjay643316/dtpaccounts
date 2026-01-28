<?php
require_once  '../../config/config.php';
require_once '../../library/account_head_balance.php';
class Bank_Payment_Voucher  extends ConfigClass
{
    public $page_token = "triplicate_challan";
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
            $fin_year=$this->getFinYear();
        ?>
<script type="text/javascript">
$(document).ready(function() {
    var bpv_serial_no = $('#bpv_serial_no').val();
    $.ajax({
        url: "bank_payment_voucher.php",
        type: "POST",
        data: {
            bpv_serial_no: btoa(bpv_serial_no),
            cmd: btoa(7)
        },
        success: function(response) {
        }
    });
    $('#loading-image').hide();
    $('#bpv_date').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'yyyy-mm-dd',
        minDate: new Date('1970-01-01'),
        maxDate: new Date()
    })

    function date_yyyy_mm_dd(ele) 
    {

    let input = ele.val().trim();
    let parts = input.split("-");
    if (parts.length !== 3) {
        alert("Invalid date format. Use YYYY-MM-DD.");
        ele.val("");
        return false;
    }

    // Parse into numbers
    let year  = parseInt(parts[0], 10);
    let month = parseInt(parts[1], 10) - 1; // JS months = 0–11
    let day   = parseInt(parts[2], 10);

    // Check if it is a real calendar date
    let date = new Date(year, month, day);
    if (
        date.getFullYear() !== year ||
        date.getMonth()    !== month ||
        date.getDate()     !== day
    ) {
        alert("Invalid date or date format Use YYYY-MM-DD.");
        ele.val("");
        return false;
    }
    let minDate=null;let maxDate=null;

	const dp =ele.data("datepicker");
    if (dp) {
        // Gijgo: minDate/maxDate can be Date or string or function
        if (dp.minDate) {
            minDate = dp.minDate instanceof Date ? dp.minDate : new Date(dp.minDate);
        }
        if (dp.maxDate) {
            maxDate = dp.maxDate instanceof Date ? dp.maxDate : new Date(dp.maxDate);
        }
    } else if (typeof ele.datepicker === "function") {
        //Fall back to jQuery UI, if used
        try {
            minDate = ele.datepicker("option", "minDate");
            maxDate = ele.datepicker("option", "maxDate");
        } catch (e) {
            // ignore if not jQuery UI
        }    

    }

    // 4) Check range against *our* JS Date `date`
    if ((minDate && date < minDate) || (maxDate && date > maxDate)) {
        alert("Date must be within the allowed range.");
        ele.val("");
        return false;
    }
    return true;
}
 $('#voucher_date').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'yyyy-mm-dd',
        minDate: new Date('2025-01-04'),
        maxDate: new Date()
    });


    function dateChangeEventListerner(e) {
        $('#loading-image').show();
        const voucher_date=$('#voucher_date').val();
        const voucher_type = $("#voucher_type").val();
        if (voucher_type == '') {
            alert('select voucher type');
            $('#voucher_date').val("");
            $('#loading-image').hide();
            return false;

    } else if(voucher_date!='' && date_yyyy_mm_dd($('#voucher_date'))){
            $.ajax({
                url: "bank_payment_voucher.php",
                type: "post",
                data: {
                    "voucher_type": btoa(voucher_type),
                    "cmd": btoa(8),
                    "voucher_date": btoa(voucher_date)
                },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.grand_total !== '-') {
                            $('#loading-image').hide();
                            var info_text = "<i class='fa fa-info-circle ms-1' role='button' title='More information' data-bs-toggle='modal' data-bs-target='#infoModal'></i>";
                            $('#amount').html(data.grand_total + info_text);
                            $('#amount_hidden').val(data.grand_total);
                             $('#voucher_type_hidden').val(data.voucher_type);
                            $('#voucher_nos').val(JSON.stringify(data.voucher_nos));
                            $('#infoModal').remove();
                            $('form').append(data.html);
                        } else {
                            alert('there is no voucher has been entered in this date');
                            $("#voucher_date").val("");
                            $("#amount").html('');
                            $('#loading-image').hide();
                            return false;
                        }
                    } catch (e) {
                        console.error("Invalid JSON response:", response);
                        $('#amount').text('');
                        $('#amount_hidden').val('');
                    }
                }
            });
        }
        else{
            $('#loading-image').hide();
        }
    }
   $('#voucher_date').on('change', dateChangeEventListerner);
   
    $('#voucher_type').on('change',  function() {
        let voucher_type = $('#voucher_type').val();
        $("#amount").html('');
        $("#spec_chalan_no").html('');
        $('#voucher_date').datepicker('destroy');
         $.ajax({
            url: "bank_payment_voucher.php",
            type: "POST",
            data: {
                voucher_type: btoa(voucher_type),
                cmd: btoa(11)
            },
            success: function(curr_date) {
                if (curr_date == '-') {
                            $('#voucher_date').datepicker({
                                    uiLibrary: 'bootstrap4',
                                    format: 'yyyy-mm-dd',
                                    minDate: new Date('2025-01-04'),
                                    maxDate: new Date()
                                });                

} else {                   
                    $('#voucher_date').datepicker({
                                    uiLibrary: 'bootstrap4',
                                    format: 'yyyy-mm-dd',
                                    minDate: new Date(curr_date),
                                    maxDate: new Date()
                                });                 
                            }
                $('#voucher_date').on('change', dateChangeEventListerner);
            },
            error: function(xhr, error, status) {
                console.log(error);
                console.log(status);
            },
            dataType: 'text'
        });
    });
    $('#account_code').change(function() {
        $('#account_head').val($('option:selected', this).attr('data-desc'));
    });
    $('#bank_code').change(function() {
        $('#bank_name').val($('option:selected', this).attr('data-desc'));
    });
    $('#pay_mode').change(function() {
        $('#cheque_no').val('');
        $('#neft_no').val('');
        $('#rtgs_no').val('');
        if ($(this).val() == '2') {
            $('.pay_mode_neft').hide();
            $('.pay_mode_rtgs').hide();
            $('.pay_mode_cheque').show();
            $('#bank_branch_row').show();
        } else if ($(this).val() == '5') {
            $('.pay_mode_cheque').hide();
            $('.pay_mode_rtgs').hide();
            $('.pay_mode_neft').show();
        } else if ($(this).val() == '6') {
            $('.pay_mode_neft').hide();
            $('.pay_mode_cheque').hide();
            $('.pay_mode_rtgs').show();
        } else {
            $('.pay_mode_neft').hide();
            $('.pay_mode_cheque').hide();
            $('.pay_mode_rtgs').hide();
        }
    });
    $(document).ready(function() {
        $('input[name=voucher_type]').each(function() {
            $(this).click();
        });
    });

    // $('input[name=voucher_type]').click(function() {
    //     var type = $(this).val();
    //     $.ajax({
    //         url: "bank_payment_voucher.php",
    //         type: "post",
    //         data: {
    //             "type": btoa(type),
    //             "cmd": btoa(1)
    //         },
    //         success: function(data) {
    //             if (type == "Collection") {
    //                 $("#cash_coll_date_row").show();
    //             } else if (type == "Accounts") {
    //                 $("#cash_coll_date_row").hide();
    //             }
    //             $('#account_code').html(data);
    //         },
    //         dataType: 'html'
    //     });
    // });

    $(document).on('click', "#btn_save", function() {
        var Current_Field_id = $(this).attr('id');
        $('#' + Current_Field_id).hide();
        try {
            if ($("#bpv_serial_no").val().length == '') {
                throw {
                    msg: "Enter Chalan Serial No",
                    foc: "#bpv_serial_no"
                }
            }
            if ($("#voucher_date").val().length == '') {
                throw {
                    msg: "Enter Chalan Date",
                    foc: "#voucher_date"
                }
            }
            if ($("#pay_mode").val().length == '') {
                throw {
                    msg: "Select Payment Mode",
                    foc: "#pay_mode"
                }
            } else if ($("#pay_mode").val() == '2') {
                if ($("#cheque_no").val().length == '') {
                    throw {
                        msg: "Enter Cheque No.",
                        foc: "#cheque_no"
                    }
                }
                if ($("#bank_name").val().length == '') {
                    throw {
                        msg: "Enter Bank Name",
                        foc: "#bank_name"
                    }
                }
            } else if ($("#pay_mode").val() == '5') {
                if ($("#neft_no").val().length == '') {
                    throw {
                        msg: "Enter DD No.",
                        foc: "#neft_no"
                    }
                }
                if ($("#bank_name").val().length == '') {
                    throw {
                        msg: "Enter Bank Name",
                        foc: "#bank_name"
                    }
                }
            } else if ($("#pay_mode").val() == '6') {
                if ($("#rtgs_no").val().length == '') {
                    throw {
                        msg: "Enter ECS No.",
                        foc: "#rtgs_no"
                    }
                }
                if ($("#bank_name").val().length == '') {
                    throw {
                        msg: "Enter Bank Name",
                        foc: "#bank_name"
                    }
                }
            }
            if ($("#favour_of").val().length == '') {
                throw {
                    msg: "Enter Name and Address of Remitter",
                    foc: "#favour_of"
                }
            }
            if ($("#voucher_type").val().length == '') {
                throw {
                    msg: "Select Voucher Type",
                    foc: "#voucher_type"
                }
            }

            if ($("#amount_hidden").val().length == '') {
                throw {
                    msg: "Enter Amount",
                    foc: "#amount_hidden"
                }
            }
            if ($("#narration").val().length == '') {
                throw {
                    msg: "Enter Narration",
                    foc: "#narration"
                }
            }
            if (parseFloat($("#amount_hidden").val()) !== parseFloat($("#amount_total").val())) {
                throw {
                    msg: "Total Amount and Voucher Amount does not match. Please Verify.",
                    foc: "#amount" // optional: focus on visible input if needed
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



 /* ================= ADD / EDIT AMOUNT ================= */
$(document).on('click', "#add_amount", function () {

    var accountCode   = $("#account_code").val();
    var accountAmount = $("#account_amount").val();
    var bpv_serial_no = $("#bpv_serial_no").val();
    var voucher_date  = $("#voucher_date").val();
    var voucher_type  = $("#voucher_type").val();

    var maxAmount   = parseFloat($("#amount_hidden").val()) || 0;
    var totalAmount = parseFloat($("#amount_total").val()) || 0;
    var newAmount   = parseFloat(accountAmount) || 0;
    var oldAmount   = parseFloat($("#old_edit_amount").val()) || 0;
    var edit_id     = $("#edit_id").val();

    if (voucher_date === '') {
        alert('Enter voucher date');
        $("#voucher_date").focus();
        return false;
    }

    if (accountCode === "" || accountAmount === "") {
        alert("Please select account code and enter amount.");
        return false;
    }

    /* ===== FINAL TOTAL CALCULATION ===== */
    var finalTotal = 0;

    if (edit_id && edit_id !== "0") {
        // EDIT MODE
        finalTotal = totalAmount - oldAmount + newAmount;
    } else {
        // ADD MODE
        finalTotal = totalAmount + newAmount;
    }

    if (finalTotal > maxAmount) {
        alert("Amount should not exceed " + maxAmount);
        return false;
    }

    /* ===== AJAX SAVE ===== */
    $.ajax({
        url: 'bank_payment_voucher.php',
        type: "POST",
        dataType: "html",
        data: {
            account_code: btoa(accountCode),
            account_amount: btoa(accountAmount),
            bpv_serial_no: btoa(bpv_serial_no),
            voucher_date: btoa(voucher_date),
            edit_id: btoa(edit_id ? edit_id : 0),
            voucher_type: btoa(voucher_type),
            cmd: btoa(4)
        },
        success: function (response) {

            if (isJson(response)) {
                let res = JSON.parse(response);

                if (res.status === "success") {

                    $("#acc_code_table").html(res.html);
                    $("#amount_total").val(res.total_amount);
                    $("#acc_codes_hidden").val(res.acc_codes);

                    // reset form
                    $("#account_amount").val('');
                    $("#account_code").val('');
                    $("#edit_id").val('');
                    $("#old_edit_amount").val(0);
                    $("#add_amount").text("Add Amount");

                    alert(res.message);
                }
            } else {
                console.log("Invalid JSON response:", response);
            }
        }
    });
});

$(document).on('click', "#btn_edit", function () {

    var account_head_id = $(this).val(); // this is ID

    $.ajax({
        url: 'bank_payment_voucher.php',
        type: 'POST',
        dataType: "json",
        data: {
            account_head_id: btoa(account_head_id),
            cmd: btoa(5)
        },
        success: function (response) {

            if (response.STATUS === "success") {

                $('#account_code').val(response.account_head_code);
                $('#account_amount').val(response.account_amount);

                $('#edit_id').val(response.accounthead_breakup_id);
                $('#old_edit_amount').val(response.account_amount);

                $("#add_amount").text("Edit Amount");

            } else {
                alert("Edit fetch failed!");
            }
        }
    });
});
$(document).on('click', "#btn_del", function () {

    var del_id = $(this).val();

    $.ajax({
        url: 'bank_payment_voucher.php',
        type: 'POST',
        data: {
            del_id: btoa(del_id),
            bpv_serial_no: btoa($("#bpv_serial_no").val()),
            bpv_date: btoa($("#bpv_date").val()),
            cmd: btoa(6)
        },
        success: function (response) {

            if (isJson(response)) {
                let res = JSON.parse(response);

                if (res.status === "success") {

                    $("#acc_code_table").html(res.html);
                    $("#acc_codes_hidden").val(res.acc_codes);

                    // 🔥 IMPORTANT RESET
                    $("#amount_total").val(res.total_amount);
                    $("#edit_id").val('');
                    $("#old_edit_amount").val(0);

                    $("#account_code").val('');
                    $("#account_amount").val('');
                    $("#add_amount").text("Add Amount");
                }
            } else {
                console.log("Invalid JSON response:", response);
            }
        }
    });
});
function isJson(str) {
    return typeof str === "string" &&
        str.trim().startsWith("{") &&
        str.trim().endsWith("}");
}

    //bank_Code_On_Change
    $('#bank_code').change(function() {
        let bank_code = $(this).val();
        $.ajax({
            url: "bank_payment_voucher.php",
            type: "post",
            data: {
                cmd: btoa(9),
                bank_code: btoa(bank_code)
            },
            dataType: 'text',
            success: function(data) {
                let branch_lists_html = "<option value>choose</option>";
                let branch_lists = JSON.parse(data);
                branch_lists.forEach((branch) => {
                    let curr =
                        `<option value=${branch["branch_id"]}>${branch["branch_name"]}</option>`;
                    branch_lists_html += curr;
                });
                $("#bank_branch").html(branch_lists_html);

            },
            error: function(xhr, error, status) {
                console.error(error);
                console.error(status);
            }
        });
    });
    $('#bank_branch').change(function() {
        let bank_branch = $("#bank_branch").val();
        let bank_id = $('#bank_code').val();
        $.ajax({
            url: "bank_payment_voucher.php",
            type: "post",
            data: {

                cmd: btoa(10),
                bank_branch: btoa(bank_branch),
                bank_id: btoa(bank_id)
            },
            success: function(data) {

                if (JSON.parse(data) == '-') {
                    alert('no cheque available , please select other bank or branch');
                    $("#cheque_no").val('');
                } else {
                    $("#cheque_no").val(JSON.parse(data));
                }
            },
            error: function(xhr, error, status) {
                console.log(error);
                console.log(status);
            },
            dataType: 'text'
        });
    })

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
<style>
#loading-image {
    position: absolute;
    -moz-border-radius: 9px;
    -webkit-border-radius: 9px;
    border-radius: 9px;
    -khtml-border-radius: 9px;
    width: 50px;
    height: 20px;
    overflow: visible;
}
</style>
<div class="container mt-3">
    <form action="bank_payment_voucher.php" method="post" enctype="multipart/form-data" autocomplete="off">
        <input class="form-control form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>"
            name="<?php echo htmlentities($this->page_token); ?>"
            value="<?php echo htmlentities($this->token($this->page_token)); ?>">
        <input type="hidden" id="voucher_type_hidden" name="voucher_type">
        <input type="hidden" id="voucher_nos" name="voucher_nos">
        <input type="hidden" id="old_edit_amount" value="0">
        <div class="card">
            <div class="card-body">

                <div id="loading-image" align="center" style="padding-left:500px">
                    <img src="<?php echo htmlentities($site_data->website_url);?>/images/ajax_loader_blue_256.gif"
                        alt="Loading..." /><br />
                </div>

                <?php
                if (isset($post_data_array["STATUS"])) {
                    echo $this->ShowMessage($post_data_array["STATUS"], $post_data_array["MESSAGE"]);
                }
                ?>

                <!-- First table start -->
                <table class="table table-bordered m-0 p-0 tndtp_form_table">
                    <thead class="bg-th-form-dsg">
                        <tr>
                            <th align="center" scope="col" colspan="2">Bank Payment Voucher</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Chalan Serial No -->
                        <tr>
                            <td class="text-left font-weight-bold"><span>BPV Serial No</span></td>
                            <td>
                                <?php 
                                    $sel_qry="select max(bpv_serial_no) as id from accounts_master.t_bank_payment_voucher where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null and fin_year=:fin_year;";
                                    $sel_qry_res=$this->prepare($sel_qry, array(":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":fin_year"=>$fin_year),4);     
                                    $chalan_no=$sel_qry_res['id']+1 . "/" . $fin_year;                               
                                    echo $chalan_no; ?>
                                <input type="hidden" id="bpv_serial_no" name="bpv_serial_no"
                                    value="<?php echo $sel_qry_res['id']+1; ?>"
                                    class="form-control w-50 form-control-sm" />
                                <input type="hidden" id="bpv_chalan_no" name="bpv_chalan_no"
                                    value="<?php echo $chalan_no; ?>"
                                    class="form-control w-50 form-control-sm" />
                            </td>
                        </tr>

                        <!-- Chalan Date -->
                        <tr>
                            <td class="text-left font-weight-bold"><span>BPV Date</span></td>
                            <td>
                                <input type="text" id="bpv_date" name="bpv_date" value=""
                                    class="form-control form-control-sm user_enter_date w-50 date_yyyy_mm_dd" />
                            </td>
                        </tr>

                        <!-- Payment Mode -->
                        <tr>
                            <td class="text-left font-weight-bold"><span>Payment Mode</span></td>
                            <td>
                                <select id="pay_mode" name="pay_mode" class="form-control form-control-sm w-50">
                                    <option value="">Choose</option>
                                    <?php 
                                        $sel_payment_type="select paymenttypeid, paymenttype as paymenttype_en, paymenttype_ta from master.m_paymenttype where del_flag is null and paymenttypeid in (2,5,6)";
                                        $sel_payment_type_res=$this->prepare($sel_payment_type, array(), 2);
                                        foreach($sel_payment_type_res as $sel_payment_type_row){
                                            ?>
                                    <option value="<?php echo $sel_payment_type_row['paymenttypeid']; ?>">
                                        <?php echo $sel_payment_type_row['paymenttype_'.$lang_code_2d]; ?>
                                    </option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <!-- Bank code and name -->
                        <tr id="bank_code_row">
                            <td class="text-left font-weight-bold"><span>Bank Code</span></td>
                            <td>
                                <select id="bank_code" name="bank_code" class="form-control form-control-sm w-50">
                                    <option value="">Choose</option>
                                    <?php
                                        $sel_bank_new_id = "SELECT bank_id, bank_code, bank_name_".$lang_code_2d." FROM accounts_master.m_bank WHERE isactive = :isactive AND del_flag IS NULL AND bank_id in (select bank_id from accounts_master.m_bankbranch where lbcode=:lbcode and dcode=:dcode) ORDER BY bank_code ASC";
                                        $sel_bank_newid_res = $this->prepare($sel_bank_new_id, array(":isactive" => 1,":lbcode"=>$lbcode ,":dcode"=>$dcode), 2);
                                        foreach ($sel_bank_newid_res as $sel_bank_newid_row) {
                                    ?>
                                    <option value="<?php echo htmlentities($sel_bank_newid_row['bank_id']); ?>"
                                        data-desc="<?php echo htmlentities($sel_bank_newid_row['bank_name_'.$lang_code_2d]); ?>">
                                        <?php echo htmlentities($sel_bank_newid_row['bank_code']); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr id="bank_name_row">
                            <td class="text-left font-weight-bold"><span>Bank Name</span></td>
                            <td><input type="text" id="bank_name" name="bank_name"
                                    class="form-control form-control-sm w-50" readonly /></td>
                        </tr>
                        <tr id="bank_branch_row" style="display:none;">
                            <td class="text-left font-weight-bold"><span>Bank Branch</span></td>
                            <td class="text-left font-weight-bold">
                                <select id="bank_branch" class="form-control form-control-sm w-50" name="bank_branch">
                                </select>
                            </td>
                        </tr>
                        <!-- Cheque / DD / ECS fields -->
                        <tr class="pay_mode_cheque" style="display:none;">
                            <td class="text-left font-weight-bold"><span>Cheque No</span></td>
                            <td><input type="text" id="cheque_no" name="cheque_no"
                                    class="form-control form-control-sm w-50"  readonly></td>
                        </tr>

                        <tr class="pay_mode_neft" style="display:none;">
                            <td class="text-left font-weight-bold"><span>NEFT</span></td>
                            <td><input type="text" id="neft_no" name="neft_no"
                                    class="form-control form-control-sm w-50" /></td>
                        </tr>

                        <tr class="pay_mode_rtgs" style="display:none;">
                            <td class="text-left font-weight-bold"><span>RTGS No</span></td>
                            <td><input type="text" id="rtgs_no" name="rtgs_no"
                                    class="form-control form-control-sm w-50" /></td>
                        </tr>



                        <!-- Remitter Name and Address -->
                        <tr>
                            <td class="text-left font-weight-bold"><span>In Favour Of</span></td>
                            <td>
                                <textarea id="favour_of" name="favour_of" rows="4" cols="50"
                                    class="form-control w-50 form-control-sm name_eng_with_space "></textarea>
                                <span>Max 250 Characters</span>
                            </td>
                        </tr>

                        <!-- Cash From -->
                        <tr>
                            <td class="text-left font-weight-bold"><span>Voucher Type</span></td>
                            <td>
                                <select id="voucher_type" name="voucher_type" class="form-control form-control-sm w-50">
                                    <option value="">Choose</option>
                                    <?php

                                        $sel_bank_new_id = "SELECT voucher_id, voucher_type_en, voucher_type_".$lang_code_2d." FROM accounts_master.m_voucher_type WHERE isactive = :isactive AND del_flag IS NULL and voucher_id in (1,2,3,4) ORDER BY voucher_id ASC";
                                        $sel_bank_newid_res = $this->prepare($sel_bank_new_id, array(":isactive" => 1), 2);
                                        foreach ($sel_bank_newid_res as $sel_bank_newid_row) {
                                    ?>
                                    <option value="<?php echo htmlentities($sel_bank_newid_row['voucher_id']); ?>"
                                        data-desc="<?php echo htmlentities($sel_bank_newid_row['voucher_type_'.$lang_code_2d]); ?>">
                                        <?php echo htmlentities($sel_bank_newid_row['voucher_type_'.$lang_code_2d]); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-weight-bold"><span>Voucher Collection Date</span></td>
                            <td><input type="text" id="voucher_date" name="voucher_date"
                                    class="form-control form-control-sm user_enter_date w-50 date_yyyy_mm_dd" /></td>
                        </tr>

                        <!-- Amount -->
                        <tr>
                            <td class="text-left font-weight-bold"><span>Voucher Amount</span></td>
                            <td>
                                <input type="hidden" id="amount_hidden" name="amount_hidden"
                                    class="form-control form-control-sm w-50" />
                                <input type="hidden" id="amount_total" name="amount_total"
                                    class="form-control form-control-sm w-50" />
                                <input type="hidden" id="acc_codes_hidden" name="acc_codes_hidden"
                                    class="form-control form-control-sm w-50" />

                                <span id="amount"></span><span id="spec_chalan_no"></span>
                            </td>
                        </tr>

                        <!-- Account Code -->
                        <tr>
                            <td class="text-left font-weight-bold"><span>Account Code</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <select id="account_code" name="account_code"
                                        class="form-control form-control-sm w-25">
                                        <option value="" DisplayLabelID="255">Choose</option>
                                        <?php
                                    $res = $this->Select_Account_Head_Code(1,10);
                                    
                                    foreach($res as $sel_dname_key=>$sel_dname_row)
                                    {
                                    ?>
                                        <option value="<?php echo htmlentities($sel_dname_row['account_head_id']); ?>">
                                            <?php echo htmlentities($sel_dname_row['old_code']." - ". $sel_dname_row['account_head_name_en'] . "(".$sel_dname_row['new_code'] . ")"); ?>
                                        </option>
                                        <?php	
                                    } ?>
                                    </select>

                                    Add Amount
                                    <input type="text" id="account_amount" name="account_amount"
                                        class="form-control form-control-sm w-25 number_field" placeholder="Enter Amount">
                                    <input type="hidden" id="edit_id" name="edit_id"
                                        class="form-control form-control-sm w-50" value='' />

                                    <button type="button" name="add_amount" id="add_amount"
                                        class="btn btn-primary btn-sm">Add Amount</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- First table end -->
                <br />
                <!-- Second table for account details -->
                <div class="container">
                    <table class="table table-bordered m-0 p-0 table-striped tndtp_report_table">
                        <thead>
                            <tr>

                                <td>Account Code </td>
                                <td>Account Head </td>
                                <td>Amount </td>
                                <td>Edit </td>
                                <td>Delete </td>

                            </tr>
                        </thead>
                        <tbody id="acc_code_table">


                        </tbody>
                    </table>
                </div>
                <br />
                <!-- Third table for Narration, Print and Save -->
                <table class="table table-bordered m-0 p-0 tndtp_form_table">
                    <tbody>
                        <tr>
                            <td class="text-left font-weight-bold"><span>Narration</span></td>
                            <td>
                                <textarea id="narration" name="narration" rows="4" cols="50"
                                    class="form-control w-50 form-control-sm name_eng_with_space"></textarea>
                                <span>Max 250 Characters</span>
                            </td>
                        </tr>
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
        $this->Template($this->getCurrentUserTemplate() != "" ? $this->getCurrentUserTemplate() : "Template1", "Adjust Triplicate Challan Form", $ob_output_main_contents, array(), array('page_id' => 12));
    }
    public function data_save($save_data)
    {
        // TOKEN VALIDATE
        // if (!$this->validateToken($this->page_token, $save_data[$this->page_token])) {
        //     $this->main_content(array_merge(array(
        //         "STATUS" => "ERROR",
        //         "STATUS_TYPE" => "FIELD",
        //         "FIELD_NAME" => $this->page_token,
        //         "MESSAGE" => "Invalid Token"
        //     ), $save_data));
        //     exit;
        // }else{
		// 	unset($_SESSION[$this->page_token]);
		// }
        $statecode = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();
		
        $cheque_no=$bank_name=$bank_code=$neft_no=$rtgs_no=NULL;
		if (isset($save_data['bpv_serial_no']) && $save_data['bpv_serial_no']!='') {
			$bpv_serial_no = $save_data['bpv_serial_no'];
            $bpv_serial_noValidation = $this->Field_Validation(
				array(
					'Field_Type' => 'number',
					'Field_Value' => $bpv_serial_no,
					'Field_Name' => 'bpv_serial_no',
					'Field_Max_length' => '5',
					'Field_Label_Name' => 'BPV Serial Number',
				)
			);
			if ($bpv_serial_noValidation['Status'] == "Error") {
				$this->main_content(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "bpv_serial_no",
					"MESSAGE" => $bpv_serial_noValidation['Message']
				), $save_data));
            }
		}else{
			$this->main_content(array_merge(array(
				"STATUS" => "ERROR",
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "bpv_serial_no",
				"MESSAGE" => 'Missing Serail Number'
			), $save_data));
			exit;
		}

        if (isset($save_data['bpv_chalan_no']) && $save_data['bpv_chalan_no']!='') {
			$bpv_chalan_no = $save_data['bpv_chalan_no'];
            $bpv_chalan_noValidation = $this->Field_Validation(
				array(
					'Field_Type' => 'number_slash_hyphen',
					'Field_Value' => $bpv_chalan_no,
					'Field_Name' => 'bpv_chalan_no',
					'Field_Max_length' => '15',
					'Field_Label_Name' => 'BPV Chalan Number',
				)
			);
			if ($bpv_chalan_noValidation['Status'] == "Error") {
				$this->main_content(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "bpv_chalan_no",
					"MESSAGE" => $bpv_chalan_noValidation['Message']
				), $save_data));
            }
		}else{
			$this->main_content(array_merge(array(
				"STATUS" => "ERROR",
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "bpv_chalan_no",
				"MESSAGE" => 'Missing Chalan Number'
			), $save_data));
			exit;
		}
        if (isset($save_data['bpv_date']) && $save_data['bpv_date']!='') {
            list($date_dateofreceived,$month_dateofreceived,$year_dateofreceived)=explode('-',$save_data['bpv_date']);
			$bpv_date=$year_dateofreceived.'-'.$month_dateofreceived.'-'.$date_dateofreceived;
            $bpv_date= $save_data['bpv_date'];
            $dateValidation = $this->Field_Validation(
                array(
                    'Field_Type' => 'date',
                    'Field_Value' => $save_data['bpv_date'],
                    'Field_Name' => 'date',
                    'Field_Format' => 'yyyy-mm-dd',
                    'Field_Label_Name' => 'Date',
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


			
		if (isset($save_data['pay_mode']) && $save_data['pay_mode']!='') {
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
		}else{
			$this->main_content(array_merge(array(
				  "STATUS" => "ERROR",
				  "STATUS_TYPE" => "FIELD",
				  "FIELD_NAME" => "pay_mode",
				  "MESSAGE" => 'Select Payment Mode'
			  ), $save_data));
			  exit;
		}
        if (isset($save_data['bank_code']) && $save_data['bank_code']!='') {
			$bank_code = $save_data['bank_code'];
			$bank_codeValidation = $this->Field_Validation(
				array(
					'Field_Type' => 'number',
					'Field_Value' => $bank_code,
					'Field_Name' => 'bank_code',
					'Field_Max_length' => '60',
					'Field_Label_Name' => 'Bank Code',
				)
			);
			if ($bank_codeValidation['Status'] == "Error") {
				$this->main_content(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "bank_code",
					"MESSAGE" => $bank_codeValidation['Message']
				), $save_data));
				exit;
			}
		}else{
			$this->main_content(array_merge(array(
				"STATUS" => "ERROR",
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "bank_code",
				"MESSAGE" => 'Select Bank Code'
			), $save_data));
			exit;
		}

		if (isset($save_data['pay_mode']) && $save_data['pay_mode'] == "2") {
			if (isset($save_data['cheque_no']) && $save_data['cheque_no']!='') {
				$cheque_no = $save_data['cheque_no'];    
				$cheque_noValidation = $this->Field_Validation(
					array(
						'Field_Type' => 'text_number',
						'Field_Value' => $cheque_no,
						'Field_Name' => 'cheque_no',
						'Field_Max_length' => '6',
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
			}else{
				$this->main_content(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "cheque_no",
					"MESSAGE" => 'Enter Cheque Number'
				), $save_data));
				exit;
			}
			
			$neft_no=$rtgs_no=NULL;
		}
		if (isset($save_data['pay_mode']) && $save_data['pay_mode'] == "5") {
			if (isset($save_data['neft_no']) && $save_data['neft_no']!='') {
				$neft_no = $save_data['neft_no'];
				$neft_noValidation = $this->Field_Validation(
					array(
						'Field_Type' => 'text_number',
						'Field_Value' => $neft_no,
						'Field_Name' => 'neft_no',
						'Field_Max_length' => '10',
						'Field_Label_Name' => 'DD Number',
					)
				);
				if ($neft_noValidation['Status'] == "Error") {
					$this->main_content(array_merge(array(
						"STATUS" => "ERROR",
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "neft",
						"MESSAGE" => $neft_noValidation['Message']
					), $save_data));
					exit;
				}
			}else{
				$this->main_content(array_merge(array(
					  "STATUS" => "ERROR",
					  "STATUS_TYPE" => "FIELD",
					  "FIELD_NAME" => "neft",
					  "MESSAGE" => 'Enter neft Number'
				  ), $save_data));
				  exit;
			}
			
			$rtgs_no=$cheque_no=NULL;
		}
		if (isset($save_data['pay_mode']) && $save_data['pay_mode'] == "6") {
			if (isset($save_data['rtgs_no']) && $save_data['rtgs_no']!='') {
				$rtgs_no = $save_data['rtgs_no'];
				$rtgs_noValidation = $this->Field_Validation(
					array(
						'Field_Type' => 'text_number',
						'Field_Value' => $rtgs_no,
						'Field_Name' => 'rtgs_no',
						'Field_Max_length' => '10',
						'Field_Label_Name' => 'ECS Number',
					)
				);
				if ($rtgs_noValidation['Status'] == "Error") {
					$this->main_content(array_merge(array(
						"STATUS" => "ERROR",
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "rtgs_no",
						"MESSAGE" => $rtgs_noValidation['Message']
					), $save_data));
					exit;
				}
			}else{
				$this->main_content(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "rtgs_no",
					"MESSAGE" => 'Enter RTGS Number'
				), $save_data));
				exit;
			}
			
			$cheque_no=$neft_no=NULL;
		}
		
		
		if (isset($save_data['voucher_type']) && $save_data['voucher_type']!='') {
			$voucher_type = $save_data['voucher_type'];
			$voucher_typeValidation = $this->Field_Validation(
				array(
					'Field_Type' => 'number',
					'Field_Value' => $voucher_type,
					'Field_Name' => 'voucher_type',
					'Field_Max_length' => '100',
					'Field_Label_Name' => 'voucher_type',
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

            if (isset($save_data['voucher_date']) && $save_data['voucher_date']!='') {
                $voucher_date=$save_data['voucher_date'];
                $dateValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'date',
                        'Field_Value' => $voucher_date,
                        'Field_Name' => 'date',
                        'Field_Format' => 'yyyy-mm-dd',
                        'Field_Label_Name' => 'voucher date',
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
		}else{
            $this->main_content(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "voucher_type",
					"MESSAGE" => 'Select Voucher Type'
				), $save_data));
				exit;
        }
		
        $amount = $save_data['amount_hidden'];
        $account_code = $save_data['acc_codes_hidden'];
        
        
		if (isset($save_data['narration']) && $save_data['narration']!='') {
			$narration = $save_data['narration'];
			// $narrationValidation = $this->Field_Validation(
			// 	array(
			// 		'Field_Type' => 'text_space',
			// 		'Field_Value' => $narration,
			// 		'Field_Name' => 'narration',
			// 		'Field_Max_length' => '60',
			// 		'Field_Label_Name' => 'narration',
			// 	)
			// );
			// if ($narrationValidation['Status'] == "Error") {
			// 	$this->main_content(array_merge(array(
			// 		"STATUS" => "ERROR",
			// 		"STATUS_TYPE" => "FIELD",
			// 		"FIELD_NAME" => "narration",
			// 		"MESSAGE" => $narrationValidation['Message']
			// 	), $save_data));
			// 	exit;
			// }
		}else{
			 $this->main_content(array_merge(array(
				  "STATUS" => "ERROR",
				  "STATUS_TYPE" => "FIELD",
				  "FIELD_NAME" => "narration",
				  "MESSAGE" => 'Enter Narration'
			  ), $save_data));
			  exit;
		}
        if (isset($save_data['favour_of']) && $save_data['favour_of']!='') {
			$favour_of = $save_data['favour_of'];
			$favour_ofValidation = $this->Field_Validation(
				array(
					'Field_Type' => 'text_space',
					'Field_Value' => $favour_of,
					'Field_Name' => 'favour_of',
					'Field_Max_length' => '60',
					'Field_Label_Name' => 'Invalid favour_of',
				)
			);
			if ($favour_ofValidation['Status'] == "Error") {
				$this->main_content(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "favour_of",
					"MESSAGE" => $favour_ofValidation['Message']
				), $save_data));
				exit;
			}
		}else{
			 $this->main_content(array_merge(array(
				  "STATUS" => "ERROR",
				  "STATUS_TYPE" => "FIELD",
				  "FIELD_NAME" => "favour_of",
				  "MESSAGE" => 'Enter favour_of'
			  ), $save_data));
			  exit;
		}

        $Result_Message = "Data Saved SuccessFully";
        $this->beginTransaction();
		$site_data = $this->siteData();
        $pp_assessment_initiation = "accounts_master.sp_bank_payment_voucher";
		$edit_id=$del_id=0;
        $user_name = $this->getCurrentUser();
        $ip_address = $this->getIpAddress(); 
        $statecode=33;
        $bank_branch_id=$save_data['bank_branch'];
        $fin_year=$this->getFinYear();
        $voucherType = (int)$_POST['voucher_type'];
        $voucherNos = json_decode($_POST['voucher_nos'], true);

        $inClause = '(' . implode(',', array_map('intval', $voucherNos)) . ')';
       
        $save_query = "select * from " . $pp_assessment_initiation . "(:statecode, :dcode, :lbcode, :bpv_serial_no,:bpv_date, :account_code, :amount, :voucher_date, :voucher_type,  :bank_code,:bank_branch_id,:cheque_no, :rtgs_no ,:neft_no, :pay_mode,:favour_of, :narration, :isactive, :user_name, :ip_address, :edit_id, :del_id, :fin_year)";
        $res1 = $this->prepare($save_query, array(":statecode" => $statecode, ":dcode" => $dcode, ":lbcode" => $lbcode, ":bpv_serial_no" => $bpv_serial_no,":bpv_date" => $bpv_date, ":voucher_date" => $voucher_date, ":pay_mode" => $pay_mode, ":cheque_no" => $cheque_no, ":bank_code" => $bank_code,":bank_branch_id"=>(int)$bank_branch_id, ":neft_no" => $neft_no, ":rtgs_no" => $rtgs_no, ":favour_of" => $favour_of, ":voucher_type" => (int)$voucher_type,  ":account_code" => $account_code, ":amount" => $amount, ":narration" => $narration, ":isactive" => 1, ":user_name" => $user_name, ":ip_address" => $ip_address, ":edit_id" => 0, ":del_id" => 0,":fin_year"=>$fin_year), 4);
        if (!isset($res1->errorInfo)) { 
        $inserted_id = $res1['sp_bank_payment_voucher'];


        switch ($voucherType) {
            case 1:
                $sql = "UPDATE accounts_master.t_cj_voucher
                        SET bpv_id = :bpv_id
                        WHERE del_flag IS NULL
                        AND cjv_serial_no IN $inClause
                        AND dcode=:dcode AND lbcode=:lbcode AND fin_year=:fin_year";
                break;

            case 2:
                $sql = "UPDATE accounts_master.t_ej_voucher
                        SET bpv_id = :bpv_id
                        WHERE del_flag IS NULL
                        AND ejv_serial_no IN $inClause
                        AND dcode=:dcode AND lbcode=:lbcode AND fin_year=:fin_year";
                break;

            case 3:
                $sql = "UPDATE accounts_master.t_gj_voucher
                        SET bpv_id = :bpv_id
                        WHERE del_flag IS NULL
                        AND gjv_no IN $inClause
                        AND dcode=:dcode AND lbcode=:lbcode AND fin_year=:fin_year";
                break;

            case 4:
                $sql = "UPDATE accounts_master.t_pj_voucher
                        SET bpv_id = :bpv_id
                        WHERE del_flag IS NULL
                        AND pjv_serial_no IN $inClause
                        AND dcode=:dcode AND lbcode=:lbcode AND fin_year=:fin_year";
                break;
        }
        $res2=$this->prepare($sql, [
        ":bpv_id" => $inserted_id,
        ":dcode" => $dcode,
        ":lbcode" => $lbcode,
        ":fin_year" => $fin_year
    ], 4);
   


            
            $latest_date_query = "update accounts_master.t_bpv_accounthead_breakup set bpv_id=:inserted_id WHERE del_flag IS NULL and bpv_voucher_no =:bpv_chalan_no and dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year";
            $sel_dname_res=$this->prepare($latest_date_query,array(":bpv_chalan_no"=> $bpv_chalan_no,":dcode"=> $dcode,":lbcode"=> $lbcode,":inserted_id"=> $inserted_id, ":fin_year"=>$fin_year),4);             
            $account_head_balance_obj=new Account_head_balance();
            $this->beginTransaction();
            try
            {
                $account_head_balance_obj->update_bank_payment_voucher_head_amount($bpv_chalan_no,False);
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
				?>
                <script>
                alert("Data Saved SuccessFully");
                </script>
                <?php
				header("Location: ".$site_data->website_url."/project/forms/masters/bank_payment_voucher_receipt.php?id=".base64_encode($res1['sp_bank_payment_voucher']) ); 
				exit();	
			
        } else {
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
$bank_payment_voucher = new Bank_Payment_Voucher();
if (!isset($_POST['cmd'])) {
    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        $bank_payment_voucher->data_save(array_merge($_POST, $_GET));
    } else {
        $bank_payment_voucher->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
    }
} else if (isset($_POST['cmd'])) {    
    $cmd = base64_decode($_POST['cmd']);
    if ($cmd == 1) {
        if(isset($_POST['type']) && $_POST['type']!=''){
            $type= base64_decode($_POST['type']);
            $type_validation=$bank_payment_voucher->Field_Validation(
                array(
                    'Field_Type' => 'text',
                    'Field_Value' => $type,
                    'Field_Name' => 'account_type',
                    'Field_Max_length' => '60',
                    'Field_Label_Name' => 'Account Type',
                )
            );
            if ($type_validation['Status'] == "Error") 
            {
                echo json_encode(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "account_type",
                "MESSAGE" => $type_validation['Message']
                 ), $_POST));
                exit;			
            } 
        }else{
            echo json_encode(array_merge(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "account_type",
            "MESSAGE" => "Select Account Type"
            ), $_POST));
            exit;	
        }
        ?>
<option value="" DisplayLabelID="255">Choose</option>
<?php 
        $sel_dname="SELECT account_head_id, new_account_head_code,old_account_head_code,account_head_name_en,account_head_name_ta,isactive, account_type_head_id FROM accounts_master.m_account_head where del_flag is null  and isactive=:isactrive;";
        $sel_dname_res=$bank_payment_voucher->prepare($sel_dname,array( ":isactrive"=>1),2);
         foreach($sel_dname_res as $sel_dname_key=>$sel_dname_row)
        {
        ?>
<option value="<?php echo htmlentities($sel_dname_row['account_head_id']); ?>">
    <?php echo htmlentities($sel_dname_row['old_account_head_code']." - ". $sel_dname_row['account_head_name_en']); ?>
</option>
<?php	
        }
		exit;
    }
    if ($cmd == 3) {
        $dcode = $bank_payment_voucher->getCurrentDistrictCode();
        $lbcode = $bank_payment_voucher->getCurrentLocalBodyCode(); 

    list($date_dateofreceived,$month_dateofreceived,$year_dateofreceived)=explode('-',base64_decode($_POST['bpv_date']));
            $chalan_date=$year_dateofreceived.'-'.$month_dateofreceived.'-'.$date_dateofreceived;
        ?>
<?php 
        $latest_date_query = "SELECT bpv_date 
        FROM accounts_master.t_bank_payment_voucher 
        WHERE del_flag IS NULL  and dcode=:dcode and lbcode=:lbcode
        ORDER BY bpv_date DESC 
        LIMIT 1";
        $sel_dname_res=$bank_payment_voucher->prepare($latest_date_query,array(":dcode"=> $dcode,":lbcode"=> $lbcode),4);
         $chalan_date_raw = base64_decode($_POST['bpv_date']); 

            $chalan_date = DateTime::createFromFormat('d-m-Y', $chalan_date_raw)->format('Y-m-d');

            $latest_date = date('Y-m-d', strtotime($sel_dname_res['bpv_date']));

        if (strtotime($chalan_date) <= strtotime($latest_date)) {
            $display_date = date('d-m-Y', strtotime($latest_date));
            echo json_encode(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "lbcode",
                "MESSAGE" => "You cannot select a past skipped date. The last Chalan Entered date is: $display_date."
            ));
            exit;
        }
        }
        if ($cmd == 4) {
            $accountCode = base64_decode($_POST['account_code']);
            $accountAmount = base64_decode($_POST['account_amount']);
            $voucher_type = base64_decode($_POST['voucher_type']);
            $bpv_serial_no = base64_decode($_POST['bpv_serial_no']);
            $voucher_date=base64_decode($_POST['voucher_date']);
            $user_name = $bank_payment_voucher->getCurrentUser();
            $ip_address = $bank_payment_voucher->getIpAddress(); 
            $edit_id = isset($_POST["edit_id"]) ? base64_decode($_POST["edit_id"]) : 0;
            $del_id  = isset($_POST["del_id"])  ? base64_decode($_POST["del_id"])  : 0;
            $dcode = $bank_payment_voucher->getCurrentDistrictCode();
            $lbcode = $bank_payment_voucher->getCurrentLocalBodyCode(); 
            if(!isset($_POST["edit_id"])){
                $latest_date_query = "SELECT count(*) as cnt FROM accounts_master.t_bpv_accounthead_breakup WHERE del_flag IS NULL and bpv_voucher_no=:bpv_voucher_no and acc_code=:account_code and dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year";
                $sel_dname_res=$bank_payment_voucher->prepare($latest_date_query,array(":bpv_voucher_no"=> $bpv_serial_no,":account_code"=> $accountCode,":dcode"=> $dcode,":lbcode"=> $lbcode, ":fin_year"=>$fin_year ),4); 
                if ($sel_dname_res['cnt'] > 0) {
                    echo json_encode([
                        "status" => "duplicate",
                        "message" => "Account Head already exists and cannot be added twice."
                    ]);
                    exit;
                }
            }
            $pp_assessment_initiation="accounts_master.sp_bpv_accounthead_breakup";
                $fin_year=$bank_payment_voucher->getFinYear();
                $save_query = "SELECT * FROM " . $pp_assessment_initiation . "(:statecode, :dcode, :lbcode, :acc_code, :acc_amount, :voucher_type, :bpv_serial_no,:voucher_date, :isactive, :user_name, :ip_address, :edit_id, :del_id,:fin_year)";
                
                $res1 = $bank_payment_voucher->prepare($save_query, array(
                    ":statecode"      => 33,
                    ":dcode"          => $dcode,
                    ":lbcode"         => $lbcode,
                    ":acc_code"       => $accountCode,
                    ":acc_amount"     => $accountAmount,
                    ":voucher_type" => (int)$voucher_type,
                    ":bpv_serial_no" => $bpv_serial_no,
                    ":voucher_date" => $voucher_date,
                    ":isactive"       => 1,
                    ":user_name"      => $user_name,
                    ":ip_address"     => $ip_address,
                    ":edit_id"        => $edit_id,
                    ":del_id"         => $del_id,
                    ":fin_year"       =>$fin_year
                ), 4);
                if (!empty($res1)) {

                    $decoded = json_decode($res1['sp_bpv_accounthead_breakup'], true);
                    $bpv_voucher_no = $decoded['accounthead_breakup_id']['bpv_voucher_no'];



            $latest_date_query = "SELECT accounthead_breakup_id,acc_amount,acc_code FROM accounts_master.t_bpv_accounthead_breakup WHERE del_flag IS NULL and bpv_voucher_no=:bpv_voucher_no and dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year";
            $sel_dname_res=$bank_payment_voucher->prepare($latest_date_query,array(":bpv_voucher_no"=> $bpv_voucher_no,":dcode"=> $dcode,":lbcode"=> $lbcode, ":fin_year"=>$fin_year ),2); 
            $output=''; 
            $total_amount=0;
            $acc_codes = [];
            foreach($sel_dname_res as $sel_dname_key => $sel_dname_row) {
                $acc_code = $sel_dname_row['acc_code'];
            
                $latest_date_query = "SELECT account_head_id,account_head_name_en,old_account_head_code FROM accounts_master.m_account_head WHERE del_flag IS NULL and account_head_id=:accountCode_id";
                $acc_data = $bank_payment_voucher->prepare($latest_date_query, array(":accountCode_id" => $acc_code), 4);
            
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
        $return_data=json_encode([
            "status" => "success",
            "message" => "Account Head added successfully.",
            "total_amount" => $total_amount,
            "acc_codes" => $acc_codes_json,
            "html" => $output
        ]);
        echo $return_data;
        exit;	
            }
                } 
                
                if ($cmd == 5) {
                    $accounthead_breakup_id = base64_decode($_POST['account_head_id']);
                    $dcode = $bank_payment_voucher->getCurrentDistrictCode();
                    $lbcode = $bank_payment_voucher->getCurrentLocalBodyCode(); 
                    $fin_year= $bank_payment_voucher->getFinYear();
                    $latest_date_query = "SELECT acc_code,acc_amount,accounthead_breakup_id FROM accounts_master.t_bpv_accounthead_breakup WHERE del_flag IS NULL and accounthead_breakup_id=:accounthead_breakup_id and dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year";
                    $sel_dname_res=$bank_payment_voucher->prepare($latest_date_query,array(":accounthead_breakup_id"=> $accounthead_breakup_id,":dcode"=> $dcode,":lbcode"=> $lbcode, ":fin_year"=>$fin_year ),4);
                    if (!empty($sel_dname_res)) {
                    $acc_code=$sel_dname_res['acc_code'];
                    $acc_amount=$sel_dname_res['acc_amount'];
                    $accounthead_breakup_id=$sel_dname_res['accounthead_breakup_id'];
                    $return_data=json_encode([
                                'STATUS' => 'success',
                                'accounthead_breakup_id' => $accounthead_breakup_id,
                                'account_head_code' => $acc_code,
                                'account_amount' => $acc_amount,
                            ]);
                    echo $return_data;
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
                            $dcode = $bank_payment_voucher->getCurrentDistrictCode();
                            $lbcode = $bank_payment_voucher->getCurrentLocalBodyCode(); 
                            $user_name = $bank_payment_voucher->getCurrentUser();
                            $ip_address = $bank_payment_voucher->getIpAddress(); 
                            $del_id  = isset($_POST["del_id"])  ? base64_decode($_POST["del_id"])  : 0;
                            $fin_year=$bank_payment_voucher->getFinYear();
                            $bpv_serial_no=base64_decode($_POST['bpv_serial_no']);
                            $bpv_date=base64_decode($_POST['bpv_date']);
                            $pp_assessment_initiation="accounts_master.sp_bpv_accounthead_breakup";
                                ?>
<?php 
                                $save_query = "SELECT * FROM " . $pp_assessment_initiation . "(:statecode, :dcode, :lbcode, :acc_code, :acc_amount, :voucher_type, :bpv_serial_no,:voucher_date, :isactive, :user_name, :ip_address, :edit_id, :del_id,:fin_year)";
                                
                                $res1 = $bank_payment_voucher->prepare($save_query, array(
                                    ":statecode"      => 33,
                                    ":dcode"          => 0,
                                    ":lbcode"         => 0,
                                    ":acc_code"       => '',
                                    ":acc_amount"     => 0,
                                    ":voucher_type" => 0,
                                    ":bpv_serial_no" => $bpv_serial_no,
                                    ":voucher_date" => $bpv_date,
                                    ":isactive"       => 1,
                                    ":user_name"      => $user_name,
                                    ":ip_address"     => $ip_address,
                                    ":edit_id"        => 0,
                                    ":del_id"         => $del_id,
                                    ":fin_year"=>$fin_year
                                ), 4);
                                if (!empty($res1)) {                
                                    $decoded = json_decode($res1['sp_bpv_accounthead_breakup'], true);
                                    $bpv_voucher_no = $decoded['accounthead_breakup_id']['bpv_voucher_no'];   
                            $latest_date_query = "SELECT accounthead_breakup_id,acc_amount,acc_code FROM accounts_master.t_bpv_accounthead_breakup WHERE del_flag IS NULL and bpv_voucher_no=:bpv_voucher_no and dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year ";
                            $sel_dname_res=$bank_payment_voucher->prepare($latest_date_query,array(":bpv_voucher_no"=> $bpv_voucher_no,":dcode"=> $dcode,":lbcode"=> $lbcode, ":fin_year"=>$fin_year),2); 
                            $output=''; 
                            $total_amount=0;
                            $acc_codes = [];
                            foreach($sel_dname_res as $sel_dname_key => $sel_dname_row) {
                                $acc_code = $sel_dname_row['acc_code'];
                                $acc_amount = $sel_dname_row['acc_amount'];
                                $total_amount += $acc_amount;
                                $acc_codes[] = $acc_code;
                                $latest_date_query = "SELECT account_head_id,account_head_name_en,old_account_head_code FROM accounts_master.m_account_head WHERE del_flag IS NULL and account_head_id=:accountCode_id";
                                $acc_data = $bank_payment_voucher->prepare($latest_date_query, array(":accountCode_id" => $acc_code), 4);
                            
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

                                if ($cmd == 7) {        
                                    $bpv_serial_no = base64_decode($_POST['bpv_serial_no']);
                                    $dcode = $bank_payment_voucher->getCurrentDistrictCode();
                                    $lbcode = $bank_payment_voucher->getCurrentLocalBodyCode(); 
                                    $fin_year = $bank_payment_voucher->getFinyear(); 
                                        ?>
<?php 
                                        $save_query = "delete from  accounts_master.t_bpv_accounthead_breakup where bpv_voucher_no=:bpv_voucher_no and dcode=:dcode and lbcode=:lbcode and del_flag is null and fin_year=:fin_year ";
                                        
                                        $res1 = $bank_payment_voucher->prepare($save_query, array(
                                            ":bpv_voucher_no"      => $bpv_serial_no.'/'.$fin_year,
                                            ":dcode"          => $dcode,
                                            ":lbcode"         => $lbcode,
                                            ":fin_year"=>$fin_year
                                        ), 4);
                                        exit;
                                        }

                                        if ($cmd == 8) {        
                                                    $dcode = $bank_payment_voucher->getCurrentDistrictCode();
                                                    $lbcode = $bank_payment_voucher->getCurrentLocalBodyCode();
                                                    $fin_year = $bank_payment_voucher->getFinYear();
                                                    $voucher_date=base64_decode($_POST['voucher_date']);
                                                    $voucher_type=base64_decode($_POST['voucher_type']);
                                                    $voucher_table=$voucher_no='';
                                                    $voucher_table_date_field='';
                                                    $query_execution_flag=True;
                                                    switch((int)$voucher_type)
                                                    {   case 1:
                                                            $voucher_table="accounts_master.t_cj_voucher";
                                                            $voucher_table_date_field="cjv_date";
                                                            $voucher_no="cjv_serial_no";
                                                            break;
                                                        case 2:
                                                            $voucher_table="accounts_master.t_ej_voucher";
                                                            $voucher_table_date_field="ejv_date";
                                                            $voucher_no="ejv_serial_no";
                                                            break;
                                                        case 3:
                                                            $voucher_table="accounts_master.t_gj_voucher";
                                                            $voucher_table_date_field="gjv_date";
                                                            $voucher_no="gjv_no";
                                                            break;
                                                        case 4:
                                                           $voucher_table="accounts_master.t_pj_voucher";
                                                           $voucher_table_date_field="pjv_date";
                                                           $voucher_no="pjv_serial_no";
                                                           break;
                                                        default:
                                                            $query_execution_flag=False;
                                                            echo json_encode('no entries');
                                                    }
                                                    
                                                    if($query_execution_flag)
                                                    {
                                                        $query="select $voucher_no as chalan_no,
                                                                total_amount,
                                                                narration
                                                                from $voucher_table 
                                                                where dcode=:dcode and  
                                                                lbcode=:lbcode and 
                                                                del_flag is null and 
                                                                isactive=:isactive and 
                                                                $voucher_no is not null and 
                                                                fin_year = :fin_year and
                                                                $voucher_table_date_field=:voucher_date";
                                                         $res2 = $bank_payment_voucher->prepare($query, array(
                                                            ":dcode"          => $dcode,
                                                            ":lbcode"         => $lbcode,
                                                            ":voucher_date"   => $voucher_date,
                                                            ":fin_year" => $fin_year,
                                                            ":isactive" => 1
                                                        ), 2);                                                        
                                                        ob_start();
                                                ?>

                                                <div class="modal fade" id="infoModal" tabindex="-1" aria-hidden="true"  data-bs-backdrop="static">
                                                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                                        <div class="modal-content">
                                                            
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Voucher Details</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            
                                                            <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
                                                                <table class="table table-bordered table-striped tndtp_form_table m-0">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="text-center text-light" style="background-color:darkslateblue;">Voucher No</th>                                                                         
                                                                            <th class="text-center text-light" style="background-color:darkslateblue;">Narration</th>
                                                                            <th class="text-center text-light" style="background-color:darkslateblue;">Amount</th>  
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php  foreach ($res2 as $row): ?>
                                                                            <tr>
                                                                                <td class="fw-bold" align="center"><?php echo htmlspecialchars($row['chalan_no']); ?></td>
                                                                                <td class="fw-bold" align="center"><?php echo htmlspecialchars($row['narration']); ?></td>
                                                                                <td class="fw-bold" align="right"><?php echo htmlspecialchars($row['total_amount']); ?></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <?php
                                                $grand_total = array_sum(array_column($res2, 'total_amount'));
                                                $voucherNos = array_column($res2, 'chalan_no');
                                                $html = ob_get_clean();
                                                    }
                                                    echo json_encode([
                                                        "voucher_nos" => $voucherNos,
                                                        "grand_total" => $grand_total,
                                                        "voucher_type" => $voucher_type,
                                                        "html"=>$html
                                                    ]);
                                                    exit;
                                                }
                                        if($cmd==9)
                                        {
                                            $bank_id=base64_decode($_POST["bank_code"]);
                                            $dcode = $bank_payment_voucher->getCurrentDistrictCode();
                                            $lbcode = $bank_payment_voucher->getCurrentLocalBodyCode();
                                            //echo ($bank_code);
                                  
                                            
                                            $get_branch_list_query="select bankbranch_id as branch_id ,bankbranch_name as branch_name from accounts_master.m_bankbranch where bank_id=:bank_id and lbcode=:lbcode and dcode=:dcode and del_flag is null and isactive=1;";
                                            $bank_branch_list=$bank_payment_voucher->prepare($get_branch_list_query, array(
                                                ":bank_id"=> $bank_id,":lbcode"=>$lbcode,":dcode"=>$dcode
                                            ), 2);
                                            $branch_list=[];
                                            foreach($bank_branch_list as $key=>$val)
                                            {
                                                $branch_list[]=["branch_id"=>$val["branch_id"],"branch_name"=>$val["branch_name"]];
                                            }

                                            echo json_encode($bank_branch_list);

                                        }
                                        if($cmd==10)
                                        {
                                                $bank_branch=base64_decode($_POST["bank_branch"]);
                                                $bank_id=base64_decode($_POST["bank_id"]);

                                                $query="select cheque_number from accounts_master.t_bank_cheque_leaves where bank_branch_id=:branch_id and bank_id=:bank_id and isused='N' and del_flag is null order by cheque_number LIMIT 1;";

                                            
                                                $res=$bank_payment_voucher->prepare($query, array(
                                                    ":bank_id"=> $bank_id,
                                                    ":branch_id"=>$bank_branch
                                                ), 4);
                                                if(count($res)>0){
                                                    echo json_encode($res['cheque_number']);
                                                }
                                                else{
                                                    echo json_encode('-');
                                                }
                                        }
                                        if($cmd == 11)
                                        {   

                                            $dcode = $bank_payment_voucher->getCurrentDistrictCode();
                                            $lbcode = $bank_payment_voucher->getCurrentLocalBodyCode();
                                            $voucher_type=base64_decode($_POST["voucher_type"]);
                                            $query="select TO_CHAR(bpv_date, 'DD-MM-YYYY') as bpv_date from accounts_master.t_bank_payment_voucher where dcode=:dcode and lbcode=:lbcode and del_flag is null and voucher_type=:voucher_type order by bpv_id desc limit 1";

                                            $res=$bank_payment_voucher->prepare($query, array(
                                                    ":dcode"=>$dcode,
                                                    ":lbcode"=>$lbcode,
                                                    ":voucher_type"=>$voucher_type
                                                ), 4);
                                            if(isset($res['bpv_date']))
                                            {
                                                echo (date("Y-m-d", strtotime($res['bpv_date'].'+1 day')));

                                            }
                                            else{
                                                echo ('-');
                                            }
                                        }    



}
?>