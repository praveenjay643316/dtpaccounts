<?php
ini_set('memory_limit', '2G'); 
ini_set('max_execution_time', '300');
trait WorkInspectionFunction
{

	public function ImageCompress($data){
	 
		$im = imagecreatefromstring($data);
		ob_start();
		imagejpeg ( $im ,NULL,30);
		$imagedata = ob_get_contents();
		ob_end_clean();
		if(base64_encode($imagedata)=="")
		{
		$out=htmlentities( "data:image/jpg;base64,".base64_encode($data));	
		}
		else{
		$out= htmlentities( "data:image/jpg;base64,".base64_encode($imagedata));
		}
		
		return $out;
	}

	public function pdf_download($decrypted_data_json)
	{

		$response_data = array();
		$req_params = array();
		
		$work_id = $decrypted_data_json->work_id;
		$inspection_id = $decrypted_data_json->inspection_id;

		$inspection_details = "select a.*,b.work_name,b.as_value,b.as_no,b.as_date,c.status,d.district_name_en,e.lbody_name_en,g.scheme_name_en as scheme_name,a.rural_urban from
		(select work_id,dcode,lbcode,inspection_id,to_char(inspection_date,'dd-mm-yyyy') as
		inspection_date,status_id,description,rural_urban from works.t_work_inspection_details where del_flag is null and work_id=:work_id and inspection_id=:inspection_id) as a
		left join
		(select work_id,work_name,scheme_id,scheme_group_id,as_value,as_no,to_char(as_date,'dd-mm-yyyy') as as_date from works.t_works where work_id=16) as b
		on a.work_id=b.work_id
		left join
		(select status_id,status from master.m_inspection_status) as c
		on a.status_id=c.status_id
		left join
		(select dcode,district_name_en from master.m_district) as d
		on a.dcode=d.dcode
		left join
		(select dcode,lbcode,lbody_name_en from master.m_localbodies where del_flag is null) as e
		on a.dcode=e.dcode and a.lbcode=e.lbcode and d.dcode=e.dcode
		left join
		(select scheme_seq_id,scheme_group_code,scheme_name_en from master.m_scheme) as g
		on b.scheme_id=g.scheme_seq_id and b.scheme_group_id=g.scheme_group_code
		";
		$res_inspection_details = $this->prepare($inspection_details, array(":work_id"=>$work_id,":inspection_id"=>$inspection_id),2);
		
		foreach ($res_inspection_details as $res_inspection_details_key => $res_inspection_details_val) {
				
				$inspection_id=$res_inspection_details_val['inspection_id'];
				$inspection_details_photos = "select file_name,image_description from works.t_work_inspection_details_images where del_flag is null and inspection_id=:inspection_id";
				$res_inspection_details_photos = $this->prepare($inspection_details_photos, array(":inspection_id"=>$inspection_id),2);
				
				$res_inspection_details[$res_inspection_details_key]['image_content'] = $res_inspection_details_photos;
		}
		
		$pdf_format_string=$this->pdf_creation($res_inspection_details);
		
		if (count($res_inspection_details) > 0 && $pdf_format_string != '') {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$response_data['JSON_DATA'] = array("pdf_string"=>$pdf_format_string);
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'NO_RECORD';
			$response_data['MESSAGE'] = 'NO_RECORD';
		}
		//echo (json_encode($response_data));		
		return $response_data; 
		exit;
	}

	public function pdf_creation($data_content)
	{
		ob_start();
		
		$list_arr=array('a','b','c','d','e','f','g');
		$i=0;

	?>
        <table  border="0" cellpadding="0" cellspacing="0" bordercolor="#5FC1F5" align="center" valign="middle" style="page-break-inside: avoid; width:100%;">
			<tr>
			<td colspan="3" style="padding:2px;line-height:1.6;text-align:right;font-weight:bold;" align="right">Date: <?php echo $data_content[0]['inspection_date']; ?></td>
			</tr>
			<tr>
				<td height="35" colspan="3" style="padding:2px;"><h3><strong>Inspection Report</strong></h3></td>
			</tr>
			
			<tr>
				<td width="23%" height="35"><strong>(<?php echo $list_arr[$i++]; ?>). Name of the Town panchayat</strong></td>
				<td width="1%"><strong>:</strong></td>
				<td width="76%"><?php echo $data_content[0]['lbody_name_en']; ?></td>
			</tr>
			
			
			
			<tr>
				<td height="35"><strong>(<?php echo $list_arr[$i++]; ?>). Name of the Scheme</strong></td>
				<td><strong>:</strong></td>
				<td><?php echo $data_content[0]['scheme_name']; ?></td>
			</tr>
			<tr>
				<td height="35"><strong>(<?php echo $list_arr[$i++]; ?>). Estimation </strong></td>
				<td><strong>:</strong></td>
				<td><?php echo $data_content[0]['as_value']; ?></td>
			</tr>
			<tr>
				<td height="35"><strong>(<?php echo $list_arr[$i++]; ?>). AS No. and Date</strong></td>
				<td><strong>:</strong></td>
				<td><?php echo $data_content[0]['as_no']; ?> and <?php echo $data_content[0]['as_date']; ?></td>
			</tr>
			<tr>
				<td height="35"><strong>(<?php echo $list_arr[$i++]; ?>). Work ID</strong></td>
				<td><strong>:</strong></td>
				<td><?php echo $data_content[0]['work_id']; ?></td>
			</tr>
			<tr>
				<td height="35"><strong>(<?php echo $list_arr[$i++]; ?>). Name of the Work</strong></td>
				<td><strong>:</strong></td>
				<td><?php echo $data_content[0]['work_name']; ?></td>
			</tr>
			<tr>
			<td colspan="3" height="35"><strong>Uploading Photos:-</strong></td>
			</tr>
			<tr>
                
				<td colspan="4" style="padding-top:10px;">
				<table  border="0" cellpadding="0" cellspacing="0" bordercolor="#5FC1F5" align="center" valign="middle" style="page-break-inside: avoid; width:100%;">
				
				<?php
               
				$slno=1;
				foreach($data_content[0]['image_content'] as $key => $val){
					
                    $Base_path = $this->getStoragePath() . "Document/work/work_inspection_photos/";
				$Temp_Base_path = $Base_path  . $data_content[0]['dcode'] . '/' . $data_content[0]['lbcode'];
				$path_to_save_file=$Temp_Base_path."/".$val['file_name'];
               
					$type = pathinfo($path_to_save_file, PATHINFO_EXTENSION);
					$data = file_get_contents($path_to_save_file);
					$base64=$this->ImageCompress($data);
					//$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
					
				if($slno % 2 != 0 && $key == 0){
				?>
				<tr>	
				<?php 
				} else if($slno % 2 != 0 && $key != 0){ 
				?>
				</tr>
				<tr>
				<?php } ?>
					<td style="width:50%;padding-left:30px;padding-right:30px;" align="center">
						<img src="<?php echo $base64; ?>" alt="" style="width:45%;height:150px;padding-top:10px;padding-bottom:10px;">
						<span><?php echo $val['image_description']; ?></span>
					</td>
				<?php
				if($slno == count($data_content[0]['image_content'])){
				?>		
				</tr>    
				<?php
				}
				$slno++;
				}
				?>  
				
				</table>  
				</td>
			</tr>
			<tr>
				<td height="35" style="padding:2px;"><strong>Status :-</strong></td>
				<td style="padding:2px;" colspan="3"><?php echo $data_content[0]['status']; ?></td>
			</tr>
			<tr>
				<td height="35" style="padding:2px;"><strong>Instructions  :</strong></td>
				<td style="padding:2px;" colspan="3"><?php echo $data_content[0]['description']; ?></td>
			</tr>
		</table>
	<?php

        $output = ob_get_contents();
        //print_r($output);die;
        ob_end_clean();
        $this->mpdf->SetDisplayMode('fullpage');
        $this->mpdf->WriteHTML($output);
        ob_clean();
        return base64_encode($this->mpdf->Output('','S'));
	}

