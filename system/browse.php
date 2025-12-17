<?php 
	include("../config.php");
	include_once(DIR_SYSTEM."function.inc.php");
	
	$act = _g("act", "");
	
	if ($act == "del") {
		@unlink(DIR_IMAGE.$_GET["fn"]);
	}

	if (isset($_POST["submit"])){
    if(isset($_FILES['upload_image'])){
			$filen = $_FILES['upload_image']['tmp_name'];
      $con_images = DIR_IMAGE."".$_FILES['upload_image']['name'];
      if (move_uploaded_file($filen, $con_images )) {
				print "<b>Файла ".$_FILES['upload_image']['name']." е качен успешно!</b><br>";
			} else {
				print "<b>Грешка при прекачване на файла: ".$_FILES['upload_image']['name']." </b><br>";
			}
		}
	} else {
	
	
	$image_id = _g("image_id","");
	$image_dir = DIR_IMAGE;
	$image_files = glob($image_dir."*.{jpg,jpeg,gif,png}", GLOB_BRACE);
	$files = array();	
	foreach($image_files as $key => $item) {
		$files[] = str_replace($image_dir, "", $item);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1251">
	<script src="<?php echo HTTP_SERVER.TEMPLATE; ?>js/jquery-1.6.1.min.js" type="text/javascript"></script>
	<script src="<?php echo HTTP_SERVER.TEMPLATE; ?>js/jquery.form.js" type="text/javascript"></script>
	<script type="text/javascript">
	$(document).ready(function(){
	  $('img#upload_box').click(function(){
	    if ($('div#upload_wrapp').is(':visible')) {
				window.location.reload();
				$('div#upload_wrapp').fadeOut('slow');
				//window.location.href
			} else {
				$('div#upload_wrapp').fadeIn('slow');
			}
	  });
	  
	  $('img.img_calc').mouseover(function(){
			$(this).css({'border':'1px red solid'});
			$(this).parent().children('div.i_heading').show();
			//$(this).after('<div class="icon_delete">'+$(this).attr('rel')+'</div>');
			
		
		}).mouseout(function(){
			$(this).css({'border':'1px #000 solid'});
			$(this).parent().children('div.i_heading').hide();
			//$('.icon_delete').remove();
		});
	});
	
	function delete_image(name) {
		window.location.href= '<?php echo HTTP_SERVER."system/browse.php?image_id=".$image_id."&act=del&fn=";?>'+name+'';
	}
	</script>	
  <title>Browse Images</title>
  <style type="text/css">
		BODY {
			margin: 0 0 0 0;
			padding: 0 0 0 0;
			background-color: #E8E6E6;
		}
		div#header {
			background-color: #000;
			padding: 6px 0px 6px 10px;
			margin-bottom: 0px;
			background: -webkit-gradient(linear, left top, left bottom, from(#666666), to(#000000));
			background: -moz-linear-gradient(top,  #666666,  #000000);
			filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#666666', endColorstr='#000000');
			
		}
		div#header h1{
			margin: 0 0 0 0;
			padding: 0 0 0 0;
			font-size: 14px;
			color: #fff;
		}
		div.content {
			/*padding-left: 60px;*/
		}
		div.content img {
			margin: 1px 3px 4px 0px; 
			border: 1px #000 solid;
			cursor: pointer;
		}
		img#upload_box {
			margin: 0 0 0 0;
			cursor: pointer;
			float: right;
			margin-right: 12px;
			border: 0px;
			margin-top: 5px;
		}
		div#upload_wrapp{
			background-color: #DFEFFC;
			border: 5px #C5DBEC solid;
			padding: 12px 30px 12px 30px;
			margin-bottom: 25px;
			display: none;
		}
		div#upload_form {
			text-align: right;
			/*display: none;*/
			background-color: #DFEFFC;
		}
		input.submit {
			/*
			http://blueimp.github.com/cdn/css/bootstrap.min.css
			http://blueimp.github.com/jQuery-File-Upload/
			*/
			color: #fff;
			font-weight: bold;
			cursor: pointer;
			padding: 4px 10px 4px 10px;
			background-color: #5BB75B;
			background: -webkit-gradient(linear, left top, left bottom, from(#62C462), to(#51A351));
			background: -moz-linear-gradient(top,  #62C462,  #51A351);
			filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#62C462', endColorstr='#51A351');
			border:1px green solid;
			-moz-border-radius: 4px;
			-webkit-border-radius: 4px;
			border-radius: 4px;
			
			/*border-color: rgba(0, 0, 0, 0.25) rgba(0, 0, 0, 0.25) rgba(0, 0, 0, 0.25) rgba(0, 0, 0, 0.1);*/
		}
		div#image_wrapp {
			margin-top: 25px;
			margin-left: 50px;
			margin-right: 50px;
		}
		
.progress { position:relative; width:400px; border: 1px solid #C5DBEC; padding: 1px; border-radius: 3px; }
.bar { background-color: #B4F5B4; width:0%; height:20px; border-radius: 3px; }
.percent { position:absolute; display:inline-block; top:3px; left:48%; }

	.icon_delete {
		position: absolute;
		border: 1px green solid;
		z-index: 20;
	}	
	.i_wrapp {
		width: 122px;
		height: 80px;
		float: left;
		margin-right: 3px;
		margin-bottom: 5px;
	}	
	.i_wrapp div.i_heading{
		display: none;
	}
	
	.i_wrapp div.i_heading img {
		margin: 0 0 0 0;
		padding: 0 0 0 0;
		border: 0px;
		margin-left: 105px;
		position: absolute;
	}
  </style>
  </head>
  <body>
		<div id="header">
			<h1><?php echo HTTP_IMAGE; ?></h1>
		</div>
		<div class="content">
		<div id="upload_wrapp">
			<div id="upload_form">
<form enctype="multipart/form-data" action="browse.php?act=upload&image_id=<?php echo $image_id; ?>" name="fUpload" id="fUpload" method="post">

			
				<table width="410" border="0" cellspacing="0" cellpadding="1" align="right">
     			<tr>
     				<td>Файл:</td>
     				<td><input type="file" name="upload_image" id="upload_image" size="45"></td>
     			</tr>
     			<tr>
     				<td>&nbsp;</td>
     				<td><input type="submit" class="submit" name="submit" value="Качи файл на сървъра"></td>
     			</tr>
     			<tr>
     				<td colspan=2>&nbsp;</td>
     			</tr>
     			<tr>
     				<td colspan=2>
							<div class="progress">
							        <div class="bar"></div >
							        <div class="percent">0%</div >
							</div>    		
						</td>
     			</tr>
     			<tr>
     				<td colspan=2>&nbsp;<span id="status"></span></td>
     			</tr>
    		</table>
</form>
	<script type="text/javascript">
	(function() {
		    
		var bar = $('.bar');
		var percent = $('.percent');
		var status = $('#status');
		   
		$('form#fUpload').ajaxForm({
		    beforeSend: function() {
		        status.empty();
		        var percentVal = '0%';
		        bar.width(percentVal)
		        percent.html(percentVal);
		    },
		    uploadProgress: function(event, position, total, percentComplete) {
		        var percentVal = percentComplete + '%';
		        bar.width(percentVal)
		        percent.html(percentVal);
		    },
			complete: function(xhr) {
		        var percentVal = 100 + '%';
		        bar.width(percentVal)
		        percent.html(percentVal);
				status.html(xhr.responseText);
				$('#upload_image').val('');
			}
		}); 
		
	})();       
	</script>	

			</div>
			<div style="clear: both;"></div>
		</div>
			<img id="upload_box" src="http://cdn.androidpolice.com/wp-content/uploads/2010/10/uploadorg_thumb.gif" border="0">

			<div id="image_wrapp">
<?php
	$html = "";
	foreach($files as $key => $item) {
		//$img = getimagesize($image_dir.$item);
		/*
		if ($img[1] > $img[0]){
			$width = 120;
			$height = 80;
		} else {
			$width = 80;
			$height = 120;
		}
		*/
			$width = 120;
			$height = 80;
		$html .= "
		<div class=\"i_wrapp\">
		<div class=\"i_heading\"><img src=\"http://cdn2.iconfinder.com/data/icons/ecqlipse2/TRASH%20-%20EMPTY.png\" border=0 width=16 height=16 onclick=\"delete_image('".$item."');\"></div>
		<img class=\"img_calc\" src=\"".HTTP_IMAGE."".$item."\" width=\"".$width."\" height=\"".$height."\" border=0 rel=\"".$item."\" onclick=\"geImage('".HTTP_IMAGE."".$item."');\">
		</div>
		";
	}
	print $html;

?>		
		</div>
		</div>
<script type='text/javascript'>
function geImage(url) {
	parent.document.getElementById('print_image_<?php echo $image_id; ?>').src = url;
	parent.document.getElementById('c_export_<?php echo $image_id; ?>').value = parent.document.getElementById('c_export_view_<?php echo $image_id; ?>').innerHTML;
//http://avdesigngroup.org/crm_avd/images/logo.jpg
}
</script>		
  </body>
</html>
<?php } ?>