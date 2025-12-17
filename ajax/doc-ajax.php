<?php
  header('Content-Type: text/html; charset=windows-1251');
	include("../config.php");
	include_once(DIR_SYSTEM."function.inc.php");
	include_once(DIR_SYSTEM."db.mysql.php");
	include_once(DIR_SYSTEM."pagination.php");

	class ajPage {
		var $act;
		var $id;
		var $json;
		var $errors = array();

		public function __construct() {
			$this->act = _p("act",_g("act",""));
			$this->id = _p("id",_g("id",""));
			$this->errors = array();
			$this->json = array();
			
			switch($this->act) {
				case "remove_card_item":
					$this->removeCard();
					break;
				case "remove_supplier_item":
					$this->removeSupplier();
					break;
				case "save_card_item":
					$this->saveCard();
					break;
				case "save_supplier_item":
					$this->saveSupplier();
					break;
				default:
					$this->json['html'] = "";
					break;
			}
			
		}//__construct()
		
		private function removeCard() {
	    global $db;
	    
      $query = "
        delete from card_items where rec_hash = '".$db->escape($this->id)."'
      ";
      $db->prepare($query);
      if(!$db->query()) {
        $this->errors[] = "Записът не може да бъде изтрит!";
      }
    }

		private function removeSupplier() {
	    global $db;
	    
      $query = "
        delete from supplier_items where rec_hash = '".$db->escape($this->id)."'
      ";
      $db->prepare($query);
      if(!$db->query()) {
        $this->errors[] = "Записът не може да бъде изтрит!";
      }
    }
    
    private function saveCard() {
 	    global $db;
  
 	    $id = $_POST['id'];
 	    $doc_id = $_POST['doc_id'];
 	    $uid = $_POST['uid'];
 	    $card_notes = $_POST['card_notes'];
 	    $card_status = $_POST['card_status'];
 	    $rec_hash = $_POST['rec_hash'];
 	    $card_date = $_POST['card_date'];
 	    
  		$card_date = dt_convert($card_date, DT_BG, DT_SQL, DT_DATE);
      if(empty($rec_hash)) {
        $rec_hash = md5(time().rand(1000,4000));
      }
      $card_notes = mb_convert_encoding($card_notes, "windows-1251", "utf-8");
 
      $insert = true;
			$query = "
				select 
					rec_hash
				from card_items where rec_hash ='".$db->escape($rec_hash)."' LIMIT 1
			";
      $db->prepare($query);
			if($db->query()) {
				if ($rec = $db->fetch()){
				  if(isset($rec['rec_hash'])) {
            $insert = false;
          }
        }
      }
	    
	    if ($insert) {
	    
  			$query = " 
  			insert into  card_items set 
  				doc_id = '".$db->escape($doc_id)."'
  				, uid = '".$db->escape($uid)."'
  				, card_date = '".$db->escape($card_date)."'
  				, card_notes = '".$db->escape(trim($card_notes))."'
  				, card_status = '".$db->escape($card_status)."'
  				, rec_hash = '".$db->escape($rec_hash)."'
  			";
      } else {
  			$query = " 
  			update  card_items set 
  				doc_id = '".$db->escape($doc_id)."'
  				, uid = '".$db->escape($uid)."'
  				, card_date = '".$db->escape($card_date)."'
  				, card_notes = '".$db->escape(trim($card_notes))."'
  				, card_status = '".$db->escape($card_status)."'
  			where 
  			  rec_hash ='".$db->escape($rec_hash)."'
  			";
      }
      $db->prepare($query);
      if(!$db->query()) {
        $this->errors[] = "Записът не може да бъде изпълнен!";
      }
    }
    
    private function saveSupplier() {
 	    global $db;

 	    $id = $_POST['id'];
 	    $doc_id = $_POST['doc_id'];
 	    $uid = $_POST['uid'];
 	    $c_id = $_POST['c_id'];
 	    $CNotes = $_POST['CNotes'];
 	    $rec_hash = $_POST['rec_hash'];
 	    $CDate = $_POST['CDate'];
 	    $CStatus = $_POST['CStatus'];
 	    $CSum = $_POST['CSum'];
 	    $date_requested = $_POST['date_requested'];
 	    $amount_requested = $_POST['amount_requested'];

  		$CDate = dt_convert($CDate, DT_BG, DT_SQL, DT_DATE);
  		$date_requested = dt_convert($date_requested, DT_BG, DT_SQL, DT_DATE);
      if(empty($rec_hash)) {
        $rec_hash = md5(time().rand(1000,4000));
      }
      $CNotes = mb_convert_encoding($CNotes, "windows-1251", "utf-8");
      
      $insert = true;
			$query = "
				select 
					*
				from supplier_items where rec_hash ='".$db->escape($rec_hash)."' LIMIT 1
			";
      $db->prepare($query);
			if($db->query()) {
				if ($rec = $db->fetch()){
				  if(isset($rec['rec_hash'])) {
            $insert = false;
          }
        }
      }
	    
	    if ($insert) {
	    
  			$query = " 
  			insert into  supplier_items set 
  				doc_id = '".$db->escape($doc_id)."'
  				, uid = '".$db->escape($uid)."'
  				, c_id = '".$db->escape($c_id)."'
  				, CDate = '".$db->escape($CDate)."'
  				, CNotes = '".$db->escape(trim($CNotes))."'
  				, CStatus = '".$db->escape($CStatus)."'
  				, amount_requested = '".$db->escape((float)$amount_requested)."'
  				, date_requested = '".$db->escape($date_requested)."'
  				, CSum = '".$db->escape((float)$CSum)."'
  				, rec_hash = '".$db->escape($rec_hash)."'
  			";
      } else {
  			$query = " 
  			update  supplier_items set 
  				doc_id = '".$db->escape($doc_id)."'
  				, uid = '".$db->escape($uid)."'
  				, c_id = '".$db->escape($c_id)."'
  				, CDate = '".$db->escape($CDate)."'
  				, CNotes = '".$db->escape(trim($CNotes))."'
  				, CStatus = '".$db->escape($CStatus)."'
  				, amount_requested = '".$db->escape((float)$amount_requested)."'
  				, date_requested = '".$db->escape($date_requested)."'
  				, CSum = '".$db->escape((float)$CSum)."'
      		where 
      			rec_hash ='".$db->escape($rec_hash)."' 
  			";
      }
      $db->prepare($query);
      if(!$db->query()) {
        $this->errors[] = "Записът не може да бъде изпълнен!";
      }
      $this->synSupplierCase($rec_hash);  
    }

  	public function synSupplierCase($hash) {
 	    global $db;

  		$query = "
  			select 
  			*  
  			from 
  				supplier_items
  			where rec_hash = '".$db->escape($hash)."'";
  
      $supplierInfo = null;
  		$db->prepare($query);
  		if($db->query()) {
  			if ($db->fetch()){
  				$supplierInfo = $db->Record;
  			}
  		} else {
  			$this->errors[] = "DB Error: ".$db->Error;
  			return false;
  		}
  		
  		if (is_null($supplierInfo)) {
  			return false;
      }
      
  		if (empty($supplierInfo['amount_requested']) || $supplierInfo['amount_requested'] == '0.0000') {
  			return false;
      }
  
      $caseInfo = NULL;
  		$query = "
  			select 
  			*  
  			from 
  				company_new_case
  			where supplier_hash = '".$db->escape($hash)."'";
  
      $caseInfo = NULL;
  		$db->prepare($query);
  		if($db->query()) {
  			if ($db->fetch()){
  				$caseInfo = $db->Record;
  			}
  		} else {
  			$this->errors[] = "DB Error: ".$db->Error;
  			return false;
  		}
  
  		if (is_null($caseInfo)) {
  			$query = " 
  			insert into  company_new_case set 
  				doc_id = '".$db->escape($supplierInfo['doc_id'])."'
  				, uid = '".$db->escape($supplierInfo["uid"])."'
  				, partner_id = '".$db->escape($supplierInfo["c_id"])."'
  				, supplier_hash = '".$db->escape($hash)."'
  				, case_description = '".$db->escape($supplierInfo["CNotes"])."'
  				, case_date = '".$db->escape($supplierInfo["date_requested"])."'
  				, case_amount_requested = '".$db->escape((float)$supplierInfo["amount_requested"])."'
  			";
    		$db->prepare($query);
    		if(!$db->query()) {
  			 $this->errors[] = "DB Error: ".$db->Error;
    		}
  			
      } else {
  			$query = " 
  			update  company_new_case set 
  				doc_id = '".$db->escape($supplierInfo['doc_id'])."'
  				, uid = '".$db->escape($supplierInfo["uid"])."'
  				, partner_id = '".$db->escape($supplierInfo["c_id"])."'
  				, case_date = '".$db->escape($supplierInfo["date_requested"])."'
  				, case_description = '".$db->escape($supplierInfo["CNotes"])."'
  				, case_amount_requested = '".$db->escape((float)$supplierInfo["amount_requested"])."'
  			where 
          case_id = '".$db->escape($caseInfo["case_id"])."'	
  			";
    		$db->prepare($query);
    		if(!$db->query()) {
  			 $this->errors[] = "DB Error: ".$db->Error;
    		}
  			
      }
  
    }

		private function build() {
			switch($this->act) {
				case "remove_card_item":
					$this->json['html'] = "remove_card";
					break;
				case "remove_supplier_item":
					$this->json['html'] = "remove_supplier";
					break;
				default:
					$this->json['html'] = "";
					break;
			}
			
			if (!count($this->errors)) {
				$this->json['success'] = "Действието е изпълненено успешно!";
			} else {
				$this->json['error'] = implode("<br>", $this->errors);
			}
			
		}//build()
		
		
		
		public function Out() {
			$this->build();
			print __json_encode($this->json);
		}
	}
	$aj = new ajPage();
	$aj->Out();


?>