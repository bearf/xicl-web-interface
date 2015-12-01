<?php

function checkbox($name) {
    return isset($_POST[$name]) && 'on' === $_POST[$name] ? '1' : '0';
}

function solvedAt($time) {
    $secs   = $time % 60; $time = floor($time / 60);
    $mins   = $time % 60; $time = floor($time / 60);
    $hrs    = $time % 24; $time = floor($time / 24);
    
    $time   = $time > 0 ? $time.'d ' : '';
    $time   = $time.$hrs.':';
    $time   = $time.($mins < 10 ? '0'.$mins : $mins).':';
    $time   = $time.($secs < 10 ? '0'.$secs : $secs);
    return $time;
}

function get_site_branch($content_tile_name) {
    $tournament = array('online', 'index', 'qualification', 'rules', 'history', 'faq', 'deletefaq', 'editanswer', 'insertq', 'about', 'orders', 'order', 'mail', 'foreign');
    return in_array($content_tile_name, $tournament) ? 'tournament' : 'contest';
}

function CheckSym($str) {
    $unavailable_sym = array('<', '>');

    for ($i=0; $i<strlen($str); $i++) {
        for ($j=0; $j<2; $j++) {
            if ($str[$i]==$unavailable_sym[$j]) {
                return false;
            }
        }
    }
    return true;
}

function LoginCorrect($str) {
    return strlen($str)<=50;
}

function NicknameCorrect($str) {
    return strlen($str)<=50;
}

function PassCorrect($str) {
    return strlen($str)<=20;
}

function NameCorrect($str) {
    return strlen($str)<=40;
}

function TeamNameCorrect($str) {
    return strlen($str)<=60;
}

function CountryCorrect($str) {
    return strlen($str)<=30;
}

function CityCorrect($str) {
    return strlen($str)<=30;
}

function StudyCorrect($str) {
    return strlen($str)<=50;
}

function EmailCorrect($str) {
    return strlen($str)<=40;
}

function InfoCorrect($str) {
    return strlen($str)<=254;
}

