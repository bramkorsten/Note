<?php
error_reporting(0);
ini_set('display_errors', 'Off');
if ($_GET['deploy'] == 'user') {
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
    
  echo '
  <html>
  <head>
  <link rel="stylesheet" href="note/css/admin.css?v=1.4">
  </head>
  <body>
  <div class="newuser-wrapper">
	<div class="note"></div>
	<div class="message loginmessage visible">
	<p>
		To finish the deployment, please make an admininstrator account.
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

else if (isset($_GET['install']) && $_GET['install'] == true) {
    /* Source File URL */
  $remote_file_url = 'http://bramkorsten.io/downloads/note/note.zip';

  /* New file name and path for this file */
  $local_file = 'installer.zip';

  /* Copy the file from source url to server */
  $copy = copy( $remote_file_url, $local_file );

  /* Add notice for success/failure */
  if( !$copy ) {
      echo "There was an error while downloading Note to the server...\n";
  }
  else{
    echo "Note was downloaded succesfully!\n";
    $path = pathinfo( realpath( $local_file ), PATHINFO_DIRNAME );
    $zip = new ZipArchive;
    $res = $zip->open($local_file);
    if ($res === TRUE) {
        $zip->extractTo( $path );
        $zip->close();
        echo "Note has succesfully been extracted to $path";
        $installed = true;
    }
    else {
        echo "There was an error while opening Note!";
    }
  }
  
  if ($installed) {
    echo <<<DATABASE_FORM

    <form action="installer.php" method="POST">
			 <input type="text" name="db" value="database"><br>
       <input type="text" name="dbhost" value="localhost"><br>
       <input type="text" name="dbuser" value="username"><br>
       <input type="password" name="dbpass" value="password"><br>
       <input type="submit" name="dbsubmit">
    </form>
  
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
  }
	else {
		unlink("installer.zip");
		unlink("installer.php");
		// Redirect
		header("Location: note/index.php");
		header("HTTP/1.1 303 See Other");
		die("redirecting");
	}
}

else if (isset($_POST['dbsubmit'])) {
	ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)'); 
	$metadata = json_decode(file_get_contents('https://api.github.com/repos/Exentory/Note-CMS/releases/latest'), true);
	$newversion = $metadata['tag_name'];
  $config = fopen("note/config/config.json", "w") or die('Could not build config file');
  $database_info = array();
  $info = array('server'=> $_POST['dbhost'], 'username'=> $_POST['dbuser'], 'data'=> $_POST['db'], 'password'=> $_POST['dbpass']);
  $versioninfo = array();
	$database_info['database'] = $info;
	$database_info['core'] = array('version'=> $newversion);
  $chmoded = chmod("note/config/config.json", 0600); 
  if (!$chmoded){echo "Could not change the file permissions, you might be on localhost";}
  else {
    echo "Configuration file created! <br> Please note that the config file will not be safe if run on localhost! <br>
    Click <a href='installer.php?deploy=user'>here</a> to continue setting up Note.";
  }
  fwrite($config, json_encode($database_info));
  fclose($config);
}

else {
  echo("click <a href='installer.php?install=true'>here</a> to download the latest version of Note");
}
?>