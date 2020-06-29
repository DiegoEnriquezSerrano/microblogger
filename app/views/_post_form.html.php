<?php

require_once "app/controllers/post_form_ctrl.php";

function postForm() {
  global $draftText;
  if(isset($_SESSION['id']) && $_SESSION['id'] > 0) {
    $postForm = <<<DELIMETER
      <div class="postForm">
        <h3>New Post</h3>
        <div class="draftSlider">
          <span id="">Draft?</span>
          <label class="switch">
            <input class="draftActive" value="1" type="checkbox">
            <span class="slider round"></span>
          </label>
        </div><!--draftSlider-->
        <div id="postFeedback">
          <span class="success" id="postSuccess">Great success.</span>
          <span class="danger" id="postFail">Couldn't publish. Try again.</span>
        </div><!--postFeedback-->
        <textarea id="postBody" placeholder="Tell the world how you feel!">{$draftText}</textarea>
        <div id="postActions">
          <button id="postSubmit">Post</button>
          <button id="postPreview">Preview</button>
        </div><!--postActions-->
      </div><!--postForm-->
DELIMETER;
    return $postForm;
  }
}