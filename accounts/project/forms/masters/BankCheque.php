<?php
require_once '../../config/config.php';

class BankCheque extends ConfigClass
{

    public $page_token = "Bank_Cheque";
    public function __construct()
    {
        if (!isset($this->db)) {
        }
    }

    public function main_content($post_data_array = array())
    {

        $site_data = $this->siteData();
        if (!isset($post_data_array["edit_id"])) {
            $post_data_array["mode_name"] = "Save";
            $post_data_array["mode_class"] = "btn-success";
        } else if (isset($post_data_array["edit_id"])) {
            $post_data_array["mode_name"] = "Update";
            $post_data_array["mode_class"] = "btn-warning";
        }
        ob_start();

        // #############

        // PAGE CONTENT START

        // #############
        $state_code = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();
        $lang_code_2d = $this->getCurrentUserLanguage2D();
        ?>
        <input type="hidden" id="page_lable_id" name="page_lable_id" value="38" />
        <script type="text/javascript">  
             $(document).ready(function() {
                $('#dataTable').DataTable(); // Initialize the DataTable
             });
                $(document).on('blur', '#Cheq_From', function () {
                    var Cheq_From = $("#Cheq_From").val();
                    var Cheq_To = $("#Cheq_To").val();
                    if (Cheq_To.length == 6 && Cheq_From.length == 6 && Cheq_From < Cheq_To) {
                        let no_of_leaves = Cheq_To - Cheq_From + 1;
                        $('#NumberOfLeaves').val(no_of_leaves);
                    }else if(Cheq_To.length == 6 && Cheq_From.length == 6 && Cheq_From > Cheq_To){
                        alert('Enter Valid Cheque Numbers');
                        $("#Cheq_To").val('');
                        $("#Cheq_From").val('');
                        $("#NumberOfLeaves").val('');
                    }
                });
                $(document).on('blur', '#Cheq_To', function () {
                    var Cheq_To = $("#Cheq_To").val();
                    var Cheq_From = $("#Cheq_From").val();
                    if (Cheq_From == '') {
                        alert('Enter Cheque From Number');
                        $("#Cheq_From").focus();
                        return false;
                    }
                    if (Cheq_From == Cheq_To) {
                        alert('Enter Valid Cheque Numbers');
                        $("#Cheq_To").val('');
                        $("#Cheq_From").val('');
                        $("#NumberOfLeaves").val('');
                        return false;
                    }
                    if (Cheq_From > Cheq_To) {
                        alert('Enter Valid Cheque Numbers');
                        $("#Cheq_To").val('');
                        $("#Cheq_From").val('');
                        $("#NumberOfLeaves").val('');
                        return false;
                    }
                    if (Cheq_From != '' && Cheq_To != '') {
                        no_of_leaves = Cheq_To - Cheq_From + 1;
                        $('#NumberOfLeaves').val(no_of_leaves);
                    }
                });
                $(document).on('click', "#btn_save", function (event) {
                    try {
                        if ($("#bank_account_id").val().length == 0) {
                            throw {
                                msg: "Select Bank Account",
                                foc: "#bank_account_id"
                            }
                        }
                        if ($("#Cheq_From").val().length == 0) {
                            throw {
                                msg: "Enter Cheque From Number",
                                foc: "#Cheq_From"
                            }
                        }
                        if ($("#Cheq_To").val().length == 0) {
                            throw {
                                msg: "Enter Cheque To Number",
                                foc: "#Cheq_To"
                            }
                        }
                        if ($("#Cheq_From").val().length != 6) {
                            throw {
                                msg: "Cheque Number should be of 6 digits",
                                foc: "#Cheq_From"
                            }
                        }
                        if ($("#Cheq_To").val().length != 6) {
                            throw {
                                msg: "Cheque Number should be of 6 digits",
                                foc: "#Cheq_To"
                            }
                        }
                        if ($("#Cheq_To").val() <= $("#Cheq_From").val()) {
                            throw {
                                msg: "Enter Valid Cheque Numbers",
                                foc: "#Cheq_To"
                            }
                        }
                        if ($("#NumberOfLeaves").val().length == 0) {
                            throw {
                                msg: "Enter Number Of Leaves",
                                foc: "#NumberOfLeaves"
                            }
                        }
                        if ($("#NumberOfLeaves").val() == 0) {
                            throw {
                                msg: "Number Of Leaves should not be Zero",
                                foc: "#NumberOfLeaves"
                            }
                        }
                        return true;
                    } catch (e) {
                        alert(e.msg);
                        $(e.foc).focus();
                        event.preventDefault();
                    }
                });            
                $(document).on('change','#bank_account_id',  function(){
                    let bank_account_id=$('#bank_account_id').val();
                    if(bank_account_id!='')
                    {
                       $.ajax({
                            url: window.location.href,
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                cmd: btoa("1"),
                                bank_account_id: btoa(String(bank_account_id))
                            },
                            success: function (data) {
                                $('#branch_text').html(data.bankbranch_name_en);
                                $('#bank_text').html(data.bank_name_en);
                                $('#acc_head_text').html(data.account_head);
                            },
                            error: function (xhr, status, error) {
                                console.error(error);
                            }
                        });
                    }
                });
            </script>
        <?php
        
