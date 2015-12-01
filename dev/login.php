<?php
require_once('./config/require.php');

if (isset($firstattempt)) { authorize(); }

// ������� �����
if (isset($login) && isset($password)) {
    if (time() - $login_lastaccess < antiSpamTimeOut) {
        $header = '�� ������� ����� � �������';
        $message = '� ����� ������ �� ����� �� ����������� ������ ������� ����� � ������� � ���������� ����� ���� ����� '.antiSpamTimeOut.' ������. ���������� ��������� ������ �����.';
    } else {
        $q = $mysqli_->prepare('SELECT id,nickname,is_admin FROM `user` WHERE login=? AND `password`=password(?)');
        $q->bind_param('ss', $login, $password);
        $q->bind_result($curuserid, $curnickname, $is_admin);
        if ($q->execute() && $q->fetch()) {
            $authorized = 1;
            $curlogin = $login;
            $curpass = $password;
            $message = '���� � ������� ���������� �������';
        } else {
            $message = '�� ������� ��������� �������� ������ ����� ������������ � ������. ��������, login ��� ������ ������� �������. ���������� ��������� ������ �����.';
        }
        $q->close();
    }
    $login_lastaccess = time();
} else {
    $message = "�� ������� ��� ����������� ���������. ���������� ��������� ������ �����.";
} // ����� ������� �����

data('message', $message);
authorize($data);
?>
