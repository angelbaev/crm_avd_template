<?php
class ModelSettings {
	private $db;
	public function __construct()  
  {  
  	global $db;
  	$this->db = &$db;
  } 
	
	public function getRoles($filter = array()) {
		$flt = "";
		if (isset($filter["page_limit"])) {
			$flt .= $filter["page_limit"];
		}
		$query = " select * from roles ".$flt;
		$roles = array();
		$this->db->prepare($query);
		if($this->db->query()) {
			while ($this->db->fetch()){
				$roles[$this->db->Record["role_id"]] = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $roles;
	}
	public function getTotalRoles() {
		$cnt = 0;
		$query = " select count(role_id) as cnt from roles";
		$roles = array();
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
	public function getRole($role_id) {
		$query = " select * from roles where role_id='".$this->db->escape($role_id)."'";
		$role = array(
			"role_id" => ""
			, "role_name" => ""
			, "role_description" => ""
			, "role_can_del" => 0
			, "uid_created" => ""
			, "uid_modified" => ""
		);
		$this->db->prepare($query);
		if($this->db->query()) {
			if ($this->db->fetch()){
				$role = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $role;
	}
	public function updateRole($role_id, $data) {
		if (count($data)) {
			if (empty($data["role_name"])) {
					msg_add("Полето <b>Наименование</b> не е попълнено!", MSG_ERROR);
					return false;
			}

			if ($role_id) {
				$query = " 
				update  roles set 
					role_name = '".$this->db->escape($data["role_name"])."'
					, role_description = '".$this->db->escape($data["role_description"])."' 
					, role_can_del = '".$this->db->escape($data["role_can_del"])."' 
					, uid_modified = '".$this->db->escape($_SESSION["uid"])."'
				where 
					role_id ='".$this->db->escape($role_id)."' 
				";
				$this->db->prepare($query);
				if(!$this->db->query()) {
					msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
					return false;
				} else {
					msg_add("Ролята беше редактирана успешно!", MSG_SUCCESS);
					return $role_id;
				}
				
			} else {
				$query = " 
				insert into  roles set 
					role_name = '".$this->db->escape($data["role_name"])."'
					, role_description = '".$this->db->escape($data["role_description"])."' 
					, role_can_del = '".$this->db->escape($data["role_can_del"])."' 
					, uid_created = '".$this->db->escape($_SESSION["uid"])."'
				";
				$this->db->prepare($query);
				if(!$this->db->query()) {
					msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
					return false;
				} else {
					msg_add("Ролята беше добавена успешно!", MSG_SUCCESS);
					$role_id = $this->db->insert_id();
					return $role_id;
				}
			
			}
		} else {
			msg_add("Грешка при предаване на данните!", MSG_ERROR);
			return false;
		}

	}
	public function deteleRole($role_id) {
		if ($role_id) {
				$can_delete = false;

					$query = " 
					select role_id from members 
					where 
						role_id ='".$this->db->escape($role_id)."' 
					";
					$this->db->prepare($query);
					if ($this->db->query()) {
						if ($this->db->num_rows() > 0 ) {
							msg_add("Тази роля има връзка с потребители и не може да бъде изтрита!", MSG_WARNING);
							return false;
						}
					} else {
						msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
						return false;
					}

/*
					$query = " 
					select role_id from perms 
					where 
						role_id ='".$this->db->escape($role_id)."' 
					";
					$this->db->prepare($query);
					if ($this->db->query()) {
						if ($this->db->num_rows() > 0 ) {
							msg_add("Тази роля има връзка с правата и не може да бъде изтрита!", MSG_WARNING);
							return false;
						}
					} else {
						msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
						return false;
					}
*/				
					$query = " 
					delete from roles 
					where 
						role_id ='".$this->db->escape($role_id)."' 
					";
			
					$this->db->prepare($query);
					if(!$this->db->query()) {
						msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
						return false;
					} else {
						msg_add("Ролята беше изтрита успешно!", MSG_SUCCESS);
						return true;
					}
					
		
		}
	}
	
	// permissions
	
	public function getPermissions() {
		$query = " 
			select 
			r.role_id
			, r.role_name 
			, p.perm_id
			, p.documents
			, p.reports
			, p.patners
			, p.contractors
			, p.activities
			, p.payoffice
			, p.settings
			from roles as r 
			left join perms as p on (p.role_id = r.role_id)
			
			";
		$perms = array();
		$this->db->prepare($query);
		if($this->db->query()) {
			while ($this->db->fetch()){
				$perms[$this->db->Record["role_id"]] = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $perms;
	
	}
	
	public function updatePermissions($data) {
		if (count($data)) {
			foreach ($data as $role_id => $perm) {
				if ($perm["perm_id"]) {
					//update
					$query = "
						update  perms set 
							documents = '".$this->db->escape($perm["documents"])."'
							, reports = '".$this->db->escape($perm["reports"])."'
							, patners = '".$this->db->escape($perm["patners"])."'
							, contractors = '".$this->db->escape($perm["contractors"])."'
							, activities = '".$this->db->escape($perm["activities"])."'
							, payoffice = '".$this->db->escape($perm["payoffice"])."'
							, settings = '".$this->db->escape($perm["settings"])."'
							, uid_modified = '".$this->db->escape($_SESSION["uid"])."'
						where 
							role_id = '".$this->db->escape($role_id)."'	
						and
							perm_id =  '".$this->db->escape($perm["perm_id"])."'	
					";
					$this->db->prepare($query);
					$this->db->query();
				} else {
					//insert
					$query = "
						insert into perms set 
							role_id = '".$this->db->escape($role_id)."'
							, documents = '".$this->db->escape($perm["documents"])."'
							, reports = '".$this->db->escape($perm["reports"])."'
							, patners = '".$this->db->escape($perm["patners"])."'
							, contractors = '".$this->db->escape($perm["contractors"])."'
							, activities = '".$this->db->escape($perm["activities"])."'
							, payoffice = '".$this->db->escape($perm["payoffice"])."'
							, settings = '".$this->db->escape($perm["settings"])."'
							, uid_created = '".$this->db->escape($_SESSION["uid"])."'
					";
					$this->db->prepare($query);
					$this->db->query();
				
				}
			}
			return true;
		} else {
			return false;
		}
	}
	
	// Members 
	public function getMembers($filter = array()) {
		$flt = "";
		if (isset($filter["page_limit"])) {
			$flt .= $filter["page_limit"];
		}
		$query = " select m.*, r.role_name from members as m left join roles as r on (r.role_id = m.role_id) ".$flt;
		$members = array();
		$this->db->prepare($query);
		if($this->db->query()) {
			while ($this->db->fetch()){
				$members[$this->db->Record["uid"]] = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $members;
	}
	public function getTotalMembers() {
		$cnt = 0;
		$query = " select count(uid) as cnt from members";
		$roles = array();
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
	public function getMember($uid) {
	
		$query = " select * from members where uid='".$this->db->escape($uid)."'";
		$member = array(
			"uid" => ""
			, "member_login" => ""
			, "member_name" => ""
			, "member_family" => ""
			, "member_password" => ""
			, "member_inc" => ""
			, "role_id" => ""
			, "session_id" => ""
			, "show_forgotten_activity" => ""
			, "dt_created" => ""
			, "dt_modified" => ""
			, "uid_created" => ""
			, "uid_modified" => ""
		);

		$this->db->prepare($query);
		if($this->db->query()) {
			if ($this->db->fetch()){
				$member = $this->db->Record;
			}
		} else {
			msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
			return false;
		}
		return $member;
		
	}
	
	public function updateMember($uid, $data) {
	
		if (count($data)) {
			if ($uid) {
				$query = " 
				update  members set 
					member_name = '".$this->db->escape($data["member_name"])."'
					, member_family = '".$this->db->escape($data["member_family"])."' 
					".((!empty($data["member_password"]) && ($data["member_password"] == $data["re_member_password"]))?
					", member_password = '".$this->db->escape(md5($data["member_password"]))."' ":""
					
					)."
					, member_inc = '".$this->db->escape($data["member_inc"])."' 
					, status = '".$this->db->escape($data["status"])."' 
                                        , show_forgotten_activity = '".$this->db->escape($data["show_forgotten_activity"])."'      
					, role_id = '".$this->db->escape($data["role_id"])."' 
					, dt_modified = NOW() 
					, uid_modified = '".$this->db->escape($_SESSION["uid"])."'
				where 
					uid ='".$this->db->escape($uid)."' 
				";
				$this->db->prepare($query);
				if(!$this->db->query()) {
					msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
					return false;
				} else {
					msg_add("Данните на потребителя бяха редактирани успешно!", MSG_SUCCESS);
					return $uid;
				}
				
			} else {
				$query = " select member_login from members where member_login='".$this->db->escape($data["member_login"])."'";
				$this->db->prepare($query);
				if($this->db->query()) {
					if ($this->db->fetch()){
						msg_add("Потребителското име <b>(".$data["member_login"].")</b> вече е заето!", MSG_WARNING);
						return false;
					}
				}

				$query = " 
				insert into  members set 
					member_login = '".$this->db->escape($data["member_login"])."'
					, member_name = '".$this->db->escape($data["member_name"])."' 
					, member_family = '".$this->db->escape($data["member_family"])."' 
					, member_password = '".$this->db->escape(md5($data["member_password"]))."'
					, member_inc = '".$this->db->escape($data["member_inc"])."' 
					, status = '".$this->db->escape($data["status"])."' 
					, role_id = '".$this->db->escape($data["role_id"])."' 
                                        , show_forgotten_activity = '".$this->db->escape($data["show_forgotten_activity"])."'      
					, dt_created = NOW() 
					, uid_created = '".$this->db->escape($_SESSION["uid"])."'
				";
				$this->db->prepare($query);
				if(!$this->db->query()) {
					msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
					return false;
				} else {
					msg_add("Потребителя беше добавен успешно успешно!", MSG_SUCCESS);
					$uid = $this->db->insert_id();
					return $uid;
				}
			
			}
		} else {
			msg_add("Грешка при предаване на данните!", MSG_ERROR);
			return false;
		}

	}
	
	public function deteleMember($uid) {
	
		if ($uid) {
					$query = " 
					delete from members 
					where 
						uid ='".$this->db->escape($uid)."' 
					";
			
					$this->db->prepare($query);
					if(!$this->db->query()) {
						msg_add("DB Error: <br>".$this->db->Error, MSG_ERROR);
						return false;
					} else {
						msg_add("Потребителя беше изтрит успешно!", MSG_SUCCESS);
						return true;
					}
					
		
		}
		
	}
	
}
?>