<?php
class ModelActivities {
	private $db;
        private static $isGenerateTempTable = false;
	public function __construct()  
  {  
  	global $db;
  	$this->db = &$db;
  } 

  private function generateTempTable($filter = array(), $uid = "") {
        $hasError = false;
        
        if (self::$isGenerateTempTable) {
            return ;
        }
        $explodeUserIds = array();
        
	$query = "
            select 
                    uid 
                    , member_name 
                    , member_family
                    , show_forgotten_activity
            from  
                    members 
            where 
            show_forgotten_activity = 0
            ";
        $this->db->prepare($query);
        if($this->db->query()) {
            while ($this->db->fetch()){
                $explodeUserIds[] = $this->db->Record["uid"];
            }
        }        
        
        $query = "DROP TABLE IF EXISTS tmp_activities;"; 
        $this->db->prepare($query);
        if(!$this->db->query()) {
            $hasError = true;
            msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
        }
    
        $query = "CREATE TEMPORARY TABLE tmp_activities(
          id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          doc_id INT(11) NOT NULL DEFAULT 0,
          doc_num VARCHAR(50) NOT NULL,
          client VARCHAR(255) NOT NULL,
          status INT(2) NOT NULL DEFAULT 0,
          doc_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
          activity_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
          user_fullname VARCHAR(255) NOT NULL,
          note VARCHAR(255) NOT NULL,
          supplier_fullname VARCHAR(255) NOT NULL,
          supplier_sum DECIMAL(12,2) NOT NULL DEFAULT 0.00,
          type INT(2) NOT NULL DEFAULT 0,
          uid INT(11) NOT NULL DEFAULT 0, 
          doc_status INT(2) NOT NULL DEFAULT 0,
          supplier_id INT(11) NOT NULL DEFAULT 0,
          is_forgotten_activity INT(1) NOT NULL DEFAULT 0
        );";
        
        $this->db->prepare($query);
        if(!$this->db->query()) {
            $hasError = true;
            msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
        }

        $flt = "";
        $_flt = "";
//        $__flt = "";
        if (isset($filter["filter_card_status"])) {
            $flt .= "and ci.card_status = '"._sqln($filter["filter_card_status"])."'";
            $_flt .= "and sui.CStatus = '"._sqln($filter["filter_card_status"])."'";
            $__flt .= "and status = '"._sqln($filter["filter_card_status"])."'";
        }

        if (isset($filter["filter_doc_num"])) {
            $flt .= "and d.DocNum = '"._sqln($filter["filter_doc_num"])."'";
            $_flt .= "and d.DocNum = '"._sqln($filter["filter_doc_num"])."'";
//            $__flt .= "and doc_num = '"._sqln($filter["filter_doc_num"])."'";
        }
		
        $select = " 
        select 
            d.doc_id as doc_id
            , d.DocNum as doc_num
            , p.PName as client
            , ci.card_status as status
            , d.DocPeceiptDate as doc_date
            , ci.card_date as activity_date
            , CONCAT(m.member_name, ' ', m.member_family) as user_fullname
            , ci.card_notes as note
            , ''
            , 0
            , 1
            , ci.uid
            , d.DStatus
            , 0
            , IF(((UNIX_TIMESTAMP(ci.card_date) < " . strtotime("-1 day") . ") AND (ci.card_status = " . CARD_STATUS_W . ") AND ((ci.uid NOT IN(". implode(',', $explodeUserIds).")) = 1) ), 1, 0) as is_forgotten_activity           
        from 
        card_items as ci 
        inner join docs as d on ((d.doc_id = ci.doc_id) )  
        left join partners as p on (p.partner_id = d.partner_id)  
        left join members as m on (m.uid = ci.uid)
        where 
          d.DType='".DOC_TYPE_ORDER."' 
          ".($uid?" and (ci.uid='"._sqln($uid)."')":"")." 
          and (ci.card_date >= '2017-01-01' or d.DocPeceiptDate >= '2017-01-01')
        ".$flt;		
      
