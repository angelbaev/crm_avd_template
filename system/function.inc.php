<?php

$MQ = get_magic_quotes_gpc();

function _p($val, $default = "") {
	global $MQ;
	
	return (isset($_POST[$val])?($MQ?stripslashes_from_array($_POST[$val]):$_POST[$val]):$default);
}//_p()

function _g($val, $default = "") {
	global $MQ;
	return (isset($_GET[$val])?($MQ?stripslashes_from_array($_GET[$val]):$_GET[$val]):$default);
}

function var_copy($a, &$b) {
	if (!is_array($a)) {$b = $a; return;}
	$b = array();
	foreach($a as $key => $val) var_copy($val, $b[$key]);
}

function var_clone($a) {
	if (!is_array($a)) {return $a;}
	$b = array();
	foreach($a as $key => $val) $b[$key] = var_clone($val);
	return $b;
}

function __addslashes_to_array($a) {// Private! Used only from addslashes_to_array
	if (!is_array($a)) return addslashes($a);
	foreach($a as $key => $val) $a[$key] = __addslashes_to_array($val);
	return ($a);
}
function addslashes_to_array($a) {
	return __addslashes_to_array(var_clone($a));
}

function __stripslashes_from_array($a) {// Private! Used only from stripslashes_from_array
	if (!is_array($a)) return stripslashes($a);
	foreach($a as $key => $val) $a[$key] = __stripslashes_from_array($val);
	return ($a);
}

function stripslashes_from_array($a) {
	return __stripslashes_from_array(var_clone($a));
}

function _h($s) {
	return htmlspecialchars($s);
}

function _fnes($str) {
	return (!empty($str)?$str:"&nbsp;");
}

function dt_convert($dt, $from, $to, $format = DT_DATE) {
	if ($format == DT_DATETIME) {
		if ($from == DT_SQL && $to == DT_BG) {
			$_dt = date("d.m.Y H:i:s", strtotime($dt));
		} else if ($from == DT_BG && $to == DT_SQL) {
			$_dt = date("Y-m-d H:i:s", strtotime($dt));
		} else {
			$_dt = "0000-00-00 00:00:00";
		}
	} else {
		if ($from == DT_SQL && $to == DT_BG) {
      if ($dt == "0000-00-00") $dt = "";
			$_dt = date("d.m.Y", strtotime($dt));

			if($_dt == "01.01.1970") $_dt = "";
		} else if ($from == DT_BG && $to == DT_SQL) {
			$_dt = date("Y-m-d", strtotime($dt));
			if($_dt == "1970-01-01") $_dt = "0000-00-00";
		} else {
			$_dt = "0000-00-00";
		}
	}
	return 	$_dt;
}

function _sqls($str) {
	return strtr($str, array(
		"\\" => "\\\\"
		, "'" => "\\'"
		, "\"" => "\\\""
		, "`" => "\\`"
		, "\t" => "\\t"
		, "\r" => "\\r"
		, "\n" => "\\n"
		, "\x00" => "\\0"
		, "\x1A" => "\\Z")
	);
}
//echo _sqls("ala 'vvfjvnjfv'");

function __sqls_array($a) {// Private! Used only from _sqls_array
	if (!is_array($a)) return _sqls($a);
	foreach($a as $key => $val) $a[$key] = __sqls_array($val);
	return ($a);
}
function _sqls_array($a) {
	return __sqls_array(var_clone($a));
}

function __sqld_0($type="date") {
	switch ($type) {
		case "datetime" :
			$ret = "0000-00-00 00:00:00";
			break;
		case "time" :
			$ret = "00:00:00";
			break;
		case "date" :
		default :
			$ret = "0000-00-00";
	}
	return $ret;	
}

function _sqln($num) {
	return ($num == "")?0:_sqls($num);
}

function _sqld($date,$type="date") {
	return ($date == "")
		?__sqld_0($type)
		:_sqls($date);
}

function _sqlnull($val) {
	return ($val != "")?"'"._sqls($val)."'":"NULL";
}

function _sqlt($val, $type = "string") {
	switch ($type) {
		case "integer" :
		case "float" :
			$ret = _sqln($val);
			break;
		case "date" :
		case "datetime" :
		case "time" :
			$ret = _sqld($val,$type);
			break;
		case "string" :
		default :
			$ret = _sqls($val);
	}
	return $ret;	
}

function __sqlt($val, $type = "string", $null = false) {
	if ($null && ($val == "")) return "NULL";
	else return "'"._sqlt($val, $type)."'";
}

function outArray($a, $r=-1) {
	if (!is_array($a)) return "";
	if (!$r) return "array(".count($a).")";
	reset($a);
	$html="<b>Array[]:</b><br>\n";
	$html.="<table border=1 cellspacing=0 cellpadding=2>\n".
		"<tr><td><p><b>Key</b></td><td><p><b>Value</b></td></tr>\n";
	while (list ($key, $val) = each ($a)) {
		$html.=
			"<tr><td valign=top>".$key."</td><td ".(is_array($val)?"":"style=\"white-space: pre;\"").">".
			(is_array($val)
				?outArray($val,$r-1)
				:(is_object($val)?"(object)":
					(is_null($val)?"(null)":
						(($val === "")?"&nbsp;":_h($val))
					)
				)).
			"</td></tr>\n";
	}
	$html.="</table>\n";
	return $html;
}

