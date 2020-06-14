<div id="postsModule">
  <div id="messagesContainer">
    <div id="messagesHeader">
      <div id="newThreadButton">
        <a href="<?php echo HOME_DIRECTORY; ?>inbox/create">+</a>
      </div>
      <div id="messagesLabel">Messages</div>
    </div><!--messagesHeader-->
<?php displayMessages(); ?>

  </div><!--messagesContainer-->
</div><!--'#postsModule'-->
<div id="sectionsModule">
  <div id="sectionsContainer">
<?php echo $sections ?>

  </div><!--sectionContainer-->
</div><!--sectionsModule-->