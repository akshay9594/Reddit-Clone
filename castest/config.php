<?php
  #############################################################################
  # Set name of this server; used to construct URLs, among other things
  #############################################################################
  $THIS_SERVER= "redtrial-kavana96.c9users.io";

  #############################################################################
  # Administrator contact info for the cas portion of the PHP site
  # This should be a valid HTML string
  #############################################################################
  $ADMIN_CONTACT= "Trevor Buttrey (<a class='bluelink' href='mailto:tbuttrey@udel.edu'>tbuttrey@udel.edu</a>)";

  #############################################################################
  # MySQL Database Connection Info
  #############################################################################
  $MYSQL_HOST= "localhost";
  $MYSQL_USER= "user";
  $MYSQL_PASS= "";
  $MYSQL_DB= "users";
  $MYSQL_PORT= NULL;
  $MYSQL_SOCKET= "/db/tmp/mysql.sock";
  #############################################################################
  # Timeouts
  #############################################################################
  $TIMEOUT['default'] = 1800; //the default timeout when one is not specified
  $TIMEOUT['max'] = 3600;     //the max timeout.  Timeouts higher than this will be effectively be this time
  //$MAXIDLETIME


  #############################################################################
  # FQDN of the UD Central Authentication Service
  #############################################################################
  $CAS_SERVER= 'https://cas.nss.udel.edu/cas/';

  #############################################################################
  # Outgoing Mail Info
  #############################################################################
  $SMTP= '';

  #############################################################################
  # Login Page on THIS server
  # This should be a relative link, beginning with a forward slash
  #############################################################################
  $LOGIN_PAGE = '/index.php';

  #############################################################################
  # Default Service
  #############################################################################
  $MENU_PAGE = '/castest/menu.html';

  #############################################################################
  # Header/Footer definitions
  #############################################################################
  $CAS_HEADER= 'header.html';
  $CAS_FOOTER= 'footer.html';

  #############################################################################
  # Define services directory.  All valid services will have a definition PHP
  # located here.
  #############################################################################
  $SERVICES_DIR= 'castest/services';
  
  #############################################################################
  # Define Index-Files directory.  All files needed by index.php will be
  # located here.
  #############################################################################
  $INDEX_DIR= 'castest';

  #############################################################################
  # Choose whether or not to disable debugging
  # This should be TRUE when not testing.
  #############################################################################
  $DISABLE_DEBUG= False;
  $GLOBAL_DEBUG= False;

  #############################################################################
  # DON'T CHANGE BELOW HERE
  #############################################################################
  $SECURE=        'https://';
  $UNSECURE=      'http://';
  $CAS_SERVICE=   $SECURE . $THIS_SERVER . $LOGIN_PAGE;

  $MENU_REDIRECT= $UNSECURE . $THIS_SERVER . $MENU_PAGE;
?>
