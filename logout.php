<?php
  if ( session_status() != 2 ) { session_start(); }
  unset($_SESSION["username"]);
  session_destroy();
  header("Location: index.php");
?>
