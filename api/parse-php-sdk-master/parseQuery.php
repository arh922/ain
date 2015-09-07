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


function pr($ar){
    echo('<pre>');
    print_R($ar);
    echo('</pre>');
}         

ParseClient::initialize( "mm6dXaFvCF0ADuhObiUnf9V1e9lbJpyutgJRhyOW", "fRZ9PkFKNfjcvbRKxzNAsB2bbLtezuRNmGwZIBQS", "JuGV1TojnASyPnjcChMUiNUSr3m2i2xMfyDChLnt" );
     
$data = array(
            'alert' => 'test' . " | " . mb_substr('test title', 0, 120, "UTF-8"),
            'sound' => 'push.caf',
            "badge" => "Increment",
            "title" =>  'test' . " | " . mb_substr('test title', 0, 120, "UTF-8"),
            "id" => 12
        );
        
$query = ParseInstallation::query();
$query->equalTo("channels", "a971");
$query->equalTo("keywords", "b1");
$query->limit(100000);

 pr($query);
pr(ParsePush::send(array(
  "where" => $query,
  "data" => $data
)));