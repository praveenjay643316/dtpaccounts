<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once  '../../config/config.php';

class ChequeCancelDetails  extends ConfigClass
{

    public $page_token = "Cheque_Cancel_Details";
    public function __construct()
    {
        if (!isset($this->db)) {
        }
    }

    public function main_content($post_data_array = array())
    {
        $site_data = $this->siteData();

       
            $post_data_array["mode_name"] = "Submit";
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
                    $(document).on('click', "#btn_save", function() {
                       

                            if ($("#Bank_Code").val().length == '') {
                                throw {
                                    msg: "Select Bank Code",
                                    foc: "#Bank_Code"
                                }
                            }
							else{
								var bank_code = $("#Bank_Code").val();
								$.ajax({
									url: "ChequeCancelDetails.php",
									type: "post",
									data: {
										"bank_code": btoa(bank_code),
										"cmd": btoa(2)
									},
									success: function(result) {
										if (result != '') {
											$('#Result_data').html('');
											$('#Result_data').html(result);
										}
									},
									dataType: 'html'
								});
							}

                            return true;
                       
                    });
        </script>

      
        <style type="text/css">
            
			table.table-bordered > tbody > tr > td, table.table-bordered > tfoot > tr > td {
				width: auto!important;
			}
        </style>
        <form action="" method="post" class="" enctype="multipart/form-data">
            <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>" name="<?php echo htmlentities($this->page_token); ?>" value="<?php echo htmlentities($this->token($this->page_token)); ?>">
            <div class="card">
                <div class="card-body pl-5 pr-5">
                    <?php
                    if (isset($post_data_array["STATUS"])) {
                        echo $this->ShowMessage($post_data_array["STATUS"], $post_data_array["MESSAGE"]);
                        header("refresh: 3; url=ChequeCancelDetails.php");
                    }
                    ?>



                    <table class="table table-bordered m-0 p-0 tndtp_form_table">
                        <thead class="bg-th-form-dsg">
                            <tr>
                                <th align="center" scope="col" colspan="12">Cheque Cancel Details</th>
                            </tr>


                        </thead>

                        <tbody>
                            <tr>
                                <td class="text-left font-weight-bold"><span DisplayLabelID="">Bank Code</span></td>
                                <td >
                                       <select id="Bank_Code" name="Bank_Code" class="form-control form-control-sm w-50">
											<option value="">Choose</option>
											<?php
											   $sel_qry = "select bank_id , bank_code, bank_name_en from accounts_master.bank_new where del_flag is null and isactive = 1";
												$sel_qry_res=$this->prepare($sel_qry,array(),2);
												foreach($sel_qry_res as $sel_qry_key=>$sel_qry_row)
												{
											?>
												<option value="<?php echo htmlentities($sel_qry_row['bank_id']);?>"><?php echo htmlentities($sel_qry_row['bank_code']);?></option>
													
											<?php }?>
									   </select>
                                </td>
                            </tr>

                            <tr align="center">
                                <td scope="row" colspan="2" align="center" class="text-center"> 
								
										<input type="button" id="btn_save" name="btn_save" value="<?php echo htmlentities($post_data_array['mode_name']); ?>" class="btn btn-md text-white font-weight-bold <?php echo htmlentities($post_data_array['mode_class']); ?>" /> 
										<input type="button" id="btn_reset" name="btn_reset" value="Cancel" class="btn btn-md text-white font-weight-bold btn-secondary" onclick="window.location='ChequeCancelDetails.php'" />										
										
                                </td>
                                
                            </tr>
                        </tbody>
                    </table>



                </div>
				   <div class="card">
                <div class="card-body pl-5 pr-5" pt-3>
				<div id="Result_data">
							
				</div>
            </div></div>
            </div>

        </form>
        <?php

        // #############

        // PAGE CONTENT END

        // #############

        $ob_output_main_contents = ob_get_contents();
        ob_clean();
        $this->Template($this->getCurrentUserTemplate() != "" ? $this->getCurrentUserTemplate() : "Template1", "Cheque Cancel Details", $ob_output_main_contents, array(), array('page_id' => 12));
    }

 

   
}

