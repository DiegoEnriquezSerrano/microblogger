<?php 

require_once "../../functions.php";

if (isset($_POST)) {
  
  $json = json_decode(file_get_contents('php://input'), true);
  $json['draft'] == false ? $info = ['posts', 'postid='] : $info = ['drafts', 'draftid='];
  $date = date('Y-m-d H:i:s');

  if (!isset($json['text'])) exit("Cannot create empty {$info[0]}");
  if (strlen($json['text']) > 69420) exit("Post is too long.");

  if (!isset($json['isRepost'])) {
    $json['isRepost'] = 0;
    $json['repostFromPostId'] = null;
  };

  if (isset($json['draftId']) && $json['draft'] == true) {
    bind_and_execute_stmt(
      "UPDATE drafts SET post = ?, datetime = ?
       WHERE id = ?", "sss",
      [ esc($json['text']), esc($date), esc($json['draftId']) ]
    );
  } else {
    bind_and_execute_stmt(
      "INSERT INTO {$info[0]} (`post`, `userid`, `datetime`, `is_repost`, `repost_from_post_id`)
       VALUES ( ?, ?, ?, ?, ?) ", "sssss",
      [ esc($json['text']), esc($_SESSION['id']), esc($date), $json['isRepost'], $json['repostFromPostId'] ]
    );
    $lastId = mysqli_insert_id($link);
    echo display_posts($info[1].$lastId);
    if (isset($json['draftId'])) {
      bind_and_execute_stmt(
        "DELETE FROM drafts
         WHERE id = ?
         AND userid = ?", "ss",
        [ esc($json['draftId']), esc($_SESSION['id']) ]
      );
    };
  };

};