
    <div id="homeModule">
    </div><!--'#homeModule'-->
    <div id="postsModule">

<?php display_navlist('directory'); ?>

      <h2>Public profiles</h2>
      <div id="userContainer">

<?php displayUsers($who); ?>

      </div><!--userContainer-->
    </div><!--'#postsModule'-->
    <div id="sectionsModule">
      <div id="sectionsContainer">

<?php displaySections(); ?>

      </div><!--sectionContainer-->
    </div><!--sectionsModule-->