<?php
die('page is to recreate');
require_once('./config/require.php');

if (@!$main_session)
    header('Location: '.ServerRoot.'message.php?error_code='.error_no_sessions_available);
    
  if (@$filter == 'fullfilled')
    $where = ' where fullfilled = 1 and status = 0 ';
  else
    $where = '';
    
  $error = false;
  $msg = '';
  
  //� ������� ������ ������������� �������
  if (@$teamid) {
    //�������� �� ������������ ����������
    $x = $teamid;
    settype($x, 'integer');
    if ($x==0) {
      $msg = '������� ������ ���� ��� ��������� ����������. ���������� �������� ������ ������� � ��������� �������.';
      $error = true;
    }
    
    //�������� �� �����������
    if (@$main_authorized == 1) {
      //�������� �� ������������� �������
      if (!$error) {
        $query = 'select teamname from teams where teamid='.$teamid;
        $r = mysql_query($query);
      
        if (mysql_num_rows($r) == 0) {
          $msg = '������� ����� ������������� �������.';
          $error = true;
        }
      }
    
      //�������!!!
      if (!$error) {
        $query = 'update teams set status=1 where fullfilled=1 and teamid='.$teamid;

        if (@!mysql_query($query)) {
          $error = true;
          $msg = '�� ������� �������� ������������� �����������.';
        }
      }
    } 
    //������������ �� �����������
    else {
      $error = true;
      $msg = '������ ������� �������� ������ �������������� �������������.';
    } //����� �������� �� �����������
  } 

  $query = 'select
    teamid,
    teamname,
    city,
    studyplace,
    status,
    qualified,
    language,
    fullfilled
  from
    teams'.$where;
  $r = mysql_query($query);
  $rowcount = mysql_num_rows($r);
  
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
          <h3>����������� ������</h3>
          <hr />
          <!--p>� ����� � ����������� ���������� �� �������, ����������� ����� ������ ���������� �� <b>8 ������ 2009 ����</b> ������������. </p>
          <p>��� ����, ����� �������� ������ ����� ������������������ �������, ��������� �� ������ "�������������" ����� � ��������� ������� � ������� ��� ������. </p>
          <p>� ������, ���� � ������ ������� �� ��������� ����, ����������� ��� ������� � �������� ������������� �������, � ����� "������" ����������� "����������� �� ���������". ������ ������������ �������, �� ����������� �������� �����, ������� �������� ��������� �������� ����� ��������� ������� ���������� ������ � ������������� �����������. ���� � ����� ������� ��� ������� ��� ������������, ���� � ����-�� �� ���������� ����������� ������� ��� ���, �������� �� ���� ����������� ������.</p>
          <p>� ������, ���� ������� ������ �� ����������� ������ � �������� ������������, ���� ������� ���� ������� ������ �����������, � ����� "�����" ����������� "������������ ��� ������� � �������". ����� �����, <b>� ���� �� 10 ������</b>, ���������� ������� ������������� ������� � ������� <a href="mailto:pupucya@mail.ru">��������� ����������</a>. � ���� ������ � ����� "�����" ����� ����������� "������� � ������� ������������".</p>
          <hr /-->
<?php
  //��������� ������
  if ($error):
?>
          <p class="red"><?=$msg?></p>
          <hr />          
<?php
  endif; //����� �������� �� ������
?>
          <!--p>����������� ����� ������ �������. ���� �� ��� �� ������ ������������������, ��������� ������ ��������� ���������� �� <a href="mailto:pupucya@mail.ru">pupucya@mail.ru</a></p-->
          <p><a href="editinfo.php">[������� ����� ������]</a></p>
<?php
  if ($rowcount > 0):
?>          
          <table>
            <tr>
              <th colspan="2">�������</th>
              <th>�����</th>
              <th>������� ���������</th>
              <th>����� ����������������</th>
              <th>������</th>
              <th>�����</th>
            </tr>
<?php
    for ($i=0; $i<$rowcount; $i++):
      $f = mysql_fetch_array($r);
      
      if ($i%2 == 1)
        $trclass = ' class="s"';
      else
        $trclass = '';
      if ($f['status'] == 1) {
        $tdstatusclass = ' class="green"';
        $tdstatus = '����������� ���������';
      }
      else if ($f['fullfilled'] == 1) {
        $tdstatusclass = ' class="blue"';
        $tdstatus = '���������� ���������';
      }
      else {
        $tdstatusclass = ' class="red"';
        $tdstatus = '����������� �� ���������';
      }
      
      if ($f['qualified'] == 2) {
        $tdqualifiedclass = ' class="green"';
        $tdqualified = '������� � ������� ������������';
      }
      else if ($f['qualified'] == 1) {
        $tdqualifiedclass = ' class="blue"';
        $tdqualified = '������������ ��� ������� � �������';
      }
      else {
        $tdqualifiedclass = '';
        $tdqualified = '����������';
      }
?>
            <tr<?=$trclass?>>        
<?php
      //if ($f['status'] > 0):
      if (false):
?>            
              <td>&nbsp;</td>
<?php
      else:
?>
              <td><sup><a href="teamlogin.php?teamid=<?=$f['teamid']?>">[�������������]</a></sup></td>
<?php
      endif;
?>
              <td><a href="viewinfo.php?teamid=<?=$f['teamid']?>"><?=$f['teamname']?></a></td>
              <td><?=$f['city']?></td>
              <td><?=$f['studyplace']?></td>
              <td><?=$f['language']?></td>
              <td<?=$tdstatusclass?>>
                <?=$tdstatus?>
<?php
      if (@$main_authorized == 1): //�������� �� �����������
        if (@$filter == 'fullfilled')
          $filter = '&filter=fullfilled';
        else
          $filter = '';
          
        if (($f['fullfilled'] == 1) && ($f['status'] < 1)):
?>
                &nbsp;<sup><a href="./register.php?teamid=<?=$f['teamid']?><?=$filter?>">[ok]</a></sup>
<?php
        endif;
      endif; //����� �������� �� �����������
?>                
              </td>
              <td<?=$tdqualifiedclass?>>
                <?=$tdqualified?>
<?php
      if (@$main_authorized == 1): //�������� �� �����������
        if ($f['qualified'] == 0): //��� �� ���������� �������
?>
                &nbsp;<sup><a href="./qualify.php?mode=invite&teamid=<?=$f['teamid']?>">[����������]</a></sup>
<?php        
        elseif ($f['qualified'] == 1): //������� ����������
?>
                &nbsp;<sup><a href="./qualify.php?mode=confirm&teamid=<?=$f['teamid']?>">[�����������]</a></sup>
<?php
        endif;
      endif; //����� �������� �� �����������
?>                
              </td>
            </tr>
<?php
    endfor;
?>          
          </table>
<?php
  else:
?>
          ��� �� ����� �������������������� �������.
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
