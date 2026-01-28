<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once  '../../config/config.php';
require_once  '../../library/num2words.php';
require_once __DIR__ . '/../../library/mpdf/vendor/autoload.php';
if($_SERVER['REQUEST_METHOD']=='GET')
{
    if( !isset($_GET['id']))
    {
        echo ("No Receipt Number has been sent");
        die();
    }
    else{
        $conn=new ConfigClass();
        $id=base64_decode($_GET['id']);
        $dcode=$conn->getCurrentDistrictCode();
        $lbcode=$conn->getCurrentLocalBodyCode();
        $fin_year=$conn->getFinYear();
        $res=$conn->prepare('select count(*) as "count" from accounts_master.t_bank_receipt_voucher where brv_id=:id and dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year and del_flag is null',[":id"=>$id,
        ":dcode"=>$dcode,
        ":lbcode"=>$lbcode,
        ":fin_year"=>$fin_year        
    ],4);
        if($res["count"]==0)
        {
            echo("Receipt Number ".$id." Does not exist");
            die();
        }
    }
}
class AdjustBankReceiptVoucher  extends ConfigClass
{
    public $page_token = "Bank_Receipt_Voucher";
    
    public function __construct()
	{
	  if (!isset($this->db)) {
		  $this->mpdf = new \Mpdf\Mpdf(["mode"=>'en',"format"=>"A4","orientation"=>"P"]);
	  }
	}
    public function main_content($post_data_array = array())
    {
        ob_start();
        // #############

        // PAGE CONTENT START

        // #############
        $state_code = 33;
        
        $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();
        $lang_code_2d = $this->getCurrentUserLanguage2D();
        $fin_year = $this->getFinYear();
         //$fin_year='2025-2026';
        $brv_id = base64_decode($post_data_array['id']);
        $dname=$this->getDistrictName($state_code , $dcode,  'en');
        
        $tpname=$this->getTownPanchayatName($state_code , $dcode, $lbcode,  'en');
        //print_r(get_class_methods($this));die();
        $sel_qry="select brv_chalan_no, brv_date, debit_amount, credit_amount, narration, total_amount, paymenttype from  (select brv_id, brv_chalan_no, TO_CHAR(brv_date, 'DD-MM-YYYY') as brv_date, triplicate_collection_date, dcode, lbcode, debit_amount, credit_amount, debit_breakup_id, credit_breakup_id, narration, fin_year, total_amount, payment_mode from accounts_master.t_bank_receipt_voucher where dcode=:dcode and lbcode=:lbcode and del_flag is null and isactive=:isactive and brv_id=:brv_id and fin_year=:fin_year)a left join (select paymenttypeid, paymenttype from master.m_paymenttype where del_flag is null )b on a.payment_mode=b.paymenttypeid";
        $sel_qry_res=$this->prepare($sel_qry, array(":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":fin_year"=>$fin_year, ":brv_id"=>$brv_id),4);
        if(count($sel_qry_res)>0){
            $sel_breakup_qry="select account_type, bank_code, bank_head, debit_amount, acc_code, acc_head, credit_amount from 
(select account_type, bank_code_id, bank_head, debit_amount, credit_amount, acc_code_id, acc_head from accounts_master.t_bank_receipt_voucher_breakup where dcode=:dcode and lbcode=:lbcode and del_flag is null and isactive=:isactive and brv_chalan_no=:brv_chalan_no and fin_year=:fin_year)a 
left join 
(SELECT account_head_id, old_account_head_code as acc_code, account_head_name_en FROM accounts_master.m_account_head WHERE isactive = :isactive AND del_flag IS NULL and old_account_head_code <> '' and old_account_head_code is not null ) b on b.account_head_id=a.acc_code_id 
left join 
(SELECT bank_code, bankaccount_id, bank_id FROM accounts_master.t_bank_account WHERE isactive = :isactive AND del_flag IS NULL) c on c.bankaccount_id=a.bank_code_id";
            $sel_breakup_qry_res=$this->prepare($sel_breakup_qry, array(":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1, ":fin_year"=>$fin_year, ":brv_chalan_no"=>$sel_qry_res['brv_chalan_no']),2);
        }
        $currency =  $this->convertToIndianCurrency($sel_qry_res['total_amount'],'en');
        ?>
        <input type="hidden" id="page_lable_id" name="page_lable_id" value="38" />
        <div class="container mt-3">
        <div>
            <div class="main-table-div">
                <table  width="100%" border="1" cellpaddingborder="1" cellspacingborder="1" color="#5FC1F5" style="border-collapse:collapse;  color:#000;" align="center">
                    <tr>
                        <th style="text-align: left;vertical-align: baseline;">Form No.MCF.18</th>
                        <th colspan="2">
                            <h2 style="text-align:center;">Bank Receipt Voucher</h2> <br>
                            <h3 style="text-align:center;"><?php echo ($tpname['lbody_name_en']); ?> Town Panchayat <br> <?php echo ($dname['district_name']); ?> District </h3>
                            <br>
                            <br>
                        </th>
                        <th>
                             <div style="text-align:right;margin-right: 3%;">
                                No : <?php echo ($sel_qry_res['brv_chalan_no']); ?> <br>
                                Date : <?php echo ($sel_qry_res['brv_date']); ?>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th style="text-align:left;">Challan Date : <?php echo ($sel_qry_res['brv_date']); ?></th>
                        <th colspan="3" style="text-align:right;">Challan No : <?php echo ($sel_qry_res['brv_date']); ?></th>
                    </tr>
                    <tr>
                        <th>Account Code</th>
                        <th>Account Head</th>
                        <th>Credit Amount</th>
                        <th>Debit Amount</th>
                    </tr>
                    <?php 
                        foreach($sel_breakup_qry_res as $sel_breakup_qry_row){
                            ?>
                                <tr>
                                    <td><?php echo $sel_breakup_qry_row['acc_code']; ?></td>
                                    <td><?php echo $sel_breakup_qry_row['acc_head']; ?></td>
                                    <td><?php echo ($sel_breakup_qry_row['credit_amount']); ?></td>
                                    <td><?php echo ($sel_breakup_qry_row['debit_amount']); ?></td>
                                </tr>
                            <?php
                        }
                    ?>
                    <tr style="height:700px;">
                        <td colspan="2" style="text-align:right;">Total(A)</td>
                        <td><?php echo ($sel_qry_res['total_amount']); ?></td>
                        <td><?php echo ($sel_qry_res['total_amount']); ?></td>
                    </tr>
                    <tr>
                        <td colspan="4"><strong>Rupees :</strong> <?php echo ($currency) . ' Only'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"><strong> Prepared by <br> <br> <br> Jr. Asst. / Assistant </strong></td>
                        <td style="text-align:center;"><strong> Checked and approved By <br> <br> <br> Executive Officer </strong></td>
                        <td style="text-align:center;" colspan="2"><strong> Entered in the General Ledger <br> <br> <br> Junior Assistant / Head Clerk </strong></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <table>
                                <tr>
                                    <td style="text-align:left;font-size:small;" colspan="3"> (This is the Abstract of day book [daily collections in Cash] ) </td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;" colspan="3"> I. Enclosures: </td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;">1) For Cash COllections :  </td>
                                    <td style="text-align:left;" colspan="2"> 
                                        <ol>
                                            <li>Triplicate Chalan copies</li>
                                            <li>Perforated copy of the Chalan Register of Bill Collectors</li>
                                            <li>Statement of Receipts for departmental Collections</li>
                                        </ol>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;">2) For Cheque COllections :  </td>
                                    <td style="text-align:left;" colspan="2"> Daily Cheque Collection Statement. </td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;">3) For Treasury Adjustment :  </td>
                                    <td style="text-align:left;" colspan="2"> Copies of proceedings sanctioning Stamp duty, E.T.Grants etc., or an office note explaining the Details of credits in the treasury Scroll. </td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;">4) For Credits Given by Bank :  </td>
                                    <td style="text-align:left;" colspan="2"> An office note detailing the credits as oeally ascertained from the Bank or Bank Advice with details.</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;" colspan="3"> II. COunter foil of Bank Pay-in slip should be pasted on the backside of the BRV </td>
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
        //$this->Template($this->getCurrentUserTemplate() != "" ? $this->getCurrentUserTemplate() : "Template1", "Property Tax - New Assessment", $ob_output_main_contents, array(), array('page_id' => 12));
        $this->mpdf->WriteHTML($ob_output_main_contents);
		$this->mpdf->Output();
    }
    public function convertToIndianCurrency($number='',$language_name='') {
		if($language_name == 'ta'){
			$numtowords=new numtowords($number, 1); 
		} else {
			$numtowords=new numtowords($number);
		}
		return $numtowords->flushData();
	}
}
$AdjustBankReceiptVoucher = new AdjustBankReceiptVoucher();
if (!isset($_POST['cmd'])) {
    $AdjustBankReceiptVoucher->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
}
?>