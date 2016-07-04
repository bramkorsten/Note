<?php
// The code in this file is depreciated and should not be used on a public server. The code will soon be upgraded to PDO,
// along with changes to the core

error_reporting(-1);
ini_set('display_errors', 'On');

require_once('config.php');

$conn = new mysqli( $s_Server, $s_Username, $s_Password, $s_Database);

// json response array
$response = array("error" => FALSE);

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {

  // receiving the post params
  $username = strip_tags($_POST['name']);
  $email = strip_tags($_POST['email']);
  $password = strip_tags($_POST['password']);
  
  $sql = "SELECT `id` FROM `user` WHERE `email` = '$email'";
  // check if user is already existed with the same email
  $result = mysqli_query($conn, $sql);
  if (($result) && (mysqli_num_rows($result) > 0)) {
    // there are results in $result
     $response["error"] = TRUE;
     $response["error_msg"] = "User already existed with " . $email;
     echo json_encode($response);
  } else {
    // no results
    $hashedpassword = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO `user` (`username` ,`email` ,`password`)
    VALUES ('$username',  '$email',  '$hashedpassword');";
    mysqli_query($conn, $sql);
  
    $sql = "SELECT * FROM `user` WHERE `username` = '$username'";
    $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
  // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
      $hashedpassword = $row["password"];
      $email = $row["email"];
      $id = $row["id"];
      $username = $row["username"];
    }
      // user stored successfully
      $response["error"] = FALSE;
      $response["uid"] = $id;
      $response["user"]["name"] = $username;
      $response["user"]["email"] = $email;
      echo json_encode($response);
    } else {
      // user failed to store
      $response["error"] = TRUE;
      $response["error_msg"] = "Unknown error occurred in registration!";
      echo json_encode($response);
    }
  }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (name, email or password) is missing!";
    echo json_encode($response);
}
?>