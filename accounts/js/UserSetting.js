$(document).ready(function() {


					// CHANGE PASSWORD

if(sessionStorage.length!=0 && parseInt(sessionStorage.getItem("resent_otp_timer"))>0)
{
	$('#resent_otp').attr('disabled','disabled');
	//console.log(sessionStorage.getItem("resent_otp_timer"));
	otp_timer();
}

$(document).on('click','#resent_otp',function(){
	
	
	
	$('#resent_otp').attr('disabled','disabled');
	
	if($('#currpass').val()=='')
	{
		alert('Enter Current Password');
		$('#resent_otp').removeAttr('disabled');
		return false;	
	}
	
	$.ajax({
		type:'post',	
		url: website_url+'project/forms/Logincheck.php',
		data: {"cmd":btoa(1)},
		success: function (data){
		if(data != '')
		{	
			var Resutl_Data=JSON.parse(data);
			if(Resutl_Data['STATUS']=='authorised')
			{
				alert(Resutl_Data['MESSAGE']);
				window.location=website_url+'project/forms/logout.php';
			}
			else
			{
				if(Resutl_Data['STATUS']=='ERROR')
				{
					alert(Resutl_Data['MESSAGE']);
					$('#resent_otp').removeAttr('disabled');
					return false;
				}
				else if(Resutl_Data['STATUS']=='SUCCESS')
				{
					alert(Resutl_Data['MESSAGE']);
					if(Resutl_Data['resent_btn_flag'])
					{
						$('#resent_otp,#timer').remove();
					}
					otp_timer();
					return false;
				}
			}
		}
		},
		dataType: 'html'
	});
	
});


$(document).on('click','#verify_otp',function(){

	var user_otp=$('#chngpass_otp').val();
	$('#verify_otp').attr('disabled','disabled');
	
	$.ajax({
		type:'post',	
		url: website_url+'project/forms/Logincheck.php',
		data: {"user_otp":btoa(user_otp),"cmd":btoa(2)},
		success: function (data){
		if(data != '')
		{	
			var Resutl_Data=JSON.parse(data);
			if(Resutl_Data['STATUS']=='authorised')
			{
				alert(Resutl_Data['MESSAGE']);
				window.location=website_url+'project/forms/logout.php';
			}
			else
			{
				if(Resutl_Data['STATUS']=='ERROR')
				{
					alert(Resutl_Data['MESSAGE']);
					$('#verify_otp').removeAttr('disabled','disabled');
					return false;
				}
				else if(Resutl_Data['STATUS']=='SUCCESS')
				{
					alert(Resutl_Data['MESSAGE']);
					$('#chngpass_otp').attr('readonly','readonly');
					$('#resent_timer,#verify_otp').remove();
					$('#td_newpassword_field').html(Resutl_Data['DATA']);
					return false;
				}
			}
		}
		},
		dataType: 'html'
	});
	
});


$(document).on('click','#cmdAdd',function(){
										try {
											var strSalt = $("#change_password_salt").val();
											// alert(strSalt);
											/*if ($("#username").val().length === 0) {
												throw {
													msg : "Enter Username",
													foc : "#username"
												}
											}*/
											if ($("#currpass").val().length === 0) {
												throw {
													msg : "Enter current Password",
													foc : "#currpass"
												}
											}

											if ($("#newpass").val().length === 0) {
												throw {
													msg : "Enter New Password",
													foc : "#newpass"
												}
											}
											
											
											if ($("#conpass").val().length === 0) {
												throw {
													msg : "Enter Confirm Password",
													foc : "#conpass"
												}
											}
											
											
											

											if (document.passchange.newpass.value == ""
													|| document.passchange.conpass.value == "") {
												alert("Password cannot be empty");
												return false;
											}
											 if ((!isValidPassword(document
													.getElementById('newpass').value))) {
												alert("Invalid password!! \nPlease read password policy carefully\n\nAny English lowercase and uppercase (a-z and A-Z) characters.\nPassword must contain at least one number (1-9)\nAny special characters from the bracket (! @ # $ ^ * _ ~)");
												document.getElementById(
														'newpass').focus();
												document
														.getElementById('newpass').value = '';
												return false;
											} else if ((!isValidPassword(document
													.getElementById('conpass').value))) {
												alert("Invalid password. Please read password policy carefully.");
												document.getElementById(
														'conpass').focus();
												document
														.getElementById('conpass').value = '';
												return false;
											} 

											//var uname = $("#uname").val();
											//$("#uname").val(btoa(uname));

											var strEncPwdcurr = new String(
													SHA512(strSalt+SHA512($("#currpass")
															.val())));
											$("#currpass").val(strEncPwdcurr);
											var strEncPwdnew = new String(
													encryptPwd_AES(
															$("#newpass").val(),
															strSalt));
											$("#newpass").val(
													btoa(strEncPwdnew));

											var strEncPwdcon = new String(
													encryptPwd_AES(
															$("#conpass").val(),
															strSalt));
											$("#conpass").val(
													btoa(strEncPwdcon));
											$("#change_password_salt").val("");											
											$("#passchange").submit();
											/*
											 * alert(strEncPwdcurr);
											 * alert(strEncPwdnew);
											 * alert(strEncPwdcon);
											 */

											return true;
										} catch (e) {
											console.log(e);
											alert("Error : " + e.msg);
											$(e.foc).focus();
											return false;
										}
									});

				});




