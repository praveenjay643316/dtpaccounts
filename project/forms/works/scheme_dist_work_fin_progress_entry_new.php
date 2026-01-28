<?php
include('../pageheader_new.php');
include('../class_data_access.php');
$obj=new dataaccess();
$state=$_SESSION["state_code"];
$district=$_SESSION['dist_code'];
$block=$_SESSION['block_code'];
$today = date("F j, Y");
$upd_date=date("Y-m-d");
$user=$_SESSION["username"];

$sqlent="select * from m_agency where dcode=$district and bcode is null";
$selres=$obj->selfn($sqlent,$db);
if(count($selres)==0) 
{
		echo "<blink><center><br><b r><H3>You are not authorised to view this page.</H3></center></blink>";
		$url  =  "home.php"; // target of the redirect
		$delay  =  "0"; // 50 second delay
		die( '<meta http-equiv = "refresh" content = "'.$delay.';url = '.$url.'">');
		exit;
}
$role_code = $_SESSION['user_id'];
$usertype = $_SESSION['user_type'];
//if ($_SESSION['dist_code']!=29) {
/*if ($usertype != 'DAU' && $usertype != 'ADP' && $usertype != 'DEDU') {
		echo "<blink><center><br><b r><H3>You are not authorised to view this page.</H3></center></blink>";
		$url  =  "../logout.php"; // target of the redirect
		$delay  =  "0"; // 50 second delay
		die( '<meta http-equiv = "refresh" content = "'.$delay.';url = '.$url.'">');
		exit;
}*/
?>
<?PHP
if(isset($_REQUEST['hid_savfrm']) && $_REQUEST['hid_savfrm']==1)
{
	$flag=0;
	$flag1=0;
	$resfin=0;
	$resphy=0;
	
	$work_id=$_REQUEST['txt_workid'];
	$schgrpid=$_REQUEST['hid_schgrpid'];
    $scheme_id=$_REQUEST['hid_schid'];
	$finyr=$_REQUEST['finyr'];
	
	
	$instmax="select max(instalment_no) as insmax from t_scheme_work_financial_progress where work_id=$work_id";
	$maxinrw=$obj->selonefn($instmax,$db);
	//echo 'test2'.$maxinrw['insmax']; 

	$instalment_no=$maxinrw['insmax']+1;
		
	//echo 'instno'. $instalment_no; exit;
		
	
	if($_REQUEST['txt_amt']!='')
	$amount_paid=$_REQUEST['txt_amt'];
	else
	$amount_paid=0;
	
	if($_REQUEST['txt_vocno']!='')
	$voucher_no=$_REQUEST['txt_vocno'];
	else
	$voucher_no='null';
    
	$word  = $_REQUEST['txt_datepay'];
	$pieces = explode("/", $word);
	$dd=$pieces[0];
	$mm=$pieces[1];
	$yy=$pieces[2];
	$paid_date="'".$yy."-".$mm."-".$dd."'";


	if($_REQUEST['rad_wc']==1) {
		$yn_work_completed="'".Y."'";
		$stage_code=11;
	}
	else {
		$yn_work_completed="'".N."'";
		$stage_code = $_REQUEST['stage_id'];
	}

  $is_dovetail_payment='false';
  if($scheme_id == 727 || $scheme_id == 728 || $scheme_id == 729){
    $is_dovetail_payment=$_REQUEST['is_dovetail_payment'];
  }

		$db->autoCommit(false);
	
			 $username=$_SESSION["username"];
			 $ipaddress=$obj->getRealIpAddr();
			 $upd_date=date("Y-m-d H:i:s");
		
		$selqry="select amount_spent_sofar from t_works where work_id=$work_id";
		$selrw=$obj->selonefn($selqry,$db);
		
		if($selrw['amount_spent_sofar']=='')
			$cur_amt=0;
		else
			$cur_amt=$selrw['amount_spent_sofar'];
		
		$upd_amt=$cur_amt+$amount_paid;
		
		if($schgrpid != '')
		{
			$asamount = $_REQUEST['hid_asval'];
			$totbill = ($_REQUEST['hid_bilamt'] + $amount_paid);
			$yn_work_completed1 = substr($yn_work_completed, 1,1);
				
				if($yn_work_completed1=='Y')
				{
					
					$savings_amt = $_REQUEST['sav_amount'];
				 
					if($savings_amt=="")
					{
						 $savings_amt = $asamount - $totbill;
					}
					$actual_amount= $totbill + $savings_amt; 
					
					if($asamount == $actual_amount)
					 {
							//Financial Insert
							$insqry="select * from sp_work_fin_progress_new($work_id,$scheme_id,$stage_code,$instalment_no,$amount_paid,$voucher_no,$paid_date,
				  $yn_work_completed,'$upd_date','$username','$ipaddress',$upd_amt,$savings_amt, $is_dovetail_payment)";
							//echo $insqry; //exit;
							$flag=$obj->idufn($insqry,$db);
							
							//Physical Insert
							 $insphy = "insert into t_scheme_work_physical_progress(scheme_id,fin_year,work_id,stage_id,upd_date,user_name,ipaddress, cd_prot_workid, cd_type_flag, cd_chainage) 
				   values ($scheme_id,'$finyr',$work_id,$stage_code,'$upd_date','$username','$ipaddress', 0, 'N', 0.0)";
				 $flag1=$obj->idufn($insphy,$db);
				 
				 
							if($flag==0 && $flag1==0) {
								$db->commit();
								$db->autoCommit(true); 	
								unset($_POST);
								echo "<script language='javascript'>
							   alert('Record Saved Successfully');
							   window.location.href='scheme_dist_work_fin_progress_entry_new.php';
							   </script>";
					 }
					}
					else
							{
								unset($_POST);
								echo "<script language='javascript'>
										alert('AS Amount and Amount Spent is not matched, So you can not make the Work as Completed');
										window.location.href='scheme_dist_work_fin_progress_entry_new.php';
								   </script>";
							}
				}
				else
				{
								if((($asamount != $totbill) && ($asamount >= $totbill)) || ($asamount == $totbill) )
								{
									$savings_amt = 0;
									$insqry="select * from sp_work_fin_progress_new($work_id,$scheme_id,$stage_code,$instalment_no,$amount_paid,$voucher_no,$paid_date,
						  $yn_work_completed,'$upd_date','$username','$ipaddress',$upd_amt,$savings_amt, $is_dovetail_payment)";
									//echo $insqry; 
									$flag=$obj->idufn($insqry,$db);
									if($flag==0) {
										$db->commit();
										$db->autoCommit(true); 	
										unset($_POST);
									echo "<script language='javascript'>
											alert('Record Saved Successfully');
											window.location.href='scheme_dist_work_fin_progress_entry_new.php';
									   </script>";
									}
								}
								else
								{
									
									unset($_POST);
									echo "<script language='javascript'>
											alert('Bill Amount exceeds AS Amount.Please revise the Estimate Amount using Form No DF-08.');
											window.location.href='scheme_dist_work_fin_progress_entry.php';
									   </script>";
								}
				}
		
		
		}
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Financial Progress Entry Form</title>