function printArray($a) {
	print outArray($a);
}

function is_login() {
	if (!isset($_SESSION["uid"])) {
		session_defaults();
	}
	return $_SESSION["logged"];
}
function session_defaults() {
	$_SESSION["logged"] = false;
	$_SESSION["uid"] = 0;
	$_SESSION["username"] = "";
	$_SESSION["token"] = 0;
}

function get_template($file_name) {
	if (file_exists(DEFAULT_TEMPLATE.$file_name)) {
		include_once(DEFAULT_TEMPLATE.$file_name);
	} 
}

function set_class($className) {
		$_dir = "";	
		$_file_include = "";	
		$_className = "Controller";	
		switch ($className) {
			case "documents":
				$_dir = "documents";	
				$_file_include = "documents.php";	
				$_className = "Controller".ucfirst ($className);	
				break;
			case "reports":
				$_dir = "reports";	
				$_file_include = "reports.php";	
				$_className = "Controller".ucfirst ($className);	
				break;
			case "patners":
				$_dir = "patners";	
				$_file_include = "patners.php";	
				$_className = "Controller".ucfirst ($className);	
				break;
			case "contractors":
				$_dir = "contractors";	
				$_file_include = "contractors.php";	
				$_className = "Controller".ucfirst ($className);	
				break;
			case "payoffice":
				$_dir = "payoffice";	
				$_file_include = "payoffice.php";	
				$_className = "Controller".ucfirst ($className);	
				break;
			case "activities":
				$_dir = "activities";	
				$_file_include = "activities.php";	
				$_className = "Controller".ucfirst ($className);	
				break;
			case "activities_new":
				$_dir = "activities_new";	
				$_file_include = "activities_new.php";	
				$_className = "Controller".ucfirst ($className);	
				break;
			case "settings":
				$_dir = "settings";	
				$_file_include = "settings.php";	
				$_className = "Controller".ucfirst ($className);	
				break;
			case "login":
				$_dir = "common";	
				$_file_include = "login.php";	
				$_className = "Controller".ucfirst ($className);	
				break;
			case "dashboard":
				default:
				$_dir = "common";	
				$_file_include = "home.php";	
				$_className = "Controller".ucfirst ("Home");	
				break;
		}

		@require_once(DIR_CONTROLLER.$_dir."/".$_file_include);
		
    if(class_exists($_className)) return new $_className;

    die('Cannot create new "'.$className.'" class - includes not found or class unavailable.');

}

function canDoOnSystem($resurce) {
	global $db;
	$token = _p("token", _g("token",""));
	if (empty($token)) {
		msg_add("Достъп отказан липсва ключ!", MSG_ERROR);
		return false;
	} else {
		$token = base64_decode ($token);
	}
	$query = "select * from members where status='1' and uid='".$db->escape($_SESSION["uid"])."'";

	$db->prepare($query);
	if($db->query()) {
		if ($user_info = $db->fetch()) {
	//	printArray($user_info);
			$GLOBALS["USER_INFO"]["uid"] = $user_info["uid"];
			$GLOBALS["USER_INFO"]["role_id"] = $user_info["role_id"];
			$GLOBALS["USER_INFO"]["session_id"] = $user_info["session_id"];
		}
	} else {
		msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
		return false;
	}
		
	if ($GLOBALS["USER_INFO"]["session_id"] != $token) {
		msg_add("Достъп отказан сесийните ключове не съвпадат!", MSG_ERROR);
		return false;
	}
	
	if (!empty($GLOBALS["USER_INFO"]["role_id"])) {
		switch ($resurce) {
			case "documents":
				$dt_field = "documents";
				break;
			case "reports":
				$dt_field = "reports";
				break;
			case "patners":
				$dt_field = "patners";
				break;
			case "contractors":
				$dt_field = "contractors";
				break;
			case "contractors":
				$dt_field = "contractors";
				break;
			case "payoffice":
				$dt_field = "payoffice";
				break;
			case "settings":
				$dt_field = "settings";
				break;
			case "dashboard":
			default:
				$dt_field = "dashboard";
				break;
		}
		if ($dt_field == "dashboard") {
			return true;
		} else {
			$query = "select role_id, `".$dt_field."` from perms where role_id='".$db->escape($GLOBALS["USER_INFO"]["role_id"])."'";
			$db->prepare($query);
			if($db->query()) {
				if ($role = $db->fetch()) {
//					print "ROLE: ".$role[$dt_field]."<bR>";
					if (!empty($role[$dt_field])) {
						return true;
					} else {
						msg_add("Нямате права за достъп!", MSG_ERROR);
						return false;
					}
				}
			} else {
				msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
				return false;
			}
			
		}
	} else {
		msg_add("Нямате права за достъп!", MSG_ERROR);
		return false;
	}
	
}

function get_token() {
	if (isset($_SESSION["token"])) {
		return base64_encode($_SESSION["token"]);
	} else {
		return "";
	}
}

function get_user() {
	return (isset($_SESSION["username"]) && !empty($_SESSION["username"])? $_SESSION["username"]:"default");
}

function get_uid() {
	return (isset($_SESSION["uid"]) && !empty($_SESSION["uid"])? $_SESSION["uid"]:0);
}

