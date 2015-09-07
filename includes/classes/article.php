<?php      
//require_once 'google/appengine/api/cloud_storage/CloudStorageTools.php';
//use google\appengine\api\cloud_storage\CloudStorageTools;

class Article {
    private $conn; 
    
    function __construct() {
        //$this->conn = new MySQLiDatabaseConnection();
    }
    
    function build_add_article_html_form(){
        global $base_path;
        $helper_obj = new Helper();
        $category_obj = new Category();
        $pgrate_obj = new Pgrate(); 
        $tag_obj = new tag(); 
        
        //$BUCKET = 'cosmic-descent-775.appspot.com/article/';      
        //$options = [ 'gs_bucket_name' => $BUCKET ];
       // $upload_url = CloudStorageTools::createUploadUrl('/add_article_html', $options);
        
        $output = '<script src="js/plugins/ckeditor/ckeditor.js"></script>';
        $output .= '<div class="box box-bordered">';
        $output .= '<div class="box-title">
                       <h3><i class="icon-th-list"></i> Add Article</h3>
                    </div>';
                            
        $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' method='post' id='add_article_html' name='add_article_html' action='add_article_html'>";
   
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Title</label>
                        <div class='controls'> 
                          <input style='width:500px' class='input-xlarge' data-rule-required='true' type='text' name='title' id='title' placeholder='" . $helper_obj->t('Title') . "'>  
                        </div>
                     </div>"; 
                     
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image1</label>
                        <div class='controls'>
                           <input class='input-xlarge' data-rule-required='true' accept='image/*' type='file' name='image1' id='image1'>                       
                        </div>  
                    </div>";
                    
        /*$output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Video Url</label>
                        <div class='controls'> 
                          <input style='width:600px' class='input-xlarge' data-rule-required='true' type='text' name='video' id='video' placeholder='" . $helper_obj->t('Video Url') . "'>  
                        </div>
                     </div>";  */
        
        $output .= $category_obj->build_categories_list("add", "", "", "style='width:152px'");
        
        $output .= $pgrate_obj->build_pgrate_list("add", "", "", "style='width:152px'");
        
        $output .= $tag_obj->build_tags_list("add", "", "", "style='height:152px'");
                        
        $output .= '<div class="control-group">
                        <label class="control-label">Main Category in home page</label>
                        <div class="controls">
                            <label class="radio">
                                <input checked="checked" type="radio" name="section" value="1"> Top Section
                            </label>
                            <label class="radio">
                                <input type="radio" name="section" value="2"> Middle Section 
                            </label>
                             <!--<label class="radio">
                                <input type="radio" name="section" value="3"> Bottom Section 
                            </label>-->
                        </div>
                    </div>';  
        
        $output .= '<div class="row-fluid">
                        <div class="span12">
                            <div class="box">
                                <div class="box-title">
                                    <h3><i class="icon-th"></i> Article Body</h3>
                                </div>
                                <div class="box-content nopadding">
                                    <textarea data-rule-required=\'true\' name="ck" id="ck" class=\'ckeditor span12\' rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                   ';      
        
        $output .= "<input onclick='setEditorValue();' class='btn btn-primary' type='submit' value='" . $helper_obj->t('Save') . "'>";  
        $output .= "<input type='reset' style='display: none' id='add_article_html_reset'></div>";  
                                 
        $output .= "</form><br /><br />";   
                     
        return $output;
    }
    
