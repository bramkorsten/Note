<div class="allposts-wrapper">
  <div class="allposts-container">
    <h2 class="allposts-title">
      All Posts
    </h2>
    <?php 
    
    try {
      $sql = $conn->prepare("SELECT * FROM cmsData ORDER BY postTime");
      $sql->bindParam(":id", $post);
      $sql->execute();
    }
    catch (PDOException $e) {
      echo "An error has occurred: " . $e->getMessage();
    }

    if ($sql->rowCount() > 0) {
      try {
        foreach ($sql->fetchAll() as $row) {
        $title = stripslashes($row['postTitle']);
        $postID = $row['postID'];
        echo <<<ENTRY_DISPLAY

      <div class="allposts-post">
        <h3>
          $title
        </h3>
        <div class="allposts-post-body"></div>
        <div class="allposts-edit-link"><a href="index.php?edit=$postID">edit</a></div>
      </div>
  
ENTRY_DISPLAY;
        }
      }
    catch (PDOException $e) {
      echo "An error has occurred: " . $e->getMessage();
    }
    }
    ?>
  </div>
</div>
