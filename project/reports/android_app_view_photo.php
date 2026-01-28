<?php 
include('connection_photo_DB.php');
include('class_data_access.php');
$state=$_SESSION["state_code"];
$today = date("F j, Y");
$upd_date=date("Y-m-d");
$username=$_SESSION["username"];
  $workid=$_GET['wrkid'];
$obj=new dataaccess(); 
	$queval = "select work_name from t_works where work_id='$workid'";
//echo $ph_query; exit;
$querow=$obj->selonefn($queval,$db); 
  $wrkname = "select a.dcode,a.bcode,a.pvcode,a.work_id,a.work_name,b.file_url,b.chainage_id,b.cd_type_flag,b.cd_chainage,b.upd_date,c.work_stage_name,e.cd_stagename,a.fin_year,md.dabbr, of_latitude, of_longitude from
(select dcode,bcode,pvcode,work_id,work_name,work_group_id,mwork_id,current_stage_of_work,fin_year from t_works where work_id='$workid' )a
INNER JOIN 
(select work_phy_seq_id,file_url, work_id, chainage_id,qlty_parameter, qlty_parameter_value, cd_type_flag,cd_chainage,photo_captured_latitude, photo_captured_longitude, upd_date, remarks, of_latitude, of_longitude,stage_id from t_scheme_work_physical_progress where file_url is NOT NULL and photo_captured_latitude is NOT NULL )b
ON a.work_id=b.work_id 
left JOIN 
(select DISTINCT work_group_code,work_code,work_stage_code,work_stage_name from m_work_stage_link)c 
on a.work_group_id=c.work_group_code and a.mwork_id=c.work_code and b.stage_id=c.work_stage_code 
left JOIN 
(select DISTINCT work_group_code,work_type_code,work_stage_code,work_stage_name as cd_stagename from m_cdwork_stage_link)e 
on a.work_group_id=e.work_group_code and a.mwork_id=e.work_type_code and b.stage_id=e.work_stage_code
left join m_district as md on a.dcode=md.dcode

 ORDER BY a.work_id,b.upd_date DESC";
//echo $wrkname; exit;
$res_wrknam=$obj->selfn($wrkname,$db); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="includes/style.css" rel="stylesheet" />
<title>Android Application - Demo</title>
<script type="text/javascript" src="includes/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="includes/jquery-ui-1.8.6.custom.min.js"></script>
<script type="text/javascript" src="includes/jquery.magnifier.js"></script>
<style>
.zoom{
  transition: 0.5s;
}
.zoom:hover{
  transform: scale(1.7);
  box-shadow: 0px 5px 25px 0px rgb(0 0 0 / 20%);
}
</style>
</head>

<body>
<form>
<?php
if($workid!='')
{
?>
<table width="700" border="1" cellspacing="0" cellpadding="0" style=" border-collapse:collapse; border-color:#06F;" align="center">
  <tr>
    <td width="91" height="35" scope="col"  style=" bold font:12px Verdana, Geneva, sans-serif"><strong>Work Name :</strong><strong><?php echo $querow['work_name']; ?></strong></td>
  </tr>
  </table>
  <div style="1000px;">
        <div style="float:left; width:1000px; border:0px solid #333;">
        <?php
foreach($res_wrknam as $row) {
	$update = explode("-",$row['upd_date']);
//print_r($to_date1);
$up_date =  ($update[2])."-".($update[1])."-". ($update[0]);
	?>
	 <div style="float:left; border:0px solid red; margin:5px">
 <table width="315" border="1" cellspacing="0" cellpadding="0" style=" border-collapse:collapse; border-color:#06F;" align="center">
  <tr>
   <td scope="col" align="center" colspan="3"><img  class="zoom" border="0" width="300" height="250" src="https://tnrd.tn.gov.in/project/files/<?php echo strtolower($row['dabbr']);  ?>/<?php echo $row['fin_year'];  ?>/<?php echo $row['file_url'];  ?>"   /></br>LATITUDE :<?php echo $row['of_latitude']; ?></br>LONGITUDE :<?php echo $row['of_longitude']; ?></td>
  </tr>
   <tr>
    <td height="35" style="font: 12px Verdana, Geneva, sans-serif"><strong>Stage Name&nbsp;&nbsp;:<?php echo $row['work_stage_name']?$row['work_stage_name']:$row['cd_stagename']; ?></strong></td>
  </tr>
   <tr>
    <td height="35" style="font: 12px Verdana, Geneva, sans-serif"><strong>Chainage&nbsp;&nbsp;:<?php echo $row['chainage_id']?$row['chainage_id']:$row['cd_chainage']; ?>    <?php if($row['cd_type_flag']=='C') echo '||  CD Work'; if($row['cd_type_flag']=='P') echo '||  Prot. Work'; ?></strong></td>
   </tr>
   <tr>
    <td height="35"  style="font: 12px Verdana, Geneva, sans-serif" ><strong>Date &nbsp;&nbsp;:<?php echo $up_date; ?></strong></td>
	</tr>
   </table>
   </div>
<?php 
	}
	?>
    </div></div>
    <?php
}  
?>    

</form> 
</body>
</html>