<?php
class Report {
    private $conn; 
    
    function __construct() {
        $this->conn = new MySQLiDatabaseConnection();
    }
    
    /**
    * @Author: Taymoor Qanadilou
    * @Date created: 26/05/2013
    * @Description:uploaded images report
    * @Param:
    *   none
    * @Updated History:
    *    none
    */
    function uploaded_images_report(){
        $db_function_obj = new DbFunctions();
        
        $number_images_approved = $this->conn->db_fetch_object($db_function_obj->number_of_images_uplaoded_report());


        $output_approved =  array( $number_images_approved->thisdayapproved ,
                                   $number_images_approved->thisweekapproved ,
                                   $number_images_approved->thismonthapproved,
                                   $number_images_approved->thisyearapproved) ;
                                   
        $output_removed =  array( $number_images_approved->thisdayremoved ,
                                  $number_images_approved->thisweekremoved ,
                                  $number_images_approved->thismonthremoved,
                                  $number_images_approved->thisyearremoved) ;
                                   
        $output_disapproved =  array( $number_images_approved->thisdaydis ,
                                      $number_images_approved->thisweekdis ,
                                      $number_images_approved->thismonthdis,
                                      $number_images_approved->thisyeardis) ; 
                                      
        $output_stuck =  array( $number_images_approved->thisdayStuck ,
                                      $number_images_approved->thisweekStuck ,
                                      $number_images_approved->thismonthStuck,
                                      $number_images_approved->thisyearStuck) ; 
                                      
                                      
                                     
        $output = " <div>
                        <table border='1'>
                            <tr><td colspan=5>Uploaded Images Report</td></tr>
                            <tr>
                                <th>options</th>
                                <th>this day</th>
                                <th>this week</th>
                                <th>this month</th>
                                <th>this year</th>
                            </tr>
                            <tr class='odd'>
                                <td>Approved</td>";
                                foreach($output_approved as $value){
                                    $output .= "<td>$value</td>";    
                                } 
        $output .=         "</tr>
                            <tr class='even'>
                                <td>Removed</td>";
                                foreach($output_removed as $value){
                                    $output .= "<td>$value</td>";    
                                } 
        $output .=         "</tr>
                            <tr class='odd'>
                                <td>disapproved</td>";
                                foreach($output_disapproved as $value){
                                    $output .= "<td>$value</td>";    
                                } 
        $output .=         "</tr>
                            <tr class='even'>
                                <td>Stuck</td>";
                                foreach($output_stuck as $value){
                                    $output .= "<td>$value</td>";    
                                } 
        $output .=         "</tr>
                        </table>";
                        
        $output .= " </div>";  
        
                    
        return $output;   

                                  
    }

    /**
    * @Author: Taymoor Qanadilou
    * @Date created: 26/05/2013
    * @Description:top five users report
    * @Param:
    *   none
    * @Updated History:
    *    none
    */
    function top_five_users_report(){
        $db_functions_obj = new DbFunctions();
        $user_obj = new User();
        $output = "";
        
        $year = $db_functions_obj->get_top_five_by('year');   
        while($rowyear = $this->conn->db_fetch_object($year)){
            $out5year[$rowyear->uid] = "$rowyear->year";       
        }   
        
        $month = $db_functions_obj->get_top_five_by('month');
        while($rowmonth = $this->conn->db_fetch_object($month)){
            $out5month[$rowmonth->uid] = "$rowmonth->month";    
        }
        
        $week = $db_functions_obj->get_top_five_by('week');
        while($rowweek = $this->conn->db_fetch_object($week)){
            $out5week[$rowweek->uid] = "$rowweek->week";    
        }
        
        $day = $db_functions_obj->get_top_five_by('day');
        while($rowday = $this->conn->db_fetch_object($day)){
            $out5day[$rowday->uid] = "$rowday->day";    
        }
        
        $output .= "    <table border='1'>
                            <tr><td colspan='9'>Top 5 Users</td></tr>
                            <tr>
                                <th>options</th>
                                <th>1st user</th>
                                <th>2nd user</th>
                                <th>3rd user</th>
                                <th>4th user</th>
                                <th>5th user</th>
                            </tr>
                            <tr class='odd'>
                                <td>this year</td>";
                               
                                foreach($out5year as $value => $key){
                                    $output .= "<td>" . $user_obj->user_load($value)->name . " -> " . ($key + 0) . "</td>";    
                                } 
        $output .=         "</tr>
                            <tr class='even'>
                                <td>ths month</td>";
                                foreach($out5month as $value => $key){
                                    $output .= "<td>" . $user_obj->user_load($value)->name . " -> " . ($key + 0) . "</td>";    
                                }
        $output .=         "</tr>
                            <tr class='odd'>
                                <td>this week</td>";
                                foreach($out5week as $value => $key){
                                    $output .= "<td>" . $user_obj->user_load($value)->name . " -> " . ($key + 0) . "</td>";    
                                }
        $output .=         "</tr>
                            <tr class='even'>
                                <td>this day</td>"; 
                                foreach($out5day as $value => $key){
                                    $output .= "<td>" . $user_obj->user_load($value)->name . " -> " . ($key + 0) . "</td>";    
                                }   
        $output .=         "</tr>
                        </table>";
        return $output;
    }

