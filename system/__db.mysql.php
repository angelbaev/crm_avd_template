<?php
/**
 * The Database Library handles database interaction for the application
 */
abstract class Database_Library
{
	abstract protected function connect();
	abstract protected function disconnect();
	abstract protected function prepare($query);
	abstract protected function query();
	abstract protected function fetch($type = 'object');	
}

/**
 * The MySQL Improved driver extends the Database_Library to provide 
 * interaction with a MySQL database
 */
class Mysql_Driver extends Database_Library
{
	/**
	 * Connection holds MySQLi resource
	 */
	private $connection;
	
	/**
	 * Query to perform
	 */
	private $query;
	
	/**
	 * Result holds data retrieved from server
	 */
	private $result;

	public $lastquery;
	
	public $Error;
	
	public $Record = array();
	
	/**
	 * Create new connection to database
	 */ 
	public function connect()
	{
		global $sys_error;

		//connection parameters
		$host = DB_HOSTNAME;
		$user = DB_USERNAME;
		$password = DB_PASSWORD;
		$database = DB_DATABASE;
		
		
		//create new mysql connection
		$this->connection =   mysqli_connect($host, $user, $password, $database);
		
		
		if (!$this->connection) {
			$this->msg_dberr("No connect to database!");
			return false;
		}
		/*
		$select_db = mysql_select_db($database, $this->connection);
		if(!$select_db){
			print("No select database!");
			return false;
		}
		*/
		$this->prepare("/*!41000 SET NAMES '".DB_CHARSET."' COLLATE '".DB_COLLATION."' */");
		if(!$this->query()) {
			print("41000 SET NAMES: ". $this->Error);
			return false;
		}

		$this->prepare(
			"/*!41000 SET character_set_database='".DB_CHARSET."',character_set_server='".DB_CHARSET."'"
			.",collation_database='".DB_COLLATION."',collation_server='".DB_COLLATION."' */"
			);
		if(!$this->query()) {
			print("41000 SET character_set_database: ". $this->Error);
			return false;
		}

		return TRUE;
	}
	
	/**
	 * Break connection to database
	 */
	public function disconnect()
	{
		return (mysqli_close($this->connection)?true:false);
	}
	
	/**
	 * Prepare query to execute
	 * 
	 * @param $query
	 */
	public function prepare($query)
	{
		//store query in query variable
		$this->query = $query;	
		$this->lastquery = $this->query;
		
		return TRUE;
	}
	
	/**
	 * Sanitize data to be used in a query
	 * 
	 * @param $data
	 */
	public function escape($data)
	{
		return mysqli_real_escape_string($this->connection, $data);
	}
	
	/**
	 * Execute a prepared query
	 */
	public function query()
	{
		if (isset($this->query))
		{
		
			//execute prepared query and store in result variable
			$this->result = mysqli_query($this->connection, $this->query);
			
			if (!$this->result) {
				$this->Error = _sqls(mysqli_error($this->connection));
			}
			return $this->result;
		} else {
			return false;
		}
//		return FALSE;		
	}

	/**
	 * Execute a mysql_insert_id
	 */	
	public function insert_id() {
		return mysqli_insert_id($this->connection);
	}
	/**
	 * Execute a mysql_num_rows
	 */	
	public function num_rows() {
		return mysqli_num_rows($this->result);
	}

	/**
	 * Execute a mysql_affected_rows
	 */	
	public function affected_rows() {
		return mysqli_affected_rows($this->connection);
	}
	
	/**
	 * Fetch a row from the query result
	 * 
	 * @param $type
	 */
	public function fetch($type = 'array')
	{
		 return $this->Record = mysqli_fetch_array($this->result, MYSQLI_ASSOC);
	/*
		if (isset($this->result))
		{
			switch ($type)
			{
				case 'array':
				
					//fetch a row as array
					$row = mysql_fetch_array($this->result, MYSQL_ASSOC);
//					$row = $this->result->fetch_array();
				
				break;
				
				case 'object':
				
				//fall through...
				
				default:
					
					//fetch a row as object
					$row = mysql_fetch_object($this->result);	
//					$row = $this->result->fetch_object();	
						
				break;
			}
			return $row;
		}
		
		return FALSE;
		*/
	}
	
	public function begin() {
     mysqli_query($this->connection, "BEGIN");
  }
  
  public function commit() {
     mysqli_query($this->connection, "COMMIT");
  }
  
  public function rollback() {
     mysqli_query($this->connection, "ROLLBACK");
  }
 
}

$db = new Mysql_Driver();
$db->connect();
?>