<?php
require_once __DIR__ . '/../../config/config.php';




class work_group_worktype_link  extends ConfigClass
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
<?php		

        // #############

        // PAGE CONTENT START

        // #############

		
		$state_code=$this->getCurrentStateCode();
		$dcode=$this->getCurrentDistrictCode();
		$tpcode=$this->getCurrentLocalBodyCode();

        ?>
<script type="text/javascript">
$(document).ready(function() {

$(document).on('click', '#btn_menu_edit', function() {
                    var id = $(this).parent().parent().find('.menu_id').val();
                    $.ajax({
                        url: "class_menu_addnew.php",
                        type: "post",
                        data: {
                            
                            "id":btoa(id),
                            "cmd": btoa(8)
                        },
                        success: function(data) {
                            if (data != '') {

                                var Result_Data = JSON.parse(data);
                                $('#txt_mnurl').val(Result_Data['ssmenu_url']);
                                $('#txt_mnname').val(Result_Data['ssmenu_desc']);
                                 $("input[name='rad_on_off'][value='" + Result_Data['rflag'] + "']").prop("checked", true);
                                $('#report_form_flag').val(Result_Data['report_no']);
                                $('#menu_order_no').val(Result_Data['menu_order_no']);
                                $('#cmb_app_web').val(Result_Data['responsive_support']);
                                $('#table_name').val(Result_Data['table_name']);
                                $('#purpose').val(Result_Data['purpose_of_form_or_report']);
                                $('#cmb_programmer').val(Result_Data['who_sec_code_added']);
                                $('#menu_delete_id').val(0);
                                $('#menu_edit_id').val(Result_Data['menu_id']);
                            }
                        },
                        dataType: 'html'
                    }); 
                }); 
                $(document).on('click', '#btn_menu_delete', function() {
                    var id = $(this).parent().parent().find('.menu_id').val();
                    $.ajax({
                        url: "class_menu_addnew.php",
                        type: "post",
                        data: {
                            "works_type":btoa(1),
                            "id":btoa(id),
                            "cmd": btoa(8)
                        },
                        success: function(data) {
                            if (data != '') {

      
                                var Result_Data = JSON.parse(data);
                                 $('#txt_mnurl').val(Result_Data['ssmenu_url']);
                                $('#txt_mnname').val(Result_Data['ssmenu_desc']);
                                 $("input[name='rad_on_off'][value='" + Result_Data['rflag'] + "']").prop("checked", true);
                                $('#report_form_flag').val(Result_Data['report_no']);
                                $('#menu_order_no').val(Result_Data['menu_order_no']);
                                $('#cmb_app_web').val(Result_Data['responsive_support']);
                                $('#table_name').val(Result_Data['table_name']);
                                $('#purpose').val(Result_Data['purpose_of_form_or_report']);
                                $('#cmb_programmer').val(Result_Data['who_sec_code_added']);
                                $('#menu_delete_id').val(Result_Data['menu_id']);
                                $('#menu_edit_id').val(0);
                            }
                        },
                        dataType: 'html'
                    }); 
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

            if ($("#cmb_lvl").val().length == '') {
                throw {
                    msg: "Select Allocation Type",
                    foc: "#cmb_lvl"
                }
            }


            return true;
        } catch (e) {
            alert(e.msg);
            $('#' + Current_Field_id).show();
            $(e.foc).focus();
            return false;
        }

    });

    $(document).on('change', '#cmb_lvl', function() {

        if ($('#cmb_lvl').val() != '') {
            var cmb_lvl = $('#cmb_lvl').val();
            //alert(allocation_type);

            $.ajax({
                url: "class_menu_addnew.php",
                type: "post",
                data: {
                    "cmb_lvl": btoa(cmb_lvl),
                    "cmd": btoa(1)
                },
                //data: {"allocation_type":btoa(allocation_type),"cmd":'%00'},
                success: function(data) {

                    if (data != '') {
                        $('#cmb_sub1').html(data);
                    }
                },
                dataType: 'html'
            });
            return true;
        } else {
            alert('Enter Submenu');
           
        }

    });






});
</script>
<style>
.tndtp_form_table {
    font-size: 15px;
    font-weight: bold;
    width: 100%;
    /* border-collapse: collapse;
    border-spacing: 0;
    border-radius: 10px;
    overflow: hidden; */
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

    /* Display table rows as block elements */
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
    /* background-color: green; */
    background: #F56217;
    /* background: linear-gradient(#0B486B, #F56217); */
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
    /* border-top: 7px solid #555a86;
border-bottom: 7px solid #555a86; */
    /* box-shadow: 0 0 8px #333; */
    box-shadow: 3px 3px 10px rgb(0 0 0 / 40%) inset;
    /* box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px; */
    /* border: 10px solid #EBEBEB; */
    background: #fff;

}
</style>
<form action="" method="post" class="" enctype="multipart/form-data" autocomplete="off">
    <div class="container mt-4">


        <input class="form-control form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>"
            name="<?php echo htmlentities($this->page_token); ?>"
            value="<?php echo htmlentities($this->token($this->page_token)); ?>">
        <div class="card">
            <div class="card-body pl-5 pr-5">
                <?php
                    if (isset($post_data_array["STATUS"])) 
                    {
                        echo $this->ShowMessage($post_data_array["STATUS"], $post_data_array["MESSAGE"]);
                    }
                    ?>


                <table class="table-bordered tndtp_form_table">
                    <thead class="newhead">
                        <tr>
                            <th colspan="2" class="text-center">ADD - New Menu (Submenu 1)
                                <button type="button" class="schemebuton float-end"
                                    onClick="location.href = '<?php echo htmlentities($site_data->website_url); ?>project/home.php?id=<?php echo base64_encode(2) ;?>';"><i
                                        class="fa fa-arrow-circle-left"></i> Back To Menu</button>
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>
                                <span DisplayLabelID="435">Level</span>
                            </td>
                            <td><select id="cmb_lvl" name="cmb_lvl"
                                    class="form-control form-control-sm w-75">
                                    <option value="" DisplayLabelID="255">Select Level</option>
                                    <?php
