<?php

function errorStringCheck() {
  if(isset($errorString)) echo $errorString;
}

global $javascriptDirectory;

$scriptBegin = '<script src="'.$javascriptDirectory;
$scriptEnd = '"></script>';

$mainScript = $scriptBegin.'main.js'.$scriptEnd;
<<<<<<< HEAD
=======
$sourceScript = $scriptBegin.'source.js'.$scriptEnd;
>>>>>>> 22c0563214fddd1b0cc339779620ce097b8999d2
$directoryScript = $scriptBegin.'directory_scripts.js'.$scriptEnd;
$timelineScript = $scriptBegin.'timeline_scripts.js'.$scriptEnd;
$noSessionScript = $scriptBegin.'no_session.js'.$scriptEnd;

$errorStringCheck = errorStringCheck();