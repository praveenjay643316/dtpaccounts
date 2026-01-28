<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once  '../../config/config.php';

class DeletedChalanNoDetails  extends ConfigClass
{

    public $page_token = "Deleted_Chalan_No_Details";
    public function __construct()
    {
        if (!isset($this->db)) {
        }
    }

    public function main_content($post_data_array = array())
    {
        $site_data = $this->siteData();

       
            $post_data_array["mode_name"] = "Enter";
            $post_data_array["mode_class"] = "btn-success";

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
            

                $(document).on('change', '#VoucherTypeDetails', function() {
					
                    var VoucherTypeDetails = $("#VoucherTypeDetails").val();
					
					if(VoucherTypeDetails != ''){
						$.ajax({
							url: "DeletedChalanNoDetails.php",
							type: "post",
							data: {
								"VoucherTypeDetails": btoa(VoucherTypeDetails),
								"cmd": btoa(2)
							},
							success: function(data) {
								//alert(data);
								if (data != '') {
									
				                    	$('#VoucherTypeChalanNo').html(data);
								}
							},
							dataType: 'html'
						});
					}
					else{
						alert('Select Voucher Type Details');
					}
                });

                    $(document).on('click', "#btn_save", function() {
                        try {

                            if ($("#VoucherTypeChalanNo").val().length == '') {
                                throw {
                                    msg: "Select Voucher Type Chalan No",
                                    foc: "#VoucherTypeChalanNo"
                                }
                            }

                            if ($("#VoucherTypeDetails").val().length == '') {
                                throw {
                                    msg: "Select Voucher Type Details ",
                                    foc: "#VoucherTypeDetails"
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

      
        <form action="" method="post" class="" enctype="multipart/form-data">
            <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>" name="<?php echo htmlentities($this->page_token); ?>" value="<?php echo htmlentities($this->token($this->page_token)); ?>">
            <div class="card">
                <div class="card-body pl-5 pr-5">
                    <?php
                    if (isset($post_data_array["STATUS"])) {
                        echo $this->ShowMessage($post_data_array["STATUS"], $post_data_array["MESSAGE"]);
                        header("refresh: 3; url=DeletedChalanNoDetails.php");
                    }
                    ?>



                    <table class="table table-bordered m-0 p-0 tndtp_form_table">
                        <thead class="bg-th-form-dsg">
                            <tr>
                                <th align="center" scope="col" colspan="12">Deleted Chalan No Details</th>
                            </tr>


                        </thead>

                        <tbody>
                            <tr>
                                <td class="text-left font-weight-bold"><span DisplayLabelID="">Voucher Type</span></td>
                                <td >
                                       <select id="VoucherTypeDetails" name="VoucherTypeDetails" class="form-control form-control-sm w-50">
											<option value="">Choose</option>
											<?php
											   $sel_qry = "select voucher_type_id, voucher_type from accounts_master.voucher_type_new where del_flag is null and isactive=:isactive";
												$sel_qry_res=$this->prepare($sel_qry,array(":isactive"=>1),2);
												foreach($sel_qry_res as $sel_qry_key=>$sel_qry_row)
												{

                                                    
												?>
												<option value="<?php echo $sel_qry_row['voucher_type_id']; ?>"><?php echo $sel_qry_row['voucher_type']; ?></option>
												<?php
												}
												?>
									   </select>
                                </td>
                            </tr>
                            <tr>
                                <td  align="center" style="width:50%;"><span DisplayLabelID="">Voucher Chalan No</span></td>
                                <td >
                                     <select id="VoucherTypeChalanNo" name="VoucherTypeChalanNo" class="form-control form-control-sm w-50">
											<option value="">Choose</option>
									   </select>
                                </td>
                            </tr>
                            <tr>
                                <td  align="center" style="width:50%;"><span DisplayLabelID="">Remarks</span></td>
                                <td>
                                   <textarea class="form-control form-control-sm w-50" name="remarks" id="remarks"></textarea>  
                                </td>
                            </tr>
                            <tr align="center">
                                <td scope="row" colspan="2" align="center" class="text-center"> 
								
										<input type="submit" id="btn_save" name="btn_save" value="<?php echo htmlentities($post_data_array['mode_name']); ?>" class="btn btn-md text-white font-weight-bold <?php echo htmlentities($post_data_array['mode_class']); ?>" />         
										
                                </td>
                                
                            </tr>
                        </tbody>
                    </table>
                 </div>
            </div>

        </form>
        <?php

        // #############

        // PAGE CONTENT END

        // #############

        $ob_output_main_contents = ob_get_contents();
        ob_clean();
        $this->Template($this->getCurrentUserTemplate() != "" ? $this->getCurrentUserTemplate() : "Template1", "Deleted Chalan No Details", $ob_output_main_contents, array(), array('page_id' => 12));
    }

    public function data_save($save_data)
    {
        
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


			if (($this->getCurrentStateCode()  != '' )) {
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
			if (($this->getCurrentLocalBodyCode() != '')) {
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
            if (isset($save_data['VoucherTypeDetails'])) {
                $VoucherTypeDetails = $save_data['VoucherTypeDetails'];

                $VoucherTypeDetailsValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $VoucherTypeDetails,
                        'Field_Name' => 'VoucherTypeDetails',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => ' Voucher Type ',
                    )
                );

                if ($VoucherTypeDetailsValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "VoucherTypeDetails",
                        "MESSAGE" => $VoucherTypeDetailsValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['VoucherTypeChalanNo'])) {
                $VoucherTypeChalanNo = $save_data['VoucherTypeChalanNo'];

                $VoucherTypeChalanNoValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $VoucherTypeChalanNo,
                        'Field_Name' => 'VoucherTypeChalanNo',
                        //'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Voucher Type Chalan No',
                    )
                );

                if ($VoucherTypeChalanNoValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "VoucherTypeChalanNo",
                        "MESSAGE" => $VoucherTypeChalanNoValidation['Message']
                    ), $save_data));
                    exit;
                }
            }
 
             if (isset($save_data['remarks']) && $save_data['remarks']!='') {
                $remarks = $save_data['remarks'];
              
                $remarksValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $remarks,
                        'Field_Name' => 'remarks',
                        //'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Remarks',
                    )
                );

