<?php include("app/views/_nav_list.php") ?>

    <div id="homeModule">
    </div><!--'#homeModule'-->
    <div id="postsModule">
      <?php include("app/views/_post_form.php"); ?>
      <?php display_navlist('home'); ?>

      <div id="postContainer">
        <h2>Posts for you</h2>
        <?php display_posts('isFollowing'); ?>

      </div><!--'#postContainer'-->
    </div><!--'#postsModule'-->