    function build_add_article_form() {   //pr($_SESSION);
        global $base_path;
        $helper_obj = new Helper();
        $category_obj = new Category();
        $pgrate_obj = new Pgrate();
    
        $output = '<div class="box box-bordered">';
        $output .= '<div class="box-title">
                                <h3><i class="icon-th-list"></i> Add Article</h3>
                            </div>';
                            
        $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' method='post' id='add_article' name='add_article' action='" . $base_path ."add_article'>";
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Title</label>
                        <div class='controls'> 
                          <input class='input-xlarge' data-rule-required='true' type='text' name='title' id='title' placeholder='" . $helper_obj->t('Title') . "'>
                        </div>
                     </div>"; 
                     
     /*   $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Text1</label>
                        <div class='controls' style='float: left; margin-left: 0; margin-right: 20px;width: 231px'>
                           <textarea placeholder='Text1' data-rule-required='true' name='text1' id='text1'></textarea><br />
                           <input data-rule-number='true' type='text' name='text1_order'  style='font-size: 11px;width: 55px' placeholder='text1 order'></input>     
                        </div>    
                    </div>";    */
       
        $output .= $category_obj->build_categories_list("add", "", "", "style='width:152px'");
        
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Text1</label>
                        <div class='controls' style='float: left; margin-left: 0; margin-right: 20px;width: 231px'>
                           <textarea placeholder='Text1' data-rule-required='true' name='text1' id='text1'></textarea><br />
                           <input data-rule-number='true' type='text' name='text1_order'  style='font-size: 11px;width: 55px' placeholder='text1 order'></input>     
                        </div>    
                    </div>";
                    
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Texts</label>
                        <div class='controls' style='float: left; margin-right: -70; margin-left: 0;width: 231px'>
                           <textarea placeholder='Text2' name='text2' id='text2'></textarea> <br />
                           <input data-rule-number='true' type='text' name='text2_order'  style='font-size: 11px;width: 55px' placeholder='text2 order'></input>
                        </div>   
                        
                        <div class='controls' style='float: left; margin-right: -70; margin-left: 0;width: 231px'>
                            <textarea placeholder='Text3' name='text3' id='text3'></textarea> <br />
                            <input data-rule-number='true' type='text' name='text3_order'  style='font-size: 11px;width: 65px' placeholder='text3 order'></input> 
                        </div>
                        
                    </div>";
                    
     /*   $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Text3</label>
                        <div class='controls'>
                            <textarea placeholder='Text3' name='text3' id='text3'></textarea> <br />
                            <input data-rule-number='true' type='text' name='text3_order'  style='font-size: 11px;width: 65px' placeholder='text3 order'></input> 
                        </div>   
                    </div>";  */
                    
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image1</label>
                        <div class='controls' style='float: left; margin-left: 0;margin-right: 20px;width: 230px'>
                           <input class='input-xlarge' data-rule-required='true' accept='image/*' type='file' name='image1' id='image1'> <br />
                           <input data-rule-number='true' type='text' name='image1_order' style='font-size: 11px;width: 65px' placeholder='image1 order'></input>
                        </div>  
                    </div>";
                    
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Images</label>
                        <div class='controls' style='float: left; margin-right: -70; margin-left: 0;'>
                           <input accept='image/*' type='file' name='image2' id='image2'>   <br />
                           <input data-rule-number='true' type='text' name='image2_order' style='font-size: 11px;width: 65px' placeholder='image2 order'></input>
                        </div>
                        
                        <div class='controls' style='float: left; margin-right: -70; margin-left: 0;'>
                           <input accept='image/*' type='file' name='image3' id='image3'>  <br />
                           <input data-rule-number='true' type='text' name='image3_order' style='font-size: 11px;width: 65px' placeholder='image3 order'></input>
                        </div>
                        
                        <div class='controls' style='float: left; margin-right: -70; margin-left: 0;'>
                           <input accept='image/*' type='file' name='image4' id='image4'>  <br />
                           <input data-rule-number='true' type='text' name='image4_order' style='font-size: 11px;width: 65px' placeholder='image4 order'></input>
                        </div>
                        
                        <div class='controls' style='float: left; margin-right: -70; margin-left: 0;'>
                           <input accept='image/*' type='file' name='image5' id='image5'>  <br />
                           <input data-rule-number='true' type='text' name='image5_order' style='font-size: 11px;width: 65px' placeholder='image5 order'></input>
                        </div>
                        
                    </div>";    
                                        
       /* $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image3</label>
                        <div class='controls'>
                           <input accept='image/*' type='file' name='image3' id='image3'>  <br />
                           <input data-rule-number='true' type='text' name='image3_order' style='font-size: 11px;width: 65px' placeholder='image3 order'></input>
                        </div>
                    </div>";    
                    
        $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image4</label>
                        <div class='controls'>
                           <input accept='image/*' type='file' name='image4' id='image4'>  <br />
                           <input data-rule-number='true' type='text' name='image4_order' style='font-size: 11px;width: 65px' placeholder='image4 order'></input>
                        </div>
                    </div>";    
                    
       $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image5</label>
                        <div class='controls'>
                           <input accept='image/*' type='file' name='image5' id='image5'>  <br />
                           <input data-rule-number='true' type='text' name='image5_order' style='font-size: 11px;width: 65px' placeholder='image5 order'></input>
                        </div>
                    </div>";  */
                    
        $output .= "<div class='control-group' > 
                        <label class='control-label' for='client_name'>videos</label>
                        <div class='controls' style='float: left; margin-right: -70; margin-left: 0;'>
                           <input class='input-xlarge' type='text' name='video1' id='video1'>   <br />
                           <input data-rule-number='true' type='text' name='video1_order' style='font-size: 11px;width: 65px' placeholder='video1 order'></input>
                        </div>
                        
                         <div class='controls' style='float: left; margin-right: -70; margin-left: 0;'>
                           <input class='input-xlarge' type='text' name='video2' id='video2'><br />
                           <input data-rule-number='true' type='text' name='video2_order' style='font-size: 11px;width: 65px' placeholder='video2 order'></input>
                        </div>
                    </div>"; 
                    
        /*$output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>video2</label>
                        <div class='controls'>
                           <input class='input-xlarge' type='text' name='video2' id='video2'><br />
                           <input data-rule-number='true' type='text' name='video2_order' style='font-size: 11px;width: 65px' placeholder='video2 order'></input>
                        </div>
                    </div>";    */
                    
        
        $output .= $pgrate_obj->build_pgrate_list("add", "", "", "style='width:152px'");  
          
        $output .= '<div class="control-group">
                        <label class="control-label">Main Category in home page</label>
                        <div class="controls">
                            <label class="radio">
                                <input type="radio" name="section" value="1"> Top Section
                            </label>
                            <label class="radio">
                                <input type="radio" name="section" value="2"> Middle Section 
                            </label>
                             <!--<label class="radio">
                                <input type="radio" name="section" value="3"> Bottom Section 
                            </label>-->
                        </div>
                    </div>';  
          
        $output .= "<input class='btn btn-primary' type='submit' value='" . $helper_obj->t('Save') . "'>";  
        $output .= "<input type='reset' style='display: none' id='add_article_reset'>";  
        $output .= "</form><br /><br />";
        
        return $output;
    }
    
