<?php

global $homeStyles;
global $yesAuthStyles;

$styles = [$homeStyles, $yesAuthStyles];
$scripts = [$mainScript, $timelineScript];

global $paths;

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

$posts = display_posts($who);
