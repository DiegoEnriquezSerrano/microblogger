
  <div id="homeboy" class="container">
    <div id="homeModule">
      <?php if(isset($_SESSION['id']) && $_SESSION['id'] > 0) { include("views/usercontrols.php"); } ?>
    </div><!--'#homeModule'-->
    <div id="postsModule">
      <?php displayPostBox(); ?>
      <?php displayNavlist(); ?>

      <div id="postContainer">
        <h2>Recent postss</h2>
        <?php displayPosts('public'); ?>
      </div><!--'#postContainer'-->
    </div><!--'#postsModule'-->
  </div><!--'#homeboy'-->