<?php

require_once "app/controllers/header_ctrl.php";

function display_header_and_styles($stylesArray) {
  global $homeDirectory;
  $styles = concatArrayValuesAsString($stylesArray);
  $echoHeader = <<<DELIMETER
<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">{$styles}
  <title>oh yeah?!</title>
</head>
<body>
  <div id="homeboy" class="container">

DELIMETER;
  echo $echoHeader;
};