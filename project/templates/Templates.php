<?php
require_once __DIR__ . '/HtmlHelper.php';

trait ClassTemplate
{
    use HtmlHelper;

    public function Template($Template = "", $pageTitle = "", $pageContent = "", $breadcrumbs = array(), $extra_args = array())
    {
        if ($Template == "Template1") {
            $this->Template1($pageTitle, $pageContent, $breadcrumbs, $extra_args);
        } else if ($Template == "Plaintemplate") {
            $this->Template2($pageTitle, $pageContent, $breadcrumbs, $extra_args);
        } else if ($Template == "PublicTemplate") {
            $this->Template3($pageTitle, $pageContent, $breadcrumbs, $extra_args);
        }
	
    }

    public function Template1($pageTitle = "", $pageContent = "", $breadcrumbs = array(), $extra_args = array())
    {
        echo $this->Template1_html("HEAD", $pageTitle, $breadcrumbs, $extra_args);
        echo $pageContent;
        echo $this->Template1_html("FOOT", $pageTitle, $breadcrumbs, $extra_args);
    }

    public function Template2($pageTitle = "", $pageContent = "", $breadcrumbs = array(), $extra_args = array())
    {
        echo $this->Template2_html("HEAD", $pageTitle, $breadcrumbs, $extra_args);
        echo $pageContent;
        echo $this->Template2_html("FOOT", $pageTitle, $breadcrumbs, $extra_args);
    }

    public function Template3($pageTitle = "", $pageContent = "", $breadcrumbs = array(), $extra_args = array())
    {
        echo $this->Template3_html("HEAD", $pageTitle, $breadcrumbs, $extra_args);
        echo $pageContent;
        echo $this->Template3_html("FOOT", $pageTitle, $breadcrumbs, $extra_args);
    }



    public function getConfigSubMenu_horizontal_openrole($menuid, $site_data = null)
    {
        $role_code = 32;//$_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'];
        
        $user_language = $this->issetCurrentUserLanguage2D() ? $this->getCurrentUserLanguage2D() : 'en';
        

        $cond = "";
        


        $query = "select * from master.mst_menu_development a , master.mst_menuconfig b  where b.roleid=:role_code and a.menuid=b.menuid and submenuid=:menuid and a.rflag=:rflag and b.isactive=:isactive $cond order by menu_order_no asc";
        $menu = $this->prepare($query, array(
            ":role_code" => $role_code,
            ":menuid" => $menuid,
            ":rflag" => 1,
            ":isactive" => 1
        ), 2);

        if (count($menu) == 0) {
            return array("display_code" => 2, "menuscript" => "");
        } else {

            $menuscript = "<div class='sub-nav'><ul class='sub-nav-group'>";
            foreach ($menu as $key => $menu_row) {

                if ($user_language == 'en')
                    $desc = trim($menu_row["menu_desc"]);
                else if ($user_language == 'ta')
                    $desc = trim($menu_row["menu_desc_ta"]) == "" ? trim($menu_row["menu_desc"]) : trim($menu_row["menu_desc_ta"]);
                else
                    $desc = trim($menu_row["menu_desc"]);

                $url = trim($menu_row["url"]); // echo htmlentities($url);
                $menuid = $menu_row["menuid"];
                $menu_no = $menu_row["menu_no"];
                $target_cond = "";

                if ($menu_row["new_tab"] == 'Y') {
                    $target_cond = "target='_blank'";
                }

                $submenu = $this->getConfigSubMenu_horizontal_openrole($menuid, $site_data);

                if ($submenu['display_code'] == 2 && $url != '') {

                    $menu_url = "'" . $site_data->website_url . "project/" . $url . "'";
                    $menuscript .= "
		<li><a href=$menu_url $target_cond>$menu_no - $desc</a></li>
		"; // $url
                } else if ($submenu['display_code'] == 3) {

                    /*$check_submenu_query = "select count(1) as cnt from master.mst_menu_development a , master.mst_menuconfig b  where b.roleid=:role_code and a.menuid=b.menuid and submenuid=:menuid and a.rflag=:rflag and b.isactive=:isactive $cond";
                $check_submenu = $this->prepare($check_submenu_query, array_merge(array(
                    ":role_code" => $role_code,
                    ":menuid" => $menuid,
                    ":rflag" => 1,
                    ":isactive" => 1
                ), $cond_array), 4);*/

                    //if(0 < $check_submenu['cnt']){

                    /*$menuscript .= "
		<li class='nav-item dropdown'>  
			<a class='dropdown-item dropdown-toggle menu_font' href='javascript:void(0)' id='navbarDropdown1' role='button' data-toggle='dropdown'
            aria-haspopup='true' aria-expanded='false' $target_cond>$menu_no - $desc</a>" .
                        $submenu['menuscript'] .
                        "</li>	
		";*/
                    //}
                }
            }
            $menuscript .= "</ul></div>";
            return array("display_code" => 3, "menuscript" => $menuscript);
        }
    }

public function getConfigSubMenu_horizontal1($menuid, $site_data = null, $sub_menu_config = null)
    {
        $role_code = $_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'];
        $state_code = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();
        $user_language = $this->issetCurrentUserLanguage2D() ? $this->getCurrentUserLanguage2D() : 'en';
        $security_id = $this->getCurrentUserSecurityID();
        $user_profile_id = $this->getCurrentUserProfileID();

        $cond = "";
        $cond_array = array();
        if ($state_code == '' && $dcode == '' && $lbcode == '') {
            $cond = "";
        } else if ($state_code != '' && $dcode == '' && $lbcode == '') {
            $cond = "and b.state_code=:state_code";
            $cond_array[':state_code'] = $state_code;
        } else if ($state_code != '' && $dcode != '' && $lbcode == '') {
            $cond = "and b.state_code=:state_code and b.dcode=:dcode";
            $cond_array[':state_code'] = $state_code;
            $cond_array[':dcode'] = $dcode;
        } else if ($state_code != '' && $dcode != '' && $lbcode != '') {
            $cond = "and b.state_code=:state_code and b.dcode=:dcode and b.lbcode=:lbcode";
            $cond_array[':state_code'] = $state_code;
            $cond_array[':dcode'] = $dcode;
            $cond_array[':lbcode'] = $lbcode;
        }

        $query_exist_user_level = "SELECT count(1) as exist_user_level FROM security.m_role_hierarchy where parent_role=28 and del_flag is null and child_role=:child_role";
        $exist_user_level = $this->prepare($query_exist_user_level, array(
            ":child_role" => $role_code
        ), 4);

        if ($exist_user_level['exist_user_level'] > 0) {

            $query_exist_level_control = "SELECT count(1) as exist_level_control FROM master.mst_menu_user_level_control where role_code=:role_code and security_id=:security_id and user_profile_id=:user_profile_id and menuid=:menuid and isactive=1 and del_flag is null";
            $exist_exist_level_control = $this->prepare($query_exist_level_control, array(
                ":role_code" => $role_code, ":security_id" => $security_id, ":user_profile_id" => $user_profile_id, ":menuid" => $menuid
            ), 4);

            if ($exist_exist_level_control['exist_level_control'] == 0) {
                return array("display_code" => 1, "menuscript" => "");
            }
        }

        $query = "select * from master.mst_menu_development a , master.mst_menuconfig b  where b.roleid=:role_code and a.menuid=b.menuid and submenuid=:menuid and a.rflag=:rflag and b.isactive=:isactive $cond order by menu_order_no asc";
        $menu = $this->prepare($query, array_merge(array(
            ":role_code" => $role_code,
            ":menuid" => $menuid,
            ":rflag" => 1,
            ":isactive" => 1
        ), $cond_array), 2);

        if (count($menu) == 0) {
            return array("display_code" => 2, "menuscript" => "");
        } else {


  if ($sub_menu_config == 1) {
            $menuscript = ' <div class="subnav-content"> <div class="container m-0">';
  }
  else{
	     $menuscript = ' ';
  }
            foreach ($menu as $key => $menu_row) {

                if ($user_language == 'en')
                    $desc = trim($menu_row["menu_desc"]);
                else if ($user_language == 'ta')
                    $desc = trim($menu_row["menu_desc_ta"]) == "" ? trim($menu_row["menu_desc"]) : trim($menu_row["menu_desc_ta"]);
                else
                    $desc = trim($menu_row["menu_desc"]);

                $url = trim($menu_row["url"]); // echo htmlentities($url);
                $menuid = $menu_row["menuid"];
                $menu_no = $menu_row["menu_no"];
                $target_cond = "";

                if ($menu_row["new_tab"] == 'Y') {
                    $target_cond = "target='_blank'";
                }

                $submenu1 = $this->getConfigSubMenu_horizontal1($menuid, $site_data,2);


                if ($submenu1['display_code'] == 2 && $url != '' && $sub_menu_config == 1) {

                    $menu_url = "'" . $site_data->website_url . "project/" . $url . "'";
		$menuscript .= '
		  <div class="col-md-12 sub_menu m-0"><div class="card"><div class="card-header"><a href='.$menu_url.'  '.$target_cond.' title="'.$menu_no .'-'. $desc.'">'.$menu_no .'-'. $desc.'</a></div></div></div>
		'; 
                } 
				else  if ($submenu1['display_code'] == 2 && $sub_menu_config == 2) {

                    $menu_url = "'" . $site_data->website_url . "project/" . $url . "'";
		$menuscript .= '
     <li class="service-content" ><a href='.$menu_url.' class="description col-md-12" title="'.$desc.'">'.$menu_no .' - '.$desc.'
     </a> </li> 
		'; 
                } else if ($submenu1['display_code'] == 3) {

                    $check_submenu_query = "select count(1) as cnt from master.mst_menu_development a , master.mst_menuconfig b  where b.roleid=:role_code and a.menuid=b.menuid and submenuid=:menuid and a.rflag=:rflag and b.isactive=:isactive $cond";
                $check_submenu = $this->prepare($check_submenu_query, array_merge(array(
                    ":role_code" => $role_code,
                    ":menuid" => $menuid,
                    ":rflag" => 1,
                    ":isactive" => 1
                ), $cond_array), 4);
				if($desc == 'Reports' || $desc == 'அறிக்கைகள்'){ 
				$menu_url1 = $site_data->website_url . "project/reports/GeneralReport/Reports.php?id=".base64_encode($menuid);;
				$menuscript .= '
		  <div class="col-md-12 sub_menu"><div class="card"><div class="card-header"><a href='.$menu_url1.'  '.$target_cond.' title="'.$menu_no .'-'. $desc.'">'.$menu_no .'-'. $desc.'</a></div></div></div>
		'; 
				}else{
						$menuscript .= '
		 <div class="col-md-12 sub_menu"><div class="card"><div class="card-header accordion"><a href="#'.$menu_no.'" data-toggle="collapse" aria-expanded="true" '.$target_cond.'>'.$menu_no .'-'. $desc.'</a></div><ul class="panel collapse show" id="'.$menu_no.'" style="columns: auto 2;">'.  
		 $submenu1['menuscript'].
		 '</ul></div></div>
		'; // $url
					
				}
	
		
                    //}
                }
            }
			if ($sub_menu_config == 1) {
            $menuscript .= "</div></div>";
			}
            return array("display_code" => 3, "menuscript" => $menuscript);
        }
    }

