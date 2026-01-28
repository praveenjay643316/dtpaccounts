<?php
require_once __DIR__ . '/config/config.php';
class Home extends ConfigClass
{
    public function __construct()
    {
        if (! isset($this->db)) { 
        }
    }

    public function main_content()
    {
        $site_data = $this->siteData();

        ob_start();

        // #############

        // PAGE CONTENT START

        // #############
		    $role_code=$_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'];

		?>
<style>
.welcome_heading_p {
    color: black !important;
    font-size: 17px;
}

.schemecard,
.schemelink,
.work {
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

/* .scheme {
    background-color: #094586;
    color: white;
    font-family: Arial, Helvetica, sans-serif;
    font-weight: bold;
    width: 90%;
    text-align: left;
    padding: 7px;
    margin: 12px;
    margin-left: 20px;
    border-radius: 7px;
    border: none;
} */
.scheme {
    background: #555a86;
    color: white;
    font-family: Arial, Helvetica, sans-serif;
    font-weight: bold;
    width: 90%;
    text-align: left;
    padding: 7px;
    margin: 12px;
    margin-left: 20px;
    border-radius: 7px;
    border: none;
    box-shadow: 0 0 0 1px #555a86 inset;
    transition: ease-in 0.7s;
}

.scheme:hover {
    box-shadow: 0 -300px 0 1px #2196F3 inset;
}

@keyframes pulse {
    0% {
        box-shadow: 0px 0px 0 4px #fff, 0px 0px 0 6px #555a86;
        opacity: 1;
    }

    100% {
        box-shadow: 0px 0px 0 8px #fff, 0px 0px 0 10px #555a86;
        opacity: 0.9;
    }
}

.scheme span {
    margin-left: 20px;
}
</style>
<div class="container">
    <?php if((isset($_GET['id']) && base64_decode($_GET['id'])==1)){
		if($role_code !=4){
		 ?>
    <div class="schemecard">
        <div class="row ">
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/master_scheme_group_entry.php'"><span>Scheme
                        Group Entry</span></button></div>
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/master_scheme_entry.php'"><span>Scheme
                        Entry</span></button></div>
        </div>
        <div class="row">
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/master_work_group_entry.php'"><span>Work
                        Group Entry</span></button></div>
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/master_work_type_entry.php'"><span>Work
                        Type Entry</span></button></div>
        </div>
        <div class="row">
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/master_as_authority_entry.php'"><span>AS
                        Designation Entry</span></button></div>
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/master_ts_authority_entry.php'"><span>TS
                        Designation Entry</span></button></div>
        </div>
        <div class="row">
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/master_as_authority_sp.php'"><span>AS
                        Authority Power Entry</span></button></div>
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/master_ts_authority_sp.php'"><span>TS
                        Authority Power Entry</span></button></div>
        </div>
        <div class="row">
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/master_agency_group_entry.php'"><span>Agency
                        Group Entry</span></button></div>
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/master_scheme_agency_entry.php'"><span>Agency
                        Entry</span></button></div>
        </div>
        <div class="row">
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/master_stage_entry.php'"><span>Stage
                        Entry</span></button></div>
        </div>
    </div>
    <?php } }else if (isset($_GET['id']) && base64_decode($_GET['id'])==2){ ?>
    <div class="schemelink">
        <div class="row ">
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/work_group_multiple_worktype_link.php'"><span>Work
                        Group With Multiple Work Type Link</span></button></div>
                        <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/scheme_worktype_link.php'"><span>Scheme
                        With Work Type Link</span></button></div>
        </div>
        <div class="row">
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/scheme_finyear_link.php'"><span>Scheme
                        With Financial Year Link</span></button></div>
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/scheme_district_link.php'"><span>Scheme
                        With District Link</span></button></div>
        </div>
        <div class="row">
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/scheme_district_link_mobile.php'"><span>Scheme
                        With District Link for Mobile Application</span></button></div>
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/scheme_agency_group_link.php'"><span>Scheme
                        With Agency Group Link</span></button></div>
        </div>
        <div class="row">
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/scheme_stage_work_link.php'"><span>Scheme
                        With Stage Work Link </span></button></div>
                        <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/role_type_wise_scheme_link.php'"><span>Scheme With Role Link</span></button></div>
            
        </div>
        <div class="row">
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/road_work_type_of_improvement_link.php'"><span>Road Type Improvement Link</span></button></div>
                    <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/scheme_masters/stage_wise_amount_breakup.php'"><span>Stage
                        Wise Amount Breakup</span></button></div>
        </div>
    </div>
    <?php } else if (isset($_GET['id']) && base64_decode($_GET['id'])==3){ ?>
    <div class="work">
        <div class="row ">
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/works/work_creation_form.php'"><span>Work
                        Creation Form</span></button></div>
        </div>
    </div>
    <?php }else if (isset($_GET['id']) && base64_decode($_GET['id'])==4){ ?>
    <div class="work">
        <div class="row ">
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/reports/tpwise_workcount.php'"><span>Town Panchayats Wise Work Count</span></button></div>
                        <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/reports/work_name_wise_schemes_rep.php'"><span>Work Group Wise Physical Progress Report</span></button></div>
        </div>
        <div class="row ">
            <div class="col-md-6"><button class="scheme"
                    onClick="window.location='<?php echo $site_data->website_url ; ?>project/reports/work_type_wise_phy_progress.php'"><span>Scheme Wise Physical Progress Report</span></button></div>
        </div>
    </div>
    <?php } else if (isset($_GET['id']) && base64_decode($_GET['id'])==5 || !isset($_GET['id'])){ ?>
    <div class="work">
        <div class="row ">
            <div class="col-md-6"><button class="scheme" onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/admin/UserProfileEntry.php'"><span>User Profile Entry</span></button></div>
            <div class="col-md-6"><button class="scheme" onClick="window.location='<?php echo $site_data->website_url ; ?>project/forms/admin/UserSetting.php'"><span>Change Password</span></button></div>
        </div>
    </div>
    <?php } ?>
</div>
<?php
        // #############

        // PAGE CONTENT END

        // #############

        $ob_output_main_contents = ob_get_contents();
        ob_clean();
        $this->Template($this->getCurrentUserTemplate()!=""?$this->getCurrentUserTemplate():"Template1", "Home", $ob_output_main_contents);
    }
}

$Home = new Home();
$Home->main_content();

?>