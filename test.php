<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

/*$host = "ainappdb.ciift4w8zsiu.eu-west-1.rds.amazonaws.com";
$user = "ainappuser";
$password = "ainapppw";
$db_name = "ainappdb";

$connection = mysqli_connect($host, $user, $password) or die(mysqli_error());
mysqli_set_charset($connection, "utf8");  
mysqli_select_db($connection, $db_name) or die(mysqli_error());

$query = "select * from users";

$result = mysqli_query($connection, $query);

while($row = mysqli_fetch_array($result)){
    echo $row['id'] . ' - ' . $row['name'] . ' - ' . $row['pw'] . '<br />';
}      */

phpinfo();