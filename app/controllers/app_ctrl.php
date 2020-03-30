<?php

$homeDirectory = HOME_DIRECTORY;
$userDirectory = USER_DIRECTORY;
$postDirectory = POST_DIRECTORY;
$editDirectory = EDIT_DIRECTORY;
$stylesDirectory = STYLES_DIRECTORY;
$fontsDirectory = FONTS_DIRECTORY;
$javascriptDirectory = JAVASCRIPT_DIRECTORY;

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

function timeSinceDateTime($datetime) {
  return time_since(time() - strtotime($datetime));
}

function url($location) {
  header("Location: $location ");
}

function checkForSession() { 
  if (!isset($_SESSION['id'])) url(HOME_DIRECTORY);
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
  } else if (gettype($values) == "string" || "integer" || "boolean" || "NULL" || "double") {
    $stmt->bind_param($placeholder, $values);
  } else return "this is not a valid command";
  return $stmt->execute();
}

function bind_and_get_result($prepared_sql, $placeholder, $values) {
  global $link;
  $stmt = $link->prepare($prepared_sql);
  if(gettype($values) == "array") {
    $stmt->bind_param($placeholder, ...$values);
  } else if (gettype($values) == "string" || "integer" || "boolean" || "NULL" || "double") {
    $stmt->bind_param($placeholder, $values);
  } else return "this is not a valid command";
  $stmt->execute();
  return mysqli_stmt_get_result($stmt);
}

function concatArrayValuesAsString($array) {
  $arrayReturn = '';
  foreach($array as $values) {
    $arrayReturn .= $values;
  }
  return $arrayReturn;
}

function getScopeInformation($object, $criteria) {
  if(array_key_exists('post_id', $object)) {
    $post = $object;
    $arrayKeys = array('user_name', 'post_id', 'user_id', 'img_status', 'img_ext', 'display_name', 'created_at', 'post_text');
    $postArray = array(
      $post['post_user_name'], $post['post_id'], $post['post_user_id'], 
      $post['post_user_img_status'], $post['post_user_img_ext'], $post['post_user_displayname'], 
      $post['post_created_at'], $post['post_text']);
    if (array_key_exists('original_post_user_id', $post)) $originalPostArray = array(
      $post['original_post_user_name'], $post['original_post_id'], $post['original_post_user_id'], 
      $post['original_post_user_img_status'], $post['original_post_user_img_ext'], $post['original_post_user_displayname'], 
      $post['original_post_created_at'], $post['original_post_text']);
    if (array_key_exists('original_relayed_post_user_id', $post)) $originalRelayedPostArray = array(
      $post['original_relayed_post_user_name'], $post['original_post_repost_from_post_id'], $post['original_relayed_post_user_id'], 
      $post['original_relayed_post_user_img_status'], $post['original_relayed_post_user_img_ext'], $post['original_relayed_post_user_displayname'], 
      $post['original_relayed_post_created_at'], $post['original_relayed_post_text']);
    if($criteria == 'child') {
      if(array_key_exists('original_relayed_post_user_id', $post)) {
        $userPostArray = $originalRelayedPostArray;
      } else $userPostArray = $originalPostArray;
    } else if($criteria == 'parent') {
      if(array_key_exists('original_relayed_post_user_id', $post)) {
        $userPostArray = $originalPostArray;
      } else if(array_key_exists('original_post_user_name', $post) && $post['post_text'] == '') {
        $userPostArray = $originalPostArray;
      } else $userPostArray = $postArray;
    } 
    if (isset($userPostArray)) return array_combine($arrayKeys, $userPostArray);
    else return 'This is not a valid command';
  } else if (array_key_exists('user_id', $object)) {
    $user = $object;
    $arrayKeys = array('user_id', 'user_name', 'display_name', 'img_status', 'img_ext', 'user_bio', 'header_img');
    $userArray = array($user['user_id'], $user['user_name'], $user['user_display_name'], $user['user_img_status'], $user['user_img_ext'], $user['user_bio'], $user['user_header_img']);
    return array_combine($arrayKeys, $userArray);
  } else return 'This is not a valid command';
}