    /**
    * @Author: Taymoor Qanadilou
    * @Date created: 26/05/2013
    * @Description:top images large size report
    * @Param:
    *   none
    * @Updated History:
    *    Ayman Hussein    02/06/2013    remove the logic that get all images then sort it on img size then getl last big 5 img 
    */
    function top_images_large_size_report() {
        $db_functions_obj = new DbFunctions();
        $helper_obj = new Helper();
        
        $image_query = $db_functions_obj->image_filesize_report();
        
        while($row = $this->conn->db_fetch_object($image_query)) {
            $image_details[] = $row;
        }

        $output = "<table width='100%'>
                        <tr><td>Top images size</td></tr>
                        <tr>
                            <th>Id</th>
                            <th>Image</td>
                            <th>Image size</th>
                        </tr>";

        foreach($image_details as $key =>$value) {
            $class = $helper_obj->table_row_class($i); 
            
            $output .= "<tr class='$class'>
                            <td>$value->id</td>
                            <td>
                                <img src='" . THUMBNAIL_IMG_PATH . "$value->img_path' />
                            </td>
                            <td>". $helper_obj->format_size_units($value->img_size) . "</td>
                        </tr>";               
        }

        $output .= "</table>";
        
        return $output;
    }

    /**
    * @Author: Taymoor Qanadilou
    * @Date created: 27/05/2013
    * @Description:number of images/posts by country
    * @Param:
    *   none
    * @Updated History:
    *    none
    */
    function community_by_country_report() {
        $db_functions_obj = new DbFunctions(); 
        $ic_obj = new ImageCommunity(); 
        $helper_obj = new Helper();  
        
        $output = "";
           
        $posts_status_0 = $db_functions_obj->number_of_posts_by_country_report('all');
        $posts_status_1 = $db_functions_obj->number_of_posts_by_country_report(STATUS_APPROVED);
        $posts_status_2 = $db_functions_obj->number_of_posts_by_country_report(STATUS_DISAPPROVED);
        
        while($post = $this->conn->db_fetch_object($posts_status_0)){
            $posts[$post->country_id]['catStatusAll'] = $post->count;    
        }
        while($post1 = $this->conn->db_fetch_object($posts_status_1)){
            $posts[$post1->country_id]['catStatus1'] =  $post1->count;   
        }
        while($post2 = $this->conn->db_fetch_object($posts_status_2)){
             $posts[$post2->country_id]['catStatus2'] =  $post2->count;    
        }
        
        //make sure the posts array has all fields 
        foreach ($posts as $key => $value) {
           if (!isset($posts[$key]['catStatus1'])) {
               $posts[$key]['catStatus1'] = 0;          
           }
           
           if (!isset($posts[$key]['catStatus2'])) {
               $posts[$key]['catStatus2'] = 0;          
           }
        }
       
        $images_status_0 = $db_functions_obj->number_of_images_by_country_report('all');
        $images_status_1 = $db_functions_obj->number_of_images_by_country_report(STATUS_APPROVED);
        $images_status_2 = $db_functions_obj->number_of_images_by_country_report(STATUS_DISAPPROVED);
        
        while($image = $this->conn->db_fetch_object($images_status_0)){
            $images[$image->country_id]['catStatusAll'] = $image->count;    
        }
        while($image1 = $this->conn->db_fetch_object($images_status_1)){
            $images[$image1->country_id]['catStatus1'] =  $image1->count;   
        }
        while($image2 = $this->conn->db_fetch_object($images_status_2)){
             $images[$image2->country_id]['catStatus2'] =  $image2->count;    
        }
        
        //make sure the images array has all fields 
        foreach ($images as $key => $value) {
           if (!isset($images[$key]['catStatus1'])) {
               $images[$key]['catStatus1'] = 0;          
           }
           
           if (!isset($images[$key]['catStatus2'])) {
               $images[$key]['catStatus2'] = 0;          
           }
        }
       
        $output .= "    <table border='1'>
                            <tr><td colspan=5>Images by country</td></tr>
                            <tr>
                                <th>Country id</th>
                                <th>Count all</th>
                                <th>Count not seen</th>
                                <th>Count approved</th>
                                <th>Count disapproved</th>
                            </tr>
                            ";
                            $countryL = $ic_obj->country_list();
                            
                            foreach($images as $key =>$value){
                                $class = $helper_obj->table_row_class($i);
                                
                                $output .= "<tr class='$class'>
                                                <td>" . ($key == 0 ?  "General" : $countryL[$key]) . "</td>
                                                <td>" . ($value['catStatusAll']+0 ) ."</td>
                                                <td>" . ($value['catStatusAll']-$value['catStatus1']-$value['catStatus2']+0 ) ."</td>
                                                <td>" . ($value['catStatus1']+0 ) ."</td>
                                                <td>" . ($value['catStatus2']+0 ) ."</td>
                                            </tr>";
                            }
                                
        $output .=       "
                         </table>";  
        $output .= "   <br /><br /><br /> 
                         <table border='1'>
                            <tr><td colspan=5>Posts by country</td></tr>
                            <tr>
                                <th>Country id</th>
                                <th>Count all</th>
                                <th>Count not seen</th>
                                <th>Count approved</th>
                                <th>Count disapproved</th>
                            </tr>
                            ";
                            
                            foreach($posts as $key =>$value){
                                $class = $helper_obj->table_row_class($i);
                                
                                $output .= "<tr class='$class'>
                                                <td>" . ($key == 0 ?  "General" : $countryL[$key]) . "</td>
                                                <td>" . ($value['catStatusAll']+0 ) ."</td>
                                                <td>" . ($value['catStatusAll']-$value['catStatus1']-$value['catStatus2']+0 ) ."</td>
                                                <td>" . ($value['catStatus1']+0 ) ."</td>
                                                <td>" . ($value['catStatus2']+0 ) ."</td>
                                            </tr>";
                            }
                                
         $output .=       "
                         </table>"; 
         return $output; 
    }

