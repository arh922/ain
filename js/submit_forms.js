// wait for the DOM to be loaded 
$(document).ready(function() { 
    // bind 'myForm' and provide a simple callback function 
    $('#add_client').ajaxForm(function(res) { 
        var isvalid = $("#add_client").valid();
        if (isvalid) { 
            $('#add_client_table tr:first').after(res);
            $("#add_client_reset").click();  
        }
    });
      
    $('#add_user').ajaxForm(function(res) { 
        var isvalid = $("#add_user").valid();
 
        if (isvalid) { 
            $('#add_user_table tr:first').after(res);
            $("#add_user_reset").click(); 
        }
    });  
    
    $('#add_item_to_menu').ajaxForm(function(res) { 
        var isvalid = $("#add_item_to_menu").valid();
            
        if (isvalid) {    
            $("#add_item_to_menu_reset").click();
            $('#all_menu').html(res); 
        }
    }); 
    
    $('#add_video').ajaxForm(function(res) { 
        var isvalid = $("#add_video").valid();
               
        if (isvalid) {    
            $("#add_video_reset").click();
            $('#add_video_table tr:first').after(res);
        }
    }); 
    
    $('#add_article').ajaxForm(function(res) { 
        var isvalid = $("#add_article").valid();
                
        if (isvalid) {    
            $("#add_article_reset").click();
            $('#add_article_table tr:first').after(res);
        }
    });
    
    $('#add_article_html').ajaxForm(function(res) { 
        var isvalid = $("#add_article_html").valid();
                
        if (isvalid) {    
            $("#add_article_html_reset").click();
            $('#add_article_html_table tr:first').after(res);
        }
    }); 
    
    $('#add_pgrate').ajaxForm(function(res) { 
        var isvalid = $("#add_pgrate").valid();
        
        if (isvalid) {    
            $("#add_pgrate_reset").click();
            $('#add_pgrate_table tr:first').after(res);
        }
    }); 
    
    $('#add_category').ajaxForm({
        
        beforeSend: function(){          
            $("#loader").css("left", "50%");
            $("#loader").css("top", "50%");
            $("#loader").css("display", "block");
        },   
        success: function(){
            //alert('success');
        },
        complete: function(res){      
            $("#loader").css("display", "none");
            $("#resp").html('done'); 
            
            $("#add_category_reset").click();
            $('#add_category_table tr:first').after(res.responseText);
        }
    });  
    
    $('#add_related_tag').ajaxForm({
        
        beforeSend: function(){          
            $("#loader").css("left", "50%");
            $("#loader").css("top", "50%");
            $("#loader").css("display", "block");
        },   
        success: function(){
            //alert('success');
        },
        complete: function(res){      
            $("#loader").css("display", "none");
           
           // $("#add_tag_reset").click();
            
            $("#synonyms_table").append(res.responseText);
           
        }
    });  
    
    $('#add_source').ajaxForm(function(res) { 
        var isvalid = $("#add_source").valid();
        
        if (isvalid) {    
            $("#add_source_reset").click();
            $('#add_source_table tr:first').after(res);
        }
    });
    
    $('#add_tag').ajaxForm(function(res) { 
        var isvalid = $("#add_tag").valid();
        
        if (isvalid) {    
            $("#add_tag_reset").click();
            $('#add_tag_table tr:first').after(res);
        }
    });
    
    $('#add_hitd').ajaxForm(function(res) { 
        var isvalid = $("#add_hitd").valid();
        
        if (isvalid) {    
            $("#add_hitd_reset").click();
            $('#add_hitd_table tr:first').after(res);
        }
    });
    
    $('#add_emergency').ajaxForm(function(res) { 
        var isvalid = $("#add_emergency").valid();
        
        if (isvalid) {    
            $("#add_emergency_reset").click();
            $('#add_emergency_table tr:first').after(res);
        }
    });
    
    $('#add_zodiac').ajaxForm(function(res) { 
        var isvalid = $("#add_zodiac").valid();
        
        if (isvalid) {    
            $("#add_zodiac_reset").click();
            $('#add_zodiac_table').remove();
            $('#main').append(res);
            
        }
    });
    
    $('#add_currency').ajaxForm(function(res) { 
        var isvalid = $("#add_currency").valid();
        if (isvalid) { 
            $('#add_currency_table tr:first').after(res);
            $("#add_currency_reset").click();  
        }
    });
    
    $('#add_operator').ajaxForm(function(res) { 
        var isvalid = $("#add_operator").valid();
        if (isvalid) { 
            $('#add_operator_table tr:first').after(res);
            $("#add_operator_reset").click();  
        }
    });
    
    $('#add_rss_source').ajaxForm(function(res) { 
        var isvalid = $("#add_rss_source").valid();   
        if (isvalid) { 
            $('#add_rss_source_table tr:first').after(res);
            $("#add_rss_source_reset").click();  
        }
    });
 
    $('#add_country_category_rss').ajaxForm(function(res) { 
        var isvalid = $("#add_country_category_rss").valid();   
        if (isvalid) { 
            $('#add_country_category_rss_source_table tr:first').after(res);
            $("#add_rss_source_reset").click();  
        }
    }); 
    
    $('#send_pn_action').ajaxForm( {
            beforeSend: function(){          
                $("#loader").css("left", "50%");
                $("#loader").css("top", "50%");
                $("#loader").css("display", "block");
            },   
            success: function(){
                //alert('success');
            },
            complete: function(response){      
                //var isvalid = $("#import_app_action").valid();
                $("#loader").css("display", "none");
                              //  console.log(response);
                $("#resp").html('done'); 
            }
    });  
   
}); 