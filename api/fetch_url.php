<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php  
error_reporting(E_ALL);
ini_set('display_errors', '1');

include('simple_html_dom.php'); 

function pr($arr){
    echo('<pre>');
    print_r($arr);
    echo('</pre>');
}

//curl http url
function getHTTP($url) {
    /* cURL Resource */
    $ch = curl_init();

    /* Set URL */
    curl_setopt($ch, CURLOPT_URL, $url);

    /* Tell cURL to return the output */
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    /* Tell cURL NOT to return the headers */
    curl_setopt($ch, CURLOPT_HEADER, false);

    /* Execute cURL, Return Data */
    $data = curl_exec($ch);
    
    return $data;
}

//parse url using diffbot                                                              
function diffbot($url) {     
                 //   $url = 'http://www.kooora.com/default.aspx?n=429169&o=n';
     $url = str_replace(" &", "&", $url);
     $url = str_replace("kooora2", "kooora", $url);
                        echo('<br />url in diffbot:' . $url . '<br />');
     $params = array('timeout' => '500000', 'token' => 'beba22041188542ff1c617b898a1d179', 'url' => $url);
     
     $diff_url = 'http://api.diffbot.com/v3/article';

     /* Update URL to container Query String of Paramaters */
     $diff_url .= '?' . http_build_query($params);
     
     $diff_url = str_replace("amp%3B", "", $diff_url);

     if (strrpos($url,"arabi21.com") !== FALSE) {
        $diff_url = urldecode($diff_url);
     }
     
              echo('<br />diff url: '. $diff_url .'<br /><br />');
     $url_parse = getHTTP($diff_url);
     $arr = json_decode($url_parse);
          pr($arr); // exit;
     //try again 
     if (!isset($arr->objects[0]->text)) {
         $url_parse = getHTTP($url);
         $arr = json_decode($url_parse);
     }
     
     $x = @$arr->objects[0]->text; 
     $y = @$arr->objects[0]->images[0]->url; 
          
     return array('x' => $x, 'y' => $y);
}

//delete text between 2 words
function delete_all_between($beginning, $end, $string) {
  $beginningPos = strpos($string, $beginning);
  $endPos = strpos($string, $end);
  if ($beginningPos === false || $endPos === false) {
    return $string;
  }

  $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

  return str_replace($textToDelete, '', $string);
}    

