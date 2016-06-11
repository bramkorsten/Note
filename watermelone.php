<?php
error_reporting(0);
ini_set('display_errors', 'Off');
  if(isset($access) && $access){
    //File can be opened
  }
  else {
   // User has no direct access to the file
    header("HTTP/1.1 403 Forbidden");
    exit;
  }

// Require the config file. this file reads the database info from a JSON file
require_once('config.php');
// The message if it needs to popup
$error = "none";
// Setup the connection using the info from the config
$conn = new mysqli( $s_Server, $s_Username, $s_Password, $s_Database);
// Set the redirect URL
$redirecturl = 'adminpanel.php';
// If a edit is reffered, store it when the user needs to login first
// We do this to check if the user already edited the post
// This way we prevent loops
if(isset($_SESSION['editID'])) {
  $_GET['edit'] = $_SESSION['editID'];
  }

// If the user posts to logout, exit the admin session, and set the message
if(isset($_POST['logout'])) {
   $_SESSION["admin"] = false;
   $_SESSION['error'] = 'You have been logged out.';
   header("Location: $redirecturl");
   header("HTTP/1.1 303 See Other");
   die("redirecting");
}

// If the user wants to login, check the database
if(isset($_POST['user']) && isset($_POST['pass'])) {
  $_SESSION["user"] = strip_tags($_POST['user']);
  $username = $_SESSION["user"];
	// Set the sql to get data from the server
  $sql = "SELECT `password` FROM `user` WHERE `username` = '$username'";
  
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
  // Get the password
    while($row = mysqli_fetch_assoc($result)) {
      $hashedpassword = $row["password"];
    }
  }
  
  else{
		// Set the error
    $_SESSION["error"] = 'Invalid Credentials, please try again!';
  }
  // Verify the password, and if it's correct, set the admin session
  if (password_verify( strip_tags($_POST['pass']), $hashedpassword))
  {
    $_SESSION["admin"] = true;
  }
  else {
    $_SESSION["admin"] = false;
    $_SESSION["error"] = 'Invalid Credentials, please try again!';
  }
  header("Location: $redirecturl");
  header("HTTP/1.1 303 See Other");
  die("redirecting");
}

// If a new user is submitted
else if (isset($_POST['usersubmit'])) {
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
  mysqli_query($conn, $sql);
	// Redirect
   header("Location: $redirecturl");
   header("HTTP/1.1 303 See Other");
   die("redirecting");
}

// Submit a normal post to the database
else if(isset($_POST['post-submit'])) {
	// Check if there is data, get it and format it
  if ( (isset($_POST['post-title'])) && (!empty($_POST['post-title'])) ) {$post_title = mysqli_real_escape_string($conn, $_POST['post-title']);}
  if ( (isset($_POST['post-data'])) && (!empty($_POST['post-data'])) ) {$post_data = mysqli_real_escape_string($conn, $_POST['post-data']);}
  if ( (isset($post_title)) && (isset($post_data)) ) {
		// Set the time the post was created
		// And a unique ID for the post
    $created = time();
    $uniqid = uniqid();
		// Set the sql
    $sql = "INSERT INTO cmsData (postID, postTitle, postData, postTime) VALUES('$uniqid' ,'$post_title','$post_data','$created')";
    // Run the query
		mysqli_query($conn, $sql);
		// Set the message to succes!
    $_SESSION["error"] = 'postsubmitted';
  }
  else {
		// Set the error to fail!
    $_SESSION["error"] = 'postsubmitfailed';
  }
	// Redirect
  header("Location: $redirecturl");
  header("HTTP/1.1 303 See Other");
  die("redirecting");
}

// If the user wants to edit a post
else if(isset($_POST['edit-submit'])) {
	// Check if there is data, get it and format it
  if ( (isset($_POST['post-title'])) && (!empty($_POST['post-title'])) ) {$post_title = mysqli_real_escape_string($conn, $_POST['post-title']);}
  if ( (isset($_POST['post-data'])) && (!empty($_POST['post-data'])) ) {$post_data = mysqli_real_escape_string($conn, $_POST['post-data']);}
  if ( (isset($post_title)) && (isset($post_data)) ) {
		// Get the ID that was provided with the post
		// and reset the session to prevent loops (see top of page)
    $editID = $_SESSION['edit-ID'];
    unset($_SESSION['edit-ID']);
		// Set sql
    $sql = "UPDATE `cmsData` SET `postTitle` = '$post_title', `postData` = '$post_data' WHERE  `postID` =  '$editID'";
    // Run query
		mysqli_query($conn, $sql);
		// Set message
    $_SESSION["error"] = 'postsubmitted';
  }
  else {
		// Set error
    $_SESSION["error"] = 'postsubmitfailed';
  }
  
   header("Location: $redirecturl");
   header("HTTP/1.1 303 See Other");
   die("redirecting");
  
}

