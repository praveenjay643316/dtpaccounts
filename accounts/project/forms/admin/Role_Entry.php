<?php
require_once __DIR__ . '/../../config/config.php';

class Role_Entry extends ConfigClass
{
    function __construct()
    {

        
    }

    public function main_form($data_array = array())
    {
        //var_dump($_SESSION);exit;
		ob_start();

      	if(!isset($data_array['mode_name'])){
			$data_array['mode_class']='btn-success';
			$data_array['mode_icon']='fa fa-floppy-o';
			$data_array['mode_name']='Save';
		}	

		$role_code=$_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'];

		

        // #############

        // PAGE CONTENT START

        // #############

        // PLACE YOUR CODE HERE
        ?>
<script type="text/javascript">
$(document).ready(function() {

    $(document).on('change', '#dcode', function() {

        if ($('#dcode').val() != '') {

            var dcode = $('#dcode').val();

            $.ajax({
                url: "Role_Entry.php",
                type: "post",
                data: {
                    "dcode": btoa(dcode),
                    "cmd": btoa(1)
                },
                success: function(data) {
                    if (data != '') {
                        $('#lbcode').html(data);
                    }
                },
                dataType: 'html'
            });
            return true;
        } else {
            alert('Select District Name');
            $('#lbcode').html('<option value="">Select Town panchayat</option>');
            return true;
        }

    });


   


    $(document).on('click', '#btn_save', function() {


        var Current_Field_id = $(this).attr('id');
        $('#' + Current_Field_id).hide();
        try {

            
            if ($("#role_name").val().length == '') {
                throw {
                    msg: "Enter Role Name",
                    foc: "#role_name"
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
});
</script>
<style>
  .newhead {
	  background: linear-gradient(to right, #494889, #3B3A7C, #494889)!important;
	  color: white!important;
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


    <div class="container">
    	<div class="card">
        <div class="card-body">
            <div class="col-lg-12 col-ml-12">
                <?php
			if (isset($data_array["STATUS"])) {
				echo $this->ShowMessage($data_array["STATUS"], $data_array["MESSAGE"]);
			}
			
			?>
                <form name="user_profile" action="" id="user_profile" method="post" autocomplete="off" class='fm-smt'>
                    <input class="form-control w-50 " type="hidden" id="profile_entry_token" name='profile_entry_token'
                        value="<?php echo htmlentities($this->token("profile_entry_token")); ?>">
                    <?php
			if (isset($data_array["mode"]) && $data_array["mode"] == "edit") {
				?>
                    <input class="form-control w-50 " type="hidden" id="profile_edit_id" name='profile_edit_id'
                        value="<?php echo htmlentities($data_array["profile_edit_id"]); ?>">
                    <?php
			 	$sel_form_data="select role_code,role_name from  
                    security.m_accounts_role  
                    where role_code=:role_code and del_flag is  null";
				$form_data = $this->prepare($sel_form_data,array(":role_code"=>$data_array["profile_edit_id"]),4);
				
			} else if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
				?>
                    <input class="form-control w-50 " type="hidden" id="profile_delete_id" name='profile_delete_id'
                        value="<?php echo htmlentities($data_array["profile_delete_id"]); ?>">
                    <?php
			 	$sel_form_data="select role_code,role_name from 
                    security.m_accounts_role  
                    where role_code=:role_code and del_flag is  null";
				$form_data = $this->prepare($sel_form_data,array(":role_code"=>$data_array["profile_delete_id"]),4);
			}
			?>
                    <table class="table table-bordered tndtp_form_table">
                        <thead>
                            <tr>
                                <td scope="col" colspan="2"  class="newhead">User Role Entry</td>
                            </tr>
                        </thead>
                        <tbody>

                            
                            <tr>
                                <td width="118" scope="col" class="w-50">Role Name</td>
                                <td width="144" scope="col">
                                    <?php
								if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
									if (isset($form_data['role_name']) && $form_data['role_name']!='') {
										echo htmlentities($form_data['role_name']);
									}
								} else {
									?>
                                    <input class="form-control w-50  Tax_Form_English_Ownername  form-control-sm"
                                        type="text" placeholder="Enter First Name" id="role_name" name='role_name'
                                        value="<?php if(isset($form_data)) { echo htmlentities($form_data['role_name']); }?>">
                                    <?php } ?>
                                </td>
                            </tr>
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" align="center">
                                    <?php 
                            if (isset($data_array["mode"]) && $data_array["mode"] == "delete") { ?>
                                    <button type="submit"
                                        class="btn <?php echo htmlentities($data_array["mode_class"]);?> btn-sm text-white"
                                        name="btn_save" id="btn_delete"><i
                                            class="<?php echo htmlentities($data_array["mode_icon"]);?> pr-1"
                                            aria-hidden="true"></i><?php echo htmlentities($data_array["mode_name"]);?></button>
                                    <?php }else{ ?>
                                    <button type="submit"
                                        class="btn <?php echo htmlentities($data_array["mode_class"]);?> btn-sm text-white"
                                        name="btn_save" id="btn_save"><i
                                            class="<?php echo htmlentities($data_array["mode_icon"]);?> pr-1"
                                            aria-hidden="true"></i> <?php echo htmlentities($data_array["mode_name"]);?></button>
                                    <?php } ?>
                                    &nbsp;
                                    <a class="btn btn-cancel btn-sm" href="Role_Entry.php"><i
                                            class="fa fa-eraser pr-1"></i> Clear</a>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </form>
            </div>
        </div>
    </div>
<div class="card">
    <div class="card-body">
        <div class="col-lg-12 col-ml-12">
            <h4 class="header-title"><a href="Role_Entry.php"
                    class="pull-right btn btn-sm btn-purple text-white"><i class="fa fa-plus-square p-1"
                        aria-hidden="true"></i>New</a>
            </h4>

            <table class="table table-bordered table-responsive tndtp_form_report_table" id="Result_table">
                <thead>
                    <tr>
                        <th scope="col" class="newhead">S.NO</th>
                        <th scope="col" class="newhead">Role Name</th>
                        
                        <th scope="col" class="newhead">Edit Action</th>
                        <th scope="col" class="newhead">Delete Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
					   $username = $this->getCurrentUser();


$office_con="";
$office_con_array=array();






	

		$list_com = "select role_code,role_name from  security.m_accounts_role where role_type='A' and del_flag is null order by role_code ASC ";
						
                        $set=$this->prepare($list_com,array(),2);


//}

						if(count($set)>0)
						{
							$slno = 1;
							foreach ($set as $row) { ?>

                    <tr>
                        <td><?php echo htmlentities($slno++); ?></td>
                     
                        <td align="left">
                            <?php echo isset($row['role_name']) ? htmlentities($row['role_name']) : ''; ?>
                        </td>
						

                        <td align="left"><a
                                href="?edit_id=<?php echo htmlentities(base64_encode($row['role_code'])); ?>"
                                class="btn btn-warning btn-sm"><i class="fa fa-pencil pr-1"
                                    aria-hidden="true"></i>Edit</a></td>
                        <td align="left"><a
                                href="?del_id=<?php echo htmlentities(base64_encode($row['role_code'])); ?>"
                                class="btn btn-danger btn-sm"><i class="fa fa-trash-o p-1"
                                    aria-hidden="true"></i>Delete</a></td>
                    </tr>


                    <?php }
						}?>
                </tbody>
                <?php if(count($set)==0){ ?>
                <tbody>
                    <td colspan="11" class="no_record">Record Not Found</td>
                </tbody>
                <?php } ?>
            </table>
</div>
        </div>
    </div>

<?php

        // #############

        // PAGE CONTENT END

        // #############

        $ob_output_main_forms = ob_get_contents();
        ob_clean();

        $this->Template("Template1", "User Profile", $ob_output_main_forms, array(
            array(
                "name" => "User Profile"
            )
        ));
        exit();
    }

    public function data_save($save_data)
    {
        //print_r($save_data);die;

		if (! $this->validateToken("profile_entry_token", $save_data["profile_entry_token"])) {
            $this->main_form(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "profile_entry_token",
                "MESSAGE" => "Invalid Token",
                "form_data" => $save_data
            ));
			exit;
        }		
		

      $role_name=NULL;
		
		if(!isset($save_data["profile_delete_id"]))
		{
			if(isset($save_data['role_name']) && $save_data['role_name']!='')
			{
				$role_name=$save_data['role_name'];
	
				$role_name_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'text_number_space',
				'Field_Value'=>$save_data['role_name'],
				'Field_Name'=>'role_name',
				//'Field_Max_length'=>'40',
				'Field_Label_Name'=>'First Name'
				)
				);
				
				if ($role_name_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR", 
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "role_name",
						"MESSAGE" => $role_name_Validation['Message']
					), $save_data));
				exit;			
				}			
			}
			
	
	
	
		}

	
		$state_code=$this->getCurrentStateCode();
	
