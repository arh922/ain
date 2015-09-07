<?php
  class Tag {                      
      private $conn; 
    
      function __construct() {
        //$this->conn = new MySQLiDatabaseConnection();
      }
      
      function build_tags_list($page = 'add', $selected_value = "", $onclick = "", $style = "", $from = '', $offset = '') {
          $db_functions_obj = new DbFunctions();
          $helper_obj = new Helper();
          
          $pgrates = $db_functions_obj->get_all_tags($from, $offset);  
     
          $output =  "<div class=\"control-group\">
                                        <label for=\"textfield\" class=\"control-label\">Tags</label>
                                        <div class=\"controls\">";
                        
          $output .= "<select multiple=\"multiple\" class='input-xlarge' data-rule-required='true' $style $onclick id='$page" . "_tag[]' name='$page" . "_tag[]'>";
          
          foreach($pgrates as $key => $value){
              if ($selected_value != ""){
                $output .= "<option ";
                if ( in_array( $value['id'], $selected_value)) {
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
      
      function build_tags_single_list($page = 'add', $selected_value = "", $onclick = "", $style = "", $from = '', $offset = '') {
          $db_functions_obj = new DbFunctions();
          $helper_obj = new Helper();
          
          $pgrates = $db_functions_obj->get_all_tags($from, $offset);  
     
          $output =  "<div class=\"control-group\">
                                        <label for=\"textfield\" class=\"control-label\">Tags</label>
                                        <div class=\"controls\">";
                        
          $output .= "<select class='input-xlarge' data-rule-required='true' $style $onclick id='$page" . "_tag' name='$page" . "_tag'>";
          
          foreach($pgrates as $key => $value){
              if ($selected_value != ""){
                $output .= "<option ";
                if ( in_array( $value['id'], $selected_value)) {
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
      
      function add_tags_to_article($aid, $tag_id) {
          $db_functions_obj = new DbFunctions();
          
          $db_functions_obj->add_tags_to_article($aid, $tag_id);
      }
          
      function build_add_tag_form(){
          $helper_obj = new Helper();
          global $base_path;
          
          $output = '<div class="box box-bordered">';
          $output .= '<div class="box-title">
                          <h3><i class="icon-th-list"></i> Add Tag</h3>
                      </div>';
          $output .= "<div class='box-content nopadding'>";
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='add_tag' id='add_tag' method='post' action='$base_path" . "add_tag'>";
        
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='tag_name'>Tag Name</label>
                        <div class='controls'>
                          <input class='input-xlarge' data-rule-required='true' data-rule-minlength='2' type='text' id='tag_name' name='tag_name' placeholder='" . $helper_obj->t("Name") . "'>
                        </div>
                      </div>";  
                      
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image</label>
                        <div class='controls'>
                           <input class='input-xlarge' data-rule-required='true' accept='image/*' type='file' name='image' id='image'>                       
                        </div> 
                    </div>"; 
                      
          $output .= "<input class=\"btn btn-primary\" type='submit' value='" . $helper_obj->t("Save") . "'>";
          $output .= "<input style='display: none' type='reset' id='add_tag_reset'>";
          $output .= "</form>";
          $output .= "</div>";
          $output .= "</div><br /><br />";
                            
          return $output; 
      }
      
      function get_tags(){
          $db_functions_obj = new DbFunctions();
          $categories = $db_functions_obj->get_all_tags();
                       
          $helper_obj = new Helper(); 
          
          $output = "<table id='add_tag_table' class='table table-hover table-nomargin table-bordered'>
                      <tr>
                        <th>" . $helper_obj->t("ID") . "</th>
                        <th>" . $helper_obj->t("Name") . "</th>  
                        <th>" . $helper_obj->t("Image") . "</th>  
                        <th>" . $helper_obj->t("Edit") . "</th>
                        <th>" . $helper_obj->t("Delete") . "</th>
                      </tr>";

         foreach($categories as $key => $tag) {
            $class = $helper_obj->table_row_class($i);
                                  
            $output .= "<tr class='$class' id='tag_$tag[id]'>
                          <td>" . $tag['id'] . "</td>
                          <td>" . $tag['name'] . "</td> 
                          <td><img src='" . TAGS_THUMBNAIL_IMAGES_PATH . $tag['image'] . "' width='70' /></td> 
                          <td><a href='javascript:void(0);' onclick='openEditTagPopup($tag[id])'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='deleteTag($tag[id])'>
                                " . $helper_obj->t("Delete") . "</div>
                              </a>
                          </td>
                       </tr>";
         }             
        
         $output .= "</table>"; 
         
         return $output;          
      }
      
      function build_tag_row ($id){
          $db_functions_obj = new DbFunctions(); 
          $helper_obj = new Helper();
          
          $tag = $db_functions_obj->get_tag_by_id($id);
            
          $class = $helper_obj->table_row_class($i); 
          
          $output = "<tr class='$class' id='tag_$tag->id'>
                          <td>" . $tag['id'] . "</td>
                          <td>" . $tag['name'] . "</td> 
                          <td><img src='" . TAGS_THUMBNAIL_IMAGES_PATH . $tag['image'] . "' width='70' /></td> 
                          <td><a href='javascript:void(0);' onclick='openEditTagPopup($tag[id])'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='deleteTag($tag[id])'>
                                " . $helper_obj->t("Delete") . "</div>
                              </a>
                          </td>
                       </tr>";
                       
            return $output; 
      }
      
      function build_edit_tag_popup($tag_id) {
          $db_functions_obj = new DbFunctions();
          $helper_obj = new Helper();
          $tag_info = $db_functions_obj->get_tag_by_id($tag_id);
          global $base_path;
                         
          $output = "<script>$(document).ready(function() {  
                        $('#edit_tag').ajaxForm(function(res) { 
                        var isvalid = $(\"#edit_tag\").valid();
                        if (isvalid) { 
                            var data = res.split(\"***#***\");   
                            $('#tag_' + data[1]).after(data[0]);
                            $('#tag_' + data[1]).remove(); 
                            closePopup();
                        }  
                    }); });</script>";
                    
          $output .= "<div class='popup-header'>" . $helper_obj->t("Edit Tag") . " (" . $tag_info['name'] . ")</div>";
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='edit_tag' id='edit_tag' method='post' action='$base_path" . "edit_tag'>";
         
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='tag_name'>Tag Name</label>
                        <div class='controls'>
                          <input value ='" . $tag_info['name'] . "' class='input-xlarge' data-rule-required='true' data-rule-minlength='2' type='text' id='tag_name_update' name='tag_name_update' placeholder='" . $helper_obj->t("Name") . "'>
                        </div>
                      </div>";
                      
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image</label>
                        <div class='controls'>
                           <input class='input-xlarge' data-rule-required='true' accept='image/*' type='file' name='image_update' id='image_update'>                       
                        </div> 
                        <div style='margin-left: 380px;margin-top: -50px;'><img src=" . TAGS_THUMBNAIL_IMAGES_PATH . $tag_info['image'] . " width=55 height=55></div> 
                    </div>";
                                         
          $output .= "<input class='btn btn-primary' type='submit' value='" . $helper_obj->t("Save") . "'>";
          $output .= "<input type='hidden' name='tag_id_updated' value='" .$tag_info['id'] . "'>";
          $output .= "</form>";
          
          return $output; 
      }
      
      function build_related_tags_form(){
           $helper_obj = new Helper();
           $tag_obj = new Tag();
           global $base_path;
           
         //  $limitation = explode("_",$limit);
         //  
         //  $from = $limitation[0];
         //  $offset = $limitation[1];
          
          $output = '<div class="box box-bordered">';
          $output .= '<div class="box-title">
                          <h3><i class="icon-th-list"></i> Add Realted Tags</h3>
                      </div>';
          $output .= "<div class='box-content nopadding'>";
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='add_related_tag' id='add_related_tag' method='post' action='$base_path" . "add_related_tag'>";
        
          //$output .= $tag_obj->build_tags_single_list("add", "", "onchange='getRelatedTags(this)'", "", $from, $offset); 
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='tag_name'>Parent Tag</label>
                        <div class='controls'>
                          <input class='input-xlarge' data-rule-required='true' data-rule-minlength='2' type='text' id='main_tag_name' name='main_tag_name' placeholder='" . $helper_obj->t("Name") . "'>
                        </div>
                      </div>"; 
          $output .= "<input class=\"btn btn-primary\" type='button' value='Search' onclick=getRelatedTags()>";
         
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='tag_name'>Related Tags Name</label>
                        <div class='controls' id='related_tags_div'>
                          
                        </div>
                      </div>"; 
                      
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='tag_name'>New Related Tag Name</label>
                        <div class='controls'>
                          <input class='input-xlarge' data-rule-required='true' data-rule-minlength='2' type='text' id='tag_name' name='tag_name' placeholder='" . $helper_obj->t("Name") . "'>
                          <div>كلمة1,كلمة2,كلمة3</div> 
                        </div>
                      </div>";  
                      
              
          $output .= "<input class=\"btn btn-primary\" type='submit' value='" . $helper_obj->t("Save") . "'>";
          $output .= "<input style='display: none' type='reset' id='add_tag_reset'>";
          $output .= "</form>";
          $output .= "</div>";
          $output .= "</div><br /><br />";
                            
          return $output; 
      }
      
      function get_related_tags($tid) {
          $db_function_obj = new DbFunctions();
          
          $tags = $db_function_obj->get_related_tags($tid);
                   //   pr($tags);
          $table = "<input type='hidden' id='parent_id' name='parent_id' value='" . @$tags[0]['id'] . "'>
                       <input type='hidden' id='parent_name' name='parent_name' value='" . @$tags[0]['name'] . "'>
                    <table id='synonyms_table' border='1' style='width: 400px; border: 1px solid !important;'><tr><td>parent</td><td>name</td><td>synonyms</td><td>&nbsp;</td></tr>";
          
          foreach($tags as $tag) {   //pr($tag); 
              $table .= "<tr id='tag_$tag[id]'><td>$tag[parent]</td>
                             <td>$tag[name]</td>
                             <td>$tag[synonyms]</td>
                             <td><a onclick='deleteReletadTag($tag[id])' href='javascript:void(0);'>X</a></td>
                             </tr>";
          }
          
          $table .= "</table>";
          
          return $table;
      }
      
      function tags_and_synonyms(){
          $db_function_obj = new DbFunctions();
          $tags = $db_function_obj->tags_and_synonyms();
          
          $table = '<div class="box box-bordered">';
          $table .= '<div class="box-title">
                          <h3><i class="icon-th-list"></i> Add Realted Tags</h3>
                      </div>';
          $table .= "<div class='box-content nopadding'>";
          
          $table .= "<table id='synonyms_table' border='1' style='width: 400px; border: 1px solid !important;'>
                      <tr><td>parent</td><td>name</td><td>synonyms</td><td><br /></td></tr>";
      
          foreach($tags as $tag) {
              //  $tag['synonyms'] = str_replace("DDD", "<", $tag['synonyms']); 
              //  $tag['synonyms'] = str_replace("CCC", ">", $tag['synonyms']); 
            //    $tag['synonyms'] = str_replace("EEE", "</a>", $tag['synonyms']); 
              
                $table .= "<tr id='tag_$tag[id]'>
                             <td>$tag[parent]</td>
                             <td>$tag[name]</td>
                             <td id='synonyms_$tag[id]'>$tag[synonyms]</td>
                             <td>
                                  <input type='text' style='500px' id='related_tags_$tag[id]' />
                                  word1,word2,word3,...  <br />
                                  <input type='button' value='Save' onclick='SaveRelatedTags($tag[parent], \"$tag[name]\")' />
                             </td>
                          </tr>";
          }
          
          $table .= "</table>";
          $table .= "</div>";
          $table .= "</div>";
          
          return $table;
      }
     
  }
?>
