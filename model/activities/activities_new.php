<?php
class ModelActivities {
	private $db;
	public function __construct()  
  {  
  	global $db;
  	$this->db = &$db;
  } 

  public function getTotal($filter = array(), $uid = "")  {
		$flt = "";
/*
		if (isset($filter["filter_case_type"]) && $filter["filter_case_type"] != '*') {
			$flt .= "and case_type = '".$this->db->escape($filter["filter_case_type"])."'";
		}
		
		if (isset($filter["filter_case_from_num"]) && !empty($filter["filter_case_from_num"])) {
			$flt .= "and case_sym >= '".$this->db->escape($filter["filter_case_from_num"])."'";
		}

		if (isset($filter["filter_case_to_num"]) && !empty($filter["filter_case_to_num"])) {
			$flt .= "and case_sym <= '".$this->db->escape($filter["filter_case_to_num"])."'";
		}

		if (isset($filter["filter_case_from_date"]) && !empty($filter["filter_case_from_date"])) {
			$filter["filter_case_from_date"] = dt_convert($filter["filter_case_from_date"], DT_BG, DT_SQL, DT_DATE);
			$flt .= "and case_date >= '".$this->db->escape($filter["filter_case_from_date"])."'";
		}

		if (isset($filter["filter_case_to_date"]) && !empty($filter["filter_case_to_date"])) {
			$filter["filter_case_to_date"] = dt_convert($filter["filter_case_to_date"], DT_BG, DT_SQL, DT_DATE);
			$flt .= "and case_date <= '".$this->db->escape($filter["filter_case_to_date"])."'";
		}
*/

		$cnt = 0;
		$query = "
			select 
				count(d.doc_id) as cnt
			from docs as d
			left join card_items as ci on ((ci.doc_id = d.doc_id) ".($uid?" and (ci.uid='".$this->db->escape($uid)."')":"").") 
			left join supplier_items as si on ((si.doc_id = d.doc_id) ".($uid?" and (si.uid='".$this->db->escape($uid)."')":"").")
			left join partners as p on (p.partner_id = d.partner_id)
			where 
				d.DType='".DOC_TYPE_ORDER."' 
				and (ci.doc_id or si.doc_id) 
				".$flt."
			order by  d.DocNum desc
		 ";
		$this->db->prepare($query);
		if($this->db->query()) {
			if ($this->db->fetch()){
				$cnt = $this->db->Record["cnt"];
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return 0;
		}
		return $cnt;
	
	}
	
	public function getActivities($filter = array(), $uid = "") {
			$limit = "";
			$flt = "";
			/*
			if (isset($filter["filter_case_type"]) && $filter["filter_case_type"] != '*') {
				$flt .= "and case_type = '".$this->db->escape($filter["filter_case_type"])."'";
			}
			
			if (isset($filter["filter_case_from_num"]) && !empty($filter["filter_case_from_num"])) {
				$flt .= "and case_sym >= '".$this->db->escape($filter["filter_case_from_num"])."'";
			}

			if (isset($filter["filter_case_to_num"]) && !empty($filter["filter_case_to_num"])) {
				$flt .= "and case_sym <= '".$this->db->escape($filter["filter_case_to_num"])."'";
			}

			if (isset($filter["filter_case_from_date"]) && !empty($filter["filter_case_from_date"])) {
				$filter["filter_case_from_date"] = dt_convert($filter["filter_case_from_date"], DT_BG, DT_SQL, DT_DATE);
				$flt .= "and case_date >= '".$this->db->escape($filter["filter_case_from_date"])."'";
			}

			if (isset($filter["filter_case_to_date"]) && !empty($filter["filter_case_to_date"])) {
				$filter["filter_case_to_date"] = dt_convert($filter["filter_case_to_date"], DT_BG, DT_SQL, DT_DATE);
				$flt .= "and case_date <= '".$this->db->escape($filter["filter_case_to_date"])."'";
			}
*/
		$this->card_status = "";
		if (isset($filter["filter_card_status"])) {
			$this->card_status = $filter["filter_card_status"];
		}

			if (isset($filter["page_limit"])) {
				$limit .= $filter["page_limit"];
			}
	/*		
			$query = " 
				select 
					d.doc_id
					, d.partner_id
					, d.uid
					, d.DType
					, d.Docdate
					, d.DocNum
					, p.PName 
				from docs as d
				left join card_items as ci on ((ci.doc_id = d.doc_id) ".($uid?" and (ci.uid='"._sqln($uid)."')":"").") 
				left join supplier_items as si on ((si.doc_id = d.doc_id) ".($uid?" and (si.uid='"._sqln($uid)."')":"").")
				left join partners as p on (p.partner_id = d.partner_id)
				where 
					d.DType='".DOC_TYPE_ORDER."' 
					and (ci.doc_id or si.doc_id) 
					".$flt."
				order by  d.Docdate desc
			".$limit;
*/

		$query = " 
		select 
      d.doc_id
      , d.partner_id
      , d.uid
      , d.DType
      , d.Docdate
      , d.DocNum
      , p.PName 
      , ci.card_date
      , ci.card_notes
      , ci.card_status
      , CONCAT(m.member_name, ' ', m.member_family) as fullName, m.member_login
			from 
      card_items as ci 
      inner join docs as d on ((d.doc_id = ci.doc_id) ".($uid?" and (ci.uid='"._sqln($uid)."')":"").")  
      left join partners as p on (p.partner_id = d.partner_id)  
      left join members as m on (m.uid = ci.uid)
		where 
		  d.DType='".DOC_TYPE_ORDER."' 
    ".$flt."
		order by d.Docdate desc, d.doc_id desc
		".$limit;
print $query." <br>";
/*
if ($_SERVER['REMOTE_ADDR'] == '89.106.109.91'){ 
//			group by d.doc_id 

print "query [".$query."] <br>";					
}
*/

			$_activities = array();
			$activities = array();
			$this->db->prepare($query);
			if($this->db->query()) {
				while ($this->db->fetch()){
				  print "test --> <br>";
				/*
if ($_SERVER['REMOTE_ADDR'] == '89.106.109.91'){ 
print "Test [".$this->db->Record['DocNum']." ( ".$this->db->Record["doc_id"].")] <br>";					
}
*/
					$this->db->Record["DTotal"] = bg_money($this->db->Record["DTotal"]);
          $_activities = $this->db->Record;
          /*
					$activities[$this->db->Record["doc_id"]] = $this->db->Record;
					$activities[$this->db->Record["doc_id"]]["ITEM_A"] = array();
					$activities[$this->db->Record["doc_id"]]["ITEM_C"] = array();
					$activities[$this->db->Record["doc_id"]]["ITEM_OPTIONS"] = array();*/
				}
			} else {
				msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
				return false;
			}
			
			if (count($activities)) {
			//	if ($_SERVER['REMOTE_ADDR'] != '89.106.109.91'){ 
					foreach($activities as $doc_id => $item) {
					//	$activities[$doc_id]["ITEM_OPTIONS"] = $this->getDocOptions($doc_id);
						$activities[$doc_id]["ITEM_A"] = $this->getDocCards($doc_id, $uid);
						$activities[$doc_id]["ITEM_C"] = $this->getDocSuppliers($doc_id, $uid);
					}
			//	}
			}
			printArray($_activities);
			return $_activities;
	}

	public function getDoc($doc_id, $uid = "") {
	
		$query = " select 
		d.*, m.member_login, 
		p.PName
		from 
		docs as d 
		left join members as m on (m.uid = d.uid) 
		left join partners as p on (p.partner_id = d.partner_id)
		
		where d.doc_id='".$this->db->escape($doc_id)."'";
		$today = date('Y-m-d');
		$today_after = strtotime($today);
		$today_after = strtotime("+6 day", $today_after);
		$today_after_day = date('Y-m-d', $today_after);

				$today = dt_convert($today, DT_SQL, DT_BG, DT_DATE);
				$today_after_day = dt_convert($today_after_day, DT_SQL, DT_BG, DT_DATE);

		$doc = array(
			"doc_id" => ""
			, "partner_id" => ""
			, "from_doc_id" => ""
			, "uid" => ""
			, "PName" => ""
			, "DType" => DOC_TYPE_OFFER
			, "DStatus" => DOC_ORDER_STATUS_N
			, "DocNum" => ""
			, "Docdate" => $today
			, "DocPeceiptDate" => $today_after_day
			, "DTotal" => ""
			, "DPayment" => ""
			, "DAdvance" => ""
			, "DSurcharge" => ""
			, "DCorrections" => ""
			, "DCAddr" => ""
			, "DNotes" => ""
			, "member_login" => ""
			, "dt_created" => ""
			, "dt_modified" => ""
			, "uid_created" => ""
			, "uid_modified" => ""
			, "doc_options" => array()
			, "doc_cards" => array()
			, "doc_suppliers" => array()
		);
		$this->db->prepare($query);
		if($this->db->query()) {
			if ($this->db->fetch()){
				$this->db->Record["Docdate"] = dt_convert($this->db->Record["Docdate"], DT_SQL, DT_BG, DT_DATE);
				$this->db->Record["DocPeceiptDate"] = dt_convert($this->db->Record["DocPeceiptDate"], DT_SQL, DT_BG, DT_DATE);
				$this->db->Record["DTotal"] = bg_money($this->db->Record["DTotal"]);
			
				$doc = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		
		//get doc option
		$doc['doc_options'] = $this->getDocOptions($doc_id);
		$doc['doc_cards'] = $this->getDocCards($doc_id, $uid);
		$doc['doc_suppliers'] = $this->getDocSuppliers($doc_id, $uid);
		
		return $doc;
		
	}
	
	public function getDocOptions($doc_id) {
		$doc_options = array();
		$query = " select * from doc_options where doc_id='".$this->db->escape($doc_id)."'";
		$this->db->prepare($query);
		if($this->db->query()) {
			while ($this->db->fetch()){
				$doc_options[$this->db->Record["option_id"]] = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $doc_options;
	}

	public function getDocCards($doc_id, $uid) {
		$doc_cards = array();
		$query = " 
		select ci.* 
		, CONCAT(m.member_name, ' ', m.member_family) as fullName, m.member_login
		from 
			card_items as ci 
			left join members as m on (m.uid = ci.uid)  
		where 
		ci.doc_id='".$this->db->escape($doc_id)."'
		".($uid? " and ci.uid='".$this->db->escape($uid)."'":"")." 
		".($this->card_status != ''?" and ci.card_status='"._sqln($this->card_status)."'":"")."
		order by ci.card_date desc 
		";
		$this->db->prepare($query);
		if($this->db->query()) {
			while ($this->db->fetch()){
				$this->db->Record["card_date"] = dt_convert($this->db->Record["card_date"], DT_SQL, DT_BG, DT_DATE);
				$doc_cards[] = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $doc_cards;
	}

	public function getDocSuppliers($doc_id, $uid) {
		$doc_suppliers = array();
		$query = " select 
			sui.* 
			, CONCAT(m.member_name, ' ', m.member_family) as fullName, m.member_login
			, s.CName
			from 
			supplier_items as sui 
			left join members as m on (m.uid = sui.uid)  
			left join suppliers as s on (s.c_id = sui.c_id)  
			where 
			sui.doc_id='".$this->db->escape($doc_id)."'
			".($uid? " and sui.uid='".$this->db->escape($uid)."'":"")." 
			".($this->card_status != ''?" and sui.CStatus='"._sqln($this->card_status)."'":"")."
			order by sui.CDate desc 
			";
		$this->db->prepare($query);
		if($this->db->query()) {
			while ($this->db->fetch()){
				$this->db->Record["CDate"] = dt_convert($this->db->Record["CDate"], DT_SQL, DT_BG, DT_DATE);
				$this->db->Record["CSum"] = bg_money($this->db->Record["CSum"]);
				$doc_suppliers[] = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $doc_suppliers;
	}


	public function updateCard($doc_id){
//////////////////////////////////////////////////////////////////////////////////		
try {
  $this->db->begin();
//////////////////////////////////////////////////////////////////////////////////		
		
		if ($doc_id) {
			$card = _p("card", array());
			$supplier = _p("supplier", array());
//			$card = array();
//			$supplier =  array();
			$can_edit = false;
			$can_finish_order = true;
		if (count($card)) {	

			$query = " 
			select doc_id from card_items 
			where 
				doc_id ='".$this->db->escape($doc_id)."' 
			";
			$this->db->prepare($query);
			if(!$this->db->query()) {
				msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			}
			
			if ($this->db->num_rows()) {
				$query = " 
				delete from card_items 
				where 
					doc_id ='".$this->db->escape($doc_id)."' 
				";
				$this->db->prepare($query);
				if(!$this->db->query()) {
					msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
				}
			
			}
		}


		if (count($supplier)) {	

			$query = " 
			select doc_id from supplier_items 
			where 
				doc_id ='".$this->db->escape($doc_id)."' 
			";
			$this->db->prepare($query);
			if(!$this->db->query()) {
				msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			}
			
			if ($this->db->num_rows()) {
				$query = " 
				delete from supplier_items 
				where 
					doc_id ='".$this->db->escape($doc_id)."' 
				";
	
				$this->db->prepare($query);
				if(!$this->db->query()) {
					msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
				}
			}
		}


			foreach($card as $key => $val) {
				$_card_status = (!empty($val["card_status"])?CARD_STATUS_F:CARD_STATUS_W);
				if($_card_status == CARD_STATUS_W ) $can_finish_order = false;
				$val["card_date"] = dt_convert($val["card_date"], DT_BG, DT_SQL, DT_DATE);
				
					$query = "
						insert into card_items set 
							doc_id = '".$this->db->escape($doc_id)."'
							, uid = '".$this->db->escape((int)$val["uid"])."'
							, card_date = '".$this->db->escape($val["card_date"])."'
							, card_notes = '"._sqls($val["card_notes"])."'
							, card_status = '".$this->db->escape($_card_status)."' 
					";
					$this->db->prepare($query);
					if(!$this->db->query()) {
						msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
						$can_finish_order = false;	
//						lwrite('(7) activities - insert into card_items: ('.$query.') :: '.$this->db->Error);
					//	break;
					} else {
						$can_edit = true;
					}
			}
			
			foreach($supplier as $key => $val) {
				$_CStatus = (!empty($val["CStatus"])?CARD_STATUS_F:CARD_STATUS_W);
				if($val["CStatus"] == CARD_STATUS_W ) $can_finish_order = false;

				$val["CDate"] = dt_convert($val["CDate"], DT_BG, DT_SQL, DT_DATE);
				
				$query = "
					insert into supplier_items set 
						doc_id = '".$this->db->escape($doc_id)."'
						, uid = '".$this->db->escape((int)$val["uid"])."'
						, c_id = '".$this->db->escape((int)$val["c_id"])."'
						, CDate = '".$this->db->escape($val["CDate"])."'
						, CNotes = '".$this->db->escape(trim($val["CNotes"]))."'
						, CSum = '".$this->db->escape((float)$val["CSum"])."' 
						, CStatus = '".$this->db->escape($_CStatus)."' 
				";
					$this->db->prepare($query);
					if(!$this->db->query()) {
						msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
						$can_finish_order = false;	
//						lwrite('(8) activities - insert into supplier_items: ('.$query.') :: '.$this->db->Error);
					//	break;
					} else {
						$can_edit = true;
					}
			
			}
			
			if ($can_edit) {
				msg_add("Информацията е редактирана успешно!", MSG_SUCCESS);
			}
			if ($can_finish_order) {
				$query = "
					update docs set 
						DStatus = '".$this->db->escape(DOC_ORDER_STATUS_E)."'
						, dt_modified = NOW()
						, uid_modified = '".$this->db->escape($_SESSION["uid"])."'
					where 
						doc_id ='".$this->db->escape($doc_id)."'	
				";
				$this->db->prepare($query);
				if(!$this->db->query()) {
					msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
//					lwrite('(9) activities : ('.$query.') :: '.$this->db->Error);
					return false;
				} else {
					msg_add("<b style=\"color:red;\">Поръчката е приключена успешно!</b>", MSG_SUCCESS);
				}
				
			}
			
		}
//////////////////////////////////////////////////////////////////////////////////		
  $this->db->commit();		
}catch ( Exception $e ) {
  $this->db->rollback();
}	
//////////////////////////////////////////////////////////////////////////////////		
	}
	
	public function __updateCard($doc_id){
		if ($doc_id) {
			$card = _p("card", array());
			$supplier = _p("supplier", array());
//			$card = array();
//			$supplier =  array();
			$can_edit = false;
			$can_finish_order = true;
			if (!count($card)) {
				$query = "select * from card_items where doc_id ='".$this->db->escape($doc_id)."'";
				$data = array();
				$this->db->prepare($query);
				if($this->db->query()) {
					while ($this->db->fetch()){
						$data[] = $this->db->Record;
					}
				} else {
					//msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
				//	lwrite('(1) activities - Serialize card_items: (doc_id = '.$doc_id.') :: '.$this->db->Error);
				//	return false;
				}
				
				if (count($data)) {
					ldbwrite(
										array('doc_id' => $doc_id
										, 'table_name' => 'card_items'
										, 'error_from' => 'Дейнсоти'
										, 'lost_data' => $data
										, 'post_data' => $card
										)
					);
				}
				//lwrite('(2) activities - cart no items :: Serialize: '.serialize($data));
			}
			if (!count($supplier)) {
				$query = "select * from supplier_items where doc_id ='".$this->db->escape($doc_id)."'";
				$data = array();
				$this->db->prepare($query);
				if($this->db->query()) {
					while ($this->db->fetch()){
						$data[] = $this->db->Record;
					}
				} else {
					//msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
					//lwrite('(3) activities - Serialize supplier_items: (doc_id = '.$doc_id.') :: '.$this->db->Error);
				//	return false;
				}
				
				if (count($data)) {
					ldbwrite(
										array('doc_id' => $doc_id
										, 'table_name' => 'supplier_items'
										, 'error_from' => 'Дейнсоти'
										, 'lost_data' => $data
										, 'post_data' => $supplier
										)
					);
				}
//				lwrite('(4) activities - supplier no items :: Serialize: '.serialize($data));
			}
			/*
			printArray($card);
			printArray($supplier);
			*/
			$query = " 
			delete from card_items 
			where 
				doc_id ='".$this->db->escape($doc_id)."' 
			";

			$this->db->prepare($query);
			if(!$this->db->query()) {
				msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
//				return false;
//				lwrite('(5) activities - card_items: (doc_id = '.$doc_id.') :: '.$this->db->Error);
			}
			$query = " 
			delete from supplier_items 
			where 
				doc_id ='".$this->db->escape($doc_id)."' 
			";

		$this->db->prepare($query);
			if(!$this->db->query()) {
				msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
//				lwrite('(6) activities - supplier_items: (doc_id = '.$doc_id.') :: '.$this->db->Error);
//				return false;
			}

			foreach($card as $key => $val) {
				$_card_status = (!empty($val["card_status"])?CARD_STATUS_F:CARD_STATUS_W);
				if($_card_status == CARD_STATUS_W ) $can_finish_order = false;
				$val["card_date"] = dt_convert($val["card_date"], DT_BG, DT_SQL, DT_DATE);
				
					$query = "
						insert into card_items set 
							doc_id = '".$this->db->escape($doc_id)."'
							, uid = '".$this->db->escape((int)$val["uid"])."'
							, card_date = '".$this->db->escape($val["card_date"])."'
							, card_notes = '"._sqls($val["card_notes"])."'
							, card_status = '".$this->db->escape($_card_status)."' 
					";
					$this->db->prepare($query);
					if(!$this->db->query()) {
						msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
						$can_finish_order = false;	
//						lwrite('(7) activities - insert into card_items: ('.$query.') :: '.$this->db->Error);
					//	break;
					} else {
						$can_edit = true;
					}
			}
			
			foreach($supplier as $key => $val) {
				$_CStatus = (!empty($val["CStatus"])?CARD_STATUS_F:CARD_STATUS_W);
				if($val["CStatus"] == CARD_STATUS_W ) $can_finish_order = false;

				$val["CDate"] = dt_convert($val["CDate"], DT_BG, DT_SQL, DT_DATE);
				
				$query = "
					insert into supplier_items set 
						doc_id = '".$this->db->escape($doc_id)."'
						, uid = '".$this->db->escape((int)$val["uid"])."'
						, c_id = '".$this->db->escape((int)$val["c_id"])."'
						, CDate = '".$this->db->escape($val["CDate"])."'
						, CNotes = '".$this->db->escape(trim($val["CNotes"]))."'
						, CSum = '".$this->db->escape((float)$val["CSum"])."' 
						, CStatus = '".$this->db->escape($_CStatus)."' 
				";
					$this->db->prepare($query);
					if(!$this->db->query()) {
						msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
						$can_finish_order = false;	
//						lwrite('(8) activities - insert into supplier_items: ('.$query.') :: '.$this->db->Error);
					//	break;
					} else {
						$can_edit = true;
					}
			
			}
			
			if ($can_edit) {
				msg_add("Информацията е редактирана успешно!", MSG_SUCCESS);
			}
			if ($can_finish_order) {
				$query = "
					update docs set 
						DStatus = '".$this->db->escape(DOC_ORDER_STATUS_E)."'
						, dt_modified = NOW()
						, uid_modified = '".$this->db->escape($_SESSION["uid"])."'
					where 
						doc_id ='".$this->db->escape($doc_id)."'	
				";
				$this->db->prepare($query);
				if(!$this->db->query()) {
					msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
//					lwrite('(9) activities : ('.$query.') :: '.$this->db->Error);
					return false;
				} else {
					msg_add("<b style=\"color:red;\">Поръчката е приключена успешно!</b>", MSG_SUCCESS);
				}
				
			}
			
		}
	}
	
	
}
?>