<?php
  class RSSRource {
      private $conn; 
            
      function __construct() {
      }
      
      function build_add_rss_source_form(){
          global $base_path;
          $helper_obj = new Helper();
          
          $output = '<div class="box box-bordered">';
          $output .= '<div class="box-title">
                           <h3><i class="icon-th-list"></i> Add RSS Source</h3>
                        </div>';
                                
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' method='post' id='add_rss_source' name='add_rss_source' action='" . $base_path ."add_rss_source'>";
       
          $output .= "<div class='control-group'> 
                            <label class='control-label' for='client_name'>RSS Source Name</label>
                            <div class='controls'> 
                              <input style='width:400px' class='input-xlarge' data-rule-required='true' type='text' name='rss_name' id='rss_name' placeholder='RSS Name'>  
                            </div>
                         </div>"; 
                         
          $output .= "<div class='control-group'> 
                            <label class='control-label' for='client_name'>RSS Link</label>
                            <div class='controls'> 
                              <input style='width:400px' class='input-xlarge' data-rule-required='true' data-rule-url='true' type='text' name='rss_link' id='rss_link' placeholder='RSS Link'>  
                            </div>
                         </div>"; 
            
          
          $output .= "<input class='btn btn-primary' type='submit' value='" . $helper_obj->t('Save') . "'>";  
          $output .= "<input type='reset' style='display: none' id='add_rss_source_reset'></div>";  
                                     
          $output .= "</form><br /><br />";   
                         
          return $output;
      }
      
      function get_all_rss_sources(){
          $db_function_obj = new DbFunctions();
          $helper_obj = new Helper();
          
          $rss_sources = $db_function_obj->get_all_rss_sources();
                          
          $output = "<table id='add_rss_source_table' style='font-size: 10px' class='table table-hover table-nomargin table-bordered'>
                      <tr>
                        <th>" . $helper_obj->t("ID") . "</th>
                        <th>" . $helper_obj->t("Title") . "</th>     
                        <th>" . $helper_obj->t("URL") . "</th>   
                        <th>" . $helper_obj->t("Edit") . "</th>
                        <th>" . $helper_obj->t("Delete") . "</th>
                      </tr>";

         foreach($rss_sources as $key => $data) {
            $class = $helper_obj->table_row_class($i);
                          
            $output .= "<tr id='rss_$data->id'>
                          <td>" . $data->id . "</td>
                          <td>" . $data->source_name . "</td>      
                          <td>" . $data->link . "</td>          
                          <td><a href='javascript:void(0);' onclick='openEditRssSourcePopup($data->id)'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='deleteRssSource($data->id)'>" . $helper_obj->t("Delete") . "</a></td>
                       </tr>";
         }
        
        return $output;         
      }
      
      function build_rss_source_row($id) {
        $db_functions_obj = new DbFunctions();
        $helper_obj = new Helper();
        
        $data = $db_functions_obj->get_rss_source_by_id($id);
         
        $output = "<tr id='rss_$data->id'>
                          <td>" . $data->id . "</td>
                          <td>" . $data->source_name . "</td>      
                          <td>" . $data->link . "</td> 
                          <td><a href='javascript:void(0);' onclick='openEditRssSourcePopup($data->id)'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='deleteRssSource($data->id)'>" . $helper_obj->t("Delete") . "</a></td>
                       </tr>";
                       
        return $output;
    }
    
    function build_edit_rss_source_popup($id) {
        $db_function_obj = new DbFunctions();
        $rss_source = $db_function_obj->get_rss_source_by_id($id);

        global $base_path;
        $helper_obj = new Helper();

        $output = "<script>$(document).ready(function() {  $('#edit_rss_source').ajaxForm(function(res) { 
                        var isvalid = $(\"#edit_rss_source\").valid();
                        if (isvalid) { 
                            var data = res.split(\"***#***\");   
                            $('#rss_' + data[1]).after(data[0]);
                            $('#rss_' + data[1]).remove(); 
                            closePopup();
                        }  
                    }); });</script>";
                    
             
        $output .= "<div class='popup-header'>" . $helper_obj->t("Edit RSS Source") . " " . $rss_source->source_name . "</div>";
                         
        $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' method='post' id='edit_rss_source' name='edit_rss_source' action='" . $base_path ."edit_rss_source'>";

        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>RSS Source Name</label>
                        <div class='controls'> 
                          <input style='width:400px' value='" . $rss_source->source_name . "' class='input-xlarge' data-rule-required='true' type='text' name='rss_name_updated' id='rss_name_updated' placeholder='RSS Name'>  
                        </div>
                     </div>"; 
                     
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>RSS Link</label>
                        <div class='controls'> 
                          <input style='width:400px' value='" . $rss_source->link . "' class='input-xlarge' data-rule-required='true' data-rule-url='true' type='text' name='rss_link_updated' id='rss_link_updated' placeholder='RSS Link'>  
                        </div>
                     </div>"; 


        $output .= "<input class='btn btn-primary' type='submit' value='" . $helper_obj->t('Save') . "'>";  
        $output .= "<input type='hidden' name='edit_rss_source' id='edit_rss_source' value='" . $id . "'>";
                               
        $output .= "</form><br /><br />";   
                     
        return $output;
    }
    
    function build_rss_source_list($page = 'add', $selected_rss_source = "", $onclick = "", $style = "") {
          $db_functions_obj = new DbFunctions();
          $helper_obj = new Helper();
          
          $rss_sources = $db_functions_obj->get_all_rss_sources();  
     
          $output =  "<div class='control-group'> 
                        <label class='control-label' for='client_name'>RSS Sources</label>
                        <div class='controls'>";
                        
          $output .= "<select class='input-xlarge' data-rule-required='true' $style $onclick id='$page" . "_rss_source' name='$page" . "_rss_source'>";
          
          $output .= "<option value=''>" . $helper_obj->t("Select...") . "</option>";
          
          foreach($rss_sources as $key => $value){
              if ($selected_rss_source != ""){
                $output .= "<option ";
                if ($selected_rss_source == $value->id) {
                      $output .= " selected='selected' ";
                }
                $output .= "value='$value->id'>$value->source_name</option>";
              }
              else{
                  $output .= "<option value='$value->id'>$value->source_name</option>";  
              }
          }
          $output .= "</select>";
          $output .= "</div>";
          $output .= "</div>";
          
          return $output;
      }
    
    function build_country_category_rss_form(){
        $op_obj = new Operator();
        $cat_obj = new Category();
        global $user;
        global $base_path; 

        $cid = $user->cid;
                 
        $helper_obj = new Helper();
        $output = "";
       
        $output = '<div class="box box-bordered">';
        $output .= '<div class="box-title">
                       <h3><i class="icon-th-list"></i> Add RSS Source To Category And Country</h3>
                    </div>';     
                            
        $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' method='post' id='add_country_category_rss' name='add_country_category_rss' action='" . $base_path ."add_country_category_rss'>";
             
        $output .= $op_obj->build_country_list("add");
        $output .= $cat_obj->build_categories_list("add");
        $output .= $this->build_rss_source_list("add");
        
        $output .= "<input class='btn btn-primary' type='submit' value='" . $helper_obj->t('Save') . "'>";  
        $output .= "<input type='reset' style='display: none' id='add_rss_source_reset'></div>";  
                                    
        $output .= "</form><br /><br />";  

        return $output;
    }
    
    function get_country_category_rss_source(){
        $db_functions_obj = new DbFunctions();
        $helper_obj = new Helper();
        
        $country_category_rss = $db_functions_obj->get_country_category_rss_source();
        
        $output = "";
        
        $output = "<table id='add_country_category_rss_source_table' style='font-size: 10px' class='table table-hover table-nomargin table-bordered'>
                      <tr>
                        <th>" . $helper_obj->t("ID") . "</th>
                        <th>" . $helper_obj->t("Country") . "</th>     
                        <th>" . $helper_obj->t("Category") . "</th>   
                        <th>" . $helper_obj->t("RSS Source") . "</th>
                        <th>" . $helper_obj->t("Delete") . "</th>
                      </tr>";

         foreach($country_category_rss as $key => $data) {
            $class = $helper_obj->table_row_class($i);
                          
            $output .= "<tr id='rss_$data->id'>
                          <td>" . $data->id . "</td>
                          <td>" . $data->country_name . "</td>      
                          <td>" . $data->category_name . "</td>  
                          <td>" . $data->source_name . "</td>  
                          <td><a href='javascript:void(0);' onclick='deleteCountryCategoryRssSource($data->id)'>" . $helper_obj->t("Delete") . "</a></td>
                       </tr>";
         }
        
        return $output;       
    }
    
    function build_country_category_rss_source_row($id) {
        $db_functions_obj = new DbFunctions();
        $helper_obj = new Helper();
        
        $data = $db_functions_obj->get_country_category_rss_source_by_id($id);
                 
        $output = "<tr id='rss_$data->id'>
                          <td>" . $data->id . "</td>
                          <td>" . $data->country_name . "</td>      
                          <td>" . $data->category_name . "</td>  
                          <td>" . $data->source_name . "</td>  
                          <td><a href='javascript:void(0);' onclick='deleteCountryCategoryRssSource($data->id)'>" . $helper_obj->t("Delete") . "</a></td>
                       </tr>";
                       
        return $output;
    }
    
  }
?>
