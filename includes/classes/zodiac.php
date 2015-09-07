<?php
  class Zodiac {
    private $conn; 
    
    function __construct() {
        $this->conn = new MySQLiDatabaseConnection();
    }
    function build_add_zodiac_form(){
        $helper_obj = new Helper();
        global $base_path;
        $operator = new Operator();
        $output = '<div class="box box-bordered">';
        $output .= '<div class="box-title">
                      <h3><i class="icon-th-list"></i> Add Zodiac</h3>
                  </div>';
        $output .= "<div class='box-content nopadding'>";
        $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='add_zodiac' id='add_zodiac' method='post' action='$base_path" . "add_zodiac'>";
                  
        $output .= $this->build_zodiac_list();
                     
        $output .= "<div class='control-group'>
                        <label class='control-label' for='textarea'>body</label>
                        <div class='controls'>
                            <textarea id='textarea' class='input-block-level' data-rule-required='true' rows='5' name='body'></textarea>
                        </div>
                    </div>";   
                       
                  
        $output .= "<input class=\"btn btn-primary\" type='submit' value='" . $helper_obj->t("Save") . "'>";
        $output .= "<input style='display: none' type='reset' id='add_zodiac_reset'>";
        $output .= "</form>";
        $output .= "</div>";
        $output .= "</div><br /><br />";
                        
        return $output; 
    }
        
      
     function build_zodiac_list($selected_client = "") {
          $db_functions_obj = new DbFunctions();
          $helper_obj = new Helper();
          
          $zodiac = $db_functions_obj->get_zodiac();  
     
          $output =  "<div class='control-group'> 
                        <label class='control-label' for='client_name'>zodiac</label>
                        <div class='controls'>";
                        
          $output .= "<select class='input-xlarge' data-rule-required='true' id='name' name='name'>";
          
          $output .= "<option value=''>" . $helper_obj->t("Select...") . "</option>";
          
          $output .= '<option value="برج الحمل">برج الحمل</option>';
          $output .= '<option value="برج الثور">برج الثور</option>';
          $output .= '<option value="برج الجوزاء">برج الجوزاء</option>';
          
          $output .= '<option value="برج السرطان">برج السرطان</option>';
          $output .= '<option value="برج الأسد">برج الأسد</option>';
          $output .= '<option value="برج العذراء">برج العذراء</option>';
          
          $output .= '<option value="برج الميزان">برج الميزان</option>';
          $output .= '<option value="برج العقرب">برج العقرب</option>';
          $output .= '<option value="برج القوس">برج القوس</option>';
          
          $output .= '<option value="برج الجدي">برج الجدي</option>';
          $output .= '<option value="برج الدلو">برج الدلو</option>';
          $output .= '<option value="برج الحوت">برج الحوت</option>';
         /* foreach($zodiac as $key => $value){
              if ($selected_client != ""){
                $output .= "<option ";
                if ($selected_client == $value->name) {
                      $output .= " selected='selected' ";
                }
                $output .= "value='$value->name'>$value->name</option>";
              }
              else{
                  $output .= "<option value='$value->name'>$value->name</option>";  
              }
          }    */
          $output .= "</select>";
          $output .= "</div>";
          $output .= "</div>";
          
          return $output;
      }
      
    function get_zodiac(){
        $db_functions_obj = new DbFunctions();
        $zodiacs = $db_functions_obj->get_all_zodiacs();
                   
        $helper_obj = new Helper(); 

        $output = "<table id='add_zodiac_table' class='table table-hover table-nomargin table-bordered'>
                  <tr>
                    <th>" . $helper_obj->t("ID") . "</th>
                    <th>" . $helper_obj->t("name") . "</th>
                    <th>" . $helper_obj->t("body") . "</th>  
                    <th>" . $helper_obj->t("Edit") . "</th>
                    <th>" . $helper_obj->t("Delete") . "</th>
                  </tr>";

        foreach($zodiacs as $key => $zodiac) {
            $class = $helper_obj->table_row_class($i);
             
            $output .= "<tr class='$class' id='zodiac_$zodiac->id'>
                          <td>" . $zodiac->id . "</td>
                          <td>" . $zodiac->name . "</td>
                          <td>" . $zodiac->body . "</td>
                          <td><a href='javascript:void(0);' onclick='openEditZodiacPopup($zodiac->id)'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='deleteZodiac($zodiac->id)'>
                                " . $helper_obj->t("Delete") . "</div>
                              </a>
                          </td>
                       </tr>";
        }             

        $output .= "</table>"; 

        return $output;           
    }

    function build_zodiac_row($id) {
      $db_functions_obj = new DbFunctions(); 
      $helper_obj = new Helper();
      
      $zodiac = $db_functions_obj->get_zodiac_by_id($id);
        
      $class = $helper_obj->table_row_class($i); 
      
      $output = "<tr class='$class' id='zodiac_$zodiac->id'>
                      <td>" . $zodiac->id . "</td>
                      <td>" . $zodiac->name . "</td>
                      <td>" . $zodiac->body . "</td>
                      <td><a href='javascript:void(0);' onclick='openEditZodiacPopup($zodiac->id)'>" . $helper_obj->t("Edit") . "</a></td>
                      <td><a href='javascript:void(0);' onclick='deleteZodiac($zodiac->id)'>
                            " . $helper_obj->t("Delete") . "
                          </a>
                      </td>
                   </tr>";
                   
        return $output; 
    }

    function build_edit_zodiac_popup($id) {
          $db_functions_obj = new DbFunctions();
          $helper_obj = new Helper();
          $zodiac_info = $db_functions_obj->get_zodiac_by_id($id);
          global $base_path;
          $operator = new Operator();                    
          
                    
          $output = "<div class='popup-header'>" . $helper_obj->t("Edit Zodiac")."</div>";
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='edit_zodiac' id='edit_zodiac' method='post' action='$base_path" . "edit_zodiac'>";
                      
          //$output .= $this->build_zodiac_list($zodiac_info->name);          
             
          $output .= "<div class='control-group'>
                        <label class='control-label' for='textarea'>body</label>
                        <div class='controls'>
                            <textarea id='textarea' class='input-block-level' data-rule-required='true' rows='5' value='$zodiac_info->body' name='body'>$zodiac_info->body</textarea>
                        </div>
                    </div>";                                   
                     
          $output .= "<input type='hidden' name='zodiac_id' value='" . $zodiac_info->id . "'>";
          $output .= "<input class='btn btn-primary' type='submit' value='" . $helper_obj->t("Save") . "'>";
          $output .= "</form>";
          $output .= "<script>
                    $(document).ready(function() {        
                       $(\"#zodiac_textfield\").datepicker();   
                     
                       $('#edit_zodiac').ajaxForm(function(res) { 
                           var isvalid = $(\"#edit_zodiac\").valid();
                           if (isvalid) { 
                               var data = res.split(\"***#***\");   
                               $('#zodiac_' + data[1]).after(data[0]);
                               $('#zodiac_' + data[1]).remove(); 
                               closePopup();
                           }  
                       }); 
                    });
                    </script>";
          return $output; 
      }
  }
?>
