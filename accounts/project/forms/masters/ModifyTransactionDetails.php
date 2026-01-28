<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once  '../../config/config.php';

class ModifyTransactionDetails  extends ConfigClass
{

    public $page_token = "Modify_Transaction_Details";
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
            

                $(document).on('click', 'input[name="Modify"]', function() {

                    var bank_code = $('input[name="Modify"]').val();
					if(bank_code != ''){
						
					}
					else{
						alert('Select Transaction');
					}

                });

                    $(document).on('click', "#btn_save", function() {
                        try {

                            if ($('input[name="Modify"]').val().length == '') {
                                throw {
                                    msg: "Select Transaction",
                                    foc: "#Modify"
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
                        header("refresh: 3; url=ModifyTransactionDetails.php");
                    }
                    ?>



                    <table class="table table-bordered m-0 p-0 tndtp_form_table">
                        <thead class="bg-th-form-dsg">
                            <tr>
                                <th align="center" scope="col" colspan="12">Modify Transaction Details</th>
                            </tr>


                        </thead>

                        <tbody>
                            <tr>
                                <td  class="text-left font-weight-bold">
										   <div class="custom-control custom-radio custom-control-inline">
												<input type="radio" id="customRadio1" name="Modify" value="1"
													class="custom-control-input">
												<label class="custom-control-label" for="customRadio1"><span
														DisplayLabelID="">Triplicate Chalan</span></label>
											</div> 
								</td>
                                <td >
                                       <div class="custom-control custom-radio custom-control-inline">
												<input type="radio" id="customRadio2" name="Modify" value="2"
													class="custom-control-input">
												<label class="custom-control-label" for="customRadio2"><span
														DisplayLabelID="">Bank Payment Voucher</span></label>
											</div> 
                                </td>
                            </tr>
                            <tr>
                                <td  align="center" style="width:50%;">
									 <div class="custom-control custom-radio custom-control-inline">
												<input type="radio" id="customRadio3" name="Modify" value="3"
													class="custom-control-input">
												<label class="custom-control-label" for="customRadio3"><span
														DisplayLabelID="">Bank Receipt Voucher</span></label>
											</div> 
								</td>
                                <td >
                                     <div class="custom-control custom-radio custom-control-inline">
												<input type="radio" id="customRadio4" name="Modify" value="4"
													class="custom-control-input">
												<label class="custom-control-label" for="customRadio4"><span
														DisplayLabelID="">Contract Journal Voucher</span></label>
											</div> 
                                </td>
                            </tr> 
							<tr>
                                <td  align="center" style="width:50%;">
									 <div class="custom-control custom-radio custom-control-inline">
												<input type="radio" id="customRadio5" name="Modify" value="5"
													class="custom-control-input">
												<label class="custom-control-label" for="customRadio5"><span
														DisplayLabelID="">Expenses Journal Voucher</span></label>
											</div> 
								</td>
                                <td >
                                     <div class="custom-control custom-radio custom-control-inline">
												<input type="radio" id="customRadio6" name="Modify" value="6"
													class="custom-control-input">
												<label class="custom-control-label" for="customRadio6"><span
														DisplayLabelID="">Purchase Journal Voucher</span></label>
											</div> 
                                </td>
                            </tr>
                            <tr>
                                <td align="center" >
									 <div class="custom-control custom-radio custom-control-inline">
												<input type="radio" id="customRadio7" name="Modify" value="7"
													class="custom-control-input">
												<label class="custom-control-label" for="customRadio7"><span
														DisplayLabelID="">General Journal Voucher</span></label>
											</div> 
								</td>
                                <td>
                                     <div class="custom-control custom-radio custom-control-inline">
												<input type="radio" id="customRadio8" name="Modify" value="8"
													class="custom-control-input">
												<label class="custom-control-label" for="customRadio8"><span
														DisplayLabelID="">Inter Bank Transfer</span></label>
											</div> 
                                </td>
                            </tr>
                            <tr>
                                <td align="center" >
									 <div class="custom-control custom-radio custom-control-inline">
												<input type="radio" id="customRadio9" name="Modify" value="9"
													class="custom-control-input">
												<label class="custom-control-label" for="customRadio9"><span
														DisplayLabelID="">Advance Register</span></label>
											</div> 
								</td>
                                <td>
                                     <div class="custom-control custom-radio custom-control-inline">
												<input type="radio" id="customRadio10" name="Modify" value="10"
													class="custom-control-input">
												<label class="custom-control-label" for="customRadio10"><span
														DisplayLabelID="">Deposit Register</span></label>
											</div> 
                                </td>
                            </tr>
                            <tr>
                                <td align="center" >
									 <div class="custom-control custom-radio custom-control-inline">
												<input type="radio" id="customRadio11" name="Modify" value="11"
													class="custom-control-input">
												<label class="custom-control-label" for="customRadio11"><span
														DisplayLabelID="">Adjust BRV</span></label>
											</div> 
								</td>
                                <td>
                                     <div class="custom-control custom-radio custom-control-inline">
												<input type="radio" id="customRadio12" name="Modify" value="12"
													class="custom-control-input">
												<label class="custom-control-label" for="customRadio12"><span
														DisplayLabelID="">Adjust Triplicate Chalan</span></label>
											</div> 
                                </td>
                            </tr>
                            <tr>
                                <td align="center" >
									 <div class="custom-control custom-radio custom-control-inline">
												<input type="radio" id="customRadio13" name="Modify" value="13"
													class="custom-control-input">
												<label class="custom-control-label" for="customRadio13"><span
														DisplayLabelID="">Triplicate Cheque/DD No</span></label>
											</div> 
								</td>
                                <td>
                                     <div class="custom-control custom-radio custom-control-inline">
												<input type="radio" id="customRadio14" name="Modify" value="14"
													class="custom-control-input">
												<label class="custom-control-label" for="customRadio14"><span
														DisplayLabelID="">Adjust Triplicate Cheque/DD No</span></label>
											</div> 
                                </td>
                            </tr>
                           <tr align="center">
                                <td scope="row" colspan="5" align="center" class="text-center"> 
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
        $this->Template($this->getCurrentUserTemplate() != "" ? $this->getCurrentUserTemplate() : "Template1", "Modify Transaction Details", $ob_output_main_contents, array(), array('page_id' => 12));
    }



    public function data_save($save_data)
    {
     
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

			

            if (isset($save_data['Modify'])) {
                $Transaction = $save_data['Modify'];

                $TransactionValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $Transaction,
                        'Field_Name' => 'Transaction',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => ' Transaction',
                    )
                );

                if ($TransactionValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "Transaction",
                        "MESSAGE" => $TransactionValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

          

        

        $Result_Message = "Data Saved SuccessFully";

       
        $this->beginTransaction();

      

            
            $save_query = "select * from " . $pp_assessment_initiation . "(:statecode,:dcode,:lbcode,:licencetypeid,:trade_name_en,:trade_name_ta,:fin_year,:traderate,:lb_tradecode,:isactive,:user_name,:ip_address,:edit_id,:del_id)";

            $res1 = $this->prepare($save_query, array(":statecode" => $statecode, ":dcode" => $dcode, ":lbcode" => $lbcode, ":licencetypeid" => $licencetypeid, ":trade_name_en" => $trade_name_en, ":trade_name_ta" => $trade_name_ta, ":fin_year" => $fin_year, ":traderate" => $traderate, ":lb_tradecode" => $lb_tradecode, ":isactive" => $isactive, ":user_name" => $user_name, ":ip_address" => $ip_address, ":edit_id" => $edit_id, ":del_id" => $del_id), 4);
       

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

$ModifyTransactionDetails = new ModifyTransactionDetails();

if (!isset($_POST['cmd'])) {

    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        // print_r(array_merge($_POST, $_GET));exit();
        $ModifyTransactionDetails->data_save(array_merge($_POST, $_GET));
    } else {
        $ModifyTransactionDetails->main_content(array_merge(array("mode_name" => "Submit", "mode_class" => "btn-primary"), $_GET));
    }
} 
?>