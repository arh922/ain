<?php
  class HappenedInThisDay {
    private $conn; 
    
    function __construct() {
        $this->conn = new MySQLiDatabaseConnection();
    }   
    function build_add_hitd_form(){
        $helper_obj = new Helper();
        global $base_path;

        $output = '<div class="box box-bordered">';
        $output .= '<div class="box-title">
                      <h3><i class="icon-th-list"></i> Add happened in this day</h3>
                  </div>';
        $output .= "<div class='box-content nopadding'>";
        $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='add_hitd' id='add_hitd' method='post' action='$base_path" . "add_hitd'>";
                  
        $output .= "<div class='control-group'>
                        <label class='control-label' for='textarea'>body</label>
                        <div class='controls'>
                            <textarea id='textarea' class='input-block-level' data-rule-required='true' rows='5' name='textarea'></textarea>
                        </div>
                    </div>";   
                       
        $output .= '<div class="control-group">
                        <label class="control-label" for="textfield">Date</label>
                        <div class="controls">
                            <input id="textfield" class="input-medium datepick" data-rule-required="true" value="' . date("Y-m-d",time()) . '" type="text" name="textfield" data-date-format="yyyy-mm-dd">
                        </div>
                    </div>';
                  
        $output .= "<input class=\"btn btn-primary\" type='submit' value='" . $helper_obj->t("Save") . "'>";
        $output .= "<input style='display: none' type='reset' id='add_hitd_reset'>";
        $output .= "</form>";
        $output .= "</div>";
        $output .= "</div><br /><br />";
                        
        return $output; 
    }

    function get_hitd(){
      $db_functions_obj = new DbFunctions();
      $hitds = $db_functions_obj->get_all_hitds();
                   
      $helper_obj = new Helper(); 
      
      $output = "<table id='add_hitd_table' class='table table-hover table-nomargin table-bordered'>
                  <tr>
                    <th>" . $helper_obj->t("ID") . "</th>
                    <th>" . $helper_obj->t("date") . "</th>
                    <th>" . $helper_obj->t("body") . "</th>  
                    <th>" . $helper_obj->t("Edit") . "</th>
                    <th>" . $helper_obj->t("Delete") . "</th>
                  </tr>";

     foreach($hitds as $key => $hitd) {
        $class = $helper_obj->table_row_class($i);
         
        $output .= "<tr class='$class' id='happened_$hitd->id'>
                      <td>" . $hitd->id . "</td>
                      <td>" . $hitd->date . "</td>
                      <td>" . $hitd->body . "</td>
                      <td><a href='javascript:void(0);' onclick='openEditHitdPopup($hitd->id)'>" . $helper_obj->t("Edit") . "</a></td>
                      <td><a href='javascript:void(0);' onclick='deleteHitd($hitd->id)'>
                            " . $helper_obj->t("Delete") . "</div>
                          </a>
                      </td>
                   </tr>";
     }             

     $output .= "</table>"; 
     
     return $output;           
    }

    function build_hitd_row($id) {
      $db_functions_obj = new DbFunctions(); 
      $helper_obj = new Helper();
      
      $hitd = $db_functions_obj->get_hitd_by_id($id);
        
      $class = $helper_obj->table_row_class($i); 
      
      $output = "<tr class='$class' id='happened_$hitd->id'>
                      <td>" . $hitd->id . "</td>
                      <td>" . $hitd->date . "</td>
                      <td>" . $hitd->body . "</td>
                      <td><a href='javascript:void(0);' onclick='openEditHitdPopup($hitd->id)'>" . $helper_obj->t("Edit") . "</a></td>
                      <td><a href='javascript:void(0);' onclick='deleteHitd($hitd->id)'>
                            " . $helper_obj->t("Delete") . "
                          </a>
                      </td>
                   </tr>";
                   
        return $output; 
    }

    function build_edit_hitd_popup($id) {
          $db_functions_obj = new DbFunctions();
          $helper_obj = new Helper();
          $hitd_info = $db_functions_obj->get_hitd_by_id($id);
          global $base_path;
                              
          
                    
          $output = "<div class='popup-header'>" . $helper_obj->t("Edit Happened in this day")."</div>";
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='edit_hitd' id='edit_hitd' method='post' action='$base_path" . "edit_hitd'>";
                      
           $output .= "<div class='control-group'>
                            <label class='control-label' for='textarea'>body</label>
                            <div class='controls'>
                                <textarea id='textarea' class='input-block-level' rows='5' name='hitd_textarea'>$hitd_info->body</textarea>
                            </div>
                        </div>";   
                       
            $output .= '<div class="control-group">
                            <label class="control-label" for="textfield">Date</label>
                            <div class="controls">
                                <input placeholder="'.$hitd_info->date.'" value="'.$hitd_info->date.'" class="input-medium " type="text" name="hitd_textfield" id="hitd_textfield" data-date-format="yyyy-mm-dd">
                            </div>
                        </div>'; 
                     
          $output .= "<input type='hidden' name='hitd_id' value='" . $hitd_info->id . "'>";
          $output .= "<input class='btn btn-primary' type='submit' value='" . $helper_obj->t("Save") . "'>";
          $output .= "</form>";
             $output .= "<script>
           $(document).ready(function() {        
                       $(\"#hitd_textfield\").datepicker();   
                     
                       $('#edit_hitd').ajaxForm(function(res) { 
                           var isvalid = $(\"#edit_hitd\").valid();
                           if (isvalid) { 
                               var data = res.split(\"***#***\");   
                               $('#happened_' + data[1]).after(data[0]);
                               $('#happened_' + data[1]).remove(); 
                               closePopup();
                           }  
                       }); 
                    });
                    </script>";
          return $output; 
      }
  }
?>
