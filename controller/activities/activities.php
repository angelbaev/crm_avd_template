<?php
include_once(DIR_MODEL."activities".SLASH."activities.php");

class ControllerActivities extends Controller {
	private $activities;
	private $activity;
	private $doc_id;

	public function __construct()  
    {  
    	parent::__construct();
    	$this->template = 'activities/activities.php';	
      $this->model = new ModelActivities();

    } 
	
	public function invoke()
	{
		$route = _g("route", "dashboard");
		$page_tab = _g("tab", "home");
		$this->data["route"] = $route;
		$this->data["page_tab"] = $page_tab;
		
		$this->act = _p("act", _g("act","out"));
		$this->doc_id = _p("doc_id", _g("doc_id",""));
		$this->uid = "";
		$SName = "SYSTEM_ACTIVITIES_ALL";
		


		if ($page_tab == "edit") { 

			switch ($this->act) {
				case "update":
					//$data = _p("supplier", array());
				//	$this->contract_id = $this->model->updateSupplier($this->contract_id, $data);
					$this->model->updateCard($this->doc_id);
					$this->data["action"] = "index.php?route=activities&tab=edit&token=".get_token()."&act=update&doc_id=".$this->doc_id;
					break;
				case "edit":
					$this->data["action"] = "index.php?route=activities&tab=edit&token=".get_token()."&act=edit&doc_id=".$this->doc_id;
					break;
				case "cancel":
					$this->data["action"] = "index.php?route=activities&tab=edit&token=".get_token()."&act=edit";
					break;
				case "delete":
					$this->data["action"] = "index.php?route=activities&tab=all&token=".get_token()."&act=list";
					break;
				case "all":
					default:
					$this->data["action"] = "index.php?route=activities&tab=all&token=".get_token()."&act=edit";
					break;
			}

		
		} else if ($page_tab == "owner") {
			$SName = "SYSTEM_ACTIVITIES_OWN";
			$this->uid = get_uid();
			switch ($this->act) {
				case "edit":
					$this->data["action"] = "index.php?route=activities&tab=edit&token=".get_token()."&act=all";
					break;
				case "delete":
					$this->model->deteleSupplier($this->contract_id);
					$this->data["action"] = "index.php?route=activities&tab=owner&token=".get_token()."&act=list";
					break;
				case "all":
					default:
					$this->data["action"] = "index.php?route=activities&tab=owner&token=".get_token()."&act=list";
					break;
			}
		
		} else {
			switch ($this->act) {
				case "edit":
					$this->data["action"] = "index.php?route=activities&tab=edit&token=".get_token()."&act=edit";
					break;
				case "delete":
					//$this->model->deteleSupplier($this->contract_id);
					$this->data["action"] = "index.php?route=activities&tab=all&token=".get_token()."&act=list";
					break;
				case "all":
					default:
					$this->data["action"] = "index.php?route=activities&tab=all&token=".get_token()."&act=list";
					break;
			}
		}	

			$pflt_filter = build_filter();
			if ($pflt_filter['filter_card_status'] != '*') {
				$filter["filter_card_status"] = $pflt_filter['filter_card_status'];
        } else {
           $filter["filter_card_status"] = CARD_STATUS_W; 
        }

			if ($pflt_filter['filter_activity_date'] != '*') {
				$filter["filter_activity_date"] = $pflt_filter['filter_activity_date'];
			}
                        
			if ($pflt_filter['filter_uid'] != '*') {
				$filter["filter_uid"] = $pflt_filter['filter_uid'];
			}
			if ($pflt_filter['filter_supplier_id'] != '*') {
				$filter["filter_supplier_id"] = $pflt_filter['filter_supplier_id'];
			}

			if ($pflt_filter['filter_activity_period'] != '') {
				$filter["filter_activity_period"] = $pflt_filter['filter_activity_period'];
			}

                        if ($pflt_filter['filter_doc_num'] != '') {
				$filter["filter_doc_num"] = $pflt_filter['filter_doc_num'];
			}
			if ($pflt_filter['filter_activity_period'] != '') {
				$filter["filter_activity_period"] = $pflt_filter['filter_activity_period'];
			}
			
			if ($pflt_filter['filter_order_by'] != '') {
				$filter["filter_order_by"] = $pflt_filter['filter_order_by'];
			} else {
                                $pflt_filter['filter_order_by'] = 'asc';
				$filter["filter_order_by"] = $pflt_filter['filter_order_by'];
                        }
                        //print "<pre>"; print_r($filter); die();
			$this->Pagination = new clsPagination($SName, HTTP_SERVER."index.php?route=activities&tab=".($this->uid?"owner":"all")."&token=".get_token()."&act=list");
                        $this->Pagination->numRows = 30;
			
			if ($this->act !== 'update' || $this->act !== 'edit') {
                            /*
                            if (isset($_GET['view']) && $_GET['view'] == 'new') {
			$this->Pagination->updateCountItem($this->model->getNewTotal($filter, $this->uid));//$this->model->getTotalRoles()
			$filter["page_limit"] = $this->Pagination->getLimit();
			$this->activities = $this->model->getNewActivities($filter, $this->uid);
                                
                            } else {
			$this->Pagination->updateCountItem($this->model->getTotal());//$this->model->getTotalRoles()
			$filter["page_limit"] = $this->Pagination->getLimit();

			$this->activities = $this->model->getActivities($filter, $this->uid);
                                
                            }
                            */
                            $this->Pagination->updateCountItem($this->model->getNewTotal($filter));//$this->model->getTotalRoles()
                            $filter["page_limit"] = $this->Pagination->getLimit();

                            $this->activities = $this->model->getNewActivities($filter, $this->uid);
			}
			$this->doc = $this->model->getDoc($this->doc_id, $this->uid);
//			printArray($this->role)."<bR>";
			//data
			
			$this->data["action_save"] = HTTP_SERVER."index.php?route=activities&tab=edit&token=".get_token()."&act=update&doc_id=".$this->doc_id;
			$this->data["action_cancel"] = HTTP_SERVER."index.php?route=activities&tab=edit&token=".get_token()."&act=edit&doc_id=".$this->doc_id;
			$this->data["action_edit"] = HTTP_SERVER."index.php?route=activities&tab=edit&token=".get_token()."&act=edit&doc_id=";
			$this->data["action_delete"] = HTTP_SERVER."index.php?route=activities&tab=all&token=".get_token()."&act=delete&doc_id=";

			$this->data["doc_id"] = $this->doc_id;
			$this->data["act"] = $this->act;

			$this->data["activities"] = $this->activities;
			$this->data["document"] = $this->doc;
			$this->data["pflt_filter"] = $pflt_filter;

			$this->data["styles"] = $this->Pagination->getStyles();
			$this->data["pagination"] = $this->Pagination->displayPagination();
		
		/*
			$this->data["all_patners"] = $this->all_patners;
			$this->data["patners"] = $this->patners;
			$this->data["patner"] = $this->patner;
			$this->data["patner_types"] = $GLOBALS["PARTNER_TYPES"];
			$this->data["q_search"] = _g("q", "");

		*/
		$this->Out();	
	}
	
}

?>