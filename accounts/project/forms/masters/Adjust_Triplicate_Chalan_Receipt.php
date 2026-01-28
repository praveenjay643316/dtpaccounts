<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once  '../../config/config.php';

class Adjust_Triplicate_Chalan extends ConfigClass
{

    public $page_token = "triplicate_challan";
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
        $lbcode = $this->getCurrentLocalBodyCode();
        $lang_code_2d = $this->getCurrentUserLanguage2D();
        ?>
		<script type="text/javascript">
        $(document).ready(function() {
            $('#rc_date').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'dd-mm-yyyy',
                minDate: new Date('01-01-1970'),
                maxDate: new Date()
            });        
            $('#cheque_date').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'dd-mm-yyyy',
                minDate: new Date('01-01-1970'),
                maxDate: new Date()
            });
            $('#cash_coll_date').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'dd-mm-yyyy',
                minDate: new Date('01-01-1970'),
                maxDate: new Date()
            });
            $('#cheque_date').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'dd-mm-yyyy',
                minDate: new Date('01-01-1970'),
                maxDate: new Date()
            });
            $('#dd_date').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'dd-mm-yyyy',
                minDate: new Date('01-01-1970'),
                maxDate: new Date()
            });
            $('#ecs_date').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'dd-mm-yyyy',
                minDate: new Date('01-01-1970'),
                maxDate: new Date()
            });
            $('#account_code').change(function() {
                $('#account_head').val($('option:selected', this).attr('data-desc'));
            });
			$('#bank_code').change(function() {
				$('#bank_name').val($('option:selected', this).attr('data-desc'));
			});
            $('#pay_mode').change(function() {
                if ($(this).val() == '2') {
                    $('.pay_mode_dd').hide();
                    $('.pay_mode_ecs').hide();
                    $('.pay_mode_cheque').show();
                    $('#bank_name_row').show();
					$('#bank_code_row').show();
                } else if ($(this).val() == '3') {
                    $('.pay_mode_cheque').hide();
                    $('.pay_mode_ecs').hide();
                    $('.pay_mode_dd').show();
                    $('#bank_name_row').show();
					$('#bank_code_row').show();
                } else if ($(this).val() == '4') {
                    $('.pay_mode_dd').hide();
                    $('.pay_mode_cheque').hide();
                    $('.pay_mode_ecs').show();
                    $('#bank_name_row').show();
					$('#bank_code_row').show();
                } else {
                    $('.pay_mode_dd').hide();
                    $('.pay_mode_cheque').hide();
                    $('.pay_mode_ecs').hide();
                    $('#bank_name_row').hide();
					$('#bank_code_row').hide();
                }
            });

            $(document).ready(function() { 
                $('input[name=cash_from_type]').each(function() {
                    $(this).click();
                });
            });
            $('input[name=cash_from_type]').click(function() {   
                var type=$(this).val();      
                $.ajax({
                    url: "Adjust_Triplicate_Chalan_Receipt.php",
                    type: "post",
                    data: {
                        "type": btoa(type),
                        "cmd": btoa(1)
                    },
                    success: function(data) {
                        if (type == "Collection") {
                            $("#cash_coll_date_row").show();
                        } else if (type == "Accounts") {
                            $("#cash_coll_date_row").hide();
                        }
                        $('#account_code').html(data);
                    },
                    dataType: 'html'
                });
            });
            $(document).on('click', "#btn_save", function() {        
                var Current_Field_id = $(this).attr('id');
                $('#' + Current_Field_id).hide();
                try {        
                    if ($("#rc_serial_no").val().length == '') {
                        throw {
                            msg: "Enter Chalan Serial No",
                            foc: "#rc_serial_no"
                        }
                    }        
                    if ($("#rc_date").val().length == '') {
                        throw {
                            msg: "Enter Chalan Date",
                            foc: "#rc_date"
                        }
                    }        
                    if ($("#pay_mode").val().length == '') {
                        throw {
                            msg: "Select Payment Mode",
                            foc: "#pay_mode"
                        }
                    } else if ($("#pay_mode").val() == '2') {
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
                    } else if ($("#pay_mode").val() == '3') {
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
                    } else if ($("#pay_mode").val() == '4') {
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
                    if ($("#remitter_name_address").val().length == '') {
                        throw {
                            msg: "Enter Name and Address of Remitter",
                            foc: "#remitter_name_address"
                        }
                    }        
                    if ($('input:radio[name=cash_from_type]:checked').length == 0) {
                        throw {
                            msg: "Choose Cash From",
                            foc: "#accounts"
                        }
                        if ($('input[value="accounts"]').prop("checked", false)) {        
                            if ($('input[value="Collection"]').prop("checked", true)) {
                                if ($("#cash_coll_date").val().length == '') {
                                    throw {
                                        msg: "Enter Cash Collection Date",
                                        foc: "#cash_coll_date"
                                    }
                                }
                            }
                        }
                    }        
                    if ($("#account_code").val().length == '') {
                        throw {
                            msg: "Select Account Code",
                            foc: "#account_code"
                        }
                    }      
                    if ($("#amount").val().length == '') {
                        throw {
                            msg: "Enter Amount",
                            foc: "#amount"
                        }
                    }   
                    if ($("#narration").val().length == '') {
                        throw {
                            msg: "Enter Narration",
                            foc: "#narration"
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
        <style type="text/css">
			.hidden_field_element_value {
				display: none;
			}
			
			.gj-datepicker {
				width: 50%;
			}
        </style>
        <div class="container mt-3">
            <form action="" method="post" class="" enctype="multipart/form-data"  autocomplete="off">
                <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>"
                    name="<?php echo htmlentities($this->page_token); ?>"
                    value="<?php echo htmlentities($this->token($this->page_token)); ?>">
                <div class="card">
                    <div class="card-body pl-5 pr-5">
                        <?php
                                    if (isset($post_data_array["STATUS"])) {
                                        echo $this->ShowMessage($post_data_array["STATUS"], $post_data_array["MESSAGE"]);
                                        header("refresh: 3; url=Adjust_Triplicate_Chalan_Receipt.php");
                                    }
                                    ?>
                        <table class="table table-bordered m-0 p-0 tndtp_form_table">
                            <thead class="bg-th-form-dsg">
                                <tr>
                                    <th align="center" scope="col" colspan="2"> Triplicate Chalan </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-left font-weight-bold"><span> Chalan Serial No</span></td>
                                    <td scope="col">
                                    	<?php 
											$sel_qry="select max(chalan_details_id) as id from accounts_master.t_triplicate_chalan_details where dcode=:dcode and lbcode=:lbcode and isactive=:isactive and del_flag is null;";
											$sel_qry_res=$this->prepare($sel_qry, array(":dcode"=>$dcode, ":lbcode"=>$lbcode, ":isactive"=>1),4);
											$get_cur_fin_year="select * from public.sp_fin_year_from_date(current_date);";
											$cur_fin_year=$this->prepare($get_cur_fin_year, array(),4);
											echo $sel_qry_res['id']+1 .'/'. $cur_fin_year['sp_fin_year_from_date'];
										?>
                                        <input type="hidden" id="rc_serial_no" name="rc_serial_no"
                                            class="form-control w-50 form-control-sm" value="<?php echo $sel_qry_res['id']+1 .'/'. $cur_fin_year['sp_fin_year_from_date']; ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left font-weight-bold"><span> Chalan Date</span></td>
                                    <td scope="col">
                                        <input type="text" id="rc_date" name="rc_date" value=""
                                            class="form-control form-control-sm user_enter_date w-50" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left font-weight-bold"><span>Payment Mode</span></td>
                                    <td scope="col">
                                        <select id="pay_mode" name="pay_mode" class="form-control form-control-sm  w-50">
                                            <option value="">Choose</option>
                                            <?php 
												$sel_payment_type="select paymenttypeid, paymenttype as paymenttype_en, paymenttype_ta from master.m_paymenttype where del_flag is null and paymenttypeid in(1);";
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
                                    <td class="text-left font-weight-bold"><span DisplayLabelID="483">Cheque No</span>
                                    </td>
                                    <td scope="col">
                                        <input type="text" id="cheque_no" name="cheque_no" class="form-control form-control-sm w-50" />
                                    </td>
                                </tr>
                                <tr class="pay_mode_cheque" style="display: none;">
                                    <td class="text-left font-weight-bold"><span DisplayLabelID="484">Cheque Date</span>
                                    </td>
                                    <td scope="col">
                                        <input type="text" id="cheque_date" name="cheque_date" value=""
                                            class="form-control form-control-sm user_enter_date w-50" />
                                    </td>
                                </tr>
                                <tr class="pay_mode_dd" style="display: none;">
                                    <td class="text-left font-weight-bold"><span DisplayLabelID="483">DD No</span></td>
                                    <td scope="col">
                                        <input type="text" id="dd_no" name="dd_no" class="form-control form-control-sm w-50" />
                                    </td>
                                </tr>
                                <tr class="pay_mode_dd" style="display: none;">
                                    <td class="text-left font-weight-bold"><span DisplayLabelID="484">DD Date</span>
                                    </td>
                                    <td scope="col">
                                        <input type="text" id="dd_date" name="dd_date" value=""
                                            class="form-control form-control-sm user_enter_date w-50" />
                                    </td>
                                </tr>
                                <tr class="pay_mode_ecs" style="display: none;">
                                    <td class="text-left font-weight-bold"><span DisplayLabelID="483">ECS No</span></td>
                                    <td scope="col">
                                        <input type="text" id="ecs_no" name="ecs_no" class="form-control form-control-sm w-50" />
                                    </td>
                                </tr>
                                <tr class="pay_mode_ecs" style="display: none;">
                                    <td class="text-left font-weight-bold"><span DisplayLabelID="484">ECS Date</span>
                                    </td>
                                    <td scope="col">
                                        <input type="text" id="ecs_date" name="ecs_date" value=""
                                            class="form-control form-control-sm user_enter_date w-50" />
                                    </td>
                                </tr>
                                <tr  style="display: none;"  id="bank_code_row">
                                    <td class="text-left font-weight-bold"><span DisplayLabelID="483">Bank Code </span>
                                    </td>
                                    <td scope="col">
                                        <select id="bank_code" name="bank_code" class="form-control form-control-sm w-50">
                                            <option value="">Choose</option>
                                            <?php
                                                    $sel_bank_new_id = "SELECT bank_id, bank_code, bank_name_".$lang_code_2d." FROM accounts_master.m_bank WHERE isactive = :isactive AND del_flag IS NULL ORDER BY bank_code ASC";
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
                                <tr id="bank_name_row" style="display: none;">
                                    <td class="text-left font-weight-bold"><span DisplayLabelID="483">Bank Name</span>
                                    </td>
                                    <td scope="col">
                                        <input type="text" id="bank_name" name="bank_name" maxlength="500" value="" class="form-control  form-control-sm Tax_Form_English_Ownername_Property_Tax first_letter_uppercase w-50" readonly/>
                                    </td>
                                </tr>    
                                <tr>
                                    <td class="text-left font-weight-bold"><span DisplayLabelID="483">Name and Address of Remitter</span></td>
                                    <td scope="col">
                                        <textarea id="remitter_name_address" name="remitter_name_address" rows="4" cols="50" class="form-control w-50 form-control-sm"></textarea>
                                        <span>Max 250 Characters</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left font-weight-bold"><span DisplayLabelID="483">Cash From</span>
                                    </td>
                                    <td scope="col">
                                        <!-- <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="accounts" name="cash_from_type" value="Accounts" class="custom-control-input">
                                            <label class="custom-control-label" for="accounts"><span DisplayLabelID="371">Accounts</span></label>
                                        </div> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; -->
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="collection" name="cash_from_type" value="Collection" class="custom-control-input" checked='checked'>
                                            <label class="custom-control-label" for="collection"><span  DisplayLabelID="372">Collection</span></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="cash_coll_date_row">
                                    <td class="text-left font-weight-bold"><span DisplayLabelID="483">Cash Collection Date</span></td>
                                    <td scope="col">
                                        <input type="text" id="cash_coll_date" name="cash_coll_date" value="" class="form-control form-control-sm user_enter_date w-50" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left font-weight-bold"><span DisplayLabelID="483">Account Code</span></td>
                                    <td scope="col">
                                        <select id="account_code" name="account_code" class="form-control form-control-sm w-50">
                                            <option value="">Choose</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left font-weight-bold"><span DisplayLabelID="483">Amount</span></td>
                                    <td scope="col">
                                        <input type="text" id="amount" name="amount" class="form-control form-control-sm w-50 number_field" />
                                    </td>
                                </tr>                          
                                <tr>
                                    <td class="text-left font-weight-bold"><span DisplayLabelID="483">Narration</span>
                                    </td>
                                    <td scope="col">
                                        <textarea id="narration" name="narration" rows="4" cols="50" class="form-control w-50 form-control-sm"></textarea>
                                        <span>Max 250 Characters</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="text-align: center !important;">
                                        <span DisplayLabelID="484">Print</span>
                                    </td>
                                    <td align="left">
                                        <input type="checkbox" id="print" name="print" value="1" checked />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center">
                                        <center>
                                            <input type="submit" id="btn_save" name="btn_save" value="Save"
                                                class="btn btn-md text-white font-weight-bold  btn-success" />
                                        </center>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
        $this->Template($this->getCurrentUserTemplate() != "" ? $this->getCurrentUserTemplate() : "Template1", "Adjust Triplicate Challan Form", $ob_output_main_contents, array(), array('page_id' => 12));
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
        }else{
			unset($_SESSION[$this->page_token]);
		}
        $statecode = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();
		
        $cheque_no=$cheque_date=$bank_name=$bank_code=$dd_no=$dd_date=$ecs_no=$ecs_date=$tax_type=NULL;
		if (isset($save_data['rc_serial_no']) && $save_data['rc_serial_no']!='') {
			$rc_serial_no = $save_data['rc_serial_no'];
		}else{
			$this->main_content(array_merge(array(
				"STATUS" => "ERROR",
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "rc_serial_no",
				"MESSAGE" => 'Missing Serail Number'
			), $save_data));
			exit;
		}
		if (isset($save_data['rc_date']) && $save_data['rc_date']!='') {
			list($date_dateofreceived,$month_dateofreceived,$year_dateofreceived)=explode('-',$save_data['rc_date']);
			$rc_date=$year_dateofreceived.'-'.$month_dateofreceived.'-'.$date_dateofreceived;
			$rc_dateValidation = $this->Field_Validation(
				array(
					'Field_Type' => 'date',
					'Field_Value' => $save_data['rc_date'],
					'Field_Name' => 'rc_date',
					'Field_Format' => 'dd-mm-yyyy',
					'Field_Label_Name' => 'Chalan Date',
				)
			);
			if ($rc_dateValidation['Status'] == "Error") {
				$this->main_content(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "rc_date",
					"MESSAGE" => $rc_dateValidation['Message']
				), $save_data));
				exit;
			}
		}else{
			 $this->main_content(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "rc_date",
					"MESSAGE" => 'Invalid Challan Date'
				), $save_data));
				exit;
		}
		if (isset($save_data['pay_mode']) && $save_data['pay_mode']!='') {
			$pay_mode = $save_data['pay_mode'];
			$pay_modeValidation = $this->Field_Validation(
				array(
					'Field_Type' => 'number',
					'Field_Value' => $pay_mode,
					'Field_Name' => 'pay_mode',
					'Field_Max_length' => '2',
					'Field_Label_Name' => 'Payment Mode',
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
		}else{
			$this->main_content(array_merge(array(
				  "STATUS" => "ERROR",
				  "STATUS_TYPE" => "FIELD",
				  "FIELD_NAME" => "pay_mode",
				  "MESSAGE" => 'Select Payment Mode'
			  ), $save_data));
			  exit;
		}
		if (isset($save_data['pay_mode']) && $save_data['pay_mode'] == "2") {
			if (isset($save_data['cheque_no']) && $save_data['cheque_no']!='') {
				$cheque_no = $save_data['cheque_no'];    
				$cheque_noValidation = $this->Field_Validation(
					array(
						'Field_Type' => 'text_number',
						'Field_Value' => $cheque_no,
						'Field_Name' => 'cheque_no',
						'Field_Max_length' => '10',
						'Field_Label_Name' => 'Cheque Number',
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
			}else{
				$this->main_content(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "cheque_no",
					"MESSAGE" => 'Enter Cheque Number'
				), $save_data));
				exit;
			}
			if (isset($save_data['cheque_date']) && $save_data['cheque_date']!='') {
				list($date_dateofreceived,$month_dateofreceived,$year_dateofreceived)=explode('-',$save_data['cheque_date']);
				$cheque_date=$year_dateofreceived.'-'.$month_dateofreceived.'-'.$date_dateofreceived;
				$cheque_dateValidation = $this->Field_Validation(
					array(
						'Field_Type' => 'date',
						'Field_Value' => $save_data['cheque_date'],
						'Field_Name' => 'cheque_date',
						'Field_Format' => 'dd-mm-yyyy',
						'Field_Label_Name' => 'Cheque Date',
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
			}else{
				$this->main_content(array_merge(array(
					  "STATUS" => "ERROR",
					  "STATUS_TYPE" => "FIELD",
					  "FIELD_NAME" => "cheque_date",
					  "MESSAGE" => 'Select Cheque Date'
				  ), $save_data));
				  exit;
			}
			$dd_no=$dd_date=$ecs_no=$ecs_date=NULL;
		}
		if (isset($save_data['pay_mode']) && $save_data['pay_mode'] == "3") {
			if (isset($save_data['dd_no']) && $save_data['dd_no']!='') {
				$dd_no = $save_data['dd_no'];
				$dd_noValidation = $this->Field_Validation(
					array(
						'Field_Type' => 'text_number',
						'Field_Value' => $dd_no,
						'Field_Name' => 'dd_no',
						'Field_Max_length' => '10',
						'Field_Label_Name' => 'DD Number',
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
			}else{
				$this->main_content(array_merge(array(
					  "STATUS" => "ERROR",
					  "STATUS_TYPE" => "FIELD",
					  "FIELD_NAME" => "dd_no",
					  "MESSAGE" => 'Enter DD Number'
				  ), $save_data));
				  exit;
			}
			if (isset($save_data['dd_date']) && $save_data['dd_date']!='') {
				list($date_dateofreceived,$month_dateofreceived,$year_dateofreceived)=explode('-',$save_data['dd_date']);
				$dd_date=$year_dateofreceived.'-'.$month_dateofreceived.'-'.$date_dateofreceived;
				$dd_dateValidation = $this->Field_Validation(
					array(
						'Field_Type' => 'date',
						'Field_Value' => $save_data['dd_date'],
						'Field_Name' => 'dd_date',
						'Field_Format' => 'dd-mm-yyyy',
						'Field_Label_Name' => 'DD Date',
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
			}else{
				$this->main_content(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "dd_date",
					"MESSAGE" => 'Select DD Date'
				), $save_data));
				exit;
			}
			$cheque_no=$cheque_date=$ecs_no=$ecs_date=NULL;
		}
		if (isset($save_data['pay_mode']) && $save_data['pay_mode'] == "4") {
			if (isset($save_data['ecs_no']) && $save_data['ecs_no']!='') {
				$ecs_no = $save_data['ecs_no'];
				$ecs_noValidation = $this->Field_Validation(
					array(
						'Field_Type' => 'text_number',
						'Field_Value' => $ecs_no,
						'Field_Name' => 'ecs_no',
						'Field_Max_length' => '10',
						'Field_Label_Name' => 'ECS Number',
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
			}else{
				$this->main_content(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "ecs_no",
					"MESSAGE" => 'Enter ECS Number'
				), $save_data));
				exit;
			}
			if (isset($save_data['ecs_date']) && $save_data['ecs_date']!='') {
				list($date_dateofreceived,$month_dateofreceived,$year_dateofreceived)=explode('-',$save_data['ecs_date']);
				$ecs_date=$year_dateofreceived.'-'.$month_dateofreceived.'-'.$date_dateofreceived;
				$ecs_dateValidation = $this->Field_Validation(
					array(
						'Field_Type' => 'date',
						'Field_Value' => $save_data['ecs_date'],
						'Field_Name' => 'ecs_date',
						'Field_Format' => 'dd-mm-yyyy',
						'Field_Label_Name' => 'ECS Date',
					)
				);
				if ($ecs_dateValidation['Status'] == "Error") {
					$this->main_content(array_merge(array(
						"STATUS" => "ERROR",
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "ecs_date",
						"MESSAGE" => $ecs_dateValidation['Message']
					), $save_data));
					exit;
				}
			}else{
				$this->main_content(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "ecs_date",
					"MESSAGE" => 'Select ECS Date'
				), $save_data));
				exit;
			}
			$cheque_no=$cheque_date=$dd_no=$dd_date=NULL;
		}
		// if (isset($save_data['bank_code']) && $save_data['bank_code']!='') {
		// 	$bank_code = $save_data['bank_code'];
		// 	$bank_codeValidation = $this->Field_Validation(
		// 		array(
		// 			'Field_Type' => 'number',
		// 			'Field_Value' => $bank_code,
		// 			'Field_Name' => 'bank_code',
		// 			'Field_Max_length' => '60',
		// 			'Field_Label_Name' => 'Bank Code',
		// 		)
		// 	);
		// 	if ($bank_codeValidation['Status'] == "Error") {
		// 		$this->main_content(array_merge(array(
		// 			"STATUS" => "ERROR",
		// 			"STATUS_TYPE" => "FIELD",
		// 			"FIELD_NAME" => "bank_code",
		// 			"MESSAGE" => $bank_codeValidation['Message']
		// 		), $save_data));
		// 		exit;
		// 	}
		// }else{
		// 	$this->main_content(array_merge(array(
		// 		"STATUS" => "ERROR",
		// 		"STATUS_TYPE" => "FIELD",
		// 		"FIELD_NAME" => "bank_code",
		// 		"MESSAGE" => 'Select Bank Code'
		// 	), $save_data));
		// 	exit;
		// }
		if (isset($save_data['remitter_name_address']) && $save_data['remitter_name_address']!='') {
			$remitter_name_address = $save_data['remitter_name_address'];
			$remitter_name_addressValidation = $this->Field_Validation(
				array(
					'Field_Type' => 'text_space',
					'Field_Value' => $remitter_name_address,
					'Field_Name' => 'remitter_name_address',
					'Field_Max_length' => '60',
					'Field_Label_Name' => 'Name and Address of Remitter',
				)
			);
			if ($remitter_name_addressValidation['Status'] == "Error") {
				$this->main_content(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "remitter_name_address",
					"MESSAGE" => $remitter_name_addressValidation['Message']
				), $save_data));
				exit;
			}
		}else{
			$this->main_content(array_merge(array(
				"STATUS" => "ERROR",
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "remitter_name_address",
				"MESSAGE" => 'Enter Name and Address of Remitter'
			), $save_data));
			exit;
		}
		if (isset($save_data['cash_from_type']) && $save_data['cash_from_type']!='') {
			$cash_from_type = $save_data['cash_from_type'];
			$cash_from_typeValidation = $this->Field_Validation(
				array(
					'Field_Type' => 'text',
					'Field_Value' => $cash_from_type,
					'Field_Name' => 'cash_from_type',
					'Field_Max_length' => '10',
					'Field_Label_Name' => 'Cash From Type',
				)
			);
			if ($cash_from_typeValidation['Status'] == "Error") {
				$this->main_content(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "cash_from_type",
					"MESSAGE" => $cash_from_typeValidation['Message']
				), $save_data));
				exit;
			}
			if ($cash_from_type == "Collection") {
				if (isset($save_data['cash_coll_date']) && $save_data['cash_coll_date']!='') {
					list($date_dateofreceived,$month_dateofreceived,$year_dateofreceived)=explode('-',$save_data['cash_coll_date']);
					$cash_coll_date=$year_dateofreceived.'-'.$month_dateofreceived.'-'.$date_dateofreceived;
					$cash_coll_dateValidation = $this->Field_Validation(
						array(
							'Field_Type' => 'date',
							'Field_Value' => $save_data['cash_coll_date'],
							'Field_Name' => 'cash_coll_date',
							'Field_Format' => 'dd-mm-yyyy',
							'Field_Label_Name' => 'Cash Collection Date',
						)
					);
					if ($cash_coll_dateValidation['Status'] == "Error") {
						$this->main_content(array_merge(array(
							"STATUS" => "ERROR",
							"STATUS_TYPE" => "FIELD",
							"FIELD_NAME" => "cash_coll_date",
							"MESSAGE" => $cash_coll_dateValidation['Message']
						), $save_data));
						exit;
					}
				}else{
					$this->main_content(array_merge(array(
						"STATUS" => "ERROR",
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "cash_coll_date",
						"MESSAGE" => 'Enter Cash Collection Date'
					), $save_data));
					exit;
				}
			} else {
				$cash_coll_date = NULL;
			}
		}else{
			$this->main_content(array_merge(array(
				  "STATUS" => "ERROR",
				  "STATUS_TYPE" => "FIELD",
				  "FIELD_NAME" => "cash_from_type",
				  "MESSAGE" => 'Select Cash From Type'
			  ), $save_data));
			  exit;
		}
		if (isset($save_data['account_code']) && $save_data['account_code']!='') {
			$account_code = $save_data['account_code'];
			$account_codeValidation = $this->Field_Validation(
				array(
					'Field_Type' => 'number',
					'Field_Value' => $account_code,
					'Field_Name' => 'account_code',
					'Field_Max_length' => '60',
					'Field_Label_Name' => 'Account Code',
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
		}else{
			$this->main_content(array_merge(array(
				"STATUS" => "ERROR",
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "account_code",
				"MESSAGE" => 'Select Account Code'
			), $save_data));
			exit;
		}
		if (isset($save_data['amount']) && $save_data['amount']!='') {
			$amount = $save_data['amount'];
			$amountValidation = $this->Field_Validation(
				array(
					'Field_Type' => 'number',
					'Field_Value' => $amount,
					'Field_Name' => 'amount',
					//'Field_Max_length'=>'30',
					'Field_Label_Name' => 'Amount',
				)
			);
			if ($amountValidation['Status'] == "Error") {
				$this->main_content(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "amount",
					"MESSAGE" => $amountValidation['Message']
				), $save_data));
				exit;
			}
		}else{
			$this->main_content(array_merge(array(
				"STATUS" => "ERROR",
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "amount",
				"MESSAGE" => 'Enter Amount'
			), $save_data));
			exit;
		}
		if (isset($save_data['narration']) && $save_data['narration']!='') {
			$narration = $save_data['narration'];
			$narrationValidation = $this->Field_Validation(
				array(
					'Field_Type' => 'text_space',
					'Field_Value' => $narration,
					'Field_Name' => 'narration',
					'Field_Max_length' => '60',
					'Field_Label_Name' => 'Narration',
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
		}else{
			 $this->main_content(array_merge(array(
				  "STATUS" => "ERROR",
				  "STATUS_TYPE" => "FIELD",
				  "FIELD_NAME" => "narration",
				  "MESSAGE" => 'Enter Narration'
			  ), $save_data));
			  exit;
		}
        $Result_Message = "Data Saved SuccessFully";
        $this->beginTransaction();
		$site_data = $this->siteData();
        $pp_assessment_initiation = "accounts_master.sp_adjust_triplicate_chalan";
		$edit_id=$del_id=0;
        $user_name = $this->getCurrentUser();
        $ip_address = $this->getIpAddress(); 
        if (isset($save_data["edit_id"])) {
            $save_query = "select * from " . $pp_assessment_initiation . "(:statecode, :dcode, :lbcode, :rc_serial_no, :rc_date, :pay_mode, :cheque_no, :cheque_date, :bank_name, :bank_code, :dd_no, :dd_date, :ecs_no, :ecs_date, :remitter_name_address, :cash_from_type, :cash_coll_date, :account_code, :amount, :narration, :isactive, :user_name, :ip_address, :edit_id, :del_id)";
            $res1 = $this->prepare($save_query, array(":statecode" => $statecode, ":dcode" => $dcode, ":lbcode" => $lbcode, ":rc_serial_no" => $rc_serial_no, ":rc_date" => $rc_date, ":pay_mode" => $pay_mode, ":cheque_no" => $cheque_no, ":cheque_date" => $cheque_date, ":bank_name" => $bank_name, ":bank_code" => $bank_code, ":dd_no" => $dd_no, ":dd_date" => $dd_date, ":ecs_no" => $ecs_no, ":ecs_date" => $ecs_date, ":remitter_name_address" => $remitter_name_address, ":cash_from_type" => $cash_from_type, ":cash_coll_date" => $cash_coll_date, ":account_code" => $account_code, ":amount" => $amount, ":narration" => $narration, ":isactive" => 1, ":user_name" => $user_name, ":ip_address" => $ip_address, ":edit_id" => $edit_id, ":del_id" => $del_id), 4);
        } else if (isset($save_data["del_id"])) {
           $save_query = "select * from " . $pp_assessment_initiation . "(:statecode, :dcode, :lbcode, :rc_serial_no, :rc_date, :pay_mode, :cheque_no, :cheque_date, :bank_name, :bank_code, :dd_no, :dd_date, :ecs_no, :ecs_date, :remitter_name_address, :cash_from_type, :cash_coll_date, :account_code, :amount, :narration, :isactive, :user_name, :ip_address, :edit_id, :del_id)";
            $res1 = $this->prepare($save_query, array(":statecode" => $statecode, ":dcode" => $dcode, ":lbcode" => $lbcode, ":rc_serial_no" => $rc_serial_no, ":rc_date" => $rc_date, ":pay_mode" => $pay_mode, ":cheque_no" => $cheque_no, ":cheque_date" => $cheque_date, ":bank_name" => $bank_name, ":bank_code" => $bank_code, ":dd_no" => $dd_no, ":dd_date" => $dd_date, ":ecs_no" => $ecs_no, ":ecs_date" => $ecs_date, ":remitter_name_address" => $remitter_name_address, ":cash_from_type" => $cash_from_type, ":cash_coll_date" => $cash_coll_date, ":account_code" => $account_code, ":amount" => $amount, ":narration" => $narration, ":isactive" => 1, ":user_name" => $user_name, ":ip_address" => $ip_address, ":edit_id" => $edit_id, ":del_id" => $del_id), 4);
        } else {
            $save_query = "select * from " . $pp_assessment_initiation . "(:statecode, :dcode, :lbcode, :rc_serial_no, :account_code, :amount, :rc_date, :cash_from_type, :cash_coll_date, :tax_type, :bank_code, :cheque_no, :cheque_date, :ecs_no, :ecs_date, :dd_no, :dd_date, :pay_mode, :remitter_name_address, :narration, :isactive, :user_name, :ip_address, :edit_id, :del_id)";
            $res1 = $this->prepare($save_query, array(":statecode" => $statecode, ":dcode" => $dcode, ":lbcode" => $lbcode, ":rc_serial_no" => $rc_serial_no, ":rc_date" => $rc_date, ":pay_mode" => $pay_mode, ":tax_type"=>$tax_type, ":cheque_no" => $cheque_no, ":cheque_date" => $cheque_date, ":bank_code" => $bank_code, ":dd_no" => $dd_no, ":dd_date" => $dd_date, ":ecs_no" => $ecs_no, ":ecs_date" => $ecs_date, ":remitter_name_address" => $remitter_name_address, ":cash_from_type" => $cash_from_type, ":cash_coll_date" => $cash_coll_date, ":account_code" => $account_code, ":amount" => $amount, ":narration" => $narration, ":isactive" => 1, ":user_name" => $user_name, ":ip_address" => $ip_address, ":edit_id" => 0, ":del_id" => 0), 4);
        }    
        if (!isset($res1->errorInfo)) {
			$this->commit();
			if(isset($save_data['print']) && $save_data['print']!=''){
				?>
				<script>
					alert("Data Saved SuccessFully");
                </script>
                <?php
				header("Location: ".$site_data->website_url."/project/forms/masters/triplicate.php?id=".base64_encode($res1['sp_adjust_triplicate_chalan']) ); 
				exit();	
			}else{
				$this->main_content(array(
					"STATUS" => "SUCCESS",
					"STATUS_TYPE" => "FORM",
					"MESSAGE" => $Result_Message
				));
				exit;
			}
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
$propertyassessment = new Adjust_Triplicate_Chalan();
if (!isset($_POST['cmd'])) {
    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        $propertyassessment->data_save(array_merge($_POST, $_GET));
    } else {
        $propertyassessment->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
    }
} else if (isset($_POST['cmd'])) {
    $cmd = base64_decode($_POST['cmd']);
    if ($cmd == 1) {
        if(isset($_POST['type']) && $_POST['type']!=''){
            $type= base64_decode($_POST['type']);
            $type_validation=$propertyassessment->Field_Validation(
                array(
                    'Field_Type' => 'text',
                    'Field_Value' => $type,
                    'Field_Name' => 'account_type',
                    'Field_Max_length' => '60',
                    'Field_Label_Name' => 'Account Type',
                )
            );
            if ($type_validation['Status'] == "Error") 
            {
                echo json_encode(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "account_type",
                "MESSAGE" => $type_validation['Message']
                 ), $_POST));
                exit;			
            } 
        }else{
            echo json_encode(array_merge(array(
            "STATUS" => "ERROR",
            "STATUS_TYPE" => "FIELD",
            "FIELD_NAME" => "account_type",
            "MESSAGE" => "Select Account Type"
            ), $_POST));
            exit;	
        }
        if($type == 'Accounts'){
            $account_type=1;
        }else{
            $account_type=2;
        }
        ?>		
		<option value=""  DisplayLabelID="255">Choose</option>
		<?php 
        $sel_dname="SELECT account_head_id, new_account_head_code,old_account_head_code,account_head_name_en,account_head_name_ta,isactive, account_type FROM accounts_master.m_account_head where del_flag is null and account_type=:account_type and isactive=:isactrive;";
        $sel_dname_res=$propertyassessment->prepare($sel_dname,array( ":account_type"=>$account_type, ":isactrive"=>1),2);
         foreach($sel_dname_res as $sel_dname_key=>$sel_dname_row)
        {
        ?>	
            <option value="<?php echo htmlentities($sel_dname_row['account_head_id']); ?>" ><?php echo htmlentities($sel_dname_row['account_head_name_en']." - ". $sel_dname_row['old_account_head_code']); ?></option>
        <?php	
        }
		exit;
    }
}
?>