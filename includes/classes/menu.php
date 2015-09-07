<?php
  class Menu {
      private $conn; 
      
      function __construct() {
          $this->conn = new MySQLiDatabaseConnection();
      }
      
      function build_super_admin_menu(){
          $helper_obj = new Helper(); 
          global $base_path, $menu_active;  
                        
          $menu = '<ul class="main-nav">
                    <li class="' . @$menu_active['dashboard'] . '">
                        <a href="' . $base_path . '">
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="' . @$menu_active['add_content'] . '">   
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <span>' . $helper_obj->t("Add Content") . '</span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="' . $base_path . 'clients">Clients</a>
                            </li>
                            
                            <!--<li>
                                <a href="' . $base_path . 'currencies">Currencies</a>
                            </li>-->
                            
                            <li>
                                <a href="' . $base_path . 'users">Users</a>
                            </li>
                            
                            <li>
                                <a href="' . $base_path . 'operators">Operators</a>
                            </li>
                            
                             <li class="dropdown-submenu">
                                <a class="dropdown-toggle" href="javascript:void(0);">Menus</a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-toggle" href="' . $base_path . 'menu/2">Add item to menu</a> 
                                    </li>
                                    <li>
                                        <a class="dropdown-toggle" href="' . $base_path . 'menu/1">Add item menu to client</a>
                                    </li>    
                                </ul>
                            </li> 
                            
                            <li>
                                <a href="' . $base_path . 'pgrate">PG rate</a>
                            </li> 
                            
                             <li class="dropdown-submenu">
                                <a class="dropdown-toggle" href="javascript:void(0);">Categories</a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-toggle" href="' . $base_path . 'categories">Add Category</a> 
                                    </li>
                                    <li>
                                        <a class="dropdown-toggle" href="' . $base_path . 'add_categories_to_client">Add Categories to client</a>
                                    </li> 
                                    <li>
                                        <a class="dropdown-toggle" href="' . $base_path . 'sources">Add Source</a>
                                    </li>
                                </ul>
                            </li> 
                                  
                            <li>
                                <a href="' . $base_path . 'hitd">Happened in this day</a>
                            </li>
                             
                            
                            <li>
                                <a href="' . $base_path . 'emergency">Emergency Calls</a>
                            </li>
                             
                            
                            <li>
                                <a href="' . $base_path . 'zodiac">Zodiac</a>
                            </li>
                            
                            <li>
                                <a href="' . $base_path . 'rss_source">RSS Source</a>
                            </li> 
                            
                            <li>
                                <a href="' . $base_path . 'country_category_rss">Add RSS Source To Category</a>
                            </li>
                            
                            <li>
                                <a href="' . $base_path . 'tags">Tags</a>
                            </li>
                            
                            <li>
                                <a href="' . $base_path . 'related_tags">Related Tags</a>
                            </li>
                            
                            <li>
                                <a href="' . $base_path . 'tags_and_synonyms">Tags And Synonyms</a>
                            </li>
 
                        </ul>
                    </li> 
                    
                     <li class="' . @$menu_active['push_notification'] . '">   
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <span>Push Notification</span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="' . $base_path . 'send_pn">Send Push Notification</a>
                            </li>
                        </ul>
                    </li>        
                </ul>';
          
         /* $menu = '<li>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <span>Components</span>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="components-timeline.html">Timeline</a>
                        </li>
                        <li>
                            <a href="components-pagestatistics.html">Page statistics</a>
                        </li>
                        <li>
                            <a href="components-sidebarwidgets.html">Sidebar widgets</a>
                        </li>
                        <li>
                            <a href="components-messages.html">Messages &amp; Chat</a>
                        </li>
                        <li>
                            <a href="components-gallery.html">Gallery &amp; Thumbs</a>
                        </li>
                        <li>
                            <a href="components-tiles.html">Tiles</a>
                        </li>
                        <li>
                            <a href="components-icons.html">Icons &amp; Buttons</a>
                        </li>
                        <li>
                            <a href="components-elements.html">UI elements</a>
                        </li>
                        <li>
                            <a href="components-typography.html">Typography</a>
                        </li>
                        <li>
                            <a href="components-bootstrap.html">Bootstrap elements</a>
                        </li>
                        <li>
                            <a href="components-grid.html">Grid</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <span>Tables</span>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="tables-basic.html">Basic tables</a>
                        </li>
                        <li>
                            <a href="tables-dynamic.html">Dynamic tables</a>
                        </li>
                        <li>
                            <a href="tables-large.html">Large tables</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <span>Plugins</span>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="plugins-charts.html">Charts</a>
                        </li>
                        <li>
                            <a href="plugins-calendar.html">Calendar</a>
                        </li>
                        <li>
                            <a href="plugins-filemanager.html">File manager</a>
                        </li>
                        <li>
                            <a href="plugins-filetrees.html">File trees</a>
                        </li>
                        <li>
                            <a href="plugins-elements.html">Editable elements</a>
                        </li>
                        <li>
                            <a href="plugins-maps.html">Maps</a>
                        </li>
                        <li>
                            <a href="plugins-dragdrop.html">Drag &amp; Drop widgets</a>
                        </li>
                        
                    </ul>
                </li>
                <li>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <span>Pages</span>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="more-error.html">Error pages</a>
                        </li>
                        <li class="dropdown-submenu">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Shop</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="more-shop-list.html">List view</a>
                                </li>
                                <li>
                                    <a href="more-shop-product.html">Product view</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="more-pricing.html">Pricing tables</a>
                        </li>
                        <li>
                            <a href="more-faq.html">FAQ</a>
                        </li>
                        <li>
                            <a href="more-invoice.html">Invoice</a>
                        </li>
                        <li>
                            <a href="more-userprofile.html">User profile</a>
                        </li>
                        <li>
                            <a href="more-searchresults.html">Search results</a>
                        </li>
                        <li>
                            <a href="more-login.html">Login</a>
                        </li>
                        <li>
                            <a href="more-locked.html">Lock screen</a>
                        </li>
                        <li>
                            <a href="more-email.html">Email templates</a>
                        </li>
                        <li>
                            <a href="more-blank.html">Blank page</a>
                        </li>
                        <li class="dropdown-submenu">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Blog</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="more-blog-list.html">List big image</a>
                                </li>
                                <li>
                                    <a href="more-blog-list-small.html">List small image</a>
                                </li>
                                <li>
                                    <a href="more-blog-post.html">Post</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <span>Layouts</span>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="layouts-sidebar-hidden.html">Default hidden sidebar</a>
                        </li>
                        <li>
                            <a href="layouts-sidebar-right.html">Sidebar right side</a>
                        </li>
                        <li>
                            <a href="layouts-color.html">Different default color</a>
                        </li>
                        <li>
                            <a href="layouts-fixed.html">Fixed layout</a>
                        </li>
                        <li>
                            <a href="layouts-fixed-topside.html">Fixed topbar and sidebar</a>
                        </li>
                        <li class="dropdown-submenu">
                            <a href="#">Mobile sidebar</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="layouts-mobile-slide.html">Slide</a>
                                </li>
                                <li>
                                    <a href="layouts-mobile-button.html">Button</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="layouts-footer.html">Footer</a>
                        </li>
                    </ul>
                </li>';  */
                
          return $menu;
      }
      
      function build_client_menu($cid){
          $db_functions_obj = new DbFunctions();
          $helper_obj = new Helper();
          global $base_path;
                      
          $menu_perants = $db_functions_obj->get_menu_parents_by_client($cid);  
          
          $menu = "";
                  ///  pr($menu_perants);exit;
          if (is_array($menu_perants)) {   
              $menu .= '<ul class="main-nav">
                            <li class="' . @$menu_active['dashboard'] . '">
                                <a href="' . $base_path . '">
                                    <span>Dashboard</span>
                                </a>
                            </li>';  
                                   
              foreach ($menu_perants as $key => $value) { 
                      
                  $menu .= '<li class="' . @$menu_active['add_content'] . '">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <span>' . $helper_obj->t($value['title']) . '</span>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">';
                                   
                  $menu_childs = $db_functions_obj->get_menu_childs_by_parent($value['id'], $value['cid']);  
                                
                  if (is_array($menu_childs)) {     
                      foreach($menu_childs as $ckey => $cvalue) { //pr($cvalue);   
                          $menu .= '<li>
                                       <a href="' . $base_path . $cvalue['url'] . '">' . $cvalue['title'] . '</a>
                                    </li>';
                      }
                  }
                  
                  $menu .= '</ul></li>'; 
              }
              
             $menu .= '</ul>';
          }
                  
          return $menu;
      }
      
      function build_menu_by_client($cid = 1){
           global $base_path;
           $helper_obj = new Helper();
                     
           if ($cid == 1) { //super admin 
              $menu = $this->build_super_admin_menu(); 
           }
           else{                 
              $menu = $this->build_client_menu($cid);  
           }
                
           return $menu;
      }
        
      function build_menu_parents($page = 'add', $selected_client = "", $onclick = "", $style = "") {
          $db_functions_obj = new DbFunctions();
          $menu_perants = $db_functions_obj->get_menu_parents();
          $helper_obj = new Helper();
          
          $output = "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Menu Perent</label>
                        <div class='controls'>";
                        
          $output .= "<select $style $onclick id='$page" . "_menu' name='$page" . "_menu'>";

          $output .= "<option value='0'>" . $helper_obj->t("Parent") . "</option>"; 
            
          foreach($menu_perants as $key => $value){
              if ($selected_client != ""){
                  $output .= "<option ";
                  if ($selected_client == $value['id']) {
                      $output .= " selected='selected' ";
                  }
                  $output .= "value='$value[id]'>$value[title]</option>";
              }
              else{
                  $output .= "<option value='$value[id]'>$value[title]</option>";  
              }
          }
          
          $output .= "</select>";
          $output .= "</div>";
          $output .= "</div>";
              
          return $output;    
      }
      
      function build_add_item_to_menu_form(){
          $helper_obj = new Helper();
          $client_obj = new Client();
          global $base_path;
          
          $output = '<div class="box box-bordered">';
          $output .= '<div class="box-title">
                                <h3><i class="icon-th-list"></i> Add Menu</h3>
                            </div>';
                            
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' id='add_item_to_menu' name='add_item_to_menu' action='$base_path" . "add_item_to_menu' method='post'>";
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Title</label>
                        <div class='controls'> 
                          <input class='input-xlarge' data-rule-required='true' type='text' name='menu_item_title' id='menu_item_title' placeholder='" . $helper_obj->t("Title") . "'>
                        </div>
                      </div>";
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>URL</label>
                        <div class='controls'>
                           <input class='input-xlarge' data-rule-required='true' type='text' name='menu_item_url' id='menu_item_url' placeholder='" . $helper_obj->t("URL") . "'>
                        </div>
                      </div>";
          $output .= $this->build_menu_parents("add", "", "", "style='width:152px'") . '<br />';  
          $output .= "<input class='btn btn-primary' type='submit' value='" . $helper_obj->t("Save") . "'>";  
          $output .= "<input type='reset' value='reset' id='add_item_to_menu_reset' style='display: none;'>";     
          $output .= "</form>";
          
          return $output;
      }
      
      function add_item_to_menu($data) {
          $db_functions_obj = new DbFunctions();
          global $user;
          $added_by = $user->id;
          
          $db_functions_obj->add_item_to_menu($data, $added_by);
          
          echo($this->build_all_menu_with_childs(1));
      }
      
      function menu_display($menu_perants, $not_assigned_to_client = false, $cid = 0, $onclick = ""){
          $output = "";
          $k = 0;
          $db_functions_obj = new DbFunctions();
          
          if (is_array($menu_perants)) {        
              foreach ($menu_perants as $key => $value) {   
                  if ($not_assigned_to_client){        //pr($value);    
                      //$menu_childs = $db_functions_obj->get_menu_childs_not_assigned_to_client($value->id, $cid);
                      $menu_childs = $db_functions_obj->get_menu_childs_by_parent($value['id'], $value['cid']);
                  }
                  else {                
                       $menu_childs = $db_functions_obj->get_menu_childs_by_parent($value['id']);
                  }
                           
                      // pr($menu_childs);   
                  $style = "";
                            
                  if ( ($k % 6) == 0) {
                        $style .= "style='clear:both'";
                  }
                    
                  $output .= "<div $style class='menu-checkboxs'>";
                  $output .= "<input $onclick type='checkbox' value='$value[id]' /><div style='font-weight:bold;margin-left: 20px;margin-top: -19px;position: absolute;'>" . $value['title'] . '</div><br />';
                  $i = 0;
                    
                  if (is_array($menu_childs)) {     
                     foreach($menu_childs as $ckey => $cvalue) { //pr($menu_childs);   
                          $output .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input $onclick type='checkbox' value='" . $menu_childs[$i]['id'] . "' /><div style='margin-left: 43px;margin-top: -19px;position: absolute;'>" . $menu_childs[$i]['title'] . '</div><br />';
                          $i++;
                     }
                  } 
                    
                  $output .= "</div>"; 
                    
                  $k++;              
              }
          }
          
          return $output;
      }
      
      function build_all_menu_with_childs($ajax = 0){
          $db_functions_obj = new DbFunctions(); 
          $menu_perants = $db_functions_obj->get_menu_parents();
          
          $output = $this->menu_display($menu_perants);
          
          if ($ajax) {
              echo($output);
              exit;
          }
          else{
            return $output;
          } 
      }
      
      function get_menu_by_client($cid){
          $db_function_obj = new DbFunctions();
          $helper_obj = new Helper();   
          
          $menu_perants = $db_function_obj->get_menu_parents_by_client($cid);
                   
          $output = $helper_obj->t("Menu items assigned to this client") . '<br />'; 
          $output .= $this->menu_display($menu_perants, true, "", "onclick='removeMenuItemFromClient(this)'");
          
          return $output;           
      } 
      
      function get_menu_not_assigned_to_client($cid) {
          $db_function_obj = new DbFunctions();
          $helper_obj = new Helper();
          
        //  $menu_perants = $db_function_obj->get_menu_parents_not_assignt_to_client($cid);
          $menu_parants = $db_function_obj->get_menu_parents($cid);
                                // pr($menu_parants);
          $output = $helper_obj->t("Menu items not assigned to this client") . '<br />';
          $output .= $this->menu_display($menu_parants, false, '', "onclick='addMenuItemToClient(this)'");
          
          return $output;
      }
  }