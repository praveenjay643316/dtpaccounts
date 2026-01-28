<?php
require_once '../../config/config.php';
require_once '../../library/account_head_balance.php';

class Voucher_Master_Form extends ConfigClass
{

    public $page_token = "Trade_Entry_Form";
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
            function displayVouchers(voucher_date,voucher_type) {
                let fin_year="<?=$this->getFinYear()?>";
                $.ajax({
                    url: "List_Journal_Vouchers.php",
                    type: "post",
                    data: {
                        "voucher_date": btoa(voucher_date),
                        "financial_year": btoa(fin_year),
                        "voucher_type": btoa(voucher_type),
                        "cmd": btoa(1),
                    },
                    success: function (response) {
                        $('#loading-image').hide();
                        //console.log(`data from chalan_date change : ${response}`)
                        const res = JSON.parse(response);

                        if (res.STATUS === "ERROR") {
                            // alert('No chalan is available on this date and financial year');
                            // $('#rc_date').val('');
                            // $("#display_chalan_list tbody").html('');

                            $("#display_chalan_list tbody").html(
                                "<tr><td colspan='3' style='text-align:center'>No chalan is available on this date and financial year</td></tr>"
                            )

                        } else {
                            let table_rows = "";
                            let editpagename = "";
                            let receiptpagename="";
                            switch (voucher_type) {
                                case "1":
                                    editpagename = "Edit_Contract_Journal_Vouchers.php";
                                    receiptpagename="Contractor_Journal_Voucher_Receipt.php";
                                    receipt_query_field="cjvno";
                                    break;
                                case "2":
                                    editpagename = "Edit_Expenses_Journal_Vouchers.php";
                                    receiptpagename="Expense_Journal_Voucher_Reciept.php";
                                    receipt_query_field="ejvno";

                                    break;
                                case "3":
                                    editpagename = "Edit_General_Journal_Vouchers.php";
                                    receiptpagename="General_Journal_Voucher_Reciept.php";
                                    receipt_query_field="gjvno";

                                    break;
                                case "4":
                                    editpagename = "Edit_Purchase_Journal_Vouchers.php";
                                    receiptpagename="Purchase_Journal_Voucher_Reciept.php";
                                    receipt_query_field="pjvno";

                            }
                            let edit_redirect_url = "<?=$this->siteData()->website_url ?>" +
                                "project/forms/masters/" + editpagename;
                                let receipt_redirect_url="<?=$this->siteData()->website_url ?>" +
                                "project/forms/masters/" + receiptpagename;
                            res.data.forEach((chalan) => {
                                let curr =
                                    `<tr>
  <td id="chalan_no_${chalan.id}">${chalan.chalan_no}</td>
  <td id="amount_${chalan.id}">${chalan.amount}</td>
  <td>
    <a href="${edit_redirect_url}?id=${btoa(chalan.id)}" class="btn btn-success" style="margin-right:5px;margin-left:25px;">Edit</a>
    <a href="${receipt_redirect_url}?${receipt_query_field}=${btoa(chalan.id)}" class="btn btn-success" style="margin-right:5px;margin-left:25px;">View</a>
    
  </td>
</tr>`
// <button class="btn btn-danger" onclick="delChalan(${chalan.chalan_no},'${voucher_date}','${voucher_type}')">Delete</button>


                                table_rows += curr;
                            });
                            $("#display_chalan_list tbody").html(table_rows);
                            //insert table_rows
                        }

                    }
                });

            }

