
    <div id="headerModule">
      <div id="img_container">
        <img src="">
      </div><!--img_container-->
      <div id="header_nav">
      </div><!--header_nav-->
    </div><!--headerModule-->
    <div id="homeModule">

<?php 
// print_r($user); 
$userModule = <<<DELIMETER
        <div id="userModule">
          <div id="user_controls_header"><img src="../rsc/clear.png"></div><!--user_controls_header-->
          <div id="user_controls_container">
            <div id="user_controls_top">
              <div id="user_image_container">
                <a id="user_image_link" href="{$userPath}"><img id="user_image" src="{$userImage}"></img></a>
              </div><!--user_image_container-->
              <span id="user_name"><a href="{$userPath}">{$userDisplayName}</a><br> 
              @{$userName}<br>
              <a href="">Edit Profile</a> | <a href="">Account Settings</a></span>
            </div><!--user_controls_top-->
            <div id="user_bio" class="active">
              <span id="user_bio_label">Bio <span id="arrow">&#9660;</span></span>
              <p id="user_bio_text">
              {$userBio}
              </p><!--user_bio_text-->
            </div><!--user_bio-->
          </div><!--user_controls_container-->
        </div><!--userModule-->
DELIMETER;
echo $userModule;?>

      <div class="image_upload_box">
        <form action="actions.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="file" value="" class="active">
        <button type="submit" name="submit">UPLOAD</button>
        </form>
      </div><!--image_upload_box-->

    </div><!--'#homeModule'-->
    <div id="editModule">

<?php 
$editModule = <<<DELIMETER
<div id="edit_container">
  <form id="edit_form">
    <span class="label one">Username</span>
    <span class="label two">Display name</span>
    <span class="label three">Bio</span>
    <input class="input one" name="username" value="{$userName}">
    <input class="input two" name="displayname" value="{$user['user_display_name']}">
    <textarea class="input three">{$userBio}</textarea>
  </form>
</div><!--edit_container-->
DELIMETER;
echo $editModule; ?>  

    </div><!--'#editModule'-->
    <div id="sectionsModule">
      <div id="sectionsContainer">
<?php
  include_once "app/views/_nav_panel.php";
  displaySections(); ?>

      </div><!--sectionContainer-->
    </div><!--sectionsModule-->