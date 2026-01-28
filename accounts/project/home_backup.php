<?php

require_once __DIR__ . '/config/config.php';

class Home extends ConfigClass
{
    public function __construct()
    {
        if (!isset($this->db)) {
        }
    }

    public function main_content()
    {
        $site_data = $this->siteData();

 ob_start();
$role_code=$_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'];
        
        // include_once('pageheader_new.php');

        // #############

        // PAGE CONTENT START

        // #############


?>


        <style>
            .workscheme{
                padding: 20px;
    margin: 20px;
    border-radius: 7px;
    box-shadow: 3px 3px 10px rgb(0 0 0 / 40%) inset;
    background: #fff;
            }
                .navbar-expand-lg {
                    border:none;
                    }
            .collapsing {
                transition: .01s ease !important;
            }

            body {
                font-family: poppins;
            }

            #works {
                margin: 0 auto;
                padding: 10px;
                margin-bottom: 10px;
                width: 90%;
                background: rgb(220 53 70 / 77%);
                color: black;
                font-weight: bold;
            }

            .work_prog_count {
                text-align: center;
                width: 270px;
                height: 170px;
                padding-top: 25px;
                margin: 0 45px;
                border-radius: 8px 8px 50px;
                box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
                background: linear-gradient(to right bottom, #fff 50%, #f9f9f9 51%);
                position: relative;
            }

            .work_prog_count.one:after {
                border-color: #3589b0;
            }

            .work_prog_count.one:before {
                border-color: #3589b0;
            }

            .work_prog_count.two:after {
                border-color: #4cc4b5;
                background: #37c3b4;
            }

            .work_prog_count.two:before {
                border-color: #4cc4b5;
            }

            .work_prog_count.three:after {
                border-color: #e5811d;
                background: #e5811d;
            }

            .work_prog_count.three:before {
                border-color: #e5811d;
            }

            .work_prog_count.four:after {
                border-color: #e14647;
                background: #e14647;
            }

            .work_prog_count.four:before {
                border-color: #e14647;
            }

            .work_prog_inner {
                padding: 15px;
                width: 70px;
                height: 70px;
                border-radius: 50px;
                box-shadow: none;
                bottom: auto;
                right: -28px;
                top: -28px;
                left: auto;
                position: absolute;
                font-size: 30px;
                line-height: 40px;
            }

            .work_prog_count.one .work_prog_inner {
                background: #2a88b1;
            }

            .work_prog_count.two .work_prog_inner {
                background: #37c3b4;
            }

            .work_prog_count.three .work_prog_inner {
                background: #ea8414;
            }

            .work_prog_count.four .work_prog_inner {
                background: #e74d48;
            }

            .work_prog_count .work_prog_count-value {
                font-size: 35px;
                font-weight: 600;
                display: block;
                margin: 20px 0 5px;
            }

            .work_prog_count h3 {
                font-size: 16px;
                font-weight: 600;
                margin: 0;
            }

            .scheme_work_prog_inner {
                width: 40px;
                height: 40px;
                border-radius: 50px;
                font-size: 18px;
                line-height: 40px;
                position: absolute;
                left: -18px;
                top: 35px;
            }

            .scheme_work_prog_count {
                text-align: center;
                height: 110px;
                padding: 5px;
                margin: 0 15px;
                border-radius: 10px;
                box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
                background: linear-gradient(to right bottom, #fff 50%, #f5f5f5 51%);
                position: relative;
                cursor: pointer;
            }

            .scheme_work_prog_count.one .scheme_work_prog_inner {
                background: #edae19;
            }

            .scheme_work_prog_count.two .scheme_work_prog_inner {
                background: #7678d5;
            }

            .scheme_work_prog_count.three .scheme_work_prog_inner {
                background: #f36a8e;
            }

            .scheme_work_prog_count.four .scheme_work_prog_inner {
                background: #b46dd7;
            }

            .scheme_work_prog_count-value {
                font-size: 28px;
                font-weight: 600;
                display: block;
                margin-top: 10px;
            }

            .scheme_card_header {
                font-size: 21px;
                margin-top: 5px;
            }

            table {
                border-color: #DCE0E3 !important;
            }

            b {
                font-weight: 700 !important;
            }

            select:focus-visible {
                outline: none;
            }

            .card_header {
                font-size: 25px;
                margin-top: 25px;
            }

            .w-70 {
                margin-top: -30px;
            }

            .secTitle {
                color: #03507e;
                font-weight: 700;
                font-size: 30px;
                text-align: center;
            }

            #loading-image {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                width: 100%;
                background: rgba(0, 0, 0, 0.75) url(../images/ajax_loader_blue_256.gif) no-repeat center center;
                z-index: 10000;
                background-size: 200px 200px;
            }

            .btn-custom {
                background-color: #007bff;
                color: white;
            }

            .btn-custom:hover {
                background-color: #0069d9;
                color: #ffffff;

            }

            .collapse {
                border: 1px solid #5a948c;
                padding: 10px;
                border-radius: 5px;
                margin-bottom: 3px;
            }
        </style>

        
        <script>
            function expandCollapse() {
                if ($(".submenu").css('display') == 'none') {
                    $("#expand-collapse").html("Collapse All");
                    $(".submenu").show("slow");
                } else {
                    $("#expand-collapse").html("Expand All");
                    $(".submenu").hide("slow");
                }
            }
        </script>
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
<!-- 
        <script>
            $(function() {
                $('.collapse_all').on('click', function() {
                    if ($(this).hasClass('activate')) {
                        $(".collapse").addClass("show")
                        $("#expand-collapse1").html("Collapse All");
                    } else {
                        $(".collapse").removeClass("show")
                        $("#expand-collapse1").html("Expand All");
                    }
                    $(this).toggleClass('activate')



                    $('.dis_down').each(function() {
                        if ($(this).attr('aria-expanded') == 'false') {
                            $(this).attr('aria-expanded', 'true')
                            $(this).find('i').removeClass('fa-minus').addClass('fa-plus')
                        } else {
                            $(this).attr('aria-expanded', 'false')
                            $(this).find('i').removeClass('fa-plus').addClass('fa-minus')
                        }
                    })
                });

                $('.dis_down').click(function() {

                    if ($(this).attr('aria-expanded') == 'false') {
                        $(this).attr('aria-expanded', 'true');
                         $(".collapse").addClass("show");
                        $(this).find('i').removeClass('fa-plus').addClass('fa-minus');
                    } else {
                        $(this).attr('aria-expanded', 'false');
                        $(this).find('i').removeClass('fa-minus').addClass('fa-plus');
                    }
                });
            });
        </script> -->

        <!-- <link href="home_tnrd_assets/css/app.css" rel="stylesheet"> -->
        <style>
            .scheme_wise .card-header {
                padding: 0.75rem 1.25rem;
                background-color: rgba(0, 0, 0, .03);
                border-bottom: 1px solid rgba(0, 0, 0, .125);
            }

            .scheme_wise .btn-link {
                font-weight: 400;
                color: #007bff;
                text-decoration: none;
                font-size: 16px;
            }

            .scheme_wise .card>.collapse {
                background: #f5f7fb;
                margin: 0;
            }

            .scheme_wise .btn-check:focus+.btn,
            .scheme_wise .btn:focus {
                box-shadow: unset;
            }

            .scheme_wise .btn-link:hover,
            .scheme_wise .btn-link:focus {
                color: #007bff;
            }

            .scheme_wise :focus-visible,
            .scheme_wise .btn:active:focus {
                outline: unset;
            }

            .mainmenu {
                width: 49%;
                padding: 0.85rem 1.25rem 0.75rem;
                margin: 3px 0;
                border-radius: 0 12px;
                background-color: #dde6ee;
                border: 1px solid #d2dde7;
            }

            .mainmenu:nth-child(odd) {
                background-color: #e9e9e9;
                border: 1px solid #e7e0e0;
            }

            .mainmenu:nth-child(odd) .content-text {
                box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
                background: #fff;
                padding: 5px 10px 10px;
                border-radius: 5px;
                height: 100%;
            }

            .mainmenu:nth-child(even) .scheme_work_prog_inner {
                right: 10px;
                left: unset;
                border-radius: 5px;
                position: absolute;
                top: -20px;
                width: 35px;
                height: 35px;
                line-height: 35px;
            }

            .mainmenu:nth-child(even) .scheme_work_prog_count {
                border-radius: 20px 0;
                background: linear-gradient(to right bottom, #fff 50%, #f5f5f5 51%);
                padding-top: 12px;
            }

            .mainmenu:nth-child(even) .scheme_work_prog_count.one {
                border: unset;
                border-bottom: 5px solid #c7637d;
            }

            .mainmenu:nth-child(even) .scheme_work_prog_count.two {
                border: unset;
                border-bottom: 5px solid #68a982;
            }

            .mainmenu:nth-child(even) .scheme_work_prog_count.three {
                border: unset;
                border-bottom: 5px solid #625591;
            }

            .mainmenu:nth-child(even) .scheme_work_prog_count.four {
                border: unset;
                border-bottom: 5px solid #dd8657;
            }

            .mainmenu:nth-child(even) .scheme_work_prog_count.one .scheme_work_prog_inner {
                background: #c7637d;
            }

            .mainmenu:nth-child(even) .scheme_work_prog_count.two .scheme_work_prog_inner {
                background: #68a982;
            }

            .mainmenu:nth-child(even) .scheme_work_prog_count.three .scheme_work_prog_inner {
                background: #625591;
            }

            .mainmenu:nth-child(even) .scheme_work_prog_count.four .scheme_work_prog_inner {
                background: #dd8657;
            }

            .mainmenu:nth-child(even) .one .content-text {
                color: #c7637d;
            }

            .mainmenu:nth-child(even) .two .content-text {
                color: #68a982;
            }

            .mainmenu:nth-child(even) .three .content-text {
                color: #625591;
            }

            .mainmenu:nth-child(even) .four .content-text {
                color: #dd8657;
            }

            .scheme_work_prog_count.one {
                background: #edae19;
            }

            .scheme_work_prog_count.two {
                background: #7678d5;
            }

            .scheme_work_prog_count.three {
                background: #f36a8e;
            }

            .scheme_work_prog_count.four {
                background: #b46dd7;
            }

            .one .content-text {
                color: #edae19;
            }

            .two .content-text {
                color: #7678d5;
            }

            .three .content-text {
                color: #f36a8e;
            }

            .four .content-text {
                color: #b46dd7;
            }

            .mainmenu a {
                margin: 10px;
                font-weight: 600;
                color: #007bff;
                text-decoration: none;
                font-size: 18px;
            }

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

            .btn-custom.active {
                /* background-color: #094586 !important; */
                background: #555a86;
                font-family: Arial, Helvetica, sans-serif;
                font-weight: bold;
                border-radius: 7px;
                color: #fff;
                /* border-color: #094586; */
                border-color: #555a86;
                font-size: 16px;
                position: relative;
            }
            .btn-custom.active:hover {
                box-shadow: 0 -300px 0 1px #2196F3 inset;
                border-color:#2196F3;
            }
            .btn-custom.active:active {
                color: #fff;
            }
            .buttoncontainer {
    display: grid;
    justify-content: end; /* Aligns the button to the right */
    align-items: start; /* Aligns the button to the top */
    padding: 10px;
}
            #expand-collapse1 {
            background-color: #555a86;
            font-family: Arial, Helvetica, sans-serif;
            font-size:15px;
            font-weight:bold;
            padding:10px;
            text-align: center;
            color: white;            
            box-shadow: 0 0 20px #eee;
            border-radius: 10px;
            display: block;
            border:none;
                /* margin-left: auto;
                display: flex;
                border: 1px solid #2980b9;
                border-radius: 5px;
                margin-right: 0;
                text-decoration: none;
                background: #fff; */
            }

            #expand-collapse1:hover{
                background-color:#2196F3;
                color:white;

            }

