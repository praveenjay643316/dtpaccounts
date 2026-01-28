<?php

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
			.cards {
				padding: 20px;
				margin: 20px;
				border-radius: 7px;
				box-shadow: 3px 3px 10px rgb(0 0 0 / 40%) inset;
				background: #fff;
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
		});
		</script>
		<div class="container mt-3">
            <div class="card">
                
                <?php 
					if(!isset($_GET['xls'])){
						$finyr=base64_decode($_GET['fin_year']);
						//$finyr=explode(",",$finyr);
						$schgrpid=base64_decode($_GET["schgrpid"]);
						//$schgrpid=explode(",",$schgrpid);
						//$schgrpid_org=base64_decode($_POST["cmb_schemegrp"]);
						//$schgrpid_org=explode(",",$schgrpid_org);
						$schemeid=base64_decode($_GET["schemeid"]);
						//$schemeid=explode(",",$schemeid);
						//$schemeid_org=base64_decode($_POST["cmb_scheme"]);
						//$schemeid_org=explode(",",$schemeid_org);
						$wrkgrpid=base64_decode($_GET["workgrpid"]);
						$work_id=base64_decode($_GET["work_id"]);
					} else if($_GET['xls'] == 1){
						$finyr=base64_decode($_GET['fin_year']);
						//$finyr=explode(",",$finyr);
						$schgrpid=base64_decode($_GET["schgrpid"]);
						//$schgrpid=explode(",",$schgrpid);
						//$schgrpid_org=base64_decode($_POST["cmb_schemegrp"]);
						//$schgrpid_org=explode(",",$schgrpid_org);
						$schemeid=base64_decode($_GET["schemeid"]);
						//$schemeid=explode(",",$schemeid);
						//$schemeid_org=base64_decode($_POST["cmb_scheme"]);
						//$schemeid_org=explode(",",$schemeid_org);
						$wrkgrpid=base64_decode($_GET["workgrpid"]);
						///$wrkgrpid=explode(",",$wrkgrpid);
						$work_id=base64_decode($_GET["work_id"]);
					}
					$dcode= $this->issetCurrentDistrictCode()?$this->getCurrentDistrictCode():'';
					$lbcode=$this->getCurrentLocalBodyCode()?$this->getCurrentLocalbodyCode():'';
					$worktype='';
					if($finyr!='' ){
						//$finyr = array_map('trim',$finyr);
						//$finyr_arr1=implode(", ",$finyr);
						//$finyr_arr=implode("', '",$finyr);
						//$worktype.=" a.fin_year in ('".$finyr_arr."') " ;
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
					if($work_id!='' )
						$worktype.=" and work_id=:work_id " ;
					$group_by_sel='';
						if($schgrpid!='' )
							$group_by_sel.=" scheme_group_id, " ;
						if($schemeid >0 )
							$group_by_sel.=" scheme_id, " ;
					if($dcode == '' && $lbcode == '')
					{
						$sql="SELECT maintab.dcode, dt.district_name_en, count(1) as cnt,	
				sum(case when current_stage_of_work=5 then 1 else 0 end) as not_started,	   
			    sum(case when  current_stage_of_work='15' then 1 else 0 end) as comp, 
				sum(case when  (current_stage_of_work!='15' or current_stage_of_work is null) then 1 else 0 end) as bal , 
				sum(case when latest_as_amount is not null then latest_as_amount else as_value end) as asamt,
				sum(amount_spent_sofar) as asexp from ( SELECT * FROM works.t_works where $worktype) maintab
                   LEFT JOIN ( SELECT scheme_group_id,
                    scheme_group_name_en AS schemegrp_name
                   FROM master.m_scheme_group) sgrp ON maintab.scheme_group_id = sgrp.scheme_group_id
                 LEFT JOIN ( SELECT scheme_group_code,
                  scheme_seq_id,
                  scheme_name_en
                 FROM master.m_scheme) sm ON maintab.scheme_group_id = sm.scheme_group_code AND maintab.scheme_id = sm.scheme_seq_id
               LEFT JOIN ( SELECT wrkgrp_id,
                wrkgrpname_en AS wrkgrpname
               FROM master.m_workgroup) wg ON maintab.work_group_id = wg.wrkgrp_id
             LEFT JOIN ( SELECT work_group_id,
              work_id,
              work_name AS worktypname
             FROM master.m_work_type) wtp ON maintab.work_group_id = wtp.work_group_id AND maintab.work_id = wtp.work_id
           LEFT JOIN ( SELECT as_authority_id,
            as_authority_desig
           FROM master.m_as_authority) asby ON maintab.as_sanc_authority = asby.as_authority_id
         LEFT JOIN ( SELECT ts_authority_id,
          ts_authority_desig
         FROM master.m_ts_authority) tsby ON maintab.as_sanc_authority = tsby.ts_authority_id
       LEFT JOIN ( SELECT agency_group_id,
        agency_group_name_en
       FROM master.m_agency_group) aggp ON maintab.agency_group_id = aggp.agency_group_id
     LEFT JOIN ( SELECT agency_group_id,
      agency_id,
      agency_name_en
     FROM master.m_agency) agy ON maintab.agency_group_id = agy.agency_group_id AND maintab.agency_id = agy.agency_id
             LEFT JOIN ( SELECT dcode,
                    district_name_en
                   FROM master.m_district) dt ON dt.dcode = maintab.dcode
             LEFT JOIN ( SELECT dcode,
                    lbcode,
                    lbody_name_en
                   FROM master.m_localbodies) tp ON tp.dcode = maintab.dcode  AND tp.lbcode = maintab.lbcode
             LEFT JOIN ( SELECT dcode,
                    lbcode,
                    ward_id,
                    ward_name_en
                   FROM master.m_warddetails) wd ON wd.dcode = maintab.dcode  AND wd.lbcode = maintab.lbcode AND wd.ward_id = maintab.ward::int
             LEFT JOIN ( SELECT stage_id,
                    stage_name
                   FROM master.m_stage) stg ON maintab.current_stage_of_work = stg.stage_id group by maintab.dcode, dt.district_name_en " ;
							$sel_qry_res=$this->prepare($sql,array(":fin_year"=>$finyr, ":schgrpid" =>$schgrpid, ":schemeid" => $schemeid, ":wrkgrpid" =>$wrkgrpid, ":work_id"=>$work_id),2);
					}
					if($dcode !='' && $lbcode !='')
					{
						 $sql="SELECT maintab.dcode, dt.district_name_en, count(1) as cnt,	
				sum(case when current_stage_of_work=5 then 1 else 0 end) as not_started,	   
			    sum(case when  current_stage_of_work='15' then 1 else 0 end) as comp, 
				sum(case when  (current_stage_of_work!='15' or current_stage_of_work is null) then 1 else 0 end) as bal , 
				sum(case when latest_as_amount is not null then latest_as_amount else as_value end) as asamt,
				sum(amount_spent_sofar) as asexp from ( SELECT * FROM works.t_works where $worktype) maintab
                   LEFT JOIN ( SELECT scheme_group_id,
                    scheme_group_name_en AS schemegrp_name
                   FROM master.m_scheme_group) sgrp ON maintab.scheme_group_id = sgrp.scheme_group_id
                 LEFT JOIN ( SELECT scheme_group_code,
                  scheme_seq_id,
                  scheme_name_en
                 FROM master.m_scheme) sm ON maintab.scheme_group_id = sm.scheme_group_code AND maintab.scheme_id = sm.scheme_seq_id
               LEFT JOIN ( SELECT wrkgrp_id,
                wrkgrpname_en AS wrkgrpname
               FROM master.m_workgroup) wg ON maintab.work_group_id = wg.wrkgrp_id
             LEFT JOIN ( SELECT work_group_id,
              work_id,
              work_name AS worktypname
             FROM master.m_work_type) wtp ON maintab.work_group_id = wtp.work_group_id AND maintab.work_id = wtp.work_id
           LEFT JOIN ( SELECT as_authority_id,
            as_authority_desig
           FROM master.m_as_authority) asby ON maintab.as_sanc_authority = asby.as_authority_id
         LEFT JOIN ( SELECT ts_authority_id,
          ts_authority_desig
         FROM master.m_ts_authority) tsby ON maintab.as_sanc_authority = tsby.ts_authority_id
       LEFT JOIN ( SELECT agency_group_id,
        agency_group_name_en
       FROM master.m_agency_group) aggp ON maintab.agency_group_id = aggp.agency_group_id
     LEFT JOIN ( SELECT agency_group_id,
      agency_id,
      agency_name_en
     FROM master.m_agency) agy ON maintab.agency_group_id = agy.agency_group_id AND maintab.agency_id = agy.agency_id
             LEFT JOIN ( SELECT dcode,
                    district_name_en
                   FROM master.m_district) dt ON dt.dcode = maintab.dcode
             LEFT JOIN ( SELECT dcode,
                    lbcode,
                    lbody_name_en
                   FROM master.m_localbodies) tp ON tp.dcode = maintab.dcode  AND tp.lbcode = maintab.lbcode
             LEFT JOIN ( SELECT dcode,
                    lbcode,
                    ward_id,
                    ward_name_en
                   FROM master.m_warddetails) wd ON wd.dcode = maintab.dcode  AND wd.lbcode = maintab.lbcode AND wd.ward_id = maintab.ward::int
             LEFT JOIN ( SELECT stage_id,
                    stage_name
                   FROM master.m_stage) stg ON maintab.current_stage_of_work = stg.stage_id group by maintab.dcode, dt.district_name_en" ;
							$sel_qry_res=$this->prepare($sql,array(":fin_year"=>$finyr, ":schgrpid" =>$schgrpid, ":schemeid" => $schemeid, ":wrkgrpid" =>$wrkgrpid, ":dcode" => $dcode, ":lbcode" => $lbcode, ":work_id"=>$work_id),2);
					}
					
					?>
                	<div class="card-body">
                    <div class="col-lg-12 col-ml-12">
                    	<br />
						  <?php
                          if(!isset($_GET['xls'])){
                            ?>
                          	<table class="table-bordered tndtp_form_table" id="sorternew">
                        		<thead>
                                    <tr>
                                        <th style="text-align: center;" colspan="7">
                                      		<a href="?xls=<?php echo base64_encode(1); ?>&fin_year=<?php echo base64_encode($finyr); ?>&schemegrp=<?php echo base64_encode($schgrpid); ?>&scheme=<?php echo base64_encode($schemeid); ?>&wrkgrp=<?php echo base64_encode($wrkgrpid); ?>&work_id="<?php echo htmlentities($work_id); ?>>Download XLS</a>
                                        </th>
                                    </tr>
                                    <tr>
                                      <td align="center">Sl.No.</td>
                                      <td align="center">District Name</td>
                                      <td align="center">Works Takenup</td>
                                      <td align="center">Works Completed</td>
                                      <td align="center">Pending Works</td>
                                      <td align="center">Not Started</td>
                                      <td align="center">% of Completion</td>
                                    </tr>
                                    <tr align="center" valign="middle"><?php $col_no=1;?>
                                      <td align="center"><?php echo $col_no++;?></td>
                                      <td align="center"><?php echo $col_no++;?></td>
                                      <td align="center"><?php echo $col_no++;?></td>
                                      <td align="center"><?php echo $col_no++;?></td>
                                      <td align="center"><?php echo $col_no++;?></td>
                                      <td align="center"><?php echo $col_no++;?></td>
                                      <td align="center"><?php echo $col_no++;?></td>
                                    </tr>
                        		</thead>
                                <tbody>
                                	<?php if(count($sel_qry_res) > 0){ 
										foreach($sel_qry_res as $sel_qry_key => $sel_qry_row){ 
										$filename="work_type_wise_phy_progress_dl.php?fin_year=".base64_encode($finyr)."&schgrpid=".base64_encode($schgrpid)."&schemeid=".base64_encode($schemeid)."&workgrpid=".base64_encode($wrkgrpid)."&work_id=".base64_encode($worktype)."&dcode=".base64_encode($sel_qry_row['dcode']);
										if($sel_qry_row['cnt']!=''){
											$string="<a href='$filename'>".strtoupper($sel_qry_row['district_name_en']?$sel_qry_row['district_name_en']:$sel_qry_row['district_name_en'])."</a>";
										}else{
											$string=strtoupper($sel_qry_row['district_name_en']?$sel_qry_row['district_name_en']:$sel_qry_row['district_name_en']);
										}
										?>
                                            <tr>
                                                <td><?php echo htmlentities($sel_qry_key+1);?></td>
                                                <td><?php echo ($string);?></td>
                                                <td><?php echo htmlentities($sel_qry_row['cnt']);?></td>
                                                <td><?php echo htmlentities($sel_qry_row['cnt']);?></td>
                                                <td><?php echo htmlentities($sel_qry_row['comp']);?></td>
                                                <td><?php echo htmlentities($sel_qry_row['bal']);?></td>
                                                <td><?php echo htmlentities($sel_qry_row['not_started']);?></td>
                                                <td align="right">
													<?php  $per=($sel_qry_row['comp']/$sel_qry_row['cnt'])*100 ; 
													echo number_format(round($per,2),2); ?>
                                                </td>
                                            </tr>
                                            <?php } 
									}else{?>
                                    	<tr>
                                        	<td colspan="7">No Records Found</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                          </table>
                          <?php
                          }
                          ?>
                    	</form>
                    </div>
                </div>
            </div>
        </div>
    	<?php
		$ob_output_main_forms = ob_get_contents();
        ob_clean();
		$this->Template($this->getCurrentUserTemplate()!=""?$this->getCurrentUserTemplate():"Template1", "Work Type Wise Physical Progress Report", $ob_output_main_forms,array(),array('page_id'=>12));
        exit();
	}	
}
$work_type_report = new work_type_report();
$work_type_report->main_form($_GET);
