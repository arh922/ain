<?php

class XmlToJson {
    function __construct() {
        //$this->conn = new MySQLiDatabaseConnection();
    }
    
    public static function delete_all_between($beginning, $end, $string) {
          $beginningPos = strpos($string, $beginning);
          $endPos = strpos($string, $end);
                           
          if ($beginningPos === false || $endPos === false) {
            return $string;
          }
                    
          $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

          return str_replace($textToDelete, '', $string);
    }

    function get($url, $referrer = true){
        $url = str_replace("&amp;", '&', $url);
        //header("Content-Type: text/html; charset=utf-8",0,301);
        $process = curl_init();
        curl_setopt($process, CURLOPT_URL, $url);
        //curl_setopt($process, CURLOPT_HTTPHEADER, $header);
        curl_setopt($process, CURLOPT_HEADER, 0);
        if ($referrer) curl_setopt($process, CURLOPT_REFERER, '[url=http://www.google.com]http://www.google.com[/url]');
        curl_setopt($process, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)');
      //  if ($this->cookies == TRUE)
         //   curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
       // if ($this->cookies == TRUE)
      //      curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
        curl_setopt($process, CURLOPT_ENCODING, "gzip,deflate");
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_HTTPGET, true);
        //curl_setopt($process, CURLOPT_AUTOREFERER, true);
        
     //   if ($this->proxy)
         //   curl_setopt($process, CURLOPT_PROXY, $this->proxy);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($process,CURLOPT_SSL_VERIFYPEER, false);      //for https 
         
        $return = curl_exec($process);
        curl_close($process);  
                                                   
