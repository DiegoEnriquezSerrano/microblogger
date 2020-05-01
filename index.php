<?php 

  require_once "functions.php";

  if (strpos($_SERVER['REQUEST_URI'], "index.php" ) !== false) {
    url(HOME_DIRECTORY);
  }

  global $homeDirectory;
  require_once "config/router.php";
  include("app/views/_header.html.php");
  require_once "app/controllers/footer_ctrl.php";

  if ($paths[0] == 'post') {
    if ($paths[1] == '') {
      url(HOME_DIRECTORY);
    }
    include("app/controllers/post_ctrl.php");
    display_header_and_styles($styles);
    include("app/views/post/index.html.php");
    
  } else if ($paths[0] == 'draft') {
    if ($paths[1] == '') {
      url(HOME_DIRECTORY);
    }
    include("app/controllers/draft_ctrl.php");
    display_header_and_styles($styles);
    include("app/views/draft/index.html.php");
    
  } else if ($paths[0] == 'timeline') {
    checkForSession();
    include("app/controllers/timeline_ctrl.php");
    display_header_and_styles($styles);
    include("app/views/timeline/index.html.php");

  } else if ($paths[0] == 'published') {
    checkForSession();
    include("app/controllers/timeline_ctrl.php");
    display_header_and_styles($styles);
    include("app/views/timeline/published.html.php");

  } else if ($paths[0] == 'liked') {
    checkForSession();
    include("app/controllers/timeline_ctrl.php");
    display_header_and_styles($styles);
    include("app/views/timeline/liked_posts.html.php");

  } else if ($paths[0] == 'drafts') {
    checkForSession();
    include("app/controllers/timeline_ctrl.php");
    display_header_and_styles($styles);
    include("app/views/timeline/drafts.html.php");

  } else if ($paths[0] == 'search') {
    include("app/controllers/search_ctrl.php");
    display_header_and_styles($styles);
    include("app/views/search/result.html.php");

  } else if ($paths[0] == 'directory') {
    include("app/controllers/directory_ctrl.php");
    display_header_and_styles($styles);
    include("app/views/directory/index.html.php");

  } else if ($paths[0] == 'edit') {
    checkForSession();
    require_once("app/controllers/edit_profile_ctrl.php");
    display_header_and_styles($styles);
    include("app/views/edit/index.html.php");

  } else if ($paths[0] == 'inbox') {
    checkForSession();
    require_once("app/controllers/messages_ctrl.php");
    display_header_and_styles($styles);
    if (isset($paths[1]) && $paths[1] == 'create') {
      include("app/views/messages/create.html.php");
      include("app/views/messages/_select_recipients_modal.html.php");

    } else if (isset($paths[1]) && $paths[1] == 'thread') {
      if (!isset($paths[2]) || $paths[2] == '') {
        url(HOME_DIRECTORY);
      }
      include("app/views/messages/thread.html.php");
    }
     else if (!isset($paths[1])) {
      include("app/views/messages/index.html.php");
    } else {
      $url = HOME_DIRECTORY.'inbox';
      url($url);
    }

  } else if ($paths[0] == 'logout') {
    session_unset();
    url(HOME_DIRECTORY);

  } else if ($paths[0] == '') {
    include("app/controllers/home_ctrl.php");
    if (!isset($_SESSION['id'])) {
      include("app/views/no_session.html.php");
    } else {
      $url = $homeDirectory.'timeline';
      url($url);
    }

  } else {
    $initialUserQuery = bind_and_get_result('SELECT username FROM users WHERE username = ?', 's', $paths[0]);
    if (mysqli_num_rows($initialUserQuery) < 1) {
      print('That user does not exist');
    } else {
      $userPath = $paths[0];
      include("app/controllers/user_ctrl.html.php");
      display_header_and_styles($styles);
      include("app/views/user/index.html.php");
    }
  }

  include("app/views/_footer.html.php");
  exit();

?>