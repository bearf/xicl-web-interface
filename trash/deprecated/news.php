<?php
  require_once("const.php");
  require_once("sessions.php");
  require_once("mysql.php");

  $r = mysql_query("select now() as time");
  $f = mysql_fetch_array($r);
  $srvtime = $f["time"];
  
  mysql_select_db(c_DBName);

  $r = mysql_query("select count(*) as cnt from user");
  $f = mysql_fetch_array($r);
  $user_count = $f["cnt"];
  $r = mysql_query("select count(*) as cnt from task");
  $f = mysql_fetch_array($r);
  $task_count = $f["cnt"];
  $r = mysql_query("select count(*) as cnt from submit");
  $f = mysql_fetch_array($r);
  $submit_count = $f["cnt"];

  mysql_select_db(t_DBName);

  if (@!$main_session)
    header('Location: '.ServerRoot.'message.php?error_code='.error_no_sessions_available);

  $error = false;
  $msg = '';
  $rowcount = 0;

  //��� ����������
  if ((!@$newsid)&&(!@$top)) {
    $r = mysql_query("SELECT id, date, caption, topic FROM news ORDER BY id DESC LIMIT 10;");
    $rowcount = mysql_num_rows($r);
  }
  else {
    //��������� ������� ���������
    if (@$newsid) {
      $x = $newsid;
      settype($x, "integer");
      if ($x==0) {
        $error = true;
        $msg = '������� ������ ���� ��� ��������� ����������. ���������� �������� ������ ������� � ��������� �������.';
      }
    }
    if (@$top) {
      $x = $top;
      settype($x, "integer");
      if ($x==0) {
        $error = true;
        $msg = '������� ������ ���� ��� ��������� ����������. ���������� �������� ������ ������� � ��������� �������.';
      }
    }

    //����������� ������

    //���� ������������� �������
    if (!$error)
      if (@$newsid) {
        $r=mysql_query("SELECT date, caption, text FROM news WHERE id=$newsid;");
        $rowcount = mysql_num_rows($r);

        if (mysql_num_rows($r) != 1) {
        	$error = true;
          $msg = '������� ����� ������������� �������. ���������� �������� ��������� � ������ �������.';
        }
      }
      //���� �������� top
      else {
        $r = mysql_query("SELECT id, date, caption FROM news ORDER BY id DESC");
        $rowcount = mysql_num_rows($r);

        if (($top > $rowcount) || ($top < 1)) {
          $error = true;
          $msg = '������� ����� �������� top. ���������� �������� ��������� � ������ �������.';
        }
      } //����� �������� top
    } //����� ��������� ����������
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
        <h2>&gt;&nbsp;����</h2>
        <ul id="menu">
          <li><a href="./index.php">� �������</a></li>
          <li><a href="./news.php">�������</a></li>
          <li><a href="./timetable.php">����������</a></li>
          <li><a href="./rules.php">�������</a></li>
          <li><a href="./register.php">����������� ������</a></li>
          <li><a href="./history.php">����� ��������</a></li>
          <li><a href="./public.php">������</a></li>
          <li><a href="./faq.php">������-�����</a></li>
          <li><a href="./partners.php">���� ��������</a></li>
        </ul>
        <h2>&gt;&nbsp;contest</h2>
        <table class="small">
          <tr>
            <td>����������</td><td class="right"><?=$user_count?></td>
          </tr>
          <tr>
            <td>�����</td><td class="right"><?=$task_count?></td>
          </tr>
          <tr>
            <td>�������</td><td class="right"><?=$submit_count?></td>
          </tr>
        </table>
        <p class="c">
          <a href="./contest">[�������]</a>
        </p>
        <!--p class="c">
          <a href="http://validator.w3.org/check?uri=referer">
            <img
              src="http://www.w3.org/Icons/valid-xhtml10"
              alt="Valid XHTML 1.0 Transitional"
              height="31"
              width="88" />
          </a>
        </p-->
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
          <h2>&gt;&nbsp;���� � �������</h2>
          <form name="frmLogin" action="login.php" method="post">
            login&nbsp;<input type="text" name="login" maxlength="20" />
            ������&nbsp;<input type="password" name="password" maxlength="20" />
            <input type="submit" name="loginbtn" value="�����" />
          </form>
