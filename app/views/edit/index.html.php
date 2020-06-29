
    <div id="editModule">
<?php echo $navlist; ?>

<?php
$editModule = <<<DELIMETER
<div id="edit_container">
  <div class="user_img" style="background-image: url('$userImage')">
    <form enctype="multipart/form-data" method="post">
      <div class="row">
        <input type="file" name="filesToUpload" id="filesToUpload" multiple="multiple" />
        <label for="filesToUpload">
          <svg width="15px" height="15px" viewBox="0 0 21 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <title>edit</title>
            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
              <g id="Dribbble-Dark-Preview" transform="translate(-99.000000, -400.000000)" fill="#fff">
                <g id="icons" transform="translate(56.000000, 160.000000)">
                  <path d="M61.9,258.010643 L45.1,258.010643 L45.1,242.095788 L53.5,242.095788 L53.5,240.106431 L43,240.106431 L43,260 L64,260 L64,250.053215 L61.9,250.053215 L61.9,258.010643 Z M49.3,249.949769 L59.63095,240 L64,244.114985 L53.3341,254.031929 L49.3,254.031929 L49.3,249.949769 Z" id="edit-[#1479]"></path>
                </g>
              </g>
            </g>
          </svg>
        </label>
      </div>
    </form>
  </div>

  <form id="edit_form" method="post">
    <label for="username" class="label one">Username</label>
    <input class="input one" name="username" value="{$userName}" required>
    <label for="displayname" class="label two">Display name</label>
    <input class="input two" name="displayname" value="{$userDisplayName}">
    <label for="bio" class="label three">Bio</label>
    <textarea name="bio" class="input three">{$userBio}</textarea>
    <hr>
    <button class="button" type="submit" value="Submit" id="editFormSubmit">Save</button>
  </form>
</div><!--edit_container-->
DELIMETER;
echo $editModule; ?>

    </div><!--'#editModule'-->
    <div id="sectionsModule">
      <div id="sectionsContainer">

<?php echo $sections; ?>

      </div><!--sectionContainer-->
    </div><!--sectionsModule-->