<?php

global $stylesDirectory;

$linkStart = '<link rel="stylesheet" href="'.$stylesDirectory;

$userStyles = $linkStart.'user_styles.css">';
$homeStyles = $linkStart.'main_styles.css">';
$yesAuthStyles = $linkStart.'home_styles.css">';
$postStyles = $linkStart.'post_styles.css">';
$editStyles = $linkStart.'edit_styles.css">';
$directoryStyles = $linkStart.'directory_styles.css">';
$noAuthStyles = $linkStart.'no_session_styles.css">';
$messagesStyles = $linkStart.'messages_styles.css">';


function returnListOfStyleSheets($stylesArray) {
  $stylesheets = '';
  foreach($stylesArray as $values) {
    $stylesheets .= $values;
  }
  return $stylesheets;
}

