<?php

require_once "../../functions.php";

if(isset($_POST)) {
  $json = json_decode(file_get_contents('php://input'), true);
  if (isset($json['user']) && ($user = $json['user']) == true) {
    bind_and_execute_stmt(
      'UPDATE profiles, users
       SET profiles.user_bio = ?,
           profiles.user_display_name = ?,
           users.username = ?
       WHERE profiles.user_id = users.id
       AND profiles.user_id = ?', 'ssss',
       [$user['user_bio'], $user['user_display_name'], $user['user_name'], $_SESSION['id']]
    );
  echo json_encode($user);
  };
};