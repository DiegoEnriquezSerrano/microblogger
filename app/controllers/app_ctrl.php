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
    array(60 * 60 * 24 * 365 , 'yr'),
    array(60 * 60 * 24 * 30 , 'mo'),
    array(60 * 60 * 24 * 7, 'wk'),
    array(60 * 60 * 24 , 'day'),
    array(60 * 60 , 'hr'),
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
    $arrayKeys = [
      'user_name',
      'post_id',
      'user_id',
      'profile_img',
      'display_name',
      'created_at',
      'post_text'
    ];
    $postArray = [
      $post['post_user_name'],
      $post['post_id'],
      $post['post_user_id'], 
      $post['post_user_img'],
      $post['post_user_displayname'], 
      $post['post_created_at'],
      $post['post_text']
    ];
    if (array_key_exists('original_post_user_id', $post)) $originalPostArray = [
      $post['original_post_user_name'],
      $post['original_post_id'],
      $post['original_post_user_id'], 
      $post['original_post_user_img'],
      $post['original_post_user_displayname'], 
      $post['original_post_created_at'],
      $post['original_post_text']
    ];
    if (array_key_exists('original_relayed_post_user_id', $post)) $originalRelayedPostArray = [
      $post['original_relayed_post_user_name'],
      $post['original_post_repost_from_post_id'],
      $post['original_relayed_post_user_id'], 
      $post['original_relayed_post_user_img'],
      $post['original_relayed_post_user_displayname'], 
      $post['original_relayed_post_created_at'],
      $post['original_relayed_post_text']
    ];
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
    $arrayKeys = [
      'user_id',
      'user_name',
      'display_name',
      'profile_img',
      'user_bio',
      'header_img'];
    $userArray = [
      $user['user_id'],
      $user['user_name'],
      $user['user_display_name'], 
      $user['user_img'],
      $user['user_bio'],
      $user['user_header_img']
    ];
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
  global $paths;
  if ($paths[0] == 'drafts') $path = 'draft/';
  if (!isset($path)) $path = 'post/';
  return HOME_DIRECTORY.$path.$postId;
}

function getUserImage($scope, $criteria) {
  $userInfo = getScopeInformation($scope, $criteria);
  global $homeDirectory;
  $noImage = $homeDirectory.'rsc/profiledefault.jpg';
  if ($userInfo['profile_img'] == 'default') return $noImage;
  else return $homeDirectory.'uploads/'.$userInfo['profile_img'].'.jpg';
}

function getUserDisplayName($scope, $criteria) {
  $userInfo = getScopeInformation($scope, $criteria);
  if ($userInfo['display_name'] !== null) return preg_replace("/&lt;/", "&amp;lt;", $userInfo['display_name']);
  else return $userInfo['user_name'];
}

