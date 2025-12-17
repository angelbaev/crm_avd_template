<?php
//include_once("model/Model.php");
include_once(DIR_MODEL."documents".SLASH."documents.php");

class ControllerDocuments extends Controller {
	private $documents;
	private $doc;
	private $doc_id;

	public function __construct()  
    {  
    	parent::__construct();
    	$this->template = 'documents/documents.php';	
      $this->model = new ModelDocuments();

    } 
	
	public function invoke()
	{
		$route = _g("route", "dashboard");
		$page_tab = _g("tab", "home");
		$this->data["route"] = $route;
		$this->data["page_tab"] = $page_tab;
		
		$this->act = _p("act", _g("act","out"));
		$this->doc_id = _p("doc_id", _g("doc_id",""));
				
		$this->documents = array ();
		$pnav_tab = "all";
		$SName = "_ALL";
		$DType = (isset($_POST['doc']['DType']) && !empty($_POST['doc']['DType'])?$_POST['doc']['DType']:DOC_TYPE_OFFER);
		if ($page_tab == "edit_doc") { 
			switch ($this->act) {
				case "update":
					$data = _p("doc", array());

					//$data['DocNum'] = $this->model->getDocNumber($data['DType']);
					$this->doc_id = $this->model->updateDoc($this->doc_id, $data);
//					printArray($data);
					$this->data["action"] = "index.php?route=documents&tab=edit_doc&token=".get_token()."&act=update&doc_id=".$this->doc_id;
					break;
				case "mk_order":
					$this->doc_id = $this->model->createOrder($this->doc_id);
					$this->data["action"] = "index.php?route=documents&tab=edit_doc&edit_doc=".get_token()."&act=update&doc_id=".$this->doc_id;
					break;	
				case "edit":
					$this->data["action"] = "index.php?route=documents&tab=edit_doc&token=".get_token()."&act=edit&doc_id=".$this->doc_id;
					break;
				case "cancel":
					$this->data["action"] = "index.php?route=documents&tab=new_doc&token=".get_token()."&act=cancel";
					break;
				case "list":
					$this->data["action"] = "index.php?route=documents&tab=all&token=".get_token()."&act=list";
					break;
				case "delete":
					print "Delete !";
					$this->data["action"] = "index.php?route=documents&tab=all&token=".get_token()."&act=list";
					break;
				case "new":
					default:
					$this->data["action"] = "index.php?route=documents&tab=new_doc&token=".get_token()."&act=new";
					break;
			}
		} else if ($page_tab == "all") {
			$pnav_tab = "all";
			$SName = "_ALL";
			switch ($this->act) {
				case "list":
					$this->data["action"] = "index.php?route=documents&tab=all&token=".get_token()."&act=list";
					break;
				case "delete":
					if (canDoOnDelete()) {
						$this->model->deteleDoc($this->doc_id);
					} else {
						msg_add("Нямате права за изтриване!", MSG_ERROR);
					}
					$this->data["action"] = "index.php?route=documents&tab=all&token=".get_token()."&act=list";
					break;
				case "new":
					default:
					$this->data["action"] = "index.php?route=documents&tab=new_doc&token=".get_token()."&act=new";
					break;
			}
		
		} else if ($page_tab == "send_email") {
			switch ($this->act) {
				case "send":
					$this->send_mail();
					$this->data["action"] = "index.php?route=documents&tab=send_email&token=".get_token()."&act=send";
					break;
				case "email":
					default:
					$this->data["action"] = "index.php?route=documents&tab=send_email&token=".get_token()."&act=send";
					break;
			}
			$this->data["email_info"] = $this->model->getEmailInfo($this->doc_id);
		} else {
			switch ($this->act) {
				case "update":
					$this->data["action"] = "index.php?route=documents&tab=edit_doc&token=".get_token()."&act=update&doc_id=".$this->doc_id;
					break;
				case "edit":
					$this->data["action"] = "index.php?route=documents&tab=edit_doc&token=".get_token()."&act=edit&doc_id=".$this->doc_id;
					break;
				case "cancel":
					$this->data["action"] = "index.php?route=documents&tab=new_doc&token=".get_token()."&act=cancel";
					break;
				case "list":
					$this->data["action"] = "index.php?route=documents&tab=all&token=".get_token()."&act=list";
					break;
				case "new":
					default:
					$this->data["action"] = "index.php?route=documents&tab=new_doc&token=".get_token()."&act=new";
					break;
			}
		
		}
		
			if ($page_tab == "not_payed") {
				$filter["filter_not_payed"] = 1;
				$pnav_tab = 'not_payed';
			}
		
			$pflt_filter = build_filter();
			if (!empty($pflt_filter['filter_doc_num'])) {
				$filter["filter_doc_num"] = $pflt_filter['filter_doc_num'];
			}
			if ($pflt_filter['filter_doc_type'] != '*') {
				$filter["filter_doc_type"] = $pflt_filter['filter_doc_type'];
			}
			if ($pflt_filter['filter_doc_status'] != '*') {
				$filter["filter_doc_status"] = $pflt_filter['filter_doc_status'];
			}
			if ($pflt_filter['filter_doc_user'] != '*') {
				$filter["filter_doc_user"] = $pflt_filter['filter_doc_user'];
			}
			if ($pflt_filter['filter_doc_partner_id'] != '*') {
				$filter["filter_doc_partner_id"] = $pflt_filter['filter_doc_partner_id'];
			}

			$this->Pagination = new clsPagination("SYSTEM_DOCUMENTS".$SName, HTTP_SERVER."index.php?route=documents&tab=".$pnav_tab."&token=".get_token()."&act=list");
			$this->Pagination->updateCountItem($this->model->getTotalDocs($filter));
			$filter["page_limit"] = $this->Pagination->getLimit();
			$this->documents = $this->model->getDocs($filter);
			$this->all_partners = $this->model->getPartners(array());
			$this->doc = $this->model->getDoc($this->doc_id);/*$this->doc_id*/
			
			if(is_array($this->documents) && count($this->documents)) {
				foreach($this->documents as $k => $v) {
					$this->documents[$k]["doc_options"] = $this->model->getDocOptions($k);
					$this->documents[$k]["doc_cards"] = $this->model->getDocCards($k);
					$this->documents[$k]["doc_suppliers"] = $this->model->getDocSuppliers($k);
				}
			}
//			printArray($this->doc)."<bR>";
			//data
			
			$this->data["action_save"] = HTTP_SERVER."index.php?route=documents&tab=edit_doc&token=".get_token()."&act=update&doc_id=".$this->doc_id;
			$this->data["action_cancel"] = HTTP_SERVER."index.php?route=documents&tab=new_doc&token=".get_token()."&act=new";
			$this->data["action_edit"] = HTTP_SERVER."index.php?route=documents&tab=edit_doc&token=".get_token()."&act=edit&doc_id=";
			$this->data["action_delete"] = HTTP_SERVER."index.php?route=documents&tab=".$pnav_tab."&token=".get_token()."&act=delete&doc_id=";
			$this->data["action_order"] = HTTP_SERVER."index.php?route=documents&tab=edit_doc&token=".get_token()."&act=mk_order&doc_id=".$this->doc_id;
			$this->data["action_email"] = HTTP_SERVER."index.php?route=documents&tab=send_email&token=".get_token()."&act=email&doc_id=".$this->doc_id;

			$this->data["doc_id"] = $this->doc_id;
			$this->data["act"] = $this->act;
			$this->data["all_partners"] = $this->all_partners;
			$this->data["documents"] = $this->documents;
			$this->data["document"] = $this->doc;
			$this->data["doc_types"] = $GLOBALS["DOC_TYPES"];
			$this->data["order_status"] = $GLOBALS["ORDER_STATUS"];
			$this->data["order_payment"] = $GLOBALS["ORDER_PAYMENT"];

			$this->data["delivery_place"] = $GLOBALS["DELIVERY_PLACE"];
			$this->data["price_vat"] = $GLOBALS["PRICE_VAT"];
			$this->data["view_term"] = $GLOBALS["VIEW_TERMS"];
			
			$this->data["pflt_filter"] = $pflt_filter;
			
			
			$this->data["DType"] = $DType;
			$this->data["index_Option"] = (count($this->doc["doc_options"]) ? (count($this->doc["doc_options"])+1):2);
			$this->data["styles"] = $this->Pagination->getStyles();
			$this->data["pagination"] = $this->Pagination->displayPagination();

		
		$this->Out();	
	}
	