// If the user did not enter all the login fields
else if (isset($_POST['user']) || isset($_POST['pass'])) 
{
	// Set the error
  $_SESSION["error"] = 'Please fill in all fields';
	// Set the admin session to false for safety
  $_SESSION['admin'] = false;
	// Redirect
  header("Location: $redirecturl");
  header("HTTP/1.1 303 See Other");
  die("redirecting");
}

// The main code for editing
// If the code gets throught the edit ID's without loops, this code runs
if(isset($_GET['edit'])) {
  $editID=$_GET['edit'];
  $_SESSION['edit-ID'] = $editID;
	// If the user is logged in, continue editing
  if ($_SESSION['admin']) {
    // Get the post
    $sql = "SELECT `postTitle`, `postData` FROM `cmsData` WHERE `postID` = '$editID'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
    // output data of each row
      while($row = mysqli_fetch_assoc($result)) {
        $edittitle = $row["postTitle"];
        $editbody = $row["postData"];
        $editbody = str_replace('"', "'", $editbody);
      }
    }
		// If the post doesn't exist, set error and redirect
    else {
      $_SESSION['error'] = 'postnonexist';
      header("Location: $redirecturl");
      header("HTTP/1.1 303 See Other");
      die("redirecting");
    }
  }
  else {
    $_SESSION['editID'] = $editID;
  }
  
}

// If the user wants to delete a post
if(isset($_GET['delete'])) {
  $deleteID=$_GET['delete'];
	// Check if he is logged in
  if($_SESSION['admin'])
  {
		// Set sql and run query
    $sql = "DELETE FROM `cmsData` WHERE `postID` = '$deleteID'";
    mysqli_query($conn, $sql);
  }
	// Redirect if not logged in
  header("Location: $redirecturl");
  header("HTTP/1.1 303 See Other");
  die("redirecting");
}

// get statistics from the database, just for bants
$sql = "SELECT `postId` FROM `cmsData`";
$result = mysqli_query($conn, $sql);
$post_count = mysqli_num_rows($result);
$error = $_SESSION['error'];

// The main function for displaying posts
// This function can be called from anywhere in the code as long as
// watermelone.php is included in the file
// The function needs a connection, which can just be the standard
// $conn, and can have a postID
// If a postID is specified, it will only display that post
function display_public($connection, $post = NULL) {
  if ($post == NULL) {
		// Set sql for all posts
    $sql = "SELECT * FROM cmsData ORDER BY postTime";
  }
  else {
		// Set sql for single post
    $sql = "SELECT * FROM cmsData WHERE `postID` = '$post' ORDER BY postTime";
  }
		// Get results
    $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));

    if ( mysqli_num_rows($result) > 0 ) {
			// While there are posts, format them
		 	while ( $a = mysqli_fetch_assoc($result) ) {
				$title = stripslashes($a['postTitle']);
				$bodytext = stripslashes($a['postData']);
				// Set the timezone to display correct post time
				date_default_timezone_set('Europe/Amsterdam');
				// Format the time to a date
				$nUpdated = date("l F jS Y \@ g:i a",$a['postTime']);
				// Replace any divs with spans (for old editor)
				$bodytext = str_replace(array('<div>', '</div>'), array('<span>', '</span>'), $bodytext);
				// more replacement, not needed now
				// $bodytext = preg_replace('!^<div>(.*?)</div>$!i', '$1', $bodytext);
				$postID = $a['postID'];
				// The format for a post
				// It has a title, body, post date, edit link and vector img
				echo <<<ENTRY_DISPLAY

      <article class="note c_b_white">
			<h1>
				$title
			</h1>
				$bodytext
		  <div class="post-date">$nUpdated - <a href="../watermelone/adminpanel.php?edit=$postID">edit</a></div>
			<div class="scribble"></div>
    </article>
  
ENTRY_DISPLAY;
      }
    }
	// Return the posts, which are all saves in the variable
  return $entry_display;
}

// If there is a connection error, display it
if ($conn->connect_error) {
  echo ("Connection failed: " . $conn->connect_error);
}

?>