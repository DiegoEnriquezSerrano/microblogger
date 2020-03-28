<?php

  include_once "../../functions.php";

  $result = bind_and_get_result("SELECT * FROM following_relations WHERE follower = ? AND is_following = ?", "ss", $new=array(esc($_SESSION['id']),esc($_POST['userid'])));
  if (mysqli_num_rows($result) > 0) {
    $row = fetch_assoc($result);
    bind_and_execute_stmt("DELETE FROM following_relations WHERE id = ?", "s", esc($row['id']));
    echo 1;
  } else {
    bind_and_execute_stmt("INSERT INTO following_relations (follower, is_following) VALUES ( ?, ?) ", "ss", $new=array(esc($_SESSION['id']),esc($_POST['userid'])));
    echo 2;
  }