        if (isset($post_data_array["edit_id"])) {
            if (isset($post_data_array["edit_id"])) {
                $bank_data_id = base64_decode($post_data_array["edit_id"]);
                $bank_data_id_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $bank_data_id,
                        'Field_Name' => 'edit_id',
                        'Field_Max_length' => '6',
                        'Field_Label_Name' => 'Edit ID',
                    )
                );
                if ($bank_data_id_Validation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "bank_data_id",
                        "MESSAGE" => $bank_data_id_Validation['Message']
                    ), $post_data_array));
                    exit;
                }

                if ($bank_data_id_Validation['Status'] == "Error") {
                    echo 'Invalide Request';
                    exit;
                }
            }
           
           $sel_bank_data = "SELECT 
        a.bank_id,
        a.bank_account_id,
        acc.accounthead_id as acc_head_id,
        bank_code,
        bank_name_en,
        cheque_no_from,
        cheque_no_to,
        number_of_leaves,
        bank_branch_id,
        bankbranch_name, 
		account_no,
		old_account_head_code, new_account_head_code, account_head_name_en, account_head_name_ta
    FROM 
        (
            SELECT 
                bank_id, 
                bank_account_id,
                cheque_no_from,
                cheque_no_to,
                number_of_leaves,
                bank_branch_id
            FROM accounts_master.t_bank_cheque_leaves_details
            WHERE bank_cheque_id = :bank_data_id  
              AND del_flag IS NULL
        ) AS a
    LEFT JOIN
        (
            SELECT 
                bankbranch_id,
                bank_id,
                bankbranch_name
            FROM accounts_master.m_bankbranch
            WHERE del_flag IS NULL
        ) AS b
        ON a.bank_id = b.bank_id
       AND b.bankbranch_id = a.bank_branch_id::INT
    LEFT JOIN
        (
            SELECT 
                bank_id,
                bank_code,
                bank_name_en
            FROM accounts_master.m_bank
            WHERE del_flag IS NULL
        ) AS c
        ON a.bank_id = c.bank_id
       AND b.bank_id = c.bank_id
    LEFT JOIN
        (
            SELECT accounthead_id,bankaccount_id, account_no
            FROM accounts_master.t_bank_account 
        ) AS acc
         ON acc.bankaccount_id=a.bank_account_id
		 left join
		 (select old_account_head_code, new_account_head_code, account_head_name_en, account_head_name_ta, account_head_id from accounts_master.m_account_head)ach on ach.account_head_id=acc.accounthead_id;";
            $data_array_val = $this->prepare($sel_bank_data, array(":bank_data_id" => $bank_data_id), 4);
        }

        ?>
        <form action="BankCheque.php" method="post" class="my-3" enctype="multipart/form-data" autocomplete="off">
            <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>"
                name="<?php echo htmlentities($this->page_token); ?>"
                value="<?php echo htmlentities($this->token($this->page_token)); ?>">
            <input class="form-control  form-control-sm" type="hidden" id="edit_id"
                name="edit_id"
                value="<?php echo htmlentities(isset($post_data_array["edit_id"])&&$post_data_array["edit_id"]!=''?$post_data_array["edit_id"]:''); ?>">
            <div class="container">
                <div class="card">
                    <div class="card-body pl-5 pr-5">
                        <?php
                        if (isset($post_data_array["STATUS"])) {
                            echo $this->ShowMessage($post_data_array["STATUS"], $post_data_array["MESSAGE"]);
                        }
                        ?>
                        <table class="table table-bordered m-0 p-0 tndtp_form_table">
                            <thead class="bg-th-form-dsg">
                                <tr>
                                    <th align="center" scope="col" colspan="12">Bank Cheque Book Entry</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2" align="left" style="width:50%;"><span DisplayLabelID="">Bank Account Number</span></td>
                                    <td colspan="2">
                                        <?php  if(!isset($post_data_array["edit_id"])){?>  
                                        <select id="bank_account_id" name="bank_account_id"
                                                class="form-control form-control-sm w-50" value="<?php
                                                if (isset($post_data_array['bank_account_id'])) {
                                                    echo htmlentities($post_data_array['bank_account_id']);
                                                } else if (isset($data_array_val['bank_account_id'])) {
                                                    echo htmlentities($data_array_val['bank_account_id']);
                                                } ?>" >
                                                <option value="">Select Account Number</option>
                                                <?php
                                                    $query='SELECT account_no,bankaccount_id FROM accounts_master.t_bank_account WHERE 
                                                        dcode=:dcode AND
                                                        lbcode=:lbcode AND
                                                        del_flag is NULL ';
                                                        $res=$this->prepare($query,[":dcode"=>$dcode,":lbcode"=>$lbcode],2);
                                                        foreach($res as $row){
                                                            ?>
                                                            <option value="<?=$row['bankaccount_id']?>"><?=$row['account_no']?></option>
                                                            <?php
                                                        } ?>
                                            </select>
                                            <?php
                                            }else{
                                                echo htmlentities($data_array_val['account_no']);
                                                ?>
                                                <input type="hidden" name="bank_account_id" id="bank_account_id" value="<?php echo htmlentities($data_array_val['bank_account_id']); ?>" />
                                            <?php } ?>

                                        </td>
                                    </tr>
                                <tr>
                                    <td colspan="2" align="left" style="width:50%;"><span
                                            DisplayLabelID="553"><?php echo htmlentities('Account Head Code and Name'); ?></span>
                                    </td>
                                    <td>
                                        <span id="acc_head_text"> <?php  if(isset($post_data_array["edit_id"])){  echo htmlentities($data_array_val['old_account_head_code'] . ' - ' . $data_array_val['account_head_name_en'] . '(' . $data_array_val['new_account_head_code'] . ')' ); } ?>  </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="left" style="width:50%;"><span DisplayLabelID="">Bank</span></td>
                                    <td colspan="2">
                                        <span id="bank_text"><?php  if(isset($post_data_array["edit_id"])){  echo htmlentities($data_array_val['bank_name_en']); } ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="left" style="width:50%;"><span DisplayLabelID="">Bank Branch</span></td>
                                    <td colspan="2"> 
                                        <span id="branch_text"><?php  if(isset($post_data_array["edit_id"])){  echo htmlentities($data_array_val['bankbranch_name']); } ?></span>
                                    </td>
                                </tr>
                                    <tr>
                                        <td colspan="2" align="left" style="width:50%;"><span DisplayLabelID="">Cheque No. From</span></td>
                                        <td colspan="2">
                                            <input type="text" id="Cheq_From" name="Cheq_From" maxlength="6"
                                                class="form-control form-control-sm w-50 bank_cheque" value="<?php
                                                if (isset($post_data_array['Cheq_From'])) {
                                                    echo htmlentities($post_data_array['Cheq_From']);
                                                } else if (isset($data_array_val['cheque_no_from'])) {
                                                    echo htmlentities($data_array_val['cheque_no_from']);
                                                }
                                                ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="left" style="width:50%;"><span DisplayLabelID="">Cheque No. To</span></td>
                                        <td colspan="2">
                                            <input type="text" id="Cheq_To" name="Cheq_To" class="form-control form-control-sm w-50 bank_cheque"  maxlength="6"
                                                value="<?php
                                                if (isset($post_data_array['Cheq_To'])) {
                                                    echo htmlentities($post_data_array['Cheq_To']);
                                                } else if (isset($data_array_val['cheque_no_to'])) {
                                                    echo htmlentities($data_array_val['cheque_no_to']);
                                                }  ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="left" style="width:50%;"><span DisplayLabelID="">Number Of Leaves</span></td>
                                        <td colspan="2">
                                            <input id="NumberOfLeaves" name="NumberOfLeaves"
                                                class="form-control form-control-sm w-50" value="<?php
                                                if (isset($post_data_array['NumberOfLeaves'])) {
                                                    echo htmlentities($post_data_array['NumberOfLeaves']);
                                                } else if (isset($data_array_val['number_of_leaves'])) {
                                                    echo htmlentities($data_array_val['number_of_leaves']);
                                                }?>" readonly></input>
                                        </td>
                                    </tr>
                                    <tr align="center">
                                        <td scope="row" colspan="5" align="center" class="text-center">
                                            <input type="submit" id="btn_save" name="btn_save"
                                                value="<?php echo htmlentities($post_data_array['mode_name']); ?>"
                                                class="btn btn-md text-white font-weight-bold <?php echo htmlentities($post_data_array['mode_class']); ?>" />
                                            <input type="button" id="btn_reset" name="btn_reset" value="Cancel"
                                                class="btn btn-md text-white font-weight-bold btn-secondary"  onclick="window.location='BankCheque.php'" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="single-table">
                                <table class="table table-bordered text-center table-striped tndtp_report_table" id="dataTable">
                                    <thead class="text-left">
                                        <tr>
                                            <th scope="col"><span DisplayLabelID="">S.No </span></th>
                                            <th scope="col"><span DisplayLabelID="">Bank Name </span></th>
                                            <th scope="col"><span DisplayLabelID="">Branch Name</span></th>
                                            <th scope="col"><span DisplayLabelID="">Account Head Code and Name</span></th>
                                            <th scope="col"><span DisplayLabelID="">Account Number</span></th>
                                            <th scope="col"><span DisplayLabelID="">Cheque From No</span></th>
                                            <th scope="col"><span DisplayLabelID="">Cheque To No</span></th>
                                            <th scope="col"><span DisplayLabelID="">Number of Cheque leaves</span></th>
                                            <th scope="col"><span DisplayLabelID="">Edit</span></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tradedetails_data">
                                        <?php
                                        $sel_qry = "SELECT 
                                            bank_account_id,
                                            a.bank_cheque_id,
                                            cheque_no_from,
                                            cheque_no_to,
                                            che.number_of_leaves,
                                            bank_name_en,
                                            bankbranch_name,account_head_name_en,old_account_head_code,account_no
                                            FROM (
                                            (
                                                SELECT 
                                                    bank_account_id,
                                                    bank_cheque_id,
                                                    cheque_no_from,
                                                    cheque_no_to,
                                                    bank_id AS bank_id,
                                                    bank_branch_id
                                                FROM accounts_master.t_bank_cheque_leaves_details
                                                WHERE del_flag IS NULL
                                                AND lbcode = :lbcode
                                                AND dcode = :dcode
                                                AND bank_branch_id::integer IN (
                                                    SELECT bankbranch_id
                                                    FROM accounts_master.m_bankbranch
                                                    WHERE del_flag IS NULL
                                                        AND lbcode = :lbcode
                                                        AND dcode = :dcode
                                                )
                                            ) AS a
                                            LEFT JOIN (
                                                SELECT 
                                                    bank_name_en,
                                                    bank_id
                                                FROM accounts_master.m_bank
                                                WHERE del_flag IS NULL
                                            ) AS b
                                            ON a.bank_id::integer = b.bank_id
                                            LEFT JOIN (
                                                SELECT 
                                                    bankbranch_id,
                                                    bankbranch_name
                                                FROM accounts_master.m_bankbranch
                                                WHERE del_flag IS NULL
                                                AND lbcode = :lbcode
                                                AND dcode = :dcode
                                            ) AS c
                                            ON c.bankbranch_id::integer = a.bank_branch_id::integer
                                            left join
										(SELECT bankaccount_id,bankbranch_id,accounthead_id,account_no FROM accounts_master.t_bank_account 
                                        WHERE dcode=:dcode AND lbcode=:lbcode AND del_flag is NULL)d
                                        on d.bankaccount_id=a.bank_account_id
                                        left join 
                                        (select account_head_id,old_account_head_code,account_head_name_en 
                                        from accounts_master.m_account_head where del_flag is NULL)e
                                        on d.accounthead_id=e.account_head_id
                                        )left join(select count(bank_cheque_id) as number_of_leaves, bank_cheque_id  from accounts_master.t_bank_cheque_leaves where isactive=1 and isused='N' and del_flag is null group by bank_cheque_id) che on che.bank_cheque_id = a.bank_cheque_id  order by bank_account_id";
                                        $sel_qry_res = $this->prepare($sel_qry, array(":lbcode" => $lbcode, ":dcode" => $dcode), 2);
                                        if (count($sel_qry_res) > 0) {
                                            foreach ($sel_qry_res as $sel_qry_key => $sel_qry_row) {
                                                ?>
                                                <tr>
                                                    <td class="text-left">
                                                        <?php echo htmlentities($sel_qry_key + 1); ?>
                                                    </td>
                                                    <td class="text-left">
                                                        <?php echo htmlentities($sel_qry_row['bank_name_en']); ?>
                                                    </td>
                                                    <td class="text-left">
                                                        <?php echo htmlentities($sel_qry_row['bankbranch_name']); ?>
                                                    </td>
                                                    <td class="text-left">
                                                        <?php echo htmlentities($sel_qry_row['old_account_head_code'] . '-' . $sel_qry_row['account_head_name_en']); ?>
                                                    </td>
                                                    <td class="text-left">
                                                        <?php echo htmlentities(isset($sel_qry_row['account_no'])?$sel_qry_row['account_no']:''); ?>
                                                    </td>
                                                    <td class="text-left">
                                                        <?php echo htmlentities($sel_qry_row['cheque_no_from']); ?>
                                                    </td>
                                                    <td class="text-left">
                                                        <?php echo htmlentities($sel_qry_row['cheque_no_to']); ?>
                                                    </td>
                                                    <td class="text-left">
                                                        <?php echo htmlentities($sel_qry_row['number_of_leaves']); ?>
                                                    </td>
                                                    <td class="text-left">
                                                        <a href="?edit_id=<?php echo htmlentities(base64_encode($sel_qry_row['bank_cheque_id'])); ?>" >  Edit </a>
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
        </form>
        <?php

        // #############

        // PAGE CONTENT END

        // #############

        $ob_output_main_contents = ob_get_contents();
        ob_clean();
        $this->Template($this->getCurrentUserTemplate() != "" ? $this->getCurrentUserTemplate() : "Template1", "Bank Cheque Book Entry", $ob_output_main_contents, array(), array('page_id' => 12));
    }

    public function Bank_Details($post_data_array = array())
    {
        $Bank_Code = base64_decode($post_data_array['Bank_Code']);
        $state_code = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $tpcode = $this->getCurrentLocalBodyCode();
        ob_start();

        $sel_Bank_Details = "";

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
        // if (!$this->validateToken($this->page_token, $save_data[$this->page_token])) {
        //     $this->main_content(array_merge(array(
        //         "STATUS" => "ERROR",
        //         "STATUS_TYPE" => "FIELD",
        //         "FIELD_NAME" => $this->page_token,
        //         "MESSAGE" => "Invalid Token"
        //     ), $save_data));
        //     exit;
        // }

        if (($this->getCurrentStateCode() != '')) {
            $statecode = $this->getCurrentStateCode();
            $statecode_Validation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $statecode,
                    'Field_Name' => 'statecode',
                    'Field_Length'=>'2',
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
                    'Field_Max_length'=>'2',
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
                    'FieldLength'=>'6',
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

        if (isset($save_data['edit_id']) && $save_data['edit_id']!='') {
            $edit_id = isset($save_data['edit_id']) ? base64_decode($save_data['edit_id']) : 0;

            $edit_id_Validation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $edit_id,
                    'Field_Name' => 'edit_id',
                    'Field_Max_length'=>'5',
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
        } else {
            $edit_id = 0;
        }
        if (isset($save_data['del_id']) && $save_data['del_id'] !='') {
            $del_id = isset($save_data['del_id']) ? base64_decode($save_data['del_id']) : 0;
            $del_id_Validation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $del_id,
                    'Field_Name' => 'del_id',
                    'Field_Max_length'=>'5',
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
        } else {
            $del_id = 0;
        }
        if (isset($save_data['bank_account_id']) && $save_data['bank_account_id']!='') {
            $Bank_Account_Id = $save_data['bank_account_id'];
            $Bank_Account_Id_enValidation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $Bank_Account_Id,
                    'Field_Name' => 'bank_account_id',
                    'Field_Max_length'=>'5',
                    'Field_Label_Name' => 'bank_account_id',
                )
            );

            if ($Bank_Account_Id_enValidation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "account_number",
                    "MESSAGE" => $Bank_Account_Id_enValidation['Message']
                ), $save_data));
                exit;
            }
        }else{
            $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "account_number",
                    "MESSAGE" => 'Select Account Number'
                ), $save_data));
                exit;
        }
        if (isset($save_data['Cheq_From']) && $save_data['Cheq_From']!='') {
            $Cheq_From = $save_data['Cheq_From'];
            $Cheq_From_Validation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $Cheq_From,
                    'Field_Name' => 'Cheq_From',
                    'Field_Length'=>'6',
                    'Field_Label_Name' => 'Cheq From Number',
                )
            );

            if ($Cheq_From_Validation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "Cheq_From",
                    "MESSAGE" => $Cheq_From_Validation['Message']
                ), $save_data));
                exit;
            }
        }else{
             $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "Cheq_From",
                    "MESSAGE" => 'Enter Cheq From Number'
                ), $save_data));
                exit;
        }
        if (isset($save_data['Cheq_To']) && $save_data['Cheq_To']!='') {
            $Cheq_To = $save_data['Cheq_To'];
            $Cheq_ToValidation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $Cheq_To,
                    'Field_Name' => 'Cheq_To',
                    'Field_Length'=>'6',
                    'Field_Label_Name' => 'Cheq To Number',
                )
            );

            if ($Cheq_ToValidation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "Cheq_To",
                    "MESSAGE" => $Cheq_ToValidation['Message']
                ), $save_data));
                exit;
            }
        }else{
            $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "Cheq_To",
                    "MESSAGE" => 'Enter Cheq To Number'
                ), $save_data));
                exit;
        }

        if (isset($save_data['NumberOfLeaves']) && $save_data['NumberOfLeaves']!='') {
            $NumberOfLeaves = (int)$save_data['NumberOfLeaves'];
            $NumberOfLeavesValidation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $NumberOfLeaves,
                    'Field_Name' => 'NumberOfLeaves',
                    'Field_Max_length'=>'5',
                    'Field_Label_Name' => 'Number Of Leaves',
                )
            );

            if ($NumberOfLeavesValidation['Status'] == "Error") {
                $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "NumberOfLeaves",
                    "MESSAGE" => $NumberOfLeavesValidation['Message']
                ), $save_data));
                exit;
            }
        }else{
            $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "NumberOfLeaves",
                    "MESSAGE" => 'Missing Number Of Leaves'
                ), $save_data));
                exit;
        }
        $Result_Message = "Data Saved SuccessFully";
        if ($edit_id > 0) {
            $Result_Message = "Data Updated SuccessFully";
        } else if ($del_id > 0) {
            $Result_Message = "Data Deleted SuccessFully";
        }
        $query='select a.bank_id, a.bankbranch_id, account_no, bank_name_en, bank_name_ta, bankbranch_name as bankbranch_name_en, bankbranch_name_ll as bankbranch_name_ta, old_account_head_code, new_account_head_code, account_head_name_en, account_head_name_ta from 
