<?php
require_once  '../../config/config.php';
require_once __DIR__ . '/../../library/mpdf/vendor/autoload.php';






class MonlyDCBReport  extends ConfigClass
{
    public function __construct()
    {
        if (! isset($this->db)) {
            
            
            
			 
            
		 	$this->mpdf = new \Mpdf\Mpdf(["mode"=>'ta']); //MPDF
			
        }
    }
	public function main_content($view_data=array())
    {
	
        $site_data = $this->siteData();

        ob_start();
		
		
		//print_r($view_data);
		
        // #############

        // PAGE CONTENT START

        // #############
		
	//	echo isset($view_data['pdf'])?print_r($view_data):'';
	
	?>
        <input type="hidden" id="page_lable_id" name="page_lable_id" value="22" />
        
        <?php

		$lang_code_2d=$this->getCurrentUserLanguage2D();
		
	
		$state_code=$this->getCurrentStateCode();
		if($this->getCurrentDistrictCode()!=''){
		$dcode=$this->getCurrentDistrictCode();
			

		$sel_dname="SELECT dcode,district_name_".$lang_code_2d." FROM master.m_district WHERE state_code=:state_code AND dcode=:dcode";
		$sel_dname_res=$this->prepare($sel_dname,array(":state_code"=>$state_code,":dcode"=>$dcode),4);
			
		} 
		
		if($this->getCurrentLocalBodyCode()!=''){
			$lbcode=$this->getCurrentLocalBodyCode();

		$sel_tpname="SELECT lbcode,lbody_name_en FROM master.m_localbodies WHERE state_code=:state_code AND dcode=:dcode AND lbcode=:lbcode AND lbtype=:lbtype";
$sel_tpname_res=$this->prepare($sel_tpname,array(":state_code"=>$state_code,":dcode"=>$dcode,":lbcode"=>$lbcode,":lbtype"=>'TP'),4);
		}


	 $pageLables=$this->GetPageLables(22);
		//print_r($pageLables);
        ?>
 <script type="text/javascript">
		$(document).ready(function(){
			
			
			
			$(document).on('change','#dcode',function(){
				
				if($('#dcode').val()!='')
				{
				
					var dcode=$('#dcode').val();

					$.ajax({
						url: "Watertax_DCB_Report.php",
						type: "post",
						data: {"dcode":btoa(dcode),"cmd":btoa(1)},
						success: function (data){
							if(data != '')
							{
								$('#lbcode').html(data);
							}
						},
						dataType: 'html'
					});
					return true;
				}
				else
				{
					alert('Select District Name');
					$('#lbcode').html('<option value="">Select Town panchayat</option>');
					return true;
				}
				
			});
			
			
	
			

<?php /*?>$(document).on('click',"#show",function()
{

	var Current_Field_id=$(this).attr('id'); $('#'+Current_Field_id).hide(); try {
		
		
		<?php if(!$this->issetCurrentDistrictCode())
					{
					?>
				if($("#dcode").val().length == '')
				{
				throw{msg:"<?php echo htmlentities($pageLables[19]); ?>",foc:"#dcode"}
				}
				<?php
					}
					?>
					
		<?php if(!$this->issetCurrentLocalBodyCode())
					{
					?>
				if($("#lbcode").val().length == '')
				{
					throw{msg:"<?php echo htmlentities($pageLables[21]); ?>",foc:"#lbcode"}
				}
		<?php	
		}
		?>

if($("#wardid").val().length == '' && $('#all_ward').prop('checked')==false)
		{
			throw{msg:"<?php echo htmlentities($pageLables[188]); ?>",foc:"#wardid"}
		}
		
	
		return true;
	} 
	catch (e) 
	{ 
		alert(e.msg); $('#'+Current_Field_id).show();
		$(e.foc).focus();
		return false;
	}

});	<?php */?>		





			
			
				$("#show").on('click',function()
{
	
	
		<?php if(!$this->issetCurrentDistrictCode())
					{
					?>
					if($('#dcode').val()!='')
					{
				var dcode=$('#dcode').val();
					}
					else
					{
						alert('Select District Name');
						$('#dcode').focus();
						return false;	
					}
				<?php
					} else {
					?>	
					var dcode='<?php echo htmlentities($this->getCurrentDistrictCode());	 ?>';
					<?php
					}
					?>
					
		<?php if(!$this->issetCurrentLocalBodyCode())
					{
					?>
					if($('#lbcode').val()!='')
					{
				var lbcode=$('#lbcode').val();
					}
					else
					{
						alert('Select Townpanchayat Name');
						$('#lbcode').focus();
						return false;	
					}
		<?php	
		} else {
		?>
		var lbcode='<?php echo htmlentities($this->getCurrentLocalBodyCode()); ?>';
		<?php
					}
					?>

				if($('#taxtypeid').val()!='')
					{
				var taxtypeid=$('#taxtypeid').val();
				
					}
					else
					{
						alert('Select Tax');
						$('#taxtypeid').focus();
						return false;	
					}

				
				if(taxtypeid !='')
				{
					jQuery('#loading-image').show(); 
					$.ajax({
						url: "Monthly_DCB_report.php",
						type: "post",
						data: {"dcode":btoa(dcode),"lbcode":btoa(lbcode),"taxtypeid":btoa(taxtypeid),"cmd":btoa(2)},
						success: function (data){
							
							if(data != '')
							{
								//alert(data);
								var result_data=JSON.parse(data);
								if(result_data['STATUS']=='ERROR') {
									alert(result_data['MESSAGE']);
									$('#'+result_data['FIELD_NAME']).focus('');
								} else if(result_data['STATUS']=='SUCCESS') {
									$('#div_collect_details').html(result_data['DATA']);
								}
								jQuery('#loading-image').hide();
								return false;
							}
						},
						dataType: 'html'
					});
					return true;
				}
				else
				{
					alert('Select Tax');
					$('#taxtypeid').html('<option value="">Select Tax</option>');
					setTimeout(function() {
					$('#taxtypeid').focus();
					}, 1); 
					return true;
				}
				
			});
			
			jQuery(window).load(function() {
jQuery('#loading-image').hide();
});
	
		});		

		</script>
        
         <style>
#loading-image {
	position:absolute;
	-moz-border-radius: 9px;
	-webkit-border-radius: 9px;
	border-radius: 9px; /* future proofing */
	-khtml-border-radius: 9px;
	width: 250px;
	height: 200px;
	overflow: visible;
}
 </style>
		<div class="container">
        <meta charset="UTF-8">
        <form action="" method="post">
       
