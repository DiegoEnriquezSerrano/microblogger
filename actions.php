<?php 

include_once 'config/dbh.php';
include("functions.php");

if(isset($_SESSION['id'])) {$key = $_SESSION['id'];}

if (isset($_GET['action']) && $_GET['action'] == "loginSignup") {
  $error = "";
  $credentials = array(
    'email' => esc($_POST['email']),
    'password' => password_hash(esc($_POST['password']), PASSWORD_DEFAULT),
    'username' => esc($_POST['username'])
  );

  if (!$credentials['email']) {
    $error = "An email address is required.";
  } else if (!$_POST['password']) {
    $error = "A password is required.";
  } else if (filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) === false) {
    $error = "Please enter a valid email address";
  }

  if ($error != "") { 
    echo "<p>{$error}</p>"; 
    exit();
  };

  if($_POST['loginActive'] == "0") {
    if (!$credentials['username']) {
      $error = "A user name is required.";
    } else {
      $email_check = bind_and_get_result("SELECT id FROM `users` WHERE email = ?", "s", $credentials['email']);
      if (mysqli_num_rows($email_check) > 0) {
        $error = "That email address is taken.";
      } else {
        $username_check = bind_and_get_result("SELECT id FROM `users` WHERE username = ?", "s", $credentials['username']);
        if (mysqli_num_rows($username_check) > 0) {
          $error = "That username is taken.";
        } else {
          bind_and_execute_stmt("INSERT INTO users (`email`, `password`, `username`) VALUES (?, ?, ?)", "sss", array_values($credentials));
          $_SESSION['id'] = mysqli_insert_id($link);
          if (isset($_POST['stayLoggedIn']) && $_POST['stayLoggedIn'] == '1') {
            setcookie("id", mysqli_insert_id($link), time() + 60*60*24*1);
          }
          $img_insert = bind_and_get_result("SELECT `id` FROM `users` WHERE email = ?", "s", $credentials['email']);
          if (mysqli_num_rows($img_insert) > 0) {
            while ($row = fetch_assoc($img_insert)) {
              $userid = $_SESSION['id'];
              bind_and_execute_stmt("INSERT INTO `profileimg` ( `userid`, `status`, `file_ext`) VALUES (?, 1, '.jpg')", "s", $userid);
              bind_and_execute_stmt("INSERT INTO `profiles` (`user_id`) VALUES (?)", "s", $userid);
              echo 1;
            }
          } else {
            echo "You have an error!";
          }
        }
      }
    }
  } else {
    $result = bind_and_get_result("SELECT * FROM `users` WHERE email = ?", "s", $credentials['email']);
    $row = fetch_array($result);
    if (isset($row)) {
      if (password_verify(esc($_POST['password']), $row['password'])) {
        $_SESSION['id'] = $row['id'];
          if (isset($_POST['stayLoggedIn']) && $_POST['stayLoggedIn'] == '1') {
            setcookie("id", $row['id'], time() + 60*60*24*1);
          }
        echo 1;
      } else {
        $error = "That email/password combination could not be found.";
      }
    } else {
      $error = "That email/password combination could not be found.";
    }
  }
}

if (isset($error) && $error != "") {
  echo '<p>'.$error.'</p>';
  exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'toggleFollow') {
  $result = bind_and_get_result("SELECT * FROM following_relations WHERE follower = ? AND is_following = ?", "ss", $new=array(esc($_SESSION['id']),esc($_POST['userid'])));
  if (mysqli_num_rows($result) > 0) {
    $row = fetch_assoc($result);
    bind_and_execute_stmt("DELETE FROM following_relations WHERE id = ?", "s", esc($row['id']));
    echo 1;
  } else {
    bind_and_execute_stmt("INSERT INTO following_relations (follower, is_following) VALUES ( ?, ?) ", "ss", $new=array(esc($_SESSION['id']),esc($_POST['userid'])));
    echo 2;
  }
}

