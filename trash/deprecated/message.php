<?php
require_once("./config/messages.php");

  if (@$error_code) {
    $title = "&gt; Ошибка: внимательно прочитайте следующее сообщение";
    $header = "Произошла ошибка при обращении к системе";
  }
  elseif (@$warning_code) {
    $title = "&gt; Предупреждение: внимательно прочитайте следующее сообщение";
    $header = "Внимание";
  }
  else {
    $title = "&gt; Архив олимпиадных задач";
    $header = "Нет сообщений";
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=WINDOWS-1251" />
    <meta name="Author" content="Игорь Зиновьев" />
    <meta name="Keywords" content="олимпиада, программирование, информатика, задача, ICL, турнир" />
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
          alt="ICL КПО-ВС"/>
        <h2>&gt;&nbsp;нет данных</h2>
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
          <p>Не удалось извлечь из базы данных информацию о текущем турнире.</p>
<?php
        break;
      }
      case error_no_mysql_connection: {
?>
          <p>Не удалось установить соединение с базой данных. Это могло произойти по одной из следующих причин:</p>
          <ul>
            <li>Сайт в настоящее время перегружен.</li>
            <li>В настоящее время производится обновление базы данных и/или сайта.</li>
          </ul>
          <p>Пожалуйста, повторите попытку запроса позже.</p>
<?php
        break;
      }
      case error_no_sessions_available: {
?>
          <p>Некорректно работает механизм сессий. Пожалуйста, проверьте, включена ли в вашем браузере поддержка cookies.</p>
<?php
        break;
      }
      default: {
?>
          <p>Идентификатор сообщения об ошибке неизвестен. Пожалуйста, повторите попытку запроса.</p>
<?php
      }
    endswitch;
  elseif (@$warning_code):
    switch ($warning_code):
      case warning_registration_is_closed: {
?>
          <p>Регистрация новых команд <b>на сайте</b> закрыта. Если по каким-то причинам вы не успели зарегистрироваться, Вам следует отправить электронное письмо <a href="mailto:pupucya@mail.ru">Екатерине Степановой</a>.</p>
<?php
        break;
      }
      default: {
?>
          <p>Идентификатор сообщения о предупреждении неизвестен. Пожалуйста, повторите попытку запроса.</p>
<?php
      }
    endswitch;
  else:
?>
          <p>Не получен идентификатор сообщения. Пожалуйста, повторите попытку запроса.</p>
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
