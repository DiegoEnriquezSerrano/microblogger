//START MESSAGE CREATE BLOCK
if(window.location != homeDirectory + 'inbox/create') {
  (() => {return})();
} else {
  const recipientInput = _q('#new_message_recipients');
  const recipientHandler = _q('#recipients_visual_input');
  recipientInput.setAttribute("recipientIds", []);
  recipientHandler.onclick = () => {
    _q('.modal').classList.toggle('open');
  }

  const recipients = Array.from(_q('.message_recipients').children);
  recipients.forEach(recipient => recipient.onclick = () => {
    if (recipientInput.attributes['recipientIds'].value == "") {
      recipientHandler.innerHTML = `<span>${recipient.children[0].innerText}</span>`;
      recipientInput.attributes['recipientIds'].value = recipient.dataset['userid'];

    } else if (recipientInput.attributes['recipientIds'].value == recipient.dataset['userid']) {
      recipientHandler.innerHTML = '';
      recipientInput.attributes['recipientIds'].value = "";

    } else if (recipientInput.attributes['recipientIds'].value.split(',').includes(recipient.dataset['userid'])) {
      let idArray = recipientInput.attributes['recipientIds'].value.split(',').filter(id => id != recipient.dataset['userid']);
      let recipientArray = recipientHandler.innerHTML.split(' ').filter(user => user != `<span>${recipient.children[0].innerText}</span>`);
      recipientInput.attributes['recipientIds'].value = idArray.sort((a, b) => a - b).toString();
      returnRecipients = () => {let recs = ''; recipientArray.forEach((rec) => {recs += (rec + ' ')}); return recs;};
      recipientHandler.innerHTML = returnRecipients();

    } else {
      recipientHandler.innerHTML += ` <span>${recipient.children[0].innerText}</span>`;
      recipientInput.attributes['recipientIds'].value += ',' + recipient.dataset['userid'];
      recipientInput.attributes['recipientIds'].value = recipientInput.attributes['recipientIds'].value.split(',').sort((a, b) => a - b).toString();
    }
    recipient.classList.toggle('clicked');
  });

  const messageBody = _q('#new_message_body');
  _q('#new_message_submit').onclick = () => {
    if (messageBody.value === "") {
      return 'Cannot send empty message. Write something and try again.';
    } else {
      let url = homeDirectory + "app/api/send_message.php";
      let params = {
        method: 'POST',
        body: 'message_body=' + messageBody.value + '&recipient_ids=' + recipientInput.attributes['recipientIds'].value,
        headers: { "Content-Type": "application/x-www-form-urlencoded" }
      }
      fetch(url,params)
      .then(response => {return response.text()})
      .then(data => {
        if (data == 1) {console.log('success!')}
        else {console.log('fail')}
      });
    };
  };
}
//END CREATE MESSAGE BLOCK

//START MESSAGE THREAD BLOCK
if (paths[1] === 'thread') {
  _q('#replyButton').onclick = () => {
    if (_q('#thread-reply').value != '') {
      console.log(_q('#thread-reply').value)
      console.log(paths[2]);
      let url = homeDirectory + "app/api/message_reply.php";
      let params = {
        method: 'POST',
        body: 'message_body=' + _q('#thread-reply').value + '&message_thread_hash=' + paths[2],
        headers: { "Content-Type": "application/x-www-form-urlencoded" }
      }
      fetch(url,params)
      .then(response => {return response.text()})
      .then(data => {
        if (data == 1) {console.log('success!')}
        else {console.log('fail')}
      });
    }
  }
}
//END MESSAGE THREAD BLOCK