var SitePath="",JSPath="";



function HelpFaq(arqSitePath,arqJSPath)
{
	
   SitePath=arqSitePath;
   JSPath=arqJSPath;	

   
  var helpModal = getHelpModal();

  // Init the modal if it hasn't been already.
  if (!helpModal) { helpModal = initHelpModal(); }


   $.ajax({
	type: "POST",
	url: SitePath+"project/ajax/AjaxGeneral.php",
	data: { cmd: btoa("13") }
  })
	.done(function( data ) {

		var html =
		'<div class="modal-header">' +
		  '<h5 class="modal-title" id="exampleModalLabel">Help</h5>' +
		  '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
			'<span aria-hidden="true">&times;</span>' +
		  '</button>' +
		'</div>' +
		'<div class="modal-body" style="max-height:80%; y-overflow:auto;">' +
		  data +
		'</div>' +
		'<div class="modal-footer">' +
		'<button type="button" class="btn btn-sm btn-primary pull-right" data-dismiss="modal" aria-label="Close">' +
		'Close' +
	  '</button>'+
		'</div>';
			
			
		setHelpModalContent(html);
		jQuery(helpModal).modal('show');
	});


	

}



/*********************************
Modal
*********************************/

function getHelpModal() {
	return document.getElementById('HelpModal');
  }
  
  function setHelpModalContent(html) {
	getHelpModal().querySelector('.modal-content').innerHTML = html;
  }
  
  function initHelpModal() {
	var modal = document.createElement('div');
	modal.classList.add('modal', 'fade');
	modal.setAttribute('id', 'HelpModal');
	modal.setAttribute('tabindex', '-1');
	modal.setAttribute('role', 'dialog');
	modal.setAttribute('aria-labelledby', 'HelpModalLabel');
	modal.setAttribute('aria-hidden', 'true');
	modal.innerHTML =
		  '<div class="modal-dialog" role="document" style="max-width: 90%; ">' +
			'<div class="modal-content"></div>' +
		  '</div>';
	document.body.appendChild(modal);
	return modal;
  }