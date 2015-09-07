<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include('autoload.php');

use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseACL;
use Parse\ParsePush;
use Parse\ParseUser;
use Parse\ParseInstallation;
use Parse\ParseException;
use Parse\ParseAnalytics;
use Parse\ParseFile;
use Parse\ParseCloud;
use Parse\ParseClient;
         
ParseClient::initialize( "mm6dXaFvCF0ADuhObiUnf9V1e9lbJpyutgJRhyOW", "fRZ9PkFKNfjcvbRKxzNAsB2bbLtezuRNmGwZIBQS", "JuGV1TojnASyPnjcChMUiNUSr3m2i2xMfyDChLnt" );

$source_name = $_GET['source_name'];
$cid = $_GET['cid'];
$channel = $_GET['channel'];
$title = $_GET['title'];
$aid = $_GET['aid'];

$data = array(
            'alert' => $source_name . " | " . mb_substr($title, 0, 120, "UTF-8"),
            'sound' => 'push.caf',
            "badge" => "Increment",
            "title" =>  $source_name . " | " . mb_substr($title, 0, 120, "UTF-8"),
            "id" => $aid
        );

// Push to Channels
echo('Breaking News(PN): <br /> <pre>');print_R( ParsePush::send(array(
    "channels" => [$channel],
    "data" => $data
)));

?>