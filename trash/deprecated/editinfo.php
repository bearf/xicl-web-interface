<?php
require_once('./config/require.php');
  
if (@!$main_session)
    header('Location: '.ServerRoot.'message.php?error_code='.error_no_sessions_available);
    
  $error = false;
  $msg = '';
  $wrongpassword = false;
  $success = false;
  $datamsg = '';
  
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
    
    //проверка правильности пароля
    if (!$error) {
      $query = 'select teamname from teams where teamid='.$teamid.' and `password`=password("'.$teampassword.'")';
      $r = mysql_query($query);
      
      if (mysql_num_rows($r) == 0) 
        $wrongpassword = true;
    }
    
    //пароль введен верно
    if (!$error && !$wrongpassword)
      //если нажат кнопка сабмит - нужно записать данные
      if (@$submit) {
        //проверка корректности данных
        $verifyMsg = verifyData($names, $lengths, false);
        
        //если все введено корректно - вносим изменения в таблицы
        if ($verifyMsg == "") {
          $contestteamid = getLoginId($contestlogin, $contestpassword);
          
          $_headpassportdate = parseDate($headpassportdate);
          if ($_headpassportdate != "NULL")
            $_headpassportdate = '"'.$_headpassportdate.'"';
          $_headbirthdate = parseDate($headbirthdate);
          if ($_headbirthdate != "NULL")
            $_headbirthdate = '"'.$_headbirthdate.'"';
          $_coachpassportdate = parseDate($coachpassportdate);
          if ($_coachpassportdate != "NULL")
            $_coachpassportdate = '"'.$_coachpassportdate.'"';
          $_coachbirthdate = parseDate($coachbirthdate);
          if ($_coachbirthdate != "NULL")
            $_coachbirthdate = '"'.$_coachbirthdate.'"';
          $_contestant1passportdate = parseDate($contestant1passportdate);
          if ($_contestant1passportdate != "NULL")
            $_contestant1passportdate = '"'.$_contestant1passportdate.'"';
          $_contestant1birthdate = parseDate($contestant1birthdate);
          if ($_contestant1birthdate != "NULL")
            $_contestant1birthdate = '"'.$_contestant1birthdate.'"';
          $_contestant2passportdate = parseDate($contestant2passportdate);
          if ($_contestant2passportdate != "NULL")
            $_contestant2passportdate = '"'.$_contestant2passportdate.'"';
          $_contestant2birthdate = parseDate($contestant2birthdate);
          if ($_contestant2birthdate != "NULL")
            $_contestant2birthdate = '"'.$_contestant2birthdate.'"';
          $_contestant3passportdate = parseDate($contestant3passportdate);
          if ($_contestant3passportdate != "NULL")
            $_contestant3passportdate = '"'.$_contestant3passportdate.'"';
          $_contestant3birthdate = parseDate($contestant3birthdate);
          if ($_contestant3birthdate != "NULL")
            $_contestant3birthdate = '"'.$_contestant3birthdate.'"';
            
          if (isFullFilled($fill))
            $fullfilled = 1;
          else
            $fullfilled = 0;
          
          $query = "update teams set
            teamname=\"$teamname\", city=\"$city\", studyplace=\"$studyplace\", address=\"$address\", phone=\"$phone\", fax=\"$fax\",
            contactname=\"$contactname\", contactphone=\"$contactphone\", contactmail=\"$contactmail\",
            headname=\"$headname\", headpost=\"$headpost\",
            headpassportno=\"$headpassportno\", headpassportplace=\"$headpassportplace\", headpassportdate=".$_headpassportdate.",
            headbirthdate=".$_headbirthdate.", headaddress=\"$headaddress\", headinn=\"$headinn\",
            coachname=\"$coachname\", coachpost=\"$coachpost\",
            coachpassportno=\"$coachpassportno\", coachpassportplace=\"$coachpassportplace\", coachpassportdate=".$_coachpassportdate.", 
            coachbirthdate=".$_coachbirthdate.", coachaddress=\"$coachaddress\", coachinn=\"$coachinn\",
            contestant1name=\"$contestant1name\", 
            contestant1studyplace=\"$contestant1studyplace\", contestant1faculty=\"$contestant1faculty\", 
            contestant1classcourse=\"$contestant1classcourse\", contestant1age=$contestant1age,
            contestant1passportno=\"$contestant1passportno\", contestant1passportplace=\"$contestant1passportplace\", contestant1passportdate=".$_contestant1passportdate.",
            contestant1birthdate=".$_contestant1birthdate.", contestant1address=\"$contestant1address\", contestant1inn=\"$contestant1inn\",
            contestant2name=\"$contestant2name\",
            contestant2studyplace=\"$contestant2studyplace\", contestant2faculty=\"$contestant2faculty\",
            contestant2classcourse=\"$contestant2classcourse\", contestant2age=$contestant2age,
            contestant2passportno=\"$contestant2passportno\", contestant2passportplace=\"$contestant2passportplace\", contestant2passportdate=".$_contestant2passportdate.",
            contestant2birthdate=".$_contestant2birthdate.", contestant2address=\"$contestant2address\", contestant2inn=\"$contestant2inn\",
            contestant3name=\"$contestant3name\",
            contestant3studyplace=\"$contestant3studyplace\", contestant3faculty=\"$contestant3faculty\",
            contestant3classcourse=\"$contestant3classcourse\", contestant3age=$contestant3age,
            contestant3passportno=\"$contestant3passportno\", contestant3passportplace=\"$contestant3passportplace\", contestant3passportdate=".$_contestant3passportdate.",
            contestant3birthdate=".$_contestant3birthdate.", contestant3address=\"$contestant3address\", contestant3inn=\"$contestant3inn\",
            `password`=password(\"$password\"),
            language=\"$language\",
            contestteamid=$contestteamid,
            fullfilled=$fullfilled 
          where
            teamid=$teamid";
              
            if (@!mysql_query($query)) {
              echo mysql_error();
              $error = true;
              $msg = 'Не удалось провести изменение данных. Попробуйте повторить попытку позже';
            }
            else {
              $success = true;
              $datamsg = 'Изменение данных проведено успешно.';
            } 
        } // конец проверки на корректность данных
      } // конец проверки на нажатие кнопки submit
      //пароль введен верно - нужно вытащить данные
      else {
        $query = 'select * from teams where teamid='.$teamid;
        $r = mysql_query($query);
        $f = mysql_fetch_array($r);
      
        $teamId = $f['teamId']; 
        $teamname = $f['teamname']; $city = $f['city']; $studyplace = $f['studyplace']; $address = $f['address']; $phone = $f['phone']; $fax  = $f['fax']; 
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
        $language = $f['language'];
       
        //если указан id в контест системе 
        if ($contestteamid > -1) {
          mysql_select_db(c_DBName);
        
          $r = mysql_query('select login from `user` where id='.$contestteamid);
          if (mysql_num_rows($r) > 0) {
            $f = mysql_fetch_array($r);
            $contestlogin = $f['login'];
          } else {        
            $error = true;
            $msg = 'Неверно указана учетная запись в contest-системе.';
          }

          mysql_select_db(t_DBName);
        } //конец проверки наличия логина в контест-системе
      } //конец проверки нажатия кнопки submit
    //конец проверки правильности введенного пароля
  }
  //в запросе нет идентификатора команды
  else 
    //если нажат кнопка сабмит - нужно записать данные
    if (@$submit) {
      //проверка корректности данных
      $verifyMsg = verifyData($names, $lengths, true);
        
      //если все введено корректно - вносим изменения в таблицы
      if ($verifyMsg == "") {
        $contestteamid = getLoginId($contestlogin, $contestpassword);
          
        $_headpassportdate = parseDate($headpassportdate);
        if ($_headpassportdate != "NULL")
          $_headpassportdate = '"'.$_headpassportdate.'"';
        $_headbirthdate = parseDate($headbirthdate);
        if ($_headbirthdate != "NULL")
          $_headbirthdate = '"'.$_headbirthdate.'"';
        $_coachpassportdate = parseDate($coachpassportdate);
        if ($_coachpassportdate != "NULL")
          $_coachpassportdate = '"'.$_coachpassportdate.'"';
        $_coachbirthdate = parseDate($coachbirthdate);
        if ($_coachbirthdate != "NULL")
          $_coachbirthdate = '"'.$_coachbirthdate.'"';
        $_contestant1passportdate = parseDate($contestant1passportdate);
        if ($_contestant1passportdate != "NULL")
          $_contestant1passportdate = '"'.$_contestant1passportdate.'"';
        $_contestant1birthdate = parseDate($contestant1birthdate);
        if ($_contestant1birthdate != "NULL")
          $_contestant1birthdate = '"'.$_contestant1birthdate.'"';
        $_contestant2passportdate = parseDate($contestant2passportdate);
        if ($_contestant2passportdate != "NULL")
          $_contestant2passportdate = '"'.$_contestant2passportdate.'"';
        $_contestant2birthdate = parseDate($contestant2birthdate);
        if ($_contestant2birthdate != "NULL")
          $_contestant2birthdate = '"'.$_contestant2birthdate.'"';
        $_contestant3passportdate = parseDate($contestant3passportdate);
        if ($_contestant3passportdate != "NULL")
          $_contestant3passportdate = '"'.$_contestant3passportdate.'"';
        $_contestant3birthdate = parseDate($contestant3birthdate);
        if ($_contestant3birthdate != "NULL")
          $_contestant3birthdate = '"'.$_contestant3birthdate.'"';
          
        if (isFullFilled($fill))
          $fullfilled = 1;
        else
          $fullfilled = 0;
          
        $query = "insert into teams(teamname, city, studyplace, address, phone, fax,
          contactname, contactphone, contactmail,
          headname, headpost,
          headpassportno, headpassportplace, headpassportdate, headbirthdate, headaddress, headinn,
          coachname, coachpost,
          coachpassportno, coachpassportplace, coachpassportdate, coachbirthdate, coachaddress, coachinn,
          contestant1name,
          contestant1studyplace, contestant1faculty, contestant1classcourse, contestant1age,
          contestant1passportno, contestant1passportplace, contestant1passportdate,
          contestant1birthdate, contestant1address, contestant1inn,
          contestant2name,
          contestant2studyplace, contestant2faculty, contestant2classcourse, contestant2age,
          contestant2passportno, contestant2passportplace, contestant2passportdate, 
          contestant2birthdate, contestant2address, contestant2inn,
          contestant3name,
          contestant3studyplace, contestant3faculty, contestant3classcourse, contestant3age,
          contestant3passportno, contestant3passportplace, contestant3passportdate,
          contestant3birthdate, contestant3address, contestant3inn,
          `password`, contestteamid,
          language,
          fullfilled)
        values(\"$teamname\", \"$city\", \"$studyplace\", \"$address\", \"$phone\", \"$fax\",
          \"$contactname\", \"$contactphone\", \"$contactmail\",
          \"$headname\", \"$headpost\",
          \"$headpassportno\", \"$headpassportplace\", ".$_headpassportdate.",
          ".$_headbirthdate.", \"$headaddress\", \"$headinn\",
          \"$coachname\", \"$coachpost\",
          \"$coachpassportno\", \"$coachpassportplace\", ".$_coachpassportdate.",
          ".$_coachbirthdate.", \"$coachaddress\", \"$coachinn\",
          \"$contestant1name\",
          \"$contestant1studyplace\", \"$contestant1faculty\", \"$contestant1classcourse\", $contestant1age,
          \"$contestant1passportno\", \"$contestant1passportplace\", ".$_contestant1passportdate.",
          ".$_contestant1birthdate.", \"$contestant1address\", \"$contestant1inn\",
          \"$contestant2name\", 
          \"$contestant2studyplace\", \"$contestant2faculty\", \"$contestant2classcourse\", $contestant2age,
          \"$contestant2passportno\", \"$contestant2passportplace\", ".$_contestant2passportdate.",
          ".$_contestant2birthdate.", \"$contestant2address\", \"$contestant2inn\",
          \"$contestant3name\", 
          \"$contestant3studyplace\", \"$contestant3faculty\", \"$contestant3classcourse\", $contestant3age,
          \"$contestant3passportno\", \"$contestant3passportplace\", ".$_contestant3passportdate.",
          ".$_contestant3birthdate.", \"$contestant3address\", \"$contestant3inn\",
          password(\"$password\"),
          $contestteamid,
          \"$language\",
          $fullfilled)";
              
        if (@!mysql_query($query)) {
          $error = true;
          $msg = 'Не удалось провести добавление новой команды ['.mysql_error().', code='.mysql_errno().', sql='.$query.']. Попробуйте повторить попытку позже';
        }
        else {
          $success = true;
          $datamsg = 'Добавление новой команды проведено успешно.';
        } 
      } // конец проверки на корректность данных
    } // конец проверки на нажатие кнопки submit
    else {
      //пустые данные
      $teamId = '';
      $teamname = ''; $city = ''; $studyplace = ''; $address = ''; $phone = ''; $fax  = '';
      $contestlogin = '';
      $contactname = ''; $contactphone = ''; $contactmail = '';
      $headname = ''; $headpost = '';
      $headpassportno = ''; $headpassportplace = ''; $headpassportdate = '';
      $headbirthdate = ''; $headaddress = ''; $headinn = ''; 
      $coachname = ''; $coachpost = '';
      $coachpassportno = ''; $coachpassportplace = ''; $coachpassportdate = '';
      $coachbirthdate = ''; $coachaddress = ''; $coachinn = ''; 
      $contestant1name = ''; 
      $contestant1studyplace = ''; $contestant1faculty = '';  $contestant1classcourse = ''; $contestant1age = ''; 
      $contestant1passportno = ''; $contestant1passportplace = ''; $contestant1passportdate = ''; 
      $contestant1birthdate = ''; $contestant1address = ''; $contestant1inn = ''; 
      $contestant2name = ''; 
      $contestant2studyplace = ''; $contestant2faculty = ''; $contestant2classcourse = ''; $contestant2age = ''; 
      $contestant2passportno = ''; $contestant2passportplace = ''; $contestant2passportdate = ''; 
      $contestant2birthdate = ''; $contestant2address = ''; $contestant2inn = ''; 
      $contestant3name = '';
      $contestant3studyplace = ''; $contestant3faculty = ''; $contestant3classcourse = ''; $contestant3age = ''; 
      $contestant3passportno = ''; $contestant3passportplace = ''; $contestant3passportdate = ''; 
      $contestant3birthdate = ''; $contestant3address = ''; $contestant3inn = ''; 
      $language = '';
      
      //header('Location: '.ServerRoot.'message.php?warning_code='.warning_registration_is_closed);
    } //конец проверки на наличие идентификатора команды
  //конец обработки отсутствия идентификатора команды в запросе
  
  //был неверно введен пароль - надо перейти на страницу ввода пароля
  if ($wrongpassword)
    header('Location: '.ServerRoot.'teamlogin.php?teamid='.$teamid.'&wrongpass=yes');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=WINDOWS-1251" />
    <meta name="Author" content="Игорь Зиновьев" />
    <meta name="Keywords" content="олимпиада, программирование, информатика, задача, ICL, турнир" />
    <title>Официальный сайт Турнира по программированию ОАО "ICL-КПО ВС"</title>
    <link href="style.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="./js/toggle.js"></script>
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
<?php
  //возникли ошибки - значит надо показать сообщение
  if ($error):
