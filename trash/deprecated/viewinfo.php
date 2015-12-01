<?php
require_once('./config/require.php');

if (@!$main_session)
    header('Location: '.ServerRoot.'message.php?error_code='.error_no_sessions_available);
    
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
    
    //�������� �� ������������� �������
    if (!$error) {
      $query = 'select teamname from teams where teamid='.$teamid;
      $r = mysql_query($query);
      
      if (mysql_num_rows($r) == 0) {
        $msg = '������� ����� ������������� �������.';
        $error = true;
      }
    }
    
    $query = 'select * from teams where teamid='.$teamid;
    $r = mysql_query($query);
    $f = mysql_fetch_array($r);
      
    $teamname = $f['teamname']; $city = $f['city']; $studyplace = $f['studyplace']; $address = $f['address']; $phone = $f['phone']; $fax  = $f['fax']; $language = $f['language'];
    $contestteamid = $f['contestteamid']; 
    $contactname = $f['contactname']; $contactphone = $f['contactphone']; $contactmail = $f['contactmail']; 
    $headname = $f['headname']; $headpost = $f['headpost']; 
    $headpassportno  = $f['headpassportno']; $headpassportplace = $f['headpassportplace']; $headpassportdate = $f['headpassportdate']; 
    $headbirthdate = $f['headbirthdate']; $headaddress = $f['headaddress']; $headinn  = $f['headinn']; 
    $coachname = $f['coachname']; $coachpost = $f['coachpost']; 
    $coachpassportno  = $f['coachpassportno']; $coachpassportplace  = $f['coachpassportplace']; $coachpassportdate = $f['coachpassportdate']; 
    $coachbirthdate  = $f['coachbirthdate']; $coachaddress  = $f['coachaddress']; $coachinn  = $f['coachinn']; 
    $contestant1name = $f['contestant1name']; 
    $contestant1studyplace = $f['contestant1studyplace']; $contestant1faculty  = $f['contestant1faculty']; $contestant1classcourse  = $f['contestant1classcourse']; $contestant1age  = $f['contestant1age']; 
    $contestant1passportno  = $f['contestant1passportno']; $contestant1passportplace  = $f['contestant1passportplace']; $contestant1passportdate  = $f['contestant1passportdate']; 
    $contestant1birthdate = $f['contestant1birthdate']; $contestant1address  = $f['contestant1address']; $contestant1inn  = $f['contestant1inn']; 
    $contestant2name = $f['contestant2name']; 
    $contestant2studyplace = $f['contestant2studyplace']; $contestant2faculty  = $f['contestant2faculty']; $contestant2classcourse  = $f['contestant2classcourse']; $contestant2age  = $f['contestant2age']; 
    $contestant2passportno  = $f['contestant2passportno']; $contestant2passportplace  = $f['contestant2passportplace']; $contestant2passportdate  = $f['contestant2passportdate']; 
    $contestant2birthdate  = $f['contestant2birthdate']; $contestant2address  = $f['contestant2address']; $contestant2inn  = $f['contestant2inn']; 
    $contestant3name = $f['contestant3name']; 
    $contestant3studyplace = $f['contestant3studyplace']; $contestant3faculty  = $f['contestant3faculty']; $contestant3classcourse = $f['contestant3classcourse']; $contestant3age  = $f['contestant3age']; 
    $contestant3passportno  = $f['contestant3passportno']; $contestant3passportplace  = $f['contestant3passportplace']; $contestant3passportdate  = $f['contestant3passportdate']; 
    $contestant3birthdate  = $f['contestant3birthdate']; $contestant3address  = $f['contestant3address']; $contestant3inn  = $f['contestant3inn']; 
    $fullfilled = $f['fullfilled']; $status = $f['status']; $qualified = $f['qualified'];
    
    //�������� login � �������-�������. ��������� ������� ����������������, �� ������ ���� ������ ���������
    mysql_select_db(c_DBName);

    $query = 'select login from `user` where id='.$contestteamid;
    $r = mysql_query($query);
    $f = mysql_fetch_array($r);
    $contestlogin = $f['login'];

    mysql_select_db(t_DBName);
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
          <h3>���������� � �������</h3>
          <hr />
<?php
  //�������� ������ - ������ ���� �������� ���������
  if ($error):
?>
          <?=$msg?>
<?php
  else:
