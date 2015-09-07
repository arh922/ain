<?php     
define("SOCIAL_SEPARATOR_FOR_NAME", 'jeel1amc1jeel');

class User {
    private $conn; 
    
    function __construct() {
        $this->conn = new MySQLiDatabaseConnection();
    }
    
    function login($form_data, $api = false) {
        $name = $this->conn->db_escape_string($form_data['name']);
        $pass = $this->conn->db_escape_string($form_data['pass']);
        global $user, $base_path;
        
        //$pass = md5($pass);
        
        $login = $this->conn->db_query("SELECT id FROM users WHERE status = 1 and (username = '$name' or email = '$name' or id = '$name') and password = md5('$pass')");
        $is_user_valid = '';
        
        //while ($row = $this->conn->db_fetch_object($login)){
        while($row = $this->conn->fetch_assoc($login)){      //pr($row);
            $is_user_valid = $row['id'];
            $user = $this->user_load($row['id']);
             // pr($user);      exit;
            //$this->update_online_status(1, $row->id); 
            
            $_SESSION['user'] = $user;
            
            $user = base64_encode(md5($user['id']) . SAVE_USERDATA_COOKIE_SEPARATER . md5($user['id']));
                          
            setcookie("login", $user, time() + (60*60*24*365), $base_path);    
        }
        
        $login->free();
        
        return $is_user_valid;
    }
    
