<?php
require_once('./config/require.php');

if (@!$main_session)
    header('Location: '.ServerRoot.'message.php?error_code='.error_no_sessions_available);
    
  $error = false;
  $msg = '';
  if (@!$teamid) {
    $msg = '� ������� �� ������ ������������� �������.';
    $error = true;
  }
  else {
    $x = $teamid;
    settype($x, 'integer');
    if ($x==0) {
      $msg = '������� ������ ���� ��� ��������� ����������. ���������� �������� ������ ������� � ��������� �������.';
      $error = true;
    }
    else {
      $query = 'select teamname from teams where teamid='.$teamid;
      $r = mysql_query($query);
      
      if (mysql_num_rows($r) == 0) {
        $msg = '������� ����� ������������� �������.';
        $error = true;
      }
    }
  }    
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
          <?php menu('tournament'); ?>
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
          <h3>���� � �������</h3>
          <hr />
<?php
  if ($error):
?>          
          <?=$msg?>
<?php
  else:
    if (@$wrongpass):
?>  
          ������ �������� ������. ��������� ������� ��� ���.
<?php
    else:
?>  
          ��� ������������� �������������� ������ ������� ������� ������, ��������������� ������:
<?php
    endif;
?>
          <hr />
          <form name="teamlogin" action="editinfo.php" method="post">
            <input type="hidden" name="teamid" value="<?=$teamid?>" />
            <table class="enter">
              <tr>
                <td>������</td>
                <td><input type="password" name="teampassword" /></td>
              </tr>
              <tr>
                <td colspan="2"><hr /></td>
              </tr>
              <tr>
                <td colspan="2" align="center"><input type="submit" name="teamlogin" value="����" /></td>
              </tr>
            </table>
          </form>
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
