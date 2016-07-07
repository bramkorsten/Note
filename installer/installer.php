<?php
error_reporting(-1);
ini_set('display_errors', 'On');
if (isset($_GET['deploy']) && $_GET['deploy'] == 'user') {
  require_once('note/config/config.php');
  $conn = new mysqli( $s_Server, $s_Username, $s_Password, $s_Database);
  $sql = "CREATE TABLE IF NOT EXISTS `cmsData` (
          `postID` varchar(13) NOT NULL,
          `postTitle` varchar(150) DEFAULT NULL,
          `postData` varchar(10000) DEFAULT NULL,
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
} else if (isset($_GET['install']) && $_GET['install'] == true) {
	echo "<code>
	Hello World...<br>Your PHP version: <b>" . phpversion() . "</b><br>Setting up...";
	if (version_compare(phpversion(), '5.3.7') < 0)
	{
		echo "
		Failed!<br>Your php version is not compitable with Note!<br>The installer returned 0<br>";
		die("Process terminated!");
	}
  $remote_file_url = 'http://bramkorsten.io/downloads/note/note.zip';
  $local_file = 'note-installer.zip';
	echo "Done!<br>Downloading Note...";
  $copy = copy($remote_file_url, $local_file);
  if (!$copy) {
      echo "Failed!<br>There was an error while downloading Note to the server...<br>The installation has failed<br>The installer returned 0";
  }
  else {
    $path = pathinfo(realpath($local_file), PATHINFO_DIRNAME);
		echo "Done!<br>Download successful<br><br>Extracting to ". $path . "...";
    $zip = new ZipArchive;
    $res = $zip->open($local_file);
    if ($res === TRUE) {
        $zip->extractTo($path);
        $zip->close();
        echo "Done!<br>";
        $installed = true;
    } else {
        echo "Failed!<br>There was an error while extracting Note...<br>The installation has failed<br>The installer returned 0</code>";
    }
  }
  
  if (isset($installed) && $installed) {
    echo <<<DATABASE_FORM

		Please enter the details of your database connection.<br>
		Note will never, under any circumstances, share credentials with anyone.
		<br><br>
    <form action="installer.php" method="POST">
			Database name:<br>
			<input type="text" name="db" value=""><br>
			Database location:<br>
			<input type="text" name="dbhost" value="localhost"><br>
			Username:<br>
			<input type="text" name="dbuser" value=""><br>
			Password:<br>
			<input type="password" name="dbpass" value=""><br><br>
			<input type="submit" value="Continue" name="dbsubmit">
    </form>
  	</code>

DATABASE_FORM;
}
}

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
    unlink("note.zip");
    unlink("note-installer.php");
    $_SESSION['error'] = "We're all set! Welcome to Note!";
    $_SESSION['admin'] = false;
    // Redirect
    header("Location: note/index.php");
    header("HTTP/1.1 303 See Other");
    die("redirecting");
  }
}

else if (isset($_POST['dbsubmit'])) {
	echo "<code>
	Getting metadata from release...";
  ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)'); 
  $metadata = json_decode(file_get_contents('https://api.github.com/repos/Exentory/Note-CMS/releases/latest'), true);
	if (!$metadata) {
		echo "Failed!<br>Could not get metadata from stream.<br>";
		die("Installation failed!<br>The installer returned 0");
	}else{
		echo "Done!<br>Writing to configuration file...";
	}
  $newversion = $metadata['tag_name'];
  $config = fopen("note/config/config.json", "w") or die('Failed!<br>Could not write configuration file...<br>Installation failed');
  $database_info = array();
  $info = array('server'=> $_POST['dbhost'], 'username'=> $_POST['dbuser'], 'data'=> $_POST['db'], 'password'=> $_POST['dbpass']);
  $versioninfo = array();
  $database_info['database'] = $info;
  $database_info['core'] = array('version'=> $newversion);
  $chmoded = chmod("note/config/config.json", 0600); 
  if (!$chmoded) {echo "Done!<br>Could not change the file permissions, you might be on localhost"; }
  else {
    echo "Done!<br>Configuration file created!<br>Please note that the config file will not be secure if ran on localhost!<br>
		[note-path]/config/config.json should have permissions <b>0-6-0-0</b><br>Current permissions: <b>"
		. substr(sprintf('%o', fileperms('note/config/config.json')), -4) .
		"</b><br><br>
    Click <a href='installer.php?deploy=user'>here</a> to continue setting up Note.";
  }
  fwrite($config, json_encode($database_info));
  fclose($config);
}

else {
  echo "
	<html>
		<head>
			<title>Note Installation</title>
			<style>
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
					margin: 10% auto 25px;
				}
				.note-content-container {
					background-color: #ffffff;
					-webkit-box-shadow: 0 1px 3px rgba(0,0,0,.13);
					box-shadow: 0 1px 3px rgba(0,0,0,.13);
					display: inline-block;
					width: 100%;
				}
				
				h2, p {
					font-family: 'Georgia';
					text-align: center;
					padding: 20px 20px;
					font-style: italic;
				}
				
				h2 {
					font-size: 3em;
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
			<div class='note'></div>
			<div class='note-content-container'>";
	if (version_compare(phpversion(), '5.3.7') >= 0)
	{
		echo "
		<h2>Hello.</h2>
		<p>click <a href='installer.php?install=true'>here</a> to download the latest version of Note</p>";
	}
	else {
		echo "
		<h2>Uh Oh.</h2>
		<p>Your PHP version is <b>". phpversion() . "</b>, which is not supported by Note.<br>
		The minimal version required for Note is <b>5.3.7</b><br>
		Contact your administrator for steps on how to upgrade your php version.</p>";
	}
	
	echo ("</div>
		</div>
		</body>
	</html>
	");
}
?>