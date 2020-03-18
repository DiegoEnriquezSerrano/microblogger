
    <div id="homeModule">
<?php include("views/usercontrols.php"); ?>

    </div><!--'#homeModule'-->
    <div id="postsModule">
<?php display_navlist('home'); ?>

      <div id="postContainer">
        <h2>Recent posts</h2>
<?php display_posts('isFollowing'); ?>

      </div><!--'#postContainer'-->
    </div><!--'#postsModule'-->
