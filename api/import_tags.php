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

function check_if_tag_exists($tag) {
    global $conn; 
    
    $tag = trim(preg_replace('/\s\s+/', '', $tag));
    $tag = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', '', $tag); 
    
    $tag_words = array();
    
    if ( strrpos($tag, "_") !== FALSE ){
       // $tag_words = explode("_", $tag);
    }
    else{
      //  $tag_words = explode(" ", $tag);
    }
    
    $exp_tags = array('الرياض' => 847, 'عيدالام' => 3139);
    //$query = "select id from tags where levenshtein(name, '$tag') < 4 limit 1";
    //$query = "select id from tags where name = '$tag' limit 1";
    
    if (count($tag_words) > 1) {
         $query = "select parent from tags where synonyms = '$tag'"; 
         $res = $conn->db_query($query);
         $c = $conn->fetch_assoc($res);
    }
    else if (in_array($tag, $exp_tags)) {
        $c['parent'] = $exp_tags[$tag];
    }
    else{
        $query = "select parent from tags where synonyms like '%$tag%' || '$tag' like concat('%', synonyms, '%')";
        $res = $conn->db_query($query);
        $c = $conn->fetch_assoc($res);
    }
    
    return $c['parent'];
}

function insert_new_tag($parent_tag, $tag, $parent = '') {
    global $conn; 
    $query = "insert into tags (parent, name, synonyms) value ('$parent', '$parent_tag', '$tag')";
    //echo($query . '<br />');
    $res = $conn->db_query($query); 
    
    $tid = $conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning 
    
    return $tid;
}

function update_parent($id) {
    global $conn;
    
    $query = "update tags set parent = '$id' where id = '$id'";
    $res = $conn->db_query($query); 
}

$tags = file($_GET['file']);

$fp = fopen("new_keywords_3.txt", "a+"); 

foreach($tags as $tag) {
    $tags_arr = explode(",", $tag);
    
 //   pr($tags_arr);
    //    continue;
        
    $count = 0;
    
    foreach($tags_arr as $tag_name) { 
        $tag_name = trim($tag_name);          
        
     //   $tag_name = str_replace("#", "", $tag_name);
        $tag_name = str_replace(":", "", $tag_name);
        $tag_name = str_replace(",", "", $tag_name);
        $tag_name = str_replace(".", "", $tag_name);
        $tag_name = str_replace(";", "", $tag_name);
        $tag_name = str_replace(">", "", $tag_name);
        $tag_name = str_replace("<", "", $tag_name);
        $tag_name = str_replace("\"", "", $tag_name);
        $tag_name = str_replace("'", "", $tag_name);
       // $tag_name = str_replace("،", "", $tag_name);
        $tag_name = str_replace("~", "", $tag_name);
        $tag_name = str_replace("`", "", $tag_name);
        
        $tag_name = str_replace("'", "", $tag_name);
        $tag_name = str_replace('"', "", $tag_name);
        
        $tag_name = str_replace("(", "", $tag_name);
        $tag_name = str_replace(")", "", $tag_name);
        $tag_name = str_replace("»", "", $tag_name);
        $tag_name = str_replace("«", "", $tag_name);
        
        $tag_name = trim($tag_name);
           
        if ($tag_name != ""){
          //  echo('$tag_name: ' . $tag_name . '<br />');
            
            $found = check_if_tag_exists($tag_name);
            
            if (!$found) {    
                if ($count == 0) {  //parent
                    if ($tag_name != "") {
                        $new_tid = insert_new_tag($tag_name, $tag_name);
                        $_COOKIE['parent_id'] = $new_tid;
                        $_COOKIE['parent_name'] = $tag_name;
                        update_parent($new_tid);
                    }
                } 
                else{
                    if ($tag_name != "") {
                        insert_new_tag($_COOKIE['parent_name'], $tag_name, $_COOKIE['parent_id']);
                    }
                }
                
                $count++;
            }
            else{
                if ($count == 0) {  //parent
                    $_COOKIE['parent_id'] = $found;
                    $_COOKIE['parent_name'] = $tag_name;
                }
                else{
                    if ($tag_name != "") {
                        insert_new_tag($_COOKIE['parent_name'], $tag_name, $_COOKIE['parent_id']);
                    }
                }
                
                $count++;
            }   
            
            fwrite($fp, $tag_name);    
            
          //  echo("tag: " . $tag_name . '<br />');
          //  pr($found);
          //  echo('---------------------------------------<br />');
        }   
    }
}

fclose($fp);