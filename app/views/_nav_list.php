<?php

function display_navlist($type) {
  global $homeDirectory;
  if($type == 'profile') {
  $nav_list= <<<DELIMETER
      <ul id="navList">
        <li class="nav-item"><a class="nav-link" href="?page=timeline">All</a></li>
        <li class="nav-item"><a class="nav-link active" href="?page=yourposts">Text</a></li>
        <li class="nav-item"><a class="nav-link" href="?page=timeline">Image</a></li>
        <li class="nav-item"><a class="nav-link" href="?page=yourposts">Video</a></li>
        <li class="nav-item"><a class="nav-link" href="?page=yourposts">Audio</a></li>
        <li class="nav-item"><a class="nav-link" href="?page=publicprofiles">Article</a></li>
      </ul><!--"#navList"-->
DELIMETER;
  } else {
    $nav_list = <<<DELIMETER
      <ul id="navList">
        <li class="nav-item"><a class="nav-link active" href="{$homeDirectory}timeline">Feed</a></li>
        <li class="nav-item"><a class="nav-link" href="{$homeDirectory}published">Published</a></li>
        <li class="nav-item"><a class="nav-link" href="?page=yourposts">Drafts</a></li>
        <li class="nav-item"><a class="nav-link" href="?page=timeline">Liked</a></li>
      </ul><!--navList-->
DELIMETER;
  }
  echo $nav_list;
}