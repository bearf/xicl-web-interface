<?php
require_once('./config/require.php');

if (@!$main_session)
    header('Location: '.ServerRoot.'message.php?error_code='.error_no_sessions_available);
    
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
    
    //проверка на существование команды
    if (!$error) {
      $query = 'select teamname from teams where teamid='.$teamid;
      $r = mysql_query($query);
      
      if (mysql_num_rows($r) == 0) {
        $msg = 'Неверно задан идентификатор команды.';
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
    
    //получаем login в контест-системе. поскольку команда зарегистрирована, он должен быть указан правильно
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
          <h3>Информация о команде</h3>
          <hr />
<?php
  //возникли ошибки - значит надо показать сообщение
  if ($error):
?>
          <?=$msg?>
<?php
  else:
?> 
<h4 style="text-align:center">Заявка на участие в IX открытом командном турнире студентов
и школьников Татарстана по программированию
17 – 19 апреля 2009 года</h4>        
          <hr />
<?php
    if (@$main_authorized == 1): //проверяем, можно ли выводить системные ссылки
      if (($fullfilled == 1) && ($status < 1)): //подтверждение корректности
?>
          <a href="./register.php?teamid=<?=$teamid?>">[подтвердить корректность заявки]</a>
<?php
      endif;
      if ($qualified == 0): //приглашение на турнир
?>
          &nbsp;<a href="./qualify.php?mode=invite&teamid=<?=$teamid?>">[пригласить]</a>
<?php
      elseif ($qualified == 1): //подтверждение приглашения
?>
          &nbsp;<a href="./qualify.php?mode=confirm&teamid=<?=$teamid?>">[подтвердить участие]</a>
<?php
      endif;
?>
          <hr />
<?php      
    endif; //конец проверки, можно ли выводить системные ссылки
?>          
            <table class="enter">
              <tr>
                <td>название команды</td>
                <td><?=$teamname?></td>
              </tr>
              <tr>
                <td>город</td>
                <td><?=$city?></td>
              </tr>
              <tr>
                <td>учебное заведение</td>
                <td><?=$studyplace?></td>
              </tr>
              <tr>
                <td>язык программирования</td>
                <td><?=$language?></td>
              </tr>
<?php
    if (@$main_authorized == 1):
?>              
              <tr>
                <td>login в contest-системе</td>
                <td><a href="./contest/userinfo.php?userid=<?=$contestteamid?>"><?=$contestlogin?></a></td>
              </tr>
              <tr>
                <td colspan="2"><hr /></td>
              </tr>
              <tr>
                <td>адрес учебного заведения</td>
                <td><?=$address?></td>
              </tr>
              <tr>
                <td>телефон</td>
                <td><?=$phone?></td>
              </tr>
              <tr>
                <td>факс</td>
                <td><?=$fax?></td>
              </tr>
              <tr>
                <td colspan="2"><hr /></td>
              </tr>
              <tr>
                <td>контактное лицо</td>
                <td><?=$contactname?></td>
              </tr>
              <tr>
                <td>контактный телефон</td>
                <td><?=$contactphone?></td>
              </tr>
              <tr>
                <td>контактный e-mail</td>
                <td><?=$contactmail?></td>
              </tr>
              <tr>
                <td colspan="2"><hr /></td>
              </tr>
<?php
    endif;
?>              
              <tr>
                <td>руководитель команды</td>
                <td><?=$headname?></td>
              </tr>
<?php
    if (@$main_authorized == 1):
?>              
              <tr>
                <td>должность</td>
                <td><?=$headpost?></td>
              </tr>
              <tr>
                <td>серия и номер паспорта</td>
                <td><?=$headpassportno?></td>
              </tr>
              <tr>
                <td>кем выдан паспорт</td>
                <td><?=$headpassportplace?></td>
              </tr>
              <tr>
                <td>когда выдан паспорт</td>
                <td><?=$headpassportdate?></td>
              </tr>
              <tr>
                <td>дата рождения</td>
                <td><?=$headbirthdate?></td>
              </tr>
              <tr>
                <td>адрес</td>
                <td><?=$headaddress?></td>
              </tr>
              <tr>
                <td>ИНН</td>
                <td><?=$headinn?></td>
              </tr>
              <tr>
                <td colspan="2"><hr /></td>
              </tr>
<?php
    endif;
?>              
              <tr>
                <td>тренер команды</td>
                <td><?=$coachname?></td>
              </tr>
<?php
    if (@$main_authorized == 1):
?>              
              <tr>
                <td>должность</td>
                <td><?=$coachpost?></td>
              </tr>
              <tr>
                <td>серия и номер паспорта</td>
                <td><?=$coachpassportno?></td>
              </tr>
              <tr>
                <td>кем выдан паспорт</td>
                <td><?=$coachpassportplace?></td>
              </tr>
              <tr>  
                <td>когда выдан паспорт</td>
                <td><?=$coachpassportdate?></td>
              </tr>
              <tr>
                <td>дата рождения</td>
                <td><?=$coachbirthdate?></td>
              </tr>
              <tr>
                <td>адрес</td>
                <td><?=$coachaddress?></td>
              </tr>
              <tr>
                <td>ИНН</td>
                <td><?=$coachinn?></td>
              </tr>
              <tr>
                <td colspan="2"><hr /></td>
              </tr>
<?php
    endif;
?>              
              <tr>
                <td>первый участник</td>
                <td><?=$contestant1name?></td>
              </tr>
<?php
    if (@$main_authorized == 1):
?>              
              <tr>
                <td>место учебы</td>
                <td><?=$contestant1studyplace?></td>
              </tr>
              <tr>
                <td>факультет</td>
                <td><?=$contestant1faculty?></td>
              </tr>
              <tr>
                <td>класс/курс</td>
                <td><?=$contestant1classcourse?></td>
              </tr>
              <tr>
                <td>возраст</td>
                <td><?=$contestant1age?></td>
              </tr>
              <tr>
                <td>серия и номер паспорта</td>
                <td><?=$contestant1passportno?></td>
              </tr>
              <tr>
                <td>кем выдан паспорт</td>
                <td><?=$contestant1passportplace?></td>
              </tr>
              <tr>
                <td>когда выдан паспорт</td>
                <td><?=$contestant1passportdate?></td>
              </tr>
              <tr>
                <td>дата рождения</td>
                <td><?=$contestant1birthdate?></td>
              </tr>
              <tr>
                <td>адрес</td>
                <td><?=$contestant1address?></td>
              </tr>
              <tr>
                <td>ИНН</td>
                <td><?=$contestant1inn?></td>
              </tr>
              <tr>
                <td colspan="2"><hr /></td>
              </tr>
<?php
    endif;
?>              
              <tr>
                <td>второй участник</td>
                <td><?=$contestant2name?></td>
              </tr>
<?php
    if (@$main_authorized == 1):
?>              
              <tr>
                <td>место учебы</td>
                <td><?=$contestant2studyplace?></td>
              </tr>
              <tr>
                <td>факультет</td>
                <td><?=$contestant2faculty?></td>
              </tr>
              <tr>
                <td>класс/курс</td>
                <td><?=$contestant2classcourse?></td>
              </tr>
              <tr>
                <td>возраст</td>
                <td><?=$contestant2age?></td>
              </tr>
              <tr>
                <td>серия и номер паспорта</td>
                <td><?=$contestant2passportno?></td>
              </tr>
              <tr>
                <td>кем выдан паспорт</td>
                <td><?=$contestant2passportplace?></td>
              </tr>
              <tr>
                <td>когда выдан паспорт</td>
                <td><?=$contestant2passportdate?></td>
              </tr>
              <tr>
                <td>дата рождения</td>
                <td><?=$contestant2birthdate?></td>
              </tr>
              <tr>
                <td>адрес</td>
                <td><?=$contestant2address?></td>
              </tr>
              <tr>
                <td>ИНН</td>
                <td><?=$contestant2inn?></td>
              </tr>
              <tr>
                <td colspan="2"><hr /></td>
              </tr>
<?php
    endif;
?>              
              <tr>
                <td>третий участник</td>
                <td><?=$contestant3name?></td>
              </tr>
<?php
    if (@$main_authorized == 1):
?>              
              <tr>
                <td>место учебы</td>
                <td><?=$contestant3studyplace?></td>
              </tr>
              <tr>
                <td>факультет</td>
                <td><?=$contestant3faculty?></td>
              </tr>
              <tr>
                <td>класс/курс</td>
                <td><?=$contestant3classcourse?></td>
              </tr>
              <tr>
                <td>возраст</td>
                <td><?=$contestant3age?></td>
              </tr>
              <tr>
                <td>серия и номер паспорта</td>
                <td><?=$contestant3passportno?></td>
              </tr>
              <tr>
                <td>кем выдан паспорт</td>
                <td><?=$contestant3passportplace?></td>
              </tr>
              <tr>
                <td>когда выдан паспорт</td>
                <td><?=$contestant3passportdate?></td>
              </tr>
              <tr>
                <td>дата рождения</td>
                <td><?=$contestant3birthdate?></td>
              </tr>
              <tr>
                <td>адрес</td>
                <td><?=$contestant3address?></td>
              </tr>
              <tr>
                <td>ИНН</td>
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
  endif; //конец проверки на отсутствие ошибок
?>
        </div>
        <div class="cleaner">
        </div>
      </div>
    </div>
    <?php footer('tournament'); ?>
  </body>
</html>