            function delChalan(voucher_no, voucher_date, voucher_type) {
                let fin_year="<?=$this->getFinYear()?>";
                let confirm_flag=confirm('are you sure want to delete this?');
                if(confirm_flag)
                {
                    $.ajax({
                        url: "List_Journal_Vouchers.php",
                        data: {
                            cmd: btoa(2),
                            voucher_date: btoa(voucher_date),
                            voucher_no: btoa(voucher_no),
                            voucher_type: btoa(voucher_type)
                        },
                        type: "post",
                        dataType: "json",
                        success: function (res) {
                            //const res=JSON.parse(data);
                            if (res.STATUS == "ERROR") {
                                console.log(res.MSG);
                            } else {
                                displayVouchers(voucher_date,voucher_type);
                            }
                        }
                    });
                }
            }
            $(document).ready(function () {


                $('#rc_date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'yyyy-mm-dd',
                    minDate: new Date('01-01-1970'),
                    maxDate: new Date()
                });




                $(document).on('click', "#redirect_chalan", function (event) {

                    var Current_Field_id = $(this).attr('id');
                    
                    let voucher_type = $("#voucher_type").val();
                    let voucher_date = $("#rc_date").val();

                    try {
                        if (voucher_type.length == '') {
                            throw ({
                                msg: "Select Voucher Type",
                                foc: "#voucher_type"
                            });
                        }
                        if (voucher_date.length == '') {
                            throw ({
                                msg: "Select Chalan Date",
                                foc: "#chalan_date"
                            });
                        }
                    } catch (e) {
                        //alert(e);
                        alert(e.msg);
                        $('#' + Current_Field_id).show();
                        $(e.foc).focus();
                        //event.preventDefault();
                        return false;
                    }

                    displayVouchers(voucher_date,voucher_type);
                });

            });
        </script>

        <style type="text/css">
            #display_chalan_list th,
            #display_chalan_list td,
            {
            text-align: center;
            }

            /*
        .hidden_field_element_value {
            display: none;
        }

        .gj-datepicker {
            width: 80%;
        }
            */
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

            $sel_exemption_cat_data_upd_details = "SELECT voucher_master_id,voucher_type_id,date,chalan_no,remarks FROM accounts_master.voucher_master WHERE  voucher_master_id=:exemption_category_data_id";
            $data_array_val = $this->prepare($sel_exemption_cat_data_upd_details, array(":exemption_category_data_id" => $exemption_category_data_id), 4);
            // var_dump($data_array_val);exit;
        }

        ?>
        <form action="" method="post" class="" enctype="multipart/form-data">
            <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>"
                name="<?php echo htmlentities($this->page_token); ?>"
                value="<?php echo htmlentities($this->token($this->page_token)); ?>">
                <div class="container">
                <div class="card">
                <div class="card-body pl-5 pr-5">
                    <?php
                    if (isset($post_data_array["STATUS"])) {
                        echo $this->ShowMessage($post_data_array["STATUS"], $post_data_array["MESSAGE"]);
                        header("refresh: 3; url=Voucher_Master.php");
                    }
                    ?>



                    <table class="table table-bordered m-0 p-0 tndtp_form_table">
                        <thead class="bg-th-form-dsg">
                            <tr>
                                <th align="center" scope="col" colspan="12">Journal Vouchers</th>
                            </tr>


                        </thead>

                        <tbody>
                            
                            <tr>
                                <td align="center" style="width:50%;"><span DisplayLabelID="186">Voucher Type</span></td>
                                <td>
                                    <select name="voucher_type" id="voucher_type" required>
                                        <option value="">-- Select Voucher Type --</option>
                                        <?php 
                                        $query="SELECT voucher_id,voucher_type_$lang_code_2d FROM accounts_master.m_voucher_type
WHERE del_flag is NULL ORDER BY voucher_id ASC ";
$res=$this->prepare($query,[],2);
foreach($res as $row)
{?>
    <option value="<?=$row['voucher_id']?>"><?=$row["voucher_type_$lang_code_2d"]?></option>
<?php
}