    /**
    * @Author: Taymoor Qanadilou
    * @Date created: 27/05/2013
    * @Description:number of images/posts by category
    * @Param:
    *   none
    * @Updated History:
    *    none
    */
    function community_by_category_report(){
        $db_function_obj = new DbFunctions();
        $user_obj = new User();
        $helper_obj = new Helper();
        
        $posts_status_0 = $db_function_obj->number_of_posts_by_category_report('all');
        $posts_status_1 = $db_function_obj->number_of_posts_by_category_report(STATUS_APPROVED);
        $posts_status_2 = $db_function_obj->number_of_posts_by_category_report(STATUS_DISAPPROVED);
        
        while($post = $this->conn->db_fetch_object($posts_status_0)){
            $posts[$post->cat_id] = array('cat_name' => $post->name,'catStatusAll' => $post->count);    
        }
        while($post1 = $this->conn->db_fetch_object($posts_status_1)){
            $posts[$post1->cat_id]['catStatus1'] =  $post1->count;   
        }
        while($post2 = $this->conn->db_fetch_object($posts_status_2)){
             $posts[$post2->cat_id]['catStatus2'] =  $post2->count;    
        }
        
        //make sure the posts array has all fields 
        foreach ($posts as $key => $value) {
           if (!isset($images[$key]['catStatus1'])) {
               $posts[$key]['catStatus1'] = 0;          
           }
           
           if (!isset($images[$key]['catStatus2'])) {
               $posts[$key]['catStatus2'] = 0;          
           }
        }
       
        $images_status_0 = $db_function_obj->number_of_images_by_category_report('all');
        $images_status_1 = $db_function_obj->number_of_images_by_category_report(STATUS_APPROVED);
        $images_status_2 = $db_function_obj->number_of_images_by_category_report(STATUS_DISAPPROVED);
        
        while($image = $this->conn->db_fetch_object($images_status_0)){     
            $images[$image->cat_id] = array('cat_name' => $image->name,'catStatusAll' => $image->count);    
        }
        while($image1 = $this->conn->db_fetch_object($images_status_1)){
            $images[$image1->cat_id]['catStatus1'] =  $image1->count;   
        } 
        while($image2 = $this->conn->db_fetch_object($images_status_2)){  
             $images[$image2->cat_id]['catStatus2'] =  $image2->count;    
        }
        
        //make sure the images array has all fields 
        foreach ($images as $key => $value) {
           if (!isset($images[$key]['catStatus1'])) {
               $images[$key]['catStatus1'] = 0;          
           }
           
           if (!isset($images[$key]['catStatus2'])) {
               $images[$key]['catStatus2'] = 0;          
           }
        }
        
        $output = "";      
       
        $output .= "    <table border='1'>
                            <tr><td colspan=5>Images by categories</td></tr>
                            <tr>
                                <th>Category name</th>
                                <th>Count all</th>
                                <th>Count not seen</th>
                                <th>Count approved</th>
                                <th>Count disapproved</th>
                            </tr>
                            ";
                            
                            foreach($images as $key =>$value){
                                $class = $helper_obj->table_row_class($i);
                                
                                $output .= "<tr class='$class'>
                                                <td>$value[cat_name]</td>
                                                <td>" . ($value['catStatusAll']+0 ) ."</td>
                                                <td>" . ($value['catStatusAll']-$value['catStatus1']-$value['catStatus2']+0 ) ."</td>
                                                <td>" . ($value['catStatus1']+0 ) ."</td>
                                                <td>" . ($value['catStatus2']+0 ) ."</td>
                                            </tr>";
                            }
                                
         $output .=       "
                         </table>";  
        $output .= "      <br /><br /><br />
                         <table border='1'>
                            <tr><td colspan=5>POSTS by categories</td></tr>
                            <tr>
                                <th>category name</th>
                                <th>count all</th>
                                <th>count not seen</th>
                                <th>count approved</th>
                                <th>count disapproved</th>
                            </tr>
                            ";
                            
                            foreach($posts as $key =>$value){
                                $class = $helper_obj->table_row_class($i); 
                                
                                $output .= "<tr class='$class'>
                                                <td>$value[cat_name]</td>
                                                <td>" . ($value['catStatusAll']+0 ) ."</td>
                                                <td>" . ($value['catStatusAll']-$value['catStatus1']-$value['catStatus2']+0 ) ."</td>
                                                <td>" . ($value['catStatus1']+0 ) ."</td>
                                                <td>" . ($value['catStatus2']+0 ) ."</td>
                                            </tr>";
                            }
                                
         $output .=       "
                         </table>"; 
         return $output; 
    }

