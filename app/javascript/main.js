const _d = document;
const _q = (query) => {return _d.querySelector(query);}
const _qs = (query) => {return Array.from(_d.querySelectorAll(query));}
const paths = window.location.href.split(homeDirectory)[1].split('/');
HTMLElement.prototype._q = function (query) {return this.querySelector(query);}
HTMLElement.prototype._qs = function (query) {return this.querySelectorAll(query);}

const getDirectoryLinks = _qs('.nav-link');
const modal = _q('#loginModal');

_qs('.loginModalButtons').forEach(button => button.onclick = () => { modal.style.display = "grid" });
_q('#loginModalClose').onclick = () => {modal.style.display = "none";}
window.onclick = (event) => {if (event.target == modal) {modal.style.display = "none";}}

const modalClose = (modal) => { modal.classList.remove('open') };

const emailRegExp = new RegExp(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
const alphaNumbericRegExp = new RegExp(/^[a-z0-9_-]+$/i);
const textRegExp = new RegExp(/^[a-zA-Z0-9 !@#$%?'/\n\^&*)(+=;._-]*$/);

function validate_email(regExp, input) {
  return regExp.test(input);
};

function validate_string(regExp, input, max, min) {
  if (input.length >= max || (min && input.length <= --min)) return false;
  if (!min && input) return regExp.test(input);
  return regExp.test(input);
};

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
  };
};

const timezone = () => { return Intl.DateTimeFormat().resolvedOptions().timeZone };

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
  ajax.send(
    `email=${_q('#email').value}
    &password=${_q('#password').value}
    &username=${_q('#username').value}
    &loginActive=${_q('#loginActive').value}`
  );
};

let draftToggles = _qs('.draftSlider');
if (draftToggles != '') {
  draftToggles.forEach(
    elem => elem._q('.draftActive').onclick = function () {
      this.checked == true ? this.value = 0 : this.value = 1;
  });
};

if (paths == 'drafts') {
  draftToggles.forEach(elem => { 
    let toggle = elem._q('.draftActive');
    toggle.checked = true;
    toggle.disabled = true;
    toggle.value = 0;
  });
};

postLinkToDraftLink = (anchorElement) => {
  newUrl = homeDirectory + 'draft/' + anchorElement.href.split(homeDirectory)[1].split('/')[1];
  anchorElement.href = newUrl;
}

