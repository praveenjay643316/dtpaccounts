<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once  '../../config/config.php';

class BankDishonourCheque  extends ConfigClass
{

    public $page_token = "Bank_Dishonour_Cheque";
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
            

                $(document).on('change', '#fin_year', function() {

                    var fin_year = $("#fin_year").val();
					if(fin_year != ''){
						$.ajax({
							url: "BankDishonourCheque.php",
							type: "post",
							data: {
								"fin_year": btoa(fin_year),
								"cmd": btoa(2)
							},
							success: function(data) {
								if (data != '') {
									var Result_Data = JSON.parse(data);
									$('#ChalanNo').val(Result_Data['DATA']);
								}
							},
							dataType: 'html'
						});	
					}
					else{
						alert('Select Financial Year');
					}

                });
				
				
				 $(document).on('change', '#ChalanNo', function() {

                    var fin_year = $("#fin_year").val();
                    var ChalanNo = $("#ChalanNo").val();
					if(fin_year != '' && ChalanNo != ''){
						$.ajax({
							url: "BankDishonourCheque.php",
							type: "post",
							data: {
								"fin_year": btoa(fin_year),
								"ChalanNo": btoa(ChalanNo),
								"cmd": btoa(3)
							},
							success: function(data) {
								if (data != '') {
									var Result_Data = JSON.parse(data);
									//$('#ChalanNo').val(Result_Data['DATA']);
								}
							},
							dataType: 'html'
						});	
					}
					else if(ChalanNo == ''){
						alert('Select BPV Chalan No');
					}else if(fin_year == ''){
						alert('Select Financial Year');
					}

                });
				
				

