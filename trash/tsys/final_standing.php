<?php
  require_once("const.php");
  require_once("sessions.php");
  require_once("mysql.php");

  $r = mysql_query("select now() as time");
  $f = mysql_fetch_array($r);
  $srvtime = $f["time"];
  
  if (IsLoginNeed == 1)
    if (@$authorized == 0)
      header('Location: '.ServerRoot.'message.php?warning_code='.warning_need_login_code);

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
        $contestname = 'Турнир не активен, либо не существует.';
    else {
      $error = true;
      $msg = 'Произошла ошибка при выполнении запроса.';
    }
  }

  $pagesize = 40;

  $msg = '';
  $error = false;
  $requested_contest_name = '';
  $rowcount = 0;

  //проверяем параметр contest
  if (!@$contest)
    $contest = $curcontest;
  else {
    $x = $contest;
    settype($x, 'integer');
    if ($x==0) {
      $msg = 'Неверно указан один или несколько параметров. Попробуйте изменить строку запроса и повторить попытку.';
      $error = true;
    }
  }

  if (!@$page)
    $page = 1;
  else {
    $x = $page;
    settype($x, 'integer');
    if ($x==0) {
      $msg = 'Неверно указан один или несколько параметров. Попробуйте изменить строку запроса и повторить попытку.';
      $error = true;
    }
  }

  //проверяем, существует ли запрашиваемый турнир
  if ((!$error)&&(@$contest)) {
    $q = 'SELECT
            ContestID, Name, Contest_Kind
          FROM
            cntest
          WHERE ContestID='.$contest;

    $r = mysql_query($q);
    if (mysql_error() != '') {
      $msg = 'Не удалось произвести запрос данных. Повторите попытку позже.';
      $error = true;
    }
    else if (mysql_num_rows($r)==0) {
      $msg = 'Турнир не активен, либо не существует.';
      $error = true;
    }
    else {
      $f = mysql_fetch_array($r);
      $kind = $f['Contest_Kind'];
      $requested_contest_name = $f['Name'];
    }
  }

  //запрашиваем данные
  if (!$error) {
    if ($kind==1)
      $q = 'SELECT
              U.ID,
              U.Nickname,
              SUM(M1.Solved) AS `Solved`,
              MAX(M1.Date) AS `LastAC`
            FROM
              (`User` U LEFT JOIN `Monitor` M1 ON U.ID=M1.UserID)
            WHERE
              M1.ContestID = '.$contest.'
              OR M1.ContestID IS NULL
            GROUP BY
              U.ID
            ORDER BY
              Solved DESC, LastAC ASC, U.ID ASC';
    elseif ($kind==2)
      $q = 'SELECT
              U.ID,
              U.Nickname,
              SUM(M1.Solved) AS `Solved`,
              MAX(M1.Date) AS `LastAC`,
              SUM(M1.RealPts) AS `Points`
            FROM
              (`User` U INNER JOIN `Monitor` M1 ON U.ID=M1.UserID)
            WHERE
              M1.ContestID = '.$contest.'
            GROUP BY
              U.ID
            ORDER BY
              Solved DESC, LastAC ASC, Points DESC, U.ID ASC';
    //сложный вопрос, потребует извлечения индексов задач              
    else {
      //запрос на извлечение данных о задачах
      $q = 'select problemId from volume where contestid='.$contest.' order by problemId';
      
      //добываем данные о задачах
      //и если что - ругаемся на ошибки
      $r = mysql_query($q);
      if (mysql_error() != '') {
        $msg = 'Не удалось произвести запрос данных. Повторите попытку позже.';
        $error = true;
      }
      else {
        //заносим индексы задач в массив
        $rowcount = mysql_num_rows($r);
        $indexes = array();                                  
        
        for ($i=0; $i<$rowcount; $i++) {
          $f = mysql_fetch_array($r);
          array_push($indexes, $f['problemId']);
        }
        
        //if it's admin mode
        if (@$main_authorized == 1) {
          //строим базовые части запросов
          $q_select_main = 'SELECT '.
            'U.id as `ID`, '.
            'U.nickname as `Nickname`, '.
            'U.city as `city`, '.
            'U.studyplace as `studyplace`, '.
            'sum(if(S.resultid=0,1,0)) as `Solved`, '.
            'sum(S.penalty) as `Penalty` ';
          
          //добавляем индексы задач к базовым частям запросов
          for ($i=0; $i<count($indexes); $i++) 
            $q_select_main = $q_select_main.', max(if(S.problemID=\''.$indexes[$i].'\',if(S.resultid=0,20000+attempt,10000+attempt),0)) as `'.$indexes[$i].'` ';
        
          //строим весь запрос
          $q = $q_select_main.
            'FROM '.
              '`User` U INNER JOIN `Submit` S ON U.ID=S.UserID '.
            'WHERE '.
              'S.ContestID = '.$contest.
              ' and S.isTaskSolved = 0 '.
            'GROUP BY '.
              'U.ID '.
            'ORDER BY '.
              '`Solved` DESC, `Penalty` ASC, U.id asc';      
        } //end admin's mode
        //normal user mode
        else {
          //строим базовые части запросов
          $q_select_main = 'SELECT '.
            'U.id as `ID`, '.
            'U.nickname as `Nickname`, '.
            'S.Solved as `Solved`, '.
            'S.Penalty as `Penalty` ';
          $q_select_inner = 'SELECT '.
            'M.userID as `userid`, '.
            'SUM(IFNULL(M.Solved, 0)) AS `Solved`, '.
            'SUM(IFNULL(M.Penalty, 0)) AS `Penalty` ';
          
          //добавляем индексы задач к базовым частям запросов
          for ($i=0; $i<count($indexes); $i++) {
            $q_select_main = $q_select_main.', S.'.$indexes[$i].' ';
            $q_select_inner = $q_select_inner.', sum(if (M.ProblemId=\''.$indexes[$i].'\', M.attempt*(2*M.Solved-1), 0)) as `'.$indexes[$i].'` ';
          }
        
          //строим весь запрос
          $q = $q_select_main.
            'from '.
              '`user` U left join ( '.
                $q_select_inner.
                'FROM '.
                  '`Monitor` M '.
                'WHERE '.
                  'M.ContestID = '.$contest.' '.
                'GROUP BY '.
                  'M.userID '.
              ') S on U.id = S.userid '.
            'ORDER BY '.
              'S.Solved DESC, S.Penalty ASC';      
        } //end normal user's mode
      } //конец проверки на ошибку при получении индексов задач
    } //конец построения запроса на извлечение монитора
    
    //выполняем запрос
    if (!$error)
      $r = mysql_query($q);
      if (mysql_error() != '') {
        $msg = 'Не удалось произвести запрос данных. Повторите попытку позже.';
        $error = true;
      }
      else
        $rowcount = mysql_num_rows($r);
  } //конец запроса данных
  
  //сообщение о времени
  $monitor_time = '';
  
  //проверяем на заморозку
  if (!$error && _settings_show_monitor_comment) {
    $query = 'select '.
        'now() as `currenttime`, '.
        'finish as `finish`, '.
        'timediff(now(), start) as `timepast`, '.
        'time_to_sec(timediff(finish, now())) as `secleft`, '.
        'sec_to_time(time_to_sec(timediff(finish, start)) - frozetime) as `frozetimeshow`, '.
        'timediff(finish, start) as `length`, '.
        'isneedfreeze, '.
        'frozetime '.
      'from '.
        'cntest C '.
      'where '.
        'C.contestId='.$contest.
        '';
        //' and C.start<=now() and C.finish>=now()';
        
    $rec = mysql_query($query);
    
    if (0 != mysql_num_rows($rec)) {
      $f = mysql_fetch_array($rec);
      
      if (!$f['isneedfreeze'] || @$main_authorized == 1)
        if ($f['currenttime'] < $f['finish'])
          $monitor_time = ' - '.$f['timepast'];
        else
          $monitor_time = ' - '.$f['length'].' [завершен]';
      else if ($f['secleft'] < $f['frozetime'])
        $monitor_time = ' - '.$f['frozetimeshow'].' [заморожен]';
      else
        if ($f['currenttime'] < $f['finish'])
          $monitor_time = ' - '.$f['timepast'];
        else
          $monitor_time = ' - '.$f['length'].' [завершен]';
    }
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=WINDOWS-1251" />
    <meta name="Author" content="Игорь Зиновьев" />
    <meta name="Keywords" content="олимпиада, программирование, информатика, задача, ICL, турнир" />
    <title>&gt; Архив олимпиадных задач ОАО "ICL-КПО ВС"</title>
    <link href="contest.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="js/monitor.js"></script>
  </head>
  <body>
<p>
<a href="./index.php">на главную</a>
</p>
<?php
  if ($requested_contest_name != ''):
?>
          <h3>Результаты: <?=$requested_contest_name?><?=$monitor_time?></h3>
<?php
  else:
?>
          <h3>Результаты<?=$monitor_time?></h3>
<?php
  endif;

  if ($error):
?>
          <p><?=$msg?></p>
<?php
  else:
?>
          <table>
            <tr>
<?php
    if ($kind==1):
?>
              <th>Позиция</th>
              <th>Пользователь</th>
              <th class="c">OK</th>
              <th>Последняя сдача</th>
<?php
    else:
      if ($kind==2):
?>
              <th>Позиция</th>
              <th>Пользователь</th>
              <th class="c">OK</th>
              <th class="c">Последняя сдача</th>
              <th>Баллы</th>
<?php
      else:
?>
              <th>Позиция</th>
              <th>Пользователь</th>
              <th>Город</th>
              <th>Место обучения</th>
<?php
        //контест третьего типа - надо вывести индексы задач
        for ($i=0; $i<count($indexes); $i++):
?>              
              <th><?=$indexes[$i]?></th>
<?php
        endfor; //конец цикла по индексам задач
?>
              <th>OK</th>
              <th>Время</th>
<?php
      endif;
    endif; //конец рисования заголовка
?>
              </tr>
<?php
    //выводим строки
    $first = ($page-1)*$pagesize+1;
    for ($i=1; $i<=min($first+$pagesize-1, $rowcount); $i++):
      $f = mysql_fetch_array($r);
      if ($i>=$first):
        if ($i%2==0):
?>
                  <tr class="s">
<?
        else:
?>
                  <tr>
<?
        endif; //конец выбора цвета строки
?>
                    <td class="c"><?=$i?></td>
                    <td>
<?php
        //если разрешен просмотр или мы в режиме админа - 
        //показываем ссылку
        if (!_permission_is_user_data_secure || (@$main_authorized == 1)):
?>                           
                      <!--<a href="userinfo.php?userid=<?=$f['ID']?>"><?=$f['Nickname']?></a>-->
                      <?=$f['Nickname']?>
<?php
        //иначе просто пишем имя пользователя
        else:
?>                            
                      <?=$f['Nickname']?>
<?php
        endif; //конец проверки разрешения на показ данных пользователя
?>        
                    </td>
<td>
  <?=$f['city']?>
</td>
<td>
  <?=$f['studyplace']?>
</td>
<?php
        //контест типа 3 - надо писать индексы задач
        if ($kind == 3):
          //цикл по индексам задач
          for ($j=0; $j<count($indexes); $j++):
            //получаем значение, непосредственно возвращенное запросом
            $value = $f[$indexes[$j]];
            
            //if attempts' count >= 20000 then task is solved
            if ($value >= 20000)
              $value -= 20000;
              
            if ($value >= 10000)
              $value = 10000 - $value;
            
            //if attempts' count >= 10000 then task is notsolved
            
            //если задача решена с первой попытки - выводим +
            if ($value == 1)
              $value = '+';
            //если задача решена - выводим +<количество неудачных попыток>
            elseif ($value > 1)
              $value = '+'.($value-1);
            //попыток не было - выводим .
            elseif ($value == 0)
              $value = '.';
?>
                    <td class="c"><?=$value?></td>
<?php
          endfor; //конец цикла по индексам задач
        endif; //конец проверки на необходимость отрисовки индексов задач
?>                    
<?
        if (@$f['Solved']):
?>
                    <td class="c"><?=$f['Solved']?></td>
<?
        else:
?>
                    <td class="c">0</td>
<?
        endif; //конец рисования ячейки с количеством решенных задач

        if (($kind==1)||($kind==2)):
          if (@($f['Solved']>0)&&(@$f['LastAC'])):
?>
                    <td class="c"><?=$f["LastAC"]?></td>
<?
          else:
?>
                    <td class="c">-</td>
<?
          endif; //конец рисования ячейки с датой последнего AC
          if ($kind==2):
            if (@$f['Points']):
?>
                    <td class="c"><?=$f['Points']?></td>
<?
            else:
?>
                    <td class="c">-</td>
<?
            endif; //конец рисования ячейки с баллами
          endif; //конец рисования данных для $kind=2
        else:
          if (!@$penalty)
            $penalty = 0;
          $penalty = $f['Penalty']/60;
          settype($penalty,'integer');
?>
                    <td class="c"><?=$penalty?></td>
<?
        endif; //конец рисования строкиd
?>
                  </tr>
<?
      endif; //конец проверки видимости строки
    endfor; //конец пробега по строкам
?>
          </table>
<?
    $params = '';
    $found = false;
    if (@$contest)
      $params = '&contest='.$contest;

    $npage = $rowcount / $pagesize;
    if ($rowcount%$pagesize!=0)
      $npage += 1;

    if ($npage > 0):
?>
          <hr />
          <p>Страницы:
<?php
      for ($i=1; $i<=$npage; $i++)
        if ($page==$i):
?>
            [<a class="i" href="standing.php?page=<?=$i?><?=$params?>"><?=$i?></a>]
<?
        else:
?>
            [<a href="standing.php?page=<?=$i?><?=$params?>"><?=$i?></a>]
<?
        endif; //конец вывода номера страницы и цикла по номерам
?>
          </p>
<?
    endif; //конец вывода номеров страниц
  endif; //конец вывода данных
?>
    <script type="text/javascript">
      window.setTimeout('refreshMonitor()', 60000);
    </script>
  </body>
</html>