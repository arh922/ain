<?php

global $base_path, $add_client_validation, $edit_client_validation, $add_user_validation, $edit_user_validation, $add_item_to_menu_validation, 
        $edit_video_validation, $add_currency_validation, $edit_currency_validation, $edit_article_validation;


$add_client_validation = array(
  'client_name' => array(  
    'required' => true,
  ),                 
  'client_logo' => array(  
    'required' => true,
  )
);

$edit_client_validation = array(
  'client_name_update' => array(  
    'required' => true,
  )
);

$edit_user_validation = array(
      'user_name_update' => array(  
        'required' => true,
      ),
      'email_update' => array(  
        'required' => true,
        'email' => true
      ),
      'phone_update' => array(  
        'required' => true,
      ),
);

$add_user_validation = array(
  'username' => array(  
    'required' => true,
  ),
  'username' => array(  
    'required' => true,
  ),
  'password' => array(  
    'required' => true,
  ),
  'email' => array(  
    'required' => true,
  ),
  'phone' => array(  
    'required' => true,
  ),
  'add_role' => array(  
    'required' => true,
  )
);

$add_item_to_menu_validation = array(
  'menu_item_title' => array(  
    'required' => true,
  ),
  'menu_item_url' => array(  
    'required' => true,
  ),
  'add_menu' => array(  
    'required' => true,
  )
);

$add_video_validation = array(
  'title' => array(  
    'required' => true,
  ),
  'description' => array(  
    'required' => true,
  ),
  'add_category' => array(  
    'required' => true,
  ),
  'image1' => array(  
    'required' => true
  ),
  'screenshot' => array(  
    'required' => true,
  ),
  'add_pgrate' => array(  
    'required' => true,
  ),
  'video' => array(  
    'required' => true,
  )
);

$edit_video_validation = array(
  'title_updated' => array(  
    'required' => true,
  ),
  'description_updated' => array(  
    'required' => true,
  ),
  'edit_category' => array(  
    'required' => true,
  ),
  'edit_pgrate' => array(  
    'required' => true,
  )
);

$edit_article_validation = array(
  'title_updated' => array(  
    'required' => true,
  ),
  'text1_updated' => array(  
    'required' => true,
  ),
  'edit_category' => array(  
    'required' => true,
  ),
  'edit_pgrate' => array(  
    'required' => true,
  )
);

$add_currency_validation = array(
  'currency_name' => array(  
    'required' => true,
  ),                 
  'currency_code' => array(  
    'required' => true,
  ) ,
  'currency_sign' => array(  
    'required' => true,
  )
);

$add_operator_validation = array(
  'operator_name' => array(  
    'required' => true,               
  )
);