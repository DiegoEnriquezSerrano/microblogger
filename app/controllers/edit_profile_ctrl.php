<?php

$styles = [$mainStyles, $editStyles];

if(strpos($_SERVER['REQUEST_URI'], "index.php" ) !== false || !isset($_SESSION['id'])) {
  url("../");
}

$userResult = bind_and_get_result(
  "SELECT users.id AS user_id,
          users.username AS user_name,
          users.email AS email,
          profiles.user_display_name,
          COALESCE(profiles.profile_img, 'default') AS user_img,
          profiles.user_bio,
          profiles.profile_header_img AS user_header_img
   FROM users
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
$userEmail = $user['email'];


include_once "app/views/_nav_list.html.php";
include_once "app/views/_sections.html.php";
$navlist = display_navlist('settings');
$sections = displaySections();

$scripts = [$mainScript, $editScript];