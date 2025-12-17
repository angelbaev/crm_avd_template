<?php
class ModelPayoffice {
	private $db;
	public function __construct()  
  {  
  	global $db;
  	$this->db = &$db;
  } 

  public function getTotalCase($filter = array())  {
		$flt = "";

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

		$cnt = 0;
		$query = " select count(case_id) as cnt from company_case where (1) ".$flt;
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
	
	public function getCases($filter = array()) {
			$limit = "";
			$flt = "";
			
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

			if (isset($filter["page_limit"])) {
				$limit .= $filter["page_limit"];
			}
			$query = " 
			select 
				*
				from company_case 
			where (1) ".$flt."
			order by case_id desc, case_date
			".$limit;
			$payoffices = array();
			$this->db->prepare($query);
			if($this->db->query()) {
				while ($this->db->fetch()){
					$this->db->Record["case_date"] =  dt_convert($this->db->Record["case_date"], DT_SQL, DT_BG, DT_DATE);
					$payoffices[$this->db->Record["case_id"]] = $this->db->Record;
				}
			} else {
				msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
				return false;
			}
			return $payoffices;
	}

	public function getCase($case_id) {
			$today = date('Y-m-d');
			$today = dt_convert($today, DT_SQL, DT_BG, DT_DATE);
			
			$payoffice = array(
			"case_id" => ""
			, "case_type" => ""
			, "case_sym" => ""
			, "case_date" => $today
			, "case_title" => ""
			, "case_notes" => ""
			, "dt_created" => ""
			, "dt_modified" => ""
			, "uid_created" => ""
			, "uid_modified" => ""
		);
		$query = " select * from company_case where case_id='".$this->db->escape($case_id)."'";
		
		$this->db->prepare($query);
		if($this->db->query()) {
			if ($this->db->fetch()){
				$this->db->Record["case_date"] = dt_convert($today, DT_SQL, DT_BG, DT_DATE);
				$payoffice = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $payoffice;	
	}

	public function updateCase($case_id, $data) {

		if (count($data)) {
			if (!isset($data["case_type"])) {
					msg_add("Полето <b>Тип</b> не е попълнено!", MSG_ERROR);
					return false;
			}
			$data["case_date"] =  dt_convert($data["case_date"], DT_BG, DT_SQL, DT_DATE);
			if ($case_id) {
				$query = " 
				update  company_case set 
					case_type = '".$this->db->escape($data["case_type"])."'
					, case_sym = '".$this->db->escape($data["case_sym"])."'
					, case_date = '".$this->db->escape($data["case_date"])."'
					, case_title = '".$this->db->escape($data["case_title"])."'
					, case_notes = '".$this->db->escape($data["case_notes"])."'
					, dt_modified = NOW()
					, uid_modified = '".$this->db->escape($_SESSION["uid"])."'
				where 
					case_id ='".$this->db->escape($case_id)."' 
				";
				
				$this->db->prepare($query);
				if(!$this->db->query()) {
					msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
					return false;
				} else {
					msg_add("Приход / Разход  беше редактиран успешно!", MSG_SUCCESS);
					return $case_id;
				}
				
			} else {
				$query = " 
				insert into  company_case set 
					case_type = '".$this->db->escape($data["case_type"])."'
					, case_sym = '".$this->db->escape($data["case_sym"])."'
					, case_date = '".$this->db->escape($data["case_date"])."'
					, case_title = '".$this->db->escape($data["case_title"])."'
					, case_notes = '".$this->db->escape($data["case_notes"])."'
					, dt_created = NOW()
					, uid_created = '".$this->db->escape($_SESSION["uid"])."'
				";
				$this->db->prepare($query);
				if(!$this->db->query()) {
					msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
					return false;
				} else {
					msg_add("Приход / Разход беше добавен успешно!", MSG_SUCCESS);
					$case_id = $this->db->insert_id();
					return $case_id;
				}
			
			}
		} else {
			msg_add("Грешка при предаване на данните!", MSG_ERROR);
			return false;
		}

	}
	public function deteleCase($case_id) {
	
		if ($case_id) {
					$query = " 
					delete from company_case 
					where 
						case_id ='".$this->db->escape($case_id)."' 
					";
					$this->db->prepare($query);
					if(!$this->db->query()) {
						msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
						return false;
					} else {
						msg_add("Приход / Разход беше изтрит успешно!", MSG_SUCCESS);
						return true;
					}
		}
		
	}

  public function getCompanyCase()  {
		$sum_data = array('PROFIT' => '0.00', 'COST' => '0.00');
		$query = " select sum(case_sym) as profit from company_case where case_type='".$this->db->escape(CASE_TYPE_PROFIT)."'";
		$this->db->prepare($query);
		if($this->db->query()) {
			if ($this->db->fetch()){
				$sum_data['PROFIT'] = $this->db->Record["profit"];
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return 0;
		}
		$query = " select sum(case_sym) as cost from company_case where case_type='".$this->db->escape(CASE_TYPE_COST)."'";
		$this->db->prepare($query);
		if($this->db->query()) {
			if ($this->db->fetch()){
				$sum_data['COST'] = $this->db->Record["cost"];
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return 0;
		}

		return $sum_data;
	
	}
	
	
	
	/*----------------------------------------*/
	
  public function getTotalNewCase($filter = array())  {
		$flt = "";
			if (isset($filter["filter_case_partner_id"]) && $filter["filter_case_partner_id"] != '*') {
				$flt .= "and c.partner_id = '".$this->db->escape($filter["filter_case_partner_id"])."'";
			}
			if (isset($filter["filter_case_payment_type"]) && $filter["filter_case_payment_type"] != '*') {
				$flt .= "and c.case_payment_type = '".$this->db->escape($filter["filter_case_payment_type"])."'";
			}
			if (isset($filter["filter_case_user_id"]) && $filter["filter_case_user_id"] != '*') {
				$flt .= "and c.partner_id = '".$this->db->escape($filter["filter_case_user_id"])."'";
			}
			if (isset($filter["filter_case_type"]) && $filter["filter_case_type"] != '*') {
				$flt .= "and c.case_type = '".$this->db->escape($filter["filter_case_type"])."'";
			}
			
			if (isset($filter["filter_case_doc_num"]) && !empty($filter["filter_case_doc_num"])) {
				$flt .= "and d.DocNum like '%".$this->db->escape($filter["filter_case_doc_num"])."%'";
			}
			if (isset($filter["filter_case_date_of_payment"]) && !empty($filter["filter_case_date_of_payment"])) {
				$filter["filter_case_to_date"] = dt_convert($filter["filter_case_date_of_payment"], DT_BG, DT_SQL, DT_DATE);
				$flt .= "and c.case_date_of_payment <= '".$this->db->escape($filter["filter_case_date_of_payment"])."'";
			}
			
			
			if (isset($filter["filter_case_from_date"]) && !empty($filter["filter_case_from_date"])) {
				$filter["filter_case_from_date"] = dt_convert($filter["filter_case_from_date"], DT_BG, DT_SQL, DT_DATE);
				$flt .= "and c.case_date >= '".$this->db->escape($filter["filter_case_from_date"])."'";
			}

			if (isset($filter["filter_case_to_date"]) && !empty($filter["filter_case_to_date"])) {
				$filter["filter_case_to_date"] = dt_convert($filter["filter_case_to_date"], DT_BG, DT_SQL, DT_DATE);
				$flt .= "and c.case_date <= '".$this->db->escape($filter["filter_case_to_date"])."'";
			}

		$cnt = 0;
//		$query = " select count(case_id) as cnt from company_new_case as c where (1) ".$flt;
		$query = " 
		select 
		 count(c.case_id) as cnt
			from company_new_case as c 
			left join docs as d on (d.doc_id = c.doc_id) 
      left join suppliers as s on (s.c_id = c.partner_id) 
      left join members as m on (m.uid = c.uid) 
		where (1) ".$flt;
		
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
	
	public function getNewCases($filter = array()) {
			$limit = "";
			$flt = "";

			if (isset($filter["filter_case_partner_id"]) && $filter["filter_case_partner_id"] != '*') {
				$flt .= "and c.partner_id = '".$this->db->escape($filter["filter_case_partner_id"])."'";
			}
			if (isset($filter["filter_case_payment_type"]) && $filter["filter_case_payment_type"] != '*') {
				$flt .= "and c.case_payment_type = '".$this->db->escape($filter["filter_case_payment_type"])."'";
			}
			if (isset($filter["filter_case_user_id"]) && $filter["filter_case_user_id"] != '*') {
				$flt .= "and c.uid = '".$this->db->escape($filter["filter_case_user_id"])."'";
			}
			if (isset($filter["filter_case_type"]) && $filter["filter_case_type"] != '*') {
				$flt .= "and c.case_type = '".$this->db->escape($filter["filter_case_type"])."'";
			}
			
			if (isset($filter["filter_case_doc_num"]) && !empty($filter["filter_case_doc_num"])) {
				$flt .= "and d.DocNum like '%".$this->db->escape($filter["filter_case_doc_num"])."%'";
			}
			if (isset($filter["filter_case_date_of_payment"]) && !empty($filter["filter_case_date_of_payment"])) {
				$filter["filter_case_to_date"] = dt_convert($filter["filter_case_date_of_payment"], DT_BG, DT_SQL, DT_DATE);
				$flt .= "and c.case_date_of_payment <= '".$this->db->escape($filter["filter_case_date_of_payment"])."'";
			}
			
			
			if (isset($filter["filter_case_from_date"]) && !empty($filter["filter_case_from_date"])) {
				$filter["filter_case_from_date"] = dt_convert($filter["filter_case_from_date"], DT_BG, DT_SQL, DT_DATE);
				$flt .= "and c.case_date >= '".$this->db->escape($filter["filter_case_from_date"])."'";
			}

			if (isset($filter["filter_case_to_date"]) && !empty($filter["filter_case_to_date"])) {
				$filter["filter_case_to_date"] = dt_convert($filter["filter_case_to_date"], DT_BG, DT_SQL, DT_DATE);
				$flt .= "and c.case_date <= '".$this->db->escape($filter["filter_case_to_date"])."'";
			}

			if (isset($filter["page_limit"])) {
				$limit .= $filter["page_limit"];
			}
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
			where (1) ".$flt."
			order by c.case_id desc, c.case_date
			".$limit;
		
			$payoffices = array();
			$this->db->prepare($query);
			if($this->db->query()) {
				while ($this->db->fetch()){
					$this->db->Record["case_date"] =  dt_convert($this->db->Record["case_date"], DT_SQL, DT_BG, DT_DATE);
					$this->db->Record["case_date_of_payment"] =  dt_convert($this->db->Record["case_date_of_payment"], DT_SQL, DT_BG, DT_DATE);
					$payoffices[$this->db->Record['case_id']] = $this->db->Record;
				}
			} else {
				msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
				return false;
			}

			return $payoffices;
	}
	
	public function deteleNewCase($case_id) {
	
		if ($case_id) {
					$query = " 
					delete from company_new_case 
					where 
						case_id ='".$this->db->escape($case_id)."' 
					";
					$this->db->prepare($query);
					if(!$this->db->query()) {
						msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
						return false;
					} else {
						msg_add("Приход / Разход беше изтрит успешно!", MSG_SUCCESS);
						return true;
					}
		}
		
	}
	
}
?>