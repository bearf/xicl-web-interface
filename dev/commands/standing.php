<?php function standing($contest, $page, $pagesize, $use_indexes = true) { ?>
<?php
// todo: в жопу глобальные переменные
// todo: возвращать data как результат
global $mysqli_;
global $is_admin;

$from_row = ($page-1) * $pagesize;

data('contest', $contest);
data('page', $page);
data('pagesize', $pagesize);


//проверяем, существует ли запрашиваемый турнир
$q = $mysqli_->prepare('SELECT ContestID, Name, Contest_Kind, start FROM cntest WHERE ContestID=?');
$q->bind_param('i', $contest);
$q->bind_result($requested_id, $requested_contest_name, $kind, $contest_start);
if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
if (!$q->fetch()) { fail(_error_no_contest_found_code); } // auto-close query
data('requested_contest_name', $requested_contest_name);
data('kind', $kind);
$q->close();
// конец загрузки информации о турнире




// теперь грузим названия команд
$teams = array();
$q = $mysqli_->query('select u.id as `id`, u.nickname as `nickname`, t.teamid as `teamid`, t.teamname as `teamname`, t.invited as `invited`, t.education as `studyplace` from (user u left join members m on u.id = m.userid) left join teams t on m.teamid=t.teamid');
if (!$q) { fail(_error_mysql_query_error_code); } // auto-close query
while ($f = $q->fetch_object()) { $teams[$f->id] = $f; }
$q->close();
data('teams', $teams);
// конец загрузки названий команд
  


//проверяем на заморозку
$q = $mysqli_->prepare('select '.
        'now() as `currenttime`, '.
        'finish as `finish`, '.
        'start as `start`, '.
        'timediff(now(), start) as `timepast`, '.
        'time_to_sec(timediff(finish, now())) as `secleft`, '.
        'sec_to_time(time_to_sec(timediff(finish, start)) - frozetime) as `frozetimeshow`, '.
        'timediff(finish, start) as `length`, '.
        'isneedfreeze, '.
        'frozetime '.
    'from cntest C '.
    'where C.contestId=?');
    //' and C.start<=now() and C.finish>=now()';
$q->bind_param('i', $contest);
$q->bind_result($currenttime, $finish, $start, $timepast, $secleft, $frozetimeshow, $length, $isneedfreeze, $frozetime);
if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
if (!$q->fetch()) { fail(_error_no_contest_found_code); } // auto-close query
if ($kind == 1 || $kind == 2) {
    $monitor_time = '';
} elseif ($isneedfreeze && $is_admin == 0 && $secleft < $frozetime) {
    $monitor_time = ' - '.$frozetimeshow.' [заморожен]';
} else {
    $monitor_time = $currenttime < $finish && $currenttime > $start ? ' - '.$timepast : ($currenttime < $start ? ' - [не начат]' : ' - '.$length.' [завершен]');
}
data('notstarted', $currenttime < $start);
data('monitor_time', $monitor_time);
$q->close();
// конец проверки на заморозку



//запрос на извлечение данных о задачах
$q = $mysqli_->prepare('select V.problemId, V.division, T.name, if(0 = V.added, null, V.added) from volume V inner join task T on V.taskId=T.taskId where contestid=? order by problemId');
$q->bind_param('i', $contest);
$q->bind_result($tmp_problem_id, $division, $tmp_name, $tmp_added);
$indexes = array(); $names = array(); $added = array(); $problems = array();
if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
while ($q->fetch()) {
    $problem = array(); $problem['division'] = $division;
    $problems[] = (object) $problem;
    array_push($indexes, $tmp_problem_id);
    array_push($names, $tmp_name);
    array_push($added, null == $tmp_added ? $contest_start : $tmp_added);
}
data('indexes', $indexes);
data('names', $names);
data('problems', $problems);
$q->close();
// конец запроса данных о задачах



//запрашиваем данные
// если мы дошли до этого места, то $contest уже содержит нормальное значение
// и нам не нужны prepared statements
// todo: все равно их использовать
//(`User` U LEFT JOIN `Monitor` M1 ON U.ID=M1.UserID)
if ($kind==1) {
    $qfields = 'SELECT M1.UserID as ID, SUM(M1.Solved) AS `Solved`, MAX(M1.Date) AS `LastAC`';
    $qquery =
         ' FROM Monitor M1'
        .' WHERE M1.ContestID = '.$contest.' OR M1.ContestID IS NULL'
        .' GROUP BY M1.UserId'
        ;
    $qorder =
         ' ORDER BY Solved DESC, LastAC ASC, M1.UserId ASC'
        .' LIMIT '.$from_row.', '.$pagesize
        ;
} elseif ($kind==2) {
    $qfields = 'SELECT U.ID, SUM(M1.Solved) AS `Solved`, MAX(M1.Date) AS `LastAC`, SUM(M1.RealPts) AS `Points`';
    $qquery =
         ' FROM (`User` U INNER JOIN `Monitor` M1 ON U.ID=M1.UserID)'
        .' WHERE M1.ContestID = '.$contest
        .' GROUP BY U.ID'
        ;
    $qorder =
         ' ORDER BY Solved DESC, LastAC ASC, Points DESC, U.ID ASC'
        .' LIMIT '.$from_row.', '.$pagesize
        ;
// сложный запрос, потребует извлечения индексов задач              
} else {
    $qatt   = array();
    $qtime  = array();
    foreach($indexes as $_ => $problem) {
        $qatt[]     = sprintf('MAX(IF(M.problemid=\'%s\', IF(NOT(ISNULL(M.okid)), M.attempt, -M.attempt), NULL)) as `A%s`',   
            $problem, $problem);
        $qtime[]    = sprintf('MAX(IF(M.problemid=\'%s\' AND NOT(ISNULL(M.okid)), M.secdiff, NULL)) as `T%s`',
            $problem, $problem);
    }
    // 59 sec - to avoid 0:00:01 submit be threaten as 0min penalty
    define('DIFF', 59);
    $qfields = sprintf(implode(' ', array(
        'SELECT', implode(',', array(
                'M.userID as ID'
            ,   'SUM(IF(NOT(ISNULL(M.okid)), 1, 0)) as `Solved`'
            ,   'SUM(IF(NOT(ISNULL(M.okid)), (M.attempt-1)*M.timePenalty*60 + M.secdiff, 0)) + %d as `Penalty`'
            ,   'SUM(M.pts) as `Points`'
            ,   implode(',', $qatt)
            ,   implode(',', $qtime)
        ))
    )), DIFF);
    $qquery = sprintf(implode(' ', array(
                ''
            ,   'FROM ('
            ,       'SELECT P.userid, P.problemid, P.attempt, P.added, P.start, P.timePenalty, P.pointPenalty, P.oktime, P.okid'
            ,           ',  IF(P.pts > (P.attempt-1)*P.pointPenalty, P.pts - (P.attempt-1)*P.pointPenalty, LEAST(P.pts, 1)) as `pts`'
            ,           ',  TIME_TO_SEC(TIMEDIFF(P.oktime, IF(P.added < P.start, P.start, P.added))) as `secdiff`'
            ,       'FROM ('
            ,           'SELECT R.contestid, R.userid, R.problemid, COUNT(*) as `attempt`, MAX(R.pts) as `pts`, R.okid, R.oktime'
            ,               ',  (SELECT added FROM volume WHERE contestid=R.contestid and problemid=R.problemid) as `added`'
            ,               ',  (SELECT start FROM cntest WHERE contestid=R.contestid) as `start`'
            ,               ',  (SELECT timePenalty FROM cntest WHERE contestid=R.contestid) as `timePenalty`'
            ,               ',  (SELECT pointPenalty FROM cntest WHERE contestid=R.contestid) as `pointPenalty`'
            ,           'FROM ('
            ,               'SELECT SL.contestid, SU.id as userid, SL.problemid, SL.message, SL.pts, SR.okid, SR.oktime'
            ,               'FROM (('
            ,                   'SELECT submit.PTS, submit.contestid, submit.submitid, submit.userid, submit.problemid, submit.message'
            ,                   'FROM (submit INNER JOIN cntest ON submit.contestid=cntest.contestid) INNER JOIN testing T ON submit.submitId = T.submitId'
            ,                   'WHERE cntest.contestid=%d AND (%s cntest.isneedfreeze = 0 OR cntest.isneedfreeze = 1 AND TIME_TO_SEC(TIMEDIFF(cntest.finish, submit.submittime)) >= cntest.frozetime)'
            ,                   'AND submit.detached=0'
            ,                   'AND T.result=0 AND (totalTime>0 OR totalMemory>0)'
            ,                   'AND submit.resultid <> 21 /* CE */'
            ,                   'AND submit.resultid <> 255 /* FL */'
            ,               ') SL LEFT JOIN ('
            ,                   'SELECT userid, problemid, min(S.submitid) as `okid`, MIN(submittime) as `oktime`'
            ,                   'FROM (submit S INNER JOIN cntest ON S.contestid=cntest.contestid) INNER JOIN testing T ON S.submitId = T.submitId'
            ,                   'WHERE cntest.contestid=%d AND (%s cntest.isneedfreeze = 0 OR cntest.isneedfreeze = 1 AND TIME_TO_SEC(TIMEDIFF(cntest.finish, S.submittime)) >= cntest.frozetime)'
            ,                   'AND S.detached=0'
            ,                   'AND T.result = 0 AND message=\'OK\''
            ,                   'GROUP BY userid, problemid'
            ,               ') SR ON SL.userid=SR.userid AND SL.problemid=SR.problemid'
            ,               ') %s JOIN user SU on SU.id = SL.userid'
            ,               'WHERE'
            ,                   '(SL.submitid <= SR.okid OR ISNULL(SR.okid)) AND %s'
            ,           ') R'
            ,           'GROUP BY R.contestid, R.userid, R.problemid'
            ,       ') P'
            ,   ') M'
            ,   'GROUP BY M.userid'
        )),
        $contest,
        1 == $is_admin ? '1=1 OR' : '',
        $contest,
        1 == $is_admin ? '1=1 OR' : '',
        _settings_show_all_users_in_monitor ? 'RIGHT' : 'INNER',
        !isset($_GET['div']) || '0' == $_GET['div'] ? '1=1' : 'SU.division = '.$_GET['div']
    );
    $qorder = implode(' ', array(
        'ORDER BY', implode(',', array(
                'Solved DESC'
            ,   'Penalty ASC'
            ,   'Points DESC'
            ,   'ID ASC'
        )), 'LIMIT', implode(',', array(
                $from_row
            ,   $pagesize
        ))
    ));
}
// конец построения запроса на извлечение монитора


// выполнение запроса
$r = $mysqli_->query(implode(' ', array(
    $qfields, $qquery, $qorder)));
if (!$r) { fail(_error_no_contest_found_code); } // auto-close query
$standing = array();
while ($f = $r->fetch_object()) {
    array_push($standing, $f);
}
data('first', $from_row);
data('standing', $standing);
$r->close();
//конец запроса данных



// общее количество строк в мониторе
$r = $mysqli_->query('select count(*) as `count` from ('.$qfields.$qquery.') S');
if (!$r) { fail(_error_mysql_query_error_code); } // auto-close query
if (!$f = $r->fetch_object()) { fail(_error_mysql_query_error_code); } // auto-close query
data('rowcount', $f->count);
$r->close();
// конец подсчета общего количества строк в мониторе
?>
<?php } ?>

