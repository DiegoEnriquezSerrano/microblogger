<?php

require_once "app/controllers/nav_panel_ctrl.php";

function displaySections() {
  global $homeDirectory;
  $sections = <<<DELIMETER
<a id="nav_panel_expander" class="sectionLink">
  <span class="sectionIcon" data-icon="menu"></span>
</a>
<a class="sectionLink" href="{$homeDirectory}">
  <span class="sectionIcon" data-icon="home"></span><span class="sectionLinkText">Home</span>
</a>
<a class="sectionLink" href="{$homeDirectory}directory">
  <span class="sectionIcon" data-icon="directory"></span><span class="sectionLinkText">Directory</span>
</a>
<a class="sectionLink" href="{$homeDirectory}notifications">
  <span class="sectionIcon" data-icon="notifications"></span><span class="sectionLinkText">Notifications</span>
</a>
<a class="sectionLink" href="{$homeDirectory}inbox">
  <span class="sectionIcon" data-icon="message"></span><span class="sectionLinkText">Messages</span>
</a>
<a class="sectionLink" href="">
  <span class="sectionIcon" data-icon="settings"></span><span class="sectionLinkText">Account</span>
</a>
<a class="sectionLink" href="{$homeDirectory}logout">
  <span class="sectionIcon" data-icon="logout"></span><span class="sectionLinkText">Logout</span>
</a>
<hr>
<a class="sectionLink" href="">
  <span class="sectionIcon" data-icon="music"></span><span class="sectionLinkText">Musicians</span>
</a>
<a class="sectionLink" href="">
  <span class="sectionIcon" data-icon="arts"></span><span class="sectionLinkText">Visual Artists</span>
</a>
<a class="sectionLink" href="">
  <span class="sectionIcon" data-icon="writing"></span><span class="sectionLinkText">Writers</span>
</a>
<div class="flex-spacer"></div>
DELIMETER;
  return $sections;
}