            .grid-container {
                column-count: 2;
                column-gap: 1em;
            }

            .grid-blocks {
                display: inline-block;
                width: 100%;
            }

            .submenu1 h6 {
                margin-bottom: 10px;
                font-size: 15px;
            }

            .submenu1 ol {
                font-weight: 500;
                margin-bottom: 5px;
            }

            .submenu1 ol li {
                list-style: none;
            }

            .submenu1 ol li a {
                text-decoration: none;
            }

            .submenu1 ol li a:hover {
                color: #2980b9;
            }

            .dis_down::after {
                content: " ";
                flex-shrink: 0;
                width: 18px;
                height: 18px;
                right: 10px;
                position: absolute;
                background-image: url('../images/assets/forward.svg');
                background-repeat: no-repeat;
                background-size: 18px;
            }

            button[aria-expanded='false']::after {
                transform: rotate(90deg);
            }
        </style>
        


        <div id="loading-image"></div>
        <div class="buttoncontainer">
                  <button id="expand-collapse1" class="collapse_all activate ms-auto">Expand All</button></div>
        <section class="container px-4">
        
             <div class="mt-3">
    <div id="accordion" class="grid-container workscheme">
        <?php
        $main_menu = "SELECT * FROM security.m_submenu1 
                      WHERE user_id=:user_id AND dept_id=:dept_id AND rflag=:rflag 
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
                        <b <?php if ($row['smenu_url'] != '') { ?>
                                onclick="window.location.href='<?php echo 'http://10.163.2.95/tndtp_egov/' . $row['smenu_url'] ?>'" 
                                role="button" 
                           <?php } ?>>
                            <?php echo $row['smenu_desc'] ?>
                        </b>
                    </span>
                </button>