	       <div class="card">
            	
               
                <div class="card-body">
					<?php if(!isset($view_data['pdf']) && !isset($view_data['xls'])){  ?>     
                     <div id="loading-image" align="center" style="padding-left:500px">
<img src="<?php echo htmlentities($site_data->website_url);?>/images/ajax_loader_blue_256.gif" alt="Loading..." /><br />
</div>             
                   <table class="table table-bordered m-0 p-0 table-striped tndtp_form_table">
                                    <thead class="">
                            
                            <tr>
                            <th colspan="2">Monthly DCB Report</th></tr>
                            </thead>
                            <tbody>
                                <tr>
								<?php if($this->getCurrentDistrictCode()==''){ ?>
                                <td align="center" style="width:50%"><span DisplayLabelID="17"><?php echo htmlentities($pageLables[17]); ?></span></td>
                                <td align="left" style="width:50%">
                                <select id="dcode"  name="dcode" class="form-control  form-control-sm" >
                            	<option value="">Select District</option>
                            	<?php
								$sel_dist_detail="SELECT state_code,dcode,district_name_".$lang_code_2d." FROM master.m_district ORDER BY dcode";
								$sel_dist_detail_res=$this->prepare($sel_dist_detail,array(),2);
								foreach($sel_dist_detail_res as $sel_dist_detail_key=>$sel_dist_detail_row)
								{
								?>
                                	<option value="<?php echo htmlentities($sel_dist_detail_row['dcode']); ?>" data-state_code="<?php echo htmlentities($sel_dist_detail_row['state_code']); ?>"><?php echo htmlentities($sel_dist_detail_row['district_name_'.$lang_code_2d]); ?></option>
                                <?php
								}
								?>
                            </select>
                             <?php if(isset($_POST['dcode'])){?>
                             <script type="text/javascript">
                                                document.getElementById('dcode').value='<?php echo htmlentities($_POST['dcode']); ?>';
                                            </script>
							 <?php } } ?>
                            </td>
                                </tr>
                                
                                <tr >
								<?php if(!$this->issetCurrentLocalBodyCode())
								
								{ ?>
                                <td align="center" ><span DisplayLabelID="18"><?php echo htmlentities($pageLables[18]); ?></span></td>
                                <td align="left">
                                
                                
                                <select id="lbcode" name="lbcode" class="form-control  form-control-sm">
                                <option value="">Select Townpanchyat</option>
                                <?php
									$state_code=$this->getCurrentStateCode();
									$dcode=isset($_POST['dcode'])?$_POST['dcode']:$this->getCurrentDistrictCode();
									
									if($state_code!='' && $dcode!='')
									{

									$sel_town_details="SELECT lbcode,lbody_name_en FROM master.m_localbodies WHERE state_code=:state_code AND dcode=:dcode AND lbtype=:lbtype";
$sel_town_details_res=$this->prepare($sel_town_details,array(":state_code"=>$state_code,":dcode"=>$dcode,":lbtype"=>'TP'),2);
								
									foreach($sel_town_details_res as $sel_town_details_key=>$sel_town_details_row)
									{
									?>
										<option value="<?php echo htmlentities($sel_town_details_row['lbcode']); ?>"><?php echo htmlentities($sel_town_details_row['lbody_name_en']); ?></option>
									<?php
									}
									}
								?>
                            </select>
                             <?php if(isset($_POST['lbcode'])){?>
                             <script type="text/javascript">
                                                document.getElementById('lbcode').value='<?php echo htmlentities($_POST['lbcode']); ?>';
                                            </script>
                            
                             <?php } } ?>
                             
                             
                             </td>
                                </tr>
                     
                                
                                <tr>
                                
                               
                                  <td align="center"  style="width: 50%;vertical-align:middle;" >Tax Type</td>
<td align="left">
                                        <select id="taxtypeid" name="taxtypeid" class="form-control form-control-sm" onchange="$('#data_result').hide();">
                                            <option value="" DisplayLabelID="255"><?php echo htmlentities($pageLables[255]);?></option>
                                            <?php
												$sel_tax="SELECT taxtypeid,taxtypedesc_en,taxtypedesc_ta FROM master.m_taxtype where taxtypeid in (1,2)";
$sel_tax_res=$this->prepare($sel_tax,array(),2);
                                                foreach($sel_tax_res as $sel_tax_key=>$sel_tax_row)
                                                {
                                            ?>
                                                <option value="<?php echo htmlentities($sel_tax_row['taxtypeid']); ?>"><?php echo htmlentities($sel_tax_row['taxtypedesc_'.$lang_code_2d]); ?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
										<?php
											if(isset($_POST['taxtypeid'])){
										?>
                                        <script type="text/javascript">
                                            document.getElementById('taxtypeid').value='<?php echo htmlentities($_POST['taxtypeid']); ?>';
											alert("<?php echo htmlentities($_POST['taxtypeid']); ?>");
                                        </script>
										<?php
											}
										?>
                                    </td>
                                </tr>
                     </tbody>     
                              <tfoot>  
                                <tr><td colspan="2"><center><input type="button" id="show" name="show" value="Show" class="btn btn-sm btn-primary" /></center></td>
                                </tr>
                     </tfoot>
                          
							
</table>
 
                    <?php } ?>




<div id="div_collect_details">
  

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
		
		
		if(!isset($view_data['pdf']) && !isset($view_data['xls']))
		{
        $this->Template($this->getCurrentUserTemplate()!=""?$this->getCurrentUserTemplate():"Template1", "Townpanchayat Wise Water DCB Details", $ob_output_main_contents,array(),array('page_id'=>12));
		exit;
		}
		else if(isset($view_data['pdf']))
		{
			$ob_output_main_contents=$this->collect_details($_GET);
			
			
			if($ob_output_main_contents['STATUS']=='ERROR') {
				echo htmlentities($ob_output_main_contents['MESSAGE']);
				exit;
			}			
			else if($ob_output_main_contents['STATUS']=='SUCCESS') {
				
				$this->mpdf->WriteHTML($ob_output_main_contents['DATA']);
			$this->mpdf->Output();
				exit;
				
			}
			

		}
		else if( isset($view_data['xls']))
		{
			$ob_output_main_contents=$this->collect_details($_GET);
			
			if($ob_output_main_contents['STATUS']=='ERROR') {
				echo htmlentities($ob_output_main_contents['MESSAGE']);
				exit;
			}			
			else if($ob_output_main_contents['STATUS']=='SUCCESS') {
				
				echo htmlentities($ob_output_main_contents['DATA']);
				
				
			}
				exit;

		}
		
		
}






function collect_details($post_data=array(),$pageLables=array())	
{

	
	ob_start();
	//print_r($post_data);exit;
	
		
	
	
	if(!isset($post_data['pdf']) && !isset($post_data['xls']))
{
	$dcode=base64_decode($post_data['dcode']);
	$lbcode=base64_decode($post_data['lbcode']);
	$taxtypeid=base64_decode($post_data['taxtypeid']);
	
	

	
	
}
else if((isset($post_data['pdf']) && $post_data['pdf']!='') || (isset($post_data['xls']) && $post_data['xls']!=''))
{
	$dcode=base64_decode($post_data['dcode']);
	$lbcode=base64_decode($post_data['lbcode']);
	$taxtypeid=base64_decode($post_data['taxtypeid']);
}
$pageLables=$this->GetPageLables(22);


   $dcode_Validation = $this->Field_Validation(array(
                "Field_Type" => "number",
                "Field_Value" =>$dcode,
                "Field_Max_length" => 100,
                "Field_Min_length"=>0,
				"Field_Label_Name"=>"District"
            ));
			
			if ($dcode_Validation['Status'] == "Error") {	
				return array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "dcode",
                    "MESSAGE" => "Invalid District"
                );

                
            }
			
			
			
