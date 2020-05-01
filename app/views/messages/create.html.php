<div id="homeModule">
</div><!--'#homeModule'-->
<div id="postsModule">
  <div id="messagesContainer">
    <div id="create_message_form">
      <form id="new_message_form">
        <div id="recipient_box">
          <input id="new_message_recipients" type="text" placeholder="Select recipients.." readonly hidden="true">
          <div id="recipients_visual_input"></div><!--recipients_visual_input-->
        </div><!--recipient_box-->
        <textarea id="new_message_body"></textarea>
      </form><!--new_message_form-->
      <button id="new_message_submit">Submit</button>
    </div><!--create_message_form-->
  </div><!--messagesContainer-->
</div><!--'#postsModule'-->
<div id="sectionsModule">
  <div id="sectionsContainer">
<?php echo $sections ?>

  </div><!--sectionContainer-->
</div><!--sectionsModule-->