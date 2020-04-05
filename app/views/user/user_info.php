<?php

  function displayUserInfo($user) {

    $userBio = getUserBio($user, 'parent');
    $userName = getUserName($user, 'parent');
    $userPath = getUserLink($user, 'parent');
    $userImage = getUserImage($user, 'parent');
    $userDisplayName = getUserDisplayName($user, 'parent');
    
    $userModule = <<<DELIMETER
        <div id="userModule">
          <div id="user_controls_header"><img src="rsc/clear.png"></div><!--user_controls_header-->
          <div id="user_controls_container">
            <div id="user_controls_top">
              <div id="user_image_container">
                <a id="user_image_link" href="{$userPath}"><img id="user_image" src="{$userImage}"></img></a>
              </div><!--user_image_container-->
              <span id="user_name"><a href="{$userPath}">{$userDisplayName}</a><br> 
              @{$userName}<br>
              <a href="">Edit Profile</a></span>
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
    return $userModule;
  }
