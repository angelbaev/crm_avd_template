<?php
//include_once("model/Model.php");
include_once(DIR_MODEL."settings".SLASH."settings.php");

class ControllerSettings extends Controller {
	private $roles;
	private $role;
	private $role_id;
	private $perms;
	private $members;
	private $member;
	private $member_id;

	public function __construct()  
    {  
    	parent::__construct();
    	$this->template = 'settings/settings.php';	
      $this->model = new ModelSettings();

    } 
	
	public function invoke()
	{
		$route = _g("route", "dashboard");
		$page_tab = _g("tab", "home");
		$this->data["route"] = $route;
		$this->data["page_tab"] = $page_tab;
		
		$this->act = _p("act", _g("act","out"));
		$this->role_id = _p("role_id", _g("role_id",""));
		
		if ($page_tab == "roles") {
			$this->roles = array ();
			switch ($this->act) {
				case "update":
					$data = _p("role", array());
					$this->role_id = $this->model->updateRole($this->role_id, $data);
					$this->data["action"] = "index.php?route=settings&tab=roles&token=".get_token()."&act=update&role_id=".$this->role_id;
					break;
				case "edit":
					$this->data["action"] = "index.php?route=settings&tab=roles&token=".get_token()."&act=edit&role_id=".$this->role_id;
					break;
				case "delete":
					$this->data["action"] = "index.php?route=settings&tab=roles&token=".get_token()."&act=delete&role_id=".$this->role_id;
					$this->model->deteleRole($this->role_id);
					$this->role_id = "";
					break;
				case "cancel":
					$this->data["action"] = "index.php?route=settings&tab=roles&token=".get_token()."&act=cancel";
					break;
				case "list":
					default:
					$this->data["action"] = "index.php?route=settings&tab=roles&token=".get_token()."&act=list";
					break;	
			}
			$this->Pagination = new clsPagination("SYSTEM_ROLES", HTTP_SERVER."index.php?route=settings&tab=roles&token=".get_token()."&act=list");
			$this->Pagination->updateCountItem($this->model->getTotalRoles());//$this->model->getTotalRoles()
			$filter["page_limit"] = $this->Pagination->getLimit();
			$this->roles = $this->model->getRoles($filter);
			$this->role = $this->model->getRole($this->role_id);
//			printArray($this->role)."<bR>";
			//data
			
			$this->data["action_save"] = HTTP_SERVER."index.php?route=settings&tab=roles&token=".get_token()."&act=update&role_id=".$this->role_id;
			$this->data["action_cancel"] = HTTP_SERVER."index.php?route=settings&tab=roles&token=".get_token()."&act=list";
			$this->data["action_edit"] = HTTP_SERVER."index.php?route=settings&tab=roles&token=".get_token()."&act=edit&role_id=";
			$this->data["action_delete"] = HTTP_SERVER."index.php?route=settings&tab=roles&token=".get_token()."&act=delete&role_id=";

			$this->data["role_id"] = $this->role_id;
			$this->data["act"] = $this->act;
			$this->data["roles"] = $this->roles;
			$this->data["role"] = $this->role;
			$this->data["styles"] = $this->Pagination->getStyles();
			$this->data["pagination"] = $this->Pagination->displayPagination();

		} else if ($page_tab == "perms") {
			$this->perms = array();
			switch ($this->act) {
				case "update":
					$data = _p("perms", array());
					//printArray($data);
					if ($this->model->updatePermissions($data)) {
						$this->msg_add("Правата за достъп са променени успешно!", MSG_SUCCESS);
					} else {
						$this->msg_add("Грешка при предаване на данните!", MSG_ERROR);
					}
					//$this->role_id = $this->model->updateRole($this->role_id, $data);
					$this->data["action"] = "index.php?route=settings&tab=perms&token=".get_token()."&act=update";
					break;
				case "cancel":
					$this->data["action"] = "index.php?route=settings&tab=perms&token=".get_token()."&act=cancel";
					break;
				case "list":
					default:
					$this->data["action"] = "index.php?route=settings&tab=perms&token=".get_token()."&act=list";
					break;	
			}
			
			$this->perms = $this->model->getPermissions();
			
			$this->data["action_save"] = HTTP_SERVER."index.php?route=settings&tab=perms&token=".get_token()."&act=update";
			$this->data["action_cancel"] = HTTP_SERVER."index.php?route=settings&tab=perms&token=".get_token()."&act=list";

			$this->data["act"] = $this->act;
			$this->data["perms"] = $this->perms;
		} else {
			
			$this->member_id = _p("member_id", _g("member_id",""));
			$this->members = array ();
			switch ($this->act) {
				case "update":
					$data = _p("member", array());
					$this->member_id = $this->model->updateMember($this->member_id, $data);
					$this->data["action"] = "index.php?route=settings&tab=users&token=".get_token()."&act=update&member_id=".$this->member_id;
					break;
				case "edit":
					$this->data["action"] = "index.php?route=settings&tab=users&token=".get_token()."&act=edit&member_id=".$this->member_id;
					break;
				case "delete":
					$this->data["action"] = "index.php?route=settings&tab=users&token=".get_token()."&act=delete&member_id=".$this->member_id;
					$this->model->deteleMember($this->member_id);
					$this->role_id = "";
					break;
				case "cancel":
					$this->data["action"] = "index.php?route=settings&tab=users&token=".get_token()."&act=cancel";
					break;
				case "list":
					default:
					$this->data["action"] = "index.php?route=settings&tab=users&token=".get_token()."&act=list";
					break;	
			}
			$this->Pagination = new clsPagination("SYSTEM_MEMBERS", HTTP_SERVER."index.php?route=settings&tab=users&token=".get_token()."&act=list");
			$this->Pagination->updateCountItem($this->model->getTotalMembers());
			$filter["page_limit"] = $this->Pagination->getLimit();
			$this->members = $this->model->getMembers($filter);
			$this->member = $this->model->getMember($this->member_id);
			$this->roles = $this->model->getRoles();
			
			$this->data["action_save"] = HTTP_SERVER."index.php?route=settings&tab=users&token=".get_token()."&act=update&member_id=".$this->member_id;
			$this->data["action_cancel"] = HTTP_SERVER."index.php?route=settings&tab=users&token=".get_token()."&act=list";
			$this->data["action_edit"] = HTTP_SERVER."index.php?route=settings&tab=users&token=".get_token()."&act=edit&member_id=";
			$this->data["action_delete"] = HTTP_SERVER."index.php?route=settings&tab=users&token=".get_token()."&act=delete&member_id=";

			$this->data["member_id"] = $this->member_id;
			$this->data["act"] = $this->act;
			$this->data["members"] = $this->members;
			$this->data["member"] = $this->member;
			$this->data["roles"] = $this->roles;
			$this->data["statuses"] = $GLOBALS["SYSTEM_USER_STATUS"];
			$this->data["show_forgotten_activities"] = $GLOBALS["SYSTEM_SHOW_FORGOTTEN_ACTIVITY"];
			$this->data["styles"] = $this->Pagination->getStyles();
			$this->data["pagination"] = $this->Pagination->displayPagination();
		
		}
		$this->Out();	
	}
	
	private function logOut()
	{
		session_destroy();
//		exit();
	}

	private function logIn()
	{
		$user = _p("name");
		$password = _p("password");
		
		if (empty($user) && empty($password)) {
			$this->msg_add("Полетата <b>Потребител</b> и <b>Парола</b> не са попълнени!", MSG_ERROR);
			return false;
		} else if (empty($user)) {
			$this->msg_add("Полетата <b>Потребител</b> не е попълнено!", MSG_ERROR);
			return false;
		} else if (empty($password)) {
			$this->msg_add("Полетата <b>Парола</b> не е попълнено!", MSG_ERROR);
			return false;
		} else {
			$this->model->getMember($user, $password);
		}
	}
	
}

?>