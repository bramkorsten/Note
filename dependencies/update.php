<?php
error_reporting(-1);
ini_set('display_errors', 'On');
if ($_GET['deploy'] == 'user') {
	require_once('../config.php');
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
  unlink("../update.zip");
		// Redirect
		header("Location: ../adminpanel.php");
		header("HTTP/1.1 303 See Other");
		die("redirecting");
}

else if (isset($_GET['install']) && $_GET['install'] == true) {
    /* Source File URL */
  $remote_file_url = 'http://bramkorsten.io/downloads/watermelone/update.zip';

  /* New file name and path for this file */
  $local_file = '../update.zip';

  /* Copy the file from source url to server */
  $copy = copy( $remote_file_url, $local_file );

  /* Add notice for success/failure */
  if( !$copy ) {
      echo "There was an error while downloading $local_file to the server...<br>";
  }
  else{
    echo "$local_file was downloaded succesfully!<br>";
    $path = pathinfo( realpath( $local_file ), PATHINFO_DIRNAME );
    $zip = new ZipArchive;
    $res = $zip->open($local_file);
    if ($res === TRUE) {
        $zip->extractTo( $path );
        $zip->close();
        echo "$local_file has succesfully been extracted to $path <br>";
        $installed = true;
    }
    else {
        echo "There was an error while opening $local_file!";
    }
  }
}

else {
  echo("click <a href='update.php?install=true'>here</a> to download the latest version of watermelone");
}

if ($installed) {  
  $config = fopen("../config.json", "w") or die('Could not build config file');
  $database_info = array();
  $info = array();
  $info = array('version'=> '1.0');
  $database_info['core'] = $info;
	fwrite($config, json_encode($database_info));
  fclose($config);
	echo "Update completed!<br>
	Click <a href='update.php?deploy=user'>here</a> to go to watermelone.";
}
?>