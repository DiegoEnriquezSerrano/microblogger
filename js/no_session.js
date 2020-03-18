(() => {_q('#authLoginActive').value = "1";})();

_q('#toggle_auth_box_login').onclick = () => {
  if(_q('#authLoginActive').value == "1") {
    _q('#authLoginActive').value = "0";
    _q('.auth_box_title_').innerHTML = "Sign up and join the conversation!";
    _q('#auth_box_submit_button').innerHTML = "Sign up";
    _q('#auth_username').style.display = "block";
    _q('#toggle_auth_box_login').innerHTML = "Login";
  } else {
    _q('#authLoginActive').value = "1";
    _q('.auth_box_title_').innerHTML = "Welcome back!";
    _q('#auth_box_submit_button').innerHTML = "Login";
    _q('#auth_username').style.display = "none";
    _q('#toggle_auth_box_login').innerHTML = "Sign up";
  }
}

_q('#auth_box_submit_button').onclick = () => {
  let ajax = new XMLHttpRequest(); 
  ajax.open("POST", homeDirectory + "actions.php?action=loginSignup");
  ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  ajax.onreadystatechange = () => { 
      if (ajax.readyState != 4 || ajax.status != 200) return; 
      ajax.responseText == 1 ? window.location.href = homeDirectory : _q("#loginAlert").innerHTML = ajax.responseText;
  };
  ajax.send(`email=${_q('#auth_email').value}&password=${_q('#auth_password').value}&username=${_q('#auth_username').value}&loginActive=${_q('#authLoginActive').value}`);
};