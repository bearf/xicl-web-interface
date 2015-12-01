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
        $rowcount = mysql_num_rows($r);

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
    <title>Официальный сайт Турнира по программированию ОАО "ICL-КПО ВС"</title>
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
          alt="ICL КПО-ВС" />
        <h2>&gt;&nbsp;меню</h2>
        <ul id="menu">
          <li><a href="./index.php">о турнире</a></li>
          <li><a href="./news.php">новости</a></li>
          <li><a href="./timetable.php">расписание</a></li>
          <li><a href="./rules.php">правила</a></li>
          <li><a href="./register.php">регистрация команд</a></li>
          <li><a href="./history.php">архив турниров</a></li>
          <li><a href="./public.php">пресса</a></li>
          <li><a href="./faq.php">вопрос-ответ</a></li>
          <li><a href="./partners.php">наши партнеры</a></li>
        </ul>
        <h2>&gt;&nbsp;contest</h2>
        <table class="small">
          <tr>
            <td>участников</td><td class="right"><?=$user_count?></td>
          </tr>
          <tr>
            <td>задач</td><td class="right"><?=$task_count?></td>
          </tr>
          <tr>
            <td>посылок</td><td class="right"><?=$submit_count?></td>
          </tr>
        </table>
        <p class="c">
          <a href="./contest">[перейти]</a>
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
          <h1>&gt;&nbsp;Турнир по программированию ОАО "ICL-КПО ВС"</h1>
        </div>
        <div id="login">
<?php
  if (@$main_authorized == 1):
?>
          <h2>&gt;&nbsp;выход из системы</h2>
          <form name="frmLogout" action="logout.php" method="post">
            Пользователь: <?=$main_curlogin?>
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
          <h3>Невозможно отобразить новости</h3>
          <?=$msg?>
<?php
  else:
?>
          <h3>Новости</h3>
          <hr />
<?php
    //новостей нет
    if ($rowcount == 0):
?>
          Нет новостей.
<?php    
    else:
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
                    [Посл. 20]
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
    endif; //конец обработки отсутствия новостей
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
