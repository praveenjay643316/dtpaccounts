<?php
if(isset($_GET['xls'])){
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
	header("Cache-Control: no-store, no-cache, must-revalidate");   // HTTP/1.1
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");                                     // HTTP/1.0
	header("Content-type: application/force-download");
	header("Content-type: application/octet-stream");
	header("Content-type: application/msexcel");
	header('Content-Disposition: attachment; filename="physical_progress_detail_report.xls";');  
}
require_once __DIR__ . '/../config/config.php';

class work_type_report extends ConfigClass
{
	public $page_token = "work_type_details_report_token";
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

/* Container for images and captions */
.image-container {
    position: relative;
    text-align: center;
    width: 250px; /* Adjust width as needed */
    height: 250px; /* Adjust height as needed */
    overflow: hidden;
    margin: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

/* Hover effect */
.image-container:hover img {
    transform: scale(1.2); /* Zoom in the image */
}

.image-container:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    cursor: pointer;
}

/* Caption below the image */
.image-caption {
    margin-top: 10px;
    font-size: 14px;
    color: #333;
    font-weight: 500;
}
</style>

		</style>
        <script type="text/javascript">	
		$(document).ready(function() {  
			$(document).on('click', '.appphoto', function () {
    const slno = $(this).data('slno'); 
    const workid = $(`#workid_${slno}`).val();
    const work_type_id = $(`#work_type_id_${slno}`).val();
    const work_group_id = $(`#work_group_id_${slno}`).val();
    const scheme_id = $(`#scheme_id_${slno}`).val();
    const scheme_group_id = $(`#scheme_group_id_${slno}`).val();
	const fin_year = $(`#fin_year_${slno}`).val();
	const dcode = $(`#dcode_${slno}`).val();
	const lbcode = $(`#lbcode_${slno}`).val();

    // Show overlay and modal
    $('#overlay').show();
    $('#popupModal').show();

    // Make AJAX request
	$.ajax({
    url: 'work_type_wise_phy_progress_details.php',
    type: 'POST',
    data: { 
        work_id: btoa(workid), 
        cmd: btoa(1), 
        work_type_id: btoa(work_type_id), 
        work_group_id: btoa(work_group_id), 
        scheme_id: btoa(scheme_id), 
        scheme_group_id: btoa(scheme_group_id),
        fin_year: btoa(fin_year),
        dcode: btoa(dcode),
        lbcode: btoa(lbcode)  
    },
    success: function (response) {
        const images = JSON.parse(response);
        let htmlContent = '<div style="display: flex; flex-wrap: wrap; gap: 10px;">';
        images.forEach(({ image, stage_name }) => {
            htmlContent += `
                <div class="image-container">
                   <img src="${image}" style="width: 200px; height: 200px; border: 1px solid #ddd;">
                        <div style="margin-top: 5px; font-size: 14px; color: #333;">${stage_name}</div>
                </div>
            `;
        });
        htmlContent += '</div>';
        $('#popupContent').html(htmlContent);
    },
    error: function () {
        $('#popupContent').html('<p>Error loading content.</p>');
    }
});
});

// Close the modal
$(document).on('click', '#closeModal, #overlay', function () {
    $('#popupModal').hide();
    $('#overlay').hide();
});
});	
	
		</script> 
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
		<div class="container mt-3">
            <div class="card">
            
