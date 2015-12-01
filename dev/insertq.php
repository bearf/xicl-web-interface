<?php
require_once('./config/require.php');

$forward = getenv('HTTP_X_FROWARDED_FROM') ? getenv('HTTP_X_FROWARDED_FROM') : 'no HTTP_X_FROWARDED_FROM';
$ip = getenv('REMOTE_ADDR');
    
// todo: ���������� ������� ���������
if (!isset($question) || '' === $question) {
    data('message', '�� ������ ������.');
    template('message', $data);
}

elseif (!CheckSym($question)) {
    data('message', '������ �������� ������������ �������.');
    template('message', $data);
} 

$q = $mysqli_->prepare('insert into faq(date, question, curuserid, ip, forward) values(now(), ?, ?, ?, ?)');
$q->bind_param('siss', stripslashes($question), $curuserid, $ip, $forward);
if (!$q->execute() || 1 != $q->affected_rows) { fail(_error_mysql_query_error_code); } // auto-close query
$q->close();

data('message', '��� ������ ��� ������� ��������.');
template('message', $data);
?>
