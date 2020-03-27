const _d = document;
const _q = (query) => {return _d.querySelector(query);}
const _qs = (query) => {return Array.from(_d.querySelectorAll(query));}

const homeDirectory = 'http://localhost/microblogger/';
const firstSplit = window.location.href.split(homeDirectory);
const secondSplit = firstSplit[1].split('/');
const getDirectoryLinks = _qs('.nav-link');

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

const draftToggle = _q('#draftActive');
if (draftToggle != null) {
  draftToggle.onclick = () => {
    if (draftToggle.checked == true) {
      draftToggle.value = 0;
    } else {
      draftToggle.value = 1;
    }
  }
  if (secondSplit == 'drafts') {
    draftToggle.checked = true;
    draftToggle.disabled = true;
    draftToggle.value = 0;
  }
}

postLinkToDraftLink = (anchorElement) => {
  newUrl = homeDirectory + 'draft/' + anchorElement.href.split(homeDirectory)[1].split('/')[1];
  anchorElement.href = newUrl;
}

function generateEventHandlers(){

  _qs('.delete_post_button').forEach(elem => elem.onclick = () => {
    let url = homeDirectory + "actions.php?action=deletePost&id=" + elem.dataset.postid;
    if (secondSplit == 'drafts') {
      url = url + '&draft';
    }
    let params = {method: 'POST'};
    fetch(url,params)
    .then(response => {return response.text()})
    .then(data => {
      if (data == 1) {elem.parentElement.remove();}
    });
  });

  if (_q('#post_box_button') != null ) {
    _q('#post_box_button').onclick = () => {
      if ( _q('#draftActive').value == 1 ) {
        var url = homeDirectory + "actions.php?action=createPost";
      } else if ( _q('#draftActive').value == 0 ) {
        var url = homeDirectory + "actions.php?action=createDraft";
      } else { 
        console.log('invalid command');
        return;
      };
      var params = {
        method: 'POST',
        body: 'post_box_textfield=' + _q('#post_box_textfield').value,
        headers: { "Content-Type": "application/x-www-form-urlencoded" }
      }
      fetch(url, params)
      .then(response => {return response.text()})
      .then(data =>  {
        if (secondSplit == 'liked') {
          return;
        } else if (secondSplit == 'drafts' && _q('#draftActive').value == 0) {
          _q('#postContainer').innerHTML = data + _q('#postContainer').innerHTML;
          postLinkToDraftLink(_q('#postContainer').firstElementChild.firstElementChild.lastElementChild.childNodes[7]);
        } else if ((secondSplit == 'timeline' || secondSplit == 'published') && _q('#draftActive').value == 1) {
          _q('#postContainer').innerHTML = data + _q('#postContainer').innerHTML;
        } else return;
        generateEventHandlers();
      });
      _q('#postSuccess').style.display = "block";
      _q('#post_box_textfield').value = "";
    };
  };
  
  _qs('.relay_post_button').forEach(elem => elem.onclick = () => {
    let url = homeDirectory + "actions.php?action=relayPost&id=" + elem.dataset.postid;
    let params = {method: 'POST'};
    fetch(url, params)
    .then(response => {return response.text()})
    .then(data =>  {
      _q('#postContainer').innerHTML = data + _q('#postContainer').innerHTML;
      generateEventHandlers();
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

}

generateEventHandlers();

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

if ( _q('#user_bio_label') != null ) {
  _q('#user_bio_label').onclick = () => {
    _q('#user_bio').classList.toggle('active');
  }
};