$cb_num = 0;
function input_cb($name, $value, $ro=false, $id="", $label="", $onclick="") {
	global $cb_num;
	if (!isset($cb_num)) $cb_num = 0;
	$tx = ++$cb_num;
	if (!$id) $id = "cb_".$tx;
	$h_id = "hd_".$id; 
	$onclick = " onclick=\"document.getElementById('$h_id').value=this.checked?1:0; {$onclick}\"";

	$rq = html_entity_decode("&raquo;");

	if($label === "") $pos = ""; elseif(preg_match("/[{$rq}]\$/",$label)) $pos = "l"; else $pos = "r";
	$html =
		(($pos == "l")?"&nbsp;<label for=\"{$id}\">"._h($label)."</label>":"").
		"<input class=\"icb\" type=\"checkbox\" id=\"{$id}\" ".($value?" checked":"").$onclick.($ro?" disabled":"")." >".
		(($pos == "r")?"&nbsp;<label for=\"{$id}\">"._h($label)."</label>":"").
		input_h($name, $value?1:0, $h_id);

	return $html;
}

function input_h($name, $val = "", $id = "") {
	$html = "<input name=\"".$name."\" type=hidden value=\""._h($val)."\"".($id?" id=\"$id\"":"")."/>";
	return $html;
} // input

function displayErrors() {
	$errors = "";
	if (count($GLOBALS["MSG"])) {
		foreach($GLOBALS["MSG"] as $key => $item){
			$errors .= $item;
		}
	}
	return $errors;
}//displayErrors()

function displayMessages($messages) {
	print "<script type=\"text/javascript\">\n";
	foreach ($messages as $id => $msg) {
		print "msg_add('".$msg['msg']."', '".$msg['type']."'); \n";
	}
	print "</script>\n";
}

function get_record_info($table_name, $column_name, $record_id) {
	global $db;
	
	$INFO = array(
		"OWNER" => "<b>Собственик:</b> "
		, "CREATED" => "<b>Съставил:</b> "
		, "MODIFIED" => "<b>Модифицирал:</b> "
	);
	//, IF(t1.dt_created, t1.dt_created, '0000-00-00 00:00:00') as dt_created 
	$query = "
		select 
			t1.*
			, m.member_login
			, m.member_name
			, m.member_family
			, r.role_name
		from `".$table_name."` as t1 
		left join members as m on (m.uid = t1.uid_created)
		left join roles as r on (r.role_id = m.role_id) 
		where 
			t1.`{$column_name}`='".$record_id."'
		LIMIT 1 ";
		$db->prepare($query);
		if($db->query()) {
			if ($db->fetch()) {
				$dt_created = (isset($db->Record["dt_created"])? $db->Record["dt_created"]:"");
				$INFO["OWNER"] .= $db->Record["member_name"]. " ".$db->Record["member_family"]." <span style='color: green;'>(".$db->Record["role_name"].".".$db->Record["member_login"].") </span> ";
				$INFO["CREATED"] .= $db->Record["member_name"]. " ".$db->Record["member_family"]."  <span style='color: green;'>(".$db->Record["role_name"].".".$db->Record["member_login"].") </span> ".$dt_created;
			}
		}
		print $db->Error;
	$query = "
		select 
			t1.*
			, m.member_login
			, m.member_name
			, m.member_family
			, r.role_name
		from `".$table_name."` as t1 
		left join members as m on (m.uid = t1.uid_modified)
		left join roles as r on (r.role_id = m.role_id) 
		where 
			t1.`{$column_name}`='".$record_id."'
		LIMIT 1 ";
		$db->prepare($query);
		if($db->query()) {
			if ($db->fetch()) {
				$dt_modified = (isset($db->Record["dt_modified"])? $db->Record["dt_modified"]:"");
				$INFO["MODIFIED"] .= $db->Record["member_name"]. " ".$db->Record["member_family"]." <span style='color: blue;'>(".$db->Record["role_name"].".".$db->Record["member_login"].") </span> ".$dt_modified;
			}
		}
		
		print '<div style="font-size: 10px;">'.$INFO["OWNER"].'</div>';
		print '<div style="font-size: 10px;">'.$INFO["CREATED"].'</div>';
		print '<div style="font-size: 10px;">'.$INFO["MODIFIED"].'</div>';
}

function cSelect($name, $id, $val="", $oth ="") {
	$html = "".
	"<select id=\"".$id."\" name=\"".$name."\" class=\"ie240s\" ".$oth.">";
		foreach ($GLOBALS["SYSTEM_CALC"] as $id => $item) {
			$sel = $item["ID"] == $val ? " selected":"";
			$html .= "<option value=\"".$item["ID"]."\"".$sel." class=\"".$item["CSS_CLASS"]."\"> ".addslashes($item["NAME"])." </option>";
		}
	$html .= "	</select>";
	return $html;
}

function userSelect($name, $id, $val="", $oth ="", $all = false) {
	global $db;
	
	$users = array();
		$db->prepare("select uid, member_name, member_family from members where status='1' order by member_name");
		if($db->query()) {
			while ($db->fetch()) {
				$users[$db->Record["uid"]] = $db->Record;
			}
		}
	$html = "".
	"<select id=\"".$id."\" name=\"".$name."\" class=\"ie240\" ".$oth.">";
		if ($all) {
			$html .= "<option value=\"*\"> - Всички - </option>";
		}
		foreach ($users as $id => $item) {
			$sel = $item["uid"] == $val ? " selected":"";
			$html .= "<option value=\"".$item["uid"]."\"".$sel."> ".addslashes($item["member_name"]." ".$item["member_family"])." </option>";
		}
	$html .= "	</select>";
	return $html;
}

