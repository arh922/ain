<?php                       
/**
 * This class will handle all database operations: connection, querying, and other issues. 
 */
class MySQLiDatabaseConnection {

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
    /*if (is_resource($this->connection)) {
       mysqli_close($this->connection);
    }*/
    $this->connection->close(); 
  }


    public function connect() {
        global $appConfig;

        $host = $appConfig['db']['host'];
        $user = $appConfig['db']['user'];
        $password = $appConfig['db']['password'];
        $db_name = $appConfig['db']['name'];
 
        $this->connection = mysqli_connect($host, $user, $password) or die(mysqli_error());
        mysqli_set_charset($this->connection, "utf8mb4");  
                    
        mysqli_select_db($this->connection, $db_name) or die(mysqli_error());
        
       /* $this->connection = new mysqli(
                                    null, // host
                                    $user, // username
                                    $password,     // password
                                    $db_name, // database name
                                    null,
                                    '/cloudsql/cosmic-descent-775:newsappamc'
                                    );  */
          

        if($this->connection->connect_errno > 0){
            die('Unable to connect to database [' . $this->connection->connect_error . ']');
        }
    }


  public function db_query($sql) {
    $result = mysqli_query($this->connection, $sql);
    //$result =  $this->connection->query($sql);
    
    if($result === false) {
        trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $this->connection->error, E_USER_ERROR);
    }
    else{
        return $result;
    }
  }


/**
 * Fetch one result row from the previous query as an array.
 *
 * @param $result
 *   A database query result resource, as returned from db_query().
 * @return
 *   An associative array representing the next row of the result, or FALSE.
 *   The keys of this object are the names of the table fields selected by the
 *   query, and the values are the field values for this result row.
 */
function db_fetch_array($result) {
  if ($result) {
    return mysqli_fetch_array($result, MYSQL_ASSOC);
   // $row = $result->fetch_assoc();
  //  $result->free();
    
   // return $row;
  }
}

function fetch_assoc($result) {
  if ($result) {
    return mysqli_fetch_array($result, MYSQL_ASSOC);
   // $row = $result->fetch_assoc();
  //  $result->free();
    
   // return $row;
  }
}


  
  /**
  * Fetch one result row from the previous query as an object.
  *
  * @param $result
  *   A database query result resource, as returned from db_query().
  * @return
  *   An object representing the next row of the result, or FALSE. The attributes
  *   of this object are the table fields selected by the query.
  */
  function db_fetch_object($result) {
    if ($result) {
      return mysqli_fetch_object($result);
    //  return $result->fetch_assoc();
    }
  }

                

  public function db_affected_rows() {
    return mysqli_affected_rows($this->connection);
       
  }
  
   public function db_num_rows($res) {
      return mysqli_num_rows($res);    
  }
     
  
  public function db_escape_string($text) {
    return mysqli_real_escape_string($this->connection, (string) $text);
  }


  public function db_last_insert_id() { 
    $row = mysqli_fetch_array($this->db_query('SELECT LAST_INSERT_ID()'));
    return $row['LAST_INSERT_ID()'];
  }

}