	public function pdf_download_all($decrypted_data_json)
	{

		$response_data = array();
		$req_params = array();
		
		$work_id = $decrypted_data_json->work_id;
		$inspection_id = $decrypted_data_json->inspection_id;

		$inspection_details = "select a.*,b.work_name,b.as_value,b.as_no,b.as_date,c.status,d.dname,e.bname,f.pvname,g.scheme_name,townpanchayat_name,municipality_name,corporation_name from
		(select work_id,dcode,bcode,pvcode,inspection_id,to_char(inspection_date,'dd-mm-yyyy') as
		inspection_date,status_id,description,rural_urban,town_type,tpcode,muncode,corcode from osms.t_work_inspection_details where del_flag is null and work_id=:work_id and inspection_id=:inspection_id) as a
		left join
		(select work_id,work_name,scheme_id,scheme_group_id,as_value,as_no,to_char(as_date,'dd-mm-yyyy') as as_date from t_works where work_id=:work_id) as b
		on a.work_id=b.work_id
		left join
		(select status_id,status from m_inspection_status) as c
		on a.status_id=c.status_id
		left join
		(select dcode,dname from m_district where district_type='R') as d
		on a.dcode=d.dcode
		left join
		(select dcode,bcode,bname from m_block) as e
		on a.dcode=e.dcode and a.bcode=e.bcode
		left join
		(select dcode,bcode,pvcode,pvname from m_village) as f
		on a.dcode=f.dcode and a.bcode=f.bcode and a.pvcode=f.pvcode
		left join
		(select scheme_seq_id,scheme_group_code,scheme_name from m_scheme) as g
		on b.scheme_id=g.scheme_seq_id and b.scheme_group_id=g.scheme_group_code
		left join
		(SELECT  dcode, townpanchayat_id, townpanchayat_name  FROM public.m_townpanchayats where delete_flag is null)as i on a.dcode=i.dcode and a.tpcode=i.townpanchayat_id
		left join
		(SELECT  dcode, municipality_id, municipality_name  FROM public.m_municipality where delete_flag is null)as j on a.dcode=j.dcode and a.muncode=j.municipality_id
		left join
		(SELECT  dcode, corporation_id, corporation_name  FROM public.m_corporation)as k on a.dcode=k.dcode and a.corcode=k.corporation_id
		";
		$res_inspection_details = $this->prepare($inspection_details, array(":work_id"=>$work_id,":inspection_id"=>$inspection_id),2);
		
		foreach ($res_inspection_details as $res_inspection_details_key => $res_inspection_details_val) {
				
				$inspection_id=$res_inspection_details_val['inspection_id'];
				$inspection_details_photos = "select file_name,image_description from osms.t_work_inspection_details_images where del_flag is null and inspection_id=:inspection_id";
				$res_inspection_details_photos = $this->prepare($inspection_details_photos, array(":inspection_id"=>$inspection_id),2);
				
				$res_inspection_details[$res_inspection_details_key]['image_content'] = $res_inspection_details_photos;
		}
		
		$pdf_format_string=$this->pdf_creation_all($res_inspection_details);
		
		if (count($res_inspection_details) > 0 && $pdf_format_string != '') {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$response_data['JSON_DATA'] = array("pdf_string"=>$pdf_format_string);
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'NO_RECORD';
			$response_data['MESSAGE'] = 'NO_RECORD';
		}
		//echo (json_encode($response_data));		
		return $response_data; 
		exit;
	}

