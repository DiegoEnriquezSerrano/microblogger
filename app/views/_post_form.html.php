<?php

require_once "app/controllers/post_form_ctrl.php";

function postForm() {
  global $paths;
  global $draftText;
  global $originalPost;
  
  $paths[0] === 'draft' ? $heading = 'Draft' : $heading = 'New post';

  if ($originalPost != '') {
  $quoted = <<<DELIMETER
    <div class="smallPost" data-postid="{$originalPost->postId}">
      <div class="smallPostHeader">
        <a class="smallAvatar">
          <div class="user_img" style="background-image: url('{$originalPost->postAvatar}.jpg')"></div>
        </a>
        <p class="smallPostUserDetail">
          <a class="userlink">{$originalPost->postUser}</a><br>
          @{$originalPost->postUserName}
        </p><!--smallPostUserDetail-->
      </div><!--smallPostHeader-->
      <p class="smallPostContent">
        {$originalPost->postText}
      </p>
    </div><!--smallPost-->
DELIMETER;
  } else { 
    $quoted = '';
  };

  if (isset($_SESSION['id']) && $_SESSION['id'] > 0) {
    $postForm = <<<DELIMETER
      <div class="postForm">
        <h3>{$heading}</h3>
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
        {$quoted}
        <div id="postActions">
          <button id="postSubmit">Post</button>
          <button id="postPreview">Preview</button>
        </div><!--postActions-->
      </div><!--postForm-->
DELIMETER;
    return $postForm;
  };
};