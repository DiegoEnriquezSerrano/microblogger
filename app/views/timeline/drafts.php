
    <div id="homeModule">

<?php include("app/views/_user_controls.php"); ?>

    </div><!--'#homeModule'-->
    <div id="postsModule">

<?php include("app/views/_post_form.php"); ?>
<?php display_navlist('home'); ?>

      <div id="postContainer">
<?php echo $posts; ?>
        
      </div><!--'#postContainer'-->
    </div><!--'#postsModule'-->
    <div id="sectionsModule">
      <div id="sectionsContainer">

<?php displaySections(); ?>

      </div><!--sectionContainer-->
    </div><!--sectionsModule-->