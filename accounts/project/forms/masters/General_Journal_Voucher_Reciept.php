<?php
require_once '../../config/config.php';

class GeneralJournalVoucherReceipt extends ConfigClass
{
  public $page_token = "General Journal Voucher Receipt";

  public function __construct()
  {
    if (!isset($this->db)) {
      $this->mpdf = new \Mpdf\Mpdf(["mode" => 'en', "format" => "A4", "orientation" => "P"]);
    }
  }

  public function main_content($post_data_array = array())
  {
    ob_start();
    // #############

    // PAGE CONTENT START

    // #############
    $state_code = 33;
    $gjv_id = base64_decode($_GET['gjvno']);
    $dcode = $this->getCurrentDistrictCode();
    $lbcode = $this->getCurrentLocalBodyCode();
    $lang_code_2d = $this->getCurrentUserLanguage2D();
    $fin_year = $this->getFinYear();
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      if (!isset($_GET['gjvno'])) {
        $url = $site_data->website_url . '/project/forms/masters/General_journal_vouchers.php' ;
        header("Location: $url");
      } else {
        $conn = new ConfigClass();
        $gjv_id = base64_decode($_GET['gjvno']);
        $dcode = $conn->getCurrentDistrictCode();
        $lbcode = $conn->getCurrentLocalBodyCode();
        $fin_year = $conn->getFinYear();
        $res = $conn->prepare('select count(*) as "count" from accounts_master.t_gj_voucher where gjv_id=:id and dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year and del_flag is null', [
          ":dcode" => $dcode,
          ":lbcode" => $lbcode,
          ":fin_year" => $fin_year,
          ":id" => $gjv_id
        ], 4);
        if ($res["count"] == 0) {
          $url = $site_data->website_url . '/project/forms/masters/General_journal_vouchers.php' ;
          header("Location: $url");
        }
      }
    }
    $dname = $this->getDistrictName($state_code, $dcode, 'en')['district_name'];
    $tpname = $this->getTownPanchayatName($state_code, $dcode, $lbcode, 'en')['lbody_name_en'];
    $query = "SELECT
    gjv_no,gjv_chalan_no,
    credit_account_id,
    debit_account_id,
    credit_tot_amount,
    debit_tot_amount,
	narration,
  TO_CHAR(gjv_date::date, 'DD-MM-YYYY') AS gjv_date
FROM accounts_master.t_gj_voucher
WHERE gjv_id   = :id
  AND lbcode   = :lbcode
  AND dcode    = :dcode
  AND fin_year = :fin_year
  AND del_flag IS NULL;
";
    $gjv_id = base64_decode($_GET['gjvno']);
    $voucher_res = $this->prepare($query, [":id" => $gjv_id, ":lbcode" => $lbcode, ":dcode" => $dcode, ":fin_year" => $fin_year], 4);
    ?>
    <style>
    .signature-table td{
        text-align:center;
    }
    .note-table td{
        text-align:justify;
        padding:10px;
    }
</style>
    <input type="hidden" id="page_lable_id" name="page_lable_id" value="38" />
 <div class="container mt-3">    
    <table width="100%" class="heading-table" style="border:1px solid #000;border-collapse:collapse;">
                    <tr>
                        <td style="text-align: left;">                            
                                Form No. TPCF 10                      
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;font-weight:bold;"> <br>
                           <?php
                            echo htmlentities($tpname . ' Town Panchayat ('. $dname . ' District)' ); 
                           ?> 
                        </td>
                    </tr>
                    <tr>                        
                        <td style="text-align:center;font-weight:bold;">
                                GENERAL JOURNAL VOUCHER
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;">
                               No.<?php echo $voucher_res['gjv_chalan_no']; ?><br/>
                                Date:<?php echo $voucher_res['gjv_date']; ?>
                        </td>
                    </tr>     
                    <tr>
                        <td style="text-align:left;"> 
                          Narration : <?php echo htmlentities($voucher_res['narration']); ?>
                        </td>
                    </tr>                 
                  </table>
    <table width="100%" class="main-data-table" border="1" style="border-collapse:collapse;">
                    <tbody>
                        <tr>
        <th rowspan="2" >A/c.Code</th>
        <th rowspan="2">Account Head</th>
        <th colspan="2">Link Reference</th>
        <th rowspan="2">L.F</th>
        <th rowspan="2">Debit Rs.</th>
        <th rowspan="2">Credit Rs.</th>
    </tr>
    <tr>
        <th>Type</th>
        <th>No.</th>
    </tr>
                        <?php
                        $voucher_breakup_query="SELECT 
    gjv_b.*, 
    acc.old_account_head_code AS account_head_code, 
    acc.account_head_name_$lang_code_2d  as account_head_name
FROM 
    accounts_master.t_gj_voucher_breakup AS gjv_b
LEFT JOIN 
    accounts_master.m_account_head AS acc 
    ON acc.account_head_id =
   CASE
     WHEN gjv_b.debit_account_id IS NOT NULL THEN gjv_b.debit_account_id
     ELSE gjv_b.credit_account_id
   END
WHERE 
    gjv_b.del_flag IS NULL 
    AND gjv_b.gjv_id = :id
    AND gjv_b.lbcode = :lbcode 
    AND gjv_b.dcode = :dcode 
    AND gjv_b.fin_year = :fin_year;
";
                        $voucher_breakup_query_res = $this->prepare($voucher_breakup_query, [
                            ":lbcode" => $lbcode,
                            ":dcode" => $dcode,
                            ":fin_year" => $fin_year,
                            ":id" => $gjv_id
                        ], 2);

