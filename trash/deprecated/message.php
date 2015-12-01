<?php
require_once("./config/messages.php");

  if (@$error_code) {
    $title = "&gt; ������: ����������� ���������� ��������� ���������";
    $header = "��������� ������ ��� ��������� � �������";
  }
  elseif (@$warning_code) {
    $title = "&gt; ��������������: ����������� ���������� ��������� ���������";
    $header = "��������";
  }
  else {
    $title = "&gt; ����� ����������� �����";
    $header = "��� ���������";
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=WINDOWS-1251" />
    <meta name="Author" content="����� ��������" />
    <meta name="Keywords" content="���������, ����������������, �����������, ������, ICL, ������" />
    <title><?=$title?></title>
    <link href="style.css" type="text/css" rel="stylesheet"/>
  </head>
  <body>
    <div id="main">
      <div id="left">
        <img
          id="logo"
          src="logo.gif"
          width="180"
          height="100"
          alt="ICL ���-��"/>
        <h2>&gt;&nbsp;��� ������</h2>
      </div>
      <div id="right">
        <div id="header">
          <img
            class="crosspiece"
            src="crosspiece.gif"
            width="1"
            height="92"
            alt=""
            />
          <h1>&gt;&nbsp;������ �� ���������������� ��� "ICL-��� ��"</h1>
        </div>
        <div id="stuff">
          -
        </div>
        <div id="content">
          <img
            class="content"
            src="content.gif"
            alt=""
            width="1"
            height="400"
            />
          <h3><?=$header?></h3>
<?php
  if (@$error_code):
    switch ($error_code):
      case error_no_contest_info_loaded: {
?>
          <p>�� ������� ������� �� ���� ������ ���������� � ������� �������.</p>
<?php
        break;
      }
      case error_no_mysql_connection: {
?>
          <p>�� ������� ���������� ���������� � ����� ������. ��� ����� ��������� �� ����� �� ��������� ������:</p>
          <ul>
            <li>���� � ��������� ����� ����������.</li>
            <li>� ��������� ����� ������������ ���������� ���� ������ �/��� �����.</li>
          </ul>
          <p>����������, ��������� ������� ������� �����.</p>
<?php
        break;
      }
      case error_no_sessions_available: {
?>
          <p>����������� �������� �������� ������. ����������, ���������, �������� �� � ����� �������� ��������� cookies.</p>
<?php
        break;
      }
      default: {
?>
          <p>������������� ��������� �� ������ ����������. ����������, ��������� ������� �������.</p>
<?php
      }
    endswitch;
  elseif (@$warning_code):
    switch ($warning_code):
      case warning_registration_is_closed: {
?>
          <p>����������� ����� ������ <b>�� �����</b> �������. ���� �� �����-�� �������� �� �� ������ ������������������, ��� ������� ��������� ����������� ������ <a href="mailto:pupucya@mail.ru">��������� ����������</a>.</p>
<?php
        break;
      }
      default: {
?>
          <p>������������� ��������� � �������������� ����������. ����������, ��������� ������� �������.</p>
<?php
      }
    endswitch;
  else:
?>
          <p>�� ������� ������������� ���������. ����������, ��������� ������� �������.</p>
<?php
  endif;
?>
        </div>
        <div class="cleaner">
        </div>
      </div>
    </div>
    <?php footer('tournament'); ?>
  </body>
</html>
