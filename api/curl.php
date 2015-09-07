<?php
ini_set('extension', 'php_openssl.dll');
ini_set('allow_url_include', 'on');
ini_set('allow_url_fopen', 'on')     ;

function exec_curl($url){    //echo($url.'<br /><br />');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HEADER, 0); 
  curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, true); 
  curl_setopt($ch, CURLOPT_REFERER, 'http://www.aljazeera.net');

  curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
  curl_setopt($ch, CURLOPT_VERBOSE, TRUE); // Display communication with server
  $status = curl_exec($ch);
          // echo('$status: ' . $status);
  curl_close($ch);   
 
  return $status;   
}

/* Resolve Short URL */
function resolveShortURL($url) {
    $ch = curl_init("$url");  
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $yy = curl_exec($ch);
    curl_close($ch);
      $w = explode("\n",$yy);
      $TheShortURL = array_values( preg_grep( '/' . str_replace( '*', '.*', 'Location' ) . '/', $w ) );
      $url = $TheShortURL[0];
      $url = str_replace("Location:", "", "$url");
      $url = trim("$url");
    return $url;
}

echo resolveShortURL('http://bit.ly/19CPdEX');
//echo exec_curl('http://www.aljazeera.net/news/scienceandtechnology/2015/3/31/%D8%B3%D8%A7%D9%85%D8%B3%D9%88%D9%86%D8%BA-%D9%88%D8%A5%D9%84-%D8%AC%D9%8A-%D8%AA%D8%AA%D8%B5%D8%A7%D9%84%D8%AD%D8%A7%D9%86');