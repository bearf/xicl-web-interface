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
      return '���� "�������� �������" �������� ������������ ��� �����';
    if (!@$_POST['city'])
      return '���� "�����" �������� ������������ ��� �����';
    if (!@$_POST['studyplace'])
      return '���� "������� ���������" �������� ������������ ��� �����';
    if (!@$_POST['address'])
      return '���� "�����" �������� ������������ ��� �����';
    if (!@$_POST['phone'])
      return '���� "�������" �������� ������������ ��� �����';

    if (!@$_POST['contestlogin'])
      return '���� "login � contest-�������" �������� ������������ ��� �����';
    if (!@$_POST['contestpassword'])
      return '���� "������ � contest-�������" �������� ������������ ��� �����';

    if (!@$_POST['contactname'])
      return '���� "���������� ����" �������� ������������ ��� �����';
    if (!@$_POST['contactphone'])
      return '���� "���������� �������" �������� ������������ ��� �����';

    if (!@$_POST['headname'])
      return '���� "������������ �������" �������� ������������ ��� �����';
    if (!@$_POST['headpost'])
      return '���� "������������ ������� - ���������" �������� ������������ ��� �����';

    if (!@$_POST['coachname'])
      return '���� "������ �������" �������� ������������ ��� �����';
    if (!@$_POST['coachpost'])
      return '���� "������ ������� - ���������" �������� ������������ ��� �����';

    if (!@$_POST['contestant1name'])
      return '���� "������ ��������" �������� ������������ ��� �����';
    if (!@$_POST['contestant1studyplace'])
      return '���� "������ �������� - ����� �����" �������� ������������ ��� �����';
    if (!@$_POST['contestant1classcourse'])
      return '���� "������ �������� - �����/����" �������� ������������ ��� �����';
    if (!@$_POST['contestant1age'])
      return '���� "������ �������� - �������" �������� ������������ ��� �����';

    if (!@$_POST['contestant2name'])
      return '���� "������ ��������" �������� ������������ ��� �����';
    if (!@$_POST['contestant2studyplace'])
      return '���� "������ �������� - ����� �����" �������� ������������ ��� �����';
    if (!@$_POST['contestant2classcourse'])
      return '���� "������ �������� - �����/����" �������� ������������ ��� �����';
    if (!@$_POST['contestant2age'])
      return '���� "������ �������� - �������" �������� ������������ ��� �����';

    if (!@$_POST['contestant3name'])
      return '���� "������ ��������" �������� ������������ ��� �����';
    if (!@$_POST['contestant3studyplace'])
      return '���� "������ �������� - ����� �����" �������� ������������ ��� �����';
    if (!@$_POST['contestant3classcourse'])
      return '���� "������ �������� - �����/����" �������� ������������ ��� �����';
    if (!@$_POST['contestant3age'])
      return '���� "������ �������� - �������" �������� ������������ ��� �����';
      
    if (!@$_POST['language'])
      return '���� "����� ����������������" �������� ������������ ��� �����';
  }
  
  function checkFieldsLength($names, $lengths)
  {
    foreach(array_keys($names) as $key)
      if (@$_POST[$key])
        if (strlen($_POST[$key]) > $lengths[$key])
          return '����� ���� "'.$names[$key].'" ������ ���� ������ '.$lengths[$key].' �������(��).';
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
          return '���� "'.$names[$key].'" �������� ������������ �������.';
  }
  
  function checkAges()
  {
    if (@$_POST['contestant1age']) {
      $x = $_POST['contestant1age'];
      settype($x, "integer");
      
      if ($x==0 || $x > 99) 
        return '������� ������� �������� ���� "������ �������� - �������".';
    }

    if (@$_POST['contestant2age']) {
      $x = $_POST['contestant2age'];
      settype($x, "integer");
      
      if ($x==0 || $x > 99) 
        return '������� ������� �������� ���� "������ �������� - �������".';
    }

    if (@$_POST['contestant3age']) {
      $x = $_POST['contestant3age'];
      settype($x, "integer");
      
      if ($x==0 || $x > 99) 
        return '������� ������� �������� ���� "������ �������� - �������".';
    }
  }
  
  function isLeapYear($year)
  {
    return ($year%400 == 0) || ($year%4 == 0 && $year%100 != 0);
  }
  
  function parseDate($value)
  {
    //������� ��� ����������� ���������� �������
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
        return '���� "������������ ������� - ���� ��������" �������� ������������ ����������.';

    if (@$_POST['headpassportdate'])
      if (!checkMyDate($_POST['headpassportdate']))
        return '���� "������������ ������� - ����� ����� �������" �������� ������������ ����������.';

    if (@$_POST['coachbirthdate'])
      if (!checkMyDate($_POST['coachbirthdate']))
        return '���� "������ ������� - ���� ��������" �������� ������������ ����������.';

    if (@$_POST['coachpassportdate'])
      if (!checkMyDate($_POST['coachpassportdate']))
        return '���� "������ ������� - ����� ����� �������" �������� ������������ ����������.';

    if (@$_POST['contestant1birthdate'])
      if (!checkMyDate($_POST['contestant1birthdate']))
        return '���� "������ �������� - ���� ��������" �������� ������������ ����������.';

    if (@$_POST['contestant1passportdate'])
      if (!checkMyDate($_POST['contestant1passportdate']))
        return '���� "������ �������� - ����� ����� �������" �������� ������������ ����������.';

    if (@$_POST['contestant2birthdate'])
      if (!checkMyDate($_POST['contestant2birthdate']))
        return '���� "������ �������� - ���� ��������" �������� ������������ ����������.';

    if (@$_POST['contestant2passportdate'])
      if (!checkMyDate($_POST['contestant2passportdate']))
        return '���� "������ �������� - ����� ����� �������" �������� ������������ ����������.';

    if (@$_POST['contestant3birthdate'])
      if (!checkMyDate($_POST['contestant3birthdate']))
        return '���� "������ �������� - ���� ��������" �������� ������������ ����������.';

    if (@$_POST['contestant3passportdate'])
      if (!checkMyDate($_POST['contestant3passportdate']))
        return '���� "������ �������� - ����� ����� �������" �������� ������������ ����������.';

  }
  
  function checkPasswords()
  {
    if (!@$_POST['password'] && !@$_POST['repeatpassword'])
      return '���� "������" � "������ ������" �������� ������������� ��� ����������.';

    if ($_POST['password'] != $_POST['repeatpassword'])
      return '������������� ������ ������� �����������';
      
    if (strlen(@$_POST['password']) < 6)
      return '����� ������ ������ ���� �� ����� 6 ��������.';
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
