<?php
require_once('./config/require.php');

if (@!$main_session)
    header('Location: '.ServerRoot.'message.php?error_code='.error_no_sessions_available);
  
$error = false;
$msg = '';
$rowcount = 0;

  //нет параметров
if (!isset($pressid)) {
    $r = $mysqli_->query('SELECT publicationid, date, caption, brief, comment FROM press ORDER BY publicationid DESC');
    $rowcount = $r->num_rows;
} else {
    //вытаскиваем данные
    $r = $mysqli_->prepare('SELECT date, caption, text FROM press WHERE publicationid=?');
    $r->bind_param('i', $pressid);
    $r->execute();
    $r->bind_result($date, $caption, $text);
    if (!$r->fetch()) {
        $error = true;
        $msg = 'Неверно задан идентификатор статьи. Попробуйте изменить параметры в строке запроса.';
    }
    $r->close();
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
<?php
if ($error):
?>
          <h3>Невозможно отобразить публикации</h3>
          <?=$msg?>
<?php
else:
    //нет параметров
    if (!isset($pressid)):
?>    
          <h3>Пресса</h3>
          <hr />
<?php          
        if ($rowcount == 0):
?>
          Публикаций нет.
<?php      
        else:
            for ($i=0; $i<$rowcount; $i++):
                $f = $r->fetch_object();
?>
            <h4><?=$f->caption?></h4>
            <p>
              <?=$f->brief?>...
              <a href="./public.php?pressid=<?=$f->publicationid?>">Читать дальше</a>
            </p>
            <p class="i r">
              <?=$f->comment?>
            </p>
            <hr />
<?php
            endfor; //конец цикла по новостям
        endif; //конец проверки на наличие новостей
    //параметры есть
    else:
?>
            <h3><?=$date?>&nbsp;&nbsp;&nbsp;<?=$caption?></h3>
            <hr />
            <?=$text?>
            <hr />
            <p class="c">
              <a href="./public.php">[на главную страницу прессы]</a>
            </p>
<?php            
    endif; //окончание обработчки параметров
endif; //конец обработки $error
?>
        </div>
        <div class="cleaner">
        </div>
      </div>
    </div>
    <?php footer('tournament'); ?>
  </body>
</html>
