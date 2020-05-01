<?php

global $homeStyles;
global $userStyles;
global $editStyles;

$styles = [$homeStyles, $userStyles, $editStyles];

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


$scripts = $mainScript;