function supplierSelect($name, $id, $val="", $oth ="", $all = false) {
	global $db;
	
	$suppliers = array();
		$db->prepare("select c_id, CName from suppliers order by CName");
		if($db->query()) {
			while ($db->fetch()) {
				$suppliers[$db->Record["c_id"]] = $db->Record;
			}
		}
	$html = "".
	"<select id=\"".$id."\" name=\"".$name."\" class=\"ie240\" ".$oth.">";
		if ($all) {
			$html .= "<option value=\"*\"> - Всички - </option>";
		}
		foreach ($suppliers as $id => $item) {
			$sel = $item["c_id"] == $val ? " selected":"";
			$html .= "<option value=\"".$item["c_id"]."\"".$sel."> ".addslashes($item["CName"])." </option>";
		}
	$html .= "	</select>";
	return $html;
}

function canDoOnOrder($doc_id) {
	global $db;
	$_id = 0;
	$query = "
		select 
			doc_id
		from docs 
		where 
			from_doc_id='"._sqln($doc_id)."'
		LIMIT 1 ";
		$db->prepare($query);
		if($db->query()) {
			if ($db->fetch()) {
				$_id = $db->Record["doc_id"];
			}
		}
		return $_id;
}

function canDoOnDelete() {
	global $db;
	$can_delete = false;
	$query = "
		select r.role_can_del from roles as r 
		left join members as m on (m.role_id = r.role_id) 
			where 
                                status='1' and 
				m.uid ='"._sqln(get_uid())."' 
		LIMIT 1";
		$db->prepare($query);
		if($db->query()) {
			if ($db->fetch()) {
				$can_delete = (!empty($db->Record["role_can_del"])?true:false);
			}
		}
	return $can_delete;
	
}

if (!function_exists('__json_encode'))
{
  function __json_encode($a=false)
  {
  
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    
    if (is_scalar($a))
    {
      if (is_float($a))
      {
        // Always use "." for floats.
        return floatval(str_replace(",", ".", strval($a)));
      }

      if (is_string($a))
      {
        static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
//        static $jsonReplaces = array(array("\\", '"'), array('\\\\','\"'));
        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }
      else
        return $a;
    }
    $isList = true;
    for ($i = 0, reset($a); $i < count($a); $i++, next($a))
    {
      if (key($a) !== $i)
      {
        $isList = false;
        break;
      }
    }
    $result = array();
    if ($isList)
    {
      foreach ($a as $v) $result[] = __json_encode($v);
      return '[' . implode(',', $result) . ']';
    }
    else
    {
      foreach ($a as $k => $v){ 
      //$v = htmlspecialchars($v)	;
			$result[] = __json_encode($k).':'.__json_encode($v);
			
			}
      return '{' . implode(',', $result) . '}';
    }

  }
  
}

function windows_1251($str) {
 $str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
 return html_entity_decode($str,null,'windows-1251');
}

function _clear($str) {
	return strtr($str, array(
		"\\" => "\\\\"
		, "'" => "\\'"
		, "\"" => "\\\""
		, "\"" => "\'"
		, "`" => "\\`"
		, "\t" => "\\t"
		, "\r" => "\\r"
		, "\n" => "\\n"
		, "\x00" => "\\0"
		, "\x1A" => "\\Z")
	);
}

