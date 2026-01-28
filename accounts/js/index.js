

$(document).ready(function(){

    
$("#password_temp").on("focus", function(){
  $('#password_temp').attr("type","password");
  });
  $('#password_temp').css("visibility","");
    $(document).on('click','#submit',function(){
		try{
			var strSalt= $("#salt").val();
			if($("#user_name_temp").val().length === 0){
				throw{msg:"Enter Username",foc:"#user_name_temp"}
			}
			if($("#password_temp").val().length === 0){
				throw{msg:"Enter Password",foc:"#password_temp"}
			}
			 if($("#captchaval_temp").val().length === 0){
				throw{msg:"Enter Captcha",foc:"#captchaval_temp"}
			}
			
			var uname=$("#user_name_temp").val();
			$("#user_name").val(btoa(uname)); 
            $("#captchaval").val($("#captchaval_temp").val()); 		
            
			
			var strEncPwd = new String(encryptPwd($("#password_temp").val(), strSalt));
			
			document.getElementById("login_form").setAttribute('action',website_url+"project/forms/Logincheck.php");
			
            $("#encpwd").val(strEncPwd);          
			$("#salt").remove();  
            $("#user_name_temp").remove();         
            $("#password_temp").remove();
            $("#captchaval_temp").remove();
            document.getElementById("login_form").submit();
	
			return false;
		}catch(e){
			alert(e.msg);
			//alert("Error");
			$(e.foc).focus();
			return false;
		}
	});  
    $('#stop-interval').on('click', function() {
        clearInterval(interval);
    });	


var serviceBox=temp_serviceBox=13;
var marqueeTxt=temp_marqueeTxt=15;
var menuBoxTxt=temp_menuBoxTxt=13;

$("#btn_ZoomIn").click(function(){ 

	if(9<=serviceBox && 19>serviceBox){
		$(".serviceBox").css('font-size',(serviceBox+=2)+'px');
	}
	
	if(9<=marqueeTxt && 19>marqueeTxt){
		$(".marqueeTxt").css('font-size',(marqueeTxt+=2)+'px');
	}
	
	if(9<=menuBoxTxt && 15>menuBoxTxt){
		$(".menuBoxTxt").css('font-size',(menuBoxTxt+=2)+'px');
	}
});

$("#btn_ZoomReset").click(function(){
	serviceBox=temp_serviceBox;
	$(".serviceBox").css('font-size',(temp_serviceBox)+'px');
	marqueeTxt=temp_marqueeTxt;
	$(".marqueeTxt").css('font-size',(temp_marqueeTxt)+'px');
	menuBoxTxt=temp_menuBoxTxt;
	$(".menuBoxTxt").css('font-size',(temp_menuBoxTxt)+'px');
});

$("#btn_ZoomOut").click(function(){
	
	if(10<=serviceBox && 19>=serviceBox){
	$(".serviceBox").css('font-size',(serviceBox-=2)+'px');
	}
	
	if(10<=marqueeTxt && 19>=marqueeTxt){
	$(".marqueeTxt").css('font-size',(marqueeTxt-=2)+'px');
	}
	
	if(10<=menuBoxTxt && 15>=menuBoxTxt){
		$(".menuBoxTxt").css('font-size',(menuBoxTxt-=2)+'px');
	}
}); 

$(".changeBlue").click(function(){
	$(".homePageBG").css('background','linear-gradient(180deg, #012042 0%, #1a79a9 25%, #ffffff 52%, #ffffff 100%)');
	$(".topBarMenu").css('background','#041e3a');
});

$(".changeRed").click(function(){
	$(".homePageBG").css('background','linear-gradient(180deg, #910101 0%, #efb5bf 25%, #ffffff 52%, #ffffff 100%)');
	$(".topBarMenu").css('background','#910101');
});

$(".changeGreen").click(function(){
	$(".homePageBG").css('background','linear-gradient(180deg, #11998e 0%, #21ea6d 25%, #ffffff 52%, #ffffff 100%)');
	$(".topBarMenu").css('background','#1a5901');
});

	
});
	
    function encryptPwd(strPwd,strSalt)
    {
		
        var strNewSalt=new String(strSalt);
        if (strPwd=="" || strSalt=="")
        {
            return null;
        }
        var strEncPwd;
        var strPwdHash = SHA512(strPwd);
		
        var strMerged = strNewSalt+strPwdHash;
        var strMerged1 = SHA512(strMerged);
		
        return strMerged1;		
    }	
    
    function disableCtrlKeyCombination(e){
        var forbiddenKeys = new Array('a','n','c','x','v','j','u');
        var key;
        var isCtrl;
        if(window.event){
            key = window.event.keyCode;    
            if(window.event.ctrlKey)
                isCtrl = true;
            else
                isCtrl = false;
        }
        else{
            key = e.which;     
            if(e.ctrlKey)
                isCtrl = true;
            else
                isCtrl = false;
        }
        if(isCtrl){
            for(i=0; i<forbiddenKeys.length; i++){
                if(forbiddenKeys[i].toLowerCase() == String.fromCharCode(key).toLowerCase()) {
                    return false;
                }
            }
        }
        return true;	
    }

    function onKeyPress(e,obj) { 
        var keycode,strSalt;
		strSalt = $("#salt").val();
        if (window.event) keycode = window.event.keyCode;
        else if (e) keycode = e.keyCode;
        else return true;
        if (keycode == 13) {  //enter key pressed
            $("#submit").trigger("click");
            return false;
        }   
    }
