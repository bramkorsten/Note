<?php
  session_start();
  require_once('note.php');
  header("Location: $redirecturl");
  header("HTTP/1.1 303 See Other");
  die("redirecting");
?>