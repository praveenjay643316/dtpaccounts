<?php
require_once '../../config/config.php';

class ContractorJournalVoucherReceipt extends ConfigClass
{
  public $page_token = "Bank_Receipt_Voucher";
  public function __construct()
  {
    if (!isset($this->db)) {
      $this->mpdf = new \Mpdf\Mpdf(["mode" => 'en', "format" => "A4", "orientation" => "P"]);
    }
  }
  
  public function main_content($post_data_array = array())
  {
    ob_start();
    // ##############

    // PAGE CONTENT START

    // #############
    $state_code = 33;
    $cjv_no = base64_decode($_GET['cjvno']);
    $dcode = $this->getCurrentDistrictCode();
    $lbcode = $this->getCurrentLocalBodyCode();
    $lang_code_2d = $this->getCurrentUserLanguage2D();
    $fin_year = $this->getFinYear();
    $dname = $this->getDistrictName($state_code, $dcode, 'en')['district_name'];
    $tpname = $this->getTownPanchayatName($state_code, $dcode, $lbcode, 'en')['lbody_name_en'];
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      if (!isset($_GET['cjvno'])) {
        $url = $site_data->website_url . '/project/forms/masters/Contract_journal_vouchers.php' ;
        header("Location: $url");
      } else {
        $cjv_no = base64_decode($_GET['cjvno']);
        $res = $this->prepare('select count(*) as "count" from accounts_master.t_cj_voucher where cjv_id=:cjv_no and dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year and del_flag is null', [
          ":dcode" => $dcode,
          ":lbcode" => $lbcode,
          ":fin_year" => $fin_year,
          ":cjv_no" => $cjv_no
        ], 4);
        if ($res["count"] == 0) {
          $url = $site_data->website_url . '/project/forms/masters/Contract_journal_vouchers.php' ;
          header("Location: $url");
        }
      }
    }
    $query = "SELECT
    credit_account_id,
    debit_account_id,
    credit_tot_amount,
    debit_tot_amount,
    name_of_contractor,
    cwd.name_of_work_en,
    sch.scheme_name_en,
	narration,
	m_book_ref_no,
  TO_CHAR(cjv_date::date, 'DD-MM-YYYY') AS cjv_date	,
  credit_tot_amount,
	debit_tot_amount, cjv.cjv_no
FROM accounts_master.t_cj_voucher as cjv 
LEFT JOIN master.m_scheme as sch ON sch.scheme_seq_id=cjv.scheme_id
LEFT JOIN accounts_master.t_contractor_work_details as cwd ON cwd.contractor_work_details_id=cjv.name_of_work::integer
WHERE cjv.cjv_id   = :cjv_id
  AND cjv.lbcode   = :lbcode
  AND cjv.dcode    = :dcode
  AND cjv.fin_year = :fin_year
  AND cwd.del_flag IS NULL
  AND cwd.lbcode   = :lbcode
  AND cwd.dcode    = :dcode
  AND cwd.fin_year = :fin_year
  AND cwd.del_flag IS NULL;
";
    $voucher_res = $this->prepare($query, [":cjv_id" => $cjv_no, ":lbcode" => $lbcode, ":dcode" => $dcode, ":fin_year" => $fin_year], 4);
    ?>
    <style>
      .data-heading td,
      .sub-heading td {
        font-weight: bold;
        text-align: left;
      }

      .data-rows td {
        text-align: left;

      }
      .info-tr td{
        text-align:justify;
      }
      body {
        font-size: 10pt;
      }
    </style>
    <input type="hidden" id="page_lable_id" name="page_lable_id" value="38" />

 <div class="container mt-3">
      <div>
        <div class="main-table-div">
          <table width="100%" border="1" cellpaddingborder="1" cellspacingborder="1" 
            style="border-collapse:collapse;  color:#000;" >
            <tr style="border: none;">
              <td style="font-weight:bold;" colspan="5">Form No. TPCF 11 <br> <br></td>
            </tr>
            <tr style="border: none;">
              <td style="font-weight:bold;font-size:15px;" colspan="5" align="center"><?php echo htmlentities($tpname . ' Town Panchayat ('. $dname . ' District)' ); ?></td>
            </tr>
            <tr style="border: none;">
              <td style="font-weight:bold;font-size:15px;" colspan="5" align="center">CONTRACTOR'S JOURNAL VOUCHER</td>
            </tr>
            <tr >
              <td align="left" colspan="2">
                MBook Reference :  &nbsp;&nbsp; <?php echo $voucher_res['m_book_ref_no']; ?>
              </td>
              <td align="left">
                No. :   &nbsp;&nbsp;<?php echo $voucher_res['cjv_no']; ?>
              </td>
              <td align="left" colspan="2">
                Date:  &nbsp;&nbsp;<?= $voucher_res['cjv_date']; ?>
              </td>
            </tr>
            <tr>
              <td colspan='2'>Name of Contractor:<br><span style="text-align: center;"><?php echo $voucher_res['name_of_contractor']; ?></span></td>
              <td colspan='3'>Name of the work:<br><span style="text-align: center;"><?php echo $voucher_res['name_of_work_en'];?></span> </td>
            </tr>
            <tr>
              <td colspan='5'>Narration:<br><span style="text-align: center;"><?= $voucher_res['narration']?></span></td>
            </tr>

            <!-- data heading -->
            <tr>
              <td colspan='1' align='center'>Account Code</td>
              <td colspan='2'  align='center'>Account Head</td>
              <td colspan='1'  align='center'>Debit <br> Rs.</td>
              <td colspan='1' align='center'>Credit <br> Rs.</td>
            </tr>
            <!-- data rows --><?php 
            
