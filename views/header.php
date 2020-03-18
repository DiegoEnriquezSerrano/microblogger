<?php

global $homeDirectory;
global $userDirectory;
global $postDirectory;
global $editDirectory;
$linkStart = '
<link rel="stylesheet" href="'; 

$userStyles = $linkStart.$userDirectory.'css/main.css">';
$homeStyles = $linkStart.$homeDirectory.'css/main.css">';
$yesAuthStyles = $linkStart.$homeDirectory.'css/home.css">';
$postStyles = $linkStart.$postDirectory.'css/main.css">';
$editStyles = $linkStart.$editDirectory.'css/style.css">';
$noAuthStyles = $linkStart.$homeDirectory.'css/no_session.css">';

function returnListOfStyleSheets($stylesArray) {
  $stylesheets = '';
  foreach($stylesArray as $values) {
    $stylesheets .= $values;
  }
  return $stylesheets;
}

function display_header_and_styles($stylesArray) {
  global $homeDirectory;
  $styles = returnListOfStyleSheets($stylesArray);
  $echoHeader = <<<DELIMETER
<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <!-- Required meta tags -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">{$styles}
  <title>oh yeah?!</title>
</head>
<body>
  <nav class="navbar">
    <a class="navbar-brand" href="{$homeDirectory}">oh yeah?</a>
    <button class="navbar-toggler" type="button" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-icon"></span>
    </button>
    <div id="navbarSupportedContent">
      <form id="searchForm">
        <input type="hidden" name="page" value="search">
        <input type="text" name="q" id="search" placeholder="Search posts">
        <button type="submit" id="searchButton"></button>
      </form>
    </div><!--'#navbarSupportedContent'-->
    <div id="loginModalButtonHolder">

    </div><!--#loginModalButtonHolder-->
  </nav><!--'.navbar'-->
  <div id="homeboy" class="container">

DELIMETER;
  echo $echoHeader;
};

?>