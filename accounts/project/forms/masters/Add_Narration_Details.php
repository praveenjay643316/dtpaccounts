<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once  '../../config/config.php';

class AddNarrationDetails  extends ConfigClass
{

    public $page_token = "Add_Narration_Details";
    public function __construct()
    {
        if (!isset($this->db)) {
        }
    }

    public function main_content($post_data_array = array())
    {
        $site_data = $this->siteData();

       
		if (isset($post_data_array["edit_id"])) {
            $post_data_array["mode_name"] = "Update";
            $post_data_array["mode_class"] = "btn-warning";
        } else if (isset($post_data_array["delete_id"])) {
            $post_data_array["mode_name"] = "Delete";
            $post_data_array["mode_class"] = "btn-danger";
        } else{
            $post_data_array["mode_name"] = "Save";
            $post_data_array["mode_class"] = "btn-warning";
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
            

                    $(document).on('click', "#btn_save", function() {
                        try {

                            if ($("#voucher_id").val().length == '') {
                                throw {
                                    msg: "Select Transaction",
                                    foc: "#voucher_id"
                                }
                            }

                            if ($("#narration").val().length == '') {
                                throw {
                                    msg: "Enter Narration",
                                    foc: "#narration"
                                }
                            }
                            if ($("#address").val().length == '') {
                                throw {
                                    msg: "Enter Address",
                                    foc: "#address"
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
                
        </script>
  <?php
        if (isset($post_data_array["edit_id"]) || isset($post_data_array["delete_id"])) {
            if (isset($post_data_array["edit_id"])) {
                $narration_id = base64_decode($post_data_array["edit_id"]);

                $narration_id_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $narration_id,
                        'Field_Name' => 'edit_id',
                        'Field_Max_length' => '6',
                        'Field_Label_Name' => 'Edit ID',
                    )
                );
				 if ($narration_id_Validation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "narration_id",
                        "MESSAGE" => $narration_id_Validation['Message']
                    ), $post_data_array));
                    exit;
                }

                if ($narration_id_Validation['Status'] == "Error") {
                    echo 'Invalide Request';
                    exit;
                }
            } 
			else if (isset($post_data_array["delete_id"])) {
				$narration_id = base64_decode($post_data_array["delete_id"]);

                $narration_id_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $narration_id,
                        'Field_Name' => 'delete_id',
                        'Field_Max_length' => '6',
                        'Field_Label_Name' => 'delete_id',
                    )
                );
				 if ($narration_id_Validation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "narration_id",
                        "MESSAGE" => $narration_id_Validation['Message']
                    ), $post_data_array));
                    exit;
                }

                if ($narration_id_Validation['Status'] == "Error") {
                    echo 'Invalide Request';
                    exit;
                }
			}
			$sel_qry = "select narration_details_id, transaction, narration, address, voucher_type_en from accounts_master.narration_details as a  left join accounts_master.m_voucher_type as b on a.narration_details_id = b.voucher_id where narration_details_id=:narration_details_id and dcode=:dcode and lbcode=:lbcode and a.del_flag is null";
			$data_array_val=$this->prepare($sel_qry,array(":narration_details_id"=>$narration_id, ":dcode"=>$dcode, ":lbcode"=>$tpcode),4);
			
        }
        
        ?>
     <div class="container mt-3">  
        <form action="" method="post" class="" enctype="multipart/form-data">
            <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>" name="<?php echo htmlentities($this->page_token); ?>" value="<?php echo htmlentities($this->token($this->page_token)); ?>">
            <div class="card">
                <div class="card-body pl-5 pr-5">
                    <?php
                    if (isset($post_data_array["STATUS"])) {
                        echo $this->ShowMessage($post_data_array["STATUS"], $post_data_array["MESSAGE"]);
                    }
                    ?>
                    <table class="table table-bordered m-0 p-0 table-striped tndtp_report_table">
                        <thead class="bg-th-form-dsg">
                            <tr>
                                <th align="center" scope="col" colspan="2">Add Narration Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td  class="text-left font-weight-bold"><span>Voucher</span></td>
                                <td>
								<?php if (!isset($post_data_array['delete_id'])) {?>
                                       <select id="voucher_id" name="voucher_id" class="form-control form-control-sm w-50">
											<option value="">Choose</option>
                                            <?php
                                        $sel_account_code_id = "SELECT voucher_id,voucher_type_en FROM accounts_master.m_voucher_type where del_flag is null and isactive = 1 ORDER BY voucher_id ASC";

                                        $sel_account_codeid_res = $this->prepare($sel_account_code_id, array(), 2);

                                        foreach ($sel_account_codeid_res as $sel_account_codeid_key => $sel_account_codeid_row) {
                                        ?>
                                            <option value="<?php echo htmlentities($sel_account_codeid_row['voucher_id']); ?>">
                                                <?php echo htmlentities($sel_account_codeid_row['voucher_type_en']); ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <script>
                                        document.getElementById('voucher_id').value =
                                            '<?php if (isset($data_array_val['transaction'])) {
                                                    echo htmlentities($data_array_val['transaction']);
                                                } ?>';
                                    </script>
									
								<?php }else { 
								?>
									<input type="hidden" id="voucher_id" name="voucher_id" class="form-control form-control-sm w-50" value="<?php echo $data_array_val['transaction'] ;?>"/>
								<?php
								
									echo $data_array_val['voucher_type_en'] ;
								}								
										?>
								
								
                                </td>
                            </tr>
                            <tr>
                                <td  align="left" class="text-left w-50"><span>Narration</span></td>
                                <td>
								<?php if (!isset($post_data_array['delete_id'])) {?>
									<textarea rows="4" cols="50" name="narration" id="narration" class="form-control form-control-sm w-50" ><?php echo isset($data_array_val['narration'])? htmlentities($data_array_val['narration']):''; ?></textarea>
									<?php }else { 
                                        ?>
                                        <input type="hidden" id="narration" name="narration" class="form-control form-control-sm w-50" value="<?php echo $data_array_val['narration'] ;?>"/>
                                        <?php 
								        echo $data_array_val['narration'] ;
								    }								
										?>
                                </td>
                            </tr> 
							<tr>
                                <td align="left" class="text-left w-50"><span>Name And Address</span></td>
                                <td>
								<?php if (!isset($post_data_array['delete_id'])) {?>
									<textarea rows="4" cols="50" id="address" name="address" class="form-control form-control-sm w-50" ><?php echo isset($data_array_val['address'])? htmlentities($data_array_val['address']):''; ?></textarea>
									<?php }else { 
										?>
                                        <input type="hidden" id="address" name="address" class="form-control form-control-sm w-50" value="<?php echo $data_array_val['address'] ;?>"/>
                                        <?php 
										echo $data_array_val['address'] ;
									}								
									?>
                                </td>
                            </tr>
                         
                           <tr>
                                <td scope="row" colspan="2" align="center"> 
									<input type="submit" id="btn_save" name="btn_save" value="<?php echo htmlentities($post_data_array['mode_name']); ?>" class="btn btn-success btn-sm text-white  " />  
									<input type="button" id="btn_cancel" name="btn_cancel" value="Clear" class="btn btn-secondary btn-sm"/> 										
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            

                    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">
                     <span displaylabelid="939">Narration Details</span> </h4>
                        <table class="table table-bordered text-center table-striped tndtp_report_table" id="dataTable2">
                            <thead class="text-left">
                                <tr>
                                    <th scope="col"><span DisplayLabelID="">S.No	</span></th>
                                    <th scope="col"><span DisplayLabelID="">Voucher</span></th>
                                    <th scope="col"><span DisplayLabelID="">Narration</span></th>
                                    <th scope="col"><span DisplayLabelID="">Address</span></th>
                                    <th scope="col"><span DisplayLabelID="">Edit</span></th>
                                    <th scope="col"><span DisplayLabelID="">Delete</span></th>
                                </tr>
                            </thead>
                            <tbody id="tradedetails_data">
                                <?php
                               $sel_qry = "select narration_details_id, transaction, narration, address from accounts_master.narration_details where dcode=:dcode and lbcode=:lbcode and del_flag is null";
							    $sel_qry_res=$this->prepare($sel_qry,array(":dcode"=>$dcode,":lbcode"=>$tpcode),2);
								if(count($sel_qry_res)>0)
								{
									foreach($sel_qry_res as $sel_qry_key=>$sel_qry_row)
									{
                                ?>
                                        <tr>
                                            <td class="text-center"><?php echo htmlentities($sel_qry_key + 1); ?></td>
                                            <td class="text-left">
                                                <?php echo htmlentities($sel_qry_row['transaction']); ?>
                                            </td>
                                            <td class="text-left">
                                                <?php echo htmlentities($sel_qry_row['narration']); ?>
                                            </td>
                                            <td class="text-left">
                                                 <?php echo htmlentities($sel_qry_row['address']); ?>
											</td>
											 <td class="text-left">
                                               <a href="Add_Narration_Details.php?edit_id=<?php echo base64_encode(htmlentities($sel_qry_row['narration_details_id'])); ?>" class="btn btn-warning btn-sm"> Edit
											</td>
											 <td class="text-left">
                                               <a href="Add_Narration_Details.php?delete_id=<?php echo base64_encode(htmlentities($sel_qry_row['narration_details_id'])); ?>" class="btn btn-danger btn-sm"> Delete
											</td>
                                        </tr>
                                    <?php
                                   }
                                }  else {
                                    ?>
                                    <tr>
                                        <td align="center" colspan="6" style="color:#F00;" class="font-weight-bold">No Records Found
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
            </div>
        </form>
     </div>
        <?php

        // #############

        // PAGE CONTENT END

        // #############

        $ob_output_main_contents = ob_get_contents();
        ob_clean();
        $this->Template($this->getCurrentUserTemplate() != "" ? $this->getCurrentUserTemplate() : "Template1", "Add Narration Details", $ob_output_main_contents, array(), array('page_id' => 12));
    }

 
    public function data_save($save_data)
    {
        // var_dump($save_data);exit();
        // TOKEN VALIDATE
        // if (!$this->validateToken($this->page_token, $save_data[$this->page_token])) {
        //     $this->main_content(array_merge(array(
        //         "STATUS" => "ERROR",
        //         "STATUS_TYPE" => "FIELD",
        //         "FIELD_NAME" => $this->page_token,
        //         "MESSAGE" => "Invalid Token"
        //     ), $save_data));
        //     exit;
        // }

        $user_name = $this->getCurrentUser();
        $ip_address = $this->getIpAddress();
       
      	if (($this->getCurrentStateCode() != '')) {
                $statecode = $this->getCurrentStateCode();

                $statecode_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $statecode,
                        'Field_Name' => 'statecode',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => ' statecode ',
                    )
                );

                if ($statecode_Validation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "statecode",
                        "MESSAGE" => $statecode_Validation['Message']
                    ), $save_data));
                    exit;
                }
            }else{
                $statecode=33;
            }
			if (($this->getCurrentDistrictCode() != '')) {
                $dcode = $this->getCurrentDistrictCode();

                $dcode_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $dcode,
                        'Field_Name' => 'dcode',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => ' dcode ',
                    )
                );

                if ($dcode_Validation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "dcode",
                        "MESSAGE" => $dcode_Validation['Message']
                    ), $save_data));
                    exit;
                }
            }
			if (($this->getCurrentLocalBodyCode()  != '')) {
                $lbcode = $this->getCurrentLocalBodyCode();

                $lbcode_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $lbcode,
                        'Field_Name' => 'lbcode',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => ' lbcode ',
                    )
                );

                if ($lbcode_Validation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "lbcode",
                        "MESSAGE" => $lbcode_Validation['Message']
                    ), $save_data));
                    exit;
                }
            }

			if (isset($save_data['edit_id'])) {
                  $edit_id = isset($save_data['edit_id']) ? base64_decode($save_data['edit_id']) : 0;

                $edit_id_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $edit_id,
                        'Field_Name' => 'edit_id',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => ' edit_id ',
                    )
                );

                if ($edit_id_Validation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "edit_id",
                        "MESSAGE" => $edit_id_Validation['Message']
                    ), $save_data));
                    exit;
                }
            }
			else{
				$edit_id = 0;
			}
    if (isset($save_data['delete_id'])) {
                  $del_id = isset($save_data['delete_id']) ? base64_decode($save_data['delete_id']) : 0;

                $del_id_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $del_id,
                        'Field_Name' => 'del_id',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => ' del_id ',
                    )
                );

                if ($del_id_Validation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "del_id",
                        "MESSAGE" => $del_id_Validation['Message']
                    ), $save_data));
                    exit;
                }
            }
			else{
				$del_id = 0;
			}

            if (isset($save_data['voucher_id'])) {
                $voucher_id = $save_data['voucher_id'];

                $voucher_idValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $voucher_id,
                        'Field_Name' => 'voucher_id',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => ' voucher_id',
                    )
                );

                if ($voucher_idValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "voucher_id",
                        "MESSAGE" => $voucher_idValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['narration'])) {
                $narration = $save_data['narration'];

                $narration_enValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $narration,
                        'Field_Name' => 'narration',
                        'Field_Label_Name' => 'narration',
                    )
                );

                if ($narration_enValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "narration",
                        "MESSAGE" => $narration_enValidation['Message']
                    ), $save_data));
                    exit;
                }
            }


            if (isset($save_data['address'])) {
                $address = $save_data['address'];

                $addressValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $address,
                        'Field_Name' => 'address',
                        'Field_Max_length' => '60',
                        'Field_Label_Name' => 'address',
                    )
                );

                if ($addressValidation['Status'] == "Error") 
                {
                    $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "address",
                    "MESSAGE" => $addressValidation['Message']
                    ), $save_data));
                    exit;			
                } 
            }


        $Result_Message = "Data Saved SuccessFully"; 

        if ($edit_id > 0) {
            $Result_Message = "Data Updated SuccessFully";
        } else if ($del_id > 0) {
            $Result_Message = "Data Deleted SuccessFully";
        }

        $this->beginTransaction();
		$getCurrentUser = $this->getCurrentUser();
        $getIpAddress = $this->getIpAddress();

          if (isset($save_data["edit_id"])) {   
            $save_query = "select * from accounts_master.narration_details_entry (:transaction,:narration,:address,:statecode,:dcode,:lbcode,:ins_username,now()::timestamp without time zone,:ins_ipaddress::text,:edit_id,:del_id)";

            $res1 = $this->prepare($save_query, array(":transaction" => $voucher_id, ":narration" => $narration, ":address" => $address, ":statecode" => $statecode, ":dcode" => $dcode, ":lbcode" => $lbcode, ":ins_username" => $getCurrentUser, ":ins_ipaddress" => $getIpAddress, ":edit_id" => $edit_id, ":del_id" => $del_id),4);
        } else if (isset($save_data["del_id"])) {

            $save_query = "select * from accounts_master.narration_details_entry (:transaction,:narration,:address,:statecode,:dcode,:lbcode,:ins_username,now()::timestamp without time zone,:ins_ipaddress::text,:edit_id,:del_id";  
			    $res1 = $this->prepare($save_query, array(":transaction" => $voucher_id, ":narration" => $narration, ":address" => $address, ":statecode" => $statecode, ":dcode" => $dcode, ":lbcode" => $lbcode, ":ins_username" => $getCurrentUser, ":ins_ipaddress" => $getIpAddress, ":edit_id" => $edit_id, ":del_id" => $del_id),4);
        } else {
          
            $save_query = "select * from accounts_master.narration_details_entry (:transaction,:narration,:address,:statecode,:dcode,:lbcode,:ins_username,now()::timestamp without time zone,:ins_ipaddress::text,:edit_id,:del_id)";  
			    $res1 = $this->prepare($save_query, array(":transaction" => $voucher_id, ":narration" => $narration, ":address" => $address, ":statecode" => $statecode, ":dcode" => $dcode, ":lbcode" => $lbcode, ":ins_username" => $getCurrentUser, ":ins_ipaddress" => $getIpAddress, ":edit_id" => $edit_id, ":del_id" => $del_id),4);
        }
		
		


        if (!isset($res1->errorInfo)) {            
            $this->commit();
            $this->main_content(array(
                "STATUS" => "SUCCESS",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => $Result_Message
            ));
            exit;
        } else {
            $this->rollback();
            $this->main_content(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Data Save Failed Due To Duplicate Entry"
            ));
            exit;
        }
    }
}

$AddNarrationDetails = new AddNarrationDetails();

if (!isset($_POST['cmd'])) {

    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        // print_r(array_merge($_POST, $_GET));exit();
        $AddNarrationDetails->data_save(array_merge($_POST, $_GET));
    } else {
        $AddNarrationDetails->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
    }
} else if (isset($_POST['cmd'])) {
    $cmd = base64_decode($_POST['cmd']);

    
}
?>