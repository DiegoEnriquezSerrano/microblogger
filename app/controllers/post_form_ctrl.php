<?php

function returnDraftText() {
  global $paths;
  global $draftRow;
  if(!isset($draftRow)) return;
  if($paths[0] == 'draft') return $draftRow['post_text'];
  else return;
};

function returnOriginalPost($post) {
  global $paths;
  global $homeDirectory;

  if(!isset($post)) return false;

  $postInfo = [
    'postId' => $post['original_post_id'],
    'postUser' => $post['original_post_user'],
    'postText' => $post['original_post'],
    'postAvatar' => $post['original_post_user_img'] == 'default' ?
      "{$homeDirectory}rsc/profiledefault" : "{$uploadDirectory}{$post['original_post_user_img']}",
    'postUserName' => $post['original_post_user_name']
  ];
  $postInfo = (object) $postInfo;
  return $postInfo;
};

isset($originalPostRow) ? $originalPost = returnOriginalPost($originalPostRow) : $originalPost = false;
$draftText = returnDraftText();