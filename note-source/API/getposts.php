<?php
error_reporting(-1);
ini_set('display_errors', 'On');

$response = array();

require_once('config.php');

$conn = new mysqli( $s_Server, $s_Username, $s_Password, $s_Database);

if (isset($_GET['post'])) {
  if ($_GET['post'] == 'ALL') {
    $sql = "SELECT * FROM `cmsData`";

    $result = mysqli_query($conn, $sql);

      if (mysqli_num_rows($result) > 0) {
      // output data of each row
        $i = 0;
        while($row = mysqli_fetch_assoc($result)) {
          $response[$i]["title"] = stripslashes($row["postTitle"]);
          $bodytext = stripslashes($row["postData"]);
          $bodytext = str_replace(array('<p>', '</p>'), array('<span>', '</span>'), $bodytext);
          $response[$i]["body"] = $bodytext;
          $response[$i]["time"] = $row["postTime"];
          $response[$i]["id"] = $row["postID"];
          $i++;
        }
      }
    echo json_encode($response);
  }
}
else if (isset($_POST['post'])) {
  $postID = $_POST['post'];
  $sql = "SELECT * FROM `cmsData` WHERE postID = '$postID'";

  $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
    // output data of each row
      while($row = mysqli_fetch_assoc($result)) {
        $response["error"] = FALSE;
        $response["post"]["postTitle"] = stripslashes($row["postTitle"]);
        $bodytext = stripslashes($row["postData"]);
        $bodytext = str_replace(array('<p>', '</p>'), array('<span>', '</span>'), $bodytext);
        $response["post"]["postBody"] = $bodytext;
        $response["post"]["postTime"] = $row["postTime"];
        $response["post"]["postID"] = $row["postID"];
      }
    }
  echo json_encode($response);
}

else if(isset($_POST['delete'])) {
  $deleteID=$_POST['delete'];
  $sql = "DELETE FROM `cmsData` WHERE `postID` = '$deleteID'";
  mysqli_query($conn, $sql);
  $response["error"] = FALSE;
  echo json_encode($response);
}

else {
  $response["error"] = TRUE;
  $response["error_msg"] = "Looks like something went wrong!";
  echo json_encode($response);
}
?>