	public function pdf_creation_all($data_content)
	{
		// ob_start();
		//print_r($data_content);exit;
		$list_arr=array('a','b','c','d','e','f','g');
		$i=0;
		$output='';
		$output.='<table  border="0" cellpadding="0" cellspacing="0" bordercolor="#5FC1F5" align="center" valign="middle" style="page-break-inside: avoid; width:100%;">
			<tr>
			<td colspan="3" style="padding:2px;line-height:1.6;text-align:right;font-weight:bold;" align="right">Date: '.$data_content[0]['inspection_date'].'</td>
			</tr>
			<tr>
				<td height="35" colspan="3" style="padding:2px;"><h3><strong>Inspection Report</strong></h3></td>
			</tr>';

			if($data_content[0]['rural_urban'] == 'R'){
			$output.='
			<tr>
				<td width="23%" height="35"><strong>('.$list_arr[$i++].'). Name of the Block</strong></td>
				<td width="1%"><strong>:</strong></td>
				<td width="76%">'.$data_content[0]['bname'].'</td>
			</tr>
			<tr>
				<td height="35"><strong>('.$list_arr[$i++].'). Name of the Panchayat</strong></td>
				<td><strong>:</strong></td>
				<td>'.$data_content[0]['pvname'].'</td>
			</tr>';
			} else if($data_content[0]['rural_urban'] == 'U'){
				if($data_content[0]['town_type'] == 'T'){
				$output.='	
				<tr>
					<td width="23%" height="35"><strong>('.$list_arr[$i++].'). Name of the Town panchayat</strong></td>
					<td width="1%"><strong>:</strong></td>
					<td width="76%">'.$data_content[0]['townpanchayat_name'].'</td>
				</tr>';
				} else if($data_content[0]['town_type'] == 'M'){
					$output.='	
					<tr>
						<td width="23%" height="35"><strong>('.$list_arr[$i++].'). Name of the Municipality</strong></td>
						<td width="1%"><strong>:</strong></td>
						<td width="76%">'.$data_content[0]['municipality_name'].'</td>
					</tr>';
				} else if($data_content[0]['town_type'] == 'C'){
					$output.='	
					<tr>
						<td width="23%" height="35"><strong>('.$list_arr[$i++].'). Name of the Corporation</strong></td>
						<td width="1%"><strong>:</strong></td>
						<td width="76%">'.$data_content[0]['corporation_name'].'</td>
					</tr>';
				}
			}
			$output.='
			<tr>
				<td height="35"><strong>('.$list_arr[$i++].'). Name of the Scheme</strong></td>
				<td><strong>:</strong></td>
				<td>'.$data_content[0]['scheme_name'].'</td>
			</tr>
			<tr>
				<td height="35"><strong>('.$list_arr[$i++].'). Estimation </strong></td>
				<td><strong>:</strong></td>
				<td>'.$data_content[0]['as_value'].'</td>
			</tr>
			<tr>
				<td height="35"><strong>('.$list_arr[$i++].'). AS No. and Date</strong></td>
				<td><strong>:</strong></td>
				<td>'.$data_content[0]['as_no'].' and '.$data_content[0]['as_date'].'</td>
			</tr>
			<tr>
				<td height="35"><strong>('.$list_arr[$i++].'). Work ID (TNRD)</strong></td>
				<td><strong>:</strong></td>
				<td>'.$data_content[0]['work_id'].'</td>
			</tr>
			<tr>
				<td height="35"><strong>('.$list_arr[$i++].'). Name of the Work</strong></td>
				<td><strong>:</strong></td>
				<td>'.$data_content[0]['work_name'].'</td>
			</tr>
			<tr>
			<td colspan="3" height="35"><strong>Uploading Photos:-</strong></td>
			</tr>
			<tr>
				<td colspan="4" style="padding-top:10px;">
				<table  border="0" cellpadding="0" cellspacing="0" bordercolor="#5FC1F5" align="center" valign="middle" style="page-break-inside: avoid; width:100%;">';
				
				$slno=1;
				foreach($data_content[0]['image_content'] as $key => $val){
					if($data_content[0]['rural_urban'] == 'R'){
						$File_Path = $this->storage_path . 'work_inspection_photos/' . $this->getDistrictAbbr($data_content[0]['dcode']) . "/" . $this->getBlockAbbr($data_content[0]['dcode'], $data_content[0]['bcode']) . "/" .$data_content[0]['pvcode']."/" . $val['file_name'];
					} else if($data_content[0]['rural_urban'] == 'U'){
						if($data_content[0]['town_type'] == 'T'){
							$File_Path = $this->storage_path . 'work_inspection_photos/' . $this->getDistrictAbbr($data_content[0]['dcode']) . "/town_panchayat/" . $val['file_name'];
						} else if($data_content[0]['town_type'] == 'M'){
							$File_Path = $this->storage_path . 'work_inspection_photos/' . $this->getDistrictAbbr($data_content[0]['dcode']) . "/municipality/" . $val['file_name'];
						} else if($data_content[0]['town_type'] == 'C'){
							$File_Path = $this->storage_path . 'work_inspection_photos/' . $this->getDistrictAbbr($data_content[0]['dcode']) . "/corporation/" . $val['file_name'];
						}				
					}
					$type = pathinfo($File_Path, PATHINFO_EXTENSION);
					$data = file_get_contents($File_Path);
					$base64=$this->ImageCompress($data);
					//$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
					
				if($slno % 2 != 0 && $key == 0){
				$output.='<tr>';	
				} else if($slno % 2 != 0 && $key != 0){ 
					$output.='</tr>
				<tr>';	
				}
				$output.='<td style="width:50%;padding-left:30px;padding-right:30px;" align="center">
						<img src="'.$base64.'" alt="" style="width:45%;height:150px;padding-top:10px;padding-bottom:10px;">
						<span>'.$val['image_description'].'</span>
					</td>';
				if($slno == count($data_content[0]['image_content'])){
				$output.='</tr>';
				}
				$slno++;
				}
				
				$output.='</table>  
				</td>
			</tr>
			<tr>
				<td height="35" style="padding:2px;"><strong>Status :-</strong></td>
				<td style="padding:2px;" colspan="3">'.$data_content[0]['status'].'</td>
			</tr>
			<tr>
				<td height="35" style="padding:2px;"><strong>Instructions  :</strong></td>
				<td style="padding:2px;" colspan="3">'.$data_content[0]['description'].'</td>
			</tr>
		</table><p style="page-break-after: always"></p>';

		
		return base64_encode($output);
	}

	public function other_work_pdf_download($decrypted_data_json)
	{

		$response_data = array();
		$req_params = array();

		$other_work_inspection_id = $decrypted_data_json->other_work_inspection_id;

		$inspection_details = "select statecode,a.dcode,a.bcode,a.pvcode,hab_code,other_work_inspection_id,to_char(inspection_date,'dd-mm-yyyy') as inspection_date,a.status_id,b.status,description,a.other_work_category_id,other_work_detail,fin_year,c.other_work_category_name,d.dname,e.bname,f.pvname,a.rural_urban,a.town_type,townpanchayat_name,municipality_name,corporation_name from 
		(select statecode,dcode,bcode,pvcode,hab_code,other_work_inspection_id,inspection_date,status_id,description,other_work_category_id,other_work_detail,fin_year,rural_urban,town_type,tpcode,muncode,corcode from osms.t_other_work_inspection_details where del_flag is null and other_work_inspection_id=:other_work_inspection_id) as a 
		left join 
		(select status_id,status from m_inspection_status) as b 
		on a.status_id=b.status_id 
		left join 
		(select other_work_category_id,other_work_category_name from osms.m_other_work_category) as c on a.other_work_category_id=c.other_work_category_id
		left join
		(select dcode,dname from m_district where district_type='R') as d
		on a.dcode=d.dcode
		left join
		(select dcode,bcode,bname from m_block) as e
		on a.dcode=e.dcode and a.bcode=e.bcode
		left join
		(select dcode,bcode,pvcode,pvname from m_village) as f
		on a.dcode=f.dcode and a.bcode=f.bcode and a.pvcode=f.pvcode
		left join
		(SELECT  dcode, townpanchayat_id, townpanchayat_name  FROM public.m_townpanchayats where delete_flag is null)as i on a.dcode=i.dcode and a.tpcode=i.townpanchayat_id
		left join
		(SELECT  dcode, municipality_id, municipality_name  FROM public.m_municipality where delete_flag is null)as j on a.dcode=j.dcode and a.muncode=j.municipality_id
		left join
		(SELECT  dcode, corporation_id, corporation_name  FROM public.m_corporation)as k on a.dcode=k.dcode and a.corcode=k.corporation_id
		";
		$res_inspection_details = $this->prepare($inspection_details, array(":other_work_inspection_id"=>$other_work_inspection_id),2);
		
		foreach ($res_inspection_details as $res_inspection_details_key => $res_inspection_details_val) {
				
				$other_work_inspection_id=$res_inspection_details_val['other_work_inspection_id'];
				$inspection_details_photos = "select file_name,image_description from osms.t_other_work_inspection_details_images where del_flag is null and other_work_inspection_id=:other_work_inspection_id";
				$res_inspection_details_photos = $this->prepare($inspection_details_photos, array(":other_work_inspection_id"=>$other_work_inspection_id),2);
				
				$res_inspection_details[$res_inspection_details_key]['image_content'] = $res_inspection_details_photos;
		}
		
		$pdf_format_string=$this->other_pdf_creation($res_inspection_details);
		
		if (count($res_inspection_details) > 0 && $pdf_format_string != '') {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$response_data['JSON_DATA'] = array("pdf_string"=>$pdf_format_string);
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'NO_RECORD';
			$response_data['MESSAGE'] = 'NO_RECORD';
		}
		//echo (json_encode($response_data));		
		return $response_data; 
		exit;
	}