function generateEventHandlers(){

  _qs('.delete_post_button').forEach(elem => elem.onclick = () => {
    let url = homeDirectory + "app/api/delete.php?action=deletePost&id=" + elem.dataset.postid;
    if (paths == 'drafts') url = url + '&draft';
    let params = {method: 'POST'};
    fetch(url,params)
    .then(response => {return response.text()})
    .then(data => {
      if (data == 1) {elem.parentElement.parentElement.remove();}
    });
  });

  if (_q('#postSubmit') != null ) {
    _q('#postSubmit').onclick = () => {
      let parent = _q('.createPostModal') != undefined ? _q('.createPostModal') : _q('#postContainer');
      let toggle = parent._q('.draftActive');
      let url = homeDirectory + "app/api/post.php";
      let body = { 
        draft: toggle.value == 0 ? true : false,
        draftId: (paths[0] == 'draft' && paths[1] != undefined) ? paths[1] : undefined,
        text: parent._q('#postBody').value == "" ? undefined : parent._q('#postBody').value,
      };
      let params = {
        method: 'POST',
        body: JSON.stringify(body), 
        headers: { "Content-Type": "application/json" }
      };
      fetch(url, params)
      .then(response => {return response.text()})
      .then(data =>  {
        let postContainer = _q('#postContainer');
        if (paths == 'liked') {
          return;
        } else if (paths == 'drafts' && _q('.draftActive').value == 0) {
          postContainer.innerHTML = data + postContainer.innerHTML;
          postLinkToDraftLink(postContainer.firstElementChild.firstElementChild.lastElementChild.childNodes[7]);
        } else if ((paths == 'timeline' || paths == 'published') && _q('.draftActive').value == 1) {
          postContainer.innerHTML = data + postContainer.innerHTML;
        } else return;
        modalClose(createPostModal);
        generateEventHandlers();
      });
      _q('#postSuccess').style.display = "block";
      _q('#postBody').value = "";
    };
  };

  _qs('.relay_post_button').forEach(elem => elem.onclick = () => {

    let post = elem.parentElement.parentElement;
    let modal = _q('.relayModal');
    let originalPost = _q('.originalPost');
    let relayOption = _q('.relayOption');
    let relayAddComment = _q('.relayAddComment');

    let postInfo = {
      postId: Number(post._q('.relay_post_button').attributes['data-postid'].value),
      postUser: post._q('.userlink').text,
      postText: post._q('.postBody').innerText,
      postAvatar: post._q('.user_img'),
      postUsername: post._q('.userlink').nextSibling.data.trim()
    }

    let relayOriginalPost = `
        <div class="smallPost">
          <div class="smallPostHeader">
            <a class="smallAvatar">
              ${postInfo.postAvatar.outerHTML}
            </a>
            <p class="smallPostUserDetail">
              <a class="userlink">${postInfo.postUser}</a>
              ${postInfo.postUsername}
            </p><!--smallPostUserDetail-->
          </div><!--smallPostHeader-->
          <p class="smallPostContent">
            ${postInfo.postText}
          </p>
        </div><!--smallPost-->
    `;
    
    modal.classList.toggle('open');
    postInfo.postAvatar.classList.add('relayAvatar');
    
    _q('#relayWithCommentButton').onclick = (e) => {
      relayOption.classList.remove('stepOne');
      relayOption.classList.add('stepTwo');
      relayAddComment.classList.add('show');
      originalPost.innerHTML = relayOriginalPost;
    }

    modal.querySelector('.close').onclick = () => {
      modalClose(modal);
      originalPost.innerHTML = '';
      relayOption.classList.remove('stepTwo');
      relayOption.classList.add('stepOne');
      relayAddComment.classList.remove('show');
    };

    _qs('.relayPostButton').forEach(elem => elem.onclick = () => {
      if ( relayAddComment.classList.contains('show') ) {
        let textarea = relayAddComment._q('textarea');
        let draftToggle = modal._q('.draftActive');

        if (textarea.value === "") {
          console.log('Cannot submit without comment');
          return;
        }

        body = {
          id: postInfo.postId,
          text: textarea.value,
          draft: draftToggle.value == 0 ? true : false,
        }
      } else body = { id: postInfo.postId }

      let url = homeDirectory + "app/api/relay.php";
      let params = {
        method: 'POST',
        body: JSON.stringify(body), 
        headers: { "Content-Type": "application/json" }
      };
      fetch(url, params)
      .then(response => {return response.text()})
      .then(data =>  {
        _q('#postContainer').innerHTML = data + _q('#postContainer').innerHTML;
        modalClose(modal);
        generateEventHandlers();
      });
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
      if (data == 1) button.innerHTML = 
      `<svg class="icon" width="20px" height="22px" viewBox="0 0 20 22" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <title>User Follow</title>
        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
          <g transform="translate(-100.000000, -2159.000000)" fill="#59617d">
            <g transform="translate(56.000000, 160.000000)">
              <path d="M58.0831232,2004.99998 C58.0831232,2002.79398 56.2518424,2000.99998 54,2000.99998 C51.7481576,2000.99998 49.9168768,2002.79398 49.9168768,2004.99998 C49.9168768,2007.20598 51.7481576,2008.99998 54,2008.99998 C56.2518424,2008.99998 58.0831232,2007.20598 58.0831232,2004.99998 M61.9457577,2018.99998 L60.1246847,2018.99998 C59.5612137,2018.99998 59.1039039,2018.55198 59.1039039,2017.99998 C59.1039039,2017.44798 59.5612137,2016.99998 60.1246847,2016.99998 L60.5625997,2016.99998 C61.26898,2016.99998 61.790599,2016.30298 61.5231544,2015.66198 C60.2869889,2012.69798 57.3838883,2010.99998 54,2010.99998 C50.6161117,2010.99998 47.7130111,2012.69798 46.4768456,2015.66198 C46.209401,2016.30298 46.73102,2016.99998 47.4374003,2016.99998 L47.8753153,2016.99998 C48.4387863,2016.99998 48.8960961,2017.44798 48.8960961,2017.99998 C48.8960961,2018.55198 48.4387863,2018.99998 47.8753153,2018.99998 L46.0542423,2018.99998 C44.7782664,2018.99998 43.7738181,2017.85698 44.044325,2016.63598 C44.7874534,2013.27698 47.1076881,2010.79798 50.1639058,2009.67298 C48.7695192,2008.57398 47.8753153,2006.88998 47.8753153,2004.99998 C47.8753153,2001.44898 51.0234032,1998.61898 54.7339414,1999.04198 C57.422678,1999.34798 59.6500217,2001.44698 60.0532301,2004.06998 C60.4002955,2006.33098 59.4560733,2008.39598 57.8360942,2009.67298 C60.8923119,2010.79798 63.2125466,2013.27698 63.955675,2016.63598 C64.2261819,2017.85698 63.2217336,2018.99998 61.9457577,2018.99998 M57.0623424,2017.99998 C57.0623424,2018.55198 56.6050326,2018.99998 56.0415616,2018.99998 L55.2290201,2018.99998 C55.2290201,2019.99998 55.3351813,2020.99998 54.2082393,2020.99998 C53.6437475,2020.99998 53.1874585,2020.55198 53.1874585,2019.99998 L53.1874585,2018.99998 L51.9584384,2018.99998 C51.3949674,2018.99998 50.9376576,2018.55198 50.9376576,2017.99998 C50.9376576,2017.44798 51.3949674,2016.99998 51.9584384,2016.99998 L53.1874585,2016.99998 L53.1874585,2015.99998 C53.1874585,2015.44798 53.6437475,2014.99998 54.2082393,2014.99998 C54.7717103,2014.99998 55.2290201,2015.44798 55.2290201,2015.99998 L55.2290201,2016.99998 L56.0415616,2016.99998 C56.6050326,2016.99998 57.0623424,2017.44798 57.0623424,2017.99998" id="profile_plus_round-[#1343]"></path>
            </g>
          </g>
        </g>
      </svg>`;
      else if (data == 2) button.innerHTML = 
      `<svg class="icon" width="20px" height="20px" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <title>User Unfollow</title>
        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
          <g transform="translate(-220.000000, -2159.000000)" fill="#59617d">
            <g transform="translate(56.000000, 160.000000)">
              <path d="M178,2005 C178,2002.794 176.206,2001 174,2001 C171.794,2001 170,2002.794 170,2005 C170,2007.206 171.794,2009 174,2009 C176.206,2009 178,2007.206 178,2005 L178,2005 Z M184,2019 L179,2019 L179,2017 L181.784,2017 C180.958,2013.214 177.785,2011 174,2011 C170.215,2011 167.042,2013.214 166.216,2017 L169,2017 L169,2019 L164,2019 C164,2014.445 166.583,2011.048 170.242,2009.673 C168.876,2008.574 168,2006.89 168,2005 C168,2001.686 170.686,1999 174,1999 C177.314,1999 180,2001.686 180,2005 C180,2006.89 179.124,2008.574 177.758,2009.673 C181.417,2011.048 184,2014.445 184,2019 L184,2019 Z M171,2019 L177,2019 L177,2017 L171,2017 L171,2019 Z" id="profile_minus-[#1340]"></path>
            </g>
          </g>
        </g>
      </svg>`;
    })
  });
  
  _qs('.like_post_button').forEach(button => button.onclick = () => {
    let liked = `
    <svg class="icon" width="21px" height="16px" viewBox="0 0 21 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
      <title>love</title>
      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g transform="translate(-139.000000, -361.000000)" fill="#59617d">
          <g transform="translate(56.000000, 160.000000)">
            <path d="M103.991908,206.599878 C103.779809,210.693878 100.744263,212.750878 96.9821188,215.798878 C94.9997217,217.404878 92.0324261,217.404878 90.042679,215.807878 C86.3057345,212.807878 83.1651892,210.709878 83.0045394,206.473878 C82.8029397,201.150878 89.36438,198.971878 93.0918745,203.314878 C93.2955742,203.552878 93.7029736,203.547878 93.9056233,203.309878 C97.6205178,198.951878 104.274358,201.159878 103.991908,206.599878" id="love"></path>
          </g>
        </g>
      </g>
    </svg>`;
    let unliked = `
    <svg class="icon" width="21px" height="16px" viewBox="0 0 21 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
      <title>love</title>
      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g transform="translate(-99.000000, -362.000000)" fill="#59617d">
          <g transform="translate(56.000000, 160.000000)">
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

};

generateEventHandlers();

_q('#sections_expander').onclick = () => { _q('#sectionsContainer').classList.toggle('expanded') };

breakWord = (element, cutoffPoint) => {
  breakPoint = --cutoffPoint;
  _qs(element).forEach((elem) => {
    if (elem.innerText.length > breakPoint) {
      let string = elem.innerText;
      elem.innerText = string.substring(0, breakPoint) + `- ` + string.substring(breakPoint);;
    } else {
      elem.innerText = elem.innerText;
    }
  });
};

if ( _q('#user_bio_label') != null ) {
  _q('#user_bio_label').onclick = () => {
    _q('#user_bio').classList.toggle('active');
  }
};