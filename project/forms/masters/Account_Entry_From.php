<?php   
require_once __DIR__ . '/../../config/config.php';


class AccountEntry extends ConfigClass
{

    public $page_token = "account_number_entry";

    function __construct()
    {       
    }

    public function main_form($data_array = array())
    {
		ob_start();
	
		// #############

        // PAGE CONTENT START

        // #############

        // PLACE YOUR CODE HERE
		if(!isset($data_array['mode_name'])){
			$data_array['mode_class']='btn-success';
			$data_array['mode_icon']='fa fa-floppy-o';
			$data_array['mode_name']='Save';
		}	
		$dcode = $this->getCurrentDistrictCode();
		$lbcode = $this->getCurrentLocalBodyCode();
        ?>

<input type="hidden" id="page_lable_id" name="page_lable_id" value="162" />
<script type='text/javascript'>
$(document).ready(function() {
    <?php if (!isset($data_array["del_id"])) { ?>
    $("#save").on('click', function() {
        var Current_Field_id = $(this).attr('id');
        $('#' + Current_Field_id).hide();
        try {

            if ($("#bank_id").val().length == '') {
                throw {
                    msg: "Select Bank",
                    foc: "#bank_id"
                }
            }
            if ($("#branch_id").val().length == '') {
                throw {
                    msg: "Select Bank Branch",
                    foc: "#branch_id"
                }
            }
            if ($("#acc_head_id").val().length == '') {
                throw {
                    msg: "Select Account Head",
                    foc: "#acc_head_id"
                }
            }
            if ($("#acc_type").val().length == '') {
                throw {
                    msg: "Select Account Type",
                    foc: "#acc_type"
                }
            }
            if ($("#fund").val().length == '') {
                throw {
                    msg: "Select Fund",
                    foc: "#fund"
                }
            }
            if ($('.ifsc_code').html().length == '') {
                throw {
                    msg: "Enter IFSC Code",
                    foc: "#ifsc_code"
                }
            }
            if ($("#acc_no").val().length == '') {
                throw {
                    msg: "Enter Account Number",
                    foc: "#acc_no"
                }
            }
            if ($("#from_date").val().length == '') {
                throw {
                    msg: "Enter From Date",
                    foc: "#from_date"
                }
            }
            if ($('input:radio[name=isactive]:checked').length == 0) {
                throw {
                    msg: "Choose Status",
                    foc: "#isactive"
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
    $(document).on('change', "#bank_id", function(e) {
        e.preventDefault();
        var bank_id = $(this).val();
        $.ajax({
            url: "Account_Entry_From.php",
            type: "post",
            data: {
                "bank_id": btoa(bank_id),
                "cmd": btoa(1)
            },
            success: function(data) {
                $('#branch_id').text(data);
            },
            dataType: 'html'
        });
    });
    $(document).on('change', "#branch_id", function(e) {
        e.preventDefault();
        var branch_id = $(this).val();
        var bank_id = $("#bank_id").val();
        $.ajax({
            url: "Account_Entry_From.php",
            type: "post",
            data: {
                "bank_id": btoa(bank_id),
                "branch_id": btoa(branch_id),
                "cmd": btoa(2)
            },
            success: function(data) {

                $('.ifsc_code').text(data);
                $('#ifsc_code').val(data);
            },
            dataType: 'html'
        });
    });
});
</script>
<div class="container mt-3">
<div class="row">
    <div class="col-md-12">
        <?php
        if (isset($data_array["STATUS"])) {
            echo $this->ShowMessage($data_array["STATUS"], $data_array["MESSAGE"]);
        }
		$lang_code_2d = $this->getCurrentUserLanguage2D();
        ?>
        <div class="card">
            <div class="card-body">
                <form method="post" autocomplete="off">
                    <input class="form-control form-control-sm" type="hidden"
                        id="<?php echo htmlentities($this->page_token); ?>"
                        name="<?php echo htmlentities($this->page_token); ?>"
                        value="<?php echo htmlentities($this->token($this->page_token)); ?>">
                    <?php

        if (isset($data_array["mode"]) && $data_array["mode"] == "edit") { 
            ?>
                    <input class="form-control form-control-sm" type="hidden" id="edit_id" name='edit_id'
                        value="<?php echo htmlentities($data_array["edit_id"]); ?>">
                    <?php
$list_com = "select b.bank_id,bankaccount_id,d.bankbranch_id, bank_name_".$lang_code_2d.", bankbranch_name as bankbranchname_en, bankbranch_name_ll as bankbranchname_ta, account_head_name_en ,  new_account_head_code as account_head, organization_name_en, fundname, ifsc_code, account_no, from_date, a.isactive ,description_en,accounthead_id,bankaccount_type,fund_id from  (SELECT bankaccount_id, bank_id, organization_id, bankbranch_id, account_no, fund_id, accounthead_id, from_date, bankaccount_type, isactive, del_flag,dcode, lbcode, is_enable,ifsc_code FROM accounts_master.t_bankaccount where bankaccount_id=:bank_id and dcode=:dcode and lbcode=:lbcode and del_flag is null)a
left join 
(select * from accounts_master.m_bank) as b on a.bank_id=b.bank_id
left join
 accounts_master.organization_lists as c on a.organization_id=c.organization_id
left join
 accounts_master.m_account_head as h on a.accounthead_id=h.account_head_id
left join 
accounts_master.m_bankbranch as d on a.bankbranch_id=d.bankbranch_id
left join 
 accounts_master.fund as e on a.fund_id=e.fundid
left join 
accounts_master.t_account_number as f on f.account_head_id=a.accounthead_id
left join 
 accounts_master.m_account_type as g on g.account_type_id=a.bankaccount_type";

		$data_array_edit = $this->prepare($list_com,array(":bank_id" => $data_array["edit_id"], ":dcode" => $dcode, ":lbcode" => $lbcode),4);
			
            $data_array = array_merge($data_array, $data_array_edit);
        } else if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            ?>
                    <input class="form-control form-control-sm" type="hidden" id="del_id" name='del_id'
                        value="<?php echo htmlentities($data_array["del_id"]); ?>">
                    <?php

     
			
			$list_com = "select b.bank_id,bankaccount_id,d.bankbranch_id, bank_name_".$lang_code_2d.", bankbranch_name as bankbranchname_en, bankbranch_name_ll as bankbranchname_ta, account_head_name_en || '-' || new_account_head_code as account_head, organization_name_en, fundname, ifsc_code, account_no, from_date, a.isactive ,description_en,accounthead_id,bankaccount_type,fund_id from  (SELECT bankaccount_id, bank_id, organization_id, bankbranch_id, account_no, fund_id, accounthead_id, from_date, bankaccount_type, isactive, del_flag,dcode, lbcode, is_enable,ifsc_code FROM accounts_master.t_bankaccount where bankaccount_id=:bank_id and dcode=:dcode and lbcode=:lbcode and del_flag is null)a
left join 
(select * from accounts_master.m_bank) as b on a.bank_id=b.bank_id
left join
 accounts_master.organization_lists as c on a.organization_id=c.organization_id
left join
 accounts_master.m_account_head as h on a.accounthead_id=h.account_head_id
left join 
accounts_master.m_bankbranch as d on a.bankbranch_id=d.bankbranch_id
left join 
 accounts_master.fund as e on a.fund_id=e.fundid
left join 
accounts_master.t_account_number as f on f.account_head_id=a.accounthead_id
left join 
 accounts_master.m_account_type as g on g.account_type_id=a.bankaccount_type";
		$set = $this->prepare($list_com,array(":bank_id" => $data_array["del_id"], ":dcode" => $dcode, ":lbcode" => $lbcode),4);
			 
            $data_array = array_merge($data_array, $set);
			
        }

        ?>
                    <table class="table table-bordered m-0 p-0 table-striped tndtp_report_table">
                        <thead>
                            <tr>
                                <td colspan="2" class="text-center"><span
                                        DisplayLabelID="939"><?php echo htmlentities('Account Entry Form'); ?></span></td>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td><span DisplayLabelID="374"><?php echo htmlentities('Bank Name'); ?></span></td>
                                <td>
                                    <?php
								if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
									if (isset($data_array)) {
										echo htmlentities($data_array['bank_name_'.$lang_code_2d]);
									}
								} else {
									?>
                                    <select name="bank_id" id="bank_id" class="form-control form-control-sm ">
                                        <option value=""> Select Bank Name</option>
                                        <?php
											$sel_bank_qry = "select bank_id, bank_name_".$lang_code_2d." from accounts_master.m_bank where isactive=:isactive and del_flag is null";
											$sel_bank_qry_res = $this->prepare($sel_bank_qry,array(":isactive"=>1),2);
											foreach($sel_bank_qry_res as $sel_bank_qry_key => $sel_bank_qry_row){
												?>
                                        <option value="<?php echo $sel_bank_qry_row['bank_id'];?>">
                                            <?php echo $sel_bank_qry_row['bank_name_'.$lang_code_2d]; ?> </option>
                                        <?php
											}
										?>
                                    </select>
                                    <script>
                                    document.getElementById("bank_id").value =
                                        '<?php echo isset($data_array['bank_id'])?$data_array['bank_id']:'';?>';
                                    </script>
                                    <?php
									}
									?>
                                </td>
                            </tr>
                            <tr>
                                <td><span DisplayLabelID="940"><?php echo htmlentities('Bank Branch Name'); ?></span></td>
                                <td>

                                    <?php
        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            if (isset($data_array)) {
                echo htmlentities($data_array['bankbranchname_'.$lang_code_2d]);
            }
        } else {
            ?>
                                    <select name="branch_id" id="branch_id" class="form-control form-control-sm ">
                                        <option value=""> Select Bank Branch Name</option>
                                        <?php
											$sel_branch_qry = "select bank_id, bankbranch_id, organization_id, bankbranch_code,bankbranch_name as bankbranch_name_".$lang_code_2d.", bankbranch_name_ll as bankbranch_name_".$lang_code_2d.", ifsccode from accounts_master.m_bankbranch where isactive=:isactive and  del_flag is null";
											$sel_branch_qry_res = $this->prepare($sel_branch_qry,array(":isactive"=>1),2);
											foreach($sel_branch_qry_res as $sel_branch_qry_key => $sel_branch_qry_row){
												?>
                                        <option value="<?php echo $sel_branch_qry_row['bankbranch_id'];?>">
                                            <?php echo $sel_branch_qry_row['bankbranch_name_'.$lang_code_2d]; ?>
                                        </option>
                                        <?php
											}
										?>
                                    </select>
                                    <script>
                                    document.getElementById("branch_id").value =
                                        '<?php echo isset($data_array['bankbranch_id'])?$data_array['bankbranch_id']:'';?>';
                                    </script>
                                    <?php
        }
        ?>

                                </td>
                            </tr>

                            <tr>
                                <td><span DisplayLabelID="941"><?php echo htmlentities('Account Head'); ?></span></td>
                                <td>

                                    <?php
        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            if (isset($data_array)) {
                echo htmlentities($data_array['account_head']);
            }
        } else {
            ?>
                                    <select name="acc_head_id" id="acc_head_id" class="form-control form-control-sm ">
                                        <option value=""> Select Account Head</option>
                                        <?php
											$sel_acc_head_qry = "select account_head_id, account_head_name_".$lang_code_2d." from accounts_master.m_account_head where isactive=:isactive and del_flag is null";
											$sel_acc_head_qry_res = $this->prepare($sel_acc_head_qry,array(":isactive"=>1),2);
											foreach($sel_acc_head_qry_res as $sel_acc_head_qry_key => $sel_acc_head_qry_row){
												?>
                                        <option value="<?php echo $sel_acc_head_qry_row['account_head_id'];?>">
                                            <?php echo $sel_acc_head_qry_row['account_head_name_'.$lang_code_2d].(-$sel_acc_head_qry_row['account_head_id']); ?>
                                        </option>
                                        <?php
											}
										?>
                                    </select>
                                    <script>
                                    document.getElementById("acc_head_id").value =
                                        '<?php echo isset($data_array['accounthead_id'])?$data_array['accounthead_id']:'';?>';
                                    </script>
                                    <?php
        }
        ?>

                                </td>
                            </tr>
                            <tr>
                                <td><span DisplayLabelID="942"><?php echo htmlentities('Description'); ?></span></td>
                                <td>

                                    <?php
        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            if (isset($data_array)) {
                echo htmlentities($data_array['description_'.$lang_code_2d]);
            }
        } else {
            ?>
                                    <select name="acc_type" id="acc_type" class="form-control form-control-sm ">
                                        <option value=""> Select Account Type</option>
                                        <?php
											$sel_acc_type_qry = "select account_type_id, description_".$lang_code_2d." from accounts_master.m_account_type";
											$sel_acc_type_qry_res = $this->prepare($sel_acc_type_qry,array(),2);
											foreach($sel_acc_type_qry_res as $sel_acc_type_qry_key => $sel_acc_type_qry_row){
												?>
                                        <option value="<?php echo $sel_acc_type_qry_row['account_type_id'];?>">
                                            <?php echo $sel_acc_type_qry_row['description_'.$lang_code_2d]; ?> </option>
                                        <?php
											}
										?>
                                    </select>
                                    <script>
                                    document.getElementById("acc_type").value =
                                        '<?php echo isset($data_array['bankaccount_type'])?$data_array['bankaccount_type']:'';?>';
                                    </script>
                                    <?php
        }
        ?>

                                </td>
                            </tr>
                            <tr>
                                <td><span DisplayLabelID="944"><?php echo htmlentities('Fund Name'); ?></span></td>
                                <td>

                                    <?php
        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            if (isset($data_array)) {
                echo htmlentities($data_array['fundname']);
            }
        } else {
            ?>
                                    <select name="fund" id="fund" class="form-control form-control-sm ">
                                        <option value=""> Select Fund</option>
                                        <?php
											$sel_fund_qry = "select fundid, fundname from accounts_master.m_fund where del_flag is null and isactive=:isactive";
											$sel_fund_qry_res = $this->prepare($sel_fund_qry,array(":isactive"=>1),2);
											foreach($sel_fund_qry_res as $sel_fund_qry_key => $sel_fund_qry_row){
												?>
                                        <option value="<?php echo $sel_fund_qry_row['fundid'];?>">
                                            <?php echo $sel_fund_qry_row['fundname']; ?> </option>
                                        <?php
											}
										?>
                                    </select>
                                    <script>
                                    document.getElementById("fund").value =
                                        '<?php echo isset($data_array['fund_id'])?$data_array['fund_id']:'';?>';
                                    </script>
                                    <?php
        }
        ?>

                                </td>
                            </tr>
                            <tr>
                                <td><span DisplayLabelID="943"><?php echo htmlentities('IFSC Code'); ?></span></td>
                                <td>

                                    <?php
        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            if (isset($data_array)) {
                echo htmlentities($data_array['ifsc_code']);
            }
        } else {
            ?> <span class="ifsc_code"><?php echo isset($data_array['ifsc_code'])?$data_array['ifsc_code']:'';?>
                                    </span>
                                    <input type="hidden" id="ifsc_code" name="ifsc_code" value="<?php echo htmlentities(isset($data_array['ifsc_code'])?$data_array['ifsc_code']:'', ENT_QUOTES, 'UTF-8'); ?>" class="form-control form-control-sm alpha_numeric_without_space upper_case" />
                                    <?php
        }
        ?>