	public function other_pdf_creation($data_content)
	{
		ob_start();
		//print_r($data_content);exit;
		$list_arr=array('a','b','c','d','e','f','g');
		$i=0;
	?>
        <table  border="0" cellpadding="0" cellspacing="0" bordercolor="#5FC1F5" align="center" valign="middle" style="page-break-inside: avoid; width:100%;">
			<tr>
			<td colspan="3" style="padding:2px;line-height:1.6;text-align:right;font-weight:bold;" align="right">Date: <?php echo $data_content[0]['inspection_date']; ?></td>
			</tr>
			<tr>
				<td height="35" colspan="3" style="padding:2px;"><h3><strong>Other Inspection Report</strong></h3></td>
			</tr>
			<?php
			if($data_content[0]['rural_urban'] == 'R'){
			?>	
			<tr>
				<td width="23%" height="35"><strong>(<?php echo $list_arr[$i++]; ?>). Name of the Block</strong></td>
				<td width="1%"><strong>:</strong></td>
				<td width="76%"><?php echo $data_content[0]['bname']; ?></td>
			</tr>
			<tr>
				<td height="35"><strong>(<?php echo $list_arr[$i++]; ?>). Name of the Panchayat</strong></td>
				<td><strong>:</strong></td>
				<td><?php echo $data_content[0]['pvname']; ?></td>
			</tr>
			<?php
			} else if($data_content[0]['rural_urban'] == 'U'){
				if($data_content[0]['town_type'] == 'T'){
			?>
			<tr>
				<td width="23%" height="35"><strong>(<?php echo $list_arr[$i++]; ?>). Name of the Town panchayat</strong></td>
				<td width="1%"><strong>:</strong></td>
				<td width="76%"><?php echo $data_content[0]['townpanchayat_name']; ?></td>
			</tr>
			<?php
				} else if($data_content[0]['town_type'] == 'M'){
			?>
			<tr>
				<td width="23%" height="35"><strong>(<?php echo $list_arr[$i++]; ?>). Name of the Municipality</strong></td>
				<td width="1%"><strong>:</strong></td>
				<td width="76%"><?php echo $data_content[0]['municipality_name']; ?></td>
			</tr>
			<?php	
				} else if($data_content[0]['town_type'] == 'C'){
			?>
			<tr>
				<td width="23%" height="35"><strong>(<?php echo $list_arr[$i++]; ?>). Name of the Corporation</strong></td>
				<td width="1%"><strong>:</strong></td>
				<td width="76%"><?php echo $data_content[0]['corporation_name']; ?></td>
			</tr>
			<?php	
				}
			}
			?>
			<tr>
				<td height="35"><strong>(<?php echo $list_arr[$i++]; ?>). Other Work Category Name</strong></td>
				<td><strong>:</strong></td>
				<td><?php echo $data_content[0]['other_work_category_name']; ?></td>
			</tr>
			<tr>
				<td height="35"><strong>(<?php echo $list_arr[$i++]; ?>). Other Work Detail</strong></td>
				<td><strong>:</strong></td>
				<td><?php echo $data_content[0]['other_work_detail']; ?></td>
			</tr>
            <tr>
				<td height="35"><strong>(<?php echo $list_arr[$i++]; ?>). Financial Year</strong></td>
				<td><strong>:</strong></td>
				<td><?php echo $data_content[0]['fin_year']; ?></td>
			</tr>
			<tr>
			<td colspan="3" height="35"><strong>Uploading Photos:-</strong></td>
			</tr>
			<tr>
				<td colspan="4" style="padding-top:10px;">
				<table  border="0" cellpadding="0" cellspacing="0" bordercolor="#5FC1F5" align="center" valign="middle" style="page-break-inside: avoid; width:100%;">
				
				<?php
				$slno=1;
				foreach($data_content[0]['image_content'] as $key => $val){
					if($data_content[0]['rural_urban'] == 'R'){
						$File_Path = $this->storage_path . 'other_work_inspection_photos/' . $this->getDistrictAbbr($data_content[0]['dcode']) . "/" . $this->getBlockAbbr($data_content[0]['dcode'], $data_content[0]['bcode']) . "/" .$data_content[0]['pvcode']."/" . $val['file_name'];
					} else if($data_content[0]['rural_urban'] == 'U'){
						if($data_content[0]['town_type'] == 'T'){
							$File_Path = $this->storage_path . 'other_work_inspection_photos/' . $this->getDistrictAbbr($data_content[0]['dcode']) . "/town_panchayat/" . $val['file_name'];
						} else if($data_content[0]['town_type'] == 'M'){
							$File_Path = $this->storage_path . 'other_work_inspection_photos/' . $this->getDistrictAbbr($data_content[0]['dcode']) . "/municipality/" . $val['file_name'];
						} else if($data_content[0]['town_type'] == 'C'){
							$File_Path = $this->storage_path . 'other_work_inspection_photos/' . $this->getDistrictAbbr($data_content[0]['dcode']) . "/corporation/" . $val['file_name'];
						}				
					}
					$type = pathinfo($File_Path, PATHINFO_EXTENSION);
					$data = file_get_contents($File_Path);
					$base64=$this->ImageCompress($data);
					//$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
					
				if($slno % 2 != 0 && $key == 0){
				?>
				<tr>	
				<?php 
				} else if($slno % 2 != 0 && $key != 0){ 
				?>
				</tr>
				<tr>
				<?php } ?>
					<td style="width:50%;padding-left:30px;padding-right:30px;" align="center">
						<img src="<?php echo $base64; ?>" alt="" style="width:45%;height:150px;padding-top:10px;padding-bottom:10px;">
						<span><?php echo $val['image_description']; ?></span>
					</td>
				<?php
				if($slno == count($data_content[0]['image_content'])){
				?>		
				</tr>    
				<?php
				}
				$slno++;
				}
				?>  
				
				</table>  
				</td>
			</tr>
			<tr>
				<td height="35" style="padding:2px;"><strong>Status :-</strong></td>
				<td style="padding:2px;" colspan="3"><?php echo $data_content[0]['status']; ?></td>
			</tr>
			<tr>
				<td height="35" style="padding:2px;"><strong>Instructions  :</strong></td>
				<td style="padding:2px;" colspan="3"><?php echo $data_content[0]['description']; ?></td>
			</tr>
		</table>
	<?php

		$output = ob_get_contents();
		ob_end_clean();
		//echo $output;exit;
		$mpdf = new mPDF('ta');
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->WriteHTML($output);
		ob_clean();
		
		return base64_encode($mpdf->Output('','S'));
	}

