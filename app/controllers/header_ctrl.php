<?php

global $stylesDirectory;

$userStyles = '<link rel="stylesheet" href="'.$stylesDirectory.'user_styles.css">';
$homeStyles = '<link rel="stylesheet" href="'.$stylesDirectory.'main_styles.css">';
$yesAuthStyles = '<link rel="stylesheet" href="'.$stylesDirectory.'home_styles.css">';
$postStyles = '<link rel="stylesheet" href="'.$stylesDirectory.'post_styles.css">';
$editStyles = '<link rel="stylesheet" href="'.$stylesDirectory.'edit_styles.css">';
$noAuthStyles = '<link rel="stylesheet" href="'.$stylesDirectory.'no_session_styles.css">';


function returnListOfStyleSheets($stylesArray) {
  $stylesheets = '';
  foreach($stylesArray as $values) {
    $stylesheets .= $values;
  }
  return $stylesheets;
}