<link href="../includes/jquery-ui.css" rel="stylesheet" type="text/css" />
<link href="../includes/sort_freeze.css" rel="stylesheet" type="text/css" />
<!--<link href="../includes/jquery.superbox.css" rel="stylesheet" type="text/css">-->

<script type="text/javascript" language="javascript" src="../js/jquery-1.7.2.js"></script> 
<script type="text/javascript" src="../js/jquery-ui.min.js"></script> 
<script type="text/javascript" src="scheme_dist_work_fin_progress_entry_new_js.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery.superbox.js"></script>

</head>
    <style>
        .textbox {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            width:150px;
            height:23px;
            font-weight: normal;
            color: #333333;
            text-decoration: none;
            border: 1px solid #6699CC;
            text-align:left;
            -moz-border-radius: 5px;
            border-radius: 5px;
            padding:3px;
        }
			
     .cmbstyle {  font-family: Arial, Helvetica, sans-serif;
                     font-size: 12px;
                     width:155px;
                     height:28px;
                     color: #333333;
                     text-decoration: none;
                     border: 1px solid #6699CC;
                     -moz-border-radius: 2px;
                     border-radius: 4px;

        }	
		
		.zoom_img img{
		-moz-transition:-moz-transform 0.1s ease-in; 
		-webkit-transition:-webkit-transform 0.1s ease-in; 
		-o-transition:-o-transform 0.1s ease-in;
		}
		
		.zoom_img img:hover{
		-moz-transform:scale(2.5); 
		-webkit-transform:scale(2.5);
		-o-transform:scale(2.5);
		}
	</style>
