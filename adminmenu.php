<div class="admin-menu-wrapper">
  <p class="welcomemessage">
    Welcome back, <?php echo $_SESSION['user']; ?>
  </p>
  <div class="admin-container admin-menu">
    <h2>
      Quick Actions
    </h2>
    <p>
      Start by adding a post right 
      <a href="adminpanel.php?ref=postcreator">here</a>!
    </p>
    <p>
      Look at 
      <a href="adminpanel.php?ref=allposts">all Posts</a>
    </p>
    <p>
      Add a new account 
      <a href="adminpanel.php?ref=usercreation">here</a>!
    </p>
  </div>
  
  <div class="admin-container stats-wrapper">
    <h2>
      Statistics
    </h2>
    <div class="stats-container">
      <h1 class="countervalue">
        <?php echo ($post_count); ?>
      </h1>
      <p>
        posts written!
      </p>
    </div>
    <div class="stats-container">
      <h1 class="countervalue">
        0
      </h1>
      <p>
        comments placed
      </p>
    </div>
    <div class="stats-container">
      <h1 class="countervalue">
        76
      </h1>
      <p>
        random stuff
      </p>
    </div>
  </div>
</div>