        $query = "
        INSERT INTO tmp_activities (
            doc_id, 
            doc_num, 
            client, 
            status, 
            doc_date, 
            activity_date, 
            user_fullname, 
            note, 
            supplier_fullname, 
            supplier_sum, 
            type,
            uid,
            doc_status,
            supplier_id,
            is_forgotten_activity
        ) 
        ". $select;
        $this->db->prepare($query);
        if(!$this->db->query()) {
            $hasError = true;
            msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
        }
    
        $select = " 
        select 
            d.doc_id as doc_id
            , d.DocNum as doc_num
            , p.PName as client
            , sui.CStatus as status
            , d.DocPeceiptDate as doc_date
            , sui.CDate as activity_date
            , CONCAT(m.member_name, ' ', m.member_family) as user_fullname
            , sui.CNotes as note
            , s.CName as supplier_fullname
            , sui.CSum as supplier_sum
            , 2
            , sui.uid
            , d.DStatus
            , s.c_id
            , IF(((UNIX_TIMESTAMP(sui.CDate) < " . strtotime("-1 day") . ") AND (sui.CStatus = " . CARD_STATUS_W . ") AND ((sui.uid NOT IN(". implode(',', $explodeUserIds).")) = 1) ), 1, 0) as is_forgotten_activity           
        from 
        supplier_items as sui  
        inner join docs as d on ((d.doc_id = sui.doc_id) )  
        left join partners as p on (p.partner_id = d.partner_id)  
        left join suppliers as s on (s.c_id = sui.c_id) 
        left join members as m on (m.uid = sui.uid)
        where 
          d.DType='".DOC_TYPE_ORDER."' 
          ".($uid?" and (sui.uid='"._sqln($uid)."')":"")." 
          and (sui.CDate >= '2017-01-01' or d.DocPeceiptDate >= '2017-01-01')    
        ".$_flt;

        $query = "
        INSERT INTO tmp_activities (
            doc_id, 
            doc_num, 
            client, 
            status, 
            doc_date, 
            activity_date, 
            user_fullname, 
            note, 
            supplier_fullname, 
            supplier_sum, 
            type,
            uid,
            doc_status,
            supplier_id,
            is_forgotten_activity
        ) 
        ". $select;
        $this->db->prepare($query);
        if(!$this->db->query()) {
            $hasError = true;
            msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
        }
        
