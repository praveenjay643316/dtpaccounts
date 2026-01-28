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
 $user_first_name=$_SESSION['USER_DETAILS']['USER_PROFILE']['user_first_name'];
  $user_last_name=$_SESSION['USER_DETAILS']['USER_PROFILE']['user_last_name'];
$role_code=$_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'];
       #print_r($_SESSION);die; 
        // include_once('pageheader_new.php');

        // #############

        // PAGE CONTENT START

        // #############


?>
<style>
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
.scroll-element, .scroll-element div {
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
.scrollbar-inner > .scroll-element div
{
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
    -webkit-border-radius: 8px;
    -moz-border-radius: 8px;
    border-radius: 8px;
}
 
.scrollbar-inner > .scroll-element .scroll-element_track,
.scrollbar-inner > .scroll-element .scroll-bar {
    -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=40)";
    filter: alpha(opacity=40);
    opacity: 0.4;
}
 
.scrollbar-inner > .scroll-element .scroll-element_track { background-color: #e0e0e0; }
.scrollbar-inner > .scroll-element .scroll-bar { background-color: #c2c2c2; }
.scrollbar-inner > .scroll-element:hover .scroll-bar { background-color: #919191; }
.scrollbar-inner > .scroll-element.scroll-draggable .scroll-bar { background-color: #919191; }
 
 
/* update scrollbar offset if both scrolls are visible */
 
.scrollbar-inner > .scroll-element.scroll-x.scroll-scrolly_visible .scroll-element_track { left: -12px; }
.scrollbar-inner > .scroll-element.scroll-y.scroll-scrollx_visible .scroll-element_track { top: -12px; }
 
 
.scrollbar-inner > .scroll-element.scroll-x.scroll-scrolly_visible .scroll-element_size { left: -12px; }
.scrollbar-inner > .scroll-element.scroll-y.scroll-scrollx_visible .scroll-element_size { top: -12px; }
				</style>
                <style>

                body {
                    margin-left: 0px;
                    margin-right: 0px;
                    margin-bottom: 0px;
                    
                }

                table#footer{
                    position: fixed;
                    bottom: 0px;
                    align:right
                }


                .warning1 {
                    background-color: #9e292b;
                    background-image:url(images/warning.png);
                    min-height:15px;
                    color:#CCCCCC;
                    clear:both;
                    text-align:center;
                    vertical-align:middle;
                    border-collapse:collapse;
                    background-position:20px 50%;
                    background-repeat:no-repeat;
                    -moz-border-radius:20px;
                    -khtml-border-radius:20px;
                    border-radius:20px;
                    margin:5em auto;
                    padding:15px 20px 15px 80px;
                    font-size:14px

                }

                .warning2 {
                    background-color: #0385A7;
                    background-image:url(images/warning.png);
                    min-height:40px;
                    color:#CCCCCC;
                    clear:both;
                    text-align:center;
                    vertical-align:middle;
                    border-collapse:collapse;
                    background-position:20px 50%;
                    background-repeat:no-repeat;
                    -moz-border-radius:20px;
                    -khtml-border-radius:20px;
                    border-radius:20px;
                    margin:5em auto;
                    padding:15px 20px 15px 80px;
                    font-size:14px

                }

                .c5 table tr .redbold blink strong {
                    font-size: 18px;
                }

                .message{
                    width:50em;
                    min-height:40px;
                    background-image:url(images/warning.png);
                    vertical-align:middle;
                    border-collapse:collapse;
                    background-position:20px 50%;
                    background-repeat:no-repeat;
                    -moz-border-radius:20px;
                    -khtml-border-radius:20px;
                    border-radius:20px;
                    margin:15px auto;
                    padding:15px 20px 15px 80px;
                    font-family:Tahoma, Geneva, sans-serif;
                    font-weight:bold;

                }
                ul
                {
                    list-style-type: none;
                    padding: 0;
                    margin: 0;
                }

                li
                {
                    /* background-image:url(images/arrows.png); */
                    background-repeat: no-repeat;
                    background-position: 0 .4em;
                    padding-left: 20px;
                    line-height:25px;
                }
				.productBlocksfirst { 
                    font-size:13px; 
                }

.listBlocksfirst {display: inline-block; } 
.productBlocksfirst div {text-align:center; width:100%; }

.texboxval
{
			font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            width:120px;
           font-weight: normal;
            color: #333333;
            text-decoration: none;
            border: 2px solid #6699CC;
            text-align:left;
            -moz-border-radius: 5px;
            border-radius: 5px 0 0 5px;
            padding:3px;	
}
.texboxval:focus-visible {
    outline: none;
}
/* header-styles */
    /* .nav_logo {
        border-bottom: 1px solid #ddd;
    } */
    .tn-logo{
        width: 80px;
    }
    .logo_heading_tamil{
        color: #0e446d;
        font-weight: bold;
        font-size: 20px;
    }
    .logo_heading_english{
        color: #000;
        font-weight: bold;
        font-size: 20px;
    }
    .dropdown-btn {
        background-color: #fff;
        color: #000;
        padding: 5px;
        cursor: pointer;
        overflow: hidden;
        -webkit-user-select: none;
        -ms-user-select: none;
        user-select: none;
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
        border-radius:30px;
        display: flex;
        align-items: center;
        font-weight: 700;
        /* width: 170px; */
        width: 165px;
        height:53px;
    }
    .dropdown-btn img {
        background: #d9d9d9;
        height: 50px;
        margin-right: 1rem;
        border-radius: 50%;
    }
    .dropdown-btn span{
/* color:#3b5999; */
        color:#1e7875;
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
        /* border-bottom: 1px solid #e7e7e7; */
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
            /* background-color: #063064; */
             /* background-color:#012120; */
             background-color:#033c3a;
                     /* background: #fff; */
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
        /* background: #3b5999; */
        background-color:white;
        /* color:#3b5999; */
        color:#1e7875;
    }
    .dashboard-btn {
        background: #40bff5;
    }
    .rdweb-btn span {
        padding: 0 5px;
        font-weight:bold;
    }
    .rdweb-btn .img-block {
        width: 40px;
        text-align: center;
        margin: 0 5px 0 0;
        /* background: rgba(0, 0, 0, 0.10); */
        /* background-color:white; */
        background-color:#e7e7e7;
        padding: 10px;
        border-radius: 5px;
    }
    /* .rdweb-btn:hover{
        color: #fff;
    } */

/* header-styles-end */

        .collapsing {
            transition: .01s ease !important;
        }
        .collapse {
            border: 1px solid #5a948c;
            border-radius: 5px;
            margin-bottom: 3px;
        }
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
             background-color:#1e7875!important;
            color: #fff;
            /* border-color: #094586; */
            border-color: #1e7875;
            font-size: 16px;
            position: relative;
        }
        .btn-custom.active:active {
            color: #fff;
        }
        .btn-custom:focus{
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
        .fa-square-caret-right,.fa-square-caret-down{
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
        .submenu1 h6 {
            margin-bottom: 10px;
            font-size: 15px;
            /* color: #0e446d; */
            font-weight: 600;
        }
        .submenu1 ol {
            font-weight: 500;
            margin-bottom: 5px;
            padding-left: 0;
        }
        .submenu1 ol li {
            list-style: none;
        }
        .submenu1 ol li a {
            text-decoration: none;
            color: #030303;
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
            background-image: url('../images/forward.svg');
            background-repeat: no-repeat;
            background-size: 18px;
        }
        button[aria-expanded='false']::after {
            transform: rotate(90deg);
        }
        .submenu1 .card-body{
            padding: 10px;
        }
        .submenu1 .card-body:hover{
        /* background:#6699CC; */
        /* background-color:#01abab; */
        background-color:#71b3b3;
        color:#fff !important;
        /* color:white; */
        }
        #shwfrm{
            border: 2px solid #6699CC;
            border-radius: 0 5px 5px 0;
            height: 28px;
            margin-left: -6px;
            background: #6699CC;
            color: #fff;
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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
        <style>
.cards {
    padding: 20px;
    margin: 20px;
    border-radius: 7px;
    /* box-shadow: 0 0 8px #333; */
    box-shadow: 3px 3px 10px rgb(0 0 0 / 40%) inset;
    /* box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px; */
    /* border: 10px solid #EBEBEB; */
    /* background: #fff; */
}

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
                /* box-shadow: 0 -300px 0 1px #2196F3 inset; */
                /* box-shadow: 0 -300px 0 1px #35c7b9 inset; */
                  box-shadow: 0 -300px 0 1px #04bebe inset;
                /* border-color:#2196F3; */
                 border-color:#35c7b9;
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

        
        <script>
    function openDropdown() {
        var dropdownElement = document.getElementById('dropArea');
        if (dropdownElement) {
            dropArea.classList.toggle("activeDropArea");
        } else {
            console.error('Dropdown element not found.');
        }
    }
</script>
<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['financial_year'])) {
    $_SESSION['financial_year'] = $_POST['financial_year'];
    header("Location: " . $_SERVER['PHP_SELF']); // avoid form resubmission
    exit;
}

// ✅ Get financial year from session (if already set)
$finYear = isset($_SESSION['financial_year']) ? $_SESSION['financial_year'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .popup-overlay {
      position: fixed;
      top: 0; left: 0;
      width: 100vw; height: 100vh;
      background: rgba(0,0,0,0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 999;
    }

    .popup-box {
      background: white;
      padding: 20px;
      border-radius: 10px;
      border: 2px solid #007BFF;
      min-width: 300px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.3);
      animation: fadeIn 0.3s ease;
    }

    .popup-box h2 {
      margin-top: 0;
      color: #007BFF;
    }

    select {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
    }

    .button-group {
      display: flex;
      justify-content: space-between;
      gap: 10px;
    }

    .button-group button {
      padding: 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .save-btn {
      background-color: #007BFF;
      color: white;
    }

    .cancel-btn {
      background-color: #aaa;
      color: white;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }

    .reset-link {
      margin-top: 20px;
      display: inline-block;
      color: #007BFF;
      text-decoration: none;
    }

    .reset-link:hover {
      text-decoration: underline;
    }
  </style>




</head>
<body>


 <?php if ($role_code!=1): ?>

  <?php if (!$finYear): ?>
    <!-- Popup only if year is NOT selected -->
    <div class="popup-overlay" id="popup">
      <div class="popup-box">
        <h2>Select Financial Year</h2>
        <form method="post">
          <select name="financial_year" required>
            <option value="">-- Select Year --</option>
            <option value="2023-2024">2023-2024</option>
            <option value="2024-2025">2024-2025</option>
            <option value="2025-2026">2025-2026</option>
          </select>
          <div class="button-group">
            <button  type="submit" class="save-btn center">Save</button>
            <!-- <button type="button" class="cancel-btn" onclick="closePopup()">Cancel</button> -->
          </div>
        </form>
      </div>
    </div>
  <?php endif; ?>
   <?php endif; ?>

  <script>
    function closePopup() {
      document.getElementById('popup').style.display = 'none';
    }
  </script>

</body>
</html>
        <form action="../result.html" id="cse-search-box">
        <div class="navbar navbar-default nav_logo">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div class="d-flex align-items-center headruler">
                        
                    </div>
               
                </div>
            </div>
        </div>
    </form>
       <!-- <div class="dashboard-btns">
        <div class="d-flex justify-content-between align-items-center mx-3">
            <a href="home.php" class="rdweb-btn home-btn">
                <div class="img-block">
                    <img src="<?php echo htmlentities($site_data->website_image_path); ?>home-icon.svg" alt="">
                </div>
                <span>Home</span>
            </a>           
            <div class="d-flex align-items-center">
                <button id="expand-collapse1" class="collapse_all activate">Expand All 1<i class="ms-1 fa-solid fa-square-caret-right" id="arrowFun"></i></button>
            </div>
        </div>
    </div>   -->
<section class="container">
<div class="cards">
    <div class="mt-3">
        <div id="accordion" class="grid-container">
            <?php
             $main_menu = "SELECT * FROM security.m_submenu1 
                      WHERE user_id=:user_id AND dept_id=:dept_id AND rflag=:rflag AND del_flag is null AND isactive=1 
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
                    <button class="btn btn-custom w-100 mb-1 dis_down active" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $key ?>" aria-expanded="true" aria-controls="collapse<?php echo $key ?>">
                        <span style="float: left;"><b> <?php echo $row['smenu_desc'] ?></b></span>
                    </button>
                    <div class="submenu1">
                        <div id="collapse<?php echo $key ?>" class="collapse" aria-labelledby="heading<?php echo $key ?>" data-parent="#accordion">
                           <?php
                          $sub_menu = "SELECT ssmenu_desc as menu_desc, ssmenu_url as menu_url, ssmenu_id as menu_id ,smenu_id as parentmenu_id
                                     FROM security.m_submenu2 
                                     WHERE user_id=:user_id AND dept_id=:dept_id AND smenu_id=:menuid  
                                     AND rflag=:rflag AND responsive_support IN('A', 'W')  and del_flag is null
                                     ORDER BY CASE
                                        WHEN menu_order_no::text LIKE '%.%' THEN  -- if there's a decimal point
                                        CAST(SPLIT_PART(menu_order_no::text, '.', 2) AS NUMERIC)
                                        ELSE
                                        CAST(menu_order_no AS NUMERIC)          -- no decimal, use the number itself
                                    END;";
                                    //

                        $sub_menu_res = $this->prepare($sub_menu, array(
                            ":user_id" => $role_code,
                            ":dept_id" => 1,
                            ":menuid" => $row['smenu_id'],
                            ":rflag" => 1
                        ), 2);
                            foreach ($sub_menu_res as $row1) {
                            ?>
                                <div class="card-body">

                                    <h6 <?php if ($row1['menu_url'] != '') { ?> onclick=" window.location.href=`<?php echo $row1['menu_url']?>` " role="button" <?php } ?> ><?php echo $row1['menu_desc'] ?></h6>
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
                                        $menuscript = "";
                                        foreach ($ssub_menu_res as $row2) {
                                    ?>
                                            <ol>
                                                <li><a href='<?php echo $row2['menu_url'] ?>' title="<?php echo $row2['menu_desc'] ?>"><?php echo $row2['menu_desc'] ?></a>
                                                </li>
                                            </ol>
                                    <?php
                                        }
                                        //
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


