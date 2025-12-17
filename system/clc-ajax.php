<?php
	session_start();
	include_once 'function.inc.php';

	$json = array();
	$act = _p("act","");
	if ($act == "save") {
		$calc_dir = _p("clc_dir","");
		$data = _p("html_data", "");
		if (!empty($calc_dir)){
			$_SESSION["CALCULATORS"][$calc_dir]['HTML_DATA'] = $data;
			$json['success'] = "Данните са записани успешно!";
		} else {
			$json['error'] = "Грешка при записа на данните!";
		}
	} else if ($act == "export") {
//		$json[] = '"success":"Данните са експортирани успешно!"';
		$folder = _p("folder","-");
		$json['success'] = "Данните са експортирани успешно!";
		if ($folder != "-") {
		if (isset($_SESSION["CALCULATORS"][$folder]['HTML_DATA'])) {
			$clc_code = mb_convert_encoding($_SESSION["CALCULATORS"][$folder]['HTML_DATA'], "windows-1251", "UTF-8");
		} else {
			$clc_code = '';
		}
		} else {
			$clc_code = getUniversal();
		}
		$json['html'] = $clc_code; //'"html":"'.$clc_code.'"';//'.$_SESSION["CALCULATORS"]['plotting']['HTML_DATA'].'
	}
	//echo "{}";
/*
	$json['success'] = 'Данните са експортирани успешно!';
	$json['error'] = 'Грешка ала бала!';
	$clc_code = mb_convert_encoding($_SESSION["CALCULATORS"]['plotting']['HTML_DATA'], "windows-1251", "UTF-8");
	$json['html'] = $clc_code;
	*/
	echo __json_encode($json);
	//json_encode($json);
	
//	'{'.(count($json)?implode(",", $json):'').'}';

	//json_encode($json);
function getUniversal() {
	return '
	<table border="0" cellpadding="1" cellspacing="0" width="98%">
	<tbody>
		<tr>
			<td>
				<img border="0" src="images/header.png" style="width:99%;" /><br />
				&nbsp;</td>
		</tr>
		<tr>
			<td>
				<table border="1" cellpadding="1" cellspacing="0" style="border-collapse: collapse;" width="99%">
					<colgroup>
						<col width="100" />
						<col />
					</colgroup>
					<tbody>
						<tr>
							<td colspan="2" style="padding: 3px;padding-left: 5px;background-color: #eee;">
								<strong>Плотиране</strong></td>
						</tr>
						<tr>
							<td>
								<div style="width:250px; height:100px;">
									&nbsp;</div>
							</td>
							<td valign="top">
								<table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse;" width="100%">
									<colgroup>
										<col width="160" />
										<col />
									</colgroup>
									<tbody>
										<tr>
											<td style="text-align: right;padding-right: 5px;">&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td style="text-align: right;padding-right: 5px;">&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td style="text-align: right;padding-right: 5px;">&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td style="text-align: right;padding-right: 5px;">&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td style="text-align: right;padding-right: 5px;">&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td style="text-align: right;padding-right: 5px;">&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td style="text-align: right;padding-right: 5px;">&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td style="text-align: right;padding-right: 5px;">&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td style="text-align: right;padding-right: 5px;">&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2" style="background-color: #eee;">
								&nbsp;<strong>ЦЕНИ</strong></td>
						</tr>
						<tr>
							<td colspan="2">
								<table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse;" width="100%">
									<colgroup>
										<col />
										<col width="160" />
									</colgroup>
									<tbody>
										<tr>
											<td style="text-align: right;padding-right: 5px;">
												<strong>Ед. цена без ДДС</strong></td>
											<td id="single_sum_print" style="text-align: right;padding-right: 5px;">
												&nbsp;0.00 лв/бр</td>
										</tr>
										<tr>
											<td style="text-align: right;padding-right: 5px;">
												<strong>ОБЩО БЕЗ ДДС</strong></td>
											<td id="sum_total_print" style="text-align: right;padding-right: 5px;">
												&nbsp;0.00 лв</td>
										</tr>
										<tr>
											<td style="text-align: right;padding-right: 5px;">
												<strong>ТОТАЛ</strong></td>
											<td id="sum_vat_total_print" style="text-align: right;padding-right: 5px;">
												&nbsp;0.00 лв</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2" style="background-color: #eee;">
								<p style="padding: 5px;">
									<strong><u>Срок за изработка:</u></strong><br />
									Срок за печат до 5 работни дни след одобрение на проекта.</p>
								<p style="padding: 5px;">
									<strong><u>Плащане:</u></strong><br />
									Поръчката за печат се стартира след 50% авансово плащане и при получаване на продукта &ndash; 50%.<br />
									При използване на готови рекламни продукти същите се заплащат 100% авансово.</p>
								<p style="padding: 5px;">
									<strong><u>Валидност на офертата:</u></strong><br />
									20 дни от датата на издаването</p>
								<p style="padding: 5px;">
									<strong><u>Цени:</u></strong></p>
								<ul>
									<li>
										Всички посочени цени в настоящата оферта са без начислен ДДС, ако не е отбелязано друго.</li>
									<li>
										Цените в настоящата оферта важат само за посочените количества.</li>
									<li>
										Посочените в офертата цени не включват дизайн, предпечатна подготовка или обработка на файлове за печат, ако не е отбелязано друго.</li>
									<li>
										Плащанията се извършват в български лева (BGN) по официалния курс на БНБ за деня.</li>
									<li>
										 АВ Дизайн Студио ЕООД си запазва правото на промяна в цената, ако оферираното не съответства на предоставения от клиента дизайн, вид печат, вид материали, или количество и причината за различието не е във фирма  АВ Дизайн Студио ЕООД.</li>
								</ul>
								<p>
									&nbsp;</p>
								<p style="padding: 5px;">
									<strong><u>Отклонения:</u></strong><br />
									Възможни отклонения в крайното количество +2%/-2%, които се отбелязват в сумата при доплащане.</p>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>

	
	';
}	
?>