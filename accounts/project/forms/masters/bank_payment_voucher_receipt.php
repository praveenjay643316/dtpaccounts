<?php
require_once  '../../config/config.php';
class Adjust_Triplicate_Chalan extends ConfigClass
{
    public $page_token = "triplicate_challan";
    public function __construct()
    {
        $this->language_name=$this->issetCurrentUserLanguage2D()?$this->getCurrentUserLanguage2D():"en";
        //$this->mpdf = new \Mpdf\Mpdf(["mode"=>'ta','margin_right'=>5,'margin_left'=>5,'margin_top'=>4,'margin_bottom'=>0]); //MPDF
        $this->mpdf = new \Mpdf\Mpdf(["mode" => 'en', "format" => "A4", "orientation" => "P"]);
    }
    public function main_content($post_data_array = array())
    {
		$site_data = $this->siteData();
		$state_code = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();
        if($_SERVER['REQUEST_METHOD']=='GET')
        {
            if( !isset($_GET['id']))
            {
                $url = $site_data->website_url . '/project/forms/masters/bank_payment_voucher.php' ;
                header("Location: $url");
            }
            else{
                $conn=new ConfigClass();
                $id=base64_decode($_GET['id']);
                $dcode=$conn->getCurrentDistrictCode();
                $lbcode=$conn->getCurrentLocalBodyCode();
                $fin_year=$conn->getFinYear();
                $res=$conn->prepare('select count(*) as "count" from accounts_master.t_bank_payment_voucher where bpv_id=:id and dcode=:dcode and lbcode=:lbcode and fin_year=:fin_year and del_flag is null',[":id"=>$id,
                ":dcode"=>$dcode,
                ":lbcode"=>$lbcode,
                ":fin_year"=>$fin_year        
            ],4);
                if($res["count"]==0)
                {
                    echo("Voucher Number ".$id." Does not exist");
                    die();
                    $url = $site_data->website_url . '/project/forms/masters/bank_payment_voucher.php' ;
                    header("Location: $url");
                }
            }
        }
        $lang_code_2d = $this->getCurrentUserLanguage2D();
		    if (isset($post_data_array["id"]) && $post_data_array["id"] !='') {
            $id=base64_decode($post_data_array["id"]);
			      $sel_qry="select district_name_".$lang_code_2d.", lbody_name_".$lang_code_2d.", bpv_chalan_no,chequeno,account_head_name_en, acc_amount as amount_text, amount, TO_CHAR(bpv_date::date, 'DD.MM.YYYY') as chalan_date, voucher_type, voucher_date, paymentmode, narration, in_favour, account_head_name_".$lang_code_2d.", old_account_head_code, new_account_head_code, paymenttype_en,bank_code from 
                (select * from accounts_master.t_bank_payment_voucher where isactive=:isactive and del_flag is null and bpv_id=:id)a 
                left join 
                (select dcode, district_name_".$lang_code_2d." from master.m_district where dist_order_no is not null)dist on a.dcode=dist.dcode 
                left join 
                (select dcode, lbcode, lbody_name_".$lang_code_2d."  from master.m_localbodies where del_flag is null and isactive=:isactive and lbtype=:lbtype)lb 
                on a.dcode=lb.dcode and a.lbcode=lb.lbcode 
                left join 
                (SELECT bpv_voucher_no,acc_code,acc_amount  FROM accounts_master.t_bpv_accounthead_breakup where del_flag is null and isactive=:isactive)d
                on d.bpv_voucher_no=a.bpv_chalan_no
                left join
                (SELECT account_head_id, account_head_name_".$lang_code_2d.", old_account_head_code, new_account_head_code  FROM accounts_master.m_account_head where del_flag is null and isactive=:isactive)b 
                on d.acc_code::int=b.account_head_id 
                left join 
                (select paymenttype as paymenttype_en, paymenttype_ta, paymenttypeid from master.m_paymenttype where del_flag is null ) c 
                on c.paymenttypeid=a.paymentmode
                left join 
                (SELECT bank_id,bank_code,bank_name_en FROM accounts_master.m_bank where del_flag is null ) e 
                on e.bank_id=a.bank_id::int";
            $sel_qry_res=$this->prepare($sel_qry, array(":isactive"=>1, ":id"=>$id, ":lbtype"=>'TP'),2);
            if($lang_code_2d == 'ta'){
              $numtowords=new numtowords($sel_qry_res[0]['amount'], 1); //1 for Tamil+
              $amount_in_words=  $numtowords->flushData() .' மட்டும்';
            } else {
              $numtowords=new numtowords($sel_qry_res[0]['amount']); //by default English
              $amount_in_words=  $numtowords->flushData() .' Only';
            }
        } 
        ob_start();
        ?>
<style>
body{padding:0px;margin:0px;font-size:15px}
.main-table,
.main-table-2,
th,
td {
    border: 1px solid black;
    border-collapse: collapse;
    

}
th,tr{
    text-align:center;
    word-wrap:break-word;
}

.main-table,
.main-table-2 {
    margin-left: auto;
    margin-right: auto;
    width: 100%;
    height:100%;
    font-family:Helvetica;
    font-size:12px;

}


.heading {
    padding:0px;
    margin-left: auto;
    margin-right: auto;
    max-height:fit-content;
    font-family:Helvetica;
}

.outer_table_container_1,.outer_table_container_2 {
    margin-left: auto;
    margin-right: auto;
    width: 100%;
}
.main-td{
height:40px;
}
.main2-td{
height:80px;}
</style>
<input type="hidden" id="page_lable_id" name="page_lable_id" value="151" />
<div>
    <table style="width:100%; border:1px solid #000; border-collapse:collapse;border-bottom:0px;">
        <tr>
            <td style="border:none;font-weight:bold;text-align: left!important;vertical-align: baseline;" colspan="2" align="right">
                Form No.TPCF.3
            </td>
            <td style="border:none; text-align:center;" colspan="6"><br><br>
                <p style="margin:0; font-weight:bold;">
                    <?php echo htmlentities($sel_qry_res[0]['lbody_name_'.$lang_code_2d].' Town Panchayat'); ?><br>
                    <?php echo htmlentities($sel_qry_res[0]['district_name_'.$lang_code_2d].' District'); ?>
                </p><br>
                <p style="margin-top:6px; font-weight:bold;">BANK PAYMENT VOUCHER</p><br>
                <div style="margin-top:10px;">
                    Bank Code :
                <span style="display:inline-block;
    border:1px solid #000;
padding:12px;
    line-height:1.2;
    vertical-align:middle;">
                        <?php echo $sel_qry_res[0]['bank_code']; ?>
                    </span>
                </div>
            </td>
            <td style="border:none; text-align:right; vertical-align:bottom;" colspan="2">
                <div>
                    No : <?php echo $sel_qry_res[0]['bpv_chalan_no']; ?>
                </div><br>
                Date : <?php echo $sel_qry_res[0]['chalan_date']; ?>
            </td>
        </tr>
    </table>
</div>
    <div class="outer_table_container_1">
<table style="
    width:100%;
    border-collapse:collapse;
    border-left:1px solid #000;
    border-right:1px solid #000;
    border-bottom:1px solid #000;
    border-top:0;
">
<tr>
    <td style="border:1px solid #000;" colspan="2">
        In Favour Of  <br><?php echo $sel_qry_res[0]['in_favour']; ?>
    </td>
    <td style="border:1px solid #000;" >
        Cheque No : <br><?php echo $sel_qry_res[0]['chequeno']; ?>
    </td>
    <td style="border:1px solid #000;" >
        Amount<br>Rs.<?php echo $sel_qry_res[0]['amount']; ?>
    </td>

    <td style="border:1px solid #000;text-align:left;padding:12px;" rowspan="2"  colspan="4">
        Amount Rs.<?php echo $sel_qry_res[0]['amount']; ?><br>
        (Rupees <?php echo $amount_in_words; ?> Only)
    </td>
</tr>

<tr>
    <td style="border:1px solid #000;"  colspan="2">Total / Gross Amount</td>
    <td style="border:1px solid #000;" colspan="2"></td>
</tr>

<tr>
    <td style="border:1px solid #000;" colspan="2">Deductions</td>
    <td style="border:1px solid #000;" colspan="2"></td>

    <td style="border:1px solid #000;" rowspan="2" colspan="4" align="left">
       <span style="font-weight: bold;" >Narration : </span><?php echo $sel_qry_res[0]['narration']; ?>
    </td>
</tr>

<tr>
    <td style="border:1px solid #000;" colspan="2">Net Amount(A)</td>
    <td style="border:1px solid #000;" colspan="2"></td>
</tr>

</table>
</div>


        <div class="outer_table_container_1">
<table style="
    width:100%;
    border-collapse:collapse;
    border-left:1px solid #000;
    border-right:1px solid #000;
    border-bottom:1px solid #000;
    border-top:0;
">


<tr>
    <th rowspan="2" style="border:1px solid #000;">SI.No</th>
    <th rowspan="2" style="border:1px solid #000;">Account Code No</th>
    <th rowspan="2" style="border:1px solid #000;">Account Head</th>
    <th colspan="2" style="border:1px solid #000;">Journal Voucher</th>
    <th rowspan="2" style="border:1px solid #000;">Dr.Amount (Rs)</th>
    <th rowspan="2" style="border:1px solid #000;">Cr.Amount (Rs)</th>
    <th style="border:1px solid #000;" rowspan="2">Ledger Folio No.</th>
</tr>

<tr>
    <th style="border:1px solid #000;">Type</th>
    <th style="border:1px solid #000;">No.</th>
</tr>

<?php foreach ($sel_qry_res as $key=>$row) { ?>
<tr>
    <td style="border:1px solid #000;" align="center"><?php echo $key+1; ?></td>
    <td style="border:1px solid #000;"  align="center"><?php echo $row['old_account_head_code']; ?></td>
    <td style="border:1px solid #000;"  align="center"><?php echo $row['account_head_name_en']; ?></td>
    <td style="border:1px solid #000;"></td>
    <td style="border:1px solid #000;"></td>
    <td style="border:1px solid #000;"></td>
    <td style="border:1px solid #000; text-align:right;"><?php echo $row['amount_text']; ?></td>
    <td style="border:1px solid #000;"></td>

</tr>
<?php } ?>
<tr>
                <td class="main2-td" colspan="4"
                    style="border-top: 0px;border-right: 0px;font-size:11px;text-align: center;vertical-align: bottom;">
                    <span style="font-size:13px;">J.A/ H.C</span></td>
                <td class="main2-td" colspan="4"
                    style="border-top: 0px;border-left: 0px;font-size: smaller;text-align: center;vertical-align: bottom;">
                    <p style="font-size:15px;">Approved By</p> <br> <br> <br>
                    <p style="font-size:13px;padding:0px;margin:0px;">EXECUTIVE OFFICER</p>
                </td>
            </tr>

</table>
</div>
<?php
  	$ob_output_main_contents = ob_get_contents();
    ob_clean();
    //print_r($ob_output_main_contents);
    //die();
      $this->mpdf->WriteHTML($ob_output_main_contents);
      $this->mpdf->Output();
    }
}
$propertyassessment = new Adjust_Triplicate_Chalan();
$propertyassessment->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));