<?php
require_once('./config/require.php');

if (@!$main_session)
    header('Location: '.ServerRoot.'message.php?error_code='.error_no_sessions_available);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=WINDOWS-1251" />
    <meta name="Author" content="����� ��������" />
    <meta name="Keywords" content="���������, ����������������, �����������, ������, ICL, ������" />
    <title>����������� ���� ������� �� ���������������� ��� "ICL-��� ��"</title>
    <link href="style.css" type="text/css" rel="stylesheet" />
  </head>
  <body>
    <div id="main">
      <div id="left">
        <img
          id="logo"
          src="logo.gif"
          width="180"
          height="100"
          alt="ICL ���-��" />
      </div>
          <?php menu('tournament'); ?>
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
        <div id="login">
<?php
  if (@$main_authorized == 1):
?>
          <h2>&gt;&nbsp;����� �� �������</h2>
          <form name="frmLogout" action="logout.php" method="post">
            ������������: <?=$main_curlogin?>
            <input type="submit" name="logoutbtn" value="�����" />
          </form>
<?php
  else:
?>
          <h2>&gt;&nbsp;��������� ����</h2>
          <form name="frmLogin" action="login.php" method="post">
            login&nbsp;<input type="text" name="login" maxlength="20" />
            ������&nbsp;<input type="password" name="password" maxlength="20" />
            <input type="submit" name="loginbtn" value="�����" />
          </form>
<?php
  endif;
?>
        </div>
        <?php stuff('tournament'); ?>
        <div id="content">
          <img
            class="content"
            src="content.gif"
            alt=""
            width="1"
            height="400"
            />
          <h3>���� ��������</h3>
          <hr />
          <p><a href="http://www.moltat.ru" title="������ ������������ �������� ����������"><img src="./images/mt.jpg" alt="������������ �������� ����������" width="500" height="109" /></a></p>
          <p><a href="http://www.business-gazeta.ru" title="������ ������-������"><img src="./images/bo.jpg" alt="������ ������-������" width="500" height="167" /></a></p>
          <p><a href="http://www.compkaz.ru" title="������������ ������"><img src="./images/ck.gif" alt="������ ������������ ������" width="250" height="46" /></a></p>
        </div>
        <div class="cleaner">
        </div>
      </div>
    </div>
    <?php footer('tournament'); ?>
  </body>
</html>
