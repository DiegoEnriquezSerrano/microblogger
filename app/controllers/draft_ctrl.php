<?php

global $draftDirectory;

function sanitizedDraftId($urlString) { 
  if (strpos($urlString, '?') == false) {
    return $urlString;
  } else {
    $onlyDraftId = explode("?", $urlString);
    return esc($onlyDraftId[0]);
  };
};

if($_SERVER['REQUEST_URI'] == $draftDirectory || (strpos($_SERVER['REQUEST_URI'], "index.php" ) !== false))
  url(HOME_DIRECTORY);

$draftIdFromURL = $paths[1];
$draftResult = bind_and_get_result(
  "SELECT
    drafts.id AS post_id,
    drafts.userid AS post_user_id,
    users.username AS post_user_name,
    profiles.user_display_name AS post_user_displayname,
    profiles.profile_header_img AS post_user_header_img,
    profiles.user_bio AS post_user_bio,
    drafts.post AS post_text,
    drafts.datetime AS post_created_at,
    drafts.is_repost AS is_repost,
    drafts.repost_from_post_id AS original_post_id
  FROM drafts
  LEFT JOIN users ON users.id = drafts.userid
  LEFT JOIN profiles ON profiles.user_id = drafts.userid
  WHERE drafts.id = ?","i", sanitizedDraftId($paths[1]));

if (mysqli_num_rows($draftResult) < 1) {
  url(HOME_DIRECTORY.'drafts');
}

$draftRow = fetch_assoc($draftResult);

if ($draftRow['original_post_id'] !== null) {
  $originalPostResult = bind_and_get_result(
    "SELECT
      posts.id AS original_post_id,
      posts.post AS original_post,
      posts.userid AS original_post_user_id,
      posts.datetime AS original_post_created_at,
      users.username AS original_post_user_name,
      COALESCE(profiles.user_display_name, users.username) AS original_post_user,
      COALESCE(profiles.profile_img, 'default') AS original_post_user_img
    FROM posts
    LEFT JOIN users ON posts.userid = users.id
    LEFT JOIN profiles ON posts.userid = profiles.user_id
    WHERE posts.id = ?", "s", $draftRow['original_post_id']
  );

  if(mysqli_num_rows($originalPostResult) < 1) return;
  $originalPostRow = fetch_assoc($originalPostResult);
};

if($draftRow['post_user_id'] != $_SESSION['id']) url(HOME_DIRECTORY.'drafts');
if($draftRow['is_repost'] == 1 && $draftRow['post_text'] == '') url(HOME_DIRECTORY.'draft/?'.$draftRow['original_post_id']);

require_once "app/views/_sections.html.php";
require_once "app/views/_nav_list.html.php";
include_once "app/views/_post_form.html.php";

$draftId = 'draftid=';
$actualDraftid = $draftRow['post_id'];
$homeDirectory = HOME_DIRECTORY;
$styles = [$mainStyles, $draftStyles];
$scripts = [$mainScript, $draftScript];
$sections = displaySections();
$postForm = postForm();
$navlist = display_navlist('home');