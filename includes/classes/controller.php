<?php
//require_once 'google/appengine/api/cloud_storage/CloudStorageTools.php';
//use google\appengine\api\cloud_storage\CloudStorageTools;
  
class Controller {                                             
    private $action;
    private $params;
    private $content;
    private $menu;

    function __construct($q) {
      //  echo('<br />q3:' . $q.'<br />');
        $data = explode("/", $q);
        $this->action = $data[0];  // /home/par1/par2
        global $user;
        $menu_obj = new Menu();
        
        //$this->params = @$data[1];
        $new_params = "";
           
        for ($i = 1; $i < count($data); $i++) {
             $new_params .= $data[$i] . "/";
        }
             
        $new_params = substr(@$new_params, 0, strlen(@$new_params)-1);
        
        $this->params = @$new_params; 
          
        $function = $this->action;
        
        if ( empty($this->action) ) {  
            $function = "home";
            $this->action = $function;  
        }
        elseif (!method_exists($this, $function)) {
            $function = "page_not_found";
            $this->action = $function;
        }
                    
        $reflection = new ReflectionMethod($this, $this->action);
        
        if ($reflection->isPublic()) {
            //echo "Public method";
        }
    
        if ($reflection->isPrivate()) {
            //echo "Private method";
            $function = "page_not_found"; 
        }
        if ($reflection->isProtected()) {
            //echo "Protected method";
            $function = "page_not_found"; 
        } 
        
        if (MAINTENANCE){
            $function = "maintenance"; 
        }
         //   echo('function:' . $function);
        $this->$function($this->params); 
             
        if ($user['id']) {   //pr($user->id);
            $this->menu = $menu_obj->build_menu_by_client($user['cid']);
        }  
    }
    
    function maintenance() {
        global $base_path;
        $helper_obj = new Helper(); 
        $helper_obj->seo('', '', $helper_obj->t('Maintenance Mode')); 
                
        //here will be 404 page design - may taymoor make it in separate page and include it here
        $this->content = "<div class='page-not-found'>
                              <img width='200' style='background:#000000' src='" . $base_path . MAIN_IMAGES_PATH . "maintenance-mode.png" . "'><br /> 
                              " . $helper_obj->t("Our servers are under maintenance Sorry for the inconvenience We are working to get them back up as quick as we can") . "<br />
                          </div>";  
    }
    
    function page_not_found () {
        global $base_path;
        $helper_obj = new Helper(); 
        $helper_obj->seo('', '', $helper_obj->t('Page Not Found!')); 
        
        header("HTTP/1.0 404 Not Found");
             
        //here will be 404 page design - may taymoor make it in separate page and include it here
        $this->content = "<div class='page-not-found'>
                              <img style='background:#000000' src='" . $base_path . IMG_PATH . "logo.png" . "'><br /> 
                              " . $helper_obj->t("The page you are trying to access is not found") . "<br />
                              <a style='color: black' href='$base_path'>" . $helper_obj->t("Home") .  "</a>" .
                              "&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<a style='color: black' href='javascript:void(0);' onclick='history.back()'>" . $helper_obj->t("Back") . "</a> 
                          </div>";  
    }
    
    function access_denied() {
        global $base_path;
        $helper_obj = new Helper(); 
        $helper_obj->seo('', '', 'Access Denied!'); 
        
        //here will be 403 page design - may taymoor make it in separate page and include it here
        return "<div class='page-not-found'>
                  <img style='background:#000000' src='" . $base_path . IMG_PATH . "logo.png" . "'><br />   
                  " . $helper_obj->t("You don't have permission to access this page") . "<br />
                  <a style='color: black' href='$base_path'>" . $helper_obj->t("Home") .  "</a>" .
                  "&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<a style='color: black' href='javascript:void(0);' onclick='history.back()'>" . $helper_obj->t("Back") . "</a>         
               </div>";
    }
    
    private function set_access_denied($ajax = 0){
        if ($ajax) {
            $helper_obj = new Helper();
            echo $helper_obj->t("You have been signed out of JEELPLUS");
            exit;  
        }
        else{
            $this->content = $this->access_denied();
        }
    }
    
    function view() {
         echo($this->content); 
    }
    
    function main_menu() {
        echo($this->menu);
    }

    function home($id = "") {      
        $user_obj = new User();
        $helper_obj = new Helper();
        global $base_path;     
                         
        if (!$helper_obj->user_is_logged_in()) {    
            $this->content = $user_obj->build_login_form();
        }
        else{             
             header("location: " . $base_path . "admin_home");
             exit;
        }
  
    } 
    
    function admin_home(){
        $menu_obj = new Menu();   
        $helper_obj = new Helper();
        global $base_path, $menu_active;
        
        $menu_active['dashboard'] = 'active'; 
        
        if ($helper_obj->user_is_logged_in()) { 
            //$this->content = $this->menu;  
        }
        else{  
             header("location: " . $base_path, 0, 301);
             exit;
        }
        
        
    }   
  
    function profile($params) {
    }
    
    function check_old_password(){
        $users_obj = new User();
      
        $url_request = explode("?", $_SERVER['REQUEST_URI']);
                      
        if (trim(@$url_request[1]) != '') {  
             $old_pw = explode("=", $url_request[1]); 
                             
             $find = $users_obj->check_old_password($old_pw[1]);
             
             if (!$find){
                 echo "false";
             } 
             else{  
                 echo "true";
             }
             exit;
        }
    }
   
    function check_if_url_is_img(){
        $helper_obj = new Helper();
        
        $url_request = explode("?", $_SERVER['REQUEST_URI']);
                      
        if (trim(@$url_request[1]) != '') {  
             $url = explode("=", $url_request[1]); 
        }
        
        $find = $helper_obj->check_if_url_is_img($url[1]);
        
        if (!$find){
             echo "false";
        } 
        else{  
            echo "true";
        }
            
        exit;
    }
    
    function change_password(){ 
    
    }   
    
