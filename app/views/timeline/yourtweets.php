<?php include("app/views/_nav_list.php") ?>

    <div id="homeModule">
    </div><!--'#homeModule'-->
    <div id="postsModule">
      <?php include("app/views/_post_form.php"); ?>
      <?php display_navlist('home'); ?>

      <div id="postContainer">
        <h2>Your posts</h2>
        <?php display_posts('yourposts'); ?>
        
      </div><!--'#postContainer'-->
    </div><!--'#postsModule'-->
