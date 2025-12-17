<?php
	include("../config.php");
	include_once(DIR_SYSTEM."function.inc.php");
	include_once(DIR_SYSTEM."db.mysql.php");
	include_once(DIR_SYSTEM."pagination.php");

$doc_id =  _p("doc_id", _g("id",""));
if ($doc_id) {
		$doc = array();
		$partner_info = array();
		
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
		
		if (isset($doc["partner_id"]) && !empty($doc["partner_id"])) {
      $sql ="select * from partners where partner_id = '".$doc["partner_id"]."'";
  		$db->prepare($sql);
  		if($db->query()) {
  			if ($db->fetch()){
  				$partner_info = $db->Record;
  			}
  		}
    }
    if (!count($partner_info)) {
      $partner_info = array(
        'PName' => '', 
        'PCity' => '', 
        'PAddr' => '', 
        'PMol' => '', 
        'PBulstat' => '', 
        'PZDDS' => '', 
        'PPerson' => '', 
        'PPhone' => '', 
        'PEmail' => '', 
      );
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
	 			<input type="image" value="" src="<?php echo HTTP_IMAGE;?>img/close.png" onClick="window.close();">
			</li>
	 		<li>
				<input type="image" value="" src="<?php echo HTTP_IMAGE;?>img/print.png" onClick="window.print();return false;">
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
	 			<input type="image" value="" src="<?php echo HTTP_IMAGE;?>img/close.png" onClick="window.close();">
			</li>
	 		<li>
				<input type="image" value="" src="<?php echo HTTP_IMAGE;?>img/print.png" onClick="window.print();return false;">
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
  <title>Заявка за изработка No <?php echo $doc["DocNum"]; ?></title>
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
	 			<input type="image" value="" src="<?php echo HTTP_IMAGE;?>img/close.png" onClick="window.close();">
			</li>
	 		<li>
				<input type="image" value="" src="<?php echo HTTP_IMAGE;?>img/print.png" onClick="window.print();return false;">
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
	 <div style="text-align: center;">
	 <h3>ЗАЯВКА ЗА ИЗРАБОТКА</h3>
	 <br> 
	 <div><b>от дата: <?php echo $doc['Docdate']; ?></b></div>
	 </div>
	</p>
	<p>
  <table width="100%" border="0" cellspacing="0" cellpadding="2">
    <colgroup>
      <col width="50%">
      <col width="50%">
    </colgroup>
    <tr>
      <td>
        <table width="100%" border="1" cellspacing="0" cellpadding="0">
          <colgroup>
            <col width="30%">
            <col width="70%">
          </colgroup>
          <tr>
            <td><b>ИЗПЪЛНИТЕЛ:</b></td>
            <td><b>АВ Дизайн Студио ООД</b></td>
          </tr>
          <tr>
            <td>Град:</td>
            <td>София</td>
          </tr>
          <tr>
            <td>Адрес:</td>
            <td>Бул. Ботевградско шосе, Сухата река бл. 6</td>
          </tr>
          <tr>
            <td>ЕИК</td>
            <td>203621911</td>
          </tr>
          <tr>
            <td>ДДС No:</td>
            <td>BG203621911</td>
          </tr>
          <tr>
            <td>М.О.Л.:</td>
            <td>Александър Димитров</td>
          </tr>
          <tr>
            <td>Телефон:</td>
            <td>02490 49 49</td>
          </tr>
          <tr>
            <td>E-mail:</td>
            <td>office@avdesigngroup.org</td>
          </tr>
          <tr>
            <td>IBAN:</td>
            <td>BG04CECB979010G0180800</td>
          </tr>
          <tr>
            <td>BIC код:</td>
            <td>CECBBGSF</td>
          </tr>
          <tr>
            <td>Банка:</td>
            <td>ЦКБ АД – клон „Мадрид”</td>
          </tr>
        </table>
      </td>
      <td>
        <table width="100%" border="1" cellspacing="0" cellpadding="0">
          <colgroup>
            <col width="30%">
            <col width="70%">
          </colgroup>
          <tr>
            <td><b>ВЪЗЛОЖИТЕЛ:</b></td>
            <td><b><?php echo $partner_info['PName']; ?></b></td>
          </tr>
          <tr>
            <td>Град:</td>
            <td><?php echo $partner_info['PCity']; ?></td>
          </tr>
          <tr>
            <td>Адрес:</td>
            <td><?php echo $partner_info['PAddr']; ?></td>
          </tr>
          <tr>
            <td>ЕИК</td>
            <td><?php echo $partner_info['PBulstat']; ?></td>
          </tr>
          <tr>
            <td>ДДС No:</td>
            <td><?php echo (empty($partner_info['PZDDS'])?'':'BG'.$partner_info['PBulstat']); ?></td>
          </tr>
          <tr>
            <td>М.О.Л.:</td>
            <td><?php echo $partner_info['PMol']; ?></td>
          </tr>
          <tr>
            <td>Телефон:</td>
            <td><?php echo $partner_info['PPhone']; ?></td>
          </tr>
          <tr>
            <td>E-mail:</td>
            <td><?php echo $partner_info['PEmail']; ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
      
      </td>
    </tr>
  </table>
	</p>
	<p>
	 <div style="text-align: center;">
	 ВЪЗЛОЖИТЕЛЯТ възлага, а ИЗПЪЛНИТЕЛЯТ приема да извърши за своя сметка срещу възнаграждение по предварително одобренa оферта от ВЪЗЛОЖИТЕЛЯ следното:
	 </div>
	</p>
	
	<p>
        <table width="100%" border="1" cellspacing="0" cellpadding="0">
          <colgroup>
            <col width="1%">
            <col width="78%">
            <col width="20%">
          </colgroup>
          <tr>
            <td><b>No</b></td>
            <td colspan="2"><b>Наименование на стоките и услугите</b></td>
          </tr>
          <?php 
          $index = 0;
          $sum_total = 0;
          foreach($doc['doc_options'] as $opt){
          $sum_total += $opt['PSum'];
          $productName = '';
          $tmp = explode('<tr>', $opt['DExportData']);
          $productName = strip_tags($tmp[1]);
          ?>
          <tr>
            <td style="text-align: right;"><?php echo (++$index); ?>.</td>
            <td colspan="2">&nbsp;<?php echo $productName; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td colspan="2" style="text-align: right;"><b>ЗАЯВКАТА Е НА ОБЩА СТОЙНОСТ: </b></td>
            <td style="text-align: right;"><b><?php echo str_replace(',', '', number_format($doc['DTotal'], 2)); ?> лв.</b></td>
          </tr>
        </table>
        <br>
	</p>


	<p>
  <table width="100%" border="0" cellspacing="0" cellpadding="2">
    <colgroup>
      <col width="50%">
      <col width="50%">
    </colgroup>
    <tr>
      <td valign="top">
        <table width="100%" border="1" cellspacing="0" cellpadding="0">
          <colgroup>
            <col width="30%">
            <col width="70%">
          </colgroup>
          <tr>
            <td colspan="2"><b>СРОК ЗА ИЗПЪЛНЕНИЕ: <br><?php echo $doc['DocPeceiptDate']; ?></b></td>
          </tr>
          <tr>
            <td colspan="2"><br><?php echo $partner_info['PPerson']; ?> <br>(име и фамилия)<br><br></td>
          </tr>
        </table>
      </td>
      <td valign="top">
        <table width="100%" border="1" cellspacing="0" cellpadding="0">
          <colgroup>
            <col width="30%">
            <col width="70%">
          </colgroup>
          <tr>
            <td colspan="2"><b>Задължавам се да изплатя пълната сума по направената Заявка по следния начин: <br>
            <?php 
            if ($doc['DPayment'] == 1) {
              echo 'в брой';
            }
            if ($doc['DPayment'] == 2) {
              echo 'по банкова сметка';
            }
            ?>
            </b></td>
          </tr>
          <tr>
            <td colspan="2">
              <br>
              <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td rowspan="2">Възложител:</td>
                  <td colspan="2" style="width: 220px;border-bottom: 1px #000 solid;">&nbsp;</td>
                </tr>
                <tr>
                  <td>(подпис)</td>
                  <td style="text-align: right;">(печат)</td>
                </tr>
              </table>
              <br>
            </td>
          </tr>
        </table>
      
      </td>
    </tr>
  </table>
  
	</p>
 			
 		</td>
 	</tr>
</table>
	</div>

  </body>
</html>
