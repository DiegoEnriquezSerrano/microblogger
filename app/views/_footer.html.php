<?php

$scriptsArray = concatArrayValuesAsString($scripts);
$renderFooter = <<<DELIMETER
  </div><!--'#homeboy'-->

  <div class="mod" id="loginModal" aria-labelledby="loginModal" aria-hidden="true">
    <div class="mod_box">
      <div class="mod_head">
        <h5 class="modal_title_" id="m1">Welcome back!</h5>
      </div><!--'.mod_head'-->
      <hr>
      <div class="mod_bod">
        <div id="loginAlert">{$errorStringCheck}</div>
        <form id="">
          <input type="hidden" id="loginActive" name="loginActive" value="1">
          <input type="email" id="email" placeholder="Email address">
          <input type="password" id="password" placeholder="Password">
          <input type="text" id="username" placeholder="Username">
        </form>
      </div><!--'.mod_bod'-->
      <hr>
      <div class="mod_foot">
        <p><a id="toggleLogin" value="">Sign up</a></p>
        <p><button id="loginSignupButton">Login</button></p>
        <p><a id="loginModalClose">Close</a></p>
      </div><!--'.mod_foot'-->
    </div><!--'.mod_box'-->
  </div><!--'#loginModal'-->

  {$sourceScript}
  {$scriptsArray}
  
</body>
</html>
DELIMETER;
echo $renderFooter;