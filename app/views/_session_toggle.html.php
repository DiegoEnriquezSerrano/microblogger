<div id="loginModalButtonHolder">
<?php if (isset($_SESSION['id'])) { ?>
      <a class="btn btn-outline-success my-2 my-sm-0" href="?function=logout">Logout</a>
<?php } else { ?>
      <button id="loginModalButton" class="loginModalButtons">Login/Sign&nbsp;Up</button>
<?php } ?>
</div><!--#loginModalButtonHolder-->