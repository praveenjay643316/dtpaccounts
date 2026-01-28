<?php
require_once __DIR__ . '/HtmlHelper.php';

?>

<?php
#print_r($_SESSION);
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
                ":role_code" => $role_code,
                ":security_id" => $security_id,
                ":user_profile_id" => $user_profile_id,
                ":menuid" => $menuid
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
            } else {
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

                $submenu1 = $this->getConfigSubMenu_horizontal1($menuid, $site_data, 2);


                if ($submenu1['display_code'] == 2 && $url != '' && $sub_menu_config == 1) {

                    $menu_url = "'" . $site_data->website_url . "project/" . $url . "'";
                    $menuscript .= '
          <div class="col-md-12 sub_menu m-0"><div class="card"><div class="card-header"><a href=' . $menu_url . '  ' . $target_cond . ' title="' . $menu_no . '-' . $desc . '">' . $menu_no . '-' . $desc . '</a></div></div></div>
        ';
                } else if ($submenu1['display_code'] == 2 && $sub_menu_config == 2) {

                    $menu_url = "'" . $site_data->website_url . "project/" . $url . "'";
                    $menuscript .= '
     <li class="service-content" ><a href=' . $menu_url . ' class="description col-md-12" title="' . $desc . '">' . $menu_no . ' - ' . $desc . '
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
                    if ($desc == 'Reports' || $desc == 'அறிக்கைகள்') {
                        $menu_url1 = $site_data->website_url . "project/reports/GeneralReport/Reports.php?id=" . base64_encode($menuid);
                        ;
                        $menuscript .= '
          <div class="col-md-12 sub_menu"><div class="card"><div class="card-header"><a href=' . $menu_url1 . '  ' . $target_cond . ' title="' . $menu_no . '-' . $desc . '">' . $menu_no . '-' . $desc . '</a></div></div></div>
        ';
                    } else {
                        $menuscript .= '
         <div class="col-md-12 sub_menu"><div class="card"><div class="card-header accordion"><a href="#' . $menu_no . '" data-toggle="collapse" aria-expanded="true" ' . $target_cond . '>' . $menu_no . '-' . $desc . '</a></div><ul class="panel collapse show" id="' . $menu_no . '" style="columns: auto 2;">' .
                            $submenu1['menuscript'] .
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
                ":role_code" => $role_code,
                ":security_id" => $security_id,
                ":user_profile_id" => $user_profile_id,
                ":menuid" => $menuid
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
    {
    }

    public function menu_loader($part = "", $menu_type = "", $site_data = null, $user_name = "", $pageTitle = "", $breadcrumbs = array())
    {
        if ($menu_type == "sidebar" || $menu_type == "") {
        } else if ($menu_type = "horizontal") {

            if ($part == "HEAD") {
            }
            if ($part == "FOOT") {
                ?>
                    </div>
                    <style>
                        .nav-link.active {
                            /* background-color: #efefef; */
                            padding: 6px;
                            background-color: white;
                            color: #04bebe !important;
                            /* color: #004080 !important; */
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
                                                                background: #04bebe;
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
                                                                background: #04bebe none repeat scroll 0 0;
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







                    <div class="footer-section">
                        <div class="container">
                            <div class="row text-center">
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
                            </div>
                        </div>

                        <div class="footer-bottom-area">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="copyright-description text-center" data-cues="fadeIn">
                                            <p>Last Updated: <?php echo date("d-m-Y"); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script type="text/javascript">
                        $(document).ready(function () {
                            <?php
                            if (isset($_GET['loginState']) && base64_decode($_GET['loginState']) == 'fail') {
                                ?>
                                $("#exampleModal").modal('show');
                                $(".alert_login_fail").delay(4000).slideUp(200, function () {
                                    $(this).alert('close');
                                });
                            <?php
                            }
                            if (isset($_GET['login']) && base64_decode($_GET['login']) == 'open') {
                                ?>
                                $("#exampleModal").modal('show');
                            <?php
                            }
                            ?>

                        });
                    </script>
                    <?php
                    //print_r($_SESSION);  
                    // Commented For Label unlink
                    
                   /* if ($this->issetCurrentUserProfileID()) { ?>
                    <script src="<?php echo htmlentities($site_data->website_js_path); ?>Master_Data_Local_Storage.js"></script>
                    <?php
                    } else {
                    ?>
                    <script src="<?php echo htmlentities($site_data->website_js_path); ?>Master_Data_Local_Storage_Public.js"></script>
                    <?php
                    } */
                    ?>

                    <script>
                        window.addEventListener("pageshow", function (event) {
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

        $menu_type = isset($_SESSION['USER_DETAILS']['USER_PROFILE']['menu_type']) ? $_SESSION['USER_DETAILS']['USER_PROFILE']['menu_type'] : 'horizontal';
        $role_code = $_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'];
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
                <link href="<?php echo htmlentities($site_data->website_css_path); ?>style.css" rel="stylesheet" />
                <script src="<?php echo htmlentities($site_data->website_js_path); ?>jquery-3.7.1.min.js"></script>
                <script src="<?php echo htmlentities($site_data->website_js_path); ?>bootstrap.bundle.min.js">
                </script>
                <script src="<?php echo htmlentities($site_data->website_js_path); ?>login.js"> </script>
                <script src="<?php echo htmlentities($site_data->website_js_path); ?>index.js"></script>
                <script src="<?php echo htmlentities($site_data->website_js_path); ?>sha512.js"></script>
                <script src="<?php echo htmlentities($site_data->website_js_path); ?>commonValidation.js"></script>
                <!-- <script src="<?php echo htmlentities($site_data->website_js_path); ?>bootstrap.min.js"></script> -->

                <link rel="icon"
                    href="<?php echo htmlentities($site_data->website_image_path); ?>template/assets/images/favicon/favicon.png" />


                <!-- Include Gijgo Datepicker CSS and JS -->
                <link href="https://cdn.jsdelivr.net/npm/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
                <link href="<?php echo htmlentities($site_data->website_css_path); ?>customstyle.css" rel="stylesheet"
                    type="text/css" />
                <script src="https://cdn.jsdelivr.net/npm/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
                <link rel="icon" href="<?php echo htmlentities($site_data->website_image_path); ?>template/assets/images/favicon/favicon.png" />
                <link href="<?php echo htmlentities($site_data->website_css_path); ?>DataTables/datatables.min.css" rel="stylesheet">
                <script src="<?php echo htmlentities($site_data->website_js_path); ?>DataTables/datatables.min.js"></script>
                <script type="text/javascript">
                    window.onerror = function (msg, url, lineNo, columnNo, error) {
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

.dropdown-btn {
    background-color: #fff;
    color: #000;
    /* padding: 5px; */
    cursor: pointer;
    overflow: hidden;
    -webkit-user-select: none;
    -ms-user-select: none;
    user-select: none;
    box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
                rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    border-radius: 30px;
    /* display: flex; */
    /* align-items: center; */
    font-weight: 700;
    width: 212px;
}

.dropdown-btn img {
    background: #d9d9d9;
    height: 50px;
    margin-right: 1rem;
    border-radius: 50%;
}

.dropdown-area {
    width: 170px;
    transform: translateY(-35px);
    opacity: 0;
    pointer-events: none;
    z-index: 1;
    position: absolute;
    background: #fff;
}

.designation {
    font-size: 12px;
    color: #686464;
}

.activeDropArea {
    opacity: 1;
    pointer-events: all;
    transform: translateY(2px);
}

.dropdown-area ul {
    padding: 0;
    list-style: none;
    margin-bottom: 0;
}

.dropdown-area ul li {
    border: 1px solid #cfcfcf;
    padding: 2px;
    cursor: pointer;
    border-radius: 2px;
    margin-bottom: 1px;
}

.dropdown-area ul li:nth-child(1) {
    background-color: #f3f3f3;
    margin-top: -20px;
    transition: .2s;
    border-top: none;
}

.dropdown-area ul li:nth-child(2) {
    background-color: #e6e6e6;
    margin-top: -40px;
    transition: .3s;
}

.dropdown-area ul li:nth-child(3) {
    background-color: #cacaca;
    margin-top: -40px;
    transition: .4s;
    color: #fff;
}

.dropdown-area ul li:nth-child(4) {
    background-color: #bfbfbf;
    margin-top: -40px;
    transition: .5s;
    color: #fff;
}

.activeDropArea ul li:nth-child(1),
.activeDropArea ul li:nth-child(2),
.activeDropArea ul li:nth-child(3),
.activeDropArea ul li:nth-child(4) {
    margin-top: 0;
}

.dropdown-area ul li a {
    display: block;
    text-decoration: none;
    font-size: 1rem;
    float: unset;
    padding: 6px 16px;
    color: #000;
    font-weight: 600;
}

.dashboard-btns {
    box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
    background-color: #033c3a;
}

.rdweb-btn {
    color: #fff;
    font-size: 16px;
    font-weight: 500;
    border-radius: 5px;
    transition: 300ms all;
    margin: 4px 0;
    text-decoration: none;
    text-align: center;
    padding: 0px 5px 0 0;
    display: flex;
    align-items: center;
}

.home-btn {
    background-color: white;
    color: #1e7875;
}

.dashboard-btn {
    background: #40bff5;
}

.rdweb-btn span {
    padding: 0 5px;
    font-weight: bold;
}

.rdweb-btn .img-block {
    width: 40px;
    text-align: center;
    margin: 0 5px 0 0;
    background-color: #e7e7e7;
    padding: 10px;
    border-radius: 5px;
}

#inner-headline ul.breadcrumb {
    margin: 30px 0 0;
    float: left;
}

#inner-headline ul.breadcrumb li {
    margin-bottom: 0;
    padding-bottom: 0;
    font-size: 13px;
    color: #fff;
    font-weight: 600;
}

#inner-headline ul.breadcrumb li i {
    color: #fff;
}

#inner-headline ul.breadcrumb li a {
    color: #fff;
}

ul.breadcrumb li a:hover {
    text-decoration: none;
}

.breadcrumbruler li a {
    text-decoration: none;
    color: black;
}

a {
    color: black;
}

.breadcrumbruler {
    margin-left: -30px !important;
    box-sizing: border-box;
}

.breadcrumb_text_color {
    color: #bb3131 !important;
}

/* Master dropdown / menu */

.master-dropdown {
    position: relative;         /* reference for the dropdown */
    display: inline-block;      /* shrink to button width */
}

.master-menu {
    position: absolute;
    top: 100%;                  /* just below the button */
    left: 50%;                  /* center point of the button wrapper */
    transform: translateX(-50%);/* center horizontally */

    width: max-content;         /* fit content width */
    max-width: 70vw;            /* don't exceed viewport */
    box-sizing: border-box;

    z-index: 9999;
    background: #fff;
    border: 1px solid #dcdcdc;
    border-radius: 10px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    padding: 10px;

    max-height: 70vh;
    overflow: auto;
    display: none;              /* hidden by default */
}

.master-dropdown:hover .master-menu {
    display: block;
}

.master_button {
    background: transparent;
    color: #fff;
    font-weight: bold;
    border: none;
    padding: 8px 16px;
    display: inline-block;
    margin-left: 0;
    cursor: pointer;
}

.master-dropdown:hover .master_button {
    color: grey;
}

/* Scrollbar helpers */

.scroll-wrapper {
    overflow: hidden !important;
    padding: 0 !important;
    position: relative;
}

.scroll-wrapper > .scroll-content {
    border: none !important;
    box-sizing: content-box !important;
    height: auto;
    left: 0;
    margin: 0;
    max-height: none;
    max-width: none !important;
    overflow: scroll !important;
    padding: 0;
    position: relative !important;
    top: 0;
    width: auto !important;
}

.scroll-wrapper > .scroll-content::-webkit-scrollbar {
    height: 0;
    width: 0;
}

.scroll-element {
    display: none;
}

.scroll-element,
.scroll-element div {
    box-sizing: content-box;
}

.scroll-element.scroll-x.scroll-scrollx_visible,
.scroll-element.scroll-y.scroll-scrolly_visible {
    display: block;
}

.scroll-element .scroll-bar,
.scroll-element .scroll-arrow {
    cursor: default;
}

.scroll-textarea {
    border: 1px solid #cccccc;
    border-top-color: #999999;
}

.scroll-textarea > .scroll-content {
    overflow: hidden !important;
}

.scroll-textarea > .scroll-content > textarea {
    border: none !important;
    box-sizing: border-box;
    height: 100% !important;
    margin: 0;
    max-height: none !important;
    max-width: none !important;
    overflow: scroll !important;
    outline: none;
    padding: 2px;
    position: relative !important;
    top: 0;
    width: 100% !important;
}

.scroll-textarea > .scroll-content > textarea::-webkit-scrollbar {
    height: 0;
    width: 0;
}

/*************** SIMPLE INNER SCROLLBAR ***************/

.scrollbar-inner > .scroll-element,
.scrollbar-inner > .scroll-element div {
    border: none;
    margin: 0;
    padding: 0;
    position: absolute;
    z-index: 10;
}

.scrollbar-inner > .scroll-element div {
    display: block;
    height: 100%;
    left: 0;
    top: 0;
    width: 100%;
}

.scrollbar-inner > .scroll-element.scroll-x {
    bottom: 2px;
    height: 8px;
    left: 0;
    width: 100%;
}

.scrollbar-inner > .scroll-element.scroll-y {
    height: 100%;
    right: 2px;
    top: 0;
    width: 8px;
}

.scrollbar-inner > .scroll-element .scroll-element_outer {
    overflow: hidden;
}

.scrollbar-inner > .scroll-element .scroll-element_outer,
.scrollbar-inner > .scroll-element .scroll-element_track,
.scrollbar-inner > .scroll-element .scroll-bar {
    border-radius: 8px;
}

.scrollbar-inner > .scroll-element .scroll-element_track,
.scrollbar-inner > .scroll-element .scroll-bar {
    opacity: 0.4;
}

.scrollbar-inner > .scroll-element .scroll-element_track {
    background-color: #e0e0e0;
}

.scrollbar-inner > .scroll-element .scroll-bar {
    background-color: #c2c2c2;
}

.scrollbar-inner > .scroll-element:hover .scroll-bar {
    background-color: #919191;
}

.scrollbar-inner > .scroll-element.scroll-draggable .scroll-bar {
    background-color: #919191;
}

/* update scrollbar offset if both scrolls are visible */

.scrollbar-inner > .scroll-element.scroll-x.scroll-scrolly_visible .scroll-element_track {
    left: -12px;
}

.scrollbar-inner > .scroll-element.scroll-y.scroll-scrollx_visible .scroll-element_track {
    top: -12px;
}

.scrollbar-inner > .scroll-element.scroll-x.scroll-scrolly_visible .scroll-element_size {
    left: -12px;
}

.scrollbar-inner > .scroll-element.scroll-y.scroll-scrollx_visible .scroll-element_size {
    top: -12px;
}

/* Submenu styles */

.submenu {
    display: none;
}

.submenu ul {
    list-style: none;
    margin: 0;
    padding: 0px;
}

.submenu li {
    background-color: #EEEEEE;
    margin-bottom: 1px;
    line-height: 30px;
}

.submenu li a {
    color: #000000;
}

/* Expand / collapse controls */

#expand-collapse {
    text-align: right;
    margin: 15px 25px 15px 0px;
    cursor: pointer;
    text-decoration: underline;
    color: #09f;
}

.show_div {
    display: block;
}

/* Buttons & grid */

.btn-custom.active {
    background-color: #1e7875 !important;
    color: #fff;
    border-color: #1e7875;
    font-size: 16px;
    position: relative;
}

.btn-custom.active:active {
    color: #fff;
}

.btn-custom:focus {
    box-shadow: none;
}

#expand-collapse1 {
    border: none;
    border-radius: 5px;
    text-decoration: none;
    background: #5eaab3;
    color: #fff;
    margin-left: 10px;
    text-wrap: nowrap;
    height: 38px;
    font-size: 16px;
    font-weight: 500;
    padding: 0;
    padding-left: 10px;
}

.fa-square-caret-right,
.fa-square-caret-down {
    width: 40px;
    font-size: 20px;
    line-height: 34px;
    background: rgba(0, 0, 0, 0.10);
}

.grid-container {
    column-count: 2;
    column-gap: 1em;
}

.grid-blocks {
    display: inline-block;
    width: 100%;
}

/* Submenu1: headings + links */

.submenu1 h6 {
    margin-bottom: 10px;
    font-size: 15px;
    font-weight: 600;
}

.sub-menu-card-body ol {
    font-weight: 500;
    margin-bottom: 5px;
    padding-left: 0;
}

.sub-menu-card-body ol li {
    list-style: none;
}

.sub-menu-card-body ol li a {
    text-decoration: none;
    color: #030303;
}

/* Hover: card-body background blue, text white */
.sub-menu-card-body:hover {
    background-color: #2980b9;
    color: #fff;
    border-radius: 6px;
}


.sub-menu-link {
    color: #fff !important;
}

/* Chevron on dis_down buttons */

.dis_down::after {
    content: " ";
    flex-shrink: 0;
    width: 18px;
    height: 18px;
    right: 10px;
    position: absolute;
    background-image: url('../images/forward.svg');
    background-repeat: no-repeat;
    background-size: 18px;
}

button[aria-expanded='false']::after {
    transform: rotate(90deg);
}
 </style>
                <script>
                    function openDropdown() {
                        var dropdownElement = document.getElementById('dropArea');
                        if (dropdownElement) {
                            dropArea.classList.toggle("activeDropArea");
                        } else {
                            console.error('Dropdown element not found.');
                        }
                    }



                    window.onerror = function (msg, url, lineNo, columnNo, error) {
                        // ... handle error ...
                        return false;
                    }
                    var DisplayLabelID_JSON = {};

                    $(document).ready(function () {


                        $(".language_id").on("click", function () {
                            var lang_id = btoa($(this).attr('data-langID'));
                            //alert(lang_id);
                            if (lang_id != '') {
                                $.ajax({
                                    method: 'post',
                                    url: "<?php echo htmlentities($site_data->website_url); ?>project/forms/ajax/AjaxGeneral.php",
                                    data: { "lang_id": lang_id, "cmd": btoa(5) },
                                    success: function (data) {
                                        if (data != '' && data == 'success') {
                                            //alert(data);
                                            location.reload(true);
                                        }
                                    },
                                    dataType: 'html'
                                });
                                return true;
                            }
                        });
                        /*
                        $.ajax({
                            method: 'post',
                            url: "<?php #echo htmlentities($site_data->website_url); ?>project/forms/ajax/AjaxLabelPopulate.php",
                            data: { "page_id": btoa(<?php #echo (isset($extra_args['page_id']) ? $extra_args['page_id'] : '13') ?>), "cmd": btoa(1) },
                            success: function (data) {
                                if (data != '') {
                                    DisplayLabelID_JSON = data;
                                    UpdateLabel();
                                }
                            },
                            dataType: 'json'
                        });

                            */

                    });

                    function UpdateLabel() {
                        $("*[DisplayLabelID]").each(function () {
                            var datalabelid = 'L' + $(this).attr('DisplayLabelID');
                            $(this).html(DisplayLabelID_JSON['Label'][datalabelid]);
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
                                <div class="row  align-items-end justify-content-start tabCenter">
                                    <div class="col-md-2">
                                        <span class="govt_of_tn">Government of TamilNadu
                                        </span>
                                    </div>
                                    <div class="col-md-3">
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
                                    <div class="col-md-3 col-sm-1 search_btn text-center ">
                                        <!-- <div id="font-setting-buttons ">
                                            
                                        </div> -->
                                        <!-- <div class="btn-group">

                                                
                                            </div> -->
                                            <button class="btn btn-default headergigw dropdown" class="m-0"> <label class="de-lag"><span
                                                            style="display: none;">Language</span>
                                                        <select title="Select Language" id="cmb_language" name="cmb_language">
                                                            <?php
                                                            $sel_lang = "SELECT lang_id,lang_code_2d,lang_name_lc FROM master.m_langauage where lang_id not in(3) and del_flag is null and  lower(lang_name_lc)=lower(:lang_name_lc)";
                                                            $sel_lang_res = $this->prepare($sel_lang, array(
                                                                ":lang_name_lc" => 'English'
                                                            ), 4);


                                                            $lang_qry = "SELECT lang_id,lang_code_2d,lang_name_lc FROM master.m_langauage where lang_id not in(3) and del_flag is null  order by lang_id";
                                                            $sel_lang = $this->prepare($lang_qry, array(), 2);

                                                            foreach ($sel_lang as $key_lang => $lang) {
                                                                $sel = "";
                                                                /*if ($this->getCurrentUserLanguage2D() == $lang['lang_code_2d']) {
                        $sel = "selected";
                        }*/
                                                                if ($this->getUserLanguage() == $lang['lang_id']) {
                                                                    $sel = "selected";
                                                                }
                                                                ?>
                                                                <option <?php

                                                                echo htmlentities($sel);
                                                                ?> value="<?php

                                                                 echo htmlentities($lang['lang_code_2d']);
                                                                 ?>" data-lan_code="<?php

                                                                 echo $lang['lang_code_2d'] ?>" data-lan_name="<?php

                                                                   echo strtoupper($lang['lang_name_lc']);
                                                                   ?>"><?php

                                                                   echo strtoupper($lang['lang_name_lc']);
                                                                   ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>

                                                    </label></button>
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


                                    <div class="col-md-4">
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
                        <div id="sticky-header" class="_nav_manu py-2">
                            <div class="container-fluid">
                                <div class="row align-items-center justify-content-center text-center flex-wrap">

                                    <!-- Logo + Text -->
                                    <div
                                        class="col-12 col-md-6 col-lg-7 d-flex justify-content-center align-items-center flex-wrap text-center">
                                        <a class="navbar-brand d-flex align-items-center" href="#">
                                            <img src="
                                            <?php echo htmlentities($site_data->website_image_path); ?>assets/TamilNadu_Logo.png "
                                                class=" ipablogo" style="height:80px;width:80px;margin-left:42px"
                                                title="Directorate of Town Panchayats, Tamil Nadu" />
                                            <div class="nav_heading text-start">
                                                <h6 class="mb-0" style="color:#0e446d; font-weight:bold;">பேரூராட்சிகள் இயக்ககம்,
                                                    தமிழ்நாடு</h6>
                                                <small class="mb-0" style="color:#0e446d; font-weight:bold;">Directorate of Town
                                                    Panchayats, Tamil Nadu</small>
                                            </div>
                                        </a>
                                    </div>

                                    <!-- eGov Image -->
                                    <div
                                        class="col-6 col-md-3 col-lg-3 d-flex justify-content-center justify-content-md-end mt-3 mt-md-0">
                                        <img src="<?php echo htmlentities($site_data->website_url); ?>/project/assets/images/egov04.png" class="ipablogo" style="height:60px; width:60px;"
                                            alt="eGov">
                                    </div>

                                    <!-- Digital India Image -->
                                    <div
                                        class="col-6 col-md-3 col-lg-2 d-flex justify-content-center justify-content-md-end mt-3 mt-md-0">
                                        <img src="<?php echo htmlentities($site_data->website_url); ?>/project/assets/images/digital-india-c2.png" class="ipablogo"
                                            style="height:60px; width:60px;" alt="Digital India">
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="dashboard-btns">
                            <div class="container">
                                <div class="d-flex justify-content-between align-items-center mx-3">
                                    <a href="<?php echo htmlentities($site_data->website_url); ?>project/home.php"
                                        class="rdweb-btn home-btn">
                                        <div class="img-block">
                                            <img src="<?php echo htmlentities($site_data->website_image_path); ?>home-icon02.svg"
                                                alt="">
                                        </div>
                                        <span>Home</span>
                                    </a>
                                    <?php 
                                                            $script_filename = basename($_SERVER['SCRIPT_FILENAME']);
                                        //print_r($script_filename);
                                    
                                    
                                    ?>
                                    <div class="d-flex align-items-center">
                                        <?php
                                        if($script_filename!='home.php' && $script_filename!='index.php')
                                    {
                                        $this->Template1_html_master_hover(); 
                                    }
                                    ?>

                                        <div class="dropdown" style="padding:5px;">
                                            <div class="dropdown-btn" onclick="openDropdown()">
                                                <img
                                                    src="https://cdn0.iconfinder.com/data/icons/avatars-3/512/avatar_hipster_guy-512.png">
                                                <span class="d-flex flex-column">
                                                    <span><?php
                                                    $user_first_name = $_SESSION['USER_DETAILS']['USER_PROFILE']['user_first_name'];
                                                    $user_last_name = $_SESSION['USER_DETAILS']['USER_PROFILE']['user_last_name'];
                                                    echo htmlentities($user_first_name . ' ' . $user_last_name); ?></span>
                                                </span>
                                            </div>
                                            <div class="dropdown-area" id="dropArea">
                                                <ul>
                                                    <li><a
                                                            href="<?php echo $this->siteData()->website_url; ?>project/forms/admin/UserSetting.php">Change
                                                            Password</a></li>
                                                    <li><a href="<?php echo $site_data->website_form_path; ?>logout.php">
                                                            Logout</a></li>
                                                </ul>
                                            </div>
                                        </div>
										<?php if (!empty($_SESSION['financial_year'])) { ?>
										<div style="padding: 8px 12px;">
											<div style="padding: 8px 12px; background-color:#033c3a;">
												<div style="display: flex; flex-direction: column; line-height: 1.2;">
													<span style="font-size: 12px; color: #ffffff;">Financial Year</span>
													<span style="font-size: 18px; font-weight: bold; margin-top: 2px; color:#ffffff;">
														<?php echo $_SESSION['financial_year']; ?>
													</span>
												</div>
											</div>
										</div>
									<?php } ?>

                                </div>
                            </div>
                        </div>

                </div>
                <?php
                $current_page = basename($_SERVER['PHP_SELF']);
                ?>


                </header>
                
               
                <?php
        } else if ($part == "FOOT") {

            echo $this->menu_loader($part, $menu_type, $site_data, $user_name, $pageTitle, $breadcrumbs);
        }
    }


    
    public function Template1_html_master_hover_old()
    {
        ?>
         <div class="master-dropdown">
        <button class='master_button' id="master_button">⯆change menu</button>                                    <div class="submenu1"></div>
        <div class="master-menu">
            <section class="container">
                <div class="cards">
                    <div class="mt-3">
                        <div id="accordion" class="grid-container">
                            <?php
                            $role_code = $_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'];

                            $main_menu = "SELECT * FROM security.m_submenu1 
                                            WHERE user_id=:user_id AND dept_id=:dept_id AND rflag=:rflag 
                                              AND del_flag is null AND isactive=1 
                                              AND responsive_support IN('A', 'W') 
                                            ORDER BY menu_order_no, smenu_desc";

                            $main_menu_res = $this->prepare($main_menu, array(
                                ":user_id" => $role_code,
                                ":dept_id" => 1,
                                ":rflag" => 1
                            ), 2);

                            $slno = 1;
                            foreach ($main_menu_res as $key => $row) {
                            ?>
                                <div class="col-lg-6 grid-blocks">
                                    
                                    <button class="btn btn-custom w-100 mb-1 dis_down active"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapse<?php echo $key ?>"
                                            aria-expanded="true"
                                            aria-controls="collapse<?php echo $key ?>">
                                        <span style="float: left;">
                                            <b><?php echo $row['smenu_desc'] ?></b>
                                        </span>
                                    </button>

                                    
                                        <div id="collapse<?php echo $key ?>" class="collapse"
                                        aria-labelledby="heading<?php echo $key ?>"
                                        data-parent="#accordion">
                                        <?php
                                        
                                            $sub_menu = "SELECT ssmenu_desc as menu_desc, ssmenu_url as menu_url,
                                                                ssmenu_id as menu_id, smenu_id as parentmenu_id
                                                         FROM security.m_submenu2 
                                                         WHERE user_id=:user_id AND dept_id=:dept_id 
                                                           AND smenu_id=:menuid and del_flag is null and isactive=1
                                                           AND rflag=:rflag AND responsive_support IN('A', 'W') 
                                                         ORDER BY menu_order_no";

                                            $sub_menu_res = $this->prepare($sub_menu, array(
                                                ":user_id" => $role_code,
                                                ":dept_id" => 1,
                                                ":menuid" => $row['smenu_id'],
                                                ":rflag" => 1
                                            ), 2);
                                            foreach ($sub_menu_res as $row1) {
                                            ?>
                                                <div class="card-body">
                                                    <h6
                                                        <?php if ($row1['menu_url'] != '') { ?>
                                                            onclick="window.location.href=`<?php echo $this->sitedata()->website_url .'/project/'. $row1['menu_url']; ?>`"
                                                            role="button"
                                                        <?php } ?>
                                                    >
                                                        <?php echo $row1['menu_desc'] ?>
                                                    </h6>

                                                    <?php
                                                    $ssub_menu = "SELECT sssmenu_desc as menu_desc, sssmenu_url as menu_url, sssmenu_id as menu_id  
                                                                  FROM security.m_submenu3 
                                                                  WHERE ssmenu_id=:menuid AND user_id=:user_id 
                                                                    AND dept_id=:dept_id AND rflag=:rflag 
                                                                    AND responsive_support IN('A', 'W') 
                                                                  ORDER BY menu_order_no, sssmenu_desc ASC";

                                                    $ssub_menu_res = $this->prepare($ssub_menu, array(
                                                        ":user_id" => $role_code,
                                                        ":dept_id" => 1,
                                                        ":menuid" => $row1['menu_id'],
                                                        ":rflag" => 1
                                                    ), 2);

                                                    if (count($ssub_menu_res) > 0) {
                                                        foreach ($ssub_menu_res as $row2) {
                                                    ?>
                                                            <ol>
                                                                <li>
                                                                    <a href='<?php echo $row2['menu_url'] ?>?menu_type=<?=base64_encode('sub_menu3')?>&parentmenu_id=<?=base64_encode($row1['menu_id'])?>'
                                                                       title="<?php echo $row2['menu_desc'] ?>">
                                                                        <?php echo $row2['menu_desc'] ?>
                                                                    </a>
                                                                </li>
                                                            </ol>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                $slno++;
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
<script>  
    $(function() {
        $('.collapse_all').on('click', function() {
            if ($(this).hasClass('activate')) {
                $(".collapse").addClass("show")
                $("#expand-collapse1").html("Collapse All");
                $("#expand-collapse1").append("<i class='ms-1 fa-solid fa-square-caret-down'></i>");
            } else {
                $(".collapse").removeClass("show")
                $("#expand-collapse1").html("Expand All");
                $("#expand-collapse1").append("<i class='ms-1 fa-solid fa-square-caret-right'></i>");
            }
            $(this).toggleClass('activate')

            $('.dis_down').each(function() {
                if ($(this).attr('aria-expanded') == 'false') {
                    $(this).attr('aria-expanded', 'true');
                } else {
                    $(this).attr('aria-expanded', 'false');
                }
            })
        });

        $('.dis_down').click(function() {
            if ($(this).attr('aria-expanded') == 'false') {
                $(this).attr('aria-expanded', 'true');
            } else {
                $(this).attr('aria-expanded', 'false');
            }
        });
    });
</script>

        

        <?php
    }



    public function Template1_html_master_hover()
{
    ?>
    <?php
    $role_code = $_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'];

    $main_menu = "SELECT * FROM security.m_submenu1 
                    WHERE user_id=:user_id AND dept_id=:dept_id AND rflag=:rflag 
                      AND del_flag is null AND isactive=1 
                      AND responsive_support IN('A', 'W') 
                    ORDER BY menu_order_no, smenu_desc";

    $main_menu_res = $this->prepare($main_menu, array(
        ":user_id" => $role_code,
        ":dept_id" => 1,
        ":rflag"   => 1
    ), 2);

    $slno = 1;
    foreach ($main_menu_res as $key => $row) {
        ?>
        <div class="master-dropdown">

            <button class="master_button" id="master_button">⯆<?php echo $row['smenu_desc']; ?></button>

            <div class="master-menu">
                <div class="submenu1">
                    <?php
                    
                    $sub_menu = "SELECT ssmenu_desc as menu_desc, ssmenu_url as menu_url,
                                        ssmenu_id as menu_id, smenu_id as parentmenu_id
                                 FROM security.m_submenu2 
                                 WHERE user_id=:user_id AND dept_id=:dept_id 
                                   AND smenu_id=:menuid AND del_flag is null AND isactive=1
                                   AND rflag=:rflag AND responsive_support IN('A', 'W') 
                                  ORDER BY CASE
                                        WHEN menu_order_no::text LIKE '%.%' THEN  -- if there's a decimal point
                                        CAST(SPLIT_PART(menu_order_no::text, '.', 2) AS NUMERIC)
                                        ELSE
                                        CAST(menu_order_no AS NUMERIC)          -- no decimal, use the number itself
                                    END;";

                    $sub_menu_res = $this->prepare($sub_menu, array(
                        ":user_id" => $role_code,
                        ":dept_id" => 1,
                        ":menuid"  => $row['smenu_id'],
                        ":rflag"   => 1
                    ), 2);

                    foreach ($sub_menu_res as $row1) {
                        ?>
                        <div class="container">
                                <div class="card-body sub-menu-card-body p-1">
                        <h6
                            <?php if ($row1['menu_url'] != '') { ?>
                                onclick="window.location.href=`<?php echo $this->sitedata()->website_url . '/project/' . $row1['menu_url']; ?>`"
                                role="button"
                            <?php } ?>
                        >
                            <?php echo $row1['menu_desc']; ?>
                        </h6>
                        <?php
                        $ssub_menu = "SELECT sssmenu_desc as menu_desc, sssmenu_url as menu_url, sssmenu_id as menu_id  
                                      FROM security.m_submenu3 
                                      WHERE ssmenu_id=:menuid AND user_id=:user_id 
                                        AND dept_id=:dept_id AND rflag=:rflag 
                                        AND responsive_support IN('A', 'W') 
                                      ORDER BY menu_order_no, sssmenu_desc ASC";

                        $ssub_menu_res = $this->prepare($ssub_menu, array(
                            ":user_id" => $role_code,
                            ":dept_id" => 1,
                            ":menuid"  => $row1['menu_id'],
                            ":rflag"   => 1
                        ), 2);

                        if (count($ssub_menu_res) > 0) {
                            ?>
                            
                                    <ol>
                                        <?php foreach ($ssub_menu_res as $row2) { ?>
                                            <li>
                                                <a  class ="sub-menu-link" href="<?php echo $row2['menu_url']; ?>?menu_type=<?= base64_encode('sub_menu3') ?>&parentmenu_id=<?= base64_encode($row1['menu_id']) ?>"
                                                   title="<?php echo $row2['menu_desc']; ?>">
                                                    <?php echo $row2['menu_desc']; ?>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ol>
                                
                            <?php
                        }?>
                        </div>
                            </div>
                    <?php
                        }
                    ?>
                    
                </div> <!-- /.submenu1 -->
            </div> <!-- /.master-menu -->

        </div> <!-- /.master-dropdown -->
        <?php
        $slno++;
    } // end foreach main_menu_res
    ?>
    <?php
} 

    public function Template2_html($part = "", $pageTitle = "", $breadcrumbs = array(), $extra_args = array())
    {
        $site_data = $this->siteData();
        if (isset($_SESSION['USER_DETAILS']['USER_PROFILE']['menu_type'])) {
            $menu_type = $_SESSION['USER_DETAILS']['USER_PROFILE']['menu_type'];
        } else {
            $menu_type = 'Plaintemplate';
        }
        if (!isset($_SESSION['USER_DETAILS'])) {
            echo "<br><br><center><h3><font color='red'>Session Timeout:Please Login Again</font></center>";
            $delay = "1";
            die('<meta http-equiv="refresh" content="' . $delay . ';URL=' . $site_data->website_url . '">');
        }
        if (isset($_SESSION['USER_DETAILS']['USER_PROFILE']['menu_type'])) {
            $user_name = $_SESSION['USER_DETAILS']['USER_PROFILE']['user_first_name'];
        } else {
            $user_name = 'test';
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
                             <link id="t-colors" href="skins/default.css" rel="stylesheet" /><?php */ ?>
                    <link rel="stylesheet" href="<?php echo htmlentities($site_data->website_css_path); ?>gijgo.datepicker.min.css">
                    <link rel="stylesheet" href="<?php echo htmlentities($site_data->website_css_path); ?>template1/styles.css">
                    <link rel="stylesheet"
                        href="<?php echo htmlentities($site_data->website_css_path); ?>Master_Tax_Form_Common_Validation.css">
                    <link rel="stylesheet" href="<?php echo htmlentities($site_data->website_css_path); ?>/small-business.css">
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
                        window.onerror = function (msg, url, lineNo, columnNo, error) {
                            // ... handle error ...
                            return false;
                        }
                        var DisplayLabelID_JSON = {};

                        function UpdateLabel() {
                            $("*[DisplayLabelID]").each(function () {
                                var datalabelid = 'L' + $(this).attr('DisplayLabelID');
                                $(this).text(DisplayLabelID_JSON['Label'][datalabelid]);
                            });
                        }
                    </script>
                                
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">


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
                                        <div id="sticky-header" class="_nav_manu py-2">
                                            <div class="container-fluid">
                                                <div class="row align-items-center justify-content-center text-center flex-wrap">

                                                    <!-- Logo + Text -->
                                                    <div
                                                        class="col-12 col-md-6 col-lg-7 d-flex justify-content-center align-items-center flex-wrap text-center">
                                                        <a class="navbar-brand d-flex align-items-center" href="#">
                                                            <img src="
                                            <?php echo htmlentities($site_data->website_image_path); ?>assets/TamilNadu_Logo.png "
                                                                class=" ipablogo" style="height:80px;width:80px;margin-left:42px"
                                                                title="Directorate of Town Panchayats, Tamil Nadu" />
                                                            <div class="nav_heading text-start">
                                                                <h6 class="mb-0" style="color:#0e446d; font-weight:bold;">
                                                                    பேரூராட்சிகள் இயக்ககம், தமிழ்நாடு</h6>
                                                                <small class="mb-0"
                                                                    style="color:#0e446d; font-weight:bold;">Directorate of Town
                                                                    Panchayats, Tamil Nadu</small>
                                                            </div>
                                                        </a>
                                                    </div>

                                                    <!-- eGov Image -->
                                                    <div
                                                        class="col-6 col-md-3 col-lg-3 d-flex justify-content-center justify-content-md-end mt-3 mt-md-0">
                                                        <img src="<?php echo htmlentities($site_data->website_url); ?>/project/assets/images/egov04.png" class="ipablogo"
                                                            style="height:60px; width:60px;" alt="eGov">
                                                    </div>

                                                    <!-- Digital India Image -->
                                                    <div
                                                        class="col-6 col-md-3 col-lg-2 d-flex justify-content-center justify-content-md-end mt-3 mt-md-0">
                                                        <img src="<?php echo htmlentities($site_data->website_url); ?>/project/assets/images/digital-india-c2.png" class="ipablogo"
                                                            style="height:60px; width:60px;" alt="Digital India">
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
                            $(document).ready(function () {
                                <?php
                                if (isset($_GET['loginState']) && base64_decode($_GET['loginState']) == 'fail') {
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
                                    url: "<?php echo $this->siteData()->website_url . 'project/ajax/forms/AjaxGeneralPublic.php'; ?>",
                                    data: {
                                        "cmd": btoa(13)
                                    },
                                    success: function (data) {
                                        if (data != '') {
                                            if (isValidURL(data)) {
                                                $('#captcha').attr('src', data);
                                                <?php /*?> $("#captcha-audio-section").find('source').attr("src",
                                                         '<?php echo htmlentities($site_data->website_url); ?>library/login_string_audio.php?aud=<?php echo base64_encode(time()); ?>'
                                                     );
                                                     $("#captcha-audio-section").find('source').get(0).load();
                                                     <?php */ ?>
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
                                <div id="sticky-header" class="_nav_manu py-2">
                                    <div class="container-fluid">
                                        <div class="row align-items-center justify-content-center text-center flex-wrap">

                                            <!-- Logo + Text -->
                                            <div
                                                class="col-12 col-md-6 col-lg-7 d-flex justify-content-center align-items-center flex-wrap text-center">
                                                <a class="navbar-brand d-flex align-items-center" href="#">
                                                    <img src="
                                            <?php echo htmlentities($site_data->website_image_path); ?>assets/TamilNadu_Logo.png "
                                                        class=" ipablogo" style="height:80px;width:80px;margin-left:42px"
                                                        title="Directorate of Town Panchayats, Tamil Nadu" />
                                                    <div class="nav_heading text-start">
                                                        <h6 class="mb-0" style="color:#0e446d; font-weight:bold;">பேரூராட்சிகள்
                                                            இயக்ககம், தமிழ்நாடு</h6>
                                                        <small class="mb-0" style="color:#0e446d; font-weight:bold;">Directorate of
                                                            Town Panchayats, Tamil Nadu</small>
                                                    </div>
                                                </a>
                                            </div>

                                            <!-- eGov Image -->
                                            <div
                                                class="col-6 col-md-3 col-lg-3 d-flex justify-content-center justify-content-md-end mt-3 mt-md-0">
                                                <img src="<?php echo htmlentities($site_data->website_url); ?>/project/assets/images/egov04.png" class="ipablogo"
                                                    style="height:60px; width:60px;" alt="eGov">
                                            </div>

                                            <!-- Digital India Image -->
                                            <div
                                                class="col-6 col-md-3 col-lg-2 d-flex justify-content-center justify-content-md-end mt-3 mt-md-0">
                                                <img src="<?php echo htmlentities($site_data->website_url); ?>/project/assets/images/digital-india-c2.png" class="ipablogo"
                                                    style="height:60px; width:60px;" alt="Digital India">
                                            </div>

                                        </div>
                                    </div>
                                </div>

                        </div>
                        <?php
                        $current_page = basename($_SERVER['PHP_SELF']);
                        ?>

                        <nav class="navbar navbar-expand-lg navbar-light" style="background-color:#04bebe">
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
                        $script_filename = basename($_SERVER['SCRIPT_FILENAME']);
                        if ($script_filename != 'index.php') {
                            ?>
                            <section id="fontSize" class="buttons mt-3">
                                <div class="bg-wrapper inner-wrapper">
                                    <div class="breadcam-bg breadcam">
                                        <?php

                                        $page_url = $_SERVER['PHP_SELF'];
                                        $File_name = explode('project/', $page_url)[1];

                                        if ($script_filename == 'Disclaimer.php' || $script_filename == 'ScreenReader.php' || $script_filename == 'WebInformationManager.php' || $script_filename == 'WebsitePolicies.php') {
                                            $breadcam_name = '';
                                            if ($script_filename == 'Disclaimer.php') {
                                                $breadcam_name = 'Disclaimer';
                                            } else if ($script_filename == 'ScreenReader.php') {
                                                $breadcam_name = 'Screen-Reader';
                                            } else if ($script_filename == 'WebInformationManager.php') {
                                                $breadcam_name = 'Web Information Manager';
                                            } else if ($script_filename == 'WebsitePolicies.php') {
                                                $breadcam_name = 'Website policies';
                                            }

                                            $site_url = ($this->getCurrentRole() != 32) ? $site_data->website_url . "project/home.php" : $site_data->website_url;
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
                                        } else if (is_array($this->Get_Menu_File_Name_Details($File_name, 32)) && count($this->Get_Menu_File_Name_Details($File_name, 32)) > 0) {
                                            $Menu_Bread_Crums = $this->Get_Menu_File_Name_Details($File_name, 32);

                                            $site_url = ($this->getCurrentRole() != 32) ? $site_data->website_url . "project/home.php" : $site_data->website_url;
                                            ?>
                                                        <div class="container">
                                                            <div class="row breadcrumbruler">
                                                                <div class="col-lg-9">
                                                                    <ul class="breadcrumb">
                                                                        <li><a href="<?php echo htmlentities($site_data->website_url); ?>"
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

                                                                                <button
                                                                                    onClick="HelpFaq('<?php

                                                                                    echo htmlentities($site_data->website_url);
                                                                                    ?>','<?php

                                                                                    echo htmlentities($site_data->website_js_path); ?>')"
                                                                                    id="page_help" name="page_help" data-toggle="tooltip"
                                                                                    title="help"
                                                                                    class="btn btn-sm btn-success m-0  font-weight-bold"><i
                                                                                        class="fa fa-question-circle"
                                                                                        aria-hidden="true"></i></button>
                                                                        <?php } ?>
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

    public function Get_Menu_File_Name_Details($url = '', $rolecode = '')
    {
        //echo '<prev>'.print_r($_SESSION['USER_DETAILS']).'</prev>';die();
        $role_code = ($rolecode == '') ? $_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'] : $rolecode;
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
        //$sel_menu_file_details = "SELECT a.menuid,a.submenuid,a.menu_desc,a.menu_desc_ta,a.menu_no,(CASE WHEN a.report_no='F' THEN 'Form Number' WHEN a.report_no='R' THEN 'Report Number' END) as report_no,report_no as report_type FROM master.mst_menu_development a , master.mst_menuconfig b where b.roleid::integer=:role_code and a.menuid=b.menuid and a.rflag=:rflag and b.isactive=:isactive and lower(trim(a.url)) like lower(trim('%" . $url . "%'))";

        //$sel_menu_file_details = "select  menu_id as menuid,ssmenu_id as submenuid,ssmenu_desc as menu_desc,ssmenu_desc_ll as menu_desc_ta,menu_order_no as menu_no,(CASE WHEN report_no='F' THEN 'Form Number' WHEN report_no='R' THEN 'Report Number' END) as report_no,report_no as report_type FROM security.m_submenu2 WHERE user_id=:role_code and  isactive=:is_active and lower(trim(ssmenu_url)) like lower(trim(:url));";

        $sel_menu_file_details = "select  b.smenu_desc as parent_menu,a.menu_id as menuid,a.ssmenu_id as submenuid,a.ssmenu_desc as menu_desc,a.ssmenu_desc_ll as menu_desc_ta,a.menu_order_no as menu_no,(CASE WHEN a.report_no='F' THEN 'Form Number' WHEN a.report_no='R' THEN 'Report Number' END) as report_no,a.report_no as report_type FROM security.m_submenu2 a LEFT JOIN security.m_submenu1 b ON (a.smenu_id=b.smenu_id and a.user_id=b.user_id)  WHERE a.user_id=:role_code and  a.isactive=:is_active and lower(trim(a.ssmenu_url)) like lower(trim(:url));";

        $sel_menu_file_details_res = $this->prepare($sel_menu_file_details, array(
            ":role_code" => $role_code,
            ":is_active" => 1,
            ":url" => $url
        ), 4);

        if (isset($sel_menu_file_details_res['menuid']) && $sel_menu_file_details_res['menuid'] != '') {
            //$Menu_Level_Text[]=$sel_menu_file_details_res['menu_no'].'-'.$sel_menu_file_details_res['menu_desc'];

            //need to add logic for parent_menu tamil
            $Menu_Level_Text['Data'][] = $sel_menu_file_details_res['parent_menu'];
            if ($user_language == 'en') {
                $Menu_Level_Text['Data'][] = $sel_menu_file_details_res['menu_desc'];
            } else if ($user_language == 'ta')
                $Menu_Level_Text['Data'][] = trim($sel_menu_file_details_res["menu_desc_ta"]) == "" ? trim($sel_menu_file_details_res["menu_desc"]) : trim($sel_menu_file_details_res["menu_desc_ta"]);
            else
                $Menu_Level_Text['Data'][] = $sel_menu_file_details_res['menu_desc'];

            $Menu_Level_Text['Data_Details'] = $sel_menu_file_details_res['report_no'] . ' : ' . $sel_menu_file_details_res['menu_no'];
            $Menu_Level_Text['report_type'] = $sel_menu_file_details_res['report_type'];

            //print_r(["Menu_Level_Text"=>$Menu_Level_Text]);die();
            //return $this->Check_Prent_Exist($sel_menu_file_details_res['submenuid'], $Menu_Level_Text);
        }
        return $Menu_Level_Text;
        //print_r(['get_menu_result'=>$this->Check_Prent_Exist($sel_menu_file_details_res['submenuid'], $Menu_Level_Text)]);
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
                ":role_code" => $role_code,
                ":security_id" => $security_id,
                ":user_profile_id" => $user_profile_id,
                ":menuid" => $submenuid
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