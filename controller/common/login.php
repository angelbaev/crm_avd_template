<?php
//include_once("model/Model.php");
include_once(DIR_MODEL."common".SLASH."login.php");

class ControllerLogin extends Controller {

	public function __construct()  
    {  
    	parent::__construct();
    	$this->template = 'common/login.php';	
      $this->model = new ModelLogin();

    } 
	
	public function invoke()
	{
		$route = _g("route", "dashboard");
		$page_tab = _g("tab", "home");
		$this->data["route"] = $route;
		$this->data["page_tab"] = $page_tab;
		
		$this->act = _p("act", _g("act",""));
		
		if ($this->act == "in") {
			$this->logIn();
		} else if ($this->act == "out") {
			$this->logOut();
		} else {
		}
		
		$this->Out();	
	}
	
	private function logOut()
	{
	  $this->model->setLogOutUserTimeTraker($_SESSION["uid"]);
	  sleep(1);
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