<?php
/*
include_once(DIR_MODEL."Model_".$GET_CLASS.".php");

class Controller {
	public $model;
	public $act;
	public function __construct()  
    {  
        $this->model = new 'Model_'.$GET_CLASS.();

    } 
	
	public function invoke()
	{
	}
	
}
*/
class ControllerHeader extends Controller {

	public function __construct()  
    {  
    	parent::__construct();
    //	$this->set_template('common/header.php');
    	$this->template = 'common/header.php';	
        //$this->model = new Model();

    } 
	
	public function invoke()
	{
		$route = _g("route", "dashboard");
		$page_tab = _g("tab", "home");

		$this->data["route"] = $route;
		$this->data["page_tab"] = $page_tab;
		//$this->msg_add("aaaaaaa baaaaaaaa", MSG_ERROR);
		$this->Out();	
	}
	
}

?>