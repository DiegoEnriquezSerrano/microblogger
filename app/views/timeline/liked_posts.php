<?php include("app/views/_nav_list.php") ?>

    <div id="homeModule">

<?php include("app/views/_user_controls.php"); ?>

    </div><!--'#homeModule'-->
    <div id="postsModule">

<?php include("app/views/_post_form.php"); ?>
<?php display_navlist('home'); ?>

      <div id="postContainer">
        <h2>Posts for you</h2>
        
<?php display_posts('liked'); ?>

      </div><!--'#postContainer'-->
    </div><!--'#postsModule'-->
    <div id="sectionsModule">
      <div id="sectionsContainer">

<?php include_once "app/views/_nav_panel.php";
displaySections(); ?>

      </div><!--sectionContainer-->
    </div><!--sectionsModule-->