function getUserBio($scope, $criteria) {
  $userInfo = getScopeInformation($scope, $criteria);
  if (array_key_exists('user_bio', $userInfo)) return preg_replace("/&lt;/", "&amp;lt;", $userInfo['user_bio']);
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
    $isFollowingQueryResult = bind_and_get_result(
      "SELECT *
       FROM following_relations
       WHERE follower = ?
       AND is_following = ?", "ss", [$_SESSION['id'], $userid]
    );
    if ($_SESSION['id'] == $userid) {
      return '';
    } else if (mysqli_num_rows ($isFollowingQueryResult) > 0) {
      return $userPathBegin.$userid.'">
        <svg class="icon" width="20px" height="20px" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
          <title>User Unfollow</title>
          <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <g transform="translate(-220.000000, -2159.000000)" fill="#59617d">
              <g transform="translate(56.000000, 160.000000)">
                <path d="M178,2005 C178,2002.794 176.206,2001 174,2001 C171.794,2001 170,2002.794 170,2005 C170,2007.206 171.794,2009 174,2009 C176.206,2009 178,2007.206 178,2005 L178,2005 Z M184,2019 L179,2019 L179,2017 L181.784,2017 C180.958,2013.214 177.785,2011 174,2011 C170.215,2011 167.042,2013.214 166.216,2017 L169,2017 L169,2019 L164,2019 C164,2014.445 166.583,2011.048 170.242,2009.673 C168.876,2008.574 168,2006.89 168,2005 C168,2001.686 170.686,1999 174,1999 C177.314,1999 180,2001.686 180,2005 C180,2006.89 179.124,2008.574 177.758,2009.673 C181.417,2011.048 184,2014.445 184,2019 L184,2019 Z M171,2019 L177,2019 L177,2017 L171,2017 L171,2019 Z" id="profile_minus-[#1340]"></path>
              </g>
            </g>
          </g>
        </svg>
      </a>';
    } else return $userPathBegin.$userid.'">
      <svg class="icon" width="20px" height="22px" viewBox="0 0 20 22" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <title>User Follow</title>
        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
          <g transform="translate(-100.000000, -2159.000000)" fill="#59617d">
            <g transform="translate(56.000000, 160.000000)">
              <path d="M58.0831232,2004.99998 C58.0831232,2002.79398 56.2518424,2000.99998 54,2000.99998 C51.7481576,2000.99998 49.9168768,2002.79398 49.9168768,2004.99998 C49.9168768,2007.20598 51.7481576,2008.99998 54,2008.99998 C56.2518424,2008.99998 58.0831232,2007.20598 58.0831232,2004.99998 M61.9457577,2018.99998 L60.1246847,2018.99998 C59.5612137,2018.99998 59.1039039,2018.55198 59.1039039,2017.99998 C59.1039039,2017.44798 59.5612137,2016.99998 60.1246847,2016.99998 L60.5625997,2016.99998 C61.26898,2016.99998 61.790599,2016.30298 61.5231544,2015.66198 C60.2869889,2012.69798 57.3838883,2010.99998 54,2010.99998 C50.6161117,2010.99998 47.7130111,2012.69798 46.4768456,2015.66198 C46.209401,2016.30298 46.73102,2016.99998 47.4374003,2016.99998 L47.8753153,2016.99998 C48.4387863,2016.99998 48.8960961,2017.44798 48.8960961,2017.99998 C48.8960961,2018.55198 48.4387863,2018.99998 47.8753153,2018.99998 L46.0542423,2018.99998 C44.7782664,2018.99998 43.7738181,2017.85698 44.044325,2016.63598 C44.7874534,2013.27698 47.1076881,2010.79798 50.1639058,2009.67298 C48.7695192,2008.57398 47.8753153,2006.88998 47.8753153,2004.99998 C47.8753153,2001.44898 51.0234032,1998.61898 54.7339414,1999.04198 C57.422678,1999.34798 59.6500217,2001.44698 60.0532301,2004.06998 C60.4002955,2006.33098 59.4560733,2008.39598 57.8360942,2009.67298 C60.8923119,2010.79798 63.2125466,2013.27698 63.955675,2016.63598 C64.2261819,2017.85698 63.2217336,2018.99998 61.9457577,2018.99998 M57.0623424,2017.99998 C57.0623424,2018.55198 56.6050326,2018.99998 56.0415616,2018.99998 L55.2290201,2018.99998 C55.2290201,2019.99998 55.3351813,2020.99998 54.2082393,2020.99998 C53.6437475,2020.99998 53.1874585,2020.55198 53.1874585,2019.99998 L53.1874585,2018.99998 L51.9584384,2018.99998 C51.3949674,2018.99998 50.9376576,2018.55198 50.9376576,2017.99998 C50.9376576,2017.44798 51.3949674,2016.99998 51.9584384,2016.99998 L53.1874585,2016.99998 L53.1874585,2015.99998 C53.1874585,2015.44798 53.6437475,2014.99998 54.2082393,2014.99998 C54.7717103,2014.99998 55.2290201,2015.44798 55.2290201,2015.99998 L55.2290201,2016.99998 L56.0415616,2016.99998 C56.6050326,2016.99998 57.0623424,2017.44798 57.0623424,2017.99998" id="profile_plus_round-[#1343]"></path>
            </g>
          </g>
        </g>
      </svg>
    </a>';
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
  $heartFilled = '
  <a class="like_post_button" data-postID="'.$post['post_id'].'">
    <svg width="21px" height="16px" viewBox="0 0 21 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
      <title>love</title>
      <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="Dribbble-Dark-Preview" transform="translate(-139.000000, -361.000000)" fill="#59617d">
          <g id="icons" transform="translate(56.000000, 160.000000)">
            <path d="M103.991908,206.599878 C103.779809,210.693878 100.744263,212.750878 96.9821188,215.798878 C94.9997217,217.404878 92.0324261,217.404878 90.042679,215.807878 C86.3057345,212.807878 83.1651892,210.709878 83.0045394,206.473878 C82.8029397,201.150878 89.36438,198.971878 93.0918745,203.314878 C93.2955742,203.552878 93.7029736,203.547878 93.9056233,203.309878 C97.6205178,198.951878 104.274358,201.159878 103.991908,206.599878" id="love"></path>
          </g>
        </g>
      </g>
    </svg>
  </a>';
  $heartStroke = '
  <a class="like_post_button" data-postID="'.$post['post_id'].'">
    <svg width="21px" height="16px" viewBox="0 0 21 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
      <title>love</title>
      <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="Dribbble-Dark-Preview" transform="translate(-99.000000, -362.000000)" fill="#59617d">
          <g id="icons" transform="translate(56.000000, 160.000000)">
            <path d="M55.5929644,215.348992 C55.0175653,215.814817 54.2783665,216.071721 53.5108177,216.071721 C52.7443189,216.071721 52.0030201,215.815817 51.4045211,215.334997 C47.6308271,212.307129 45.2284309,210.70073 45.1034811,207.405962 C44.9722313,203.919267 48.9832249,202.644743 51.442321,205.509672 C51.9400202,206.088455 52.687619,206.420331 53.4940177,206.420331 C54.3077664,206.420331 55.0606152,206.084457 55.5593644,205.498676 C57.9649106,202.67973 62.083004,203.880281 61.8950543,207.507924 C61.7270546,210.734717 59.2322586,212.401094 55.5929644,215.348992 M53.9066671,204.31012 C53.8037672,204.431075 53.6483675,204.492052 53.4940177,204.492052 C53.342818,204.492052 53.1926682,204.433074 53.0918684,204.316118 C49.3717243,199.982739 42.8029348,202.140932 43.0045345,207.472937 C43.1651842,211.71635 46.3235792,213.819564 50.0426732,216.803448 C51.0370217,217.601149 52.2739197,218 53.5108177,218 C54.7508657,218 55.9898637,217.59915 56.9821122,216.795451 C60.6602563,213.815565 63.7787513,211.726346 63.991901,207.59889 C64.2754005,202.147929 57.6173611,199.958748 53.9066671,204.31012" id="love-[#1489]"></path>
          </g>
        </g>
      </g>
    </svg>
  </a>';
  $reply = '
  <a class="reply_post_buttom" data-postId="'.$postId.'">
    <svg width="21px" height="16px" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
      <title>reply</title>
      <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="Dribbble-Dark-Preview" transform="translate(-60.000000, -919.000000)" fill="#59617d">
          <g id="icons" transform="translate(56.000000, 160.000000)">
            <path d="M13.9577278,759 C7.99972784,759 3.26472784,764.127 4.09472784,770.125 C4.62372784,773.947 7.52272784,777.156 11.3197278,778.168 C12.7337278,778.545 14.1937278,778.625 15.6597278,778.372 C16.8837278,778.16 18.1397278,778.255 19.3387278,778.555 L20.7957278,778.919 L20.7957278,778.919 C22.6847278,779.392 24.4007278,777.711 23.9177278,775.859 C23.9177278,775.859 23.6477278,774.823 23.6397278,774.79 C23.3377278,773.63 23.2727278,772.405 23.5847278,771.248 C23.9707278,769.822 24.0357278,768.269 23.6887278,766.66 C22.7707278,762.415 18.8727278,759 13.9577278,759 M13.9577278,761 C17.9097278,761 21.0047278,763.71 21.7337278,767.083 C22.0007278,768.319 21.9737278,769.544 21.6547278,770.726 C20.3047278,775.718 24.2517278,777.722 19.8237278,776.614 C18.3507278,776.246 16.8157278,776.142 15.3187278,776.401 C14.1637278,776.601 12.9937278,776.544 11.8347278,776.236 C8.80772784,775.429 6.49272784,772.863 6.07572784,769.851 C5.40472784,764.997 9.26872784,761 13.9577278,761 L13.9577278,761" id="message-[#1579]"></path>
          </g>
        </g>
      </g>
    </svg>
  </a>';
  $relay = '
  <a class="relay_post_button" data-postID="'.$postId.'">
    <svg width="21px" height="16px" viewBox="0 0 20 27" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
      <title>arrow_repeat</title>
      <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="Dribbble-Dark-Preview" transform="translate(-100.000000, -7074.000000)" fill="#59617d">
          <g id="icons" transform="translate(56.000000, 160.000000)">
            <path d="M64,6919.75588 L58.343,6914 L56.929,6915.51204 L60.172,6918.7711 L44,6918.7711 L44,6928.32285 L46,6928.32285 L46,6920.68145 L60.172,6920.68145 L56.929,6923.61479 L58.343,6924.88326 L64,6919.75588 Z M62,6935.96425 L47.828,6935.96425 L51.071,6932.70328 L49.657,6931.27052 C44.671,6935.98431 45.809,6934.91069 44,6936.61186 C46.227,6938.72566 44.99,6937.54888 49.657,6942 L51.071,6940.81081 L47.828,6937.8746 L64,6937.8746 L64,6928.32285 L62,6928.32285 L62,6935.96425 Z" id="arrow_repeat-[#241]"></path>
          </g>
        </g>
      </g>
    </svg>
  </a> ';
  $delete = '
  <a class="delete_post_button" data-postID="'.$postId.'">
    <svg width="21px" height="16px" viewBox="0 0 21 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
      <title>Delete</title>
      <g iD="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="Dribbble-Dark-Preview" transform="translate(-179.000000, -360.000000)" fill="#59617d">
          <g id="icons" transform="translate(56.000000, 160.000000)">
            <path d="M130.35,216 L132.45,216 L132.45,208 L130.35,208 L130.35,216 Z M134.55,216 L136.65,216 L136.65,208 L134.55,208 L134.55,216 Z M128.25,218 L138.75,218 L138.75,206 L128.25,206 L128.25,218 Z M130.35,204 L136.65,204 L136.65,202 L130.35,202 L130.35,204 Z M138.75,204 L138.75,200 L128.25,200 L128.25,204 L123,204 L123,206 L126.15,206 L126.15,220 L140.85,220 L140.85,206 L144,206 L144,204 L138.75,204 Z" id="delete-[#1487]"></path>
          </g>
        </g>
      </g>
    </svg>
  </a>';
  if (isset($_SESSION['id'])) {
    if ($_SESSION['id'] == $postUserId || array_key_exists('original_post_user_id', $post) && $_SESSION['id'] == $post['post_user_id']) {
      return $reply.$relay.$delete;
    } else {
      $isPostLikedQueryResult = bind_and_get_result("SELECT * FROM liked_relations WHERE user = ? AND post_liked = ?", "ss", [$_SESSION['id'], $postId]);
      if (mysqli_num_rows($isPostLikedQueryResult) > 0) {
        $likedStatus = $heartFilled;
      } else {
        $likedStatus = $heartStroke;
      }
      if (!array_key_exists('original_post_deleted', $post) || (array_key_exists('original_post_deleted', $post) && ($_SESSION['id'] !== $postUserId && $post['post_text'] !== ''))) {
        return $reply.$relay.$likedStatus;
      }
    }
  }
}

