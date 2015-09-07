<?php
  class EmergencyCalls {
    private $conn; 
    
    function __construct() {
        $this->conn = new MySQLiDatabaseConnection();
    }
    function build_add_emergency_form(){
        $helper_obj = new Helper();
        global $base_path;
        $operator = new Operator();
        $output = '<div class="box box-bordered">';
        $output .= '<div class="box-title">
                      <h3><i class="icon-th-list"></i> Add Emergency Calls</h3>
                  </div>';
        $output .= "<div class='box-content nopadding'>";
        $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='add_emergency' id='add_emergency' method='post' action='$base_path" . "add_emergency'>";
                  
        $output .= "<div class='control-group'>
                        <label class='control-label' for='textarea'>name</label>
                        <div class='controls'>
                            <input id='textfield' class='input-xlarge' type='text' data-rule-required='true' placeholder='Name' name='name'>
                        </div>
                    </div>";             
        $output .= "<div class='control-group'>
                        <label class='control-label' for='numberfield'>phone</label>
                        <div class='controls'>
                            <input id='numberfield' class='input-xlarge' type='text' data-rule-number='true' data-rule-required='true' placeholder='Phone' name='phone'>
                        </div>
                    </div>";   
                       
        $output .= $operator->build_country_list('emergency');
                  
        $output .= "<input class=\"btn btn-primary\" type='submit' value='" . $helper_obj->t("Save") . "'>";
        $output .= "<input style='display: none' type='reset' id='add_emergency_reset'>";
        $output .= "</form>";
        $output .= "</div>";
        $output .= "</div><br /><br />";
                        
        return $output; 
    }

    function get_emergency(){
        $db_functions_obj = new DbFunctions();
        $emergencys = $db_functions_obj->get_all_emergencys();
                   
        $helper_obj = new Helper(); 

        $output = "<table id='add_emergency_table' class='table table-hover table-nomargin table-bordered'>
                  <tr>
                    <th>" . $helper_obj->t("ID") . "</th>
                    <th>" . $helper_obj->t("name") . "</th>
                    <th>" . $helper_obj->t("phone") . "</th>  
                    <th>" . $helper_obj->t("country") . "</th>  
                    <th>" . $helper_obj->t("Edit") . "</th>
                    <th>" . $helper_obj->t("Delete") . "</th>
                  </tr>";

        foreach($emergencys as $key => $emergency) {
            $class = $helper_obj->table_row_class($i);
             
            $output .= "<tr class='$class' id='emergency_$emergency->id'>
                          <td>" . $emergency->id . "</td>
                          <td>" . $emergency->name . "</td>
                          <td>" . $emergency->phone . "</td>
                          <td>" . $emergency->country_name . "</td>
                          <td><a href='javascript:void(0);' onclick='openEditEmergencyPopup($emergency->id)'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='deleteEmergency($emergency->id)'>
                                " . $helper_obj->t("Delete") . "</div>
                              </a>
                          </td>
                       </tr>";
        }             

        $output .= "</table>"; 

        return $output;           
    }

    function build_emergency_row($id) {
      $db_functions_obj = new DbFunctions(); 
      $helper_obj = new Helper();
      
      $emergency = $db_functions_obj->get_emergency_by_id($id);
        
      $class = $helper_obj->table_row_class($i); 
      
      $output = "<tr class='$class' id='emergency_$emergency->id'>
                      <td>" . $emergency->id . "</td>
                      <td>" . $emergency->name . "</td>
                      <td>" . $emergency->phone . "</td>
                      <td>" . $emergency->country_name . "</td>
                      <td><a href='javascript:void(0);' onclick='openEditEmergencyPopup($emergency->id)'>" . $helper_obj->t("Edit") . "</a></td>
                      <td><a href='javascript:void(0);' onclick='deleteEmergency($emergency->id)'>
                            " . $helper_obj->t("Delete") . "
                          </a>
                      </td>
                   </tr>";
                   
        return $output; 
    }

    function build_edit_emergency_popup($id) {
          $db_functions_obj = new DbFunctions();
          $helper_obj = new Helper();
          $emergency_info = $db_functions_obj->get_emergency_by_id($id);
          global $base_path;
          $operator = new Operator();                    
          
                    
          $output = "<div class='popup-header'>" . $helper_obj->t("Edit Emergency Call")."</div>";
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='edit_emergency' id='edit_emergency' method='post' action='$base_path" . "edit_emergency'>";
                      
          $output .= "<div class='control-group'>
                        <label class='control-label' for='textarea'>name</label>
                        <div class='controls'>
                            <input id='textfield' class='input-xlarge' type='text' data-rule-required='true' value='$emergency_info->name' placeholder='$emergency_info->name' name='name'>
                        </div>
                    </div>";             
          $output .= "<div class='control-group'>
                        <label class='control-label' for='numberfield'>phone</label>
                        <div class='controls'>
                            <input id='numberfield' class='input-xlarge' type='text' data-rule-number='true' data-rule-required='true' value='$emergency_info->phone' placeholder='$emergency_info->phone' name='phone'>
                        </div>
                    </div>";   
                       
          $output .= $operator->build_country_list('emergency',$emergency_info->country_id);
                     
          $output .= "<input type='hidden' name='emergency_id' value='" . $emergency_info->id . "'>";
          $output .= "<input class='btn btn-primary' type='submit' value='" . $helper_obj->t("Save") . "'>";
          $output .= "</form>";
          $output .= "<script>
                    $(document).ready(function() {        
                       $(\"#emergency_textfield\").datepicker();   
                     
                       $('#edit_emergency').ajaxForm(function(res) { 
                           var isvalid = $(\"#edit_emergency\").valid();
                           if (isvalid) { 
                               var data = res.split(\"***#***\");   
                               $('#emergency_' + data[1]).after(data[0]);
                               $('#emergency_' + data[1]).remove(); 
                               closePopup();
                           }  
                       }); 
                    });
                    </script>";
          return $output; 
      }
  }
?>
