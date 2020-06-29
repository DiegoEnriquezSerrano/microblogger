<?php

$styles = [$mainStyles];


include_once "app/views/_nav_list.html.php";
include_once "app/views/_sections.html.php";
$navlist = display_navlist('settings');
$sections = displaySections();

$scripts = [$mainScript];