<?php
//session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once  '../../config/config.php';
class Voucher_Master_Form  extends ConfigClass
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

    function delChalan(chalan_no)
    {   
        let confirm_flag=confirm('are you sure want to delete this?');
        if(confirm_flag)
        {
            $.ajax({
                url:"List_Triplicate_Chalan.php",
                data:{cmd:btoa(2),chalan_no:btoa(chalan_no)},
                type:"post",
                dataType:"json",
                success:function(res){
                    //const res=JSON.parse(data)
                    if(res.STATUS=="ERROR")
                    {
                        console.log(res.MSG);
                    }
                    else
                    {
                        $("#redirect_chalan").trigger("click");
                    }
                }
            });
        }
    }
$(document).ready(function() {


    $('#rc_date').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'yyyy-mm-dd',
        minDate: new Date('01-01-1970'),
        maxDate: new Date()
    });
    $(document).on('click', "#redirect_chalan", function(event) {

        var Current_Field_id = $(this).attr('id');

        try {

            if ($("#rc_date").val().length == '') {
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
        const chalan_date = $("#rc_date").val();
        $.ajax({
            url: "List_Triplicate_Chalan.php",
            type: "post",
            data: {
                "chalan_date": btoa(chalan_date),
                "cmd": btoa(1),
            },
            success: function(response) {
                $('#loading-image').hide();
                //console.log(`data from chalan_date change : ${response}`)
                const res = JSON.parse(response);

                if (res.STATUS === "ERROR") {
                    $('#rc_date').val('');
                     $("#display_chalan_list tbody").html('<tr><td td colspan="3" style="text-align:center">No chalan is available on this date and financial year</td></tr>');
                } else {
                    let table_rows = "";
                    let redirect_url="<?=$this->siteData()->website_url?>"+"project/forms/masters/Edit_Triplicate_Chalan.php";
                    res.data.forEach((chalan) => {
                        let curr =
                            `<tr>
  <td id="chalan_no_${chalan.id}">${chalan.chalan_no}</td>
  <td id="amount_${chalan.id}">${chalan.amount}</td>
  <td id="remitter_name_${chalan.id}">${chalan.remitter_name}</td>
  <td>
    <a href="${redirect_url}?query_chalan_no=${btoa(chalan.chalan_no)}" class="btn btn-success" style="margin-right:5px;margin-left:25px;">Edit</a>
    <button class="btn btn-danger" onclick="delChalan(${chalan.chalan_no})">Delete</button>
  </td>
</tr>`


                        table_rows += curr;
                    });
                    $("#display_chalan_list tbody").html(table_rows);
                    //insert table_rows
                }

            }
        });


    });

});
</script>

<style type="text/css">

    #display_chalan_list th,
    #display_chalan_list td,{
       text-align:center;
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
                        <th align="center" scope="col" colspan="12">List Triplicate Chalans</th>
                    </tr>


                </thead>

                <tbody>
                    <tr>
                        <td align="center" style="width:50%;"><span DisplayLabelID="186">Chalan Date</span></td>
                        <td>
                            <input type="text" id="rc_date" name="rc_date" value="<?php echo isset($post_data_array['rc_date'])?$post_data_array['rc_date']:''?>"
                                class="form-control form-control-sm user_enter_date w-50" />
                        </td>
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
    <div class="card-body pl-5 pr-5">
        <table id="display_chalan_list" class="table table-bordered m-0 p-0 tndtp_form_table">
            <thead>
                <th>Chalon No</th>
                <th>Amount</th>
                <th>Remitters Name</th>
                <th>Actions</th>                
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
//print_r(get_class_methods($propertyassessment));die();
if (!isset($_POST['cmd'])) {

    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        // print_r(array_merge($_POST, $_GET));exit();
        $propertyassessment->data_save(array_merge($_POST, $_GET));
    } else {
        $propertyassessment->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
    }
}

else{
    $cmd=base64_decode($_POST["cmd"]);
    //echo json_encode($_POST); 
    if($cmd=="1")
    {
        $lbcode=$propertyassessment->getCurrentLocalBodyCode ();
        $dcode=$propertyassessment->getCurrentDistrictCode ();
        $chalan_date=base64_decode($_POST['chalan_date']);
        $fin_year=$propertyassessment->getFinYear();
        $query="SELECT 
        chalan_details_id AS id,
        tc_serial_no,
        total_amount,
        remitter_name
    FROM accounts_master.t_triplicate_chalan_details
    WHERE 
        del_flag IS NULL
        AND fin_year = :fin_year
        AND lbcode = :lbcode
        AND dcode = :dcode
        AND chalan_date = :chalan_date
    and brv_id is null
    ORDER BY tc_serial_no;";
        $res=$propertyassessment->prepare($query,[
            ":fin_year"=>$fin_year,
            ":chalan_date"=>$chalan_date,
            ":lbcode"=>$lbcode,
            ":dcode"=>$dcode
        ],2);
        $data=["data"=>[]];
        if(count($res)>0){
            foreach($res as $row)
            {
                $data["data"][]=[
                    "id"=>$row["id"],
                    "chalan_no"=>$row["tc_serial_no"],
                    "amount"=>$row["total_amount"],
                    "remitter_name"=>$row["remitter_name"]
                ];
            }
            $data["STATUS"]="SUCCESS";
        }
        else
        {
            $data["STATUS"]="ERROR";
        }
        echo json_encode($data);

        exit;
    }
    if($cmd=="2")
    {
        $chalan_no=base64_decode($_POST['chalan_no']);
        //delete triplicate chalan 

        try{            

                        $account_head_balance=new Account_head_balance();
                        $account_head_balance->update_triplicate_chalan_head_amount($chalan_no,true);
                        $query="UPDATE accounts_master.t_triplicate_chalan_details
                        SET   del_username=:user_name, del_upd_date=now(), del_ipaddress=:ip_address, del_flag='Y'
                        WHERE chalan_no=:chalan_no and fin_year=:fin_year and lbcode=:lbcode and dcode=:dcode";
                    $params=[":user_name"=>$propertyassessment->getCurrentUser(),
                        ":ip_address"=>$propertyassessment->getIpAddress(),
                        ":chalan_no"=>$chalan_no,
                        ":fin_year"=>$propertyassessment->getFinYear(),
                        ":lbcode"=>$propertyassessment->getCurrentLocalBodyCode(),
                        ":dcode"=>$propertyassessment->getCurrentDistrictCode()
                    ];
                    
                    $propertyassessment->prepare($query,$params,4);

                    $query="UPDATE accounts_master.t_triplicate_accounthead_breakup
                    SET   del_username=:user_name, del_upd_date=now(), del_ipaddress=:ip_address, del_flag='Y'
                    WHERE triplicate_chalan_no=:chalan_no and fin_year=:fin_year and lbcode=:lbcode and dcode=:dcode";
                
                    $propertyassessment->prepare($query,$params,4);
        }
        catch(PDOException $e)
        {
            echo json_encode(["STATUS"=>"ERROR","MSG"=>$e->getMessage()]);
            exit;    
        }
        echo json_encode(["STATUS"=>"SUCCESS"]);
        exit;

    
    
    }
}
    
?>