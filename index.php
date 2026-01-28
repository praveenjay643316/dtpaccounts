<?php
//require_once  'project/config/configPublic.php';

class ContactUs 
{
   
    public function __construct()
    {
       
    }

    public function main_content()
    {
        
              
        ob_start();
		?>
<input type="hidden" id="page_lable_id" name="page_lable_id" value="191" />
<?php
        ?>
<<<<<<< HEAD
<header>
<!-- <meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="format-detection" content="telephone=no" />
<meta name="description" content="html template">
<meta name="author" content="uxdt">
<link rel="stylesheet" href="works/css/bootstrap.css">
<link rel="stylesheet" href="works/css/bootstrap.min.css">
<link rel="stylesheet" href="works/css/bootstrap-theme.css">
<link rel="stylesheet" href="works/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="works/css/customstyle.css"> -->
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
<script>
function redirectToAccounts(name) {
    if(name=='accounts'){
        window.location.href = "https://egov.dtp.tn.gov.in/accounts/";
    }else{
        window.location.href = "https://egov.dtp.tn.gov.in/works/";
    }
    
}
</script>
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
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
=======


<section class="sectionparas">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card01">
                   <button class="cardsbutton"><h4>Accounts</h4></button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card01">
                <button class="cardsbutton"><h4>Works</h4></button>
                </div>
            </div>
        </div>
    </div>
</section>




<section class="sectionpara">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="cards">
                    <img class="paralogos"
                        src="
                     <?php 
                                            echo htmlentities($site_data->website_image_path); ?>assets/Egovernments.png">
                    <br><br>
                    <p class="townpara">This is an intranic application for directorate of Town Panchyats. Consisting of
                        Work monitoring,Town Panchayat Statistics,Accounts & Grivences.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="cards">
                    <img class="paraimage" src="
                                            <?php 
                                            echo htmlentities($site_data->website_image_path); ?>assets/Egov.jpg">
                </div>
            </div>
        </div>
    </div>
</section>

<style>
body {
    font-family: 'Open Sans', sans-serif;
}

.sectionpara {
    margin: 20px;
}
.sectionparas{
    margin-left:212px;
    margin-top:20px;
}
.cards {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
    box-sizing: border-box;
    padding: 20px;
    height: 392px;
    border: 10px solid #EBEBEB;
}
.card01 {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
    box-sizing: border-box;
    padding: 20px;
    height: 102px;
    width:50%;
    /* border: 10px solid #EBEBEB; */
}
.cardsbutton{
    margin-left:50px;
    margin-top:10px;
    background-color:#3c3b6e;
    /* background-color: #2C2B5E; */
    font-size:16px;
    padding: 4px 17px;
    text-align: center;
    color: white;
    box-shadow: 0 0 20px #eee;
    border-radius: 5px;
    border: none;
}
.btn-grad {
    /* background-color: #2D9DA7; */
    /* background-color: #004080; */
    background-color: #2C2B5E;
    margin: 10px;
    /* padding: 6px 21px; */
    padding: 4px 17px;
    text-align: center;
    color: white;
    box-shadow: 0 0 20px #eee;
    border-radius: 5px;
    border: none;
}

.townpara {
    font-size: 18px;
    font-family: sans-serif
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645
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

<<<<<<< HEAD
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
		width: 387px;
    }
=======
.paraimage {
    /* height: 350px; */
    /* width: 494px; */
    height: 333px;
    width: 569px;
}

.paralogos {
    width: 130px;
    margin-left: 20px;
}

/* .about-area {
    margin-top: 30px
}

.about-img {
    position: relative;
    z-index: 1;
    margin-right: -30px;
    padding: 0 20px;
}



.about-contents {
    border: 10px solid #EBEBEB;
    margin: 10px;
    height: 98%;
}

.about-img {
    position: absolute;
    border: 10px solid #EBEBEB;
    left: 0;
    bottom: -5px;
    width: 100%;
    content: "";
    height: 98%;
    z-index: -1;
}

.paraimage {
    height: 350px;
    width: 494px;
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645
}


img {
    max-width: 100%;
    -webkit-transition: all 0.3s ease-out 0s;
    transition: all 0.3s ease-out 0s;
    height: auto;
} */
</style>
</header>

	<!-- loder -->
	<div class="loader-wrapper">
		<div class="loader"></div>
		<div class="loder-section left-section"></div>
		<div class="loder-section right-section"></div>
	</div>







	<!--==================================================-->
	<!-- Start  header-top-menu  -->
	<!--==================================================-->

	<div class="header-top-section">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<div class="header-too-menu-address">
						<ul>
							<li><span>Government of TamilNadu</span></li>
							<!-- <li><i class="bi bi-clock"></i> <span>Hours: Mon-Fri: 8am – 7pm</span></li> -->
						</ul>
					</div>
				</div>
				<!-- <div class="col-lg-5">
					<div class="header-top-info">
						<ul>
							<li>Council</li>
							<li>Government</li>
							<li>Complaints</li>
						</ul>
					</div>
				</div> -->


				
				<div class="col-lg-6">
					<div class="header-top-menu-social-icon">
						<ul>
  <button type="button" class="btn btn-sm" style="background: transparent;border: 1px solid white;color:white;">
                                Skip
                                To Main
                                Content
                            </button>

                            <button type="button" class="btn btn-sm btn-outline-secondary decrease-plugin-ac"  style="background: transparent;border: 1px solid white;color:white;">A-</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary normal-plugin-ac"    style="background: transparent;border: 1px solid white;color:white;">A</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary increase-plugin-ac"  style="background: transparent;border: 1px solid white;color:white;">A+</button>











							<!-- <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
							<li><a href="#"><i class="fab fa-twitter"></i></a></li>
							<li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
							<li><a href="#"><i class="fab fa-pinterest-p"></i></a></li> -->
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="sticky-header" class="_nav_manu py-2">
  <div class="container-fluid">
    <div class="row align-items-center justify-content-center text-center flex-wrap">

      <!-- Logo + Text -->
      <div class="col-12 col-md-6 col-lg-7 d-flex justify-content-center align-items-center flex-wrap text-center">
        <a class="navbar-brand d-flex align-items-center" href="#">
          <img src="assets/images/TamilNadu_Logo.png" alt="Logo" class="ipablogo me-2" style="height:60px; width:60px;">
          <div class="nav_heading text-start">
            <h6 class="mb-0" style="color:#0e446d; font-weight:bold;">பேரூராட்சிகள் இயக்ககம், தமிழ்நாடு</h6>
            <small><b>Directorate of Town Panchayats, Tamil Nadu</b></small>
          </div>
        </a>
      </div>

      <!-- eGov Image -->
      <div class="col-6 col-md-3 col-lg-3 d-flex justify-content-center justify-content-md-end mt-3 mt-md-0">
        <img src="assets/images/egov04.png" class="ipablogo" style="height:60px; width:60px;" alt="eGov">
      </div>

      <!-- Digital India Image -->
      <div class="col-6 col-md-3 col-lg-2 d-flex justify-content-center justify-content-md-end mt-3 mt-md-0">
        <img src="assets/images/digital-india-c2.png" class="ipablogo" style="height:60px; width:60px;" alt="Digital India">
      </div>

    </div>
  </div>
</div>


<!-- 
	<div id="sticky-header" class="_nav_manu">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-7">
					<div class="logo">
						<a class="navbar-brand d-flex flex-column flex-sm-row  align-items-center mr-0" href="#">
                        <img src="assets/images/TamilNadu_Logo.png " class=" ipablogo" style="height:80px;width:80px;margin-left:42px" title="Directorate of Town Panchayats, Tamil Nadu">
                        <div class="nav_heading" style="margin-left:8px">
                            <h5 style="color:#0e446d;font-weight:bold;">பேரூராட்சிகள்
                                இயக்ககம், தமிழ்நாடு</h5>
                            <p><b> Directorate of Town Panchayats,
                                    Tamil Nadu</b></p>
                        </div>
                        </a>
						
					</div>
				</div>
				<div class="col-lg-3">
					<img src="assets/images/egov04.png" class=" ipablogo" style="height:100px;width:100px;" title="Directorate of Town Panchayats, Tamil Nadu">
				</div>
				<div class="col-lg-2 ms-auto">
					<img src="assets/images/digital-india-c2.png" class=" ipablogo" style="height:100px;width:100px;margin-left: 15px;" title="Directorate of Town Panchayats, Tamil Nadu">
				</div>
				
			</div>
		</div>
	</div> -->
   <?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

   
    
	
	<div class="slider-list owl-carousel">
		<div class="slider-section d-flex align-items-center">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-lg-12">
						<div class="slider-content">
							<h2>E-Gov Intranic Application Services</h2>
						
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="slider-section style-two d-flex align-items-center">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-lg-12">
						<div class="slider-content">
							<h2>E-Gov Intranic Application Services</h2>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	

	<!--==================================================-->
	<!-- End  slier Section  -->
	<!--==================================================-->

		
<!--==================================================-->
	<!-- Start  feature Section  -->
	<!--==================================================-->

	<div class="feature-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<div class="section-title center-title" >
						<!-- <h1>Explore Our Online E-Government</h1> -->
						<h1>Our Services</h1>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6">
					<div class="single-feature-box" data-cues="fadeIn">
						<a onclick="redirectToAccounts('works')">
						<div class="feature-icon">
							<img src="assets/images/resource/icon01.png" alt="icon">
						</div>
						<div class="feature-content">
							<h4>Works</h4>
							<p>E-Governance works involve the digital transformation of government services to enhance efficiency, transparency, and citizen engagement.
</p>
						</div>
						</a> 
					</div>
				</div>
				<div class="col-lg-6 col-md-6">
					<div class="single-feature-box" data-cues="fadeIn">
						 <a  onclick="redirectToAccounts('accounts')">
						<div class="feature-icon">
							<img src="assets/images/resource/icon8.png" alt="icon">
						</div>
						<div class="feature-content">
							<h4>Accounts</h4>
							<p>E-Governance manages digital financial transactions and bookkeeping for government departments efficiently</p>
						</div>
						</a> 
					</div>
				</div>
				
			</div>
		</div>
	</div>

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
          <img src="assets/images/resource/Egovabt.jpg" alt="eGov Image" class="img-fluid custom-image">
          <div class="text mt-2">
            <h4>E-GOVERNMENT</h4>
          </div>
          <div class="about-shape2">
            <img src="assets/images/resource/about-shape1.jpg" alt="shape" class="img-fluid">
          </div>
        </div>
      </div>

    </div>
  </div>
</div>





<!-- <div class="about-section2">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					
					<div class="section-title">
						 <div class="about-content2">
						<div class="img2">
							<h4>eGOV</h4>
						</div>
						<p class="section-dsc-1">This is an intranic application for directorate of Town Panchyats. Consisting of Work monitoring,Town Panchayat Statistics,Accounts & Grivences.
						</p>
					</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="about-thumb2" data-cue="zoomIn">
						<img src="assets/images/resource/Egovabt.jpg" alt="" style="width: 600px;margin-left: 50px;">
						<div class="text"><h4>E-GOVERNMENT</h4></div>
						<div class="about-shape2">
							<img src="assets/images/resource/about-shape1.jpg" alt="shape">
						</div>
					</div>
				</div>

 


			</div>
		</div>
	</div> -->



 


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
    </body>

















    
 

<script>
function redirectToAccounts(name) {
    if(name=='accounts'){
        window.location.href = "https://egov.dtp.tn.gov.in/accounts/";
    }else{
        window.location.href = "https://egov.dtp.tn.gov.in/works/"; 
    }
    
}
</script>

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






<?php
		

        // #############

        // PAGE CONTENT END

        // #############

       
		
        //$this->Template("PublicTemplate", "Index", $ob_output_main_contents,array(),array('page_id'=>12));
    }
}

$Home = new ContactUs();
$Home->main_content();

?>