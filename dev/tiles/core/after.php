<?php function after() { ?>
<div id="message-form">
<div id="message-cloak"></div>
<div id="message-shadow"></div>
<div id="message-form-content">
<div id="message-header">
::<span id="message-to-username"></span>
&nbsp;&nbsp;::<a id="message-send" href="javascript:void(0);" onclick="sendMessage();">���������</a>
&nbsp;&nbsp;::<a href="javascript:void(0);" onclick="closeMessage();">�������</a>
</div>
<input type="hidden" id="message-to" name="message-to" value="-1" />
<textarea id="message-text" name="message-text"></textarea>
<div id="message-loading"></div>
</div>
</div>
<div id="message-success">��������� ������� ����������</div>
<div id="message-error">�� ������� ��������� ���������</div>
</body>
</html>
<?php } ?>
