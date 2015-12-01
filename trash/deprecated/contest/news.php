<?php
  require_once("const.php");
  require_once("sessions.php");
  require_once("mysql.php");

  $r = mysql_query("select now() as time");
  $f = mysql_fetch_array($r);
  $srvtime = $f["time"];
  
  $error = false;
  $msg = '';

  if (@!$curcontest)
    header('Location: '.ServerRoot.'message.php?error_code='.error_no_sessions_available_code);
  else {
    $q = 'SELECT 
            Name
          FROM
            Cntest
          WHERE
            start<=NOW() and finish>=NOW() and 
            ContestID='.$curcontest;

    $r=@mysql_query($q);

    if (mysql_error() == "") 
      if (mysql_num_rows($r) > 0) {
        $f = mysql_fetch_array($r);
        $contestname = $f["Name"];
      }
      else 
        $contestname = 'Нет активного турнира.';
    else {
      $error = true;
      $msg = 'Произошла ошибка при выполнении запроса.';
    }
  }

  //если запрещен просмотр информации
  //и мы не в админском режиме - делаем перенаправление на страницу с сообщением
  if (!_permission_allow_view_news && !(@$main_authorized == 1))
    header('Location: '.ServerRoot.'message.php?warning_code='.warning_no_rights_for_this_operation_code);

  $rowcount = 0;

  //нет параметров
  if ((!@$newsid)&&(!@$top)) {
    $r = mysql_query("SELECT id, date, caption, topic FROM news ORDER BY id DESC LIMIT 10;");
    $rowcount = mysql_num_rows($r);
  }
  else {
    //проверяем входные параметры
    if (@$newsid) {
      $x = $newsid;
      settype($x, "integer");
      if ($x==0) {
        $error = true;
        $msg = 'Неверно указан один или несколько параметров. Попробуйте изменить строку запроса и повторить попытку.';
      }
    }
    if (@$top) {
      $x = $top;
      settype($x, "integer");
      if ($x==0) {
        $error = true;
        $msg = 'Неверно указан один или несколько параметров. Попробуйте изменить строку запроса и повторить попытку.';
      }
    }

    //вытаскиваем данные

    //есть идентификатор новости
    if (!$error)
      if (@$newsid) {
        $r=mysql_query("SELECT date, caption, text FROM news WHERE id=$newsid;");

        if (mysql_num_rows($r) != 1) {
        	$error = true;
          $msg = 'Неверно задан идентификатор новости. Попробуйте изменить параметры в строке запроса.';
        }
      }
      //есть параметр top
      else {
        $r = mysql_query("SELECT id, date, caption FROM news ORDER BY id DESC");
        $rowcount = mysql_num_rows($r);

        if (($top > $rowcount) || ($top < 1)) {
          $error = true;
          $msg = 'Неверно задан параметр top. Попробуйте изменить параметры в строке запроса.';
        }
      } //конец проверки top
    } //конец обработки параметров
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=WINDOWS-1251" />
    <meta name="Author" content="Игорь Зиновьев" />
    <meta name="Keywords" content="олимпиада, программирование, информатика, задача, ICL, турнир" />
    <title>&gt; Архив олимпиадных задач ОАО "ICL-КПО ВС"</title>
    <link href="contest.css" type="text/css" rel="stylesheet" />
  </head>
  <body>
    <div id="main">
      <div id="left">
        <img
          id="logo"
          src="logo.gif"
          width="180"
          height="100"
          alt="ICL КПО-ВС" />
<?php
  //включаем меню
  require_once('menu.php');
?>        
        <h2>&gt;&nbsp;текущий турнир</h2>
        <p class="c"><a href="./contest.php" class="white" title="сменить текущий турнир"><?=$contestname?></a></p>
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
          <h1>&gt;&nbsp;<?=_system_site_title?></h1>
        </div>
        <div id="login">
<?php
  if (@$authorized == 1):
?>
          <h2>&gt;&nbsp;выход из системы</h2>
          <form name="frmLogout" action="logout.php" method="post">
            Пользователь: <?=$curnickname?>
            <input type="submit" name="logoutbtn" value="выйти" />
          </form>
<?php
  else:
?>
          <h2>&gt;&nbsp;вход в систему</h2>
          <form name="frmLogin" action="login.php" method="post">
            login&nbsp;<input type="text" name="login" maxlength="20" />
            пароль&nbsp;<input type="password" name="password" maxlength="20" />
            <input type="submit" name="loginbtn" value="войти" />
          </form>
<?php
  endif;
?>
        </div>
<?php        
  //включаем верхнюю информационную строку
  require_once('stuff.php');
?>        
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
          <h3>Невозможно отобразить новости</h3>
          <?=$msg?>
<?php
  else:
?>
          <h3>Новости</h3>
<?php
    //нет параметров
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
      endfor; //конец цикла по новостям
?>
            <p class="c">
              <a href="news.php?top=1">[Все новости]</a>
            </p>
<?php
    //параметры есть
    else:
      //задан идентификатор новости
      if (@$newsid):
        $f=mysql_fetch_array($r);
?>
            <h4><?=$f["date"]?>&nbsp;&nbsp;&nbsp;<?=$f["caption"]?></h4>
            <hr />
            <?=$f["text"]?>
            <hr />
            <p class="c">
              <a href="./news.php">[На главную страницу новостей]</a>
            </p>
<?php
      //задан параметр top
      else:
?>
            <table>
<?php
        //перебираем начало новостной ленты
        for ($i=1; $i<$top; $i++)
          $f=mysql_fetch_array($r);

        //цикл по новостной ленте
        for ($i=$top; $i<=min(array($top+20-1, $rowcount)); $i++):
          $f=mysql_fetch_array($r);
?>
             <tr><td>
               <?=$f["date"]?>&nbsp;&nbsp;&nbsp;<a href="news.php?newsid=<?=$f["id"]?>"><?=$f["caption"]?></a>
             </td></tr>
<?php
        endfor; //конец цикла по новостной ленте

        //рисуем ссылки на остальные новости
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
                    [След. 20]
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
                    [Посл]
                  </a>
                </td>
                <td style="width:33%">
<?php
        if ($next<$rowcount+1):
?>
                  <a href=\"news.php?top=<?=$next?>\">
                    [Пред. 20]
                  </a>
<?php
        else:
?>
                  &nbsp;
<?php
        endif; //окончание рисования ссылок на остальные новости
?>
                </td>
              </tr>
            </table>
<?php
      endif; //окончание обработки параметра top
    endif; //окончание обработчки параметров
  endif; //конец обработки $error
?>
        </div>
        <div class="cleaner">
        </div>
      </div>
    </div>
    <div id="footer">
      <div id="feature">
        вопросы и пожелания по содержанию и оформлению сайта отправляйте на: <a href="mailto:teddybear@icl.kazan.ru" class="white">teddybear@icl.kazan.ru</a>
      </div>
    </div>
  </body>
</html>