<body>
<tr>
<td>
<form id="frm_scheme_exp" name="frm_scheme_exp" method="post" action="">
<input type="hidden" id="mode" name="mode" />
<input type="hidden" id="hid_savfrm" name="hid_savfrm" />
<input type="hidden" id="distid" name="distid" value="<?php echo $_SESSION['dist_code']; ?>" />
<table><tr><td height="5">&nbsp;</td></tr></table>
<table  align="center" width="45%" class="tablesorterform colorchform">
  <tr>
    <th height="34" colspan="2" align="center" >Financial Progress Entry Screen</th>
    </tr>
	<tr class="hidwid" <?php if($_POST['rururb'] != '') { ?> style="display:none;" <?php } ?>>
    <td width="41%" height="34" align="left" >Enter Work Id: :&nbsp;</td>
    <td align="left" style="padding:8px"><input name="txt_workid" type="textfield" class="textbox_nw_bg" tabindex="1" id="txt_workid" value="<?php echo $_REQUEST['txt_workid']; ?>"  />&nbsp;<span class="fillwrkid button" style="font-size:11px; cursor:pointer"><strong>Know Your Work ID</strong></span></td>
    </tr>

	<tr class="shwid" <?php if($_POST['rururb'] == '') { ?> style="display:none" <?php } ?>>
	  <td align="left" valign="middle">Select Rural/Urban Works</td>
	  <td height="35" align="left" valign="middle"><select name="rururb" class="cmbstyle" id="rururb">
                    	<option value="">Select Rural/Urban</option>
                    	<option value="R" <?php echo ($_POST['rururb']=='R')?'selected="selected"':'';  ?>>Rural</option>
                    	<option value="U" <?php echo ($_POST['rururb']=='U')?'selected="selected"':'';  ?>>Urban</option>
                    </select></td>
	  </tr>
	<tr class="rurid" <?php if($_POST['rururb']!='R') { ?> style="display:none;" <?php } ?> >
    <td width="324" align="left" valign="middle"><strong>Select Block Name</strong></td>
    <td width="421" height="35" align="left" valign="middle">
    <select name="blk_popup" class="cmbstyle"   id="blk_popup" <?php if (isset($_REQUEST['all_blks'])) { ?> disabled="disabled" <?php } ?> >
      <option value="">Select Block</option>
	   <?php
            if($district!='' ){
				$block = $_REQUEST['blk_popup'];
            $sel_res= "select bcode,bname from m_block where dcode=$district  order by bname asc ";
            $selres = $obj->selfn($sel_res, $db);
            foreach ($selres as $row) {
                ?>
        <option value="<?php echo $row['bcode']; ?>"><?php echo $row['bname']; ?></option>
        <script>document.getElementById('blk_popup').value=<?php echo $_REQUEST['blk_popup']; ?></script>
        <?php
            }
            }
            ?>
    </select>
	<script>document.getElementById('blk_popup').value="<?php echo $_REQUEST['blk_popup']; ?>"</script>
    <input type= "checkbox" name ="all_blks" id="all_blks"<?php if (isset($_REQUEST['all_blks'])) { ?> checked="checked" <?php } ?> 
                                   onchange="if(this.checked==true)blk_popup.disabled=true;else blk_popup.disabled=false; blk_popup.value=''; " />
          All Blocks </td>
  </tr>
  <tr class="rurid" <?php if($_POST['rururb']!='R') { ?> style="display:none;" <?php } ?> >
    <td align="left" valign="middle"><strong>Select Village Name</strong></td>
    <td height="35" align="left" valign="middle">
      <select name="cmb_village" class="cmbstyle"  id="cmb_village" <?php if (isset($_REQUEST['all_villgs'])) { ?> disabled="disabled" <?php } ?> >
        <option value="">Select Village</option>
        <?php
            if($district!=''  && $_REQUEST['blk_popup'] != ''){
				$block = $_REQUEST['blk_popup'];
            $sel_res= "select * from m_village where dcode=$district and bcode=$block and delete_pvname='Y' order by pvname asc ";
            $selres = $obj->selfn($sel_res, $db);
            foreach ($selres as $row) {
                ?>
        <option value="<?php echo $row['pvcode']; ?>"><?php echo $row['pvname']; ?></option>
        <script>document.getElementById('cmb_village').value=<?php echo $_REQUEST['cmb_village']; ?></script>
        <?php
            }
            }
            ?>
      </select>
	  <input type= "checkbox" name ="all_villgs" id="all_villgs"<?php if (isset($_REQUEST['all_villgs'])) { ?> checked="checked" <?php } ?> 
                                   onchange="if(this.checked==true)cmb_village.disabled=true;else cmb_village.disabled=false; cmb_village.value=''; " />
          All Villages </td>
  </tr>
  <tr class="urbid" <?php if($_POST['rururb']!='U') { ?> style="display:none;" <?php } ?>>
    <td align="left" valign="middle"><strong>Type of Urban Local Body</strong></td>
    <td height="35" align="left" valign="middle">
       <select name="cmb_urban" class="cmbstyle validate[urbn] text-input"  id="cmb_urban" >
          <option value="" >Select Urban Local Body</option>
          <option value="1">Town Panchayat</option>
          <option value="2">Municipalities</option>
          <option value="3">Corporation</option>
       </select>
       <script>document.getElementById('cmb_urban').value=<?php echo $_REQUEST['cmb_urban']; ?></script>
    </td>
  </tr>
  
  <tr id="tpid"  <?php if($_POST['rururb'] != 'U' || $_POST['cmb_urban'] != '1') { ?> style="display:none;" <?php } ?>>
    <td align="left" valign="middle"><strong>Select Town Panchayat</strong></td>
    <td height="35" align="left" valign="middle"><select name="cmb_twnpan" class="cmbstyle" id="cmb_twnpan" <?php if (isset($_REQUEST['all_tpts'])) { ?> disabled="disabled" 	<?php } ?>  >
      <option value="">Select Town Panchayat</option>
     <?php
                $sel_tp = "select townpanchayat_id,townpanchayat_name,dcode from m_townpanchayats where dcode=$district order by townpanchayat_name";
                $seltp = $obj->selfn($sel_tp, $db);
                foreach ($seltp as $tprow) {
                    echo "<option value='" . $tprow['townpanchayat_id'] . "' >" . ucwords(strtolower($tprow["townpanchayat_name"])) . "</option>";
                }
               ?>
    </select><script>document.getElementById('cmb_twnpan').value=<?php echo $_REQUEST['cmb_twnpan']; ?></script>
    <input type= "checkbox" name ="all_tpts" id="all_tpts"<?php if (isset($_REQUEST['all_tpts'])) { ?> checked="checked" <?php } ?> 
                                   onchange="if(this.checked==true) cmb_twnpan.disabled=true;else cmb_twnpan.disabled=false; cmb_twnpan.value=''; " />
          All Townpanchayats </td>
  </tr>
  <tr id="munid"  <?php if($_POST['rururb'] != 'U' || $_POST['cmb_urban'] != 2) { ?> style="display:none;" <?php } ?>>
    <td align="left" valign="middle"><strong>Select Municipality</strong></td>
    <td height="35" align="left" valign="middle">
        <select name="cmb_muncp" class="cmbstyle" id="cmb_muncp" <?php if (isset($_REQUEST['all_mptys'])) { ?> disabled="disabled" <?php } ?>  >
             <option value="">Select Muncipality</option>
                    <?php
                        $sel_mp = "select municipality_id,municipality_name,dcode from m_municipality where dcode=$district order by municipality_name";
                        $selmp = $obj->selfn($sel_mp, $db);
                        foreach ($selmp as $mprow) {
                        echo "<option value='" . $mprow['municipality_id'] . "' >" . ucwords(strtolower($mprow["municipality_name"])) . "</option>";
                         }
                     ?>
         </select>
         <script>document.getElementById('cmb_muncp').value=<?php echo $_REQUEST['cmb_muncp']; ?></script>
             <input type= "checkbox" name ="all_mptys" id="all_mptys"<?php if (isset($_REQUEST['all_mptys'])) { ?> checked="checked" <?php } ?> 
                                   onchange="if(this.checked==true) cmb_muncp.disabled=true;else cmb_muncp.disabled=false; cmb_muncp.value=''; " />
          All Municipalities    
    </td>
  </tr>
  <tr id="corpid" <?php if($_POST['rururb'] != 'U' || $_POST['cmb_urban'] != 3) { ?> style="display:none;" <?php } ?>>
    <td align="left" valign="middle"><strong>Select Corporation</strong></td>
    <td height="35" align="left" valign="middle">
    	<select name="cmb_corp" class="cmbstyle" id="cmb_corp" >
          <option value="">Select Corporation</option>
				<?php
                $sel_cp = "select corporation_id,corporation_name,dcode from m_corporation where dcode=$district order by corporation_name";
                $selcp = $obj->selfn($sel_cp, $db);
                foreach ($selcp as $cprow) {
                    echo "<option value='" . $cprow['corporation_id'] . "'  >" . ucwords(strtolower($cprow["corporation_name"])) . "</option>";
                }
                ?>
            </select><script>document.getElementById('cmb_corp').value=<?php echo $_REQUEST['cmb_corp']; ?></script></td>
  </tr>
  <tr class="shwid" <?php if($_POST['fin_year'] == '') { ?> style="display:none" <?php } ?>>
    <td align="left" valign="middle"><strong>Select Year</strong></td>
    <td height="35" align="left" valign="middle"><strong>
      <select name="fin_year" class="cmbstyle" id="fin_year">
					 <option value="" >Select Fin Year </option>
					<?php  
						$sel_yr="select * from m_finyear_scheme WHERE disp_order in(1,2,3,4)  order by disp_order desc ";
						$selyr=$obj->selfn($sel_yr,$db);
						foreach($selyr as $selrow)
						{
						if($selrow['fin_year']==$_REQUEST['fin_year'])
						$sel="selected";
						else
						$sel="";
						?>
					<option value="<?= $selrow['fin_year']; ?>"<?php echo $sel; ?> >
					  <?= $selrow['fin_year']; ?>
			  </option>
					<?php
						}				 
						?>
		    </select>
    </strong></td>
  </tr>
  <tr class="shwid" <?php if($_POST['scheme'] == '') { ?> style="display:none" <?php } ?>>
    <td align="left" valign="middle"><strong>Scheme</strong></td>
    <td height="35" align="left" valign="middle"><strong>
      <select name="scheme" id="scheme" class="cmbstyle" >
                        <option value="">Select Scheme</option>
                        <?php
						    $schemenot = ''; if($usertype == 'DAU') { $schemenot = 'and scheme_seq_id not in(422)';  }
                           $qry_sh = "select dtlnk.scheme_seq_id,dtlnk.scheme_name from
			(SELECT scheme_grp_id,scheme_id FROM m_role_type_wise_scheme_link where sch_role_link_y_n=1 and role_id=$role_code ORDER BY scheme_grp_id,scheme_id)mq
			INNER JOIN
			(select scheme_group_id,scheme_seq_id,scheme_name from m_scheme_district_link where dcode=$district  $schemenot order by scheme_name)dtlnk
			on mq.scheme_grp_id=dtlnk.scheme_group_id and mq.scheme_id=dtlnk.scheme_seq_id  ORDER BY scheme_name";
                            $arrsh = $obj->selfn($qry_sh, $db);
                            foreach ($arrsh as $shrow) {
                                ?>
                        <option value="<?= $shrow['scheme_seq_id']; ?>" <? if ($_REQUEST['scheme'] == $shrow['scheme_seq_id']) echo 'selected'; ?>>
                          <?= $shrow['scheme_name']; ?>
                        </option>
                        <?php
                            }
                         ?>
            </select>
    </strong></td>
  </tr>    
  <tr>
    <th height="26" colspan="2" align="center">
      <input type="submit" name="showfrm" id="showfrm" class="button" value="View Details"/>
   </th>
    </tr>
