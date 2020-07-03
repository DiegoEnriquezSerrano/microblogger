if (paths[0] === 'edit' && !paths[1]) {

  if (window.File && window.FileReader && window.FileList && window.Blob) {
    document.getElementById('filesToUpload').onchange = function(){
      var files = document.getElementById('filesToUpload').files;
      Array.from(files).forEach((file) => {
        resizeAndUpload(file);
      });
    };
  } else {
    alert('The File APIs are not fully supported in this browser.');
  }
  
  _q('#editFormSubmit').onclick = (e) => {
    let inputs = Array.from(e.target.parentElement.children);
    let usernameInput = inputs.find(elem => elem.name == 'username').value;
    let displayNameInput = inputs.find(elem => elem.name == 'displayname').value;
    let bioInput = inputs.find(elem => elem.name == 'bio').value;
    
    if (
      validate_string(alphaNumbericRegExp, usernameInput, 19, 2) && 
      validate_string(textRegExp, displayNameInput, 21) &&
      validate_string(textRegExp, bioInput, 500)
    ) {
      let url = homeDirectory + "app/api/edit.php";
      let body = {
          user: { 
          user_name: usernameInput,
          user_display_name: displayNameInput || null,
          user_bio: bioInput ? bioInput.toString() : null,
        }
      };
      let params = {
        method: 'POST',
        body: JSON.stringify(body), 
        headers: { "Content-Type": "application/json" }
      };
      fetch(url, params)
      .then(response => {return response.text()})
      .then(data =>  {
        console.log(data);
      });
    };
    e.preventDefault();
  };
  
  function resizeAndUpload(file) {
    let reader = new FileReader();
    reader.onloadend = function() {
  
      let tempImg = new Image();
      tempImg.src = reader.result;
      tempImg.onload = function() {
  
        let MAX_WIDTH = 500;
        let MAX_HEIGHT = 500;
        let tempW = tempImg.width;
        let tempH = tempImg.height;
        if (tempW > tempH) {
          if (tempW > MAX_WIDTH) {
            tempH *= MAX_WIDTH / tempW;
            tempW = MAX_WIDTH;
          };
        } else {
          if (tempH > MAX_HEIGHT) {
            tempW *= MAX_HEIGHT / tempH;
            tempH = MAX_HEIGHT;
          };
        };
  
        let canvas = document.createElement('canvas');
        canvas.width = tempW;
        canvas.height = tempH;
        let ctx = canvas.getContext("2d");
        ctx.drawImage(this, 0, 0, tempW, tempH);
        let dataURL = canvas.toDataURL("image/jpeg");
  
        let url = homeDirectory + "app/api/upload_img.php";
        let params = {
          method: 'POST',
          body: 'image=' + dataURL,
          headers: { "Content-Type": "application/x-www-form-urlencoded" }
        };
        fetch(url, params)
        .then(response => {return response.text()})
        .then(data =>  {
          console.log(data);
        });
      };
    };
    reader.readAsDataURL(file);
  };
};



if (paths[0] === 'edit' && paths[1] === 'account') {

  _q('#editFormSubmit').onclick = (e) => {

    e.preventDefault();

    let email = _q('input[name=email]').value;
    let oldPassword = _q('input[name=oldPassword]').value;
    let newPassword = _q('input[name=newPassword]').value;
    let newPasswordConfirm = _q('input[name=newPasswordConfirm]').value;

    let body = {};

    if (!validate_email(emailRegExp, email)) return console.error('Please enter a valid email');
    if (newPassword !== '') {
      if (oldPassword === '') return console.error('You must enter your original password');
      if (newPassword !== newPasswordConfirm) return console.error('New passwords must match');
      if (newPassword.length < 12) return console.error('New password must be at least 13 characters long')
    } else {
      oldPassword = '';
      newPasswordConfirm = '';
    };

    body = {
      account: {
        email,
        old_password: oldPassword || null,
        new_password: newPassword || null,
        new_password_confirm: newPasswordConfirm || null,
      }
    };

    let url = homeDirectory + "app/api/edit.php";
    let params = {
      method: 'POST',
      body: JSON.stringify(body), 
      headers: { "Content-Type": "application/json" }
    };
    fetch(url, params)
    .then(response => {return response.text()})
    .then(data =>  {
      console.log(data);
    });

    console.log(body);
  };
};