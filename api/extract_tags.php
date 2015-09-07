<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include("../includes/conf.php");
include("../includes/classes/MySQLiDatabaseConnection.php");

global $conn; 
$conn = new MySQLiDatabaseConnection();

function pr($arr){
    echo('<pre>');
    print_r($arr);
    echo('</pre>');
}

function check_if_tag_exists($tag) {
    global $conn; 
    
    $tag = trim(preg_replace('/\s\s+/', '', $tag));
    $tag = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', '', $tag); 

    $exp_tags = array('الرياض' => 847, 'عيدالام' => 3139);

    $query = "select parent from tags where synonyms = '$tag' and image is null";
    echo('<br />tag query: ' . $query . '<br />');
    $res = $conn->db_query($query);
    $c = $conn->fetch_assoc($res);
    
    return $c['parent'];
}

function process_tags($title) {   
    global $conn; 
    
    $title_array = explode(" ", strtolower($title));
           
    $tag_count = 0;
             pr($title_array);
    foreach($title_array as $tag) {
        $tag = trim($tag);
        $tag = strtolower($tag);
        
        $tag_count++;
        
        if ($tag_count == 15) break;

        $tag = str_replace("#", "", $tag);
        $tag = str_replace(":", "", $tag);
        $tag = str_replace(",", "", $tag);
        $tag = str_replace(".", "", $tag);
        $tag = str_replace(";", "", $tag);
        $tag = str_replace(">", "", $tag);
        $tag = str_replace("<", "", $tag);
        $tag = str_replace('"', "", $tag);
        $tag = str_replace("'", "", $tag);
        $tag = str_replace("،", "", $tag);
        $tag = str_replace("~", "", $tag);
        $tag = str_replace("`", "", $tag);
        $tag = str_replace("(", "", $tag);
        $tag = str_replace(")", "", $tag);
        $tag = str_replace("»", "", $tag);
        $tag = str_replace("«", "", $tag);
                                                                             
        $tag = preg_replace("/[a-zA-Z0-9]+/", '',$tag);
                               
        $tag_encoding = mb_detect_encoding($tag);
              // echo($tag_encoding . '<br />');
        if ($tag_encoding != "" && $tag_encoding == 'UTF-8' && !is_numeric($tag) && (mb_strlen($tag, "UTF-8") > 2)) {   //just arabic and not number and tag length > 2 and it's noun
            echo check_if_tag_exists($tag);
        }
    }
}


process_tags('وزير الشباب العراقي لـ #المستقبل: مفتاح حل الهجرة يبدأ بخلق فرص العمل
#العراق
#الهجرة
#أوروبا
');