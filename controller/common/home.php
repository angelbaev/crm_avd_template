<?php
include_once(DIR_MODEL."activities".SLASH."activities.php");

class ControllerHome {
	public $model;
	public $act;
	private $data;
	public function __construct()  
    {  
        $this->model = new ModelActivities();

    } 
	
	public function invoke()
	{
		$this->data['login_user'] = $_SESSION["user_name"];
		$this->data['arr'] = array('ab' => 'test', 'vv' => 'ss vvvs');
		$this->uid = get_uid();

		$filter["page_limit"] = " LIMIT 21";
		$filter["filter_card_status"] = 0;
		$this->activities = $this->model->getActivities($filter, $this->uid);
		
		$this->model->getNewTotal(array('filter_card_status' => CARD_STATUS_W));
		$forgotenActivities = $this->model->getNewActivities(array('filter_card_status' => CARD_STATUS_W, 'filter_is_forgotten_activity' => 1));
                /*
		foreach($forgotenActivities as $key => $forgotenActivity) {
		  if (!count($forgotenActivity["ITEM_A"])) {
        unset($forgotenActivities[$key]["ITEM_A"]);
      }
		  if (!count($forgotenActivity["ITEM_C"])) {
        unset($forgotenActivities[$key]["ITEM_C"]);
      }
      if (count($forgotenActivity["ITEM_A"])) {
        foreach($forgotenActivity["ITEM_A"] as $k => $v) {
          $isForget = (strtotime($v["activity_date"]) < strtotime("-1 day"));
          if(!$isForget) {
            unset($forgotenActivities[$key]["ITEM_A"][$k]);
          }
        }
        if (!count($forgotenActivities[$key]["ITEM_A"])) {
          unset($forgotenActivities[$key]["ITEM_A"]);
        }
      }
      if (count($forgotenActivity["ITEM_C"])) {
        foreach($forgotenActivity["ITEM_C"] as $k => $v) {
          $isForget = (strtotime($v["activity_date"]) < strtotime("-1 day"));
          if(!$isForget) {
            unset($forgotenActivities[$key]["ITEM_C"][$k]);
          }
        }
        if (!count($forgotenActivities[$key]["ITEM_C"])) {
          unset($forgotenActivities[$key]["ITEM_C"]);
        }
      }
      
      if (!count($forgotenActivities[$key])) {
        unset($forgotenActivities[$key]);
      }   
      
    }*/
/*		
		foreach ($this->activities as $key => $val) {
			if (count($val['ITEM_A'])) {
				foreach($val['ITEM_A'] as $k1 => $v1) {
					if ($v1['card_status'] != 0 ) {
						unset($this->activities[$key]['ITEM_A'][$k1]);
					}
				}
			}
			if (count($val['ITEM_C'])) {
				foreach($val['ITEM_C'] as $k1 => $v1) {
					if ($v1['CStatus'] != 0 ) {
						unset($this->activities[$key]['ITEM_C'][$k1]);
					}
				}
			}
		}
*/
		$this->data['activities'] = $this->activities;
		$this->data['forgotenActivities'] = $forgotenActivities;
		$this->data["action_edit"] = HTTP_SERVER."index.php?route=activities&tab=edit&token=".get_token()."&act=edit&doc_id=";

		
		$this->Out();
	}
	
	public function Out() {
		extract($this->data); 
		include_once DEFAULT_TEMPLATE.'common/home.php';
	}	
}


?>