    /**
    * @Author: Taymoor Qanadilou
    * @Date created: 27/05/2013
    * @Description: get all comments have bad words
    * @Param:
    *   none
    * @Updated History:
    *    none
    */
    function bad_words_images_report(){
        $db_function_obj = new DbFunctions();
        $user_obj = new User();
        $helper_obj = new Helper();
        
        $bad_words = $db_function_obj->bad_words_images_query_report();
        $output = "";
        
        $output .= "<table border='1'>
                        <tr><td colspan=5>Bad words on Images</td></tr>
                        <tr>
                            <th>Image Id</th>
                            <th>Name</th>
                            <th>User name</th>
                            <th>Description</th>
                            <th>Date added</th>
                        </tr>
                        ";
                        while($row = $this->conn->db_fetch_object($bad_words)){
                            
                            $class = $helper_obj->table_row_class($i);
                            
                            $output .= "<tr class='$class'>
                                            <td>$row->id</td>
                                            <td>$row->name</td>
                                            <td>" . $user_obj->user_load($row->uid)->name . "</td>
                                            <td>" . $helper_obj->textLimitation($row->description) ."</td>
                                            <td>$row->date_added</td>
                                            
                                        </tr>";    
                        }
                                
         $output .= "</table>";  
       
         return $output; 
    }

