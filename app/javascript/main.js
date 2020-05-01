const _d = document;
const _q = (query) => {return _d.querySelector(query);}
const _qs = (query) => {return Array.from(_d.querySelectorAll(query));}
const paths = window.location.href.split(homeDirectory)[1].split('/');

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
  ajax.open("POST", homeDirectory + "app/api/timezone.php");
  ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  ajax.onload = () => { if (ajax.readyState != 4 || ajax.status != 200) return };
  ajax.send('timezone='+ timezone());
}

_q('#loginSignupButton').onclick = () => {
  let ajax = new XMLHttpRequest(); 
  ajax.open("POST", homeDirectory + "app/api/authenticate.php");
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
  if (paths == 'drafts') {
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
    let url = homeDirectory + "app/api/delete.php?action=deletePost&id=" + elem.dataset.postid;
    if (paths == 'drafts') {
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
      if( (paths[1] != undefined) && paths[0] == 'draft' && _q('#draftActive').value == 0 ) {
        var url = homeDirectory + "app/api/post.php?action=editDraft&id=" + paths[1];
      } else if ( (paths[1] != undefined) && paths[0] == 'draft' && _q('#draftActive').value == 1 ) {
        var url = homeDirectory + "app/api/post.php?action=createPost&fromDraft=" + paths[1];
      } else if ( _q('#draftActive').value == 1 ) {
        var url = homeDirectory + "app/api/post.php?action=createPost";
      } else if ( _q('#draftActive').value == 0 ) {
        var url = homeDirectory + "app/api/post.php?action=createDraft";
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
        let postContainer = _q('#postContainer');
        if (paths == 'liked') {
          return;
        } else if (paths == 'drafts' && _q('#draftActive').value == 0) {
          postContainer.innerHTML = data + postContainer.innerHTML;
          postLinkToDraftLink(postContainer.firstElementChild.firstElementChild.lastElementChild.childNodes[7]);
        } else if ((paths == 'timeline' || paths == 'published') && _q('#draftActive').value == 1) {
          postContainer.innerHTML = data + postContainer.innerHTML;
        } else return;
        generateEventHandlers();
      });
      _q('#postSuccess').style.display = "block";
      _q('#post_box_textfield').value = "";
    };
  };
  
  _qs('.relay_post_button').forEach(elem => elem.onclick = () => {
    let url = homeDirectory + "app/api/relay.php?id=" + elem.dataset.postid;
    let params = {method: 'POST'};
    fetch(url, params)
    .then(response => {return response.text()})
    .then(data =>  {
      _q('#postContainer').innerHTML = data + _q('#postContainer').innerHTML;
      generateEventHandlers();
    });
  });
  
  _qs('.toggleFollow').forEach(button => button.onclick = () => {
    let url = homeDirectory + "app/api/follow.php";
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
    let url = homeDirectory + "app/api/like.php";
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

_q('#nav_panel_expander').onclick = () => {
  _q('#sectionsContainer').classList.toggle('expanded');
  console.log(_q('#sectionsContainer'));
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

if ( _q('#user_bio_label') != null ) {
  _q('#user_bio_label').onclick = () => {
    _q('#user_bio').classList.toggle('active');
  }
};

_qs('.modal_close').forEach(button => button.onclick = () => {
  button.parentNode.parentNode.classList.toggle('open');
});