<?php

$isvalidated = False;
$valid_users_length = count($validusers);
for($x=0;$x<$valid_users_length; $x++){
  if($_SESSION['user_email'] == $validusers[$x]){
    $isvalidated = True;
  }
}
if( $_SESSION[ $permissions[ 'debug' ] ] == "debugger" ){
  $isvalidated = True;
}


include("/www/nwp/main/html/include/engrIT/header.html");
if($isvalidated){

  if(isset($_POST["debugging"])){
    if($_POST["debugging"] == "on"){
      $_SESSION['debug'] = True;
    } elseif($_POST["debugging"] == "off"){
      $_SESSION['debug'] = False;
    } else {
      echo "error in form submission<br>";
    }
  }
  $DB_TEST2 = "Not Connected";
  if(isset($_POST["DB_SetUserID"]) && isset($_POST["DB_SetService"]) && isset($_POST["DB_SetAccess"])){
    require "$INDEX_DIR/security.php";
    $DB_CASUSERS2 = mysqli_connect($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASS, $MYSQL_DB, $MYSQL_PORT,  $MYSQL_SOCKET) or die( "ERROR " . mysqli_error( $DB_CASUSERS));
    $DB_TEST2 = "Connected";
    if( mysqli_query($DB_CASUSERS2, "INSERT INTO casusers (UserID, Service, Access) VALUES ('" . sanitize_input($_POST["DB_SetUserID"]) . "', '" . sanitize_input($_POST["DB_SetService"]) . "', '" . sanitize_input($_POST["DB_SetAccess"]) . "')") === TRUE){
    //if( mysqli_query($DB_CASUSERS2, "INSERT INTO casusers (UserID, Service, Access) VALUES (\'tbuttrey@udel.edu\', \'debug\', \'test2\')\;") === TRUE ){
      echo "Added Sucessfuly<br>";
      $DB_TEST2 = "Connected and Query was Valid";
    } else {
      $DB_TEST2 = "Connected but Query was INVALID";
    }
    echo "UserID: " . sanitize_input($_POST["DB_SetUserID"]) . "<br>Service: " . sanitize_input($_POST["DB_SetService"]) . "<br>Access: " . sanitize_input($_POST["DB_SetAccess"]) . "<br>";
    //echo "Sumbission: " . $DB_Submit_Result . "<br>";
  }

  if(isset($_GET["permissions"]) && $_GET[ "permissions" ] == "reset" ){
    setAccessLevel();
  }
  echo "validated";
  echo "<br>Enable/Disable Debugging:<br>";
  echo "<form id=\"debug\" method=\"post\" name=\"debug\"";
  echo "action=\"";
  echo htmlspecialchars($_SERVER["PHP_SELF"]. "?goto=debug");
  echo "\">";
  if(!isset($_SESSION['debug']) || $_SESSION['debug'] == False){
    echo "Debugging on?:<br><input id=\"debug\" type=\"radio\" name=\"debugging\" value=\"on\">on<br><input id=\"debug\" type=\"radio\" name=\"debugging\" value=\"off\" checked>off";
  } else{
     echo "Debugging on?:<br><input id=\"debug\" type=\"radio\" name=\"debugging\" value=\"on\" checked>on<br><input id=\"debug\" type=\"radio\" name=\"debugging\" value=\"off\">off";
  }
  echo "<br><input type=\"submit\" id=\"debug\" value=\"Set\">";
  echo "</form>";

  echo "<br><br>Insert Into Database:<br>";
  echo "<form id=\"DB_Insert\" method=\"post\" name=\"DB_Insert\"";
  echo "action=\"";
  echo htmlspecialchars($_SERVER["PHP_SELF"]. "?goto=debug");
  echo "\">";
  echo "UserID: &nbsp<input id=\"DB_Insert\" type=\"text\" name=\"DB_SetUserID\"><br>";
  echo "Service:&nbsp;&thinsp;<input id=\"DB_Insert\" type=\"text\" name=\"DB_SetService\"><br>";
  echo "Access: &nbsp;&#x200A;<input id=\"DB_Insert\" type=\"text\" name=\"DB_SetAccess\"><br>";
  echo "<br><input type=\"submit\" id=\"DB_Insert\" value=\"Add Entry\">";
  echo "</form>";

  

} else {
  echo "you are unauthorized";
}
  include($INDEX_DIR . "/debuggingfooter.php");
include("/www/nwp/main/html/include//engrIT/footer.html");
?>
