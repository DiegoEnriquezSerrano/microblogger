<?php 

$page = <<<DELIMETER
  <div id="headerModule">
    {$userInfo}
    {$navlist}
  </div><!--headerModule-->
  <div id="mainModule">
    <div id="postContainer">
      {$posts}
    </div><!--'#postContainer'-->
  </div><!--'#mainModule'-->
  <div id="sectionsModule">
    <div id="sectionsContainer">
      {$sections}
    </div><!--sectionContainer-->
  </div><!--sectionsModule-->

DELIMETER;
echo $page;
