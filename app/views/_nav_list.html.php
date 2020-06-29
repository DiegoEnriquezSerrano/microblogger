<?php

function display_navlist($type) {
  global $homeDirectory;
  if($type == 'profile') {
  $nav_list= <<<DELIMETER
      <ul id="navList">
        <li class="nav-item"><a class="nav-link" href="?page=timeline">All</a></li>
        <li class="nav-item"><a class="nav-link" href="?page=yourposts">Text</a></li>
        <li class="nav-item"><a class="nav-link" href="?page=timeline">Image</a></li>
        <li class="nav-item"><a class="nav-link" href="?page=yourposts">Video</a></li>
        <li class="nav-item"><a class="nav-link" href="?page=yourposts">Audio</a></li>
        <li class="nav-item"><a class="nav-link" href="?page=publicprofiles">Article</a></li>
      </ul><!--"#navList"-->
DELIMETER;
  } else if ($type == 'directory') {
    $nav_list= <<<DELIMETER
      <ul id="navList">
        <li class="nav-item"><a class="nav-link" data-directory="all" href="{$homeDirectory}directory">All</a></li>
        <li class="nav-item"><a class="nav-link" data-directory="following" href="{$homeDirectory}directory/following">Following</a></li>
        <li class="nav-item"><a class="nav-link" data-directory="followers" href="{$homeDirectory}directory/followers">Followers</a></li>
        <li class="nav-item"><a class="nav-link" data-directory="mutuals" href="{$homeDirectory}directory/mutuals">Mutuals</a></li>
      </ul><!--"#navList"-->
  DELIMETER;
    } else if ($type == 'settings') {
      $nav_list= <<<DELIMETER
        <ul id="navList">
          <li class="nav-item"><a class="nav-link" data-directory="all" href="{$homeDirectory}edit">Profile</a></li>
          <li class="nav-item"><a class="nav-link" data-directory="following" href="{$homeDirectory}edit/account">Account</a></li>
        </ul><!--"#navList"-->
    DELIMETER;
      } else {
    $nav_list = <<<DELIMETER
      <ul id="navList">
        <li class="nav-item"><a class="nav-link" href="{$homeDirectory}timeline">Feed</a></li>
        <li class="nav-item"><a class="nav-link" href="{$homeDirectory}published">Published</a></li>
        <li class="nav-item"><a class="nav-link" href="{$homeDirectory}drafts">Drafts</a></li>
        <li class="nav-item"><a class="nav-link" href="{$homeDirectory}liked">Liked</a></li>
      </ul><!--navList-->
DELIMETER;
  }
  return $nav_list;
}
