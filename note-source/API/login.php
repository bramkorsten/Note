<?php
// The code in this file is depreciated and should not be used on a public server. The code will soon be upgraded to PDO,
// along with changes to the core

error_reporting(-1);
ini_set('display_errors', 'On');

require_once('config.php');

$conn = new mysqli( $s_Server, $s_Username, $s_Password, $s_Database);

// json response array
$response = array("error" => FALSE);

if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['androidid'])) {

  // receiving the post params
  $username = $_POST['email'];
  $password = $_POST['password'];
  $androidID = $_POST['androidid'];
  
  // get the user by email and password
  $sql = "SELECT * FROM `user` WHERE `username` = '$username'";
  
  $result = mysqli_query($conn, $sql);
  
  if (mysqli_num_rows($result) > 0) {
  // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
      $hashedpassword = $row["password"];
      $email = $row["email"];
      $id = $row["id"];
    }
  
    if (password_verify($password, $hashedpassword)) {
      // use is found
      $sql = "SELECT `androidID` FROM `userdevices` WHERE `androidID` = '$androidID'";

      $result = mysqli_query($conn, $sql);

      if (mysqli_num_rows($result) > 0) {
      // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
          $serverAndroidID = $row["androidID"];    
        }
        $sql = "DELETE FROM `userdevices` WHERE `androidID` = '$androidID'";
        mysqli_query($conn, $sql);
      }

      $deviceID = random_bytes(30);
      $deviceID = bin2hex($deviceID);
      $sql = "INSERT INTO userdevices (username, deviceID, androidID) VALUES ('$username', '$deviceID', '$androidID')";
      mysqli_query($conn, $sql);  
      $response["error"] = FALSE;
      $response["user"]["id"] = $id;
      $response["user"]["name"] = $username;
      $response["user"]["email"] = $email;
      $response["user"]["deviceID"] = $deviceID;
      echo json_encode($response);
    } 
  }
  else {
      // user is not found with the credentials
      $response["error"] = TRUE;
      $response["error_msg"] = "Login credentials are wrong. Please try again!";
      echo json_encode($response);
  }
}

else if (isset($_POST['deviceID']) && isset($_POST['username'])) {
  $username = $_POST['username'];
  $deviceID = $_POST['deviceID'];
  $sql = "SELECT `username` FROM `userdevices` WHERE `username` = '$username' AND `deviceID` = '$deviceID'";
  
  $result = mysqli_query($conn, $sql);
  
  if (mysqli_num_rows($result) > 0) {
    $response["error"] = FALSE;
    $response["user"]["name"] = $username;
    $response["user"]["deviceID"] = $deviceID;
    echo json_encode($response);
  }
}

else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters email or password is missing!";
    echo json_encode($response);
}
?>

