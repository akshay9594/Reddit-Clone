<?php
###########################################################################
# Relogin
# This page is displayed when the user tries to access a service for which
# They are timed out
###########################################################################

###########################################################################
# Include the ENGR header
###########################################################################
#include("/www/nwp/main/html/include/engrIT/header.html");

###########################################################################
# Display Page 
###########################################################################
echo "<h1>You have been timed out</h1><br>";
echo "Click <a href=\"$LOGIN_PAGE?action=relogin&goto=$_SESSION[goto]&$_SESSION[query_string]\">Here</a> to Relogin. <br>OR<br>Click <a href=\"$LOGIN_PAGE?action=logout\">Here</a> to Logout.";
echo "<br><br>";
echo "WARNING: By Choosing to relogin you will not be able to access any services (including those you may not be timed out for) until you have successfully authenticated again.";

###########################################################################
# include Footer
###########################################################################
  include($INDEX_DIR . "/debuggingfooter.php");

#include("/www/nwp/main/html/include//engrIT/footer.html");

?>
