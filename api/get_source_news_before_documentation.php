<?php  
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();
require_once("twitteroauth/twitteroauth.php"); //Path to twitteroauth library

define('CONSUMER_KEY', 'jOOPT3ukvqJME560agM0Sshp7');
define('CONSUMER_SECRET', '6tAv1ncUMzFNZDfdMtQDf8uD6TGDMcCWxuAL4OskeDT2pLOUr7');
define('ACCESS_TOKEN', '521548868-6v8a35WaOdtTPtgngIOCu6XcWgyafSjFKKMpJxD2');
define('ACCESS_TOKEN_SECRET', 'I9CUxl2Z2wqTXrwfJ4ifJkDtx9ROfCBJA0Q7EUC6edmTi');
 
header('Content-Type: text/html; charset=utf-8');
     
include("../includes/conf.php");
include("../includes/classes/MySQLiDatabaseConnection.php"); 

/*function reverse_tinyurl($url) {
    $headers = get_headers($url, 1); //echo('<pre>');print_r($headers); echo('</pre>');
    
    if (isset($headers['Location'])) {
        if (is_array($headers['Location'])) {
            return $headers['Location'][0];
        }
        else{
            return $headers['Location'];
        }
    }
    else{
        return $url;
    }
}     */

//extract og meta data
function get_og_meta($url) {
                    
    $url = str_replace(" ", "%20", $url);
    
    if (strrpos($url,"dw.de") !== FALSE || strrpos($url,"dw.com") !== FALSE) { 
        $url = explode("/", $url);  
        $before_last_part = $url[count($url)-2];
        $before_last_part = urlencode($before_last_part);
            
        $dw_url = '';
        
        for($i = 0; $i < count($url)-2; $i++) {
            $dw_url .= $url[$i] . "/";
        }
        
        $dw_url .= $before_last_part . '/' . $url[count($url)-1]; 
        
        $url = $dw_url;
          
    } 
        
         //      echo('meta url: ' . $url);exit;
    if(strrpos($url,"alarabiya.net") !== FALSE) {
        $opts = array(
              'http'=>array(
                'method'=>"GET",
                'header'=>"Accept-language: en\r\n" .
                          "Cookie: YPF8827340282Jdskjhfiw_928937459182JAX666=52.16.79.31\r\n"
              )
            );

            $context = stream_context_create($opts);
                                                           
            // Open the file using the HTTP headers set above
            $sites_html = file_get_contents($url, false, $context); 
    }      
    else{   
        $sites_html = file_get_contents($url);
    }   
         
    $html = new DOMDocument();
    @$html->loadHTML($sites_html);
    $meta_og_img = null;
    
    $c = 0;
    //Get all meta tags and loop through them.    
    foreach($html->getElementsByTagName('meta') as $meta) {
        //If the property attribute of the meta tag is og:image
        //pr($meta);
        if (strpos($meta->getAttribute('property'), "og:") !== FALSE) {       //  echo($meta->getAttribute('content') . '<br />');
        //if($meta->getAttribute('property') == 'og:image'){ 
            //Assign the value from content attribute to $meta_og_img
            //echo( htmlspecialchars($meta->getAttribute('content')) . '<br />');  
            $meta_og_img[$meta->getAttribute('property')] = $meta->getAttribute('content');
            $meta_og_img[$meta->getAttribute('property') . '_' . $c++] = $meta->getAttribute('content');
        }
        else if (strpos($meta->getAttribute('property'), "twitter:") !== FALSE) {
            $meta_og_img[$meta->getAttribute('property')] = $meta->getAttribute('content'); 
            $meta_og_img[$meta->getAttribute('property'). '_' . $c++] = $meta->getAttribute('content'); 
        }
    }
    return $meta_og_img;
}
      
function reverse_tinyurl_xxxxxxxx($url){
    // Resolves a TinyURL.com encoded url to it's source.
    $url = explode('.com/', $url);
    $url = 'http://preview.tinyurl.com/'.$url[1];
    $preview = file_get_contents($url);
    preg_match('/redirecturl" href="(.*)">/', $preview, $matches);
    return $matches[1];
}

function reverse_tinyurl_xxxxxxxxxxxxxxxxxxxxxxxxxx($url){
    $origin_url = $url; 
 
    $url = urlencode($url);
          
    $url = file_get_contents("http://api.longurl.org/v2/expand?url=" . $url);
        
    $pos1 = strpos($url, '<![CDATA[');
    $pos2 = strpos($url, ']]');
    
    $startIndex = min($pos1, $pos2);
    $length = abs($pos1 - $pos2);

    $between = substr($url, ($startIndex+9), ($length-9));
          
    if (strpos($between, "NOT_SHORTURL") !== FALSE || 
        strpos($between, "Missing required parameter") !== FALSE ||
        $url == ""
       ) { 
        return $origin_url;
    }
    else{              
        return $between;
    }
}

