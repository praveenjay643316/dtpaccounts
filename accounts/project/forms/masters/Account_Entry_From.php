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
	
		// ############

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
        if (isset($data_array["mode"]) && $data_array["mode"] == "edit"){?>
            <input class="form-control form-control-sm" type="hidden" id="edit_id" name='edit_id' value="<?php echo htmlentities($data_array["edit_id"]); ?>">
            <?php
            $list_com = "select a.acc_head_code,a.bank_code, b.bank_id,bankaccount_id,d.bankbranch_id as branch_id, bank_name_en as bank_name_text, bankbranch_name as bankbranchname_en, bankbranch_name_ll as bankbranchname_ta, ifsccode as ifsc_code, account_no as acc_no, TO_CHAR(from_date, 'DD-MM-YYYY') AS  from_date, a.isactive, account_head_name_en, old_account_head_code from  (SELECT bank_code,accounthead_id as acc_head_code,bankaccount_id, bank_id, bankbranch_id, account_no,  from_date, isactive, del_flag, dcode, lbcode, accounthead_id FROM accounts_master.t_bank_account where bankaccount_id=:bank_id and dcode=:dcode and lbcode=:lbcode and del_flag is null)a
            left join 
            (select * from accounts_master.m_bank) as b on a.bank_id=b.bank_id
            left join 
            accounts_master.m_bankbranch as d on a.bankbranch_id=d.bankbranch_id
             left join
			accounts_master.m_account_head as c on a.accounthead_id=c.account_head_id";
            $data_array_edit = $this->prepare($list_com,array(":bank_id" => $data_array["edit_id"], ":dcode" => $dcode, ":lbcode" => $lbcode),4);
            $data_array = array_merge($data_array, $data_array_edit);
        } else if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {?>
            <input class="form-control form-control-sm" type="hidden" id="del_id" name='del_id' value="<?php echo htmlentities($data_array["del_id"]); ?>">
            <?php
            $list_com = "select a.bank_code, b.bank_id,bankaccount_id,d.bankbranch_id as branch_id, bank_name_en as bank_name_text, bankbranch_name as bankbranchname_en, bankbranch_name_ll as bankbranchname_ta, ifsccode as ifsc_code, account_no as acc_no, TO_CHAR(from_date, 'DD-MM-YYYY') AS  from_date, a.isactive, account_head_name_en, old_account_head_code from  (SELECT bank_code, bankaccount_id, bank_id, bankbranch_id, account_no, from_date, isactive, del_flag,dcode, lbcode, accounthead_id FROM accounts_master.t_bank_account where bankaccount_id=:bank_id and dcode=:dcode and lbcode=:lbcode and del_flag is null)a
            left join 
            (select * from accounts_master.m_bank) as b on a.bank_id=b.bank_id
            left join 
            accounts_master.m_bankbranch as d on a.bankbranch_id=d.bankbranch_id
            left join
			accounts_master.m_account_head as c on a.accounthead_id=c.account_head_id";
            $set = $this->prepare($list_com,array(":bank_id" => $data_array["del_id"], ":dcode" => $dcode, ":lbcode" => $lbcode),4);
            $data_array = array_merge($data_array, $set);
        }
        
        ?>
        <input type="hidden" id="page_lable_id" name="page_lable_id" value="162" />
        <script type='text/javascript'>
            $(document).ready(function() {
          
                $('#dataTable').DataTable(); // Initialize the DataTable

                $('#from_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'yyyy-mm-dd',
                   // minDate: new Date('<?php echo isset($data_array['from_date']) && $data_array['from_date']!=''?$data_array['from_date']:'01-01-2015'; ?>'),
                    maxDate: new Date()
                });
                $('#bank_code').on('change',function(){
                    let bank_code=$('#bank_code').val();
                    $.ajax({
                        url: "Account_Entry_From.php",
                        type: "post",
                        data: {
                            "bank_code": btoa(bank_code),
                            "cmd": btoa(3)
                        },
                        success: function(data) {
                            $('#bank_id').val(data.bank_id);
                            $('#bank_id').trigger('change');
                        },
                        dataType: 'json'
                    })
                });
                <?php if (!isset($data_array["del_id"])) { ?>
                    $("#save").on('click', function() {
                        var Current_Field_id = $(this).attr('id');
                        $('#' + Current_Field_id).hide();
                        try {
                            <?php if (!isset($data_array["edit_id"])) { ?>
                            if ($("#bank_id").val().length == 0) {
                                throw {
                                    msg: "Select Bank",
                                    foc: "#bank_id"
                                }
                            }
                            if ($("#branch_id").val().length == 0) {
                                throw {
                                    msg: "Select Bank Branch",
                                    foc: "#branch_id"
                                }
                            }
                            if ($("#bank_code").val().length == 0) {
                                throw {
                                    msg: "Enter Bank Code",
                                    foc: "#bank_code"
                                }
                            }
                            if ($('.ifsc_code').html().length == 0) {
                                throw {
                                    msg: "Enter IFSC Code",
                                    foc: "#ifsc_code"
                                }
                            }
                            
                            if ($("#from_date").val().length == 0) {
                                throw {
                                    msg: "Enter Account Opening Date",
                                    foc: "#from_date"
                                }
                            }
                            <?php } ?>
                            if($('#acc_head_code').val().length==0)
                            {
                            throw {
                                    msg: "Select Account Code",
                                    foc: "#acc_head_code"
                                }
                            }                            
                            if ($("#acc_no").val().length == 0) {
                                throw {
                                    msg: "Enter Account Number",
                                    foc: "#acc_no"
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
                    if($(this).val() !=''){
                        var bank_id = $(this).val();
                    }else{
                        alert("Select Bank");
                    }       
                    $.ajax({
                        url: "Account_Entry_From.php",
                        type: "post",
                        data: {
                            "bank_id": btoa(bank_id),
                            "cmd": btoa(1)
                        },
                        success: function(data) {
                            $('#branch_id').html(data);
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
        <div class="container my-3">
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
                            <form action="Account_Entry_From.php" method="post" autocomplete="off">
                                <input class="form-control w-75  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>" name="<?php echo htmlentities($this->page_token); ?>" value="<?php echo htmlentities($this->token($this->page_token)); ?>">
                                
                                <input class="form-control form-control-sm" type="hidden" id="edit_id" name="edit_id" value="<?php echo htmlentities(isset($data_array["edit_id"])?$data_array["edit_id"]:''); ?>">
                                <input class="form-control form-control-sm" type="hidden" id="del_id" name="del_id" value="<?php echo htmlentities(isset($data_array["del_id"])?$data_array["del_id"]:''); ?>">
                                    <table class="table table-bordered m-0 p-0 tndtp_form_table">
                                        <thead class="bg-th-form-dsg">
                                            <tr>
                                                <th colspan="2"><span DisplayLabelID="939"><?php echo htmlentities('Bank Account Entry'); ?></span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><span DisplayLabelID="374"><?php echo htmlentities('Bank');?></span></td>
                                                <td><?php
                                                if ((isset($data_array["mode"]) && $data_array["mode"] == "delete") || (isset($data_array["mode"]) && $data_array["mode"] == "edit")) {
                                                    if (isset($data_array['bank_code'])) {
                                                        echo htmlentities($data_array['bank_code'] . ' - '. $data_array['bank_name_text']);
                                                    }
                                                } else { 
                                                    ?>
                                                    <select class="form-control form-control-sm w-50" name="bank_code" id="bank_code" >
                                                        <option value=""> Select Bank</option>
                                                        <?php
                                                        $query="select bank_name_en,bank_code from accounts_master.m_bank where del_flag is null and isactive=1 AND bank_id in (select bank_id from accounts_master.m_bankbranch where lbcode=:lbcode and dcode=:dcode) ORDER BY bank_code ASC";
                                                        $res=$this->prepare($query,[":lbcode"=>$lbcode,":dcode"=>$dcode],2);
                                                        foreach($res as $row)
                                                        {
                                                            $selected = (isset($data_array['bank_code']) && $data_array['bank_code'] == $row['bank_code']) ? "selected" : "";
                                                            ?>
                                                            <option value=<?=$row['bank_code']?> <?=$selected?>><?=$row['bank_name_en']?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <input id="bank_id" type="hidden" name="bank_id" value="" />
                                                    <?php
                                                    }
                                                    ?>
                                                    <script>
                                                    document.getElementById("bank_id").value =
                                                        '<?php echo isset($data_array['bank_id'])?$data_array['bank_id']:'';?>';
                                                    </script>
                                                </td>
                                                </tr>                                            
                                                <tr>
                                                    <td><span DisplayLabelID="940"><?php echo htmlentities('Bank Branch Name'); ?></span></td>
                                                    <td> <?php  if ((isset($data_array["mode"]) && $data_array["mode"] == "delete") || (isset($data_array["mode"]) && $data_array["mode"] == "edit")) {
                                                            if (isset($data_array['bankbranchname_'.$lang_code_2d]) && $data_array['bankbranchname_'.$lang_code_2d] !='') {
                                                                echo htmlentities($data_array['bankbranchname_'.$lang_code_2d]);
                                                            }
                                                        } else { ?>
                                                        <select name="branch_id" id="branch_id" class="form-control form-control-sm w-50">
                                                            <option value="">Select Bank Branch Name</option>
                                                                <?php
                                                                    if(isset($data_array['bank_id']) && $data_array['bank_id']!=''){   
                                                                        $sel_branch_qry = "select bankbranch_id as branch_id,bankbranch_name as bankbranch_name_en, bankbranch_name_ll as bankbranch_name_ta from accounts_master.m_bankbranch where bank_id=:bank_id and lbcode=:lbcode and dcode=:dcode and del_flag is null and isactive=1;";
                                                                        $sel_branch_qry_res = $this->prepare($sel_branch_qry,array(":bank_id"=>$data_array['bank_id'] ,":lbcode"=>$lbcode,":dcode"=>$dcode),2);

                                                                        foreach ($sel_branch_qry_res as $sel_branch_qry_row) { 
                                                                            $branch_name = $sel_branch_qry_row["bankbranch_name_{$lang_code_2d}"];
                                                                            ?>
                                                                            <option value="<?php echo $sel_branch_qry_row['branch_id']; ?>">
                                                                                <?php echo $branch_name; ?>
                                                                            </option>
                                                                        <?php 
                                                                        } 
                                                                    }
                                                                ?>
                                                            </select>
                                                        <script>
                                                        document.getElementById("branch_id").value =
                                                            '<?php echo isset($data_array['branch_id'])?$data_array['branch_id']:'';?>';
                                                        </script>
                                                    <?php } ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span DisplayLabelID="943"><?php echo htmlentities('IFSC Code'); ?></span></td>
                                                    <td> <?php
                                                    if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                                                        if (isset($data_array['ifsc_code']) && $data_array['ifsc_code']!='') {
                                                            echo htmlentities($data_array['ifsc_code']);
                                                        }
                                                    } else {
                                                        ?> <span class="ifsc_code"><?php echo isset($data_array['ifsc_code'])?$data_array['ifsc_code']:'';?>
                                                        </span>
                                                        <input type="hidden" id="ifsc_code" name="ifsc_code" value="<?php echo htmlentities(isset($data_array['ifsc_code'])?$data_array['ifsc_code']:'', ENT_QUOTES, 'UTF-8'); ?>" class="form-control form-control-sm alpha_numeric_without_space upper_case" />
                                                        <?php  } ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span DisplayLabelID="553"><?php echo htmlentities('Bank Account Number'); ?></span></td>
                                                    <td> <?php
                                                    if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                                                        if (isset($data_array)) {
                                                            echo htmlentities($data_array['acc_no']);
                                                        }
                                                    } else {
                                                    ?>
                                                    <input type="text" id="acc_no" name="acc_no" class="form-control form-control-sm number_field  w-50" value="<?php echo htmlentities(isset($data_array['acc_no'])?$data_array['acc_no']:'');?>"  placeholder="Enter Bank Account Number"  maxlength="16"/>
                                                    <?php  }  ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span DisplayLabelID="553"><?php echo htmlentities('Account Head Code and Name'); ?></span></td>
                                                    <td> <?php
                                                    if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                                                        if (isset($data_array['old_account_head_code']) && $data_array['old_account_head_code'] !='') {
                                                            echo htmlentities($data_array['old_account_head_code']) . ' - ' . htmlentities($data_array['account_head_name_en']);
                                                        }
                                                        
                                                    } else {
                                                    
                                                    ?>
                                                    <select  id="acc_head_code" name="acc_head_code" class="form-control form-control-sm  w-50" value="<?php echo htmlentities(isset($data_array['acc_head_code'])?$data_array['acc_head_code']:'');?>"  placeholder="Select Account Code">
                                                        <option value="" DisplayLabelID="255">Choose</option>
                                                        <?php 
                                                        $sel_dname_res = $this->Select_Account_Head_Code(0,15);
                                                        
                                                    foreach($sel_dname_res as $sel_dname_key=>$sel_dname_row)
                                                    {
                                                    ?>
                                                        <option value="<?php echo htmlentities($sel_dname_row['account_head_id']); ?>" 
                                                        <?php echo (isset($data_array['acc_head_code']) 
                                                        && $data_array['acc_head_code'] == $sel_dname_row['account_head_id'])
                                                    ? 'selected' : ''; ?>  >
                                                            <?php echo htmlentities($sel_dname_row['old_code']." - ". $sel_dname_row['account_head_name_en'] . ' (' . $sel_dname_row['new_code'] . ') '  ) ; ?> 
                                                        </option>
                                                        <?php	
                                                    } ?>
                                                            
                                                    </select>
                                                    <script>
                                                        document.getElementById("acc_head_code").value =
                                                            '<?php echo isset($data_array['acc_head_code'])?$data_array['acc_head_code']:'';?>';
                                                        </script>
                                                    <?php  
                                                    
                                                }  ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span DisplayLabelID="415"><?php echo htmlentities('Account Opening Date'); ?></span></td>
                                                    <td><?php
                                                     if ((isset($data_array["mode"]) && $data_array["mode"] == "delete") || (isset($data_array["mode"]) && $data_array["mode"] == "edit")) {
                                                        if (isset($data_array['from_date']) && $data_array['from_date']!='') {
                                                            echo htmlentities($data_array['from_date']);
                                                        }
                                                    } else { ?>
                                                    <input type="text" id="from_date" name="from_date" class="form-control form-control-sm field_datepicker user_enter_date  w-50" value="<?php echo htmlentities(isset($data_array['from_date'])?$data_array['from_date']:''); ?>"  placeholder="Select Account Opening Date"/>
                                                    <?php  }  ?>
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
                                                            name="submit" id="save"> <i class="<?php echo htmlentities($data_array["mode_icon"]);?> pr-1" aria-hidden="true"></i> <?php echo htmlentities($data_array["mode_name"]);?></button>
                                                        &nbsp;
                                                        <?php
                                                            $uri = $_SERVER['REQUEST_URI'];
                                                            $parts = parse_url($uri);
                                                            $path = $parts['path'] ?? '';
                                                            parse_str($parts['query'] ?? '', $params);
                                                            unset($params['edit_id'], $params['del_id']);
                                                            $clean_query = http_build_query($params);
                                                            $clean_url = $path . ($clean_query ? '?' . $clean_query : '');

                                                        ?>
                                                        <a class="btn btn-secondary btn-sm" href="<?php echo $clean_url; ?>"><i class="fa fa-eraser pr-1"></i> Clear</a>
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
                     <span DisplayLabelID="939"><?php echo htmlentities('Bank Account Entry'); ?></span> 
                </h4>
                <div class="single-table">
                    <table class="table table-bordered m-0 p-0 table-striped tndtp_report_table" id="dataTable"
                        style="width:auto!important;">
                        <thead>
                            <tr>
                                <td><span DisplayLabelID="174"><?php echo htmlentities('Sl. No'); ?></span></td>
                                <td><span DisplayLabelID="374"><?php echo htmlentities('Bank'); ?></span></td>
                                <td><span DisplayLabelID="374"><?php echo htmlentities('Bank Name'); ?></span></td>
                                <td><span DisplayLabelID="940"><?php echo htmlentities('Branch Name'); ?></span></td>
                                <td><span DisplayLabelID="943"><?php echo htmlentities('IFSC Code'); ?></span></td>
                                <td><span DisplayLabelID="943"><?php echo htmlentities('Account Head Code and Name'); ?></span></td>
                                <td><span DisplayLabelID="553"><?php echo htmlentities('Account Number'); ?></span></td>
                                <td><span DisplayLabelID="415"><?php echo htmlentities('Account Opening Date'); ?></span></td>
                                <td><span DisplayLabelID="346"><?php echo htmlentities('Actions'); ?></span></td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
$list_com = "
SELECT 
    a.bank_code, 
    a.bankaccount_id, 
    b.bank_name_en, 
    d.bankbranch_name AS bankbranchname_en, 
    d.bankbranch_name_ll AS bankbranchname_ta, 
    d.ifsccode, 
    a.account_no, 
    a.from_date, 
    f.old_account_head_code AS old_account_head_code,
    f.new_account_head_code AS new_account_head_code,
    f.account_head_name_en,
    f.account_head_name_ta
FROM  
    (
        SELECT 
            bank_code, 
            bankaccount_id, 
            bank_id, 
            bankbranch_id, 
            account_no,
            from_date, 
            del_flag, 
            dcode, 
            lbcode, 
            is_enable, 
            accounthead_id
        FROM accounts_master.t_bank_account 
        WHERE dcode = :dcode 
          AND lbcode = :lbcode 
          AND del_flag IS NULL
    ) a
LEFT JOIN accounts_master.m_bank AS b 
    ON a.bank_id = b.bank_id
LEFT JOIN accounts_master.m_bankbranch AS d 
    ON a.bankbranch_id = d.bankbranch_id
LEFT JOIN accounts_master.m_account_head AS f 
    ON f.account_head_id=a.accounthead_id
    WHERE 
         f.del_flag IS NULL
";

		$set = $this->prepare($list_com,array(":dcode" => $dcode, ":lbcode" => $lbcode),2);
        $slno = 1;
        if(count($set)>0){
        foreach ($set as $key => $row) {
            ?>
                        
                            <tr>
                                <td><?php echo htmlentities($slno++); ?></td>
                                <td align="left"><?php echo htmlentities($row['bank_code']); ?></td>
                                <td align="left"><?php echo htmlentities($row['bank_name_'.$lang_code_2d]); ?></td>
                                <td align="left"><?php echo htmlentities($row['bankbranchname_'.$lang_code_2d]); ?></td>
                                <td align="left"><?php echo htmlentities($row['ifsccode']); ?></td>
                                <td align="left"><?php echo htmlentities($row['old_account_head_code'].'-'.$row['account_head_name_en'].'('.$row['new_account_head_code'].')'); ?></td>
                                <td align="left"><?php echo htmlentities($row['account_no']); ?></td>
                                <td align="left"><?php echo htmlentities($row['from_date']); ?></td>
                                <td align="left">
                                    <a href="?edit_id=<?php echo base64_encode($row['bankaccount_id']); ?>"
                                        class="btn btn-warning btn-sm"><i class="fa fa-pencil pr-1"
                                            aria-hidden="true"></i>Edit</a>
                                    <a href="?del_id=<?php echo base64_encode($row['bankaccount_id']); ?>"
                                        class="btn btn-danger btn-sm"><i class="fa fa-trash-o p-1"
                                            aria-hidden="true"></i>Delete</a>
                                </td>
                            </tr>

                       
                        <?php
        } 
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

        $this->Template('Template1', "Bank Account Entry", $ob_output_main_forms, array(
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
       
		if (!isset($save_data["del_id"]) || $save_data["del_id"]=='') {
            if (!isset($save_data["edit_id"]) || $save_data["edit_id"]=='') {
                if(isset($save_data['bank_code']) && $save_data['bank_code'] != '')
                {
                    $bank_code = $save_data['bank_code'];
                    $bank_code_Validation = $this->Field_Validation(
                        array
                        (
                        'Field_Type'=>'text_number',
                        'Field_Value'=>$bank_code,
                        'Field_Name'=>'bank_code',
                        'Field_Max_Length'=>5,
                        'Field_Label_Name'=>'Bank Code',
                        )
                        );
                        
                        if ($bank_code_Validation['Status'] == "Error") {
                            $this->main_form(array_merge(array(
                                "STATUS" => "ERROR", 
                                "STATUS_TYPE" => "FIELD",
                                "FIELD_NAME" => "bank_code",
                                "MESSAGE" => $bank_code_Validation['Message']
                            ), $save_data));
                            exit;			
                        }
                    }  
                else
                {
                    $this->main_form(array_merge(array(
                        "STATUS" => "ERROR", 
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "bank_code",
                        "MESSAGE" => "Enter Bank Code"
                    ), $save_data));
                    exit;
                }
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
                
                if(isset($save_data['ifsc_code']) && $save_data['ifsc_code']!=''){
                    $ifsc_code = $save_data['ifsc_code'];
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
                if(isset($save_data['from_date']) && $save_data['from_date'] != '')
                {
                    $from_date = date("Y-m-d", strtotime($save_data['from_date']));			
                    $from_date_Validation = $this->Field_Validation(
                    array
                    (
                        'Field_Type'=>'date',
                        'Field_Value'=>$from_date,
                        'Field_Name'=>'from_date',
                        'Field_Label_Name'=>'Account Opening Date',
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
                        "MESSAGE" => 'Select Account Opening Date'
                    ), $save_data));
                    exit;
                }
            }else{
                $bank_code=$bank_id=$branch_id=$from_date=$ifsc_code=NULL;
            }
            if(isset($save_data['acc_head_code']) && $save_data['acc_head_code'] != '')
			{
				$acc_head_id = $save_data['acc_head_code'];
				$acc_head_Validation = $this->Field_Validation(
				array
				(
					'Field_Type'=>'number',
					'Field_Value'=>$acc_head_id,
					'Field_Name'=>'acc_head_id',
					'Field_Max_Length'=>5,
					'Field_Label_Name'=>'Account Head',
					)
				);			
				if ($acc_head_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR", 
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "acc_head_id",
						"MESSAGE" => $acc_head_Validation['Message']
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
					"MESSAGE" => 'Select Account Head'
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
        }else {
            $bank_code=$bank_id=$branch_id=$acc_no=$from_date=$ifsc_code=$acc_head_id=NULL;
        }
        $this->beginTransaction();
		$nameentry = "accounts_master.sp_bankaccount_entry";
        $getCurrentUser = $this->getCurrentUser();
        $getIpAddress = $this->getIpAddress();
		$edit_id = isset($save_data["edit_id"]) && $save_data["edit_id"]!=''?($save_data["edit_id"]):0;
		$del_id = isset($save_data["del_id"]) && $save_data["del_id"]!=''?($save_data["del_id"]):0;
        if (isset($save_data["edit_id"])  && $save_data["edit_id"]!='') {
            $save_query = "select " . $nameentry . "(:bank_code, :bank_id, :branch_id,:acc_head_id, :acc_no,  :from_date, :ifsc_code, :dcode, :lbcode, :getCurrentUser,:getIpAddress,:edit_id,:del_id)"; 
			$res = $this->prepare($save_query,array(":bank_code"=>$bank_code, ":bank_id"=>$bank_id,":branch_id"=>$branch_id,":acc_no"=>$acc_no,":acc_head_id"=>$acc_head_id,":from_date"=>$from_date,":ifsc_code"=>$ifsc_code,":dcode"=>$dcode,":lbcode"=>$lbcode,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>$edit_id,":del_id"=>$del_id),4);
			$message='Data Updated SccessFully';
        } else if (isset($save_data["del_id"]) && $save_data["del_id"]!='')  {
            $save_query = "select " . $nameentry . "(:bank_code, :bank_id, :branch_id,:acc_head_id, :acc_no,  :from_date, :ifsc_code, :dcode, :lbcode, :getCurrentUser,:getIpAddress,:edit_id,:del_id)"; 
			$res = $this->prepare($save_query,array(":bank_id"=>Null,":bank_code"=>Null,":branch_id"=>Null,":acc_no"=>Null,":acc_head_id"=>$acc_head_id,":from_date"=>Null,":ifsc_code"=>Null,":dcode"=>$dcode,":lbcode"=>$lbcode,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>$edit_id,":del_id"=>$del_id),4);
		     $message='Data Deleted SccessFully';
        } else {
            $save_query = "select " . $nameentry . "(:bank_code, :bank_id, :branch_id,:acc_head_id, :acc_no, :from_date, :ifsc_code, :dcode, :lbcode, :getCurrentUser,:getIpAddress,:edit_id,:del_id)"; 
			$res = $this->prepare($save_query,array(":bank_id"=>$bank_id,":bank_code"=>$bank_code,":branch_id"=>$branch_id,":acc_no"=>$acc_no,":acc_head_id"=>$acc_head_id,":from_date"=>$from_date,":ifsc_code"=>$ifsc_code,":dcode"=>$dcode,":lbcode"=>$lbcode,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>$edit_id,":del_id"=>$del_id),4);
			$message='Data Saved SccessFully';
        }
       
       if (!isset($res->errorInfo)) {
            $this->commit();
            $this->main_form(array(
                "STATUS" => "SUCCESS",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" =>  $message,
            ));
        } else {
            $this->rollBack();
            $this->main_form(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Data Save Failed Due To Duplicate Entry",
            ));
        }
    }
}
$AccountEntry = new AccountEntry();
$lang_code_2d = $AccountEntry->getCurrentUserLanguage2D();
$lbcode=$AccountEntry->getCurrentLocalBodyCode();
$dcode=$AccountEntry->getCurrentDistrictCode();
if(isset($_POST['cmd']) && $_POST['cmd'] !=''){
	$cmd=base64_decode($_POST['cmd']);
	if($cmd == 1){
         if (isset($_POST['bank_id']) && $_POST['bank_id']!='') {
             $bank_id = base64_decode($_POST['bank_id']);
             $bank_Code_Validation = $AccountEntry->Field_Validation(
                 array(
                     'Field_Type' => 'number',
                     'Field_Value' =>  $bank_id,
                     'Field_Name' => 'bank_id',
                     "Field_Max_length" => 10,
                     "Field_Min_length" => 0,
                     'Field_Label_Name' => 'Invalid Bank Id',
                 )
             );
             if ($bank_Code_Validation['Status'] == "Error") {
                 echo json_encode(array(
                     "STATUS" => "FAIL",
                     "FIELD_NAME" => "bank_code",
                     "MESSAGE" => "Invalid Bank Code"
                     ));
                     exit;	
                 exit;
             }
         }else{
             echo json_encode(array(
                 "STATUS" => "FAIL",
                 "FIELD_NAME" => "bank_code",
                 "MESSAGE" => "Select Bank Code"
                 ));
                 exit;	
             exit;
         }
		$options='<option value=""> Select Bank Branch Name</option>';
        $sel_branch_qry = "select bankbranch_id as branch_id,bankbranch_name as bankbranch_name_en, bankbranch_name_ll as bankbranch_name_ta from accounts_master.m_bankbranch where bank_id=:bank_id and lbcode=:lbcode and dcode=:dcode and del_flag is null and isactive=1;";
        $sel_branch_qry_res = $AccountEntry->prepare($sel_branch_qry,array(":bank_id"=>$bank_id ,":lbcode"=>$lbcode,":dcode"=>$dcode),2);
        foreach($sel_branch_qry_res as $sel_branch_qry_row){
            $curr="
            <option value=".$sel_branch_qry_row['branch_id'].">".$sel_branch_qry_row['bankbranch_name_'.$lang_code_2d]."</option>
            ";
            $options.=$curr;
        }
        echo $options;
        exit;
	}
	if($cmd == 2){
        if (isset($_POST['bank_id']) && $_POST['bank_id']!='') {
             $bank_id = base64_decode($_POST['bank_id']);
             $bank_Code_Validation = $AccountEntry->Field_Validation(
                 array(
                     'Field_Type' => 'number',
                     'Field_Value' =>$bank_id,
                     'Field_Name' => 'bank_id',
                     "Field_Max_length" => 10,
                     "Field_Min_length" => 0,
                     'Field_Label_Name' => 'Invalid Bank Id',
                 )
             );
             if ($bank_Code_Validation['Status'] == "Error") {
                 echo json_encode(array(
                     "STATUS" => "FAIL",
                     "FIELD_NAME" => "bank_code",
                     "MESSAGE" => "Invalid Bank Code"
                     ));
                     exit;	
                 exit;
             }
         }else{
             echo json_encode(array(
                 "STATUS" => "FAIL",
                 "FIELD_NAME" => "bank_code",
                 "MESSAGE" => "Select Bank Code"
                 ));
                 exit;	
             exit;
         }
        if (isset($_POST['branch_id']) && $_POST['branch_id']!='') {
             $branch_id = base64_decode($_POST['branch_id']);
             $branch_id_Validation = $AccountEntry->Field_Validation(
                 array(
                     'Field_Type' => 'number',
                     'Field_Value' => $branch_id,
                     'Field_Name' => 'branch_id',
                     "Field_Max_length" => 10,
                     "Field_Min_length" => 0,
                     'Field_Label_Name' => 'Invalid Bank Branch',
                 )
             );
             if ($branch_id_Validation['Status'] == "Error") {
                 echo json_encode(array(
                     "STATUS" => "FAIL",
                     "FIELD_NAME" => "branch_id",
                     "MESSAGE" => "Invalid Bank Branch"
                     ));
                     exit;	
                 exit;
             }
         }else{
             echo json_encode(array(
                 "STATUS" => "FAIL",
                 "FIELD_NAME" => "branch_id",
                 "MESSAGE" => "Select Bank Branch"
                 ));
                 exit;	
             exit;
         }
		$sel_branch_qry = "select ifsccode from accounts_master.m_bankbranch where bank_id=:bank_id and bankbranch_id=:branch_id and isactive=:isactive and  del_flag is null";
		$sel_branch_qry_res = $AccountEntry->prepare($sel_branch_qry,array(":bank_id"=>$bank_id, ":branch_id"=>$branch_id, ":isactive"=>1),4);
		echo $sel_branch_qry_res['ifsccode'];
		exit;
	}
    if($cmd == 3)
    {   
        $bank_code=base64_decode($_POST['bank_code']);
        if (isset($_POST['bank_code']) && $_POST['bank_code']!='') {
             $bank_id = base64_decode($_POST['bank_code']);
             $bank_Code_Validation = $AccountEntry->Field_Validation(
                 array(
                     'Field_Type' => 'text',
                     'Field_Value' => $bank_id ,
                     'Field_Name' => 'bank_id',
                     "Field_Max_length" => 10,
                     "Field_Min_length" => 0,
                     'Field_Label_Name' => 'Bank Id',
                 )
             );
             if ($bank_Code_Validation['Status'] == "Error") {
                 echo json_encode(array(
                     "STATUS" => "FAIL",
                     "FIELD_NAME" => "bank_code",
                     "MESSAGE" => "Invalid Bank Code"
                     ));
                     exit;	
                 exit;
             }
         }else{
             echo json_encode(array(
                 "STATUS" => "FAIL",
                 "FIELD_NAME" => "bank_code",
                 "MESSAGE" => "Select Bank Code"
                 ));
                 exit;	
             exit;
         }
        $query='select bank_id,bank_name_en from accounts_master.m_bank where bank_code=:bank_code';
        $res=$AccountEntry->prepare($query,[":bank_code"=>$bank_code],4);
        echo json_encode([
            'bank_id'=>$res['bank_id'],'bank_name'=>$res['bank_name_en']
        ]);

    }
}else{
	if (isset($_POST["submit"])) {
		$AccountEntry->data_save(array_merge($_POST,$_GET));
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