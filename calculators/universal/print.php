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
		font-size: 14px;
	}
.bg_total {
	background-color: #000;
	color: #fff;
} 		
	p, ul{
		font-family: Verdana, Tahoma, Helvetica, Arial;
		font-size: 14px;
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
		font-size: 14px;
			}
			div#nav_bar {
				display:none;
			}
.bg_total {
	background-color: #000;
	color: #ffffff;
} 		

	p, ul{
		font-family: Verdana, Tahoma, Helvetica, Arial;
		font-size: 14px;
	}
			
#print_div_content{		
	width:99%;/*210mm*/
	/*height: 29cm;*/
	/*border: 1px #666 solid;*/
}
 		
}  
  </style>
  </head>
  <body>
	<div id="print_div_content">
<table style="border-collapse: collapse;" width="100%" border="1" cellspacing="0" cellpadding="1">
	<colgroup>
		<col width="100">
		<col>
	</colgroup>
 	<tr>
 		<td colspan="2" style="padding: 3px;padding-left: 5px;background-color: #eee;"><strong><?php echo htmlspecialchars($_GET["calc_name"], ENT_QUOTES, 'cp1251'); ?></strong></td>
 	</tr>
 	<tr>
 		<td align="center"><div style="width:210px; height:210px;"><!--IMAGE--></div></td>
 		<td valign="top">
			<table style="border-collapse: collapse;" width="100%" border="1" cellspacing="0" cellpadding="0">
				<colgroup>
					<col width="160">
					<col>
				</colgroup>
				<?php
					$count_field = $_GET["box_cnt"];
					if ($count_field > 0 ) {
					for($i = 1; $i <= $count_field; $i++) { 
				?>
				<?php if (isset($_GET["input_Lfield_".$i]) && $_GET["input_Lfield_".$i] != '') { ?>
				<tr>
					<td style="text-align: right;padding-right: 5px;"><?php echo htmlspecialchars ($_GET["input_Lfield_".$i], ENT_QUOTES, 'cp1251'); ?></td>
					<td><?php echo htmlspecialchars ($_GET["input_Rfield_".$i], ENT_QUOTES, 'cp1251'); ?></td>
				</tr>
				<?php } ?>
				<?php } ?>
				<?php } ?>
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
					<td style="text-align: right;padding-right: 5px;font-weight: bold;" id="single_sum_print">&nbsp;<?php echo number_format((float)$_GET["single_sum"], 2); ?> лв/бр</td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;"><strong>ОБЩО БЕЗ ДДС</strong></td>
					<td style="text-align: right;padding-right: 5px;font-weight: bold;" id="sum_total_print">&nbsp;<?php echo number_format((float)$_GET["sum_total"], 2); ?> лв</td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;"><strong>ТОТАЛ</strong></td>
					<td style="text-align: right;padding-right: 5px;font-weight: bold;" id="sum_vat_total_print">&nbsp;<?php echo number_format(((float)$_GET["sum_total"]*1.2), 2); ?> лв</td>
				</tr>
		 	</table>
		 
		</td>
 	</tr>
 	<tr>
 		<td colspan="2">&nbsp;</td>
 	</tr>
</table>
<?php
/*
print "count: ".count($_GET)."<br>";
print "<pre>";
print_r($_GET);
print "</pre>";
*/
?>
	</div>

  </body>
</html>