function get_filter() {
	$url = '';
	$filter_doc_num = _g("filter_doc_num","");
	$filter_doc_status = _g("filter_doc_status","*");
	$filter_doc_user = _g("filter_doc_user","*");
	$filter_doc_partner_id = _g("filter_doc_partner_id","*");
	$filter_doc_type = _g("filter_doc_type","*");

	$filter_supplier_sector = _g("filter_supplier_sector","");
	$filter_supplier_name = _g("filter_supplier_name","");

	$filter_case_type = _g("filter_case_type","*");
	$filter_case_from_date = _g("filter_case_from_date","");
	$filter_case_to_date= _g("filter_case_to_date","");
	$filter_case_from_num = _g("filter_case_from_num","");
	$filter_case_to_num = _g("filter_case_to_num","");
	
	$filter_partner_name = _g("filter_partner_name","");
	$filter_partner_type = _g("filter_partner_type","*");
	
	$filter_card_status = _g("filter_card_status","*");

	$filter_activity_date = _g("filter_activity_date","*");
	$filter_activity_period = _g("filter_activity_period","*");
	
	$filter_uid = _g("filter_uid","*");
	$filter_supplier_id = _g("filter_supplier_id","*");
        
	if (!empty($filter_doc_num)){
		$url .= "&filter_doc_num=".$filter_doc_num;
	}

	if ($filter_doc_status != '*'){
		$url .= "&filter_doc_status=".$filter_doc_status;
	}
	if ($filter_doc_user != '*'){
		$url .= "&filter_doc_user=".$filter_doc_user;
	}
	if ($filter_doc_partner_id != '*'){
		$url .= "&filter_doc_partner_id=".$filter_doc_partner_id;
	}
	if ($filter_doc_type != '*'){
		$url .= "&filter_doc_type=".$filter_doc_type;
	}
	//supplier
	if (!empty($filter_supplier_sector)){
		$url .= "&filter_supplier_sector=".$filter_supplier_sector;
	}
	if (!empty($filter_supplier_name)){
		$url .= "&filter_supplier_name=".$filter_supplier_name;
	}

	//case
	if ($filter_case_type != '*'){
		$url .= "&filter_case_type=".$filter_case_type;
	}
	if (!empty($filter_case_from_date)){
		$url .= "&filter_case_from_date=".$filter_case_from_date;
	}
	if (!empty($filter_case_to_date)){
		$url .= "&filter_case_to_date=".$filter_case_to_date;
	}
	if (!empty($filter_case_from_num)){
		$url .= "&filter_case_from_num=".$filter_case_from_num;
	}
	if (!empty($filter_case_to_num)){
		$url .= "&filter_case_to_num=".$filter_case_to_num;
	}

//partners
	if (!empty($filter_partner_name)){
		$url .= "&filter_partner_name=".$filter_partner_name;
	}
	if ($filter_partner_type != '*'){
		$url .= "&filter_partner_type=".$filter_partner_type;
	}

	if ($filter_card_status != '*'){
		$url .= "&filter_card_status=".$filter_card_status;
	}

	if ($filter_activity_date != '*'){
		$url .= "&filter_activity_date=".$filter_activity_date;
	}

        if ($filter_activity_period != '*'){
		$url .= "&filter_activity_period=".$filter_activity_period;
	}

        if ($filter_uid != '*'){
		$url .= "&filter_uid=".$filter_uid;
	}

        if ($filter_supplier_id != '*'){
		$url .= "&filter_supplier_id=".$filter_supplier_id;
	}
	return $url;
}

function build_filter() {
	$filter_data = array();
	$filter_data["filter_doc_num"] = _g("filter_doc_num","");
	$filter_data["filter_doc_status"] = _g("filter_doc_status","*");
	$filter_data["filter_doc_user"] = _g("filter_doc_user","*");
	$filter_data["filter_doc_partner_id"] = _g("filter_doc_partner_id","*");
	$filter_data["filter_doc_type"] = _g("filter_doc_type","*");

	$filter_data["filter_supplier_sector"] = _g("filter_supplier_sector","");
	$filter_data["filter_supplier_sector"] = mb_convert_encoding ($filter_data["filter_supplier_sector"],'windows-1251','UTF-8'); 
	$filter_data["filter_supplier_name"] = _g("filter_supplier_name","");
	$filter_data["filter_supplier_name"] = mb_convert_encoding ($filter_data["filter_supplier_name"],'windows-1251','UTF-8'); 

	$filter_data["filter_case_type"] = _g("filter_case_type","*");
	$filter_data["filter_case_to_date"] = _g("filter_case_to_date","");
	$filter_data["filter_case_from_date"] = _g("filter_case_from_date","");
	$filter_data["filter_case_from_num"] = _g("filter_case_from_num","");
	$filter_data["filter_case_to_num"] = _g("filter_case_to_num","");


	$filter_data["filter_partner_name"] = mb_convert_encoding (_g("filter_partner_name",""),'windows-1251','UTF-8');
	$filter_data["filter_partner_type"] = _g("filter_partner_type","*");

	$filter_data["filter_card_status"] = _g("filter_card_status","*");

	$filter_data["filter_activity_date"] = _g("filter_activity_date","*");

	$filter_data["filter_activity_period"] = _g("filter_activity_period","*");

        $filter_data["filter_case_partner_id"] = _g("filter_case_partner_id","*");
	$filter_data["filter_case_payment_type"] = _g("filter_case_payment_type","*");
	$filter_data["filter_case_user_id"] = _g("filter_case_user_id","*");
	$filter_data["filter_case_type"] = _g("filter_case_type","*");

	$filter_data["filter_case_doc_num"] = _g("filter_case_doc_num","");
	$filter_data["filter_case_from_date"] = _g("filter_case_from_date","");
	$filter_data["filter_case_date_of_payment"] = _g("filter_case_date_of_payment","");
	$filter_data["filter_case_from_date"] = _g("filter_case_from_date","");
	$filter_data["filter_case_to_date"] = _g("filter_case_to_date","");
	$filter_data["filter_uid"] = _g("filter_uid","*");
	$filter_data["filter_supplier_id"] = _g("filter_supplier_id","*");

	return $filter_data;
}

function bg_money($_number, $pression = 2) {
	$_number = (float) $_number;
	return str_replace(",", "", number_format($_number, $pression));	
}

function categoryTree($category_id = 0, $level = 0) {                                                                                                 
	global $connect;                                                                                                                                    
	                                                                                                                                                    
	$cagetory = array();                                                                                                                                
	$query = "SELECT * FROM `dt_category` WHERE parent_id = '".$category_id."' ORDER BY `".(empty($category_id)?"category_id":"category_order")."` ASC";
	$result = mysql_query($query) or die (mysql_error());                                                                                               
	$level++;                                                                                                                                           
	while ($row = mysql_fetch_assoc($result)) {                                                                                                         
		$cagetory[$row["category_id"]] = $row;                                                                                                            
		$cagetory[$row["category_id"]]["children"] = array();                                                                                             
		$cagetory[$row["category_id"]]["children"] = categoryTree($row["category_id"], $level);                                                           
	}                                                                                                                                                   
		return $cagetory;                                                                                                                                 
}//categoryTree()                                                                                                                                     


