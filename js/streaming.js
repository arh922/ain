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
    
    $('#box-alert').center();
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

function getCategoriesByClient(id) {
    $("#loader").css("left", "50%");
    $("#loader").css("top", "50%");
    $("#loader").css("display", "block");
    
    $.ajax({
        url:basePath + "get_categories_by_client/" + id.value,
        success:function(data){     
          $(".client-menu").html(data);
          $("#loader").css("display", "none");
        }
    });
}

function addCategoryToClient(id) {
    var cid = $("#add_client").val();
    
    $("#loader").css("left", "50%");
    $("#loader").css("top", "50%");
    $("#loader").css("display", "block");
    
    $.ajax({
        url:basePath + "add_category_to_client/" + $(id).val() + "/" + cid,
        success:function(data){     
           $(".client-menu").html(data);
           $("#loader").css("display", "none"); 
        }
    });
}

function removeCategoryToClient(id) {
   var cid = $("#add_client").val();
   
   $("#loader").css("left", "50%");
   $("#loader").css("top", "50%");
   $("#loader").css("display", "block");
    
   $.ajax({
        url:basePath + "remove_category_to_client/" + $(id).val() + "/" + cid,
        success:function(data){     
           $(".client-menu").html(data);
           $("#loader").css("display", "none"); 
        }
    });
}
                       
function addMenuItemToClient(id) {
    var clientId = $("#add_client").val();
    var menuId = id.value;
    
    $("#loader").css("left", "50%");
    $("#loader").css("top", "50%");
    $("#loader").css("display", "block");
   
    $.ajax({
        url:basePath + "add_menu_item_to_client/" + clientId + "/" + menuId,
        success:function(data){     
          $(".client-menu").html(data); 
          $("#loader").css("display", "none");  
        }
    });
}

