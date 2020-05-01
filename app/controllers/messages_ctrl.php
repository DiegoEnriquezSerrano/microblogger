<?php

function displayMessages(){
  global $homeDirectory;
  $getThreadsResult = bind_and_get_result(
  'SELECT
    DISTINCT(mt.id) AS message_thread_id,
    (SELECT COALESCE(profiles.user_display_name, users.username)
    FROM messages
    INNER JOIN users on messages.user_id = users.id
    INNER JOIN profiles on messages.user_id = profiles.user_id
    WHERE message_thread_hash = mt.message_thread_hash
    ORDER BY messages.id DESC LIMIT 1) AS sender,
    (SELECT message
    FROM messages
    WHERE message_thread_hash = mt.message_thread_hash
    ORDER BY messages.id DESC LIMIT 1) AS message,
    mt.message_thread_hash AS message_thread_hash
  FROM message_threads mt
  INNER JOIN message_recipients mr ON mt.message_thread_hash = mr.message_thread_hash
  WHERE mt.message_thread_hash IN (
  SELECT message_thread_hash
  FROM message_recipients
  WHERE user_id = ? )
  ORDER BY mt.updated_at DESC' , 'i', $_SESSION['id']
  );

  if (mysqli_num_rows($getThreadsResult) < 1) {
    echo 'No messages :/';
  } else {
    while($threads = fetch_assoc($getThreadsResult)) {

      $messageParticipantsResult = bind_and_get_result(
        'SELECT COALESCE(p.user_display_name, u.username) AS participants
        FROM message_recipients mr
        INNER JOIN users u ON mr.user_id = u.id
        INNER JOIN profiles p ON u.id = p.user_id
        WHERE mr.message_thread_hash = (
          SELECT message_thread_hash
          FROM message_threads
          WHERE id = ?
        )
        AND mr.user_id != ?', 'ss', [$threads['message_thread_id'], $_SESSION['id']]
      );

      $participants = '';
      while($messageParticipants = fetch_assoc($messageParticipantsResult)) {
        if ($participants === '') {
          $participants = $messageParticipants['participants'];
        } else {
          $participants .= ', '.$messageParticipants['participants'];
        }
      }

      $threads['participants'] = $participants;
      
      $messageThread = <<<DELIMETER
    <a href="{$homeDirectory}inbox/thread/{$threads['message_thread_hash']}">
      <div class="message_row">
        <div class="thread_participants">
          {$threads['participants']}
        </div><!--thread_participants-->
        <div class="last_sender">
          {$threads['sender']}
        </div><!--last_sender-->
        <div class="last_message">
          {$threads['message']}
        </div><!--last_message-->
      </div><!--message_row-->
    </a>

DELIMETER;
      echo $messageThread;
    }
  }
}

function display_message_list(){
  $messageListResults = bind_and_get_result(
  'SELECT
    (SELECT COALESCE(profiles.user_display_name, users2.username)
    FROM users users2
    INNER JOIN profiles ON users2.id = profiles.user_id
    WHERE users2.id = users.id) as message_candidate_username, 
    users.id AS message_candidate_id
  FROM users
  WHERE users.id IN (
    SELECT follower
    FROM following_relations
    WHERE is_following = ?
    UNION
    SELECT is_following
    FROM following_relations
    WHERE follower = ?)', 'ss', $new=array($_SESSION['id'], $_SESSION['id'])
  );
  if (mysqli_num_rows($messageListResults) < 1) {
    echo 'No messages :/';
  } else {
    while($messageList = fetch_assoc($messageListResults)) {
      $messageList = <<<DELIMETER
    <div class="message_row" data-userid="{$messageList['message_candidate_id']}">
      <div class="last_sender">
        {$messageList['message_candidate_username']}
      </div><!--last_sender-->
      <div class="last_message">
        
      </div><!--last_message-->
    </div><!--message_row-->

DELIMETER;
      echo $messageList;
    }
  }
}

function displayThread(){
  global $paths;
  $messages = '';
  if (isset($paths[1]) && $paths[1] == 'thread') {
    $threadResult = bind_and_get_result(
      'SELECT 
        COALESCE(p.user_display_name, u.username) AS user,
        m.message,
        m.sent_at,
        m.attachment
      FROM messages m
      INNER JOIN users u ON m.user_id = u.id
      INNER JOIN profiles p ON m.user_id = p.user_id
      WHERE m.message_thread_hash = ?
      ORDER BY m.sent_at ASC', 's', esc($paths[2])
    );

    if (mysqli_num_rows($threadResult) < 1) {
      echo "Thread doesn't exist";
    } else {

      while ($threadMessage = fetch_assoc($threadResult)) {
        $message = <<<DELIMETER
        <div class="message">
          <div>{$threadMessage['user']}:</div>
          <div>{$threadMessage['message']}</div>
          <div>Sent: {$threadMessage['sent_at']}</div>
        </div><!--message-->

  DELIMETER;
        $messages .= $message;
      }
    }
  }
  return $messages;
}

require_once "app/views/_nav_panel.html.php";

$styles = [$homeStyles, $messagesStyles];
$scripts = [$mainScript, $messagesScript];
$sections = displaySections();
$thread = displayThread();