<?php
//include_once("model/Model.php");
include_once(DIR_MODEL."contractors".SLASH."contractors.php");

class ControllerContractors extends Controller {
	private $contractors;
	private $contractor;
	private $contract_id;

	public function __construct()  
    {  
    	parent::__construct();
    	$this->template = 'contractors/contractors.php';	
      $this->model = new ModelContractors();

    } 
	
	public function invoke()
	{
		$route = _g("route", "dashboard");
		$page_tab = _g("tab", "home");
		$this->data["route"] = $route;
		$this->data["page_tab"] = $page_tab;
		
		$this->act = _p("act", _g("act",""));
		$this->contract_id = _p("c_id", _g("c_id",""));
		
		$this->contractors = array();
		$SName = "SYSTEM_CONTRACTORS";
		if ($page_tab == "edit_contractor") { 

			switch ($this->act) {
				case "update":
					$data = _p("supplier", array());
					$this->contract_id = $this->model->updateSupplier($this->contract_id, $data);
					$this->data["action"] = "index.php?route=contractors&tab=edit_contractor&token=".get_token()."&act=update&c_id=".$this->contract_id;
					break;
				case "edit":
					$this->data["action"] = "index.php?route=contractors&tab=edit_contractor&token=".get_token()."&act=edit&c_id=".$this->contract_id;
					break;
				case "cancel":
					$this->data["action"] = "index.php?route=contractors&tab=new_contractor&token=".get_token()."&act=cancel";
					break;
				case "list":
					$this->data["action"] = "index.php?route=contractors&tab=list&token=".get_token()."&act=list";
					break;
				case "delete":
					print "Delete !";
					$this->data["action"] = "index.php?route=contractors&tab=list&token=".get_token()."&act=list";
					break;
				case "new":
					default:
					$this->data["action"] = "index.php?route=contractors&tab=new_contractor&token=".get_token()."&act=new";
					break;
			}

		
		} else if ($page_tab == "list") {
			switch ($this->act) {
				case "list":
					$this->data["action"] = "index.php?route=contractors&tab=list&token=".get_token()."&act=list";
					break;
				case "delete":
					if (canDoOnDelete()) {
						$this->model->deteleSupplier($this->contract_id);
					} else {
						msg_add("Нямате права за изтриване!", MSG_ERROR);
					}
					$this->data["action"] = "index.php?route=contractors&tab=list&token=".get_token()."&act=list";
					break;
				case "new":
					default:
					$this->data["action"] = "index.php?route=contractors&tab=new_contractor&token=".get_token()."&act=new";
					break;
			}
		} else if ($page_tab == "printing") {
					$this->data["action"] = "index.php?route=contractors&tab=list&token=".get_token()."&act=list";
					$act = _p("action", "");
					//$temp_file = tempnam(sys_get_temp_dir());
					
					if ($act == "upload") {
          	require(DIR_EXCEL_LIB.'php-excel-reader/excel_reader2.php');
          	require(DIR_EXCEL_LIB.'SpreadsheetReader.php');
          
          	date_default_timezone_set('UTC');
          	
          	$file_name = $_FILES['file_excel']['name'];
          	$file_tmp =$_FILES['file_excel']['tmp_name'];
          	$temp_file = sys_get_temp_dir().SLASH.$file_name;
          	if (move_uploaded_file($file_tmp, $temp_file)) {
              	try {
              	  $this->model->clearPrinting();
              		$Spreadsheet = new SpreadsheetReader($temp_file);
              		$Sheets = $Spreadsheet -> Sheets();
              		foreach ($Sheets as $Index => $Name) {
              			$Spreadsheet -> ChangeSheet($Index);
              			foreach ($Spreadsheet as $Key => $Row) {
                     if ($Key == 1) continue;
                     
                     if (count(array_filter($Row)) == 0) {
                      break;
                     }
                     $this->model->importPrinting($Row);
                    }
                 }
                 if (file_exists($temp_file)) {
                    unlink($temp_file);
                 }
              } catch (Exception $E)
            	{
            		echo $E -> getMessage();
            	}
            }
            
          }
		      $this->data["printings"] = $this->model->getPrintings();
		} else {
			switch ($this->act) {
				case "update":
					$this->data["action"] = "index.php?route=contractors&tab=edit_contractor&token=".get_token()."&act=update&c_id=".$this->contract_id;
					break;
				case "edit":
					$this->data["action"] = "index.php?route=contractors&tab=edit_contractor&token=".get_token()."&act=edit&c_id=".$this->contract_id;
					break;
				case "cancel":
					$this->data["action"] = "index.php?route=contractors&tab=new_contractor&token=".get_token()."&act=cancel";
					break;
				case "list":
					$this->data["action"] = "index.php?route=contractors&tab=list&token=".get_token()."&act=list";
					break;
				case "new":
					default:
					$this->data["action"] = "index.php?route=contractors&tab=new_contractor&token=".get_token()."&act=new";
					break;
			}
		}	

			$pflt_filter = build_filter();
			if (!empty($pflt_filter['filter_supplier_sector'])) {
				$filter["filter_supplier_sector"] = $pflt_filter['filter_supplier_sector'];
			}
			if (!empty($pflt_filter['filter_supplier_name'])) {
				$filter["filter_supplier_name"] = $pflt_filter['filter_supplier_name'];
			}

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

			
		$this->Out();	
	}
	
}

?>