<?php
  class Validation_js {
      function add_client_validation(){
          global $add_client_validation;
          $helper_obj = new Helper();
          
          $output = "<script>$(document).ready(function(){
                        $('#add_client').validate(
                        {
                          'rules': " . json_encode($add_client_validation) . ",
                          'messages':{
                              'client_name' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                               'client_logo' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                          }
                        }); 
                     });</script>";
          return $output;
      }
      
      function edit_client_validation(){
          global $edit_client_validation;
          $helper_obj = new Helper();
          
          $output = "<script>$(document).ready(function(){
                        $('#edit_client').validate(
                        {
                          'rules': " . json_encode($edit_client_validation) . ",
                          'messages':{
                              'client_name_update' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                               'client_logo_update' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                          }
                        }); 
                     });</script>";
          return $output;
      }
      
      function edit_user_validation() {
          global $edit_user_validation;
          $helper_obj = new Helper();
          
          $output = "<script>$(document).ready(function(){
                        $('#edit_user').validate(
                        {
                          'rules': " . json_encode($edit_user_validation) . ",
                          'messages':{
                              'user_name_update' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                               'email_update' :{
                                'required': '" . $helper_obj->t('This field is required') . "',
                                'url': true        
                              },
                               'add_menu' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                          }
                        }); 
                     });</script>";
          return $output;
      }
      
      function add_user_validation(){
          global $add_user_validation;
          $helper_obj = new Helper();
          
          $output = "<script>$(document).ready(function(){
                        $('#add_user').validate(
                        {
                          'rules': {
                                        'username': { 'required':true},
                                        'email': { 'required':true, 'email': true, 'remote' : basePath + 'check_mail_available'},
                                        'password': { 'required':true},
                                        'phone': { 'required':true},
                                        'add_role': { 'required':true},
                                        'add_client': {
                                            'required': function(element) {
                                                            return (($('#add_role').val() != '1')?true:false);
                                            }             
                                        },
                                        'add_country': {
                                            'required': function(element) {
                                                            return (($('#add_role').val() != '1')?true:false);
                                            }             
                                        },
                                        
                                    }
                          ,
                          'messages':{
                              'username' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                               'password' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                              'add_client' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                              'add_country' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                              'email' :{
                                'required': '" . $helper_obj->t('This field is required') . "',        
                                'remote': '" . $helper_obj->t('Email address is exists!') . "'        
                              },
                              'phone' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                              'add_role' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                          }
                        }); 
                     });</script>";
          return $output;
      }
      
      function add_item_to_menu_validation() {
          global $add_item_to_menu_validation;
          $helper_obj = new Helper();
          
          $output = "<script>$(document).ready(function(){
                        $('#add_item_to_menu').validate(
                        {
                          'rules': " . json_encode($add_item_to_menu_validation) . ",
                          'messages':{
                              'menu_item_title' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                               'menu_item_url' :{
                                'required': '" . $helper_obj->t('This field is required') . "'     
                              },
                               'add_menu' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                          }
                        }); 
                     });</script>";
          return $output;
      }
      
      function add_video_validation(){
          global $add_video_validation;
          $helper_obj = new Helper();
          
          $output = "<script>$(document).ready(function(){
                        $('#add_video').validate(
                        {
                          'rules': " . json_encode($add_video_validation) . ",
                          'messages':{
                              'title' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                               'description' :{
                                'required': '" . $helper_obj->t('This field is required') . "'     
                              },
                               'add_category' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                              'video' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                              'image1' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                              'screenshot' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                              'add_pgrate' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                          }
                        }); 
                     });</script>";
          return $output;
      }
      
      function edit_video_validation() {
          global $edit_video_validation;
          $helper_obj = new Helper();
          
          $output = "<script>$(document).ready(function(){
                        $('#edit_video').validate(
                        {
                          'rules': " . json_encode($edit_video_validation) . ",
                          'messages':{
                              'title_updated' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                               'description_updated' :{
                                'required': '" . $helper_obj->t('This field is required') . "'     
                              },
                               'edit_category' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                              'edit_pgrate' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                          }
                        }); 
                     });</script>";
          return $output;
      }  
      
      function edit_article_validation() {
          global $edit_article_validation;
          $helper_obj = new Helper();
          
          $output = "<script>$(document).ready(function(){
                        $('#edit_video').validate(
                        {
                          'rules': " . json_encode($edit_article_validation) . ",
                          'messages':{
                              'title_updated' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                               'description_updated' :{
                                'required': '" . $helper_obj->t('This field is required') . "'     
                              },
                               'edit_category' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                              'edit_pgrate' :{
                                'required': '" . $helper_obj->t('This field is required') . "'        
                              },
                          }
                        }); 
                     });</script>";
          return $output;
      }
  }