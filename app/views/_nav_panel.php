<?php

require_once "app/controllers/nav_panel_ctrl.php";

function displaySections() {
  global $homeDirectory;
  $sections = <<<DELIMETER
<a class="sectionLink" href="{$homeDirectory}">
  <span>Home</span>
</a>
<a class="sectionLink" href="{$homeDirectory}directory">
  <span>Directory</span>
</a>
<a class="sectionLink" href="{$homeDirectory}notifications">
  <span>Notifications</span>
</a>
<a class="sectionLink" href="{$homeDirectory}inbox">
  <span>Messages</span>
</a>
<a class="sectionLink" href="">
  <span>Account</span>
</a>
<a class="sectionLink" href="{$homeDirectory}?function=logout">
  <span>Logout</span>
</a>
<hr>
<a class="sectionLink" href="">
  <span>Musicians</span>
</a>
<a class="sectionLink" href="">
  <span>Visual Artists</span>
</a>
<a class="sectionLink" href="">
  <span>Writers</span>
</a>
<div class="flex-spacer"></div>
DELIMETER;
  echo $sections;
}