<?php
require_once('./config/require.php');

// чекаем параметры
if (!isset($contest)) { $contest = $curcontest; }

// проверяем, существует ли запрашиваемый турнир
// todo: check на доступность турнира
$q = $mysqli_->prepare('SELECT ContestID, Name FROM cntest WHERE ContestID=? and now()>=start');
$q->bind_param('i', $contest);
$q->bind_result($requested_id, $requested_contest_name);
if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
if (!$q->fetch()) { fail(_error_requested_contest_is_not_available_code); } // auto-close query
$q->close();
// конец загрузки информации о турнире

//проверяем наличие идентификатора
if (!isset($problem)) { fail(_error_no_problem_id_code); }

//читаем данные
$q = $mysqli_->prepare('SELECT T.Name, T.Input, T.Output, T.TimeLimit, T.MemoryLimit, V.ProblemID, V.VolumeID, V.ContestID, T.TaskID, T.Text, '
        .' T.FormatIn, T.FormatOut, T.SampleIn, T.SampleOut, T.Author, T.Source'
    .' FROM Task T INNER JOIN Volume V ON T.TaskID=V.TaskID'
    .' WHERE LPAD(V.ProblemID,6,\' \')=LPAD(?,6,\' \') AND V.ContestID=?');
$q->bind_param('si', $problem, $contest);
$q->bind_result($Name, $Input, $Output, $TimeLimit, $MemoryLimit, $ProblemID, $VolumeID, $ContestID, $TaskID, $Text, $FormatIn, $FormatOut, $SampleIn, $SampleOut, $Author, $Source);
if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
if (!$q->fetch()) { fail(_error_no_task_found_code); } // auto-close query

$instance = array();
$instance['Name'] = $Name;
$instance['Input'] = $Input;
$instance['Output'] = $Output;
$instance['TimeLimit'] = $TimeLimit;
$instance['MemoryLimit'] = $MemoryLimit;
$instance['ProblemID'] = $ProblemID;
$instance['VolumeID'] = $VolumeID;
$instance['ContestID'] = $ContestID;
$instance['TaskID'] = $TaskID;
$instance['Text'] = $Text;
$instance['FormatIn'] = $FormatIn;
$instance['FormatOut'] = $FormatOut;
$instance['SampleIn'] = $SampleIn;
$instance['SampleOut'] = $SampleOut;
$instance['Author'] = $Author;
$instance['Source'] = $Source;
$instance = (object) $instance;
$q->close();

data('contest', $contest);
data('problem', $problem);
data('instance', $instance);

template('problem', $data);
?>
