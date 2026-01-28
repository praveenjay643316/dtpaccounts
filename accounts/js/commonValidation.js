/*---------------- common validation ----------------*/

$(document).ready(function(){

/*---------------- Name field with space----------------*/
$(document).on('keyup blur','.name_eng_with_space',function(){
		$(this).val( $(this).val().replace(/[^.A-Za-z ]/g,'') ); 
});

/*---------------- Name field without space----------------*/
$(document).on('keyup blur','.name_eng_without_space',function(){
		$(this).val( $(this).val().replace(/[^.A-Za-z]/g,'') ); 
});

<<<<<<< HEAD:accounts/js/commonValidation.js
/*-----------------relative url validation----------------------- */
$(document).on('keyup blur', '.relative-url', function () {
    $(this).val(
        $(this).val().replace(/[^A-Za-z0-9\/._~\-?&=%#]/g, '')
    );
});

/*^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$
 */

=======
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:js/commonValidation.js
/*---------------- Alpha numeric without space----------------*/
$(document).on('keyup blur','.alpha_numeric_without_space',function(){
		$(this).val( $(this).val().replace(/[^.A-Za-z0-9/\-\/]/g,'') ); 
});

/*---------------- Alpha numeric with space----------------*/
$(document).on('keyup blur','.alpha_numeric_with_space',function(){
		$(this).val( $(this).val().replace(/[^.A-Za-z0-9 ]/g,'') ); 
});

<<<<<<< HEAD:accounts/js/commonValidation.js

$(document).on('keyup blur', '.alpha_numeric_with_space_hiphen_brackets_dot', function () {
    $(this).val(
        $(this).val().replace(/[^A-Za-z0-9 .\/\-\(\)]/g, '')
    );
});



=======
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:js/commonValidation.js
/*---------------- Number field ----------------*/
$(document).on('keyup blur','.number_field',function(){
	$(this).val( $(this).val().replace(/[^0-9]/g,'') ); 
});

/*---------------- Number Slash field ----------------*/
$(document).on('keyup blur','.number_slash_field',function(){
	$(this).val( $(this).val().replace(/[^0-9/]/g,'') ); 
});
$(document).on('keyup blur','.order_number',function(){
	$(this).val( $(this).val().replace(/[^a-zA-Z0-9-\/.]/g,'') ); 
});

/*---------------- float field----------------*/
$(document).on('keyup blur','.float_field',function(){
	$(this).val( $(this).val().replace(/[^0-9.]/g,'') );
	if($(this).val().indexOf(".")!=-1 && $(this).val().indexOf(".",$(this).val().indexOf(".")+1)!=-1)
	{			
		$(this).val('');
		alert("Invalid Input");
	}
});

/*---------------- address field ----------------*/
$(document).on('keyup blur','.address_field',function(){
<<<<<<< HEAD:accounts/js/commonValidation.js
	$(this).val( $(this).val().replace(/[^A-Za-z0-9 .,\/\-]/g
,'') ); 
=======
	$(this).val( $(this).val().replace(/[^.A-Za-z ,0-9/-]/g,'') ); 
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:js/commonValidation.js
});	

/*---------------- Mobile number ----------------*/
$(document).on('blur','.mobile_number',function(){
  
			  var Current_field_id=$(this).attr('id');
			  var mobile_number=$('#'+Current_field_id).val().length;
			  var str = $('#'+Current_field_id).val();
			  st_pos=str.charAt(0);
			  
	if(str!= '' || !isNaN(str)){	
		 
		 if(st_pos != 1 && st_pos != 2 && st_pos != 3 && st_pos != 4 && st_pos != 5){ 
			if(mobile_number!=10)		  
		   {   
			   alert("Enter valid Mobile No."); 
			   $('#'+Current_field_id).val('');	   
			  return false; 
		   }else{
				  return true;
		   }
		 } else { 
			alert("Enter valid Mobile No.");
			$('#'+Current_field_id).val('');	   
			  return false; 
		 }
	} else{
		alert("Enter valid Mobile No.");
		$('#'+Current_field_id).val('');	   
		return false; 
	}

});

/*---------------- Name field with space----------------*/
$(document).on('keyup blur','.alpha_numeric_char',function(){
		$(this).val( $(this).val().replace(/[^.A-Za-z0-9&-\/,.+')(@ ]/g,'') ); 
});



/*---------------- Bank Ac number ----------------*/
<<<<<<< HEAD:accounts/js/commonValidation.js

/*
=======
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:js/commonValidation.js
$(".bank_ac_number").blur(function(){ 

	var bank_ac_number=$(".bank_ac_number").val().length;
	var str = $('.bank_ac_number').val();
	if(str!= '' || !isNaN(str)){			  		  
		if(bank_ac_number < 5)		  
	   {   
		   alert("Enter valid Bank Ac No."); 
		   $('.bank_ac_number').val('');	   
		  return false; 
	   }
	} else {
	   alert("Enter valid Bank Ac No."); 
	   $('.bank_ac_number').val('');	   
	  return false;	
	}

	});
<<<<<<< HEAD:accounts/js/commonValidation.js
*/


$(".bank_ac_number").blur(function () {

    var str = $('.bank_ac_number').val();

    // Regex for 9 and 18 digits
    var regex = /^\d{6}$/;

    if (!regex.test(str)) {
        alert("Enter valid Bank Ac No.");
        $('.bank_ac_number').val('');
        return false;
    }
});


=======
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:js/commonValidation.js

/*---------------- Date of birth ----------------*/
	$(".date_of_birth").blur(function(){
        
		var dateformat = /^(0?[1-9]|[12][0-9]|3[01])[-](0?[1-9]|1[012])[-]\d{4}$/;
	    var str=$(this).val();
		
		if(str.match(dateformat)){
			var arr = str.split("-");
			var yearThen = parseInt(arr[2], 10);
			var monthThen = parseInt(arr[1], 10);
			var dayThen = parseInt(arr[0], 10);
			
			var today = new Date(2019,0,01);
			var birthday = new Date(yearThen, monthThen-1, dayThen);
			
			var differenceInMilisecond = today.valueOf() - birthday.valueOf();
			
			var year_age = Math.floor(differenceInMilisecond / 31536000000);
			var day_age = Math.floor((differenceInMilisecond % 31536000000) / 86400000);
			
			if ((today.getMonth() == birthday.getMonth()) && (today.getDate() == birthday.getDate())) {
			  //  alert("Happy B'day!!!");
			}
			
			var month_age = Math.floor(day_age/30);
			
			day_age = day_age % 30;
			
				if(year_age<18 || year_age>=150)		  
				{  
					 $(".date_of_birth").val(''); $(".date_of_month").val(''); $(".date_of_date").val(''); 
					alert("invalid date of birth");
					return false; 
				}
			
			if(year_age!='NAN')
			{
				$(".age").val(year_age);
				$('.mem_age1').val(year_age);  
			}
		} else {
			alert('Invalid date format');
			$(this).val('');
			return false;		
		}
    });

/*---------------- Date format ----------------*/
<<<<<<< HEAD:accounts/js/commonValidation.js
	
$(".date_of_year").blur(function(){
=======
	$(".date_of_year").blur(function(){
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:js/commonValidation.js
		
		var str =$(this).val();
		var dateformat = /^(0?[1-9]|[12][0-9]|3[01])[-](0?[1-9]|1[012])[-]\d{4}$/;
		
		if(str.match(dateformat)){
			return true;
		} else {
			alert('Invalid date format');
			$(this).val('');
			return false;
		}
	});

<<<<<<< HEAD:accounts/js/commonValidation.js

	$(".date_dd_mm_yyyy").change(function () {
    const $this = $(this);
    const input = $.trim($this.val());

    const parts = input.split("-");

    //Check basic structure: DD-MM-YYYY
    if (parts.length !== 3) {
      alert("Invalid date format. Use DD-MM-YYYY.");
      $this.val("");
      return false;
    }

    const day = parseInt(parts[0], 10);
    const month = parseInt(parts[1], 10) - 1; // JS months: 0–11
    const year = parseInt(parts[2], 10);

    //Check if it's a real calendar date
    const date = new Date(year, month, day);

    if (
      date.getFullYear() !== year ||
      date.getMonth() !== month ||
      date.getDate() !== day
    ) {
      alert("Invalid date or date format. Use DD-MM-YYYY.");
      $this.val("");
      return false;
    }
   //Get min/max depending on which datepicker is used

    let minDate = null;
    let maxDate = null;

    //First try Gijgo (instance stored in data("datepicker"))
    const dp = $this.data("datepicker");
    if (dp) {
        // Gijgo: minDate/maxDate can be Date or string or function
        if (dp.minDate) {
            minDate = dp.minDate instanceof Date ? dp.minDate : new Date(dp.minDate);
        }
        if (dp.maxDate) {
            maxDate = dp.maxDate instanceof Date ? dp.maxDate : new Date(dp.maxDate);
        }
    } else if (typeof $this.datepicker === "function") {
        //Fall back to jQuery UI, if used
        try {
            minDate = $this.datepicker("option", "minDate");
            maxDate = $this.datepicker("option", "maxDate");
        } catch (e) {
            // ignore if not jQuery UI
        }
    }

    // 4) Check range against *our* JS Date `date`
    if ((minDate && date < minDate) || (maxDate && date > maxDate)) {
        alert("Date must be within the allowed range.");
        $this.val("");
        return false;
    }

    return true;
  });


/* ..
	$(".date_yyyy_dd_mm").blur(function(){
		
		var str =$(this).val();
		var dateformat =/^\d{4}[-](0?[1-9]|1[0-2])[-](0?[1-9]|[12][0-9]|3[01])$/;
		..
		
    if (str !== "" && !str.match(dateformat)) {
        alert('Invalid date format');
        $(this).val('');
        return false;
    }
	return true;
	});
*/


$(".date_yyyy_mm_dd").change(
    function () 
    {

    console.log('entered date validation yyyy-mm-dd');

    let $this = $(this);
    let input = $this.val().trim();

    // Split input
    let parts = input.split("-");
    if (parts.length !== 3) {
        alert("Invalid date format. Use YYYY-MM-DD.");
        $this.val("");
        return false;
    }

    // Parse into numbers
    let year  = parseInt(parts[0], 10);
    let month = parseInt(parts[1], 10) - 1; // JS months = 0–11
    let day   = parseInt(parts[2], 10);

    // Check if it is a real calendar date
    let date = new Date(year, month, day);
    if (
        date.getFullYear() !== year ||
        date.getMonth()    !== month ||
        date.getDate()     !== day
    ) {
        alert("Invalid date or date format Use YYYY-MM-DD.");
        $this.val("");
        return false;
    }

    let minDate = null;
    let maxDate = null;

	const dp = $this.data("datepicker");
    if (dp) {
        // Gijgo: minDate/maxDate can be Date or string or function
        if (dp.minDate) {
            minDate = dp.minDate instanceof Date ? dp.minDate : new Date(dp.minDate);
        }
        if (dp.maxDate) {
            maxDate = dp.maxDate instanceof Date ? dp.maxDate : new Date(dp.maxDate);
        }
    } else if (typeof $this.datepicker === "function") {
        //Fall back to jQuery UI, if used
        try {
            minDate = $this.datepicker("option", "minDate");
            maxDate = $this.datepicker("option", "maxDate");
        } catch (e) {
            // ignore if not jQuery UI
        }
    }

    // 4) Check range against *our* JS Date `date`
    if ((minDate && date < minDate) || (maxDate && date > maxDate)) {
        alert("Date must be within the allowed range.");
        $this.val("");
        return false;
    }
    

    return true;
});



=======
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:js/commonValidation.js
/*---------------- finyear ----------------*/
	$(".finyear").blur(function(){
        
		var dateformat = /^\d{4}[-]\d{4}$/;
	    var str=$(this).val();
		
	if(str.match(dateformat)){
		var arr = str.split("-");
		var finyr1 = arr[0];
		var finyr2 = arr[1];
		
		if((finyr1.length!=4 || finyr2.length!=4)) {
			alert('Invalid fin year');	
			return false;
		}	
	} else {
			alert('Invalid date format');
			$(this).val('');
			return false;
		}
		
	});


/*------------- Aadhar number validation---------------*/
var d = [
    [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
    [1, 2, 3, 4, 0, 6, 7, 8, 9, 5],
    [2, 3, 4, 0, 1, 7, 8, 9, 5, 6],
    [3, 4, 0, 1, 2, 8, 9, 5, 6, 7],
    [4, 0, 1, 2, 3, 9, 5, 6, 7, 8],
    [5, 9, 8, 7, 6, 0, 4, 3, 2, 1],
    [6, 5, 9, 8, 7, 1, 0, 4, 3, 2],
    [7, 6, 5, 9, 8, 2, 1, 0, 4, 3],
    [8, 7, 6, 5, 9, 3, 2, 1, 0, 4],
    [9, 8, 7, 6, 5, 4, 3, 2, 1, 0]
];

// permutation table p
var p = [
    [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
    [1, 5, 7, 6, 2, 8, 3, 0, 9, 4],
    [5, 8, 0, 3, 7, 9, 6, 1, 4, 2],
    [8, 9, 1, 6, 0, 4, 3, 5, 2, 7],
    [9, 4, 5, 3, 1, 2, 6, 8, 7, 0],
    [4, 2, 8, 6, 5, 7, 3, 9, 0, 1],
    [2, 7, 9, 3, 8, 0, 6, 4, 1, 5],
    [7, 0, 4, 6, 9, 1, 3, 2, 5, 8]
];

// inverse table inv
var inv = [0, 4, 3, 2, 1, 5, 6, 7, 8, 9];
var msg='';
// converts string or number to an array and inverts it
function invArray(array)
 {

    if (Object.prototype.toString.call(array) === "[object Number]")
	 {
        array = String(array);
    }

    if (Object.prototype.toString.call(array) === "[object String]")
	 {
        array = array.split("").map(Number);
    }

    return array.reverse();

}

// generates checksum
function generate(array) {

    var c = 0;
    var invertedArray = invArray(array);
 
    for (var i = 0; i < invertedArray.length; i++) {
        c = d[c][p[((i + 1) % 8)][invertedArray[i]]];
    }
     alert(inv[c]);
    return inv[c];
}

// validates checksum
function validate(array,index) {
	//generate(array);
	$('#msg').html('');
	if(array.length !=12)
	{
     
	 alert('Please Enter 12 digit Aadhaar Number!!');
	 $('.aadhar_number').val('');
	 return false;
	}
	else if(array=='333333333333' || array=='666666666666' || array=='999999999999' )
	{
		 alert('Invalid Aadhaar Number!!');
		 $('.aadhar_number').val('');
		 return false;
	}
    var c = 0;
    var invertedArray = invArray(array);
	//alert(invertedArray.length);
    for (var i = 0; i < invertedArray.length; i++) {
        c = d[c][p[(i % 8)][invertedArray[i]]];
		//alert(c);
    }
   if(c==0)
   {
	
	}
	else
	{
	 alert("Invalid Aadhaar Number");
	 $('.aadhar_number').val('');
	}
	//$('#msg').html('');
	//$('#msg').html('<font style="font-weight:bold;" color="green">'+msg+'</font>');
	//$('.aadharno_validate'+index).css('background-color', '').css('border', '1px solid #ced4da');
    
}

$(".aadhar_number").blur(function(){
	var aadhar_val=$(this).val();
	
	validate(aadhar_val,1);
});

	
/*------------- Name tamil field---------------*/
<<<<<<< HEAD:accounts/js/commonValidation.js
/*
old version:

$('.name_tamil').bind('keyup blur',function(){ 
	$(this).val( $(this).val().replace(/[^அ ஆ இ ஈ உ ஊ எ ஏ ஐ ஒ ஓ ஔ ஃ க  கா  கி  கீ  கு  கூ  கெ  கே  கை  கொ  கோ  கௌ  க்  ங  ஙா  ஙி  ஙீ  ஙு  ஙூ  ஙெ  ஙே  ஙை  ஙொ  ஙோ  ஙௌ  ங்  ச  சா  சி  சீ  சு  சூ  செ  சே  சை  சொ  சோ  சௌ  ச்  ஞ  ஞா  ஞி  ஞீ  ஞு  ஞூ  ஞெ  ஞே  ஞை  ஞொ  ஞோ  ஞௌ  ஞ்  ட  டா  டி  டீ  டு  டூ  டெ  டே  டை  டொ  டோ  டௌ  ட்  ண  ணா  ணி  ணீ  ணு  ணூ  ணெ  ணே  ணை  ணொ  ணோ  ணௌ  ண்  த  தா  தி  தீ  து  தூ  தெ  தே  தை  தொ  தோ  தௌ  த்  ந  நா  நி  நீ  நு  நூ  நெ  நே  நை  நொ  நோ  நௌ  ந்  ப  பா  பி  பீ  பு  பூ  பெ  பே  பை  பொ  போ  பௌ  ப்  ம  மா  மி  மீ  மு  மூ  மெ  மே  மை  மொ  மோ  மௌ  ம்  ய  யா  யி  யீ  யு  யூ  யெ  யே  யை  யொ  யோ  யௌ  ய்  ர  ரா  ரி  ரீ  ரு  ரூ  ரெ  ரே  ரை  ரொ  ரோ  ரௌ  ர்  ல  லா  லி  லீ  லு  லூ  லெ  லே  லை  லொ  லோ  லௌ  ல்  வ  வா  வி  வீ  வு  வூ  வெ  வே  வை  வொ  வோ  வௌ  வ்  ழ  ழா  ழி  ழீ  ழு  ழூ  ழெ  ழே  ழை  ழொ  ழோ  ழௌ  ழ்  ள  ளா  ளி  ளீ  ளு  ளூ  ளெ  ளே  ளை  ளொ  ளோ  ளௌ  ள்  ற  றா  றி  றீ  று  றூ  றெ  றே  றை  றொ  றோ  றௌ  ற்  ன  னா  னி  னீ  னு  னூ  னெ  னே  னை  னொ  னோ  னௌ  ன்   ஜ  ஜா  ஜி  ஜீ  ஜு  ஜூ  ஜெ  ஜே  ஜை  ஜொ  ஜோ  ஜௌ  ஜ்  ஷ  ஷா  ஷி  ஷீ  ஷு  ஷூ  ஷெ  ஷே  ஷை  ஷொ  ஷோ  ஷௌ  ஷ்  ஸ  ஸா  ஸி  ஸீ  ஸு  ஸூ  ஸெ  ஸே  ஸை  ஸொ  ஸோ  ஸௌ  ஸ்  ஹ  ஹா  ஹி  ஹீ  ஹு  ஹூ  ஹெ  ஹே  ஹை  ஹொ  ஹோ  ஹௌ  ஹ்  க்ஷ  க்ஷா  க்ஷி  க்ஷீ  க்ஷு  க்ஷூ  க்ஷெ  க்ஷே  க்ஷை  க்ஷொ  க்ஷோ  க்ஷௌ  க்ஷ்   ஸ்ரீ   ஃப  ஃபா  ஃபி  ஃபீ  ஃபு  ஃபூ  ஃபெ  ஃபே  ஃபை  ஃபொ  ஃபோ  ஃபௌ  ஃப்  ஃஜ  ஃஜா  ஃஜி  ஃஜீ  ஃஜு  ஃஜூ  ஃஜெ  ஃஜே  ஃஜை  ஃஜொ  ஃஜோ  ஃஜௌ  ஃஜ்  ஃஸ  ஃஸா  ஃஸி  ஃஸீ  ஃஸு  ஃஸூ  ஃஸெ  ஃஸே  ஃஸை  ஃஸொ  ஃஸோ  ஃஸௌ  ஃஸ்   ௧  ௨  ௩  ௪  ௫  ௬  ௭  ௮  ௯  ௰  ௲ ]/g,''));
});
*/
$('.name_tamil').on('keyup blur', function () {
    $(this).val(
        $(this).val().replace(/[^\u0B85-\u0BBF\u0BC1-\u0BCD\u0BD0\u0BD7\u0BE7-\u0BEA ]/g, '')
    );
});
=======
$('.name_tamil').bind('keyup blur',function(){ 
	$(this).val( $(this).val().replace(/[^அ ஆ இ ஈ உ ஊ எ ஏ ஐ ஒ ஓ ஔ ஃ க  கா  கி  கீ  கு  கூ  கெ  கே  கை  கொ  கோ  கௌ  க்  ங  ஙா  ஙி  ஙீ  ஙு  ஙூ  ஙெ  ஙே  ஙை  ஙொ  ஙோ  ஙௌ  ங்  ச  சா  சி  சீ  சு  சூ  செ  சே  சை  சொ  சோ  சௌ  ச்  ஞ  ஞா  ஞி  ஞீ  ஞு  ஞூ  ஞெ  ஞே  ஞை  ஞொ  ஞோ  ஞௌ  ஞ்  ட  டா  டி  டீ  டு  டூ  டெ  டே  டை  டொ  டோ  டௌ  ட்  ண  ணா  ணி  ணீ  ணு  ணூ  ணெ  ணே  ணை  ணொ  ணோ  ணௌ  ண்  த  தா  தி  தீ  து  தூ  தெ  தே  தை  தொ  தோ  தௌ  த்  ந  நா  நி  நீ  நு  நூ  நெ  நே  நை  நொ  நோ  நௌ  ந்  ப  பா  பி  பீ  பு  பூ  பெ  பே  பை  பொ  போ  பௌ  ப்  ம  மா  மி  மீ  மு  மூ  மெ  மே  மை  மொ  மோ  மௌ  ம்  ய  யா  யி  யீ  யு  யூ  யெ  யே  யை  யொ  யோ  யௌ  ய்  ர  ரா  ரி  ரீ  ரு  ரூ  ரெ  ரே  ரை  ரொ  ரோ  ரௌ  ர்  ல  லா  லி  லீ  லு  லூ  லெ  லே  லை  லொ  லோ  லௌ  ல்  வ  வா  வி  வீ  வு  வூ  வெ  வே  வை  வொ  வோ  வௌ  வ்  ழ  ழா  ழி  ழீ  ழு  ழூ  ழெ  ழே  ழை  ழொ  ழோ  ழௌ  ழ்  ள  ளா  ளி  ளீ  ளு  ளூ  ளெ  ளே  ளை  ளொ  ளோ  ளௌ  ள்  ற  றா  றி  றீ  று  றூ  றெ  றே  றை  றொ  றோ  றௌ  ற்  ன  னா  னி  னீ  னு  னூ  னெ  னே  னை  னொ  னோ  னௌ  ன்   ஜ  ஜா  ஜி  ஜீ  ஜு  ஜூ  ஜெ  ஜே  ஜை  ஜொ  ஜோ  ஜௌ  ஜ்  ஷ  ஷா  ஷி  ஷீ  ஷு  ஷூ  ஷெ  ஷே  ஷை  ஷொ  ஷோ  ஷௌ  ஷ்  ஸ  ஸா  ஸி  ஸீ  ஸு  ஸூ  ஸெ  ஸே  ஸை  ஸொ  ஸோ  ஸௌ  ஸ்  ஹ  ஹா  ஹி  ஹீ  ஹு  ஹூ  ஹெ  ஹே  ஹை  ஹொ  ஹோ  ஹௌ  ஹ்  க்ஷ  க்ஷா  க்ஷி  க்ஷீ  க்ஷு  க்ஷூ  க்ஷெ  க்ஷே  க்ஷை  க்ஷொ  க்ஷோ  க்ஷௌ  க்ஷ்   ஸ்ரீ   ஃப  ஃபா  ஃபி  ஃபீ  ஃபு  ஃபூ  ஃபெ  ஃபே  ஃபை  ஃபொ  ஃபோ  ஃபௌ  ஃப்  ஃஜ  ஃஜா  ஃஜி  ஃஜீ  ஃஜு  ஃஜூ  ஃஜெ  ஃஜே  ஃஜை  ஃஜொ  ஃஜோ  ஃஜௌ  ஃஜ்  ஃஸ  ஃஸா  ஃஸி  ஃஸீ  ஃஸு  ஃஸூ  ஃஸெ  ஃஸே  ஃஸை  ஃஸொ  ஃஸோ  ஃஸௌ  ஃஸ்   ௧  ௨  ௩  ௪  ௫  ௬  ௭  ௮  ௯  ௰  ௲ ]/g,''));
});
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:js/commonValidation.js


/*------------- ward tamil field---------------*/
$('.ward_tamil').bind('keyup blur',function(){ 
	$(this).val( $(this).val().replace(/[^0-9அ ஆ இ ஈ உ ஊ எ ஏ ஐ ஒ ஓ ஔ ஃ க  கா  கி  கீ  கு  கூ  கெ  கே  கை  கொ  கோ  கௌ  க்  ங  ஙா  ஙி  ஙீ  ஙு  ஙூ  ஙெ  ஙே  ஙை  ஙொ  ஙோ  ஙௌ  ங்  ச  சா  சி  சீ  சு  சூ  செ  சே  சை  சொ  சோ  சௌ  ச்  ஞ  ஞா  ஞி  ஞீ  ஞு  ஞூ  ஞெ  ஞே  ஞை  ஞொ  ஞோ  ஞௌ  ஞ்  ட  டா  டி  டீ  டு  டூ  டெ  டே  டை  டொ  டோ  டௌ  ட்  ண  ணா  ணி  ணீ  ணு  ணூ  ணெ  ணே  ணை  ணொ  ணோ  ணௌ  ண்  த  தா  தி  தீ  து  தூ  தெ  தே  தை  தொ  தோ  தௌ  த்  ந  நா  நி  நீ  நு  நூ  நெ  நே  நை  நொ  நோ  நௌ  ந்  ப  பா  பி  பீ  பு  பூ  பெ  பே  பை  பொ  போ  பௌ  ப்  ம  மா  மி  மீ  மு  மூ  மெ  மே  மை  மொ  மோ  மௌ  ம்  ய  யா  யி  யீ  யு  யூ  யெ  யே  யை  யொ  யோ  யௌ  ய்  ர  ரா  ரி  ரீ  ரு  ரூ  ரெ  ரே  ரை  ரொ  ரோ  ரௌ  ர்  ல  லா  லி  லீ  லு  லூ  லெ  லே  லை  லொ  லோ  லௌ  ல்  வ  வா  வி  வீ  வு  வூ  வெ  வே  வை  வொ  வோ  வௌ  வ்  ழ  ழா  ழி  ழீ  ழு  ழூ  ழெ  ழே  ழை  ழொ  ழோ  ழௌ  ழ்  ள  ளா  ளி  ளீ  ளு  ளூ  ளெ  ளே  ளை  ளொ  ளோ  ளௌ  ள்  ற  றா  றி  றீ  று  றூ  றெ  றே  றை  றொ  றோ  றௌ  ற்  ன  னா  னி  னீ  னு  னூ  னெ  னே  னை  னொ  னோ  னௌ  ன்   ஜ  ஜா  ஜி  ஜீ  ஜு  ஜூ  ஜெ  ஜே  ஜை  ஜொ  ஜோ  ஜௌ  ஜ்  ஷ  ஷா  ஷி  ஷீ  ஷு  ஷூ  ஷெ  ஷே  ஷை  ஷொ  ஷோ  ஷௌ  ஷ்  ஸ  ஸா  ஸி  ஸீ  ஸு  ஸூ  ஸெ  ஸே  ஸை  ஸொ  ஸோ  ஸௌ  ஸ்  ஹ  ஹா  ஹி  ஹீ  ஹு  ஹூ  ஹெ  ஹே  ஹை  ஹொ  ஹோ  ஹௌ  ஹ்  க்ஷ  க்ஷா  க்ஷி  க்ஷீ  க்ஷு  க்ஷூ  க்ஷெ  க்ஷே  க்ஷை  க்ஷொ  க்ஷோ  க்ஷௌ  க்ஷ்   ஸ்ரீ   ஃப  ஃபா  ஃபி  ஃபீ  ஃபு  ஃபூ  ஃபெ  ஃபே  ஃபை  ஃபொ  ஃபோ  ஃபௌ  ஃப்  ஃஜ  ஃஜா  ஃஜி  ஃஜீ  ஃஜு  ஃஜூ  ஃஜெ  ஃஜே  ஃஜை  ஃஜொ  ஃஜோ  ஃஜௌ  ஃஜ்  ஃஸ  ஃஸா  ஃஸி  ஃஸீ  ஃஸு  ஃஸூ  ஃஸெ  ஃஸே  ஃஸை  ஃஸொ  ஃஸோ  ஃஸௌ  ஃஸ்   ௧  ௨  ௩  ௪  ௫  ௬  ௭  ௮  ௯  ௰  ௲ ]/g,''));
});



/*------------- Name tamil field with comma and dot---------------*/
<<<<<<< HEAD:accounts/js/commonValidation.js

$('.name_tamil_comma_dot').on('keyup blur', function () {
    $(this).val(
        $(this).val().replace(/[^அ-௿0-9\/.,()\-\s]/g, '')
    );
});

/*
$('.name_tamil_comma_dot').bind('keyup blur',function(){ 
	$(this).val( $(this).val().replace(/[^அ ஆ இ ஈ உ ஊ எ ஏ ஐ ஒ ஓ ஔ ஃ க  கா  கி  கீ  கு  கூ  கெ  கே  கை  கொ  கோ  கௌ  க்  ங  ஙா  ஙி  ஙீ  ஙு  ஙூ  ஙெ  ஙே  ஙை  ஙொ  ஙோ  ஙௌ  ங்  ச  சா  சி  சீ  சு  சூ  செ  சே  சை  சொ  சோ  சௌ  ச்  ஞ  ஞா  ஞி  ஞீ  ஞு  ஞூ  ஞெ  ஞே  ஞை  ஞொ  ஞோ  ஞௌ  ஞ்  ட  டா  டி  டீ  டு  டூ  டெ  டே  டை  டொ  டோ  டௌ  ட்  ண  ணா  ணி  ணீ  ணு  ணூ  ணெ  ணே  ணை  ணொ  ணோ  ணௌ  ண்  த  தா  தி  தீ  து  தூ  தெ  தே  தை  தொ  தோ  தௌ  த்  ந  நா  நி  நீ  நு  நூ  நெ  நே  நை  நொ  நோ  நௌ  ந்  ப  பா  பி  பீ  பு  பூ  பெ  பே  பை  பொ  போ  பௌ  ப்  ம  மா  மி  மீ  மு  மூ  மெ  மே  மை  மொ  மோ  மௌ  ம்  ய  யா  யி  யீ  யு  யூ  யெ  யே  யை  யொ  யோ  யௌ  ய்  ர  ரா  ரி  ரீ  ரு  ரூ  ரெ  ரே  ரை  ரொ  ரோ  ரௌ  ர்  ல  லா  லி  லீ  லு  லூ  லெ  லே  லை  லொ  லோ  லௌ  ல்  வ  வா  வி  வீ  வு  வூ  வெ  வே  வை  வொ  வோ  வௌ  வ்  ழ  ழா  ழி  ழீ  ழு  ழூ  ழெ  ழே  ழை  ழொ  ழோ  ழௌ  ழ்  ள  ளா  ளி  ளீ  ளு  ளூ  ளெ  ளே  ளை  ளொ  ளோ  ளௌ  ள்  ற  றா  றி  றீ  று  றூ  றெ  றே  றை  றொ  றோ  றௌ  ற்  ன  னா  னி  னீ  னு  னூ  னெ  னே  னை  னொ  னோ  னௌ  ன்   ஜ  ஜா  ஜி  ஜீ  ஜு  ஜூ  ஜெ  ஜே  ஜை  ஜொ  ஜோ  ஜௌ  ஜ்  ஷ  ஷா  ஷி  ஷீ  ஷு  ஷூ  ஷெ  ஷே  ஷை  ஷொ  ஷோ  ஷௌ  ஷ்  ஸ  ஸா  ஸி  ஸீ  ஸு  ஸூ  ஸெ  ஸே  ஸை  ஸொ  ஸோ  ஸௌ  ஸ்  ஹ  ஹா  ஹி  ஹீ  ஹு  ஹூ  ஹெ  ஹே  ஹை  ஹொ  ஹோ  ஹௌ  ஹ்  க்ஷ  க்ஷா  க்ஷி  க்ஷீ  க்ஷு  க்ஷூ  க்ஷெ  க்ஷே  க்ஷை  க்ஷொ  க்ஷோ  க்ஷௌ  க்ஷ்   ஸ்ரீ   ஃப  ஃபா  ஃபி  ஃபீ  ஃபு  ஃபூ  ஃபெ  ஃபே  ஃபை  ஃபொ  ஃபோ  ஃபௌ  ஃப்  ஃஜ  ஃஜா  ஃஜி  ஃஜீ  ஃஜு  ஃஜூ  ஃஜெ  ஃஜே  ஃஜை  ஃஜொ  ஃஜோ  ஃஜௌ  ஃஜ்  ஃஸ  ஃஸா  ஃஸி  ஃஸீ  ஃஸு  ஃஸூ  ஃஸெ  ஃஸே  ஃஸை  ஃஸொ  ஃஸோ  ஃஸௌ  ஃஸ்   ௧  ௨  ௩  ௪  ௫  ௬  ௭  ௮  ௯  ௰  ௲  (0-9) \/\ .,-()]/g,''));
});	
*/





/*------------- Alpha numeric tamil field with number,comma and dot---------------*/	
/*		
$('.alphanum_tamil_comma_dot').bind('keyup blur',function(){ 
	$(this).val( $(this).val().replace(/[^0-9 அ ஆ இ ஈ உ ஊ எ ஏ ஐ ஒ ஓ ஔ ஃ க  கா  கி  கீ  கு  கூ  கெ  கே  கை  கொ  கோ  கௌ  க்  ங  ஙா  ஙி  ஙீ  ஙு  ஙூ  ஙெ  ஙே  ஙை  ஙொ  ஙோ  ஙௌ  ங்  ச  சா  சி  சீ  சு  சூ  செ  சே  சை  சொ  சோ  சௌ  ச்  ஞ  ஞா  ஞி  ஞீ  ஞு  ஞூ  ஞெ  ஞே  ஞை  ஞொ  ஞோ  ஞௌ  ஞ்  ட  டா  டி  டீ  டு  டூ  டெ  டே  டை  டொ  டோ  டௌ  ட்  ண  ணா  ணி  ணீ  ணு  ணூ  ணெ  ணே  ணை  ணொ  ணோ  ணௌ  ண்  த  தா  தி  தீ  து  தூ  தெ  தே  தை  தொ  தோ  தௌ  த்  ந  நா  நி  நீ  நு  நூ  நெ  நே  நை  நொ  நோ  நௌ  ந்  ப  பா  பி  பீ  பு  பூ  பெ  பே  பை  பொ  போ  பௌ  ப்  ம  மா  மி  மீ  மு  மூ  மெ  மே  மை  மொ  மோ  மௌ  ம்  ய  யா  யி  யீ  யு  யூ  யெ  யே  யை  யொ  யோ  யௌ  ய்  ர  ரா  ரி  ரீ  ரு  ரூ  ரெ  ரே  ரை  ரொ  ரோ  ரௌ  ர்  ல  லா  லி  லீ  லு  லூ  லெ  லே  லை  லொ  லோ  லௌ  ல்  வ  வா  வி  வீ  வு  வூ  வெ  வே  வை  வொ  வோ  வௌ  வ்  ழ  ழா  ழி  ழீ  ழு  ழூ  ழெ  ழே  ழை  ழொ  ழோ  ழௌ  ழ்  ள  ளா  ளி  ளீ  ளு  ளூ  ளெ  ளே  ளை  ளொ  ளோ  ளௌ  ள்  ற  றா  றி  றீ  று  றூ  றெ  றே  றை  றொ  றோ  றௌ  ற்  ன  னா  னி  னீ  னு  னூ  னெ  னே  னை  னொ  னோ  னௌ  ன்   ஜ  ஜா  ஜி  ஜீ  ஜு  ஜூ  ஜெ  ஜே  ஜை  ஜொ  ஜோ  ஜௌ  ஜ்  ஷ  ஷா  ஷி  ஷீ  ஷு  ஷூ  ஷெ  ஷே  ஷை  ஷொ  ஷோ  ஷௌ  ஷ்  ஸ  ஸா  ஸி  ஸீ  ஸு  ஸூ  ஸெ  ஸே  ஸை  ஸொ  ஸோ  ஸௌ  ஸ்  ஹ  ஹா  ஹி  ஹீ  ஹு  ஹூ  ஹெ  ஹே  ஹை  ஹொ  ஹோ  ஹௌ  ஹ்  க்ஷ  க்ஷா  க்ஷி  க்ஷீ  க்ஷு  க்ஷூ  க்ஷெ  க்ஷே  க்ஷை  க்ஷொ  க்ஷோ  க்ஷௌ  க்ஷ்   ஸ்ரீ   ஃப  ஃபா  ஃபி  ஃபீ  ஃபு  ஃபூ  ஃபெ  ஃபே  ஃபை  ஃபொ  ஃபோ  ஃபௌ  ஃப்  ஃஜ  ஃஜா  ஃஜி  ஃஜீ  ஃஜு  ஃஜூ  ஃஜெ  ஃஜே  ஃஜை  ஃஜொ  ஃஜோ  ஃஜௌ  ஃஜ்  ஃஸ  ஃஸா  ஃஸி  ஃஸீ  ஃஸு  ஃஸூ  ஃஸெ  ஃஸே  ஃஸை  ஃஸொ  ஃஸோ  ஃஸௌ  ஃஸ்   ௧  ௨  ௩  ௪  ௫  ௬  ௭  ௮  ௯  ௰  ௲ .,-() ]/g,''));
});				
*/
$('.alphanum_tamil_comma_dot').on('keyup blur', function () {
    $(this).val(
        $(this).val().replace(/[^0-9அ-௿.,()\- ]/g, '')
    );
});
		
$('.alphanum_tamil_comma_dot_slash').on('keyup blur', function () {
    $(this).val(
        $(this).val().replace(/[^0-9அ-௿.,()\- \/]/g, '')
    );
});

=======
$('.name_tamil_comma_dot').bind('keyup blur',function(){ 
	$(this).val( $(this).val().replace(/[^அ ஆ இ ஈ உ ஊ எ ஏ ஐ ஒ ஓ ஔ ஃ க  கா  கி  கீ  கு  கூ  கெ  கே  கை  கொ  கோ  கௌ  க்  ங  ஙா  ஙி  ஙீ  ஙு  ஙூ  ஙெ  ஙே  ஙை  ஙொ  ஙோ  ஙௌ  ங்  ச  சா  சி  சீ  சு  சூ  செ  சே  சை  சொ  சோ  சௌ  ச்  ஞ  ஞா  ஞி  ஞீ  ஞு  ஞூ  ஞெ  ஞே  ஞை  ஞொ  ஞோ  ஞௌ  ஞ்  ட  டா  டி  டீ  டு  டூ  டெ  டே  டை  டொ  டோ  டௌ  ட்  ண  ணா  ணி  ணீ  ணு  ணூ  ணெ  ணே  ணை  ணொ  ணோ  ணௌ  ண்  த  தா  தி  தீ  து  தூ  தெ  தே  தை  தொ  தோ  தௌ  த்  ந  நா  நி  நீ  நு  நூ  நெ  நே  நை  நொ  நோ  நௌ  ந்  ப  பா  பி  பீ  பு  பூ  பெ  பே  பை  பொ  போ  பௌ  ப்  ம  மா  மி  மீ  மு  மூ  மெ  மே  மை  மொ  மோ  மௌ  ம்  ய  யா  யி  யீ  யு  யூ  யெ  யே  யை  யொ  யோ  யௌ  ய்  ர  ரா  ரி  ரீ  ரு  ரூ  ரெ  ரே  ரை  ரொ  ரோ  ரௌ  ர்  ல  லா  லி  லீ  லு  லூ  லெ  லே  லை  லொ  லோ  லௌ  ல்  வ  வா  வி  வீ  வு  வூ  வெ  வே  வை  வொ  வோ  வௌ  வ்  ழ  ழா  ழி  ழீ  ழு  ழூ  ழெ  ழே  ழை  ழொ  ழோ  ழௌ  ழ்  ள  ளா  ளி  ளீ  ளு  ளூ  ளெ  ளே  ளை  ளொ  ளோ  ளௌ  ள்  ற  றா  றி  றீ  று  றூ  றெ  றே  றை  றொ  றோ  றௌ  ற்  ன  னா  னி  னீ  னு  னூ  னெ  னே  னை  னொ  னோ  னௌ  ன்   ஜ  ஜா  ஜி  ஜீ  ஜு  ஜூ  ஜெ  ஜே  ஜை  ஜொ  ஜோ  ஜௌ  ஜ்  ஷ  ஷா  ஷி  ஷீ  ஷு  ஷூ  ஷெ  ஷே  ஷை  ஷொ  ஷோ  ஷௌ  ஷ்  ஸ  ஸா  ஸி  ஸீ  ஸு  ஸூ  ஸெ  ஸே  ஸை  ஸொ  ஸோ  ஸௌ  ஸ்  ஹ  ஹா  ஹி  ஹீ  ஹு  ஹூ  ஹெ  ஹே  ஹை  ஹொ  ஹோ  ஹௌ  ஹ்  க்ஷ  க்ஷா  க்ஷி  க்ஷீ  க்ஷு  க்ஷூ  க்ஷெ  க்ஷே  க்ஷை  க்ஷொ  க்ஷோ  க்ஷௌ  க்ஷ்   ஸ்ரீ   ஃப  ஃபா  ஃபி  ஃபீ  ஃபு  ஃபூ  ஃபெ  ஃபே  ஃபை  ஃபொ  ஃபோ  ஃபௌ  ஃப்  ஃஜ  ஃஜா  ஃஜி  ஃஜீ  ஃஜு  ஃஜூ  ஃஜெ  ஃஜே  ஃஜை  ஃஜொ  ஃஜோ  ஃஜௌ  ஃஜ்  ஃஸ  ஃஸா  ஃஸி  ஃஸீ  ஃஸு  ஃஸூ  ஃஸெ  ஃஸே  ஃஸை  ஃஸொ  ஃஸோ  ஃஸௌ  ஃஸ்   ௧  ௨  ௩  ௪  ௫  ௬  ௭  ௮  ௯  ௰  ௲  (0-9) \/\ .,-()]/g,''));
});	

/*------------- Alpha numeric tamil field with number,comma and dot---------------*/			
$('.alphanum_tamil_comma_dot').bind('keyup blur',function(){ 
	$(this).val( $(this).val().replace(/[^0-9 அ ஆ இ ஈ உ ஊ எ ஏ ஐ ஒ ஓ ஔ ஃ க  கா  கி  கீ  கு  கூ  கெ  கே  கை  கொ  கோ  கௌ  க்  ங  ஙா  ஙி  ஙீ  ஙு  ஙூ  ஙெ  ஙே  ஙை  ஙொ  ஙோ  ஙௌ  ங்  ச  சா  சி  சீ  சு  சூ  செ  சே  சை  சொ  சோ  சௌ  ச்  ஞ  ஞா  ஞி  ஞீ  ஞு  ஞூ  ஞெ  ஞே  ஞை  ஞொ  ஞோ  ஞௌ  ஞ்  ட  டா  டி  டீ  டு  டூ  டெ  டே  டை  டொ  டோ  டௌ  ட்  ண  ணா  ணி  ணீ  ணு  ணூ  ணெ  ணே  ணை  ணொ  ணோ  ணௌ  ண்  த  தா  தி  தீ  து  தூ  தெ  தே  தை  தொ  தோ  தௌ  த்  ந  நா  நி  நீ  நு  நூ  நெ  நே  நை  நொ  நோ  நௌ  ந்  ப  பா  பி  பீ  பு  பூ  பெ  பே  பை  பொ  போ  பௌ  ப்  ம  மா  மி  மீ  மு  மூ  மெ  மே  மை  மொ  மோ  மௌ  ம்  ய  யா  யி  யீ  யு  யூ  யெ  யே  யை  யொ  யோ  யௌ  ய்  ர  ரா  ரி  ரீ  ரு  ரூ  ரெ  ரே  ரை  ரொ  ரோ  ரௌ  ர்  ல  லா  லி  லீ  லு  லூ  லெ  லே  லை  லொ  லோ  லௌ  ல்  வ  வா  வி  வீ  வு  வூ  வெ  வே  வை  வொ  வோ  வௌ  வ்  ழ  ழா  ழி  ழீ  ழு  ழூ  ழெ  ழே  ழை  ழொ  ழோ  ழௌ  ழ்  ள  ளா  ளி  ளீ  ளு  ளூ  ளெ  ளே  ளை  ளொ  ளோ  ளௌ  ள்  ற  றா  றி  றீ  று  றூ  றெ  றே  றை  றொ  றோ  றௌ  ற்  ன  னா  னி  னீ  னு  னூ  னெ  னே  னை  னொ  னோ  னௌ  ன்   ஜ  ஜா  ஜி  ஜீ  ஜு  ஜூ  ஜெ  ஜே  ஜை  ஜொ  ஜோ  ஜௌ  ஜ்  ஷ  ஷா  ஷி  ஷீ  ஷு  ஷூ  ஷெ  ஷே  ஷை  ஷொ  ஷோ  ஷௌ  ஷ்  ஸ  ஸா  ஸி  ஸீ  ஸு  ஸூ  ஸெ  ஸே  ஸை  ஸொ  ஸோ  ஸௌ  ஸ்  ஹ  ஹா  ஹி  ஹீ  ஹு  ஹூ  ஹெ  ஹே  ஹை  ஹொ  ஹோ  ஹௌ  ஹ்  க்ஷ  க்ஷா  க்ஷி  க்ஷீ  க்ஷு  க்ஷூ  க்ஷெ  க்ஷே  க்ஷை  க்ஷொ  க்ஷோ  க்ஷௌ  க்ஷ்   ஸ்ரீ   ஃப  ஃபா  ஃபி  ஃபீ  ஃபு  ஃபூ  ஃபெ  ஃபே  ஃபை  ஃபொ  ஃபோ  ஃபௌ  ஃப்  ஃஜ  ஃஜா  ஃஜி  ஃஜீ  ஃஜு  ஃஜூ  ஃஜெ  ஃஜே  ஃஜை  ஃஜொ  ஃஜோ  ஃஜௌ  ஃஜ்  ஃஸ  ஃஸா  ஃஸி  ஃஸீ  ஃஸு  ஃஸூ  ஃஸெ  ஃஸே  ஃஸை  ஃஸொ  ஃஸோ  ஃஸௌ  ஃஸ்   ௧  ௨  ௩  ௪  ௫  ௬  ௭  ௮  ௯  ௰  ௲ .,-() ]/g,''));
});				
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:js/commonValidation.js


});
function isValidURL(url) {
    return /^(https?:\/\/[^\s]+)$/.test(url); // Only allows http(s) URLs
}
