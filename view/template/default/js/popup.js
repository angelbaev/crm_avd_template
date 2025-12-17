    function setPopUpBox(title, message) {
			$('#popup_box #popup_box_content').css({'width':'600px', 'height':'300px'});
    	$('#popup_box #popup_heading h1').html('');
    	$('#popup_box #popup_box_content').html('');

    	$('#popup_box #popup_heading h1').html(title);
    	$('#popup_box #popup_box_content').html(message);
			$('#popup_box').fadeIn("slow");
		}
		
    function setIFramePopUpBox(title, src) {
    	$('#popup_box #popup_box_content').css({'width':'250px', 'height':'680px'});
    	$('#popup_box #popup_heading h1').html('');
    	$('#popup_box #popup_box_content').html('');
    	$('#popup_box #popup_heading h1').html(title);
    	var ifrm = '<iframe name="box_iframe" id="box_iframe" src="'+src+'" width="240" height="660" scrolling="auto" noresize="noresize" frameborder="0"></iframe>';
    	$('#popup_box #popup_box_content').html(ifrm);
			$('#popup_box').fadeIn("slow");
		}
		function hidePopUpBox() {
			$('#popup_box').fadeOut("slow");
		}


		function sendRequest(url, params, elementId) {
			$.ajax({
				type : 'POST',
				url : ''+url+'',
				dataType : 'json',
				cache: false,
				data: params,
			  beforeSend: function( xhr ) {
			  	clear_error_log();
			    $('div#ajax_box').html(''); 
			    $('div#ajax_box').css({'text-align':'center'});
					$('div#ajax_box').html('<img src="http://www.unicreditbulbank.bg/weblayout/groups/bulbankwebsite/documents/ass_image/loading.gif" border="0">'); 
			  }, 		
				success : function(data){
			    $('div#ajax_box').html('');
			    
			    //$("#elementId").is("input") ; $("#elementId").get(0).tagName;
			    if (data['success']){
						msg_add(data['success'], MSG_SUCCESS);
						
						if ($(elementId).is('input')) {
						    $(elementId).val(''); 
						    $(elementId).val(data['html']);
						} else {
						    $(elementId).html(''); 
						    $(elementId).html(data['html']);
				    }
					} else {
						msg_add(data['error'], MSG_ERROR);
					}
				
				},
				error : function(XMLHttpRequest, textStatus, errorThrown) {
			    $('div#ajax_box').html(''); 
			    msg_add('Error: '+errorThrown, MSG_ERROR);
				}
			});
		}
