<?php
 $id = isset($_GET['id']) ? $_GET['id'] : 'xxx';
 $news = file_get_contents('http://api.ainnewsapp.com/api/api/index.php?action=get_article&aid=' . $id . '&cid=47&uid=123');
 $news = json_decode($news);

// echo('<pre>');    print_R($news);exit;


$source = explode(":",$news->cats_name);
$source_id = $source[0];
$source_name = $source[1];

//Detect special conditions devices 
$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
$webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
?> 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>تطبيق عين الاخباري | <?php echo $news->title; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="keywords" content="HTML5 Template">
        <meta name="description" content="Mist — Multi-Purpose HTML Template">
        <meta name="author" content="zozothemes.com">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Favicon -->
        <link rel="shortcut icon" href="http://ainnewsapp.com/images/shorticon.ico">
        <!-- Font -->
        <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Arimo:300,400,700,400italic,700italic'>
        <link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>
        <!-- Font Awesome Icons -->
        <link href='news/css/font-awesome.min.css' rel='stylesheet' type='text/css'/>
        <!-- Bootstrap core CSS -->
        <link href="news/css/bootstrap.min.css" rel="stylesheet">
        <link href="news/css/hover-dropdown-menu.css" rel="stylesheet">
        <!-- Icomoon Icons -->
        <link href="news/css/icons.css" rel="stylesheet">
        <!-- Revolution Slider -->
<!--        <link href="news/css/revolution-slider.css" rel="stylesheet">-->
<!--        <link href="rs-plugin/news/css/settings.css" rel="stylesheet">-->
        <!-- Animations -->
<!--        <link href="news/css/animate.min.css" rel="stylesheet">-->
        <!-- Owl Carousel Slider -->
<!--        <link href="news/css/owl/owl.carousel.css" rel="stylesheet" >-->
<!--        <link href="news/css/owl/owl.theme.css" rel="stylesheet" >-->
<!--        <link href="news/css/owl/owl.transitions.css" rel="stylesheet" >-->
        <!-- PrettyPhoto Popup -->
<!--        <link href="news/css/prettyPhoto.css" rel="stylesheet">-->
        <!-- Custom Style -->
        <link href="news/css/style.css" rel="stylesheet">
       <link href="news/css/responsive.css" rel="stylesheet" />
        <!-- Color Scheme -->
        <link href="news/css/color.css" rel="stylesheet">
        <link href="news/css/jquery.dialogbox.css" rel="stylesheet">
        
        <style>
