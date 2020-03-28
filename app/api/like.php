<?php

require_once "../../functions.php";


$result = bind_and_get_result("SELECT * FROM liked_relations WHERE user = ? AND post_liked = ?", "ss", $new=array(esc($_SESSION['id']),esc($_POST['postid'])));
if (mysqli_num_rows($result) > 0) {
  $row = fetch_assoc($result);
  bind_and_execute_stmt("DELETE FROM liked_relations WHERE id = ?", "s", esc($row['id']));
  echo 1;
} else {
  bind_and_execute_stmt("INSERT INTO liked_relations (user, post_liked) VALUES ( ?, ?) ", "ss", $new=array(esc($_SESSION['id']),esc($_POST['postid'])));
  echo 2;
}