                        foreach ($voucher_breakup_query_res as $data_row) { ?>
                            <tr>
                                <td style="text-align:center;"><?= $data_row['account_head_code'] ?? '' ?></td>
    <td style="text-align:center;"><?= $data_row['account_head_name'] ?? '' ?></td>
    <td></td>
    <td></td>
    <td></td>

    <td style="text-align:right;"><?= $data_row['debit_amount'] ?? '' ?></td>
    <td style="text-align:right;"><?= $data_row['credit_amount'] ?? '' ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="5" style="text-align:right;">Total</td>
                            <td  style="text-align:right;"><?=$voucher_res['credit_tot_amount']?></td>
                            <td style="text-align:right;"><?=$voucher_res['debit_tot_amount']?></td>
                        </tr>
                    </tbody>
    </table>
    <table width="100%" class="signature-table" border="1" style="border-collapse:collapse;">
        <tbody>
            <tr>
                <td>
                    Prepared by <br/><br/><br/><br/><br/>
                    Junior Assistant<br/><br/><br/>
                    Date
                </td>
                <td>
                    Checked by <br/><br/><br/><br/><br/>
                    Junior Asst.&#47;HeadClerk<br/><br/><br/>
                    Date
                </td>
                <td>
                    Approved by <br/><br/><br/><br/><br/>
                    Executive Officer<br/><br/><br/>
                    Date
                </td>
                <td>
                    Posted by <br/><br/><br/><br/><br/>
                    Junior Assistant<br/><br/><br/>
                    Date
                </td>
            </tr>
        </tbody>
    </table>
    <table width="100%" class="note-table"  style="border-collapse:collapse;">
        <tr>
            <td>
                <div style="font-size: small;">
                    Note:
                    <br/> 
                    1.A common Journal Voucher is prescribed. They may be used as
                    General Journal Voucher, Expense Journal Voucher, or Purchase
                    Journal Voucher according to the nature of transactions.<br/>
                    2."L.F." indicates the folio number in General Ledger.<br/>
                    3."Narration" is meant for utilising details of transactions
                    required for "Journalisation".
                </div>
            </td>
        </tr>
    </table>
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
$GeneralJournalVoucherReceipt = new GeneralJournalVoucherReceipt();
if (!isset($_POST['cmd'])) {
  $GeneralJournalVoucherReceipt->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
}


?>
