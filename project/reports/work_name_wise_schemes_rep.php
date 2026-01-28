<?php
require_once __DIR__ . '/../config/config.php';




class road_work_type_of_improvement_link  extends ConfigClass
{

	public $page_token = "office_name_entry_token";
    public function __construct()
    {
        if (! isset($this->db)) {
            
            
            
            
        }
    }

    public function main_content($post_data_array = array())
    {
        $site_data = $this->siteData();

		if (! isset($post_data_array["mode_name"]))
		{
			$post_data_array["mode_name"] = "Save";
			$post_data_array["mode_class"] = "btn-success";
		}


        ob_start();

?>
<input type="hidden" id="page_lable_id" name="page_lable_id" value="59" />
<?php		

        // #############

        // PAGE CONTENT START

        // #############

        ?>
<script type="text/javascript">
$(document).ready(function() {

    $('.user_enter_date').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'dd-mm-yyyy',
        maxDate: new Date()
    });

    $(".swm_select_all").click(function() {

        var check_count = $('input.checkboxall').length;



        if (this.checked) {
            $('.checkboxall').each(function() {
                $(".checkboxall").prop('checked', true);
            })
        } else {
            $('.checkboxall').each(function() {
                $(".checkboxall").prop('checked', false);
            })
        }





    });






    $("#btn_show").on('click', function() {

        var Current_Field_id = $(this).attr('id');
        $('#' + Current_Field_id).hide();
        try {


            // if ($("#fin_year").val().length == '') {
            //     throw {
            //         msg: "Select Fin Year",
            //         foc: "#fin_year"
            //     }
            // }
            // if ($("#district").val().length == '') {
            //     throw {
            //         msg: "Select District",
            //         foc: "#district"
            //     }
            // }
            // if ($("#work_group_id").val().length == '') {
            //     throw {
            //         msg: "Select Work Group",
            //         foc: "#work_group_id"
            //     }
            // }
            // if ($("#work_name").val().length == '') {
            //     throw {
            //         msg: "Select Work Name",
            //         foc: "#work_name"
            //     }
            // }


            return true;
        } catch (e) {
            alert(e.msg);
            $('#' + Current_Field_id).show();
            $(e.foc).focus();
            return false;
        }

    });






    $(document).on('change', '#work_group_id', function() {

        if ($('#work_group_id').val() != '') {
            var work_group_id = $('#work_group_id').val();


            $.ajax({
                url: "work_name_wise_schemes_rep.php",
                type: "post",
                data: {
                    "work_group_id": btoa(work_group_id),
                    "cmd": btoa(3)
                },

                success: function(data) {

                    if (data != '') {
                        $('#work_name').html(data);
                    }
                },
                dataType: 'html'
            });
            return true;
        } else {
            alert('Select Scheme Name');
            $('#work_name').html('<option value="">Select Work Name</option>');
            return true;
        }

    });


});
</script>
<style>
.tndtp_form_table {
    font-size: 15px;
    font-weight: bold;
    width: 100%;
}

.tndtp_form_table thead {
    padding: 3px
}