                if ($remarksValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "Remarks",
                        "MESSAGE" => $remarksValidation['Message']
                    ), $save_data));
                    exit;
                }
            }else{
				$remarks=NULL;
				}
          

        $Result_Message = "Data Saved SuccessFully";


        $this->beginTransaction();

         $sp_delchalannodetails="accounts_master.sp_delchalanno_details";
		
		 $getCurrentUser=$this->getCurrentUser();
		 $getIpAddress=$this->getIpAddress();
		 
		 
		 $sel_qry="select ".$sp_delchalannodetails."(:VoucherTypeDetails,:VoucherTypeChalanNo,:remarks,:getCurrentUser,now()::timestamp without time zone,:getIpAddress,:statecode,:dcode,:lbcode);";
		 $sel_qry_res=$this->prepare($sel_qry, array(":VoucherTypeDetails"=>$VoucherTypeDetails, ":VoucherTypeChalanNo"=>$VoucherTypeChalanNo, ":remarks"=>$remarks, ":getCurrentUser"=>$getCurrentUser, ":getIpAddress"=>$getIpAddress, ":statecode"=>$statecode, ":dcode"=>$dcode, ":lbcode"=>$lbcode),4);
		 

        $this->commit();

        if (!isset($sel_qry_res->errorInfo)) {
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

$DeletedChalanNoDetails = new DeletedChalanNoDetails();

if (!isset($_POST['cmd'])) {

    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        // print_r(array_merge($_POST, $_GET));exit();
        $DeletedChalanNoDetails->data_save(array_merge($_POST, $_GET));
    } else {
        $DeletedChalanNoDetails->main_content(array_merge(array("mode_name" => "Enter", "mode_class" => "btn-primary"), $_GET));
    }
} else if (isset($_POST['cmd'])) {
    $cmd = base64_decode($_POST['cmd']);

    if ($cmd == 2) {
		
        $VoucherTypeDetails = base64_decode($_POST['VoucherTypeDetails']);
		
		?>
        
		<option value="">Choose</option>
        <?php 
		$sel_chalan_no="select voucher_type_id, chalan_no from accounts_master.voucher_master where del_flag is null and isactive=1 and voucher_type_id=$VoucherTypeDetails";
		$sel_chalan_no_res = $DeletedChalanNoDetails->prepare($sel_chalan_no, array(), 2);
		
		foreach($sel_chalan_no_res as $sel_chalan_no_res_key=>$sel_chalan_no_res_row){?>
			<option value="<?php echo htmlentities($sel_chalan_no_res_row['chalan_no']);?>"><?php echo htmlentities($sel_chalan_no_res_row['chalan_no']);?></option>
		<?php }
		
        exit;
    }
}
?>