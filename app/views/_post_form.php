<?php

require_once "app/controllers/post_form_ctrl.php";

function display_post_box() {
  if(isset($_SESSION['id']) && $_SESSION['id'] > 0) {
    $post_box = <<<DELIMETER
      <div class="post_box">
        <div id="draft_slider">
          <span id="">Draft?</span>
          <label class="switch">
            <input id="draftActive" value="1" type="checkbox">
            <span class="slider round"></span>
          </label>
        </div><!--draft_slider-->
        <div id="post_pass_or_fail">
          <span class="success" id="postSuccess">Great success.</span>
          <span class="danger" id="postFail">Couldn't publish. Try again.</span>
        </div><!--post_pass_or_fail-->
        <button id="post_box_button">Post</button>
        <textarea id="post_box_textfield" placeholder="Tell the world how you feel!"></textarea>
      </div><!--postBox-->
DELIMETER;
    echo $post_box;
  }
}
display_post_box();