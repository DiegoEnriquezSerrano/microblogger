<?php

session_start();
include_once 'dbh.php';

if (isset($_GET['action']) && $_GET['action'] == "updateTimezone") {
  date_default_timezone_set($_POST['timezone']); 
  $timezone = date_default_timezone_get();
}

if (isset($_GET['function']) && $_GET['function'] == "logout") {
    session_unset();
}

function time_since($since) {
  $chunks = array(
    array(60 * 60 * 24 * 365 , 'year'),
    array(60 * 60 * 24 * 30 , 'month'),
    array(60 * 60 * 24 * 7, 'week'),
    array(60 * 60 * 24 , 'day'),
    array(60 * 60 , 'hour'),
    array(60 , 'min'),
    array(1 , 'sec')
  );
  for ($i = 0, $j = count($chunks); $i < $j; $i++) {
    $seconds = $chunks[$i][0];
    $name = $chunks[$i][1];
    if (($count = floor($since / $seconds)) != 0) {
      break;
    }
  }
  $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
  return $print;
}

function esc($string) {
  global $link;
  return mysqli_real_escape_string($link, $string);
}

function query($sql){
  global $link;
  return mysqli_query($link, $sql);
}

function fetch_array($result){
  return mysqli_fetch_array($result);
}

function fetch_assoc($result){
  return mysqli_fetch_assoc($result);
}

function stmt($prepared_sql){
  global $link;
  $stmt = $link->prepare($prepared_sql);
  return $stmt;
}

function bind_and_execute_stmt($prepared_sql, $placeholder, $values) {
  global $link;
  $stmt = $link->prepare($prepared_sql);
  if(gettype($values) == "array") {
    $stmt->bind_param($placeholder, ...$values);
  } else if (gettype($values) == "string" || "number") {
    $stmt->bind_param($placeholder, $values);
  }
  return $stmt->execute();
}

function bind_and_get_result($prepared_sql, $placeholder, $values) {
  global $link;
  $stmt = $link->prepare($prepared_sql);
  if(gettype($values) == "array") {
    $stmt->bind_param($placeholder, ...$values);
  } else if (gettype($values) == "string" || "number") {
    $stmt->bind_param($placeholder, $values);
  }
  $stmt->execute();
  return mysqli_stmt_get_result($stmt);
}

