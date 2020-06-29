<div id="mainModule">
  <div id="messagesContainer">
    <div id="messagesHeader">
      <div id="newThreadButton">

      </div>
      <div id="messagesLabel">Messages</div>
    </div><!--messagesHeader-->
    <div id="create_message_form">
      <form id="new_message_form">
        <div id="recipient_box">
          <label for="recipients" class="label">Send to</label>
          <input id="new_message_recipients" type="text" name="recipients" placeholder="Select recipients.." readonly hidden="true">
          <div id="recipients_visual_input"></div><!--recipients_visual_input-->
        </div><!--recipient_box-->
        <label for="recipients" class="label">Body</label>
        <textarea id="new_message_body"></textarea>
      </form><!--new_message_form-->
      <button class="button" id="new_message_submit">Send</button>
    </div><!--create_message_form-->
  </div><!--messagesContainer-->
</div><!--'#mainModule'-->
<div id="sectionsModule">
  <div id="sectionsContainer">
<?php echo $sections ?>

  </div><!--sectionContainer-->
</div><!--sectionsModule-->