        if (!$hasError) {
            self::$isGenerateTempTable = true;
        }
  }
    public function getNewTotal($filter = array(), $uid = "")  {
        $this->generateTempTable($filter, $uid);
	$flt = "";
        if (isset($filter["filter_card_status"])) {
                $flt .= "and status = '"._sqln($filter["filter_card_status"])."'";
        }

        if (isset($filter["filter_uid"]) && !empty($filter["filter_uid"])) {
                $flt .= "and uid = '"._sqln($filter["filter_uid"])."'";
        }
        if (isset($filter["filter_supplier_id"]) && !empty($filter["filter_supplier_id"])) {
                $flt .= "and supplier_id = '"._sqln($filter["filter_supplier_id"])."'";
        }
        if (isset($filter["filter_is_forgotten_activity"])) {
                $flt .= "and is_forgotten_activity = '"._sqln($filter["filter_is_forgotten_activity"])."'";
        }

        if (isset($filter["filter_doc_num"])) {
            $flt .= "and doc_num like '%"._sqln($filter["filter_doc_num"])."%'";
        }

        if (isset($filter["filter_activity_period"])) {
            if ($filter["filter_activity_period"] == 'this_week') {
                $thisWeek = date('Y-m-d', strtotime('this week'));
                $today =  date('Y-m-d', strtotime('now'));
                $flt .= " and (activity_date BETWEEN '"._sqld($thisWeek)."' and '"._sqld($today)."')";
            }
            if ($filter["filter_activity_period"] == 'this_and_last_week') {
                $lastWeek = date('Y-m-d', strtotime('last week'));
                $today =  date('Y-m-d', strtotime('now'));
                $flt .= " and (activity_date BETWEEN '"._sqld($lastWeek)."' and '"._sqld($today)."')";
            }
            if ($filter["filter_activity_period"] == 'this_month') {
                $firstDayOfMonth = date('Y-m-01');
                $lastDayofMonth =  date('Y-m-t');
                $flt .= " and (activity_date BETWEEN '"._sqld($firstDayOfMonth)."' and '"._sqld($lastDayofMonth)."')";
            }
            if ($filter["filter_activity_period"] == 'this_and_last_month') {
                $lastMonth = date('Y-m-d', strtotime(date('Y-m') . ' -1 month'));
                $lastDayofMonth =  date('Y-m-t');
                $flt .= " and (activity_date BETWEEN '"._sqld($lastMonth)."' and '"._sqld($lastDayofMonth)."')";
            }
            if ($filter["filter_activity_period"] == 'this_year') {
                $firstDayOfYear = date('Y-01-01');
                $lastDayofYear =  date('Y-12-31');
                $flt .= " and (activity_date BETWEEN '"._sqld($firstDayOfYear)."' and '"._sqld($lastDayofYear)."')";
            }
        }
        
	$cnt = 0;
        $query = " 
        select 
            count(id) as cnt
        from 
        tmp_activities 
        where (1)
          ".($uid?" and (uid='"._sqln($uid)."')":"")." 
        ".$flt;
//                ."
//        order by doc_date desc, doc_id desc
//        ";
		 
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
  
    public function getNewActivities($filter = array(), $uid = "") {
        $this->generateTempTable($filter, $uid);        
	$flt = "";
        if (isset($filter["filter_card_status"])) {
                $flt .= "and status = '"._sqln($filter["filter_card_status"])."'";
        }

        if (isset($filter["filter_uid"]) && !empty($filter["filter_uid"])) {
                $flt .= "and uid = '"._sqln($filter["filter_uid"])."'";
        }
        if (isset($filter["filter_supplier_id"]) && !empty($filter["filter_supplier_id"])) {
                $flt .= "and supplier_id = '"._sqln($filter["filter_supplier_id"])."'";
        }
        
        if (isset($filter["filter_is_forgotten_activity"])) {
                $flt .= "and is_forgotten_activity = '"._sqln($filter["filter_is_forgotten_activity"])."'";
        }

        if (isset($filter["filter_doc_num"])) {
            $flt .= "and doc_num like '%"._sqln($filter["filter_doc_num"])."%'";
        }

        if (isset($filter["filter_activity_period"])) {
            if ($filter["filter_activity_period"] == 'this_week') {
                $thisWeek = date('Y-m-d', strtotime('this week'));
                $today =  date('Y-m-d', strtotime('now'));
                $flt .= " and (activity_date BETWEEN '"._sqld($thisWeek)."' and '"._sqld($today)."')";
            }
            if ($filter["filter_activity_period"] == 'this_and_last_week') {
                $lastWeek = date('Y-m-d', strtotime('last week'));
                $today =  date('Y-m-d', strtotime('now'));
                $flt .= " and (activity_date BETWEEN '"._sqld($lastWeek)."' and '"._sqld($today)."')";
            }
            if ($filter["filter_activity_period"] == 'this_month') {
                $firstDayOfMonth = date('Y-m-01');
                $lastDayofMonth =  date('Y-m-t');
                $flt .= " and (activity_date BETWEEN '"._sqld($firstDayOfMonth)."' and '"._sqld($lastDayofMonth)."')";
            }
            if ($filter["filter_activity_period"] == 'this_and_last_month') {
                $lastMonth = date('Y-m-d', strtotime(date('Y-m') . ' -1 month'));
                $lastDayofMonth =  date('Y-m-t');
                $flt .= " and (activity_date BETWEEN '"._sqld($lastMonth)."' and '"._sqld($lastDayofMonth)."')";
            }
            if ($filter["filter_activity_period"] == 'this_year') {
                $firstDayOfYear = date('Y-01-01');
                $lastDayofYear =  date('Y-12-31');
                $flt .= " and (activity_date BETWEEN '"._sqld($firstDayOfYear)."' and '"._sqld($lastDayofYear)."')";
            }
        }

        if (isset($filter["page_limit"])) {
            $limit = $filter["page_limit"];
        }

        $query = " 
        select 
            *
        from 
        tmp_activities 
        where (1)
          ".($uid?" and (uid='"._sqln($uid)."')":"")." 
        ".$flt."
        order by is_forgotten_activity desc, activity_date asc, doc_date asc, doc_id asc
        " . $limit;
//        order by is_forgotten_activity desc, doc_date desc, doc_id desc

        $activities = array();
        $docIds = array();
        $i = 0;
        $this->db->prepare($query);
        if($this->db->query()) {
            while ($this->db->fetch()){
                $this->db->Record["doc_date"] = dt_convert($this->db->Record["doc_date"], DT_SQL, DT_BG, DT_DATE);
                $this->db->Record["activity_date"] = dt_convert($this->db->Record["activity_date"], DT_SQL, DT_BG, DT_DATE);
                $this->db->Record["supplier_sum"] = bg_money($this->db->Record["supplier_sum"]);
                $activities[] = $this->db->Record;
                /*
                if (!isset($docIds[$this->db->Record["doc_id"]])) {
                    $docIds[$this->db->Record["doc_id"]] = $this->db->Record["doc_id"];
                    ++$i;
                    $activities[$i]["ITEM_A"] = array();
                    $activities[$i]["ITEM_C"] = array();
                }
                
                if ($this->db->Record['type'] == '1') {
                    $activities[$i]["ITEM_A"][] = $this->db->Record;
                } else {
                    $activities[$i]["ITEM_C"][] = $this->db->Record;
                }
                 * 
                 */
                //$activities[$i] = $this->db->Record;
//                    $activities[$this->db->Record["doc_id"]]["ITEM_A"][] = $this->db->Record;
//                    $activities[$this->db->Record["doc_id"]]["ITEM_C"] = array();
//                    $doc_data[$this->db->Record["doc_id"]] = array("DocNum" => $this->db->Record["DocNum"], "PName" => $this->db->Record["PName"]);
            }
        } else {
            msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
            return false;
        }   

        return $activities;
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
		if (isset($filter["filter_card_status"])) {
			$flt .= "and ci.card_status = '"._sqln($filter["filter_card_status"])."'";
		}

		if (isset($filter["filter_doc_num"])) {
			$flt .= "and d.DocNum = '"._sqln($filter["filter_doc_num"])."'";
		}

		$cnt = 0;
		/*
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
		 */
  		$query = " 
  		select 
				count(d.doc_id) as cnt
  			from 
        card_items as ci 
        inner join docs as d on ((d.doc_id = ci.doc_id))  
        left join partners as p on (p.partner_id = d.partner_id)  
        left join members as m on (m.uid = ci.uid)
  		where 
  		  d.DType='".DOC_TYPE_ORDER."' 
  		  ".($uid?" and (ci.uid='"._sqln($uid)."')":"")." 
      ".$flt."
  		order by d.Docdate desc, d.doc_id desc
  		".$limit;
		 
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
//            if ($_SERVER['REMOTE_ADDR'] == '176.12.23.39') {
//                  $this->generateTempTable($filter, $uid);
//                
//            }

			$limit = "";
			$flt = "";
			$_flt = "";
		$this->card_status = "";
		if (isset($filter["filter_card_status"])) {
			$this->card_status = $filter["filter_card_status"];
			$flt .= "and ci.card_status = '"._sqln($filter["filter_card_status"])."'";
			$_flt .= "and sui.CStatus = '"._sqln($filter["filter_card_status"])."'";
		}
		
		if (isset($filter["filter_uid"]) && !empty($filter["filter_uid"])) {
			$flt .= "and ci.uid = '"._sqln($filter["filter_uid"])."'";
			$_flt .= "and sui.uid = '"._sqln($filter["filter_uid"])."'";
		}
		
		
		if (isset($filter["filter_doc_num"])) {
			$flt .= "and d.DocNum like '%"._sqln($filter["filter_doc_num"])."%'";
			$_flt .= "and d.DocNum like '%"._sqln($filter["filter_doc_num"])."%'";
		}
		
		if (isset($filter["filter_order_by"])) {
			$orderBySorting = $filter["filter_order_by"];
		} else {
			$orderBySorting = 'asc';
    }

		
    $orderBy = "ci.card_date " . $orderBySorting;
		if (isset($filter["filter_activity_date"])) {
      if ($filter["filter_activity_date"] == ACTIVITY_DATE_TYPE_A) {
        $orderBy = "ci.card_date " . $orderBySorting;
      }			
      if ($filter["filter_activity_date"] == ACTIVITY_DATE_TYPE_O) {
        $orderBy = "d.DocPeceiptDate ".$orderBySorting.", d.doc_id desc";
      }			
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

			$activities = array();
			$this->db->prepare($query);
			if($this->db->query()) {
				while ($this->db->fetch()){

					$this->db->Record["DTotal"] = bg_money($this->db->Record["DTotal"]);
					$activities[$this->db->Record["doc_id"]] = $this->db->Record;
					$activities[$this->db->Record["doc_id"]]["ITEM_A"] = array();
					$activities[$this->db->Record["doc_id"]]["ITEM_C"] = array();
					$activities[$this->db->Record["doc_id"]]["ITEM_OPTIONS"] = array();
				}
			} else {
				msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
				return false;
			}
			
			if (count($activities)) {
					foreach($activities as $doc_id => $item) {
						$activities[$doc_id]["ITEM_OPTIONS"] = $this->getDocOptions($doc_id);
						$activities[$doc_id]["ITEM_A"] = $this->getDocCards($doc_id, $uid);
						$activities[$doc_id]["ITEM_C"] = $this->getDocSuppliers($doc_id, $uid);
					}
				}
			}
			
			return $activities;
			*/
  		$query = " 
  		select 
        d.doc_id
        , d.partner_id
        , d.uid
        , d.DType
        , d.Docdate
        , d.DocNum
        , d.DTotal
        , p.PName 
        , ci.card_date
        , ci.card_notes
        , ci.card_status
        , CONCAT(m.member_name, ' ', m.member_family) as fullName, m.member_login
  			from 
        card_items as ci 
        inner join docs as d on ((d.doc_id = ci.doc_id) )  
        left join partners as p on (p.partner_id = d.partner_id)  
        left join members as m on (m.uid = ci.uid)
  		where 
  		  d.DType='".DOC_TYPE_ORDER."' 
  		  ".($uid?" and (ci.uid='"._sqln($uid)."')":"")." 
      ".$flt."
  		order by " . $orderBy . "
  		".$limit;

			$activities = array();
			$doc_data = array();
			$this->db->prepare($query);
			if($this->db->query()) {
				while ($this->db->fetch()){
				  $this->db->Record["Docdate"] = dt_convert($this->db->Record["Docdate"], DT_SQL, DT_BG, DT_DATE);
				  $this->db->Record["card_date"] = dt_convert($this->db->Record["card_date"], DT_SQL, DT_BG, DT_DATE);
          $this->db->Record["DTotal"] = bg_money($this->db->Record["DTotal"]);
					$activities[$this->db->Record["doc_id"]]["ITEM_A"][] = $this->db->Record;
					$activities[$this->db->Record["doc_id"]]["ITEM_C"] = array();
					$doc_data[$this->db->Record["doc_id"]] = array("DocNum" => $this->db->Record["DocNum"], "PName" => $this->db->Record["PName"]);
				}
			} else {
				msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
				return false;
			}

    $orderBy = "sui.CDate " . $orderBySorting;
		if (isset($filter["filter_activity_date"])) {
      if ($filter["filter_activity_date"] == ACTIVITY_DATE_TYPE_A) {
        $orderBy = "sui.CDate " . $orderBySorting;
      }			
      if ($filter["filter_activity_date"] == ACTIVITY_DATE_TYPE_O) {
        $orderBy = "d.DocPeceiptDate ".$orderBySorting.", d.doc_id desc";
      }			
		}

  		$query = " 
  		select 
        d.doc_id
        , d.partner_id
        , d.uid
        , d.DType
        , d.Docdate
        , d.DocNum
        , d.DTotal
        , p.PName 
        , s.CName
        , sui.*
        , CONCAT(m.member_name, ' ', m.member_family) as fullName, m.member_login
  			from 
        supplier_items as sui  
        inner join docs as d on ((d.doc_id = sui.doc_id) )  
        left join partners as p on (p.partner_id = d.partner_id)  
        left join suppliers as s on (s.c_id = sui.c_id) 
        left join members as m on (m.uid = sui.uid)
  		where 
  		  d.DType='".DOC_TYPE_ORDER."' 
  		  ".($uid?" and (sui.uid='"._sqln($uid)."')":"")." 
      ".$_flt."
  		order by " . $orderBy . "
  		".$limit;

			$this->db->prepare($query);
			if($this->db->query()) {
				while ($this->db->fetch()){
				  $this->db->Record["CDate"] = dt_convert($this->db->Record["CDate"], DT_SQL, DT_BG, DT_DATE);
				
				  if(isset($activities[$this->db->Record["doc_id"]])) {
            $activities[$this->db->Record["doc_id"]]["ITEM_C"][] = $this->db->Record;
          } else {
  				//	$activities[$this->db->Record["doc_id"]]["ITEM_A"][] = array();
  					$activities[$this->db->Record["doc_id"]]["ITEM_C"][] = $this->db->Record;
          }
          
          if(!isset($doc_data[$this->db->Record["doc_id"]])) {
					$doc_data[$this->db->Record["doc_id"]] = array("DocNum" => $this->db->Record["DocNum"], "PName" => $this->db->Record["PName"]);
          }
				}
			} else {
				msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
				return false;
			}
			
			
			if (/*count($activities)*/false) {
					foreach($activities as $doc_id => $item) {
					//	$activities[$doc_id]["ITEM_C"] = $this->getDocSuppliers($doc_id, $uid);
						if(count($activities[$doc_id]["ITEM_C"])) {
              foreach($activities[$doc_id]["ITEM_C"] as $k => $v) {
                $activities[$doc_id]["ITEM_C"][$k]["DocNum"] = $doc_data[$doc_id]["DocNum"];
                $activities[$doc_id]["ITEM_C"][$k]["PName"] = $doc_data[$doc_id]["PName"];
              }
            }
					}

			}
//printArray($activities);
		return $activities;	
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
			
		  $card = array_unique_associative($card);
			$supplier = array_unique_associative($supplier);
			
//			$card = array();
//			$supplier =  array();
			$can_edit = false;
			$can_finish_order = true;
			
////////////////////////////////////////////////////////////////////////////////
  		if (count($card)) {	
  		  foreach($card as $key => $val) {
//  		    $rec_hash = md5($doc_id.''.$val['uid'].''.$val['card_date']);
 // 		    $card[$key]['rec_hash'] = $rec_hash;
          if(empty($val['rec_hash'])) {
            $val['rec_hash'] = md5(time().rand(1000,4000));
          }
          $rec_hash = $val['rec_hash'];
          

  				$_card_status = (!empty($val["card_status"])?CARD_STATUS_F:CARD_STATUS_W);
  				if($_card_status == CARD_STATUS_W ) $can_finish_order = false;
  				$val["card_date"] = dt_convert($val["card_date"], DT_BG, DT_SQL, DT_DATE);
  				
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
  						insert into card_items set 
  							doc_id = '".$this->db->escape($doc_id)."'
  							, uid = '".$this->db->escape((int)$val["uid"])."'
  							, card_date = '".$this->db->escape($val["card_date"])."'
  							, card_notes = '"._sqls($val["card_notes"])."'
  							, card_status = '".$this->db->escape($_card_status)."' 
				        , rec_hash = '".$this->db->escape($rec_hash)."'
  					";
          } else {
   					$query = "
  						update card_items set 
  							doc_id = '".$this->db->escape($doc_id)."'
  							, uid = '".$this->db->escape((int)$val["uid"])."'
  							, card_date = '".$this->db->escape($val["card_date"])."'
  							, card_notes = '"._sqls($val["card_notes"])."'
  							, card_status = '".$this->db->escape($_card_status)."' 
        			where 
        			  rec_hash ='".$this->db->escape($rec_hash)."'
  					";
          }
          $this->db->prepare($query);
					if(!$this->db->query()) {
						msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
						$can_finish_order = false;	
					} else {
						$can_edit = true;
					}
    		    
  		  }
      }
 ////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
      if (count($supplier)) {
  			foreach($supplier as $key => $val) {
  			 // $rec_hash = md5($doc_id.''.$val['c_id'].''.$val['uid'].''.$val['CDate']);
  				$_CStatus = (!empty($val["CStatus"])?CARD_STATUS_F:CARD_STATUS_W);
  				if($val["CStatus"] == CARD_STATUS_W ) $can_finish_order = false;
  
  				$val["CDate"] = dt_convert($val["CDate"], DT_BG, DT_SQL, DT_DATE);
  				//$supplier[$key]['rec_hash'] = $rec_hash;
          if(empty($val['rec_hash'])) {
            $val['rec_hash'] = md5(time().rand(1000,4000));
          }
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
    					insert into supplier_items set 
    						doc_id = '".$this->db->escape($doc_id)."'
    						, uid = '".$this->db->escape((int)$val["uid"])."'
    						, c_id = '".$this->db->escape((int)$val["c_id"])."'
    						, CDate = '".$this->db->escape($val["CDate"])."'
    						, CNotes = '".$this->db->escape(trim($val["CNotes"]))."'
    						, CSum = '".$this->db->escape((float)$val["CSum"])."' 
    						, CStatus = '".$this->db->escape($_CStatus)."' 
    						, rec_hash = '".$this->db->escape($rec_hash)."'
    				";
          } else {
    				$query = "
    					update supplier_items set 
    						doc_id = '".$this->db->escape($doc_id)."'
    						, uid = '".$this->db->escape((int)$val["uid"])."'
    						, c_id = '".$this->db->escape((int)$val["c_id"])."'
    						, CDate = '".$this->db->escape($val["CDate"])."'
    						, CNotes = '".$this->db->escape(trim($val["CNotes"]))."'
    						, CSum = '".$this->db->escape((float)$val["CSum"])."' 
    						, CStatus = '".$this->db->escape($_CStatus)."' 
        			where 
        			  rec_hash ='".$this->db->escape($rec_hash)."'
    				";
          }
          
          $this->db->prepare($query);
					if(!$this->db->query()) {
						msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
						$can_finish_order = false;	
					} else {
						$can_edit = true;
					}
  				
  				
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
	
				$this->db->prepare($query);
				if(!$this->db->query()) {
					msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
				}
			}
		}

*/
/*
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
		*/
    /*	
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
		*/
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