	public function other_work_pdf_download_all($decrypted_data_json)
	{

		$response_data = array();
		$req_params = array();

		$other_work_inspection_id = $decrypted_data_json->other_work_inspection_id;

		$inspection_details = "select statecode,a.dcode,a.bcode,a.pvcode,hab_code,other_work_inspection_id,to_char(inspection_date,'dd-mm-yyyy') as inspection_date,a.status_id,b.status,description,a.other_work_category_id,other_work_detail,fin_year,c.other_work_category_name,d.dname,e.bname,f.pvname,a.rural_urban,a.town_type,townpanchayat_name,municipality_name,corporation_name from 
		(select statecode,dcode,bcode,pvcode,hab_code,other_work_inspection_id,inspection_date,status_id,description,other_work_category_id,other_work_detail,fin_year,rural_urban,town_type,tpcode,muncode,corcode from osms.t_other_work_inspection_details where del_flag is null and other_work_inspection_id=:other_work_inspection_id) as a 
		left join 
		(select status_id,status from m_inspection_status) as b 
		on a.status_id=b.status_id 
		left join 
		(select other_work_category_id,other_work_category_name from osms.m_other_work_category) as c on a.other_work_category_id=c.other_work_category_id
		left join
		(select dcode,dname from m_district where district_type='R') as d
		on a.dcode=d.dcode
		left join
		(select dcode,bcode,bname from m_block) as e
		on a.dcode=e.dcode and a.bcode=e.bcode
		left join
		(select dcode,bcode,pvcode,pvname from m_village) as f
		on a.dcode=f.dcode and a.bcode=f.bcode and a.pvcode=f.pvcode
		left join
		(SELECT  dcode, townpanchayat_id, townpanchayat_name  FROM public.m_townpanchayats where delete_flag is null)as i on a.dcode=i.dcode and a.tpcode=i.townpanchayat_id
		left join
		(SELECT  dcode, municipality_id, municipality_name  FROM public.m_municipality where delete_flag is null)as j on a.dcode=j.dcode and a.muncode=j.municipality_id
		left join
		(SELECT  dcode, corporation_id, corporation_name  FROM public.m_corporation)as k on a.dcode=k.dcode and a.corcode=k.corporation_id
		";
		$res_inspection_details = $this->prepare($inspection_details, array(":other_work_inspection_id"=>$other_work_inspection_id),2);
		
		foreach ($res_inspection_details as $res_inspection_details_key => $res_inspection_details_val) {
				
				$other_work_inspection_id=$res_inspection_details_val['other_work_inspection_id'];
				$inspection_details_photos = "select file_name,image_description from osms.t_other_work_inspection_details_images where del_flag is null and other_work_inspection_id=:other_work_inspection_id";
				$res_inspection_details_photos = $this->prepare($inspection_details_photos, array(":other_work_inspection_id"=>$other_work_inspection_id),2);
				
				$res_inspection_details[$res_inspection_details_key]['image_content'] = $res_inspection_details_photos;
		}
		
		$pdf_format_string=$this->other_pdf_creation_all($res_inspection_details);
		
		if (count($res_inspection_details) > 0 && $pdf_format_string != '') {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$response_data['JSON_DATA'] = array("pdf_string"=>$pdf_format_string);
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'NO_RECORD';
			$response_data['MESSAGE'] = 'NO_RECORD';
		}
		//echo (json_encode($response_data));		
		return $response_data; 
		exit;
	}

	public function other_pdf_creation_all($data_content)
	{
		// ob_start();
		//print_r($data_content);exit;
		$list_arr=array('a','b','c','d','e','f','g');
		$i=0;
		$output='';	
        $output.='<table  border="0" cellpadding="0" cellspacing="0" bordercolor="#5FC1F5" align="center" valign="middle" style="page-break-inside: avoid; width:100%;">
			<tr>
			<td colspan="3" style="padding:2px;line-height:1.6;text-align:right;font-weight:bold;" align="right">Date: '.$data_content[0]['inspection_date'].'</td>
			</tr>
			<tr>
				<td height="35" colspan="3" style="padding:2px;"><h3><strong>Other Inspection Report</strong></h3></td>
			</tr>';
			if($data_content[0]['rural_urban'] == 'R'){
				$output.='
				<tr>
					<td width="23%" height="35"><strong>('.$list_arr[$i++].'). Name of the Block</strong></td>
					<td width="1%"><strong>:</strong></td>
					<td width="76%">'.$data_content[0]['bname'].'</td>
				</tr>
				<tr>
					<td height="35"><strong>('.$list_arr[$i++].'). Name of the Panchayat</strong></td>
					<td><strong>:</strong></td>
					<td>'.$data_content[0]['pvname'].'</td>
				</tr>';
				} else if($data_content[0]['rural_urban'] == 'U'){
					if($data_content[0]['town_type'] == 'T'){
					$output.='	
					<tr>
						<td width="23%" height="35"><strong>('.$list_arr[$i++].'). Name of the Town panchayat</strong></td>
						<td width="1%"><strong>:</strong></td>
						<td width="76%">'.$data_content[0]['townpanchayat_name'].'</td>
					</tr>';
					} else if($data_content[0]['town_type'] == 'M'){
						$output.='	
						<tr>
							<td width="23%" height="35"><strong>('.$list_arr[$i++].'). Name of the Municipality</strong></td>
							<td width="1%"><strong>:</strong></td>
							<td width="76%">'.$data_content[0]['municipality_name'].'</td>
						</tr>';
					} else if($data_content[0]['town_type'] == 'C'){
						$output.='	
						<tr>
							<td width="23%" height="35"><strong>('.$list_arr[$i++].'). Name of the Corporation</strong></td>
							<td width="1%"><strong>:</strong></td>
							<td width="76%">'.$data_content[0]['corporation_name'].'</td>
						</tr>';
					}
				}

			$output.='	
			<tr>
				<td height="35"><strong>('.$list_arr[$i++].'). Other Work Category Name</strong></td>
				<td><strong>:</strong></td>
				<td>'.$data_content[0]['other_work_category_name'].'</td>
			</tr>
			<tr>
				<td height="35"><strong>('.$list_arr[$i++].'). Other Work Detail</strong></td>
				<td><strong>:</strong></td>
				<td>'.$data_content[0]['other_work_detail'].'</td>
			</tr>
            <tr>
				<td height="35"><strong>('.$list_arr[$i++].'). Financial Year</strong></td>
				<td><strong>:</strong></td>
				<td>'.$data_content[0]['fin_year'].'</td>
			</tr>
			<tr>
			<td colspan="3" height="35"><strong>Uploading Photos:-</strong></td>
			</tr>
			<tr>
				<td colspan="4" style="padding-top:10px;">
				<table  border="0" cellpadding="0" cellspacing="0" bordercolor="#5FC1F5" align="center" valign="middle" style="page-break-inside: avoid; width:100%;">';
				$slno=1;
				foreach($data_content[0]['image_content'] as $key => $val){
					if($data_content[0]['rural_urban'] == 'R'){
						$File_Path = $this->storage_path . 'other_work_inspection_photos/' . $this->getDistrictAbbr($data_content[0]['dcode']) . "/" . $this->getBlockAbbr($data_content[0]['dcode'], $data_content[0]['bcode']) . "/" .$data_content[0]['pvcode']."/" . $val['file_name'];
					} else if($data_content[0]['rural_urban'] == 'U'){
						if($data_content[0]['town_type'] == 'T'){
							$File_Path = $this->storage_path . 'other_work_inspection_photos/' . $this->getDistrictAbbr($data_content[0]['dcode']) . "/town_panchayat/" . $val['file_name'];
						} else if($data_content[0]['town_type'] == 'M'){
							$File_Path = $this->storage_path . 'other_work_inspection_photos/' . $this->getDistrictAbbr($data_content[0]['dcode']) . "/municipality/" . $val['file_name'];
						} else if($data_content[0]['town_type'] == 'C'){
							$File_Path = $this->storage_path . 'other_work_inspection_photos/' . $this->getDistrictAbbr($data_content[0]['dcode']) . "/corporation/" . $val['file_name'];
						}				
					}
					$type = pathinfo($File_Path, PATHINFO_EXTENSION);
					$data = file_get_contents($File_Path);
					$base64=$this->ImageCompress($data);
					//$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
					
				if($slno % 2 != 0 && $key == 0){
				$output.='<tr>';	
				} else if($slno % 2 != 0 && $key != 0){ 
					$output.='</tr>
				<tr>';
				}
				$output.='<td style="width:50%;padding-left:30px;padding-right:30px;" align="center">
						<img src="'.$base64.'" alt="" style="width:45%;height:150px;padding-top:10px;padding-bottom:10px;">
						<span>'.$val['image_description'].'</span>
					</td>';
				if($slno == count($data_content[0]['image_content'])){	
					$output.='</tr>';
				}
				$slno++;
				}
				
				$output.='</table>  
				</td>
			</tr>
			<tr>
				<td height="35" style="padding:2px;"><strong>Status :-</strong></td>
				<td style="padding:2px;" colspan="3">'.$data_content[0]['status'].'</td>
			</tr>
			<tr>
				<td height="35" style="padding:2px;"><strong>Instructions  :</strong></td>
				<td style="padding:2px;" colspan="3">'.$data_content[0]['description'].'</td>
			</tr>
		</table><p style="page-break-after: always"></p>';

		
		
		return base64_encode($output);
	}