?>
                                    </select>

                                </td>
                                <?php
                                if(isset($post_data_array['voucher_type']))
                                    {?>
                                        <script>
                                            $(document).ready(function(){
                                                $("#voucher_type").val($post_data_array['voucher_type']);
                                            });
                                        </script>
                                    <?php }
                                    ?>
                            </tr>
                            <tr>
                                <td align="center" style="width:50%;"><span DisplayLabelID="186">Chalan Date</span></td>
                                <td>
                                    <input type="text" id="rc_date" name="rc_date" value=""
                                        class="form-control form-control-sm user_enter_date w-50"/>
                                </td>
                                <?php
                                if(isset($post_data_array['rc_date']))
                                    {?>
                                        <script>
                                            $(document).ready(function(){
                                                $("#rc_date").val($post_data_array['rc_date']);
                                            });
                                        </script>
                                    <?php }
                                    ?>
                            </tr>



                            <tr>
                                <td colspan="4" align="center">
                                    <center>
                                        <!-- <a href="" id="redirect_chalan" name="redirect_chalan" class="btn btn-success"
                                    target="_blank">Show</a> -->
                                        <!-- <a href="" id="redirect_chalan" name="redirect_chalan" class="btn btn-success">Show</a> -->
                                        <input type="button" id="redirect_chalan" class="btn btn-success" value="Show"></input>
                                    </center>
                                </td>

                            </tr>
                        </tbody>
                    </table>



                </div>
                </div>
            </div>


            </div>



        </form>
        <div class="container">
        <div class="card">
            <div class="card-body" style="width:100%;margin-left:auto;margin-right:auto;">
                <table id="display_chalan_list" class="table table-bordered m-0 p-0 tndtp_form_table">
                    <thead>
                        <th style="width:20%;text-align:center;">Chalan No</th>
                        <th style="width:20%;text-align:center;">Amount</th>
                        <th style="width:40%;text-align:center;">Actions</th>
                        <thead>
                        <tbody>

                        </tbody>
                </table>
            </div>
        </div>
        </div>
        <?php

        // #############

        // PAGE CONTENT END

        // #############

        $ob_output_main_contents = ob_get_contents();
        ob_clean();
        $this->Template($this->getCurrentUserTemplate() != "" ? $this->getCurrentUserTemplate() : "Template1", "Property Tax - New Assessment", $ob_output_main_contents, array(), array('page_id' => 12));
    }

    public function data_save($save_data)
    {

    }
}

$propertyassessment = new Voucher_Master_Form();

