<?php
//echo('2222222222');
//start register session
session_start();
//   echo('3333333333');  
//require_once 'google/appengine/api/cloud_storage/CloudStorageTools.php';
//use google\appengine\api\cloud_storage\CloudStorageTools;
   
include("conf.php");
include("constant.php");
   //  echo('444444444444');  
include("classes/MySQLiDatabaseConnection.php");
include("classes/helper.php");
        //    echo('55555555');  
$q = isset($_GET['q'])? $_GET['q'] : "";
$q = trim($q);
     
include("translation.php");     
   
//classes
include("classes/db_functions.php"); 
include("classes/user.php"); 
include("validation_rules.php");
include("classes/validation.php");  
include("classes/validation_js.php");  

include ("bootstrap.php");     
include("classes/controller.php");  
include("classes/encryption.php"); 
include("classes/video.php"); 
include("classes/article.php"); 
include("classes/menu.php"); 
include("classes/client.php"); 
include("classes/category.php"); 
include("classes/pgrate.php"); 
                                       
//include("classes/currency.php");   
include("classes/operator.php"); 
include("classes/payment.php"); 
include("classes/rss_source.php"); 
include("classes/zodiac.php"); 
include("classes/happened_in_this_day.php"); 
include("classes/emergency_calls.php"); 
include("classes/tag.php"); 
include("classes/push_notification.php"); 
     // echo('q1:' . $q.'<br />');
$controller = new controller($q);
         //   echo('q2:' . $q);           
$head_title = $head_title . ' | ' . $helper_obj->t('Streaming');   
$final_title =  $head_title;
                            
?>
<!doctype html>
<html>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Apple devices fullscreen -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- Apple devices fullscreen -->
    <meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    
    <title><?php echo $final_title; ?></title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/bootstrap.min.css">
    <!-- Bootstrap responsive -->
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/bootstrap-responsive.min.css">
    <!-- jQuery UI -->
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/plugins/jquery-ui/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/plugins/jquery-ui/smoothness/jquery.ui.theme.css">
    <!-- PageGuide -->
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/plugins/pageguide/pageguide.css">
    <!-- Fullcalendar -->
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/plugins/fullcalendar/fullcalendar.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/plugins/fullcalendar/fullcalendar.print.css" media="print">
    <!-- chosen -->
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/plugins/chosen/chosen.css">
    <!-- select2 -->
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/plugins/select2/select2.css">
    <!-- icheck -->
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/plugins/icheck/all.css">
    <!-- Theme CSS -->
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/style.css">
    <!-- Color CSS -->
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/themes.css">


    <!-- jQuery -->
    <script src="<?php echo $base_path; ?>js/jquery.min.js"></script>
    
    <script>
        var basePath = '<?php echo $base_path; ?>';
    </script>
    
    <!-- Nice Scroll -->
    <script src="<?php echo $base_path; ?>js/plugins/nicescroll/jquery.nicescroll.min.js"></script>
    <!-- jQuery UI -->
    <script src="<?php echo $base_path; ?>js/plugins/jquery-ui/jquery.ui.core.min.js"></script>
    <script src="<?php echo $base_path; ?>js/plugins/jquery-ui/jquery.ui.widget.min.js"></script>
    <script src="<?php echo $base_path; ?>js/plugins/jquery-ui/jquery.ui.mouse.min.js"></script>
    <script src="<?php echo $base_path; ?>js/plugins/jquery-ui/jquery.ui.draggable.min.js"></script>
    <script src="<?php echo $base_path; ?>js/plugins/jquery-ui/jquery.ui.resizable.min.js"></script>
    <script src="<?php echo $base_path; ?>js/plugins/jquery-ui/jquery.ui.sortable.min.js"></script>
    <!-- Touch enable for jquery UI -->
    <script src="<?php echo $base_path; ?>js/plugins/touch-punch/jquery.touch-punch.min.js"></script>
    <!-- slimScroll -->
    <script src="<?php echo $base_path; ?>js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo $base_path; ?>js/bootstrap.min.js"></script>
    <!-- vmap -->
    <script src="<?php echo $base_path; ?>js/plugins/vmap/jquery.vmap.min.js"></script>
    <script src="<?php echo $base_path; ?>js/plugins/vmap/jquery.vmap.world.js"></script>
    <script src="<?php echo $base_path; ?>js/plugins/vmap/jquery.vmap.sampledata.js"></script>
    <!-- Bootbox -->
    <script src="<?php echo $base_path; ?>js/plugins/bootbox/jquery.bootbox.js"></script>
    <!-- Flot -->
    <script src="<?php echo $base_path; ?>js/plugins/flot/jquery.flot.min.js"></script>
    <script src="<?php echo $base_path; ?>js/plugins/flot/jquery.flot.bar.order.min.js"></script>
    <script src="<?php echo $base_path; ?>js/plugins/flot/jquery.flot.pie.min.js"></script>
    <script src="<?php echo $base_path; ?>js/plugins/flot/jquery.flot.resize.min.js"></script>
    <!-- imagesLoaded -->
    <script src="<?php echo $base_path; ?>js/plugins/imagesLoaded/jquery.imagesloaded.min.js"></script>
    <!-- PageGuide -->
    <script src="<?php echo $base_path; ?>js/plugins/pageguide/jquery.pageguide.js"></script>
    <!-- FullCalendar -->
    <script src="<?php echo $base_path; ?>js/plugins/fullcalendar/fullcalendar.min.js"></script>
    <!-- Chosen -->
    <script src="<?php echo $base_path; ?>js/plugins/chosen/chosen.jquery.min.js"></script>
    <!-- select2 -->
    <script src="<?php echo $base_path; ?>js/plugins/select2/select2.min.js"></script>
    <!-- icheck -->
    <script src="<?php echo $base_path; ?>js/plugins/icheck/jquery.icheck.min.js"></script>

    <!-- Theme framework -->
    <script src="<?php echo $base_path; ?>js/eakroko.min.js"></script>
    <!-- Theme scripts -->
    <script src="<?php echo $base_path; ?>js/application.min.js"></script>
    <!-- Just for demonstration -->
    <script src="<?php echo $base_path; ?>js/demonstration.min.js"></script>
    
    <script type="text/javascript" src="<?php echo $base_path?>js/jquery.leanModal.min.js"></script>  
    
    <script type="text/javascript" src="<?php echo $base_path?>js/jquery.form.min.js"></script>
    
    <script type="text/javascript" src="<?php echo $base_path?>js/submit_forms.js"></script>  
    
    <!--AMC-->
    <script type="text/javascript" src="<?php echo $base_path?>js/streaming.js?eyyu"></script>    
    
    <!--[if lte IE 9]>
        <script src="js/plugins/placeholder/jquery.placeholder.min.js"></script>
        <script>
            $(document).ready(function() {
                $('input, textarea').placeholder();
            });
        </script>
    <![endif]-->

    <!-- Favicon -->
    <link rel="shortcut icon" href="img/favicon.ico" />
    <!-- Apple devices Homescreen icon -->
    <link rel="apple-touch-icon-precomposed" href="<?php echo $base_path; ?>img/apple-touch-icon-precomposed.png" />
    
              
</head>
<body>