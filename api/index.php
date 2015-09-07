<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//header('Content-Type: text/html; charset=utf-8');

error_reporting(E_ALL);
ini_set('display_errors', '1');   

include("../includes/conf.php");
include("../includes/classes/MySQLiDatabaseConnection.php");

include("../includes/constant.php");

include("../includes/classes/helper.php");

include("../includes/translation.php");     
   
//classes
include("../includes/classes/db_functions.php"); 
include("../includes/classes/user.php"); 
include("../includes/validation_rules.php");
include("../includes/classes/validation.php");  
include("../includes/classes/validation_js.php");  
    
include("../includes/classes/controller.php");  
include("../includes/classes/encryption.php"); 
include("../includes/classes/video.php"); 
include("../includes/classes/article.php"); 
include("../includes/classes/menu.php"); 
include("../includes/classes/client.php"); 
include("../includes/classes/category.php"); 
include("../includes/classes/pgrate.php");   
include("../includes/classes/operator.php"); 
include("../includes/classes/payment.php"); 

include("../includes/classes/PHPMailerAutoload.php"); 

global $conn; 

include("functions.php");

$conn = new MySQLiDatabaseConnection();

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";  

switch($action) {
    case "get_article":  
        echo get_html_article($_REQUEST['aid'], $_REQUEST['cid'], $_REQUEST['uid']);
    break;

    case "all_articles":  
        echo get_all_html_articles($_REQUEST['cid'], ($_REQUEST['page']-1), $_REQUEST['limit']);
    break;

    case "articles_by_section":  
        echo get_html_articles_by_section($_REQUEST['section'], $_REQUEST['cid'],($_REQUEST['page']-1), $_REQUEST['limit']);
    break;
    
    case "get_categories":      //xxxxxxxxxxxxx not use
        echo get_categories($_REQUEST['client_id']);
    break;
    
    case "get_parent_categories":  
        echo get_parent_categories($_REQUEST['client_id']);
    break;
    
    case "get_categories_by_parent":  
        echo get_categories_by_parent($_REQUEST['client_id'], $_REQUEST['parent'], $_REQUEST['udid']);
    break;
    
    case "get_source_followers":  
        echo get_source_followers($_REQUEST['client_id'], $_REQUEST['parent']);
    break;
    
    case "get_source_followers_by_source_id":  
        echo get_source_followers_by_source_id($_REQUEST['client_id'], $_REQUEST['source_ids']);
    break;
    
    case "get_articles_by_cat":  //news by my selected sources
        echo get_html_articles_by_cat($_REQUEST['cid'], $_REQUEST['client_id'], ($_REQUEST['page']-1), $_REQUEST['limit']);
    break;
    
    case "update_device_id":  //for andriod 
        echo update_device_id($_REQUEST['uid'], $_REQUEST['device_id']);
    break;
    
   /* case "article_details":  
        echo get_articles_details($_REQUEST['aid']);
    break; */
    
    case "register_user":  
        echo register_user();
    break;
    
     case "generate_ver_code":
       $phone = trim($_REQUEST['phone']);
       $country_id = trim($_REQUEST['country_id']);
       $operator_id = trim($_REQUEST['operator_id']);
       $email = trim($_REQUEST['email']);
       $cid = trim(@$_REQUEST['client_id']);
       
       $callback = trim(@$_REQUEST['callback']);
       
       if (!empty($phone) && !empty($country_id) && !empty($operator_id) && !empty($email)) { 
           echo generate_ver_code($phone, $country_id, $operator_id, $email, $cid, $callback);
       }
       else{
           $json = $callback . '({
                        "proposals": 
                        ';
           $json .= '{"success":"0","message":"failed"}';
                        
           $json .= '})';    
             
           echo($json); 
       }
    break;
    
    case "dlr":         
        $par = $_GET['par'];     
        $par = explode("_",$par);
        $del = $par[0];
        $type = $par[1];
        $uid = $par[2];
        $op_id = $par[3];
        $phone = $par[4];
        
        if (!isset($par[5])) {
            $callback = "callback";
        }
        else{
            $callback = $par[5];
        }
                                          
        $db_fuctions_obj = new DbFunctions();
        
        $ope_details = $db_fuctions_obj->get_operator_details($op_id);
        
        //pr($par);
      //  pr($ope_details);
        $period = $ope_details[0]['period'] * 24 * 60 * 60;
        
        //check if ver code is correct
        if ($par[1] == 1) {    
           echo dlr($uid, $del, $op_id); 
        }  
        
        //repayment
        else if ($par[1] == 2) {
           //$ope_details = get_operator_details($op_id);
           
           $log_obj = new Payment();

           $log_obj->update_log_del_status($uid, $del);
    
           $success_array = array(1);//sucuss
               
           if ($op_id == 1) { //jawwal
                $success_array = array(1);//sucuss
           }
           
           
           $fp = fopen("dlrs_repayment.txt", "a+");
            fwrite($fp, $del . "\n");
            fclose($fp);
    
           if (in_array($del, $success_array)) {
               $user_obj = new User();
               
               $user_details = $user_obj->user_load($uid);
               
               $cid = $user_details->cid;
               
               $phone_details = $db_fuctions_obj->get_phone_details($phone, $cid); 
                 //   pr($phone_details);
               $country_id = $ope_details[0]['country_id'];
              
               $end_date = $phone_details[0]['end_date']; 
               
               //$uid = $phone_details[0]['uid']; 
               
               $email = $phone_details[0]['email']; 
               
               $now = time(); 
                    
               //with period and he want to add new payment
               if ($now < $end_date) {  
                   $start_date = $phone_details[0]['date_added'];

                   $end_date += $period; 
                           
                   $db_fuctions_obj->update_payment($uid, $start_date, $end_date);
                   
                   $db_fuctions_obj->reset_payment_count($uid);
               }

               //his payment is expired 
               else if ($now >= $end_date) {   
                  $end_date = $now + $period;
                         
                  $db_fuctions_obj->update_payment($uid, $now, $end_date);
                  
                  $db_fuctions_obj->reset_payment_count($uid);
               }
               
               $db_fuctions_obj->activate_user($uid);
               
               $json = $callback . '({
                        "proposals": 
                        ';
               $json .= '{"success":"1","message":"success"}';
                            
               $json .= '})';    
                 
               echo($json);
               //call bashar service              
               //exec_curl("http://cp.m-diet.com/payments.ashx/insertpaymenttbl?PersonEmail=$email&OperatorID=$op_id&Status=1"); 
           }
       }
       
    break;
    
    case "dlr_mo":
        $db_fuctions_obj = new DbFunctions();  
         
        $phone = urlencode($_GET['from']);
        $shortcode = $_GET['to'];
        $sms_body = $_GET['message'];  //email
        $smsc = $_GET['smsc'];
                  
        //send to bashar phone and email:success
        echo('{"status":"1","email": "' . $sms_body . '", "phone":"' . $phone . '"}'); 
        
        
        $phone_details = $db_fuctions_obj->get_phone_details($phone);
        
        $email = $phone_details[0]['email'];
        $op_id = $phone_details[0]['operator_id'];
        $uid = $phone_details[0]['uid'];
        
        echo dlr($uid, 1, $op_id);     

    break;
    
    case "verify_code":  
       $number = $_REQUEST['phone'];
       $code = $_REQUEST['code'];
       $cid = $_REQUEST['client_id'];
       $callback = @$_REQUEST['callback']; 
       
       echo verify_code($number, $code, $callback, $cid);
    break;
    
    case "cron_payment":     //every 8 hours for 3 days 
        renew_payment(@$_REQUEST['cid']);  
    break;
    
    case "repayment":
        $phone = trim($_REQUEST['phone']); 
        $email = trim($_REQUEST['email']);   
        $country_id = trim($_REQUEST['country_id']);  
        $operator_id = trim($_REQUEST['operator_id']); 
        
        $callback = @$_REQUEST['callback']; 
        $cid = @$_REQUEST['client_id']; 
        
        echo repayment($phone, $country_id, $operator_id, $email, $callback, $cid);
    break;
    
    case "deactivate_expired_user":  //every 5 mins
        deactivate_expired_user($_REQUEST['cid']);  
    break;
    
    case "forget_password":  
        $valid = forget_password($_REQUEST['email']);
        
        if ($valid) {
            echo ('Ext.data.JsonP.callback1({
                "proposals": 
                ' . '{"success":"1","message":"success"}' . '})'); 
        }
        else{
            echo ('Ext.data.JsonP.callback1({
                "proposals": 
                ' . '{"success":"0","message":"failed"}' . '})');   
        }  
    break;  
    
    case "change_password":  
        $valid = change_password($_REQUEST['email'], $_REQUEST['old_password'], $_REQUEST['new_password']);
        
        if ($valid) {
            echo ('Ext.data.JsonP.callback1({
                "proposals": 
                ' . '{"success":"1","message":"success"}' . '})'); 
        }
        else{
            echo ('Ext.data.JsonP.callback1({
                "proposals": 
                ' . '{"success":"0","message":"failed"}' . '})');   
        }  
    break;
    
    case "login":  
        $user_obj = new User();  
                              
        $form_data['name'] = $_REQUEST['name'];
        $form_data['pass'] = $_REQUEST['pass'];
        
        $callback = trim($_REQUEST['callback']);
        
        $is_valid_user = $user_obj->login($form_data);  
        
        if ($_REQUEST['sencha']) {      
            if ($is_valid_user) {
                echo ('Ext.data.JsonP.callback1({
                    "proposals": 
                    ' . '{"success":"1", "uid":"' . $is_valid_user . '","message":"success"}' . '})'); 
            }
            else{
                echo ('Ext.data.JsonP.callback1({
                    "proposals": 
                    ' . '{"success":"0","message":"failed"}' . '})');   
            }
        }
        else{
            if ($is_valid_user) {
                echo ('{"success":"1", "uid":"' . $is_valid_user . '","message":"success"}'); 
            }
            else{        
                $json = $callback . '({
                        "proposals": 
                        ';
               $json .= '{"success":"0","message":"failed"}';
                            
               $json .= '})';    
                 
               echo($json);  
            }
        }
    break;
    
    case "check_user_paid":
      $log_obj = new Payment(); 
      $payment = $log_obj->get_user_payment($_REQUEST['uid']); 
      
      $payment_end_date = @$payment->end_date; 
      
      $now = time();
    
      $response = ('{"success":"1","message":"success"}');
                    
      if ($payment_end_date < $now){
          $response = ('{"success":"0","message":"failed"}');
      }
      
      $callback = trim($_REQUEST['callback']);
      
      $json = $callback . '({
                        "proposals": 
                ';
      $json .= $response;
                    
      $json .= '})';    
         
      echo($json); 
  
    break;
    
    case "check_user_status":
      echo check_user_status($_REQUEST['phone'], $_REQUEST['client_id'], $_REQUEST['callback']); 
    break;
    
    
    //**********************COMMENTS******************************************************************************
    case "add_comment":
         $valid = add_comment($_REQUEST['aid'], $_REQUEST['username'], $_REQUEST['comment'], @$_REQUEST['client_id']);
         $response = ('{"success":"1","message":"success"}');;
                    
         if (!$valid){
             $response = $callback . '({"proposals":{"success":"0","message":"failed"}})'; 
         }
         else{
             $response = get_comments_article($_REQUEST['aid'], @$_REQUEST['callback']);
         }
               
         echo($response); 
      
    break;
    
    case "get_comments_article":
         $response = get_comments_article($_REQUEST['aid']);
         echo $response; 
    break;
    
    case "delete_comment";
         $valid = delete_comment($_REQUEST['comment_id'], $_REQUEST['uid']);
         $response = ('{"success":"1","message":"success"}');;
                    
         if (!$valid){
             $response = ('{"success":"0","message":"failed"}');
         }
         
         //$callback = trim(@$_REQUEST['callback']);
         
       /*  $json = $callback . '({
                        "proposals": 
                    ';   */
         $json .= $response;
                        
        /* $json .= '})';       */
             
         echo($json); 
    break;
    
    case "edit_comment":
          $valid = edit_comment($_REQUEST['id'], $_REQUEST['uid'], $_REQUEST['comment']);
          $response = ('{"success":"1","message":"success"}');
                    
          if (!$valid){
             $response = ('{"success":"0","message":"failed"}');
          }
          
         // $callback = trim(@$_REQUEST['callback']);
          
         /* $json = $callback . '({
                        "proposals": 
                    ';           */
          $json .= $response;
                        
         /* $json .= '})';      */
          
          echo $json;
             
    break;
    
    case "comment_abuse":
         comment_abuse($_REQUEST['comment_id']);
         
         $response = ('{"success":"1","message":"success"}');
        
         //$callback = trim(@$_REQUEST['callback']);
          
       /*  $json = $callback . '({
                        "proposals": 
                    ';    */
         $json .= $response;
                        
     /*    $json .= '})';      */
          
         echo $json;
        
    break;
    
    
    //**********************RATING******************************************************************************
    case "rate_article":   //http://www.jeelplus.com/appstreamig/streaming/api/index.php?action=rate_article&uid=55&aid=44&rate=5
          rate_article($_REQUEST['aid'], $_REQUEST['rate'], $_REQUEST['uid']);
          $response = ('{"success":"1","message":"success"}');
          
          //$callback = trim(@$_REQUEST['callback']);
          
         /* $json = $callback . '({
                        "proposals": 
                    ';         */
          $json .= $response;
                        
          /*$json .= '})';    */
          
          echo $json;
    break;
    
    case "get_user_rate": //http://www.jeelplus.com/appstreamig/streaming/api/index.php?action=get_user_rate&aid=44&uid=234
          $rate = get_user_rate($_REQUEST['aid'], $_REQUEST['uid']);
          $response =  ('{"success":"1","message":"success", "rate":"' . $rate . '"}'); 
          
          //$callback = trim(@$_REQUEST['callback']);
          
         /* $json = $callback . '({
                        "proposals": 
                    ';     */
          $json .= $response;
                        
          /*$json .= '})';     */
          
          echo $json;
          
    break; 
    
    case "get_avg_rate":       //http://www.jeelplus.com/appstreamig/streaming/api/index.php?action=get_avg_rate&aid=44
          $rate = get_avg_rate($_REQUEST['aid']);
          $response = ('{"success":"1","message":"success", "rate":"' . $rate . '"}'); 
          
          //$callback = trim(@$_REQUEST['callback']);
          
          /*$json = $callback . '({
                        "proposals": 
                    ';  */
          $json .= $response;
                        
          /*$json .= '})'; */
          
          echo $json;
    break;
    
    case "tay":
         $fp = fopen("fffff.txt", "a+");
         fwrite($fp, $_REQUEST['token']);
         fclose($fp);
    break;
    
    case "save_app_users":
        save_token_pushwizard($_REQUEST['token'], $_REQUEST['client_id'], @$_REQUEST['type']);
        $response = ('{"success":"1","message":"success"}');
        
        //$callback = trim(@$_REQUEST['callback']);
          
        /*$json = $callback . '({
                        "proposals": 
                    ';      */
        $json .= $response;   
                        
        /*$json .= '})';     */
          
        echo $json;
    break;
             
    case "check_free_period":
       echo check_free_period($_REQUEST['udid'], $_REQUEST['cid'], $_REQUEST['callback']);
    break;
    
    //**************************************TAGS**********************************************************************************
    
    case "get_all_tags":
          echo get_all_tags($_REQUEST['cid'], ($_REQUEST['start']-1), $_REQUEST['offset']);
    break;
    
    case "get_articles_by_tag" :
          echo get_articles_by_tag($_REQUEST['tid'], $_REQUEST['cid'], ($_REQUEST['page']-1), $_REQUEST['limit']);
    break;
    
    case "get_articles_by_tag_from_my_sources" :
          echo get_articles_by_tag_from_my_sources($_REQUEST['tid'], $_REQUEST['cid'], ($_REQUEST['page']-1), $_REQUEST['limit'], $_REQUEST['sources']);
    break;
    
    case "loop":
          while(true){
              echo(1);
          }
    break;
    
    
    //***************************sport*************************************************************
    
    case "save_football_PADS":
         $comp_array = array(620,625,650,635);
         $type = $_REQUEST['type'];   //fixturesEnhanced, LeagueTableEnhanced
          
         save_football_PADS($comp_array, $type);
    break;
    
    case "comp":
        $mid = $_REQUEST['mid'];
       // $data = file_get_contents('http://pads6.pa-sport.com/api/football/competition/fixturesEnhanced/B2j8snvg44/' . $mid . '/json');
        //$data = str_replace("@", "", $data);
        
        $data = get_football_PADS($mid, 'fixturesEnhanced');
          
         // $data = '{"football":{"match":[{"matchID":"3774886","dateTime":"2014-09-12T19:30:00","originalID":"3731004","leg":"1","matchStatus":"New Match","lastStatusUpdate":"2014-09-02T13:32:15.423","stage":{"stageNumber":"1","stageType":"League"},"round":{"roundNumber":"1","roundName":"League"},"venue":{"id":"298","name":"Municipal De Gerland"},"team":[{"id":"26345","name":"Lyon","homeTeam":"true"},{"id":"26343","name":"Monaco","homeTeam":"false"}]},{"matchID":"3731008","dateTime":"2014-09-13T16:00:00","leg":"1","matchStatus":"New Match","stage":{"stageNumber":"1","stageType":"League"},"round":{"roundNumber":"1","roundName":"League"},"venue":{"id":"246","name":"Route de Lorient"},"team":[{"id":"26349","name":"Rennes","homeTeam":"true"},{"id":"26339","name":"PSG","homeTeam":"false"}]},{"matchID":"3731000","dateTime":"2014-09-13T19:00:00","leg":"1","matchStatus":"New Match","stage":{"stageNumber":"1","stageType":"League"},"round":{"roundNumber":"1","roundName":"League"},"venue":{"id":"127","name":"Armand-Cesari-Furiani"},"team":[{"id":"26341","name":"Bastia","homeTeam":"true"},{"id":"26342","name":"Lens","homeTeam":"false"}]},{"matchID":"3731001","dateTime":"2014-09-13T19:00:00","leg":"1","matchStatus":"Postponed","lastStatusUpdate":"2014-09-02T13:31:14.740","comment":"Postponed - now being played Sun, Sep 14","stage":{"stageNumber":"1","stageType":"League"},"round":{"roundNumber":"1","roundName":"League"},"venue":{"id":"1059","name":"Parc des Sports"},"team":[{"id":"59842","name":"Evian TG","homeTeam":"true"},{"id":"26344","name":"Marseille","homeTeam":"false"}]},{"matchID":"3731002","dateTime":"2014-09-13T19:00:00","leg":"1","matchStatus":"Postponed","lastStatusUpdate":"2014-09-02T13:31:15.133","comment":"Postponed - now being played Sun, Sep 14","stage":{"stageNumber":"1","stageType":"League"},"round":{"roundNumber":"1","roundName":"League"},"venue":{"id":"361","name":"Du Roudourou"},"team":[{"id":"26347","name":"Guingamp","homeTeam":"true"},{"id":"26340","name":"Bordeaux","homeTeam":"false"}]},{"matchID":"3731003","dateTime":"2014-09-13T19:00:00","leg":"1","matchStatus":"Postponed","lastStatusUpdate":"2014-09-02T13:31:15.487","comment":"Postponed - now being played Sun, Sep 14","stage":{"stageNumber":"1","stageType":"League"},"round":{"roundNumber":"1","roundName":"League"},"venue":{"id":"6914","name":"Grand Stade Lille Metropole"},"team":[{"id":"27372","name":"Lille","homeTeam":"true"},{"id":"26350","name":"Nantes","homeTeam":"false"}]},{"matchID":"3731004","dateTime":"2014-09-13T19:00:00","leg":"1","matchStatus":"Postponed","lastStatusUpdate":"2014-09-02T13:31:15.870","comment":"Postponed - now being played Fri, Sep 12","stage":{"stageNumber":"1","stageType":"League"},"round":{"roundNumber":"1","roundName":"League"},"venue":{"id":"298","name":"Municipal De Gerland"},"team":[{"id":"26345","name":"Lyon","homeTeam":"true"},{"id":"26343","name":"Monaco","homeTeam":"false"}]},{"matchID":"3731005","dateTime":"2014-09-13T19:00:00","leg":"1","matchStatus":"New Match","stage":{"stageNumber":"1","stageType":"League"},"round":{"roundNumber":"1","roundName":"League"},"venue":{"id":"362","name":"La Mosson"},"team":[{"id":"26351","name":"Montpellier","homeTeam":"true"},{"id":"26469","name":"Lorient","homeTeam":"false"}]},{"matchID":"3731006","dateTime":"2014-09-13T19:00:00","leg":"1","matchStatus":"New Match","stage":{"stageNumber":"1","stageType":"League"},"round":{"roundNumber":"1","roundName":"League"},"venue":{"id":"430","name":"Allianz Riviera"},"team":[{"id":"27462","name":"Nice","homeTeam":"true"},{"id":"6832","name":"Metz","homeTeam":"false"}]},{"matchID":"3731007","dateTime":"2014-09-13T19:00:00","leg":"1","matchStatus":"New Match","stage":{"stageNumber":"1","stageType":"League"},"round":{"roundNumber":"1","roundName":"League"},"venue":{"id":"130","name":"Auguste Delaune"},"team":[{"id":"41053","name":"Reims","homeTeam":"true"},{"id":"26346","name":"Toulouse","homeTeam":"false"}]},{"matchID":"3731009","dateTime":"2014-09-13T19:00:00","leg":"1","matchStatus":"New Match","stage":{"stageNumber":"1","stageType":"League"},"round":{"roundNumber":"1","roundName":"League"},"venue":{"id":"468","name":"Geoffroy-Guichard"},"team":[{"id":"27408","name":"St Etienne","homeTeam":"true"},{"id":"27435","name":"Caen","homeTeam":"false"}]}]}}';
         $callback = $_REQUEST['callback'];
          
         echo $callback . '({
                    "proposals":[ 
                    ' . $data .  ']})';
    break;
    
    case "League_table":
		$mid = $_REQUEST['mid'];
      
        $data = get_football_PADS($mid, 'LeagueTableEnhanced');
        
        $callback = $_REQUEST['callback'];
          
        echo $callback . '({
                    "proposals":[ 
                    ' . $data .  ']})';
    break;          
    
    case "trend_tags":
          echo trend_tags();
    break; 
    
    case "delete_old_news":
          delete_old_news();
    break;
    
    case "get_latest_news":
          echo get_latest_news($_REQUEST['sources'], $_REQUEST['client_id'], ($_REQUEST['start']-1), $_REQUEST['offset']);
    break;
    
    case "get_latest_news_by_categories":   //sources = 1,2,3xxx4,5xxx7,8,9,77,55xxx434
          echo get_latest_news_by_categories($_REQUEST['sources'], $_REQUEST['client_id']);   ///////////not used 
    break;
    
    case "get_recent_read":
          echo get_recent_read($_REQUEST['article_ids'], $_REQUEST['client_id']);
    break;
    
    case "get_random_news":
          echo get_random_news($_REQUEST['client_id'], $_REQUEST['my_sources'] ,($_REQUEST['start']-1), $_REQUEST['offset']);
    break;
    
    case "get_breaking_news":
          if (!isset($_REQUEST['sources'])) $_REQUEST['sources'] = '';
          echo get_break_news($_REQUEST['sources'], $_REQUEST['client_id'], ($_REQUEST['start']-1), $_REQUEST['offset']);
    break;
    
    case "follow_source":     
         follow_source($_REQUEST['udid'], $_REQUEST['sources']);
    break;
    
    case "update_mobile_for_new_and_updated_categories"://for update client(phone)
          echo update_mobile_for_new_and_updated_categories($_REQUEST['uv'], $_REQUEST['av']);
    break;
    
    //*********************keywords************************
    case "search_keyword":
          echo search_keyword($_REQUEST['keyword']);
    break;
    
    case "add_keyword":
          echo add_keyword($_REQUEST['keyword'], $_REQUEST['udid']);
    break; 
    
    case "get_my_keywords":
          echo get_my_keywords($_REQUEST['udid']);
    break;
    
    case "get_news_by_keywords_from_mysources":
          echo get_news_by_keywords_from_mysources($_REQUEST['keyword_id'], $_REQUEST['udid'], ($_REQUEST['start']-1), $_REQUEST['offset']);
    break;
}