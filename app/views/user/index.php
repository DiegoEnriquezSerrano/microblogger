<?php 

require_once "app/views/_nav_list.php";

$pageBegin = <<<DELIMETER
  <div id="headerModule">
    <div id="img_container">
      <img src="">
    </div><!--img_container-->
    <div id="header_nav">
    </div><!--header_nav-->
  </div><!--headerModule-->
  <div id="homeModule">

DELIMETER;
echo $pageBegin;

include("app/views/user/user_info.php");

$pageMiddle = <<<DELIMETER
    </div><!--'#homeModule'-->
    <div id="postsModule">
      <div id="postContainer">
  
DELIMETER;
echo $pageMiddle;

display_navlist('profile');
display_posts($userid.$actualUserid);

$otherMiddle = <<<DELIMETER
    </div><!--'#postContainer'-->
  </div><!--'#postsModule'-->
  <div id="sectionsModule">
    <div id="sectionsContainer">

DELIMETER;
echo $otherMiddle;

include_once "app/views/_nav_panel.php";
displaySections();

$closingTags = <<<DELIMETER
    </div><!--sectionContainer-->
  </div><!--sectionsModule-->

DELIMETER;
echo $closingTags;
