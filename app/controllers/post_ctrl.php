<?php

function sanitizedPostId($urlString) { 
  if (strpos($urlString, '?') == false) {
    return $urlString;
  } else {
    $onlyPostId = explode("?", $urlString);
    return $onlyPostId[0];
  }
}

global $postDirectory;

if($_SERVER['REQUEST_URI'] == $postDirectory || (strpos($_SERVER['REQUEST_URI'], "index.php" ) !== false)) {
  url(HOME_DIRECTORY);
}

global $homeStyles;
global $yesAuthStyles;
global $postStyles;

$styles = [$homeStyles, $yesAuthStyles, $postStyles];

$postIdFromURL = explode("/post/" ,$_SERVER['REQUEST_URI']);
$postResult = bind_and_get_result(
  "SELECT posts.id AS post_id, posts.userid AS post_user_id, users.username AS post_user_name,
  profiles.user_display_name AS post_user_displayname, profileimg.status AS post_user_img_status,
  profileimg.id AS post_user_img_id, profileimg.file_ext AS post_user_img_ext,
  profiles.profile_header_img AS post_user_header_img, profiles.user_bio AS post_user_bio, 
  posts.post AS post_text, posts.datetime AS post_created_at, posts.is_repost AS is_repost, 
  posts.repost_from_post_id AS original_post_id
  FROM posts
  INNER JOIN profileimg ON profileimg.userid = posts.userid
  LEFT JOIN users ON users.id = posts.userid
  LEFT JOIN profiles ON profiles.user_id = posts.userid
  WHERE posts.id = ?","i", esc(sanitizedPostId($postIdFromURL[1])));

if (mysqli_num_rows($postResult) < 1) {
  echo 'That post does not exist';
}

$postRow = fetch_assoc($postResult);
if ($postRow['is_repost'] == 1 && $postRow['post_text'] == '') url(HOME_DIRECTORY.'post/?'.$postRow['original_post_id']);
$postId = 'postid=';
$actualPostid = $postRow['post_id'];
$homeDirectory = HOME_DIRECTORY;


$scripts = $mainScript;