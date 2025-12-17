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
 		<td colspan="2" style="padding: 3px;padding-left: 5px;background-color: #eee;"><strong>CD календари</strong></td>
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
					<td id="print_paper_size"><?php echo $_GET["size"]; ?></td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;">обем</td>
					<td id="print_paper_type"><?php echo $_GET["print_type"]; ?></td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;">печат</td>
					<td id="print_paper_print"><?php echo $_GET["paper_print"]; ?></td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;">покритие</td>
					<td id="print_paper_cover"><?php echo $_GET["cover"]; ?></td>
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
					<td style="text-align: right;padding-right: 5px;">ОБЩО ПЕЧАТ</td>
					<td style="text-align: right;padding-right: 5px;" id="print_total_print">&nbsp;<?php echo $_GET["total_print"]; ?></td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;">ОБЩО ХАРТИЯ</td>
					<td style="text-align: right;padding-right: 5px;" id="print_total_paper">&nbsp;<?php echo $_GET["total_paper"]; ?></td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;">ОБЩО ПОКРИТИЕ</td>
					<td style="text-align: right;padding-right: 5px;" id="print_total_cover">&nbsp;<?php echo $_GET["total_cover"]; ?></td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;">ОБЩО ХОЛДЕРИ</td>
					<td style="text-align: right;padding-right: 5px;" id="print_total_holders">&nbsp;<?php echo $_GET["total_holders"]; ?></td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;">ОБЩО ПРЕДПЕЧАТ</td>
					<td style="text-align: right;padding-right: 5px;" id="print_total_prepress">&nbsp;<?php echo $_GET["total_prepress"]; ?></td>
				</tr>




				<tr>
					<td style="text-align: right;padding-right: 5px;"><strong>Ед. цена без ДДС</strong></td>
					<td style="text-align: right;padding-right: 5px;font-weight: bold;" id="single_sum_print">&nbsp;<?php echo $_GET["sum"]; ?></td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;"><strong>ОБЩО БЕЗ ДДС</strong></td>
					<td style="text-align: right;padding-right: 5px;font-weight: bold;" id="sum_total_print">&nbsp;<?php echo $_GET["total"]; ?></td>
				</tr>
				<tr>
					<td style="text-align: right;padding-right: 5px;"><strong>ТОТАЛ</strong></td>
					<td style="text-align: right;padding-right: 5px;font-weight: bold;" id="sum_vat_total_print">&nbsp;<?php echo $_GET["vat"]; ?></td>
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
