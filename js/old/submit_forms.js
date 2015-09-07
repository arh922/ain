// wait for the DOM to be loaded 
$(document).ready(function() { 
    // bind 'myForm' and provide a simple callback function 
    $('#add_client').ajaxForm(function(res) { 
        var isvalid = $("#add_client").valid();
        if (isvalid) { 
            $('#add_client_table tr:first').after(res);
        }
    });
      
    $('#add_user').ajaxForm(function(res) { 
        var isvalid = $("#add_user").valid();
 
        if (isvalid) { 
            $('#add_user_table tr:first').after(res);
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
               //alert(res);
        if (isvalid) {    
            $("#add_video_reset").click();
            $('#add_video_table tr:first').after(res);
        }
    }); 
}); 