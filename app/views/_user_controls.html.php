<?php

  $id = esc($_SESSION['id']);
  $userResult = bind_and_get_result(
    "SELECT users.id AS user_id, users.username AS user_name, profiles.user_display_name, profileimg.status AS user_img_status, 
            profileimg.file_ext AS user_img_ext, profiles.user_bio, profiles.profile_header_img AS user_header_img
     FROM users 
     INNER JOIN profileimg ON profileimg.userid = users.id 
     LEFT JOIN profiles ON profiles.user_id = users.id
     WHERE users.id = ?","s", esc($id));
  
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
        <div id="user_controls_header"></div><!--user_controls_header-->
        <div id="user_controls_top">
          <a href="{$userPath}"><img id="user_image" src="{$userImage}"></a>
          <span id="user_name"><a href="{$userPath}">{$userDisplayName}</a><br> 
          @{$userName}<br>
          <a href="">Edit Profile</a> | <a href="">Account Settings</a></span>
        </div><!--user_controls_top-->
        <div id="user_bio">
          <span id="user_bio_label">Bio <span id="arrow">&#9660;</span></span>
          <p id="user_bio_text">
          {$userBio}
          </p><!--user_bio_text-->
        </div><!--user_bio-->
      </div><!--userModule-->
DELIMETER;

  // echo $userModule;
