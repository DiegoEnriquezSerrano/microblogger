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

  _q('#new_message_body').addEventListener('input', function (event) {
    if (event.target.tagName.toLowerCase() !== 'textarea') return;
    autoExpand(event.target);
  }, false);
}
//END CREATE MESSAGE BLOCK

let autoExpand = (field) => {
  field.style.height = 'inherit';
  let computed = window.getComputedStyle(field);
  let borderHeight = 
  parseInt(computed.getPropertyValue('border-top-width')) +
  parseInt(computed.getPropertyValue('border-bottom-width')) +
  parseInt(computed.getPropertyValue('border-right-width')) +
  parseInt(computed.getPropertyValue('border-left-width'));
  let height;
  field.scrollHeight <= 29 ? height = 25 : height = field.scrollHeight - 4;
  fullHeight = borderHeight + height;
  field.style.height = fullHeight + 'px';
};

//START MESSAGE THREAD BLOCK
if (paths[1] === 'thread') {

  let input = _q('#thread-reply');

  (() => {_d.scrollingElement.scrollTop = _d.scrollingElement.scrollTopMax;})();
  
  generateEventHandlers = function() {

    let sendMessage = function () {
      if (input.value != '') {
        let url = homeDirectory + "app/api/message_reply.php";
        let params = {
          method: 'POST',
          body: 'message_body=' + input.value + '&message_thread_hash=' + paths[2],
          headers: { "Content-Type": "application/x-www-form-urlencoded" }
        };
        fetch(url,params)
          .then(response => {return response.text()})
          .then(data => {
            input.value = '';
            input.style.height = 33 + 'px';
            let messageThread = _q('#threads');
            let currentTop = _d.scrollingElement.scrollTop;
            let currentTopMax = _d.scrollingElement.scrollTopMax;
            messageThread.innerHTML = messageThread.innerHTML + data;
            _q('#threadContainer').style.marginBottom = 33 + "px";
            if (currentTop == currentTopMax) {
              _d.scrollingElement.scrollTop = _d.scrollingElement.scrollTopMax;
            };
          });
      };
    };

    _q('#replyButton').onclick = () => {
      sendMessage();
      generateEventHandlers();
    };
  
    _q('#thread-reply').addEventListener('input', function (event) {
      let currentTop = _d.scrollingElement.scrollTop;
      let currentTopMax = _d.scrollingElement.scrollTopMax;
      let inputHeight = event.target.offsetHeight;
      autoExpand(event.target);
      if (inputHeight != event.target.height) {
        _q('#threadContainer').style.marginBottom = event.target.offsetHeight + "px";
      };
      if (currentTop == currentTopMax) {
        _d.scrollingElement.scrollTop = _d.scrollingElement.scrollTopMax;
      };
    }, false);
  };

  generateEventHandlers();
};
//END MESSAGE THREAD BLOCK


//START MESSAGE INBOX BLOCK
if (paths[0] === 'inbox' && !paths[1]) {
  threads = Array.from(_qs('.message_row'));
  threads.forEach(thread => thread.onclick = () => {
    window.location.href = homeDirectory + 'inbox/thread/' + thread.dataset.thread;
  })
}
//END MESSAGE INBOX BLOCK