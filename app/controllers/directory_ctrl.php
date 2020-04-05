<?php 

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

include_once "app/views/_nav_list.php";
include_once "app/views/_nav_panel.php";

$styles = [$homeStyles, $directoryStyles];
$scripts = [$mainScript, $directoryScript];
$sections = displaySections();
$navlist = display_navlist('directory');
$users = displayUsers($who);