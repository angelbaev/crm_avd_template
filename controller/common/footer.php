<?php
class ControllerFooter {
	public $model;
	public $act;
	public function __construct()  
    {  
        //$this->model = new Model();

    } 
	
	public function invoke()
	{
		get_template("common/footer.php");
		//include 'view/booklist.php';
	}
	
}

?>