.tndtp_form_report_table {
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

.newhead {
    background: linear-gradient(to right, #494889, #3B3A7C, #494889);
    color: white;
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

.card {
    padding: 20px;
    margin: 20px;
    border-radius: 7px;
    box-shadow: 3px 3px 10px rgb(0 0 0 / 40%) inset;
    background: #fff;
}
</style>

<form action="" method="post" class="" enctype="multipart/form-data" autocomplete="off">
    <input class="form-control form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>"
        name="<?php echo htmlentities($this->page_token); ?>"
        value="<?php echo htmlentities($this->token($this->page_token)); ?>">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <?php
                    if (isset($post_data_array["STATUS"])) 
                    {
                        echo $this->ShowMessage($post_data_array["STATUS"], $post_data_array["MESSAGE"]);
                    }
                    ?>

                <div class="container">
                    <table class="table-bordered tndtp_form_table">
                        <thead class="newhead">
                            <tr>
                                <th colspan="2" class="text-center">Work Group Wise Physical & Financial Progress Report<button type="button" class="schemebuton float-end" onClick="location.href = '<?php echo htmlentities($site_data->website_url); ?>project/home.php?id=<?php echo htmlentities(base64_encode(4));?>';"><i class="fa fa-arrow-circle-left"></i> Back To Menu</button></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    <span DisplayLabelID="121">Work Group</span>
                                </td>
                                <td>
                                

                                <select id="work_group_id" name="work_group_id"
                                        class="form-control form-control-sm w-50">
                                        <option value="" DisplayLabelID="255">Choose Work Name</option>
                                        <?php
$sel_street_details="select wrkgrp_id,wrkgrpname_en from master.m_workgroup where  del_flag is null";

$sel_street_details_res=$this->prepare($sel_street_details,array(),2);

foreach($sel_street_details_res as $sel_street_details_key=>$sel_street_details_row)
{
?>
                                        <option
                                            value="<?php echo htmlentities($sel_street_details_row['wrkgrp_id']); ?>">
                                            <?php echo htmlentities($sel_street_details_row['wrkgrpname_en']); ?>
                                        </option>
                                        <?php
}

?>                                  </select>
                                    <script type="text/javascript">
                                    document.getElementById('work_group_id').value =
                                        '<?php echo htmlentities(isset($_POST['work_group_id'])?$_POST['work_group_id']:''); ?>';
                                    </script>
                                </td>
                            </tr>


                            <tr>
                                <td>
                                    <span DisplayLabelID="121">Work Name</span>
                                    
                                </td>
                                
                                <td><select id="work_name" name="work_name" class="form-control form-control-sm w-50">
                                        <option value="" DisplayLabelID="255">Choose Work Name</option>
                                        <?php
if(isset($_POST['work_group_id']) && $_POST['work_group_id']!='')
{

      $work_group_id=($_POST['work_group_id']);
            


		$sel_role_qry="select b.work_id,c.work_name_en from 
	(select work_group_id,work_id from master.m_work_type where  del_flag is null and work_group_id=:work_group_id)b
    left join
    (select work_type_id,work_name_en from master.m_work_type_name where  del_flag is null)c
	on b.work_id=c.work_type_id"; 
		$sel_street_details_res=$this->prepare($sel_role_qry,array(":work_group_id"=>$work_group_id),2);

foreach($sel_street_details_res as $sel_street_details_key=>$sel_street_details_row)
{
?>
                                        <option value="<?php echo htmlentities($sel_street_details_row['work_id']); ?>">
                                            <?php echo htmlentities($sel_street_details_row['work_name_en']); ?>
                                        </option>
                                        <?php
}
}
?>
                                    </select>
                                    <input type="checkbox" id="all_work_group" name="all_work_group" value="1" onclick="($(this).prop('checked')==true)?$('#work_name').attr('disabled','disabled'):$('#work_name').removeAttr('disabled');$('#work_name').val('');" <?php echo htmlentities(isset($_POST['all_work_group'])?'checked':''); ?> class="ml-1" /> All Work Name
                                    <script type="text/javascript">
                                    document.getElementById('work_name').value =
                                        '<?php echo htmlentities(isset($_POST['work_name'])?$_POST['work_name']:''); ?>';
                                    </script>
                                    
                                </td>
                                
                            </tr>

                            <tr>
                           
                                <td width="118" scope="col" class="w-50">
                                    <span DisplayLabelID="435"> Financial Year</span>
                                </td>
                                <td width="144" scope="col"><select id="fin_year" name="fin_year"
                                        class="form-control w-50 form-control-sm">
                                        <option value="" DisplayLabelID="255">Select Financial Year</option>
                                        <?php
$sel_fin_yearid="SELECT fin_yearid,fin_year FROM master.m_fin_year order by fin_yearid desc";
$sel_fin_yearid_res=$this->prepare($sel_fin_yearid,array(),2);

foreach($sel_fin_yearid_res as $sel_fin_yearid_res_key=>$sel_fin_yearid_res_row)
{								
?>
                                        <option
                                            value="<?php echo htmlentities($sel_fin_yearid_res_row['fin_year']); ?>">
                                            <?php echo htmlentities($sel_fin_yearid_res_row['fin_year']); ?></option>
                                        <?php
}
?>
                                    </select>
                                    <input type="checkbox" id="all_fin_year" name="all_fin_year" value="2" onclick="($(this).prop('checked')==true)?$('#fin_year').attr('disabled','disabled'):$('#fin_year').removeAttr('disabled');$('#fin_year').val('');" <?php echo htmlentities(isset($_POST['all_fin_year'])?'checked':''); ?> class="ml-1" /> All Financial Year
                                    <script type="text/javascript">
                                    document.getElementById('fin_year').value =
                                        '<?php echo htmlentities(isset($_POST['fin_year'])?$_POST['fin_year']:''); ?>';
                                    </script>
                                </td>
                            </tr>
                            <tr>
                           
                                <td width="118" scope="col" class="w-50">District Name</td>
                                <td scope="col">
                                
                            
                                    <select id="district" name="district" class="form-control w-50 form-control-sm">
                                        <option value="">Select District</option>
                                        <?php
                                $sel_agency_group="SELECT dcode,district_name_en FROM master.m_district ";
                                    $sel_agency_group_res=$this->prepare($sel_agency_group,array(),2);
                                    
                                    foreach($sel_agency_group_res as $sel_agency_group_key=>$sel_agency_group_row)
                                    {
                                ?>
                                        <option value="<?php echo $sel_agency_group_row['dcode']; ?>">
                                            <?php echo $sel_agency_group_row['district_name_en']; ?></option>
                                        <?php
                                }
                                ?>
                                    </select>
                                    <input type="checkbox" id="all_district" name="all_district" value="3" onclick="($(this).prop('checked')==true)?$('#district').attr('disabled','disabled'):$('#district').removeAttr('disabled');$('#district').val('');" <?php echo htmlentities(isset($_POST['all_district'])?'checked':''); ?> class="ml-1" /> All District
                                    <script>
                                    document.getElementById('district').value =
                                        '<?php echo htmlentities(isset($_POST['district'])?$_POST['district']:''); ?>';
                                    </script>
                                 
                                </td>
                            </tr>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" align="center">
                                    <input type="submit" id="btn_show" name="btn_show"
                                        class="btn btn-primary btn-sm text-white" value="View">
                                    &nbsp;
                                    <a class="btn btn-secondary btn-sm" href="road_work_type_of_improvement_link.php"><i
                                            class="fa fa-eraser pe-1 me-2"></i>Clear</a>
                                </td>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>






            <?php
  if((isset($_POST['btn_show']) && $_POST['btn_show']!='') || (isset($post_data_array["STATUS"]) && $post_data_array["STATUS"]=="SUCCESS" ))
  { 

  ?>
            <div class="container">
                <div class="cards">
                    <div class="card-body">

                        <table class="table-bordered tndtp_form_report_table">
                            <thead class="newhead">
                                <tr>





                                    <th scope="col" class="text-center"><span DisplayLabelID="174">Sl.No</span></th>
                                    <th scope="col" class="text-center"><span DisplayLabelID="436">Scheme Group
                                            Name</span></th>

                                    <td width="9%" align="center" valign="middle" nowrap="nowrap"><strong>Taken
                                            Up</strong></td>
                                    <td width="11%" align="center" valign="middle" nowrap="nowrap">
                                        <strong>Completed</strong>
                                    </td>
                                    <td width="9%" align="center" valign="middle" nowrap="nowrap">
                                        <strong>Balance</strong>
                                    </td>
                                    <td width="12%" align="center" valign="middle" nowrap="nowrap"><strong>% of
                                            Comp.</strong></td>
                                    <td width="11%" align="center" valign="middle" nowrap="nowrap"><strong>AS
                                            Amount</strong></td>
                                    <td width="9%" align="center" valign="middle" nowrap="nowrap">
                                        <strong>Expen.</strong>
                                    </td>
                                    <td width="9%" align="center" valign="middle" nowrap="nowrap">
                                        <strong>Balance</strong>
                                    </td>
                                    <td width="12%" align="center" valign="middle" nowrap="nowrap"><strong>% of
                                            Expen.</strong></td>


                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
		
		
 
         if(isset($_POST['work_name']) && $_POST['work_name']!='')
         {
             $work_name=$_POST['work_name'];
             $work_name_Validation = $this->Field_Validation(
             array
             (
             'Field_Type'=>'number',
             'Field_Value'=>$work_name,
             'Field_Name'=>'work_name',
             'Field_Label_Name'=>'work_name'
             )
             );
             
             if ($work_name_Validation['Status'] == "Error") {
                 $this->main_content(array_merge(array(
                     "STATUS" => "ERROR", 
                     "STATUS_TYPE" => "FIELD",
                     "FIELD_NAME" => "work_name",
                     "MESSAGE" => $work_name_Validation['Message']
                 ), $_POST));
                 exit;			
             }			
         }else{
            $work_name='';	
         }

         
         if(isset($_POST['work_group_id']) && $_POST['work_group_id']!='')
         {
             $work_group_id=$_POST['work_group_id'];
             $work_group_id_Validation = $this->Field_Validation(
             array
             (
             'Field_Type'=>'number',
             'Field_Value'=>$work_group_id,
             'Field_Name'=>'work_group_id',
             'Field_Label_Name'=>'work_group_id'
             )
             );
             
             if ($work_group_id_Validation['Status'] == "Error") {
                 $this->main_content(array_merge(array(
                     "STATUS" => "ERROR", 
                     "STATUS_TYPE" => "FIELD",
                     "FIELD_NAME" => "work_group_id",
                     "MESSAGE" => $work_group_id_Validation['Message']
                 ), $_POST));
                 exit;			
             }			
         }else{
             $this->main_content(array_merge(array(
                 "STATUS" => "ERROR", 
                 "MESSAGE" => "Enter Work Group Name"
             ), $_POST));
             exit;	
         }

         if(isset($_POST['fin_year']) && $_POST['fin_year']!='')
         {
             $fin_year=$_POST['fin_year'];
             $fin_year_Validation = $this->Field_Validation(
             array
             (
             'Field_Type'=>'fin_year',
             'Field_Value'=>$fin_year,
             'Field_Name'=>'fin_year',
             'Field_Label_Name'=>'fin_year'
             )
             );
             
             if ($work_group_id_Validation['Status'] == "Error") {
                 $this->main_content(array_merge(array(
                     "STATUS" => "ERROR", 
                     "STATUS_TYPE" => "FIELD",
                     "FIELD_NAME" => "fin_year",
                     "MESSAGE" => $fin_year_Validation['Message']
                 ), $_POST));
                 exit;			
             }			
         }else{
            $fin_year='';
         }

         if(isset($_POST['district']) && $_POST['district']!='')
         {
             $district=$_POST['district'];
             $district_Validation = $this->Field_Validation(
             array
             (
             'Field_Type'=>'number',
             'Field_Value'=>$district,
             'Field_Name'=>'district',
             'Field_Label_Name'=>'district'
             )
             );
             
             if ($district_Validation['Status'] == "Error") {
                 $this->main_content(array_merge(array(
                     "STATUS" => "ERROR", 
                     "STATUS_TYPE" => "FIELD",
                     "FIELD_NAME" => "district",
                     "MESSAGE" => $district_Validation['Message']
                 ), $_POST));
                 exit;			
             }			
         }else{
            $district='';
         }
		
         $wrk_stages='';
         if($work_group_id!='' )
         $wrk_stages.=" work_group_id=$work_group_id " ;
         if($work_name!='' )
         $wrk_stages.=" and work_type_id=$work_name " ;
         if($fin_year!='')
         $wrk_stages.=" and fin_year='$fin_year' ";
         if($district!='')
         $wrk_stages.=" and dcode='$district' ";


		$list_com="
select scheme_group_name_en,count,completed,balance,as_amt,expen from 
		( SELECT scheme_group_id, scheme_group_name_en from master.m_scheme_group ORDER BY scheme_group_name_en )a
		INNER JOIN
		(select scheme_group_id ,  count(1) as count,
		sum(case when current_stage_of_work=11 then 1 else 0 end ) as completed, 
		sum(case when (current_stage_of_work<>11 or current_stage_of_work is null) then 1 else 0 end ) as balance, 
		sum(as_value) as as_amt,
		sum(case when current_stage_of_work=11 then amount_spent_sofar else 0 end ) as expen 
		from works.t_works where  $wrk_stages  GROUP BY scheme_group_id) b
		on a.scheme_group_id=b.scheme_group_id   order by a.scheme_group_name_en "; 
      
	$set=$this->prepare($list_com,array(),2);

		$count_val=count($set);
        $cnt = 0;
        $comp = 0;
        $bal = 0;
        $as_amt = 0;
        $expen = 0;
        $balance = 0;
        $slno = 1;
		if ($count_val > 0) {
			foreach ($set as $row) {
			?>



                                <tr>
                                    <td class="text-center"><?php echo htmlentities($slno); ?></td>
                                    <td class="text-center"><?php echo htmlentities($row['scheme_group_name_en']); ?>
                                    </td>
                                    <td class="text-center"><?php echo htmlentities($row['count']); ?></td>
                                    <td class="text-center"><?php echo htmlentities($row['completed']); ?></td>
                                    <td class="text-center"><?php echo htmlentities($row['balance']); ?></td>
                                    <td class="text-center">
                                        <?php echo htmlentities(number_format($row['completed']/$row['count']*100,2)); ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo htmlentities(number_format($row['as_amt']/100000,3)); ?></td>
                                    <td class="text-center">
                                        <?php echo htmlentities(number_format($row['expen']/100000,3)); ?></td>
                                    <td class="text-center">
                                        <?php $balance = $row['as_amt'] - $row['expen'];  echo number_format($balance/100000,3); ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo htmlentities(number_format($row['expen']/$row['as_amt']*100,2)); ?>
                                    </td>
                                </tr>
                                <?php
			
			$slno++;
            $cnt+=$row['count'];
	        $comp+=$row['completed'];
	        $bal+=$row['balance'];
	        $as_amt+=$row['as_amt'];
	        $expen+=$row['expen'];
	        $balance=$balance;
		}
		
		?> <tr style="background-image: -moz-linear-gradient(top, #dcf4fe 25%, #b6eaff 80%); ">
                                    <td height="37" colspan="2" align="right"><strong> Total </span></strong></strong>
                                    </td>
                                    <td width="9%" align="right">
                                        <strong><?php echo htmlentities($cnt) ; ?></span></strong></strong>
                                    </td>
                                    <td width="11%" align="right">
                                        <strong><?php echo htmlentities($comp) ; ?></span></strong></strong>
                                    </td>
                                    <td width="9%" align="right">
                                        <strong><?php echo htmlentities($bal) ; ?></span></strong></strong>
                                    </td>
                                    <td width="12%" align="right">
                                        <strong><?php echo htmlentities(number_format($comp/$cnt*100,2)) ; ?></span></strong></strong>
                                    </td>
                                    <td width="11%" align="right">
                                        <strong><?php echo htmlentities(number_format($as_amt/100000,3)); ?></span></strong></strong>
                                    </td>
                                    <td width="9%" align="right">
                                        <strong><?php echo htmlentities(number_format($expen/100000,3)); ?></span></strong></strong>
                                    </td>
                                    <td width="9%" align="right">
                                        <strong><?php echo htmlentities(number_format($balance/100000,3)); ?></span></strong></strong>
                                    </td>
                                    <td width="12%" align="right">
                                        <strong><?php echo htmlentities(number_format($expen/$as_amt*100,2)) ; ?></span></strong></strong>
                                    </td>
                                </tr>
                            </tbody>

                            <?php
        } else {
    ?>
                            <tr>
                                <td colspan="3" class="text-center">No records found</td>
                            </tr>
                            </tbody>
                            <?php
} ?>
                        </table>
                    </div>
                </div>
            </div>

            <?php
  }
  ?>

</form>







<?php

        // #############

        // PAGE CONTENT END

        // #############

        $ob_output_main_forms = ob_get_contents();
        ob_clean();

        $this->Template("Template1", "Scheme Entry", $ob_output_main_forms, array(
            array(
                "name" => "Scheme Entry"
            )
        ));
        exit();
    }
	
	
	
	public function data_save($save_data)
    {
        
//print_r($save_data);die;
       
        // TOKEN VALIDATE
        if (! $this->validateToken($this->page_token, $save_data[$this->page_token])) {
            $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => $this->page_token,
                "MESSAGE" => "Invalid Token"
            ), $save_data));
			exit;
        }	
	
        if(isset($save_data['scheme_id']) && $save_data['scheme_id']!='')
         {
             $scheme_id=$save_data['scheme_id'];
             $scheme_id_Validation = $this->Field_Validation(
             array
             (
             'Field_Type'=>'number',
             'Field_Value'=>$scheme_id,
             'Field_Name'=>'scheme_id',
             'Field_Label_Name'=>'Scheme Name'
             )
             );
             
             if ($scheme_id_Validation['Status'] == "Error") {
                 $this->main_content(array_merge(array(
                     "STATUS" => "ERROR", 
                     "STATUS_TYPE" => "FIELD",
                     "FIELD_NAME" => "scheme_id",
                     "MESSAGE" => $scheme_id_Validation['Message']
                 ), $save_data));
                 exit;			
             }			
         }else{
             $this->main_content(array_merge(array(
                 "STATUS" => "ERROR", 
                 "MESSAGE" => "Enter Scheme Name"
             ), $save_data));
             exit;	
         }
 
         if(isset($save_data['work_name']) && $save_data['work_name']!='')
         {
             $work_name=$save_data['work_name'];
             $work_name_Validation = $this->Field_Validation(
             array
             (
             'Field_Type'=>'number',
             'Field_Value'=>$work_name,
             'Field_Name'=>'work_name',
             'Field_Label_Name'=>'work_name'
             )
             );
             
             if ($work_name_Validation['Status'] == "Error") {
                 $this->main_content(array_merge(array(
                     "STATUS" => "ERROR", 
                     "STATUS_TYPE" => "FIELD",
                     "FIELD_NAME" => "work_name",
                     "MESSAGE" => $work_name_Validation['Message']
                 ), $save_data));
                 exit;			
             }			
         }else{
             $this->main_content(array_merge(array(
                 "STATUS" => "ERROR", 
                 "MESSAGE" => "Enter Work Name"
             ), $save_data));
             exit;	
         }

         if(isset($save_data['scheme_group_id']) && $save_data['scheme_group_id']!='')
         {
             $scheme_group_id=$save_data['scheme_group_id'];
             $scheme_group_id_Validation = $this->Field_Validation(
             array
             (
             'Field_Type'=>'number',
             'Field_Value'=>$scheme_group_id,
             'Field_Name'=>'scheme_group_id',
             'Field_Label_Name'=>'scheme_group_id'
             )
             );
             
             if ($scheme_group_id_Validation['Status'] == "Error") {
                 $this->main_content(array_merge(array(
                     "STATUS" => "ERROR", 
                     "STATUS_TYPE" => "FIELD",
                     "FIELD_NAME" => "scheme_group_id",
                     "MESSAGE" => $scheme_group_id_Validation['Message']
                 ), $save_data));
                 exit;			
             }			
         }else{
             $this->main_content(array_merge(array(
                 "STATUS" => "ERROR", 
                 "MESSAGE" => "Enter Scheme Group Name"
             ), $save_data));
             exit;	
         }
         if(isset($save_data['work_group_id']) && $save_data['work_group_id']!='')
         {
             $work_group_id=$_POST['work_group_id'];
             $work_group_id_Validation = $this->Field_Validation(
             array
             (
             'Field_Type'=>'number',
             'Field_Value'=>$work_group_id,
             'Field_Name'=>'work_group_id',
             'Field_Label_Name'=>'schemework_group_id_group_id'
             )
             );
             
             if ($work_group_id_Validation['Status'] == "Error") {
                 $this->main_content(array_merge(array(
                     "STATUS" => "ERROR", 
                     "STATUS_TYPE" => "FIELD",
                     "FIELD_NAME" => "work_group_id",
                     "MESSAGE" => $work_group_id_Validation['Message']
                 ), $save_data));
                 exit;			
             }			
         }else{
             $this->main_content(array_merge(array(
                 "STATUS" => "ERROR", 
                 "MESSAGE" => "Enter Work Group Name"
             ), $save_data));
             exit;	
         }       
	
         $type_of_improvement_id = '{}';
         if(isset($save_data['type_of_improvement_id'])){
             $type_of_improvement_id = '{' . implode(',', $save_data['type_of_improvement_id']) . '}';
         }




	$pp_initiation = "master.sp_road_work_type_of_improvement_link";
	$user_name = $this->getCurrentUser();
	$ip_address = $this->getIpAddress();
	$date = $this->getCurrentDate();
  	
	$save_query1="select * from master.sp_road_work_type_of_improvement_link(:scheme_id,:work_name,:work_group_id,:scheme_group_id,:type_of_improvement_id,:user_name,:ip_address)";
    $res1=$this->prepare($save_query1,array(":scheme_id"=>$scheme_id,":work_name"=>$work_name,":work_group_id"=>$work_group_id,":scheme_group_id"=>$scheme_group_id,":type_of_improvement_id"=>$type_of_improvement_id,":user_name"=>$user_name,":ip_address"=>$ip_address),4); 
    

               if ($this->prepareStatus($res1) == true) {
				   $this->commit();
                $message='Data Linked Successfully.';
                ?>
<script>
alert('<?php echo htmlentities($message); ?>');
window.location.href = 'road_work_type_of_improvement_link.php';
</script>
<?php
            }
            else {
                $this->rollBack();
                $this->main_content(array(
                    "STATUS" => "FAIL",
                    "STATUS_TYPE" => "FORM",
                    "MESSAGE" => "Data Entry Failed Due To Duplicate Entry"
                ));
            }
		

 
 

		
	}
	
}

