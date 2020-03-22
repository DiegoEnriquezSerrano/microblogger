<?php include("app/views/_nav_list.php") ?>

    <div id="homeModule">
    </div><!--'#homeModule'-->
    <div id="postsModule">
      <?php display_navlist('profiles'); ?>

      <h2>Public profiles</h2>
      <div id="userContainer">
          <?php displayUsers('all'); ?>

      </div><!--userContainer-->
    </div><!--'#postsModule'-->
