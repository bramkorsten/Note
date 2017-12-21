<?php

error_reporting(-1);
ini_set('display_errors', 'On');

// Require the config file. this file reads the database info from a JSON file
require_once('config/config.php');
// The message if it needs to popup
$error = "none";
$version = $s_CoreVersion;

$conn = connect($s_Server, $s_Database, $s_Username, $s_Password);

function connect($s_Server, $s_Database, $s_Username, $s_Password) {
	try {
  $conn = new PDO("mysql:host=$s_Server;dbname=$s_Database", "$s_Username", "$s_Password");
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch (PDOException $e) {
		echo "An error has occurred: " . $e->getMessage();
	}
	return $conn;
}

sec_session_start();

function sec_session_start() {
    $session_name = 'note_session_id';   // Set a custom session name
    $usinghttps = false;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: redirect.php");
        exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $usinghttps,
        $httponly);
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();            // Start the PHP session
    session_regenerate_id(true);    // regenerated the session, delete the old one.
}

function redirect() {
	$redirecturl = 'index.php';
	header("Location: $redirecturl");
	header("HTTP/1.1 303 See Other");
	die("redirecting");
}

if (isset($_GET['post'])) {
  $post = $_GET['post'];
  if ($post == "*") {
    try {
      $sql = $conn->prepare("SELECT * FROM cmsData ORDER BY postTime");
      $sql->execute();
      encodePost($sql);
    }
    catch (PDOException $e) {
      echo "An error has occurred: " . $e->getMessage();
    }
  } else {
    // Set sql for single post
    try {
      $sql = $conn->prepare('SELECT * FROM cmsData WHERE postID = :postId ; ');
      $sql->bindParam(':postId', $post);
      $sql->execute();
      encodePost($sql);
    }
    catch (PDOException $e) {
      echo "An error has occurred: " . $e->getMessage();
    }
  }
}

if (isset($_GET['tag'])) {
  $tag = $_GET['tag'];
  // Set sql for single tag
  try {
    $sql = $conn->prepare("SELECT * FROM cmstags WHERE `tag` = :tag ");
    $sql->bindParam(":tag", $tag);
    $sql->execute();
    if ($sql->rowCount() > 0) {
      $postarray = array();
      $rowcount = 0;
      foreach ($sql->fetchAll() as $row) {
        $postarray[$rowcount] = "'" . $row['postID'] . "'";
        $rowcount++;
      }
      $sql->closeCursor();
      $posts = (string)implode(', ', $postarray);
      $sql = $conn->prepare("SELECT * FROM cmsData WHERE `postID` IN ( $posts )");
      $sql->execute();
      encodePost($sql);
    }
  }
  catch (PDOException $e) {
    echo "An error has occurred: " . $e->getMessage();
  }
}

function encodePost($sql)
{
  if ($sql->rowCount() > 0) {
    // While there are posts, format them
          date_default_timezone_set('Europe/Amsterdam');
          // Get results
          try {
            $jsonData = array();
            $i = 0;
            foreach ($sql->fetchAll() as $row) {
              $rowData = array();
              $rowData['title'] = stripslashes($row["postTitle"]);
              $bodytext = stripslashes($row["postData"]);
              $bodytext = str_replace(array('<div>', '</div>'), array('<span>', '</span>'), $bodytext);
              $rowData['body'] = $bodytext;
              $rowData['id'] = $row['postID'];
              $rowData['lastUpdate'] = date("l F jS Y \@ g:i a",$row['postTime']);
              $jsonData[$i] = $rowData;
              $i++;
            }
            echo json_encode($jsonData);
          }
      catch (PDOException $e) {
          echo "An error has occurred: " . $e->getMessage();
      }
      $sql->closeCursor();
    }
  else {
    echo "There are no posts to show!";
  }
}


?>