	public function action_taken_pdf_download($decrypted_data_json)
	{

		$response_data = array();
		$req_params = array();
		
		$work_id = $decrypted_data_json->work_id;
		$inspection_id = $decrypted_data_json->inspection_id;
		$action_taken_id = $decrypted_data_json->action_taken_id;

		$final_array=array();
		$inspection_details = "  select a.*,b.work_name,b.as_value,b.as_no,b.as_date,c.status,d.district_name_en,e.lbody_name_en,g.scheme_name_en as scheme_name from
		(select work_id,dcode,lbcode,inspection_id,to_char(inspection_date,'dd-mm-yyyy') as
		inspection_date,status_id,description,rural_urban from works.t_work_inspection_details where del_flag is null and work_id=:work_id and inspection_id=:inspection_id) as a
		left join
		(select work_id,work_name,scheme_id,scheme_group_id,as_value,as_no,to_char(as_date,'dd-mm-yyyy') as as_date from works.t_works where work_id=:work_id) as b
		on a.work_id=b.work_id
		left join
		(select status_id,status from master.m_inspection_status) as c
		on a.status_id=c.status_id
		left join
		(select dcode,district_name_en from master.m_district) as d
		on a.dcode=d.dcode
		left join
		(select dcode,lbcode,lbody_name_en from master.m_localbodies where del_flag is null) as e
		on a.dcode=e.dcode and a.lbcode=e.lbcode and d.dcode=e.dcode
		left join
		(select scheme_seq_id,scheme_group_code,scheme_name_en from master.m_scheme) as g
		on b.scheme_id=g.scheme_seq_id and b.scheme_group_id=g.scheme_group_code";
		$res_inspection_details = $this->prepare($inspection_details, array(":work_id"=>$work_id,":inspection_id"=>$inspection_id),2);
		
		foreach ($res_inspection_details as $res_inspection_details_key => $res_inspection_details_val) {
				
				$inspection_id=$res_inspection_details_val['inspection_id'];
				$inspection_details_photos = "select file_name,image_description from works.t_work_inspection_details_images where del_flag is null and inspection_id=:inspection_id";
				$res_inspection_details_photos = $this->prepare($inspection_details_photos, array(":inspection_id"=>$inspection_id),2);
				
				$res_inspection_details[$res_inspection_details_key]['image_content'] = $res_inspection_details_photos;
		}

		$final_array['inspection_details']=$res_inspection_details;

		$inspection_action_taken_details = "select work_id,dcode,lbcode,inspection_id,action_taken_id,to_char(inspection_date,'dd-mm-yyyy') as
		inspection_date,description,rural_urban from works.t_work_inspection_action_taken_details where del_flag is null and work_id=:work_id and inspection_id=:inspection_id and action_taken_id=:action_taken_id";
		$res_inspection_action_taken_details = $this->prepare($inspection_action_taken_details, array(":work_id"=>$work_id,":inspection_id"=>$inspection_id,":action_taken_id"=>$action_taken_id),2);
		
		foreach ($res_inspection_action_taken_details as $res_inspection_action_taken_details_key => $res_inspection_action_taken_details_val) {
				
				$inspection_id=$res_inspection_action_taken_details_val['inspection_id'];
				$action_taken_id=$res_inspection_action_taken_details_val['action_taken_id'];
				$inspection_action_taken_details_photos = "select file_name,image_description from works.t_work_inspection_action_taken_details_images where del_flag is null and inspection_id=:inspection_id and action_taken_id=:action_taken_id";
				$res_inspection_action_taken_details_photos = $this->prepare($inspection_action_taken_details_photos, array(":inspection_id"=>$inspection_id,":action_taken_id"=>$action_taken_id),2);
				
				$res_inspection_action_taken_details[$res_inspection_action_taken_details_key]['image_content'] = $res_inspection_action_taken_details_photos;
		}
		$final_array['action_taken_details']=$res_inspection_action_taken_details;
        //print_r($res_inspection_action_taken_details);die;
		
		$pdf_format_string=$this->action_taken_pdf_creation($final_array);
		//print_r($pdf_format_string);die;
		if (count($res_inspection_action_taken_details) > 0 && $pdf_format_string != '') {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'OK';
			$response_data['JSON_DATA'] = array("pdf_string"=>$pdf_format_string);
		} else {
			$response_data['STATUS'] = 'OK';
			$response_data['RESPONSE'] = 'NO_RECORD';
			$response_data['MESSAGE'] = 'NO_RECORD';
		}
		//echo (json_encode($response_data));		
		return $response_data; 
		exit;
	}

