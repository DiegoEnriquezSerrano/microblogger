<?php

require_once "../../functions.php";

if(isset($_SESSION['id'])) {$key = $_SESSION['id'];}

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

if (isset($error) && $error != "") {
  echo '<p>'.$error.'</p>';
  exit();
}