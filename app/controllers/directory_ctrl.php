<?php 

global $homeStyles;
global $directoryStyles;

$styles = [$homeStyles, $directoryStyles];

if(isset($paths)) {
  if(isset($paths[1])) {
    if($paths[1] == 'following') {
      $who = 'following';
    } else if($paths[1] == 'followers') {
      $who = 'followers';
    } else if($paths[1] == 'mutuals'){
      $who = 'mutuals';
    } else {
      url($homeDirectory);
    }
  } else {
    $who = 'all';
  }
} else {
  url($homeDirectory);
}

$scripts = [$mainScript,$directoryScript];

include_once "app/views/_nav_list.php";
include_once "app/views/_nav_panel.php";