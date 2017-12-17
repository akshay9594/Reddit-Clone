<?php
$response = '';
require 'castest/config.php';

##############################################################################
# Initialize the session. This will either start a new session, or join one that
# already exists.  Call this first to join & check an already running session.
##############################################################################
session_start();

##############################################################################
# Logout if action=logout.
##############################################################################
if ( isset( $_GET['action']) && $_GET['action'] == 'logout' ) {
  ############################################################################
  # Delete the session cookie.  Must do before any page output
  # Note: This will destroy the session, and not just the session data!
  ############################################################################
  if ( isset( $_COOKIE[session_name()] ) ) {
    setcookie(session_name(), '', time()-42000, '/');
  }

  ############################################################################
  # Unset all session variables Destroy the session.
  ############################################################################
  $_SESSION = array();
  session_destroy();

  ############################################################################
  # Write the logout page
  ############################################################################
  $title= 'Logout';
  $content= "
    <p>Logged Out
    <p>Return to <a class='bluelink' href='/'> Home page</a></p>
  ";

  include( $CAS_HEADER );
  print( $content );
  include( $CAS_FOOTER );
  exit();
}

##############################################################################
# Relogin if action=relogin.
##############################################################################
if ( isset( $_GET['action']) && $_GET['action'] == 'relogin' ) {
  $_SESSION[ 'relogin' ] = TRUE;
}

##############################################################################
#  IF GET[ 'goto' ]
#    Set SESSION[ 'goto' ] and [ 'query_string' ]
#    Set each key,value to a session variable QS_key= value
##############################################################################
if ( isset( $_GET[ 'goto' ] ) ) {
  $query_string= '';
  $goto= htmlspecialchars( $_GET[ 'goto' ] );
  unset( $_GET[ 'goto' ] );
  $_SESSION[ 'goto' ]= $goto;
  foreach ( $_GET as $key=> $value ) {
    $_SESSION[ "QS_${key}" ]= $value;
    $query_string.= "${key}=${value}&";
  }
  $query_string= trim( $query_string, '&' );
  $_SESSION[ 'query_string' ]= htmlspecialchars( $query_string );
} else {
  #$_SESSION[ 'goto' ] = 'Default';
  header( "Location:".$CAS_SERVICE."?goto=Default" );
}

##############################################################################
#  Calculate Timeouts
##############################################################################
if ( !isset( $_SESSION['last_active_time'] ) ) {
  $_SESSION['last_active_time'] = time();
} else {
  ############################################################################
  # the current inactive time is the current time minus the last active time
  ############################################################################
  $INACTIVETIME = time() - $_SESSION['last_active_time']; 

  ############################################################################
  # if that time is greater then the max idle time for that person, then
  # that is the longest that person has been idle, so set it to be the new
  # max idle time
  ############################################################################
  if ( $INACTIVETIME > $_SESSION['max_idle_time'] ) {
    $_SESSION['max_idle_time'] = $INACTIVETIME;
  }

  ############################################################################
  # set the last active time to the current time
  ############################################################################
  $_SESSION['last_active_time'] = time();  
}


##############################################################################
# Get Permissions/Access Levels
##############################################################################
$DB_TEST = "Not Connected";
/*
if ( isset( $_SESSION[ 'user_email' ] ) ) {
  if ( !isset( $_SESSION[ $permissions[ $_SESSION[ 'goto' ] ] ] ) ) {
    $DB_CASUSERS = mysqli_connect($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASS, $MYSQL_DB, $MYSQL_PORT,  $MYSQL_SOCKET);
    $DB_TEST = "Connected";
    $_SESSION[ $permissions[ $_SESSION[ 'goto' ] ] ] = mysqli_query($DB_CASUSERS, "SELECT Access FROM casusers WHERE UserID='" . $_SESSION[ 'user_email' ] . "' AND Service='" . $_SESSION[ 'goto' ] . "'");
  }
}
*/

