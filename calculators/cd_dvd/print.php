<?php 
	include("../../config.php");
	include_once(DIR_SYSTEM."function.inc.php");
	
	foreach($_GET as $key => $val) {
		$_GET[$key] = windows_1251($val);
	}	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1251">
  <meta name="generator" content="PSPad editor, www.pspad.com">
  <title></title>
    <script src="js/jquery-1.6.1.min.js"  type="text/javascript"></script>
  
  <style>
@media screen{
	body{
		margin: 0 0 0 0;
		padding: 0 0 0 0;
		font-family: Verdana, Tahoma, Helvetica, Arial;
		font-size: 8px;
	}
	p, ul{
		font-family: Verdana, Tahoma, Helvetica, Arial;
		font-size: 12px;
	}
	div#nav_bar{
		background-color: #eee;
		height: 40px;
		border-bottom: 2px #ccc solid;
		margin-bottom: 10px;
	}
	#nav_btns {
		float: right;
		list-style: none;
		margin: 0 0 0 0;
		padding: 0 0 0 0;
	}
	#nav_btns li {
	float: right;
	margin: 2px 8px 0px 0px;
	
	
	}
.closePrint{
	position: absolute;
}

#print_div_content{		
	width:99%;/*210mm*/
/*	height: 29cm;*/
	/*border: 1px #666 solid;*/
} 		

}  
@media print
{
      body{ 
		margin: 0 0 0 0;
		padding: 0 0 0 0;
		font-family: Verdana, Tahoma, Helvetica, Arial;
		font-size: 8px;
			}
			div#nav_bar {
				display:none;
			}

	p, ul{
		font-family: Verdana, Tahoma, Helvetica, Arial;
		font-size: 12px;
	}
			
#print_div_content{		
	width:99%;/*210mm*/
	/*height: 29cm;*/
	/*border: 1px #666 solid;*/
} 		
}  
  </style>
	<script language="javascript">
		$(document).ready(function(){
			var url_params = getUrlVars();
	
			if (url_params['polish_flag'] ==  '1') {
				$('.hide_tr').hide();
				$('.hide_tr_polish').show();
			} else {
				$('.hide_tr').show();
				$('.hide_tr_polish').hide();
			}


			$('#print_paper_type').html('&nbsp;'+url_params['type']);
			$('#print_paper_carrier').html('&nbsp;'+url_params['carrier']);
			$('#print_paper_print').html('&nbsp;'+url_params['print_type']);
			$('#print_paper_record').html('&nbsp;'+url_params['record']);
			$('#print_paper_polish').html('&nbsp;'+url_params['polish']);
			$('#print_paper_box').html('&nbsp;'+url_params['box']);
			$('#print_paper_front_cover').html('&nbsp;'+url_params['front_cover']);
			$('#print_paper_back_cover').html('&nbsp;'+url_params['back_cover']);
			$('#print_paper_other_cover').html('&nbsp;'+url_params['other_cover']);
			$('#print_paper_completion').html('&nbsp;'+url_params['completion']);
			$('#print_paper_tselofanirane').html('&nbsp;'+url_params['tselofanirane']);
			$('#print_paper_file').html('&nbsp;'+url_params['file']);
			$('#print_paper_cnt').html('&nbsp;'+url_params['cnt']);
			
			// load price

//print form 
	$('#single_sum_print').html('&nbsp;'+url_params['sum']);
	$('#sum_total_print').html('&nbsp;'+url_params['total']);
	$('#sum_vat_total_print').html('&nbsp;'+url_params['vat']);
			
		
		});
		
		function getUrlVars(){
		    var vars = [], hash;
		    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		    for(var i = 0; i < hashes.length; i++)
		    {
		        hash = hashes[i].split('=');
		        vars.push(hash[0]);
		        vars[hash[0]] = unescape(hash[1]);
		      //  alert('key: '+hash[0]+ ' val: '+unescape(hash[1])+'');
		    }
		    return vars;
		}	
	</script>  
  </head>
  <body>
	<div id="print_div_content">
