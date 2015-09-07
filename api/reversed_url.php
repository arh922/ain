<?php

function reverse_tinyurl($url) {      
    $org_url = $url;
    
    if (strrpos($url,"dw.de") !== FALSE) { 
        return $url;      
    } 
    
    $ch = curl_init("$url");  
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $yy = curl_exec($ch);
    curl_close($ch);
    $w = explode("\n",$yy);
    $TheShortURL = array_values( preg_grep( '/' . str_replace( '*', '.*', 'Location' ) . '/', $w ) );
    $url = @$TheShortURL[0];
    $url = str_replace("Location:", "", "$url");
    $url = trim("$url");
    
    if ($url == "" || filter_var($url, FILTER_VALIDATE_URL) === false) {
        $url = $org_url;
    }
    
    return $url;
}


echo('1'.reverse_tinyurl('www.aljazeera.net/news/healthmedicine/2015/4/21/%D8%A7%D9%84%D8%A3%D9%83%D9%84-%D9%84%D9%8A%D9%84%D8%A7-%D9%8A%D8%B2%D9%8A%D8%AF-%D9%85%D8%AE%D8%A7%D8%B7%D8%B1-%D8%A7%D9%84%D8%B3%D9%83%D8%B1%D9%8A-%D9%88%D8%B3%D8%B1%D8%B7%D8%A7%D9%86-%D8%A7%D9%84%D8%AB%D8%AF%D9%8A'));