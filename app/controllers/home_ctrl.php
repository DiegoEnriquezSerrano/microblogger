<?php

global $mainStyles;
global $timelineStyles;
global $noAuthStyles;

if (!isset($_SESSION['id'])) {
  $styles = [$mainStyles, $noAuthStyles];
} else {
  $styles = [$mainStyles, $timelineStyles];
}

display_header_and_styles($styles);

$scripts = [$mainScript, $noSessionScript];