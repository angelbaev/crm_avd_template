<?php
class ModelDocuments {
	private $db;
	public function __construct()  
  {  
  	global $db;
  	$this->db = &$db;
  } 
	
	public function getPartners($filter = array()) {
	
		$query = " select * from partners where (1) order by  PName";
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

	public function getDocs($filter = array()) {
	
		$limit = "";
		$flt = "";
		if (isset($filter["filter_doc_status"]) && !empty($filter["filter_doc_status"])) {
			$flt .= "and d.DStatus ='".$this->db->escape($filter["filter_doc_status"])."'";
		}
		if (isset($filter["filter_doc_type"]) && !empty($filter["filter_doc_type"])) {
			$flt .= "and d.DType ='".$this->db->escape($filter["filter_doc_type"])."'";
		}

		if (isset($filter["filter_doc_num"]) && !empty($filter["filter_doc_num"])) {
			$flt .= "and d.DocNum LIKE '%".$this->db->escape($filter["filter_doc_num"])."%'";
		}
		if (isset($filter["filter_doc_user"]) && !empty($filter["filter_doc_user"])) {
			$flt .= "and d.uid ='".$this->db->escape((int)$filter["filter_doc_user"])."'";
		}
		if (isset($filter["filter_doc_partner_id"]) && !empty($filter["filter_doc_partner_id"])) {
			$flt .= "and d.partner_id ='".$this->db->escape((int)$filter["filter_doc_partner_id"])."'";
		}

		if (isset($filter["filter_not_payed"]) && !empty($filter["filter_not_payed"])) {
			$flt .= " and (d.DType = '".DOC_TYPE_ORDER."') and ((d.doc_payed ='0' and d.doc_payed2 ='0') or (d.doc_payed ='0' and d.doc_payed2 ='1') or (d.doc_payed ='1' and d.doc_payed2 ='0') ) ";
		}

		if (isset($filter["page_limit"])) {
			$limit .= $filter["page_limit"];
		}
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

	public function getTotalDocs($filter = array()) {
		$flt = "";
/*
		if (isset($filter["PName"]) && !empty($filter["PName"])) {
			$flt .= " and (PName like '%".$filter["PName"]."%')";
		}
*/
		if (isset($filter["filter_doc_status"]) && !empty($filter["filter_doc_status"])) {
			$flt .= "and DStatus ='".$this->db->escape($filter["filter_doc_status"])."'";
		}
		if (isset($filter["filter_doc_type"]) && !empty($filter["filter_doc_type"])) {
			$flt .= "and DType ='".$this->db->escape($filter["filter_doc_type"])."'";
		}

		if (isset($filter["filter_doc_num"]) && !empty($filter["filter_doc_num"])) {
			$flt .= "and DocNum LIKE '%".$this->db->escape($filter["filter_doc_num"])."%'";
		}
		if (isset($filter["filter_doc_user"]) && !empty($filter["filter_doc_user"])) {
			$flt .= "and uid ='".$this->db->escape((int)$filter["filter_doc_user"])."'";
		}
		if (isset($filter["filter_doc_partner_id"]) && !empty($filter["filter_doc_partner_id"])) {
			$flt .= "and partner_id ='".$this->db->escape((int)$filter["filter_doc_partner_id"])."'";
		}

		if (isset($filter["filter_not_payed"]) && !empty($filter["filter_not_payed"])) {
			$flt .= " and (DType = '".DOC_TYPE_ORDER."') and ((doc_payed ='0' and doc_payed2 ='0') or (doc_payed ='0' and doc_payed2 ='1') or (doc_payed ='1' and doc_payed2 ='0') ) ";
		}
		
		$cnt = 0;
		$query = " select count(doc_id) as cnt from docs where (1) ".$flt;
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
	public function getDoc($doc_id) {
	
		$query = " select d.*, m.member_login from docs as d left join members as m on (m.uid = d.uid) where d.doc_id='".$this->db->escape($doc_id)."'";
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
			, "doc_payed" => ""
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
		$doc['doc_cards'] = $this->getDocCards($doc_id);
		$doc['doc_suppliers'] = $this->getDocSuppliers($doc_id);
		
		return $doc;
		
	}
	
	public function getDocOptions($doc_id) {
		$doc_options = array();
		$query = " select * from doc_options where doc_id='".$this->db->escape($doc_id)."'";
		$this->db->prepare($query);
		if($this->db->query()) {
			while ($this->db->fetch()){
				$this->db->Record["PSum"] = bg_money($this->db->Record["PSum"]);
      //  $this->db->Record["DExportData"] =  iconv("UTF-8", "WINDOWS-1251//TRANSLIT", $this->db->Record["DExportData"])  ;
				$doc_options[$this->db->Record["option_id"]] = $this->db->Record;
 			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $doc_options;
	}

	public function getDocCards($doc_id) {
		$doc_cards = array();
		$query = " 
		select ci.*, m.member_name, m.member_family 
		from 
			card_items as ci 
			left join members as m on (m.uid = ci.uid)  
		where 
		ci.doc_id='".$this->db->escape($doc_id)."'
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

	public function getDocSuppliers($doc_id) {
		$doc_suppliers = array();
		$query = " select 
			sui.* 
			, m.member_name, m.member_family 
			, s.CName
			from 
			supplier_items as sui 
			left join members as m on (m.uid = sui.uid)  
			left join suppliers as s on (s.c_id = sui.c_id)  
			where 
			sui.doc_id='".$this->db->escape($doc_id)."'";
		$this->db->prepare($query);
		if($this->db->query()) {
			while ($this->db->fetch()){
				$this->db->Record["CDate"] = dt_convert($this->db->Record["CDate"], DT_SQL, DT_BG, DT_DATE);
				$this->db->Record["CSum"] = bg_money($this->db->Record["CSum"]);
				$this->db->Record["date_requested"] = dt_convert(substr($this->db->Record["date_requested"], 0, 10), DT_SQL, DT_BG, DT_DATE);
				$this->db->Record["amount_requested"] = bg_money($this->db->Record["amount_requested"]);
				$doc_suppliers[] = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $doc_suppliers;
	}
	
	public function getEmailInfo($doc_id) {

		$email_data = array(
			"from" => "office@avdesigngroup.org"
			, "to" => ""
			, "partner" => ""
			, "subject" => ""
			, "content" => ""
			, "doc_print" => ""
		);
		$query = "
			select 
			d.doc_id
			, d.partner_id 
			, p.PName
			, p.PEmail 
			from 
				docs as d 
				left join partners as p on (p.partner_id = d.partner_id )
			where d.doc_id = '".$this->db->escape($doc_id)."'";

		$this->db->prepare($query);
		if($this->db->query()) {
			if ($this->db->fetch()){
				$email_data['to'] = $this->db->Record["PEmail"];
				$email_data['partner'] = $this->db->Record["PName"];
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		
		$email_data['doc_print'] = file_get_contents(HTTP_SERVER.'print/print-doc.php?id='.$doc_id);
//		printArray($email_data);
		return $email_data;

	}
	
	public function updateDoc($doc_id, $data) {
	
		if (count($data)) {
				$data["Docdate"] = dt_convert($data["Docdate"], DT_BG, DT_SQL, DT_DATE);
				if ($data["DType"] == DOC_TYPE_ORDER) {
					$data["DocPeceiptDate"] = dt_convert($data["DocPeceiptDate"], DT_BG, DT_SQL, DT_DATE);
				} else {
					$data["DocPeceiptDate"] = "0000-00-00";
				}
//////////////////////////////////////////////////////////////////////////////////		
try {
  $this->db->begin();
//////////////////////////////////////////////////////////////////////////////////		

			if ($doc_id) {
				$query = " 
				update  docs set 
					partner_id = '".$this->db->escape($data["partner_id"])."'
					, uid = '".$this->db->escape($data["uid"])."'
					, DType = '".$this->db->escape($data["DType"])."'
					, DocNum = '".$this->db->escape($data["DocNum"])."' 
					, Docdate = '".$this->db->escape($data["Docdate"])."' 
					, DocPeceiptDate = '".$this->db->escape($data["DocPeceiptDate"])."' 
					, DTotal = '".$this->db->escape($data["DTotal"])."' 
					, DPayment = '".$this->db->escape($data["DPayment"])."' 
					, DAdvance = '".$this->db->escape($data["DAdvance"])."' 
					, DSurcharge = '".$this->db->escape($data["DSurcharge"])."' 
					, DCorrections = '".$this->db->escape($data["DCorrections"])."' 
					, DCAddr = '".$this->db->escape($data["DCAddr"])."' 
					
					, DStatus = '".$this->db->escape($data["DStatus"])."' 
					, DTermData = '".$this->db->escape($data["DTermData"])."' 
					, term_work = '".$this->db->escape($data["term_work"])."' 
					, view_term = '".$this->db->escape($data["view_term"])."' 
					, delivery_place = '".$this->db->escape($data["delivery_place"])."' 
					, vat_id = '".$this->db->escape($data["vat_id"])."' 
					, vat_id = '".$this->db->escape($data["vat_id"])."' 
					, Dpay_advance = '".$this->db->escape($data["Dpay_advance"])."' 
					, Dpay_surcharge = '".$this->db->escape($data["Dpay_surcharge"])."' 
					, doc_payed = '".$this->db->escape($data["doc_payed"])."'
					, doc_payed2 = '".$this->db->escape($data["doc_payed2"])."'

					, dt_modified = NOW()
					, uid_modified = '".$this->db->escape($_SESSION["uid"])."'
				where 
					doc_id ='".$this->db->escape($doc_id)."' 
				";
				$this->db->prepare($query);
				if(!$this->db->query()) {
					msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
					return false;
				} else {
					msg_add("Документа беше редактиран успешно!", MSG_SUCCESS);
					//return $partner_id;
				}
				
			} else {
				$data["DocNum"] = $this->getDocNumber($data["DType"]);
				$query = " 
				insert into  docs set 
					partner_id = '".$this->db->escape($data["partner_id"])."'
					, uid = '".$this->db->escape($data["uid"])."'
					, DType = '".$this->db->escape($data["DType"])."'
					, DocNum = '".$this->db->escape($data["DocNum"])."' 
					, Docdate = '".$this->db->escape($data["Docdate"])."' 
					, DocPeceiptDate = '".$this->db->escape($data["DocPeceiptDate"])."' 
					, DTotal = '".$this->db->escape($data["DTotal"])."' 
					, DPayment = '".$this->db->escape($data["DPayment"])."' 
					, DAdvance = '".$this->db->escape($data["DAdvance"])."' 
					, DSurcharge = '".$this->db->escape($data["DSurcharge"])."' 
					, DCorrections = '".$this->db->escape($data["DCorrections"])."' 
					, DCAddr = '".$this->db->escape($data["DCAddr"])."' 

					, DStatus = '".$this->db->escape($data["DStatus"])."' 
					, DTermData = '".$this->db->escape($data["DTermData"])."' 
					, term_work = '".$this->db->escape($data["term_work"])."' 
					, view_term = '".$this->db->escape($data["view_term"])."' 
					, delivery_place = '".$this->db->escape($data["delivery_place"])."' 
					, vat_id = '".$this->db->escape($data["vat_id"])."' 
					, Dpay_advance = '".$this->db->escape($data["Dpay_advance"])."' 
					, Dpay_surcharge = '".$this->db->escape($data["Dpay_surcharge"])."' 
					, doc_payed = '".$this->db->escape($data["doc_payed"])."'
					, doc_payed2 = '".$this->db->escape($data["doc_payed2"])."'

					, dt_created = NOW()
					, uid_created = '".$this->db->escape($_SESSION["uid"])."'
				";
				$this->db->prepare($query);
				if(!$this->db->query()) {
					msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
					return false;
				} else {
					msg_add("Документа беше добавен успешно!", MSG_SUCCESS);
					$doc_id = $this->db->insert_id();
				}
			
			}

			$query = " 
			delete from doc_options 
			where 
				doc_id ='".$this->db->escape($doc_id)."' 
			";

			$this->db->prepare($query);
			if(!$this->db->query()) {
				msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
				//return false;
			}
			
			if (count($data["doc_options"])) {
					$_option_id = 1;
					foreach ($data["doc_options"] as $key => $item) {/*$doc_id*/
								$query = " 
								insert into  doc_options set 
									doc_id = '".$this->db->escape($doc_id)."'
									, option_id = '".$this->db->escape($_option_id)."'
									, c_id = '".$this->db->escape($item["c_id"])."'
									, DExportData = '".$this->db->escape($item["DExportData"])."'
									, DNotes = '".$this->db->escape($item["DNotes"])."'
									, PSum = '".$this->db->escape((float)$item["PSum"])."'
								";

								$_option_id++;
								$this->db->prepare($query);
								if(!$this->db->query()) {
									msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
								//	break;
								}
					}
					
			}
			
			$card = _p("card", array());
			$supplier = _p("supplier", array());

		  $card = array_unique_associative($card);
			$supplier = array_unique_associative($supplier);
//remove this  ..
/*
			if (!count($card)) {
				$query = "select * from card_items where doc_id ='".$this->db->escape($doc_id)."'";
				$data = array();
				$this->db->prepare($query);
				if($this->db->query()) {
					while ($this->db->fetch()){
						$data[] = $this->db->Record;
					}
				} else {
					//lwrite('(1) docs::activities - Serialize card_items: (doc_id = '.$doc_id.') :: '.$this->db->Error);
				}

				if (count($data)) {
					ldbwrite(
										array('doc_id' => $doc_id
										, 'table_name' => 'card_items'
										, 'error_from' => 'Документи'
										, 'lost_data' => $data
										, 'post_data' => $card
										)
					);
				}
				//lwrite('(2) docs::activities - cart no items :: Serialize: '.serialize($data));
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
					//lwrite('(3) docs::activities - Serialize supplier_items: (doc_id = '.$doc_id.') :: '.$this->db->Error);
				}
				if (count($data)) {
					ldbwrite(
										array('doc_id' => $doc_id
										, 'table_name' => 'supplier_items'
										, 'error_from' => 'Документи'
										, 'lost_data' => $data
										, 'post_data' => $supplier
										)
					);
				}
				
//				lwrite('(4) docs::activities - supplier no items :: Serialize: '.serialize($data));
			}
			*/
//remove this end ..

////////////////////////////////////////////////////////////////////////////////
if (/*count($card)*/ false) {
  foreach($card as $key => $val) {
    //$rec_hash = md5($doc_id.''.$val['uid'].''.$val['card_date']);
 		$val["card_date"] = dt_convert($val["card_date"], DT_BG, DT_SQL, DT_DATE);
    if(empty($val['rec_hash'])) {
      $val['rec_hash'] = md5(time().rand(1000,4000));
    }
//    $card[$key]['rec_hash'] = $rec_hash;
    $rec_hash = $val['rec_hash'];
		$query = " 
		select rec_hash from card_items 
		where 
			rec_hash ='".$this->db->escape($rec_hash)."' 
		";
		
		$this->db->prepare($query);
		if(!$this->db->query()) {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
		}
    
    if ($this->db->num_rows() == 0) {
			$query = " 
			insert into  card_items set 
				doc_id = '".$this->db->escape($doc_id)."'
				, uid = '".$this->db->escape($val["uid"])."'
				, card_date = '".$this->db->escape($val["card_date"])."'
				, card_notes = '".$this->db->escape(trim($val["card_notes"]))."'
				, card_status = '".$this->db->escape($val["card_status"])."'
				, rec_hash = '".$this->db->escape($rec_hash)."'
			";
    } else {
			$query = " 
			update  card_items set 
				doc_id = '".$this->db->escape($doc_id)."'
				, uid = '".$this->db->escape($val["uid"])."'
				, card_date = '".$this->db->escape($val["card_date"])."'
				, card_notes = '".$this->db->escape(trim($val["card_notes"]))."'
				, card_status = '".$this->db->escape($val["card_status"])."'
			where 
			  rec_hash ='".$this->db->escape($rec_hash)."'
			";
    }
		$this->db->prepare($query);
		if(!$this->db->query()) {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
		}
    
  }
}
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
if (/*count($supplier)*/ false) {
  foreach($supplier as $key => $val) {
    if(empty($val['rec_hash'])) {
      $val['rec_hash'] = md5(time().rand(1000,4000));
    }
 //   $rec_hash = md5($doc_id.''.$val['c_id'].''.$val['uid'].''.$val['CDate']);
    $val["CDate"] = dt_convert($val["CDate"], DT_BG, DT_SQL, DT_DATE);
    $val["date_requested"] = dt_convert($val["date_requested"], DT_BG, DT_SQL, DT_DATE);
  //  $supplier[$key]['rec_hash'] = $rec_hash;
    $rec_hash = $val['rec_hash'];

		$query = " 
		select rec_hash from supplier_items 
		where 
			rec_hash ='".$this->db->escape($rec_hash)."' 
		";
		
		$this->db->prepare($query);
		if(!$this->db->query()) {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
		}
    
    if ($this->db->num_rows() == 0) {
			$query = " 
			insert into  supplier_items set 
				doc_id = '".$this->db->escape($doc_id)."'
				, uid = '".$this->db->escape($val["uid"])."'
				, c_id = '".$this->db->escape($val["c_id"])."'
				, CDate = '".$this->db->escape($val["CDate"])."'
				, CNotes = '".$this->db->escape(trim($val["CNotes"]))."'
				, CStatus = '".$this->db->escape($val["CStatus"])."'
				, amount_requested = '".$this->db->escape((float)$val["amount_requested"])."'
				, date_requested = '".$this->db->escape($val["date_requested"])."'
				, CSum = '".$this->db->escape((float)$val["CSum"])."'
				, rec_hash = '".$this->db->escape($rec_hash)."'
			";
    
    } else {
			$query = " 
			update  supplier_items set 
				doc_id = '".$this->db->escape($doc_id)."'
				, uid = '".$this->db->escape($val["uid"])."'
				, c_id = '".$this->db->escape($val["c_id"])."'
				, CDate = '".$this->db->escape($val["CDate"])."'
				, CNotes = '".$this->db->escape(trim($val["CNotes"]))."'
				, CStatus = '".$this->db->escape($val["CStatus"])."'
				, amount_requested = '".$this->db->escape((float)$val["amount_requested"])."'
				, date_requested = '".$this->db->escape($val["date_requested"])."'
				, CSum = '".$this->db->escape((float)$val["CSum"])."'
    		where 
    			rec_hash ='".$this->db->escape($rec_hash)."' 
			";
    
    } 
		$this->db->prepare($query);
		if(!$this->db->query()) {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
		}
    $this->synSupplierCase($rec_hash);  
  }
}
////////////////////////////////////////////////////////////////////////////////

/*
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
  				//lwrite('(5) docs::activities - card_items: (doc_id = '.$doc_id.') :: '.$this->db->Error);
  				//return false;
  			}
      }


}
*/
/*
			if (count($card)) {
				foreach($card as $key => $val) {
								$val["card_date"] = dt_convert($val["card_date"], DT_BG, DT_SQL, DT_DATE);
								$query = " 
								insert into  card_items set 
									doc_id = '".$this->db->escape($doc_id)."'
									, uid = '".$this->db->escape($val["uid"])."'
									, card_date = '".$this->db->escape($val["card_date"])."'
									, card_notes = '".$this->db->escape(trim($val["card_notes"]))."'
									, card_status = '".$this->db->escape($val["card_status"])."'
								";

								$this->db->prepare($query);
								if(!$this->db->query()) {
									msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
									//lwrite('(6) docs::activities - insert into card_items: ('.$query.') :: '.$this->db->Error);
									//break;
								}
								
				}
				
			}
*/
	/*		
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
  
  				lwrite($query);
  			$this->db->prepare($query);
  			if(!$this->db->query()) {
  				msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
  				//lwrite('(7) docs::activities - supplier_items: (doc_id = '.$doc_id.') :: '.$this->db->Error);
  				//return false;
  			}
  		}
}			

			if (count($supplier)) {
				foreach($supplier as $key => $val) {
								$val["CDate"] = dt_convert($val["CDate"], DT_BG, DT_SQL, DT_DATE);
								$query = " 
								insert into  supplier_items set 
									doc_id = '".$this->db->escape($doc_id)."'
									, uid = '".$this->db->escape($val["uid"])."'
									, c_id = '".$this->db->escape($val["c_id"])."'
									, CDate = '".$this->db->escape($val["CDate"])."'
									, CNotes = '".$this->db->escape(trim($val["CNotes"]))."'
									, CStatus = '".$this->db->escape($val["CStatus"])."'
									, CSum = '".$this->db->escape((float)$val["CSum"])."'
								";

								$this->db->prepare($query);
								if(!$this->db->query()) {
									msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
									//lwrite('(8) docs::activities - insert into supplier_items: ('.$query.') :: '.$this->db->Error);
									//break;
								}
				}
			}
			*/
//////////////////////////////////////////////////////////////////////////////////		
  $this->db->commit();		
}catch ( Exception $e ) {
  $this->db->rollback();
}	
//////////////////////////////////////////////////////////////////////////////////		

			return $doc_id;
		} else {
			msg_add("Грешка при предаване на данните!", MSG_ERROR);
			return false;
		}

	}
	
	public function createOrder($doc_id) {
		$query = " select d.* from docs as d  where d.doc_id='".$this->db->escape($doc_id)."'";

		$doc = array(
			"doc_id" => ""
			, "partner_id" => ""
			, "from_doc_id" => ""
			, "uid" => ""
			, "DType" => ""
			, "DocNum" => ""
			, "Docdate" => ""
			, "DocPeceiptDate" => ""
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
		);
		$this->db->prepare($query);
		if($this->db->query()) {
			if ($this->db->fetch()){
				$doc = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		
		if ($doc["doc_id"]) {
		$query = "update  docs set DStatus = '".$this->db->escape(DOC_ORDER_STATUS_R)."' where doc_id='".$this->db->escape($doc["doc_id"])."'";
		$this->db->prepare($query);
		if(!$this->db->query()) {
			msg_add("Грешка при обновяване статуса на офертата!", MSG_ERROR);
		}
		
			$doc["DocNum"] = $this->getDocNumber(DOC_TYPE_ORDER);
			$query = " 
					insert into  docs set 
						partner_id = '".$this->db->escape($doc["partner_id"])."'
						, uid = '".$this->db->escape($doc["uid"])."'
						, DType = '".$this->db->escape(DOC_TYPE_ORDER)."'
						, from_doc_id = '".$this->db->escape($doc["doc_id"])."'
						, DocNum = '".$this->db->escape($doc["DocNum"])."' 
						, Docdate = '".$this->db->escape($doc["Docdate"])."' 
						, DocPeceiptDate = '".$this->db->escape($doc["DocPeceiptDate"])."' 
						, DTotal = '".$this->db->escape($doc["DTotal"])."' 
						, DPayment = '".$this->db->escape($doc["DPayment"])."' 
						, DAdvance = '".$this->db->escape($doc["DAdvance"])."' 
						, DSurcharge = '".$this->db->escape($doc["DSurcharge"])."' 
						, DCorrections = '".$this->db->escape($doc["DCorrections"])."' 
						, DCAddr = '".$this->db->escape($doc["PCAddr"])."' 
	
						, DStatus = '".$this->db->escape(DOC_ORDER_STATUS_A)."' 
						, DTermData = '".$this->db->escape($doc["DTermData"])."' 
						, term_work = '".$this->db->escape($doc["term_work"])."' 
						, view_term = '".$this->db->escape($doc["view_term"])."' 
						, delivery_place = '".$this->db->escape($doc["delivery_place"])."' 
						, vat_id = '".$this->db->escape($doc["vat_id"])."' 
						, Dpay_advance = '".$this->db->escape($doc["Dpay_advance"])."' 
						, Dpay_surcharge = '".$this->db->escape($doc["Dpay_surcharge"])."' 
	
						, dt_created = NOW()
						, uid_created = '".$this->db->escape($_SESSION["uid"])."'
					";
					$this->db->prepare($query);
					if(!$this->db->query()) {
						msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
						return false;
					} else {
						msg_add("Поръчката е създадена успешно!", MSG_SUCCESS);
						$doc_id = $this->db->insert_id();
					}
					
					//insert options data
					$doc_options = $this->getDocOptions($doc["doc_id"]);
//					$doc_cards = $this->getDocCards($doc["doc_id"]);
//					$doc_suppliers = $this->getDocSuppliers($doc["doc_id"]);
					
					if (count($doc_options)) {
						$_option_id = 0;
						foreach ($doc_options as $key => $item) {
									$query = " 
									insert into  doc_options set 
										doc_id = '".$this->db->escape($doc_id)."'
										, option_id = '".$this->db->escape($_option_id)."'
										, c_id = '".$this->db->escape($item["c_id"])."'
										, DExportData = '".$this->db->escape($item["DExportData"])."'
										, DNotes = '".$this->db->escape($item["DNotes"])."'
										, PSum = '".$this->db->escape((float)$item["PSum"])."'
									";
	
									$_option_id++;
									$this->db->prepare($query);
									if(!$this->db->query()) {
										msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
										break;
									}
						}
					}
					/*
//cards
					if (count($doc_cards)) {
							foreach($doc_cards as $key => $val) {
											$val["card_date"] = dt_convert($val["card_date"], DT_BG, DT_SQL, DT_DATE);
											$query = " 
											insert into  card_items set 
												doc_id = '".$this->db->escape($doc_id)."'
												, uid = '".$this->db->escape($val["uid"])."'
												, card_date = '".$this->db->escape($val["card_date"])."'
												, card_notes = '".$this->db->escape($val["card_notes"])."'
											";
			
											$this->db->prepare($query);
											if(!$this->db->query()) {
												msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
												break;
											}
							}
					}
//suppliers
					if (count($doc_suppliers)) {
							foreach($doc_suppliers as $key => $val) {
								$val["CDate"] = dt_convert($val["CDate"], DT_BG, DT_SQL, DT_DATE);
								$query = " 
								insert into  supplier_items set 
									doc_id = '".$this->db->escape($doc_id)."'
									, uid = '".$this->db->escape($val["uid"])."'
									, c_id = '".$this->db->escape($val["c_id"])."'
									, CDate = '".$this->db->escape($val["CDate"])."'
									, CNotes = '".$this->db->escape($val["CNotes"])."'
									, CSum = '".$this->db->escape((float)$val["CSum"])."'
								";

								$this->db->prepare($query);
								if(!$this->db->query()) {
									msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
									break;
								}
							}
					}
*/
					
			}
			return $doc_id;
	}
	
	public function getDocNumber($DType) {
		$DocNum = $DocNumBase = 0;
		$query = " select * from doc_num where DType ='".$this->db->escape($DType)."' ORDER BY DocNumBase DESC LIMIT 1";
		$this->db->prepare($query);
		if($this->db->query()) {
			if ($this->db->fetch()){
				$DocNum = $this->db->Record["DocNumBase"];
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return 0;
		}

		$index_prf = ($DType == DOC_TYPE_OFFER ? "KF":"KP");

		if (!empty($DocNum)){
			//$DocNum = substr($DocNum, 2);
			$DocNum ++;
			$DocNumBase = $DocNum;
			if ($DType == DOC_TYPE_OFFER) {
				$DocNum = $index_prf.str_pad($DocNum, 5, "0", STR_PAD_LEFT);
			} else {
				$DocNum = $index_prf.str_pad($DocNum, 5, "0", STR_PAD_LEFT);
			}
			
		} else {
			if ($DType == DOC_TYPE_OFFER) {
				$DocNum = $index_prf."00001";
			} else {
				$DocNum = $index_prf."05000";
			}
		}
		
		
		$query = "insert into doc_num set DocNum='".$this->db->escape($DocNum)."', DocNumBase = '".$DocNumBase."', DType ='".$this->db->escape($DType)."'";
		$this->db->prepare($query);
		if(!$this->db->query()) {
		;
		}
		
		return $DocNum;
	}

	public function deteleDoc($doc_id) {
	
		if ($doc_id) {
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
					delete from docs 
					where 
						doc_id ='".$this->db->escape($doc_id)."' 
					";
					$this->db->prepare($query);
					if(!$this->db->query()) {
						msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
						return false;
					} else {
						msg_add("Документа беше изтрит успешно!", MSG_SUCCESS);
						return true;
					}
					
		
		}
		
	}
	
	public function synSupplierCase($hash) {
		$query = "
			select 
			*  
			from 
				supplier_items
			where rec_hash = '".$this->db->escape($hash)."'";

    $supplierInfo = null;
		$this->db->prepare($query);
		if($this->db->query()) {
			if ($this->db->fetch()){
				$supplierInfo = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		
		if (is_null($supplierInfo)) {
			return false;
    }
    
		if (empty($supplierInfo['amount_requested']) || $supplierInfo['amount_requested'] == '	0.0000') {
			return false;
    }

    $caseInfo = NULL;
		$query = "
			select 
			*  
			from 
				company_new_case
			where supplier_hash = '".$this->db->escape($hash)."'";

    $caseInfo = NULL;
		$this->db->prepare($query);
		if($this->db->query()) {
			if ($this->db->fetch()){
				$caseInfo = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}

		if (is_null($caseInfo)) {
			$query = " 
			insert into  company_new_case set 
				doc_id = '".$this->db->escape($supplierInfo['doc_id'])."'
				, uid = '".$this->db->escape($supplierInfo["uid"])."'
				, partner_id = '".$this->db->escape($supplierInfo["c_id"])."'
				, supplier_hash = '".$this->db->escape($hash)."'
				, case_description = '".$this->db->escape($supplierInfo["CNotes"])."'
				, case_date = '".$this->db->escape($supplierInfo["date_requested"])."'
				, case_amount_requested = '".$this->db->escape((float)$supplierInfo["amount_requested"])."'
			";
  		$this->db->prepare($query);
  		if(!$this->db->query()) {
  			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
  		}
			
    } else {
   // $_SESSION['info_debug'] = $supplierInfo;
   
			$query = " 
			update  company_new_case set 
				doc_id = '".$this->db->escape($supplierInfo['doc_id'])."'
				, uid = '".$this->db->escape($supplierInfo["uid"])."'
				, partner_id = '".$this->db->escape($supplierInfo["c_id"])."'
				, case_date = '".$this->db->escape($supplierInfo["date_requested"])."'
				, case_description = '".$this->db->escape($supplierInfo["CNotes"])."'
				, case_amount_requested = '".$this->db->escape((float)$supplierInfo["amount_requested"])."'
			where 
        case_id = '".$this->db->escape($caseInfo["case_id"])."'	
			";
  		$this->db->prepare($query);
  		if(!$this->db->query()) {
  			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
  		}
			
    }

  }
		
}
?>