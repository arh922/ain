<?php
//require_once 'google/appengine/api/cloud_storage/CloudStorageTools.php';
//use google\appengine\api\cloud_storage\CloudStorageTools;

function pr($d){
    echo('<pre>');
    print_r($d);
    echo('</pre>');
}

function build_json_array($res){
    $json = ('[');
    $valid = false;
    global $conn;
                
    //while($row = $conn->db_fetch_array($res)) {
    while($row = $res->fetch_assoc()){
       
       $sort = array();
       $data_parts = array();
           
       foreach($row as $key => $value) {
           if (trim($value) != "") {
              $data[$key] = $value;
           }  
           if ( (strpos(trim($key), '_order') > 0) && trim($value) != 0){
               $sort[$key] = $value; 
           }
       }
         
       foreach($sort as $k => $v) {
          $split = explode("_", $k);
               
          if (isset($data[$split[0]])) { 
              $data_parts[$v][$split[0]] = $data[$split[0]];
          }
           
           unset($data[$split[0]]);    
       }
       
       asort($sort); 
       
       if (count($data_parts) > 0) {
           ksort($data_parts);
       }
       $i = 1;
       
       foreach($data_parts as $key_part => $parts) {
           foreach($parts as $ke => $val) {
               $data[$ke] = $val;
           }
       }         
       
       $json .= json_encode($data) . ",";
       
       $valid = true;
    }
       
    if ($valid) $json = substr($json, 0, strlen($json)-1);
    
    //else $json .= '{"msg":"error"}';
   
    $json .= ']';
              
    return ($json);
}

//increate view count in every visit
function update_article_view($aid){
    global $conn;
    $query = "update articles_html set views = views + 1 where id = '$aid'";
    $res = $conn->db_query($query);
}

