<?php
	include("../config.php");
	include_once(DIR_SYSTEM."function.inc.php");
	include_once(DIR_SYSTEM."db.mysql.php");
	include_once(DIR_SYSTEM."pagination.php");

$doc_id =  _p("doc_id", _g("id",""));
if ($doc_id) {
		$doc = array();
		$query = " 
		select d.*
		, p.PName
		, CONCAT(m.member_name, m.member_family) as fullName 
		from docs as d 
		left join partners as p on (p.partner_id = d.partner_id)
		left join members as m on (m.uid = d.uid_created) 
		where d.doc_id='".$db->escape($doc_id)."' LIMIT 1";
		$db->prepare($query);
		if($db->query()) {
			if ($db->fetch()){
				$db->Record["Docdate"] = dt_convert($db->Record["Docdate"], DT_SQL, DT_BG, DT_DATE);
				$db->Record["DocPeceiptDate"] = dt_convert($db->Record["DocPeceiptDate"], DT_SQL, DT_BG, DT_DATE);
			
				$doc = $db->Record;
				$doc["doc_options"] = array();
			}
		} else {
			print ("DB Error: <br>".$db->Error);
			return false;
		}
		
		if (count($doc)){

			$doc_options = array();
			$query = " select * from doc_options where doc_id='".$db->escape($doc_id)."'";
			$db->prepare($query);
			if($db->query()) {
				while ($db->fetch()){
					$doc_options[$db->Record["option_id"]] = $db->Record;
				}
			} else {
			print ("DB Error: <br>".$db->Error);
				return false;
			}
			$doc['doc_options'] = $doc_options;
			//printArray($doc);
		}

} else {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1251">
  <title>Няма документ за печат!</title>
    <script src="<?php echo TEMPLATE; ?>js/jquery-1.6.1.min.js"  type="text/javascript"></script>
  <style>
@media screen{
	body{
		margin: 0 0 0 0;
		padding: 0 0 0 0;
		font-family: Verdana, Tahoma, Helvetica, Arial;
		font-size: 12px;
	}
	table {
		font-family: Verdana, Tahoma, Helvetica, Arial;
		font-size: 12px;
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
  width:90%;
  margin: 0 0 0 0;
  padding: 0 0 0 0;
} 		

}  
@media print
{
      body{ 
		margin: 0 0 0 0;
		padding: 0 0 0 0;
		font-family: Verdana, Tahoma, Helvetica, Arial;
		font-size: 12px;
			}
			div#nav_bar {
				display:none;
			}

	p, ul{
		font-family: Verdana, Tahoma, Helvetica, Arial;
		font-size: 12px;
	}

	table {
		font-family: Verdana, Tahoma, Helvetica, Arial;
		font-size: 12px;
	}
			
#print_div_content{		
	width:99%;/*210mm*/
	/*height: 29cm;*/
	/*border: 1px #666 solid;*/
  width:90%;
  margin: 0 0 0 0;
  padding: 0 0 0 0;
} 		
}  
  </style>
  </head>
  <body>
	<div id="nav_bar">
	 	<ul id="nav_btns">
	 		<li>
	 			<input type="image" value="" src="<?php echo HTTP_IMAGE;?>img/close.png" onclick="window.close();">
			</li>
	 		<li>
				<input type="image" value="" src="<?php echo HTTP_IMAGE;?>img/print.png" onclick="window.print();return false;">
			</li>
	 	</ul>
	</div>
	<div id="print_div_content">
		<h1>Няма документ за печат!</h1>
	</div>
		
  </body>
</html>

<?php
	exit();
}

