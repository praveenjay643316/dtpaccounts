<?php
require_once '../../config/config.php';
class CancelBankCheque extends ConfigClass
{
    public $page_token = "Cancel_Bank_Cheque";
    public function __construct()
    {
        if (!isset($this->db)) {
        }
    }
    public function main_content($post_data_array = array())
    {
        $site_data = $this->siteData();
        $post_data_array["mode_name"] = "Save";
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
        $lbcode = $this->getCurrentLocalBodyCode();
        $lang_code_2d = $this->getCurrentUserLanguage2D();
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $(document).on('click', "#btn_show", function () {
                    var Current_Field_id = $(this).attr('id');
                    $('#' + Current_Field_id).hide();
                    try {
                        if ($("#bank_account_id").val().length == '') {
                            throw {
                                msg: "Select Bank Account  ",
                                foc: "#bank_account_id"
                            }
                        }
                        if (($("#ChequeNo").val().length == '')) {
                            throw {
                                msg: "Enter Cheque no",
                                foc: ""
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
                                $('#acc_head_text').html(data.account_head_name_en);
                                $.ajax({
                                    url: window.location.href,
                                    type: 'post',
                                    data: {
                                        bank_account_id: btoa(bank_account_id),
                                        cmd: btoa(2)
                                    },
                                    success: function (data) {
                                        let html = ""
                                        if (data.length == 0) {
                                            alert('No cheque leaves available select other bank or branch');
                                            html = '<tr><td colspan="6" align="centre">No cheque leaves available</td></tr>';
                                        }
                                        else {
                                            let sno = 1
                                            data.forEach((bank) => {
                                                let curr_html = `
                                                <tr>
                                                    <td class="text-center">${sno++}</td>
                                                    <td class="text-center">${bank.bank_name}</td>
                                                    <td class="text-center">${bank.branch_name}</td>
                                                    <td class="text-center">${bank.old_account_head_code} - ${bank.account_head_name_en} (${bank.new_account_head_code})</td>
                                                    <td class="text-center">${bank.account_no}</td>
                                                    <td class="text-center">${bank.cheque_number}</td>
                                                </tr>
                                            `;
                                                html += curr_html;

                                            });
                                        }
                                        $("#dataTable2").children("tbody").html(html);
                                    },
                                    dataType: 'json'
                                });
                            },
                            error: function (xhr, status, error) {
                                console.error(error);
                            }
                        });
                    }
                });
                $(document).on('blur', '#ChequeNo', function () {
                    var ChequeNo = $("#ChequeNo").val();
                    var bank_account_id = $("#bank_account_id").val();
                    if (ChequeNo != '' && bank_account_id != '') {
                        if (isNaN(ChequeNo)) {
                            $("#ChequeNo").val('');
                            alert('cheque number should contain only 6 numeric digits');
                        }
                        else {
                            $.ajax({
                                url: window.location.href,
                                type: "post",
                                data: {
                                    "dcode": btoa('<?php echo $dcode; ?>'),
                                    "lbcode": btoa('<?php echo $tpcode; ?>'),
                                    "bank_account_id": btoa(bank_account_id),
                                    "ChequeNo": btoa(ChequeNo),
                                    "cmd": btoa(3)
                                },
                                success: function (data) {
                                    if (data != '') {
                                        var Result_Data = JSON.parse(data);
                                        if (Result_Data['STATUS'] != "SUCCESS") {
                                            $("#ChequeNo").val('');
                                            alert("Enter Valid Check Number");
                                        }
                                    }
                                },
                                error: function (xhe, error, status) {
                                    console.log(`errpr: ${error}`);
                                    console.log(`status: ${status}`);
                                },
                                dataType: 'html'
                            });
                        }
                    }
                });
                $(document).on('click', "#btn_save", function (event) {
                    try {
                         if ($("#ChequeNo").val().length == '') {
                            throw {
                                msg: "Enter Cheque No",
                                foc: "#ChequeNo"
                            }
                        }
                        if ($("#remark").val().length == '') {
                            throw {
                                msg: "Enter Remarks",
                                foc: "#remark"
                            }
                        }
                        return true;
                    } catch (e) {
                        event.preventDefault();
                        alert(e.msg);
                        $('#' + Current_Field_id).show();
                        $(e.foc).focus();
                    }
                });
            });
        </script>

        <div class="container mt-3">
            <form action="CancelBankCheque.php" method="post" class="my-3" enctype="multipart/form-data" autocomplete="off">
                <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>"
                    name="<?php echo htmlentities($this->page_token); ?>"
                    value="<?php echo htmlentities($this->token($this->page_token)); ?>" />
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
                                    <th align="left" scope="col" colspan="12">Bank Cheque Cancel</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2" align="left" style="width:50%;"><span DisplayLabelID="">Bank Account Number</span></td>
                                    <td colspan="2">
                                        <select id="bank_account_id" name="bank_account_id" class="form-control form-control-sm w-50">
                                            <option value="">Select Account Number</option>
                                            <?php
                                                $query='SELECT account_no,bankaccount_id FROM accounts_master.t_bank_account WHERE dcode=:dcode AND lbcode=:lbcode AND del_flag is NULL;';
                                                $res=$this->prepare($query,[":dcode"=>$dcode,":lbcode"=>$lbcode],2);
                                                foreach($res as $row){
                                                ?>
                                                    <option value="<?=$row['bankaccount_id']?>"><?=$row['account_no']?></option>
                                                <?php
                                                } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="left" style="width:50%;"><span DisplayLabelID="553"><?php echo htmlentities('Account Head Code and Name'); ?></span>
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
                                    <td colspan="2" align="left" style="width:50%;"><span DisplayLabelID="">Cheque No </span>
                                    </td>
                                    <td>
                                        <input type="text" id="ChequeNo" name="ChequeNo" class="form-control form-control-sm w-50" value="" placeholder="Enter Canceled Cheque Number" maxlength="6"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2" align="left" style="width:50%;"><span DisplayLabelID="">Remarks</span>
                                    </td>
                                    <td>
                                        <textarea id="remark" name="remark" rows="4" cols="50"
                                            class="form-control w-50 form-control-sm" placeholder="Enter Remarks"></textarea>
                                    </td>
                                </tr>
                                <tr align="center">
                                    <td scope="row" colspan="5" align="center" class="text-center">
                                        <input type="submit" id="btn_save" name="btn_save" value="Cancel"
                                            class="btn btn-md text-white font-weight-bold btn-danger" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                         <table class="table table-bordered m-0 p-0 table-striped tndtp_report_table" id="dataTable2">
                    <thead>
                        <tr>
                            <th scope="col">S.No.</th>
                            <th scope="col">Bank Name</th>
                            <th scope="col">Branch Name</th>
                            <th scope="col">Account Head Code and Name</th>
                            <th scope="col">Account Number</th>
                            <th scope="col">Cheque Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sel_details = '
    SELECT 
        b.bank_name_en AS bank_name,
        br.bankbranch_name AS branch_name,
        cheque_number,
		account_no,account_head_name_en,old_account_head_code,new_account_head_code 
    FROM  accounts_master.t_bank_cheque_leaves cl
    LEFT JOIN accounts_master.m_bank b 
        ON cl.bank_id = b.bank_id
    LEFT JOIN accounts_master.m_bankbranch br 
        ON br.bank_id = cl.bank_id 
        AND br.bankbranch_id = cl.bank_branch_id
		left join accounts_master.t_bank_cheque_leaves_details c
		on c.bank_cheque_id = cl.bank_cheque_id
		left join
		(SELECT bankaccount_id,bankbranch_id,accounthead_id,account_no  FROM accounts_master.t_bank_account 
                WHERE dcode=:dcode AND lbcode=:lbcode AND del_flag is NULL )d
				on d.bankaccount_id=c.bank_account_id
                left join 
                (select account_head_id,old_account_head_code,account_head_name_en ,new_account_head_code 
                from accounts_master.m_account_head where del_flag is NULL)e
                on d.accounthead_id=e.account_head_id      
    WHERE 
        cl.dcode = :dcode 
        AND cl.lbcode = :lbcode  
        AND cl.del_flag IS NULL
        AND br.lbcode=:lbcode
        AND br.dcode=:dcode
