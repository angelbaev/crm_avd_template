<?php
include_once(DIR_MODEL."reports".SLASH."reports.php");

class ControllerReports extends Controller {
	private $report_partner = array();
	private $report_user = array();
	private $report_supplier = array();
	private $report_category = array();

	public function __construct()  
    {  
    	parent::__construct();
    	$this->template = 'reports/reports.php';	
      $this->model = new ModelReports();

    } 
	
	public function invoke()
	{
		$route = _g("route", "dashboard");
		$page_tab = _g("tab", "home");
		$this->data["route"] = $route;
		$this->data["page_tab"] = $page_tab;
		
		$this->act = _p("act", _g("act",""));

		$SName = "SYSTEM_REPORTS";
		$this->report_partner = array();
		$this->report_user = array();
		$this->report_timetracker = array();
		$this->report_supplier = array();
		$this->report_category = array();
		$this->report_not_payed = array();
		
		if ($page_tab == "users") { 
			$this->data["action"] = HTTP_SERVER."index.php?route=reports&tab=users&token=".get_token()."";

			$pflt_filter = array(); 
			$pflt_filter["report_from_date"] = _g("report_from_date", "");
			$pflt_filter["report_to_date"] = _g("report_to_date", "");
			$pflt_filter["report_user_id"] = _g("report_user_id", "*");
			$pflt_filter["report_order_type"] = _g("report_order_type", "*");

			if ($pflt_filter['report_user_id'] != "*") {
				$filter["report_user_id"] = $pflt_filter['report_user_id'];
			}
			if ($pflt_filter['report_order_type'] != "*") {
				$filter["report_order_type"] = $pflt_filter['report_order_type'];
			}
			if (!empty($pflt_filter['report_from_date'])) {
				$filter["report_from_date"] = $pflt_filter['report_from_date'];
			}
			if (!empty($pflt_filter['report_to_date'])) {
				$filter["report_to_date"] = $pflt_filter['report_to_date'];
			}
			$sum_total = 0;
			$sum_total = bg_money($sum_total);
			if (count($filter)){
				$this->report_user = $this->model->reportUsers($filter);
				foreach ($this->report_user as $k => $v) {
					if ((int) $v["DTotal"] == 0 ) continue;
					$v["DTotal"] = str_replace(",", "", $v["DTotal"]);
					$sum_total += $v["DTotal"];
				}
			}
			$pflt_filter["report_total"] = bg_money($sum_total);


		} else if ($page_tab == "suppliers") {
			$this->data["action"] = HTTP_SERVER."index.php?route=reports&tab=suppliers&token=".get_token()."";

			$pflt_filter = array(); 
			$pflt_filter["report_from_date"] = _g("report_from_date", "");
			$pflt_filter["report_to_date"] = _g("report_to_date", "");
			$pflt_filter["report_supplier_id"] = _g("report_supplier_id", "*");
			$pflt_filter["report_order_type"] = _g("report_order_type", "*");

			if ($pflt_filter['report_supplier_id'] != "*") {
				$filter["report_supplier_id"] = $pflt_filter['report_supplier_id'];
			}
			if ($pflt_filter['report_order_type'] != "*") {
				$filter["report_order_type"] = $pflt_filter['report_order_type'];
			}
			if (!empty($pflt_filter['report_from_date'])) {
				$filter["report_from_date"] = $pflt_filter['report_from_date'];
			}
			if (!empty($pflt_filter['report_to_date'])) {
				$filter["report_to_date"] = $pflt_filter['report_to_date'];
			}
			$sum_total = 0;
			$sum_total = bg_money($sum_total);
			if (count($filter)){
				$this->report_supplier = $this->model->reportSuppliers($filter);
				foreach ($this->report_supplier as $k => $v) {
					if ((int) $v["CSum"] == 0 ) continue;
					$v["CSum"] = str_replace(",", "", $v["CSum"]);
					$sum_total += $v["CSum"];
				}
			}
			$pflt_filter["report_total"] = bg_money($sum_total);


		}	else if ($page_tab == "category") {
			$this->data["action"] = HTTP_SERVER."index.php?route=reports&tab=category&token=".get_token()."";
			
			$pflt_filter = array(); 
			$pflt_filter["report_from_date"] = _g("report_from_date", "");
			$pflt_filter["report_to_date"] = _g("report_to_date", "");
			$pflt_filter["report_category_id"] = _g("report_category_id", "*");
			$pflt_filter["report_order_type"] = _g("report_order_type", "*");

			if ($pflt_filter['report_category_id'] != "*") {
				$filter["report_category_id"] = $pflt_filter['report_category_id'];
			}
			if ($pflt_filter['report_order_type'] != "*") {
				$filter["report_order_type"] = $pflt_filter['report_order_type'];
			}
			if (!empty($pflt_filter['report_from_date'])) {
				$filter["report_from_date"] = $pflt_filter['report_from_date'];
			}
			if (!empty($pflt_filter['report_to_date'])) {
				$filter["report_to_date"] = $pflt_filter['report_to_date'];
			}
			$sum_total = 0;
			$sum_total = bg_money($sum_total);
			if (count($filter)){
				$this->report_category = $this->model->reportCategories($filter);
				foreach ($this->report_category as $k => $v) {
					if ((int) $v["PSum"] == 0 ) continue;
					$v["PSum"] = str_replace(",", "", $v["PSum"]);
					$sum_total += $v["PSum"];
				}
			}
			$pflt_filter["report_total"] = bg_money($sum_total);

		} else if ($page_tab == "timetracker") {
 			$this->data["action"] = HTTP_SERVER."index.php?route=reports&tab=timetracker&token=".get_token()."";
 			
			$pflt_filter = array(); 
			$pflt_filter["report_from_date"] = _g("report_from_date", "");
			$pflt_filter["report_to_date"] = _g("report_to_date", "");
			$pflt_filter["report_user_id"] = _g("report_user_id", "*");
			$pflt_filter["report_work_time"] = _g("report_work_time", "*");

			if ($pflt_filter['report_user_id'] != "*") {
				$filter["report_user_id"] = $pflt_filter['report_user_id'];
			}
			if ($pflt_filter['report_work_time'] != "*") {
				$filter["report_work_time"] = $pflt_filter['report_work_time'];
			}
			if (!empty($pflt_filter['report_from_date'])) {
				$filter["report_from_date"] = $pflt_filter['report_from_date'];
			}
			if (!empty($pflt_filter['report_to_date'])) {
				$filter["report_to_date"] = $pflt_filter['report_to_date'];
			}

			if (count($filter)){
				$this->report_timetracker = $this->model->reportWorkTime($filter);
				foreach ($this->report_timetracker as $k => $v) {
				
				/*
					if ((int) $v["DTotal"] == 0 ) continue;
					$v["DTotal"] = str_replace(",", "", $v["DTotal"]);
					$sum_total += $v["DTotal"];
					*/
				}
			}
 			
 			
   } else if ($page_tab == "not_payed") {
 			$this->data["action"] = HTTP_SERVER."index.php?route=reports&tab=not_payed&token=".get_token()."";
			
      $pflt_filter = array(); 
			$pflt_filter["report_from_date"] = _g("report_from_date", "");
			$pflt_filter["report_to_date"] = _g("report_to_date", "");
		//	$pflt_filter["report_total"] = _g("report_total", "");

			if (!empty($pflt_filter['report_from_date'])) {
				$filter["report_from_date"] = $pflt_filter['report_from_date'];
			}
			if (!empty($pflt_filter['report_to_date'])) {
				$filter["report_to_date"] = $pflt_filter['report_to_date'];
			}
			if (!empty($pflt_filter['report_total'])) {
			//	$filter["report_total"] = $pflt_filter['report_total'];
			}
			//$pflt_filter["report_total"] = bg_money($pflt_filter["report_total"]);
			
			$sum_total = 0;
			$sum_total = bg_money($sum_total);
			$sum_total_not_payed = 0;
			$sum_total_not_payed = bg_money($sum_total_not_payed);
			if (count($filter)){
			 $this->report_not_payed = $this->model->getDocs($filter);
			
			 foreach ($this->report_not_payed as $k => $v) {
			
			   //if ((int) $v["DTotal"] == 0 ) continue;
			   $v["DAdvance"] = str_replace(",", "", $v["DAdvance"]);
			   $v["DSurcharge"] = str_replace(",", "", $v["DSurcharge"]);
			   
			   $sum_total += ($v["DAdvance"] + $v["DSurcharge"]);
			   if (!$v["doc_payed"]) {
          $sum_total_not_payed += $v["DAdvance"];
         } 
			   if (!$v["doc_payed2"]) {
          $sum_total_not_payed += $v["DSurcharge"];
         } 
			 }
			}
			$pflt_filter["report_total"] = bg_money($sum_total);
			$pflt_filter["report_total_not_payed"] = bg_money($sum_total_not_payed);
   
   } else {
			$this->data["action"] = HTTP_SERVER."index.php?route=reports&tab=patners&token=".get_token()."";
			
			$pflt_filter = array(); 
			$pflt_filter["report_from_date"] = _g("report_from_date", "");
			$pflt_filter["report_to_date"] = _g("report_to_date", "");
			$pflt_filter["report_partner_id"] = _g("report_partner_id", "*");
			$pflt_filter["report_order_type"] = _g("report_order_type", "*");

			if ($pflt_filter['report_partner_id'] != "*") {
				$filter["report_partner_id"] = $pflt_filter['report_partner_id'];
			}
			if ($pflt_filter['report_order_type'] != "*") {
				$filter["report_order_type"] = $pflt_filter['report_order_type'];
			}
			if (!empty($pflt_filter['report_from_date'])) {
				$filter["report_from_date"] = $pflt_filter['report_from_date'];
			}
			if (!empty($pflt_filter['report_to_date'])) {
				$filter["report_to_date"] = $pflt_filter['report_to_date'];
			}
			$sum_total = 0;
			$sum_total = bg_money($sum_total);
			if (count($filter)){
				$this->report_partner = $this->model->reportPartners($filter);
				foreach ($this->report_partner as $k => $v) {
					if ((int) $v["DTotal"] == 0 ) continue;
					$v["DTotal"] = str_replace(",", "", $v["DTotal"]);
					$sum_total += $v["DTotal"];
				}
			}
			$pflt_filter["report_total"] = bg_money($sum_total);
		}	


			$this->data["all_partners"] = $this->model->getPartners();
			$this->data["all_users"] = $this->model->getUsers();
			$this->data["all_suppliers"] = $this->model->getSuppliers();
			$this->data["all_category"] = $GLOBALS["SYSTEM_CALC"];
			$this->data["order_types"] = $GLOBALS["DOC_TYPES"];
			$this->data["report_partner"] = $this->report_partner;
			$this->data["report_user"] = $this->report_user;
			$this->data["report_supplier"] = $this->report_supplier;
			$this->data["report_category"] = $this->report_category;
			$this->data["report_timetracker"] = $this->report_timetracker;
			$this->data["report_not_payed"] = $this->report_not_payed;
			$this->data["report_filter"] = $pflt_filter;
/*
			$this->Pagination = new clsPagination($SName, HTTP_SERVER."index.php?route=contractors&tab=list&token=".get_token()."&act=list");
			$this->Pagination->updateCountItem($this->model->getTotalContractors($filter));
			$filter["page_limit"] = $this->Pagination->getLimit();
			$this->contractors = $this->model->getSuppliers($filter);
			$this->all_suppliers = $this->model->getSuppliers(array());
			$this->contractor = $this->model->getSupplier($this->contract_id);

			$this->data["styles"] = $this->Pagination->getStyles();
			$this->data["pagination"] = $this->Pagination->displayPagination();


			$this->data["all_suppliers"] = $this->all_suppliers;
			$this->data["supplier"] = $this->contractor;
			$this->data["suppliers"] = $this->contractors;
			$this->data["pflt_filter"] = $pflt_filter;
			
			
			$this->data["action_save"] = HTTP_SERVER."index.php?route=contractors&tab=edit_contractor&token=".get_token()."&act=update&c_id=".$this->contract_id;
			$this->data["action_cancel"] = HTTP_SERVER."index.php?route=contractors&tab=new_contractor&token=".get_token()."&act=new";
			$this->data["action_edit"] = HTTP_SERVER."index.php?route=contractors&tab=edit_contractor&token=".get_token()."&act=edit&c_id=";
			$this->data["action_delete"] = HTTP_SERVER."index.php?route=contractors&tab=list&token=".get_token()."&act=delete&c_id=";

			$this->data["c_id"] = $this->contract_id;
			$this->data["act"] = $this->act;
*/
			
		$this->Out();	
	}
	
}

?>