if (!count($doc)) {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1251">
  <title>Няма документ за печат!</title>
    <script src="<?php echo TEMPLATE; ?>js/jquery-1.6.1.min.js"  type="text/javascript"></script>
  <style>
@media screen{
	body{
		margin: 0 0 0 0;
		padding: 0 0 0 0;
		font-family: Verdana, Tahoma, Helvetica, Arial;
		font-size: 12px;
	}
	table {
		font-family: Verdana, Tahoma, Helvetica, Arial;
		font-size: 12px;
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
		font-size: 12px;
			}
			div#nav_bar {
				display:none;
			}

	p, ul{
		font-family: Verdana, Tahoma, Helvetica, Arial;
		font-size: 12px;
	}

	table {
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
  </head>
  <body>
	<div id="nav_bar">
	 	<ul id="nav_btns">
	 		<li>
	 			<input type="image" value="" src="<?php echo HTTP_IMAGE;?>img/close.png" onclick="window.close();">
			</li>
	 		<li>
				<input type="image" value="" src="<?php echo HTTP_IMAGE;?>img/print.png" onclick="window.print();return false;">
			</li>
	 	</ul>
	</div>
	<div id="print_div_content">
		<h1>Няма документ за печат!</h1>
	</div>
		
  </body>
</html>

<?php
	exit();
	/*
	
	
		****************************************************************************************
	
	
	*/
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1251">
  <title><?php echo ($doc["DType"] == 2? "Поръчка":"Оферта"); ?> No <?php echo $doc["DocNum"]; ?></title>
    <script src="<?php echo TEMPLATE; ?>js/jquery-1.6.1.min.js"  type="text/javascript"></script>
  
  <style>
@media screen{
	body{
		margin: 0 0 0 0;
		padding: 0 0 0 0;
		font-family: Verdana, Tahoma, Helvetica, Arial;
		font-size: 12px;
	}
	table {
		font-family: Verdana, Tahoma, Helvetica, Arial;
		font-size: 12px;
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
		font-size: 12px;
			}
			div#nav_bar {
				display:none;
			}

	p, ul{
		font-family: Verdana, Tahoma, Helvetica, Arial;
		font-size: 12px;
	}

	table {
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
  </head>
  <body>
	<div id="nav_bar">
	 	<ul id="nav_btns">
	 		<li>
	 			<input type="image" value="" src="<?php echo HTTP_IMAGE;?>img/close.png" onclick="window.close();">
			</li>
	 		<li>
				<input type="image" value="" src="<?php echo HTTP_IMAGE;?>img/print.png" onclick="window.print();return false;">
			</li>
	 	</ul>
	</div>
	<div id="print_div_content">
<table width="98%" border="0" cellspacing="0" cellpadding="1">
 	<tr>
 		<td>
 			<img src="<?php echo HTTP_IMAGE;?>img/header.png" border=0 style="width:99%;">
 			<br><br>
 		</td>
 	</tr>

 	<tr>
 		<td>

	<p>
		<?php if ($doc["DType"] == 2) {?>

		<div style="text-align: center;">
			<h1>Поръчка N<u><sup>o</sup></u> <?php echo $doc["DocNum"]; ?></h1>
			
			<div>
			<table style="border-collapse: collapse; margin-bottom: 40px; font-size: 16px;" align="center" border="0" cellspacing="4" cellpadding="12">
				<tr>
					<td width="160"><b>Дата: </b></td>
					<td width="220"><?php echo $doc["Docdate"]; ?></td>
					<td width="120">&nbsp;</td>
					<td width="160"><b>СТОЙНОСТ: </b></td>
					<td width="220"><?php echo bg_money($doc["DTotal"]); ?></td>
				</tr>
				<tr>
					<td width="160"><b>Дата за получаване: </b></td>
					<td width="220"><?php echo $doc["DocPeceiptDate"]; ?></td>
					<td width="120">&nbsp;</td>
					<td width="160"><b>Аванс: </b></td>
					<td width="220"><?php echo bg_money($doc["DAdvance"]); ?> лв.</td>
				</tr>
				<tr>
					<td width="160"><b>Клиент: </b></td>
					<td width="220"><?php echo $doc["PName"]; ?></td>
					<td width="120">&nbsp;</td>
					<td width="160"><b>Доплащане: </b></td>
					<td width="220"><?php echo bg_money($doc["DSurcharge"]); ?>  лв.</td>
				</tr>
				<tr>
					<td width="160"><b>Обслужва: </b></td>
					<td width="220"><?php echo $doc["fullName"]; ?></td>
					<td width="120">&nbsp;</td>
					<td width="160"><b>Корекции: </b></td>
					<td width="220"><?php echo $doc["DCorrections"]; ?></td>
				</tr>
				<tr>
					<td width="160"><b>Място за получаване: </b></td>
					<td width="220"><?php echo $doc["DCAddr"]; ?></td>
					<td width="120">&nbsp;</td>
					<td width="160"><b>Плащане: </b></td>
					<td width="220"><?php echo $GLOBALS["ORDER_PAYMENT"][$doc["DPayment"]]; ?></td>
				</tr>
			</table>
							
			</div>
		</div>


		<?php } else { ?>
		<div style="text-align: center;">
			<h1>Оферта</h1>
			<div style="font-weight: bold; font-size: 1.2em;">
				N<u><sup>o</sup></u> <?php echo $doc["DocNum"]; ?> Дата: <?php echo $doc["Docdate"]; ?>
			</div>
			<div>
			<table style="border-collapse: collapse; margin-bottom: 40px;margin-right: 20px;" align="right" width="250" border="1" cellspacing="0" cellpadding="1">
				<tr>
					<td style="font-weight: bold; font-size: 1em; color: #fd8b30; text-align: center; background-color: #eee;">НА ВНИМАНИЕТО НА</td>
				</tr>
				<tr>
					<td  style="font-weight: bold; text-align: center;"><?php echo $doc["PName"]; ?></td>
				</tr>
			</table>
							
			</div>
		</div>
		<?php } ?>
	</p>

<table style="border-collapse: collapse;" width="99%" border="0" cellspacing="0" cellpadding="1">
	<colgroup>
		<col width="100">
		<col>
	</colgroup>
 	<tr>
 		<td colspan="2">
		 <?php if (count($doc['doc_options'])) { ?>
		 <?php foreach ($doc['doc_options'] as $key => $item) { ?>
		 <?php echo $item["DExportData"];?>
		 <?php 
		 $item["DNotes"] = trim($item["DNotes"]);
		 if (!empty($item["DNotes"])) {
		 echo "<p><b>Забележка: </b> ".$item["DNotes"]."</p>";
		 }
		 ?>
		 <?php } ?>
		 <?php } ?>
		</td>
 	</tr>
 	<tr>
 		<td colspan="2">&nbsp;</td>
 	</tr>
 	<?php if ($doc["view_term"]) { ?>
 	<tr>
 		<td colspan="2" style="background-color: #eee;">
<p style="padding: 5px;">
<strong><u>Срок за изработка:</u></strong><br>
	Срок за печат до <?php echo $doc["term_work"];?> след одобрение на проекта.

</p> 		
<p style="padding: 5px;">
<strong><u>Плащане:</u></strong><br>
Поръчката за печат се стартира след <?php echo $doc["Dpay_advance"];?> авансово плащане и при получаване на продукта – <?php echo $doc["Dpay_surcharge"];?>.<br>
При използване на готови рекламни продукти същите се заплащат 100% авансово.
</p>

<p style="padding: 5px;">
<strong><u>Валидност на офертата:</u></strong><br>
20 дни от датата на издаването
</p>
<p style="padding: 5px;">
<strong><u>Цени:</u></strong><br>
<ul>
	<li>Всички посочени цени в настоящата оферта са 
	<?php
		if ($doc["vat_id"] == 2) {
			print "с начислен ДДС";
		} else {
			print "без начислен ДДС";
		}
	?>
	, ако не е отбелязано друго.</li>
	<li>Цените в настоящата оферта важат само за посочените количества.</li>
	<li>Посочените в офертата цени не включват дизайн, предпечатна подготовка или обработка на файлове за печат, ако не е отбелязано друго.</li>
	<li>Плащанията се извършват в български лева (BGN) по официалния курс на БНБ за деня.</li>
	<li>АВ Дизайн Студио ООД си запазва правото на промяна в цената, ако оферираното не съответства на предоставения от клиента дизайн, вид печат, вид материали, или количество и причината за различието не е във фирма АВ Дизайн Студио ООД.</li>
</ul>
</p>
<p style="padding: 5px;">
<strong><u>Отклонения:</u></strong><br>
Възможни отклонения в крайното количество +2%/-2%, които се отбелязват в сумата при доплащане.
</p>
<p style="padding: 5px;">
<strong><u>Доставка:</u></strong><br>
	<?php echo $GLOBALS["DELIVERY_PLACE"][$doc["delivery_place"]];?>
</p>

		</td>
 	</tr>
<?php } ?>
 	
</table>
<p style="padding: 5px;">
<b style="font-size: 1.2em;">
Изготвил: 
</b>
<?php echo $doc["fullName"]; ?>
</p>

 			
 		</td>
 	</tr>
</table>
	</div>

  </body>
</html>