		$tpcode=$this->getCurrentLocalBodyCode();
		$role_code=isset($_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'])?$_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code']:0; 
		$edit_id=isset($save_data["profile_edit_id"])?$save_data["profile_edit_id"]:0;
		$del_id=isset($save_data["profile_delete_id"])?$save_data["profile_delete_id"]:0;
        $userProfileSaveFunction = "security.sp_m_accounts_role_entry";
        $getCurrentUser = $this->getCurrentUser();
        $getIpAddress = $this->getIpAddress();


        // Save Part
		$users_save_query = '';
	

		$save_query = "select * from " . $userProfileSaveFunction . "(:role_name,:role_type,:getCurrentUser,:getIpAddress,:date,:edit_id,:del_id);"; 
		
        $res = $this->prepare($save_query,array(":role_name"=>$role_name,":role_type"=>'A',":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":date"=>'now()',":edit_id"=>$edit_id,":del_id"=>$del_id),4);
		
		 // print_r($res); exit;
		if (isset($res->errorInfo)) {
			$error_count[] = 1;
			}

        
			 
			 if (!isset($res->errorInfo)) {
				$this->commit();
				$this->main_form(array(
					"STATUS" => "SUCCESS",
					"STATUS_TYPE" => "FORM",
					"MESSAGE" => "Profile Saved SccessFully"
				));
			 }
			 else {
				$this->rollBack();
            $this->main_form(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Profile Saving Failed Due To Duplicate Entry"
            ));
        	}
        
		
    }
}