			   $lbcode_Validation = $this->Field_Validation(array(
                "Field_Type" => "number",
                "Field_Value" =>$lbcode,
                "Field_Max_length" => 100,
                "Field_Min_length"=>0,
				"Field_Label_Name"=>"Townpanchayat"
            ));
			
			if ($lbcode_Validation['Status'] == "Error") {
				return array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "lbcode",
                    "MESSAGE" => "Invalid Townpanchayat"
                );
            }
			
			
			
			  $taxtypeid_Validation = $this->Field_Validation(array(
                "Field_Type" => "number",
                "Field_Value" =>$taxtypeid,
                "Field_Max_length" => 100,
                "Field_Min_length"=>0,
				"Field_Label_Name"=>"Tax type"
            ));
			
			if ($taxtypeid_Validation['Status'] == "Error") {
                return array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "taxtypeid",
                    "MESSAGE" => "Invalid Tax Type"
                );
            }





	
//print_r($pageLables);
          ?>         

<?php if(!isset($post_data['pdf']) && !isset($post_data['xls'])){ 
	
?>          
<div class="row text-center mt-1 mb-1">
   <div class="col-md-12 text-center">
	    <a href="?pdf=<?php echo base64_encode(htmlentities(1)); ?>&dcode=<?php echo base64_encode(htmlentities($dcode)); ?>&taxtypeid=<?php echo ($post_data['taxtypeid']); ?>&lbcode=<?php echo base64_encode(htmlentities($lbcode)); ?>" target="_blank" class="font-weight-bold">Download PDF <img src="../../../images/pdf.png"></a>      
		<!--<a href="?xls=<?php echo base64_encode(htmlentities(1)); ?>&dcode=<?php echo base64_encode(htmlentities($dcode)); ?>&taxtypeid=<?php echo ($post_data['taxtypeid']); ?>&lbcode=<?php echo base64_encode(htmlentities($lbcode));?>" target="_blank" class="font-weight-bold">Download xls <img width="25px" height="25px" src="../../../images/excel.png"></a>  -->
    </div>
</div>
<?php } ?>
<?php 
if(isset($post_data['xls'])){
	
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
header("Cache-Control: no-store, no-cache, must-revalidate");   // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");                                     // HTTP/1.0
header("Content-type: application/force-download");
header("Content-type: application/octet-stream");
header("Content-type: application/msexcel");
header('Content-Disposition: attachment; filename="report.xls";');  
	
	
}

