const _d = document;
const _q = (query) => {return _d.querySelector(query);}
const _qs = (query) => {return Array.from(_d.querySelectorAll(query));}

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
  ajax.open("POST", "functions.php?action=updateTimezone");
  ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  ajax.onload = () => { if (ajax.readyState != 4 || ajax.status != 200) return };
  ajax.send('timezone='+ timezone());
}

_q('#loginSignupButton').onclick = () => {
  let ajax = new XMLHttpRequest(); 
  ajax.open("POST", "actions.php?action=loginSignup");
  ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  ajax.onreadystatechange = () => { 
      if (ajax.readyState != 4 || ajax.status != 200) return; 
      ajax.responseText == 1 ? window.location.href = "index.php" : _q("#loginAlert").innerHTML = ajax.responseText;
  };
  ajax.send(`email=${_q('#email').value}&password=${_q('#password').value}&username=${_q('#username').value}&loginActive=${_q('#loginActive').value}`);
};

const deleteButtons = _qs('.delete_post_button');

deleteButtons.forEach(elem => elem.onclick = () => {
  let url = "actions.php?action=deletePost&id=" + elem.dataset.postid;
  let params = {method: 'POST'};
  fetch(url,params).then(() => {
    elem.parentElement.parentElement.remove();
  });
});

$(".toggleFollow").click(function() {
  let id = $(this).attr("data-userId");
  $.ajax({
    type: "POST",
    url: "actions.php?action=toggleFollow",
    data: "userid=" + id,
    success: function(result) {
      if (result == "1") {
        $("a[data-userId='" + id + "']").html("Follow");
      } else if (result == "2") {
        $("a[data-userId='" + id + "']").html("Unfollow");
      }
    }
  });
});

$('#createPostButton').click(function() {
  $.ajax({
    type: "POST",
    url: "actions.php?action=createPost",
    data:  "postTextfield=" + $("#postTextfield").val(),
    success: function(result){
      if (result == "1") {
        $("#postSuccess").show();
        $("#postFail").hide();
        setTimeout(() => {
          window.location.replace("/");
        }, 2000);
      } else if (result != "") {
        $("#postFail").html(result).show();
        $("#postSuccess").hide();
      }
    }
  });
});