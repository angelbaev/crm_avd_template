<?php 
  header('Content-Type: text/html; charset=windows-1251');
	include("../config.php");
	include_once(DIR_SYSTEM."function.inc.php");
	include_once(DIR_SYSTEM."db.mysql.php");
	include_once(DIR_SYSTEM."pagination.php");
	class ajPage {
		var $act;
		var $json;
		var $errors = array();
		var $partners = array();
		public function __construct() {
			$this->act = _p("act",_g("act",""));
			$this->errors = array();
			$this->json = array();
			switch($this->act) {
				case "restore_session":
					$this->restore_session_id();
					break;
				case "search_partner":
					$this->getPartnerList();
					break;
				case "get_case_info":
				  $this->json = getNewCaseInfo();
          break;	
				case "update_case_info":
				  $this->json = updateNewCaseInfo();
          break;	
				default:
					$this->json['html'] = "";
					break;
			}
			
		}//__construct()

		private function build() {
			switch($this->act) {
				case "restore_session":
					$this->display_session_id();
					break;
				case "search_partner":
					$this->display_partners();
					break;
				default:
					$this->json['html'] = "none";
					break;
			}
		}//build()
		
		private function restore_session_id() {
			$sid = _p("sid","");
			$uid = _p("uid","");
			if (!$sid && !$uid) {
				$this->errors[] = "Няма зададени потребителски номер и сесиен ключ!";
			} else if (!$sid) {
				$this->errors[] = "Няма зададен сесиен ключ!";
			} else if (!$uid) {
				$this->errors[] = "Няма зададен потребителски номер!";
			} else {
				$sid = base64_decode($sid);

				if ($sid) {
					session_id($sid);
				} else {
					session_id();
				}
				$err = login_user($uid);
				if (count($err)){
					$this->errors[] = implode(",", $err);
				}
				
				
			}
		
		}
		
		private function display_session_id() {
			$sid = base64_decode(_p("sid",""));
			if (!count($this->errors)) {
				$this->json['success'] = "Успешно обновяване на сесията!";
			} else {
				$this->json['error'] = implode("<br>", $this->errors);
			}
			//$this->content = __json_encode($json);
		}//display_session_id()
		
		//Partners 
		private function getPartnerList() {
			$PName = _p("pname",_g("pname",""));
			$this->partners = getPartners($PName);
		}
		private function display_partners() {
			if (!count($this->errors)) {
				//$this->json['success'] = "Успешно обновяване на сесията!";
				$this->json['error'] =  "no errors";
				if (count($this->partners)) {
					$this->json['html'] = "<ul>";
					foreach ($this->partners as $key => $partner) {
						$url = HTTP_SERVER."index.php?route=documents&tab=all&token=".get_token()."&filter_doc_partner_id=".$partner["partner_id"];
						$this->json['html'] .= "<li><a href=\"".$url."\"> ".htmlspecialchars($partner["PName"], ENT_QUOTES, 'cp1251')."</a></li>";
					}
					$this->json['html'] .= "</ul>";
				
				} else {
					$this->json['html'] = "
					<ul>
						<li><a href=\"#\">Няма намерени резултати!</a></li>
					</ul>
					";
				}
				/*
				$this->json['html'] = '
								<ul>
									<li><a href="http://abv.bg/">KP0403 &nbsp; Ангел Желязков Баев</a></li>
									<li><a href="#">KF00002 &nbsp; Валентин Стоянов</a></li>
									<li><a href="#">KP0403 &nbsp; Ангел Желязков Баев</a></li>
									<li><a href="#">KF00002 &nbsp; Валентин Стоянов</a></li>
									<li><a href="#">KP0403 &nbsp; Ангел Желязков Баев</a></li>
									<li><a href="#">KF00002 &nbsp; Валентин Стоянов</a></li>
									<li><a href="#">KP0403 &nbsp; Ангел Желязков Баев</a></li>
									<li><a href="#">KF00002 &nbsp; Валентин Стоянов</a></li>
									<li><a href="#">KP0403 &nbsp; Ангел Желязков Баев</a></li>
									<li><a href="#">KF00002 &nbsp; Валентин Стоянов</a></li>
									<li><a href="#">KP0403 &nbsp; Ангел Желязков Баев</a></li>
									<li><a href="#">KF00002 &nbsp; Валентин Стоянов</a></li>
									<li><a href="#">KP0403 &nbsp; Ангел Желязков Баев</a></li>
									<li><a href="#">KF00002 &nbsp; Валентин Стоянов</a></li>
									<li><a href="#">KP0403 &nbsp; Ангел Желязков Баев</a></li>
									<li><a href="#">KF00002 &nbsp; Валентин Стоянов</a></li>
								</ul>
				
				';
				*/
				$this->json['success'] = "";
			} else {
				$this->json['error'] =  implode("<br>", $this->errors);
			}
		}
		
		public function Out() {
			$this->build();
			$uid = _p("uid", "");
			$sid = _p("sid", "");
		//	$this->json['success'] = "dddddddddd: ".$uid." sid: ".$sid."<br>";
			//$this->json['html'] = "";
			print __json_encode($this->json);
		}
	}
	$aj = new ajPage();
	$aj->Out();


