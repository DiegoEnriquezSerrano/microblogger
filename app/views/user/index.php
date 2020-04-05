<?php 

$page = <<<DELIMETER
  <div id="headerModule">
    {$userInfo}
    {$navlist}
  </div><!--headerModule-->
  <div id="postsModule">
    <div id="postContainer">
      {$posts}
    </div><!--'#postContainer'-->
  </div><!--'#postsModule'-->
  <div id="sectionsModule">
    <div id="sectionsContainer">
      {$sections}
    </div><!--sectionContainer-->
  </div><!--sectionsModule-->

DELIMETER;
echo $page;
