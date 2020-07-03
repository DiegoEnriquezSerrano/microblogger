<?php

$styles = [$mainStyles, $userStyles];
$scripts = [$mainScript, $userScript];

$userResult = bind_and_get_result(
  "SELECT
    users.id AS user_id,
    users.username AS user_name,
    profiles.user_display_name,
    COALESCE(profiles.profile_img, 'default') AS profile_img,
    profiles.user_bio,
    profiles.profile_header_img AS user_header_img
   FROM users
   LEFT JOIN profiles ON profiles.user_id = users.id
   WHERE username = ?","s", esc($userPath));

if (mysqli_num_rows($userResult) < 1) {
  echo 'That user does not exist';
}

$user = fetch_assoc($userResult);
$userid = 'userid=';
$actualUserid = $user['user_id'];

require_once "app/views/_nav_list.html.php";
require_once "app/views/user/user_info.html.php";
require_once "app/views/_sections.html.php";

$userInfo = displayUserInfo($user);
$posts = display_posts($userid.$actualUserid);
$navlist = display_navlist('profile');
$sections = displaySections();
