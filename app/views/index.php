<?php include("app/views/_nav_list.php") ?>

    <div id="homeModule">
<?php include("app/views/_user_controls.php"); 
      include("app/views/_post_form.php"); ?>

    </div><!--'#homeModule'-->
    <div id="postsModule">
<?php display_navlist('home'); ?>

      <div id="postContainer">
        <h2>Recent posts</h2>
<?php display_posts('isFollowing'); ?>

      </div><!--'#postContainer'-->
    </div><!--'#postsModule'-->