function getPostId($post, $criteria) {
  $postid = getScopeInformation($post, $criteria);
  return $postid['post_id'];
}

function getUserId($scope, $criteria) {
  $userid = getScopeInformation($scope, $criteria);
  return $userid['user_id'];
}

function getUserName($scope, $criteria) {
  $username = getScopeInformation($scope, $criteria);
  return $username['user_name'];
}

function getUserLink($scope, $criteria) {
  global $homeDirectory;
  $userName = getUserName($scope, $criteria);
  return $homeDirectory.esc($userName);
}

function getPostPagePath($post, $criteria) {
  $postId = getPostId($post, $criteria);
  global $parameterArray;
  if ($parameterArray[0] == 'drafts') $path = 'draft/';
  if (!isset($path)) $path = 'post/';
  return HOME_DIRECTORY.$path.$postId;
}

function getUserImage($scope, $criteria) {
  $userInfo = getScopeInformation($scope, $criteria);
  global $homeDirectory;
  $noImage = $homeDirectory.'rsc/profiledefault.jpg';
  if ($userInfo['img_status'] == 1) return $noImage;
  else return $homeDirectory.'uploads/profile'.$userInfo['user_id'].$userInfo['img_ext'];
}

function getUserDisplayName($scope, $criteria) {
  $userInfo = getScopeInformation($scope, $criteria);
  if ($userInfo['display_name'] !== null) return $userInfo['display_name'];
  else return $userInfo['user_name'];
}

function getUserBio($scope, $criteria) {
  $userInfo = getScopeInformation($scope, $criteria);
  if (array_key_exists('user_bio', $userInfo)) return $userInfo['user_bio'];
  else return '';
}

function getUserHeader($scope, $criteria) {
  $userInfo = getScopeInformation($scope, $criteria);
  if (array_key_exists('user_header_img', $userInfo)) return $userInfo['user_header_img'];
  else return '';
}

function getPostCreatedTime($post, $criteria) {
  $postTime = getScopeInformation($post, $criteria);
  return timeSinceDateTime($postTime['created_at']);
}

function getUserIsFollowing($scope, $criteria) {
  if (isset($_SESSION['id'])) {
    $userid = getUserId($scope, $criteria);
    $userPathBegin = '<a class="toggleFollow" data-userId="';
    $isFollowingQueryResult = bind_and_get_result("SELECT * FROM following_relations WHERE follower = ? AND is_following = ?", "ss", $new=array($_SESSION['id'], $userid));
    if ($_SESSION['id'] == $userid) {
      return '';
    } else if (mysqli_num_rows ($isFollowingQueryResult) > 0) {
      return $userPathBegin.$userid.'">&#x2212; Unfollow</a>';
    } else return $userPathBegin.$userid.'">&#x2b; Follow</a>';
  } else return '<a class="loginModalButtons">Sign up or Login to follow</a>';
}

function getPostTextContent($post, $criteria) {
  $postText = getScopeInformation($post, $criteria);
  if (strlen(preg_replace('#^https?://#', '', $postText['post_text'])) > 6913) { 
    return substr(preg_replace('#^https?://#', '', nl2br($postText['post_text'])), 0, 6913).'&hellip;'; 
  } else { 
    return nl2br($postText['post_text']); 
  }
}