                                </td>
                     </tr>
                            <tr>
                                <td><span DisplayLabelID="553"><?php echo htmlentities('Account Number'); ?></span></td>
                                <td>

                                    <?php
        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            if (isset($data_array)) {
                echo htmlentities($data_array['account_no']);
            }
        } else {
            ?>
                                    <input type="text" id="acc_no" name="acc_no"
                                        class="form-control form-control-sm number_field"
                                        value="<?php echo htmlentities(isset($data_array['account_no'])?$data_array['account_no']:'');?>" />
                                    <?php
        }
        ?>

                                </td>
                            </tr>
                            <tr>
                                <td><span DisplayLabelID="415"><?php echo htmlentities('From Date'); ?></span></td>
                                <td>

                                    <?php
        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            if (isset($data_array)) {
                echo htmlentities($data_array['from_date']);
            }
        } else {
            ?>
                                    <input type="text" id="from_date" name="from_date"
                                        class="form-control form-control-sm field_datepicker user_enter_date"
                                        value="<?php echo htmlentities(isset($data_array['from_date'])?$data_array['from_date']:''); ?>" />
                                    <?php
        }
        ?>

                                </td>
                            </tr>
                            <tr>
                                <td class="text-left font-weight-bold"><span
                                        DisplayLabelID="345"><?php echo htmlentities('Status'); ?></span></td>
                                <td>
                                    <?php
        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            if (isset($data_array) && $data_array['isactive']==1) {
                echo 'Active';
            } else if(isset($data_array) && $data_array['isactive']==0){
				echo 'Deactive';
			}
        } else {
            ?>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadio4" name="isactive" value="1" class="custom-control-input"
                                            <?php if(isset($data_array['isactive']) && $data_array['isactive']==1){ ?>checked<?php } ?>>
                                        <label class="custom-control-label" for="customRadio4"><span
                                                DisplayLabelID="371"><?php echo htmlentities('Active'); ?></span></label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadio5" name="isactive" value="0"
                                            class="custom-control-input"
                                            <?php if(isset($data_array['isactive']) && $data_array['isactive']==0){ ?>checked<?php } ?>>
                                        <label class="custom-control-label" for="customRadio5"><span
                                                DisplayLabelID="372"><?php echo htmlentities('In Active'); ?></span></label>
                                    </div>
                                    <?php
        }
        ?>

                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <?php

        if (! isset($data_array["mode_name"])) {
            $data_array["mode_name"] = "Save";
        }

        ?>

                                    <button type="submit"
                                        class="btn <?php echo htmlentities($data_array["mode_class"]);?> btn-sm text-white"
                                        name="submit" id="save"><i
                                            class="<?php echo htmlentities($data_array["mode_icon"]);?> pr-1"
                                            aria-hidden="true"></i> <?php echo htmlentities($data_array["mode_name"]);?></button>
                                    &nbsp;
                                    <a class="btn btn-secondary btn-sm" href="Account_Entry_From.php"><i class="fa fa-eraser pr-1"></i>Clear</a>
                                </td>
                            </tr>


                    </table>
                </form>

            </div>
        </div>
    </div>
    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">
                     <span DisplayLabelID="939"><?php echo htmlentities('Account Entry'); ?></span> <!--<a href="Account_Entry_Form.php" class="pull-right btn btn-sm btn-purple"><i
                            class="fa fa-plus-square p-1" aria-hidden="true"></i><span
                            DisplayLabelID="808"><?php //echo htmlentities('Back'); ?></span></a> -->
                </h4>
                <div class="single-table">


                    <table class="table table-bordered m-0 p-0 table-striped tndtp_report_table" id="dataTable2"
                        style="width:auto!important;">
                        <thead>
                            <tr>
                                <td><span DisplayLabelID="174"><?php echo htmlentities('Sl. No'); ?></span></td>
                                <td><span DisplayLabelID="374"><?php echo htmlentities('Bank Name'); ?></span></td>
                                <td><span DisplayLabelID="940"><?php echo htmlentities('Branch Name'); ?></span></td>
                                <td><span DisplayLabelID="941"><?php echo htmlentities('Account Head'); ?></span></td>
                                <td><span DisplayLabelID="942"><?php echo htmlentities('Account Type'); ?></span></td>
                                <td><span DisplayLabelID="944"><?php echo htmlentities('Fund'); ?></span></td>
                                <td><span DisplayLabelID="943"><?php echo htmlentities('IFSC Code'); ?></span></td>
                                <td><span DisplayLabelID="553"><?php echo htmlentities('Account Number'); ?></span></td>
                                <td><span DisplayLabelID="415"><?php echo htmlentities('From Date'); ?></span></td>
                                <td><span DisplayLabelID="345"><?php echo htmlentities('Status'); ?></span></td>
                                <td><span DisplayLabelID="346"><?php echo htmlentities('Actions'); ?></span></td>
                            </tr>
                        </thead>
                        <?php
