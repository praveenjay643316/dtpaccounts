<?php
require_once '../../config/config.php';
class Add_AdvanceDepositDetails extends ConfigClass
{

  public $page_token = "Add_Bank_Branch";

  public function __construct()
  {
    // $this->pageRoleAccessCheck(array(1));
  }

  public function main_form($data_array = array())
  {

    ob_start();
    // #############

    // PAGE CONTENT START

    // #############

    // PLACE YOUR CODE HERE
	?>
    <input type="hidden" id="page_lable_id" name="page_lable_id" value="199" />
    <?php
    $lang_code_2d = $this->getCurrentUserLanguage2D();
    if (!isset($data_array['mode_name'])) {
      $data_array['mode_class'] = 'btn-success';
      $data_array['mode_icon'] = 'fa fa-floppy-o';
      $data_array['mode_name'] = 'Save';
    }
    $statecode = $this->getCurrentStateCode();
    $dcode = $this->getCurrentDistrictCode();
    $lbcode = $this->getCurrentLocalBodyCode();
   
    ?>
    <script type='text/javascript'>
      $(document).ready(function() {
		$('.alpha').keyup(function () {
		  let result = $(this).val().replace(/[^a-zA-Z]/g, '');
		  $(this).val(result);
		});	  
		$(document).on('keyup', ".upper_case", function() {
			 $(this).val($(this).val().toUpperCase());
		});
		$(document).on('change', "#ifsc_code", function() {
			 var ifsc_code = $(this).val();
			 var reg = "^[A-Z]{4}[0][A-Z0-9]{6}$";
			 if (ifsc_code.match(reg)) {    
			    return true;    
			}else{
				alert('Invalid IFSC Code');
				$("#ifsc_code").val('');
			} 
		});
        <?php if (!isset($data_array["del_id"])) { ?>
          $("#save").on('click', function() {
            var Current_Field_id = $(this).attr('id');
            $('#' + Current_Field_id).hide();
            try {
              if ($("#bank_code").val().length == '') {
                throw {
                  msg: "Select Bank Name",
                  foc: "#bank_code"
                }
              }
              if ($("#branch_code").val().length == '') {
                throw {
                  msg: "Enter Branch Code",
                  foc: "#branch_code"
                }
              }
              if ($("#branch_name_en").val().length == '') {
                throw {
                  msg: "Enter Branch Name in English",
                  foc: "#branch_name_en"
                }
              }
              if ($("#branch_name_ta").val().length == '') {
                throw {
                  msg: "Enter Branch Name in Tamil",
                  foc: "#branch_name_ta"
                }
              }
              if ($("#ifsc_code").val().length == '') {
                throw {
                  msg: "Enter IFSC Code",
                  foc: "#ifsc_code"
                }
              }
              if ($("#bankaddress").val().length == '') {
                throw {
                  msg: "Enter Bank Branch Address",
                  foc: "#bankaddress"
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
        <?php } ?>
      });
    </script>
    <style>
      .hidden_field_element_value {
        display: none;
      }
      .bg-table-form-dsg {
        background-color: #35577c !important;
        color: white;
        text-transform: capitalize !important;
        text-align: center !important;
      }
      .bg-table-report-dsg {
        background-color: #608db9 !important;
        color: white;
        text-transform: capitalize !important;
        text-align: center !important;
      }
    </style>
    <div class="container pt-3">
    <div class="row">
      <div class="col-md-12">
        <?php
        if (isset($data_array["STATUS"])) {
          echo $this->ShowMessage($data_array["STATUS"], $data_array["MESSAGE"]);
        }
        ?>
        <div class="card">
          <div class="card-body">
            <form action="Add_Bank_Branch.php" method="post"   autocomplete="off">
              <input class="form-control w-75  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>" name="<?php echo htmlentities($this->page_token); ?>" value="<?php echo htmlentities($this->token($this->page_token)); ?>">
              <?php
              if (isset($data_array["edit_id"]) && $data_array["edit_id"] != "") {
                $data_array["mode_name"] = "Update";
                $data_array["mode_class"] = "btn-warning"
              	?>
                <input class="form-control w-75  form-control-sm" type="hidden" id="edit_id" name='edit_id' value="<?php echo htmlentities($data_array["edit_id"]); ?>">
              	<?php
                $edit_id = htmlentities(base64_decode($data_array["edit_id"]));
                $edit_query = " SELECT bankbranch_id, bank_id, bankbranch_code, bankbranch_name, bankbranch_name_ll, bank_branch_address, ifsccode from accounts_master.m_bankbranch where isactive=:isactive and del_flag is null and bankbranch_id=:edit_id";
                $data_array_edit = $this->prepare($edit_query, array(":edit_id" => $edit_id, ":isactive" => 1), 4);
                $data_array = array_merge($data_array, $data_array_edit);
              } else if (isset($data_array["del_id"]) && $data_array["del_id"] != "") {
                $data_array["mode_name"] = "Delete";
                $data_array["mode_class"] = "btn-danger"
              	?>
                <input class="form-control w-50  form-control-sm" type="hidden" id="del_id" name='del_id' value="<?php echo htmlentities(base64_decode($data_array["del_id"])); ?>">
              	<?php
                $del_id = htmlentities(base64_decode($data_array["del_id"]));
                $delete_query = " SELECT a.bankbranch_id,a.bank_id,a.bankbranch_code,a.bankbranch_name,a.bankbranch_name_ll,a.ifsccode,a.bank_branch_address,b.bank_name_".$lang_code_2d.",b.bank_code FROM (SELECT bankbranch_id, bank_id, bankbranch_code, bankbranch_name, bankbranch_name_ll, bank_branch_address, ifsccode from accounts_master.m_bankbranch WHERE isactive=:isactive and del_flag is null and bankbranch_id=:del_id)a
                LEFT JOIN
                (select bank_id, bank_code, bank_name_".$lang_code_2d." from accounts_master.m_bank where del_flag is null and isactive = :isactive)b
                on a.bank_id=b.bank_id;";
                $data_array_delete = $this->prepare($delete_query, array(":del_id" => $del_id, ":isactive" => 1), 4);
                $data_array = array_merge($data_array, $data_array_delete);
              }
              ?>

              <table class="table table-bordered table-striped tndtp_form_table">
                <thead>
                  <tr>
                    <th colspan="4"> <span><?php echo htmlentities('Bank Branch Entry Form '); ?></span></th>
                  </tr>
                </thead>
                <tr>
                  <td width="25%" class="text-left font-weight-bold">
                    <span class="text-danger font-weight-bold">*</span>
                    <span><?php echo htmlentities('Bank Name '); ?></span>
                  </td>
                  <td width="25%" scope="col">
                    <?php
                    if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                      if (isset($data_array['bank_id']) && $data_array['bank_id']!='') {
                        echo htmlentities($data_array['bank_id']);
                      }
                    } else {
                    ?>
                      <select id="bank_code" name="bank_code" class="form-control form-control-sm">
                            <option value="">Choose</option>
                            <?php
							$sel_qry = "select bank_id, bank_code, bank_name_".$lang_code_2d." from accounts_master.m_bank where del_flag is null and isactive = :isactive;";
	
							$sel_qry_res = $this->prepare($sel_qry, array(":isactive"=>1), 2);
	
							foreach ($sel_qry_res as $sel_qry_key => $sel_qry_row) {
							?>
							  <option value="<?php echo htmlentities($sel_qry_row['bank_id']); ?>"><?php echo htmlentities($sel_qry_row['bank_name_'.$lang_code_2d]); ?></option>
	
							<?php } ?>
                      </select>
                      <script>
                        document.getElementById('bank_code').value =
                                            '<?php if (isset($data_array['bank_id'])) {
                                                    echo htmlentities($data_array['bank_id']);
                                                } ?>';
                      </script>
                     
                    <?php
                    }
                    ?>

                  </td>
                  <td class="text-left font-weight-bold">
                    <span class="text-danger font-weight-bold">*</span>
                    <span><?php echo htmlentities('Branch Code '); ?></span>
                  </td>
                  <td scope="col">

                    <?php
                    if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                      if (isset($data_array['bankbranch_code']) && $data_array['bankbranch_code']!='') {
                        echo htmlentities($data_array['bankbranch_code']);
                      }
                    } else {
                    ?>
                      <input class="form-control form-control-sm alpha_numeric_without_space upper_case" maxlength="10" type="text" placeholder="Enter Branch Code" id="branch_code" name='branch_code' value="<?php if (isset($data_array['bankbranch_code']) && $data_array['bankbranch_code']!='') { echo htmlentities($data_array['bankbranch_code']); } ?>">
                    <?php
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <td class="text-left font-weight-bold">
                    <span class="text-danger font-weight-bold">*</span>
                    <span><?php echo htmlentities('Branch Name In English '); ?></span>
                  </td>
                  <td scope="col">

                    <?php
                    if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                      if (isset($data_array['bankbranch_name']) && $data_array['bankbranch_name']!='') {
                        echo htmlentities($data_array['bankbranch_name']);
                      }
                    } else {
                    ?>
                      <input class="form-control form-control-sm  name_eng_with_space" type="text" placeholder="Enter Branch Name in English" id="branch_name_en" name='branch_name_en' value="<?php if (isset($data_array['bankbranch_name']) && $data_array['bankbranch_name']!='') {   echo htmlentities($data_array['bankbranch_name']);  } ?>">
                    <?php
                    }
                    ?>

                  </td>
                   <td class="text-left font-weight-bold">
                    <span class="text-danger font-weight-bold">*</span>
                    <span><?php echo htmlentities('Branch Name in Tamil '); ?></span>
                  </td>
                  <td scope="col">

                    <?php
                    if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                      if (isset($data_array['bankbranch_name_ll']) && $data_array['bankbranch_name_ll']!='') {
                        echo htmlentities($data_array['bankbranch_name_ll']);
                      }
                    } else {
                    ?>
                      <input class="form-control form-control-sm  name_tamil_comma_dot" type="text" placeholder="Enter Branch Name in Tamil" id="branch_name_ta" name='branch_name_ta' value="<?php if (isset($data_array['bankbranch_name_ll'])) { echo htmlentities($data_array['bankbranch_name_ll']); } ?>">
                    <?php
                    }
                    ?>

                  </td>

                </tr>

                <tr>
                 
                  <td class="text-left font-weight-bold">
                  <span class="text-danger font-weight-bold">*</span>
                    <span><?php echo htmlentities('IFSC Code '); ?></span>
                  </td>
                  <td scope="col">

                    <?php
                    if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                      if (isset($data_array['ifsccode']) && $data_array['ifsccode']!='') {
                        echo htmlentities($data_array['ifsccode']);
                      }
                    } else {
                    ?>
                      <input class="form-control form-control-sm alpha_numeric_without_space upper_case" type="text" placeholder="Enter IFSC CODE" id="ifsc_code" name='ifsc_code' value="<?php if (isset($data_array['ifsccode']) && $data_array['ifsccode']!='') { echo htmlentities($data_array['ifsccode']); } ?>">
                    <?php
                    }
                    ?>

                  </td>
                  <td class="text-left font-weight-bold">
                    <span class="text-danger font-weight-bold">*</span>
                    <span><?php echo htmlentities('Bank Branch Address '); ?></span>
                  </td>
                  <td colspan="3" scope="col">

                    <?php
                    if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                      if (isset($data_array['bank_branch_address'])) {
                        echo htmlentities($data_array['bank_branch_address']);
                      }
                    } else {
                    ?>
                      <textarea class="form-control form-control-sm address_field" type="text" placeholder="Enter Address" id="bankaddress" name='bankaddress' value="
                      <?php if (isset($data_array['bank_branch_address']) && $data_array['bank_branch_address']!='') {
                        echo htmlentities($data_array['bank_branch_address']);
                      } ?>"><?php if (isset($data_array['bank_branch_address']) && $data_array['bank_branch_address']!='') {
                              echo htmlentities($data_array['bank_branch_address']);
                            } ?></textarea>
                    <?php
                    }
                    ?>

    				</td>
                </tr>
                <tr>
                  <td colspan="4" class="text-center">
                    <?php

                    if (!isset($data_array["mode_name"])) {
                      $data_array["mode_name"] = "Save";
                    }

                    ?>

                    <input type="submit" class="btn <?php echo $data_array["mode_class"]; ?> btn-sm text-white" name="save" id="save" value="<?php echo $data_array["mode_name"]; ?>" />&nbsp;&nbsp;
                    <input type="button" class="btn btn-secondary btn-sm" onclick="window.location='Add_Bank_Branch.php'" value="Clear" />


                  </td>
                </tr>
                </thead>

              </table>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-12 mt-4">
        <div class="card">
          <div class="card-body">
            <h4 class="header-title">
              <span><?php echo htmlentities('Bank Branch Details '); ?></span> 
            </h4>
            <div class="single-table">
              <table class="table table-bordered m-0 p-0 table-striped tndtp_report_table" id="dataTable2">
                <thead>
                  <tr>
                    <td><span><?php echo htmlentities('Sl. No'); ?></span></td>
                    <th scope="col"><span><?php echo htmlentities('Branch Code '); ?></span></th>
                    <th scope="col"><span><?php echo htmlentities('Bank Name '); ?></span></th>
                    <th scope="col"><span><?php echo htmlentities('Branch Name In English '); ?></span></th>
                    <th scope="col"><span><?php echo htmlentities('Branch Name in Tamil '); ?></span></th>
                    <th scope="col"><span><?php echo htmlentities('IFSC Code '); ?></span></th>
                    <th scope="col"><span><?php echo htmlentities('Bank Branch Address '); ?></span></th>
                    <th><span><?php echo htmlentities('Action'); ?></span></th>
                  </tr>
                </thead>
                <tbody>
                <?php
                $list_com = " SELECT a.bankbranch_id, a.bank_id, a.bankbranch_code, a.bankbranch_name_ta, a.bankbranch_name_en, a.ifsccode, a.bank_branch_address, b.bank_name_".$lang_code_2d.", b.bank_code FROM
                (SELECT bankbranch_id, bank_id, bankbranch_code, bankbranch_name as bankbranch_name_en, bankbranch_name_ll as bankbranch_name_ta, ifsccode, bank_branch_address FROM accounts_master.m_bankbranch WHERE isactive=:isactive and del_flag is null)a
                LEFT JOIN
                (select bank_id, bank_code, bank_name_".$lang_code_2d." from accounts_master.m_bank where del_flag is null and isactive = :isactive)b
                on a.bank_id=b.bank_id;";
                $set = $this->prepare($list_com, array(":isactive"=>1), 2);
                $slno = 1;
                if(count($set) > 0){
                foreach ($set as $row) {
                ?>
                  
                    <tr>
                      <td><?php echo htmlentities($slno++); ?></td>
                      <td align="left"><?php echo htmlentities($row['bankbranch_code']); ?></td>
                      <td align="left"><?php echo htmlentities($row['bank_name_'.$lang_code_2d]); ?></td>
                      <td align="left"><?php echo htmlentities($row['bankbranch_name_en']); ?></td>
                      <td align="left"><?php echo htmlentities($row['bankbranch_name_ta']); ?></td>
                      <td align="left"><?php echo htmlentities($row['ifsccode']); ?></td>
                      <td align="left"><?php echo htmlentities($row['bank_branch_address']); ?></td>
                      <td align="center"><a href="?edit_id=<?php echo base64_encode(htmlentities($row['bankbranch_id'])); ?>" class="btn btn-warning btn-sm"><i class="fa fa-pencil pr-1" aria-hidden="true"></i>Edit</a>
                        <a href="?del_id=<?php echo base64_encode(htmlentities($row['bankbranch_id'])); ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash-o p-1" aria-hidden="true"></i>Delete</a>
                      </td>
                    </tr>
                  
                <?php
                }
              }else{
                ?>
<tr>
<td colspan="8" class="text-center text-danger"><?php echo htmlentities('No Records Found'); ?></td>
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
	<?php
    // #############

    // PAGE CONTENT END

    // #############

    $ob_output_main_forms = ob_get_contents();
    ob_clean();

    $this->Template("Template1", "Add Bank Branch", $ob_output_main_forms, array(
      array(
        "name" => "Add Bank Branch",
      ),
    ));
    exit();
  }

  public function data_save($save_data)
  {
    // TOKEN VALIDATE
    if (! $this->validateToken($this->page_token, $save_data[$this->page_token])) {
        $this->main_form(array_merge(array(
        "STATUS" => "ERROR",
        "STATUS_TYPE" => "FIELD",
        "FIELD_NAME" => $this->page_token,
        "MESSAGE" => "Invalid Token"
        ), $save_data));
        exit;
	}
	else
	{
		unset($_SESSION[$this->page_token]);
	}
         
    if (!isset($save_data["del_id"])) {
      	$ifsccode = $save_data['ifsc_code'];
     	$bank_branch_address = $save_data['bankaddress'];
		if(isset($save_data["bank_code"]) && $save_data["bank_code"]!=''){
			$bankid = $save_data['bank_code'];
		  	$reg_value_Validation = $this->Field_Validation(array(
				"Field_Type" => "number",
				"Field_Value" => $save_data["bank_code"],
				"Field_Max_length" => 10,
				"Field_Min_length" => 0,
				"Field_Label_Name" => "Bank Name",
			  ));
			  if ($reg_value_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
				  "STATUS" => "ERROR",
				  "STATUS_TYPE" => "FIELD",
				  "FIELD_NAME" => "bank_code",
				  "MESSAGE" => "Invalid Bank Name",
				), $save_data));
				exit;
			  }
			}else{
				$this->main_form(array_merge(array(
				  "STATUS" => "ERROR",
				  "STATUS_TYPE" => "FIELD",
				  "FIELD_NAME" => "bank_code",
				  "MESSAGE" => "Select Bank Name",
				), $save_data));
				exit;
			}	
		  if(isset($save_data["branch_code"]) && $save_data["branch_code"]!=''){
			  $bankbranchcode = $save_data['branch_code'];
			  $account_code_Validation = $this->Field_Validation(array(
				"Field_Type" => "text_number",
				"Field_Value" => $save_data["branch_code"],
				"Field_Max_length" => 10,
				"Field_Min_length" => 0,
				"Field_Label_Name" => "Branch Code",
			  ));
			  
			  if ($account_code_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
				  "STATUS" => "ERROR",
				  "STATUS_TYPE" => "FIELD",
				  "FIELD_NAME" => "branch_code",
				  "MESSAGE" => "Branch Code",
				), $save_data));
				exit;
			  }
		  }else{
		  		$this->main_form(array_merge(array(
				  "STATUS" => "ERROR",
				  "STATUS_TYPE" => "FIELD",
				  "FIELD_NAME" => "branch_code",
				  "MESSAGE" => "Enter Branch Code",
				), $save_data));
				exit;
		  }
		if(isset($save_data["branch_name_en"]) && $save_data["branch_name_en"]!=''){
     			$bankbranchname = $save_data['branch_name_en'];
			  $voucher_Validation = $this->Field_Validation(array(
				"Field_Type" => "text_comma_dot_space_slash",
				"Field_Value" => $save_data["branch_name_en"],
				"Field_Max_length" => 250,
				"Field_Min_length"=>0,
				"Field_Label_Name" => "Branch Name in English",
			  ));
		
			  if ($voucher_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
				  "STATUS" => "ERROR",
				  "STATUS_TYPE" => "FIELD",
				  "FIELD_NAME" => "branch_name_en",
				  "MESSAGE" => "Invalid Branch Name in English",
				), $save_data));
				exit;
			  }
		}else{
			$this->main_form(array_merge(array(
			  "STATUS" => "ERROR",
			  "STATUS_TYPE" => "FIELD",
			  "FIELD_NAME" => "branch_name_en",
			  "MESSAGE" => "Enter Branch Name in English",
			), $save_data));
			exit;
		}
		if(isset($save_data['branch_name_ta']) && $save_data['branch_name_ta']!=''){
			$branchname = $save_data['branch_name_ta'];
		}else{
			$this->main_form(array_merge(array(
			  "STATUS" => "ERROR",
			  "STATUS_TYPE" => "FIELD",
			  "FIELD_NAME" => "branch_name_ta",
			  "MESSAGE" => "Enter Branch Name in Tamil",
			), $save_data));
			exit;
		}
		if(isset($save_data['ifsc_code']) && $save_data['ifsc_code']!=''){
			$ifsccode = $save_data['ifsc_code'];
			$ifsccode_Validation = $this->Field_Validation(array(
			  "Field_Type" => "text_number",
			  "Field_Value" => $save_data["ifsc_code"],
			  "Field_Max_length" => 15,
			  "Field_Min_length"=>0,
			  "Field_Label_Name" => "IFSC Code",
			));
			if ($ifsccode_Validation['Status'] == "Error") {
				  $this->main_form(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "ifsccode",
					"MESSAGE" => "Invalid IFSC Code",
				  ), $save_data));
				  exit;
			}
		}else{
			$this->main_form(array_merge(array(
			  "STATUS" => "ERROR",
			  "STATUS_TYPE" => "FIELD",
			  "FIELD_NAME" => "ifsccode",
			  "MESSAGE" => "Enter IFSC Code",
			), $save_data));
			exit;
		}
		if(isset($save_data['bankaddress']) && $save_data['bankaddress']!=''){
			$bank_branch_address = $save_data['bankaddress'];
			$bank_branch_address_Validation = $this->Field_Validation(array(
			  "Field_Type" => "text_comma_dot_space_slash",
			  "Field_Value" => $save_data["bankaddress"],
			  "Field_Max_length" => 10,
			  "Field_Min_length"=>0,
			  "Field_Label_Name" => "Bank Branch Address",
			));
	  
			if ($ifsccode_Validation['Status'] == "Error") {
				  $this->main_form(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "bankaddress",
					"MESSAGE" => "Invalid Bank Branch Address",
				  ), $save_data));
				  exit;
			}
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR",
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "bankaddress",
				"MESSAGE" => "Enter Bank Branch Address",
			  ), $save_data));
			  exit;
		}
    }

    if (isset($save_data["del_id"]) && $save_data["del_id"]!='') {
      $role_name_Validation = $this->Field_Validation(array(
        "Field_Type" => "number",
        "Field_Value" => $save_data["del_id"],
        "Field_Max_length" => 10,
        "Field_Min_length" => 0,
      ));
      if ($role_name_Validation['Status'] == "Error") {
        $this->main_form(array_merge(array(
          "STATUS" => "ERROR",
          "STATUS_TYPE" => "FIELD",
          "FIELD_NAME" => "del_id",
          "MESSAGE" => "Invalid data",
        ), $save_data));
      }
    }

    if (isset($save_data["edit_id"]) && $save_data["edit_id"]!='') {
      $edit_id = base64_decode($save_data["edit_id"]);
      $role_name_Validation = $this->Field_Validation(array(
        "Field_Type" => "number",
        "Field_Value" => $edit_id,
        "Field_Max_length" => 10,
        "Field_Min_length" => 0,
      ));

      if ($role_name_Validation['Status'] == "Error") {
        $this->main_form(array_merge(array(
          "STATUS" => "ERROR",
          "STATUS_TYPE" => "FIELD",
          "FIELD_NAME" => "edit_id",
          "MESSAGE" => "Invalid data 1",
        ), $save_data));
      }
    }

    $statecode = $this->getCurrentStateCode();
    $dcode = $this->getCurrentDistrictCode();
    $lbcode = $this->getCurrentLocalBodyCode();
    $getCurrentUser = $this->getCurrentUser();
    $getIpAddress = $this->getIpAddress();
    $edit_id = isset($save_data["edit_id"]) ? base64_decode($save_data["edit_id"]) : 0;
    $del_id = isset($save_data["del_id"]) ? ($save_data["del_id"]) : 0;
    $ins_date = date('Y-m-d h:i:s');
    $Result_Message = "Data Saved SuccessFully";
    if ($edit_id > 0) {
      $Result_Message = "Data Updated SuccessFully";
    } else if ($del_id > 0) {
      $Result_Message = "Data Deleted SuccessFully";
    }
    $this->beginTransaction();
    if (isset($save_data["edit_id"])) {
      $save_query = "select * from accounts_master.sp_add_bank_branch (:bankid,:organizationid,:bankbranchcode,:bankbranchname,:branchname,:bank_branch_address,:ifsccode,:isactive,:ins_username,now()::timestamp without time zone,:ins_ipaddress::text,:edit_id,:del_id)";
      $res1 = $this->prepare($save_query, array(":bankid" => $bankid, ":organizationid" => null, ":bankbranchcode" => $bankbranchcode, ":bankbranchname" => $bankbranchname, ":branchname" => $branchname, ":bank_branch_address" => $bank_branch_address, ":ifsccode" => $ifsccode, ":isactive" => 1, ":ins_username" => $getCurrentUser, ":ins_ipaddress" => $getIpAddress, ":edit_id" => $edit_id, ":del_id" => $del_id), 4);
    } else if (isset($save_data["del_id"])) {
      $save_query = "select * from accounts_master.sp_add_bank_branch (:bankid,:organizationid,:bankbranchcode,:bankbranchname,:branchname,:bank_branch_address,:ifsccode,:isactive,:ins_username,now()::timestamp without time zone,:ins_ipaddress::text,:edit_id,:del_id)";
      $res1 = $this->prepare($save_query, array(":bankid" => null, ":organizationid" => null, ":bankbranchcode" => null, ":bankbranchname" => null, ":branchname" => null, ":bank_branch_address" => null, ":ifsccode" => null, ":isactive" => 0, ":ins_username" => $getCurrentUser, ":ins_ipaddress" => $getIpAddress, ":edit_id" => $edit_id, ":del_id" => $del_id), 4);
    } else {
      $save_query = "select * from accounts_master.sp_add_bank_branch (:bankid,:organizationid,:bankbranchcode,:bankbranchname,:branchname,:bank_branch_address,:ifsccode,:isactive,:ins_username,now()::timestamp without time zone,:ins_ipaddress::text,:edit_id,:del_id)";
      $res1 = $this->prepare($save_query, array(":bankid" => $bankid, ":organizationid" => null, ":bankbranchcode" => $bankbranchcode, ":bankbranchname" => $bankbranchname, ":branchname" => $branchname, ":bank_branch_address" => $bank_branch_address, ":ifsccode" => $ifsccode, ":isactive" => 1, ":ins_username" => $getCurrentUser, ":ins_ipaddress" => $getIpAddress, ":edit_id" => $edit_id, ":del_id" => $del_id), 4);
    }
    $this->commit();
    if ($this->prepareStatus($res1) == true) {
      $this->main_form(array(
        "STATUS" => "SUCCESS",
        "STATUS_TYPE" => "FORM",
        "MESSAGE" => $Result_Message,
      ));
    } else {
      $this->main_form(array(
        "STATUS" => "FAIL",
        "STATUS_TYPE" => "FORM",
        "MESSAGE" => "Data Save Failed Due To Duplicate Entry",
      ));
    }
  }
}
$home = new Add_AdvanceDepositDetails();
if (!isset($_POST['cmd'])) {
  if (isset($_POST["save"])) {
    $home->data_save($_POST);
  }
  if (isset($_GET["edit_id"]) && $_GET["edit_id"]!='') {
    $edit_id = base64_decode($_GET["edit_id"]);
    $edit_id_Validation = $home->Field_Validation(
      array(
        'Field_Type' => 'number',
        'Field_Value' => $edit_id,
        'Field_Name' => 'otax_two_txt',
        'Field_Max_length'=>'10',
        'Field_Label_Name' => 'Invalied Edit ID',
      )
    );
    if ($edit_id_Validation['Status'] == "Error") {
      $home->main_form(array_merge(array(
        "STATUS" => "ERROR",
        "STATUS_TYPE" => "FIELD",
        "FIELD_NAME" => "otax_two_txt",
        "MESSAGE" => $edit_id_Validation['Message'],
      ), $edit_id));
      exit;
    }
    $home->main_form(array_merge(array(
      "mode" => "edit",
      "mode_name" => "Update",
      "mode_class" => "btn-warning",
      "mode_icon" => "fa fa-pencil",
      "edit_id" => $edit_id,
    ), $_POST, $_GET));
  }
  if (isset($_GET["del_id"]) && $_GET["del_id"]!='') {
    $del_id = base64_decode($_GET["del_id"]);
    $delete_id_Validation = $home->Field_Validation(
      array(
        'Field_Type' => 'number',
        'Field_Value' => $del_id,
        'Field_Name' => 'otax_two_txt',
        'Field_Max_length'=>'10',
        'Field_Label_Name' => 'Invalied Edit ID',
      )
    );
    if ($delete_id_Validation['Status'] == "Error") {
      $home->main_form(array_merge(array(
        "STATUS" => "ERROR",
        "STATUS_TYPE" => "FIELD",
        "FIELD_NAME" => "otax_two_txt",
        "MESSAGE" => $delete_id_Validation['Message'],
      ), $del_id));
      exit;
    }
    $home->main_form(array_merge(array(
      "mode" => "delete",
      "mode_name" => "Delete",
      "mode_class" => "btn-danger",
      "mode_icon" => "fa fa-trash-o",
      "del_id" => $del_id,
    ), $_POST, $_GET));
  } else {
    $home->main_form(array(
      "mode" => "save", "mode_name" => "Save", "mode_class" => "btn-success", "mode_icon" => "fa fa-floppy-o",
    ));
  }
} else {
  $cmd = base64_decode($_POST['cmd']);
}
?>