                <?php 
				 		if (isset($data_array["STATUS"])) {
                            echo $this->ShowMessage($data_array["STATUS"], $data_array["MESSAGE"]);
                        }else{
					if(!isset($data_array['xls'])){
						$finyr=base64_decode($data_array['fin_year']);
						$schgrpid=base64_decode($data_array["schgrpid"]);
						$schemeid=base64_decode($data_array["schemeid"]);
						$wrkgrpid=base64_decode($data_array["workgrpid"]);
						$type=base64_decode($data_array["type"]);
						$work_type_id=base64_decode($data_array['work_type']);
					} else if(isset($data_array['xls']) && base64_decode($data_array['xls']) == 1){
						$finyr=base64_decode($data_array['fin_year']);
						$schgrpid=base64_decode($data_array["schgrpid"]);
						$schemeid=base64_decode($data_array["schemeid"]);
						$wrkgrpid=base64_decode($data_array["workgrpid"]);
						$type=base64_decode($data_array["type"]); 
						$work_type_id=base64_decode($data_array['work_type']);
					}
					$wrktypschwise='';
					if ($type == 'cnt'){
						$wrktypschwise.=" and (current_stage_of_work is null or current_stage_of_work is not null) ";
					}
					if ($type == 'comp'){
						$wrktypschwise.=" and  current_stage_of_work=15 ";
					}
					if ($type == 'bal'){
						$wrktypschwise.=" and (current_stage_of_work<>15 OR current_stage_of_work IS NULL)";
					}
					if ($type == 'not_started'){
						$wrktypschwise.=" and current_stage_of_work=5";	
					}
						$sql="select scheme_id, a.work_group_id, a.workid as work_id, a.work_type_id, as_no, as_date, as_sanc_authority, as_value, as_revised, ts_no, ts_date, ts_sanc_authority, ts_value, ts_revised, a.agency_id, a.agency_group_id, a.dcode, a.lbcode, current_stage_of_work, amount_spent_sofar, latest_as_amount, latest_ts_amount, ward, location, street,scheme_group_id,work_type_name,work_name,agency_group_name_en,district_name_en,lbody_name_en,street_name_en,stage_name,as_authority_desig,ts_authority_desig,ward_name_en,agency_name_en,photo_exist from  
(SELECT scheme_id, work_group_id, work_id as workid, work_name, work_type_id, as_no, as_date, as_sanc_authority, as_value, as_revised, ts_no, ts_date, ts_sanc_authority, ts_value, ts_revised, agency_id, agency_group_id, dcode, lbcode, current_stage_of_work, amount_spent_sofar, latest_as_amount, latest_ts_amount, ward, location, street,scheme_group_id from works.t_works where fin_year=:fin_year and scheme_group_id=:scheme_group_id and scheme_id=:scheme_id and work_group_id=:wrkgrpid and work_type_id=:work_type_id $wrktypschwise)a
							LEFT JOIN
							(select work_group_id, work_name_en as work_type_name, work_id as work_type_id from (select work_group_id, work_id from master.m_work_type where del_flag is null)a left join (select work_type_id, work_name_en  from master.m_work_type_name where del_flag is null ) b on a.work_id = b.work_type_id )b 
							ON a.work_group_id=b.work_group_id and a.work_type_id=b.work_type_id
							left join
(select agency_group_id, agency_group_name_en from master.m_agency_group where del_flag is null ) 	c on a.agency_group_id=c.agency_group_id
left join
(select agency_id, agency_group_id, agency_name_en, dcode, lbcode from master.m_agency where del_flag is null)d on d.agency_group_id=a.agency_group_id and d.agency_id=a.agency_id and a.dcode=d.dcode and a.lbcode=d.lbcode
left join
(select dcode, district_name_en from master.m_district where dist_order_no is not null)e on e.dcode=a.dcode
left join 
(select dcode, lbcode, lbody_name_en from master.m_localbodies where del_flag is null and isactive=1)f on a.dcode=f.dcode and a.lbcode=f.lbcode
left join
(select dcode, lbcode, ward_id, ward_name_en from master.m_warddetails where del_flag is null and isactive=1)g on a.ward=g.ward_id::text
left join 
(select dcode, lbcode, wardid, streetid, street_name_en from master.m_streetdetails where isactive=1 and del_flag is null)h on a.ward=h.wardid::text and a.street=h.streetid::text
left join
(select as_authority_id, as_authority_desig from master.m_as_authority where del_flag is null and isactive=1)i on a.as_sanc_authority=i.as_authority_id
left join
(select ts_authority_id, ts_authority_desig from master.m_ts_authority where del_flag is null and isactive=1)j on a.ts_sanc_authority=j.ts_authority_id
left join
(select stage_id, stage_name from master.m_stage where del_flag is null and isactive=1)k on a.current_stage_of_work=stage_id
left join
(select work_id, count(1)as photo_exist from works.t_scheme_work_physical_progress where file_url is NOT NULL and photo_captured_latitude is NOT NULL and fin_year=:fin_year and scheme_group_id=:scheme_group_id and scheme_id=:scheme_id and work_group_id=:wrkgrpid and work_type_id=:work_type_id group by work_id) l ON a.workid=l.work_id " ;
							$sel_qry_res=$this->prepare($sql,array(":fin_year"=>$finyr, ":scheme_group_id" =>$schgrpid, ":scheme_id" => $schemeid, ":wrkgrpid" =>$wrkgrpid, ":work_type_id"=>$work_type_id),2);
					?>
                	<div class="card-body">
                    <div class="col-lg-12 col-ml-12 table-responsive">
                    <?php if(!isset($data_array['xls'])){ ?>
                                    <div>
                                        <div style="text-align: center;" class="my-2 text-b">
                                      		<a href="?xls=<?php echo base64_encode(1); ?>&fin_year=<?php echo base64_encode($finyr); ?>&schgrpid=<?php echo base64_encode($schgrpid); ?>&schemeid=<?php echo base64_encode($schemeid); ?>&workgrpid=<?php echo base64_encode($wrkgrpid); ?>&work_type=<?php echo base64_encode($work_type_id); ?>&type=<?php echo base64_encode($type); ?>">Download XLS<img width="25px" height="25px" src="../../images/excel.png" /></a>
                                        </div>
                                    </div>
                                    <?php } ?>
                          	<table class=" table-bordered tndtp_form_table" id="sorternew" border="1">
                        		<thead class="newhead">
                                	<tr>
                                        <td colspan="18" class="text-center">Work Type wise Physical Progress Report</td>
                                    </tr>
                                    <tr>
                                        <td colspan="18" class="text-center">Financial Year :: <?php echo htmlentities($finyr); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="18" class="text-center">Scheme Group :: <?php echo htmlentities($this->get_scheme_group_name($schgrpid)); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="18" class="text-center">Scheme :: <?php echo htmlentities($this->get_scheme_name($schemeid)); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="18" class="text-center">Work Group :: <?php echo htmlentities($this->get_work_group_name($wrkgrpid)); ?></td>
                                    </tr>
                                    <tr>
                                        <td align="center">Sl.No</td>
                                        <td align="center">District</td>
                                        <td align="center">Town Panchayat</td>
                                        <td align="center">Ward</td>
                                        <td align="center">Street</td>
                                        <td align="center">Work ID</td>
                                        <td align="center">Work Type</td>
                                        <td align="center">Work Name</td>
                                        <td align="center">AS Number / As Date</td>
                                        <td align="center">AS Value</td>
                                        <td align="center">TS Number / TS Date </td>
                                        <td align="center">TS Value</td>
                                        <td align="center">Agency Group</td>
                                        <td align="center">Agency</td>
                                        <td align="center">Amount Spent Sofar</td>
                                        <td align="center">Current Stage</td>
                                        <td>View Photo</td>
                                     </tr>
                                </thead>
                                <tbody>
                                	<?php if(count($sel_qry_res) > 0){ 
										$slno = 1;
										foreach($sel_qry_res as $sel_qry_key => $sel_qry_row){ 
										?>
                                            <tr>
                                                <td><?php echo htmlentities($sel_qry_key+1);?></td>
                                                <td><?php echo htmlentities($sel_qry_row['district_name_en']!=''?$sel_qry_row['district_name_en']:'');?></td>
                                                <td><?php echo htmlentities($sel_qry_row['lbody_name_en']!=''?$sel_qry_row['lbody_name_en']:'');?></td>
                                                <td><?php echo htmlentities($sel_qry_row['ward_name_en']!=''?$sel_qry_row['ward_name_en']:'');?></td>
                                                <td><?php echo htmlentities($sel_qry_row['street_name_en']!=''?$sel_qry_row['street_name_en']:'');?></td>
                                                <td><?php echo htmlentities($sel_qry_row['work_id']!=''?$sel_qry_row['work_id']:'');?></td>
                                                <td><?php echo htmlentities($sel_qry_row['work_type_name']!=''?$sel_qry_row['work_type_name']:'');?></td>
												<td><?php echo htmlentities($sel_qry_row['work_name']!=''?$sel_qry_row['work_name']:'');?></td>
                                                <td><?php echo htmlentities($sel_qry_row['as_no'] .' / '.$sel_qry_row['as_date']);?></td>
												<td><?php echo htmlentities($sel_qry_row['as_value']!=''?$sel_qry_row['as_value']:'');?></td>
                                                <td><?php echo htmlentities($sel_qry_row['ts_no'] .' / '.$sel_qry_row['ts_date']);?></td>
												<td><?php echo htmlentities($sel_qry_row['work_type_name']!=''?$sel_qry_row['work_type_name']:'');?></td>
												<td><?php echo htmlentities($sel_qry_row['ts_value']!=''?$sel_qry_row['ts_value']:'');?></td>
												<td><?php echo htmlentities($sel_qry_row['agency_group_name_en']!=''?$sel_qry_row['agency_group_name_en']:'');?></td>
                                                <td><?php echo htmlentities($sel_qry_row['agency_name_en']!=''?$sel_qry_row['agency_name_en']:'');?></td>
                                                <td><?php echo htmlentities($sel_qry_row['stage_name']!=''?$sel_qry_row['stage_name']:'');?></td>
                                         <td>
                                                	<input type="hidden" name="workid_<?php echo $slno; ?>" id="workid_<?php echo $slno; ?>" value="<?php echo $sel_qry_row['work_id']; ?>" />
													<input type="hidden" name="work_type_id_<?php echo $slno; ?>" id="work_type_id_<?php echo $slno; ?>" value="<?php echo $work_type_id; ?>" />
													<input type="hidden" name="work_group_id_<?php echo $slno; ?>" id="work_group_id_<?php echo $slno; ?>" value="<?php echo $wrkgrpid; ?>" />
													<input type="hidden" name="scheme_id_<?php echo $slno; ?>" id="scheme_id_<?php echo $slno; ?>" value="<?php echo $schemeid; ?>" />
													<input type="hidden" name="scheme_group_id_<?php echo $slno; ?>" id="scheme_group_id_<?php echo $slno; ?>" value="<?php echo $schgrpid; ?>" />
													<input type="hidden" name="fin_year_<?php echo $slno; ?>" id="fin_year_<?php echo $slno; ?>" value="<?php echo $finyr; ?>" />
													<input type="hidden" name="dcode_<?php echo $slno; ?>" id="dcode_<?php echo $slno; ?>" value="<?php echo $sel_qry_row['dcode']; ?>" />
													<input type="hidden" name="lbcode_<?php echo $slno; ?>" id="lbcode_<?php echo $slno; ?>" value="<?php echo $sel_qry_row['lbcode']; ?>" />
													<?php if($sel_qry_row['photo_exist']>0) { ?>
                                                    <img class="appphoto" data-slno='<?php echo $slno; ?>' src="../../images/eye-icon.gif" width='30' height='20' border='0'/>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php 
												$slno++;
											} 
									}else{?>
                                    	<tr>
                                       		<td colspan="16" class="text-danger">Records Not Found</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                          </table>
                          <br /><br />
        				<div id="dialog-rdinst" title="Photo"></div>
                    	</form>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
		<input type="hidden" name="workid_<?php echo $slno; ?>" id="workid_<?php echo $slno; ?>" value="<?php echo $sel_qry_row['work_id']; ?>" />
<?php if($sel_qry_row['photo_exist'] > 0) { ?>
    <img class="appphoto" data-slno="<?php echo $slno; ?>" 
         src="../../images/eye-icon.gif" 
         width="30" height="20" border="0" alt="View Photo">
<?php } ?>

<!-- Modal Popup -->

<div id="popupModal" style="position: fixed; top: 3%; left: 18%; width: 66%; height: 90%; background: rgb(255, 255, 255); border: 1px solid rgb(204, 204, 204); box-shadow: rgba(0, 0, 0, 0.5) 0px 0px 10px; z-index: 1000; display: none;">
    <div style="padding: 10px; border-bottom: 1px solid #ccc; display: flex; justify-content: space-between; align-items: center;">
        <div style="flex-grow: 1; text-align: center; font-size: 16px; font-weight: bold;">Stage List</div>
        <button id="closeModal" style="background: none; border: none; font-size: 18px;">&times;</button>
    </div>
    <div id="popupContent" style="padding: 20px; overflow-y: auto; max-height: calc(100% - 50px);">
        <!-- Your content will go here -->
    </div>
</div>
<div id="overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999;"></div>



    	<?php
		$ob_output_main_forms = ob_get_contents();
        ob_clean();
		if(!isset($data_array['pdf']) && !isset($data_array['xls']))
		{
			$this->Template($this->getCurrentUserTemplate()!=""?$this->getCurrentUserTemplate():"Template1", "Work Type Wise Physical Progress Detail Report", $ob_output_main_forms,array(),array('page_id'=>12));
		}
		else if( isset($data_array['xls']))
		{
			echo ($ob_output_main_forms);
		}
        exit();
	}	
}
$work_type_report = new work_type_report();
if(isset($_GET['fin_year']) && $_GET['fin_year']!=''){
	$fin_year=base64_decode($_GET['fin_year']);
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
		$work_type_report->main_form($Result_Data);		
	}
}
if(isset($_GET['schgrpid']) && $_GET['schgrpid']!=''){
	$schemegrp=base64_decode($_GET['schgrpid']);
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
		$work_type_report->main_form($Result_Data);	
	}
}
if(isset($_GET['schemeid']) && $_GET['schemeid']!=''){
	$scheme=base64_decode($_GET['schemeid']);
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
		$work_type_report->main_form($Result_Data);	
	}
}	
if(isset($_GET['workgrpid']) && $_GET['workgrpid']!=''){
	$wrkgrp=base64_decode($_GET['workgrpid']);
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
		$work_type_report->main_form($Result_Data);	
	}
}
if(isset($_GET['work_type']) && $_GET['work_type']!=''){
	$work_type=base64_decode($_GET['work_type']);
	$work_type_Validation=$work_type_report->Field_Validation(
	array
	(
	'Field_Type'=>'number',
	'Field_Value'=>$work_type,
	'Field_Name'=>'work_type',
	'Field_Max_length'=>'5',
	'Field_Label_Name'=>'Work Type'
	));
	if($work_type_Validation['Status'] == "Error") {
		$Result_Data['STATUS']='ERROR';
		$Result_Data['MESSAGE']='Invalid Work Type';
		$work_type_report->main_form($Result_Data);	
	}
}
if(isset($_GET['type']) && $_GET['type']!=''){
	$type=base64_decode($_GET['type']);
	$type_Validation=$work_type_report->Field_Validation(
	array
	(
	'Field_Type'=>'text_underscore',
	'Field_Value'=>$type,
	'Field_Name'=>'type',
	'Field_Max_length'=>'15',
	'Field_Label_Name'=>'Type'
	));
	if($type_Validation['Status'] == "Error") {
		$Result_Data['STATUS']='ERROR';
		$Result_Data['MESSAGE']='Invalid Type';
		$work_type_report->main_form($Result_Data);	
	}
}
if (isset($_POST["cmd"])) {
	$workid=base64_decode($_POST["work_id"]);
	$work_type_id=base64_decode($_POST["work_type_id"]);
	$work_group_id=base64_decode($_POST["work_group_id"]);
	$scheme_id=base64_decode($_POST["scheme_id"]);
	$scheme_group_id=base64_decode($_POST["scheme_group_id"]);
	$dcode=base64_decode($_POST["dcode"]);
	$lbcode=base64_decode($_POST["lbcode"]);
	$fin_year=base64_decode($_POST["fin_year"]);
	$cmd=base64_decode($_POST["cmd"]);
	if($cmd==1)
	{
		$sql = "SELECT work_id, file_url, stage_name 
        FROM 
        (SELECT work_id, file_url, stage_id 
         FROM works.t_scheme_work_physical_progress 
         WHERE file_url IS NOT NULL 
           AND photo_captured_latitude IS NOT NULL 
           AND scheme_group_id = :scheme_group_id 
           AND scheme_id = :scheme_id 
           AND work_group_id = :wrkgrpid 
           AND work_type_id = :work_type_id 
           AND work_id = :work_id) a 
        LEFT JOIN 
        (SELECT stage_id, stage_name 
         FROM master.m_stage 
         WHERE del_flag IS NULL) b 
        ON a.stage_id = b.stage_id";

$sel_qry_res = $work_type_report->prepare($sql, array(
    ":work_id" => $workid,
    ":scheme_group_id" => $scheme_group_id,
    ":scheme_id" => $scheme_id,
    ":wrkgrpid" => $work_group_id,
    ":work_type_id" => $work_type_id
), 2);

$imageData = [];
$Base_path = $work_type_report->getStoragePath() . "Document/work/work_monitoring_image";
$Temp_Base_path = $Base_path . '/' . $dcode . '/' . $lbcode . '/' . $fin_year . '/';

foreach ($sel_qry_res as $row) {
    $File_Path = $Temp_Base_path . $row['file_url'];
    if (file_exists($File_Path)) {
        $type = pathinfo($File_Path, PATHINFO_EXTENSION);
        $data = file_get_contents($File_Path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $imageData[] = [
            'image' => $base64,
            'stage_name' => $row['stage_name'] ?? '' 
        ];
    }
}

echo json_encode($imageData);
die;

	}
}
$work_type_report->main_form($_GET);