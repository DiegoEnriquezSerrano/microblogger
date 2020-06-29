
    <div id="messagesHeader">
      <div id="newThreadButton">
        <a href="<?php echo HOME_DIRECTORY; ?>inbox/create">
          <svg class="icon" width="20px" height="20px" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <title>New Message</title>
            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
              <g transform="translate(-260.000000, -999.000000)" fill="#59617d">
                <g transform="translate(56.000000, 160.000000)">
                  <path d="M213,846 L211,846 L211,844 L213,844 L213,842 L215,842 L215,844 L217,844 L217,846 L215,846 L215,848 L213,848 L213,846 Z M221,849 L221,846 L222,849 L221,849 Z M222,857 L206,857 L206,851 L210,851 L210,855 L218,855 L218,851 L222,851 L222,857 Z M207,846 L207,849 L206,849 L207,846 Z M209,841 L219,841 L219,849 L216,849 L216,853 L212,853 L212,849 L209,849 L209,841 Z M222,843 L221,843 L221,839 L207,839 L207,843 L206,843 L204,849 L204,859 L224,859 L224,849 L222,843 Z" id="inbox_plus-[#1554]"></path>
                </g>
              </g>
            </g>
          </svg>
        </a>
      </div><!--newThreadButton-->
      <div id="messagesLabel">Messages</div>
    </div><!--messagesHeader-->
    <div id="mainModule">
      <div id="messagesContainer">
<?php displayMessages(); ?>

      </div><!--messagesContainer-->
    </div><!--'#mainModule'-->
    <div id="sectionsModule">
      <div id="sectionsContainer">
<?php echo $sections ?>

      </div><!--sectionContainer-->
    </div><!--sectionsModule-->