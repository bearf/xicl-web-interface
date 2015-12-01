<?php
require_once('./config/require.php');

// todo: check all usecases
// todo: check отсутствие prepared statement

// чекаем параметры
if (!isset($contest)) { $contest = $curcontest; }
// тепер... если у нас данные пользователя защищены
// то нужно взять curuserid и подставить в текущий id
if (!_permission_allow_view_all_submits && 1 != $is_admin) { $userid = $curuserid; }

//проверяем, существует ли запрашиваемый турнир
$q = $mysqli_->prepare('SELECT ContestID, Name, Contest_Kind FROM cntest WHERE ContestID=?');
$q->bind_param('i', $contest);
$q->bind_result($requested_id, $requested_contest_name, $kind);
if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
if (!$q->fetch()) { fail(_error_no_contest_found_code); } // auto-close query
data('requested_contest_name', $requested_contest_name);
$q->close();
// конец загрузки информации о турнире

//извлечение данных
// если добрались до сюда, то $contest корректен
$q = 'SELECT'
    .' S.SubmitID, S.ProblemID, U.ID, U.Nickname, L.Ext, S.Message, S.StatusID, S.ResultID, S.SubmitTime, S.TotalTime, S.TotalMemory'
    .' FROM Submit S, User U, Lang L'
    .' WHERE S.UserID = U.ID AND S.LangID = L.LangID AND S.ContestID = ?';
if (isset($problem)) { $q .= ' AND S.ProblemID = ?'; }
if (isset($userid)) { $q .= ' AND S.UserID = ?'; }
if (isset($top)) { $q .= ' AND S.SubmitID <= ?'; }
$q .= ' ORDER BY S.SubmitID DESC LIMIT 10';
$q = $mysqli_->prepare($q);
if (isset($problem) && isset($userid) && isset($top)):
    $q->bind_param('isii', $contest, $problem, $userid, $top);
elseif(isset($problem) && isset($userid)):
    $q->bind_param('isi', $contest, $problem, $userid);
elseif(isset($problem) && isset($top)):
    $q->bind_param('isi', $contest, $problem, $top);
elseif(isset($userid) && isset($top)):
    $q->bind_param('iii', $contest, $userid, $top);
elseif(isset($problem)):
    $q->bind_param('is', $contest, $problem);
elseif(isset($userid)):
    $q->bind_param('ii', $contest, $userid);
elseif(isset($top)):
    $q->bind_param('ii', $contest, $top);
else:
    $q->bind_param('i', $contest);
endif;
$q->bind_result($SubmitID, $ProblemID, $ID, $Nickname, $Ext, $Message, $StatusID, $ResultID, $SubmitTime, $TotalTime, $TotalMemory);
if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
$status = array();
while ($q->fetch()) {
    if (0 == count($status)) { $first = $SubmitID; }
    $last = $SubmitID;
    $f = array();
    $f['SubmitID'] = $SubmitID;
    $f['ProblemID'] = $ProblemID;
    $f['ID'] = $ID;
    $f['Nickname'] = $Nickname;
    $f['Ext'] = $Ext;
    $f['Message'] = $Message;
    $f['StatusID'] = $StatusID;
    $f['ResultID'] = $ResultID;
    $f['SubmitTime'] = $SubmitTime;
    $f['TotalTime'] = $TotalTime;
    $f['TotalMemory'] = $TotalMemory;
    array_push($status, (object) $f);
}
data('status', $status);
$q->close();

// отображение ссылок на следующие страницы статуса
// если добрались досюда, то проблем с параметрами не должно быть
if (!isset($first)) { $first = 1000000000; }
if (!isset($last)) { $last = -1; }
$params = '&contest='.$contest;
$where  = ' AND ContestID=?';
if (isset($problem)) {
    $params .= '&problem='.$problem;
    $where  .= ' AND ProblemID=?';
}
if (isset($userid)) {
    $params .= '&userid='.$userid;
    $where  .= ' AND UserID=?';
}
$topparams = '' === $params ? $params : substr($params, 1, strlen($params));
// Наверх на 10 - SubmitID > first
// first указан совершенно точно, поэтому его можно использовать
// не чекаем здесь prepared statement
$q = 'SELECT SubmitID FROM Submit WHERE SubmitID>?'.$where.' ORDER BY SubmitID ASC LIMIT 10';
$q = $mysqli_->prepare($q);
if (isset($problem) && isset($userid)):
    $q->bind_param('iisi', $first, $contest, $problem, $userid);
elseif (isset($problem)):
    $q->bind_param('iis', $first, $contest, $problem);
elseif (isset($userid)):
    $q->bind_param('iii', $first, $contest, $userid);
else:
    $q->bind_param('ii', $first, $contest);
endif;
$q->bind_result($tmp);
if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
// нам нужна последняя выбранная запись
// поэтому мы смещаемся
$top_submit = -1;
while ($q->fetch()) { $top_submit = $tmp; }
$q->close();
// Вниз на 10 - SubmitID < last
// last также точно определен, поэтому его можно использовать
// todo: не используем prepared statement
$q = $mysqli_->prepare('SELECT SubmitID FROM Submit WHERE SubmitID<?'.$where.' ORDER BY SubmitID DESC LIMIT 10');
if (isset($problem) && isset($userid)):
    $q->bind_param('iisi', $last, $contest, $problem, $userid);
elseif (isset($problem)):
    $q->bind_param('iis', $last, $contest, $problem);
elseif (isset($userid)):
    $q->bind_param('iii', $last, $contest, $userid);
else:
    $q->bind_param('ii', $last, $contest);
endif;
$q->bind_result($tmp);
if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
// а в данном случае нам нужна самая первая запись
$bottom_submit = -1;
if ($q->fetch()) { $bottom_submit = $tmp; }
data('params', $params);
data('topparams', $topparams);
data('top_submit', $top_submit);
data('contest', $contest);
data('bottom_submit', $bottom_submit);
data('first_submit', $first);
$q->close();
// конец запроса информации на следующие страницы статуса

template('status', $data);
?>
