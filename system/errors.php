<?php
/**
 * The error  handles interaction for the application
 */
abstract class Error_Library 
{
	abstract protected function msg_clear();
	abstract protected function msg_count($type = NULL);
	abstract protected function msg_add($messages, $type = MSG_ERROR, $title = "");
	abstract protected function msg_dberr($msg, $_db=NULL);
	abstract protected function msg_update();	
}

/**
 * The error  handles Improved driver extends the Error_Library to provide 
 * interaction with a error in application
 */
class Error_Handler extends Error_Library
{
	/**
	 * System Errors array
	 */
	public $SystemErrors = array();
	
	public function __construct() {
		$this->SystemErrors = array();
	}//__construct()
	
	/**
	 * Clear errors
	 */
	public function msg_clear() {
		$this->SystemErrors = array();
	}//msg_clear()

	/**
	 * Add a message to message list
	 * @param $messages
	 * @param $MType
	 * @param $title
	 */	
	public function msg_add($messages, $MType = MT_ERROR, $title = "") {
		// continue empty messages;
		if (!empty($messages)) {
			if (is_array($messages) && count($messages)) {
				$msg = outArray($messages);
			} else {
				$msg = $messages;
			}
			if (empty($title)){
				switch ($MType) {
					case MSG_WARNING:
						$title = "Warning";
						break;
					case MSG_SUCCESS:
						$title = "Success";
						break;
					case MSG_INFO:
						$title = "Info";
						break;
					case MSG_AUTOLOAD:
						$title = "Autoload";
						break;
					case MSG_LOGS:
						$title = "Logs";
						break;
					case MSG_ERROR:
						default:
						$title = "Error";
						break;
				}
			}
			$this->SystemErrors[] = array("title"=> $title, "message"=>$msg, "type"=>$MType);
		}
	}//msg_dberr()

	/**
	 * Get message count
	 * @param $MType
	 */
	public function msg_count($MType = NULL) {
	   if (is_null($MType)) return count($this->SystemErrors);
	   else {
	      $cnt = 0;
	      foreach($this->SystemErrors as $msg)
	         if ($msg["type"] == $MType) $cnt++;
			return $cnt;
	   }
	}//msg_count()
	
	
	/**
	 * Add db error message to message list
	 * 
	 * @param $message
	 * @param $db
	 */
	 
	public function msg_dberr($message, $_db = NULL) {
		//store query in query variable
		if (is_null($_db)) {
			global $db;
			$_db = $db;
		}

		$err = preg_replace("/\\s+/", " ", $_db->Error);
		if($_db->Errno == 1062) {
			$message .= "<br>There is already a record with this key: <b>".substr($err,strpos($err,"'")+1,strrpos($err,"'")-strpos($err,"'")-1)."</b><br>&nbsp;";
		}
		$message .= "<br><b>error_message</b>:<br><span class=label>".$err."</span>";
		$message .= "<br><b>last_query</b>:<br>".preg_replace("/\\s+/", " ", $_db->last_query)."";
		
		$this->msg_add($messages, MT_ERROR, "Database Error");
		
	}//msg_dberr()
	
	
	/**
	 * construct message html elements, include javascript routines and body.onload events
	 * 
	 */
	public function msg_update() {
		if(!count($this->SystemErrors)) return;
		$js = "
			var BAEVERR = new Object();
			var startError = 0;
			function BAEVERR_add(type, title, messeges) {
				BAEVERR[startError]= {'type':''+type+''}
			}	
		";
		$html = '
		
		';
		
		return $html;
	}//msg_update()
	
}

$sys_error = new Error_Handler();
?>