<?php 

  require_once "functions.php";

  if (strpos($_SERVER['REQUEST_URI'], "index.php" ) !== false) {
    url(HOME_DIRECTORY);
  }

  global $homeDirectory;

  $whatTheUserRequested = $_SERVER['REQUEST_URI'];
  $firstSplit = explode('microblogger/', $whatTheUserRequested);
  $parameterArray = explode('/', $firstSplit[1]);

//  echo $whatTheUserRequested;
//  print_r($parameterArray);

include("app/views/_header.php");

  if ($parameterArray[0] == 'post') {
    if ($parameterArray[1] == '') {
      url(HOME_DIRECTORY);
    }
    include("app/controllers/post_ctrl.php");
    display_header_and_styles($styles);
    include("app/views/post/index.php");
    
  } else if ($parameterArray[0] == 'timeline') {
    checkForSession();
    include("app/controllers/timeline_ctrl.php");
    display_header_and_styles($styles);
    include("app/views/timeline/index.php");

  } else if ($parameterArray[0] == 'search') {
    include("app/controllers/search_ctrl.php");
    display_header_and_styles($styles);
    include("app/views/search/result.php");

  } else if ($parameterArray[0] == 'published') {
    checkForSession();
    include("app/controllers/timeline_ctrl.php");
    display_header_and_styles($styles);
    include("app/views/timeline/yourtweets.php");

  } else if ($parameterArray[0] == 'directory') {
    include("app/controllers/directory_ctrl.php");
    display_header_and_styles($styles);
    include("app/views/directory/index.php");

  } else if ($parameterArray[0] == 'edit') {
    checkForSession();
    require_once("app/controllers/edit_profile_ctrl.php");
    display_header_and_styles($styles);
    include("app/views/edit/index.php");

  } else if ($parameterArray[0] == '') {
    if (!isset($_SESSION['id'])) {
      include("app/controllers/home_ctrl.php");
      include("app/views/no_session.php");
    } else {
      include("app/controllers/home_ctrl.php");
      include("app/views/index.php");
    }
  } else {
    $initialUserQuery = bind_and_get_result('SELECT username FROM users WHERE username = ?', 's', $parameterArray[0]);
    if (mysqli_num_rows($initialUserQuery) < 1) {
      print('That user does not exist');
    } else {
      $usernameToUse = $parameterArray[0];
      include("app/controllers/user_ctrl.php");
      display_header_and_styles($styles);
      include("app/views/user/index.php");
    }
  }

  include("app/views/_footer.php");
  exit();

?>