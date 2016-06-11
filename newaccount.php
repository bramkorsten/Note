<?php
if(isset($access) && $access){
  //File can be opened
}
else {
 // user has no direct access to the file
  header("HTTP/1.1 403 Forbidden");
  exit;
}
?>
<div class="newuser-wrapper">
  <form class="userform" action="redirect.php" method="POST">
    <p class="label">
      Username
    </p>
    <input class="logininput" type="text" name="newuser" value="" size="20">
    <br>
    <p class="label">
      Password
    </p>
    <input class="logininput" type="password" name="newpass" value="">
    <br>
    <p class="label">
      Email
    </p>
    <input class="logininput" type="email" name="newemail" value="">
    <br>
    <input class="submitbutton loginbutton" type="submit" name="usersubmit" value="Create user">
  </form>
  
  <p class="powered">
    <i>Powered by Watermelone ©</i>
  </p>
</div>
