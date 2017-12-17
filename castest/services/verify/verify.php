<?php
require 'config.php';
include("/www/nwp/main/html/include//engrIT/header.html");
    
      #########################################################################
      # Tags and Tags-Index Array output
      #########################################################################
      echo "<hr>Tags ARRAY from xml_parser routine<hr>\n";
      echo "<pre>\n";
      print_r( $tags );
      echo "</pre>\n";
      echo "<hr>Tags-Index ARRAY from xml_parser routine<hr>\n";
      echo "<pre>\n";
      print_r( $tags_index );
      echo "</pre>\n";

      #########################################################################
      # CAS data output
      #########################################################################
      echo "<hr>CAS_DATA key=values<hr>\n";
      foreach ( $cas_data as $key=> $value ) {
        echo "${key}= $value<br>\n";
      }

      #########################################################################
      # $_SESSION data output
      #########################################################################
      echo "<hr>SESSION_DATA key=values<hr>\n";
      foreach ( $_SESSION as $key=> $value ) {
        echo "${key}= ${value}<br>\n";
      }

      #########################################################################
      # print RAW CAS response, logout link and exit
      #########################################################################
      echo "<hr>CAS Response:<hr>\n";
      echo htmlentities( $response ) . "\n<br>";
      echo "<b><a href=\"/castest/index.php?action=logout\">Click here to logout</a></b>\n";
      
      foreach ( $TIMEOUT as $key=> $value ) {
        echo "${key}= ${value}<br>\n";
      }

      
      include("/www/nwp/main/html/include//engrIT/footer.html");
?>
