<?php 

global $homeStyles;
global $directoryStyles;

$styles = [$homeStyles, $directoryStyles];

<<<<<<< HEAD
if(isset($parameterArray)) {
  if(isset($parameterArray[1])) {
    if($parameterArray[1] == 'following') {
      $who = 'following';
    } else if($parameterArray[1] == 'followers') {
      $who = 'followers';
    } else if($parameterArray[1] == 'mutuals'){
=======
if(isset($paths)) {
  if(isset($paths[1])) {
    if($paths[1] == 'following') {
      $who = 'following';
    } else if($paths[1] == 'followers') {
      $who = 'followers';
    } else if($paths[1] == 'mutuals'){
>>>>>>> 22c0563214fddd1b0cc339779620ce097b8999d2
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