?>
          <?=$msg?>
<?php
  elseif ($success):
?>
          <?=$datamsg?>
<?php
  else:
?> 
<p>Пожалуйста, заполните анкету. Поля, обязательные для заполнения, отмечены звездочками (*). При вводе информации запрещается использовать символы "&amp;",
          "/", "\", "%",
          "!", "$", "*", "^",
          "?", "&lt;", "&gt;".</p>         
<p>Для того, чтобы зарегистрировать команду Вам необходима учетная запись в системе онлайн-тестирования. Если один из участников команды уже имеет подобную учетную запись, вы можете использовать её, указав в соответствующем поле имя и пароль доступа к ней.</p>

<p>Для того чтобы все достойные могли получить призы, нам необходимы паспортные данные и ИНН участников, тренера и руководителя команды. Вы можете не заполнять их прямо сейчас, однако эти данные понадобятся нам, если Ваша команда пройдет в основные соревнования по результатам отбора. </p>

          <hr />
<h4 style="text-align:center">Заявка на участие в IX открытом командном турнире студентов
и школьников Татарстана по программированию
17 – 19 апреля 2009 года</h4>        
          <hr />
<?php
  if (@$verifyMsg):
?>
          <p class="red"><b><?=$verifyMsg?></b></red>
          <hr />