$Role_Entry = new Role_Entry();

if (!isset($_POST["cmd"])) {
if (isset($_POST["btn_save"])) {
    $Role_Entry->data_save($_POST);
	//print_r($_POST);die;
}
if (isset($_GET["edit_id"])) {
    $profile_edit_id = base64_decode($_GET["edit_id"]);
    $Role_Entry->main_form(array(
         "mode" => "edit",
        "mode_name" => "Update",
		"mode_class" => "btn-warning",
		"mode_icon" => "fa fa-pencil",
        "profile_edit_id" => $profile_edit_id
    ));
}
if (isset($_GET["del_id"])) {
    $profile_delete_id = base64_decode($_GET["del_id"]);
    $Role_Entry->main_form(array(
         "mode" => "delete",
        "mode_name" => "Delete",
		"mode_class" => "btn-danger",
		"mode_icon" => "fa fa-trash-o",
        "profile_delete_id" => $profile_delete_id
    ));
} else {
    $Role_Entry->main_form(array(
       "mode" => "save","mode_name" => "Save","mode_class" => "btn-success","mode_icon" => "fa fa-floppy-o"
    ));
}
}
else if (isset($_POST["cmd"])) {
	
	$cmd=base64_decode($_POST["cmd"]);
	if($cmd==1)
	{
		$dcode=base64_decode($_POST['dcode']);
	?>
<option value="" DisplayLabelID="255">Choose.</option>
<?php
		$sel_town_details="SELECT lbcode,lbody_name_en FROM master.m_localbodies WHERE  dcode=:dcode AND lbtype=:lbtype  and del_flag is null order by lbody_name_en asc";
			$sel_town_details_res=$Role_Entry->prepare($sel_town_details,array(":dcode"=>$dcode,":lbtype"=>'TP'),2);
	
		foreach($sel_town_details_res as $sel_town_details_key=>$sel_town_details_row)
		{
		?>
<option value="<?php echo htmlentities($sel_town_details_row['lbcode']); ?>">
    <?php echo htmlentities($sel_town_details_row['lbody_name_en']); ?></option>
<?php
		}
		exit;
	}
}
?>