	public function action_taken_pdf_creation($data_content)
	{
		ob_start();
		//print_r($data_content);exit;
		$list_arr=array('a','b','c','d','e','f','g');
		$i=0;
	?>
        <table  border="0" cellpadding="0" cellspacing="0" bordercolor="#5FC1F5" align="center" valign="middle" style="page-break-inside: avoid; width:100%;">
			<tr>
			<td colspan="3" style="padding:2px;line-height:1.6;text-align:right;font-weight:bold;" align="right">Date: <?php echo $data_content['inspection_details'][0]['inspection_date']; ?></td>
			</tr>
			<tr>
				<td height="35" colspan="3" style="padding:2px;"><h3><strong>Inspection Report</strong></h3></td>
			</tr>
			<tr>
				<td height="35"><strong>(<?php echo $list_arr[$i++]; ?>). Name of the Scheme</strong></td>
				<td><strong>:</strong></td>
				<td><?php echo $data_content['inspection_details'][0]['scheme_name']; ?></td>
			</tr>
			<tr>
				<td height="35"><strong>(<?php echo $list_arr[$i++]; ?>). Estimation </strong></td>
				<td><strong>:</strong></td>
				<td><?php echo $data_content['inspection_details'][0]['as_value']; ?></td>
			</tr>
			<tr>
				<td height="35"><strong>(<?php echo $list_arr[$i++]; ?>). AS No. and Date</strong></td>
				<td><strong>:</strong></td>
				<td><?php echo $data_content['inspection_details'][0]['as_no']; ?> and <?php echo $data_content['inspection_details'][0]['as_date']; ?></td>
			</tr>
			<tr>
				<td height="35"><strong>(<?php echo $list_arr[$i++]; ?>). Work ID (TNRD)</strong></td>
				<td><strong>:</strong></td>
				<td><?php echo $data_content['inspection_details'][0]['work_id']; ?></td>
			</tr>
			<tr>
				<td height="35"><strong>(<?php echo $list_arr[$i++]; ?>). Name of the Work</strong></td>
				<td><strong>:</strong></td>
				<td><?php echo $data_content['inspection_details'][0]['work_name']; ?></td>
			</tr>
			<tr>
			<td colspan="3" height="35"><strong>Uploading Photos:-</strong></td>
			</tr>
			<tr>
				<td colspan="4" style="padding-top:10px;">
				<table  border="0" cellpadding="0" cellspacing="0" bordercolor="#5FC1F5" align="center" valign="middle" style="page-break-inside: avoid; width:100%;">
				
				<?php
				$slno=1;
				foreach($data_content['inspection_details'][0]['image_content'] as $key => $val){
					$Base_path = $this->getStoragePath() . "Document/work/work_inspection_photos/";
				$Temp_Base_path = $Base_path  . $data_content['inspection_details'][0]['dcode'] . '/' . $data_content['inspection_details'][0]['lbcode'];
				$path_to_save_file=$Temp_Base_path."/".$val['file_name'];
                //print_r($path_to_save_file);die;
					$type = pathinfo($path_to_save_file, PATHINFO_EXTENSION);
					$data = file_get_contents($path_to_save_file);
					$base64=$this->ImageCompress($data);
					//$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
					
				if($slno % 2 != 0 && $key == 0){
				?>
				<tr>	
				<?php 
				} else if($slno % 2 != 0 && $key != 0){ 
				?>
				</tr>
				<tr>
				<?php } ?>
					<td style="width:50%;padding-left:30px;padding-right:30px;" align="center">
						<img src="<?php echo $base64; ?>" alt="" style="width:45%;height:150px;padding-top:10px;padding-bottom:10px;">
						<span><?php echo $val['image_description']; ?></span>
					</td>
				<?php
				if($slno == count($data_content['inspection_details'][0]['image_content'])){
				?>		
				</tr>    
				<?php
				}
				$slno++;
				}
				?>  
				
				</table>  
				</td>
			</tr>
			<tr>
				<td height="35" style="padding:2px;"><strong>Status :-</strong></td>
				<td style="padding:2px;" colspan="3"><?php echo $data_content['inspection_details'][0]['status']; ?></td>
			</tr>
			<tr>
				<td height="35" style="padding:2px;"><strong>Instructions  :</strong></td>
				<td style="padding:2px;" colspan="3"><?php echo $data_content['inspection_details'][0]['description']; ?></td>
			</tr>
			<tr>
				<td height="35" colspan="3" style="padding:2px;"><h3><strong>Action Taken Report</strong></h3></td>
			</tr>
            <tr>
				<td colspan="4" style="padding-top:10px;">
				<table  border="0" cellpadding="0" cellspacing="0" bordercolor="#5FC1F5" align="center" valign="middle" style="page-break-inside: avoid; width:100%;">
				
				<?php
				$slno=1;
				foreach($data_content['action_taken_details'][0]['image_content'] as $key => $val){
					

                    $Base_path = $this->getStoragePath() . "Document/work/work_inspection_action_taken_photos/";
				$Temp_Base_path = $Base_path  . $data_content['action_taken_details'][0]['dcode'] . '/' . $data_content['action_taken_details'][0]['lbcode'];
				$path_to_save_file=$Temp_Base_path."/".$val['file_name'];


					$type = pathinfo($path_to_save_file, PATHINFO_EXTENSION);
					$data = file_get_contents($path_to_save_file);
					$base64=$this->ImageCompress($data);
					//$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
					
				if($slno % 2 != 0 && $key == 0){
				?>
				<tr>	
				<?php 
				} else if($slno % 2 != 0 && $key != 0){ 
				?>
				</tr>
				<tr>
				<?php } ?>
					<td style="width:50%;padding-left:30px;padding-right:30px;" align="center">
						<img src="<?php echo $base64; ?>" alt="" style="width:45%;height:150px;padding-top:10px;padding-bottom:10px;">
						<span><?php echo $val['image_description']; ?></span>
					</td>
				<?php
				if($slno == count($data_content['action_taken_details'][0]['image_content'])){
				?>		
				</tr>    
				<?php
				}
				$slno++;
				}
				?>  
				
				</table>  
				</td>
			</tr>
			<tr>
				<td height="35" style="padding:2px;"><strong>Instructions  :</strong></td>
				<td style="padding:2px;" colspan="3"><?php echo $data_content['action_taken_details'][0]['description']; ?></td>
			</tr>
		</table>
	<?php

        $output = ob_get_contents();
        //print_r($output);die;
	ob_end_clean();
	$this->mpdf->SetDisplayMode('fullpage');
	$this->mpdf->WriteHTML($output);
	ob_clean();
	return base64_encode($this->mpdf->Output('','S'));

	}
	