</table>
<br />
<?PHP
if(isset($_REQUEST['showfrm']) && $_REQUEST['showfrm'] == 'View Details')
{
	
	
	if (isset($_REQUEST['rururb']) && $_REQUEST['rururb'] != '')
	{
?>
<table align="center" width="85%" border="1" class="tablesorterform colorchform">
  <tr >
        <th width="4%" height="30" valign="middle"><strong>S.No.</strong></th>
        <th width="10%" valign="middle" >Work ID</th>
        <th width="17%" valign="middle" >Work Name</th>
        <th width="8%"  height="30" valign="middle"><strong>Block Name / Village Panchayat / Habitation </strong></th>
        <th width="9%" valign="middle"><strong>Town Panchayat <br />
          / <br />
          Municipality <br />
          /<br />
          Corporation
        </strong></th>
        <th width="9%" valign="middle">Agency Group Name</th>
        <th width="9%" valign="middle">Agency Name</th>
        <th width="6%" valign="middle">Work Group</th>
       
        <th width="6%"  height="30" valign="middle">Work Type</th>
        <th width="6%" valign="middle">AS Amount</th>
        <th width="6%" valign="middle">TS Amount</th>
        <th width="6%" valign="middle">Amount Spent</th>
        <th width="9%" valign="middle" >Present Stage</th>
        <th width="9%" valign="middle" >Select <br />
          Work ID</th>
  </tr>
  <input type="hidden" name="schemeid" id="schemeid" value="<?= $_REQUEST['scheme']; ?>" />
  <input type="hidden" name="finyr" id="finyr" value="<?= $_REQUEST['fin_year']; ?>" />
  <?PHP
  		  $rururb = $_REQUEST['rururb'];
		  $bcode = $_REQUEST['blk_popup'];
		  $pvcode = $_REQUEST['cmb_village'];
		  
		  $tptcode = $_REQUEST['cmb_twnpan'];
		  $muncode = $_REQUEST['cmb_muncp'];
		  $corpcode = $_REQUEST['cmb_corp'];
		  
		  $rurjoin.='';
		  $rurvar.='';
		  $rururbdatas='';
		  	
			if($rururb == 'R')
			{
			  
			  $rurvar.=',lbd.dname,lbd.bname,lbd.pvname,lbd.habitation_name';
			  $rurjoin.='left join
					(select * from viewlbdnames )lbd
				on lbd.dcode=wd.dcode and lbd.bcode=wd.bcode and lbd.pvcode=wd.pvcode and lbd.habitation_code =wd.hab_code';	
				
			  $sdwphyprog = '';
              if ($bcode > 0)
                   $sdwphyprog.=" and bcode=".$bcode;
             
			  if ($pvcode > 0)
                   $sdwphyprog.=" and pvcode=".$pvcode;
					
				
			  if ($sdwphyprog == '')
					$sdwphyprog.=" and bcode > 0 and pvcode > 0 and hab_code > 0";	
					
				$rururbdatas = 'dcode='.$district.$sdwphyprog;		  			
			}
		  	
			
			
			if($rururb == 'U')
			{
			  $tpalldatas = '';
              if ($tptcode > 0 )
			  {
                   $tpalldatas.=' and townpanchayat_code='.$tptcode;
			  }
              
			  if ($_REQUEST['all_tpts'] == 'on')
			  {
				   $tpalldatas.=' and townpanchayat_code > 0';
			  }
					
			  $mpalldatas = '';
              if ($muncode > 0 )
			  {
                   $mpalldatas.=' and municipality_code='.$muncode;
			  }
              
			  if ($_REQUEST['all_mptys'] == 'on')
			  {
				   $mpalldatas.=' and municipality_code > 0';
			  }
					
				$rururbdatas = 'dcode='.$district.$tpalldatas.$mpalldatas;		  			
			}

		  
		  $fin_year = $_REQUEST['fin_year'];
		  $scheme = $_REQUEST['scheme'];
		  $wrkdetqry="select wd.* $rurvar from
				(select * from view_workdetails where $rururbdatas and fin_year='$fin_year' and scheme_id=$scheme and (current_stage_of_work<>'11' and current_stage_of_work > 1))wd
				$rurjoin
				inner join
				(select scheme_grp_id,scheme_id from m_role_type_wise_scheme_link where role_id=$role_code and sch_role_link_y_n=1)bb
							 on wd.scheme_group_id=bb.scheme_grp_id and wd.scheme_id=bb.scheme_id"; 
		//echo $wrkdetqry; 
		$res_wrkdets=$obj->selfn($wrkdetqry,$db);
		$reccnt = count($res_wrkdets);
		$slno = 1;
		foreach($res_wrkdets as $selrw)
		{
			
  ?>
  <tr>
  	<input type="hidden" name="hid_asval<?php echo $slno; ?>" id="hid_asval<?php echo $slno; ?>" value="<?= $selrw['as_value']; ?>" />
    <td height="30" valign="middle"><?php echo $slno; ?></td>
    <td valign="middle" ><?php echo $selrw['work_id']; ?><input type="hidden" name="work_id" id="work_id<?php echo $slno; ?>" value="<?php echo $selrw['work_id']; ?>" /></td>
    <td align="left" valign="middle" ><?php echo $selrw['work_name']; ?></td>
    <td  height="30" align="left" valign="middle"><?php echo $selrw['bname'].'/'.$selrw['pvname'].'/'.$selrw['habitation_name']; ?></td>
    <td valign="middle"><?php if($selrw['townpanchayat_code'] != '') { echo $selrw['townpanchayat_name']; } if($selrw['municipality_code'] != '') { echo $selrw['municipality_name']; } if($selrw['corporation_code'] != '') { echo $selrw['corporation_name']; } ?></td>
    <td valign="middle"><?php echo $selrw['agency_group_name']; ?></td>
    <td valign="middle"><?php echo $selrw['agency_name']; ?></td>
    <td align="left" valign="middle"><?php echo $selrw['wrkgrpname']; ?></td>
    <td  height="30" align="left" valign="middle"><?php echo $selrw['worktypname']; ?></td>
    <td valign="middle" class="completed"><?php echo $selrw['as_value']; ?></td>
    <td valign="middle" ><?php echo $selrw['ts_value']; ?></td>
    <td valign="middle" class="takenup"><?php echo $selrw['amount_spent_sofar']; ?></td>
    <td align="left" valign="middle" nowrap="nowrap" ><?php echo $selrw['stage_name']; ?><input type="hidden" name="stage_code<?php echo $slno; ?>" id="stage_code<?php echo $slno; ?>" value="<?php echo $selrw['current_stage_of_work']; ?>" /></td>
    <td valign="middle" ><input type="radio" name="datarad" id="datarad<?php echo $slno; ?>" data-slno="<?php echo $slno; ?>" class="selwrkid" /></td>
  </tr>

  <?php $slno++; } ?>
  <?php if($reccnt == '0') { ?>
  <tr>
    <td height="30" colspan="14" align="center" valign="middle" style="font-family:Georgia, 'Times New Roman', Times, serif; font-size:12px; font-style:italic; color:#F00;">No Record Found</td>
   </tr>  
   <?php } ?>
</table>

<?php  }  ?>

<?php 
	  if($_REQUEST['txt_workid'] != '')
	  {
	  $workid = $_REQUEST['txt_workid'];
	  $wrkqry="select wd.*,lbd.dname,lbd.bname,
				lbd.pvname,lbd.habitation_name from
				(select * from view_workdetails where work_id=$workid and dcode=$district and (current_stage_of_work<>'11' and current_stage_of_work > 1))wd
				left join
				(select * from viewlbdnames )lbd
				on lbd.dcode=wd.dcode and lbd.bcode=wd.bcode and lbd.pvcode=wd.pvcode and lbd.habitation_code =wd.hab_code
				inner join
				(select scheme_grp_id,scheme_id from m_role_type_wise_scheme_link where role_id=$role_code and sch_role_link_y_n=1)bb
							 on wd.scheme_group_id=bb.scheme_grp_id and wd.scheme_id=bb.scheme_id"; 
		//echo $wrkqry; exit;
		$wrkrow=$obj->selonefn($wrkqry,$db);	
		if(count($wrkrow)!= 0)
		{
			$workid = $wrkrow['work_id'];
			$stageid = $wrkrow['current_stage_of_work'];
			$selphy="select count(1) as phycnt from t_scheme_work_physical_progress where work_id=$workid";
			//echo $selphy;
			$slphyrw=$obj->selonefn($selphy,$db);
			$phycnt = $slphyrw['phycnt'];
			if($phycnt >  0)
			{
			$sel="select * from t_scheme_work_financial_progress where work_id=$workid and (yn_work_completed='Y' or stage_code=11) ";
			//echo $sel;
			$slrw=$obj->selfn($sel,$db);
			$rowcnt = count($slrw);
			
			if($rowcnt == 0)
			{
				if($wrkrow['latest_as_amount']=='')
					$asvalue=$wrkrow['as_value'];
				else
					$asvalue=$wrkrow['latest_as_amount'];
					
				if($wrkrow['latest_ts_amount']=='')
					$tsvalue=$wrkrow['ts_value'];
				else
					$tsvalue=$wrkrow['latest_ts_amount'];
					
					//echo $asvalue; 
				
		?>
        
  <input type="hidden" name="hid_schgrpid" id="hid_schgrpid" value="<?= $wrkrow['scheme_group_id']; ?>" />
  <input type="hidden" name="hid_schid" id="hid_schid" value="<?= $wrkrow['scheme_id']; ?>" />
  <input type="hidden" name="hid_asval" id="hid_asval" value="<?= $asvalue; ?>" />
  
  <br />
  <table id="disptab" width="75%" border="1" align="center" style="border-collapse:collapse"  >
  
<tr>
<td>
<table width="100%" height="145" border="0" align="center" class="tablesorterform colorchform" style="border-collapse:collapse"  >
  <tr>
    <td height="19" colspan="4"  >
     <?php
	 if($wrkrow['rural_urban']=='U')
	 {
	  if($wrkrow['townpanchayat_code']!='')
	  {
	  ?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="50%" class="tlabel">Town Panchayat</td>          <td width="50%" class="tlabel">&nbsp;</td>
      </tr>
    </table>
     <?php
	  }
	  if( $wrkrow['municipality_code']!='')
	  {
	  ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="50%" class="tlabel">Muncipality</td>         <td width="50%" class="tlabel">&nbsp;</td>
        </tr>
      </table>
      <?php
	  }
	  if( $wrkrow['corporation_code']!='')
	  {
	  ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="50%" class="tlabel">Corporation</td>          <td width="50%" class="tlabel">&nbsp;</td>
        </tr>
      </table>
      <?php
	  } }
	 if($wrkrow['rural_urban']=='R')
	 {
	  if( $wrkrow['bcode']!='' &&  $wrkrow['pvcode']!='' &&  $wrkrow['hab_code']!='')
	  {
	  ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <th width="17%" class="tlabel">Block::</th>       <th width="22%" style="font-size:11px; font-style:italic;" ><?= $wrkrow['bname']; ?></th>
          <th width="8%" class="tlabel">Village::</th>     <th width="23%" style="font-size:11px; font-style:italic;" ><?= $wrkrow['pvname']; ?></th>
          <th width="12%" class="tlabel">Habitation::</th>  <th width="18%" style="font-size:11px; font-style:italic;" ><?= $wrkrow['habitation_name']; ?></th>
        </tr>
      </table>
      <?php
	  } }
	  ?>
      </td>
    </tr>
  <tr>
    <td height="18" colspan="4"  class="tlabel"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="47%" class="tlabel">Work Name</td>
        <td width="53%" class="tlabel"><?php echo $wrkrow['work_name']; ?></td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td width="151" height="18" align="left"  class="tlabel">Scheme::</td>
    <td width="281" align="left"  class="tlabel"><?php echo $wrkrow['scheme_name']; ?></td>
    <td width="276" align="left" class="tlabel">Financial Year::</td>
    <td width="211" align="left" class="tlabel"><?php echo $wrkrow['fin_year']; ?><input type="hidden" name="finyr" id="finyr" value="<?php echo $wrkrow['fin_year']; ?>"  /></td>
  </tr>
  <tr>
    <td height="18" align="left"  class="tlabel">Agency Group::</td>
    <td height="18" align="left"  class="tlabel"><?php echo $wrkrow['agency_group_name']; ?></td>
    <td align="left" class="tlabel">Agency Name::</td>
    <td align="left" class="tlabel"><?php echo $wrkrow['agency_name']; ?></td>
  </tr>
  <tr>
    <td height="19" align="left"  class="tlabel">Work Type::</td>
    <td height="19" align="left"  class="tlabel"><?php echo $wrkrow['worktypname']; ?></td>
    <td align="left" class="tlabel">Present Stage::</td>
    <td align="left" class="tlabel"><?php echo $wrkrow['stage_name']; ?></td>
    <input type="hidden" name="stage_id" id="stage_id" value="<?php echo $wrkrow['current_stage_of_work']; ?>" />
  </tr>
  <tr>
    <td height="19" align="left"  class="tlabel">AS Amount</td>
    <td height="19" align="left"  class="tlabel"><?= $asvalue; ?></td>
    <td align="left" class="tlabel">TS Amount</td>
    <td align="left" class="tlabel"><?= $tsvalue; ?></td>
  </tr>
  
  </table>
											<!-- Start Financial Progress - Entry Section Below -->
  <table width="54%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="30" align="center" valign="middle" class="headingtable" style="padding-left:10px;">Part Payment Details</td>
    </tr>
  </table>
  
  <table width="100%" height="56" border="0" align="center" class="tablesorterform colorchform" style="border-collapse:collapse"  >
    <tr align="center" >
      <th width="132">Installment No.</th>
      <th width="112">Voucher No.</th>
      <th width="117">Date of Payment</th>
      <th width="151">Bill Amount</th>
    </tr>
    <?php  
	$sel_se="select * from t_scheme_work_financial_progress where work_id=$workid";
	$selse=$obj->selfn($sel_se,$db);
	foreach($selse as $serow)
	{
	?>
    <tr align="center" >
      <td height="18"  class="tlabel"><?= $serow['instalment_no']; ?></td>
      <td  class="tlabel"><?= $serow['voucher_no']; ?></td>
      <td  class="tlabel"><?= $serow['paid_date']; ?></td>
      <td  class="tlabel"><?= $serow['amount_paid']; ?></td>
    </tr>
    
    <?php
		$totbillamt+=$serow['amount_paid'];
	}
	?>
    <tr align="center">
      <th height="18" colspan="3" align="right" >Total Bill Amount</th>
      <th><?= $totbillamt?$totbillamt:0; ?></th>
    </tr>
      
  </table>
 
 	<?php
				//Check Photo Works
		$ph_qry="select work_id,fin_year,stage_id,file_url,upd_date from t_scheme_work_physical_progress where work_id=$workid and stage_id=$stageid and file_url is not null and file_url not in('null') and file_url<>''";
		$res_phqry = $obj->selonefn($ph_qry,$db);
	?>
 	<input type="hidden" name="phrowcnt" id="phrowcnt" value="<?php echo $phrowcnt; ?>" />
  <table width="54%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <th height="30" align="center" valign="middle" class="headingtable" style="padding-left:10px;">Current Payment Entry</th>
    </tr>
  </table>

  <table width="100%" height="169" border="0" align="center" class="tablesorterform colorchform" style="border-collapse:collapse"  >
    <tr>
      <td style="vertical-align:top;">
        <table width="100%" border="1" class="tablesorterform colorchform" style="border-collapse:collapse" >
          <tr>
            <th height="23" align="left" valign="middle"  class="tlabel">Description</th>
            <th align="center" valign="middle" class="tlabel">Values</th>
            </tr>
          <tr>
            <td width="205" height="23" align="left"  class="tlabel">Bill Amount</td>
            <td width="313" align="left" valign="middle">
              <input type="text" name="txt_amt" id="txt_amt" class="textbox_nw_bg" onkeypress="return numbersonly(event,this)" onblur="fnchkamt()" />
              <input type="hidden" name="hid_bilamt" id="hid_bilamt" value="<?php echo $totbillamt?$totbillamt:0; ?>" />
              </td>
            </tr>
            <tr>
              <td height="26" align="left"  class="tlabel">Voucher Number </td>
              <td align="left" valign="middle"><input type="text" name="txt_vocno" id="txt_vocno" class="textbox_nw_bg" onkeypress="return numbersonly(event,this)" /></td>
            </tr>
            <tr>
              <td height="26" align="left"  class="tlabel">Whether If the above Voucher is Final?</td>
              <td align="left" valign="middle"><span id="fin_vr_id">
                <input type="radio" name="fin_vr" id="fin_vr_y" value="1" /><span class="tlabel">Yes</span></span>
                <input type="radio" name="fin_vr" id="fin_vr_n" value="0" /> <span class="tlabel">No</span></td>
            </tr>
            <?php /*?><tr id="ddamtrow"  style="display:none;">
              <td height="26"  class="tlabel">Deduction Amount</td>
              <td><input type="text" class="textbox_nw_bg" name="tax_amt" id="tax_amt" onkeypress="return numbersonly(event,this)" /><span class="tlabel">If any Tax etc.,			              </span></td>
            </tr><?php */?>
            <tr id="savrow" style="display:none;">
              <td height="26" align="left"  class="tlabel">Savings Amount</td>
              <td align="left" valign="middle"><input type="text" class="textbox_nw_bg" name="sav_amount" id="sav_amount" readonly="readonly"  /></td>
            </tr>
            <tr>
              <td height="24" align="left"  class="tlabel">Date of Payment</td>
              <td align="left" valign="middle"><input type="text" name="txt_datepay" id="txt_datepay" class="textbox_nw_bg"  /></td>
            </tr>
            <tr>
              <td height="30" align="left"  class="tlabel">Whether Work Completed ?</td>
              <td align="left" valign="middle"><span id="wrk_comp_id">
                <input type="radio" name="rad_wc" id="rad_wc_y" value="1" disabled="disabled" /><span class="tlabel">Yes</span></span>
                <input type="radio" name="rad_wc" id="rad_wc_n" value="0" /> <span class="tlabel">No</span>
              </td>
            </tr>
            <?php
            if($wrkrow['scheme_id'] == 727 || $wrkrow['scheme_id'] == 728 || $wrkrow['scheme_id'] == 729){
            ?>
            <tr>
              <td height="30" align="left"  class="tlabel">Is this Dovetail Payment?</td>
              <td align="left" valign="middle"><span id="is_dovetail_payment">
                <input type="radio" name="is_dovetail_payment" id="is_dovetail_payment_t" value="true" class="is_dovetail_payment"/><span class="tlabel">Yes</span></span>
                <input type="radio" name="is_dovetail_payment" id="is_dovetail_payment_f" value="false" class="is_dovetail_payment"/> <span class="tlabel">No</span>
              </td>
            </tr>
            <?php
            }
            ?>
            <tr>            
          </table>    
        </td>
      <td align="center"><?php
               	 $finyear = $res_phqry['fin_year'];
                    
				$sel_dpabbr = "select dabbr from m_district where dcode=$district";
                $res_dpabbr = $obj->selonefn($sel_dpabbr,$db);
                
                $dir = strtolower($res_dpabbr['dabbr']);
                $dirnam = $dir.'/'.$finyear.'/'; 
                $imgsrc="../files/".$dirnam."/".$res_phqry['file_url'];
                 $imgsrcold="../files/scheme/".$res_phqry['file_url'];
				
				if ($res_phqry['file_url'] != '') {
				 if (file_exists($imgsrc)) { ?>
                        <div class="zoom_img" style="width:200px; border:1px solid #333; overflow:hidden;" align="center">
                            <img align="middle"  class="magnify" border="0" src="<?php echo $imgsrc;  ?>" style="cursor: url("magnify.cur"), -moz-zoom-in;" width="140" height="150"  /><br />
                            <span style="font-family:Verdana, Geneva, sans-serif; font-size:9px"> Upload Date:<br />
<?php echo $res_phqry['upd_date']; ?> </span>
                        </div>
                <?php } else { ?>        
                         <div class="zoom_img" style="width:200px; border:1px solid #333; overflow:hidden;" align="center">
                            <img align="middle"  class="magnify" border="0" src="<?php echo $imgsrcold;  ?>" style="cursor: url("magnify.cur"), -moz-zoom-in;" width="140" height="150"  /><br />
                            <span style="font-family:Verdana, Geneva, sans-serif; font-size:9px">Upload Date:<br />
<?php echo $res_phqry['upd_date']; ?></span>
                </div>
	<?php } } else { echo 'Photo Not Found'; } ?>
       </td>
    </tr>
    
    <tr>
      <th height="30" colspan="2" align="center">
        <input name="save" type="button" class="button" value="Save" onclick="saveform()" />
      </th>
    </tr>
  </table>
  </td></tr></table>
    <? 
   }
   else
   {
   ?>

   
   <div align="center"><p style="color:#C00;font-size:15px; font-style:italic; font-weight:bold;">Work Completed </p></div>

   <table width="62%" height="102" border="0" align="center" class="tablesorterform colorchform" style="border-collapse:collapse">
     <tr>
       <td width="98" height="18"  class="tlabel">Scheme</td>
       <td width="22"  class="tlabel">::</td>
       <td width="211"  class="tlabel"><?php echo $wrkrow['scheme_name']; ?></td>
       <td width="103" class="tlabel">Work Name</td>
       <td width="22" class="tlabel">::</td>
       <td width="217" class="tlabel"><?php echo $wrkrow['work_name']; ?></td>
     </tr>
     <tr>
       <td height="18"  class="tlabel">Agency Group</td>
       <td  class="tlabel">::</td>
       <td height="18"  class="tlabel"><?php echo $wrkrow['agency_group_name']; ?></td>
       <td class="tlabel">Agency Name</td>
       <td class="tlabel">::</td>
       <td class="tlabel"><?php echo $wrkrow['agency_name']; ?></td>
     </tr>
     <tr>
       <td height="19"  class="tlabel">Work Type</td>
       <td  class="tlabel">::</td>
       <td height="19"  class="tlabel"><?php echo $wrkrow['worktypname']; ?></td>
       <td class="tlabel">Financial Year</td>
       <td class="tlabel">::</td>
       <td class="tlabel"><?php echo $wrkrow['fin_year']; ?></td>
     </tr>
        <tr>
        <td height="19"  class="tlabel">AS Amount</td>
        <td  class="tlabel">::</td>
        <td height="19"  class="tlabel"><?= $wrkrow['as_value']; ?></td>
        <td class="tlabel">TS Amount</td>
        <td class="tlabel">::</td>
        <td class="tlabel"><?= $wrkrow['ts_value']; ?></td>
      </tr>
   </table>
  
   <?
   }
			}
			else
			{
			?>
            <table align="center"><tr><td style="color:#C00; font-size:10px"><blink>No physical progress entry found for this work</blink></td></tr></table>
            <?	
			}
	}
   else
   {
   ?>
<table align="center" border="0">
               <tr>
               		<td style="font-family:Verdana, Geneva, sans-serif; font-size:16px; font-style:oblique; font-weight:bold; color:#F00;"><blink>You are not authorized to Enter Voucher for this Work</blink></td>
               </tr>
           </table>
  
  <?php
  		}
	}
}
  ?>   
