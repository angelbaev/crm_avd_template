<?php
//include_once("model/Model.php");
include_once(DIR_MODEL."patners".SLASH."patners.php");

class ControllerPatners extends Controller {
	private $patners;
	private $patner;
	private $partner_id;

	public function __construct()  
    {  
    	parent::__construct();
    	$this->template = 'patners/patners.php';	
      $this->model = new ModelPatners();

    } 
	
	public function invoke()
	{
		$route = _g("route", "dashboard");
		$page_tab = _g("tab", "home");
		$this->data["route"] = $route;
		$this->data["page_tab"] = $page_tab;
		
		$this->act = _p("act", _g("act","out"));
		$this->partner_id = _p("partner_id", _g("partner_id",""));
		
		if ($this->partner_id && $this->act != "list") {
			$ch_tab = "edit_partner";
		} else {
			$ch_tab = "new_partner";
		}
		
			$this->patners = array ();
			switch ($this->act) {
				case "update":
					$data = _p("patner", array());
					$this->partner_id = $this->model->updatePatner($this->partner_id, $data);
					$this->data["action"] = "index.php?route=patners&tab=".$ch_tab."&token=".get_token()."&act=update&partner_id=".$this->partner_id;
					break;
				case "edit":
					$this->data["action"] = "index.php?route=patners&tab=".$ch_tab."&token=".get_token()."&act=edit&partner_id=".$this->partner_id;
					break;
				case "delete":
					$this->data["action"] = "index.php?route=patners&tab=list&token=".get_token()."&act=delete&partner_id=".$this->partner_id;

					if (canDoOnDelete()) {
						$this->model->detelePatner($this->partner_id);
						$this->partner_id = "";
					} else {
						msg_add("Нямате права за изтриване!", MSG_ERROR);
					}
					break;
				case "cancel":
					$this->data["action"] = "index.php?route=patners&tab=new_partner&token=".get_token()."&act=cancel";
					break;
				case "search":
					break;
				case "list":
					default:
					$this->data["action"] = "index.php?route=patners&tab=list&token=".get_token()."&act=list";
					break;	
			}

			$pflt_filter = build_filter();
			if (!empty($pflt_filter['filter_partner_name'])) {
				$filter["filter_partner_name"] = $pflt_filter['filter_partner_name'];
			}
			if ($pflt_filter['filter_partner_type'] != "*") {
				$filter["filter_partner_type"] = $pflt_filter['filter_partner_type'];
			}

			$this->Pagination = new clsPagination("SYSTEM_PARTNERS", HTTP_SERVER."index.php?route=patners&tab=list&token=".get_token()."&act=list");
			$this->Pagination->updateCountItem($this->model->getTotalPatners($filter));//$this->model->getTotalRoles()
			$filter["page_limit"] = $this->Pagination->getLimit();
			$this->patners = $this->model->getPatners($filter);
			$this->all_patners = $this->model->getPatners(array());
			$this->patner = $this->model->getPatner($this->partner_id);
//			printArray($this->role)."<bR>";
			//data
			
			$this->data["action_save"] = HTTP_SERVER."index.php?route=patners&tab=".$ch_tab."&token=".get_token()."&act=update&partner_id=".$this->partner_id;
			$this->data["action_cancel"] = HTTP_SERVER."index.php?route=patners&tab=new_partner&token=".get_token()."&act=list";
			$this->data["action_edit"] = HTTP_SERVER."index.php?route=patners&tab=edit_partner&token=".get_token()."&act=edit&partner_id=";
			$this->data["action_delete"] = HTTP_SERVER."index.php?route=patners&tab=list&token=".get_token()."&act=delete&partner_id=";
			$this->data["action_search"] = HTTP_SERVER."index.php?route=patners&tab=list&token=".get_token()."&act=search&q=";

			$this->data["partner_id"] = $this->partner_id;
			$this->data["act"] = $this->act;
			$this->data["all_patners"] = $this->all_patners;
			$this->data["patners"] = $this->patners;
			$this->data["patner"] = $this->patner;
			$this->data["patner_types"] = $GLOBALS["PARTNER_TYPES"];
			$this->data["pflt_filter"] = $pflt_filter;
			$this->data["styles"] = $this->Pagination->getStyles();
			$this->data["pagination"] = $this->Pagination->displayPagination();

		
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