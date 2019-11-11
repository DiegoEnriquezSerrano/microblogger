<?php 

  include_once 'dbh.php';
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
      $errorString = "<p>".$error."</p>";
      echo $errorString; 
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
          bind_and_execute_stmt("INSERT INTO users (`email`, `password`, `username`) VALUES (?, ?, ?)", "sss", array_values($credentials));
          $_SESSION['id'] = mysqli_insert_id($link);
          if (isset($_POST['stayLoggedIn']) && $_POST['stayLoggedIn'] == '1') {
            setcookie("id", mysqli_insert_id($link), time() + 60*60*24*1);
          }
          $img_insert = bind_and_get_result("SELECT * FROM `users` WHERE email = ?", "s", $credentials['email']);
          if (mysqli_num_rows($img_insert) > 0) {
            while ($row = mysqli_fetch_assoc($img_insert)) {
              $userid = $row['id'];
              bind_and_execute_stmt("INSERT INTO `profileimg` ( `userid`, `status`) VALUES (?, 1);", "s", $userid);
              echo 1;
            }
          } else {
            echo "You have an error!";
          }
        }
      }
    } else {
      $result = bind_and_get_result("SELECT * FROM `users` WHERE email = ?", "s", $credentials['email']);
      $row = mysqli_fetch_array($result);
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
    $errorString = '<p>'.$error.'</p>';
    echo $errorString;
    exit();
  }

  if (isset($_GET['action']) && $_GET['action'] == 'toggleFollow') {
    $result = bind_and_get_result("SELECT * FROM following_relations WHERE follower = ? AND is_following = ?", "ss", $new=array(esc($_SESSION['id']),esc($_POST['userid'])));
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      bind_and_execute_stmt("DELETE FROM following_relations WHERE id = ?", "s", esc($row['id']));
      echo "1";
    } else {
      bind_and_execute_stmt("INSERT INTO following_relations (follower, is_following) VALUES ( ?, ?) ", "ss", $new=array(esc($_SESSION['id']),esc($_POST['userid'])));
      echo "2";
    }
  }

  if (isset($_GET['action']) && $_GET['action'] == 'createPost') {
    if (!$_POST['postTextfield']) {
      echo "Your tweet could not be posted.";
    } else if (strlen($_POST['postTextfield']) > 69420) {
      echo "Your tweet is too long.";
    } else {
      $date = date('Y-m-d H:i:s');
      bind_and_execute_stmt("INSERT INTO posts (`post`, `userid`, `datetime`) VALUES ( ?, ?, ?) ", "sss", $new=array(esc($_POST['postTextfield']),esc($_SESSION['id']), esc($date)));
      echo "1";
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
          $fileNameNew = "profile".$_SESSION['id'].".".$fileActualExt;
          $fileDestination = 'uploads/'.$fileNameNew;
          move_uploaded_file($fileTmpName, $fileDestination);
          $sql = "UPDATE profileimg SET status = 0 WHERE userid =".$_SESSION['id'];
          $result = mysqli_query($link, $sql);
          header("Location: index.php?uploadsuccess");
        } else {
          echo "Your file is too big.";
        }
      } else {
        echo "There was an error uploading your file.";
      }
    } else {
      echo "You cannot upload files of this type.";
    }
  }

?>