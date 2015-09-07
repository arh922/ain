<?php
// base path
global $base_path;

//save login after close browser
if (isset($_COOKIE['login'])) {          
    $is_login_before_close_browser = base64_decode($_COOKIE['login']);
    $is_login_before_close_browser = explode(SAVE_USERDATA_COOKIE_SEPARATER, $is_login_before_close_browser);
          //   pr($is_login_before_close_browser);                                          
    $user_obj = new User();
    $info = $user_obj->user_load($is_login_before_close_browser[0], 1);
          //   pr($info);  
    if (@$info['id'] != 0) {     
        $_SESSION['user'] = $info; 
    } 
} 
  
//date_default_timezone_set('Asia/Jerusalem');   

//set language to cookie
if ( isset($_POST['lang']) && !empty($_POST['lang'])) {      
    setcookie("lang", $_POST['lang'], time() + (60*60*24*365), $base_path);     
   // header("location: " .  $_SERVER['REQUEST_URI']);    
}
   
if (empty($_COOKIE['lang'])){      
    setcookie("lang", 'en', time() + (60*60*24*365), $base_path);  
  //  header("location: " .  $_SERVER['REQUEST_URI']); 
}

if (!isset($_COOKIE['lang'])){   
    setcookie("lang", 'en', time() + (60*60*24*365), $base_path);   
  //  header("location: " .  $_SERVER['REQUEST_URI']);
}  
   
//setcookie("lang", 'ar', time() + (60*60*24*365), $base_path);   
                 //   pr($_SESSION);
global $language, $user; 
if (isset($_SESSION['user'])){      
    $user = $_SESSION['user'];       
}
else{
    //$user = new stdClass();
    $user = array();
    $user['id'] = 0;
    $user['rid'] = 0;
}

//$language = $_COOKIE['lang'];
  
if ($user['rid'] == 8){
    $language = 'en';
}
else{
    $language = 'en';
}

//page direction
if ($language == 'ar'){
    $language_direction = 'rtl';
}
else{
    $language_direction = 'ltr';
}

//SEO 
global $desc, $keywords, $head_title, $message;

if (!isset($helper_obj)) {
    $helper_obj= new Helper();
}

if (!isset($db_object)) { 
    $db_object = new DbFunctions();  
}

if (!isset($conn)) { 
    $conn = new MySQLiDatabaseConnection(); 
}

if ($dir = trim(dirname($_SERVER['SCRIPT_NAME']), '\,/')) {
    $base_path = "/$dir";
    $base_path .= '/';
}
else {
    $base_path = '/';
}

//moderator always in english
if ($user['rid'] == 8){          
    setcookie("lang", 'en', time() + (60*60*24*365), $base_path);
} 

//this for any click without login
$not_login = "";

//print array in readable view
function pr($arr){
   echo('<pre>');
   print_r($arr);
   echo('</pre>');
}