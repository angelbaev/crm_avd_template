<?php
class ModelReports {
	private $db;
	public function __construct()  
  {  
  	global $db;
  	$this->db = &$db;
  }

	public function getPartners($filter = array()) {
	
		$query = " select * from partners where (1) order by PName";
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

	public function getUsers($filter = array()) {
	
		$query = " select uid, CONCAT(member_name, ' ', member_family) as UName from members where (1) order by UName";
		$users = array();
		$this->db->prepare($query);
		if($this->db->query()) {
			while ($this->db->fetch()){
				$users[$this->db->Record["uid"]] = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $users;
		
	}

	public function getSuppliers($filter = array()) {
	
		$query = " select c_id, CName from suppliers where (1)  order by CName";
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
	 
	public function reportPartners($filter = array()) {
		$flt = "";
		if (isset($filter["report_partner_id"]) && !empty($filter["report_partner_id"])) {
			$flt .= "and d.partner_id = '".$this->db->escape((int)$filter["report_partner_id"])."'";
		}
		/*
		if (isset($filter["report_order_type"]) && !empty($filter["report_order_type"])) {
			$flt .= "and d.DType = '".$this->db->escape((int)$filter["report_order_type"])."'";
		}
*/
		if (isset($filter["report_from_date"]) && !empty($filter["report_from_date"])) {
			$filter["report_from_date"] = dt_convert($filter["report_from_date"], DT_BG, DT_SQL, DT_DATE);
			$flt .= "and d.Docdate >= '".$this->db->escape($filter["report_from_date"])."'";
		}

		if (isset($filter["report_to_date"]) && !empty($filter["report_to_date"])) {
			$filter["report_to_date"] = dt_convert($filter["report_to_date"], DT_BG, DT_SQL, DT_DATE);
			$flt .= "and d.Docdate <= '".$this->db->escape($filter["report_to_date"])."'";
		}

		$query = " 
			select 
				d.doc_id
				, d.DocNum
				, d.Docdate
				, d.DocPeceiptDate
				, d.DType
				, d.DStatus
				, d.DPayment
				, d.DTotal
				, CONCAT(m.member_name, ' ',  m.member_family) as fullName
				,p.partner_id
				, p.PName 
			from 
				docs as d 
				left join partners as p on (p.partner_id = d.partner_id) 
				left join members as m on (m.uid = d.uid)
			where
				 (1)  
				and d.DType = '".$this->db->escape(DOC_TYPE_ORDER)."' 
			".$flt."  
				order by d.Docdate desc, d.DocNum desc
			";
		$report = array();
		$this->db->prepare($query);
		if($this->db->query()) {
			while ($this->db->fetch()){
				$this->db->Record["Docdate"] = dt_convert($this->db->Record["Docdate"], DT_SQL, DT_BG, DT_DATE);
				$this->db->Record["DocPeceiptDate"] = dt_convert($this->db->Record["DocPeceiptDate"], DT_SQL, DT_BG, DT_DATE);
				$this->db->Record["DTotal"] = bg_money($this->db->Record["DTotal"]);
				$report[$this->db->Record["doc_id"]] = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $report;
	} 

	public function reportUsers($filter = array()) {
		$flt = "";
		if (isset($filter["report_user_id"]) && !empty($filter["report_user_id"])) {
			$flt .= "and d.uid = '".$this->db->escape((int)$filter["report_user_id"])."'";
		}
		/*
		if (isset($filter["report_order_type"]) && !empty($filter["report_order_type"])) {
			$flt .= "and d.DType = '".$this->db->escape((int)$filter["report_order_type"])."'";
		}
*/
		if (isset($filter["report_from_date"]) && !empty($filter["report_from_date"])) {
			$filter["report_from_date"] = dt_convert($filter["report_from_date"], DT_BG, DT_SQL, DT_DATE);
			$flt .= "and d.Docdate >= '".$this->db->escape($filter["report_from_date"])."'";
		}

		if (isset($filter["report_to_date"]) && !empty($filter["report_to_date"])) {
			$filter["report_to_date"] = dt_convert($filter["report_to_date"], DT_BG, DT_SQL, DT_DATE);
			$flt .= "and d.Docdate <= '".$this->db->escape($filter["report_to_date"])."'";
		}

		$query = " 
			select 
				d.doc_id
				, d.DocNum
				, d.Docdate
				, d.DocPeceiptDate
				, d.DType
				, d.DStatus
				, d.DPayment
				, d.DTotal
				, CONCAT(m.member_name, ' ',  m.member_family) as fullName
				,p.partner_id
				, p.PName 
			from 
				docs as d 
				left join partners as p on (p.partner_id = d.partner_id) 
				left join members as m on (m.uid = d.uid)
			where
				 (1)  
				and d.DType = '".$this->db->escape(DOC_TYPE_ORDER)."' 
			".$flt."  
				order by d.Docdate desc, d.DocNum desc
			";
		$report = array();
		$this->db->prepare($query);
		if($this->db->query()) {
			while ($this->db->fetch()){
				$this->db->Record["Docdate"] = dt_convert($this->db->Record["Docdate"], DT_SQL, DT_BG, DT_DATE);
				$this->db->Record["DocPeceiptDate"] = dt_convert($this->db->Record["DocPeceiptDate"], DT_SQL, DT_BG, DT_DATE);
				$this->db->Record["DTotal"] = bg_money($this->db->Record["DTotal"]);
				$report[$this->db->Record["doc_id"]] = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $report;
	}
	
	public function reportSuppliers($filter = array()) {
		$flt = "";
		if (isset($filter["report_supplier_id"]) && !empty($filter["report_supplier_id"])) {
			$flt .= "and si.c_id = '".$this->db->escape((int)$filter["report_supplier_id"])."'";
		}
		/*
		if (isset($filter["report_order_type"]) && !empty($filter["report_order_type"])) {
			$flt .= "and d.DType = '".$this->db->escape((int)$filter["report_order_type"])."'";
		}
*/
		if (isset($filter["report_from_date"]) && !empty($filter["report_from_date"])) {
			$filter["report_from_date"] = dt_convert($filter["report_from_date"], DT_BG, DT_SQL, DT_DATE);
			$flt .= "and si.CDate >= '".$this->db->escape($filter["report_from_date"])."'";
//			$flt .= "and d.Docdate >= '".$this->db->escape($filter["report_from_date"])."'";
		}

		if (isset($filter["report_to_date"]) && !empty($filter["report_to_date"])) {
			$filter["report_to_date"] = dt_convert($filter["report_to_date"], DT_BG, DT_SQL, DT_DATE);
			$flt .= "and si.CDate <= '".$this->db->escape($filter["report_to_date"])."'";
//			$flt .= "and d.Docdate <= '".$this->db->escape($filter["report_to_date"])."'";
		}

		$query = " 
			select 
				si.doc_id
				, si.c_id
				, si.uid 
				, si.CStatus 
				, si.CSum 
                                , si.CDate
				
				, s.CName
				, p.PName
				, d.Docdate
				, d.DocNum 
				, d.DType
				, d.DStatus
				, d.DTotal
				, CONCAT(m.member_name, ' ', m.member_family) as fullName
			from 
					supplier_items as si 
					left join suppliers as s on (s.c_id = si.c_id)  
					left join docs as d on (d.doc_id = si.doc_id)
					left join partners as p on (p.partner_id = d.partner_id)
					left join members as m on (m.uid = d.uid)
			where
				 (1)  
				and d.DType = '".$this->db->escape(DOC_TYPE_ORDER)."' 
			".$flt."  
				order by si.CDate desc, si.doc_id desc, d.DocNum desc
			";
		$report = array();
		$this->db->prepare($query);
		if($this->db->query()) {
			while ($this->db->fetch()){
				$this->db->Record["Docdate"] = dt_convert($this->db->Record["Docdate"], DT_SQL, DT_BG, DT_DATE);
				$this->db->Record["CDate"] = dt_convert($this->db->Record["CDate"], DT_SQL, DT_BG, DT_DATE);
				$this->db->Record["DocPeceiptDate"] = dt_convert($this->db->Record["DocPeceiptDate"], DT_SQL, DT_BG, DT_DATE);
				$this->db->Record["DTotal"] = bg_money($this->db->Record["DTotal"]);
				$this->db->Record["CSum"] = bg_money($this->db->Record["CSum"]);
				$report[] = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $report;
	
	} 
	
	public function reportCategories($filter = array()) {
		$flt = "";
		if (isset($filter["report_category_id"]) && !empty($filter["report_category_id"])) {
			$flt .= "and d_o.c_id = '".$this->db->escape((int)$filter["report_category_id"])."'";
		}
		/*
		if (isset($filter["report_order_type"]) && !empty($filter["report_order_type"])) {
			$flt .= "and d.DType = '".$this->db->escape((int)$filter["report_order_type"])."'";
		}
*/
		if (isset($filter["report_from_date"]) && !empty($filter["report_from_date"])) {
			$filter["report_from_date"] = dt_convert($filter["report_from_date"], DT_BG, DT_SQL, DT_DATE);
			$flt .= "and d.Docdate >= '".$this->db->escape($filter["report_from_date"])."'";
		}

		if (isset($filter["report_to_date"]) && !empty($filter["report_to_date"])) {
			$filter["report_to_date"] = dt_convert($filter["report_to_date"], DT_BG, DT_SQL, DT_DATE);
			$flt .= "and d.Docdate <= '".$this->db->escape($filter["report_to_date"])."'";
		}

		$query = " 
			select 
				d_o.doc_id
				, d_o.c_id 
				, d_o.PSum 
				, p.PName
				, d.Docdate
				, d.DocNum 
				, d.DType
				, d.DStatus
				, d.DTotal
				, d.DPayment
				, CONCAT(m.member_name, ' ', m.member_family) as fullName
			from 
					doc_options as d_o 
					left join docs as d on (d.doc_id = d_o.doc_id)
					left join partners as p on (p.partner_id = d.partner_id)
					left join members as m on (m.uid = d.uid)
			where
				 (1)  
				and d.DType = '".$this->db->escape(DOC_TYPE_ORDER)."' 
			".$flt."  
				order by d.Docdate desc, d_o.doc_id desc, d.DocNum desc
			";
		$report = array();
		$this->db->prepare($query);
		if($this->db->query()) {
			while ($this->db->fetch()){
				$this->db->Record["Docdate"] = dt_convert($this->db->Record["Docdate"], DT_SQL, DT_BG, DT_DATE);
				$this->db->Record["DocPeceiptDate"] = dt_convert($this->db->Record["DocPeceiptDate"], DT_SQL, DT_BG, DT_DATE);
				$this->db->Record["DTotal"] = bg_money($this->db->Record["DTotal"]);
				$this->db->Record["PSum"] = bg_money($this->db->Record["PSum"]);
				$report[] = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $report;
	}
	
	public function reportWorkTime($filter = array()) {
		$flt = "";
		if (isset($filter["report_user_id"]) && !empty($filter["report_user_id"])) {
			$flt .= "and t.user_id = '".$this->db->escape((int)$filter["report_user_id"])."'";
		}
	/*	
		if (isset($filter["report_work_time"]) && !empty($filter["report_work_time"])) {
			$flt .= "and t.time_duration >= '".$this->db->escape((int)$filter["report_work_time"])."'";
		}
*/
		if (isset($filter["report_from_date"]) && !empty($filter["report_from_date"])) {
			$filter["report_from_date"] = dt_convert($filter["report_from_date"], DT_BG, DT_SQL, DT_DATE);
			$flt .= "and t.start_time >= '".$this->db->escape($filter["report_from_date"])."'";
		}

		if (isset($filter["report_to_date"]) && !empty($filter["report_to_date"])) {
			$filter["report_to_date"] = dt_convert($filter["report_to_date"], DT_BG, DT_SQL, DT_DATE);
			$flt .= "and t.start_time <= '".$this->db->escape($filter["report_to_date"])."'";
		}

		$query = " 
			select 
				t.id
				, t.key_yyyy_mm_dd 
				, t.start_time 
				, t.end_time 
				, t.time_duration 
				, CONCAT(m.member_name, ' ', m.member_family) as fullName
			from 
					timetraker as t 
					left join members as m on (m.uid = t.user_id)
			where
				 (1)  
			".$flt."  
				order by t.start_time desc, t.id, t.time_duration desc
			";
		$report = array();
		$this->db->prepare($query);
		if($this->db->query()) {
			while ($this->db->fetch()){
			  $w_time = (date("U", strtotime($this->db->Record["end_time"])) - date("U", strtotime($this->db->Record["start_time"]))); 
			  if ($w_time < 0 ) $w_time = 0;
				$this->db->Record["key_yyyy_mm_dd"] = dt_convert($this->db->Record["key_yyyy_mm_dd"], DT_SQL, DT_BG, DT_DATE);
				$this->db->Record["start_time"] = substr($this->db->Record["start_time"], 11, 8);
				$this->db->Record["end_time"] = substr($this->db->Record["end_time"], 11, 8);
				$this->db->Record["time_duration_hh"] = gmdate("H:i:s", $this->db->Record["time_duration"]);
				$this->db->Record["w_time_hh"] = gmdate("H:i:s", $w_time);
				$this->db->Record["w_time_U"] = $w_time;
				$report[] = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $report;
  
  }
  /*
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
	*/	
	
	
	public function getDocs($filter = array()) {
	
		$limit = "";
		$flt = "";
		
		if (isset($filter["report_from_date"]) && !empty($filter["report_from_date"])) {
			$filter["report_from_date"] = dt_convert($filter["report_from_date"], DT_BG, DT_SQL, DT_DATE);
			$flt .= "and d.Docdate >= '".$this->db->escape($filter["report_from_date"])."'";
		}

		if (isset($filter["report_to_date"]) && !empty($filter["report_to_date"])) {
			$filter["report_to_date"] = dt_convert($filter["report_to_date"], DT_BG, DT_SQL, DT_DATE);
			$flt .= "and d.Docdate <= '".$this->db->escape($filter["report_to_date"])."'";
		}

		if (isset($filter["report_total"]) && !empty($filter["report_total"])) {
			$flt .= "and d.DTotal >= '".$this->db->escape($filter["report_total"])."'";
		}

//		$flt .= " and (d.DType = '".DOC_TYPE_ORDER."') and ((d.doc_payed ='0' and d.doc_payed2 ='0') or (d.doc_payed ='0' and d.doc_payed2 ='1') or (d.doc_payed ='1' and d.doc_payed2 ='0') ) ";
		$flt .= " and (d.DType = '".DOC_TYPE_ORDER."') ";
		// DTotal

		$query = " 
		select 
			d.*
			, p.PName
			, m.member_name
			, m.member_family 
			from docs as d 
			left join partners as p on (p.partner_id = d.partner_id) 
			left join members as m on (m.uid = d.uid)
		where (1) ".$flt."
		order by d.dt_created desc
		".$limit;
	
		$documents = array();
		$this->db->prepare($query);
		if($this->db->query()) {
			while ($this->db->fetch()){
				$this->db->Record["Docdate"] = dt_convert($this->db->Record["Docdate"], DT_SQL, DT_BG, DT_DATE);
				$this->db->Record["DocPeceiptDate"] = dt_convert($this->db->Record["DocPeceiptDate"], DT_SQL, DT_BG, DT_DATE);
				$this->db->Record["DTotal"] = bg_money($this->db->Record["DTotal"]);
				$documents[$this->db->Record["doc_id"]] = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $documents;
		
	}
	
	public function getProductStocks($filter = array()) {
	
		$query = " select * from product where (1) order by name";
		$products = array();
		$this->db->prepare($query);
		if($this->db->query()) {
			while ($this->db->fetch()){
				$products[$this->db->Record["product_id"]] = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}

		return $products;
	}
	
	public function getProductStock($product_id) {
		$query = " select * from product where product_id='".$this->db->escape($product_id)."'";
		$product = array(
			"product_id" => ""
			, "number" => ""
			, "name" => ""
			, "quantity" => ""
			, "dt_created" => ""
			, "dt_modified" => ""
			, "uid_created" => ""
			, "uid_modified" => ""
		);
		$this->db->prepare($query);
		if($this->db->query()) {
			if ($this->db->fetch()){
				$product = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}

		return $product;
	}	
	
	public function getProductStockHistory($product_id) {
		$query = " select * from product_history where product_id='".$this->db->escape($product_id)."' order by date_order desc limit 10";
		$products = array();
		$this->db->prepare($query);
		if($this->db->query()) {
			while ($this->db->fetch()){
			  $this->db->Record["date_order"] = dt_convert($this->db->Record["date_order"], DT_SQL, DT_BG, DT_DATE);
				$products[$this->db->Record["product_history_id"]] = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}

		return $products;
	}
	
	public function createProduct($product) {
			$query = " 
					insert into  product set 
						number = '".$this->db->escape($product["number"])."'
						, name = '".$this->db->escape($product["name"])."'
						, quantity = '".$this->db->escape($product["quantity"])."' 
	
						, dt_created = NOW()
						, uid_created = '".$this->db->escape($_SESSION["uid"])."'
					";
			$this->db->prepare($query);
			if(!$this->db->query()) {
				msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
				return false;
			} else {
				msg_add("Продукта е създадена успешно!", MSG_SUCCESS);
   			$query = " 
  					insert into  product_history set 
  						product_id = '".$this->db->escape($this->db->insert_id())."'
  						, type = '1'
  						, quantity = '".$this->db->escape($product["quantity"])."' 
  						, date_order = NOW()
  					";
  
  			$this->db->prepare($query);
  			if(!$this->db->query()) {
  				msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
  			} else {
  				msg_add("Продукта е обновен успешно!", MSG_SUCCESS);
  			}
  				
				return true;
			}
  }
  
  public function synQuantity($product_id, $product) {
 			$query = " 
					insert into  product_history set 
						product_id = '".$this->db->escape($product_id)."'
						, type = '".$this->db->escape($product["type"])."'
						, quantity = '".$this->db->escape($product["quantity"])."' 
						, note = '".$this->db->escape($product["note"])."'
						, date_order = NOW()
					";

			$this->db->prepare($query);
			if(!$this->db->query()) {
				msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			} else {
				msg_add("Продукта е обновен успешно!", MSG_SUCCESS);
			}
 
      $type = (int)$product["type"];
      $operation = ($type == 0? "-" : "+");
      
				$query = " 
				update  product set 
					quantity = (quantity " . $operation . "'".$this->db->escape($product["quantity"])."')

					, dt_modified = NOW()
					, uid_modified = '".$this->db->escape($_SESSION["uid"])."'
				where 
					product_id ='".$this->db->escape($product_id)."' 
				";
				
				$this->db->prepare($query);
				if(!$this->db->query()) {
					msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
				} else {
				  msg_add("Количествата са синхронизирани успешно!", MSG_SUCCESS);
				}
  }
  
  public function removeProduct($product_id) {
					$query = " 
					delete from product_history 
					where 
						product_id ='".$this->db->escape($product_id)."' 
					";
			
					$this->db->prepare($query);
					if(!$this->db->query()) {
						msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
					}
					
					$query = " 
					delete from product 
					where 
						product_id ='".$this->db->escape($product_id)."' 
					";
			
					$this->db->prepare($query);
					if(!$this->db->query()) {
						msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
					}
  
  }
	
}
?>