<?php
//require_once 'google/appengine/api/cloud_storage/CloudStorageTools.php';
//use google\appengine\api\cloud_storage\CloudStorageTools;

class DbFunctions {
    private $conn;
    
    function __construct() {
        $this->conn = new MySQLiDatabaseConnection();
    }
    
    function get_all_clients($cid = 0, $active = 0){
          $query = "SELECT c.id, c.name, logo, c.status, c.date_added, u1.username added_by, c.date_updated, u2.username updated_by, c.pem, c.api
                    FROM clients c
                    INNER JOIN users u1 on c.added_by = u1.id
                    LEFT JOIN users u2 on c.updated_by = u2.id 
                    ";
          $condition = false;
          
          if ($cid != 0) {
              $query .= " WHERE c.id = '$cid'";
              $condition = true;
          }
          
          if ($active){
              if ($condition) {
                  $query .= " AND c.status = 1";
              }
              else{
                $query .= " WHERE c.status = 1";
              }
          }
          
          $query .= " ORDER BY c.date_added DESC";
                  
          $result =  $this->conn->db_query($query);
           
          //while($row = $this->conn->db_fetch_object($result)) {
          while($row = $this->conn->fetch_assoc($result)) {
              $clients[] = $row;
          }
          
          return $clients;     
    }
    
    function get_active_client(){
        return $this->get_all_clients(0,1);
    }
    
    function get_all_users($uid = 0, $cid = 0) {
        $query = "SELECT u.id, username, email, phone, u.date_added, c.name country_name, o.name operator_name, u.status,
                         r.name role_name, cl.name client_name
                  FROM users u
                  INNER JOIN countries c on u.country_id = c.id
                  INNER JOIN operators o on u.operator_id = o.id
                  INNER JOIN users_roles ur on ur.uid = u.id
                  INNER JOIN roles r on r.id = ur.rid
                  LEFT JOIN clients cl on cl.id = ur.cid";
        $valid = false;
                  
        if ($uid != 1) {
            $query .= " WHERE u.id = '$uid'";  
            $valid = true;
        }
        
        if ($cid != 1) {
            if ($valid) {
                $query .= " AND cl.id = '$cid'";
            }
            else{
                $query .= " WHERE cl.id = '$cid'";
            }
        }
        
        $query .= " ORDER BY u.date_added DESC"; 
      //  echo($query);
        $result =  $this->conn->db_query($query);
        
        $users = array();
              
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
          
        return $users;
    }
    
    function get_user_detail_by_id($uid) {         //echo($uid);
        $user_info = $this->get_all_users($uid, 1);
      //  pr($user_info);
        return $user_info[0];
    }
    
    function add_client($client_name, $logo, $added_by){
        $now = time();
        $last_id = ""; 
        
        global $user;
        $rid = $user->rid;
         
        //only super admin can delete client 
        if (SUPER_ADMIN_ROLE_ID == $rid) {
            $query = "INSERT INTO clients (name, logo, date_added, added_by) value ('$client_name', '$logo', '$now', '$added_by')";
            $result =  $this->conn->db_query($query);  
                       
            $last_id = $this->conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning     
        }
        
        return $last_id; 
    }
    
    function get_client_by_id($cid) {
         $client_info = $this->get_all_clients($cid);
                   
         return $client_info[0];
    }
    
    function deactive_active_client($cid, $added_by) {
        //$rid = $this->get_rid_by_uid($added_by);
         global $user;
         $rid = $user->rid;
         
        //only super admin can delete client 
        if (SUPER_ADMIN_ROLE_ID == $rid) {
            $query = "UPDATE clients set status = !status where id = '$cid'";
            $result =  $this->conn->db_query($query); 
            
            $client_info = $this->get_client_by_id($cid);
            echo($client_info->status);
            exit;
        }
    }
    
    function deactive_active_user($uid) {
        $query = "UPDATE users set status = !status where id  = '$uid'";
        $result =  $this->conn->db_query($query); 
        $user_obj = new User();
        
        $user_info = $user_obj->user_load($uid);
        echo($user_info->status);
        exit;
    }
      
    function get_rid_by_uid($uid) {
        $query = "SELECT rid from users_roles where uid = '$uid' ORDER BY id DESC";
        $result =  $this->conn->db_query($query); 
        $row = $this->conn->db_fetch_array($result);
        //$row = $result->fetch_assoc();
            
        return $row->rid;
    }
    
    function edit_client($cid, $cname, $clogo) {
        global $user;
        $updated_by = $user->id;
        $date_updated = time();
        
        $query = "UPDATE clients SET name = '$cname'";
        
        if (trim($clogo) != "") {
            $query .= ", logo = '$clogo'";
        }
        
        $query .= ", date_updated = '$date_updated', updated_by = '$updated_by' WHERE id = $cid";   
        $result =  $this->conn->db_query($query);
    }
    
    function get_country() {
        $query = "SELECT id, name FROM countries ORDER BY id DESC";
        $result =  $this->conn->db_query($query); 
        
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $countries[] = $row;
        }
          
