<?php
//include_once("model/Model.php");
include_once(DIR_MODEL."payoffice".SLASH."payoffice.php");
include_once(DIR_MODEL."reports".SLASH."reports.php");

class ControllerPayoffice extends Controller {
	private $payoffices;
	private $payoffice;
	private $case_id;

	public function __construct()  
    {  
    	parent::__construct();
    	$this->template = 'payoffice/payoffice.php';	
      $this->model = new ModelPayoffice();
      $this->model_report = new ModelReports();

    } 
	
	public function invoke()
	{
		$route = _g("route", "dashboard");
		$page_tab = _g("tab", "home");
		$this->data["route"] = $route;
		$this->data["page_tab"] = $page_tab;
		
		$this->act = _p("act", _g("act",""));
		$this->case_id = _p("case_id", _g("case_id",""));

		$this->contractors = array();
		$this->report_product_stock = array();
		$SName = "SYSTEM_PAYOFFICE";

		if ($page_tab == "edit_paycase") { 

			switch ($this->act) {
				case "update":
					$data = _p("case", array());
					$this->case_id = $this->model->updateCase($this->case_id, $data);
					$this->data["action"] = "index.php?route=payoffice&tab=edit_paycase&token=".get_token()."&act=update&case_id=".$this->case_id;
					break;
				case "edit":
					$this->data["action"] = "index.php?route=payoffice&tab=edit_paycase&token=".get_token()."&act=edit&case_id=".$this->case_id;
					break;
				case "cancel":
					$this->data["action"] = "index.php?route=payoffice&tab=new_paycase&token=".get_token()."&act=cancel";
					break;
				case "list":
					$this->data["action"] = "index.php?route=payoffice&tab=list&token=".get_token()."&act=list";
					break;
				case "delete":
					$this->data["action"] = "index.php?route=payoffice&tab=list&token=".get_token()."&act=list";
					break;
				case "new":
					default:
					$this->data["action"] = "index.php?route=payoffice&tab=new_paycase&token=".get_token()."&act=new";
					break;
			}

		
		} else if ($page_tab ==  "payment") {
			switch ($this->act) {
				case "list":
					$this->data["action"] = "index.php?route=payoffice&tab=payment&token=".get_token()."&act=list";
					break;
				case "delete":
					if (canDoOnDelete()) {
						$this->model->deteleNewCase($this->case_id);
					} else {
						msg_add("Нямате права за изтриване!", MSG_ERROR);
					}
					$this->data["action"] = "index.php?route=payoffice&tab=payment&token=".get_token()."&act=list";
					break;
				case "new":
					default:
					$this->data["action"] = "index.php?route=payoffice&tab=payment&token=".get_token()."&act=new";
					break;
			}
    
    } else if ($page_tab == "list") {
			switch ($this->act) {
				case "list":
					$this->data["action"] = "index.php?route=payoffice&tab=list&token=".get_token()."&act=list";
					break;
				case "delete":
					if (canDoOnDelete()) {
						$this->model->deteleCase($this->case_id);
					} else {
						msg_add("Нямате права за изтриване!", MSG_ERROR);
					}
					$this->data["action"] = "index.php?route=payoffice&tab=list&token=".get_token()."&act=list";
					break;
				case "new":
					default:
					$this->data["action"] = "index.php?route=payoffice&tab=new_paycase&token=".get_token()."&act=new";
					break;
			}
		
		} else if ($page_tab == "stock") {
 			$this->data["action"] = HTTP_SERVER."index.php?route=payoffice&tab=stock&token=".get_token()."";
			$this->data["action_new"] = HTTP_SERVER."index.php?route=payoffice&tab=stock&sub_mode=form&token=".get_token();
			$this->data["action_edit_stock"] = HTTP_SERVER."index.php?route=payoffice&tab=stock&sub_mode=form&token=".get_token()."&act=edit&product_id=";
      $this->data["action_delete_stock"] = HTTP_SERVER."index.php?route=payoffice&tab=stock&sub_mode=list&token=".get_token()."&act=delete&product_id=";

      $sub_mode = _p("sub_mode", _g("sub_mode",""));
      $product_id = _p("product_id", _g("product_id",""));
      
      if ($sub_mode == "form" && $_SERVER['REQUEST_METHOD'] === 'POST') { // 
        $product = _p("stock", array());
        
        if (isset($product["type"])) {
          $this->model_report->synQuantity($product_id, $product);
        }
        if (isset($product["number"])) {
          $this->model_report->createProduct($product);
        }
      }
      
      if ($product_id && $this->act == "delete") {
        $this->model_report->removeProduct($product_id);
      }
      
      $pflt_filter = array(); 
			$pflt_filter["report_product_name"] = _g("report_product_name", "");
			$pflt_filter["report_product_qty"] = _g("report_product_qty", "");
      
      $filter = array();
			if (!empty($pflt_filter['report_product_name'])) {
				$filter["report_product_name"] = $pflt_filter['report_product_name'];
			}
			if (!empty($pflt_filter['report_product_qty'])) {
				$filter["report_product_qty"] = $pflt_filter['report_product_qty'];
			}
			
			$this->report_product_stock = $this->model_report->getProductStocks($filter);
			
			if ($product_id) {
        $this->data["product_stock"] = $this->model_report->getProductStock($product_id);
        $this->data["product_stock_history"] = $this->model_report->getProductStockHistory($product_id);
      }
/*
print "<pre>";
print_r($this->report_product_stock);
print "</pre>";
die();
*/
			$this->data["report_product_stock"] = $this->report_product_stock;
			$this->data["report_product_stock"] = $this->report_product_stock;
			$this->data["sub_mode"] = $sub_mode;
			$this->data["product_id"] = $product_id;
   } else {
			switch ($this->act) {
				case "update":
					$this->data["action"] = "index.php?route=payoffice&tab=edit_paycase&token=".get_token()."&act=update&case_id=".$this->case_id;
					break;
				case "edit":
					$this->data["action"] = "index.php?route=payoffice&tab=edit_paycase&token=".get_token()."&act=edit&case_id=".$this->case_id;
					break;
				case "cancel":
					$this->data["action"] = "index.php?route=payoffice&tab=new_paycase&token=".get_token()."&act=cancel";
					break;
				case "list":
					$this->data["action"] = "index.php?route=payoffice&tab=list&token=".get_token()."&act=list";
					break;
				case "new":
					default:
					$this->data["action"] = "index.php?route=payoffice&tab=new_paycase&token=".get_token()."&act=new";
					break;
			}
		}	
      if ($page_tab ==  "payment") {
			$pflt_filter = build_filter();

			if ($pflt_filter['filter_case_partner_id'] != '*') {
				$filter["filter_case_partner_id"] = $pflt_filter['filter_case_partner_id'];
			}
			if ($pflt_filter['filter_case_payment_type'] != '*') {
				$filter["filter_case_payment_type"] = $pflt_filter['filter_case_payment_type'];
			}
			if ($pflt_filter['filter_case_user_id'] != '*') {
				$filter["filter_case_user_id"] = $pflt_filter['filter_case_user_id'];
			}
			if ($pflt_filter['filter_case_type'] != '*') {
				$filter["filter_case_type"] = $pflt_filter['filter_case_type'];
			}

			if (!empty($pflt_filter['filter_case_doc_num'])) {
				$filter["filter_case_doc_num"] = $pflt_filter['filter_case_doc_num'];
			}
			if (!empty($pflt_filter['filter_case_from_date'])) {
				$filter["filter_case_from_date"] = $pflt_filter['filter_case_from_date'];
			}
			if (!empty($pflt_filter['filter_case_date_of_payment'])) {
				$filter["filter_case_date_of_payment"] = $pflt_filter['filter_case_date_of_payment'];
			}
			if (!empty($pflt_filter['filter_case_from_date'])) {
				$filter["filter_case_from_date"] = $pflt_filter['filter_case_from_date'];
			}
			if (!empty($pflt_filter['filter_case_to_date'])) {
				$filter["filter_case_to_date"] = $pflt_filter['filter_case_to_date'];
			}
			
			$this->data["all_users"] = $this->model_report->getUsers();
			$this->data["all_suppliers"] = $this->model_report->getSuppliers();

			$this->Pagination = new clsPagination($SName, HTTP_SERVER."index.php?route=payoffice&tab=payment&token=".get_token()."&act=list");
			$this->Pagination->updateCountItem($this->model->getTotalNewCase($filter));
			$filter["page_limit"] = $this->Pagination->getLimit();
			$this->payoffices = $this->model->getNewCases($filter);
			$this->payoffice = array(); // $this->model->getCase($this->case_id);

			$this->data["styles"] = $this->Pagination->getStyles();
			$this->data["pagination"] = $this->Pagination->displayPagination();


			$this->data["case_total"] = $this->model->getCompanyCase();
			$this->data["payoffice"] = $this->payoffice;
			$this->data["payoffices"] = $this->payoffices;
			$this->data["pflt_filter"] = $pflt_filter;
      
      } else {
			$pflt_filter = build_filter();
			
			if ($pflt_filter['filter_case_type'] != '*') {
				$filter["filter_case_type"] = $pflt_filter['filter_case_type'];
			}
			if (!empty($pflt_filter['filter_case_from_date'])) {
				$filter["filter_case_from_date"] = $pflt_filter['filter_case_from_date'];
			}
			if (!empty($pflt_filter['filter_case_to_date'])) {
				$filter["filter_case_to_date"] = $pflt_filter['filter_case_to_date'];
			}
			if (!empty($pflt_filter['filter_case_from_num'])) {
				$filter["filter_case_from_num"] = $pflt_filter['filter_case_from_num'];
			}
			if (!empty($pflt_filter['filter_case_to_num'])) {
				$filter["filter_case_to_num"] = $pflt_filter['filter_case_to_num'];
			}

			$this->Pagination = new clsPagination($SName, HTTP_SERVER."index.php?route=payoffice&tab=list&token=".get_token()."&act=list");
			$this->Pagination->updateCountItem($this->model->getTotalCase($filter));
			$filter["page_limit"] = $this->Pagination->getLimit();
			$this->payoffices = $this->model->getCases($filter);
			$this->payoffice = $this->model->getCase($this->case_id);

			$this->data["styles"] = $this->Pagination->getStyles();
			$this->data["pagination"] = $this->Pagination->displayPagination();


			$this->data["case_total"] = $this->model->getCompanyCase();
			$this->data["payoffice"] = $this->payoffice;
			$this->data["payoffices"] = $this->payoffices;
			$this->data["pflt_filter"] = $pflt_filter;
}			
			
			$this->data["action_save"] = HTTP_SERVER."index.php?route=payoffice&tab=edit_paycase&token=".get_token()."&act=update&case_id=".$this->case_id;
			$this->data["action_cancel"] = HTTP_SERVER."index.php?route=payoffice&tab=new_paycase&token=".get_token()."&act=new";
			$this->data["action_edit"] = HTTP_SERVER."index.php?route=payoffice&tab=edit_paycase&token=".get_token()."&act=edit&case_id=";
			if ($page_tab ==  "payment") {
        $this->data["action_delete"] = HTTP_SERVER."index.php?route=payoffice&tab=payment&token=".get_token()."&act=delete&case_id=";
      } else {
        $this->data["action_delete"] = HTTP_SERVER."index.php?route=payoffice&tab=list&token=".get_token()."&act=delete&case_id=";
      }
			$this->data["case_id"] = $this->case_id;
			$this->data["act"] = $this->act;
		
		$this->Out();	
	}
		
}

?>