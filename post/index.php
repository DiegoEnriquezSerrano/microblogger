<?php 
  require_once("../functions.php");
  require_once("../post/functions.php");
  include("../views/header.php");
  display_header_and_styles($new=array($homeStyles, $postStyles));
?>

    <div id="homeModule">
<?php print_r($postRow); ?>

    </div><!--'#homeModule'-->
    <div id="postsModule">
      <div id="postContainer">
        <h2>Recent post</h2>
<?php display_posts($postId.$actualPostid); ?>

      </div><!--'#postContainer'-->
    </div><!--'#postsModule'-->
    <div id="sectionsModule">
      <div id="sectionsContainer">
<?php displaySections(); ?>

      </div><!--sectionContainer-->
    </div><!--sections-->

<?php include("../views/footer.php"); ?>