if (isset($_GET['action']) && $_GET['action'] == 'createPost') {
  if (!$_POST['post_box_textfield']) {
    echo "Cannot create empty post.";
  } else if (strlen($_POST['post_box_textfield']) > 69420) {
    echo "Your post is too long.";
  } else {
    $date = date('Y-m-d H:i:s');
    bind_and_execute_stmt("INSERT INTO posts (`post`, `userid`, `datetime`, `is_repost`) VALUES ( ?, ?, ?, ?) ", "ssss", $new=array(esc($_POST['post_box_textfield']),esc($_SESSION['id']), esc($date), 0));
    echo 1;
  }
}

if (isset($_GET['action']) && $_GET['action'] == 'deletePost') {
  if (!isset($_GET['id'])) {
    echo "Post does not exist";
  } else {
    $user_post = bind_and_get_result("SELECT * FROM posts WHERE id = ?", "s", $_GET['id']);
    $row = fetch_assoc($user_post);
    if($row['userid'] !== $_SESSION['id']) {
      echo "Cannot delete another user's post";
    } else {
      bind_and_execute_stmt("DELETE FROM posts WHERE id = ?", "s", $_GET['id']);
      header("Location: index.php");
      echo "1";
    }
  }
}

if (isset($_GET['action']) && $_GET['action'] == 'relayPost') {
  if (!isset($_GET['id'])) {
    echo "Post does not exist";
  } else {
    $user_post = bind_and_get_result("SELECT * FROM posts WHERE id = ?", "s", $_GET['id']);
    $row = fetch_assoc($user_post);
    if($row['userid'] == $_SESSION['id']) {
      echo "Cannot relay your own post.";
    } else {
      $date = date('Y-m-d H:i:s');
      bind_and_execute_stmt("INSERT INTO posts (`post`, `userid`, `datetime`, `is_repost`, `repost_from_post_id`) VALUES ( ?, ?, ?, ?, ?)", "sssss", $new=array('', esc($_SESSION['id']), esc($date), 1, $_GET['id']));
      echo "1";
    }
  }
}

if (isset($_GET['action']) && $_GET['action'] == 'toggleLike') {
  $result = bind_and_get_result("SELECT * FROM liked_relations WHERE user = ? AND post_liked = ?", "ss", $new=array(esc($_SESSION['id']),esc($_POST['postid'])));
  if (mysqli_num_rows($result) > 0) {
    $row = fetch_assoc($result);
    bind_and_execute_stmt("DELETE FROM liked_relations WHERE id = ?", "s", esc($row['id']));
    echo 1;
  } else {
    bind_and_execute_stmt("INSERT INTO liked_relations (user, post_liked) VALUES ( ?, ?) ", "ss", $new=array(esc($_SESSION['id']),esc($_POST['postid'])));
    echo 2;
  }
}

if(isset($_POST['submit'])){
  $file = $_FILES['file'];
  $fileName = $_FILES['file']['name'];
  $fileTmpName = $_FILES['file']['tmp_name'];
  $fileSize = $_FILES['file']['size'];
  $fileError = $_FILES['file']['error'];
  $fileType = $_FILES['file']['type'];
  $fileExt = explode('.', $fileName);
  $fileActualExt = strtolower($fileExt[1]);
  $allowed = array('jpg', 'jpeg', 'png', 'gif');
  if(in_array($fileActualExt, $allowed)) {
    if($fileError === 0){
      if ($fileSize < 200000){
        $fileNameNew = "profile{$_SESSION['id']}.{$fileActualExt}";
        $fileDestination = "uploads/{$fileNameNew}";
        move_uploaded_file($fileTmpName, $fileDestination);
        $fileExtWithDot = ".{$fileActualExt}";
        $sql = "UPDATE profileimg SET status = 0, file_ext ='{$fileExtWithDot}' WHERE userid =".$_SESSION['id'];
        $result = query($sql);
        header("Location: index.php");
      } else {
        echo "Your file is too large.";
      }
    } else {
      echo "There was an error uploading your file.";
    }
  } else {
    echo "You cannot upload files of this type.";
  }
}

?>