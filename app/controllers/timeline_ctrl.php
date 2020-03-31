<?php

global $homeStyles;
global $yesAuthStyles;

$styles = [$homeStyles, $yesAuthStyles];
$scripts = [$mainScript, $timelineScript];

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