#############################################################################
# IF SESSION[ 'user_email' ] and !SESSION[ 'relogin' ];
#   CAS login successful. Take action based on goto flag
# ELSIF $_GET[ 'ticket' ];
#   CAS returned a ticket. Validate ticket and then take goto action	
# ELSE login mode, redirect to UD/CAS for authentication 
##############################################################################
if ( isset( $_SESSION[ 'user_email' ] ) && !$_SESSION[ 'relogin' ] /*&& !isset( $_GET[ 'ticket' ] )*/) {
  ############################################################################
  # User Authenticated: Take "goto" action
  ############################################################################
  if ( isServiceInFolder( $_SESSION[ 'goto' ] ) ) {
    include $SERVICES_DIR . '/' . $_SESSION[ 'goto' ] . '/' . 'config.php';
    //echo "$TIMEOUT[ \"$_SESSION[ \'goto\' ]\" ]";
    if ( isset($TIMEOUT[ $_SESSION[ 'goto' ] ] ) ){
      $currentTimeout = $TIMEOUT[ $_SESSION[ 'goto' ] ];
    } else {
      $currentTimeout = $TIMEOUT[ 'default' ];
    }
    $isTimedOut = 'false';
    if ( $_SESSION['max_idle_time'] >= $currentTimeout) {
      //require 
      $isTimedOut = 'true';
      //require $SERVICES_DIR . '/' . $_SESSION[ 'goto' ] . '/' . $_SESSION[ 'goto' ] . '.php';
        require $INDEX_DIR . '/' . 'relogin.php';
    } else {
      require $SERVICES_DIR . '/' . $_SESSION[ 'goto' ] . '/' . $_SESSION[ 'goto' ] . '.php';
    }

    exit();

  } else {
    ##########################################################################
    # Unknown site reference; send warning and exit
    ##########################################################################
    $title= 'Unknown service';
    $content="
      <p>ERROR: Unknown service specified!
      <p><sp><sp>Service: ${goto}<br>
    ";

    include( $CAS_HEADER );
    print( $content );
    include( $CAS_FOOTER );
    exit();
  }

} else {
  if ( isset( $_GET[ 'ticket' ] ) && $_GET[ 'ticket' ] ) {
    ############################################################################
    # Received a UD CAS ticket. Send back to the CAS to validate.
    # If valid and matches service, will return an XML file with our data.
    # Parse XML and take next action based on goto flag
    ############################################################################
    $ticket= $_GET[ 'ticket' ];
    $handle= fopen( $CAS_SERVER."serviceValidate?ticket=".$ticket."&service=".$CAS_SERVICE, "r" );
    while ( $line=fgets( $handle,1000 ) ) {
      $response.= $line;
    }
    fclose( $handle );
      
    if ( ! validate_response($response) ) {
      ###########################################################################
      # Invalid response from CAS; deny access
      ###########################################################################
      echo "<h1 class='error'>Access Denied</h1>";
      echo "<p>Please try to <a class='bluelink' href='index.php'>login</a> again</p>\n";
      echo "Response: $response\n";
      exit(1);
  
    } else {
      ###########################################################################
      # Valid ticket; store as session variable 'ticket'
      ###########################################################################
      $_SESSION[ 'ticket' ]= $ticket;
  		
      ###########################################################################
      # Parse XML file
      # XML parsing based strongly on John Hall's code on the UD CAS Guidlines
      # https://chico.nss.udel.edu/auth-service/cas-guidelines.html
      ###########################################################################
      $parser= xml_parser_create();
      xml_parse_into_struct( $parser, $response, $tags, $tags_index );
      xml_parser_free( $parser );
  
      ###########################################################################
      # Assign CAS variables from XML parse arrays
      ###########################################################################
      foreach( $tags as $tag=> $tag_value ) {
        #########################################################################
        # If tag is of type=complete and has a value, record
        #########################################################################
        if ( $tag_value[ 'type' ] == 'complete' ) {
          $key_parts= explode( ":", $tag_value[ 'tag' ] );
          $index= $key_parts[1];
  
          if ( isset( $tag_value[ 'value' ] ) ) {
            $value= $tag_value[ 'value' ];
          } else {
            $value= '';
          }
          $cas_data[ $index ]= $value;
        }
      }
  
      ###########################################################################
      # Set CAS parameters
      ###########################################################################
      $cas_data['USER']=   strtolower($cas_data['USER']);
      $cas_data['EMAIL']=  strtolower($cas_data['EMAIL']);
      $cas_data['FIRSTNAME']= ucwords(strtolower($cas_data['FIRSTNAME']));
      $cas_data['MIDDLENAME']= ucwords(strtolower($cas_data['MIDDLENAME']));
      $cas_data['LASTNAME']= ucwords(strtolower($cas_data['LASTNAME']));
      $cas_data['FULLNAME']=   $cas_data['FIRSTNAME'] . " " . $cas_data['LASTNAME'];		
      $cas_data['PERSONTYPE'] =   str_replace("|",",",$cas_data['PERSONTYPE']);		
     
      if (strncmp("EG",$cas_data['COLLEGE'],2) == 0 ) {
        $cas_data['COLLEGE'] = "Engineering";
      } 
  		
      ###########################################################################
      # Set CAS related data to SESSION variables if it is not a relogin
      ###########################################################################
      if ( !$_SESSION[ 'relogin' ] ) {
        $_SESSION['firstname']=  $cas_data['FIRSTNAME'];
        $_SESSION['lastname']=   $cas_data['LASTNAME'];
        $_SESSION['user']=       $cas_data['USER'];
        $_SESSION['user_email']= $cas_data['EMAIL'];
        $_SESSION['user_name']=  $cas_data['FULLNAME'];
      } else {
        if ( $_SESSION['user_email'] != $cas_data['EMAIL'] ) {
  	echo "Just no"; //TODO replace this with the require for logins with different credentials
          exit();
        }
      }
  
      ###########################################################################
      # And non CAS related SESSION variables
      ###########################################################################
      $_SESSION['max_idle_time'] = 0;
      $_SESSION['relogin'] = FALSE;
      $_SESSION['login_method'] = "CAS";
  
      ###########################################################################
      # Set the Users's Access Levels
      ###########################################################################
      #setAccessLevel();
  
      ###########################################################################
      # Take next action based on goto flag
      ###########################################################################
      if ( isServiceInFolder($_SESSION[ 'goto' ])) {
        include $SERVICES_DIR . '/' . $_SESSION[ 'goto' ] . '/' . 'config.php';
        require $SERVICES_DIR . '/' . $_SESSION[ 'goto' ] . '/' . $_SESSION[ 'goto' ] . '.php';
        exit();
  
      } else {
        ########################################################################
        # Default Service
        ########################################################################
        echo "Redirecting to Menu\n";
        echo "Goto: " . $_SESSION['goto'] . "\n";
        //header("Location: $MENU_REDIRECT");
        exit();
      } 
    } 
  } else {
    ############################################################################
    # First time login; redirect user to UD CAS for authentication
    # Define CAS redirect URL based on goto FLAG
    ############################################################################
    if(!isServiceInFolder($goto)){
        #########################################################################
        # Unset all of the session variables. Destroy the session.
        #########################################################################
        $_SESSION = array();
        session_destroy();
  
        #########################################################################
        # Print warning page
        #########################################################################
        include( $CAS_HEADER );
        echo "<p>ERROR: Unknown service specified!";
        echo "<p><sp><sp>Service: $goto";
        echo "<p>Return to <a class='bluelink' href='http://www.engr.udel.edu/'>ENGR Home page</a></p>";
        include( $CAS_FOOTER );
        exit();
    }
  
    ############################################################################
    # Check for IPs we allow to access pages without CAS
    ############################################################################
    #include $SERVICES_DIR . '/' . $_SESSION[ 'goto' ] . '/' . 'config.php';
    #if ( isset( $SKIP_CAS_CIDR_LIST ) ) {
  
    if ( !isset($_SESSION['relogin'] ) ) {
      $_SESSION[ 'relogin' ] = FALSE;
    }
    if ($_SESSION[ 'relogin'] == TRUE){
      if ( isset( $_SESSION['login_method'] ) && $_SESSION['login_method'] == "CAS" ) {
        header( "Location:".$CAS_SERVER."login?renew=true&service=".$CAS_SERVICE ); 
      } else if ( isset( $_SESSION['login_method'] ) && $_SESSION['login_method'] == "LOCAL" ) {
        header( "Location:\login.php" ); 
      }
    }
    if (isset($_SESSION['action']) && $_SESSION['action'] == 'CAS') {
      header( "Location:".$CAS_SERVER."login?renew=true&service=".$CAS_SERVICE ); 
    } else {
      ##########################################################################
      # Login Page
      ##########################################################################
      $title= 'Login';
      $content="
        <p>To login Locally click <a href=\"login.php\">here</a></p>
        <p>To login using UDEL CAS click <a href=\"".$CAS_SERVER."login?renew=true&service=".$CAS_SERVICE."\">here</a></p>
        <p><sp><sp>Service: ${goto}<br>
      ";
  
      #include( $CAS_HEADER );
      #require 'login.php';
      print( $content );
      require 'castest/debuggingfooter.php';
      exit();
    } 
  }
}
?>