    function user_load($uid, $md5 = 0) {  
      
        $where = "u.id = $uid";
       
        if ($md5){
           $where = "md5(u.id) = '$uid'";
        }
        
        $login = $this->conn->db_query("SELECT u.id, username, password, email, u.date_added, phone, u.status, rid, country_id, operator_id, payment_id,
                                               c.id cid, c.name cname
                                         FROM users u 
                                         LEFT JOIN users_roles ur on u.id = ur.uid  
                                         LEFT JOIN clients c on c.id = ur.cid  
                                         WHERE $where"); 
                                   
        $user = array();                                 
        while ($row = $this->conn->db_fetch_array($login)){
        //while($row = $login->fetch_assoc()){
            $user[] = $row;
        }
         
        $login->free(); 
             
        return isset($user[0])?$user[0]:$user;
    }
   
    function check_username_available($username) {
        $username = $this->conn->db_escape_string($username);
        
        $query = "
            SELECT count(*) c 
            FROM users 
            WHERE name= '$username'";
                                  
        $record = $this->conn->db_query($query);
        $find = 0;
        while ($row = $this->conn->db_fetch_object($record)){
            $find = $row->c;
        }
        
        return $find;
    }
    
    function check_user_exists_by_udid($udid) {
        //$username = $this->conn->db_escape_string($username);
        
        $query = "
            SELECT count(id) c 
            FROM users 
            WHERE udid= '$udid'";
                                  
        $record = $this->conn->db_query($query);
        $find = 0;
        
        while($row = $this->conn->fetch_assoc($record)){
            $find = $row['c'];
        }
        
        return $find;
    }
    
    function get_uid_by_udid($udid){
        $query = "select id from users where udid = '$udid'"; 
        $record = $this->conn->db_query($query);  
        $row = $this->conn->fetch_assoc($record);
        return $row['id'];  
    }
     
    function check_mail_available($mail) {
        $mail = $this->conn->db_escape_string($mail);
        
        $query = "
            SELECT count(*) c
            FROM users 
            WHERE `email` = '$mail'";
             
        $record = $this->conn->db_query($query); 
         
        while ($row = $this->conn->db_fetch_object($record)){
            $find = $row->c;
        }
              
        return $find;;
    }
       
    function build_forget_password_form(){
        $helper_obj = new Helper();
        $output = "<div class='new-pass'>";
        $output .= "<div id='signup-header'>" .
                       $helper_obj->t("Forget Password") . 
                   "</div>";
        $output .= "<div style='margin: auto; width: 100%;color: white'>";
        $output .= "<form name='forget_password' id='forget_password' method='post' action='new_password'>";
        $output .= "<table style='border: 0'><tr><td style='color:white'>" . $helper_obj->t("Email or Username:") . "</td><td>" . " <input style=' height: 32px;width: 220px;' type='text' name='email' id='email'></td></tr>";
        $output .= "<tr><td colspan='3'><input class='Development-gold_button' style='margin-right: 65px' type='submit' value='" . $helper_obj->t("Request new password") . "'></td></tr></table>";
        $output .= "</form>";
        $output .= "</div>";
        $output .= "</div>";
        
        return $output;
    }
    
    function check_if_username_or_email_exists($email){
        $db_functions_obj = new DbFunctions(); 
        $helper_obj = new Helper();
                             
        $mail_valid = $this->check_mail_available($email);
           
        if ($mail_valid) {    
            $url = $this->generate_forget_password_url($email);
            $to = $email;
           
            $header = $helper_obj->build_mail_header();
                        
            mail($to, $helper_obj->t("Forget Password"),  $url, $header);   
            
            return $helper_obj->t("Forget password link sent to your email"); 
 
        }
        else{
            return $helper_obj->t("Email Address or Username are not exist");
        }
    }
    
    function generate_forget_password_url ($email){
        global $base_path;
                             
        $converter = new Encryption;
        $encode = $converter->encode($email . CHANGE_PW_SEPARATOR . time());
       
        $url = "http://" . $_SERVER['HTTP_HOST'] . $base_path . "forget_password/" . $encode; 
        
        return $url;
    } 
    
    function build_change_forget_password_form($email_or_username, $type){
        $helper_obj = new Helper();   
        $db_functions_obj = new DbFunctions();  
        
        $output = "<div class='change-pw-div'>";
        $output .= "<div class='reg-fields-title'>" . $helper_obj->t("Change Password") . "</div>";
        $output .= "<div style='margin:auto;width:37%'>";
        $output .= "<form name='change_forget_password' id='change_forget_password' method='post'>";
        $output .= "<input plaseholder='" . $helper_obj->t("Password") . "' class='form-item-input' type='password' name='password_forget' id='password_forget'>";
        $output .=  "<div class='reg-item'>
                        <input type='checkbox' onclick='showPassword(\"_forget\");'>
                         <div class='show-password-text'>" . $helper_obj->t("Show My Password") . " </div>
                     </div>";
        $output .= "<input class='submit' style='margin-right: 15px;width: 245px;' type='submit' value='" . $helper_obj->t("Change") . "'>";
        $output .= "</form>";
        $output .= "</div>";
        $output .= "</div>";
                                                
        if (isset($_POST['password_forget']) && trim($_POST['password_forget']) != ''){   
            $valid = $db_functions_obj->change_password($email_or_username, $type, $_POST['password_forget']); 
            
            if ($valid){
                $helper_obj->set_message($helper_obj->t("Password has been changed successfully"), 'success');
            }
            else{   
                $helper_obj->set_message($helper_obj->t("Error while changing password"), 'error');
            }
        }
        
        return $output; 
    }
    
    function check_old_password($old_pw){
        $db_functions_obj = new DbFunctions();
        $old_pw = urldecode($old_pw);
        return $db_functions_obj->check_old_password($old_pw);
    }
    
    function build_login_form(){
        global $base_path;
        $helper_obj = new Helper();
        
        $output = '<div class="box box-bordered">';  
        $output .= '<div class="box-title">
                                <h3><i class="icon-th-list"></i> Login Form</h3>
                            </div>';
                            
        $output .= "<form class='form-horizontal form-vertical form-bordered' method='post' name='login-form' action='" . $base_path . "login'>
                       <div class='login-form'>
                          <div class='control-group'> 
                            <label class='control-label' for='client_name'>Username or Email</label>
                               <div class='controls'>
                                  <input name='name' type='text' placeholder='" . $helper_obj->t("Username or Email") . "'>
                               </div>
                          </div>
                          
                          <div class='control-group'> 
                            <label class='control-label' for='client_name'>Password</label>
                               <div class='controls'>
                                  <input name='pass' type='password' placeholder='" . $helper_obj->t("Password") . "'>
                                </div>
                          </div>
                          
                          <input class='btn btn-primary' type='submit' value='" . $helper_obj->t("Sign in") . "'>
                       </div>
                   </form>";
                   
                   
        return $output;
    }
    
    function get_all_users($cid = 1) {
        $db_functions_obj = new DbFunctions();
        $helper_obj = new Helper(); 
        global $user;
        
        $uid = $user['id'];
        
        $users = $db_functions_obj->get_all_users($uid, $cid);
        
        $output = "<table id='add_user_table' class='table table-hover table-nomargin table-bordered'>
                      <tr>
                        <th>" . $helper_obj->t("ID") . "</th>
                        <th>" . $helper_obj->t("Username") . "</th>
                        <th>" . $helper_obj->t("Your email") . "</th>
                        <th>" . $helper_obj->t("Phone") . "</th>
                        <th>" . $helper_obj->t("Role") . "</th>
                        <th>" . $helper_obj->t("Country") . "</th>
                        <th>" . $helper_obj->t("Operator") . "</th>
                        <th>" . $helper_obj->t("Client") . "</th>
                        <th>" . $helper_obj->t("Status") . "</th>
                        <th>" . $helper_obj->t("Date Added") . "</th>   
                        <th>" . $helper_obj->t("Edit") . "</th>
                        <th>" . $helper_obj->t("Delete") . "</th>
                      </tr>";

         foreach($users as $key => $value) {
            $class = $helper_obj->table_row_class($i);
             
            $output .= "<tr class='$class' id='user_$value[id]'>
                          <td>" . $value['id'] . "</td>
                          <td>" . $value['username'] . "</td>
                          <td>" . $value['email'] . "</td>
                          <td>" . $value['phone'] . "</td>
                          <td>" . $value['role_name'] . "</td>
                          <td>" . $value['country_name'] . "</td>
                          <td>" . $value['operator_name'] . "</td>
                          <td>" . $value['client_name'] . "</td>
                          <td><div id='user_status_$value[id]'>" . ($value['status'] == 1 ? $helper_obj->t("Active") : $helper_obj->t("Deactive")) . "</div></td>
                          <td>" . date(DATE_FORMAT, $value['date_added']) . "</td> 
                          <td><a href='javascript:void(0);' onclick='openEditUserPopup($value[id])'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='activeDeactiveUser($value[id])'>
                                <div id='deactive_$value[id]'>" . ($value['status'] == 1 ? $helper_obj->t("Deactivate") : $helper_obj->t("Activate")) . "</div>
                              </a>
                          </td>
                       </tr>";
         }             
        
         $output .= "</table>"; 
         
         return $output;           
    }
    
    function build_countries_list($page = 'add', $selected_client = "", $onclick = "", $style = "") {
          $db_functions_obj = new DbFunctions();
          $helper_obj = new Helper();
          
          $countries = $db_functions_obj->get_country();
          
          $output = '<div class="control-group">
                       <label class="control-label" for="select">Countries</label>
                       <div class="controls">';
                       
          $output .= "<select $style $onclick id='$page" . "_country' name='$page" . "_country'>";
          $output .= "<option value=''>" . $helper_obj->t("Select...") . "</option>";
          
          foreach($countries as $key => $value){
              if ($selected_client != ""){
                $output .= "<option ";
                if ($selected_client == $value['id']) {
                      $output .= " selected='selected' ";
                }
                $output .= "value='$value[id]'>$value[name]</option>";
              }
              else{
                  $output .= "<option value='$value[id]'>$value[name]</option>";  
              }
          }
          $output .= "</select>";
          $output .= "</div>";
          $output .= "</div>";
          
          return $output;
    } 
    
    function build_operators_list($country_id, $page = "add", $onclick = "", $selected_client = ""){
        $db_functions_obj = new DbFunctions();
        $helper_obj = new Helper();
          
        $operators = $db_functions_obj->get_operators_by_country($country_id);

        $output = "<select $onclick id='$page" . "_operator' name='$page" . "_operator'>";
         
        foreach($operators as $key => $value){
          if ($selected_client != ""){
            $output .= "<option ";
            if ($selected_client == $value->id) {
                  $output .= " selected='selected' ";
            }
            $output .= "value='$value->id'>$value->name</option>";
          }
          else{
              $output .= "<option value='$value->id'>$value->name</option>";  
          }
        }
        $output .= "</select>";
          
        return $output;
    }
    
    function build_add_user_form($rid = null){
        $helper_obj = new Helper();
        $client_obj = new Client();
       // $validation_js_obj = new Validation_js();
                  
        $output = '<div class="box box-bordered">';
        $output .= '<div class="box-title">
                                <h3><i class="icon-th-list"></i> Add User</h3>
                            </div>';
        $output .= "<div class='box-content nopadding'>";
        $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='add_user' id='add_user' method='post' action='add_user'>";
       
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>User Name</label>
                        <div class='controls'> 
                          <input class='input-xlarge' data-rule-required='true' data-rule-minlength='4' type='text' name='username' id='username' placeholder='" . $helper_obj->t("Username") . "'>
                        </div>
                      </div>";
                      
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Password</label>
                        <div class='controls'> 
                          <input class='input-xlarge' data-rule-required='true' data-rule-minlength='" . PASSWORD_MIN_LENGTH . "' type='password' name='password' id='password' placeholder='" . $helper_obj->t("Password") . "'>
                        </div>
                      </div>";
                      
        $output .= '<div class="control-group">
                                        <label for="confirmpassword" class="control-label">Confirm password</label>
                                        <div class="controls">
                                            <input type="text" name="confirmpassword" id="confirmpassword" type="password" class="input-xlarge" data-rule-equalTo="#password" data-rule-required="true">
                                        </div>
                                    </div>';
                                    
  
                      
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Email</label>
                        <div class='controls'> 
                          <input class='input-xlarge' data-rule-required='true' data-rule-email='true' type='text' name='email' id='email' placeholder='" . $helper_obj->t("Email") . "'>
                        </div>
                      </div>";
                      
        $output .= '<div class="control-group">
                                        <label for="confirmemail" class="control-label">Confirm Email</label>
                                        <div class="controls">
                                            <input type="text" name="confirmemail" id="confirmemail" class="input-xlarge" data-rule-equalTo="#email" data-rule-required="true">
                                        </div>
                                    </div>';
                      
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Phone</label>
                        <div class='controls'>  
                          <input class='input-xlarge 'data-rule-number='true' data-rule-required='true' type='text' name='phone' id='phone' placeholder='" . $helper_obj->t("Phone") . "'>
                        </div>
                      </div>";
                                           
        $output .= $this->build_roles_list("onchange='getClientsByRole(this)'", 'add', '', "style='width: 152px'") ;
        $output .= $client_obj->build_clients_list('add','','',"style='width: 152px'");
        $output .= $this->build_countries_list('add', '', "onchange='getOperatorsByCountry(this)'", "style='width: 152px'");
        $output .= '<div class="control-group">
                       <label class="control-label" for="select">Operators</label>
                       <div class="controls">';
        $output .= "<div id='operator_list'><select style='width: 152px'><option></option></select></div>";
        $output .= "</div>"; 
        $output .= "</div>"; 
        $output .= "<input class='btn btn-primary' type='submit' value='" . $helper_obj->t("Save") . "'>";
        $output .= "<input style='display: none' type='reset' id='add_user_reset'>"; 
        $output .= "</form>";
        $output .= "</div><br /><br />";
        
        return $output;
    }
    
    function build_roles_list($onclick = "", $page = "", $selected_client = "", $style = "", $exeption = REQULAR_USER_ROLE_ID) {
        $db_functions_obj = new DbFunctions();
        $helper_obj = new Helper();
        
        $roles = $db_functions_obj->get_roles();
        
        $output = '<div class="control-group">
                       <label class="control-label" for="select">Roles</label>
                   <div class="controls">';
        $output .= "<select data-rule-required='true' $style $onclick id='$page" . "_role' name='$page" . "_role'>";
        $output .= "<option value=''>" . $helper_obj->t("Select...") . "</option>";
           
        foreach($roles as $key => $value){ 
            if ($exeption == $value['id']) {
                continue;
            }
            
            if ($selected_client != ""){
                $output .= "<option ";
                if ($selected_client == $value['id']) {
                    $output .= " selected='selected' ";
                }
            $output .= "value='$value[id]'>$value[name]</option>";
          }
          else{
              $output .= "<option value='$value[id]'>$value[name]</option>";  
          }
        }
        $output .= "</select>";
        $output .= "</div>";
        $output .= "</div>";
          
        return $output;
    }
    
    function add_user($data, $api = false) {
        $db_functions_obj = new DbFunctions();
        
        $uid = "";
        
     //   if (!$db_functions_obj->check_if_phone_exists($data['phone'])) {
            $uid = $db_functions_obj->add_user($data);
     //   }
        
       /* if ($api) {
            if ($uid) {
                return ('{"success":"1","message":"success", "uid": "' . $uid . '"}'); 
            }
            else{
                return ('{"success":"0","message":"failed"}'); 
            }
        }
        else{
            return $uid; 
        }   */
        
        return $uid; 
    }
    
    function build_edit_user_popup($uid){
          $helper_obj = new Helper();
          $db_functions_obj = new DbFunctions();
       //   $validation_js_obj = new Validation_js();
          
          $user_info = $db_functions_obj->get_user_detail_by_id($uid);
                       
       //   $output = $validation_js_obj->edit_user_validation();
           
          $output = "<script>$(document).ready(function() {  $('#edit_user').ajaxForm(function(res) { 
                        var isvalid = $(\"#edit_user\").valid();
                        if (isvalid) { 
                            var data = res.split(\"***#***\"); 
                            $('#user_' + data[1]).after(data[0]);
                            $('#user_' + data[1]).remove(); 
                            closePopup();
                        }  
                    }); });</script>";
          $output .= "<div class='popup-header'>" . $helper_obj->t("Edit User") . " " . $user_info['username'] . "</div>";
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='edit_user' id='edit_user' method='post' action='edit_user'>";
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>User Name</label>
                        <div class='controls'>
                           <input class='input-xlarge' data-rule-required='true' data-rule-minlength='4' value='" . $user_info['username'] . "' type='text' id='user_name_update' name='user_name_update' placeholder='" . $helper_obj->t("UserName") . "'>
                        </div>
                      </div>";
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Password</label>
                        <div class='controls'>
                           <input type='text' id='password_update' name='password_update' placeholder='" . $helper_obj->t("Password") . "'>
                        </div>
                      </div>";
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Email</label>
                        <div class='controls'>
                           <input class='input-xlarge' data-rule-email='true' data-rule-required='true' value='" . $user_info['email'] . "' type='text' id='email_update' name='email_update' placeholder='" . $helper_obj->t("Email") . "'>
                        </div>
                      </div>";
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Phone</label>
                        <div class='controls'>
                           <input value='" . $user_info['phone'] . "' type='text' id='phone_update' name='phone_update' placeholder='" . $helper_obj->t("Phone") . "'>
                        </div>
                      </div>";
          $output .= "<input class='btn btn-primary' type='submit' value='" . $helper_obj->t("Save") . "'>";
          $output .= "<input type='hidden' name='user_id_updated' value='" . $user_info['id'] . "'>";
          $output .= "</form>";
          
