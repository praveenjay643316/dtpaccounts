<?php
require_once  '../../config/config.php';
require_once __DIR__ . '/../../library/phpexcel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\IOFactory;   // also needed

class Trade_Entry_Form  extends ConfigClass
{

    public $page_token = "Op_Balance";
    public function __construct()
    {
        if (!isset($this->db)) {
        }
    }

    public function main_content($post_data_array = array())
    {
        // ========================== DOWNLOAD SAMPLE EXCEL ==========================
if (isset($_GET['download_sample'])) {

    // Prevent accidental output
    ob_clean();
    ob_start();

    $flag = base64_decode($_GET['download_sample']);
    if ($flag !== "1") {
        die("Invalid Request");
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    /* ================= HEADER ROW ================= */
    $sheet->setCellValue('A1', 'S.No');
    $sheet->setCellValue('B1', 'Mode');
    $sheet->setCellValue('C1', 'Account Code 4 Digit');
    $sheet->setCellValue('D1', 'Account Code 7 Digit');
    $sheet->setCellValue('E1', 'Amount');
    $sheet->setCellValue('F1', 'Date');
    $sheet->setCellValue('G1', 'Challan No. / JV No.');
    $sheet->setCellValue('H1', 'Name and Details');

    /* ================= MODE DROPDOWN ================= */
    $modeValidation = $sheet->getCell('B2')->getDataValidation();
    $modeValidation->setType(DataValidation::TYPE_LIST);
    $modeValidation->setErrorStyle(DataValidation::STYLE_STOP);
    $modeValidation->setAllowBlank(false);
    $modeValidation->setShowDropDown(true);
    $modeValidation->setFormula1('"Credit,Debit"');

    /* ================= DATE VALIDATION ================= */
    $dateValidation = $sheet->getCell('F2')->getDataValidation();
    $dateValidation->setType(DataValidation::TYPE_DATE);
    $dateValidation->setAllowBlank(false);
    $dateValidation->setShowInputMessage(true);
    $dateValidation->setPromptTitle('Date');
    $dateValidation->setPrompt('Enter date (DD-MM-YYYY)');

    /* ================= APPLY VALIDATION FOR ROWS ================= */
    for ($i = 2; $i <= 200; $i++) {

        // Serial Number
        $sheet->setCellValue("A$i", $i - 1);

        // Mode dropdown
        $sheet->getCell("B$i")->setDataValidation(clone $modeValidation);

        // Date validation
        $sheet->getCell("F$i")->setDataValidation(clone $dateValidation);
    }

    /* ================= AUTO COLUMN WIDTH ================= */
    foreach (range('A', 'H') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    /* ================= FREEZE HEADER ================= */
    $sheet->freezePane('A2');

    /* ================= DOWNLOAD ================= */
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="sample_opening_balance.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
// ================================ HANDLE UPLOAD ================================


if (isset($_POST['btn_upload'])) {

    $statecode  = $this->getCurrentStateCode();
    $dcode      = $this->getCurrentDistrictCode();
    $lbcode     = $this->getCurrentLocalBodyCode();
    $user_name  = $this->getCurrentUser();
    $ip_address = $this->getIpAddress();
    $fin_year   = $this->getFinYear();

    if (!empty($_FILES['upload_excel']['tmp_name'])) {

        /* ================= STORAGE PATH ================= */
        $basePath = rtrim($this->getStoragePath(), DIRECTORY_SEPARATOR);
        $upload_dir = $basePath . DIRECTORY_SEPARATOR .
                      'Document' . DIRECTORY_SEPARATOR .
                      'initiation' . DIRECTORY_SEPARATOR .
                      $dcode . DIRECTORY_SEPARATOR .
                      $lbcode . DIRECTORY_SEPARATOR;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        /* ================= MOVE FILE ================= */
        $filename    = 'upload_' . time() . '.xlsx';
        $destination = $upload_dir . $filename;

        if (!move_uploaded_file($_FILES['upload_excel']['tmp_name'], $destination)) {
            throw new Exception('File upload failed');
        }

        /* ================= INSERT UPLOAD LOG ================= */
        $sql = "INSERT INTO accounts_master.temp_op_excel_upload
                (dcode, lbcode, file_name, uploaded_date, uploaded_user, upload_flag)
                VALUES (:dcode, :lbcode, :fname, now(), :user, 0)
                RETURNING upload_id";

        $res = $this->prepare($sql, [
            ":dcode" => $dcode,
            ":lbcode"=> $lbcode,
            ":fname" => $filename,
            ":user"  => $user_name
        ], 2);

        $upload_id = $res[0]['upload_id'];

        /* ================= READ EXCEL ================= */
        $spreadsheet = IOFactory::load($destination);
        $rows = $spreadsheet->getActiveSheet()->toArray();
// echo "<pre>";
//         print_r($rows);die;

        for ($i = 1; $i < count($rows); $i++) {

            $row = $rows[$i];

            // Column mapping
            $mode       = trim($row[1] ?? '');
            $code4      = trim($row[2] ?? '');
            $code7      = trim($row[3] ?? '');
            $amount     = trim($row[4] ?? '');
            $date_excel = trim($row[5] ?? '');

            if ($amount === '' || $mode === '') {
                continue;
            }

            /* ================= MODE ================= */
            if (!in_array($mode, ['Credit', 'Debit'])) {
                continue;
            }
            $mode_type = ($mode === 'Credit') ? 1 : 2;

            /* ================= DATE (FIXED) ================= */
            $entry_date = null;

            if ($date_excel !== '') {

                // Excel numeric date
                if (is_numeric($date_excel)) {
                    $entry_date = Date::excelToDateTimeObject((float)$date_excel)
                                    ->format('Y-m-d');
                }
                // String date
                else {
                    $timestamp = strtotime($date_excel);
                    if ($timestamp !== false) {
                        $entry_date = date('Y-m-d', $timestamp);
                    }
                }
            }

            /* ================= ACCOUNT HEAD ================= */
            $query = "SELECT account_head_id
                      FROM accounts_master.m_account_head
                      WHERE 
                       old_account_head_code = :code4
                      ";

            $get_head_id = $this->prepare($query, [
                ":code4" => $code4
                
            ], 4);

            if (empty($get_head_id)) {
                continue;
            }

            $account_head_id = $get_head_id['account_head_id'];

            /* ================= CHECK EXISTING ================= */
            $checkquery = "SELECT tp_ob_cb_id
                           FROM accounts_master.m_tp_opening_closing_balance
                           WHERE dcode = :dcode
                           AND lbcode = :lbcode
                           AND account_head_id = :ahid
                           AND fin_year = :fin_year";

            $check = $this->prepare($checkquery, [
                ":dcode" => $dcode,
                ":lbcode"=> $lbcode,
                ":ahid"  => $account_head_id,
                ":fin_year" => $fin_year
            ], 4);

            $edit_id = !empty($check) ? $check['tp_ob_cb_id'] : 0;

            /* ================= SAVE ================= */
            $save_query = "SELECT * FROM accounts_master.sp_op_balance(
                :statecode, :dcode, :lbcode, :mode_type,
                :account_head, :amount, :total,
                :fin_year, :user_name, :ip_address,
                :edit_id, :del_id, :entry_date
            )";

            $params = [
                ":statecode"    => $statecode,
                ":dcode"        => $dcode,
                ":lbcode"       => $lbcode,
                ":mode_type"    => $mode_type,
                ":account_head" => $account_head_id,
                ":amount"       => $amount,
                ":total"        => $amount,
                ":fin_year"     => $fin_year,
                ":user_name"    => $user_name,
                ":ip_address"   => $ip_address,
                ":edit_id"      => $edit_id,
                ":del_id"       => 0,
                ":entry_date"   => $entry_date
            ];

            $this->prepare($save_query, $params, 4);
        }

        /* ================= MARK UPLOAD COMPLETE ================= */
        $this->prepare(
            "UPDATE accounts_master.temp_op_excel_upload
             SET upload_flag = 1
             WHERE upload_id = :id",
            [":id" => $upload_id]
        );

        echo "<script>alert('Excel Uploaded & Processed Successfully');</script>";
    }
}


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
        $fin_year = $this->issetFinYear() ? $this->getFinYear() : null;
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
            $(document).ready(function() {
				$('#account_code').change(function() {
					var acc_code = $(this).val();
                    if($('input[name="amount_type"]:checked').val() !=''){
                        var mode = $('input[name="amount_type"]:checked').val();
                    }else{
                        alert('Select Mode');
                        return false;
                    }
                    $('#account_head').html($('option:selected', this).attr('data-desc'));
					
                    /*
                    if(acc_code != ''){
                        $.ajax({
                            url: window.location.href,
                            type: "post",
                            data: {
                                "acc_code": btoa(acc_code),
                                "cmd": btoa(1)
                            },
                            success: function(data) {
                                var Result_Data = JSON.parse(data);
                                if(mode == 1){
                                    $('#credit_total').html(Result_Data['credit']);
                                    $('.credit_total').val(Result_Data['credit']);
                                }else{
                                    $('#debit_total').html(Result_Data['debit']);
                                    $('.debit_total').val(Result_Data['debit']);
                                }
                                },
                                dataType: 'html'
						});
					}
                        */
                });
                $('#date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd-mm-yyyy',
                    minDate: new Date('01-01-1970'),
                    maxDate: new Date()
                });
                /*
                $('input[name="amount_type"]').click(function() {
					var mode = $(this).val();		
					if(mode != ''){
                        <?php #print_r($_SERVER["REQUEST_URI"]); ?>
                        $.ajax({
                            url: window.location.href,
                            type: "post",
                            data: {
                                "mode": btoa(mode),
                                "cmd": btoa(2)
                            },
                            success: function(data) {
                                var Result_Data=JSON.parse(data);
                                $('#accounts_data').removeClass('d-none');
                                $('#account_details').html(Result_Data['account_data_table']);
                                $('#account_code').html(Result_Data['account_code']);
                                if(mode == 1){
                                    $('#credit_total').html(Result_Data['total']);
                                    $('.credit_total').val(Result_Data['total']);
                                }else{
                                    $('#debit_total').html(Result_Data['total']);
                                    $('.debit_total').val(Result_Data['total']);
                                }
                            },
                            dataType: 'html'
						});
					}
                });
                */
                    $(document).on('click', "#btn_save", function() {
                        var Current_Field_id = $(this).attr('id');
                        $('#' + Current_Field_id).hide();
                        try {
                            var edit_id= <?php echo isset($post_data_array['edit_id'])?base64_decode($post_data_array['edit_id']):0 ?>;
                            var del_id= <?php echo isset($post_data_array['del_id'])?base64_decode($post_data_array['del_id']):0 ?>;
                            if ($('input:radio[name=amount_type]:checked').length == 0) {
                                throw {
                                    msg: "Choose Mode",
                                    foc: "#credit"
                                }
                            }else{
                                var mode = $('input:radio[name=amount_type]:checked').val();
                                if(mode == 1){
                                    var total = $("#credit_total").val();
                                }else{
                                    var total = $("#debit_total").val(); 
                                }
                            }
                            if($('#date').val().length==0  &&  !(/^\d{2}-\d{2}-\d{4}$/.test($('#date').val())) )
                            {
                                throw{
                                    msg:'enter valid date',
                                    foc:'#date'
                                }
                            }
                            if ($("#account_code").val().length == '') {
                                throw {
                                    msg: "Select Account Code",
                                    foc: "#account_code"
                                }
                            }  else{
                                var acc_code=$("#account_code").val();
                            }
                            if ($("#amount").val().length == '') {
                                throw {
                                    msg: "Enter Amount",
                                    foc: "#amount"
                                }
                            }else{
                                var amount = $("#amount").val();
                            }
                            
                            $.ajax({
                                url: "Op_Balance.php",
                                type: "post",
                                data: {
                                    "account_code": btoa(acc_code),
                                    "amount": btoa(amount),
                                    "amount_type": btoa(mode),
                                    "total":btoa(total),
                                    "edit_id":btoa(edit_id),
                                    "del_id":btoa(del_id),
                                    "cmd": btoa(4)
                                },
                                success: function(data) {
                                    //console.log(data);
                                    //location.reload();
                                },
                                dataType: 'html'
						    });
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
                width: 80%;
            }
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
            if (isset($post_data_array["acct_id"])){
              $taxMap = [
                3003 => ['propertytax.t_pp_assessmentdemand', 'totaldemand', 'paidstatus', 'fin_year'],
                3006 => ['professionaltax.prof_demand_details', 'profession_tax', 'paid', 'financialyear'],
                3010 => ['nontax.t_nt_assessment_demand', 'nontax_amount', 'paid', 'fin_year'],
                3012 => ['tradelicense.t_tl_assessment_demand', 'traders_rate', 'paid_status', 'fin_year'],
                3015 => ['watertax.t_wt_demand', 'watercharges', 'paid', 'fin_year']
            ];

            $pending_tax_amount = 0;



    list($table, $amountCol, $paidCol, $yearCol) =
        $taxMap[base64_decode($post_data_array['acct_id'])];

    $tax_sql = "
        SELECT COALESCE(SUM($amountCol),0) AS opening_balance_amount
        FROM $table
        WHERE $paidCol = 'N'
          AND isactive = 1
          AND del_flag IS NULL
          AND $yearCol < '2025-2025'
          AND not_taken = 'N'
          AND taken_for_payment IS NULL
    ";

    $tax_res = $this->prepare($tax_sql, [], 4);
    // print_r($tax_res);die;
    $pending_tax_amount = $tax_res['opening_balance_amount'];

                /* =========================
               OPENING / CLOSING BALANCE
               ========================= */
            $sel_exemption_cat_data_upd_details = "
                SELECT 
                    a.account_head_id,
                    a.date,
                    a.mode_type,
                    a.closing_balance_amount,
                    b.account_head_name_en,
                    b.old_account_head_code
                FROM (
                    SELECT 
                        receipt_expenditure AS mode_type,
                        date,
                        account_head_id,
                        opening_balance_amount,
                        closing_balance_amount
                    FROM accounts_master.m_tp_opening_closing_balance
                    WHERE del_flag IS NULL
                      AND dcode = :dcode
                      AND lbcode = :lbcode
                      AND tp_ob_cb_id = :tp_ob_cb_id
                ) a
                LEFT JOIN (
                    SELECT 
                        account_head_id,
                        account_head_name_en,
                        old_account_head_code
                    FROM accounts_master.m_account_head
                    WHERE isactive = :isactive
                      AND del_flag IS NULL
                ) b
                ON a.account_head_id = b.account_head_id
            ";

            $data_array_val = $this->prepare(
                $sel_exemption_cat_data_upd_details,
                [
                    ":tp_ob_cb_id" => $exemption_category_data_id,
                    ":dcode"       => $dcode,
                    ":lbcode"      => $lbcode,
                    ":isactive"    => 1
                ],
                4
            );
/* =========================
   MERGE TAX AMOUNT (SINGLE ROW)
   ========================= */
if (is_array($data_array_val)) {
    $data_array_val['opening_balance_amount'] = $pending_tax_amount;
}

/* Merge into post data */
$post_data_array = array_merge($post_data_array, $data_array_val);

        }else{
             $sel_exemption_cat_data_upd_details = "select a.account_head_id,date,mode_type, opening_balance_amount, closing_balance_amount, account_head_name_en, old_account_head_code from (SELECT receipt_expenditure as mode_type, date ,account_head_id, opening_balance_amount, closing_balance_amount FROM accounts_master.m_tp_opening_closing_balance WHERE del_flag is null and dcode=:dcode and lbcode=:lbcode and tp_ob_cb_id=:tp_ob_cb_id) a left join (select account_head_id, account_head_name_en, old_account_head_code from accounts_master.m_account_head where isactive=:isactive and del_flag is null)b on a.account_head_id=b.account_head_id;";
            $data_array_val = $this->prepare($sel_exemption_cat_data_upd_details, array(":tp_ob_cb_id" => $exemption_category_data_id, ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1), 4);
            $post_data_array = array_merge($post_data_array, $data_array_val);
        }
           
        }

        ?>

        <?php 
        $dcode     = $this->getCurrentDistrictCode();
        
    $lbcode    = $this->getCurrentLocalBodyCode();
$flagQuery = "SELECT upload_flag  FROM accounts_master.temp_op_excel_upload where dcode=:dcode and lbcode=:lbcode";
$upload_status = $this->prepare($flagQuery, array(":dcode"=>$dcode,":lbcode"=>$lbcode), 4);

$upload_flag = isset($upload_status['upload_flag']) ? $upload_status['upload_flag'] : 0;


// echo $upload_flag;die;
if ($upload_flag === 0) {
 ?>
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Opening Balance Excel Upload</h5>
        </div>

        <div class="card-body">

            <form method="post" enctype="multipart/form-data">

                <div class="row align-items-end">

                    <!-- File Upload -->
                    <div class="col-md-5">
                        <label class="form-label"><b>Select Excel File</b></label>
                        <input type="file" name="upload_excel" accept=".xlsx,.xls" 
                               class="form-control" required>
                    </div>

                    <!-- Upload Button -->
                    <div class="col-md-3">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" name="btn_upload" class="btn btn-success w-100">
                            Upload Excel
                        </button>
                    </div>

                    <!-- Download Sample Link -->
                    <div class="col-md-4">
                        <label class="form-label d-block">&nbsp;</label>
                        <a href="Op_Balance.php?download_sample=<?= base64_encode('1') ?>"
                           class="btn btn-primary w-100">
                            Download Sample Excel
                        </a>
                    </div>

                </div>

            </form>

        </div>
    </div>
</div>
<?php } ?>

        <div class="container pt-3"> 
    

        <form action="" method="post" class="" enctype="multipart/form-data"  autocomplete="off">
            <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>" name="<?php echo htmlentities($this->page_token); ?>" value="<?php echo htmlentities($this->token($this->page_token)); ?>">
            <div class="card">
                <div class="card-body pl-5 pr-5">
                    <?php
                    if (isset($post_data_array["STATUS"])) {
                        echo $this->ShowMessage($post_data_array["STATUS"], $post_data_array["MESSAGE"]);
                        header("refresh: 3; url=".$_SERVER['REQUEST_URI']);
                    }
                    ?>
                    <table class="table table-bordered m-0 p-0 table-striped tndtp_form_table">
                        <thead class="bg-th-form-dsg">
                            <tr>
                                <th align="center" colspan="2" scope="col">Opening Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-left font-weight-bold w-50"><span DisplayLabelID="345">Mode</span></td>
                                <td class="w-50">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="credit" name="amount_type" value="1" class="custom-control-input" <?php if(isset($post_data_array['mode_type']) && $post_data_array['mode_type']==1){ ?>checked<?php } ?>>
                                        <label class="custom-control-label" for="credit"><span DisplayLabelID="371">Credit</span></label>
                                    </div> 
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="debit" name="amount_type" value="2" class="custom-control-input" <?php if(isset($post_data_array['mode_type']) && $post_data_array['mode_type']==2){ ?>checked<?php } ?>>
                                        <label class="custom-control-label" for="debit"><span DisplayLabelID="372">Debit</span></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left font-weight-bold" scope='row'><span DisplayLabelID="186">Date</span></td>
                                <td class="w-50" scope="row">
                                    <div class="row">
                                        <div class="col-auto">
                                             <input type="text" id="date" name="date"
                                                class="form-control form-control-sm"
                                                value="<?php echo isset($post_data_array['date']) ? $post_data_array['date'] : '' ?>" />
                                        </div>
                                    </div>
                                  
                                </td>
                            </tr>
                            <tr>
    <td class="text-left font-weight-bold">
        <span DisplayLabelID="186">Account Code</span>
    </td>
    <td>
        <?php
            $mode = isset($post_data_array['mode_type']) ? $post_data_array['mode_type'] : 0;
        ?>

        <select id="account_code"
                name="account_code"
                class="form-control form-control-sm w-50"
                required>
            <option value="">Choose</option>

            <?php
            if ($mode != 0) {

                $sel_account = "
                    SELECT 
                        account_head_id, 
                        old_account_head_code, 
                        new_account_head_code, 
                        account_head_name_en, 
                        account_head_name_ta 
                    FROM accounts_master.m_account_head 
                    WHERE 
                        del_flag IS NULL 
                        AND isactive = :isactive
                    ORDER BY old_account_head_code ASC
                ";

                $acc_res = $this->prepare(
                    $sel_account,
                    [':isactive' => 1],
                    2
                );

            } else {

                $sel_account = "
                    SELECT 
                        account_head_id, 
                        old_account_head_code, 
                        new_account_head_code, 
                        account_head_name_en, 
                        account_head_name_ta 
                    FROM accounts_master.m_account_head 
                    WHERE 
                        del_flag IS NULL 
                        AND isactive = :isactive 
                        AND account_head_id NOT IN (
                            SELECT account_head_id 
                            FROM accounts_master.m_tp_opening_closing_balance 
                            WHERE 
                                dcode = :dcode 
                                AND lbcode = :lbcode 
                                AND del_flag IS NULL 
                                AND isactive = :isactive 
                                AND fin_year = :fin_year
                        )
                    ORDER BY old_account_head_code ASC
                ";

                $acc_res = $this->prepare(
                    $sel_account,
                    [
                        ':isactive' => 1,
                        ':dcode'    => $dcode,
                        ':lbcode'   => $lbcode,
                        ':fin_year' => $fin_year
                    ],
                    2
                );
            }

            if (!empty($acc_res)) {
                foreach ($acc_res as $acc_row) {
            ?>
                <option
                    value="<?php echo htmlentities($acc_row['account_head_id']); ?>"
                    data-desc="<?php echo htmlentities($acc_row['account_head_name_en']); ?>"
                    <?php
                        if (
                            isset($post_data_array['account_head_id']) &&
                            $post_data_array['account_head_id'] == $acc_row['account_head_id']
                        ) {
                            echo 'selected';
                        }
                    ?>
                >
                    <?php
                        echo htmlentities(
                            $acc_row['old_account_head_code'] . ' - ' .
                            $acc_row['account_head_name_en'] . ' (' .
                            $acc_row['new_account_head_code'] . ')'
                        );
                    ?>
                </option>
            <?php
                }
            }
            ?>
        </select>
    </td>
</tr>

                            <tr>
                                <td class="text-left font-weight-bold"><span DisplayLabelID="186">Account Head</span></td>
                                <td>
                                <span id="account_head"><?php echo isset($post_data_array['account_head_name_en'])&&$post_data_array['account_head_name_en']!=''?$post_data_array['account_head_name_en']:''; ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td scope="row">Amount
                                </td>
                                <td scope="row">
                                    <div class="row">
                                        <?php if(isset($post_data_array['del_id'])){ ?>
                                            <span>
                                              <?php echo isset($post_data_array['opening_balance_amount'])?$post_data_array['opening_balance_amount']:''; ?>
                                            </span>
                                            <input type="hidden" class="form-control form-control-sm number_field" name="amount" id="amount" value="<?php if (isset($post_data_array['mode_type'])) {echo htmlentities($post_data_array['opening_balance_amount']); } ?>">
                                        <?php
                                        } else{ ?>
                                            <div class="col-6"><input type="text" class="form-control form-control-sm number_field" name="amount" id="amount" value="<?php if (isset($post_data_array['mode_type'])) {echo htmlentities($post_data_array['opening_balance_amount']); } ?>"></div>
                                            <?php
                                        }?>
                                    </div>
                                </td>
                            </tr>
                        
                            <tr>
                                <td colspan="4" align="center">
                                    <input type="submit" id="btn_save" name="btn_save" value="<?php echo htmlentities('Save'); ?>" class="btn btn-md text-white font-weight-bold btn-primary" />
                                </td>
                            </tr>
                            <tr id="accounts_data" <?php echo (isset($post_data_array['mode_type']) && $post_data_array['mode_type']=='')?'class="d-none"':''?>>
                                <td colspan="1">
                                    <div class="card">
                                        <div class="card-body">
                                            <?php
                                    $sel_account = "SELECT * FROM (SELECT account_head_id,old_account_head_code,
                                    new_account_head_code,account_head_name_en,account_head_name_ta,is_assets,is_liabilities,is_nic_demand FROM accounts_master.m_account_head
                                    WHERE del_flag IS NULL AND isactive = :isactive
                                    /*AND account_type_head_id = :mode*/) a
                                    LEFT JOIN (SELECT tp_ob_cb_id,account_head_id,opening_balance_amount,amount_spent_so_far,balance_amount,
                                    closing_balance_amount FROM accounts_master.m_tp_opening_closing_balance WHERE 
                                    dcode = :dcode AND lbcode = :lbcode AND fin_year = :fin_year AND del_flag IS NULL AND receipt_expenditure=1
                                    ) b 
                                    ON a.account_head_id = b.account_head_id
                                    WHERE tp_ob_cb_id IS NOT NULL
                                    ORDER BY old_account_head_code ASC";
$lbcode=$this->getCurrentLocalBodyCode();
$dcode=$this->getCurrentDistrictCode();

$acc_res = $this->prepare($sel_account,array(':dcode'=>$dcode,':lbcode'=>$lbcode,":fin_year"=>$fin_year, ":isactive"=>1),2);

// print_r($acc_res);die;
                                                            
                                                            $total = 0;
                                                            foreach ($acc_res as $row) {
                                                            
                                                                $total += $row['opening_balance_amount'];
                                                            }
                                                         
                                                            ?>
                                            <div class="single-table" id="credit_account_details">
                                                                                            <div style="max-height: 80vh; overflow-y: auto;">   
                                                <table class="table table-bordered text-center table-striped tndtp_report_table" style="width:100%;" id="dataTable2">
                                                            <thead class="text-left">
                                                                <tr>
                                                                    <th scope="col"><span DisplayLabelID="311">S.No</span></th>
                                                                    <th scope="col"><span DisplayLabelID="329">Account Code</span></th>
                                                                    <th scope="col"><span DisplayLabelID="329">Account Head</span></th>
                                                                    <th scope="col"><span DisplayLabelID="186">Amount</span></th>
                                                                    <th scope="col"><span DisplayLabelID="186">Amount Spent So Far</span></th>
                                                                    <th scope="col"><span DisplayLabelID="186">Balance Amount</span></th>
                                                                    <th scope="col"><span DisplayLabelID="671">Action (Edit | Delete)</span></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>    
                                                             <div style="position:sticky;  top: 0; background: #e3d2ffff; z-index: 10; padding:10px;border-radius:5px; color:black; text-align:center;">
                                                                Credit Amount :: <span id='credit_total'><?php echo $total; ?></span>
                                <input type="hidden" name="credit_total" class="credit_total"  value="<?php echo $total; ?>"/>
                                                            </div>
                                                            
                                                            <?php
                                                            if (count($acc_res) > 0) {
                                                                foreach ($acc_res as $acc_key => $acc_row) { ?>
                                                                    <tr>
                                                                        <td class="text-center"><?php echo htmlentities($acc_key + 1); ?></td>
                                                                        <td class="text-left"><?php echo htmlentities($acc_row['old_account_head_code']);?></td>
                                                                        <td class="text-left"><?php echo htmlentities($acc_row['account_head_name_en']);?></td>
                                                                        <td class="text-left"><?php echo htmlentities($acc_row['opening_balance_amount']);?></td>
                                                                        <td class="text-left"><?php echo htmlentities($acc_row['amount_spent_so_far']);?></td>
                                                                        <td class="text-left"><?php echo htmlentities($acc_row['balance_amount']);?></td>
                                                                        <td align="center">
                                                                            <?php
                                $edit_url = '?edit_id=' . base64_encode($acc_row['tp_ob_cb_id']);

                                if ($acc_row['is_assets'] === 'Y') {
                                    $edit_url = 'Assets_Statement.php?old_acc_id=' . base64_encode($acc_row['old_account_head_code']) .
                                                '&ob_amt=' . base64_encode($acc_row['opening_balance_amount']);
                                } elseif ($acc_row['is_liabilities'] === 'Y') {
                                    $edit_url = 'Op_Liabilities_Statement.php?old_acc_id=' . base64_encode($acc_row['old_account_head_code']) .
                                                '&ob_amt=' . base64_encode($acc_row['opening_balance_amount']);
                                }elseif ($acc_row['is_nic_demand'] === 'Y') {
                                    $edit_url = '?edit_id=' . base64_encode($acc_row['tp_ob_cb_id']).
                                                '&acct_id=' . base64_encode($acc_row['account_head_id']);

                                }

                                ?>


                                <a href="<?php echo htmlentities($edit_url); ?>"
                                   class="btn btn-warning btn-sm mb-2">
                                   Edit
                                </a>

                                                                            <a href="?del_id=<?php echo htmlentities(base64_encode($acc_row['tp_ob_cb_id'])); ?>" class="btn btn-danger btn-sm">Delete</a>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                    $total = array_sum(array_column($acc_row, 'opening_balance_amount'));
                                                                    ?>
                                                                    
                                                                    <?php
                                                                }
                                                            } else {
                                                            ?>
                                                            <tr>
                                                                <td align="center" colspan="6" style="color:#F00;" class="font-weight-bold">No Record Found</td>
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
                                </td>
                                <td colspan="1">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="single-table" id="debit_account_details">
                                            <div style="max-height: 80vh; overflow-y: auto;">
                                                 <?php
                                                            $sel_account = "
                                                                    SELECT *
                                                                    FROM (
                                                                        SELECT 
                                                                            account_head_id,
                                                                            old_account_head_code,
                                                                            new_account_head_code,
                                                                            account_head_name_en,
                                                                            account_head_name_ta,is_assets,is_liabilities,is_nic_demand
                                                                        FROM accounts_master.m_account_head
                                                                        WHERE 
                                                                            del_flag IS NULL
                                                                            AND isactive = :isactive
                                                                            
                                                                    ) a
                                                                    LEFT JOIN (
                                                                        SELECT 
                                                                            tp_ob_cb_id,
                                                                            account_head_id,
                                                                            opening_balance_amount,
                                                                            amount_spent_so_far,
                                                                            balance_amount,
                                                                            closing_balance_amount
                                                                        FROM accounts_master.m_tp_opening_closing_balance
                                                                        WHERE 
                                                                            dcode = :dcode
                                                                            AND lbcode = :lbcode
                                                                            AND fin_year = :fin_year
                                                                            AND del_flag IS NULL
                                                                            AND receipt_expenditure=2

                                                                    ) b
                                                                        ON a.account_head_id = b.account_head_id
                                                                    WHERE tp_ob_cb_id IS NOT NULL
                                                                    ORDER BY old_account_head_code ASC
                                                                ";
                                                                         $lbcode=$this->getCurrentLocalBodyCode();
                                                                        $dcode=$this->getCurrentDistrictCode();

                                                                $acc_res = $this->prepare(
                                                                    $sel_account,
                                                                    array(
                                                                        ':dcode'   => $dcode,
                                                                        ':lbcode'  => $lbcode,
                                                                        ':fin_year'=> $fin_year,
                                                                        ':isactive'=> 1
                                                                    ),
                                                                    2
                                                                );

                                                            $total = array_sum(array_column($acc_res, 'opening_balance_amount'));
                                                           // print_r($total);?>
                                                <table class="table table-bordered text-center table-striped tndtp_report_table" style="width:100%;" id="dataTable2">
                                                            <thead class="text-left">
                                                                <tr>
                                                                    <th scope="col"><span DisplayLabelID="311">S.No</span></th>
                                                                    <th scope="col"><span DisplayLabelID="329">Account Code</span></th>
                                                                    <th scope="col"><span DisplayLabelID="329">Account Head</span></th>
                                                                    <th scope="col"><span DisplayLabelID="186">Amount</span></th>
                                                                    <th scope="col"><span DisplayLabelID="186">Amount Spent So Far</span></th>
                                                                     <th scope="col"><span DisplayLabelID="186">Balance Amount</span></th>
                                                                    <th scope="col"><span DisplayLabelID="671">Action (Edit | Delete)</span></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>    
                                                           <div style="position:sticky;  top: 0; background: #e3d2ffff; z-index: 10; padding:10px;border-radius:5px; color:black; text-align:center;">
                                                            Debit Amount :: 
                                                            <span id="debit_total">
                                                               <?=$total?> 
                                                            </span>
                                                            <input type="hidden" 
                                                                name="debit_total" 
                                                                class="debit_total" 
                                                                value="<?=$total?>" />
                                                           </div>
                                                             
                                                            
                                                            <?php
                                                            if (count($acc_res) > 0) {
                                                                foreach ($acc_res as $acc_key => $acc_row) { ?>
                                                                    <tr>
                                                                        <td class="text-center"><?php echo htmlentities($acc_key + 1); ?></td>
                                                                        <td class="text-left"><?php echo htmlentities($acc_row['old_account_head_code']);?></td>
                                                                        <td class="text-left"><?php echo htmlentities($acc_row['account_head_name_en']);?></td>
                                                                        <td class="text-left"><?php echo htmlentities($acc_row['opening_balance_amount']);?></td>
                                                                        <td class="text-left"><?php echo htmlentities($acc_row['amount_spent_so_far']);?></td>
                                                                         <td class="text-left"><?php echo htmlentities($acc_row['balance_amount']);?></td>
                                                                     <td align="center">
                                                                            <?php
                                $edit_url = '?edit_id=' . base64_encode($acc_row['tp_ob_cb_id']);

                                if ($acc_row['is_assets'] === 'Y') {
                                    $edit_url = 'Assets_Statement.php?old_acc_id=' . base64_encode($acc_row['old_account_head_code']) .
                                                '&ob_amt=' . base64_encode($acc_row['opening_balance_amount']);
                                } elseif ($acc_row['is_liabilities'] === 'Y') {
                                    $edit_url = 'Op_Liabilities_Statement.php?old_acc_id=' . base64_encode($acc_row['old_account_head_code']) .
                                                '&ob_amt=' . base64_encode($acc_row['opening_balance_amount']);
                                }elseif ($acc_row['is_nic_demand'] === 'Y') {
                                    $edit_url = '?edit_id=' . base64_encode($acc_row['tp_ob_cb_id']).
                                                '&acct_id=' . base64_encode($acc_row['old_account_head_code']);

                                }

                                ?>


                                <a href="<?php echo htmlentities($edit_url); ?>"
                                   class="btn btn-warning btn-sm mb-2">
                                   Edit
                                </a>

                                                                            <a href="?del_id=<?php echo htmlentities(base64_encode($acc_row['tp_ob_cb_id'])); ?>" class="btn btn-danger btn-sm">Delete</a>
                                                                        </td>
                                                                    </tr>
                                                                  
                                                                    
                                                                    <?php
                                                                }
                                                            } else {
                                                            ?>
                                                            <tr>
                                                                <td align="center" colspan="6" style="color:#F00;" class="font-weight-bold">No Record Found</td>
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
                                </td>
                            </tr>
                            
                            
                        </tbody>
                    </table>
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
    
    $statecode = $this->getCurrentStateCode();
    $dcode     = $this->getCurrentDistrictCode();
    $lbcode    = $this->getCurrentLocalBodyCode();

    $edit_id = isset($save_data['edit_id']) && $save_data['edit_id'] !== ''
        ? (int) base64_decode($save_data['edit_id'])
        : 0;

    $del_id = isset($save_data['del_id']) && $save_data['del_id'] !== ''
        ? (int) base64_decode($save_data['del_id'])
        : 0;


    if (isset($save_data['amount_type']) && $save_data['amount_type'] != '') {
            $amount_type = $save_data['amount_type'];
            $amount_typeValidation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $amount_type,
                    'Field_Name' => 'amount_type',
                    'Field_Max_length' => '1',
                    'Field_Label_Name' => 'Amount Type',
                )
            );
            if ($amount_typeValidation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "amount_type",
                    "MESSAGE" => $amount_typeValidation['Message']
                ), $save_data));
                exit;
            }
        }
         if (isset($save_data['account_code']) && $save_data['account_code'] != '') {
            $account_code = $save_data['account_code'];
            $account_code_typeValidation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $account_code,
                    'Field_Name' => 'account_code',
                    'Field_Max_length' => '10',
                    'Field_Label_Name' => 'Account Code',
                )
            );
            if ($account_code_typeValidation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "account_code",
                    "MESSAGE" => $account_code_typeValidation['Message']
                ), $save_data));
                exit;
            }
        }
        if (isset($save_data['amount']) && $save_data['amount'] != '') {
            $amount = $save_data['amount'];
            $amount_typeValidation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $amount,
                    'Field_Name' => 'amount',
                    'Field_Max_length' => '10',
                    'Field_Label_Name' => 'Amount',
                )
            );
            if ($amount_typeValidation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "amount",
                    "MESSAGE" => $amount_typeValidation['Message']
                ), $save_data));
                exit;
            }
        }
       if (isset($save_data['date']) && $save_data['date'] != '') {
            $date = $save_data['date'];
            $date_typeValidation = $this->Field_Validation(
                array(
                   'Field_Type' => 'date',
                    'Field_Value' => $save_data['date'],
                    'Field_Name' => 'date',
                    'Field_Format' => 'dd-mm-yyyy',
                    'Field_Label_Name' => 'Date',
                )
            );
            if ($amount_typeValidation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "date",
                    "MESSAGE" => $amount_typeValidation['Message']
                ), $save_data));
                exit;
            }
        }
       
        
        

    $amount_type  = isset($save_data['amount_type'])  ? $save_data['amount_type']  : null;
    $account_code = isset($save_data['account_code']) ? $save_data['account_code'] : null;
    $amount       = isset($save_data['amount'])       ? $save_data['amount']       : 0;
    $total        = isset($save_data['total'])        ? $save_data['total']        : 0;
    $date=$save_data['date'];

    $data = [
        "statecode"    => $statecode,
        "dcode"        => $dcode,
        "lbcode"       => $lbcode,
        "edit_id"      => $edit_id,
        "del_id"       => $del_id,
        "amount_type"  => $amount_type,
        "account_code" => $account_code,
        "amount"       => $amount,
        "total"        => $total,
    ];




    // print_r($data); exit;

    /*
    // Your validation block is commented out for now. You can keep or re-enable it as needed.
    */

    // Result message
    $Result_Message = "Data Saved Successfully";

    if ($edit_id > 0) {
        $Result_Message = "Data Updated Successfully";
    } elseif ($del_id > 0) {
        $Result_Message = "Data Deleted Successfully";
    }

    // Begin DB transaction
    $this->beginTransaction();

    $pp_assessment_initiation = "accounts_master.sp_op_balance";
    $user_name = $this->getCurrentUser();
    $ip_address = $this->getIpAddress();
    $fin_year   = $this->getFinYear();

 
    $save_query = "SELECT * from " . $pp_assessment_initiation . "(
        :statecode,
        :dcode,
        :lbcode,
        :mode_type,
        :account_head,
        :amount,
        :total,
        :fin_year,
        :user_name,
        :ip_address,
        :edit_id,
        :del_id,
        :date
    )";

    $params = array(
        ":statecode"   => $statecode,
        ":dcode"       => $dcode,
        ":lbcode"      => $lbcode,
        ":mode_type"   => $amount_type,
        ":account_head"=> $account_code,
        ":amount"      => $amount,
        ":total"       => $total,
        ":fin_year"    => $fin_year,
        ":user_name"   => $user_name,
        ":ip_address"  => $ip_address,
        ":edit_id"     => $edit_id,
        ":del_id"      => $del_id,
        ":date"        =>$date
    );

    // 4 = mode/flag for your custom prepare wrapper
    $res1 = $this->prepare($save_query, $params, 4);

    // Commit / rollback

    if (!isset($res1->errorInfo)) {
        $this->commit();
         ?>
        <script>
            alert('data saved successfully');
            window.location.href = window.location.origin + window.location.pathname;
        </script>
        <?php
    } else {
        $this->rollback();
        ?>
        <script>
            alert('failed to save data');
            window.location.href = window.location.origin + window.location.pathname;
        </script>
        
        <?php
    }
    exit;
}

}

