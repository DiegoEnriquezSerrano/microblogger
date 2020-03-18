<?php

if(strpos($_SERVER['REQUEST_URI'], "index.php" ) !== false || !isset($_SESSION['id'])) {
  url("../");
}

$userResult = bind_and_get_result(
  "SELECT users.id AS user_id, users.username AS user_name, profiles.user_display_name, profileimg.status AS user_img_status, 
          profileimg.file_ext AS user_img_ext, profiles.user_bio, profiles.profile_header_img AS user_header_img
   FROM users 
   INNER JOIN profileimg ON profileimg.userid = users.id 
   LEFT JOIN profiles ON profiles.user_id = users.id
   WHERE users.id = ?", "s", esc($_SESSION['id']));

if (mysqli_num_rows($userResult) < 1) {
  echo 'That user does not exist';
}

$user = fetch_assoc($userResult);
$userBio = getUserBio($user, 'parent');
$userName = getUserName($user, 'parent');
$userPath = getUserLink($user, 'parent');
$userImage = getUserImage($user, 'parent');
$userDisplayName = getUserDisplayName($user, 'parent');

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

?>
