<?php

  include_once "../../functions.php";

  if (!isset($_GET['id'])) {
    echo "Post does not exist";
  } else {
    $user_post = bind_and_get_result("SELECT * FROM posts WHERE id = ?", "s", $_GET['id']);
    $row = fetch_assoc($user_post);
    if($row['userid'] == $_SESSION['id']) {
      echo "Cannot relay your own post.";
    } else {
      $date = date('Y-m-d H:i:s');
      bind_and_execute_stmt("INSERT INTO posts (`post`, `userid`, `datetime`, `is_repost`, `repost_from_post_id`) VALUES ( ?, ?, ?, ?, ?)", "sssss", $new=array('', esc($_SESSION['id']), esc($date), 1, $_GET['id']));
      $lastPostId = mysqli_insert_id($link);
      $lastPostResult = bind_and_get_result("SELECT * FROM posts WHERE id = ?", "s", $lastPostId);
      $lastPostRow = fetch_assoc($lastPostResult);
      echo display_posts('postid='.$lastPostId);
    }
  }