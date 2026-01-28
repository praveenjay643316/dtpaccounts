<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once  '../../config/config.php';

class AdjustBankReceiptVoucher  extends ConfigClass
{

    public $page_token = "Adjust_Bank_Receipt_Voucher";
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
            $(document).ready(function() {
				
				 $(document).on('change', '#bank_code', function() {

                    var bank_code = $("#bank_code").val();
					if(bank_code != ''){
						$.ajax({
							url: "Adjust_Bank_Receipt_Voucher.php",
							type: "post",
							data: {
								"bank_code": btoa(bank_code),
								"cmd": btoa(2)
							},
							success: function(data) {
								if (data != '') {
									var Result_Data = JSON.parse(data);
									$('#bank_name').val(Result_Data['DATA']);
								}
							},
							dataType: 'html'
						});	
					}
					else{
						alert('Select Bank Code');
					}

                });
				
				$('input[type="radio"]').on('click', function() {
				   
				   var type=$(this).val();
				 
				   if(type == 'D'){
					 
					  $("#debit_amt").removeAttr("style");
					  $("#credit_amt").attr("style", "display:none");  
				  }
				  else if(type == 'C'){
					$("#credit_amt").removeAttr("style");
					$("#debit_amt").attr("style","display:none");  
			    }else{
					$("#debit_amt").attr("style","display:none");
					$("#credit_amt").attr("style","display:none");  
			}
				   
		   });

                $('#date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd-mm-yyyy',
                    //minDate:  '12-12-2014',
                    minDate: new Date('01-01-1970'),
                    //maxDate: new Date() 
                    maxDate: new Date()

                });
                $('#chl_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd-mm-yyyy',
                    //minDate:  '12-12-2014',
                    minDate: new Date('01-01-1970'),
                    //maxDate: new Date() 
                    maxDate: new Date()

                });

