<?php
session_start();
$_SESSION = array(); // Destroy all session variables.
session_destroy(); // Destroy the session itself.
header("Location:index.php"); // Redirect to the login page.
exit;
