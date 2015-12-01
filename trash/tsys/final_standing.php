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
        $contestname = '������ �� �������, ���� �� ����������.';
    else {
      $error = true;
      $msg = '��������� ������ ��� ���������� �������.';
    }
  }

  $pagesize = 40;

  $msg = '';
  $error = false;
  $requested_contest_name = '';
  $rowcount = 0;

  //��������� �������� contest
  if (!@$contest)
    $contest = $curcontest;
  else {
    $x = $contest;
    settype($x, 'integer');
    if ($x==0) {
      $msg = '������� ������ ���� ��� ��������� ����������. ���������� �������� ������ ������� � ��������� �������.';
      $error = true;
    }
  }

  if (!@$page)
    $page = 1;
  else {
    $x = $page;
    settype($x, 'integer');
    if ($x==0) {
      $msg = '������� ������ ���� ��� ��������� ����������. ���������� �������� ������ ������� � ��������� �������.';
      $error = true;
    }
  }

  //���������, ���������� �� ������������� ������
  if ((!$error)&&(@$contest)) {
    $q = 'SELECT
            ContestID, Name, Contest_Kind
          FROM
            cntest
          WHERE ContestID='.$contest;

    $r = mysql_query($q);
    if (mysql_error() != '') {
      $msg = '�� ������� ���������� ������ ������. ��������� ������� �����.';
      $error = true;
    }
    else if (mysql_num_rows($r)==0) {
      $msg = '������ �� �������, ���� �� ����������.';
      $error = true;
    }
    else {
      $f = mysql_fetch_array($r);
      $kind = $f['Contest_Kind'];
      $requested_contest_name = $f['Name'];
    }
  }

  //����������� ������
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
    //������� ������, ��������� ���������� �������� �����              
    else {
      //������ �� ���������� ������ � �������
      $q = 'select problemId from volume where contestid='.$contest.' order by problemId';
      
      //�������� ������ � �������
      //� ���� ��� - �������� �� ������
      $r = mysql_query($q);
      if (mysql_error() != '') {
        $msg = '�� ������� ���������� ������ ������. ��������� ������� �����.';
        $error = true;
      }
      else {
        //������� ������� ����� � ������
        $rowcount = mysql_num_rows($r);
        $indexes = array();                                  
        
        for ($i=0; $i<$rowcount; $i++) {
          $f = mysql_fetch_array($r);
          array_push($indexes, $f['problemId']);
        }
        
        //if it's admin mode
        if (@$main_authorized == 1) {
          //������ ������� ����� ��������
          $q_select_main = 'SELECT '.
            'U.id as `ID`, '.
            'U.nickname as `Nickname`, '.
            'U.city as `city`, '.
            'U.studyplace as `studyplace`, '.
            'sum(if(S.resultid=0,1,0)) as `Solved`, '.
            'sum(S.penalty) as `Penalty` ';
          
          //��������� ������� ����� � ������� ������ ��������
          for ($i=0; $i<count($indexes); $i++) 
            $q_select_main = $q_select_main.', max(if(S.problemID=\''.$indexes[$i].'\',if(S.resultid=0,20000+attempt,10000+attempt),0)) as `'.$indexes[$i].'` ';
        
          //������ ���� ������
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
          //������ ������� ����� ��������
          $q_select_main = 'SELECT '.
            'U.id as `ID`, '.
            'U.nickname as `Nickname`, '.
            'S.Solved as `Solved`, '.
            'S.Penalty as `Penalty` ';
          $q_select_inner = 'SELECT '.
            'M.userID as `userid`, '.
            'SUM(IFNULL(M.Solved, 0)) AS `Solved`, '.
            'SUM(IFNULL(M.Penalty, 0)) AS `Penalty` ';
          
          //��������� ������� ����� � ������� ������ ��������
          for ($i=0; $i<count($indexes); $i++) {
            $q_select_main = $q_select_main.', S.'.$indexes[$i].' ';
            $q_select_inner = $q_select_inner.', sum(if (M.ProblemId=\''.$indexes[$i].'\', M.attempt*(2*M.Solved-1), 0)) as `'.$indexes[$i].'` ';
          }
        
          //������ ���� ������
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
      } //����� �������� �� ������ ��� ��������� �������� �����
    } //����� ���������� ������� �� ���������� ��������
    
    //��������� ������
    if (!$error)
      $r = mysql_query($q);
      if (mysql_error() != '') {
        $msg = '�� ������� ���������� ������ ������. ��������� ������� �����.';
        $error = true;
      }
      else
        $rowcount = mysql_num_rows($r);
  } //����� ������� ������
  
  //��������� � �������
  $monitor_time = '';
  
  //��������� �� ���������
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
          $monitor_time = ' - '.$f['length'].' [��������]';
      else if ($f['secleft'] < $f['frozetime'])
        $monitor_time = ' - '.$f['frozetimeshow'].' [���������]';
      else
        if ($f['currenttime'] < $f['finish'])
          $monitor_time = ' - '.$f['timepast'];
        else
          $monitor_time = ' - '.$f['length'].' [��������]';
    }
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=WINDOWS-1251" />
    <meta name="Author" content="����� ��������" />
    <meta name="Keywords" content="���������, ����������������, �����������, ������, ICL, ������" />
    <title>&gt; ����� ����������� ����� ��� "ICL-��� ��"</title>
    <link href="contest.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="js/monitor.js"></script>
  </head>
  <body>
