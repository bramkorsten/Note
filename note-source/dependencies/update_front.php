<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ed1c24" /><!--#20C520 #ed1c24-->
    <title>Update Note</title>
    <link rel="stylesheet" href="../css/admin.css?v=1.4">
  </head>

  <body>
    <div class="login-wrapper">
      <div class="note"></div>
      <div class="loginform container-two-wrapper">
        <div class="container-two">
          <p class="updatetitle">
          Current version:
          </p>
          <h2 class="updateversion">
            <?php echo $oldversion; ?>
          </h2>
        </div>
        <div class="container-two">
          <p class="updatetitle">
            New version:
          </p>
          <h2 class="updateversion">
            <?php echo $newversion; ?>
          </h2>
        </div>
        <a href='update.php?install=true' class="updatebutton">Update</a>
      </div>
    </div>
  </body>
</html>