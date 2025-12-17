<?php
class ModelContractors {
	private $db;
	public function __construct()  
  {  
  	global $db;
  	$this->db = &$db;
  } 
  
  public function getTotalContractors($filter = array())  {
		$flt = "";

		if (isset($filter["filter_supplier_sector"]) && !empty($filter["filter_supplier_sector"])) {
			$flt .= "and CSector LIKE '%".$this->db->escape($filter["filter_supplier_sector"])."%'";
		}
		if (isset($filter["filter_supplier_name"]) && !empty($filter["filter_supplier_name"])) {
			$flt .= "and CName LIKE '%".$this->db->escape($filter["filter_supplier_name"])."%'";
		}

		$cnt = 0;
		$query = " select count(c_id) as cnt from suppliers where (1) ".$flt;
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
	
	public function getSuppliers($filter = array()) {
			$limit = "";
			$flt = "";
			if (isset($filter["filter_supplier_sector"]) && !empty($filter["filter_supplier_sector"])) {
				$flt .= "and CSector LIKE '%".$this->db->escape($filter["filter_supplier_sector"])."%'";
			}
			if (isset($filter["filter_supplier_name"]) && !empty($filter["filter_supplier_name"])) {
				$flt .= "and CName LIKE '%".$this->db->escape($filter["filter_supplier_name"])."%'";
			}

			if (isset($filter["page_limit"])) {
				$limit .= $filter["page_limit"];
			}
			$query = " 
			select 
				*
				from suppliers 
			where (1) ".$flt."
			order by CName asc
			".$limit;
			$suppliers = array();
			$this->db->prepare($query);
			if($this->db->query()) {
				while ($this->db->fetch()){
					$suppliers[$this->db->Record["c_id"]] = $this->db->Record;
				}
			} else {
				msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
				return false;
			}
			return $suppliers;
	}

	public function getSupplier($c_id) {
			$supplier = array(
			"c_id" => ""
			, "CName" => ""
			, "CCity" => ""
			, "CAddr" => ""
			, "CPerson" => ""
			, "CPhone" => ""
			, "CEmail" => ""
			, "CSector" => ""
			, "CWebsite" => ""
			, "CPriceList" => ""
			, "dt_created" => ""
			, "dt_modified" => ""
			, "uid_created" => ""
			, "uid_modified" => ""
		);
		$query = " select * from suppliers where c_id='".$this->db->escape($c_id)."'";
		
		$this->db->prepare($query);
		if($this->db->query()) {
			if ($this->db->fetch()){
				$supplier = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $supplier;	
	}

	public function updateSupplier($c_id, $data) {
	
		if (count($data)) {
			if (empty($data["CName"])) {
					msg_add("Полето <b>Име</b> не е попълнено!", MSG_ERROR);
					return false;
			}

			if ($c_id) {
				$query = " 
				update  suppliers set 
					CName = '".$this->db->escape($data["CName"])."'
					, CCity = '".$this->db->escape($data["CCity"])."'
					, CAddr = '".$this->db->escape($data["CAddr"])."'
					, CPerson = '".$this->db->escape($data["CPerson"])."' 
					, CPhone = '".$this->db->escape($data["CPhone"])."' 
					, CEmail = '".$this->db->escape($data["CEmail"])."' 
					, CSector = '".$this->db->escape($data["CSector"])."' 
					, CWebsite = '".$this->db->escape($data["CWebsite"])."' 
					, CPriceList = '".$this->db->escape($data["CPriceList"])."' 
					, dt_modified = NOW()
					, uid_modified = '".$this->db->escape($_SESSION["uid"])."'
				where 
					c_id ='".$this->db->escape($c_id)."' 
				";
				$this->db->prepare($query);
				if(!$this->db->query()) {
					msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
					return false;
				} else {
					msg_add("Контрагента беше редактиран успешно!", MSG_SUCCESS);
					return $c_id;
				}
				
			} else {
				$query = " 
				insert into  suppliers set 
					CName = '".$this->db->escape($data["CName"])."'
					, CCity = '".$this->db->escape($data["CCity"])."'
					, CAddr = '".$this->db->escape($data["CAddr"])."'
					, CPerson = '".$this->db->escape($data["CPerson"])."' 
					, CPhone = '".$this->db->escape($data["CPhone"])."' 
					, CEmail = '".$this->db->escape($data["CEmail"])."' 
					, CSector = '".$this->db->escape($data["CSector"])."' 
					, CWebsite = '".$this->db->escape($data["CWebsite"])."' 
					, CPriceList = '".$this->db->escape($data["CPriceList"])."' 
					, dt_created = NOW()
					, uid_created = '".$this->db->escape($_SESSION["uid"])."'
				";
				$this->db->prepare($query);
				if(!$this->db->query()) {
					msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
					return false;
				} else {
					msg_add("Контрагента беше добавен успешно!", MSG_SUCCESS);
					$c_id = $this->db->insert_id();
					return $c_id;
				}
			
			}
		} else {
			msg_add("Грешка при предаване на данните!", MSG_ERROR);
			return false;
		}

	}
	public function deteleSupplier($c_id) {
	
		if ($c_id) {
				$can_delete = false;
/*
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
*/
					$query = " 
					delete from suppliers 
					where 
						c_id ='".$this->db->escape($c_id)."' 
					";
			
					$this->db->prepare($query);
					if(!$this->db->query()) {
						msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
						return false;
					} else {
						msg_add("Контрагента беше изтрит успешно!", MSG_SUCCESS);
						return true;
					}
		}
		
	}
	
	public function clearPrinting() {
    $query = "TRUNCATE TABLE  printing;";
		$this->db->prepare($query);
		$this->db->query();
  }
  
  public function importPrinting($row) {
    $query = "insert into printing set 
      material_type = '".$this->db->escape(iconv('utf-8', 'windows-1251', $row[0]))."',
      size = '".$this->db->escape(iconv('utf-8', 'windows-1251', $row[1]))."',
      print = '".$this->db->escape(iconv('utf-8', 'windows-1251', $row[2]))."',
      drawing = '".$this->db->escape(iconv('utf-8', 'windows-1251', $row[3]))."',
      paper_type = '".$this->db->escape(iconv('utf-8', 'windows-1251', $row[4]))."',
      paper_size = '".$this->db->escape(iconv('utf-8', 'windows-1251', $row[5]))."',
      waste = '".$this->db->escape(iconv('utf-8', 'windows-1251', $row[6]))."',
      installation = '".$this->db->escape(iconv('utf-8', 'windows-1251', $row[7]))."',
      printing_company = '".$this->db->escape(iconv('utf-8', 'windows-1251', $row[8]))."',
      note = '".$this->db->escape(iconv('utf-8', 'windows-1251', $row[9]))."'
    ";
		$this->db->prepare($query);
		$this->db->query();
  }
  
	public function getPrintings($filter = array()) {
			$limit = "";
	    $flt = "";
			$query = " 
			select 
				*
			from printing 
			where (1) ".$flt."
			order by id asc
			".$limit;
			$printings = array();
			$this->db->prepare($query);
			if($this->db->query()) {
				while ($this->db->fetch()){
					$printings[$this->db->Record["id"]] = $this->db->Record;
				}
			} else {
				msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
				return false;
			}
			return $printings;
	}
  
}
?>