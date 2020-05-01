<?php include("app/views/_nav_list.php") ?>

    <div id="homeModule">
    </div><!--'#homeModule'-->
    <div id="postsModule">
      <?php include("app/views/_post_form.php"); ?>
      <?php display_navlist('search'); ?>

      <div id="postContainer">
        <h2>Search results</h2>
        <?php display_posts('search'); ?>

      </div><!--'#postContainer'-->
    </div><!--'#postsModule'-->
