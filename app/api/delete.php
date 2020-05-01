<?php

require_once "../../functions.php";

if (isset($_GET['action']) && $_GET['action'] == 'deletePost') {
  if (isset($_GET['draft'])) {
    $table = 'drafts';
  }
  if (!isset($table)) {
    $table = 'posts';
  }
  if (!isset($_GET['id'])) {
    echo "Post does not exist";
  } else {
    $user_post = bind_and_get_result("SELECT * FROM {$table} WHERE id = ?", "s", $_GET['id']);
    $row = fetch_assoc($user_post);
    if($row['userid'] !== $_SESSION['id']) {
      echo "Cannot delete another user's post";
    } else {
      bind_and_execute_stmt("DELETE FROM {$table} WHERE id = ?", "s", $_GET['id']);
      echo 1;
    }
  }
}