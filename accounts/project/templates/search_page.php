<?php
require_once  '../config/configPublic.php';



class Dashboard  extends ConfigClass
{	

	
	public function __construct()
	{
		
	}
	
	public function main()
	{
		?>

<div class="container">

  <div class="card mt-5 border  border-info mb-3" >
 
    <div class="card-header bg-info   text-left text-white font-weight-bold "> Search  </div>
    <div class="card-body  pt-5 text-center btn-container">
   
    <div class="col-lg-12">
    <script async src="https://cse.google.com/cse.js?cx=a59520a91edc549a5">
</script>
<div class="gcse-search"></div>

    </div>
        
  </div>
</div>
<?php
$ob_output_main_contents = ob_get_contents();
ob_clean();
$this->Template($this->getCurrentUserTemplate()!=""?$this->getCurrentUserTemplate():"PublicTemplate", "search page", $ob_output_main_contents,array());
	}	
}


$Dashboard=new Dashboard();
$Dashboard->main();


?>