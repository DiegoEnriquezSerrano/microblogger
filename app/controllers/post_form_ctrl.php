<?php

function returnDraftText() {
  global $parameterArray;
  global $draftRow;
  if(!isset($draftRow)) return;
  if($parameterArray[0] == 'draft') return $draftRow['post_text'];
  else return;
}
$draftText = returnDraftText();