?>
 
 
 
    <div style="overflow-x:scroll;overflow-y:hidden;">    
                    <table class="table table-bordered m-0 p-0 table-striped tndtp_report_table"  
                    
                    <?php if(isset($post_data['pdf'])){ ?>         
                    border="1"
                    bordercolor="#5FC1F5"
                    style="border-collapse:collapse; font-size: 12px;font-weight:normal;"
                    <?php } ?>
                    
                    >
                    
                      
<thead >

<tr>
        	<th colspan="27" scope="col">Demand Register Report</th>
        </tr>

<?php if(isset($post_data['pdf'])){  ?>
<?php /*?><tr> 
<td colspan="20" align="center">
Demand Register Report
</td>
</tr><?php */?>
<tr>
<td colspan="20" align="center">
<?php
$sel_dname="SELECT dcode,district_name_".$lang_code_2d." FROM master.m_district WHERE state_code=:state_code AND dcode=:dcode";

 $sel_dname_res=$this->prepare($sel_dname,array(":state_code"=>$state_code,":dcode"=>$dcode),4);
?>

District Name : <?php echo htmlentities($sel_dname_res['district_name_'.$lang_code_2d]);  ?>
</td>
</tr>

<tr>
<td colspan="20" align="center">
<?php
$sel_tpname="SELECT lbcode,lbody_name_en FROM master.m_localbodies WHERE state_code=:state_code AND dcode=:dcode AND lbcode=:lbcode AND lbtype='TP'";
$sel_tpname_res=$this->prepare($sel_tpname,array(":state_code"=>$state_code,":dcode"=>$dcode,":lbcode"=>$lbcode),4);


?>
Townpanchayat Name : <?php echo htmlentities($sel_tpname_res['lbody_name_en']); ?>
</td>
</tr>




<?php } ?>



                            <tr>
                              <td rowspan="2" align="center" scope="col">Month</td>
                                                              
                                <td colspan="4" align="center" scope="col">Collection</td>
                                <td colspan="4" align="center" scope="col">Pending</td>
                                <td rowspan="2" align="center" scope="col"><span DisplayLabelID="184"><?php echo htmlentities($pageLables[184]); ?></span></td>
                                <td rowspan="2" align="center" scope="col"><span DisplayLabelID="185"><?php echo htmlentities($pageLables[185]); ?></span></td>
                          
                            </tr>
                            <tr>
                              <td align="center" scope="col"><span DisplayLabelID="69"><?php echo htmlentities($pageLables[69]); ?></span></td>
                              <td align="center" scope="col"><span DisplayLabelID="178"><?php echo htmlentities($pageLables[178]); ?></span></td>
                              <td align="center" scope="col">Education</td>
                              <td align="center" scope="col"><span DisplayLabelID="52"><?php echo htmlentities($pageLables[52]); ?></span></td>
                              <td align="center" scope="col"><span DisplayLabelID="69"><?php echo htmlentities($pageLables[69]); ?></span></td>
                              <td align="center" scope="col"><span DisplayLabelID="178"><?php echo htmlentities($pageLables[178]); ?></span></td>
                              <td align="center" scope="col">Pending Education</td>
                              <td align="center" scope="col"><span DisplayLabelID="52"><?php echo htmlentities($pageLables[52]); ?></span></td>
                              
                            </tr>
                            
                            </thead>
                        <tbody>
                        
							<?php
							
			


					 	 