///get # of comments on news
function get_num_of_comments($aid) {
    global $conn;
    $query = "select count(id) c from comments where aid = '$aid' and status = 1";
    $res = $conn->db_query($query);
    //$comments = $conn->db_fetch_array($res);
    $comments = $res->fetch_assoc(); 
    
    return $comments['c'];
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

//get news details
function get_html_article($aid, $cid, $uid = ''){
     global $conn;
    
    update_article_view($aid);
    
    $query = "select articles_html.*, GROUP_CONCAT(distinct concat(tags.id, ':', tags.name) SEPARATOR ', ') tags_name, 
                     GROUP_CONCAT(distinct concat(categories.id, ':', categories.name) SEPARATOR ', ') cats_name
               from articles_html
               inner join article_tags on article_tags.aid = articles_html.id 
               inner join article_categories on article_categories.aid = articles_html.id 
               inner join tags on tags.id = article_tags.tid
               inner join categories on article_categories.cid = categories.id
               where (articles_html.body not like '%error was encountered while trying to use an ErrorDocument to handle the request%' or articles_html.body not like '%Somethings wrong%') 
                     and articles_html.id='" . $aid . "' and client_id='" . $cid . "'";    //pr($query); 
                 
    $res = $conn->db_query($query);
    //$article = $conn->db_fetch_array($res);
    $article = $res->fetch_assoc();
            
    $log_obj = new Payment();
    $helper_obj = new Helper();
    
    $article['title'] = trim($article['title']);
    
    $article['title'] = html_entity_decode($article['title']); 
    
    //$payment = $log_obj->get_user_payment($uid);
            
   // $payment_end_date = @$payment->end_date;
    
    $now = time();
    
    
    //$payment_valid = 1;
             
    /*if ($payment_end_date < $now){
        $payment_valid = 0;
    } */
    
   // $article['payment_valid'] = $payment_valid;
    $article['num_comments'] = get_num_of_comments($aid);
    
    $article['body'] = trim($article['body']);
    
    if(
      // strrpos($article['body'],"Ø") !== FALSE || 
     //  strrpos($article['body'],"š") !== FALSE || 
       strrpos($article['body'],"ط§ظ") !== FALSE || 
       strrpos($article['body'],"ظپظ") !== FALSE  
       //strrpos($article['body'],"Â") 
       ) { 
        $article['body'] = "";
    }
    
    $article['image'] = str_replace("new.bab.com", "www.bab.com", @$article['image']);
    $article['image'] = str_replace("makkahonline.com.sa", "www.makkahnewspaper.com", @$article['image']);
    $article['image'] = str_replace("sites/default/files/images", "sites/default/files/styles/optimized_original/public", @$article['image']);
    
    if ( 
          $article['body'] == "<br /><br />" ||
          $article['body'] == "<br /><br /> <br /><br />" ||
          $article['body'] == "<br /><br /> <br />" ||
          $article['body'] == "<br /><br /><br />" ||
          $article['body'] == "<br /><br /><br /><br />"
       ) {
           $article['body'] = 'go to fb';
    }
         
            // pr($article);
    if ($article['body'] != "") {  
        //$article['body'] = $desc = str_replace('<p>', '\n', $article['body']); 
        //$article['body'] = $desc = str_replace('<p>', '', $article['body']); 
        //$article['body'] = $desc = str_replace('</p>', '', $article['body']); 
        
        $article['body'] = str_replace('<br /><br />  <br /><br />  <br /><br />  <br /><br />  <br /><br />  <br /><br />  <br /><br />  <br /><br />  <br /><br />  <br /><br />  <br /><br />  <br /><br />  <br /><br />  <br /><br />  <br /><br />  <br /><br />  <br /><br />  <br /><br />  <br /><br />  <br /><br />  <br /><br />', '<br /><br />', $article['body']);    //البلد السعودية
          
        $article['body'] = $desc = str_replace('<br /><br /> <br /><br />', '<br /><br />', $article['body']);    //البلد السعودية
        $article['body'] = $desc = str_replace('<br /><br /><br /><br />', '<br /><br />', $article['body']);    //البلد السعودية
        $article['body'] = $desc = str_replace('<br /><br /><br />', '<br /><br />', $article['body']);    //البلد السعودية
        $article['body'] = $desc = str_replace('<br /><br /> <br />', '<br /><br />', $article['body']);    //البلد السعودية
        $article['body'] = $desc = str_replace('<br /> <br /> <br />', '<br /><br />', $article['body']);    //البلد السعودية
        $article['body'] = $desc = str_replace('<br /> <br /><br />', '<br /><br />', $article['body']);    //البلد السعودية
                                                     
        $article['body'] = $desc = str_replace('<br/><br/><br/>', '<br /><br />', $article['body']);    //البلد السعودية
        $article['body'] = $desc = str_replace('<br /><br/> <br/> ', '<br /><br />', $article['body']);    //البلد السعودية
        $article['body'] = $desc = str_replace('<br/> <br/> <br/>', '<br /><br />', $article['body']);    //البلد السعودية
        
        $article['body'] = $desc = str_replace('<br /><br /><br/>
<br/>', '<br /><br />', $article['body']);    //البلد السعودية

        $article['body'] = $desc = str_replace('<br /><br /><br/>', '<br /><br />', $article['body']);    //البلد السعودية
        $article['body'] = $desc = str_replace('<br /><br /> <br/>', '<br /><br />', $article['body']);    //البلد السعودية
        $article['body'] = $desc = str_replace('<br /><br /> <br/><br/>', '<br /><br />', $article['body']);    //البلد السعودية
        
        $article['body'] = $desc = str_replace('<br /><br />  <br /><br />  <br /><br />', '<br /><br />', $article['body']);    //مصدر
        
        $article['body'] = str_replace('<br />  <br /><br />', '<br /><br />', $article['body']);    //البلد السعودية
        $article['body'] = str_replace('<br /><br />  <br /><br />  <br /><br />  <br /><br />', '<br /><br />', $article['body']);    //البلد السعودية
        
        $article['body'] = str_replace('<br />  <br />  <br />  <br />', '<br />', $article['body']);    
        $article['body'] = str_replace('<br />  <br />  <br />', '<br />', $article['body']);    
        
        $article['body'] = str_replace('1599998474121px; line-height: 1<br />3em;>', '', $article['body']);    //البلد السعودية
        
        if(strrpos($article['reversed_url'],"alnoornews.net") !== FALSE) {
            $newbody = explode("النور نيوز / بغداد", $article['body']);
            if (isset($newbody[1])) $article['body'] = $newbody[1];
            else $article['body'] = $newbody[0];
        }
        
        $article['body'] = str_replace('<!-- Plugins: BeforeDisplayContent -->              <!-- K2 Plugins: K2BeforeDisplayContent -->                                 <!-- Item introtext -->       <div class=itemIntroText>           <h3 style=text-align: justify;><span style=font-size: 12<br />1599998474121px; line-height: 1<br />3em;>', '', $article['body']);    //البلد السعودية
          
        $article['body'] = $desc = str_replace('Get Al Jaras Updates with the most read and shared stories sent directly to you email <br /><br />', '', $article['body']); 
        
        $article['body'] = $desc = str_replace('font-size:18px', '', $article['body']); 
        $article['body'] = $desc = str_replace('font-size:15px', '', $article['body']); 
        $article['body'] = $desc = str_replace('font-size:14px', '', $article['body']); 
        $article['body'] = $desc = str_replace('font-size:12px', '', $article['body']); 
        $article['body'] = $desc = str_replace('font-size:10px', '', $article['body']);  
        
        $article['body'] = $desc = str_replace('font-family:Arial', '', $article['body']); 
        $article['body'] = $desc = str_replace('font-family:arial', '', $article['body']);
        $article['body'] = $desc = str_replace('th;">', '', $article['body']);
        $article['body'] = $desc = str_replace('th;>', '', $article['body']);
        $article['body'] = $desc = str_replace('bo', '', $article['body']);
        $article['body'] = $desc = str_replace('</div>', '', $article['body']);
        $article['body'] = $desc = str_replace('<div class=pdfprnt-bottom-left></div><div class=addthis_toolbox addthis_default_style addthis:url=http://yanair<br />net/archives/99069 addthis:title=بالفيديو | الحكومة للشعب : هاتوا كوسة بـ 2 <br /><br />', '', $article['body']);
        $article['body'] = delete_all_between("<a", "</a>", $article['body']);
        
        $article['body'] = $desc = str_replace('6em;>', '>', $article['body']);
        $article['body'] = $desc = str_replace('jpg/>', '', $article['body']);
        
        $article['body'] = $desc = str_replace('6>', '', $article['body']);
        $article['body'] = $desc = str_replace('<6', '', $article['body']);   
        
        $article['body'] = $desc = str_replace('الرئيسة المحلية اقليمي ودولي أمن وقضاء مختارات متفرقات منوعات غرائب عجائب تكنولوجيا رياضة مشاهير <br /><br />', '', $article['body']);   
        
        $article['body'] = $desc = str_replace('اشترك بالنشرة البريدية للمدونة لتصلك أخر الاخبار ومقالات الرأي المنشرة علي حصري <br /><br /> <br /><br />', '', $article['body']);
        $article['body'] = $desc = str_replace('اشترك بالنشرة البريدية للمدونة لتصلك أخر الاخبار', '', $article['body']);
        $article['body'] = $desc = str_replace('ومقالات الرأي المنشرة علي حصري <br /><br /> <br /><br />', '', $article['body']);
        $article['body'] = $desc = str_replace('ومقالات الرأي المنشرة علي حصري', '', $article['body']);
        $article['body'] = $desc = str_replace('<br /><br /> <br /><br />', '', $article['body']);
       // $article['body'] = $desc = str_replace('<div class=content>', '', $article['body']);
        
        $article['body'] = $desc = str_replace('error was encountered while trying to use an ErrorDocument to handle the request', '', $article['body']);
        
        $article['body'] = str_replace('To get best possible experiance using our website we recommend that you upgrade to a newer version or other web browser', "", $article['body']);
        $article['body'] = str_replace('To get best possible experiance using our website we recommend that you upgrade to a newer version or other web browser<br />', "", $article['body']);
        $article['body'] = str_replace('A list of the most popular web browsers can be found below', "", $article['body']);
        $article['body'] = str_replace('A list of the most popular web browsers can be found below <br /><br />', "", $article['body']);
        
        $article['body'] = str_replace('To get best possible experiance using our website we recommend that you upgrade to a newer version or other web browser<br /> A list of the most popular web browsers can be found below <br /><br />', "", $article['body']);
        
        $article['body'] = str_replace('كافة الحقوق محفوظة لـ scbnews<br />com &copy; 1436 التصميم بواسطة :ALTALEDI NET Powered by Dimofinf cms Version 3<br />0<br />0Copyright&copy; Dimensions Of Information Inc <br /><br />', "", $article['body']);
        $article['body'] = str_replace('كافة الحقوق محفوظة لـ scbnews.com &copy; 1436 التصميم بواسطة :ALTALEDI NET Powered by Dimofinf cms Version 3.0.0Copyright&copy; Dimensions Of Information Inc', "", $article['body']);
    
        $article['body'] = str_replace('function GoogleLanguageTranslatorInit() { new google', "", $article['body']);
        $article['body'] = str_replace('translate', "", $article['body']);
        
        $article['body'] = str_replace('googletag<br />display(div-gpt-ad-mpu);', "", $article['body']);
        $article['body'] = str_replace('googletag', "", $article['body']);
        $article['body'] = str_replace('display(div-gpt-ad-mpu);', "", $article['body']);
        
        $article['body'] = str_replace('Powered by Dimofinf cms Version 3', "", $article['body']);
        $article['body'] = str_replace('0Copyright© Dimensions Of Information Inc.', "", $article['body']);
        $article['body'] = str_replace('0Copyright© Dimensions Of Information Inc', "", $article['body']);
        $article['body'] = str_replace('0Copyright&copy; Dimensions Of Information Inc', "", $article['body']);
        $article['body'] = str_replace('404', "", $article['body']); 
        
        $article['body'] = str_replace('#8220;', "-", $article['body']); 
        $article['body'] = str_replace('&#8220;', "-", $article['body']); 
        $article['body'] = str_replace('&#13;', " ", $article['body']); 
        $article['body'] = str_replace('&nbsp;', " ", $article['body']); 
        $article['body'] = str_replace('&ndash;', "-", $article['body']); 
        $article['body'] = str_replace('#8221;', "-", $article['body']);
        $article['body'] = str_replace('&#8211;', "-", $article['body']);
        $article['body'] = str_replace('&#8221;', "-", $article['body']);
        $article['body'] = str_replace('amp;#039;', "-", $article['body']);
        $article['body'] = str_replace('#8217;', "-", $article['body']);
        $article['body'] = str_replace('0pt;>', "", $article['body']);
        
        if(strrpos($article['reversed_url'],"alhayat.com") !== FALSE) {
            $new_body = explode("اترك تعليقاً..", $article['body']);
            
            $article['body'] = $new_body[0];
        }
        
        if(strrpos($article['reversed_url'],"zamanarabic.com") === FALSE) {
            $article['body'] = str_replace("\n", "<br /><br />", $article['body']);
            $article['body'] = str_replace('\n', "<br /><br />", $article['body']);
        }
        else{
            $article['body'] = str_replace('.', ".<br /><br />", $article['body']);
        }
        
        
        
        $article['body'] = str_replace('التعليقات تعبر عن وجهة نظر أصحابها فقط.', "", $article['body']);
        $article['body'] = str_replace('التعليقات تعبر عن وجهة نظر أصحابها فقط', "", $article['body']);
        $article['body'] = str_replace('التعليقات تعبر عن وجهة نظر أصحابها فقط. <br /><br />', "", $article['body']);
        
        $article['body'] = str_replace('&#1604;&#1604;&#1578;&#1581;&#1602;&#1602;', "", $article['body']); //للتحقق في مصدر موسوعه الملك عبدالعزيز
         
        $article['title'] = str_replace('#8220;', "-", $article['title']); 
        $article['title'] = str_replace('&#8220;', "-", $article['title']); 
        $article['title'] = str_replace('&#13;', " ", $article['title']); 
        $article['title'] = str_replace('&nbsp;', " ", $article['title']); 
        $article['title'] = str_replace('&ndash;', "-", $article['title']); 
        $article['title'] = str_replace('#8221;', "-", $article['title']); 
        $article['title'] = str_replace('&#8211;', "-", $article['title']); 
        $article['title'] = str_replace('&#8221;', "-", $article['title']); 
        $article['title'] = str_replace('amp;#039;', "-", $article['title']); 
        
        $article['title'] = str_replace('amp;quot;', "-", $article['title']);
        $article['title'] = str_replace('quot;', "-", $article['title']);
        
        $article['body'] = str_replace('<br /><br /> $(<br />more)<br />disableTextSelect(); $(function(){ $(<br />more-selected) <br /><br />', "", $article['body']);
        $article['body'] = str_replace('<br /><br /> محتوى حبر مرخص برخصة المشاع الإبداعي<br /> يسمح بإعادة نشر المواد بشرط الإشارة إلى المصدر بواسطة رابط (hyperlink)، وعدم إجراء تغييرات على النص، وعدم استخدامه لأغراض تجارية <br /><br />', "", $article['body']);
        
        if(strrpos($article['reversed_url'],"hassacom.com") !== FALSE) { 
            $article['body'] = str_replace('0', "", $article['body']);
        } 
        
        if(strrpos($article['reversed_url'],"khaberni") !== FALSE) { 
            //$article['body'] = str_replace('<br /><br />', "<br />", $article['body']);
        }
        
        if(strrpos($article['reversed_url'],"guryatnews.com") !== FALSE) {
          //  $pos = strpos($article['body'], "القريات");      
           // $article['body'] = substr_replace($article['body'], '<br /><br />', ($pos+1), 0);
        }
        
        if(strrpos($article['reversed_url'],"youm7.com") !== FALSE) {
            $article['body'] = str_replace('موضوعات متعلقة', '', $article['body']);  
        }
        
        if(strrpos($article['reversed_url'],"alkoutnews.net") !== FALSE) {
            $article['body'] = str_replace('s=clear>', '', $article['body']);  
        }  
     
        if(strrpos($article['reversed_url'],"alhurra.com") !== FALSE) {  
            $article['body'] = str_replace('<br />  <br /><br />', '', $article['body']);    
            $article['body'] = str_replace('<br />  <br /><br /> ', '', $article['body']);    
        }
        
        if(strrpos($article['reversed_url'],"eurosport.com") !== FALSE) { 
            $article['body'] = str_replace('ستقوم يوروسبورت عربية بنقل الأحداث والمباريات التالية في بث حي ومباشر خلال هذا الأسبوع', '', $article['body']);             
        }
        
        if(strrpos($article['reversed_url'],"almaghribtoday.net") !== FALSE) { 
            $article['body'] = str_replace("1.", '1>', $article['body']);             
        } 
        
        if(strrpos($article['reversed_url'],"alsumaria.tv") !== FALSE) { 
            $article['body'] = str_replace("/", '', $article['body']);             
        }
        
        if(strrpos($article['reversed_url'],"anazahra.com") !== FALSE) { 
            $article['body'] = str_replace("المزيد:", '', $article['body']);             
            $article['body'] = str_replace("للمزيد:", '', $article['body']);             
        }
        
        if(strrpos($article['reversed_url'],"mbc.net") !== FALSE) { 
            $article['body'] = str_replace("روابط ذات صلة", '', $article['body']);                         
        }
        
        if(strrpos($article['reversed_url'],"euronews") !== FALSE) { 
            if ($article['body'] == "<dir=rtl p></p> <br /><br />") {
                $article['body'] = str_replace("<dir=rtl p></p> <br /><br />", '', $article['body']); 
            }                        
        }
        if(strrpos($article['reversed_url'],"tabke.net") !== FALSE) { 
            $article['body'] = '';                         
        }
        
        if(strrpos($article['reversed_url'],"manalonline.com/movie") !== FALSE) { 
            $article['body'] = '';                         
        }
        if(strrpos($article['reversed_url'],"goodykitchen.com/trainee-feedback") !== FALSE) { 
            $article['body'] = '';                         
        }
        if($article['reversed_url'] == "http://onlineacademy.goodykitchen.com/") { 
            $article['body'] = '';                         
        }
        
        
        if(strrpos($article['reversed_url'],"arabic.cnn.com") !== FALSE) { 
            if ($article['body'] == "<br /><br />  <br /><br />") {
                $article['body'] = str_replace("<br /><br />  <br /><br />", '', $article['body']); 
            }
            if ($article['body'] == "<br /><br /><br />") {
                $article['body'] = str_replace("<br /><br /><br />", '', $article['body']); 
            }                        
        }
        
        if(strrpos($article['reversed_url'],"atyabtabkha") !== FALSE) { 
            if ($article['body'] == "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /> <br /><br />") {
                $article['body'] = str_replace("<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /> <br /><br />", '', $article['body']); 
            }
            if ($article['body'] == "<br /><br /><br /><br />") {
                $article['body'] = str_replace("<br /><br /><br /><br />", '', $article['body']); 
            }                        
        }
        
        if(strrpos($article['reversed_url'],"mbc.net") !== FALSE) { 
            if(
                strrpos($article['body'],"قنوات MBC 1 MBC") !== FALSE ||
                strrpos($article['body'],"Were sorry, ") !== FALSE 
            ) { 
                 $article['body'] = '';
            }                          
        }       
        
        if(strrpos($article['reversed_url'],"elkhabar.com") !== FALSE) {  
            if (strrpos($article['image'],"elkhabar.com") === FALSE) {
                $article['image'] = 'http://www.elkhabar.com' . $article['image'];
            }  
        }
        
        if(strrpos($article['reversed_url'],"almesryoon.com") !== FALSE) {  
             $article['body'] = str_replace('ta-share=true>', '', $article['body']); 
             $article['body'] = str_replace('ta-share=true', '', $article['body']); 
        }
        
        if(strrpos($article['reversed_url'],"nas.sa") !== FALSE) {  
             $article['body'] = str_replace('embed>', '', $article['body']); 
        }
        
        if(strrpos($article['reversed_url'],"14march.org") !== FALSE) {  
            if(strrpos($article['body'],"&#1578;&#1587;&#1580;&#1610;&#1604; &#1575;&#1604;&#1583;&#1582;&#1608;&#1604;") !== FALSE) {  
                 $article['body'] = "";
            }    
        }
        
        if(strrpos($article['reversed_url'],"ounousa.com") !== FALSE) {  
            if(strrpos($article['body'],"إختر البلد الأردن الامارات العربية المتحدة البحرين الجزائر السودان العراق الكويت المغرب المملكة العربية السعودية") !== FALSE) {  
                 $article['body'] = "";
            }    
        }
        
        if(strrpos($article['reversed_url'],"tayyar.org") !== FALSE) {  
             $article['body'] = str_replace('A', '', $article['body']);  
             $article['body'] = str_replace('+', '', $article['body']);  
             $article['body'] = str_replace('-', '', $article['body']);  
        }
        
        if(strrpos($article['reversed_url'],"felesteen.ps") !== FALSE) {  
             $article['body'] = str_replace('وكالات', '', $article['body']);  
             $article['body'] = str_replace('هذا الخبر يتحدث عن', '', $article['body']);  
        }
        
        if(strrpos($article['reversed_url'],"alhilal.com") !== FALSE) {  
            if(strrpos($article['body'],"خلاصات RSS") !== FALSE) { 
               $body = explode('خلاصات RSS', $article['body']);  
               $article['body'] = $body[0];
            }
            else{
                $article['body'] = $article['body'];
            }
        }
        
        if(strrpos($article['reversed_url'],"youm7.com") !== FALSE) {  
            if(strrpos($article['body'],"أخبار متعلقة") !== FALSE) { 
               $body = explode('أخبار متعلقة', $article['body']);  
               $article['body'] = $body[0];
            }
            else{
                $article['body'] = $article['body'];
            }
        }
        
        if(strrpos($article['reversed_url'],"ahram.org.eg") !== FALSE) {  
            if(strrpos($article['body'],"رابط دائم") !== FALSE) { 
               $body = explode('رابط دائم', $article['body']);  
               $article['body'] = $body[0];
            }
            else{
                $article['body'] = $article['body'];
            }
        }
        
        if(strrpos($article['reversed_url'],"alwasatnews") !== FALSE) {  
            if(strrpos($article['body'],"صحيفة الوسط البحرينية") !== FALSE) { 
               $body = explode('صحيفة الوسط البحرينية', $article['body']);  
               $article['body'] = $body[0];
            }
            else{
                $article['body'] = $article['body'];
            }
        }
        
        if (strrpos($article['reversed_url'],"albiladpress.com") !== FALSE) {
            $article['reversed_url'] = str_replace("demox/news_inner.php?nid=", "article", $article['reversed_url']);
            $article['reversed_url'] = str_replace("&", "-1.html&", $article['reversed_url']);
            
            $url_ = explode("&", $article['reversed_url']);
            $article['reversed_url'] = $url_[0];
            
            //echo($url); exit;
        }
        
        if(strrpos($article['reversed_url'],'alqabas.com.kw') !== FALSE) { 
            $article['reversed_url'] = str_replace("Article.aspx", "Articles.aspx", $article['reversed_url']);
            $article['reversed_url'] = str_replace("id=", "ArticleID=", $article['reversed_url']);
        }
        
        if(strrpos($article['body'],'We have been receiving a large volume of requests from your network') !== FALSE) { 
            $article['body'] = 'go to fb';
        }
        
        if(strrpos($article['body'],'Your browser will redirect to your requested content shortly') !== FALSE) { 
            $article['body'] = '';
        }
        
        if(strrpos($article['body'],'Completing the CAPTCHA proves you are a human and gives') !== FALSE) { 
            $article['body'] = '';
        }
        
        if(strrpos($article['body'],'حقوق النشر &copy; 2015. كورة عالمية') !== FALSE) { 
            $article['body'] = '';
        }
        
        if(strrpos($article['body'],'(disqus.com)') !== FALSE) { 
            $article['body'] = '';
        }
        
        if(strrpos($article['body'],'CloudFlare Ray ID:') !== FALSE) { 
            $article['body'] = '';
        }
        
        if(strrpos($article['body'],'What can I do to prevent this in the future') !== FALSE) { 
            $article['body'] = '';
        }
        
        if(strrpos($article['reversed_url'],'arabic.euronews.com') !== FALSE) {                     
            if(strrpos($article['body'],'Français') !== FALSE) { 
                $article['body'] = '';
            }
        }
        
        if(strrpos($article['body'],'What can I do to prevent this in the future') !== FALSE) { 
            $article['body'] = '';
        } 
        
        if(strrpos($article['body'],'You are using an older version of Internet Explorer') !== FALSE) { 
            $article['body'] = '';
        }
        
        if(strrpos($article['reversed_url'],'egyptiannews.net') !== FALSE) {                               
            if(strrpos($article['body'],'ال') === FALSE) {  
                $article['body'] = '';
            }
        } 
        
        if(strrpos($article['reversed_url'],'alquds.co.uk') !== FALSE) {                               
            if(strrpos($article['body'],'Send to Email Address Your Name Your Email Address Cancel Post was not sent') !== FALSE) {  
                $article['body'] = '';
            }
        }
        
        if(strrpos($article['reversed_url'],'alalam.ir') !== FALSE) {                               
            if(strrpos($article['body'],'شریط الاخبار') !== FALSE) {  
                $article['body'] = '';
            }
        }
        
        if(strrpos($article['reversed_url'],'hawahome.com') !== FALSE) { 
            $article['body'] = '';
        }
        
        if($article['reversed_url'] == 'http://alkhaleejonline.net/') { 
            $article['body'] = '';
        }
        
        $article['body'] = str_replace('الرئيسية | الصور |  المقالات |  البطاقات | الملفات  | الجوال  |الأخبار |الفيديو |الصوتيات |راسلنا |للأعلى', "", $article['body']);
        $article['body'] = str_replace('الرئيسية | الصور |&nbsp; المقالات |&nbsp; البطاقات |&nbsp;الملفات&nbsp; |&nbsp;الجوال &nbsp;|الأخبار |الفيديو |الصوتيات |راسلنا |للأعلى', "", $article['body']);
        $article['body'] = str_replace('جميع الحقوق محفوظة لصحيفة الخبر تايمز ولا يسمح بالنسخ أو الاقتباس إلا بموافقه خطيه من إدارة الصحيفة', "", $article['body']);
 
        $article['body'] = str_replace('باب.كوم جميع الحقوق محفوظة © 2015 شركة باب العالمية للخدمات المتخصصة – باب حاصلة على ترخيص وزارة الثقافة والإعلام', "", $article['body']); 
        
        $article['body'] = $desc = str_replace('<;;;5', '', $article['body']); 
        $article['body'] = $desc = str_replace('5;;;>', '', $article['body']); 
        $article['body'] = $desc = str_replace('4;;;>', '', $article['body']); 
        $article['body'] = $desc = str_replace('3;;;>', '', $article['body']); 
        $article['body'] = $desc = str_replace('2;;;>', '', $article['body']); 
        $article['body'] = $desc = str_replace('1;;;>', '', $article['body']); 
        $article['body'] = $desc = str_replace('<;;;4', '', $article['body']); 
        $article['body'] = $desc = str_replace('<;;;3', '', $article['body']); 
        $article['body'] = $desc = str_replace('<;;;2', '', $article['body']); 
        $article['body'] = $desc = str_replace('<;;;1', '', $article['body']); 
        
        $article['body'] = $desc = str_replace('شارك هذا الموضوع:', '', $article['body']); 
        $article['body'] = $desc = str_replace('معجب بهذه:', '', $article['body']); 
        $article['body'] = $desc = str_replace('<span class=button><span>إعجاب</span></span> <span class=loading>تحميل...</span>', '', $article['body']); 
        
        //$article['body'] = $desc = str_replace('&quot;', '"', $article['body']); 
        
        if(strrpos($article['reversed_url'],"alkhobartimes.com") === FALSE) {          
           // preg_match('/(.*)\.([^.]*)$/', $article['body'], $matches); //remove last dot
            
           // $article['body'] = str_replace(".", "<br />", @$matches[1]);     //replace all dots with new line
        }
        else{
            $article['body'] = str_replace(".", "<br /><br />", $article['body']);     //replace all dots with new line 
        }
        
        $article['body'] = html_entity_decode($article['body']);
    }
    
    
    $article['reversed_url'] = str_replace(' ', '', $article['reversed_url']);
    $article['reversed_url'] = str_replace('#!/', '', $article['reversed_url']);
    
    $body_container = array('go to fb', 'go to youtube', 'go to islamweb', 'go to vine');
    $body_container2 = array('url not set');
    $body_container3 = array('go to pdf');
    
    $content_type = 0;
    
    if (in_array($article['body'], $body_container)) {
        $content_type = 1;
    }
    else if (in_array($article['body'], $body_container2)) {
        $content_type = 2;
    } 
    else if (in_array($article['body'], $body_container3)) {
        $content_type = 3;
    } 
    
    
    $article['content_type'] = $content_type;
    
    $article['date_added'] = "منذ " . $helper_obj->time_elapsed_string(($article['date_added'])); 
         
    /*if ($api) {
        $json = 'Ext.data.JsonP.callback1({
                "proposals": 
                ';
        $article = $json . json_encode($article) . '})';
    }
    else{ */
        $article = json_encode($article);
    /*} */
                
    return $article;
    
}

//not used - old version
function get_article($aid, $cid, $uid = '', $api = false){
    global $conn;
    
    $query = "select * from articles where id='" . $aid . "' and client_id='" . $cid . "'";       
    $res = $conn->db_query($query);
    
    $log_obj = new Payment();
    
    $payment = $log_obj->get_user_payment($uid);
            
    $payment_end_date = $payment->end_date;
    
    $now = time();
    
    $payment_valid = 1;
             
    if ($payment_end_date < $now){
        $payment_valid = 0;
    }
    
    $article = build_json_array($res);
              
    $article_array = json_decode($article);
    
    $article_array[0]->text1 = nl2br($article_array[0]->text1);
    $article_array[0]->payment_valid = $payment_valid;
             
    $article['payment_valid'] = $payment_valid;
    
    if ($api) {
        $json = 'Ext.data.JsonP.callback1({
                "proposals": 
                ';
        $article = $json . json_encode($article_array) . '})';
    }
    else{ 
        $article = json_encode($article_array);
    }
                
    return $article;
}

//not used - old version
function get_all_articles($cid){
    global $conn;
    
    $query = "select * from articles where client_id='" . $cid . "'";                  
    $res = $conn->db_query($query);
    return build_json_array($res);
}

//get all news
function get_all_html_articles($cid, $start, $offset){       
    global $conn;
    
    $start = ($start*$offset); 
    
    $query = "select *
              from articles_html where client_id='" . $cid . "'" ."' limit $start, $offset";;                  
    $res = $conn->db_query($query);
    
    $helper_obj = new Helper();
    
    $all_articles = array();
            
    //while($row = $conn->db_fetch_array($res)) {  
    while($row = $res->fetch_assoc()){
        $row['date_added'] = "منذ " . $helper_obj->time_elapsed_string(($row['date_added'])); 
        
        $all_articles[] = $row;
    }
    
    return json_encode($all_articles);
}

//not used - old version
function get_articles_by_section($section, $cid, $callback, $start, $offset) {
    global $conn;
    
    $query = "select * from articles where section='$section' and client_id='" . $cid . "' order by id desc limit $start, $offset";   
    $res = $conn->db_query($query);
    
    /*if ($section == 2) {*/
        $json = $callback . '({
                "proposals": 
                ';
                
        $json .= build_json_array($res);
                
        $json .= '})';
        
        return $json;
   /* }
    else{             
        return build_json_array($res);
    } */
}

//get news by section
function get_html_articles_by_section($section, $cid, $start, $offset) {
    global $conn;
    
    $start = ($start*$offset);
    
    $query = "select *, GROUP_CONCAT(a.cat_name SEPARATOR ', ') category_name 
                from (
                select articles_html.id, title, image, client_id, added_by, date_added, views, pg_rated_id, section, 
                updated_date, updated_by, categories.name cat_name, categories.id cat_is
                from articles_html 
                inner join article_categories on article_categories.aid = articles_html.id
                inner join categories on categories.id = article_categories.cid
                where section='$section' and client_id='" . $cid . "' order by id desc) a
                group by a.id order by a.id desc limit $start, $offset";   
  // echo($query);
    $res = $conn->db_query($query);
    
    $helper_obj = new Helper();
    
    $all_articles = array();
    
    $count = $start;
    
    //while($row = $conn->db_fetch_array($res)) { 
    while($row = $res->fetch_assoc()){
        $row['date_added'] = "منذ " . $helper_obj->time_elapsed_string(($row['date_added'])); 
        
        $row['num_comments'] = get_num_of_comments($row['id']);
        
        $row['image'] = str_replace("new.bab.com", "www.bab.com", $row['image']);
        
        /*if($cid == 45){ //tech
            if($count == 0){  
                $row['image'] = 'video_images_feature/'.$row['image'];      
            }
            else{
                $row['image'] = 'video_images_thumbnail/'.$row['image'];     
            }
        }         */
        
        $count++;
        
        $row['count'] = $count;
        
        $all_articles[] = $row;
    }
     //   pr($all_articles);
    /*if ($section == 2) {*/
    /*if ($sencha) {
        $json = $callback . '({
                "proposals": 
                ';
                
        $json .= json_encode($all_articles);
                
        $json .= '})';
    }
    else{    */
        $json = json_encode($all_articles); 
  /*  }  */
        
    return $json;
   /* }
    else{             
        return build_json_array($res);
    } */
}

//get sources
function get_categories($client_id){
     global $conn;
    
    $query = "select categories.id, name, image1, image2, premium 
              from categories
              inner join categories_by_clients on categories_by_clients.category_id = categories.id
              where client_id = '$client_id' and parent <> 0";         
    $result = $conn->db_query($query);
    
    $cat = array();
    
   /* while($row = $conn->db_fetch_array($res)) {
        $cat[] = $row;
    }   */
    
    while($row = $result->fetch_assoc()){
        $cat[] = $row;
    }

    $result->free();
               
   /* if ($sencha) {
        header('Content-type: application/json');   
        $json = 'Ext.data.JsonP.callback2({
                "proposals": 
                ';
                
        return $json . json_encode($cat) . '})';
    }
    else{  */
        return json_encode($cat);   
   /* }   */
}

//get sources parent
function get_parent_categories($client_id){
     global $conn;
    
    $query = "select categories.id, name, image1, image2, premium 
              from categories
              inner join categories_by_clients on categories_by_clients.category_id = categories.id
              where client_id = '$client_id' and parent = 0 order by sort";         
    $res = $conn->db_query($query);
    
    $cat = array();
    
    //while($row = $conn->db_fetch_array($res)) {
    while($row = $res->fetch_assoc()){  
       // $object_image_url1 = '';
        
        //if ($row["image1"] != '') { 
        //    $object_image_file1 = 'gs://' . GOOGLE_APP_ID . '/cat/' . $row['image1'].'';
            //echo($object_image_file1.'<br />');
         //   $object_image_url1 = CloudStorageTools::getImageServingUrl($object_image_file1, ['size' => 200, 'crop' => false]);
       // }
        
       // $row['image1'] = $object_image_url1;                    
        
        $cat[] = $row;
    }
               
    /*if ($sencha) {
        header('Content-type: application/json');   
        $json = 'Ext.data.JsonP.callback2({
                "proposals": 
                ';
                
        return $json . json_encode($cat) . '})';
    }
    else{   */
        return json_encode($cat);   
  /*  }  */
}

//get sources by parent
function get_categories_by_parent($client_id, $parent, $udid){
     global $conn;
     
     $user_obj = new User();
     
     $uid = $user_obj->get_uid_by_udid($udid);
    
    /*$query = "select categories.id, name, image1, image2, premium 
              from categories
              inner join categories_by_clients on categories_by_clients.category_id = categories.id
              where client_id = '$client_id' and parent = '$parent'";    */
              
   /* $query = "select c1.id id1 , c1.name name1 , GROUP_CONCAT(distinct concat(c2.id) SEPARATOR ',') id2 , c2.name name2, c2.image1 image, c1.group,
                     case (select 1 from follow_sources fs where fs.cid = c2.id and uid = '$uid')
                     when 1 then 1 else 0 end as 'selected', count(fs.cid) followers
                from categories c1
                inner join categories_by_clients on categories_by_clients.category_id = c1.id
                inner join categories c2 on c1.id = c2.parent
                left join follow_sources fs on fs.cid = c2.id  
                where client_id = '$client_id' and c1.parent = '$parent' 
                group by c2.group 
                order by c2.sort";   */
                
   $query = "select c1.id id1 , c1.name name1 , GROUP_CONCAT(distinct concat(c2.id) SEPARATOR ',') id2, c2.sort sort2,
                c2.name name2, c2.image1 image, c1.group,
                case (select 1 from follow_sources fs where fs.cid in (select id from categories where categories.group = c2.group) and uid = '$uid')
                when 1 then 1 else 0 end as 'selected', count(fs.cid) followers
                from categories c1
                inner join categories_by_clients on categories_by_clients.category_id = c1.id
                inner join categories c2 on c1.id = c2.parent
                left join follow_sources fs on fs.cid = c2.id  
                where client_id = '$client_id' and c1.parent = '$parent' 
                group by c2.group 
                order by c1.sort, c2.sort";
               //      echo($query);
    $res = $conn->db_query($query);
    
    $cat = array();
    
    //while($row = $conn->db_fetch_array($res)) {
    while($row = $conn->fetch_assoc($res)){
      //  $object_image_url1 = '';
        
       // if ($row["image"] != '') { 
           // $object_image_file1 = 'gs://' . GOOGLE_APP_ID . '/cat/' . $row['image'].'';
          //  echo($object_image_file1."\n");
            //$object_image_url1 = CloudStorageTools::getImageServingUrl($object_image_file1, ['size' => 200, 'crop' => false]);
       // }
        
       // $row['image'] = $object_image_url1; 
       
        //$sort[] = $row['sort2'];
        //$sources[] = $row['id2'] . '_' . $row['name2'] . "_" . $row['image'] . '_' . $row['selected'] . '_' . $row['followers'];
        
        //array_multisort($sources, SORT_DESC, SORT_NUMERIC, $sort, SORT_NUMERIC, SORT_DESC);
        
        $cat[$row['id1'] . '_' . $row['name1']][] = $row['id2'] . '_' . $row['name2'] . "_" . $row['image'] . '_' . $row['selected'] . '_' . $row['followers']; 
    }  
           
    return json_encode($cat);   
}

//get source flollowers
function get_source_followers($client_id, $parent){
     global $conn;
     
     $user_obj = new User();
         
     $query = "select c2.id id2, count(fs.cid) followers
                from categories c1
                inner join categories_by_clients on categories_by_clients.category_id = c1.id
                inner join categories c2 on c1.id = c2.parent
                left join follow_sources fs on fs.cid = c2.id  
                where client_id = '$client_id' and c1.parent = '$parent' 
                group by c2.id
                order by c1.id";   
                   
    $res = $conn->db_query($query);
    
    $cat = array();
   
    while($row = $res->fetch_assoc()){
         $cat[$row['id2']][] = $row['followers']; 
    }
  
    return json_encode($cat);   
}

//not used - old version
function get_source_followers_by_source_id($client_id, $source_ids){
     global $conn;
     
     $user_obj = new User();
         
     $query = "select c2.id id2, count(fs.cid) followers
                from categories c1
                inner join categories_by_clients on categories_by_clients.category_id = c1.id
                inner join categories c2 on c1.id = c2.parent
                left join follow_sources fs on fs.cid = c2.id  
                where client_id = '$client_id' and c2.id in ($source_ids) 
                group by c2.id
                order by c1.id";   
                   
    $res = $conn->db_query($query);
    
    $cat = array();
   
    while($row = $res->fetch_assoc()){
         $cat[$row['id2']][] = $row['followers']; 
    }
  
    return json_encode($cat);   
}

//not used - old version
function get_articles_by_cat($cid, $client_id, $sencha = 0){
     global $conn;
    
    $query = "select * from articles where category_id = '$cid' and client_id = '$client_id' order by id desc";            
    $res = $conn->db_query($query);
    
    $json = '';
    
    if ($sencha) {
        $json .= 'Ext.data.JsonP.callback1({
                "proposals": 
                ';
    }
    
    $json .= build_json_array($res); 
    
    if ($sencha) {
        $json .= '})';
    } 
    
    return $json;
} 

//get news by source
function get_html_articles_by_cat($cid, $client_id, $start, $offset){
     global $conn;
     
     $start = ($start*$offset);
     
     //mdiet - طعام وطبخ
     if ($cid == 1063) {
         //convert it to health
         $cid = 1062;
     }
     
     //bab for saudi
     if ($cid == 1461) {
         $cid = 883;
     }
     
     $query = "select c2.parent, articles_html.id, articles_html.body, title, image, client_id, added_by, reversed_url, 
                          date_added, cid, views, pg_rated_id, section, updated_date, updated_by
                          from articles_html 
                          inner join article_categories on article_categories.aid = articles_html.id
                          inner join categories c1 on c1.id = article_categories.cid
                          inner join categories c2 on c2.id = c1.parent
                          where (articles_html.body not like '%error was encountered while trying to use an ErrorDocument to handle the request%' or articles_html.body not like '%Somethings wrong%')
                                and cid in ($cid) and client_id = '$client_id' 
                          group by title
                          order by date_added desc    
                          limit $start, $offset";            
    $res = $conn->db_query($query);
    
    $all_articles = array();
    
    $helper_obj = new Helper();
    
    $count = $start;
    
    //while($row = $conn->db_fetch_array($res)) {
    while($row = $res->fetch_assoc()){
        $row['date_added'] = "منذ " . $helper_obj->time_elapsed_string(($row['date_added']));
        
        $row['image'] = trim($row['image']);//سودانيز اونلاين  
        
        $row['image'] = str_replace("new.bab.com", "www.bab.com", $row['image']);
        $row['image'] = str_replace("makkahonline.com.sa", "www.makkahnewspaper.com", @$row['image']);
        $row['image'] = str_replace("sites/default/files/images", "sites/default/files/styles/optimized_original/public", @$row['image']);
        
        $row['image'] = str_replace("http://alkhabarkw.com/themes/dv_blue_rtl/img/alkhabar.com-header.png", "https://pbs.twimg.com/profile_images/378800000347131805/59d5b67fbe09f6518eed85e772fd4665_400x400.jpeg", @$row['image']);
        $row['image'] = str_replace("http://ad.doubleclick.net/N7524/ad/Akhbarak.net/Inner;tile=2;sz=300x250;ord=[timestamp]?", "http://www.akhbarak.net/assets/v2/defaultOG.png", @$row['image']);
                  
        $row['title'] = str_replace('#8220;', "-", $row['title']); 
        $row['title'] = str_replace('&#8220;', "-", $row['title']); 
        $row['title'] = str_replace('&#13;', " ", $row['title']); 
        $row['title'] = str_replace('&nbsp;', " ", $row['title']); 
        $row['title'] = str_replace('&ndash;', "-", $row['title']); 
        $row['title'] = str_replace('#8221;', "-", $row['title']);
        $row['title'] = str_replace('&#8211;', "-", $row['title']);
        $row['title'] = str_replace('&#8221;', "-", $row['title']);
        $row['title'] = str_replace('amp;#039;', "-", $row['title']);
        
        $row['title'] = str_replace('amp;quot;', "-", $row['title']);
        $row['title'] = str_replace('quot;', "-", $row['title']);
        
        $row['body'] = str_replace('#8220;', "-", $row['body']); 
        $row['body'] = str_replace('&#8220;', "-", $row['body']); 
        $row['body'] = str_replace('&#13;', " ", $row['body']); 
        $row['body'] = str_replace('&nbsp;', " ", $row['body']); 
        $row['body'] = str_replace('&ndash;', "-", $row['body']); 
        $row['body'] = str_replace('#8221;', "-", $row['body']);
        $row['body'] = str_replace('&#8221;', "-", $row['body']);
        $row['body'] = str_replace('&#8211;', "-", $row['body']);
        $row['body'] = str_replace('amp;#039;', "-", $row['body']);
        
        $row['body'] = str_replace('amp;quot;', "-", $row['body']);
        $row['body'] = str_replace('quot;', "-", $row['body']);
        
        if(strrpos($row['reversed_url'],'alquds.co.uk') !== FALSE) {                               
            if(strrpos($row['body'],'Send to Email Address Your Name Your Email Address Cancel Post was not sent') !== FALSE) {   
                $row['body'] = '';
            }
        }
        
        $sd = delete_all_between("<style", "</style>", $row['body']);
        $row['body'] = trim(mb_substr(strip_tags($sd), 0, 85, 'UTF-8'));
                     
        if(strrpos($row['reversed_url'],"elkhabar.com") !== FALSE) {  
            if (strrpos($row['image'],"elkhabar.com") === FALSE && strrpos($row['image'],"placehold") === FALSE) {
                $row['image'] = 'http://www.elkhabar.com' . $row['image'];
            }  
        }
              
        if(strrpos($row['reversed_url'],"kuna.net.kw") !== FALSE) {  
            if (strrpos($row['image'],"NewsPictures") !== FALSE) {
                $row['image'] = $row['image'];
            }  
            else{
                $row['image'] = '';
            }
        }
        
        if(strrpos($row['reversed_url'],"ittinews.net") !== FALSE) {  
            if (strrpos($row['image'],"msahaads.jpg") === FALSE) {
                $row['image'] = $row['image'];
            }  
            else{
                $row['image'] = '';
            }
        }
        
        if(strrpos($row['reversed_url'],"25feb.net") !== FALSE) {  
            $row['image'] = str_replace("XS", "M", $row['image']);         
        }
        
        if(strrpos($row['reversed_url'],"mugtama.com") !== FALSE) {  
            $row['image'] = str_replace("_XS", "_L", $row['image']);         
        }
        
        if(strrpos($row['reversed_url'],"14march.org") !== FALSE) {  
            if(strrpos($row['image'],"captop1.png") !== FALSE) {  
                 $row['image'] = "http://api.ainnewsapp.com/api/api/images/march14logo.png";
            }     
        }
        
        if(strrpos($row['reversed_url'],"oleeh") !== FALSE) {  
            $row['image'] = str_replace("FilesNews", 'Files/News/', $row['image']);         
        }
        
        if(strrpos($row['reversed_url'],"reyada.akhbarelyom.com") !== FALSE) {  
            $row['image'] = str_replace("new", 'reyada', $row['image']);         
        }
        
        if(strrpos($row['reversed_url'],"almasryalyoum") !== FALSE) {  
            $row['image'] = str_replace("VeryLarge", 'Large', $row['image']);         
        }
        
        if(strrpos($row['reversed_url'],"alamalmal.net") !== FALSE) {  
            $row['image'] = str_replace("images", 'news/images', $row['image']);         
        } 
        
        if(strrpos($row['reversed_url'],'alqabas.com.kw') !== FALSE) { 
            if ($row['image'] == "") {
                $row['image'] = 'http://www.alqabas.com.kw/Images/logo.png';
            }
        }
        
        if(strrpos($row['reversed_url'],'akhbar-alkhaleej.com') !== FALSE) { 
            if ($row['image'] == "") {
                $row['image'] = 'http://www.akhbar-alkhaleej.com/images/logo.jpg';
            }
        }
        
        if(strrpos($row['reversed_url'],'almotamar.net') !== FALSE) { 
            $row['image'] = str_replace("http://", 'http://www.almotamar.net', $row['image']);
        }  
        
        $row['title'] = trim(mb_substr($row['title'], 0, 140, 'UTF-8'));
          
        $count++;
        
        $row['count'] = $count;
        
        $all_articles[] = $row;
    }
    
    $json = '';
   
    $json .= json_encode($all_articles); 
    
    return $json;
}

//get last 24 news by parent category
function get_latest_news_by_categories($sources, $client_id){
     global $conn;
     
     $sources = explode("xxx", $sources);
     
     $all_articles = array();
     
     foreach($sources as $source) {
         $query = "select c2.parent, c3.name, articles_html.id, title, image, client_id, added_by, 
                              date_added, cid, views, pg_rated_id, section, updated_date, updated_by
                              from articles_html 
                              inner join article_categories on article_categories.aid = articles_html.id
                              inner join categories c1 on c1.id = article_categories.cid
                              inner join categories c2 on c2.id = c1.parent
                              inner join categories c3 on c3.id = c2.parent
                              where cid in ($source) and client_id = '$client_id' limit 7";   
                          //  echo($query . '<br />');         
        $res = $conn->db_query($query);
                      
        $helper_obj = new Helper();

        while($row = $res->fetch_assoc()){
            $row['date_added'] = "منذ " . $helper_obj->time_elapsed_string(($row['date_added']));
            
            $row['title'] = str_replace('#8220;', "-", $row['title']); 
            $row['title'] = str_replace('&#8220;', "-", $row['title']); 
            $row['title'] = str_replace('&#13;', " ", $row['title']); 
            $row['title'] = str_replace('&nbsp;', " ", $row['title']); 
            $row['title'] = str_replace('&ndash;', "-", $row['title']); 
            $row['title'] = str_replace('#8221;', "-", $row['title']);
            $row['title'] = str_replace('&#8211;', "-", $row['title']);
            $row['title'] = str_replace('&#8221;', "-", $row['title']);
            $row['title'] = str_replace('amp;#039;', "-", $row['title']);
            $row['title'] = str_replace('#8217;', "-", $row['title']); 
            
            $row['title'] = str_replace('amp;quot;', "-", $row['title']);
            $row['title'] = str_replace('quot;', "-", $row['title']);
            
            $all_articles[$row['parent'] . "_" . $row['name']][] = $row;     
        }
    }
       
    $json = json_encode($all_articles); 
    
    return $json;
}

//get recent read news
function get_recent_read($article_ids, $client_id){
     global $conn;
     
     $query = "select c2.parent, articles_html.id, articles_html.body, title, image, client_id, added_by, 
                          date_added, cid, views, pg_rated_id, section, updated_date, updated_by
                          from articles_html 
                          inner join article_categories on article_categories.aid = articles_html.id
                          inner join categories c1 on c1.id = article_categories.cid
                          inner join categories c2 on c2.id = c1.parent
                          where articles_html.id in ($article_ids) and client_id = '$client_id'";            
    $res = $conn->db_query($query);
    
    $all_articles = array();
    
    $helper_obj = new Helper();

    while($row = $res->fetch_assoc()){
        $row['date_added'] = "منذ " . $helper_obj->time_elapsed_string(($row['date_added']));
        
        $sd = delete_all_between("<style", "</style>", $row['body']);
        $row['body'] = trim(mb_substr(strip_tags($sd), 0, 85, 'UTF-8'));
        
        $row['title'] = trim(mb_substr($row['title'], 0, 140, 'UTF-8'));
          
        $all_articles[] = $row;
    }
    
    $json = '';
   
    $json .= json_encode($all_articles); 
    
    return $json;
}

//get last 24 news
function get_latest_news($sources, $client_id, $start, $offset){
     global $conn;
     
     $start = ($start*$offset);
             
     $last_day = time() - (24*60*60); //last 24 hours
     
     $query = "select c2.parent, articles_html.id, articles_html.body, title, image, client_id, added_by, date_added, cid, views, pg_rated_id, section, updated_date, updated_by, reversed_url
              from articles_html 
              inner join article_categories on article_categories.aid = articles_html.id
              inner join categories c1 on c1.id = article_categories.cid
              inner join categories c2 on c2.id = c1.parent
              where date_added >= '$last_day' and cid in ($sources) and client_id = '$client_id' 
              group by title
              order by id desc limit $start, $offset";       
              // echo($query);    
    $res = $conn->db_query($query);
    
    $all_articles = array();
    
    $helper_obj = new Helper();
    
    $count = $start;

    while($row = $res->fetch_assoc()){
        $row['date_added'] = "منذ " . $helper_obj->time_elapsed_string(($row['date_added']));
        
        $count++;
        
        $row['count'] = $count;
        
        $row['body'] = str_replace('#8220;', "-", $row['body']); 
        $row['body'] = str_replace('&#8220;', "-", $row['body']); 
        $row['body'] = str_replace('&#13;', " ", $row['body']); 
        $row['body'] = str_replace('&nbsp;', " ", $row['body']); 
        $row['body'] = str_replace('&ndash;', "-", $row['body']); 
        $row['body'] = str_replace('#8221;', "-", $row['body']);
        $row['body'] = str_replace('&#8211;', "-", $row['body']);
        $row['body'] = str_replace('&#8221;', "-", $row['body']);
        $row['body'] = str_replace('amp;#039;', "-", $row['body']);
        $row['body'] = str_replace('#8217;', "-", $row['body']);
        
        $sd = delete_all_between("<style", "</style>", $row['body']);
        $row['body'] = trim(mb_substr(strip_tags($sd), 0, 85, 'UTF-8'));  
        
        $row['title'] = str_replace('#8220;', "-", $row['title']); 
        $row['title'] = str_replace('&#8220;', "-", $row['title']); 
        $row['title'] = str_replace('&#13;', " ", $row['title']); 
        $row['title'] = str_replace('&nbsp;', " ", $row['title']); 
        $row['title'] = str_replace('&ndash;', "-", $row['title']); 
        $row['title'] = str_replace('#8221;', "-", $row['title']);
        $row['title'] = str_replace('&#8211;', "-", $row['title']);
        $row['title'] = str_replace('&#8221;', "-", $row['title']);
        $row['title'] = str_replace('amp;#039;', "-", $row['title']);
        $row['title'] = str_replace('#8217;', "-", $row['title']);  
        
        $row['title'] = str_replace('amp;quot;', "-", $row['title']);
        $row['title'] = str_replace('quot;', "-", $row['title']);
        
        $row['title'] = trim(mb_substr($row['title'], 0, 140, 'UTF-8'));
        
        if(strrpos($row['reversed_url'],"14march.org") !== FALSE) {  
            if(strrpos($row['image'],"captop1.png") !== FALSE) {  
                 $row['image'] = "http://api.ainnewsapp.com/api/api/images/march14logo.png";
            }     
        }
        
        $all_articles[] = $row;
    }
    
    $json = '';
    
    $json .= json_encode($all_articles); 

    return $json;
}

//get random news not from my sources
function get_random_news($client_id, $my_sources, $start, $offset){
     global $conn;
     
     $start = ($start*$offset);
             
     $last_day = time() - (24*60*60); //last 24 hours
     
     $query = "select c2.parent, articles_html.id, articles_html.body, title, image, client_id, added_by, date_added, cid, views, pg_rated_id, section, updated_date, updated_by
              from articles_html 
              inner join article_categories on article_categories.aid = articles_html.id
              inner join categories c1 on c1.id = article_categories.cid
              inner join categories c2 on c2.id = c1.parent
              where cid NOT IN ($my_sources) and date_added >= '$last_day' and client_id = '$client_id' order by rand() desc limit $start, $offset";       
              // echo($query);    
    $res = $conn->db_query($query);
    
    $all_articles = array();
    
    $helper_obj = new Helper();
    
    $count = $start;

    while($row = $res->fetch_assoc()){
        $row['date_added'] = "منذ " . $helper_obj->time_elapsed_string(($row['date_added']));
        
        $count++;
        
        $row['count'] = $count;
        
        $sd = delete_all_between("<style", "</style>", $row['body']);
        $row['body'] = trim(mb_substr(strip_tags($sd), 0, 85, 'UTF-8'));
        
        $row['title'] = trim(mb_substr($row['title'], 0, 140, 'UTF-8'));
        
        $all_articles[] = $row;
    }
    
    $json = '';
    
    $json .= json_encode($all_articles); 

    return $json;
}

//get breaking news
function get_break_news($sources, $client_id, $start, $offset){
     global $conn;
     
     $start = ($start*$offset);
    
     $query = "select c2.parent, articles_html.id, articles_html.body, title, image, client_id, added_by, date_added, cid, views, pg_rated_id, section, updated_date, updated_by
              from articles_html 
              inner join article_categories on article_categories.aid = articles_html.id
              inner join categories c1 on c1.id = article_categories.cid
              inner join categories c2 on c2.id = c1.parent
              where (title like ('%عاجـــل%') or title like ('%عاجل%') or title like ('%عـاجل%') or title like ('%عــاجل%') or title like ('%عاجـل%') or title like ('%عاجــل%') 
              or title like ('%عـــاجل%')) and cid in ($sources) and client_id = '$client_id' order by id desc limit $start, $offset";
              
     if ($sources == "") {
         $query = "select c2.parent, articles_html.id, articles_html.body, title, image, client_id, added_by, date_added, cid, views, pg_rated_id, section, updated_date, updated_by
                  from articles_html 
                  inner join article_categories on article_categories.aid = articles_html.id
                  inner join categories c1 on c1.id = article_categories.cid
                  inner join categories c2 on c2.id = c1.parent
                  where (title like ('%عاجـــل%') or title like ('%عاجل%') or title like ('%عـاجل%') or title like ('%عــاجل%') or title like ('%عاجـل%') or title like ('%عاجــل%') 
                    or title like ('%عـــاجل%')) and client_id = '$client_id' order by id desc limit $start, $offset"; 
     }       
            
             //  echo($query);    
    $res = $conn->db_query($query);
    
    $all_articles = array();
    
    $helper_obj = new Helper();
    
    $count = $start;

    while($row = $res->fetch_assoc()){
        $row['date_added'] = "منذ " . $helper_obj->time_elapsed_string(($row['date_added']));
        
        $sd = delete_all_between("<style", "</style>", $row['body']);
        $row['body'] = trim(mb_substr(strip_tags($sd), 0, 85, 'UTF-8'));
        
        $row['title'] = trim(mb_substr($row['title'], 0, 140, 'UTF-8'));
        
        $count++;
        
        $row['count'] = $count;
        
        $all_articles[] = $row;
    }
    
    $json = '';
    
    $json .= json_encode($all_articles); 

    return $json;
}

//not used - old version
function get_operator_details($id) {
    global $conn; 
    
    $query = "select * from operators where id = '$id'";
    $res = $conn->db_query($query);;
     
    //while($row = $conn->db_fetch_array($res)) {
    while($row = $res->fetch_assoc()){
        $data[] = $row;
    }
    
    return $data;
}

//not used - old version
function check_if_user_exists_in_verfication_table($phone, $email, $cid){
    $query = "select count(users.id) c 
              from users 
              inner join users_roles on users_roles.uid = users.id
              where phone = '$phone' and email = '$email' and users_roles.cid = '$cid'";
    //$query = "select count(id) c from verification_code where phone = '$phone' and email = '$email'";
          //echo($query);
    global $conn;
    
    $res = $conn->db_query($query);
    
    $c = 0;
     
    //while($row = $conn->db_fetch_array($res)) {
    while($row = $res->fetch_assoc()){
        $c = $row['c'];
    }

    return $c; 
}

//not used - old version
function get_verification_code($uid) {
    $query = "select * from payments where uid = '$uid'";
    global $conn;    
           // echo($query.'<br />');
    $res = $conn->db_query($query);
    
    $data = array();
     
    //while($row = $conn->db_fetch_array($res)) {
    while($row = $res->fetch_assoc()){
        $data[] = $row;
    }

    return $data;
}

//not used - old version
function add_ver_code($phone, $country_id, $operator_id, $email, $client) { 
    global $conn; 
        
    /*$query = "insert into verification_code (phone, country_id, operator_id, email, code)
              value
              ('$phone','$country_id','$operator_id','$email','$code')"; */
    
    $now = time();
    
    $query = "insert into users (phone, country_id, operator_id, email, date_added)
              value
              ('$phone','$country_id','$operator_id','$email', '$now')";
                //   echo($query.'<br />');     
    $conn->db_query($query);
    
    $uid = $conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning
    
    $role_query = "INSERT INTO users_roles (uid, rid, cid) value ('$uid', '3', '$client')";     //echo($role_query.'<br />');
    $result =  $conn->db_query($role_query);
        
    return $uid;     
}

//not used - old version
function generate_ver_code($phone, $country_id, $operator_id, $email, $cid, $callback = "") {
  $code = rand(999, 9999);
  
  $ope_details = get_operator_details($operator_id);
  
  //$period = $ope_details[0]['shortcode_period'] * 24 * 60 * 60;     
  
  $exists = check_if_user_exists_in_verfication_table($phone, $email, $cid);
  $db_functions_obj = new DbFunctions();  
  
   $root = "http://www.jeelplus.com/appstreamig/streaming/api/";
   //$root = "http://192.168.0.54:8080/appstreamig/streaming/api/";
   $dlr_url = $root . 'index.php?action=dlr' . ('&par=%d_');
                
  //new user
  if (!$exists) {        
      $uid = add_ver_code($phone, $country_id, $operator_id, $email, $cid);
           
      $log_obj = new Payment();
               
      $log_id = $log_obj->insert($uid, $code, 0, 0); 
           
      send_sms($ope_details[0]['free_shortcode'], $phone, ("كود التفعيل هو: " . $code), $ope_details[0]['free_smsc'], $dlr_url, $ope_details[0]['port']); 
      
      $response =  '{"success":"1","message":"success"}';  
      
      $json = $callback . '({
                        "proposals": 
                    ';
      $json .= $response;
                        
      $json .= '})'; 
      
      return $json;
  }
  else{
       $uid = $db_functions_obj->get_uid_by_phone($phone, $cid);
      
       $data = get_verification_code($uid);
       
       send_sms($ope_details[0]['free_shortcode'], $phone, ("كود التفعيل هو: " . $data[0]['code']), $ope_details[0]['free_smsc'], $dlr_url, $ope_details[0]['port']); 
       //pr($data);
       //$response = '{"success":"0","message":"failed", "code":"' . $data[0]['code'] . '"}';    
       $response =  '{"success":"1","message":"success"}';   
       
       $json = $callback . '({
                        "proposals": 
                    ';
       $json .= $response;
                        
       $json .= '})'; 
      
       return $json;
  } 
}

//not used - old version
function get_premium_categories($cat_id){
    global $conn; 
    
    $query = "select id from categories where parent in (select id from categories
              where parent = '$cat_id') and premium = 1";
                //     echo($query);
    $res = $conn->db_query($query);
   
    $data = array();

    while($row = $res->fetch_assoc()){
        $data[] = $row;
    }

    return $data;    
}

//follow user to source
function follow_user_source($uid, $source_id){
    global $conn; 
    $query = "insert into follow_sources (cid, uid) values ('$source_id', '$uid')";  //echo($query);
    $res = $conn->db_query($query);
}

//remove follow source
function delete_follow_source($uid, $source_id){
    global $conn; 
    $query = "delete from follow_sources where cid = '$source_id' and uid = '$uid'";
    $res = $conn->db_query($query);
}

//check if user follow source or not
function check_if_user_follow_source($uid, $source_id){
    global $conn; 
    $query = "select count(id) from follow_sources where uid = '$uid' and cid = '$source_id'";
    $res = $conn->db_query($query);
  
    $row = $res->fetch_assoc();

    return $row;
}

//register user  
function register_user() {
    $user_obj = new User();
    $db_functions_obj = new DbFunctions();
        
    $uid = "";
              
    $uid = $user_obj->register_user();
   
    if ($uid && $uid != '-1') {     
        $categories_arr = explode(",", $_REQUEST['categories']); 
        
        $categories_ids = '';
        
        foreach($categories_arr as $cat) {
            $prem_cats = get_premium_categories($cat);
                    
            foreach($prem_cats as $prem){
                follow_user_source($uid, $prem['id']); 
                
                $categories_ids .= $prem['id'] . ",";
            }
        }
        
        $categories_ids = substr($categories_ids, 0, strlen($categories_ids)-1);
        
        $log = ('{"success":"1","message":"success", "uid": "' . $uid . '",' . '"my_sources":"' . $categories_ids . '"}');
                     
       // return ('{"success":"1","message":"success", "uid": "' . $uid . '",' . '"my_sources":"' . $categories_ids . '"}'); 
    }
    else if ($uid == '-1'){
        $user_obj = new User();
        
        $exists_uid = $user_obj->get_uid_by_udid($_REQUEST['udid']);
        $my_sources = get_my_sources($exists_uid);
        
        $log = ('{"success":"0","message":"user already exists",' . '"uid":"' . $exists_uid . '",' . '"my_sources":"' . $my_sources . '"}');
        
       // return ('{"success":"0","message":"user already exists",' . '"uid":"' . $exists_uid . '",' . '"my_sources":"' . $my_sources . '"}');
    }
    else{
        $log = ('{"success":"0","message":"failed"}');
        
       // return ('{"success":"0","message":"failed"}'); 
    } 
    
    $fp = fopen("registration_log.txt", "a+");
    fwrite($fp, $_REQUEST['device_id'] . ' -----> ' . $_REQUEST['udid'] . ' -----> ' . $log . "\n");
    fclose($fp);
    
    return $log;   
}

//not used - old version
function update_device_id($uid, $device_id) {
     global $conn;
    
    $query = "update users set device_id = '$device_id' where id = '$uid'";
    
    $res = $conn->db_query($query);
    
    return ('{"success":"1","message":"success"}');
}

//get my selected sources
function get_my_sources($uid){
    global $conn;
    
    $query = "select cid from follow_sources where uid = '$uid'";
    
    $res = $conn->db_query($query);
   
    $sources = '';
    
    while($row = $res->fetch_assoc()){
        $sources .= $row['cid'] . ",";
    }                              

    return $sources;
}

//follow/unfollow sources
function follow_source($udid, $source_id){
    $user_obj = new User();
    $uid = $user_obj->get_uid_by_udid($udid);
                       //echo($source_id);
    $sources_id = explode(",", $source_id);
                  //   print_r($sources_id);
    foreach($sources_id as $id) {  
        $user_follow_source = check_if_user_follow_source($uid, $id);
                 //pr($user_follow_source['count(id)']);
        if (!$user_follow_source['count(id)']) {
            follow_user_source($uid, $id); 
        }
        else{
            delete_follow_source($uid, $id);
        }
    }
     
}

//not used - old version
function dlr($uid, $del, $op_id, $callback){
    // 0: not delivered  // 1:accepted  // 2: faild
    // 1: delivery success      // 2: delivery failure  // 4: message buffered  // 8: smsc submit  // 16: smsc reject  // 32: Kannek reject

    $log_obj = new Payment();

    $log_obj->update_log_del_status($uid, $del);
    
    $sucess_del_array = array(1);
    
    //jawwal
    if ($op_id == 1) {
        $sucess_del_array = array(1);
    }
    
    $fp = fopen("dlrs.txt", "a+");
    fwrite($fp, $del . "\n");
    fclose($fp); 
    
    if (in_array($del, $sucess_del_array)){
        //$user_obj = new User();
        $db_funtions_obj = new DbFunctions();
        $helper_obj = new Helper();  
        
        $payment_log_details = $db_funtions_obj->get_payment_log_by_uid($uid);
        
        $db_funtions_obj->activate_user($uid);
        
        $ope_details = $db_funtions_obj->get_operator_by_id($payment_log_details['operator_id']);
       
        $period = $ope_details['period'];
       
        $now = time();
        $end = $now + $period;
               // pr($payment_log_details);  
        $country_id = $ope_details['country_id'];
        $phone = $payment_log_details['phone'];     
        $email = $payment_log_details['email'];     
               
        $db_funtions_obj->update_payment($uid, $now, $end);
        
        $response = ('{"success":"1","message":"success"}'); 
        
        $json = $callback . '({
                        "proposals": 
                    ';
        $json .= $response;
                            
        $json .= '})'; 
          
        return $json;
                
        /*$log_id = $log_obj->insert($uid, $payment_log_details['country_id'], $payment_log_details['client_id'], $payment_log_details['operator_id'], 
                         "لقد تم تفعيل اشتراكك بنجاح", $op_details['free_shortcode'], $payment_log_details['mobile'], 0);
                       */
        //$root = "http://arh:8080/appstreamig/streaming/";
        //$dlr_url = $root . 'index.php?action=dlr&par=%d_' . $log_id; 
        
       // $helper_obj->send_sms($op_details['free_shortcode'], $payment_log_details['mobile'], "لقد تم تفعيل اشتراكك بنجاح", 
                     //         $payment_log_details['free_smsc'], $dlr_url, $op_details['port']);
    }

}

//not used - old version
function verify_code_success($uid) {
    global $conn;  
    $query = "update payments set status = 1 where uid = '$uid'";
    //echo($query);
    $conn->db_query($query); 
}

//not used - old version
function verify_code($phone, $code, $callback = "", $cid = ""){
    $db_fuctions_obj = new DbFunctions();
    
    $uid = $db_fuctions_obj->get_uid_by_phone($phone, $cid);
    
    $valid = $db_fuctions_obj->verify_code($uid, $code);
    
    $phone_details = $db_fuctions_obj->get_phone_details($phone, $cid);
              //    pr($phone_details);
    if ($valid) {
        $ope_details = $db_fuctions_obj->get_operator_details($phone_details[0]['operator_id']);
                        
        verify_code_success($uid);
                     
        //$root = "http://arh:8080/appstreamig/streaming/";
        $root = "http://www.jeelplus.com/appstreamig/streaming/api/";
        $dlr_url = $root . 'index.php?action=dlr' . ('&par=%d_1_' . $uid . "_" . $phone_details[0]['operator_id'] . "_" . $phone . "_" . $callback); 
                     
        //'0: Accepted for delivery'        
        $status = send_sms($ope_details[0]['paid_shortcode'], $phone, ("لقد تم تفعيل اشتراكك بنجاح"), $ope_details[0]['paid_smsc'], $dlr_url, $ope_details[0]['port']); 
                   
        $response = ('{"success":"1","message":"success"}');
        
        $json = $callback . '({
                        "proposals": 
                    ';
        $json .= $response;
                            
        $json .= '})'; 
          
        return $json;   
    }                                                          
    else{
        $response = ('{"success":"0","message":"failed"}'); 
        
        $json = $callback . '({
                        "proposals": 
                    ';
        $json .= $response;
                            
        $json .= '})'; 
          
        return $json;
    }
            
}

//not used - old version
function renew_payment($cid){
    $payment_obj = new Payment();
    $db_fuctions_obj = new DbFunctions();
    
    $expired_users = $db_fuctions_obj->get_expired_users($cid, REPAYMENT_TRIES);
    
    $time = time();
              
    foreach($expired_users as $user) {
        $fp = fopen("expired_users" . $time . ".txt", "a+");
        fwrite($fp, $user->uid . " - " . $user->phone . " - " . $user->cid . "\n");
        fclose($fp);
          
        $payment_obj->renew_payment($user);  
    }
}

//not used - old version
function deactivate_expired_user($cid) {
    $db_fuctions_obj = new DbFunctions();
    $expired_users = $db_fuctions_obj->get_expired_users($cid, 0, false); 
    
    $time = time(); 
    
    foreach($expired_users as $user) {
        $fp = fopen("deactived_users" . $time . ".txt", "a+");
        fwrite($fp, $user->uid . " - " . $user->phone . " - " . $user->cid . "\n");
        fclose($fp);
        
        $db_fuctions_obj->deactivate_user($user->uid); 
    }
}

//sms payment
    /*function add_ver_code($phone, $country_id, $operator_id, $email, $code, $start_date, $end_date) { 
        
        $query = "insert into verification_code (phone, country_id, operator_id, email, code, date_added, end_date)
                  value
                  ('$phone','$country_id','$operator_id','$email','$code', '$start_date', '$end')";
                  
        mysql_query($query);
        
    } */
    
    //sms payment
     /* function generate_ver_code($phone, $country_id, $operator_id, $email) {
          $code = rand(999, 9999);
          
          $ope_details = get_operator_details($operator_id);
          
          //$period = $ope_details[0]['shortcode_period'] * 24 * 60 * 60;     
          
          $exists = check_if_user_exists_in_verfication_table($phone, $email);
          
          //new user
          if (!$exists) {        
              add_ver_code($phone, $country_id, $operator_id, $email, $code, 0, 0);
              
              $root = "http://arh:8080/appstreamig/streaming/";
              $dlr_url = $root . 'api.php?action=dlr' . urlencode('&par=%d_'); 
                
              send_sms($ope_details[0]['free_shortcode'], $phone, rawurlencode("Your verification code is: " . $code), $ope_details[0]['free_smsc'], $dlr_url, $ope_details[0]['port']); 
              
              return '{"success":"0","message":"success"}';  
          }
          else{
              return '{"success":"0","message":"failed"}';    
          } 
      } */
//not used - old version      
function repayment($phone, $country_id, $operator_id, $email, $callback = "", $cid = "") {
          $db_fuctions_obj = new DbFunctions(); 
          
          $ope_details = $db_fuctions_obj->get_operator_details($operator_id);
          
          $phone_details = $db_fuctions_obj->get_phone_details($phone, $cid);
          
          if (count($phone_details) > 0) {
              //$root = "http://arh:8080/appstreamig/streaming/";
              $root = "http://www.jeelplus.com/appstreamig/streaming/api/";
              $dlr_url = $root . 'index.php?action=dlr' . ('&par=%d_2_' . $phone_details[0]['uid'] . "_" . $operator_id . "_" . $phone . "_" . $callback);
              
              $status = send_sms($ope_details[0]['paid_shortcode'], $phone, ("لقد تم تفعيل اشتراكك بنجاح"), $ope_details[0]['paid_smsc'], $dlr_url, $ope_details[0]['port']); 

              $uid = $db_fuctions_obj->get_uid_by_phone($phone, $cid);
              
              $db_fuctions_obj->update_repayment_count('', $uid);
              
              /*if($status == '0: Accepted for delivery') {
                    return '{"status":"1","message":"success","PersonID":"' . $email . '"}'; 
              }
              else{
                    return ('{"success":"0","message":"failed"}'); 
              } */
          }
          else{
              return '{"success":"0","message":"phone_not_found"}';
          }
      }

//not used - old version      
function exec_curl($url){    //echo($url.'<br /><br />');
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_HEADER, 0); 
          curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, true); 

          curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
          curl_setopt($ch, CURLOPT_VERBOSE, TRUE); // Display communication with server
          $status = curl_exec($ch);
                  // echo('$status: ' . $status);
          curl_close($ch);   
         
          return $status;
      }

