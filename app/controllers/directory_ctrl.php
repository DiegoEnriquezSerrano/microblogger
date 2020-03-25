<?php 

global $homeStyles;
global $directoryStyles;

$styles = [$homeStyles, $directoryStyles];

if(isset($parameterArray)) {
  if(isset($parameterArray[1])) {
    if($parameterArray[1] == 'following') {
      $who = 'following';
    } else if($parameterArray[1] == 'followers') {
      $who = 'followers';
    } else if($parameterArray[1] == 'mutuals'){
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

$scripts = $mainScript.$directoryScript;

include_once "app/views/_nav_list.php";
include_once "app/views/_nav_panel.php";