(select * from accounts_master.t_bank_account where bankaccount_id=:bankaccount_id and del_flag is null and dcode=:dcode and lbcode=:lbcode ) a left join 
(select * from accounts_master.m_account_head )d on a.accounthead_id=d.account_head_id left join
(select * from accounts_master.m_bank where del_flag is null)b on a.bank_id=b.bank_id left join
(select * from accounts_master.m_bankbranch where del_flag is null)c on a.bank_id=c.bank_id and a.bankbranch_id=c.bankbranch_id ';
        $res=$this->prepare($query,[':bankaccount_id'=>$Bank_Account_Id,":dcode"=>$dcode,":lbcode"=>$lbcode],4);
        $bank_id=$res['bank_id'];
        $bank_branch_id=$res['bankbranch_id'];
        $this->beginTransaction();
        $bank_cheque_details_function = "accounts_master.sp_insert_bank_cheque_details";
        $getCurrentUser = $this->getCurrentUser();
        $getIpAddress = $this->getIpAddress();
        if (isset($save_data["edit_id"]) && $save_data["edit_id"]!='') {
            $save_query = "select count(cheque_number) as cheque_number from accounts_master.t_bank_cheque_leaves where bank_id=:bank_id and bank_branch_id=:bank_branch_id and cheque_number::int between :Cheq_From and :Cheq_To  and del_flag is null and isactive='1'  and bank_cheque_id=:bank_cheque_id and bank_account_id=:bank_account_id;";
            $res = $this->prepare($save_query, array(":bank_id" => $bank_id, ":bank_branch_id" => $bank_branch_id, ":Cheq_From" => $Cheq_From, ":Cheq_To" => $Cheq_To, ":bank_cheque_id"=>base64_decode($save_data["edit_id"]), ":bank_account_id"=>$Bank_Account_Id), 4);
            // if ($res['cheque_number'] != 0) {
            //     $this->main_content(array(
            //         "STATUS" => "FAIL",
            //         "STATUS_TYPE" => "FORM",
            //         "MESSAGE" => "Cheque Number Already Exist."
            //     ));
            //     exit;
            // }
            $save_query = "select " . $bank_cheque_details_function . "(:dcode,:lbcode,:bank_code,:bank_name,:bank_account_id,:Cheq_From,:Cheq_To,:no_of_leaves,now()::DATE,:getCurrentUser,now()::DATE,  :getIpAddress,:edit_id,:del_id);";
            $res = $this->prepare($save_query, array(":dcode" => $dcode, ":lbcode" => $lbcode, ":bank_code" => $bank_id, ":bank_name" => $bank_branch_id,":bank_account_id"=>$Bank_Account_Id, ":Cheq_From" => $Cheq_From, ":Cheq_To" => $Cheq_To, ":no_of_leaves" => $NumberOfLeaves, ":getCurrentUser" => $getCurrentUser, ":getIpAddress" => $getIpAddress, ":edit_id" => $edit_id, ":del_id" => $del_id), 4);
            $json = json_decode($res['sp_insert_bank_cheque_details'], true);
            if ($json['STATUS'] === 'SUCCESS') {
                $save_query = "delete from accounts_master.t_bank_cheque_leaves where bank_cheque_id=:edit_id and bank_id=:bank_id and bank_branch_id=:bank_branch_id";
                $res = $this->prepare($save_query, array(":edit_id" => $edit_id, ":bank_id" => $bank_id, ":bank_branch_id" => $bank_branch_id), 4);
                $values = [];
                $params = [];
                $index = 0;
                for ($cheqinsert = $Cheq_From; $cheqinsert <= $Cheq_To; $cheqinsert++) {
                    $values[] = "(:bank_cheque_id{$index}, :bank_id{$index}, :bank_branch_id{$index}, :cheque_number{$index},:dcode,:lbcode,:ins_username{$index}, now(), :ins_ipaddress{$index}, :isactive{$index}, :bank_account_id{$index})";
                    $params[":bank_cheque_id{$index}"] = $json['Id'];
                    $params[":bank_id{$index}"] = $bank_id;
                    $params[":bank_branch_id{$index}"] = $bank_branch_id;
                    $params[":cheque_number{$index}"] =  str_pad($cheqinsert, 6, '0', STR_PAD_LEFT);
                    $params[":dcode"] = $dcode;
                    $params[":lbcode"] = $lbcode;
                    $params[":ins_username{$index}"] = $getCurrentUser;
                    $params[":ins_ipaddress{$index}"] = $getIpAddress;
                    $params[":bank_account_id{$index}"] = $Bank_Account_Id;
                    $params[":isactive{$index}"] = 1;
                    $index++;
                }
                if (!empty($values)) {
                    $save_query1 = "INSERT INTO accounts_master.t_bank_cheque_leaves(bank_cheque_id, bank_id, bank_branch_id, cheque_number,dcode,lbcode,ins_username, ins_date, ins_ipaddress, isactive, bank_account_id) VALUES " . implode(", ", $values);
                    $res1=$this->prepare($save_query1, $params, 4);
                }
            }
        } else {
            $save_query = "select count(cheque_number) as cheque_number from accounts_master.t_bank_cheque_leaves where bank_id=:bank_id and bank_branch_id=:bank_branch_id and cheque_number between :Cheq_From and :Cheq_To  and del_flag is null and isactive='1' and bank_account_id=:bank_account_id;";
            $res = $this->prepare($save_query, array(":bank_id" => $bank_id, ":bank_branch_id" => $bank_branch_id, ":Cheq_From" => $Cheq_From, ":Cheq_To" => $Cheq_To, ":bank_account_id"=>$Bank_Account_Id),4);
            if ($res['cheque_number'] > 0) {
                $this->main_content(array(
                    "STATUS" => "FAIL",
                    "STATUS_TYPE" => "FORM",
                    "MESSAGE" => "Already Cheque is added for these Bank and Branch"
                ));
                exit;
            }
            $save_query = "select " . $bank_cheque_details_function . "(:dcode,:lbcode,:bank_code,:bank_branch_id,:bank_account_id,:Cheq_From,:Cheq_To,:no_of_leaves,now()::DATE,:getCurrentUser,now()::DATE,  :getIpAddress,:edit_id,:del_id);";
            $res = $this->prepare($save_query, array(":dcode" => $dcode, ":lbcode" => $lbcode, ":bank_code" => $bank_id, ":bank_branch_id" => $bank_branch_id,":bank_account_id"=>$Bank_Account_Id,":Cheq_From" => $Cheq_From, ":Cheq_To" => $Cheq_To, ":no_of_leaves" => $NumberOfLeaves, ":getCurrentUser" => $getCurrentUser, ":getIpAddress" => $getIpAddress, ":edit_id" => $edit_id, ":del_id" => $del_id), 4);
            $json = json_decode($res['sp_insert_bank_cheque_details'], true);
            if ($json['STATUS'] === 'SUCCESS') {
                $values = [];
                $params = [];
                $index = 0;
                for ($cheqinsert = $Cheq_From; $cheqinsert <= $Cheq_To; $cheqinsert++) {
                    $values[] = "(:bank_cheque_id_{$index}, :bank_id{$index}, :bank_branch_id{$index}, :cheque_number{$index},:dcode,:lbcode, :ins_username_{$index}, now(), :ins_ipaddress_{$index}, :isactive{$index}, :bank_account_id{$index})";
                    $params[":bank_cheque_id_{$index}"] = $json['Id'];
                    $params[":bank_id{$index}"] = $bank_id;
                    $params[":bank_branch_id{$index}"] = $bank_branch_id;
                    $params[":cheque_number{$index}"] = str_pad($cheqinsert, 6, '0', STR_PAD_LEFT);
                    $params[":dcode"] = $dcode;
                    $params[":lbcode"] = $lbcode;
                    $params[":ins_username_{$index}"] = $getCurrentUser;
                    $params[":ins_ipaddress_{$index}"] = $getIpAddress;
                    $params[":bank_account_id{$index}"] = $Bank_Account_Id;
                    $params[":isactive{$index}"] = 1;
                    $index++;
                }
                if (!empty($values)) {
                    $save_query1 = "INSERT INTO accounts_master.t_bank_cheque_leaves (bank_cheque_id, bank_id, bank_branch_id, cheque_number,dcode,lbcode, ins_username, ins_date, ins_ipaddress, isactive, bank_account_id)VALUES " . implode(", ", $values);
                    $res1=$this->prepare($save_query1, $params, 4);
                }
            }
        }
        if (!isset($res1->errorInfo)) {
            $this->commit();
            $this->main_content(array(
                "STATUS" => "SUCCESS",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => $Result_Message
            ));
        } else {
            $this->rollBack();
            $this->main_content(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Data Save Failed Due To Duplicate Entry"
            ));
            exit;
        }
    }
}

