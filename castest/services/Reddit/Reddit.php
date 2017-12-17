<?php
    if (empty($_SESSION['user_name'])) {
        echo "You must be logged in to access this page";
        die();
    } else {
        //echo "Hello ".$_SESSION['user_name'];
    }
    require_once 'php/connect.php';
    require_once 'php/functions.php';
?>


<!DOCTYPE html>
<html>
    <head>
        <title> Comment System</title>
        <meta charset="UTF-8" lang="en-US">
        <link rel="stylesheet" href="style.css" />
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
        <script type="text/javascript" src="js/global.js"></script>
    </head>
    <body>
        <p><a href="\?action=logout">Logout</a></p>
        <div class="page-container">
            <?php
                //get_total();
                require_once 'php/check_comm.php';
            ?>
            <form action="" method="post" class="main">
                <label>enter a comment</label>
                <textarea class="form-text" name="comment" id="comment"></textarea>
                <br />
                <input type="submit" class="form-submit" name="new_comment" value="post"/>
            </form>
            <?php
                get_comments();
            ?>
        </div>
    </body>
</html>