function display_posts($type) {
  global $link;
  if ($type == 'public') {
    $whereClause = "";
  } else if ($type == 'isFollowing') {
    if (!$_SESSION) {
      $whereClause = "WHERE userid = 0";
    } else {
      $result = bind_and_get_result("SELECT * FROM following_relations WHERE follower = ?", "s", esc($_SESSION['id']));
      $whereClause = "";
      if(mysqli_num_rows($result) < 1) {
        $whereClause = "WHERE userid = 0";
      } else {
        while ($row = mysqli_fetch_assoc($result)) {
          if ($whereClause == "") $whereClause = "WHERE";
          else $whereClause.= " OR";
          $whereClause.= " userid = ".$row['is_following'];
        }
      }
    }
  } else if ($type == 'yourposts') {
    if (!$_SESSION) {
      $whereClause = "WHERE userid = 0";
    } else {
      $whereClause = "WHERE userid = ". esc($_SESSION['id']);
    }
  } else if ($type == 'search') {
    if (!$_SESSION) {
      $whereClause = "WHERE userid = 0";
    } else {
      echo "<p>Showing results for '". esc($_GET['q'])."':</p>";
      $whereClause = "WHERE post LIKE '%". esc($_GET['q'])."%'";
    }
  } else if (strpos($type, 'username')) {
    $userQueryResult = bind_and_get_result("SELECT * FROM users WHERE username = ?", "s", esc($_GET['username']));
    $user = mysqli_fetch_assoc($userQueryResult);
    echo "<h2>". esc($user['username'])."'s posts:</h2>";
    $whereClause = "WHERE userid = ". esc($user['id']);
  }

  $query = "SELECT * FROM posts ".$whereClause." ORDER BY `datetime` DESC LIMIT 20";
  $results = query($query);

  if (mysqli_num_rows($results) == 0) {
    echo "There are no posts to display";
  } else {
    while ($row = fetch_assoc($results)) {
      $userQueryResult = bind_and_get_result("SELECT * FROM users WHERE id = ?", "s", esc($row['userid']));
      $user = fetch_assoc($userQueryResult);
      echo '<div class="tweet">
        <div class="post_header">
          <a class="user_avatar" href="?page=publicprofiles&username='.$user['username'].'">';
      $userProfileImageQuery = bind_and_get_result("SELECT * FROM profileimg WHERE userid = ?", "s", esc($row['userid']));
      $userProfileImage = fetch_assoc($userProfileImageQuery);
      if ($userProfileImage['status'] == 0) {
        echo "<img src='uploads/profile{$row['userid']}{$userProfileImage['file_ext']}'>";
      } else if ($userProfileImage['status'] == 1) {
        echo "<img src='rsc/profiledefault.jpg'>";
      }
      echo '</a>';
      echo '
          <p class="post_details_box"><a class="userlink" href="?page=publicprofiles&username='.$user['username'].'">'.$user['username'].'</a><br>';
      echo '
          <span class="time"> '.time_since(time() - strtotime($row['datetime']))." ago</span><br>";
      if ($_SESSION) {
        $isFollowingQueryResult = bind_and_get_result("SELECT * FROM following_relations WHERE follower = ? AND is_following = ?", "ss", $new=array($_SESSION['id'], $row['userid']));
        if (isset($_SESSION['id']) && $_SESSION['id'] == $user['id']) {
          echo '';
        } else {
          echo '
          <a class="toggleFollow" data-userId="'.$row['userid'].'">';
          if (mysqli_num_rows($isFollowingQueryResult) > 0) {
            echo "Unfollow";
          } else {
            echo "Follow";
          }
        }
      } else {
        echo '<a class="loginModalButtons">Sign up or Login to follow';
      }
      echo '</a></p><!--post_details_box-->';
      echo '
      </div><!--post_header-->';
      echo '
        <p class="tweetContent">';
      if (strlen(preg_replace('#^https?://#', '', $row['post'])) > 6913) { 
        echo substr(preg_replace('#^https?://#', '', nl2br($row['post'])), 0, 6913).'&hellip;'; 
      } else { 
        echo nl2br($row['post']); 
      }
      echo '</p>';
      if ($_SESSION) {
        if (isset($_SESSION['id']) && $_SESSION['id'] == $user['id']) {
          echo '<a  class="delete_post_button" data-postID="'.$row['id'].'">Delete</a>';
        }
      } 
      echo '
        </div>';
    }
  }
}

function display_search() {
  $search_bar = <<<DELIMETER
  <form id="searchForm">
    <input type="hidden" name="page" value="search">
    <input type="text" name="q" id="search" placeholder="Search posts">
    <button type="submit" id="searchButton"></button>
  </form>
DELIMETER;
  echo $search_bar;
}

function display_navlist() {
  $nav_list= <<<DELIMETER
    <ul id="navList">
      <li class="nav-item">
        <a class="nav-link" href="?page=timeline">The community</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="?page=yourposts">Your posts</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="?page=publicprofiles">Public profiles</a>
      </li>
    </ul><!--"#navList"-->
DELIMETER;
  echo $nav_list;
}

function display_post_box() {
  if(isset($_SESSION['id']) && $_SESSION['id'] > 0) {
    $post_box = <<<DELIMETER
    <div class="postBox">
      <span class="success" id="postSuccess">Your post was successfully published.</span>
      <span class="danger" id="postFail">Could not publish post. Please try again later.</span>
      <textarea id="postTextfield" placeholder="Tell the world how you feel!"></textarea>
      <button id="createPostButton">Post</button>
    </div><!--postBox-->
DELIMETER;
    echo $post_box;
  }
}

function display_users() {
  global $link;
  $query = "SELECT * FROM users";
  $results = query($query);
  if (mysqli_num_rows($results) == 0) {
    echo "There are no users";
  } else {
    while ($row = fetch_assoc($results)) {
      echo "<p><a href='?page=publicprofiles&username=".$row['username']."'>".$row['username']."</a></p>";
    }
  }
}

?>