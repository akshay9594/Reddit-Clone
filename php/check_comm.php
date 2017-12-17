<?php
    //require 'functions.php';
    //when a new comment is typed
    if(isset($_POST['new_comment'])) {
        $new_com_name = $_SESSION['user_name'] ;
        $new_com_text = $_POST['comment'];
        $new_com_date = date('Y-m-d H:i:s');
        $new_com_code = generateRandomString();
        
        if(isset($new_com_text)) {
            mysqli_query($connect, "INSERT INTO `parents` (`user`, `text`, `date`, `code`)
            VALUES ('$new_com_name', '$new_com_text', '$new_com_date', '$new_com_code' )");
            get_total();
        }
        
        header("Location: ");
    }
    
    
    //new reply
    if(isset($_POST['new_reply'])) {
        $new_reply_name = $_SESSION['user_name'] ;
        $new_reply_text = $_POST['new-reply'];
        $new_reply_date = date('Y-m-d H:i:s');
        $new_reply_code = $_POST['code'];
        $new_reply_child_code = generateRandomString();
        
        if(isset($new_reply_text)) {
            mysqli_query($connect, "INSERT INTO `children` (`user`, `text`, `date`, `par_code`, `child_code`)
            VALUES ('$new_reply_name', '$new_reply_text', '$new_reply_date', '$new_reply_code', '$new_reply_child_code' )") or die(mysqli_error());
            get_total();
        }
        
        header("Location: ");
    }
    
    //edited comment
    if(isset($_POST['comment'])) {
        $new_edit_name = $_SESSION['user_name'] ;
        $new_edit_text = $_POST['edit_comment'];
        $new_edit_date = date('Y-m-d H:i:s');
        $new_edit_code = $_POST['code'];
        
        if(isset($new_edit_text)) {
            mysqli_query($connect, "UPDATE `parents` SET `text`='$new_edit_text', `date`='$new_edit_date' WHERE `code`='$new_edit_code'") or die(mysqli_error());
            get_total();
           // mysqli_query($connect, "UPDATE `parents` (`user`, `text`, `date`, `code`)
            //VALUES ('$new_edit_name', '$new_edit_text', '$new_edit_date', '$new_edit_code' )") or die(mysqli_error());
        }
            
        header("Location: ");
    }
    
    if(isset($_POST['comment_child'])) {
        $new_edit_name_child = $_SESSION['user_name'] ;
        $new_edit_text_child = $_POST['edit_comment_child'];
        $new_edit_date_child = date('Y-m-d H:i:s');
        $new_edit_code_child = $_POST['code'];
        
        if(isset($new_edit_text_child)) {
            mysqli_query($connect, "UPDATE `children` SET `text`='$new_edit_text_child', `date`='$new_edit_date_child' WHERE `par_code`='$new_edit_code_child'") or die(mysqli_error());
            get_total();
           // mysqli_query($connect, "UPDATE `parents` (`user`, `text`, `date`, `code`)
            //VALUES ('$new_edit_name', '$new_edit_text', '$new_edit_date', '$new_edit_code' )") or die(mysqli_error());
        }
            
        header("Location: ");
    }
    
    //delete comment
    if(isset($_POST['delete-comment-yes'])) {
        //$new_edit_name = $_SESSION['user'] ;
        //$new_edit_text = $_POST['delete_comment'];
        //$new_edit_date = date('Y-m-d H:i:s');
        $new_edit_code = $_POST['code'];
        
        if(isset($new_edit_code)) {
            mysqli_query($connect, "DELETE FROM `parents` WHERE `code`='$new_edit_code'") or die(mysqli_error());
            mysqli_query($connect, "DELETE FROM `children` WHERE `par_code`='$new_edit_code'") or die(mysqli_error());
            get_total();
           // mysqli_query($connect, "UPDATE `parents` (`user`, `text`, `date`, `code`)
            //VALUES ('$new_edit_name', '$new_edit_text', '$new_edit_date', '$new_edit_code' )") or die(mysqli_error());
        }
            
        header("Location: ");
    }
    
    if(isset($_POST['delete-comment-no'])) {
        
        header("Location: ");
    }
    
    if(isset($_POST['delete-comment-yes-child'])) {
        //$new_edit_name = $_SESSION['user'] ;
        //$new_edit_text = $_POST['delete_comment'];
        //$new_edit_date = date('Y-m-d H:i:s');
        $new_edit_code = $_POST['code'];
        
        if(isset($new_edit_code)) {
            //mysqli_query($connect, "DELETE FROM `parents` WHERE `code`='$new_edit_code'") or die(mysqli_error());
            mysqli_query($connect, "DELETE FROM `children` WHERE `par_code`='$new_edit_code'") or die(mysqli_error());
            get_total();
           // mysqli_query($connect, "UPDATE `parents` (`user`, `text`, `date`, `code`)
            //VALUES ('$new_edit_name', '$new_edit_text', '$new_edit_date', '$new_edit_code' )") or die(mysqli_error());
        }
            
        header("Location: ");
    }
    
    if(isset($_POST['delete-comment-no-child'])) {
        
        header("Location: ");
    }
    
    if(isset($_POST['par_like'])) {
        $new_like_child_user = $_SESSION['user_name'];
       $new_childlike_code = $_POST['code'];
         $result = mysqli_query($connect, "SELECT * FROM `parents` WHERE `code`='$new_childlike_code'");
        $row_cnt = mysqli_num_rows($result);
        $childlike = 0;
        $check=0;
        $user_check='';
        
        foreach($result as $item){
            $mainchildcode = $item['code'];
            //$main_childcode = $item['par_code'];
            $num_childlike_par = $item['num_like'];
            $num_childdislike_par = $item['num_dislike'];
            $num_child_every_user = $item['user'];
            
            $result1=mysqli_query($connect,"SELECT * FROM `likedislike` WHERE EXISTS (SELECT * FROM `likedislike` WHERE `maincode`='$mainchildcode' AND `user`='$new_like_child_user')");
            $row_cnt1=mysqli_num_rows($result1);
            foreach($result1 as $item1){
                $checkdis=$item1['dislikenumber'];
                $checklike=$item1['likenumber'];
                $user_check=$item['user'];
            }
            if($mainchildcode == $new_childlike_code && $checklike!=1 && $user_check!=$new_like_child_user){
                $count=$count+1;
                //$count_user = 1;
                if($new_childlike_code==$mainchildcode ){
                    //$count_user= $count_user + 1;
                    $num_childlike_par = $num_childlike_par + 1;
                
                //$num_childlike_par = $num_childlike_par + 1;
                    mysqli_query($connect, "UPDATE `parents` SET `num_like`='$num_childlike_par' WHERE `code`='$new_childlike_code'") or die(mysqli_error());
                    if($num_childlike_par==1 || $user_check!=$new_like_child_user){
                        $update=1;
                        if($checkdis!=1 || $checklike!=1){
                        mysqli_query($connect, "INSERT INTO `likedislike` (`maincode`, `likenumber`, `dislikenumber`, `user`) VALUES ('$mainchildcode', '1', '$num_childdislike_par', '$new_like_child_user')") or die(mysqli_error());}
                    }
                }
            }
        }
        get_total();
    }
    
   
    if(isset($_POST['par_like_child'])) {
        $new_like_child_user = $_SESSION['user_name'];
       $new_childlike_code = $_POST['code'];
         $result = mysqli_query($connect, "SELECT * FROM `children` WHERE `child_code`='$new_childlike_code'");
        $row_cnt = mysqli_num_rows($result);
        $childlike = 0;
        $check=0;
        $user_check='';
        
        foreach($result as $item){
            $mainchildcode = $item['child_code'];
            $main_childcode = $item['par_code'];
            $num_childlike_par = $item['child_like'];
            $num_childdislike_par = $item['child_dislike'];
            $num_child_every_user = $item['user'];
            
            $result1=mysqli_query($connect,"SELECT * FROM `likedislike` WHERE EXISTS (SELECT * FROM `likedislike` WHERE `maincode`='$mainchildcode' AND `user`='$new_like_child_user')");
            $row_cnt1=mysqli_num_rows($result1);
            foreach($result1 as $item1){
                $checkdis=$item1['dislikenumber'];
                $checklike=$item1['likenumber'];
                $user_check=$item['user'];
            }
            if($mainchildcode == $new_childlike_code && $checklike!=1 && $user_check!=$new_like_child_user){
                $count=$count+1;
                //$count_user = 1;
                if($new_childlike_code==$mainchildcode ){
                    //$count_user= $count_user + 1;
                    $num_childlike_par = $num_childlike_par + 1;
                
                //$num_childlike_par = $num_childlike_par + 1;
                    mysqli_query($connect, "UPDATE `children` SET `child_like`='$num_childlike_par' WHERE `child_code`='$mainchildcode'") or die(mysqli_error());
                    if($num_childlike_par==1 || $user_check!=$new_like_child_user){
                        $update=1;
                        if($checkdis!=1 || $checklike!=1){
                        mysqli_query($connect, "INSERT INTO `likedislike` (`maincode`, `likenumber`, `dislikenumber`, `user`) VALUES ('$mainchildcode', '1', '$num_childdislike_par', '$new_like_child_user')") or die(mysqli_error());}
                    }
                }
            }
        }
        get_total();
    }
    
    if(isset($_POST['par_dislike'])) {
        $new_like_child_user = $_SESSION['user_name'];
       $new_childlike_code = $_POST['code'];
         $result = mysqli_query($connect, "SELECT * FROM `parents` WHERE `code`='$new_childlike_code'");
        $row_cnt = mysqli_num_rows($result);
        $childlike = 0;
        $check=0;
        $user_check='';
        
        foreach($result as $item){
            $mainchildcode = $item['code'];
            //$main_childcode = $item['par_code'];
            $num_childlike_par = $item['num_like'];
            $num_childdislike_par = $item['num_dislike'];
            $num_child_every_user = $item['user'];
            
            $result1=mysqli_query($connect,"SELECT * FROM `likedislike` WHERE EXISTS (SELECT * FROM `likedislike` WHERE `maincode`='$mainchildcode' AND `user`='$new_like_child_user')");
            $row_cnt1=mysqli_num_rows($result1);
            foreach($result1 as $item1){
                $checkdis=$item1['dislikenumber'];
                $checklike=$item1['likenumber'];
                $user_check=$item['user'];
            }
            if($mainchildcode == $new_childlike_code && $checkdis!=1 && $user_check!=$new_like_child_user){
                $count=$count+1;
                //$count_user = 1;
                if($new_childlike_code==$mainchildcode ){
                    //$count_user= $count_user + 1;
                    $num_childdislike_par = $num_childdislike_par + 1;
                
                //$num_childlike_par = $num_childlike_par + 1;
                    mysqli_query($connect, "UPDATE `parents` SET `num_dislike`='$num_childdislike_par' WHERE `code`='$new_childlike_code'") or die(mysqli_error());
                    if($num_childdislike_par==1 || $user_check!=$new_like_child_user){
                        $update=1;
                        if($checkdis!=1 || $checklike!=1){
                        mysqli_query($connect, "INSERT INTO `likedislike` (`maincode`, `likenumber`, `dislikenumber`, `user`) VALUES ('$mainchildcode', '$num_childlike_par', '1', '$new_like_child_user')") or die(mysqli_error());}
                    }
                }
            }
        }
        get_total();
    }
    
     if(isset($_POST['par_dislike_child'])) {
         $new_like_child_user = $_SESSION['user_name'];
       $new_childlike_code = $_POST['code'];
         $result = mysqli_query($connect, "SELECT * FROM `children` WHERE `child_code`='$new_childlike_code'");
        $row_cnt = mysqli_num_rows($result);
        $childlike = 0;
        $check=0;
        $user_check='';
        
        foreach($result as $item){
            $mainchildcode = $item['child_code'];
            $main_childcode = $item['par_code'];
            $num_childlike_par = $item['child_like'];
            $num_childdislike_par = $item['child_dislike'];
            $num_child_every_user = $item['user'];
            
            $result1=mysqli_query($connect,"SELECT * FROM `likedislike` WHERE EXISTS (SELECT * FROM `likedislike` WHERE `maincode`='$mainchildcode' AND `user`='$new_like_child_user')");
            $row_cnt1=mysqli_num_rows($result1);
            foreach($result1 as $item1){
                $checkdis=$item1['dislikenumber'];
                $checklike=$item1['likenumber'];
                $user_check=$item['user'];
            }
            if($mainchildcode == $new_childlike_code && $checkdis!=1 && $user_check!=$new_like_child_user){
                $count=$count+1;
                //$count_user = 1;
                if($new_childlike_code==$mainchildcode ){
                    //$count_user= $count_user + 1;
                    $num_childdislike_par = $num_childdislike_par + 1;
                
                //$num_childlike_par = $num_childlike_par + 1;
                    mysqli_query($connect, "UPDATE `children` SET `child_dislike`='$num_childdislike_par' WHERE `child_code`='$mainchildcode'") or die(mysqli_error());
                    if($num_childdislike_par==1 || $user_check!=$new_like_child_user){
                        $update=1;
                        if($checkdis!=1 || $checklike!=1){
                        mysqli_query($connect, "INSERT INTO `likedislike` (`maincode`, `likenumber`, `dislikenumber`, `user`) VALUES ('$mainchildcode',  '$num_childlike_par', '1', '$new_like_child_user')") or die(mysqli_error());}
                    }
                }
            }
        }
        get_total();
    }
        //if(isset($new_edit_code)) {
            //mysqli_query($connect, "DELETE FROM `parents` WHERE `code`='$new_edit_code'") or die(mysqli_error());
          //  mysqli_query($connect, "UPDATE parents SET num_like=num_like+3 WHERE code='$new_like_code'") or die(mysqli_error());
            
           // mysqli_query($connect, "UPDATE `parents` (`user`, `text`, `date`, `code`)
            //VALUES ('$new_edit_name', '$new_edit_text', '$new_
    

?>