            $voucher_breakup_query='select cjv_b.*,acc.old_account_head_code as account_head_code,acc.account_head_name_'.$lang_code_2d.'  from accounts_master.t_cj_voucher_breakup as cjv_b left join accounts_master.m_account_head as acc on (acc.account_head_id=cjv_b.debit_account_id or acc.account_head_id=cjv_b.credit_account_id)  where cjv_b.del_flag is null and cjv_b.cjv_id=:cjv_id and cjv_b.lbcode=:lbcode and cjv_b.dcode=:dcode and cjv_b.fin_year=:fin_year order by account_type desc; ';
            $voucher_breakup_query_res=$this->prepare($voucher_breakup_query,[":lbcode"=>$lbcode,":dcode"=>$dcode,":fin_year"=>$fin_year,":cjv_id"=>$cjv_no],2);
        
           foreach ($voucher_breakup_query_res as $voucher_breakup_query_res_row) { ?>
              <tr class="data-rows">
                  <td  colspan='1' align="center"><?=$voucher_breakup_query_res_row['account_head_code']?></td>
                  <td colspan="2" align="center"><?=$voucher_breakup_query_res_row['account_head_name_'.$lang_code_2d]?></td>
                  <td  colspan='1'  align='right'>
                      <?php
                      echo ($voucher_breakup_query_res_row['account_type'] == 2)
                          ? $voucher_breakup_query_res_row['debit_amount']
                          : '';
                      ?>
                  </td>
                  <td  colspan='1'  align='right'>
                      <?php
                      echo ($voucher_breakup_query_res_row['account_type'] == 1)
                          ? $voucher_breakup_query_res_row['credit_amount']
                          : '';
                      ?>
                  </td>
              </tr>
              <?php } ?>

            <tr class="total-row">
              <td colspan='3' align="right">Total </td>
              <td colspan='1'  align='right'><?=$voucher_res['debit_tot_amount']?></td>
              <td colspan='1'  align='right'><?=$voucher_res['credit_tot_amount']?></td>
            </tr>

           <tr class="info-tr">
    <!-- LEFT COLUMN -->
    <td colspan="2"
    valign="top"
    style="padding:12px; border:1px solid #000;">

    <table width="100%" cellspacing="0" cellpadding="0">
        <!-- HEADING ROW -->
        <tr>
            <td align="center" style="font-weight:bold; text-decoration:underline;">
                Engineering Department
            </td>
        </tr>

        <!-- SPACE ROW -->
        <tr>
            <td height="8"></td>
        </tr>

        <!-- BODY ROW -->
        <tr>
            <td style="text-align:justify;">
                Certified that the bill is in accordance with the measurements recorded in the M.Book and the claim is
                correct as per agreement. The cost of materials issued and other recoveries have been deducted from this
                bill and posted in the sub-ledgers. The Payment is covered within the approved budget provision
            </td>
        </tr>
    </table>
</td>
    <!-- RIGHT COLUMN (NO PADDING) -->
    <td colspan="3" valign="top" style="padding:12px; border:1px solid #000;">
        <div style="text-align: justify;">
            Certified that the bill has been checked and passed for payment of Rs.
            <?=$voucher_res['credit_tot_amount']?> &#40; Rs.<?php echo $this->convertToIndianCurrency($voucher_res['credit_tot_amount'],$lang_code_2d); ?> &#41;
            after adjusting Rs............ for cost of materials issued and for other recoveries
        </div>

        <table width="100%" style="margin-top:40px;">
            <tr>
              <td align="left">J.A / A.E</td>
              <td align="right" style="padding-right:20px;">A.E.E</td>
            </tr>
          </table>
    </td>
</tr>
<tr>
  <td colspan="2"
    valign="bottom"
    style="border:1px solid #000; padding:5px; padding-top:0;">

    <!-- TOP TEXT (NO EXTRA GAP, ALIGNED CLEANLY) -->
    <div style="line-height:1.3;">
        Folio No.<br>
        In Projects Ledger
    </div>

    <!-- PUSH CONTENT DOWN -->
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td height="120"></td>
        </tr>
    </table>

    <!-- BOTTOM SIGNATURE -->
    <div>
        Junior Asst. Engineer
    </div>

</td>

  <td colspan="3" valign="bottom"
    style="border:1px solid #000; padding:5px;">

  <div>
    Certified also that entry in the Asset schedule is attested by me.
  </div>

  <!-- SPACE -->
  <table width="100%">
    <tr>
      <td height="100"></td>
    </tr>
  </table>

  <table width="100%">
    <tr>
      <td align="left">Junior Asst./ <br>Head Clerk</td>
      <td align="center">A.E.E</td>
          <td width="20"></td>

      <td align="right" style="padding-left:10px;padding-right:20px;">
    E.O
</td>

    </tr>
  </table>

</td>

</tr>

          </table>
        </div>
      </div>
    </div>

    <?php

    // #############

    // PAGE CONTENT END

    // #############

    $ob_output_main_contents = ob_get_contents();
    ob_clean();
    $this->mpdf->WriteHTML($ob_output_main_contents);
    $this->mpdf->Output();
  }
  
}
$ContractorJournalVoucherReceipt = new ContractorJournalVoucherReceipt();
if (!isset($_POST['cmd'])) {
  $ContractorJournalVoucherReceipt->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
}
?>