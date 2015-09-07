<?php
class Video {
    private $conn; 
    
    function __construct() {
        $this->conn = new MySQLiDatabaseConnection();
    }
    
    function build_add_video_form() {
        global $base_path;
        $helper_obj = new Helper();
        $category_obj = new Category();
        $pgrate_obj = new Pgrate();
    
        $output = '<div class="box box-bordered">';
        $output .= '<div class="box-title">
                                <h3><i class="icon-th-list"></i> Add Video</h3>
                            </div>';
                            
        $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' method='post' id='add_video' name='add_video' action='" . $base_path ."add_video'>";
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Title</label>
                        <div class='controls'> 
                          <input class='input-xlarge' data-rule-required='true' type='text' name='title' id='title' placeholder='" . $helper_obj->t('Title') . "'>
                        </div>
                     </div>";
                     
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Description</label>
                        <div class='controls'>
                            <textarea class='input-xlarge' data-rule-required='true' name='description' id='description' placeholder='" . $helper_obj->t('Description') . "'></textarea>
                        </div>
                    </div>";
       
        $output .= $category_obj->build_categories_list("add", "", "", "style='width:152px'");
        
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image1</label>
                        <div class='controls'>
                           <input class='input-xlarge' data-rule-required='true' accept='image/*' type='file' name='image1' id='image1'>
                        </div>   
                    </div>";
                    
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image2</label>
                        <div class='controls'>
                           <input accept='image/*' type='file' name='image2' id='image2'>
                        </div>
                    </div>";
                                        
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image3</label>
                        <div class='controls'>
                           <input accept='image/*' type='file' name='image3' id='image3'>
                        </div>
                    </div>";
                    
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image4</label>
                        <div class='controls'>
                           <input accept='image/*' type='file' name='image4' id='image4'>
                        </div>
                    </div>";
                    
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>ScreenShot</label>
                        <div class='controls'>
                           <input class='input-xlarge' data-rule-required='true' accept='image/*' type='file' name='screenshot' id='screenshot'>
                        </div>
                    </div>";  
                    
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Video</label>
                        <div class='controls'>
                           <input class='input-xlarge' data-rule-required='true' accept='video/*' type='file' name='video' id='video'>
                        </div>
                    </div>";    
                    
        
        $output .= $pgrate_obj->build_pgrate_list("add", "", "", "style='width:152px'");  
        
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'></label>
                        <div class='controls'>
                           <label class='checkbox'><input value='1' type='checkbox' name='feature' id='feature'>Feature</label>  
                           <label class='checkbox'><input value='1' type='checkbox' name='premium' id='premium'>Premium</label>
                        </div>
                  </div>"; 
                   
        $output .= "<input class='btn btn-primary' type='submit' value='" . $helper_obj->t('Save') . "'>";  
        $output .= "<input type='reset' style='display: none' id='add_video_reset'>";  
        $output .= "</form><br /><br />";
        
        return $output;
    }
    
    function add_video($data) {
        $db_functions_obj = new DbFunctions();
                              
        $vid = $db_functions_obj->add_video($data);
        
        return $vid;
    }
    
    function build_video_row($vid) {
        $db_functions_obj = new DbFunctions();
        $helper_obj = new Helper();
        
        $data = $db_functions_obj->get_vid_by_id($vid);
         
        $output = "<tr id='vid_$data->id'>
                          <td>" . $data->id . "</td>
                          <td>" . $data->title . "</td>
                          <td>" . $data->description . "</td>
                          <td>" . $data->image1 . "</td>
                          <td>" . $data->image2 . "</td>
                          <td>" . $data->image3 . "</td>
                          <td>" . $data->image4 . "</td>
                          <td>" . $data->screenshot . "</td>
                          <td>" . date(DATE_FORMAT, $data->upload_date) . "</td> 
                          <td>" . date(DATE_FORMAT, $data->published_date) . "</td> 
                          <td>" . $data->cat_name . "</td>
                          <td>" . $data->url . "</td>
                          <td>" . $data->pgrate_name . "</td>
                          <td>" . $data->views . "</td>
                          <td>" . $data->featured . "</td>
                          <td>" . $data->premium . "</td>   
                          <td>" . $data->added_by . "</td>   
                          <td><a href='javascript:void(0);' onclick='openEditVideoPopup($data->id)'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='deleteVideo($data->id)'>" . $helper_obj->t("Delete") . "</a></td>
                       </tr>";
                       
        return $output;
      //  pr($vid_details);
    }
    
