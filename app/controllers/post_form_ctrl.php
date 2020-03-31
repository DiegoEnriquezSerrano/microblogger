<?php

function returnDraftText() {
<<<<<<< HEAD
  global $parameterArray;
  global $draftRow;
  if(!isset($draftRow)) return;
  if($parameterArray[0] == 'draft') return $draftRow['post_text'];
=======
  global $paths;
  global $draftRow;
  if(!isset($draftRow)) return;
  if($paths[0] == 'draft') return $draftRow['post_text'];
>>>>>>> 22c0563214fddd1b0cc339779620ce097b8999d2
  else return;
}
$draftText = returnDraftText();