                $('#cheque_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd-mm-yyyy',
                    //minDate:  '12-12-2014',
                    minDate: new Date('01-01-1970'),
                    //maxDate: new Date() 
                    maxDate: new Date()

                });

                $('#dd_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd-mm-yyyy',
                    //minDate:  '12-12-2014',
                    minDate: new Date('01-01-1970'),
                    //maxDate: new Date() 
                    maxDate: new Date()

                });

                $('#ecs_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd-mm-yyyy',
                    //minDate:  '12-12-2014',
                    minDate: new Date('01-01-1970'),
                    //maxDate: new Date() 
                    maxDate: new Date()

                });

                $('#pay_mode').change(function() {
                    if ($(this).val() == 'Cheque') {
                        $('.pay_mode_dd').hide();
                        $('.pay_mode_ecs').hide();
                        $('.pay_mode_cheque').show();
                        $('.bank_name_row').show();
                    } else if ($(this).val() == 'DD') {
                        $('.pay_mode_cheque').hide();
                        $('.pay_mode_ecs').hide();
                        $('.pay_mode_dd').show();
                        $('.bank_name_row').show();
                    } else if ($(this).val() == 'ECS') {
                        $('.pay_mode_dd').hide();
                        $('.pay_mode_cheque').hide();
                        $('.pay_mode_ecs').show();
                        $('.bank_name_row').show();
                    } else {
                        $('.pay_mode_dd').hide();
                        $('.pay_mode_cheque').hide();
                        $('.pay_mode_ecs').hide();
                        $('.bank_name_row').hide();
                    }
                });




                <?php if (!isset($post_data_array['del_id'])) { ?>

                    $(document).on('click', "#btn_save", function() {

                        var Current_Field_id = $(this).attr('id');
                        $('#' + Current_Field_id).hide();
                        try {

                            if ($("#serial_no").val().length == '') {
                                throw {
                                    msg: "Enter Serial Number",
                                    foc: "#serial_no"
                                }
                            }

                            if ($("#date").val().length == '') {
                                throw {
                                    msg: "Select Date",
                                    foc: "#date"
                                }
                            }

                            if ($("#pay_mode").val().length == '') {
                                throw {
                                    msg: "Select Payment Mode",
                                    foc: "#pay_mode"
                                }
                            } 

                            else if ($("#pay_mode").val() == 'Cheque') {
                                if ($("#cheque_no").val().length == '') {
                                    throw {
                                        msg: "Enter Cheque No.",
                                        foc: "#cheque_no"
                                    }
                                }

                                if ($("#cheque_date").val().length == '') {
                                    throw {
                                        msg: "Enter Cheque Date",
                                        foc: "#cheque_date"
                                    }
                                }

                                if ($("#bank_name").val().length == '') {
                                    throw {
                                        msg: "Enter Bank Name",
                                        foc: "#bank_name"
                                    }
                                }
                            }

                            else if ($("#pay_mode").val() == 'DD') {
                                if ($("#dd_no").val().length == '') {
                                    throw {
                                        msg: "Enter DD No.",
                                        foc: "#dd_no"
                                    }
                                }

                                if ($("#dd_date").val().length == '') {
                                    throw {
                                        msg: "Select DD Date",
                                        foc: "#dd_date"
                                    }
                                }

                                if ($("#bank_name").val().length == '') {
                                    throw {
                                        msg: "Enter Bank Name",
                                        foc: "#bank_name"
                                    }
                                }
                            }

                            else if ($("#pay_mode").val() == 'ECS') {
                                if ($("#ecs_no").val().length == '') {
                                    throw {
                                        msg: "Enter ECS No.",
                                        foc: "#ecs_no"
                                    }
                                }

                                if ($("#ecs_date").val().length == '') {
                                    throw {
                                        msg: "Select ECS Date",
                                        foc: "#ecs_date"
                                    }
                                }

                                if ($("#bank_name").val().length == '') {
                                    throw {
                                        msg: "Enter Bank Name",
                                        foc: "#bank_name"
                                    }
                                }
                            }

                            if ($("#bank_code").val().length == '') {
                                throw {
                                    msg: "Select Bank Code",
                                    foc: "#bank_code"
                                }
                            }

                            if ($("#acc_code").val().length == '') {
                                throw {
                                    msg: "Select Account Code",
                                    foc: "#acc_code"
                                }
                            }

                        

                            if ($("#bank_head").val().length == '') {
                                throw {
                                    msg: "Enter Bank Head",
                                    foc: "#bank_head"
                                }
                            }

                            if ($("#acc_head").val().length == '') {
                                throw {
                                    msg: "Enter Account Head",
                                    foc: "#acc_head"
                                }
                            }

                            if ($("#cash_from_type").val().length == '') {
                                throw {
                                    msg: "Select Cash From Type",
                                    foc: "#cash_from_type"
                                }
                            }

                            if ($("#cash_from_type").val() == 'D' && $("#debit_amount").val().length == '') {
                                throw {
                                    msg: "Enter Debit Amount",
                                    foc: "#debit_amount"
                                }
                            }

                            if ($("#cash_from_type").val() == 'C' && $("#credit_amount").val().length == '') {
                                throw {
                                    msg: "Enter Credit Amount",
                                    foc: "#credit_amount"
                                }
                            }
                            
                            if ($("#narration").val().length == '') {
                                throw {
                                    msg: "Enter Narration",
                                    foc: "#narration"
                                }
                            }
                            
                            /* if ($("#lb_tradecode").val().length == '') {
                                throw {
                                    msg: "Enter Trade LB Code",
                                    foc: "#lb_tradecode"
                                }
                            }

                            if ($('input:radio[name=isactive]:checked').length == 0) {
                                throw {
                                    msg: "Choose Status",
                                    foc: "#isactive"
                                }
                            } */

                            return true;
                        } catch (e) {
                            alert(e.msg);
                            $('#' + Current_Field_id).show();
                            $(e.foc).focus();
                            return false;
                        }

                    });

                <?php } ?>
            });
        </script>

        <style type="text/css">
            .hidden_field_element_value {
                display: none;
            }

            .gj-datepicker {
                width: 50%;
            }
			table.table-bordered > tbody > tr > td, table.table-bordered > tfoot > tr > td {
				width: 50%!important;
			}
        </style>


        <?php
        if (isset($post_data_array["edit_id"]) || isset($post_data_array["del_id"])) {
            if (isset($post_data_array["edit_id"])) {
                $exemption_category_data_id = base64_decode($post_data_array["edit_id"]);

                $exemption_category_data_id_nameValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $exemption_category_data_id,
                        'Field_Name' => 'edit_id',
                        'Field_Max_length' => '6',
                        'Field_Label_Name' => 'Edit ID',
                    )
                );

                if ($exemption_category_data_id_nameValidation['Status'] == "Error") {
                    echo 'Invalide Request';
                    exit;
                }
            } else if (isset($post_data_array["del_id"])) {
                $exemption_category_data_id = base64_decode($post_data_array["del_id"]);

                $exemption_category_data_id_nameValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $exemption_category_data_id,
                        'Field_Name' => 'del_id',
                        'Field_Max_length' => '6',
                        'Field_Label_Name' => 'Delete ID',
                    )
                );

                if ($exemption_category_data_id_nameValidation['Status'] == "Error") {
                    echo 'Invalide Request';
                    exit;
                }
            }

            $sel_exemption_cat_data_upd_details = "";
            $data_array_val = $this->prepare($sel_exemption_cat_data_upd_details, array(":exemption_category_data_id" => $exemption_category_data_id), 4);
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
                        header("refresh: 3; url=Trade_Entry_Form.php");
                    }
                    ?>



                    <table class="table table-bordered m-0 p-0 table-striped tndtp_form_table">
                        <thead class="bg-th-form-dsg">
                            <tr>
                                <th align="center" scope="col" colspan="12">Adjust Bank Receipt Voucher</th>
                            </tr>


                        </thead>

                        <tbody>
                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Serial No</span></td>
                                <td  scope="col">
                                    <input type="text" id="serial_no" name="serial_no" class="form-control form-control-sm w-50 Number_Field" />
                                </td>
							</tr>
                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="484">Date</span></td>
                                <td  scope="col">
                                    <input type="text" id="date" name="date" value="" class="form-control form-control-sm user_enter_date w-50" />
                                </td>
                            </tr>
                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="484">Chalan Collection Date</span></td>
                                <td  scope="col">
                                    <input type="text" id="chl_date" name="chl_date" value="" class="form-control form-control-sm user_enter_date w-50" />
                                </td>
                            </tr>
                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Payment Mode</span></td>
                                <td  scope="col">
                                    <select id="pay_mode" name="pay_mode" class="form-control form-control-sm w-50"  colspan="2">
                                        <option value="">Choose</option>
                                        <?php 
												$sel_payment_type="select paymenttypeid, paymenttype as paymenttype_en, paymenttype_ta from master.m_paymenttype where del_flag is null and paymenttypeid in(1,2,3,4);";
												$sel_payment_type_res=$this->prepare($sel_payment_type, array(), 2);
												foreach($sel_payment_type_res as $sel_payment_type_row){
													?>
                                                    <option value="<?php echo $sel_payment_type_row['paymenttypeid']; ?>"><?php echo $sel_payment_type_row['paymenttype_'.$lang_code_2d]; ?></option>
                                                    <?php
												}
											?>
                                    </select>
                                </td>
                            </tr>

                            <tr class="pay_mode_cheque" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Cheque No</span></td>
                                <td  scope="col">
                                    <input type="text" id="cheque_no" name="cheque_no" class="form-control form-control-sm w-50" />
                                </td>
								 </tr>

                            <tr class="pay_mode_cheque" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="484">Cheque Date</span></td>
                                <td  scope="col">
                                    <input type="text" id="cheque_date" name="cheque_date" value="" class="form-control form-control-sm user_enter_date w-50" />
                                </td>
                            </tr>

                            <tr class="pay_mode_dd" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">DD No</span></td>
                                <td  scope="col">
                                    <input type="text" id="dd_no" name="dd_no" class="form-control form-control-sm w-50" />
                                </td>
							</tr>

                            <tr class="pay_mode_dd" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="484">DD Date</span></td>
                                <td  scope="col">
                                    <input type="text" id="dd_date" name="dd_date" value="" class="form-control form-control-sm user_enter_date w-50" />
                                </td>
                            </tr>

                            <tr class="pay_mode_ecs" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">ECS No</span></td>
                                <td  scope="col">
                                    <input type="text" id="dd_no" name="dd_no" class="form-control form-control-sm w-50" />
                                </td>
							</tr>

                            <tr class="pay_mode_ecs" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="484">ECS Date</span></td>
                                <td  scope="col">
                                    <input type="text" id="ecs_date" name="ecs_date" value="" class="form-control form-control-sm user_enter_date w-50" />
                                </td>
                            </tr>
							<tr>
                                    <td class="text-left font-weight-bold"><span DisplayLabelID="483">Cash From</span>
                                    </td>
                                    <td scope="col">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="credit" name="cash_from_type" value="1" class="custom-control-input">
                                            <label class="custom-control-label" for="credit"><span>Credit</span></label>
                                        </div> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="debit" name="cash_from_type" value="2" class="custom-control-input">
                                            <label class="custom-control-label" for="debit"><span>Debit</span></label>
                                        </div>
                                    </td>
                                </tr>
                            <tr id="debit_amt" style="display:none">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Debit Amount</span></td>
                                <td  scope="col">
                                    <input type="text" id="debit_amount" name="debit_amount" class="form-control form-control-sm w-50 Number_Field" /></td>
                            </tr>
                            <tr id="credit_amt" style="display:none">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Credit Amount</span></td>
                                <td  scope="col">
                                    <input type="text" id="credit_amount" name="credit_amount" class="form-control form-control-sm w-50 Number_Field"/></td>
                            </tr>
                            <tr>
                                <td class="text-left font-weight-bold"><span>Bank Code</span></td>
                                <td scope="col">
                                    <select id="bank_code" name="bank_code" class="form-control form-control-sm  w-50">
                                        <option value="">Choose</option>
                                        <?php
                                                    $sel_bank_new_id = "SELECT bank_id, bank_code, bank_name_".$lang_code_2d." FROM accounts_master.m_bank WHERE isactive = :isactive AND del_flag IS NULL ORDER BY bank_code ASC;";
                                                    $sel_bank_newid_res = $this->prepare($sel_bank_new_id, array(":isactive" => 1), 2);
            
                                                    foreach ($sel_bank_newid_res as $sel_bank_newid_key => $sel_bank_newid_row) {
            
                                                    ?>
                                            <option value="<?php echo htmlentities($sel_bank_newid_row['bank_id']); ?>" data-desc="<?php echo htmlentities($sel_bank_newid_row['bank_name_'.$lang_code_2d]); ?>">
                                                <?php echo htmlentities($sel_bank_newid_row['bank_code']); ?></option>
                                            <?php
                                                    }
                                                    ?>
                                    </select>
                                    
                                </td>
							</tr>
                           <tr class="bank_name_row" style="display: none;">
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Bank Name</span></td>
                                <td scope="col">
                                    <input type="text" id="bank_name" name="bank_name" maxlength="500" value="" class="form-control  form-control-sm Tax_Form_English_Ownername_Property_Tax first_letter_uppercase w-50" />
                                </td>
                            </tr>
                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Bank Head</span></td>
                                <td  scope="col">
                                    <textarea id="bank_head" name="bank_head" rows="4" cols="50" class="form-control form-control-sm w-50"></textarea>
                                </td>
							</tr>
                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Account Code</span></td>
                                <td  scope="col">
                                    <select id="acc_code" name="acc_code" class="form-control form-control-sm w-50">
                                        <option value="">Choose</option>
                                        <?php
                                        $sel_fin_year_id = "SELECT bank_payment_voucher_account_codes_id,acc_code as account_code,acc_head FROM accounts_master.bank_payment_voucher_account_codes ORDER BY account_code DESC";

                                        $sel_fin_yearid_res = $this->prepare($sel_fin_year_id, array(),2);

                                        foreach ($sel_fin_yearid_res as $sel_fin_yearid_key => $sel_fin_yearid_row) {

                                        ?>
                                            <option value="<?php echo htmlentities($sel_fin_yearid_row['bank_payment_voucher_account_codes_id']); ?>">
                                                <?php echo htmlentities($sel_fin_yearid_row['account_code']); ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                   
                                </td>
                                
                            </tr>
                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="484">Account Head</span></td>
                                <td  scope="col">
                                    <textarea id="acc_head" name="acc_head" rows="4" cols="50" class="form-control form-control-sm w-50"></textarea>
                                </td>
                            </tr>

                            <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="483">Amount</span></td>
                                <td  scope="col">
                                    <input type="text" id="debit_amount" name="debit_amount" class="form-control form-control-sm number_field w-50" />
                                </td>
							</tr>
                            
                            <tr>
                                <td align="center" >
                                    <span DisplayLabelID="484">Narration</span>
                                </td>
                                <td align="left" colspan="2">
                                    <textarea id="narration" name="narration" rows="4" cols="50" class="form-control w-50 form-control-sm"></textarea>
                                    <span>Max 250 Characters</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" align="center">
                                    <center>
                                        <input type="submit" id="btn_save" name="btn_save" value="Save" class="btn btn-md text-white font-weight-bold  btn-success" />
                                        <input type="button" id="btn_reset" name="btn_reset" value="Cancel" class="btn btn-md text-white font-weight-bold btn-secondary" onclick="window.location='Trade_Entry_Form.php'" />
                                    </center>
                                </td>
                            </tr>

                        </tbody>
                    </table>



                </div>
            </div>
            </div>
        </form>
        <?php

        // #############

        // PAGE CONTENT END

        // #############

        $ob_output_main_contents = ob_get_contents();
        ob_clean();
        $this->Template($this->getCurrentUserTemplate() != "" ? $this->getCurrentUserTemplate() : "Template1", "Property Tax - New Assessment", $ob_output_main_contents, array(), array('page_id' => 12));
    }



    public function Trade_Fin_Year_Range_Details($post_data_array = array())
    {
        $fin_year = base64_decode($post_data_array['fin_year']);
        $state_code = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $tpcode = $this->getCurrentLocalBodyCode();

        ob_start();
        $sel_tradedetails_details = "SELECT a.tradedetails_id as edit_id,b.traders_license_type_name,a.isactive,c.fin_year,a.description_ta FROM 
        (SELECT tradedetails_id,licencetypeid,description_ta,finyear,isactive FROM tradelicense.t_tl_tradedetails WHERE statecode=:state_code AND dcode=:dcode AND lbcode=:tpcode AND finyear = :fin_year AND del_flag IS NULL)a 
        LEFT JOIN (SELECT traders_license_type_id,traders_license_type_name FROM tradelicense.m_t_tl_trader_license_type WHERE isactive=:isactive AND del_flag IS NULL)b ON a.licencetypeid::INTEGER=b.traders_license_type_id 
        LEFT JOIN
        (SELECT fin_yearid,fin_year FROM master.m_fin_year WHERE isactive = :isactive AND del_flag IS NULL)c
        ON a.finyear=c.fin_year order by a.tradedetails_id desc";

        $sel_tradedetails_details_res = $this->prepare($sel_tradedetails_details, array(":state_code" => $state_code, ":dcode" => $dcode, ":tpcode" => $tpcode, ":isactive" => 1, ":fin_year" => $fin_year), 2);
        // var_dump($sel_tradedetails_details_res);exit();

        if (count($sel_tradedetails_details_res) > 0) {
            foreach ($sel_tradedetails_details_res as $sel_tradedetails_details_key => $sel_tradedetails_details_row) {
        ?>
                <tr>
                    <td class="text-center"><?php echo htmlentities($sel_tradedetails_details_key + 1); ?></td>
                    <td class="text-left">
                        <?php echo htmlentities($sel_tradedetails_details_row['traders_license_type_name']); ?>
                    </td>
                    <td class="text-left">
                        <?php echo htmlentities($sel_tradedetails_details_row['fin_year']); ?>
                    </td>
                    <td class="text-left">
                        <?php echo htmlentities($sel_tradedetails_details_row['description_ta']); ?></td>

                    <td align="center">
                        <?php if ($sel_tradedetails_details_row['isactive'] == 1) {
                            echo 'Active';
                        } else {
                            echo 'Deactive';
                        } ?>
                    </td>
                    <td align="center"><a href="?edit_id=<?php echo htmlentities(base64_encode($sel_tradedetails_details_row['edit_id'])); ?>" class="btn btn-warning btn-sm"><?php /* ?><i class="fa fa-pencil pr-1"
                aria-hidden="true"></i><?php */ ?>Edit</a>
                        <a href="?del_id=<?php echo htmlentities(base64_encode($sel_tradedetails_details_row['edit_id'])); ?>" class="btn btn-danger btn-sm">Delete</a>
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


        $statecode = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();


        $edit_id = isset($save_data['edit_id']) ? base64_decode($save_data['edit_id']) : 0;
        $del_id = isset($save_data['del_id']) ? base64_decode($save_data['del_id']) : 0;

        if ($del_id == 0) {

            if (isset($save_data['date'])) {
                $date = $save_data['date'];
                list($date_completion, $month_completion, $year_completion) = explode('-', $date);
                $date = $year_completion . '-' . $month_completion . '-' . $date_completion;

                $dateValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'date',
                        'Field_Value' => $save_data['date'],
                        'Field_Name' => 'date',
                        'Field_Format' => 'dd-mm-yyyy',
                        'Field_Label_Name' => 'Invalid Date',
                    )
                );

                if ($dateValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "date",
                        "MESSAGE" => $dateValidation['Message']
                    ), $save_data));
                    exit;
                }
            }


            if (isset($save_data['serial_no'])) {
                $serial_no = $save_data['serial_no'];

                $serial_noValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $serial_no,
                        'Field_Name' => 'serial_no',
                        'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Invalid Serial No',
                    )
                );

                if ($serial_noValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "serial_no",
                        "MESSAGE" => $serial_noValidation['Message']
                    ), $save_data));
                    exit;
                }
            }


            if (isset($save_data['licencetypeid'])) {
                $licencetypeid = $save_data['licencetypeid'];

                $licencetypeidValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $licencetypeid,
                        'Field_Name' => 'licencetypeid',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => 'Invalid Instalment Type',
                    )
                );

                if ($licencetypeidValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "licencetypeid",
                        "MESSAGE" => $licencetypeidValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['pay_mode'])) {
                $pay_mode = $save_data['pay_mode'];

                $pay_modeValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $pay_mode,
                        'Field_Name' => 'pay_mode',
                        'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Invalid Payment Mode',
                    )
                );

                if ($pay_modeValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "pay_mode",
                        "MESSAGE" => $pay_modeValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['cheque_no'])) {
                $cheque_no = $save_data['cheque_no'];

                $cheque_noValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $cheque_no,
                        'Field_Name' => 'cheque_no',
                        'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Invalid Cheque No',
                    )
                );

                if ($cheque_noValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "cheque_no",
                        "MESSAGE" => $cheque_noValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['cheque_date'])) {
                $cheque_date = $save_data['cheque_date'];
                list($date_completion, $month_completion, $year_completion) = explode('-', $cheque_date);
                $cheque_date = $year_completion . '-' . $month_completion . '-' . $date_completion;

                $cheque_dateValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'date',
                        'Field_Value' => $save_data['cheque_date'],
                        'Field_Name' => 'cheque_date',
                        'Field_Format' => 'dd-mm-yyyy',
                        'Field_Label_Name' => 'Invalid Cheque Date',
                    )
                );

                if ($cheque_dateValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "cheque_date",
                        "MESSAGE" => $cheque_dateValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['dd_no'])) {
                $dd_no = $save_data['dd_no'];

                $dd_noValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $dd_no,
                        'Field_Name' => 'dd_no',
                        'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Invalid DD No',
                    )
                );

                if ($dd_noValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "dd_no",
                        "MESSAGE" => $dd_noValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['dd_date'])) {
                $dd_date = $save_data['dd_date'];
                list($date_completion, $month_completion, $year_completion) = explode('-', $dd_date);
                $dd_date = $year_completion . '-' . $month_completion . '-' . $date_completion;

                $dd_dateValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'date',
                        'Field_Value' => $save_data['dd_date'],
                        'Field_Name' => 'dd_date',
                        'Field_Format' => 'dd-mm-yyyy',
                        'Field_Label_Name' => 'Invalid DD Date',
                    )
                );

                if ($dd_dateValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "dd_date",
                        "MESSAGE" => $dd_dateValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['ecs_no'])) {
                $ecs_no = $save_data['ecs_no'];

                $ecs_noValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $ecs_no,
                        'Field_Name' => 'ecs_no',
                        'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Invalid ECS No',
                    )
                );

                if ($ecs_noValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "ecs_no",
                        "MESSAGE" => $ecs_noValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['ecs_date'])) {
                $ecs_date = $save_data['ecs_date'];
                list($date_completion, $month_completion, $year_completion) = explode('-', $ecs_date);
                $ecs_date = $year_completion . '-' . $month_completion . '-' . $date_completion;

                $ecs_dateValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'date',
                        'Field_Value' => $save_data['ecs_date'],
                        'Field_Name' => 'ecs_date',
                        'Field_Format' => 'dd-mm-yyyy',
                        'Field_Label_Name' => 'Invalid ECS Date',
                    )
                );

                if ($dd_dateValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "ecs_date",
                        "MESSAGE" => $dd_dateValidation['Message']
                    ), $save_data));
                    exit;
                }
            }


            if (isset($save_data['bank_name'])) {
                $bank_name = $save_data['bank_name'];

                $bank_nameValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $bank_name,
                        'Field_Name' => 'bank_name',
                        'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Invalid Bank Name',
                    )
                );

                if ($bank_nameValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "bank_name",
                        "MESSAGE" => $bank_nameValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['bank_code'])) {
                $bank_code = $save_data['bank_code'];

                $bank_codeValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $bank_code,
                        'Field_Name' => 'bank_code',
                        'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Invalid Bank Code',
                    )
                );

                if ($bank_codeValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "bank_code",
                        "MESSAGE" => $bank_codeValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['account_code'])) {
                $account_code = $save_data['account_code'];

                $account_codeValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $account_code,
                        'Field_Name' => 'account_code',
                        'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Invalid Account Code',
                    )
                );

                if ($account_codeValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "account_code",
                        "MESSAGE" => $account_codeValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['bank_head'])) {
                $bank_head = $save_data['bank_head'];

                $bank_headValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $bank_head,
                        'Field_Name' => 'bank_head',
                        'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Invalid Bank Head',
                    )
                );

                if ($bank_headValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "bank_head",
                        "MESSAGE" => $bank_headValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['account_head'])) {
                $account_head = $save_data['account_head'];

                $account_headValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $account_head,
                        'Field_Name' => 'account_head',
                        'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Invalid Account Head',
                    )
                );

                if ($account_headValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "account_head",
                        "MESSAGE" => $account_headValidation['Message']
                    ), $save_data));
                    exit;
                }
            }




            if (isset($save_data['debit_amount'])) {
                $debit_amount = $save_data['debit_amount'];

                $debit_amountValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $debit_amount,
                        'Field_Name' => 'debit_amount',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => 'Invalid Debit Amount',
                    )
                );

                if ($debit_amountValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "debit_amount",
                        "MESSAGE" => $debit_amountValidation['Message']
                    ), $save_data));
                    exit;
                }
            }

            if (isset($save_data['credit_amount'])) {
                $credit_amount = $save_data['credit_amount'];

                $credit_amountValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $credit_amount,
                        'Field_Name' => 'credit_amount',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => 'Invalid Credit Amount',
                    )
                );

                if ($credit_amountValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "credit_amount",
                        "MESSAGE" => $credit_amountValidation['Message']
                    ), $save_data));
                    exit;
                }
            }


            if (isset($save_data['narration'])) {
                $narration = $save_data['narration'];

                $narrationValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $narration,
                        'Field_Name' => 'narration',
                        'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Invalid Narration',
                    )
                );

                if ($narrationValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "narration",
                        "MESSAGE" => $narrationValidation['Message']
                    ), $save_data));
                    exit;
                }
            }
        }

        $Result_Message = "Data Saved SuccessFully";

        if ($edit_id > 0) {
            $Result_Message = "Data Updated SuccessFully";
        } else if ($del_id > 0) {
            $Result_Message = "Data Deleted SuccessFully";
        }

        $this->beginTransaction();

        $pp_assessment_initiation = "tradelicense.adjust_bank_receicpt_voucher";
        $user_name = $this->getCurrentUser();
        $ip_address = $this->getIpAddress();
        //$date = $this->getCurrentDate();

        if (isset($save_data["edit_id"])) {

            $save_query = "select * from " . $pp_assessment_initiation . "(:statecode,:dcode,:lbcode,:licencetypeid,:trade_name_en,:trade_name_ta,:fin_year,:traderate,:lb_tradecode,:isactive,:user_name,:ip_address,:edit_id,:del_id)";

            $res1 = $this->prepare($save_query, array(":statecode" => $statecode, ":dcode" => $dcode, ":lbcode" => $lbcode, ":licencetypeid" => $licencetypeid, ":trade_name_en" => $trade_name_en, ":trade_name_ta" => $trade_name_ta, ":fin_year" => $fin_year, ":traderate" => $traderate, ":lb_tradecode" => $lb_tradecode, ":isactive" => $isactive, ":user_name" => $user_name, ":ip_address" => $ip_address, ":edit_id" => $edit_id, ":del_id" => $del_id), 4);
        } else if (isset($save_data["del_id"])) {

            $save_query = "select * from " . $pp_assessment_initiation . "(:statecode,:dcode,:lbcode,:licencetypeid,:trade_name_en,:trade_name_ta,:fin_year,:traderate,:lb_tradecode,:isactive,:user_name,:ip_address,:edit_id,:del_id)";
            $res1 = $this->prepare($save_query, array(":statecode" => $statecode, ":dcode" => $dcode, ":lbcode" => $lbcode, ":licencetypeid" => NULL, ":trade_name_en" => NULL, ":trade_name_ta" => NULL, ":fin_year" => NULL, ":traderate" => NULL, ":lb_tradecode" => NULL, ":isactive" => NULL, ":user_name" => $user_name, ":ip_address" => $ip_address, ":edit_id" => $edit_id, ":del_id" => $del_id), 4);
        } else {
            // print_r(array($statecode,$dcode,$lbcode,$licencetypeid,$lb_tradecode,$trade_name,$edscription_en,$edscription_ta,$isactive,$edit_id,$del_id));
            // exit();
            $save_query = "select * from " . $pp_assessment_initiation . "(:statecode,:dcode,:lbcode,:licencetypeid,:trade_name_en,:trade_name_ta,:fin_year,:traderate,:lb_tradecode,:isactive,:user_name,:ip_address,:edit_id,:del_id)";

            $res1 = $this->prepare($save_query, array(":statecode" => $statecode, ":dcode" => $dcode, ":lbcode" => $lbcode, ":licencetypeid" => $licencetypeid, ":trade_name_en" => $trade_name_en, ":trade_name_ta" => $trade_name_ta, ":fin_year" => $fin_year, ":traderate" => $traderate, ":lb_tradecode" => $lb_tradecode, ":isactive" => $isactive, ":user_name" => $user_name, ":ip_address" => $ip_address, ":edit_id" => $edit_id, ":del_id" => $del_id), 4);
            // var_dump($res1);exit();
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

$AdjustBankReceiptVoucher = new AdjustBankReceiptVoucher();

if (!isset($_POST['cmd'])) {

    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        // print_r(array_merge($_POST, $_GET));exit();
        $AdjustBankReceiptVoucher->data_save(array_merge($_POST, $_GET));
    } else {
        $AdjustBankReceiptVoucher->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
    }
} else if (isset($_POST['cmd'])) {
    $cmd = base64_decode($_POST['cmd']);

    if ($cmd == 2) {
       $bank_code = base64_decode($_POST['bank_code']);
		$sel_qry = "select bank_code, bank_name_en from accounts_master.bank_new where del_flag is null and isactive = 1 and bank_id=:bank_code";
		
		$sel_qry_res=$AdjustBankReceiptVoucher->prepare($sel_qry,array(":bank_code"=>$bank_code),4);
												
        $Result['STATUS'] = 'SUCCESS';
        $Result['DATA'] = $sel_qry_res['bank_name_en'];
        echo json_encode($Result);
        exit;
    }
}
?>