          return $output; 
      }
      
      function edit_user($uid, $username, $email, $phone, $password) {
          $db_functions_obj = new DbFunctions();
          $db_functions_obj->edit_user($uid, $username, $email, $phone, $password);
      }
      
      function build_user_row($data) {
      //  pr($data);
        $helper_obj = new Helper();
      
        $output = "<tr id='user_$data->id'>
                          <td>" . $data->id . "</td>
                          <td>" . $data->username . "</td>
                          <td>" . $data->email . "</td>
                          <td>" . $data->phone . "</td>
                          <td>" . $data->role_name . "</td>
                          <td>" . $data->country_name . "</td>
                          <td>" . $data->operator_name . "</td>
                          <td>" . $data->client_name . "</td>
                          <td><div id='user_status_$data->id'>" . ($data->status == 1 ? $helper_obj->t("Active") : $helper_obj->t("Deactive")) . "</div></td>
                          <td>" . date(DATE_FORMAT, $data->date_added) . "</td> 
                          <td><a href='javascript:void(0);' onclick='openEditUserPopup($data->id)'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='activeDeactiveUser($data->id)'>
                                <div id='deactive_$data->id'>" . ($data->status == 1 ? $helper_obj->t("Deactivate") : $helper_obj->t("Activate")) . "</div>
                              </a>
                          </td>
                       </tr>";
                       
        return $output;
    } 
    
    function register_user(){
        $user_obj = new User();
        $helper_obj = new Helper();
        $log_obj = new Payment();  
        $db_functions_obj = new DbFunctions(); 
        $uid = 0;
                        
        $data['username'] = '';
        $data['password'] = '';
        $data['phone'] = '';
        $data['email'] = '';
        $data['add_role'] = 3;
        $data['add_client'] = 47;
        $data['add_country'] = '';  
        $data['add_operator'] = ''; 
        $data['status'] = 1; 
        $data['verification_code'] = rand(999,9999); 
        $data['udid'] = $_REQUEST['udid']; 
        $data['device_id'] = $_REQUEST['device_id']; 
        $data['type'] = $_REQUEST['type']; 
        
        $user_exists = $user_obj->check_user_exists_by_udid($data['udid']);
        
        if (!$user_exists) {                  
            $uid = $user_obj->add_user($data, true);
        }
        else{
            $uid = '-1';
        }
        
        //$op_details = $db_functions_obj->get_operator_by_id($data['add_operator']);
                          
       /* if ($op_details['type'] == "MO") {
             $from = $op_details['paid_shortcode'];
             $msg = $data['verification_code'];  ///شو لازم تكون المسج
             $smsc = $op_details['paid_smsc']; 
        }
        else{ */
         //   $from = $op_details['free_shortcode'];
         //   $msg = "Your verification code is: " . $data['verification_code']; 
         //   $smsc = $op_details['free_smsc'];  
       // }
               
       // $reg_status_decoded = (json_decode($reg_status, true));
        
        //$uid = $reg_status;
          
        
        
        //$date = time();
      //  $next_time = $date + PAYMENT_END_DATE /*7 days*/;
        
      //  $log_id = $log_obj->insert($uid, $data['verification_code'], 0, 0);
        
        //$log_obj->add_payment($uid, $date, $next_time);
        
        //$root = "http://comm.m-diet.com/mdiet/web/ayman/"; 
     //   $root = "http://www.jeelplus.com/appstreamig/streaming/api"; 
        //$dlr_url = $root . 'api.php?action=dlr' . urlencode('&par=%d_');  
      //  $dlr_url = $root . 'index.php?action=dlr' . urlencode('&par=%d_');  
        
       // echo $reg_status;
       // return $uid;
        
       /* if ($uid){   
            $number = $data['phone'];    
            $port = $op_details['port'];
            $msg = urlencode($msg);
            $helper_obj->send_sms($from, $number, $msg, $smsc, $dlr_url, $port);     
        } */
        
        return $uid; 
    }

}