function removeMenuItemFromClient(id) {
    var clientId = $("#add_client").val();
    var menuId = id.value;
    
    $("#loader").css("left", "50%");
    $("#loader").css("top", "50%");
    $("#loader").css("display", "block");
    
    $.ajax({
        url:basePath + "remove_menu_item_from_client/" + clientId + "/" + menuId,
        success:function(data){     
           $(".client-menu").html(data);
           $("#loader").css("display", "none");   
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

function deleteArticle(aid) {
     var conf = confirm(t("Are you sure you want to delete this Article?"));
     
     if (conf) {
         $.ajax({
            url:basePath + "delete_article/" + aid,
            success:function(data){     
              $("#article_" + aid).hide();  
            }
        });
     }
}

function deleteHtmlArticle(aid) {
     var conf = confirm(t("Are you sure you want to delete this Article?"));
     
     if (conf) {
         $.ajax({
            url:basePath + "delete_html_article/" + aid,
            success:function(data){     
              $("#article_" + aid).hide();  
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

function openEditArticlePopup(aid) {
    $.ajax({
        url:basePath + "open_edit_article_popup/" + aid,
        success:function(data){   
             $("#box-alert").css("width", "920px");      
            _alert(data);  
        }
    });
}

function openEditHtmlArticlePopup(aid) {
    $.ajax({
        url:basePath + "open_edit_html_article_popup/" + aid,
        success:function(data){    //alert(data);
             $("#box-alert").css("width", "920px");      
            _alert(data);  
            CKEDITOR.replace('ck_updated');
        }
    });
}

function openEditPgratePopup(id){
    $.ajax({
        url:basePath + "open_edit_pgrate_popup/" + id,
        success:function(data){     
          _alert(data);  
        }
    });
}

function deletePgrate(id) {
    var conf = confirm(t("Are you sure you want to delete this PG rate?"));
     
     if (conf) {
         $.ajax({
                url:basePath + "delete_pgrate/" + id,
                success:function(data){     
                    $("#pgrate_" + id).hide();  
                }
        });
     }
}

function deleteCategory(cid) {
    var conf = confirm(t("Are you sure you want to delete this Category?"));
     
     if (conf) {
         $.ajax({
                url:basePath + "delete_category/" + cid,
                success:function(data){     
                    $("#category_" + cid).hide();  
                }
        });
     }
}

function deleteTag(cid) {
    var conf = confirm(t("Are you sure you want to delete this Tag?"));
     
     if (conf) {
         $.ajax({
                url:basePath + "delete_tag/" + cid,
                success:function(data){     
                    $("#tag_" + cid).hide();  
                }
        });
     }
}

function deleteHitd(id) {
    var conf = confirm(t("Are you sure you want to delete this happens?"));
     
     if (conf) {
         $.ajax({
                url:basePath + "delete_hitd/" + id,
                success:function(data){     
                    $("#happened_" + id).hide();  
                }
        });
     }
}

function deleteEmergency(id) {
    var conf = confirm(t("Are you sure you want to delete this happens?"));
     
     if (conf) {
         $.ajax({
                url:basePath + "delete_emergency/" + id,
                success:function(data){     
                    $("#emergency_" + id).hide();  
                }
        });
     }
}

function deleteZodiac(id) {
    var conf = confirm(t("Are you sure you want to delete this Zodiac?"));
     
     if (conf) {
         $.ajax({
                url:basePath + "delete_zodiac/" + id,
                success:function(data){     
                    $("#zodiac_" + id).hide();  
                }
        });
     }
}

function openEditCategoryPopup(cid) {
    /*$("#loader").css("left", "50%");
    $("#loader").css("top", "50%");   */
    $("#loader").css("display", "block");
         
    $("#loader").center();
    
    $.ajax({
        url:basePath + "open_edit_category_popup/" + cid,
        success:function(data){
           $("#loader").css("display", "none");
           
          //$("#box-alert").css("top", "47%");     
          _alert(data);  
        }
    });
}

//jQuery.ready(function() {

    jQuery.fn.center = function () {
        this.css("position","absolute");
        this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + 
                                                    $(window).scrollTop()) + "px");
        this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + 
                                                    $(window).scrollLeft()) + "px");
        return this;
    }


//});

function openEditTagPopup(cid) {
    $.ajax({
        url:basePath + "open_edit_tag_popup/" + cid,
        success:function(data){
          //$("#box-alert").css("top", "47%");     
          _alert(data);  
        }
    });
}

function openEditHitdPopup(hid) {
    $.ajax({
        url:basePath + "open_edit_hitd_popup/" + hid,
        success:function(data){
          //$("#box-alert").css("top", "47%");     
          _alert(data);  
        }
    });
}

function openEditEmergencyPopup(hid) {
    $.ajax({
        url:basePath + "open_edit_emergency_popup/" + hid,
        success:function(data){
          //$("#box-alert").css("top", "47%");     
          _alert(data);  
        }
    });
}

function openEditZodiacPopup(hid) {
    $.ajax({
        url:basePath + "open_edit_zodiac_popup/" + hid,
        success:function(data){
          //$("#box-alert").css("top", "47%");     
          _alert(data);  
        }
    });
}

function openEditCurrencyPopup(id){
    $.ajax({
        url:basePath + "open_edit_currency_popup/" + id,
        success:function(data){ 
           _alert(data);
        }
    });
}

function openEditRssSourcePopup(id){
    $.ajax({
        url:basePath + "open_edit_rss_source_popup/" + id,
        success:function(data){ 
           $("#box-alert").css("width", "620px"); 
           _alert(data);
        }
    });
}

function deleteCurrency(id) {
    var conf = confirm(t("Are you sure you want to delete this Currency?"));
     
     if (conf) {
         $.ajax({
                url:basePath + "delete_currency/" + id,
                success:function(data){     
                    $("#currency_" + id).hide();  
                }
        });
     }
}

function openEditOperatorPopup(id){
    $.ajax({
        url:basePath + "open_edit_operator_popup/" + id,
        success:function(data){
           $("#box-alert").css("top", "47%"); 
           _alert(data);
        }
    });
}

function deleteOperator(id) {
    var conf = confirm(t("Are you sure you want to delete this Operator?"));
     
     if (conf) {
         $.ajax({
                url:basePath + "delete_operator/" + id,
                success:function(data){     
                    $("#operator_" + id).hide();  
                }
        });
     }
}

function deleteRssSource(id) {
    var conf = confirm(t("Are you sure you want to delete this RSS Source?"));
     
     if (conf) {
         $.ajax({
                url:basePath + "delete_rss_source/" + id,
                success:function(data){     
                    $("#rss_" + id).hide();  
                }
        });
     }
}

function deleteCountryCategoryRssSource(id) {
    var conf = confirm(t("Are you sure you want to delete this Country - Category - RSS Source?"));
     
     if (conf) {
         $.ajax({
                url:basePath + "delete_country_category_rssSource/" + id,
                success:function(data){     
                    $("#rss_" + id).hide();  
                }
        });
     }
}

function setEditorValue(){
   $("#ck").html(CKEDITOR.instances.ck.getData());
}

function setHtmlEditorValue(){
   $("#ck_updated").html(CKEDITOR.instances.ck_updated.getData());
}

function approveComment(id) {
        id = $(id).val();
        
        $.ajax({
                url:basePath + "approve_comment/" + id,
                success:function(data){     
                    $("#commnet_" + id).hide();  
                }
        });
}

function deleteComment(id) {
        id = $(id).val();
        
        $.ajax({
                url:basePath + "delete_comment/" + id,
                success:function(data){     
                    $("#commnet_" + id).hide();  
                }
        });
}

function deleteSource(id) {
    var conf = confirm(t("Are you sure you want to delete this Source?")); 
    
    if (conf) {
        $.ajax({
                url:basePath + "delete_source/" + id,
                success:function(data){     
                    $("#category_" + id).hide();  
                }
        });
    }
}

function searchNews(){
    var word = encodeURIComponent($("#search_word").val());
    $("#loader").css("display", "block");
         
    $("#loader").center();
    
    $.ajax({
            url:basePath + "search_news/" + word,
            success:function(data){        // alert(data);
                $("#news_table").html(data);
                $("#loader").css("display", "none");  
            }
    });
}

function getRelatedTags() {
    var tname = encodeURIComponent($('#main_tag_name').val());
    
    $("#loader").css("display", "block");
    
    $("#loader").center();
    
    $.ajax({
            url:basePath + "get_related_tags/" + tname,
            success:function(data){        // alert(data);
                $("#related_tags_div").html(data);
                $("#loader").css("display", "none");  
            }
    });
}

function deleteReletadTag(id) {
    var confirmation = confirm("Are you sure you delete this record?");
    
    if(confirmation) {
        $.ajax({
                url:basePath + "delete_reletad_tag/" + id,
                success:function(data){      
                    $("#tag_" + id).remove();
                }
        });
    }
}

function UpdateSort(id) {
        var sortValue = $('#sort_' + id).val(); 
                 
        $.ajax({
                url:basePath + "update_sort/" + id + "_" + sortValue,
                success:function(data){      
                    //nothing 
                }
        });
}

function SaveRelatedTags(parent, parent_name){
        var related_tags = $('#related_tags_' + parent).val();
        
        $.ajax({
                url:basePath + "add_related_tag1/" + parent + "_" + encodeURIComponent(parent_name) + "_" + encodeURIComponent(related_tags),
                success:function(data){      
                    $('#synonyms_' + parent).append(data);
                }
        });
}