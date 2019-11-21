
<div id="homeboy" class="container">
  <div id="homeModule">
  </div><!--'#homeModule'-->
  <div id="postsModule">
    <?php display_post_box(); ?>
    <?php display_navlist(); ?>

    <div id="postContainer">
      <h2>Posts for you</h2>
      <?php display_posts('isFollowing'); ?>
    </div><!--'#postContainer'-->
  </div><!--'#postsModule'-->
</div><!--'#homeboy'-->