//get reversed url from tiny url 
function reverse_tinyurl($url) {      
    $org_url = $url;
    
    if (strrpos($url,"dw.de") !== FALSE) { 
        return $url;      
    } 
    if (strrpos($url,"dw.com") !== FALSE) { 
        return $url;      
    }   
    else if (strrpos($url,"aljazeera.net") !== FALSE) {
        return $url;
    }
    else if (strrpos($url,"kharjhome.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"al-balad.net") !== FALSE) {
        return $url;
    }else if (strrpos($url,"tabuk-news.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"anbaanews.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"aljouf-news") !== FALSE) {
        return $url;
    }else if (strrpos($url,"almowaten.net") !== FALSE) {
        return $url;
    }else if (strrpos($url,"almjardh.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"rasdnews.net") !== FALSE) {
        return $url;
    }else if (strrpos($url,"an7a.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"twasul.info") !== FALSE) {
        return $url;
    }else if (strrpos($url,"ajel.sa") !== FALSE) {
        return $url;
    }else if (strrpos($url,"almaghribtoday.net") !== FALSE) {
        return $url;
    }else if (strrpos($url,"alforatnews.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"basnews.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"facebook.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"youtube.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"twitter.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"maannews.net") !== FALSE) {
        return $url;
    }else if (strrpos($url,"elbilad.net") !== FALSE) {
        return $url;
    }else if (strrpos($url,"kuna.net.kw") !== FALSE) {
        return $url;
    }else if (strrpos($url,"alkoutnews.net") !== FALSE) {
        return $url;
    }else if (strrpos($url,"alhakea.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"alrayalaam.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"Alkuwaityah.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"alkuwaityah.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"arabic.cnn.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"mbc.net") !== FALSE) {
        return $url;
    }else if (strrpos($url,"alriyadh.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"ahram.org.eg") !== FALSE) {
        return $url;
    }else if (strrpos($url,"alayam.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"buyemen.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"mubasher.info") !== FALSE) {
        return $url;
    }else if (strrpos($url,"sea7htravel.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"q8ping.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"yahoo.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"manalonline.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"goodykitchen.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"yumyume.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"fatafeat.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"shahiya.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"shorouknews.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"youm7.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"elfann.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"nok6a.net") !== FALSE) {
        return $url;
    }else if (strrpos($url,"3alyoum.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"qna.org.qa") !== FALSE) {
        return $url;
    }else if (strrpos($url,"almotamar.net") !== FALSE) {
        return $url;
    }else if (strrpos($url,"saidaonline.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"dostor.org") !== FALSE) {
        return $url;
    }else if (strrpos($url,"ng4a.com") !== FALSE) {
        return $url;
    }else if (strrpos($url,"alkhaleejonline.net") !== FALSE) {
        return $url;
    }
    
    $ch = curl_init("$url");  
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
    $yy = curl_exec($ch);
    curl_close($ch);
    $w = explode("\n",$yy);      
    $TheShortURL = array_values( preg_grep( '/' . str_replace( '*', '.*', 'Location' ) . '/', $w ) );
    
    $f = false;
    
    if (!count($TheShortURL)) {
        $f = true;
        $TheShortURL = array_values( preg_grep( '/' . str_replace( '*', '.*', 'location' ) . '/', $w ) );
    }
     
    $url = @$TheShortURL[0];  
    
    if ($f) {
        $url = str_replace("location:", "", "$url");
    }
    else {                            
        $url = str_replace("Location:", "", "$url");
    }
    
    $url = trim("$url");
           
    if ( ($url == "" || filter_var($url, FILTER_VALIDATE_URL) === false) ) {
        $url = $org_url;
    }
    
    return $url;
}

include("fetch_url.php");

//global access db object
global $conn; 
$conn = new MySQLiDatabaseConnection(); 

//cid: source id, type: 1 twitter 2: rss
$cat_id = isset($_GET['cid']) ? $_GET['cid'] : 1;
$type = isset($_GET['type']) ? $_GET['type'] : 1;

//twitter app 
$twitteruser = "arh922_";
$notweets = 30;
$consumerkey = CONSUMER_KEY;
$consumersecret = CONSUMER_SECRET;
$accesstoken = ACCESS_TOKEN;
$accesstokensecret = ACCESS_TOKEN_SECRET;

//twitter connection
function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
  return $connection;
}

$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret); 

//check if news exists or not by checking its twitter id 
function check_if_twitter_news_exists($twitter_news_id) {
    global $conn; 
    $query = "select count(id) c from articles_html where twitter_news_id = '$twitter_news_id'";
    echo('<br />'.$query.'<br />');
    $res = $conn->db_query($query);
    $twitte_count = $conn->db_fetch_array($res); 
          //  pr($twitte_count);   
    return $twitte_count['c'];
}

//check if title exists or not if yes return number of repeated.
function check_if_title_exists($title, $twitter = 0) {
    global $conn; 
    
    $title = trim(@$title);
    
    if ($title != "") {
        $title = $conn->db_escape_string($title);
        $query = "select count(id) c from articles_html where title = '$title' and twitter_news_id = '$twitter'";
        
        echo('<br />title exists query: ' . $query . "<br />");
        $res = $conn->db_query($query);
        $twitte_count = $conn->db_fetch_array($res);
    } 
    else{
        $twitte_count['c'] = 0;
    }
              
    return $twitte_count['c'];  
   // return 0;
}

//check if tag exixts or not if yes return its id
function check_if_tag_exists($tag) {
    global $conn; 
    
    $tag = trim(preg_replace('/\s\s+/', '', $tag));
    $tag = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', '', $tag); 
    
   /* if ( strrpos($tag, "_") !== FALSE ){
        $tag_words = explode("_", $tag);
    }
    else{
        $tag_words = explode(" ", $tag);
    }    */
    
    $exp_tags = array('الرياض' => 847, 'عيدالام' => 3139);
    //$query = "select id from tags where levenshtein(name, '$tag') < 4 limit 1";
    //$query = "select id from tags where name = '$tag' limit 1";
    
  /*  if (count($tag_words) > 1) {                
         $tag = str_replace('\\', '', $tag);               
         $query = "select parent from tags where synonyms = ' $tag ' and image is null"; 
         //echo('<br />' . $query . '<br />');
         $res = $conn->db_query($query);
         $c = $conn->fetch_assoc($res);
    }
    else if (in_array($tag, $exp_tags)) {
        $c['parent'] = $exp_tags[$tag];
    }
    else{
        if (mb_strlen($tag, "UTF-8") > 3) {
            //$query = "select parent from tags where ( synonyms like '%$tag%' || '$tag' like concat('%', synonyms, '%') ) and image is null";
            //$query = "select parent from tags where (synonyms like '%$tag%') and image is null";
            $query = "select parent from tags where synonyms = ' $tag ' and image is null";
            //echo('<br />' . $query . '<br />');
            $res = $conn->db_query($query);
            $c = $conn->fetch_assoc($res);
        }
        else{
            $c['parent'] = 0;
        }
    }
        */
    $query = "select parent from tags where synonyms = '$tag' and image is null";
    echo('<br />tag query: ' . $query . '<br />');
    $res = $conn->db_query($query);
    $c = $conn->fetch_assoc($res);
    
    return $c['parent'];
}

function insert_new_tag($tag, $source = 0) {
    global $conn; 
    $query = "insert into tags (name, synonyms, image) value ('$tag', '$tag', '$source')";
    $res = $conn->db_query($query); 
    
    $tid = $conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning 
    
    return $tid;
}

function cron_start_end($start, $end, $source){
    global $conn; 
    
    $date_added = time();
    
    $query = "insert into cron_start_end (start, end, source, date_added) value ('$start', '$end', '$source', '$date_added')";
    
   // echo($query);
    $res = $conn->db_query($query); 
}

function check_if_article_assoc_for_such_tag($aid, $tid) {
    global $conn;  
    $query = "select count(id) c from article_tags where aid = '$aid' and tid = '$tid'";
    $res = $conn->db_query($query);
    $c = $conn->fetch_assoc($res);
    
    return $c['c'];
}

function update_parent($id) {
    global $conn;
    
    $query = "update tags set parent = '$id' where id = '$id'";
    $res = $conn->db_query($query); 
}
           
function process_tags($title, $aid) {   
    global $conn; 
    
    $title_array = explode(" ", strtolower($title));
    
    $words = FILE("useless_words.txt");
    
    $real_tags = array_diff($title_array, $words); 
    
    //$obj = new I18N_Arabic_WordTag();
              //    echo($title . '<br />');               
               //   echo($aid . '<br />');    
               //pr($real_tags);
         //      exit;           
    $tag_count = 0;
    
    foreach($real_tags as $tag) {
        $tag = trim($tag);
        $tag = strtolower($tag);
        
        $tag_count++;
        
        if ($tag_count == 15) break;
        
        //$taggedText = $obj->tagText($tag);
           //echo('<br />verb:<br />'); pr($taggedText);
       /* $word = '';
        $tag_type = '';
           
        if (is_array($taggedText[0])) {
            list($word, $tag_type) = $taggedText[0];
        }   */
              //     echo('$word: ' . $word . '<br />');
                //   echo('$tag_type: ' . $tag_type . '<br /><br />');
        $tag = str_replace("#", "", $tag);
        $tag = str_replace(":", "", $tag);
        $tag = str_replace(",", "", $tag);
        $tag = str_replace(".", "", $tag);
        $tag = str_replace(";", "", $tag);
        $tag = str_replace(">", "", $tag);
        $tag = str_replace("<", "", $tag);
        $tag = str_replace('"', "", $tag);
        $tag = str_replace("'", "", $tag);
        $tag = str_replace("،", "", $tag);
        $tag = str_replace("~", "", $tag);
        $tag = str_replace("`", "", $tag);
        $tag = str_replace("(", "", $tag);
        $tag = str_replace(")", "", $tag);
        $tag = str_replace("»", "", $tag);
        $tag = str_replace("«", "", $tag);
                                                                             
        $tag = preg_replace("/[a-zA-Z0-9]+/", '',$tag);
                               
        $tag_encoding = mb_detect_encoding($tag);
                  
        //if ($tag_encoding != "" && $tag_encoding == 'UTF-8' && !is_numeric($tag) && (mb_strlen($tag, "UTF-8") > 2) /*&& ($tag_type == 1)*/) {   //just arabic and not number and tag length > 2 and it's noun
       if ($tag_encoding != "" && $tag_encoding == 'UTF-8' && !is_numeric($tag) && (mb_strlen($tag, "UTF-8") > 2)) {   //just arabic and not number and tag length > 2 and it's noun
            $tag_id = check_if_tag_exists($tag);
               // pr($tag_id);  exit;
            if ($tag_id) {
                if ($tag_id != 0) {
                    $aid_tid = check_if_article_assoc_for_such_tag($aid, $tag_id);
                    
                    if (!$aid_tid) {
                        $tag_query = "insert into article_tags (aid, tid) value ('$aid', '" . $tag_id ."')";
                        echo('<br />$tag_query1: ' . $tag_query . '<br />');
                        $res = $conn->db_query($tag_query);
                    }
                }
            }
            else{
                if ($tag != "") {
                 //   $new_tag_id = insert_new_tag($tag, 1);
                    
                   // update_parent($new_tag_id);
                }
                /*if ($new_tag_id != 0) {
                    $tag_query = "insert into article_tags (aid, tid) value ('$aid', '$new_tag_id')";
                    echo('<br />$tag_query2: ' . $tag_query . '<br />');
                    $res = $conn->db_query($tag_query);
                } */
            }
        }   
    }
}  
  
function save_news($news, $site_data_array, $reversed_url) {
      echo('******************************');
    pr($news);  //exit;
    global $conn;
    
    $reversed_url = reverse_tinyurl($reversed_url);
    
    if (strpos($reversed_url, "bit.ly") !== FALSE || 
        strpos($reversed_url, "news.google.com") !== FALSE || 
        strpos($reversed_url, "feedproxy.google.com") !== FALSE || 
        strpos($reversed_url, "alanba.com.kw") !== FALSE || 
        strpos($reversed_url, "trib.al") !== FALSE || 
        strpos($reversed_url, "fb.me") !== FALSE || 
        strpos($reversed_url, "goo.gl") !== FALSE || 
        strpos($reversed_url, "t.co") !== FALSE || 
        strpos($reversed_url, "eel.la") !== FALSE || 
        strpos($reversed_url, "ow.ly") !== FALSE || 
        strpos($reversed_url, "shar.es") !== FALSE){
        $reversed_url = reverse_tinyurl($reversed_url);
    }
    
    $reversed_url = str_replace("'", "", $reversed_url);
    $reversed_url = str_replace('"', "", $reversed_url);
    
   // echo($reversed_url);  exit;
    $title = $news['title'];
    $title = preg_replace('|http?://www\.[a-z\.0-9\/]+|i', '', $title);
    $title = preg_replace('|https?://www\.[a-z\.0-9\/]+|i', '', $title);
    $title = preg_replace('|https?://t\.[a-z\.0-9\/]+|i', '', $title);
    $title = preg_replace('|http?://t\.[a-z\.0-9\/]+|i', '', $title);
    
    $title = str_replace("I added a video to a @YouTube", "", $title);
    $title = str_replace("I added a video to", "", $title);
    $title = str_replace("playlist", "", $title);
    
    $title = str_replace("\"", "", $title);
    $title = str_replace("'", "", $title);
    
    if ($title == "") $title = @$news['alt_title'];
    
    $desc = trim($news['desc']);
    $desc = str_replace("\"", "", $desc);
    $desc = str_replace('"', "", $desc);
    $desc = str_replace("'", "", $desc);
               
    //kora
    $desc = str_replace('var addthis_config={"data_track_clickback":true};', "", $desc);
    $desc = str_replace('Get Al Jaras Updates with the most read and shared stories sent directly to you email <br /><br />', "", $desc);
    
    $desc = str_replace('This site uses cookies', "", $desc);
    $desc = str_replace('By clicking allow you are agreeing to our use of cookies.', "", $desc);
    $desc = str_replace('By clicking allow you are agreeing to our use of cookies', "", $desc);
    $desc = str_replace('Be a Citizen and discover all the benefits of being a City member.', "", $desc);
    $desc = str_replace('Be a Citizen and discover all the benefits of being a City member', "", $desc);
    $desc = str_replace('Find out more', "", $desc);
    
    $desc = str_replace('================ هـ ع.', "", $desc);
    $desc = str_replace('================ هـ ع', "", $desc);
    
    $desc = str_replace('1599998474121px; line-height: 1<br />3em;>', "", $desc);
    
    $desc = str_replace('<!-- Plugins: BeforeDisplayContent -->              <!-- K2 Plugins: K2BeforeDisplayContent -->                                 <!-- Item introtext -->       <div class=itemIntroText>           <h3 style=text-align: justify;><span style=font-size: 12<br />1599998474121px; line-height: 1<br />3em;>', "", $desc);
        
    $desc = str_replace('googletag<br />display(div-gpt-ad-mpu);', "", $desc);
    
    $desc = str_replace('ومقالات الرأي المنشرة علي حصري', "", $desc);
    $desc = str_replace('اشترك بالنشرة البريدية للمدونة لتصلك أخر الاخبار', "", $desc);
    
    $desc = str_replace('قم بإضافة تطبيق الموجز على متصفح كروم (Chrome) لتسهيل متابعة وقراءة اخر الاخبار من موقع الموجز<br /> مع هذا التطبيق ستكون على علم بأخر الاخبار المصرية والعربية والعالمية <br /><br />', "", $desc);
    $desc = str_replace('قم بإضافة تطبيق الموجز على متصفح كروم (Chrome) لتسهيل متابعة وقراءة اخر الاخبار من موقع الموجز<br /> مع هذا التطبيق ستكون على علم بأخر الاخبار المصرية والعربية والعالمية', "", $desc);
    
    $desc = str_replace('<br /><br /> محتوى حبر مرخص برخصة المشاع الإبداعي<br /> يسمح بإعادة نشر المواد بشرط الإشارة إلى المصدر بواسطة رابط (hyperlink)، وعدم إجراء تغييرات على النص، وعدم استخدامه لأغراض تجارية <br /><br />', "", $desc);
    
    $desc = str_replace('error was encountered while trying to use an ErrorDocument to handle the request', "", $desc);
    
    $desc = str_replace('To get best possible experiance using our website we recommend that you upgrade to a newer version or other web browser', "", $desc);
    $desc = str_replace('A list of the most popular web browsers can be found below', "", $desc);
    
    $desc = str_replace(' للتحقق ', "", $desc);
    $desc = str_replace('�', "", $desc);
    $title = str_replace('�', "", $title);
    
    //general
    $desc = str_replace('developer', "", $desc);
    $desc = str_replace('Developer', "", $desc);
    $desc = str_replace('API', "", $desc);
    $desc = str_replace('Api', "", $desc);
    $desc = str_replace('Terms', "", $desc);
    $desc = str_replace('Conditions', "", $desc);
    $desc = str_replace('Privacy', "", $desc);
    $desc = str_replace('Policy', "", $desc);
    $desc = str_replace('Copyright', "", $desc);
  //  $desc = str_replace('right', "", $desc);
  //  $desc = str_replace('left', "", $desc);
  //  $desc = str_replace('position', "", $desc);
   // $desc = str_replace('RTL', "", $desc);
  //  $desc = str_replace('LTR', "", $desc);
   // $desc = str_replace('rtl', "", $desc);
   // $desc = str_replace('ltr', "", $desc);
   // $desc = str_replace('pt', "", $desc);
   // $desc = str_replace('0001', "", $desc);
  //  $desc = str_replace('>', "", $desc);
    //$desc = str_replace('<', "", $desc);
    //$desc = str_replace('dir', "", $desc);
    //$desc = str_replace(';', "", $desc);
    
    $desc = str_replace('stLight<br />options({publisher: 2683a2c2-035f-4bce-b2c4-26b1a403e01a, doNotHash: false, doNotCopy: false, hashAddressBar: false});', "", $desc);
    $desc = str_replace('Lorem Ipsum is simply dummy text of the printing and typesetting industry<br /> Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took', "", $desc);
    
    $desc = str_replace('إضغط على الصورة لمشاهدة الحجم الكامل', "", $desc);
    
    $desc = str_replace('ظ‡ط§ظ… - ط§ظ„ط±ظٹط§ط¶', "", $desc);
    $desc = str_replace('ظ‡ط§ظ… - ط§ظ„ط±ظٹط§ط', "", $desc);
    
    $desc = str_replace('
Not All tags are allowed! Please remove html tags from your comments and try again', "", $desc);
$desc = str_replace('Not All tags are allowed! Please remove html tags from your comments and try again', "", $desc);
    
    $desc = str_replace('Powered by Dimofinf cms Version 3', "", $desc);
    $desc = str_replace('0Copyright© Dimensions Of Information Inc.', "", $desc);
    $desc = str_replace('404', "", $desc);
    
     $desc = str_replace('الرئيسية | الصور |  المقالات |  البطاقات | الملفات  | الجوال  |الأخبار |الفيديو |الصوتيات |راسلنا |للأعلى', "", $desc);
     $desc = str_replace('الرئيسية | الصور |&nbsp; المقالات |&nbsp; البطاقات |&nbsp;الملفات&nbsp; |&nbsp;الجوال &nbsp;|الأخبار |الفيديو |الصوتيات |راسلنا |للأعلى', "", $desc);
     $desc = str_replace('جميع الحقوق محفوظة لصحيفة الخبر تايمز ولا يسمح بالنسخ أو الاقتباس إلا بموافقه خطيه من إدارة الصحيفة', "", $desc);
    
    $desc = str_replace('function GoogleLanguageTranslatorInit() { new google', "", $desc);
    $desc = str_replace('translate', "", $desc);
    
    $desc = str_replace('كافة الحقوق محفوظة لـ scbnews<br />com &copy; 1436 التصميم بواسطة :ALTALEDI NET Powered by Dimofinf cms Version 3<br />0<br />0Copyright&copy; Dimensions Of Information Inc <br /><br />', "", $desc);
    $desc = str_replace('كافة الحقوق محفوظة لـ scbnews.com &copy; 1436 التصميم بواسطة :ALTALEDI NET Powered by Dimofinf cms Version 3.0.0Copyright&copy; Dimensions Of Information Inc', "", $desc);
    
    $desc = str_replace('باب.كوم جميع الحقوق محفوظة © 2015 شركة باب العالمية للخدمات المتخصصة – باب حاصلة على ترخيص وزارة الثقافة والإعلام', "", $desc);
    
    $desc = str_replace('الكوكيز يتيح لك العديد من الميزات لتعزيز تجربتك على موقع يابلادي. باستخدام هذا الموقع، فإنك توافق على سياستنا الخاصة بالحريات الشخصية، لمعرفة المزيد إظغط هناX', "", $desc);
    $desc = str_replace('الكوكيز يتيح لك العديد من الميزات لتعزيز تجربتك على موقع يابلادي. باستخدام هذا الموقع، فإنك توافق على سياستنا الخاصة بالحريات الشخصية، لمعرفة المزيد إظغط هنا', "", $desc);
    $desc = str_replace('الكوكيز يتيح لك العديد من الميزات لتعزيز تجربتك على موقع يابلادي', "", $desc);
    $desc = str_replace('الكوكيز يتيح لك العديد من الميزات لتعزيز تجربتك على موقعنا', "", $desc);
    $desc = str_replace('الكوكيز يتيح لك العديد من الميزات لتعزيز تجربتك على موقعنا. باستخدام هذا الموقع، فإنك توافق على سياستنا الخاصة بالحريات الشخصية، لمعرفة المزيد إظغط هناX', "", $desc);
    $desc = str_replace('الكوكيز يتيح لك العديد من الميزات لتعزيز تجربتك على موقعنا. باستخدام هذا الموقع، فإنك توافق على سياستنا الخاصة بالحريات الشخصية، لمعرفة المزيد إظغط هنا', "", $desc);
    
    $desc = str_replace('يمكنك الآن الاشتراك في القائمة البريدية و سوف يصلك جديد الأخبار على البريد الإلكتروني الخاص بكم', "", $desc);
    $desc = str_replace('يمكنك الآن الاشتراك في خدمة الرسائل القصيرة SMS , لتصلك آخر الأخبار على نقالك أولاً بأول', "", $desc);
          
    $desc = str_replace('Copyright © 2015 www.alnilin.com All Rights Reserved.', "", $desc);
    $desc = str_replace('موقع النيلين هو وجهتك الاولى للاخبار المحلية والعالمية ، الصور والفيديو والمنوعات ،الوظائف ، والتسويق ، الاعلانات', "", $desc);
    
    $desc = str_replace('var initId = 880103; function changeVideo(id){ if(id == initId){ return false; } $.ajax({ url: http://www.charlesayoub.com/get-video-embed/+id+/side, beforeSend: function(){ $(#videoLoader).show(); $(#playSection).css({opacity:0.5}); }, success: function(data) { $(#videoLoader).hide(); $(#playSection).css({opacity:1}); $(#playSection).html(data); initId = id; } }); }', "", $desc);
    
    $desc = str_replace('googletag.defineSlot(5308/ab_ar, [300,250], banner300x250).addService(googletag.pubads()); googletag.pubads().enableSyncRendering();
googletag.enableServices(); var wd = 300; var ht = 250; var divPart1 = banner+wd; var divPart2=x+ht; // var divPart1 = banner+300; // var divPart2=x+250; if(wd == 1 && ht ==2){ googletag.display(divPart1+divPart2+-oop); }else{ googletag.display(divPart1+divPart2); }', "", $desc);

    
    $desc = str_replace('ومضة هي منصة تعنى بالاستثمار وبدعم رواد الأعمال في منطقة الشرق الأوسط وشمال إفريقيا.', "", $desc);
    
    $desc = str_replace('© جميع الحقوق محفوظة لقناة العربية 2015 Provided by SyndiGate Media Inc. (Syndigate.info).', "", $desc);
    $desc = str_replace('© جميع الحقوق محفوظة لقناة العربية 2016 Provided by SyndiGate Media Inc. (Syndigate.info).', "", $desc);
    $desc = str_replace('© جميع الحقوق محفوظة لقناة العربية 2017 Provided by SyndiGate Media Inc. (Syndigate.info).', "", $desc);
    
    $desc = str_replace('$(".more").disableTextSelect(); $(function(){ $(".more-selected").disableTextSelect(); });', "", $desc);
    $desc = str_replace('$(.more).disableTextSelect(); $(function(){ $(.more-selected).disableTextSelect(); });', "", $desc);
    $desc = str_replace('&nbsp; var addthis_config={data_track_clickback:true};', "", $desc);
    
    $desc = str_replace('(adsbygoogle = window.adsbygoogle || []).push({});Tweet', "", $desc);
    $desc = str_replace('Powered by Dimofinf cms Version 3.0.0Copyright© Dimensions Of Information Inc.', "", $desc);
    
    $desc = str_replace('Powered by Dimofinf cms Version 3.0.0Copyright© Dimensions Of Information Inc. Design By : ALTALEDI.NET', "", $desc);
    $desc = str_replace(' :ALTALEDI NET Powered by Dimofinf cms Version 3.0.0Copyright© Dimensions Of Information Inc.', "", $desc);
    
    $desc = str_replace('“. #Porto 3-0 #Basel (Agg 4-1) . #Herrera 47 @casemiro_oficial 56 (Super goal)”', "", $desc);
    $desc = str_replace('“Half time . #Porto 1-0 #Basel (Agg 2-1) . #Brahimi 14”', "", $desc);
    
    $desc = str_replace('--> $(document).ready(function(){ setTimeout(function() { $(#todayWeatherContainer).load(/pages/today_weather/0); }, 10000); });', "", $desc);
    
    $desc = str_replace('Powered by vBulletin™ Version 4.2.2 Copyright © 2015 vBulletin Solutions, Inc. All rights reserved. vb4 Watermark Generator provided by Purgatory-Labs.de', "", $desc);

    $desc = str_replace('You are using an outdated browser. Please upgrade your browser to improve your experience.', "", $desc);
    $desc = str_replace('To get best possible experiance using our website we recommend that you upgrade to a newer version or other web browser. A list of the most popular web browsers can be found below.', "", $desc);
   
    $desc = str_replace('هذا الموقع لا يدعم مستعرض Internet Explorer 6, للحصول على أفضل تجربة لموقع Goal الرجاء تحديث المستعرض الخاص بكاضغط هنا لتحديث مستعرض الانترنت الخاص بك', "", $desc);
    $desc = str_replace('هذا الموقع لا يدعم مستعرض Internet Explorer 7, للحصول على أفضل تجربة لموقع Goal الرجاء تحديث المستعرض الخاص بكاضغط هنا لتحديث مستعرض الانترنت الخاص بك', "", $desc);
    $desc = str_replace('هذا الموقع لا يدعم مستعرض Internet Explorer 8, للحصول على أفضل تجربة لموقع Goal الرجاء تحديث المستعرض الخاص بكاضغط هنا لتحديث مستعرض الانترنت الخاص بك', "", $desc);
 
    $desc = str_replace('أنت تستخدم إصداراً قديماً من مستعرض الانترنت, للحصول على أفضل تجربة لموقع Goal الرجاء تحديث مستعرضك.اضغط هنا لتحديث مستعرض الانترنت الخاص بك', "", $desc);
    
    $desc = str_replace('We use cookies on this web site... To find out more about cookies, please see our Privacy Policy. If you continue using our website, we will assume that you consent to the cookies we set.', "", $desc);
   
    $desc = str_replace('To get best possible experiance using our website we recommend that you upgrade to a newer version or other web browser. A list of the most popular web browsers can be found below.', "", $desc);
    
    $desc = str_replace('function get_url(title,url){ var title_encode=encodeURI(title); document.getElementById(title).href =components/com_mailajax/form.php?url=+url+&title=+title_encode+&keepThis=true&TB_iframe=true&height=325&width=425; //location.href=components/com_mailajax/form.php?url=+url+&title=+title_encode+&keepThis=true&TB_iframe=true&height=325&width=425; } function fnSave() { document.execCommand(SaveAs,null,document.title); } function Check_Controls() { var form = document.adminForm; // do field validation var filter=/[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}/; if(form.FNameOfQuestioner.value==){ alert(', "", $desc);
    $desc = str_replace('); return false; } else { form.submit(); } }', "", $desc);
    $desc = str_replace('myButton { background-color:#660033; border:1px solid #660033; display:inline-block; color:#ffffff; font-family:arial; font-size:14px; padding:6px 12px; text-decoration:none; width: 174px; text-shadow:0px 1px 0px #b20f50; }', "", $desc);
               
    
    $desc = str_replace('CNN © 2014 Cable News Network. Turner Broadcasting System, Inc. All Rights Reserved', "", $desc);
    $desc = str_replace('CNN © 2015 Cable News Network. Turner Broadcasting System, Inc. All Rights Reserved', "", $desc);
    $desc = str_replace('CNN © 2016 Cable News Network. Turner Broadcasting System, Inc. All Rights Reserved', "", $desc);
    
    $desc = str_replace('Web Design & Development By Mega Solutions! Web Design Egypt', "", $desc);
    $desc = str_replace('embed" dir="RTL">', "", $desc);
    
    $desc = str_replace('$(document).ready( function(){ $(.ticker).innerfade({ animationtype: slide, speed: 750, timeout: 4000, type: random, containerheight: 1em });} );', "", $desc);
    $desc = str_replace('Powered by Dimofinf cms Version 3.0.0Copyright© Dimensions Of Information Inc.', "", $desc);
    $desc = str_replace('Powered by Dimofinf cms Version 3.0.0Copyright© Dimensions Of Information Inc.\n', "", $desc);
    
    $desc = str_replace('new TWTR.Widget({ version: 2, type: profile, rpp: 3, interval: 1000, width: 246, height: 265, theme: { shell: { background: #63BEFD, color: #FFFFFF }, tweets: { background: #FFFFFF, color: #000000, links: #47a61e } }, features: { loop: false,live: true, scrollbar: false,hashtags: false,timestamp: true, avatars: true,behavior: default } }).render().setUser(binybohair).start();', "", $desc);
    $desc = str_replace('vbmenu_register(posts6_32925, true);\nPowered by vBulletin® Version 3.8.7Copyright ©2000 - 2015, vBulletin Solutions, Inc. Content Relevant URLs by vBSEO 3.6.0 PL2', "", $desc);
    $desc = str_replace('SALEM ALSHMRANI Ads Management Version 3.0.1 by Saeed Al-Atwi', "", $desc);
    $desc = str_replace('var sAppPath = /; var fbLanguage = ar_AR; var sImageLangPath = ar; var LanguageDirection = right; //', "", $desc);
    
    //for cnn
    $desc = str_replace("اشترك في خدمة آخر خبر من CNNArabic.com وتلقى بريد إلكتروني فور حدوث أي خبر مهم.", "", $desc);
    $desc = str_replace("NN © 2014 Cable News Network. Turner Broadcasting System, Inc. All Rights Reserved", "", $desc);
    $desc = str_replace("الآراء الواردة أدناه لا تعبر عن رأي موقع CNN بالعربية، بل تعكس وجهات نظر أصحابها فقط.", "", $desc);
    $desc = str_replace("استخدامها، مع اسمك وصورتك، استنادا إلى سياسة الخصوصية بما يتوافق مع شروط استخدام الموقع.", "", $desc);
    $desc = str_replace("ترحب شبكة CNN بالنقاش الحيوي والمفيد، وكي لا نضطر في موقع CNN بالعربية إلى مراجعة التعليقات قبل نشرها. ننصحك بمراجعة إرشادات الاستخدام للتعرف إليها جيداً. وللعلم فان جميع مشاركاتك يمكن", "", $desc);
    $desc = str_replace("(CNN) -- &nbsp;", "", $desc);
    
    //maan
    $desc = str_replace("الرئيسية التغطية الـقـــــدس رام الـلــــه بيـت لـحـم الخـلــيـــــل نــابــلـــــس أريـــحــــــــــا طولـكــــرم جــنــيــــــــن قـلقـيـلـيـة طـوبــــاس ســلـفـيــت قطاع غزة الـشـــــتـــــــات فلسطين 48 عربي ودولي اســرائيـلـيـات أخـبــار اقتـصــاد أســرى ريـاضــة مرور وحوادث الشــتات فلسطين 48 عربي ودولي اسرائيليات", "", $desc); 
    
    $desc = str_replace('\r\n', "", $desc);
    //$desc = str_replace(" \n ", "", $desc);
    $desc = str_replace('\r', "", $desc);
    //$desc = str_replace('<br/>', "", $desc);
                           
    $desc = str_replace("<br/><br/>C<br/>", "", $desc);
    $desc = str_replace("\n\nC\n", "", $desc);
    $desc = str_replace("<br/>C", "", $desc);
    $desc = str_replace("\nC", "", $desc);
    $desc = str_replace("Developer API Terms &amp; Conditions Privacy Policy Copyright &copy;2014 Hootsuite Media Inc. All Rights Reserved.", "", $desc);
    
    $desc = str_replace("Developed by Creation House", "", $desc);
    $desc = str_replace("الأخبار
                        المقالات
                        الأمراض
                        استشارات
                        سلايد شو
                        كويز
                        انفوجرافيك
                        الوظائف
                        اكلات صحية", "", $desc);
    
    $twitter_new_id = $news['twitter_news_id'];
    $image = @$news['image'];
    $url = $news['url'];
    $cid = $news['cid'];
    $date_added = time();
    $added_by = 3;//by cron job
         
    if (
          strpos($desc, "Add to Want to watch this again later?") !== FALSE
       ) {
           $desc = $title;
    }
       //   echo($desc);      exit;
    //$twitte_count = check_if_twitter_news_exists($twitter_new_id);
               
    //if (!$twitte_count) {
        $title_exists = check_if_title_exists($title, $twitter_new_id);
             //echo('000000000000000000000000000000000000000');  
        if (!$title_exists /*&& $twitter_new_id != ""*/) {
            //$title = mysqli_escape_string($conn, $title);
          //  $desc = mysqli_escape_string($conn, $desc);
          
            $site_data_array = serialize($site_data_array);          
            $site_data_array = str_replace('"', "", $site_data_array);
            $site_data_array = str_replace("'", "", $site_data_array);
            $site_data_array = str_replace('\r\n', "", $site_data_array);
            $site_data_array = str_replace(" \n ", "", $site_data_array);
            $site_data_array = str_replace('\r', "", $site_data_array);
            $site_data_array = str_replace('<br/>', "", $site_data_array);
                                               
            if(strrpos($reversed_url,"yalla") === FALSE) {            
                if(strrpos($reversed_url,"kora.com") !== FALSE || 
                  // strrpos($reversed_url,"tracksport.net") !== FALSE ||
                   strrpos($reversed_url,"forbesmiddleeast.com") !== FALSE ||
                   strrpos($reversed_url,"binybohair.com") !== FALSE ||
                   //strrpos($reversed_url,"alsopar.com") !== FALSE ||
                   strrpos($reversed_url,"sudaneseonline.com") !== FALSE ||
                   strrpos($reversed_url,"moi.gov.qa") !== FALSE ||
                   strrpos($reversed_url,"qh.gov.sa") !== FALSE ||
                   strrpos($reversed_url,"elfann.com") !== FALSE ||
                  //strrpos($reversed_url,"hroobnews.com") !== FALSE ||
                   strrpos($reversed_url,"yemen-press.com") !== FALSE ||
                 //  strrpos($reversed_url,"hassacom.com") !== FALSE ||
                  // strrpos($reversed_url,"almotamar.net") !== FALSE ||
                //   strrpos($reversed_url,"lahaonline.com") !== FALSE ||
                   strrpos($reversed_url,"barca4ever.com") !== FALSE ||
                   strrpos($reversed_url,"elnashra.com") !== FALSE ||
                   strrpos($reversed_url,"qassimy.com") !== FALSE ||
                   strrpos($reversed_url,"sahafah.net") !== FALSE ||
                 //  strrpos($reversed_url,"scbnews.com") !== FALSE ||
                   strrpos($reversed_url,"shathanews.com") !== FALSE ||
                   strrpos($reversed_url,"sheikhmohammed.ae") !== FALSE ||
                   strrpos($reversed_url,"nna-leb.gov.lb") !== FALSE ||
                   strrpos($reversed_url,"sabanews.net") !== FALSE ||
                  // strrpos($reversed_url,"almaydan2.net") !== FALSE ||
                   //strrpos($reversed_url,"lebanonfiles.com") !== FALSE ||
                   strrpos($reversed_url,"enferaad.com") !== FALSE ||
                  // strrpos($reversed_url,"yemenat.net") !== FALSE ||
                   //strrpos($reversed_url,"fath-news.com") !== FALSE ||
                  // strrpos($reversed_url,"marebpress.net") !== FALSE ||
                   strrpos($reversed_url,"24yemen.net") !== FALSE ||
                   strrpos($reversed_url,"yemen-press.net") !== FALSE ||
                   strrpos($reversed_url,"yemen-perss.com") !== FALSE ||
                   strrpos($reversed_url,"maps.google.com.qa") !== FALSE ||
                   strrpos($reversed_url,"alazraq.com") !== FALSE ||
                  // strrpos($reversed_url,"aksalser.com") !== FALSE ||
                   strrpos($reversed_url,"guryatnews.com") !== FALSE ||
                   //strrpos($reversed_url,"shabiba.com") !== FALSE ||
                   strrpos($reversed_url,"arabic-military.com") !== FALSE ||
                   strrpos($reversed_url,"nmisr.com") !== FALSE ||
                   strrpos($reversed_url,"sactr.net") !== FALSE ||
                   strrpos($reversed_url,"ajmanpolice.gov") !== FALSE ||
                   //strrpos($reversed_url,"reqaba.com") !== FALSE ||
                   //strrpos($reversed_url,"almaydan2.net") !== FALSE ||
                   strrpos($reversed_url,"ham-24.com") !== FALSE ||
                   //strrpos($reversed_url,"nas.sa") !== FALSE ||
                   strrpos($reversed_url,"marib.net") !== FALSE ||
                   //strrpos($reversed_url,"babnet.net") !== FALSE ||
                   //strrpos($reversed_url,"tracksport.net") !== FALSE ||
                   strrpos($reversed_url,"assawsana.com") !== FALSE ||
                  // strrpos($reversed_url,"reqaba.com") !== FALSE ||
                   strrpos($reversed_url,"alwasatnews.com") !== FALSE ||
                   strrpos($reversed_url,"sabqq.org") !== FALSE ||
                   //strrpos($reversed_url,"alsopar.com") !== FALSE ||
                   strrpos($reversed_url,"nna-leb.gov.lb") !== FALSE ||
                   strrpos($reversed_url,"saidaonline.com") !== FALSE ||
                   //strrpos($reversed_url,"hroobnews.com") !== FALSE ||
                   //strrpos($reversed_url,"lahaonline.com") !== FALSE ||
                   strrpos($reversed_url,"alhilal.com") !== FALSE) { //exit; 
                    $desc = iconv('windows-1256', 'UTF-8', $desc);
                    echo('<br /> 2converted to utf-8 <br />');
                   // $desc = str_replace('<br/>', "", $desc);
                }
            }
            
          /*  if (strrpos($reversed_url,"klmty.net") !== FALSE) {
                $desc = 'go to fb';
            } */
            if (strrpos($reversed_url,"/vb/") !== FALSE) { //forum
                $desc = 'go to fb';
            }
            if (strrpos($reversed_url,"instagram.com") !== FALSE) {
                $desc = 'go to fb';
            }
            if ( strrpos($reversed_url,"hawahome.com") !== FALSE ||    
                 strrpos($reversed_url,"zakatfund.gov.ae") !== FALSE || 
                 strrpos($reversed_url,"basmaty.com") !== FALSE || 
                 strrpos($reversed_url,"manalonline.com") !== FALSE || 
                 strrpos($reversed_url,"atyabtabkha.3a2ilati.com") !== FALSE || 
                 strrpos($reversed_url,"koooraworld.net") !== FALSE || 
                 strrpos($reversed_url,"sada-al-malaeb-cat") !== FALSE || 
                 strrpos($reversed_url,"onmbc.net") !== FALSE || 
                 strrpos($reversed_url,"fatafeat.com") !== FALSE || 
                 strrpos($reversed_url,"zf.ae") !== FALSE) {       
                $desc = 'go to fb';
            }
            if (strrpos($reversed_url,"dmi.ae/samadubai") !== FALSE) {
                $desc = 'go to fb';
            }
            if (strrpos($reversed_url,"dcndigital.ae") !== FALSE) {
                $desc = 'go to fb';
            }
            if (strrpos($reversed_url,"ittisport.com") !== FALSE) {
                $desc = 'go to fb';
            }
            if (strrpos($reversed_url,"alg360.com") !== FALSE) {
                $desc = 'go to fb';
            }
            if (strrpos($reversed_url,"argaam.com") !== FALSE || strrpos($reversed_url,"alborsanews.com") !== FALSE) {
                if (strrpos($desc,"<table") !== FALSE) {
                    $desc = 'go to fb';
                }
            } 
            if (strrpos($reversed_url,"moe.gov.qa") !== FALSE) {
                if (strrpos($desc,"<table") !== FALSE) {
                    $desc = 'go to fb';
                }
            }
            if (strrpos($reversed_url,"yallakora.com") !== FALSE) {
                $image = str_replace('\\', '/', $image);
            }
            
                //echo('descccccccccccccccc: '.$desc);exit;   
            $image = str_replace("new.bab.com", "www.bab.com", $image);
            
                             //    echo($reversed_url);exit;
            if (trim(@$title) != "") {
                
               /* if (
                    strrpos($reversed_url,"almogaz.com") !== FALSE ||
                    strrpos($reversed_url,"elshaab.org") !== FALSE ||
                    strrpos($reversed_url,"elwatannews.com") !== FALSE ||
                    strrpos($reversed_url,"akhbarak.net") !== FALSE ||
                    strrpos($reversed_url,"arabsturbo.com") !== FALSE ||
                    strrpos($reversed_url,"dailymedicalinfo.com") !== FALSE 
                   ) {
                        if ($desc != "") $desc .= " ... يمكنكم قرأءة باقي الخبر على الموقع الرسمي";
                     }  */
                     
                if (strrpos($reversed_url,"sabanews.net") !== FALSE) {
                    $desc1 = explode("htm", $desc);
                    
                    $desc = $desc1[1];
                }
                
                $desc = $conn->db_escape_string($desc);
                $title = $conn->db_escape_string($title);
                $url = $conn->db_escape_string($url);              
                
                $insert = "insert into articles_html (twitter_news_id, title, body, news_url, image, added_by, date_added, reversed_url) 
                                       value ('$twitter_new_id', '$title', '$desc', '$url', '$image', '$added_by', '$date_added', '$reversed_url')";
                                 echo($insert);  //  exit; 
                                 
                if(
                    strrpos($desc,"you may be trying to access") === FALSE &&
                    strrpos($desc,"find out what fines") === FALSE &&
                    strrpos($desc,"I Agree on the And to user this") === FALSE &&
                    strrpos($desc,"getElementById") === FALSE &&
                    strrpos($desc,"getElementsBy") === FALSE &&
                    strrpos($desc,"window") === FALSE &&
                    strrpos($desc,"document") === FALSE &&
                    strrpos($desc,"use your My ID credentials to access the apps") === FALSE &&
                    strrpos($desc,"You may be trying to access this site from a secured browser") === FALSE
                  ) { 
                        $news_found = check_if_twitter_news_exists($twitter_new_id);
                        
                        if (!$news_found) {
                            $res = $conn->db_query($insert);
                            
                            $aid = $conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning
                                echo('<br />$aid: ' . $aid . '<br />');
                            $cat_query = "insert into article_categories (aid, cid) value ('$aid', '$cid')";
                            $res = $conn->db_query($cat_query);
                                              //exit;
                            $source_name_query = "select name from categories where id = '$cid'";
                            $source_name_res = $conn->db_query($source_name_query);
                            $source_name_data = $conn->db_fetch_object($source_name_res);
                            
                            date_default_timezone_set('Asia/Jerusalem');
                            
                            $current_time = time();
                            //$current_time = strtotime('15-06-24 12:50 am');
                            //$start_time = strtotime('09:00 pm');
                            //$start_time = strtotime(date("Y-m-d", strtotime( date("Y-m-d") . ' -1 days')) . ' ' . '09:00 pm');
                            $start_time = strtotime('12:00 am');
                            //$next_date =  date("Y-m-d", strtotime( date("Y-m-d") . ' + ' . $data['days'] . ' days')) . ' ' . $data['time'];
                           // $start_time = strtotime('08:30 am');
                            $end_time = strtotime('06:00 am');
                          //  $end_time = strtotime('03:00 am');
                         //   $end_time = strtotime('09:30 am');
                                   echo('<br /><br />start time: ' . $start_time . '(' . date('y-m-d h:i a', $start_time) . ')' . '<br />');
                                   echo('current time: ' . $current_time . '(' . date('y-m-d h:i a', $current_time) . ')' . '<br />');
                                   echo('end time: ' . $end_time . '(' . date('y-m-d h:i a', $end_time) . ')' . '<br /><br />');
                                   
                            if($end_time >= $current_time && $current_time >= $start_time) {
                                echo '<br /><br />do not send PNNNNNNNNNNNNNNNNNNNNN<br /><br />';
                            }
                            else {
                                breaking_pn($title, $cid, $source_name_data->name, $aid);
                                keyword_pn($title, $source_name_data->name, $aid);
                                echo('<br /><br />send PNNNNNNNNNNNNNNNNNNNNNNNNNNNN<br /><br />');
                            }
                            
                            
                            
                            process_tags($title, $aid);
                        }
                
                  }
                
                
            }
            
           // $tag_query = "insert into article_tags (aid, tid) value ('$aid', '1')";
            //$res = $conn->db_query($tag_query);
        }
        else{
            echo 'title existssssssss';
        }
        
      //  exit;
             
   /* }
    else{  */
        //update 
       // $title = mysqli_escape_string($conn, $title);
       // $desc = mysqli_escape_string($conn, $desc);
            
      /*  $update = "update articles_html set title = '$title', body = '$desc', news_url = '$url', updated_date = '$date_added', updated_by = '$added_by' 
                   where twitter_news_id = '$twitter_new_id'";
        $res = $conn->db_query($update);
    } */
} 

function keyword_pn($title, $source_name, $aid) {
    global $conn;
    $title = trim($title);
            echo('<br />keyword pn: '.$title.'<br />');
    $breaking_array = array('عاجـــل' , 'عاجل' , 'عـاجل' , 'عــاجل' , 'عاجـل' , 'عاجــل' , 'عـــاجل', 'AJABreaking', 'Breaking');
    
    $title_arr = explode(" ", $title);
    $keyword_str = '';
    foreach($title_arr as $title_) {
        if (mb_strlen($title_, 'UTF-8') >= 3) {
            $keyword_query = "select id from tags where synonyms = '$title_'";
            $keyword_res = $conn->db_query($keyword_query);
            $keyword_id = $conn->db_fetch_object($keyword_res);
            
            if ($keyword_id) {
               $keyword_str .= $keyword_id->id . ' - '; 
           
               $channel = "b" . $keyword_id->id;
               
               foreach($breaking_array as $word){
                   if (strrpos($title, $word) !== FALSE){
                        parse_pn($title, $channel, '', $source_name, $aid);
            
                        $fp = fopen('keyword_pn.txt', 'a+');
                        fwrite($fp, $title);
                        fclose($fp);
                    
                        break;
                   }
               }
            }
        }
    }
    
    echo $keyword_str;
}

function breaking_pn($title, $cid, $source_name, $aid) {    
    $title = trim($title);
            echo('<br />breaking pn: '.$title.'<br />');
    $breaking_array = array('عاجـــل' , 'عاجل' , 'عـاجل' , 'عــاجل' , 'عاجـل' , 'عاجــل' , 'عـــاجل', 'AJABreaking', 'Breaking');
    
    $channel = "a" . $cid;
    
    foreach($breaking_array as $word){
        if (strrpos($title, $word) !== FALSE){
            //send pn
            parse_pn($title, $channel, $cid, $source_name, $aid);
            
            $fp = fopen('breaking_pn.txt', 'a+');
            fwrite($fp, $title);
            fclose($fp);
        
            break;
        }
    }
    
}

function parse_pn($title, $channel, $cid, $source_name, $aid) {
     $_GET['source_name'] = $source_name;
     $_GET['cid'] = $cid;
     $_GET['channel'] = $channel;
     $_GET['title'] = str_replace("\n", '', $title);
     $_GET['title'] = str_replace('\n', '', $title);
     $_GET['aid'] = $aid;

     include('parse-php-sdk-master/index.php'); 
}       
                        

         //   echo('666666666666');  
function get_sources_xxxxx($cat_id, $connection, $start, $offset) {
    global $conn;
    /*$query = "select category_id, link, categories.parent
                from categories
                inner join rss_news on categories.id = rss_news.category_id
                where categories.parent = '$cat_id'";*/
    
    $start = ($start*$offset);
                
    $query = "select category_id, link, c1.parent
                from categories c1
                inner join rss_news on c1.id = rss_news.category_id
                inner join categories c2 on c1.parent = c2.id
                where c2.parent = '$cat_id' and rss_news.type = 1 order by category_id desc limit $start, $offset";    //twitter
                   
                   // echo($query);   exit;
    $res = $conn->db_query($query);
    $desc = '';
    $news['desc'] = '';
               //  echo('9999999999');
    //while($row = $conn->db_fetch_array($res)) { //echo($row);exit;   
    while($row = $conn->fetch_assoc($res)) { //echo($row);exit;   
                                  
   // echo('88888888888888');
         $tweets = $connection->get($row['link']);    //parse twitter link
         
         $news['cid'] = $row['category_id'];
                        //   pr($tweets);  exit;
                       // pr( json_encode($tweets)); exit;
         foreach($tweets as $tweet) { //   pr($tweet);  exit;
            $news['title'] = $tweet->text;
            $news['alt_title'] = @$tweet->user->description;
            $news['twitter_news_id'] = (integer)@$tweet->id;
            $news['image'] = @$tweet->extended_entities->media[0]->media_url;
            $url = @$tweet->entities->urls;
            
            $news['url'] = @$url[0]->expanded_url;
            
            $news['url'] = str_replace('amp;', '&', $news['url']);
            $news['url'] = str_replace('amp; ', '&', $news['url']);
            $news['url'] = str_replace(' amp;', '&', $news['url']);
            
            //$news['url'] = 'http://ow.ly/IRPcy';
                                     
            //$url[0]->expanded_url = 'http://ow.ly/IRPcy';
            
            $reversed_url = '';
            
            if (isset($url[0]->expanded_url)) {
                $reversed_url = reverse_tinyurl(@$url[0]->expanded_url);
                
                if (strpos($reversed_url, "bit.ly") !== FALSE || 
                    strpos($reversed_url, "news.google.com") !== FALSE || 
                    strpos($reversed_url, "feedproxy.google.com") !== FALSE || 
                    strpos($reversed_url, "eel.la") !== FALSE || 
                    strpos($reversed_url, "trib.al") !== FALSE || 
                    strpos($reversed_url, "alanba.com.kw") !== FALSE || 
                    strpos($reversed_url, "fb.me") !== FALSE || 
                    strpos($reversed_url, "t.co") !== FALSE || 
                    strpos($reversed_url, "goo.gl") !== FALSE || 
                    strpos($reversed_url, "ow.ly") !== FALSE || 
                    strpos($reversed_url, "shar.es") !== FALSE){
                    $reversed_url = reverse_tinyurl($reversed_url);
                }
            
            } 
            
            $url_decode = urldecode($reversed_url);
            $data = '';
            
                  echo('<br />reversed xxxx: ' . $reversed_url . '<br />');
                  echo('<br />$url_decode xxxx: ' . $url_decode . '<br />');
            if ( isset($url[0]->expanded_url) && 
                 strpos($reversed_url, "http://www.alwatanvoice.com/common/error.html") === FALSE &&
                 strpos($url_decode, "http://www.youm7.com/أخبار-عاجلة-65") === FALSE &&
                 strpos($reversed_url, "http://www.alwatanvoice.com/arabic/index.html") === FALSE
                ) {
                if (strpos($reversed_url, 'fb.me') !== false || strpos($reversed_url, 'facebook.com') !== false) {
                    $news['desc'] = "go to fb";
                    $news['image'] = "";
                }  
                else if (strpos($reversed_url, 'youtube.com') !== false || strpos($reversed_url, 'youtu.be') !== false) {
                    $news['desc'] = "go to youtube";
                    $news['image'] = "";
                }  
                else if (strpos($reversed_url, 'audio.islamweb.net') !== false) {
                    $news['desc'] = "go to islamweb";
                }
                else if (strpos($reversed_url, 'instagram.com') !== false) {
                    $news['desc'] = "go to islamweb";
                }
                else if (strpos($reversed_url, '/vb/') !== false) {
                    $news['desc'] = "go to fb";
                }
                else if (strpos($reversed_url, 'media.5d3a.com') !== false /*|| strpos($reversed_url, 'wikise7a.com') !== false*/) {
                    $news['desc'] = "go to fb";
                }
                else if (strpos($reversed_url, 'zakatfund.gov.ae') !== false || strrpos($reversed_url,"zf.ae") !== FALSE) {
                    $news['desc'] = "go to fb";
                }
                else if (strpos($reversed_url, 'altibbi.com') !== false) {
                    $news['desc'] = "go to fb";
                }
                else if (strpos($reversed_url, 'ittisport.com') !== false) {
                    $news['desc'] = "go to fb";
                }
                else if (strpos($reversed_url, 'alg360.com') !== false) {
                    $news['desc'] = "go to fb";
                }
                else if (strpos($reversed_url, 'dcndigital.ae') !== false) {
                    $news['desc'] = "go to fb";
                }
                else if (strpos($reversed_url, 'dmi.ae/samadubai') !== false) {
                    $news['desc'] = "go to fb";
                } 
                else if (strpos($reversed_url, 'fitnessyard.com/registration') !== false) {
                    $news['desc'] = "";
                }
                else if (strpos($reversed_url, 'fitnessyard.com/workout/exercise-directory') !== false) {
                    $news['desc'] = "";
                }
                else if (strpos($reversed_url, '3eesho.com/articles/browse/category') !== false) {
                    $news['desc'] = "";
                }
                else if (strpos($reversed_url, 'vine.co') !== false) {
                    $news['desc'] = "go to fb";
                }else if (strpos($reversed_url, '.pdf') !== false) {
                    $news['desc'] = "go to pdf";
                } else if (strpos($reversed_url, 'twitter.com') !== false) {
                    continue;
                } 
                else{   
                    if ($url[0]->expanded_url != "") {
                        
                        $twitte_count = check_if_twitter_news_exists($news['twitter_news_id']);
                        echo('<br />$twitte_count: ' . $twitte_count . '<br />');
                        $news['desc'] = '';
                        
                        if (!$twitte_count) {      
                            $data = fetch($url[0]->expanded_url); 
                            
                            if ($data['title'] == 'أخبار عاجلة | اليوم السابع') continue;            
                                    //echo('<br />'.$url[0]->expanded_url.'<br />');  pr($data);   exit;  
                                /*  if ($url[0]->expanded_url == 'http://ow.ly/GBQAd') */ //pr($data['paragraph']); 
                                
                            if (isset($data['paragraph'])) {
                                if (is_array($data['paragraph'])) {
                                    $counter = 0;
                                    
                                    foreach($data['paragraph'] as $content) {
                                        if (trim($content['contents']) != "" && 
                                            strpos($content['contents'], 'المعذرة ، لقد حدث خطأ ما') === FALSE &&
                                            strpos($content['contents'], 'الصفحة المطلوبة غير موجودة') === FALSE &&
                                            strpos($content['contents'], 'هذه الصفحة غير متاحة حالياً') === FALSE &&
                                            strpos($content['contents'], 'مميزات والخيارات المتاحة فقط للأعضاء') === FALSE &&
                                            strpos($content['contents'], 'لقد حدث خطأ في استخدامك لنظام التصفح') === FALSE &&
                                            strpos($content['contents'], '404اتصل بنا شروط الاستخدام عن الموقع خدمة الرسائل بوابة الشروق 2015 جميع الحقوق محفوظة') === FALSE &&
                                            strpos($content['contents'], 'getElementById') === FALSE &&
                                            strpos($content['contents'], 'getElementsBy') === FALSE &&
                                            strpos($content['contents'], 'من نحن | اتصل بنا | المواطن الصحفي') === FALSE &&
                                            strpos($content['contents'], 'addThumbnail') === FALSE &&
                                            strpos($content['contents'], 'Your browser will redirect to your requested content shortly') === FALSE &&
                                            strpos($content['contents'], 'Completing the CAPTCHA proves you are a human and gives') === FALSE &&
                                            strpos($content['contents'], 'TWTR.Widget') === FALSE &&
                                            strpos($content['contents'], 'window') === FALSE && 
                                            strpos($content['contents'], 'Internal Server Error') === FALSE && 
                                            strpos($content['contents'], 'document') === FALSE &&
                                            strpos($content['contents'], 'جميع التعليقات') === FALSE &&
                                            strpos($content['contents'], 'الأخبار العاجلةالأولىأول') === FALSE &&
                                            strpos($content['contents'], 'الوسوم') === FALSE &&
                                            strpos($content['contents'], 'قم بالتسجيل') === FALSE &&
                                            strpos($content['contents'], 'جميع الحقوق') === FALSE &&
                                            strpos($content['contents'], 'رئيس التحرير') === FALSE &&
                                            strpos($content['contents'], 'رئاسة التحرير') === FALSE &&
                                            strpos($content['contents'], 'الرئيسية الأخبار') === FALSE &&
                                            strpos($content['contents'], 'الرئيسية الاخبار') === FALSE &&
                                            strpos($content['contents'], 'جميع الحقوق محفوظة') === FALSE &&
                                            strpos($content['contents'], 'الرئيسية مركز المباريات') === FALSE &&
                                            strpos($content['contents'], 'function') === FALSE &&
                                            strpos($content['contents'], 'success') === FALSE &&  
                                            strpos($content['contents'], 'بالفيديو والصور') === FALSE &&  
                                            strpos($content['contents'], 'شؤون دينية') === FALSE &&
                                            strpos($desc['contents'], 'ة في رحاب الداعية ') === FALSE &&
                                            strpos($content['contents'], 'updateEmailTo') === FALSE &&
                                            strpos($content['contents'], 'أخبار محلية أخبار المناطق') === FALSE &&
                                            strpos($content['contents'], 'Adobe Flash Player') === FALSE &&
                                            strpos($content['contents'], 'Flash Player') === FALSE &&
                                            strpos($content['contents'], 'ContentType: Video') === FALSE &&
                                            strpos($content['contents'], 'مواقع شبكة الجزيرة: الجزيرة') === FALSE &&
                                            strpos($content['contents'], 'siteheadersponsorship') === FALSE &&
                                            strpos($content['contents'], 'اسم المستخدم الى بريدك الالكتروني') === FALSE &&
                                            strpos($content['contents'], 'jQuery(function($)') === FALSE &&
                                            strpos($content['contents'], 'jQuery') === FALSE &&
                                            strpos($content['contents'], 'function') === FALSE &&
                                            strpos($content['contents'], 'password') === FALSE &&
                                            strpos($content['contents'], 'Password') === FALSE &&
                                            strpos($content['contents'], 'Rights') === FALSE &&
                                            strpos($content['contents'], 'rights') === FALSE &&
                                            strpos($content['contents'], 'copyright') === FALSE &&
                                            strpos($content['contents'], 'Copyright') === FALSE &&
                                            strpos($content['contents'], 'Copyrights') === FALSE &&
                                            strpos($content['contents'], 'copyrights') === FALSE &&
                                            strpos($content['contents'], '404 NOT FOUND') === FALSE &&
                                            strpos($content['contents'], '404 NOTFOUND') === FALSE &&
                                            strpos($content['contents'], 'التعليقات') === FALSE &&
                                            strpos($content['contents'], 'NOT FOUND') === FALSE
                                            
                                           ) { 
                                               if(strrpos($reversed_url,"alkhobartimes.com") === FALSE && 
                                            //      strrpos($reversed_url,"hassacom.com") === FALSE &&
                                               //   strrpos($reversed_url,"al-balad.net") === FALSE &&
                                                  strrpos($reversed_url,"sra7h.com") === FALSE &&
                                                  strrpos($reversed_url,"aljubailtoday.com.sa") === FALSE &&
                                                  strrpos($reversed_url,"arabi21.com") === FALSE &&
                                                  strrpos($reversed_url,"ajel.sa") === FALSE &&
                                                  strrpos($reversed_url,"almowaten.net") === FALSE
                                                  ) {  
                                                       if (strrpos($reversed_url,"argaam.com") !== FALSE) {
                                                           //no newline for .
                                                       }
                                                       else {          
                                                           preg_match('/(.*)\.([^.]*)$/', $content['contents'], $matches); //remove last dot
                                                           
                                                           //preg_match('\d+(?=\.(?:[^\d]|$))', $content['contents'], $matches1); //remove last dot
                                                            
                                                           $content['contents'] = str_replace(".", "<br />", @$matches[1]);     //replace all dots with new line 
                                                       }
                                               }
                                               else{
                                                   $content['contents'] = str_replace(".", "<br /><br />", $content['contents']);     //replace all dots with new line 
                                               }
                                                    
                                               if(strrpos($reversed_url,"kora.com") !== FALSE) {
                                                  $news['desc'] .= $content['contents'] . " <br /><br /> "; 
                                                  break;
                                               }
                                               elseif(strrpos($reversed_url,"tracksport.net") !== FALSE /*|| strrpos($reversed_url,"marebpress.net") !== FALSE*/) {
                                                   if (count($content) == 1) {
                                                       $desc .= $content['contents'] . " <br /><br /> ";
                                                       break;
                                                   }
                                                   else {
                                                       if (strpos($content, 'يمكنك الآن الإضافة المباشرة للتعليقات، وعدد كبير من المميزات والخيارات المتاحة فقط للأعضاء') === FALSE) {
                                                            $desc .= $content['contents'] . " <br /><br /> ";
                                                            break;    
                                                       }
                                                       else{
                                                           if ($counter == 1){
                                                               $desc .= $content['contents'] . " <br /><br /> ";
                                                               break;
                                                           }
                                                       }
                                                       
                                                       $counter++;
                                                   }
                                               }   
                                               elseif(/*strrpos($reversed_url,"almotamar.net") !== FALSE ||*/ strrpos($reversed_url,"barca4ever.com") !== FALSE) {
                                                   if (is_array($content['contents'])) {
                                                       if ($counter == 2){
                                                           $desc .= $content['contents'] . " <br /><br /> ";
                                                           break;
                                                       }
                                                       $counter++;
                                                   }
                                                   else{
                                                       $desc .= $content['contents'] . " <br /><br /> "; 
                                                   }
                                               }    
                                               elseif(strrpos($reversed_url,"shorouqoman.com") !== FALSE) {
                                                  if ($counter == 0){  
                                                       $counter++; 
                                                       continue;
                                                  }
                                                  else{  
                                                      $desc .= $content['contents'] . " <br /><br /> ";
                                                  }
                                                   
                                                  $counter++;
                                               }
                                              /* else if (strpos($reversed_url, "aljazeera.net") !== FALSE) {
                                                    $desc .= $content['contents'] . " <br /><br /> ";
                                                    
                                                    if ($counter == 6) break;
                                                    
                                                    $counter++;
                                               }  */
                                               else if (strpos($reversed_url, "yemen-press.net") !== FALSE || 
                                                        strpos($reversed_url, "ajmanpolice.gov") !== FALSE ||
                                                        //strpos($reversed_url, "alaraby.co.uk") !== FALSE ||
                                                        strpos($reversed_url, "alsumaria.tv") !== FALSE
                                                        ) { 
                                                   if ($counter == 0){   //  pr($content);    
                                                       $desc .= $content['contents'] . " <br /><br /> ";
                                                       break;
                                                   }
                                                   $counter++;
                                               }
                                               else if (strpos($reversed_url, "almayadeen.net") !== FALSE) { 
                                                  /*  if (strpos($reversed_url, "/news/") !== FALSE) {  
                                                        $desc .= $content['contents'] . " <br /><br /> ";  
                                                    }
                                                    else{
                                                       $desc = "";
                                                    }  */
                                               }
                                               else if (strpos($reversed_url, "24.ae") !== FALSE) { 
                                                   if ($counter == 0){   //  pr($content);    
                                                       $desc .= $content['contents'] . " <br /><br /> ";
                                                       break;
                                                   }
                                                   $counter++;
                                               }
                                               else if (strpos($reversed_url, "kaahe.org/ar/index") !== FALSE) { 
                                                    if (
                                                         strpos($content['contents'], "SOURCES:") !== FALSE ||
                                                         strpos($content['contents'], "Copyright") !== FALSE ||
                                                         strpos($content['contents'], "للتحقق. HONcode نحن نلتزم بمبادئ ميثاق") !== FALSE ||
                                                         strpos($content['contents'], "مت الترجمة بواسطة الفريق العلمي لموسوعة") !== FALSE 
                                                       )
                                                       {
                                                           continue;
                                                       }
                                                       else{
                                                           $desc .= $content['contents'] . " <br /><br /> "; 
                                                       }
                                               }
                                               /*else if (strpos($reversed_url, "felesteen.ps") !== FALSE) { 
                                                   if ($counter == 3){   //  pr($content);    
                                                       $desc .= $content['contents'] . " <br /><br /> ";
                                                       break;
                                                   }
                                                   $counter++;
                                               }  */
                                               else if (strpos($reversed_url, "asir.net") !== FALSE){
                                                   if ($counter == 2){   //  pr($content);    
                                                       $desc .= $content['contents'] . " <br /><br /> ";
                                                       break;
                                                   }
                                                   $counter++;
                                               }
                                               else if (strpos($reversed_url, "adpolice.gov.ae") !== FALSE){
                                                  // echo(' aymaaaaaaaaaaaaaaaaaaaaaaaaaaaaaan');
                                                   if ($counter == 0){  // echo(' ahmaddddddddddddddddddddddd');    
                                                       continue;        
                                                   }
                                                   else{ //echo(' ashraaaaaaaaaaaaf');
                                                       $desc .= $content['contents'] . " <br /><br /> ";
                                                   }
                                                   $counter++;
                                               }
                                               else if (strpos($reversed_url, "goal.com") !== FALSE || 
                                                        strpos($reversed_url, "dw.de") !== FALSE ||   
                                                        strpos($reversed_url, "dw.com") !== FALSE ||   
                                                        strpos($reversed_url, "alraimedia.com") !== FALSE ||
                                                        strpos($reversed_url, "twasul.info") !== FALSE ||
                                                        strpos($reversed_url, "alarab.qa") !== FALSE ||
                                                        strpos($reversed_url, "ashorooq.net") !== FALSE ||
                                                        strpos($reversed_url, "ounousa.com") !== FALSE ||
                                                        strpos($reversed_url, "wafa.com.sa") !== FALSE ||
                                                        strpos($reversed_url, "wikise7a.com") !== FALSE ||
                                                        strpos($reversed_url, "sayidaty.net") !== FALSE ||
                                                        strpos($reversed_url, "hihi2.com") !== FALSE ||
                                                        strpos($reversed_url, "fashion4arab.com") !== FALSE ||
                                                        strpos($reversed_url, "arabitechnomedia.com") !== FALSE ||
                                                        strpos($reversed_url, "snobonline.net") !== FALSE ||
                                                        strpos($reversed_url, "wonews.net") !== FALSE ||
                                                        strpos($reversed_url, "lahamag.com") !== FALSE ||
                                                        strpos($reversed_url, "steelbeauty.net") !== FALSE ||
                                                        strpos($reversed_url, "hawaaworld.com") !== FALSE ||
                                                        strpos($reversed_url, "almesryoon.com") !== FALSE ||
                                                        strpos($reversed_url, "ardroid.com") !== FALSE ||
                                                        strpos($reversed_url, "arabi21.com") !== FALSE ||
                                                        strpos($reversed_url, "manchestercityfc.ae") !== FALSE ||
                                                        strpos($reversed_url, "android4ar.com") !== FALSE ||
                                                        strpos($reversed_url, "arabhardware.net") !== FALSE ||
                                                        strpos($reversed_url, "arabapps.org") !== FALSE ||
                                                        strpos($reversed_url, "euronews.com") !== FALSE ||
                                                        strpos($reversed_url, "hashtagarabi.com") !== FALSE ||
                                                        strpos($reversed_url, "fcbarcelona.com") !== FALSE ||
                                                        strpos($reversed_url, "th3professional.com") !== FALSE ||
                                                        strpos($reversed_url, "hyperstage.net") !== FALSE ||
                                                        strpos($reversed_url, "techplus.me") !== FALSE ||
                                                        strpos($reversed_url, "alnilin.com") !== FALSE ||
                                                        strpos($reversed_url, "beinsports.com") !== FALSE ||
                                                        strpos($reversed_url, "arabic.sport360.com") !== FALSE ||
                                                        strpos($reversed_url, "marebpress.net") !== FALSE ||
                                                        strpos($reversed_url, "doniatech.com") !== FALSE ||
                                                        strpos($reversed_url, "almogaz.com") !== FALSE ||
                                                        strpos($reversed_url, "alarabiya.net") !== FALSE ||
                                                        strpos($reversed_url, "kooora.com") !== FALSE ||
                                                        strpos($reversed_url, "kooora2.com") !== FALSE ||
                                                        strpos($reversed_url, "arriyadiyah.com") !== FALSE ||
                                                        strpos($reversed_url, "bna.bh") !== FALSE ||
                                                        strpos($reversed_url, "echoroukonline.com") !== FALSE ||
                                                        strpos($reversed_url, "al-mashhad.com") !== FALSE ||
                                                        strpos($reversed_url, "akhbar-alkhaleej.com") !== FALSE ||
                                                        strpos($reversed_url, "linkis.com") !== FALSE ||
                                                        strpos($reversed_url, "yen-news.com") !== FALSE ||
                                                        strpos($reversed_url, "sudanmotion.com") !== FALSE ||
                                                        strpos($reversed_url, "bahrainalyoum.net") !== FALSE ||
                                                        strpos($reversed_url, "libyanow.net.ly") !== FALSE ||
                                                        strpos($reversed_url, "akhbarlibya24.net") !== FALSE ||
                                                        strpos($reversed_url, "shorouknews.com") !== FALSE ||
                                                        strpos($reversed_url, "assafir.com") !== FALSE ||
                                                        strpos($reversed_url, "alwatannews.net") !== FALSE ||
                                                        strpos($reversed_url, "alwefaq.net") !== FALSE ||
                                                        strpos($reversed_url, "lana-news.ly") !== FALSE ||
                                                        strpos($reversed_url, "tuniscope.com") !== FALSE ||
                                                        strpos($reversed_url, "lebanondebate.com") !== FALSE ||
                                                        strpos($reversed_url, "hespress.com") !== FALSE ||
                                                        strpos($reversed_url, "yemen-press.com") !== FALSE ||
                                                        strpos($reversed_url, "aljoumhouria.com") !== FALSE ||
                                                        strpos($reversed_url, "makkahnewspaper.com") !== FALSE ||
                                                        strpos($reversed_url, "hibapress.com") !== FALSE ||
                                                        strpos($reversed_url, "assabeel.net") !== FALSE ||
                                                        strpos($reversed_url, "tounesnews.com") !== FALSE ||
                                                        strpos($reversed_url, "anaween.com") !== FALSE ||
                                                        strpos($reversed_url, "bahrainmirror.no-ip.info") !== FALSE ||
                                                        strpos($reversed_url, "suhailnews.blogspot.com") !== FALSE ||
                                                        strpos($reversed_url, "annahar.com") !== FALSE ||
                                                        strpos($reversed_url, "alkhabarnow.net") !== FALSE ||
                                                        strpos($reversed_url, "paltoday.ps") !== FALSE ||
                                                        strpos($reversed_url, "assawsana.com") !== FALSE ||
                                                        strpos($reversed_url, "ammonnews.net") !== FALSE ||
                                                        strpos($reversed_url, "aliraqnews.com") !== FALSE ||
                                                        strpos($reversed_url, "alquds.com") !== FALSE ||
                                                        strpos($reversed_url, "yemenat.net") !== FALSE ||
                                                        strpos($reversed_url, "kuwaitnews.com") !== FALSE ||
                                                        strpos($reversed_url, "fath-news.com") !== FALSE ||
                                                        strpos($reversed_url, "anbaaonline.com") !== FALSE ||
                                                        strpos($reversed_url, "qudspress.com") !== FALSE ||
                                                        strpos($reversed_url, "alhasela.com") !== FALSE ||
                                                        strpos($reversed_url, "saidaonline.com") !== FALSE ||
                                                        strpos($reversed_url, "palsawa.com") !== FALSE ||
                                                        strpos($reversed_url, "hattpost.com") !== FALSE ||
                                                        strpos($reversed_url, "azzaman.com") !== FALSE ||
                                                        strpos($reversed_url, "adhamiyahnews.com") !== FALSE ||
                                                        strpos($reversed_url, "rudaw.net") !== FALSE ||
                                                        strpos($reversed_url, "ahram.org.eg") !== FALSE ||
                                                        strpos($reversed_url, "alsawt.net") !== FALSE ||
                                                        strpos($reversed_url, "q8news.com") !== FALSE ||
                                                        strpos($reversed_url, "alhayat.com") !== FALSE ||
                                                        strpos($reversed_url, "masralarabia.com") !== FALSE ||
                                                        strpos($reversed_url, "watn-news.com") !== FALSE ||
                                                        strpos($reversed_url, "ng4a.com") !== FALSE ||
                                                        strpos($reversed_url, "aldostornews.com") !== FALSE ||
                                                        strpos($reversed_url, "albawabhnews.com") !== FALSE ||
                                                        strpos($reversed_url, "al-balad.net") !== FALSE ||
                                                        strpos($reversed_url, "alhurra.com") !== FALSE ||
                                                        strpos($reversed_url, "alquds.co.uk") !== FALSE ||
                                                        strpos($reversed_url, "al-sharq.com") !== FALSE ||
                                                        strpos($reversed_url, "skynewsarabia.com") !== FALSE ||
                                                        strpos($reversed_url, "almayadeen.net") !== FALSE ||
                                                        strpos($reversed_url, "arn.ps") !== FALSE ||
                                                        strpos($reversed_url, "3seer.net") !== FALSE ||
                                                        strpos($reversed_url, "akherkhabaronline.com") !== FALSE ||
                                                        strpos($reversed_url, "pal24.net") !== FALSE ||
                                                        strpos($reversed_url, "middle-east-online.com") !== FALSE ||
                                                        strpos($reversed_url, "alaraby.co.uk") !== FALSE ||
                                                        strpos($reversed_url, "elbilad.net") !== FALSE ||
                                                        strpos($reversed_url, "alborsanews.com") !== FALSE ||
                                                        strpos($reversed_url, "omannews.gov.om") !== FALSE ||
                                                        strpos($reversed_url, "lebanonfiles.com") !== FALSE ||
                                                        strpos($reversed_url, "felesteen.ps") !== FALSE ||
                                                        strpos($reversed_url, "safa.ps") !== FALSE ||
                                                        strpos($reversed_url, "alkhaleejonline.net") !== FALSE ||
                                                        strpos($reversed_url, "layalina.com") !== FALSE ||
                                                        strpos($reversed_url, "elfagr.org") !== FALSE ||
                                                        strpos($reversed_url, "al-akhbar.com") !== FALSE ||
                                                        strpos($reversed_url, "arabic.cnn.com") !== FALSE ||
                                                        strpos($reversed_url, "akhbarak.net") !== FALSE ||
                                                        strpos($reversed_url, "qna.org.qa") !== FALSE ||
                                                        //strpos($reversed_url, "atyabtabkha.3a2ilati.com") !== FALSE ||
                                                        strpos($reversed_url, "autosearch.me") !== FALSE ||
                                                        strpos($reversed_url, "cdn.alkass.net") !== FALSE ||
                                                        strpos($reversed_url, "alkhabarsport.com") !== FALSE ||
                                                        strpos($reversed_url, "alkhabarkw.com") !== FALSE ||
                                                        strpos($reversed_url, "dostor.org") !== FALSE ||
                                                        strpos($reversed_url, "france24.com") !== FALSE ||
                                                        strpos($reversed_url, "almustaqbal.com") !== FALSE ||
                                                        strpos($reversed_url, "zamanarabic.com") !== FALSE ||
                                                        strpos($reversed_url, "alwasat.com.kw") !== FALSE ||
                                                        strpos($reversed_url, "almotamar.net") !== FALSE ||
                                                        strpos($reversed_url, "nas.sa") !== FALSE ||
                                                        strpos($reversed_url, "youm7.com") !== FALSE ||
                                                        strpos($reversed_url, "arabsturbo.com") !== FALSE ||
                                                        strpos($reversed_url, "3alyoum.com") !== FALSE ||
                                                        strpos($reversed_url, "n1t1.com") !== FALSE ||
                                                        strpos($reversed_url, "elfann.com") !== FALSE ||
                                                        strpos($reversed_url, "q8ping.com") !== FALSE ||
                                                        strpos($reversed_url, "arab4x4.com") !== FALSE ||
                                                        strpos($reversed_url, "nok6a.net") !== FALSE ||
                                                        strpos($reversed_url, "shahiya.com") !== FALSE ||
                                                        strpos($reversed_url, "qabaq.com") !== FALSE ||
                                                        strpos($reversed_url, "arbdroid.com") !== FALSE ||
                                                        //strpos($reversed_url, "manalonline.com") !== FALSE ||
                                                        //strpos($reversed_url, "fatafeat.com") !== FALSE ||
                                                        strpos($reversed_url, "yumyume.com") !== FALSE ||
                                                        strpos($reversed_url, "buyemen.com") !== FALSE ||
                                                        strpos($reversed_url, "forbesmiddleeast.com") !== FALSE ||
                                                        strpos($reversed_url, "mubasher.info") !== FALSE ||
                                                        strpos($reversed_url, "euronews") !== FALSE ||
                                                        strpos($reversed_url, "alwasatnews.com") !== FALSE ||
                                                        strpos($reversed_url, "sea7htravel.com") !== FALSE ||
                                                        strpos($reversed_url, "akhbarelyom.com") !== FALSE ||
                                                        strpos($reversed_url, "olympic.qa") !== FALSE ||
                                                        strpos($reversed_url, "anazahra.com") !== FALSE ||
                                                        strpos($reversed_url, "goodykitchen.com") !== FALSE ||
                                                        strpos($reversed_url, "android-time.com") !== FALSE ||
                                                        strpos($reversed_url, "hiamag.com") !== FALSE ||
                                                        strpos($reversed_url, "masrawy.com") !== FALSE ||
                                                        strpos($reversed_url, "al-gornal.com") !== FALSE ||
                                                        strpos($reversed_url, "alsopar.com") !== FALSE ||
                                                        strpos($reversed_url, "alittihad.ae") !== FALSE ||
                                                        strpos($reversed_url, "alayam.com") !== FALSE ||
                                                        strpos($reversed_url, "elwatannews.com") !== FALSE ||
                                                        strpos($reversed_url, "zamalekfans.com") !== FALSE ||
                                                        strpos($reversed_url, "ismailyonline.com") !== FALSE ||
                                                        strpos($reversed_url, "alsawtnews.cc") !== FALSE ||
                                                        strpos($reversed_url, "sport.ahram.org") !== FALSE ||
                                                        strpos($reversed_url, "mbc.net") !== FALSE ||
                                                        strpos($reversed_url, "al-jazirah.com") !== FALSE ||
                                                        strpos($reversed_url, "sabqq.org") !== FALSE ||
                                                        strpos($reversed_url, "alriyadh.com") !== FALSE ||
                                                        strpos($reversed_url, "filgoal.com") !== FALSE ||
                                                        strpos($reversed_url, "alriadey.com") !== FALSE ||
                                                        strpos($reversed_url, "ittinews.net") !== FALSE ||
                                                        strpos($reversed_url, "alahlyegypt.com") !== FALSE ||
                                                        strpos($reversed_url, "tracksport.net") !== FALSE ||
                                                        strpos($reversed_url, "realmadrid.com") !== FALSE ||
                                                        strpos($reversed_url, "elheddaf.com") !== FALSE ||
                                                        strpos($reversed_url, "goalna.com") !== FALSE ||
                                                        strpos($reversed_url, "ar.beinsports.net") !== FALSE ||
                                                        strpos($reversed_url, "GalerieArtciles") !== FALSE ||
                                                        strpos($reversed_url, "elaph.com") !== FALSE ||
                                                        strpos($reversed_url, "hilalcom.net") !== FALSE ||
                                                        strpos($reversed_url, "tayyar.org") !== FALSE ||
                                                        strpos($reversed_url, "Elaph") !== FALSE ||
                                                        strpos($reversed_url, "elaph") !== FALSE ||
                                                        strpos($reversed_url, "yallakora.com") !== FALSE ||
                                                        strpos($reversed_url, "acakuw.com") !== FALSE ||
                                                        strpos($reversed_url, "fifa.com") !== FALSE ||
                                                        strpos($reversed_url, "almashhad.net") !== FALSE ||
                                                        strpos($reversed_url, "alrayalaam.com") !== FALSE ||
                                                        strpos($reversed_url, "aljazeera.net") !== FALSE ||
                                                        strpos($reversed_url, "almowaten.net") !== FALSE ||
                                                        strpos($reversed_url, "kuna.net.kw") !== FALSE ||
                                                        strpos($reversed_url, "reqaba.com") !== FALSE ||
                                                        strpos($reversed_url, "alshamiya-news.com") !== FALSE ||
                                                        strpos($reversed_url, "oleeh.com") !== FALSE ||
                                                        strpos($reversed_url, "annaharkw.com") !== FALSE ||
                                                        strpos($reversed_url, "egyptiannews.net") !== FALSE ||
                                                        strpos($reversed_url, "alkoutnews.net") !== FALSE ||
                                                        strpos($reversed_url, "alkuwaityah.com") !== FALSE ||
                                                        strpos($reversed_url, "ajialq8.com") !== FALSE ||
                                                        strpos($reversed_url, "Alkuwaityah.com") !== FALSE ||
                                                        strpos($reversed_url, "dasmannews.com") !== FALSE ||
                                                        strpos($reversed_url, "alhakea.com") !== FALSE ||
                                                        strpos($reversed_url, "chouftv.ma") !== FALSE ||
                                                        strpos($reversed_url, "altaleea.com") !== FALSE ||
                                                        strpos($reversed_url, "arabesque.tn") !== FALSE ||
                                                        strpos($reversed_url, "tounessna.info") !== FALSE ||
                                                        strpos($reversed_url, "al-seyassah.com") !== FALSE ||
                                                        strpos($reversed_url, "alanba.com.kw") !== FALSE ||
                                                      //  strpos($reversed_url, "moheet.com") !== FALSE ||
                                                        strpos($reversed_url, "babnet.net") !== FALSE ||
                                                        strpos($reversed_url, "ennaharonline.com") !== FALSE ||
                                                        strpos($reversed_url, "alyaoum24.com") !== FALSE ||
                                                        strpos($reversed_url, "zoomtunisia.tn") !== FALSE ||
                                                        strpos($reversed_url, "moroccoeyes") !== FALSE ||
                                                        strpos($reversed_url, "alikhbaria.com") !== FALSE ||
                                                        strpos($reversed_url, "filwajiha.com") !== FALSE ||
                                                        strpos($reversed_url, "le360.ma") !== FALSE ||
                                                        strpos($reversed_url, "attounissia.com.tn") !== FALSE ||
                                                        strpos($reversed_url, "mmaqara2t.com") !== FALSE ||
                                                        strpos($reversed_url, "annaharnews.net") !== FALSE ||
                                                        strpos($reversed_url, "atheer.om") !== FALSE ||
                                                        strpos($reversed_url, "alwatan.com") !== FALSE ||
                                                        strpos($reversed_url, "tnntunisia.com") !== FALSE ||
                                                        strpos($reversed_url, "tunisien.tn") !== FALSE ||
                                                        strpos($reversed_url, "otv.com.lb") !== FALSE ||
                                                        strpos($reversed_url, "almanar.com.lb") !== FALSE ||
                                                        strpos($reversed_url, "shabiba.com") !== FALSE ||
                                                        strpos($reversed_url, "omandaily.om") !== FALSE ||
                                                        strpos($reversed_url, "al-watan.com") !== FALSE ||
                                                        strpos($reversed_url, "moe.gov.qa") !== FALSE ||
                                                        strpos($reversed_url, "futuretvnetwork.com") !== FALSE ||
                                                        strpos($reversed_url, "lbcgroup.tv") !== FALSE ||
                                                        strpos($reversed_url, "o-t.tv") !== FALSE ||
                                                        strpos($reversed_url, "hroobnews.com") !== FALSE ||
                                                        strpos($reversed_url, "sahelmaten.com") !== FALSE ||
                                                        strpos($reversed_url, "basnews.com") !== FALSE ||
                                                        strpos($reversed_url, "nna-leb.gov.lb") !== FALSE ||
                                                        strpos($reversed_url, "orient-news.net") !== FALSE ||
                                                        strpos($reversed_url, "iraqdirectory.com") !== FALSE ||
                                                        strpos($reversed_url, "alnoornews.net") !== FALSE ||
                                                        strpos($reversed_url, "alrafidain.org") !== FALSE ||
                                                        strpos($reversed_url, "lebwindow.net") !== FALSE ||
                                                        strpos($reversed_url, "etilaf.org") !== FALSE ||
                                                        strpos($reversed_url, "alliraqnews.com") !== FALSE ||
                                                        strpos($reversed_url, "syrianow.sy") !== FALSE ||
                                                        strpos($reversed_url, "alroeya.ae") !== FALSE ||
                                                        strpos($reversed_url, "albayan.ae") !== FALSE ||
                                                        strpos($reversed_url, "el-balad.com") !== FALSE ||
                                                        strpos($reversed_url, "yanair.net") !== FALSE ||
                                                        strpos($reversed_url, "argaam.com") !== FALSE ||
                                                        strpos($reversed_url, "alforatnews.com") !== FALSE ||
                                                        strpos($reversed_url, "maqar.com") !== FALSE ||
                                                        strpos($reversed_url, "elshaab.org") !== FALSE ||
                                                        strpos($reversed_url, "alamalmal.net") !== FALSE ||
                                                        strpos($reversed_url, "7iber.com") !== FALSE ||
                                                        strpos($reversed_url, "wam.ae") !== FALSE ||
                                                        strpos($reversed_url, "jn-news.com") !== FALSE ||
                                                        strpos($reversed_url, "jo24.net") !== FALSE ||
                                                        strpos($reversed_url, "alarabalyawm.net") !== FALSE ||
                                                        strpos($reversed_url, "royanews.tv") !== FALSE ||
                                                        strpos($reversed_url, "wikise7a") !== FALSE ||
                                                        strpos($reversed_url, "lahaonline.com") !== FALSE ||
                                                        strpos($reversed_url, "klmty.net") !== FALSE ||
                                                        strpos($reversed_url, "3eesho.com") !== FALSE ||
                                                        strpos($reversed_url, "addustour.com") !== FALSE ||
                                                        strpos($reversed_url, "kaahe.org") !== FALSE ||
                                                        strpos($reversed_url, "rotanamags.net") !== FALSE ||
                                                        strpos($reversed_url, "almaghribtoday.net") !== FALSE ||
                                                        strpos($reversed_url, "almasryalyoum.com") !== FALSE ||
                                                        strpos($reversed_url, "almaydan2.net") !== FALSE ||
                                                        strpos($reversed_url, "almuraba.net") !== FALSE ||
                                                        strpos($reversed_url, "dailymedicalinfo.com") !== FALSE ||
                                                        strpos($reversed_url, "aldawadmi.net") !== FALSE ||
                                                        strpos($reversed_url, "hafralbaten.com") !== FALSE ||
                                                        strpos($reversed_url, "naseej.net") !== FALSE ||
                                                        strpos($reversed_url, "arabi21.com") !== FALSE ||
                                                        strpos($reversed_url, "newsqassim.com") !== FALSE ||
                                                        strpos($reversed_url, "ham-24.com") !== FALSE ||
                                                        strpos($reversed_url, "spa.gov.sa") !== FALSE ||
                                                        strpos($reversed_url, "nwafecom.net") !== FALSE ||
                                                        strpos($reversed_url, "fajr.sa") !== FALSE ||
                                                        strpos($reversed_url, "adwaalwatan.com") !== FALSE ||
                                                        strpos($reversed_url, "aljubailtoday.com.sa") !== FALSE ||
                                                        strpos($reversed_url, "alqabas.com.kw") !== FALSE ||
                                                        strpos($reversed_url, "ajel.sa") !== FALSE ||
                                                        strpos($reversed_url, "mini-news.net") !== FALSE ||
                                                        strpos($reversed_url, "aljouf-news.com") !== FALSE ||
                                                        strpos($reversed_url, "almjardh.com") !== FALSE ||
                                                        strpos($reversed_url, "cma.org.sa") !== FALSE ||
                                                        strpos($reversed_url, "rasdnews.net") !== FALSE ||
                                                        strpos($reversed_url, "anbaanews.com") !== FALSE ||
                                                        strpos($reversed_url, "tabuk-news.com") !== FALSE ||
                                                        strpos($reversed_url, "zahran.org") !== FALSE ||
                                                        strpos($reversed_url, "alkhaleejaffairs.org") !== FALSE ||
                                                        strpos($reversed_url, "rsssd.com") !== FALSE ||
                                                        strpos($reversed_url, "roaanews.net") !== FALSE ||
                                                        strpos($reversed_url, "hassacom.com") !== FALSE ||
                                                        strpos($reversed_url, "arjja.com") !== FALSE ||
                                                        strpos($reversed_url, "raialyoum.com") !== FALSE ||
                                                        strpos($reversed_url, "asir.com") !== FALSE ||
                                                        strpos($reversed_url, "kharjhome.com") !== FALSE ||
                                                        strpos($reversed_url, "alsharq.net.sa") !== FALSE ||
                                                        strpos($reversed_url, "baareq.com.sa") !== FALSE ||
                                                        strpos($reversed_url, "freeswcc.com") !== FALSE ||
                                                        strpos($reversed_url, "moh.gov.sa") !== FALSE ||
                                                        strpos($reversed_url, "ar.yabiladies.com") !== FALSE ||
                                                        strpos($reversed_url, "paltimes.net") !== FALSE ||
                                                        strpos($reversed_url, "saso.gov.sa") !== FALSE ||
                                                        strpos($reversed_url, "electrony.net") !== FALSE ||
                                                        strpos($reversed_url, "electronynet") !== FALSE ||
                                                        strpos($reversed_url, "aljadeed.tv") !== FALSE ||
                                                        strpos($reversed_url, "palinfo.com") !== FALSE ||
                                                        strpos($reversed_url, "alwasat.ly") !== FALSE ||
                                                        strpos($reversed_url, "sabq.org") !== FALSE ||
                                                        strpos($reversed_url, "reuters.com") !== FALSE ||
                                                        strpos($reversed_url, "alrakoba.net") !== FALSE ||
                                                        strpos($reversed_url, "alkhaleej.ae") !== FALSE ||
                                                        strpos($reversed_url, "alshahedkw.com") !== FALSE ||
                                                        strpos($reversed_url, "al-madina.com") !== FALSE ||
                                                        strpos($reversed_url, "aawsat.com") !== FALSE ||
                                                        strpos($reversed_url, "naba.ps") !== FALSE ||
                                                        strpos($reversed_url, "charlesayoub.com") !== FALSE ||
                                                        strpos($reversed_url, "aleqt.com") !== FALSE ||
                                                        strpos($reversed_url, "dotmsr.com") !== FALSE ||
                                                        strpos($reversed_url, "sabr.cc") !== FALSE ||
                                                        strpos($reversed_url, "aljarida.com") !== FALSE ||
                                                        strpos($reversed_url, "alwatan.kuwait.tt") !== FALSE ||
                                                        strpos($reversed_url, "14march.org") !== FALSE
                                                        ) { 
                                                   if ($counter == 0){   //  pr($content);    
                                                       $desc .= $content['contents'] . " <br /><br /> ";  
                                                       break;
                                                   }
                                                   
                                                   $counter++;
                                               }   
                                                  
                                               else if ( strpos($reversed_url, 'alalam.ir') !== FALSE) {    
                                                   if ($counter == 2) {
                                                       if (strpos($content['contents'], 'Shadowbox.init') !== FALSE) {
                                                           $no_text1 = true;
                                                           $counter++;  
                                                           
                                                           continue;
                                                       }
                                                   }  
                                                   elseif ($counter == 3) {
                                                       if (strpos($content['contents'], 'Shadowbox.init') !== FALSE) {
                                                           $no_text = true;
                                                           $counter++;  
                                                           
                                                           continue;
                                                       }
                                                   }  
                                                   else if ($counter == 3 && isset($no_text1)) {
                                                       $desc = '';
                                                       $desc .= $content['contents'] . " <br /><br /> ";
                                                       break;
                                                   }
                                                   else if ($counter == 4 && isset($no_text)) {
                                                       $desc = '';
                                                       $desc .= $content['contents'] . " <br /><br /> ";
                                                       break;
                                                   }
                                                   else{
                                                       $desc .= $content['contents'] . " <br /><br /> ";
                                                   }
                                                   
                                                   $counter++;
                                               }
                                               else{
                                                   $news['desc'] .= $content['contents'] . " <br /><br /> "; 
                                               }
                                        }
                                        else{
                                            $news['desc'] = '404';
                                        }
                                    }
                                }
                                else{      // pr($data);
                                    //$news['desc'] = $data['paragraph']['contents'];
                                    $news['desc'] = $news['title'];
                                }
                            }
                                     // pr($data['images']);
                            if (!isset($tweet->extended_entities->media[0]->media_url)) {
                                $news['image'] = isset($data['images'][0]) ? $data['images'][0] : "";
                            }
                            else if($tweet->extended_entities->media[0]->media_url == ""){
                                $news['image'] = isset($data['images'][0]) ? $data['images'][0] : "";     
                            }
                        }
                        else{   //news is already exist
                            $news['desc'] = 'already_exists';
                        }
  
                    }
                    else{
                        $news['desc'] = "url not set";
                    }
                   // echo('url: '.$url[0]->expanded_url . '<br />');
                    //pr($data);
                }
            }
            else{
                $news['desc'] = "url not set";
            }
            
            echo('<br />desc:' . $desc); //exit;
            echo('$reversed_url_1: ' . $reversed_url . '<br />');
            //echo($title . ' -> ' . $image . ' -> ' . $desc .'<br />');
            
            if (strpos($reversed_url, "http://www.alwatanvoice.com/common/error.html") === FALSE &&
                strpos($reversed_url, "http://www.alwatanvoice.com/arabic/index.html") === FALSE && 
                strpos($url_decode, "http://www.youm7.com/أخبار-عاجلة-65") === FALSE &&
                strpos($reversed_url, "video.alwatanvoice.com") === FALSE && 
                strpos($reversed_url, "dropbox.com") === FALSE 
            ) {
                 if ($news['desc'] != 'already_exists') {
                     save_news($news, $data, $reversed_url);
                 }
            }
            
            //exit;
         } 
    }    
} 

function get_category_id_by_twitter_user_id($twitter_user_id){
    global $conn; 
    $query = "select category_id cid from rss_news where twitter_user_id = '$twitter_user_id' and type = 1";
    echo($query . '<br />');
    $res = $conn->db_query($query);
    
    $data = array();
    
    while($row = $conn->fetch_assoc($res)){
         $data[] = $row; 
    }
    
    return $data; 
}

function get_sources($cat_id = '', $connection = '', $start = '', $offset = '') {
    global $conn;
    /*$query = "select category_id, link, categories.parent
                from categories
                inner join rss_news on categories.id = rss_news.category_id
                where categories.parent = '$cat_id'";*/
    
    $start = ($start*$offset);
    
    $time = time();
                
    /*$query = "select tweets.tweet_id, tweet_text text, user_id, url
                from tweets 
                left join tweet_urls on tweet_urls.tweet_id = tweets.tweet_id 
                where (LEFT(tweet_text , 2) <> 'RT' and LEFT(tweet_text , 1) <> '@') and user_id = 1026662552 
                order by created_at desc 
                limit $start, $offset";    //twitter  */     
                
    $query = "select tweets.tweet_id, tweet_text text, user_id, url, news_image
                from tweets 
                left join tweet_urls on tweet_urls.tweet_id = tweets.tweet_id 
                where (LEFT(tweet_text , 2) <> 'RT' and LEFT(tweet_text , 1) <> '@')
                order by created_at desc
                limit $start, $offset";    //twitter   
                   
                  // echo($query);   exit;
    $res = $conn->db_query($query);
    $desc = '';
    $news['desc'] = '';     // $cc = 1;
               //  echo('9999999999');
    //while($row = $conn->db_fetch_array($res)) { //echo($row);exit;   
    while($row = $conn->fetch_assoc($res)) { //echo($row);exit;   
        $news['desc'] = '';   
        $news['image'] = '';                       
   // echo('88888888888888');
         //$tweets = $connection->get($row['link']);    //parse twitter link
         $categories_ids = get_category_id_by_twitter_user_id($row['user_id']);
                       //echo($cc++ . '-cat id: '.$news['cid'].'<br />');
                       
             //echo($news['cid']);   continue;     
         //$news['cid'] = $row['category_id'];
                        //   pr($tweets);  exit;
                       // pr( json_encode($tweets)); exit;
               echo('<br />twitter user id: ' . $row['user_id'] . '<br />');        
                    //   pr($categories_ids); 
         if ($categories_ids != "") {
            
            foreach($categories_ids as $cat_id) {//like mdiet
                $news['cid'] = $cat_id['cid'];
               //  echo($news['cid']);   continue; 
                $news['title'] = $row['text'];
               // $news['alt_title'] = @$tweet->user->description;
                //$news['twitter_news_id'] = $row['tweet_id'] = 111111;
                $news['twitter_news_id'] = $row['tweet_id'];
                //$news['image'] = @$tweet->extended_entities->media[0]->media_url;
                //$url = @$tweet->entities->urls;
                
                //$news['url'] = $row['url'] = "http://www.kooora.com/default.aspx?n=429204";
                $news['url'] = $row['url'];
                $news['news_image'] = $row['news_image'];
                
                if (strpos($news['url'], "itunes.apple.com") !== FALSE) continue;
                if (strpos($news['url'], "play.google.com") !== FALSE) continue;
                if (strpos($news['url'], "pay-it.mobi") !== FALSE) continue;
                if (strpos($news['url'], ".mobi") !== FALSE) continue;
                if (strpos($news['url'], "unfollowers.com") !== FALSE) continue;
                
                if (strpos($news['url'], "mmaqara2t") !== FALSE) {
                    if (strpos($news['title'], "عروض خاصة") !== FALSE) continue;
                }
                
                if (strpos($news['url'], "mubasher.info") !== FALSE) {  
                    if (strpos($news['title'], "تحت الإنشاء") !== FALSE){   
                       continue;
                    }
                }
                
                $news['url'] = str_replace('amp;', '&', $news['url']);
                $news['url'] = str_replace('amp; ', '&', $news['url']);
                $news['url'] = str_replace(' amp;', '&', $news['url']);
                
                //$news['url'] = 'http://ow.ly/IRPcy';
                                         
                //$url[0]->expanded_url = 'http://ow.ly/IRPcy';
                
                $reversed_url = '';
                
                if (isset($news['url'])) {
                    $reversed_url = reverse_tinyurl(@$news['url']);
                    
                    if (strpos($reversed_url, "bit.ly") !== FALSE || 
                        strpos($reversed_url, "news.google.com") !== FALSE || 
                        strpos($reversed_url, "feedproxy.google.com") !== FALSE || 
                        strpos($reversed_url, "eel.la") !== FALSE || 
                        strpos($reversed_url, "alanba.com.kw") !== FALSE || 
                        strpos($reversed_url, "fb.me") !== FALSE || 
                        strpos($reversed_url, "t.co") !== FALSE || 
                        strpos($reversed_url, "goo.gl") !== FALSE || 
                        strpos($reversed_url, "trib.al") !== FALSE || 
                        strpos($reversed_url, "ow.ly") !== FALSE || 
                        strpos($reversed_url, "shar.es") !== FALSE){
                        $reversed_url = reverse_tinyurl($reversed_url);
                    }
                    
                } 
                
                $url_decode = urldecode($reversed_url);
                $data = '';
                
                      echo('<br />reversed xxxx: ' . $reversed_url . '<br />');
                      echo('<br />$url_decode xxxx: ' . $url_decode . '<br />');
                if ( isset($news['url']) && 
                     strpos($reversed_url, "http://www.alwatanvoice.com/common/error.html") === FALSE &&
                     strpos($url_decode, "http://www.youm7.com/أخبار-عاجلة-65") === FALSE &&
                     strpos($reversed_url, "http://www.alwatanvoice.com/arabic/index.html") === FALSE
                    ) {
                    if (strpos($reversed_url, 'fb.me') !== false || strpos($reversed_url, 'facebook.com') !== false) {
                        $news['desc'] = "go to fb";
                        
                        if($row['user_id'] == 373257377) {//aqsa tv
                            $news['image'] = "aqsatv@2x.jpg";
                        }
                        else{
                            $news['image'] = "";
                        }
                                
                    }  
                    else if (strpos($reversed_url, 'media.5d3a.com') !== false /*|| strpos($reversed_url, 'wikise7a.com') !== false*/) {
                        $news['desc'] = "go to fb";
                    }
                    else if (strpos($reversed_url, 'altibbi.com') !== false) {
                        $news['desc'] = "go to fb";
                    }
                    else if (strpos($reversed_url, 'alg360.com') !== false) {
                        $news['desc'] = "go to fb";
                    }
                    else if (strpos($reversed_url, '/vb/') !== false) {
                        $news['desc'] = "go to fb";
                    } 
                    else if (strpos($reversed_url, 'dmi.ae/samadubai') !== false) {
                        $news['desc'] = "go to fb";
                    }
                    else if (strpos($reversed_url, 'instagram.com') !== false) {
                        $news['desc'] = "go to fb";
                    }
                    else if (strpos($reversed_url, 'youtube.com') !== false || strpos($reversed_url, 'youtu.be') !== false) {
                        $news['desc'] = "go to youtube";
                    } 
                    else if (strpos($reversed_url, 'dcndigital.ae') !== false) {
                        $news['desc'] = "go to fb";
                    }
                    else if (strpos($reversed_url, 'ittisport.com') !== false) {
                        $news['desc'] = "go to fb";
                    }
                    else if (strpos($reversed_url, 'zakatfund.gov.ae') !== false || strrpos($reversed_url,"zf.ae") !== FALSE) {
                        $news['desc'] = "go to fb";
                    }  
                    else if (strpos($reversed_url, '3eesho.com/articles/browse/category') !== false) {
                        $news['desc'] = "";
                    }
                    else if (strpos($reversed_url, 'fitnessyard.com/registration') !== false) {
                        $news['desc'] = "";
                    }
                    else if (strpos($reversed_url, 'fitnessyard.com/workout/exercise-directory') !== false) {
                        $news['desc'] = "";
                    }  
                    else if (strpos($reversed_url, 'audio.islamweb.net') !== false) {
                        $news['desc'] = "go to islamweb";
                    } 
                    else if (strpos($reversed_url, 'vine.co') !== false) {
                        $news['desc'] = "go to fb";
                    }
                    else if (strpos($reversed_url, '.pdf') !== false) {
                        $news['desc'] = "go to pdf";
                    }
                    else if (strpos($reversed_url, 'twitter.com') !== false && strpos($reversed_url, 'utm_source=twitter.com') === false) {
                        continue;
                    }
                    else{   
                        if ($news['url'] != "") {
                            
                            $twitte_count = check_if_twitter_news_exists($news['twitter_news_id']);
                            echo('<br />$twitte_count: ' . $twitte_count . '<br />');
                            $news['desc'] = '';
                            
                            if (!$twitte_count) {    // $news['url'] = 'http://www.kooora.com/default.aspx?n=429204'; 
                                $data = fetch($news['url']); 
                                      echo('----------------><br />');   pr($data);   //exit;
                                $news['image'] = @$data['images'][0];
                                
                              /*  if(strrpos($reversed_url,"naseej.net") !== FALSE){  
                                    $data['paragraph']['contents'] = $data['paragraph'][0];
                                } */
                                     //echo('---------------->');   pr($data);   exit; 
                                if ($data['title'] == 'أخبار عاجلة | اليوم السابع') continue;            
                                        //echo('<br />'.$url[0]->expanded_url.'<br />');  pr($data);   exit;  
                                    /*  if ($url[0]->expanded_url == 'http://ow.ly/GBQAd') */ //pr($data['paragraph']); 
                                    
                                if (isset($data['paragraph'])) {
                                    if (is_array($data['paragraph'])) {
                                        $counter = 0;
                                                       
                                        foreach($data['paragraph'] as $content) {   
                                          /*  if(strrpos($reversed_url,"naseej.net") !== FALSE){
                                                $content['contents'] = $content; 
                                            }*/
                                                   
                                            if (trim($content['contents']) != "" && 
                                                strpos($content['contents'], 'المعذرة ، لقد حدث خطأ ما') === FALSE &&
                                                strpos($content['contents'], 'الصفحة المطلوبة غير موجودة') === FALSE &&
                                                strpos($content['contents'], 'هذه الصفحة غير متاحة حالياً') === FALSE &&
                                                strpos($content['contents'], 'مميزات والخيارات المتاحة فقط للأعضاء') === FALSE &&
                                                strpos($content['contents'], 'لقد حدث خطأ في استخدامك لنظام التصفح') === FALSE &&
                                                strpos($content['contents'], '404اتصل بنا شروط الاستخدام عن الموقع خدمة الرسائل بوابة الشروق 2015 جميع الحقوق محفوظة') === FALSE &&
                                                strpos($content['contents'], '404 NOT FOUND') === FALSE &&
                                                strpos($content['contents'], 'getElementById') === FALSE &&
                                                strpos($content['contents'], 'addThumbnail') === FALSE &&
                                                strpos($content['contents'], 'TWTR.Widget') === FALSE &&
                                                strpos($content['contents'], 'Your browser will redirect to your requested content shortly') === FALSE &&
                                                strpos($content['contents'], 'Completing the CAPTCHA proves you are a human and gives') === FALSE &&
                                                //strpos($content['contents'], 'قنوات MBC 1 MBC') === FALSE &&
                                                strpos($content['contents'], 'Get Al Jaras Updates with the most') === FALSE &&
                                                strpos($content['contents'], 'موقع جريدة الأنباء') === FALSE &&
                                                strpos($content['contents'], 'عودة للرئيسية') === FALSE &&
                                                strpos($content['contents'], 'الأخبار العاجلةالأولىأول') === FALSE &&
                                                strpos($content['contents'], 'قم بالتسجيل') === FALSE && 
                                                strpos($content['contents'], 'التعليقات') === FALSE &&
                                                strpos($content['contents'], 'رقابة :: برلمانية') === FALSE &&
                                                strpos($content['contents'], 'رقابة::برلمانية') === FALSE &&
                                                strpos($content['contents'], 'لمزيد من المعلومات') === FALSE &&
                                                strpos($content['contents'], 'stLight.options') === FALSE &&
                                                strpos($content['contents'], 'المزيد من المعلومات') === FALSE &&
                                                strpos($content['contents'], 'الرئيسية سيارات مستعملة') === FALSE &&
                                                strpos($content['contents'], 'شارك اصدقاءك') === FALSE &&
                                                strpos($content['contents'], 'لم نجد هذه الصفحة') === FALSE &&
                                                strpos($content['contents'], 'السعودية الإمارات البحرين') === FALSE &&
                                                strpos($content['contents'], 'شارك أصدقاءك') === FALSE &&
                                                strpos($content['contents'], 'مطبوعات وصفات أطباق رئيسية') === FALSE &&
                                                strpos($content['contents'], 'يحدث الآن يحدث الآن أهم الأخبار') === FALSE &&
                                                strpos($content['contents'], 'ملفات تعريف الارتباط') === FALSE &&
                                                strpos($content['contents'], 'ملفات تعريف الإرتباط') === FALSE &&
                                                strpos($content['contents'], 'ملفات تعريف الأرتباط') === FALSE &&
                                                strpos($content['contents'], 'مستقلة رقابة') === FALSE &&
                                                strpos($content['contents'], 'مستقلة رقابة ::') === FALSE &&
                                                strpos($content['contents'], 'مستقلة رقابة') === FALSE &&
                                                strpos($content['contents'], 'جريدة إلكترونية') === FALSE &&
                                                strpos($content['contents'], 'اسم المستخدم الى بريدك الالكتروني') === FALSE &&
                                                strpos($content['contents'], 'مواضيع ساخنة أتصل بنا') === FALSE &&
                                                strpos($content['contents'], 'جميع الحقوق محفوظة') === FALSE &&
                                                strpos($content['contents'], 'تسر إدارة موقع كووورة عالمية') === FALSE &&
                                                strpos($content['contents'], 'تسر إدارة موقع كووورة') === FALSE &&
                                                strpos($content['contents'], 'أضف تعليق') === FALSE &&
                                                strpos($content['contents'], 'أضف تعليقك') === FALSE &&
                                                strpos($content['contents'], 'إستونياإنجلتراأيسلنداإيطالي') === FALSE &&
                                                strpos($content['contents'], 'zflag') === FALSE &&
                                                strpos($content['contents'], 'الحقول الإلزامية مشار') === FALSE &&
                                                strpos($content['contents'], 'حسابنا الرسمي') === FALSE &&
                                                strpos($content['contents'], 'اخبرنا برأيك') === FALSE &&
                                                strpos($content['contents'], 'برامج مختارة') === FALSE &&
                                                strpos($content['contents'], 'اخبرتا برأيك') === FALSE &&
                                                strpos($content['contents'], 'أخبرتا برأيك') === FALSE &&
                                                strpos($content['contents'], 'أخبرنا برأيك') === FALSE &&
                                                strpos($content['contents'], 'لا توجد دورات متوفرة') === FALSE &&
                                                strpos($content['contents'], 'حقوق النشر محفوظة') === FALSE &&
                                                strpos($content['contents'], 'شريط الاخبار') === FALSE &&
                                                strpos($content['contents'], 'شريط الأخبار') === FALSE &&
                                                //strpos($content['contents'], 'ستقوم يوروسبورت عربية بنقل') === FALSE &&
                                                strpos($content['contents'], 'getElementsBy') === FALSE &&
                                               // strpos($content['contents'], 'cookies') === FALSE &&
                                               // strpos($content['contents'], 'cookie') === FALSE &&
                                                strpos($content['contents'], 'By clicking allow you') === FALSE &&
                                                strpos($content['contents'], 'Find out more') === FALSE &&
                                                strpos($content['contents'], 'sponsorKind') === FALSE &&
                                                strpos($content['contents'], 'كأس العالم FIFA') === FALSE &&
                                                strpos($content['contents'], 'siteheadersponsorship') === FALSE &&
                                                strpos($content['contents'], 'Share on Facebook') === FALSE &&
                                                strpos($content['contents'], 'sponsorship') === FALSE &&
                                                strpos($content['contents'], 'div_container') === FALSE &&
                                                strpos($content['contents'], 'you can debate these issues') === FALSE &&
                                                strpos($content['contents'], 'updateEmailTo') === FALSE &&
                                                strpos($content['contents'], 'Adobe Flash Player') === FALSE &&
                                                strpos($content['contents'], 'Flash Player') === FALSE &&
                                                strpos($content['contents'], 'مواقع شبكة الجزيرة: الجزيرة') === FALSE &&
                                                strpos($content['contents'], 'ContentType: Video') === FALSE &&
                                                strpos($content['contents'], 'خلاصات RSS') === FALSE &&
                                                strpos($content['contents'], 'window') === FALSE &&
                                                strpos($content['contents'], 'password') === FALSE &&
                                                strpos($content['contents'], 'Password') === FALSE &&
                                                strpos($content['contents'], 'document') === FALSE &&
                                                strpos($content['contents'], 'jQuery(function($)') === FALSE &&
                                                strpos($content['contents'], 'jQuery') === FALSE &&
                                                strpos($content['contents'], 'function') === FALSE &&
                                                strpos($content['contents'], 'Rights') === FALSE &&
                                                strpos($content['contents'], 'rights') === FALSE &&
                                                strpos($content['contents'], 'copyright') === FALSE &&
                                                strpos($content['contents'], 'Copyright') === FALSE &&
                                                strpos($content['contents'], 'Copyrights') === FALSE &&
                                                strpos($content['contents'], 'copyrights') === FALSE &&                                          
                                                strpos($content['contents'], '404 NOTFOUND') === FALSE &&
                                                strpos($content['contents'], 'NOT FOUND') === FALSE
                                                
                                               ) {   
                                                    
                                                   if(strrpos($reversed_url,"alkhobartimes.com") === FALSE && 
                                                     //  strrpos($reversed_url,"hassacom.com") === FALSE &&
                                                       strrpos($reversed_url,"almowaten.net") === FALSE &&
                                                       strrpos($reversed_url,"ajel.sa") === FALSE &&
                                                       strrpos($reversed_url,"yanair.net") === FALSE &&
                                                       strrpos($reversed_url,"aljubailtoday.com.sa") === FALSE &&
                                                       strrpos($reversed_url,"arabi21.com") === FALSE &&
                                                       strrpos($reversed_url,"sra7h.com") === FALSE /*&&  */
                                                  //     strrpos($reversed_url,"al-balad.net") === FALSE 
                                                       ) {        
                                                       // preg_match('/(.*)\.([^.]*)$/', $content['contents'], $matches); //remove last dot
                                                          //  pr($content['contents']);
                                                      //  $content['contents'] = str_replace(".", "<br />", @$matches[1]);     //replace all dots with new line
                                                          // echo('ayman1: '.$content['contents']);exit;
                                                      //  pr($content['contents']);     exit;    
                                                      
                                                      
                                                       //  $content['contents']=preg_split("/(?<!\..)([\?\!\.]+)\s(?!.\.)/",$content['contents'],-1, PREG_SPLIT_DELIM_CAPTURE);  
                                                           // $desc['contents'] = preg_replace("/([\?\!\.]+)(?=\s+[A-Z])/", '<br />',$desc['contents']); 
                                                        //  unset($sentences[array_search('.', $sentences)]);
                                                          
                                                         // $content['contents'] = join("<br />", $content['contents']);
                                                    }
                                                    else{
                                                       // $content['contents'] = str_replace(".", "<br /><br />", $content['contents']);     //replace all dots with new line 
                                                       // echo('ayman2: '.$content['contents']);exit;
                                                    }
                                                    
                                                     //echo ('yyyyyyyyyyyyyyyyyyyy');pr($reversed_url);   exit; 
                                                   if(strrpos($reversed_url,"kora.com") !== FALSE) {
                                                      $news['desc'] .= $content['contents'] . " <br /><br /> "; 
                                                      break;
                                                   }
                                                   elseif(strrpos($reversed_url,"tracksport.net") !== FALSE /*|| strrpos($reversed_url,"marebpress.net") !== FALSE*/) {
                                                       if (count($content) == 1) {
                                                           $news['desc'] .= $content['contents'] . " <br /><br /> ";
                                                           break;
                                                       }
                                                       else {
                                                           if (strpos($content, 'يمكنك الآن الإضافة المباشرة للتعليقات، وعدد كبير من المميزات والخيارات المتاحة فقط للأعضاء') === FALSE) {
                                                                $news['desc'] .= $content['contents'] . " <br /><br /> ";
                                                                break;    
                                                           }
                                                           else{
                                                               if ($counter == 1){
                                                                   $news['desc'] .= $content['contents'] . " <br /><br /> ";
                                                                   break;
                                                               }
                                                           }
                                                           
                                                           $counter++;
                                                       }
                                                   }   
                                                   elseif(/*strrpos($reversed_url,"almotamar.net") !== FALSE ||*/ strrpos($reversed_url,"barca4ever.com") !== FALSE) {
                                                       if (is_array($content['contents'])) {
                                                           if ($counter == 2){
                                                               $news['desc'] .= $content['contents'] . " <br /><br /> ";
                                                               break;
                                                           }
                                                           $counter++;
                                                       }
                                                       else{
                                                           $news['desc'] .= $content['contents'] . " <br /><br /> ";
                                                       }
                                                   }    
                                                   elseif(strrpos($reversed_url,"shorouqoman.com") !== FALSE) {
                                                      if ($counter == 0){  
                                                           $counter++; 
                                                           continue;
                                                      }
                                                      else{  
                                                          $news['desc'] .= $content['contents'] . " <br /><br /> ";
                                                      }
                                                       
                                                      $counter++;
                                                   }
                                                  /* else if (strpos($reversed_url, "aljazeera.net") !== FALSE) {
                                                        $news['desc'] .= $content['contents'] . " <br /><br /> ";
                                                        
                                                        if ($counter == 6) break;
                                                        
                                                        $counter++;
                                                   }  */
                                                   else if (strpos($reversed_url, "yemen-press.net") !== FALSE || 
                                                           // strpos($reversed_url, "alaraby.co.uk") !== FALSE ||
                                                            strpos($reversed_url, "ajmanpolice.gov") !== FALSE ||
                                                            strpos($reversed_url, "alsumaria.tv") !== FALSE 
                                                            ) { 
                                                       if ($counter == 0){   //  pr($content);    
                                                           $news['desc'] .= $content['contents'] . " <br /><br /> ";
                                                           break;
                                                       }
                                                       $counter++;
                                                   }
                                                   else if (strpos($reversed_url, "almayadeen.net") !== FALSE) { 
                                                      /* if ($counter == 3){   //  pr($content);    
                                                           $desc .= $content['contents'] . " <br /><br /> ";
                                                           break;
                                                       }
                                                       $counter++;*/
                                                      /* if (strpos($reversed_url, "/news/") !== FALSE) {  
                                                            $news['desc'] .= $content['contents'] . " <br /><br /> ";  
                                                        }
                                                        else{
                                                           $news['desc'] = "";
                                                        } */
                                                   }
                                                   else if (strpos($reversed_url, "24.ae") !== FALSE) { 
                                                       if ($counter == 0){   //  pr($content);    
                                                           $news['desc'] .= $content['contents'] . " <br /><br /> ";
                                                           break;
                                                       }
                                                       $counter++;
                                                   }
                                                  /* else if (strpos($reversed_url, "felesteen.ps") !== FALSE) { 
                                                       if ($counter == 3){   //  pr($content);    
                                                           $news['desc'] .= $content['contents'] . " <br /><br /> ";
                                                           break;
                                                       }
                                                       $counter++;
                                                   } */
                                                   else if (strpos($reversed_url, "asir.net") !== FALSE || strpos($reversed_url, "bayankw.net") !== FALSE){
                                                       if ($counter == 2){   //  pr($content);    
                                                           $news['desc'] .= $content['contents'] . " <br /><br /> ";
                                                           break;
                                                       }
                                                       $counter++;
                                                   }
                                                   else if (strpos($reversed_url, "kaahe.org/ar/index") !== FALSE) { 
                                                        if (
                                                             strpos($content['contents'], "SOURCES:") !== FALSE ||
                                                             strpos($content['contents'], "Copyright") !== FALSE ||
                                                             strpos($content['contents'], "للتحقق. HONcode نحن نلتزم بمبادئ ميثاق") !== FALSE ||
                                                             strpos($content['contents'], "مت الترجمة بواسطة الفريق العلمي لموسوعة") !== FALSE 
                                                           )
                                                           {
                                                               continue;
                                                           }
                                                           else{
                                                               $news['desc'] .= $content['contents'] . " <br /><br /> "; 
                                                           }
                                                   }
                                                   else if (strpos($reversed_url, "adpolice.gov.ae") !== FALSE){
                                                       if ($counter == 0){       
                                                           continue;
                                                       }
                                                       else{
                                                            $news['desc'] .= $content['contents'] . " <br /><br /> ";
                                                       }
                                                       $counter++;
                                                   }
                                                   else if (strpos($reversed_url, "goal.com") !== FALSE || 
                                                            strpos($reversed_url, "dw.de") !== FALSE ||
                                                            strpos($reversed_url, "dw.com") !== FALSE ||
                                                            strpos($reversed_url, "al-madina.com") !== FALSE ||
                                                            strpos($reversed_url, "ittinews.net") !== FALSE ||
                                                            strpos($reversed_url, "alhurra.com") !== FALSE ||
                                                            strpos($reversed_url, "th3professional.com") !== FALSE ||
                                                            strpos($reversed_url, "qabaq.com") !== FALSE ||
                                                            strpos($reversed_url, "arabhardware.net") !== FALSE ||
                                                            strpos($reversed_url, "snobonline.net") !== FALSE ||
                                                            strpos($reversed_url, "hihi2.com") !== FALSE ||
                                                            strpos($reversed_url, "wafa.com.sa") !== FALSE ||
                                                            strpos($reversed_url, "doniatech.com") !== FALSE ||
                                                            strpos($reversed_url, "ounousa.com") !== FALSE ||
                                                            strpos($reversed_url, "fcbarcelona.com") !== FALSE ||
                                                            strpos($reversed_url, "ardroid.com") !== FALSE ||
                                                            strpos($reversed_url, "sayidaty.net") !== FALSE ||
                                                            strpos($reversed_url, "steelbeauty.net") !== FALSE ||
                                                            strpos($reversed_url, "arabapps.org") !== FALSE ||
                                                            strpos($reversed_url, "fashion4arab.com") !== FALSE ||
                                                            strpos($reversed_url, "lahamag.com") !== FALSE ||
                                                            strpos($reversed_url, "rudaw.net") !== FALSE ||
                                                            strpos($reversed_url, "hawaaworld.com") !== FALSE ||
                                                            strpos($reversed_url, "techplus.me") !== FALSE ||
                                                            strpos($reversed_url, "alborsanews.com") !== FALSE ||
                                                            strpos($reversed_url, "wonews.net") !== FALSE ||
                                                            strpos($reversed_url, "wikise7a.com") !== FALSE ||
                                                            strpos($reversed_url, "hashtagarabi.com") !== FALSE ||
                                                            strpos($reversed_url, "kuwaitnews.com") !== FALSE ||
                                                            strpos($reversed_url, "android4ar.com") !== FALSE ||
                                                            strpos($reversed_url, "arabi21.com") !== FALSE ||
                                                            strpos($reversed_url, "hyperstage.net") !== FALSE ||
                                                            strpos($reversed_url, "euronews.com") !== FALSE ||
                                                            strpos($reversed_url, "arabitechnomedia.com") !== FALSE ||
                                                            strpos($reversed_url, "alwatannews.net") !== FALSE ||
                                                            strpos($reversed_url, "manchestercityfc.ae") !== FALSE ||
                                                            strpos($reversed_url, "ashorooq.net") !== FALSE ||
                                                            strpos($reversed_url, "alarab.qa") !== FALSE ||
                                                            strpos($reversed_url, "akhbar-alkhaleej.com") !== FALSE ||
                                                            strpos($reversed_url, "beinsports.com") !== FALSE ||
                                                            strpos($reversed_url, "bahrainalyoum.net") !== FALSE ||
                                                            strpos($reversed_url, "kooora.com") !== FALSE ||
                                                            strpos($reversed_url, "kooora2.com") !== FALSE ||
                                                            strpos($reversed_url, "arabic.sport360.com") !== FALSE ||
                                                            strpos($reversed_url, "sudanmotion.com") !== FALSE ||
                                                            strpos($reversed_url, "watn-news.com") !== FALSE ||
                                                            strpos($reversed_url, "arriyadiyah.com") !== FALSE ||
                                                            strpos($reversed_url, "hibapress.com") !== FALSE ||
                                                            strpos($reversed_url, "lana-news.ly") !== FALSE ||
                                                            strpos($reversed_url, "alnilin.com") !== FALSE ||
                                                            strpos($reversed_url, "al-mashhad.com") !== FALSE ||
                                                            strpos($reversed_url, "linkis.com") !== FALSE ||
                                                            strpos($reversed_url, "akhbarlibya24.net") !== FALSE ||
                                                            strpos($reversed_url, "libyanow.net.ly") !== FALSE ||
                                                            strpos($reversed_url, "hespress.com") !== FALSE ||
                                                            strpos($reversed_url, "yen-news.com") !== FALSE ||
                                                            strpos($reversed_url, "bna.bh") !== FALSE ||
                                                            strpos($reversed_url, "alwefaq.net") !== FALSE ||
                                                            strpos($reversed_url, "azzaman.com") !== FALSE ||
                                                            strpos($reversed_url, "assafir.com") !== FALSE ||
                                                            strpos($reversed_url, "tounesnews.com") !== FALSE ||
                                                            strpos($reversed_url, "aljoumhouria.com") !== FALSE ||
                                                            strpos($reversed_url, "alkhabarnow.net") !== FALSE ||
                                                            strpos($reversed_url, "bahrainmirror.no-ip.info") !== FALSE ||
                                                            strpos($reversed_url, "tuniscope.com") !== FALSE ||
                                                            strpos($reversed_url, "q8news.com") !== FALSE ||
                                                            strpos($reversed_url, "annahar.com") !== FALSE ||
                                                            strpos($reversed_url, "suhailnews.blogspot.com") !== FALSE ||
                                                            strpos($reversed_url, "lebanondebate.com") !== FALSE ||
                                                            strpos($reversed_url, "yemenat.net") !== FALSE ||
                                                            strpos($reversed_url, "marebpress.net") !== FALSE ||
                                                            strpos($reversed_url, "yemen-press.com") !== FALSE ||
                                                            strpos($reversed_url, "palsawa.com") !== FALSE ||
                                                            strpos($reversed_url, "ammonnews.net") !== FALSE ||
                                                            strpos($reversed_url, "assabeel.net") !== FALSE ||
                                                            strpos($reversed_url, "adhamiyahnews.com") !== FALSE ||
                                                            strpos($reversed_url, "alsawt.net") !== FALSE ||
                                                            strpos($reversed_url, "paltoday.ps") !== FALSE ||
                                                            strpos($reversed_url, "alarabiya.net") !== FALSE ||
                                                            strpos($reversed_url, "anbaaonline.com") !== FALSE ||
                                                            strpos($reversed_url, "qudspress.com") !== FALSE ||
                                                            strpos($reversed_url, "alquds.com") !== FALSE ||
                                                            strpos($reversed_url, "skynewsarabia.com") !== FALSE ||
                                                            strpos($reversed_url, "albawabhnews.com") !== FALSE ||
                                                            strpos($reversed_url, "makkahnewspaper.com") !== FALSE ||
                                                            strpos($reversed_url, "aliraqnews.com") !== FALSE ||
                                                            strpos($reversed_url, "alhasela.com") !== FALSE ||
                                                            strpos($reversed_url, "al-balad.net") !== FALSE ||
                                                            strpos($reversed_url, "hattpost.com") !== FALSE ||
                                                            strpos($reversed_url, "ahram.org.eg") !== FALSE ||
                                                            strpos($reversed_url, "shorouknews.com") !== FALSE ||
                                                            strpos($reversed_url, "middle-east-online.com") !== FALSE ||
                                                            strpos($reversed_url, "masralarabia.com") !== FALSE ||
                                                            strpos($reversed_url, "alshahedkw.com") !== FALSE ||
                                                            strpos($reversed_url, "anaween.com") !== FALSE ||
                                                            strpos($reversed_url, "fath-news.com") !== FALSE ||
                                                            strpos($reversed_url, "al-sharq.com") !== FALSE ||
                                                            strpos($reversed_url, "3seer.net") !== FALSE ||
                                                            strpos($reversed_url, "elfagr.org") !== FALSE ||
                                                            strpos($reversed_url, "almayadeen.net") !== FALSE ||
                                                            strpos($reversed_url, "layalina.com") !== FALSE ||
                                                            strpos($reversed_url, "echoroukonline.com") !== FALSE ||
                                                            strpos($reversed_url, "alkhabarsport.com") !== FALSE ||
                                                            strpos($reversed_url, "alquds.co.uk") !== FALSE ||
                                                            strpos($reversed_url, "alhayat.com") !== FALSE ||
                                                            strpos($reversed_url, "omannews.gov.om") !== FALSE ||
                                                            strpos($reversed_url, "alkhabarkw.com") !== FALSE ||
                                                            strpos($reversed_url, "pal24.net") !== FALSE ||
                                                            strpos($reversed_url, "alaraby.co.uk") !== FALSE ||
                                                            strpos($reversed_url, "akherkhabaronline.com") !== FALSE ||
                                                            strpos($reversed_url, "arn.ps") !== FALSE ||
                                                            strpos($reversed_url, "akhbarak.net") !== FALSE ||
                                                            strpos($reversed_url, "safa.ps") !== FALSE ||
                                                            strpos($reversed_url, "otv.com.lb") !== FALSE ||
                                                            strpos($reversed_url, "al-akhbar.com") !== FALSE ||
                                                            strpos($reversed_url, "nas.sa") !== FALSE ||
                                                            strpos($reversed_url, "ng4a.com") !== FALSE ||
                                                            strpos($reversed_url, "france24.com") !== FALSE ||
                                                            strpos($reversed_url, "saidaonline.com") !== FALSE ||
                                                            strpos($reversed_url, "elheddaf.com") !== FALSE ||
                                                            strpos($reversed_url, "lebanonfiles.com") !== FALSE ||
                                                            strpos($reversed_url, "dostor.org") !== FALSE ||
                                                            strpos($reversed_url, "n1t1.com") !== FALSE ||
                                                            strpos($reversed_url, "zamanarabic.com") !== FALSE ||
                                                            strpos($reversed_url, "almustaqbal.com") !== FALSE ||
                                                            strpos($reversed_url, "alkhaleejonline.net") !== FALSE ||
                                                            strpos($reversed_url, "youm7.com") !== FALSE ||
                                                            strpos($reversed_url, "arabic.cnn.com") !== FALSE ||
                                                            strpos($reversed_url, "alwasat.com.kw") !== FALSE ||
                                                            strpos($reversed_url, "felesteen.ps") !== FALSE ||
                                                            strpos($reversed_url, "nok6a.net") !== FALSE ||
                                                            strpos($reversed_url, "euronews") !== FALSE ||
                                                            strpos($reversed_url, "qna.org.qa") !== FALSE ||
                                                            strpos($reversed_url, "almotamar.net") !== FALSE ||
                                                            strpos($reversed_url, "3alyoum.com") !== FALSE ||
                                                            //strpos($reversed_url, "atyabtabkha.3a2ilati.com") !== FALSE ||
                                                            strpos($reversed_url, "android-time.com") !== FALSE ||
                                                            strpos($reversed_url, "autosearch.me") !== FALSE ||
                                                            strpos($reversed_url, "arabsturbo.com") !== FALSE ||
                                                            strpos($reversed_url, "elfann.com") !== FALSE ||
                                                            strpos($reversed_url, "elheddaf.com") !== FALSE ||
                                                            strpos($reversed_url, "arbdroid.com") !== FALSE ||
                                                            strpos($reversed_url, "shahiya.com") !== FALSE ||
                                                            strpos($reversed_url, "anazahra.com") !== FALSE ||
                                                            strpos($reversed_url, "q8ping.com") !== FALSE ||
                                                            //strpos($reversed_url, "fatafeat.com") !== FALSE ||
                                                            strpos($reversed_url, "yumyume.com") !== FALSE ||
                                                            strpos($reversed_url, "arab4x4.com") !== FALSE ||
                                                            strpos($reversed_url, "goodykitchen.com") !== FALSE ||
                                                            strpos($reversed_url, "olympic.qa") !== FALSE ||
                                                            strpos($reversed_url, "al-jazirah.com") !== FALSE ||
                                                            //strpos($reversed_url, "manalonline.com") !== FALSE ||
                                                            strpos($reversed_url, "oleeh.com") !== FALSE ||
                                                            strpos($reversed_url, "sea7htravel.com") !== FALSE ||
                                                            strpos($reversed_url, "alittihad.ae") !== FALSE ||
                                                            strpos($reversed_url, "buyemen.com") !== FALSE ||
                                                            strpos($reversed_url, "hiamag.com") !== FALSE ||
                                                            strpos($reversed_url, "akhbarelyom.com") !== FALSE ||
                                                            strpos($reversed_url, "mubasher.info") !== FALSE ||
                                                            strpos($reversed_url, "alayam.com") !== FALSE ||
                                                            strpos($reversed_url, "masrawy.com") !== FALSE ||
                                                            strpos($reversed_url, "al-gornal.com") !== FALSE ||
                                                            strpos($reversed_url, "forbesmiddleeast.com") !== FALSE ||
                                                            strpos($reversed_url, "elaph.com") !== FALSE ||
                                                            strpos($reversed_url, "alwasatnews.com") !== FALSE ||
                                                            strpos($reversed_url, "alriadey.com") !== FALSE ||
                                                            strpos($reversed_url, "ismailyonline.com") !== FALSE ||
                                                            strpos($reversed_url, "elwatannews.com") !== FALSE ||
                                                            strpos($reversed_url, "zamalekfans.com") !== FALSE ||
                                                            strpos($reversed_url, "cdn.alkass.net") !== FALSE ||
                                                            strpos($reversed_url, "sabqq.org") !== FALSE ||
                                                            strpos($reversed_url, "sport.ahram.org") !== FALSE ||
                                                            strpos($reversed_url, "Elaph") !== FALSE ||
                                                            strpos($reversed_url, "alriyadh.com") !== FALSE ||
                                                            strpos($reversed_url, "elaph") !== FALSE ||
                                                            strpos($reversed_url, "alsopar.com") !== FALSE ||
                                                            strpos($reversed_url, "alahlyegypt.com") !== FALSE ||
                                                            strpos($reversed_url, "tracksport.net") !== FALSE ||
                                                            strpos($reversed_url, "hilalcom.net") !== FALSE ||
                                                            strpos($reversed_url, "tayyar.org") !== FALSE ||
                                                            strpos($reversed_url, "goalna.com") !== FALSE ||
                                                            strpos($reversed_url, "aljazeera.net") !== FALSE ||
                                                            strpos($reversed_url, "realmadrid.com") !== FALSE ||
                                                            strpos($reversed_url, "alrayalaam.com") !== FALSE ||
                                                            strpos($reversed_url, "filgoal.com") !== FALSE ||
                                                            strpos($reversed_url, "yallakora.com") !== FALSE ||
                                                            strpos($reversed_url, "mbc.net") !== FALSE ||
                                                            strpos($reversed_url, "ar.beinsports.net") !== FALSE ||
                                                            strpos($reversed_url, "fifa.com") !== FALSE ||
                                                            strpos($reversed_url, "GalerieArtciles") !== FALSE ||
                                                            strpos($reversed_url, "dasmannews.com") !== FALSE ||
                                                            strpos($reversed_url, "mmaqara2t.com") !== FALSE ||
                                                            strpos($reversed_url, "ajialq8.com") !== FALSE ||
                                                            strpos($reversed_url, "annaharkw.com") !== FALSE ||
                                                            strpos($reversed_url, "aldostornews.com") !== FALSE ||
                                                            strpos($reversed_url, "almowaten.net") !== FALSE ||
                                                            strpos($reversed_url, "tounessna.info") !== FALSE ||
                                                            strpos($reversed_url, "altaleea.com") !== FALSE ||
                                                            strpos($reversed_url, "alkoutnews.net") !== FALSE ||
                                                            strpos($reversed_url, "almashhad.net") !== FALSE ||
                                                            strpos($reversed_url, "alsawtnews.cc") !== FALSE ||
                                                            strpos($reversed_url, "reqaba.com") !== FALSE ||
                                                            strpos($reversed_url, "acakuw.com") !== FALSE ||
                                                            strpos($reversed_url, "alhakea.com") !== FALSE ||
                                                            strpos($reversed_url, "alshamiya-news.com") !== FALSE ||
                                                            strpos($reversed_url, "al-seyassah.com") !== FALSE ||
                                                            strpos($reversed_url, "arabesque.tn") !== FALSE ||
                                                            strpos($reversed_url, "alkuwaityah.com") !== FALSE ||
                                                            strpos($reversed_url, "kuna.net.kw") !== FALSE ||
                                                            strpos($reversed_url, "Alkuwaityah.com") !== FALSE ||
                                                            strpos($reversed_url, "le360.ma") !== FALSE ||
                                                            strpos($reversed_url, "attounissia.com.tn") !== FALSE ||
                                                       //     strpos($reversed_url, "moheet.com") !== FALSE ||
                                                            strpos($reversed_url, "tunisien.tn") !== FALSE ||
                                                            strpos($reversed_url, "chouftv.ma") !== FALSE ||
                                                            strpos($reversed_url, "alanba.com.kw") !== FALSE ||
                                                            strpos($reversed_url, "elbilad.net") !== FALSE ||
                                                            strpos($reversed_url, "filwajiha.com") !== FALSE ||
                                                            strpos($reversed_url, "ennaharonline.com") !== FALSE ||
                                                            strpos($reversed_url, "alyaoum24.com") !== FALSE ||
                                                            strpos($reversed_url, "zoomtunisia.tn") !== FALSE ||
                                                            strpos($reversed_url, "alikhbaria.com") !== FALSE ||
                                                            strpos($reversed_url, "elshaab.org") !== FALSE ||
                                                            strpos($reversed_url, "moroccoeyes") !== FALSE ||
                                                            strpos($reversed_url, "babnet.net") !== FALSE ||
                                                            strpos($reversed_url, "tnntunisia.com") !== FALSE ||
                                                            strpos($reversed_url, "annaharnews.net") !== FALSE ||
                                                            strpos($reversed_url, "alwatan.com") !== FALSE ||
                                                            strpos($reversed_url, "atheer.om") !== FALSE ||
                                                            strpos($reversed_url, "al-watan.com") !== FALSE ||
                                                            strpos($reversed_url, "argaam.com") !== FALSE ||
                                                            strpos($reversed_url, "moe.gov.qa") !== FALSE ||
                                                            strpos($reversed_url, "omandaily.om") !== FALSE ||
                                                            strpos($reversed_url, "shabiba.com") !== FALSE ||
                                                            strpos($reversed_url, "futuretvnetwork.com") !== FALSE ||
                                                            strpos($reversed_url, "sahelmaten.com") !== FALSE ||
                                                            strpos($reversed_url, "lbcgroup.tv") !== FALSE ||
                                                            strpos($reversed_url, "nna-leb.gov.lb") !== FALSE ||
                                                            strpos($reversed_url, "orient-news.net") !== FALSE ||
                                                            strpos($reversed_url, "alforatnews.com") !== FALSE ||
                                                            strpos($reversed_url, "lebwindow.net") !== FALSE ||
                                                            strpos($reversed_url, "iraqdirectory.com") !== FALSE ||
                                                            strpos($reversed_url, "almanar.com.lb") !== FALSE ||
                                                            strpos($reversed_url, "alroeya.ae") !== FALSE ||
                                                            strpos($reversed_url, "syrianow.sy") !== FALSE ||
                                                            strpos($reversed_url, "o-t.tv") !== FALSE ||
                                                            strpos($reversed_url, "basnews.com") !== FALSE ||
                                                            strpos($reversed_url, "etilaf.org") !== FALSE ||
                                                            strpos($reversed_url, "alnoornews.net") !== FALSE ||
                                                            strpos($reversed_url, "alliraqnews.com") !== FALSE ||
                                                            strpos($reversed_url, "wam.ae") !== FALSE ||
                                                            strpos($reversed_url, "alrafidain.org") !== FALSE ||
                                                            strpos($reversed_url, "hroobnews.com") !== FALSE ||
                                                            strpos($reversed_url, "albayan.ae") !== FALSE ||
                                                            strpos($reversed_url, "alarabalyawm.net") !== FALSE ||
                                                            strpos($reversed_url, "el-balad.com") !== FALSE ||
                                                            strpos($reversed_url, "yanair.net") !== FALSE ||
                                                            strpos($reversed_url, "almesryoon.com") !== FALSE ||
                                                            strpos($reversed_url, "almogaz.com") !== FALSE ||
                                                            strpos($reversed_url, "jo24.net") !== FALSE ||
                                                            strpos($reversed_url, "egyptiannews.net") !== FALSE ||
                                                            strpos($reversed_url, "7iber.com") !== FALSE ||
                                                            strpos($reversed_url, "maqar.com") !== FALSE ||
                                                            strpos($reversed_url, "klmty.net") !== FALSE ||
                                                            strpos($reversed_url, "alamalmal.net") !== FALSE ||
                                                            strpos($reversed_url, "jn-news.com") !== FALSE ||
                                                            strpos($reversed_url, "assawsana.com") !== FALSE ||
                                                            strpos($reversed_url, "royanews.tv") !== FALSE ||
                                                            strpos($reversed_url, "lahaonline.com") !== FALSE ||
                                                            strpos($reversed_url, "addustour.com") !== FALSE ||
                                                            strpos($reversed_url, "wikise7a") !== FALSE ||
                                                            strpos($reversed_url, "almaghribtoday.net") !== FALSE ||
                                                            strpos($reversed_url, "3eesho.com") !== FALSE ||
                                                            strpos($reversed_url, "dailymedicalinfo.com") !== FALSE ||
                                                            strpos($reversed_url, "almasryalyoum.com") !== FALSE ||
                                                            strpos($reversed_url, "hafralbaten.com") !== FALSE ||
                                                            strpos($reversed_url, "kaahe.org") !== FALSE ||
                                                            strpos($reversed_url, "rotanamags.net") !== FALSE ||
                                                            strpos($reversed_url, "ajel.sa") !== FALSE ||
                                                            strpos($reversed_url, "almuraba.net") !== FALSE ||
                                                            strpos($reversed_url, "arabi21.com") !== FALSE ||
                                                            strpos($reversed_url, "almaydan2.net") !== FALSE ||
                                                            strpos($reversed_url, "aldawadmi.net") !== FALSE ||
                                                            strpos($reversed_url, "naseej.net") !== FALSE ||
                                                            strpos($reversed_url, "adwaalwatan.com") !== FALSE ||
                                                            strpos($reversed_url, "newsqassim.com") !== FALSE ||
                                                            strpos($reversed_url, "nwafecom.net") !== FALSE ||
                                                            strpos($reversed_url, "fajr.sa") !== FALSE ||
                                                            strpos($reversed_url, "ham-24.com") !== FALSE ||
                                                            strpos($reversed_url, "twasul.info") !== FALSE ||
                                                            strpos($reversed_url, "aljubailtoday.com.sa") !== FALSE ||
                                                            strpos($reversed_url, "rasdnews.net") !== FALSE ||
                                                            strpos($reversed_url, "zahran.org") !== FALSE ||
                                                            strpos($reversed_url, "arjja.com") !== FALSE ||
                                                            strpos($reversed_url, "spa.gov.sa") !== FALSE ||
                                                            strpos($reversed_url, "aljouf-news.com") !== FALSE ||
                                                            strpos($reversed_url, "almjardh.com") !== FALSE ||
                                                            strpos($reversed_url, "anbaanews.com") !== FALSE ||
                                                            strpos($reversed_url, "mini-news.net") !== FALSE ||
                                                            strpos($reversed_url, "tabuk-news.com") !== FALSE ||
                                                            strpos($reversed_url, "kharjhome.com") !== FALSE ||
                                                            strpos($reversed_url, "alkhaleejaffairs.org") !== FALSE ||
                                                            strpos($reversed_url, "alkhaleej.ae") !== FALSE ||
                                                            strpos($reversed_url, "roaanews.net") !== FALSE ||
                                                            strpos($reversed_url, "rsssd.com") !== FALSE ||
                                                            strpos($reversed_url, "cma.org.sa") !== FALSE ||
                                                            strpos($reversed_url, "saso.gov.sa") !== FALSE ||
                                                            strpos($reversed_url, "freeswcc.com") !== FALSE ||
                                                            strpos($reversed_url, "asir.com") !== FALSE ||
                                                            strpos($reversed_url, "asir.net_xxxxxx") !== FALSE ||
                                                            strpos($reversed_url, "baareq.com.sa") !== FALSE ||
                                                            strpos($reversed_url, "moh.gov.sa") !== FALSE ||
                                                            strpos($reversed_url, "alrakoba.net") !== FALSE ||
                                                            strpos($reversed_url, "alsharq.net.sa") !== FALSE ||
                                                            strpos($reversed_url, "sabq.org") !== FALSE ||
                                                            strpos($reversed_url, "paltimes.net") !== FALSE ||
                                                            strpos($reversed_url, "ar.yabiladies.com") !== FALSE ||
                                                            strpos($reversed_url, "reuters.com") !== FALSE ||
                                                            strpos($reversed_url, "palinfo.com") !== FALSE ||
                                                            strpos($reversed_url, "electrony.net") !== FALSE ||
                                                            strpos($reversed_url, "electronynet") !== FALSE ||
                                                            strpos($reversed_url, "alwasat.ly") !== FALSE ||
                                                            strpos($reversed_url, "dotmsr.com") !== FALSE ||
                                                            strpos($reversed_url, "naba.ps") !== FALSE ||
                                                            strpos($reversed_url, "raialyoum.com") !== FALSE ||
                                                            strpos($reversed_url, "charlesayoub.com") !== FALSE ||
                                                            strpos($reversed_url, "aljadeed.tv") !== FALSE ||
                                                            strpos($reversed_url, "aawsat.com") !== FALSE ||
                                                            strpos($reversed_url, "alqabas.com.kw") !== FALSE ||
                                                            strpos($reversed_url, "aleqt.com") !== FALSE ||
                                                            strpos($reversed_url, "aljarida.com") !== FALSE ||
                                                            strpos($reversed_url, "sabr.cc") !== FALSE ||
                                                            strpos($reversed_url, "alwatan.kuwait.tt") !== FALSE ||
                                                            strpos($reversed_url, "alraimedia.com") !== FALSE ||
                                                            strpos($reversed_url, "14march.org") !== FALSE
                                                            ) {  //echo('dddddddddddddddddddd');     pr($news['desc']); exit;
                                                       if ($counter == 0){   //  pr($content);    
                                                           $news['desc'] .= $content['contents'] . " <br /><br /> ";
                                                           break;
                                                       }
                                                       $counter++;
                                                   }
                                                  
                                                   else if ( strpos($reversed_url, 'alalam.ir') !== FALSE) {    
                                                       if ($counter == 2) {
                                                           if (strpos($content['contents'], 'Shadowbox.init') !== FALSE) {
                                                               $no_text1 = true;
                                                               $counter++;  
                                                               
                                                               continue;
                                                           }
                                                       } 
                                                       elseif ($counter == 3) {
                                                           if (strpos($content['contents'], 'Shadowbox.init') !== FALSE) {
                                                               $no_text = true;
                                                               $counter++;  
                                                               
                                                               continue;
                                                           }
                                                       } 
                                                       else if ($counter == 3 && isset($no_text1)) {
                                                           $news['desc'] = '';
                                                           $news['desc'] .= $content['contents'] . " <br /><br /> ";
                                                           break;
                                                       }
                                                       else if ($counter == 4 && isset($no_text)) {
                                                           $news['desc'] = '';
                                                           $news['desc'] .= $content['contents'] . " <br /><br /> ";
                                                           break;
                                                       }
                                                       else{
                                                           $news['desc'] .= $content['contents'] . " <br /><br /> "; 
                                                       }
                                                       
                                                       $counter++;
                                                   }
                                                   else{
                                                       $news['desc'] .= $content['contents'] . " <br /><br /> "; 
                                                   }
                                            }
                                            else{
                                                $news['desc'] = '404';
                                            }
                                        }
                                    }
                                    else{      // pr($data);
                                        //$news['desc'] = $data['paragraph']['contents'];
                                        $news['desc'] = $news['title'];
                                    }
                                }
                                
                                if (strpos($reversed_url, "http://www.paltoday.tv") !== FALSE) {
                                    $news['desc'] = "";
                                }
                                //  echo('dddddddddddddddddddd');     pr($news['desc']); exit;
                               // if (!isset($news['image'])) {
                                   // $headers = get_meta_tags($news['url']);
                                                 
                                    //if (!isset($header['twitter:image:src'])) {   
                                   //     $og_meta = get_og_meta($news['url']);     //facebook           
                                   //     $news['image'] = $og_meta['og:image'];
                                  //  }
                                  //  else if (isset($headers['twitter:image:src'])){   
                                     //   $news['image'] = $headers['twitter:image:src'];
                                   // }
                                   // else{
                                      //  $news['image'] = '';
                                   // }
                                   
                                   if ($news['image'] == "") {   
                                         if (isset($item['media_content']['@attributes']['url'])) {     
                                             $news['image'] = $item['media_content']['@attributes']['url'];
                                         }
                                         else{
                                             $news['image'] = @$data['images'][0];
                                         }
                                   }
                                   else{
                                         $news['image'] = @$data['images'][0];
                                   }
                     
                                   if (trim($news['news_image']) != "") {
                                       $news['image'] = $news['news_image'];
                                   }
                                   else {
                                       //   pr($news);     exit;
                                        if (strpos($reversed_url, "moe.gov.sa") === FALSE && 
                                            strpos($reversed_url, "moh.gov.sa") === FALSE &&
                                            strpos($reversed_url, "zamanalwsl.net") === FALSE &&
                                            strpos($reversed_url, "alkoutnews.net") === FALSE &&
                                            strpos($reversed_url, "oleeh.com") === FALSE &&
                                            strpos($reversed_url, "almustagbal.com") === FALSE &&
                                            strpos($reversed_url, "alqabas.com.kw") === FALSE &&
                                            strpos($reversed_url, "realmadrid") === FALSE &&
                                            strpos($reversed_url, "akhbarelyom.com") === FALSE &&
                                            strpos($reversed_url, "alweeam.com") === FALSE &&
                                            strpos($reversed_url, "1asir.com") === FALSE &&
                                            strpos($reversed_url, "alroya.om") === FALSE &&
                                            //strpos($reversed_url, "atyabtabkha.3a2ilati.com") === FALSE &&
                                            strpos($reversed_url, "al-jamaheir.net") === FALSE &&
                                            strpos($reversed_url, "shaam.org") === FALSE
                                            ) {
                                                         
                                            $news['url'] = str_replace(" &", "&", $news['url']);
                                            
                                            $headers = get_meta_tags(trim($reversed_url));
                                                //  pr($headers);       
                                            if (isset($headers['og:image'])) { 
                                                $news['image'] = $headers['og:image'];
                                            }    
                                            else if (isset($headers['twitter:image:src'])) { 
                                                $news['image'] = $headers['twitter:image:src'];
                                            }
                                            else if ( isset($headers['twitter:image']) ) {
                                                $news['image'] = $headers['twitter:image'];
                                            }
                                            else if (!isset($headers['twitter:image:src'])){   
                                                $og_meta = get_og_meta($news['url']);     //facebook 
                                                             //   pr($og_meta);
                                                if (is_array($og_meta)) {
                                                    if (isset($og_meta['og:image'])) {        
                                                        $news['image'] = $og_meta['og:image'];
                                                    }
                                                    else if ( isset($og_meta['twitter:image']) ) {
                                                        $news['image'] = $og_meta['twitter:image'];
                                                    }
                                                }
                                            }
                                            else{
                                               // $news['image'] = '';
                                            }
                                        }
                                        
                                        if (strpos($reversed_url, "zamanalwsl.net") !== FALSE) {
                                             $og_meta = get_og_meta($reversed_url);     //facebook 
                                                             //   pr($og_meta);
                                             if (is_array($og_meta)) {
                                                if (isset($og_meta['og:image'])) {        
                                                    $news['image'] = $og_meta['og:image'];
                                                }
                                                else if ( isset($og_meta['twitter:image']) ) {
                                                    $news['image'] = $og_meta['twitter:image'];
                                                }
                                             }
                                        }
                                   }
                                   
                                    
                                    //pr('fff: '.$reversed_url);          exit;
                                      echo('^^^^^^^^^^^^^^^^^^^^^^^^'); pr($news);    // exit;
                                    if (strpos($reversed_url, "arabianbusiness.com") !== FALSE) {  //   exit;
                                        $news['image'] = "http://arabic.arabianbusiness.com/" . $news['image'];
                                    }
                                    else if (strpos($reversed_url, "yabiladies.com") !== FALSE) {
                                        $news['image'] = str_replace(":443", "", $news['image']);
                                    }
                                    else if(strpos($reversed_url, "freeswcc.com") !== FALSE) {  
                                         $news['image'] = str_replace("new/", "", $news['image']);
                                    }else if(strpos($reversed_url, "makkahnewspaper") !== FALSE) {  
                                         $news['image'] = str_replace("makkahonline.com.sa", "www.makkahnewspaper.com", $news['image']);
                                    }else if(strpos($reversed_url, "ajel.sa") !== FALSE) {  
                                         $news['image'] = str_replace("sites/default/files/images", "sites/default/files/styles/optimized_original/public", $news['image']);
                                    }
                            }
                            else{   //news is already exist
                                $news['desc'] = 'already_exists';
                            }
      
                        }
                        else{
                            $news['desc'] = "url not set";
                        }
                       // echo('url: '.$url[0]->expanded_url . '<br />');
                        //pr($data);
                    }
                }
                else{
                    $news['desc'] = "url not set";
                }
                        //   pr($news);    exit;
                echo('$reversed_url:_2 ' . $reversed_url . '<br />');
                //echo($title . ' -> ' . $image . ' -> ' . $desc .'<br />');
                
                if (strpos($reversed_url, "http://www.alwatanvoice.com/common/error.html") === FALSE &&
                    strpos($reversed_url, "http://www.alwatanvoice.com/arabic/index.html") === FALSE && 
                    strpos($url_decode, "http://www.youm7.com/أخبار-عاجلة-65") === FALSE &&
                    strpos($reversed_url, "video.alwatanvoice.com") === FALSE && 
                    strpos($reversed_url, "dropbox.com") === FALSE 
                ) {
                     if ($news['desc'] != 'already_exists') {
                         save_news($news, $data, $reversed_url);
                     }
                }
                
                //exit;
            }
         } 
         
       //  if (strpos($reversed_url, "kooora.com") === FALSE) { 
             //delete tweet
             $delete_tweet = "delete from tweets where tweet_id = '" . $row['tweet_id'] . "'";
             $delete_tweet_url = "delete from tweet_urls where tweet_id = '" . $row['tweet_id'] . "'";
             
            // $fp = fopen('delete_tweets.txt', 'a+');
            // fwrite($fp, $delete_tweet . "\n");
            // fclose($fp);
             
             $conn->db_query($delete_tweet);
             $conn->db_query($delete_tweet_url);
         // }
    }    
} 

function get_sources_rss($cat_id, $start, $offset){
    global $conn;
    
    $start = ($start*$offset);
                  
    /*$query = "select category_id, link, c1.parent
                from categories c1
                inner join rss_news on c1.id = rss_news.category_id
                inner join categories c2 on c1.parent = c2.id                                                                         
                where c2.parent = '$cat_id' and rss_news.type = 2 and rss_news.category_id = 1644 limit $start, $offset";    //rss*/  
                
                   
    $query = "select category_id, link, c1.parent
                from categories c1
                inner join rss_news on c1.id = rss_news.category_id
                inner join categories c2 on c1.parent = c2.id
                where c2.parent = '$cat_id' and rss_news.type = 2 order by category_id limit $start, $offset";    //rss    
                                        
    echo($query); //exit;
    
    $res = $conn->db_query($query);
    $desc = '';
    $news['desc'] = '';                                           

    while($row = $conn->fetch_assoc($res)) {
        $news['cid'] = $row['category_id'];
        
        if (!is_array($row['link'])) {
            $row['link'] = $row['link'];
        }
        else{
            $row['link'] = $row['link'][0];
        }
        
        $row['link'] = trim($row['link']);
        
        if (strpos($row['link'], "itunes.apple.com") !== FALSE) continue;
        if (strpos($row['link'], "play.google.com") !== FALSE) continue;
                
        $json = XmlToJson::Parse($row['link'], false);
            echo('<br />$row[link]: ' . $row['link'] . '<br />');   
        $counter = 0;
                                     
        if (!isset($json['channel']['item'][0]['title'])) { //for qna القطرية
            $item_arr = $json['channel']['item'];
            unset($json['channel']['item']);
            $json['channel']['item'][0] = $item_arr;
        }
            //  pr($json['channel']['item']);    exit; 
        foreach($json['channel']['item'] as $item) {
            $counter++;
            // echo('c:'.is_array($item));    exit; 
            if ($counter == 5) break; //last 4 news
            
            $news['title'] = $item['title'];
                
            if (is_array(@$item['link'])) {
                $news['url'] = $item['link'][0];
            }
            else {
                $news['url'] = @$item['link'];
            }
              //$news['url'] =  "http://www.charlesayoub.com/more/894078";
            $news['url'] = str_replace('amp;', '&', $news['url']);
            $news['url'] = str_replace('amp; ', '&', $news['url']);
            $news['url'] = str_replace(' amp;', '&', $news['url']);
            
            $news['image'] = @$item['image'][0];
               
            $news['url'] = str_replace("new.bab.com", "www.bab.com", $news['url']);
                              //  echo($news['image']);   exit;      
            $reversed_url = reverse_tinyurl($news['url']);
            
            if (strpos($reversed_url, "bit.ly") !== FALSE || 
                strpos($reversed_url, "news.google.com") !== FALSE || 
                strpos($reversed_url, "feedproxy.google.com") !== FALSE || 
                strpos($reversed_url, "alanba.com.kw") !== FALSE || 
                strpos($reversed_url, "eel.la") !== FALSE || 
                strpos($reversed_url, "trib.al") !== FALSE || 
                strpos($reversed_url, "goo.gl") !== FALSE || 
                strpos($reversed_url, "t.co") !== FALSE || 
                strpos($reversed_url, "fb.me") !== FALSE || 
                strpos($reversed_url, "ow.ly") !== FALSE || 
                strpos($reversed_url, "shar.es") !== FALSE){
                $reversed_url = reverse_tinyurl($reversed_url);
            }
            
            if (!isset($item['title'])) {
                continue;
            }
            else{
                if (trim($item['title']) == "") continue;
            }
                                                           // echo('<br />title:');  pr($item) . '<br />';
            $twitter_new_id = $news['twitter_news_id'] = md5(@$item['title'] . $news['cid']);   
                         //    pr($item['link']);  exit;
            echo('<br />url_000:' . $news['url'] . '<br />');
                                                              
            $twitte_count = check_if_twitter_news_exists($twitter_new_id); 
            
            echo('<br />$twitte_count: ' . $twitte_count . '<br />');
                  //echo(file_get_contents('http://petra.gov.jo/Public_News/Nws_NewsDetails.aspx?Site_Id=2&lang=1&NewsID=194030&CatID=14'));
                 // exit;
            $data = '';
                     //   exit;
            if (!$twitte_count) {    //echo('*0*0*0*0*0*0*0*0*0*0*0*0'); pr($news);     exit;
                if ($news['url'] != "") {
                    
                    if(strrpos($news['url'],'alqabas.com.kw') !== FALSE) { 
                        $news['url'] = str_replace("Article.aspx", "Articles.aspx", $news['url']);
                        $news['url'] = str_replace("id=", "ArticleID=", $news['url']);
                    }
                    
                    if(strrpos($news['url'],'http://www.qna.org.qa/News/NewsBulletin') !== FALSE) {
                        $news['desc'] = $item['description'];
                    }
                    else {
                       $data = fetch($news['url'], false); 
                       
                       echo('-------------------------------------->>>>>>>>>>><br />');pr($data);  // exit;
                       $news['desc'] = '';
                    }
                    
                    if(strrpos($news['url'],"tayyar.org") !== FALSE) {    
                         $news['image'] = @$data['images'][0];
                    }            
                    
                
                    if ($news['image'] == "") {   
                         if (isset($item['media_content']['@attributes']['url'])) {     
                             $news['image'] = $item['media_content']['@attributes']['url'];
                         }
                         else{
                             $news['image'] = @$data['images'][0];
                         }
                     }
                     else{
                         $news['image'] = @$data['images'][0];
                     }
                      
                     if(strrpos($news['url'],"aljazeera.net") !== FALSE) {               
                        $news['url'] = explode("/", $news['url']);
                        
                        $last_part = urlencode($news['url'][count($news['url'])-1]);
                        
                        $aljazeera_url = '';
                        
                        for($i = 0; $i < count($news['url'])-1; $i++) {
                            $aljazeera_url .= $news['url'][$i] . "/";
                        }
                        
                        $aljazeera_url .= $last_part; 
                        
                        $news['url'] = $aljazeera_url;
                      //  echo($aljazeera_url); exit;
                    }
        
                       //  pr($data);      
                         
                       //   exit; 
                    // if ($news['image'] == "") {
                    
                   // echo($news['url']);
                    if(
                        strrpos($news['url'],"alrafidain.org") === FALSE &&
                        strrpos($news['url'],"annaharnews.net") === FALSE &&
                        strrpos($news['url'],"elbilad.net") === FALSE &&
                        strrpos($news['url'],"alkuwaityah.com") === FALSE &&
                        strrpos($news['url'],"Alkuwaityah.com") === FALSE &&
                        strrpos($news['url'],"alqabas.com.kw") === FALSE &&
                        strrpos($news['url'],"tayyar.org") === FALSE &&
                        strrpos($news['url'],"almustagbal.com") === FALSE &&
                        strrpos($news['url'],"akhbarelyom.com") === FALSE &&
                        strrpos($news['url'],"alroya.om") === FALSE &&
                        strrpos($news['url'],"1asir.com") === FALSE &&
                        strrpos($news['url'],"alweeam.com") === FALSE &&
                        strrpos($news['url'],"alayam.com") === FALSE /*&&
                        strrpos($news['url'],"aliraqnews.com") === FALSE */
                       ) {
                         //  echo('111111111111111111111111111111111');exit;
                           
                        if(strrpos($news['url'],"charlesayoub.com") === FALSE) {
                            $news['url'] = str_replace(" &", "&", $news['url']); 
                                     
                            $headers = get_meta_tags(trim($news['url'])); 
                        } 
                        else{
                            $headers = get_og_meta($news['url']);
                            $headers['og:image'] = $headers['og:image_2'];
                        }
                        
                        if(strrpos($news['url'],"alkhaleejaffairs.org") !== FALSE) {  
                            $headers = get_og_meta($news['url']); 
                        }
                             //echo('------<br />'); pr($headers); // exit;
                        if (isset($headers['og:image'])) { 
                            $news['image'] = $headers['og:image'];
                            
                            if (strpos($news['url'], "palinfo.com") !== FALSE) { 
                                $news['image'] = "https://www.palinfo.com" . $news['image'];
                            }
                        }    
                        else if (isset($headers['twitter:image:src'])) { 
                            $news['image'] = $headers['twitter:image:src'];
                        }
                        else if ( isset($headers['twitter:image']) ) {
                            $news['image'] = $headers['twitter:image'];
                        }
                        else if (!isset($headers['twitter:image:src'])){   
                            $og_meta = get_og_meta($news['url']);     //facebook 
                                   //  pr($og_meta);       exit;
                            if (is_array($og_meta)) {   //pr($og_meta); exit;
                                if (isset($og_meta['og:image'])) {    
                                    $news['image'] = $og_meta['og:image'];
                                }
                                else if ( isset($og_meta['twitter:image']) ) {
                                    $news['image'] = $og_meta['twitter:image'];
                                }
                            }
                        }
                        else{
                           // $news['image'] = '';
                        }
                        
                    }
                      //  pr($headers); exit;
                    //}
                    
                                echo('urrrrrrrrrrl:' . $news['url']);
                           
                        echo('++++++++++++++++++++++++++++');pr($data['paragraph']);     // exit;
                        // pr($data);exit;
                    $count = 0;
                           // exit;
                    if (isset($data['paragraph'])) {
                        foreach($data['paragraph'] as $desc) {
                             if (
                                 (
                                 trim($desc['contents']) != "" &&
                                 strpos($desc['contents'], 'اقرأ المزيد') === FALSE &&
                                 strpos($desc['contents'], 'getElementById') === FALSE &&
                                 strpos($desc['contents'], 'getElementsBy') === FALSE &&
                                 strpos($desc['contents'], 'jQuery(function($)') === FALSE &&
                                 strpos($desc['contents'], 'jQuery') === FALSE &&
                                 strpos($desc['contents'], 'window') === FALSE &&
                                 strpos($desc['contents'], 'document') === FALSE &&
                                 strpos($desc['contents'], 'TWTR.Widget') === FALSE &&
                                 strpos($desc['contents'], 'Your browser will redirect to your requested content shortly') === FALSE &&
                                 strpos($desc['contents'], 'Completing the CAPTCHA proves you are a human and gives') === FALSE &&
                                 strpos($desc['contents'], 'addThumbnail') === FALSE &&
                                 strpos($desc['contents'], 'الأخبار العاجلةالأولىأول') === FALSE &&
                                 strpos($desc['contents'], 'jQuery(function($)') === FALSE &&
                                 strpos($desc['contents'], '$("') === FALSE &&
                                 strpos($desc['contents'], 'updateEmailTo') === FALSE &&
                                 strpos($desc['contents'], 'siteheadersponsorship') === FALSE &&
                                 strpos($desc['contents'], 'Adobe Flash Player') === FALSE &&
                                 strpos($desc['contents'], 'Flash Player') === FALSE &&
                                 strpos($desc['contents'], 'article.title') === FALSE &&
                                 strpos($desc['contents'], 'mainImage') === FALSE &&
                                 strpos($desc['contents'], 'addClass') === FALSE &&
                                 strpos($desc['contents'], 'ContentType: Video') === FALSE &&
                                 strpos($desc['contents'], 'اسم المستخدم الى بريدك الالكتروني') === FALSE &&
                                 strpos($desc['contents'], 'Internal Server Error') === FALSE &&
                                 strpos($desc['contents'], 'لا يوجد تعليقات على هذا المقال') === FALSE &&
                                 strpos($desc['contents'], 'You are using an outdated') === FALSE &&
                                 strpos($desc['contents'], 'من نحن | اتصل بنا | المواطن الصحفي') === FALSE &&
                                 strpos($desc['contents'], 'مواقع شبكة الجزيرة: الجزيرة') === FALSE &&
                                 strpos($desc['contents'], 'Please upgrade your browser') === FALSE &&
                                 strpos($desc['contents'], ' جميع التعليقات') === FALSE &&
                                 strpos($desc['contents'], 'متصفح قديم') === FALSE &&
                                 strpos($desc['contents'], 'للاستمتاع بكافة المميزات يرجى') === FALSE &&
                                 strpos($desc['contents'], 'الرئيسية مركز المباريات') === FALSE &&
                                 strpos($desc['contents'], 'قم بالتسجيل') === FALSE &&
                                 strpos($desc['contents'], 'الوسوم') === FALSE &&
                                 strpos($desc['contents'], 'جميع الحقوق') === FALSE &&
                                 strpos($desc['contents'], 'جميع الحقوق محفوظة') === FALSE &&
                                 strpos($desc['contents'], 'رئيس التحرير') === FALSE &&
                                 strpos($desc['contents'], 'صفحتنا على فيس بوك') === FALSE &&
                                 strpos($desc['contents'], 'شارك اصدقاءك') === FALSE &&
                                 strpos($desc['contents'], 'شارك أصدقاءك') === FALSE &&
                                 strpos($desc['contents'], 'رئاسة التحرير') === FALSE &&
                                 strpos($desc['contents'], 'شريط الاخبار') === FALSE &&
                                 strpos($desc['contents'], 'شريط الأخبار') === FALSE &&
                                 strpos($desc['contents'], 'أخبار محلية أخبار المناطق') === FALSE &&
                                 strpos($desc['contents'], 'الرئيسية الأخبار') === FALSE &&
                                 strpos($desc['contents'], 'معايير البحث كل التصنيفات') === FALSE &&
                                 strpos($desc['contents'], 'معايير البحث') === FALSE &&
                                 strpos($desc['contents'], 'معايير البحث كل التصنيفات') === FALSE &&
                                 strpos($desc['contents'], 'منوعات أخبار محلية') === FALSE &&
                                 strpos($desc['contents'], 'أخبار مصر عرب وعالم') === FALSE &&
                                 strpos($desc['contents'], 'حقوق النشر محفوظة') === FALSE &&
                                 strpos($desc['contents'], 'الرئيسية الاخبار') === FALSE &&
                                 strpos($desc['contents'], 'الرئيسية مركز المباريات') === FALSE &&
                                 strpos($desc['contents'], 'function') === FALSE &&
                                 strpos($desc['contents'], 'success') === FALSE &&  
                                 strpos($desc['contents'], 'بالفيديو والصور') === FALSE &&  
                                 strpos($desc['contents'], 'شؤون دينية') === FALSE &&
                                 strpos($desc['contents'], 'ة في رحاب الداعية ') === FALSE &&
                                 strpos($desc['contents'], 'Weather forecast') === FALSE &&
                                 strpos($desc['contents'], 'يومية اخبارية') === FALSE &&
                                 strpos($desc['contents'], 'من نحن') === FALSE &&
                                 strpos($desc['contents'], 'أعلن معنا') === FALSE &&
                                 strpos($desc['contents'], 'Powered By') === FALSE &&
                                 strpos($desc['contents'], 'Powered by') === FALSE &&
                                 strpos($desc['contents'], 'powered by') === FALSE &&
                                 strpos($desc['contents'], 'just click') === FALSE &&
                                 strpos($desc['contents'], 'Just Click') === FALSE &&
                                 strpos($desc['contents'], 'Receive all') === FALSE &&
                                 strpos($desc['contents'], 'via facebook') === FALSE &&
                                 strpos($desc['contents'], 'password') === FALSE &&
                                 strpos($desc['contents'], 'Password') === FALSE &&
                                 strpos($desc['contents'], 'ترحب شبكة cnn') === FALSE &&
                                 strpos($desc['contents'], 'ننصحك بمراجعة') === FALSE &&
                                 strpos($desc['contents'], 'Cable News Network') === FALSE &&
                                 strpos($desc['contents'], 'All Rights Reserved') === FALSE &&
                                 strpos($desc['contents'], 'إقرأ المزيد') === FALSE 
                                 )
                                 ||
                                 (
                                      strrpos($news['url'],"sabr.cc") !== FALSE
                                 )
                                ) {
                                              
                                    if(strrpos($news['url'],"alkhobartimes.com") === FALSE && 
                                     // strrpos($news['url'],"hassacom.com") === FALSE &&
                                    //  strrpos($news['url'],"al-balad.net") === FALSE &&
                                      strrpos($news['url'],"sra7h.com") === FALSE &&
                                      strrpos($news['url'],"ajel.sa") === FALSE &&
                                      strrpos($news['url'],"arabi21.com") === FALSE &&
                                      strrpos($news['url'],"alrafidain.org") === FALSE &&
                                      strrpos($news['url'],"aljubailtoday.com.sa") === FALSE //&&
                                      //strrpos($news['url'],"almowaten.net") === FALSE 
                                      ) {     // echo('1no dot with 2 brs');       
                                      // preg_match('/(.*)\.([^.]*)$/', $desc['contents'], $matches); //remove last dot
                                            
                                      // $desc['contents'] = str_replace(".", "<br />", @$matches[1]);     //replace all dots with new line   
                                      
                                     // if (strrpos($news['url'],"almowaten.net") === FALSE) {      echo('2no dot with 2 brs');
                                            //$desc['contents']=preg_split("/(?<!\..)([\?\!\.]+)\s(?!.\.)/",$desc['contents'],-1, PREG_SPLIT_DELIM_CAPTURE);
                                    //        $desc['contents'] = preg_replace("/([\?\!\.]+)(?=\s+[A-Z])/", '<br />',$desc['contents']);  
          
                                          //  unset($sentences[array_search('.', $sentences)]);
                                          
                                           // $desc['contents'] = join("<br />", $desc['contents']); 
                                     // }
                                   }
                                   else{     //  echo('dot with 2 brs');
                                      // $desc['contents'] = str_replace(".", "<br /><br />", $desc['contents']);     //replace all dots with new line 
                                   }
                                                   
                                if (strpos($news['url'], "moheet.com") !== FALSE) {
                                    if (isset($desc['attributes']['style'])) continue;
                                    
                                    if ($desc['len'] < 100) continue;
                                }
                                
                               /* if (strpos($news['url'], "aljazeera.net") !== FALSE) {
                                    $news['desc'] .= $desc['contents'] . " <br /><br /> ";
                                    
                                    if ($count == 6) break;
                                }
                                else*/ if (strpos($news['url'], "24.ae") !== FALSE) {
                                    if ($count == 0){   //  pr($content);    
                                       $news['desc'] .= $desc['contents'] . " <br /><br /> ";
                                       break;
                                    }
                                    $count++;
                                }
                                /*elseif (strpos($news['url'], "felesteen.ps") !== FALSE) {
                                    if ($count == 3){   //  pr($content);    
                                       $news['desc'] .= $desc['contents'] . " <br /><br /> ";
                                       break;
                                    }
                                    $count++;
                                } */
                                else if (strpos($news['url'], "asir.net") !== FALSE){
                                   if ($count == 2){   //  pr($content);    
                                       $news['desc'] .= $desc['contents'] . " <br /><br /> ";
                                       break;
                                   }
                                   $count++;
                                }
                                else if (strpos($news['url'], "kaahe.org/ar/index") !== FALSE) { 
                                    if (
                                         strpos($content['contents'], "SOURCES:") !== FALSE ||
                                         strpos($content['contents'], "Copyright") !== FALSE ||
                                         strpos($content['contents'], "للتحقق. HONcode نحن نلتزم بمبادئ ميثاق") !== FALSE ||
                                         strpos($content['contents'], "مت الترجمة بواسطة الفريق العلمي لموسوعة") !== FALSE 
                                       )
                                       {
                                           continue;
                                       }
                                       else{
                                           $news['desc'] .= $desc['contents'] . " <br /><br /> "; 
                                       }
                                }
                                else if (strpos($news['url'], "adpolice.gov.ae") !== FALSE){   
                                   if ($count == 0){  
                                       $count++;      
                                       continue;          
                                   }
                                   else{          
                                       $news['desc'] .= $desc['contents'] . " <br /><br /> ";
                                   }
                                   $count++;
                                }
                                elseif (strpos($news['url'], "goal.com") !== FALSE || 
                                        strpos($news['url'], "dw.de") !== FALSE ||
                                        strpos($news['url'], "dw.com") !== FALSE ||
                                        strpos($news['url'], "aleqt.com") !== FALSE ||
                                        strpos($news['url'], "almaydan2.net") !== FALSE ||
                                        strpos($news['url'], "alliraqnews.com") !== FALSE ||
                                        strpos($news['url'], "hawaaworld.com") !== FALSE ||
                                        strpos($news['url'], "attounissia.com.tn") !== FALSE ||
                                        strpos($news['url'], "ahram.org.eg") !== FALSE ||
                                        strpos($news['url'], "fcbarcelona.com") !== FALSE ||
                                        strpos($news['url'], "al-mashhad.com") !== FALSE ||
                                        strpos($news['url'], "echoroukonline.com") !== FALSE ||
                                        strpos($news['url'], "steelbeauty.net") !== FALSE ||
                                        strpos($news['url'], "sayidaty.net") !== FALSE ||
                                        strpos($news['url'], "fashion4arab.com") !== FALSE ||
                                        strpos($news['url'], "hihi2.com") !== FALSE ||
                                        strpos($news['url'], "th3professional.com") !== FALSE ||
                                        strpos($news['url'], "annahar.com") !== FALSE ||
                                        strpos($news['url'], "hashtagarabi.com") !== FALSE ||
                                        strpos($news['url'], "ounousa.com") !== FALSE ||
                                        strpos($news['url'], "wafa.com.sa") !== FALSE ||
                                        strpos($news['url'], "snobonline.net") !== FALSE ||
                                        strpos($news['url'], "euronews.com") !== FALSE ||
                                        strpos($news['url'], "wonews.net") !== FALSE ||
                                        strpos($news['url'], "lahamag.com") !== FALSE ||
                                        strpos($news['url'], "arabapps.org") !== FALSE ||
                                        strpos($news['url'], "arabhardware.net") !== FALSE ||
                                        strpos($news['url'], "android4ar.com") !== FALSE ||
                                        strpos($news['url'], "wikise7a.com") !== FALSE ||
                                        strpos($news['url'], "ardroid.com") !== FALSE ||
                                        strpos($news['url'], "techplus.me") !== FALSE ||
                                        strpos($news['url'], "ashorooq.net") !== FALSE ||
                                        strpos($news['url'], "doniatech.com") !== FALSE ||
                                        strpos($news['url'], "arabitechnomedia.com") !== FALSE ||
                                        strpos($news['url'], "alquds.com") !== FALSE ||
                                        strpos($news['url'], "arabi21.com") !== FALSE ||
                                        strpos($news['url'], "hyperstage.net") !== FALSE ||
                                        strpos($news['url'], "aljoumhouria.com") !== FALSE ||
                                        strpos($news['url'], "arabic.sport360.com") !== FALSE ||
                                        strpos($news['url'], "manchestercityfc.ae") !== FALSE ||
                                        strpos($news['url'], "lana-news.ly") !== FALSE ||
                                        strpos($news['url'], "alarab.qa") !== FALSE ||
                                        strpos($news['url'], "beinsports.com") !== FALSE ||
                                        strpos($news['url'], "linkis.com") !== FALSE ||
                                        strpos($news['url'], "arriyadiyah.com") !== FALSE ||
                                        strpos($news['url'], "kooora.com") !== FALSE ||
                                        strpos($news['url'], "kooora2.com") !== FALSE ||
                                        strpos($news['url'], "sudanmotion.com") !== FALSE ||
                                        strpos($news['url'], "alnilin.com") !== FALSE ||
                                        strpos($news['url'], "libyanow.net.ly") !== FALSE ||
                                        strpos($news['url'], "akhbar-alkhaleej.com") !== FALSE ||
                                        strpos($news['url'], "tuniscope.com") !== FALSE ||
                                        strpos($news['url'], "alwatannews.net") !== FALSE ||
                                        strpos($news['url'], "hibapress.com") !== FALSE ||
                                        strpos($news['url'], "akhbarlibya24.net") !== FALSE ||
                                        strpos($news['url'], "bahrainalyoum.net") !== FALSE ||
                                        strpos($news['url'], "alwefaq.net") !== FALSE ||
                                        strpos($news['url'], "bna.bh") !== FALSE ||
                                        strpos($news['url'], "hespress.com") !== FALSE ||
                                        strpos($news['url'], "france24.com") !== FALSE ||
                                        strpos($news['url'], "bahrainmirror.no-ip.info") !== FALSE ||
                                        strpos($news['url'], "al-sharq.com") !== FALSE ||
                                        strpos($news['url'], "assafir.com") !== FALSE ||
                                        strpos($news['url'], "tounesnews.com") !== FALSE ||
                                        strpos($news['url'], "yen-news.com") !== FALSE ||
                                        strpos($news['url'], "lebanondebate.com") !== FALSE ||
                                        strpos($news['url'], "alkhabarnow.net") !== FALSE ||
                                        strpos($news['url'], "marebpress.net") !== FALSE ||
                                        strpos($news['url'], "yemen-press.com") !== FALSE ||
                                        strpos($news['url'], "yemenat.net") !== FALSE ||
                                        strpos($news['url'], "assabeel.net") !== FALSE ||
                                        strpos($news['url'], "suhailnews.blogspot.com") !== FALSE ||
                                        strpos($news['url'], "qudspress.com") !== FALSE ||
                                        strpos($news['url'], "watn-news.com") !== FALSE ||
                                        strpos($news['url'], "ammonnews.net") !== FALSE ||
                                        strpos($news['url'], "azzaman.com") !== FALSE ||
                                        strpos($news['url'], "palsawa.com") !== FALSE ||
                                        strpos($news['url'], "q8news.com") !== FALSE ||
                                        strpos($news['url'], "adhamiyahnews.com") !== FALSE ||
                                        strpos($news['url'], "alhasela.com") !== FALSE ||
                                        strpos($news['url'], "alsawt.net") !== FALSE ||
                                        strpos($news['url'], "makkahnewspaper.com") !== FALSE ||
                                        strpos($news['url'], "paltoday.ps") !== FALSE ||
                                        strpos($news['url'], "albawabhnews.com") !== FALSE ||
                                        strpos($news['url'], "alarabiya.net") !== FALSE ||
                                        strpos($news['url'], "aliraqnews.com") !== FALSE ||
                                        strpos($news['url'], "hattpost.com") !== FALSE ||
                                        strpos($news['url'], "alhurra.com") !== FALSE ||
                                        strpos($news['url'], "kuwaitnews.com") !== FALSE ||
                                        strpos($news['url'], "al-balad.net") !== FALSE ||
                                        strpos($news['url'], "alaraby.co.uk") !== FALSE ||
                                        strpos($news['url'], "3seer.net") !== FALSE ||
                                        strpos($news['url'], "alquds.co.uk") !== FALSE ||
                                        strpos($news['url'], "fath-news.com") !== FALSE ||
                                        strpos($news['url'], "shorouknews.com") !== FALSE ||
                                        strpos($news['url'], "skynewsarabia.com") !== FALSE ||
                                        strpos($news['url'], "middle-east-online.com") !== FALSE ||
                                        strpos($news['url'], "akhbarak.net") !== FALSE ||
                                        strpos($news['url'], "masralarabia.com") !== FALSE ||
                                        strpos($news['url'], "anaween.com") !== FALSE ||
                                        strpos($news['url'], "al-akhbar.com") !== FALSE ||
                                        strpos($news['url'], "rudaw.net") !== FALSE ||
                                        strpos($news['url'], "pal24.net") !== FALSE ||
                                        strpos($news['url'], "alhayat.com") !== FALSE ||
                                        strpos($news['url'], "akherkhabaronline.com") !== FALSE ||
                                        strpos($news['url'], "alborsanews.com") !== FALSE ||
                                        strpos($news['url'], "alikhbaria.com") !== FALSE ||
                                        strpos($news['url'], "anbaaonline.com") !== FALSE ||
                                        strpos($news['url'], "arn.ps") !== FALSE ||
                                        strpos($news['url'], "omannews.gov.om") !== FALSE ||
                                        strpos($news['url'], "elfagr.org") !== FALSE ||
                                        strpos($news['url'], "almayadeen.net") !== FALSE ||
                                        strpos($news['url'], "fifa.com") !== FALSE ||
                                        strpos($news['url'], "arab4x4.com") !== FALSE ||
                                        strpos($news['url'], "alkhabarsport.com") !== FALSE ||
                                        strpos($news['url'], "safa.ps") !== FALSE ||
                                        strpos($news['url'], "alkhabarkw.com") !== FALSE ||
                                        strpos($news['url'], "almowaten.net") !== FALSE ||
                                        strpos($news['url'], "almustaqbal.com") !== FALSE ||
                                        strpos($news['url'], "youm7.com") !== FALSE ||
                                        strpos($news['url'], "felesteen.ps") !== FALSE ||
                                        strpos($news['url'], "layalina.com") !== FALSE ||
                                        strpos($news['url'], "lebanonfiles.com") !== FALSE ||
                                        strpos($news['url'], "zamanarabic.com") !== FALSE ||
                                        strpos($news['url'], "nas.sa") !== FALSE ||
                                        strpos($news['url'], "alwasat.com.kw") !== FALSE ||
                                        //strpos($news['url'], "atyabtabkha.3a2ilati.com") !== FALSE ||
                                        strpos($news['url'], "saidaonline.com") !== FALSE ||
                                        strpos($news['url'], "nok6a.net") !== FALSE ||
                                        strpos($news['url'], "arabic.cnn.com") !== FALSE ||
                                        strpos($news['url'], "ng4a.com") !== FALSE ||
                                        strpos($news['url'], "alkhaleejonline.net") !== FALSE ||
                                        strpos($news['url'], "dostor.org") !== FALSE ||
                                        strpos($news['url'], "almotamar.net") !== FALSE ||
                                        strpos($news['url'], "qna.org.qa") !== FALSE ||
                                        strpos($news['url'], "euronews") !== FALSE ||
                                        strpos($news['url'], "3alyoum.com") !== FALSE ||
                                        strpos($news['url'], "autosearch.me") !== FALSE ||
                                        strpos($news['url'], "n1t1.com") !== FALSE ||
                                        strpos($news['url'], "arabsturbo.com") !== FALSE ||
                                        strpos($news['url'], "elfann.com") !== FALSE ||
                                        strpos($news['url'], "qabaq.com") !== FALSE ||
                                        strpos($news['url'], "android-time.com") !== FALSE ||
                                        strpos($news['url'], "anazahra.com") !== FALSE ||
                                        //strpos($news['url'], "manalonline.com") !== FALSE ||
                                        strpos($news['url'], "arbdroid.com") !== FALSE ||
                                        strpos($news['url'], "goodykitchen.com") !== FALSE ||
                                        strpos($news['url'], "q8ping.com") !== FALSE ||
                                        strpos($news['url'], "shahiya.com") !== FALSE ||
                                        //strpos($news['url'], "fatafeat.com") !== FALSE ||
                                        strpos($news['url'], "forbesmiddleeast.com") !== FALSE ||
                                        strpos($news['url'], "sea7htravel.com") !== FALSE ||
                                        strpos($news['url'], "alayam.com") !== FALSE ||
                                        strpos($news['url'], "yumyume.com") !== FALSE ||
                                        strpos($news['url'], "hiamag.com") !== FALSE ||
                                        strpos($news['url'], "akhbarelyom.com") !== FALSE ||
                                        strpos($news['url'], "mubasher.info") !== FALSE ||
                                        strpos($news['url'], "alittihad.ae") !== FALSE ||
                                        strpos($news['url'], "ismailyonline.com") !== FALSE ||
                                        strpos($news['url'], "olympic.qa") !== FALSE ||
                                        strpos($news['url'], "masrawy.com") !== FALSE ||
                                        strpos($news['url'], "al-gornal.com") !== FALSE ||
                                        strpos($news['url'], "cdn.alkass.net") !== FALSE ||
                                        strpos($news['url'], "buyemen.com") !== FALSE ||
                                        strpos($news['url'], "zamalekfans.com") !== FALSE ||
                                        strpos($news['url'], "alahlyegypt.com") !== FALSE ||
                                        strpos($news['url'], "alriadey.com") !== FALSE ||
                                        strpos($news['url'], "elheddaf.com") !== FALSE ||
                                        strpos($news['url'], "alwasatnews.com") !== FALSE ||
                                        strpos($news['url'], "goalna.com") !== FALSE ||
                                        strpos($news['url'], "elwatannews.com") !== FALSE ||
                                        strpos($news['url'], "al-jazirah.com") !== FALSE ||
                                        strpos($news['url'], "sabqq.org") !== FALSE ||
                                        strpos($news['url'], "sport.ahram.org") !== FALSE ||
                                        strpos($news['url'], "tracksport.net") !== FALSE ||
                                        strpos($news['url'], "tayyar.org") !== FALSE ||
                                        strpos($news['url'], "elaph.com") !== FALSE ||
                                        strpos($news['url'], "Elaph") !== FALSE ||
                                        strpos($news['url'], "alriyadh.com") !== FALSE ||
                                        strpos($news['url'], "alsopar.com") !== FALSE ||
                                        strpos($news['url'], "ittinews.net") !== FALSE ||
                                        strpos($news['url'], "elaph") !== FALSE ||
                                        strpos($news['url'], "realmadrid.com") !== FALSE ||
                                        strpos($news['url'], "ar.beinsports.net") !== FALSE ||
                                        strpos($news['url'], "GalerieArtciles") !== FALSE ||
                                        strpos($news['url'], "hilalcom.net") !== FALSE ||
                                        strpos($news['url'], "filgoal.com") !== FALSE ||
                                        strpos($news['url'], "yallakora.com") !== FALSE ||
                                        strpos($news['url'], "mbc.net") !== FALSE ||
                                        strpos($news['url'], "aljazeera.net") !== FALSE ||
                                        strpos($news['url'], "ajialq8.com") !== FALSE ||
                                        strpos($news['url'], "oleeh.com") !== FALSE ||
                                        strpos($news['url'], "alshamiya-news.com") !== FALSE ||
                                        strpos($news['url'], "alrayalaam.com") !== FALSE ||
                                        strpos($news['url'], "alkoutnews.net") !== FALSE ||
                                        strpos($news['url'], "almashhad.net") !== FALSE ||
                                        strpos($news['url'], "alsawtnews.cc") !== FALSE ||
                                        strpos($news['url'], "acakuw.com") !== FALSE ||
                                        strpos($news['url'], "dasmannews.com") !== FALSE ||
                                        strpos($news['url'], "reqaba.com") !== FALSE ||
                                        strpos($news['url'], "aldostornews.com") !== FALSE ||
                                        strpos($news['url'], "annaharkw.com") !== FALSE ||
                                        strpos($news['url'], "altaleea.com") !== FALSE ||
                                        strpos($news['url'], "filwajiha.com") !== FALSE ||
                                        strpos($news['url'], "arabesque.tn") !== FALSE ||
                                        strpos($news['url'], "alhakea.com") !== FALSE ||
                                        strpos($news['url'], "kuna.net.kw") !== FALSE ||
                                        strpos($news['url'], "ennaharonline.com") !== FALSE ||
                                        strpos($news['url'], "alanba.com.kw") !== FALSE ||
                                        strpos($news['url'], "elbilad.net") !== FALSE ||
                                        strpos($news['url'], "alkuwaityah.com") !== FALSE ||
                                        strpos($news['url'], "Alkuwaityah.com") !== FALSE ||
                                        strpos($news['url'], "al-seyassah.com") !== FALSE ||
                                        strpos($news['url'], "chouftv.ma") !== FALSE ||
                                        strpos($news['url'], "mmaqara2t.com") !== FALSE ||
                                        strpos($news['url'], "tnntunisia.com") !== FALSE ||
                                        strpos($news['url'], "annaharnews.net") !== FALSE ||
                                        strpos($news['url'], "moroccoeyes") !== FALSE ||
                                        strpos($news['url'], "babnet.net") !== FALSE ||
                                        strpos($news['url'], "atheer.om") !== FALSE ||
                                        strpos($news['url'], "alyaoum24.com") !== FALSE ||
                                        strpos($news['url'], "le360.ma") !== FALSE ||
                                     //   strpos($news['url'], "moheet.com") !== FALSE ||
                                        strpos($news['url'], "tunisien.tn") !== FALSE ||
                                        strpos($news['url'], "shabiba.com") !== FALSE ||
                                        strpos($news['url'], "alforatnews.com") !== FALSE ||
                                        strpos($news['url'], "alwatan.com") !== FALSE ||
                                        strpos($news['url'], "tounessna.info") !== FALSE ||
                                        strpos($news['url'], "futuretvnetwork.com") !== FALSE ||
                                        strpos($news['url'], "zoomtunisia.tn") !== FALSE ||
                                        strpos($news['url'], "lbcgroup.tv") !== FALSE ||
                                        strpos($news['url'], "omandaily.om") !== FALSE ||
                                        strpos($news['url'], "al-watan.com") !== FALSE ||
                                        strpos($news['url'], "nna-leb.gov.lb") !== FALSE ||
                                        strpos($news['url'], "moe.gov.qa") !== FALSE ||
                                        strpos($news['url'], "orient-news.net") !== FALSE ||
                                        strpos($news['url'], "otv.com.lb") !== FALSE ||
                                        strpos($news['url'], "syrianow.sy") !== FALSE ||
                                        strpos($news['url'], "almanar.com.lb") !== FALSE ||
                                        strpos($news['url'], "lebwindow.net") !== FALSE ||
                                        strpos($news['url'], "sahelmaten.com") !== FALSE ||
                                        strpos($news['url'], "basnews.com") !== FALSE ||
                                        strpos($news['url'], "o-t.tv") !== FALSE ||
                                        strpos($news['url'], "iraqdirectory.com") !== FALSE ||
                                        strpos($news['url'], "assawsana.com") !== FALSE ||
                                        strpos($news['url'], "alnoornews.net") !== FALSE ||
                                        strpos($news['url'], "etilaf.org") !== FALSE ||
                                        strpos($news['url'], "elshaab.org") !== FALSE ||
                                        strpos($news['url'], "alrafidain.org") !== FALSE ||
                                        strpos($news['url'], "alroeya.ae") !== FALSE ||
                                        strpos($news['url'], "hroobnews.com") !== FALSE ||
                                        strpos($news['url'], "argaam.com") !== FALSE ||
                                        strpos($news['url'], "wam.ae") !== FALSE ||
                                        strpos($news['url'], "albayan.ae") !== FALSE ||
                                        strpos($news['url'], "royanews.tv") !== FALSE ||
                                        strpos($news['url'], "almesryoon.com") !== FALSE ||
                                        strpos($news['url'], "yanair.net") !== FALSE ||
                                        strpos($news['url'], "almogaz.com") !== FALSE ||
                                        strpos($news['url'], "egyptiannews.net") !== FALSE ||
                                        strpos($news['url'], "maqar.com") !== FALSE ||
                                        strpos($news['url'], "alamalmal.net") !== FALSE ||
                                        strpos($news['url'], "7iber.com") !== FALSE ||
                                        strpos($news['url'], "el-balad.com") !== FALSE ||
                                        strpos($news['url'], "jn-news.com") !== FALSE ||
                                        strpos($news['url'], "jo24.net") !== FALSE ||
                                        strpos($news['url'], "wikise7a") !== FALSE ||
                                        strpos($news['url'], "addustour.com") !== FALSE ||
                                        strpos($news['url'], "klmty.net") !== FALSE ||
                                        strpos($news['url'], "alarabalyawm.net") !== FALSE ||
                                        strpos($news['url'], "rotanamags.net") !== FALSE ||
                                        strpos($news['url'], "kaahe.org") !== FALSE ||
                                        strpos($news['url'], "lahaonline.com") !== FALSE ||
                                        strpos($news['url'], "3eesho.com") !== FALSE ||
                                        strpos($news['url'], "almaghribtoday.net") !== FALSE ||
                                        strpos($news['url'], "dailymedicalinfo.com") !== FALSE ||
                                        strpos($news['url'], "almasryalyoum.com") !== FALSE ||
                                        strpos($news['url'], "arabi21.com") !== FALSE ||
                                        strpos($news['url'], "ham-24.com") !== FALSE ||
                                        strpos($news['url'], "al-madina.com") !== FALSE ||
                                        strpos($news['url'], "anbaanews.com") !== FALSE ||
                                        strpos($news['url'], "almuraba.net") !== FALSE ||
                                        strpos($news['url'], "aldawadmi.net") !== FALSE ||
                                        strpos($news['url'], "hafralbaten.com") !== FALSE ||
                                        strpos($news['url'], "naseej.net") !== FALSE ||
                                        strpos($news['url'], "newsqassim.com") !== FALSE ||
                                        strpos($news['url'], "nwafecom.net") !== FALSE ||
                                        strpos($news['url'], "adwaalwatan.com") !== FALSE ||
                                        strpos($news['url'], "ajel.sa") !== FALSE ||
                                        strpos($news['url'], "fajr.sa") !== FALSE ||
                                        strpos($news['url'], "arjja.com") !== FALSE ||
                                        strpos($news['url'], "aljubailtoday.com.sa") !== FALSE ||
                                        strpos($news['url'], "twasul.info") !== FALSE ||
                                        strpos($news['url'], "spa.gov.sa") !== FALSE ||
                                        strpos($news['url'], "mini-news.net") !== FALSE ||
                                        strpos($news['url'], "almjardh.com") !== FALSE ||
                                        strpos($news['url'], "rasdnews.net") !== FALSE ||
                                        strpos($news['url'], "aljouf-news.com") !== FALSE ||
                                        strpos($news['url'], "zahran.org") !== FALSE ||
                                        strpos($news['url'], "alshahedkw.com") !== FALSE ||
                                        strpos($news['url'], "tabuk-news.com") !== FALSE ||
                                        strpos($news['url'], "alkhaleejaffairs.org") !== FALSE ||
                                        strpos($news['url'], "asir.com") !== FALSE ||
                                        strpos($news['url'], "rsssd.com") !== FALSE ||
                                        strpos($news['url'], "roaanews.net") !== FALSE ||
                                        strpos($news['url'], "kharjhome.com") !== FALSE ||
                                        strpos($news['url'], "asir.net_xxxxxx") !== FALSE ||
                                        strpos($news['url'], "cma.org.sa") !== FALSE ||
                                        strpos($news['url'], "baareq.com.sa") !== FALSE ||
                                        strpos($news['url'], "saso.gov.sa") !== FALSE ||
                                        strpos($news['url'], "freeswcc.com") !== FALSE ||
                                        strpos($news['url'], "moh.gov.sa") !== FALSE ||
                                        strpos($news['url'], "reuters.com") !== FALSE ||
                                        strpos($news['url'], "sabq.org") !== FALSE ||
                                        strpos($news['url'], "raialyoum.com") !== FALSE ||
                                        strpos($news['url'], "alsharq.net.sa") !== FALSE ||
                                        strpos($news['url'], "ar.yabiladies.com") !== FALSE ||
                                        strpos($news['url'], "paltimes.net") !== FALSE ||
                                        strpos($news['url'], "palinfo.com") !== FALSE ||
                                        strpos($news['url'], "electrony.net") !== FALSE ||
                                        strpos($news['url'], "electronynet") !== FALSE ||
                                        strpos($news['url'], "naba.ps") !== FALSE ||
                                        strpos($news['url'], "alkhaleej.ae") !== FALSE ||
                                        strpos($news['url'], "alwasat.ly") !== FALSE ||
                                        strpos($news['url'], "alrakoba.net") !== FALSE ||
                                        strpos($news['url'], "charlesayoub.com") !== FALSE ||
                                        strpos($news['url'], "aljadeed.tv") !== FALSE ||
                                        strpos($news['url'], "aawsat.com") !== FALSE ||
                                        strpos($news['url'], "dotmsr.com") !== FALSE ||
                                        strpos($news['url'], "alqabas.com.kw") !== FALSE ||
                                        strpos($news['url'], "aljarida.com") !== FALSE ||
                                        strpos($news['url'], "sabr.cc") !== FALSE ||
                                        strpos($news['url'], "alwatan.kuwait.tt") !== FALSE ||
                                        strpos($news['url'], "alraimedia.com") !== FALSE ||
                                        strpos($news['url'], "14march.org") !== FALSE 
                                        ) {
                                    if ($count == 0){   // echo ('kkkkkkkkkkkkkkkkkkkkkkkkk');pr($desc);  exit;
                                       $news['desc'] .= $desc['contents'] . " <br /><br /> ";
                                       break;
                                    }
                                    $count++;
                                }
                               /* elseif (strpos($news['url'], "goal.com") !== FALSE
                                        ) {
                                    if ($count == 0){   //  pr($content);    
                                       $news['desc'] .= $desc['contents'] . " <br /><br /> ";
                                       break;
                                    }
                                    $count++;
                                }   */
                                else if ( strpos($news['url'], 'alalam.ir') !== FALSE) {    
                                    if ($count == 2) {
                                       if (strpos($desc['contents'], 'Shadowbox.init') !== FALSE) {
                                           $no_text1 = true;
                                           $count++;  
                                           
                                           continue;
                                       }
                                   } 
                                    elseif ($count == 3) {
                                       if (strpos($desc['contents'], 'Shadowbox.init') !== FALSE) {
                                           $no_text = true;
                                           $count++;  
                                           
                                           continue;
                                       }
                                   } 
                                   else if ($count == 3 && isset($no_text1)) {
                                       $news['desc'] = '';
                                       $news['desc'] .= $desc['contents'] . " <br /><br /> ";
                                       break;
                                   }
                                   else if ($count == 4 && isset($no_text)) {
                                       $news['desc'] = '';
                                       $news['desc'] .= $desc['contents'] . " <br /><br /> ";
                                       break;
                                   }
                                   else{
                                       $news['desc'] .= $desc['contents'] . " <br /><br /> "; 
                                   }
                                   
                                   $count++;
                               }
                                else if (strpos($news['url'], "yemen-press.net") !== FALSE || 
                                        // strpos($news['url'], "alaraby.co.uk") !== FALSE ||
                                         strpos($news['url'], "ajmanpolice.gov") !== FALSE ||
                                         strpos($news['url'], "alsumaria.tv") !== FALSE 
                                         ) { 
                                   if ($count == 0){   //  pr($content);    
                                       $news['desc'] .= $desc['contents'] . " <br /><br /> ";
                                       break;
                                   }
                                   $count++; 
                               }
                               else if (strpos($news['url'], "almayadeen.net") !== FALSE) { 
                                  /* if ($count == 3){   //  pr($content);    
                                       $news['desc'] .= $content['contents'] . " <br /><br /> ";
                                       break;
                                   }
                                   $counter++;  */
                                  /* if (strpos($news['url'], "/news/") !== FALSE) { 
                                        $news['desc'] .= $desc['contents'] . " <br /><br /> ";  
                                   }
                                   else{
                                      $news['desc'] = "";
                                   } */
                               }
                               else {
                                    $news['desc'] .= $desc['contents'] . " <br /><br /> ";
                               }
                                
                                $count++;
                            }
                        }
                    }
                    
                    if (strpos($news['url'], 'rsssd.com') !== false ) {
                        $news['title'] = $data['title'];
                    }
                    if (strpos($news['url'], 'kharjhome.com') !== false) {
                        $data['title'] = iconv('windows-1256', 'UTF-8', $data['title']);
                        $news['title'] = $data['title'];
                    }
                       echo('......................................>>>>>>>>>>');  pr($news);     //  exit; 
                    if (strpos($news['url'], 'fb.me') !== false || strpos($news['url'], 'facebook.com') !== false) {
                        $news['desc'] = "go to fb";
                        $news['image'] = "";
                    } 
                    else if (strpos($news['url'], 'media.5d3a.com') !== false /*|| strpos($news['url'], 'wikise7a.com') !== false*/) {
                        $news['desc'] = "go to fb";
                    }
                    else if (strpos($news['url'], 'altibbi.com') !== false) {
                        $news['desc'] = "go to fb";
                    }
                    else if (strpos($news['url'], '/vb/') !== false) {
                        $news['desc'] = "go to fb";
                    }
                    else if (strpos($news['url'], 'dmi.ae/samadubai') !== false) {
                        $news['desc'] = "go to fb";
                    }
                    else if (strpos($news['url'], 'alg360.com') !== false) {
                        $news['desc'] = "go to fb";
                    }
                    else if (strpos($news['url'], 'instagram.com') !== false) {
                        $news['desc'] = "go to fb";
                    }
                    else if (strpos($news['url'], 'zakatfund.gov.ae') !== false || strrpos($reversed_url,"zf.ae") !== FALSE) {
                        $news['desc'] = "go to fb";
                    }
                    else if (strpos($news['url'], 'ittisport.com') !== false) {
                        $news['desc'] = "go to fb";
                    }
                    else if (strpos($news['url'], 'dcndigital.ae') !== false) {
                        $news['desc'] = "go to fb";
                    }
                    else if (strpos($news['url'], 'youtube.com') !== false || strpos($news['url'], 'youtu.be') !== false) {
                        $news['desc'] = "go to youtube";
                        $news['image'] = "";
                    }
                    else if (strpos($news['url'], 'audio.islamweb.net') !== false || strpos($news['url'], 'youtu.be') !== false) {
                        $news['desc'] = "go to islamweb";
                    }
                    else if (strpos($news['url'], 'vine.co') !== false) {
                        $news['desc'] = "go to fb";
                    }
                    else if (strpos($news['url'], 'fitnessyard.com/registration') !== false) {
                        $news['desc'] = "";
                    }
                    else if (strpos($news['url'], 'fitnessyard.com/workout/exercise-directory') !== false) {
                        $news['desc'] = "";
                    }
                    else if (strpos($news['url'], '3eesho.com/articles/browse/category') !== false) {
                        $news['desc'] = "";
                    }
                    else if (strpos($news['url'], '.pdf') !== false) {
                        $news['desc'] = "go to pdf";
                    } else if (strpos($news['url'], 'twitter.com') !== false) {
                        continue;
                    } 
                }
                else{
                    $news['desc'] = 'url not set';
                }             //   exit;
               
                //echo('......................................>>>>>>>>>>');  pr($news['desc']);       exit;
                
                save_news($news, $data, $reversed_url);
            }
            
            echo('<br />-----------------------------------------------------------------------------<br />');  
            
            //pr($news);
            
            sleep(5);
        }  
    }
}
       
function trying_to_fill_body() {
    global $conn;
             
    $query = "select news_url, reversed_url, twitter_news_id, title
                from articles_html  
                where (TRIM(TRAILING '\n' FROM body) = '' or trim(body) = '' or body is null) and reversed_url <> '' and 
                news_url <> 'h' and news_url <> '' and news_url <> 'Array' and reversed_url not like '%kooora%' 
                and reversed_url not like '%<error>Connection timeout</error>%' order by id asc
                limit 322";
                
    $res = $conn->db_query($query);
    
    while($row = $conn->fetch_assoc($res)) {
        //pr($row); 
        
        if ($row['reversed_url'] != "http://www.goal.com/") {        
            $data = fetch($row['reversed_url']); 
        }
        else{
            $data['paragraph']['contents'] = '';
        }
        
        pr($data);
             //exit;
        $desc = $alt_desc = '';
        
        if (isset($data['paragraph'])) {   // pr($data);
            if (is_array($data['paragraph'])) {
                $counter = 0;
                                                            
                foreach($data['paragraph'] as $content) {  //echo($content['contents']);    exit;
                    if (isset($content['contents'])) {   
                        if ( trim(@$content['contents']) != "" && 
                            strpos($content['contents'], 'المعذرة ، لقد حدث خطأ ما') === FALSE &&
                            strpos($content['contents'], 'الصفحة المطلوبة غير موجودة') === FALSE &&
                            strpos($content['contents'], 'هذه الصفحة غير متاحة حالياً') === FALSE &&
                            strpos($content['contents'], 'مميزات والخيارات المتاحة فقط للأعضاء') === FALSE && //maareb
                            strpos($content['contents'], 'لقد حدث خطأ في استخدامك لنظام التصفح') === FALSE &&
                            strpos($content['contents'], '404اتصل بنا شروط الاستخدام عن الموقع خدمة الرسائل بوابة الشروق 2015 جميع الحقوق محفوظة') === FALSE &&
                            strpos($content['contents'], '404 NOT FOUND') === FALSE &&
                            strpos($content['contents'], 'getElementById') === FALSE &&
                            strpos($content['contents'], 'getElementsBy') === FALSE &&
                            strpos($content['contents'], 'addThumbnail') === FALSE &&
                            strpos($content['contents'], 'updateEmailTo') === FALSE &&
                            strpos($content['contents'], 'TWTR.Widget') === FALSE &&
                            strpos($content['contents'], 'Your browser will redirect to your requested content shortly') === FALSE &&
                            strpos($content['contents'], 'Completing the CAPTCHA proves you are a human and gives') === FALSE &&
                            strpos($content['contents'], 'siteheadersponsorship') === FALSE &&
                            strpos($content['contents'], 'Adobe Flash Player') === FALSE &&
                            strpos($content['contents'], 'Flash Player') === FALSE &&
                            strpos($content['contents'], 'ContentType: Video') === FALSE &&
                            strpos($content['contents'], 'اسم المستخدم الى بريدك الالكتروني') === FALSE &&
                            strpos($content['contents'], 'window') === FALSE &&
                            strpos($content['contents'], 'password') === FALSE &&
                            strpos($content['contents'], 'الأخبار العاجلةالأولىأول') === FALSE &&
                            strpos($content['contents'], 'قم بالتسجيل') === FALSE &&
                            strpos($content['contents'], 'Password') === FALSE &&
                            strpos($content['contents'], 'مواقع شبكة الجزيرة: الجزيرة') === FALSE &&
                            strpos($content['contents'], 'document') === FALSE &&
                            strpos($content['contents'], 'LinkBacks') === FALSE &&
                            strpos($content['contents'], 'jQuery(function($)') === FALSE &&
                            strpos($content['contents'], 'jQuery') === FALSE &&
                            strpos($content['contents'], 'function') === FALSE &&
                            strpos($content['contents'], 'Rights') === FALSE &&
                            strpos($content['contents'], 'rights') === FALSE &&
                            strpos($content['contents'], 'copyright') === FALSE &&
                            strpos($content['contents'], 'Copyright') === FALSE &&
                            strpos($content['contents'], 'Copyrights') === FALSE &&
                            strpos($content['contents'], 'ترحب شبكة cnn') === FALSE &&
                            strpos($content['contents'], 'ننصحك بمراجعة') === FALSE &&
                            strpos($content['contents'], 'Cable News Network') === FALSE &&
                            strpos($content['contents'], 'All Rights Reserved') === FALSE &&
                            strpos($content['contents'], 'copyrights') === FALSE &&
                            strpos($content['contents'], '404 NOTFOUND') === FALSE &&
                            strpos($content['contents'], 'NOT FOUND') === FALSE
                            
                           ) {              
                               //    echo('<br />is: ' . strrpos($row['reversed_url'],"tracksport.net") . '<br />');
                               if(strrpos($row['reversed_url'],"kora.com") !== FALSE) {
                                   $desc .= $content['contents'] . " <br /><br /> ";
                                   break;
                               }      
                               else if(strrpos($row['reversed_url'],"tracksport.net") !== FALSE /*|| strrpos($row['reversed_url'],"marebpress.net") !== FALSE*/) {  //echo('<br />$counter: ' . $counter . '<br />'); 
                                  // echo(count($data['paragraph']));exit;
                                   if (count($data['paragraph']) == 1) {
                                       $desc .= $content['contents'] . " <br /><br /> ";
                                       break;
                                   }
                                   else {
                                       if (strpos($content['contents'], 'يمكنك الآن الإضافة المباشرة للتعليقات، وعدد كبير من المميزات والخيارات المتاحة فقط للأعضاء') === FALSE) {
                                            $desc .= $content['contents'] . " <br /><br /> ";
                                            break;    
                                       }
                                       else{
                                           if ($counter == 1){   //  pr($content);  
                                                $desc .= $content['contents'] . " <br /><br /> ";
                                                break;
                                           }
                                       }
                                       $counter++;
                                   }
                               }
                               else if(/*strrpos($row['reversed_url'],"almotamar.net") !== FALSE ||*/ strrpos($row['reversed_url'],"barca4ever.com") !== FALSE) {  //echo('<br />$counter: ' . $counter . '<br />'); 
                                   if (is_array($content['contents'])) {
                                       if ($counter == 2){ //echo('ayman');    pr($content); exit;   
                                           $desc .= $content['contents'] . " <br /><br /> ";
                                           break;
                                       }
                                       $counter++;
                                   }
                                   else{
                                       $desc .= $content['contents'] . " <br /><br /> ";
                                   }
                               }
                               /*else if (strpos($row['reversed_url'], "aljazeera.net") !== FALSE) {
                                    $desc .= $content['contents'] . " <br /><br /> ";
                                    
                                    if ($counter == 6) break;
                                    
                                    $counter++;
                               } */
                               else if (strpos($row['reversed_url'], "yemen-press.net") !== FALSE || 
                                        strpos($row['reversed_url'], "ajmanpolice.gov") !== FALSE ||
                                       // strpos($row['reversed_url'], "alaraby.co.uk") !== FALSE ||
                                        strpos($row['reversed_url'], "alsumaria.tv") !== FALSE 
                                        ) { 
                                   if ($counter == 0){   //  pr($content);    
                                       $desc .= $content['contents'] . " <br /><br /> ";
                                       break;
                                   }
                                   $counter++;
                               } 
                               else if (strpos($row['reversed_url'], "almayadeen.net") !== FALSE) { 
                                /*   if ($counter == 3){   //  pr($content);    
                                       $desc .= $content['contents'] . " <br /><br /> ";
                                       break;
                                   }
                                   $counter++; */
                                   
                                  /* if (strpos($row['reversed_url'], "/news/") !== FALSE) {  
                                        $desc .= $content['contents'] . " <br /><br /> ";  
                                   }
                                   else{
                                       $desc = "";
                                   }    */                               
                               }
                               else if (strpos($row['reversed_url'], "24.ae") !== FALSE) { 
                                   if ($counter == 0){   //  pr($content);    
                                       $desc .= $content['contents'] . " <br /><br /> ";
                                       break;
                                   }
                                   $counter++;
                               }
                              /* else if (strpos($row['reversed_url'], "felesteen.ps") !== FALSE) { 
                                   if ($counter == 3){   //  pr($content);    
                                       $desc .= $content['contents'] . " <br /><br /> ";
                                       break;
                                   }
                                   $counter++;
                               }  */
                               else if (strpos($row['reversed_url'], "asir.net") !== FALSE){
                                   if ($counter == 2){   //  pr($content);    
                                       $desc .= $content['contents'] . " <br /><br /> ";
                                       break;
                                   }
                                   $counter++;
                               }
                               else if (strpos($row['reversed_url'], "kaahe.org/ar/index") !== FALSE) { 
                                    if (
                                         strpos($content['contents'], "SOURCES:") !== FALSE ||
                                         strpos($content['contents'], "Copyright") !== FALSE ||
                                         strpos($content['contents'], "للتحقق. HONcode نحن نلتزم بمبادئ ميثاق") !== FALSE ||
                                         strpos($content['contents'], "مت الترجمة بواسطة الفريق العلمي لموسوعة") !== FALSE 
                                       )
                                       {
                                           continue;
                                       }
                                       else{
                                           $desc .= $content['contents'] . " <br /><br /> "; 
                                       }
                                } 
                                else if (strpos($row['reversed_url'], "adpolice.gov.ae") !== FALSE){
                                   if ($counter == 0){       
                                       continue;
                                   }
                                   else{
                                       $desc .= $content['contents'] . " <br /><br /> ";
                                   }
                                   
                                   $counter++;
                                }
                               else if (strpos($row['reversed_url'], "goal.com") !== FALSE || 
                                        strpos($row['reversed_url'], "dw.de") !== FALSE ||
                                        strpos($row['reversed_url'], "dw.com") !== FALSE ||
                                        strpos($row['reversed_url'], "al-madina.com") !== FALSE ||
                                        strpos($row['reversed_url'], "ham-24.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alquds.co.uk") !== FALSE ||
                                        strpos($row['reversed_url'], "alroeya.ae") !== FALSE ||
                                        strpos($row['reversed_url'], "elaph.com") !== FALSE ||
                                        strpos($row['reversed_url'], "rudaw.net") !== FALSE ||
                                        strpos($row['reversed_url'], "lahamag.com") !== FALSE ||
                                        strpos($row['reversed_url'], "arabic.sport360.com") !== FALSE ||
                                        strpos($row['reversed_url'], "3seer.net") !== FALSE ||
                                        strpos($row['reversed_url'], "yemen-press.com") !== FALSE ||
                                        strpos($row['reversed_url'], "steelbeauty.net") !== FALSE ||
                                        strpos($row['reversed_url'], "hihi2.com") !== FALSE ||
                                        strpos($row['reversed_url'], "ounousa.com") !== FALSE ||
                                        strpos($row['reversed_url'], "wafa.com.sa") !== FALSE ||
                                        strpos($row['reversed_url'], "fashion4arab.com") !== FALSE ||
                                        strpos($row['reversed_url'], "sayidaty.net") !== FALSE ||
                                        strpos($row['reversed_url'], "fcbarcelona.com") !== FALSE ||
                                        strpos($row['reversed_url'], "techplus.me") !== FALSE ||
                                        strpos($row['reversed_url'], "snobonline.net") !== FALSE ||
                                        strpos($row['reversed_url'], "hawaaworld.com") !== FALSE ||
                                        strpos($row['reversed_url'], "skynewsarabia.com") !== FALSE ||
                                        strpos($row['reversed_url'], "wonews.net") !== FALSE ||
                                        strpos($row['reversed_url'], "wikise7a.com") !== FALSE ||
                                        strpos($row['reversed_url'], "arabapps.org") !== FALSE ||
                                        strpos($row['reversed_url'], "ardroid.com") !== FALSE ||
                                        strpos($row['reversed_url'], "arabhardware.net") !== FALSE ||
                                        strpos($row['reversed_url'], "arabitechnomedia.com") !== FALSE ||
                                        strpos($row['reversed_url'], "doniatech.com") !== FALSE ||
                                        strpos($row['reversed_url'], "android4ar.com") !== FALSE ||
                                        strpos($row['reversed_url'], "hashtagarabi.com") !== FALSE ||
                                        strpos($row['reversed_url'], "th3professional.com") !== FALSE ||
                                        strpos($row['reversed_url'], "euronews.com") !== FALSE ||
                                        strpos($row['reversed_url'], "manchestercityfc.ae") !== FALSE ||
                                        strpos($row['reversed_url'], "arabi21.com") !== FALSE ||
                                        strpos($row['reversed_url'], "libyanow.net.ly") !== FALSE ||
                                        strpos($row['reversed_url'], "hyperstage.net") !== FALSE ||
                                        strpos($row['reversed_url'], "adhamiyahnews.com") !== FALSE ||
                                        strpos($row['reversed_url'], "arriyadiyah.com") !== FALSE ||
                                        strpos($row['reversed_url'], "ashorooq.net") !== FALSE ||
                                        strpos($row['reversed_url'], "kooora.com") !== FALSE ||
                                        strpos($row['reversed_url'], "kooora2.com") !== FALSE ||
                                        strpos($row['reversed_url'], "sudanmotion.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alnilin.com") !== FALSE ||
                                        strpos($row['reversed_url'], "beinsports.com") !== FALSE ||
                                        strpos($row['reversed_url'], "tounesnews.com") !== FALSE ||
                                        strpos($row['reversed_url'], "hibapress.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alarab.qa") !== FALSE ||
                                        strpos($row['reversed_url'], "bahrainmirror.no-ip.info") !== FALSE ||
                                        strpos($row['reversed_url'], "linkis.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alwefaq.net") !== FALSE ||
                                        strpos($row['reversed_url'], "akhbar-alkhaleej.com") !== FALSE ||
                                        strpos($row['reversed_url'], "akhbarlibya24.net") !== FALSE ||
                                        strpos($row['reversed_url'], "lana-news.ly") !== FALSE ||
                                        strpos($row['reversed_url'], "annahar.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alwatannews.net") !== FALSE ||
                                        strpos($row['reversed_url'], "hespress.com") !== FALSE ||
                                        strpos($row['reversed_url'], "bahrainalyoum.net") !== FALSE ||
                                        strpos($row['reversed_url'], "bna.bh") !== FALSE ||
                                        strpos($row['reversed_url'], "suhailnews.blogspot.com") !== FALSE ||
                                        strpos($row['reversed_url'], "yen-news.com") !== FALSE ||
                                        strpos($row['reversed_url'], "tuniscope.com") !== FALSE ||
                                        strpos($row['reversed_url'], "paltoday.ps") !== FALSE ||
                                        strpos($row['reversed_url'], "yemenat.net") !== FALSE ||
                                        strpos($row['reversed_url'], "assafir.com") !== FALSE ||
                                        strpos($row['reversed_url'], "makkahnewspaper.com") !== FALSE ||
                                        strpos($row['reversed_url'], "aljoumhouria.com") !== FALSE ||
                                        strpos($row['reversed_url'], "marebpress.net") !== FALSE ||
                                        strpos($row['reversed_url'], "hattpost.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alkhabarnow.net") !== FALSE ||
                                        strpos($row['reversed_url'], "lebanondebate.com") !== FALSE ||
                                        strpos($row['reversed_url'], "qudspress.com") !== FALSE ||
                                        strpos($row['reversed_url'], "assabeel.net") !== FALSE ||
                                        strpos($row['reversed_url'], "palsawa.com") !== FALSE ||
                                        strpos($row['reversed_url'], "albawabhnews.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alsawt.net") !== FALSE ||
                                        strpos($row['reversed_url'], "alquds.com") !== FALSE ||
                                        strpos($row['reversed_url'], "ahram.org.eg") !== FALSE ||
                                        strpos($row['reversed_url'], "ammonnews.net") !== FALSE ||
                                        strpos($row['reversed_url'], "aliraqnews.com") !== FALSE ||
                                        strpos($row['reversed_url'], "q8news.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alarabiya.net") !== FALSE ||
                                        strpos($row['reversed_url'], "al-mashhad.com") !== FALSE ||
                                        strpos($row['reversed_url'], "azzaman.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alhasela.com") !== FALSE ||
                                        strpos($row['reversed_url'], "kuwaitnews.com") !== FALSE ||
                                        strpos($row['reversed_url'], "akhbarak.net") !== FALSE ||
                                        strpos($row['reversed_url'], "alhurra.com") !== FALSE ||
                                        strpos($row['reversed_url'], "al-balad.net") !== FALSE ||
                                        strpos($row['reversed_url'], "alhayat.com") !== FALSE ||
                                        strpos($row['reversed_url'], "anaween.com") !== FALSE ||
                                        strpos($row['reversed_url'], "fath-news.com") !== FALSE ||
                                        strpos($row['reversed_url'], "al-sharq.com") !== FALSE ||
                                        strpos($row['reversed_url'], "masralarabia.com") !== FALSE ||
                                        strpos($row['reversed_url'], "Elaph") !== FALSE ||
                                        strpos($row['reversed_url'], "middle-east-online.com") !== FALSE ||
                                        strpos($row['reversed_url'], "shorouknews.com") !== FALSE ||
                                        strpos($row['reversed_url'], "echoroukonline.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alkhabarsport.com") !== FALSE ||
                                        strpos($row['reversed_url'], "watn-news.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alkhabarkw.com") !== FALSE ||
                                        strpos($row['reversed_url'], "pal24.net") !== FALSE ||
                                        strpos($row['reversed_url'], "anbaaonline.com") !== FALSE ||
                                        strpos($row['reversed_url'], "omannews.gov.om") !== FALSE ||
                                        strpos($row['reversed_url'], "akherkhabaronline.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alborsanews.com") !== FALSE ||
                                        strpos($row['reversed_url'], "arn.ps") !== FALSE ||
                                        strpos($row['reversed_url'], "elfagr.org") !== FALSE ||
                                        strpos($row['reversed_url'], "al-akhbar.com") !== FALSE ||
                                        strpos($row['reversed_url'], "almayadeen.net") !== FALSE ||
                                        strpos($row['reversed_url'], "almotamar.net") !== FALSE ||
                                        strpos($row['reversed_url'], "safa.ps") !== FALSE ||
                                        strpos($row['reversed_url'], "arabsturbo.com") !== FALSE ||
                                        strpos($row['reversed_url'], "almustaqbal.com") !== FALSE ||
                                        strpos($row['reversed_url'], "zamanarabic.com") !== FALSE ||
                                        strpos($row['reversed_url'], "france24.com") !== FALSE ||
                                        strpos($row['reversed_url'], "layalina.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alkhaleejonline.net") !== FALSE ||
                                        strpos($row['reversed_url'], "alaraby.co.uk") !== FALSE ||
                                        strpos($row['reversed_url'], "autosearch.me") !== FALSE ||
                                        strpos($row['reversed_url'], "felesteen.ps") !== FALSE ||
                                        strpos($row['reversed_url'], "dostor.org") !== FALSE ||
                                        strpos($row['reversed_url'], "nas.sa") !== FALSE ||
                                        strpos($row['reversed_url'], "lebanonfiles.com") !== FALSE ||
                                        strpos($row['reversed_url'], "arabic.cnn.com") !== FALSE ||
                                        strpos($row['reversed_url'], "youm7.com") !== FALSE ||
                                        strpos($row['reversed_url'], "euronews") !== FALSE ||
                                        strpos($row['reversed_url'], "alwasat.com.kw") !== FALSE ||
                                        strpos($row['reversed_url'], "saidaonline.com") !== FALSE ||
                                        strpos($row['reversed_url'], "ng4a.com") !== FALSE ||
                                        strpos($row['reversed_url'], "qna.org.qa") !== FALSE ||
                                        strpos($row['reversed_url'], "3alyoum.com") !== FALSE ||
                                        //strpos($row['reversed_url'], "manalonline.com") !== FALSE ||
                                        strpos($row['reversed_url'], "arab4x4.com") !== FALSE ||
                                        strpos($row['reversed_url'], "nok6a.net") !== FALSE ||
                                        strpos($row['reversed_url'], "arbdroid.com") !== FALSE ||
                                        strpos($row['reversed_url'], "elfann.com") !== FALSE ||
                                        strpos($row['reversed_url'], "android-time.com") !== FALSE ||
                                       // strpos($row['reversed_url'], "atyabtabkha.3a2ilati.com") !== FALSE ||
                                        strpos($row['reversed_url'], "n1t1.com") !== FALSE ||
                                        strpos($row['reversed_url'], "elaph") !== FALSE ||
                                        strpos($row['reversed_url'], "qabaq.com") !== FALSE ||
                                        strpos($row['reversed_url'], "goodykitchen.com") !== FALSE ||
                                        strpos($row['reversed_url'], "shahiya.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alriyadh.com") !== FALSE ||
                                        strpos($row['reversed_url'], "forbesmiddleeast.com") !== FALSE ||
                                        //strpos($row['reversed_url'], "fatafeat.com") !== FALSE ||
                                        strpos($row['reversed_url'], "mubasher.info") !== FALSE ||
                                        strpos($row['reversed_url'], "q8ping.com") !== FALSE ||
                                        strpos($row['reversed_url'], "anazahra.com") !== FALSE ||
                                        strpos($row['reversed_url'], "yumyume.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alittihad.ae") !== FALSE ||
                                        strpos($row['reversed_url'], "sea7htravel.com") !== FALSE ||
                                        strpos($row['reversed_url'], "hiamag.com") !== FALSE ||
                                        strpos($row['reversed_url'], "olympic.qa") !== FALSE ||
                                        strpos($row['reversed_url'], "masrawy.com") !== FALSE ||
                                        strpos($row['reversed_url'], "al-gornal.com") !== FALSE ||
                                        strpos($row['reversed_url'], "elwatannews.com") !== FALSE ||
                                        strpos($row['reversed_url'], "akhbarelyom.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alayam.com") !== FALSE ||
                                        strpos($row['reversed_url'], "tayyar.org") !== FALSE ||
                                        strpos($row['reversed_url'], "cdn.alkass.net") !== FALSE ||
                                        strpos($row['reversed_url'], "hilalcom.net") !== FALSE ||
                                        strpos($row['reversed_url'], "buyemen.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alriadey.com") !== FALSE ||
                                        strpos($row['reversed_url'], "zamalekfans.com") !== FALSE ||
                                        strpos($row['reversed_url'], "ismailyonline.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alwasatnews.com") !== FALSE ||
                                        strpos($row['reversed_url'], "al-jazirah.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alahlyegypt.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alsopar.com") !== FALSE ||
                                        strpos($row['reversed_url'], "sabqq.org") !== FALSE ||
                                        strpos($row['reversed_url'], "tracksport.net") !== FALSE ||
                                        strpos($row['reversed_url'], "le360.ma") !== FALSE ||
                                        strpos($row['reversed_url'], "sport.ahram.org") !== FALSE ||
                                        strpos($row['reversed_url'], "realmadrid.com") !== FALSE ||
                                        strpos($row['reversed_url'], "filgoal.com") !== FALSE ||
                                        strpos($row['reversed_url'], "ittinews.net") !== FALSE ||
                                        strpos($row['reversed_url'], "yallakora.com") !== FALSE ||
                                        strpos($row['reversed_url'], "ar.beinsports.net") !== FALSE ||
                                        strpos($row['reversed_url'], "almowaten.net") !== FALSE ||
                                        strpos($row['reversed_url'], "alrayalaam.com") !== FALSE ||
                                        strpos($row['reversed_url'], "elheddaf.com") !== FALSE ||
                                        strpos($row['reversed_url'], "goalna.com") !== FALSE ||
                                        strpos($row['reversed_url'], "aljazeera.net") !== FALSE ||
                                        strpos($row['reversed_url'], "mbc.net") !== FALSE ||
                                        strpos($row['reversed_url'], "GalerieArtciles") !== FALSE ||
                                        strpos($row['reversed_url'], "fifa.com") !== FALSE ||
                                        strpos($row['reversed_url'], "almashhad.net") !== FALSE ||
                                        strpos($row['reversed_url'], "alkoutnews.net") !== FALSE ||
                                        strpos($row['reversed_url'], "oleeh.com") !== FALSE ||
                                        strpos($row['reversed_url'], "aldostornews.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alshamiya-news.com") !== FALSE ||
                                        strpos($row['reversed_url'], "reqaba.com") !== FALSE ||
                                        strpos($row['reversed_url'], "dasmannews.com") !== FALSE ||
                                        strpos($row['reversed_url'], "al-seyassah.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alhakea.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alsawtnews.cc") !== FALSE ||
                                        strpos($row['reversed_url'], "acakuw.com") !== FALSE ||
                                        strpos($row['reversed_url'], "ajialq8.com") !== FALSE ||
                                        strpos($row['reversed_url'], "altaleea.com") !== FALSE ||
                                        strpos($row['reversed_url'], "annaharkw.com") !== FALSE ||
                                        strpos($row['reversed_url'], "tounessna.info") !== FALSE ||
                                        strpos($row['reversed_url'], "atheer.om") !== FALSE ||
                                        strpos($row['reversed_url'], "otv.com.lb") !== FALSE ||
                                        strpos($row['reversed_url'], "kuna.net.kw") !== FALSE ||
                                        strpos($row['reversed_url'], "alanba.com.kw") !== FALSE ||
                                        strpos($row['reversed_url'], "alikhbaria.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alkuwaityah.com") !== FALSE ||
                                        strpos($row['reversed_url'], "Alkuwaityah.com") !== FALSE ||
                                        strpos($row['reversed_url'], "ennaharonline.com") !== FALSE ||
                                        strpos($row['reversed_url'], "elbilad.net") !== FALSE ||
                                        strpos($row['reversed_url'], "filwajiha.com") !== FALSE ||
                                        strpos($row['reversed_url'], "moroccoeyes") !== FALSE ||
                                        strpos($row['reversed_url'], "alyaoum24.com") !== FALSE ||
                                        strpos($row['reversed_url'], "chouftv.ma") !== FALSE ||
                                        strpos($row['reversed_url'], "attounissia.com.tn") !== FALSE ||
                                        strpos($row['reversed_url'], "annaharnews.net") !== FALSE ||
                                      //  strpos($row['reversed_url'], "moheet.com") !== FALSE ||
                                        strpos($row['reversed_url'], "sahelmaten.com") !== FALSE ||
                                        strpos($row['reversed_url'], "tunisien.tn") !== FALSE ||
                                        strpos($row['reversed_url'], "babnet.net") !== FALSE ||
                                        strpos($row['reversed_url'], "arabesque.tn") !== FALSE ||
                                        strpos($row['reversed_url'], "mmaqara2t.com") !== FALSE ||
                                        strpos($row['reversed_url'], "zoomtunisia.tn") !== FALSE ||
                                        strpos($row['reversed_url'], "omandaily.om") !== FALSE ||
                                        strpos($row['reversed_url'], "tnntunisia.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alwatan.com") !== FALSE ||
                                        strpos($row['reversed_url'], "moe.gov.qa") !== FALSE ||
                                        strpos($row['reversed_url'], "shabiba.com") !== FALSE ||
                                        strpos($row['reversed_url'], "futuretvnetwork.com") !== FALSE ||
                                        strpos($row['reversed_url'], "al-watan.com") !== FALSE ||
                                        strpos($row['reversed_url'], "lbcgroup.tv") !== FALSE ||
                                        strpos($row['reversed_url'], "syrianow.sy") !== FALSE ||
                                        strpos($row['reversed_url'], "iraqdirectory.com") !== FALSE ||
                                        strpos($row['reversed_url'], "o-t.tv") !== FALSE ||
                                        strpos($row['reversed_url'], "alforatnews.com") !== FALSE ||
                                        strpos($row['reversed_url'], "almanar.com.lb") !== FALSE ||
                                        strpos($row['reversed_url'], "nna-leb.gov.lb") !== FALSE ||
                                        strpos($row['reversed_url'], "lebwindow.net") !== FALSE ||
                                        strpos($row['reversed_url'], "alliraqnews.com") !== FALSE ||
                                        strpos($row['reversed_url'], "orient-news.net") !== FALSE ||
                                        strpos($row['reversed_url'], "basnews.com") !== FALSE ||
                                        strpos($row['reversed_url'], "etilaf.org") !== FALSE ||
                                        strpos($row['reversed_url'], "alnoornews.net") !== FALSE ||
                                        strpos($row['reversed_url'], "argaam.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alrafidain.org") !== FALSE ||
                                        strpos($row['reversed_url'], "hroobnews.com") !== FALSE ||
                                        strpos($row['reversed_url'], "albayan.ae") !== FALSE ||
                                        strpos($row['reversed_url'], "yanair.net") !== FALSE ||
                                        strpos($row['reversed_url'], "wam.ae") !== FALSE ||
                                        strpos($row['reversed_url'], "maqar.com") !== FALSE ||
                                        strpos($row['reversed_url'], "almesryoon.com") !== FALSE ||
                                        strpos($row['reversed_url'], "almogaz.com") !== FALSE ||
                                        strpos($row['reversed_url'], "egyptiannews.net") !== FALSE ||
                                        strpos($row['reversed_url'], "elshaab.org") !== FALSE ||
                                        strpos($row['reversed_url'], "jn-news.com") !== FALSE ||
                                        strpos($row['reversed_url'], "klmty.net") !== FALSE ||
                                        strpos($row['reversed_url'], "jo24.net") !== FALSE ||
                                        strpos($row['reversed_url'], "el-balad.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alamalmal.net") !== FALSE ||
                                        strpos($row['reversed_url'], "assawsana.com") !== FALSE ||
                                        strpos($row['reversed_url'], "7iber.com") !== FALSE ||
                                        strpos($row['reversed_url'], "royanews.tv") !== FALSE ||
                                        strpos($row['reversed_url'], "alarabalyawm.net") !== FALSE ||
                                        strpos($row['reversed_url'], "addustour.com") !== FALSE ||
                                        strpos($row['reversed_url'], "wikise7a") !== FALSE ||
                                        strpos($row['reversed_url'], "almasryalyoum.com") !== FALSE ||
                                        strpos($row['reversed_url'], "kaahe.org") !== FALSE ||
                                        strpos($row['reversed_url'], "3eesho.com") !== FALSE ||
                                        strpos($row['reversed_url'], "dailymedicalinfo.com") !== FALSE ||
                                        strpos($row['reversed_url'], "rotanamags.net") !== FALSE ||
                                        strpos($row['reversed_url'], "almaghribtoday.net") !== FALSE ||
                                        strpos($row['reversed_url'], "lahaonline.com") !== FALSE ||
                                        strpos($row['reversed_url'], "hafralbaten.com") !== FALSE ||
                                        strpos($row['reversed_url'], "arabi21.com") !== FALSE ||
                                        strpos($row['reversed_url'], "almaydan2.net") !== FALSE ||
                                        strpos($row['reversed_url'], "almuraba.net") !== FALSE ||
                                        strpos($row['reversed_url'], "aldawadmi.net") !== FALSE ||
                                        strpos($row['reversed_url'], "adwaalwatan.com") !== FALSE ||
                                        strpos($row['reversed_url'], "naseej.net") !== FALSE ||
                                        strpos($row['reversed_url'], "arjja.com") !== FALSE ||
                                        strpos($row['reversed_url'], "nwafecom.net") !== FALSE ||
                                        strpos($row['reversed_url'], "newsqassim.com") !== FALSE ||
                                        strpos($row['reversed_url'], "fajr.sa") !== FALSE ||
                                        strpos($row['reversed_url'], "ajel.sa") !== FALSE ||
                                        strpos($row['reversed_url'], "aljubailtoday.com.sa") !== FALSE ||
                                        strpos($row['reversed_url'], "spa.gov.sa") !== FALSE ||
                                        strpos($row['reversed_url'], "almjardh.com") !== FALSE ||
                                        strpos($row['reversed_url'], "twasul.info") !== FALSE ||
                                        strpos($row['reversed_url'], "rasdnews.net") !== FALSE ||
                                        strpos($row['reversed_url'], "aljouf-news.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alshahedkw.com") !== FALSE || 
                                        strpos($row['reversed_url'], "mini-news.net") !== FALSE || 
                                        strpos($row['reversed_url'], "anbaanews.com") !== FALSE || 
                                        strpos($row['reversed_url'], "zahran.org") !== FALSE || 
                                        strpos($row['reversed_url'], "kharjhome.com") !== FALSE ||
                                        strpos($row['reversed_url'], "tabuk-news.com") !== FALSE ||
                                        strpos($row['reversed_url'], "roaanews.net") !== FALSE ||
                                        strpos($row['reversed_url'], "alkhaleejaffairs.org") !== FALSE ||
                                        strpos($row['reversed_url'], "rsssd.com") !== FALSE ||
                                        strpos($row['reversed_url'], "asir.com") !== FALSE ||
                                        strpos($row['reversed_url'], "asir.net_xxxxxx") !== FALSE ||
                                        strpos($row['reversed_url'], "reuters.com") !== FALSE ||
                                        strpos($row['reversed_url'], "baareq.com.sa") !== FALSE ||
                                        strpos($row['reversed_url'], "cma.org.sa") !== FALSE ||
                                        strpos($row['reversed_url'], "freeswcc.com") !== FALSE ||
                                        strpos($row['reversed_url'], "moh.gov.sa") !== FALSE ||
                                        strpos($row['reversed_url'], "saso.gov.sa") !== FALSE ||
                                        strpos($row['reversed_url'], "alwasat.ly") !== FALSE ||
                                        strpos($row['reversed_url'], "electrony.net") !== FALSE ||
                                        strpos($row['reversed_url'], "alsharq.net.sa") !== FALSE ||
                                        strpos($row['reversed_url'], "sabq.org") !== FALSE ||
                                        strpos($row['reversed_url'], "palinfo.com") !== FALSE ||
                                        strpos($row['reversed_url'], "paltimes.net") !== FALSE ||
                                        strpos($row['reversed_url'], "ar.yabiladies.com") !== FALSE ||
                                        strpos($row['reversed_url'], "electronynet") !== FALSE ||
                                        strpos($row['reversed_url'], "aljadeed.tv") !== FALSE ||
                                        strpos($row['reversed_url'], "raialyoum.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alrakoba.net") !== FALSE ||
                                        strpos($row['reversed_url'], "alkhaleej.ae") !== FALSE ||
                                        strpos($row['reversed_url'], "naba.ps") !== FALSE ||
                                        strpos($row['reversed_url'], "charlesayoub.com") !== FALSE ||
                                        strpos($row['reversed_url'], "aawsat.com") !== FALSE ||
                                        strpos($row['reversed_url'], "alqabas.com.kw") !== FALSE ||
                                        strpos($row['reversed_url'], "aljarida.com") !== FALSE ||
                                        strpos($row['reversed_url'], "dotmsr.com") !== FALSE ||
                                        strpos($row['reversed_url'], "sabr.cc") !== FALSE ||
                                        strpos($row['reversed_url'], "alwatan.kuwait.tt") !== FALSE ||
                                        strpos($row['reversed_url'], "alraimedia.com") !== FALSE ||
                                        strpos($row['reversed_url'], "14march.org") !== FALSE
                                        ) { 
                                   if ($counter == 0){   //  pr($content);    
                                       $desc .= $content['contents'] . " <br /><br /> ";
                                       break;
                                   }
                                   $counter++;
                               }
                               
                               else if ( strpos($row['reversed_url'], 'alalam.ir') !== FALSE) {    
                                   if ($counter == 2) {
                                       if (strpos($content['contents'], 'Shadowbox.init') !== FALSE) {
                                           $no_text1 = true;
                                           $counter++;  
                                           
                                           continue;
                                       }
                                   }  
                                   elseif ($counter == 3) {
                                       if (strpos($content['contents'], 'Shadowbox.init') !== FALSE) {
                                           $no_text = true;
                                           $counter++;  
                                           
                                           continue;
                                       }
                                   } 
                                   else if ($counter == 3 && isset($no_text1)) {
                                       $desc = '';
                                       $desc .= $content['contents'] . " <br /><br /> ";
                                       break;
                                   } 
                                   else if ($counter == 4 && isset($no_text)) {
                                       $desc = '';
                                       $desc .= $content['contents'] . " <br /><br /> ";
                                       break;
                                   }
                                   else{
                                       $desc .= $content['contents'] . " <br /><br /> ";
                                   }
                                   
                                   $counter++;
                               }
                               else{
                                   $desc .= $content['contents'] . " <br /><br /> ";
                               }
                        }
                        else{
                           // $desc = '404';
                          //  break;
                        }
                    }
                    else{
                        $desc = $data['paragraph'][0];
                    }
                }            
            }
            else{      // pr($data);
                //$news['desc'] = $data['paragraph']['contents'];
                $desc = $row['title'];
            }
        }
        
         if(strrpos($row['reversed_url'],"yalla") === FALSE) {
            if(strrpos($row['reversed_url'],"kora.com") !== FALSE || 
               //strrpos($row['reversed_url'],"tracksport.net") !== FALSE || 
               strrpos($row['reversed_url'],"binybohair.com") !== FALSE || 
            //   strrpos($row['reversed_url'],"aksalser.com") !== FALSE || 
               strrpos($row['reversed_url'],"sudaneseonline.com") !== FALSE || 
               strrpos($row['reversed_url'],"barca4ever.com") !== FALSE || 
               strrpos($row['reversed_url'],"qh.gov.sa") !== FALSE || 
               strrpos($row['reversed_url'],"moi.gov.qa") !== FALSE || 
               strrpos($row['reversed_url'],"elfann.com") !== FALSE || 
              // strrpos($row['reversed_url'],"hroobnews.com") !== FALSE || 
            //   strrpos($row['reversed_url'],"almotamar.net") !== FALSE || 
               strrpos($row['reversed_url'],"elnashra.com") !== FALSE || 
               strrpos($row['reversed_url'],"lahaonline.com") !== FALSE || 
               strrpos($row['reversed_url'],"yemen-press.com") !== FALSE || 
            //   strrpos($row['reversed_url'],"hassacom.com") !== FALSE || 
               strrpos($row['reversed_url'],"sahafah.net") !== FALSE || 
               strrpos($row['reversed_url'],"enferaad.com") !== FALSE || 
            //   strrpos($row['reversed_url'],"almaydan2.net") !== FALSE || 
               strrpos($row['reversed_url'],"qassimy.com") !== FALSE || 
               strrpos($row['reversed_url'],"shathanews.com") !== FALSE || 
               strrpos($row['reversed_url'],"nna-leb.gov.lb") !== FALSE || 
            //   strrpos($row['reversed_url'],"scbnews.com") !== FALSE || 
               //strrpos($row['reversed_url'],"fath-news.com") !== FALSE || 
               strrpos($row['reversed_url'],"sheikhmohammed.ae") !== FALSE || 
               strrpos($row['reversed_url'],"maps.google.com.qa") !== FALSE || 
              // strrpos($row['reversed_url'],"yemenat.net") !== FALSE || 
               //strrpos($row['reversed_url'],"reqaba.com") !== FALSE ||                     
               strrpos($row['reversed_url'],"24yemen.net") !== FALSE ||                     
               strrpos($row['reversed_url'],"sabanews.net") !== FALSE ||                     
              // strrpos($row['reversed_url'],"lebanonfiles.com") !== FALSE || 
               strrpos($row['reversed_url'],"yemen-press.net") !== FALSE || 
               strrpos($row['reversed_url'],"yemen-perss.com") !== FALSE || 
             //  strrpos($row['reversed_url'],"shabiba.com") !== FALSE || 
            //   strrpos($row['reversed_url'],"klmty.net") !== FALSE || 
               strrpos($row['reversed_url'],"arabic-military.com") !== FALSE || 
               strrpos($row['reversed_url'],"nmisr.com") !== FALSE || 
               strrpos($row['reversed_url'],"alazraq.com") !== FALSE || 
               strrpos($row['reversed_url'],"guryatnews.com") !== FALSE || 
               strrpos($row['reversed_url'],"ajmanpolice.gov") !== FALSE || 
               strrpos($row['reversed_url'],"sactr.net") !== FALSE || 
               //strrpos($row['reversed_url'],"nas.sa") !== FALSE || 
              // strrpos($row['reversed_url'],"marebpress.net") !== FALSE || 
               strrpos($row['reversed_url'],"ham-24.com") !== FALSE || 
               //strrpos($row['reversed_url'],"babnet.net") !== FALSE || 
               strrpos($row['reversed_url'],"tracksport.net") !== FALSE || 
               strrpos($row['reversed_url'],"forbesmiddleeast.com") !== FALSE || 
               //strrpos($row['reversed_url'],"reqaba.com") !== FALSE || 
               strrpos($row['reversed_url'],"sabqq.org") !== FALSE || 
             //  strrpos($row['reversed_url'],"hroobnews.com") !== FALSE || 
             //  strrpos($row['reversed_url'],"almaydan2.net") !== FALSE || 
               //strrpos($row['reversed_url'],"alsopar.com") !== FALSE || 
               strrpos($row['reversed_url'],"nna-leb.gov.lb") !== FALSE || 
               strrpos($row['reversed_url'],"saidaonline.com") !== FALSE || 
               strrpos($row['reversed_url'],"alwasatnews.com") !== FALSE || 
               //strrpos($row['reversed_url'],"lahaonline.com") !== FALSE || 
               strrpos($row['reversed_url'],"marib.net") !== FALSE || 
               strrpos($row['reversed_url'],"alhilal.com") !== FALSE
               ) {             
                $desc = iconv('windows-1256', 'UTF-8', $desc);      
                $desc = str_replace('<br/>', "", $desc);
                                  echo('<br /> 1converted to utf-8 <br />');
                //echo($desc); exit;
            }
        }
        
    /*    if (strrpos($row['reversed_url'],"klmty.net") !== FALSE) {
            $desc = iconv('windows-1252', 'UTF-8', $desc);
        }    */
        
        
        $desc = str_replace("\"", "", $desc);
        $desc = str_replace("'", "", $desc);
    
        //kora
        $desc = str_replace('var addthis_config={"data_track_clickback":true};', "", $desc);
        
        $desc = str_replace('This site uses cookies', "", $desc);
        $desc = str_replace('By clicking allow you are agreeing to our use of cookies.', "", $desc);
        $desc = str_replace('By clicking allow you are agreeing to our use of cookies', "", $desc);
        $desc = str_replace('Be a Citizen and discover all the benefits of being a City member.', "", $desc);
        $desc = str_replace('Be a Citizen and discover all the benefits of being a City member', "", $desc);
        $desc = str_replace('Find out more', "", $desc);
        
        $desc = str_replace('================ هـ ع.', "", $desc);
        $desc = str_replace('================ هـ ع', "", $desc);
        
        $desc = str_replace('1599998474121px; line-height: 1<br />3em;>', "", $desc);
        
        $desc = str_replace('<!-- Plugins: BeforeDisplayContent -->              <!-- K2 Plugins: K2BeforeDisplayContent -->                                 <!-- Item introtext -->       <div class=itemIntroText>           <h3 style=text-align: justify;><span style=font-size: 12<br />1599998474121px; line-height: 1<br />3em;>', "", $desc);
        
        $desc = str_replace('googletag<br />display(div-gpt-ad-mpu);', "", $desc);
        
        $desc = str_replace('ومقالات الرأي المنشرة علي حصري', "", $desc);
        $desc = str_replace('اشترك بالنشرة البريدية للمدونة لتصلك أخر الاخبار', "", $desc);
        
        $desc = str_replace('قم بإضافة تطبيق الموجز على متصفح كروم (Chrome) لتسهيل متابعة وقراءة اخر الاخبار من موقع الموجز<br /> مع هذا التطبيق ستكون على علم بأخر الاخبار المصرية والعربية والعالمية <br /><br />', "", $desc);
        $desc = str_replace('قم بإضافة تطبيق الموجز على متصفح كروم (Chrome) لتسهيل متابعة وقراءة اخر الاخبار من موقع الموجز<br /> مع هذا التطبيق ستكون على علم بأخر الاخبار المصرية والعربية والعالمية', "", $desc);
    
        
        $desc = str_replace('<br /><br /> محتوى حبر مرخص برخصة المشاع الإبداعي<br /> يسمح بإعادة نشر المواد بشرط الإشارة إلى المصدر بواسطة رابط (hyperlink)، وعدم إجراء تغييرات على النص، وعدم استخدامه لأغراض تجارية <br /><br />', "", $desc);
        
        $desc = str_replace('error was encountered while trying to use an ErrorDocument to handle the request', "", $desc);
        $desc = str_replace('To get best possible experiance using our website we recommend that you upgrade to a newer version or other web browser', "", $desc);
        $desc = str_replace('A list of the most popular web browsers can be found below', "", $desc);
        
        $desc = str_replace(' للتحقق ', "", $desc);
        
        //general
        $desc = str_replace('developer', "", $desc);
        $desc = str_replace('Developer', "", $desc);
        $desc = str_replace('API', "", $desc);
        $desc = str_replace('Api', "", $desc);
        $desc = str_replace('Terms', "", $desc);
        $desc = str_replace('Conditions', "", $desc);
        $desc = str_replace('Privacy', "", $desc);
        $desc = str_replace('Policy', "", $desc);
        $desc = str_replace('Copyright', "", $desc);
       // $desc = str_replace('text-align', "", $desc);
      //  $desc = str_replace('right', "", $desc);
      //  $desc = str_replace('left', "", $desc);
       // $desc = str_replace('position', "", $desc);
       // $desc = str_replace('RTL', "", $desc);
       // $desc = str_replace('LTR', "", $desc);
       // $desc = str_replace('rtl', "", $desc);
        //$desc = str_replace('ltr', "", $desc);
       // $desc = str_replace('pt', "", $desc);
       // $desc = str_replace('0001', "", $desc);
       // $desc = str_replace('>', "", $desc);
       // $desc = str_replace('<', "", $desc);
     //   $desc = str_replace('dir', "", $desc);
      //  $desc = str_replace(';', "", $desc);
        
        $desc = str_replace('stLight<br />options({publisher: 2683a2c2-035f-4bce-b2c4-26b1a403e01a, doNotHash: false, doNotCopy: false, hashAddressBar: false});', "", $desc);
        $desc = str_replace('Lorem Ipsum is simply dummy text of the printing and typesetting industry<br /> Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took', "", $desc);
        
        $desc = str_replace('إضغط على الصورة لمشاهدة الحجم الكامل', "", $desc);
        
        $desc = str_replace('ظ‡ط§ظ… - ط§ظ„ط±ظٹط§ط¶', "", $desc);
        $desc = str_replace('ظ‡ط§ظ… - ط§ظ„ط±ظٹط§ط', "", $desc);
        
         $desc = str_replace('
        Not All tags are allowed! Please remove html tags from your comments and try again', "", $desc);
        $desc = str_replace('Not All tags are allowed! Please remove html tags from your comments and try again', "", $desc);
        
         $desc = str_replace('Powered by Dimofinf cms Version 3', "", $desc);
         $desc = str_replace('0Copyright© Dimensions Of Information Inc.', "", $desc);
         $desc = str_replace('0Copyright© Dimensions Of Information Inc', "", $desc);
         $desc = str_replace('404', "", $desc);
        
        $desc = str_replace('الرئيسية | الصور |  المقالات |  البطاقات | الملفات  | الجوال  |الأخبار |الفيديو |الصوتيات |راسلنا |للأعلى', "", $desc);
        $desc = str_replace('جميع الحقوق محفوظة لصحيفة الخبر تايمز ولا يسمح بالنسخ أو الاقتباس إلا بموافقه خطيه من إدارة الصحيفة', "", $desc);
        
        $desc = str_replace('function GoogleLanguageTranslatorInit() { new google', "", $desc);
        $desc = str_replace('translate', "", $desc);
    
        $desc = str_replace('باب.كوم جميع الحقوق محفوظة © 2015 شركة باب العالمية للخدمات المتخصصة – باب حاصلة على ترخيص وزارة الثقافة والإعلام', "", $desc);
        
        $desc = str_replace('كافة الحقوق محفوظة لـ scbnews<br />com &copy; 1436 التصميم بواسطة :ALTALEDI NET Powered by Dimofinf cms Version 3<br />0<br />0Copyright&copy; Dimensions Of Information Inc <br /><br />', "", $desc);
        $desc = str_replace('كافة الحقوق محفوظة لـ scbnews.com &copy; 1436 التصميم بواسطة :ALTALEDI NET Powered by Dimofinf cms Version 3.0.0Copyright&copy; Dimensions Of Information Inc', "", $desc);
        
        $desc = str_replace('الكوكيز يتيح لك العديد من الميزات لتعزيز تجربتك على موقع يابلادي. باستخدام هذا الموقع، فإنك توافق على سياستنا الخاصة بالحريات الشخصية، لمعرفة المزيد إظغط هناX', "", $desc);
        $desc = str_replace('الكوكيز يتيح لك العديد من الميزات لتعزيز تجربتك على موقع يابلادي. باستخدام هذا الموقع، فإنك توافق على سياستنا الخاصة بالحريات الشخصية، لمعرفة المزيد إظغط هنا', "", $desc);
        $desc = str_replace('الكوكيز يتيح لك العديد من الميزات لتعزيز تجربتك على موقع يابلادي', "", $desc);
        $desc = str_replace('الكوكيز يتيح لك العديد من الميزات لتعزيز تجربتك على موقعنا', "", $desc);
        $desc = str_replace('الكوكيز يتيح لك العديد من الميزات لتعزيز تجربتك على موقعنا. باستخدام هذا الموقع، فإنك توافق على سياستنا الخاصة بالحريات الشخصية، لمعرفة المزيد إظغط هناX', "", $desc);
        $desc = str_replace('الكوكيز يتيح لك العديد من الميزات لتعزيز تجربتك على موقعنا. باستخدام هذا الموقع، فإنك توافق على سياستنا الخاصة بالحريات الشخصية، لمعرفة المزيد إظغط هنا', "", $desc);
        
        $desc = str_replace('يمكنك الآن الاشتراك في القائمة البريدية و سوف يصلك جديد الأخبار على البريد الإلكتروني الخاص بكم', "", $desc);
        $desc = str_replace('يمكنك الآن الاشتراك في خدمة الرسائل القصيرة SMS , لتصلك آخر الأخبار على نقالك أولاً بأول', "", $desc);
        
        $desc = str_replace('Copyright © 2015 www.alnilin.com All Rights Reserved.', "", $desc);
        $desc = str_replace('موقع النيلين هو وجهتك الاولى للاخبار المحلية والعالمية ، الصور والفيديو والمنوعات ،الوظائف ، والتسويق ، الاعلانات', "", $desc);
        
        $desc = str_replace('var initId = 880103; function changeVideo(id){ if(id == initId){ return false; } $.ajax({ url: http://www.charlesayoub.com/get-video-embed/+id+/side, beforeSend: function(){ $(#videoLoader).show(); $(#playSection).css({opacity:0.5}); }, success: function(data) { $(#videoLoader).hide(); $(#playSection).css({opacity:1}); $(#playSection).html(data); initId = id; } }); }', "", $desc);
        
        $desc = str_replace('googletag.defineSlot(5308/ab_ar, [300,250], banner300x250).addService(googletag.pubads()); googletag.pubads().enableSyncRendering();
googletag.enableServices(); var wd = 300; var ht = 250; var divPart1 = banner+wd; var divPart2=x+ht; // var divPart1 = banner+300; // var divPart2=x+250; if(wd == 1 && ht ==2){ googletag.display(divPart1+divPart2+-oop); }else{ googletag.display(divPart1+divPart2); }', "", $desc);
        
        $desc = str_replace('ومضة هي منصة تعنى بالاستثمار وبدعم رواد الأعمال في منطقة الشرق الأوسط وشمال إفريقيا.', "", $desc);
        
        $desc = str_replace('© جميع الحقوق محفوظة لقناة العربية 2015 Provided by SyndiGate Media Inc. (Syndigate.info).', "", $desc);
        $desc = str_replace('© جميع الحقوق محفوظة لقناة العربية 2016 Provided by SyndiGate Media Inc. (Syndigate.info).', "", $desc);
        $desc = str_replace('© جميع الحقوق محفوظة لقناة العربية 2017 Provided by SyndiGate Media Inc. (Syndigate.info).', "", $desc);
    
        $desc = str_replace('$(".more").disableTextSelect(); $(function(){ $(".more-selected").disableTextSelect(); });', "", $desc);
        $desc = str_replace('$(.more).disableTextSelect(); $(function(){ $(.more-selected).disableTextSelect(); });', "", $desc);
        
        $desc = str_replace('$(".more").disableTextSelect(); $(function(){ $(".more-selected").disableTextSelect(); });', "", $desc);
        
        $desc = str_replace('(adsbygoogle = window.adsbygoogle || []).push({});Tweet', "", $desc);
        $desc = str_replace('Powered by Dimofinf cms Version 3.0.0Copyright© Dimensions Of Information Inc.', "", $desc);
                                         
        $desc = str_replace('--> $(document).ready(function(){ setTimeout(function() { $(#todayWeatherContainer).load(/pages/today_weather/0); }, 10000); });', "", $desc);
        
        $desc = str_replace('Powered by Dimofinf cms Version 3.0.0Copyright© Dimensions Of Information Inc. Design By : ALTALEDI.NET', "", $desc);
        
        $desc = str_replace(':ALTALEDI NET Powered by Dimofinf cms Version 3.0.0Copyright© Dimensions Of Information Inc.', "", $desc);
        $desc = str_replace('To get best possible experiance using our website we recommend that you upgrade to a newer version or other web browser. A list of the most popular web browsers can be found below.', "", $desc);
        
        //echo($desc);        exit;
        
        //instegram
        $desc = str_replace('“. #Porto 3-0 #Basel (Agg 4-1) . #Herrera 47 @casemiro_oficial 56 (Super goal)”', "", $desc);
        $desc = str_replace('“Half time . #Porto 1-0 #Basel (Agg 2-1) . #Brahimi 14”', "", $desc);
        $desc = str_replace('embed" dir="RTL">', "", $desc);
        
        $desc = str_replace('You are using an outdated browser. Please upgrade your browser to improve your experience.', "", $desc);
        
        $desc = str_replace('Powered by vBulletin™ Version 4.2.2 Copyright © 2015 vBulletin Solutions, Inc. All rights reserved. vb4 Watermark Generator provided by Purgatory-Labs.de', "", $desc);
        
        $desc = str_replace('function get_url(title,url){ var title_encode=encodeURI(title); document.getElementById(title).href =components/com_mailajax/form.php?url=+url+&title=+title_encode+&keepThis=true&TB_iframe=true&height=325&width=425; //location.href=components/com_mailajax/form.php?url=+url+&title=+title_encode+&keepThis=true&TB_iframe=true&height=325&width=425; } function fnSave() { document.execCommand(SaveAs,null,document.title); } function Check_Controls() { var form = document.adminForm; // do field validation var filter=/[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}/; if(form.FNameOfQuestioner.value==){ alert(', "", $desc);
        $desc = str_replace('); return false; } else { form.submit(); } }', "", $desc);
        $desc = str_replace('myButton { background-color:#660033; border:1px solid #660033; display:inline-block; color:#ffffff; font-family:arial; font-size:14px; padding:6px 12px; text-decoration:none; width: 174px; text-shadow:0px 1px 0px #b20f50; }', "", $desc);
        
        $desc = str_replace('CNN © 2014 Cable News Network. Turner Broadcasting System, Inc. All Rights Reserved', "", $desc);
        $desc = str_replace('CNN © 2015 Cable News Network. Turner Broadcasting System, Inc. All Rights Reserved', "", $desc);
        $desc = str_replace('CNN © 2016 Cable News Network. Turner Broadcasting System, Inc. All Rights Reserved', "", $desc);
        
        $desc = str_replace('$(document).ready( function(){ $(.ticker).innerfade({ animationtype: slide, speed: 750, timeout: 4000, type: random, containerheight: 1em });} );', "", $desc);
        $desc = str_replace('Powered by Dimofinf cms Version 3.0.0Copyright© Dimensions Of Information Inc.', "", $desc);
        $desc = str_replace('Powered by Dimofinf cms Version 3.0.0Copyright© Dimensions Of Information Inc.\n', "", $desc);
        
        $desc = str_replace('new TWTR.Widget({ version: 2, type: profile, rpp: 3, interval: 1000, width: 246, height: 265, theme: { shell: { background: #63BEFD, color: #FFFFFF }, tweets: { background: #FFFFFF, color: #000000, links: #47a61e } }, features: { loop: false,live: true, scrollbar: false,hashtags: false,timestamp: true, avatars: true,behavior: default } }).render().setUser(binybohair).start();', "", $desc);
        $desc = str_replace('vbmenu_register(posts6_32925, true);\nPowered by vBulletin® Version 3.8.7Copyright ©2000 - 2015, vBulletin Solutions, Inc. Content Relevant URLs by vBSEO 3.6.0 PL2', "", $desc);
        $desc = str_replace('SALEM ALSHMRANI Ads Management Version 3.0.1 by Saeed Al-Atwi', "", $desc);
        $desc = str_replace('var sAppPath = /; var fbLanguage = ar_AR; var sImageLangPath = ar; var LanguageDirection = right; //', "", $desc);
       
        //alhilal 
        $desc = str_replace("????\nvar newwindow; function popit(url) { newwindow=window.open(url,'name','height=400,width=600,scrollbars=yes'); if (window.focus) {newwindow.focus()} }", "", $desc);
        $desc = str_replace("روابط الأخبار: أخبار نادي الهلال اخبار الصحف اخبار المنتخب السعودي اخبار الدوري السعودي اخبار متفرقة", "", $desc);
        $desc = str_replace("RSS معلومات عن نادي الهلال اخبار نادي الهلال قائمة اللاعبين في نادي الهلال صور الهلال اهداف الهلال الهلال تيوب المنتديات بريد الموظفين جميع الحقوق محفوظة لنادي الهلال السعودي © 1999 - 2011 انترنت بلس", "", $desc);
      
        //tracksport
        $desc = str_replace("\$(document).ready( function(){ $(.ticker).innerfade({ animationtype: slide, speed: 750, timeout: 4000, type: random, containerheight: 1em });} ); (adsbygoogle = window.adsbygoogle || []).push({}); (adsbygoogle = window.adsbygoogle || []).push({});", "", $desc);
        $desc = str_replace("Powered by Dimofinf cms Version 3.0.0Copyright© Dimensions Of Information Inc.\nالرئيسية |الصور |المقالات |الأخبار |الفيديو |راسلنا | للأعلى Copyright © 1436 tracksport.net - All rights reserved", "", $desc);
        $desc = str_replace("رباعي المبارزة السعودية يحققون برونزية للسيف .. يشاركون غداً في منافسات سلاح الشيش تحت 17 سنه اثني عشر لاعباً يمثلون السعودية في دولية البحرين للبولينج .. المرحلة الأولى انطلقت يوم الأثنين اتحاد التنس يوقع عقد رعاية مع أبناء الحقباني المدرب قوميز يبارك لرجال التعاون التاهل بعد الفوز على السويق العماني رئيس التعاون يتلقى اتصالا من صاحب السمو الملكي أمير منطقة القصيم فيصل بن مشعل ادارة الفيصلي تجدد عقد قائد الفريق عمر عبدالعزيز واللاعب محمد سالم أبها الى ال16 عبر بوابة الشعلة رئيس هجر يجتمع بالجهاز الفني واللاعبين قبيل انطلاق التدريبات اخضر الصالات يخوض وديتين في يوم واحد النهضة يعاود التدريبات بعد الاتفاق\nرباعي المبارزة السعودية يحققون برونزية للسيف .. يشاركون غداً في منافسات سلاح الشيش تحت 17 سنه", "", $desc);
        $desc = str_replace("0 | 0 | 46\nتقييم 0.00/10 (0 صوت) $(function() { $(#accordion).tabs(#accordion div.pane, {tabs: h2, effect: fade, initialIndex: null, event:click}); });", "", $desc);
        
        $desc = str_replace("Developer API Terms &amp; Conditions Privacy Policy Copyright &copy;2014 Hootsuite Media Inc. All Rights Reserved.", "", $desc);
        $desc = str_replace("var sAppPath = /; var fbLanguage = ar_AR; var sImageLangPath = ar; var LanguageDirection = right; //", "", $desc);
        
              // echo('<br />$data[title]: ' . $data['title'].'<br />');
              // echo('<br />strpos $data[title]: ' . (strpos($data['title'], 'NOT FOUND')).'<br />');
        if (strpos($data['title'], 'Not Found') !== FALSE || 
            strpos($data['title'], 'Page not found') ||
            strpos($data['title'], 'مميزات والخيارات المتاحة فقط للأعضاء') ||
            strpos($data['title'], 'لقد حدث خطأ في استخدامك لنظام التصفح') ||
            strpos($data['title'], 'الصفحة غير موجودة') 
            ) { //echo('ayaaaaaaaaaaaaaaaamaaaaaaaaan');
             $desc = '404'; 
        }
        
        if (strpos($row['reversed_url'], 'hashtagarabi') !== FALSE && $data['title'] == "") {
            $desc = '404';
        }   
        //echo($desc);
        
      /*  if ($desc == "") {
            $update_query = "update articles_html set body = title where twitter_news_id = '" . $row['twitter_news_id'] . "'"; 
        }
        else{  */
            $update_query = "update articles_html set body = '$desc' where twitter_news_id = '" . $row['twitter_news_id'] . "'";
      /*  }    */
                   
        echo($update_query.'<br />');  //exit;
        $res_update = $conn->db_query($update_query);
        
        echo('<br />-------------------------------------------------------------------------------------<br />');
    }
}
           
      echo('7777777777'); 
      
if ($type == 1) {  //twitter     
    $start = isset($_GET['start']) ? $_GET['start']: 0;
    $offset = isset($_GET['offset']) ? $_GET['offset']: 100;
    
   // $cron_source = isset($_GET['cron_source']) ? $_GET['cron_source']: 'gae';
   
   // $time1 = time();
   
     $time1 = date("h:i:s");
     
     //set_time_limit(60);
          
     $fp = fopen('twitter_1.txt', 'a+');
     fwrite($fp, 's(' . $start .'):' . $time1 . "\n");
     fclose($fp);
  
    //get_sources($cat_id, $connection, $start, $offset);
    get_sources('', $connection, $start, $offset);
    
  //  $time2 = time();
     $time2 = date("h:i:s");
     $fp = fopen('twitter_1.txt', 'a+');
     fwrite($fp, 'e(' . $start . '):' . $time2 . "\n");
     fclose($fp);
    
   // cron_start_end($time1, $time2, $cron_source);
}
else if ($type == 2){     //rss
    $start = isset($_GET['start']) ? $_GET['start']: 0;
    $offset = isset($_GET['offset']) ? $_GET['offset']: 100;
                 
   // $cron_source = 'rss_source';
   
  //  $time1 = time();
  
    $time1 = date("h:i:s");
     
     //set_time_limit(60);
          
     $fp = fopen('rss_1.txt', 'a+');
     fwrite($fp, 's(' . $start .'):' . $time1 . "\n");
     fclose($fp);
    
    include("xmltojson.php");
    get_sources_rss($cat_id, $start, $offset); 
    
    $time2 = date("h:i:s");
     $fp = fopen('rss_1.txt', 'a+');
     fwrite($fp, 'e(' . $start . '):' . $time2 . "\n");
     fclose($fp);
    
   // $time2 = time();
    
   // cron_start_end($time1, $time2, $cron_source);
}     
else if ($type == 3) {
    trying_to_fill_body();
}

else if ($type == 4) {
     $time1 = date("h:i:s");
     
     //set_time_limit(60);
          
     $fp = fopen('test_script_time.txt', 'a+');
     fwrite($fp, 's:' . $time1 . "\n");
     fclose($fp);
            
     while(true);
     
     $time2 = date("h:i:s");
     
     $fp = fopen('test_script_time.txt', 'a+');
     fwrite($fp, 'e:'.$time2 . "\n");
     fclose($fp);
}


echo('kkkkkkkkkkk');