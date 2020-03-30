<?php

global $userStyles;
global $homeStyles;

$styles = [$homeStyles, $userStyles];

if(strpos($_SERVER['REQUEST_URI'], "index.php" ) !== false) {
  url("../");
}
//^^to avoid a page load without a username parameter or someone trying to access the 'ugly' index page link

$userResult = bind_and_get_result(
  "SELECT users.id AS user_id, users.username AS user_name, profiles.user_display_name, profileimg.status AS user_img_status, 
          profileimg.file_ext AS user_img_ext, profiles.user_bio, profiles.profile_header_img AS user_header_img
   FROM users 
   INNER JOIN profileimg ON profileimg.userid = users.id 
   LEFT JOIN profiles ON profiles.user_id = users.id
   WHERE username = ?","s", esc($usernameToUse));

if (mysqli_num_rows($userResult) < 1) {
  echo 'That user does not exist';
}

$user = fetch_assoc($userResult);
$userid = 'userid=';
$actualUserid = $user['user_id'];

//^^^^like the post page, the user page has to get enough vital information about the object

$scripts = [$mainScript];