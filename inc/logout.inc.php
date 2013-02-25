<?php
if (isset($_GET['logout']) && $_GET['logout'] == 'yes') {
  // empty session
  $_SESSION = array();
  // invalidate session cookie
  if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-86400, '/');
  }
  // end session and redirect
  session_destroy();
  header('Location: login.php');
  exit;
}
?>
<a class="brand" style="float:right;font-size: 14px;" href="tasks.php?logout=yes">Log Out</a>