function otp_timer()
{
	if(sessionStorage.length==0 || parseInt(sessionStorage.getItem("resent_otp_timer"))==0)
	{
		var counter = 300;
	}
	else
	{
		var counter = sessionStorage.getItem("resent_otp_timer");	
	}
	var interval = setInterval(function() {
	counter--;
	// Display 'counter' wherever you want to display it.
	if (counter <= 0) {
		clearInterval(interval);
		$('#timer').html('').hide();  
		//console.log("Timer --> " + counter);
		sessionStorage.setItem('resent_otp_timer',counter);
		console.log(sessionStorage.getItem("resent_otp_timer"));
		$('#resent_otp').removeAttr('disabled');
		return;
	}else{
		sessionStorage.setItem('resent_otp_timer',counter);	
		$('#timer').show();
		$('#time').text(counter);
		//console.log("Timer --> " + counter);
	}
	}, 1000);
}



function encryptPwd(strPwd, strSalt) {
	var strNewSalt = new String(strSalt);
	if (strPwd == "" || strSalt == "") {
		return null;
	}
	var strEncPwd;
	var strPwdHash = SHA512(strPwd);
	var strMerged = strNewSalt + strPwdHash;
	var strMerged1 = SHA512(strMerged);
	return strMerged1;
}

function encryptPwd_AES(strPwd, strSalt) {
	var strNewSalt = new String(strSalt);
	if (strPwd == "" || strSalt == "") {
		return null;
	}
	var strEncPwd;
	var strPwdHash = SHA512(strPwd);
	// var strMerged = strNewSalt+strPwdHash;
	var strMerged = strPwdHash;
	var strMerged1 = CryptoJS.AES.encrypt(strMerged, strSalt, {
		format : CryptoJSAesJson
	}).toString();
	return strMerged1;
}

function disableCtrlKeyCombination(e) {
	var forbiddenKeys = new Array('a', 'n', 'c', 'x', 'v', 'j', 'u');
	var key;
	var isCtrl;
	if (window.event) {
		key = window.event.keyCode;
		if (window.event.ctrlKey)
			isCtrl = true;
		else
			isCtrl = false;
	} else {
		key = e.which;
		if (e.ctrlKey)
			isCtrl = true;
		else
			isCtrl = false;
	}
	if (isCtrl) {
		for (i = 0; i < forbiddenKeys.length; i++) {
			if (forbiddenKeys[i].toLowerCase() == String.fromCharCode(key)
					.toLowerCase()) {
				return false;
			}
		}
	}
	return true;
}

function noenter() {
	return !(window.event && window.event.keyCode == 18);
}
function isValidPassword(sVal) {
	if (isEmpty(sVal))
		return false;
	var sPattern = /(?=.*[a-z,A-Z])(?=.*[0-9])(?=.*[!,@,#,$,^,*,_,~])/;
	if (!sPattern.test(sVal)) {
		return false;
	}
	return true;
}
function isEmpty(s) {
	return (s == "" || s.length == 0);
}

function load() {
	document.passchange.username.focus();
}
