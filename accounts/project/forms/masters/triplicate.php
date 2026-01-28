<?php
require_once  '../../config/config.php';
require_once  '../../library/num2words.php';
require_once __DIR__ . '/../../library/mpdf/vendor/autoload.php';
class Adjust_Triplicate_Chalan extends ConfigClass
{

    public $page_token = "triplicate_challan";
    public function __construct()
    {
        $this->language_name=$this->issetCurrentUserLanguage2D()?$this->getCurrentUserLanguage2D():"en";
        $this->mpdf = new \Mpdf\Mpdf(["mode"=>'ta' , 'format' => 'A4-L',  'margin_right' => 2, 'margin_top' =>2, 'margin_bottom' =>2, 'margin_header' => 0, 'margin_footer' => 0]); //MPDF
	  	  $this->mpdf->SetDisplayMode('fullpage'); 
    }
    public function main_content($post_data_array = array())
    {
		    $site_data = $this->siteData();
		    $state_code = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();
        $lang_code_2d = $this->getCurrentUserLanguage2D();
		    if (isset($post_data_array["id"]) && $post_data_array["id"] !='') {

          



            $id=base64_decode($post_data_array["id"]);
			      $sel_qry = "
    SELECT 
        district_name_{$lang_code_2d},
        lbody_name_{$lang_code_2d},
         a.tc_serial_no,
		    a.tc_chalan_no,
        account_head_name_en,
        total_amount AS amount_text,
        d.credit_account_id,
        d.debit_account_id,
        d.account_type,
        case when d.credit_amount is not null then d.credit_amount else d.debit_amount end as amount, d.credit_amount,d.debit_amount,
        TO_CHAR(chalan_date::date, 'DD.MM.YYYY') AS chalan_date,
        TO_CHAR(chequedate::date, 'DD.MM.YYYY') AS cheque_date,
        TO_CHAR(ecs_date::date, 'DD.MM.YYYY') AS ecs_date,
        TO_CHAR(dd_date::date, 'DD.MM.YYYY') AS dd_date,
        collectiontype,
        collectiondate,
        paymentmode,
        narration,
        remitter_name,
        account_head_name_{$lang_code_2d},
        old_account_head_code,
        new_account_head_code,
        paymenttype_en,
		chequeno, 
		dd_no, 
		ecs_no
    FROM (
        SELECT * 
        FROM accounts_master.t_triplicate_chalan_details 
        WHERE 
            isactive = :isactive 
            AND del_flag IS NULL 
            AND chalan_details_id = :id 
            AND lbcode = :lbcode 
            AND dcode = :dcode
    ) a
    LEFT JOIN (
        SELECT 
            dcode, 
            district_name_{$lang_code_2d} 
        FROM master.m_district 
        WHERE dist_order_no IS NOT NULL
    ) dist 
        ON a.dcode = dist.dcode
    LEFT JOIN (
        SELECT 
            dcode, 
            lbcode, 
            lbody_name_{$lang_code_2d}  
        FROM master.m_localbodies 
        WHERE 
            del_flag IS NULL 
            AND isactive = :isactive 
            AND lbtype = :lbtype
    ) lb 
        ON a.dcode = lb.dcode 
        AND a.lbcode = lb.lbcode
    LEFT JOIN (
        SELECT 
            tc_serial_no,
            credit_account_id,debit_account_id,
            credit_amount,debit_amount ,account_type 
        FROM accounts_master.t_triplicate_accounthead_breakup 
        WHERE 
            del_flag IS NULL 
            AND isactive = :isactive 
            AND lbcode = :lbcode 
            AND dcode = :dcode
    ) d 
        ON d.tc_serial_no = a.tc_serial_no
    LEFT JOIN (
        SELECT 
            account_head_id, 
            account_head_name_{$lang_code_2d}, 
            old_account_head_code, 
            new_account_head_code  
        FROM accounts_master.m_account_head 
        WHERE 
            del_flag IS NULL 
            AND isactive = :isactive
    ) b 
         ON  (
       (d.credit_account_id IS NOT NULL AND d.credit_account_id = b.account_head_id)
    OR (d.debit_account_id IS NOT NULL AND d.debit_account_id = b.account_head_id)
)
    LEFT JOIN (
        SELECT 
            paymenttype AS paymenttype_en, 
            paymenttype_ta, 
            paymenttypeid 
        FROM master.m_paymenttype 
        WHERE del_flag IS NULL
    ) c 
        ON c.paymenttypeid = a.paymentmode
";

            $sel_qry_res=$this->prepare($sel_qry, array(":isactive"=>1, ":id"=>$id,":lbcode"=>$lbcode,":dcode"=>$dcode, ":lbtype"=>'TP'),2);
            if($lang_code_2d == 'ta'){
              $numtowords=new numtowords($sel_qry_res[0]['amount_text'], 1); //1 for Tamil
              $amount_in_words=  $numtowords->flushData() .' மட்டும்';
            } else {
              $numtowords=new numtowords($sel_qry_res[0]['amount_text']); //by default English
              $amount_in_words=  $numtowords->flushData() .' Only';
            }
        } 
        ob_start();
        ?>
        <input type="hidden" id="page_lable_id" name="page_lable_id" value="151" />	
            <table>
              <tr>
                <?php for($i=0;$i<3;$i++){ ?>
                  <td>
                    <table>
                      <tr>
                        <td colspan="2">
                          <span style="font-size:10px;"><?php echo htmlentities("Do not waste water, use it economically"); ?>.</span>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">
                          <table  border="1" cellpadding="0" cellspacing="0" bordercolor="#5FC1F5" align="center" valign="middle">
                            <tr>
                              <td style="padding:2px;font-size:15px;font-weight:bold;border-bottom:0;" colspan="5" align="center">
                                <?php echo htmlentities($sel_qry_res[0]['lbody_name_'.$lang_code_2d] . ' ' . ' Town Panchayat') . ' <br/> ' . htmlentities($sel_qry_res[0]['district_name_'.$lang_code_2d] . ' ' . ' District'); ?>
                              </td>
                            </tr>
                            <tr>
                              <td style="padding:2px;font-size:13px;border-bottom:0;border-top:0;" colspan="5" align="right">
                                <span style="font-weight:bold;"> <?php echo htmlentities('Chalan No') ; ?> </span>  <?php echo ' : '. $sel_qry_res[0]['tc_chalan_no']; ?>
                              </td>
                            </tr>
                            <tr>
                              <td style="padding:2px;font-size:13px;border-right:0;border-top:0;" colspan="2" align="left">
                                <span style="font-weight:bold;"> 
											            <?php 
                                    if($i==0){
                                      echo htmlentities('Office Copy');
                                    }else if($i==1){
                                      echo htmlentities('Dept.Copy');
                                    }else{
                                      echo htmlentities("Party's.Copy");
                                    }
                                  ?>
                                </span>
                              </td>
                              <td style="padding:2px;font-size:13px;border-left:0;border-top:0;" colspan="3" align="right">
                                <span style="font-weight:bold;"> 
                                  <?php echo htmlentities('Chalan Date'); ?>
                                </span> <?php echo ' : '. $sel_qry_res[0]['chalan_date']; ?>
                              </td>
                            </tr>
                            <tr>
                              <td style="padding:2px;font-size:13px;border-right:0;" colspan="1" align="left"  valign="top">
                                <span style="font-weight:bold;"> 
                                  <?php echo htmlentities('Name of the Payer') .' / <br/>'. htmlentities('Address'); ?>
                                </span>
                              </td>
                              <td style="padding:2px;font-size:13px;height:50px;border-left:0;" colspan="4" align="left" valign="top">
                                <?php echo  $sel_qry_res[0]['remitter_name']; ?>
                              </td>
                            </tr>
                            <tr>
                              <td style="padding:2px;font-size:13px;" colspan="1"  align="center">
                                <span style="font-weight:bold;"> 
                                  <?php echo htmlentities('Account Code'); ?>
                                </span>
                              </td>
                              <td style="padding:2px;font-size:13px;" colspan="2"  align="center">
                                <span style="font-weight:bold;"> 
                                        		<?php echo htmlentities('Account Details'); ?>
                                            </span>
                                        </td>
                                        <td style="padding:2px;font-size:13px;" colspan="2" align="center">
                                        	<span style="font-weight:bold;"> 
                                          		<?php echo htmlentities('Amount'); ?> <br /> <?php echo htmlentities('Rs'); ?>
                                            </span>
                                        </td>
                                      </tr>
                                      <?php
                                      foreach ($sel_qry_res as $row) {
                                        if($row['account_type']==2){                                        
                                        ?>
                                        <tr height="250px" align="center" valign="top"> 
                                            <td style="padding:2px;font-size:13px;" colspan="1" align="center" valign="top">
                                              <div style="height:250px;">
                                                <?php echo htmlentities($row['old_account_head_code']); ?>
                                              </div>
                                            </td>
                                            <td style="padding:2px;font-size:13px;" colspan="2" align="center" valign="top">
                                              <div style="height:250px;">
                                                <?php echo htmlentities($row['account_head_name_' . $lang_code_2d]); ?>
                                              </div>
                                            </td>
                                              <td style="padding:2px;font-size:13px;" colspan="2" align="center" valign="top">
                                              <div style="height:250px;">
                                                <?php echo htmlentities($row['amount']); ?>       
                                              </div>
                                            </td>
                                        </tr>
                                       
                                        <?php
                                        }
                                      }?>
                                      <tr>
                                        <td style="padding:2px;font-size:13px;" colspan="3" align="center">
                                        	<span style="font-weight:bold;"> 
                                          		<?php echo htmlentities('Rupees') .' (₹) '; ?>                                            	
                                            </span>
                                            <?php echo $amount_in_words ; ?>
                                        </td>
                                        <td style="padding:2px;font-size:13px;" colspan="2" align="center">
										  	                  <?php echo htmlentities($sel_qry_res[0]['amount_text']);  ?>
                                        </td>
                                      </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr >
                            	<td colspan="2" style="font-size:11px;">
                                	<span style="font-weight:bold;"> 
                            			<?php echo htmlentities('Note'); ?> 
                                  </span>
                                  <?php echo htmlentities(' : ' . $sel_qry_res[0]['narration']); ?>
                              </td>
                            </tr>
                            <tr >
                            	<td colspan="2" style="font-size:11px;">
                                	<span style="font-weight:bold;"> 
                            			<?php echo htmlentities('Payment Mode'); ?> 
                                  </span>
                                  <?php echo htmlentities(' : ' . $sel_qry_res[0]['paymenttype_en']); ?>
                              </td>
                            </tr>
                            <?php
                              if($sel_qry_res[0]['paymentmode'] == 2){
                                ?>
                                  <tr>
                                    <td>
                                      <span style="font-weight:bold;"> 
                                      <?php echo htmlentities('Cheque No.'); ?> 
                                      </span>
                                      <?php  echo htmlentities(' : ' . $sel_qry_res[0]['chequeno']); ?>
                                      
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <span style="font-weight:bold;"> 
                                      <?php echo htmlentities('Cheque Date: '); ?> 
                                      </span>
                                      <?php echo  htmlentities(' : ' . $sel_qry_res[0]['cheque_date']);?>
                                    </td>
                                  </tr>
                                <?php  
                            }
                              if($sel_qry_res[0]['paymentmode'] == 3){
                                ?>
                                  <tr>
                                    <td>
                                      <span style="font-weight:bold;"> 
                                      <?php echo htmlentities('DD No.'); ?> 
                                      </span>
                                      <?php echo htmlentities(' : ' . $sel_qry_res[0]['dd_no']); ?>                        
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <span style="font-weight:bold;"> 
                                      <?php echo htmlentities('DD Date: '); ?> 
                                      </span>
                                      <?php echo  htmlentities(' : ' . $sel_qry_res[0]['dd_date']);?>
                                    </td>
                                  </tr>
                                <?php  
                            }
                            if($sel_qry_res[0]['paymentmode'] == 4){
                                ?>
                                  <tr>
                                    <td>
                                      <span style="font-weight:bold;"> 
                                      <?php echo htmlentities('ECS No.'); ?> 
                                      </span>
                                      <?php echo htmlentities(' : ' . $sel_qry_res[0]['ecs_no']); ?>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <span style="font-weight:bold;"> 
                                      <?php echo htmlentities('ECS Date: '); ?> 
                                      </span>
                                      <?php echo  htmlentities(' : ' . $sel_qry_res[0]['ecs_date']);?>
                                    </td>
                                  </tr>
                                <?php  
                            }
                            ?>
                            <tr >
                            	<td class="text-left" style="height:200px;" >
                                	<span style="font-weight:bold;"> 
                            			<?php echo htmlentities(string: 'Date:'); ?>
                                    
                                    <br/><br/><br/><br/><br/><br/>
                                    <?php echo htmlentities('J.A/H.C.'); ?>
                                    </span>
                                </td>
                                <td align="center">
                                	<span style="font-weight:bold;"> 
                            			<?php echo htmlentities(string: "Remitter's signature:"); ?>
                                                                        <br/><br/><br/><br/><br/><br/>

                                    <?php echo htmlentities('E.O'); ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                              <?php 
                              if($i == 2){
                                ?>
                                <td align="center"></td>
                                <td>
                                  <span style="font-weight:bold; text-align:justify;">

                                   <p style="font-size:8px;">
                                      Note : 1.If the amount remitted represents "Deposit" this original Chalan shall be retained by the remitter and enclosed with the claim for refund.Xerox copy shall not be accepted.
                                    </p>
                                    <p style="font-size:8px;">
                                      2.If the full signature of the Officer is not furnished, this chalan shall not be valid.
                                    </p>

                                </span>
                               
                                </td>
                               
                                
                                <?php

                              }else
                              {
                                ?>
                                <tr>
                                  <td><br/><br/><br/></td>
                                </tr>
                                <?php
                              }
                              ?>
                            	
                            </tr>
                           
                        </table>
                     </td>
          <?php } ?>
        </tr>
      </table>
      <?php
     //print_r($this->siteData()->physical_image_path . 'tamilnadu_Logo_Non_Tax_Receipt.png');die;
     //echo ob_get_contents();die;
  	  $ob_output_main_contents = ob_get_contents();
      ob_clean();
      $this->mpdf->WriteHTML($ob_output_main_contents);
      $this->mpdf->SetWatermarkImage($this->siteData()->physical_image_path . 'tamilnadu_Logo_Non_Tax_Receipt.png',0.2,[300,300],[5, 5]);
      $this->mpdf->showWatermarkImage = true;
      $this->mpdf->Output();
    }
}
$propertyassessment = new Adjust_Triplicate_Chalan();
$propertyassessment->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));