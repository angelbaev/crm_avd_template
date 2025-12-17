<?php
class Controller {
	public $act;
	public $data;
	private $model;
	protected $msg;
	protected $template;

	public function __construct()  
    {  
        $this->template = 'common/home.php';
				$this->data = array ();
				$this->errors = array ();
    } 
	
	public function invoke()
	{
		$this->Out();
	}


	protected function hasPermissions()
	{
		
	}

	protected function msg_add($msg, $type = MSG_ERROR)
	{
		$this->msg[] = array("type" => $type, "msg" => $msg);
	}
		
	public function Out($tpl = "")
	{
		if (count((array)$this->msg)) {
			$this->data["messages"] = $this->msg;
		}
		if (count((array)$this->data)){
			extract($this->data); 
		}
		include_once DEFAULT_TEMPLATE.$this->template;
	}
}

?>