	public function get_work_details_pdf($data_content)
	{
		ob_start();

		$dcode = $data_content->dcode;
		$bcode = $data_content->bcode;
		$work_id_array=array();
		$work_id_array = json_decode($data_content->work_id);
			$work_id_array = array_combine(
				array_map(function ($i) {
					return ':work_id' . $i;
				}, array_keys($work_id_array)),
				$work_id_array
			);
			$work_id_cond = " and work_id in (" . implode(',', array_keys($work_id_array)) . ")";
			
		$sql = "select dcode,dabbr,dname from m_district where dcode=:dcode";
		$res_dist = $this->prepare($sql, array(":dcode"=>$dcode),4);

		$sql = "select dcode,bcode,babbr,bname from m_block where dcode=:dcode and bcode=:bcode;";
        $res_blk = $this->prepare($sql,array(":dcode"=>$dcode,":bcode"=>$bcode),4);

		$sql = "select to_char(current_date,'DD-MM-YYYY') as current_date";
        $res_cur_date = $this->prepare($sql,array(),4);

		?>
		<table  border="0" cellpadding="0" cellspacing="0" bordercolor="#5FC1F5" align="center" valign="middle" style="page-break-inside: avoid; width:100%;">
		<tr>
			<td height="25" colspan="4" align="right"><strong>Date:&nbsp; <?php echo $res_cur_date['current_date']; ?></strong></td>
		</tr>
		<tr>
			<td height="25" colspan="4" align="center"><strong>Inspection Plan Details</strong></td>
		</tr>
		<tr>
			<td colspan="2" align="center" style="width:50%;"><strong>District:&nbsp; <?php echo $res_dist['dname']; ?></strong></td>
			<td colspan="2" align="center"><strong>Block:&nbsp; <?php echo $res_blk['bname']; ?></strong></td>
		</tr>
		</table>
		<br />
		<?php
		$sql = "select dcode,bcode,pvcode,pvname from m_village where (dcode,bcode,pvcode) in (select dcode,bcode,pvcode from t_works where dcode=:dcode and bcode=:bcode $work_id_cond)";
        $res_vill = $this->prepare($sql,array_merge(array(':dcode' => $dcode, ':bcode' => $bcode), $work_id_array),2);

		foreach($res_vill as $key => $val){
		?>
		<table  border="1" cellpadding="0" cellspacing="0" bordercolor="#5FC1F5" align="center" valign="middle" style="page-break-inside: avoid; width:100%;">
		<tr>
			<th colspan="4" align="center" style="padding: 2px;">Village:&nbsp; <?php echo $val['pvname']; ?></th>
		</tr>
		<tr>
			<th style="padding: 2px;">Work ID</th>
			<th style="padding: 2px;">Work Name</th>
			<th style="padding: 2px;">Work Type Name</th>
			<th style="padding: 2px;">AS Value</th>
		</tr>
		<?php
		$inspection_work_details_list = "select a.dcode,a.bcode,a.pvcode,hab_code,scheme_group_id,scheme_id,a.work_group_id,a.mwork_id as work_type_id,fin_year,a.work_id,work_name,as_value,ts_value,current_stage_of_work,stage_name,to_char(a.as_date,'DD-MM-YYYY') as as_date,
		to_char(a.ts_date,'DD-MM-YYYY') as ts_date,
		to_char(TO_DATE(a.agreement_date,'DD/MM/YYYY'),'DD-MM-YYYY') as work_order_date,
		wtype.work_type_name as work_type_name,d.scheme_name,dname,bname,pvname	
 		from (select * from t_works where dcode=:dcode and bcode=:bcode and pvcode=:pvcode /*and current_stage_of_work!=11*/ $work_id_cond) as a 
		left join
		(select stage_id,stage_name from m_stage) as c
		on a.current_stage_of_work=c.stage_id
		LEFT JOIN (SELECT work_id AS work_type_id,work_group_id,work_name as work_type_name FROM m_work_type ) as wtype on a.work_group_id=wtype.work_group_id and a.mwork_id=wtype.work_type_id
		left join
		(select scheme_seq_id,scheme_group_code,scheme_name from m_scheme) as d
		on a.scheme_id=d.scheme_seq_id and a.scheme_group_id=d.scheme_group_code
		left join 
		(select dcode,dname from m_district where dcode=:dcode) as e on a.dcode=e.dcode
		left join 
		(SELECT dcode,bcode,bname FROM m_block where dcode=:dcode and bcode=:bcode) as f on a.dcode=f.dcode and a.bcode=f.bcode
		left join 
		(SELECT dcode,bcode,pvcode,pvname FROM m_village where dcode=:dcode and bcode=:bcode and pvcode=:pvcode) as g on a.dcode=g.dcode and a.bcode=g.bcode and a.pvcode=g.pvcode order by pvname
		";

		$res = $this->prepare($inspection_work_details_list, array_merge(array(':dcode' => $val['dcode'], ':bcode' => $val['bcode'], ':pvcode' => $val['pvcode']), $work_id_array), 2);

		foreach($res as $work_det_key => $work_det_val){
	?>
		<tr>
			<td style="padding: 2px;"><?php echo $work_det_val['work_id']; ?></td>
			<td style="padding: 2px;"><?php echo $work_det_val['work_name']; ?></td>
			<td style="padding: 2px;"><?php echo $work_det_val['work_type_name']; ?></td>
			<td style="padding: 2px;"><?php echo $work_det_val['as_value']; ?></td>
		</tr>
	<?php
		}
	?>
		</table>
		<br>
	<?php
		}

	$output = ob_get_contents();
	ob_end_clean();
	//echo $output;exit;
	$mpdf = new mPDF('ta');
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->WriteHTML($output);
	ob_clean();

	$pdf_format_string = base64_encode($mpdf->Output('','S'));

	if (count($res_vill) > 0 && $pdf_format_string != '') {
		$response_data['STATUS'] = 'OK';
		$response_data['RESPONSE'] = 'OK';
		$response_data['JSON_DATA'] = array("pdf_string"=>$pdf_format_string);
	} else {
		$response_data['STATUS'] = 'OK';
		$response_data['RESPONSE'] = 'NO_RECORD';
		$response_data['MESSAGE'] = 'NO_RECORD';
	}

	//echo (json_encode($response_data));		
	return $response_data; 
	exit;
	}

	public function getDistrictAbbr($dcode = "")
    {

        $sql = "select dabbr from m_district where dcode=:dcode";

        $dist_abr = $this->prepare($sql, array(":dcode"=>$dcode),4);

        if ($dist_abr['dabbr'] == "") {
            echo "ERROR";
            exit;
        }

        return strtolower($dist_abr['dabbr']);

    }

    public function getBlockAbbr($dcode = "", $bcode = "")
    {

        $sql = "select babbr from m_block where dcode=:dcode and bcode=:bcode;";

        $babbr_abr = $this->prepare($sql,array(":dcode"=>$dcode,":bcode"=>$bcode),4);

        if ($babbr_abr['babbr'] == "") {
            echo "ERROR";
            exit;
        }

        return strtolower($babbr_abr['babbr']);

    }
}
?>