<?php
  endif;
?>
          <form name="editinfo" action="editinfo.php" method="post">
<?php          
  if (@$teamid):
?>
            <input type="hidden" name="teamid" value="<?=$teamid?>" />
            <input type="hidden" name="teampassword" value="<?=$teampassword?>" />
<?php
  endif;
?>  
            <table class="enter">
              <tr>
                <td>название команды (*)</td>
                <td><input type="text" name="teamname" value="<?=$teamname?>" /></td>
              </tr>
              <tr>
                <td>пароль (*)</td>
                <td><input type="password" name="password" value="" /></td>
              </tr>
              <tr>
                <td>повтор пароля (*)</td>
                <td><input type="password" name="repeatpassword" value="" /></td>
              </tr>
              <tr>
                <td>среда программирования (*)</td>
                <td><input type="text" name="language" value="<?=$language?>" /></td>
              </tr>
              <tr>
                <td>город (*)</td>
                <td><input type="text" name="city" value="<?=$city?>" /></td>
              </tr>
              <tr>
                <td>учебное заведение (*)</td>
                <td><input type="text" name="studyplace" value="<?=$studyplace?>" /></td>
              </tr>
              <tr>
                <td>адрес учебного заведения (*)</td>
                <td><input type="text" name="address" value="<?=$address?>" /></td>
              </tr>
              <tr>
                <td>телефон (*)</td>
                <td><input type="text" name="phone" value="<?=$phone?>" /></td>
              </tr>
              <tr>
                <td>факс</td>
                <td><input type="text" name="fax" value="<?=$fax?>" /></td>
              </tr>
            </table>
            <hr />
            <table class="enter">
              <tr>
                <td>login в contest-системе (*)</td>
                <td><input type="text" name="contestlogin" value="<?=$contestlogin?>" /></td>
              </tr>
              <tr>
                <td>пароль в contest-системе (*)</td>
                <td><input type="password" name="contestpassword" value="" /></td>
              </tr>
            </table>
            <hr />
            <table class="enter">
              <tr>
                <td>контактное лицо (*)</td>
                <td><input type="text" name="contactname" value="<?=$contactname?>" /></td>
              </tr>
              <tr>
                <td>контактный телефон (*)</td>
                <td><input type="text" name="contactphone" value="<?=$contactphone?>" /></td>
              </tr>
              <tr>
                <td>контактный e-mail</td>
                <td><input type="text" name="contactmail" value="<?=$contactmail?>" /></td>
              </tr>
            </table>
            <hr />
            <table class="enter">
              <tr>
                <td>руководитель команды (*)</td>
                <td><input type="text" name="headname" value="<?=$headname?>" /></td>
              </tr>
              <tr>
                <td>должность (*)</td>
                <td><input type="text" name="headpost" value="<?=$headpost?>" /></td>
              </tr>
              <tr>
                <td colspan="2"><a id="headlink" href="#" onclick="return toggleHead()">[расширенная информация - показать]</a></td>
              </tr>
            </table>
            <table class="enter" id="head" style="display:none">
              <tr>
                <td>серия и номер паспорта</td>
                <td><input type="text" name="headpassportno" value="<?=$headpassportno?>" /></td>
              </tr>
              <tr>
                <td>кем выдан паспорт</td>
                <td><input type="text" name="headpassportplace" value="<?=$headpassportplace?>" /></td>
              </tr>
              <tr>
                <td>когда выдан паспорт</td>
                <td><input type="text" name="headpassportdate" value="<?=$headpassportdate?>" /></td>
              </tr>
              <tr>
                <td>дата рождения</td>
                <td><input type="text" name="headbirthdate" value="<?=$headbirthdate?>" /></td>
              </tr>
              <tr>
                <td>адрес</td>
                <td><input type="text" name="headaddress" value="<?=$headaddress?>" /></td>
              </tr>
              <tr>
                <td>ИНН</td>
                <td><input type="text" name="headinn" value="<?=$headinn?>" /></td>
              </tr>
            </table>
            <hr />
            <table class="enter">
              <tr>
                <td>тренер команды (*)</td>
                <td><input type="text" name="coachname" value="<?=$coachname?>" /></td>
              </tr>
              <tr>
                <td>должность (*)</td>
                <td><input type="text" name="coachpost" value="<?=$coachpost?>" /></td>
              </tr>
              <tr>
                <td colspan="2"><a id="coachlink" href="#" onclick="return toggleCoach()">[расширенная информация - показать]</a></td>
              </tr>
            </table>
            <table class="enter" id="coach" style="display:none">
              <tr>
                <td>серия и номер паспорта</td>
                <td><input type="text" name="coachpassportno" value="<?=$coachpassportno?>" /></td>
              </tr>
              <tr>
                <td>кем выдан паспорт</td>
                <td><input type="text" name="coachpassportplace" value="<?=$coachpassportplace?>" /></td>
              </tr>
              <tr>  
                <td>когда выдан паспорт</td>
                <td><input type="text" name="coachpassportdate" value="<?=$coachpassportdate?>" /></td>
              </tr>
              <tr>
                <td>дата рождения</td>
                <td><input type="text" name="coachbirthdate" value="<?=$coachbirthdate?>" /></td>
              </tr>
              <tr>
                <td>адрес</td>
                <td><input type="text" name="coachaddress" value="<?=$coachaddress?>" /></td>
              </tr>
              <tr>
                <td>ИНН</td>
                <td><input type="text" name="coachinn" value="<?=$coachinn?>" /></td>
              </tr>
            </table>
            <hr />
            <table class="enter">
              <tr>
                <td>первый участник (*)</td>
                <td><input type="text" name="contestant1name" value="<?=$contestant1name?>" /></td>
              </tr>
              <tr>
                <td>место учебы (*)</td>
                <td><input type="text" name="contestant1studyplace" value="<?=$contestant1studyplace?>" /></td>
              </tr>
              <tr>
                <td>факультет</td>
                <td><input type="text" name="contestant1faculty" value="<?=$contestant1faculty?>" /></td>
              </tr>
              <tr>
                <td>класс/курс (*)</td>
                <td><input type="text" name="contestant1classcourse" value="<?=$contestant1classcourse?>" /></td>
              </tr>
              <tr>
                <td>возраст (*)</td>
                <td><input type="text" name="contestant1age" value="<?=$contestant1age?>" /></td>
              </tr>
              <tr>
                <td colspan="2"><a id="contestant1link" href="#" onclick="return toggleContestant1()">[расширенная информация - показать]</a></td>
              </tr>
            </table>
            <table class="enter" id="contestant1" style="display:none">
              <tr>
                <td>серия и номер паспорта</td>
                <td><input type="text" name="contestant1passportno" value="<?=$contestant1passportno?>" /></td>
              </tr>
              <tr>
                <td>кем выдан паспорт</td>
                <td><input type="text" name="contestant1passportplace" value="<?=$contestant1passportplace?>" /></td>
              </tr>
              <tr>
                <td>когда выдан паспорт</td>
                <td><input type="text" name="contestant1passportdate" value="<?=$contestant1passportdate?>" /></td>
              </tr>
              <tr>
                <td>дата рождения</td>
                <td><input type="text" name="contestant1birthdate" value="<?=$contestant1birthdate?>" /></td>
              </tr>
              <tr>
                <td>адрес</td>
                <td><input type="text" name="contestant1address" value="<?=$contestant1address?>" /></td>
              </tr>
              <tr>
                <td>ИНН</td>
                <td><input type="text" name="contestant1inn" value="<?=$contestant1inn?>" /></td>
              </tr>
            </table>
            <hr />
            <table class="enter">
              <tr>
                <td>второй участник (*)</td>
                <td><input type="text" name="contestant2name" value="<?=$contestant2name?>" /></td>
              </tr>
              <tr>
                <td>место учебы (*)</td>
                <td><input type="text" name="contestant2studyplace" value="<?=$contestant2studyplace?>" /></td>
              </tr>
              <tr>
                <td>факультет</td>
                <td><input type="text" name="contestant2faculty" value="<?=$contestant2faculty?>" /></td>
              </tr>
              <tr>
                <td>класс/курс (*)</td>
                <td><input type="text" name="contestant2classcourse" value="<?=$contestant2classcourse?>" /></td>
              </tr>
              <tr>
                <td>возраст (*)</td>
                <td><input type="text" name="contestant2age" value="<?=$contestant2age?>" /></td>
              </tr>
              <tr>
                <td colspan="2"><a id="contestant2link" href="#" onclick="return toggleContestant2()">[расширенная информация - показать]</a></td>
              </tr>
            </table>
            <table class="enter" id="contestant2" style="display:none">
              <tr>
                <td>серия и номер паспорта</td>
                <td><input type="text" name="contestant2passportno" value="<?=$contestant2passportno?>" /></td>
              </tr>
              <tr>
                <td>кем выдан паспорт</td>
                <td><input type="text" name="contestant2passportplace" value="<?=$contestant2passportplace?>" /></td>
              </tr>
              <tr>
                <td>когда выдан паспорт</td>
                <td><input type="text" name="contestant2passportdate" value="<?=$contestant2passportdate?>" /></td>
              </tr>
              <tr>
                <td>дата рождения</td>
                <td><input type="text" name="contestant2birthdate" value="<?=$contestant2birthdate?>" /></td>
              </tr>
              <tr>
                <td>адрес</td>
                <td><input type="text" name="contestant2address" value="<?=$contestant2address?>" /></td>
              </tr>
              <tr>
                <td>ИНН</td>
                <td><input type="text" name="contestant2inn" value="<?=$contestant2inn?>" /></td>
              </tr>
            </table>
            <hr />
            <table class="enter">
              <tr>
                <td>третий участник (*)</td>
                <td><input type="text" name="contestant3name" value="<?=$contestant3name?>" /></td>
              </tr>
              <tr>
                <td>место учебы (*)</td>
                <td><input type="text" name="contestant3studyplace" value="<?=$contestant3studyplace?>" /></td>
              </tr>
              <tr>
                <td>факультет</td>
                <td><input type="text" name="contestant3faculty" value="<?=$contestant3faculty?>" /></td>
              </tr>
              <tr>
                <td>класс/курс (*)</td>
                <td><input type="text" name="contestant3classcourse" value="<?=$contestant3classcourse?>" /></td>
              </tr>
              <tr>
                <td>возраст (*)</td>
                <td><input type="text" name="contestant3age" value="<?=$contestant3age?>" /></td>
              </tr>
              <tr>
                <td colspan="2"><a id="contestant3link" href="#" onclick="return toggleContestant3()">[расширенная информация - показать]</a></td>
              </tr>
            </table>
            <table class="enter" id="contestant3" style="display:none">
              <tr>
                <td>серия и номер паспорта</td>
                <td><input type="text" name="contestant3passportno" value="<?=$contestant3passportno?>" /></td>
              </tr>
              <tr>
                <td>кем выдан паспорт</td>
                <td><input type="text" name="contestant3passportplace" value="<?=$contestant3passportplace?>" /></td>
              </tr>
              <tr>
                <td>когда выдан паспорт</td>
                <td><input type="text" name="contestant3passportdate" value="<?=$contestant3passportdate?>" /></td>
              </tr>
              <tr>
                <td>дата рождения</td>
                <td><input type="text" name="contestant3birthdate" value="<?=$contestant3birthdate?>" /></td>
              </tr>
              <tr>
                <td>адрес</td>
                <td><input type="text" name="contestant3address" value="<?=$contestant3address?>" /></td>
              </tr>
              <tr>
                <td>ИНН</td>
                <td><input type="text" name="contestant3inn" value="<?=$contestant3inn?>" /></td>
              </tr>
              <tr>
                <td colspan="2"><hr /></td>
              </tr>
            </table>
            <hr />
            <table class="enter">
              <tr>
                <td colspan="2"><input type="submit" name="submit" value="принять" /></td>
              </tr>
            </table>
          </form>
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