?> 
<h4 style="text-align:center">������ �� ������� � IX �������� ��������� ������� ���������
� ���������� ���������� �� ����������������
17 � 19 ������ 2009 ����</h4>        
          <hr />
<?php
    if (@$main_authorized == 1): //���������, ����� �� �������� ��������� ������
      if (($fullfilled == 1) && ($status < 1)): //������������� ������������
?>
          <a href="./register.php?teamid=<?=$teamid?>">[����������� ������������ ������]</a>
<?php
      endif;
      if ($qualified == 0): //����������� �� ������
?>
          &nbsp;<a href="./qualify.php?mode=invite&teamid=<?=$teamid?>">[����������]</a>
<?php
      elseif ($qualified == 1): //������������� �����������
?>
          &nbsp;<a href="./qualify.php?mode=confirm&teamid=<?=$teamid?>">[����������� �������]</a>
<?php
      endif;
?>
          <hr />
<?php      
    endif; //����� ��������, ����� �� �������� ��������� ������
?>          
            <table class="enter">
              <tr>
                <td>�������� �������</td>
                <td><?=$teamname?></td>
              </tr>
              <tr>
                <td>�����</td>
                <td><?=$city?></td>
              </tr>
              <tr>
                <td>������� ���������</td>
                <td><?=$studyplace?></td>
              </tr>
              <tr>
                <td>���� ����������������</td>
                <td><?=$language?></td>
              </tr>
<?php
    if (@$main_authorized == 1):
?>              
              <tr>
                <td>login � contest-�������</td>
                <td><a href="./contest/userinfo.php?userid=<?=$contestteamid?>"><?=$contestlogin?></a></td>
              </tr>
              <tr>
                <td colspan="2"><hr /></td>
              </tr>
              <tr>
                <td>����� �������� ���������</td>
                <td><?=$address?></td>
              </tr>
              <tr>
                <td>�������</td>
                <td><?=$phone?></td>
              </tr>
              <tr>
                <td>����</td>
                <td><?=$fax?></td>
              </tr>
              <tr>
                <td colspan="2"><hr /></td>
              </tr>
              <tr>
                <td>���������� ����</td>
                <td><?=$contactname?></td>
              </tr>
              <tr>
                <td>���������� �������</td>
                <td><?=$contactphone?></td>
              </tr>
              <tr>
                <td>���������� e-mail</td>
                <td><?=$contactmail?></td>
              </tr>
              <tr>
                <td colspan="2"><hr /></td>
              </tr>
<?php
    endif;
?>              
              <tr>
                <td>������������ �������</td>
                <td><?=$headname?></td>
              </tr>
<?php
    if (@$main_authorized == 1):
?>              
              <tr>
                <td>���������</td>
                <td><?=$headpost?></td>
              </tr>
              <tr>
                <td>����� � ����� ��������</td>
                <td><?=$headpassportno?></td>
              </tr>
              <tr>
                <td>��� ����� �������</td>
                <td><?=$headpassportplace?></td>
              </tr>
              <tr>
                <td>����� ����� �������</td>
                <td><?=$headpassportdate?></td>
              </tr>
              <tr>
                <td>���� ��������</td>
                <td><?=$headbirthdate?></td>
              </tr>
              <tr>
                <td>�����</td>
                <td><?=$headaddress?></td>
              </tr>
              <tr>
                <td>���</td>
                <td><?=$headinn?></td>
              </tr>
              <tr>
                <td colspan="2"><hr /></td>
              </tr>
<?php
    endif;
?>              
              <tr>
                <td>������ �������</td>
                <td><?=$coachname?></td>
              </tr>
<?php
    if (@$main_authorized == 1):
?>              
              <tr>
                <td>���������</td>
                <td><?=$coachpost?></td>
              </tr>
              <tr>
                <td>����� � ����� ��������</td>
                <td><?=$coachpassportno?></td>
              </tr>
              <tr>
                <td>��� ����� �������</td>
                <td><?=$coachpassportplace?></td>
              </tr>
              <tr>  
                <td>����� ����� �������</td>
                <td><?=$coachpassportdate?></td>
              </tr>
              <tr>
                <td>���� ��������</td>
                <td><?=$coachbirthdate?></td>
              </tr>
              <tr>
                <td>�����</td>
                <td><?=$coachaddress?></td>
              </tr>
              <tr>
                <td>���</td>
                <td><?=$coachinn?></td>
              </tr>
              <tr>
                <td colspan="2"><hr /></td>
              </tr>
