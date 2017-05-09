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
      <h3>Please login to continue.</h3>
      <form class="inner_login_form" action="redirect.php" method="POST">
        <input class="logininput" autocomplete="new-password" type="text" name="user" value="" placeholder="Username" size="20">
        <br>
        <input class="logininput" autocomplete="new-password" type="password" placeholder="Password" name="pass" value="">
        <br>
        <input class="submitbutton loginbutton" type="submit" name="submit" value="Login">
      </form>
  </div>
  </div>

  <div class="note"></div>
  <p class="powered">
    <i><?php echo($version); ?></i>
  </p>
  <audio id="audiomelone">
  <source src="video/watermelone.ogg" type="audio/ogg">
  </audio>
</div>