    function add_article($data) {
        $db_functions_obj = new DbFunctions();
                              
        $vid = $db_functions_obj->add_article($data);
        
        return $vid;
    } 
    
    function add_article_html($data) {
        $db_functions_obj = new DbFunctions();
                              
        $vid = $db_functions_obj->add_article_html($data);
        
        return $vid;
    }
    
    function build_article_html_row($aid){
        $db_functions_obj = new DbFunctions();
        $helper_obj = new Helper();
        
        $data = $db_functions_obj->get_article_html_by_id($aid);
                       //   pr($data);
        $output = "<tr id='article_$data[aid]'>
                          <td>" . $data['aid'] . "</td>
                          <td>" . $data['title'] . "</td>      
                          <td>" . $data['image'] . "</td>   
                          <td>" . $data['username'] . "</td>   
                          <td>" .  date(DATE_FORMAT, $data['date_added']) . "</td>   
                          <td><a href='javascript:void(0);' onclick='openEditHtmlArticlePopup($data[aid])'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='deleteHtmlArticle($data[aid])'>" . $helper_obj->t("Delete") . "</a></td>
                       </tr>";
                       
        return $output;
    }
    
    function build_article_row($aid) {
        $db_functions_obj = new DbFunctions();
        $helper_obj = new Helper();
        
        $data = $db_functions_obj->get_article_by_id($aid);
         
        $output = "<tr id='article_$data->id'>
                          <td>" . $data->id . "</td>
                          <td>" . $data->title . "</td>      
                          <td>" . $data->text1 . "</td>
                          <td>" . $data->text2 . "</td>
                          <td>" . $data->text3 . "</td>
                          <td>" . $data->image1 . "</td>
                          <td>" . $data->image2 . "</td>
                          <td>" . $data->image3 . "</td>
                          <td>" . $data->image4 . "</td>
                          <td>" . $data->image5 . "</td>
                          <td>" . $data->video1 . "</td>
                          <td>" . $data->video2 . "</td>     
                          <td>" . date(DATE_FORMAT, $data->uploaded_date) . "</td>    
                          <td>" . $data->cat_name . "</td> 
                          <td>" . $data->pgrate_name . "</td>
                          <td>" . $data->views . "</td>    
                          <td>" . $data->added_by . "</td>   
                          <td><a href='javascript:void(0);' onclick='openEditArticlePopup($data->id)'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='deleteArticle($data->id)'>" . $helper_obj->t("Delete") . "</a></td>
                       </tr>";
                       
        return $output;
      //  pr($vid_details);
    }
    
