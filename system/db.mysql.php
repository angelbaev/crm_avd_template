<?php

abstract class Database_Library
{
    abstract protected function connect();
    abstract protected function disconnect();
    abstract protected function prepare($query);
    abstract protected function query();
    abstract protected function fetch($type = 'object');
}

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

        // Connection parameters
        $host = DB_HOSTNAME;
        $user = DB_USERNAME;
        $password = DB_PASSWORD;
        $database = DB_DATABASE;

        // Create new MySQLi connection
        $this->connection = mysqli_connect($host, $user, $password, $database);

        // Check connection
        if (!$this->connection) {
            $this->msg_dberr("No connect to database! Error: " . mysqli_connect_error());
            return false;
        }

        // Set character set
        if (!mysqli_set_charset($this->connection, DB_CHARSET)) {
            $this->msg_dberr("Error loading character set " . DB_CHARSET . ": " . mysqli_error($this->connection));
            return false;
        }

        return true;
    }

    /**
     * Break connection to database
     */
    public function disconnect()
    {
        return mysqli_close($this->connection);
    }

    /**
     * Prepare query to execute
     * 
     * @param $query
     */
    public function prepare($query)
    {
        $this->query = $query;
        $this->lastquery = $this->query;
        return true;
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
        if (isset($this->query)) {
            $this->result = mysqli_query($this->connection, $this->query);

            if (!$this->result) {
                $this->Error = mysqli_error($this->connection);
            }

            return $this->result;
        } else {
            return false;
        }
    }

    /**
     * Execute a mysqli_insert_id
     */
    public function insert_id()
    {
        return mysqli_insert_id($this->connection);
    }

    /**
     * Execute a mysqli_num_rows
     */
    public function num_rows()
    {
        return mysqli_num_rows($this->result);
    }

    /**
     * Execute a mysqli_affected_rows
     */
    public function affected_rows()
    {
        return mysqli_affected_rows($this->connection);
    }

    /**
     * Fetch a row from the query result
     * 
     * @param $type
     */
    public function fetch($type = 'array')
    {
        if (isset($this->result)) {
            switch ($type) {
                case 'array':
                    return $this->Record = mysqli_fetch_assoc($this->result);
                case 'object':
                default:
                    return $this->Record = mysqli_fetch_object($this->result);
            }
        }
        return false;
    }

    public function begin()
    {
        mysqli_query($this->connection, "BEGIN");
    }

    public function commit()
    {
        mysqli_query($this->connection, "COMMIT");
    }

    public function rollback()
    {
        mysqli_query($this->connection, "ROLLBACK");
    }
}

// Usage
$db = new Mysql_Driver();
$db->connect();

?>