$propertyassessment = new Trade_Entry_Form();
if (!isset($_POST['cmd'])) {
    
    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        //print_r(array_merge($_POST, $_GET));exit();
        $propertyassessment->data_save(array_merge($_POST, $_GET));
    } else {
        $propertyassessment->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
    }
} else if (isset($_POST['cmd'])) {
    $cmd = base64_decode($_POST['cmd']);
    if ($cmd == 1) {
        $account_head_id = base64_decode($_POST['acc_code']);
		$dcode = $propertyassessment->getCurrentDistrictCode();
        $lbcode = $propertyassessment->getCurrentLocalBodyCode();
		$fin_year = $propertyassessment->getFinYear();
        $state_code = $propertyassessment->getCurrentStateCode();
        $result=array();
		$sel_account = "select tp_ob_cb_id, account_head_id, opening_balance_amount, closing_balance_amount from accounts_master.m_tp_opening_closing_balance where dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year and del_flag is null and account_head_id=:account_head_id and state_code=:state_code";
		$acc_res = $propertyassessment->prepare($sel_account,array(':dcode'=>$dcode,':lbcode'=>$lbcode,':account_head_id'=>$account_head_id, ":fin_year"=>$fin_year, ":state_code"=>$state_code),4);
		if(isset($acc_res['opening_balance_amount'] ) && $acc_res['opening_balance_amount'] !=''){            
		    $result['credit'] = $acc_res['opening_balance_amount'];
        }else{
            $result['credit'] =0;
        }
		echo json_encode($result);
        exit;
    }
    if ($cmd == 2) {
        //$mode = base64_decode($_POST['mode']);
		$dcode = $propertyassessment->getCurrentDistrictCode();
        $lbcode = $propertyassessment->getCurrentLocalBodyCode();
        $fin_year = $propertyassessment->getFinYear();
        ob_start();
        ?>
        <table class="table table-bordered text-center table-striped tndtp_report_table" style="width:100%;" id="dataTable2">
            <thead class="text-left">
                <tr>
                    <th scope="col"><span DisplayLabelID="311">S.No</span></th>
                    <th scope="col"><span DisplayLabelID="329">Account Code</span></th>
                    <th scope="col"><span DisplayLabelID="329">Account Head</span></th>
                    <th scope="col"><span DisplayLabelID="186">Total Amount</span></th>
                    <th scope="col"><span DisplayLabelID="186">Amount Spent So Far</span></th>
                    <th scope="col"><span DisplayLabelID="186">Balance Amount</span></th>
                    <th scope="col"><span DisplayLabelID="671">Action (Edit | Delete)</span></th>
                </tr>
            </thead>
            <tbody>    
            <?php
            $sel_account = "select * from 
    (select account_head_id, old_account_head_code, new_account_head_code, account_head_name_en, account_head_name_ta from accounts_master.m_account_head where del_flag is null and isactive=:isactive and account_type_head_id=:mode)a left join
    (select tp_ob_cb_id, account_head_id, opening_balance_amount, closing_balance_amount,total_amount,amount_spent_so_far,balance_amount from accounts_master.m_tp_opening_closing_balance where dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year and del_flag is null )b on a.account_head_id=b.account_head_id where tp_ob_cb_id is not null order by old_account_head_code asc";
            $acc_res = $propertyassessment->prepare($sel_account,array(':dcode'=>$dcode,':lbcode'=>$lbcode,':mode'=>$mode, ":fin_year"=>$fin_year, ":isactive"=>1),2);
            if (count($acc_res) > 0) {
                foreach ($acc_res as $acc_key => $acc_row) { ?>
                    <tr>
                        <td class="text-center"><?php echo htmlentities($acc_key + 1); ?></td>
                        <td class="text-left"><?php echo htmlentities($acc_row['old_account_head_code']);?></td>
                        <td class="text-left"><?php echo htmlentities($acc_row['account_head_name_en']);?></td>
                        <td class="text-left"><?php echo htmlentities($acc_row['total_amount']);?></td>
                        <td class="text-left"><?php echo htmlentities($acc_row['amount_spent_so_far']);?></td>
                        <td class="text-left"><?php echo htmlentities($acc_row['balance_amount']);?></td>
                        <td align="center">
                            <?php
                            $query = $_SERVER['QUERY_STRING'];
                            $query = $query ? $query . "&" : "";

                            ?>
                            <a href="?<?php echo $query?>edit_id=<?php echo htmlentities(base64_encode($acc_row['tp_ob_cb_id'])); ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?<?php echo $query?>del_id=<?php echo htmlentities(base64_encode(string: $acc_row['tp_ob_cb_id'])); ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
            ?>
            <tr>
                <td align="center" colspan="6" style="color:#F00;" class="font-weight-bold">No Record Found</td>
            </tr>
            <?php
                }
            ?>
            </tbody>
        </table>
        <?php
        $total = array_sum(array_column($acc_res, 'total_amount'));
        $ob_contents = ob_get_contents();
		ob_clean();
		$Result_Data['STATUS']='SUCCESS';
		$Result_Data['account_data_table']=$ob_contents;
            
        $sel_account = "select account_head_id, old_account_head_code, new_account_head_code, account_head_name_en, account_head_name_ta from accounts_master.m_account_head where del_flag is null and isactive=:isactive and account_type_head_id=:mode and account_head_id not in (select account_head_id from accounts_master.m_tp_opening_closing_balance where dcode=:dcode and lbcode=:lbcode and del_flag is null and isactive=:isactive and fin_year=:fin_year and receipt_expenditure=:mode) order by old_account_head_code asc";
        $acc_res = $propertyassessment->prepare($sel_account,array(':mode'=>$mode, ":isactive"=>1, ":dcode"=>$dcode, ":lbcode"=>$lbcode, ":fin_year"=>$fin_year ),2);
        if (count($acc_res) > 0) {
            ?>
                <option value="" >Choose</option>
            <?php
            foreach ($acc_res as $acc_key => $acc_row) { 
                ?>
                 <option value="<?php echo htmlentities($acc_row['account_head_id']);?>" data-desc="<?php echo htmlentities($acc_row['account_head_name_en']);?>"><?php echo htmlentities($acc_row['old_account_head_code']);?></option>
                <?php
            }
        } 
        $ob_contents = ob_get_contents();
		ob_clean();
		$Result_Data['STATUS']='SUCCESS';
		$Result_Data['account_code']=$ob_contents;
        $Result_Data['total']=$total ;
		echo json_encode($Result_Data);	
        exit;        
    }
    if ($cmd == 4) {
        $dcode = $propertyassessment->getCurrentDistrictCode();
        $lbcode = $propertyassessment->getCurrentLocalBodyCode();
        $fin_year = $propertyassessment->getFinYear();
        //print_r(array_merge($_POST, $_GET));exit;
        $propertyassessment->data_save(array_merge($_POST, $_GET));
        exit;        
    }
}
?>