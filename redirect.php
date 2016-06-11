<?php
  session_start();
  $access = true; //required for getting config file
  require_once('watermelone.php');
  header("Location: $redirecturl");
  header("HTTP/1.1 303 See Other");
  die("redirecting");
?>