<?php
class Helper {
    private $conn; 
    
    function __construct() {
        $this->conn = new MySQLiDatabaseConnection();
    }
    
    function t($s) {
        global $trans;
        global $language;   
                   
        return isset($trans[$s][$language]) ? $trans[$s][$language] : $s;
    }
    
    function print_message() { 
        if (!empty($_SESSION['message'])) {      
            echo( '<div class="message ' . $_SESSION['message_style'] . '">' . $_SESSION['message'] . '</div>' );  
            
            unset($_SESSION['message_style'], $_SESSION['message']);  
        }
    }
    
    function set_message($message = "", $style = "error") {
        $_SESSION['message'] = $message;
        $_SESSION['message_style'] = $style;
    }
    
    /**
    * @Author: Ayman Hussein    
    * @Date created: 30/07/2013  
    * @Description: set item title and desc to keyword and description metadata
    * @Param:
    *   $k: keyword, $d: description, $t: title
    * @Updated History:
    *    none
    *
    */
    function seo($k = "", $d = "", $t = "", $img = "") {
        global $keywords, $desc, $head_title, $img_g;
        
        $keywords = $k;
        $desc = $d;
        $head_title = $t;
        $img_g = $img;
                   
        if ($head_title == "") {
            $head_title = $this->t("Share thoughts - share images, videos, news via internet");
        } 
        if ($keywords == "") {
            $keywords = $this->t('images, funny pictures, image host, image upload, image sharing, image resize.');
        }
        if($desc == ""){
            $desc = $this->t('Jeel+ is used to share photos with social networks and online communities, and has the funniest pictures from all over the Internet.');
        }
        if($img_g == ""){     
            $img_g = 'http://www.jeelplus.com/images/logo_L.png';
        }
        
    }
               
    /**
    * @Author: Ayman Hussein    
    * @Date created: 17/04/2013  
    * @Description: to check if the user loged in with authenticat role - we can send multi rols as string seperated by comma like: "1,2,3" 
    * @Param:
    *   $role:role id 
    * @Updated History:
    *    none
    *
    */
    function check_role($roles) {
        
        global $user;
        $access = FALSE;

        $roles = explode(",", $roles);
      
        if (in_array($user['rid'], $roles)){
            $access = TRUE;
        }
       
        return $access;
    }
    
    /**
    * @Author: Ayman Hussein    
    * @Date created: 30/07/2013  
    * @Description: check sesion exists 
    * @Param:
    *   none
    * @Updated History:
    *    none
    *
    */
    function user_is_logged_in() {
        
        global $user;
        $access = FALSE;   
                         
        if (isset($_SESSION['user']) && isset($user) && @$user['id'] != 0 && $user['status'] == 1) {    
            $access = TRUE;
        }
        
        return $access;
    }

    /**
    * @Author: Taymoor Qanadilou    
    * @Date created: 22/04/2013  
    * @Description: knowing file size in bytes   
    * @Param:
    *   
    * @Updated History:
    *
    */
    function format_size_units($bytes){
        
        if ($bytes >= 1073741824){
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576){
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024){
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1){
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1){
            $bytes = $bytes . ' byte';
        }
        else{
            $bytes = '0 bytes';
        }

        return $bytes;
    }
  