$sel_wrkgrp_name="SELECT role_code,role_name FROM security.m_accounts_role where del_flag is null";
$sel_wrkgrp_name_res=$this->prepare($sel_wrkgrp_name,array(),2);

foreach($sel_wrkgrp_name_res as $sel_wrkgrp_name_res_key=>$sel_wrkgrp_name_res_row)
{								
?>
                                    <option value="<?php echo htmlentities($sel_wrkgrp_name_res_row['role_code']); ?>">
                                        <?php echo htmlentities($sel_wrkgrp_name_res_row['role_name']); ?></option>
                                    <?php
}
?>
                                </select>
                                <script type="text/javascript">
                                document.getElementById('cmb_lvl').value =
                                    '<?php echo htmlentities(isset($_POST['cmb_lvl'])?$_POST['cmb_lvl']:''); ?>';
                                </script>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <span DisplayLabelID="121">Sub Menu 1</span>
                            </td>
                            <td><select id="cmb_sub1" name="cmb_sub1" class="form-control form-control-sm w-50">
                                    <option value="" DisplayLabelID="255">Choose Scheme</option>
                                    <?php
if(isset($_POST['cmb_lvl']) && $_POST['cmb_lvl']!='')
{
$cmb_lvl=$_POST['cmb_lvl'];

$sel_street_details="select menu_id,smenu_id,user_id,smenu_desc from security.m_submenu1 where user_id=:user_id and del_flag is null";

$sel_street_details_res=$this->prepare($sel_street_details,array(":user_id"=>$cmb_lvl),2);

foreach($sel_street_details_res as $sel_street_details_key=>$sel_street_details_row)
{
?>
                                    <option
                                        value="<?php echo htmlentities($sel_street_details_row['smenu_id']); ?>">
                                        <?php echo htmlentities($sel_street_details_row['smenu_desc']); ?></option>
                                    <?php
}
}
?>
                                </select>
                                <script type="text/javascript">
                                document.getElementById('cmb_sub1').value =
                                    '<?php echo htmlentities(isset($_POST['cmb_sub1'])?$_POST['cmb_sub1']:''); ?>';
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
                                <a class="btn btn-secondary btn-sm" href="work_group_multiple_worktype_link.php"><i
                                        class="fa fa-eraser pe-1 me-2"></i>Clear</a>
                            </td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    </div>





    <?php
  if((isset($_POST['btn_show']) && $_POST['btn_show']!='') || (isset($post_data_array["STATUS"]) && $post_data_array["STATUS"]=="SUCCESS" ))
  { 
  
  ?>
    <div class="container mt-3">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title" align="center">
                    <!-- <a href="../../reports/PropertyTax/PropertyTaxRoleWiseWardAllocation_new.php"
class=" btn btn-sm btn-primary">Report View</a> -->
                </h4>
                <table class="table-bordered tndtp_form_report_table">
                    <thead class="newhead">
                        <tr>
                           
                            <th scope="col"><span DisplayLabelID="436">Menu Name</span></th>
                            <th scope="col"><span DisplayLabelID="436">Menu URL</span></th>
                             <th scope="col"><span DisplayLabelID="436">Table/View/Stored Procedure Name</span></th>
                              
                               <th scope="col"><span DisplayLabelID="436">Programmer Name</span></th>
                            <th scope="col" width="10"><span DisplayLabelID="436">Menu Order Number</span></th>
                            <th width="111" align="center" nowrap="nowrap"> Form - F<br />
                                    Report - R<br />
                                    (Ex.: F or R)</th>
                             <th width="149" align="center" nowrap="nowrap">Form No/<br />
                                    Report No</th>

                           <th colspan="2" align="center" nowrap="nowrap">Flag <br />
                                    ON/
                                    OFF</th>
                                    <th scope="col"><span DisplayLabelID="436">purpose of Form or Report</span></th>
                                    <th  align="center" nowrap="nowrap">Responsive Support</th>
                        </tr>
                        
                    </thead>
                    <?php
                    $cmlvl = $_REQUEST['cmb_lvl'];
                    $cmb_sub1 = $_REQUEST['cmb_sub1'];
 $sqlmax="SELECT  count(1) as rep_no from security.m_submenu2 where user_id=:user_id and  smenu_id=:smenu_id and del_flag is null;";
             $resultmax=$this->prepare($sqlmax,array(":user_id"=>$cmlvl,":smenu_id"=>$cmb_sub1),4);
        
            // print_r($resultmax);die;
                 if (isset($resultmax['rep_no']) && $resultmax['rep_no']!='') {
                        $rep_no = $resultmax['rep_no'] + 1;
                        
                }else{
                        $rep_no = 1;
                }

?>
                    <tbody>
                        <tr align="center">
                                <td><input type="text" name="txt_mnname" id="txt_mnname"  value="<?php echo $rep_no. ' - '; ?>" class="textbox_long" /></td>
                                <td><input type="text" name="txt_mnurl" id="txt_mnurl" class="textbox_long"  /></td>
                                <td><input type="text" name="table_name" id="table_name"  class="textbox_long" /></td>
                                <td>


                                <select id="cmb_programmer" name="cmb_programmer"
                                    class="form-control form-control-sm w-75">
                                    <option value="" DisplayLabelID="255">Select Programmer</option>
                                    <?php
$sel_wrkgrp_name="select programmer_id, programmer_name from master.m_programmer WHERE current_employee='N' order by programmer_id ";
$sel_wrkgrp_name_res=$this->prepare($sel_wrkgrp_name,array(),2);

foreach($sel_wrkgrp_name_res as $sel_wrkgrp_name_res_key=>$sel_wrkgrp_name_res_row)
{                               
?>
                                    <option value="<?php echo htmlentities($sel_wrkgrp_name_res_row['programmer_id']); ?>">
                                        <?php echo htmlentities($sel_wrkgrp_name_res_row['programmer_name']); ?></option>
                                    <?php
}
?>
                                </select>





                            </td>
                                <td ><input type="text" name="menu_order_no" id="menu_order_no" style="width: 45px;" class="small_textbox" /></td>
                                <td><input type="text" name="report_form_flag" id="report_form_flag" style="width: 45px;" class="small_textbox" pattern="[FR]{1}"  title="Example: F or R" maxlength="1" /></td>
                                <td><?php echo $rep_no; ?><input type="hidden" name="report_form_no" id="report_form_no" value="<?php echo $rep_no; ?>" readonly="readonly"  class="small_textbox" /> 
                                </td>
                                <td width="88"><input name="rad_on_off" id="rad_on" type="radio" value="1" checked="checked" /></td>
                                <td width="95"><input name="rad_on_off" id="rad_off" type="radio" value="0" ></td>
                                <td><textarea rows="2" cols="30" name="purpose" id="purpose"></textarea></td>
                                <input type="hidden" id="menu_edit_id" name="menu_edit_id" class="form-control form-control-sm number_field" value=""/>
                            <input type="hidden" id="menu_delete_id" name="menu_delete_id" class="form-control form-control-sm number_field" value=""/>
                                <td width="297" height="30" align="left">
                                <select name="cmb_app_web" id="cmb_app_web" class="cmb_style" >
                                    <option value="">Select Responsive Support</option>
                                    <option value="A">All</option>
                                    <option value="M">Select App</option>
                                    <option value="W">Select Web</option>
                                </select>
                                <script>document.getElementById('cmb_app_web').value="" </script></td>
                            </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4">
                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                        <input type="submit" id="btn_save" name="btn_save"
                                            value="<?php echo htmlentities($post_data_array['mode_name']); ?>"
                                            class="btn btn-sm <?php echo htmlentities($post_data_array['mode_class']); ?>" />
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>


    <div class="card mt-4">
        <div class="card-body">
            <div class="col-lg-12 col-ml-12">
                <table class=" table-bordered tndtp_form_report_table" id="Result_table">
                    <thead class="newhead">
                        <tr>
                            <th scope="col">S.NO</th>
                            <th scope="col">Menu ID</th>
                            <th scope="col">Menu Name</th>
                            <th scope="col">Menu URL</th>
                            <th scope="col">Menu Order Number</th>
                            <th width="89" align="center">Form - F<br />
                                    Report - R<br />
                                    (Ex.: F or R)</th>
                                <th width="78" align="center">Form No/<br />
                                    Report No</th>
                                <th width="57" align="center">ON/ OFF</th>
                                <th width="51" align="center">Responsive Support</th>
                                <th width="51" align="center">Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cmlvl = $_REQUEST['cmb_lvl'];
                        $cmb_sub1 = $_REQUEST['cmb_sub1'];
                               $list_com = "SELECT menu_id,smenu_id,ssmenu_id,user_id,dept_id,ssmenu_desc, ssmenu_url,menu_order_no,report_no,report_form_no,rflag,responsive_support, ROW_NUMBER() OVER(PARTITION BY user_id 
ORDER BY user_id, smenu_id) AS rep_no from security.m_submenu2 where user_id=:user_id and smenu_id=:cmb_sub1 and  del_flag is null ORDER BY CASE
                                        WHEN menu_order_no::text LIKE '%.%' THEN  -- if there's a decimal point
                                        CAST(SPLIT_PART(menu_order_no::text, '.', 2) AS NUMERIC)
                                        ELSE
                                        CAST(menu_order_no AS NUMERIC)          -- no decimal, use the number itself
                                    END;;";
                                $set = $this->prepare($list_com,array(":user_id"=>$cmlvl,":cmb_sub1"=>$cmb_sub1),2);
                                if(count($set)>0)
                                {
                                    $slno = 1;
                                    foreach ($set as $row) { if ($row['rep_no'] != '') {
                                $rep_no = $row['rep_no'] + 1;
                            }
                            else{
                                $rep_no = '1';
                                }


                                 ?>
                            
                        <tr>
                            <td><?php echo htmlentities($slno++); ?></td>
                        
                            </td>
                            <td align="left"><?php echo htmlentities($row['ssmenu_id']); ?></td>
                            </td>
                            
                            <td align="left">
    <?php echo htmlentities($row['ssmenu_desc']); ?>
</td>

                            </td>
                            <td align="left"><?php echo htmlentities($row['ssmenu_url']); ?></td>
                            </td>
                            <td align="left"><?php echo htmlentities($row['menu_order_no']); ?></td>
                            
                             <td align="left">
    <?php echo ($row['report_no'] == 1) ? 'R' : 'F'; ?>
</td>
                             <td align="left"><?php echo htmlentities($row['report_form_no']); ?></td>
                             <td align="left">
                                <?php echo ($row['rflag'] == 1) ? 'ON' : 'OFF'; ?>
                            </td>
                            <td align="left"><?php echo htmlentities($row['responsive_support']); ?></td>
                             <input type="hidden" name="menu_bank_id" value="<?php echo htmlentities($row['menu_id']);?>" class="menu_id" />
                            
                            <td>
                    <input type="button" id="btn_menu_edit" name="btn_menu_edit" value="Edit" class="btn btn-md text-white font-weight-bold btn-success" style="font-size: small;">
                    <br/>
                    <input type="button" id="btn_menu_delete" name="btn_menu_delete" value="Delete" class="btn btn-md text-white font-weight-bold btn-danger" style="font-size: small;">
                </td>
                          
                        </tr>
                        <?php }
                                }?>
                    </tbody>
                    <?php if(count($set)==0){ ?>
                    <tbody>
                        <td colspan="4" class="text-center text-danger">Record Not Found</td>
                    </tbody>
                    <?php } ?>
                </table>



            </div>
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

        $this->Template("Template1", "agency Entry", $ob_output_main_forms, array(
            array(
                "name" => "agency Entry"
            )
        ));
    }
	
	
	
	public function data_save($save_data)
    {
        // echo "<pre>";
        // print_r($save_data);die;
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

		
	
	if(isset($save_data['cmb_lvl']) && $save_data['cmb_lvl']!='')
        {
            $cmb_lvl=$save_data['cmb_lvl'];
            $cmb_lvl_Validation = $this->Field_Validation(
            array
            (
            'Field_Type'=>'number',
            'Field_Value'=>$cmb_lvl,
            'Field_Name'=>'cmb_lvl',
            'Field_Label_Name'=>'Level name'
            )
            );
            
            if ($cmb_lvl_Validation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR", 
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "cmb_lvl",
                    "MESSAGE" => $cmb_lvl_Validation['Message']
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


        // if (isset($save_data['txt_mnname'])) {
                

        //         $txt_mnname_Validation = $this->Field_Validation(
        //             array(
        //                 'Field_Type' => 'text',
        //                 'Field_Value' => $txt_mnname,
        //                 'Field_Name' => 'txt_mnname',
        //                 'Field_Max_length' => '150',
        //                 'Field_Label_Name' => 'Invalid txt_mnname',
        //             )
        //         );

        //         if ($txt_mnname_Validation['Status'] == "Error") {
        //             $this->main_content(array_merge(array(
        //                 "STATUS" => "ERROR",
        //                 "STATUS_TYPE" => "FIELD",
        //                 "FIELD_NAME" => "txt_mnname",
        //                 "MESSAGE" => $txt_mnname_Validation['Message']
        //             ), $save_data));
        //             exit;
        //         }
        //     }

        if(isset($save_data['menu_order_no']) && $save_data['menu_order_no']!='')
        {
            $menu_order_no=$save_data['menu_order_no'];
                  
        }


        if(isset($save_data['report_form_no']) && $save_data['report_form_no']!='')
        {
            $report_form_no=$save_data['report_form_no'];
            $report_form_no_Validation = $this->Field_Validation(
            array
            (
            'Field_Type'=>'number',
            'Field_Value'=>$report_form_no,
            'Field_Name'=>'report_form_no',
            'Field_Label_Name'=>'Report or form'
            )
            );
            
            if ($report_form_no_Validation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR", 
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "report_form_no",
                    "MESSAGE" => $report_form_no_Validation['Message']
                ), $save_data));
                exit;           
            }           
        }


        if(isset($save_data['rad_on_off']) && $save_data['rad_on_off']!='')
        {
            $rad_on_off=$save_data['rad_on_off'];
            $rad_on_off_Validation = $this->Field_Validation(
            array
            (
            'Field_Type'=>'number',
            'Field_Value'=>$rad_on_off,
            'Field_Name'=>'rad_on_off',
            'Field_Label_Name'=>'On or off'
            )
            );
            
            if ($rad_on_off_Validation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR", 
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "rad_on_off",
                    "MESSAGE" => $rad_on_off_Validation['Message']
                ), $save_data));
                exit;           
            }           
        }



            $txt_mnname = $save_data['txt_mnname'];
            $txt_mnurl=$save_data['txt_mnurl'];
            // $menu_order_no=$save_data['menu_order_no'];
            $report_form_flag=$save_data['report_form_flag'];
            // $report_form_no=$save_data['report_form_no'];
            // $rad_on_off=$save_data['rad_on_off'];
            $cmb_app_web=$save_data['cmb_app_web'];

            $cmb_sub1=$save_data['cmb_sub1'];
            $table_name=$save_data['table_name'];
            $cmb_programmer=isset($save_data['cmb_programmer'])?$save_data['cmb_programmer']:NULL;
            $purpose=$save_data['purpose'];
             $edit_id = $save_data['menu_edit_id'] ?? '0';
           $delete_id = $save_data['menu_delete_id'] ?? '0';



 $sqlmx="SELECT max(ssmenu_id) as smxid FROM security.m_submenu2 WHERE del_flag IS NULL and user_id=:cmb_lvl;";
             $result=$this->prepare($sqlmx,array(":cmb_lvl"=>$cmb_lvl),4);
        
            
                 if ($result['smxid'] != '') {
                        $maxid = $result['smxid'] + 1;
                        $maxid = '0' . $maxid;
                }else{
                        $maxid = '01' . '01';
                }

            



 	$user_name = $this->getCurrentUser();
	$ip_address = $this->getIpAddress();
	$date = $this->getCurrentDate();
	$user_name = $this->getCurrentUser();
	$save_query1="select * from security.sp_submenu2(:ssmenu_id,:user_id,:dept_id,:txt_mnname,:txt_mnurl,:menu_order_no,:report_form_flag,:report_form_no,:rad_on_off,:cmb_app_web,:cmb_sub1,:table_name,:cmb_programmer,:purpose,:user_name,:ip_address,:edit_id,:delete_id)";
               $res1=$this->prepare($save_query1,array(":ssmenu_id"=>$maxid,":user_id"=>$cmb_lvl,":dept_id"=>01,":txt_mnname"=>$txt_mnname,":txt_mnurl"=>$txt_mnurl,":menu_order_no"=>$menu_order_no,":report_form_flag"=>$report_form_flag,":report_form_no"=>$report_form_no,":rad_on_off"=>$rad_on_off,":cmb_app_web"=>$cmb_app_web,":cmb_sub1"=>$cmb_sub1,":table_name"=>$table_name,":cmb_programmer"=>$cmb_programmer,":purpose"=>$purpose,":user_name"=>$user_name,":ip_address"=>$ip_address,":edit_id"=>$edit_id,":delete_id"=>$delete_id),4); 

               // print_r($res1);die;
	

               if ($this->prepareStatus($res1)== true) {
				   $this->commit();
                $message='Data Linked Successfully.';
                ?>
<script>
alert('<?php echo htmlentities($message); ?>');
window.location.href = 'class_menu_addnew.php';
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

$propertyassessment = new work_group_worktype_link();


if(!isset($_POST['cmd']))
{
	
	if(isset($_POST['btn_save']) && $_POST['btn_save']!='')
	{
		$propertyassessment->data_save($_POST);
	}
	else
	{
		$propertyassessment->main_content(array("mode_name" => "Save","mode_class" => "btn-success"));
	}
}
else
{
	try
    {
		if(isset($_POST['cmd']) && $_POST['cmd']!=''){
    		$cmd=base64_decode($_POST['cmd']);
            if ($cmd == 8) {
        $Result=array();
        $id = base64_decode($_POST['id']);
        $dcode = $propertyassessment->getCurrentDistrictCode();
        $lbcode = $propertyassessment->getCurrentLocalBodyCode();
        $sel_qry = "select menu_id,smenu_id,ssmenu_id,ssmenu_url,ssmenu_desc,ssmenu_desc_ll,user_id,dept_id,rflag,report_no,menu_order_no,report_form_no,responsive_support,table_name,purpose_of_form_or_report,who_sec_code_added  from  security.m_submenu2
 where menu_id=:menu_id and del_flag is  null and isactive=:isactive";      
        $sel_qry_res=$propertyassessment->prepare($sel_qry,array(  ":isactive"=>1, ":menu_id"=>$id),4);
        $Result['STATUS'] = 'SUCCESS';
        $Result['menu_id'] = $sel_qry_res['menu_id'];
        $Result['ssmenu_id'] = $sel_qry_res['ssmenu_id'];
        $Result['ssmenu_url'] = $sel_qry_res['ssmenu_url'];
        $Result['ssmenu_desc'] = $sel_qry_res['ssmenu_desc'];         
        $Result['rflag'] = $sel_qry_res['rflag'];
        $Result['report_no'] = $sel_qry_res['report_no'];
        $Result['menu_order_no'] = $sel_qry_res['menu_order_no'];
        $Result['report_form_no'] = $sel_qry_res['report_form_no'];
        $Result['responsive_support'] = $sel_qry_res['responsive_support'];
        $Result['table_name'] = $sel_qry_res['table_name'];
        $Result['purpose_of_form_or_report'] = $sel_qry_res['purpose_of_form_or_report'];
        $Result['who_sec_code_added'] = $sel_qry_res['who_sec_code_added'];
        echo json_encode($Result);
        exit;
    }
        
            if($cmd==1)
            {
        
                
                $cmb_lvl=base64_decode($_POST['cmb_lvl']);


            $sel_role_qry="select menu_id,smenu_id,user_id,smenu_desc from security.m_submenu1 where user_id=:user_id and del_flag is null"; 
            $sel_role_qry_res=$propertyassessment->prepare($sel_role_qry,array(":user_id"=>$cmb_lvl),2);
           
                ?>
    <option value="">Select Scheme Name</option>
    <?php   
                foreach($sel_role_qry_res as $sel_street_details_key=>$sel_street_details_row)
                {
                ?>
    <option value="<?php echo htmlentities($sel_street_details_row['smenu_id']); ?>">
        <?php echo htmlentities($sel_street_details_row['smenu_desc']); ?></option>
    <?php
                }
                exit;
            }
            else
            {
                echo 'Invalid Request';
                exit;   
            }
		}else{
			echo json_encode(array(
			"STATUS" => "ERROR",
			"FIELD_NAME" => "cmd",
			"MESSAGE" => $cmd_Validation['Message']
			));
			exit;	
		}

        
	
		
	}
	catch (Exception  $e)
    {
       	echo 'Invalid Request';
		exit;
    }
	
}
?>