                    $(document).on('click', "#btn_save", function() {
                        try {

                            if ($("#bank_code").val().length == '') {
                                throw {
                                    msg: "Select Bank Code",
                                    foc: "#bank_code"
                                }
                            }

                            if ($("#NewChequeNo").val().length == '') {
                                throw {
                                    msg: "Enter New Cheque Number",
                                    foc: "#NewChequeNo"
                                }
                            }

                            if ($("#NewDate").val().length == '') {
                                throw {
                                    msg: "Choose New Date",
                                    foc: "#NewDate"
                                }
                            }

                            if ($("#InFavour").val().length == '') {
                                throw {
                                    msg: "Eneter InFavour",
                                    foc: "#InFavour"
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
                        header("refresh: 3; url=BankDishonourCheque.php");
                    }
                    ?>



                    <table class="table table-bordered m-0 p-0 tndtp_form_table">
                        <thead class="bg-th-form-dsg">
                            <tr>
                                <th align="center" scope="col" colspan="12">Bank Dishonour of Cheque Details</th>
                            </tr>


                        </thead>

                        <tbody>
                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="">Financial Year</span></td>
                                <td >
                                       <select id="fin_year" name="fin_year" class="form-control form-control-sm">
											<option value="">Choose</option>
									   </select>
                                </td>
                           
                                <td  align="center" ><span DisplayLabelID="">BPV Chalan No</span></td>
                                <td >
								  <select id="ChalanNo" name="ChalanNo" class="form-control form-control-sm">
											<option value="">Choose</option>
									   </select>
                                </td>
                            </tr>
                            <tr align="center">
                                <td align="center" colspan="4" class="text-center"><span DisplayLabelID="">Old Cheque Details</span></td>
                            </tr>
                            <tr>
                                <td  ><span DisplayLabelID="">Chalan No</span></td>
                                <td >
                                    <input id="oldChalanNo" name="oldChalanNo" class="form-control form-control-sm"></input>
                                </td>
								<td ><span DisplayLabelID="">Date</span></td>
								<td >
									<input id="Date" name="Date" class="form-control form-control-sm"></input>
								</td>
							</tr>
							 <tr>
                                <td ><span DisplayLabelID="">Bank Code</span></td>
                                <td >
                                    <input id="Bank_Code" name="Bank_Code" class="form-control form-control-sm"></input>
                                </td>
								<td ><span DisplayLabelID="">Cheque No</span></td>
								<td >
								<input id="ChequeNo" name="ChequeNo" class="form-control form-control-sm"></input></td>
							</tr>
							 <tr>
                                <td><span DisplayLabelID="">Amount</span></td>
                                <td colspan="3">
                                    <input id="Amount" name="Amount" class="form-control form-control-sm w-50"></input>
                                </td>
							</tr>
							<tr>
								<td ><span DisplayLabelID="">In Favour</span></td>
								<td ">
								<input id="InFavour" name="InFavour" class="form-control form-control-sm"></input></td>
							</tr>
							  <tr align="center">
                                <td align="center" colspan="4" class="text-center"><span DisplayLabelID="">New Cheque Details</span></td>
                            </tr>
							<tr>
                                <td ><span DisplayLabelID="">Bank Code</span></td>
                                <td >
                                    <input id="New_Bank_Code" name="New_Bank_Code" class="form-control form-control-sm"></input>
                                </td>
								<td ><span DisplayLabelID="">Cheque No</span></td>
								<td >
								<input id="New_ChequeNo" name="New_ChequeNo" class="form-control form-control-sm"></input></td>
							</tr>
							<tr>
								<td  ><span DisplayLabelID="">Date</span></td>
								<td >
									<input id="New_Date" name="New_Date" class="form-control form-control-sm"></input>
								</td>
								<td ><span DisplayLabelID="">In Favour</span></td>
								<td  >
									<textarea name="New_InFavour" rows="2" cols="20" id="New_InFavour" class="form-control form-control-sm" ></textarea>
								</td>
							</tr>
								<tr>
                                <td scope="row" colspan="5" align="center" class="text-center"> 
								
										<input type="submit" id="btn_save" name="btn_save" value="<?php echo htmlentities($post_data_array['mode_name']); ?>" class="btn btn-md text-white font-weight-bold <?php echo htmlentities($post_data_array['mode_class']); ?>" />         
										<input type="button" id="btn_reset" name="btn_reset" value="Reset" class="btn btn-md text-white font-weight-bold btn-secondary" onclick="window.location='BankCheque.php'" />
										
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
        $this->Template($this->getCurrentUserTemplate() != "" ? $this->getCurrentUserTemplate() : "Template1", "Bank Dishonour of Cheque Details", $ob_output_main_contents, array(), array('page_id' => 12));
    }

 public function Bank_Details($post_data_array = array())
  {
	   $Bank_Code = base64_decode($post_data_array['Bank_Code']);
        $state_code = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $tpcode = $this->getCurrentLocalBodyCode();
	    ob_start();
		
		 $sel_Bank_Details= "";

        $sel_Bank_Details_res = $this->prepare($sel_Bank_Details, array(":state_code" => $state_code, ":dcode" => $dcode, ":tpcode" => $tpcode, ":isactive" => 1, ":Bank_Code" => $Bank_Code), 2);
		
		 if (count($sel_Bank_Details_res) > 0) {
            foreach ($sel_Bank_Details_res as $sel_Bank_Details_key => $sel_Bank_Details_row) {
				?>
				
				<option value=""> <?php echo htmlentities($sel_Bank_Details_row['']); ?> </option>
				<?php
			}
		 }
		 
		$ob_output_main_contents = ob_get_contents();
        ob_clean();
        return $ob_output_main_contents;
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


            if (isset($save_data['fin_year'])) {
                $fin_year = $save_data['fin_year'];

                $fin_yearValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'fin_year',
                        'Field_Value' => $fin_year,
                        'Field_Name' => 'fin_year',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => ' fin_year',
                    )
                );

                if ($fin_yearValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "fin_year",
                        "MESSAGE" => $fin_yearValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['ChalanNo'])) {
                $ChalanNo = $save_data['ChalanNo'];

                $ChalanNoValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $ChalanNo,
                        'Field_Name' => 'ChalanNo',
                        'Field_Label_Name' => 'ChalanNo',
                    )
                );

                if ($ChalanNoValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "ChalanNo",
                        "MESSAGE" => $ChalanNoValidation['Message']
                    ), $save_data));
                    exit;
                }
            }


            if (isset($save_data['oldChalanNo'])) {
                $oldChalanNo = $save_data['oldChalanNo'];

                $oldChalanNoValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $oldChalanNo,
                        'Field_Name' => 'oldChalanNo',
                        'Field_Max_length' => '60',
                        'Field_Label_Name' => 'old Chalan No',
                    )
                );

                if ($oldChalanNoValidation['Status'] == "Error") 
                {
                    $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "oldChalanNo",
                    "MESSAGE" => $oldChalanNoValidation['Message']
                    ), $save_data));
                    exit;			
                } 
            }


            if (isset($save_data['Date'])) {
                $Date = $save_data['Date'];

                $Date_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'Date',
                        'Field_Value' => $Date,
                        'Field_Name' => 'Date',
                        // 'Field_Max_length'=>'10',
                        'Field_Label_Name' => 'Cheq Date',
                    )
                );

                if ($Date_Validation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "Date",
                        "MESSAGE" => $Date_Validation['Message']
                    ), $save_data));
                    exit;
                }
            }


            if (isset($save_data['New_Date'])) {
                $New_Date = $save_data['New_Date'];

                $New_Date_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'Date',
                        'Field_Value' => $New_Date,
                        'Field_Name' => 'New_Date',
                        // 'Field_Max_length'=>'10',
                        'Field_Label_Name' => 'New Cheq Date',
                    )
                );

                if ($New_Date_Validation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "New_Date",
                        "MESSAGE" => $New_Date_Validation['Message']
                    ), $save_data));
                    exit;
                }
            }




            if (isset($save_data['Bank_Code'])) {
                $Bank_Code = $save_data['Bank_Code'];

                $Bank_CodeValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $Bank_Code,
                        'Field_Name' => 'Bank_Code',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => 'Bank Code',
                    )
                );

                if ($Bank_CodeValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "Bank_Code",
                        "MESSAGE" => $Bank_CodeValidation['Message']
                    ), $save_data));
                    exit;
                }
            }


            if (isset($save_data['New_Bank_Code'])) {
                $New_Bank_Code = $save_data['New_Bank_Code'];

                $New_Bank_CodeValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $New_Bank_Code,
                        'Field_Name' => 'New_Bank_Code',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => 'New Bank Code',
                    )
                );

                if ($New_Bank_CodeValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "New_Bank_Code",
                        "MESSAGE" => $New_Bank_CodeValidation['Message']
                    ), $save_data));
                    exit;
                }
            }


            if (isset($save_data['ChequeNo'])) {
                $ChequeNo = $save_data['ChequeNo'];

                $ChequeNoValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $ChequeNo,
                        'Field_Name' => 'ChequeNo',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => 'Cheque No',
                    )
                );

                if ($ChequeNoValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "ChequeNo",
                        "MESSAGE" => $ChequeNoValidation['Message']
                    ), $save_data));
                    exit;
                }
            }
            if (isset($save_data['New_ChequeNo'])) {
                $New_ChequeNo = $save_data['New_ChequeNo'];

                $NewChequeNoValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $New_ChequeNo,
                        'Field_Name' => 'New_ChequeNo',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => 'New Cheque No',
                    )
                );

                if ($NewChequeNoValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "New_ChequeNo",
                        "MESSAGE" => $NewChequeNoValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['Amount'])) {
                $Amount = $save_data['Amount'];

                $AmountValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $Amount,
                        'Field_Name' => 'Amount',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => 'Amount',
                    )
                );

                if ($AmountValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "Amount",
                        "MESSAGE" => $AmountValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['InFavour'])) {
                $InFavour = $save_data['InFavour'];

                $InFavourValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $InFavour,
                        'Field_Name' => 'InFavour',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => 'InFavour',
                    )
                );

                if ($InFavourValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "InFavour",
                        "MESSAGE" => $InFavourValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['New_InFavour'])) {
                $New_InFavour = $save_data['New_InFavour'];

                $New_InFavourValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $New_InFavour,
                        'Field_Name' => 'New_InFavour',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => 'New_InFavour',
                    )
                );

                if ($New_InFavourValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "New_InFavour",
                        "MESSAGE" => $New_InFavourValidation['Message']
                    ), $save_data));
                    exit;
                }
            }



        

        $Result_Message = "Data Saved SuccessFully";

        if ($edit_id > 0) {
            $Result_Message = "Data Updated SuccessFully";
        }

        $this->beginTransaction();

      

        if (isset($save_data["edit_id"])) {

          
        } else if (isset($save_data["del_id"])) {

           
        } else {
            
           
        }

        $this->commit();

        if (!isset($res1->errorInfo)) {
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

$BankDishonourCheque = new BankDishonourCheque();

if (!isset($_POST['cmd'])) {

    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        // print_r(array_merge($_POST, $_GET));exit();
        $BankDishonourCheque->data_save(array_merge($_POST, $_GET));
    } else {
        $BankDishonourCheque->main_content(array_merge(array("mode_name" => "Submit", "mode_class" => "btn-primary"), $_GET));
    }
} else if (isset($_POST['cmd'])) {
    $cmd = base64_decode($_POST['cmd']);

    if ($cmd == 2) {
        $fin_year = base64_decode($_POST['fin_year']);

        $Result['STATUS'] = 'SUCCESS';
        $Result['DATA'] = $BankDishonourCheque->Bank_Details($_POST);
        echo json_encode($Result);
        exit;
    } 
	if ($cmd == 3) {
        $fin_year = base64_decode($_POST['fin_year']);

        $Result['STATUS'] = 'SUCCESS';
        $Result['DATA'] = $BankDishonourCheque->Bank_Details($_POST);
        echo json_encode($Result);
        exit;
    }
}
?>