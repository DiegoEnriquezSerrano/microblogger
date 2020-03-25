const _d = document;
const _q = (query) => {return _d.querySelector(query);}
const _qs = (query) => {return Array.from(_d.querySelectorAll(query));}

const homeDirectory = 'http://localhost/microblogger/';
const modal = _q('#loginModal');
_qs('.loginModalButtons').forEach(button => button.onclick = () => {modal.style.display = "grid";});
_q('#loginModalClose').onclick = () => {modal.style.display = "none";}
_q('#search').onfocus = () => {_q('#searchForm').classList.toggle('hover')};
_q('#search').onblur = () => {_q('#searchForm').classList.toggle('hover')};
window.onclick = (event) => {if (event.target == modal) {modal.style.display = "none";}}

(() => {_q('#loginActive').value = "1";})();

_q('#toggleLogin').onclick = () => {
  if(_q('#loginActive').value == "1") {
    _q('#loginActive').value = "0";
    _q('.modal_title_').innerHTML = "Sign up and join the conversation!";
    _q('#loginSignupButton').innerHTML = "Sign up";
    _q('#username').style.display = "block";
    _q('#toggleLogin').innerHTML = "Login";
  } else {
    _q('#loginActive').value = "1";
    _q('.modal_title_').innerHTML = "Welcome back!";
    _q('#loginSignupButton').innerHTML = "Login";
    _q('#username').style.display = "none";
    _q('#toggleLogin').innerHTML = "Sign up";
  }
}

const timezone = function(){return Intl.DateTimeFormat().resolvedOptions().timeZone;}

window.onload = () => {
  let ajax = new XMLHttpRequest();
  ajax.open("POST", homeDirectory + "functions.php?action=updateTimezone");
  ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  ajax.onload = () => { if (ajax.readyState != 4 || ajax.status != 200) return };
  ajax.send('timezone='+ timezone());
}

_q('#loginSignupButton').onclick = () => {
  let ajax = new XMLHttpRequest(); 
  ajax.open("POST", homeDirectory + "actions.php?action=loginSignup");
  ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  ajax.onreadystatechange = () => { 
      if (ajax.readyState != 4 || ajax.status != 200) return; 
      ajax.responseText == 1 ? window.location.href = homeDirectory : _q("#loginAlert").innerHTML = ajax.responseText;
  };
  ajax.send(`email=${_q('#email').value}&password=${_q('#password').value}&username=${_q('#username').value}&loginActive=${_q('#loginActive').value}`);
};

const deleteButtons = _qs('.delete_post_button');
deleteButtons.forEach(elem => elem.onclick = () => {
  let url = homeDirectory + "actions.php?action=deletePost&id=" + elem.dataset.postid;
  let params = {method: 'POST'};
  fetch(url,params).then(() => {
    elem.parentElement.remove();
  });
});

const relayButtons = _qs('.relay_post_button');
relayButtons.forEach(elem => elem.onclick = () => {
  let url = homeDirectory + "actions.php?action=relayPost&id=" + elem.dataset.postid;
  let params = {method: 'POST'};
  fetch(url,params).then(() => {
    setTimeout(() => {
      window.location.replace(homeDirectory);
    }, 2000);
  });
});

_qs('.toggleFollow').forEach(button => button.onclick = () => {
  let url = homeDirectory + "actions.php?action=toggleFollow";
  let params = {
    method: 'POST',
    body: 'userid=' + button.attributes['data-userid'].value,
    headers: { "Content-Type": "application/x-www-form-urlencoded" }
  };
  fetch(url, params)
  .then(response => {return response.text()})
  .then(data =>  {
    if (data == 1) button.innerHTML = '&#x2b; Follow';
    else if (data == 2) button.innerHTML = '&#x2212; Unfollow';
  })
});

_qs('.like_post_button').forEach(button => button.onclick = () => {
  let url = homeDirectory + "actions.php?action=toggleLike";
  let params = {
    method: 'POST',
    body: 'postid=' + button.attributes['data-postid'].value,
    headers: { "Content-Type": "application/x-www-form-urlencoded" }
  };
  fetch(url, params)
  .then(response => {return response.text()})
  .then(data =>  {
    if (data == 1) button.innerHTML = '&#x2b; Like';
    else if (data == 2) button.innerHTML = '&#x2212; Unlike';
  })
});


_q('#post_box_button').onclick = () => {
  let url = homeDirectory + "actions.php?action=createPost";
  let params = {
    method: 'POST',
    body: 'post_box_textfield=' + _q('#post_box_textfield').value,
    headers: { "Content-Type": "application/x-www-form-urlencoded" }
  };
  fetch(url, params)
  .then(response => {return response.text()})
  .then(data =>  {
    if (data == 1) {
      _q("#postSuccess").style.display = "block";
      _q("#postFail").style.display = "none";
      setTimeout(() => {
        window.location.replace(homeDirectory);
      }, 2000);
    }
    else if (data != "") {
      _q("#postSuccess").style.display = "none";
      _q("#postFail").style.display = "block";
      _q("#postFail").innerHTML = data;
    }
  })
};

breakWord = (element, cutoffPoint) => {
  breakPoint = cutoffPoint - 1;
  _qs(element).forEach((elem) => {
    if (elem.innerText.length > breakPoint) {
      let string = elem.innerText;
      elem.innerText = string.substring(0, breakPoint) + `- ` + string.substring(breakPoint);;
    } else {
      elem.innerText = elem.innerText;
    }
  });
};

breakWord('.userName', 19);

_q('#user_bio_label').onclick = () => {
  _q('#user_bio').classList.toggle('active');
}