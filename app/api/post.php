<?php 

require_once "../../functions.php";

if (isset($_GET['action']) && $_GET['action'] == 'createPost') {
  if (!$_POST['post_box_textfield']) {
    echo "Cannot create empty post.";
  } else if (strlen($_POST['post_box_textfield']) > 69420) {
    echo "Your post is too long.";
  } else {
    $date = date('Y-m-d H:i:s');
    bind_and_execute_stmt("INSERT INTO posts (`post`, `userid`, `datetime`, `is_repost`) VALUES ( ?, ?, ?, ?) ", "ssss", $new=array(esc($_POST['post_box_textfield']),esc($_SESSION['id']), esc($date), 0));
    $lastPostId = mysqli_insert_id($link);
    echo display_posts('postid='.$lastPostId);
    if (isset($_GET['fromDraft'])) {
      bind_and_execute_stmt("DELETE FROM drafts WHERE id = ? AND userid = ?", "ss", [esc($_GET['fromDraft']),esc($_SESSION['id'])]);
    }
  }
}

if (isset($_GET['action']) && $_GET['action'] == 'createDraft') {
  if (!$_POST['post_box_textfield']) {
    echo "Cannot create empty draft.";
  } else if (strlen($_POST['post_box_textfield']) > 69420) {
    echo "Your draft is too long.";
  } else {
    $date = date('Y-m-d H:i:s');
    bind_and_execute_stmt("INSERT INTO drafts (`post`, `userid`, `datetime`, `is_repost`) VALUES ( ?, ?, ?, ?) ", "ssss", $new=array(esc($_POST['post_box_textfield']),esc($_SESSION['id']), esc($date), 0));
    $lastDraftId = mysqli_insert_id($link);
    echo display_posts('draftid='.$lastDraftId);
    // $lastDraftResult = bind_and_get_result("SELECT * FROM drafts WHERE id = ?", "s", $lastDraftId);
    // $lastDrafttRow = fetch_assoc($lastDraftResult);
    // echo json_encode([
    //   'post_id' => $lastPostRow['id'], 
    //   'user_id' => $lastPostRow['userid'],
    //   'post_body' => $lastPostRow['post'],
    //   'datetime' => $lastPostRow['datetime'],
    //   'is_relay' => $lastPostRow['is_repost'],
    //   'post_relayed' => $lastPostRow['repost_from_post_id']
    //   ]);
  }
}

if (isset($_GET['action']) && $_GET['action'] == 'editDraft') {
  if (!$_POST['post_box_textfield']) {
    echo "Cannot create empty draft.";
  } else if (strlen($_POST['post_box_textfield']) > 69420) {
    echo "Your draft is too long.";
  } else {
    $date = date('Y-m-d H:i:s');
    bind_and_execute_stmt("UPDATE drafts SET post = ?, datetime = ? WHERE id = ?", "sss", $new=array(esc($_POST['post_box_textfield']), esc($date), esc($_GET['id'])));
  }
}