        //$response = file_get_contents($url);
                      //  echo($response); exit;
        return $return;
    }
    
     function get_cookie($url, $referrer = true){
        $url = str_replace("&amp;", '&', $url);
        //header("Content-Type: text/html; charset=utf-8",0,301);
        $process = curl_init();
        curl_setopt($process, CURLOPT_URL, $url);
        //curl_setopt($process, CURLOPT_HTTPHEADER, $header);
        curl_setopt($process, CURLOPT_HEADER, 0);
        if ($referrer) curl_setopt($process, CURLOPT_REFERER, '[url=http://www.google.com]http://www.google.com[/url]');
        curl_setopt($process, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)');
      //  if ($this->cookies == TRUE)
            curl_setopt($process, CURLOPT_COOKIEFILE, 'xx.txt');
       // if ($this->cookies == TRUE)
            curl_setopt($process, CURLOPT_COOKIEJAR, 'xx.txt');
        curl_setopt($process, CURLOPT_ENCODING, "gzip,deflate");
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_HTTPGET, true);
      //  curl_setopt($process, CURLOPT_COOKIE, "cf_clearance=2ec4d032ac973813eecfbef707f451c00bb04d4c-1435480029-604800");
        //curl_setopt($process, CURLOPT_AUTOREFERER, true);
        
     //   if ($this->proxy)
         //   curl_setopt($process, CURLOPT_PROXY, $this->proxy);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($process,CURLOPT_SSL_VERIFYPEER, false);      //for https 
         
        $return = curl_exec($process);
        curl_close($process);  
                                                   
        //$response = file_get_contents($url);
                      //  echo($response); exit;
        return $return;
    }

    public static function Parse ($url, $array = false) {
         echo('<br /> url inside parse: ' . $url . '<br />');  
                                    
        if(strrpos($url,"alforsan.net") !== FALSE) {    
            include("parse_huge_rss.php");
                                      
            $url_exp = explode("/", $url);
            
            $last = $url_exp[count($url_exp)-1]; 
            
            $last_exp = explode(".", $last);
            
            $source_name = $last_exp[0];

            // Open the XML
            $file = file_get_contents($url);

            $fp = fopen("forsan_" . $source_name . ".txt", "w+");
            fwrite($fp, $file);
            fclose($fp);
                         
            $handle = fopen("forsan_" . $source_name . ".txt", 'r');
                       
            // Get the nodestring incrementally from the xml file by defining a callback
            // In this case using a anon function.
            $xxx = nodeStringFromXMLFile($handle, '<item>', '</item>', function($nodeText){
                // Transform the XMLString into an array and 
              //  $data = (getArrayFromXMLString($nodeText));
                
               // return array_slice($data, 0, 5);
               
              //  $_SESSION['simpleXml']['channel']['item'][] = getArrayFromXMLString($nodeText);
               // pr($_SESSION['simpleXml']);   exit;  
            });

            fclose($handle);
            
            foreach($xxx as $xx){
                $yy['channel']['item'][] = getArrayFromXMLString($xx);
            }
            
            return $yy;
        }
           
            //pr($yy);exit;                  
        //$curl_obj = new CURL();
        $obj = new XmlToJson();
                                      
        $fileContents = $obj->get($url);       
              
                // echo('------------>');echo($fileContents);     exit;       
        if(strrpos($fileContents,"HTTP Error 400") || strrpos($fileContents,"The page you are looking for cannot be found") || 
           strrpos($fileContents,"An Error Was Encountered") ||  strrpos($fileContents,'Internal Server Error') ||  strrpos($fileContents,'Object reference not') 
           || strpos($url, 'alwakeelnews') !== false){
            $url = str_replace("amp;", '&', $url); 
           // $fileContents = file_get_contents($url);    
        }    
             
        if(strrpos($url,"alarabiya.net") !== FALSE) {
            $opts = array(
                  'http'=>array(
                    'method'=>"GET",
                    'header'=>"Accept-language: en\r\n" .                           
                              "Cookie: YPF8827340282Jdskjhfiw_928937459182JAX666=52.18.222.69\r\n"
                  )
                );

                $context = stream_context_create($opts);

                // Open the file using the HTTP headers set above
                $fileContents = file_get_contents($url, false, $context);
        }  
        
        elseif(strrpos($url,"basnews.com") !== FALSE) {
             $fileContents = $obj->get_cookie($url); 
        }            
            
        //$fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);     
                         
        $fileContents = trim(str_replace('"', "'", $fileContents));
             
        if(strrpos($url,"dotmsr.com") === FALSE) {    
            $fileContents = trim(str_replace('&', " ", $fileContents));
        }
        
        if(strrpos($url,"alraimedia.com") !== FALSE) {   
            $fileContents = trim(str_replace(' bg ', " ", $fileContents));
        }
        
        if(strrpos($url,"eqtsad.net") !== FALSE) {    
            $simpleXml = simplexml_load_string($fileContents, "SimpleXMLElement", LIBXML_NOCDATA); 
        }
           //pr($simpleXml);exit;      
                  /* echo($fileContents); 
                              //$simpleXml = simplexml_load_string($fileContents, null, LIBXML_NOCDATA);
                              $simpleXml = simplexml_load_string($fileContents);
                              pr($simpleXml);
                              exit;   */     
        $fileContents = str_replace("allowfullscreen", "", $fileContents); 
        $fileContents = str_replace("media:title", "media_title", $fileContents); 
        $fileContents = str_replace("atom:link", "atom_link", $fileContents); 
        $fileContents = str_replace("sy:updatePeriod", "sy_updatePeriod", $fileContents); 
        $fileContents = str_replace("sy:updateFrequency", "sy_updateFrequency", $fileContents); 
        $fileContents = str_replace("dc:creator", "dc_creator", $fileContents); 
        
        $fileContents = str_replace("content:encoded", "content_encoded", $fileContents); 
                    
        $fileContents = str_replace("media:content", "media_content", $fileContents); 
        $fileContents = str_replace('<media:', '<', $fileContents);
        $fileContents = str_replace('</media:', '</', $fileContents);
                        /* echo($fileContents); 
                              //$simpleXml = simplexml_load_string($fileContents, null, LIBXML_NOCDATA);
                              $simpleXml = simplexml_load_string($fileContents);
                              pr($simpleXml);
                              exit;  */       
        if(strrpos($url,"sabr.cc") === FALSE && strrpos($url,"youm7.com") === FALSE && 
           strrpos($url,"almaghribtoday.net") === FALSE && strrpos($url,"alarabalyawm.net") === FALSE &&
           strrpos($url,"assawsana.com") === FALSE && strrpos($url,"alsawt") === FALSE && 
           strrpos($url,"islahnews.net") === FALSE &&
           strrpos($url,"hattpost.com") === FALSE &&
           strrpos($url,"tnntunisia") === FALSE &&
           strrpos($url,"moroccoeyes") === FALSE &&
           strrpos($url,"tayyar.org") === FALSE &&
           strrpos($url,"tunisien.tn") === FALSE &&
           strrpos($url,"febrayer") === FALSE &&
           strrpos($url,"hyperstage.net") === FALSE &&
           strrpos($url,"almashhad.net") === FALSE &&
           strrpos($url,"zajelpress.ps") === FALSE &&
           strrpos($url,"raialyoum.com") === FALSE &&
           strrpos($url,"alhasela.com") === FALSE &&
           strrpos($url,"alayam.com") === FALSE &&
           strrpos($url,"ontveg.com") === FALSE &&
           strrpos($url,"altaleea.com") === FALSE &&
           strrpos($url,"basnews.com") === FALSE &&
           strrpos($url,"twasul.info") === FALSE &&
           strrpos($url,"hasatoday.com") === FALSE &&
           strrpos($url,"yejournal.com") === FALSE &&
           strrpos($url,"an7a.com") === FALSE &&
           strrpos($url,"alraimedia.com") === FALSE &&
           strrpos($url,"alummahnews.com") === FALSE &&
           strrpos($url,"alaraby.co.uk") === FALSE &&
           strrpos($url,"turkey-post.net") === FALSE &&
           strrpos($url,"alhorya.com") === FALSE &&
           strrpos($url,"arn.ps") === FALSE &&
           strrpos($url,"hasanews.com") === FALSE &&
           strrpos($url,"watn-news.com") === FALSE &&
           strrpos($url,"tounessna.info") === FALSE &&
           strrpos($url,"filgoal.com") === FALSE &&
           strrpos($url,"bahrainalyoum.net") === FALSE &&
           strrpos($url,"ittihadna.com") === FALSE &&
           strrpos($url,"q8ping") === FALSE &&
           strrpos($url,"shahiya.com") === FALSE &&
           strrpos($url,"3alyoum.com") === FALSE &&
           strrpos($url,"alforsan.net") === FALSE &&
           strrpos($url,"babnet.net") === FALSE &&
           strrpos($url,"saidaonline.com") === FALSE &&
           strrpos($url,"almowaten.net") === FALSE &&
           strrpos($url,"eqtsad.net") === FALSE &&
           strrpos($url,"albiladpress.com") === FALSE &&
           strrpos($url,"alkhaleej.ae") === FALSE &&
           strrpos($url,"fajr.sa") === FALSE &&
           strrpos($url,"qtv.qa") === FALSE &&
           strrpos($url,"al-gornal.com") === FALSE &&
           strrpos($url,"alrafidain.org") === FALSE &&
           strrpos($url,"fath-news.com") === FALSE &&
           strrpos($url,"almesryoon") === FALSE 
           ) {
            $fileContents = str_replace('<![CDATA[', '', $fileContents);
            
            $fileContents = str_replace('</link>]]', '', $fileContents); //youm7
            
            $fileContents = str_replace('<]]>', '', $fileContents);
            $fileContents = str_replace('<br]]>', '', $fileContents);
            
            $fileContents = str_replace('<di]]>', '', $fileContents);
            $fileContents = str_replace('</di]]>', '', $fileContents);
            
            $fileContents = str_replace('<div]]>', '', $fileContents);
            
            $fileContents = str_replace('</d]]>', '', $fileContents);
            $fileContents = str_replace('<b]]>', '', $fileContents);
            $fileContents = str_replace(']]>', '', $fileContents);      
            
            $fileContents = str_replace('entry', 'item', $fileContents);
            
            $fileContents = str_replace('<br />', '', $fileContents);
            $fileContents = str_replace('<BR>', '', $fileContents);
            
            $fileContents = str_replace('<br/>', '', $fileContents);    
        }
                /*echo($fileContents); 
                           //   $simpleXml = simplexml_load_string($fileContents);
                              $simpleXml = simplexml_load_string($fileContents,null,LIBXML_NOCDATA);
                              pr($simpleXml);
                              exit; */  
        if(strrpos($url,"filgoal.com") === FALSE && 
           strrpos($url,"buyemen.com") === FALSE &&  
           strrpos($url,"yemenat.net") === FALSE &&  
           strrpos($url,"fath-news.com") === FALSE) {       
            $fileContents = str_replace('utf-16', 'utf-8', $fileContents);
            $fileContents = str_replace('windows-1256', 'utf-8', $fileContents);
            $fileContents = str_replace('Windows-1256', 'utf-8', $fileContents);
        }  
               /* echo($fileContents); 
                              $simpleXml = simplexml_load_string($fileContents,null,LIBXML_NOCDATA);
                              pr($simpleXml);
                              exit;    */             
        $fileContents = XmlToJson::delete_all_between('<atom_link', '/>', $fileContents);  
       // $fileContents = XmlToJson::delete_all_between('<iframe', '</iframe>', $fileContents);  
                 
        if(strrpos($url,"sabr.cc") !== FALSE || strrpos($url,"babnet.net") !== FALSE ) {
            $fileContents = iconv('windows-1256', 'UTF-8', $fileContents);
           // $fileContents = XmlToJson::delete_all_between('<description', '</description>', $fileContents);
        }
            /*  echo($fileContents); 
                              //$simpleXml = simplexml_load_string($fileContents,null,LIBXML_NOCDATA);
                              $simpleXml = simplexml_load_string($fileContents);
                              pr($simpleXml);
                              exit;  */
        if(strrpos($url,"sabr.cc") === FALSE && 
           strrpos($url,"alkhaleej.ae") === FALSE && 
           strrpos($url,"buyemen.com") === FALSE && 
           strrpos($url,"babnet.net") === FALSE && 
           strrpos($url,"qtv.qa") === FALSE) {
            $fileContents = preg_replace("=^<p>(.*)</p>$=i", "", $fileContents);   //this will remove <pubDate> as well
            $fileContents = preg_replace("/<\/?p[^>]*\>/i", "", $fileContents); 
        }
                 /*echo($fileContents); 
                              $simpleXml = simplexml_load_string($fileContents,null,LIBXML_NOCDATA);
                              pr($simpleXml);
                              exit;    */                 
        $fileContents = str_replace('<p>', '', $fileContents);
        $fileContents = str_replace("<p style='text-align: justify;'>", '', $fileContents);
        $fileContents = str_replace("<p style='text-align: center;'>", '', $fileContents);
        $fileContents = str_replace("<strong style='font-size: 10pt;'>", '', $fileContents);
        $fileContents = str_replace("<strong style='font-size: 11pt;'>", '', $fileContents);
        $fileContents = str_replace("<strong style='font-size: 12pt;'>", '', $fileContents);
        $fileContents = str_replace("<strong style='font-size: 13pt;'>", '', $fileContents);
        $fileContents = str_replace("<strong style='font-size: 14pt;'>", '', $fileContents);
        $fileContents = str_replace("<strong style='font-size: 15pt;'>", '', $fileContents);
                  
        $fileContents = str_replace('<p dir="rtl">', '', $fileContents);
        $fileContents = str_replace("<p dir='rtl'>", '', $fileContents);
        $fileContents = str_replace('</p>', '', $fileContents);  
                         
        $fileContents = str_replace('<strong>', '', $fileContents);  
        $fileContents = str_replace('</strong>', '', $fileContents);  
                                             
        if(strrpos($url,"almashhad.net") === FALSE) { 
            $fileContents = str_replace('<h1>', '', $fileContents);  
            $fileContents = str_replace('<h1 style="text-align: justify;">', '', $fileContents);  
            $fileContents = str_replace('<h2>', '', $fileContents);  
            $fileContents = str_replace('<h2 style="text-align: justify;">', '', $fileContents);  
            $fileContents = str_replace('<h3>', '', $fileContents);  
            $fileContents = str_replace('<h3 style="text-align: justify;">', '', $fileContents);  
            $fileContents = str_replace('<h4>', '', $fileContents); 
            $fileContents = str_replace('<h4 style="text-align: justify;">', '', $fileContents); 
            $fileContents = str_replace("<h4 style='text-align: justify;'>", '', $fileContents); 
        }
                  
        $fileContents = str_replace("<h2 Class='NewsSubTitleText'>", '', $fileContents); 
        $fileContents = str_replace('<h2 Class="NewsSubTitleText">', '', $fileContents); 
                             
        $fileContents = preg_replace("/<\/?div[^>]*\>/i", "", $fileContents); 
                             
        $fileContents = str_replace('<div>', '', $fileContents); 
        $fileContents = str_replace('<div dir="rtl" style="text-align: justify;">', '', $fileContents); 
        $fileContents = str_replace("<div dir='rtl' style='text-align: justify;'>", '', $fileContents); 
        $fileContents = str_replace('<div dir="rtl">', '', $fileContents); 
        $fileContents = str_replace('<div style="text-align: justify;">', '', $fileContents); 
        $fileContents = str_replace("<div style='text-align: justify;'>", '', $fileContents); 
        $fileContents = str_replace("<div dir='rtl'>", '', $fileContents);     
        $fileContents = str_replace('</div>', '', $fileContents); 
                                            
        $fileContents = str_replace('</h1>', '', $fileContents);  
        $fileContents = str_replace('</h2>', '', $fileContents);  
        $fileContents = str_replace('</h3>', '', $fileContents);  
        $fileContents = str_replace('</h4>', '', $fileContents); 
                              
        $fileContents = str_replace("<h4 dir='ltr'>", '', $fileContents);  
        $fileContents = str_replace('<h4 dir="ltr">', '', $fileContents);
                    
        $fileContents = str_replace('<h4 dir="RTL">', '', $fileContents); 
        $fileContents = str_replace("<h4 dir='RTL'>", '', $fileContents); 
         
        $fileContents = str_replace("<h4 style='text-align: right;' align='right'>", '', $fileContents);  
        $fileContents = str_replace('<h4 style="text-align: right;" align="right">', '', $fileContents);  
        
        $fileContents = str_replace('<h4 style="text-align: right;">', '', $fileContents);  
        $fileContents = str_replace("<h4 style='text-align: right;'>", '', $fileContents);  
        $fileContents = str_replace("<span style='float:left'></span>", '', $fileContents);  
        $fileContents = str_replace("<span style='float:left'> </span>", '', $fileContents);  
        $fileContents = str_replace("<span style='float:left'>", '', $fileContents);  
        $fileContents = str_replace("<span style='color: #ff0000;'>", '', $fileContents);  
        $fileContents = str_replace('<span style="color: #ff0000;">', '', $fileContents);  
        $fileContents = str_replace("</span>", '', $fileContents);  
                             
        $fileContents = str_replace('<br>', '', $fileContents);  
                                    
        if(strrpos($url,"kharjhome.com") !== FALSE || strrpos($url,"rsssd.com") !== FALSE) {      
            $fileContents = utf8_encode($fileContents);                    
        } 
                     
        if(strrpos($url,"newsqassim.com") !== FALSE || strrpos($url,"assawsana.com") !== FALSE || strrpos($url,"sabanews.net") !== FALSE) {      
            $fileContents = iconv('windows-1256', 'UTF-8', $fileContents);                   
        }    
                  /*   echo($fileContents); 
                              $simpleXml = simplexml_load_string($fileContents,null,LIBXML_NOCDATA);
                              pr($simpleXml);
                              exit;*/
        if(strrpos($url,"newsqassim.com") === FALSE && 
           strrpos($url,"assawsana.com") === FALSE && 
           strrpos($url,"qtv.qa") === FALSE &&     
           strrpos($url,"buyemen.com") === FALSE &&     
           strrpos($url,"filgoal.com") === FALSE &&     
           strrpos($url,"yemenat.net") === FALSE &&     
           strrpos($url,"bahrainalyoum.net") === FALSE &&     
           strrpos($url,"fath-news.com") === FALSE) {                  
            $fileContents = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $fileContents);
        }
               
        if(strrpos($url,"tounessna.info") === FALSE && strrpos($url,"filgoal.com") === FALSE) {                              
             $fileContents = preg_replace("/<img[^>]+\>/i", "", $fileContents);  
        }
                   
     //   $fileContents = preg_replace('location', "", $fileContents);   
       // $fileContents = preg_replace('reload', "", $fileContents);   
       // $fileContents = preg_replace('href', "", $fileContents);   
                  
        if(strrpos($url,"almaghribtoday.net") !== FALSE ) {
            $fileContents .= "</channel></rss>";
        }
        
        if(strrpos($url,"albiladpress.com") !== FALSE && strrpos($url,"econrss.php") === FALSE) {
            if(strrpos($fileContents,"</channel></rss>") === FALSE && strrpos($fileContents,"</rss>") === FALSE) {
                $fileContents .= "</channel></rss>";   
            }
        }
                /*echo($fileContents); 
                              $simpleXml = simplexml_load_string($fileContents,null,LIBXML_NOCDATA);
                              pr($simpleXml);
                              exit;   */
        //$fileContents = utf8_encode($fileContents);
        
        //$fileContents = stripslashes($fileContents);
                                    //  echo('<br />'.$url.'<br />');
            //if (strpos($url, 'elkhabar.com') !== FALSE) {      echo('1111111111111111111111');
             // echo($fileContents); exit;   
           //}   
                                                        
        if(strrpos($url,"youm7.com") !== FALSE || strrpos($url,"almaghribtoday.net") !== FALSE || 
           strrpos($url,"alarabalyawm.net") !== FALSE || strrpos($url,"assawsana.com") !== FALSE ||
           strrpos($url,"alsawt") !== FALSE || strrpos($url,"islahnews.net") !== FALSE ||
           strrpos($url,"al-gornal.com") !== FALSE ||
           strrpos($url,"hattpost.com") !== FALSE ||
           strrpos($url,"tnntunisia") !== FALSE ||
           strrpos($url,"moroccoeyes") !== FALSE ||
           strrpos($url,"febrayer") !== FALSE ||
           strrpos($url,"hyperstage.net") !== FALSE ||
           strrpos($url,"tayyar.org") !== FALSE ||
           strrpos($url,"tunisien.tn") !== FALSE ||
           strrpos($url,"babnet.net") !== FALSE ||
           strrpos($url,"alaraby.co.uk") !== FALSE ||
           strrpos($url,"almashhad.net") !== FALSE ||
           strrpos($url,"alhasela.com") !== FALSE ||
           strrpos($url,"altaleea.com") !== FALSE ||
           strrpos($url,"alayam.com") !== FALSE ||
           strrpos($url,"basnews.com") !== FALSE ||
           strrpos($url,"twasul.info") !== FALSE ||
           strrpos($url,"hasatoday.com") !== FALSE ||
           strrpos($url,"an7a.com") !== FALSE ||
           strrpos($url,"alummahnews.com") !== FALSE ||
           strrpos($url,"ontveg.com") !== FALSE ||
           strrpos($url,"alraimedia.com") !== FALSE ||
           strrpos($url,"turkey-post.net") !== FALSE ||
           strrpos($url,"yejournal.com") !== FALSE ||
           strrpos($url,"alhorya.com") !== FALSE ||
           strrpos($url,"arn.ps") !== FALSE ||
           strrpos($url,"hasanews.com") !== FALSE ||
           strrpos($url,"watn-news.com") !== FALSE ||
           strrpos($url,"filgoal.com") !== FALSE ||
           strrpos($url,"zajelpress.ps") !== FALSE ||
           strrpos($url,"ittihadna.com") !== FALSE ||
           strrpos($url,"q8ping") !== FALSE ||
           strrpos($url,"shahiya.com") !== FALSE ||
           strrpos($url,"3alyoum.com") !== FALSE ||
           strrpos($url,"alborsanews.com") !== FALSE ||
           strrpos($url,"raialyoum.com") !== FALSE ||
           strrpos($url,"albiladpress.com") !== FALSE ||
           strrpos($url,"bahrainalyoum.net") !== FALSE ||
           strrpos($url,"alforsan.net") !== FALSE ||
           strrpos($url,"almowaten.net") !== FALSE ||
           strrpos($url,"tounessna.info") !== FALSE ||
           strrpos($url,"saidaonline.com") !== FALSE ||
           strrpos($url,"qtv.qa") !== FALSE ||
           strrpos($url,"alkhaleej.ae") !== FALSE ||
           strrpos($url,"alrafidain.org") !== FALSE ||
           strrpos($url,"fath-news.com") !== FALSE ||
           strrpos($url,"almesryoon") !== FALSE 
           ) {
            $simpleXml = simplexml_load_string($fileContents,null,LIBXML_NOCDATA);  
        }
        else{
            if(strrpos($url,"eqtsad.net") === FALSE) {
               $simpleXml = simplexml_load_string($fileContents);
            }
        }
                    
         //   if (strpos($url, 'elkhabar.com') !== FALSE) {      echo('1111111111111111111111');
              //echo('11111111111'); pr($simpleXml); exit; 
         // } 
            
        if (!$array) {    
            $simpleXml = json_encode($simpleXml);
                       
            $rss_array = json_decode($simpleXml, TRUE);
            
            //pr($rss_array); exit; 
            
            if(strrpos($url,"dw.de") === FALSE) {
                if (isset($rss_array['channel'])) {
                    $items_arr = $rss_array['channel'];
                }
                else{ //for bbc
                    $items_arr = $rss_array;
                }
            }
            else{          
                $items_arr = $rss_array; 
            }
            
            $i = 0;
              // pr($items_arr); exit; 
             //  pr($items_arr['item']); exit; 
            
            if (isset($items_arr['item'])) { 
                foreach($items_arr['item'] as $rss) {  
                        //    pr($rss);
                    if (!isset($rss['pubDate']) && isset($rss['description'])) {
                        $rss_array['channel']['item'][$i]['pubDate'] = $rss['description'];
                        
                      /*  if(strrpos($url,"tayyar.org") !== FALSE) {

                            $image_splitter = explode("src=", $rss['description']);
                            
                            $image_splitter = explode(" ", $image_splitter[1]);
                            
                            $image_splitter1 = str_replace("'", "", $image_splitter[0]);
                            $image_splitter1 = str_replace('"', "", $image_splitter1);
                            
                            $rss_array['channel']['item'][$i]['image'][0] = $image_splitter1;

                        } */
                    } 
                    
                    //if (isset($rss['media_content'])) {
                    if (isset($rss['content'])) {
                        $rss_array['channel']['item'][$i]['image'][] = @$rss['content']['@attributes']['url'];                              
                        unset($rss_array['channel']['item'][$i]['content']);  
                    } 
                    
                    if (isset($rss['enclosure'])) {   
                        if (isset($rss['enclosure']['@attributes'])) {
                            $rss_array['channel']['item'][$i]['image'][] = $rss['enclosure']['@attributes']['url'];                              
                            unset($rss_array['channel']['item'][$i]['enclosure']); 
                        }
                    }
                    
                    //bbc 
                    if (isset($rss['title'])) {   
                        $rss_array['channel']['item'][$i]['title'] = $rss['title'];                              
                    }    
                    
                    if (isset($rss['thumbnail'])) {  
                        if (is_array($rss['thumbnail'])){
                            foreach($rss['thumbnail'] as $thumb) {    //pr($thumb);    exit;
                                 if (isset($thumb['@attributes'])) {
                                    $rss_array['channel']['item'][$i]['thumbs'][] = $thumb['@attributes']['url'];     
                                 }
                                 else{
                                     $rss_array['channel']['item'][$i]['thumbs'][] = $thumb['url']; 
                                 }               
                                           
                                 unset($rss_array['channel']['item'][$i]['thumbnail']); 
                            }
                        }    
                    }
                    
                          
                    if (!isset($rss['content']) && !isset($rss['enclosure'])) { 
                         if (!isset($rss_array['channel']['item'][$i]['image'])){
                            $rss_array['channel']['item'][$i]['image'][] = '';
                         }
                    } 
                    
                    //bbc 
                    if (isset($rss['link'])) {            
                         if(isset($rss['link']['@attributes'])) {
                             $rss_array['channel']['item'][$i]['link'] = $rss['link']['@attributes']['href']; 
                         }
                         else{
                              if (is_array(@$rss['link'])) {
                                  if (is_array(@$rss['link'][0])) {
                                      $rss_array['channel']['item'][$i]['link'] = @$rss['link'][0]['@attributes']['href']; 
                                  }
                                  else {
                                      $rss_array['channel']['item'][$i]['link'] = @$rss['link'][0]; 
                                  }
                              }
                              else{
                                  $rss_array['channel']['item'][$i]['link'] = $rss['link']; 
                              }
                         }
                             // pr($rss['link']);  
                         if (isset($rss['link']['content']['thumbnail'])) {  
                             if (isset($rss['link']['content']['thumbnail'][0])) {
                                 $rss_array['channel']['item'][$i]['image'][0] = $rss['link']['content']['thumbnail'][0]['img']['@attributes']['src'];
                             }
                             else {             
                                $rss_array['channel']['item'][$i]['image'][0] = $rss['link']['content']['thumbnail']['img']['@attributes']['src']; 
                             }
                         } 
                    }
                         
                    //$rss_array['channel']['item'][$i]['pubDate'] = "نشرت منذ " . time_elapsed_string(strtotime($rss_array['channel']['item'][$i]['pubDate']));   
                       
                    $i++;
                    
                    //echo('11111111111111<br />');
                    
                   // pr($rss_array); exit; 
                }
            }
              // pr($rss_array); exit;
            //$simpleXml = json_encode($rss_array);
              
            //echo('<pre>');print_R($rss_array); 
           // exit; 
        }
          
        //if (strpos($url, 'alwakeelnews') !== false)    pr($simpleXml);
           // pr($rss_array);exit;
        return $rss_array;

    }
    
}