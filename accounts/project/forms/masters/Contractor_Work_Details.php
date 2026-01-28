<?php
require_once  '../../config/config.php';
class VoucherTypeDetails  extends ConfigClass
{
    public $page_token = "Contractor_Work_Details";
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
            $('#dataTable').DataTable(); // Initialize the DataTable
            $('#date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'yyyy-dd-mm',
                    minDate: '1970-01-01',
                    maxDate: new Date()
                });
                <?php if (!isset($post_data_array["del_id"])) { ?>
                    $(document).on('click', "#btn_save", function() {
                        try {

                            if ($("#name_of_work_en").val().trim().length == 0) {
                                throw {
                                    msg: "Enter Name of Work in English",
                                    foc: "#name_of_work_en"
                                }
                            }

                            if ($("#name_of_work_ta").val().trim().length == 0) {
                                throw {
                                    msg: "Enter Name of Work in Tamil",
                                    foc: "#name_of_work_ta"
                                }
                            }
                            if ($("#date").val().trim().length == 0) {
                                throw {
                                    msg: "Select date",
                                    foc: "#date"
                                }
                            }
                            if ($("#amount").val().trim().length == 0) {
                                throw {
                                    msg: "Enter Amount",
                                    foc: "#amount"
                                }
                            }
                            if ($("#scheme").val().trim().length == 0) {
                                throw {
                                    msg: "Select Scheme",
                                    foc: "#scheme"
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
                <?php }?>
           });
        </script>
 <?php
        if (isset($post_data_array["edit_id"]) || isset($post_data_array["del_id"])) {
            if (isset($post_data_array["edit_id"])) {
                $cwd_id = base64_decode($post_data_array["edit_id"]);

                $fundid_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $cwd_id,
                        'Field_Name' => 'edit_id',
                        'Field_Max_length' => '6',
                        'Field_Label_Name' => 'Edit ID',
                    )
                );

                if ($fundid_Validation['Status'] == "Error") {
                    echo 'Invalid Request';
                    exit;
                }
            } else if (isset($post_data_array["del_id"])) {
                $cwd_id = base64_decode($post_data_array["del_id"]);

                $fundid_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $cwd_id,
                        'Field_Name' => 'del_id',
                        'Field_Max_length' => '6',
                        'Field_Label_Name' => 'Delete ID',
                    )
                );

                if ($fundid_Validation['Status'] == "Error") {
                    echo 'Invalid Request';
                    exit;
                }
            }

            $sel_contractor_work_details = "SELECT 
                                                cwd.name_of_work_en,
                                                cwd.name_of_work_ta,
                                                cwd.amount,
                                                cwd.cwd_date,
                                                cwd.scheme_id,
                                                sch.scheme_name_".$lang_code_2d." AS scheme_name
                                            FROM 
                                                accounts_master.t_contractor_work_details AS cwd
                                            LEFT JOIN 
                                                master.m_scheme AS sch 
                                                    ON sch.scheme_seq_id = cwd.scheme_id
                                            WHERE  
                                                cwd.contractor_work_details_id = :cwd_id 
                                                AND cwd.lbcode = :lbcode 
                                                AND cwd.dcode = :dcode 
                                                AND cwd.fin_year = :fin_year 
                                                AND cwd.del_flag IS NULL 
                                                AND cwd.isactive = :isactive
                                            ";

            $data_array_val = $this->prepare($sel_contractor_work_details, array(":cwd_id" => $cwd_id,":isactive"=>1,":dcode"=>$this->getCurrentDistrictCode(),":lbcode"=>$this->getCurrentLocalBodyCode(),":fin_year"=>$this->getFinYear()), 4);
        }

        ?>
      <div class="container pt-3"> 
        <form action="" method="post" class="" enctype="multipart/form-data">
            <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>" name="<?php echo htmlentities($this->page_token); ?>" value="<?php echo htmlentities($this->token($this->page_token)); ?>">
                <?php
                    if (isset($post_data_array["STATUS"])) {
                        echo $this->ShowMessage($post_data_array["STATUS"], $post_data_array["MESSAGE"]);
                    }
                    ?>
                    <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                   <table class="table table-bordered m-0 p-0 tndtp_form_table">
                        <thead class="bg-th-form-dsg">
                            <tr>
                                <th align="center" scope="col" colspan="12">Contractor Work Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-left font-weight-bold"><span DisplayLabelID="">Name of Work in English</span></td>
                                <td>

                                        <?php if(isset($post_data_array["del_id"]) && $post_data_array["del_id"]>0)
                                        {
                                            if(isset($data_array_val['name_of_work_en']) && $data_array_val['name_of_work_en'] != '')
                                            {
                                            ?>
                                            
                                            <span><?php echo $data_array_val['name_of_work_en'] ?></span>
                                            
                                            <?php
                                            }
                                        }else{
                                                ?>

                                            <textarea id="name_of_work_en" name="name_of_work_en"
                                            class="form-control w-50 form-control-sm alpha_numeric_with_space_hiphen_brackets_dot" value=""></textarea>
                                        <span>Max 250 Characters</span>
                                        <script>
                                            $("#name_of_work_en").val('<?php echo ((isset($data_array_val['name_of_work_en']) && $data_array_val['name_of_work_en'] != '') ? $data_array_val['name_of_work_en'] : "") ?>');
                                        </script>
                                    <?php    
                                    }     
                                            ?>

                                        
                                    </td>
                            </tr>
                             <tr>
                                <td class="text-left font-weight-bold"><span DisplayLabelID="">Name of Work in Tamil</span></td>
                                <td>

                                        <?php 
                                        
                                        if(isset($post_data_array["del_id"]) && $post_data_array["del_id"]>0)
                                        {                                           
                                            if(isset($data_array_val['name_of_work_ta']) && $data_array_val['name_of_work_ta'] != '')
                                            {
                                            ?>                                            
                                            <span><?php echo $data_array_val['name_of_work_ta'] ?></span>
                                            
                                            <?php
                                            }
                                        }else{
                                                ?>

                                            <textarea id="name_of_work_ta" name="name_of_work_ta"
                                            class="form-control w-50 form-control-sm alphanum_tamil_comma_dot_slash" value=""></textarea>
                                        <span>Max 250 Characters</span>
                                        <script>
                                            $("#name_of_work_ta").val('<?php echo ((isset($data_array_val['name_of_work_ta']) && $data_array_val['name_of_work_ta'] != '') ? $data_array_val['name_of_work_ta'] : "") ?>');
                                        </script>
                                    <?php    
                                    }     
                                            ?>

                                        
                                    </td>
                            </tr>
                            <tr>
                                <td class="text-left font-weight-bold"><span DisplayLabelID="">Date</span></td>
                                <td>

                                    <?php
                                    if(isset($post_data_array["del_id"]) && $post_data_array["del_id"]>0 )
                                    {
                                        if(isset($data_array_val['cwd_date']) && $data_array_val['cwd_date'] != '')
                                        {
                                        ?>
                                        <span> <?php echo $data_array_val['cwd_date']; ?> </span>
                                        <?php
                                        }
                                    }
                                    else{?>

                                        <input type="text" id="date" name="date" class="form-control form-control-sm field_datepicker user_enter_date  w-50 date_yyyy_dd_mm" value="<?php echo htmlentities(isset($data_array_val['cwd_date'])?$data_array_val['cwd_date']:''); ?>"  placeholder="YYYY-MM-DD"/>

                                    <?php
                                    }
                                    ?>

                                    
                                </td>
                            </tr>
                             <tr>
                                <td  class="text-left font-weight-bold"><span DisplayLabelID="">Amount</span></td>
                                <td><?php
                                    if(isset($post_data_array["del_id"]) && $post_data_array["del_id"]>0 )
                                    {
                                        if(isset($data_array_val['amount']) && $data_array_val['amount'] != '')
                                        {
                                        ?>
                                        <span> <?php echo $data_array_val['amount']; ?> </span>
                                    <?php
                                        }
                                    }else{?>

                                        <input type="text" name="amount" id="amount" class="form-control form-control-sm w-50 number_field"  value="<?php if (isset($data_array_val['amount'])) { echo htmlentities($data_array_val['amount']); } ?>"/>

                                    <?php
                                    }
                                    ?>

                                     
                                </td>        
                            </tr>
                                    
                            </tr>
                            <tr>
                                <td class="text-left font-weight-bold"><span DisplayLabelID="186">Scheme</span></td>
                                <td>
                                    <?php
                                    if(isset($post_data_array["del_id"]) && $post_data_array["del_id"]>0 )
                                    {
                                        if(isset($data_array_val['scheme_name']) && $data_array_val['scheme_name'] != '')
                                        {
                                        ?>
                                        <span> <?php echo $data_array_val['scheme_name']; ?> </span>
                                    <?php
                                    }
                                    }else{?>
                                        

                                        <select id="scheme" name="scheme" class="form-control form-control-sm w-50">
                                        <option value="">Choose</option>
                                        <?php
                                        $sel_scheme = "SELECT scheme_seq_id,scheme_name_".$lang_code_2d." FROM accounts_master.m_scheme where del_flag is null;";
                                        $sel_scheme_res = $this->prepare($sel_scheme, array(), 2);
                                        foreach ($sel_scheme_res as $sel_scheme_res_key => $sel_scheme_res_row) {
                                        ?>
                                            <option value="<?php echo htmlentities($sel_scheme_res_row['scheme_seq_id']); ?>" >
                                                <?php echo htmlentities($sel_scheme_res_row['scheme_name_'.$lang_code_2d]); ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <script>
                                        $(document).ready(function(){
                                       $("#scheme").val(
                                            <?php if (isset($data_array_val['scheme_id'])) {
                                                    echo htmlentities($data_array_val['scheme_id']);
                                                } ?>
                                       )
                                        });
                                        
                                    </script>

                                    <?php
                                }
                                ?>
                                </td>
                            </tr>

                            <tr align="center">
                                <td scope="row" colspan="2" align="center" class="text-center"> 								
										<input type="submit" id="btn_save" name="btn_save" value="<?php echo htmlentities($post_data_array['mode_name']); ?>" class="btn btn-md text-white font-weight-bold <?php echo htmlentities($post_data_array['mode_class']); ?>" />  
                                        <input type="button" id="btn_reset" name="btn_reset" value="Cancel" class="btn btn-md text-white font-weight-bold btn-secondary" onclick="window.location='Contractor_Work_Details.php'" />      										
                                </td>                                
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
            <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-body" >

                    <div class="single-table">
                        <table class="table table-bordered text-center table-striped tndtp_report_table" id="dataTable">
                            <thead class="text-left">
                                <tr>
                                    <th scope="col"><span DisplayLabelID="311">S.No</span></th>
                                    <th scope="col"><span DisplayLabelID="186">Name of Work in English</span></th>
                                    <th scope="col"><span DisplayLabelID="186">Name of Work in Tamil</span></th>
                                    <th scope="col"><span DisplayLabelID="186">Amount</span></th>
                                    <th scope="col"><span DisplayLabelID="186">Scheme</span></th>
                                    <th scope="col"><span DisplayLabelID="354">Action</span></th>
                                </tr>
                            </thead>
                            <tbody id="tradedetails_data">
                                <?php
                                $sel_cwd_details="
                               SELECT 
                                cwd.contractor_work_details_id,
                                cwd.name_of_work_en,
                                cwd.name_of_work_ta,
                                cwd.amount,
                                sche.scheme
                            FROM 
                                accounts_master.t_contractor_work_details AS cwd
                            LEFT JOIN 
                                (
                                    SELECT 
                                        scheme_seq_id,
                                        scheme_name_{$lang_code_2d} AS scheme
                                    FROM 
                                        accounts_master.m_scheme
                                    WHERE 
                                        del_flag IS NULL
                                ) AS sche
                            ON 
                                sche.scheme_seq_id = cwd.scheme_id
                                WHERE cwd.isactive=:isactive AND cwd.lbcode=:lbcode AND cwd.dcode=:dcode AND cwd.fin_year=:fin_year AND cwd.del_flag IS NULL;
                                ";

                                $sel_cwd_details_res = $this->prepare($sel_cwd_details, array(":isactive" => 1,":lbcode"=>$this->getCurrentLocalBodyCode(),":dcode"=>$this->getCurrentDistrictCode(),":fin_year"=>$this->getFinYear()), 2);

                                if (count($sel_cwd_details_res) > 0) {
                                    foreach ($sel_cwd_details_res as $sel_cwd_details_key => $sel_cwd_details_row) {
                                ?>
                                        <tr>
                                            <td class="text-center"><?php echo htmlentities($sel_cwd_details_key + 1); ?></td>
                                           
                                            <td class="text-left">
                                                <?php echo htmlentities($sel_cwd_details_row['name_of_work_en']); ?>
                                            </td>
                                            <td class="text-left">
                                                <?php echo htmlentities($sel_cwd_details_row['name_of_work_ta']); ?>
                                            </td>
                                            <td class="text-left">
                                                <?php echo htmlentities($sel_cwd_details_row['amount']); ?>
                                            </td>
                                              <td class="text-left">
                                                <?php echo htmlentities($sel_cwd_details_row['scheme']); ?>
                                            </td>
                                            <td align="center"><a href="?edit_id=<?php echo htmlentities(base64_encode($sel_cwd_details_row['contractor_work_details_id'])); ?>" class="btn btn-warning btn-sm"><?php /* ?><i class="fa fa-pencil pr-1"
                                        aria-hidden="true"></i><?php */ ?>Edit</a>
                                                <a href="?del_id=<?php echo htmlentities(base64_encode($sel_cwd_details_row['contractor_work_details_id'])); ?>" class="btn btn-danger btn-sm">Delete</a>
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
        <div class="container pt-3"> 
        <?php

        // #############

        // PAGE CONTENT END

        // #############

        $ob_output_main_contents = ob_get_contents();
        ob_clean();
        $this->Template($this->getCurrentUserTemplate() != "" ? $this->getCurrentUserTemplate() : "Template1", "Voucher Type", $ob_output_main_contents, array(), array('page_id' => 12));
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
        $statecode = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();
        $edit_id = isset($save_data['edit_id']) ? base64_decode($save_data['edit_id']) : 0;
        $del_id = isset($save_data['del_id']) ? base64_decode($save_data['del_id']) : 0;

            if (isset($save_data['name_of_work_en']) && $save_data['name_of_work_en']!='') {
                $name_of_work_en = $save_data['name_of_work_en'];

                $name_of_work_en_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text_area',
                        'Field_Value' => $name_of_work_en,
                        'Field_Name' => 'name_of_work_en',
                        'Field_Max_length' => '250',
                        'Field_Label_Name' => 'Name of Work in English',
                    )
                );

                if ($name_of_work_en_Validation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "name_of_work_en",
                        "MESSAGE" => $name_of_work_en_Validation['Message']
                    ), $save_data));
                    exit;
                }
            }
            else{
                if($del_id == 0)
                {
                    $this->main_content(array_merge(array(
                            "STATUS" => "ERROR",
                            "STATUS_TYPE" => "FIELD",
                            "FIELD_NAME" => "name_of_work_en",
                            "MESSAGE" => "Enter Name of Work in English"
                        ), $save_data));
                        exit;
                }
                else{
                    $name_of_work_en=null;
                }

            }


            if (isset($save_data['name_of_work_ta']) && $save_data['name_of_work_ta']!='')                 
            {
                $name_of_work_ta = $save_data['name_of_work_ta'];

                // $name_of_work_ta_Validation = $this->Field_Validation(
                //     array(
                //         'Field_Type' => 'text_area_ta',
                //         'Field_Value' => $name_of_work_ta,
                //         'Field_Name' => 'name_of_work_ta',
                //         'Field_Max_length' => '250',
                //         'Field_Label_Name' => 'Name of Work in Tamil',
                //     )
                // );

                // if ($name_of_work_ta_Validation['Status'] == "Error") {
                //     $this->main_content(array_merge(array(
                //         "STATUS" => "ERROR",
                //         "STATUS_TYPE" => "FIELD",
                //         "FIELD_NAME" => "name_of_work_ta",
                //         "MESSAGE" => $name_of_work_ta_Validation['Message']
                //     ), $save_data));
                //     exit;
                // }
            }
            else{
                if($del_id == 0)
                {
                    $this->main_content(array_merge(array(
                            "STATUS" => "ERROR",
                            "STATUS_TYPE" => "FIELD",
                            "FIELD_NAME" => "name_of_work_ta",
                            "MESSAGE" => "Enter Name of Work in Tamil"
                        ), $save_data));
                        exit;
                }
                else{
                    $name_of_work_ta=null;
                }

            }
             if (isset($save_data['amount']) && $save_data['amount']!='') {
                $amount = $save_data['amount'];
                $amount_of_work_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'float',
                        'Field_Value' => $amount,
                        'Field_Name' => 'amount',
                        'Field_Max_length' => '16',
                        'Field_Label_Name' => 'amount',
                    )
                );

                if ($amount_of_work_Validation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "amount",
                        "MESSAGE" => $amount_of_work_Validation['Message']
                    ), $save_data));
                    exit;
                }
             }
             else{
                if($del_id==0)
                {

                    $this->main_content(array_merge(array(
                            "STATUS" => "ERROR",
                            "STATUS_TYPE" => "FIELD",
                            "FIELD_NAME" => "amount",
                            "MESSAGE" => "Enter Amount"
                        ), $save_data));
                        exit;
                }
                else{
                    $amount=null;
                }
            }

            

             if (isset($save_data['scheme']) && $save_data['scheme']!='') {
                $scheme = $save_data['scheme'];
                $scheme_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $scheme,
                        'Field_Name' => 'scheme',
                        'Field_Label_Name' => 'scheme',
                    )
                );

                if ($scheme_Validation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "scheme",
                        "MESSAGE" => $scheme_Validation['Message']
                    ), $save_data));
                    exit;
                }
             }else{
                if($del_id==0)
                {
                    $this->main_content(array_merge(array(
                            "STATUS" => "ERROR",
                            "STATUS_TYPE" => "FIELD",
                            "FIELD_NAME" => "scheme",
                            "MESSAGE" => "Select Scheme"
                        ), $save_data));
                        exit;
                }
                else{
                    $scheme=null;
                }
            }
            if(isset($save_data['date']) && $save_data['date']!='')
            {
                $date = $save_data['date'];
                list($date_completion, $month_completion, $year_completion) = explode('-', $date);
                $date = $year_completion . '-' . $month_completion . '-' . $date_completion;
                $date_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'date',
                        'Field_Value' => $save_data['date'],
                        'Field_Name' => 'date',
                        'Field_Format' => 'dd-mm-yyyy',
                        'Field_Label_Name' => 'date',
                    )
                );

                if ($date_Validation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "date",
                        
                        "MESSAGE" => $date_Validation['Message']
                    ), $save_data));
                    exit;
                }
            }
            else{
               if($del_id==0)
                {
                    $this->main_content(array_merge(array(
                            "STATUS" => "ERROR",
                            "STATUS_TYPE" => "FIELD",
                            "FIELD_NAME" => "date",
                            "MESSAGE" => "Select date"
                        ), $save_data));
                        exit;
                }
                else{
                    $date=null;
                }
            }
	   $Result_Message = "Data Saved SuccessFully"; 

        if ($edit_id > 0) {
            $Result_Message = "Data Updated SuccessFully";
        } else if ($del_id > 0) {
            $Result_Message = "Data Deleted SuccessFully";
        }

        $this->beginTransaction();

        $CwdFunction = "accounts_master.sp_contractor_work_details";
        $getCurrentUser = $this->getCurrentUser();
        $getIpAddress = $this->getIpAddress();
       
        $save_query = "select " . $CwdFunction . "(:date,:account_head_id,:cjv_no,:name_of_work_en,:name_of_work_ta,:amount,:scheme_id,:dcode,:lbcode,:finyear,:getCurrentUser,now()::timestamp without time zone,:getIpAddress,:edit_id,:del_id);";  
			 $res = $this->prepare($save_query,array(
                ":date"=>$date,
             ":account_head_id"=>null,
             ":cjv_no"=>null,
             ":name_of_work_en"=>$name_of_work_en,
             ":name_of_work_ta"=>$name_of_work_ta,
             ":amount"=>$amount,
            ":scheme_id"=>$scheme,
            ":dcode"=>$dcode,
            ":lbcode"=>$lbcode,
            ":finyear"=>$this->getFinYear(),
             ":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>$edit_id, ":del_id" => $del_id),4);          
        if (!isset($res->errorInfo)) {
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
                "MESSAGE" => "Data Save Failed Due To Duplicate Entry"
            ));
            exit;
        }
    
		}
}

$VoucherTypeDetails = new VoucherTypeDetails();

if (!isset($_POST['cmd'])) {

    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        $VoucherTypeDetails->data_save(array_merge($_POST, $_GET));
    } else {
        $VoucherTypeDetails->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
    }
}else if (isset($_POST['cmd'])) {
    $cmd = base64_decode($_POST['cmd']);
    if ($cmd == 3) {

         $accounts = base64_decode($_POST['accounts']);
        ?>
        <option value="" DisplayLabelID="255">Choose </option>
        <?php
        $sel_street_details = "SELECT account_head_id,old_account_head_code as account_code,account_head_name_en FROM accounts_master.m_account_head where account_type_head_id=:account ORDER BY account_code DESC";
        $sel_street_details_res =$VoucherTypeDetails->prepare($sel_street_details,array(":account"=>$accounts),2);
        foreach ($sel_street_details_res as $sel_street_details_key => $sel_street_details_row) {
        ?>


         <option value="<?php echo htmlentities($sel_street_details_row['account_head_id']); ?>">
                        <?php echo htmlentities($sel_street_details_row['account_code']) . ' - ' . htmlentities($sel_street_details_row['account_head_name_en']); ?>
                    </option>
        <?php
        }

        exit;
    }
}    
?>