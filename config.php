<?php 
session_start();
header('Content-Type: text/html; charset=windows-1251');


function getmicrotime(){
	list($usec,$sec)=explode(" ",microtime());
	return ((float)$usec+(float)$sec);
}

// anti flood protection
function flood_protection() {
	$_time = time();
	$_time_out = ($_time-2);
//			$_SESSION["session_request_timeout"] = false;

	if (isset($_SESSION["session_request_timeout"]) && $_SESSION["session_request_timeout"]) {
		if ($_time > $_SESSION["session_request_timeout"]) {
			$_SESSION["session_request_timeout"] = false;
		} else {
	    header("location: flood.html");
	    exit();
		}
	}
	
	if ($_SESSION["last_session_request"] > $_time_out){
			$_SESSION["session_request_timeout"] = (time()+60);
			/*
	    header("location: flood.html");
	    exit();
	    */
	}
	
	$_SESSION["last_session_request"] = time();
 
}
// anti flood protection
//flood_protection();

$GLOBALS['PAGE_BEGIN'] = getmicrotime();

date_default_timezone_set("Europe/Sofia");

define("HTTP_SERVER", "https://avdesigngroup.org/crm_avd/");
define("HTTP_IMAGE", HTTP_SERVER."images/");
define("HTTP_CALCULATOR", HTTP_SERVER."calculators/");

define("SLASH", strstr(__FILE__,"/")?"/":"\\");
define("DIR_BASE", dirname(__FILE__).SLASH);
define("DIR_SYSTEM", DIR_BASE."system".SLASH);
define("DIR_IMAGE", DIR_BASE."images".SLASH);

define("DIR_CONTROLLER", DIR_BASE."controller".SLASH);
define("DIR_MODEL", DIR_BASE."model".SLASH);
define("DIR_VIEW", DIR_BASE."view".SLASH);
define("DIR_TEMPLATE", DIR_VIEW."template".SLASH);
define("DIR_EXCEL_LIB", DIR_SYSTEM."libraries".SLASH."excel".SLASH);


define("DEFAULT_TEMPLATE",DIR_TEMPLATE."default".SLASH);
define("TEMPLATE", "view".SLASH."template".SLASH."default".SLASH);

define("DB_HOSTNAME", "localhost");
define("DB_USERNAME", "avdes_crmuser");
define("DB_PASSWORD", "T>;%sPg48?j7");
define("DB_DATABASE", "avdes_crm");

define("DB_CHARSET", "cp1251");
define("DB_COLLATION", "cp1251_bulgarian_ci");

define("MSG_ERROR","1");
define("MSG_WARNING","2");
define("MSG_INFO","3");
define("MSG_SUCCESS","4");

define("DT_DATE","DATE");
define("DT_DATETIME","DATETIME");
define("DT_BG","BG");
define("DT_SQL","SQL");

if (isset($_SESSION["uid"]) && $_SESSION["uid"] == 1) {
define("DEBUG",true);
} else {
define("DEBUG",false);
}
if (DEBUG) {
	ini_set("html_errors", "1");
	ini_set("display_errors","1");
	error_reporting (E_ALL ^ E_NOTICE);

} else {
ini_set("display_errors","0");
}

$GLOBALS["MSG"] = array();

function msg_add($msg, $type = MSG_ERROR) {
	$_class = "";
	switch($type) {
		case MSG_WARNING:
			$_class = "warning";
			break;
		case MSG_INFO:
			$_class = "info";
			break;
		case MSG_SUCCESS:
			$_class = "success";
			break;
		case MSG_ERROR:
			default:
			$_class = "error";
			break;
	}
	$GLOBALS["MSG"][] = array("type" => $type, "msg" => $msg);
	/*
	$GLOBALS["MSG"][] = "
	<div class=\"".$_class."\">
		<div class=\"close_msg\" onclick=\"hide_msg(this);\">close</div>
		<div class=\"msg_content\">".$msg."</div>
	</div>
	";
	*/
}

define("DOC_TYPE_OFFER", "1");//OFFER
define("DOC_TYPE_ORDER", "2");//ORDER

define("CASE_TYPE_PROFIT", "1");//profit
define("CASE_TYPE_COST", "2");//cost

$GLOBALS["SYSTEM_ROLES"] = array(
	"0" => "- Вид потребител - "
	, "1" => "Administrator"
	, "2" => "Manager"
	, "3" => "Account"
	, "4" => "Designer"
	, "5" => "Supplier"
);

$GLOBALS["SYSTEM_USER_STATUS"] = array(
	"1" => "Активен"
	, "0" => "Неактивен"
);