    function get_all_articles_html($aid) { 
        $db_functions_obj = new DbFunctions();
        $helper_obj = new Helper();  
        
        $output = '<script src="js/plugins/ckeditor/ckeditor.js"></script>';
        $output .= '<div class="box box-bordered">';
        $output .= '<div class="box-title">
                       <h3><i class="icon-th-list"></i>Search Article</h3>
                    </div>';
                    
        $output .= '<div class="control-group"> 
                        <div class="controls"> 
                          <input type="text" placeholder="Search in title or body" id="search_word" name="search_word" data-rule-required="true" class="input-xlarge" style="width:500px">  
                        </div>
                        <div class="row-fluid">
                        <input type="submit" value="Save" class="btn btn-primary" onclick="searchNews();">   
                        </div>
                     </div>
                 </div>
                     ';
        
        $articles = $db_functions_obj->get_all_articles_html($aid);
        
        $output .= "<div id='news_table'>";
        $output .= "<table id='add_article_html_table' style='font-size: 10px' class='table table-hover table-nomargin table-bordered'>
                      <tr>
                        <th>" . $helper_obj->t("ID") . "</th>
                        <th>" . $helper_obj->t("Title") . "</th>     
                        <th>" . $helper_obj->t("Image") . "</th>  
                        <th>" . $helper_obj->t("Added By") . "</th>  
                        <th>" . $helper_obj->t("Date Added") . "</th>  
                        <th>" . $helper_obj->t("Edit") . "</th>
                        <th>" . $helper_obj->t("Delete") . "</th>
                      </tr>";

         foreach($articles as $key => $data) {
            $class = $helper_obj->table_row_class($i);
            
            $output .= "<tr id='article_$data[id]'>
                          <td>" . $data['id'] . "</td>
                          <td>" . $data['title'] . "</td>      
                          <td>" . $data['image'] . "</td>      
                          <td>" . $data['added_by'] . "</td>      
                          <td>" . date(DATE_FORMAT, $data['date_added']) . "</td>      
                          <td><a href='javascript:void(0);' onclick='openEditHtmlArticlePopup($data[id])'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='deleteHtmlArticle($data[id])'>" . $helper_obj->t("Delete") . "</a></td>
                       </tr>";
         }
        
         $output .= "</table>";
         $output .= "</div>";
        
         return $output;             
        
    }
    