//not used - old version      
function send_sms($from, $number, $msg, $smsc, $dlr_url, $port = ""){
        if (SQLBOX) {
            $host = '89.234.33.27';
            $user = 'kannel55';
            $password = 'O.brah.*#&';
            $db_name = 'gwapp_prod';
            $port = '3306';  
                  //   echo('sqlbox<br />');
            $connection = mysqli_connect($host, $user, $password) or die(mysqli_error($connection));
            mysqli_set_charset($connection, "utf8");           
            mysqli_select_db($connection, $db_name) or die(mysqli_error($connection));
            
            
            $msg = rawurlencode($msg);       
            $number = trim($number);
            
            $insert = "INSERT INTO send_sms_sqlbox (
                          momt, sender, receiver, msgdata, sms_type, smsc_id, charset, coding, dlr_mask, dlr_url, boxc_id
                        ) VALUES (
                          'MT', '$from', '$number', '$msg' , '2', '$smsc', 'UTF-8', 2, 31, '$dlr_url', 'netbox'
                        )"; 
                        //   echo($insert);
            $status = mysqli_query($connection, $insert) or die(mysqli_error($connection)); 
        }
        else{
            $url = 'http://89.234.33.27:' . $port .'/cgi-bin/sendsms?username=kannel&password=kannel&from=' . $from . 
                   '&to=' . $number . '&text=' . $msg . '&charset=utf-8&coding=2&smsc=' . $smsc . 
                   '&binfo=&dlr-url=' . $dlr_url . '&dlr-mask=31';
                      // echo($url);
            $status = exec_curl($url);    
        }
        
        return $status;
    }
    
    /*function validate_verification_code($phone, $email, $code) {
        $valid = check_user_verification_code($phone, $email, $code);
        
        if ($valid) {
            $phone_details = get_phone_details($phone);
                
            $ope_details = get_operator_details($phone_details[0]['operator_id']);
                     
            $root = "http://arh:8080/appstreamig/streaming/";
            $dlr_url = $root . 'api.php?action=dlr' . urlencode('&par=%d_1_' . $email . "_" . $phone_details[0]['operator_id'] . "_" . $phone); 
                     
            //'0: Accepted for delivery'        
            $status = send_sms($ope_details[0]['paid_shortcode'], $phone, rawurlencode("لقد تم تفعيل اشتراكك بنجاح"), $ope_details[0]['paid_smsc'], $dlr_url, $ope_details[0]['port']); 
                            
            return '{"status":"1","message":"success","PersonID":"' . $email . '"}'; 
        }
        else{
            return '{"status":"0","message":"incorrect verifiation code"}'; 
        }
    } */