</body> </html>

<?php
###############################################################################
# Function: validate_response
###############################################################################
function validate_response($response) {
  $expected_regexp = "/<cas:authenticationSuccess>/";
  return( preg_match( $expected_regexp, $response ) );
}

###############################################################################
# Function: valid_user
###############################################################################
function valid_user( $applicant ) {
  #########################################################################
  # Check to see if the user is in the User's File (defined in config.php)
  # Check to make sure users file is readable
  #########################################################################
  global $VALID_USERS;
  $valid_user = 'false';

  if ( ! is_readable( $VALID_USERS ) ) {
    echo "<h1 class='error'>ERROR:</h1><p>Cannot read valid users file</p>\n";
    die();
  }

  $lines = file( $VALID_USERS );
  foreach( $lines as $data ) {
    ######################################################################
    #Skip comment lines demarketed by a hash (#);
    #Use === so 0 and False aren't confused
    ######################################################################
    if ( strpos( ltrim( $data ), '#' ) === 0 ) { continue; }
    if ( ! strpos( $data, ':' ) ) { continue; }

    ######################################################################
    #Seperate colon seperated values into seperate variables
    ######################################################################
    list( $user, $auth_level )= explode( ':', $data );

    ######################################################################
    # Remove whitespace before and after $user and $auth_level;
    ######################################################################
    $user=       strtolower( ltrim( rtrim( $user ) ) );
    $auth_level= strtolower( ltrim( rtrim( $auth_level ) ) );

    if ( $applicant == $user ) {
      ####################################################################
      # Valid user found...
      ####################################################################
      $valid_user= $auth_level;
      $_SESSION['auth_level']=  $auth_level;
      break;
    }
  }
  return $valid_user;
}

