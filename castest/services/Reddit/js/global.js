$(document).ready(function() {
    //hide the child comments until otherwise
    $(".child-comments").hide();
    
    //create a link to displayy the child comments on a click
    $("a#children").click(function() {
        var section = $(this).attr("name");
        var togTxt = $("#tog_text").text();
        $("#C-" +section).toggle();
    });
    
    //validation of the comment
  
    $(".form-submit").click(function() {
       var commentBox = $("#comment");
       var commentCheck = commentBox.val();
       if(commentCheck == '' || commentCheck == null) {
           commentBox.addClass("form-text-error");
           return false;
       }
    });   
    
    //validation of the reply
    $(".form-reply").click(function() {
       var replyBox = $("#new-reply");
       var replyCheck = replyBox.val();
       if(replyCheck == '' || replyCheck == null) {
           replyBox.addClass("form-text-error");
           return false;
       }
    });   
    
    
    
    
    //add a reply link with a click otion to insert a reply box below the comment
    $("a#reply").one("click", function() {
        var comCode = $(this).attr("name");
        var parent = $(this).parent();
       
        parent.append("<br /><form action='' method='post'><textarea class='form-text' name='new-reply' id='new-reply' required='required'></textarea><input type='hidden' name='code' value='"+comCode+"' /><input type='submit' class='form-submit' id='form-reply' name='new_reply' value='Reply' /></form>"); 
    });
    
    $("a#parent-edit").one("click", function() {
        var comCode = $(this).attr("name");
        var parent = $(this).parent();
       
        parent.append("<br /><form action='' method='post'><textarea class='form-text' name='edit_comment' id='edit_comment' required='required'></textarea><input type='hidden' name='code' value='"+comCode+"' /><input type='submit' class='form-submit' id='comment' name='comment' value='edit' /></form>"); 
    });
    
    $("a#parent-delete").one("click", function() {
        var comCode = $(this).attr("name");
        var parent = $(this).parent();
    
        parent.append("<br /><form action='' method='post'><input type='hidden' name='code' value='"+comCode+"' /><input type='submit' class='form-submit' id='delete-comment-yes' name='delete-comment-yes' value='yes' /><input type='submit' class='form-submit' id='delete-comment-no' name='delete-comment-no' value='no' /></form>"); 
    });
    
    $("a#parent-delete-child").one("click", function() {
        var comCode = $(this).attr("name");
        var parent = $(this).parent();
       
        parent.append("<br /><form action='' method='post'><input type='hidden' name='code' value='"+comCode+"' /><input type='submit' class='form-submit' id='delete-comment-yes-child' name='delete-comment-yes-child' value='yes' /><input type='submit' class='form-submit' id='delete-comment-no-child' name='delete-comment-no-child' value='no' /></form>"); 
    });
    
    $("a#parent-edit-child").one("click", function() {
        var comCode = $(this).attr("name");
        var parent = $(this).parent();
       
        parent.append("<br /><form action='' method='post'><textarea class='form-text' name='edit_comment_child' id='edit_comment_child' required='required'></textarea><input type='hidden' name='code' value='"+comCode+"' /><input type='submit' class='form-submit' id='comment_child' name='comment_child' value='edit' /></form>"); 
    });
    
    $("a#parent-like").one("click", function() {
        var comCode = $(this).attr("name");
        var parent = $(this).parent();
       
        parent.append("<br /><form action='' method='post'><input type='hidden' name='code' value='"+comCode+"' /><input type='submit' class='form-submit' id='par_like' name='par_like' value='like' /></form>"); 
    });
    
    $("a#parent-dislike").one("click", function() {
        var comCode = $(this).attr("name");
        var parent = $(this).parent();
       
        parent.append("<br /><form action='' method='post'><input type='hidden' name='code' value='"+comCode+"' /><input type='submit' class='form-submit' id='par_dislike' name='par_dislike' value='dislike' /></form>"); 
    });
    
    $("a#parent-like-child").one("click", function() {
        var comCode = $(this).attr("name");
        var parent = $(this).parent();
       
        parent.append("<br /><form action='' method='post'><input type='hidden' name='code' value='"+comCode+"' /><input type='submit' class='form-submit' id='par_like_child' name='par_like_child' value='like' /></form>"); 
    });
    
     $("a#parent-dislike-child").one("click", function() {
        var comCode = $(this).attr("name");
        var parent = $(this).parent();
    
       
        parent.append("<br /><form action='' method='post'><input type='hidden' name='code' value='"+comCode+"' /><input type='submit' class='form-submit' id='par_dislike_child' name='par_dislike_child' value='dislike' /></form>"); 
    });
});







