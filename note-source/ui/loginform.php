<div class="login-wrapper">
  <div class="message loginmessage
              <?php if (isset($error) && ($error != "none")) {
  echo ('visible'); }
              else {
                echo ('hidden'); }
              ?>">
  <p>
    <?php echo ($error);
    unset($_SESSION['error']);
    ?>
  </p>
  <div class="arrow-down"></div>
  </div>
  <div class="loginform">
    <div class="gradient_header">
      <h2 class="hello">Hello!</h2>
    </div>
    <div class="login_inner_wrap">
      <h3>Welcome to Note</h3>
      <p>Please login to continue</p>
  </div>
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
  <div class="note"></div>
  <p class="powered">
    <i>Powered by Note - <?php echo($version); ?><br>
      By Bram Korsten</i>
  </p>
  <audio id="audiomelone">
  <source src="video/watermelone.ogg" type="audio/ogg">
  </audio>
</div>