function login_user($uid) {
	global $db;
$error = array();

				if ($uid) {
					$query = "
						select 
							uid
							, member_login 
						from members where uid ='".$db->escape((int)$uid)."' LIMIT 1
					";
					
					
					$db->prepare($query);
					if($db->query()) {
						if ($user = $db->fetch()){
							$_SESSION["logged"] = true;
							$_SESSION["uid"] = $user["uid"];
							$_SESSION["username"] = $user["member_login"];
							$_SESSION["token"] = session_id();
							//'Усшено логване ще бъдете пренасочен към началната страница!';
						} else {
							$error[] = "Няма региситририан потребител с този номер <b>".$uid."</b> !";
							//'Няма регистиран потребител с това име <b>'.$uid.'</b>!';
						}
					} else {
							$error[] = "Грешка база данни!";
					}
				}	

		return (count($error)?$error:array());
}	

function getPartners($PName) {
	global $db;
	$partners = array();
	if ($PName) {
		 $PName = mb_convert_encoding ($PName,'windows-1251','UTF-8');
		$query = "
			select 
				p.partner_id, p.PName 
			from partners as p 
			where  
			p.PName LIKE '%".$db->escape($PName)."%'  
		";
		//p.PName LIKE '%".$db->escape($PName)."%' 
		$db->prepare($query);
		if($db->query()) {
			while ($db->fetch()) {
				$partners[] = array("partner_id" => $db->Record["partner_id"], "PName" => $db->Record["PName"]);
			}	
		}
		//printArray($partners);
		return $partners;
	} else {
		return array();
	}
}

function getNewCaseInfo(){
	global $db;
	$case_info = array();
	
  $case_id = _p("case_id",_g("case_id",""));
  
 
		$query = "
			select 
				c.*,
				d.DocNum,
				s.CName, 
				CONCAT( m.member_name, ' ',  m.member_family) as UName
				from company_new_case as c 
				left join docs as d on (d.doc_id = c.doc_id) 
        left join suppliers as s on (s.c_id = c.partner_id) 
        left join members as m on (m.uid = c.uid) 
			where c.case_id ='".$db->escape($case_id)."'
		";
		//p.PName LIKE '%".$db->escape($PName)."%' 
		$db->prepare($query);
		if($db->query()) {
			if  ($db->fetch()) {
				$case_info = $db->Record;
				$case_info["case_date"] =  dt_convert($case_info["case_date"], DT_SQL, DT_BG, DT_DATE);
				$case_info["case_date_of_payment"] =  dt_convert($case_info["case_date_of_payment"], DT_SQL, DT_BG, DT_DATE);
        $case_info["case_amount_requested"] = bg_money($case_info["case_amount_requested"]);
        $case_info["case_paid_amount"] = bg_money($case_info["case_paid_amount"]);
			}	
		}
  
  return $case_info;
}

function updateNewCaseInfo() {
	global $db;
	
	$case_info = array();
  $case_id = _p("case_id",_g("case_id",""));
  
  if ($case_id) {
  
      $case_paid_amount = _p("cs_paid_amount",_g("cs_paid_amount",""));
      $case_payment_type = _p("cs_payment_type",_g("cs_payment_type",""));
      $case_date_of_payment = _p("cs_date_of_payment",_g("cs_date_of_payment",""));
      $case_type = _p("cs_type",_g("cs_type",""));
      $case_doc_num = _p("cs_doc_num",_g("cs_doc_num",""));
      $case_date_of_payment = dt_convert($case_date_of_payment, DT_BG, DT_SQL, DT_DATE);
			$query = " 
			update  company_new_case set 
				case_paid_amount = '".$db->escape($case_paid_amount)."'
				, case_payment_type = '".$db->escape($case_payment_type)."'
				, case_date_of_payment = '".$db->escape($case_date_of_payment)."'
				, case_type = '".$db->escape($case_type)."'
				, case_doc_num = '".$db->escape($case_doc_num)."'
			where 
        case_id = '".$db->escape($case_id)."'	
			";
  		$db->prepare($query);
  		if(!$db->query()) {
  			msg_add("DB Error: <br>".$db->Error, MSG_ERROR);
  		}
  
  }
}

//login_user("1");
//	print  __json_encode($json);
	
?>