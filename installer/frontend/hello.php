<!DOCTYPE html>
<html>
  <head>
    <title>Note Installation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#2c3e50" />
    <style>
      @import url('https://fonts.googleapis.com/css?family=Montserrat:300i,400,700');
      body {
        background-color: #f1f1f1;
        height: auto;
        margin: 0;
        font-family: 'Georgia';
      }

      .login-wrapper, .newuser-wrapper {
        width: 80%;
        max-width: 450px;
        min-width: 320px;
        margin: 50px auto 25px;
      }
      .note-content-container {
        background-color: #ffffff;
        -webkit-box-shadow: 0 1px 3px rgba(0,0,0,.13);
        box-shadow: 0 1px 3px rgba(0,0,0,.13);
        display: inline-block;
        width: 100%;
      }

      .hello {
        width: 100%;
        height: auto;
      }

      .gradient {
        /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#7e43aa+0,6681ea+100 */
        background: #7e43aa; /* Old browsers */
        background: -moz-linear-gradient(45deg, #7e43aa 0%, #6681ea 100%); /* FF3.6-15 */
        background: -webkit-linear-gradient(45deg, #7e43aa 0%,#6681ea 100%); /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(45deg, #7e43aa 0%,#6681ea 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#7e43aa', endColorstr='#6681ea',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
      }

      p {
        font-family: 'Montserrat';
        padding: 20px 20px;
      }

      .continue {
        padding: 12.5px 20px;
        background: #6681ea;
        color: #fff;
        font-family: 'Montserrat';
        text-decoration: none;
        font-size: 20px;
        display: inline-block;
        margin: 20px;
        line-height: 1;
        font-weight: 700;
        text-transform: capitalize;
      }

      .continue:hover {
        background: #566dc7;
      }

      .details {
        color: #6681ea;
        font-weight: 400;
        font-size: 1em;
      }

      .details b {
        float: right;
      }

      .welcome_message {
        color: #6681ea;
        font-weight: 400;
        font-size: 1.5em;
      }

      .hello h2{
        font-family: 'Montserrat';
        font-size: 6em;
        color: #ffffff;
        font-weight: 700;
        padding: 150px 20px 20px;
        margin: 0;
        line-height: 1;
      }

      .installing h2{
        font-family: 'Montserrat';
        font-size: 3.5em;
        color: #ffffff;
        font-weight: 700;
        padding: 150px 20px 20px;
        margin: 0;
        line-height: 1;
      }

      .title {
        color: #6681ea;
        font-weight: 700;
        font-size: 2em;
        margin: 0;
        padding: 0 0 20px 20px;
        line-height: 1;
        display: inline-block;
      }

      .normal {
        color: #6681ea;
        font-size: 1em;
        margin: 0;
        padding: 0 20px 20px;
      }

      .dbform {
        margin: 20px;
      }

      .dbform .input {
        font-family: 'Montserrat';
        font-size: 1em;
        width: 100%;
        box-sizing: border-box;
        margin: 10px 0;
        border: 0;
        border-bottom: 1px solid #6681ea;
        font-weight: 700;
        color: #6681ea !important;
        padding: 10px 0;
        transition: 0.5s all;
      }

      .dbform .input:focus {
        outline: none;
        border-bottom: 1px solid #7e43aa;
      }

      ::-webkit-input-placeholder { /* WebKit, Blink, Edge */
        color:    #8e83b9;
      }
      :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
         color:    #8e83b9;
         opacity:  1;
      }
      ::-moz-placeholder { /* Mozilla Firefox 19+ */
         color:    #8e83b9;
         opacity:  1;
      }
      :-ms-input-placeholder { /* Internet Explorer 10-11 */
         color:    #8e83b9;
      }

      .submit {
        padding: 12.5px 20px;
        background: #6681ea;
        color: #fff;
        font-family: 'Montserrat';
        text-decoration: none;
        font-size: 20px;
        display: inline-block;
        margin: 0;
        line-height: 1;
        font-weight: 700;
        text-transform: capitalize;
        border: 0;
      }

      .submit:hover {
        background: #566dc7;
      }

      .note {
        height: 60px;
        background-size: contain;
        background: url('http://bramkorsten.io/downloads/note/note/img/noteblack.svg');
        margin-bottom: 25px;
        width: 100%;
        background-repeat: no-repeat;
        background-position: center;
      }
    </style>
  </head>
  <body>
  <div class='newuser-wrapper'>
    <div class='note-content-container'>
      <?php
      if (isset($_GET['deploy']) && $_GET['deploy'] == 'user') {
        require_once('note/config/config.php');
        $conn = new mysqli( $s_Server, $s_Username, $s_Password, $s_Database);
        $sql = "CREATE TABLE IF NOT EXISTS `cmsData` (
                `postID` varchar(13) NOT NULL,
                `postTitle` varchar(150) DEFAULT NULL,
                `postData` varchar(90000) DEFAULT NULL,
                `postTime` varchar(100) DEFAULT NULL,
                PRIMARY KEY (`postID`),
                UNIQUE KEY `postID` (`postID`)
              ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        if (!mysqli_query($conn, $sql)) {
          die('Invalid query: ' . mysqli_error($conn));
        }
        $sql = "CREATE TABLE IF NOT EXISTS `user` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `username` varchar(255) NOT NULL,
                `email` varchar(255) NOT NULL,
                `password` varchar(255) NOT NULL,
                `active` tinyint(1) NOT NULL DEFAULT '1',
                PRIMARY KEY (`id`),
                UNIQUE KEY `username` (`username`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16;";
        if (!mysqli_query($conn, $sql)) {
          die('Invalid query: ' . mysqli_error($conn));
        }
        $sql = "CREATE TABLE IF NOT EXISTS `userdevices` (
                `username` varchar(255) NOT NULL,
                `deviceID` varchar(255) DEFAULT NULL,
                `androidID` varchar(255) NOT NULL,
                UNIQUE KEY `androidID` (`androidID`)
              ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        if (!mysqli_query($conn, $sql)) {
          die('Invalid query: ' . mysqli_error($conn));
        }
        $sql = "CREATE TABLE IF NOT EXISTS `cmstags` (
                `postID` varchar(13) NOT NULL,
                `tag` varchar(255) DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        if (!mysqli_query($conn, $sql)) {
          die('Invalid query: ' . mysqli_error($conn));
        }

        echo '
        <html>
        <head>
        <link rel="stylesheet" href="note/css/admin.css?v=1.4">
      	<title>Note Installation</title>
        </head>
        <body>
        <div class="newuser-wrapper">
      	<div class="note"></div>
      	<div class="message loginmessage visible">
      	<p>
      		Lets finish up! Please make an admininstrator account.
        </p>
        <div class="arrow-down"></div>
        </div>
        <form class="userform" action="installer.php" method="POST">
          <p class="label">
            Username
          </p>
          <input required = "required" class="logininput" type="text" name="newuser" value="" size="20">
          <br>
          <p class="label">
            Password
          </p>
          <input required = "required" class="logininput" type="password" name="newpass" value="">
          <br>
          <p class="label">
            Email
          </p>
          <input required = "required" class="logininput" type="email" name="newemail" value="">
          <br>
          <input class="submitbutton loginbutton" type="submit" name="usersubmit" value="Create user">
        </form>

        <p class="powered">
          <i>Powered by Note - © 2016</i>
        </p>
      </div>
      </body>
      </html>
        ';
      }
      //
      // installing
      //
      else if (isset($_GET['install']) && $_GET['install'] == true) {
        echo "<div class='installing gradient'>
              <h2>Installing.</h2>
              </div>
              <p class='details'>";
        echo "Checking Compatibility...";
            if (version_compare(phpversion(), '5.3.7') < 0)
            {
              echo "<br>Your php version is not compatible with Note!<br><br>The installation failed.";
              die("Process terminated!");
            }

            $remote_file_url = 'http://bramkorsten.io/downloads/note/note.zip';
            $local_file = 'note-installer.zip';

            echo " <b>Done!</b><br>Downloading Note...";
            $copy = copy($remote_file_url, $local_file);
            if (!$copy) {
              echo " <b>Failed!</b><br>Error while downloading.<br><br>The installation failed.";
            }
            else {
            $path = pathinfo(realpath($local_file), PATHINFO_DIRNAME);
            echo " <b>Done!</b><br>Extracting dependencies...";
            }

            $zip = new ZipArchive;
            $res = $zip->open($local_file);
            if ($res === TRUE) {
                $zip->extractTo($path);
                $zip->close();
                echo " <b>Done!</b><br>";
                $installed = true;
            }
              else {
                echo " <b>Failed!</b><br>Error while extracting Note.<br>The installation failed.</p>";
              }

          if (isset($installed) && $installed) {
          echo <<<DATABASE_FORM

          <p class="title">Ready, Set...</p><br>
          <p class="normal">Enter your database credentials below.</p>
          <form class="dbform" autocomplete="new-password" action="hello.php" method="POST">
            <input class="input" type="text" id="db" name="db" value="" placeholder="Database name"><br>
            <input class="input" type="text" id="host" name="dbhost" value="localhost"><br>
            <input class="input" type="text" id="user" name="dbuser" autocomplete="new-password" value="" placeholder="Username"><br>
            <input class="input" type="password" id="pass" name="dbpass" autocomplete="new-password" value="" placeholder="Password"><br><br>
            <input class="submit" type="submit" value="Continue" name="dbsubmit">
          </form>

DATABASE_FORM;
}
}

      //
      // User Creation finished
      //
      else if (isset($_POST['usersubmit'])) {
        require_once('note/config/config.php');
      $conn = new mysqli( $s_Server, $s_Username, $s_Password, $s_Database);
        $username = strip_tags($_POST['newuser']);
        $password = strip_tags($_POST['newpass']);
        $email = strip_tags($_POST['newemail']);
        // Hash the new password
        $hashedpassword = password_hash($password, PASSWORD_BCRYPT);
        // Set the sql
        $sql = "INSERT INTO `user` (
        `username` ,
        `email` ,
        `password`
        )
        VALUES (
        '$username',  '$email',  '$hashedpassword'
        );
        ";
        // Run the query
        if (!mysqli_query($conn, $sql)) {
          echo ('Invalid query: ' . mysqli_error($conn));
          die("something went wrong while registering the administrator account! <br>
          Please ask the developer of Note for help, or check the github page.");
        }
        else {
          $_SESSION['error'] = "We're all set! Welcome to Note!";
          $_SESSION['admin'] = false;
          // Redirect
          header("Location: note/index.php");
          header("HTTP/1.1 303 See Other");
          unlink("note-installer.zip");
          unlink("hello.php");
          die("redirecting");
        }
      }
      //
      // Installation finished
      //
      else if (isset($_POST['dbsubmit'])) {
        echo "<div class='installing gradient'>
              <h2>Configuring.</h2>
              </div>
              <p class='details'>";
        echo "Fetching metadata...";
        ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)');
        $metadata = json_decode(file_get_contents('https://api.github.com/repos/Exentory/Note-CMS/releases/latest'), true);
        if (!$metadata) {
          echo "<b>Failed</b><br>Could not get metadata from stream.<br>";
          die("Installation failed!<br>The installer returned 0");
        }else{
          echo "<b>Done</b><br>Configuring settings...";
        }
        $newversion = $metadata['tag_name'];
        $config = fopen("note/config/config.json", "w") or die('<b>Failed</b><br>Could not write configuration file...<br>Installation failed');
        $database_info = array();
        $info = array('server'=> $_POST['dbhost'], 'username'=> $_POST['dbuser'], 'data'=> $_POST['db'], 'password'=> $_POST['dbpass']);
        $versioninfo = array();
        $database_info['database'] = $info;
        $database_info['core'] = array('version'=> $newversion);
        $chmoded = chmod("note/config/config.json", 0600);
        if (!$chmoded) {echo "<b>Done</b><br>Could not change the file permissions, you might be on localhost"; }
        else {
          echo "<b>Done</b></p>

          <p class='title'>Done setting up!</p>
          <p class='normal'>Please note that the config file will not be secure if ran on localhost!</p>
          <p class='details'>Desired permissions:<b>0600</b>
          <br>Current permissions: <b>"
          . substr(sprintf('%o', fileperms('note/config/config.json')), -4) .
          "</b></p>
          <a class='continue' href='hello.php?deploy=user'>Continue</a>";
        }
        fwrite($config, json_encode($database_info));
        fclose($config);
      }
      //
      // Start
      //
      else{
        if (version_compare(phpversion(), '5.3.7') >= 0)
        {
          echo "
          <div class='hello gradient'>
            <h2>Hello.</h2>
          </div>
          <p class='welcome_message'>Welcome to the Note installer! We'll set you up in no time!</p>
          <br><a class='continue' href='hello.php?install=true'>Start</a>";
        }
        else {
          echo "
          <h2>Uh Oh.</h2>
          <p>Your PHP version is <b>". phpversion() . "</b>, which is not supported by Note.<br>
          The minimal version required for Note is <b>5.3.7</b><br>
          Contact your administrator for steps on how to upgrade your php version.</p>";
        }
      }
      ?>
</div>
  </div>
  </body>
</html>