function redirect($url, $sec = 4) {
	$html = "
<html>\n
  <head>\n
  <meta http-equiv=\"content-type\" content=\"text/html; charset=windows-1251\">\n
	<meta http-equiv=\"refresh\" content=\"".$sec.";url=".$url."\"> \n
  <title>Препращане ...</title>\n
  </head>\n
  <body>\n

  </body>\n
</html>\n
	";
	print $html;
}

function lwrite($msg) {

	$msg = strtr($msg, array(
		"\\" => ""
		, "\t" => ""
		, "\r" => ""
		, "\n" => "")
	);

	$script_name = __FILE__;
	$time = @date('[d/M/Y:H:i:s]');
	$content = "".$time." (".$script_name.") "._sqls($msg)."\n";
	$fp = fopen(DIR_BASE."mysql_log.txt","a+");
	fputs($fp,$content); 
	fclose($fp);	
}

function avd_error_log($msg) {

	$msg = strtr($msg, array(
		"\\" => ""
		, "\t" => ""
		, "\r" => ""
		, "\n" => "")
	);

	$time = @date('[d/M/Y:H:i:s]');
	$content = "".$time." : "._sqls($msg)."\n";
	$fp = fopen(DIR_BASE."error_log.txt","a+");
	fputs($fp,$content); 
	fclose($fp);	
}

function ldbwrite($params = array()) {
	global $db;
	if (count($params)) {
	$params['doc_id'] = (isset($params['doc_id'])?$params['doc_id']:'');
	$params['table_name'] = (isset($params['table_name'])?$params['table_name']:'');
	$params['lost_data'] = (isset($params['lost_data'])?$params['lost_data']:'');
//	$params['post_data'] = (isset($params['post_data'])?$params['post_data']:'');
	$params['post_data'] = getBrowser();
	
	$params['error_from'] = (isset($params['error_from'])?$params['error_from']:'');
	$params['post_all'] = serialize($_POST);

	if (count($params['lost_data'])) {
		$params['lost_data'] = serialize($params['lost_data']);
	}
	if (count($params['post_data'])) {
		$params['post_data'] = serialize($params['post_data']);
	}

	$query = "
		insert into logs set 
			doc_id = '"._sqln($params['doc_id'])."'
			, uid = '"._sqln(get_uid())."'
			, `table_name` = '"._sqls($params['table_name'])."'
			, lost_status = '"._sqln('1')."'
			, error_from = '"._sqls($params['error_from'])."'
			, lost_data = '"._sqls($params['lost_data'])."'
			, post_data = '"._sqls($params['post_data'])."'
			, post_all = '"._sqls($params['post_all'])."'
			, lost_time = NOW()
		";
		$db->prepare($query);
		if($db->query()) {
			/*
			if ($db->fetch()) {
				$can_delete = (!empty($db->Record["role_can_del"])?true:false);
			}
			*/
		} else {
			lwrite("LOG ERROR: insert into logs set ");
		}
	}
}//ldbwrite

function lost_data() {
	global $db;
	
	$data = array();
	$query = "
		select 
			l.* 
			, m.member_login 
			, d.DocNum
		from  
			logs as l 
			left join members as m on (m.uid = l.uid)
			left join docs as d on (d.doc_id = l.doc_id)
		where 
			l.lost_status = '1' order by l.log_id desc LIMIT 100
		";
		$db->prepare($query);
		if($db->query()) {
			while ($db->fetch()) {
				$data[$db->Record["log_id"]] = $db->Record; 
			}
		} else {
			lwrite("LOG ERROR: select data ");
		}
	return $data;
}

function getUserById($uid) {
	global $db;
	$user = array();
	
	$query = "
		select 
			uid 
			, member_name 
			, member_family 
		from  
			members 
		where 
			uid = '"._sqln($uid)."' LIMIT 1
		";
		$db->prepare($query);
		if($db->query()) {
			if ($db->fetch()) {
				$user = $db->Record;
			}
		} else {
			lwrite("LOG ERROR: getUserById ");
		}
	return $user['member_name']." ".$user['member_family'];
}

function getSupplierById($cid) {
	global $db;
	$supplier = array();
	$query = "
		select 
			* 
		from  
			suppliers 
		where 
			c_id = '"._sqln($cid)."' LIMIT 1
		";
		$db->prepare($query);
		if($db->query()) {
			if ($db->fetch()) {
				$supplier = $db->Record;
			}
		} else {
			lwrite("LOG ERROR: getSupplierById ");
		}
	return $supplier['CName'];

}

function getTimeTrackerDuration() {
	global $db;
 
  $key_yyyy_mm_dd = date('Y-m-d');
  $query = "select * from timetraker where key_yyyy_mm_dd = '".$key_yyyy_mm_dd."' and user_id = '".(int) get_uid()."'";
		$db->prepare($query);
		if($db->query()) {
			if ($db->fetch()) {
				$timetracker = $db->Record;
			}
		} else {
			lwrite("LOG ERROR: getSupplierById ");
		}
//		return $timetracker['time_duration'];
		return $timetracker['time_duration'];
}

