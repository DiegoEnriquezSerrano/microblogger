<?php

require_once "../../functions.php";

  if(isset($_POST)) {
    $json = json_decode(file_get_contents('php://input'), true);
  if (!isset($json['id'])) {
    echo "Post does not exist";
  } else {
    $user_post = bind_and_get_result("SELECT * FROM posts WHERE id = ?", "s", $json['id']);
    $row = fetch_assoc($user_post);
    if($row['userid'] == $_SESSION['id']) {
      echo "Cannot relay your own post.";
    } else {
      $date = date('Y-m-d H:i:s');
      $json['draft'] == false ? $info = 'posts' : $info = 'drafts';
      bind_and_execute_stmt(
        "INSERT INTO $info (`post`, `userid`, `datetime`, `is_repost`, `repost_from_post_id`) 
         VALUES ( ?, ?, ?, ?, ?)", "sssss", 
        [
          isset($json['text']) ? $json['text'] : '',
          esc($_SESSION['id']),
          esc($date), 1,
          $json['id']
        ]
      );
      $lastPostId = mysqli_insert_id($link);
      $lastPostResult = bind_and_get_result("SELECT * FROM posts WHERE id = ?", "s", $lastPostId);
      $lastPostRow = fetch_assoc($lastPostResult);
      echo display_posts('postid='.$lastPostId);
    }
  }
}