//fetch url body guru function - do not read it it's afraid :)
function fetch($url, $twitter = true){ 
      
      echo('url at top of fetch:' . $url . '<br />');
        $org_url = $url;  
                
        $url = str_replace('', '%20', $url); 
                        //  echo($url); exit;
        $url = str_replace('&amp;', '&', $url);
        $url = str_replace(' &amp;', '&', $url);
        $url = str_replace('&amp; ', '&', $url);
        
        
        $url = str_replace('& ', '&', $url);
        $url = str_replace(' &', '&', $url);
        $url = str_replace(' & ', '&', $url);
                                                      
             
        if( 
           strrpos($url,"webteb.com") === FALSE && 
           strrpos($url,"elshaab.org") === FALSE && 
           strrpos($url,"tnntunisia.com") === FALSE
          ) {               
         //   $url = urldecode($url); 
        }
              
        if(strrpos($url,"aljazeera.net") !== FALSE) {               
            $url = explode("/", $url);
            
            $last_part = urlencode($url[count($url)-1]);
            
            $aljazeera_url = '';
            
            for($i = 0; $i < count($url)-1; $i++) {
                $aljazeera_url .= $url[$i] . "/";
            }
            
            $aljazeera_url .= $last_part; 
            
            $url = $aljazeera_url;
          //  echo($aljazeera_url); exit;
        }
             
        if (strrpos($url,"24.ae") !== FALSE) {
            $page_name = pathinfo($url);
            
            $url = $page_name['dirname'] . '/' . urlencode($page_name['basename']); 
            
            $url = str_replace('%3', '?', $url);
            $url = str_replace('?F', '?', $url);
            $url = str_replace('?D', '=', $url);
            
          //  pr($url);
        } 
              
        if (strrpos($url,"almayadeen.net") !== FALSE) { 
            $url = explode("/", $url);
            
            $before_last_part = urlencode($url[count($url)-2]);
            
            $before_last_part = explode("-", $before_last_part);
            
            $page_id = $before_last_part[1];
            
            $url = "http://www.almayadeen.net/news/print/" . $page_id;
            
           // echo($url); exit;
        }
        
        if (strrpos($url,"alsumaria.tv") !== FALSE) { 
            $url = explode("/", $url);  
            $before_last_part = $url[count($url)-2];
            $before_last_part = urlencode($before_last_part);
                
            $sumaria_url = '';
            
            for($i = 0; $i < count($url)-2; $i++) {
                $sumaria_url .= $url[$i] . "/";
            }
            
            $sumaria_url .= $before_last_part; 
            
            $url = $sumaria_url;
              
        } 
        
        if (strrpos($url,"almesryoon.com") !== FALSE) { 
             $url = explode("/", $url);  
             $before_last_part1 = $url[count($url)-2];
             $before_last_part1 = urlencode($before_last_part1);
            
             $before_last_part2 = $url[count($url)-1];
             $before_last_part2 = urlencode($before_last_part2);
                
             $sumaria_url = '';
            
             for($i = 0; $i < count($url)-2; $i++) {
                $sumaria_url .= $url[$i] . "/";
             }
            
             $sumaria_url .= $before_last_part1 . "/" . $before_last_part2; 
            
             $url = $sumaria_url;
        } 
        
        if (strrpos($url,"sayidaty.net") !== FALSE) { 
             $url = explode("/", $url);  
             
             $before_last_part3 = $url[count($url)-3];
             $before_last_part3 = urlencode($before_last_part3);
             
             $before_last_part1 = $url[count($url)-2];
             $before_last_part1 = urlencode($before_last_part1);
            
             $before_last_part2 = $url[count($url)-1];
             $before_last_part2 = urlencode($before_last_part2);
                
             $sumaria_url = '';
            
             for($i = 0; $i < count($url)-3; $i++) {
                $sumaria_url .= $url[$i] . "/";
             }
            
             $sumaria_url .= $before_last_part1 . "/" . $before_last_part2 . '/' . $before_last_part3; 
            
             $url = $sumaria_url;
        }   
        
        if (strrpos($url,"dw.de") !== FALSE || strpos($url, "dw.com") !== FALSE) { 
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
        
        if (
             strrpos($url,"alaraby.co.uk") !== FALSE || 
             strrpos($url,"france24.com") !== FALSE || 
             strrpos($url,"lahamag.com") !== FALSE
            ) { 
                $url = explode("/", $url);  
                $before_last_part = $url[count($url)-1];
                $before_last_part = urlencode($before_last_part);
                    
                $alaraby_url = '';
                
                for($i = 0; $i < count($url)-1; $i++) {
                    $alaraby_url .= $url[$i] . "/";
                }
                
                $alaraby_url .= $before_last_part; 
                
                $url = $alaraby_url;
                //    echo($url);  exit;
        }
        
        if (strrpos($url,"khaberni.com") !== FALSE) { 
            $url = explode('?', $url);  
            
            $url = "http://www.khaberni.com/print.php?" . $url[1];
              
        } 
        
        if (strrpos($url,"alhasela.com") !== FALSE) {           
            //$url .= "?upm_export=print";     
            $url .= "?print=print";     
        }
        
        if (strrpos($url,"paltoday.tv") !== FALSE) {           
            $url .= "?tmpl=component&print=1&layout=default&page=";     
        } 
        
        if (strrpos($url,"al-balad.net") !== FALSE) {  
            $url_id = explode("=", $url);
                 
            $url = "http://www.al-balad.net/print.php?id=" . $url_id[1];     
        } 
           
        if (strrpos($url,"akhbarelyom.com") !== FALSE || strrpos($url,"alayam.com") !== FALSE || 
            strrpos($url,"alarabiya.net") !== FALSE   || strrpos($url,"almaghribtoday.net") !== FALSE 
            ) { 
            $url = explode("/", $url);
                                             //  echo($url[count($url)-1]);
            $page_name = explode(".", $url[count($url)-1]);
            $page_decoding = urlencode($page_name[0]);  
            
            $akhbarelyom_url = '';
           
            for($i = 0; $i < count($url)-1; $i++) {
                $akhbarelyom_url .= $url[$i] . "/";
            }
            
            $akhbarelyom_url .= $page_decoding . '.html'; 
            
            $url = $akhbarelyom_url;
            //echo($url);exit;
                 
        }
        
        if (strrpos($url,"lahaonline.com") !== FALSE) { 
            $url = explode("/", $url);
                                             //  echo($url[count($url)-1]);
            $page_name = explode(".", $url[count($url)-1]);
            $page_decoding = urlencode($page_name[0]);  
            
            $akhbarelyom_url = '';
           
            for($i = 0; $i < count($url)-1; $i++) {
                $akhbarelyom_url .= $url[$i] . "/";
            }
            
            $akhbarelyom_url .= $page_decoding . '.htm'; 
            
            $url = $akhbarelyom_url;
            //echo($url);exit;
                 
        }
        
        if (strrpos($url,"albiladpress.com") !== FALSE) {
            $url = str_replace("demox/news_inner.php?nid=", "article", $url);
            $url = str_replace("&", "-1.html&", $url);
            
            $url_ = explode("&", $url);
            $url = $url_[0];
            
            //echo($url); exit;
        }
        
        if (strrpos($url,"tnntunisia.com") !== FALSE) { 
           /* $url = explode("/", $url);
            $url_encode = urlencode($url[1]);                               
            
            $new_url = "http://tnntunisia.com/" . $url_encode;
            
            $url = $new_url;*/
            
        }
           
         // echo('<br />hasela'.strrpos($url,"alhasela.com").'<br />');exit;
           echo('<br />a:'. $url . '<br />');   // exit;
           
          
        $url = checkValues($url);
        
        if ($twitter) {
            $url = reverse_tinyurl($url); 
            
            if (strpos($url, "bit.ly") !== FALSE || 
                strpos($url, "shar.es") !== FALSE ||
                strpos($url, "news.google.com") !== FALSE || 
                strpos($url, "feedproxy.google.com") !== FALSE || 
                strpos($url, "fb.me") !== FALSE || 
                strpos($url, "alanba.com.kw") !== FALSE || 
                strpos($url, "t.co") !== FALSE || 
                strpos($url, "goo.gl") !== FALSE || 
                strpos($url, "ow.ly") !== FALSE 
               ){
                $url = reverse_tinyurl($url);
            } 
        }
        
        if (strrpos($url,"yanair.net") !== FALSE) {  
               
            $url .= "?print=print";     
        } 
        
        $charset = 'utf-8';
       /* if(strrpos($url,"kora.com")) { 
            $charset = 'windows-1256';
        } */
        
        echo('b:'. $url . '<br />');
        if (strpos($url, "alwatanvoice.com") !== FALSE) {
            $url_arr = explode("/", $url);
            
            $page_name = $url_arr[count($url_arr) - 1];
                                                 
            $url = "http://www.alwatanvoice.com/arabic/content/print/" . $page_name;
            
            echo('<br />watan printable: ' . $url . '<br />'); 
        }      
        
        if (strpos($url, "youm7.com") !== FALSE) {
            $url_arr = explode("/", $url);
            $id = $url_arr[count($url_arr)-1];
            
            $id_arr = explode("#", $id);
            $new_id = $id_arr[0];
            
            $url = "http://www.youm7.com/news/newsprint?newid=" . $new_id;//2061760
            
             echo('<br />youm7 printable: ' . $url . '<br />'); 
        } 
        
        if (strpos($url, "alanba.com.kw") !== FALSE) {
            $url = explode("/", $url);
            
            $aid = $url[count($url)-1];
            
            $url = "http://www.alanba.com.kw/absolutenmnew/templates/print-article.aspx?articleid=" . $aid . "&zoneid=89556 ";
        }
        
        if (strpos($url, "tayyar.org") !== FALSE || strpos($url, "arabi21.com") !== FALSE || strpos($url, "arabic.sport360.com") !== FALSE) {
            $url = explode("/", $url);  
            $before_last_part = $url[count($url)-1];
            $before_last_part = urlencode($before_last_part);
                
            $tayyar_url = '';
            
            for($i = 0; $i < count($url)-1; $i++) {
                $tayyar_url .= $url[$i] . "/";
            }
            
            $tayyar_url .= $before_last_part; 
            
            $url = $tayyar_url;
                //  echo($url);  exit;
        }
        
        if (strpos($url, "mubasher.info") !== FALSE) {
            $url = explode("/", $url);  
            $before_last_part = $url[count($url)-1];
            
            $before_last_part_arr = explode("?", $before_last_part);
            
            $before_last_part = urlencode($before_last_part_arr[0]);
                
            $tayyar_url = '';
            
            for($i = 0; $i < count($url)-1; $i++) {
                $tayyar_url .= $url[$i] . "/";
            }
            
            $tayyar_url .= $before_last_part . "?" . $before_last_part_arr[1]; 
            
            $url = $tayyar_url;
                 // echo($url);  exit;
        }
        
        if (strpos($url, "al-gornal.com") !== FALSE) {
            $url = explode("/", $url);  
            $before_last_part = $url[count($url)-2];
            $before_last_part = urlencode($before_last_part);
                
            $tayyar_url = '';
            
            for($i = 0; $i < count($url)-2; $i++) {
                $tayyar_url .= $url[$i] . "/";
            }
            
            $tayyar_url .= $before_last_part; 
            
            $url = $tayyar_url;
              //    echo($url);  exit;
        }
        
        if (strpos($url, "sabanews.net") !== FALSE) {
            $url = str_replace("/news", "/print", $url);
        }
        
            
        $return_array = array();

        $base_url = substr($url,0, strpos($url, "/",8));
        $relative_url = substr($url,0, strrpos($url, "/")+1);
        
        // Get Data
        $cc = new cURL();
                 //  $url = 'http://arabi21.com//story/831955/%D8%A8%D9%84%D8%A7%D8%AA%D9%8A%D9%86%D9%8A-%D9%8A%D9%84%D9%85%D8%AD-%D9%84%D8%A7%D8%AD%D8%AA%D9%85%D8%A7%D9%84-%D8%AA%D8%AE%D9%81%D9%8A%D9%81-%D9%82%D9%88%D8%A7%D8%B9%D8%AF-%D8%A7%D9%84%D9%84%D8%B9%D8%A8-%D8%A7%D9%84%D9%85%D8%A7%D9%84%D9%8A-%D8%A7%D9%84%D9%86%D8%B8%D9%8A%D9%81';  
        if (strpos($url, "petra.gov.jo") !== FALSE) { 
            $string = $cc->get($url, false, $charset); 
        }else{
            $string = $cc->get($url, false, $charset); 
        }    
        
        if (strpos($url, "filwajiha.com") !== FALSE) { 
            $string = file_get_contents($url);
        } 
          
          // pr('<br />string11111:' . $string.'<br />');exit;  
        
        if (strpos($url, "alaraby.co.uk") !== FALSE) {     
             if (strpos($string, "/Content/english/images/404Error.jpg") !== FALSE) {  
                 $return_array['description'] = '404';  
                 $return_array['title'] = '';  
                 $return_array['paragraph'][0]['contents'] = '404';  
                 
                 return  $return_array;
             } 
        }  
        
        if (strpos($url, "maktoob.yahoo.com") !== FALSE || strpos($url, "maktoob.news.yahoo.com") !== FALSE) {    
             if (
                   strpos($string, "عذراً... لم يتم العثور على الصفحة المطلوبة. الرجاء استخدام البحث") !== FALSE ||
                   strpos($string, "err=404") !== FALSE
                ) {              //echo($string);  exit; 
                 $return_array['description'] = '404';  
                 $return_array['title'] = '';  
                 $return_array['paragraph'][0]['contents'] = '404';  
                 
                 return  $return_array;
             } 
        }
        
        if (strpos($url, "khaberni.com") !== FALSE) {    
             if (
                   strpos($string, '<div id="mainWarpper">') === FALSE ||
                   strpos($string, "<div id='mainWarpper'>") === FALSE
                ) {             // echo($string);  exit; 
                 $return_array['description'] = '404';  
                 $return_array['title'] = '';  
                 $return_array['paragraph'][0]['contents'] = '404';  
                 
              //   return  $return_array;
             } 
        }
                              
        if (strpos($url, "naba.ps") !== FALSE) {      
             if ( trim($string) == "error") {         //echo($string);  exit;
                 $return_array['description'] = '404';  
                 $return_array['title'] = '';  
                 $return_array['paragraph'][0]['contents'] = '404';  
                 
                 return  $return_array;
             } 
        }
        
        if(strrpos($string,"HTTP Error 400") || strrpos($string,"The page you are looking for cannot be found") || 
           strrpos($string,"Bad Request - Invalid URL") || 
           strrpos($string,"An Error Was Encountered") ||  strrpos($string,'Internal Server Error') ||  strrpos($string,'Object reference not') ){
            //$org_url = str_replace("amp;", '&', $org_url);   echo('<<<<<<<<<<<<<<BAD REQUEST>>>>>>>>>>>>');
           //$string = file_get_contents($org_url);     //exit;
               echo('<br /><br /><<<<<<<<<<<<<<21111BADREQUEST11112>>>>>>>>>>>><br /><br />');   
                        
               if(strrpos($url,"arabi21.com")) {
                   /*$url_exp = explode("/", $url);
                   
                   $url_encode = urlencode($url_exp[count($url_exp)-1]);
                   
                   $new_url = '';
                   
                   for($i = 0 ; $i < count($url_exp)-1; $i++){
                       $new_url .= $url_exp[$i] . '/';
                   }
                   
                   $new_url = $new_url . $url_encode;  
                   
                   $string = file_get_contents($new_url);   */

                  // $string = file_get_contents($org_url);
               }
               else {
                   //echo('<br /><br /> $new_url:' . $new_url . '<br />'); 
                   //$string = $cc->get($url, false, $charset); 
                 //  $string = file_get_contents($url); 
               }
        }
        
        if ($string == ""){
            //$org_url = str_replace("amp;", '&', $url); 
           // $string = file_get_contents($org_url);     //exit;
        }
        //pr(str_ireplace('<','',($string)));
        //exit;          
       // $string = str_replace(array("\n","\r","\t",'</span>','</div>'), '', $string);
        //$encooding = iconv_get_encoding($string);
         
        /*$string = preg_replace('/(<(div|span)\s[^>]+\s?>)/',  '', $string);   */
                //      pr('<br />string:' . $string.'<br />');exit;
        $encode_char = mb_detect_encoding($string, "auto", true);
        if($encode_char == ""){
            $encode_char = (mb_detect_encoding($string));
        }
        //pr($encode_char);
        if ($encode_char != "UTF-8" && trim($encode_char) != ""){   
            //$string = utf8_encode($string);  
            $string = iconv($encode_char, "UTF-8//TRANSLIT//IGNORE", $string);    
        }
        else if ($encode_char != "UTF-8") {
            $string = iconv('CP1256', "UTF-8//TRANSLIT//IGNORE", $string);    
        }
             
        // Parse Title
        $nodes = extract_tags( $string, 'title' );       
        $return_array['title'] = trim(@$nodes[0]['contents']);
           
        //meta for youtube img
        $meta = @get_meta_tags($url);
        $return_array['youtube_img'] = @$meta['twitter:image'];
                  
        // Parse Base
        $base_override = false; 
        $base_regex = '/<base[^>]*'.'href=[\"|\'](.*)[\"|\']/Ui';
        preg_match_all($base_regex, $string, $base_match, PREG_PATTERN_ORDER);
        if(strlen(@$base_match[1][0]) > 0){
            $base_url = $base_match[1][0];
            $base_override = true; 
        }
            
        // Parse Description
        $return_array['description'] = '';
        $nodes = extract_tags( $string, 'meta' );
          // pr($nodes);  exit;
        foreach($nodes as $node){
            if (strtolower(@$node['attributes']['name']) == 'description'){
                $return_array['description'] = str_replace('<!--', '',trim(@$node['attributes']['content']));
            }  
                
            if ($return_array['description'] == "" && strtolower(@$node['attributes']['property']) == 'og:description'){
                    $return_array['description'] = str_replace('<!--', '', trim($node['attributes']['content']));
            }
        }
        
        if ( strpos($url, 'altibbi.com') !== FALSE){    //echo('zzzzzzzzzzzzzzz');
              if ( strpos($string, 'og:image') === FALSE){ 
                  return; 
              }
        }
                      //echo($url);  exit;
                 // pr($return_array);  exit;
        $return_array['paragraph'] = '';
        
     /*   if ( strpos($url, 'kooora.com') !== FALSE){ 
            $nodes = extract_tags( $string, 'div' );
        }
        else {   */
            $nodes = extract_tags( $string, 'p' );
      /*  }   */
                   
        $first_item_flag = 1;
                
        if (strpos($url, "www.qudstv.com") !== FALSE) { 
            $paragraph_length = 67;
        }
        else{
            $paragraph_length = 78;
        }
        
        if ( strpos($url, "alqabas.com") !== FALSE) $paragraph_length = 55;
        if ( strpos($url, "www.okaz.com") !== FALSE) $paragraph_length = 77;
               // echo('vvvvvvvvvvvvvvvvvvv');  pr($nodes);exit;
        foreach($nodes as $node) {    //echo('vvvvvvvvvvvvvvvvvvv');  pr($node);exit;  
            if ( strpos($url, "bbc.co.uk") !== FALSE || strpos($url, "bbc.com") !== FALSE ) {
                if ($first_item_flag) {
                    $first_item_flag = 0;
                    continue;
                }
            }
            
            if ( strpos($url, 'felesteen.ps') !== FALSE || 
                 strpos($url, 'goal.com') !== FALSE || 
                 strpos($url, 'al-madina.com') !== FALSE || 
                 strpos($url, 'dotmsr.com') !== FALSE || 
                 strpos($url, 'alkhaleej.ae') !== FALSE || 
                 strpos($url, 'jo24.net') !== FALSE || 
                 strpos($url, 'maqar.com') !== FALSE || 
                 strpos($url, 'jn-news.com') !== FALSE || 
                 strpos($url, 'almogaz.com') !== FALSE || 
                 strpos($url, 'yanair.net') !== FALSE || 
                 strpos($url, 'almesryoon.com') !== FALSE || 
                 strpos($url, 'alsharq.net.sa') !== FALSE || 
                 strpos($url, 'reuters.com') !== FALSE || 
                 strpos($url, 'zoomtunisia.tn') !== FALSE || 
                 strpos($url, 'anaween.com') !== FALSE || 
                 strpos($url, 'tounessna.info') !== FALSE || 
                 strpos($url, 'nna-leb.gov.lb') !== FALSE || 
                 strpos($url, 'alriadey.com') !== FALSE || 
                 strpos($url, 'almayadeen.net') !== FALSE || 
                 strpos($url, 'hawaaworld.com') !== FALSE || 
                 strpos($url, 'bna.bh') !== FALSE || 
                 strpos($url, 'goodykitchen.com') !== FALSE || 
                 //strpos($url, 'manalonline.com') !== FALSE || 
                 strpos($url, 'hassacom.com') !== FALSE || 
                 strpos($url, 'kuwaitnews.com') !== FALSE || 
                 strpos($url, 'q8news.com') !== FALSE || 
                 strpos($url, 'hattpost.com') !== FALSE || 
                 strpos($url, 'alayam.com') !== FALSE || 
                 strpos($url, 'olympic.qa') !== FALSE || 
                 strpos($url, 'sportksa') !== FALSE || 
                 strpos($url, 'otv.com.lb') !== FALSE || 
                 strpos($url, 'cdn.alkass.net') !== FALSE || 
                 strpos($url, 'alyaoum24.com') !== FALSE || 
                 strpos($url, 'arabic.cnn.com') !== FALSE || 
                 strpos($url, 'nok6a.net') !== FALSE || 
                 strpos($url, 'android4ar.com') !== FALSE || 
                 strpos($url, 'arabsturbo.com') !== FALSE || 
                 strpos($url, 'sport.ahram.org') !== FALSE || 
                 //strpos($url, 'fatafeat.com') !== FALSE || 
                 strpos($url, 'chouftv.ma') !== FALSE || 
                 strpos($url, '3eesho.com') !== FALSE || 
                 strpos($url, 'alarabiya.net') !== FALSE || 
                 strpos($url, 'fajr.sa') !== FALSE || 
                 strpos($url, 'echoroukonline.com') !== FALSE || 
                 strpos($url, 'saidaonline.com') !== FALSE || 
                 strpos($url, 'naseej.net') !== FALSE || 
                 strpos($url, 'realmadrid.com') !== FALSE || 
                 strpos($url, 'moe.gov.qa') !== FALSE || 
                 strpos($url, 'reqaba.com') !== FALSE || 
                 strpos($url, '3seer.net') !== FALSE || 
                 strpos($url, 'arn.ps') !== FALSE || 
                 strpos($url, 'klmty.net') !== FALSE || 
                 strpos($url, 'n1t1.com') !== FALSE || 
                 strpos($url, 'doniatech.com') !== FALSE || 
                 strpos($url, 'buyemen.com') !== FALSE || 
                 strpos($url, 'alsawtnews.cc') !== FALSE || 
                 strpos($url, 'shorouknews.com') !== FALSE || 
                 strpos($url, 'sabqq.org') !== FALSE || 
                 strpos($url, 'mubasher.info') !== FALSE || 
                 strpos($url, 'elheddaf.com') !== FALSE || 
                 strpos($url, 'alnoornews.net') !== FALSE || 
                 strpos($url, 'alquds.com') !== FALSE || 
                 strpos($url, 'argaam.com') !== FALSE || 
                 strpos($url, 'th3professional.com') !== FALSE || 
                 strpos($url, 'alforatnews.com') !== FALSE || 
                 strpos($url, 'alhurra.com') !== FALSE || 
                 //strpos($url, 'atyabtabkha.3a2ilati.com') !== FALSE || 
                 strpos($url, 'baareq.com.sa') !== FALSE || 
                 strpos($url, 'alliraqnews.com') !== FALSE || 
                 strpos($url, 'akhbarak.net') !== FALSE || 
                 strpos($url, 'kuna.net.kw') !== FALSE || 
                 strpos($url, 'newsqassim.com') !== FALSE || 
                 strpos($url, 'lebanonfiles.com') !== FALSE || 
                 strpos($url, 'hashtagarabi.com') !== FALSE || 
                 strpos($url, 'arabesque.tn') !== FALSE || 
                 strpos($url, 'lahamag.com') !== FALSE || 
                 strpos($url, 'hafralbaten.com') !== FALSE || 
                 strpos($url, '7iber.com') !== FALSE || 
                 strpos($url, 'egyptiannews.net') !== FALSE || 
                 strpos($url, 'elshaab.org') !== FALSE || 
                 strpos($url, 'paltimes.net') !== FALSE || 
                 strpos($url, 'al-balad.net') !== FALSE || 
                 strpos($url, 'shahiya.com') !== FALSE || 
                 strpos($url, 'moroccoeyes') !== FALSE || 
                 strpos($url, 'altibbi.com') !== FALSE || 
                 strpos($url, 'arriyadiyah.com') !== FALSE || 
                 strpos($url, 'ar.yabiladies.com') !== FALSE || 
                 strpos($url, 'qabaq.com') !== FALSE || 
                 strpos($url, 'freeswcc.com') !== FALSE || 
                 strpos($url, 'azzaman.com') !== FALSE || 
                 strpos($url, 'dasmannews.com') !== FALSE || 
                 strpos($url, 'yemenat.net') !== FALSE || 
                 strpos($url, 'anazahra.com') !== FALSE || 
                 strpos($url, 'annahar.com') !== FALSE || 
                 strpos($url, 'rsssd.com') !== FALSE || 
                 strpos($url, 'omannews.gov.om') !== FALSE || 
                 strpos($url, 'pal24.net') !== FALSE || 
                 strpos($url, 'aldawadmi.net') !== FALSE || 
                 strpos($url, 'anbaanews.com') !== FALSE || 
                 strpos($url, 'futuretvnetwork.com') !== FALSE || 
                 strpos($url, 'forbesmiddleeast.com') !== FALSE || 
                 strpos($url, 'autosearch.me') !== FALSE || 
                 strpos($url, 'palsawa.com') !== FALSE || 
                 strpos($url, 'suhailnews.blogspot.com') !== FALSE || 
                 strpos($url, 'ajel.sa') !== FALSE || 
                 strpos($url, 'arabi21.com') !== FALSE || 
                 strpos($url, 'zahran.org') !== FALSE || 
                 strpos($url, 'lebanondebate.com') !== FALSE || 
                 strpos($url, 'annaharkw.com') !== FALSE || 
                 strpos($url, 'assabeel.net') !== FALSE || 
                 strpos($url, 'qudspress.com') !== FALSE || 
                 strpos($url, 'al-seyassah.com') !== FALSE || 
                 strpos($url, 'alarabalyawm.net') !== FALSE || 
                 strpos($url, 'marebpress.net') !== FALSE || 
                 strpos($url, 'arabapps.org') !== FALSE || 
                 strpos($url, 'oleeh.com') !== FALSE || 
                 strpos($url, 'etilaf.org') !== FALSE || 
                 strpos($url, 'q8ping.com') !== FALSE || 
                 strpos($url, 'anbaaonline.com') !== FALSE || 
                 strpos($url, 'spa.gov.sa') !== FALSE || 
                 strpos($url, 'babnet.net') !== FALSE || 
                 strpos($url, 'alwatannews.net') !== FALSE || 
                 strpos($url, 'tuniscope.com') !== FALSE || 
                 strpos($url, 'masrawy.com') !== FALSE || 
                 strpos($url, 'akhbarlibya24.net') !== FALSE || 
                 strpos($url, 'linkis.com') !== FALSE || 
                 strpos($url, 'android-time.com') !== FALSE || 
                 strpos($url, 'tounesnews.com') !== FALSE || 
                 strpos($url, 'filwajiha.com') !== FALSE || 
                 strpos($url, 'alikhbaria.com') !== FALSE || 
                 strpos($url, 'alsopar.com') !== FALSE || 
                 strpos($url, 'fath-news.com') !== FALSE || 
                 strpos($url, 'alrafidain.org') !== FALSE || 
                 strpos($url, 'yen-news.com') !== FALSE || 
                 strpos($url, 'aldostornews.com') !== FALSE || 
                 strpos($url, 'alshamiya-news.com') !== FALSE || 
                 strpos($url, 'orient-news.net') !== FALSE || 
                 strpos($url, 'ahram.org.eg') !== FALSE || 
                 strpos($url, 'ismailyonline.com') !== FALSE || 
                 strpos($url, 'sudanmotion.com') !== FALSE || 
                 strpos($url, 'tracksport.net') !== FALSE || 
                 strpos($url, 'wikise7a.com') !== FALSE || 
                 strpos($url, 'wonews.net') !== FALSE || 
                 strpos($url, 'alwasatnews.com') !== FALSE || 
                 strpos($url, 'libyanow.net.ly') !== FALSE || 
                 strpos($url, 'lana-news.ly') !== FALSE || 
                 strpos($url, 'euronews.com') !== FALSE || 
                 strpos($url, 'alhasela.com') !== FALSE || 
                 strpos($url, 'arjja.com') !== FALSE || 
                 strpos($url, 'arab4x4.com') !== FALSE || 
                 strpos($url, 'albawabhnews.com') !== FALSE || 
                 strpos($url, 'akhbar-alkhaleej.com') !== FALSE || 
                 strpos($url, 'elaph.com') !== FALSE || 
                 strpos($url, 'youm7.com') !== FALSE || 
                 strpos($url, 'skynewsarabia.com') !== FALSE || 
                 strpos($url, 'akhbarelyom.com') !== FALSE || 
                 strpos($url, 'alarab.qa') !== FALSE || 
                 strpos($url, 'Elaph') !== FALSE || 
                 strpos($url, 'elaph') !== FALSE || 
                 strpos($url, 'hibapress.com') !== FALSE || 
                 strpos($url, 'alkhabarnow.net') !== FALSE || 
                 strpos($url, 'alnilin.com') !== FALSE || 
                 strpos($url, 'qna.org.qa') !== FALSE || 
                 strpos($url, 'attounissia.com.tn') !== FALSE || 
                 strpos($url, 'nwafecom.net') !== FALSE || 
                 strpos($url, 'dailymedicalinfo.com') !== FALSE || 
                 strpos($url, 'ardroid.com') !== FALSE || 
                 strpos($url, 'ham-24.com') !== FALSE || 
                 strpos($url, 'mini-news.net') !== FALSE || 
                 strpos($url, 'steelbeauty.net') !== FALSE || 
                 strpos($url, 'wafa.com.sa') !== FALSE || 
                 strpos($url, 'almowaten.net') !== FALSE || 
                 strpos($url, 'asir.com') !== FALSE || 
                 strpos($url, 'arabi21.com') !== FALSE || 
                 strpos($url, 'hilalcom.net') !== FALSE || 
                 strpos($url, 'france24.com') !== FALSE || 
                 strpos($url, 'bahrainalyoum.net') !== FALSE || 
                 strpos($url, 'layalina.com') !== FALSE || 
                 strpos($url, 'elbilad.net') !== FALSE || 
                 strpos($url, 'almuraba.net') !== FALSE || 
                 strpos($url, 'zamanarabic.com') !== FALSE || 
                 strpos($url, 'almaghribtoday.net') !== FALSE || 
                 strpos($url, 'aliraqnews.com') !== FALSE || 
                 strpos($url, 'adhamiyahnews.com') !== FALSE || 
                 strpos($url, 'hiamag.com') !== FALSE || 
                 strpos($url, 'beinsports.com') !== FALSE || 
                 strpos($url, 'kooora.com') !== FALSE || 
                 strpos($url, 'kooora2.com') !== FALSE || 
                 strpos($url, 'middle-east-online.com') !== FALSE || 
                 strpos($url, 'almotamar.net') !== FALSE || 
                 strpos($url, 'makkahnewspaper.com') !== FALSE || 
                 strpos($url, 'addustour.com') !== FALSE || 
                 strpos($url, 'al-mashhad.com') !== FALSE || 
                 strpos($url, 'paltoday.ps') !== FALSE || 
                 strpos($url, 'snobonline.net') !== FALSE || 
                 strpos($url, 'aljoumhouria.com') !== FALSE || 
                 strpos($url, 'assafir.com') !== FALSE || 
                 strpos($url, 'alaraby.co.uk') !== FALSE || 
                 strpos($url, 'lahaonline.com') !== FALSE || 
                 strpos($url, 'akherkhabaronline.com') !== FALSE || 
                 strpos($url, 'albayan.ae') !== FALSE || 
                 strpos($url, 'ounousa.com') !== FALSE || 
                 strpos($url, 'almashhad.net') !== FALSE || 
                 strpos($url, 'sayidaty.net') !== FALSE || 
                 strpos($url, 'hihi2.com') !== FALSE || 
                 strpos($url, 'aljazeera.net') !== FALSE || 
                 strpos($url, '3alyoum.com') !== FALSE || 
                 strpos($url, 'wam.ae') !== FALSE || 
                 strpos($url, 'yumyume.com') !== FALSE || 
                 strpos($url, 'alkhaleejaffairs.org') !== FALSE || 
                 strpos($url, 'alwatan.kuwait.tt') !== FALSE || 
                 strpos($url, 'alamalmal.net') !== FALSE || 
                 strpos($url, 'ammonnews.net') !== FALSE || 
                 strpos($url, 'ashorooq.net') !== FALSE || 
                 strpos($url, 'techplus.me') !== FALSE || 
                 strpos($url, 'assawsana.com') !== FALSE || 
                 strpos($url, 'basnews.com') !== FALSE || 
                 strpos($url, 'al-sharq.com') !== FALSE || 
                 strpos($url, 'mbc.net') !== FALSE || 
                 strpos($url, 'almasryalyoum.com') !== FALSE || 
                 strpos($url, 'bahrainmirror.no-ip.info') !== FALSE || 
                 strpos($url, 'alwefaq.net') !== FALSE || 
                 strpos($url, 'al-akhbar.com') !== FALSE || 
                 strpos($url, 'sahelmaten.com') !== FALSE || 
                 strpos($url, 'fcbarcelona.com') !== FALSE || 
                 strpos($url, 'alsawt.net') !== FALSE || 
                 strpos($url, 'almanar.com.lb') !== FALSE || 
                 strpos($url, 'rudaw.net') !== FALSE || 
                 strpos($url, 'alriyadh.com') !== FALSE || 
                 strpos($url, 'altaleea.com') !== FALSE || 
                 strpos($url, 'annaharnews.net') !== FALSE || 
                 strpos($url, 'aljubailtoday.com.sa') !== FALSE || 
                 strpos($url, 'tnntunisia.com') !== FALSE || 
                 strpos($url, 'roaanews.net') !== FALSE || 
                 strpos($url, 'ennaharonline.com') !== FALSE || 
                 strpos($url, 'o-t.tv') !== FALSE || 
                 strpos($url, 'al-gornal.com') !== FALSE || 
                 strpos($url, 'rotanamags.net') !== FALSE || 
                 strpos($url, 'arabitechnomedia.com') !== FALSE || 
                 strpos($url, 'hespress.com') !== FALSE || 
                 strpos($url, 'al-watan.com') !== FALSE || 
                 strpos($url, 'elfagr.org') !== FALSE || 
                 strpos($url, 'dostor.org') !== FALSE || 
                 strpos($url, 'masralarabia.com') !== FALSE || 
                 strpos($url, 'elwatannews.com') !== FALSE || 
                 strpos($url, 'arabic.sport360.com') !== FALSE || 
                 strpos($url, 'alhakea.com') !== FALSE || 
                 strpos($url, 'zamalekfans.com') !== FALSE || 
                 strpos($url, 'alhayat.com') !== FALSE || 
                 strpos($url, 'asir.net_xxxxxx') !== FALSE || 
                 strpos($url, 'sabq.org') !== FALSE || 
                 strpos($url, 'fashion4arab.com') !== FALSE || 
                 strpos($url, 'GalerieArtciles') !== FALSE || 
                 strpos($url, 'yemen-press.com') !== FALSE || 
                 strpos($url, 'nas.sa') !== FALSE || 
                 strpos($url, 'royanews.tv') !== FALSE || 
                 strpos($url, 'euronews') !== FALSE || 
                 strpos($url, 'elfann.com') !== FALSE || 
                 strpos($url, 'wikise7a') !== FALSE || 
                 strpos($url, 'alquds.co.uk') !== FALSE || 
                 strpos($url, 'hroobnews.com') !== FALSE || 
                 strpos($url, 'el-balad.com') !== FALSE || 
                 strpos($url, 'shabiba.com') !== FALSE || 
                 strpos($url, 'alanba.com.kw') !== FALSE || 
                 strpos($url, 'rasdnews.net') !== FALSE || 
                 strpos($url, 'manchestercityfc.ae') !== FALSE || 
                 strpos($url, 'ng4a.com') !== FALSE || 
                 strpos($url, 'lebwindow.net') !== FALSE || 
                 strpos($url, 'alsumaria.tv') !== FALSE || 
                 strpos($url, 'alkhabarsport.com') !== FALSE || 
                 strpos($url, 'alkhabarkw.com') !== FALSE || 
                 strpos($url, 'arabhardware.net') !== FALSE || 
                 strpos($url, 'alroeya.ae') !== FALSE || 
                 strpos($url, 'watn-news.com') !== FALSE || 
                 strpos($url, 'omandaily.om') !== FALSE || 
                 strpos($url, 'almustaqbal.com') !== FALSE || 
                 strpos($url, 'atheer.om') !== FALSE ||       
                 strpos($url, 'tayyar.org') !== FALSE ||       
                // strpos($url, 'alsharq.net.sa') !== FALSE ||       
                 strpos($url, 'syrianow.sy') !== FALSE || 
                 strpos($url, 'fifa.com') !== FALSE || 
                 strpos($url, 'le360.ma') !== FALSE || 
                 strpos($url, 'tunisien.tn') !== FALSE || 
                 strpos($url, 'tunisien.tn') !== FALSE || 
                 strpos($url, 'alborsanews.com') !== FALSE || 
                 strpos($url, 'yallakora.com') !== FALSE || 
                 strpos($url, 'alkhaleejonline.net') !== FALSE || 
                 strpos($url, 'ajialq8.com') !== FALSE || 
                 strpos($url, 'raialyoum.com') !== FALSE || 
                 strpos($url, 'safa.ps') !== FALSE || 
                 strpos($url, 'alwasat.com.kw') !== FALSE || 
                 strpos($url, 'twasul.info') !== FALSE || 
                 strpos($url, 'lbcgroup.tv') !== FALSE || 
                 strpos($url, 'goalna.com') !== FALSE || 
                 strpos($url, 'al-jazirah.com') !== FALSE || 
                 strpos($url, 'ar.beinsports.net') !== FALSE || 
                 strpos($url, 'alittihad.ae') !== FALSE || 
                 strpos($url, 'mmaqara2t.com') !== FALSE || 
                 strpos($url, 'ittinews.net') !== FALSE || 
                 strpos($url, 'iraqdirectory.com') !== FALSE || 
                 strpos($url, 'acakuw.com') !== FALSE || 
               //  strpos($url, 'moheet.com') !== FALSE || 
                 strpos($url, 'alwasat.ly') !== FALSE || 
                 strpos($url, 'adwaalwatan.com') !== FALSE || 
                 strpos($url, 'hyperstage.net') !== FALSE || 
                 strpos($url, 'alkoutnews.net') !== FALSE || 
                 strpos($url, 'alwatan.com') !== FALSE || 
                 strpos($url, 'ittisport.com') !== FALSE || 
                 strpos($url, 'arbdroid.com') !== FALSE || 
                 strpos($url, 'sea7htravel.com') !== FALSE || 
                 strpos($url, 'electrony.net') !== FALSE || 
                 strpos($url, 'alkuwaityah.com') !== FALSE || 
                 strpos($url, 'alrayalaam.com') !== FALSE || 
                 strpos($url, 'alahlyegypt.com') !== FALSE || 
                 strpos($url, 'Alkuwaityah.com') !== FALSE || 
                 strpos($url, 'electronynet') !== FALSE || 
                 strpos($url, 'dw.com') !== FALSE || 
                 strpos($url, 'aleqt.com') !== FALSE || 
                 strpos($url, 'dw.de') !== FALSE) {
                break;
            }
              //echo('yyyyyyyyyyyyyyyyyyyyyyyy');  echo($url);     exit;
              
            //  echo($node['contents']);
            if ( strpos($url, 'www.kaahe.org/health') !== FALSE){    /// echo('zzzzzzzzzzzzzzz');
                    break; 
            }
                     //  exit;
            if (strpos($url, "layalina.com") !== FALSE || strpos($url, "eqtsad.net") !== FALSE) {  
                $node['contents'] = delete_all_between("<a", "</a>", $node['contents']); 
                $node['contents'] = delete_all_between("<a", "</a>", $node['contents']); 
                $node['contents'] = delete_all_between("<a", "</a>", $node['contents']); 
                $node['contents'] = delete_all_between("<a", "</a>", $node['contents']); 
                $node['contents'] = delete_all_between("<a", "</a>", $node['contents']); 
            }
            
            if (strpos($url, "eqtsad.net") !== FALSE) {
                $node['contents'] = delete_all_between("<ul", "</ul>", $node['contents']);
                $node['contents'] = delete_all_between("<ul", "</ul>", $node['contents']);
                $node['contents'] = delete_all_between("<ul", "</ul>", $node['contents']);
                $node['contents'] = delete_all_between("<ul", "</ul>", $node['contents']);
                $node['contents'] = delete_all_between("<li", "</li>", $node['contents']);
                $node['contents'] = delete_all_between("<li", "</li>", $node['contents']);
                $node['contents'] = delete_all_between("<li", "</li>", $node['contents']);
                $node['contents'] = delete_all_between("<li", "</li>", $node['contents']);
                $node['contents'] = delete_all_between("<li", "</li>", $node['contents']);
                
                $node['contents'] = delete_all_between("<script", "</script>", $node['contents']);
                $node['contents'] = delete_all_between("<script", "</script>", $node['contents']);
                $node['contents'] = delete_all_between("<script", "</script>", $node['contents']);
                $node['contents'] = delete_all_between("<script", "</script>", $node['contents']);
                $node['contents'] = delete_all_between("<script", "</script>", $node['contents']);
            }
            
            $node['contents'] = str_replace(array("<br>", "<br />"), "-xx-", $node['contents']);
            
            $node['contents'] = trim(strip_tags($node['contents']));
            
            $node['contents'] = preg_replace("/\s{2,}/"," ", $node['contents']);  //remove more than 2 spaces b/w 2 words
            
            $node['contents'] = str_replace("-xx-", "<br/>", $node['contents']); 
            
            if (mb_strlen($node['contents'], "UTF-8") < $paragraph_length) {
                unset($node);         
            }
            else {     
                $node['len'] = mb_strlen($node['contents'], "UTF-8"); 
                               
                if (strpos($url, ".annahar.comXXXXXXXXXXXX") !== FALSE) {      
                    if ( 
                          strpos($node['contents'], 'يلفت موقع النهار الألكتروني') === FALSE &&
                          strpos($node['contents'], 'getElementsByTagName') === FALSE &&
                          strpos($node['contents'], 'I added a video to a') === FALSE &&
                          strpos($node['contents'], 'حياتنا <br/> التربية والأسرة <br/> الثنائي <br/> حياتي <br/> في العمل <br/> مجتمعنا <br/>') === FALSE &&
                          strpos($node['contents'], 'صحّة وجمال<br/> صحة <br/> صحة جنسية <br/> لياقة بدنية <br/> معلومات صحية <br/>') === FALSE &&
                          strpos($node['contents'], 'صحّة وجمال<br/> صحة <br/> صحة جنسية <br/> لياقة بدنية <br/> معلومات صحية <br/>') === FALSE &&
                          strpos($node['contents'], 'إقتصاد<br/> إقتصاد لبناني<br/> أقلام إقتصادية<br/> تقارير وتحاليل<br/> مال وأعمال<br/> أخبار الشركات<br/> مصارف وأسواق<br/> عقارات وإنشاءات<br/> عالم الطاقة<br/> متفرقات<br/>') === FALSE &&
                          strpos($node['contents'], 'صحّة وجمال<br/> صحة <br/> صحة جنسية <br/> لياقة بدنية <br/> معلومات صحية <br/>') === FALSE &&
                          strpos($node['contents'], 'موضة وجمال<br/> أخبار - موضة وجمال <br/> اخترنا لك <br/> اطلالة اليوم <br/> جمال <br/> مقابلات وريبورتاج <br/>') === FALSE
                       ) {
                       // $return_array['paragraph'][] = $node;
                    } 
                }
                else if (strpos($url, "http://petra.gov.jo") !== FALSE) {
                    if ( 
                          strpos($node['contents'], 'جميع الحقوق محفوظة') === FALSE
                        ) {
                            $return_array['paragraph'][] = $node; 
                        }
                }  
                else{    
                    if ( 
                          strpos($node['contents'], 'To view this video please enable JavaScript') === FALSE &&
                          strpos($node['contents'], 'getElementsByTagName') === FALSE &&
                          strpos($node['contents'], 'jQuery') === FALSE &&
                          strpos($node['contents'], 'HTML') === FALSE &&
                          strpos($node['contents'], 'I added a video to a') === FALSE &&
                          strpos($node['contents'], 'أنت الآن في') === FALSE &&
                          strpos($node['contents'], 'h.toString()') === FALSE 
                   ) {
                       $return_array['paragraph'][] = $node; 
                   }
                }
            }
        }
            
            
              //    echo ('rrrrrrrrrrrrrrrrrrr');pr($return_array) ;exit;
        //almostaqbal                   
        if ($return_array['paragraph'] == '') {    
            if (strpos($url, "shorouqoman.com") !== FALSE || 
                strpos($url, 'alrakoba.net') !== FALSE || 
                strpos($url, 'kharjhome.com') !== FALSE || 
               // strpos($url, 'hassacom.com') !== FALSE || 
                strpos($url, 'ham-24.com') !== FALSE || 
                strpos($url, 'altibbi.com') !== FALSE || 
               // strpos($url, 'shabiba.com') !== FALSE || 
                strpos($url, 'tracksport.net') !== FALSE || 
               // strpos($url, 'alsumaria.tv') !== FALSE || 
                strpos($url, 'aljouf-news.com') !== FALSE || 
                strpos($url, 'lbcgroup.tv') !== FALSE || 
                strpos($url, 'al-watan.com') !== FALSE || 
                strpos($url, 'cdn.alkass.net') !== FALSE || 
                strpos($url, 'sabqq.org') !== FALSE /*|| 
                strpos($url, 'rsssd.com') !== FALSE */
               ) {
                $nodes = extract_tags( $string, 'span' ); 
            }
            else {  
                $nodes = extract_tags( $string, 'div' );
            }
              
            $first_item_flag1 = 1; 
            
            if (strpos($url, "arabi21.com") !== FALSE) {
                $nodes = $return_array;
            }
                         // echo('<pre>');        print_r($nodes); 
            foreach($nodes as $node) {  
                               
                //for donia wattan
                if (strpos(@$node['contents'], 'الصفحة المطلوبة غير موجودة') !== FALSE || strpos(@$node['contents'], 'قد تكون اتبعت رابط خاطئ ') !== FALSE){
                    continue;
                }
               
                $node['contents'] = trim(strip_tags(@$node['contents']));
                                
                $node['contents'] = preg_replace("/\s{2,}/"," ", @$node['contents']);  //remove more than 2 spaces b/w 2 words
                //    echo('<pre>');        print_r($node['contents']);    echo('</pre>'); 
            /*    if (mb_strlen($node['contents'], "UTF-8") < $paragraph_length && strpos($url, "kooora.com") === FALSE ) {  //else main paragraph 
                    unset($node);       //echo('ayman<br />');
                    
                  //  echo ('<br />ddddddddddddddd');  echo($url);  exit;
                }  
                else */{      
                    $node['len'] = mb_strlen(@$node['contents'], "UTF-8"); 
                               
                    if ( strpos($url, "www.okaz.com") !== FALSE) {  
                        if (count($node['attributes']) == 0){
                            $return_array['paragraph'][] = $node; 
                        }
                    }
                  //  else if (strpos($url, "www.almustaqbal.com") !== FALSE) {
                       // $return_array['paragraph'][] = $node;   
                  //  }
                    /*else if (strpos($url, "tracksport.net") !== FALSE) {
                       // pr($node);  
                    } */
                    else if (strpos($url, ".assabeel.netXXXXXXXXXX") !== FALSE) {
                        
                        if ( 
                          strpos($node['contents'], 'To view this video please enable JavaScript') === FALSE &&
                          strpos($node['contents'], 'firstChild.nodeValu') === FALSE &&
                          strpos($node['contents'], 'اترياضةمقالاتدراساتاسلامياتثقافةم') === FALSE &&
                          strpos($node['contents'], 'التصويت من تعتقد أنها الشخصية الأهم لعام') === FALSE &&
                          strpos($node['contents'], 'حجم الخط تصغير') === FALSE &&
                          strpos($node['contents'], 'options.element') === FALSE &&
                          strpos($node['contents'], 'I added a video to a') === FALSE &&
                          strpos($node['contents'], 'أخبار ذات صلة') === FALSE &&
                          strpos($node['contents'], 'getElementsByTagName') === FALSE 
                       ) {
                          /*  $html = file_get_html($url);  
                            foreach($html->find('div.itemIntroText') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                   
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; */
                       }   
                    //  $html = file_get_html($url);
                      //  $divContent =  $html->find('div#itemIntroText', 0);
                        //         echo('<pre>');        print_r($divContent);    echo('</pre>'); 
                       // $return_array['paragraph'][] = $node;
                    }
                    else if (strpos($url, "yemen-press.net") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.complete') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }  
                    else if (strpos($url, "al-jazirah.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.writers-blk-bttm-left') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }  
                    else if (strpos($url, "filgoal.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div#ctl00_cphFilGoalMain_pnlNewsBody') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                                  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    } 
                    else if (strpos($url, "altibbi.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('span.article-description') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }  
                    else if (strpos($url, "sportksa") !== FALSE || strpos($url, "alborsanews.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.entry') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                                                       
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                             // pr($return_array);exit;
                    }
                    else if (strpos($url, "alriyadh.comXXXXXXXXXXXXXX") !== FALSE) {  
                             /*$html = file_get_html($url);  
                             foreach($html->find('div#article_text') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                                                       
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                             // pr($return_array);exit;   */
                    }  
                    else if (strpos($url, "alsharq.net.sa") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.entry') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<h4", "</h4>", $x);  
                              $x = delete_all_between("<h4", "</h4>", $x);  
                              $x = delete_all_between("<h4", "</h4>", $x);  
                              
                              $x = delete_all_between("<blockquote", "</blockquote>", $x);  
                              $x = delete_all_between("<blockquote", "</blockquote>", $x);  
                              $x = delete_all_between("<blockquote", "</blockquote>", $x);  
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }  
                    else if (strpos($url, "tracksport.net") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('span.largfont') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }  
                    else if (strpos($url, "7iber.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.entry-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              if ( isset($x) ) {
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                   
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $return_array['paragraph'][0]['contents'] = $x;  
                              }  
                              else{
                                  foreach($html->find('div.e-content') as $e) 
                                  $x = delete_all_between("<div", "</div>", $e->innertext);
                                  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                   
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $return_array['paragraph'][0]['contents'] = $x;
                              }
                            
                              $return_array['paragraph'][] = $node; 
                              
                              //pr($return_array);exit;
                    }  
                    else if (strpos($url, "alrakoba.net") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('span.rakoba23') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    } 
                    else if (strpos($url, "tayyar.org") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.textWrapHere') as $e) 
                              $x = $e->innertext;  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    } 
                    else if (strpos($url, "iraqdirectory.comXXXXXXXXXXXXXXXXX") !== FALSE) {  
                           /*  $html = file_get_html($url);  
                             foreach($html->find('div#ctl00_ContentPlaceHolder1_DocumentBody') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;    */
                    }
                    else if (strpos($url, "mbc.net") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.wrap_article') as $e) 
                              $x = $e->innertext;  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $x = delete_all_between("<h2", "</h2>", $x);
                              $x = delete_all_between("<h2", "</h2>", $x);
                              $x = delete_all_between("<h2", "</h2>", $x);
                              $x = delete_all_between("<h2", "</h2>", $x);
                              
                              $x = delete_all_between("<dd", "</dd>", $x);
                              $x = delete_all_between("<dd", "</dd>", $x);
                              $x = delete_all_between("<dd", "</dd>", $x);
                              $x = delete_all_between("<dd", "</dd>", $x);
                              $x = delete_all_between("<dd", "</dd>", $x);
                              
                              $x = delete_all_between("<em", "</em>", $x);
                              $x = delete_all_between("<em", "</em>", $x);
                              $x = delete_all_between("<em", "</em>", $x);
                              $x = delete_all_between("<em", "</em>", $x);
                              $x = delete_all_between("<em", "</em>", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x);
                               
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              
                              $x = str_replace("روابط ذات صلة", "", $x);
                                
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                         //     pr($return_array);exit;
                    }  
                    else if (strpos($url, "fifa.comXXXXXXXXXXXX") !== FALSE) {  
                            /* $html = file_get_html($url);  
                             foreach($html->find('div.article') as $e) 
                              $x = $e->innertext;  
                              
                              if (isset($x)) {
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                    
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  
                                  $x = delete_all_between("<fb:like", "</fb:like>", $x); 
                                  $x = delete_all_between("<fb:like", "</fb:like>", $x); 
                                  $x = delete_all_between("<fb:like", "</fb:like>", $x); 
                                  $x = delete_all_between("<fb:like", "</fb:like>", $x); 
                                  $x = delete_all_between("<fb:like", "</fb:like>", $x); 
                                  $x = delete_all_between("<fb:like", "</fb:like>", $x); 
                                  
                                  $x = delete_all_between("<span", "</span>", $x); 
                                  $x = delete_all_between("<span", "</span>", $x); 
                                  $x = delete_all_between("<span", "</span>", $x); 
                                  $x = delete_all_between("<span", "</span>", $x); 
                                  $x = delete_all_between("<span", "</span>", $x); 
                                   
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);         
                              }
                              else{
                                  $html = file_get_html($url);  
                                  foreach($html->find('div#article-body') as $e) 
                                    $x = $e->innertext;
                                  
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);
                              }
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                        //      pr($return_array);exit;    */
                    }
                    else if (strpos($url, "aljazeera.net") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div#DynamicContentContainer') as $e) 
                              $x = $e->innertext;  
                              
                              
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "elaph.com") !== FALSE || strpos($url, "Elaph") !== FALSE || strpos($url, "elaph") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div#articlebody') as $e) 
                              $x = $e->innertext;  
                              
                              
                              //$x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "hilalcom.net") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.pf-content') as $e) 
                              $x = $e->innertext;  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                               
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "euronews") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div#articleTranscript') as $e) 
                              $x = $e->innertext;  
                                                          
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $x = delete_all_between("<dir", "</dir='rtl'>", $x);  
                              $x = delete_all_between("<dir", "</dir='rtl'>", $x);  
                              $x = delete_all_between("<dir", "</dir='rtl'>", $x);  
                              $x = delete_all_between("<dir", '</dir="rtl">', $x);  
                              $x = delete_all_between("<dir", '</dir="rtl">', $x);  
                              $x = delete_all_between("<dir", '</dir="rtl">', $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              
                              $x = delete_all_between('<dir="rtl"', '</dir="rtl">', $x);  
                              $x = delete_all_between('<dir="rtl"', '</dir="rtl">', $x);  
                              $x = delete_all_between('<dir="rtl"', '</dir="rtl">', $x);  
                              $x = delete_all_between('<dir="rtl"', '</dir="rtl">', $x);  
                              
                              $x = delete_all_between('نحن في قناة', 'من زاوية مختلفة…', $x);  
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "ittinews.net") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.entry') as $e) 
                              $x = $e->innertext;  
                                       
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);   
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $xx = explode("عدد المشاهدات", $x);
                              
                              $x = $xx[0];
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "alsopar.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div#textcontent') as $e) 
                              $x = $e->innertext;  
                                       
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $xx = explode("عدد المشاهدات", $x);
                              
                              $x = $xx[0];
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "dostor.org") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.show-parags') as $e) 
                              $x = $e->innertext;  
                                       
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "jo24.net") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div#snewsbody') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "maqar.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.row div.grid_8 div.post div.clearfix') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "almotamar.netXXXXXXXXXXXX") !== FALSE) {  
                            /* $html = file_get_html($url);  
                             foreach($html->find('div.news') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;  */
                    }
                    else if (strpos($url, "jn-news.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.article-body') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "qna.org.qa") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.article-body') as $e) 
                              $x = $e->innertext;  
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                 
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "addustour.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div#page_main_contentarea_bot_inner') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);
                                
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (/*strpos($url, "nok6a.net") !== FALSE ||*/ strpos($url, "safa.ps") !== FALSE) {  
                             $html = file_get_html($url);  
                             
                             
                             foreach($html->find('div.post-text') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                                
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                                   
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $x = delete_all_between("<h4", "</h4>", $x);
                              $x = delete_all_between("<h4", "</h4>", $x);
                              $x = delete_all_between("<h4", "</h4>", $x);
                              $x = delete_all_between("<h4", "</h4>", $x);
                              $x = delete_all_between("<h4", "</h4>", $x);
                              $x = delete_all_between("<h4", "</h4>", $x);
                              $x = delete_all_between("<h4", "</h4>", $x);
                              $x = delete_all_between("<h4", "</h4>", $x);
                                                        
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                          //    pr($return_array);exit;
                    }
                    else if (strpos($url, "hassacom.comXXXXXXXXXXXX") !== FALSE) {  
                            /* $html = file_get_html($url);  
                             foreach($html->find('span.largfont') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;*/
                    }
                    else if (strpos($url, "ittisport.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.subtitle') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              if (isset($x)) {
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x); 
                                  
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                   
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x); 
                              }
                              else{
                                  $html = file_get_html($url);  
                                  foreach($html->find('p') as $e) 
                                     $x = delete_all_between("<div", "</div>", $e->innertext); 
                              }
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                              pr($return_array);exit;
                    }
                    else if (strpos($url, "alarabalyawm.netXXXXXXXXXXX") !== FALSE) {  
                           /*  $html = file_get_html($url);  
                             foreach($html->find('div#full-body') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);         
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                               
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;*/
                    }
                    else if (strpos($url, "assawsana.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.NEWS') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "zoomtunisia.tn") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.txt') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                                                                           
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                               
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "felesteen.ps") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.newsdetails') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                                                                           
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                               
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "arabic.cnn.comXXXXXXXXXXXX") !== FALSE) {  
                            /* $html = file_get_html($url);  
                             
                             foreach($html->find('div.article-content') as $e) 
                              $x = $e->innertext;  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = delete_all_between("<h4", "</h4>", $x); 
                              $x = delete_all_between("<h4", "</h4>", $x); 
                              $x = delete_all_between("<h4", "</h4>", $x); 
                              $x = delete_all_between("<h4", "</h4>", $x); 
                              
                              $x = delete_all_between("<h5", "</h5>", $x); 
                              $x = delete_all_between("<h5", "</h5>", $x); 
                              $x = delete_all_between("<h5", "</h5>", $x); 
                              $x = delete_all_between("<h5", "</h5>", $x); 
                              $x = delete_all_between("<h5", "</h5>", $x); 
                              
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              
                              $x = delete_all_between("<aside", "</aside>", $x); 
                              $x = delete_all_between("<aside", "</aside>", $x); 
                              $x = delete_all_between("<aside", "</aside>", $x); 
                              $x = delete_all_between("<aside", "</aside>", $x); 
                              $x = delete_all_between("<aside", "</aside>", $x); 
                                    
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $x = str_replace('<li class="story"></li>', '', $x);
                              $x = str_replace('<li class="story"></li>', '', $x);
                              $x = str_replace('<li class="story"></li>', '', $x);
                              $x = str_replace('<li class="story"></li>', '', $x);
                              $x = str_replace('<li class="story"></li>', '', $x);
                              $x = str_replace('<li class="story"></li>', '', $x);
                              $x = str_replace('<li class="story"></li>', '', $x);
                              $x = str_replace('<li class="story"></li>', '', $x);
                              $x = str_replace('<li class="story"></li>', '', $x);
                              $x = str_replace('<li class="story"></li>', '', $x);
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit; */
                    }
                    else if (strpos($url, "royanews.tv") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.single-post-contetn') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "chouftv.ma") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.post-inner') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "alittihad.aeXXXXXXXXXXXXX") !== FALSE) {  
                            /* $html = file_get_html($url);  
                             foreach($html->find('div#article') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;  */
                    }
                    else if (strpos($url, "filwajiha.com") !== FALSE) {  
                             $html = file_get_html_1($url);  
                             
                             foreach($html->find('div.main-content div article div p') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "moroccoeyes") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.pf-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }  
                    else if (strpos($url, "alyaoum24.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.article-entry-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }  
                    else if (strpos($url, "ar.beinsports.net") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.article-content') as $e) 
                              $x = $e->innertext;  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                             
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }  
                    else if (strpos($url, "sea7htravel.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.post-body') as $e) 
                              $x = $e->innertext;  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                             
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }  
                    else if (strpos($url, "kaahe.orgXXXXXXXXXXXXXXX") !== FALSE) {  
                             /*$html = file_get_html($url);  
                             foreach($html->find('div#maincontenttext') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                             // pr($return_array);exit;  */
                    }
                    else if (strpos($url, "aljouf-news.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('span.largfont') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "babnet.netXXXXXXXXXXX") !== FALSE) {  
                             /*$html = file_get_html($url);  
                             foreach($html->find('div.article_ct') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<center", "</center>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;*/
                    }
                    else if (strpos($url, "realmadrid.comXXXXXXXXXX") !== FALSE) {  
                             /*$html = file_get_html($url);  
                             foreach($html->find('div.m_text_content p.cap') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<center", "</center>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                             // pr($return_array);exit; */
                    }
                    else if (strpos($url, "wam.ae") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.newsDetail') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                               
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "wikise7a") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.art-postcontent') as $e) 
                              $x = $e->innertext;  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                               
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);
                                
                              $x = delete_all_between("<table", "</table>", $x);  
                              $x = delete_all_between("<table", "</table>", $x);  
                              $x = delete_all_between("<table", "</table>", $x);  
                              $x = delete_all_between("<table", "</table>", $x);  
                              $x = delete_all_between("<table", "</table>", $x); 
                                          
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                
                              $x = str_replace("<br/>", '', $x);
                              $x = str_replace("<br />", '', $x);
                              $x = str_replace('<div style="clear: both; text-align: center;" class="separator">  </div>', '', $x);
                              $x = str_replace('<div class="separator" style="clear: both; text-align: center;">  </div>', '', $x);
                              $x = str_replace('<div class="separator" style="clear: both; text-align: center;"> </div>', '', $x);
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                         //     pr($return_array);exit;
                    }
                    else if (strpos($url, "elfagr.org") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.ni-content') as $e) 
                              $x = $e->innertext;  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                               
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);
                                
                              $x = delete_all_between("<table", "</table>", $x);  
                              $x = delete_all_between("<table", "</table>", $x);  
                              $x = delete_all_between("<table", "</table>", $x);  
                              $x = delete_all_between("<table", "</table>", $x);  
                              $x = delete_all_between("<table", "</table>", $x); 
                                          
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                       
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                         //     pr($return_array);exit;
                    }
                    else if (strpos($url, "almowaten.net") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.pdetails') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "q8ping.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.entry') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                             
                              $x = str_replace("youtube", "", $x);
                              $x = str_replace("YouTube", "", $x);
                              $x = str_replace("Youtube", "", $x);
                                 
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "saidaonline.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.newsdisc') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = delete_all_between("<table", "</table>", $x); 
                              $x = delete_all_between("<table", "</table>", $x); 
                              $x = delete_all_between("<table", "</table>", $x); 
                              $x = delete_all_between("<table", "</table>", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                             
                              $x = str_replace("youtube", "", $x);
                              $x = str_replace("YouTube", "", $x);
                              $x = str_replace("Youtube", "", $x);
                                 
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "ng4a.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.post-item-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = delete_all_between("<table", "</table>", $x); 
                              $x = delete_all_between("<table", "</table>", $x); 
                              $x = delete_all_between("<table", "</table>", $x); 
                              $x = delete_all_between("<table", "</table>", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                             
                              $x = str_replace("youtube", "", $x);
                              $x = str_replace("YouTube", "", $x);
                              $x = str_replace("Youtube", "", $x);
                                 
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "tabuk-news.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div#textcontent') as $e){ 
                                 $x = delete_all_between("<div", "</div>", $e->innertext); 
                                 $x = delete_all_between("<a", "</a>", $x);
                             } 
                              
                              
                           //   $x = delete_all_between("<div", "</div>", $x);  
                           //   $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<script", "</script>", $x);  
                           //   $x = delete_all_between("<script", "</script>", $x); 
                               
                            
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }else if (strpos($url, "anbaanews.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('article#show_post') as $e){ 
                                 $x = delete_all_between("<div", "</div>", $e->innertext); 
                                 $x = delete_all_between("<a", "</a>", $x);
                                 $x = delete_all_between("<a", "</a>", $x);
                                 $x = delete_all_between("<a", "</a>", $x);
                                 $x = delete_all_between("<a", "</a>", $x);
                                 $x = delete_all_between("<a", "</a>", $x);
                                 $x = delete_all_between("<a", "</a>", $x);
                                 $x = delete_all_between("<a", "</a>", $x);
                                 $x = delete_all_between("<a", "</a>", $x);
                                 $x = delete_all_between("<a", "</a>", $x);
                                 $x = delete_all_between("<a", "</a>", $x);
                                 $x = delete_all_between("<a", "</a>", $x);
                                 $x = delete_all_between("<a", "</a>", $x);
                                 $x = delete_all_between("<a", "</a>", $x);
                                 $x = delete_all_between("<a", "</a>", $x);
                                 $x = delete_all_between("<a", "</a>", $x);
                                 $x = delete_all_between("<a", "</a>", $x);
                                 $x = delete_all_between("<a", "</a>", $x);
                                 $x = delete_all_between("<a", "</a>", $x);
                                 
                                 $x = delete_all_between("<li", "</li>", $x);
                               //  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                             } 
                              
                              
                          //    $x = delete_all_between("<div", "</div>", $x);  
                         //     $x = delete_all_between("<div", "</div>", $x);  
                          //    $x = delete_all_between("<div", "</div>", $x);  
                          ///    $x = delete_all_between("<div", "</div>", $x);  
                          //    $x = delete_all_between("<div", "</div>", $x);  
                           //   $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                               
                            
                              //$x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }else if (strpos($url, "zahran.org") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div#textcontent') as $e){ 
                                 $x = delete_all_between("<div", "</div>", $e->innertext); 
                                 $x = delete_all_between("<a", "</a>", $x);
                                 
                                 $x = delete_all_between("<li", "</li>", $x);
                                 $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                 $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                 $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                 $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                 $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                 $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                 $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                 $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                 $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                 $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                             } 
                              
                              
                          //    $x = delete_all_between("<div", "</div>", $x);  
                         //     $x = delete_all_between("<div", "</div>", $x);  
                          //    $x = delete_all_between("<div", "</div>", $x);  
                          ///    $x = delete_all_between("<div", "</div>", $x);  
                          //    $x = delete_all_between("<div", "</div>", $x);  
                           //   $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                               
                            
                              //$x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }else if (strpos($url, "kharjhome.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('span.largfont') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }  
                    else if (strpos($url, "rsssd.com") !== FALSE) {  
                             $html = file_get_html($url); 
                                          
                           /*  foreach($html->find('span.largfont') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);    */
                              
                              /*if (!isset($x)) {  */
                                  foreach($html->find('div#textcontent') as $e) 
                                     $x = delete_all_between("<div", "</div>", $e->innertext);
                            /*  } */
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "alsawtnews.cc") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.main-image-detail') as $e) 
                              $x = $e->innertext;  
                              
                              
                             // $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                            // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $xx = explode("عدد المشاهدات", $x);
                              
                              $x = $xx[0];
                                      
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "naseej.netXXXXXXXX") !== FALSE) {  
                            /* $html = file_get_html($url);  
                            
                             foreach($html->find('div.news-text') as $e) 
                              $x = $e->innertext; 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                   
                              $x = delete_all_between("<a", "</a>", $x);  
                              
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; */
                              
                           //   pr($return_array);exit;
                    }  
                    else if (strpos($url, "alsumaria.tv") !== FALSE) {    
                             $html = file_get_html($url);  
                             //foreach($html->find('span.NewsTitleDescription') as $e) 
                             foreach($html->find('h3.lblTitleFirstNEwsDetails') as $e) 
                              $x = $e->innertext;  
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              
                              $x = delete_all_between("<table", "</table>", $x);  
                              $x = delete_all_between("<table", "</table>", $x);  
                              $x = delete_all_between("<table", "</table>", $x);  
                              $x = delete_all_between("<table", "</table>", $x);  
                              $x = delete_all_between("<table", "</table>", $x);  
                              $x = delete_all_between("<table", "</table>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "otv.com.lbXXXXXXXXXXX") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.edesc') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "almanar.com.lb") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('td#textFontSize2') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    } 
                    else if (strpos($url, "almustaqbal.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div#articlebdy') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    } 
                    else if (strpos($url, "atheer.omXXXXXXXXXXXXX") !== FALSE) {    
                           /*  $html = file_get_html($url);  
                             foreach($html->find('div.articile_detailed_description') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;   */
                    }
                    else if (strpos($url, "tounessna.info") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.item-page') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }   
                    else if (strpos($url, "ham-24.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('span.largfont') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<span", "</span>", $x);  
                              $x = delete_all_between("<span", "</span>", $x);  
                              $x = delete_all_between("<span", "</span>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                              //$return_array['paragraph'][0]['contents'] = utf8_encode($return_array['paragraph'][0]['contents']);
                             // $return_array['paragraph'][0]['contents'] = iconv('windows-1256', 'UTF-8', $return_array['paragraph'][0]['contents']);
                              
                           //   pr($return_array);exit;
                    } 
                    else if (strpos($url, "annaharnews.netXXXXXXXXXXXXX") !== FALSE) {    
                            /* $html = file_get_html($url);  
                             foreach($html->find('div.entry') as $e) 
                              $x = $e->innertext;  
                              
                              
                              $x = delete_all_between("<span", "</span>", $x);  
                              $x = delete_all_between("<span", "</span>", $x);  
                              $x = delete_all_between("<span", "</span>", $x);
                              
                              $x = delete_all_between('<div class="juiz_sps_links  counters_both juiz_sps_displayed_bottom">', "</div>", $x);
                              $x = delete_all_between('<div class="sharedaddy sd-sharing-enabled">', "</div>", $x);
                              $x = delete_all_between('<div data-name="like-post-frame', "</div>", $x);
                                
                              $x = delete_all_between("<ul", "</ul>", $x);  
                              $x = delete_all_between("<ul", "</ul>", $x);  
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x);
                              $x = delete_all_between("<ul", "</ul>", $x);
                              $x = delete_all_between("<ul", "</ul>", $x);
                               
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              
                            /*  $x = delete_all_between("<h3", "</h3>", $x); 
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              $x = delete_all_between("<h3", "</h3>", $x); */
                              
                          /*    $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                              //$return_array['paragraph'][0]['contents'] = utf8_encode($return_array['paragraph'][0]['contents']);
                             // $return_array['paragraph'][0]['contents'] = iconv('windows-1256', 'UTF-8', $return_array['paragraph'][0]['contents']);
                              
                           //   pr($return_array);exit; */
                    }
                    else if (strpos($url, "zamanarabic.comXXXXXXXXX") !== FALSE) {    
                          /*   $html = file_get_html($url);  
                             foreach($html->find('div.entry') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<span", "</span>", $x);  
                              $x = delete_all_between("<span", "</span>", $x);  
                              $x = delete_all_between("<span", "</span>", $x);
                                
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                               
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node;    */
                    }
                    else if (strpos($url, "shabiba.comXXXXXXXXXXXXX") !== FALSE) {    
                            /* $html = file_get_html($url);  
                             foreach($html->find('span.ctl00_ContentPlaceHolder1_news_desc') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<span", "</span>", $x);  
                              $x = delete_all_between("<span", "</span>", $x);  
                              $x = delete_all_between("<span", "</span>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node;   */
                              
                              //$return_array['paragraph'][0]['contents'] = utf8_encode($return_array['paragraph'][0]['contents']);
                             // $return_array['paragraph'][0]['contents'] = iconv('windows-1256', 'UTF-8', $return_array['paragraph'][0]['contents']);
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "ajel.sa") !== FALSE) {    
                          //   $html = file_get_html($url);  
                           //  foreach($html->find('div.field.field-name-body.field-type-text-with-summary.field-label-hidden') as $e) 
                            //  $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                            //  $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<script", "</script>", $x);  
                            //  $x = delete_all_between("<script", "</script>", $x);  
                    //          $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][0]['contents'] = $return_array['description']; 
                              
                          //    pr($return_array);exit;
                    }
                    else if (strpos($url, "reuters.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div#resizeableText') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "14march.org") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('td#news_text') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (/*strpos($url, "orient-news.net") !== FALSE ||*/ strpos($url, "o-t.tv") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.news_show_contents_div') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }  
                    else if (strpos($url, "layalina.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.dcption_wrap') as $e) 
                              $x = $e->innertext;  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }  
                    else if (strpos($url, "alwasat.com.kw") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.Articlebody') as $e) 
                              $x = $e->innertext;  
                                          
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                        
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                      
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "alkhabarsport.com") !== FALSE || strpos($url, "alkhabarkw.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div#article_body') as $e) 
                              $x = $e->innertext;  
                              
                              $x = delete_all_between('<div>', "</div>", $x); 
                              $x = delete_all_between('<div>', "</div>", $x); 
                              $x = delete_all_between('<div>', "</div>", $x); 
                              $x = delete_all_between('<div>', "</div>", $x); 
                              $x = delete_all_between('<div>', "</div>", $x); 
                              $x = delete_all_between('<div>', "</div>", $x); 
                              $x = delete_all_between('<div>', "</div>", $x); 
                              $x = delete_all_between('<div>', "</div>", $x); 
                              $x = delete_all_between('<div>', "</div>", $x); 
                              $x = delete_all_between('<div>', "</div>", $x); 
                              $x = delete_all_between('<div>', "</div>", $x); 
                              $x = delete_all_between('<div>', "</div>", $x); 
                              $x = delete_all_between('<div>', "</div>", $x); 
                              
                              $x = delete_all_between('<div class="share-outer">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-outer">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-outer">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-outer">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-outer">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-outer">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-outer">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-outer">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-outer">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-outer">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-outer">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-outer">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-outer">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-outer">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-outer">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-outer">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-outer">', "</div>", $x);
                               
                              $x = delete_all_between('<div class="share-btm-line">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-btm-line">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-btm-line">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-btm-line">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-btm-line">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-btm-line">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-btm-line">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-btm-line">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-btm-line">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-btm-line">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-btm-line">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-btm-line">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-btm-line">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-btm-line">', "</div>", $x); 
                              $x = delete_all_between('<div class="share-btm-line">', "</div>", $x); 
                                          
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x);
                               
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                                          
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                        
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                                                
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "syrianow.sy") !== FALSE || strpos($url, "o-t.tv") !== FALSE) {    
                               $html = file_get_html($url);  
                               foreach($html->find('div.news_txt') as $e) 
                                  $x = $e->innertext;
                              
                              if (isset($x)) {    
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              }
                              else{
                                  $html = file_get_html($url);  
                                   foreach($html->find('td.center_block') as $e) 
                                     $x = $e->innertext;
                                     
                                      $x = delete_all_between("<a", "</a>", $x);  
                                      $x = delete_all_between("<a", "</a>", $x);  
                                      $x = delete_all_between("<a", "</a>", $x);  
                                      $x = delete_all_between("<a", "</a>", $x);  
                                      $x = delete_all_between("<a", "</a>", $x);  
                                      $x = delete_all_between("<a", "</a>", $x);  
                                      $x = delete_all_between("<a", "</a>", $x);  
                                      $x = delete_all_between("<a", "</a>", $x); 
                                      
                                      $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                      $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                      $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                      $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                      $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                      $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                      $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                      $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                      $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                      $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                      $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                      $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                      $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                      $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              }
                           
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }  
                    else if (strpos($url, "alforatnews.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.itemBody') as $e) 
                              $x = $e->innertext;  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                             /// $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                             // pr($return_array);exit;
                    }
                    else if (strpos($url, "france24.comXXXXXXXXXX") !== FALSE) {    
                          /* $html = file_get_html($url);  
                             foreach($html->find('div#modeless-target') as $e) 
                              $x =$e->innertext;  
                              
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                               
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              
                              $x = delete_all_between("<figcaption", "</figcaption>", $x); 
                              $x = delete_all_between("<figcaption", "</figcaption>", $x); 
                              $x = delete_all_between("<figcaption", "</figcaption>", $x); 
                              $x = delete_all_between("<figcaption", "</figcaption>", $x); 
                              $x = delete_all_between("<figcaption", "</figcaption>", $x); 
                              $x = delete_all_between("<figcaption", "</figcaption>", $x); 
                                  
                              $x = delete_all_between("<time", "</time>", $x); 
                              $x = delete_all_between("<time", "</time>", $x); 
                              $x = delete_all_between("<time", "</time>", $x); 
                              $x = delete_all_between("<time", "</time>", $x); 
                              $x = delete_all_between("<time", "</time>", $x); 
                              $x = delete_all_between("<time", "</time>", $x); 
                              $x = delete_all_between("<time", "</time>", $x); 
                              $x = delete_all_between("<h5", "</h5>", $x); 
                              $x = delete_all_between("<h5", "</h5>", $x); 
                              $x = delete_all_between("<h5", "</h5>", $x); 
                              $x = delete_all_between("<h5", "</h5>", $x); 
                              $x = delete_all_between("<h5", "</h5>", $x); 
                              $x = delete_all_between("<h5", "</h5>", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $first_step = explode( '<div class="bd">' , $x );
                              $second_step = explode("</div>" , $first_step[1] );

                              $x = $second_step[0];
                       
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                     //  pr($return_array);exit;        */
                    }
                    else if (strpos($url, "almuraba.net") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.entry') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                               
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;   
                               
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "pal24.net") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.page-content') as $e) 
                              $x = $e->innertext;  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                                      
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                               
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;   
                               
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "arabsturbo.comXXXXXXXX") !== FALSE) {    
                          /*   $html = file_get_html($url);  
                                                          
                             foreach($html->find('div.cont-parag.article') as $e) 
                              $x = $e->innertext;  
                                 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<h1", "</h1>", $x); 
                              $x = delete_all_between("<h1", "</h1>", $x); 
                              $x = delete_all_between("<h1", "</h1>", $x); 
                              $x = delete_all_between("<h1", "</h1>", $x); 
                              $x = delete_all_between("<h1", "</h1>", $x); 
                              $x = delete_all_between("<h1", "</h1>", $x); 
                              
                              $x = delete_all_between("<h2", "</h2>", $x); 
                              $x = delete_all_between("<h2", "</h2>", $x); 
                              $x = delete_all_between("<h2", "</h2>", $x); 
                              $x = delete_all_between("<h2", "</h2>", $x); 
                              $x = delete_all_between("<h2", "</h2>", $x); 
                              $x = delete_all_between("<h2", "</h2>", $x); 
                              $x = delete_all_between("<h2", "</h2>", $x); 
                              $x = delete_all_between("<h2", "</h2>", $x); 
                              
                              $x = delete_all_between("<ul", "</ul>", $x);
                              $x = delete_all_between("<ul", "</ul>", $x);
                              $x = delete_all_between("<ul", "</ul>", $x);
                              $x = delete_all_between("<ul", "</ul>", $x);
                              $x = delete_all_between("<ul", "</ul>", $x);
                              $x = delete_all_between("<ul", "</ul>", $x);
                              $x = delete_all_between("<ul", "</ul>", $x);
                              $x = delete_all_between("<ul", "</ul>", $x);
                              $x = delete_all_between("<ul", "</ul>", $x);
                              $x = delete_all_between("<ul", "</ul>", $x);
                               
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                               
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                             
                              $x = str_replace('<p dir="rtl">&nbsp;</p>', '', $x);
                              $x = str_replace('<div class="marb-20 content-image"> </div>', '', $x);
                              
                              $xx = explode("بالفيديو", $x);
                              
                              $return_array['paragraph'][0]['contents'] = $xx[0];   
                               
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit; */
                    }
                    else if (strpos($url, "alliraqnews.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.itemBody') as $e) 
                              $x = $e->innertext;  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                             // $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "manalonline.comXXXXXXXXXXX") !== FALSE) {    
                          /*   $html = file_get_html($url);  
                             foreach($html->find('table.innerarticlesdiv_contenttable') as $e) 
                              $x = $e->innertext;  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;   */
                    }
                    else if (strpos($url, "autosearch.me") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.field-items') as $e) 
                              $x = $e->innertext;  
                             
                              if (!isset($x)) {
                                  foreach($html->find('div.LexusMid') as $e) 
                                  $x = $e->innertext;                                 
                              }
                               
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<h2", "</h2>", $x); 
                              $x = delete_all_between("<h2", "</h2>", $x); 
                              $x = delete_all_between("<h2", "</h2>", $x); 
                              $x = delete_all_between("<h2", "</h2>", $x); 
                              $x = delete_all_between("<h2", "</h2>", $x); 
                              
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              
                              $x = delete_all_between("<dl", "</dl>", $x); 
                              $x = delete_all_between("<dl", "</dl>", $x); 
                              $x = delete_all_between("<dl", "</dl>", $x); 
                              $x = delete_all_between("<dl", "</dl>", $x); 
                              $x = delete_all_between("<dl", "</dl>", $x); 
                              $x = delete_all_between("<dl", "</dl>", $x); 
                              $x = delete_all_between("<dl", "</dl>", $x); 
                              
                              $x = delete_all_between("<dt", "</dt>", $x); 
                              $x = delete_all_between("<dt", "</dt>", $x); 
                              $x = delete_all_between("<dt", "</dt>", $x); 
                              $x = delete_all_between("<dt", "</dt>", $x); 
                              $x = delete_all_between("<dt", "</dt>", $x); 
                              $x = delete_all_between("<dt", "</dt>", $x); 
                              $x = delete_all_between("<dt", "</dt>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              
                              $x = delete_all_between("<!-- Responsive 336x280 -->", "<!-- Responsive 300x250 -->", $x); 
                              $x = delete_all_between("<!-- Responsive 336x280 -->", "<!-- Responsive 300x250 -->", $x); 
                              $x = delete_all_between("<!-- Responsive 336x280 -->", "<!-- Responsive 300x250 -->", $x); 
                              $x = delete_all_between("<!-- Responsive 336x280 -->", "<!-- Responsive 300x250 -->", $x); 
                              $x = delete_all_between("<!-- Responsive 336x280 -->", "<!-- Responsive 300x250 -->", $x); 
                              $x = delete_all_between("<!-- Responsive 336x280 -->", "<!-- Responsive 300x250 -->", $x); 
                              $x = delete_all_between("<!-- Responsive 336x280 -->", "<!-- Responsive 300x250 -->", $x); 
                              
                              $x = delete_all_between("<!--", "-->", $x); 
                              $x = delete_all_between("<!--", "-->", $x); 
                              $x = delete_all_between("<!--", "-->", $x); 
                              $x = delete_all_between("<!--", "-->", $x); 
                              $x = delete_all_between("<!--", "-->", $x); 
                              
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              
                              $x = delete_all_between("<ins", "</ins>", $x); 
                              $x = delete_all_between("<ins", "</ins>", $x); 
                              $x = delete_all_between("<ins", "</ins>", $x); 
                              $x = delete_all_between("<ins", "</ins>", $x); 
                              $x = delete_all_between("<ins", "</ins>", $x); 
                              $x = delete_all_between("<ins", "</ins>", $x); 
                              
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = delete_all_between("<style", "</style>", $x); 
                              $x = delete_all_between("<style", "</style>", $x); 
                              $x = delete_all_between("<style", "</style>", $x); 
                              $x = delete_all_between("<style", "</style>", $x); 
                              $x = delete_all_between("<style", "</style>", $x); 
                              $x = delete_all_between("<style", "</style>", $x); 
                              $x = delete_all_between("<style", "</style>", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              
                              $x = str_replace("كلمات دلالية:", '', $x);
                              $x = str_replace("من قبل", '', $x);
                              $x = str_replace("<br><br>", '', $x);
                              $x = str_replace(",", '', $x);
                                
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node;   
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "naba.ps") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div#body_news') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                     //  pr($return_array);exit;
                    } 
                    else if (strpos($url, "el-balad.comXXXXXXXXXXX") !== FALSE) {    
                        /*     $html = file_get_html($url);  
                             foreach($html->find('div.ni-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                               
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                     //  pr($return_array);exit;    */
                    }               
                    else if (strpos($url, "klmty.net11111") !== FALSE) {    
                             $html = file_get_html($url, false, null, -1, -1, true, true, 'window-1252');  
                             foreach($html->find('div.date_author') as $e) 
                              $x = $e->innertext;  
                                                          
                              $x = delete_all_between("<ul", "</ul>", $x);  
                              $x = delete_all_between("<ul", "</ul>", $x);  
                              $x = delete_all_between("<ul", "</ul>", $x);  
                              $x = delete_all_between("<ul", "</ul>", $x);  
                              
                              $x = delete_all_between("<li", "</li>", $x);  
                              $x = delete_all_between("<li", "</li>", $x);  
                              $x = delete_all_between("<li", "</li>", $x);  
                              $x = delete_all_between("<li", "</li>", $x);  
                              $x = delete_all_between("<li", "</li>", $x);  
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                
                              //$x = delete_all_between("<div", "</div>", $x);  
                              //$x = delete_all_between("<div", "</div>", $x);  
                              //$x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);
                                 
                              $return_array['paragraph'][0]['contents'] = $x;    
                              
                              $return_array['paragraph'][] = $node; 
                              
                    //   pr($return_array);exit;
                    } 
                    else if (strpos($url, "almesryoon.comXXXXXXXXXX") !== FALSE) {    
                           /*  $html = file_get_html($url);  
                             foreach($html->find('div.row .details') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                               $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              
                              $x = str_replace('6em;', "", $x);
                              $x = str_replace('6em;"', "", $x);
                             // $x = str_replace("</div>", "", $x);
                              $x = str_replace('"like" data-show-faces="true" data-share="true">', "", $x);
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                      // pr($return_array);exit; */
                    }  
                    else if (strpos($url, "ar.yabiladies.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.detail-header') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                     //  pr($return_array);exit;
                    }     
                    else if (strpos($url, "akhbarelyom.comXXXXXXXXXXX") !== FALSE) {    
                           /*  $html = file_get_html($url);  
                             foreach($html->find('section.articleBody') as $e) 
                              $x = $e->innertext;  
                              
                             //include('readibility/index.php');
                              
                           //   $x = delete_all_between("<div", "</div>", $x);  
                           //   $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                               
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                     //  pr($return_array);exit; */
                    }
                    else if (strpos($url, "al-gornal.comXXXXXXXXXXXXXXX") !== FALSE) {    
                             /*$html = file_get_html($url);  
                             foreach($html->find('div.entry') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                     //  pr($return_array);exit;      */
                    }
                    else if (strpos($url, "al-akhbar.comXXXXXXXXX") !== FALSE) {    
                          /*   $html = file_get_html($url);  
                             foreach($html->find('div.story-body') as $e) 
                              $x = $e->innertext;  
                                     
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                     //  pr($return_array);exit;       */
                    }
                    else if (strpos($url, "aldostornews.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div#body_detail') as $e) 
                              $x = $e->innertext;  
                              
                              
                             // $x = delete_all_between("<div", "</div>", $x);  
                           //   $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                     //  pr($return_array);exit;
                    }
                    else if (strpos($url, "annaharkw.com") !== FALSE) {    
                             $html = file_get_html($url);  
                            // foreach($html->find('div#ctl00_ContentPlaceHolder1_repArticlesNew_ctl00_ArticleContent') as $e) 
                             foreach($html->find('div#ContentPlaceHolder1_repArticlesNew_ArticleContent_0') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                     //  pr($return_array);exit;
                    }
                    else if (strpos($url, "forbesmiddleeast.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('article.newsarticle') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); 
                               
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                     //  pr($return_array);exit;
                    }
                    else if (strpos($url, "aawsat.comXXXXXXXXXXXXXX") !== FALSE) {    
                            /* $html = file_get_html($url);  
                             foreach($html->find('div.node_new_body') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit; */
                    } 
                    else if (strpos($url, "yumyume.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.recipe') as $e) 
                              $x = $e->innertext;  
                                
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              
                              //$x = str_replace("المزيد من أطباق رئيسية", '', $x);
                              $xx = explode("المزيد", $x);
                              
                              $x = $xx[0];
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    } 
                    else if (strpos($url, "fatafeat.comXXXXXXXXXXXXXX") !== FALSE) {    
                           /*  $html = file_get_html($url);  
                             foreach($html->find('div.division5') as $e) 
                              $x = $e->innertext;  
                                
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              
                              //$x = str_replace("المزيد من أطباق رئيسية", '', $x);
                              $xx = explode("المزيد", $x);
                              
                              $x = $xx[0];
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;  */
                    } 
                    else if (strpos($url, "shahiya.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.entry-content') as $e) 
                              $x = $e->innertext;  
                                
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                                            
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    } 
                    else if (strpos($url, "oleeh.com") !== FALSE) {    
                             $html = file_get_html($url);  
                                                  
                             foreach($html->find('td.Body_Content_TD') as $e) 
                              $x = $e->innertext;  
                              
                              
                              //$x = delete_all_between("<div", "</div>", $x);  
                              //$x = delete_all_between("<div", "</div>", $x);  
                              //$x = delete_all_between("<div", "</div>", $x); 
                                             
                              $x = delete_all_between("<ins", "</ins>", $x); 
                              $x = delete_all_between("<ins", "</ins>", $x); 
                              $x = delete_all_between("<ins", "</ins>", $x); 
                              $x = delete_all_between("<ins", "</ins>", $x); 
                              $x = delete_all_between("<ins", "</ins>", $x); 
                              $x = delete_all_between("<ins", "</ins>", $x); 
                              $x = delete_all_between("<ins", "</ins>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                             // pr($return_array);exit;
                    } 
                    else if (strpos($url, "goodykitchen.com") !== FALSE) {    
                             $html = file_get_html($url); 
                             
                             $x = '';
                              
                             foreach($html->find('div.rcpdcontent') as $e) 
                              $x = $e->innertext;     
                              
                              if (isset($x)) {           
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<label", "</label>", $x); 
                                  $x = delete_all_between("<label", "</label>", $x); 
                                  $x = delete_all_between("<label", "</label>", $x); 
                                  $x = delete_all_between("<label", "</label>", $x); 
                                  $x = delete_all_between("<select", "</select>", $x); 
                                  $x = delete_all_between("<select", "</select>", $x); 
                                  $x = delete_all_between("<select", "</select>", $x); 
                                  $x = delete_all_between("<select", "</select>", $x); 
                                  
                                  $x = delete_all_between("<form", "</form>", $x); 
                                  $x = delete_all_between("<form", "</form>", $x); 
                                  $x = delete_all_between("<form", "</form>", $x); 
                                  $x = delete_all_between("<form", "</form>", $x); 
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x); 
                              } 
                                  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                             // pr($return_array);exit;
                    } 
                    else if (strpos($url, "omannews.gov.om") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.tab-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                             
                              $x = str_replace("youtube", "", $x);
                              $x = str_replace("YouTube", "", $x);
                              $x = str_replace("Youtube", "", $x);
                                 
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "alrayalaam.comXXXXXXXXXX") !== FALSE) {    
                           /*  $html = file_get_html($url);  
                             foreach($html->find('div.entry') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;     */
                    } 
                    
                    else if (strpos($url, "acakuw.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.entry') as $e) 
                              $x = $e->innertext;  
                              
                              
                             // $x = delete_all_between("<div", "</div>", $x);  
                          //    $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = delete_all_between("<figure", "</figure>", $x);
                              $x = delete_all_between("<figure", "</figure>", $x);
                              $x = delete_all_between("<figure", "</figure>", $x);
                              $x = delete_all_between("<figure", "</figure>", $x);
                              
                              $x = delete_all_between("<ul", "</ul>", $x);
                              $x = delete_all_between("<ul", "</ul>", $x);
                              $x = delete_all_between("<ul", "</ul>", $x);
                              $x = delete_all_between("<ul", "</ul>", $x);
                              
                              $x = delete_all_between("<li", "</li>", $x);
                              $x = delete_all_between("<li", "</li>", $x);
                              $x = delete_all_between("<li", "</li>", $x);
                              $x = delete_all_between("<li", "</li>", $x);
                              $x = delete_all_between("<li", "</li>", $x);
                                
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    } 
                    else if (strpos($url, "alshamiya-news.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.main-image-detail') as $e) 
                              $x = $e->innertext;  
                                 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              
                              $x = delete_all_between("<strong", "</strong>", $x);  
                              
                             // $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $xx = explode("عدد المشاهدات", $x);
                              
                              $x = $xx[0];  
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    } 
                    else if (strpos($url, "3alyoum.comXXXXXXXXXX") !== FALSE) {    
                            /* $html = file_get_html($url);  
                             foreach($html->find('div.body') as $e) 
                              $x = $e->innertext;  
                                 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<form", "</form>", $x);
                              $x = delete_all_between("<form", "</form>", $x);
                              $x = delete_all_between("<form", "</form>", $x);
                              $x = delete_all_between("<form", "</form>", $x);
                              $x = delete_all_between("<form", "</form>", $x);
                              
                              $x = delete_all_between("<h2", "</h2>", $x);
                              $x = delete_all_between("<h2", "</h2>", $x);
                              $x = delete_all_between("<h2", "</h2>", $x);
                              $x = delete_all_between("<h2", "</h2>", $x);
                              $x = delete_all_between("<h2", "</h2>", $x);
                              $x = delete_all_between("<h2", "</h2>", $x);
                               
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              $x = delete_all_between("<h3", "</h3>", $x); 
                              
                              $x = delete_all_between("<h4", "</h4>", $x); 
                              $x = delete_all_between("<h4", "</h4>", $x); 
                              $x = delete_all_between("<h4", "</h4>", $x); 
                              $x = delete_all_between("<h4", "</h4>", $x); 
                              $x = delete_all_between("<h4", "</h4>", $x); 
                              $x = delete_all_between("<h4", "</h4>", $x); 
                              $x = delete_all_between("<h4", "</h4>", $x); 
                               
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                                
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              
                              $x = delete_all_between("<strong", "</strong>", $x);  
                              $x = delete_all_between("<strong", "</strong>", $x);  
                              $x = delete_all_between("<strong", "</strong>", $x);  
                                                                                
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;   */
                    } 
                    else if (strpos($url, "lebanonfiles.comXXXXXXXXXXXX") !== FALSE) {    
                          /*   $url_splitter = explode("/", $url);
                             
                             $id = $url_splitter[count($url_splitter)-1];
                             
                             $url = "http://www.lebanonfiles.com/print.php?id=" . $id;
                             
                             $html = file_get_html($url);  
                             foreach($html->find('div.body') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);
                                
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; */
                    }
                    else if (strpos($url, "atyabtabkha.3a2ilati.comXXXXXXXXXXXX") !== FALSE) {    
                             /*$html = file_get_html($url);  
                             
                             //ingredients
                             foreach($html->find('div.ingredients') as $e) 
                              $x = $e->innertext;  
                              
                              if (!isset($x)) {
                                  foreach($html->find('div.tabs') as $e) 
                                  $x = $e->innertext;
                                  $xx = '';
                              }
                              else{      
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  
                                  $x = delete_all_between("<h1", "</h1>", $x);  
                                  $x = delete_all_between("<h1", "</h1>", $x);  
                                  $x = delete_all_between("<h1", "</h1>", $x);  
                                  $x = delete_all_between("<h1", "</h1>", $x);  
                                  $x = delete_all_between("<h1", "</h1>", $x);  
                                  $x = delete_all_between("<h1", "</h1>", $x);
                                  
                                  $x = delete_all_between("<form", "</form>", $x);
                                  $x = delete_all_between("<form", "</form>", $x);
                                  $x = delete_all_between("<form", "</form>", $x);
                                  $x = delete_all_between("<form", "</form>", $x);
                                  $x = delete_all_between("<form", "</form>", $x);
                                    
                                 
                                 // $x = delete_all_between("<div", "</div>", $x);  
                                //  $x = delete_all_between("<div", "</div>", $x);  
                                //  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  
                                  //preparation
                                  foreach($html->find('div.preparation') as $e) 
                                  $xx = $e->innertext;
                              }
                                      
                              $x .= '<br /><br />' . $xx;
                              
                              $x = str_replace('<div class="ie-fix">', '', $x);
                              $x = str_replace('<div id="ingredient-plain" class="" title="ingredient">', '', $x);
                              $x = str_replace('</div>', '', $x);
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit; */
                    } 
                    else if (strpos($url, "egyptiannews.net") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.article_text') as $e) 
                              //$x = delete_all_between("<div", "</div>", $e->innertext);  
                              $x = $e->innertext;  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              
                             /* $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); */
                                
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);   
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "dasmannews.comXXXXXXXXXXX") !== FALSE) {    
                            /* $html = file_get_html($url);  
                             foreach($html->find('div.entry-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;  */
                    }
                    else if (strpos($url, "yanair.netXXXXXXXXXXX") !== FALSE) { 
                          /*   $html = file_get_html($url);  
                             foreach($html->find('div.content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);                                                                                        
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              //$x = delete_all_between("<html", "</html>", $x);
                              //$x = delete_all_between("<h1", "</h1>", $x);
                                
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);   
                                    
                            //  $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // //$x = delete_all_between("<div", "</div>", $x);
                              
                             // echo($x);exit; 
                                
                           /*   $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); */ 
                                     
                              //$return_array['paragraph'][0]['contents'] = mb_substr($x, 1490, strlen($x), "UTF-8");    
                            /*  $return_array['paragraph'][0]['contents'] = $x;    
                               
                             
                              $return_array['paragraph'][] = $node; 
                              
                          //    pr($return_array);exit;     */
                             
                    }
                    else if (strpos($url, "ennaharonline.com") !== FALSE) { 
                            $new_url = explode("/", $url);
                            
                            $url_encode = urlencode($new_url[count($new_url)-1]);
                            
                            $url1 = '';
                            
                            for($i = 0; $i < count($new_url)-1; $i++){
                                $url1 .= $new_url[$i] . '/';
                            }
                            
                            $url1 .= $url_encode;
                            
                            $url = $url1;
                            
                             //echo('<br />ddddddddddddd:'.$url);exit;
                            $html = file_get_html($url);
                                        
                            // echo($html);  exit;
                             foreach($html->find('div.article') as $e) 
                              $x = $e->innertext;
                              
                                
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);                                                                                        
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);
                              
                             // $x = delete_all_between("<iframe", "</iframe>", $x);
                              //$x = delete_all_between("<html", "</html>", $x);
                              //$x = delete_all_between("<h1", "</h1>", $x);
                                
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);   
                                    
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);
                              
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                                   
                              $return_array['paragraph'][0]['contents'] = $x;    
                               
                             
                              $return_array['paragraph'][] = $node; 
                              
                         //     pr($return_array);exit;
                             
                    }
                    else if (strpos($url, "alkhaleej.aeXXXXXXXXXXXXXX") !== FALSE) {    
                            /* $html = file_get_html($url);  
                             foreach($html->find('div#detailedBody') as $e) 
                              //$x = delete_all_between("<div", "</div>", $e->innertext);  
                              $x = $e->innertext;  
                              
                              
                            //  $x = delete_all_between("<div", "</div>", $x);  
                           //   $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit; */
                    }
                    else if (strpos($url, "lbcgroup.tv") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('span#ctl00_MainContent_lblNewsDetailsLongDescription') as $e)  
                              $x = $e->innertext; 
                              
                              if (isset($x)) { 
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);     
                                  $x = delete_all_between("<script", "</script>", $x); 
                                  
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                              }
                              else{
                                  foreach($html->find('span#ctl00_MainContent_divBlogsDetails_lblLongDescription') as $e)  
                                  $x = $e->innertext; 
                                  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);     
                                  $x = delete_all_between("<script", "</script>", $x); 
                                  
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                              }
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "elbilad.net") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div#text_space') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }    
                    else if (strpos($url, "kuna.net.kwXXXXXXXXXXXXX") !== FALSE) {    
                             /*$html = file_get_html($url);  
                             foreach($html->find('div#divDetails') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = delete_all_between("<span", "</span>", $x);  
                              $x = delete_all_between("<span", "</span>", $x);  
                              $x = delete_all_between("<span", "</span>", $x);  
                              $x = delete_all_between("<span", "</span>", $x);  
                              $x = delete_all_between("<span", "</span>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);
                                
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;  */
                    }    
                    else if (strpos($url, "nas.sa") !== FALSE) {      
                             $html = file_get_html($url);  
                             foreach($html->find('div.span7') as $e) 
                              $x = $e->innertext;  
                                               
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                               
                              $x = delete_all_between("<h1", "</h1>", $x); 
                              $x = delete_all_between("<h1", "</h1>", $x); 
                              $x = delete_all_between("<h1", "</h1>", $x); 
                              $x = delete_all_between("<h1", "</h1>", $x); 
                              $x = delete_all_between("<h1", "</h1>", $x); 
                              
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              
                              $x = delete_all_between("<form", "</form>", $x); 
                              $x = delete_all_between("<form", "</form>", $x); 
                              $x = delete_all_between("<form", "</form>", $x); 
                              $x = delete_all_between("<form", "</form>", $x); 
                              $x = delete_all_between("<form", "</form>", $x); 
                                                                             
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);     
                              $x = delete_all_between("<script", "</script>", $x);  
                                                            
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "buyemen.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.story_text') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                             
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);
                                
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }    
                    else if (strpos($url, "qabaq.comXXXXXXXXXXXXX") !== FALSE) {    
                             /*$html = file_get_html($url);  
                             foreach($html->find('div#article_content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                             
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);
                                
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              $x = delete_all_between("<ul", "</ul>", $x); 
                              
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                              $x = delete_all_between("<li", "</li>", $x); 
                               
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);
                              $x = delete_all_between("<div", "</div>", $x);
                              $x = delete_all_between("<div", "</div>", $x);
                              $x = delete_all_between("<div", "</div>", $x);
                              $x = delete_all_between("<div", "</div>", $x);
                                
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;*/
                    }    
                    else if (strpos($url, "futuretvnetwork.com") !== FALSE) {    
                             $html = file_get_html($url);  
                              foreach($html->find('div#BodyNews') as $e) 
                              $x = $e->innertext; 
                              
                            //  $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);                           
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "le360.ma") !== FALSE) {    
                             $html = file_get_html($url);  
                              foreach($html->find('div.articles-holder p') as $e) 
                              $x = $e->innertext; 
                              
                              foreach($html->find('div.ctn') as $e) 
                              $xx = $e->innertext; 
                              
                              $x = delete_all_between("<h1", "</h1>", $x);
                              $x = delete_all_between("<h1", "</h1>", $x);
                                
                              $x = delete_all_between("<span", "</span>", $x);  
                              $x = delete_all_between("<span", "</span>", $x);  
                              $x = delete_all_between("<span", "</span>", $x);  
                              $x = delete_all_between("<span", "</span>", $x);  
                              $x = delete_all_between("<span", "</span>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                            //  $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);                           
                               
                              $return_array['paragraph'][0]['contents'] = $x . " " . $xx;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "alikhbaria.com") !== FALSE) {    
                             $html = file_get_html($url);  
                              foreach($html->find('div.itemFullText') as $e) 
                              $x = $e->innertext; 
                              
                            //  $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);                           
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "anazahra.comXXXXXXXXXXXXXXX") !== FALSE) {    
                            /* $html = file_get_html($url);  
                              foreach($html->find('div#articleContent') as $e) 
                              $x = $e->innertext; 
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); 
                               
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);                           
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;  */
                    }
                    else if (strpos($url, "moe.gov.qa") !== FALSE) {    
                             $html = file_get_html($url);  
                              foreach($html->find('div#ctl00_PlaceHolderMain_ctl05__ControlWrapper_RichHtmlField') as $e) 
                              $x = $e->innertext; 
                              
                            //  $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);                           
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    } 
                    else if (strpos($url, "almashhad.net") !== FALSE) {    
                             $html = file_get_html($url);  
                              foreach($html->find('div.text') as $e) 
                              $x = $e->innertext; 
                              
                            //  $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);  
                              $x = delete_all_between("<ins", "</ins>", $x);
                               
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                               
                              $x = delete_all_between("<script", "</script>", $x);                           
                              $x = delete_all_between("<script", "</script>", $x);                           
                              $x = delete_all_between("<script", "</script>", $x);                           
                              $x = delete_all_between("<script", "</script>", $x);                           
                              $x = delete_all_between("<script", "</script>", $x);                           
                              $x = delete_all_between("<script", "</script>", $x);                           
                              $x = delete_all_between("<script", "</script>", $x);                           
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    } 
                    else if (strpos($url, "al-watan.com") !== FALSE) {    
                             $html = file_get_html($url);  
                              foreach($html->find('span#ctl00_ContentPlaceHolder1_lblDescription') as $e) 
                              $x = $e->innertext; 
                              
                            //  $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x);                         
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "alkoutnews.net") !== FALSE) {    
                             $html = file_get_html($url);  
                              foreach($html->find('div#article_content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext); 
                              
                              if ( !isset($x) ) {  
                                  foreach($html->find('div.entry-content') as $e)  
                                  $x = delete_all_between("<div", "</div>", $e->innertext);
                              }
                                
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);
                              $x = delete_all_between("<div", "</div>", $x);
                              $x = delete_all_between("<div", "</div>", $x);
                              $x = delete_all_between("<div", "</div>", $x);
                              $x = delete_all_between("<div", "</div>", $x);
                                
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x);                         
                              $x = delete_all_between("<a", "</a>", $x);                         
                              $x = delete_all_between("<a", "</a>", $x);                         
                              $x = delete_all_between("<a", "</a>", $x);                         
                               
                              
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "omandaily.om") !== FALSE) {    
                             $html = file_get_html($url);  
                              foreach($html->find('div.entry-content .clearfix') as $e) 
                              $x = $e->innertext; 
                              
                            //  $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x);                         
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "alhakea.com") !== FALSE) {    
                             $html = file_get_html($url);  
                              foreach($html->find('div.post-inner div.entry') as $e) 
                              $x = $e->innertext; 
                              
                            //  $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x);                         
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                         //     pr($return_array);exit;
                    }
                    else if (strpos($url, "tunisien.tn") !== FALSE) {    
                             $html = file_get_html($url);  
                              foreach($html->find('div.entry-content') as $e) 
                              $x = $e->innertext; 
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x);                         
                              
                              $xx = explode("كلمات البح", $x);
                              
                              $x = $xx[0];
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                         //     pr($return_array);exit;
                    }
                    else if (strpos($url, "tnntunisia.comXXXXXXXXXXXX") !== FALSE) {    
                           /*  $html = file_get_html($url);  
                              foreach($html->find('div.entry-content') as $e) 
                              $x = $e->innertext; 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x);                         
                                                      
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            // pr($return_array);exit;*/
                    }
                    else if (strpos($url, "attounissia.com.tnXXXXXXXXXXXX") !== FALSE) {    
                            /* $html = file_get_html($url);  
                              foreach($html->find('div.text') as $e) 
                              $x = $e->innertext; 
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x);                         
                              
                              $xx = explode("كلمات البح", $x);
                              
                              $x = $xx[0];
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                         //     pr($return_array);exit;    */
                    }
                    else if (strpos($url, "reqaba.comXXXXXXXXXXXX") !== FALSE) {    
                            /* $html = file_get_html($url);  
                              foreach($html->find('div#cont') as $e) 
                              $x = $e->innertext; 
                              
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x);                         
                             
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                         //     pr($return_array);exit; */
                    }
                    else if (strpos($url, "alnoornews.net") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.itemBody') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                         
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              
                            //  $x = delete_all_between("<div", "</div>", $x);  
                           //   $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "zamalekfans.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.topFeatured') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                         
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                                                                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "alshahedkw.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.article-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "nna-leb.gov.lb") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.article-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "arbdroid.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.entry') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "n1t1.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.entry') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                              $x = delete_all_between("<span", "</span>", $x); 
                               
                              $x = delete_all_between("<i", "</i>", $x);  
                              $x = delete_all_between("<i", "</i>", $x);  
                              $x = delete_all_between("<i", "</i>", $x);  
                              $x = delete_all_between("<i", "</i>", $x);  
                              $x = delete_all_between("<i", "</i>", $x);  
                              $x = delete_all_between("<b", "</b>", $x);  
                              $x = delete_all_between("<b", "</b>", $x);  
                              $x = delete_all_between("<b", "</b>", $x);  
                              $x = delete_all_between("<b", "</b>", $x);  
                              $x = delete_all_between("<b", "</b>", $x);  
                              $x = delete_all_between("<b", "</b>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                
                              $x = str_replace('="s1">', '', $x);
                                
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "android-time.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.entry-content') as $e) 
                              $x .= '----888----' . delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $xx = explode("----888----", $x);
                                
                              $return_array['paragraph'][0]['contents'] = $xx[1];    
                            
                              $return_array['paragraph'][] = $node; 
                              
                             // pr($return_array);exit;
                    }
                    else if (strpos($url, "argaam.comXXXXXXXXX") !== FALSE) {    
                           /*  $html = file_get_html($url);  
                             foreach($html->find('div.article-detail-content') as $e) 
                              $x = $e->innertext;  
                              
                              if (!isset($x)) {
                                  foreach($html->find('div.entry-content') as $e) 
                                  $x = $e->innertext; 
                              }
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                            
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<div", "</div>", $x);
                                
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit; */
                    }
                    else if (strpos($url, "alwasat.ly") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.art-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "youm7.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div#articleBody') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);
                              
                              $x = delete_all_between("<h1", "</h1>", $x);
                              $x = delete_all_between("<h1", "</h1>", $x);
                              $x = delete_all_between("<h1", "</h1>", $x);
                              $x = delete_all_between("<h1", "</h1>", $x);
                              
                              $x = delete_all_between("<h2", "</h2>", $x);
                              $x = delete_all_between("<h2", "</h2>", $x);
                              $x = delete_all_between("<h2", "</h2>", $x);
                              $x = delete_all_between("<h2", "</h2>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                                
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "alriadey.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.article-body') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                               
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "raialyoum.comXXXXXXXXXXXXXXX") !== FALSE) {    
                           /*  $html = file_get_html($url);  
                             //foreach($html->find('div.post-content') as $e) 
                             foreach($html->find('div.entry-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                               
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                             // pr($return_array);exit;  */
                    }
                    else if (strpos($url, "olympic.qaXXXXXXXXXXXXXXX") !== FALSE) {    
                            /* $html = file_get_html($url);  
                            // echo($html);   exit;
                             if ($html != '')  {
                                 foreach($html->find('div#ctl00_PlaceHolderMain_ctl02__ControlWrapper_RichHtmlField') as $e) 
                                  $x = $e->innertext;  
                                                     
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x); 
                                  
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                             }
                             else{
                                 $x = '';
                             }  
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                             // pr($return_array);exit;*/
                    }
                    else if (strpos($url, "cdn.alkass.net") !== FALSE) {    
                             $html = file_get_html($url);  
                             //echo($html);
                             foreach($html->find('span#ctl00_ContentPlaceHolder1_Lab_details') as $e) 
                              $x = $e->innertext;  
                                                 
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "elfann.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             //echo($html);
                             foreach($html->find('div.nbody') as $e) 
                              $x = $e->innertext;  
                                                 
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                               
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "dotmsr.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div#shownContent') as $e) 
                              //$x = delete_all_between("<div", "</div>", $e->innertext);  
                              $x = $e->innertext;  
                              
                              
                             /* $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); 
                              $x = delete_all_between("<div", "</div>", $x); */
                               
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "aljadeed.tv") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.newsBody') as $e) 
                              $x = $e->innertext;  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</div>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "adwaalwatan.comXXXXXXXXX") !== FALSE) {  
                           /*  $html = file_get_html($url);  
                             foreach($html->find('div#textcontent') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                                  
                              if (!isset($x)) {
                                  $html = file_get_html($url);  
                                 foreach($html->find('div#post-content') as $e) 
                                  $x = delete_all_between("<div", "</div>", $e->innertext); 
                              }
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                               
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit; */
                    }
                    else if (strpos($url, "almasryalyoum.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div#NewsStory') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "alahlyegypt.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.pix-content-wrap') as $e) 
                              $x = $e->innertext;  
                              
                              $x = delete_all_between("<h1", "</h1>", $x);  
                              $x = delete_all_between("<h1", "</h1>", $x);  
                              $x = delete_all_between("<h1", "</h1>", $x);  
                              $x = delete_all_between("<h1", "</h1>", $x);  
                              
                              $x = delete_all_between("<ul", "</ul>", $x);  
                              $x = delete_all_between("<ul", "</ul>", $x);  
                              $x = delete_all_between("<ul", "</ul>", $x);  
                              $x = delete_all_between("<ul", "</ul>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "sport.ahram.org") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div#ContentPlaceHolder1_divBody') as $e) 
                              $x = $e->innertext;   
                              
                              $x = delete_all_between("<input", ">", $x);   
                              $x = delete_all_between("<input", ">", $x);   
                              $x = delete_all_between("<input", ">", $x);   
                              $x = delete_all_between("<input", ">", $x);   
                              $x = delete_all_between("<input", ">", $x);   
                              $x = delete_all_between("<input", ">", $x);   
                              $x = delete_all_between("<input", ">", $x);   
                               
                              $x = delete_all_between("<h1", "</h1>", $x);  
                              $x = delete_all_between("<h1", "</h1>", $x);  
                              $x = delete_all_between("<h1", "</h1>", $x);  
                              $x = delete_all_between("<h1", "</h1>", $x);  
                              
                              $x = delete_all_between("<ul", "</ul>", $x);  
                              $x = delete_all_between("<ul", "</ul>", $x);  
                              $x = delete_all_between("<ul", "</ul>", $x);  
                              $x = delete_all_between("<ul", "</ul>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                              $x = delete_all_between("<script", "</script>", $x); 
                               
                              $x = str_replace("كلمات البحث:", "", $x);
                              $x = str_replace("|", "", $x);
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if ( /*strpos($url, "alwatan.com") !== FALSE ||*/ strpos($url, "mmaqara2t.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.entry') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (         //diffbot sources
                             strpos($url, "almayadeen.net") !== FALSE || //1 \
                             strpos($url, "almogaz.com") !== FALSE ||    //2 \
                             strpos($url, "elwatannews.com") !== FALSE || //3  \
                             strpos($url, "akhbarak.net") !== FALSE ||    //4  \
                             strpos($url, "middle-east-online.com") !== FALSE || //5 |
                             strpos($url, "anbaaonline.com") !== FALSE ||      //6
                             strpos($url, "akherkhabaronline.com") !== FALSE || //7
                             strpos($url, "zamanarabic.com") !== FALSE ||     //8
                             strpos($url, "dailymedicalinfo.com") !== FALSE ||   //9
                             strpos($url, "arabsturbo.com") !== FALSE ||     //10  
                             strpos($url, "elshaab.org") !== FALSE ||            //11
                             strpos($url, "alquds.co.uk") !== FALSE ||            //12
                             strpos($url, "raialyoum.com") !== FALSE ||            //13
                             strpos($url, "aawsat.com") !== FALSE ||            //14
                             strpos($url, "24.ae") !== FALSE ||            //15
                             strpos($url, "alittihad.ae") !== FALSE ||            //16 
                             strpos($url, "alhayat.com") !== FALSE ||            //17  
                             strpos($url, "rudaw.net") !== FALSE ||            //18 
                             strpos($url, "echoroukonline.com") !== FALSE ||            //19
                             //strpos($url, "manalonline.com") !== FALSE ||            //20 
                             strpos($url, "france24.com") !== FALSE ||            //21
                             strpos($url, "skynewsarabia.com") !== FALSE ||            //22 
                             strpos($url, "yanair.net") !== FALSE ||            //23
                             strpos($url, "almesryoon.com") !== FALSE ||            //24
                             strpos($url, "almaghribtoday.net") !== FALSE ||            //25 
                             strpos($url, "baareq.com.sa") !== FALSE ||            //26 
                             strpos($url, "adwaalwatan.com") !== FALSE ||            //27 
                             strpos($url, "watn-news.com") !== FALSE ||            //28 
                             strpos($url, "freeswcc.com") !== FALSE ||            //29 
                             strpos($url, "hassacom.com") !== FALSE ||            //30 
                             strpos($url, "3alyoum.com") !== FALSE ||            //31 
                             strpos($url, "anaween.com") !== FALSE ||            //32
                             strpos($url, "3seer.net") !== FALSE ||            //33
                             strpos($url, "al-balad.net") !== FALSE ||            //34
                             strpos($url, "fajr.sa") !== FALSE ||            //35
                             strpos($url, "makkahnewspaper.com") !== FALSE ||            //36
                             strpos($url, "wasul.info") !== FALSE ||            //37
                             strpos($url, "naseej.net") !== FALSE ||            //38
                             strpos($url, "klmty.net") !== FALSE ||            //39 
                             strpos($url, "masralarabia.com") !== FALSE ||            //40
                             strpos($url, "el-balad.com") !== FALSE ||            //41
                             strpos($url, "al-sharq.com") !== FALSE ||            //42 
                             strpos($url, "shorouknews.com") !== FALSE ||            //43
                             strpos($url, "akhbarelyom.com") !== FALSE ||            //44
                             strpos($url, "fath-news.com") !== FALSE ||            //45
                             strpos($url, "otv.com.lb") !== FALSE ||            //46 
                             strpos($url, "albawabhnews.com") !== FALSE ||            //47
                             strpos($url, "ahram.org.eg") !== FALSE ||            //48
                             strpos($url, "alhurra.com") !== FALSE ||            //49
                             strpos($url, "al-mashhad.com") !== FALSE ||            //50
                             strpos($url, "alrayalaam.com") !== FALSE ||            //51
                             strpos($url, "alhasela.com") !== FALSE ||            //52
                             strpos($url, "dasmannews.com") !== FALSE ||            //53
                             strpos($url, "ajialq8.com") !== FALSE ||            //54
                             strpos($url, "reqaba.com") !== FALSE ||            //55 
                             strpos($url, "kuna.net.kw") !== FALSE ||            //56  
                             strpos($url, "altaleea.com") !== FALSE ||            //57 
                             strpos($url, "kuwaitnews.com") !== FALSE ||            //59 
                             strpos($url, "q8news.com") !== FALSE ||            //60 
                             strpos($url, "alanba.com.kw") !== FALSE ||            //61 
                             strpos($url, "al-seyassah.com") !== FALSE ||            //62
                             strpos($url, "alkhaleej.ae") !== FALSE ||            //63
                             strpos($url, "hroobnews.com") !== FALSE ||            //64
                             strpos($url, "hattpost.com") !== FALSE ||            //65 
                             strpos($url, "orient-news.net") !== FALSE ||            //66
                             strpos($url, "azzaman.com") !== FALSE ||            //67
                             strpos($url, "aliraqnews.com") !== FALSE ||            //68
                             strpos($url, "adhamiyahnews.com") !== FALSE ||            //69
                             strpos($url, "iraqdirectory.com") !== FALSE ||            //70
                             strpos($url, "alarabiya.net") !== FALSE ||            //71
                             strpos($url, "qudspress.com") !== FALSE ||            //72
                             strpos($url, "paltoday.ps") !== FALSE ||            //73
                             strpos($url, "palsawa.com") !== FALSE ||            //74
                             strpos($url, "alsawt.net") !== FALSE ||            //75 
                             strpos($url, "ammonnews.net") !== FALSE ||            //76 
                             strpos($url, "assabeel.net") !== FALSE ||            //77
                             strpos($url, "alarabalyawm.net") !== FALSE ||            //78
                             strpos($url, "suhailnews.blogspot.com") !== FALSE ||            //79
                             strpos($url, "alkhabarnow.net") !== FALSE ||            //80
                             strpos($url, "almotamar.net") !== FALSE ||            //81
                             strpos($url, "yemenat.net") !== FALSE ||            //82
                             strpos($url, "yemen-press.com") !== FALSE ||            //83
                             strpos($url, "yen-news.com") !== FALSE ||            //84
                             strpos($url, "marebpress.net") !== FALSE ||            //85
                             strpos($url, "lebanondebate.com") !== FALSE ||            //86
                             strpos($url, "annahar.com") !== FALSE ||            //87
                             strpos($url, "al-akhbar.com") !== FALSE ||            //88
                             strpos($url, "lebanonfiles.com") !== FALSE ||            //89
                             strpos($url, "aljoumhouria.com") !== FALSE ||            //90
                             strpos($url, "assafir.com") !== FALSE ||            //91
                             strpos($url, "tnntunisia.com") !== FALSE ||            //92
                             strpos($url, "babnet.net") !== FALSE ||            //93
                             strpos($url, "attounissia.com.tn") !== FALSE ||            //94
                             strpos($url, "tounesnews.com") !== FALSE ||            //95
                             strpos($url, "annaharnews.net") !== FALSE ||            //97
                             strpos($url, "arabesque.tn") !== FALSE ||            //98
                             strpos($url, "tuniscope.com") !== FALSE ||            //99
                             strpos($url, "alwatannews.net") !== FALSE ||            //100
                             strpos($url, "bahrainmirror.no-ip.info") !== FALSE ||            //101
                             strpos($url, "alwefaq.net") !== FALSE ||            //102
                             strpos($url, "bna.bh") !== FALSE ||            //103
                             strpos($url, "bahrainalyoum.net") !== FALSE ||            //104
                             strpos($url, "alayam.com") !== FALSE ||            //105
                             strpos($url, "akhbar-alkhaleej.com") !== FALSE ||            //106 
                             strpos($url, "atheer.om") !== FALSE ||            //107 
                             strpos($url, "alwatan.com") !== FALSE ||            //108 
                             strpos($url, "shabiba.com") !== FALSE ||            //109 
                             strpos($url, "hespress.com") !== FALSE ||            //110
                             strpos($url, "hibapress.com") !== FALSE ||            //111
                             strpos($url, "akhbarlibya24.net") !== FALSE ||            //112
                             strpos($url, "linkis.com") !== FALSE ||            //113
                             strpos($url, "libyanow.net.ly") !== FALSE ||            //114
                             strpos($url, "lana-news.ly") !== FALSE ||            //115
                             strpos($url, "ashorooq.net") !== FALSE ||            //116
                             strpos($url, "sudanmotion.com") !== FALSE ||            //117
                             strpos($url, "alnilin.com") !== FALSE ||            //118
                             strpos($url, "alaraby.co.uk") !== FALSE ||            //119
                             strpos($url, "alarab.qa") !== FALSE ||            //120
                             strpos($url, "arabic.cnn.com") !== FALSE ||            //121
                             strpos($url, "elheddaf.com") !== FALSE ||            //122
                             strpos($url, "arriyadiyah.com") !== FALSE ||            //123
                             strpos($url, "fifa.com") !== FALSE ||            //124
                             strpos($url, "beinsports.com") !== FALSE ||            //125
                             strpos($url, "kooora.com") !== FALSE ||            //126 
                             strpos($url, "kooora2.com") !== FALSE ||            //126 
                             strpos($url, "arabic.sport360.com") !== FALSE ||            //127
                             strpos($url, "fcbarcelona.com") !== FALSE ||            //128
                             strpos($url, "manchestercityfc.ae") !== FALSE ||            //129 
                             strpos($url, "realmadrid.com") !== FALSE ||            //130 
                             strpos($url, "hyperstage.net") !== FALSE ||            //131 
                             strpos($url, "th3professional.com") !== FALSE ||            //132 
                             strpos($url, "euronews.com") !== FALSE ||            //133 
                             strpos($url, "arabi21.com") !== FALSE ||            //134
                             strpos($url, "arabitechnomedia.com") !== FALSE ||            //135
                             strpos($url, "doniatech.com") !== FALSE ||            //136
                             strpos($url, "android4ar.com") !== FALSE ||            //137
                             strpos($url, "techplus.me") !== FALSE ||            //138
                             strpos($url, "hashtagarabi.com") !== FALSE ||            //139
                             strpos($url, "ardroid.com") !== FALSE ||            //140 
                             strpos($url, "electrony.net") !== FALSE ||            //141
                             strpos($url, "arabhardware.net") !== FALSE ||            //142
                             strpos($url, "arabapps.org") !== FALSE ||            //143
                             strpos($url, "kaahe.org") !== FALSE ||            //144
                             strpos($url, "wikise7a.com") !== FALSE ||            //145
                             strpos($url, "nok6a") !== FALSE ||            //146
                             strpos($url, "olympic.qa") !== FALSE ||            //147
                             strpos($url, "alriyadh.com") !== FALSE ||            //148
                             strpos($url, "anazahra.com") !== FALSE ||            //149
                             strpos($url, "qabaq.com") !== FALSE ||            //150
                             strpos($url, "arab4x4.com") !== FALSE ||            //151
                             strpos($url, "snobonline.net") !== FALSE ||            //152
                             strpos($url, "hiamag.com") !== FALSE ||            //153
                             strpos($url, "hawaaworld.com") !== FALSE ||            //154
                             strpos($url, "wonews.net") !== FALSE ||            //155
                             strpos($url, "lahamag.com") !== FALSE ||            //156
                             strpos($url, "al-gornal.com") !== FALSE ||            //157
                             strpos($url, "wafa.com.sa") !== FALSE ||            //158
                             strpos($url, "lahaonline.com") !== FALSE ||            //159
                             strpos($url, "sayidaty.net") !== FALSE ||            //160
                             strpos($url, "ounousa.com") !== FALSE ||            //161
                             strpos($url, "fashion4arab.com") !== FALSE ||            //162
                             strpos($url, "steelbeauty.net") !== FALSE ||            //163
                             strpos($url, "hihi2.com") !== FALSE ||            //163
                             strpos($url, "argaam.com") !== FALSE ||            //164 
                             strpos($url, "arn.ps") !== FALSE      // last one
                            ) {      //       echo ('<br />ddddddddddddddd');  echo($url);  exit;
                             $x = diffbot($url);      
                                // echo('bbbbbbbbbbbbbbbbbbbbbbb');    pr($x);exit;
                             $return_array['paragraph'][0]['contents'] = $x['x']; 
                                
                             $return_array['images'][0] = $x['y'];    
                            
                             $return_array['paragraph'][] = $node;
                            // pr($return_array);exit;
                             break;
                    }
                    else if (strpos($url, "alkhaleejonline.net") !== FALSE || strpos($url, "alquds.com") !== FALSE) {  
                             $url = str_replace("#!/", '', $url);   
                             
                             $x = diffbot($url);
                                 
                             $return_array['paragraph'][0]['contents'] = $x['x']; 
                             
                             $return_array['images'][0] = $x['y'];   
                            
                             $return_array['paragraph'][] = $node;
                             break;
                             
                             /*include('readibility/index.php'); 
                                                
                             $x = delete_all_between("<script", "</script>", $x);  
                             $x = delete_all_between("<script", "</script>", $x);  
                             $x = delete_all_between("<script", "</script>", $x);  
                             $x = delete_all_between("<script", "</script>", $x);   
                             $x = delete_all_between("<script", "</script>", $x); 
                             
                             $x = delete_all_between("<iframe", "</iframe>", $x); 
                             $x = delete_all_between("<iframe", "</iframe>", $x); 
                             $x = delete_all_between("<iframe", "</iframe>", $x); 
                             $x = delete_all_between("<iframe", "</iframe>", $x); 
                             
                             $x = delete_all_between("<a", "</a>", $x); 
                             $x = delete_all_between("<a", "</a>", $x); 
                             $x = delete_all_between("<a", "</a>", $x); 
                             $x = delete_all_between("<a", "</a>", $x); 
                             $x = delete_all_between("<a", "</a>", $x); 
                             $x = delete_all_between("<a", "</a>", $x); 
                              
                             $return_array['paragraph'][0]['contents'] = $x;    
                             $return_array['images'][0] = $lead_image;
                             
                             $return_array['paragraph'][] = $node;  */
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "alroeya.ae") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.entry-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }                
                    else if (strpos($url, "newsqassim.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.justify') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = delete_all_between("<fb:like", "</fb:like", $x);
                              $x = delete_all_between("<fb:like ", "</fb:like", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                                
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                              $return_array['title'] = iconv('windows-1256', 'UTF-8', $return_array['title']);
                              
                             //echo('****************************<br />'); pr($return_array);exit;
                    }else if (strpos($url, "lahaonline.comXXXXXXXXXXX") !== FALSE) {  
                            /* $html = file_get_html($url);  
                             foreach($html->find('div.articlecontent') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = delete_all_between("<fb:like", "</fb:like", $x);
                              $x = delete_all_between("<fb:like ", "</fb:like", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                              $return_array['title'] = iconv('windows-1256', 'UTF-8', $return_array['title']);
                              
                             //echo('****************************<br />'); pr($return_array);exit;  */
                    }
                    else if (strpos($url, "alraimedia.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.ArticleDetailsLabel2') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              $x = delete_all_between("<script", "</script>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                                
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "albayan.ae") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.body') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                          //    pr($return_array);exit;
                    }
                    else if (strpos($url, "hroobnews.comXXXXXXXXXXXXXX") !== FALSE) {    
                           /*  $html = file_get_html($url);  
                             foreach($html->find('div#textcontent') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                          //    pr($return_array);exit; */
                    }
                    else if (strpos($url, "sahelmaten.com") !== FALSE || strpos($url, "lebwindow.net") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.entry') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                          //    pr($return_array);exit;
                    }
                    else if (strpos($url, "spa.gov.sa") !== FALSE || strpos($url, "masrawy.com") !== FALSE ) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div#content') as $e){ 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                             }
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (/*strpos($url, "electrony.net") !== FALSE || */ strpos($url, "electronynet") !== FALSE) {      
                             $html = file_get_html($url);  
                             foreach($html->find('div.entry') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                                                            
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "alrafidain.org") !== FALSE) {      
                             $html = file_get_html($url);  
                             foreach($html->find('div#article_content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                                                            
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "alkuwaityah.com") !== FALSE || strpos($url, "Alkuwaityah.com") !== FALSE ) {      
                             $html = file_get_html($url);  
                             foreach($html->find('div.article_content') as $e) 
                              //$x = delete_all_between("<div", "</div>", $e->innertext);  
                              $x = $e->innertext;  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                            //  $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                                                            
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "altaleea.comXXXXXXXXXXXX") !== FALSE) {      
                            /* $html = file_get_html($url);  
                             foreach($html->find('div.entry') as $e) 
                              //$x = delete_all_between("<div", "</div>", $e->innertext);  
                              $x = $e->innertext;  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                            //  $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                                                            
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit; */
                    }
                    else if (strpos($url, "hiamag.comXXXXXXXXXXXXX") !== FALSE) {      
                             /*$html = file_get_html($url);  
                             foreach($html->find('div.node_body_inner') as $e) 
                              $x = $e->innertext;  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                                                                            
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                                                            
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;   */
                    }
                    else if (strpos($url, "rotanamags.net") !== FALSE) {      
                             $html = file_get_html($url);  
                             foreach($html->find('div.field p') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                               
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                                                            
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "alwatan.kuwait.tt") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.ArticleText') as $e) 
                              //$x = delete_all_between("<div", "</div>", $e->innertext);  
                              $x = $e->innertext;  
                              
                              if (!isset($x)) {
                                  $html = file_get_html($url);  
                                     foreach($html->find('div#divArtContento11') as $e) 
                                      //$x = delete_all_between("<div", "</div>", $e->innertext);  
                                      $x = $e->innertext;
                                  
                              }
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                               
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                             // pr($return_array);exit;
                    } 
                    else if (strpos($url, "aljarida.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.bodyTextContainer') as $e) 
                              //$x = delete_all_between("<div", "</div>", $e->innertext);  
                              $x = $e->innertext;  
                              
                              
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);  
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                             // pr($return_array);exit;
                    }
                    else if (strpos($url, "al-seyassah.comXXXXXXXXXXXXX") !== FALSE) {    
                             /*$html = file_get_html($url);  
                             foreach($html->find('div.pf-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                             // pr($return_array);exit; */
                    }
                    else if (strpos($url, "sabr.cc") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div#body_detail') as $e) 
                              //$x = delete_all_between("<div", "</div>", $e->innertext);  
                              $x = $e->innertext;  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x); 
                              $x = delete_all_between("<a", "</a>", $x);
                               
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                              //pr($return_array);exit;
                    }
                    else if (strpos($url, "aleqt.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.post-body') as $e) 
                              //$x = delete_all_between("<div", "</div>", $e->innertext);  
                              $x = $e->innertext;  
                              
                              
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "al-madina.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.news_text') as $e) 
                              //$x = delete_all_between("<div", "</div>", $e->innertext);  
                              $x = $e->innertext;  
                              
                              
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "alqabas.com.kw") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div#cphContent_Articles1_details') as $e) 
                              //$x = delete_all_between("<div", "</div>", $e->innertext);  
                              $x = $e->innertext;  
                              
                              
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                            //  pr($return_array);exit;
                    }else if (strpos($url, "arabi21.comXXXXXXXXXXXXX") !== FALSE) {    
                           /*  $html = file_get_html($url); 
                             foreach($html->find('div.articleCont12') as $e) 
                              //$x = delete_all_between("<div", "</div>", $e->innertext);  
                              $x = $e->innertext;  
                            
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;  
                              
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit; */
                    }
                    else if (strpos($url, "dw.de") !== FALSE || strpos($url, "dw.com") !== FALSE) {   
                             $html = file_get_html($url);  
                             $x = '';
                             
                             foreach($html->find('div.longText') as $e) 
                              $x .= $e->innertext;  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              $x = delete_all_between("<a", "</a>", $x);  
                              
                              $x = delete_all_between("<h2", "</h2>", $x);  
                              $x = delete_all_between("<h2", "</h2>", $x);  
                              $x = delete_all_between("<h2", "</h2>", $x);  
                              $x = delete_all_between("<h2", "</h2>", $x);  
                              $x = delete_all_between("<h2", "</h2>", $x);  
                              $x = delete_all_between("<h2", "</h2>", $x);  
                              $x = delete_all_between("<h2", "</h2>", $x);  
                              $x = delete_all_between("<h2", "</h2>", $x);  
                              $x = delete_all_between("<h2", "</h2>", $x);  
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);  
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "palinfo.com") !== FALSE) {   
                             $html = file_get_html($url);  
                             foreach($html->find('div.ltrFullPageDiv') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                             // $x = delete_all_between("<div", "</div>", $x);  
                           //   $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "basnews.com") !== FALSE) {   
                             $html = file_get_html($url);  
                             foreach($html->find('div.a_box_text') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                             // $x = delete_all_between("<div", "</div>", $x);  
                           //   $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "alaraby.co.ukXXXXXXXXXXXXX") !== FALSE) {    
                            /* $html = file_get_html($url);  
                             foreach($html->find('div.DP-MainText') as $e) 
                              $x = $e->innertext;  
                              
                              if ( isset($x) ) {
                                  $x = delete_all_between("<iframe", "</iframe>", $x);  
                                  $x = delete_all_between("<iframe", "</iframe>", $x);  
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                   
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node;  */
                              
                    } 
                    else if (strpos($url, "paltimes.net") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div#last') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              if ( isset($x) ) {
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);
                                  $x = delete_all_between("<script", "</script>", $x);
                                  $x = delete_all_between("<script", "</script>", $x);
                                  $x = delete_all_between("<script", "</script>", $x);
                                    
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  
                                  $x = delete_all_between("<strong", "</strong>", $x); 
                                  $x = delete_all_between("<strong", "</strong>", $x); 
                                  $x = delete_all_between("<strong", "</strong>", $x); 
                                  $x = delete_all_between("<strong", "</strong>", $x); 
                                   
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                          //    pr($return_array);exit;
                    }
                    else if (strpos($url, "alamalmal.net") !== FALSE) {    
                             $html = file_get_html($url);  
                                              
                             foreach($html->find('div.item-page') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              if ( isset($x) ) {
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  
                                  $x = delete_all_between("<h1", "</h1>", $x);
                                  $x = delete_all_between("<h1", "</h1>", $x);
                                  $x = delete_all_between("<h1", "</h1>", $x);
                                  $x = delete_all_between("<h1", "</h1>", $x);
                                  $x = delete_all_between("<h1", "</h1>", $x);
                                  
                                  $x = delete_all_between("<h2", "</h2>", $x);
                                  $x = delete_all_between("<h2", "</h2>", $x);
                                  $x = delete_all_between("<h2", "</h2>", $x);
                                  $x = delete_all_between("<h2", "</h2>", $x);
                                  $x = delete_all_between("<h2", "</h2>", $x);
                                   
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "roaanews.net") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.entry-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              if ( isset($x) ) {
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                             // pr($return_array);exit;
                    }
                    else if (strpos($url, "ismailyonline.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div#artexti') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              if ( isset($x) ) {
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x); 
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x);
                                   
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                             // pr($return_array);exit;
                    }
                    else if (strpos($url, "almaydan2.net") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.intro') as $e) 
                              //$x = delete_all_between("<div", "</div>", $e->innertext);  
                              $x = $e->innertext;  
                              
                              if ( isset($x) ) {
                                 // $x = delete_all_between("<div", "</div>", $x);  
                                 // $x = delete_all_between("<div", "</div>", $x);  
                                 // $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                             // pr($return_array);exit;
                    }else if (strpos($url, "nwafecom.net") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div#textcontent') as $e) 
                              //$x = delete_all_between("<div", "</div>", $e->innertext);  
                              $x = $e->innertext;  
                              
                              if ( isset($x) ) {
                                 // $x = delete_all_between("<div", "</div>", $x);  
                                 // $x = delete_all_between("<div", "</div>", $x);  
                                 // $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<iframe", "</iframe>", $x);  
                                  $x = delete_all_between("<iframe", "</iframe>", $x);  
                                  $x = delete_all_between("<iframe", "</iframe>", $x);  
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                   
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                             // pr($return_array);exit;
                    }else if (strpos($url, "aljubailtoday.com.sa") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.entry-content') as $e) { 
                                  $x = delete_all_between("<div", "</div>", $e->innertext);  
                                  $x = delete_all_between("<a", "</a>", $e->innertext); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                             }
                              
                              if ( isset($x) ) {
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                   
                                 // $x = delete_all_between("<div", "</div>", $x);  
                                 // $x = delete_all_between("<div", "</div>", $x);  
                                 // $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                             // pr($return_array);exit;
                    }else if (strpos($url, "arjja.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div#textcontent') as $e) 
                              $x = $e->innertext;  
                              
                              if ( isset($x) ) {
                                  $x = delete_all_between("<iframe", "</iframe>", $x);  
                                  $x = delete_all_between("<iframe", "</iframe>", $x);  
                                  $x = delete_all_between("<iframe", "</iframe>", $x);  
                                  $x = delete_all_between("<iframe", "</iframe>", $x);  
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x);  
                                   
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                             // pr($return_array);exit;
                    }else if (strpos($url, "twasul.infoXXXXXXXX") !== FALSE) {    
                            /* $html = file_get_html($url);  
                             foreach($html->find('div.entry-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              if ( isset($x) ) {
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x); 
                                  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                   
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x); 
                                   
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                             // pr($return_array);exit;   */
                    } 
                    else if (strpos($url, "charlesayoub.com") !== FALSE) {   
                             $html = file_get_html($url);            
                             foreach($html->find('div.tahoma') as $e)  {    
                                 if (strlen($e->innertext) > 100) $x = delete_all_between("<div", "</div>", $e->innertext);
                             } 
                                
                              if ( isset($x) ) {
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x); 
                                   
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x); 
                                   
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                   
                             // pr($return_array);exit;
                    } 
                    else if (strpos($url, "goalna.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.articalText') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              if ( isset($x) ) {
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x); 
                                   
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                            //  pr($return_array);exit;
                    } 
                    else if (strpos($url, "mubasher.info") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.article__content-text') as $e) 
                              $x = $e->innertext;  
                              
                              if ( isset($x) ) {
                                  
                                /*  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x);    */
                                  
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                   
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x); 
                                   
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                            //  pr($return_array);exit;
                    } 
                    else if (strpos($url, "baareq.com.saXXXXXXXXXXXX") !== FALSE) {    
                          /*   $html = file_get_html($url);  
                             foreach($html->find('div#post-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              if ( isset($x) ) {
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                            //  pr($return_array);exit;     */
                    }
                    else if (strpos($url, "hafralbaten.com") !== FALSE || strpos($url, "aldawadmi.net") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div#post-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              if ( isset($x) ) {
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x);
                                  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);
                                  
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                  $x = delete_all_between("<iframe", "</iframe>", $x);
                                    
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "mini-news.net") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div#post-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              if ( isset($x) ) {
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                             // pr($return_array);exit;
                    }
                    else if (strpos($url, "ajialq8.comXXXXXXXXXX") !== FALSE) {    
                           /*  $html = file_get_html($url);  
                             foreach($html->find('div.entry-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              if ( isset($x) ) {
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x); 
                                  $x = delete_all_between("<div", "</div>", $x); 
                                  $x = delete_all_between("<div", "</div>", $x); 
                                  $x = delete_all_between("<div", "</div>", $x); 
                                  $x = delete_all_between("<div", "</div>", $x); 
                                  $x = delete_all_between("<div", "</div>", $x); 
                                  $x = delete_all_between("<div", "</div>", $x); 
                                  $x = delete_all_between("<div", "</div>", $x); 
                                  $x = delete_all_between("<div", "</div>", $x); 
                                  $x = delete_all_between("<div", "</div>", $x); 
                                  $x = delete_all_between("<div", "</div>", $x); 
                                  $x = delete_all_between("<div", "</div>", $x); 
                                  $x = delete_all_between("<div", "</div>", $x); 
                                  $x = delete_all_between("<div", "</div>", $x);
                                   
                                  $x = delete_all_between("<span", "</span>", $x); 
                                  $x = delete_all_between("<span", "</span>", $x); 
                                  $x = delete_all_between("<span", "</span>", $x); 
                                  $x = delete_all_between("<span", "</span>", $x); 
                                  $x = delete_all_between("<span", "</span>", $x); 
                                  $x = delete_all_between("<span", "</span>", $x); 
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  $x = delete_all_between("<a", "</a>", $x);
                                   
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x); 
                                   
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                             // pr($return_array);exit; */
                    }
                    else if (strpos($url, "dailymedicalinfo.com1111111") !== FALSE) {    
                           /*  $html = file_get_html($url);  
                             foreach($html->find('div.ordered_style') as $e) 
                              $x = delete_all_between("<a", "</a>", $e->innertext);  
                              
                              if ( isset($x) ) {
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x); 
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                              pr($return_array);exit; */
                    }
                    else if (strpos($url, "almjardh.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div#textcontent') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              if ( isset($x) ) {
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                             // pr($return_array);exit;
                    }else if (strpos($url, "rasdnews.net") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.pf-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              if ( isset($x) ) {
                                 // $x = delete_all_between("<div", "</div>", $x);  
                                 // $x = delete_all_between("<div", "</div>", $x);  
                                  //$x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                             // pr($return_array);exit;
                    }
                    else if (strpos($url, "asir.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.entry p:not(:first-child)') as $e) 
                              $x = delete_all_between("<a", "</a>", $e->innertext);  
                              
                              if ( isset($x) ) {
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                           
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "alayam.comXXXXXXXXXXXX") !== FALSE) {    
                            /* $html = file_get_html($url);  
                             foreach($html->find('div.detail') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              if ( isset($x) ) {
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                           
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                            //  pr($return_array);exit;  */
                    }
                    else if (strpos($url, "alwasatnews.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div#news_body') as $e) 
                              $x = delete_all_between("<a", "</a>", $e->innertext);  
                              
                              if ( isset($x) ) {
                              //    $x = delete_all_between("<div", "</div>", $x);  
                               ////   $x = delete_all_between("<div", "</div>", $x);  
                               //   $x = delete_all_between("<div", "</div>", $x); 
                               //   $x = delete_all_between("<div", "</div>", $x); 
                                //  $x = delete_all_between("<div", "</div>", $x); 
                                  
                                  $x = delete_all_between("<h1", "</h1>", $x); 
                                  $x = delete_all_between("<h1", "</h1>", $x); 
                                  $x = delete_all_between("<h1", "</h1>", $x); 
                                  $x = delete_all_between("<h1", "</h1>", $x); 
                                  $x = delete_all_between("<h1", "</h1>", $x); 
                                  
                                  $x = delete_all_between("<h2", "</h2>", $x); 
                                  $x = delete_all_between("<h2", "</h2>", $x); 
                                  $x = delete_all_between("<h2", "</h2>", $x); 
                                  $x = delete_all_between("<h2", "</h2>", $x); 
                                  $x = delete_all_between("<h2", "</h2>", $x); 
                                  $x = delete_all_between("<h2", "</h2>", $x); 
                                  
                                  $x = delete_all_between("<ul", "</ul>", $x); 
                                  $x = delete_all_between("<ul", "</ul>", $x); 
                                  $x = delete_all_between("<ul", "</ul>", $x); 
                                  $x = delete_all_between("<ul", "</ul>", $x); 
                                  $x = delete_all_between("<ul", "</ul>", $x); 
                                  
                                  $x = delete_all_between("<li", "</li>", $x); 
                                  $x = delete_all_between("<li", "</li>", $x); 
                                  $x = delete_all_between("<li", "</li>", $x); 
                                  $x = delete_all_between("<li", "</li>", $x); 
                                  $x = delete_all_between("<li", "</li>", $x); 
                                  $x = delete_all_between("<li", "</li>", $x); 
                                  $x = delete_all_between("<li", "</li>", $x); 
                                  $x = delete_all_between("<li", "</li>", $x); 
                                  $x = delete_all_between("<li", "</li>", $x); 
                                   
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x); 
                                  $x = delete_all_between("<a ", "</a>", $x); 
                                  $x = delete_all_between("<a ", "</a>", $x); 
                                  $x = delete_all_between("<a ", "</a>", $x); 
                                  $x = delete_all_between("<a ", "</a>", $x); 
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                           
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "almaghribtoday.netXXXXXXXXXXXXX") !== FALSE) {    
                          /*   $html = file_get_html($url);  
                             foreach($html->find('div#balmon') as $e) 
                              //$x = delete_all_between("<a", "</a>", $e->innertext);  
                              $x = $e->innertext;  
                              
                              if ( isset($x) ) {
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x); 
                                   
                                  /*$x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  */
                                  
                               /*   $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                           
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                            //  pr($return_array);exit;  */
                    }
                    else if (strpos($url, "alkhaleejaffairs.org") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.text') as $e) 
                              $x = delete_all_between("<a", "</a>", $e->innertext);  
                              
                              if ( isset($x) ) {
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x); 
                                   
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x); 
                                   
                                  $x = delete_all_between("<iframe ", "</iframe>", $x);   
                                  $x = delete_all_between("<iframe ", "</iframe>", $x);   
                                  $x = delete_all_between("<iframe ", "</iframe>", $x);   
                                  $x = delete_all_between("<iframe ", "</iframe>", $x);   
                                   
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                           
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "3eesho.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.article_description') as $e) 
                              $x = delete_all_between("<a", "</a>", $e->innertext);  
                              
                              if ( isset($x) ) {
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<a ", "</a>", $x); 
                                   
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  
                                  $x = delete_all_between("<iframe", "</iframe>", $x);  
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                           
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                            //  pr($return_array);exit;
                    }
                    else if (strpos($url, "freeswcc.comXXXXXXXXXX") !== FALSE) {    
                           /*  $html = file_get_html($url);  
                             foreach($html->find('div.fultext') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              if ( isset($x) ) {
                                  $x = delete_all_between("<p", "</p>", $x);  
                                  $x = delete_all_between("<span", "</span>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                            //  pr($return_array);exit;     */
                    } 
                    else if (strpos($url, "saso.gov.sa") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div#ctl00_PlaceHolderMain_PageContent__ControlWrapper_RichHtmlField') as $e) 
                              //$x = delete_all_between("<div", "</div>", $e->innertext);  
                              $x =  $e->innertext;  
                              
                              if ( isset($x) ) {
                               //   $x = delete_all_between("<div", "</div>", $x);  
                                //  $x = delete_all_between("<div", "</div>", $x);  
                                 // $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                            //  pr($return_array);exit;
                    }else if (strpos($url, "moh.gov.sa") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div#ctl00_PlaceHolderMain_ctl02__ControlWrapper_RichHtmlField') as $e) 
                              //$x = delete_all_between("<div", "</div>", $e->innertext);  
                              $x =  $e->innertext;  
                              
                              if ( isset($x) ) {
                               //   $x = delete_all_between("<div", "</div>", $x);  
                                //  $x = delete_all_between("<div", "</div>", $x);  
                                 // $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                            //  pr($return_array);exit;
                    } 
                    else if (strpos($url, "sabq.org") !== FALSE) {     
                             $html = file_get_html($url);  
                             foreach($html->find('div#object-var-content') as $e) 
                              $x = $e->innertext;  
                              
                              if ( isset($x) ) {
                                //  $x = delete_all_between("<div", "</div>", $x);  
                                 // $x = delete_all_between("<div", "</div>", $x);  
                                 // $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x); 
                                   
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  $x = delete_all_between("<a", "</a>", $x);  
                                  
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                                  
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                            //  pr($return_array);exit;
                    } 
                    else if (strpos($url, "sabqq.org") !== FALSE) {     
                             $html = file_get_html($url);  
                             foreach($html->find('span#content') as $e) 
                              $x = $e->innertext;  
                              
                              if ( isset($x) ) {
                                  $x = delete_all_between("<span", "</span>", $x);
                                  
                                //  $x = delete_all_between("<div", "</div>", $x);  
                                 // $x = delete_all_between("<div", "</div>", $x);  
                                 // $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                            //  pr($return_array);exit;
                    } 
                    else if (/*strpos($url, "alsharq.net.sa") !== FALSE ||*/ strpos($url, "cutt.us") !== FALSE ) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.entry p') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              if ( isset($x) ) {
                                //  $x = delete_all_between("<div", "</div>", $x);  
                                 // $x = delete_all_between("<div", "</div>", $x);  
                                 // $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x); 
                                  
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  $x = delete_all_between("<iframe", "</iframe>", $x); 
                                  
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                               
                                  $return_array['paragraph'][0]['contents'] = $x;   
                              } 
                            
                              $return_array['paragraph'][] = $node; 
                                    
                            //  pr($return_array);exit;
                    } 
                    else if (strpos($url, "khaberni.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('span#more-content') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                              
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    }
                    else if (strpos($url, "fajr.saXXXXXXXXXXX") !== FALSE) {    
                             /*$html = file_get_html($url);  
                             foreach($html->find('div.wa-post-detail') as $e) 
                              $x = $e->innertext;                   
                                
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x); 
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              $x = delete_all_between("<iframe", "</iframe>", $x); 
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x);
                               
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit; */
                    }  
                    else if (strpos($url, "yallakora.com") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.articleBody') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    } 
                    else if (strpos($url, "cma.org.sa") !== FALSE) {    
                             $html = file_get_html($url);  
                             foreach($html->find('div.rtEditorHeight div:nth-child(1)') as $e) 
                              $x = $e->innertext;  
                              
                              
                              //$x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                             // $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                           //   pr($return_array);exit;
                    } 
                    else if (strpos($url, "goal.com") !== FALSE) {  
                             $html = file_get_html($url);  
                             $return_array['paragraph'][] = array();
                                  
                             if ( $html ){
                                 foreach($html->find('div.article-text') as $e) 
                                  $x = delete_all_between("<div", "</div>", $e->innertext);  
                                  
                                  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);  
                                  $x = delete_all_between("<div", "</div>", $x);
                                   
                                  $x = delete_all_between("<object", "</object>", $x); 
                                  $x = delete_all_between("<object", "</object>", $x); 
                                  $x = delete_all_between("<object", "</object>", $x); 
                                  
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  $x = delete_all_between("<a", "</a>", $x);
                                  
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  $x = delete_all_between("<script", "</script>", $x);  
                                  
                                  $return_array['paragraph'][0]['contents'] = $x;    
                                
                                  $return_array['paragraph'][] = $node; 
                             }
                              
                             // pr($return_array);exit;
                    } 
                    else if (strpos($url, "24.aeXXXXXX") !== FALSE) {  
                           /*  $html = file_get_html($url);  
                             foreach($html->find('div.khabarcontent') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                          //    pr($return_array);exit;  */
                    } 
                    else if (/*strpos($url, "elheddaf.com") !== FALSE ||*/ strpos($url, "GalerieArtciles") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div#post_core') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = delete_all_between("<center", "</center>", $x);
                              $x = delete_all_between("<center", "</center>", $x);
                              $x = delete_all_between("<center", "</center>", $x);
                              $x = delete_all_between("<center", "</center>", $x);
                              
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              $x = delete_all_between("<iframe", "</iframe>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                             //pr($return_array);exit;
                    } 
                    else if (strpos($url, "etilaf.org") !== FALSE) {  
                             $html = file_get_html($url);  
                             foreach($html->find('div.itemFullText') as $e) 
                              $x = delete_all_between("<div", "</div>", $e->innertext);  
                              
                              
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              $x = delete_all_between("<a", "</a>", $x);
                              
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                                
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x;    
                            
                              $return_array['paragraph'][] = $node; 
                              
                          //    pr($return_array);exit;
                    } 
                    
                    
                  /*  else if (strpos($url, "alwatanvoice.com") !== FALSE) {   */
                        
                        /*if (strpos($node['contents'], 'الصفحة المطلوبة غير موجودة') !== FALSE){
                            continue;
                        }*/
                    /*    if ( 
                          strpos($node['contents'], 'To view this video please enable JavaScript') === FALSE &&
                          strpos($node['contents'], 'firstChild.nodeValu') === FALSE &&
                          strpos($node['contents'], 'I added a video to a') === FALSE &&
                          strpos($node['contents'], 'اترياضةمقالاتدراساتاسلامياتثقافةم') === FALSE &&
                          strpos($node['contents'], 'التصويت من تعتقد أنها الشخصية الأهم لعام') === FALSE &&
                          strpos($node['contents'], 'حجم الخط تصغير') === FALSE &&
                          strpos($node['contents'], '<br/>شؤون فلسطينية<br/>عربي<br/>دولي') === FALSE &&
                          strpos($node['contents'], 'فنون<br/>الفن الجميل<br/>سينما وتلفزيون') === FALSE &&
                          strpos($node['contents'], 'options.element') === FALSE &&
                          strpos($node['contents'], 'أخبار ذات صلة') === FALSE &&
                          strpos($node['contents'], 'روابط سريعة هيئة التحرير') === FALSE &&
                          strpos($node['contents'], 'عن دنيا الوطن') === FALSE &&
                          strpos($node['contents'], 'الصفحة المطلوبة غير موجودة .. قد تكون اتبعت رابط خاطئ او أن هذه الصفحة قد حذفت.') === FALSE &&
                          strpos($node['contents'], 'المعذرة ، لقد حدث خطأ ما') === FALSE &&
                          strpos($node['contents'], 'المعذرة ، لقد حدث خطأ ما<br/>تم تسجيل الخطأ لإصلاحه ، نرجو التوجه للصفحة الرئيسية .. شكرا لصبرك.') === FALSE &&
                          strpos($node['contents'], 'روابط سريعة هيئة التحرير<br/>عن دنيا الوطن<br/>اعلن معنا<br/>راسلنا English<br/>دنيا الجاليات<br/>دنيا الرأي<br/>دنيا الأطفال دنيا الوطن فيديو<br/>الخلاصة') === FALSE &&
                          strpos($node['contents'], 'اعلن معنا') === FALSE &&
                          strpos($node['contents'], 'راسلنا English') === FALSE &&
                          strpos($node['contents'], 'دنيا الجاليات') === FALSE &&
                          strpos($node['contents'], 'دنيا الرأي') === FALSE &&
                          strpos($node['contents'], 'دنيا الأطفال دنيا الوطن فيديو') === FALSE &&
                          strpos($node['contents'], 'getElementsByTagName') === FALSE 
                       ) {   */        
                           /* $html = file_get_html($url); 
                            $x = ''; 
                            foreach($html->find('div.details') as $e) {    
                                 $x .=  ($e->innertext);  
                                // break;
                            }
                              
                              
                            //  $x = delete_all_between("<div", "</div>", $x);  
                            //  $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<div", "</div>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $x = delete_all_between("<script", "</script>", $x);  
                              $return_array['paragraph'][0]['contents'] = $x; */   
                            
                              //$return_array['paragraph'][] = $node; 
                      /* } */
                       
                  /*  }     */
                    else{
                         if ( 
                          strpos($node['contents'], 'To view this video please enable JavaScript') === FALSE &&
                          strpos($node['contents'], 'firstChild.nodeValu') === FALSE &&
                          strpos($node['contents'], 'اترياضةمقالاتدراساتاسلامياتثقافةم') === FALSE &&
                          strpos($node['contents'], 'التصويت من تعتقد أنها الشخصية الأهم لعام') === FALSE &&
                          strpos($node['contents'], 'حجم الخط تصغير') === FALSE &&
                          strpos($node['contents'], 'options.element') === FALSE &&
                          strpos($node['contents'], 'doubleclick.net') === FALSE &&
                          strpos($node['contents'], 'googletag.cmd.push') === FALSE &&
                          strpos($node['contents'], 'var theSummaries = new Array();') === FALSE &&
                          strpos($node['contents'], '.dailySocialNew') === FALSE &&
                          strpos($node['contents'], 'أخبار ذات صلة') === FALSE &&
                          strpos($node['contents'], 'روابط سريعة هيئة التحرير') === FALSE &&
                          strpos($node['contents'], 'عن دنيا الوطن') === FALSE &&
                          strpos($node['contents'], 'الصفحة المطلوبة غير موجودة .. قد تكون اتبعت رابط خاطئ او أن هذه الصفحة قد حذفت.') === FALSE &&
                          strpos($node['contents'], 'المعذرة ، لقد حدث خطأ ما') === FALSE &&
                          strpos($node['contents'], 'المعذرة ، لقد حدث خطأ ما<br/>تم تسجيل الخطأ لإصلاحه ، نرجو التوجه للصفحة الرئيسية .. شكرا لصبرك.') === FALSE &&
                          strpos($node['contents'], 'أخبار ذات صلة') === FALSE &&
                          strpos($node['contents'], 'روابط سريعة هيئة التحرير<br/>عن دنيا الوطن<br/>اعلن معنا<br/>راسلنا English<br/>دنيا الجاليات<br/>دنيا الرأي<br/>دنيا الأطفال دنيا الوطن فيديو<br/>الخلاصة') === FALSE &&
                          strpos($node['contents'], 'خدمات صحافةمحلية') === FALSE &&
                          strpos($node['contents'], 'عرب صحافة صحافة-محلية') === FALSE &&
                          strpos($node['contents'], 'سياسة<br/>تقارير<br/>حوادث<br/>محافظات<br/>تحقيقات<br/>رياضة') === FALSE &&
                          strpos($node['contents'], 'getElementsByTagName') === FALSE 
                       ) {  
                           $return_array['paragraph'][] = $node;
                           
                           //if (strpos($url, "youm7.com") !== FALSE) break; 
                       }  
                    }            
                }
            }
          
        }            
                 
        //وكالة انباء البحرين  
        if ($return_array['paragraph'] == '') { 
            $nodes = extract_tags( $string, 'td' );
            $first_item_flag1 = 1; 
                      
            foreach($nodes as $node) {
                $node['contents'] = trim(strip_tags($node['contents']));
                                
                $node['contents'] = preg_replace("/\s{2,}/"," ", $node['contents']);  //remove more than 2 spaces b/w 2 words
                    //echo('<pre>');        print_r($node); 
                if (mb_strlen($node['contents'], "UTF-8") < $paragraph_length) {  //else main paragraph 
                    unset($node);       //echo('ayman<br />');
                }  
                else {   
                     $node['len'] = mb_strlen($node['contents'], "UTF-8"); 
                     $return_array['paragraph'][] = $node;          
                }
            }
        }
        //  echo ('rrrrrrrrrrrrrrrrrrr');  echo($url);  exit;
        if (strpos($url, "ahram.org.egXXXXXXXXXXX") !== FALSE) {  
           /*  $html = file_get_html($url);  
             foreach($html->find('div#abstractDiv') as $e) 
              $x = delete_all_between("<div", "</div>", $e->innertext);  
              
              
              $x = delete_all_between("<a", "</a>", $x);
              $x = delete_all_between("<a", "</a>", $x);
              $x = delete_all_between("<a", "</a>", $x);
              $x = delete_all_between("<a", "</a>", $x);
              
              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
              $x = preg_replace("/<img[^>]+\>/i", " ", $x); 
                
              $x = delete_all_between("<div", "</div>", $x);  
              $x = delete_all_between("<div", "</div>", $x);  
              $x = delete_all_between("<div", "</div>", $x);  
              $x = delete_all_between("<script", "</script>", $x);  
              $x = delete_all_between("<script", "</script>", $x);  
              
              $return_array['paragraph'][0]['contents'] = $x . '<br /><br />' . $return_array['paragraph'][0]['contents'];    
            
              $return_array['paragraph'][] = $node;    */
        }  
              
                    
     //   echo ('22222<pre>');print_r($return_array['paragraph']); 
     //   exit;
                    
        // Parse Images
        $images_array = extract_tags( $string, 'img' );
        
      /*  for ($i=0;$i<=sizeof($images_array);$i++){
            $img = trim(@$images_array[$i]['attributes']['src']);
            break;
        } */
                   //  echo('imng: ' . $img);      exit;
      //  return array($return_array['paragraph'], $img);  
                    
        $images = array();
        for ($i=0;$i<=sizeof($images_array);$i++){
            $img = trim(@$images_array[$i]['attributes']['src']);
            $style = trim(@$images_array[$i]['attributes']['style']);
            $width = preg_replace("/[^0-9.]/", '', @$images_array[$i]['attributes']['width']);
            $height = preg_replace("/[^0-9.]/", '', @$images_array[$i]['attributes']['height']);
            
            $img = str_replace("comimages", "com/images", $img);   //alchourouk
                  
            $ext = trim(pathinfo($img, PATHINFO_EXTENSION));
            
            if (strpos($url, "alchourouk.com") !== FALSE) {
                if ($i == 3) {
                   $images[] = array("img" => "http://www.alchourouk.com/" . $img);
                   break; 
                }
            }
                       //  echo($img.'<br />');
            /*if (strpos($url, "alkhaleejonline.net") !== FALSE) {
                if (strpos($img, "1280/960") !== FALSE) {   
                    $images[] = array("img" => $img);
                    break;
                }
                
            } */
            if (strpos($url, "paltoday.tv") !== FALSE) {
                if (strpos($img, "webnews") !== FALSE) {   
                    $images[] = array("img" => "http://paltoday.tv/". $img);
                    break;
                }
                  
            } 
            
            if (strpos($url, "moe.gov.sa") !== FALSE) {
                if (strpos($img, "news/SiteAssets") !== FALSE) {   
                    $images[] = array("img" => "http://he.moe.gov.sa". $img);
                    break;
                }
                  
            }
            
           /* if (strpos($url, "aliraqnews.com") !== FALSE) {
                if (strpos($img, "wp-content/uploads") !== FALSE) {   
                    $images[] = array("img" => $img);
                    break;
                }
                  
            }  */
            
            if (strpos($url, "etilaf.org") !== FALSE) {
                if (strpos($img, "items/cache") !== FALSE) {   
                    $images[] = array("img" => "http://www.etilaf.org". $img);
                    break;
                }
                  
            }
            
            if (strpos($url, "shaam.org") !== FALSE) {
                if (strpos($img, "/media/k2/items/cache") !== FALSE) {   
                    $images[] = array("img" => "http://www.shaam.org". $img);
                   // break;
                }
                  
            }
            
            if (strpos($url, "moh.gov.sa") !== FALSE) {
                if (strpos($img, "News/PublishingImages") !== FALSE) {   
                    $images[] = array("img" => "http://www.moh.gov.sa". $img);
                    break;
                }
                  
            }
            
            if (strpos($url, "14march.org") !== FALSE) {
                if (strpos($img, "images/medium") !== FALSE) {   
                    $images[] = array("img" => "http://www.14march.org/" . $img);
                    break;
                }
                  
            }
            
            if (strpos($url, "kaahe.org") !== FALSE) {
                if (strpos($img, "workflow/dev/news") !== FALSE) {   
                    $images[] = array("img" => "http://www.kaahe.org". $img);
                    break;
                }
                  
            }
              
            if (strpos($url, "jeddahwalnas.com") !== FALSE) {
                if (strpos($img, "component/imgen") !== FALSE) {   
                    $images[] = array("img" => "http://www.jeddahwalnas.com". $img);
                    break;
                }   
            }
            
            if (strpos($url, "alroya.om") !== FALSE) {
                if (strpos($img, "photos.alroya.info") !== FALSE) {   
                    $images[] = array("img" => $img);
                    break;
                }   
            }
            
           /* if (strpos($url, "albiladpress.com") !== FALSE) {
                if (strpos($img, "caricatures") === FALSE && strpos($img, "newsimage") === FALSE) {   
                    $images[] = array("img" => "http://www.albiladpress.com/". $img);
                    break;
                }   
            }  */
                   
           /* if (strpos($url, "almanar.com.lb") !== FALSE) {
                if ( 
                      strpos($img, "Politicians") !== FALSE || strpos($img, "akhbar-logo.jpg") !== FALSE || strpos($img, "Asia") !== FALSE ||
                      strpos($img, "Africa") !== FALSE || strpos($img, "America") !== FALSE || strpos($img, "World") !== FALSE
                   ) {   
                       echo($img . '<br />');
                    $images[] = array("img" => $img);
                    break;
                }   
            }  */
            
           /* if (strpos($url, "alforatnews.com") !== FALSE) {
                if (strpos($img, "news/image") !== FALSE) {   
                    $images[] = array("img" => $img);
                    break;
                }   
            }     */
            
           /* if (strpos($url, "syrianow.sy") !== FALSE) {
                if (strpos($img, "news/images") !== FALSE) {   
                    $images[] = array("img" => "http://www.syrianow.sy" . $img);
                    break;
                }   
            }  */
            
            if (strpos($url, "alnoornews.net") !== FALSE) {
                if (strpos($img, "images/news") !== FALSE || strpos($img, "images/image") !== FALSE || strpos($img, "-alnoornews") !== FALSE) {   
                    $images[] = array("img" => "http://alnoornews.net". $img);
                    break;
                }
                  
            }
            
           /* if (strpos($url, "altibbi.com") !== FALSE) {
                if (strpos($img, "global/img/website") !== FALSE) {   
                    $images[] = array("img" => $img);
                    break;
                }
                  
            }  */
                     // echo('555555555555');pr($images);             
            if (strpos($url, "almaghribtoday.net") !== FALSE) {     
                if (strpos($img, "img.almaghribtoday.net") !== FALSE) {    
                    $images[] = array("img" => $img);  
                    
                    break;
                }
                  
            }
            
            if (strpos($url, "webteb.com") !== FALSE) {     
                if (strpos($img, "static.webteb.net/images/content") !== FALSE) {    
                    $images[] = array("img" => $img);  
                    
                    break;
                }
                  
            }
                  // pr($images);
            if($img && $ext != 'gif') 
            {              
                if (strpos($url, 'paltoday.tv') === FALSE) {
                    if (substr($img,0,7) == 'http://' || substr($img,0,7) == 'https:/'); 
                    else if (substr($img,0,2) == '//'){
                        $img = "http:" . $img;
                    }
                    elseif (substr($img,0,1) == '/' || $base_override)
                        $img = $base_url . $img;
                    else 
                        $img = $relative_url . $img;
                }
                
                if ($width == '' && $height == '')
                {
                    $details = @getimagesize($img);
                    
                    if(is_array($details))
                    {
                        list($width, $height, $type, $attr) = $details;
                    } 
                }
                // cleberatis yahoo maktoob site issue done by taymoor 
                if(strpos($img,'transparent') !== false){
                    if(strpos($style,'background-image') !== false){
                        preg_match("/http(.*?)jpg/", $style, $Matches); 
                        $img = $Matches[0] ; 
                    }    
                }
                    
                $width = intval($width);
                $height = intval($height);
                          
                if ($width > 199 || $height > 199 )
                {            
                    if (
                        (($width > 0 && $height > 0 && (($width / $height) < 3) && (($width / $height) > .2)) 
                            || ($width > 0 && $height == 0 && $width < 800) 
                            || ($width == 0 && $height > 0 && $height < 800)
                        ) 
                        && strpos($img, 'logo') === false )
                    {
                        //$images[] = array("img" => $img, "width" => $width, "height" => $height, 'area' =>  ($width * $height),'offset' => $images_array[$i]['offset']);
                        $images[] = array("img" => $img);
                    }
                }
                //echo('-'.$img.'<br />'); 
            }  
        }
           //pr($images);      exit;
         //   echo($url);        
        if (strpos($url, 'alqabas.com.kw') !== FALSE) {
            foreach($images as $img){ 
                if (strpos($img['img'], 'Temp/Pictures') !== FALSE) {
                    $return_array['images'][0] = str_replace('\\', '/', $img['img']);
                }
            }
        }
        elseif (strpos($url, 'almustagbal.com') !== FALSE) {   
            foreach($images as $img){ 
                if (strpos($img['img'], 'public/uploads/') !== FALSE) {
                    $return_array['images'][0] = $img['img'];
                }
            }
        } 
        elseif (strpos($url, 'alkhabarkw.com') !== FALSE || strpos($url, 'alkhabarsport.com') !== FALSE) {   
            foreach($images as $img){ 
                if (strpos($img['img'], 'thumbnail.php') !== FALSE) {
                    $return_array['images'][0] = $img['img'];
                }
            }
        } 
        elseif (strpos($url, 'sport.ahram.org.eg') !== FALSE) {   
            foreach($images as $img){ 
                if (strpos($img['img'], 'Media/NewsMedia') !== FALSE) {
                    $return_array['images'][0] = $img['img'];
                    break;
                }
            }
        } 
        elseif (strpos($url, 'zamalekfans.com') !== FALSE) {   
            foreach($images as $img){ 
                if (strpos($img['img'], '/uploads/') !== FALSE) {
                    $return_array['images'][0] = $img['img'];
                    break;
                }
            }
        } 
        elseif (strpos($url, 'ismailyonline.com') !== FALSE) {   
            foreach($images as $img){ 
                if (strpos($img['img'], 'files/pictures/slide') !== FALSE) {
                    $return_array['images'][0] = $img['img'];
                    break;
                }
            }
        } 
        elseif (strpos($url, 'alkuwaityah.com') !== FALSE || strpos($url, 'Alkuwaityah.com') !== FALSE) {   
            foreach($images as $img){ 
                if (strpos($img['img'], 'ArticleImages') !== FALSE) {
                    $return_array['images'][0] = $img['img'];
                    break;
                }
            }
        } 
        elseif (strpos($url, 'futuretvnetwork.com') !== FALSE) {   
            foreach($images as $img){ 
                if (strpos($img['img'], 'content_display_v2') !== FALSE) {
                    $return_array['images'][0] = $img['img'];
                }
            }
        } 
        elseif (strpos($url, 'reuters.com') !== FALSE) {   
            foreach($images as $img){ 
                if (strpos($img['img'], 'reutersmedia.net/resources') !== FALSE) {
                    $return_array['images'][0] = $img['img'];
                }
            }                  
        }
        elseif (strpos($url, 'annaharnews.net') !== FALSE) {   
            foreach($images as $img){ 
                if (strpos($img['img'], 'wp-content/themes') !== FALSE) {
                    $return_array['images'][0] = $img['img'];
                    break;
                }
            }                  
        } 
        elseif (strpos($url, 'hadath.net') !== FALSE) {   
            foreach($images as $img){ 
                if (strpos($img['img'], 'articlefiles') !== FALSE) {
                    $return_array['images'][0] = $img['img'];
                    break;
                }
            }                  
        } 
        /*elseif (strpos($url, "alkhaleejonline.net") !== FALSE) {
             foreach($images as $img){ 
                if (strpos($img['img'], "1280/960") !== FALSE) {   
                    $return_array['images'][0] = $img['img'];
                    break;
                }
             }      
        }  */
        elseif (strpos($url, 'hibapress.com') !== FALSE) {   
            foreach($images as $img){ 
                if (strpos($img['img'], 'hibapress') !== FALSE) {
                    $return_array['images'][0] = $img['img'];
                    break;
                }
            }                  
        } 
        elseif (strpos($url, 'reqaba.com') !== FALSE) {   
            foreach($images as $img){ 
                if (strpos($img['img'], 'ArticleImages') !== FALSE) {
                    $return_array['images'][0] = $img['img'];
                    break;
                }
            }                  
        }  
        elseif (strpos($url, 'alforsan.net') !== FALSE) {   
            foreach($images as $img){ 
                if (strpos($img['img'], '/news/') !== FALSE) {
                    $return_array['images'][0] = $img['img'];
                    break;
                }
            }                  
        }   
        elseif (strpos($url, 'bayankw.net') !== FALSE) {   
            foreach($images as $img){ 
                if (strpos($img['img'], 'banners') === FALSE) {
                    $return_array['images'][0] = $img['img'];
                    break;
                }
            }                  
        }
        elseif (strpos($url, 'akhbar-tech') !== FALSE) {   
            foreach($images as $img){ 
                if (strpos($img['img'], '500x300') !== FALSE) {
                    $return_array['images'][0] = $img['img'];
                    break;
                }
            }                  
        } 
        elseif (strpos($url, 'ahram.org.eg') !== FALSE) {   
            foreach($images as $img){ 
                if (strpos($img['img'], 'MediaFiles') !== FALSE) {
                    $return_array['images'][0] = $img['img'];
                    break;
                }
            }                  
        }
        elseif (strpos($url, 'atyabtabkha.3a2ilati.com') !== FALSE) {   
           /* foreach($images as $img){ 
                if (
                     strpos($img['img'], '/recipes/') !== FALSE || 
                     strpos($img['img'], '/tags/') !== FALSE || 
                     strpos($img['img'], '/tipimage/') !== FALSE
                    ) {
                    $return_array['images'][0] = $img['img'];
                    break;
                }
            }   */               
        }      
        elseif (isset($images[0])) {
            if (is_Array($images[0])) {
                $return_array['images'] = array_values(($images[0]));
            }
        } 
        
        if (strpos($url, "raialyoum.com") !== FALSE) {   
            $return_array['images'][0] = $images[1]['img'];
        }
        
        if (strpos($url, "almaghribtoday.net") !== FALSE) {   
            $return_array['images'][0] = $images[2]['img'];
        }   
        
        if (strpos($url, "hattpost.com") !== FALSE) {   //echo('rrrrrrrrrrrrrrrrrrrrrr');
            $return_array['images'][0] = $images[count($images)-1]['img'];     
        } 
        
        if (strpos($url, "shaam.org") !== FALSE) {   //echo('rrrrrrrrrrrrrrrrrrrrrr');
            $return_array['images'][0] = $images[count($images)-1]['img'];     
        } 
        
        if (strpos($url, "aliraqnews.com") !== FALSE) {   //echo('rrrrrrrrrrrrrrrrrrrrrr');
            $return_array['images'][0] = $images[1]['img'];     
        } 
        
        if (strpos($url, "syrianow.sy") !== FALSE) {   //echo('rrrrrrrrrrrrrrrrrrrrrr');
            $return_array['images'][0] = isset($images[4]['img']) ? $images[4]['img'] : $images[3]['img'];     
        }
        
        if (strpos($url, "alforatnews.com") !== FALSE) {   //echo('rrrrrrrrrrrrrrrrrrrrrr');
            $return_array['images'][0] = isset($images[1]['img']) ? $images[1]['img'] : $images[0]['img'];     
        }  
        
        if (strpos($url, "realmadrid.com") !== FALSE) {   //echo('rrrrrrrrrrrrrrrrrrrrrr');
            $return_array['images'][0] = isset($images[3]['img']) ? $images[3]['img'] : $images[2]['img'];     
        }
        
        if (strpos($url, "albiladpress.com") !== FALSE) {   //echo('rrrrrrrrrrrrrrrrrrrrrr');
            $return_array['images'][0] = isset($images[1]['img']) ? $images[1]['img'] : $images[0]['img'];     
        } 
        
        if (strpos($url, "almanar.com.lb") !== FALSE) {
            foreach($images as $img){ 
                if ( 
                      strpos($img['img'], "Politicians") !== FALSE || strpos($img['img'], "akhbar-logo.jpg") !== FALSE || strpos($img['img'], "Asia") !== FALSE ||
                      strpos($img['img'], "Africa") !== FALSE || strpos($img['img'], "America") !== FALSE || strpos($img['img'], "World") !== FALSE ||
                      strpos($img['img'], "MiddleEast") !== FALSE 
                   ) {   
                       echo($img . '<br />');
                       $images = array();
                       $images[] = array("img" => $img['img']);
                       break;
                }  
                else{
                    $images[] = array("img" => "http://image.almanartv.com.lb/edimg/akhbar-logo.jpg"); 
                }
            }      
             
        } 
        
        if (strpos($url, "shabiba.com") !== FALSE) {   //echo('rrrrrrrrrrrrrrrrrrrrrr');
            if (strpos($return_array['images'][0], "Banner") !== FALSE) {
                $return_array['images'][0] = "http://www.shabiba.com/images/Shabiba_Logo_New.gif";
            }    
        }  
        
        if (strpos($url, "rop.gov.om") !== FALSE) {   //echo('rrrrrrrrrrrrrrrrrrrrrr');
            if ($return_array['images'][0] == '') {
                $return_array['images'][0] = "http://upload.wikimedia.org/wikipedia/en/d/d1/ROP_-_Logo.jpg";
            }    
        } 
        
      /*  if (strpos($url, "sabanews.net") !== FALSE) {   //echo('rrrrrrrrrrrrrrrrrrrrrr');
            $return_array['images'][0] = "http://www.sabanews.net/ar/images/ar/sabanews_ar_logo.jpg";  
        } */ 
        
       
          //pr($return_array); exit;
       // pr($return_array);exit;
        //$return_array['total_images'] = count($return_array['images']); 
               // echo('hooooon: ');pr($return_array);    exit;
        //header('Cache-Control: no-cache, must-revalidate');
        //header('content="text/html; charset=utf-8" http-equiv="Content-Type"');
        /*header('Content-type: application/json');
        */
        
        if ($return_array['paragraph'] == "") {
            $return_array['paragraph'][] = $return_array['description'];
        }
        
    /*    if (strpos($url, "dailymedicalinfo.com") !== FALSE) {   //echo(' hussein 1111'); 
            unset($return_array['paragraph']);
            $return_array['paragraph'][0]['contents'] = $return_array['description'];     
        }       */
      /*  if (strpos($url, "elwatannews.com") !== FALSE) {   //echo(' hussein 1111'); 
            unset($return_array['paragraph']);
            $return_array['paragraph'][0]['contents'] = $return_array['description'];     
        }  */
        
        /*if (strpos($url, "almogaz.com") !== FALSE) {   //echo(' hussein 1111'); 
            unset($return_array['paragraph']);
            $return_array['paragraph'][0]['contents'] = $return_array['description'];     
        } */
              /* pr($return_array['description']);  
               pr($return_array);  
               exit; */
           //        echo ('returrrrrrrrrrrrrrrrrrrrrrrrrrrrr: ');pr($return_array);   //   exit;
        
        $url_parse = parse_url($url);
        
       // pr($url_parse);
        if (
              (!isset($url_parse['path']) && !isset($url_parse['query'])) ||
              ($url_parse['path'] == '/' && !isset($url_parse['query']) ) ||  //http://www.example.com/
              ($url_parse['path'] == '/' && @$url_parse['query'] == '' ) ||  //http://www.example.com/
              (strtolower(@$url_parse['path']) == 'portal') || //aljazera home page  
              (strtolower(@$url_parse['path']) == '/news/') //alhasela home page  
           ) {
               unset($return_array['paragraph']);
               $return_array['paragraph'][0]['contents']  = '';  
        }
        //   echo ('returrrrrrrrrrrrrrrrrrrrrrrrrrrrr: ');pr($return_array);        
        return ($return_array);
        //exit;


        /**
         * FUNCTIONS
         * Feel Free to put these in another file
         */
        
    }

//parse html tags    
function checkValues($value){
    $value = trim($value);
    if (get_magic_quotes_gpc()){
        $value = stripslashes($value);
    }
    $value = strtr($value, array_flip(get_html_translation_table(HTML_ENTITIES))); 
    $value = strip_tags($value);   
    $value = htmlspecialchars($value);
    return $value;
}

//extraxt text from html tag    
function extract_tags( $html, $tag, $selfclosing = null, $return_the_entire_tag = false, $charset = 'ISO-8859-1' ){
     
        if ( is_array($tag) ){
            $tag = implode('|', $tag);
        }
     
        //If the user didn't specify if $tag is a self-closing tag we try to auto-detect it
        //by checking against a list of known self-closing tags.
        $selfclosing_tags = array('area', 'base', 'basefont', 'br', 'hr', 'input', 'img', 'link', 'meta', 'col', 'param' );
        if ( is_null($selfclosing) ){
            $selfclosing = in_array( $tag, $selfclosing_tags );
        }
     
        //The regexp is different for normal and self-closing tags because I can't figure out 
        //how to make a sufficiently robust unified one.
        if ( $selfclosing ){
            $tag_pattern = 
                '@<(?P<tag>'.$tag.')            # <tag
                (?P<attributes>\s[^>]+)?        # attributes, if any
                \s*/?>                    # /> or just >, being lenient here 
                @xsi';
        }
        else{
            $tag_pattern = 
                '@<(?P<tag>'.$tag.')            # <tag
                (?P<attributes>\s[^>]+)?        # attributes, if any
                \s*>                    # >
                (?P<contents>.*?)            # tag contents
                </(?P=tag)>                # the closing </tag>
                @xsi';
        }
     
        $attribute_pattern = 
            '@
            (?P<name>\w+)                            # attribute name
            \s*=\s*
            (
                (?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote)    # a quoted value
                |                            # or
                (?P<value_unquoted>[^\s"\']+?)(?:\s+|$)            # an unquoted value (terminated by whitespace or EOF) 
            )
            @xsi';
     
        //Find all tags 
        if ( !preg_match_all($tag_pattern, $html, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE ) ){
            //Return an empty array if we didn't find anything
            return array();
        }
     
        $tags = array();
        foreach ($matches as $match){
     
            //Parse tag attributes, if any
            $attributes = array();
            if ( !empty($match['attributes'][0]) ){ 
     
                if ( preg_match_all( $attribute_pattern, $match['attributes'][0], $attribute_data, PREG_SET_ORDER ) ){
                    //Turn the attribute data into a name->value array
                    foreach($attribute_data as $attr){
                        if( !empty($attr['value_quoted']) ){
                            $value = $attr['value_quoted'];
                        } else if( !empty($attr['value_unquoted']) ){
                            $value = $attr['value_unquoted'];
                        } else {
                            $value = '';
                        }
     
                        //Passing the value through html_entity_decode is handy when you want
                        //to extract link URLs or something like that. You might want to remove
                        //or modify this call if it doesn't fit your situation.
                        $value = html_entity_decode( $value, ENT_QUOTES, $charset );
     
                        $attributes[$attr['name']] = $value; 
                        
                    }
                }
     
            }
     
            $tag = array(
                'tag_name' => $match['tag'][0],
                'offset' => $match[0][1], 
                'contents' => !empty($match['contents'])?$match['contents'][0]:'', //empty for self-closing tags
                'attributes' => $attributes, 
            );
            if ( $return_the_entire_tag ){
                $tag['full_tag'] = $match[0][0];             
            }
     
            $tags[] = $tag;
        }
     
        return $tags;
    }

//curl class     
class cURL{

    var $headers;

    var $user_agent;

    var $compression;

    var $cookie_file;

    var $proxy;

    function cURL($cookies = true, $cookie = 'cookies_fetch.txt', $compression = 'gzip,deflate', $proxy = ''){   
        $this->headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
        $this->headers[] = 'Connection: Keep-Alive';
        $this->headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
        $this->user_agent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)';
        $this->compression = $compression;
        $this->proxy = $proxy;
        $this->cookies = $cookies;
        if ($this->cookies == TRUE)
            $this->cookie($cookie);
    }

    function cookie($cookie_file){
        if (file_exists($cookie_file))
        {
            $this->cookie_file = $cookie_file;
        }
        else
        {
            fopen($cookie_file, 'w') or $this->error('The cookie file could not be opened. Make sure this directory has the correct permissions');
            $this->cookie_file = $cookie_file;
            fclose($this->cookie_file);
        }
    }

    function get($url, $referrer = true, $charset = 'utf-8'){     
        $url = str_replace("&amp;", '&', $url);
        $url = str_replace(" &amp;", '&', $url);
        $url = str_replace("&amp; ", '&', $url);
        
        //$url = urlencode($url);
        
        //header("Content-Type: text/html; charset=utf-8",0,301);
        $process = curl_init();
        curl_setopt($process, CURLOPT_URL, $url);
        //curl_setopt($process, CURLOPT_HTTPHEADER, $header);
        curl_setopt($process, CURLOPT_HEADER, 0);
        if ($referrer) curl_setopt($process, CURLOPT_REFERER, '[url=http://www.google.com]http://www.google.com[/url]');
        curl_setopt($process, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)');
        if ($this->cookies == TRUE)
            curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
        if ($this->cookies == TRUE)
            curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
        curl_setopt($process, CURLOPT_ENCODING, "gzip,deflate");
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_HTTPGET, true);
        //curl_setopt($process, CURLOPT_AUTOREFERER, true);
                                                                  
        if ($this->proxy)
            curl_setopt($process, CURLOPT_PROXY, $this->proxy);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($process,CURLOPT_SSL_VERIFYPEER, false);      //for https 
         
        $return = curl_exec($process);
        curl_close($process);  
        
        //$response = file_get_contents($url);
            //  echo('<br /><br /><br />ayman: ' . $url . '<br /><br /><br />');
       /* $data = ['data' => 'this', 'data2' => 'that'];
        $data = http_build_query($data);
        $context = [
          'http' => [
            'method' => 'GET',
            'header' => "Content-Type: text/html; charset=" . $charset . "\r\n",   
            "ssl" => array(
                                "allow_self_signed" => true,
                                "verify_peer" => false
                            ),
            "http" => array(
                                "timeout" => 60
                            ),
            'content' => $data,
            "timeout" => 50     //new 
          ]
        ];
        $context = stream_context_create($context);
        $response = file_get_contents($url, false, $context);*/
           //  echo('<br />res:' . $response.'<br />');
             /* echo('<br />' . $url . '<br />-------------------------------<br />');      
              echo($response);
              echo('-------------------------------<br />');  */    
        return $return;
    }

    function post($url, $data) {
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($process, CURLOPT_HEADER, 1);
        curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
        if ($this->cookies == TRUE)
            curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
        if ($this->cookies == TRUE)
            curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
        curl_setopt($process, CURLOPT_ENCODING, $this->compression);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        if ($this->proxy)
            curl_setopt($process, CURLOPT_PROXY, $this->proxy);
        curl_setopt($process, CURLOPT_POSTFIELDS, $data);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process,CURLOPT_SSL_VERIFYPEER, false);      //for https 
        $return = curl_exec($process);
        curl_close($process);
        return $return;
    }

    function error($error){
        die;
    }
}

?>