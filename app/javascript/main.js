const _d = document;
const _q = (query) => {return _d.querySelector(query);}
const _qs = (query) => {return Array.from(_d.querySelectorAll(query));}
const paths = window.location.href.split(homeDirectory)[1].split('/');

const getDirectoryLinks = _qs('.nav-link');

const modal = _q('#loginModal');
_qs('.loginModalButtons').forEach(button => button.onclick = () => {modal.style.display = "grid";});
_q('#loginModalClose').onclick = () => {modal.style.display = "none";}
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
      if (data == 1) {elem.parentElement.parentElement.remove();}
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
    let liked = `
    <svg width="21px" height="16px" viewBox="0 0 21 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
      <title>love</title>
      <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
          <g id="Dribbble-Dark-Preview" transform="translate(-139.000000, -361.000000)" fill="#59617d">
              <g id="icons" transform="translate(56.000000, 160.000000)">
                  <path d="M103.991908,206.599878 C103.779809,210.693878 100.744263,212.750878 96.9821188,215.798878 C94.9997217,217.404878 92.0324261,217.404878 90.042679,215.807878 C86.3057345,212.807878 83.1651892,210.709878 83.0045394,206.473878 C82.8029397,201.150878 89.36438,198.971878 93.0918745,203.314878 C93.2955742,203.552878 93.7029736,203.547878 93.9056233,203.309878 C97.6205178,198.951878 104.274358,201.159878 103.991908,206.599878" id="love"></path>
              </g>
          </g>
      </g>
    </svg>`;
    let unliked = `
    <svg width="21px" height="16px" viewBox="0 0 21 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
      <title>love</title>
      <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
          <g id="Dribbble-Dark-Preview" transform="translate(-99.000000, -362.000000)" fill="#59617d">
              <g id="icons" transform="translate(56.000000, 160.000000)">
                  <path d="M55.5929644,215.348992 C55.0175653,215.814817 54.2783665,216.071721 53.5108177,216.071721 C52.7443189,216.071721 52.0030201,215.815817 51.4045211,215.334997 C47.6308271,212.307129 45.2284309,210.70073 45.1034811,207.405962 C44.9722313,203.919267 48.9832249,202.644743 51.442321,205.509672 C51.9400202,206.088455 52.687619,206.420331 53.4940177,206.420331 C54.3077664,206.420331 55.0606152,206.084457 55.5593644,205.498676 C57.9649106,202.67973 62.083004,203.880281 61.8950543,207.507924 C61.7270546,210.734717 59.2322586,212.401094 55.5929644,215.348992 M53.9066671,204.31012 C53.8037672,204.431075 53.6483675,204.492052 53.4940177,204.492052 C53.342818,204.492052 53.1926682,204.433074 53.0918684,204.316118 C49.3717243,199.982739 42.8029348,202.140932 43.0045345,207.472937 C43.1651842,211.71635 46.3235792,213.819564 50.0426732,216.803448 C51.0370217,217.601149 52.2739197,218 53.5108177,218 C54.7508657,218 55.9898637,217.59915 56.9821122,216.795451 C60.6602563,213.815565 63.7787513,211.726346 63.991901,207.59889 C64.2754005,202.147929 57.6173611,199.958748 53.9066671,204.31012" id="love-[#1489]"></path>
              </g>
          </g>
      </g>
    </svg>`;
    let url = homeDirectory + "app/api/like.php";
    let params = {
      method: 'POST',
      body: 'postid=' + button.attributes['data-postid'].value,
      headers: { "Content-Type": "application/x-www-form-urlencoded" }
    };
    fetch(url, params)
    .then(response => {return response.text()})
    .then(data =>  {
      if (data == 1) button.innerHTML = unliked;
      else if (data == 2) button.innerHTML = liked;
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