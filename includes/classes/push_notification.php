<?php
  class PushNotification {
      private $conn; 
      
      function __construct() {
          $this->conn = new MySQLiDatabaseConnection();
      }
      
      function build_send_push_notificatation_form (){
          $helper_obj = new Helper();
          global $base_path;
          
          $client_obj = new Client();
          
          $output = '<div class="box box-bordered">';
          $output .= '<div class="box-title">
                                <h3><i class="icon-th-list"></i> Send Push Notification</h3>
                      </div>';
          $output .= "<div class='box-content nopadding'>";
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='send_pn_action' id='send_pn_action' method='post' action='$base_path" . "send_pn_action'>";
        
          $output .= $client_obj->build_clients_list("add");
                      
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Message Body</label>
                        <div class='controls'>
                           <textarea placeholder='Message' data-rule-required='true' name='msg' id='msg'></textarea><br />   
                        </div>    
                    </div>";
                    
          $output .= "<input class=\"btn btn-primary\" type='submit' value='" . $helper_obj->t("Save") . "'>";
          $output .= "<input style='display: none' type='reset' id='add_client_reset'>";
          $output .= "</form>";
          $output .= "</div>";
          $output .= "</div><br />";
          $output .= "<div style='color: red'>Note: If you choose 'Select ...' in clients list that mean you will send PN for all tokens</div><br /><br />";
         
          return $output;
      }   
      
      // Put your device token here (without spaces):
      function push_notification($messages, $iosDeviceId, $device_type = ''/*2: ios, 1: andriod*/ , $pem){  
            if (trim($iosDeviceId) == '-' || trim($iosDeviceId) == "") return;
            
            //$deviceId=$_REQUEST['deviceId'];
            //$deviceToken = "$deviceId";
            $deviceToken="$iosDeviceId";
            // Put your private key's passphrase here:
            $passphrase = 'pushchat';
            
            // Put your alert message here:
            //$inputMsg=$_REQUEST['message'];
            $message =$messages;
            
            ////////////////////////////////////////////////////////////////////////////////
                     
            $ctx = stream_context_create();
            stream_context_set_option($ctx, 'ssl', 'local_cert', $pem);
            stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
            
            // Open a connection to the APNS server
            $fp = stream_socket_client(
               /* 'ssl://gateway.sandbox.push.apple.com:2195' */'ssl://gateway.push.apple.com:2195' , $err,
                $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
            
          //  echo('er: ');
           // print_r($errstr);
           // print_r($err);

            if (!$fp)
                exit("Failed to connect: $err $errstr" . PHP_EOL);
            
            //echo 'Connected to APNS' . PHP_EOL;
            
            // Create the payload body
            $body['aps'] = array(
                'alert' => $message,
                'sound' => 'default'
                );
            
            // Encode the payload as JSON
            $payload = json_encode($body);
            
            // Build the binary notification
            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
            
            // Send it to the server
            $result = fwrite($fp, $msg, strlen($msg));
            
            if (!$result)
                echo 'Message not delivered' . PHP_EOL;
            else {
               // echo 'Message successfully delivered: ' . $iosDeviceId . PHP_EOL;
                        /* echo('<pre>');
                    print_r($result);
                    echo('</pre>');  */
            }
            
            // Close the connection to the server
            fclose($fp);
      }
  
  }
?>
