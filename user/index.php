<?php 

  global $homeDirectory;
  $usernameFromGetUrl = explode("?" ,$_SERVER['REQUEST_URI']);
  $usernameFromDirectUrl = explode("/" ,$_SERVER['REQUEST_URI']);

  if (strpos($_SERVER['REQUEST_URI'], "microblogger/user/" ) !== false) {
    $usernameToUse = $usernameFromGetUrl[1];
    require_once('../functions.php');
    require_once('functions.php');
    include("../views/header.php");
    display_header_and_styles($a=array($homeStyles, $userStyles));
    echo $pageBegin;
    include("views/userinfo.php");
    echo $pageMiddle;
    display_navlist('profile');
    display_posts($userid.$actualUserid);
    echo $otherMiddle;
    displaySections();
    echo $closingTags;
    include("../views/footer.php");
    
  } else if (strpos($_SERVER['REQUEST_URI'], "/microblogger/" ) !== false) {
    $usernameToUse = $usernameFromDirectUrl[2];
    require_once('functions.php');
    require_once('user/functions.php');
    include("views/header.php");
    display_header_and_styles($a=array($homeStyles, $userStyles));
    echo $pageBegin;    
    include("user/views/userinfo.php");
    echo $pageMiddle;
    display_navlist('profile');
    display_posts($userid.$actualUserid);
    echo $otherMiddle;
    displaySections();
    echo $closingTags;
    include("views/footer.php");

  }
//^^^^the page will either be called from the index page or the directory where the
//other user related files exist, so we have to handle for both of those possibilities

?>