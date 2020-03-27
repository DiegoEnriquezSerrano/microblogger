<?php

function sanitizedDraftId($urlString) { 
  if (strpos($urlString, '?') == false) {
    return $urlString;
  } else {
    $onlyDraftId = explode("?", $urlString);
    return $onlyDraftId[0];
  }
}

global $draftDirectory;

if($_SERVER['REQUEST_URI'] == $draftDirectory || (strpos($_SERVER['REQUEST_URI'], "index.php" ) !== false)) {
  url(HOME_DIRECTORY);
}

global $homeStyles;
global $yesAuthStyles;
global $postStyles;

$styles = [$homeStyles, $yesAuthStyles, $postStyles];

$draftIdFromURL = explode("/draft/" ,$_SERVER['REQUEST_URI']);
$draftResult = bind_and_get_result(
  "SELECT drafts.id AS post_id, drafts.userid AS post_user_id, users.username AS post_user_name,
  profiles.user_display_name AS post_user_displayname, profileimg.status AS post_user_img_status,
  profileimg.id AS post_user_img_id, profileimg.file_ext AS post_user_img_ext,
  profiles.profile_header_img AS post_user_header_img, profiles.user_bio AS post_user_bio, 
  drafts.post AS post_text, drafts.datetime AS post_created_at, drafts.is_repost AS is_repost, 
  drafts.repost_from_post_id AS original_post_id
  FROM drafts
  INNER JOIN profileimg ON profileimg.userid = drafts.userid
  LEFT JOIN users ON users.id = drafts.userid
  LEFT JOIN profiles ON profiles.user_id = drafts.userid
  WHERE drafts.id = ?","i", esc(sanitizedDraftId($draftIdFromURL[1])));

if (mysqli_num_rows($draftResult) < 1) {
  echo 'That draft does not exist';
}

$draftRow = fetch_assoc($draftResult);
if ($draftRow['is_repost'] == 1 && $draftRow['post_text'] == '') url(HOME_DIRECTORY.'draft/?'.$draftRow['original_post_id']);
$draftId = 'draftid=';
$actualDraftid = $draftRow['post_id'];
$homeDirectory = HOME_DIRECTORY;


$scripts = [$mainScript];