$sel_town_details="select to_char(demanddate::date,'MM') as month_no,to_char(demanddate::date,'Month') as collectionmonth,
sum(case when paidstatus = 'Y' then general_tax else 0 end)as general_tax,
sum(case when paidstatus = 'Y' then library_tax else 0 end)as library_tax,
sum(case when paidstatus = 'Y' then education_tax else 0 end)as education,
sum(case when paidstatus = 'N' then general_tax else 0 end)as pending_general,
sum(case when paidstatus = 'N' then library_tax else 0 end)as pending_library,
sum(case when paidstatus = 'N' then education_tax else 0 end)as pending_education,
sum(case when paidstatus = 'Y' then totaldemand else 0 end )totaldemand,
sum(case when paidstatus = 'N' then totaldemand else 0 end ) pending_totaldemand

from propertytax.t_pp_assessmentdemand where isactive = :isactive and dcode = :dcode and lbcode = :lbcode  and paidstatus in ('Y','N')  and del_flag is NULL group by month_no,collectionmonth order by month_no";


                            
							$sel_town_details_res=$this->prepare($sel_town_details,array(":isactive"=>1,":lbcode"=>$lbcode,":dcode"=>$dcode),2);
							
							if(count($sel_town_details_res)>0)
							{
								$temp_assesmentid='';
								$slno=1;
								
								foreach($sel_town_details_res as $sel_town_details_key=>$sel_town_details_row)
								{
								
								?>
									<tr>
									  
<td rowspan="<?php //$sel_ward_details_row['ward_name_'.$lang_code_2d]; ?>" align="center"><?php echo htmlentities($sel_town_details_row['collectionmonth']); ?></td>

<td rowspan="<?php //$sel_ward_details_row['ward_name_'.$lang_code_2d]; ?>" align="right"><?php echo htmlentities(number_format($sel_town_details_row['general_tax'],2)); ?></td>
<td rowspan="<?php //$sel_ward_details_row['ward_name_'.$lang_code_2d]; ?>" align="right"><?php echo htmlentities(number_format($sel_town_details_row['library_tax'],2)); ?></td>
<td rowspan="<?php //$sel_ward_details_row['ward_name_'.$lang_code_2d]; ?>" align="right"><?php echo htmlentities(number_format($sel_town_details_row['education'],2)); ?></td>
<td rowspan="<?php //$sel_ward_details_row['ward_name_'.$lang_code_2d]; ?>" align="right"><?php echo htmlentities(number_format(($sel_town_details_row['general_tax']+$sel_town_details_row['library_tax']+$sel_town_details_row['education']),2)); ?></td>

<td rowspan="<?php //$sel_ward_details_row['ward_name_'.$lang_code_2d]; ?>" align="right"><?php echo htmlentities(number_format($sel_town_details_row['pending_general'],2)); ?></td>
<td rowspan="<?php //$sel_ward_details_row['ward_name_'.$lang_code_2d]; ?>" align="right"><?php echo htmlentities(number_format($sel_town_details_row['pending_library'],2)); ?></td>
<td rowspan="<?php //$sel_ward_details_row['ward_name_'.$lang_code_2d]; ?>" align="right"><?php echo htmlentities(number_format($sel_town_details_row['pending_education'],2)); ?></td>
<td rowspan="<?php //$sel_ward_details_row['ward_name_'.$lang_code_2d]; ?>" align="right"><?php echo htmlentities(number_format(($sel_town_details_row['pending_general']+$sel_town_details_row['pending_library']+$sel_town_details_row['pending_education']),2)); ?></td>

<td rowspan="<?php //$sel_ward_details_row['ward_name_'.$lang_code_2d]; ?>" align="right"><?php echo htmlentities(number_format($sel_town_details_row['totaldemand'],2)); ?></td>
<td rowspan="<?php //$sel_ward_details_row['ward_name_'.$lang_code_2d]; ?>" align="right"><?php echo htmlentities(number_format($sel_town_details_row['pending_totaldemand'],2)); ?></td>
										
									</tr>
								<?php
								$slno++;
								}
							
							}
							else
							{
								
							?>
                            	<tr>
                                	<td colspan="19" class="text-center text-danger font-weight-bold h5">
                                    	Record Not Found
                                    </td>
                                </tr>
                            <?php	
							}
                            ?>
                        </tbody>
                        <?php
						if(count($sel_town_details_res)>0)
						{
						?>
                            <tfoot class="font-weight-bold">  
                                <tr>
                                    <td  align="right">Total</td>
                                    
                                  <td align="right"><?php echo htmlentities(number_format(array_sum(array_column($sel_town_details_res,'general_tax')),2)); ?></td>
                                  <td align="right"><?php echo htmlentities(number_format(array_sum(array_column($sel_town_details_res,'library_tax')),2)); ?></td>
                                  <td align="right"><?php echo htmlentities(number_format(array_sum(array_column($sel_town_details_res,'education')),2)); ?></td>
                                  <td align="right"><?php echo htmlentities(number_format(array_sum(array_column($sel_town_details_res,'general_tax'))+array_sum(array_column($sel_town_details_res,'library_tax'))+array_sum(array_column($sel_town_details_res,'education')),2)); ?></td>
                                  
                                  <td align="right"><?php echo htmlentities(number_format(array_sum(array_column($sel_town_details_res,'pending_general')),2)); ?></td>
                                  <td align="right"><?php echo htmlentities(number_format(array_sum(array_column($sel_town_details_res,'pending_library')),2)); ?></td>
                                  <td align="right"><?php echo htmlentities(number_format(array_sum(array_column($sel_town_details_res,'pending_education')),2)); ?></td>
								  <td align="right"><?php echo htmlentities(number_format(array_sum(array_column($sel_town_details_res,'pending_general'))+array_sum(array_column($sel_town_details_res,'pending_library'))+array_sum(array_column($sel_town_details_res,'pending_education')),2)); ?></td>
                                  
                                  <td align="right"><?php echo htmlentities(number_format(array_sum(array_column($sel_town_details_res,'totaldemand')),2)); ?></td>
                                  <td align="right"><?php echo htmlentities(number_format(array_sum(array_column($sel_town_details_res,'pending_totaldemand')),2)); ?></td>
                                  
                                 
                                </tr>
                            </tfoot>
                        <?php
						} 
						?>
                    </table>
