<?php
###########################################################################
# Index Debugging Footer
###########################################################################

###########################################################################
# Display the Footer if the user has enabled debugging and the server has     
# not disabled it 
###########################################################################
if($_SESSION['debug'] || $GLOBAL_DEBUG && !$DISABLE_DEBUG){
  echo "<br><br><br>";
  echo "<hr style=\"border-top: dashed 1px;\" />";
  echo "<br>";

  #########################################################################
  # Tags and Tags-Index Array output
  #########################################################################
  echo "<h3>General Debugging:</h3>";
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
  # print RAW CAS response
  #########################################################################
  echo "<hr>CAS Response:<hr>\n";
  echo htmlentities( $response ) . "\n";

  #########################################################################
  # Timeouts
  #########################################################################
  echo "<hr>Timeout info:<hr>";
  foreach ( $TIMEOUT as $key=>$value ) {
    echo "${key}= ${value}<br>\n";
  }
  echo "Inactive Time = $INACTIVETIME<br>";
  echo "<br>current timeout = $currentTimeout <br> is timed out = $isTimedOut <br>";
  echo "relogin = ";
  if ($_SESSION[ 'relogin' ] ) {
    echo "true";
  } else {
    echo "false";
  }
  echo "<br>";
  echo "Login Method = ".$_SESSION[ 'login_method' ];

      
  #########################################################################
  # Query String                                 
  #########################################################################
  echo "<hr>Query String:<hr>";
  echo "QS = $query_string";

  #########################################################################
  # Get Values:
  #########################################################################
  echo "<hr>Get Values:<hr>";
  foreach ( $_GET as $key=> $value ) {
    echo "${key}= ${value}\n";
  }

  #########################################################################
  # POST Values:
  #########################################################################
  echo "<hr>Post Values:<hr>";
  foreach ( $_POST as $key=> $value ) {
    echo "${key}= ${value}\n";
  }
  
  #########################################################################
  # Permissions:
  #########################################################################
  echo "<hr>Permissions:<hr>";
  echo "All Permissions:<br>";
  foreach ( $_SESSION[ 'permissions' ] as $key => $value ) {
    echo "${key}= ${value}<br>";
  }
  echo "<br>Effective Permissions:<br>";
  echo $_SESSION[ 'permissions' ][ $_SESSION[ 'goto' ] ];

  #########################################################################
  # Database Info
  #########################################################################
  echo "<hr>Database:<hr>";
  if(mysqli_connect_error()) {
    echo "Failed to connect to database: " . mysqli_connect_error();
  } else {
    echo "Database1 is $DB_TEST<br>";
    echo "Database2 is $DB_TEST2";
  }
  //echo "Database = $DB_CASUSERS";
  $result = mysqli_query($DB_CASUSERS, "SELECT * FROM casusers WHERE Access='test'");
  echo "<br>number of rows is: " . mysqli_num_rows($result) . "<br>";
  #########################################################################
  # PHP Info
  #########################################################################
  echo "<hr>PHP Info:<hr>";
  //$tmp = phpinfo();
  //echo "$tmp";
}

?>
