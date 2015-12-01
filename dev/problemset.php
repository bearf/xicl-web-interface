<?php
require_once('./config/require.php');

// todo: check если в контесте всего один том

// чекаем параметры
if (!isset($contest)) { $contest = $curcontest; }

//проверяем, существует ли запрашиваемый турнир
$fail_query = ''; // если контест некорректен, задачи запрашиваться не будут
$q = $mysqli_->prepare('SELECT ContestID, Name FROM cntest WHERE ContestID=? and now()>=start');
$q->bind_param('i', $contest);
$q->bind_result($requested_id, $requested_contest_name);
if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
if (!$q->fetch()) { $fail_query = ' LIMIT 0'; } // auto-close query
$q->close();
// конец загрузки информации о турнире

//проверяем наличие тома в запросе
//если в текущем контесте только один том -
//выбираем его и не отображаем список томов
if (!isset($volume)) {
    $q = $mysqli_->prepare('SELECT DISTINCT Volume_ID FROM volume_names WHERE Volume_Contest_ID=?'.$fail_query);
    $q->bind_param('i', $contest);
    $q->bind_result($tmp);
    if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
    // todo: узнавать, что только одна запись другим способом
    if ($q->fetch() && !$q->fetch()) { $volume = $tmp; }
    $q->close();
}

//составляем SQL-запрос и выполняем его (если тома нет)
if (!isset($volume)) {
    $q = $mysqli_->prepare('SELECT Volume_ID, Volume_Brief, Volume_Name FROM volume_names WHERE Volume_Contest_ID=? ORDER BY Volume_ID'.$fail_query);
    $q->bind_param('i', $contest);
    $q->bind_result($Volume_ID, $Volume_Brief, $Volume_Name);
    if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
    $volumes = array();
    while ($q->fetch()) {
        $instance = array();
        $instance['Volume_ID'] = $Volume_ID;
        $instance['Volume_Brief'] = $Volume_Brief;
        $instance['Volume_Name'] = $Volume_Name;
        $instance = (object) $instance;
        array_push($volumes, $instance);
    }
    $q->close();
}

//составляем SQL-запрос и выполняем его (если том есть)
if (isset($volume)) {
    $q = 
        'SELECT LPAD(V.ProblemID, 6, \' \') AS `ProblemID`, T.TaskID, T.Name
         FROM volume V INNER JOIN task T ON T.TaskID=V.TaskID
         WHERE V.ContestID=? AND V.VolumeID=?
         ORDER BY ProblemID'.$fail_query;
    $q = $mysqli_->prepare($q);
    $q->bind_param('is', $contest, $volume);
    $q->bind_result($ProblemID, $TaskID, $Name);
    if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
    $problems = array();
    while ($q->fetch()) {
        $instance = array();
        $instance['ProblemID'] = $ProblemID;
        $instance['TaskID'] = $TaskID;
        $instance['Name'] = $Name;
        $instance = (object) $instance;
        array_push($problems, $instance);
    }
    $q->close();

    foreach ($problems as $problem) {
        $q = $mysqli_->prepare('SELECT count(*) from submit S INNER JOIN testing T ON S.submitId = T.submitId where T.result = 0 AND S.contestid=? and S.problemid=? and S.resultid=0 and S.userid=?');
        $q->bind_param('isi', $contest, trim($problem->ProblemID), $curuserid);
        $q->bind_result($var);
        $q->execute(); $q->fetch(); $q->close();

        $q = $mysqli_->prepare('SELECT COUNT(*) FROM submit S INNER JOIN testing T ON S.submitId = T.submitId WHERE T.result = 0 AND S.contestId = ? AND S.resultId = 0 AND S.problemId = ?');
        $q->bind_param('is', $contest, trim($problem->ProblemID));
        $q->bind_result($problem->Solved);
        $q->execute(); $q->fetch(); $q->close();

        $q = $mysqli_->prepare('SELECT COUNT(*) FROM submit S INNER JOIN testing T ON S.submitId = T.submitId WHERE T.result = 0 AND S.contestId = ? AND S.problemId = ? and S.userid=?');
        $q->bind_param('isi', $contest, trim($problem->ProblemID), $curuserid);
        $q->bind_result($problem->MyAttempt);
        $q->execute(); $q->fetch(); $q->close();

        $q = $mysqli_->prepare('SELECT COUNT(*) FROM submit S INNER JOIN testing T ON S.submitId = T.submitId WHERE T.result = 0 AND S.contestId = ? AND S.problemId = ?');
        $q->bind_param('is', $contest, trim($problem->ProblemID));
        $q->bind_result($problem->Attempt);
        $q->execute(); $q->fetch(); $q->close();

        $q = $mysqli_->prepare('SELECT count(*) from questions Q WHERE Q.taskId=? and Q.result = 0 and comment is null');
        $q->bind_param('i', $problem->TaskID);
        $q->bind_result($problem->questions);
        $q->execute(); $q->fetch(); $q->close();
    }
}

data('requested_contest_name', $requested_contest_name);
data('contest', $contest);
if (isset($volume)) { data('volume', $volume); }
if (isset($volumes)) { data('volumes', $volumes); }
if (isset($problems)) { data('problems', $problems); }

template('problemset', $data);
?>
