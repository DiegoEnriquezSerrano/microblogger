<?php 

require_once "app/views/_nav_list.php";
require_once "app/views/user/user_info.php";
$userInfo = displayUserInfo($user);
$posts = display_posts($userid.$actualUserid);

$pageBegin = <<<DELIMETER
  <div id="headerModule">
    {$userInfo}
  </div><!--headerModule-->
  <div id="homeModule">
    </div><!--'#homeModule'-->
    <div id="postsModule">
      <div id="postContainer">
      {$posts}
  
DELIMETER;
echo $pageBegin;

display_navlist('profile');

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
