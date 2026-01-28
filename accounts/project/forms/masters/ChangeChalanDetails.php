<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once  '../../config/config.php';

class Trade_Entry_Form  extends ConfigClass
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
            $(document).ready(function() {

                $('#date').datepicker({
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


                $('#cash_coll_date').datepicker({
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
                        $('#pay_mode_dd').hide();
                        $('#pay_mode_ecs').hide();
                        $('#pay_mode_cheque').show();
                        $('#bank_name_row').show();

                    } 
                    else if ($(this).val() == 'DD') {
                        $('#pay_mode_cheque').hide();
                        $('#pay_mode_ecs').hide();
                        $('#pay_mode_dd').show();
                        $('#bank_name_row').show();

                    }
                    else if ($(this).val() == 'ECS') {
                        $('#pay_mode_dd').hide();
                        $('#pay_mode_cheque').hide();
                        $('#pay_mode_ecs').show();
                        $('#bank_name_row').show();

                    }
                    else
                    {
                        $('#pay_mode_dd').hide();
                        $('#pay_mode_cheque').hide();
                        $('#pay_mode_ecs').hide();
                        $('#bank_name_row').hide();
                    }
                });



                $('input[name=cash_from_type]').click(function() {
                    if (this.id == "collection") {
                        $("#cash_coll_date_row").show();
                    } else {
                        $("#cash_coll_date_row").hide();
                    }
                });

                /* 
                $(document).on('change', '#fin_year', function() {

                    var fin_year = $("#fin_year").val();
                    $.ajax({
                        url: "Trade_Entry_Form.php",
                        type: "post",
                        data: {
                            "fin_year": btoa(fin_year),
                            "cmd": btoa(2)
                        },
                        success: function(data) {
                            if (data != '') {
                                var Result_Data = JSON.parse(data);
                                $('#tradedetails_data').html(Result_Data['DATA']);
                            }
                        },
                        dataType: 'html'
                    });

                }); */




                <?php if (!isset($post_data_array['del_id'])) { ?>

                    $(document).on('click', "#btn_save", function() {

                        var Current_Field_id = $(this).attr('id');
                        $('#' + Current_Field_id).hide();
                        try {

                            if ($("#licencetypeid").val().length == '') {
                                throw {
                                    msg: "Select Licence Type",
                                    foc: "#licencetypeid"
                                }
                            }

                            if ($("#trade_name_en").val().length == '') {
                                throw {
                                    msg: "Enter Trade Name in English",
                                    foc: "#trade_name_en"
                                }
                            }

                            if ($("#trade_name_ta").val().length == '') {
                                throw {
                                    msg: "Enter Trade Name in Tamil",
                                    foc: "#trade_name_ta"
                                }
                            }

                            if ($("#fin_year").val().length == '') {
                                throw {
                                    msg: "Select Financial Year",
                                    foc: "#fin_year"
                                }
                            }

                            if ($("#traderate").val().length == '') {
                                throw {
                                    msg: "Enter Trade Rate",
                                    foc: "#traderate"
                                }
                            }

                            if ($("#lb_tradecode").val().length == '') {
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

        <style type="text/css">
            .hidden_field_element_value {
                display: none;
            }

            .gj-datepicker {
                width: 80%;
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

            $sel_exemption_cat_data_upd_details = "SELECT tradedetails_id,licencetypeid,description_en,description_ta,finyear,traderate,lb_tradecode,trade_id,isactive FROM tradelicense.t_tl_tradedetails WHERE  tradedetails_id=:exemption_category_data_id";
            $data_array_val = $this->prepare($sel_exemption_cat_data_upd_details, array(":exemption_category_data_id" => $exemption_category_data_id), 4);
        }

        ?>
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



                    <table class="table table-bordered m-0 p-0 tndtp_form_table">
                        <thead class="bg-th-form-dsg">
                            <tr>
                                <th align="center" scope="col" colspan="12">Adjust Triplicate Chalan(TPCF 1)</th>
                            </tr>


                        </thead>

                        <tbody>
                            <tr>
                                <td colspan="2" class="text-left font-weight-bold"><span DisplayLabelID="483">Remittance Chalan Serial No</span></td>

                                <td colspan="2" scope="col">
                                    <input type="text" id="swm_amount" name="swm_amount" class="form-control w-50 form-control-sm" />
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2" class="text-left font-weight-bold"><span DisplayLabelID="483">Remittance Chalan Date</span></td>

                                <td colspan="2" scope="col">
                                    <input type="text" id="date" name="date" value="" class="form-control form-control-sm user_enter_date" />
                                </td>
                            </tr>

                            <tr>
                                <td width="25%" class="text-left font-weight-bold"><span DisplayLabelID="483">Payment Mode</span></td>
                                <td width="25%" scope="col">
                                    <select id="pay_mode" name="pay_mode" class="form-control form-control-sm ">
                                        <option value="">Choose</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="DD">DD</option>
                                        <option value="ECS">ECS</option>
                                    </select>
                                </td>
                                <td width="25%" class="text-left font-weight-bold"><span DisplayLabelID="483"></span></td>
                                <td width="25%" scope="col"></td>
                            </tr>

                            <tr id="pay_mode_cheque" style="display: none;">
                                <td width="25%" class="text-left font-weight-bold"><span DisplayLabelID="483">Cheque No</span></td>
                                <td width="25%" scope="col">
                                    <input type="text" id="cheque_no" name="cheque_no" class="form-control form-control-sm" />
                                </td>
                                <td width="25%" class="text-left font-weight-bold"><span DisplayLabelID="484">Cheque Date</span></td>
                                <td width="25%" scope="col">
                                    <input type="text" id="cheque_date" name="cheque_date" value="" class="form-control form-control-sm user_enter_date" />
                                </td>
                            </tr>

                            <tr id="pay_mode_dd" style="display: none;">
                                <td width="25%" class="text-left font-weight-bold"><span DisplayLabelID="483">DD No</span></td>
                                <td width="25%" scope="col">
                                    <input type="text" id="dd_no" name="dd_no" class="form-control form-control-sm" />
                                </td>
                                <td width="25%" class="text-left font-weight-bold"><span DisplayLabelID="484">DD Date</span></td>
                                <td width="25%" scope="col">
                                    <input type="text" id="dd_date" name="dd_date" value="" class="form-control form-control-sm user_enter_date" />
                                </td>
                            </tr>

                            <tr id="pay_mode_ecs" style="display: none;">
                                <td width="25%" class="text-left font-weight-bold"><span DisplayLabelID="483">ECS No</span></td>
                                <td width="25%" scope="col">
                                    <input type="text" id="ecs_no" name="ecs_no" class="form-control form-control-sm" />
                                </td>
                                <td width="25%" class="text-left font-weight-bold"><span DisplayLabelID="484">ECS Date</span></td>
                                <td width="25%" scope="col">
                                    <input type="text" id="ecs_date" name="ecs_date" value="" class="form-control form-control-sm user_enter_date" />
                                </td>
                            </tr>

                            <tr id="bank_name_row" style="display: none;">
                                <td colspan="1" class="text-left font-weight-bold"><span DisplayLabelID="483">Bank Name</span></td>

                                <td colspan="3" scope="col">
                                    <input type="text" id="bank_name" name="bank_name" maxlength="500" value="" class="form-control  form-control-sm Tax_Form_English_Ownername_Property_Tax first_letter_uppercase" />
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2" class="text-left font-weight-bold"><span DisplayLabelID="483">Bank Code </span></td>
                                <td colspan="2" scope="col">
                                    <select id="fin_year" name="fin_year" class="form-control form-control-sm w-50">
                                        <option value="">Choose</option>
                                        <?php
                                        $isactive = '1';
                                        $sel_fin_year_id = "SELECT fin_yearid,fin_year FROM master.m_fin_year WHERE isactive = :isactive AND del_flag IS NULL ORDER BY fin_year DESC";

                                        $sel_fin_yearid_res = $this->prepare($sel_fin_year_id, array(":isactive" => $isactive), 2);

                                        foreach ($sel_fin_yearid_res as $sel_fin_yearid_key => $sel_fin_yearid_row) {

                                        ?>
                                            <option value="<?php echo htmlentities($sel_fin_yearid_row['fin_year']); ?>"> <?php echo htmlentities($sel_fin_yearid_row['fin_year']); ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <script>
                                        document.getElementById('fin_year').value =
                                            '<?php if (isset($data_array_val['finyear'])) {
                                                    echo htmlentities($data_array_val['finyear']);
                                                } ?>';
                                    </script>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2" class="text-left font-weight-bold"><span DisplayLabelID="483">Name and Address of Remitter</span></td>
                                <td colspan="2" scope="col">
                                    <textarea id="remark" name="remark" rows="4" cols="50" class="form-control w-50 form-control-sm"></textarea>
                                    <span>Max 250 Characters</span>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2" class="text-left font-weight-bold"><span DisplayLabelID="483">Cash From</span></td>
                                <td colspan="2" scope="col">

                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="accounts" name="cash_from_type" value="1" class="custom-control-input">
                                        <label class="custom-control-label" for="accounts"><span DisplayLabelID="371">Accounts</span></label>
                                    </div> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="collection" name="cash_from_type" value="0" class="custom-control-input">
                                        <label class="custom-control-label" for="collection"><span DisplayLabelID="372">Collection</span></label>
                                    </div>

                                </td>
                            </tr>

                            <tr id="cash_coll_date_row" style="display: none;">
                                <td colspan="2" class="text-left font-weight-bold"><span DisplayLabelID="483">Cash Collection Date</span></td>
                                <td colspan="2" scope="col">
                                    <input type="text" id="cash_coll_date" name="cash_coll_date" value="" class="form-control form-control-sm user_enter_date" />
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2" class="text-left font-weight-bold"><span DisplayLabelID="483">Account Code</span></td>
                                <td colspan="2" scope="col">
                                    <select id="fin_year" name="fin_year" class="form-control form-control-sm w-50">
                                        <option value="">Choose</option>
                                        <?php
                                        $isactive = '1';
                                        $sel_fin_year_id = "SELECT fin_yearid,fin_year FROM master.m_fin_year WHERE isactive = :isactive AND del_flag IS NULL ORDER BY fin_year DESC";

                                        $sel_fin_yearid_res = $this->prepare($sel_fin_year_id, array(":isactive" => $isactive), 2);

                                        foreach ($sel_fin_yearid_res as $sel_fin_yearid_key => $sel_fin_yearid_row) {

                                        ?>
                                            <option value="<?php echo htmlentities($sel_fin_yearid_row['fin_year']); ?>" <?php //echo htmlentities($sel); 
                                                                                                                            ?>>
                                                <?php echo htmlentities($sel_fin_yearid_row['fin_year']); ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <script>
                                        document.getElementById('fin_year').value =
                                            '<?php if (isset($data_array_val['finyear'])) {
                                                    echo htmlentities($data_array_val['finyear']);
                                                } ?>';
                                    </script>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2" class="text-left font-weight-bold"><span DisplayLabelID="483">Account Head</span></td>
                                <td colspan="2" scope="col">
                                    <textarea id="remark" name="remark" rows="4" cols="50" class="form-control w-50 form-control-sm"></textarea>
                                </td>
                            </tr>



                            <tr>
                                <td colspan="2" class="text-left font-weight-bold"><span DisplayLabelID="483">Amount</span></td>
                                <td colspan="2" scope="col">
                                    <input type="text" id="swm_amount" name="swm_amount" class="form-control form-control-sm w-50" />
                                    <br>
                                    <input type="submit" id="btn_save" name="btn_save" value="Add" class="btn btn-md text-white font-weight-bold  btn-success" />
                                    <input type="button" id="btn_reset" name="btn_reset" value="Cancel" class="btn btn-md text-white font-weight-bold btn-secondary" onclick="window.location='Trade_Entry_Form.php'" />
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2" class="text-left font-weight-bold"><span DisplayLabelID="483">Total</span></td>
                                <td colspan="2" scope="col">
                                    <input type="text" id="swm_amount" name="swm_amount" class="form-control form-control-sm w-50" />
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2" class="text-left font-weight-bold"><span DisplayLabelID="483">Rupees (In Words)</span></td>
                                <td colspan="2" scope="col">
                                    <textarea id="remark" name="remark" rows="4" cols="50" class="form-control w-50 form-control-sm"></textarea>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2" class="text-left font-weight-bold"><span DisplayLabelID="483">Narration</span></td>
                                <td colspan="2" scope="col">
                                    <textarea id="remark" name="remark" rows="4" cols="50" class="form-control w-50 form-control-sm"></textarea>
                                    <span>Max 250 Characters</span>
                                </td>
                            </tr>

                            <tr>
                                <td align="center" style="text-align: center !important;" colspan="2">
                                    <span DisplayLabelID="484">Print</span>
                                </td>
                                <td align="left" colspan="2">
                                    <input type="checkbox" id="taxtypeid_1" name="taxtypeid[]" value="" checked />
                                </td>
                            </tr>

                            <tr>
                                <td colspan="4" align="center">
                                    <center>
                                        <input type="submit" id="btn_save" name="btn_save" value="Save" class="btn btn-md text-white font-weight-bold  btn-success" />

                                    </center>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

            <!--  <div class="card">
        <div class="card-body" style=" background-color:#e1f9ff;border:1px solid;border-color:#94f0f1">

            <div class="single-table">
                <table class="table table-bordered text-center table-striped tndtp_report_table" id="dataTable2">
                    <thead class="text-left">

                        <tr>
                            <th scope="col"><span DisplayLabelID="311">S.No</span></th>
                            <th scope="col"><span DisplayLabelID="329">Licence Type</span></th>
                            <th scope="col"><span DisplayLabelID="186">Financial Year</span></th>
                            <th scope="col"><span DisplayLabelID="671">Trade Name</span></th>
                            <th scope="col"><span DisplayLabelID="388">Trade Rate</span></th>
                            <th scope="col"><span DisplayLabelID="345">Status</span></th>
                            <th scope="col"><span DisplayLabelID="354">Action</span></th>
                        </tr>
                    </thead>
                    <tbody id="tradedetails_data">
                        <?php
                        $sel_tradedetails_details = "SELECT a.tradedetails_id as edit_id,b.traders_license_type_name,a.isactive,c.fin_year,a.description_ta,a.traderate FROM 
                                (SELECT tradedetails_id,licencetypeid,description_ta,finyear,isactive,traderate FROM tradelicense.t_tl_tradedetails WHERE statecode=:state_code AND dcode=:dcode AND lbcode=:tpcode AND del_flag IS NULL)a 
                                LEFT JOIN (SELECT traders_license_type_id,traders_license_type_name FROM tradelicense.m_t_tl_trader_license_type WHERE isactive=:isactive AND del_flag IS NULL)b ON a.licencetypeid::INTEGER=b.traders_license_type_id 
                                LEFT JOIN
                                (SELECT fin_yearid,fin_year FROM master.m_fin_year WHERE isactive = :isactive AND del_flag IS NULL)c
                                ON a.finyear=c.fin_year order by a.tradedetails_id desc";

                        $sel_tradedetails_details_res = $this->prepare($sel_tradedetails_details, array(":state_code" => $state_code, ":dcode" => $dcode, ":tpcode" => $tpcode, ":isactive" => 1), 2);
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
                            <td class="text-left">
                                <?php echo htmlentities($sel_tradedetails_details_row['traderate']); ?></td>

                            <td align="center">
                                <?php if ($sel_tradedetails_details_row['isactive'] == 1) {
                                    echo 'Active';
                                } else {
                                    echo 'Deactive';
                                } ?>
                            </td>
                            <td align="center"><a
                                    href="?edit_id=<?php echo htmlentities(base64_encode($sel_tradedetails_details_row['edit_id'])); ?>"
                                    class="btn btn-warning btn-sm"><?php /* ?><i class="fa fa-pencil pr-1"
                                        aria-hidden="true"></i><?php */ ?>Edit</a>
                                <a href="?del_id=<?php echo htmlentities(base64_encode($sel_tradedetails_details_row['edit_id'])); ?>"
                                    class="btn btn-danger btn-sm">Delete</a>
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
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> -->
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


            if (isset($save_data['licencetypeid'])) {
                $licencetypeid = $save_data['licencetypeid'];

                $licencetypeidValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $licencetypeid,
                        'Field_Name' => 'licencetypeid',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => 'Invalied Instalment Type',
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

            if (isset($save_data['trade_name_en'])) {
                $trade_name_en = $save_data['trade_name_en'];

                $trade_name_enValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $trade_name_en,
                        'Field_Name' => 'trade_name_en',
                        'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Trade Name in English',
                    )
                );

                if ($trade_name_enValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "trade_name_en",
                        "MESSAGE" => $trade_name_enValidation['Message']
                    ), $save_data));
                    exit;
                }
            }


            if (isset($save_data['trade_name_ta'])) {
                $trade_name_ta = $save_data['trade_name_ta'];

                $trade_name_taValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'text',
                        'Field_Value' => $trade_name_ta,
                        'Field_Name' => 'trade_name_ta',
                        'Field_Max_length' => '60',
                        'Field_Label_Name' => 'Trade Name in Tamil',
                    )
                );

                /* if ($trade_name_taValidation['Status'] == "Error") 
                {
                    $this->main_content(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "trade_name_ta",
                    "MESSAGE" => $trade_name_taValidation['Message']
                    ), $save_data));
                    exit;			
                } */
            }


            if (isset($save_data['fin_year'])) {
                $fin_year = $save_data['fin_year'];

                $fin_year_Validation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'fin_year',
                        'Field_Value' => $fin_year,
                        'Field_Name' => 'fin_year',
                        // 'Field_Max_length'=>'10',
                        'Field_Label_Name' => 'Financial Year',
                    )
                );

                if ($fin_year_Validation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "fin_year",
                        "MESSAGE" => $fin_year_Validation['Message']
                    ), $save_data));
                    exit;
                }
            }



            if (isset($save_data['traderate'])) {
                $traderate = $save_data['traderate'];

                $traderateValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $traderate,
                        'Field_Name' => 'traderate',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => 'Trade Rate',
                    )
                );

                if ($traderateValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "traderate",
                        "MESSAGE" => $traderateValidation['Message']
                    ), $save_data));
                    exit;
                }
            }


            if (isset($save_data['lb_tradecode'])) {
                $lb_tradecode = $save_data['lb_tradecode'];

                $lb_tradecodeValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $lb_tradecode,
                        'Field_Name' => 'lb_tradecode',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => 'Trade Code',
                    )
                );

                if ($lb_tradecodeValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "lb_tradecode",
                        "MESSAGE" => $lb_tradecodeValidation['Message']
                    ), $save_data));
                    exit;
                }
            }




            // $sel_trade_code = "SELECT lb_tradecode FROM tradelicense.m_trade_list where trade_id=:trade_name";
            // $sel_trade_code_res = $this->prepare($sel_trade_code, array(":trade_name" => $trade_name), 4);

            // $lb_tradecode = $sel_trade_code_res['lb_tradecode'];

            // $sel_trade = "SELECT description_en, description_ta FROM tradelicense.m_trade_list where trade_id=:trade_name";
            // $sel_trade_res = $this->prepare($sel_trade, array(":trade_name" => $trade_name), 4);

            // $edscription_en = $sel_trade_res['description_en'];
            // $edscription_ta = $sel_trade_res['description_ta'];


            if (isset($save_data['isactive'])) {
                $isactive = $save_data['isactive'];

                $isactiveValidation = $this->Field_Validation(
                    array(
                        'Field_Type' => 'number',
                        'Field_Value' => $isactive,
                        'Field_Name' => 'isactive',
                        //'Field_Max_length'=>'30',
                        'Field_Label_Name' => 'Invalid Instalment Type',
                    )
                );

                if ($isactiveValidation['Status'] == "Error") {
                    $this->main_content(array_merge(array(
                        "STATUS" => "ERROR",
                        "STATUS_TYPE" => "FIELD",
                        "FIELD_NAME" => "isactive",
                        "MESSAGE" => $isactiveValidation['Message']
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

        $pp_assessment_initiation = "tradelicense.t_tl_tradedetails";
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

$propertyassessment = new Trade_Entry_Form();

if (!isset($_POST['cmd'])) {

    if (isset($_POST['btn_save']) && $_POST['btn_save'] != '') {
        // print_r(array_merge($_POST, $_GET));exit();
        $propertyassessment->data_save(array_merge($_POST, $_GET));
    } else {
        $propertyassessment->main_content(array_merge(array("mode_name" => "Save", "mode_class" => "btn-primary"), $_GET));
    }
} else if (isset($_POST['cmd'])) {
    $cmd = base64_decode($_POST['cmd']);

    if ($cmd == 2) {
        $fin_year = base64_decode($_POST['fin_year']);

        $Result['STATUS'] = 'SUCCESS';
        $Result['DATA'] = $propertyassessment->Trade_Fin_Year_Range_Details($_POST);
        echo json_encode($Result);
        exit;
    }
}
?>