###############################################################################
# Function: isServiceInFolder
###############################################################################
function isServiceInFolder($serviceName){
  global $SERVICES_DIR;
  $directories = array();
  $files = array();
  
  // Check SERVICES_DIR exists and is a directory
  if ( is_dir( $SERVICES_DIR ) ) {
    if ( $handle = opendir( $SERVICES_DIR ) ) {
      while (($item = readdir($handle)) !== false) {
        // Loop through current directory and divide files and directories
        if(!is_dir($item)){
          array_push($files, ($item));
        }
      }
    closedir($handle); // Close the directory handle
    } else {
      echo "<p class=\"error\">Directory handle could not be obtained.</p>";
    }
  } else {
    echo "<p class=\"error\">Path is not a directory</p>";
  }

  ############################################################################
  # Loop through list of files, and see if we have a match with servicename
  ############################################################################
  foreach( $files as $file ){
    if($serviceName == basename($file) || $serviceName == $file || $serviceName . ".php" ==$file){
      return true;
    }
  }
  return false;
}

###############################################################################
# Function: setAccessLevel
###############################################################################
function setAccessLevel(){
  global $MYSQL_HOST;
  global $MYSQL_USER;
  global $MYSQL_PASS;
  global $MYSQL_DB;
  global $MYSQL_PORT;
  global $MYSQL_SOCKET;
  global $_SESSION;
  global $DB_CASUSERS;
  global $DB_TEST;

  $DB_TEST = "Not Connected";

  if ( isset( $_SESSION[ 'user_email' ] ) ) {
    unset($_SESSION[ 'permissions' ]);

    $DB_CASUSERS = mysqli_connect($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASS, $MYSQL_DB, $MYSQL_PORT,  $MYSQL_SOCKET);

    $DB_TEST = "Connected";
    $query = "SELECT * FROM casusers WHERE UserID='" . $_SESSION[ 'user_email' ] . "'"; 
    $db_permissions = mysqli_query($DB_CASUSERS, $query);

    if ($db_permissions){
      $DB_TEST = "Connected and Query was Valid";
      while($row = mysqli_fetch_assoc($db_permissions)){
        $service = $row [ 'Service' ];
        if( $_SESSION[ 'permissions' ][ $service ] != ""){
          $_SESSION[ 'permissions' ][ $service ] .= $_SESSION[ 'permsissions' ][ $service ] . ", " . $row[ 'Access' ];
        } else {
          $_SESSION[ 'permissions' ][ $service ] = $row[ 'Access' ];
        }
      }
      mysqli_free_result($db_permissions);
    } else {
      $DB_TEST = "Connected but Query was INVALID. Query was: $query";
    }
    mysqli_close($DB_CASUSERS);
  }
}

?>