<table style="border-collapse: collapse;" width="100%" border="1" cellspacing="0" cellpadding="1">
	<colgroup>
		<col width="100">
		<col>
	</colgroup>
 	<tr>
 		<td colspan="2" style="padding: 3px;padding-left: 5px;background-color: #eee;"><strong>CD и DVD </strong></td>
 	</tr>
 	<tr>
 		<td align="center"><div style="width:210px; height:210px;"><!--IMAGE--></div></td>
 		<td valign="top">
			<table style="border-collapse: collapse;" width="100%" border="1" cellspacing="0" cellpadding="0">
				<colgroup>
					<col width="160">
					<col>
				</colgroup>
				<tr>
					<td style="text-align: right;padding-right: 5px;">вид</td>
					<td id="print_paper_type"><?php echo $_GET["type"]; ?></td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;">вид носител</td>
					<td id="print_paper_carrier"><?php echo $_GET["carrier"]; ?></td>
				</tr>
				<?php if ($_GET["polish_flag"] != 1) {?>
				<tr class="hide_tr">
					<td style="text-align: right;padding-right: 5px;">печат</td>
					<td id="print_paper_print"><?php echo $_GET["print_type"]; ?></td>
				</tr>
				<tr class="hide_tr">
					<td style="text-align: right;padding-right: 5px;">запис</td>
					<td id="print_paper_record"><?php echo $_GET["record"]; ?></td>
				</tr>
				<?php } ?>
				<?php if ($_GET["polish_flag"] == 1) {?>
				<tr class="hide_tr_polish">
					<td style="text-align: right;padding-right: 5px;">лак</td>
					<td id="print_paper_polish"><?php echo $_GET["polish"]; ?></td>
				</tr>
				<?php } ?>
				<tr>
					<td style="text-align: right;padding-right: 5px;">кутийка</td>
					<td id="print_paper_box"><?php echo $_GET["box"]; ?></td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;">предна обложка</td>
					<td id="print_paper_front_cover"><?php echo $_GET["front_cover"]; ?></td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;">задна обложка</td>
					<td id="print_paper_back_cover"><?php echo $_GET["back_cover"]; ?></td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;">други опаковки</td>
					<td id="print_paper_other_cover"><?php echo $_GET["other_cover"]; ?></td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;">комплектоване</td>
					<td id="print_paper_completion"><?php echo $_GET["completion"]; ?></td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;">целофаниране</td>
					<td id="print_paper_tselofanirane"><?php echo $_GET["tselofanirane"]; ?></td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;">файл</td>
					<td id="print_paper_file"><?php echo $_GET["file"]; ?></td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;">брой</td>
					<td id="print_paper_cnt"><?php echo $_GET["cnt"]; ?></td>
				</tr>
		 	</table>
		</td>
 	</tr>

 	<tr>
 		<td colspan="2">&nbsp;</td>
 	</tr>
 	<tr>
 		<td colspan="2" style="background-color: #eee;">&nbsp;<strong>ЦЕНИ</strong></td>
 	</tr>
 	<tr>
 		<td colspan="2">
			<table style="border-collapse: collapse;" width="100%" border="1" cellspacing="0" cellpadding="0">
				<colgroup>
					<col>
					<col width="160">
				</colgroup>
				<tr>
					<td style="text-align: right;padding-right: 5px;"><strong>Ед. цена без ДДС</strong></td>
					<td style="text-align: right;padding-right: 5px;" id="single_sum_print">&nbsp;<?php echo $_GET["sum"]; ?></td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;"><strong>ОБЩО БЕЗ ДДС</strong></td>
					<td style="text-align: right;padding-right: 5px;" id="sum_total_print">&nbsp;<?php echo $_GET["total"]; ?></td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;"><strong>ТОТАЛ</strong></td>
					<td style="text-align: right;padding-right: 5px;" id="sum_vat_total_print">&nbsp;<?php echo $_GET["vat"]; ?></td>
				</tr>
		 	</table>
		 
		</td>
 	</tr>
 	<tr>
 		<td colspan="2">&nbsp;</td>
 	</tr>
 	
</table>

	</div>

  </body>
</html>
