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

$(document).ready(function(){
  $.ajax({
    type: "POST",
    url: "functions.php?action=updateTimezone",
    data: "timezone=" + timezone(),
    success: function(result){
      done();
    }
  });
});

$('#loginSignupButton').click(function() {
  $.ajax({
    type: "POST",
    url: "actions.php?action=loginSignup",
    data: "email=" + $("#email").val() + "&password=" + $("#password").val() + "&username=" + $("#username").val() + "&loginActive=" + $("#loginActive").val(),
    success: function(result){
      if (result == "1") {
        window.location.replace("index.php");
      } else {
        $("#loginAlert").html(result).show();
      }
    }
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
      } else if (result != "") {
        $("#postFail").html(result).show();
        $("#postSuccess").hide();
      }
    }
  });
});