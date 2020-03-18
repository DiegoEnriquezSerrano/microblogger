<?php 

  include("functions.php");

  if (strpos($_SERVER['REQUEST_URI'], "index.php" ) !== false) {
    url(HOME_DIRECTORY);
  }

  global $homeDirectory;

  $whatTheUserRequested=$_SERVER['REQUEST_URI'];
  $parameterArray=explode('/', $whatTheUserRequested);

  if ($parameterArray[2] == 'timeline' || (isset($_GET['page']) && $_GET['page'] == 'timeline')) {
    checkForSession();
    include("views/header.php");
    display_header_and_styles($new=array($homeStyles, $yesAuthStyles));
    include("views/timeline.php");
    include("views/footer.php");
    exit();

  } else if ($parameterArray[2] == 'directory' || (isset($_GET['page']) && $_GET['page'] == 'publicprofiles')) {
    include("views/header.php");
    display_header_and_styles($new=array($homeStyles));
    include("views/publicprofiles.php");
    include("views/footer.php");
    exit();

  } else if ($parameterArray[2] == '') {
    if (!isset($_SESSION['id'])) {
      include("views/header.php");
      display_header_and_styles($new=array($homeStyles, $noAuthStyles));
      include("views/no-session-page.php");
      include("views/footer.php");
      exit();
    } else {
      include("views/header.php");
      display_header_and_styles($new=array($homeStyles, $yesAuthStyles));
      include("views/home.php");
      include("views/footer.php");
      exit();
    }

  } else {
    $initialUserQuery = bind_and_get_result('SELECT username FROM users WHERE username = ?', 's', $parameterArray[2]);
    if (mysqli_num_rows($initialUserQuery) < 1) {
      print('That user does not exist');
      exit();
    } else {
      include("user/index.php");
      exit();
    }
  } 


  if (isset($_GET['page']) && $_GET['page'] == 'yourposts') {
    checkForSession();
    include("views/yourtweets.php");

  } else if (isset($_GET['page']) && $_GET['page'] == 'search') {
    include("views/search.php");

  }

  include("views/footer.php");

?>