';
                        $sel_details_res = $this->prepare($sel_details, array(":dcode" => $dcode, ":lbcode" => $lbcode), 2);
                        if (0 < count($sel_details_res)) {
                            $sl_no = 1;
                            foreach ($sel_details_res as $sel_details_key => $sel_details_row) {
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $sl_no++; ?></td>
                                    <td class="text-center"><?php echo htmlentities($sel_details_row['bank_name']); ?></td>
                                    <td class="text-center"><?php echo htmlentities($sel_details_row['branch_name']); ?></td>
                                    <td class="text-center">
                                        <?php echo htmlentities($sel_details_row['old_account_head_code'] . '-' . $sel_details_row['account_head_name_en'] .'('. $sel_details_row['new_account_head_code'].')'); ?>
                                    </td>
                                    <td class="text-center"><?php echo htmlentities($sel_details_row['account_no']); ?></td>
                                    <td class="text-center"><?php echo htmlentities($sel_details_row['cheque_number']); ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="5" class="text-center text-danger">Record Not Found</td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
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
        $this->Template($this->getCurrentUserTemplate() != "" ? $this->getCurrentUserTemplate() : "Template1", "Bank Cheque Cancel", $ob_output_main_contents, array(), array('page_id' => 12));
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
        if (($this->getCurrentStateCode() != '')) {
            $statecode = $this->getCurrentStateCode();
            $statecode_Validation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $statecode,
                    'Field_Name' => 'statecode',
                    'Field_Max_length'=>'2',
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
                    'Field_Length'=>'6',
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
        if (isset($save_data['ChequeNo'])) {
            $ChequeNo = $save_data['ChequeNo'];
            $ChequeNoValidation = $this->Field_Validation(
                array(
                    'Field_Type' => 'number',
                    'Field_Value' => $ChequeNo,
                    'Field_Name' => 'ChequeNo',
                    'Field_Length' => '6',
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
        }else{
            $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "ChequeNo",
                    "MESSAGE" => "Enter Cheque Number"
                ), $save_data));
                exit;
        }

        if (isset($save_data['remark'])) {
            $remark = $save_data['remark'];
        }else{
            $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "ChequeNo",
                    "MESSAGE" => "Enter Remark"
                ), $save_data));
                exit;
        }
        if (isset($save_data['bank_account_id'])) {
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
                    "FIELD_NAME" => "ChequeNo",
                    "MESSAGE" => "Select Bank Account"
                ), $save_data));
                exit;
        }

        $getCurrentUser = $this->getCurrentUser();
        $getIpAddress = $this->getIpAddress();
        $Result_Message = "Cheque Removed SuccessFully";
        $this->beginTransaction();
        $save_query = "select accounts_master.sp_cheque_cancel_details(:cheque_no, :dcode, :lbcode,:remark,:ins_username, :ins_ipaddress,:bank_account_id)";

        $res1 = $this->prepare($save_query, array(
            ":cheque_no" =>  $ChequeNo,
            ":dcode" =>  $dcode,
            ":lbcode" =>  $lbcode,
            ":remark" => $remark,
            ":ins_username" => $getCurrentUser,
            ":ins_ipaddress" => $getIpAddress,
            ":bank_account_id" => $Bank_Account_Id
        ), 4);
        $status_details = $res1["sp_cheque_cancel_details"];
        $json_res = json_decode($status_details);
        if ($json_res->STATUS === "SUCCESS") {
            $this->commit();
            $this->main_content(array(
                "STATUS" => "SUCCESS",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => $Result_Message
            ));
            exit;
        } else {
            $this->rollBack();
            $this->main_content(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Cheque leaf does not exists"
            ));
            exit;
        }
    }
}

