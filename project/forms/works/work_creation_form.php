<?php
require_once __DIR__ . '/../../config/config.php';

class scheme_work_creation extends ConfigClass
{
    function __construct()
    {
    }

    public function main_form($data_array = array())
    {
	   ob_start();
       if(!isset($data_array['mode_name'])){
			$data_array['mode_class']='btn-success';
			$data_array['mode_icon']='fa fa-floppy-o';
			$data_array['mode_name']='Save';
		}	
		$site_data = $this->siteData();
		$role_code=$_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'];
		$state_code=$this->getCurrentStateCode()!=null?$this->getCurrentStateCode():33;
		$dcode=$this->getCurrentDistrictCode()!=''?$this->getCurrentDistrictCode():'';
		$lbcode=$this->getCurrentLocalBodyCode()!=''?$this->getCurrentLocalBodyCode():'';
        // #############

        // PAGE CONTENT START

        // #############

        // PLACE YOUR CODE HERE
        ?>
<script type="text/javascript">
$(document).ready(function() {
    $(document).on('click', '#btn_save', function() {
        var Current_Field_id = $(this).attr('id');
        $('#' + Current_Field_id).hide();
        try {
            if ($("#dcode").val().length == '') {
                throw {
                    msg: "Select District",
                    foc: "#dcode"
                }
            }
            if ($("#lbcode").val().length == '') {
                throw {
                    msg: "Select Town Panchayat",
                    foc: "#lbcode"
                }
            }
            if ($("#cmb_schemegrp").val().length == '') {
                throw {
                    msg: "Select Scheme Group",
                    foc: "#cmb_schemegrp"
                }
            }
            if ($("#cmb_scheme").val().length == '') {
                throw {
                    msg: "Select Scheme ",
                    foc: "#cmb_scheme"
                }
            }
            if ($("#fin_year").val().length == '') {
                throw {
                    msg: "Select Financial Year ",
                    foc: "#fin_year"
                }
            }
            if ($("#cmb_wrkgrp").val().length == '') {
                throw {
                    msg: "Select Work Group",
                    foc: "#cmb_wrkgrp"
                }
            }
            if ($("#cmb_wrk_type").val().length == '') {
                throw {
                    msg: "Select Work Type",
                    foc: "#cmb_wrk_type"
                }
            }
            if ($("#work_name").val().length == '') {
                throw {
                    msg: "Select Work Name",
                    foc: "#work_name"
                }
            }
            // if ($("#cmb_catgry_of_rep_wk").val().length == '') {
            //     throw {
            //         msg: "Select Category of Repair Works",
            //         foc: "#cmb_catgry_of_rep_wk"
            //     }
            // }
            if ($("#txt_agmtno").val().length == '') {
                throw {
                    msg: "Enter Agreement / Work Order Number",
                    foc: "#txt_agmtno"
                }
            }
            if ($("#txt_agmtdate").val().length == '') {
                throw {
                    msg: "Enter Agreement / Work Order Date",
                    foc: "#txt_agmtdate"
                }
            }
            if ($("#cmb_aggrp").val().length == '') {
                throw {
                    msg: "Select Agency Group",
                    foc: "#cmb_aggrp"
                }
            }
            if ($("#cmb_agn").val().length == '') {
                throw {
                    msg: "Select Agency",
                    foc: "#cmb_agn"
                }
            }
            if ($("#ward").val().length == '') {
                throw {
                    msg: "Enter Ward",
                    foc: "#ward"
                }
            }
            if ($("input[name=work_undertaken_street_y_n]:checked").length == 0) {
                throw {
                    msg: "Choose Wheather the work is undertaken in a street(Yes/No)",
                    foc: "#work_undertaken_street_y"
                }
            } else {
                if ($("input[name=work_undertaken_street_y_n]:checked").val() == 'Y') {
                    if ($("#street_code").val().length == '') {
                        throw {
                            msg: "Enter Street",
                            foc: "#street_code"
                        }
                    }
                } else {
                    if ($("#location").val().length == '') {
                        throw {
                            msg: "Enter Location",
                            foc: "#location"
                        }
                    }
                }
            }
            if ($("#txt_asval").val().length == '') {
                throw {
                    msg: "Enter Administrative Sanction Value",
                    foc: "#txt_asval"
                }
            }
            if ($("#cmb_asby").val().length == '') {
                throw {
                    msg: "Select Administrative Sanction By",
                    foc: "#cmb_asby"
                }
            }
            if ($("#txt_asdate").val().length == '') {
                throw {
                    msg: "Select Administrative Sanction Date",
                    foc: "#txt_asdate"
                }
            }
            if ($("#txt_asno").val().length == '') {
                throw {
                    msg: "Enter Administrative Sanction Number",
                    foc: "#txt_asno"
                }
            }
            if ($("#txt_asno").val().length == '') {
                throw {
                    msg: "Enter Administrative Sanction Number",
                    foc: "#txt_asno"
                }
            }
            if ($("#txt_tsval").val().length == '') {
                throw {
                    msg: "Enter Technical Sanction Value",
                    foc: "#txt_tsval"
                }
            }
            if ($("#cmb_tsby").val().length == '') {
                throw {
                    msg: "Select Technical Sanction By",
                    foc: "#cmb_tsby"
                }
            }
            if ($("#txt_tsdate").val().length == '') {
                throw {
                    msg: "Select Technical Sanction Date",
                    foc: "#txt_tsdate"
                }
            }
            if ($("#txt_tsno").val().length == '') {
                throw {
                    msg: "Enter Technical Sanction Number",
                    foc: "#txt_tsno"
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
    $(document).on('change', '#dcode', function() {
        var dcode = $(this).val();
        if (dcode != '') {
            $.ajax({
                url: "work_creation_ajax.php",
                data: {
                    "dcode": btoa(dcode),
                    "cmd": btoa(6)
                },
                type: "post",
                success: function(data) {
                    if (data != '') {
                        $('#lbcode').html(data);
                    }
                },
                dataType: 'html'
            });
        } else {
            alert('Select District');
        }
        return true;
    });
    $(document).on('change', '#lbcode', function() {
        var dcode = $("#dcode").val();
        var lbcode = $(this).val();
        if (dcode != '') {
            $.ajax({
                url: "work_creation_ajax.php",
                data: {
                    "dcode": btoa(dcode),
                    "lbcode": btoa(lbcode),
                    "cmd": btoa(12)
                },
                type: "post",
                success: function(data) {
                    if (data != '') {
                        $('#ward').html(data);
                    }
                },
                dataType: 'html'
            });
        } else {
            alert('Select Town Panchayat');
        }
        return true;
    });
    $(document).on('change', '#ward', function() {
        var dcode = $("#dcode").val();
        var lbcode = $("#lbcode").val();
        var ward = $(this).val();
        if (dcode != '') {
            $.ajax({
                url: "work_creation_ajax.php",
                data: {
                    "dcode": btoa(dcode),
                    "lbcode": btoa(lbcode),
                    "ward": btoa(ward),
                    "cmd": btoa(13)
                },
                type: "post",
                success: function(data) {
                    if (data != '') {
                        $('#street_code').html(data);
                    }
                },
                dataType: 'html'
            });
        } else {
            alert('Select Ward');
        }
        return true;
    });
    $(document).on('change', '#cmb_schemegrp', function() {
        var schemegrp = $(this).val();
        if (schemegrp != '') {
            $.ajax({
                url: "work_creation_ajax.php",
                data: {
                    "schemegrp": btoa(schemegrp),
                    "cmd": btoa(1)
                },
                type: "post",
                success: function(data) {
                    if (data != '') {
                        var Result_Data = JSON.parse(data);
                        if (Result_Data['STATUS'] == 'ERROR') {
                            alert(Result_Data['MESSAGE']);
                            $('#cmb_scheme').val('');
                        } else {
                            $('#cmb_scheme').html(Result_Data['DATA']);
                        }
                    }
                },
                dataType: 'html'
            });
        }
        return true;
    });
    $(document).on('change', '#cmb_scheme', function() {
        if ($('#cmb_schemegrp').val() != '') {
            var sgrpid = $('#cmb_schemegrp').val();
        } else {
            alert('Select Scheme Group');
        }
        if ($(this).val() != '') {
            var scheme = $(this).val();
        } else {
            alert('Select Scheme');
        }
        var cmd = 5;
        if (sgrpid != '' && scheme != '') {
            $.ajax({
                url: "work_creation_ajax.php",
                data: {
                    "schemegrp": btoa(sgrpid),
                    "scheme": btoa(scheme),
                    "cmd": btoa(2)
                },
                type: "post",
                success: function(data) {
                    if (data != '') {
                        var Result_Data = JSON.parse(data);
                        if (Result_Data['STATUS'] == 'ERROR') {
                            alert(Result_Data['MESSAGE']);
                        } else {
                            $('#fin_year').html(Result_Data['FIN_YEAR']);
                            $('#cmb_wrkgrp').html(Result_Data['WORK_GROUP']);
                            $('#cmb_aggrp').html(Result_Data['AGENCY_GROUP']);
                        }
                    }
                },
                dataType: 'html'
            });
            return true;
        }
    });
    $("#cmb_wrkgrp").change(function() {
        $("#tr_housing_info_lbl").hide();
        $("#tr_allotment_amount").hide();
        if ($("#cmb_schemegrp").val() != '') {
            var sgrpid = $("#cmb_schemegrp").val();
        } else {
            alert('Select Scheme Group');
        }
        if ($("#cmb_scheme").val() != '') {
            var scheme = $("#cmb_scheme").val();
        } else {
            alert('Select Scheme');
        }
        if ($("#cmb_wrkgrp").val() != '') {
            var wgpid = $("#cmb_wrkgrp").val();
        } else {
            alert('Select Work Group');
        }
        if (wgpid != '') {
            $.ajax({
                url: "work_creation_ajax.php",
                data: {
                    "schgrp": btoa(sgrpid),
                    "scheme": btoa(scheme),
                    "wgpid": btoa(wgpid),
                    "cmd": btoa(3)
                },
                type: "post",
                success: function(data) {
                    var Result_Data = JSON.parse(data);
                    if (Result_Data['STATUS'] == 'ERROR') {
                        alert(Result_Data['MESSAGE']);
                    } else {
                        $('#cmb_wrk_type').html(Result_Data['DATA']);
                    }
                },
                dataType: 'html'
            });
            return true;
        } else {
            alert("Work Group Not Set");
            return false;
        }
    });
    $('#cmb_wrk_type').change(function() {
        $("#tr_housing_info_lbl").hide();
        $("#tr_allotment_amount").hide();
        if ($("#cmb_schemegrp").val() != '') {
            var sgrpid = $("#cmb_schemegrp").val();
        } else {
            alert('Select Scheme Group');
        }
        if ($("#cmb_scheme").val() != '') {
            var scheme = $("#cmb_scheme").val();
        } else {
            alert('Select Scheme');
        }
        if ($("#cmb_wrkgrp").val() != '') {
            var wgpid = $("#cmb_wrkgrp").val();
        } else {
            alert('Select Work Group');
        }
        if ($("#cmb_wrk_type").val() != '') {
            var worktype = $("#cmb_wrk_type").val();
        } else {
            alert('Select Work Type');
        }
        if ($("#fin_year").val() != '') {
            var year = $("#fin_year").val();
        } else {
            alert('Select Financial Year');
        }
        $("#txt_asval").val('');
        $('#txt_asval').attr('readonly', false);
        $("#sow_det_div").hide();
        $("#sow_det_div").html('');
        $("#proposal_id").val('');
        if (worktype != '') {
            $.ajax({
                url: "work_creation_ajax.php",
                data: {
                    "schgrp": btoa(sgrpid),
                    "scheme": btoa(scheme),
                    "wgpid": btoa(wgpid),
                    "typid": btoa(worktype),
                    "cmd": btoa(4)
                },
                type: "post",
                success: function(data) {
                    if (data != '') {
                        if(sgrpid==10){
                            $('#tr_type_of_improvement').show();
                        $('#type_of_improvement').html(data);
                        $('.type_of_improvement_chainage').show();
                        }
                    } else {
                        $('#tr_type_of_improvement').hide();
                        $('.type_of_improvement_chainage').hide();
                    }
                },
                dataType: 'html'
            });
        }
    });
    $("#cmb_aggrp").change(function() {
        if ($("#cmb_aggrp").val() != '') {
            var agency_group = $("#cmb_aggrp").val();
        } else {
            alert('Select Agency Group');
        }
        if ($("#dcode").val() != '') {
            var dcode = $("#dcode").val();
        } else {
            alert('Select District');
        }
		if ($("#lbcode").val() != '') {
            var lbcode = $("#lbcode").val();
        } else {
            alert('Select Town Panchayat');
        }
        if (agency_group != '' ) {
            $.ajax({
                url: "work_creation_ajax.php",
                data: {
                    "agency_group": btoa(agency_group),
                    "dcode": btoa(dcode),
					"lbcode": btoa(lbcode),
                    "cmd": btoa(5)
                },
                type: "post",
                success: function(data) {
                    if (data != '') {
                        $('#cmb_agn').html(data);
                    }
                },
                dataType: 'html'
            });
            return true;
        } else {
            alert("Agency Group Not Set");
            return false;
        }
    });
    $("#txt_agmtdate").datepicker({
        uiLibrary: 'bootstrap4',
        format: 'dd-mm-yyyy',
        //minDate:  '12-12-2014',
        minDate: new Date('01-01-2020'),
        maxDate: new Date()
    });
    $("#txt_asdate").datepicker({
        uiLibrary: 'bootstrap4',
        format: 'dd-mm-yyyy',
        //minDate:  '12-12-2014',
        minDate: new Date('01-01-2020'),
        maxDate: new Date()
    });
    $("#txt_tsdate").datepicker({
        uiLibrary: 'bootstrap4',
        format: 'dd-mm-yyyy',
        //minDate:  '12-12-2014',
        minDate: new Date('01-01-2020'),
        maxDate: new Date()
    });
    $("input[name=work_undertaken_street_y_n]:radio").click(function() {
        var work_undertaken_street_y_n = $(this).val();
        if (work_undertaken_street_y_n == 'Y') {
            $("#streetid").show();
            $("#area_location").hide();
        } else {
            $("#streetid").hide();
            $("#area_location").show();
        }
    });
});
</script>
<style>
.hidden_field_element_value {
    display: none;
}

.gj-datepicker {
    width: 50%;
}

.gj-datepicker-bootstrap [role=right-icon] button {
    height: 30px;
}

/* .tr_section {
    background-color: #108d93de !important;
    font-weight: 600;
} */

#tr_housing_info_lbl,
#tr_housing_info_tbl,
#road_info_hd,
#road_info_main,
#thai_info_hd,
#thai_info_main,
#csc_info_main,
.csc_info_main,
#csc_info_main,
#sss_info_hd,
#sss_info_tbl,
#tr_atrocity_villages,
.tr_csc_additional_info,
.tr_gobardhan_additional_info,
.tr_gobardhan_additional_info,
#proposal_info_hd,
#proposal_info_main,
.beneficiary_details,
.tr_mini_csc_additional_info,
#toilet_taken_under,
.fund_under_sbm_15th_mgnregs {
    display: none;
}

.tndtp_form_table {
    font-size: 15px;
    font-weight: bold;
    width: 100%;
    /* border-collapse: collapse;
    border-spacing: 0;
    border-radius: 10px;
    overflow: hidden; */
}

.tndtp_form_table thead {
    padding: 3px
}

.tndtp_form_report_table {
    font-size: 15px;
    font-weight: bold;
    width: 100%;
    border-radius: 10px;
    text-align: center;
}

.tndtp_form_report_table th,
td {
    padding: 10px;
    text-align: center;
}

@media (max-width: 600px) {

    .tndtp_form_report_table,
    .tndtp_form_table {
        width: 100%;
        display: block;
        overflow-x: auto;
    }

    /* Display table rows as block elements */
    .tndtp_form_report_table thead,
    .tndtp_form_table thead {
        display: none;
    }
}

.newhead {
    background: linear-gradient(to right, #494889, #3B3A7C, #494889);
    color: white;
    padding: 5px;
    text-align: center;
}

.schemebuton {
    /* background-color: green; */
    background: #F56217;
    /* background: linear-gradient(#0B486B, #F56217); */
    color: white;
    font-size: 15px;
    border-radius: 7px;
    font-weight: bold;
    padding: 5px;
    margin: 3px;
    border: none;

}

.card {

    padding: 20px;
    margin: 20px;
    border-radius: 7px;
    /* border-top: 7px solid #555a86;
border-bottom: 7px solid #555a86; */
    /* box-shadow: 0 0 8px #333; */
    box-shadow: 3px 3px 10px rgb(0 0 0 / 40%) inset;
    /* box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px; */
    /* border: 10px solid #EBEBEB; */
    background: #fff;

}
</style>
<div class="container mt-3">
    <div class="card">
        <div class="card-body">
            <div class="col-lg-12 col-ml-12">
                <?php 
                        if (isset($data_array["STATUS"])) {
                            echo $this->ShowMessage($data_array["STATUS"], $data_array["MESSAGE"]);
                        }
                        ?>
                <form name="scheme_work_creation_entry" action="" id="scheme_work_creation_entry" method="post"
                    autocomplete="off" enctype="multipart/form-data">
                    <input class="form-control w-50 " type="hidden" id="scheme_work_creation_token"
                        name='scheme_work_creation_token'
                        value="<?php echo htmlentities($this->token("scheme_work_creation_token")); ?>">
                    <h5 class="text-center">New Work Creation Form</h5>
                    <table class="table-bordered tndtp_form_table">
                        <tbody>
                            <tr class="newhead">
                                <td colspan="2" class="tr_section">Work Details</td>
                            </tr>
                            <?php 
							//echo $this->issetCurrentDistrictCode(); die;
							if(!$this->issetCurrentDistrictCode()){ ?>
                            <tr>
                                <td class="w-50">District </td>
                                <td scope="col">
                                    <select name="dcode" class="form-control w-50 form-control-sm" id="dcode">
                                        <option value="">Select District</option>
                                        <?php
														$sel_dist = "select dcode, district_name_en from master.m_district where dist_order_no is not null;";
														$sel_dist_res = $this->prepare($sel_dist, array(),2);
														foreach ($sel_dist_res as $sel_dist_row) {
														?>
                                        <option value="<?php echo $sel_dist_row['dcode']; ?>">
                                            <?php echo $sel_dist_row['district_name_en']; ?></option>
                                        <?php  }  ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50">Town Panchayat</td>
                                <td scope="col">
                                    <select name="lbcode" class="form-control w-50 form-control-sm" id="lbcode">
                                        <option value="">Select Town Panchayat</option>
                                    </select>
                                </td>
                            </tr>
                            <?php }else{ 
							?>
                            	<input type="hidden" name="dcode" id="dcode" value="<?php echo ($dcode); ?>" />
                                <input type="hidden" name="lbcode" id="lbcode" value="<?php echo ($lbcode); ?>" />
							<?php
							} ?>
                            <tr>
                                <td class="w-50">Scheme Group </td>
                                <td scope="col">
                                    <select name="cmb_schemegrp" class="form-control w-50 form-control-sm"
                                        id="cmb_schemegrp">
                                        <option value="">Select Scheme Group</option>
                                        <?php
													$sel_schemegrp = "select DISTINCT a.scheme_grp_id, b.scheme_group_name_en from
(SELECT scheme_grp_id FROM master.m_role_type_wise_scheme_link where sch_role_link_y_n=1 and role_id=:role_id)a
LEFT JOIN
(SELECT scheme_group_id, scheme_group_name_en from master.m_scheme_group where isactive=:isactive and del_flag is null)b
on a.scheme_grp_id=b.scheme_group_id  ORDER BY scheme_group_name_en";
														$sel_schemegrp_res = $this->prepare($sel_schemegrp, array(":isactive"=>1, ":role_id"=>$role_code),2);
														foreach ($sel_schemegrp_res as $sel_schemegrp_row) {
														?>
                                        <option value="<?php echo $sel_schemegrp_row['scheme_grp_id'];?>">
                                            <?php echo $sel_schemegrp_row['scheme_group_name_en']; ?></option>
                                        <?php  }  ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50">Scheme </td>
                                <td scope="col">
                                    <select name="cmb_scheme" class="form-control w-50 form-control-sm" id="cmb_scheme">
                                        <option value="">Select Scheme</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50">Year </td>
                                <td scope="col">
                                    <select name="fin_year" class="form-control w-50 form-control-sm" id="fin_year">
                                        <option value="">Select Financial Year</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50">Work Group </td>
                                <td scope="col">
                                    <select name="cmb_wrkgrp" class="form-control w-50 form-control-sm" id="cmb_wrkgrp">
                                        <option value="">Select Work Group</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50">Work Type </td>
                                <td scope="col">
                                    <select name="cmb_wrk_type" class="form-control w-50 form-control-sm"
                                        id="cmb_wrk_type">
                                        <option value="">Select Work Type</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="tr_type_of_improvement" style="display: none;">
                                <td class="w-50">Type of improvement</td>
                                <td scope="col">
                                    <select name="type_of_improvement" id="type_of_improvement"
                                        class="form-control w-50 form-control-sm">
                                        <option value="">Select Type of improvement</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="wrknam">
                                <td class="w-50">Work Name</td>
                                <td>
                                    <textarea rows="2" name="work_name" id="work_name"
                                        class="form-control w-50 form-control-sm alpha_numeric_char"></textarea>
                                    
                                    <input type="hidden" id="mi_tank_survey_id" name="mi_tank_survey_id" value="" />
                                    <input type="hidden" id="mi_tank_lbcode" name="mi_tank_lbcode" value="" />
                                    <input type="hidden" id="mi_tank_dcode" name="mi_tank_dcode" value="" />
                                </td>
                            </tr>
                            <tr id="toilet_taken_under">
                                <td class="w-50">Toilet Taken Under</td>
                                <td>
                                    <select name="toilet_taken_under_id" class="form-control w-50 form-control-sm"
                                        id="toilet_taken_under_id">
                                        <option value="">Select Toilet Taken Under</option>
                                        <?php
                                                    /*$sel_rep = "select * from public.m_toilet_taken_under";
                                                    $selrep = $obj->selfn($sel_rep, $db);
                                                    foreach ($selrep as $reprow) {
                                                        ?>
                                        <option value="<?php echo $reprow['toilet_taken_under_id']; ?>">
                                            <?php echo $reprow['toilet_taken_under_name']; ?></option>
                                        <?php
                                                    }*/
                                                    ?>
                                    </select>
                                </td>
                            </tr>
                            <!-- <tr id="catgry_of_rep_wk">
                                <td class="w-50">Category of Repair Works</td>
                                <td>
                                    <select name="cmb_catgry_of_rep_wk" class="form-control w-50 form-control-sm"
                                        id="cmb_catgry_of_rep_wk">
                                        <option value="">Select Category of Repair Works</option>
                                        <?php
                                                    // $sel_rep = "select * from master.m_category_of_repair_works where del_flag is null and isactive=:isactive;";
                                                    // $selrep = $this->prepare($sel_rep, array(":isactive"=>1),2);
                                                    // foreach ($selrep as $reprow) {
                                                    ?>
                                        <option value="<?php //echo $reprow['id']; ?>">
                                            <?php //echo $reprow['category_name']; ?></option>
                                        <?php
                                                   // }
                                                    ?>
                                    </select>
                                </td>
                            </tr> -->
                            <tr id="shwtypemats" style="display:none;">
                                <td id="listmatrls" colspan="2"></td>
                            </tr>
                            <tr id="shwtypewrks" style="display:none">
                                <td colspan="2" id="listwtypes">&nbsp;</td>
                            </tr>
                            <tr id="tr_housing_info_lbl">
                                <td colspan="2" class="tr_section">Housing - Additional Information</td>
                            </tr>
                            <tr id="tr_housing_info_tbl">
                                <td colspan="2">
                                    <table border="0" style="width:100%">
                                        <tr>
                                            <td class="tlabel">
                                                <table border="1" style="width:100%"
                                                    class="table table-bordered table-striped tndtp_form_table">
                                                    <tr>
                                                        <td>Name of Beneficiary <spsn class="tlabel tr_bank_details">
                                                                <br />(As per Bank Pass book)</span> </td>
                                                        <td><input name="hou_ben_name" type="text"
                                                                class="form-control form-control-sm"
                                                                id="hou_ben_name" /></td>
                                                        <td class="tlabel aadhar_name_td">Name of Beneficiary <spsn>
                                                                <br />(As per Aadhar)</span> </td>
                                                        <td class="aadhar_name_td"><input name="aadhar_ben_name"
                                                                type="text" class="form-control form-control-sm"
                                                                id="aadhar_ben_name" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="tlabel">Sex</td>
                                                        <td>
                                                            <select name="cmb_sex" id="cmb_sex"
                                                                class="form-control form-control-sm">
                                                                <option value="">Select Sex</option>
                                                                <option value="1">Male</option>
                                                                <option value="2">Female</option>
                                                                <option value="3">Others</option>
                                                            </select>
                                                        </td>
                                                        <td>Father/Husband Name</td>
                                                        <td><input name="hou_benfh_name" type="text"
                                                                class="form-control form-control-sm"
                                                                id="hou_benfh_name" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Community</td>
                                                        <td>
                                                            <select name="cmb_community" id="cmb_community"
                                                                class="form-control form-control-sm">
                                                                <option value="">Select Community</option>
                                                                <?php
																	   /* $sel_com = "select * from m_community";
																		$selcom = $obj->selfn($sel_com, $db);
																		foreach ($selcom as $comrow) {
																			?>
                                                                <option value="<?= $comrow['community_code']; ?>">
                                                                    <?= $comrow['community_name']; ?>
                                                                </option>
                                                                <?php
																		}*/
                                										?>
                                                            </select>
                                                        </td>
                                                        <td><span class="span_mobile_no">Mobile No</span></td>
                                                        <td><span class="span_mobile_no">
                                                                <input name="txt_mobile_no" type="text"
                                                                    class="form-control form-control-sm numeric"
                                                                    id="txt_mobile_no" maxlength="10"
                                                                    onblur="mobile_number_validate(this.value)" /></span>
                                                        </td>
                                                    </tr>
                                                    <tr class="ihhl_work_type_hidden_field">
                                                        <td class="tlabel">Religion</td>
                                                        <td><select name="cmb_religion" id="cmb_religion"
                                                                class="form-control form-control-sm">
                                                                <option value="">Select Religion</option>
                                                                <?php
                                                                            /*$sel_com = "select * from m_religion";
                                                                            $selcom = $obj->selfn($sel_com, $db);
                                                                            foreach ($selcom as $comrow) {
                                                                                ?>
                                                                <option value="<?php echo $comrow['religion_code']; ?>">
                                                                    <?php echo $comrow['religion_name']; ?></option>
                                                                <?php
																}*/
																?>
                                                            </select></td>
                                                        <td class="tlabel" colspan="2"></td>
                                                    </tr>
                                                    <tr class="tr_bank_details" style="display:none;">
                                                        <td class="tlabel">Bank Name</td>
                                                        <td>
                                                            <select name="bank_id" class="form-control form-control-sm"
                                                                id="bank_id">
                                                                <option value="">Select Bank Name</option>
                                                                <?php
                                                                            /*$bnksql="select bank_id,bank_name,llbank_name from m_bank order by bank_name";
                                                                            $a=$obj->selfn($bnksql,$db);
                                                                             foreach($a as $bnkrow)
                                                                            {
                                                                                echo "<option value='".$bnkrow['bank_id']."' >".ucwords(strtolower($bnkrow["bank_name"]))."</option>";
                                                                            }*/
                                                                            ?>
                                                            </select>
                                                        </td>
                                                        <td class="tlabel">Branch Name</td>
                                                        <td>
                                                            <select name="branch_id"
                                                                class="form-control form-control-sm" id="branch_id">
                                                                <option value="" selected="selected">Select Branch Name
                                                                </option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr class="tr_bank_details">
                                                        <td class="tlabel">IFSC Code</td>
                                                        <td>
                                                            <input name="ifsc_code" type="text"
                                                                class="form-control form-control-sm" id="ifsc_code"
                                                                value="" />
                                                        </td>
                                                        <td class="tlabel">Account No</td>
                                                        <td><input name="txt_acct_no" type="text"
                                                                class="form-control form-control-sm" id="txt_acct_no"
                                                                maxlength="18" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="tlabel ihhl_work_type_hidden_field">Disabled</td>
                                                        <td class="ihhl_work_type_hidden_field">
                                                            <input type="radio" name="txt_disabled"
                                                                id="txt_disabled_yes" class="" value="1" />
                                                            Yes
                                                            <input name="txt_disabled" type="radio" id="txt_disabled_no"
                                                                class="" value='0' />
                                                            No
                                                        </td>
                                                        <td height="5" class="tlabel" style="padding:6px;"><span
                                                                class="span_aadhaar_no">AADHAAR Card
                                                                Number&nbsp;&nbsp;&nbsp;</span></td>
                                                        <td>
                                                            <span class="span_aadhaar_no"><input name="txt_aadhaar_no"
                                                                    type="text" class="form-control form-control-sm"
                                                                    id="txt_aadhaar_no" maxlength="12"
                                                                    onblur="aadhar_validate(this.value)" /></span>
                                                        </td>
                                                    </tr>
                                                    <tr class="tr_bank_details ihhl_work_type_hidden_field_2">
                                                        <td>AWAAS Soft Code</td>
                                                        <td>
                                                            <input name="txt_pmayg_code" type="text"
                                                                class="form-control form-control-sm" id="txt_pmayg_code"
                                                                maxlength="12" onblur="" />
                                                        </td>
                                                        <td>Bank Pass Book</td>
                                                        <td>
                                                            <input type="file" id="file_pass_book" name="file_pass_book"
                                                                class="form-control form-control-sm" value=""
                                                                onChange="return doc_validate(this);" accept=".pdf" />
                                                            <span style="color:#06F;"> Maximum File Size 10MB </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="tlabel td_id_st_department">Department</td>
                                                        <td class="tlabel td_id_st_department">
                                                            <select name="cmb_st_department" id="cmb_st_department"
                                                                class="form-control form-control-sm">
                                                                <option value="">Select Department</option>
                                                                <?php
                                                                               /* $sel_com = "select * from m_green_house_st_dept";
                                                                                $selcom = $obj->selfn($sel_com, $db);
                                                                                foreach ($selcom as $comrow) {
                                                                                    ?>
                                                                <option value="<?php echo $comrow['id']; ?>">
                                                                    <?php echo $comrow['dept_name']; ?></option>
                                                                <?php
																		}*/
																		?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr class="ihhl_work_type_show_field">
                                                        <td>Poverty line Category</td>
                                                        <td>
                                                            <select name="poverty_line_cat_id" id="poverty_line_cat_id"
                                                                class="form-control form-control-sm">
                                                                <option value="">Select Poverty line category</option>
                                                                <?php
                                                                            /*$sel_com = "select * from m_poverty_line_category";
                                                                            $selcom = $obj->selfn($sel_com, $db);
                                                                            foreach ($selcom as $comrow) {
                                                                                ?>
                                                                <option
                                                                    value="<?php echo $comrow['poverty_line_cat_id']; ?>">
                                                                    <?php echo $comrow['poverty_line_category_name']; ?>
                                                                </option>
                                                                <?php
																	}*/
																	?>
                                                            </select>
                                                        </td>
                                                        <td>Poverty line Sub Category</td>
                                                        <td>
                                                            <select name="poverty_line_sub_cat_id"
                                                                id="poverty_line_sub_cat_id"
                                                                class="form-control form-control-sm">
                                                                <option value="">Select Poverty line sub category
                                                                </option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr class="ihhl_work_type_show_field">
                                                        <td>Toilet Technology</td>
                                                        <td>
                                                            <select name="toilet_technology_id"
                                                                id="toilet_technology_id"
                                                                class="form-control form-control-sm">
                                                                <option value="">Select Toilet Technology</option>
                                                                <?php
                                                                                    /*$sel_com = "select * from m_toilet_technology";
                                                                                    $selcom = $obj->selfn($sel_com, $db);
                                                                                    foreach ($selcom as $comrow) {
                                                                                        ?>
                                                                <option
                                                                    value="<?php echo $comrow['toilet_technology_id']; ?>">
                                                                    <?php echo $comrow['toilet_technology_name']; ?>
                                                                </option>
                                                                <?php
																			}*/
																			?>
                                                            </select>
                                                        </td>

                                                        <td>Toilet for Person with Disability</td>
                                                        <td>
                                                            <input type="radio" name="toilet_person_with_disability_yn"
                                                                id="toilet_person_with_disability_y" value="Y"
                                                                class="validate[required] radio" />
                                                            Yes<input type="radio"
                                                                name="toilet_person_with_disability_yn"
                                                                id="toilet_person_with_disability_n" value="N"
                                                                class="validate[required] radio" />No
                                                        </td>
                                                    </tr>
                                                    <tr class="ihhl_work_type_show_field">
                                                        <td>Beneficiary ID as per IMIS</td>
                                                        <td>
                                                            <input name="beneficiary_id_per_imis" type="text"
                                                                class="form-control form-control-sm"
                                                                id="beneficiary_id_per_imis" maxlength="10" />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <?php /*?><tr>
                                <td>No of Units</td>
                                <td><input type="text" name="txt_unit" id="txt_unit"
                                        class="form-control w-50 form-control-sm" value="1" /></td>
                            </tr>
                            <tr id="tr_lngth">
                                <td>Length</td>
                                <td><input type="text" name="txt_length" id="txt_length"
                                        class="form-control w-50 form-control-sm" /> <span id="txtuom">(UOM)</span></td>
                            </tr><?php */?>
                            <tr class="work_order_details">
                                <td>Agreement / Work Order Number </td>
                                <td><input name="txt_agmtno" type="text"
                                        class="form-control w-50 form-control-sm order_number" id="txt_agmtno" />
                                </td>
                            </tr>
                            <tr class="work_order_details">
                                <td>Agreement / <span class="tlabel" style="padding:8px;">Work Order</span>Date</td>
                                <td><input name="txt_agmtdate" type="text" class="form-control w-50 form-control-sm"
                                        id="txt_agmtdate" />Format:<span style="color:#06F;"> [dd-mm-yyyy] </span></td>
                            </tr>
                            <tr id="road_info_hd">
                                <td colspan="2" class="tr_section">Roads - Additional Information</td>
                            </tr>
                            <tr id="road_info_main">
                                <td colspan="2">
                                    <table border="1" style="width:100%"
                                        class="table table-bordered table-striped tndtp_form_table">
                                        <tr>
                                            <td>Select Rural/Urban Road</td>
                                            <td>
                                                <select name="rur_urb_road" class="form-control form-control-sm"
                                                    id="rur_urb_road">
                                                    <option value="">Select Rural/Urban Road </option>
                                                    <option value="RR">Rural Road</option>
                                                    <option value="UR">Urban Road</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr id="roadstrt">
                                            <td>Select Road/Street</td>
                                            <td>
                                                <select name="cmb_street_lane" class="form-control form-control-sm"
                                                    id="cmb_street_lane">
                                                    <option value="">Select Road/Street</option>
                                                    <option value="1">Road</option>
                                                    <option value="2">Street</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr id="urbrdid">
                                            <td>Road ID</td>
                                            <td>
                                                <input type="text" name="road_id" id="road_id"
                                                    class="form-control form-control-sm" />
                                            </td>
                                            <td>Search and Select Road ID:</td>
                                            <td><img src="../images/eye_icon.png" border='0' class="category"
                                                    style="cursor:pointer;"
                                                    title="Click here To Search Road Register Details" /></td>
                                        </tr>
                                        <tr>
                                            <td>Road Name</td>
                                            <td>
                                                <textarea rows="2" cols="30" name="road_name" id="road_name"
                                                    class="form-control form-control-sm"></textarea>
                                            </td>
                                        </tr>
                                        <tr id="rdpmgsy">
                                            <td>Road Name as per PMGSY Core Network</td>
                                            <td>
                                                <textarea rows="2" cols="30" name="road_pmgsy" id="road_pmgsy"
                                                    class="form-control form-control-sm"></textarea>
                                            </td>
                                        </tr>
                                        <tr id="urbrdcde">
                                            <td>Road Code</td>
                                            <td>
                                                <input type="text" name="road_code" id="road_code"
                                                    class="form-control form-control-sm" />
                                            </td>
                                        </tr>
                                        <tr id="urbrdcat">
                                            <td>Road Category</td>
                                            <td>
                                                <input type="text" name="road_category" id="road_category"
                                                    class="form-control form-control-sm" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Green / Alternate Technology Adopted</td>
                                            <td>
                                                <select name="road_technology_adapted"
                                                    class="form-control form-control-sm" id="road_technology_adapted">
                                                    <option value="">Select Road Technology</option>
                                                    <?php
                                                                /*$sel_tech = "select * from public.road_m_technology_adapted order by display_order";
                                                                $sel_tech_res = $obj->selfn($sel_tech, $db);
                                                                foreach ($sel_tech_res as $sel_tech_row) {
                                                                    ?>
                                                    <option
                                                        value="<?php echo $sel_tech_row['road_technology_adapted']; ?>">
                                                        <?php echo $sel_tech_row['technology_adapted']; ?></option>
                                                    <?php
                                                                }*/
                                                                ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td rowspan="2">Chainage</td>
                                            <td>From</td>
                                            <td>To</td>
                                            <td>Total Length</td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" name="chg_from" id="chg_from"
                                                    class="form-control form-control-sm" /></td>
                                            <td><input type="text" name="chg_to" id="chg_to"
                                                    class="form-control form-control-sm" /></td>
                                            <td><input type="text" name="chg_tot" id="chg_tot"
                                                    class="form-control form-control-sm" /></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">Additional Information - Chainage Taken up</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <table id="roadTypechgTkupTable"
                                                    class="table table-bordered table-striped tndtp_form_table">
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <input type="hidden" name="chg_takenup1" id="chg_takenup1"
                                                            value="1" />
                                                        <td>From</td>
                                                        <td>To</td>
                                                        <td>Total Length</td>
                                                        <td>Type of improvement</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Chainage (Taken up)-1</td>
                                                        <input type="hidden" name="cd_work_id1" id="cd_work_id1"
                                                            value="1" />
                                                        <td>
                                                            <input type="text" data-slno="1" name="road_chgtkupfrm1"
                                                                id="road_chgtkupfrm1"
                                                                class="form-control form-control-sm" rel="wrk3" />
                                                        </td>
                                                        <td><input type="text" data-slno="1" name="road_chgtkupto1"
                                                                id="road_chgtkupto1"
                                                                class="form-control form-control-sm" rel="wrk3" /></td>
                                                        <td><input type="text" data-slno="1" name="road_chgtkuptot1"
                                                                id="road_chgtkuptot1"
                                                                class="form-control form-control-sm" rel="wrk3" /></td>
                                                        <td align="right" class="type_of_improvement_chainage">
                                                            <select name="type_of_improvement1"
                                                                id="type_of_improvement1"
                                                                class="form-control form-control-sm">
                                                                <option value="">Select Type of improvement</option>
                                                                <?php
                                                                          /*$type_of_improvement_qry = "select type_of_improvement_id,type_of_improvement from m_type_of_improvement
                                                                          order by type_of_improvement_id" ;
                                                                            $res_type_of_improvement_qry = $obj->selfn($type_of_improvement_qry,$db);
                                    
                                                                            foreach($res_type_of_improvement_qry as $key => $row){
                                                                        ?>
                                                                <option
                                                                    value="<?php echo $row['type_of_improvement_id']; ?>">
                                                                    <?php echo $row['type_of_improvement']; ?></option>
                                                                <?php
                                                                            }*/
                                                                    	?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <div style="width:50px;" align="right">
                                                                <img src="../images/plus.png" style="cursor:pointer;"
                                                                    class="addChgTkup" name="addChgTkup" title="Add"
                                                                    value=" + " />
                                                                <img src="../images/minus.png" style="cursor:pointer;"
                                                                    class="minusChgTkup" name="minusChgTkup" value=" - "
                                                                    title="Remove" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <input type="hidden" name="roadTypechgTkupcount"
                                                    id="roadTypechgTkupcount" value="1" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table class="table table-bordered table-striped tndtp_form_table">
                                                    <tr>
                                                        <td>Chainage Takenup Overall Total Length</td>
                                                        <td>
                                                            <input type="text" name="cons_takenup_total"
                                                                id="cons_takenup_total"
                                                                class="form-control form-control-sm" />
                                                        </td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Whether this Road has Under CD or Protective Work?</td>
                                            <td>
                                                <input type="radio" name="cd_prot_yn" id="cd_prot_wrk_y" value="Y"
                                                    class="" />
                                                Yes&nbsp;&nbsp;&nbsp;<input type="radio" name="cd_prot_yn"
                                                    id="cd_prot_wrk_n" value="N" class="" />No
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">Additional Information - CD Works</td>
                                        </tr>
                                        <tr class="cdprwrkyn">
                                            <td>
                                                <table border="1">
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td>Chainage [in Km.]</td>
                                                        <td>CD Type</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr class="cdprwrkyn">
                                                        <td>CD Work No-1</td>
                                                        <input type="hidden" name="cd_work_id1" id="cd_work_id1"
                                                            value="1" />
                                                        <td>
                                                            <input type="text" data-slno="1" name="road_chainage1"
                                                                id="road_chainage1" class="form-control form-control-sm"
                                                                rel="wrk3" />
                                                        </td>
                                                        <td>
                                                            <select name="road_cdtype1" id="road_cdtype1"
                                                                class="form-control form-control-sm">
                                                                <option value="">Select CD Type</option>
                                                                <?php
                                                                        /*$sel_cdt = "select * from m_cross_drainage_works where cd_type_flag='C' order by cd_name";
                                                                        //echo $sel_cdt;
                                                                        $selcdt = $obj->selfn($sel_cdt, $db);
                                                                        foreach ($selcdt as $cdtrw) {
                                                                            ?>
                                                                <option value="<?= $cdtrw['cd_code']; ?>">
                                                                    <?= $cdtrw['cd_name']; ?></option>
                                                                <?php
                                                                        }*/
                                                                        ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <div style="width:50px;" align="right">
                                                                <img src="../images/plus.png" style="cursor:pointer;"
                                                                    class="addInfocd" name="addInfocd" title="Add"
                                                                    value=" + " />
                                                                <img src="../images/minus.png" style="cursor:pointer;"
                                                                    class="minusInfocd" name="minusInfocd" value=" - "
                                                                    title="Remove" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <input type="hidden" name="roadTypecdcount" id="roadTypecdcount"
                                                    value="1" />
                                            </td>
                                        </tr>
                                        <tr bgcolor="#6699CC" class="cdprwrkyn">
                                            <td colspan="4">Additional Information - Protective Works</td>
                                        </tr>
                                        <tr class="cdprwrkyn">
                                            <td>
                                                <table border="1"
                                                    class="table table-bordered table-striped tndtp_form_table">
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td>Chainage [in Km.]</td>
                                                        <td>Protective Type</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Protective Work No - 1
                                                            <input type="hidden" name="prot_work_id1" id="prot_work_id1"
                                                                value="1" />
                                                        </td>
                                                        <td>
                                                            <input type="text" data-slno="1" name="roadworkprotchg1"
                                                                id="roadworkprotchg1"
                                                                class="form-control form-control-sm" />
                                                        </td>
                                                        <td>
                                                            <select name="roadprot_cdtype1" id="roadprot_cdtype1"
                                                                class="form-control form-control-sm">
                                                                <option value="">Select Protective Type</option>
                                                                <?php
                                                                        /*$sel_cdt = "select * from m_cross_drainage_works where cd_type_flag='P' order by cd_name";
                                //echo $sel_cdt;
                                                                        $selcdt = $obj->selfn($sel_cdt, $db);
                                                                        foreach ($selcdt as $cdtrw) {
                                                                            ?>
                                                                <option value="<?= $cdtrw['cd_code']; ?>">
                                                                    <?= $cdtrw['cd_name']; ?></option>
                                                                <?php
                                                                        }*/
                                                                        ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <div style="width:50px;" align="right">
                                                                <img src="../images/plus.png" style="cursor:pointer;"
                                                                    class="addInfoprot" name="addInfoprot" title="Add"
                                                                    value=" + " />
                                                                <img src="../images/minus.png" style="cursor:pointer;"
                                                                    class="minusInfoprot" name="minusInfoprot"
                                                                    value=" - " title="Remove" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <input type="hidden" name="roadTypeprotcount" id="roadTypeprotcount"
                                                    value="1" />
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id="thai_info_hd">
                                <td colspan="2" class="tr_section">THAI - Additional Information</td>
                            </tr>
                            <tr id="thai_info_main">
                                <td colspan="2">
                                    <table border="1" class="table table-bordered table-striped tndtp_form_table">
                                        <tr>
                                            <td>Thai Category</td>
                                            <td>
                                                <select name="cmb_thaicateg" class="form-control form-control-sm"
                                                    id="cmb_thaicateg">
                                                    <option value="">Select Thai Category</option>
                                                    <?php
                                                            /*$sel_tc = "select * from m_thai_category order by id";
                                                            $seltc = $obj->selfn($sel_tc, $db);
                                                            foreach ($seltc as $cgrow) {
                                                                ?>
                                                    <option value="<?= $cgrow['id']; ?>">
                                                        <?= $cgrow['thai_categ_description']; ?>
                                                    </option>
                                                    <?php
                                                            }*/
                                                            ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Thai Component</td>
                                            <td>
                                                <select name="thai_comp" id="thai_comp"
                                                    class="form-control form-control-sm">
                                                    <option value="">Select Thai Component </option>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr class="csc_info_main">
                                <td colspan="2" class="tr_section">Community Sanitary Complex - Additional Information
                                </td>
                            </tr>
                            <tr class="csc_info_main">
                                <td colspan="2">
                                    <table border="1" class="table table-bordered table-striped tndtp_form_table">
                                        <tr>
                                            <td>Situlated at</td>
                                            <td>
                                                <select name="csc_situlated_at_id" class="form-control form-control-sm"
                                                    id="csc_situlated_at_id">
                                                    <option value="">Select Situlated at</option>
                                                    <?php
                                                            /*$sel_tc = "select * from m_csc_situlated_at order by csc_situlated_at_id";
                                                            $seltc = $obj->selfn($sel_tc, $db);
                                                            foreach ($seltc as $cgrow) {
                                                                ?>
                                                    <option value="<?php echo $cgrow['csc_situlated_at_id']; ?>">
                                                        <?php echo $cgrow['csc_situlated_at_desc']; ?>
                                                    </option>
                                                    <?php
                                                            }*/
                                                            ?>
                                                </select>
                                                <span id="csc_situlated_at_others_span" style="display:none">
                                                    <input name="csc_situlated_at_others" type="text"
                                                        class="form-control form-control-sm"
                                                        id="csc_situlated_at_others" />
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>No. of HHs having access to CSC</td>
                                            <td>
                                                <input name="no_of_hhs_have_access_csc" type="text"
                                                    class="form-control form-control-sm"
                                                    id="no_of_hhs_have_access_csc" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Technology Type</td>
                                            <td>
                                                <select name="csc_technology_type_id"
                                                    class="form-control form-control-sm" id="csc_technology_type_id">
                                                    <option value="">Select Technology Type</option>
                                                    <?php
                                                            /*$sel_tc = "select * from m_csc_technology_type order by csc_technology_type_id";
                                                            $seltc = $obj->selfn($sel_tc, $db);
                                                            foreach ($seltc as $cgrow) {
                                                                ?>
                                                    <option value="<?php echo $cgrow['csc_technology_type_id']; ?>">
                                                        <?php echo $cgrow['csc_technology_type_desc']; ?>
                                                    </option>
                                                    <?php
                                                            }*/
                                                            ?>
                                                </select>
                                                <span id="csc_technology_type_others_span" style="display:none">
                                                    <input name="csc_technology_type_others" type="text"
                                                        class="form-control form-control-sm"
                                                        id="csc_technology_type_others" />
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id="sss_info_hd">
                                <td colspan="2" class="tr_section">Contribution</td>
                            </tr>
                            <tr id="sss_info_tbl">
                                <td colspan="2">
                                    <table border="1" class="table table-bordered table-striped tndtp_form_table">
                                        <tr>
                                            <td>Government Contributions</td>
                                            <td>Public Contributions</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input name="govt_contribution" type="text"
                                                    class="form-control form-control-sm" id="govt_contribution" />
                                            </td>
                                            <td>
                                                <input name="public_contribution" type="text"
                                                    class="form-control form-control-sm" id="public_contribution" />
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id="tr_atrocity_villages">
                                <td>Work Belongs to Atrocity Prone Villages</td>
                                <td>
                                    <input type="radio" id="atrocity_villages_y" name="atrocity_villages_y_n"
                                        value="Y" />Yes
                                    <input type="radio" id="atrocity_villages_n" name="atrocity_villages_y_n"
                                        value="N" />No
                                </td>
                            </tr>
                            <tr class="tr_csc_additional_info">
                                <td colspan="2" class="tr_section">CSC - Additional Info</td>
                            </tr>
                            <tr class="tr_csc_additional_info">
                                <td colspan="2">
                                    <table border="1" class="table table-bordered table-striped tndtp_form_table">
                                        <tr>
                                            <td>Location</td>
                                            <td>
                                                <select name="csc_location_id" class="form-control form-control-sm"
                                                    id="csc_location_id">
                                                    <option value="">Select CSC Location</option>
                                                    <?php
                                                            /*$sel_tc = "select * from m_csc_location order by csc_location_id";
                                                            $seltc = $obj->selfn($sel_tc, $db);
                                                            foreach ($seltc as $cgrow) {
                                                            ?>
                                                    <option value="<?= $cgrow['csc_location_id']; ?>">
                                                        <?= $cgrow['csc_location_name']; ?>
                                                    </option>
                                                    <?php
                                                            }*/
                                                            ?>
                                                </select>
                                            </td>
                                            <td>Operation and Maintenance by</td>
                                            <td>
                                                <select name="csc_operation_maintenance_id"
                                                    class="form-control form-control-sm"
                                                    id="csc_operation_maintenance_id">
                                                    <option value="">Select Operation and Maintenance by</option>
                                                    <?php
                                                            /*$sel_tc = "select * from m_csc_operation_maintenance order by csc_operation_maintenance_id";
                                                            $seltc = $obj->selfn($sel_tc, $db);
                                                            foreach ($seltc as $cgrow) {
                                                            ?>
                                                    <option value="<?= $cgrow['csc_operation_maintenance_id']; ?>">
                                                        <?= $cgrow['csc_operation_maintenance_name']; ?>
                                                    </option>
                                                    <?php
                                                            }*/
                                                            ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Technology Type</td>
                                            <td>
                                                <select name="csc_toilet_technology_type_id"
                                                    class="form-control form-control-sm"
                                                    id="csc_toilet_technology_type_id">
                                                    <option value="">Select Technology Type</option>
                                                    <?php
                                                            /*$sel_tc = "select * from m_toilet_technology order by toilet_technology_id";
                                                            $seltc = $obj->selfn($sel_tc, $db);
                                                            foreach ($seltc as $cgrow) {
                                                            ?>
                                                    <option value="<?= $cgrow['toilet_technology_id']; ?>">
                                                        <?= $cgrow['toilet_technology_name']; ?>
                                                    </option>
                                                    <?php
                                                            }*/
                                                            ?>
                                                </select>
                                            </td>
                                            <td>No. of Households gets benifitted</td>
                                            <td>
                                                <input name="csc_no_of_households_gets_benifitted" type="text"
                                                    class="form-control form-control-sm"
                                                    id="csc_no_of_households_gets_benifitted" maxlength="10"
                                                    placeholder="" />
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr class="tr_mini_csc_additional_info">
                                <td colspan="2" class="tr_section">MINI CSC - Additional Info</td>
                            </tr>
                            <tr class="tr_mini_csc_additional_info">
                                <td colspan="2">
                                    <table border="1" class="table table-bordered table-striped tndtp_form_table">
                                        <tr>
                                            <td>Location</td>
                                            <td>
                                                <select name="mini_csc_location_id" class="form-control form-control-sm"
                                                    id="mini_csc_location_id">
                                                    <option value="">Select MINI CSC Location</option>
                                                    <?php
                                                        /*$sel_tc = "select * from m_mini_csc_location order by mini_csc_location_id";
                                                        $seltc = $obj->selfn($sel_tc, $db);
                                                        foreach ($seltc as $cgrow) {
                                                        ?>
                                                    <option value="<?= $cgrow['mini_csc_location_id']; ?>">
                                                        <? $cgrow['mini_csc_location_name']; ?>
                                                    </option>
                                                    <?php
                                                        }*/
                                                        ?>
                                                </select>
                                            </td>
                                            <td>Operation and Maintenance by</td>
                                            <td>
                                                <select name="mini_csc_operation_maintenance_id"
                                                    class="form-control form-control-sm"
                                                    id="mini_csc_operation_maintenance_id">
                                                    <option value="">Select Operation and Maintenance by</option>
                                                    <?php
                                                            /*$sel_tc = "select * from m_mini_csc_operation_maintenance order by mini_csc_operation_maintenance_id";
                                                            $seltc = $obj->selfn($sel_tc, $db);
                                                            foreach ($seltc as $cgrow) {
                                                            ?>
                                                    <option value="<?= $cgrow['mini_csc_operation_maintenance_id']; ?>">
                                                        <?= $cgrow['mini_csc_operation_maintenance_name']; ?>
                                                    </option>
                                                    <?php
                                                            }*/
                                                            ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Technology Type</td>
                                            <td>
                                                <select name="mini_csc_toilet_technology_type_id"
                                                    class="form-control form-control-sm"
                                                    id="mini_csc_toilet_technology_type_id">
                                                    <option value="">Select Technology Type</option>
                                                    <?php
                                                            /*$sel_tc = "select * from m_toilet_technology order by toilet_technology_id";
                                                            $seltc = $obj->selfn($sel_tc, $db);
                                                            foreach ($seltc as $cgrow) {
                                                            ?>
                                                    <option value="<?= $cgrow['toilet_technology_id']; ?>">
                                                        <?= $cgrow['toilet_technology_name']; ?>
                                                    </option>
                                                    <?php
                                                            }*/
                                                            ?>
                                                </select>
                                            </td>
                                            <td>No. of Households gets benifitted</td>
                                            <td>
                                                <input name="mini_csc_no_of_households_gets_benifitted" type="text"
                                                    class="form-control form-control-sm"
                                                    id="mini_csc_no_of_households_gets_benifitted" maxlength="10"
                                                    placeholder="" />
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr class="tr_gobardhan_additional_info">
                                <td class="tr_section" colspan="2">Gobardhan - Additional Info</td>
                            </tr>
                            <tr class="tr_gobardhan_additional_info">
                                <td colspan="2">
                                    <table border="1" class="table table-bordered table-striped tndtp_form_table">
                                        <tr>
                                            <td>Total Project Area (Sq.ft)</td>
                                            <td>
                                                <input name="total_project_area_in_sqft" type="text"
                                                    class="form-control form-control-sm" id="total_project_area_in_sqft"
                                                    maxlength="6" placeholder="" />
                                            </td>
                                            <td>Land classification</td>
                                            <td>
                                                <select name="land_classification" class="form-control form-control-sm"
                                                    id="land_classification">
                                                    <option value="">Select Land classification</option>
                                                    <?php
                                                            /*$sel_tc = "select * from m_land_classification order by land_classification_id";
                                                            $seltc = $obj->selfn($sel_tc, $db);
                                                            foreach ($seltc as $cgrow) {
                                                            ?>
                                                    <option value="<?= $cgrow['land_classification_id']; ?>">
                                                        <?= $cgrow['land_classification_name']; ?>
                                                    </option>
                                                    <?php
                                                            }*/
                                                            ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Capacity of the plant feedstock (in kg)</td>
                                            <td>
                                                <input name="capacity_plant_feedstock_kg" type="text"
                                                    class="form-control form-control-sm"
                                                    id="capacity_plant_feedstock_kg" maxlength="10" placeholder="" />
                                            </td>
                                            <td>Capacity of the plant Bio Gas (in cum)</td>
                                            <td>
                                                <input name="capacity_plant_bio_gas_cum" type="text"
                                                    class="form-control form-control-sm"
                                                    id="capacity_plant_bio_gas_cum" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Type of feedstock</td>
                                            <td>
                                                <select name="type_of_feedstock[]" id="type_of_feedstock"
                                                    class="form-control form-control-sm" multiple="multiple">
                                                    <?php                       
                                                                /*$sel_wt = "select  * from m_type_of_feedstock";
                                                                $selwt = $obj->selfn($sel_wt, $db);
                                                                foreach ($selwt as $wtrow) {
                                                            ?>
                                                    <option value="<?= $wtrow['type_of_feedstock_id']; ?>"
                                                        <?php if (in_array($wtrow['work_id'], $_REQUEST['cmb_wrknam'])) echo 'selected'; ?>>
                                                        <? $wtrow['type_of_feedstock_name']; ?>
                                                    </option>
                                                    <?
                                                                }*/
                                                            ?>
                                                </select>
                                            </td>
                                            <td>Source of Waste / feedstock</td>
                                            <td>
                                                <select name="source_waste_feedstock[]" id="source_waste_feedstock"
                                                    class="form-control form-control-sm" multiple="multiple">
                                                    <?php                       
                                                            /*$sel_wt = "select  * from m_type_of_feedstock";
                                                            $selwt = $obj->selfn($sel_wt, $db);
                                                            foreach ($selwt as $wtrow) {
                                                        ?>
                                                    <option value="<?= $wtrow['type_of_feedstock_id']; ?>"
                                                        <?php if (in_array($wtrow['work_id'], $_REQUEST['cmb_wrknam'])) echo 'selected'; ?>>
                                                        <?= $wtrow['type_of_feedstock_name']; ?>
                                                    </option>
                                                    <?
                                                            }    */                                            
                                                        ?>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th colspan="2" class="newhead">Agency Details</th>
                            </tr>
                            <tr>
                                <td>Agency Group</td>
                                <td>
                                    <select name="cmb_aggrp" class="form-control form-control-sm w-50" id="cmb_aggrp">
                                        <option value="">Select Agency Group</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Agency</td>
                                <td>
                                    <select name="cmb_agn" class="form-control form-control-sm w-50" id="cmb_agn">
                                        <option value="">Select Agency</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th colspan="2" class="newhead">Work Location Details</th>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table id="tab_rural" class="table-bordered tndtp_form_table">
                                        <tr>
                                            <td width="50%">Ward</td>
                                            <td>
                                            <select name="ward" class="form-control w-50 form-control-sm" id="ward">
                                                <option value="">Select Ward Name</option>
                                                <?php 
													if($this->issetCurrentDistrictCode()){
														$lb_list = $this->getWardNameList($dcode,$lbcode,'en');
														foreach ($lb_list as $lb_row) { 
														?>
															<option value="<?php echo $lb_row['ward_id'];?>">
															  <?php echo $lb_row['ward_code'] . ' - ' . $lb_row['ward_name']; ?>
                                                            </option>
														<?php
														}
													}
												?>
                                            </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Wheather the work is undertaken in a street(Yes/No)</td>
                                            <td>
                                                <input type="radio" id="work_undertaken_street_y"
                                                    name="work_undertaken_street_y_n" value="Y" />Yes
                                                <input type="radio" id="work_undertaken_street_n"
                                                    name="work_undertaken_street_y_n" value="N" />No
                                            </td>
                                        </tr>
                                        <tr id="streetid">
                                            <td>Street</td>
                                            <td>
                                            <select name="street_code" class="form-control w-50 form-control-sm" id="street_code">
                                                <option value="">Select Street Name</option>
                                            </select>
                                            </td>
                                        </tr>
                                        <tr id="area_location">
                                            <td>Area Location</td>
                                            <td>
                                                <textarea rows="2" cols="30" name="location" id="location"
                                                    class="form-control form-control-sm w-50 address_field"></textarea>
                                            </td>
                                        </tr>
                                        <?php /*?><tr id="revenue_taluk">
                                            <td height="32">Revenue Taluk</td>
                                            <td>
                                                <select name="rev_taluk" class="form-control form-control-sm"
                                                    id="rev_taluk">
                                                    <option value="">Select Revenue Taluk</option>
                                                    <?php
                                                            if (isset($_REQUEST["rev_taluk"]) && $_REQUEST["rev_taluk"] != "") {
                                                                $sel_qry = "select a.rev_taluk_code,b.taluk_name from
                                                                    (select dcode,rev_taluk_code FROM public.m_taluk_revenuevillage_link where dcode=" . $_REQUEST["district"] . " and bcode=" . $_REQUEST["block"] . " and pvcode=" . $_REQUEST["village"] . " )as a
                                                                    left join
                                                                    (select dcode,taluk_code,taluk_name from public.public_taluk_unicode)as b on a.rev_taluk_code=b.taluk_code and a.dcode=b.dcode";
                                                                $res_qry = $obj->selfn($sel_qry, $db);
                                                                foreach ($res_qry as $res_taluk) {
                                                                    if ($_REQUEST["rev_taluk"] == $res_taluk['rev_taluk_code'])
                                                                        $sel = "selected";
                                                                    else
                                                                        $sel = "";
                                                                    echo "<option value='" . $res_taluk['rev_taluk_code'] . "' $sel >" . ucwords(strtolower($res_taluk["taluk_name"])) . "</option>";
                                                                }
                                                            }
                                                            ?>
                                                </select>
                                            </td>
                                        </tr><?php */?>
                                    </table>
                                </td>
                            </tr>
                            <?php /*?><tr>
                                <td>Benifited Population</td>
                            </tr>
                            <tr>
                                <td>Benifited SC Population</td>
                                <td>
                                    <input name="benefited_sc_pop" type="text" class="form-control form-control-sm"
                                        id="benefited_sc_pop" />
                                </td>
                            </tr>
                            <tr>
                                <td>Benifited ST Population</td>
                                <td>
                                    <input name="benefited_st_pop" type="text" class="form-control form-control-sm"
                                        id="benefited_st_pop" />
                                </td>
                            </tr>
                            <tr id="proposal_info_hd">
                                <td colspan="2" class="tr_section">Proposals - Additional Information</td>
                            </tr>
                            <tr id="proposal_info_main">
                                <td colspan="2">
                                    <table border="1" class="table table-bordered table-striped tndtp_form_table">
                                        <tr>
                                            <td>Proposal ID</td>
                                            <td colspan="3">
                                                <input type="text" name="proposal_id" id="proposal_id"
                                                    class="form-control form-control-sm" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Work Description</td>
                                            <td><span id="work_desc"></span></td>
                                            <td>Proposal Amount</td>
                                            <td><span id="prop_amt"></span></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id="tr_allotment_amount">
                                <td colspan="2">
                                    <table width="100%" align="center" cellpadding="2" cellspacing="2" border="1"
                                        bordercolor="#6699CC" style="padding:8px;border-right:0px solid #6699CC;">
                                        <tr>
                                            <td align="center">Allotment amount</td>
                                            <td align="center">Allotment amount (70 percentage)</td>
                                            <td align="center">Allotment amount (30 percentage)</td>
                                        </tr>
                                        <tr>
                                            <td id="allotment_amount" align="right" class="tlabel" height="32"></td>
                                            <td id="allotment_amount_70per" align="right" class="tlabel" height="32">
                                            </td>
                                            <td id="allotment_amount_30per" align="right" class="tlabel" height="32">
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <table width="100%" align="center" cellpadding="2" cellspacing="2" border="1"
                                        bordercolor="#6699CC" style="padding:8px;border-right:0px solid #6699CC;">
                                        <tr>
                                            <td align="center">Available amount</td>
                                            <td align="center">Available amount (70 percentage)</td>
                                            <td align="center">Available amount (30 percentage)</td>
                                        </tr>
                                        <tr>
                                            <td id="available_amount" align="right" class="tlabel" height="32"></td>
                                            <td id="available_amount_70per" align="right" class="tlabel" height="32">
                                            </td>
                                            <td id="available_amount_30per" align="right" class="tlabel" height="32">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id="sow_det_tr">
                                <td>Shelf of works</td>
                                <td><img src="../images/eye_icon.png" border='0' class="get_sow_det"
                                        style="cursor:pointer;" title="Click here To Shelf of works" /></td>
                            </tr>
                            <tr class="beneficiary_details">
                                <td colspan="2" class="tr_section">Beneficiary Details</td>
                            </tr>
                            <tr class="beneficiary_details">
                                <td>Name as per Passbook</td>
                                <td><input name="ben_name_passbook" type="text" class="form-control form-control-sm"
                                        id="txt_ben_name_passbook" /></td>
                            </tr>
                            <tr class="beneficiary_details">
                                <td>Beneficiary Name</td>
                                <td><input name="ben_name" type="text" class="form-control form-control-sm"
                                        id="txt_ben_name" /></td>
                            </tr>
                            <tr class="beneficiary_details">
                                <td>Beneficiary Father Name</td>
                                <td><input name="ben_fname" type="text" class="form-control form-control-sm"
                                        id="txt_fben_name" /></td>
                            </tr>
                            <tr class="beneficiary_details">
                                <td>Community</td>
                                <td>
                                    <input name="hid_community_code" type="hidden" class="form-control form-control-sm"
                                        id="hid_community_code" />
                                    <?php 
                                                $sel_code_qry="select community_code, community_name from public.m_community;";
                                                $sel_code_qry_res = $obj->selfn($sel_code_qry, $db);
                                                foreach ($sel_code_qry_res as $sel_code_qry_row) {
                                                ?>
                                    <input name="community_code" type="radio" class="community_code"
                                        value="<?php echo $sel_code_qry_row['community_code']?>" />
                                    <?php echo $sel_code_qry_row['community_name']?>
                                    <?php
                                                }
                                            ?>
                                </td>
                            </tr>
                            <tr class="beneficiary_details">
                                <td>Gender</td>
                                <td>
                                    <input name="hid_gender" type="hidden" id="hid_gender" />
                                    <input name="gender" type="radio" class="gender" id="m_gender" value="1" /> Male
                                    <input name="gender" type="radio" class="gender" id="f_gender" value="2" /> Female
                                    <input name="gender" type="radio" class="gender" id="o_gender" value="3" /> Others
                                </td>
                            </tr>
                            <tr class="beneficiary_details">
                                <td class="tlabel ihhl_work_type_hidden_field">Disabled</td>
                                <td class="ihhl_work_type_hidden_field">
                                    <input name="hid_txt_disabled" type="hidden" id="hid_txt_disabled" />
                                    <input type="radio" name="txt_disabled" id="txt_disabled_yes" value="1" />
                                    Yes
                                    <input name="txt_disabled" type="radio" id="txt_disabled_no" value='0' />
                                    No
                                </td>
                            </tr>
                            <tr class="beneficiary_details">
                                <td>Mobile Number</td>
                                <td><input name="txt_mbl_no" type="text" class="form-control form-control-sm"
                                        id="txt_mbl_no" maxlength="10" /></td>
                            </tr>
                            <tr class="beneficiary_details">
                                <td>Aadhaar Number</td>
                                <td><input name="txt_aadhar_no" type="text" class="form-control form-control-sm"
                                        id="txt_aadhar_no" maxlength="12" /></td>
                            </tr>
                            <tr class="beneficiary_details">
                                <td>Account Number</td>
                                <td><input name="txt_account_no" type="text" class="form-control form-control-sm"
                                        id="txt_account_no" />
                                </td>
                            </tr>
                            <tr class="beneficiary_details">
                                <td>IFSC Code</td>
                                <td>
                                    <input name="txt_ifsc_code" type="text" class="form-control form-control-sm"
                                        id="txt_ifsc_code" />
                                    <input name="txt_bank_id" type="hidden" class="form-control form-control-sme"
                                        id="txt_bank_id" value="" />
                                    <input name="txt_branch_id" type="hidden" class="form-control form-control-sm"
                                        id="txt_branch_id" value="" />
                                    <input name="txt_passbook_file_name" type="hidden"
                                        class="form-control form-control-sm" id="txt_passbook_file_name" value="" />
                                </td>
                            </tr><?php */?>
                            <tr>
                                <th colspan="2" class="newhead">Administrative Sanction Details</th>
                            </tr>
                            <tr id="sow_det_div">

                            </tr>
                            <tr>
                                <td>Administrative Sanction Value (Rs)</td>
                                <td><input name="txt_asval" type="text"
                                        class="form-control form-control-sm amount_disable w-50 float_field"
                                        id="txt_asval" maxlength="10" /></td>
                            </tr>
                            <?php /*?><tr class="ihhl_work_type_show_field">
                                <td>Fund Category</td>
                                <td>
                                    <select name="fund_category" class="form-control form-control-sm"
                                        id="fund_category">
                                        <option value="">Select Fund Category</option>
                                        <?php
                                                /*$sel_cp = "select fund_category_id,fund_category_name from t_fund_category order by fund_category_id";
                                                $selcp = $obj->selfn($sel_cp, $db);
                                                foreach ($selcp as $cprow) {
                                                ?>
                                        <option value="<?php echo $cprow['fund_category_id']; ?>">
                                            <?php echo $cprow["fund_category_name"]; ?></option>
                                        <?php
                                                }
                                                ?>
                                    </select>
                                </td>
                            </tr><?php */?>
                            <tr class="fund_under_sbm_15th_mgnregs tr_slwm">
                                <td>Fund under SBM - G (Rs.)</td>
                                <td><input name="fund_under_sbm_g" type="text" class="form-control form-control-sm w-50"
                                        id="fund_under_sbm_g" maxlength="10" /></td>
                            </tr>
                            <tr class="fund_under_sbm_15th_mgnregs tr_slwm ihhl_pwmu_gobard_under_15th_fc">
                                <td>Fund under 15th FC (Rs.)</td>
                                <td><input name="fund_under_15th_fc" type="text"
                                        class="form-control form-control-sm w-50" id="fund_under_15th_fc"
                                        maxlength="10" /></td>
                            </tr>
                            <tr class="fund_under_sbm_15th_mgnregs">
                                <td>Fund under MGNREGS (Rs.)</td>
                                <td><input name="fund_under_mgnregs" type="text"
                                        class="form-control form-control-sm  w-50" id="fund_under_mgnregs"
                                        maxlength="10" /></td>
                            </tr>
                            <tr>
                                <td>Administrative Sanction by</td>
                                <td>
                                    <select name="cmb_asby" class="form-control form-control-sm w-50" id="cmb_asby">
                                        <?php
                                                $asbyqry = "select as_authority_id, as_authority_desig from master.m_as_authority where isactive=:isactive and del_flag is null;";
                                                $res_asbyqry = $this->prepare($asbyqry, array(":isactive"=>1),2);
                                                if (count($res_asbyqry) > 0) {
                                                    echo  '<option value="" >Select Administrative Sanction</option>';
                                                    foreach ($res_asbyqry as $rowas) { ?>
                                        <option value="<?php echo htmlentities($rowas['as_authority_id']);?>">
                                            <?php echo htmlentities($rowas['as_authority_desig']); ?> </option>
                                        <?php }
                                                }
                                                ?>
                                    </select>
                                </td>
                            </tr>
                            <tr class="sanction_date">
                                <td>Administrative Sanction Date</td>
                                <td><input name="txt_asdate" type="text" class="form-control form-control-sm w-50"
                                        id="txt_asdate" />&nbsp;Format:<span style="color:#06F;"> [dd-mm-yyyy] </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Administrative Sanction Number</td>
                                <td><input name="txt_asno" type="text"
                                        class="form-control form-control-sm w-50 number_slash_field" id="txt_asno"
                                        maxlength="20" placeholder="Maximum 20 Digits" /></td>
                            </tr>
                            <tr class="as_letter_file">
                                <td>As File</td>
                                <td>
                                    <input type="file" id="as_letter" name="as_letter"
                                        class="form-control form-control-sm w-50" value="" accept=".pdf" /> <span
                                        style="color:#06F;"> Maximum File Size 10MB </span>
                                </td>
                            </tr>
                            <tr class="estimate_signed_pdf_file">
                                <td>Estimation Signed PDF</td>
                                <td><input type="file" id="estimate_signed_pdf" name="estimate_signed_pdf"
                                        class="form-control form-control-sm w-50" value="" accept=".pdf" /> <span
                                        style="color:#06F;"> Maximum File Size 10MB </span>
                                </td>
                            </tr><br>
                            <tr>
                                <th colspan="2" class="newhead">Technical Sanction Details</th>
                            </tr>
                            <tr>
                                <td>Technical Sanction Value (Rs)</td>
                                <td><input name="txt_tsval" type="text"
                                        class="form-control form-control-sm  w-50 float_field" id="txt_tsval" /></td>
                            </tr>
                            <tr>
                                <td>Technical Sanction by</td>
                                <td>
                                    <select name="cmb_tsby" class="form-control form-control-sm w-50" id="cmb_tsby">
                                        <?php
                                                $tsbyqry = "select ts_authority_id, ts_authority_desig from master.m_ts_authority where del_flag is null and isactive=:isactive;";
                                                $res_tsbyqry = $this->prepare($tsbyqry, array(":isactive"=>1),2);
                                                if (count($res_tsbyqry) > 0) { ?>
                                        <option value="">Select Technical Sanction</option>
                                        <?php
                                                    foreach ($res_tsbyqry as $rowts) { ?>
                                        <option value="<?php echo htmlentities($rowts['ts_authority_id']); ?>">
                                            <?php echo htmlentities($rowts['ts_authority_desig']); ?> </option>
                                        <?php
                                                    }
                                                }
                                                ?>
                                    </select>
                                </td>
                            </tr>
                            <tr class="sanction_date">
                                <td>Technical Sanction Date</td>
                                <td><input name="txt_tsdate" type="text" class="form-control form-control-sm w-50"
                                        id="txt_tsdate" />
                                    &nbsp;Format:<span style="color:#06F;"> [dd-mm-yyyy] </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Technical Sanction Number</td>
                                <td><input name="txt_tsno" type="text"
                                        class="form-control form-control-sm w-50 number_slash_field" id="txt_tsno"
                                        maxlength="20" placeholder="Maximum 20 Digits" /></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" align="center">
                                    <button type="submit"
                                        class="btn <?php echo htmlentities($data_array["mode_class"]);?> btn-sm text-white"
                                        name="btn_save" id="btn_save"><i
                                            class="<?php echo htmlentities($data_array["mode_icon"]);?> pe-1 me-1"
                                            aria-hidden="true"></i><?php echo htmlentities($data_array["mode_name"]);?></button>
                                    &nbsp;
                                    <a class="btn btn-secondary btn-sm" href="work_creation_form.php"><i
                                            class="fa fa-eraser pe-1 me-1"></i>Clear</a>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </form>
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

        $this->Template("Template1", "Work Creation Form", $ob_output_main_forms, array(
            array(
                "name" => "Work Creation Form"
            )
        ));
        exit();
    }

    public function data_save($save_data)
    {
		//print_r($save_data);die;

		 if (! $this->validateToken("scheme_work_creation_token", $save_data["scheme_work_creation_token"])) {
             $this->main_form(array(
                 "STATUS" => "ERROR",
                 "STATUS_TYPE" => "FIELD",
                 "FIELD_NAME" => "scheme_work_creation_token",
                 "MESSAGE" => "Invalid Token",
                 "form_data" => $save_data
             ));
		 	exit;
         }
		 $state_code=$this->getCurrentStateCode();
		 if(isset($_POST['dcode']) && $_POST['dcode']!=''){
			$dcode = $_POST['dcode'];
			$dcode_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$dcode,
			'Field_Name'=>'dcode',
			'Field_max_length'=>'2',
			'Field_Label_Name'=>'District'
			)
			);
			if ($dcode_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "dcode",
					"MESSAGE" => $dcode_Validation['Message']
				), $save_data));
				exit;			
			}
		}else if(($this->issetCurrentDistrictCode())){
			$dcode = $this->getCurrentDistrictCode();
			$dcode_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$dcode,
			'Field_Name'=>'dcode',
			'Field_max_length'=>'2',
			'Field_Label_Name'=>'District'
			)
			);
			if ($dcode_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "dcode",
					"MESSAGE" => $dcode_Validation['Message']
				), $save_data));
				exit;			
			}
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "dcode",
				"MESSAGE" => "Select District"
			), $save_data));
			exit;	
		}
		
		if(isset($_POST['lbcode']) && $_POST['lbcode']!=''){
			$lbcode = $_POST['lbcode'];
			$lbcode_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$lbcode,
			'Field_Name'=>'lbcode',
			'Field_length'=>'6',
			'Field_Label_Name'=>'Town Panchayat'
			)
			);
			
			if ($lbcode_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "lbcode",
					"MESSAGE" => $lbcode_Validation['Message']
				), $save_data));
				exit;			
			}
		}else if (($this->issetCurrentLocalbodyCode())){
			$lbcode=$this->getCurrentLocalbodyCode();
			$lbcode_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$lbcode,
			'Field_Name'=>'lbcode',
			'Field_length'=>'6',
			'Field_Label_Name'=>'Town Panchayat'
			)
			);
			if ($lbcode_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "lbcode",
					"MESSAGE" => $lbcode_Validation['Message']
				), $save_data));
				exit;			
			}
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "lbcode",
				"MESSAGE" => "Town Panchayat"
			), $save_data));
			exit;	
		}
		if(isset($_POST['cmb_schemegrp']) && $_POST['cmb_schemegrp']!=''){
			$scheme_group_id = $_POST['cmb_schemegrp'];
			$scheme_group_id_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$scheme_group_id,
			'Field_Name'=>'scheme_group_id',
			'Field_max_length'=>'5',
			'Field_Label_Name'=>'Scheme Group'
			)
			);
			if ($scheme_group_id_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "scheme_group_id",
					"MESSAGE" => $scheme_group_id_Validation['Message']
				), $save_data));
				exit;			
			}
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "scheme_group_id",
				"MESSAGE" => "Select Scheme Group"
			), $save_data));
			exit;	
		}
		
		if(isset($_POST['cmb_scheme']) && $_POST['cmb_scheme']!=''){
			$scheme_id = $_POST['cmb_scheme'];
			$scheme_id_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$scheme_id,
			'Field_Name'=>'scheme_id',
			'Field_max_length'=>'5',
			'Field_Label_Name'=>'Scheme'
			)
			);
			if ($scheme_id_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "scheme_id",
					"MESSAGE" => $scheme_id_Validation['Message']
				), $save_data));
				exit;			
			}
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "scheme_id",
				"MESSAGE" => "Select Scheme"
			), $save_data));
			exit;	
		}
		if(isset($_POST['fin_year']) && $_POST['fin_year']!=''){
			$fin_year = $_POST['fin_year'];
			$fin_year_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'fin_year',
			'Field_Value'=>$fin_year,
			'Field_Name'=>'fin_year',
			'Field_max_length'=>'9',
			'Field_Label_Name'=>'fin_year'
			)
			);
			if ($fin_year_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "fin_year",
					"MESSAGE" => $fin_year_Validation['Message']
				), $save_data));
				exit;			
			}
		}else{
			$fin_year=NULL;
		}
		
		if(isset($_POST['cmb_wrkgrp']) && $_POST['cmb_wrkgrp']!=''){
			$work_group_id = $_POST['cmb_wrkgrp'];
			$work_group_id_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$work_group_id,
			'Field_Name'=>'work_group_id',
			'Field_max_length'=>'5',
			'Field_Label_Name'=>'Work Group'
			)
			);
			if ($work_group_id_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "work_group_id",
					"MESSAGE" => $work_group_id_Validation['Message']
				), $save_data));
				exit;			
			}
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "work_group_id",
				"MESSAGE" => "Select Work Group"
			), $save_data));
			exit;	
		}
		
		if(isset($_POST['cmb_wrk_type']) && $_POST['cmb_wrk_type']!=''){
			$work_type = $_POST['cmb_wrk_type'];
			$cmb_wrk_type_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$work_type,
			'Field_Name'=>'cmb_wrk_type',
			'Field_max_length'=>'5',
			'Field_Label_Name'=>'Work Type'
			)
			);
			if ($cmb_wrk_type_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "cmb_wrk_type",
					"MESSAGE" => $cmb_wrk_type_Validation['Message']
				), $save_data));
				exit;			
			}
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR", 
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "cmb_wrk_type",
				"MESSAGE" => "Select Work Type"
			), $save_data));
			exit;	
		}
	
		if(isset($_POST['type_of_improvement']) && $_POST['type_of_improvement']!=''){
			$type_of_improvement = $_POST['type_of_improvement'];
			$type_of_improvement_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$type_of_improvement,
			'Field_Name'=>'type_of_improvement',
			'Field_max_length'=>'2',
			'Field_Label_Name'=>'Type Of Improvement'
			)
			);
			if ($type_of_improvement_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "type_of_improvement",
					"MESSAGE" => $type_of_improvement_Validation['Message']
				), $save_data));
				exit;			
			}
        }
        else{
            $type_of_improvement=NULL; 
        }
		
		if(isset($_POST['work_name']) && $_POST['work_name']!=''){
			$work_name=$_POST['work_name'];
			$work_name_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'text_number_character',
			'Field_Value'=>$save_data['work_name'],
			'Field_Name'=>'work_name',
			//'Field_Max_length'=>'50',
			'Field_Label_Name'=>'Work Name'
			)
			);
			
			if ($work_name_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "work_name",
					"MESSAGE" => $work_name_Validation['Message']
				), $save_data));
				exit;			
			}	
		}else{
			$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "work_name",
					"MESSAGE" => 'Enter Work Name'
				), $save_data));
				exit;	
		}
		if(isset($save_data['cmb_catgry_of_rep_wk']) && $save_data['cmb_catgry_of_rep_wk']!='')
		{
			$repair_works=$save_data['cmb_catgry_of_rep_wk'];
			$repair_works_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$save_data['cmb_catgry_of_rep_wk'],
			'Field_Name'=>'repair_works',
			'Field_Max_length'=>'5',
			'Field_Label_Name'=>'Category of Repair Works'
			)
			);
			
			if ($repair_works_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "cmb_catgry_of_rep_wk",
					"MESSAGE" => $repair_works_Validation['Message']
				), $save_data));
				exit;			
			}			
		}else{
			$repair_works=NULL;	
		}
		if(isset($save_data['txt_agmtno']) && $save_data['txt_agmtno']!='')
		{
			$agreement_number=$save_data['txt_agmtno'];
			$agreement_number_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'order_number',
			'Field_Value'=>$save_data['txt_agmtno'],
			'Field_Name'=>'agmtno',
			'Field_Max_length'=>'20',
			'Field_Label_Name'=>'Agreement / Work Order Number'
			)
			);
			
			if ($agreement_number_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "txt_agmtno",
					"MESSAGE" => $agreement_number_Validation['Message']
				), $save_data));
				exit;			
			}			
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR", 
				"MESSAGE" => "Enter Agreement / Work Order Number"
			), $save_data));
			exit;	
		}	
		if(isset($save_data['txt_agmtdate']) && $save_data['txt_agmtdate']!='')
		{
			$agreement_date=$save_data['txt_agmtdate'];
			$agreement_date_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'date',
			'Field_Format'=>'dd-mm-yyyy',
			'Field_Value'=>$save_data['txt_agmtdate'],
			'Field_Name'=>'agmtdate',
			'Field_Max_length'=>'10',
			'Field_Label_Name'=>'Agreement / Work OrderDate'
			)
			);
			
			if ($agreement_date_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "txt_agmtno",
					"MESSAGE" => $agreement_date_Validation['Message']
				), $save_data));
				exit;			
			}	
            list($date,$month,$year)=explode('-',$save_data['txt_agmtdate']);
            $agreement_date=$year.'-'.$month.'-'.$date;		
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR", 
				"MESSAGE" => "Enter Agreement / Work Order Date"
			), $save_data));
			exit;	
		}		
		if(isset($save_data['cmb_agn']) && $save_data['cmb_agn']!='')
		{
			$agency_name=$save_data['cmb_agn'];
			$agency_name_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$save_data['cmb_agn'],
			'Field_Name'=>'agency_name',
			'Field_Max_length'=>'30',
			'Field_Label_Name'=>'Agency Name'
			)
			);
			
			if ($agency_name_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "agency_name",
					"MESSAGE" => $agency_name_Validation['Message']
				), $save_data));
				exit;			
			}			
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR", 
				"MESSAGE" => "Select Agency Name"
			), $save_data));
			exit;	
		}
		if(isset($save_data['cmb_aggrp']) && $save_data['cmb_aggrp']!='')
		{
			$agency_group=$save_data['cmb_aggrp'];
			$agency_group_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$save_data['cmb_aggrp'],
			'Field_Name'=>'agency_group',
			'Field_Max_length'=>'30',
			'Field_Label_Name'=>'Agency Group'
			)
			);
			
			if ($agency_group_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "agency_group",
					"MESSAGE" => $agency_group_Validation['Message']
				), $save_data));
				exit;			
			}			
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR", 
				"MESSAGE" => "Select Agency Group"
			), $save_data));
			exit;	
		}
		if(isset($save_data['ward']) && $save_data['ward']!='')
		{
			$ward=$save_data['ward'];
			$ward_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'text_number_space',
			'Field_Value'=>$save_data['ward'],
			'Field_Name'=>'ward',
			'Field_Max_length'=>'30',
			'Field_Label_Name'=>'Ward'
			)
			);
			
			if ($ward_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "ward",
					"MESSAGE" => $ward_Validation['Message']
				), $save_data));
				exit;			
			}			
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR", 
				"MESSAGE" => "Enter Ward"
			), $save_data));
			exit;	
		}
		if(isset($save_data['work_undertaken_street_y_n']) && $save_data['work_undertaken_street_y_n']!='')
		{
			$work_undertaken=$save_data['work_undertaken_street_y_n'];
			$work_undertaken_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'text',
			'Field_Value'=>$save_data['work_undertaken_street_y_n'],
			'Field_Name'=>'work_undertaken',
			'Field_length'=>'1',
			'Field_Label_Name'=>'Wheather the work is undertaken in a street(Yes/No)'
			)
			);
			
			if ($work_undertaken_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "ward",
					"MESSAGE" => $work_undertaken_Validation['Message']
				), $save_data));
				exit;			
			}			
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR", 
				"MESSAGE" => "Select work is undertaken  Status"
			), $save_data));
			exit;	
		}
		$location = NULL; 
        $street_code = NULL; 

		if ($work_undertaken == 'Y') {
			if (isset($save_data['street_code']) && $save_data['street_code'] != '') {
				$street_code = $save_data['street_code'];
				$street_code_Validation = $this->Field_Validation(
					array(
						'Field_Type' => 'text_number_space',
						'Field_Value' => $save_data['street_code'],
						'Field_Name' => 'street_code',
						'Field_Max_length' => '50',
						'Field_Label_Name' => 'Street'
					)
				);
		
				if ($street_code_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR",
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "street_code",
						"MESSAGE" => $street_code_Validation['Message']
					), $save_data));
					exit;
				}
			} else {
				$street_code = NULL;
			}
		} else {
			if (isset($save_data['location']) && $save_data['location'] != '') {
				$location = $save_data['location'];
				$location_Validation = $this->Field_Validation(
					array(
						'Field_Type' => 'text_number_character',
						'Field_Value' => $save_data['location'],
						'Field_Name' => 'location',
						'Field_Max_length' => '50',
						'Field_Label_Name' => 'Area Location'
					)
				);
		
				if ($location_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR",
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "location",
						"MESSAGE" => $location_Validation['Message']
					), $save_data));
					exit;
				}
			} else {
				$location = NULL;
			}
		}
		if(isset($save_data['txt_asval']) && $save_data['txt_asval']!='')
		{
			$asval=$save_data['txt_asval'];
			$asval_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'float',
			'Field_Value'=>$save_data['txt_asval'],
			'Field_Name'=>'asval',
			'Field_Max_length'=>'10',
			'Field_Label_Name'=>'Administrative Sanction Value'
			)
			);
			
			if ($asval_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "asval",
					"MESSAGE" => $asval_Validation['Message']
				), $save_data));
				exit;			
			}			
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR", 
				"MESSAGE" => "Enter Administrative Sanction Value"
			), $save_data));
			exit;	
		}
		if(isset($save_data['cmb_asby']) && $save_data['cmb_asby']!='')
		{
			$asby=$save_data['cmb_asby'];
			$asby_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$save_data['cmb_asby'],
			'Field_Name'=>'asby',
			'Field_Max_length'=>'2',
			'Field_Label_Name'=>'Administrative Sanction By'
			)
			);
			
			if ($asby_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "asby",
					"MESSAGE" => $asby_Validation['Message']
				), $save_data));
				exit;			
			}			
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR", 
				"MESSAGE" => "Select Administrative Sanction by"
			), $save_data));
			exit;	
		}
		if(isset($save_data['txt_asdate']) && $save_data['txt_asdate']!='')
		{
			$as_date=$save_data['txt_asdate'];
			$as_date_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'date',
			'Field_Format'=>'dd-mm-yyyy',
			'Field_Value'=>$save_data['txt_asdate'],
			'Field_Name'=>'as_date',
			'Field_Max_length'=>'10',
			'Field_Label_Name'=>'Administrative Sanction Date'
			)
			);
			
			if ($as_date_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "txt_asdate",
					"MESSAGE" => $as_date_Validation['Message']
				), $save_data));
				exit;			
			}	
            list($date,$month,$year)=explode('-',$save_data['txt_asdate']);
            $as_date=$year.'-'.$month.'-'.$date;		
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR", 
				"MESSAGE" => "Enter Administrative Sanction Date"
			), $save_data));
			exit;	
		}
		if(isset($save_data['txt_asno']) && $save_data['txt_asno']!='')
		{
			$as_no=$save_data['txt_asno'];
			$as_no_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'order_number',
			'Field_Value'=>$save_data['txt_asno'],
			'Field_Name'=>'as_no',
			'Field_Max_length'=>'10',
			'Field_Label_Name'=>'Administrative Sanction Number'
			)
			);
			
			if ($as_no_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "txt_asno",
					"MESSAGE" => $as_no_Validation['Message']
				), $save_data));
				exit;			
			}			
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR", 
				"MESSAGE" => "Enter Administrative Sanction Number"
			), $save_data));
			exit;	
		}

		if(isset($_FILES['as_letter']) && $_FILES['as_letter']['name'] !=''){
			$File = $_FILES['as_letter'];
			$File_Name = $File["name"];
			$imageFileType =mime_content_type($File["tmp_name"]);
			$file_type = array('application/pdf');
			if (!in_array($imageFileType, $file_type)) {
				$this->main_form(array(
					"STATUS" => "FAIL",
					"STATUS_TYPE" => "FORM",
					"MESSAGE" => "Sorry, only PDF files are alloweds."
				));
				exit;
			}
			$File_Size_Limit = 10 * 1024 * 1024;
			if ($File["size"] > $File_Size_Limit) {
				$this->main_form(array(
					"STATUS" => "FAIL",
					"STATUS_TYPE" => "FORM",
					"MESSAGE" => "Sorry, your file is too large."
				));
				exit;
			}
			list($selected_file_type,$imageFileType) =explode('/',$imageFileType);			
			$Base_path = $this->getStoragePath() . "Document/work/as_file/";
			$Temp_Base_path = $Base_path . '/' . $dcode . '/' . $lbcode . '/';
			if (!is_dir($Temp_Base_path)) {
				mkdir($Temp_Base_path, 0777, true);
			}
			$as_File_Name = 'as_file' . $dcode . '_' . $lbcode . '_' . rand() . '.' . $imageFileType;
			$Target_File_Name = $Temp_Base_path . $as_File_Name;
			if (move_uploaded_file($File["tmp_name"], $Target_File_Name)) {
				 $img = fopen($Target_File_Name, 'r');
				 $data = fread($img, filesize($Target_File_Name));
				 
			} else {
				$this->main_form(array(
					"STATUS" => "FAIL",
					"STATUS_TYPE" => "FORM",
					"MESSAGE" => "Sorry, there was an error uploading AS file."
				));
				exit;
			}				
		}else{
			$as_File_Name=NULL;
		}
		
		if(isset($_FILES['estimate_signed_pdf']) && $_FILES['estimate_signed_pdf']['name'] !=''){
			$File = $_FILES['estimate_signed_pdf'];
			$File_Name = $File["name"];
			$imageFileType =mime_content_type($File["tmp_name"]);
			$file_type = array('application/pdf');
			if (!in_array($imageFileType, $file_type)) {
				$this->main_form(array(
					"STATUS" => "FAIL",
					"STATUS_TYPE" => "FORM",
					"MESSAGE" => "Sorry, only PDF files are allowed."
				));
				exit;
			}
            $File_Size_Limit = 10 * 1024 * 1024;
			if ($File["size"] > $File_Size_Limit) {
				$this->main_form(array(
					"STATUS" => "FAIL",
					"STATUS_TYPE" => "FORM",
					"MESSAGE" => "Sorry, your file is too large."
				));
				exit;
			}
			list($selected_file_type,$imageFileType) =explode('/',$imageFileType);			
			$Base_path = $this->getStoragePath() . "Document/work/estimate_signed_pdf/";
			$Temp_Base_path = $Base_path . '/' . $dcode . '/' . $lbcode . '/';
			if (!is_dir($Temp_Base_path)) {
				mkdir($Temp_Base_path, 0777, true);
			}
			$estimated_File_Name = 'estimate_signed_pdf' . $dcode . '_' . $lbcode . '_' . rand() . '.' . $imageFileType;
			$Target_File_Name = $Temp_Base_path . $estimated_File_Name;


			if (move_uploaded_file($File["tmp_name"], $Target_File_Name )) {
				 $img = fopen($Target_File_Name, 'r');
				 $data = fread($img, filesize($Target_File_Name));
				 
			} else {
				$this->main_form(array(
					"STATUS" => "FAIL",
					"STATUS_TYPE" => "FORM",
					"MESSAGE" => "Sorry, there was an error uploading Estimation Signed PDF file."
				));
				exit;
			}				
		}else{
			$estimated_File_Name=NULL;
		}

      
		if(isset($save_data['txt_tsval']) && $save_data['txt_tsval']!='')
		{
			$tsval=$save_data['txt_tsval'];
			$tsval_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'float',
			'Field_Value'=>$save_data['txt_tsval'],
			'Field_Name'=>'tsval',
			'Field_Max_length'=>'10',
			'Field_Label_Name'=>'Technical Sanction Value'
			)
			);
			
			if ($tsval_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "tsval",
					"MESSAGE" => $tsval_Validation['Message']
				), $save_data));
				exit;			
			}			
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR", 
				"MESSAGE" => "Enter Technical Sanction Value"
			), $save_data));
			exit;	
		}
		if(isset($save_data['cmb_tsby']) && $save_data['cmb_tsby']!='')
		{
			$tsby=$save_data['cmb_tsby'];
			$tsby_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$save_data['cmb_tsby'],
			'Field_Name'=>'tsby',
			'Field_Max_length'=>'2',
			'Field_Label_Name'=>'Technical Sanction By'
			)
			);
			
			if ($tsby_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "tsby",
					"MESSAGE" => $tsby_Validation['Message']
				), $save_data));
				exit;			
			}			
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR", 
				"MESSAGE" => "Select Technical Sanction by"
			), $save_data));
			exit;	
		}
		if(isset($save_data['txt_tsdate']) && $save_data['txt_tsdate']!='')
		{
			$ts_date=$save_data['txt_tsdate'];
			$ts_date_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'date',
			'Field_Format'=>'dd-mm-yyyy',
			'Field_Value'=>$save_data['txt_tsdate'],
			'Field_Name'=>'ts_date',
			'Field_Max_length'=>'10',
			'Field_Label_Name'=>'Technical Sanction Date'
			)
			);
			
			if ($ts_date_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "txt_tsdate",
					"MESSAGE" => $ts_date_Validation['Message']
				), $save_data));
				exit;			
			}	
            list($date,$month,$year)=explode('-',$save_data['txt_tsdate']);
            $ts_date=$year.'-'.$month.'-'.$date;		
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR", 
				"MESSAGE" => "Enter Technical Sanction Date"
			), $save_data));
			exit;	
		}
		if(isset($save_data['txt_tsno']) && $save_data['txt_tsno']!='')
		{
			$ts_no=$save_data['txt_tsno'];
			$ts_no_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'order_number',
			'Field_Value'=>$save_data['txt_tsno'],
			'Field_Name'=>'ts_no',
			'Field_Max_length'=>'10',
			'Field_Label_Name'=>'Technical Sanction Number'
			)
			);
			
			if ($ts_no_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR", 
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "txt_tsno",
					"MESSAGE" => $ts_no_Validation['Message']
				), $save_data));
				exit;			
			}			
		}else{
			$this->main_form(array_merge(array(
				"STATUS" => "ERROR", 
				"MESSAGE" => "Enter Technical Sanction Number"
			), $save_data));
			exit;	
		}
		$message='Work Created Successfully.';
		$curr_stage_work=1;
        $getCurrentUser = $this->getCurrentUser();
        $getIpAddress = $this->getIpAddress();
		$save_query = "select * from works.sp_works(:state_code, :dcode, :lbcode, :scheme_group_id, :scheme_id, :fin_year, :work_group_id, :work_type_id, :work_name, :as_no, :as_date, :as_sanc_authority, :as_value, :as_revised,  :ts_no, :ts_date, :ts_sanc_authority, :ts_value, :ts_revised, :agency_group_id, :agency_id, :no_of_works_takenup, :yn_completed, :yn_cancelled, :no_of_works_completed, :no_of_works_physically_completed, :amount_spent_sofar,  :username, :ip_address, :current_stage_of_work, :latest_as_amount, :latest_ts_amount, :agreement_no, :agreement_date, :ward, :verified_stage_id, :verified_emp_name, :verified_y_n, :verified_date, :verified_designation_id, :inspection_date, :cancel_revert, :category_of_repair_works, :group_works_completed, :type_of_improvement, :location, :work_undertaken_street_y_n, :street, :work_undertaken_stsc_street_y_n, :estimation_signed_file_name, :as_file_name, :edit_id, :del_id);"; 		
        $res = $this->prepare($save_query,array(
		":state_code"=>$state_code, 
		":dcode"=>$dcode, 
		":lbcode"=>$lbcode, 
		":scheme_group_id"=>$scheme_group_id, 
		":scheme_id"=>$scheme_id, 
		":fin_year"=>$fin_year, 
		":work_group_id"=>$work_group_id,
		 ":work_type_id"=>$work_type, 
		 ":work_name"=>$work_name, 
		 ":as_no"=>$as_no, 
		 ":as_date"=>$as_date, 
		 ":as_sanc_authority"=>$asby, 
		 ":as_value"=>$asval, 
		 ":as_revised"=>NULL,  
		 ":ts_no"=>$ts_no, 
		 ":ts_date"=>$ts_date,
		  ":ts_sanc_authority"=>$tsby,
		   ":ts_value"=>$tsval, 
		   ":ts_revised"=>NULL, 
		   ":agency_group_id"=>$agency_group, 
		   ":agency_id"=>$agency_name, 
		   ":no_of_works_takenup"=>0, 
		   ":yn_completed"=>NULL, 
		   ":yn_cancelled"=>NULL, 
		   ":no_of_works_completed"=>NULL, 
		   ":no_of_works_physically_completed"=>0, 
		   ":amount_spent_sofar"=>0,  
		   ":username"=>$getCurrentUser, 
		   ":ip_address"=>$getIpAddress,
		    ":current_stage_of_work"=>$curr_stage_work, 
			":latest_as_amount"=>0, ":latest_ts_amount"=>0, 
			":agreement_no"=>$agreement_number, 
			":agreement_date"=>$agreement_date, 
			 ":ward"=>$ward, 
			 ":verified_stage_id"=>NULL, 
			 ":verified_emp_name"=>NULL, 
			 ":verified_y_n"=>NULL,
			 ":verified_date"=>NULL, 
			 ":verified_designation_id"=>NULL,
			  ":inspection_date"=>NULL, 
			  ":cancel_revert"=>NULL, 
			  ":category_of_repair_works"=>$repair_works, 
			  ":group_works_completed"=>NULL, 
			  ":type_of_improvement"=>$type_of_improvement, 
			  ":location"=>$location, 
			  ":work_undertaken_street_y_n"=>$work_undertaken, 
			  ":street"=>$street_code, 
			  ":work_undertaken_stsc_street_y_n"=>NULL,
			   ":estimation_signed_file_name"=>$estimated_File_Name, 
			   ":as_file_name"=>$as_File_Name,
			    ":edit_id"=>0, 
				":del_id"=>0),4);
        if ($this->prepareStatus($res)== true) {
            $this->commit();
			?>
<script>
alert('<?php echo htmlentities($message); ?>');
window.location.href = 'work_creation_form.php';
</script>
<?php
		}
		else {
			$this->rollBack();
            $this->main_form(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Work Creation Failed Due To Duplicate Entry"
            ));
        }
    }
}

$scheme_work_creation = new scheme_work_creation();
if (isset($_POST["btn_save"])) {
    $scheme_work_creation->data_save($_POST);
}
if (isset($_GET["edit_id"])) {
    $agency_group_edit_id = base64_decode($_GET["edit_id"]);
    $scheme_work_creation->main_form(array(
         "mode" => "edit",
        "mode_name" => "Update",
		"mode_class" => "btn-warning",
		"mode_icon" => "fa fa-pencil",
        "edit_id" => $agency_group_edit_id
    ));
}
if (isset($_GET["del_id"])) {
    $agency_group_delete_id = base64_decode($_GET["del_id"]);
    $scheme_work_creation->main_form(array(
         "mode" => "delete",
        "mode_name" => "Delete",
		"mode_class" => "btn-danger",
		"mode_icon" => "fa fa-trash-o",
        "delete_id" => $agency_group_delete_id
    ));
} else {
    $scheme_work_creation->main_form(array(
       "mode" => "save","mode_name" => "Save","mode_class" => "btn-success","mode_icon" => "fa fa-floppy-o"
    ));
}
?>