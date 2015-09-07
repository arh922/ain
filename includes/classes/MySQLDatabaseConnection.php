<?php
/**
 * This class will handle all database operations: connection, querying, and other issues. 
 */
class MySQLDatabaseConnection {

  //stores a refernce to databse
  private $connection;
  
  /**
   * Constructor, connects to database, only once
   * @global type $appConfig 
   */
  public function __construct() {
      $this->connect();
  }


  public function __destruct() {
    if (is_resource($this->connection)) {
      mysql_close($this->connection);
    }
  }


  public function connect() {
    global $appConfig;

    $host = $appConfig['db']['host'];
    $user = $appConfig['db']['user'];
    $password = $appConfig['db']['password'];
    $db_name = $appConfig['db']['name'];

    echo "connecting", $db_name;
    
    $this->connection = mysql_connect($host, $user, $password) or die(mysql_error());
        mysql_select_db($db_name) or die(mysql_error());
        
        
    echo "connected";
    }


  public function db_query($sql) {
    $result = mysql_query($sql);

    return $result;
  }


  public function db_fetch_object($resource) {
    $object = mysql_fetch_object($resource);

    return $object;
  }


  public function db_fetch_array($resource) {
    $array = mysql_fetch_array($resource);

    return $array;
  }
                

  public function db_affected_rows() {

    return mysql_affected_rows();
  }


  public function db_escape_string($text) {
    return mysql_real_escape_string((string) $text);
  }


  public function db_last_insert_id() {
    $row = mysql_fetch_array($this->db_query('SELECT LAST_INSERT_ID()'));
    return $row['LAST_INSERT_ID()'];
  }

}