        return $countries;  
    }
    
    function get_zodiac() {
        $query = "SELECT id, name FROM zodiac ORDER BY id DESC";
        $result =  $this->conn->db_query($query); 
        
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $zodiac[] = $row;
        }
          
        return $zodiac;  
    }
    
    function get_operators_by_country($country_id) {
        $query = "SELECT id, name FROM operators WHERE country_id = '$country_id' ORDER BY id DESC";
        $result =  $this->conn->db_query($query); 
              
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $operators[] = $row;
        }
          
        return $operators;
    }
    
    function get_roles(){
        $query = "SELECT id, name FROM roles ORDER BY id DESC";
        $result =  $this->conn->db_query($query); 
              
        while($row = $this->conn->db_fetch_array($result)) { 
        //while($row = $result->fetch_assoc()){ 
            $roles[] = $row;
        }
          
        return $roles;
    }
    
    function add_user($data) {
        $username = trim($data['username']);
        $password = trim($data['password']);
        $phone = trim($data['phone']);
        $email = trim($data['email']);
        $role = trim($data['add_role']);
        $udid = trim($data['udid']);
        $device_id = trim($data['device_id']);
        $type = trim($data['type']);
              
        $client = '1';
        
        if (isset($data['add_operator'])){
            $client = trim($data['add_client']); 
        }
        
        $country = '1';
        
        if (isset($data['add_country'])){
            $country = trim($data['add_country']); 
        }

        $operator = '1';
        
        if (isset($data['add_operator'])){
            $operator = trim($data['add_operator']);
        }
        
        $status = 1;
        
        if (isset($data['status'])){
            $status = trim($data['status']);
        }
        
        $verification_code = '';
        
        if (isset($data['verification_code'])){
            $verification_code = trim($data['verification_code']);
        }
                
        $date_added = time();
        
        $query = "INSERT INTO users (username, password, email, phone, date_added, country_id, operator_id, status, verification_code, udid, type, device_id) value
                                    ('$username', md5('$password'), '$email', '$phone', '$date_added', '$country', '$operator', '$status', '$verification_code', 
                                     '$udid', '$type', '$device_id')";
                                 
        $result =  $this->conn->db_query($query); 
        
        $last_id = $this->conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning   
        
        $role_query = "INSERT INTO users_roles (uid, rid, cid) value ('$last_id', '$role', '$client')"; 
        
        $result =  $this->conn->db_query($role_query);
        
        return $last_id;
    }
    
    function edit_user($uid, $username, $email, $phone, $password) {
          $query = "UPDATE users set username = '$username', email = '$email', phone = '$phone' ";
          
          if (trim($password) != "") {
              $password = md5($password);
              $query .= ", password = '$password'";
          }
          
          $query .= " WHERE id = '$uid'";
          
          $result =  $this->conn->db_query($query);
    }
    
    function get_menu_parents() {
        $query = "SELECT id, title, url FROM menu WHERE parent = 0";
        $result =  $this->conn->db_query($query); 
        
        $menu_parents = "";
        
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $menu_parents[] = $row;
        }
          
        return $menu_parents;
    }
    
    function get_menu_parents_by_client($cid) {
        $query = "SELECT menu.id, title, url, menu_clients.cid  
                  FROM menu_clients  
                  INNER JOIN menu on menu.id = menu_clients.menu_id
                  WHERE menu_clients.cid = '$cid' AND parent = 0 ORDER BY menu.id DESC";  
                          
        $result =  $this->conn->db_query($query); 
        
        $menu_parents = "";
        
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $menu_parents[] = $row;
        }
        
        $result->free();
          
        return $menu_parents;
    }
    
     function get_menu_parents_not_assignt_to_client($cid) {
        $query = "SELECT menu.id, title, url 
                  FROM menu 
                  WHERE menu.id not in ( select menu_id from menu_clients where cid = '$cid') 
                  AND parent = 0"; 
                  
        $result =  $this->conn->db_query($query); 
        
        $menu_parents = "";
        
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $menu_parents[] = $row;
        }
        
        $result->free();
          
        return $menu_parents;
    }
    
    function add_item_to_menu($data, $added_by) {
        $title = trim($data['menu_item_title']);
        $url = trim($data['menu_item_url']);
        $parent = trim($data['add_menu']);
        
        $date_added = time();      
        
        $query = "INSERT INTO menu (title, url, parent, date_added, added_by) value ('$title', '$url', '$parent', '$date_added', '$added_by')";
        //echo($query);  exit;
        $result =  $this->conn->db_query($query); 
    }
    
    function get_menu_childs_by_parent($pid, $cid = 0){
        $query = "SELECT menu.id, title, url
                  FROM menu";
                  
        if ($cid) {
            $query .= " INNER JOIN menu_clients on menu_clients.menu_id = menu.id AND menu_clients.cid = '$cid'";
        }
                  
        $query .= " WHERE parent = '$pid'";
         
        $result =  $this->conn->db_query($query); 
                //echo($query . '<br />');
        $menu_childs = "";
        
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $menu_childs[] = $row;
        }
        
        $result->free();
          
        return $menu_childs;
    }
    
    function get_menu_childs_not_assigned_to_client($pid, $cid) {
        $query = "  SELECT menu.id, title, url 
                    FROM menu 
                    WHERE menu.id not in ( select menu_id from menu_clients where cid = '$cid') 
                    AND parent = '$pid'";      
                    
        //$query = "SELECT ";
        $result =  $this->conn->db_query($query); 
               // echo($query);
        $menu_childs = "";
        
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $menu_childs[] = $row;
        }
        
        $result->free();
          
        return $menu_childs;
    }
    
    function add_menu_item_to_client($cid, $menu_id) {
        $query = "INSERT INTO menu_clients (menu_id, cid) value ('$menu_id', $cid)";
        $result =  $this->conn->db_query($query);   
    }
    
    function remove_menu_item_from_client($cid, $menu_id){ 
        $query = "DELETE FROM menu_clients WHERE cid = '$cid' AND menu_id = '$menu_id'";
        $result =  $this->conn->db_query($query); 
    }
    
    function check_if_client_has_menu_item($menu_id, $cid) {
        $query = "SELECT count(id) c FROM menu_clients where menu_id = '$menu_id' AND cid = '$cid'";
        $result =  $this->conn->db_query($query); 
        $row = $this->conn->db_fetch_object($result);
               // echo($query);
        return $row->c;   
    }
    
    function get_all_categories($client_id){
        //$query = "SELECT id, name, image1, image2, premium FROM categories ORDER BY id DESC";
        
        $client_condition = " inner join categories_by_clients on categories_by_clients.category_id = categories.id where client_id = '$client_id'";
        
        if ($client_id == 1) {
            $client_condition = "";
        }
        
        $query = "select c1.id, c1.name, c1.image1, c1.image2, c1.premium, c1.sort, c2.name parent 
                  from categories c1
                  inner join categories c2 on c1.parent = c2.id
                  " . $client_condition . " order by c1.name";
                  //echo($query);    
        $result =  $this->conn->db_query($query);
        $categories = array();
       
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $categories[] = $row;
        }     
        
        $result->free();
            
        return $categories; 
    }
    
    function get_all_category_sources(){
        //$query = "SELECT id, name, image1, image2, premium FROM categories ORDER BY id DESC";
        
        $query = "select c1.id, c1.name
                    from categories c1
                    inner join categories c2 on c1.parent = c2.id
                    inner join categories c3 on c2.parent = c3.id
                    where not exists (select category_id from rss_news where rss_news.category_id = c1.id)";
                  //echo($query);    
        $result =  $this->conn->db_query($query);
        $categories = array();
       
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $categories[] = $row;
        }     
        
        $result->free();
            
        return $categories; 
    }
    
    function get_all_sources(){
       
        $query = "select rss_news.*, categories.name from rss_news inner join categories on categories.id = rss_news.category_id";
                  //echo($query);    
        $result =  $this->conn->db_query($query);
        $categories = array();
       
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $categories[] = $row;
        }     
        
        $result->free();
            
        return $categories; 
    }
    
    function get_all_hitds(){
        $query = "SELECT id, date, body FROM happened_in_this_day ORDER BY id DESC";
        $result =  $this->conn->db_query($query);
        $hitds = array();
       
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $hitds[] = $row;
        }     
        
        $result->free();
            
        return $hitds; 
    }
    
    function get_all_pgrate() {
        $query = "SELECT id, name FROM pg_rates";
        $result =  $this->conn->db_query($query);
        
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $pgrates[] = $row;
        }
        
        $result->free();
           
        return $pgrates;
    }
    
    function get_all_tags($from = '', $offset = '') {
        
        if ($from != '' && $offset != '') {
            $query = "SELECT id, name, image FROM tags order by id desc limit $from, $offset"; 
        }
        else{
           $query = "SELECT id, name, image FROM tags order by id desc";
        }
        $result =  $this->conn->db_query($query);
        
        $tags = array();
        
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $tags[] = $row;
        }
          
        $result->free();
           
        return $tags;
    }
    
     function add_video($data) {
        $helper_obj = new Helper();
        $image2 = $image3 = $image4 = "";
        
        global $user;
        
        $uid = $user->id;
        $cid = $user->cid;
           //  pr($_FILES);
        $image1_type = explode(".", $_FILES['image1']['name']);
        $screenshot_type = explode(".", $_FILES['screenshot']['name']);
        $video_type = explode(".", $_FILES['video']['name']);
        
        if (isset($_FILES['image2'])){
            $image2_type = explode(".", $_FILES['image2']['name']);
            $image2 = $helper_obj->generate_name() . '.' . $image2_type[1];  
            
            move_uploaded_file($_FILES["image2"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image2);
            
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image2, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image2, THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
        
        } 
        if (isset($_FILES['image3'])){
            $image3_type = explode(".", $_FILES['image3']['name']);
            $image3 = $helper_obj->generate_name() . '.' . $image3_type[1];  
            
            move_uploaded_file($_FILES["image3"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image3);
            
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image3, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image3, THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
        
        }
        if (isset($_FILES['image4'])){
            $image4_type = explode(".", $_FILES['image4']['name']);
            $image4 = $helper_obj->generate_name() . '.' . $image4_type[1]; 
            
            move_uploaded_file($_FILES["image4"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image4);
            
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image4, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image4, THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
         
        }
                 
        //pr($data);
        $title = trim($data['title']);
        $description = trim($data['description']);
        
        $cat_id = $data['add_category'];
        $image1 = $helper_obj->generate_name() . "." . $image1_type[1]; 
        
        move_uploaded_file($_FILES["image1"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image1);
            
        $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image1, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image1, THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
        
        $screenshot = $helper_obj->generate_name() . "." . $screenshot_type[1];
        $video = $helper_obj->generate_name() . "." . $video_type[1];
        
        move_uploaded_file($_FILES["video"]["tmp_name"], VIDEOS_PATH_UPLOAD . $video);
        
        move_uploaded_file($_FILES["screenshot"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $screenshot);
            
        $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $screenshot, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $screenshot, THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
            
        $pgrate_id = $data['add_pgrate'];  
        $feature = (isset($data['feature']) ? $data['feature']: 0);
        $premium = (isset($data['premium']) ? $data['premium']: 0);
        
        $now = time();
        
        $last_id = "";
        
        $query = "INSERT INTO videos (title, description, upload_date, category_id, image1, image2, image3, image4, screenshot, url, featured, premium, pg_rated_id, added_by, client_id) value
                                     ('$title', '$description', '$now', '$cat_id' , '$image1', '$image2', '$image3', '$image4', '$screenshot', '$video', '$feature', '$premium', '$pgrate_id', '$uid', $cid)";
                             //echo($query);        
        $result =  $this->conn->db_query($query);
        
        $last_id = $this->conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning  
                 
        return $last_id;
    }
    
    function add_article_html($data){
        global $user;
        $helper_obj = new Helper();
        $tag_obj = new Tag();
        $cat_obj = new Category();
        
        $uid = $user['id'];
        $cid = $user['cid'];
        
        $title = trim($data['title']);
       // $video = trim($data['video']); 
        
        $ck = trim($data['ck']);       
                   
       // $ck = str_replace("<script>", "***script***", $ck);
        //$ck = str_replace("</script>", "***@script***", $ck);
        
        //$add_category = trim($data['add_category']);
        $add_pgrate = trim($data['add_pgrate']);
        $section = trim($data['section']);
                      
        if (isset($_FILES['image1'])){
            $image2_type = explode(".", $_FILES['image1']['name']);
            $image2 = $helper_obj->generate_name() . '.' . $image2_type[1];  
                    
            $gs_name = $_FILES['image1']['tmp_name'];
            $move = copy($gs_name, 'gs://' . GOOGLE_APP_ID . '/article/'.$image2.'');
            $move = copy($gs_name, 'gs://' . GOOGLE_APP_ID . '/feature/'.$image2.'');
            $move = copy($gs_name, 'gs://' . GOOGLE_APP_ID . '/thumbnail/'.$image2.'');
            
            $object_image_file1 = 'gs://' . GOOGLE_APP_ID . '/article/'.$image2.'';
            $object_image_url1 = CloudStorageTools::getImageServingUrl($object_image_file1, ['size' => 620, 'crop' => false]);
            
            $object_image_file2 = 'gs://' . GOOGLE_APP_ID . '/feature/'.$image2.'';
            $object_image_url2 = CloudStorageTools::getImageServingUrl($object_image_file2, ['size' => 100, 'crop' => true]);
            
            $object_image_file3 = 'gs://' . GOOGLE_APP_ID . '/thumbnail/'.$image2.'';
            $object_image_url3 = CloudStorageTools::getImageServingUrl($object_image_file3, ['size' => 320, 'crop' => true]);
                                     
            $move = copy($object_image_url1, 'gs://' . GOOGLE_APP_ID . '/article/'.$image2.'');         
            $move = copy($object_image_url2, 'gs://' . GOOGLE_APP_ID . '/feature/'.$image2.'');         
            $move = copy($object_image_url3, 'gs://' . GOOGLE_APP_ID . '/thumbnail/'.$image2.'');   
                  
            //move_uploaded_file($_FILES["image1"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image2);
            
            //$im = $helper_obj->img_resize(VIDEOS_IMAGES_PATH_UPLOAD . $image2, 620);
            //$helper_obj->imageToFile($im, VIDEOS_IMAGES_PATH_UPLOAD . $image2, 100);
            
            /*$helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image2, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image2, 
                                           THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
        
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image2, VIDEOS_IMAGES_PATH_FEATURE_UPLOAD . $image2, 
                                           FEATURE_W, FEATURE_H, THUMBNAIL_R, THUMBNAIL_ENABLE);   */
                                           
            //woman app
            if ($cid == 49) {           
              /*  $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image2, VIDEOS_IMAGES_THUMBNAIL_320x272_PATH_UPLOAD . $image2, 
                                               IMAGE_320x272_W, IMAGE_320x272_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
            
                $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image2, VIDEOS_IMAGES_THUMBNAIL_320x352_PATH_UPLOAD . $image2, 
                                           IMAGE_320x352_W, IMAGE_320x352_H, THUMBNAIL_R, THUMBNAIL_ENABLE);  */
            }
        } 
        
       // $image = trim($data['image1']);
        
        $now = time();
                   
       /* $query = "insert into articles_html (title, body, image, added_by, date_added, client_id, category_id, pg_rated_id, section, video) 
                  value 
                  ('$title', '$ck', '$image2', '$uid', '$now', '$cid', '$add_category', '$add_pgrate', '$section', '$video')";     */
                  
        $query = "insert into articles_html (title, body, image, added_by, date_added, client_id, pg_rated_id, section) 
                  value 
                  ('$title', '$ck', '$image2', '$uid', '$now', '$cid', '$add_pgrate', '$section')";     
        //  echo($query);
        $result =  $this->conn->db_query($query) or die(mysql_error());
        
        $last_id = "";
        
        $last_id = $this->conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning 
        
        //add tags
        foreach($data['add_tag'] as $tag_id) {
            $tag_obj->add_tags_to_article($last_id, $tag_id); 
        }
        
         //add categories
        foreach($data['add_category'] as $cat_id) {
            $cat_obj->add_categories_to_article($last_id, $cat_id); 
        }
                 
        return $last_id;       
    }
    
    function add_tags_to_article($aid, $tag_id) {   
        $query = "insert into article_tags (aid, tid) value ('$aid', '$tag_id')";
        $this->conn->db_query($query);
    }
    
    function add_categories_to_article($aid, $cid) {   
        $query = "insert into article_categories (aid, cid) value ('$aid', '$cid')";
        $this->conn->db_query($query);
    }
    
    function get_article_tags($aid){
        $query = "select tid from article_tags where aid = '$aid'";
        $res = $this->conn->db_query($query); 
        
        $data = array();
         
        while($row = $this->conn->db_fetch_array($res)) {
        //while($row = $res->fetch_assoc()){
             $data[] = $row['tid'];  
        }   
          
        return $data;
    }  
    
    function get_article_categories($aid){
        $query = "select cid from article_categories where aid = '$aid'";
        $res = $this->conn->db_query($query); 
        
        $data = array();
         
        while($row = $this->conn->db_fetch_array($res)) {
        //while($row = $res->fetch_assoc()){
             $data[] = $row['cid'];  
        }   
          
        return $data;
    }
    
    function get_all_articles_in_tag($tid) {
        $query = "select articles_html.* 
                 from article_tags
                 inner join articles_html on articles_html.id = article_tags.aid 
                 where tid = '$tid'";
                 
        $res = $this->conn->db_query($query);
        
        $data = array();
         
        while($row = $this->conn->db_fetch_array($res)) {
        //while($row = $res->fetch_assoc()){
             $data[] = $row;  
        }   
          
        return $data; 
    }
   
    function add_article($data) {
        $helper_obj = new Helper();
        $text2 = $text3 = $image1 = $image2 = $image3 = $image4 = $image5 = $video1 = $video2 = "";
        $text1_order = $text2_order = $text3_order = $image1_order = $image2_order = $image3_order = $image4_order = $image5_order = $video1_order = $video2_order = 0;
        
        global $user;
        
        $uid = $user->id;
        $cid = $user->cid;
              // pr($data);
        $text1 = $data['text1'];
          
        if (isset($data['text2'])){
            $text2 = $data['text2'];
        }
        
        if (isset($data['text3'])){
            $text3 = $data['text3'];
        }
         
        if (isset($_FILES['image1'])){
            $image1_type = explode(".", $_FILES['image1']['name']);
            $image1 = $helper_obj->generate_name() . '.' . $image1_type[1];  
            
            move_uploaded_file($_FILES["image1"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image1);
            
            $im = $helper_obj->img_resize(VIDEOS_IMAGES_PATH_UPLOAD . $image1, 620);
            $helper_obj->imageToFile($im, VIDEOS_IMAGES_PATH_UPLOAD . $image1, 100);
                        
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image1, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image1, THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
          
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image1, VIDEOS_IMAGES_PATH_FEATURE_UPLOAD . $image1, 
                                           FEATURE_W, FEATURE_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
        } 
        if (isset($_FILES['image2'])){
            $image2_type = explode(".", $_FILES['image2']['name']);
            $image2 = $helper_obj->generate_name() . '.' . $image2_type[1];  
            
            move_uploaded_file($_FILES["image2"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image2);
            
            $im = $helper_obj->img_resize(VIDEOS_IMAGES_PATH_UPLOAD . $image2, 620);
            $helper_obj->imageToFile($im, VIDEOS_IMAGES_PATH_UPLOAD . $image2, 100);
            
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image2, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image2, THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
        
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image2, VIDEOS_IMAGES_PATH_FEATURE_UPLOAD . $image2, 
                                           FEATURE_W, FEATURE_H, THUMBNAIL_R, THUMBNAIL_ENABLE);
        } 
        if (isset($_FILES['image3'])){
            $image3_type = explode(".", $_FILES['image3']['name']);
            $image3 = $helper_obj->generate_name() . '.' . $image3_type[1];  
            
            move_uploaded_file($_FILES["image3"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image3);
            
            $im = $helper_obj->img_resize(VIDEOS_IMAGES_PATH_UPLOAD . $image3, 620);
            $helper_obj->imageToFile($im, VIDEOS_IMAGES_PATH_UPLOAD . $image3, 100);
            
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image3, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image3, THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
        
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image3, VIDEOS_IMAGES_PATH_FEATURE_UPLOAD . $image3, 
                                           FEATURE_W, FEATURE_H, THUMBNAIL_R, THUMBNAIL_ENABLE);
        }
        if (isset($_FILES['image4'])){
            $image4_type = explode(".", $_FILES['image4']['name']);
            $image4 = $helper_obj->generate_name() . '.' . $image4_type[1]; 
            
            move_uploaded_file($_FILES["image4"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image4);
            
            $im = $helper_obj->img_resize(VIDEOS_IMAGES_PATH_UPLOAD . $image4, 620);
            $helper_obj->imageToFile($im, VIDEOS_IMAGES_PATH_UPLOAD . $image4, 100);
            
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image4, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image4, THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
         
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image4, VIDEOS_IMAGES_PATH_FEATURE_UPLOAD . $image4, 
                                           FEATURE_W, FEATURE_H, THUMBNAIL_R, THUMBNAIL_ENABLE);
        }
        if (isset($_FILES['image5'])){
            $image5_type = explode(".", $_FILES['image5']['name']);
            $image5 = $helper_obj->generate_name() . '.' . $image5_type[1]; 
            
            move_uploaded_file($_FILES["image5"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image5);
            
            $im = $helper_obj->img_resize(VIDEOS_IMAGES_PATH_UPLOAD . $image5, 620);
            $helper_obj->imageToFile($im, VIDEOS_IMAGES_PATH_UPLOAD . $image5, 100);
                                         
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image5, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image5, THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
         
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image5, VIDEOS_IMAGES_PATH_FEATURE_UPLOAD . $image5, 
                                           FEATURE_W, FEATURE_H, THUMBNAIL_R, THUMBNAIL_ENABLE);
        }
        
        if (isset($data['video1'])){
            $video1 = $data['video1'];
        }
        
        if (isset($data['video2'])){
            $video2 = $data['video2'];
        }
        
        if (isset($data['text1_order']) && trim($data['text1_order']) != "") {
            $text1_order = $data['text1_order'];
        }
        if (isset($data['text2_order'])&& trim($data['text2_order']) != "") {
            $text2_order = $data['text2_order'];
        }
        if (isset($data['text3_order'])&& trim($data['text3_order']) != "") {
            $text3_order = $data['text3_order'];
        }
        
        if (isset($data['image1_order']) && trim($data['image1_order']) != "") {
            $image1_order = $data['image1_order'];
        }
        if (isset($data['image2_order'])&& trim($data['image2_order']) != "") {
            $image2_order = $data['image2_order'];
        }
        if (isset($data['image3_order'])&& trim($data['image3_order']) != "") {
            $image3_order = $data['image3_order'];
        }
        if (isset($data['image4_order'])&& trim($data['image4_order']) != "") {
            $image4_order = $data['image4_order'];
        }
        if (isset($data['image4_order'])&& trim($data['image4_order']) != "") {
            $image5_order = $data['image5_order'];
        }
        
        if (isset($data['video1_order'])&& trim($data['video1_order']) != "") {
            $video1_order = $data['video1_order'];
        }
        if (isset($data['video2_order'])&& trim($data['video2_order']) != "") {
            $video2_order = $data['video2_order'];
        }
                 
        //pr($data);
        $title = trim($data['title']);
        
        $cat_id = $data['add_category'];
        
        $section = isset($data['section']) ? $data['section'] : 0;
        //$image1 = $helper_obj->generate_name() . "." . $image1_type[1]; 
        
       // move_uploaded_file($_FILES["image1"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image1);
            
        //$helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image1, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image1, THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
        
       // $screenshot = $helper_obj->generate_name() . "." . $screenshot_type[1];
       // $video = $helper_obj->generate_name() . "." . $video_type[1];
        
       // move_uploaded_file($_FILES["video"]["tmp_name"], VIDEOS_PATH_UPLOAD . $video);
        
       // move_uploaded_file($_FILES["screenshot"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $screenshot);
            
        //$helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $screenshot, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $screenshot, THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
            
        $pgrate_id = $data['add_pgrate'];  
        //$feature = (isset($data['feature']) ? $data['feature']: 0);
        //$premium = (isset($data['premium']) ? $data['premium']: 0);
        
        $now = time();
        
        $last_id = "";
                               
        $query = "INSERT INTO articles (title, text1, text2, text3, uploaded_date, category_id, image1, image2, image3, image4, image5, 
                                      video1, video2, pg_rated_id, added_by, client_id, text1_order, text2_order, text3_order, image1_order, image2_order, image3_order,
                                      image4_order, image5_order, video1_order, video2_order, section) value
                                     ('$title', '$text1', '$text2', '$text3', '$now', '$cat_id' , '$image1', '$image2', '$image3', '$image4', '$image4', '$image5' 
                                      '$video1', '$video2', '$pgrate_id', '$uid', '$cid', '$text1_order', '$text2_order', '$text3_order', '$image1_order', '$image2_order', 
                                      '$image3_order', '$image4_order', '$image5_order', '$video1_order', '$video2_order', '$section')";
                           //  echo($query);        
        $result =  $this->conn->db_query($query);
        
        $last_id = $this->conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning  
                 
        return $last_id;
    }
    
    function get_vid_by_id($vid) {
        $query = "SELECT v.id, title, description, v.image1, v.image2, image3, image4, screenshot, upload_date, published_date, cat.name cat_name,
                         url, pgr.name pgrate_name, views, featured, v.premium, u.username added_by, cat.id cat_id, pgr.id pgrate_id
                  FROM videos v
                  INNER JOIN categories cat on cat.id = v.category_id
                  INNER JOIN pg_rates pgr on pgr.id = v.pg_rated_id
                  INNER JOIN users u on u.id = v.added_by 
                  WHERE v.id = '$vid'"; 
                                        //echo($query);
        $result =  $this->conn->db_query($query); 
        $video = $this->conn->db_fetch_array($result);
        //$video = $result->fetch_assoc();
        
        return $video;  
    }    
    
    function get_article_by_id($aid) {
        $query = "SELECT v.id, title, v.text1, v.text2, v.text3, v.image5, v.image1, v.image2, image3, image4, uploaded_date, v.video1, v.video2, 
                         cat.name cat_name, pgr.name pgrate_name, views, u.username added_by, cat.id cat_id, pgr.id pgrate_id, text1_order, text2_order, text3_order,
                         image1_order, image2_order, image3_order, image4_order, image5_order, video1_order, video2_order, section
                  FROM articles v
                  INNER JOIN categories cat on cat.id = v.category_id
                  INNER JOIN pg_rates pgr on pgr.id = v.pg_rated_id
                  INNER JOIN users u on u.id = v.added_by 
                  WHERE v.id = '$aid'"; 
                                        //echo($query);
        $result =  $this->conn->db_query($query); 
        $video = $this->conn->db_fetch_array($result);
        //$video = $result->fetch_assoc();
        
        return $video;  
    }
    
    function get_article_html_by_id($aid) {
        /*$query = "SELECT v.id, title, v.body
                  FROM articles_html v
                  INNER JOIN categories cat on cat.id = v.category_id
                  INNER JOIN pg_rates pgr on pgr.id = v.pg_rated_id
                  INNER JOIN users u on u.id = v.added_by 
                  WHERE v.id = '$aid'"; */
                  
        $query = "SELECT *, v.id aid 
                  FROM articles_html v
                  INNER JOIN users u on u.id = v.added_by 
                  WHERE v.id = '$aid'"; 
                                        //echo($query);
        $result =  $this->conn->db_query($query); 
        $video = $this->conn->db_fetch_array($result);
        //$video = $result->fetch_assoc();
          
        return $video;  
    }
    
    function get_all_videos ($cid){
        $query = "SELECT v.id, title, description, v.image1, v.image2, image3, image4, screenshot, upload_date, published_date, cat.name cat_name,
                         url, pgr.name pgrate_name, views, featured, v.premium, u.username added_by, cat.id cat_id, pgr.id pgrate_id
                  FROM videos v
                  INNER JOIN categories cat on cat.id = v.category_id
                  INNER JOIN pg_rates pgr on pgr.id = v.pg_rated_id
                  INNER JOIN users u on u.id = v.added_by
                  WHERE v.client_id = '$cid'
                  ORDER BY upload_date DESC"; 
                  
        $result =  $this->conn->db_query($query); 
        
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $video[] = $row;
        }
           
        return $video;
    }
    
    function get_all_articles ($cid){
        $query = "SELECT v.id, v.title, v.text1, v.text2, v.text3, v.image1, v.image2, v.image3, v.image4, v.image5, v.video1, v.video2, 
                       uploaded_date, cat.name cat_name,
                       pgr.name pgrate_name, views, u.username added_by, cat.id cat_id, pgr.id pgrate_id, 
                       text1_order, text2_order, text3_order,
                       image1_order, image2_order, image3_order, image4_order, image5_order, video1_order, video2_order
                FROM articles v
                INNER JOIN categories cat on cat.id = v.category_id
                INNER JOIN pg_rates pgr on pgr.id = v.pg_rated_id
                INNER JOIN users u on u.id = v.added_by
                WHERE v.client_id = '$cid'
                ORDER BY uploaded_date DESC"; 
                  
        $result =  $this->conn->db_query($query); 
        $articles = array();
        
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $articles[] = $row;
        }
           
        return $articles;
    }
    
    function get_all_articles_html($cid){
     /*   $query = "SELECT v.id, v.title, v.body
                FROM articles v
                INNER JOIN categories cat on cat.id = v.category_id
                INNER JOIN pg_rates pgr on pgr.id = v.pg_rated_id
                INNER JOIN users u on u.id = v.added_by
                WHERE v.client_id = '$aid'
                ORDER BY uploaded_date DESC";*/ 
                
        $query = "SELECT v.*, u.username added_by, v.id id
                FROM articles_html v
                INNER JOIN users u on u.id = v.added_by
                WHERE v.client_id = '$cid'
                ORDER BY v.date_added DESC limit 50"; 
                     //echo($query);
        $result =  $this->conn->db_query($query); 
        $articles = array();
        
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $articles[] = $row;
        }
           
        return $articles;
    }
    
    function delete_video($vid, $cid) {
        $query = "DELETE FROM videos where id = '$vid' and client_id = '$cid'";

        $result =  $this->conn->db_query($query); 
    }
    
    function delete_article($aid, $cid) {
        $query = "DELETE FROM articles where id = '$aid' and client_id = '$cid'";

        $result =  $this->conn->db_query($query); 
    }
    
    function delete_html_article($aid, $cid) {
        $query = "DELETE FROM articles_html where id = '$aid' and client_id = '$cid'";

        $result =  $this->conn->db_query($query); 
    }
    
    function edit_video($data, $cid, $uid) {    //pr($data);
        $title = trim($data['title_updated']);
        $description = trim($data['description_updated']);
        $cat_id = $data['edit_category'];
        $helper_obj = new Helper();
        $image1 = $image2 = $image3 = $image4 = $screenshot = "";
                  
        if (isset($_FILES['image1_updated'])){
            $image1_type = explode(".", $_FILES['image1_updated']['name']);
            $image1 = $helper_obj->generate_name() . '.' . $image1_type[1];
                           
            move_uploaded_file($_FILES["image1_updated"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image1);
                                                                                                          
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image1, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image1, 
                                           THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);            
        } 
        if (isset($_FILES['screenshot_updated'])){
            $screenshot_type = explode(".", $_FILES['screenshot_updated']['name']);
            $screenshot = $helper_obj->generate_name() . '.' . $screenshot_type[1]; 
            
            move_uploaded_file($_FILES["screenshot_updated"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $screenshot);
                                                                                                          
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $screenshot, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $screenshot, 
                                           THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);  
                                            
        } 
        if (isset($_FILES['image2_updated'])){
            $image2_type = explode(".", $_FILES['image2_updated']['name']);
            $image2 = $helper_obj->generate_name() . '.' . $image2_type[1];  
            
            move_uploaded_file($_FILES["image2_updated"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image2);
                                                                                                          
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image2, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image2, 
                                           THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);
        } 
        if (isset($_FILES['image3_updated'])){
            $image3_type = explode(".", $_FILES['image3_updated']['name']);
            $image3 = $helper_obj->generate_name() . '.' . $image3_type[1];
            
            move_uploaded_file($_FILES["image3_updated"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image3);
                                                                                                          
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image3, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image3, 
                                           THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);
                                             
        }
        if (isset($_FILES['image4_updated'])){
            $image4_type = explode(".", $_FILES['image4']['name']);
            $image4 = $helper_obj->generate_name() . '.' . $image4_type[1];  
            
            move_uploaded_file($_FILES["image4_updated"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image4);
                                                                                                          
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image4, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image4, 
                                           THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);
        }
            
        $pgrate_id = $data['edit_pgrate'];
        $featured = isset($data['featured_updated']) ? $data['featured_updated'] : 0;
        $premium = isset($data['premium_updated']) ? $data['premium_updated'] : 0;
        
        $vid = $data['edit_vid'];
        
        $time = time();
        
        $query = "UPDATE videos set title = '$title', description = '$description', category_id = '$cat_id', featured = '$featured', premium = '$premium', 
                                    pg_rated_id = '$pgrate_id', updated_by = '$uid', updated_date = '$time' ";
        
        if ($image1 != "") {
            $query .= " ,image1 = '$image1'";
        }
        
        if ($image2 != "") {
            $query .= " ,image2 = '$image2'";
        }
        
        if ($image3 != "") {
            $query .= " ,image3 = '$image3'";
        }
        
        if ($image4 != "") {
            $query .= " ,image4 = '$i$image4mage1'";
        }
        
        if ($screenshot != "") {
            $query .= " ,screenshot = '$screenshot'";
        }
        
        $query .= " WHERE client_id = '$cid' AND id = '$vid'";
        
        $result =  $this->conn->db_query($query);  
    }
    
    function edit_article($data, $cid, $uid) {    //pr($data);
        $title = trim($data['title_updated']);
       
        $text1_updated = trim($data['text1_updated']);
        $cat_id = $data['edit_category'];
       
        $helper_obj = new Helper();
        $image1 = $image2 = $image3 = $image4 = $image5 = "";
        $text2 = $text3 = $video1 = $video2 = "";
        
        $text1_order = $text2_order = $text3_order = 0;
        $image1_order = $image2_order = $image3_order = $image4_order = $image5_order = 0;
        $video1_order = $vidoe2_order = 0;
        
                  
        if (isset($_FILES['image1_updated'])){
            $image1_type = explode(".", $_FILES['image1_updated']['name']);
            $image1 = $helper_obj->generate_name() . '.' . $image1_type[1];
                           
            move_uploaded_file($_FILES["image1_updated"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image1);
            
            $im = $helper_obj->img_resize(VIDEOS_IMAGES_PATH_UPLOAD . $image1, 620);
            $helper_obj->imageToFile($im, VIDEOS_IMAGES_PATH_UPLOAD . $image1, 100);
                                                                                                          
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image1, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image1, 
                                           THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE); 
                                           
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image1, VIDEOS_IMAGES_PATH_FEATURE_UPLOAD . $image1, 
                                           FEATURE_W, FEATURE_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
        }     
        if (isset($_FILES['image2_updated'])){
            $image2_type = explode(".", $_FILES['image2_updated']['name']);
            $image2 = $helper_obj->generate_name() . '.' . $image2_type[1];  
            
            move_uploaded_file($_FILES["image2_updated"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image2);
            
            $im = $helper_obj->img_resize(VIDEOS_IMAGES_PATH_UPLOAD . $image2, 620);
            $helper_obj->imageToFile($im, VIDEOS_IMAGES_PATH_UPLOAD . $image2, 100);
                                                                                                          
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image2, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image2, 
                                           THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);
                                           
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image2, VIDEOS_IMAGES_PATH_FEATURE_UPLOAD . $image2, 
                                           FEATURE_W, FEATURE_H, THUMBNAIL_R, THUMBNAIL_ENABLE);
        } 
        if (isset($_FILES['image3_updated'])){
            $image3_type = explode(".", $_FILES['image3_updated']['name']);
            $image3 = $helper_obj->generate_name() . '.' . $image3_type[1];
            
            move_uploaded_file($_FILES["image3_updated"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image3);
            
            $im = $helper_obj->img_resize(VIDEOS_IMAGES_PATH_UPLOAD . $image3, 620);
            $helper_obj->imageToFile($im, VIDEOS_IMAGES_PATH_UPLOAD . $image3, 100);
                                                                                                          
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image3, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image3, 
                                           THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);
                                           
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image3, VIDEOS_IMAGES_PATH_FEATURE_UPLOAD . $image3, 
                                           FEATURE_W, FEATURE_H, THUMBNAIL_R, THUMBNAIL_ENABLE);
                                             
        }
        if (isset($_FILES['image4_updated'])){
            $image4_type = explode(".", $_FILES['image4_updated']['name']);
            $image4 = $helper_obj->generate_name() . '.' . $image4_type[1];  
            
            move_uploaded_file($_FILES["image4_updated"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image4);
            
            $im = $helper_obj->img_resize(VIDEOS_IMAGES_PATH_UPLOAD . $image4, 620);
            $helper_obj->imageToFile($im, VIDEOS_IMAGES_PATH_UPLOAD . $image4, 100);
                                                                                                          
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image4, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image4, 
                                           THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE);
                                           
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image4, VIDEOS_IMAGES_PATH_FEATURE_UPLOAD . $image4, 
                                           FEATURE_W, FEATURE_H, THUMBNAIL_R, THUMBNAIL_ENABLE);
        }
        if (isset($_FILES['image5_updated'])){
            $image5_updated_type = explode(".", $_FILES['image5_updated']['name']);
            $image5 = $helper_obj->generate_name() . '.' . $image5_updated_type[1]; 
            
            move_uploaded_file($_FILES["image5_updated"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image5);
            
            $im = $helper_obj->img_resize(VIDEOS_IMAGES_PATH_UPLOAD . $image5, 620);
            $helper_obj->imageToFile($im, VIDEOS_IMAGES_PATH_UPLOAD . $image5, 100);
                                                                                                          
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image5, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image5, 
                                           THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE); 
                                           
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image5, VIDEOS_IMAGES_PATH_FEATURE_UPLOAD . $image5, 
                                           FEATURE_W, FEATURE_H, THUMBNAIL_R, THUMBNAIL_ENABLE); 
                                            
        } 
           
        $pgrate_id = $data['edit_pgrate'];
       // $featured = isset($data['featured_updated']) ? $data['featured_updated'] : 0;
       // $premium = isset($data['premium_updated']) ? $data['premium_updated'] : 0;
        
        $vid = $data['edit_article'];
        $section = $data['section'];
        
        $time = time();
        
        $query = "UPDATE articles set title = '$title', text1 = '$text1_updated', category_id = '$cat_id',
                                    pg_rated_id = '$pgrate_id', updated_by = '$uid', updated_date = '$time' ";
        
        if ($image1 != "") {
            $query .= " ,image1 = '$image1'";
        }
        
        if ($image2 != "") {
            $query .= " ,image2 = '$image2'";
        }
        
        if ($image3 != "") {
            $query .= " ,image3 = '$image3'";
        }
        
        if ($image4 != "") {
            $query .= " ,image4 = '$image4'";
        }
        
        if ($image5 != "") {
            $query .= " ,image5 = '$image5'";
        }
        
        if (isset($data['text1_updated']) && trim($data['text1_updated']) != "") {
            $text1 = $data['text1_updated'];
            $query .= " ,text1 = '$text1'";
        }                 
        if (isset($data['text2_updated']) /*&& trim($data['text2_updated']) != ""*/) {
            $text2 = $data['text2_updated'];
            $query .= " ,text2 = '$text2'";
        }
        if (isset($data['text3_updated']) /*&& trim($data['text3_updated']) != ""*/) {
            $text3 = $data['text3_updated'];
            $query .= " ,text3 = '$text3'";
        }
        
        if (isset($data['video1_updated']) /*&& trim($data['video1_updated']) != ""*/) {
            $video1 = $data['video1_updated'];
            $query .= " ,video1 = '$video1'";
        }
        if (isset($data['video2_updated']) /*&& trim($data['video2_updated']) != ""*/) {
            $video2 = $data['video2_updated'];
            $query .= " ,video2 = '$video2'";
        }
        
        if (isset($data['text1_order_updated']) && trim($data['text1_order_updated']) != "") {
            $text1_order = $data['text1_order_updated'];
            $query .= " ,text1_order = '$text1_order'"; 
        }
        if (isset($data['text2_order_updated'])&& trim($data['text2_order_updated']) != "") {
            $text2_order = $data['text2_order_updated'];
            $query .= " ,text2_order = '$text2_order'";  
        }
        if (isset($data['text3_order_updated'])&& trim($data['text3_order_updated']) != "") {
            $text3_order = $data['text3_order_updated'];
            $query .= " ,text3_order = '$text3_order'"; 
        }
        
        if (isset($data['image1_order_updated']) /*&& trim($data['image1_order_updated']) != ""*/) {
            $image1_order = $data['image1_order_updated'] == "" ? 0 : $data['image1_order_updated'];
            $query .= " ,image1_order = '$image1_order'";
        }
        if (isset($data['image2_order_updated']) /*&& trim($data['image2_order_updated']) != ""*/) {
            $image2_order = $data['image2_order_updated'] == "" ? 0 : $data['image2_order_updated'];
            $query .= " ,image2_order = '$image2_order'";
        }
        if (isset($data['image3_order_updated'])/*&& trim($data['image3_order_updated']) != ""*/) {
            $image3_order = $data['image3_order_updated'] == "" ? 0 : $data['image3_order_updated'];      
            $query .= " ,image3_order = '$image3_order'"; 
        }
        if (isset($data['image4_order_updated'])/*&& trim($data['image4_order_updated']) != ""*/) {
            $image4_order = $data['image4_order_updated'] == "" ? 0 : $data['image4_order_updated'];
            $query .= " ,image4_order = '$image4_order'"; 
        }
        if (isset($data['image4_order_updated'])/*&& trim($data['image4_order_updated']) != ""*/) {
            $image5_order = $data['image5_order_updated'] == "" ? 0 : $data['image5_order_updated']; 
            $query .= " ,image5_order = '$image5_order'"; 
        }
        
        if (isset($data['video1_order_updated'])/*&& trim($data['video1_order_updated']) != ""*/) {
            $video1_order = $data['video1_order_updated'] == "" ? 0 : $data['video1_order_updated']; 
            $query .= " ,video1_order = '$video1_order'"; 
        }
        if (isset($data['video2_order_updated'])/*&& trim($data['video2_order_updated']) != ""*/) {
            $video2_order = $data['video2_order_updated'] == "" ? 0 : $data['video2_order_updated']; 
            $query .= " ,video2_order = '$video2_order'";
        }
        
        $query .= " ,section = '$section'";
        
        $query .= " WHERE client_id = '$cid' AND id = '$vid'";
             // echo($query);
        $result =  $this->conn->db_query($query);  
    }
    
    function edit_html_article($data, $cid, $uid) {  
        $title = trim($data['title_updated']);
        //$video = trim($data['video_updated']);
        $body = trim($data['ck_updated']);
                         
        //$cat_id = $data['edit_category'];
       
        $helper_obj = new Helper();
        $db_functions_obj = new DbFunctions();
        $tag_obj = new Tag();
        $cat_obj = new Category();
        
        $image1  = "";
               
        if (isset($_FILES['image1_updated'])){
            $image1_type = explode(".", $_FILES['image1_updated']['name']);
            $image1 = $helper_obj->generate_name() . '.' . $image1_type[1];
                           
            move_uploaded_file($_FILES["image1_updated"]["tmp_name"], VIDEOS_IMAGES_PATH_UPLOAD . $image1);
            
            $im = $helper_obj->img_resize(VIDEOS_IMAGES_PATH_UPLOAD . $image1, 620);
            $helper_obj->imageToFile($im, VIDEOS_IMAGES_PATH_UPLOAD . $image1, 100);
                                                                                                          
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image1, VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD . $image1, 
                                           THUMBNAIL_W, THUMBNAIL_H, THUMBNAIL_R, THUMBNAIL_ENABLE); 
                                           
            $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image1, VIDEOS_IMAGES_PATH_FEATURE_UPLOAD . $image1, 
                                           FEATURE_W, FEATURE_H, THUMBNAIL_R, THUMBNAIL_ENABLE);   
                                           
            //woman app
            if ($cid == 49) {           
                $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image1, VIDEOS_IMAGES_THUMBNAIL_320x272_PATH_UPLOAD . $image1, 
                                               IMAGE_320x272_W, IMAGE_320x272_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
            
                $helper_obj->resize_crob_image(VIDEOS_IMAGES_PATH_UPLOAD . $image1, VIDEOS_IMAGES_THUMBNAIL_320x352_PATH_UPLOAD . $image1, 
                                           IMAGE_320x352_W, IMAGE_320x352_H, THUMBNAIL_R, THUMBNAIL_ENABLE);
            }        
        }     
        
        $pgrate_id = $data['edit_pgrate'];
        
        $vid = $data['edit_article'];
        $section = $data['section_updated'];
        
        $time = time();
        
        /*$query = "UPDATE articles_html set title = '$title', body = '$body', category_id = '$cat_id',
                                    pg_rated_id = '$pgrate_id', updated_by = '$uid', updated_date = '$time', video = '$video' ";*/
                                    
        $query = "UPDATE articles_html set title = '$title', body = '$body',
                                    pg_rated_id = '$pgrate_id', updated_by = '$uid', updated_date = '$time' ";
        
        if ($image1 != "") {
            $query .= " ,image = '$image1'";
        }
              
        $query .= " ,section = '$section'";
        
        $query .= " WHERE client_id = '$cid' AND id = '$vid'";
           //   echo($query);
           
        //update tags
        //1- delete old tags
        //2- add new tags
        $db_functions_obj->delete_article_tags($vid);
      
        foreach($data['edit_tag'] as $tag_id) {
            $tag_obj->add_tags_to_article($vid, $tag_id); 
        }
        
        $this->delete_article_categories($vid);
      
        foreach($data['edit_category'] as $cid) {
            $cat_obj->add_categories_to_article($vid, $cid); 
        }
        
        $result =  $this->conn->db_query($query);  
    }
    
    function delete_article_tags($aid){
        $query = "delete from article_tags where aid = '$aid'";
        $result =  $this->conn->db_query($query);
    }
    
    function delete_article_categories($aid){
        $query = "delete from article_categories where aid = '$aid'";
        $result =  $this->conn->db_query($query);
    }
    
    function get_pgrates() {
        $query = "SELECT id, name FROM pg_rates ORDER BY id DESC";
        $result =  $this->conn->db_query($query);
        
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $pgrates[] = $row;
        }
           
        return $pgrates;  
    } 
    
    function get_operators() {
        $query = "SELECT operators.id, operators.name op_name, countries.name country_name,
                           country_id, paid_shortcode, free_shortcode, type
                    FROM operators 
                    inner join countries on countries.id = operators.country_id
                    ORDER BY operators.id DESC";
        $result =  $this->conn->db_query($query);
        
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $operators[] = $row;
        }
           
        return $operators;  
    }
    
    function delete_pgrate($id) {
        $query = "DELETE from pg_rates where id = '$id'";
        $result =  $this->conn->db_query($query);
    }
    
    function add_pgrate($data) {
       $pgrate_name = trim($data['pgrate_name']);
       
       if ($pgrate_name != "") {
           $query = "INSERT INTO pg_rates (name) value ('$pgrate_name')"; 
           $result =  $this->conn->db_query($query);
           
           $last_id = $this->conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning  
                 
           return $last_id;
       }
    }                     
    
    function get_pgrate_by_id($id) {
        $query = "SELECT id, name FROM pg_rates WHERE id = '$id'";
        $result =  $this->conn->db_query($query);
        $row = $this->conn->db_fetch_array($result);
        //$row = $result->fetch_assoc();
        return array('id' => $row['id'], 'name' => $row['name']);
    }
    
    function edit_pgrate($name, $id){
        $query = "UPDATE pg_rates set name = '$name' where id = '$id'";
        $result =  $this->conn->db_query($query);  
    }
    
    function add_category($data) {
        $helper_obj = new Helper();
             
        $name = trim($data['category_name']);
        $parent = trim($data['add_category']);
        $premium = (isset($data['premium']) ? $data['premium']: 0); 
        
        $image1_type = explode(".", $_FILES['image1']['name']);
        $image1 = $helper_obj->generate_name() . '.' . $image1_type[1];  
        
        //move_uploaded_file($_FILES["image1"]["tmp_name"], CATEGORIES_IMAGES_PATH_UPLOAD . $image1);
        
        //$helper_obj->resize_crob_image(CATEGORIES_IMAGES_PATH_UPLOAD . $image1, CATEGORIES_IMAGES_PATH_UPLOAD . $image1, CATEGORY_IMAGE_W, CATEGORY_IMAGE_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
        $gs_name = $_FILES["image1"]["tmp_name"];
       // $move = copy($gs_name, 'gs://' . GOOGLE_APP_ID . '/cat/'.$image1.'');
      //  $object_image_file1 = 'gs://' . GOOGLE_APP_ID . '/cat/'.$image1.'';
      //  $object_image_url1 = CloudStorageTools::getImageServingUrl($object_image_file1, ['size' => 200, 'crop' => false]);
            
        $image2 = "";
        
        if (isset($_FILES['image2'])){
            /*$image2_type = explode(".", $_FILES['image2']['name']);
            $image2 = $helper_obj->generate_name() . '.' . $image2_type[1];  
            
            move_uploaded_file($_FILES["image2"]["tmp_name"], CATEGORIES_IMAGES_PATH_UPLOAD . $image2);
            
            $helper_obj->resize_crob_image(CATEGORIES_IMAGES_PATH_UPLOAD . $image2, CATEGORIES_IMAGES_PATH_UPLOAD . $image2, CATEGORY_IMAGE_W, CATEGORY_IMAGE_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
            */
        } 
        
        $uv = 0;
        
        $max_av_query = "select max(av) av from categories"; 
        $result_av =  $this->conn->db_query($max_av_query); 
        $row_av = $this->conn->db_fetch_array($result_av);
        //$row_av = $result_av->fetch_assoc();
        $av = ($row_av['av'] == "" ? 0 : ($row_av['av']+1) );
        
        $query = "INSERT INTO categories (name, image1, parent, image2, premium, uv, av, sort) value ('$name', '$image1', '$parent', '$image2', '$premium', '$uv', '$av', '999')";
        $result =  $this->conn->db_query($query);  
        $last_id = $this->conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning  
        
        $update_group_query = "update categories set `group` = '$last_id' where id = '$last_id'";  
        $result =  $this->conn->db_query($update_group_query);
        
        return $last_id;
    }
    
    function add_source($data) {
        $helper_obj = new Helper();
             
        $parent = trim($data['add_category']);
        $source_link = trim($data['source_link']);
        $source_type = trim($data['source_type']);
        
        $query = "INSERT INTO rss_news (category_id, link, type) value ('$parent', '$source_link', '$source_type')";
        $result =  $this->conn->db_query($query);  
        $last_id = $this->conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning  
        
        return $last_id;
    }
    
    function add_tag($data) {
        $helper_obj = new Helper();
        
        $name = trim($data['tag_name']);
        
        $image1_type = explode(".", $_FILES['image']['name']);
        $image1 = $helper_obj->generate_name() . '.' . $image1_type[1];  
        
        
        move_uploaded_file($_FILES["image"]["tmp_name"], TAGS_THUMBNAIL_IMAGES_PATH_UPLOAD . $image1);     
       
       //200x200   
        $helper_obj->resize_crob_image(TAGS_THUMBNAIL_IMAGES_PATH_UPLOAD . $image1, TAGS_THUMBNAIL_IMAGES_PATH_UPLOAD . $image1, TAG_THUMBNAIL_IMAGE_W, TAG_THUMBNAIL_IMAGE_H, 
                                       THUMBNAIL_R, THUMBNAIL_ENABLE); 
                                       
        //400x200 
        $helper_obj->resize_crob_image(TAGS_THUMBNAIL_IMAGES_PATH_UPLOAD . $image1, TAGS_IMAGES_PATH_UPLOAD . $image1, TAG_IMAGE_W, TAG_IMAGE_H, THUMBNAIL_R, THUMBNAIL_ENABLE);           
       
                                       
        $query = "INSERT INTO tags (name, image) value ('$name', '$image1')";
        $result =  $this->conn->db_query($query);  
        $last_id = $this->conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning  
        
        return $last_id;
    }
    
    function add_hitd($data) {
        $helper_obj = new Helper();
             
        $date = trim($data['textfield']);  
        $body = trim($data['textarea']);  
        $last_id = 0;
        if($date != '' && $body != ''){
            $query = "INSERT INTO happened_in_this_day (body,date) value ('$body', '$date')";
            $result =  $this->conn->db_query($query);  
            $last_id = $this->conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning  
        }
        return $last_id;
    }
    
    function get_category_by_id($id) {
        $query = "SELECT id, name, image1, image2, premium, parent FROM categories WHERE id = '$id'";
        $result =  $this->conn->db_query($query);
        $row = $this->conn->db_fetch_array($result);
        //$row = $result->fetch_assoc();
        return $row;
    } 
    
    function get_source_by_id($id) {
        $query = "SELECT rss_news.id, category_id, link, type, categories.name 
                  FROM rss_news 
                  inner join categories on categories.id = rss_news.category_id
                  WHERE rss_news.id = '$id'";
        $result =  $this->conn->db_query($query);
        $row = $this->conn->db_fetch_array($result);
        //$row = $result->fetch_assoc();
        return $row;
    }
    
    function get_tag_by_id($id) {
        $query = "SELECT id, name, image FROM tags WHERE id = '$id'";
        $result =  $this->conn->db_query($query);
        $row = $this->conn->db_fetch_array($result);
        //$row = $result->fetch_assoc();
        return $row;
    }
    
    function get_hitd_by_id($id) {
        $query = "SELECT id, date, body FROM happened_in_this_day WHERE id = '$id'";
        $result =  $this->conn->db_query($query);
        $row = $this->conn->db_fetch_array($result);
        //$row = $result->fetch_assoc();
        return $row;
    }
    
    function delete_category($cid) {
        $query = "DELETE FROM categories where id = '$cid'";
        $result =  $this->conn->db_query($query); 
    }
    
    function delete_source($cid) {
        $query = "DELETE FROM rss_news where id = '$cid'";
        $result =  $this->conn->db_query($query); 
    } 
    
    function delete_tag($tid) {
        $query = "DELETE FROM tags where id = '$tid'";
        $result =  $this->conn->db_query($query); 
    } 
    
    function delete_all_article_belongs_to_tag($tid) {
        $query = "DELETE FROM article_tags where tid = '$tid'";
        $result =  $this->conn->db_query($query); 
    }
    
    function delete_hitd($cid) {
        $query = "DELETE FROM happened_in_this_day where id = '$cid'";
        $result =  $this->conn->db_query($query); 
    }
    
    function edit_category($cid, $name, $new_image1, $new_image2, $premium, $parent){
    //function edit_category($cid, $name, $new_image1, $new_image2, $premium){
        $name = trim($name);
        
        $max_uv_query = "select max(uv) uv from categories";       
        $result_uv =  $this->conn->db_query($max_uv_query);
        $row_uv = $this->conn->db_fetch_array($result_uv);
        //$row_uv = $result_uv->fetch_assoc();
        $uv = ($row_uv['uv'] == "" ? 0 : ($row_uv['uv']+1) );
                       
        $query = "UPDATE categories set parent = '$parent', name = '$name', premium = '$premium', uv = '$uv'";
       // $query = "UPDATE categories set name = '$name', premium = '$premium', uv = '$uv'";
        
        if (!empty($new_image1)) {
            $query .= " ,image1 = '$new_image1'";
        }
        
        if (!empty($new_image2)) {
            $query .= " ,image2 = '$new_image2'";
        }
        
        $query .= " WHERE id = '$cid'";
             // echo $query;
        $result =  $this->conn->db_query($query); 
    }
    
    function edit_tag($tid, $name, $image1){
        $name = trim($name);
       
        $query = "UPDATE tags set name = '$name', image = '$image1'";
                
        $query .= " WHERE id = '$tid'";
        
        $result =  $this->conn->db_query($query); 
    }
    
    function edit_hitd($id, $body, $date){
       
        $query = "UPDATE happened_in_this_day set body = '$body', date = '$date'";
          
        $query .= " WHERE id = '$id'";
                 //echo $query;
        $result =  $this->conn->db_query($query); 
    }
    
    function vaild_user_menu ($menu_id, $cid){
        
        $query = "SELECT count(id) c FROM menu_clients WHERE menu_id='$menu_id' AND cid='$cid'";
        $result =  $this->conn->db_query($query);
        $row = $this->conn->db_fetch_array($result);
        //$row = $result->fetch_assoc();
        //echo"$query";
        
        $valid = false;
        
        if($row->c){
            $valid = true;
        }
    
        return $valid;
           
    }
    
    function get_menu_by_url($url){      
        $query = "SELECT id FROM menu WHERE url='$url' ";
        $result =  $this->conn->db_query($query);
        $row = $this->conn->db_fetch_array($result);
        //$row = $result->fetch_assoc();
        return $row;     
    }
    
    function get_all_currencies(){
        
        $query = "SELECT c.id, c.code,  c.name, c.sign
                    FROM currencies c 
                    ";
          $condition = false;
          
          
          $query .= " ORDER BY c.name DESC";
                  
          $result =  $this->conn->db_query($query);
           
          while($row = $this->conn->db_fetch_array($result)) {
          //while($row = $result->fetch_assoc()){
              $currencies[] = $row;
          }
          
          return $currencies;
        
    }
    
    function get_currency_by_id($id){
        
        $query = "SELECT id, code, name, sign FROM currencies WHERE id='$id' ";
        $result =  $this->conn->db_query($query);
        $row = $this->conn->db_fetch_array($result);
        //$row = $result->fetch_assoc();
        return array('id' => $row['id'], 'code' => $row['code'], 'name' => $row['name'], 'sign' => $row['sign']);
        
        
    }
    
    function delete_currency($id) {
        $query = "DELETE from currencies where id = '$id'";
        $result =  $this->conn->db_query($query);
    }
    
    function edit_currency($id, $code, $name, $sign) {
        
        //$query = "UPDATE currencies SET code = '$code' name = '$name' sign = '$sign' WHERE id = '$id'";
        $query = "UPDATE currencies set code = '$code' ,name = '$name', sign = '$sign' where id = '$id'";
        $result =  $this->conn->db_query($query);
            echo"edit currency db";  
       // $result =  $this->conn->db_query($query);
      //  pr($result);
        
    }
    
    function add_currency($code, $name, $sign) { 
       
       
       if (($code != "") && ($name != "")) {
           $query = "INSERT INTO currencies (code, name, sign) value ('$code', '$name', '$sign')"; 
           $result =  $this->conn->db_query($query);
           
           $last_id = $this->conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning  
                 
           return $last_id;
       }
    }
    
        function get_all_operators(){
        
        $query = "SELECT o.id, o.name, o.country_id
                    FROM operators o 
                    ";
          $condition = false;
          
          
          $query .= " ORDER BY o.name DESC";
                  
          $result =  $this->conn->db_query($query);
           
          while($row = $this->conn->db_fetch_array($result)) {
          //while($row = $result->fetch_assoc()){
              $operators[] = $row;
          }
          
          return $operators;
        
    }
    
     function get_country_name_by_id($id){
        
        $query = "SELECT name FROM countries WHERE id='$id' ";
        $result =  $this->conn->db_query($query);
        $row = $this->conn->db_fetch_array($result);
        //$row = $result->fetch_assoc();
        return $row;
        
    }
    
    function add_operator($operator_name, $country_id, $paid_shortcode, $free_shortcode, $type) { 
       
       
       if (($operator_name != "") && ($country_id != "")) {
           $query = "INSERT INTO operators ( name, country_id, paid_shortcode, free_shortcode, type) value ('$operator_name', '$country_id', '$paid_shortcode', '$free_shortcode', '$type')"; 
           $result =  $this->conn->db_query($query);
                   
           $last_id = $this->conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning  
                 
           return $last_id;
       }
    }
    
    function get_operator_by_id($id){
        
        $query = "SELECT id, name, country_id, paid_shortcode, free_shortcode, type, paid_smsc, free_smsc, port, period FROM operators WHERE id='$id' ";
        $result =  $this->conn->db_query($query);
        $row = $this->conn->db_fetch_array($result);
        //$row = $result->fetch_assoc();
                 
        $country = $this->get_country_name_by_id($row['country_id']); 
        
        
        return array('id' => $row['id'], 'name' => $row['name'], 'country_id' => $row['country_id'], 'country_name' => $country['name'], 'paid_shortcode' => $row['paid_shortcode'],
                     'free_shortcode' => $row['free_shortcode'], 'type' => $row['type'], 'paid_smsc' => $row['paid_smsc'], 'free_smsc' => $row['free_smsc'], 'port' => $row['port'],
                     'period' => $row['period']);
        
        
    }
    
    function delete_operator($id) {
        $query = "DELETE from operators where id = '$id'";
        $result =  $this->conn->db_query($query);
    }
    
    function edit_operator($operator_id, $operator_name, $paid_shortcode, $free_shortcode, $type) {        
        
        $query = "UPDATE operators set name = '$operator_name', free_shortcode = '$free_shortcode', paid_shortcode = '$paid_shortcode', type = '$type' 
                  where id = '$operator_id'";
        $result =  $this->conn->db_query($query);
                   
    }
    
    function get_new_verification_code($uid) {
        $code = rand(999, 9999);
        
        $query = "update users set verification_code = '$code' where id = '$uid'";
        $this->conn->db_query($query);
        
        return $code;
    }
    
    function activate_user($uid) {
        $query = "update users set status = 1 where id = '$uid'";
        $this->conn->db_query($query);
    }
    
    function deactivate_user($uid) {
        $query = "update users set status = 0 where id = '$uid'";
        $this->conn->db_query($query);
    }
    
    function get_payment_log_by_uid($id) {
        /*$query = "select * from payment_log 
                  inner join users payment_log.uid = users.id
                  where id = '$id'";*/
                  
        $query = "select * from payments 
                  inner join users on payments.uid = users.id
                  where users.id = '$id'";
                  
                  
        $res = $this->conn->db_query($query);
        $row = $this->conn->db_fetch_array($res);
        //$row = $result->fetch_assoc();
        
        return $row;   
    }
    
    function check_if_phone_exists($phone) {
        $query = "select count(id) c from users where phone = '$phone'";    
        //$query = "select count(id) c from verification_code where phone = '$phone'";    
        $res = $this->conn->db_query($query);
        $row = $this->conn->db_fetch_array($res);
        //$row = $result->fetch_assoc();
        
        return $row->c; 
    }
    
    function verify_code($uid, $code) {
         //$query = "select count(id) c from users where verification_code = '$code' and phone = '$phone'";
         //$query = "select count(id) c from verification_code where code = '$code' and phone = '$phone'"; 
         $query = "select count(id) c from payments where code = '$code' and uid = '$uid'";
         
         //echo($query);
         $res = $this->conn->db_query($query);
         $row = $this->conn->db_fetch_array($res);
         //$row = $result->fetch_assoc();
        
         return $row->c; 
    }      
    
    function get_expired_users($cid, $tries = 0, $activated = true) {
         $now = time();
        
         $query = "select * from users 
                    inner join payments on payments.uid = users.id
                    inner join users_roles on users_roles.uid = users.id 
                    where end_date <= '$now' and cid = '$cid'";
         
         if ($tries != 0) {           
              $query .= " and repayment_count <= " . $tries;
         }
         
         if (!$activated) {
             $query .= " and users.status = 1";
         }
              
         $res = $this->conn->db_query($query);        
               //echo($query .'<br />');
               
         $expired = array();
         
         while($row = $this->conn->db_fetch_array($res)){
         //while($row = $res->fetch_assoc()){
             $expired[] = $row;
         }   
           
         return $expired;
    }   
    
    function update_payment($uid, $start_date, $end_date) { 
        
        $query = "update payments set date_added = '$start_date', end_date = '$end_date'
                  where
                  uid = '$uid'";
                 //   echo($query.'<br />');    
        $this->conn->db_query($query); 
        
    }
    
    function get_operator_details($id) {
        $query = "select * from operators where id = '$id'";
        
        $res = $this->conn->db_query($query);        
        
        $data = array(); 
         
        while($row = $this->conn->db_fetch_array($res)){
        //while($row = $res->fetch_assoc()){
             $data[] = $row;
        }   
            
        return $data;
    }
    
    function get_phone_details($phone, $cid = "") {
        $query = "select * from payments 
                  inner join users on users.id = payments.uid
                  inner join users_roles on users_roles.uid = users.id
                  where users.phone = '$phone'";   
            // echo($query);
        //$query = "select * from verification_code where phone = '$phone'"; 
        
        if ($cid != "") {
            $query .= " and users_roles.cid = '$cid'";
        }
                        
        $res = $this->conn->db_query($query);        
        
        $data = array(); 
         
        while($row = $this->conn->db_fetch_array($res)){
        //while($row = $res->fetch_assoc()){
             $data[] = $row;
        }   
            
        return $data;
    }
    
    function check_user_verification_code($phone, $email, $code) {
        $query = "select count(id) c from payments where phone = '$phone' and email = '$email' and code = '$code'";
        
        $res = $this->conn->db_query($query);        
        
        $data = array(); 
         
        while($row = $this->conn->db_fetch_array($res)){
        //while($row = $res->fetch_assoc()){
             $c = $row['c'];
        }   
              
        return $c;    
    }
    
    function check_if_user_exists_in_verfication_table($phone, $email){
        $query = "select count(id) c from payments where phone = '$phone' and email = '$email'";
        $res = $this->conn->db_query($query);        
        
        $data = array(); 
         
        while($row = $this->conn->db_fetch_array($res)){
        //while($row = $res->fetch_assoc()){
             $c = $row['c'];
        }   
              
        return $c;  
    }
    
    function delete_ver_code($phone, $email){
        $query = "delete from payments where phone = '$phone' and email = '$email'";
        
        $this->conn->db_query($query);
    }
    
    /*function get_expired_users() {
        $now = time();
        
        $query = "select * from payments where end_date <= $now and repayment_count < 10"; //9 times for 3 days each day 3 times every 8 hours
        
        $res = $this->conn->db_query($query);        
        
        $data = array(); 
         
        while($row = $this->conn->db_fetch_array($res)){
             $data[] = $row;
        }   
            
        return $data;
    }   */
    
    function update_repayment_count($id = '', $phone = '', $email = '') {
        
        if ($id == "") {
            //$query = "update payments set repayment_count = repayment_count + 1 where phone = '$phone' and email = '$email'"; 
            $query = "update payments set repayment_count = repayment_count + 1 where uid = '$phone'"; 
        }  
        else {
            $query = "update payments set repayment_count = repayment_count + 1 where id = '$id'"; 
        }
           //  echo($query);
        $this->conn->db_query($query);
    }
    
    function reset_payment_count($uid) {
        $query = "update payments set repayment_count = 0 where uid = '$uid'"; 
                   
         $this->conn->db_query($query);    
    }       
    
    function get_uid_by_phone($phone, $cid = "") {
        $query = "select id from users where phone = '$phone'";
         
        //$query = "select id from verification_code where phone = '$phone'";
                 //    echo($query.'<br />');
        $res = $this->conn->db_query($query);  
        
        while($row = $this->conn->db_fetch_array($res)) {
        //while($row = $res->fetch_assoc()){
            if ($cid != "") { 
                $query1 = "select uid from users_roles where uid = '" . $row['id'] . "' and cid = '$cid'";
                $res1 = $this->conn->db_query($query1);
                $row1 = $this->conn->db_fetch_array($res1);
                $user_id = $row1['uid']; 
            }
            else {
                $user_id = $row['id'];
            }
        }
                   
        return $user_id;
    }                                                              
    
     function forget_password($email){
        $new_pass = generate_name();
        $new_pass_md5 = md5($new_pass);
        
        send("ayman@a.com", "ayman", $email, $email, "Forget Password", "Your new password is: " . $new_pass, "", "", "", "");
        $query = "update users set password = '$new_pass_md5' where email = '$email'";
        $res = $this->conn->db_query($query);
        
        return $res;
    }
    
    function change_password($email, $old_password, $new_password) {
        $new_password = md5($new_password);
        $old_password = md5($old_password);
        
        $query = "update users set password = '$new_password' where password = '$old_password' and email = '$email'";
        $res = $this->conn->db_query($query);
        
        return $res;
    }
    
    function get_all_rss_sources(){ 
        $query = "select * from rss_news order by id desc";
        $res = $this->conn->db_query($query); 
        
        while($data = $this->conn->db_fetch_array($res)){
        //while($row = $res->fetch_assoc()){
            $rss_sources[] = $data;
        }
        
        return $rss_sources;
    }
    
    function add_rss_source($data) {
        $name = $data['rss_name'];
        $link = $data['rss_link'];
        
        $query = "insert into rss_news (source_name, link) value ('$name', '$link')";
        $res = $this->conn->db_query($query); 
        
        return $this->conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning 
    }
    
    function get_rss_source_by_id($id) {
        $query = "select * from rss_news where id = '$id'";
        $res = $this->conn->db_query($query); 
        
        $data = $this->conn->db_fetch_array($res);
        //$data = $res->fetch_assoc();
            
        return $data;
    }
    
    function delete_rss_source($id) {
        $query = "delete from rss_news where id = '$id'";
        $res = $this->conn->db_query($query);
    }
    
    function edit_rss_source($rss_id, $name, $link) {
        $query = "update rss_news set source_name = '$name', link = '$link' where id = '$rss_id'";
        $res = $this->conn->db_query($query); 
    }
    
    function get_country_category_rss_source(){
        $query = "select 
                    categories_by_countries.id,
                    countries.name country_name,
                    categories.name category_name,
                    rss_news.source_name source_name
                    from categories_by_countries
                    inner join countries on countries.id = categories_by_countries.country_id
                    inner join categories on categories.id = categories_by_countries.category_id
                    inner join rss_news on rss_news.id = categories_by_countries.source_id
                    order by country_name";
                  
        $res = $this->conn->db_query($query); 
        
        while($data = $this->conn->db_fetch_array($res)){
        //while($data = $res->fetch_assoc()){
            $rss_sources[] = $data;
        }
        
        return $rss_sources;     
    }
    
    function get_country_category_rss_source_by_id($id){
        $query = "select 
                    categories_by_countries.id,
                    countries.name country_name,
                    categories.name category_name,
                    rss_news.source_name source_name
                    from categories_by_countries
                    inner join countries on countries.id = categories_by_countries.country_id
                    inner join categories on categories.id = categories_by_countries.category_id
                    inner join rss_news on rss_news.id = categories_by_countries.source_id
                    where categories_by_countries.id = '$id'
                    order by country_name";
                    
        $res = $this->conn->db_query($query); 
        
        $data = $this->conn->db_fetch_array($res);
        //$data = $res->fetch_assoc();
        
        return $data;     
    }
    
    function add_country_category_rss($data){
        $country_id = $data['add_country'];
        $category_id = $data['add_category'];
        $rss_source_id = $data['add_rss_source'];
        
        $query = "insert into categories_by_countries (country_id, category_id, source_id) value ('$country_id', '$category_id', '$rss_source_id')";
        $res = $this->conn->db_query($query);
        
        return $this->conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning 
    }
    
    function delete_country_category_rssSource($id) {
        $query = "delete from categories_by_countries where id = '$id'";
        $res = $this->conn->db_query($query); 
    }
    
    
    function add_emergency($data) {
        $helper_obj = new Helper();
             
        $name = trim($data['name']);  
        $phone = trim($data['phone']);  
        $country_id = trim($data['emergency_country']);  
        $last_id = 0;
        if($name != '' && $phone != '' && $country_id != ''){
            $query = "INSERT INTO emergency_calls (phone,name,country_id) value ('$phone', '$name','$country_id')";
           
            $result =  $this->conn->db_query($query);  
            $last_id = $this->conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning  
        }
        return $last_id;
    }
    
    function get_emergency_by_id($id) {
        $query = "SELECT emergency_calls.id, emergency_calls.name, phone, country_id, countries.name country_name 
                  FROM emergency_calls 
                  inner join countries on countries.id = emergency_calls.country_id
                  WHERE id = '$id'";
        $result =  $this->conn->db_query($query);
        $row = $this->conn->db_fetch_array($result);
        //$row = $result->fetch_assoc();
            
        return $row;
    }
    
    function delete_emergency($id) {
        $query = "DELETE FROM emergency_calls where id = '$id'";
        $result =  $this->conn->db_query($query); 
    }
    
     function edit_emergency($id, $name, $phone, $country_id){
       
        $query = "UPDATE emergency_calls set phone = '$phone', name = '$name', country_id = '$country_id'";
          
        $query .= " WHERE id = '$id'";
        $result =  $this->conn->db_query($query); 
    }
    function get_all_emergencys(){
        $query = "SELECT emergency_calls.id, emergency_calls.name, phone, country_id, countries.name country_name  
                  FROM emergency_calls 
                  inner join countries on countries.id = emergency_calls.country_id
                  ORDER BY id DESC";
        $result =  $this->conn->db_query($query);
        $emergencys = array();
       
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $emergencys[] = $row;
        }     
            
        return $emergencys; 
    }
    
    function add_zodiac($data) {
        $helper_obj = new Helper();
             
        $name = trim($data['name']);  
        $body = trim($data['body']);  
        $last_id = 0;
        if($name != '' && $body != ''){
        
            $query = $this->conn->db_query("UPDATE zodiac set status = '0' where name = '$name'");
            $query = "INSERT INTO zodiac (name,body) value ('$name', '$body')";
            
            $result =  $this->conn->db_query($query);  
            $last_id = $this->conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning  
        }
        return $last_id;
    }
    
    function get_zodiac_by_id($id) {
        $query = "SELECT id, name, body, status FROM zodiac WHERE id = '$id'";
        $result =  $this->conn->db_query($query);
        $row = $this->conn->db_fetch_array($result);
        //$row = $result->fetch_assoc();
        return $row;
    }
    
    function delete_zodiac($id) {
        $query = "DELETE FROM zodiac where id = '$id'";
        $result = $this->conn->db_query($query); 
    }
    
    function edit_zodiac($id, $body){
       
        $query = "UPDATE zodiac set body = '$body'";
          
        $query .= " WHERE id = '$id'";
        $result =  $this->conn->db_query($query); 
    }
    
    function get_all_zodiacs(){
        $query = "SELECT id, name, body FROM zodiac WHERE status=1 ORDER BY id DESC";
        $result =  $this->conn->db_query($query);
        $zodiacs = array();
       
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $zodiacs[] = $row;
        }     
            
        return $zodiacs; 
    }
    
    function get_categories_not_assigned_to_client($cid) {
        $query = "select * from categories where id not in (
                  select category_id from categories_by_clients
                  inner join categories on categories.id = categories_by_clients.category_id
                  where client_id = '$cid' )";
                  
        $result =  $this->conn->db_query($query);
        
        $data = array();
       
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $data[] = $row;
        }     
            
        return $data;
    } 
    
    function get_categories_assigned_to_client($cid) {
        $query = "select category_id id, name from categories_by_clients
                  inner join categories on categories.id = categories_by_clients.category_id
                  where client_id = '$cid'";
                  
        $result =  $this->conn->db_query($query);
        
        $data = array();
       
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $data[] = $row;
        }     
            
        return $data;
    }
    
    function add_category_to_client($cat_id, $cid) {
        $query = "insert into categories_by_clients (category_id, client_id) value ('$cat_id', '$cid')";  
        $result =  $this->conn->db_query($query);
    } 
    
    function remove_category_to_client($cat_id, $cid) {
        $query = "delete from categories_by_clients where category_id = '$cat_id' and client_id = '$cid'";
        $result =  $this->conn->db_query($query);
    }
    
    function get_comments_by_client($client_id) {
        $query = "select id, comment, username, date_added from comments where client_id = '$client_id' and status = 0";
        $result =  $this->conn->db_query($query); 
                    
        $data = array();
       
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $data[] = $row;
        }     
            
        return $data;
         
    }
    
    function approve_comment($comment_id) {
        $query = "update comments set status = 1 where id = '$comment_id'";
        $result =  $this->conn->db_query($query); 
    } 
    
    function delete_comment($comment_id) {
        $query = "update comments set status = 0 where id = '$comment_id'";
        $result =  $this->conn->db_query($query); 
    }  
    
    function get_comment_abuse_by_client($client_id) {
        $query = "select comments.id, comment, username, group_concat( FROM_UNIXTIME(comment_abuse.date_added) SEPARATOR ', <br />') abuse_date,
                         count(comment_abuse.id) c
                  from comment_abuse
                  inner join comments on comments.id = comment_abuse.comment_id
                  where comments.client_id = '$client_id' and comments.status = 1 group by comments.id order by c desc";
                  
        $result =  $this->conn->db_query($query); 
                    
        $data = array();
       
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $data[] = $row;
        }     
            
        return $data;
    }
    
    function get_tokens($cid) {
        if ($cid != "") {
           $query = "select token, type from tokens where client_id = '$cid'";
        }
        else{
            $query = "select token, type from tokens"; 
        }
              
        $result =  $this->conn->db_query($query); 
        
        $data = array();
       
        while($row = $this->conn->db_fetch_array($result)) {
        //while($row = $result->fetch_assoc()){
            $data[] = $row;
        }     
            
        return $data;
    }
    
    function search_news($word) {
        $query = "select articles_html.id, title, articles_html.date_added, image, users.username added_by 
                  from articles_html 
                  inner join users on users.id = articles_html.added_by
                  where title like '%$word%' or body like '%$word%'";
                  
              //    echo($query);
        $result =  $this->conn->db_query($query);
   
        $data = array();
       
        while($row = $this->conn->db_fetch_array($result)){
        //while($row = $result->fetch_assoc()){
            $data[] = $row;
        }     
            
        return $data;
    }
    
    function get_related_tags($tname) {
        $tname = urldecode($tname);
        
        $query = "select id, name, parent, synonyms from tags where name = '$tname'" ;
        $result =  $this->conn->db_query($query);
             //echo($query);
        $data = array();
       
        while($row = $this->conn->db_fetch_array($result)){
        //while($row = $result->fetch_assoc()){
            $data[] = $row;
        }     
           //  pr($data);
        return $data;
    }
    
    function add_related_tag($data, $comma = 0){
        $parent_id = $data['parent_id'];
        $tag_name = $data['tag_name'];
        $parent_name = $data['parent_name'];
        
        if (trim($tag_name) != "") {
            
            $tags = explode(",", $tag_name);
            $resp = '';
            
            foreach($tags as $tag) {
                $query = "insert into tags (parent, name, synonyms) values ('$parent_id', '$parent_name', '$tag')"; 
                $result =  $this->conn->db_query($query);
                
                $last_id = $this->conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning 
                
                if ($parent_id == "") {
                    $update_query = "update tags set parent = id, name = synonyms where id = $last_id";
                    $result =  $this->conn->db_query($update_query);
                } 
                
                if ($comma) {
                    $resp .= '<br />' . $tag . ",";
                }
                else{
                    $resp .= "<tr id='tag_$last_id'><td>$parent_id</td><td>$parent_name</td><td>$tag</td><td><a href='javascript:void(0);' onclick='deleteReletadTag($last_id)'>X</a></td></tr>";
                }
            }
            
            return $resp;
        }
    }
    
    function delete_reletad_tag($id) {
        $query = "delete from tags where id = '$id'";
        $result =  $this->conn->db_query($query);
    }
    
    function update_sort($cat_id, $sort_val) {
        $query = "update categories set sort = '$sort_val' where id = '$cat_id'";
        $result =  $this->conn->db_query($query);
    }
    
    function update_uv($id) {
        $max_uv_query = "select max(uv) uv from categories";       
        $result_uv =  $this->conn->db_query($max_uv_query);
        $row_uv = $this->conn->db_fetch_array($result_uv);
        $uv = ($row_uv['uv'] == "" ? 0 : ($row_uv['uv']+1) );
        
        $query = "UPDATE categories set uv = '$uv' where id = '$id'"; 
        $result =  $this->conn->db_query($query);
    }
    
    function tags_and_synonyms(){
      /*  $query = "select id, parent, name, 
                   group_concat( concat(synonyms, 'DDDa href=javascript:void(0); onclick=deleteReletadTag(', id, ')CCC X EEE') SEPARATOR ', <br />') synonyms 
                   from tags group by parent";   */
                   
        $query = "select id, parent, name, 
                   group_concat(synonyms SEPARATOR ', <br />') synonyms 
                   from tags group by parent"; 
                   
        $result =  $this->conn->db_query($query);  
         
        while($rows = $this->conn->db_fetch_array($result)){
            $tags[] = $rows;
        }
        
        return $tags; 
    }
}