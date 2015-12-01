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
  
  //в запросе пришел идентификатор команды
  if (@$teamid) {
    //проверка на корректность параметров
    $x = $teamid;
    settype($x, 'integer');
    if ($x==0) {
      $msg = 'Неверно указан один или несколько параметров. Попробуйте изменить строку запроса и повторить попытку.';
      $error = true;
    }
    
    //проверка на авторизацию
    if (@$main_authorized == 1) {
      //проверка на существование команды
      if (!$error) {
        $query = 'select teamname from teams where teamid='.$teamid;
        $r = mysql_query($query);
      
        if (mysql_num_rows($r) == 0) {
          $msg = 'Неверно задан идентификатор команды.';
          $error = true;
        }
      }
    
      //поехали!!!
      if (!$error) {
        $query = 'update teams set status=1 where fullfilled=1 and teamid='.$teamid;

        if (@!mysql_query($query)) {
          $error = true;
          $msg = 'Не удалось провести подтверждение регистрации.';
        }
      }
    } 
    //пользователь не авторизован
    else {
      $error = true;
      $msg = 'Данная функция доступна только авторизованным пользователям.';
    } //конец проверки на авторизацию
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
          <h3>Регистрация команд</h3>
          <hr />
          <!--p>В связи с длительными проблемами на сервере, регистрация новых команд проводится до <b>8 апреля 2009 года</b> включительно. </p>
          <p>Для того, чтобы изменить данные ранее зарегистрированной команды, перейдите по ссылке "редактировать" рядом с названием команды и введите Ваш пароль. </p>
          <p>В случае, если в анкете команды не заполнены поля, необходимые для участия в основных соревнованиях турнира, в графе "статус" указывается "Регистрация не завершена". Статус выставляется вручную, по результатам проверки анкет, поэтому возможна некоторая задержка между моментами полного заполнения данных и подтверждения регистрации. Если у Вашей команды нет тренера или руководителя, либо у кого-то из участников отсутствует паспорт или ИНН, сообщите об этом электронной почтой.</p>
          <p>В случае, если команда прошла по результатам отбора в основные соревнования, либо команде было выслано личное приглашение, в графе "отбор" указывается "Приглашается для участия в турнире". После этого, <b>в срок до 10 апреля</b>, необходимо выслать подтверждение участия в турнире <a href="mailto:pupucya@mail.ru">Екатерине Степановой</a>. В этом случае в графе "отбор" будет проставлено "Участие в турнире подтверждено".</p>
          <hr /-->
<?php
  //случилась ошибка
  if ($error):
?>
          <p class="red"><?=$msg?></p>
          <hr />          
<?php
  endif; //конец проверки на ошибку
?>
          <!--p>Регистрация новых команд закрыта. Если вы еще не успели зарегистрироваться, отправьте письмо Екатерине Степановой на <a href="mailto:pupucya@mail.ru">pupucya@mail.ru</a></p-->
          <p><a href="editinfo.php">[создать новую запись]</a></p>
<?php
  if ($rowcount > 0):
?>          
          <table>
            <tr>
              <th colspan="2">команда</th>
              <th>город</th>
              <th>учебное заведение</th>
              <th>среда программирования</th>
              <th>статус</th>
              <th>отбор</th>
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
        $tdstatus = 'Регистрация завершена';
      }
      else if ($f['fullfilled'] == 1) {
        $tdstatusclass = ' class="blue"';
        $tdstatus = 'Информация заполнена';
      }
      else {
        $tdstatusclass = ' class="red"';
        $tdstatus = 'Регистрация не завершена';
      }
      
      if ($f['qualified'] == 2) {
        $tdqualifiedclass = ' class="green"';
        $tdqualified = 'Участие в турнире подтверждено';
      }
      else if ($f['qualified'] == 1) {
        $tdqualifiedclass = ' class="blue"';
        $tdqualified = 'Приглашается для участия в турнире';
      }
      else {
        $tdqualifiedclass = '';
        $tdqualified = 'Неизвестно';
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
              <td><sup><a href="teamlogin.php?teamid=<?=$f['teamid']?>">[редактировать]</a></sup></td>
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
      if (@$main_authorized == 1): //проверка на авторизацию
        if (@$filter == 'fullfilled')
          $filter = '&filter=fullfilled';
        else
          $filter = '';
          
        if (($f['fullfilled'] == 1) && ($f['status'] < 1)):
?>
                &nbsp;<sup><a href="./register.php?teamid=<?=$f['teamid']?><?=$filter?>">[ok]</a></sup>
<?php
        endif;
      endif; //конец проверки на авторизацию
?>                
              </td>
              <td<?=$tdqualifiedclass?>>
                <?=$tdqualified?>
<?php
      if (@$main_authorized == 1): //проверка на авторизацию
        if ($f['qualified'] == 0): //еще не выставлена отметка
?>
                &nbsp;<sup><a href="./qualify.php?mode=invite&teamid=<?=$f['teamid']?>">[пригласить]</a></sup>
<?php        
        elseif ($f['qualified'] == 1): //отметка выставлена
?>
                &nbsp;<sup><a href="./qualify.php?mode=confirm&teamid=<?=$f['teamid']?>">[подтвердить]</a></sup>
<?php
        endif;
      endif; //конец проверки на авторизацию
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
          Нет ни одной зарегистрировавшейся команды.
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