$list_com = "select bankaccount_id, bank_name_".$lang_code_2d.", bankbranch_name as bankbranchname_en, bankbranch_name_ll as bankbranchname_ta, account_desc_".$lang_code_2d.", organization_name_".$lang_code_2d.", fundname, ifsc_code, account_no, from_date, a.isactive ,description_en,account_head_name_".$lang_code_2d." || '-' || new_account_head_code as account_head_name from  (SELECT bankaccount_id, bank_id, organization_id, bankbranch_id, account_no, fund_id, accounthead_id, from_date, bankaccount_type, isactive, del_flag,dcode, lbcode, is_enable,ifsc_code FROM accounts_master.t_bankaccount where dcode=:dcode and lbcode=:lbcode and del_flag is null)a
left join 
(select * from accounts_master.m_bank) as b on a.bank_id=b.bank_id
left join
 accounts_master.organization_lists as c on a.organization_id=c.organization_id
 left join
 accounts_master.m_account_head as h on a.accounthead_id=h.account_head_id
left join 
accounts_master.m_bankbranch as d on a.bankbranch_id=d.bankbranch_id
left join 
 accounts_master.m_fund as e on a.fund_id=e.fundid
left join 
accounts_master.t_account_number as f on f.account_head_id=a.accounthead_id
left join 
accounts_master.m_account_type as g on g.account_type_id=a.bankaccount_type";
		$set = $this->prepare($list_com,array(":dcode" => $dcode, ":lbcode" => $lbcode),2);
        $slno = 1;
        if(count($set)>0){
        foreach ($set as $key => $row) {
            ?>
                        <tbody>
                            <tr>
                                <td><?php echo htmlentities($slno++); ?></td>
                                <td align="left"><?php echo htmlentities($row['bank_name_'.$lang_code_2d]); ?></td>
                                <td align="left"><?php echo htmlentities($row['bankbranchname_'.$lang_code_2d]); ?></td>
                                <td align="left"><?php echo htmlentities($row['account_head_name']); ?></td>
                                <td align="left"><?php echo htmlentities($row['description_en']); ?></td>
                                <td align="left"><?php echo htmlentities($row['fundname']); ?></td>
                                <td align="left"><?php echo htmlentities($row['ifsc_code']); ?></td>
                                <td align="left"><?php echo htmlentities($row['account_no']); ?></td>
                                <td align="left"><?php echo htmlentities($row['from_date']); ?></td>
                                <td align="center">
                                    <?php if($row['isactive'] == 1){ echo htmlentities('Active'); } else { echo htmlentities('Inactive'); } ?>
                                </td>
                                <td align="left">
                                    <a href="?edit_id=<?php echo base64_encode($row['bankaccount_id']); ?>"
                                        class="btn btn-warning btn-sm"><i class="fa fa-pencil pr-1"
                                            aria-hidden="true"></i>Edit</a>
                                    <a href="?del_id=<?php echo base64_encode($row['bankaccount_id']); ?>"
                                        class="btn btn-danger btn-sm"><i class="fa fa-trash-o p-1"
                                            aria-hidden="true"></i>Delete</a>
                                </td>
                            </tr>

                        </tbody>
                        <?php
        } 
    }else{
        ?>
        <tr>
                                <td align="center" colspan="11"><?php echo htmlentities('No Records Found'); ?></td>
        </tr>
        <?php
    }
        ?>

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

        $this->Template('Template1', "Bank Account Entry Form", $ob_output_main_forms, array(
            array(
                "name" => "User Role"
            )
        ));
        exit();
    }

    public function data_save($save_data)
    {
		$dcode = $this->getCurrentDistrictCode();
		$lbcode = $this->getCurrentLocalBodyCode();
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
			if(isset($save_data['bank_id']) && $save_data['bank_id'] != '')
			{
				$bank_id = $save_data['bank_id'];
				$bank_id_Validation = $this->Field_Validation(
					array
					(
					'Field_Type'=>'number',
					'Field_Value'=>$bank_id,
					'Field_Name'=>'bank_id',
					'Field_Max_Length'=>5,
					'Field_Label_Name'=>'Bank',
					)
					);
					
					if ($bank_id_Validation['Status'] == "Error") {
						$this->main_form(array_merge(array(
							"STATUS" => "ERROR", 
							"STATUS_TYPE" => "FIELD",
							"FIELD_NAME" => "bank_id",
							"MESSAGE" => $bank_id_Validation['Message']
						), $save_data));
						exit;			
					}
				}  
			else
			{
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "bank_id",
					"MESSAGE" => "Select Bank"
				), $save_data));
				exit;
			}
			if(isset($save_data['branch_id']) && $save_data['branch_id'] != '')
			{
				$branch_id = $save_data['branch_id'];
				$branch_id_Validation = $this->Field_Validation(
				array
				(
					'Field_Type'=>'number',
					'Field_Value'=>$branch_id,
					'Field_Name'=>'branch_id',
					'Field_Max_Length'=>5,
					'Field_Label_Name'=>'Branch',
					)
				);			
				if ($branch_id_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR", 
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "branch_id",
						"MESSAGE" => $branch_id_Validation['Message']
					), $save_data));
					exit;			
				} 
			}
			else
			{
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "branch_id",
					"MESSAGE" => "Select Branch"
				), $save_data));
				exit;
			}
			if(isset($save_data['acc_head_id']) && $save_data['acc_head_id'] != '')
			{
				$acc_head_id = $save_data['acc_head_id'];
				$acc_head_id_Validation = $this->Field_Validation(
				array
				(
					'Field_Type'=>'number',
					'Field_Value'=>$acc_head_id,
					'Field_Name'=>'acc_head_id',
					'Field_Max_Length'=>5,
					'Field_Label_Name'=>'Account Head',
					)
				);			
				if ($acc_head_id_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR", 
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "acc_head_id",
						"MESSAGE" => $acc_head_id_Validation['Message']
					), $save_data));
					exit;			
				} 
			}
			else
			{
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "acc_head_id",
					"MESSAGE" => 'Select Account Head'
				), $save_data));
				exit;
			}
			if(isset($save_data['acc_type']) && $save_data['acc_type'] != '')
			{
				$acc_type = $save_data['acc_type'];
				$acc_type_Validation = $this->Field_Validation(
				array
				(
					'Field_Type'=>'number',
					'Field_Value'=>$acc_type,
					'Field_Name'=>'acc_type',
					'Field_Max_Length'=>2,
					'Field_Label_Name'=>'Account Type',
					)
				);			
				if ($acc_type_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR", 
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "acc_type",
						"MESSAGE" => $acc_type_Validation['Message']
					), $save_data));
					exit;			
				} 
			}
			else
			{
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "acc_type",
					"MESSAGE" => 'Select Account Type'
				), $save_data));
				exit;
			}
			if(isset($save_data['fund']) && $save_data['fund'] != '')
			{
				$fund = $save_data['fund'];
				$fund_Validation = $this->Field_Validation(
				array
				(
					'Field_Type'=>'number',
					'Field_Value'=>$fund,
					'Field_Name'=>'fund',
					'Field_Max_Length'=>5,
					'Field_Label_Name'=>'fund',
					)
				);			
				if ($fund_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR", 
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "fund",
						"MESSAGE" => $fund_Validation['Message']
					), $save_data));
					exit;			
				} 
			}
			else
			{
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "fund",
					"MESSAGE" => 'Select Fund'
				), $save_data));
				exit;
			}
			if(isset($save_data['ifsc_code']) && $save_data['ifsc_code'] != '')
			{
				$ifsc_code = $save_data['ifsc_code'];
				$ifsc_code_Validation = $this->Field_Validation(
				array
				(
					'Field_Type'=>'text_number',
					'Field_Value'=>$ifsc_code,
					'Field_Name'=>'ifsc_code',
					'Field_Max_Length'=>10,
					'Field_Label_Name'=>'IFSC Code',
					)
				);			
				if ($ifsc_code_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR", 
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "ifsc_code",
						"MESSAGE" => $ifsc_code_Validation['Message']
					), $save_data));
					exit;			
				} 
			}
			else
			{
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "ifsc_code",
					"MESSAGE" => 'Enter IFSC Code'
				), $save_data));
				exit;
			}		   
			if(isset($save_data['acc_no']) && $save_data['acc_no'] != '')
			{
				$acc_no = $save_data['acc_no'];
				$acc_no_Validation = $this->Field_Validation(
				array
				(
					'Field_Type'=>'number',
					'Field_Value'=>$acc_no,
					'Field_Name'=>'acc_no',
					'Field_Max_Length'=>16,
					'Field_Label_Name'=>'Account Number',
					)
				);			
				if ($acc_no_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR", 
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "acc_no",
						"MESSAGE" => $acc_no_Validation['Message']
					), $save_data));
					exit;			
				} 
			}
			else
			{
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "acc_no",
					"MESSAGE" => 'Enter Account Number'
				), $save_data));
				exit;
			}
			if(isset($save_data['from_date']) && $save_data['from_date'] != '')
			{
				$from_date = date("Y-m-d", strtotime($save_data['from_date']));			
				$from_date_Validation = $this->Field_Validation(
				array
				(
					'Field_Type'=>'date',
					'Field_Value'=>$from_date,
					'Field_Name'=>'from_date',
					'Field_Label_Name'=>'From Date',
					'Field_Format' => 'yyyy-mm-dd',
					)
				);			
				if ($from_date_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR", 
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "from_date",
						"MESSAGE" => $from_date_Validation['Message']
					), $save_data));
					exit;			
				} 
			}
			else
			{
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "from_date",
					"MESSAGE" => 'Select From Date'
				), $save_data));
				exit;
			}
			if(isset($save_data['isactive']) && $save_data['isactive'] != '')
			{
				$isactive = $save_data['isactive'];
				$isactive_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'number',
				'Field_Value'=>$isactive,
				'Field_Name'=>'isactive',
				'Field_Label_Name'=>'Select Status',
				)
				);			
				if ($isactive_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR", 
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "isactive",
						"MESSAGE" => $isactive_Validation['Message']
					), $save_data));
				exit;			
				}			
			}
			else
			{
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "isactive",
					"MESSAGE" => "Select Status"
				), $save_data));
				exit;
			}
        }
		$nameentry = "accounts_master.sp_bankaccount_entry";
        $getCurrentUser = $this->getCurrentUser();
        $getIpAddress = $this->getIpAddress();
		$edit_id = isset($save_data["edit_id"])?$save_data["edit_id"]:0;
		$del_id = isset($save_data["del_id"])?$save_data["del_id"]:0;
        if (isset($save_data["edit_id"])) {
            $save_query = "select " . $nameentry . "(:bank_id,:organizationid,:branch_id,:acc_no,:fund, :acc_head_id, :acc_type, :from_date, :ifsc_code, :dcode, :lbcode, :isactive, :getCurrentUser,:getIpAddress,:edit_id,:del_id)"; 
			$res = $this->prepare($save_query,array(":bank_id"=>$bank_id,":organizationid"=>1,":branch_id"=>$branch_id,":acc_no"=>$acc_no,":fund"=>$fund,":acc_head_id"=>$acc_head_id,":acc_type"=>$acc_type,":from_date"=>$from_date,":ifsc_code"=>$ifsc_code,":dcode"=>$dcode,":lbcode"=>$lbcode,":isactive"=>$isactive,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>$save_data["edit_id"],":del_id"=>0),4);
			$message='Data Updated SccessFully';
        } else if (isset($save_data["del_id"])) {
            $save_query = "select " . $nameentry . "(:bank_id,:organizationid,:branch_id,:acc_no,:fund, :acc_head_id, :acc_type, :from_date, :ifsc_code, :dcode, :lbcode, :isactive, :getCurrentUser,:getIpAddress,:edit_id,:del_id)"; 
			$res = $this->prepare($save_query,array(":bank_id"=>Null,":organizationid"=>Null,":branch_id"=>Null,":acc_no"=>Null,":fund"=>Null,":acc_head_id"=>Null,":acc_type"=>Null,":from_date"=>Null,":ifsc_code"=>Null,":dcode"=>$dcode,":lbcode"=>$lbcode,":isactive"=>Null,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>0,":del_id"=>$save_data["del_id"]),4);
		   $message='Data Deleted SccessFully';
        } else {
               $save_query = "select " . $nameentry . "(:bank_id,:organizationid,:branch_id,:acc_no,:fund, :acc_head_id, :acc_type, :from_date, :ifsc_code, :dcode, :lbcode, :isactive, :getCurrentUser,:getIpAddress,:edit_id,:del_id)"; 
			$res = $this->prepare($save_query,array(":bank_id"=>$bank_id,":organizationid"=>1,":branch_id"=>$branch_id,":acc_no"=>$acc_no,":fund"=>$fund,":acc_head_id"=>$acc_head_id,":acc_type"=>$acc_type,":from_date"=>$from_date,":ifsc_code"=>$ifsc_code,":dcode"=>$dcode,":lbcode"=>$lbcode,":isactive"=>$isactive,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>0,":del_id"=>0),4);
			 $message='Data Saved SccessFully';
        }
        if (!isset($res->errorInfo)) {
            /*$this->main_form(array(
                "STATUS" => "SUCCESS",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => $message
            ));
			exit;*/
			?>
            <script>
				alert('<?php echo $message; ?>');
				window.location.href = "<?php $site_data->website_url;?>/project/forms/Accounts/Account_Entry_Form.php";
            </script>
            <?php 
        } else {
            $this->main_form(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Data Save Failed Due To Duplicate Entry"
            ));
			exit;
        }
    }
}
$AccountEntry = new AccountEntry();
$lang_code_2d = $AccountEntry->getCurrentUserLanguage2D();
if(isset($_POST['cmd']) && $_POST['cmd'] !=''){
	$cmd=base64_decode($_POST['cmd']);
	if($cmd == 1){
		$result=array();
		$bank_id = base64_decode($_POST['bank_id']);
		?>
        <option value=""> Select Bank Branch Name</option>
        <?php
        $sel_branch_qry = "select bankbranch_id,bankbranch_name as bankbranch_name_en, bankbranch_name_ll as bankbranch_name_ta from accounts_master.m_bankbranch where bank_id=:bankid and isactive=:isactive and  del_flag is null";
        $sel_branch_qry_res = $AccountEntry->prepare($sel_branch_qry,array(":bankid"=>$bank_id, ":isactive"=>1),2);
        foreach($sel_branch_qry_res as $sel_branch_qry_key => $sel_branch_qry_row){
            ?>
            <option value="<?php echo $sel_branch_qry_row['bankbranch_id'];?>"><?php echo $sel_branch_qry_row['bankbranch_name_'.$lang_code_2d]; ?> </option>
            <?php
        }
        exit;
	}
	if($cmd == 2){
		$result=array();
		$bank_id = base64_decode($_POST['bank_id']);
		$branch_id = base64_decode($_POST['branch_id']);
		$sel_branch_qry = "select ifsccode from accounts_master.m_bankbranch where bank_id=:bank_id and bankbranch_id=:branch_id and isactive=:isactive and  del_flag is null";
		$sel_branch_qry_res = $AccountEntry->prepare($sel_branch_qry,array(":bank_id"=>$bank_id, ":branch_id"=>$branch_id, ":isactive"=>1),4);
		echo $sel_branch_qry_res['ifsccode'];
		exit;
	}
}else{
	if (isset($_POST["submit"])) {
		$AccountEntry->data_save($_POST);
	}
	if (isset($_GET["edit_id"])) {
		$edit_id = base64_decode($_GET["edit_id"]);
		$AccountEntry->main_form(array(
			"mode" => "edit",
			"mode_name" => "Update",
			"mode_class" => "btn-warning",
			"mode_icon" => "fa fa-pencil",
			"edit_id" => $edit_id
		));
	}
	if (isset($_GET["del_id"])) {
		$del_id = base64_decode($_GET["del_id"]);
		$AccountEntry->main_form(array(
			"mode" => "delete",
			"mode_name" => "Delete",
			"mode_class" => "btn-danger",
			"mode_icon" => "fa fa-trash-o",
			"del_id" => $del_id
		));
	} else {
		$AccountEntry->main_form(array(
			"mode" => "save","mode_name" => "Save","mode_class" => "btn-success","mode_icon" => "fa fa-floppy-o"
		));
	}
}
?>