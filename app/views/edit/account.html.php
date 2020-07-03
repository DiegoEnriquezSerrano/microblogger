<?php echo $navlist; ?>

    <div id="editModule">

<?php
$editModule = <<<DELIMETER
<div id="edit_container">
  <form id="edit_form" method="post">
    <label for="email" class="label one">Email</label>
    <input class="input one" name="email" value="{$userEmail}" type="email" required>
    <label for="oldPassword" class="label two">Password</label>
    <input class="input two" name="oldPassword" type="password" value="">
    <label for="newPassword" class="label three">New Password</label>
    <input class="input two" name="newPassword" type="password" value="">
    <label for="newPasswordConfirm" class="label three">Re-type New Password</label>
    <input class="input two" name="newPasswordConfirm" type="password" value="">
    <hr>
    <button class="button" type="submit" value="Submit" id="editFormSubmit">Save</button>
  </form>
</div><!--edit_container-->
DELIMETER;
echo $editModule; ?>

    </div><!--editModule-->
    <div id="sectionsModule">
      <div id="sectionsContainer">

<?php echo $sections; ?>

      </div><!--sectionContainer-->
    </div><!--sectionsModule-->