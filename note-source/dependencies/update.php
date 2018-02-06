<?php
error_reporting(-1);
ini_set('display_errors', 'On');
if (isset($_GET['deploy']) && $_GET['deploy'] == 'user') {
  require_once('../config/config.php');
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
  unlink("../../update.zip");
  $_SESSION['admin'] = false;
  $_SESSION['error'] = 'Update Succesful!';
    // Redirect
    header("Location: ../index.php");
    header("HTTP/1.1 303 See Other");
    die("redirecting");
}

else if (isset($_GET['install']) && $_GET['install'] == true) {
    /* Source File URL */
  $remote_file_url = 'https://bramkorsten.nl/downloads/note/note.zip';

  /* New file name and path for this file */
  $local_file = '../../update.zip';

  /* Copy the file from source url to server */
  $copy = copy($remote_file_url, $local_file);

  /* Add notice for success/failure */
  if (!$copy) {
      echo "There was an error while downloading the latest version of Note to the server...<br>";
  }
  else {
    echo "Update was downloaded succesfully!<br>";
    $path = pathinfo(realpath($local_file), PATHINFO_DIRNAME);
    $zip = new ZipArchive;
    $res = $zip->open($local_file);
    if ($res === TRUE) {
        $zip->extractTo($path);
        $zip->close();
        echo "Update has succesfully been extracted to $path <br>";
        $installed = true;
    }
    else {
        echo "There was an error while opening the update file! ($local_file)";
    }
  }
}

else {
  $url = 'https://api.github.com/repositories/60926745/releases/latest';
  $cURL = curl_init();
  curl_setopt($cURL, CURLOPT_URL, $url);
  curl_setopt($cURL, CURLOPT_HTTPGET, true);
  curl_setopt($cURL, CURLOPT_USERAGENT,'NoteCMS');
  curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Accept: application/json'
  ));
  curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
  $result = curl_exec($cURL);
  $metadata = json_decode($result, true);
  curl_close($cURL);
  $newversion = $metadata['tag_name'];
  $_SESSION['newversion'] = $newversion;
  require_once("Parsedown.php");
  $Parsedown = new Parsedown();
  $changelog = $Parsedown->text($metadata['body']);
  $jsonString = file_get_contents('../config/config.json');
  $data = json_decode($jsonString, true);
  $oldversion = $data['core']['version'];
  require_once("update_front.php");
}

if (isset($installed) && $installed) {
  if (isset($_SESSION['newversion'])) {
    $newversion = $_SESSION['newversion'];
  }
  else {
    die("Error while getting data from stream. You might have opened this page by accident.");
  }
  $jsonString = file_get_contents('../config/config.json');
  $data = json_decode($jsonString, true);
  $data['core']['version'] = $newversion;
  $newJsonString = json_encode($data);
  file_put_contents('../config/config.json', $newJsonString);
  echo "Update completed!<br>
	Click <a href='update.php?deploy=user'>here</a> to go to Note.";
}
?>
