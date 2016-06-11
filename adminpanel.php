<?php
  session_start();
  $access = true; //required for getting config file
  require_once('watermelone.php');
?>

<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ed1c24" /><!--#20C520 #ed1c24-->
    <title>nxtCMS - Adminpanel</title>
    <link rel="stylesheet" href="../css/style.css?v=1.4">
    <link rel="stylesheet" href="css/admin.css?v=1.4">
    <link href="http://cdn.quilljs.com/latest/quill.snow.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/main.min.js"></script>
    <script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
  </head>

  <body>
    <?php
      if ($_SESSION["admin"]) {
        require('adminbar.php');
        
        if (($_GET["ref"] == 'postcreator') || (isset($_GET["edit"]))) {
          require('editor.php');
          unset($_SESSION['editID']);
        }
        else if (($error == 'postsubmitted') || ($error == 'postsubmitfailed') || ($_GET["ref"] == 'postsubmitcancelled') || ($_SESSION['error'] == 'postnonexist')) {
          require('verification.php');
          require('adminmenu.php');
        }
        else if ($_GET["ref"] == 'usercreation') {
          require('newaccount.php');
        }
        
        else if ($_GET["ref"] == 'allposts') {
          require('posts.php');
        }
        
        else if (isset($_GET["ref"]) == false) {
          require('adminmenu.php');
        }
      }
      else {
        require('loginform.php');
      }
    ?>
  </body>
  
</html>
