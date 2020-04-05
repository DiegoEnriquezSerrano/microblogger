<?php

$styles = [$homeStyles];
$scripts = [$mainScript];

$getThreadsResult = bind_and_get_result(
 'SELECT
    DISTINCT(mt.id),
    (SELECT
      user_id
    FROM messages
    WHERE message_thread_hash = mt.message_thread_hash
    ORDER BY id DESC
    LIMIT 1) AS sender,
    (SELECT
      message
    FROM messages
    WHERE message_thread_hash = mt.message_thread_hash
    ORDER BY id DESC
    LIMIT 1) AS message
  FROM message_threads mt
  INNER JOIN message_recipients mr ON mt.message_thread_hash = mr.message_thread_hash
  WHERE mt.message_thread_hash IN (
    SELECT message_thread_hash
    FROM message_recipients
    WHERE user_id = ?                   
  )
  ORDER BY mt.updated_at DESC' , 'i', $_SESSION['id']
);

if (mysqli_num_rows($getThreadsResult) < 1) {
  echo 'no results';
} else {
  while($threads = fetch_assoc($getThreadsResult)) {
    echo $threads['sender'].': '.$threads['message'].'<br>';
  }
  
}