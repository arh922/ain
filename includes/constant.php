<?php
if ($dir = trim(dirname($_SERVER['SCRIPT_NAME']), '\,/')) {
    $base_path = "/$dir";
    $base_path .= '/';
}
else {
    $base_path = '/';
}

define("THUMBNAIL_IMG_PATH", $base_path . "img/thumbnail/");
define("THUMBNAIL_IMG_PATH_UPLOAD", "img/thumbnail/");

define("IMG_PATH",  $base_path . "img/");
define("IMG_PATH_UPLOAD",  "img/");

define("LOGO_PATH_UPLOAD",  "img/logos/");
define("LOGO_PATH", $base_path . "img/logos/");


//roles
define("SUPER_ADMIN_ROLE_ID", 1);
define("CLIENT_ROLE_ID", 2);
define("REQULAR_USER_ROLE_ID", 3);

define("MAINTENANCE", 0);

define("SAVE_USERDATA_COOKIE_SEPARATER", 'b5tu2grefo28');

define("DATE_FORMAT", 'Y-m-d');

define("LENGTH_IMG_PATH", 6);

define("MENU_TITLE_ID_SEPARATOR", '**##*');

define("VIDEOS_PATH", $base_path . 'videos/');
define("VIDEOS_PATH_UPLOAD", 'videos/');

define("VIDEOS_IMAGES_PATH", 'https://console.developers.google.com/m/cloudstorage/b/cosmic-descent-775.appspot.com/o/article/');
define("VIDEOS_IMAGES_PATH_UPLOAD", 'img/video_images/');

//define("VIDEOS_IMAGES_FEATURE_PATH", $base_path . 'img/video_images_feature/');
define("VIDEOS_IMAGES_FEATURE_PATH", 'https://console.developers.google.com/m/cloudstorage/b/cosmic-descent-775.appspot.com/o/feature/');
define("VIDEOS_IMAGES_PATH_FEATURE_UPLOAD", 'img/video_images_feature/');

//define("VIDEOS_IMAGES_THUMBNAIL_PATH", $base_path . 'img/video_images_thumbnail/');
define("VIDEOS_IMAGES_THUMBNAIL_PATH", 'https://console.developers.google.com/m/cloudstorage/b/cosmic-descent-775.appspot.com/o/thumbnail/');
define("VIDEOS_IMAGES_THUMBNAIL_PATH_UPLOAD", 'img/video_images_thumbnail/');

define("VIDEOS_IMAGES_THUMBNAIL_320x272_PATH", $base_path . 'img/video_images_thumbnail/320x272/');
define("VIDEOS_IMAGES_THUMBNAIL_320x272_PATH_UPLOAD", 'img/video_images_thumbnail/320x272/');

define("VIDEOS_IMAGES_THUMBNAIL_320x352_PATH", $base_path . 'img/video_images_thumbnail/320x352/');
define("VIDEOS_IMAGES_THUMBNAIL_320x352_PATH_UPLOAD", 'img/video_images_thumbnail/320x352/');

define("CATEGORIES_IMAGES_PATH", 'https://console.developers.google.com/m/cloudstorage/b/cosmic-descent-775.appspot.com/o/cat/');
define("CATEGORIES_IMAGES_PATH_UPLOAD", 'https://console.developers.google.com/m/cloudstorage/b/cosmic-descent-775.appspot.com/o/cat/');

define("TAGS_IMAGES_PATH", $base_path . 'https://console.developers.google.com/m/cloudstorage/b/cosmic-descent-775.appspot.com/o/tags/');
define("TAGS_IMAGES_PATH_UPLOAD", 'https://console.developers.google.com/m/cloudstorage/b/cosmic-descent-775.appspot.com/o/tags/');

define("TAGS_THUMBNAIL_IMAGES_PATH", $base_path . 'img/tags/thumbnail/');
define("TAGS_THUMBNAIL_IMAGES_PATH_UPLOAD", 'img/tags/thumbnail/');

define("IMAGE_320x272_W", '320');
define("IMAGE_320x272_H", '272');

define("IMAGE_320x352_W", '320');
define("IMAGE_320x352_H", '352');

define("THUMBNAIL_W", '100');
define("FEATURE_W", '320');

define("THUMBNAIL_H", '100');
define("FEATURE_H", '210');

define("THUMBNAIL_R", '100');
define("THUMBNAIL_ENABLE", '1');

define("PASSWORD_MIN_LENGTH", '6');

define("IMG_WIDTH_EDIT_VID", '50');
define("IMG_HIEGHT_EDIT_VID", '50');

define("CATEGORY_IMAGE_W", '200');
define("CATEGORY_IMAGE_H", '200');

define("TAG_THUMBNAIL_IMAGE_W", '200');
define("TAG_THUMBNAIL_IMAGE_H", '200');

define("TAG_IMAGE_W", '400');
define("TAG_IMAGE_H", '200');

define("SQLBOX", '1');

define("REPAYMENT_TRIES", 10);

define("PAYMENT_END_DATE", 604800);

define("SMTP_USERNAME", 'azure_4116bfde5812399ccde7dabb32bd8c3e@azure.com');
define("SMTP_PASSWORD", 't5U2a39LayOuZUT');
define("SMTP_HOSTNAME", 'smtp.sendgrid.net');

define("MAIN_PATH", "index.php?q=");

define("GOOGLE_APP_ID", "cosmic-descent-775.appspot.com");

define("TIME_TO_DELETE_NEWS", 10); //in days