$BankCheque = new BankCheque();
if (!isset($_POST['cmd'])) {
    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        $BankCheque->data_save(array_merge($_POST, $_GET));
    } else {
        $BankCheque->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
    }
} else if (isset($_POST['cmd'])) {
    $cmd = base64_decode($_POST['cmd']);
    if($cmd==1)
    {
        $bank_account_id=base64_decode($_POST['bank_account_id']);
        $lbcode=$BankCheque->getCurrentLocalBodyCode();
        $dcode=$BankCheque->getCurrentDistrictCode();
        $query="select a.bank_id, a.bankbranch_id, account_no, bank_name_en, bank_name_ta, bankbranch_name as bankbranch_name_en, bankbranch_name_ll as bankbranch_name_ta, old_account_head_code, new_account_head_code, account_head_name_en, account_head_name_ta, d.old_account_head_code || ' - ' || 
    d.account_head_name_en || ' (' || d.new_account_head_code || ')' AS account_head from 
(select * from accounts_master.t_bank_account where bankaccount_id=:bankaccount_id and del_flag is null and dcode=:dcode and lbcode=:lbcode ) a left join 
(select * from accounts_master.m_account_head )d on a.accounthead_id=d.account_head_id left join
(select * from accounts_master.m_bank where del_flag is null)b on a.bank_id=b.bank_id left join
(select * from accounts_master.m_bankbranch where del_flag is null)c on a.bank_id=c.bank_id and a.bankbranch_id=c.bankbranch_id ";
        $res=$BankCheque->prepare($query,[':bankaccount_id'=>$bank_account_id,":dcode"=>$dcode,":lbcode"=>$lbcode],4);
        echo json_encode($res);
        exit;
    }
}
?>