$road_work_type_of_improvement_link = new road_work_type_of_improvement_link();


if(!isset($_POST['cmd']))
{
	
	if(isset($_POST['btn_save']) && $_POST['btn_save']!='')
	{
		$road_work_type_of_improvement_link->data_save($_POST);
	}
	else
	{
		$road_work_type_of_improvement_link->main_content(array("mode_name" => "Save","mode_class" => "btn-success"));
	}
}
else if (isset($_POST["cmd"])) {
	
    
		$cmd=base64_decode($_POST['cmd']);
       
		
		
         if($cmd==3)
		{
           
            $work_group_id=base64_decode($_POST['work_group_id']);
            


		$sel_role_qry="select b.work_id,c.work_name_en from 
	(select work_group_id,work_id from master.m_work_type where  del_flag is null and work_group_id=:work_group_id)b
    left join
    (select work_type_id,work_name_en from master.m_work_type_name where  del_flag is null)c
	on b.work_id=c.work_type_id"; 
		$sel_role_qry_res=$road_work_type_of_improvement_link->prepare($sel_role_qry,array(":work_group_id"=>$work_group_id),2);
       
			?>
<option value="">Select Work Name</option>
<?php	
			foreach($sel_role_qry_res as $sel_street_details_key=>$sel_street_details_row)
			{
			?>
<option value="<?php echo htmlentities($sel_street_details_row['work_id']); ?>">
    <?php echo htmlentities($sel_street_details_row['work_name_en']); ?></option>
<?php
			}
			exit;
		}
        
		
	}
	
	


?>