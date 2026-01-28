<?php 

//phpinfo();

require_once  'project/config/configPublic.php';
class ContactUs extends ConfigClass
{
    public function __construct()
    {
       
    }

    public function main_content()
    {
       // $NodbCommonFunctions = new NodbCommonFunctions();
       // $captcha = new captcha();
        $site_data = $this->siteData();
        if(isset($_POST['password']))
        {
          header('HTTP/1.1 400 Bad Request');
          exit;
        }        
        ob_start();
		?>
<input type="hidden" id="page_lable_id" name="page_lable_id" value="191" />
<?php

        // #############

        // PAGE CONTENT START

        // #############
		
		$lang_code_2d=$this->getCurrentUserLanguage2D();
        ?>

<meta charset="UTF-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>E-Gov Websites</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Favicon -->
	<link rel="icon" type="image/png" sizes="56x56" href="assets/images/fav-icon/icon.png">
	<!-- bootstrap CSS -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css" media="all">
	<!-- carousel CSS -->
	<link rel="stylesheet" href="assets/css/owl.carousel.min.css" type="text/css" media="all">
	<!-- animate CSS -->
	<link rel="stylesheet" href="assets/css/animate.css" type="text/css" media="all">
	<!-- animated-text CSS -->
	<link rel="stylesheet" href="assets/css/animated-text.css" type="text/css" media="all">
	<!-- font-awesome CSS -->
	<link rel="stylesheet" href="assets/css/all.min.css" type="text/css" media="all">
	<!-- font-flaticon CSS -->
	<link rel="stylesheet" href="assets/css/flaticon.css" type="text/css" media="all">
	<!-- theme-default CSS -->
	<link rel="stylesheet" href="assets/css/theme-default.css" type="text/css" media="all">
	<!-- meanmenu CSS -->
	<link rel="stylesheet" href="assets/css/meanmenu.min.css" type="text/css" media="all">
	<!-- transitions CSS -->
	<link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css" media="all">
	<!-- venobox CSS -->
	<link rel="stylesheet" href="venobox/venobox.css" type="text/css" media="all">
	<!-- bootstrap icons -->
	<link rel="stylesheet" href="assets/css/bootstrap-icons.css" type="text/css" media="all">
	<!-- Main Style CSS -->
	<link rel="stylesheet" href="assets/css/scrollCue.css" type="text/css" media="all">  
	<!-- Main Style CSS -->
	<link rel="stylesheet" href="assets/css/style.css" type="text/css" media="all">  
	<!-- responsive CSS -->
	<link rel="stylesheet" href="assets/css/responsive.css" type="text/css" media="all">
	<!-- modernizr js -->
	<script src="assets/js/vendor/modernizr-3.5.0.min.js"></script>
	
	<link href="https://fonts.cdnfonts.com/css/clash-display" rel="stylesheet">
                
  <style>
	@media only screen and (min-width: 300px) and (max-width: 767px) {
		/* .nav_heading h5{
    font-size: 16px;
    text-align: center;
}
.nav_heading p{
    font-size: 14px;
    text-align: center;
} */
.about-content2{
	margin-left:0;
}
.slider-content h2 {
    font-size:50px;
    /* line-height: 90px; */
    color: #ffffff;
    font-weight: 700;
    font-family: "Poppins";
    text-align: center;
    margin: 0;
}
	}

@media (max-width: 767.98px) {
    .ipablogo {
      height: 45px !important;
      width: 45px !important;
    }

    .nav_heading h6 {
      font-size: 14px;
      margin-bottom: 2px;
    }

    .nav_heading small {
      font-size: 12px;
    }

    .navbar-brand {
      flex-direction: row;
      flex-wrap: wrap;
      text-align: left;
    }
  }

  .custom-image {
    width: 100%;
    max-width: 600px;
  }

  /* Remove left margin on smaller screens */
  @media (max-width: 991.98px) { /* below lg breakpoint */
    .custom-image {
      margin-left: 0 !important;
    }
  }

  /* Adjust font sizes for smaller screens */
  @media (max-width: 767.98px) {
    .section-dsc-1 {
      font-size: 14px;
    }

    .about-thumb2 .text h4 {
      font-size: 16px;
    }
  }
  @media only screen and (min-width: 300px) and (max-width: 768px) {
    .about-thumb2:before {
    /* width: 335px; */
		width:364px;
		left: 0px;
    }
}
</style> 

<!-- loder -->
	<div class="loader-wrapper">
		<div class="loader"></div>
		<div class="loder-section left-section"></div>
		<div class="loder-section right-section"></div>
	</div>







<div class="breadcumb-section d-flex align-items-center">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-12">
					<div class="breadcumb-container text-center">
						<!-- <ul>
							<li><a href="index.html">Home</a></li>
							<li><i class="bi bi-chevron-right"></i></li>
							<li><span>services</span></li>
						</ul> -->
						<div class="breadcumb-title">
							<h1>Accounts</h1>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--==================================================-->
	<!-- End breadcumb Section  -->
	<!--==================================================-->


    

<div class="about-section2">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-12 col-lg-6 mb-4 mb-lg-0">
        <div class="section-title">
          <div class="about-content2">
            <div class="img2">
              <h4>eGOV</h4>
            </div>
            <p class="section-dsc-1">
              This is an intranic application for directorate of Town Panchyats. Consisting of Work monitoring,
              Town Panchayat Statistics, Accounts & Grievances.
            </p>
          </div>
        </div>
      </div>

     
      <div class="col-12 col-lg-6">
        <div class="about-thumb2" data-cue="zoomIn" style="text-align:center;">
         	<img class="img-fluid custom-image" src="
                                            <?php 
                                            echo htmlentities($site_data->website_image_path); ?>assets/E-gov-Inner.jpg">
          <div class="text mt-2">
            <h4>E-GOV (ACCOUNTS)</h4>
          </div>
          <div class="about-shape2">
            <img src="assets/images/resource/about-shape1.jpg" alt="shape" class="img-fluid">
          </div>
        </div>
      </div>

    </div>
  </div>
</div>







<script src="assets/js/vendor/jquery-3.6.2.min.js"></script>

<script src="assets/js/bootstrap.min.js"></script>

<script src="assets/js/owl.carousel.min.js"></script>

<script src="assets/js/jquery.counterup.min.js"></script>

<script src="assets/js/waypoints.min.js"></script>

<script src="assets/js/wow.js"></script>

<script src="assets/js/imagesloaded.pkgd.min.js"></script>

<script src="venobox/venobox.js"></script>

<script src="assets/js/animated-text.js"></script>

<script src="venobox/venobox.min.js"></script>

<script src="assets/js/isotope.pkgd.min.js"></script>

<script src="assets/js/jquery.meanmenu.js"></script>

<script src="assets/js/jquery.scrollUp.js"></script>

<script src="assets/js/jquery.barfiller.js"></script>

<script src="assets/js/theme.js"></script>

<script src="assets/js/scrollCue.min.js"></script>







<?php
		

        // #############

        // PAGE CONTENT END

        // #############

        $ob_output_main_contents = ob_get_contents();
        ob_clean();
		
        $this->Template("PublicTemplate", "Index", $ob_output_main_contents,array(),array('page_id'=>12));
    }
}

$Home = new ContactUs();
$Home->main_content();

?>