<?php
    endif;
?>              
              <tr>
                <td>������ ��������</td>
                <td><?=$contestant1name?></td>
              </tr>
<?php
    if (@$main_authorized == 1):
?>              
              <tr>
                <td>����� �����</td>
                <td><?=$contestant1studyplace?></td>
              </tr>
              <tr>
                <td>���������</td>
                <td><?=$contestant1faculty?></td>
              </tr>
              <tr>
                <td>�����/����</td>
                <td><?=$contestant1classcourse?></td>
              </tr>
              <tr>
                <td>�������</td>
                <td><?=$contestant1age?></td>
              </tr>
              <tr>
                <td>����� � ����� ��������</td>
                <td><?=$contestant1passportno?></td>
              </tr>
              <tr>
                <td>��� ����� �������</td>
                <td><?=$contestant1passportplace?></td>
              </tr>
              <tr>
                <td>����� ����� �������</td>
                <td><?=$contestant1passportdate?></td>
              </tr>
              <tr>
                <td>���� ��������</td>
                <td><?=$contestant1birthdate?></td>
              </tr>
              <tr>
                <td>�����</td>
                <td><?=$contestant1address?></td>
              </tr>
              <tr>
                <td>���</td>
                <td><?=$contestant1inn?></td>
              </tr>
              <tr>
                <td colspan="2"><hr /></td>
              </tr>
<?php
    endif;
?>              
              <tr>
                <td>������ ��������</td>
                <td><?=$contestant2name?></td>
              </tr>
<?php
    if (@$main_authorized == 1):
?>              
              <tr>
                <td>����� �����</td>
                <td><?=$contestant2studyplace?></td>
              </tr>
              <tr>
                <td>���������</td>
                <td><?=$contestant2faculty?></td>
              </tr>
              <tr>
                <td>�����/����</td>
                <td><?=$contestant2classcourse?></td>
              </tr>
              <tr>
                <td>�������</td>
                <td><?=$contestant2age?></td>
              </tr>
              <tr>
                <td>����� � ����� ��������</td>
                <td><?=$contestant2passportno?></td>
              </tr>
              <tr>
                <td>��� ����� �������</td>
                <td><?=$contestant2passportplace?></td>
              </tr>
              <tr>
                <td>����� ����� �������</td>
                <td><?=$contestant2passportdate?></td>
              </tr>
              <tr>
                <td>���� ��������</td>
                <td><?=$contestant2birthdate?></td>
              </tr>
              <tr>
                <td>�����</td>
                <td><?=$contestant2address?></td>
              </tr>
              <tr>
                <td>���</td>
                <td><?=$contestant2inn?></td>
              </tr>
              <tr>
                <td colspan="2"><hr /></td>
              </tr>
<?php
    endif;
?>              
              <tr>
                <td>������ ��������</td>
                <td><?=$contestant3name?></td>
              </tr>
<?php
    if (@$main_authorized == 1):
?>              
              <tr>
                <td>����� �����</td>
                <td><?=$contestant3studyplace?></td>
              </tr>
              <tr>
                <td>���������</td>
                <td><?=$contestant3faculty?></td>
              </tr>
              <tr>
                <td>�����/����</td>
                <td><?=$contestant3classcourse?></td>
              </tr>
              <tr>
                <td>�������</td>
                <td><?=$contestant3age?></td>
              </tr>
              <tr>
                <td>����� � ����� ��������</td>
                <td><?=$contestant3passportno?></td>
              </tr>
              <tr>
                <td>��� ����� �������</td>
                <td><?=$contestant3passportplace?></td>
              </tr>
              <tr>
                <td>����� ����� �������</td>
                <td><?=$contestant3passportdate?></td>
              </tr>
              <tr>
                <td>���� ��������</td>
                <td><?=$contestant3birthdate?></td>
              </tr>
              <tr>
                <td>�����</td>
                <td><?=$contestant3address?></td>
              </tr>
              <tr>
                <td>���</td>
                <td><?=$contestant3inn?></td>
              </tr>
              <tr>
                <td colspan="2"><hr /></td>
              </tr>
<?php
    endif;
?>              
            </table>
<?php
  endif; //����� �������� �� ���������� ������
?>
        </div>
        <div class="cleaner">
        </div>
      </div>
    </div>
    <?php footer('tournament'); ?>
  </body>
</html>
