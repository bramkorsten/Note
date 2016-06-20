<div class="editor-wrapper">
  <form action="redirect.php" method="POST">
  <div class="editor-toolbar">
  <input class="post-title-input" type="text" name="post-title" value="<?php if(isset($_GET['edit'])) {echo ($edittitle);} else {echo ('Enter a title');}?>" onfocus="<?php 
                                                                                                                                                                     if(!isset($_GET['edit']))
                                                                                                                                                                     {
                                                                                                                                                                       echo ("if(this.value==this.defaultValue)this.value='';");
                                                                                                                                                                     }?>">
  </div>
    <textarea name="post-data" id="editor1" rows="10" cols="80">
                <?php if(isset($_GET['edit'])) {echo ($editbody);} else {echo ('<p>If you have something to say, <b>say it</b>!</p>');}?>
            </textarea>
    <input name="metatags" type="tags" value="<?php if(isset($_GET['edit'])) {echo ($edittags);} ?>" placeholder="metatags">
    <script>
      [].forEach.call(document.querySelectorAll('input[type="tags"]'), tagsInput);
        CKEDITOR.replace( 'editor1' );
    </script>
    <input class="post-submit-input" type="submit" name="<?php if(isset($_GET['edit'])) {echo ('edit-submit');} else {echo ('post-submit');}?>" value="<?php if(isset($_GET['edit'])) {echo ('Update');} else {echo ('Publish');}?>">
    <a class="post-cancel-input" href="index.php?ref=postsubmitcancelled">Cancel</a>
    <?php 
    if(isset($_GET['edit'])) {
      echo ('<a href="redirect.php?delete=' . $_GET['edit'] . '" class="deletepost post-delete-input" data-confirm="Are you sure you want to delete this post? There is no turning back after this!">Delete</a>');
    }
    ?>
  </form>
</div>