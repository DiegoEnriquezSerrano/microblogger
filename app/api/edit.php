<?php

require_once "../../functions.php";

if(isset($_POST)) {
  $json = json_decode(file_get_contents('php://input'), true);

  if (isset($json['user']) && ($user = $json['user']) == true) {
    if ($user['user_name'] == null) exit(json_encode(['type' => 'error', 'message' => 'Must include a username.']));
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

  if (isset($json['account']) && ($user = $json['account']) == true) {
    $accountResult = bind_and_get_result(
      'SELECT
        id,
        email,
        password
       FROM users
       WHERE id = ?', 's',
      $_SESSION['id']
    );
    $account = fetch_assoc($accountResult);
    
    if ($user['old_password'] != null) {
      if (!password_verify($user['old_password'],$account['password']))
        exit(json_encode(['type' => 'error', 'message' => 'Incorrect password.']));
    };

    if ($user['new_password'] != null || $user['new_password_confirm'] != null) {
      if ($user['old_password'] == null)
        exit(json_encode(['type' => 'error', 'message' => 'Must enter current password.']));
      if ($user['new_password'] !== $user['new_password_confirm'])
        exit(json_encode(['type' => 'error', 'message' => 'New passwords must match.']));
      if (strlen($user['new_password']) < 12)
        exit(json_encode(['type' => 'error', 'message' => 'New password must be at least 13 characters long.']));
    };

    if ($user['email'] !== $account['email']) {
      if ($user['old_password'] == null)
        exit(json_encode(['type' => 'error', 'message' => 'Must enter current password.']));
      if (!password_verify($user['old_password'],$account['password']))
        exit(json_encode(['type' => 'error', 'message' => 'Incorrect password.']));
      
      if ($user['new_password'] == null && $user['new_password_confirm'] == null) {
        bind_and_execute_stmt('UPDATE users SET email = ? WHERE id = ?', 'ss', [$user['email'], $_SESSION['id']]);
        echo json_encode(['type' => 'success', 'message' => 'Email successfully updated.']);
      } else {
        bind_and_execute_stmt(
          'UPDATE users
           SET email = ?, password = ?
           WHERE id = ?' , 'sss', 
           [$user['email'], password_hash($user['new_password'], PASSWORD_DEFAULT), $_SESSION['id']]
        );
        echo json_encode(['type' => 'success', 'message' => 'Account successfully updated.']);
      };
    } else {
      if (!password_verify($user['old_password'],$account['password']))
        exit(json_encode(['type' => 'error', 'message' => 'Incorrect password.']));

      bind_and_execute_stmt('UPDATE users SET password = ? WHERE id = ?' , 'ss', 
         [password_hash($user['new_password'], PASSWORD_DEFAULT), $_SESSION['id']]
      );
      echo json_encode(['type' => 'success', 'message' => 'Password successfully updated.']);
    };
  };
};