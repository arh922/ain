<?php

class Validation {
    private $helper_obj;
    private $user_obj;
    
    function __construct() {
        $this->helper_obj = new Helper();
        $this->user_obj = new User();  
    }   
    
    function getErrors($postData,$rules){ 

        $errors = array();

        // validate each existing input
        foreach($postData as $name => $value){
          
            //if rule not found, skip loop iteration
            if(!isset($rules[$name])){
                continue;        
            }

            //convert special characters to HTML entities
            $fieldName = htmlspecialchars($name);

            $rule = $rules[$name];
              
            //check required values
            if(isset($rule['required']) && $rule['required'] && !$value){
                $errors[] = '<span class="error">'. $this->helper_obj->t('Field ') . $fieldName . $this->helper_obj->t(' is required.') . '</span>';
            }

            //check field's minimum length
            if(isset($rule['minlength']) && mb_strlen($value, 'UTF8') < $rule['minlength']){
                 $errors[] = '<span class="error">' . $fieldName.' should be at least ' . $rule['minlength'] . ' characters length.' . '</span>';     
            }
            
            //check field's maximum length
            if(isset($rule['maxlength']) && mb_strlen($value, 'UTF8') > $rule['maxlength']){
                 $errors[] = '<span class="error">' . $fieldName . ' should be at max ' . $rule['maxlength'] . ' characters length.' . '</span>';    
            }

            //verify email address     
            if(isset($rule['email']) && $rule['email'] && !filter_var($value,FILTER_VALIDATE_EMAIL)){
              $errors[] = '<span class="error">' . $fieldName . ' must be valid email address.' . '</span>';
            }
            
             //verify url address     
            if(isset($rule['url']) && $rule['url'] && !filter_var($value,FILTER_VALIDATE_URL)){
              $errors[] = '<span class="error">' . $fieldName . ' must be valid url address.' . '</span>';
            }  
                     
            $rules[$name]['found'] = true;

        }


        //check for missing inputs
        foreach($rules as $name => $values){
            if(!isset($values['found']) && isset($values['required']) && $values['required']){
              $errors[] = '<span class="error">' . 'Field '.htmlspecialchars($name).' is required.' . '</span>'; 
            }   
        }

        return $errors;
    }
    
}