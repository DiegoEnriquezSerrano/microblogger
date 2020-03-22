<?php

function display_landing_page(){
  $landingPageTop = <<<DELIMETER
      <div id="auth_box">
        <div id="auth_box_head">
          <h5 class="auth_box_title_" id="m1">Welcome back!</h5>
        </div><!--'auth_box_head'-->
        <div id="auth_box_bod">
          <div id="loginAlert"></div>
          <div id="login-signup-grid-box">
            <form id="">
              <input type="hidden" id="authLoginActive" name="loginActive" value="1">
              <input type="email" id="auth_email" placeholder="Email address">
              <input type="password" id="auth_password" placeholder="Password">
              <input type="text" id="auth_username" placeholder="Username">
            </form>
          </div><!--login-signup-grid-box-->
        </div><!--'auth_box_bod'-->
        <div id="auth_box_foot">
          <button id="auth_box_submit_button">Login</button>
          <p>New user? <a id="toggle_auth_box_login" value="">Create an account.</a></p>
          <p><a id="auth_box_close">Forgot password?</a></p>
        </div><!--'auth_box_foot'-->
      </div><!--'auth_box'-->
DELIMETER;
  echo $landingPageTop;
}

?>
    <div id="homeModule">
      <div id="theLinkContainer">
        <a id="theLink" href="<?php echo HOME_DIRECTORY ?>">
          <span class="first">o</span>
          <span class="second">yea<span class="the_H">h?</span></span>
        </a>
      </div><!--theLink-->

<?php 
if(isset($_SESSION['id']) && $_SESSION['id'] > 0) { url(HOME_DIRECTORY); } else { display_landing_page(); }; ?>

    </div><!--'#homeModule'-->
    <div id="postsModule">
      <div id="postContainer">
        <h2>Recent post</h2>
<?php display_posts('random'); ?>

      </div><!--'#postContainer'-->
    </div><!--'#postsModule'-->
    <div id="sectionsModule">
      <div id="sectionsContainer">
      <?php
  include_once "app/views/_nav_panel.php";
  displaySections(); ?>

      </div><!--sectionContainer-->
    </div><!--sections-->