	private function send_mail() {
			
			$mail = _p('email', array());
			
			$email_from = $mail['From'];
			$email_subjet = $mail['Subject'];
			
			$pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i"; 
      if (preg_match($pattern, trim(strip_tags($mail['To'])))) { 
          $email_to = trim(strip_tags($mail['To'])); 
      } else { 
      		msg_add('Невалиден email!');
      		return false;
      } 
			if (empty($email_to)) {
      		msg_add('Не е въведен email!');
      		return false;
			}

			$headers = "From: " . $email_from . "\r\n";
			$headers .= "Reply-To: ". strip_tags($email_to) . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=windows-1251\r\n";

			$message = '<html><body>';
			$message .= '<table rules="all" style="border-color: #666;" cellpadding="10" width="800">';
			$message .= "<tr style='background: #eee;'><td><strong>От:</strong> </td><td>" . strip_tags($email_from) . "</td></tr>";
			$message .= "<tr><td><strong>Получател:</strong> </td><td>" . strip_tags($email_to) . "</td></tr>";
			$message .= "<tr><td><strong>Съобщение:</strong> </td><td>" . strip_tags($mail['content']) . "</td></tr>";
			if (isset($mail['doc_print'])) {
				$message .= "<tr><td colspan=\"2\">
				<h1>Оферта / Поръчка </h1>
				".$mail['doc_print']."
				</td></tr>";
			}
			$message .= "</table>";
			$message .= "</body></html>";

      if (mail($email_to, $email_subjet, $message, $headers)) {
        msg_add('Писмото е изпратено успешно!', MSG_SUCCESS);
      } else {
        msg_add('Грешка при изпращане на писмото!');
      }

			//printArray($mail);
	
	}
		
}

?>