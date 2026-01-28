<?php
require_once __DIR__ . '/../../config/config.php';




class scheme_district_link  extends ConfigClass
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

    // $(document).ready(function() {
    //     $('#data_table').DataTable();
        
    // });


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


            if ($("#voucher_name").val().length == '') {
                throw {
                    msg: "Select voucher Type",
                    foc: "#voucher_name"
                }
            }
            if ($("#account_type").val().length == '') {
                throw {
                    msg: "Select Account Type",
                    foc: "#account_type"
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








    $("#btn_save").on('click', function() {

        var Current_Field_id = $(this).attr('id');
        $('#' + Current_Field_id).hide();
        try {

            if ($('input:checkbox').filter(':checked').length < 1) {

                throw {
                    msg: "Please Check at least One Account Head",
                }


            }
            return true;

        } catch (e) {
            alert(e.msg);
            $('#' + Current_Field_id).show();
            setTimeout(function() {
                $(e.foc).focus();
            }, 1);
            return false;
        }

    });
    $(document).on('change', '#scheme_groupname', function() {

        if ($('#scheme_groupname').val() != '') {
            var scheme_groupname = $('#scheme_groupname').val();
            //alert(allocation_type);

            $.ajax({
                url: "scheme_district_link.php",
                type: "post",
                data: {
                    "scheme_groupname": btoa(scheme_groupname),
                    "cmd": btoa(1)
                },
                //data: {"allocation_type":btoa(allocation_type),"cmd":'%00'},
                success: function(data) {

                    if (data != '') {
                        $('#scheme_name').html(data);
                    }
                },
                dataType: 'html'
            });
            return true;
        } else {
            alert('Select Scheme Name');
            $('#scheme_name').html('<option value="">Select Scheme Name</option>');
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


.table-wrapper{
    height:60vh;
    overflow-y:auto;
}


</style>

<form action="accounthead_link.php" method="post" class="" enctype="multipart/form-data" autocomplete="off">
    <input class="form-control form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>"
        name="<?php echo htmlentities($this->page_token); ?>"
        value="<?php echo htmlentities($this->token($this->page_token)); ?>">
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
                            <th colspan="2" class="text-center">Account Head - Link <button type="button"
                                    class="schemebuton float-end"
                                    onClick="location.href = '<?php echo htmlentities($site_data->website_url); ?>project/home.php?id=<?php echo base64_encode(2) ;?>';"><i
                                        class="fa fa-arrow-circle-left"></i> Back To Menu</button></th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>
                                <span DisplayLabelID="435">Voucher Type</span>
                            </td>
                            <td><select id="voucher_name" name="voucher_name"
                                    class="form-control form-control-sm w-75">
                                    <option value="" DisplayLabelID="255">Select Voucher Type</option>
                                    <?php
                                        $sel_schemegrp_name="SELECT voucher_id,voucher_type_en FROM accounts_master.m_voucher_type where del_flag is null order by voucher_id";
                                        $sel_schemegrp_name_res=$this->prepare($sel_schemegrp_name,array(),2);

                                        foreach($sel_schemegrp_name_res as $sel_schemegrp_name_res_key=>$sel_schemegrp_name_res_row)
                                        {								
                                        ?>
                                            <option value="<?php echo htmlentities($sel_schemegrp_name_res_row['voucher_id']); ?>"><?php echo htmlentities($sel_schemegrp_name_res_row['voucher_type_en']); ?></option>
                                        <?php
                                        }
                                        
                                        ?>
                                        <option
                                                                                value="<?php echo htmlentities($sel_schemegrp_name_res_row['voucher_id']); ?>">
                                                                                <?php echo htmlentities($sel_schemegrp_name_res_row['voucher_type_en']); ?>
                                                                            </option>
                                </select>
                                <script type="text/javascript">
                                document.getElementById('voucher_name').value =
                                    '<?php echo htmlentities(isset($_POST['voucher_name'])?$_POST['voucher_name']:''); ?>';
                                </script>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span DisplayLabelID="435">Account Type</span>
                            </td>
                            <td><select id="account_type" name="account_type" class="form-control form-control-sm w-75">
                                    <option value="" DisplayLabelID="255"><span>Account Type</span></option>
                                    <option value="1" DisplayLabelID="255"><span>Credit</span></option>
                                    <option value="2" DisplayLabelID="255"><span>Debit</span></option>

                                </select>
                                <script type="text/javascript">
                                document.getElementById('account_type').value =
                                    '<?php echo htmlentities(isset($_POST['account_type'])?$_POST['account_type']:''); ?>';
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
                                <a class="btn btn-secondary btn-sm" href="scheme_district_link.php"><i
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
            <div class="card">
                <div class="card-body">
                    <div  class="table-wrapper">
 <table class="table-bordered tndtp_form_report_table table-striped" id="data_table" >
                        <thead class="newhead data-table-head">
                            <tr>
                                <th scope="col" class="text-center"><span DisplayLabelID="174">Sl.No</span></th>
                                <th scope="col" class="text-center"><span DisplayLabelID="436">Account Head Code(4 series)</span></th>
                                <th scope="col" class="text-center"><span DisplayLabelID="436">Account Head Code(7 series)</span></th>
                                <th scope="col" class="text-center"><span DisplayLabelID="436">Account Head Name(English)</span></th>
                                <th scope="col" class="text-center"><span DisplayLabelID="436">Account Head Name(Tamil)</span></th>
                                <th scope="col"><span DisplayLabelID="354">Select Account Head</span>
                                    

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
		
		
         if(isset($_POST['voucher_name']) && $_POST['voucher_name']!='')
         {
             $voucher_name=$_POST['voucher_name'];
             $voucher_name_Validation = $this->Field_Validation(
             array
             (
             'Field_Type'=>'number',
             'Field_Value'=>$voucher_name,
             'Field_Name'=>'voucher_name',
             'Field_Label_Name'=>'voucher name'
             )
             );
             
             if ($voucher_name_Validation['Status'] == "Error") {
                 $this->main_content(array_merge(array(
                     "STATUS" => "ERROR", 
                     "STATUS_TYPE" => "FIELD",
                     "FIELD_NAME" => "voucher_name",
                     "MESSAGE" => $voucher_name_Validation['Message']
                 ), $_POST));
                 exit;			
             }			
         }else{
             $this->main_content(array_merge(array(
                 "STATUS" => "ERROR", 
                 "MESSAGE" => "Enter voucher name"
             ), $_POST));
             exit;	
         }
 
         if(isset($_POST['account_type']) && $_POST['account_type']!='')
         {
             $account_type=$_POST['account_type'];
             $account_type_Validation = $this->Field_Validation(
             array
             (
             'Field_Type'=>'number',
             'Field_Value'=>$account_type,
             'Field_Name'=>'account_type',
             'Field_Label_Name'=>'account type'
             )
             );
             
             if ($account_type_Validation['Status'] == "Error") {
                 $this->main_content(array_merge(array(
                     "STATUS" => "ERROR", 
                     "STATUS_TYPE" => "FIELD",
                     "FIELD_NAME" => "account type",
                     "MESSAGE" => $account_type_Validation['Message']
                 ), $_POST));
                 exit;			
             }			
         }else{
             $this->main_content(array_merge(array(
                 "STATUS" => "ERROR", 
                 "MESSAGE" => "Enter account type"
             ), $_POST));
             exit;	
         }
		$list_com="select account_head_name_en,account_head_name_ta,a.account_head_id as account_head_id,account_headid,old_account_head_code,new_account_head_code,account_head_name_en from
        (select account_head_id,old_account_head_code,new_account_head_code,account_head_name_en,account_head_name_ta from accounts_master.m_account_head where del_flag is null  )a
        left join
		(select account_head_link_id,voucher_id ,account_type_id,account_headid from accounts_master.m_accounthead_link where  voucher_id=:voucher_id and account_type_id=:account_type_id and del_flag is null  )b
		on a.account_head_id=b.account_headid order by old_account_head_code asc"; 
        $set=$this->prepare($list_com,array(":voucher_id"=>$voucher_name,":account_type_id"=>$account_type),2);
		$count_val=count($set);
        $slno = 1;
		if ($count_val > 0) {
			foreach ($set as $row) {
			?>
                           <tr>
                                <td class="text-center"><?php echo htmlentities($slno); ?></td>
                                <td class="text-center"><?php echo htmlentities($row['old_account_head_code']); ?></td>
                                <td class="text-center"><?php echo htmlentities($row['new_account_head_code']); ?></td>
                                <td class="text-center"><?php echo htmlentities($row['account_head_name_en']); ?></td>
                                <td class="text-center"><?php echo htmlentities($row['account_head_name_ta']); ?></td>
                                <td>

                                    <input type="checkbox" class="checkboxall"
                                        id="account_head_link" name="account_head_link[]"
                                        value="<?php echo htmlentities($row['account_head_id']); ?>"
                                        <?php echo $row['account_headid']!=''?'checked="checked"':''; ?> />



                                </td>
                            </tr>
                            <?php
			
			$slno++;
		}
		
		?>
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
                   <div class="form-group row mt-5">
                                        <div class="col-sm-12 text-center">
                                            <input type="submit" id="btn_save" name="btn_save"
                                                value="<?php echo htmlentities($post_data_array['mode_name']); ?>"
                                                class="btn btn-sm <?php echo htmlentities($post_data_array['mode_class']); ?>" />
                                        </div>
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

        $this->Template("Template1", "Scheme Entry", $ob_output_main_forms, array(
            array(
                "name" => "Scheme Entry"
            )
        ));
        exit();
    }
	
	
	
	public function data_save($save_data)
    {
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
		
		
		$statecode=$this->getCurrentStateCode();
		$dcode=$this->getCurrentDistrictCode();
		$tpcode=$this->getCurrentLocalBodyCode();
	
	 if(isset($save_data['voucher_name']) && $save_data['voucher_name']!='')
         {
             $voucher_name=$save_data['voucher_name'];
             $voucher_name_Validation = $this->Field_Validation(
             array
             (
             'Field_Type'=>'number',
             'Field_Value'=>$voucher_name,
             'Field_Name'=>'voucher_name',
             'Field_Label_Name'=>'voucher name'
             )
             );
             
             if ($voucher_name_Validation['Status'] == "Error") {
                 $this->main_content(array_merge(array(
                     "STATUS" => "ERROR", 
                     "STATUS_TYPE" => "FIELD",
                     "FIELD_NAME" => "voucher_name",
                     "MESSAGE" => $voucher_name_Validation['Message']
                 ), $save_data));
                 exit;			
             }			
         }else{
             $this->main_content(array_merge(array(
                 "STATUS" => "ERROR", 
                 "MESSAGE" => "Enter voucher name"
             ), $save_data));
             exit;	
         }
 
         if(isset($save_data['account_type']) && $save_data['account_type']!='')
         {
             $account_type=$save_data['account_type'];
             $account_type_Validation = $this->Field_Validation(
             array
             (
             'Field_Type'=>'number',
             'Field_Value'=>$account_type,
             'Field_Name'=>'account_type',
             'Field_Label_Name'=>'account type'
             )
             );
             
             if ($account_type_Validation['Status'] == "Error") {
                 $this->main_content(array_merge(array(
                     "STATUS" => "ERROR", 
                     "STATUS_TYPE" => "FIELD",
                     "FIELD_NAME" => "account type",
                     "MESSAGE" => $account_type_Validation['Message']
                 ), $save_data));
                 exit;			
             }			
         }else{
             $this->main_content(array_merge(array(
                 "STATUS" => "ERROR", 
                 "MESSAGE" => "Enter account type"
             ), $save_data));
             exit;	
         }
        $account_head_link_cond = '{}';
    if(isset($save_data['account_head_link'])){
        $account_head_link_cond = '{' . implode(',', $save_data['account_head_link']) . '}';
    }
	$user_name = $this->getCurrentUser();
	$ip_address = $this->getIpAddress();
	$date = $this->getCurrentDate();
    $this->beginTransaction();
	$save_query1="select * from accounts_master.sp_accounthead_link(:voucher_name,:account_type,:account_head_link_cond,:user_name,:ip_address)";
               $res1=$this->prepare($save_query1,array(":voucher_name"=>$voucher_name,":account_type"=>$account_type,":user_name"=>$user_name,":ip_address"=>$ip_address,":account_head_link_cond"=>$account_head_link_cond),4); 
	

               if ($this->prepareStatus($res1) == true) {
				   $this->commit();
                $message='Data Linked Successfully.';
                ?>
<script>
alert('<?php echo htmlentities($message); ?>');
window.location.href = 'accounthead_link.php';
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

$scheme_district_link = new scheme_district_link();


if(!isset($_POST['cmd']))
{
	
	if(isset($_POST['btn_save']) && $_POST['btn_save']!='')
	{
		$scheme_district_link->data_save($_POST);
	}
	else
	{
		$scheme_district_link->main_content(array("mode_name" => "Save","mode_class" => "btn-success"));
	}
}
else
{
	try
    {
		$cmd=base64_decode($_POST['cmd']);
	
		if($cmd==1)
		{
	
			
			$scheme_groupname=base64_decode($_POST['scheme_groupname']);


		$sel_role_qry="select scheme_seq_id,scheme_group_code,scheme_name_en from master.m_scheme where scheme_group_code=:scheme_groupname and del_flag is null  "; 
		$sel_role_qry_res=$scheme_district_link->prepare($sel_role_qry,array(":scheme_groupname"=>$scheme_groupname),2);
       
			?>
<option value="">Select Scheme Name</option>
<?php	
			foreach($sel_role_qry_res as $sel_street_details_key=>$sel_street_details_row)
			{
			?>
<option value="<?php echo htmlentities($sel_street_details_row['scheme_seq_id']); ?>">
    <?php echo htmlentities($sel_street_details_row['scheme_name_en']); ?></option>
<?php
			}
			exit;
		}
		else
		{
			echo 'Invalid Request';
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