    public function getConfigSubMenu_horizontal($menuid, $site_data = null)
    {
        $role_code = $_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'];
		 
        $state_code = $this->getCurrentStateCode();
        $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();
        $user_language = $this->issetCurrentUserLanguage2D() ? $this->getCurrentUserLanguage2D() : 'en';
        $security_id = $this->getCurrentUserSecurityID();
        $user_profile_id = $this->getCurrentUserProfileID();

        $cond = "";
        $cond_array = array();
        if ($state_code == '' && $dcode == '' && $lbcode == '') {
            $cond = "";
        } else if ($state_code != '' && $dcode == '' && $lbcode == '') {
            $cond = "and b.state_code=:state_code";
            $cond_array[':state_code'] = $state_code;
        } else if ($state_code != '' && $dcode != '' && $lbcode == '') {
            $cond = "and b.state_code=:state_code and b.dcode=:dcode";
            $cond_array[':state_code'] = $state_code;
            $cond_array[':dcode'] = $dcode;
        } else if ($state_code != '' && $dcode != '' && $lbcode != '') {
            $cond = "and b.state_code=:state_code and b.dcode=:dcode and b.lbcode=:lbcode";
            $cond_array[':state_code'] = $state_code;
            $cond_array[':dcode'] = $dcode;
            $cond_array[':lbcode'] = $lbcode;
        }

        $query_exist_user_level = "SELECT count(1) as exist_user_level FROM security.m_role_hierarchy where parent_role=28 and del_flag is null and child_role=:child_role";
        $exist_user_level = $this->prepare($query_exist_user_level, array(
            ":child_role" => $role_code
        ), 4);

        if ($exist_user_level['exist_user_level'] > 0) {

            $query_exist_level_control = "SELECT count(1) as exist_level_control FROM master.mst_menu_user_level_control where role_code=:role_code and security_id=:security_id and user_profile_id=:user_profile_id and menuid=:menuid and isactive=1 and del_flag is null";
            $exist_exist_level_control = $this->prepare($query_exist_level_control, array(
                ":role_code" => $role_code, ":security_id" => $security_id, ":user_profile_id" => $user_profile_id, ":menuid" => $menuid
            ), 4);

            if ($exist_exist_level_control['exist_level_control'] == 0) {
                return array("display_code" => 1, "menuscript" => "");
            }
        }

        $query = "select * from master.mst_menu_development a , master.mst_menuconfig b  where b.roleid=:role_code and a.menuid=b.menuid and submenuid=:menuid and a.rflag=:rflag and b.isactive=:isactive $cond order by menu_order_no asc";
        $menu = $this->prepare($query, array_merge(array(
            ":role_code" => $role_code,
            ":menuid" => $menuid,
            ":rflag" => 1,
            ":isactive" => 1
        ), $cond_array), 2);

        if (count($menu) == 0) {
            return array("display_code" => 2, "menuscript" => "");
        } else {

            $menuscript = "<ul class='dropdown-menu dropdown' role='menu'>";
            foreach ($menu as $key => $menu_row) {

                if ($user_language == 'en')
                    $desc = trim($menu_row["menu_desc"]);
                else if ($user_language == 'ta')
                    $desc = trim($menu_row["menu_desc_ta"]) == "" ? trim($menu_row["menu_desc"]) : trim($menu_row["menu_desc_ta"]);
                else
                    $desc = trim($menu_row["menu_desc"]);

                $url = trim($menu_row["url"]); // echo htmlentities($url);
                $menuid = $menu_row["menuid"];
                $menu_no = $menu_row["menu_no"];
                $target_cond = "";

                if ($menu_row["new_tab"] == 'Y') {
                    $target_cond = "target='_blank'";
                }

                $submenu = $this->getConfigSubMenu_horizontal($menuid, $site_data);

                if ($submenu['display_code'] == 2 && $url != '') {

                    $menu_url = "'" . $site_data->website_url . "project/" . $url . "'";
                    $menuscript .= "
		<li role='menuitem'><a class='dropdown-item menu_font' href=$menu_url $target_cond>$menu_no - $desc</a></li>
		";  // $url
                } else if ($submenu['display_code'] == 3) {

                    /*$check_submenu_query = "select count(1) as cnt from master.mst_menu_development a , master.mst_menuconfig b  where b.roleid=:role_code and a.menuid=b.menuid and submenuid=:menuid and a.rflag=:rflag and b.isactive=:isactive $cond";
                $check_submenu = $this->prepare($check_submenu_query, array_merge(array(
                    ":role_code" => $role_code,
                    ":menuid" => $menuid,
                    ":rflag" => 1,
                    ":isactive" => 1
                ), $cond_array), 4);*/

                    //if(0 < $check_submenu['cnt']){

                    $menuscript .= "
		<li role='menuitem' class='nav-item dropdown-submenu' aria-haspopup='true'>  
			<a class='dropdown-item menu_font' href='#' id='navbarDropdown' role='button' data-toggle='dropdown'
            aria-haspopup='true' aria-expanded='false' $target_cond>$menu_no - $desc</a>" .
                        $submenu['menuscript'] .
                        "</li>	
		";
                    //}
                }
            }
            $menuscript .= "</ul>";
            return array("display_code" => 3, "menuscript" => $menuscript);
        }
    }

    public function getConfigSubMenu_sidebar($menuid, $site_data = null)
    {}

