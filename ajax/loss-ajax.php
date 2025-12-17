<?php 
	include("../config.php");
	include_once(DIR_SYSTEM."function.inc.php");
	include_once(DIR_SYSTEM."db.mysql.php");
	include_once(DIR_SYSTEM."pagination.php");
	
	if (isset($_SESSION["uid"])) {
		$log_id = _p('l_id', '');
		$_act =  _p('act', '');
		switch($_act) {
			case "resolve":
				echo "Възтановяване!";
				
				echo (resolve_data($log_id)? "Информацията е възтановена успешно!":"Грешка при възтановяване!");
				break;
			case "delete":
				echo (remove_data($log_id)? "Информацията е изтрита успешно!":"Грешка при изтриване!");
				break;
			default:
				echo "Невалидна операция!";
				break;
		}
	} else {
		echo "Невалиден потребител в системата!";
	}
	

function resolve_data($log_id) {
	global $db;
	$L_data = array();
	
	$transaction = true;
	$query = "
		select 
			*
		from  
			logs 
		where 
			log_id = '"._sqln($log_id)."' LIMIT 1
		";
		$db->prepare($query);
		if($db->query()) {
			if ($db->fetch()) {
				$L_data = $db->Record;
			}
		} else {
			$transaction = false;
		}
		if (count($L_data)) {
			$_data = unserialize($L_data['lost_data']);
			foreach($_data as $key => $val) {
				if ($L_data['table_name'] == "supplier_items") {
								//$val["CDate"] = dt_convert($val["CDate"], DT_BG, DT_SQL, DT_DATE);
								$query = " 
								insert into  supplier_items set 
									doc_id = '"._sqls($val["doc_id"])."'
									, uid = '"._sqls($val["uid"])."'
									, c_id = '"._sqls($val["c_id"])."'
									, CDate = '"._sqls($val["CDate"])."'
									, CNotes = '"._sqls(trim($val["CNotes"]))."'
									, CStatus = '"._sqls($val["CStatus"])."'
									, CSum = '"._sqls((float)$val["CSum"])."'
								";

								$db->prepare($query);
								if(!$db->query()) {
									//print ("DB Error: <br>".$db->Error);
									//lwrite('(8) docs::activities - insert into supplier_items: ('.$query.') :: '.$this->db->Error);
									$transaction = false;
								}
				
				} else {
						//$val["card_date"] = dt_convert($val["card_date"], DT_BG, DT_SQL, DT_DATE);
						$query = " 
						insert into  card_items set 
							doc_id = '"._sqls($val["doc_id"])."'
							, uid = '"._sqls($val["uid"])."'
							, card_date = '"._sqls($val["card_date"])."'
							, card_notes = '"._sqls(trim($val["card_notes"]))."'
							, card_status = '"._sqls($val["card_status"])."'
						";

						$db->prepare($query);
						if(!$db->query()) {
							$transaction = false;
						}
				
				}
			}
			
			
		}
		if ($transaction) {
						$query = " 
						update  logs set 
							lost_status = '"._sqln('0')."'
						where
							log_id = '"._sqln($log_id)."'
						";

						$db->prepare($query);
						if(!$db->query()) {
							$transaction = false;
						}
		
		}
		
		return $transaction;
}
function remove_data($log_id) {
	global $db;
	
	$query = "
		delete from logs where log_id = '"._sqln($log_id)."'
		";
		$db->prepare($query);
		if($db->query()) {
			return true;
		} else {
			return false;
		}

}	




$_act =  _g('view_log', '');
if ($_act) {
	$log_data = array();
	$query = "
		select 
			*
		from  
			logs 
		where 
			(1) order by log_id desc   LIMIT 20
		";
		$db->prepare($query);
		if($db->query()) {
			while ($db->fetch()) {
				$db->Record['lost_data'] = unserialize($db->Record['lost_data']);
				$db->Record['post_all'] = unserialize($db->Record['post_all']);
				$log_data[] = $db->Record;
			}
		} 

printArray($log_data);
}
?>