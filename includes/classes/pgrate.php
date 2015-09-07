<?php
  class Pgrate{
      private $conn; 
    
      function __construct() {
         $this->conn = new MySQLiDatabaseConnection();
      }
      
      function build_pgrate_list($page = 'add', $selected_client = "", $onclick = "", $style = "") {
          $db_functions_obj = new DbFunctions();
          $helper_obj = new Helper();
          
          $pgrates = $db_functions_obj->get_all_pgrate();  
     
          $output =  "<div class='control-group'> 
                        <label class='control-label' for='client_name'>PG Rate</label>
                        <div class='controls'>";
                        
          $output .= "<select class='input-xlarge' data-rule-required='true' $style $onclick id='$page" . "_pgrate' name='$page" . "_pgrate'>";
          
          $output .= "<option value=''>" . $helper_obj->t("Select...") . "</option>";
          
          foreach($pgrates as $key => $value){
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
      
      function build_add_pgrate_form() {
          $helper_obj = new Helper();
          global $base_path;
          
          $output = '<div class="box box-bordered">';
          $output .= '<div class="box-title">
                          <h3><i class="icon-th-list"></i> Add PG rate</h3>
                      </div>';
          $output .= "<div class='box-content nopadding'>";
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='add_pgrate' id='add_pgrate' method='post' action='$base_path" . "add_pgrate'>";
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>PG Rate Name</label>
                        <div class='controls'>
                          <input class='input-xlarge' data-rule-required='true' data-rule-minlength='2' type='text' id='pgrate_name' name='pgrate_name' placeholder='" . $helper_obj->t("Name") . "'>
                        </div>
                      </div>";
          $output .= "<input class=\"btn btn-primary\" type='submit' value='" . $helper_obj->t("Save") . "'>";
          $output .= "<input style='display: none' type='reset' id='add_pgrate_reset'>";
          $output .= "</form>";
          $output .= "</div>";
          $output .= "</div><br /><br />";
                            
          return $output; 
        
      } 
      
      function get_pgrates(){
          $db_functions_obj = new DbFunctions();
          $pgrates = $db_functions_obj->get_pgrates();
          $helper_obj = new Helper(); 
          
          $output = "<table id='add_pgrate_table' class='table table-hover table-nomargin table-bordered'>
                      <tr>
                        <th>" . $helper_obj->t("ID") . "</th>
                        <th>" . $helper_obj->t("Name") . "</th>
                        <th>" . $helper_obj->t("Edit") . "</th>
                        <th>" . $helper_obj->t("Delete") . "</th>
                      </tr>";

         foreach($pgrates as $key => $client) {
            $class = $helper_obj->table_row_class($i);
             
            $output .= "<tr class='$class' id='pgrate_$client[id]'>
                          <td>" . $client['id'] . "</td>
                          <td>" . $client['name'] . "</td>
                          <td><a href='javascript:void(0);' onclick='openEditPgratePopup($client[id])'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='deletePgrate($client[id])'>
                                " . $helper_obj->t("Delete") . "</div>
                              </a>
                          </td>
                       </tr>";
         }             
        
         $output .= "</table>"; 
         
         return $output;           
      }
      
      function build_pgrate_row($pgrate_id) {
          $db_functions_obj = new DbFunctions();  
          $helper_obj = new Helper();
          
          $pgrate = $db_functions_obj->get_pgrate_by_id($pgrate_id);
         
          $class = $helper_obj->table_row_class($i); 
                  
          $output = "<tr class='$class' id='pgrate_$pgrate_id'>
                          <td>" . $pgrate['id'] . "</td>
                          <td>" . $pgrate['name'] . "</td>
                          <td><a href='javascript:void(0);' onclick='openEditPgratePopup(" . $pgrate['id'] . ")'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='deletePgrate(" . $pgrate['id'] . ")'>
                                " . $helper_obj->t("Delete") . "</div>
                              </a>
                          </td>
                       </tr>";
                       
          return $output;
      }
      
      function build_edit_pgrate_popup($pgrate_id) {
          $db_functions_obj = new DbFunctions();
          $helper_obj = new Helper();
          $pgrate_info = $db_functions_obj->get_pgrate_by_id($pgrate_id);
          global $base_path;
                              
          $output = "<script>$(document).ready(function() {  
                        $('#edit_pgrate').ajaxForm(function(res) { 
                        var isvalid = $(\"#edit_pgrate\").valid();
                        if (isvalid) { 
                            var data = res.split(\"***#***\");   
                            $('#pgrate_' + data[1]).after(data[0]);
                            $('#pgrate_' + data[1]).remove(); 
                            closePopup();
                        }  
                    }); });</script>";
                    
          $output .= "<div class='popup-header'>" . $helper_obj->t("Edit PG rate") . " " . $pgrate_info['name'] . "</div>";
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='edit_pgrate' id='edit_pgrate' method='post' action='$base_path" . "edit_pgrate'>";
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>PG rate Name</label>
                        <div class='controls'>
                           <input class='input-xlarge' data-rule-required='true' data-rule-minlength='2' value='" . $pgrate_info['name'] . "' type='text' id='pgrate_name_update' name='pgrate_name_update' placeholder='" . $helper_obj->t("Name") . "'>
                        </div>
                     </div>";
          $output .= ""; 
          $output .= "<input class='btn btn-primary' type='submit' value='" . $helper_obj->t("Save") . "'>";
          $output .= "<input type='hidden' name='pgrate_id_updated' value='" .$pgrate_info['id'] . "'>";
          $output .= "</form>";
          
          return $output; 
      }
  
  }
?>
