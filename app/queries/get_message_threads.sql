SELECT
  DISTINCT(mt.id),
  (SELECT 
    message
  FROM messages
  WHERE message_thread_hash = mt.message_thread_hash
  ORDER BY id DESC 
  LIMIT 1) 
FROM message_threads mt
INNER JOIN message_recipients mr ON mt.message_thread_hash = mr.message_thread_hash
WHERE mt.message_thread_hash IN ( 
  SELECT message_thread_hash
  FROM message_recipients
  WHERE user_id = ? 
  )
ORDER BY mt.updated_at DESC