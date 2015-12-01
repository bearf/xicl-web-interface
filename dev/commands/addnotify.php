<?php function notify($touser, $toteam, $header, $notify, $allow_non_admin = false) { ?>
<?php global $is_admin; global $mysqli_;
if (!$allow_non_admin && 1 != $is_admin) { authorize(); }

// параметры
if (!isset($touser)) { return _error_no_user_id_code; }
if (!isset($toteam)) { return _error_no_team_id_code; }
// проверяем на непустоту уведомления
if (!isset($header) || '' == $header) { return _error_notify_header_is_empty_code; }
if (!isset($notify) || '' == $notify) { return _error_notify_is_empty_code; }
// проверяем на размер исходника
if (strlen($notify) > _param_notify_max_length) { return _error_notify_is_too_large_code; }

// запрос на проверку существования пользователя
if (-1 < $touser):
    $query = $mysqli_->prepare('select count(*) from `user` U where U.ID=?');
    $query->bind_param('i', $touser);
    $query->bind_result($count);
    if (!$query->execute()) { fail(_error_mysql_query_error_code); } // auto-close of query 
    $query->fetch();
    if (0 == $count) { return _error_no_user_found_code; } // auto-close of query
    $query->close();
endif; // конец проверки существования пользователя

// запрос на проверку существования команды
if (-1 < $toteam):
    $query = $mysqli_->prepare('select count(*) from `teams` T where T.teamID=?');
    $query->bind_param('i', $toteam);
    $query->bind_result($count);
    if (!$query->execute()) { fail(_error_mysql_query_error_code); } // auto-close of query 
    $query->fetch();
    if (0 == $count) { return _error_no_team_found_code; } // auto-close of query
    $query->close();
endif; // конец проверки существования команды

// добавляем исходник
global $curuserid;
$query = $mysqli_->prepare('insert into `messages`(date, `from`, header, message, kind, touser, toteam) values(now(), ?, ?, ?, 2, ?, ?)');
$query->bind_param('issii', $curuserid, stripslashes($header), stripslashes($notify), $touser, $toteam);
if (!$query->execute() || 0 == $query->affected_rows) { fail(_error_mysql_query_error_code); } // auto-close of query 
$query->close();

return _success_notify_added_code; ?>
<?php } ?>
