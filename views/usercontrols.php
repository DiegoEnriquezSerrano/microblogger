<?php

  include_once 'dbh.php';

  $id = esc($_SESSION['id']);
  $result = bind_and_get_result("SELECT * FROM users WHERE id = ?", "s", $id);

  if(mysqli_num_rows($result) > 0) {

    while($row = fetch_assoc($result)) {
      $rowId = esc($row['id']);
      $sqlImg = bind_and_get_result("SELECT * FROM profileimg WHERE userid = ?", "s", $rowId);

      while($rowImg = fetch_assoc($sqlImg)) {
        echo "<h4>{$row['username']}</h4><br>";
        echo "
  <div>";
        if($rowImg['status'] == 0) {
          echo "
      <img src='uploads/profile{$rowId}{$rowImg['file_ext']}?".mt_rand()."'><br>";
        } else {
          echo '
      <img src="rsc/profiledefault.jpg"><br>';
        }  
        echo "
  </div>";
      }
    }
  } else {
    echo "There are no users yet.";
  }

?>

    <div class="image_upload_box">
      <form action="actions.php" method="POST" enctype="multipart/form-data">
      <input type="file" name="file" value="" class="active">
      <button type="submit" name="submit">UPLOAD</button>
      </form>
    </div>