<?php

global $homeStyles;
global $yesAuthStyles;

$styles = [$homeStyles, $yesAuthStyles];
$scripts = [$mainScript, $timelineScript];

<<<<<<< HEAD
global $parameterArray;

if (isset($parameterArray[1])) url(HOME_DIRECTORY.$parameterArray[0]);

if ($parameterArray[0] == 'timeline') {
  $who = 'isFollowing';
} else if ($parameterArray[0] == 'drafts') {
  $who = 'yourdrafts';
} else if ($parameterArray[0] == 'liked') {
  $who = 'liked';
} else if ($parameterArray[0] == 'published') {
  $who = 'yourposts';
}
=======
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
>>>>>>> 22c0563214fddd1b0cc339779620ce097b8999d2
