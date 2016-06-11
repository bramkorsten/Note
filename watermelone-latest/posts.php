<?php
  if(isset($access) && $access){
    //File can be opened
  }
  else {
   // user has no direct access to the file
    header("HTTP/1.1 403 Forbidden");
    exit;
  }

?>

<div class="allposts-wrapper">
  <div class="allposts-container">
    <h2 class="allposts-title">
      All Posts
    </h2>
    <?php 
    $sql = "SELECT * FROM cmsData ORDER BY postTime";
    $result = mysqli_query($conn, $sql);

    if ( mysqli_num_rows($result) > 0 ) {
     while ( $a = mysqli_fetch_assoc($result) ) {
        $title = stripslashes($a['postTitle']);
        $bodytext = stripslashes($a['postData']);
        $bodytext = str_replace(array('<div>', '</div>'), array('<span>', '</span>'), $bodytext);
        $postID = $a['postID'];
        echo <<<ENTRY_DISPLAY

      <div class="allposts-post">
        <h3>
          $title
        </h3>
        <p class="allposts-post-body">$bodytext</p>
        <div class="allposts-edit-link"><a href="adminpanel.php?edit=$postID">edit</a></div>
      </div>
  
ENTRY_DISPLAY;
      }
    }
    ?>
  </div>
</div>