$GLOBALS["SYSTEM_SHOW_FORGOTTEN_ACTIVITY"] = array(
	"1" => "Да"
	, "0" => "Не"
);

$GLOBALS["DOC_TYPES"] = array(
	DOC_TYPE_OFFER => "Оферта"
	, DOC_TYPE_ORDER => "Поръчка"
);

define("DOC_ORDER_STATUS_N", "1");//Нереализирана
define("DOC_ORDER_STATUS_R", "2");//Реализирана
define("DOC_ORDER_STATUS_A", "3");//Активна
define("DOC_ORDER_STATUS_E", "4");//Плиключила

$GLOBALS["ORDER_STATUS"] = array(
	DOC_ORDER_STATUS_N => "Нереализирана"
	, DOC_ORDER_STATUS_R => "Реализирана"
	, DOC_ORDER_STATUS_A => "Активна"
	, DOC_ORDER_STATUS_E => "Приключила"
);

define("CARD_STATUS_W", "0");//Очаква
define("CARD_STATUS_F", "1");//Готов
$GLOBALS["CARD_STATUS"] = array(
	CARD_STATUS_W => "Очаква"
	, CARD_STATUS_F => "Готов"
);

define("ACTIVITY_DATE_TYPE_A", "0");//Дейности
define("ACTIVITY_DATE_TYPE_O", "1");//Поръчки
$GLOBALS["ACTIVITY_DATE_TYPES"] = array(
	ACTIVITY_DATE_TYPE_A => "Дейности"
	, ACTIVITY_DATE_TYPE_O => "Поръчки"
);

/*
	"1" => "Дефиниране"
	, "2" => "Активна"
	, "3" => "Задържана"
	, "4" => "Плиключила"
	, "5" => "Реализирана"
	, "6" => "Нереализирана"
*/	

$GLOBALS["PAYMENT"] = array(
	"1" => "Авансово"
	, "2" => "Платена"
	, "3" => "Неплатена"
);

$GLOBALS["ORDER_PAYMENT"] = array(
	"1" => "в брой"
	, "2" => "по банкова сметка"
);

$GLOBALS["PARTNER_TYPES"] = array(
	"1" => "КК"
	, "2" => "РА"
);
$GLOBALS["VIEW_TERMS"] = array(
	"0" => "Не"
	, "1" => "Да"
);

$GLOBALS["PRICE_VAT"] = array(
	"1" => "Цените са без ДДС"
	, "2" => "Цените са с ДДС"
);
//АВ Дизайн Студио ООД
$GLOBALS["DELIVERY_PLACE"] = array(
//	"1" => "Франко склад или офис на ДЗЗД \"АВ Дизайн Груп\""
	"1" => "Франко склад или офис на \"АВ Дизайн Студио ООД\""
	, "2" => "Доставка до посочен адрес в гр. София"
);

