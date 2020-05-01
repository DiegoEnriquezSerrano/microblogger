<?php

  include_once "../../functions.php";

  $messageThreadResult = bind_and_get_result(
   'SELECT id, message_thread_hash
    FROM message_threads
    WHERE message_thread_hash = ?
    LIMIT 1', 's', esc($_POST['message_thread_hash'])
  );

  if (mysqli_num_rows($messageThreadResult) < 1) {
    return 'This message thread does not exist';
    echo 2;
  } else {
    $messageThread = fetch_assoc($messageThreadResult);
    bind_and_execute_stmt('UPDATE message_threads SET updated_at = NOW() WHERE id = ?', 's', esc($messageThread['id']));
    bind_and_execute_stmt('INSERT INTO messages (message_thread_hash, user_id, message, sent_at, attachment) VALUES (?,?,?,NOW(),?)', 'ssss', [$messageThread['message_thread_hash'], $_SESSION['id'], $_POST['message_body'],null]);
    bind_and_execute_stmt('UPDATE message_recipients SET opened_since_last_message = 0 WHERE message_thread_hash = ?', 's', $messageThread['message_thread_hash']);
    bind_and_execute_stmt('UPDATE message_recipients SET opened_since_last_message = 1 WHERE message_thread_hash = ? and user_id = ?', 'ss', [$messageThread['message_thread_hash'], $_SESSION['id']]);
    echo 1;
  }