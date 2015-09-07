<script>
$(document).ready(function(){
    $('#my-form').validate(
    {
      'rules': <?php echo json_encode($validation_rules); ?>,
      'messages':{
          'title' :{
            'required': '<?php echo $helper_obj->t('This field is required'); ?>',
            'minlength': '<?php echo $helper_obj->t('Please enter at least {0} characters.'); ?>',
            'maxlength': '<?php echo $helper_obj->t('Please enter no more than {0} characters'); ?>', 
            'email': '<?php echo $helper_obj->t('Please enter a valid email address'); ?>', 
            'remote':  '<?php echo $helper_obj->t('User name is already exists!'); ?>'         
          },
      }
    }); 
       
   
    $('#image-form-upload').validate({
      'rules': {'title': {
                    'required': true,
                    'maxlength': <?php echo 33; ?>,
                },
                'upload-type': {
                    'required': true,
                },
                'url_path': {
                    'url':true,
                    'required': function(element) {
                                    return (($('.upload-url-img').val() == 'true')?true:false);
                    },
                    'remote': basePath + 'check_if_url_is_img'               
                },
                'file': {          
                    'required': function(element) {
                                    return (($('.upload-file-img').val() == 'true')?true:false);
                    }            
                }
      },
      'messages':{
                'title': {
                    'required': '<?php echo $helper_obj->t('This field is required'); ?>',
                    'maxlength': '<?php echo $helper_obj->t('Please enter no more than {0} characters'); ?>',
                },
                'upload-type': {
                    'required': '<?php echo $helper_obj->t('This field is required'); ?>',
                }, 
                'file': {
                    'required': '<?php echo $helper_obj->t('This field is required'); ?>',
                }, 
                'url_path': {
                    'required': '<?php echo $helper_obj->t('This field is required'); ?>',
                    'remote': '<?php echo $helper_obj->t('Images allowed only'); ?>'
                }
      } 
    });
         
    

});
</script>
