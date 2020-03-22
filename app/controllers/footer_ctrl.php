<?php

function errorStringCheck() {
  if(isset($errorString)) echo $errorString;
}

function javascriptGenerator() {
  $scripts = '<script src="'.JAVASCRIPT_DIRECTORY.'main.js"></script>';
  if (!isset($_SESSION['id'])) { 
  $scripts =  $scripts.'<script src="'.JAVASCRIPT_DIRECTORY.'no_session.js"></script>'; 
  }
  return $scripts;
}

$errorStringCheck = errorStringCheck();
$scripts = javascriptGenerator();