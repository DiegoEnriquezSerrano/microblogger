
    <div id="editModule">

<?php 
$editModule = <<<DELIMETER
<div id="edit_container">
  <form id="edit_form">
    <label for="username" class="label one">Username</label>
    <input class="input one" name="username" value="{$userName}">
    <label for="displayname" class="label two">Display name</label>
    <input class="input two" name="displayname" value="{$user['user_display_name']}">
    <label for="bio" class="label three">Bio</label>
    <textarea name="bio" class="input three">{$userBio}</textarea>
  </form>
</div><!--edit_container-->
DELIMETER;
echo $editModule; ?>
      <div class="image_upload_box">
        <form action="actions.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="file" value="" class="active">
        <button type="submit" name="submit">UPLOAD</button>
        </form>
      </div>

    </div><!--'#editModule'-->
    <div id="sectionsModule">
      <div id="sectionsContainer">
<?php
  include_once "app/views/_nav_panel.html.php";
  displaySections(); ?>

      </div><!--sectionContainer-->
    </div><!--sectionsModule-->