    /**
    * @Author: Taymoor Qanadilou
    * @Date created: 15/01/2013
    * @Description: Generate the image name by random    
    * @Param:
    *   none
    * @Updated History:
    *    none
    *
    */
    function generate_name ($length = LENGTH_IMG_PATH) {
        $image_name = "";
        $possible = "0123456789bcdfghjkmnpqrstvwxyz";

        $i = 0;

        while ($i < $length) {

            $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);

            if (!strstr($image_name, $char)) {
                $image_name .= $char;
                $i++;               
            }              
        }            
        return $image_name;
    }     
    
    /**
    * @Author: Taymoor Qanadilou    
    * @Date created: 18/02/2013  
    * @Description: bad words     
    * @Param:
    *   $comment:comment 
    * @Updated History:
    *    none
    *
    */
    function clear_bad_words($comment){

        $query = $this->conn->db_query("SELECT word FROM bad_words");
           
        while($row = $this->conn->db_fetch_array($query)){
            $bad = str_replace(array('*',')','('),'',trim($row['word']));
            $bad = '/\b' . $bad . '\b/u';  
            $comment = preg_replace($bad , "****", $comment);  
        }
         
        return $comment;
    }

    /**
    * @Author: Ayman Hussien
    * @Date created: 1-4-2013 ?
    * @Description: this function will return the time elapsed or difference between current time and the passed parameter time.
    * @Param:
    *   $ptime: the time to calculate difference against.
    * @Updated History:
    *    none
    */
    function time_elapsed_string($ptime) {
        
        global $language;
        if(!is_numeric($ptime)){
            $ptime = strtotime($ptime);
        }
        
        $etime = time() - $ptime;                  
        
        if ($etime < 1) {     
            return $this->t('0 seconds');
        }
        $s = null;
        $a = array( 12 * 30 * 24 * 60 * 60  =>  $this->t('سنة'),
                    30 * 24 * 60 * 60       =>  $this->t('شهر'),
                    24 * 60 * 60            =>  $this->t('يوم'),
                    60 * 60                 =>  $this->t('ساعة'),
                    60                      =>  $this->t('دقيقة'),
                    1                       =>  $this->t('ثانية')
                    );
        /*if($language == 'ar'){
            $s = null;
        }
        else {
            $s = 's';
        }*/
        foreach ($a as $secs => $str) {
            $d = $etime / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . $str . ($r > 1 ? $s : '') . " " ;
            }
        }
    }
 

    /**
    * Aspires code, to 
    * 
    * @param mixed $string
    * @param mixed $limit
    * @param mixed $break
    * @param mixed $pad
    */
    function textLimitation($string, $limit = 32 , $break=" ", $pad="") {
    /*     if (strlen($txt) <= $limit)
            return $txt;
        else 
           return substr($txt, 0, ($limit - 4)) . '...'; */

            if(mb_strlen($string, 'UTF-8') < $limit) return $string;

      // is $break present between $limit and the end of the string?
      if(FALSE !== ($breakpoint = mb_strpos($string, $break, $limit))) {
        if($breakpoint < mb_strlen($string, 'UTF-8') - 1) {
          $string = mb_substr($string, 0, $breakpoint,'UTF-8') . $pad;
        }
      }
      return $string;  
    }    


    /**
    * put your comment there...
    * 
    * @param mixed $string
    * @param mixed $length
    */
    function my_text_limitation($string, $length = 20) {
        $return_string = $this->textLimitation($string, $length);
        
        
        if(mb_strlen($return_string, 'UTF-8') > $length) {
            
            $return_string = mb_substr($return_string, 0, $length,'UTF-8');
            $return_string .= "...";
        }
        return $return_string;
    }   
    
    /**
     * Encode special characters in a plain-text string for display as HTML.
     *
     * Also validates strings as UTF-8 to prevent cross site scripting attacks on
     * Internet Explorer 6.
     *
     * @param $text
     *   The text to be checked or processed.
     * @return
     *   An HTML safe version of $text, or an empty string if $text is not
     *   valid UTF-8.
     *
     * @see drupal_validate_utf8().
     */
    function check_plain($text) {
      static $php525;

      if (!isset($php525)) {
        $php525 = version_compare(PHP_VERSION, '5.2.5', '>=');
      }
      // We duplicate the preg_match() to validate strings as UTF-8 from
      // drupal_validate_utf8() here. This avoids the overhead of an additional
      // function call, since check_plain() may be called hundreds of times during
      // a request. For PHP 5.2.5+, this check for valid UTF-8 should be handled
      // internally by PHP in htmlspecialchars().
      // @see http://www.php.net/releases/5_2_5.php

      if ($php525) {
        return htmlspecialchars($text, ENT_NOQUOTES);
      }
      return (preg_match('/^./us', $text) == 1) ? htmlspecialchars($text, ENT_NOQUOTES) : '';
    }

    /**
    * @Author: Ayman Hussein
    * @Date created: 08/04/2013
    * @Description: replace text into smiley face 
    * @Param:
    *    $text: text
    * @Updated History:
    *   none
    */
    function smiley($text){  
        global $emotDict;
        $updated_text = str_replace(array_keys($emotDict), array_values($emotDict), $text);
                     
        return $updated_text;
    }


    /**
    * @Author: Ayman Hussein
    * @Date created: 15/04/2013
    * @Description: resize and crob an image
    * @Param:
    *    $source_image, 
    *    $destination_filename, 
    *    $width = 200, 
    *    $height = 150, 
    *    $quality = 70, 
    *    $crop = true
    * @Updated History:
    *   none
    */  
    function resize_crob_image($source_image, $destination_filename, $width = 200, $height = 150, $quality = 70, $crop = true){

        if( ! $image_data = getimagesize( $source_image ) ){
                return false;
        }
        
        switch( $image_data['mime'] ){
                case 'image/gif':
                        $get_func = 'imagecreatefromgif';
                        $suffix = ".gif";
                break;
                case 'image/jpeg';
                        $get_func = 'imagecreatefromjpeg';
                        $suffix = ".jpg";
                break;
                case 'image/png':
                        $get_func = 'imagecreatefrompng';
                        $suffix = ".png";
                break;
        }
            
        $img_original = call_user_func( $get_func, $source_image );
        $old_width = $image_data[0];
        $old_height = $image_data[1];
        $new_width = $width;
        $new_height = $height;
        $src_x = 0;
        $src_y = 0;
        $current_ratio = round( $old_width / $old_height, 2 );
        $desired_ratio_after = round( $width / $height, 2 );
        $desired_ratio_before = round( $height / $width, 2 );

        //ignor this case - by ayman   21/08/2013
        if( $old_width < $width || $old_height < $height ){    
                /**
                 * The desired image size is bigger than the original image. 
                 * Best not to do anything at all really.
                 */
               // return false;
        }
             

        /**
         * If the crop option is left on, it will take an image and best fit it
         * so it will always come out the exact specified size.
         */
        if( $crop ){
                /**
                 * create empty image of the specified size
                 */
                $new_image = imagecreatetruecolor( $width, $height );

                /**
                 * Landscape Image
                 */
                if( $current_ratio > $desired_ratio_after ){
                        $new_width = $old_width * $height / $old_height;
                }

                /**
                 * Nearly square ratio image.
                 */
                if( $current_ratio > $desired_ratio_before && $current_ratio < $desired_ratio_after ){
                       echo('($old_height * $width / $old_width): ' . ($old_height * $width / $old_width));
                         echo('<br />$height: '  . $height);
                        if( $old_width > $old_height ){
                                $new_height = max( $width, $height );
                                $new_width = $old_width * $new_height / $old_height;
                        }   
                        else{            echo('22222222');  
                                $new_height = $old_height * $width / $old_width;
                        }
                }
                          echo('$new_height1: ' . $new_height);
                /**
                 * Portrait sized image
                 */
                if( $current_ratio < $desired_ratio_before  ){   echo('3333333333333333');
                        $new_height = $old_height * $width / $old_width;  
                }
                
                
                 if ( ($old_height * $width / $old_width) < $height) {      echo('1111111111'); 
                     $new_height = $height;
                 }
                    echo('$new_height2: ' . $new_height);
                /**
                 * Find out the ratio of the original photo to it's new, thumbnail-based size
                 * for both the width and the height. It's used to find out where to crop.
                 */
                $width_ratio = $old_width / $new_width;
                $height_ratio = $old_height / $new_height;

                /**
                 * Calculate where to crop based on the center of the image
                 */
                $src_x = floor( ( ( $new_width - $width ) / 2 ) * $width_ratio );
                $src_y = round( ( ( $new_height - $height ) / 2 ) * $height_ratio );
        }
        /**
         * Don't crop the image, just resize it proportionally
         */
        else{
                if( $old_width > $old_height ){
                        $ratio = max( $old_width, $old_height ) / max( $width, $height );
                }else{
                        $ratio = max( $old_width, $old_height ) / min( $width, $height );
                }

                $new_width = $old_width / $ratio;
                $new_height = $old_height / $ratio;

                $new_image = imagecreatetruecolor( $new_width, $new_height );
        }
            
        /**
         * Where all the real magic happens
         */
        imagecopyresampled( $new_image, $img_original, 0, 0, $src_x, $src_y, $new_width, $new_height, $old_width, $old_height );

        /**
         * Save it as a JPG File with our $destination_filename param.
         */
        imagejpeg( $new_image, $destination_filename, $quality  );

        /**
         * Destroy the evidence!
         */
        imagedestroy( $new_image );
        imagedestroy( $img_original );
              
        /**
         * Return true because it worked and we're happy. Let the dancing commence!
         */
        return true;
    }   
    
    function table_row_class(&$i = 0){
        $class = 'odd';
           ;
        if ($i % 2) {
            $class = 'even';
        }
        
        $i++;
        
        return $class;
    }
    
    function image_exists($img, $type = 'not') {
        $url = "";     
        global $base_path;  
                  
        if (file_exists($img) && is_file($img)) {
           $url = $base_path . $img;
        }
        else{
            $url = $base_path . MAIN_IMAGES_PATH . "default.png";
            if($type == 'youtube'){
                $url = $base_path . MAIN_IMAGES_PATH . "article-h.png";    
            }
        }
        
        return $url;
    }
    
    function calc_image_size($file, $file_name){
        $valid = true;
              
        if ($file[$file_name]["size"] > MAX_IMAGE_SIZE) {
            $valid = false;
        }
        
        return $valid;
    }
    
    function captcha($captcha){   
        $valid = false;   
        if( $_SESSION['security_code'] == trim($captcha) && !empty($_SESSION['security_code']) ) {
            $valid = true;         
        } 
        
        return $valid;
    }
    
    function imagecreatefromjpeg_if_correct($file_tempname) {
        $file_dimensions = getimagesize($file_tempname);
        $file_type = strtolower($file_dimensions['mime']);
        $im = 0;
              
        if ($file_type == 'image/jpeg' || $file_type == 'image/pjpeg'){
            $im = imagecreatefromjpeg($file_tempname);
        }
        elseif ($file_type == 'image/png'){
            $im = imagecreatefrompng($file_tempname);
        }

        return $im;
    }
    
    /**
     * Create a thumbnail image from $inputFileName no taller or wider than 
     * $maxSize. Returns the new image resource or false on error.
     * Author: mthorn.net
     */
    function img_resize($inputFileName, $maxSize = 100) {         
        $info = getimagesize($inputFileName);
                        
        $type = isset($info['type']) ? $info['type'] : $info[2];

        // Check support of file type
        if ( !(imagetypes() & $type) )
        {                  
            // Server does not support file type
            return false;
        }
                  
        $width  = isset($info['width'])  ? $info['width']  : $info[0];
        $height = isset($info['height']) ? $info['height'] : $info[1];

        // Calculate aspect ratio
        $wRatio = $maxSize / $width;
        $hRatio = $maxSize / $height;
              
        // Using imagecreatefromstring will automatically detect the file type
        $sourceImage = imagecreatefromstring(file_get_contents($inputFileName));
             
        // Calculate a proportional width and height no larger than the max size.
        if ( ($width <= $maxSize) /*&& ($height <= $maxSize)*/ )
        {        
            // Input is smaller than thumbnail, do nothing
            return $sourceImage;
        }
        elseif ( ($wRatio * $height) < $maxSize )
        {          
            // Image is horizontal
            $tHeight = ceil($wRatio * $height);
            $tWidth  = $maxSize;
        }
        else
        {
            // Image is vertical
            $tWidth  = ceil($hRatio * $width);
            $tHeight = $maxSize;
        }
            
        $thumb = imagecreatetruecolor($tWidth, $tHeight);
          
        if ( $sourceImage === false )
        {
            // Could not load image
            return false;
        }
             
        // Copy resampled makes a smooth thumbnail
        imagecopyresampled($thumb, $sourceImage, 0, 0, 0, 0, $tWidth, $tHeight, $width, $height);
        imagedestroy($sourceImage);
             
        return $thumb;
    }

    /**
     * Save the image to a file. Type is determined from the extension.
     * $quality is only used for jpegs.
     * Author: mthorn.net
     */
    function imageToFile($im, $fileName, $quality = 80){          
        //pr(file_exists($fileName));  
        /*if ( !$im || file_exists($fileName) )
        {
           return false;
        }*/
              
        $ext = strtolower(substr($fileName, strrpos($fileName, '.')));

        switch ( $ext )
        {
            case '.gif':
                imagegif($im, $fileName);
                break;
            case '.jpg':
            case '.jpeg':
                imagejpeg($im, $fileName, $quality);
                break;
            case '.png':
                imagepng($im, $fileName);
                break;
            case '.bmp':
                imagewbmp($im, $fileName);
                break;
            default:
                return false;
        }

        return true;
    }
    
    function build_mail_header($from = ""){
        //$cr = '\r\n';
        $cr = "\n";
        
        if ($from == "") {
            $from = "admin@jeelplus.com";
        }
        
        $header = "";
        $header .= "MIME-Version: 1.0$cr";
        $header .= "From: $from" . $cr;
        $header .= "Reply-To: noreply@jeelplus.com$cr";         
        $header .= "Content-Type:text/html; charset=utf8-1$cr";
        
        
        return $header;
    }
    
    function get_pure_name($name){
        $name_arr = explode(SOCIAL_SEPARATOR_FOR_NAME, $name);
        
        if (isset($name_arr[1])) {
             $pure_name = $name_arr[1];
        }
        else{
            $pure_name = $name_arr[0]; 
        }
        
        return $pure_name;
    }
    
    function check_youtube($url){

        $valid = false;
                
        if ( strpos($url, "youtube.com") !== FALSE) {
            $valid = true;
        }
        
        return $valid;
    }
    
    function add_watermark($img){     
        global $base_path;
        // Load the stamp and the photo to apply the watermark to
        $stamp = imagecreatefrompng(MAIN_IMAGES_PATH . 'watermark.png');
       // $im = imagecreatefromjpeg($img);
        
        $im = $this->imagecreatefromjpeg_if_correct($img);
                      //  echo($img);        exit;  
        // Set the margins for the stamp and get the height/width of the stamp image
        $marge_right = 10;
        $marge_bottom = 10;
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);

        // Copy the stamp image onto our photo using the margin offsets and the photo 
        // width to calculate positioning of the stamp. 
        $v = imagecopy($im, $stamp, $marge_right, imagesy($im)/(1.5), 0, 0, imagesx($stamp), imagesy($stamp));  
                        
        // Output and free memory
       // header('Content-type: image/png');
        imagepng($im, $img);
    }
 
    function dataAlert($title, $body){
        return "<div class=\"abuse-header\" >" . $title . '</div><div class="alert-body">' .  $body . '</div>';
    }
    
    function check_if_url_is_img($url) {
        $url_info = @getimagesize(urldecode($url));
        
        $valid = false;
                       
        if (strpos($url_info['mime'], 'image') !== FALSE){   
            $valid = true;
        }  
        
        return $valid;
    }
    
    function build_table($header, $content){
         $output = "<table>
                      <tr>";
         
         foreach ($header as $row) {
             $output .= "<th>" . $row . "</th>";
         }
                         
         $output .= "</tr>";
         
         foreach($content as $key => $value) {
            $output .= "<tr>
                          <td>" . $client->id . "</td>
                          <td>" . $client->name . "</td>
                          <td>" . $client->logo . "</td>
                          <td>" . $client->status . "</td>
                          <td>" . $client->date_added . "</td>
                          <td>" . $client->added_by . "</td>
                          <td>" . $client->date_updated . "</td>
                          <td>" . $client->updated_by . "</td>
                       </tr>";
         }             
        
         $output .= "</table>"; 
    }
    
    
    function check_user_menu($menu_id, $cid){
        
        global $user;
        $access = FALSE;
        
        $db_function_obj = new DbFunctions();
      
        
        if ($db_function_obj->vaild_user_menu ($menu_id, $cid)){
            $access = TRUE;
        }
                //echo('<br /><br />$access:' . $access.'<br /><br />');
        return $access;     
    }
    
    function exec_curl($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
        curl_setopt($ch, CURLOPT_VERBOSE, TRUE); // Display communication with server
        $status = curl_exec($ch);
        //pr($status);
        curl_close($ch);

        return $status;
    }
    
    function send_sms($from, $number, $msg, $smsc, $dlr_url, $port = ""){
        if (SQLBOX) {
            $host = '89.234.33.27';
            $user = 'kannel55';
            $password = 'O.brah.*#&';
            $db_name = 'gwapp_prod';
            $port = '3306';  
            
            $connection = mysqli_connect($host, $user, $password) or die(mysqli_error($connection));
            mysqli_set_charset($connection, "utf8");           
            mysqli_select_db($connection, $db_name) or die(mysqli_error($connection));
            
            
            $msg = rawurlencode($msg);       
            $number = trim($number);
            
            $insert = "INSERT INTO send_sms_sqlbox (
                          momt, sender, receiver, msgdata, sms_type, smsc_id, charset, coding, dlr_mask, dlr_url
                        ) VALUES (
                          'MT', '$from', '$number', '$msg' , '2', '$smsc', 'UTF-8', 2, 31, '$dlr_url'
                        )"; 
            
            $result = mysqli_query($connection, $insert) or die(mysqli_error($connection)); 
        }
        else{
            $url = 'http://89.234.33.27:' . $port .'/cgi-bin/sendsms?username=kannel&password=kannel&from=' . $from . 
                   '&to=' . $number . '&text=' . $msg . '&charset=utf-8&coding=2&smsc=' . $smsc . 
                   '&binfo=&dlr-url=' . $dlr_url . '&dlr-mask=31';
                      //   echo $url.'<br /><br />';
            $status = $this->exec_curl($url);
        }
    }
}