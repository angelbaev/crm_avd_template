<?php
class ModelLogin {
	private $db;
	public function __construct()  
  {  
  	global $db;
  	$this->db = &$db;
  } 
	
	public function getMember($name, $password) {
		$query = " 
		select 
			uid
			, member_login
			, member_password 
		from 
			members 
		where 
                        status = '1' 
                and         
			member_login ='".$this->db->escape($name)."' 
		and 
			member_password = '".md5($this->db->escape($password))."' 
		LIMIT 1";
		$this->db->prepare($query);
		if($this->db->query()) {
			if ($user = $this->db->fetch()){
				session_start();
				$_SESSION["logged"] = true;
				$_SESSION["uid"] = $user["uid"];
				$_SESSION["username"] = $user["member_login"];
				$_SESSION["token"] = session_id();
				 
				$this->updateSession($_SESSION["token"], $user["uid"]);
				$this->setLoginUserTimeTraker($user["uid"]);
				
				msg_add("Успешно логване ще бъдете пренасочен към началната страница!", MSG_SUCCESS);
				redirect(HTTP_SERVER."index.php?route=dashboard&tab=home&token=".get_token(), 2);
				//return true;
			} else {
				msg_add("Няма регистиран потребител с това име <b>".$name."</b>!", MSG_ERROR);
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
	}
	
	public function updateSession($token, $uid) {

		$query = " 
		update  members set 
			session_id = '".$this->db->escape($token)."'
		where 
			uid ='".$this->db->escape($uid)."' 
		";

		$this->db->prepare($query);
		if(!$this->db->query()) {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
	}
	
	public function setLoginUserTimeTraker($uid) {
      $key_yyyy_mm_dd = date('Y-m-d');
      $query  = "select * from timetraker where key_yyyy_mm_dd = '".$key_yyyy_mm_dd."' and user_id = '".(int) $uid."' LIMIT 1";
      $this->db->prepare($query);
      if($this->db->query()) {
        $row = $this->db->fetch();
      } 

      if (!$this->db->num_rows()) {
        $start_time = time();
        $query  = "insert into timetraker set key_yyyy_mm_dd = '".$key_yyyy_mm_dd."', week = '".date('W')."', user_id = '".$uid."', start_time = '".date('Y-m-d H:i:s')."', time_duration = '0', total_time = '0' ";
        $this->db->prepare($query); 
        $this->db->query();
        $_SESSION["WORK_TIME_START"] = $start_time;  
        $_SESSION["WORK_TIME_DURATION"] = 0;  
      } else {
        $start_time = time();
        $_SESSION["WORK_TIME_START"] = $start_time;  
        $_SESSION["WORK_TIME_DURATION"] = $row['time_duration'];
      }
  }
  
	public function setLogOutUserTimeTraker($uid) {
      $key_yyyy_mm_dd = date('Y-m-d');
      $query  = "select * from timetraker where key_yyyy_mm_dd = '".$key_yyyy_mm_dd."' and user_id = '".(int) $uid."' LIMIT 1";
      $this->db->prepare($query);
      if($this->db->query()) {
        $row = $this->db->fetch();
      }
      if ($this->db->num_rows()) {
        $end_time = time();
        $query = "update timetraker set end_time = '".date('Y-m-d H:i:s')."', time_duration = '".($row['time_duration'] + $_SESSION["WORK_TIME_DURATION"])."' where key_yyyy_mm_dd = '".$key_yyyy_mm_dd."' and user_id = '".(int) $uid."'";
        $this->db->prepare($query);
        $this->db->query();
      } 
	
  }  
}
?>