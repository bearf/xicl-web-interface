<?php
require_once dirname(__FILE__) . '/config/require.php';

// только авторизованные пользователи могут делать это =)
if (1 != $authorized) { authorize(); }
//если запрещен просмотр информации
//и мы не в админском режиме - делаем перенаправление на страницу с сообщением
if (!_permission_allow_change_info && $is_admin != 1) { authorize(); }

// проверка на спам
if (isset($changebtn)) {
    if (time() - $changeinfo_lastaccess < antiSpamTimeOut) {
        $message = 'В целях защиты от спама не разрешается делать попытки отправления данных с интервалом между ними менее '.antiSpamTimeOut.' секунд. Попробуйте повторить запрос позже.';
    } else {
        $changeinfo_lastaccess = time();
    }
} // конец проверки на спам

// проверка присланных данных
// todo: дата рождения
if (isset($changebtn) && !isset($message)) {
    if ((isset($changepassword) && (!$passrep || !$newpass)) || !$nickname /*|| !$name*/) { $message = 'Не заполнено одно или несколько из обязательных полей ввода. Введите необходимые данные'; }
    elseif (isset($changepassword) && $newpass!=$passrep) { $message =  'Повтор нового пароля некорректен.'; }
    elseif (isset($newpass) && !PassCorrect($newpass)) { $message =  'Неверно введен пароль. Длина строки не должна превышать 20 символов. '; }
    elseif (!NicknameCorrect($nickname)) { $message =  'Неверно введен nick. Длина строки не должна превышать 30 символов. '; }
    elseif (!StudyCorrect($studyplace)) { $message =  'Неверно введено название места обучения. Длина строки не должна превышать 50 символов. '; }
    elseif (!EmailCorrect($email)) { $message =  'Неверно введен E-mail. Длина строки не должна превышать 40 символов. '; }
    elseif (!InfoCorrect($info)) { $message =  'Неверно введена дополнительная информация. Длина строки не должна превышать 254 символа. '; }
    elseif (isset($passrep) && !CheckSym($passrep) ||
        isset($newpass) && !CheckSym($newpass) ||
        !CheckSym($nickname) ||
        !CheckSym($studyplace) ||
        !CheckSym($email) ||
        !CheckSym($info)) {
            $message =  'Одно из полей ввода содержит недопустимые символы.';
    }
} //конец проверки присланных данных

// коррекция данных
if (isset($changebtn) && !isset($message)) {
    // проверка на наличие паролей
    if (!isset($changepassword) || !$changepassword) {
        $newpass = $curpass;
        $passrep = $curpass;
    }
} // конец коррекции данных

// проверка на реальное изменение данных // todo: не работает
if (isset($changebtn) && !isset($message)) {
    $q = $mysqli_->prepare('select id'
        .' from user'
        .' where `password`=password(?) and nickname=? and studyplace=? and class=? and email=? and allowpublish=? and info=? and id=?');
    $q->bind_param('sssssisi', $newpass, $nickname, $studyplace, $clss, $email, checkbox('allowpublish'), $info, $curuserid);
    if (!$q->execute()) { fail(_error_mysql_query_error_code); }
    if ($q->fetch()) { $message = 'Ни одно из полей не было изменено'; }
    $q->close();
} // конец проверки на изменение данных

// попытка изменить данные
if (isset($changebtn) && !isset($message)) {
    $q = $mysqli_->prepare('UPDATE `user`'
        .' SET `password`=password(?), nickname=?, studyplace=?, class=?, email=?, allowpublish=?, info=?'
        .' WHERE id=?');
    $q->bind_param('sssssisi', $newpass, $nickname, $studyplace, $clss, $email, checkbox('allowpublish'), stripslashes($info), $curuserid);
    if (!$q->execute() || 0 == $q->affected_rows) {
        $message = 'Изменение данных не удалось. Возможно, пользователь с таким nickname\'ом уже существует.';
    } else {
        $message = 'Изменение данных произведено успешно.';
    }
    $q->close();
} //конец попытки изменить данные

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

// форма не прислана - считываем данные из сессии
if (!isset($changebtn)) {
    getUserInfo($curuserid);
} // конец считывания данных из сессии

template('changeinfo', $data);
?>
