
    <div id="homeModule">

<?php include("app/views/_user_controls.php"); ?>

    </div><!--'#homeModule'-->
    <div id="postsModule">

<?php include("app/views/_post_form.php"); ?>
<?php echo $navlist; ?>

      <div id="postContainer">
          
<?php echo $posts ?>

      </div><!--'#postContainer'-->
    </div><!--'#postsModule'-->
    <div id="sectionsModule">
      <div id="sectionsContainer">

<?php echo $sections; ?>

      </div><!--sectionContainer-->
    </div><!--sectionsModule-->