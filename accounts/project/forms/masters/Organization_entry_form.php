<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once  '../../config/config.php';

class Organization_Details  extends ConfigClass
{

    public $page_token = "Organization_Details";
    public function __construct()
    {
        if (!isset($this->db)) {
        }
    }

    public function main_content($post_data_array = array())
    {
        $site_data = $this->siteData();

       
        if (!isset($post_data_array["edit_id"]) && !isset($post_data_array["del_id"])) {
            $post_data_array["mode_name"] = "Save";
            $post_data_array["mode_class"] = "btn-success";
        } else if (isset($post_data_array["edit_id"])) {
            $post_data_array["mode_name"] = "Update";
            $post_data_array["mode_class"] = "btn-warning";
        } else if (isset($post_data_array["del_id"])) {
            $post_data_array["mode_name"] = "Delete";
            $post_data_array["mode_class"] = "btn-danger";
        }

        ob_start();

        // #############

        // PAGE CONTENT START

        // #############

?>
        <input type="hidden" id="page_lable_id" name="page_lable_id" value="38" />

        <?php

        $state_code = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $tpcode = $this->getCurrentLocalBodyCode();



        $lang_code_2d = $this->getCurrentUserLanguage2D();

        ?>
        <script type="text/javascript">
                   <?php if (!isset($post_data_array["del_id"])) { ?>
                    $(document).on('click', "#btn_save", function() {
                        try {

                            if ($("#organization_name_en").val().length == '') {
                                throw {
                                    msg: "Select Voucher Type",
                                    foc: "#organization_name_en"
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
                <?php }?>
          //  });
        </script>
 <?php
        if (isset($post_data_array["edit_id"]) || isset($post_data_array["del_id"])) {
            if (isset($post_data_array["edit_id"])) {
                $organization_id = base64_decode($post_data_array["edit_id"]);

                $organization_idValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $organization_id,
                        'Field_Name' => 'edit_id',
                        'Field_Max_length' => '6',
                        'Field_Label_Name' => 'Edit ID',
                    )
                );

                if ($organization_idValidation['Status'] == "Error") {
                    echo 'Invalide Request';
                    exit;
                }
            } else if (isset($post_data_array["del_id"])) {
                $organization_id = base64_decode($post_data_array["del_id"]);

                $organization_idValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $organization_id,
                        'Field_Name' => 'del_id',
                        'Field_Max_length' => '6',
                        'Field_Label_Name' => 'Delete ID',
                    )
                );

                if ($organization_idValidation['Status'] == "Error") {
                    echo 'Invalide Request';
                    exit;
                }
            }

            $sel_exemption_cat_data_upd_details = "SELECT organization_id,organization_name_en,organization_name_ta FROM accounts_master.organization_lists WHERE  organization_id=:organization_id";
            $data_array_val = $this->prepare($sel_exemption_cat_data_upd_details, array(":organization_id" => $organization_id), 4);
            // var_dump($data_array_val);exit;
        }

        ?>
      <div class="container pt-3"> 
        <form action="" method="post" class="" enctype="multipart/form-data">
            <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>" name="<?php echo htmlentities($this->page_token); ?>" value="<?php echo htmlentities($this->token($this->page_token)); ?>">
                <?php
                    if (isset($post_data_array["STATUS"])) {
                        echo $this->ShowMessage($post_data_array["STATUS"], $post_data_array["MESSAGE"]);
                        header("refresh: 2; url=Organization_entry_form.php");
                    }
                    ?>

                    <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                   <table class="table table-bordered m-0 p-0 tndtp_form_table">
                        <thead class="bg-th-form-dsg">
                            <tr>
                                <th align="center" scope="col" colspan="12">Organization Entry Form</th>
                            </tr>


                        </thead>

                        <tbody>
                            <tr>
                                <td class="text-left font-weight-bold"><span DisplayLabelID="">Organization Name in English</span></td>
                                <td>
                                       <input type="text" name="organization_name_en" id="organization_name_en" class="form-control form-control-sm w-50 Tax_Form_English_Ownername_Property_Tax" value="<?php if (isset($data_array_val['organization_name_en'])) { echo htmlentities($data_array_val['organization_name_en']); } ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left font-weight-bold" style="width:50%;"><span DisplayLabelID="">Organization Name in Tamil</span></td>
                                <td>
                                     <input type="text" name="organization_name_ta" id="organization_name_ta" class="form-control form-control-sm w-50 Tax_Form_Tamil_Ownername"  value="<?php if (isset($data_array_val['organization_name_ta'])) { echo htmlentities($data_array_val['organization_name_ta']); } ?>"/>
                                </td>
                            </tr>
                           

                            <tr align="center">
                                <td scope="row" colspan="2" align="center" class="text-center"> 
										<input type="submit" id="btn_save" name="btn_save" value="<?php echo htmlentities($post_data_array['mode_name']); ?>" class="btn btn-md text-white font-weight-bold <?php echo htmlentities($post_data_array['mode_class']); ?>" />  
                                        <input type="button" id="btn_reset" name="btn_reset" value="Cancel" class="btn btn-md text-white font-weight-bold btn-secondary" onclick="window.location='Voucher_type_entry_form.php'" />       
										
                                </td>
                                
                            </tr>
                        </tbody>
                    </table>



                </div>
            </div>
            </div>
            <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-body" >

                    <div class="single-table">
                        <table class="table table-bordered text-center table-striped tndtp_report_table" id="dataTable2">
                            <thead class="text-left">

                                <tr>
                                    <th scope="col"><span DisplayLabelID="311">S.No</span></th>
                                    <th scope="col"><span DisplayLabelID="186">Organation Name English</span></th>
                                    <th scope="col"><span DisplayLabelID="186">Organation Name Tamil</span></th>
                                    <th scope="col"><span DisplayLabelID="354">Action</span></th>
                                </tr>
                            </thead>
                            <tbody id="tradedetails_data">
                                <?php
                                $sel_vouchermaster_details = "select organization_id as edit_id, organization_name_en, organization_name_ta from accounts_master.organization_lists where isactive=:isactive and del_flag IS NULL order by organization_id";

                                $sel_vouchermaster_details_res = $this->prepare($sel_vouchermaster_details, array(":isactive" => 1), 2);
                                // var_dump($sel_vouchermaster_details_res);exit();

                                if (count($sel_vouchermaster_details_res) > 0) {
                                    foreach ($sel_vouchermaster_details_res as $sel_vouchermaster_details_key => $sel_vouchermaster_details_row) {
                                ?>
                                        <tr>
                                            <td class="text-center"><?php echo htmlentities($sel_vouchermaster_details_key + 1); ?></td>
                                           
                                            <td class="text-left">
                                                <?php echo htmlentities($sel_vouchermaster_details_row['organization_name_en']); ?>
                                            </td>
                                             <td class="text-left">
                                                <?php echo htmlentities($sel_vouchermaster_details_row['organization_name_ta']); ?>
                                            </td>
                                         
                                            <td align="center"><a href="?edit_id=<?php echo htmlentities(base64_encode($sel_vouchermaster_details_row['edit_id'])); ?>" class="btn btn-warning btn-sm"><?php /* ?><i class="fa fa-pencil pr-1"
                                        aria-hidden="true"></i><?php */ ?>Edit</a>
                                                <a href="?del_id=<?php echo htmlentities(base64_encode($sel_vouchermaster_details_row['edit_id'])); ?>" class="btn btn-danger btn-sm">Delete</a>
                                            </td>

                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td align="center" colspan="6" style="color:#F00;" class="font-weight-bold">No Record Found
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
         </div>   

        </form>
    </div>
        <?php

        // #############

        // PAGE CONTENT END

        // #############

        $ob_output_main_contents = ob_get_contents();
        ob_clean();
        $this->Template($this->getCurrentUserTemplate() != "" ? $this->getCurrentUserTemplate() : "Template1", "Organization Details", $ob_output_main_contents, array(), array('page_id' => 12));
    }

 
   public function data_save($save_data)
    {
		
        // var_dump($save_data);exit();
        // TOKEN VALIDATE
        if (!$this->validateToken($this->page_token, $save_data[$this->page_token])) {
            $this->main_content(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => $this->page_token,
                "MESSAGE" => "Invalid Token"
            ), $save_data));
            exit;
        }


        $statecode = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();


        $edit_id = isset($save_data['edit_id']) ? base64_decode($save_data['edit_id']) : 0;
        $del_id = isset($save_data['del_id']) ? base64_decode($save_data['del_id']) : 0;

       // if ($del_id == 0) {

            if (isset($save_data['organization_name_en'])) {
                $organization_name_en = $save_data['organization_name_en'];

                $organization_name_enValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $organization_name_en,
                        'Field_Name' => 'organization_name_en',
                        // 'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Invalid Voucher Type',
                    )
                );

                if ($organization_name_enValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "organization_name_en",
                        "MESSAGE" => $organization_name_enValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['organization_name_ta']) && $save_data['organization_name_ta']) {
                $organization_name_ta = $save_data['organization_name_ta'];

                /* $organization_name_taValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $organization_name_ta,
                        'Field_Name' => 'organization_name_ta',
                        // 'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Invalid voucher type',
                    )
                );

                if ($organization_name_taValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "voucher_type_ta",
                        "MESSAGE" => $organization_name_taValidation['Message']
                    ), $save_data));
                    exit;
                } */
            }
             else{
				$organization_name_ta="";
			}
      //  }

      
       
