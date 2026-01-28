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
        $role_code = $_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'];
        
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

            $menuscript = "<ul class='dropdown-menu'>";
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
		<li><a class='dropdown-item' href=$menu_url style='font-size:14px; font-weight:600;' $target_cond>$menu_no - $desc</a></li>
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

                    $menuscript .= "
		<li>  
			<a class='dropdown-item dropdown-toggle' href='javascript:void(0)' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' style='font-size:14px; font-weight:600;' $target_cond>$menu_no - $desc</a>" .
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

            $menuscript = "<ul class='dropdown-menu'>";
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
		<li><a class='dropdown-item' href=$menu_url style='font-size:14px; font-weight:600;' $target_cond>$menu_no - $desc</a></li>
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

                    $menuscript .= "
		<li>  
			<a class='dropdown-item dropdown-toggle' href='javascript:void(0)' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' style='font-size:14px; font-weight:600;' $target_cond>$menu_no - $desc</a>" .
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
            $menuscript = "<ul class='collapse'>";
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

                $submenu = $this->getConfigSubMenu_sidebar($menuid, $site_data);

                if ($submenu['display_code'] == 2 && $url != '') {
                    $menu_url = "'" . $site_data->website_url . "project/" . $url . "'";
                    $menuscript .= "
		<li><a
		href=$menu_url style='font-size:14px; font-weight:600;' $target_cond>$menu_no - $desc</a></li>		
		"; // $url
                } else if ($submenu['display_code'] == 3) {
                    $menuscript .= "
		<li><a href='#' aria-expanded='true' style='font-size:14px; font-weight:600;' $target_cond>$menu_no - $desc</a>" .
                        $submenu['menuscript'] .
                        "</li>	
		";
                }
            }
            $menuscript .= "</ul>";
            return array("display_code" => 3, "menuscript" => $menuscript);
        }
    }

    public function menu_loader($part = "", $menu_type = "", $site_data = null, $user_name = "", $pageTitle = "", $breadcrumbs = array())
    {
        if ($menu_type == "sidebar" || $menu_type == "") {
            if ($part == "HEAD") {
?>
                <div class="page-container">
                    <!-- sidebar menu area start -->
                    <div class="sidebar-menu">
                        <div class="sidebar-header">
                            <div class="logo" style="font-size:10px;">
                                <a href="<?php

                                            echo htmlentities($site_data->website_url);
                                            ?>project/home.php"> TNDTP
                                    <!--Directorate of Town Panchayats, Tamil Nadu-->
                                    <!--Rural Development & Panchayat Raj Department-->
                                </a>
                            </div>
                        </div>
                        <div class="main-menu">
                            <div class="menu-inner">
                                <nav>
                                    <ul class="metismenu" id="menu" style="padding-left: 0px;">
                                        <li><a href="<?php

                                                        echo htmlentities($site_data->website_url);
                                                        ?>project/home.php"><i class="ti-home"></i><span>Home</span></a></li>
                                        <?php
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

                                            $query_exist_level_control = "SELECT STRING_AGG(menuid::text,',') as menuid_list FROM master.mst_menu_user_level_control where role_code::integer=:role_code and security_id=:security_id and user_profile_id=:user_profile_id and menuid in (select a.menuid from master.mst_menu_development a , master.mst_menuconfig b  where b.roleid::integer=:role_code and a.menuid=b.menuid and submenuid=:submenuid and a.rflag=:rflag and b.isactive=:isactive $cond) and isactive=:isactive and del_flag is null";
                                            $exist_exist_level_control = $this->prepare($query_exist_level_control, array_merge(array(
                                                ":role_code" => $role_code, ":security_id" => $security_id, ":user_profile_id" => $user_profile_id, ":submenuid" => 0, ":rflag" => 1, ":isactive" => 1
                                            ), $cond_array), 4); //var_dump($exist_exist_level_control);exit;

                                            $menuid_list = array();
                                            $menuid_list_cnt = 0;
                                            if (isset($exist_exist_level_control['menuid_list']) && $exist_exist_level_control['menuid_list'] != '') {
                                                $menuid_list = explode(",", $exist_exist_level_control['menuid_list']);
                                                $menuid_list_cnt = count($menuid_list);
                                                $menuid_list = array_combine(
                                                    array_map(function ($i) {
                                                        return ':menuid_list' . $i;
                                                    }, array_keys($menuid_list)),
                                                    $menuid_list
                                                );
                                                $menuid_list_cond = " and a.menuid in (" . implode(',', array_keys($menuid_list)) . ")";
                                            }

                                            if ($menuid_list_cnt > 0) {
                                                $query = "select * from master.mst_menu_development a , master.mst_menuconfig b  where b.roleid=:role_code and a.menuid=b.menuid $menuid_list_cond and a.rflag=:rflag and b.isactive=:isactive $cond order by menu_order_no desc";
                                                $menu = $this->prepare($query, array_merge(array(
                                                    ":role_code" => $role_code,
                                                    ":rflag" => 1,
                                                    ":isactive" => 1
                                                ), $cond_array, $menuid_list), 2); //var_dump($menu);exit;
                                            } else {
                                                $menu = array();
                                            }
                                        } else {

                                            $query = "select * from master.mst_menu_development a , master.mst_menuconfig b  where b.roleid=:role_code and a.menuid=b.menuid and submenuid=:submenuid and a.rflag=:rflag and b.isactive=:isactive $cond order by menu_order_no desc";
                                            $menu = $this->prepare($query, array_merge(array(
                                                ":role_code" => $role_code,
                                                ":submenuid" => 0,
                                                ":rflag" => 1,
                                                ":isactive" => 1
                                            ), $cond_array), 2);
                                        }

                                        if (count($menu) > 0) {
                                            $menuscript = "";
                                            foreach ($menu as $key => $menu_row) {
                                                if ($user_language == 'en')
                                                    $desc = trim($menu_row["menu_desc"]);
                                                else if ($user_language == 'ta')
                                                    $desc = trim($menu_row["menu_desc_ta"]) == "" ? trim($menu_row["menu_desc"]) : trim($menu_row["menu_desc_ta"]);
                                                else
                                                    $desc = trim($menu_row["menu_desc"]);

                                                $url = trim($menu_row["url"]); // echo htmlentities($url);
                                                $menuid = $menu_row["menuid"];
                                                $target_cond = "";

                                                if ($menu_row["new_tab"] == 'Y') {
                                                    $target_cond = "target='_blank'";
                                                }
                                                $submenu = $this->getConfigSubMenu_sidebar($menuid, $site_data);

                                                if ($submenu['display_code'] == 2 && $url != '') {
                                                    $menu_url = "'" . $site_data->website_url . "project/" . $url . "'";
                                                    $menuscript .= "
				<li><a 
				href=$menu_url $target_cond><i
				class='fa fa-align-left'></i><span>$desc</span></a>
				</li> 
				"; // $url
                                                } else if ($submenu['display_code'] == 3) {
                                                    $menuscript .= "
				<li><a href='javascript:void(0)'
				aria-expanded='true' style='font-size:14px; font-weight:600;' $target_cond><i class='fa fa-align-left'></i> <span>$desc</span></a>"
				.$submenu['menuscript'].
				"</li>
				";
                                                }
                                            }

                                            echo $menuscript;
                                        }
                                        ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <!-- sidebar menu area end -->
                    <!-- main content area start -->
                    <div class="main-content">
                        <!-- header area start -->
                        <div class="header-area">
                            <div class="row align-items-center">
                                <!-- nav and search button -->
                                <div class="col-md-3 clearfix">
                                    <div class="nav-btn pull-left">
                                        <span></span> <span></span> <span></span>
                                    </div>
                                </div>
                                <!-- profile info & task notification -->
                                <div class="col-md-9 clearfix text-right">
                                    <div class="d-md-inline-block d-block mr-md-4">
                                        <ul class="notification-area pull-right">
                                            <?php
                                            /*
                 * ?><li id="full-view"><i class="ti-fullscreen"></i></li>
                 * <li id="full-view-exit"><i class="ti-zoom-out"></i></li>
                 * <li class="dropdown"><i class="ti-bell dropdown-toggle"
                 * data-toggle="dropdown"> <span>2</span>
                 * </i>
                 * <div class="dropdown-menu bell-notify-box notify-box">
                 * <span class="notify-title">You have 3 new notifications <a
                 * href="#">view all</a></span>
                 * <div class="nofity-list">
                 * <a href="#" class="notify-item">
                 * <div class="notify-thumb">
                 * <i class="ti-key btn-danger"></i>
                 * </div>
                 * <div class="notify-text">
                 * <p>You have Changed Your Password</p>
                 * <span>Just Now</span>
                 * </div>
                 * </a>
                 * </div>
                 * </div></li>
                 * <li class="dropdown"><i class="fa fa-envelope-o dropdown-toggle"
                 * data-toggle="dropdown"><span>3</span></i>
                 * <div class="dropdown-menu notify-box nt-enveloper-box">
                 * <span class="notify-title">You have 3 new notifications <a
                 * href="#">view all</a></span>
                 * <div class="nofity-list">
                 * <a href="#" class="notify-item">
                 * <div class="notify-thumb">
                 * <img
                 * src="<?php echo htmlentities($site_data->website_image_path);?>template1/author/author-img1.jpg"
                 * alt="image">
                 * </div>
                 * <div class="notify-text">
                 * <p>Aglae Mayer</p>
                 * <span class="msg">Hey I am waiting for you...</span> <span>3:15
                 * PM</span>
                 * </div>
                 * </a>
                 * </div>
                 * </div></li>
                 * <li class="settings-btn"><i class="ti-settings"></i></li><?php
                 */
                                            ?>
                                            <li>
                                                <div class="language horiz-user-profile m-0">
                                                    <h4 class="user-name btn btn-rounded btn-info dropdown-toggle text-uppercase" data-toggle="dropdown">
                                                        <?php
                                                        if (isset($_SESSION['USER_DETAILS']['language_id'])) {
                                                            $language_id = $_SESSION['USER_DETAILS']['language_id'];

                                                            $lang_qry = "SELECT lang_id,lang_code_2d,lang_name_lc FROM master.m_langauage where del_flag is null and lang_id=:language_id order by lang_id";
                                                            $sel_lang = $this->prepare($lang_qry, array(
                                                                ":language_id" => $language_id
                                                            ), 2);

                                                            foreach ($sel_lang as $key_lang => $lang) {
                                                                echo htmlentities($lang['lang_code_2d']);
                                                            }
                                                        } else {
                                                            echo "EN";
                                                            $_SESSION['USER_DETAILS']['language_id'] = 2;
                                                        }
                                                        ?><i class="fa fa-angle-down"></i>
                                                    </h4>
                                                    <div class="dropdown-menu">
                                                        <?php
                                                        $lang_qry = "SELECT lang_id,lang_code_2d,lang_name_lc FROM master.m_langauage  where del_flag is null   order by lang_id";
                                                        $sel_lang = $this->prepare($lang_qry, array(), 2);

                                                        foreach ($sel_lang as $key_lang => $lang) {
                                                        ?>
                                                            <a class="dropdown-item language_id" data-langID="<?php

                                                                                                                echo $lang['lang_id'] ?>"><?php

                                                            echo $lang['lang_name_lc'] ?></a>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="clearfix d-md-inline-block d-block">
                                        <div class="user-profile m-0">
                                            <img class="avatar user-thumb" src="<?php

                                                                                echo htmlentities($site_data->website_image_path);
                                                                                ?>template1/author/avatar.png" alt="avatar">
                                            <h4 class="user-name dropdown-toggle" data-toggle="dropdown"><?php

                                                                                                            echo htmlentities($user_name);
                                                                                                            ?> <i class="fa fa-angle-down"></i>
                                            </h4>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#"><?php

                                                                                    echo $this->GetLable(741, "Message");
                                                                                    ?></a>
                                                <a class="dropdown-item" href="#" onClick="BugReport('<?php

                                                                                                            echo htmlentities($site_data->website_url);
                                                                                                            ?>','<?php

                        echo $site_data->website_js_path ?>')" >Bug Report</a>
                                                <a class="dropdown-item" href="<?php

                                                                                echo $site_data->website_form_path;
                                                                                ?>admin/UserSetting.php"><?php

                                            echo $this->GetLable(3, "Setting");
                                            ?></a>
                                                <a class="dropdown-item" href="<?php

                                                                                echo $site_data->website_form_path;
                                                                                ?>logout.php"><?php

                                echo $this->GetLable(740, "Logout");
                                ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- header area end -->
                        <!-- page title area start -->
                        <?php
                        /*
                 * ?><div class="page-title-area pt-2">
                 * <div class="row align-items-center">
                 * <div class="col-sm-12">
                 * <div class="breadcrumbs-area clearfix">
                 * <h4 class="page-title pull-left"><?php echo htmlentities($pageTitle); ?></h4>
                 * <ul class="breadcrumbs pull-left">
                 * <?php
                 * if (count($breadcrumbs) > 0) {
                 * foreach ($breadcrumbs as $breadcrumbs_val) {
                 * if (isset($breadcrumbs_val['href'])) {
                 * ?>
                 * <li><a
                 * href="<?php echo $breadcrumbs_val['href'] ?>"><?php echo $breadcrumbs_val['name'] ?></a></li>
                 * <?php
                 * } else {
                 * ?>
                 * <li><a><?php echo $breadcrumbs_val['name'] ?></a></li>
                 * <?php
                 * }
                 * }
                 * }
                 * ?>
                 *
                 *
                 * </ul>
                 * </div>
                 * </div>
                 * </div>
                 * </div><?php
                 */
                        ?>
                        <!-- page title area end -->

                        <div class="main-content-inner">


                        <?php
                    }
                    if ($part == "FOOT") {

                        ?>
                        </div>
                    </div>
                    <!-- main content area end -->
                    <!-- footer area start-->
                    <footer>
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
                        //print_r($_SESSION); 
                        if ($this->issetCurrentUserProfileID()) { ?>
                            <script src="<?php echo htmlentities($site_data->website_js_path); ?>Master_Data_Local_Storage.js"></script>
                        <?php
                        } else {
                        ?>
                            <script src="<?php echo htmlentities($site_data->website_js_path); ?>Master_Data_Local_Storage_Public.js"></script>
                        <?php
                        }
                        ?>


                        <div class=" footer-area">
                            <!--<hr class="hr-text m-0 py-0">-->
                            <div class="d-flex col-md-11 m-auto pb-2">
                                <div class="col-md-4"><?php

                                                        echo $this->GetLable(742, "Last Update");
                                                        ?>: <?php

                        echo date("d-m-Y");
                    ?></div>
                                <div class="col-md-3 text-center">
                                    <?php
                                    if ($this->issetCurrentUserSecurityID()) {
                                    ?> <a href="<?php echo $this->siteData()->website_url; ?>project/forms/admin/UserSetting.php?page=bXlfYWN0aXZpdHk="><?php

                                                                                                                            echo $this->GetLable(745, "Last Login Date");
                                                                                                                            ?>: <?php

                                        echo  $this->getLastLoginActivity($this->getCurrentUserSecurityID(), 1)->last_login_activity_date;

                    ?></a>
                                        <br />
                                        <?php

                                        echo $this->GetLable(746, "Last Login IP");
                                        ?>: <?php
                                        echo  $this->getLastLoginActivity($this->getCurrentUserSecurityID(), 1)->last_login_activity_ip;
                    ?>

                                    <?php } ?>
                                </div>
                                <div class="col-md-5 text-right"><?php

                                                                    echo $this->GetLable(743, "Designed, Developed &amp; Maintained by NIC.");
                                                                    ?></div>
                            </div>

                        </div>
                    </footer>
                    <!-- footer area end-->
                </div>
            <?php
                    }
                } else if ($menu_type = "horizontal") {
                    if ($part == "HEAD") {
            ?>
                <div class="horizontal-main-wrapper">
                    <!-- main header area start -->
                    <div class="fixed-top">
                        <div class="mainheader-area horiz-mainheader-area">
                            <div class="container">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div>
                                            <div style="float:left; padding:3px">
                                                <img src="<?php

                                                            echo htmlentities($site_data->website_url);
                                                            ?>images/tn_logo_top.png" width="60">
                                            </div>
                                            <div style="float:left;" class="pl-2">
                                                <div style="padding-top:2px; line-height:36px;">
                                                    <a class="navbar-brand" href="<?php

                                                                                    echo htmlentities($site_data->website_url);
                                                                                    ?>project/home.php" style="color:white; font-weight:900;">
                                                        <!--<img src="img/logo.png" alt="logo"/>-->
                                                        பேரூராட்சிகள் இயக்ககம், தமிழ்நாடு
                                                    </a>
                                                </div>
                                                <div style="padding-top:0px; line-height:1px; text-indent:3px;"> <a class="navbar-headertitle" href="<?php

                                                                                                                                                        echo htmlentities($site_data->website_url);
                                                                                                                                                        ?>project/home.php" style="text-decoration: none;color:white;">Directorate of Town Panchayats, Tamil Nadu
                                                        <!--Rural Development & Panchayat Raj Department-->
                                                    </a></div>
                                                </a>
                                            </div>
                                        </div>
                                        <!--	<div class="logo">
							<a href="<?php

                                        echo htmlentities($site_data->website_url);
                                        ?>project/home.php"><span
								style="color: #ffffff; font-size: 24px;"  DisplayLabelID="20"  >Directorate of Town Panchayats, Tamil Nadu</span></a>
						</div>-->
                                    </div>
                                    <!-- profile info & task notification -->
                                    <div class="col-md-6 clearfix text-right">
                                        <div class="d-md-inline-block d-block">
                                            <ul class="notification-area horiz-notification-area" style="margin-top:25px;">
                                                <?php
                                                /*
                 * ?><li id="full-view"><i class="ti-fullscreen"></i></li>
                 * <li id="full-view-exit"><i class="ti-zoom-out"></i></li>
                 * <li class="dropdown"><i class="ti-bell dropdown-toggle"
                 * data-toggle="dropdown"> <span>2</span>
                 * </i>
                 * <div class="dropdown-menu bell-notify-box notify-box">
                 * <span class="notify-title">You have 3 new notifications <a
                 * href="#">view all</a></span>
                 * <div class="nofity-list">
                 * <a href="#" class="notify-item">
                 * <div class="notify-thumb">
                 * <i class="ti-key btn-danger"></i>
                 * </div>
                 * <div class="notify-text">
                 * <p>You have Changed Your Password</p>
                 * <span>Just Now</span>
                 * </div>
                 * </a> <a href="#" class="notify-item">
                 * <div class="notify-thumb">
                 * <i class="ti-comments-smiley btn-info"></i>
                 * </div>
                 * <div class="notify-text">
                 * <p>New Commetns On Post</p>
                 * <span>30 Seconds ago</span>
                 * </div>
                 * </a> <a href="#" class="notify-item">
                 * <div class="notify-thumb">
                 * <i class="ti-key btn-primary"></i>
                 * </div>
                 * <div class="notify-text">
                 * <p>Some special like you</p>
                 * <span>Just Now</span>
                 * </div>
                 * </a> <a href="#" class="notify-item">
                 * <div class="notify-thumb">
                 * <i class="ti-comments-smiley btn-info"></i>
                 * </div>
                 * <div class="notify-text">
                 * <p>New Commetns On Post</p>
                 * <span>30 Seconds ago</span>
                 * </div>
                 * </a> <a href="#" class="notify-item">
                 * <div class="notify-thumb">
                 * <i class="ti-key btn-primary"></i>
                 * </div>
                 * <div class="notify-text">
                 * <p>Some special like you</p>
                 * <span>Just Now</span>
                 * </div>
                 * </a> <a href="#" class="notify-item">
                 * <div class="notify-thumb">
                 * <i class="ti-key btn-danger"></i>
                 * </div>
                 * <div class="notify-text">
                 * <p>You have Changed Your Password</p>
                 * <span>Just Now</span>
                 * </div>
                 * </a> <a href="#" class="notify-item">
                 * <div class="notify-thumb">
                 * <i class="ti-key btn-danger"></i>
                 * </div>
                 * <div class="notify-text">
                 * <p>You have Changed Your Password</p>
                 * <span>Just Now</span>
                 * </div>
                 * </a>
                 * </div>
                 * </div></li>
                 * <li class="dropdown"><i class="fa fa-envelope-o dropdown-toggle"
                 * data-toggle="dropdown"><span>3</span></i>
                 * <div class="dropdown-menu notify-box nt-enveloper-box">
                 * <span class="notify-title">You have 1 new notifications <a
                 * href="#">view all</a></span>
                 * <div class="nofity-list">
                 * <a href="#" class="notify-item">
                 * <div class="notify-thumb">
                 * <img
                 * src="<?php echo htmlentities($site_data->website_image_path);?>template1/author/author-img1.jpg"
                 * alt="image">
                 * </div>
                 * <div class="notify-text">
                 * <p>Aglae Mayer</p>
                 * <span class="msg">Hey I am waiting for you...</span> <span>3:15
                 * PM</span>
                 * </div>
                 * </a>
                 * </div>
                 * </div></li>
                 * <li class="settings-btn"><i class="ti-settings"></i></li><?php
                 */
                                                ?>
                                                <li>

                                                    <?php
                                                    /*
                 * ?><div class="language horiz-user-profile m-0">
                 * <h4 class="user-name btn btn-rounded btn-info dropdown-toggle text-uppercase" data-toggle="dropdown">
                 * <?php
                 * if(isset($_SESSION['USER_DETAILS']['language_id'])){
                 * $language_id=$_SESSION['USER_DETAILS']['language_id'];
                 *
                 * $lang_qry = "SELECT lang_id,lang_code_2d,lang_name_lc FROM master.m_langauage where lang_id=$language_id order by lang_id";
                 * $sel_lang = $this->prepare($lang_qry);
                 *
                 * foreach($sel_lang as $key_lang => $lang){
                 * echo htmlentities($lang['lang_code_2d']);
                 * } } else { echo "EN"; $_SESSION['USER_DETAILS']['language_id']=2;} ?><i class="fa fa-angle-down"></i>
                 * </h4>
                 * <div class="dropdown-menu">
                 * <?php
                 * $lang_qry = "SELECT lang_id,lang_code_2d,lang_name_lc FROM master.m_langauage order by lang_id";
                 * $sel_lang = $this->prepare($lang_qry);
                 *
                 * foreach($sel_lang as $key_lang => $lang){
                 * ?>
                 * <a class="dropdown-item language_id" data-langID="<?php echo $lang['lang_id']?>"><?php echo $lang['lang_name_lc']?></a>
                 * <?php } ?>
                 * </div>
                 *
                 *
                 *
                 * </div><?php
                 */
                                                    ?>

                                                    <div class="selectLanguage">
                                                        <select id="cmb_language" name="cmb_language">
                                                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                                                            <?php
                                                            $sel_lang = "SELECT lang_id,lang_code_2d,lang_name_lc FROM master.m_langauage where del_flag is null and  lower(lang_name_lc)=lower(lang_name_lc:)";
                                                            $sel_lang_res = $this->prepare($sel_lang, array(
                                                                ":lang_name_lc" => 'English'
                                                            ), 4);


                                                            $lang_qry = "SELECT lang_id,lang_code_2d,lang_name_lc FROM master.m_langauage where del_flag is null  order by lang_id";
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
                                                    </div>

                                                </li>
                                            </ul>
                                        </div>
                                        <div class="clearfix d-md-inline-block d-block">
                                            <div class="user-profile horiz-user-profile horiz m-0">
                                                <img class="avatar user-thumb" src="<?php

                                                                                    echo htmlentities($site_data->website_image_path);
                                                                                    ?>template1/author/avatar.png" alt="avatar">
                                                <h4 class="user-name dropdown-toggle" data-toggle="dropdown">
                                                    <?php

                                                    echo htmlentities($user_name);
                                                    ?> <i class="fa fa-angle-down"></i>
                                                </h4>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#"><?php

                                                                                        echo $this->GetLable(741, "Message");
                                                                                        ?></a>
                                                    <a class="dropdown-item hide" href="#" onClick="BugReport('<?php

                                                                                                                    echo htmlentities($site_data->website_url);
                                                                                                                    ?>','<?php

                        echo $site_data->website_js_path ?>')" >Bug Report</a>
                                                    <a class="dropdown-item" href="<?php

                                                                                    echo $site_data->website_form_path;
                                                                                    ?>admin/UserSetting.php"><?php

                                            echo $this->GetLable(3, "Setting");
                                            ?></a>
                                                    <a class="dropdown-item" href="<?php

                                                                                    echo $site_data->website_form_path;
                                                                                    ?>logout.php"><?php

                                echo $this->GetLable(740, "Logout");
                                ?></a>

                                                </div>
                                            </div>

                                            <?php
                                            /* ?><span style="color:#FFF; text-align:center"><?php */
                                            ?>
                                            <span style="color:#FF0; text-align:center;"><strong>
                                                    <?php

                                                    if (!$this->issetCurrentUserLanguage2D())
                                                        $this->setCurrentUserLanguage2D('en');
                                                    $loc_disp = array();

                                                    if (isset($_SESSION['USER_DETAILS']['USER_PROFILE']['OFFICE_DETAILS']['lbody_name_' . ($this->getCurrentUserLanguage2D())])) {

                                                        $loc_disp[] = $_SESSION['USER_DETAILS']['USER_PROFILE']['OFFICE_DETAILS']['lbody_name_' . ($this->getCurrentUserLanguage2D())];
                                                    } else if (isset($_SESSION['USER_DETAILS']['USER_PROFILE']['OFFICE_DETAILS']['lbody_name_en'])) {
                                                        $loc_disp[] = $_SESSION['USER_DETAILS']['USER_PROFILE']['OFFICE_DETAILS']['lbody_name_en'];
                                                    }
                                                    ?>

                                                    <?php
                                                    if (isset($_SESSION['USER_DETAILS']['USER_PROFILE']['OFFICE_DETAILS']['district_name_' . ($this->getCurrentUserLanguage2D())])) {

                                                        $loc_disp[] = $_SESSION['USER_DETAILS']['USER_PROFILE']['OFFICE_DETAILS']['district_name_' . ($this->getCurrentUserLanguage2D())];
                                                    } else if (isset($_SESSION['USER_DETAILS']['USER_PROFILE']['OFFICE_DETAILS']['district_name_en'])) {
                                                        $loc_disp[] = $_SESSION['USER_DETAILS']['USER_PROFILE']['OFFICE_DETAILS']['district_name_en'];
                                                    }

                                                    echo implode(',', $loc_disp);
                                                    ?>

                                                </strong></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- main header area end -->
                        <!-- header area start -->

                        <nav class="navbar navbar-expand-md navbar-default bg-default navbar-hover header-area header-bottom" style="/* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#ffffff+0,f1f1f1+50,e1e1e1+51,f6f6f6+100;White+Gloss+%231 */
background: rgb(255,255,255); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(241,241,241,1) 50%, rgba(225,225,225,1) 51%, rgba(246,246,246,1) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(241,241,241,1) 50%,rgba(225,225,225,1) 51%,rgba(246,246,246,1) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom,  rgba(255,255,255,1) 0%,rgba(241,241,241,1) 50%,rgba(225,225,225,1) 51%,rgba(246,246,246,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f6f6f6',GradientType=0 ); /* IE6-9 */
">
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHover" aria-controls="navbarDD" aria-expanded="false" aria-label="Navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse" id="navbarHover">
                                <ul class="navbar-nav">
                                    <li class="nav-item active"><a class="nav-link" href="<?php

                                                                                            echo htmlentities($site_data->website_url);
                                                                                            ?>project/home.php" style="font-size:14px; font-weight:600;  padding-top:4px;padding-bottom:4px; padding-left:30px;"><i class="ti-home" style="font-size:14px; font-weight:800;"></i></a></li>
                                    <?php
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

                                        $query_exist_level_control = "SELECT STRING_AGG(menuid::text,',') as menuid_list FROM master.mst_menu_user_level_control where role_code::integer=:role_code and security_id=:security_id and user_profile_id=:user_profile_id and menuid in (select a.menuid from master.mst_menu_development a , master.mst_menuconfig b  where b.roleid::integer=:role_code and a.menuid=b.menuid and submenuid=:submenuid and a.rflag=:rflag and b.isactive=:isactive $cond) and isactive=:isactive and del_flag is null";
                                        $exist_exist_level_control = $this->prepare($query_exist_level_control, array_merge(array(
                                            ":role_code" => $role_code, ":security_id" => $security_id, ":user_profile_id" => $user_profile_id, ":submenuid" => 0, ":rflag" => 1, ":isactive" => 1
                                        ), $cond_array), 4); //var_dump($exist_exist_level_control);exit;

                                        $menuid_list = array();
                                        $menuid_list_cnt = 0;
                                        if (isset($exist_exist_level_control['menuid_list']) && $exist_exist_level_control['menuid_list'] != '') {
                                            $menuid_list = explode(",", $exist_exist_level_control['menuid_list']);
                                            $menuid_list_cnt = count($menuid_list);
                                            $menuid_list = array_combine(
                                                array_map(function ($i) {
                                                    return ':menuid_list' . $i;
                                                }, array_keys($menuid_list)),
                                                $menuid_list
                                            );
                                            $menuid_list_cond = " and a.menuid in (" . implode(',', array_keys($menuid_list)) . ")";
                                        }

                                        if ($menuid_list_cnt > 0) {
                                            $query = "select * from master.mst_menu_development a , master.mst_menuconfig b  where b.roleid=:role_code and a.menuid=b.menuid $menuid_list_cond and a.rflag=:rflag and b.isactive=:isactive $cond order by menu_order_no desc";
                                            $menu = $this->prepare($query, array_merge(array(
                                                ":role_code" => $role_code,
                                                ":rflag" => 1,
                                                ":isactive" => 1
                                            ), $cond_array, $menuid_list), 2); //var_dump($menu);exit;
                                        } else {
                                            $menu = array();
                                        }
                                    } else {

                                        $query = "select * from master.mst_menu_development a , master.mst_menuconfig b  where b.roleid=:role_code and a.menuid=b.menuid and submenuid=:submenuid and a.rflag=:rflag and b.isactive=:isactive $cond order by menu_order_no desc";
                                        $menu = $this->prepare($query, array_merge(array(
                                            ":role_code" => $role_code,
                                            ":submenuid" => 0,
                                            ":rflag" => 1,
                                            ":isactive" => 1
                                        ), $cond_array), 2);
                                    }

                                    if (count($menu) > 0) {
                                        $menuscript = "";
                                        foreach ($menu as $key => $menu_row) {

                                            if ($user_language == 'en')
                                                $desc = trim($menu_row["menu_desc"]);
                                            else if ($user_language == 'ta')
                                                $desc = trim($menu_row["menu_desc_ta"]) == "" ? trim($menu_row["menu_desc"]) : trim($menu_row["menu_desc_ta"]);
                                            else
                                                $desc = trim($menu_row["menu_desc"]);

                                            $url = trim($menu_row["url"]); // echo htmlentities($url);
                                            $menuid = $menu_row["menuid"];
                                            $target_cond = "";

                                            if ($menu_row["new_tab"] == 'Y') {
                                                $target_cond = "target='_blank'";
                                            }
                                            $submenu = $this->getConfigSubMenu_horizontal($menuid, $site_data);

                                            if ($submenu['display_code'] == 2 && $url != '') {
                                                $menu_url = "'" . $site_data->website_url . "project/" . $url . "'";
                                                $menuscript .= "
				<li class='nav-item'>
				<a class='nav-link' style='font-size:14px; font-weight:600; padding-top:4px;padding-bottom:4px;' href=$menu_url $target_cond><i class='ti-layers-alt'></i> 
				<span>$desc</span>
				</a></li>
				"; // $url
                                            } else if ($submenu['display_code'] == 3) {
                                                $menuscript .= "
				<li class='nav-item dropdown'>  
					<a class='nav-link dropdown-toggle' href='javascript:void(0)' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' style='font-size:14px; font-weight:600; padding-top:4px;padding-bottom:4px;' $target_cond><i class='ti-layers-alt'></i><span>$desc</span></a>" .
                                                    $submenu['menuscript'] .
                                                    "</li>
				";
                                            }
                                        }

                                        echo $menuscript;
                                    }
                                    ?>
                                </ul>
                            </div>
                        </nav>

                    </div>
                    <!-- header area end -->
                    <!-- page title area end -->
                    <div class="main-content-inner">
                    <?php
                    }
                    if ($part == "FOOT") {

                        // print_r($this->prepare("select role_name,role_desc from security.m_role "));

                    ?>
                    </div>
                    <!-- main content area end -->
                    <!-- footer area start-->
                </div>
                <footer>
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
                        //print_r($_SESSION); 
                        if ($this->issetCurrentUserProfileID()) { ?>
                        <script src="<?php echo htmlentities($site_data->website_js_path); ?>Master_Data_Local_Storage.js"></script>
                    <?php
                        } else {
                    ?>
                        <script src="<?php echo htmlentities($site_data->website_js_path); ?>Master_Data_Local_Storage_Public.js"></script>
                    <?php
                        }
                    ?>


                    <div class=" footer-area">
                        <!--<hr class="hr-text m-0 py-0">-->
                        <div class="d-flex col-md-11 m-auto pb-2">
                            <div class="col-md-4"><?php

                                                    echo $this->GetLable(742, "Last Update");
                                                    ?>: <?php

                        echo date("d-m-Y");
                    ?></div>
                            <div class="col-md-3 text-center">
                                <?php
                                if ($this->issetCurrentUserSecurityID()) {
                                ?> <a href="<?php echo $this->siteData()->website_url; ?>project/forms/admin/UserSetting.php?page=bXlfYWN0aXZpdHk="><?php

                                                                                                                            echo $this->GetLable(745, "Last Login Date");
                                                                                                                            ?>: <?php

                                    echo  $this->getLastLoginActivity($this->getCurrentUserSecurityID(), 1)->last_login_activity_date;

            ?></a>
                                    <br />
                                    <?php

                                    echo $this->GetLable(746, "Last Login IP");
                                    ?>: <?php
                                    echo  $this->getLastLoginActivity($this->getCurrentUserSecurityID(), 1)->last_login_activity_ip;
                    ?>

                                <?php } ?>
                            </div>
                            <div class="col-md-5 text-right"><?php

                                                                echo $this->GetLable(743, "Designed, Developed &amp; Maintained by NIC.");
                                                                ?></div>
                        </div>

                    </div>
                </footer>
                <!-- footer area end-->
                </div>
            <?php
                    }
                }
            }

            public function Template1_html($part = "", $pageTitle = "", $breadcrumbs = array(), $extra_args = array())
            {
                $site_data = $this->siteData();

                $menu_type = $_SESSION['USER_DETAILS']['USER_PROFILE']['menu_type'];

                if (!isset($_SESSION['USER_DETAILS'])) {
                    echo "<br><br><center><h3><font color='red'>Session Timeout:Please Login Again</font></center>";
                    $delay = "1";
                    die('<meta http-equiv="refresh" content="' . $delay . ';URL=' . $site_data->website_url . '">');
                }

                $user_name = $_SESSION['USER_DETAILS']['USER_PROFILE']['user_first_name'];

                if ($part == "HEAD") {
                    if (!isset($_SESSION)) {
                        session_start();
                    }

            ?>
            <!doctype html>
            <html class="no-js" lang="en">

            <head>
                <?php
                    /* ?><meta charset="utf-8"><?php */
                ?>
                <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1,shrink-to-fit=no">
                <meta name="description" content="">
                <meta name="author" content="">
                <meta http-equiv="x-ua-compatible" content="ie=edge">
                <title DisplayLabelID="20">Directorate of Town Panchayats, Tamil Nadu</title>
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <!--<link rel="shortcut icon" type="image/png" href="<?php

                                                                        echo htmlentities($site_data->website_image_path);
                                                                        ?>template1/icon/favicon.ico">-->
                <link rel="stylesheet" href="<?php

                                                echo htmlentities($site_data->website_css_path);
                                                ?>template1/bootstrap.min.css">
                <link rel="stylesheet" href="<?php

                                                echo htmlentities($site_data->website_css_path);
                                                ?>template1/font-awesome.min.css">
                <link rel="stylesheet" href="<?php

                                                echo htmlentities($site_data->website_css_path);
                                                ?>template1/themify-icons.css">
                <link rel="stylesheet" href="<?php

                                                echo htmlentities($site_data->website_css_path);
                                                ?>template1/metisMenu.css">
                <link rel="stylesheet" href="<?php

                                                echo htmlentities($site_data->website_css_path);
                                                ?>template1/owl.carousel.min.css">
                <link rel="stylesheet" href="<?php

                                                echo htmlentities($site_data->website_css_path);
                                                ?>template1/slicknav.min.css">
                <!-- others css -->
                <link rel="stylesheet" href="<?php

                                                echo htmlentities($site_data->website_css_path);
                                                ?>template1/typography.css">
                <?php
                    /*
             * ?><link rel="stylesheet"
             * href="<?php echo htmlentities($site_data->website_css_path);?>template1/default-css.css"><?php
             */
                ?>
                <link rel="stylesheet" href="<?php

                                                echo htmlentities($site_data->website_css_path);
                                                ?>template1/styles.css">
                <link rel="stylesheet" href="<?php

                                                echo htmlentities($site_data->website_css_path);
                                                ?>template1/responsive.css">

                <link rel="stylesheet" href="<?php

                                                echo htmlentities($site_data->website_css_path);
                                                ?>template1/small-business.css">

                <link rel="stylesheet" href="<?php

                                                echo htmlentities($site_data->website_css_path);
                                                ?>fontawesome-free-5.15.3-web/css/all.css">
                <link href="<?php

                            echo htmlentities($site_data->website_css_path);
                            ?>googleapis/googleapis.css" rel="stylesheet">

                <link rel="stylesheet" href="<?php

                                                echo htmlentities($site_data->website_css_path);
                                                ?>gijgo.datepicker.min.css">
                <link rel="stylesheet" href="<?php

                                                echo htmlentities($site_data->website_css_path);
                                                ?>Master_Tax_Form_Common_Validation.css">

                <link rel="stylesheet" href="<?php

                                                echo htmlentities($site_data->website_css_path);
                                                ?>jquery.multiselect.css">


                <link rel="stylesheet" href="<?php

                                                echo htmlentities($site_data->website_css_path);
                                                ?>jquery-ui.css">
                <link rel="stylesheet" href="<?php

                                                echo htmlentities($site_data->website_css_path);
                                                ?>dataTables.jqueryui.min.css">
                <link rel="stylesheet" href="<?php

                                                echo htmlentities($site_data->website_css_path);
                                                ?>scroller.jqueryui.min.css">
                <!-- Start datatable css -->


                <!-- modernizr css -->
                <script type="text/javascript">
                    var website_url = "<?php

                                        echo $this->siteData()->website_url;
                                        ?>";
                </script>


                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>template1/vendor/modernizr-2.8.3.min.js"></script>
                <!-- jquery latest version -->
                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>template1/vendor/jquery-2.2.4.min.js"></script>
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
                                ?>gijgo.datepicker.min.js"></script>
                <script type="text/javascript">
                    window.onerror = function(msg, url, lineNo, columnNo, error) {
                        // ... handle error ...
                        return false;
                    }


                    var DisplayLabelID_JSON = {};

                    $(document).ready(function() {

                        <?php
                        /*
             * ?>$(".language_id").on("click", function(){
             *
             * var lang_id=btoa($(this).attr('data-langID'));
             * //alert(lang_id);
             * if(lang_id != '')
             * {
             * $.ajax({
             * method:'post',
             * url: "<?php echo htmlentities($site_data->website_url);?>project/ajax/AjaxGeneral.php",
             * data: {"lang_id":lang_id, "cmd":btoa(5) },
             * success: function (data){
             * if(data != '' && data == 'success')
             * {
             * //alert(data);
             * location.reload(true);
             * }
             * },
             * dataType: 'html'
             * });
             * return true;
             * }
             * });
             *
             *
             *
             * $.ajax({
             * method:'post',
             * url: "<?php echo htmlentities($site_data->website_url);?>project/ajax/AjaxLabelPopulate.php",
             * data: {"page_id":btoa(<?=isset($extra_args['page_id'])?$extra_args['page_id']:'13'?>), "cmd":btoa(1) },
             * success: function (data){
             * if(data != '' )
             * {
             * DisplayLabelID_JSON=data;
             * UpdateLabel();
             *
             * }
             * },
             * dataType: 'json'
             * });
             * <?php
             */
                        ?>


                    });

                    function UpdateLabel() {
                        $("*[DisplayLabelID]").each(function() {
                            var datalabelid = 'L' + $(this).attr('DisplayLabelID');
                            $(this).html(DisplayLabelID_JSON['Label'][datalabelid]);
                        });
                    }
                </script>
            </head>

            <body class="insideBodyBg">
                <noscript>

                    <div class="awesome-fancy-styling">
                        This site requires JavaScript. I will only be visible if you have it disabled.
                    </div>
                    <meta http-equiv="refresh" content="2;url=/noScript.html" />
                </noscript>
                <!--[if lt IE 8]>
                    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
                <![endif]-->
                <!-- preloader area start -->
                <div id="preloader">
                    <div class="loader"></div>
                </div>
                <!-- preloader area end -->
                <!-- page container area start -->
                <?php

                    echo $this->menu_loader($part, $menu_type, $site_data, $user_name, $pageTitle, $breadcrumbs);
                ?>
                <!-- page container area end -->
                <!-- offset area start -->
                <div class="offset-area">
                    <div class="offset-close">
                        <i class="ti-close"></i>
                    </div>
                    <ul class="nav offset-menu-tab">
                        <li><a class="active" data-toggle="tab" href="#activity">Activity</a></li>
                        <li><a data-toggle="tab" href="#settings">Settings</a></li>
                    </ul>
                    <div class="offset-content tab-content">
                        <div id="activity" class="tab-pane fade in show active">
                            <div class="recent-activity">
                                <div class="timeline-task">
                                    <div class="icon bg1">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <div class="tm-title">
                                        <h4>Rashed sent you an email</h4>
                                        <span class="time"><i class="ti-time"></i>09:35</span>
                                    </div>
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse
                                        distinctio itaque at.</p>
                                </div>
                            </div>
                        </div>
                        <div id="settings" class="tab-pane fade">
                            <div class="offset-settings">
                                <h4>General Settings</h4>
                                <div class="settings-list">
                                    <div class="s-settings">
                                        <div class="s-sw-title">
                                            <h5>Notifications</h5>
                                            <div class="s-swtich">
                                                <input type="checkbox" id="switch1" /> <label for="switch1">Toggle</label>
                                            </div>
                                        </div>
                                        <p>Keep it 'On' When you want to get all the notification.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- offset area end -->

                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>commonValidation.js"></script>

                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>Master_Tax_Form_Common_Validation.js"></script>

                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>jquery.multiselect.js"></script>




                <?php
                    /*
             * ?>
             * <script
             * src="<?php echo htmlentities($site_data->website_js_path);?>jquery.min.js"></script>
             * <?php
             */
                ?>

                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>jquery.jkey.min.js"></script>

                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>UserProfileEntry.js"></script>

                <!-- bootstrap 4 js -->
                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>template1/popper.min.js"></script>
                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>template1/bootstrap.min.js"></script>
                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>template1/owl.carousel.min.js"></script>
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
                                ?>template1/scripts.js"></script>

                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>CommonFunctions.js"></script>

                <script type="module" src="<?php

                                            echo htmlentities($site_data->website_js_path);
                                            ?>BugReport.js"></script>


                <style>
                    .breadcrumb {
                        font-weight: bold;
                    }

                    .breadcrumb-item+.breadcrumb-item::before {
                        content: ">";
                    }
                </style>

                <div class="container-fluid">
                    <?php

                    $page_url = $_SERVER['PHP_SELF'];
                    $File_name = explode('project/', $page_url)[1];

                    if (is_array($this->Get_Menu_File_Name_Details($File_name)) && count($this->Get_Menu_File_Name_Details($File_name)) > 0) {
                        $Menu_Bread_Crums = $this->Get_Menu_File_Name_Details($File_name);
                    ?>
                        <div class="row">
                            <div class="col-md-9">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <?php
                                        foreach (array_reverse($Menu_Bread_Crums['Data']) as $Menu_Bread_Crums_row => $Menu_Bread_Crums_res) {
                                        ?>
                                            <li class="breadcrumb-item" aria-current="page"><?php

                                                                                            echo htmlentities($Menu_Bread_Crums_res);
                                                                                            ?></li>
                                        <?php
                                        }
                                        ?>
                                    </ol>
                                </nav>
                            </div>
                            <div class="col-md-3 text-right font-weight-bold">
                                <div class="row" style="    display: -webkit-box;
    
  
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    
    margin-bottom: 1rem;
    list-style: none;
    background-color: #e9ecef;
    border-radius: .25rem; ">
                                    <div class="col-md-9" style="vertical-align: middle;line-height: normal; margin:auto; padding: .85rem; p-0"><span><?php

                                                                                                                                                        echo htmlentities($Menu_Bread_Crums['Data_Details']);
                                                                                                                                                        ?></span></div>
                                    <div class="col-md-3 p-0" style="vertical-align: middle;line-height: normal;  margin:auto;">

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

                                            echo htmlentities($site_data->website_js_path); ?>')" id="page_help" name="page_help" data-toggle="tooltip" title="help" class="btn btn-sm btn-success m-0  font-weight-bold"><i class="fa fa-question-circle" aria-hidden="true"></i></button>
                                        <?php }  ?>
                                        <button id="Load_Master_Data" name="Load_Master_Data" data-toggle="tooltip" title="Reload Master Data" class="btn btn-sm btn-success m-0  font-weight-bold" style="display:none;"><i class="fa fa-refresh" aria-hidden="true"></i></button>
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
                 * <input type="button" id="Load_Master_Data" name="Load_Master_Data" value="Reload Master Data" class="btn btn-sm btn-success pull-right font-weight-bold" style="display:none;" />
                 * </div>
                 * </div>
                 * <script type="text/javascript">
                 * $(document).ready(function(){
                 * //if (typeof $('.Reload_Local_Stroage')[0] !== typeof undefined && $('.Reload_Local_Stroage')[0] !== false)
                 * // {
                 * // $('#Load_Master_Data').show();
                 * // }
                 * // else
                 * // {
                 * // $('#Load_Master_Data').hide();
                 * // }
                 *
                 * if (typeof $('#page_lable_id')[0] !== typeof undefined && $('#page_lable_id')[0] !== false)
                 * {
                 * $('#Load_Master_Data').show();
                 * }
                 * else
                 * {
                 * $('#Load_Master_Data').hide();
                 * }
                 * });
                 * </script>
                 * <?php }
                 */
                        ?>
                    <?php
                    }
                    ?>

                    <?php
                    /*
             * ?><div class="row m-2">
             * <div class="col-md-10"></div>
             * <div class="col-md-2 text-right font-weight-bold">
             * <input type="button" id="Load_Master_Data" name="Load_Master_Data" value="Reload Master Data" class="btn btn-sm btn-success pull-right font-weight-bold" style="display:none;" />
             * </div>
             * </div><?php
             */
                    ?>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            //if (typeof $('.Reload_Local_Stroage')[0] !== typeof undefined && $('.Reload_Local_Stroage')[0] !== false) 
                            //			{
                            //				$('#Load_Master_Data').show();
                            //			}
                            //			else
                            //			{
                            //				$('#Load_Master_Data').hide();
                            //			}

                            if (typeof $('#page_lable_id')[0] !== typeof undefined && $('#page_lable_id')[0] !== false) {
                                $('#Load_Master_Data').show();
                            } else {
                                $('#Load_Master_Data').hide();
                            }
                        });
                    </script>
                <?php
                } else if ($part == "FOOT") {
                    echo $this->menu_loader($part, $menu_type, $site_data, $user_name, $pageTitle, $breadcrumbs);
                }
            }

            public function Template2_html($part = "", $pageTitle = "", $breadcrumbs = array(), $extra_args = array())
            {
                $site_data = $this->siteData();

                $menu_type = $_SESSION['USER_DETAILS']['USER_PROFILE']['menu_type'];

                if (!isset($_SESSION['USER_DETAILS'])) {
                    echo "<br><br><center><h3><font color='red'>Session Timeout:Please Login Again</font></center>";
                    $delay = "1";
                    die('<meta http-equiv="refresh" content="' . $delay . ';URL=' . $site_data->website_url . '">');
                }

                $user_name = $_SESSION['USER_DETAILS']['USER_PROFILE']['user_first_name'];

                if ($part == "HEAD") {
                    if (!isset($_SESSION)) {
                        session_start();
                    }

                ?>
                    <!doctype html>
                    <html class="no-js" lang="en">

                    <head>

                        <meta charset="utf-8">
                        <meta http-equiv="x-ua-compatible" content="ie=edge">
                        <title DisplayLabelID="20">Directorate of Town Panchayats, Tamil Nadu</title>
                        <meta name="viewport" content="width=device-width, initial-scale=1">
                        <!--<link rel="shortcut icon" type="image/png" href="<?php

                                                                                echo htmlentities($site_data->website_image_path);
                                                                                ?>template1/icon/favicon.ico">-->
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>template1/bootstrap.min.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>template1/font-awesome.min.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>template1/themify-icons.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>template1/metisMenu.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>template1/owl.carousel.min.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>template1/slicknav.min.css">
                        <!-- others css -->
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>template1/typography.css">
                        <?php
                        /*
             * ?><link rel="stylesheet"
             * href="<?php echo htmlentities($site_data->website_css_path);?>template1/default-css.css"><?php
             */
                        ?>
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>template1/styles.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>/small-business.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>template1/responsive.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>gijgo.datepicker.min.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>Master_Tax_Form_Common_Validation.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>jquery.multiselect.css">


                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>jquery-ui.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>dataTables.jqueryui.min.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>scroller.jqueryui.min.css">


                        <!-- Start datatable css -->
                        <link rel="stylesheet" type="text/css" href="<?php

                                                                        echo htmlentities($site_data->website_css_path);
                                                                        ?>jquery.dataTables.css">
                        <link rel="stylesheet" type="text/css" href="<?php

                                                                        echo htmlentities($site_data->website_css_path);
                                                                        ?>dataTables.bootstrap4.min.css">
                        <link rel="stylesheet" type="text/css" href="<?php

                                                                        echo htmlentities($site_data->website_css_path);
                                                                        ?>responsive.bootstrap.min.css">
                        <link rel="stylesheet" type="text/css" href="<?php

                                                                        echo htmlentities($site_data->website_css_path);
                                                                        ?>responsive.jqueryui.min.css">

                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>fontawesome-free-5.15.3-web/css/all.css">
                        <link href="<?php

                                    echo htmlentities($site_data->website_css_path);
                                    ?>googleapis/googleapis.css" rel="stylesheet">

                        <!-- modernizr css -->
                        <script type="text/javascript">
                            var website_url = "<?php

                                                echo $this->siteData()->website_url;
                                                ?>";
                        </script>

                        <script src="<?php

                                        echo htmlentities($site_data->website_js_path);
                                        ?>template1/vendor/modernizr-2.8.3.min.js"></script>
                        <!-- jquery latest version -->
                        <script src="<?php

                                        echo htmlentities($site_data->website_js_path);
                                        ?>template1/vendor/jquery-2.2.4.min.js"></script>

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
                                        ?>gijgo.datepicker.min.js"></script>
                        <script type="text/javascript">
                            var DisplayLabelID_JSON = {};

                            $(document).ready(function() {

                                <?php
                                /*
             * ?>$(".language_id").on("click", function(){
             *
             * var lang_id=btoa($(this).attr('data-langID'));
             * //alert(lang_id);
             * if(lang_id != '')
             * {
             * $.ajax({
             * method:'post',
             * url: "<?php echo htmlentities($site_data->website_url);?>project/ajax/AjaxGeneral.php",
             * data: {"lang_id":lang_id, "cmd":btoa(5) },
             * success: function (data){
             * if(data != '' && data == 'success')
             * {
             * //alert(data);
             * location.reload(true);
             * }
             * },
             * dataType: 'html'
             * });
             * return true;
             * }
             * });
             *
             *
             *
             *
             *
             * $.ajax({
             * method:'post',
             * url: "<?php echo htmlentities($site_data->website_url);?>project/ajax/AjaxLabelPopulate.php",
             * data: {"page_id":btoa(<?=isset($extra_args['page_id'])?$extra_args['page_id']:'13'?>), "cmd":btoa(1) },
             * success: function (data){
             * if(data != '' )
             * {
             * DisplayLabelID_JSON=data;
             * UpdateLabel();
             *
             * }
             * },
             * dataType: 'json'
             * });
             * <?php
             */
                                ?>


                            });

                            function UpdateLabel() {
                                $("*[DisplayLabelID]").each(function() {
                                    var datalabelid = 'L' + $(this).attr('DisplayLabelID');
                                    $(this).html(DisplayLabelID_JSON['Label'][datalabelid]);
                                });
                            }
                        </script>
                    </head>

                    <body>
                        <noscript>

                            <div class="awesome-fancy-styling">
                                This site requires JavaScript. I will only be visible if you have it disabled.
                            </div>
                            <meta http-equiv="refresh" content="2;url=/noScript.html" />
                        </noscript>
                        <!--[if lt IE 8]>
                    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
                <![endif]-->
                        <!-- preloader area start -->
                        <div id="preloader">
                            <div class="loader"></div>
                        </div>
                        <!-- preloader area end -->
                        <!-- page container area start -->

                        <!-- main header area start -->
                        <div class="fixed-top">
                            <div class="mainheader-area horiz-mainheader-area">
                                <div class="container">
                                    <div class="row align-items-center">
                                        <div class="col-md-12">
                                            <!--<div class="logo">
								<a href="#"><span
									style="color: #ffffff; font-size: 24px;"  DisplayLabelID="20"  >Directorate of Town Panchayats, Tamil Nadu</span></a>
							</div>-->
                                            <div>
                                                <div style="float:left; padding:3px">
                                                    <img src="<?php

                                                                echo htmlentities($site_data->website_url);
                                                                ?>images/tn_logo_top.png" width="60">
                                                </div>
                                                <div style="float:left;" class="pl-2">
                                                    <div style="padding-top:2px; line-height:36px;">
                                                        <a class="navbar-brand" href="<?php

                                                                                        echo htmlentities($site_data->website_url);
                                                                                        ?>project/home.php" style="color:white; font-weight:900;">
                                                            பேரூராட்சிகள் இயக்ககம், தமிழ்நாடு </a>
                                                    </div>
                                                    <div style="padding-top:0px; line-height:1px; text-indent:3px;"> <a class="navbar-headertitle" href="<?php

                                                                                                                                                            echo htmlentities($site_data->website_url);
                                                                                                                                                            ?>project/home.php" style="text-decoration: none;color:white;">Directorate of Town Panchayats, Tamil Nadu
                                                            <!--Rural Development & Panchayat Raj Department-->
                                                        </a></div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- page container area end -->
                        <!-- offset area start -->
                        <div class="offset-area">
                            <div class="offset-close">
                                <i class="ti-close"></i>
                            </div>



                        </div>
                </div>
                <!-- offset area end -->

                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>commonValidation.js"></script>

                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>Master_Tax_Form_Common_Validation.js"></script>

                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>jquery.multiselect.js"></script>


                <?php
                    /*
             * ?>
             * <script
             * src="<?php echo htmlentities($site_data->website_js_path);?>jquery.min.js"></script>
             * <?php
             */
                ?>


                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>jquery.jkey.min.js"></script>


                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>DesignationRoleLink.js"></script>

                <!-- bootstrap 4 js -->
                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>template1/popper.min.js"></script>
                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>template1/bootstrap.min.js"></script>
                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>template1/owl.carousel.min.js"></script>
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


                <!-- Start datatable js -->


                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>template1/scripts.js"></script>

                <script src="<?php

                                echo htmlentities($site_data->website_js_path);
                                ?>CommonFunctions.js"></script>


                <script type="module" src="<?php

                                            echo htmlentities($site_data->website_js_path);
                                            ?>BugReport.js"></script>

                <div class="container-fluid">


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
                    <!doctype html>
                    <html class="no-js" lang="en">

                    <head>

                        <meta charset="utf-8">
                        <meta http-equiv="x-ua-compatible" content="ie=edge">
                        <title DisplayLabelID="20">Directorate of Town Panchayats, Tamil Nadu</title>
                        <meta name="viewport" content="width=device-width, initial-scale=1">
                        <!--<link rel="shortcut icon" type="image/png" href="<?php

                                                                                echo htmlentities($site_data->website_image_path);
                                                                                ?>template1/icon/favicon.ico">-->
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>template1/bootstrap.min.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>template1/font-awesome.min.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>template1/themify-icons.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>template1/metisMenu.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>template1/owl.carousel.min.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>template1/slicknav.min.css">
                        <!-- others css -->
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>template1/typography.css">
                        <?php
                        /*
             * ?><link rel="stylesheet"
             * href="<?php echo htmlentities($site_data->website_css_path);?>template1/default-css.css"><?php
             */
                        ?>
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>template1/styles.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>/small-business.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>template1/responsive.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>gijgo.datepicker.min.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>Master_Tax_Form_Common_Validation.css">

                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>jquery.multiselect.css">

                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>jquery-ui.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>dataTables.jqueryui.min.css">
                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>scroller.jqueryui.min.css">


                        <!-- Start datatable css -->
                        <link rel="stylesheet" type="text/css" href="<?php

                                                                        echo htmlentities($site_data->website_css_path);
                                                                        ?>jquery.dataTables.css">
                        <link rel="stylesheet" type="text/css" href="<?php

                                                                        echo htmlentities($site_data->website_css_path);
                                                                        ?>dataTables.bootstrap4.min.css">
                        <link rel="stylesheet" type="text/css" href="<?php

                                                                        echo htmlentities($site_data->website_css_path);
                                                                        ?>responsive.bootstrap.min.css">
                        <link rel="stylesheet" type="text/css" href="<?php

                                                                        echo htmlentities($site_data->website_css_path);
                                                                        ?>responsive.jqueryui.min.css">

                        <link rel="stylesheet" href="<?php

                                                        echo htmlentities($site_data->website_css_path);
                                                        ?>fontawesome-free-5.15.3-web/css/all.css">
                        <link href="<?php

                                    echo htmlentities($site_data->website_css_path);
                                    ?>googleapis/googleapis.css" rel="stylesheet">

                        <!-- modernizr css -->

                        <script type="text/javascript">
                            var website_url = "<?php

                                                echo $this->siteData()->website_url;
                                                ?>";
                        </script>

                        <script src="<?php

                                        echo htmlentities($site_data->website_js_path);
                                        ?>template1/vendor/modernizr-2.8.3.min.js"></script>
                        <!-- jquery latest version -->
                        <script src="<?php

                                        echo htmlentities($site_data->website_js_path);
                                        ?>template1/vendor/jquery-2.2.4.min.js"></script>

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
                                        ?>gijgo.datepicker.min.js"></script>
                        <script type="text/javascript">
                            var DisplayLabelID_JSON = {};

                            $(document).ready(function() {

                                <?php
                                /*
             * ?>$(".language_id").on("click", function(){
             *
             * var lang_id=btoa($(this).attr('data-langID'));
             * //alert(lang_id);
             * if(lang_id != '')
             * {
             * $.ajax({
             * method:'post',
             * url: "<?php echo htmlentities($site_data->website_url);?>project/ajax/AjaxGeneral.php",
             * data: {"lang_id":lang_id, "cmd":btoa(5) },
             * success: function (data){
             * if(data != '' && data == 'success')
             * {
             * //alert(data);
             * location.reload(true);
             * }
             * },
             * dataType: 'html'
             * });
             * return true;
             * }
             * });
             *
             *
             *
             * $.ajax({
             * method:'post',
             * url: "<?php echo htmlentities($site_data->website_url);?>project/ajax/AjaxLabelPopulate.php",
             * data: {"page_id":btoa(<?=isset($extra_args['page_id'])?$extra_args['page_id']:'13'?>), "cmd":btoa(1) },
             * success: function (data){
             * if(data != '' )
             * {
             * DisplayLabelID_JSON=data;
             * UpdateLabel();
             *
             * }
             * },
             * dataType: 'json'
             * });
             * <?php
             */
                                ?>


                            });

                            function UpdateLabel() {
                                $("*[DisplayLabelID]").each(function() {
                                    var datalabelid = 'L' + $(this).attr('DisplayLabelID');
                                    $(this).html(DisplayLabelID_JSON['Label'][datalabelid]);
                                });
                            }
                        </script>


                    </head>

                    <body>
                        <noscript>

                            <div class="awesome-fancy-styling">
                                This site requires JavaScript. I will only be visible if you have it disabled.
                            </div>
                            <meta http-equiv="refresh" content="2;url=/noScript.html" />
                        </noscript>


                        <!--[if lt IE 8]>
                    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
                <![endif]-->
                        <!-- preloader area start -->
                        <div id="preloader">
                            <div class="loader"></div>
                        </div>
                        <!-- preloader area end -->
                        <!-- page container area start -->

                        <!-- main header area start -->


                        <div class="horizontal-main-wrapper">
                            <!-- main header area start -->
                            <div class="fixed-top">
                                <div class="mainheader-area horiz-mainheader-area">
                                    <div class="container">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <!--<div class="logo">
							<a href="<?php

                                        echo htmlentities($site_data->website_url);
                                        ?>project/home.php"><span
								style="color: #ffffff; font-size: 24px;"  DisplayLabelID="20"  >Directorate of Town Panchayats, Tamil Nadu</span></a>
						</div>-->
                                                <div>
                                                    <div style="float:left; padding:3px">
                                                        <img src="<?php

                                                                    echo htmlentities($site_data->website_url);
                                                                    ?>images/tn_logo_top.png" width="60">
                                                    </div>
                                                    <div style="float:left;" class="pl-2">
                                                        <div style="padding-top:2px; line-height:36px;">
                                                            <a class="navbar-brand" href="<?php

                                                                                            echo htmlentities($site_data->website_url);
                                                                                            ?>" style="color:white; font-weight:900;">
                                                                பேரூராட்சிகள் இயக்ககம், தமிழ்நாடு</a>
                                                        </div>
                                                        <div style="padding-top:0px; line-height:1px; text-indent:3px;"> <a class="navbar-headertitle" href="<?php

                                                                                                                                                                echo htmlentities($site_data->website_url);
                                                                                                                                                                ?>" style="text-decoration: none;color:white;">Directorate of Town Panchayats, Tamil Nadu
                                                                <!--Rural Development & Panchayat Raj Department-->
                                                            </a></div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- profile info & task notification -->
                                            <div class="col-md-6 clearfix text-right">
                                                <div class="d-md-inline-block d-block">
                                                    <ul class="notification-area horiz-notification-area">


                                                        <li>
                                                            <div class="selectLanguage">
                                                                <select id="cmb_language" name="cmb_language">
                                                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                                                    <?php
                                                                    $sel_lang = "SELECT lang_id,lang_code_2d,lang_name_lc FROM master.m_langauage where del_flag is null and  lower(lang_name_lc)=lower(lang_name_lc:)";
                                                                    $sel_lang_res = $this->prepare($sel_lang, array(
                                                                        ":lang_name_lc" => 'English'
                                                                    ), 4);


                                                                    $lang_qry = "SELECT lang_id,lang_code_2d,lang_name_lc FROM master.m_langauage where del_flag is null  order by lang_id";
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

                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                               <?php /* <div class="clearfix d-md-inline-block d-block">
                                                    <div class="user-profile horiz-user-profile horiz m-0" style=" visibility:hidden">
                                                        <img class="avatar user-thumb" src="<?php

                                                                                            echo htmlentities($site_data->website_image_path);
                                                                                            ?>template1/author/avatar.png" alt="avatar">
                                                        <h4 class="user-name dropdown-toggle" data-toggle="dropdown">
                                                            <?php

                                                            echo htmlentities($user_name);
                                                            ?>



                                                            <i class="fa fa-angle-down"></i>
                                                        </h4>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="#"><?php

                                                                                                echo $this->GetLable(741, "Message");
                                                                                                ?></a>
                                                            <!--<a class="dropdown-item hide" href="#" onClick="BugReport('<?php

                                                                                                                            echo htmlentities($site_data->website_url);
                                                                                                                            ?>','<?php

                    echo $site_data->website_js_path ?>')" >Bug Report</a>-->
                                                            <a class="dropdown-item" href="<?php

                                                                                            echo $site_data->website_form_path;
                                                                                            ?>admin/UserSetting.php"><?php

                                        echo $this->GetLable(3, "Setting");
                                        ?></a>
                                                            <a class="dropdown-item" href="<?php

                                                                                            echo $site_data->website_form_path;
                                                                                            ?>logout.php"><?php

                            echo $this->GetLable(740, "Logout");
                            ?></a>
                                                        </div>
                                                    </div>
                                                </div> */ ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <nav class="navbar navbar-expand-md navbar-default bg-default navbar-hover header-area header-bottom" style="/* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#ffffff+0,f1f1f1+50,e1e1e1+51,f6f6f6+100;White+Gloss+%231 */
background: rgb(255,255,255); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(241,241,241,1) 50%, rgba(225,225,225,1) 51%, rgba(246,246,246,1) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(241,241,241,1) 50%,rgba(225,225,225,1) 51%,rgba(246,246,246,1) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom,  rgba(255,255,255,1) 0%,rgba(241,241,241,1) 50%,rgba(225,225,225,1) 51%,rgba(246,246,246,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f6f6f6',GradientType=0 ); /* IE6-9 */
">
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHover" aria-controls="navbarDD" aria-expanded="false" aria-label="Navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse" id="navbarHover">
                                <ul class="navbar-nav">
                                    <li class="nav-item active"><a class="nav-link" href="<?php

                                                                                            echo htmlentities($site_data->website_url);
                                                                                            ?>" style="font-size:14px; font-weight:600;  padding-top:4px;padding-bottom:4px; padding-left:30px;"><i class="ti-home" style="font-size:14px; font-weight:800;"></i></a></li>
                                    <?php
                                    $role_code = $_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'];
                                    $user_language = $this->issetCurrentUserLanguage2D() ? $this->getCurrentUserLanguage2D() : 'en';

                                        $query = "select * from master.mst_menu_development a , master.mst_menuconfig b  where b.roleid=:role_code and a.menuid=b.menuid and submenuid=:submenuid and a.rflag=:rflag and b.isactive=:isactive order by menu_order_no desc";
                                        $menu = $this->prepare($query, array(
                                            ":role_code" => $role_code,
                                            ":submenuid" => 0,
                                            ":rflag" => 1,
                                            ":isactive" => 1
                                        ), 2);
                                  

                                    if (count($menu) > 0) {
                                        $menuscript = "";
                                        foreach ($menu as $key => $menu_row) {

                                            if ($user_language == 'en')
                                                $desc = trim($menu_row["menu_desc"]);
                                            else if ($user_language == 'ta')
                                                $desc = trim($menu_row["menu_desc_ta"]) == "" ? trim($menu_row["menu_desc"]) : trim($menu_row["menu_desc_ta"]);
                                            else
                                                $desc = trim($menu_row["menu_desc"]);

                                            $url = trim($menu_row["url"]); // echo htmlentities($url);
                                            $menuid = $menu_row["menuid"];
                                            $target_cond = "";

                                            if ($menu_row["new_tab"] == 'Y') {
                                                $target_cond = "target='_blank'";
                                            }
                                            $submenu = $this->getConfigSubMenu_horizontal_openrole($menuid, $site_data);

                                            if ($submenu['display_code'] == 2 && $url != '') {
                                                $menu_url = "'" . $site_data->website_url . "project/" . $url . "'";
                                                $menuscript .= "
				<li class='nav-item'>
				<a class='nav-link' style='font-size:14px; font-weight:600; padding-top:4px;padding-bottom:4px;' href=$menu_url $target_cond><i class='ti-layers-alt'></i> 
				<span>$desc</span>
				</a></li>
				"; // $url
                                            } else if ($submenu['display_code'] == 3) {
                                                $menuscript .= "
				<li class='nav-item dropdown'>  
					<a class='nav-link dropdown-toggle' href='javascript:void(0)' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' style='font-size:14px; font-weight:600; padding-top:4px;padding-bottom:4px;' $target_cond><i class='ti-layers-alt'></i><span>$desc</span></a>" .
                                                    $submenu['menuscript'] .
                                                    "</li>
				";
                                            }
                                        }

                                        echo $menuscript;
                                    }
                                    ?>
                                </ul>
                            </div>
                        </nav>
                            </div>
                            <!-- header area end -->
                            <!-- page title area end -->
                            <div class="main-content-inner">




                                <!-- offset area end -->

                                <script src="<?php

                                                echo htmlentities($site_data->website_js_path);
                                                ?>commonValidation.js"></script>

                                <script src="<?php

                                                echo htmlentities($site_data->website_js_path);
                                                ?>Master_Tax_Form_Common_Validation.js"></script>

                                <script src="<?php

                                                echo htmlentities($site_data->website_js_path);
                                                ?>jquery.multiselect.js"></script>


                                <?php
                                /*
             * ?>
             * <script
             * src="<?php echo htmlentities($site_data->website_js_path);?>jquery.min.js"></script>
             * <?php
             */
                                ?>


                                <script src="<?php

                                                echo htmlentities($site_data->website_js_path);
                                                ?>jquery.jkey.min.js"></script>


                                <script src="<?php

                                                echo htmlentities($site_data->website_js_path);
                                                ?>DesignationRoleLink.js"></script>

                                <!-- bootstrap 4 js -->
                                <script src="<?php

                                                echo htmlentities($site_data->website_js_path);
                                                ?>template1/popper.min.js"></script>
                                <script src="<?php

                                                echo htmlentities($site_data->website_js_path);
                                                ?>template1/bootstrap.min.js"></script>
                                <script src="<?php

                                                echo htmlentities($site_data->website_js_path);
                                                ?>template1/owl.carousel.min.js"></script>
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


                                <!-- Start datatable js -->


                                <script src="<?php

                                                echo htmlentities($site_data->website_js_path);
                                                ?>template1/scripts.js"></script>


                                <script src="<?php

                                                echo htmlentities($site_data->website_js_path);
                                                ?>CommonFunctions.js"></script>

                                <script type="module" src="<?php

                                                            echo htmlentities($site_data->website_js_path);
                                                            ?>BugReport.js"></script>

                                <div class="container-fluid">


                        <?php
                    } else if ($part == "FOOT") {
                        echo $this->menu_loader($part, null, $site_data, null, $pageTitle, $breadcrumbs);
                    }
                }

                public function Get_Menu_File_Name_Details($url)
                {
                    $role_code = $_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'];
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