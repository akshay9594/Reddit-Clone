<?php
    //session_start();
    //$_SESSION['user'] = 'Admin';    //create an user for now
    #$_SESSION['user'] = 'nitya';
    //$_SESSION['user'] = 'user1';
    
    //display all the comments done previously
    function get_total() {
        require 'connect.php';
        $result = mysqli_query($connect, "SELECT * FROM `parents` ORDER BY `date` DESC");
        $row_cnt = mysqli_num_rows($result);
        echo '<h1> All Comments ('.$row_cnt.')</h1>';
    }
    

    
    function increaselike($par_code) {
        require 'connect.php';
        $result = mysqli_query($connect, "SELECT * FROM `parents` WHERE `code`='$par_code'");
        $row_cnt = mysqli_num_rows($result);
        foreach($result as $item){
            $main_code = $item['code'];
            $num_like_par = $item['num_like'];
            if($main_code == $par_code){
                $num_like_par = $num_like_par + 1;
                mysqli_query($connect, "UPDATE `parents` SET `num_like`='$num_like_par' WHERE `code`='$par_code'") or die(mysqli_error());
            }
        }
    }
    
    function get_comments() {
        require 'connect.php';
        $par_like=0;
        $par_dislike=0;
        $chi_like=0;
        $chi_dislike=0;
        $result = mysqli_query($connect, "SELECT * FROM `parents` ORDER BY `date` DESC");
        $row_cnt = mysqli_num_rows($result);
        
        foreach($result as $item) {
            $date = new dateTime($item['date']);
            $date = date_format($date, 'M j, Y | H:i:s');
            $user = $item['user'];
            $comment = $item['text'];
            $par_code = $item['code'];
            $par_like_par = $item['num_like'];
            $par_dislike_par = $item['num_dislike'];
            
            //display of the main comments
            if($item['user'] == $_SESSION['user_name']){
            echo '<div class ="comment" id="'.$par_code.'">'
                    .'<p class="user">'.$user.'</p>&nbsp;'
                    .'<p class="time">'.$date.'</p>'
                    .'<p class="comment-text">'.$comment.'</p>'
                    .'<a class="link-reply" id="reply" name="'.$par_code.'">Reply</a>'
                    .'<a class="link-edit" id="parent-edit" name="'.$par_code.'" > Edit</a>'
                    .'<a class="link-delete" id="parent-delete" name="'.$par_code.'" > Delete</a>'
                    //.'<button type="button" id="like-par" onclick="'.get_like_parent($par_code).'">like</button>'
                    .'<span><a class="link-like" id="parent-like" name="'.$par_code.'"> like </a>'.$par_like_par.'</span>'
                    //.'<p class="likes">'.$par_like.'</p>&nbsp'
                    .'<span><a class="link-dislike" id="parent-dislike" name="'.$par_code.'"> dislike </a>'.$par_dislike_par.'</span>';}
                    //.'<p class="dislikes">'.$par_dislike.'</p>&nbsp';}
            else{
                echo '<div class ="comment" id="'.$par_code.'">'
                    .'<p class="user">'.$user.'</p>&nbsp;'
                    .'<p class="time">'.$date.'</p>'
                    .'<p class="comment-text">'.$comment.'</p>'
                    .'<a class="link-reply" id="reply" name="'.$par_code.'">Reply</a>'
                    //.'<span><button type="button" onclick="'.increaselike($par_code).'"> like</button></span>'
                    .'<span><a class="link-like" id="parent-like" name="'.$par_code.'"> like </a>'.$par_like_par.'</span>'
                    //.'<p class="likes">'.$par_like.'</p>&nbsp'
                    .'<span><a class="link-dislike" id="parent-dislike" name="'.$par_code.'"> dislike </a>'.$par_dislike_par.'<span>';
                    //.'<p class="dislikes">'.$par_dislike.'</p>&nbsp';
            }
            
            $chi_result = mysqli_query($connect, "SELECT * FROM `children` WHERE `par_code`='$par_code' ORDER BY `date` DESC");
            $chi_cnt = $chi_result->num_rows;
        
            if($chi_cnt == 0) {
            } else {
                echo '<a class="link-reply" id="children" name="'.$par_code.'"><span id="tog_text">replies</span>('.$chi_cnt.')</a>'
                    .'<div class="child-comments" id="C-'.$par_code.'">';
                    
                foreach($chi_result as $com) {
                    $chi_date = new dateTime($com['date']);
                    $chi_date = date_format($chi_date, 'M j, Y | H:i:s');
                    $chi_user = $com['user'];
                    $chi_com = $com['text'];
                    $chi_par = $com['par_code'];
                    $chi_code = $com['child_code'];
                    $chi_like = $com['child_like'];
                   $chi_dislike = $com['child_dislike'];
                   // $resultchi = mysqli_query($connect, "SELECT * FROM `likedislike` WHERE `maincode`='$chi_code'");
            //$row_cnt_chi = mysqli_num_rows($result1);
            //foreach($resultchi as $itemchi){
              //  $chi_like = $itemchi['likenumber'];
                //$chi_dislike = $itemchi['dislikenumber'];
            //}
                    
                    if($com['user'] == $_SESSION['user_name']){
                        echo '<div class ="child" id="'.$par_code.'-C">'
                    .'<p class="user">'.$chi_user.'</p>&nbsp;'
                    .'<p class="time">'.$chi_date.'</p>'
                    .'<p class="comment-text">'.$chi_com.'</p>'
                    .'<a class="link-delete-child" id="parent-delete-child" name="'.$chi_par.'" > Delete</a>'
                    .'<a class="link-edit-child" id="parent-edit-child" name="'.$chi_par.'" > Edit</a>'
                    .'<span><a class="link-like-child" id="parent-like-child" name="'.$chi_code.'"> like </a>'.$chi_like.'</span>'
                    //.'<p class="child-likes">'.$chi_like.'</p>&nbsp'
                    .'<span><a class="link-dislike-child" id="parent-dislike-child" name="'.$chi_code.'"> dislike </a>'.$chi_dislike.'</span>'
                    //.'<p class="child-dislikes">'.$chi_dislike.'</p>&nbsp'
                    .'</div>';
                    } else {
                    echo '<div class ="child" id="'.$par_code.'-C">'
                    .'<p class="user">'.$chi_user.'</p>&nbsp;'
                    .'<p class="time">'.$chi_date.'</p>'
                    .'<p class="comment-text">'.$chi_com.'</p>'
                    .'<span><a class="link-like-child" id="parent-like-child" name="'.$chi_code.'"> like </a>'.$chi_like.'</span>'
                    //.'<p class="child-likes">'.$chi_like.'</p>&nbsp'
                    .'<span><a class="link-dislike-child" id="parent-dislike-child" name="'.$chi_code.'"> dislike </a>'.$chi_dislike.'</span>'
                    //.'<p class="child-dislikes">'.$chi_dislike.'</p>&nbsp'
                    .'</div>';
                    }
                }
                echo '</div>';
            }
            
            echo '</div>';
        }
    }
    
    function generateRandomString($length = 6) {
        $characters = '0123456789abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characterLength = strlen($characters);
        $randomString = '';
        
        for($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $characterLength  - 1)];
        }
        return $randomString;
    }
?>