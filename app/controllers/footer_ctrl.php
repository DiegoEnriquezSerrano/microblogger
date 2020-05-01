<?php

function errorStringCheck() {
  if(isset($errorString)) echo $errorString;
}

global $javascriptDirectory;

$scriptBegin = '<script src="'.$javascriptDirectory;
$scriptEnd = '"></script>';

$mainScript = $scriptBegin.'main.js'.$scriptEnd;
$sourceScript = $scriptBegin.'source.js'.$scriptEnd;
$directoryScript = $scriptBegin.'directory_scripts.js'.$scriptEnd;
$timelineScript = $scriptBegin.'timeline_scripts.js'.$scriptEnd;
$noSessionScript = $scriptBegin.'no_session.js'.$scriptEnd;
$userScript = $scriptBegin.'user_scripts.js'.$scriptEnd;
$messagesScript = $scriptBegin.'messages_scripts.js'.$scriptEnd;

$errorStringCheck = errorStringCheck();