function getPostInteractButtons($post, $criteria) {
  $postUserId = getUserId($post, $criteria);
  $postId = getPostId($post, $criteria);
  if (isset($_SESSION['id'])) {
    if ($_SESSION['id'] == $postUserId) {
      return '<a  class="delete_post_button" data-postID="'.$postId.'">&#x00D7; Delete</a>';
    } else {
      if (array_key_exists('original_post_user_id', $post) && $_SESSION['id'] == $post['post_user_id']) {
        return '<a class="delete_post_button" data-postID="'.$post['post_id'].'">&#x00D7; Delete</a>';
      }
      $isPostLikedQueryResult = bind_and_get_result("SELECT * FROM liked_relations WHERE user = ? AND post_liked = ?", "ss", $new=array($_SESSION['id'], $postId));
      if (mysqli_num_rows($isPostLikedQueryResult) > 0) {
        $likedStatus = '<a class="like_post_button" data-postID="'.$post['post_id'].'">&#x2665; Unlike</a>';
      } else {
        $likedStatus = '<a class="like_post_button" data-postID="'.$post['post_id'].'">&#x2665; Like</a>';
      }
      if (!array_key_exists('original_post_deleted', $post) || (array_key_exists('original_post_deleted', $post) && ($_SESSION['id'] !== $postUserId && $post['post_text'] !== ''))) {
        return '<a class="relay_post_button" data-postID="'.$postId.'">&#x21c4; Relay</a> '.$likedStatus;
      }
    }
  }
}

function getPostRelayInfo($post) {
  if ($post['is_repost'] == 1) {
    if (array_key_exists('original_post_user_name', $post)) {
    return '<span class="relay-text">'.$post['post_user_name'].' relayed '.$post['original_post_user_name'].' </span>'.timeSinceDatetime($post['post_created_at']).' ago';
    } else {
      return '<span class="relay-text">'.$post['post_user_name'].' relayed this post </span>'.timeSinceDatetime($post['post_created_at']).' ago';
    }
  }
}

