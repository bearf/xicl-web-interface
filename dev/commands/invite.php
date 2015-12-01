<?php function invite($teamid, $mode) { ?>
<?php global $is_admin; global $mysqli_;
if (1 != $is_admin) { authorize(); }

// ���������
if (!isset($teamid)) { return _error_no_team_id_code; }

// ������ �� �������� ������������� �������
if (-1 < $teamid):
    $query = $mysqli_->prepare('select count(*) from `teams` T where T.teamID=?');
    $query->bind_param('i', $teamid);
    $query->bind_result($count);
    if (!$query->execute()) { fail(_error_mysql_query_error_code); } // auto-close of query 
    $query->fetch();
    if (0 == $count) { return _error_no_team_found_code; } // auto-close of query
    $query->close();
endif; // ����� �������� ������������� �������

// ���������, ��� ������� ��� �� ����������
$query = $mysqli_->prepare('select count(*) from `teams` T where T.teamID=? and T.invited=1-?');
$query->bind_param('ii', $teamid, $mode);
$query->bind_result($count);
if (!$query->execute()) { fail(_error_mysql_query_error_code); } // auto-close of query 
$query->fetch();
if (0 == $count && 1 == $mode) { return _error_team_already_invited_code; } // auto-close of query
if (0 == $count && 0 == $mode) { return _error_team_not_invited_code; } // auto-close of query
$query->close();

// ����������� �������
$query = $mysqli_->prepare('update `teams` set invited=? where teamid=?');
$query->bind_param('ii', $mode, $teamid);
if (!$query->execute() || 0 == $query->affected_rows) { fail(_error_mysql_query_error_code); } // auto-close of query 
$query->close();

// �������� ��������� �����������
global $messages;
$code = notify(
    -1
    ,$teamid
    ,1 == $mode ? $messages[_header_you_are_invited_code] : $messages[_header_you_are_fired_code]
    ,1 == $mode ? $messages[_message_you_are_invited_code] : $messages[_message_you_are_fired_code]
);
if (_success_notify_added_code != $code) { return $code; }
// ����� ������� ��������� �����������

return 1 == $mode ? _success_team_invited_code : _success_team_declined_code; ?>
<?php } ?>
