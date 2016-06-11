

<!DOCTYPE html>
<html lang="en">

  <head>
    <title>NXTCMS</title>
  </head>

  <body>
<?php

  include_once('_class/nxt.php');
  $obj = new nxtCMS();
  $obj->host = 'localhost';
  $obj->username = 'deb85634_master';
  $obj->password = 'ThisIsCms';
  $obj->table = 'deb85634_cms';
  $obj->connect();

  if ( $_POST )
    $obj->write($_POST);

  echo ( $_GET['admin'] == 1 ) ? $obj->display_admin() : $obj->display_public();

?>
  </body>

</html>