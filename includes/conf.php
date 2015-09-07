<?php
//database connection
global $appConfig;

$host = "ainappdb.ciift4w8zsiu.eu-west-1.rds.amazonaws.com";
$user = "ainappuser";
$password = "ainapppw";
$db_name = "ainappdb";

$appConfig['db']['host'] = $host;
$appConfig['db']['port'] = "";
$appConfig['db']['user'] = $user;
$appConfig['db']['password'] = $password;
$appConfig['db']['name'] = $db_name;
