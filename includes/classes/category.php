<?php
  //require_once 'google/appengine/api/cloud_storage/CloudStorageTools.php';
  //use google\appengine\api\cloud_storage\CloudStorageTools;

  class Category {
      private $conn; 
    
      function __construct() {
          $this->conn = new MySQLiDatabaseConnection();
      }
      
      function build_categories_list($page = 'add', $selected_client = "", $onclick = "", $style = "", $mutiple = true){
          $db_functions_obj = new DbFunctions();
          $helper_obj = new Helper();
          
          global $user;
                     
          $categories = $db_functions_obj->get_all_categories($user['cid']);  
          
          $output =  "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Category</label>
                        <div class='controls'>";
          $multi = '';
          $select_name = "_category";
          
          if ($mutiple){
               $multi = "multiple='multiple'"; 
               $select_name = "_category[]";
          }            
          
          $output .= "<select $multi class='input-xlarge' data-rule-required='true' $style $onclick id='$page" . $select_name . "' name='$page" . $select_name . "'>";
                 
          //$output .= "<option value=''>" . $helper_obj->t("Select...") . "</option>";
          
          foreach($categories as $key => $value){
              if ($selected_client != ""){
                $output .= "<option ";
                if ( in_array( $value['id'], $selected_client)) {
                      $output .= " selected='selected' ";
                }
                $output .= "value='" . $value['id'] . "'>" . $value['name'] . "</option>";
              }
              else{
                  $output .= "<option value='" . $value['id'] . "'>" . $value['name'] . "</option>";  
              }
          }
          $output .= "</select>";
          $output .= "</div>";
          $output .= "</div>";
          
          return $output;
      }
      
      function build_categories_source_list($page = 'add', $selected_client = "", $onclick = "", $style = "", $mutiple = true){
          $db_functions_obj = new DbFunctions();
          $helper_obj = new Helper();
          
          global $user;
                     
          $categories = $db_functions_obj->get_all_category_sources();  
          
          $output =  "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Category</label>
                        <div class='controls'>";
          $multi = '';
          $select_name = "_category";
          
          if ($mutiple){
               $multi = "multiple='multiple'"; 
               $select_name = "_category[]";
          }            
          
          $output .= "<select $multi class='input-xlarge' data-rule-required='true' $style $onclick id='$page" . $select_name . "' name='$page" . $select_name . "'>";
                 
          //$output .= "<option value=''>" . $helper_obj->t("Select...") . "</option>";
          
          foreach($categories as $key => $value){
              if ($selected_client != ""){
                $output .= "<option ";
                if ( in_array( $value['id'], $selected_client)) {
                      $output .= " selected='selected' ";
                }
                $output .= "value='" . $value['id'] . "'>" . $value['name'] . "</option>";
              }
              else{
                  $output .= "<option value='" . $value['id'] . "'>" . $value['name'] . "</option>";  
              }
          }
          $output .= "</select>";
          $output .= "</div>";
          $output .= "</div>";
          
          return $output;
      }
      
      function add_categories_to_article($aid, $cid) {
          $db_functions_obj = new DbFunctions();
          
          $db_functions_obj->add_categories_to_article($aid, $cid);
      }
      
      function build_add_category_form(){
          $helper_obj = new Helper();
          global $base_path;
          
          //$BUCKET = 'cosmic-descent-775.appspot.com/cat/';      
         // $options = [ 'gs_bucket_name' => $BUCKET ];
         // $upload_url = CloudStorageTools::createUploadUrl('/add_category', $options);
          
          $output = '<div class="box box-bordered">';
          $output .= '<div class="box-title">
                          <h3><i class="icon-th-list"></i> Add Category</h3>
                      </div>';
          $output .= "<div class='box-content nopadding'>";
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='add_category' id='add_category' method='post' action='add_category'>";
          
          $output .= $this->build_categories_list('add','','','',false);
          
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='category_name'>Category Name</label>
                        <div class='controls'>
                          <input class='input-xlarge' data-rule-required='true' data-rule-minlength='2' type='text' id='category_name' name='category_name' placeholder='" . $helper_obj->t("Name") . "'>
                        </div>
                      </div>";
                      
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image1</label>
                        <div class='controls'>
                           <input class='input-xlarge' data-rule-required='true' type='file' name='image1' id='image1' accept='image/*' > 
                        </div>   
                    </div>";
                    
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image2</label>
                        <div class='controls'>
                           <input accept='image/*' type='file' name='image2' id='image2'>
                        </div>
                    </div>";
                    
         $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'></label>
                        <div class='controls'> 
                           <label class='checkbox'><input value='1' type='checkbox' name='premium' id='premium'>Premium</label>
                        </div>
                  </div>"; 
                      
          $output .= "<input class=\"btn btn-primary\" type='submit' value='" . $helper_obj->t("Save") . "'>";
          $output .= "<input style='display: none' type='reset' id='add_category_reset'>";
          $output .= "</form>";
          $output .= "</div>";
          $output .= "</div><br /><br />";
                            
          return $output; 
      }
      
      function build_add_source_form(){
          $helper_obj = new Helper();
          global $base_path;
          
          $output = '<div class="box box-bordered">';
          $output .= '<div class="box-title">
                          <h3><i class="icon-th-list"></i> Add Source</h3>
                      </div>';
          $output .= "<div class='box-content nopadding'>";
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='add_source' id='add_source' method='post' action='$base_path" . "add_source'>";
          
          $output .= $this->build_categories_source_list('add','','','',false);
          
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='category_name'>Source Link</label>
                        <div class='controls'>
                          <input style='width: 600px' data-rule-url='true' class='input-xlarge' data-rule-required='true' data-rule-minlength='2' type='text' id='source_link' name='source_link' placeholder='" . $helper_obj->t("Source Link") . "'>
                          <div style='font-size: 12px; margin-top: 10px'><a href='javascript:void(0);' onclick='$(\"#source_link\").val(\"https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=iphoneislam&count=5\")'>Twitter link:</a> https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=<span style='color: red'>iphoneislam</span>&count=5</div> 
                        </div>   
                      </div>";
                      
          $output .=  "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Category</label>
                        <div class='controls'>";
          $output .= "<select class='input-xlarge' data-rule-required='true' id='source_type' name='source_type'>";
          $output .= "<option value='1'>Twitter</option>";
          $output .= "<option value='2'>RSS</option>";
          $output .= "</select>";
          $output .= "</div>";
          $output .= "</div>";
                             
          $output .= "<input class=\"btn btn-primary\" type='submit' value='" . $helper_obj->t("Save") . "'>";
          $output .= "<input style='display: none' type='reset' id='add_source_reset'>";
          $output .= "</form>";
          $output .= "</div>";
          $output .= "</div><br /><br />";
                            
          return $output; 
      }
      
      function get_categories(){
          $db_functions_obj = new DbFunctions();
          
          global $user;
                           
          $categories = $db_functions_obj->get_all_categories($user['cid']);
                       
          $helper_obj = new Helper(); 
          
          $output = "<table id='add_category_table' class='table table-hover table-nomargin table-bordered'>
                      <tr>
                        <th>" . $helper_obj->t("ID") . "</th>
                        <th>" . $helper_obj->t("Sort") . "</th>
                        <th>" . $helper_obj->t("Name") . "</th>
                        <th>" . $helper_obj->t("Parent") . "</th>
                        <th>" . $helper_obj->t("Image1") . "</th>
                        <th>" . $helper_obj->t("Image2") . "</th>
                        <th>" . $helper_obj->t("Premium") . "</th>
                        <th>" . $helper_obj->t("Edit") . "</th>
                        <th>" . $helper_obj->t("Delete") . "</th>
                      </tr>";

         foreach($categories as $key => $category) {
            $class = $helper_obj->table_row_class($i);
             
            $output .= "<tr class='$class' id='category_$category[id]'>
                          <td>" . $category['id'] . "</td>
                          <td><input onkeyup='UpdateSort($category[id])' style='width: 30px' type='text' id='sort_$category[id]' value='" . $category['sort'] . "'></td>
                          <td>" . $category['name'] . "</td>
                          <td>" . $category['parent'] . "</td>
                          <td>" . $category['image1'] . "</td>
                          <td>" . $category['image2'] . "</td>
                          <td>" . $category['premium'] . "</td>
                          <td><a href='javascript:void(0);' onclick='openEditCategoryPopup($category[id])'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='deleteCategory($category[id])'>
                                " . $helper_obj->t("Delete") . "</div>
                              </a>
                          </td>
                       </tr>";
         }             
        
         $output .= "</table>"; 
         
         return $output;           
      }
      
      function get_sources(){
          $db_functions_obj = new DbFunctions();
          
          global $user;
                           
          $categories = $db_functions_obj->get_all_sources();
                       
          $helper_obj = new Helper(); 
          
          $output = "<table id='add_source_table' class='table table-hover table-nomargin table-bordered'>
                      <tr>
                       
                        <th>" . $helper_obj->t("Source ID") . "</th>
                        <th>" . $helper_obj->t("Source Name") . "</th>
                        <th>" . $helper_obj->t("Twitter User ID") . "</th>
                        <th>" . $helper_obj->t("Link") . "</th> 
                        <th>" . $helper_obj->t("Delete") . "</th> 
                      </tr>";

         foreach($categories as $key => $category) {
            $class = $helper_obj->table_row_class($i);
             
            $output .= "<tr class='$class' id='category_$category[id]'>
                         
                          <td>" . $category['category_id'] . "</td>
                          <td>" . $category['name'] . "</td>
                          <td>" . $category['twitter_user_id'] . "</td>
                          <td>" . $category['link'] . "</td>
                          <td><a href='javascript:void(0)' onclick='deleteSource(" . $category['id'] . ");'>Delete</a></td>
                       </tr>";
         }             
        
         $output .= "</table>"; 
         
         return $output;           
      }
      
      function build_category_row($id) {
          $db_functions_obj = new DbFunctions(); 
          $helper_obj = new Helper();
          
          $category = $db_functions_obj->get_category_by_id($id);
            
          $class = $helper_obj->table_row_class($i); 
          
          $output = "<tr class='$class' id='category_$category[id]'>
                          <td>" . $category['id'] . "</td>
                          <td>" . $category['name'] . "</td>
                          <td>" . $category['image1'] . "</td>
                          <td>" . $category['image2'] . "</td>
                          <td>" . $category['premium'] . "</td>
                          <td><a href='javascript:void(0);' onclick='openEditCategoryPopup($category[id])'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='deleteCategory($category[id])'>
                                " . $helper_obj->t("Delete") . "</div>
                              </a>
                          </td>
                       </tr>";
                       
            return $output; 
      }
      
      function build_source_row($id) {
          $db_functions_obj = new DbFunctions(); 
          $helper_obj = new Helper();
          
          $category = $db_functions_obj->get_source_by_id($id);
            
          $class = $helper_obj->table_row_class($i); 
          
          $output = "<tr class='$class' id='category_$category[id]'>
                          <td>" . $category['id'] . "</td>
                          <td>" . $category['name'] . "</td>
                          <td>" . $category['link'] . "</td>
                       </tr>";
                       
            return $output; 
      }
      
      function build_edit_category_popup($category_id) {
          $db_functions_obj = new DbFunctions();
          $helper_obj = new Helper();
          $category_info = $db_functions_obj->get_category_by_id($category_id);
          global $base_path;
          
          $premium_checkbox = "";
          if ($category_info['premium']) {
              $premium_checkbox = "checked = 'checked'";
          }
          
         /* $output = '<!-- Validation -->
                                 <script src="' . $base_path . 'js/plugins/validation/jquery.validate.min.js"></script>
                                 <script src="' . $base_path . 'js/plugins/validation/additional-methods.min.js"></script>                                 
                                 <script src="' . $base_path . 'js/plugins/datepicker/bootstrap-datepicker.js"></script>';*/  
                                                 
          $output = "<script>$(document).ready(function() {  
                        $('#edit_category').ajaxForm(function(res) {   
                        var isvalid = $(\"#edit_category\").valid(); //alert(isvalid);  
                        if (isvalid) { 
                            var data = res.split(\"***#***\");   
                            $('#category_' + data[1]).after(data[0]);
                            $('#category_' + data[1]).remove(); 
                            closePopup();
                        }  
                    }); });</script>";
                    
          //$BUCKET = 'cosmic-descent-775.appspot.com/cat/';      
          //$options = [ 'gs_bucket_name' => $BUCKET ];
          //$upload_url = CloudStorageTools::createUploadUrl('/edit_category', $options);
                    
          $output .= "<div class='popup-header'>" . $helper_obj->t("Edit Category") . " (" . $category_info['name'] . ")</div>";
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='edit_category' id='edit_category' method='post' action='edit_category'>";
         
          $output .= $this->build_categories_list('add_parent', array($category_info['parent']),'','',false);  
          
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='category_name'>Category Name</label>
                        <div class='controls'>
                          <input value ='" . $category_info['name'] . "' class='input-xlarge' data-rule-required='true' data-rule-minlength='2' type='text' id='category_name_update' name='category_name_update' placeholder='" . $helper_obj->t("Name") . "'>
                        </div>
                      </div>";
                      
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image1</label>
                        <div class='controls'>
                           <input type='file' name='image1_update' id='image1_update' accept='image/*' > 
                        </div>   
                        <div style='margin-left: 380px;margin-top: -50px;'><img src=" . CATEGORIES_IMAGES_PATH . $category_info['image1'] . " width=77 height=77></div>
                    </div>";
                    
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image2</label>
                        <div class='controls'>
                           <input accept='image/*' type='file' name='image2_update' id='image2_update'> 
                        </div>
                        <div style='margin-left: 380px;margin-top: -50px;'><img src=" . CATEGORIES_IMAGES_PATH . $category_info['image2'] . " width=77 height=77></div> 
                    </div>";
                    
         $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'></label>
                        <div class='controls'> 
                           <label class='checkbox'><input value='1' $premium_checkbox type='checkbox' name='premium_update' id='premium_update'>Premium</label>
                        </div>
                  </div>"; 
                     
          $output .= "<input class='btn btn-primary' type='submit' value='" . $helper_obj->t("Save") . "'>";
          $output .= "<input type='hidden' name='category_id_updated' value='" .$category_info['id'] . "'>";
          $output .= "</form>";
          
          return $output; 
      }
      
      function get_categories_not_assigned_to_client($cid) {
          $db_function_obj = new DbFunctions();
          
          $categories = $db_function_obj->get_categories_not_assigned_to_client($cid);
          
          $output = "Categories not assinged to this client:<br />";
          
          foreach($categories as $cat) {    
              $output .= "<div style='margin: 8px; padding:5px; float: left; height: 25px; border: 1px solid'> 
                          <input onclick='addCategoryToClient(this);' type='checkbox' value='" . $cat['id'] . "' style='margin: -2px 0 0'>&nbsp;&nbsp;" . $cat['name'] . "</div>";
          }
          
          return $output;
      }  
      
      function get_categories_assigned_to_client($cid) {
          $db_function_obj = new DbFunctions();
          
          $categories = $db_function_obj->get_categories_assigned_to_client($cid);
          
          $output = "Categories assinged to this client:<br />";
          
          foreach($categories as $cat) {    
              $output .= "<div style='margin: 8px; padding:5px; float: left; height: 25px; border: 1px solid'>
                          <input onclick='removeCategoryToClient(this);' type='checkbox' value='" . $cat['id'] . "' style='margin: -2px 0 0'>&nbsp;&nbsp;" . $cat['name'] . "</div>";
          }
          
          return $output;
      }
  }
?>
