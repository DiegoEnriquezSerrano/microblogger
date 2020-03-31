<?php

function returnDraftText() {
  global $paths;
  global $draftRow;
  if(!isset($draftRow)) return;
  if($paths[0] == 'draft') return $draftRow['post_text'];
  else return;
}
$draftText = returnDraftText();