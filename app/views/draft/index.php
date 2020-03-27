
    <div id="homeModule">
<?php print_r($draftRow); ?>

    </div><!--'#homeModule'-->
    <div id="postsModule">
      <div id="postContainer">
        <h2>Recent post</h2>
<?php display_posts($draftId.$actualDraftid); ?>

      </div><!--'#postContainer'-->
    </div><!--'#postsModule'-->
    <div id="sectionsModule">
      <div id="sectionsContainer">
      <?php
  include_once "app/views/_nav_panel.php";
  displaySections(); ?>

      </div><!--sectionContainer-->
    </div><!--sections-->
