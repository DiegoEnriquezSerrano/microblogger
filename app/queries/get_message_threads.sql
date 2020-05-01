SELECT
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
  ORDER BY messages.id DESC LIMIT 1) AS message
FROM message_threads mt
INNER JOIN message_recipients mr ON mt.message_thread_hash = mr.message_thread_hash
WHERE mt.message_thread_hash IN (
SELECT message_thread_hash
FROM message_recipients
WHERE user_id = ? )
ORDER BY mt.updated_at DESC