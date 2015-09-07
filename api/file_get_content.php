<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

header('Content-Type: text/html; charset=utf-8'); 
//  $x = file_get_contents("http://www.alwakeelnews.com/index.php?page=article&id=87527#.Uw3HkYXbezw");
  
  //print_R(htmlspecialchars ($x, ENT_QUOTES, 'UTF-8'));

  //$fileContents= file_get_contents('http://www.youm7.com/story/2015/2/10/مشادة-كلامية-بين-حسام-غالى-ومدرب-الأهلى-بسبب-رمضان-صبحى/2061569#.VNndDfmulXE');
  
  //$fileContents= file_get_contents('http://www.alarabiya.net/.mrss/ar/arab-and-world.xml');
  $fileContents= fopen('http://basnews.com/ar/feed/', 'r');
       echo($fileContents);
  
    exit;                         
                      
         
  fopen("cookies1.txt", "w");
$url="http://www.jordanzad.com/index.php?page=article&id=201262";    

/*echo('<pre>');
print_R($_SERVER);
   exit;*/
$ch = curl_init();

$header=array('GET /1575051 HTTP/1.1',
'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*//**;q=0.8',
'Accept-Language:en-US,en;q=0.8',
'Cache-Control:max-age=0',
'Connection:keep-alive',
'User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64; rv:37.0) Gecko/20100101 Firefox/37.0 FirePHP/0.7.4',
);    
                                                     
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,0);
    curl_setopt( $ch, CURLOPT_COOKIESESSION, true );
   curl_setopt($ch, CURLOPT_TIMEOUT, 200);

    curl_setopt($ch,CURLOPT_COOKIEFILE,'cookies1.txt');
    curl_setopt($ch,CURLOPT_COOKIEJAR,'cookies1.txt');
    curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
    $fileContents=curl_exec($ch);

    if (curl_errno($ch))
{
 // echo this error
  echo curl_error($ch);

}
    curl_close($ch);
    
   echo($fileContents);  
?>