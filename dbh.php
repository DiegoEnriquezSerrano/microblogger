<?php 

$link = mysqli_connect("localhost", "root", "", "microblogger"); 

if (mysqli_connect_errno()) {
  print_r(mysqli_connect_error());
  exit();
}

defined("HOME_DIRECTORY") ? null : define("HOME_DIRECTORY", "http://localhost:8080/microblogger/");
defined("USER_DIRECTORY") ? null : define("USER_DIRECTORY", "http://localhost:8080/microblogger/user/");
defined("POST_DIRECTORY") ? null : define("POST_DIRECTORY", "http://localhost:8080/microblogger/post/");
defined("EDIT_DIRECTORY") ? null : define("EDIT_DIRECTORY", "http://localhost:8080/microblogger/edit/");
defined("UPLOAD_DIRECTORY") ? null : define("UPLOAD_DIRECTORY", "http://localhost:8080/microblogger/uploads/");

?>