<div id="dialog-finentry" title="Fin Entry Screen"></div>
</form>
</td>
</tr>
</body>
<script type="text/javascript">
$(document).ready(function(){

	$("#txt_datepay").datepicker({
        showOn: 'focus',
        changeMonth: true,
        changeYear: true,
        selectWeek: true,
        inline: true,
        maxDate: new Date(),
        dateFormat: "dd/mm/yy"
   	});
	
	$(".fillwrkid").click(function() {
		$("#txt_workid").val("");
		$(".shwid").show();
		$("#disptab").hide();;
		$(".hidwid").hide();
		$(".rurid").hide();
		$(".urbid").hide();		
		
	});		
	
	
	$("#rururb").change(function() {
		var ru = $("#rururb").val();
		if (ru == 'U')
		{
			$("#blk_popup").val("");
			$("#cmb_village").val("");
			$(".rurid").hide();
			$(".urbid").show();
			$("#tpid").hide();
			$("#munid").hide();
			$("#corpid").hide();			
		} 
		else if(ru == 'R') 
		{ 
		 	$(".rurid").show();
			$(".urbid").hide();
			$("#tpid").hide();
			$("#munid").hide();
			$("#corpid").hide();
			//$('#all_blks').attr('checked',true);	
			//$('#blk_popup').attr("disabled", true);
			//$('#all_villgs').attr('checked',true);	
			//$('#cmb_village').attr("disabled", true);
		}
		else{
			$(".rurid").hide();
			$(".urbid").hide();	
		}
	});	
	
	$("#cmb_urban").change(function() {
		var urblb = $("#cmb_urban").val();
		if (urblb == 1) {
			$("#tpid").show();
			$("#munid").hide();
			$("#corpid").hide();
			
			$("#cmb_muncp").val("");
			$("#all_mptys").removeAttr('checked');	
			
			$("#cmb_corp").val("");
			$("#all_corp").removeAttr('checked');	
			
			
		}
		else if (urblb == 2) {
			$("#tpid").hide();
			$("#munid").show();
			$("#corpid").hide();
			
			$("#cmb_twnpan").val("");
			$("#all_tpts").removeAttr('checked');	
			
			$("#cmb_corp").val("");
			$("#all_corp").removeAttr('checked');	
				
		}
		else if (urblb == 3) {
			$("#tpid").hide();
			$("#munid").hide();
			$("#corpid").show();	
		}
		else{
			$("#tpid").hide();
			$("#munid").hide();
			$("#corpid").hide();	

		}
	});
	

	$('#dialog-finentry').dialog(
	{
            autoOpen	:	false, 
            modal		:	true, 
            draggable	:	false, 
            resizable	:	false, 
            width		:	950
    });
		
    $(".selwrkid").click(function() { 
            var slno = $(this).data("slno");
			var work_id = $("#work_id"+slno).val();
			var schmid = $("#schemeid").val();
			var finyr = $("#finyr").val();
			var asval = $("#hid_asval"+slno).val();
			var stgid = $("#stage_code"+slno).val();
            $.ajax({
                url: "scheme_distadm_fin_progress_entry_ajax.php",
                data: {"work_id":work_id,"asval":asval,"stgid":stgid,"schmid":schmid,"finyr":finyr},
                success: function (data){
                    $('#dialog-finentry').html(data).dialog('open');
                },
                dataType: 'html'
            });
        });
		
	$(".ui-icon-closethick").click(function(){
                $(".selwrkid").attr('checked',false);
    });			
		
//AJAX Insert
	$("#savebtn").live('click',function(){
         	 try {
                var schemcode = $("#schemcode").val(); 
                if($("#txtamtajx").val().length === 0){
                    throw{msg:"Enter Payable Amount",foc:"#txtamtajx"}
                }
				
                if($("#txt_vocnoajx").val().length === 0){
                    throw{msg:"Enter Voucher Number",foc:"#txt_vocnoajx"}
                }
               if ((!$("#ajx_fin_yr_y").is(':checked')) && (!$("#ajx_fin_yr_n").is(':checked'))) {
                    throw{msg:"Select If the above Voucher is Final Yes/No?",foc:"#ajx_fin_yr_n"}
                }
				
                if($("#txt_datepayajx").val().length === 0){
                    throw{msg:"Select Paid Date",foc:"#txt_datepayajx"}
                }

                if(schemcode == 727 || schemcode == 728 || schemcode == 729){
                  if ((!$("#is_dovetail_payment_t").is(':checked')) && (!$("#is_dovetail_payment_f").is(':checked'))) {
                    throw{msg:"Select Is this Dovetail Payment?",foc:"#is_dovetail_payment_t"}
                  }
                }
				
				
				var mainworkid = $("#mainworkid").val();
				var finyear = $("#finyear").val(); 
				var stagecode = $("#stageid").val(); 
				var asamount = $("#hidasamt").val();
				var hidbillamt = $("#hid_bilamtajx").val();			 
				var paidamt = $("#txtamtajx").val(); 
				var savingsamt = $("#sav_amountajx").val();
				var vouchrno = $("#txt_vocnoajx").val();  
				var paid_date = $("#txt_datepayajx").val();  
				var workcomp_yn = $(".workcmp:checked").val(); 
        var is_dovetail_payment = $(".is_dovetail_payment:checked").val();		
				 
                $.ajax({
                    url: "scheme_dist_work_fin_progress_insert_ajax.php",
                    data: {"mainworkid":mainworkid,"schemcode":schemcode,"finyear":finyear,"stagecode":stagecode,"asamt":asamount,"hidbillamt":hidbillamt,"paidamt":paidamt,"savingsamt":savingsamt,"vouchrno":vouchrno,"paid_date":paid_date,"workcomp_yn":workcomp_yn,"is_dovetail_payment":is_dovetail_payment,"cmd":1}, 
                    success: function (data){
                      // alert(data);							
                        //var res = data.split('%');                                                       
                        if(data != '')
                        { 
							alert(data);
                            $('#dialog-finentry').dialog('close');
							//location.reload();
                        }  
                    },
                    dataType: 'html'
                });		
  				return true; 
            } catch(e){
                alert(e.msg);
                $(e.foc).focus();
                return false;
            }
	
        });	
	
	
	$("#blk_popup").change(function() {
				var distid = $("#distid").val();	
				var blkid = $("#blk_popup").val();	
				if(blkid != '')
				{			
					$.ajax({
						url: "../drop_down_forms/block_village_ddown.php",
						 data: {"distid":distid,"blkid":blkid,"cmd":1},
						success: function (data){
							//alert(data);
							if(data != '')
							{									
							   $('#cmb_village').html(data).text;		
							}
						},
						dataType: 'html'
					});
					return true;
				}
				else
				{
					alert("Block ID not set");
					return false;
				}
			});	  
	

 		$("#fin_vr_y").change(function() {

			if($("#fin_vr_y").val()==1) {							
				var savamt = $("#sav_amount").val(); 
				var asamt = $("#hid_asval").val(); //alert(asamt); 
				var paidamt = $("#txt_amt").val(); //alert(paidamt); 
				if (paidamt != '') {
					var paidamt = $("#txt_amt").val();
				}
				else {
					var paidamt = 0;	
				}
				var billamt = $("#hid_bilamt").val(); //alert(billamt); 
				var totbillamt = parseFloat(paidamt) + parseFloat(billamt);
				//alert(totbillamt);
				
					savamt = parseFloat(asamt) - parseFloat(totbillamt); 				
					$("#savrow").show();
					$("#sav_amount").val(savamt);
					$( "#rad_wc_y").removeAttr("disabled");
					$("#rad_wc_y").attr("checked", "true");
					$("#rad_wc_n").attr("disabled", "disabled");
			} 
		});
		
		$("#fin_vr_n").change(function() {
			if($("#fin_vr_n").val()==0) { 
				$("#savrow").hide(); 
				$("#rad_wc_y").attr("disabled", "disabled"); 
				$( "#rad_wc_n").removeAttr("disabled"); 
				$("#rad_wc_n").attr("checked", "true");  }
		});
		
		$(".photochk").click(function() {
			var phrowcnt = $("#phrowcnt").val();
			//alert(phrowcnt);
			if (phrowcnt == 0) {
				alert('Photo not Uploaded this work');
				return false;
			}
		});	
 
  });
</script>
</html>