<p>
<a href="./index.php">�� �������</a>
</p>
<?php
  if ($requested_contest_name != ''):
?>
          <h3>����������: <?=$requested_contest_name?><?=$monitor_time?></h3>
<?php
  else:
?>
          <h3>����������<?=$monitor_time?></h3>
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
              <th>�������</th>
              <th>������������</th>
              <th class="c">OK</th>
              <th>��������� �����</th>
<?php
    else:
      if ($kind==2):
?>
              <th>�������</th>
              <th>������������</th>
              <th class="c">OK</th>
              <th class="c">��������� �����</th>
              <th>�����</th>
<?php
      else:
?>
              <th>�������</th>
              <th>������������</th>
              <th>�����</th>
              <th>����� ��������</th>
<?php
        //������� �������� ���� - ���� ������� ������� �����
        for ($i=0; $i<count($indexes); $i++):
?>              
              <th><?=$indexes[$i]?></th>
<?php
        endfor; //����� ����� �� �������� �����
?>
              <th>OK</th>
              <th>�����</th>
<?php
      endif;
    endif; //����� ��������� ���������
?>
              </tr>
<?php
    //������� ������
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
        endif; //����� ������ ����� ������
?>
                    <td class="c"><?=$i?></td>
                    <td>
<?php
        //���� �������� �������� ��� �� � ������ ������ - 
        //���������� ������
        if (!_permission_is_user_data_secure || (@$main_authorized == 1)):
?>                           
                      <!--<a href="userinfo.php?userid=<?=$f['ID']?>"><?=$f['Nickname']?></a>-->
                      <?=$f['Nickname']?>
<?php
        //����� ������ ����� ��� ������������
        else:
?>                            
                      <?=$f['Nickname']?>
<?php
        endif; //����� �������� ���������� �� ����� ������ ������������
?>        
                    </td>
<td>
  <?=$f['city']?>
</td>
<td>
  <?=$f['studyplace']?>
</td>
<?php
        //������� ���� 3 - ���� ������ ������� �����
        if ($kind == 3):
          //���� �� �������� �����
          for ($j=0; $j<count($indexes); $j++):
            //�������� ��������, ��������������� ������������ ��������
            $value = $f[$indexes[$j]];
            
            //if attempts' count >= 20000 then task is solved
            if ($value >= 20000)
              $value -= 20000;
              
            if ($value >= 10000)
              $value = 10000 - $value;
            
            //if attempts' count >= 10000 then task is notsolved
            
            //���� ������ ������ � ������ ������� - ������� +
            if ($value == 1)
              $value = '+';
            //���� ������ ������ - ������� +<���������� ��������� �������>
            elseif ($value > 1)
              $value = '+'.($value-1);
            //������� �� ���� - ������� .
            elseif ($value == 0)
              $value = '.';
?>
                    <td class="c"><?=$value?></td>
<?php
          endfor; //����� ����� �� �������� �����
        endif; //����� �������� �� ������������� ��������� �������� �����
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
        endif; //����� ��������� ������ � ����������� �������� �����

        if (($kind==1)||($kind==2)):
          if (@($f['Solved']>0)&&(@$f['LastAC'])):
?>
                    <td class="c"><?=$f["LastAC"]?></td>
<?
          else:
?>
                    <td class="c">-</td>
<?
          endif; //����� ��������� ������ � ����� ���������� AC
          if ($kind==2):
            if (@$f['Points']):
?>
                    <td class="c"><?=$f['Points']?></td>
<?
            else:
?>
                    <td class="c">-</td>
<?
            endif; //����� ��������� ������ � �������
          endif; //����� ��������� ������ ��� $kind=2
        else:
          if (!@$penalty)
            $penalty = 0;
          $penalty = $f['Penalty']/60;
          settype($penalty,'integer');
?>
                    <td class="c"><?=$penalty?></td>
<?
        endif; //����� ��������� ������d
?>
                  </tr>
<?
      endif; //����� �������� ��������� ������
    endfor; //����� ������� �� �������
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
          <p>��������:
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
        endif; //����� ������ ������ �������� � ����� �� �������
?>
          </p>
<?
    endif; //����� ������ ������� �������
  endif; //����� ������ ������
?>
    <script type="text/javascript">
      window.setTimeout('refreshMonitor()', 60000);
    </script>
  </body>
</html>