    function get_all_articles($aid) {
        $db_functions_obj = new DbFunctions();
        $helper_obj = new Helper();
        
        $articles = $db_functions_obj->get_all_articles($aid);
                     
        $output = "<table id='add_article_table' style='font-size: 10px' class='table table-hover table-nomargin table-bordered'>
                      <tr>
                        <th>" . $helper_obj->t("ID") . "</th>
                        <th>" . $helper_obj->t("Title") . "</th>     
                        <th>" . $helper_obj->t("Text1") . "</th>
                        <th>" . $helper_obj->t("Text2") . "</th>
                        <th>" . $helper_obj->t("Text3") . "</th>
                        <th>" . $helper_obj->t("Image1") . "</th>
                        <th>" . $helper_obj->t("Image2") . "</th>
                        <th>" . $helper_obj->t("Image3") . "</th>
                        <th>" . $helper_obj->t("Image4") . "</th>
                        <th>" . $helper_obj->t("Image5") . "</th>
                        <th>" . $helper_obj->t("Video1") . "</th>
                        <th>" . $helper_obj->t("Video2") . "</th>    
                        <th>" . $helper_obj->t("Uploaded date") . "</th> 
                        <th>" . $helper_obj->t("Category") . "</th>
                        <th>" . $helper_obj->t("PG rate") . "</th> 
                        <th>" . $helper_obj->t("Views") . "</th>    
                        <th>" . $helper_obj->t("Added By") . "</th>  
                        <th>" . $helper_obj->t("Edit") . "</th>
                        <th>" . $helper_obj->t("Delete") . "</th>
                      </tr>";

         foreach($articles as $key => $data) {
            $class = $helper_obj->table_row_class($i);
            
            $output .= "<tr id='article_$data->id'>
                          <td>" . $data->id . "</td>
                          <td>" . $data->title . "</td>      
                          <td>" . $data->text1 . "</td>
                          <td>" . $data->text2 . "</td>
                          <td>" . $data->text3 . "</td>
                          <td>" . $data->image1 . "</td>
                          <td>" . $data->image2 . "</td>
                          <td>" . $data->image3 . "</td>
                          <td>" . $data->image4 . "</td>
                          <td>" . $data->image5 . "</td>
                          <td>" . $data->video1 . "</td>
                          <td>" . $data->video2 . "</td>   
                          <td>" . date(DATE_FORMAT, $data->uploaded_date) . "</td>   
                          <td>" . $data->cat_name . "</td> 
                          <td>" . $data->pgrate_name . "</td>
                          <td>" . $data->views . "</td>      
                          <td>" . $data->added_by . "</td>   
                          <td><a href='javascript:void(0);' onclick='openEditArticlePopup($data->id)'>" . $helper_obj->t("Edit") . "</a></td>
                          <td><a href='javascript:void(0);' onclick='deleteArticle($data->id)'>" . $helper_obj->t("Delete") . "</a></td>
                       </tr>";
         }
        
        return $output;
    }
    
    function build_edit_html_article_popup($aid){
        $db_functions_obj = new DbFunctions();
        $validation_js_obj = new Validation_js();   
        $cat_obj = new Category();
        $helper_obj = new Helper();
        $pgrate_obj = new Pgrate();
        $tag_obj = new Tag();
                      
        $article_tags = $db_functions_obj->get_article_tags($aid);
        $article_categories = $db_functions_obj->get_article_categories($aid);
        
        global $base_path;
        
        $article_info = $db_functions_obj->get_article_html_by_id($aid);
        $output = "";
               
        //$output .= '<script src="js/plugins/ckeditor/ckeditor.js"></script>';           
        //$output = $validation_js_obj->edit_article_validation(); 
        $output .= "<script>$(document).ready(function() {  var isvalid = $(\"#edit_html_article\").valid();
        $('#edit_html_article').ajaxForm(function(res) { 
                        
                        if (isvalid) { 
                            var data = res.split(\"***#***\");   
                            $('#article_' + data[1]).after(data[0]);
                            $('#article_' + data[1]).remove(); 
                            closePopup();
                        }  
                    }); }); 
                    </script>";
                    
              
          $output .= "<div class='popup-header'>" . $helper_obj->t("Edit Article") . " " . $article_info['title'] . "</div>";
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='edit_html_article' id='edit_html_article' method='post' action='$base_path" . "edit_html_article'>";
         
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Title</label>
                        <div class='controls'>
                           <input style='width:500px' type='text' id='title_updated' name='title_updated' value='" . $article_info['title'] . "'>
                        </div>
                      </div>"; 
                      
