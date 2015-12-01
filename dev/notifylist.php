<?php
require_once('./config/require.php');

if (1 != $authorized) { authorize(); }

// запрос списка пользователей
if (1 == $is_admin):
    $r = $mysqli_->query('select id as userid, nickname from `user` order by nickname');
    if (!$r) { fail(_error_mysql_query_error_code); }
    $users = array();
    while($f = $r->fetch_object()) { array_push($users, $f); }
    data('users', $users);
    $r->close();
endif; // конец запроса списка пользователей

// запрос списка команд
if (1 == $is_admin):
    $r = $mysqli_->query('select teamid, teamname from `teams` order by teamname');
    if (!$r) { fail(_error_mysql_query_error_code); }
    $teams = array();
    while($f = $r->fetch_object()) { array_push($teams, $f); }
    data('teams', $teams);
    $r->close();
endif; // конец запроса списка команд

$rowcount = 0;
$pagesize = 10;
if (!isset($page)) { $page = 1; };
$first = ($page-1)*$pagesize;
$q = $mysqli_->prepare('SELECT M.messageid, M.date, M.header, M.message, (SELECT count(*) FROM `reads` R WHERE R.messageid=M.messageid and R.userid=?) as `read` FROM `messages` M WHERE M.kind=2 and (M.touser=? and M.touser<>-1 or M.toteam=? and M.toteam<>-1 or M.touser=-1 and M.toteam=-1) and M.date > (select U.regdate from `user` U where U.id=?) ORDER BY M.messageid DESC');
$q->bind_param('iiii', $curuserid, $curuserid, $curteamid, $curuserid);
$q->bind_result($notifyid, $date, $header, $notify, $read);
if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
$notifies = array(); $index = 0; $rowcount = 0;
while ($q->fetch()) { 
    $index++; $rowcount++;
    $f = array();
    $f['notifyid'] = $notifyid; $f['date'] = $date; $f['header'] = $header; $f['notify'] = $notify; $f['read'] = $read;
    if ($index > $first && $index < $first + $pagesize + 1) { array_push($notifies, (object) $f); }
}
$pagecount = $rowcount / $pagesize;
if ($rowcount % $pagesize != 0) { $pagecount += 1; }
$q->close();

if (isset($code)) { data('message', $messages[$code]); }
data('notifies', $notifies);
data('pagecount', $pagecount);
data('page', $page);

template('notifylist', $data);
?>

