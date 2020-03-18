<?php

require_once("../edit/router.php");
display_header_and_styles($style);

?>

    <div id="headerModule">
      <div id="img_container">
        <img src="">
      </div><!--img_container-->
      <div id="header_nav">
      </div><!--header_nav-->
    </div><!--headerModule-->
    <div id="homeModule">

<?php 
// print_r($user); 
  
echo $userModule;?>

      <div class="image_upload_box">
        <form action="actions.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="file" value="" class="active">
        <button type="submit" name="submit">UPLOAD</button>
        </form>
      </div><!--image_upload_box-->

    </div><!--'#homeModule'-->
    <div id="editModule">

<?php echo $editModule; ?>  

    </div><!--'#editModule'-->
    <div id="sectionsModule">
      <div id="sectionsContainer">
<?php displaySections(); ?>

      </div><!--sectionContainer-->
    </div><!--sectionsModule-->

<?php include("../views/footer.php"); ?>