    function get_all_videos($cid) {
        $db_functions_obj = new DbFunctions();
        $helper_obj = new Helper();
        
        $videos = $db_functions_obj->get_all_videos($cid);
                     
        $output = "<table id='add_video_table' style='font-size: 12px' class='table table-hover table-nomargin table-bordered'>
                      <tr>
                        <th>" . $helper_obj->t("ID") . "</th>
                        <th>" . $helper_obj->t("Title") . "</th>
                        <th>" . $helper_obj->t("Description") . "</th>
                        <th>" . $helper_obj->t("Image1") . "</th>
                        <th>" . $helper_obj->t("Image2") . "</th>
                        <th>" . $helper_obj->t("Image3") . "</th>
                        <th>" . $helper_obj->t("Image4") . "</th>
                        <th>" . $helper_obj->t("Screenshot") . "</th> 
                        <th>" . $helper_obj->t("Uploaded date") . "</th>
                        <th>" . $helper_obj->t("Published date") . "</th>
                        <th>" . $helper_obj->t("Category") . "</th>
                        <th>" . $helper_obj->t("Video") . "</th> 
                        <th>" . $helper_obj->t("PG rate") . "</th> 
                        <th>" . $helper_obj->t("Views") . "</th> 
                        <th>" . $helper_obj->t("Featured") . "</th>
                        <th>" . $helper_obj->t("Premium") . "</th>  
                        <th>" . $helper_obj->t("Added By") . "</th>  
                        <th>" . $helper_obj->t("Edit") . "</th>
                        <th>" . $helper_obj->t("Delete") . "</th>
                      </tr>";

         foreach($videos as $key => $data) {
            $class = $helper_obj->table_row_class($i);
            
            $output .= "<tr id='vid_$data->id'>
                          <td>" . $data->id . "</td>
                          <td>" . $data->title . "</td>
                          <td>" . $data->description . "</td>
                          <td>" . $data->image1 . "</td>
                          <td>" . $data->image2 . "</td>
                          <td>" . $data->image3 . "</td>
                          <td>" . $data->image4 . "</td>
                          <td>" . $data->screenshot . "</td>
                          <td>" . date(DATE_FORMAT, $data->upload_date) . "</td> 
                          <td>" . date(DATE_FORMAT, $data->published_date) . "</td> 
                          <td>" . $data->cat_name . "</td>
                          <td>" . $data->url . "</td>
                          <td>" . $data->pgrate_name . "</td>
                          <td>" . $data->views . "</td>
                          <td>" . $data->featured . "</td>
                          <td>" . $data->premium . "</td>   
                          <td>" . $data->added_by . "</td>   
                          <td><a href='javascript:void(0);' onclick='openEditVideoPopup($data->id)'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='deleteVideo($data->id)'>" . $helper_obj->t("Delete") . "</a></td>
                       </tr>";
         }
        
        return $output;
    }
    