/*        .dialog-box-title{height: 0;}*/
/*        .dialog-box-content{padding: 0; padding-left: 10px; padding-right: 20px; padding-bottom: 20px;}*/     
        </style>
    </head>
    <body>
        <div id="page">
            <!-- Top Bar -->
            <div id="top-bar" class="top-bar-section top-bar-bg-color" style="padding: 0">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- Top Contact -->
                            <div class="top-contact link-hover-black">
                                <img style="-webkit-filter: brightness(0) invert(1);filter: brightness(0) invert(1);" width="70" src="http://ainnewsapp.com/images/Ain-Logo.png" />
                            </div>
                            <!-- Top Social Icon -->
                            <div class="top-social-icon icons-hover-black">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Top Bar -->
            
            <?php
            if( $iPod || $iPhone || $iPad || $Android){
                 if( $iPod || $iPhone || $iPad){
                    $url = "https://itunes.apple.com/us/app/yn/id967997675?mt=8";
                 }
                 else if ($Android) {
                     $url = "";
                 }
            ?>
                <div style="font-family: HelveticaNeueLTW20-bold; margin: 30px auto; width: 90%; background: #4bc1d2; text-align: center; padding-left: 7px; padding-right: 7px">
                    <span style="color: white;">تم نشر هذا الخبر عبر تطبيق عين الاخباري</span><br />
                    <span style="color: white"><a style="color: white" href="<?php echo @$url; ?>">اضغط هنا لتحميل تطبيق عين الاخباري مجانا واحصل على آخر الاخبار</a></span>
                </div>
            
            <?php
            }
            ?>
            
            <div class="page-header">
                <div class="container">
                    <h1 class="title" style="text-align: right; font-size: 18px"><?php echo $news->title; ?></h1>
                    <span style="font-size: 13px; color: #969696; float: right; text-align: right"><?php echo $source_name ?></span>
                    <span style="width: 20px; text-align: center; float: right;">-</span>
                    <span style="font-size: 13px; color: #C0C0C0; float: right; text-align: right"><?php echo $news->date_added ?></span>
                </div>
                
            </div>
            <!-- page-header -->
            <section class="page-section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="post-image opacity"><img src="<?php echo $news->image; ?>" width="1170" height="382" alt="" title=""></div>
                            <div class="post-content top-pad-20" style="text-align: right">
                               <?php  
                               if (
                                     $news->body != 'url not set' &&
                                     $news->body != 'go to fb' &&
                                     $news->body != 'go to youtube'
                                  ) {
                                   echo $news->body; 
                               }
                               else{
                                   if ($news->body == 'go to youtube') {
                                       if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $news->reversed_url, $id)) {
                                          $values = $id[1];
                                        } else if (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $news->reversed_url, $id)) {
                                          $values = $id[1];
                                        } else if (preg_match('/youtube\.com\/v\/([^\&\?\/]+)/', $news->reversed_url, $id)) {
                                          $values = $id[1];
                                        } else if (preg_match('/youtu\.be\/([^\&\?\/]+)/', $news->reversed_url, $id)) {
                                          $values = $id[1];
                                        }
                                        else if (preg_match('/youtube\.com\/verify_age\?next_url=\/watch%3Fv%3D([^\&\?\/]+)/', $news->reversed_url, $id)) {
                                            $values = $id[1];
                                        } else {   
                                        // not an youtube video
                                        }           
                                        
                                        echo('<iframe width="100%" height="450" src="' . 'https://www.youtube.com/embed/' . $values . '" frameborder="0" allowfullscreen></iframe>');
                                   }
                                   
                                   elseif ( $news->body != 'url not set') {
                                       echo ("<iframe src='" . $news->reversed_url . "' width='1170' height='400'></iframe>");
                                   }
                               }
                               
                               ?>
                            </div> 
                        </div>        
                    </div>
                    <hr>
                    
                </div>
            </section>
               
            <!-- page-section -->
            <div id="get-quote" class="bg-color get-a-quote black text-center" data-appear-animation="fadeInUp">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                             if( $iPod || $iPhone || $iPad){
                                 $device = "للأي فون";
                                 echo ('<a target="_blank" href="https://itunes.apple.com/us/app/yn/id967997675?mt=8"><img width="200" src="http://www.ibreviary.com/new/images/app_store_badge.png" /></a>');

                             }else if($Android){
                                 $device = "للأندرويد"; 
                                echo ('<a target="_blank" href=""><img width="200" src="http://www.reontech.is/img/apps/playbadge.png" /></a>');
                             }
                             else{
                                  echo ('<a target="_blank" href="https://itunes.apple.com/us/app/yn/id967997675?mt=8"><img width="200" src="http://www.ibreviary.com/new/images/app_store_badge.png" /></a>');
                                  echo ('&nbsp;&nbsp;&nbsp;&nbsp;');
                                  echo ('<a target="_blank" href=""><img width="200" src="http://www.reontech.is/img/apps/playbadge.png" /></a>');
                             }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- request -->
            <footer id="footer">
                
                <!-- footer-top -->
                <div class="copyright">
                    <div class="container">
                        <div class="row">
                            <!-- Copyrights -->
                            <div class="col-xs-10 col-sm-6 col-md-6"> &copy; <?php echo date('Y'); ?> <a target="_blank" href="http://www.arabmobilecontent.com">arabmobilecontent.com</a>.
                            <br>
                                <!-- Terms Link -->