$ChequeCancelDetails = new ChequeCancelDetails();

if (!isset($_POST['cmd'])) {

    //if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
     //   $ChequeCancelDetails->data_save(array_merge($_POST, $_GET));
    //} else {
        $ChequeCancelDetails->main_content(array_merge(array("mode_name" => "Generate", "mode_class" => "btn-primary"), $_GET));
   // }
} 
else{
	if(base64_decode($_POST['cmd']) == 2)
	{
       
  ob_start();
			
			if (($ChequeCancelDetails->getCurrentDistrictCode() != '')) {
                $dcode = $ChequeCancelDetails->getCurrentDistrictCode();

                $dcode_Validation = $ChequeCancelDetails->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $dcode,
                        'Field_Name' => 'dcode',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => ' dcode ',
                    )
                );

                if ($dcode_Validation['Status'] == "Error") {
                    $ChequeCancelDetails->main_content(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "dcode",
                        "MESSAGE" => $dcode_Validation['Message']
                    ));
                    exit;
                }
            }
			if (($ChequeCancelDetails->getCurrentLocalBodyCode() != '')) {
                $lbcode = $ChequeCancelDetails->getCurrentLocalBodyCode();

                $lbcode_Validation = $ChequeCancelDetails->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $lbcode,
                        'Field_Name' => 'lbcode',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => ' lbcode ',
                    )
                );

                if ($lbcode_Validation['Status'] == "Error") {
                    $ChequeCancelDetails->main_content(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "lbcode",
                        "MESSAGE" => $lbcode_Validation['Message']
                    ));
                    exit;
                }
            }
            if (isset($_POST['bank_code'])) {
                $Bank_Code = base64_decode($_POST['bank_code']);

                $Bank_CodeValidation = $ChequeCancelDetails->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $Bank_Code,
                        'Field_Name' => 'Bank_Code',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => 'Bank Code ',
                    )
                );

                if ($Bank_CodeValidation['Status'] == "Error") {
                    $ChequeCancelDetails->main_content(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "Bank_Code",
                        "MESSAGE" => $Bank_CodeValidation['Message']
                    ));
                    exit;
                }
            }

            

        $Result_Message = "Data Generated SuccessFully";

            
            $save_query = "select bank_code, bank_name, cheque_no, remarks, ins_date from accounts_master.bank_cheque_cancel where bank_code=:bank_code and dcode=:dcode and lbcode=:lbcode";

            $res1 = $ChequeCancelDetails->prepare($save_query, array(":bank_code" => $Bank_Code, ":dcode" => $dcode, ":lbcode" => $lbcode),2);
			
			?>
			   <table class="table table-bordered m-0 p-0 tndtp_form_table">
                        <thead class="bg-th-form-dsg">
                            <tr>
                                <th align="center">Sl.No</th>
                                <th align="center">Bank Code</th>
                                <th align="center">Bank Name</th>
                                <th align="center">Cheque Number</th>
                                <th align="center">Remarks</th>
                                <th align="center">Cancel Date</th>
                            </tr>
                        </thead>
                        <tbody>
						<?php
							if(count($res1) > 0){
								foreach($res1 as $key=>$row)
								{
									?>
									<tr>
									<td align="center"><?php echo htmlentities($key + 1); ?></td>
									<td align="center"><?php echo htmlentities($row['bank_code']); ?></td>
									<td align="center"><?php echo htmlentities($row['bank_name']); ?></td>
									<td align="center"><?php echo htmlentities($row['cheque_no']); ?></td>
									<td align="center"><?php echo htmlentities($row['remarks']); ?></td>
									<td align="center"><?php echo htmlentities(date("d-m-Y", strtotime($row['ins_date']))); ?></td>
									</tr>
								<?php
								}
							}else{?>
							<tr>
									<td align="center"  colspan="6" class="text-center">No Records Found.</td>
									</tr>
							
							<?php
								
							}
						?>
						</tbody>
						
				</table>
				<?php
			 $ob_output_main_contents = ob_get_contents();
			ob_clean();
			echo $ob_output_main_contents;
			exit;
	}
    
}
?>