function getWorkTime() {
	global $db;
 
  $key_yyyy_mm_dd = date('Y-m-d');
  $query = "select * from timetraker where key_yyyy_mm_dd = '".$key_yyyy_mm_dd."' and user_id = '".(int) get_uid()."'";
		$db->prepare($query);
		if($db->query()) {
			if ($db->fetch()) {
				$timetracker = $db->Record;
			}
		} else {
			lwrite("LOG ERROR: getSupplierById ");
		}
		$w_time = (date("U", strtotime($timetracker['end_time'])) - date("U", strtotime($timetracker['start_time'])));
//		return $timetracker['time_duration'];
		return  gmdate("H:i:s", $w_time);

}

function saveTimeTrackerDuration() {
	global $db;
 
  $key_yyyy_mm_dd = date('Y-m-d');
  $query = "update timetraker set time_duration = '".(getTimeTrackerDuration()+$_SESSION["WORK_TIME_DURATION"])."', end_time = '".date('Y-m-d H:i:s')."' where key_yyyy_mm_dd = '".$key_yyyy_mm_dd."' and user_id = '".(int) get_uid()."'";
  //print $query;
  $db->prepare($query);
  $db->query();
  $_SESSION["WORK_TIME_START"] = time();  
}

function getBrowser($agent = NULL) {
	if (is_null($agent)) $agent = $_SERVER['HTTP_USER_AGENT']; 
	$subclass = "MSIE,Firefox,Konqueror,Safari,Opera,Netscape,Navigator,Lynx,Amaya,Omniweb";
	$superclass = "Gecko,Mozilla,Mosaic,Webkit";
	$subclassregex = "(?:" . str_replace(",",")|(?:",$subclass) . ")";
	$superclassregex = "(?:" . str_replace(",",")|(?:",$subclass) . ")";

	$b = array(
		"name" => "unrecognized"
		, "majorversion" => 0
		, "version" => "0.0"
	);
	
	$bsuperclassmatch = FALSE;
	$bsubclassmatch = preg_match("/(" . $subclassregex . ")(?:\D*)(\d*)((?:\.\d*)*)/i", $agent, $matches);
	if (!$bsubclassmatch)
		$bsuperclassmatch = preg_match("/(" . $superclassregex . ")(?:\D*)(\d*)((?:\.\d*)*)/i", $agent, $matches);
// $matches[1] - browser
// $matches[2] - major version
// $matches[3] - sub version
	if ($bsubclassmatch||$bsuperclassmatch){
		$b["name"] = strtolower($matches[1]);
		$b["majorversion"] = (int)$matches[2];
		$b["version"] = $matches[2].$matches[3];
		if (!strcasecmp($b["name"], "Safari")){
			$safarimatch = preg_match("/Version\/(\d*)((?:\.\d*)*)/i", $agent, $safarimatches);
			if ($safarimatch){
				$b["majorversion"] = (int)$safarimatches[1];
				$b["version"] = $safarimatches[1].$safarimatches[2];
			}
		}
	}	
	return $b;
}

function isForgottenActivityEnabled($uid) {
	global $db;
	$user = array();
	
	$query = "
		select 
			uid 
			, member_name 
			, member_family
                        , show_forgotten_activity
		from  
			members 
		where 
			uid = '"._sqln($uid)."' LIMIT 1
		";
		$db->prepare($query);
		if($db->query()) {
			if ($db->fetch()) {
				$user = $db->Record;
			}
		} else {
			lwrite("LOG ERROR: getUserById ");
		}
                
	return intval($user['show_forgotten_activity']) === 1;
}

function isForgotten($data) {
     return ((strtotime($data["activity_date"]) < strtotime("-1 day")) && ($data["status"] == CARD_STATUS_W) && isForgottenActivityEnabled($data["uid"]));
}

function seconds_from_time($time) {
	list($h, $m, $s) = explode(':', $time);
	return ($h * 3600) + ($m * 60) + $s;
}
function time_from_seconds($seconds) {
	$h = floor($seconds / 3600);
	$m = floor(($seconds % 3600) / 60);
	$s = $seconds - ($h * 3600) - ($m * 60);
	return sprintf('%02d:%02d:%02d', $h, $m, $s);
}

function array_unique_associative($array) {
  $result = array_map("unserialize", array_unique(array_map("serialize", $array)));
  
  return $result;
}
function super_unique($array){
  $result = array_map("unserialize", array_unique(array_map("serialize", $array)));

  foreach ($result as $key => $value)
  {
    if ( is_array($value) )
    {
      $result[$key] = super_unique($value);
    }
  }

  return $result;
}


class OAL_Manager {
	private static $_instance = NULL;
	private $doc_id;
	private $user_id;
	private $db;
	private $request;
	private $_actions = array();

	private function __construct() {
		global $db;
		$this->db = & $db;      
		$this->doc_id = (isset($_GET['doc_id'])?$_GET['doc_id']:0);
		$this->user_id = (isset($_SESSION['uid'])?$_SESSION['uid']:0);
	}
	
	public function run() {
		if($this->isModuleEnabled()) {
			$this->actionHandlers();
		}
	}//run()
	