//not used - old version    
function cron_repayment(){  //xxxxxxxxxxxxxxxxxxxxxxxxx do not use it 
        $users = get_expired_users();
        
        foreach($users as $user) {
            
            $ope_details = get_operator_details($user['operator_id']);
            $email = $user['email'];
            $phone = $user['phone'];
            
            //$root = "http://arh:8080/appstreamig/streaming/";
            
            $root = "http://www.jeelplus.com/appstreamig/streaming/api/";
             
            $dlr_url = $root . 'index.php?action=dlr' . ('&par=%d_2_' . $email . "_" . $user['operator_id'] . "_" . $phone); 
    
            $status = send_sms($ope_details[0]['paid_shortcode'], $phone, ("لقد تم تفعيل اشتراكك بنجاح"), $ope_details[0]['paid_smsc'], $dlr_url, $ope_details[0]['port']); 
            
            update_repayment_count($user['id']);
            
        }
    }
    //end sms payment
    //end sms payment
//not used - old version    
function generate_name ($length = 5) {
        $image_name = "";
        $possible = "0123456789abcdefghijklmnopqrstuvwxyz";

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

//not used - old version    
function send($from_email, $from_name, $to_email, $to_name, $subject, $html_body = "", $attached_file_path = "", $alt_body = "", $reply_to_email = "admin@opapp.com", $reply_to_name = "opapp"){
            //SMTP needs accurate times, and the PHP time zone MUST be set
            //This should be done in your php.ini, but this is how to do it if you don't have access to that
            date_default_timezone_set('Asia/Jerusalem');
            
            //header('Content-Type: text/html; charset=utf-8');
                  
            //Create a new PHPMailer instance
            $mail = new PHPMailer();
            //Tell PHPMailer to use SMTP
            $mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $mail->Host = SMTP_HOSTNAME;
            //Set the SMTP port number - likely to be 25, 465 or 587
            $mail->Port = 25;
            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;
            //Username to use for SMTP authentication
            $mail->Username = SMTP_USERNAME;
            //Password to use for SMTP authentication
            $mail->Password = SMTP_PASSWORD;
            //Set who the message is to be sent from
            $mail->setFrom($from_email, $from_name);
            
            if (!empty($reply_to_email) && !empty($reply_to_name)) {
                //Set an alternative reply-to address
                $mail->addReplyTo($reply_to_email, $reply_to_name);
            }
           
            //Set who the message is to be sent to
            $mail->addAddress($to_email, $to_name);
            //Set the subject line
            $mail->Subject = $subject;
            
           // if (!empty($html_file_path)) {
                //Read an HTML message body from an external file, convert referenced images to embedded,
                //convert HTML into a basic plain-text alternative body
                $mail->msgHTML($html_body);
          //  }
           
            if (!empty($alt_body)) {
                //Replace the plain text body with one created manually
                $mail->AltBody = $alt_body;
            }
            
            if (!empty($attached_file_path)) {
                //Attach an image file
                $mail->addAttachment($attached_file_path);
            }

            //send the message, check for errors
            if (!$mail->send()) {
                return "Mailer Error: " . $mail->ErrorInfo;
            } else {
                return "Message sent!";
            }
      }   

//not used - old version    
function forget_password($email){
        $db_fuctions_obj = new DbFunctions();
        return $db_fuctions_obj->forget_password($email);
    }
    
//not used - old version
function change_password($email, $old_password, $new_password){
        $db_fuctions_obj = new DbFunctions();
        return $db_fuctions_obj->change_password($email, $old_password, $new_password);
    }

//not used - old version    
function add_comment($aid, $username, $comment, $client_id) {
        global $conn;
        
        $comment = trim($comment);
        $comment = $conn->db_escape_string($comment);
        
        $now = time();
           
        $insert_query = "insert into comments (aid, username, comment, date_added, client_id) value ('$aid','$username','$comment','$now', '$client_id')";
        
        $res = $conn->db_query($insert_query); 
        
        return $res;
    }

//not used - old version    
function get_comments_article($aid) {
        global $conn;
        
        $helper_obj = new Helper();
        
        $query = "select id, aid, username, comment, date_added 
                  from comments where aid = '$aid' and status = 1";
        
        $res = $conn->db_query($query); 
        $comments = array();
        $empty = true;
            
        //while($row = $conn->db_fetch_array($res)) {
        while($row = $res->fetch_assoc()){
            $row['date_added'] = "منذ" . $helper_obj->time_elapsed_string(($row['date_added']));
            $comments[] = $row;
            $empty = false;
        }
          
        /*$json = $callback . '({
                    "proposals": 
                    ';   */
               
        if ($empty) {
             $comment = $json . json_encode(array('success' => 0, 'message' => 'لا يوجد تعليقات')); 
        }
        else{
            
            $comment = $json . json_encode($comments);
        }
        
       /* $comment .= '})';         */
        
        return $comment;
    }

//not used - old version    
function delete_comment($comment_id, $uid) {
        global $conn;
        $query = "delete from comments where id = '$comment_id' and uid = '$uid'";
        $res = $conn->db_query($query);
        return $res;
    }
    
//not used - old version    
function edit_comment($id, $uid, $comment) {
        global $conn;
        
        $comment = trim($comment);
        
        $query = "update comments set comment = '$comment' where id = '$id' and uid = '$uid'";
        $res = $conn->db_query($query);
        return $res;
    }
    
//not used - old version    
function rate_article($aid, $rate, $uid) {
        $rated = check_if_user_rate_article($aid, $uid);
        
        if ($rated) {
            update_rate($aid, $rate, $uid);
        }
        else{
            add_rate($aid, $rate, $uid);
        }
    }
    
//not used - old version    
function get_user_rate($aid, $uid) {
        global $conn;  
        $query = "select rate from article_rate where aid = '$aid' and uid = '$uid'";
        $res = $conn->db_query($query);
        $row = $conn->db_fetch_array($res);
        
        return ($row['rate']+0);
    }
    
//not used - old version    
function get_avg_rate($aid) {
        global $conn;  
        $query = "select avg(rate) avg_rate from article_rate where aid = '$aid'";
        $res = $conn->db_query($query);
        $row = $conn->db_fetch_array($res);
        
        return ($row['avg_rate']+0);
    }
    
//not used - old version    
function check_if_user_rate_article($aid, $uid) { 
        global $conn;    
        $query = "select count(id) c from article_rate where aid = '$aid' and uid = '$uid'";
        $res = $conn->db_query($query);
        $row = $conn->db_fetch_array($res);
        
        return $row['c'];
    }
    
//not used - old version    
function update_rate($aid, $rate, $uid) {
        $now = time();
        global $conn;
        $query = "update article_rate set rate = '$rate', date_added = '$now' where uid = '$uid' and aid = '$aid'";
 
        $res = $conn->db_query($query);
    }
    
//not used - old version
function add_rate($aid, $rate, $uid) {
        $now = time();
        global $conn;
        $query = "insert into article_rate (aid, uid, rate, date_added) value ('$aid','$uid','$rate','$now')";
        
        $res = $conn->db_query($query);
    }

//not used - old version    
function save_token_pushwizard($token, $cid, $type = 1) {
        $now = time();
        global $conn;
        
        $valid = check_if_token_exists_for_client($token, $cid);
        
        if (!$valid) {
            $query = "insert into tokens (token, date_added, client_id, type) value ('$token','$now', '$cid', '$type')";          
            $res = $conn->db_query($query);
        }
    }

//not used - old version    
function check_if_token_exists_for_client($token, $cid) {
        global $conn;
        
        $query = "select count(id) c from tokens where token = '$token' and client_id = '$cid'";
        
        $res = $conn->db_query($query);
        $row = $conn->db_fetch_array($res);
        
        return ($row['c']);
    }

//get all tags    
function get_all_tags($cid, $start, $offset) {
        global $conn; 
        
        $start = ($start*$offset); 
        
        $query = "select tags.id, tags.name, tags.image, count(article_tags.tid) as tags_count
                    from tags 
                    left join article_tags on article_tags.tid = tags.id
                    left join articles_html on articles_html.id = article_tags.aid
                    where tags.name <> '' and articles_html.client_id = '$cid' group by tags.id order by tags_count desc limit $start, $offset";   
                  
        $res = $conn->db_query($query);
        
        $count = 0;
        
        //while($row = $conn->db_fetch_array($res)) {
        while($row = $res->fetch_assoc()){
            if($count == 0){
                $row['image'] = TAGS_IMAGES_PATH_UPLOAD . $row['image'];      
            }
            else{
                $row['image'] = TAGS_THUMBNAIL_IMAGES_PATH_UPLOAD . $row['image'];     
            } 
            
            $tags[] = $row; 
            
            $count++;  
        }
        
       /* $json = $callback . '({
                    "proposals": 
                    ';     */
 
        $tags = json_encode($tags);
        
       /* $tags .= '})';  */
        
        return $tags;
    }
    
//get news by tag
function get_articles_by_tag($tid, $cid, $start, $offset) {
        global $conn;
        
        $start = ($start*$offset); 
        
        $query = "select articles_html.id, articles_html.body, title, articles_html.image, client_id, added_by, date_added, 
                           articles_html.client_id cid, views, pg_rated_id, section, updated_date, updated_by,
                           GROUP_CONCAT(distinct concat(tags.id, ':', tags.name) SEPARATOR ', ') tags_name,
                           GROUP_CONCAT(distinct concat(categories.id, ':', categories.name) SEPARATOR ', ') cats_name
                    from articles_html 
                    inner join article_categories on article_categories.aid = articles_html.id 
                    inner join article_tags on article_tags.aid = articles_html.id
                    inner join tags on tags.id = article_tags.tid
                    inner join categories on article_categories.cid = categories.id
                    where article_tags.tid = '$tid' and articles_html.client_id = '$cid' group by articles_html.id order by date_added desc limit $start, $offset";
                    
        $res = $conn->db_query($query);
            
       // $flag = false;
        
        $helper_obj = new Helper();
        
        $data = array();
        
        $count = $start;
        
        //while($row = $conn->db_fetch_array($res)) {
        while($row = $res->fetch_assoc()){
            $row['date_added'] = "منذ " . $helper_obj->time_elapsed_string(($row['date_added'])); 
            
            $sd = delete_all_between("<style", "</style>", $row['body']);
            $row['body'] = trim(mb_substr(strip_tags($sd), 0, 85, 'UTF-8'));
            
            $row['title'] = trim(mb_substr($row['title'], 0, 140, 'UTF-8'));
            
            $count++;
        
            $row['count'] = $count;
        
            $data[] = $row;
         //   $flag = true;
        }
        
      /*  if ($sencha) {      
            $json = $callback . '({
                        "proposals": 
                        ';
              
           /* if (!$flag) {
                 $data = $json . json_encode(array('success' => 0, 'message' => 'لا يوجد معلومات')); 
            }
            else{  */
                
            /*    $data = $json . json_encode($data); */
           /* }  */
            
        /*    $data .= '})';
        }     
        else{  */
            $data = json_encode($data); 
       /* }      */
        
        return $data;
    }
    
//get news by tags from my selected sources
function get_articles_by_tag_from_my_sources($tid, $cid, $start, $offset, $sources) {
        global $conn;
        
        $start = ($start*$offset); 
        
        $query = "select articles_html.id, title, articles_html.image, client_id, added_by, date_added, 
                           articles_html.client_id cid, views, pg_rated_id, section, updated_date, updated_by,
                           GROUP_CONCAT(distinct concat(tags.id, ':', tags.name) SEPARATOR ', ') tags_name,
                           GROUP_CONCAT(distinct concat(categories.id, ':', categories.name) SEPARATOR ', ') cats_name
                    from articles_html 
                    inner join article_categories on article_categories.aid = articles_html.id 
                    inner join article_tags on article_tags.aid = articles_html.id
                    inner join tags on tags.id = article_tags.tid
                    inner join categories on article_categories.cid = categories.id
                    where article_categories.cid in ($sources) and article_tags.tid = '$tid' and articles_html.client_id = '$cid' 
                    group by articles_html.id order by date_added desc limit $start, $offset";
                    
        $res = $conn->db_query($query);
                      
        $helper_obj = new Helper();
        
        $data = array();
        
        $count = $start;

        while($row = $res->fetch_assoc()){
            $row['date_added'] = "منذ " . $helper_obj->time_elapsed_string(($row['date_added'])); 
            
            $count++;
        
            $row['count'] = $count;
        
            $data[] = $row;
        }
        
     
        $data = json_encode($data); 
       
        
        return $data;
    }
    
//not used - old version 
function save_football_PADS($comp_array, $type) {
        global $conn;  
        
        $delete_rows = "delete from sports where type = '$type'";
        $res = $conn->db_query($delete_rows); 
        
        $url = "";
                
        foreach($comp_array as $comp_id) {
            if ($type == 'fixturesEnhanced') {
                $url = 'http://pads6.pa-sport.com/api/football/competition/fixturesEnhanced/B2j8snvg44/' . $comp_id . '/json';
            }
            else if ($type == 'LeagueTableEnhanced') {
                $url = 'http://pads6.pa-sport.com/API/Football/Competition/LeagueTableEnhanced/B2j8snvg44/' . $comp_id . '/json';
            }
        
            $data = file_get_contents($url);
            $data = str_replace("@", "", $data);
          
            $data =$conn->db_escape_string($data);  
                                          
            $query = "insert into sports (req_id, json, type) value ('$comp_id', '$data', '$type')"; 
            $res = $conn->db_query($query);
        }
    }
    
//not used - old version 
function get_football_PADS($comp_id, $type) {
        global $conn;
        
        $query = "select json from sports where comp_id = '$comp_id' and type = '$type'";
        
        $res = $conn->db_query($query);
        
        $row = $conn->db_fetch_array($res);
        
        return $row['json'];
    }
    
//not used - old version 
function comment_abuse($comment_id) {
        $now = time();
        global $conn;   
        
        $query = "insert into comment_abuse (comment_id, date_added) value ('$comment_id', '$now')";
        $result =  $conn->db_query($query); 
    }
    
//not used - old version 
function check_user_status($phone, $client_id, $callback) {
        global $conn;  
        
        $query = "select status 
                  from users 
                  inner join users_roles on users_roles.uid = users.id
                  where phone = '$phone' and cid = '$client_id'";
                  
        $result =  $conn->db_query($query);  
        
        $row = $conn->db_fetch_array($result);  
        
        $response = ('{"success":"0","message":"failed"}');
                    
        if ($row['status']){
            $response = ('{"success":"1","message":"success"}');
        }
        
        $json = $callback . '({
                        "proposals": 
                ';
        $json .= $response;
                        
        $json .= '})';    
             
        return $json;
      
    }
    
//save udid for user
function save_udid($udid, $cid) {
        $now = time();
        global $conn;  
        
        $query = "insert into free_period (udid, cid, date_added) value ('$udid', '$cid', '$now')";
       
        $result =  $conn->db_query($query);  
    }
    
//not used - old version 
function check_free_period($udid, $cid, $callback) {
        $now = time();
        global $conn;  
        
        $query = "select date_added from free_period where udid = '$udid' and cid = '$cid'";
        $result =  $conn->db_query($query);
        $row = $conn->db_fetch_array($result);
        
        $three_days = 3 * 24 * 60 * 60;
        
        if ($row['date_added'] != "") {
            $free_period = $row['date_added'] + $three_days;
            
             $response = ('{"success":"0","message":"failed"}'); 
            
            if ($free_period >= $now) {
                $response = ('{"success":"1","message":"success"}'); 
            } 
        }
        else{
            save_udid($udid, $cid);
            $response = ('{"success":"1","message":"success"}');
        }
        
         $json = $callback . '({
                        "proposals": 
                    ';
         $json .= $response;
                        
         $json .= '})'; 
         
         return $json;
    }
    
//get tags have large number of news
function trend_tags(){
        global $conn;   
        
        $query = "select tid id, count(article_tags.tid) tags_count, tags.name
                    from article_tags 
                    inner join tags on tags.id = article_tags.tid 
                    group by tid having tags_count > 0
                    order by tags_count desc 
                    limit 50";
        $res =  $conn->db_query($query);
        
        $tags = array();
        
        while($row = $res->fetch_assoc()){
            $tags[] = $row;
        }
        
        return json_encode($tags);
    }
    
//get old news - 10 days
function get_old_news(){
        global $conn; 
        
        $time_to_delete = TIME_TO_DELETE_NEWS * 24 * 60* 60;
        
        $data_to_delete = time() - $time_to_delete;
        //$data_to_delete = '1423380086';
        
        $query = "select id from articles_html where date_added <= " . $data_to_delete;
        
        $res =  $conn->db_query($query);
        
        $news = array();
        
        while($row = $res->fetch_assoc()){
            $news[] = $row;
        }
        
        return $news;
    }

//delete news > 10 days    
function delete_old_news() {
        global $conn;
        
        $old_news = get_old_news();    
        
        foreach($old_news as $news) {
            //delete articles_html
            $query1 = "delete from articles_html where id = '" . $news['id'] . "'";
            $res =  $conn->db_query($query1);
            
            //delete article_tags
            $query2 = "delete from article_tags where aid = '" . $news['id'] . "'";
            $res =  $conn->db_query($query2);  
            
            //delete article_categories
            $query3 = "delete from article_categories where aid = '" . $news['id'] . "'";  
            $res =  $conn->db_query($query3);
        }
        
    }
    
//not used - old version 
function update_mobile_for_new_and_updated_categories($uv, $av){
        global $conn; 
        
        $query_uv = "select * from categories where uv > $uv";
        $res_uv =  $conn->db_query($query_uv);
        
        $query_av = "select * from categories where av > $av";
        $res_av =  $conn->db_query($query_av);
        
        $cats = array();
        
        while($row = $res_uv->fetch_assoc()){
            $cats['uv'][] = $row;
        } 
        while($row = $res_av->fetch_assoc()){
            $cats['av'][] = $row;
        }
        
        return json_encode($cats);
    }
    
//**********************keywords*************************
///      all below function for keywords task - اهتماماتي 
function search_keyword($keyword) {
        $keyword = trim($keyword);
        
        global $conn; 
        
        $query = "select parent, name from keywords where synonyms like '%$keyword%' group by parent limit 50";
        $res =  $conn->db_query($query);
        
        $keywords = array();
        
        while($row = $res->fetch_assoc()){
            $keywords[] = $row;
        }
        
        if (count($keywords) == 0) {
            $keywords = array('success' => 0, 'message' => 'no results'); 
        }
        
        return json_encode($keywords); 
    }
    
function check_if_keyword_exists($keyword) {
    $keyword = trim($keyword);
    
    global $conn; 
    
    $query = "select count(id) c from keywords where synonyms = '$keyword'";
    $res = $conn->db_query($query);
    $keyword = $res->fetch_assoc(); 

    return $keyword['c']; 
}
    
function get_keyword_id_by_name($keyword) {
    $keyword = trim($keyword);
    
    global $conn; 
    
    $query = "select parent from keywords where synonyms = '$keyword'";
    $res = $conn->db_query($query);
    $keyword = $res->fetch_assoc(); 

    return $keyword['parent']; 
}

function update_keyword_parent($parent, $keyword_id) {
    global $conn; 
    $query = "update keywords set parent = '$parent' where id = '$keyword_id'";
    $res =  $conn->db_query($query);
}

function add_keyword_to_user($keyword_id, $uid){
    global $conn; 
    $query = "insert into keyword_user (keyword_id, user_id) values ('$keyword_id', '$uid')";
    $res =  $conn->db_query($query);
}

function check_if_user_has_keyword($keyword_id, $uid){
    global $conn; 
    $query = "select count(id) c from keyword_user where keyword_id = '$keyword_id' and user_id = '$uid'";
    $res =  $conn->db_query($query);
    $keyword = $res->fetch_assoc(); 

    return $keyword['c'];
}

function add_keyword($keyword, $udid) {
    $keyword = trim($keyword);
    
    global $conn; 
    
    if (!check_if_keyword_exists($keyword)) {
        $synonyms = array($keyword, 'و' . $keyword, $keyword . '_', '_' . $keyword, 
                          str_replace(" ", '_', $keyword), str_replace(" ", '-', $keyword), 
                          str_replace(" ", '.', $keyword),
                          $keyword . ":",
                          ":" . $keyword . ":",
                          ":" . $keyword,
                          "." . $keyword,
                          "." . $keyword . '.',
                          $keyword . '.',
                          $keyword . ',',
                          ',' . $keyword . ',',
                          ',' . $keyword,
                          ';' . $keyword,
                          ';' . $keyword . ';',
                          $keyword . ';'
                          );
        $parent = '';
        
        foreach($synonyms as $synonym) {
            $query = "insert into keywords (name, synonyms) values ('$keyword', '$synonym')";
            $res =  $conn->db_query($query);
            
            $keyword_id = $conn->db_last_insert_id(NULL, NULL);//added NULL, NULL to remove warning
            
            if ($parent == '') $parent = $keyword_id;
            
            update_keyword_parent($parent, $keyword_id);
        }
        
        $keyword_id = get_keyword_id_by_name($keyword);
    }
    else{
         $keyword_id = get_keyword_id_by_name($keyword);
    }
    
    $user_obj = new User();
     
    $uid = $user_obj->get_uid_by_udid($udid);
            
    if (!check_if_user_has_keyword($keyword_id, $uid)) {  
        
        add_keyword_to_user($keyword_id, $uid);
    }
    
    return ('{"success":"1","keyword_id":"' . $keyword_id . '"}');
}

function get_my_keywords($udid) {
    global $conn;
    $query = "select keyword_id, name 
              from keyword_user
              inner join keywords on keywords.id = keyword_user.keyword_id
              inner join users on users.id = keyword_user.user_id
              where users.udid = '$udid'";
    
    $res =  $conn->db_query($query);
              
    $keywords = array();
    
    while($row = $res->fetch_assoc()){
        $keywords[] = $row;
    }
    
    if (count($keywords) == 0) {
        $keywords = array('success' => 0, 'message' => 'no results'); 
    }
    
    return json_encode($keywords); 
}

function get_synonyms_by_keyword_id($keyword_id){
    global $conn;
    $query = "select synonyms from keywords where parent = '$keyword_id'";
    $res =  $conn->db_query($query);
              
    $keywords = array();
    
    while($row = $res->fetch_assoc()){
        $keywords[] = $row['synonyms'];
    }
    
    return $keywords; 
}

function get_news_by_keywords_from_mysources($keyword_id, $udid, $start, $offset) {
     $user_obj = new User();
     
     //$uid = $user_obj->get_uid_by_udid($udid);
    
     $synonyms = get_synonyms_by_keyword_id($keyword_id); 
     
     $keyword_for_search = "title like ('%swdsd323232322232sdaahskjas%') or ";
     
     foreach($synonyms as $synonym) {
         $synonym = str_replace('_', '\_', $synonym);
         
         $keyword_for_search .= "title like ('% $synonym %') or ";
         //$keyword_for_search .= "title = '$synonym' or ";
     }
    
     $keyword_for_search = substr($keyword_for_search, 0, (strlen($keyword_for_search)-4) );
    
     //$my_sources = get_my_sources($uid);
     
    // if ($my_sources == '') $my_sources = '79,';
    // $my_sources = substr($my_sources, 0, (strlen($my_sources)-1) );
     
     global $conn;
 
     $start = ($start*$offset);
    
     /*$query = "select c2.parent, articles_html.id, title, image, client_id, added_by, date_added, cid, views, pg_rated_id, section, updated_date, updated_by
              from articles_html 
              inner join article_categories on article_categories.aid = articles_html.id
              inner join categories c1 on c1.id = article_categories.cid
              inner join categories c2 on c2.id = c1.parent
              where (
                      $keyword_for_search
                     ) 
              and cid in ($my_sources) and client_id = '47' order by id desc limit $start, $offset";     */
              
     $query = "select c2.parent, articles_html.id, articles_html.body, title, image, client_id, added_by, date_added, cid, views, pg_rated_id, section, updated_date, updated_by
              from articles_html 
              inner join article_categories on article_categories.aid = articles_html.id
              inner join categories c1 on c1.id = article_categories.cid
              inner join categories c2 on c2.id = c1.parent
              where (
                      $keyword_for_search
                     ) 
              and client_id = '47' 
              group by title
              order by id desc limit $start, $offset";
              
     //echo($query); 
    $res = $conn->db_query($query);
    
    $all_articles = array();
    
    $helper_obj = new Helper();
    
    $count = $start;

    while($row = $res->fetch_assoc()){
        $row['date_added'] = "منذ " . $helper_obj->time_elapsed_string(($row['date_added']));
        
        $sd = delete_all_between("<style", "</style>", $row['body']);
        $row['body'] = trim(mb_substr(strip_tags($sd), 0, 85, 'UTF-8'));
        
        $row['title'] = trim(mb_substr($row['title'], 0, 140, 'UTF-8'));
        
        $count++;
        
        $row['count'] = $count;
        
        $all_articles[] = $row;
    }
    
    $json = '';
    
    $json .= json_encode($all_articles); 

    return $json;
}