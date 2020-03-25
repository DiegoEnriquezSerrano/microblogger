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

global $javascriptDirectory;

$scriptBegin = '<script src="'.$javascriptDirectory;
$scriptEnd = '"></script>';

$mainScript = $scriptBegin.'main.js'.$scriptEnd;
$directoryScript = $scriptBegin.'directory_scripts.js'.$scriptEnd;
$noSessionScript = $scriptBegin.'no_session.js'.$scriptEnd;

$errorStringCheck = errorStringCheck();