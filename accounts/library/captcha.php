<?php
include 'nodbCommonFunctions.php';

class captcha extends NodbCommonFunctions
{
	//**********************************************DATABASE CONNECTION***********************************************	
	public $NodbCommonFunctions;
	function __construct() {
		$this->NodbCommonFunctions = new NodbCommonFunctions();
	}
		
	public function generateNewCaptcha($captchaSessionName='')
	{
		return $this->NodbCommonFunctions->getNewCaptcha($captchaSessionName);		
	}
	function __destruct() {
   }
}

?>