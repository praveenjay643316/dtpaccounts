<?php
class NodbCommonFunctions extends ConfigClass
{
	
	//**********************************************DATABASE CONNECTION***********************************************
	function __construct() {
		
	}
	public function getNewCaptcha($captchaSessionName="") {		
		ob_start();			
		if(!isset($_SESSION))
		{
			session_start();
		}				
		if(isset($_SESSION[$captchaSessionName]))
		{
			unset($_SESSION[$captchaSessionName]); // destroy the session if already there
			unset($_SESSION['voice_captcha']); // destroy the session if already there
		}
		//////Part 1 Random string generation ////////
		$string1="abcdefghijklmnopqrstuvwxyz";	
		$string=$string1;
		$string= str_shuffle($string);
		$random_text= substr($string,0,6); // change the number to change number of chars
		/////End of Part 1 ///////////		
		 $_SESSION[$captchaSessionName] =$random_text; // Assign the random text to session variable
		 $_SESSION['voice_captcha'] =$random_text; // Assign the random text to session variable		
		///// Create the image ////////
		$im = @ImageCreate (120, 20)
		or die ("Cannot Initialize new GD image stream");
		$background_color = ImageColorAllocate ($im, 255,255,255); // Assign background color
		$text_color = ImageColorAllocate ($im, 0, 0, 0);      // text color is given 
		ImageString($im,5,5,3,$_SESSION[$captchaSessionName],$text_color); // Random string  from session added 		
		ImagePng ($im); // image displayed		
		$image=ob_get_contents();	
		ob_end_clean();	
		imagedestroy($im); // Memory allocation for the image is removed. 
		return  'data:image/png;base64,' . base64_encode($image);
	}		
	public function randomPrefix($length) {
		$random = "";
		srand((double) microtime() * 1000000);
		$data = "AbcDE123IJKLMN67QRSTUVWXYZ";
		$data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
		$data .= "0FGH45OP89";
		for ($i = 0; $i < $length; $i++) {
			$random .= substr($data, (rand() % (strlen($data))), 1);
		}
		return $random;
	}
	public function token($tokenName="") {		
		if(!isset($_SESSION))	
		session_start();
		$pagetoken = $this->randomPrefix(20);
		$_SESSION[$tokenName] = $pagetoken;		
		return $pagetoken;		
	}
	function __destruct() {
   }
}

?>