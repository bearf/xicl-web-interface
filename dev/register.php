<?php
require_once('./config/require.php');

//���� �������� �������� ����������
//� �� �� � ��������� ������ - ������ ��������������� �� �������� � ����������
if (!_permission_allow_register_new_user && $is_admin != 1) { authorize(); }

// �������� �� ����
if (isset($regbtn)) {
    if (time() - $register_lastaccess < antiSpamTimeOut) {
        $message = '� ����� ������ �� ����� �� ����������� ������ ������� ����������� ������ � ���������� ����� ���� ����� '.antiSpamTimeOut.' ������. ���������� ��������� ������ �����.';
    } else {
        $register_lastaccess = time();
    }
} // ����� �������� �� ����

// �������� ���������� ������
// todo: ���� ��������
if (isset($regbtn) && !isset($message)) {
    if (!$passrep || !$newpass || !$login || !$nickname /*|| !$name*/) { $message = '�� ��������� ���� ��� ��������� �� ������������ ����� �����. ������� ����������� ������'; }
    elseif ($newpass!=$passrep) { $message =  '������ ������ �����������.'; }
    elseif (!LoginCorrect($login)) { $message =  '������� ������ login. ����� ������ �� ������ ��������� 50 ��������. '; }
    elseif (!PassCorrect($newpass)) { $message =  '������� ������ ������. ����� ������ �� ������ ��������� 20 ��������. '; }
    elseif (!NicknameCorrect($nickname)) { $message =  '������� ������ nick. ����� ������ �� ������ ��������� 50 ��������. '; }
    elseif (!CheckSym($passrep) ||
        !CheckSym($newpass) ||
        !CheckSym($login) ||
        !CheckSym($nickname)) {
            $message =  '���� �� ����� ����� �������� ������������ �������.';
    }
} //����� �������� ���������� ������

// ��������� ������ � ������� �������� ������������
if (isset($regbtn) && !isset($message)) {
    //������� ���������������� ������
    $q = $mysqli_->prepare('INSERT INTO `user`(login, `password`, nickname, regdate, teamid) VALUES(?, password(?), ?, NOW(), -1)');
    $q->bind_param('sss', $login, $newpass, $nickname);
    $success = $q->execute() || 0 == $q->affected_rows;
    $userId = $mysqli_->insert_id;
    $q->close();
    if (!$success) {
        $message = '����������� �� �������. ��������, ������������ � ����� nickname\'�� ��� ����������.';
    } else {
        // ������� ����������� �������
        // ������� �������� �������� ������� � ����� �� name ��� nickname ������������
        $q = $mysqli_->prepare('SELECT teamname from Teams where teamname = ?');
        $q->bind_param('s', $nickname);
        if ($q->execute()) {
            $fetched = $q->fetch();
            $q->close();
            // ������ �� ���������� �������
            $q = $mysqli_->prepare('INSERT INTO teams(teamname, education, city) VALUES(?, \'\', \'\')');
            $teamname = $nickname.($fetched ? time() : '');
            $q->bind_param('s', $teamname);
            $q->execute();
            $teamId = $mysqli_->insert_id;
            $q->close();
            // ������ �� ���������� ������������
            $q = $mysqli_->prepare('INSERT INTO members(userid, teamid) VALUES(?, ?)');
            $q->bind_param('ii', $userId, $teamId);
            $q->execute();
            $q->close();
        } else {
            $q->close();
        }
    }
    //����� ������� �������� ������
} // ����� ������� �������� ������������

// �������� �����������
if (isset($regbtn) && !isset($message)) {
    data('message', '����������� ������ �������');
    template('message', $data);
} else { // ������ ������
    if (isset($message)) { data('message', $message); }
    data('login', isset($login) ? $login : '');
    data('nickname', isset($nickname) ? $nickname : '');
    data('teamname', isset($teamname) ? $teamname : '');
    template('register', $data);
}
?>
