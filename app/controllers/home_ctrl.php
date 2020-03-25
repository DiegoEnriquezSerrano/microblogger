<?php

global $homeStyles;
global $yesAuthStyles;
global $noAuthStyles;

if (!isset($_SESSION['id'])) {
  $styles = [$homeStyles, $noAuthStyles];
} else {
  $styles = [$homeStyles, $yesAuthStyles];
}

display_header_and_styles($styles);

$scripts = $mainScript.$noSessionScript;