    function check_if_user_has_country(){
        $helper_obj = new Helper();
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(REGULAR_USER)) { 
            $img_com = new ImageCommunity();
            echo($img_com->check_if_user_has_country());
            exit;
        }
        else{
            $this->set_access_denied(1);
        }            
    }
    
    function logout($param = ''){    
        global $user, $base_path;
        $user_obj = new User();
        $uid = $user['id'];
       
        //set user online for reporting purpose
        //$user_obj->update_online_status(0, $uid);
        
        session_destroy(); 
        unset($user);
       // unset($_COOKIE['login']);
        //save login after close browser  
        setcookie("login", "", time() + (60*60*24*365), $base_path); 
        
        //for moderator logout 
        if ($param == 'ar'){
            setcookie("lang", 'ar', time() + (60*60*24*365), $base_path);  
        }
           
        header("location: $base_path", 0, 301);
        exit;
    }

    function js_translate($text){
        $helper_obj = new Helper();      
        $text = $helper_obj->t($text);
        echo($text);     
        exit;
    }   

    function login(){
        global $base_path;
        $helper_obj = new Helper();
           
        if (count($_POST) > 0) {  
             $user_obj = new User();  
             $is_valid_user = $user_obj->login($_POST);
                     
             if (!$is_valid_user){ 
                $this->home(); 
                  
                $this->content .=  "<script>
                                     $(document).ready(function($){
                                        _alert(dataAlert('" . $helper_obj->t("Error") . "','" .  $helper_obj->t("invalid username or password, please check again") . "'));
                                        $('#box-alert').css('height','120px');
                                        setTimeout(function(){ $('#box-alert').css('display', 'none');$('#lean_overlay').css('display', 'none');  },3000);
                                     });
                                 </script>";
                
                
             }
             else{   
                 header("location: " . $base_path, 0, 301);
                 exit;
             }
        }
        else{     
           // pr($_REQUEST);
            header("location: $base_path", 0, 301);
            exit;
        }
    }

    function route() {
       global $user;   
       $role = trim($user['rid']);
       $uid = $user['id'];
       
       $user_obj = new User();
       
       global $base_path;
       
       //set user online for reporting purpose
       //$user_obj->update_online_status(1, $uid);
       if ($role == REGULAR_USER) {  
           header("location: $base_path", 0, 301);
           exit;
       }
       else if($role == MODERATOR_USER) {
           header("location: ./mod", 0, 301);
           exit; 
       }
    }
    
    function check_username_available() {
        $user_obj = new User(); 

        global $base_path;
        
        $url_request = explode("?", $_SERVER['REQUEST_URI']);
       
        if (trim(@$url_request[1]) != '') {  
             $name = explode("=", $url_request[1]); 
             
             $name[1] = urldecode($name[1]);
             
             $find = $user_obj->check_username_available($name[1]);  

             if ($find){
                 echo "false";
             } 
             else{  
                 echo "true";
             }
             exit;
        }
        else{       
            header ("location: $base_path", 0, 301);
            exit;
        }
    
    }

    function check_mail_available() {
        $user_obj = new User();  
        global $base_path;
             
        $url_request = explode("?", $_SERVER['REQUEST_URI']);
        
        if (trim(@$url_request[1]) != ''){
            $email = explode("=", $url_request[1]);

            $email_decode = urldecode($email[1]);
             
            $find = $user_obj->check_mail_available($email_decode);  
                  
            if ($find){
             echo "false";
            } 
            else{  
              echo "true";
            }
            exit;
        }
        else{       
            header ("location: $base_path");
        }
    }

    function new_password(){
       
    }
    
    function check_if_username_or_email_exists($param){
        $user_obj = new User();
        if ($param != ""){
            echo $user_obj->check_if_username_or_email_exists($param);   
        }
        
        exit; 
    }
    
    function forget_password($param) {
        $db_functions_obj = new DbFunctions(); 
        $user_obj = new User();
               
        $converter = new Encryption;

        $decoded = $converter->decode($param);
        $email = explode(CHANGE_PW_SEPARATOR, $decoded);
                       
        $link_date = $email[1];
        
        $exp_date = strtotime(FORGET_PW_EXP_DATE, $email[1]);
         
        $type = 'email';
       
        if($email[0] != "" && $link_date < $exp_date){
            $this->content = $user_obj->build_change_forget_password_form($email[0], 'email');
        }
        else{
            $this->page_not_found();
        }

    }
       
    function validate_captcha(){
        $helper_obj = new Helper();
                      
        $url_request = explode("?", $_SERVER['REQUEST_URI']);

        $captcha = explode("=", $url_request[1]); 
        $valid = $helper_obj->captcha($captcha[1]); 
        
        if (!$valid){
             echo "false";
         } 
         else{  
             echo "true";
         }
         exit;
    }   
    
    function clients(){
        
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){  
            global $user, $base_path, $menu_active;
            $client_obj = new Client();   
        //    $validation_js_obj = new Validation_js();   
            
            $menu_active['add_content'] = 'active'; 
                         
           // $this->content = $this->menu;
            $this->content = '<!-- Validation -->
                               <script src="' . $base_path . 'js/plugins/validation/jquery.validate.min.js"></script>
                               <script src="' . $base_path . 'js/plugins/validation/additional-methods.min.js"></script>';
          //  $this->content .= $validation_js_obj->add_client_validation();
            $this->content .= $client_obj->build_add_client_form();
            $this->content .= $client_obj->get_all_clients();
        }
        else{
           $this->set_access_denied();
        }
        
    }   
    
    function add_client(){
       $helper_obj = new Helper();
       $db_functions_obj = new DbFunctions();
                 
       global $user;
       $added_by = $user['id'];
                      
       $rid = $user['rid'];
         
        //only super admin can delete client 
       if (SUPER_ADMIN_ROLE_ID == $rid && isset($_POST) && $_POST['client_name'] != "") {     
           $logo_name = $helper_obj->generate_name();
           $client_obj = new Client();
           
           $desc = LOGO_PATH_UPLOAD . $logo_name . '.png';
           
           move_uploaded_file(@$_FILES["client_logo"]["tmp_name"], $desc); 
           
           $cid = $db_functions_obj->add_client($_POST['client_name'], $logo_name . '.png', $added_by);
                    
           $client_info = $db_functions_obj->get_client_by_id($cid);
                     
           $output = $client_obj->build_client_row($client_info);
           
           echo($output);         
       }
       else{
           $this->set_access_denied();
        }
       
       exit;
    }

    function deactive_active_client($cid) {
        global $user;
        $helper_obj = new Helper();  
        
        $uid = $user['id'];
        
        $rid = $user['rid'];
         
        //only super admin can delete client 
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)) {
            $db_functions_obj = new DbFunctions();   
            $db_functions_obj->deactive_active_client($cid, $uid);
            exit;
       }
       else{
           $this->set_access_denied(1);
       }
    }
    
    function deactive_active_user($uid) {
        global $user;
        $uid = $user['id'];
        $rid = $user['rid'];
        $helper_obj = new Helper();  
         
        //only super admin can delete client 
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)) {
            $db_functions_obj = new DbFunctions();   
            $db_functions_obj->deactive_active_user($uid);
            exit;
       }
       else{
           $this->set_access_denied(1);
       }
    }
    
    function open_edit_client_popup($cid) {
        $client_obj = new Client();
        echo $client_obj->build_edit_client_popup($cid);
        exit;
    }
    
    function open_edit_user_popup($uid) {
        $user_obj = new User();   
        echo $user_obj->build_edit_user_popup($uid);
        exit;
    }
    
    function check_client_logo_size(){
        $client_obj = new Client();
      
        $url_request = explode("?", $_SERVER['REQUEST_URI']);
                      
        if (trim(@$url_request[1]) != '') {  
             $logo = explode("=", $url_request[1]); 
                             
             $find = $client_obj->check_client_logo_size($logo[1]);
             
             if (!$find){
                 echo "false";
             } 
             else{  
                 echo "true";
             }
             exit;
        }   
    }
    
    function edit_client(){
        $client_obj = new Client();
        $db_functions_obj = new DbFunctions();
        $helper_obj = new Helper();
        global $user;
        
        $rid = $user['rid'];
         
        //only super admin can delete client 
         if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){ 
        
            if ( isset($_FILES["client_logo_update"]["tmp_name"]) ) {
                $logo_name = $helper_obj->generate_name();
               
                $desc = LOGO_PATH_UPLOAD . $logo_name . '.png';
               
                move_uploaded_file($_FILES["client_logo_update"]["tmp_name"], $desc); 
                
                $new_logo = $logo_name . '.png';
            }
            else{
                $new_logo = "";
            }
                                                                                 
            $client_obj->edit_client($_POST['client_id_updated'], $_POST['client_name_update'], $new_logo);
            $client_info = $db_functions_obj->get_client_by_id($_POST['client_id_updated']);
            echo $client_obj->build_client_row($client_info) . "***#***" . $_POST['client_id_updated'];   
       }
       else{
           $this->set_access_denied(1);
       }
       
       exit;
    }
    
    function edit_user() {
        $user_obj = new User();
        $db_functions_obj = new DbFunctions();
        $helper_obj = new Helper();
        global $user;
        
        $rid = $user['rid'];
        
        $user_info = $db_functions_obj->get_user_detail_by_id($_POST['user_id_updated']);
        
        //super admin and users for loggined client        
        if (SUPER_ADMIN_ROLE_ID == $rid || $user_info->rid == $rid) {
        
            if ( $_POST['user_name_update'] != "" && $_POST['email_update'] != "" && $_POST['phone_update'] != "") {                                                                        
                $user_obj->edit_user($_POST['user_id_updated'], $_POST['user_name_update'], $_POST['email_update'], $_POST['phone_update'], $_POST['password_update']);
                $user_info = $db_functions_obj->get_user_detail_by_id($_POST['user_id_updated']);//call again to get updated row
                echo $user_obj->build_user_row($user_info) . "***#***" . $_POST['user_id_updated'];
                exit;
            }
       }
       else{
           $this->set_access_denied(1);
       }
    }
    
    function users(){
        global $user, $menu_active, $base_path;
        $user_obj = new User();   
     //   $validation_js_obj = new Validation_js();
        $helper_obj = new Helper();
        
        $menu_active['add_content'] = 'active'; 
        
        $rid = $user['rid'];
        
        $cid = $user['cid'];
        //$this->content = $this->menu; 
         
        //only super admin can delete client 
        if ($helper_obj->user_is_logged_in() /*&& $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)*/){                   
           //$this->content .= $validation_js_obj->add_user_validation();
           $this->content = '<!-- Validation -->
                           <script src="' . $base_path . 'js/plugins/validation/jquery.validate.min.js"></script>
                           <script src="' . $base_path . 'js/plugins/validation/additional-methods.min.js"></script>';
            $this->content .= $user_obj->build_add_user_form($helper_obj->check_role(SUPER_ADMIN_ROLE_ID));
            $this->content .= $user_obj->get_all_users($cid);
        }
        else{
           $this->set_access_denied();
        }
    }
    
    function category()  {
        
        $helper_obj = new Helper();
        $db_functions_obj = new DbFunctions();
        
        global $base_path,$user; 
                         
        $cid = $user['cid'];
        
        //pr($cid);
        
        $menu_info = $db_functions_obj->get_menu_by_url($this->action);
        //pr($menu_info);
        $menu_id = $menu_info->id;
        
        if(!$helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){
            
            if ($helper_obj->user_is_logged_in() &&  $helper_obj->check_user_menu($menu_id, $cid)){
                       echo "category";
            }
            
            else{
                $this->set_access_denied();    
            }
        }
    
    }
    
    function get_operators_by_country($country_id){
        $user_obj = new User();
        echo $user_obj->build_operators_list($country_id);
        exit;
    }
    
    function add_user() {
        global $user;
        $user_obj = new User(); 
        $db_function_obj = new DbFunctions();   
        $helper_obj = new Helper(); 
        
        $uid = $user['id'];
        $rid = $user['rid'];
                 
        //only super admin can delete client 
       if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID) && isset($_POST) && $_POST['username'] != "" && $_POST['password'] && 
           $_POST['email'] && $_POST['phone'] && $_POST['add_role']) {
           $last_uid = $user_obj->add_user($_POST);
           $user_info = $db_function_obj->get_user_detail_by_id($last_uid);
                     
           $output = $user_obj->build_user_row($user_info);
           
           echo($output); 
           
           exit;
       }
    }
    
    function menu($param){
        $menu_obj = new Menu();
        $helper_obj = new Helper();
       // $validation_js_obj = new Validation_js();
        global $base_path, $menu_active;
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)) {  
           // $this->content = $this->menu;
             $menu_active['add_content'] = 'active'; 
                           
            /*$this->content .= "<div style='width: 330px;margin: auto'>" . "<a href='$base_path" . 'menu/1' . "'>" . $helper_obj->t("Add item to menu") . "</a> | " . 
                                                                          "<a href='$base_path" . 'menu/2' . "'>" . $helper_obj->t("Add item menu to client") . "</a></div>";
                */                                                          
            if ( ($param == 2) ) {
               // $this->content .= $validation_js_obj->add_item_to_menu_validation();
               $this->content = '<!-- Validation -->
                           <script src="' . $base_path . 'js/plugins/validation/jquery.validate.min.js"></script>
                           <script src="' . $base_path . 'js/plugins/validation/additional-methods.min.js"></script>';
                           
                $this->content .= $menu_obj->build_add_item_to_menu_form();
                $this->content .= "<div id='all_menu'>" . $this->build_all_menu_with_childs() . '</div>';
            }
            else if ( ($param == 1) ){     
                $client_obj = new Client();  
                
                $this->content = '<div class="box box-bordered">';
                $this->content .= '<div class="box-title">
                                <h3><i class="icon-th-list"></i> Select a client</h3>
                            </div>';
                               
                $this->content .= '<form class="form-horizontal form-validate form-vertical form-bordered">' . 
                                      $client_obj->build_clients_list("add","", "onchange='getMenuByClient(this)'") . 
                                  '</form><br /><br />';
                $this->content .= "<div class='client-menu'></div>";
            }
        }
        else{
            $this->set_access_denied();
        }
    }
    
    function build_all_menu_with_childs(){
       $menu_obj = new Menu();
       return $menu_obj->build_all_menu_with_childs();
    }
    
    function add_item_to_menu(){
        $helper_obj = new Helper();
                             
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)) {  
            $menu_obj = new Menu(); 
            $menu_obj->add_item_to_menu($_POST);
        }
        exit; 
    }
    
    function get_menu_by_client($cid){
        $menu_obj = new Menu();
        
        $client_no_menu = $menu_obj->get_menu_not_assigned_to_client($cid);
        $client_menu = $menu_obj->get_menu_by_client($cid);
        
        echo $client_no_menu . "<div style='clear: both'></div><br /><hr /><br />" . $client_menu;
        exit;
    }
    
    function get_categories_by_client($cid){
        $cat_obj = new Category();
        
        $client_no_cats = $cat_obj->get_categories_not_assigned_to_client($cid);
        $client_cats = $cat_obj->get_categories_assigned_to_client($cid);
        
        echo $client_no_cats . "<div style='clear: both'></div><br /><hr /><br />" . $client_cats;
        exit;
    }
    
    function add_menu_item_to_client($params) {
        $params = explode("/", $params);
        
        $cid = $params[0];
        $menu_id = $params[1];
        
        $db_function_obj = new DbFunctions();
        
        $valid = $db_function_obj->check_if_client_has_menu_item($menu_id, $cid);
        
        //client has no menu item
        if ($valid == 0) {  
            $db_function_obj->add_menu_item_to_client($cid, $menu_id);
        }
        
        $this->get_menu_by_client($cid);
    }
    
    function remove_menu_item_from_client($params){
        $params = explode("/", $params);
        
        $cid = $params[0];    //read it from menu
        $menu_id = $params[1];
        
        global $user;
        //$cid = $user['cid'];
        
        $db_function_obj = new DbFunctions();
        $db_function_obj->remove_menu_item_from_client($cid, $menu_id);
        
        $this->get_menu_by_client($cid);
    }
    
    function video(){      
        $helper_obj = new Helper();
        global $user, $base_path;
        ///$this->content = $this->menu;
                                   
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(CLIENT_ROLE_ID)){         
               $vid_obj = new Video();
              // $validation_js_obj = new Validation_js();
                     //    pr($_POST);
               //$this->content .= $validation_js_obj->add_video_validation(); 
               $this->content = '<!-- Validation -->
                                 <script src="' . $base_path . 'js/plugins/validation/jquery.validate.min.js"></script>
                                 <script src="' . $base_path . 'js/plugins/validation/additional-methods.min.js"></script>';
                                
               $this->content .= $vid_obj->build_add_video_form();
               $this->content .= $vid_obj->get_all_videos($user['cid']);
        } 
    } 
    
    function article_html(){      
        $helper_obj = new Helper();
        global $user, $base_path;
        ///$this->content = $this->menu;
                                   
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(CLIENT_ROLE_ID)){         
               $article_obj = new Article();
              // $validation_js_obj = new Validation_js();
                     //    pr($_POST);
               //$this->content .= $validation_js_obj->add_video_validation(); 
               $this->content = '<!-- Validation -->
                                 <script src="' . $base_path . 'js/plugins/validation/jquery.validate.min.js"></script>
                                 <script src="' . $base_path . 'js/plugins/validation/additional-methods.min.js"></script>';
                                
               $this->content .= $article_obj->build_add_article_html_form();
               $this->content .= $article_obj->get_all_articles_html($user['cid']);
        } 
        else{
            $this->content = $this->access_denied();
        }
    }
    
    function article(){      
        $helper_obj = new Helper();
        global $user, $base_path;
        ///$this->content = $this->menu;
                                   
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(CLIENT_ROLE_ID)){         
               $article_obj = new Article();
              // $validation_js_obj = new Validation_js();
                     //    pr($_POST);
               //$this->content .= $validation_js_obj->add_video_validation(); 
               $this->content = '<!-- Validation -->
                                 <script src="' . $base_path . 'js/plugins/validation/jquery.validate.min.js"></script>
                                 <script src="' . $base_path . 'js/plugins/validation/additional-methods.min.js"></script>';
                                
               $this->content .= $article_obj->build_add_article_form();
               $this->content .= $article_obj->get_all_articles($user['cid']);
        } 
    }
    
    function add_video(){
        $vid_obj = new Video();
        $helper_obj = new Helper();
                            //pr($_POST);
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(CLIENT_ROLE_ID)){
            $last_id = $vid_obj->add_video($_POST);  
            echo($vid_obj->build_video_row($last_id));
        }
        
        exit;
    }   
    
    function add_article(){
        $article_obj = new Article();
        $helper_obj = new Helper();
                            //pr($_POST);
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(CLIENT_ROLE_ID)){
            $last_id = $article_obj->add_article($_POST);  
            echo($article_obj->build_article_row($last_id));
        }
        
        exit;
    }  
    
    function add_article_html(){
        $article_obj = new Article();
        $helper_obj = new Helper();
                                
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(CLIENT_ROLE_ID)){
            $last_id = $article_obj->add_article_html($_POST);  
            echo($article_obj->build_article_html_row($last_id));
        }
        
        exit;
    } 
    
    function delete_video($param) {
        $db_functions_obj = new DbFunctions();
        global $user;
        
        $cid = $user['cid'];
        
        $db_functions_obj->delete_video($param, $cid);
        
        exit;
    } 
    
    function delete_article($param) {
        $db_functions_obj = new DbFunctions();
        global $user;
        
        $cid = $user['cid'];
        
        $db_functions_obj->delete_article($param, $cid);
        
        exit;
    }
    
    function delete_html_article($param) {
        $db_functions_obj = new DbFunctions();
        global $user;
        
        $cid = $user['cid'];
        
        $db_functions_obj->delete_html_article($param, $cid);
        
        exit;
    }
    
    function open_edit_video_popup($param) {
        $helper_obj = new Helper();
                            
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(CLIENT_ROLE_ID)){
            $vid_obj = new Video();
            echo $vid_obj->build_edit_video_popup($param);
        }
        
        exit;
    } 
    
    function open_edit_article_popup($param) {
        $helper_obj = new Helper();
                            
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(CLIENT_ROLE_ID)){
            $article_obj = new Article();
            echo $article_obj->build_edit_article_popup($param);
        }
        
        exit;
    }
    
    function open_edit_html_article_popup($param) {
        $helper_obj = new Helper();
                            
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(CLIENT_ROLE_ID)){
            $article_obj = new Article();            
            echo $article_obj->build_edit_html_article_popup($param);
        }
        
        exit;
    }
    
    function open_edit_pgrate_popup($param) {
        $helper_obj = new Helper();
                            
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){
            $pgrate_obj = new Pgrate();
            echo $pgrate_obj->build_edit_pgrate_popup($param);   
        }
        
        exit;
    }
    
    function edit_video() {
        $helper_obj = new Helper();
                            
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(CLIENT_ROLE_ID)){
            $db_functions_obj = new DbFunctions();
            $vid_obj = new Video();
            global $user;
            
            $uid = $user['id'];
            $cid = $user['cid'];
            
            if ($_POST['title_updated'] && $_POST['description_updated']) {           //pr($_POST);
                $db_functions_obj->edit_video($_POST, $cid, $uid);
                echo($vid_obj->build_video_row($_POST['edit_vid'])) . "***#***" . $_POST['edit_vid']; 
            }
        }
        
        exit;
    }
    
    function edit_article() {
        $helper_obj = new Helper();
                            
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(CLIENT_ROLE_ID)){
            $db_functions_obj = new DbFunctions();
            $article_obj = new Article();
            global $user;
            
            $uid = $user['id'];
            $cid = $user['cid'];
            
            if ($_POST['title_updated'] && $_POST['text1_updated']) {           //pr($_POST);
                $db_functions_obj->edit_article($_POST, $cid, $uid);
                echo($article_obj->build_article_row($_POST['edit_article'])) . "***#***" . $_POST['edit_article']; 
            }
        }
        
        exit;
    } 
    
    function edit_html_article() {
        $helper_obj = new Helper();
                            
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(CLIENT_ROLE_ID)){
            $db_functions_obj = new DbFunctions();
            $article_obj = new Article();
            global $user;
                      
            $uid = $user['id'];
            $cid = $user['cid'];
                                     
            if ($_POST['title_updated'] && $_POST['ck_updated']) {         //pr($_POST);
                $db_functions_obj->edit_html_article($_POST, $cid, $uid);
                echo($article_obj->build_article_html_row($_POST['edit_article'])) . "***#***" . $_POST['edit_article']; 
            }
        }
        
        exit;
    }
    
    function pgrate(){
        $helper_obj = new Helper();
        global $base_path; 
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){
            $pgrate_obj = new pgrate();
            $this->content = '<!-- Validation -->
                                 <script src="' . $base_path . 'js/plugins/validation/jquery.validate.min.js"></script>
                                 <script src="' . $base_path . 'js/plugins/validation/additional-methods.min.js"></script> ';
            $this->content .= $pgrate_obj->build_add_pgrate_form();
            $this->content .= $pgrate_obj->get_pgrates();
        }
    }
    
    function delete_pgrate($param) {
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){  
             $db_functions_obj = new DbFunctions(); 
             $db_functions_obj->delete_pgrate($param);
             exit;
        }
    }
    
    function add_pgrate(){
        $db_functions_obj = new DbFunctions();
        $pgrate_obj = new pgrate(); ;
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){ 
            $last_id = $db_functions_obj->add_pgrate($_POST);     
            echo($pgrate_obj->build_pgrate_row($last_id));   
        }
        exit;
    }
    
    function edit_pgrate(){
         $helper_obj = new Helper();
                            
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){
            $db_functions_obj = new DbFunctions();
            $pgrate_obj = new Pgrate();
            
            $name = trim($_POST['pgrate_name_update']);
            $id = trim($_POST['pgrate_id_updated']);
            
            if (!empty($name)) {
                $db_functions_obj->edit_pgrate($name, $id);
                
                echo($pgrate_obj->build_pgrate_row($id)) . "***#***" . $id;
            }
        }
        
        exit;
    }
    
    function categories(){
        $helper_obj = new Helper();
        global $base_path; 
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){
            $cat_obj = new Category();
            $this->content = '<!-- Validation -->
                                 <script src="' . $base_path . 'js/plugins/validation/jquery.validate.min.js"></script>
                                 <script src="' . $base_path . 'js/plugins/validation/additional-methods.min.js"></script>';
            $this->content .= $cat_obj->build_add_category_form();
            $this->content .= $cat_obj->get_categories();
        }
        else{
            $this->page_not_found();
        }
    }
    
    function sources(){
        $helper_obj = new Helper();
        global $base_path; 
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){
            $cat_obj = new Category();
            $this->content = '<!-- Validation -->
                                 <script src="' . $base_path . 'js/plugins/validation/jquery.validate.min.js"></script>
                                 <script src="' . $base_path . 'js/plugins/validation/additional-methods.min.js"></script>';
            $this->content .= $cat_obj->build_add_source_form();
            $this->content .= $cat_obj->get_sources();
        }
        else{
            $this->page_not_found();
        }
    }
    
    function add_category() {
        $helper_obj = new Helper();
        global $base_path; 
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)) {
            $db_functions_obj = new DbFunctions();
            $category_obj = new Category();
                            //  pr($_FILES);exit;
            $last_id = $db_functions_obj->add_category($_POST);     
            echo($category_obj->build_category_row($last_id)); 
        }
        
        exit;
    } 
    
    function add_source() {
        $helper_obj = new Helper();
        global $base_path; 
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)) {
            $db_functions_obj = new DbFunctions();
            $category_obj = new Category();
                            //  pr($_FILES);exit;
            $last_id = $db_functions_obj->add_source($_POST);     
            echo($category_obj->build_source_row($last_id)); 
        }
        
        exit;
    }
    
    // happened in this day
    
    function hitd(){
        $helper_obj = new Helper();
        global $base_path; 
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){
            $hitd_obj = new HappenedInThisDay();
            $this->content = '<!-- Validation -->
                                 <script src="' . $base_path . 'js/plugins/validation/jquery.validate.min.js"></script>
                                 <script src="' . $base_path . 'js/plugins/validation/additional-methods.min.js"></script>                                 
                                 <script src="' . $base_path . 'js/plugins/datepicker/bootstrap-datepicker.js"></script>';

            $this->content .= $hitd_obj->build_add_hitd_form();
            $this->content .= $hitd_obj->get_hitd();
        }
    }
     
    function add_hitd() {
        $helper_obj = new Helper();
        global $base_path; 
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)) {
            $db_functions_obj = new DbFunctions();
            $hitd_obj = new HappenedInThisDay();

            $last_id = $db_functions_obj->add_hitd($_POST);     
            echo($hitd_obj->build_hitd_row($last_id)); 
        }
        
        exit;
    }
                                      
    function open_edit_hitd_popup($param) {
        $helper_obj = new Helper();
                            
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){
            $hitd_obj = new HappenedInThisDay();
            echo $hitd_obj->build_edit_hitd_popup($param);   
        }
        
        exit;
    }
    
    function delete_hitd($param) {
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){ 
             $db_function_obj = new DbFunctions();
             $db_function_obj->delete_hitd($param);
        }
        
        exit;
    }
    
    function edit_hitd(){
         $hitd_obj = new HappenedInThisDay();
         $db_functions_obj = new DbFunctions();
         $helper_obj = new Helper();
         global $user;
        
         $rid = $user['rid'];
         
         //only super admin can delete hidt 
         if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){  
             
            $db_functions_obj->edit_hitd($_POST['hitd_id'],$_POST['hitd_textarea'], $_POST['hitd_textfield']);
            $hitd_info = $db_functions_obj->get_hitd_by_id($_POST['hitd_id']);
            echo $hitd_obj->build_hitd_row($_POST['hitd_id']) . "***#***" . $_POST['hitd_id'];   
       }
       else{
           $this->set_access_denied(1);
       }
       
       exit;
    }
    
    //end
    //emergency calls
    
    function emergency(){
        $helper_obj = new Helper();
        global $base_path; 
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){
            $emergency_obj = new EmergencyCalls();
            $this->content = '<!-- Validation -->
                                 <script src="' . $base_path . 'js/plugins/validation/jquery.validate.min.js"></script>
                                 <script src="' . $base_path . 'js/plugins/validation/additional-methods.min.js"></script>                                 
                                 <script src="' . $base_path . 'js/plugins/datepicker/bootstrap-datepicker.js"></script>';

            $this->content .= $emergency_obj->build_add_emergency_form();
            $this->content .= $emergency_obj->get_emergency();
        }
    }
     
    function add_emergency() {
        $helper_obj = new Helper();
        global $base_path; 
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)) {
            $db_functions_obj = new DbFunctions();
            $emergency_obj = new EmergencyCalls();

            $last_id = $db_functions_obj->add_emergency($_POST);     
            echo($emergency_obj->build_emergency_row($last_id)); 
        }
        
        exit;
    }
                                      
    function open_edit_emergency_popup($param) {
        $helper_obj = new Helper();
                            
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){
            $emergency_obj = new EmergencyCalls();
            echo $emergency_obj->build_edit_emergency_popup($param);   
        }
        
        exit;
    }
    
    function delete_emergency($param) {
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){ 
             $db_function_obj = new DbFunctions();
             $db_function_obj->delete_emergency($param);
        }
        
        exit;
    }
    
    function edit_emergency(){
         $emergency_obj = new EmergencyCalls();
         $db_functions_obj = new DbFunctions();
         $helper_obj = new Helper();
         global $user;
        
         $rid = $user['rid'];
         
         //only super admin can delete hidt 
         if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){  
             
            $db_functions_obj->edit_emergency($_POST['emergency_id'],$_POST['name'], $_POST['phone'], $_POST['emergency_country']);
            $emergency_info = $db_functions_obj->get_emergency_by_id($_POST['emergency_id']);
            echo $emergency_obj->build_emergency_row($_POST['emergency_id']) . "***#***" . $_POST['emergency_id'];   
       }
       else{
           $this->set_access_denied(1);
       }
       
       exit;
    }
    
    //end 
    //zodiac
    
    function zodiac(){
        $helper_obj = new Helper();
        global $base_path; 
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){
            $zodiac_obj = new Zodiac();
            $this->content = '<!-- Validation -->
                                 <script src="' . $base_path . 'js/plugins/validation/jquery.validate.min.js"></script>
                                 <script src="' . $base_path . 'js/plugins/validation/additional-methods.min.js"></script>                                 
                                 <script src="' . $base_path . 'js/plugins/datepicker/bootstrap-datepicker.js"></script>';

            $this->content .= $zodiac_obj->build_add_zodiac_form();
            $this->content .= $zodiac_obj->get_zodiac();
        }
    }
     
    function add_zodiac() {
        $helper_obj = new Helper();
        global $base_path; 
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)) {
            $db_functions_obj = new DbFunctions();
            $zodiac_obj = new Zodiac();

            $last_id = $db_functions_obj->add_zodiac($_POST);     
            echo($zodiac_obj->get_zodiac($last_id)); 
        }
        
        exit;
    }
                                      
    function open_edit_zodiac_popup($param) {
        $helper_obj = new Helper();
                            
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){
            $zodiac_obj = new Zodiac();
            echo $zodiac_obj->build_edit_zodiac_popup($param);   
        }
        
        exit;
    }
    
    function delete_zodiac($param) {
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){ 
             $db_function_obj = new DbFunctions();
             $db_function_obj->delete_zodiac($param);
        }
        
        exit;
    }
    
    function edit_zodiac(){
         $zodiac_obj = new Zodiac();
         $db_functions_obj = new DbFunctions();
         $helper_obj = new Helper();
         global $user;
        
         $rid = $user['rid'];
         
         //only super admin can delete hidt 
         if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){  
             
            $db_functions_obj->edit_zodiac($_POST['zodiac_id'], $_POST['body']);
            $zodiac_info = $db_functions_obj->get_zodiac_by_id($_POST['zodiac_id']);
            echo $zodiac_obj->build_zodiac_row($_POST['zodiac_id']) . "***#***" . $_POST['zodiac_id'];   
       }
       else{
           $this->set_access_denied(1);
       }
       
       exit;
    }
    
    //end
    
    function delete_category($param) {
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){ 
             $db_function_obj = new DbFunctions();
             $db_function_obj->delete_category($param);
        }
        
        exit;
    }
    
    function delete_source($param) {
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){ 
             $db_function_obj = new DbFunctions();
             $db_function_obj->delete_source($param);
        }
        
        exit;
    }
    
    function delete_tag($param) {
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){ 
             $db_function_obj = new DbFunctions();
             $db_function_obj->delete_tag($param);
             $db_function_obj->delete_all_article_belongs_to_tag($param);
        }
        
        exit;
    }
    
    function open_edit_category_popup($param) {
        $helper_obj = new Helper();
                            
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){
            $category_obj = new Category();
            echo $category_obj->build_edit_category_popup($param);   
        }
        
        exit;
    }  
    
    function open_edit_tag_popup($param) {
        $helper_obj = new Helper();
                            
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){
            $tag_obj = new Tag();
            echo $tag_obj->build_edit_tag_popup($param);   
        }
        
        exit;
    }
     
    function edit_category(){    //pr($_FILES);  exit;
         $category_obj = new Category();
         $db_functions_obj = new DbFunctions();
         $helper_obj = new Helper();
         global $user;
                        
         $rid = $user['rid'];
         
         //only super admin can delete client 
         if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){ 
                    
             $new_image1 = "";
             
             if ( isset($_FILES["image1_update"]["tmp_name"]) ) {
               // $new_image1 = $helper_obj->generate_name();
               
               // $desc = CATEGORIES_IMAGES_PATH_UPLOAD . $new_image1 . '.png';
               
                //move_uploaded_file($_FILES["image1_update"]["tmp_name"], $desc);
                
                //$helper_obj->resize_crob_image($desc, $desc, CATEGORY_IMAGE_W, CATEGORY_IMAGE_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
                  
               // $new_image1 = $new_image1 . '.png';
                
               // $gs_name = $_FILES["image1_update"]["tmp_name"];
              //  $move = copy($gs_name, 'gs://' . GOOGLE_APP_ID . '/cat/'.$new_image1.'');
             //   $object_image_file1 = 'gs://' . GOOGLE_APP_ID . '/cat/'.$new_image1.'';
              //  $object_image_url1 = CloudStorageTools::getImageServingUrl($object_image_file1, ['size' => 200, 'crop' => false]);
            }
            else{
              //  $new_image1 = "";
            }
            
           /* if ( isset($_FILES["image2_update"]["tmp_name"]) ) {
                $new_image2 = $helper_obj->generate_name();
               
                $desc = CATEGORIES_IMAGES_PATH_UPLOAD . $new_image2 . '.png';
               
                move_uploaded_file($_FILES["image2_update"]["tmp_name"], $desc); 
                
                $helper_obj->resize_crob_image($desc, $desc, CATEGORY_IMAGE_W, CATEGORY_IMAGE_H, THUMBNAIL_R, THUMBNAIL_ENABLE); 
                
                $new_image2 = $new_image2 . '.png';
            }
            else{
                $new_image2 = "";
            } */
            $new_image2 = "";
            
            $premium = isset($_POST['premium_update']) ? $_POST['premium_update'] : 0;
            
            $name = trim($_POST['category_name_update']);
            
            if (!empty($name)) {                                                                     
                $db_functions_obj->edit_category($_POST['category_id_updated'], $_POST['category_name_update'], $new_image1, $new_image2, $premium, $_POST['add_parent_category']);
               // $db_functions_obj->edit_category($_POST['category_id_updated'], $_POST['category_name_update'], $new_image1, $new_image2, $premium);
                //$category_info = $db_functions_obj->get_category_by_id($_POST['category_id_updated']);
                echo $category_obj->build_category_row($_POST['category_id_updated']) . "***#***" . $_POST['category_id_updated']; 
            }  
       }
       else{
           $this->set_access_denied(1);
       }
       
       exit;
    }
    
    function edit_tag(){
         $tag_obj = new Tag();
         $db_functions_obj = new DbFunctions();
         $helper_obj = new Helper();
         global $user;
        
         $rid = $user['rid'];
         
         //only super admin can delete client 
         if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){ 
            
             $name = trim($_POST['tag_name_update']);
             
            if (!empty($name)) {                
               
                $image1_type = explode(".", $_FILES['image_update']['name']);
                $image1 = $helper_obj->generate_name() . '.' . $image1_type[1];  
         
                move_uploaded_file($_FILES["image_update"]["tmp_name"], TAGS_THUMBNAIL_IMAGES_PATH_UPLOAD . $image1);     
       
                //200x200  
                $helper_obj->resize_crob_image(TAGS_THUMBNAIL_IMAGES_PATH_UPLOAD . $image1, TAGS_THUMBNAIL_IMAGES_PATH_UPLOAD . $image1, TAG_THUMBNAIL_IMAGE_W, TAG_THUMBNAIL_IMAGE_H, 
                                               THUMBNAIL_R, THUMBNAIL_ENABLE); 
                                               
                //400x200
                $helper_obj->resize_crob_image(TAGS_THUMBNAIL_IMAGES_PATH_UPLOAD . $image1, TAGS_IMAGES_PATH_UPLOAD . $image1, TAG_IMAGE_W, TAG_IMAGE_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
       
                                                          
                $db_functions_obj->edit_tag($_POST['tag_id_updated'], $_POST['tag_name_update'], $image1);
                //$tag_info = $db_functions_obj->get_category_by_id($_POST['tag_id_updated']);
                echo $tag_obj->build_tag_row($_POST['tag_id_updated']) . "***#***" . $_POST['tag_id_updated'];   
            }
       }
       else{
           $this->set_access_denied(1);
       }
       
       exit;
    }
    
    function currencies(){
        
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){  
            global $user, $base_path, $menu_active;
            
            $currency_obj = new Currency();    
            
            $menu_active['add_content'] = 'active'; 
                         
         
            $this->content = '<!-- Validation -->
                               <script src="' . $base_path . 'js/plugins/validation/jquery.validate.min.js"></script>
                               <script src="' . $base_path . 'js/plugins/validation/additional-methods.min.js"></script>';
          
            $this->content .= $currency_obj->build_add_currency_form();
            $this->content .= $currency_obj->get_all_currencies();
        }
        else{
           $this->set_access_denied();
        }
        
    }
    
    function open_edit_currency_popup($id) {
        $currency_obj = new Currency();
        echo $currency_obj->build_edit_currency_popup($id);
        exit;
    }
    
    function delete_currency($param) {
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){  
             $db_functions_obj = new DbFunctions(); 
             $db_functions_obj->delete_currency($param);
             exit;
        }
    }
    
    function edit_currency(){
         $helper_obj = new Helper();
                            
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){
            $db_functions_obj = new DbFunctions();
            $currency_obj = new Currency();
            
            echo"edit currency controller";
            
            $name = trim($_POST['currency_name_update']);
            $code = trim($_POST['currency_code_update']);
            $sign = trim($_POST['currency_sign_update']);
            $id = trim($_POST['currency_id_update']);
            
            if (!empty($name)&& !empty($code) ) {
                $db_functions_obj->edit_currency($id, $code, $name, $sign);
                
                echo($currency_obj->build_currency_row($id)) . "***#***" . $id;
            }
        }
        
        exit;
    }
    
    function add_currency(){
        $db_functions_obj = new DbFunctions();
        $currency_obj = new Currency(); ;
        $helper_obj = new Helper();
        
        $name = trim($_POST['currency_name']);
        $code = trim($_POST['currency_code']);
        $sign = trim($_POST['currency_sign']);
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){ 
            $last_id = $db_functions_obj->add_currency($code, $name, $sign);     
            echo($currency_obj->build_currency_row($last_id));   
        }
        exit;
    }
    
    
    function operators(){
        
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){  
            global $user, $base_path, $menu_active;
            
            $operator_obj = new Operator();    
            
            $menu_active['add_content'] = 'active'; 
                         
         
            $this->content = '<!-- Validation -->
                               <script src="' . $base_path . 'js/plugins/validation/jquery.validate.min.js"></script>
                               <script src="' . $base_path . 'js/plugins/validation/additional-methods.min.js"></script>';
          
            $this->content .= $operator_obj->build_add_operator_form();
            $this->content .= $operator_obj->get_all_operators();
        }
        else{
           $this->set_access_denied();
        }
        
    }
    
    function add_operator(){
        $db_functions_obj = new DbFunctions();
        $operator_obj = new Operator(); ;
        $helper_obj = new Helper();
        
        $operator_name = trim($_POST['operator_name']);
        $country_id = trim($_POST['add_country']);
        
        $paid_sc = trim($_POST['paid_sc']);
        $free_sc = trim($_POST['free_sc']);
        $type = trim($_POST['type']);
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){ 
            $last_id = $db_functions_obj->add_operator($operator_name, $country_id, $paid_sc, $free_sc, $type);     
            echo($operator_obj->build_operator_row($last_id));   
        }
        exit;
    }
    
    function open_edit_operator_popup($id) {
        $operator_obj = new Operator();
        echo $operator_obj->open_edit_operator_popup($id);
        exit;
    }
    
    function delete_operator($param) {
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){  
             $db_functions_obj = new DbFunctions(); 
             $db_functions_obj->delete_operator($param);
             exit;
        }
    }
    
    function edit_operator(){
         $helper_obj = new Helper();
                            
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){
            $db_functions_obj = new DbFunctions();
            
            $operator_obj = new Operator();    
            
            $operator_name = trim($_POST['operator_name_updated']);
            $operator_id = trim($_POST['operator_id_updated']);
            
            $paid_sc = trim($_POST['paid_sc_updated']);
            $free_sc = trim($_POST['free_sc_updated']);
            $type = trim($_POST['type_updated']);
            //$country_id = trim($_POST['country_id']);
           
            
            if (!empty($operator_name) ) {
                $db_functions_obj->edit_operator($operator_id, $operator_name, $paid_sc, $free_sc, $type);
                
                echo($operator_obj->build_operator_row($operator_id)) . "***#***" . $operator_id;
            }
        }
        
        exit;
    }
    
    function rss_source(){
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){  
            global $user, $base_path, $menu_active;
            
            $rss_obj = new RSSRource();    
            
            $menu_active['add_content'] = 'active'; 

            $this->content = '<!-- Validation -->
                               <script src="' . $base_path . 'js/plugins/validation/jquery.validate.min.js"></script>
                               <script src="' . $base_path . 'js/plugins/validation/additional-methods.min.js"></script>';
          
            $this->content .= $rss_obj->build_add_rss_source_form();
            $this->content .= $rss_obj->get_all_rss_sources();
        }
        else{
           $this->set_access_denied();
        }   
    }
    
    function add_rss_source(){
        $helper_obj = new Helper();
        $rss_obj = new RSSRource();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){ 
            $db_functions_obj = new DbFunctions();
            
            $id = $db_functions_obj->add_rss_source($_POST);
            
            echo $rss_obj->build_rss_source_row($id);
        }
        
        exit;  
    }
    
    function delete_rss_source($id){
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){ 
            $db_functions_obj = new DbFunctions();
            
            $db_functions_obj->delete_rss_source($id);  
        } 
        
        exit;  
    }
    
    function open_edit_rss_source_popup($id) {
        $rss_obj = new RSSRource();
        echo $rss_obj->build_edit_rss_source_popup($id);
        
        exit;
    }
    
    function edit_rss_source(){
         $helper_obj = new Helper();
                            
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){
            $rss_obj = new RSSRource();
            
            $rss_id = $_POST['edit_rss_source'];
            $name = $_POST['rss_name_updated'];
            $link = $_POST['rss_link_updated'];
            
            $db_functions_obj = new DbFunctions(); 
            
            $db_functions_obj->edit_rss_source($rss_id, $name, $link);
             
            echo $rss_obj->build_rss_source_row($rss_id) . "***#***" . $rss_id;
        }
        
        exit;
    }
    
    function country_category_rss(){
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){  
            global $user, $base_path, $menu_active;
            
            $rss_obj = new RSSRource();    
            
            $menu_active['add_content'] = 'active'; 

            $this->content = '<!-- Validation -->
                               <script src="' . $base_path . 'js/plugins/validation/jquery.validate.min.js"></script>
                               <script src="' . $base_path . 'js/plugins/validation/additional-methods.min.js"></script>';
              
            $this->content .= $rss_obj->build_country_category_rss_form();
            $this->content .= $rss_obj->get_country_category_rss_source();
        }
        else{
           $this->set_access_denied();
        }   
        
         
    }
    
    function add_country_category_rss(){
         $helper_obj = new Helper();
        
         if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){
            $rss_obj = new RSSRource();
            $db_functions_obj = new DbFunctions();
            
            $id = $db_functions_obj->add_country_category_rss($_POST);
                     
            echo $rss_obj->build_country_category_rss_source_row($id);
         }
         
         exit;
    }
    
    function delete_country_category_rssSource($id){
        
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){ 
            $db_functions_obj = new DbFunctions();
            $db_functions_obj->delete_country_category_rssSource($id);
        }
        exit;
    }
    
    function tags() {
        $helper_obj = new Helper();
        global $base_path; 
             // echo('you login?'.$helper_obj->user_is_logged_in());
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)){
            $tag_obj = new Tag();
            $this->content = '<!-- Validation -->
                                 <script src="' . $base_path . 'js/plugins/validation/jquery.validate.min.js"></script>
                                 <script src="' . $base_path . 'js/plugins/validation/additional-methods.min.js"></script>';
            $this->content .= $tag_obj->build_add_tag_form();
            $this->content .= $tag_obj->get_tags();
        }
    }
    
    function add_tag(){
        $helper_obj = new Helper();
        global $base_path; 
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)) {
            $db_functions_obj = new DbFunctions();
            $tag_obj = new Tag();
                            //  pr($_FILES);exit;
            $last_id = $db_functions_obj->add_tag($_POST);     
            echo($tag_obj->build_tag_row($last_id)); 
        }
        
        exit;
    }
    
    function add_categories_to_client(){
        $client_obj = new Client();  
                
        $this->content = '<div class="box box-bordered">';
        $this->content .= '<div class="box-title">
                        <h3><i class="icon-th-list"></i> Select a client</h3>
                    </div>';
                       
        $this->content .= '<form class="form-horizontal form-validate form-vertical form-bordered">' . 
                              $client_obj->build_clients_list("add","", "onchange='getCategoriesByClient(this)'") . 
                          '</form><br /><br />';
        $this->content .= "<div class='client-menu'></div>";
    }
    
    function add_category_to_client($param){
        $db_function_onj = new DbFunctions();
        
        $data = explode("/", $param);
        
        $cat_id = $data[0];
        $client_id = $data[1];
                    
        $db_function_onj->add_category_to_client($cat_id, $client_id);
        
        $this->get_categories_by_client($client_id);
    }
    
    function remove_category_to_client($param){
        $db_function_onj = new DbFunctions();
        
        $data = explode("/", $param);
        
        $cat_id = $data[0];
        $client_id = $data[1];
        
        $db_function_onj->remove_category_to_client($cat_id, $client_id);
        
        $this->get_categories_by_client($client_id);
    }
    
    function comment_approve() {
        global $user; 
        $client_id = $user['cid'];
        $db_function_onj = new DbFunctions(); 
        
        $commets = $db_function_onj->get_comments_by_client($client_id);
        
        $output = "<br /><br /><b>Comments:</b><br /><br /><table id='add_article_html_table' style='font-size: 10px' class='table table-hover table-nomargin table-bordered'>
                      <tr>
                        <th>Id</th>
                        <th>Username</th>     
                        <th>Comment</th>  
                        <th>Date Added</th>  
                        <th>Approve</th>
                      </tr>";

         foreach($commets as $key => $data) {
            $output .= "<tr id='commnet_" . $data->id . "'>
                          <td>" . $data->id . "</td>
                          <td>" . $data->username . "</td>      
                          <td>" . $data->comment . "</td>        
                          <td>" .  date(DATE_FORMAT, $data->date_added) . "</td>      
                          <td><input type='checkbox' value='" . $data->id . "' onclick='approveComment(this);'></td>
                       </tr>";
         }
        
        $this->content = $output;         
    }
    
    function approve_comment($comment_id) {
        $db_function_onj = new DbFunctions(); 
        
        $db_function_onj->approve_comment($comment_id);
    }
    
    function delete_comment($comment_id) {
        $db_function_onj = new DbFunctions(); 
        
        $db_function_onj->delete_comment($comment_id);
    }
    
    function comment_abuse() {
        global $user; 
        $client_id = $user['cid'];
        $db_function_onj = new DbFunctions(); 
        
        $commets = $db_function_onj->get_comment_abuse_by_client($client_id); 
        
        $output = "<br /><br /><b>Comments Abuse:</b><br /><br />
                    <table id='add_article_html_table' style='font-size: 10px' class='table table-hover table-nomargin table-bordered'>
                      <tr>
                        <th>Comment Id</th>
                        <th>Username</th>     
                        <th>Comment</th>  
                        <th>Abuse Count</th>
                        <th>Date Added</th>  
                        <th>Delete</th>  
                      </tr>";

         foreach($commets as $key => $data) {
            $output .= "<tr id='commnet_" . $data->id . "'>
                          <td>" . $data->id . "</td>
                          <td>" . $data->username . "</td>      
                          <td>" . $data->comment . "</td>        
                          <td><b><span style='color:red'>" .  $data->c . "</span></b></td>      
                          <td>" .  $data->abuse_date . "</td>      
                          <td><input type='checkbox' value='" . $data->id . "' onclick='deleteComment(this);'></td>
                       </tr>";
         }
        
        $this->content = $output; 
    }
    
    function send_pn() {
        global $menu_active, $base_path;
        $helper_obj = new Helper();
        
        $menu_active['push_notification'] = 'active'; 
        
        $pn_obj = new PushNotification();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)) {
            $this->content = '<!-- Validation -->
                           <script src="' . $base_path . 'js/plugins/validation/jquery.validate.min.js"></script>
                           <script src="' . $base_path . 'js/plugins/validation/additional-methods.min.js"></script>';;
            $this->content .= $pn_obj->build_send_push_notificatation_form();
        }
    }
    
    function send_pn_action() {
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)) {
            $db_functions_obj = new DbFunctions();
            
            $cid = $_POST['add_client'];
            $msg = $_POST['msg'];
            
            $client_info = $db_functions_obj->get_client_by_id($cid);
                         
            $tokens = $db_functions_obj->get_tokens($cid);
            
            define("GOOGLE_API_KEY", $client_info->api); // Place your Google API Key 
            include_once 'includes/classes/GCM.php'; 
            $gcm = new GCM(); 
                     
            foreach ($tokens as $token) {       
                $user_token = $token->token;
                $type = $token->type; 
                
                //ios 
                if ($type == 1) {
                    $pn_obj = new PushNotification();
                    $pn_obj->push_notification($msg, $user_token, 2, $client_info->pem);
                }
                //android 
                else if ($type == 2) {  
                     $m = array("message" => $msg);
                     $deviceId[] = $user_token;
                     $result = $gcm->send_notification($deviceId, $m); 
                }
            }
            
            exit;
        }
    }
    
    function search_news($word) {
        global $base_path;
        $helper_obj = new Helper();
       
        $output = "";
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(CLIENT_ROLE_ID)) {
            $db_function_obj = new DbFunctions();
            
            $word = urldecode($word);
            
            if (mb_strlen($word, "UTF-8") > 5) {
                $search_results = $db_function_obj->search_news($word);
                
                //$output .= '<script src="js/plugins/ckeditor/ckeditor.js"></script>';
                    
                $output .= "<table id='add_article_html_table' style='font-size: 10px' class='table table-hover table-nomargin table-bordered'>
                          <tr>
                            <th>" . $helper_obj->t("ID") . "</th>
                            <th>" . $helper_obj->t("Title") . "</th>     
                            <th>" . $helper_obj->t("Image") . "</th>  
                            <th>" . $helper_obj->t("Added By") . "</th>  
                            <th>" . $helper_obj->t("Date Added") . "</th>  
                            <th>" . $helper_obj->t("Edit") . "</th>
                            <th>" . $helper_obj->t("Delete") . "</th>
                          </tr>"; 
                          
                foreach($search_results as $data) {
                    $class = $helper_obj->table_row_class($i);
                
                    $output .= "<tr id='article_$data[id]'>
                                  <td>" . $data['id'] . "</td>
                                  <td>" . $data['title'] . "</td>      
                                  <td>" . $data['image'] . "</td>      
                                  <td>" . $data['added_by'] . "</td>      
                                  <td>" . date(DATE_FORMAT, $data['date_added']) . "</td>      
                                  <td><a href='javascript:void(0);' onclick='openEditHtmlArticlePopup($data[id])'>" . $helper_obj->t("Edit") . "</a></td>
                                  <td><a href='javascript:void(0);' onclick='deleteHtmlArticle($data[id])'>" . $helper_obj->t("Delete") . "</a></td>
                               </tr>";
                    
                }
            }
            else{
                echo('<tr><td><b>يجب ادخال كلمة اكتر من 5 احرف</b></td></tr>');
            }
            
            $output .= "</table>";  
        }  
        
        echo $output;                                  
        exit;       
    }
    
    function related_tags(){
        $helper_obj = new Helper();
        
        global $base_path;
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)) {
            $tags_obj = new Tag();
            $this->content = '<!-- Validation -->
                               <script src="' . $base_path . 'js/plugins/validation/jquery.validate.min.js"></script>
                               <script src="' . $base_path . 'js/plugins/validation/additional-methods.min.js"></script>';
                               
            $this->content .= $tags_obj->build_related_tags_form();
            
        }
    }
    
    function add_related_tag(){
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)) {
            $db_function_obj = new DbFunctions();
            echo $db_function_obj->add_related_tag($_POST);
        }
        
        exit;
    }  
    
    function add_related_tag1($params){
        $helper_obj = new Helper();       
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)) {
            $db_function_obj = new DbFunctions();
            
            $params = explode("_", $params);
            
            $_POST['parent_id'] = $params[0];
            $_POST['parent_name'] = $params[1];
            $_POST['tag_name'] = $params[2];
            
            echo $db_function_obj->add_related_tag($_POST, 1);
        }
        
        exit;
    }           
    
    function get_related_tags($tid) {
        $helper_obj = new Helper();
                     
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)) {
            $tag_obj = new Tag();
            
            echo $tag_obj->get_related_tags($tid);   
        }
        else{
            $this->set_access_denied(1); 
        }
        
        exit; 
    }
    
    function delete_reletad_tag($id) {
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)) {
            $db_function_obj = new DbFunctions();
            $db_function_obj->delete_reletad_tag($id);
        }
        exit;
    }
    
    function update_sort($id) {
        $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)) {
            $db_function_obj = new DbFunctions();
            $data = explode("_", $id);
            $cat_id = $data[0];
            $sort_val = $data[1];
            
            $db_function_obj->update_sort($cat_id, $sort_val);
            $db_function_obj->update_uv($cat_id);
        }
        
        exit;
    }
    
    function tags_and_synonyms(){
         $helper_obj = new Helper();
        
        if ($helper_obj->user_is_logged_in() && $helper_obj->check_role(SUPER_ADMIN_ROLE_ID)) {
            $tag_obj = new Tag();
            
            $this->content = $tag_obj->tags_and_synonyms();
        }
    }
    
}