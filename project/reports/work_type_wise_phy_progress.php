<?php
if(isset($_GET['xls'])){
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
	header("Cache-Control: no-store, no-cache, must-revalidate");   // HTTP/1.1
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");                                     // HTTP/1.0
	header("Content-type: application/force-download");
	header("Content-type: application/octet-stream");
	header("Content-type: application/msexcel");
	header('Content-Disposition: attachment; filename="physical_progress_report.xls";');  
}
require_once __DIR__ . '/../config/config.php';

class work_type_report extends ConfigClass
{
	public $page_token = "work_type_report_token";
    public function __construct()
    {
		 if (! isset($this->db)) {
        }
    }

    public function main_form($data_array = array())
    {
		ob_start();
		$site_data = $this->siteData();
		$role_code=$_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'];
		$state_code=$this->getCurrentStateCode()!=null?$this->getCurrentStateCode():33;
		?>
        <style>
			.newhead {
				background: linear-gradient(to right, #494889, #3B3A7C, #494889);
				color: white;
			}
			.tndtp_form_table {
				font-size: 15px;
				font-weight: bold;
				width: 100%;
			}
			
			.tndtp_form_table thead {
				padding: 3px
			}
			
			.tndtp_report_table {
				font-size: 15px;
				font-weight: bold;
				width: 100%;
				border-radius: 10px;
				text-align: center;
			}
			
			.tndtp_form_report_table th,
			td {
				padding: 10px;
				text-align: center;
			}
			.card {
				padding: 20px;
				margin: 20px;
				border-radius: 7px;
				box-shadow: 3px 3px 10px rgb(0 0 0 / 40%) inset;
				background: #fff;
			}
			.schemebuton {
				background: #F56217;
				color: white;
				font-size: 15px;
				border-radius: 7px;
				font-weight: bold;
				padding: 5px;
				margin: 3px;
				border: none;
			}
			@media (max-width: 600px) {
			
				.tndtp_form_report_table,
				.tndtp_form_table {
					width: 100%;
					display: block;
					overflow-x: auto;
				}
				.tndtp_form_report_table thead,
				.tndtp_form_table thead {
					display: none;
				}
			}
		</style>
        <script>
		$(document).ready(function(){
				//	Scheme Group and Sheme link
				$(document).on('change', '#fin_year', function() {
					var fin_year = $("#fin_year").val();	
					if(fin_year != '')
					{			
						$.ajax({
							url: "work_type_wise_phy_progress.php",
							data: {"fin_year":btoa(fin_year), "cmd":btoa(3)},
							type: 'post',
							success: function (data){
								if(data != '')
								{	
									$('#schemegrp').html(data);	
								}
								else{
									$('#schemegrp').html();
								}
							},
							dataType: 'html'
						});
						return true;
					}
					else
					{
						alert("Select Scheme");
						return false;
					}
				});
			//	Scheme Group and Sheme link
			$(document).on('change', '#schemegrp', function() {
				if($("#fin_year").val() !=''){
					var fin_year = $("#fin_year").val();	
				}else{
					alert("Select Financial Year");
					return false;
				}
				if($("#schemegrp").val() !=''){
					var schemegrp = $("#schemegrp").val();	
				}else{
					alert("Select Scheme Group");
					return false;
				}
				if(fin_year !='' && schemegrp != '')
				{			
					$.ajax({
						url: "work_type_wise_phy_progress.php",
						data: {"fin_year":btoa(fin_year), "schemegrp":btoa(schemegrp), "cmd":btoa(1)},
						type: 'post',
						success: function (data){
							if(data != '')
							{	
								$('#scheme').html(data);
							}else{
								$('#scheme').html();
							}
						},
						dataType: 'html'
					});
					return true;
				}
				else
				{
					alert("Select Scheme Group");
					return false;
				}
			});
				//	Scheme and Work Group link
				 $("#scheme").change(function() {
					if($("#fin_year").val() !=''){
						var fin_year = $("#fin_year").val();	
					}else{
						alert("Select Financial Year");
						return false;
					}
					if($("#schemegrp").val() !=''){
						var sgid = $("#schemegrp").val();	
					}else{
						alert("Select Scheme Group Name");
						return false;
					}
					if($("#scheme").val() != ''){
						var sid = $("#scheme").val();
					}else{
						alert("Select Scheme Name");
						return false;
					}
					if(fin_year !='' && sgid != '' && sid !='')
					{			
						$.ajax({
							url: "work_type_wise_phy_progress.php",
							data: {"sgid":btoa(sgid), "sid":btoa(sid), "cmd":btoa(2)},
							type:'post',
							success: function (data){
								if(data != '')
								{	
								  $('#wrkgrp').html(data);				
								}
							},
							dataType: 'html'
						});
						return true;
					}
					else
					{
						alert("Select Work Group Name");
						return false;
					}
				});
		});
		function showfrm()
		{
			if(document.getElementById('fin_year').value=='')
			{
				alert('Select Financial Year');
				document.getElementById('fin_year').focus()
				return false;
			}
			else if(document.getElementById('schemegrp').value=='')
			{
				alert('Select Scheme Group');
				document.getElementById('schemegrp').focus()
				return false;
			}
			else if(document.getElementById('scheme').value=='')
			{
				alert('Select Scheme');
				document.getElementById('scheme').focus()
				return false;
			}
			else if(document.getElementById('wrkgrp').value=='')
			{
				alert('Select Work Group');
				document.getElementById('wrkgrp').focus()
				return false;
			}
			else
			{
				document.getElementById("work_type_wise_phy").action='work_type_wise_phy_progress.php';
				document.getElementById("work_type_wise_phy").submit();
				return true;
			}
		}
		</script>
		<div class="container mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="col-lg-12 col-ml-12">
                    <?php if(!isset($data_array['pdf']) && !isset($data_array['xls'])) { 
					 
                        if (isset($data_array["STATUS"])) {
                            echo $this->ShowMessage($data_array["STATUS"], $data_array["MESSAGE"]);
                        }
                        ?>
                    	<form id="work_type_wise_phy" name="work_type_wise_phy" method="post"  enctype="multipart/form-data"  autocomplete="off">
                        	<input class="form-control form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>" name="<?php echo htmlentities($this->page_token); ?>" value="<?php echo htmlentities($this->token($this->page_token)); ?>">
                            <table class="table-bordered tndtp_form_table">
                                <thead>
                                    <tr>
                                        <td colspan="2" class="text-center newhead">Work Type wise Physical Progress 
                                        	<button type="button" class="schemebuton float-end" onClick="location.href = '<?php echo htmlentities($site_data->website_url); ?>project/home.php?id=<?php echo htmlentities(base64_encode(4));?>';">
                                        		<i class="fa fa-arrow-circle-left"></i> Back To Menu</button>
                                        </td>
                                    </tr>
                                    <?php /*?><tr>
                                        <th align="center" colspan="2"><span style="color:red">Use Ctrl and Left Click simultaneously to select Multiple items</span></th>
                                    </tr><?php */?>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td align="left">Financial Year</td>
                                        <td align="left">
                                            <select name="fin_year" <?php /*?>multiple="multiple"<?php */?> class="form-control w-50 form-control-sm" id="fin_year" >
                                            	<option value="" > Choose Financial Year</option>
                                                <?php  
                                                $sel_yr="select * from master.m_fin_year where del_flag is null order by fin_year";
                                                $sel_yr_res=$this->prepare($sel_yr,array(),2);
                                                foreach($sel_yr_res as $selrow)
                                                {                                            
                                                ?>
                                                    <option value="<?php echo htmlentities($selrow['fin_year']); ?>"> <?php echo htmlentities($selrow['fin_year']); ?></option>
                                                <?php
                                                }				 
                                                ?>
                                            </select>
                                            <script>
												document.getElementById('fin_year').value='<?php echo htmlentities(isset($data_array['fin_year'])?$data_array['fin_year']:''); ?>';
											</script>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left">Scheme Group</td>
                                        <td align="left">
                                            <select name="schemegrp" <?php /*?>multiple="multiple"<?php */?> class="form-control w-50 form-control-sm" id="schemegrp"  >
                                            	<option value="" > Choose Scheme Group</option>
                                              	<?php  
											  	if(isset($data_array['fin_year']) && $data_array['fin_year']!=''){
													$fin_year = $data_array['fin_year'];
                                                    $sel_sg=$this->get_scheme_group_list($fin_year);
                                                    foreach($sel_sg as $sgrow)
                                                    {
                                                        ?>
                                                        <option value="<?php echo htmlentities($sgrow['scheme_group_id']); ?>" ><?php echo htmlentities($sgrow['scheme_group_name_en']); ?>	</option>
                                                        <?php
                                                    }	
												}	 
                                                    ?>
                                            </select>
                                            <script>
												document.getElementById('schemegrp').value='<?php echo htmlentities(isset($data_array['schemegrp'])?$data_array['schemegrp']:''); ?>';
											</script>
                                            <?php /*?><input type= "checkbox" name ="all_schemegrp" id="all_schemegrp"<?php if(isset($_REQUEST['all_schemegrp']))  {?> checked="checked" <?php } ?> onchange=" if(this.checked==true) {  $('#schemegrp option').prop('selected', true); $('#schemegrp').trigger('change'); }  else { $('#schemegrp option').prop('selected', false); $('#schemegrp').trigger('change'); }"  />All Scheme Group<?php */?>
                                        </td>
                                    </tr>
                                    <tr >
                                        <td  align="left">Scheme Name</td>
                                        <td align="left">
                                          <select name="scheme"  <?php /*?>multiple="multiple"<?php */?>  class="form-control w-50 form-control-sm"  id="scheme"  >         
                                            <?php 
                                                if(isset($data_array['schemegrp']) &&  $data_array['schemegrp']!='' && isset($data_array['fin_year']) && $data_array['fin_year']!='')
                                                {
													$fin_year = $data_array['fin_year'];
                                                  	$sgrp=$data_array['schemegrp'];
                                                    $schnam=$this->getschemelist($fin_year, $sgrp);
                                                      foreach($schnam as $row)
                                                      {
                                                          ?>
                                                          <option value="<?php echo htmlentities($row['scheme_seq_id']); ?>" > <?php echo htmlentities($row['scheme_name_en']); ?> </option>
                                                          <?php
                                                      }
                                                }
                                            ?>
                                          </select>
                                          <script>
												document.getElementById('scheme').value='<?php echo htmlentities(isset($data_array['scheme'])?$data_array['scheme']:''); ?>';
											</script>
                                          </td>
                                    </tr>
                                    <tr >
                                        <td align="left">Work Group</td>
                                        <td align="left">
                                          <select name="wrkgrp"  <?php /*?>multiple="multiple"<?php */?>  class="form-control w-50 form-control-sm"  id="wrkgrp"  >         
                                            <?php 
                                                 if(isset($data_array['schemegrp']) &&  $data_array['schemegrp']!='' && isset($data_array['fin_year']) && $data_array['fin_year']!='' && isset($data_array['scheme']) && $data_array['scheme']!='')
                                                {
													$fin_year = $data_array['fin_year'];
                                                  	$sgrp=$data_array['schemegrp'];
                                                  	$scheme=$data_array['scheme'];
                                                    $schnam=$this->get_work_group_list( $sgrp, $scheme);
                                                      foreach($schnam as $row)
                                                      {
                                                          ?>
                                                          <option value="<?php echo htmlentities($row['work_group_id']); ?>" > <?php echo htmlentities($row['wrkgrpname_en']); ?> </option>
                                                          <?php
                                                      }
                                                }
                                            ?>
                                          </select>
                                          <script>
												document.getElementById('wrkgrp').value='<?php echo htmlentities(isset($data_array['wrkgrp'])?$data_array['wrkgrp']:''); ?>';
											</script>
                                          <?php /*?><input type= "checkbox" name ="all_workgrp" id="all_workgrp" <?php if(isset($_REQUEST['all_workgrp']))  {?> checked="checked" <?php } ?> onchange=" if(this.checked==true) {  $('#wrkgrp option').prop('selected', true); $('#wrkgrp').trigger('change'); }  else { $('#wrkgrp option').prop('selected', false); $('#wrkgrp').trigger('change'); }" />All Work Group
<?php */?>                                        </td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="center">
                                        <input type="submit" name="show" id="show" value="View Report" onclick="javascript:return showfrm()" />
                                      </td>
                                    </tr>
                                </tbody>
                            </table>
                    	</form> <?php } ?>
                    </div>
                </div>
                <?php if((isset($data_array['show']) && $data_array['show']!='') || (isset($data_array['xls']) && base64_decode($data_array['xls']) == 1)){
					if(!isset($_GET['xls'])){
						$finyr=$data_array['fin_year'];
						$schgrpid=$data_array["schemegrp"];
						$schemeid=$data_array["scheme"];
						$wrkgrpid=$data_array["wrkgrp"];
					} else if(base64_decode($data_array['xls']) == 1){
						$finyr=base64_decode($data_array['fin_year']);
						$schgrpid=base64_decode($data_array["schemegrp"]);
						$schemeid=base64_decode($data_array["scheme"]);
						$wrkgrpid=base64_decode($data_array["wrkgrp"]);
					}
					$dcode= $this->issetCurrentDistrictCode()?$this->getCurrentDistrictCode():'';
					$lbcode=$this->getCurrentLocalBodyCode()?$this->getCurrentLocalbodyCode():'';
					$worktype='';
					if($finyr!='' ){
						$worktype.=" fin_year = :fin_year " ;
					}
					if($schgrpid!='')
						$worktype.=" and scheme_group_id = :schgrpid " ;
					if($schemeid!='')
						$worktype.=" and scheme_id = :schemeid" ;
					if($wrkgrpid!='')
						$worktype.=" and work_group_id = :wrkgrpid" ; 
					if($dcode!='')
						$worktype.=" and dcode=:dcode " ;
					if($lbcode!='')
						$worktype.=" and lbcode=:lbcode " ;
					$group_by_sel='';
						if($schgrpid!='' )
							$group_by_sel.=" a.scheme_group_id, " ;
						if($schemeid >0 )
							$group_by_sel.=" a.scheme_id, " ;
					if($dcode == '' && $lbcode == '')
					{
						$sql="select  a.*, b.work_name, c.work_group_name,d.scheme_name from 
(SELECT scheme_group_id, scheme_id, work_group_id, work_type_id, count(1) as cnt,	
sum(case when current_stage_of_work=5 then 1 else 0 end) as not_started,
sum(case when current_stage_of_work=15 then 1 else 0 end) as comp, 
sum(case when (current_stage_of_work!=15 or current_stage_of_work is null) then 1 else 0 end) as bal, 
sum(case when latest_as_amount is not null then latest_as_amount else as_value end) as asamt, 
sum(amount_spent_sofar) as asexp from works.t_works where $worktype group by scheme_group_id, scheme_id, work_group_id, work_type_id)a
							LEFT JOIN
							(select work_group_id, work_name_en as work_name, work_id from (select work_group_id, work_id from master.m_work_type where del_flag is null)a left join (select work_type_id, work_name_en  from master.m_work_type_name where del_flag is null ) b on a.work_id = b.work_type_id )b 
							ON a.work_group_id=b.work_group_id and a.work_type_id=b.work_id
							left join (select wrkgrp_id, wrkgrpname_en as work_group_name from master.m_workgroup where del_flag is null)c on a.work_group_id=c.wrkgrp_id
							left join  (select scheme_seq_id, scheme_group_code, scheme_name_en as scheme_name from master.m_scheme where del_flag is null ) as d on a.scheme_id=d.scheme_seq_id
							ORDER BY d.scheme_name,c.work_group_name,b.work_name " ;
							$sel_qry_res=$this->prepare($sql,array(":fin_year"=>$finyr, ":schgrpid" =>$schgrpid, ":schemeid" => $schemeid, ":wrkgrpid" =>$wrkgrpid),2);
					}
					if($dcode !='' && $lbcode !='')
					{
						 $sql="select  a.*, b.work_name, c.work_group_name,d.scheme_name from 
(SELECT scheme_group_id, scheme_id, work_group_id, work_type_id, count(1) as cnt,	
sum(case when current_stage_of_work=5 then 1 else 0 end) as not_started,
sum(case when current_stage_of_work=15 then 1 else 0 end) as comp, 
sum(case when (current_stage_of_work!=15 or current_stage_of_work is null) then 1 else 0 end) as bal, 
sum(case when latest_as_amount is not null then latest_as_amount else as_value end) as asamt, 
sum(amount_spent_sofar) as asexp from works.t_works where $worktype group by scheme_group_id, scheme_id, work_group_id, work_type_id)a
							LEFT JOIN
							(select work_group_id, work_name_en as work_name, work_id from (select work_group_id, work_id from master.m_work_type where del_flag is null)a left join (select work_type_id, work_name_en  from master.m_work_type_name where del_flag is null ) b on a.work_id = b.work_type_id )b 
							ON a.work_group_id=b.work_group_id and a.work_type_id=b.work_id
							left join (select wrkgrp_id, wrkgrpname_en as work_group_name from master.m_workgroup where del_flag is null)c on a.work_group_id=c.wrkgrp_id
							left join  (select scheme_seq_id, scheme_group_code, scheme_name_en as scheme_name from master.m_scheme where del_flag is null ) as d on a.scheme_id=d.scheme_seq_id
							ORDER BY d.scheme_name,c.work_group_name,b.work_name " ;
							$sel_qry_res=$this->prepare($sql,array(":fin_year"=>$finyr, ":schgrpid" =>$schgrpid, ":schemeid" => $schemeid, ":wrkgrpid" =>$wrkgrpid, ":dcode" => $dcode, ":lbcode" => $lbcode),2);
					} 
					?>
                	<div class="card-body">
                    <div class="col-lg-12 col-ml-12">
                    	<br />
                        <?php if(!isset($_GET['xls'])){ ?>
                                    <div>
                                        <div style="text-align: center;" class="my-2 text-b">
                                      		<a href="?xls=<?php echo base64_encode(1); ?>&fin_year=<?php echo base64_encode($finyr); ?>&schemegrp=<?php echo base64_encode($schgrpid); ?>&scheme=<?php echo base64_encode($schemeid); ?>&wrkgrp=<?php echo base64_encode($wrkgrpid); ?>">Download XLS<img width="25px" height="25px" src="../../images/excel.png" /></a>
                                        </div>
                                    </div>
                                    <?php } ?>
                          	<table class="table-bordered tndtp_form_table" id="sorternew" border="1">
                        		<thead>
                                    <tr>
                                        <td colspan="9" class="text-center newhead">Work Type wise Physical Progress Report</td>
                                    </tr>
                                    <tr>
                                      <td rowspan="2" align="center">Sl.No.</td>
                                      <td rowspan="2" align="center">Scheme Name</td>
                                      <td rowspan="2" align="center">Work Group Name</td>
                                      <td rowspan="2" align="center">Name of Work Type</td>
                                      <td colspan="5" align="center">Physical Progress</td>
                                    </tr>
                                    <tr align="center" valign="middle">
                                      <td align="center">Works Takenup</td>
                                      <td align="center">Works Completed</td>
                                      <td align="center">Pending Works</td>
                                      <td align="center">Not Started</td>
                                      <td align="center">% of Completion</td>
                                    </tr>
                        		</thead>
                                <tbody>
                                	<?php if(count($sel_qry_res) > 0){ 
										foreach($sel_qry_res as $sel_qry_key => $sel_qry_row){ 
										/*$filename="work_type_wise_phy_progress_sl.php?fin_year=".base64_encode($finyr)."&schgrpid=".base64_encode($schgrpid)."&schemeid=".base64_encode($schemeid)."&workgrpid=".base64_encode($wrkgrpid)."&work_id=".base64_encode($sel_qry_row['work_id']);
										if($sel_qry_row['cnt']!=''){
											$string="<a href='$filename'>".strtoupper($sel_qry_row['work_name']?$sel_qry_row['work_name']:$sel_qry_row['work_name'])."</a>";
										}else{
											$string=strtoupper($sel_qry_row['work_name']?$sel_qry_row['work_name']:$sel_qry_row['work_name']);
										}*/
										?>
                                            <tr>
                                                <td><?php echo htmlentities($sel_qry_key+1);?></td>
                                                <td><?php echo htmlentities($sel_qry_row['scheme_name']);?></td>
                                                <td><?php echo htmlentities($sel_qry_row['work_group_name']);?></td>
                                                <td><?php echo htmlentities($sel_qry_row['work_name'] !='' ?$sel_qry_row['work_name']:'');?></td>
                                                <td>
                                                	<?php if(!isset($_GET['xls'])){ ?>
                                                		<a href="work_type_wise_phy_progress_details.php?fin_year=<?php echo base64_encode($finyr) ; ?>&schgrpid=<?php echo base64_encode($schgrpid); ?>&schemeid=<?php echo base64_encode($schemeid); ?>&workgrpid=<?php echo base64_encode($wrkgrpid); ?>&work_type=<?php echo base64_encode($sel_qry_row['work_type_id']); ?>&type=<?php echo base64_encode('cnt'); ?>" > <?php echo htmlentities($sel_qry_row['cnt']); ?></a>
                                                	<?php } else{echo htmlentities($sel_qry_row['cnt']);} ?>
                                                </td>
                                                <td>
                                                	<?php if(!isset($_GET['xls'])){ ?>
                                                    	<a href="work_type_wise_phy_progress_details.php?fin_year=<?php echo base64_encode($finyr) ; ?>&schgrpid=<?php echo base64_encode($schgrpid); ?>&schemeid=<?php echo base64_encode($schemeid); ?>&workgrpid=<?php echo base64_encode($wrkgrpid); ?>&work_type=<?php echo base64_encode($sel_qry_row['work_type_id']); ?>&type=<?php echo base64_encode('comp'); ?>" > <?php echo htmlentities($sel_qry_row['comp']); ?></a>
                                                    <?php } else{echo htmlentities($sel_qry_row['comp']);}?>
                                                </td>
                                                <td>
													<?php if(!isset($_GET['xls'])){ ?>
                                                        <a href="work_type_wise_phy_progress_details.php?fin_year=<?php echo base64_encode($finyr) ; ?>&schgrpid=<?php echo base64_encode($schgrpid); ?>&schemeid=<?php echo base64_encode($schemeid); ?>&workgrpid=<?php echo base64_encode($wrkgrpid); ?>&work_type=<?php echo base64_encode($sel_qry_row['work_type_id']); ?>&type=<?php echo base64_encode('bal'); ?>" > <?php echo htmlentities($sel_qry_row['bal']); ?></a>
                                                    <?php } else{echo htmlentities($sel_qry_row['bal']);}?>
                                                </td>
                                                <td>
													<?php if(!isset($_GET['xls'])){ ?>
                                                        <a href="work_type_wise_phy_progress_details.php?fin_year=<?php echo base64_encode($finyr) ; ?>&schgrpid=<?php echo base64_encode($schgrpid); ?>&schemeid=<?php echo base64_encode($schemeid); ?>&workgrpid=<?php echo base64_encode($wrkgrpid); ?>&work_type=<?php echo base64_encode($sel_qry_row['work_type_id']); ?>&type=<?php echo base64_encode('not_started'); ?>" > <?php echo htmlentities($sel_qry_row['not_started']); ?></a>
                                                    <?php } else{echo htmlentities($sel_qry_row['not_started']);}?>
                                                </td>
                                                <td align="right">
													<?php  $per=($sel_qry_row['comp']/$sel_qry_row['cnt'])*100 ; 
													echo number_format(round($per,2),2); ?>
                                                </td>
                                            </tr>
                                            <?php } 
									}else{?>
                                    	<tr>
                                       		<td colspan="9" class="text-danger">Records Not Found</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                          </table>
                          
                    	</form>
                    </div>
                </div>
                
                <?php } ?>
            </div>
        </div>
    	<?php
		$ob_output_main_forms = ob_get_contents();
        ob_clean();
		if(!isset($data_array['pdf']) && !isset($data_array['xls']))
		{
			$this->Template($this->getCurrentUserTemplate()!=""?$this->getCurrentUserTemplate():"Template1", "Work Type Wise Physical Progress Report", $ob_output_main_forms,array(),array('page_id'=>12));
		}
		else if( isset($data_array['xls']))
		{
			echo ($ob_output_main_forms);
		}
        exit();
	}	
}
$work_type_report = new work_type_report();
if(!isset($_POST['cmd'])){
	if(isset($_POST['show']) && $_POST['show']!='') {		
		if(isset($_POST['fin_year']) && $_POST['fin_year']!=''){
			$fin_year=($_POST['fin_year']);
			$fin_year_Validation=$work_type_report->Field_Validation(
			array
			(
			'Field_Type'=>'fin_year',
			'Field_Value'=>$fin_year,
			'Field_Name'=>'fin_year',
			'Field_Label_Name'=>'Financial Year'
			));
			if($fin_year_Validation['Status'] == "Error") {
				$Result_Data['STATUS']='ERROR';
				$Result_Data['MESSAGE']='Invalid Financial Year';
				$work_type_report->main_form(array_merge($Result_Data));		
			}
		}else{
			$Result_Data['STATUS']='ERROR';
			$Result_Data['MESSAGE']='Select Financial Year';
			$work_type_report->main_form(array_merge($Result_Data));
		}
		if(isset($_POST['schemegrp']) && $_POST['schemegrp']!=''){
			$schemegrp=($_POST['schemegrp']);
			$schemegrp_Validation=$work_type_report->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$schemegrp,
			'Field_Name'=>'schemegrp',
			'Field_Max_length'=>'5',
			'Field_Label_Name'=>'Scheme Group'
			));
			if($schemegrp_Validation['Status'] == "Error") {
				$Result_Data['STATUS']='ERROR';
				$Result_Data['MESSAGE']='Invalid Scheme Group';
				$work_type_report->main_form(array_merge($Result_Data));	
			}
		}else{
			$Result_Data['STATUS']='ERROR';
			$Result_Data['MESSAGE']='Select Scheme Group';
			$work_type_report->main_form(array_merge($Result_Data));
		}
		if(isset($_POST['scheme']) && $_POST['scheme']!=''){
			$scheme=($_POST['scheme']);
			$scheme_Validation=$work_type_report->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$scheme,
			'Field_Name'=>'scheme',
			'Field_Max_length'=>'5',
			'Field_Label_Name'=>'Scheme'
			));
			if($scheme_Validation['Status'] == "Error") {
				$Result_Data['STATUS']='ERROR';
				$Result_Data['MESSAGE']='Invalid Scheme';
				$work_type_report->main_form(array_merge($Result_Data));	
			}
		}else{
			$Result_Data['STATUS']='ERROR';
			$Result_Data['MESSAGE']='Select Scheme';
			$work_type_report->main_form(array_merge($Result_Data));
		}	
		if(isset($_POST['wrkgrp']) && $_POST['wrkgrp']!=''){
			$wrkgrp=($_POST['wrkgrp']);
			$wrkgrp_Validation=$work_type_report->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$wrkgrp,
			'Field_Name'=>'wrkgrp',
			'Field_Max_length'=>'5',
			'Field_Label_Name'=>'Work Group'
			));
			if($wrkgrp_Validation['Status'] == "Error") {
				$Result_Data['STATUS']='ERROR';
				$Result_Data['MESSAGE']='Invalid Work Group';
				$work_type_report->main_form(array_merge($Result_Data));	
			}
		}else{
			$Result_Data['STATUS']='ERROR';
			$Result_Data['MESSAGE']='Select Work Group';
			$work_type_report->main_form(array_merge($Result_Data));
		}
		
	}
	$work_type_report->main_form(array_merge($_GET, $_POST));
}else if(isset($_POST['cmd']) && $_POST['cmd']!=''){
	$cmd = base64_decode($_POST['cmd']);
	$key_Validation=$work_type_report->Field_Validation(
	array
	(
	'Field_Type'=>'number',
	'Field_Value'=>$cmd,
	'Field_Name'=>'key',
	'Field_length'=>'1',
	'Field_Label_Name'=>'key'
	));
	if($key_Validation['Status'] == "Error") {
		$Result_Data['STATUS']='ERROR';
		$Result_Data['MESSAGE']='Invalid Key';
		return json_encode($Result_Data);
		exit;		
	}
	if($cmd == 1){
		if(isset($_POST['fin_year']) && $_POST['fin_year']!=''){
			$fin_year=base64_decode($_POST['fin_year']);
			$fin_year_Validation=$work_type_report->Field_Validation(
			array
			(
			'Field_Type'=>'fin_year',
			'Field_Value'=>$fin_year,
			'Field_Name'=>'fin_year',
			'Field_Label_Name'=>'Financial Year'
			));
			if($fin_year_Validation['Status'] == "Error") {
				$Result_Data['STATUS']='ERROR';
				$Result_Data['MESSAGE']='Invalid Financial Year';
				return json_encode($Result_Data);
				exit;		
			}
		}else{
			$Result_Data['STATUS']='ERROR';
			$Result_Data['MESSAGE']='Select Financial Year';
			return json_encode($Result_Data);
			exit;	
		}		
		if(isset($_POST['schemegrp']) && $_POST['schemegrp']!=''){
			$schemegrp=base64_decode($_POST['schemegrp']);
			$schemegrp_Validation=$work_type_report->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$schemegrp,
			'Field_Name'=>'schemegrp',
			'Field_Max_length'=>'5',
			'Field_Label_Name'=>'Scheme Group'
			));
			if($schemegrp_Validation['Status'] == "Error") {
				$Result_Data['STATUS']='ERROR';
				$Result_Data['MESSAGE']='Invalid Scheme Group';
				return json_encode($Result_Data);
				exit;		
			}
		}else{
			$Result_Data['STATUS']='ERROR';
			$Result_Data['MESSAGE']='Select Scheme Group';
			return json_encode($Result_Data);
			exit;	
		}
		
		$schemegrplist=$work_type_report->getschemelist($fin_year, $schemegrp);
		ob_start();
		?>
        	<option value="" DisplayLabelID="255">Choose Scheme</option>
        <?php
		foreach($schemegrplist as $sel_assess_details_key => $sel_assess_details_row)
		{
		?>
        	<option value="<?php echo htmlentities($sel_assess_details_row['scheme_seq_id']); ?>"><?php echo htmlentities($sel_assess_details_row['scheme_name_en']!=''?$sel_assess_details_row['scheme_name_en']:''); ?></option>
        <?php
		}
		exit;
	}
	
	if($cmd == 2){
		if(isset($_POST['sgid']) && $_POST['sgid']!=''){
			$schemegrp=base64_decode($_POST['sgid']);
			$schemegrp_Validation=$work_type_report->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$schemegrp,
			'Field_Name'=>'schemegrp',
			'Field_Max_length'=>'5',
			'Field_Label_Name'=>'Scheme Group'
			));
			if($schemegrp_Validation['Status'] == "Error") {
				$Result_Data['STATUS']='ERROR';
				$Result_Data['MESSAGE']='Invalid Scheme Group';
				return json_encode($Result_Data);
				exit;		
			}
		}else{
			$Result_Data['STATUS']='ERROR';
			$Result_Data['MESSAGE']='Select Scheme Group';
			return json_encode($Result_Data);
			exit;	
		}
		if(isset($_POST['sid']) && $_POST['sid']!=''){
			$scheme=base64_decode($_POST['sid']);
			$scheme_Validation=$work_type_report->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$scheme,
			'Field_Name'=>'scheme',
			'Field_Max_length'=>'5',
			'Field_Label_Name'=>'Scheme'
			));
			if($scheme_Validation['Status'] == "Error") {
				$Result_Data['STATUS']='ERROR';
				$Result_Data['MESSAGE']='Invalid Scheme';
				return json_encode($Result_Data);
				exit;		
			}
		}else{
			$Result_Data['STATUS']='ERROR';
			$Result_Data['MESSAGE']='Select Scheme';
			return json_encode($Result_Data);
			exit;	
		}
		
		$work_group_list=$work_type_report->get_work_group_list($schemegrp, $scheme);
		ob_start();
		?>
        	<option value="" DisplayLabelID="255">Choose Work Group</option>
        <?php
		foreach($work_group_list as $sel_assess_details_key => $sel_assess_details_row)
		{
		?>
        	<option value="<?php echo htmlentities($sel_assess_details_row['work_group_id']); ?>"><?php echo htmlentities($sel_assess_details_row['wrkgrpname_en']); ?></option>
        <?php
		}
		exit;
	}
	if($cmd == 3){
		if(isset($_POST['fin_year']) && $_POST['fin_year']!=''){
			$fin_year=base64_decode($_POST['fin_year']);
			$fin_year_Validation=$work_type_report->Field_Validation(
			array
			(
			'Field_Type'=>'fin_year',
			'Field_Value'=>$fin_year,
			'Field_Name'=>'fin_year',
			'Field_Label_Name'=>'Financial Year'
			));
			if($fin_year_Validation['Status'] == "Error") {
				$Result_Data['STATUS']='ERROR';
				$Result_Data['MESSAGE']='Invalid Financial Year';
				return json_encode($Result_Data);
				exit;		
			}
		}else{
			$Result_Data['STATUS']='ERROR';
			$Result_Data['MESSAGE']='Select Financial Year';
			return json_encode($Result_Data);
			exit;	
		}		
		$scheme_group_list=$work_type_report->get_scheme_group_list($fin_year);
		ob_start();
		?>
        	<option value="" DisplayLabelID="255">Choose Scheme Group</option>
        <?php
		foreach($scheme_group_list as $sel_assess_details_key => $sel_assess_details_row)
		{
		?>
        	<option value="<?php echo htmlentities($sel_assess_details_row['scheme_group_id']); ?>"><?php echo htmlentities($sel_assess_details_row['scheme_group_name_en']); ?></option>
        <?php
		}
		exit;
	}
}