    public function menu_loader($part = "", $menu_type = "", $site_data = null, $user_name = "", $pageTitle = "", $breadcrumbs = array())
    {
        if ($menu_type == "sidebar" || $menu_type == "") {
			} else if ($menu_type = "horizontal") {
			
                    if ($part == "HEAD") {}
                    if ($part == "FOOT") {
                    ?>
</div>
<style>
.nav-link.active {
    background-color: #efefef;
    /* color: #2D9DA7 !important; */
    color: #004080 !important;
    font-weight: 600;
    border-radius: 7px;
    /* margin-top: 2px; */
}

hr {
    height: 1px;
    background-color: #ddd;
    border: none;
    margin: 10px !important;
}

.footer_slide {
    width: 90% !important;

}

.page-permission {
    padding: 0;
    border: none !important;
}

.slider {
    width: 100% !important;
}

.table-striped>tbody>tr:nth-child(odd)>td,
.table-striped>tbody>tr:nth-child(odd)>th {
    background-color: white;
}

.table-striped>tbody>tr:nth-child(even)>td,
.table-striped>tbody>tr:nth-child(even)>th {
    background-color: #EFF7FD;
}

.table-striped {
    border-collapse: collapse;
    border-radius: 10px;
    overflow: hidden;
}

.table_div {
    padding: 13px;
    background-color: #efefef;
}

.table_header,
.table_header th {
    border-color: #fff !important;
    color: #fff !important;
    background-color: #15487e !important;
}

tfoot {
    background-color: white;
    font-weight: bold;
}


.header-top {
    /* background: #EC1C23; */
    border-bottom: 1px solid #ddd;
    background: #f2f2f2;
    /* background: #f47176; */
    position: relative;

}

/* .header-top:after {
    background: #2C2B5E;
    background: #f2f2f2;
    position: absolute;
    right: 0;
    top: 0;
    content: "";
    height: 100%;
    width: 36%;
} */

/* .header-top::before {
    background: #fff none repeat scroll 0 0;
    content: "";
    height: 69px;
    position: absolute;
    right: 37.6%;
    top: -20px;
    height: 105px;
    position: absolute;
    right: 37.8%;
    top: -18px;
    transform: rotate(-45deg);
    width: 8px;
    z-index: 1;
} */

.header-top-right {
    position: relative;
    color: black;
}

/* .header-top-right::after {
    background: #2c2b5e none repeat scroll 0 0;
    content: "";
    height: 93px;
    left: -16%;
    position: absolute;
    top: -48px;
    transform: rotate(-45deg);
    width: 59px;
    height: 90px;
    left: -16%;
    position: absolute;
    top: -38px;
    transform: rotate(-45deg);
    width: 60px;
} */

.header-top-right ul li {
    display: inline-block;
    margin-right: 28px;
}

.header-top-right ul li:last-child {
    margin-right: 0px;
}

.header-top-left p,
.header-top-right ul li a {
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    font-family: 'Open Sans', sans-serif;
    color: #fff;
    text-transform: uppercase;
    line-height: 68px;
    position: relative;
    z-index: 1;
}

.header-top-right ul li:hover a {
    color: #EC1C23;
}

.header-area.two .main-menu>ul>li>a {
    color: #303030;
    line-height: 132px;
}

.header-area.two .main-menu>ul>li:hover>a {
    color: #EC1C23;
}

/* @media (min-width: 1920px) {

    .header-top-right::after {
        left: 3%;
    }

    .header-top::before {
        right: 37.5%
    }
}

/* Laptop Device :1366px. */
/* @media (min-width: 1200px) and (max-width: 1500px) {
    .header-top::before {
        right: 38%
    }

    .header-top-right::after {
        left: -19%
    }


} */

/* @media (min-width: 992px) and (max-width: 1200px) {
    .header-top::before {
        right: 32.5%
    }

    .header-top::after {
        width: 30%
    }

    .header-top-right::after {
        left: -5%
    }
} */

@media (min-width: 768px) and (max-width: 991px) {

    .header-top::before {
        right: 35%
    }

    .header-top-right::after {
        left: -15%
    }

    .header-top::after {
        width: 32%
    }
}

@media (max-width: 767px) {

    .header-top::before {
        display: none;
    }

    .header-top-right::after,
    .header-top::before {
        display: none;
    }

    .header-top::after {
        width: 100%
    }

    .header-top-left p,
    .header-top-right ul li a {
        text-align: center;
        line-height: 24px
    }

    .header-top {
        padding: 10px 0
    }

    .header-top-right.text-right {
        text-align: center;
    }

    .header-top-left p,
    .header-top-right ul li a {
        font-size: 12px
    }
}
</style>



</div>

</div>







<footer class="text-center text-white" style="background-color:#2C2B5E;font-size:15px;">
    <!-- <div class="container"> -->

    <div class="text-center text-white mt-4 p-1">
        <p class="mb-0 fw-bold">
        <p>&copy; Contents Owned, Maintained and Updated by, <a class='footer_content_div_anchor'
                href="https://dtp.tn.gov.in/" target="_blank" style="color:white;text-decoration:none">Directorate
                of Town
                Panchayats ( DTP
                )</a></p>
        <p>Developed And Hosted By <a class='footer_content_div_anchor' href="http://www.tn.nic.in/" target="_blank"
                style="color:white;text-decoration:none">National
                Informatics Centre Chennai</a><br>
            <a class='footer_content_div_anchor' href="http://www.tn.nic.in/" target="_blank"
                style="color:white;text-decoration:none">Ministry
                of
                Electronics & Information Technology,</a> Government of India
        </p>
        <p>
            <hr>
        <p>Last Updated: <?php echo date("d-m-Y"); ?></p>
        </p>
        <!-- </div> -->
</footer>

<script type="text/javascript">
$(document).ready(function() {
    <?php 
		if(isset($_GET['loginState']) && base64_decode($_GET['loginState'])=='fail')
		{
		  ?>
    $("#exampleModal").modal('show');
    $(".alert_login_fail").delay(4000).slideUp(200, function() {
        $(this).alert('close');
    });
    <?php 
		}
    if(isset($_GET['login']) && base64_decode($_GET['login'])=='open')
		{
		  ?>
    $("#exampleModal").modal('show');
    <?php 
		}
	?>

});
</script>

<script>
window.addEventListener("pageshow", function(event) {
    var historyTraversal = event.persisted ||
        (typeof window.performance != "undefined" &&
            window.performance.navigation.type === 2);
    if (historyTraversal) {
        // Handle page restore.
        window.location.reload();
    }
});
</script>

<?php
                    }
                }
            }