                <div class="submenu1">
                    <div id="collapse<?php echo $key ?>" class="collapse" 
                         aria-labelledby="heading<?php echo $key ?>" 
                         data-parent="#accordion">
                         
                        <?php
                        $sub_menu = "SELECT ssmenu_desc as menu_desc, ssmenu_url as menu_url, ssmenu_id as menu_id 
                                     FROM security.m_submenu2 
                                     WHERE user_id=:user_id AND dept_id=:dept_id AND smenu_id=:menuid  
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
                                <h6 <?php if ($row1['menu_url'] != '') { ?>
                                        onclick="window.location.href='<?php echo 'http://10.163.2.95/tndtp_egov/' . $row1['menu_url'] ?>'" 
                                        role="button" 
                                    <?php } ?>>
                                    <?php echo $row1['menu_desc'] ?>
                                </h6>

                                <?php
                                $ssub_menu = "SELECT sssmenu_desc as menu_desc, sssmenu_url as menu_url, sssmenu_id as menu_id  
                                              FROM security.m_submenu3 
                                              WHERE ssmenu_id=:menuid AND user_id=:user_id AND dept_id=:dept_id AND rflag=:rflag 
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
                                                <a href="<?php echo $row2['menu_url'] ?>" 
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


    
        
          

        </section>


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


