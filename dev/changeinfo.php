<?php
require_once dirname(__FILE__) . '/config/require.php';

// ������ �������������� ������������ ����� ������ ��� =)
if (1 != $authorized) { authorize(); }
//���� �������� �������� ����������
//� �� �� � ��������� ������ - ������ ��������������� �� �������� � ����������
if (!_permission_allow_change_info && $is_admin != 1) { authorize(); }

// �������� �� ����
if (isset($changebtn)) {
    if (time() - $changeinfo_lastaccess < antiSpamTimeOut) {
        $message = '� ����� ������ �� ����� �� ����������� ������ ������� ����������� ������ � ���������� ����� ���� ����� '.antiSpamTimeOut.' ������. ���������� ��������� ������ �����.';
    } else {
        $changeinfo_lastaccess = time();
    }
} // ����� �������� �� ����

// �������� ���������� ������
// todo: ���� ��������
if (isset($changebtn) && !isset($message)) {
    if ((isset($changepassword) && (!$passrep || !$newpass)) || !$nickname /*|| !$name*/) { $message = '�� ��������� ���� ��� ��������� �� ������������ ����� �����. ������� ����������� ������'; }
    elseif (isset($changepassword) && $newpass!=$passrep) { $message =  '������ ������ ������ �����������.'; }
    elseif (isset($newpass) && !PassCorrect($newpass)) { $message =  '������� ������ ������. ����� ������ �� ������ ��������� 20 ��������. '; }
    elseif (!NicknameCorrect($nickname)) { $message =  '������� ������ nick. ����� ������ �� ������ ��������� 30 ��������. '; }
    elseif (!StudyCorrect($studyplace)) { $message =  '������� ������� �������� ����� ��������. ����� ������ �� ������ ��������� 50 ��������. '; }
    elseif (!EmailCorrect($email)) { $message =  '������� ������ E-mail. ����� ������ �� ������ ��������� 40 ��������. '; }
    elseif (!InfoCorrect($info)) { $message =  '������� ������� �������������� ����������. ����� ������ �� ������ ��������� 254 �������. '; }
    elseif (isset($passrep) && !CheckSym($passrep) ||
        isset($newpass) && !CheckSym($newpass) ||
        !CheckSym($nickname) ||
        !CheckSym($studyplace) ||
        !CheckSym($email) ||
        !CheckSym($info)) {
            $message =  '���� �� ����� ����� �������� ������������ �������.';
    }
} //����� �������� ���������� ������

// ��������� ������
if (isset($changebtn) && !isset($message)) {
    // �������� �� ������� �������
    if (!isset($changepassword) || !$changepassword) {
        $newpass = $curpass;
        $passrep = $curpass;
    }
} // ����� ��������� ������

// �������� �� �������� ��������� ������ // todo: �� ��������
if (isset($changebtn) && !isset($message)) {
    $q = $mysqli_->prepare('select id'
        .' from user'
        .' where `password`=password(?) and nickname=? and studyplace=? and class=? and email=? and allowpublish=? and info=? and id=?');
    $q->bind_param('sssssisi', $newpass, $nickname, $studyplace, $clss, $email, checkbox('allowpublish'), $info, $curuserid);
    if (!$q->execute()) { fail(_error_mysql_query_error_code); }
    if ($q->fetch()) { $message = '�� ���� �� ����� �� ���� ��������'; }
    $q->close();
} // ����� �������� �� ��������� ������

// ������� �������� ������
if (isset($changebtn) && !isset($message)) {
    $q = $mysqli_->prepare('UPDATE `user`'
        .' SET `password`=password(?), nickname=?, studyplace=?, class=?, email=?, allowpublish=?, info=?'
        .' WHERE id=?');
    $q->bind_param('sssssisi', $newpass, $nickname, $studyplace, $clss, $email, checkbox('allowpublish'), stripslashes($info), $curuserid);
    if (!$q->execute() || 0 == $q->affected_rows) {
        $message = '��������� ������ �� �������. ��������, ������������ � ����� nickname\'�� ��� ����������.';
    } else {
        $message = '��������� ������ ����������� �������.';
    }
    $q->close();
} //����� ������� �������� ������

data('allowpublish', checkbox('allowpublish'));
data('changepassword', checkbox('changepassword'));

if (isset($changebtn)) {
    if (isset($message)) { data('message', $message); }
    data('nickname', $nickname);
    data('studyplace', $studyplace);
    data('clss', $clss);
    data('email', $email);
    data('info', $info);
}

if (!checkCreatePersonalInfo($curuserid)) {
    data('persInfo', selectPersonalInfo($curuserid));
}

// ����� �� �������� - ��������� ������ �� ������
if (!isset($changebtn)) {
    getUserInfo($curuserid);
} // ����� ���������� ������ �� ������

template('changeinfo', $data);
?>