<?php
  endif;
?>
        </div>
        <div id="stuff">
          <?=$srvtime?>
        </div>
        <div id="content">
          <img
            class="content"
            src="content.gif"
            alt=""
            width="1"
            height="400"
            />
<?php
  if ($error):
?>
          <h3>���������� ���������� �������</h3>
          <?=$msg?>
<?php
  else:
?>
          <h3>�������</h3>
          <hr />
<?php
    //�������� ���
    if ($rowcount == 0):
?>
          ��� ��������.
<?php    
    else:
      //��� ����������
      if ((!@$newsid)&&(!@$top)):
        for ($i=0; $i<$rowcount; $i++):
          $f=mysql_fetch_array($r);
?>
            <p>
              <a href="news.php?newsid=<?=$f["id"]?>"><?=$f["date"]?>&nbsp;&nbsp;&nbsp;<?=$f["caption"]?></a>
            </p>
            <p><?=$f["topic"]?>...</p>
            <hr />
<?php
        endfor; //����� ����� �� ��������
?>
            <p class="c">
              <a href="news.php?top=1">[��� �������]</a>
            </p>
<?php
      //��������� ����
      else:
        //����� ������������� �������
        if (@$newsid):
          $f=mysql_fetch_array($r);
?>
            <h4><?=$f["date"]?>&nbsp;&nbsp;&nbsp;<?=$f["caption"]?></h4>
            <hr />
            <?=$f["text"]?>
            <hr />
            <p class="c">
              <a href="./news.php">[�� ������� �������� ��������]</a>
            </p>
<?php
        //����� �������� top
        else:
?>
            <table>
<?php
          //���������� ������ ��������� �����
          for ($i=1; $i<$top; $i++)
            $f=mysql_fetch_array($r);

          //���� �� ��������� �����
          for ($i=$top; $i<=min(array($top+20-1, $rowcount)); $i++):
            $f=mysql_fetch_array($r);
?>
             <tr><td>
               <?=$f["date"]?>&nbsp;&nbsp;&nbsp;<a href="news.php?newsid=<?=$f["id"]?>"><?=$f["caption"]?></a>
             </td></tr>
<?php
          endfor; //����� ����� �� ��������� �����

          //������ ������ �� ��������� �������
          $prev=$top - 20;
          $next=$top + 20;
?>
            </table>
            <hr />

            <table class="links">
              <tr>
                <td style="width:33%">
<?php     
          if ($prev>0):
?>
                  <a href=\"news.php?top=<?=$prev?>\">
                    [����. 20]
                  </a>
<?php
          else:
?>
                  &nbsp;
<?php
          endif;
?>
                </td>
                <td style="width:34%">
                  <a href="news.php?top=1">
                    [����. 20]
                  </a>
                </td>
                <td style="width:33%">
<?php
          if ($next<$rowcount+1):
?>
                  <a href=\"news.php?top=<?=$next?>\">
                    [����. 20]
                  </a>
<?php
          else:
?>
                  &nbsp;
<?php
          endif; //��������� ��������� ������ �� ��������� �������
?>
                </td>
              </tr>
            </table>
<?php
        endif; //��������� ��������� ��������� top
      endif; //��������� ���������� ����������
    endif; //����� ��������� ���������� ��������
  endif; //����� ��������� $error
?>
        </div>
        <div class="cleaner">
        </div>
      </div>
    </div>
    <div id="footer">
      <div id="feature">
        ������� � ��������� �� ���������� � ���������� ����� ����������� ��: <a href="mailto:teddybear@icl.kazan.ru" class="white">teddybear@icl.kazan.ru</a>
      </div>
    </div>
  </body>
</html>
