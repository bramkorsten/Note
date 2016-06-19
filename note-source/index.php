<?php
  session_start();
  require_once('note.php');
?>

<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ed1c24" /><!--#20C520 #ed1c24-->
    <title>Note - Adminpanel</title>
    <link rel="stylesheet" href="css/admin.css?v=1.4">
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/main.min.js"></script>
    <script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
    <link rel="stylesheet" href="css/tags-input.css">
    <script src="js/tags-input.js"></script>
  </head>

  <body>
    <?php
      if (isset($_SESSION["admin"]) && $_SESSION["admin"]) {
        require('ui/adminbar.php');
        
        if (!isset($_GET["ref"])) {
          $_GET["ref"] = NULL;
        }
        
        if (!isset($error)) {
          $error = NULL;
        }
        
        if (!isset($_SESSION['error'])) {
          $_SESSION['error'] = NULL;
        }
        
        if (($_GET["ref"] == 'postcreator') || (isset($_GET["edit"]))) {
          require('ui/editor.php');
          unset($_SESSION['editID']);
        }
        else if (($error == 'postsubmitted') || ($error == 'postsubmitfailed') || ($_GET["ref"] == 'postsubmitcancelled') || ($_SESSION['error'] == 'postnonexist')) {
          require('ui/update-notif.php');
          require('ui/verification.php');
          require('ui/adminmenu.php');
        }
        else if ($_GET["ref"] == 'usercreation') {
          require('ui/newaccount.php');
        }
        
        else if ($_GET["ref"] == 'allposts') {
          require('ui/posts.php');
        }
        
        else if (isset($_GET["ref"]) == false) {
          require('ui/update-notif.php');
          require('ui/adminmenu.php');
        }
      }
      else {
        require('ui/loginform.php');
      }
    ?>
  </body>
  
</html>
