<?php 

require_once "../../functions.php";

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