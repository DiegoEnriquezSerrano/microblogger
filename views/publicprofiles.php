
  <div id="homeboy" class="container">
    <div id="homeModule">
    </div><!--'#homeModule'-->
    <div id="postsModule">
      <?php display_post_box(); ?>
      <?php display_navlist(); ?>
      <div id="postContainer">
        <?php if (isset($_GET['username'])) {?>
          <?php 
          $username = 'username=';
          $actualUsername = $_GET['username'];
          display_posts('$username.$actualUsername'); ?>
        <?php } else { ?>
          <h2>Public profiles</h2>
          <?php display_users(); ?>
        <?php } ?>
      </div><!--'#postContainer'-->
    </div><!--'#postsModule'-->
  </div><!--'#homeboy'-->