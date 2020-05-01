<?php

  include_once "../../functions.php";

  $recipientUsers = explode(',', esc($_POST['recipient_ids']));
  $recipientUsers[] = esc($_SESSION['id']);
  asort($recipientUsers);
  $messageUsers = implode(',',$recipientUsers);
  $usershashed = hash('sha256',$messageUsers, false); 
  $doesThreadExistResult = bind_and_get_result('SELECT * FROM message_threads WHERE message_thread_hash = ?', 's', $usershashed);

  if (mysqli_num_rows($doesThreadExistResult) < 1) {
    bind_and_execute_stmt('INSERT INTO message_threads (message_thread_hash, updated_at) VALUES (?, NOW())', 's', $usershashed);
    foreach( explode(',',$messageUsers) as $id) {
      bind_and_execute_stmt('INSERT INTO message_recipients (message_thread_hash, user_id, opened_since_last_message) VALUES (?,?,0)', 'ss', [$usershashed,$id]);
    }
    bind_and_execute_stmt('INSERT INTO messages (message_thread_hash, user_id, message, sent_at, attachment) VALUES (?,?,?,NOW(),?)', 'ssss', [$usershashed, $_SESSION['id'], $_POST['message_body'],null]);
    bind_and_execute_stmt('UPDATE message_recipients SET opened_since_last_message = 1 WHERE message_thread_hash = ? and user_id = ?', 'ss', [$usershashed, $_SESSION['id']]);
    echo 1;
  } else {
    while($testRows = fetch_assoc($doesThreadExistResult)) {
      $message_thread_hash = $testRows['message_thread_hash'];
      bind_and_execute_stmt('INSERT INTO messages (message_thread_hash, user_id, message, sent_at, attachment) VALUES (?,?,?,NOW(),?)', 'ssss', [$message_thread_hash, $_SESSION['id'], $_POST['message_body'],null]);
      bind_and_execute_stmt('UPDATE message_recipients SET opened_since_last_message = 0 WHERE message_thread_hash = ?', 's', [$usershashed]);
      bind_and_execute_stmt('UPDATE message_recipients SET opened_since_last_message = 1 WHERE message_thread_hash = ? and user_id = ?', 'ss', [$usershashed, $_SESSION['id']]);
      bind_and_execute_stmt('UPDATE message_threads SET updated_at = NOW() WHERE message_thread_hash = ?', 's', $message_thread_hash);
      echo 1;
    }
  }