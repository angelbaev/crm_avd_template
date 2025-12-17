<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1251">
  <script src="<?php echo TEMPLATE; ?>js/jquery-1.6.1.min.js"  type="text/javascript"></script>
	<link type="text/css" rel="Stylesheet" href="<?php echo TEMPLATE; ?>css/reset.css" media="screen" />
	<link type="text/css" rel="Stylesheet" href="<?php echo TEMPLATE; ?>css/style.css" media="screen" />
  <title>Вход към системата</title>
  <script type="text/javascript">
     	var MSG_ERROR = 'error';
    	var MSG_INFO = 'info';
    	var MSG_SUCCESS = 'success';
    	var MSG_WARNING = 'warning';

 	$(document).ready(function(){
     $('form').each(function() {
        $(this).find('input').keypress(function(e) {
            // Enter pressed?
            if(e.which == 10 || e.which == 13) {
                this.form.submit();
            }
        });
    }); 	
  	});

			function msg_add(msg, type) {
				var set_class = MSG_ERROR;
				switch(type) {
					case '3':
						set_class = MSG_INFO;
						break;
					case '4':
						set_class = MSG_SUCCESS;		
						break;
					case '2':
						set_class = MSG_WARNING;		
						break;
					default:
						set_class = MSG_ERROR;		
						break;
					}
				var msg_box = 
						'<div class="msg_box">'+
							'<div class="close_msg" onclick="hide_msg(this);">close</div>'+
							'<div class="'+set_class+'">'+msg+'</div>'+
						'</div>';
				/*
				'<div class="msg_dialog '+set_class+'">'+
										'<div class="close_msg" onclick="hide_msg(this);">close</div>'+
										'<div class="msg_content">'+msg+'</div>'+
										'</div>';	
										*/			
				$('div#msg_wrapp').append(msg_box);
	
			
			}
			
			function clear_error_log() {
				$('div#msg_wrapp').html('');
			}

		function hide_msg(div) {
			$(div).parent().fadeOut();
		}
		
  </script>
  </head>
  <body>
		<div id="main">
			<div id="header_wrapp">
				<div class="header">
					<div class="left">
						<a href="#">
						<!--
						<img src="<?php echo HTTP_SERVER.TEMPLATE; ?>images/logo.jpg" border=0 alt="System logo">
						-->
						</a>
					</div>
					<div class="left company_text">
						<!--<h1>АВ Дизайн Груп</h1>-->
						<img src="<?php echo HTTP_SERVER.TEMPLATE; ?>images/white_points copy.png" style="margin-top:14px;" border=0 alt="System logo">
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div id="content" >
				<!-- msg errors begin -->
				
					<div id="msg_wrapp">
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
				<!-- msg errors end -->
				<!-- page form begin-->
				<div id="page_content" style="border: 0px solid;">
						<div class="login_heading">
							<h1> Вход в avdesigngroup.org </h1>
						</div>
					<div class="login_wrapp">
						<div class="login_logo">
							<img src="<?php echo HTTP_SERVER.TEMPLATE; ?>images/logo.jpg" border=0>
						</div>
						<form name="fLogin" method="post" action="">
						<input type="hidden" name="act" value="in">
						<table width="100%" class="form" border="0" cellspacing="0" cellpadding="2">
								<colgroup>
									<col width="120">
									<col  width="180">
									<col>
								</colgroup>
	      				<tr>
	      					<td class="label">Потребител: </td>
	      					<td><input type="text" name="name" class="ie180" value=""></td>
	      				</tr>
	      				<tr>
	      					<td class="label">Парола: </td>
	      					<td><input type="password" name="password" class="ie180" value=""></td>
	      				</tr>
	      				<tr>
	      					<td>&nbsp;</td>
	      					<td align="right" style="padding-right:20px;"><input type="button" name="button_save" value="Вход" class="submit_button" onclick="document.fLogin.submit();"></td>
	      				</tr>
						</table>							
						</form>
					</div>
				
				</div>
				<?php 
					if (count($messages)) {
						displayMessages($messages);
					}
				?>
				<!-- page form end-->
			</div>
		</div>
  </body>
</html>