<!--                                <a href="#">Terms of Use</a> / <a href="#"> Privacy Policy</a>-->
                            </div>
                            <div class="col-xs-2  col-sm-6 col-md-6 text-right page-scroll gray-bg icons-circle i-3x">
                                <!-- Goto Top -->
                                <a href="#page">
                                <i class="glyphicon glyphicon-arrow-up"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- footer-bottom -->
            </footer>
            <!-- footer -->
        </div>
            
        <!-- page -->
        <!-- Scripts -->
        <script type="text/javascript" src="news/js/jquery.min.js"></script>
        <script type="text/javascript" src="news/js/bootstrap.min.js"></script>
        <!-- Menu jQuery plugin -->
        <script type="text/javascript" src="news/js/hover-dropdown-menu.js"></script>
        <!-- Menu jQuery Bootstrap Addon -->    
        <script type="text/javascript" src="news/js/jquery.hover-dropdown-menu-addon.js"></script>    
        <!-- Scroll Top Menu -->
        <script type="text/javascript" src="news/js/jquery.easing.1.3.js"></script>
        <!-- Sticky Menu -->    
        <script type="text/javascript" src="news/js/jquery.sticky.js"></script>
        <!-- Bootstrap Validation -->
        <script type="text/javascript" src="news/js/bootstrapValidator.min.js"></script>    
        <!-- Animations -->
        <script type="text/javascript" src="news/js/jquery.appear.js"></script>
        <script type="text/javascript" src="news/js/effect.js"></script>
        <!-- Parallax BG -->
        <script type="text/javascript"  src="news/js/jquery.parallax-1.1.3.js"></script>
        <!-- Fun Factor / Counter -->
        <script type="text/javascript"  src="news/js/jquery.countTo.js"></script>
        <!-- Custom Js Code -->
        <script type="text/javascript" src="news/js/custom.js"></script>
        <script type="text/javascript" src="news/js/jquery.dialogBox.js"></script>
        <!-- Scripts -->
        
        <?php
         if( $iPod || $iPhone || $iPad){
        ?>
            <div id="simple-dialogBox"></div>
            <script>
            function closebtn(){      
                $('.dialog-box-content').hide();
                $('#dialog-box-mask').hide();
            }
            $(document).ready(function() {  
                $('#simple-dialogBox').dialogBox({
/*                        title: ' ',*/
                        hasClose: true,
                        hasMask: true,
                        content: "<div style='text-align:center; margin: auto; width: 100%;'><div style='text-align: center; margin: -30px auto auto; width: 65%;'><img width='100' src='http://ainnewsapp.com/images/Ain-Logo.png'></div><div style='font-size: 20px; font-family: HelveticaNeueLTW20-bold;'>تطبيـــق 'عيــن' للأيـــفون</div> <br /> <div style='font-size: 18px; font-family: HelveticaNeueLTW20-ligh;  color: red'>مجانا اليوم! حمّل التطبيق الآن </div><br /><a target='_blank' href='https://itunes.apple.com/us/app/yn/id967997675?mt=8'><img width='150' src='http://www.ibreviary.com/new/images/app_store_badge.png' /></a> <br /> <br /><a target='_blank' href='https://itunes.apple.com/us/app/yn/id967997675?mt=8'><div style='height: 45px; font-size: 14px; border-radius: 20px 20px 20px 20px; background: #4bc1d2; color: white; font-family: HelveticaNeueLTW20-ligh; line-height: 37px;'>حمّل التطبيق مجانا الآن</div></a><div style='font-size: 14px; color: #4bc1d2; font-family: HelveticaNeueLTW20-ligh; line-height: 37px; cursor: pointer' onclick='closebtn();'>اغلاق</div></div>"
                });
            });
            </script>
        <?php
         }
         else if ($Android){
        ?>
            <div id="simple-dialogBox"></div>
            <script>
            function closebtn(){      
                $('.dialog-box-content').hide();
                $('#dialog-box-mask').hide();
            }
            $(document).ready(function() {  
                $('#simple-dialogBox').dialogBox({
/*                        title: ' ',*/
                        hasClose: true,
                        hasMask: true,
                        content: "<div style='text-align:center; margin: auto; width: 100%;font-family: HelveticaNeueLTW20-bold;'><div style='text-align: center; margin: -30px auto auto; width: 65%;'><img width='100' src='http://ainnewsapp.com/images/Ain-Logo.png'></div><div style='font-size: 20px; font-family: HelveticaNeueLTW20-bold;'>تطبيـــق 'عيــن' للأنـــدرويد </div><br /><div style='font-size: 18px; font-family: HelveticaNeueLTW20-ligh; color: red'> مجانا اليوم! حمّل التطبيق الآن </div><br /> <a target='_blank' href=''><img width='150' src='http://www.reontech.is/img/apps/playbadge.png' /></a> <br /> <br /><a target='_blank' href=''><div style='height: 45px; font-size: 14px; border-radius: 20px 20px 20px 20px; background: #4bc1d2; color: white; font-family: HelveticaNeueLTW20-ligh; line-height: 37px;'>حمّل التطبيق مجانا الآن</div></a><div style='font-size: 14px; color: #4bc1d2; font-family: HelveticaNeueLTW20-ligh; line-height: 37px; cursor: pointer' onclick='closebtn();'>اغلاق</div></div>"
                });
            });
            </script>
        <?php
         }
        ?>

    </body>
</html>