          /*$output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Video Url</label>
                        <div class='controls'> 
                          <input style='width:600px' class='input-xlarge' data-rule-required='true' type='text' name='video_updated' id='video_updated' value='" . $article_info->video . "'>  
                        </div>
                     </div>";   */
                      
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image1</label>
                        <div class='controls'>
                            <input accept='image/*' type='file' name='image1_updated' id='image1_updated'><img src='" . VIDEOS_IMAGES_THUMBNAIL_PATH . $article_info['image'] . "' width=" . IMG_WIDTH_EDIT_VID . " height=" . IMG_HIEGHT_EDIT_VID . ">
                        </div>
                      </div>";
             
         $output .= $cat_obj->build_categories_list('edit', $article_categories);
         
         $output .= $pgrate_obj->build_pgrate_list("edit",  $article_info['pg_rated_id']);
         
         $output .= $tag_obj->build_tags_list("edit", $article_tags, "", "style='height:152px'");
         
         $radio1 = "";
         $radio2 = "";
         $radio3 = "";
          
         if ($article_info['section'] == 1) $radio1 = "checked='checked'";
         if ($article_info['section'] == 2) $radio2 = "checked='checked'";
                        
         $output .= '<div class="control-group">
                            <label class="control-label">Main Category in home page</label>
                            <div class="controls">
                                <label class="radio">
                                    <input ' . $radio1 . ' type="radio" name="section_updated" value="1"> Top Section
                                </label>
                                <label class="radio">
                                    <input ' . $radio2 . ' type="radio" name="section_updated" value="2"> Middle Section 
                                </label> 
                            </div>
                        </div>';
                        
         $output .= '<div class="row-fluid">
                        <div class="span12">
                            <div class="box">
                                <div class="box-title">
                                    <h3><i class="icon-th"></i> Article Body</h3>
                                </div>
                                <div class="box-content nopadding">
                                    <textarea data-rule-required=\'true\' name="ck_updated" id="ck_updated" class=\'ckeditor span12\' rows="5">' . $article_info['body'] .'</textarea>
                                </div>
                            </div>
                        </div>
                   ';   
                        
          $output .= "<input onclick='setHtmlEditorValue();' class='btn btn-primary' type='submit' value='" . $helper_obj->t("Save") . "'>"; 
          $output .= "<input type='hidden' name='edit_article' id='edit_article' value='" . $aid . "'>";
          $output .= "</form>";
               
