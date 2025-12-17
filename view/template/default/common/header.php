<?php
	$_PAGE_TITLE = array(
		"dashboard" => array("home" => "Начало")
		, "documents" => array("new_doc" => "Нов документ", "edit_doc" => "Редакция на документ", "send_email" => "Изпращане на писмо", "all" => "Всички", "not_payed" => "Неплатени", "ended" => "Реалицирани","notended" => "Нереалицирани", "active" => "Активни")
		, "reports" => array("patners" => "Справка по клиенти", "users" => "Справка по потребители", "category" => "Справка по категории", "suppliers" => "Справка по контрагенти",  "timetracker" => "Справка за работно време", "not_payed" => "Плащания")
		, "patners" => array("new_partner" => "Нов клиент", "edit_partner" => "Редактиране на клиент", "list" => "Списък на клиенти")
		, "contractors" => array("new_contractor" => "Нов контрагент", "edit_contractor" => "Редактиране на контрагент", "list" => "Списък на контрагенти", "printing" => "Печатници")
		, "activities" => array("all" => "Всички дейности", "owner" => "Мои дейности", "edit" => "Редактиране на дейност")
		, "payoffice" => array("list" => "Каса", "new_paycase" => "Нов Приход / Разход", "esit_paycase" =>"Редактиране Приход / Разход", "payment" => "Разплащане", "stock" => "Наличности")
		, "settings" => array("users" => "Потребители", "roles" => "Роли/Групи", "perms" => "Достъп")
	);
	$session_maxlifetime = ini_get('session.gc_maxlifetime');
	$session_maxlifetime = ($session_maxlifetime/60);
	
	if(isset($_SESSION["WORK_TIME_DURATION"])) {
    $_SESSION["WORK_TIME_DURATION"] = (time()-$_SESSION["WORK_TIME_START"]);
    saveTimeTrackerDuration();
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1251">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <script src="<?php echo TEMPLATE; ?>js/jquery-1.6.1.min.js"  type="text/javascript"></script>
  
  <link rel="stylesheet" href="<?php echo TEMPLATE; ?>js/themes/ui-lightness/jquery.ui.all.css">
	<script src="<?php echo TEMPLATE; ?>js/jquery-1.8.0.js"></script>
	<script src="<?php echo TEMPLATE; ?>js/ui/jquery.ui.core.js"></script>
	<script src="<?php echo TEMPLATE; ?>js/ui/jquery.ui.datepicker.js"></script>
	<script src="<?php echo TEMPLATE; ?>js/jquery.cookie.js"></script>
	<script src="<?php echo TEMPLATE; ?>js/popup.js"></script>
	<script src="<?php echo TEMPLATE; ?>js/jquery.countdown.js"></script>

  
	<link type="text/css" rel="Stylesheet" href="<?php echo TEMPLATE; ?>css/reset.css" media="screen" />
	<link type="text/css" rel="Stylesheet" href="<?php echo TEMPLATE; ?>css/style.css" media="screen" />
	<link type="text/css" rel="Stylesheet" href="<?php echo TEMPLATE; ?>css/popup.css" media="screen" />
	<link type="text/css" rel="Stylesheet" href="<?php echo TEMPLATE; ?>css/jquery.countdown.css" media="screen" />
  <link rel="shortcut icon" href="<?php echo HTTP_SERVER; ?>icon.ico" type="image/ico" />
	<title><?php echo(isset($_PAGE_TITLE[$route][$page_tab])?$_PAGE_TITLE[$route][$page_tab]:$_PAGE_TITLE["dashboard"]["home"]);?></title>
  <script type="text/javascript">
     	var MSG_ERROR = '1';
    	var MSG_INFO = '3';
    	var MSG_SUCCESS = '4';
    	var MSG_WARNING = '2';

		function safeSubmit(f, onsubmit, nocheckloaded) {
			if (!nocheckloaded && !document.body.loaded) {
			   alert('Страницата не е напълно заредена! Използвайте [Refresh]');
			   return (onsubmit?false:true);
			}
			if (f.submited) return (onsubmit?false:true);
			f.submited = true;
			if (!onsubmit)	f.submit();
			return true;
		}

    	
  	$(document).ready(function(){

		  $('div.edit_icon').click(function(){
		    var edit_div = $(this).parent().parent().children('.edit_content').children('.editable').attr('id');
		    var edit_textarea = $(this).parent().parent().children('.edit_content').children('textarea').attr('id');
				if ($('div#'+edit_div).attr('contenteditable') != 'false') {
			    $(this).parent().parent().css({'border':'0px solid'});
			  	$('div#'+edit_div).attr('contenteditable', false);
			  	$(this).closest('.edit_wrapp').find('.edit_content').removeClass('is-editable');
			  	$(this).closest('.edit_wrapp').find('.editable_tooltip').remove();
			  	$('textarea#'+edit_textarea).val($('div#'+edit_div).html());
				} else {
			    $(this).parent().parent().css({'border':'1px red dotted'});
			  	$('div#'+edit_div).attr('contenteditable', true);
			  	$(this).closest('.edit_wrapp').find('.edit_content').addClass('is-editable');
			  	$(this).closest('.edit_wrapp').find('table table tr td:first-child').append('<span class="editable_tooltip"><span class="e-plus">[+]</span><span class="e-minus">[-]</span></span>');
				}
			});
			
    $(document).on('click','.e-plus',function() {
        $(this).closest('table').append('<tr><td style="text-align: right;padding-right: 5px;">&nbsp;<span class="editable_tooltip"><span class="e-plus">[+]</span><span class="e-minus">[-]</span></span></td><td>&nbsp;</td></tr>');
    });		
    $(document).on('click','.e-minus',function() {
        $(this).closest('tr').remove();
    });			
    	
/*	
      $( '.edit_content table table tr td:first-child' )
      .mouseover(function() {
        if ($(this).closest('.edit_content').hasClass('is-editable')) {
          $(this).append('<span class="editable_tooltip">xxxxx</span>');
        }
      })
      .mouseout(function() {
        $( '.editable_tooltip' ).remove();
      });
	*/		
			//search 
			$('input#input_search').click(function(){
				$(this).val('');
			});

			$('input#input_search').keyup(function(){
				$('div#search_list').slideDown();
				clear_error_log();
				//msg_add($('input#input_search').val());
				//sendRequest('ajax/system-ajax.php', {'act':'search_partner', 'pid':''+$('input#input_search').val()+''}, 'div#search_list');
			url = 'ajax/system-ajax.php';
			params = {'act':'search_partner', 'pname':''+$('input#input_search').val()+''}
			elementId = 'div#search_list';
			$.ajax({
				type : 'POST',
				url : ''+url+'',
				dataType : 'json',
				cache: false,
				data: params,
			  beforeSend: function( xhr ) {
			  	clear_error_log();
			    $('div#ajax_re').html(''); 
			    $('div#ajax_re').css({'text-align':'center'});
					$('div#ajax_re').html('<img src="http://avdesigngroup.org/crm_avd/view/template/default/images/loading.gif" border="0">'); 
			  }, 		
				success : function(data){
			    $('div#ajax_re').html('');
			    //msg_add(data['html'], MSG_SUCCESS);
			    data['success'] = true;
			    //$("#elementId").is("input") ; $("#elementId").get(0).tagName;
			    
			    if (data['success']){
					//	msg_add(data['success'], MSG_SUCCESS);
						
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
			    $('div#ajax_re').html(''); 
			    msg_add('Error: '+errorThrown, MSG_ERROR);
				}
			});
				
			});
			
			$('input#input_search').blur(function(){
				
				setTimeout(function(){
				$('input#input_search').val('Търси.... ');
				$('div#search_list').hide();
				}, 500);
				
			});
  	
  	});
			function msg_add(msg, type) {
				var set_class = MSG_ERROR;
				switch(type) {
					case '3':
						set_class = 'info'; //MSG_INFO;
						break;
					case '4':
						set_class = 'success'; //MSG_SUCCESS;		
						break;
					case '2':
						set_class = 'warning'; //MSG_WARNING;		
						break;
					default:
						set_class = 'error'; //MSG_ERROR;		
						break;
					}
				var msg_box = 
						'<div class="msg_box">'+
							'<div class="close_msg" onclick="hide_msg(this);">close</div>'+
							'<div class="'+set_class+'">'+msg+'</div>'+
						'</div>';
				$('div#msg_wrapp').append(msg_box);
				$('html, body').animate({ scrollTop: 0 }, 'slow');
			
			}
			
			function clear_error_log() {
				$('div#msg_wrapp').html('');
			}

		function hide_msg(div) {
			$(div).parent().fadeOut();
		}

/*New notification function begin*/
function showNotification(params) {
	if (typeof params['type'] == 'undefined') {
		params['type'] = 'success';
	}
	if (typeof params['message'] == 'undefined') {
		params['message'] = '';
	}
	if (typeof params['showAfter'] == 'undefined') {
		params['showAfter'] = 0;
	}
	if (typeof params['duration'] == 'undefined') {
		params['duration'] = 0;
	}
	if (typeof params['autoClose'] == 'undefined') {
		params['autoClose'] = false;
	}
	
	if (params['type'] == 'error') {
		msg_title = 'Грешка';
	} else if (params['type'] == 'warning') {
		msg_title = 'Внимание';
	} else if (params['type'] == 'info') {
		msg_title = 'Информация';
	} else {
		msg_title = 'Успех';
	}
	var container = '<div class="system_'+params['type']+' message">';
			container += '	<h3>'+msg_title+'</h3>';
			container += '	<div class="close" onclick="removeNotification(this);"></div>';
			container += '	<p>'+params['message']+'</p>';
			container += '</div>';
			
	$('div#notification').hide();
	if (params['showAfter'] > 0) {
		setTimeout(function(){
			$('div#notification').html(container).show('slow');
			$('html, body').animate({ scrollTop: 0 }, 'slow');
		}, parseInt(params['showAfter'] * 1000));
		
		if (params['autoClose']) {
			
			setTimeout(function(){
				$('div#notification').hide('slow');
			}, parseInt((params['showAfter'] * 1000)+2000));
		}
	} else if (params['autoClose'] && params['duration'] > 0) { 
			$('div#notification').html(container).show('slow');
			$('html, body').animate({ scrollTop: 0 }, 'slow');

			setTimeout(function(){
				$('div#notification').hide('slow');
			}, parseInt(params['duration'] * 1000));
	} else {		
		$('div#notification').html(container).slideDown('slow');
		$('html, body').animate({ scrollTop: 0 }, 'slow');
	}
//	alert(params['type']);
}

function removeNotification(el) {
	$(el).parent().remove();
}

function system_logout() {
	window.location.href = '<?php echo HTTP_SERVER; ?>index.php?route=login&act=out';
}
function system_session_restore() {
	sendRequest('ajax/system-ajax.php', {'act':'restore_session', 'uid':'<?php echo get_uid(); ?>','sid':'<?php echo get_token(); ?>'}, 'div#ajax_box');
} 

/*New notificateion function end*/


		
		// save  function 
		function update_settings(act) {
			var f = document.fEdit;
			f.action = act;
			safeSubmit(f);
//			f.submit();
		}
		function delete_settings(url) {
			if (confirm ('Изтриване на запис!')) {
				window.location.href = url;
			}
		}
		
		function check_user_form(act) {
			clear_error_log();
			if ($('select#role_id option:selected').val() == '') {
				msg_add('Не е избрана роля на потребителя!', MSG_ERROR);
			} else if ($('input#login').val() == '') {
				msg_add('Не е въведено потребителско име!', MSG_ERROR);
			} else if (($('input#pass').val() != '') && ($('input#pass').val() != $('input#re_pass').val()) ) {
				msg_add('Паролите не съвпадат!', MSG_ERROR);
			} else {
				update_settings(act);
			}
//			update_settings(act);
		}
		
		
		function validate_partner(act) {
			clear_error_log();
			
			if ($('input#PName').val() == '') {
				msg_add('Не е въведено  име на клиента!', MSG_ERROR);
			} else if ($('select#ptype option:selected').val() == '') {
				msg_add('Не е избрана вид на клиента!', MSG_ERROR);
			} else if ($('input#partner_email').val() != '') {
				if (!validate($('input#partner_email').val())) {
					msg_add('Невалиден имейл!', MSG_ERROR);
				} else {
					update_settings(act);
				}
			} else {
				update_settings(act);
			}
		}

		function validate_supplier(act) {
			clear_error_log();
			
			if ($('input#CName').val() == '') {
				msg_add('Не е въведено  име на клиента!', MSG_ERROR);
			} else if ($('input#supplier_email').val() != '') {
				if (!validate($('input#supplier_email').val())) {
					msg_add('Невалиден имейл!', MSG_ERROR);
				} else {
					update_settings(act);
				}
			} else {
				update_settings(act);
			}
		}
		
		function validate_docs(act, validateAll) {
		  if (typeof validateAll == 'undefined') {
        validateAll = false;
      }
      validateAll = parseInt(validateAll);
      if (validateAll) {
          $('input[type="text"], select, textarea').removeClass('ierr');
          $('input[type="text"], select, textarea').each(
              function(index){  
                  var input = $(this);
                  if (input.val()== '') {
                    input.addClass('ierr');
                  }
              }
          );  
          
          if($('input[type="text"], select, textarea').hasClass('ierr')) {
            if(!confirm('Не сте попълнили всички полета, желаете ли да продължите?')) {
              return false;
            }
          }      
      }
			update_settings(act);
		}
		function validate_case(act) {
			clear_error_log();

			if (!$('input#type_P').is(':checked') && !$('input#type_C').is(':checked')) {
				msg_add('Не сте посочили <b>Тип</b> на записа!', MSG_ERROR);
			} else if ($('input#case_sym').val() == '') {
				msg_add('Не сте посочили <b>Стойност</b> на записа!', MSG_ERROR);
			} else {
				update_settings(act);
			}
		}		
		
		function validate_product(act) {
			clear_error_log();
			if ($('input#product_name').val() == '') {
				msg_add('Не сте въвели <b>Наименование</b> на записа!', MSG_ERROR);
			} else if ($('input#product_quantity').val() == '') {
				msg_add('Не сте въвели <b>Количество</b> на записа!', MSG_ERROR);
			} else {
				update_settings(act);
			}
    }
    
			function validate(email_addr) {
			 
			   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
			   var address = email_addr;
			   if(reg.test(address) == false) {
			      return false;
			   } else {
				 	return true;
				 }
			}

	function pflt_clear_filter(flt) {
		if (flt == 'documents') {
			var url = '<?php echo HTTP_SERVER; ?>index.php?route=documents&tab=all&token=<?php echo get_token();?>';
		} else if (flt == 'contractors') {
			var url = '<?php echo HTTP_SERVER; ?>index.php?route=contractors&tab=list&token=<?php echo get_token();?>';
		} else if (flt == 'payoffice') {
			var url = '<?php echo HTTP_SERVER; ?>index.php?route=payoffice&tab=list&token=<?php echo get_token();?>';
		} else if (flt == 'payment') {
			var url = '<?php echo HTTP_SERVER; ?>index.php?route=payoffice&tab=payment&token=<?php echo get_token();?>';
		} else if (flt == 'patners') {
			var url = '<?php echo HTTP_SERVER; ?>index.php?route=patners&tab=list&token=<?php echo get_token();?>';
		} else if (flt == 'activities') {
			var url = '<?php echo HTTP_SERVER; ?>index.php?route=activities&tab=<?php echo $page_tab; ?>&token=<?php echo get_token();?>';
		} else {
			var url = '<?php echo HTTP_SERVER; ?>index.php?route=dashboard&tab=home&token=<?php echo get_token();?>';
		}
		window.location.href= url;
	}				

	function pflt_case_filter() {
		var url = '<?php echo HTTP_SERVER; ?>index.php?route=payoffice&tab=list&token=<?php echo get_token();?>';
		var filter_case_from_date = $('input[name=\'filter_case_from_date\']').attr('value');
		if (filter_case_from_date) {
			url += '&filter_case_from_date=' + encodeURIComponent(filter_case_from_date);
		}		
		
		var filter_case_to_date = $('input[name=\'filter_case_to_date\']').attr('value');
		if (filter_case_to_date) {
			url += '&filter_case_to_date=' + encodeURIComponent(filter_case_to_date);
		}		
		var filter_case_from_num = $('input[name=\'filter_case_from_num\']').attr('value');
		if (filter_case_from_num) {
			url += '&filter_case_from_num=' + encodeURIComponent(filter_case_from_num);
		}		
		var filter_case_to_num = $('input[name=\'filter_case_to_num\']').attr('value');
		if (filter_case_to_num) {
			url += '&filter_case_to_num=' + encodeURIComponent(filter_case_to_num);
		}		

		var filter_case_type = $('select[name=\'filter_case_type\'] option:selected').val();
		if (filter_case_type != '*') {
			url += '&filter_case_type=' + encodeURIComponent(filter_case_type);
		}

		window.location.href= url;		
	}

	function pflt_partner_filter() {
		var url = '<?php echo HTTP_SERVER; ?>index.php?route=patners&tab=list&token=<?php echo get_token();?>';
		var filter_partner_name = $('input[name=\'filter_partner_name\']').attr('value');
		if (filter_partner_name) {
			url += '&filter_partner_name=' + encodeURIComponent(filter_partner_name);
		}		
		var filter_partner_type = $('select[name=\'filter_partner_type\'] option:selected').val();
		if (filter_partner_type != '*') {
			url += '&filter_partner_type=' + encodeURIComponent(filter_partner_type);
		}		

		window.location.href= url;		
	}

	function pflt_supplier_filter() {
		var url = '<?php echo HTTP_SERVER; ?>index.php?route=contractors&tab=list&token=<?php echo get_token();?>';
		var filter_supplier_sector = $('input[name=\'filter_supplier_sector\']').attr('value');
		if (filter_supplier_sector) {
			url += '&filter_supplier_sector=' + encodeURIComponent(filter_supplier_sector);
		}		
		var filter_supplier_name = $('input[name=\'filter_supplier_name\']').attr('value');
		if (filter_supplier_name) {
			url += '&filter_supplier_name=' + encodeURIComponent(filter_supplier_name);
		}		

		window.location.href= url;		
	}

	function pflt_doc_filter() {
		var url = '<?php echo HTTP_SERVER; ?>index.php?route=documents&tab=all&token=<?php echo get_token();?>';
		var filter_doc_num = $('input[name=\'filter_doc_num\']').attr('value');
		if (filter_doc_num) {
			url += '&filter_doc_num=' + encodeURIComponent(filter_doc_num);
		}		

		var filter_doc_type = $('select[name=\'filter_doc_type\'] option:selected').val();
		if (filter_doc_type != '*') {
			url += '&filter_doc_type=' + encodeURIComponent(filter_doc_type);
		}		

		var filter_doc_status = $('select[name=\'filter_doc_status\'] option:selected').val();
		if (filter_doc_status != '*') {
			url += '&filter_doc_status=' + encodeURIComponent(filter_doc_status);
		}
		var filter_doc_user = $('select[name=\'filter_doc_user\'] option:selected').val();
		if (filter_doc_user != '*') {
			url += '&filter_doc_user=' + encodeURIComponent(filter_doc_user);
		}
		var filter_doc_partner_id = $('select[name=\'filter_doc_partner_id\'] option:selected').val();
		if (filter_doc_partner_id != '*') {
			url += '&filter_doc_partner_id=' + encodeURIComponent(filter_doc_partner_id);
		}
		window.location.href= url;		
	}

	function pflt_activities_filter() {
		var url = '<?php echo HTTP_SERVER; ?>index.php?route=activities&tab=<?php echo $page_tab; ?>&token=<?php echo get_token();?>';
    var filter_doc_num = $('input[name=\'filter_doc_num\']').attr('value');
		if (filter_doc_num) {
			url += '&filter_doc_num=' + encodeURIComponent(filter_doc_num);
		}		

    var filter_order_by = $('input[name=\'filter_order_by\']').attr('value');
		if (filter_order_by) {
			url += '&filter_order_by=' + encodeURIComponent(filter_order_by);
		}		

		var filter_card_status = $('select[name=\'filter_card_status\'] option:selected').val();
		if (filter_card_status != '*') {
			url += '&filter_card_status=' + encodeURIComponent(filter_card_status);
		}		

		var filter_activity_date = $('select[name=\'filter_activity_date\'] option:selected').val();
		if (filter_activity_date != '*') {
			url += '&filter_activity_date=' + encodeURIComponent(filter_activity_date);
		}	
    	
		var filter_uid = $('select[name=\'filter_uid\'] option:selected').val();
		if (filter_uid != '*') {
			url += '&filter_uid=' + encodeURIComponent(filter_uid);
		}		
		var filter_activity_date = $('select[name=\'filter_activity_date\'] option:selected').val();
		if (filter_activity_date != '*') {
			url += '&filter_activity_date=' + encodeURIComponent(filter_activity_date);
		}	
		var filter_activity_period = $('select[name=\'filter_activity_period\'] option:selected').val();
		if (filter_activity_period != '*') {
			url += '&filter_activity_period=' + encodeURIComponent(filter_activity_period);
		}	
    	
		var filter_uid = $('select[name=\'filter_uid\'] option:selected').val();
		if (filter_uid != '*') {
			url += '&filter_uid=' + encodeURIComponent(filter_uid);
		}		

		var filter_supplier_id = $('select[name=\'filter_supplier_id\'] option:selected').val();
		if (filter_supplier_id != '*') {
			url += '&filter_supplier_id=' + encodeURIComponent(filter_supplier_id);
		}		

		window.location.href= url;		
	}
	
	function pflt_activities_filter_new() {
		var url = '<?php echo HTTP_SERVER; ?>index.php?route=activities&tab=<?php echo $page_tab; ?>&view=new&token=<?php echo get_token();?>';
    var filter_doc_num = $('input[name=\'filter_doc_num\']').attr('value');
		if (filter_doc_num) {
			url += '&filter_doc_num=' + encodeURIComponent(filter_doc_num);
		}		

    var filter_order_by = $('input[name=\'filter_order_by\']').attr('value');
		if (filter_order_by) {
			url += '&filter_order_by=' + encodeURIComponent(filter_order_by);
		}		

		var filter_card_status = $('select[name=\'filter_card_status\'] option:selected').val();
		if (filter_card_status != '*') {
			url += '&filter_card_status=' + encodeURIComponent(filter_card_status);
		}		

		var filter_activity_date = $('select[name=\'filter_activity_date\'] option:selected').val();
		if (filter_activity_date != '*') {
			url += '&filter_activity_date=' + encodeURIComponent(filter_activity_date);
		}	
		var filter_activity_period = $('select[name=\'filter_activity_period\'] option:selected').val();
		if (filter_activity_period != '*') {
			url += '&filter_activity_period=' + encodeURIComponent(filter_activity_period);
		}	
    	
		var filter_uid = $('select[name=\'filter_uid\'] option:selected').val();
		if (filter_uid != '*') {
			url += '&filter_uid=' + encodeURIComponent(filter_uid);
		}		

		var filter_supplier_id = $('select[name=\'filter_supplier_id\'] option:selected').val();
		if (filter_supplier_id != '*') {
			url += '&filter_supplier_id=' + encodeURIComponent(filter_supplier_id);
		}		

    window.location.href= url;		
	}
        
	function pflt_case_payment_filter() {
		var url = '<?php echo HTTP_SERVER; ?>index.php?route=payoffice&tab=payment&token=<?php echo get_token();?>';

		var filter_case_partner_id = $('select[name=\'filter_case_partner_id\'] option:selected').val();
		if (filter_case_partner_id != '*') {
			url += '&filter_case_partner_id=' + encodeURIComponent(filter_case_partner_id);
		}
		var filter_case_payment_type = $('select[name=\'filter_case_payment_type\'] option:selected').val();
		if (filter_case_payment_type != '*') {
			url += '&filter_case_payment_type=' + encodeURIComponent(filter_case_payment_type);
		}
		var filter_case_user_id = $('select[name=\'filter_case_user_id\'] option:selected').val();
		if (filter_case_user_id != '*') {
			url += '&filter_case_user_id=' + encodeURIComponent(filter_case_user_id);
		}
		var filter_case_type = $('select[name=\'filter_case_type\'] option:selected').val();
		if (filter_case_type != '*') {
			url += '&filter_case_type=' + encodeURIComponent(filter_case_type);
		}

		var filter_case_doc_num = $('input[name=\'filter_case_doc_num\']').attr('value');
		if (filter_case_doc_num) {
			url += '&filter_case_doc_num=' + encodeURIComponent(filter_case_doc_num);
		}		
		var filter_case_from_date = $('input[name=\'filter_case_from_date\']').attr('value');
		if (filter_case_from_date) {
			url += '&filter_case_from_date=' + encodeURIComponent(filter_case_from_date);
		}		
		var filter_case_date_of_payment = $('input[name=\'filter_case_date_of_payment\']').attr('value');
		if (filter_case_date_of_payment) {
			url += '&filter_case_date_of_payment=' + encodeURIComponent(filter_case_date_of_payment);
		}		
		var filter_case_from_date = $('input[name=\'filter_case_from_date\']').attr('value');
		if (filter_case_from_date) {
			url += '&filter_case_from_date=' + encodeURIComponent(filter_case_from_date);
		}		
		var filter_case_to_date = $('input[name=\'filter_case_to_date\']').attr('value');
		if (filter_case_to_date) {
			url += '&filter_case_to_date=' + encodeURIComponent(filter_case_to_date);
		}		

		window.location.href= url;		
	}
	
$(document).ready(function(){
/*
			var session_maxlifetime = parseInt('<?php echo $session_maxlifetime; ?>');//1440/60
			$('#sessionCountdown').countdown({until: '+0h +'+session_maxlifetime+'m +00s',compact: true,   
			format: 'HMS', description: '', onExpiry: function() {
					showNotification({
					message: 'Потребителската Ви сесия е изтекла! <br><div class="clear"></div><div class="session_tm_box"><div class="log_out" onclick="system_logout();"></div><div class="ses_restore" onclick="system_session_restore();"></div></div><div class="clear"></div>',
					type: 'error'
					});			
				}
			}); 
*/
function pollServer() {
			$.ajax({
				type : 'GET',
				url : 'http://avdesigngroup.org/crm_avd/ajax/polling.php',
				dataType : 'json',
				cache: false,
				data: {},
			  beforeSend: function( xhr ) {
			  }, 		
				success : function(data){
				console.log('Poll server')
         setTimeout(function() {
            pollServer();
         },1000);

				},
				error : function(XMLHttpRequest, textStatus, errorThrown) {
				}
			});   
}

// Call the function on load
//pollServer();
});	
  </script>
  </head>
  <body onload="document.body.loaded = document.getElementById('__eloaded__');">
	<div id="notification">
	</div>
	<div class="session_box">
		<div class="title"> </div>
		<div id="sessionCountdown"></div>
	</div>
<?php if(isset($_SESSION["WORK_TIME_DURATION"])) {?>
	<div id="timetraker"><span>Раб. Време:</span> <?php echo getWorkTime(); ?>
	</div>
<?php } ?>
  	<?php
  	/*
		$route = _g("route", "dashboard");
		$page_tab = _g("tab", "home");
		*/
  	?>
		<div id="main">
			<div id="header_wrapp">
				<div class="header">
					<div class="company-logo">
						<img src="<?php echo TEMPLATE; ?>images/white_points copy.png" border=0>
					</div>
					<div class="left top_menu">
						<ul>
							<li><a href="http://avdesigngroup.org/" target="_blank">AV Desing Group</a></li>
							<li><a href="http://www.avprint.org/" target="_blank">AV Print</a></li>
							<li><a href="http://www.cd-print.net/" target="_blank">CD Print</a></li>
							<li><a href="http://www.brandusb.eu/" target="_blank">Brand USB</a></li>
						</ul>
					</div>
					<div class="left login">
						<ul>
							<li><a href="#">(<?php echo get_user();?>)</a></li>
							<li><a href="<?php echo HTTP_SERVER."index.php?route=login&act=out";?>">Изход</a></li>
						</ul>
					</div>
					<div class="clear"></div>
					<div class="search_box right">
						<form name="" method="post">
							<input type="text" id="input_search" name="search" value="Търси.... " autocomplete="off">
							<div id="search_list">
								<div id="ajax_re"></div>
							</div>
						</form>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div id="sub_header"></div>
			<div id="content">
				<div id="tabs">
					<ul>
						<li<?php if ($route == "dashboard") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=dashboard&tab=home&token=".get_token();?>">Табло</a></li>
						<li<?php if ($route == "documents") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=documents&tab=all&token=".get_token();?>">Документи</a></li>
						<li<?php if ($route == "reports") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=reports&tab=patners&token=".get_token();?>">Справки</a></li>
						<li<?php if ($route == "patners") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=patners&tab=list&token=".get_token();?>">Клиенти</a></li>
						<li<?php if ($route == "contractors") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=contractors&tab=list&token=".get_token();?>">Контрагенти</a></li>
						<li<?php if ($route == "activities") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=activities&tab=all&token=".get_token();?>">Дейности</a></li>
						<li<?php if ($route == "payoffice") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=payoffice&tab=list&token=".get_token();?>">Каса</a></li>
						<li<?php if ($route == "settings") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=settings&tab=users&token=".get_token();?>">Настройки</a></li>
					</ul>
				</div>
				<div id="sub_tabs">
					<?php if ( $route == "documents" ) { ?>
					<ul>
						<li<?php if ($page_tab == "all") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=documents&tab=all&token=".get_token().get_filter();?>">Всички</a></li>
						<li<?php if ($page_tab == "new_doc" || $page_tab == "edit_doc") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=documents&tab=new_doc&token=".get_token();?>"><?php echo ($page_tab == "edit_doc"? "Редактиране":"Нов документ"); ?></a></li>
						<?php if ($page_tab == "send_email") { ?>
						<li<?php if ($page_tab == "send_email") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=documents&tab=send_email&token=".get_token().get_filter();?>">Изпрати писмо</a></li>
						<?php } ?>
						<li<?php if ($page_tab == "not_payed") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=documents&tab=not_payed&token=".get_token().get_filter();?>">Неплатени</a></li>
						
<!--
						<li<?php if ($page_tab == "edit_doc") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=documents&tab=edit_doc&token=".get_token();?>">Редакция</a></li>
-->						
						
					</ul>
					<?php } else if ( $route == "reports" ) { ?>
					<ul>
						<li<?php if ($page_tab == "patners") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=reports&tab=patners&token=".get_token();?>">Клиенти</a></li>
						<li<?php if ($page_tab == "users") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=reports&tab=users&token=".get_token();?>">Потребители</a></li>
						<li<?php if ($page_tab == "suppliers") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=reports&tab=suppliers&token=".get_token();?>">Контрагенти</a></li>
						<li<?php if ($page_tab == "category") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=reports&tab=category&token=".get_token();?>">Категории</a></li>
						<li<?php if ($page_tab == "timetracker") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=reports&tab=timetracker&token=".get_token();?>">Работно време</a></li>
						<li<?php if ($page_tab == "not_payed") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=reports&tab=not_payed&token=".get_token();?>">Плащания</a></li>
					</ul>
					<?php } else if ( $route == "patners" ) { ?>
					<ul>
						<li<?php if ($page_tab == "list") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=patners&tab=list&token=".get_token();?>">Списък</a></li>
						<li<?php if ($page_tab == "new_partner" || $page_tab == "edit_partner") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=patners&tab=".($page_tab == "edit_partner"?"edit_partner":"new_partner")."&token=".get_token();?>"><?php echo ($page_tab == "edit_partner"? "Редактиране":"Нов"); ?></a></li>
<!--
						<li<?php if ($page_tab == "edit_partner") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=patners&tab=edit_partner&token=".get_token();?>">Редактиране</a></li>
-->						
					</ul>
					<?php } else if ( $route == "contractors" ) { ?>

					<ul>
						<li<?php if ($page_tab == "list") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=contractors&tab=list&token=".get_token().get_filter();?>">Списък</a></li>
						<li<?php if ($page_tab == "new_contractor"  || $page_tab == "edit_contractor") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=contractors&tab=new_contractor&token=".get_token();?>"><?php echo ($page_tab == "edit_contractor"? "Редактиране":"Нов"); ?></a></li>
						<li<?php if ($page_tab == "printing") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=contractors&tab=printing&token=".get_token();?>">Печатници</a></li>
					</ul>
					
					<?php } else if ( $route == "activities" ) { ?>
					<ul>
						<li<?php if ($page_tab == "all") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=activities&tab=all&token=".get_token();?>">Всички</a></li>
						<li<?php if ($page_tab == "owner") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=activities&tab=owner&token=".get_token();?>">Мои</a></li>
				<?php if ($page_tab == "edit") { ?>
						<li<?php if ($page_tab == "edit") echo " class=\"selected\""; ?> ><a href="#">Редактиране</a></li>
				<?php } ?>				
					</ul>
					<?php } else if ( $route == "payoffice" ) { ?>
					<ul>
						<li<?php if ($page_tab == "new_paycase"  || $page_tab == "edit_paycase") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=payoffice&tab=".($page_tab == "edit_paycase"? "edit_paycase":"new_paycase")."&token=".get_token();?>"><?php echo ($page_tab == "edit_paycase"? "Редактиране":"Нов"); ?></a></li>
						<li<?php if ($page_tab == "list") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=payoffice&tab=list&token=".get_token().get_filter();?>">Каса</a></li>
						<li<?php if ($page_tab == "payment") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=payoffice&tab=payment&token=".get_token().get_filter();?>">Разплащане</a></li>
						<li<?php if ($page_tab == "stock") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=payoffice&tab=stock&token=".get_token();?>">Наличности</a></li>
					</ul>
					<?php } else if ( $route == "settings" ) { ?>
					<ul>
						<li<?php if ($page_tab == "users") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=settings&tab=users&token=".get_token();?>">Всички</a></li>
						<li<?php if ($page_tab == "roles") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=settings&tab=roles&token=".get_token();?>">Роли</a></li>
						<li<?php if ($page_tab == "perms") echo " class=\"selected\""; ?> ><a href="<?php echo HTTP_SERVER."index.php?route=settings&tab=perms&token=".get_token();?>">Достъп</a></li>
					</ul>
					<?php } else { ?>
					<ul>
						<li class="selected"><a href="#">Начало</a></li>
					</ul>
					<?php } ?>
				</div>
					<div id="page_sub_title">
						<h1><?php echo(isset($_PAGE_TITLE[$route][$page_tab])?$_PAGE_TITLE[$route][$page_tab]:$_PAGE_TITLE["dashboard"]["home"]);?></h1>
						<?php 
							switch($route) {
								case "documents":
									print '<div class="right"><a href="'.HTTP_SERVER.'index.php?route=documents&tab=new_doc&token='.get_token().'" class="add_new">Нов документ</a></div>';
									break;
								case "settings":
									if ($page_tab == "roles") {
										print '<div class="right"><a href="'.HTTP_SERVER.'index.php?route=settings&tab=roles&token='.get_token().'&act=edit" class="add_new">Нова Роля</a></div>';
									} else if ($page_tab == "perms") {
									;
									}	else {
										print '<div class="right"><a href="'.HTTP_SERVER.'index.php?route=settings&tab=users&token='.get_token().'&act=edit" class="add_new">Нов Потребител</a></div>';
									}
									break;
								case "patners":
									print '<div class="right"><a href="'.HTTP_SERVER.'index.php?route=patners&tab=new_partner&token='.get_token().'" class="add_new">Нов Клиент</a></div>';
									break;
								default:
									break;
							}
						?>
						<!--
						<div class="right"><a href="#" class="add_new">Нов документ</a></div>
						-->
					</div>
				<!-- msg errors begin -->
				
					<div id="msg_wrapp">
					<?php 
					/*printArray($messages);*/
					?>
					<!--
						<div class="msg_box">
							<div class="close_msg" onclick="hide_msg(this);">close</div>
							<div class="success">Успех</div>
						</div>
						<div class="msg_box">
							<div class="close_msg" onclick="hide_msg(this);">close</div>
							<div class="info">Информация</div>
						</div>
						<div class="msg_box">
							<div class="close_msg" onclick="hide_msg(this);">close</div>
							<div class="warning">Внимание</div>
						</div>
						<div class="msg_box">
							<div class="close_msg" onclick="hide_msg(this);">close</div>
							<div class="error">Грешка</div>
						</div>
						-->
					</div>
					<div id="ajax_box"></div>
				<!-- msg errors end -->
				<!-- page form begin-->
				<div id="page_content">
				