if (!isset($_POST['cmd'])) {

    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        // print_r(array_merge($_POST, $_GET));exit();
        $propertyassessment->data_save(array_merge($_POST, $_GET));
    } else {
        $propertyassessment->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
    }
} else {
    $cmd = base64_decode($_POST["cmd"]);
    //echo json_encode($_POST); 
    $voucher_date = base64_decode($_POST['voucher_date']);
    $voucher_type = base64_decode($_POST['voucher_type']);


    switch ($voucher_type) {

        case "1":
            $voucher_type_account_head_balance_id=1;
            $id_field = "cjv_id";
            $chalan_no_field = "cjv_no";
            $date_field = "cjv_date";
            $voucher_table = "t_cj_voucher";
            $voucher_breakup_table = "t_cj_voucher_breakup";
            $breakup_serial_no_field = "cjv_serial_no";
            break;
        case "2":
            $voucher_type_account_head_balance_id=2;
            $id_field = "ejv_id";
            $chalan_no_field = "ejv_chalan_no";
            $date_field = "ejv_date";
            $voucher_table = "t_ej_voucher";
            $voucher_breakup_table = "t_ej_voucher_breakup";
            $breakup_serial_no_field = "ejv_serial_no";
            break;
        case "3":
            $voucher_type_account_head_balance_id=3;
            $id_field = "gjv_id";
            $chalan_no_field = "gjv_chalan_no";
            $date_field = "gjv_date";
            $voucher_table = "t_gj_voucher";
            $voucher_breakup_table = "t_gj_voucher_breakup";
            $breakup_serial_no_field = "gjv_serial_no";
            break;
        case "4":
            $voucher_type_account_head_balance_id=20;
            $id_field = "pjv_id";
            $chalan_no_field = "pjv_chalan_no";
            $date_field = "pjv_date";
            $voucher_table = "t_pj_voucher";
            $voucher_breakup_table = "t_pt_voucher_breakup";
            $breakup_serial_no_field = "pjv_serial_no";
            break;
        
        



    }
    $fin_year = $propertyassessment->getFinYear();
    if ($cmd == "1") {
            //print_r($voucher_type);die;
        if($voucher_type == 7)
        {
            $query="SELECT 
    chalan_details_id AS id,
    tc_chalan_no as chalan_no,
    total_amount,
    remitter_name
FROM accounts_master.t_triplicate_chalan_details
WHERE 
    del_flag IS NULL
    AND fin_year = :fin_year
    AND lbcode = :lbcode
    AND dcode = :dcode
    AND chalan_date = :chalan_date
    AND brv_id is null
ORDER BY tc_chalan_no;";
        $res=$propertyassessment->prepare($query,[
            ":fin_year"=>$fin_year,
            ":chalan_date"=>$voucher_date,
            ":lbcode"=>$propertyassessment->getCurrentLocalBodyCode(),
            ":dcode"=>$propertyassessment->getCurrentDistrictCode()
        ],2);
        }
        else{
            $query = "select " . $id_field . " as id," . $chalan_no_field . " as chalan_no , total_amount from accounts_master." . $voucher_table . " where del_flag is null and fin_year=:fin_year and " . "$date_field" . "=:voucher_date and lbcode=:lbcode and dcode=:dcode AND bpv_id is null order by chalan_no;";
        $res = $propertyassessment->prepare($query, [
            ":fin_year" => $fin_year,
            ":voucher_date" => $voucher_date,
            ":lbcode" => $propertyassessment->getCurrentLocalBodyCode(),
            ":dcode" => $propertyassessment->getCurrentDistrictCode()
            
        ], 2);
       
        }
        
        $data = ["data" => []];
        if (count($res) > 0) {
            foreach ($res as $row) {
                $data["data"][] = [
                    "id" => $row["id"],
                    "chalan_no" => $row["chalan_no"],
                    "amount" => $row["total_amount"]
                ];
            }
            $data["STATUS"] = "SUCCESS";
        } else {
            $data["STATUS"] = "ERROR";
        }
        echo json_encode($data);
    }
    if ($cmd == "2") {
        $voucher_no = base64_decode($_POST['voucher_no']);
        //delete triplicate chalan 

        try {
            $account_head_balance=new Account_head_balance();
            $account_head_balance->update_voucher_head_amount($voucher_type_account_head_balance_id,$voucher_no,True);

            $query = "UPDATE accounts_master." . $voucher_table . "
                    SET   del_username=:user_name, del_upd_date=now(), del_ipaddress=:ip_address, del_flag='Y'
                    WHERE " . $chalan_no_field . "=:voucher_no and lbcode=:lbcode and dcode=:dcode and fin_year=:fin_year";

            $params = [
                ":user_name" => $propertyassessment->getCurrentUser(),
                ":ip_address" => $propertyassessment->getIpAddress(),
                ":voucher_no" => $voucher_no,
                ":fin_year"=>$propertyassessment->getFinYear(),
                ":lbcode" => $propertyassessment->getCurrentLocalBodyCode(),
                ":dcode" => $propertyassessment->getCurrentDistrictCode()
            ];

            $propertyassessment->prepare($query, $params, 4);

            $query = "UPDATE accounts_master." . $voucher_breakup_table . "
                    SET   del_username=:user_name, del_upd_date=now(), del_ipaddress=:ip_address, del_flag='Y'
                    WHERE " . $breakup_serial_no_field . "=:voucher_no and lbcode=:lbcode and dcode=:dcode and fin_year=:fin_year";

            $propertyassessment->prepare($query, $params, 4);
        } catch (PDOException $e) {
            echo json_encode(["STATUS" => "ERROR", "MSG" => $e->getMessage()]);
            exit;
        }
        echo json_encode(["STATUS" => "SUCCESS"]);
        exit;



    }
}

?>