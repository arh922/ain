<?php
  class Operator{
      private $conn; 
    
      function __construct() {
         $this->conn = new MySQLiDatabaseConnection();
      }
      
      function build_operator_list($page = 'add', $selected_client = "", $onclick = "", $style = "") {
          $db_functions_obj = new DbFunctions();
          $helper_obj = new Helper();
          
          $operators = $db_functions_obj->get_all_operator();  
     
          $output =  "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Operator</label>
                        <div class='controls'>";
                        
          $output .= "<select class='input-xlarge' data-rule-required='true' $style $onclick id='$page" . "_operator' name='$page" . "_operator'>";
          
          $output .= "<option value=''>" . $helper_obj->t("Select...") . "</option>";
          
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
          $output .= "</div>";
          $output .= "</div>";
          
          return $output;
      }      
      
      function build_country_list($page = 'add', $selected_client = "", $onclick = "", $style = "") {
          $db_functions_obj = new DbFunctions();
          $helper_obj = new Helper();
          
          $operators = $db_functions_obj->get_country();  
     
          $output =  "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Country</label>
                        <div class='controls'>";
                        
          $output .= "<select class='input-xlarge' data-rule-required='true' $style $onclick id='$page" . "_country' name='$page" . "_country'>";
          
          $output .= "<option value=''>" . $helper_obj->t("Select...") . "</option>";
          
          foreach($operators as $key => $value){
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
      
      function build_add_operator_form() {
          $helper_obj = new Helper();
          global $base_path;
          
          $output = '<div class="box box-bordered">';
          $output .= '<div class="box-title">
                          <h3><i class="icon-th-list"></i> Add Operator</h3>
                      </div>';
          $output .= "<div class='box-content nopadding'>";
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='add_operator' id='add_operator' method='post' action='$base_path" . "add_operator'>";
       
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Operator Name</label>
                        <div class='controls'>
                          <input class='input-xlarge' data-rule-required='true' data-rule-minlength='2' type='text' id='operator_name' name='operator_name' placeholder='" . $helper_obj->t("Name") . "'>
                        </div>
                      </div>";
        
          $output .= $this->build_country_list();
          
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Paid Shortcode</label>
                        <div class='controls'>
                          <input data-rule-number='true' class='input-xlarge' data-rule-required='true' data-rule-minlength='4' type='text' id='paid_sc' name='paid_sc' placeholder='" . $helper_obj->t("Paid Shortcode") . "'>
                        </div>
                      </div>";
                      
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Free Shortcode</label>
                        <div class='controls'>
                          <input data-rule-number='true' class='input-xlarge' data-rule-required='true' data-rule-minlength='4' type='text' id='free_sc' name='free_sc' placeholder='" . $helper_obj->t("Free Shortcode") . "'>
                        </div>
                      </div>";
                      
          $output .= '<div class="control-group"> 
                        <label for="client_name" class="control-label">Type</label>
                        <div class="controls">
                            <select name="type" id="type" data-rule-required="true" class="input-xlarge">
                                <option value="">Select...</option>
                                <option value="MO">MO</option>
                                <option value="MT">MT</option>
                            </select>
                        </div></div>';
          
          $output .= "<input class=\"btn btn-primary\" type='submit' value='" . $helper_obj->t("Save") . "'>";
          $output .= "<input style='display: none' type='reset' id='add_operator_reset'>";
          $output .= "</form>";
          $output .= "</div>";
          $output .= "</div><br /><br />";
                            
          return $output; 
        
      } 
      
      function get_all_operators(){
          $db_functions_obj = new DbFunctions();
          $operators = $db_functions_obj->get_operators();
          $helper_obj = new Helper(); 
          
          $output = "<table id='add_operator_table' class='table table-hover table-nomargin table-bordered'>
                      <tr>
                        <th>" . $helper_obj->t("ID") . "</th>
                        <th>" . $helper_obj->t("Name") . "</th>
                        <th>" . $helper_obj->t("Country") . "</th>
                        <th>" . $helper_obj->t("Paid Shortcode") . "</th>
                        <th>" . $helper_obj->t("Free Shortcode") . "</th>
                        <th>" . $helper_obj->t("Type") . "</th>
                        <th>" . $helper_obj->t("Edit") . "</th>
                        <th>" . $helper_obj->t("Delete") . "</th>
                      </tr>";

         foreach($operators as $key => $client) {
            $class = $helper_obj->table_row_class($i);
             
            $output .= "<tr class='$class' id='operator_$client[id]'>
                          <td>" . $client['id'] . "</td>
                          <td>" . $client['op_name'] . "</td>
                          <td>" . $client['country_name'] . "</td>
                          <td>" . $client['paid_shortcode'] . "</td>
                          <td>" . $client['free_shortcode'] . "</td>
                          <td>" . $client['type'] . "</td>
                          <td><a href='javascript:void(0);' onclick='openEditOperatorPopup($client[id])'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='deleteOperator($client[id])'>
                                " . $helper_obj->t("Delete") . "</div>
                              </a>
                          </td>
                       </tr>";
         }             
        
         $output .= "</table>"; 
         
         return $output;           
      }
      
      function build_operator_row($operator_id) {
          $db_functions_obj = new DbFunctions();  
          $helper_obj = new Helper();
          
          $operator = $db_functions_obj->get_operator_by_id($operator_id);
         
          $class = $helper_obj->table_row_class($i); 
                  
          $output = "<tr class='$class' id='operator_$operator_id'>
                          <td>" . $operator['id'] . "</td>
                          <td>" . $operator['name'] . "</td>
                          <td>" . $operator['country_name'] . "</td>
                          <td>" . $operator['paid_shortcode'] . "</td>
                          <td>" . $operator['free_shortcode'] . "</td>
                          <td>" . $operator['type'] . "</td>
                          <td><a href='javascript:void(0);' onclick='openEditOperatorPopup(" . $operator['id'] . ")'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='deleteOperator(" . $operator['id'] . ")'>
                                " . $helper_obj->t("Delete") . "</div>
                              </a>
                          </td>
                       </tr>";
                       
          return $output;
      }
      
      function open_edit_operator_popup($operator_id) {
          $db_functions_obj = new DbFunctions();
          $helper_obj = new Helper();
          $operator_info = $db_functions_obj->get_operator_by_id($operator_id);
          global $base_path;
                              
          $output = "<script>$(document).ready(function() {  
                        $('#edit_operator').ajaxForm(function(res) { 
                        var isvalid = $(\"#edit_operator\").valid();
                        if (isvalid) { 
                            var data = res.split(\"***#***\");   
                            $('#operator_' + data[1]).after(data[0]);
                            $('#operator_' + data[1]).remove(); 
                            closePopup();
                        }  
                    }); });</script>";
                    
          $output .= "<div class='popup-header'>" . $helper_obj->t("Edit Operator") . " " . $operator_info['name'] . "</div>";
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='edit_operator' id='edit_operator' method='post' action='$base_path" . "edit_operator'>";
         
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Operator Name</label>
                        <div class='controls'>
                           <input class='input-xlarge' data-rule-required='true' data-rule-minlength='2' value='" . $operator_info['name'] . "' type='text' id='operator_name_updated' name='operator_name_updated' placeholder='" . $helper_obj->t("Name") . "'>
                        </div>
                     </div>";
                     
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Paid Shortcode</label>
                        <div class='controls'>
                          <input value='" . $operator_info['paid_shortcode'] . "' data-rule-number='true' class='input-xlarge' data-rule-required='true' data-rule-minlength='4' type='text' id='paid_sc_updated' name='paid_sc_updated' placeholder='" . $helper_obj->t("Paid Shortcode") . "'>
                        </div>
                      </div>";
                      
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Free Shortcode</label>
                        <div class='controls'>
                          <input value='" . $operator_info['free_shortcode'] . "' data-rule-number='true' class='input-xlarge' data-rule-required='true' data-rule-minlength='4' type='text' id='free_sc_updated' name='free_sc_updated' placeholder='" . $helper_obj->t("Free Shortcode") . "'>
                        </div>
                      </div>";
          
          $selected_mt = '';
          $selected_mo = '';
                                 
          if ($operator_info['type'] == 'MT') $selected_mt = 'selected="selected"'; 
          if ($operator_info['type'] == 'MO') $selected_mo = 'selected="selected"';
           
          $output .= '<div class="control-group"> 
                        <label for="client_name" class="control-label">Type</label>
                        <div class="controls">
                            <select name="type_updated" id="type_updated" data-rule-required="true" class="input-xlarge">
                                <option value="">Select...</option>
                                <option ' . $selected_mo . ' value="MO">MO</option>
                                <option ' . $selected_mt . 'value="MT">MT</option>
                            </select>
                        </div></div>';
        
          $output .= ""; 
          $output .= "<input class='btn btn-primary' type='submit' value='" . $helper_obj->t("Save") . "'>";
          $output .= "<input type='hidden' name='operator_id_updated' value='" .$operator_info['id'] . "'>";
          $output .= "</form>";
          
          return $output; 
      }
  
  }
?>
