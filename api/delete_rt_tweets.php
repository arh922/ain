<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

header('Content-Type: text/html; charset=utf-8'); 

include('../includes/classes/MySQLiDatabaseConnection.php');
include('../includes/classes/tag.php');
include('../includes/classes/db_functions.php');
include('functions.php');
include('../includes/conf.php');

global $conn;

$conn = new MySQLiDatabaseConnection(); 

$query = "delete from tweets where LEFT(tweet_text , 2) = 'RT' or LEFT(tweet_text , 1) = '@'";
$res = $conn->db_query($query);