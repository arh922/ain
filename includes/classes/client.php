<?php
  class Client {
      private $conn; 
      
      function __construct() {
          $this->conn = new MySQLiDatabaseConnection();
      }
      
      function get_all_clients(){
         $db_functions_obj = new DbFunctions();
         $helper_obj = new Helper();
         
         $clients = $db_functions_obj->get_all_clients();
         
         $output = "<table id='add_client_table' class='table table-hover table-nomargin table-bordered'>
                      <tr>
                        <th>" . $helper_obj->t("ID") . "</th>
                        <th>" . $helper_obj->t("Name") . "</th>
                        <th>" . $helper_obj->t("Logo") . "</th>
                        <th>" . $helper_obj->t("pem") . "</th>
                        <th>" . $helper_obj->t("api") . "</th>
                        <th>" . $helper_obj->t("Status") . "</th>
                        <th>" . $helper_obj->t("Date Added") . "</th>
                        <th>" . $helper_obj->t("Added By") . "</th> 
                        <th>" . $helper_obj->t("Date Updated") . "</th>
                        <th>" . $helper_obj->t("Updated By") . "</th>  
                        <th>" . $helper_obj->t("Edit") . "</th>
                        <th>" . $helper_obj->t("Delete") . "</th>
                      </tr>";

         foreach($clients as $key => $client) {
            $class = $helper_obj->table_row_class($i);
             
            $output .= "<tr class='$class' id='client_$client[id]'>
                          <td>" . $client['id'] . "</td>
                          <td>" . $client['name'] . "</td>
                          <td>" . $client['logo'] . "</td>
                          <td>" . $client['pem'] . "</td>
                          <td>" . $client['api'] . "</td>
                          <td><div id='client_status_$client[id]'>" . ($client['status'] == 1 ? $helper_obj->t("Active") : $helper_obj->t("Deactive")) . "</div></td>
                          <td>" . date(DATE_FORMAT, $client['date_added']) . "</td>
                          <td>" . $client['added_by'] . "</td>
                          <td>" . ($client['date_updated'] != "" ? date(DATE_FORMAT, $client['date_updated']) : "") . "</td>
                          <td>" . $client['updated_by'] . "</td>
                          <td><a href='javascript:void(0);' onclick='openEditClientPopup($client[id])'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='activeDeactiveClient($client[id])'>
                                <div id='deactive_$client[id]'>" . ($client['status'] == 1 ? $helper_obj->t("Deactivate") : $helper_obj->t("Activate")) . "</div>
                              </a>
                          </td>
                       </tr>";
         }             
        
         $output .= "</table>"; 
         
         return $output;           
      }
      
      function build_add_client_form(){
          $helper_obj = new Helper();
          global $base_path;
          
          $output = '<div class="box box-bordered">';
          $output .= '<div class="box-title">
                                <h3><i class="icon-th-list"></i> Add Client</h3>
                      </div>';
          $output .= "<div class='box-content nopadding'>";
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='add_client' id='add_client' method='post' action='$base_path" . "add_client'>";
        
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Client Name</label>
                        <div class='controls'>
                          <input class='input-xlarge' data-rule-required='true' data-rule-minlength='2' type='text' id='client_name' name='client_name' placeholder='" . $helper_obj->t("Name") . "'>
                        </div>
                      </div>";
          $output .= "<div class='control-group'>
                        <label class='control-label' for='client_logo'>Client Logo</label>   
                        <div class='controls'>
                          <input class='input-xlarge' data-rule-required='true' type='file' id='client_logo' name='client_logo' placeholder='" . $helper_obj->t("Logo") . "'>
                        </div>
                      </div>";
          $output .= "<input class=\"btn btn-primary\" type='submit' value='" . $helper_obj->t("Save") . "'>";
          $output .= "<input style='display: none' type='reset' id='add_client_reset'>";
          $output .= "</form>";
          $output .= "</div>";
          $output .= "</div><br /><br />";
         
          return $output;
      }
      
      function build_edit_client_popup($cid){
          $helper_obj = new Helper();
          $db_functions_obj = new DbFunctions();
        //  $validation_js_obj = new Validation_js();
          global $base_path;
          
          $client_info = $db_functions_obj->get_client_by_id($cid);
                              
         // $output = $validation_js_obj->edit_client_validation(); 
          $output = "<script>$(document).ready(function() {  $('#edit_client').ajaxForm(function(res) { 
                        var isvalid = $(\"#edit_client\").valid();
                        if (isvalid) { 
                            var data = res.split(\"***#***\");   
                            $('#client_' + data[1]).after(data[0]);
                            $('#client_' + data[1]).remove(); 
                            closePopup();
                        }  
                    }); });</script>";
          $output .= "<div class='popup-header'>" . $helper_obj->t("Edit Client") . " " . $client_info->name . "</div>";
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='edit_client' id='edit_client' method='post' action='$base_path" . "edit_client'>";
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Client Name</label>
                        <div class='controls'>
                           <input class='input-xlarge' data-rule-required='true' data-rule-minlength='2' value='" . $client_info->name . "' type='text' id='client_name_update' name='client_name_update' placeholder='" . $helper_obj->t("Name") . "'>
                        </div>
                     </div>";
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Client Logo</label>
                        <div class='controls'>
                          <input type='file' id='client_logo_update' name='client_logo_update' placeholder='" . $helper_obj->t("Logo") . "'>
                        </div>
                        <div style='margin-left: 380px;margin-top: -50px;'><img src=" . LOGO_PATH . $client_info->logo . " width=77 height=77></div>
                     </div>";
          $output .= ""; 
          $output .= "<input class='btn btn-primary' type='submit' value='" . $helper_obj->t("Save") . "'>";
          $output .= "<input type='hidden' name='client_id_updated' value='" . $client_info->id . "'>";
          $output .= "</form>";
          
          return $output; 
      }
      
      function check_client_logo_size($logo) {
          $logo_size = getimagesize($logo);
          pr($logo_size);
      }
      
      function edit_client($cid, $cname, $clogo){
          $db_functions_obj = new DbFunctions();  
          $db_functions_obj->edit_client($cid, $cname, $clogo);
      }
      
      function build_clients_list($page = 'add', $selected_client = "", $onclick = "", $style = "") {
          $db_functions_obj = new DbFunctions();
          $helper_obj = new Helper();
          
          $clients = $db_functions_obj->get_active_client();
          
          $output = '<div class="control-group">
                       <label class="control-label" for="select">Clients</label>
                       <div class="controls">';
                   
          $output .= "<select $style $onclick id='$page" . "_client' name='$page" . "_client'>";
          
          $output .= "<option value=''>" . $helper_obj->t("Select...") . "</option>";
          
          foreach($clients as $key => $value){
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
      
      function build_client_row($client_info){
        $helper_obj = new Helper();
        
        $output = "<tr id='client_$client_info->id'>
                     <td>" . $client_info->id . "</td>
                     <td>" . $client_info->name . "</td>
                     <td>" . $client_info->logo . "</td>
                     <td><div id='client_status_$client_info->id'>" . ($client_info->status == 1 ? $helper_obj->t("Active") : $helper_obj->t("Deactive")) . "</div></td>
                     <td>" . date(DATE_FORMAT, $client_info->date_added) . "</td>
                     <td>" . $client_info->added_by . "</td>
                     <td>" . ($client_info->date_updated != "" ? date(DATE_FORMAT, $client_info->date_updated) : "") . "</td>
                     <td>" . $client_info->updated_by . "</td>
                     <td><a href='javascript:void(0);' onclick='openEditClientPopup($client_info->id)'>" . $helper_obj->t("Edit") . "</a></td>
                     <td><a href='javascript:void(0);' onclick='activeDeactiveClient($client_info->id)'>
                          <div id='deactive_$client_info->id'>" . ($client_info->status == 0 ? $helper_obj->t("Activate") : $helper_obj->t("Deactivate")). "</div>
                         </a>
                     </td>
                  </tr>";
        return $output;
    }
  }