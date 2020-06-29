<?php include("app/views/_nav_list.html.php") ?>

    <div id="homeModule">
<?php include("app/views/_user_controls.html.php"); 
      include("app/views/_post_form.html.php"); ?>

    </div><!--'#homeModule'-->
    <div id="mainModule">
<?php display_navlist('home'); ?>

      <div id="postContainer">
        <h2>Recent posts</h2>
<?php display_posts('isFollowing'); ?>

      </div><!--'#postContainer'-->
    </div><!--'#mainModule'-->
    <div id="sectionsModule">
      <div id="sectionsContainer">
      <?php
  include_once "app/views/_sections.html.php";
  displaySections(); ?>

      </div><!--sectionContainer-->
    </div><!--sections-->