            public function Template1_html($part = "", $pageTitle = "", $breadcrumbs = array(), $extra_args = array())
            {
              $site_data = $this->siteData();
				
                $menu_type = isset($_SESSION['USER_DETAILS']['USER_PROFILE']['menu_type'])?$_SESSION['USER_DETAILS']['USER_PROFILE']['menu_type']:'horizontal';
				$role_code=$_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'];
                if (!isset($_SESSION['USER_DETAILS'])) {
                    echo "<br><br><center><h3><font color='red'>Session Timeout:Please Login Again</font></center>";
                    $delay = "1";
                    die('<meta http-equiv="refresh" content="' . $delay . ';URL=' . $site_data->website_url . '">');
                }

                $user_name = $_SESSION['USER_DETAILS']['USER_PROFILE']['user_first_name'];
				//var_dump($_SESSION);die;

                if ($part == "HEAD") {
                    if (!isset($_SESSION)) {
                        session_start();
                    }

            ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="format-detection" content="telephone=no" />
    <meta name="description" content="html template">
    <meta name="author" content="uxdt">
    <link href="<?php echo htmlentities($site_data->website_css_path); ?>bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<<<<<<< HEAD
    <script src="<?php echo htmlentities($site_data->website_js_path); ?>bootstrap.bundle.min.js />
    </script>



    <script src="<?php echo htmlentities($site_data->website_js_path); ?>jquery-3.7.1.min.js"></script>
=======
    <script src="<?php echo htmlentities($site_data->website_js_path); ?>jquery-3.7.1.min.js"></script>
    <script src="<?php echo htmlentities($site_data->website_js_path); ?>bootstrap.bundle.min.js">
    </script>
>>>>>>> 1837ae24994e4c2fa6aae1282e5c0c3abf6f5156
    <script src="<?php echo htmlentities($site_data->website_js_path); ?>login.js"> </script>
    <script src="<?php echo htmlentities($site_data->website_js_path); ?>index.js"></script>
    <script src="<?php echo htmlentities($site_data->website_js_path); ?>sha512.js"></script>
    <script src="<?php echo htmlentities($site_data->website_js_path); ?>commonValidation.js"></script>
	<script src="<?php echo htmlentities($site_data->website_js_path); ?>bootstrap.min.js"></script>
<<<<<<< HEAD
    <link rel = "icon" href = "<?php echo htmlentities($site_data->website_image_path); ?>template/assets/images/favicon/favicon.png" / >
=======
    <link rel = "icon" href = "<?php echo htmlentities($site_data->website_image_path); ?>template/assets/images/favicon/favicon.png" />
>>>>>>> 1837ae24994e4c2fa6aae1282e5c0c3abf6f5156
    
    <!-- Include Gijgo Datepicker CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo htmlentities($site_data->website_css_path); ?>customstyle.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link rel="icon"
        href="<?php echo htmlentities($site_data->website_image_path); ?>template/assets/images/favicon/favicon.png" />
    <script type="text/javascript">
    window.onerror = function(msg, url, lineNo, columnNo, error) {
        // ... handle error ...
        return false;
    }
    var website_url = "<?php echo $this->siteData()->website_url; ?>";
      
    </script>
    <style>
    .user_icon {
        color: #00446d;
    }

    .last_login {
        font-size: 11px;
    }

    .nav-link {
        padding: inherit;
    }

    .login_detail {
        margin: 3px 0 0 30px;
    }

    .login_name {
        font-size: 13px;
        font-weight: 600;
        color: #00446d;
    }

    .login_dropdown_menu {
        background: white !important;
        padding-left: 8px;
        border: 2px groove #efefef;
        margin-top: 9px;
        margin-left: 129px;
    }
    </style>

</head>

<body>
    <div id="fb-root"></div>
    <div id="wrapper">
        <!-- Header Area Start -->
        <header class="top">
            <div class="top_head" style="border-bottom: 1px solid #ddd;
    background: #f2f2f2;">
                <div class="container-fluid">
                    <div class="row py-1 px-lg-4 align-items-center justify-content-between tabCenter">
                        <div class="col-md-5">
                            <span class="govt_of_tn">Government of TamilNadu
                            </span>
                        </div>
                        <div class="col-md-4">
                            <form action="javascript:void(0);" autocomplete="off" style="width:60%">
                                <div class="input-group  input-group-sm">

                                    <input type="text" class="form-control" placeholder="Search" aria-label="Search"
                                        aria-describedby="btnGroupAddon" id="search-txt">

                                    <div class="input-group-text" id="btnGroupAddon"
                                        style="padding:3px;border-radius: 0px 3px 3px 0px;border-left: none;">
                                        <button type="submit" class="fa fa-search" onclick="google_search();"
                                            style="outline:none;border:none;padding:3px"></button>
                                    </div>

                                </div>
                            </form>

                        </div>
                        <script>
                        function google_search() {
                            var search_value = $('#search-txt').val();
                            if (search_value != '') {
                                document.location.href =
                                    website_url + '/project/templates/search_page.php#gsc.tab=0&gsc.q=' +
                                    search_value + '';
                            }

                        }
                        </script>


                        <div class="p-1 p-lg-0 col-md-3">
                            <button type="button" class="btn btn-sm"
                                style="background: transparent;border: 1px solid #c1c0c0;color: #464444;">
                                Skip
                                To Main
                                Content
                            </button>

                            <button type="button"
                                class="btn btn-sm btn-outline-secondary decrease-plugin-ac">A-</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary normal-plugin-ac">A</button>
                            <button type="button"
                                class="btn btn-sm btn-outline-secondary increase-plugin-ac">A+</button>
                        </div>
                    </div>


                </div>
            </div>
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container">
                    <a class="navbar-brand d-flex align-items-center mr-0" href="#">
                        <img src="
                                            <?php 
                                            echo htmlentities($site_data->website_image_path); ?>assets/TamilNadu_Logo.png "
                            class=" ipablogo" style="height:80px;width:80px;margin-left:42px"
                            title="Directorate of Town Panchayats, Tamil Nadu" />
                        <div class="nav_heading" style="margin-left:8px">
                            <h5 class="mt-lg-2 font-17" style="color:#0e446d;font-weight:bold;">பேரூராட்சிகள்
                                இயக்ககம், தமிழ்நாடு</h5>
                            <p style="color:black;" class="font-19 h5 mt-2"><b> Directorate of Town Panchayats,
                                    Tamil Nadu</b></p>
                        </div>
                        <div class="nav_heading">
                            <img src="<?php echo htmlentities($site_data->website_image_path); ?>assets/Egovernments.png"
                                class="emblem" title="Digital India" style="width:130px;margin-top:-4px;" />
                    </a>
                </div>
                <div class="nav_heading">
                    <img src="<?php echo htmlentities($site_data->website_image_path); ?>assets/digital-india-c2.png"
                        class="emblem" title="Digital India" style="width:125px;margin-top:-8px" /></a>

                </div>
                </a>
    </div>
    </nav>
    </div>
    <?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

    <nav class="navbar navbar-expand-lg navbar-light" style="background-color:#2C2B5E">
        <div class="container">
            <!-- <a class="navbar-brand" href="#">Your Logo</a> -->
            <!-- Button to toggle the responsive navigation menu -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse text-white" id="navbarSupportedContent" style="height: 18px;;">
                <!-- Right-aligned menu -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (!isset($_GET['id'])) ? 'active' : ''; ?>"
                            href="<?php echo $site_data->website_url ; ?>project/home.php" style="color:white;">
                            <i class="fa fa-home" aria-hidden="true"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($_GET['id']) && base64_decode($_GET['id']) == 5) ? 'active' : ''; ?>"
                            style="color:white;"
                            href="<?php echo $site_data->website_url ; ?>project/home.php?id=<?php echo base64_encode(5); ?>">
                            User Settings</a>
                    </li>
                    <?php if($role_code != 4){ ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($_GET['id']) && base64_decode($_GET['id']) == 1) ? 'active' : ''; ?>"
                            style="color:white;"
                            href="<?php echo $site_data->website_url ; ?>project/home.php?id=<?php echo base64_encode(1); ?>">
                            Scheme Master</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($_GET['id']) && base64_decode($_GET['id']) == 2) ? 'active' : ''; ?>"
                            style="color:white;"
                            href="<?php echo $site_data->website_url ; ?>project/home.php?id=<?php echo base64_encode(2); ?>">
                            Scheme Master Link</a>
                    </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($_GET['id']) && base64_decode($_GET['id']) == 3) ? 'active' : ''; ?>"
                            style="color:white;"
                            href="<?php echo $site_data->website_url ; ?>project/home.php?id=<?php echo base64_encode(3); ?>">
                            Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($_GET['id']) && base64_decode($_GET['id']) == 4) ? 'active' : ''; ?>"
                            style="color:white;"
                            href="<?php echo $site_data->website_url ; ?>project/home.php?id=<?php echo base64_encode(4); ?>">
                            Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="color:white;"
                            href="<?php echo $site_data->website_form_path ; ?>logout.php">
                            Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    </header>
    <?php echo $this->menu_loader($part, $menu_type, $site_data, $user_name, $pageTitle, $breadcrumbs); ?>
    <!--   <div class="container">-->
    <?php
                } else if ($part == "FOOT") {
					
                    echo $this->menu_loader($part, $menu_type, $site_data, $user_name, $pageTitle, $breadcrumbs);
                }
            }

            public function Template2_html($part = "", $pageTitle = "", $breadcrumbs = array(), $extra_args = array())
            {
                $site_data = $this->siteData();
                if(isset($_SESSION['USER_DETAILS']['USER_PROFILE']['menu_type'])){
                $menu_type = $_SESSION['USER_DETAILS']['USER_PROFILE']['menu_type'];
                }else{
                    $menu_type='Plaintemplate';
                }
                if (!isset($_SESSION['USER_DETAILS'])) {
                    echo "<br><br><center><h3><font color='red'>Session Timeout:Please Login Again</font></center>";
                    $delay = "1";
                    die('<meta http-equiv="refresh" content="' . $delay . ';URL=' . $site_data->website_url . '">');
                }
                if(isset($_SESSION['USER_DETAILS']['USER_PROFILE']['menu_type'])){
                $user_name = $_SESSION['USER_DETAILS']['USER_PROFILE']['user_first_name'];
            }else{
                $user_name='test';
            }
                if ($part == "HEAD") {
                    if (!isset($_SESSION)) {
                        session_start();
                    }

                ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="format-detection" content="telephone=no" />
        <meta name="description" content="html template">
        <meta name="author" content="uxdt">
        <link rel="apple-touch-icon"
            href="<?php echo htmlentities($site_data->website_image_path); ?>template/assets/images/favicon/apple-touch-icon.png">
        <link rel="icon"
            href="<?php echo htmlentities($site_data->website_image_path); ?>template/assets/images/favicon/favicon.png">
        <title>Directorate of Town Panchayats, Tamil Nadu பேரூராட்சிகள் இயக்ககம், தமிழ்நாடு </title>

        <link href="<?php echo htmlentities($site_data->website_css_path); ?>template/css/bootstrap.min.css"
            rel="stylesheet">
        <link href="<?php echo htmlentities($site_data->website_css_path); ?>template/css/cubeportfolio.min.css"
            rel="stylesheet">
        <link href="<?php echo htmlentities($site_data->website_css_path); ?>template/css/style.css" rel="stylesheet">
        <!-- <link href="<?php echo htmlentities($site_data->website_css_path); ?>template/css/monthly.css" rel="stylesheet">-->
        <link href="<?php echo htmlentities($site_data->website_css_path); ?>template/css/slick/slick.css"
            rel="stylesheet">
        <link href="<?php echo htmlentities($site_data->website_css_path); ?>template/css/slick/slick-theme.css"
            rel="stylesheet">
        <link href="<?php echo htmlentities($site_data->website_css_path); ?>template/css/sitemap.css"
            rel="stylesheet" />
        <?php /*?>
        <link id="t-colors" href="skins/default.css" rel="stylesheet" /><?php */?>
        <link rel="stylesheet" href="<?php echo htmlentities($site_data->website_css_path); ?>gijgo.datepicker.min.css">
        <link rel="stylesheet" href="<?php  echo htmlentities($site_data->website_css_path);  ?>template1/styles.css">
        <link rel="stylesheet"
            href="<?php echo htmlentities($site_data->website_css_path);  ?>Master_Tax_Form_Common_Validation.css">
        <link rel="stylesheet" href="<?php  echo htmlentities($site_data->website_css_path); ?>/small-business.css">
        <link rel="stylesheet" href="<?php

echo htmlentities($site_data->website_css_path);
?>jquery-ui.css">
        <link rel="stylesheet" href="<?php

echo htmlentities($site_data->website_css_path);
?>dataTables.jqueryui.min.css">
        <link rel="stylesheet" href="<?php

echo htmlentities($site_data->website_css_path);
?>scroller.jqueryui.min.css">

        <link rel="stylesheet" type="text/css" href="<?php

                echo htmlentities($site_data->website_css_path);
                ?>responsive.bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php

                echo htmlentities($site_data->website_css_path);
                ?>responsive.jqueryui.min.css">
        <link rel="stylesheet" href="<?php echo htmlentities($site_data->website_css_path); ?>template/css/style.css">
        <link rel="stylesheet" href="<?php

echo htmlentities($site_data->website_css_path);
?>jquery.multiselect.css">
        <!-- HTML5 shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
      <script src="<?php echo htmlentities($site_data->website_js_path); ?>template/assets/js/html5shiv.js"></script>
      <script src="<?php echo htmlentities($site_data->website_js_path); ?>template/assets/js/respond.min.js"></script>
      <![endif]-->
        <!-- Custom JS for this template -->
        <noscript>
            <link href="<?php echo htmlentities($site_data->website_css_path); ?>template/theme/css/no-js.css"
                type="text/css" rel="stylesheet">
        </noscript>

        <script src="<?php echo htmlentities($site_data->website_js_path); ?>template1/vendor/jquery-2.2.4.min.js">
        </script>

        <script src="<?php echo htmlentities($site_data->website_js_path); ?>template1/popper.min.js">
        </script>
        <script src="<?php echo htmlentities($site_data->website_js_path); ?>template1/bootstrap.min.js">
        </script>
        <script src="<?php echo htmlentities($site_data->website_js_path); ?>template1/owl.carousel.min.js">
        </script>
        <script src="<?php 

echo htmlentities($site_data->website_js_path);
?>template1/metisMenu.min.js"></script>
        <script src="<?php

echo htmlentities($site_data->website_js_path);
?>template1/jquery.slimscroll.min.js"></script>
        <script src="<?php

echo htmlentities($site_data->website_js_path);
?>template1/jquery.slicknav.min.js"></script>


        <!-- others plugins -->
        <script src="<?php

echo htmlentities($site_data->website_js_path);
?>template1/plugins.js"></script>
        <script src="<?php echo htmlentities($site_data->website_js_path); ?>template/assets/js/framework.js">
        </script>
        <script src="<?php echo htmlentities($site_data->website_js_path); ?>template/assets/js/jquery.flexslider.js">
        </script>
        <script src="<?php echo htmlentities($site_data->website_js_path); ?>template/assets/js/font-size.js">
        </script>
        <script src="<?php echo htmlentities($site_data->website_js_path); ?>template/assets/js/swithcer.js">
        </script>
        <script src="<?php echo htmlentities($site_data->website_js_path); ?>template/theme/js/ma5gallery.js">
        </script>
        <script src="<?php echo htmlentities($site_data->website_js_path); ?>template/theme/js/easyResponsiveTabs.js">
        </script>
        <script src="<?php echo htmlentities($site_data->website_js_path); ?>sha512.js"></script>
        <script src="<?php echo htmlentities($site_data->website_js_path); ?>index.js"></script>
        <script src="<?php echo htmlentities($site_data->website_js_path); ?>template/assets/js/bootnavbar.js">
        </script>
        <!-- Start datatable js -->
        <script src="<?php

echo htmlentities($site_data->website_js_path);
?>template1/jquery.dataTables.js"></script>
        <script src="<?php

echo htmlentities($site_data->website_js_path);
?>template1/jquery.dataTables.min.js"></script>
        <script src="<?php

echo htmlentities($site_data->website_js_path);
?>template1/dataTables.bootstrap4.min.js"></script>
        <script src="<?php

echo htmlentities($site_data->website_js_path);
?>template1/dataTables.responsive.min.js"></script>
        <script src="<?php

echo htmlentities($site_data->website_js_path);
?>template1/responsive.bootstrap.min.js"></script>
        <script src="<?php

echo htmlentities($site_data->website_js_path);
?>template/assets/js/scripts.js"></script>
        <script type="text/javascript">
        	var website_url = "<?php echo $this->siteData()->website_url; ?>";
        </script>
        <script src="<?php

echo htmlentities($site_data->website_js_path);
?>template1/vendor/jquery-barcode.js"></script>
        <script src="<?php

                                        echo htmlentities($site_data->website_js_path);
                                        ?>utf.js"></script>
        <script src="<?php

echo htmlentities($site_data->website_js_path);
?>tamil.js"></script>
        <script src="<?php

echo htmlentities($site_data->website_js_path);
?>CommonFunctions.js"></script>

        <script src="<?php

echo htmlentities($site_data->website_js_path);
?>BugReport.js"></script>

        <script src="<?php

echo htmlentities($site_data->website_js_path);
?>Login.js"></script>
        <script src="<?php

echo htmlentities($site_data->website_js_path);
?>gijgo.datepicker.min.js"></script>

        <script type="text/javascript">
        window.onerror = function(msg, url, lineNo, columnNo, error) {
            // ... handle error ...
            return false;
        }
        var DisplayLabelID_JSON = {};

        function UpdateLabel() {
            $("*[DisplayLabelID]").each(function() {
                var datalabelid = 'L' + $(this).attr('DisplayLabelID');
                $(this).text(DisplayLabelID_JSON['Label'][datalabelid]);
            });
        }
        </script>


    </head>

    <body>
        <div id="fb-root"></div>


        <header>
            <div class="region region-header-top">
                <div id="block-cmf-content-header-region-block" class="block block-cmf-content first last odd">
                    <noscript class="no_scr">"JzavaScript is a standard programming language that is
                        included to
                        provide interactive features, Kindly enable Javascript in your browser. For
                        details visit
                        help page"
                    </noscript>
                    <div class="wrapper common-wrapper">
                        <div class="top">
                            <div class="top_head" style="border-bottom: 1px solid #ddd;
    background: #f2f2f2;">
                                <div class="container-fluid">
                                    <div class="row py-1 px-lg-4 align-items-center justify-content-between tabCenter">
                                        <div class="col-md-5">
                                            <span class="govt_of_tn">Government of TamilNadu
                                            </span>
                                        </div>
                                        <div class="col-md-4">
                                            <form action="javascript:void(0);" autocomplete="off" style="width:60%">
                                                <div class="input-group  input-group-sm">

                                                    <input type="text" class="form-control" placeholder="Search"
                                                        aria-label="Search" aria-describedby="btnGroupAddon"
                                                        id="search-txt">

                                                    <div class="input-group-text" id="btnGroupAddon"
                                                        style="padding:3px;border-radius: 0px 3px 3px 0px;border-left: none;">
                                                        <button type="submit" class="fa fa-search"
                                                            onclick="google_search();"
                                                            style="outline:none;border:none;padding:3px"></button>
                                                    </div>

                                                </div>
                                            </form>

                                        </div>
                                        <script>
                                        function google_search() {

                                            var search_value = $('#search-txt').val();
                                            if (search_value != '') {
                                                document.location.href =
                                                   website_url + '/project/templates/search_page.php#gsc.tab=0&gsc.q=' +
                                                    search_value + '';
                                            }

                                        }
                                        </script>


                                        <div class="p-1 p-lg-0 col-md-3">
                                            <button type="button" class="btn btn-sm"
                                                style="background: transparent;border: 1px solid #c1c0c0;color: #464444;">
                                                Skip
                                                To Main
                                                Content
                                            </button>

                                            <button type="button"
                                                class="btn btn-sm btn-outline-secondary decrease-plugin-ac">A-</button>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-secondary normal-plugin-ac">A</button>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-secondary increase-plugin-ac">A+</button>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="navbar navbar-default ">
                                <div class="container headruler">
                                    <div class="col-lg-1 col-md-2 text-center">
                                        <div class="navbar-header" style="width:80px;height:80px;">

                                            <img src="
                                            <?php 
                                            echo htmlentities($site_data->website_image_path); ?>assets/TamilNadu_Logo.png "
                                                class=" ipablogo" style="height:80px;width:80px;margin-left:54px"
                                                title="Directorate of Town Panchayats, Tamil Nadu" />

                                        </div>
                                    </div>
                                    <div class="col-md-9 logo_heading mt-2 mt-md-0 mt-lg-0 px-0">
                                        <h5 class="mb-2"
                                            style="font-family: 'Roboto', sans-serif;font-weight: 700;line-height: 1.1em;color: #0e446d;">
                                            பேரூராட்சிகள் இயக்ககம், தமிழ்நாடு</h5>
                                        <h5 class="goverment hidden-xs"
                                            style="font-family: 'Roboto', sans-serif;font-weight: 700;line-height: 1.1em;">
                                            Directorate of Town Panchayats, Tamil Nadu</h5>

                                    </div>

                                    <div class=" col-md-1 emblem d-md-none d-lg-block">
                                        <div class="navbar-header">
                                            <img src="<?php echo htmlentities($site_data->website_image_path); ?>assets/digital-india-c2.png"
                                                class="emblem" title="Digital India"
                                                style="width:150px;margin-top:-8px" /></a>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-2 emblem">
                                <div class="navbar-header">
                                    <a href="http://digitalindia.gov.in/" rel="noopener noreferrer" target="_blank"
                                        class="page-permission"
                                        aria-label="Government of Tamil Nadu - External site that opens in a new window"><img
                                            src="<?php echo htmlentities($site_data->website_image_path); ?>images/reload.png"
                                            class="emblem" style="height: 100px;" /></a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--Top-Header Section end-->

        </header>
        <!--Top-Header Section end-->


        <?php
                } else if ($part == "FOOT") {
                    echo $this->menu_loader($part, $menu_type, $site_data, $user_name, $pageTitle, $breadcrumbs);
                }
            }

            public function Template3_html($part = "", $pageTitle = "", $breadcrumbs = array(), $extra_args = array())
            {
                $site_data = $this->siteData();
                $user_name = "";

                if ($part == "HEAD") {
                    if (!isset($_SESSION)) {
                        session_start();
                    }

                ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="utf-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
            <meta name="format-detection" content="telephone=no" />
            <meta name="description" content="html template" />
            <meta name="author" content="uxdt" />
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
                integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
                crossorigin="anonymous" />
            <link rel="stylesheet"
                href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
                crossorigin="anonymous" />
            </script>
            <script src="<?php echo htmlentities($site_data->website_js_path); ?>login.js">
            </script>
            <script src="<?php echo htmlentities($site_data->website_js_path); ?>jquery-3.7.1.min.js">
            </script>
            <link rel="icon"
                href="<?php echo htmlentities($site_data->website_image_path); ?>template/assets/images/favicon/favicon.png" />
            <script src="<?php echo htmlentities($site_data->website_js_path); ?>index.js"></script>
            <script src="<?php echo htmlentities($site_data->website_js_path); ?>sha512.js"></script>
            <title>Directorate of Town Panchayats, Tamil Nadu பேரூராட்சிகள் இயக்ககம், தமிழ்நாடு </title>
            <style>
            .nav-link {
                padding: inherit;
            }
            </style>
            <script type="text/javascript">
            $(document).ready(function() {
                <?php 
						if(isset($_GET['loginState']) && base64_decode($_GET['loginState'])=='fail')
						{
						?>
                LoginShow('<?php echo htmlentities($site_data->website_url); ?>',
                    '<?php echo $site_data->website_js_path ?>',
                    '<?php echo $_GET['loginState']; ?>');
                <?php } ?>
            });
            var website_url = "<?php echo $this->siteData()->website_url; ?>";

            function reload() {
                $.ajax({
                    type: 'post',
                    url: "<?php echo $this->siteData()->website_url.'project/ajax/AjaxGeneralPublic.php'; ?>",
                    data: {
                        "cmd": btoa(13)
                    },
                    success: function(data) {
                        if (data != '') {
                            if(isValidURL(data)){
                                $('#captcha').attr('src', data);
                                <?php /*?> $("#captcha-audio-section").find('source').attr("src",
                                    '<?php echo htmlentities($site_data->website_url); ?>library/login_string_audio.php?aud=<?php echo base64_encode(time()); ?>'
                                );
                                $("#captcha-audio-section").find('source').get(0).load();
                                <?php */?>
                            }
                        }
                    },
                    dataType: 'html'
                });
            }
            </script>
        </head>

        <body>
            <div id="fb-root"></div>
            <div id="wrapper">

                <!-- Header Area Start -->
                <header class="top">
                    <div class="top_head" style="border-bottom: 1px solid #ddd;
    background: #f2f2f2;">
                        <div class="container-fluid">
                            <div class="row py-1 px-lg-4 align-items-center justify-content-between tabCenter">
                                <div class="col-md-5">
                                    <span class="govt_of_tn">Government of TamilNadu
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <form action="javascript:void(0);" autocomplete="off" style="width:60%">
                                        <div class="input-group  input-group-sm">

                                            <input type="text" class="form-control" placeholder="Search"
                                                aria-label="Search" aria-describedby="btnGroupAddon" id="search-txt">

                                            <div class="input-group-text" id="btnGroupAddon"
                                                style="padding:3px;border-radius: 0px 3px 3px 0px;border-left: none;">
                                                <button type="submit" class="fa fa-search" onclick="google_search();"
                                                    style="outline:none;border:none;padding:3px"></button>
                                            </div>

                                        </div>
                                    </form>

                                </div>
                                <script>
                                function google_search() {

                                    var search_value = $('#search-txt').val();
                                    if (search_value != '') {
                                        document.location.href =
                                            website_url + '/project/templates/search_page.php#gsc.tab=0&gsc.q=' +
                                            search_value + '';
                                    }

                                }
                                </script>


                                <div class="p-1 p-lg-0 col-md-3">
                                    <button type="button" class="btn btn-sm"
                                        style="background: transparent;border: 1px solid #c1c0c0;color: #464444;">
                                        Skip
                                        To Main
                                        Content
                                    </button>

                                    <button type="button"
                                        class="btn btn-sm btn-outline-secondary decrease-plugin-ac">A-</button>
                                    <button type="button"
                                        class="btn btn-sm btn-outline-secondary normal-plugin-ac">A</button>
                                    <button type="button"
                                        class="btn btn-sm btn-outline-secondary increase-plugin-ac">A+</button>
                                </div>
                            </div>


                        </div>
                    </div>
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <div class="container">
                            <a class="navbar-brand d-flex align-items-center mr-0" href="#">
                                <img src="
                                            <?php 
                                            echo htmlentities($site_data->website_image_path); ?>assets/TamilNadu_Logo.png "
                                    class=" ipablogo" style="height:80px;width:80px;margin-left:42px"
                                    title="Directorate of Town Panchayats, Tamil Nadu" />
                                <div class="nav_heading" style="margin-left:8px">
                                    <h5 class="mt-lg-2 font-17" style="color:#0e446d;font-weight:bold;">பேரூராட்சிகள்
                                        இயக்ககம், தமிழ்நாடு</h5>
                                    <p style="color:black;" class="font-19 h5 mt-2"><b> Directorate of Town Panchayats,
                                            Tamil Nadu</b></p>
                                </div>
                                <div class="nav_heading">
                                    <img src="<?php echo htmlentities($site_data->website_image_path); ?>assets/Egovernments.png"
                                        class="emblem" title="Digital India" style="width:130px;margin-top:-4px;" />
                            </a>
                        </div>
                        <div class="nav_heading">
                            <img src="<?php echo htmlentities($site_data->website_image_path); ?>assets/digital-india-c2.png"
                                class="emblem" title="Digital India" style="width:125px;margin-top:-8px" /></a>

                        </div>
                        </a>
            </div>
            </nav>
            </div>
            <?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

            <nav class="navbar navbar-expand-lg navbar-light" style="background-color:#2C2B5E">
                <div class="container">
                    <!-- <a class="navbar-brand" href="#">Your Logo</a> -->
                    <!-- Button to toggle the responsive navigation menu -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse justify-content-end text-white" id="navbarSupportedContent"
                        style="height: 18px;">
                        <!-- Right-aligned menu -->
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>"
                                    href="<?php echo htmlentities($site_data->website_url); ?>" style="color:white;">
                                    <i class="fa fa-home" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($current_page == 'ContactUs.php') ? 'active' : ''; ?>"
                                    href="<?php echo htmlentities($site_data->website_url); ?>project/reports/public/ContactUs.php"
                                    style="color:white;">
                                    Contact Us
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($current_page == 'login.php') ? 'active' : ''; ?>"
                                    style="color:white;" href="#"
                                    onClick="LoginShow('<?php echo htmlentities($site_data->website_url); ?>', '<?php echo $site_data->website_js_path ?>')">
                                    Login
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            </header>
            </div>
            <?php
			$script_filename=basename($_SERVER['SCRIPT_FILENAME']);
			if($script_filename != 'index.php'){                
        ?>
            <section id="fontSize" class="buttons mt-3">
                <div class="bg-wrapper inner-wrapper">
                    <div class="breadcam-bg breadcam">
                        <?php

        $page_url = $_SERVER['PHP_SELF'];
        $File_name = explode('project/', $page_url)[1];

        if($script_filename == 'Disclaimer.php' || $script_filename == 'ScreenReader.php' || $script_filename == 'WebInformationManager.php' || $script_filename == 'WebsitePolicies.php'){
            $breadcam_name='';
            if($script_filename == 'Disclaimer.php'){
                $breadcam_name='Disclaimer';
            } else if($script_filename == 'ScreenReader.php'){
                $breadcam_name='Screen-Reader';
            } else if($script_filename == 'WebInformationManager.php'){
                $breadcam_name='Web Information Manager';
            } else if($script_filename == 'WebsitePolicies.php'){
                $breadcam_name='Website policies';
            }

            $site_url=($this->getCurrentRole()!=32)?$site_data->website_url."project/home.php":$site_data->website_url;
        ?>
                        <div class="container">
                            <div class="row breadcrumbruler  p-3">
                                <div class="col-lg-9">
                                    <ul class="breadcrumb">
                                        <li><a href="index.php" class="breadcrumb_text_color">Home</a><i
                                                class="icon-angle-right"></i></li>
                                        <li><a href="#"
                                                style="text-decoration: none;cursor:auto;"><?php echo htmlentities($breadcam_name); ?></a>
                                        </li>
                                    </ul>
                                </div>
                                <?php
        } else if(is_array($this->Get_Menu_File_Name_Details($File_name,32)) && count($this->Get_Menu_File_Name_Details($File_name,32)) > 0) {
            $Menu_Bread_Crums = $this->Get_Menu_File_Name_Details($File_name,32);

            $site_url=($this->getCurrentRole()!=32)?$site_data->website_url."project/home.php":$site_data->website_url;
        ?>
                                <div class="container">
                                    <div class="row breadcrumbruler">
                                        <div class="col-lg-9">
                                            <ul class="breadcrumb">
                                                <li><a href="<?php echo htmlentities($site_data->website_url);?>"
                                                        class="breadcrumb_text_color">Home</a><i
                                                        class="icon-angle-right"></i></li>
                                                <?php
                foreach (array_reverse($Menu_Bread_Crums['Data']) as $Menu_Bread_Crums_row => $Menu_Bread_Crums_res) {
                ?>
                                                <li><a href="#"
                                                        style="text-decoration: none;cursor:auto;"><?php echo htmlentities($Menu_Bread_Crums_res); ?></a>
                                                </li>
                                                <?php
                }
                ?>
                                            </ul>
                                        </div>


                                        <div class="col-md-3 text-center">
                                            <div class="row" style="background-color:#fff;    border: 1px solid rgba(0, 0, 0, .125);
    border-radius: 0.25rem;"> 
                                                <div class="col-md-9"
                                                    style="vertical-align: middle;line-height: normal; margin:auto; padding: .85rem;">
                                                    <span><?php echo htmlentities($Menu_Bread_Crums['Data_Details']); ?></span>
                                                </div>

                                                <div class="col-md-3 p-0"
                                                    style="vertical-align:middle;line-height:normal;margin:auto;">
                                                    <?php

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $link = "https";
else
    $link = "http";

// Here append the common URL characters.
$link .= "://";

// Append the host(domain name, ip) to the URL.
$link .= $_SERVER['HTTP_HOST'];

// Append the host(domain name, ip) to the URL.
if ($_SERVER['SERVER_PORT'] != "80" && $_SERVER['SERVER_PORT'] != "443")
    $link .= ":" . $_SERVER['HTTP_HOST'];

// Append the requested resource location to the URL
$link .= $_SERVER['REQUEST_URI'];

// Print the link
$pageUrl = $link;

$role = $this->getCurrentRole();
$basePath = $this->siteData()->website_url;
$pageUrl = strtok($pageUrl, '?');
$pageUrl = str_replace($basePath, "", $pageUrl);


$sql_help = "SELECT faq_question,faq_answer FROM master.m_page_url as a left join master.m_page_faq as b on a.page_url_id=b.page_url_id where a.page_url=:page_url and (b.role_code  &&  ARRAY[:role]::integer[])=true;";
$sql_help_res = $this->prepare($sql_help, array(":page_url" => $pageUrl, ":role" => $role), 2);

if (count($sql_help_res) > 0) {
?>

                                                    <button onClick="HelpFaq('<?php

                                echo htmlentities($site_data->website_url);
                                ?>','<?php

    echo htmlentities($site_data->website_js_path); ?>')" id="page_help" name="page_help" data-toggle="tooltip"
                                                        title="help"
                                                        class="btn btn-sm btn-success m-0  font-weight-bold"><i
                                                            class="fa fa-question-circle"
                                                            aria-hidden="true"></i></button>
                                                    <?php }  ?>
                                                    <button id="Load_Master_Data" name="Load_Master_Data"
                                                        data-toggle="tooltip" title="Reload Master Data"
                                                        class="btn btn-sm btn-success m-0  font-weight-bold"
                                                        style="display:none;"><i class="fa fa-refresh"
                                                            aria-hidden="true"></i></button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        /*
                 * if($Menu_Bread_Crums['report_type']=='F'){?>
                        * <div class="row m-2">
                            * <div class="col-md-10">
                                *
                                * </div>
                            * <div class="col-md-2 text-right font-weight-bold">
                                * <input type="button" id="Load_Master_Data" name="Load_Master_Data"
                                    value="Reload Master Data"
                                    class="btn btn-sm btn-success pull-right font-weight-bold" style="display:none;" />
                                * </div>
                            * </div>
                        * <script type="text/javascript">
                        * $(document).ready(function() {
                            * //if (typeof $('.Reload_Local_Stroage')[0] !== typeof undefined && $('.Reload_Local_Stroage')[0] !== false)
                            * // {
                            * // $('#Load_Master_Data').show();
                            * // }
                            * // else
                            * // {
                            * // $('#Load_Master_Data').hide();
                            * // }
                            *
                            *
                            if (typeof $('#page_lable_id')[0] !== typeof undefined && $(
                                    '#page_lable_id')[0] !== false)
                                *
                                {
                                    * $('#Load_Master_Data').show();
                                    *
                                }
                                *
                                else
                                    *{
                                        * $('#Load_Master_Data').hide();
                                        *
                                    }
                                    *
                        });
                        *
                        </script>
                        * <?php }
                 */
                        ?>
                        <?php
                    }
                    ?>

            </section>
            <?php } ?>
        </body>
        <?php
                    } else if ($part == "FOOT") {
                        echo $this->menu_loader($part, "horizontal", $site_data, null, $pageTitle, $breadcrumbs);
                    }
                    ?>

        </html>
        <?php
                }

                public function Get_Menu_File_Name_Details($url='',$rolecode='')
                {
                    $role_code = ($rolecode == '')?$_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code']:$rolecode;
                    $state_code = $this->getCurrentStateCode();
                    $dcode = $this->getCurrentDistrictCode();
                    $lbcode = $this->getCurrentLocalBodyCode();
                    $user_language = $this->issetCurrentUserLanguage2D() ? $this->getCurrentUserLanguage2D() : 'en';

                    $cond = "";
                    if ($state_code == '' && $dcode == '' && $lbcode == '') {
                        $cond = "";
                    } else if ($state_code != '' && $dcode == '' && $lbcode == '') {
                        $cond = "and b.state_code='$state_code'";
                    } else if ($state_code != '' && $dcode != '' && $lbcode == '') {
                        $cond = "and b.state_code='$state_code' and b.dcode='$dcode'";
                    } else if ($state_code != '' && $dcode != '' && $lbcode != '') {
                        $cond = "and b.state_code='$state_code' and b.dcode='$dcode' and b.lbcode='$lbcode'";
                    }

                    $Menu_Level_Text = array();
                    $sel_menu_file_details = "SELECT a.menuid,a.submenuid,a.menu_desc,a.menu_desc_ta,a.menu_no,(CASE WHEN a.report_no='F' THEN 'Form Number' WHEN a.report_no='R' THEN 'Report Number' END) as report_no,report_no as report_type FROM master.mst_menu_development a , master.mst_menuconfig b where b.roleid=:role_code and a.menuid=b.menuid and a.rflag=:rflag and b.isactive=:isactive and lower(trim(a.url)) like lower(trim('%" . $url . "%'))";

                    $sel_menu_file_details_res = $this->prepare($sel_menu_file_details, array(
                        ":role_code" => $role_code,
                        ":rflag" => 1,
                        ":isactive" => 1 /* ,":url"=>$url */
                    ), 4);

                    if (isset($sel_menu_file_details_res['menuid']) && $sel_menu_file_details_res['menuid'] != '') {
                        // $Menu_Level_Text[]=$sel_menu_file_details_res['menu_no'].'-'.$sel_menu_file_details_res['menu_desc'];

                        if ($user_language == 'en')
                            $Menu_Level_Text['Data'][] = $sel_menu_file_details_res['menu_desc'];
                        else if ($user_language == 'ta')
                            $Menu_Level_Text['Data'][] = trim($sel_menu_file_details_res["menu_desc_ta"]) == "" ? trim($sel_menu_file_details_res["menu_desc"]) : trim($sel_menu_file_details_res["menu_desc_ta"]);
                        else
                            $Menu_Level_Text['Data'][] = $sel_menu_file_details_res['menu_desc'];

                        $Menu_Level_Text['Data_Details'] = $sel_menu_file_details_res['report_no'] . ' : ' . $sel_menu_file_details_res['menu_no'];
                        $Menu_Level_Text['report_type'] = $sel_menu_file_details_res['report_type'];

                        return $this->Check_Prent_Exist($sel_menu_file_details_res['submenuid'], $Menu_Level_Text);
                    }
                }

                public function Check_Prent_Exist($submenuid, $Menu_Level_Text)
                {
                    $role_code = $_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'];
                    $state_code = $this->getCurrentStateCode();
                    $dcode = $this->getCurrentDistrictCode();
                    $lbcode = $this->getCurrentLocalBodyCode();
                    $user_language = $this->issetCurrentUserLanguage2D() ? $this->getCurrentUserLanguage2D() : 'en';
                    $security_id = $this->getCurrentUserSecurityID();
                    $user_profile_id = $this->getCurrentUserProfileID();

                    $cond = "";
                    if ($state_code == '' && $dcode == '' && $lbcode == '') {
                        $cond = "";
                    } else if ($state_code != '' && $dcode == '' && $lbcode == '') {
                        $cond = "and b.state_code='$state_code'";
                    } else if ($state_code != '' && $dcode != '' && $lbcode == '') {
                        $cond = "and b.state_code='$state_code' and b.dcode='$dcode'";
                    } else if ($state_code != '' && $dcode != '' && $lbcode != '') {
                        $cond = "and b.state_code='$state_code' and b.dcode='$dcode' and b.lbcode='$lbcode'";
                    }

                    $query_exist_user_level = "SELECT count(1) as exist_user_level FROM security.m_role_hierarchy where parent_role=28 and del_flag is null and child_role=:child_role";
                    $exist_user_level = $this->prepare($query_exist_user_level, array(
                        ":child_role" => $role_code
                    ), 4);

                    if ($exist_user_level['exist_user_level'] > 0) {

                        $query_exist_level_control = "SELECT count(1) as exist_level_control FROM master.mst_menu_user_level_control where role_code=:role_code and security_id=:security_id and user_profile_id=:user_profile_id and menuid=:menuid and isactive=1 and del_flag is null";
                        $exist_exist_level_control = $this->prepare($query_exist_level_control, array(
                            ":role_code" => $role_code, ":security_id" => $security_id, ":user_profile_id" => $user_profile_id, ":menuid" => $submenuid
                        ), 4);

                        if ($exist_exist_level_control['exist_level_control'] == 0) {
                            return NULL;
                        }
                    }

                    $sel_menu_file_details = "SELECT a.menuid,a.submenuid,a.menu_desc,a.menu_desc_ta,a.menu_no FROM master.mst_menu_development a , master.mst_menuconfig b where b.roleid=:role_code and a.menuid=b.menuid and a.rflag=:rflag and b.isactive=:isactive and b.menuid=:submenuid";
                    $sel_menu_file_details_res = $this->prepare($sel_menu_file_details, array(
                        ":role_code" => $role_code,
                        ":rflag" => 1,
                        ":isactive" => 1,
                        ":submenuid" => $submenuid
                    ), 4);
                    // $Menu_Level_Text[]=$sel_menu_file_details_res['menu_no'].'-'.$sel_menu_file_details_res['menu_desc'];
                    if (isset($sel_menu_file_details_res['menu_desc']) && $sel_menu_file_details_res['menu_desc'] != '') {

                        if ($user_language == 'en')
                            $Menu_Level_Text['Data'][] = $sel_menu_file_details_res['menu_desc'];
                        else if ($user_language == 'ta')
                            $Menu_Level_Text['Data'][] = trim($sel_menu_file_details_res["menu_desc_ta"]) == "" ? trim($sel_menu_file_details_res["menu_desc"]) : trim($sel_menu_file_details_res["menu_desc_ta"]);
                        else
                            $Menu_Level_Text['Data'][] = $sel_menu_file_details_res['menu_desc'];
                    }

                    if (isset($sel_menu_file_details_res['submenuid']) && $sel_menu_file_details_res['submenuid'] != 0) {
                        $sel_parent_menu_file_details = "SELECT a.menuid,a.submenuid,a.menu_desc,a.menu_desc_ta,a.menu_no FROM master.mst_menu_development a , master.mst_menuconfig b where b.roleid=:role_code and a.menuid=b.menuid and a.rflag=:rflag and b.isactive=:isactive and b.menuid=:menuid";
                        $sel_parent_menu_file_details_res = $this->prepare($sel_parent_menu_file_details, array(
                            ":role_code" => $role_code,
                            ":rflag" => 1,
                            ":isactive" => 1,
                            ":menuid" => $sel_menu_file_details_res['submenuid']
                        ), 4);

                        // $Menu_Level_Text[]=$sel_parent_menu_file_details_res['menu_no'].'-'.$sel_parent_menu_file_details_res['menu_desc'];
                        if (isset($sel_parent_menu_file_details_res['menu_desc']) && $sel_parent_menu_file_details_res['menu_desc'] != '') {
                            if ($user_language == 'en')
                                $Menu_Level_Text['Data'][] = $sel_parent_menu_file_details_res['menu_desc'];
                            else if ($user_language == 'ta')
                                $Menu_Level_Text['Data'][] = trim($sel_parent_menu_file_details_res["menu_desc_ta"]) == "" ? trim($sel_parent_menu_file_details_res["menu_desc"]) : trim($sel_parent_menu_file_details_res["menu_desc_ta"]);
                            else
                                $Menu_Level_Text['Data'][] = $sel_parent_menu_file_details_res['menu_desc'];
                        }
                        if (isset($sel_parent_menu_file_details_res['submenuid']) && $sel_parent_menu_file_details_res['submenuid'] != 0) {
                            $Temp_Menu_Level_Text = array();
                            $Temp_Menu_Level_Text = $this->Check_Prent_Exist($sel_parent_menu_file_details_res['submenuid'], $Menu_Level_Text);
                            array_map($Menu_Level_Text, $Temp_Menu_Level_Text);
                        }
                    }

                    return $Menu_Level_Text;
                }
            }
                        ?>