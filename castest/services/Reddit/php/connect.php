<?php
    date_default_timezone_set('America/New_York');
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $dbname = 'maindatabase';
    
    $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die(mysqli_connect($connect));
?>
