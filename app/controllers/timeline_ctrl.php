<?php

if (isset($paths[1])) url(HOME_DIRECTORY.$paths[0]);

if ($paths[0] == 'timeline') {
  $who = 'isFollowing';
} else if ($paths[0] == 'drafts') {
  $who = 'yourdrafts';
} else if ($paths[0] == 'liked') {
  $who = 'liked';
} else if ($paths[0] == 'published') {
  $who = 'yourposts';
}

include_once "app/views/_nav_list.html.php";
include_once "app/views/_sections.html.php";
include_once "app/views/_post_form.html.php";

$styles = [$mainStyles, $timelineStyles];
$scripts = [$mainScript, $timelineScript];
$posts = display_posts($who);
$navlist = display_navlist('home');
$sections = displaySections();
$postForm = postForm();