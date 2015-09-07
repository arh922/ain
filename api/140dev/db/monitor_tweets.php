<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
/**
* monitor_tweets.php
* Check for new tweets in the tweets table and report by email if they aren't found
* Latest copy of this code: http://140dev.com/free-twitter-api-source-code-library/
* @author Adam Green <140dev@gmail.com>
* @license GNU Public License
* @version BETA 0.20
*/
require_once('140dev_config.php');
require_once('db_lib.php');
$oDB = new db;
      
if ($oDB->error) {
  // Report a DB connection error 
  error_email('Twitter Database Error', 'Unable to connect to Twitter database');
  exit;
}
    
// Check for new tweets arriving in the tweets table
// TWEET_ERROR_INTERVAL is defined in 140dev_config.php
/*$query = 'SELECT COUNT(*) AS cnt FROM tweets ' .
  'WHERE created_at > DATE_SUB(NOW( ), ' .
  'INTERVAL ' . TWEET_ERROR_INTERVAL . ' MINUTE)'; */
$del_query = "delete from tweets where LEFT(tweet_text , 2) = 'RT' or LEFT(tweet_text , 1) = '@'";  
$result_del = $oDB->select($del_query);

$query = 'SELECT COUNT(*) AS cnt FROM tweets ';  
$result = $oDB->select($query);
$row = mysqli_fetch_assoc($result);
     
// If there are no new tweets
if ($row['cnt'] < 11) {
  // Get the date and time of the last tweet added
  /*$query = 'SELECT created_at FROM tweets ' .
    'ORDER BY created_at DESC LIMIT 1';
  $result = $oDB->select($query);
  $row = mysqli_fetch_assoc($result);
  $time_since = (time() - strtotime($row['created_at']))/60;
  $time_since = (int) $time_since;
  $error_message = 'No tweets added for ' . $time_since . " minutes."; 
  $subject = 'Twitter Database Server Error';
  echo error_email($subject,$error_message);   */
  $process_id = file_get_contents('process_id_get_tweets.txt');
  //exec('kill -9 ' . $process_id);
  //exec('nohup php get_tweets.php > /dev/null &');

  $process_id = file_get_contents('process_id_parse_tweets.txt');
 // exec('kill -9 ' . $process_id);
 // exec('nohup php parse_tweets.php > /dev/null &'); 
  
  
    $host = '89.234.33.27';
    $user = 'kannel55';
    $password = 'O.brah.*#&';
    $db_name = 'gwapp_prod';
    $port = '3306';  

    $connection = mysqli_connect($host, $user, $password) or die(mysqli_error($connection));
    mysqli_set_charset($connection, "utf8");           
    mysqli_select_db($connection, $db_name) or die(mysqli_error($connection));


    $msg = rawurlencode('twitter streaming - down');       
                                                   
    $insert = "INSERT INTO send_sms_sqlbox (
                  momt, sender, receiver, msgdata, sms_type, smsc_id, charset, coding, dlr_mask, dlr_url
                ) VALUES (
                  'MT', 'Monitoring', '0599387989', '$msg' , '2', 'jawwal', 'UTF-8', 2, 31, 'system_down'
                )"; 
                
    $insert1 = "INSERT INTO send_sms_sqlbox (
                  momt, sender, receiver, msgdata, sms_type, smsc_id, charset, coding, dlr_mask, dlr_url
                ) VALUES (
                  'MT', 'Monitoring', '0599317684', '$msg' , '2', 'jawwal', 'UTF-8', 2, 31, 'system_down'
                )";        

    $status = mysqli_query($connection, $insert) or die(mysqli_error($connection)); 
    $status = mysqli_query($connection, $insert1) or die(mysqli_error($connection)); 
        
}
else{
    echo('tweets table has ' . $row['cnt'] . ' rows');
}
     
// Email the error message
function error_email($subject,$message) {
  $to = TWEET_ERROR_ADDRESS;
  $from = "From: Twitter Database Server Monitor <" . 
    TWEET_ERROR_ADDRESS . ">\r\n";
  return mail($to,$subject,$message,$from);
}
?>

<script>
    //setTimeout(function(){location.reload();}, 20000);
</script>