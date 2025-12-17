<?php
class ModelPatners {
	private $db;
	public function __construct()  
  {  
  	global $db;
  	$this->db = &$db;
  } 
	
	public function getPatners($filter = array()) {
	
		$limit = "";
		$flt = "";
		if (isset($filter["filter_partner_name"]) && !empty($filter["filter_partner_name"])) {
			$flt .= " and (PName like '%".$filter["filter_partner_name"]."%')";
		}
		if (isset($filter["filter_partner_type"]) && !empty($filter["filter_partner_type"])) {
			$flt .= " and (PType = '".(int)$filter["filter_partner_type"]."')";
		}
		if (isset($filter["page_limit"])) {
			$limit = $filter["page_limit"];
		}
		$query = " select * from partners where (1) ".$flt." order by PName asc ".$limit;
		$patners = array();
		$this->db->prepare($query);
		if($this->db->query()) {
			while ($this->db->fetch()){
				$patners[$this->db->Record["partner_id"]] = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $patners;
		
	}
	public function getTotalPatners($filter = array()) {
		$flt = "";
		if (isset($filter["filter_partner_name"]) && !empty($filter["filter_partner_name"])) {
			$flt .= " and (PName like '%".$filter["filter_partner_name"]."%')";
		}
		if (isset($filter["filter_partner_type"]) && !empty($filter["filter_partner_type"])) {
			$flt .= " and (PType = '".(int)$filter["filter_partner_type"]."')";
		}
		
		$cnt = 0;
		$query = " select count(partner_id) as cnt from partners where (1) ".$flt;
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
	public function getPatner($partner_id) {
	
		$query = " select * from partners where partner_id='".$this->db->escape($partner_id)."'";
		$partner = array(
			"partner_id" => ""
			, "PType" => ""
			, "PName" => ""
			, "PCountry" => ""
			, "PCity" => ""
			, "PAddr" => ""
			, "PMol" => ""
			, "PBulstat" => ""
			, "PZDDS" => ""
			, "PPerson" => ""
			, "PPhone" => ""
			, "PEmail" => ""
			, "PCAddr" => ""
			, "PWebsite" => ""
			, "dt_created" => ""
			, "dt_modified" => ""
			, "uid_created" => ""
			, "uid_modified" => ""
		);
		$this->db->prepare($query);
		if($this->db->query()) {
			if ($this->db->fetch()){
				$partner = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $partner;
		
	}
	public function updatePatner($partner_id, $data) {
	
		if (count($data)) {
			if (empty($data["PName"])) {
					msg_add("Полето <b>Име</b> не е попълнено!", MSG_ERROR);
					return false;
			}

			if ($partner_id) {
				$query = " 
				update  partners set 
					PType = '".$this->db->escape($data["PType"])."'
					, PName = '".$this->db->escape($data["PName"])."'
					, PCountry = '".$this->db->escape($data["PCountry"])."'
					, PCity = '".$this->db->escape($data["PCity"])."' 
					, PAddr = '".$this->db->escape($data["PAddr"])."' 
					, PMol = '".$this->db->escape($data["PMol"])."' 
					, PBulstat = '".$this->db->escape($data["PBulstat"])."' 
					, PZDDS = '".$this->db->escape($data["PZDDS"])."' 
					, PPerson = '".$this->db->escape($data["PPerson"])."' 
					, PPhone = '".$this->db->escape($data["PPhone"])."' 
					, PEmail = '".$this->db->escape($data["PEmail"])."' 
					, PCAddr = '".$this->db->escape($data["PCAddr"])."' 
					, PWebsite = '".$this->db->escape($data["PWebsite"])."' 
					, dt_modified = NOW()
					, uid_modified = '".$this->db->escape($_SESSION["uid"])."'
				where 
					partner_id ='".$this->db->escape($partner_id)."' 
				";
				$this->db->prepare($query);
				if(!$this->db->query()) {
					msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
					return false;
				} else {
					msg_add("Клиента беше редактиран успешно!", MSG_SUCCESS);
					return $partner_id;
				}
				
			} else {
				$query = " 
				insert into  partners set 
					PType = '".$this->db->escape($data["PType"])."'
					, PName = '".$this->db->escape($data["PName"])."'
					, PCountry = '".$this->db->escape($data["PCountry"])."'
					, PCity = '".$this->db->escape($data["PCity"])."' 
					, PAddr = '".$this->db->escape($data["PAddr"])."' 
					, PMol = '".$this->db->escape($data["PMol"])."' 
					, PBulstat = '".$this->db->escape($data["PBulstat"])."' 
					, PZDDS = '".$this->db->escape($data["PZDDS"])."' 
					, PPerson = '".$this->db->escape($data["PPerson"])."' 
					, PPhone = '".$this->db->escape($data["PPhone"])."' 
					, PEmail = '".$this->db->escape($data["PEmail"])."' 
					, PCAddr = '".$this->db->escape($data["PCAddr"])."' 
					, PWebsite = '".$this->db->escape($data["PWebsite"])."' 
					, dt_created = NOW()
					, uid_created = '".$this->db->escape($_SESSION["uid"])."'
				";
				$this->db->prepare($query);
				if(!$this->db->query()) {
					msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
					return false;
				} else {
					msg_add("Клиента беше добавен успешно!", MSG_SUCCESS);
					$partner_id = $this->db->insert_id();
					return $partner_id;
				}
			
			}
		} else {
			msg_add("Грешка при предаване на данните!", MSG_ERROR);
			return false;
		}

	}
	public function detelePatner($partner_id) {
	
		if ($partner_id) {
				$can_delete = false;

					$query = " 
					select partner_id from docs 
					where 
						partner_id ='".$this->db->escape($partner_id)."' 
					";
					$this->db->prepare($query);
					if ($this->db->query()) {
						if ($this->db->num_rows() > 0 ) {
							msg_add("Този клиент има връзка с документите и не може да бъде изтрит!", MSG_WARNING);
							return false;
						}
					} else {
						msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
						return false;
					}

					$query = " 
					delete from partners 
					where 
						partner_id ='".$this->db->escape($partner_id)."' 
					";
			
					$this->db->prepare($query);
					if(!$this->db->query()) {
						msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
						return false;
					} else {
						msg_add("Клиента беше изтрит успешно!", MSG_SUCCESS);
						return true;
					}
					
		
		}
		
	}
		
}
?>