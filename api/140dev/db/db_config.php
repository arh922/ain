<?php       
error_reporting(E_ALL);
ini_set('display_errors', '1');
/**         
* db_config.php
* MySQL connection parameters for 140dev Twitter database server
* Fill in these values for your database
* Latest copy of this code: http://140dev.com/free-twitter-api-source-code-library/
* @author Adam Green <140dev@gmail.com>
* @license GNU Public License
* @version BETA 0.30
*/
include("../../../includes/conf.php"); 

  $db_host = $host;
  $db_user = $user;
  $db_password = $password;

  // MySQL time zone setting to normalize dates
  define('TIME_ZONE','America/New_York');
?> 