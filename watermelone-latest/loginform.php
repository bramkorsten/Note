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
<div class="login-wrapper">
  <div class="melone"></div>
  <div class="message loginmessage 
              <?php if(isset($error)){
  echo ('visible');} 
              else {
                echo ('hidden');} 
              ?>">
  <p>
    <?php echo ($error);
    unset($_SESSION['error']);
    ?>
  </p>
  <div class="arrow-down"></div>
  </div>
  <form class="loginform" action="redirect.php" method="POST">
    <p class="label">
      Username
    </p>
    <input class="logininput" type="text" name="user" value="" size="20">
    <br>
    <p class="label">
      Password
    </p>
    <input class="logininput" type="password" name="pass" value="">
    <br>
    <input class="submitbutton loginbutton" type="submit" name="submit" value="Login">
  </form>
  
  <p class="powered">
    <i>Powered by <a class="audiomelonecontrol">Watermelone</a><br>
      Copyright © 2016 - Bram Korsten</i>
  </p>
  <audio id="audiomelone">
  <source src="video/watermelone.ogg" type="audio/ogg">
  </audio>
</div>