    /**
    * @Author: Taymoor Qanadilou
    * @Date created: 27/05/2013
    * @Description: get all comments have bad words
    * @Param:
    *   none
    * @Updated History:
    *    none
    */
    function bad_words_posts_report(){
        $db_function_obj = new DbFunctions();
        $user_obj = new User();
        $helper_obj = new Helper();
        
        $bad_words = $db_function_obj->bad_words_posts_query_report();
        $output = "";
        
        $output .= "<table border='1'>
                        <tr><td colspan=6>Bad word on posts</td></tr>
                        <tr>
                            <th>Post id</th>
                            <th>Title</th>
                            <th>User id</th>
                            <th>Post</td>
                            <th>Date added</th>
                        </tr>
                        ";
                        while($row = $this->conn->db_fetch_object($bad_words)){
                            $class = $helper_obj->table_row_class($i);
                            
                            $output .= "<tr class='$class'>
                                            <td>$row->id</td>
                                            <td>$row->title</td>
                                            <td>" . $user_obj->user_load($row->uid)->name . "</td>
                                            <td>" . $helper_obj->textLimitation($row->post) ."</td>
                                            <td>" . date("Y n d H:i:s", $row->date_added) . "</td>
                                            
                                        </tr>";    
                        }
                                
         $output .= "</table>";  
       
         return $output; 
    }

    /**
    * @Author: Taymoor Qanadilou
    * @Date created: 27/05/2013
    * @Description: get all comments have bad words
    * @Param:
    *   none
    * @Updated History:
    *    none
    */
    function bad_words_report(){
        $db_function_obj = new DbFunctions();
        $user_obj = new User();
        $helper_obj = new Helper();
        
        $bad_words = $db_function_obj->bad_words_query_report();
        $output = "";
        
        $output .= "<table border='1'>
                        <tr><td colspan=6>Bad word on comments</td></tr>
                        <tr>
                            <th>Comment id</th>
                            <th>Community id</th>
                            <th>User id</th>
                            <th>Type flag</th>
                            <th>Comment</th>
                            <th>Date added</th>
                        </tr>
                        ";
        while($row = $this->conn->db_fetch_object($bad_words)) {
            $class = $helper_obj->table_row_class($i);
            $output .= "
                        <tr class='$class'>
                            <td>$row->id</td>
                            <td>$row->nid</td>
                            <td>" . $user_obj->user_load($row->uid)->name . "</td>
                            <td>$row->type_flag_str</td>
                            <td>" . $helper_obj->textLimitation($row->comment) ."</td>
                            <td>$row->date_added</td>
                            
                        </tr>";
        }
                                
         $output .= "</table>";  
       
         return $output; 
    }

    /**
    * @Author: Taymoor Qanadilou
    * @Date created: 27/05/2013
    * @Description: get all comments have bad words
    * @Param:
    *   none
    * @Updated History:
    *    none
    */
    function disapproved_users_report(){
        $db_function_obj = new DbFunctions();
        $user_obj = new User();
        $helper_obj = new Helper();
        $output = "";
        
        //$approved_images = number_of_approved_images_report(STATUS_APPROVED);
        $disapproved_images = $db_function_obj->number_of_approved_images_report(STATUS_DISAPPROVED);
        //$approved_posts = number_of_approved_posts_report(STATUS_APPROVED);
        $disapproved_posts = $db_function_obj->number_of_approved_posts_report(STATUS_DISAPPROVED);
        
        while($row = $this->conn->db_fetch_object($disapproved_images)){
            $disapproved[$row->uid]['count_disapproved_image'] = $row->count;
        }
       
        while($row = $this->conn->db_fetch_object($disapproved_posts)){
            $disapproved[$row->uid]['count_disapproved_post'] = $row->count;
        }
             
        $output .= "<table border='1'>
                        <tr><td colspan='3'>Disapproved Users Posts/Images</td></tr>
                        <tr>
                            <th>User Name</th>
                            <th>Disapproved images</th>
                            <th>Disapproved posts</th>
                        </tr>
                        ";
                        if (is_array($disapproved)){
                            foreach($disapproved as $key => $value){
                                $class = $helper_obj->table_row_class($i);  
                                
                                $output .= "<tr class='$class'>
                                                <td>" . $user_obj->user_load($key)->name . "</td>
                                                <td>" . (@$value['count_disapproved_image']+0) . "</td>
                                                <td>" . (@$value["count_disapproved_post"]+0) . "</td>
                                            </tr>";    
                            }
                        }
                                
         $output .= "</table>";  
       
         return $output; 
    }
}