          return $output;
    }
    
    function build_edit_article_popup($aid) {
        $db_functions_obj = new DbFunctions();
        $validation_js_obj = new Validation_js();   
        $cat_obj = new Category();
        $helper_obj = new Helper();
        $pgrate_obj = new Pgrate();
        
        global $base_path;
        
        $article_info = $db_functions_obj->get_article_by_id($aid);
        $output = "";
                   
        $output = $validation_js_obj->edit_article_validation(); 
        $output .= "<script>$(document).ready(function() {  $('#edit_article').ajaxForm(function(res) { 
                        var isvalid = $(\"#edit_article\").valid();
                        if (isvalid) { 
                            var data = res.split(\"***#***\");   
                            $('#article_' + data[1]).after(data[0]);
                            $('#article_' + data[1]).remove(); 
                            closePopup();
                        }  
                    }); });</script>";
                    
             
          $output .= "<div class='popup-header'>" . $helper_obj->t("Edit Article") . " " . $article_info->title . "</div>";
          $output .= "<form class='form-horizontal form-validate form-vertical form-bordered' name='edit_article' id='edit_article' method='post' action='$base_path" . "edit_article'>";
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Title</label>
                        <div class='controls'>
                           <input type='text' id='title_updated' name='title_updated' value='" . $article_info->title . "'>
                        </div>
                      </div>";
             
         $output .= $cat_obj->build_categories_list('edit', $article_info->cat_id);
         
         $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Text1</label>
                        <div class='controls' style='float: left; margin-right: -70; margin-left: 0;width: 231px'>
                           <textarea name='text1_updated' id='text1_updated'>$article_info->text1</textarea><br />
                           <input type='text' style='font-size: 11px;width: 65px' name='text1_order_updated' value='$article_info->text1_order'></input>
                        </div>
                        
                        <div class='controls' style='float: left; margin-right: -70; margin-left: 0;width: 231px'>
                            <textarea name='text2_updated' id='text2_updated'>$article_info->text2</textarea> <br />
                            <input type='text' style='font-size: 11px;width: 65px' name='text2_order_updated' value='$article_info->text2_order'></input> 
                        </div>
                        
                        <div class='controls' style='float: left; margin-right: -70; margin-left: 0;width: 233px'>
                            <textarea name='text3_updated' id='text3_updated'>$article_info->text3</textarea> <br />
                            <input type='text' style='font-size: 11px;width: 65px' name='text3_order_updated' value='$article_info->text3_order'></input>    
                        </div>
                        
                      </div>";
                      
        /* $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Text2</label>
                        <div class='controls' style='float: left; margin-right: -70; margin-left: 0;width: 231px'>
                            <textarea name='text2_updated' id='text2_updated'>$article_info->text2</textarea> <br />
                            <input type='text' style='font-size: 11px;width: 65px' name='text2_order_updated' value='$article_info->text2_order'></input> 
                        </div>
                        
                        <div class='controls' style='float: left; margin-right: -70; margin-left: 0;width: 49%'>
                            <textarea name='text3_updated' id='text3_updated'>$article_info->text3</textarea> <br />
                            <input type='text' style='font-size: 11px;width: 65px' name='text3_order_updated' value='$article_info->text3_order'></input>    
                        </div>
                        
                      </div>";  */
                      
      /*   $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Text3</label>
                        <div class='controls'>
                            <textarea name='text3_updated' id='text3_updated'>$article_info->text3</textarea> <br />
                            <input type='text' style='font-size: 11px;width: 65px' name='text3_order_updated' value='$article_info->text3_order'></input>    
                        </div>
                      </div>";  */
                      
         $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image1</label>
                        <div class='controls'>
                            <input accept='image/*' type='file' name='image1_updated' id='image1_updated'><img src='" . VIDEOS_IMAGES_THUMBNAIL_PATH . $article_info->image1 . "' width=" . IMG_WIDTH_EDIT_VID . " height=" . IMG_HIEGHT_EDIT_VID . ">
                            <input type='text' style='font-size: 11px;width: 65px' name='image1_order_updated' value='$article_info->image1_order'></input> 
                        </div>
                      </div>";
                      
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image2</label>
                        <div class='controls'>
                            <input accept='image/*' type='file' name='image2_updated' id='image2_updated'><img src='" . VIDEOS_IMAGES_THUMBNAIL_PATH . $article_info->image2 . "' width=" . IMG_WIDTH_EDIT_VID . " height=" . IMG_HIEGHT_EDIT_VID . ">
                            <input type='text' style='font-size: 11px;width: 65px' name='image2_order_updated' value='$article_info->image2_order'></input>
                        </div>
                      </div>";
                      
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image3</label>
                        <div class='controls'>
                            <input accept='image/*' type='file' name='image3_updated' id='image3_updated'><img src='" . VIDEOS_IMAGES_THUMBNAIL_PATH . $article_info->image3 . "' width=" . IMG_WIDTH_EDIT_VID . " height=" . IMG_HIEGHT_EDIT_VID . ">
                            <input type='text' style='font-size: 11px;width: 65px' name='image3_order_updated' value='$article_info->image3_order'></input>
                        </div>
                      </div>";
                      
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image4</label>
                        <div class='controls'>
                             <input accept='image/*' type='file' name='image4_updated' id='image4_updated'><img src='" . VIDEOS_IMAGES_THUMBNAIL_PATH . $article_info->image4 . "' width=" . IMG_WIDTH_EDIT_VID . " height=" . IMG_HIEGHT_EDIT_VID . ">
                             <input type='text' style='font-size: 11px;width: 65px' name='image4_order_updated' value='$article_info->image4_order'></input>
                        </div>
                      </div>";
                      
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Image5</label>
                        <div class='controls'>
                            <input accept='image/*' type='file' name='image5_updated' id='image5_updated'><img src='" . VIDEOS_IMAGES_THUMBNAIL_PATH . $article_info->image5 . "' width=" . IMG_WIDTH_EDIT_VID . " height=" . IMG_HIEGHT_EDIT_VID . ">
                            <input type='text' style='font-size: 11px;width: 65px' name='image5_order_updated' value='$article_info->image5_order'></input>
                        </div>
                      </div>";
                      
          $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Video1</label>
                        <div class='controls' style='float: left; margin-right: -70; margin-left: 0;'>
                           <input type='text' name='video1_updated' value='$article_info->video1'></input><br />
                           <input type='text' style='font-size: 11px;width: 65px' name='video1_order_updated' value='$article_info->video1_order'></input> 
                        </div>
                        
                        <div class='controls' style='float: left; margin-right: -70; margin-left: 0; width: 49%'>
                           <input type='text' name='video2_updated' value='$article_info->video2'></input><br />
                           <input type='text'style='font-size: 11px;width: 65px'  name='video2_order_updated' value='$article_info->video2_order'></input>
                        </div>
                        
                      </div>";
                      
          /*$output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Video2</label>
                        <div class='controls'>
                           <input type='text' name='video2_updated' value='$article_info->video2'></input><br />
                           <input type='text'style='font-size: 11px;width: 65px'  name='video2_order_updated' value='$article_info->video2_order'></input>
                        </div>
                      </div>"; */ 
                                  
          $output .= $pgrate_obj->build_pgrate_list("edit",  $article_info->pgrate_id);
          
      /*    $output .= "<div class='control-group'> 
                        <label class='control-label' for='client_name'>Ordering</label>
                        <div class='controls'>               

                        </div>
                      </div>";    */
      
      $radio1 = "";
      $radio2 = "";
      $radio3 = "";
      
      if ($article_info->section == 1) $radio1 = "checked='checked'";
      if ($article_info->section == 2) $radio2 = "checked='checked'";
      if ($article_info->section == 3) $radio3 = "checked='checked'";
                      
      $output .= '<div class="control-group">
                        <label class="control-label">Main Category in home page</label>
                        <div class="controls">
                            <label class="radio">
                                <input ' . $radio1 . ' type="radio" name="section" value="1"> Top Section
                            </label>
                            <label class="radio">
                                <input ' . $radio2 . ' type="radio" name="section" value="2"> Middle Section 
                            </label>
                            <label class="radio">
                                <input ' . $radio3 . ' type="radio" name="section" value="3"> Bottom Section 
                            </label>
                        </div>
                    </div>'; 
         
          $output .= "<input class='btn btn-primary' type='submit' value='" . $helper_obj->t("Save") . "'>"; 
          $output .= "<input type='hidden' name='edit_article' id='edit_article' value='" . $article_info->id . "'>";
          $output .= "</form>";
        
        return $output;
    }
}