function getPostRelayInfo($post) {
  if ($post['is_repost'] == 1) {
    if (array_key_exists('original_post_user_name', $post)) {
    return '<span class="relay-text">'.$post['post_user_name'].' relayed '.$post['original_post_user_name'].' '.timeSinceDatetime($post['post_created_at']).' ago'.' </span>';
    } else {
      return '<span class="relay-text">'.$post['post_user_name'].' relayed this post '.timeSinceDatetime($post['post_created_at']).' ago'.' </span>';
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
  $posts = '';

  if (!$results || mysqli_num_rows($results) < 1) {
    return "There are no {$table} to display";
  } else {

    while ($row = fetch_assoc($results)) {

      $postResult = bind_and_get_result(
        "SELECT
          {$table}.id AS post_id,
          {$table}.userid AS post_user_id,
          users.username AS post_user_name,
          profiles.user_display_name AS post_user_displayname,
          COALESCE(profiles.profile_img, 'default') AS post_user_img,
          profiles.profile_header_img AS post_user_header_img,
          profiles.user_bio AS post_user_bio,
          {$table}.post AS post_text,
          {$table}.datetime AS post_created_at,
          {$table}.is_repost AS is_repost,
          {$table}.repost_from_post_id AS original_post_id
         FROM {$table}
         LEFT JOIN users ON users.id = {$table}.userid
         LEFT JOIN profiles ON profiles.user_id = {$table}.userid
         WHERE {$table}.id = ?","s", esc($row['id']));

      if (mysqli_num_rows($postResult) < 1) {
        return 'No posts to show';
      }

      $post = fetch_assoc($postResult);

      if ($post['is_repost'] == '1') {
        $originalPostResult = bind_and_get_result(
          "SELECT
            posts.userid AS original_post_user_id,
            users.username AS original_post_user_name,
            profiles.user_display_name AS original_post_user_displayname,
            COALESCE(profiles.profile_img, 'default') AS original_post_user_img,
            profiles.profile_header_img AS original_post_user_header_img,
            profiles.user_bio AS original_post_user_bio,
            posts.post AS original_post_text,
            posts.datetime AS original_post_created_at,
            posts.is_repost AS original_post_is_repost,
            posts.repost_from_post_id AS original_post_repost_from_post_id
           FROM posts
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
            "SELECT 
              posts.userid AS original_relayed_post_user_id,
              users.username AS original_relayed_post_user_name,
              profiles.user_display_name AS original_relayed_post_user_displayname,
              COALESCE(profiles.profile_img, 'default') AS original_relayed_post_user_img,
              profiles.profile_header_img AS original_relayed_post_user_header_img,
              profiles.user_bio AS original_relayed_post_user_bio,
              posts.post AS original_relayed_post_text,
              posts.datetime AS original_relayed_post_created_at
             FROM posts
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
              <div class="user_img" style="background-image: url('$postUserImagePath')"></div>
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

        <div class="post">
          {$relayedMessage}
          <div class="post_header">
{$postdetailHeader}
            <p class="post_details_box">
{$postDetailsBox}
            </p><!--post_details_box-->
          </div><!--post_header-->
          <div class="postContent">
            <p class="postBody">{$postTextContent}</p>
            <span class="post_deleted"> This post no longer exists. </span>
          </div><!--postContent-->
          {$postInteractButtons}
        </div><!--post-->
DELIMETER;

      } else {

        if (($post['is_repost'] == '1' && $post['post_text'] !== '') || 
          ($post['post_text'] == '' && $post['is_repost'] == 1 && !array_key_exists('original_post_deleted', $post) && $post['original_post_is_repost'] == 1)) {
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

            <div class="post">
              <div class="post_header">
                <a class="user_avatar" href="{$childPostUserLinkPath}">
                  <div class="user_img" style="background-image: url('$childPostUserImagePath')"></div>
                </a>
                <p class="post_details_box">
                  <a class="userlink" href="{$childPostUserLinkPath}">{$childPostUserDisplayName}</a> @{$childPostUserName}<br>
                  <span class="time"> {$childPostCreatedAtDatetime} ago </span> <a class="postArrow" href="$childPostPagePath">&#x27A4;</a><br>
                  {$childPostIsFollowingLink}
                </p><!--post_details_box-->
              </div><!--post_header-->
              <p class="postContent">
                {$childPostTextContent}
              </p>
            </div><!--post-->
DELIMETER;
        } else {
          $postInnerContent = '';
        }

        $echoPost = <<<DELIMETER

        <div class="post">
          {$relayedMessage}
          <div class="post_header">
            <a class="user_avatar" href="{$postUserLinkPath}">
              <div class="user_img" style="background-image: url('$postUserImagePath')"></div>
            </a>
            <p class="post_details_box">
              <a class="userlink" href="{$postUserLinkPath}">{$postUserDisplayName}</a> @{$postUserName}<br>
              <span class="time"> {$postCreatedAtDatetime} ago </span> <a class="postArrow" href="$postPagePath">&#x27A4;</a>
            </p><!--post_details_box-->
          </div><!--post_header-->
          <div class="postContent">
            <p class="postBody">{$postTextContent}</p>
            {$postInnerContent}
          </div><!--postContent-->
          <div class="post_interaction_buttons">
            {$postInteractButtons}
          </div><!--post_interaction_buttons-->
        </div><!--post-->
DELIMETER;
      }
      $posts .= $echoPost;
    }
  }
  return $posts;
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
  $users = '';

  if (!$results || mysqli_num_rows($results) < 1) {
    return "There are no users to display";
  } else {
    while ($row = fetch_assoc($results)) {
      $userResult = bind_and_get_result(
        "SELECT
          users.id AS user_id,
          users.username AS user_name,
          profiles.user_display_name, 
          COALESCE(profiles.profile_img, 'default') AS user_img,
          profiles.user_bio,
          profiles.profile_header_img AS user_header_img
         FROM users
         LEFT JOIN profiles ON profiles.user_id = users.id
         WHERE users.id = ?", "s", esc($row['id'])
      );

      if (mysqli_num_rows($userResult) < 1) {
        echo 'That user does not exist';
      }

      $user = fetch_assoc($userResult);

      // foreach($user as $key => $value) {
      //   echo $key.': '.$value.'<br>';
      // };

      $userId = getUserId($user, '');
      $userName = getUserName($user, '');
      $userDisplayName = getUserDisplayName($user, '');
      $userImage = getUserImage($user, '');
      $userPath = getUserLink($user, '');
      $userBio = getUserBio($user, '');
      $userHeader = getUserHeader($user, '');
      $userFollowButton = getUserIsFollowing($user, '');

      $echoUser = <<<DELIMETER

      <div class="userNode">
        <div class="userNodeInfo">
          <a class="user_avatar" href="{$userPath}">
            <div class="user_img" style="background-image: url('$userImage')"></div>
          </a><br>
          <a class="userName" href="$userPath">{$userDisplayName}</a><br>
          <span>@{$userName}</span><br>
          <p class="userNodeBio">{$userBio}</p>
        </div><!--userNodeInfo-->

        <div class="userNodeActions">
          {$userFollowButton}
          <a href="">
            <svg class="icon" width="30px" height="30px" viewBox="0 0 20 15" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <title>Direct Message</title>
              <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g transform="translate(-300.000000, -922.000000)" fill="#59617d">
                  <g transform="translate(56.000000, 160.000000)">
                    <path d="M262,764.291 L254,771.318 L246,764.281 L246,764 L262,764 L262,764.291 Z M246,775 L246,766.945 L254,773.98 L262,766.953 L262,775 L246,775 Z M244,777 L264,777 L264,762 L244,762 L244,777 Z" id="email-[#1573]"></path>
                  </g>
                </g>
              </g>
            </svg>
          </a>
          <a href="">
            <svg class="icon" width="30px" height="30px" viewBox="0 0 21 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <title>block</title>
              <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g transform="translate(-219.000000, -600.000000)" fill="#59617d">
                  <g transform="translate(56.000000, 160.000000)">
                    <path d="M177.7,450 C177.7,450.552 177.2296,451 176.65,451 L170.35,451 C169.7704,451 169.3,450.552 169.3,450 C169.3,449.448 169.7704,449 170.35,449 L176.65,449 C177.2296,449 177.7,449.448 177.7,450 M173.5,458 C168.86845,458 165.1,454.411 165.1,450 C165.1,445.589 168.86845,442 173.5,442 C178.13155,442 181.9,445.589 181.9,450 C181.9,454.411 178.13155,458 173.5,458 M173.5,440 C167.70085,440 163,444.477 163,450 C163,455.523 167.70085,460 173.5,460 C179.29915,460 184,455.523 184,450 C184,444.477 179.29915,440 173.5,440" id="minus_circle-[#1426]"></path>
                  </g>
                </g>
              </g>
            </svg>
          </a>
        </div><!--userNodeActions-->
      </div><!--userNode-->
DELIMETER;

      $users .= $echoUser;
    }
  }
  return $users;
}