function display_posts($type) {
  global $homeDirectory;
  global $link;
  $endClause = ' ORDER BY `datetime` DESC LIMIT 20';
  if ($type == 'public') {
    $whereClause = "".esc($endClause);

  } else if (strpos($type, 'userid=') !== false) {
    $userid = explode("=", $type);
    $whereClause = ' WHERE userid = '.esc($userid[1]).esc($endClause);

  } else if (strpos($type, 'postid=') !== false) {
    $postid = explode("=", $type);
    $whereClause = ' WHERE id = '.esc($postid[1]).esc($endClause);

  } else if ($type == 'random') {
    $whereClause = ' ORDER BY RAND() LIMIT 1';

  } else if ($type == 'search') {
    echo "<p>Showing results for '". esc($_GET['q'])."':</p>";
    $whereClause = " WHERE post LIKE '%". esc($_GET['q']) ."%'".esc($endClause);
///////////////////////////////////////////////
////////////////////////////AUTH REQUIRED BEGIN
  } else if (!isset($_SESSION['id'])) {
    $whereClause = ' WHERE userid = 0';

  } else if ($type == 'isFollowing') {
    $whereClause = " WHERE userid = ".esc($_SESSION['id'])." OR userid IN 
                      (SELECT is_following FROM following_relations WHERE follower = ".esc($_SESSION['id']).")".esc($endClause);
  
  } else if ($type == 'yourposts') {
    $whereClause = " WHERE userid = ".esc($_SESSION['id']).esc($endClause);

  } else if ($type == 'liked') {
    $whereClause = " WHERE id IN (SELECT post_liked FROM liked_relations WHERE user = ".esc($_SESSION['id']).")".esc($endClause);

  } else if ($type == 'yourdrafts') {
    $whereClause = " WHERE userid = ".esc($_SESSION['id']).esc($endClause);
    $table = 'drafts';

  } else if (strpos($type, 'draftid=') !== false) {
    $draftid = explode("=", $type);
    $whereClause = ' WHERE id = '.esc($draftid[1]).esc($endClause);
    $table = 'drafts';
  }
///////////////////////////////////////////////
//////////////////////////////AUTH REQUIRED END

  if(!isset($table)) {
    $table = 'posts';
  }

  $query = "SELECT * FROM ".$table.$whereClause;
  $results = query($query);

  if (!$results || mysqli_num_rows($results) < 1) {
    echo "There are no {$table} to display";
  } else {

    while ($row = fetch_assoc($results)) {

      $postResult = bind_and_get_result(
        "SELECT {$table}.id AS post_id, {$table}.userid AS post_user_id, users.username AS post_user_name,
                profiles.user_display_name AS post_user_displayname, profileimg.status AS post_user_img_status,
                profileimg.id AS post_user_img_id, profileimg.file_ext AS post_user_img_ext,
                profiles.profile_header_img AS post_user_header_img, profiles.user_bio AS post_user_bio, 
                {$table}.post AS post_text, {$table}.datetime AS post_created_at, {$table}.is_repost AS is_repost, 
                {$table}.repost_from_post_id AS original_post_id
         FROM {$table}
         INNER JOIN profileimg ON profileimg.userid = {$table}.userid
         LEFT JOIN users ON users.id = {$table}.userid
         LEFT JOIN profiles ON profiles.user_id = {$table}.userid
         WHERE {$table}.id = ?","s", esc($row['id']));

      if (mysqli_num_rows($postResult) < 1) {
        echo 'That post does not exist';
      }

      $post = fetch_assoc($postResult);

      if ($post['is_repost'] == '1') {
        $originalPostResult = bind_and_get_result(
          "SELECT posts.userid AS original_post_user_id, users.username AS original_post_user_name,
                  profiles.user_display_name AS original_post_user_displayname, profileimg.status AS original_post_user_img_status,
                  profileimg.id AS original_post_user_img_id, profileimg.file_ext AS original_post_user_img_ext,
                  profiles.profile_header_img AS original_post_user_header_img, profiles.user_bio AS original_post_user_bio, 
                  posts.post AS original_post_text, posts.datetime AS original_post_created_at, posts.is_repost AS original_post_is_repost,
                  posts.repost_from_post_id AS original_post_repost_from_post_id
           FROM posts
           INNER JOIN profileimg ON profileimg.userid = posts.userid
           LEFT JOIN users ON users.id = posts.userid
           LEFT JOIN profiles ON profiles.user_id = posts.userid
           WHERE posts.id = ?","s", esc($post['original_post_id']));

        if (mysqli_num_rows($originalPostResult) < 1) {
          $postDeleted = array('original_post_deleted' => 'true');
          $post = array_merge($post, $postDeleted);
        } else {
          $originalPost = fetch_assoc($originalPostResult);
          $post = array_merge($post, $originalPost);
        }

        if (array_key_exists('original_post_is_repost', $post) && $post['original_post_is_repost'] == '1') {
          $originalRelayedPostResult = bind_and_get_result(
            "SELECT posts.userid AS original_relayed_post_user_id, users.username AS original_relayed_post_user_name,
                    profiles.user_display_name AS original_relayed_post_user_displayname, profileimg.status AS original_relayed_post_user_img_status,
                    profileimg.id AS original_relayed_post_user_img_id, profileimg.file_ext AS original_relayed_post_user_img_ext,
                    profiles.profile_header_img AS original_relayed_post_user_header_img, profiles.user_bio AS original_relayed_post_user_bio, 
                    posts.post AS original_relayed_post_text, posts.datetime AS original_relayed_post_created_at
             FROM posts
             INNER JOIN profileimg ON profileimg.userid = posts.userid
             LEFT JOIN users ON users.id = posts.userid
             LEFT JOIN profiles ON profiles.user_id = posts.userid
             WHERE posts.id = ?","s", esc($post['original_post_repost_from_post_id']));
  
          if (mysqli_num_rows($originalRelayedPostResult) < 1) {
            $relayedPostDeleted = array('original_relayed_post_deleted' => 'true');
            $post = array_merge($post, $relayedPostDeleted);
          } else {
            $originalRelayedPost = fetch_assoc($originalRelayedPostResult);
            $post = array_merge($post, $originalRelayedPost);
          }
        }
      }

      // foreach($post as $key => $value) {
      //   echo $key.': '.$value.'<br>';
      // };

      $postUserName = getUserName($post, 'parent');
      $postUserLinkPath = getUserLink($post, 'parent');
      $postUserImagePath = getUserImage($post, 'parent');
      $postUserDisplayName = getUserDisplayName($post, 'parent');
      $postIsFollowingLink = getUserIsFollowing($post, 'parent');
      $postPagePath = getPostPagePath($post, 'parent');
      $postCreatedAtDatetime = getPostCreatedTime($post, 'parent');
      $postTextContent = getPostTextContent($post, 'parent');
      $postInteractButtons = getPostInteractButtons($post, 'parent');
      $relayedMessage = getPostRelayInfo($post);

      if (isset($post['original_post_deleted']) && $post['original_post_deleted'] == 'true' || array_key_exists('original_relayed_post_deleted', $post)) {

        if (isset($post['original_post_deleted']) && $post['post_text'] !== '' || array_key_exists('original_relayed_post_deleted', $post)) {
          $postdetailHeader = <<<DELIMETER
            <a class="user_avatar" href="{$postUserLinkPath}">
              <img src="{$postUserImagePath}">
            </a>
DELIMETER;

          $postDetailsBox = <<<DELIMETER
              <a class="userlink" href="{$postUserLinkPath}">{$postUserDisplayName}</a> @{$postUserName}<br>
              <span class="time"> {$postCreatedAtDatetime} ago </span> <a class="postArrow" href="{$postPagePath}">&#x27A4;</a><br>
          {$postIsFollowingLink}
DELIMETER;

        } else {
          $postdetailHeader = '';
          $postDetailsBox = '';
        }

        $echoPost = <<<DELIMETER

        <div class="tweet">
          {$relayedMessage}
          <div class="post_header">
{$postdetailHeader}
            <p class="post_details_box">
{$postDetailsBox}
            </p><!--post_details_box-->
          </div><!--post_header-->
          <div class="tweetContent">
            {$postTextContent}
            <span class="post_deleted"> This post no longer exists. </span>
          </div><!--tweetContent-->
          {$postInteractButtons}
        </div><!--tweet-->
DELIMETER;

      } else {

        if (($post['is_repost'] == '1' && $post['post_text'] !== '') || ($post['post_text'] == '' && $post['is_repost'] == 1 && !array_key_exists('original_post_deleted', $post) && $post['original_post_is_repost'] == 1)) {
          $relayedMessage = getPostRelayInfo($post);
          $childPostUserName = getUserName($post, 'child');
          $childPostUserLinkPath = getUserLink($post, 'child');
          $childPostUserDisplayName = getUserDisplayName($post, 'child');
          $childPostUserImagePath = getUserImage($post, 'child');
          $childPostIsFollowingLink = getUserIsFollowing($post, 'child');
          $childPostPagePath = getPostPagePath($post, 'child');
          $childPostCreatedAtDatetime = getPostCreatedTime($post, 'child');
          $childPostTextContent = getPostTextContent($post, 'child');
          $postInnerContent = <<<DELIMETER

            <div class="tweet">
              <div class="post_header">
                <a class="user_avatar" href="{$childPostUserLinkPath}">
                  <img src="{$childPostUserImagePath}">
                </a>
                <p class="post_details_box">
                  <a class="userlink" href="{$childPostUserLinkPath}">{$childPostUserDisplayName}</a> @{$childPostUserName}<br>
                  <span class="time"> {$childPostCreatedAtDatetime} ago </span> <a class="postArrow" href="$childPostPagePath">&#x27A4;</a><br>
                  {$childPostIsFollowingLink}
                </p><!--post_details_box-->
              </div><!--post_header-->
              <p class="tweetContent">
                {$childPostTextContent}
              </p>
            </div><!--tweet-->
DELIMETER;
        } else {
          $postInnerContent = '';
        }

        $echoPost = <<<DELIMETER

        <div class="tweet">
          {$relayedMessage}
          <div class="post_header">
            <a class="user_avatar" href="{$postUserLinkPath}">
              <img src="{$postUserImagePath}">
            </a>
            <p class="post_details_box">
              <a class="userlink" href="{$postUserLinkPath}">{$postUserDisplayName}</a> @{$postUserName}<br>
              <span class="time"> {$postCreatedAtDatetime} ago </span> <a class="postArrow" href="$postPagePath">&#x27A4;</a><br>
              {$postIsFollowingLink}
            </p><!--post_details_box-->
          </div><!--post_header-->
          <div class="tweetContent">
            {$postTextContent}
            {$postInnerContent}
          </div><!--tweetContent-->
          {$postInteractButtons}
        </div><!--tweet-->
DELIMETER;
      }

      echo $echoPost;
    }
  }
}

function displayUsers($who) {
  global $homeDirectory;
  global $link;
  if(isset($_SESSION['id'])) $id = esc($_SESSION['id']);
  if ($who == 'all') {
    $whereClause = "";
  } else if (!isset($_SESSION['id'])) {
    $whereClause = 'WHERE id = 0';
  } else if ($who == 'following') {
    $whereClause = 'WHERE id IN (SELECT is_following FROM following_relations WHERE follower = '.$id.')';
  } else if ($who == 'followers') {
    $whereClause = 'WHERE id IN (SELECT follower FROM following_relations WHERE is_following = '.$id.')';
  } else if ($who == 'mutuals') {
    $whereClause = 'WHERE id IN (SELECT follower FROM following_relations WHERE is_following = '.$id.')
                    AND id IN (SELECT is_following FROM following_relations WHERE follower = '.$id.')
                    AND id != '.$id;
  }
  $query = "SELECT * FROM users ".$whereClause." ORDER BY RAND() DESC LIMIT 20";
  $results = query($query);

  if (!$results || mysqli_num_rows($results) < 1) {
    echo "There are no users to display";
  } else {
    while ($row = fetch_assoc($results)) {
      $userResult = bind_and_get_result(
        "SELECT users.id AS user_id, users.username AS user_name, profiles.user_display_name, profileimg.status AS user_img_status, 
                profileimg.file_ext AS user_img_ext, profiles.user_bio, profiles.profile_header_img AS user_header_img
         FROM users 
         INNER JOIN profileimg ON profileimg.userid = users.id 
         LEFT JOIN profiles ON profiles.user_id = users.id
         WHERE users.id = ?","s", esc($row['id']));

      if (mysqli_num_rows($userResult) < 1) {
        echo 'That user does not exist';
      }

      $user = fetch_assoc($userResult);

      // foreach($user as $key => $value) {
      //   echo $key.': '.$value.'<br>';
      // };

      // global $homeDirectory;
      $userId = getUserId($user, '');
      $userName = getUserName($user, '');
      $userDisplayName = getUserDisplayName($user, '');
      $userImage = getUserImage($user, '');
      $userPath = getUserLink($user, '');
      $userBio = getUserBio($user, '');
      $userHeader = getUserHeader($user, '');
      $userFollowButton = getUserIsFollowing($user, '');

      $echoUser = <<<DELIMETER

      <div class="user_node">
        <div class="user_node_header">
        </div>
        <div class="user_node_info">
          <div class="user_node_name_img">
            <a data-header="{$userHeader}" href="{$userPath}"><img src="{$userImage}"></a>
            <span class="user_node_user">
              <span class="the_holder" data-header="{$userHeader}">
                <div class="spacer"></div>
                <a class="userName" href="$userPath">{$userDisplayName}</a><br>
                @{$userName}
              </span>
            </span>
            {$userFollowButton}
          </div><!--user_node_name_img-->
          <p class="user_node_bio">{$userBio}</p>
        </div><!--user_node_info-->
      </div>
DELIMETER;

      echo $echoUser;
    }
  }
}