function DateCorrect($year, $month, $day) {
    if ($month==2) {
        if ($day > 29) { return false; }
        return $day<=28 || $day==29 && year%4==0 && $year%100!=0 || $year%400==0;
    }
    if (($month==1 || $month==3 || $month==5 || $month==7 || $month==8 || $month==10 || $month==12) && $day<=31) { return true; }
    if (($month==4 || $month==6 || $month==9 || $month==11) && $day<=30) { return true; }
    return false;
}
  
  function checkMandatoryFields($names, $lengths)
  {
    if (!@$_POST['teamname'])
      return 'Поле "название команды" является обязательным для ввода';
    if (!@$_POST['city'])
      return 'Поле "город" является обязательным для ввода';
    if (!@$_POST['studyplace'])
      return 'Поле "учебное заведение" является обязательным для ввода';
    if (!@$_POST['address'])
      return 'Поле "адрес" является обязательным для ввода';
    if (!@$_POST['phone'])
      return 'Поле "телефон" является обязательным для ввода';

    if (!@$_POST['contestlogin'])
      return 'Поле "login в contest-системе" является обязательным для ввода';
    if (!@$_POST['contestpassword'])
      return 'Поле "пароль в contest-системе" является обязательным для ввода';

    if (!@$_POST['contactname'])
      return 'Поле "контактное лицо" является обязательным для ввода';
    if (!@$_POST['contactphone'])
      return 'Поле "контактный телефон" является обязательным для ввода';

    if (!@$_POST['headname'])
      return 'Поле "руководитель команды" является обязательным для ввода';
    if (!@$_POST['headpost'])
      return 'Поле "руководитель команды - должность" является обязательным для ввода';

    if (!@$_POST['coachname'])
      return 'Поле "тренер команды" является обязательным для ввода';
    if (!@$_POST['coachpost'])
      return 'Поле "тренер команды - должность" является обязательным для ввода';

    if (!@$_POST['contestant1name'])
      return 'Поле "первый участник" является обязательным для ввода';
    if (!@$_POST['contestant1studyplace'])
      return 'Поле "первый участник - место учебы" является обязательным для ввода';
    if (!@$_POST['contestant1classcourse'])
      return 'Поле "первый участник - класс/курс" является обязательным для ввода';
    if (!@$_POST['contestant1age'])
      return 'Поле "первый участник - возраст" является обязательным для ввода';

    if (!@$_POST['contestant2name'])
      return 'Поле "второй участник" является обязательным для ввода';
    if (!@$_POST['contestant2studyplace'])
      return 'Поле "второй участник - место учебы" является обязательным для ввода';
    if (!@$_POST['contestant2classcourse'])
      return 'Поле "второй участник - класс/курс" является обязательным для ввода';
    if (!@$_POST['contestant2age'])
      return 'Поле "второй участник - возраст" является обязательным для ввода';

    if (!@$_POST['contestant3name'])
      return 'Поле "третий участник" является обязательным для ввода';
    if (!@$_POST['contestant3studyplace'])
      return 'Поле "третий участник - место учебы" является обязательным для ввода';
    if (!@$_POST['contestant3classcourse'])
      return 'Поле "третий участник - класс/курс" является обязательным для ввода';
    if (!@$_POST['contestant3age'])
      return 'Поле "третий участник - возраст" является обязательным для ввода';
      
    if (!@$_POST['language'])
      return 'Поле "среда программирования" является обязательным для ввода';
  }
  
  function checkFieldsLength($names, $lengths)
  {
    foreach(array_keys($names) as $key)
      if (@$_POST[$key])
        if (strlen($_POST[$key]) > $lengths[$key])
          return 'Длина поля "'.$names[$key].'" должна быть меньше '.$lengths[$key].' символа(ов).';
  }
  
  function checkValueForSymbols($value)
  {
    $restrictedSymbols = array("&", "/", "\\", "%",
                             "!", "$", "*", "^",
                             "?", "<", ">");

    for ($i=0; $i<strlen($value); $i++)
      for ($j=0; $j<11; $j++)
        if ($value[$i]==$restrictedSymbols[$j])
          return false;
    
    return true;
  }
  
  function checkSymbols($names, $lengths)
  {
    foreach(array_keys($names) as $key)
      if (@$_POST[$key])
        if (!checkValueForSymbols($_POST[$key]))
          return 'Поле "'.$names[$key].'" содержит недопустимые символы.';
  }
  
  function checkAges()
  {
    if (@$_POST['contestant1age']) {
      $x = $_POST['contestant1age'];
      settype($x, "integer");
      
      if ($x==0 || $x > 99) 
        return 'Неверно указано значение поля "первый участник - возраст".';
    }

    if (@$_POST['contestant2age']) {
      $x = $_POST['contestant2age'];
      settype($x, "integer");
      
      if ($x==0 || $x > 99) 
        return 'Неверно указано значение поля "второй участник - возраст".';
    }

    if (@$_POST['contestant3age']) {
      $x = $_POST['contestant3age'];
      settype($x, "integer");
      
      if ($x==0 || $x > 99) 
        return 'Неверно указано значение поля "третий участник - возраст".';
    }
  }
  
  function isLeapYear($year)
  {
    return ($year%400 == 0) || ($year%4 == 0 && $year%100 != 0);
  }
  
  function parseDate($value)
  {
    //сделано для возможности проставить прочерк
    if ('-' == $value)
      return '-';
  
    if (strlen($value) < 10)
      return "NULL";
    if ($value{2} != '.')
      return "NULL";
    if ($value{5} != '.')
      return "NULL";
      
    $day = substr($value, 0, 2);
    $month = substr($value, 3, 2);
    $year = substr($value, 6, 4);
    
    $_day = $day;
    $_month = $month;
    $_year = $year;
    settype($_day, 'integer');
    settype($_month, 'integer');
    settype($_year, 'integer');
    
    $_month = $_month-1;
    
    $days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    
    if ($_year < 1900 || $_year > 2009)
      return "NULL";
    if ($_month < 0 || $_month > 11)
      return "NULL";
      
    if ($_day < 1 || 
      $_month != 1 && $_day > $days[$_month] || 
      $_month == 1 && isLeapYear($_year) && $_day > 29 ||
      $_month == 1 && !isLeapYear($_year) && $_day > 28)
      return "NULL";
      
    return $day.'.'.$month.'.'.$year;
  }
  
  function checkMyDate($value)
  {
    if (parseDate($value) != "NULL")
      return true;
    else
      return false;
  }
  
  function checkDates()
  {
    if (@$_POST['headbirthdate'])
      if (!checkMyDate($_POST['headbirthdate']))
        return 'Поле "Руководитель команды - дата рождения" содержит некорректную информацию.';

    if (@$_POST['headpassportdate'])
      if (!checkMyDate($_POST['headpassportdate']))
        return 'Поле "Руководитель команды - когда выдан паспорт" содержит некорректную информацию.';

    if (@$_POST['coachbirthdate'])
      if (!checkMyDate($_POST['coachbirthdate']))
        return 'Поле "Тренер команды - дата рождения" содержит некорректную информацию.';

    if (@$_POST['coachpassportdate'])
      if (!checkMyDate($_POST['coachpassportdate']))
        return 'Поле "Тренер команды - когда выдан паспорт" содержит некорректную информацию.';

    if (@$_POST['contestant1birthdate'])
      if (!checkMyDate($_POST['contestant1birthdate']))
        return 'Поле "Первый участник - дата рождения" содержит некорректную информацию.';

    if (@$_POST['contestant1passportdate'])
      if (!checkMyDate($_POST['contestant1passportdate']))
        return 'Поле "Первый участник - когда выдан паспорт" содержит некорректную информацию.';

    if (@$_POST['contestant2birthdate'])
      if (!checkMyDate($_POST['contestant2birthdate']))
        return 'Поле "Второй участник - дата рождения" содержит некорректную информацию.';

    if (@$_POST['contestant2passportdate'])
      if (!checkMyDate($_POST['contestant2passportdate']))
        return 'Поле "Второй участник - когда выдан паспорт" содержит некорректную информацию.';

    if (@$_POST['contestant3birthdate'])
      if (!checkMyDate($_POST['contestant3birthdate']))
        return 'Поле "Третий участник - дата рождения" содержит некорректную информацию.';

    if (@$_POST['contestant3passportdate'])
      if (!checkMyDate($_POST['contestant3passportdate']))
        return 'Поле "Третий участник - когда выдан паспорт" содержит некорректную информацию.';

  }
  
  function checkPasswords()
  {
    if (!@$_POST['password'] && !@$_POST['repeatpassword'])
      return 'Поля "Пароль" и "Повтор пароля" являются обязательными для заполнения.';

    if ($_POST['password'] != $_POST['repeatpassword'])
      return 'Подтверждение пароля введено некорректно';
      
    if (strlen(@$_POST['password']) < 6)
      return 'Длина пароля должна быть не менее 6 символов.';
  }
  
  function getLoginId($login, $password)
  {
    mysql_select_db(c_DBName);

    $r = mysql_query("select id from `user` where login='".$login."' and `password`='".$password."'");
    if (mysql_num_rows($r) > 0) {
      $f = mysql_fetch_array($r);
      $result = $f['id'];
    }
    else
      $result = -1;

    mysql_select_db(t_DBName);
    
    return $result;
  }
  
  function verifyData($names, $lengths, $insert)
  {
    $result = checkMandatoryFields($names, $lengths);
    if ($result != "")
      return $result;
      
    $result = checkFieldsLength($names, $lengths);
    if ($result != "")
      return $result;
      
    $result = checkSymbols($names, $lengths);
    if ($result != "")
      return $result;
      
    $result = checkAges();
    if ($result != "")
      return $result;

    $result = checkDates();
    if ($result != "")
      return $result;
      
    $result = checkPasswords();
    if ($result != "")
      return $result;
  }
  
  function isFullFilled($fill)
  {
    $result = true;
    
    foreach(array_values($fill) as $key)
      if (!@$_POST[$key] )
        $result = false;
      
    return $result;
  }
?>
