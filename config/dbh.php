<?php 

$link = mysqli_connect("localhost", "root", "", "microblogger"); 

if (mysqli_connect_errno()) {
  print_r(mysqli_connect_error());
  exit();
}