$CancelBankCheque = new CancelBankCheque();

if (!isset($_POST['cmd'])) {
    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        $CancelBankCheque->data_save(array_merge($_POST, $_GET));
    } else {
        $CancelBankCheque->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
    }
} else if (isset($_POST['cmd'])) {
    $cmd = base64_decode($_POST['cmd']);
    if($cmd==1)
    {
        $bank_account_id=base64_decode($_POST['bank_account_id']);
        $lbcode=$CancelBankCheque->getCurrentLocalBodyCode();
        $dcode=$CancelBankCheque->getCurrentDistrictCode();
        $query='select a.bank_id, a.bankbranch_id, account_no, bank_name_en, bank_name_ta, bankbranch_name as bankbranch_name_en, bankbranch_name_ll as bankbranch_name_ta, old_account_head_code, new_account_head_code, account_head_name_en, account_head_name_ta from 
(select * from accounts_master.t_bank_account where bankaccount_id=:bankaccount_id and del_flag is null and dcode=:dcode and lbcode=:lbcode ) a left join 
(select * from accounts_master.m_account_head )d on a.accounthead_id=d.account_head_id left join
(select * from accounts_master.m_bank where del_flag is null)b on a.bank_id=b.bank_id left join
(select * from accounts_master.m_bankbranch where del_flag is null)c on a.bank_id=c.bank_id and a.bankbranch_id=c.bankbranch_id ';
        $res=$CancelBankCheque->prepare($query,[':bankaccount_id'=>$bank_account_id,":dcode"=>$dcode,":lbcode"=>$lbcode],4);
        echo json_encode($res);
        exit;
    }
    if ($cmd == 3) {
        $dcode = base64_decode($_POST['dcode']);
        $lbcode = base64_decode($_POST['lbcode']);
        if(isset($_POST['ChequeNo']) && $_POST['ChequeNo']!=''){
            $ChequeNo = base64_decode($_POST['ChequeNo']);
            $Cheque_Validation = $CancelBankCheque->Field_Validation(
            array(
                'Field_Type' => 'number',
                'Field_Value' => $ChequeNo,
                'Field_Name' => 'ChequeNo',
                'Field_Max_length'=>'6',
                'Field_Label_Name' => ' ChequeNo ',
                )
            );
            if ($Cheque_Validation['Status'] == "Error") {
                echo json_encode(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "NumberOfLeaves",
                    "MESSAGE" => $Cheque_Validation['Message']
                ));
                exit;
            }
        }else{
            echo json_encode(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "NumberOfLeaves",
                "MESSAGE" => 'Missing Number Of Leaves'
            ));
            exit;
        }  
        if(isset($_POST['bank_account_id']) && $_POST['bank_account_id']!=''){
            $bank_account_id = base64_decode($_POST['bank_account_id']);
            $bank_account_id_Validation = $CancelBankCheque->Field_Validation(
            array(
                'Field_Type' => 'number',
                'Field_Value' => $bank_account_id,
                'Field_Name' => 'bank_account_id',
                'Field_Max_length'=>'6',
                'Field_Label_Name' => ' Bank Account Number ',
                )
            );
            if ($bank_account_id_Validation['Status'] == "Error") {
                echo json_encode(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "bank_account_id",
                    "MESSAGE" => $bank_account_id_Validation['Message']
                ));
                exit;
            }
        }else{
            echo json_encode(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "bank_account_id",
                "MESSAGE" => 'Select Bank Account Number'
            ));
            exit;
        }         
        $sel_bank_data = 'select * from accounts_master.t_bank_cheque_leaves where dcode=:dcode and lbcode=:lbcode and cheque_no=:cheque_no and issued=:issued;';
        $data_array_val = $CancelBankCheque->prepare($sel_bank_data, array(":dcode" => $dcode, ":lbcode" => $lbcode, ":cheque_no" => $ChequeNo, ":issued"=>'Y'), 7);
        if ($data_array_val["count"] > 0) {
            $Result['STATUS'] = 'SUCCESS';
        } else {
            $Result['STATUS'] = 'Error';
        }        
        echo json_encode($Result);
        exit;
    }
    if ($cmd == 2) {
        $dcode = $CancelBankCheque->getCurrentDistrictCode();
        $lbcode = $CancelBankCheque->getCurrentLocalBodyCode();
        $bank_account_id = base64_decode($_POST['bank_account_id']);
        $sel_details = '
     SELECT 
        b.bank_name_en AS bank_name,
        br.bankbranch_name AS branch_name,
        cheque_number,
		account_no,account_head_name_en,old_account_head_code,new_account_head_code 
    FROM  accounts_master.t_bank_cheque_leaves cl
    LEFT JOIN accounts_master.m_bank b 
        ON cl.bank_id = b.bank_id
    LEFT JOIN accounts_master.m_bankbranch br 
        ON br.bank_id = cl.bank_id 
        AND br.bankbranch_id = cl.bank_branch_id
		left join accounts_master.t_bank_cheque_leaves_details c
		on c.bank_cheque_id = cl.bank_cheque_id
		left join
		(SELECT bankaccount_id,bankbranch_id,accounthead_id,account_no  FROM accounts_master.t_bank_account 
                WHERE dcode=:dcode AND lbcode=:lbcode AND del_flag is NULL)d
				on d.bankaccount_id=c.bank_account_id
                left join 
                (select account_head_id,old_account_head_code,account_head_name_en ,new_account_head_code 
                from accounts_master.m_account_head where del_flag is NULL)e
                on d.accounthead_id=e.account_head_id      
    WHERE 
        cl.dcode = :dcode 
        AND cl.lbcode = :lbcode 
        AND cl.del_flag IS NULL
        AND c.bank_account_id=:bank_account_id
    ORDER BY cheque_number Asc
';
        $sel_details_res = $CancelBankCheque->prepare($sel_details, array(":dcode" => $dcode, ":lbcode" => $lbcode, ":bank_account_id" => $bank_account_id), 2);
        $data = [];
        $sl_no = 1;
        foreach ($sel_details_res as $sel_details_key => $sel_details_row) {
            $data[] = [
                "bank_name" => $sel_details_row['bank_name'],
                "branch_name" => $sel_details_row['branch_name'],
                "account_no" => $sel_details_row['account_no'],
                "account_head_name_en" => $sel_details_row['account_head_name_en'],
                "old_account_head_code" => $sel_details_row['old_account_head_code'],
                "new_account_head_code" => $sel_details_row["new_account_head_code"],
                "cheque_number" => $sel_details_row["cheque_number"],
            ];
        }        
        echo json_encode($data);
    }
}
?>