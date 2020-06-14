<?php

  include_once "../../functions.php";

  $date = date('Y-m-d H:i:s');

  $messageThreadResult = bind_and_get_result(
   'SELECT id, message_thread_hash
    FROM message_threads
    WHERE message_thread_hash = ?
    LIMIT 1', 's', esc($_POST['message_thread_hash'])
  );

  if (mysqli_num_rows($messageThreadResult) < 1) {
    return 'This message thread does not exist';
  } else {
    $messageThread = fetch_assoc($messageThreadResult);

    bind_and_execute_stmt(
      'UPDATE message_threads
       SET updated_at = ?
       WHERE id = ?', 'ss', [$date, esc($messageThread['id'])]);
    bind_and_execute_stmt(
      'INSERT INTO messages (message_thread_hash, user_id, message, sent_at, attachment)
       VALUES (?,?,?,?,?)', 'sssss',
      [$messageThread['message_thread_hash'], $_SESSION['id'], $_POST['message_body'], $date, null]);
    $newMesssageId = mysqli_insert_id($link);
    bind_and_execute_stmt(
      'UPDATE message_recipients
       SET opened_since_last_message = 0
       WHERE message_thread_hash = ?', 's',
      $messageThread['message_thread_hash']);
    bind_and_execute_stmt(
      'UPDATE message_recipients
       SET opened_since_last_message = 1
       WHERE message_thread_hash = ?
       AND user_id = ?', 'ss',
      [$messageThread['message_thread_hash'], $_SESSION['id']]);

    $newMessageResult = bind_and_get_result('SELECT * from messages WHERE id = ?', 's', $newMesssageId);
    $newMessage = fetch_assoc($newMessageResult);
    $newMessageTime = timeSinceDateTime($newMessage['sent_at']);

    $message = <<<DELIMETER
    <div class="threadRow mine">
      <div class="guestAvatar"></div>
      <div class="messageSection">
        <div class="message">
          <div>{$newMessage['message']}</div>
        </div><!--message-->
        <div class="messageDetail">{$newMessageTime} ago</div>
      </div><!--messageSection-->
    </div><!--threadRow-->
DELIMETER;
    echo $message;
  }