</div> 
                    <?php 

	
	        $ob_output_main_contents = ob_get_contents();
			
       ob_clean();

return array(
"STATUS" => "SUCCESS",
"STATUS_TYPE" => "FIELD",
"DATA" => $ob_output_main_contents
);


}





}

$Home = new MonlyDCBReport();






if(!isset($_POST['cmd']))
{		

	//if(isset($_GET['pdf'])) {
		//$Home->collect_details($_GET);

//	} else {
		
		$Home->main_content(array_merge($_GET,array()));exit;
	//}
}
else if(isset($_POST['cmd']))
{

	$cmd=base64_decode($_POST['cmd']);
	$lang_code_2d=$Home->getCurrentUserLanguage2D();
	 $pageLables=$Home->GetPageLables(116);
	$state_code=$Home->getCurrentStateCode();
	
if($cmd==1)
	{
		$dcode=base64_decode($_POST['dcode']);
	?>
		<option value="">Select Town panchayat</option>
    <?php

		$sel_town_details="SELECT lbcode,lbody_name_en FROM master.m_localbodies WHERE state_code=:state_code AND dcode=:dcode AND lbtype=:lbtype";
		$sel_town_details_res=$Home->prepare($sel_town_details,array(":state_code"=>$state_code,":dcode"=>$dcode,":lbtype"=>'TP'),2);
		foreach($sel_town_details_res as $sel_town_details_key=>$sel_town_details_row)
		{
		?>
			<option value="<?php echo htmlentities($sel_town_details_row['lbcode']); ?>"><?php echo htmlentities($sel_town_details_row['lbody_name_en']); ?></option>
		<?php
		}
		exit;
	}
	

	
		if($cmd==2)
	{
		$result_data=array();
		$dcode=base64_decode($_POST['dcode']);
		$lbcode=base64_decode($_POST['lbcode']);
		$taxtypeid=base64_decode($_POST['taxtypeid']); 
	
		
		
		/*$dcode_Validation = $Home->Field_Validation(array(
                "Field_Type" => "number",
                "Field_Value" => $dcode,
				"Field_Label_Name"=>"District"
            ));
			
			//print_r($fin_year_Validation); e
			if ($dcode_Validation['Status'] == "Error") {
                $result_data=array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "dcode",
                    "MESSAGE" => "Invalid District"
                );
				
				echo json_encode($result_data);
				exit;
				
            }*/


$result_data=$Home->collect_details($_POST,$pageLables);
echo json_encode($result_data);
		
		
		exit;
	
	}
}

?>            