$GLOBALS["USER_INFO"] = array(
	"uid" => ""
	, "role_id"
	, "session_id" => 0
);
/*
$GLOBALS["SYSTEM_CALC"] = array(
	"1" => array("ID" => "1", "NAME" => "Универсален", "FOLDER" => "universal")
	, "2" => array("ID" => "2", "NAME" => "Ароматизатори", "FOLDER" => "airfresheners")
	, "3" => array("ID" => "3", "NAME" => "Балони", "FOLDER" => "balloons")
	, "4" => array("ID" => "4", "NAME" => "Бланки", "FOLDER" => "blank")
	, "5" => array("ID" => "5", "NAME" => "Визитки", "FOLDER" => "prege2")
	, "6" => array("ID" => "6", "NAME" => "Визитни картички", "FOLDER" => "business-cards")
	, "7" => array("ID" => "7", "NAME" => "Гравиране", "FOLDER" => "engraving")
	, "8" => array("ID" => "8", "NAME" => "Джобни календарчета", "FOLDER" => "plamcalendar")
	, "9" => array("ID" => "9", "NAME" => "Дигитален печат", "FOLDER" => "digital-print")
	, "10" => array("ID" => "10", "NAME" => "Дигитален трансферен печат", "FOLDER" => "digitalprint")
	, "11" => array("ID" => "11", "NAME" => "Значки с обемен стикер", "FOLDER" => "badges-label")
	, "12" => array("ID" => "12", "NAME" => "Индивидуални тефтери", "FOLDER" => "individual-pads")
	, "13" => array("ID" => "13", "NAME" => "Индивидуални пирамиди", "FOLDER" => "pyramids")
	, "14" => array("ID" => "14", "NAME" => "Ключодържатели", "FOLDER" => "keyholders")
	, "15" => array("ID" => "15", "NAME" => "Листовки", "FOLDER" => "listovki")
	, "16" => array("ID" => "16", "NAME" => "Листови календари", "FOLDER" => "list_calendars")
	, "17" => array("ID" => "17", "NAME" => "Листов печат", "FOLDER" => "list-print")
	, "18" => array("ID" => "18", "NAME" => "Магнити", "FOLDER" => "magnets")
	, "19" => array("ID" => "19", "NAME" => "Настолни работни календари", "FOLDER" => "calendars")
	, "20" => array("ID" => "20", "NAME" => "Обемни стикери", "FOLDER" => "stickers")
	, "21" => array("ID" => "21", "NAME" => "Папки", "FOLDER" => "folders")
	, "22" => array("ID" => "22", "NAME" => "Падове и кубчета", "FOLDER" => "padove")
	, "23" => array("ID" => "23", "NAME" => "Печат на книги, каталози, списания, брошури", "FOLDER" => "books-ofset")
	, "24" => array("ID" => "24", "NAME" => "Печат на чаши", "FOLDER" => "print-cups")
	, "25" => array("ID" => "25", "NAME" => "Планшети", "FOLDER" => "planchettes")
	, "26" => array("ID" => "26", "NAME" => "Плакати", "FOLDER" => "posters")
	, "27" => array("ID" => "27", "NAME" => "Плотиране", "FOLDER" => "plotting")
	, "28" => array("ID" => "28", "NAME" => "Подложки за мишки", "FOLDER" => "mousepads")
	, "29" => array("ID" => "29", "NAME" => "Пощенски пликове", "FOLDER" => "envelopes")
	, "30" => array("ID" => "30", "NAME" => "Работни календари", "FOLDER" => "rcalendar")
	, "31" => array("ID" => "31", "NAME" => "Ситопечат", "FOLDER" => "screenPrinting")
	, "32" => array("ID" => "32", "NAME" => "Табелки", "FOLDER" => "badges")
	, "33" => array("ID" => "33", "NAME" => "Тампонен печат", "FOLDER" => "tampon")
	, "34" => array("ID" => "34", "NAME" => "Тефтери", "FOLDER" => "prege1")
	, "35" => array("ID" => "35", "NAME" => "Тиксо", "FOLDER" => "scotch")
	, "36" => array("ID" => "36", "NAME" => "Фактурници", "FOLDER" => "invoice")
	, "37" => array("ID" => "37", "NAME" => "Флок и флекс", "FOLDER" => "flockflex")
	, "38" => array("ID" => "38", "NAME" => "Хартиени торби", "FOLDER" => "paperBags")
	, "39" => array("ID" => "39", "NAME" => "Широкоформатен печат", "FOLDER" => "wideprinting")
	, "40" => array("ID" => "40", "NAME" => "CD календари", "FOLDER" => "cd-calendars")
	, "41" => array("ID" => "41", "NAME" => "CD и DVD", "FOLDER" => "cd_dvd")
	, "42" => array("ID" => "42", "NAME" => "PVC карти", "FOLDER" => "pvc-card")
);
*/
$GLOBALS["SYSTEM_CALC"] = array(
	"1" => array("ID" => "1", "NAME" => "Универсален", "FOLDER" => "universal", "IMAGE" => "uni.jpg", "CSS_CLASS" => "")
	, "2" => array("ID" => "2", "NAME" => "Ароматизатори", "FOLDER" => "airfresheners", "IMAGE" => "airfresheners.jpg", "CSS_CLASS" => "")
	, "3" => array("ID" => "3", "NAME" => "Балони", "FOLDER" => "balloons", "IMAGE" => "baloons.jpg", "CSS_CLASS" => "")
	, "4" => array("ID" => "4", "NAME" => "Бланки", "FOLDER" => "blank", "IMAGE" => "blank.jpg", "CSS_CLASS" => "")
	, "5" => array("ID" => "5", "NAME" => "Визитни картички", "FOLDER" => "business-cards", "IMAGE" => "business-cards.jpg", "CSS_CLASS" => "")
	, "6" => array("ID" => "6", "NAME" => "Гравиране", "FOLDER" => "engraving", "IMAGE" => "engraving.jpg", "CSS_CLASS" => "")
	, "7" => array("ID" => "7", "NAME" => "Джобни календарчета", "FOLDER" => "plamcalendar", "IMAGE" => "plamcalendar.jpg", "CSS_CLASS" => "")
	, "8" => array("ID" => "8", "NAME" => "Дигитален печат", "FOLDER" => "digital-print", "IMAGE" => "digital-print.jpg", "CSS_CLASS" => "")
	, "9" => array("ID" => "9", "NAME" => "Значки с обемен стикер", "FOLDER" => "badges-label", "IMAGE" => "badges-label.jpg", "CSS_CLASS" => "")
	, "10" => array("ID" => "10", "NAME" => "Индивидуални тефтери", "FOLDER" => "individual-pads", "IMAGE" => "individual-pads.jpg", "CSS_CLASS" => "")
	, "11" => array("ID" => "11", "NAME" => "Индивидуални пирамиди", "FOLDER" => "pyramids", "IMAGE" => "individ_piramida.jpg", "CSS_CLASS" => "")
	, "12" => array("ID" => "12", "NAME" => "Ключодържатели", "FOLDER" => "keyholders", "IMAGE" => "keyholders.jpg", "CSS_CLASS" => "")
	, "13" => array("ID" => "13", "NAME" => "Книги, каталози", "FOLDER" => "books-ofset-new", "IMAGE" => "books-ofset.jpg", "CSS_CLASS" => "")
//	, "50" => array("ID" => "50", "NAME" => "Книги, каталози", "FOLDER" => "books-ofset-new", "IMAGE" => "books-ofset.jpg", "CSS_CLASS" => "error-select")
	, "14" => array("ID" => "14", "NAME" => "Листовки", "FOLDER" => "listovki", "IMAGE" => "brochures.jpg", "CSS_CLASS" => "")
	, "15" => array("ID" => "15", "NAME" => "Листови календари", "FOLDER" => "list_calendars", "IMAGE" => "list_calendars.jpg", "CSS_CLASS" => "")
	, "16" => array("ID" => "16", "NAME" => "Листов печат", "FOLDER" => "list-print", "IMAGE" => "offset.jpg", "CSS_CLASS" => "")
	, "17" => array("ID" => "17", "NAME" => "Магнити", "FOLDER" => "magnets", "IMAGE" => "magnets.jpg", "CSS_CLASS" => "")
	, "18" => array("ID" => "18", "NAME" => "Настолни работни календари", "FOLDER" => "calendars", "IMAGE" => "nasstolni.jpg", "CSS_CLASS" => "")
	, "19" => array("ID" => "19", "NAME" => "Обемни стикери", "FOLDER" => "stickers", "IMAGE" => "stickers.jpg", "CSS_CLASS" => "")
	, "20" => array("ID" => "20", "NAME" => "Папки", "FOLDER" => "folders", "IMAGE" => "folders.jpg", "CSS_CLASS" => "")
	, "21" => array("ID" => "21", "NAME" => "Падове и кубчета", "FOLDER" => "padove", "IMAGE" => "padove.jpg", "CSS_CLASS" => "")
	, "22" => array("ID" => "22", "NAME" => "Планшети", "FOLDER" => "planchettes", "IMAGE" => "planchettes.jpg", "CSS_CLASS" => "")
	, "23" => array("ID" => "23", "NAME" => "Плакати", "FOLDER" => "posters", "IMAGE" => "posters.jpg", "CSS_CLASS" => "")
	, "24" => array("ID" => "24", "NAME" => "Плотиране", "FOLDER" => "plotting", "IMAGE" => "plotting.jpg", "CSS_CLASS" => "")
	, "25" => array("ID" => "25", "NAME" => "Подложки за мишки", "FOLDER" => "mousepads", "IMAGE" => "mousepad.jpg", "CSS_CLASS" => "")
	, "26" => array("ID" => "26", "NAME" => "Пощенски пликове", "FOLDER" => "envelopes", "IMAGE" => "envelopes.jpg", "CSS_CLASS" => "")
	, "27" => array("ID" => "27", "NAME" => "Преге", "FOLDER" => "prege2", "IMAGE" => "prege2.jpg", "CSS_CLASS" => "")
	, "28" => array("ID" => "28", "NAME" => "Работни календари", "FOLDER" => "rcalendar", "IMAGE" => "kal3.jpg", "CSS_CLASS" => "")
	, "29" => array("ID" => "29", "NAME" => "Ситопечат", "FOLDER" => "screenPrinting", "IMAGE" => "screenPrinting.jpg", "CSS_CLASS" => "")
	, "30" => array("ID" => "30", "NAME" => "Табелки", "FOLDER" => "badges", "IMAGE" => "tabelki-pvc.jpg", "CSS_CLASS" => "")
	, "31" => array("ID" => "31", "NAME" => "Тампонен печат", "FOLDER" => "tampon", "IMAGE" => "tampon.jpg", "CSS_CLASS" => "")
	, "32" => array("ID" => "32", "NAME" => "Тефтери", "FOLDER" => "prege1", "IMAGE" => "prege1.jpg", "CSS_CLASS" => "")
	, "33" => array("ID" => "33", "NAME" => "Тиксо", "FOLDER" => "scotch", "IMAGE" => "scotch.jpg", "CSS_CLASS" => "")
	, "34" => array("ID" => "34", "NAME" => "Tрансферен печат", "FOLDER" => "digitalprint", "IMAGE" => "transfer.jpg", "CSS_CLASS" => "")
	, "35" => array("ID" => "35", "NAME" => "Фактурници", "FOLDER" => "invoice", "IMAGE" => "invoice.jpg", "CSS_CLASS" => "")
	, "36" => array("ID" => "36", "NAME" => "Флок и флекс", "FOLDER" => "flockflex", "IMAGE" => "flockflex.jpg", "CSS_CLASS" => "")
	, "37" => array("ID" => "37", "NAME" => "Хартиени торби", "FOLDER" => "paperBags", "IMAGE" => "paperBags.jpg", "CSS_CLASS" => "")
	, "38" => array("ID" => "38", "NAME" => "Чаши", "FOLDER" => "print-cups", "IMAGE" => "mugs.jpg", "CSS_CLASS" => "")
	, "39" => array("ID" => "39", "NAME" => "Широкоформатен печат", "FOLDER" => "wideprinting", "IMAGE" => "wideprinting.jpg", "CSS_CLASS" => "")
	, "40" => array("ID" => "40", "NAME" => "CD календари", "FOLDER" => "cd-calendars", "IMAGE" => "CD-calendars.jpg", "CSS_CLASS" => "")
	, "41" => array("ID" => "41", "NAME" => "CD и DVD", "FOLDER" => "cd_dvd", "IMAGE" => "cd dvd.jpg", "CSS_CLASS" => "")
	, "42" => array("ID" => "42", "NAME" => "PVC карти", "FOLDER" => "pvc-card", "IMAGE" => "PVC_karti.jpg", "CSS_CLASS" => "")
	, "43" => array("ID" => "43", "NAME" => "Директен UV печат", "FOLDER" => "uv_printing", "IMAGE" => "Direct-UV.jpg ", "CSS_CLASS" => "")
	, "44" => array("ID" => "44", "NAME" => "Банери", "FOLDER" => "banners", "IMAGE" => "blank.jpg", "CSS_CLASS" => "")
	, "45" => array("ID" => "45", "NAME" => "Дизайн и предпечат", "FOLDER" => "design_prepress", "IMAGE" => "Design.jpg", "CSS_CLASS" => "")
	, "46" => array("ID" => "46", "NAME" => "Рекламни сувенири", "FOLDER" => "advertising_souvenirs", "IMAGE" => "blank.jpg", "CSS_CLASS" => "")
	, "47" => array("ID" => "47", "NAME" => "РА Дигитален печат", "FOLDER" => "pa-digital-print", "IMAGE" => "digital-print.jpg", "CSS_CLASS" => "error-select")
	, "48" => array("ID" => "48", "NAME" => "РА Широкоформатен печат", "FOLDER" => "pa-wideprinting", "IMAGE" => "wideprinting.jpg", "CSS_CLASS" => "error-select")
	, "49" => array("ID" => "49", "NAME" => "РА CD и DVD", "FOLDER" => "pa_cd_dvd", "IMAGE" => "cd dvd.jpg", "CSS_CLASS" => "error-select")
);
$GLOBALS["TERMS_HTML"] = '
<p style="padding: 5px;">
<strong><u>Срок за изработка:</u></strong><br>
	Срок за печат до <span id="w_days">5 работни дни</span> след одобрение на проекта.

</p> 		
<p style="padding: 5px;">
<strong><u>Плащане:</u></strong><br>
Поръчката за печат се стартира след <span id="trm_advance">50%</span> авансово плащане и при получаване на продукта – <span id="trm_surcharge">50%</span>.<br>
При използване на готови рекламни продукти същите се заплащат 100% авансово.
</p>

<p style="padding: 5px;">
<strong><u>Валидност на офертата:</u></strong><br>
20 дни от датата на издаването
</p>
<p style="padding: 5px;">
<strong><u>Цени:</u></strong><br>
<ul>
	<li>Всички посочени цени в настоящата оферта са <span id="trm_vat">без начислен ДДС</span>, ако не е отбелязано друго.</li>
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
<span id="trm_delivery_place"></span>
</p>

<div style="clear: both;"></div>
';
//print "pass: ".md5('123a@')."<br>";
//print "pass: ".md5('123@')."<br>";

?>
