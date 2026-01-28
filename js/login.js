var SitePath = "", JSPath = "";
function LoginShow(arqSitePath, arqJSPath, loginState = '') {
	
	SitePath = arqSitePath;
	JSPath = arqJSPath;
	var data_content = "User Login";
	var exampleModal = getExampleModal();
	if (!exampleModal) { exampleModal = initExampleModal(); }
	var element = document.getElementById('exampleModal');
	if (element.style.display === 'none') {
		$("#exampleModal").show();
	$(".modal-backdrop").show();
		
	}
	queryString = window.location.search;
	urlParams = new URLSearchParams(queryString);
	user = urlParams.get('user');
	$.ajax({
		type: "POST",
		url: SitePath + "project/forms/LoginContent.php",
		data: { cmd: "2", code: 'nav', loginState: loginState }
	}).done(function (data) {
		var html = data;
		setExampleModalContent(html);
		// document.getElementById("Alert").style.display = "none";
		jQuery(exampleModal).modal('show');
		$('#user_name_temp').focus();
		$(".alert_login_fail").delay(4000).slideUp(200, function () {
			$(this).alert('close');
		});
	});
}
function getExampleModal() {
	return document.getElementById('exampleModal');
}
function setExampleModalContent(html) {
	getExampleModal().querySelector('.modal-content').innerHTML = html;
}
function initExampleModal() {
	var modal = document.createElement('div');
	modal.classList.add('modal', 'fade');
	modal.setAttribute('id', 'exampleModal');
	modal.setAttribute('tabindex', '-1');
	modal.setAttribute('role', 'dialog');
	modal.setAttribute('aria-labelledby', 'exampleModalLabel');
	modal.setAttribute('aria-hidden', 'true');
	modal.innerHTML =
		'<div class="modal-dialog" role="document" >' +
		'<div class="modal-content" style="border:0; border-radius:10px;background:transparent;   padding: 12px;width:78%"></div>' +
		'</div>';
	document.body.appendChild(modal);
	return modal;
}


function showAlert() {
	$("#exampleModal").hide();
	$(".modal-backdrop").hide();
	
}



