var startPage = 1;
var refreshTime; 

var langPrefix = "";
var pagProfile = 1;

/*
 * Added By:  Taymoor Qanadilou
 * Added Date: [05/08/2013]
 * Description: display popup window
 *
*/
function _alert(data){
    $('#box-alert').html(data);
    $('.box-alert').click();
}

$(document).mousemove(function(e){
        $(".loading-img").css({"left" : (e.clientX+16) ,"top" : (e.clientY)});
});
/*
 * Added By:  Taymoor Qanadilou
 * Added Date: [05/08/2013]
 * Description: display loading image
 *
*/
function _loadingImage(){      
    $(".loading-img").show();      
}

/*
 * Added By:  Taymoor Qanadilou
 * Added Date: [05/08/2013]
 * Description: close popup window
 *
*/
function x_alert(){
    $('.modal_close').click();
}

/*
 * Added By:  Taymoor Qanadilou
 * Added Date: [04/08/2013]
 * Description: define popup links like[sign in,sign up etc..]
 *
*/
$(function() {
    $('a[rel*=leanModal]').leanModal({ closeButton: ".modal_close" });
});

function buildDeletePopup(w, h, headerTitle, question, yesAction) {
    var msg;
    
    $('#box-alert').css('width', w); 
    $('#box-alert').css('height', h); 
    msg = dataAlert(t(headerTitle), t(question));
    msg += '<div class="delete-btn"><div onclick="' + yesAction + '" class="delete-yes">' + t('Yes') + '</div><div onclick="closePopup()" class="delete-no">' + t('No') + '</div></div>';       
    _alert(msg);
    $('#box-alert .abuse-body').css('height','55px'); 
}

function usleep(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds) {
            break;
        }
    }
}

function sleep(seconds) {
    usleep(seconds * 1000);
}

function t(text){
    text = encodeURIComponent(text);
    var x = null;
    $.ajax({
            url:basePath+'js_translate/' + text,
            success: function(data){
                //$("#moderator_search_results").html(data);
                
                x = data;
            },
            async: false
    });
    
    return x;
    
}

function dataAlert(title, body) {
    msg = '<div class="abuse-header" >' + title + '</div><div class="abuse-body">' +  body + '</div>';
    return msg;
}

function closePopup(){
    $('#box-alert').css('display', 'none');
    $('#lean_overlay').css('display', 'none');
}

function submitNewPassword(){
    var email = $("#email").val();
    $.ajax({
        url:basePath + "check_if_username_or_email_exists/" + email,
        success:function(data){
            //alert(data);
            data = data.split('_');
            
            $(".error-msg").html(data[0]); 
                
            if (data[1] == 1) {      
                setTimeout(function(){
                  $('#new-pass').css('display', 'none');
                  $('#lean_overlay').css('display', 'none');
                }, 2000);
                
            }
        }
    });
}


function goToProfile(){
   location.href = basePath + "profile"; 
}

function goToHome(){
    location.href = basePath; 
}

function activeDeactiveClient(cid) {

    $.ajax({
        url:basePath + "deactive_active_client/" + cid,
        success:function(data){ 
           if (data == 1) {
               $("#deactive_" + cid).html(t("Deactivate")); 
               $("#client_status_" + cid).html(t("Active")); 
           }
           else{
               $("#deactive_" + cid).html(t("Activate"));  
               $("#client_status_" + cid).html(t("Deactive")); 
           } 
        }
    });
}

function activeDeactiveUser(uid) {
   $.ajax({
        url:basePath + "deactive_active_user/" + uid,
        success:function(data){ 
           if (data == 1) {
               $("#deactive_" + uid).html(t("Deactivate")); 
               $("#user_status_" + uid).html(t("Active")); 
           }
           else{
               $("#deactive_" + uid).html(t("Activate"));  
               $("#user_status_" + uid).html(t("Deactive")); 
           } 
        }
    });
}

function openEditClientPopup(cid){
    $.ajax({
        url:basePath + "open_edit_client_popup/" + cid,
        success:function(data){ 
           _alert(data);
        }
    });
}

function openEditUserPopup(uid) {
   $.ajax({
        url:basePath + "open_edit_user_popup/" + uid,
        success:function(data){ 
           _alert(data);
        }
    });
}

function getOperatorsByCountry(countryId) {
   $.ajax({
        url:basePath + "get_operators_by_country/" + countryId.value,
        success:function(data){ 
          $("#operator_list").html(data);
        }
    });
}

function getClientsByRole(id){     
     //super admin no need client or country he has default 
     if (id.value == 1) {
         $("#add_client").hide();
         $("#add_client").val('');
         
         $("#add_country").hide();
         $("#add_country").val('');
         
         $("#add_operator").hide();
         $("#add_operator").val('');
         
         $("#operator_list").hide();
     }
     else{
        $("#add_client").show();
        $("#add_country").show();
        $("#operator_list").show(); 
     }
}

function getMenuByClient(id) {
    $.ajax({
        url:basePath + "get_menu_by_client/" + id.value,
        success:function(data){     
          $(".client-menu").html(data);
        }
    });
}

function addMenuItemToClient(id) {
    var clientId = $("#add_client").val();
    var menuId = id.value;
    $.ajax({
        url:basePath + "add_menu_item_to_client/" + clientId + "/" + menuId,
        success:function(data){     
          $(".client-menu").html(data);  
        }
    });
}

function removeMenuItemFromClient(id) {
    var clientId = $("#add_client").val();
    var menuId = id.value;
    
    $.ajax({
        url:basePath + "remove_menu_item_from_client/" + clientId + "/" + menuId,
        success:function(data){     
          $(".client-menu").html(data);  
        }
    });
}

function deleteVideo(vid) {
     var conf = confirm(t("Are you sure you want to delete this video?"));
     
     if (conf) {
         $.ajax({
            url:basePath + "delete_video/" + vid,
            success:function(data){     
              $("#vid_" + vid).hide();  
            }
        });
     }
}

function openEditVideoPopup(vid) {
    $.ajax({
        url:basePath + "open_edit_video_popup/" + vid,
        success:function(data){     
          _alert(data);  
        }
    });
}