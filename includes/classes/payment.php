<?php
  class Payment{
      private $conn; 
    
      function __construct() {
         $this->conn = new MySQLiDatabaseConnection();
      }
      
      function insert($uid, $code, $start_date, $end_date){
          $query = "insert into payments (uid, code, date_added, end_date)
                    value 
                    ('$uid','$code', '$start_date', '$end_date')";
                          // echo($query);     
          $this->conn->db_query($query);
          
          return $this->conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning  
      }
      
      function update_log_del_status($uid, $del) {
          $query = "update payments set del_status = '$del' where uid = '$uid'";
          echo($query);
          $this->conn->db_query($query);
      }
      
      function add_payment($uid, $start_date, $end_date) {
          $query = "insert into payments (uid, date_added, end_date) value ('$uid', '$start_date', '$end_date')";   
          $this->conn->db_query($query);   
          return $this->conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning  
      }
      
      function update_payment($uid, $start_date, $end_date) {
          $query = "update payments set date_added = '$start_date', end_date = '$end_date' where uid = '$uid'";   
          $this->conn->db_query($query);   
      }
      
      function get_user_payment($uid){
          $query = "select * from payments where uid = '$uid'"; 
          $res = $this->conn->db_query($query); 
          return $this->conn->db_fetch_object($res);
      }
      
      function update_payment_tries ($uid) {
          $query = "update payments set repayment_count = repayment_count + 1 where uid = '$uid'";
          $res = $this->conn->db_query($query);   
      }
      
      function reset_payment_tries($uid) {
          $query = "update payments set repayment_count = 0 where uid = '$uid'";
          $res = $this->conn->db_query($query);   
      }
      
      function renew_payment($user){
          $this->update_payment_tries($user->uid);
          
          $db_functions_obj = new DbFunctions();
          $payment_obj = new Payment();
          $helper_obj = new Helper();
          
          //pr($user);  //exit;
                  
          //exit;
          $operator_details = $db_functions_obj->get_operator_by_id($user->operator_id);
               //pr($operator_details);  exit;
               
        /*  if ($operator_details['type'] == "MO") {
                 $from = $operator_details['paid_shortcode'];
                // $msg = $operator_details['verification_code'];  ///شو لازم تكون المسج
                 $smsc = $operator_details['paid_smsc']; 
          }
          else{*/
                $from = $operator_details['paid_shortcode'];
               // $msg = $operator_details['verification_code']; 
                $smsc = $operator_details['paid_smsc'];  
         /* }    */
               
               
         /* $log_id = $payment_obj->insert($user->uid, $operator_details['country_id'], $user->cid, $user->operator_id, "تم تفعيل الاشتراك بنجاح", $operator_details['paid_shortcode'], 
                                         $user->phone, 1);  */
        
         // $date = time();
         // $next_time = $date + $operator_details['period'] /*7 days*/;
        
         // $payment_obj->update_payment($user->uid, $date, $next_time);
        
          $db_functions_obj->update_repayment_count($user->uid);
        
          //$root = "http://arh:8080/appstreamig/streaming/"; 
          $root = "http://www.jeelplus.com/appstreamig/streaming/api/"; 
          $dlr_url = $root . 'index.php?action=dlr' . ('&par=%d_2_' . $user->uid . "_" . $user->operator_id . "_" . $user->phone);
                   //echo($dlr_url);
                   
          if ($user->uid){   
             $number = $user->phone;    
             $port = $operator_details['port'];
            
             $helper_obj->send_sms($from, $number, ("سيتم تفعيل اشتراكك"), $smsc, $dlr_url, $port);     
          }
        
         // return $uid; 
          
      }
  }
?>
