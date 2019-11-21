
  <div id="homeboy" class="container">
    <div id="homeModule">
      <?php if(isset($_SESSION['id']) && $_SESSION['id'] > 0) { include("views/usercontrols.php"); } ?>
      <?php display_post_box(); ?>
    </div><!--'#homeModule'-->
    <div id="postsModule">
      <?php display_navlist(); ?>

      <div id="postContainer">
        <h2>Recent posts</h2>
        <?php display_posts('public'); ?>
      </div><!--'#postContainer'-->
    </div><!--'#postsModule'-->
  </div><!--'#homeboy'-->