	   $Result_Message = "Data Saved SuccessFully"; 

        if ($edit_id > 0) {
            $Result_Message = "Data Updated SuccessFully";
        } else if ($del_id > 0) {
            $Result_Message = "Data Deleted SuccessFully";
        }

        $this->beginTransaction();

      $VoucherTypeFunction = "accounts_master.sp_organization_entry";
        $getCurrentUser = $this->getCurrentUser();
        $getIpAddress = $this->getIpAddress();
        //$date = $this->getCurrentDate();



        if (isset($save_data["edit_id"])) {

            $save_query = "select " . $VoucherTypeFunction . "(:organization_name_en,:organization_name_ta,:getCurrentUser,now()::timestamp without time zone,:getIpAddress,:edit_id,:del_id);";  
			   $res = $this->prepare($save_query,array(":organization_name_en"=>$organization_name_en,":organization_name_ta"=>$organization_name_ta,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>$edit_id, ":del_id" => $del_id),4);
        } else if (isset($save_data["del_id"])) {

            $save_query = "select " . $VoucherTypeFunction . "(:organization_name_en,:organization_name_ta,:getCurrentUser,now()::timestamp without time zone,:getIpAddress,:edit_id,:del_id);";  
			 $res = $this->prepare($save_query,array(":organization_name_en"=>$organization_name_en,":organization_name_ta"=>$organization_name_ta,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>$edit_id,":del_id"=>$del_id),4);
        } else {
          
            $save_query = "select " . $VoucherTypeFunction . "(:organization_name_en,:organization_name_ta,:getCurrentUser,now()::timestamp without time zone,:getIpAddress,:edit_id,:del_id);"; 
			   
			   $res = $this->prepare($save_query,array(":organization_name_en"=>$organization_name_en,":organization_name_ta"=>$organization_name_ta,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>$edit_id,":del_id"=>$del_id),4);  
            // var_dump($res);exit();
        }

        $this->commit();

        if (!isset($res->errorInfo)) {
            $this->main_content(array(
                "STATUS" => "SUCCESS",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => $Result_Message
            ));
            exit;
        } else {
            $this->main_content(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Data Save Failed Due To Duplicate Entry"
            ));
            exit;
        }
    
		}
}

$Organization_Details = new Organization_Details();

if (!isset($_POST['cmd'])) {

    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        //print_r(array_merge($_POST, $_GET));exit();
        $Organization_Details->data_save(array_merge($_POST, $_GET));
    } else {
        $Organization_Details->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
    }
}
?>