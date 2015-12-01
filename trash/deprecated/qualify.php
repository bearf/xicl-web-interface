<?php
require_once('./config/require.php');

if (@!$main_session)
    header('Location: '.ServerRoot.'message.php?error_code='.error_no_sessions_available);
    
  if (@$filter == 'fullfilled')
    $where = ' where fullfilled = 1 and status = 0 ';
  else
    $where = '';
    
  $error = false;
  $msg = '';

  //проверка авторизации
  if (!@$main_authorized) {
    $msg = true;
    $error = 'Данная функция доступна только авторизованным пользователям.';
  }
  //в запросе пришел идентификатор команды
  else if (@$teamid) {
    //проверка на корректность параметров
    $x = $teamid;
    settype($x, 'integer');
    if ($x==0) {
      $msg = 'Неверно указан один или несколько параметров. Попробуйте изменить строку запроса и повторить попытку.';
      $error = true;
    }
    
    //проверка на существование команды
    if (!$error) {
      $query = 'select teamname from teams where teamid='.$teamid;
      $r = mysql_query($query);
      
      if (mysql_num_rows($r) == 0) {
        $msg = 'Неверно задан идентификатор команды.';
        $error = true;
      }
    }
    
    //проверяем наличие режима в запросе
    if (!$error)
      if (!@$mode) {
        $msg = 'Не указан режим работы.';
        $error = true;
      }

    //строим строку запроса    
    if (!$error) 
      if ($mode == 'invite')
        $query = 'update teams set qualified=1 where teamid='.$teamid;
      elseif ($mode == 'confirm')
        $query = 'update teams set qualified=2 where teamid='.$teamid;
      else {
        $msg = 'Неизвестный режим работы.';
        $error = true;
      }
      
    //поехали!!!
    if (!$error) 
      if (@!mysql_query($query)) {
        $error = true;
        $msg = 'Не удалось провести операцию.';
      }
      else
        $msg = 'Операция успешно произведена.';
  } //конец проверки на наличие идентификатора команды
  //в запросе идентификатора нет
  else {
    $error = true;
    $msg = 'В запросе отсутствует идентификатор команды.';
  } //конец общего if

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
          <h2>&gt;&nbsp;служебный вход</h2>
          <form name="frmLogin" action="login.php" method="post">
            login&nbsp;<input type="text" name="login" maxlength="20" />
            пароль&nbsp;<input type="password" name="password" maxlength="20" />
            <input type="submit" name="loginbtn" value="войти" />
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
          <h3>Участие в турнире</h3>
          <hr />
          <?=$msg?>
          <hr />
          <a href="./register.php">[перейти к странице регистрации]</a>
        </div>
        <div class="cleaner">
        </div>
      </div>
    </div>
    <?php footer('tournament'); ?>
  </body>
</html>