    function build_edit_video_popup($vid) {
        $db_functions_obj = new DbFunctions();
        $validation_js_obj = new Validation_js();   
        $cat_obj = new Category();
        $helper_obj = new Helper();
        $pgrate_obj = new Pgrate();
        
        global $base_path;
        
        $vid_info = $db_functions_obj->get_vid_by_id($vid);
        $output = "";
        
        $output = $validation_js_obj->edit_video_validation(); 
        $output .= "<script>$(document).ready(function() {  $('#edit_video').ajaxForm(function(res) { 
                        var isvalid = $(\"#edit_video\").valid();
                        if (isvalid) { 
                            var data = res.split(\"***#***\");   
                            $('#vid_' + data[1]).after(data[0]);
                            $('#vid_' + data[1]).remove(); 
                            closePopup();
                        }  
                    }); });</script>";
                    
          $feature_checked = "";
          if ($vid_info->featured) {
              $feature_checked = "checked='checked'";
          }
          
          $premium_checked = "";
          if ($vid_info->premium) {
              $premium_checked = "checked='checked'";
          }
                    
          $output .= "<div class='popup-header'>" . $helper_obj->t("Edit Video") . " " . $vid_info->title . "</div>";
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='edit_video' id='edit_video' method='post' action='$base_path" . "edit_video'>";
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Title</label>
                        <div class='controls'>
                           <input type='text' id='title_updated' name='title_updated' value='" . $vid_info->title . "'>
                        </div>
                      </div>";
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Title</label>
                        <div class='controls'>
                           <textarea name='description_updated' id='description_updated'>$vid_info->description</textarea>
                        </div>
                      </div>";
                      
          $output .= $cat_obj->build_categories_list('edit', $vid_info->cat_id);
         
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image1</label>
                        <div class='controls'>
                            <input accept='image/*' type='file' name='image1_updated' id='image1_updated'><img src='" . VIDEOS_IMAGES_THUMBNAIL_PATH . $vid_info->image1 . "' width=" . IMG_WIDTH_EDIT_VID . " height=" . IMG_HIEGHT_EDIT_VID . ">
                        </div>
                      </div>";
                      
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image2</label>
                        <div class='controls'>
                            <input accept='image/*' type='file' name='image2_updated' id='image2_updated'><img src='" . VIDEOS_IMAGES_THUMBNAIL_PATH . $vid_info->image2 . "' width=" . IMG_WIDTH_EDIT_VID . " height=" . IMG_HIEGHT_EDIT_VID . ">
                        </div>
                      </div>";
                      
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image3</label>
                        <div class='controls'>
                             <input accept='image/*' type='file' name='image3_updated' id='image3_updated'><img src='" . VIDEOS_IMAGES_THUMBNAIL_PATH . $vid_info->image3 . "' width=" . IMG_WIDTH_EDIT_VID . " height=" . IMG_HIEGHT_EDIT_VID . ">
                        </div>
                      </div>";
                      
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image4</label>
                        <div class='controls'>
                            <input accept='image/*' type='file' name='image4_updated' id='image4_updated'><img src='" . VIDEOS_IMAGES_THUMBNAIL_PATH . $vid_info->image4 . "' width=" . IMG_WIDTH_EDIT_VID . " height=" . IMG_HIEGHT_EDIT_VID . ">
                        </div>
                      </div>";
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>ScreenShot</label>
                        <div class='controls'>
                            <input accept='image/*' type='file' name='screenshot_updated' id='screenshot_updated'><img src='" . VIDEOS_IMAGES_THUMBNAIL_PATH . $vid_info->screenshot . "' width=" . IMG_WIDTH_EDIT_VID . " height=" . IMG_HIEGHT_EDIT_VID . ">
                        </div>
                     </div>"; 
                     
          $output .= $pgrate_obj->build_pgrate_list("edit",  $vid_info->pgrate_id);
          
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Featured</label>
                        <div class='controls'>
                           <input $feature_checked type='checkbox' value='1' id='featured_updated' name='featured_updated'>" . $helper_obj->t("Featured") . 
                        "</div>
                      </div>";
                      
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Premium</label>
                        <div class='controls'>
                          <input $premium_checked type='checkbox' value='1' id='premium_updated' name='premium_updated'>" . $helper_obj->t("Premium") . 
                        "</div>
                      </div>";
          $output .= "<input class='btn btn-primary' type='submit' value='" . $helper_obj->t("Save") . "'>"; 
          $output .= "<input type='hidden' name='edit_vid' id='edit_vid' value='" . $vid_info->id . "'>";
          $output .= "</form>";
        
        return $output;
    }
}