<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


include('../includes/classes/MySQLiDatabaseConnection.php');
include('../includes/conf.php');

global $conn;

$conn = new MySQLiDatabaseConnection();

$query = "delete from tweets where not exists 
(select 1 from rss_news where type = 1 and rss_news.twitter_user_id = tweets.user_id)";

$res = $conn->db_query($query);  

echo($res);

$time = date("Y-m-d h:i:s");

$fp = fopen('delete_tweets/delete_unneeded_tweets.txt', 'a+');
fwrite($fp,  $time . "\n");
fclose($fp);