	private function setRoute() {
  /*
		global $request;
		
		$route = (isset($this->request->request['route'])?$this->request->request['route']:'');
		if (stripos($route, 'sale/order') !== false) {
			$route = str_replace('sale/order', 'sale/morder', $route);
			if(isset($this->request->request['route'])) { 
				$this->request->request['route'] = $route;
				$this->request->get['route'] =$this->request->request['route'];
			}
			$request = $this->request;
		}
	  */
	}
	
	private function clearActionHandlers() {
		$this->_actions = array();
	}//clearActionHandlers()

	private function setActionHandler($action, $method) {
		$this->_actions[$action] = $method;
	}//setActionHandler()

	private function getActionHandler($action) {
		return (isset($this->_actions[$action])?$this->_actions[$action]:false);
	}//getActionHandler()
	
	private function _execute() {
		foreach($this->_actions as $action => $_method) {
			if(method_exists($this, $_method)) {
				$this->$_method();
			}
		}
	}
	
	private function _unlock() {
		if (!empty($this->user_id) && empty($this->doc_id)) {
      $sql = "SELECT * FROM  `lock` where uid = '".(int)$this->user_id."'";
      $this->db->prepare($sql);
      if($this->db->query()) {
       while ($this->db->fetch()) {
          $sql = "DELETE FROM `lock` where uid = '".(int)$this->user_id."' and doc_id = '".(int)$this->db->Record["doc_id"]."'";
        //   @mysql_query($sql) or die (mysql_error()); 
          $this->db->prepare($sql);
          $this->db->query();
       }
      } else {
        lwrite("LOG ERROR: _unlock ");
      }
		}	
	}//_unlock()

	private function _lock() {
//		$action = (isset($_GET['action'])?$_GET['action']:'');
//		if($action == 'unlock') return;
		if (!empty($this->user_id) && !empty($this->doc_id)) {
      //$doc_id = $doc_id1 = 0;
      $sql = "SELECT * FROM  `lock` where doc_id = '".(int)$this->doc_id."' AND uid != '".(int)$this->user_id."' LIMIT 1";
      $this->db->prepare($sql);
      if($this->db->query()) {
       if ($this->db->fetch()) {
          $doc_id = (int)$this->db->Record["doc_id"];
       }
      } else {
        lwrite("LOG ERROR: _lock ");
      }

      $sql = "SELECT * FROM  `lock` where doc_id = '".(int)$this->doc_id."' AND uid = '".(int)$this->user_id."' LIMIT 1";
      $this->db->prepare($sql);
      if($this->db->query()) {
       if ($this->db->fetch()) {
          $doc_id1 = (int)$this->db->Record["doc_id"];
       }
      } else {
        lwrite("LOG ERROR: _lock ");
      }
      if(!isset($doc_id) && !isset($doc_id1)){
        $sql = "INSERT INTO `lock` set uid = '".(int) $this->user_id."', doc_id ='".(int) $this->doc_id."', lock_time = '".date('Y-m-d H:i:s')."'";
        $this->db->prepare($sql);
        if($this->db->query()) {
        } else {
          lwrite("LOG ERROR: INSERT INTO `lock` ");
        }
        
      }

		}
	}//_lock()
	
	public function isModuleEnabled() {
    return true;
	
	}//isModuleEnabled()
	
	public function isLock($doc_id) {
    $sql = " SELECT doc_id FROM  `lock`  WHERE  uid != '".(int) $this->user_id."' AND doc_id = '".(int) $doc_id."'";

    $this->db->prepare($sql);
    if($this->db->query()) {
     if ($this->db->fetch()) {
        $_doc_id = (int)$this->db->Record["doc_id"];
     }
    } else {
      lwrite("LOG ERROR: isLock ");
    }
 		return (isset($_doc_id)?true:false);
	}//isLock()

	public function getInfo($doc_id) {

    $sql = "SELECT l.uid, l.doc_id, m.member_login, m.member_name, m.member_family FROM `lock` as l LEFT JOIN members as m on (m.uid =l.uid) WHERE l.doc_id = '".(int) $doc_id."' AND l.uid= '".(int) $this->user_id."'" ;
    $info = array();
    $this->db->prepare($sql);
    if($this->db->query()) {
     if ($this->db->fetch()) {
        $info['doc_id'] = (int)$this->db->Record["doc_id"];
        $info['member_login'] = (int)$this->db->Record["member_login"];
        $info['member_name'] = (int)$this->db->Record["member_name"];
        $info['member_family'] = (int)$this->db->Record["member_family"];
     }
    } else {
      lwrite("LOG ERROR: getInfo ");
    }
		if(isset($info['doc_id'])) {
			return ''.$info['member_login'].' ('.$info['member_name'].' '.$info['member_family'].')';
		} 
    
		return '';
	}//getInfo()
	
	public function Icon($doc_id = '') {
		return '<img title="'.$this->getInfo($doc_id).'" src="https://cdn0.iconfinder.com/data/icons/remy-2/512/lock-16.png" style="float:left;margin-left: 0px;margin-top: 2px;" border="0">';	
	}//Icon()
		
	public function actionHandlers() {
		$this->clearActionHandlers();
		$this->setActionHandler('unlock', '_unlock');
		$this->setActionHandler('lock', '_lock');
		$this->_execute();
	}//actionHandlers
	
	public static function getInstance() {
		if(self::$_instance == NULL) self::$_instance = new self();
		return self::$_instance;
	}//getInstance()

}//OAL_Manager()

?>