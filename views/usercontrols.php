<?php

    include_once 'dbh.php';
    $id = $_SESSION['id'];

    $sql = "SELECT * FROM `users` WHERE id = '$id'";
    $result = mysqli_query($link, $sql);

    if(mysqli_num_rows($result) > 0) {

        while($row = mysqli_fetch_assoc($result)) {
            $rowId = $row['id'];
            $sqlImg = " SELECT * FROM profileimg WHERE userid = '$rowId'";
            $resultImg = mysqli_query($link, $sqlImg);

            while($rowImg = mysqli_fetch_assoc($resultImg)) {
                echo "<h4>".$row['username']."</h4><br>";
                echo "
        <div>";
                if($rowImg['status'] == 0) {
                    echo '
            <img src="uploads/profile'.$rowId.'.jpg?'.mt_rand().'"><br>';

                } else {
                    echo '
            <img src="uploads/profiledefault.jpg"><br>';
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
