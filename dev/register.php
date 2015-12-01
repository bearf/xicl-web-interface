<?php
require_once('./config/require.php');

//если запрещен просмотр информации
//и мы не в админском режиме - делаем перенаправление на страницу с сообщением
if (!_permission_allow_register_new_user && $is_admin != 1) { authorize(); }

// проверка на спам
if (isset($regbtn)) {
    if (time() - $register_lastaccess < antiSpamTimeOut) {
        $message = '¬ цел€х защиты от спама не разрешаетс€ делать попытки отправлени€ данных с интервалом между ними менее '.antiSpamTimeOut.' секунд. ѕопробуйте повторить запрос позже.';
    } else {
        $register_lastaccess = time();
    }
} // конец проверки на спам

// проверка присланных данных
// todo: дата рождени€
if (isset($regbtn) && !isset($message)) {
    if (!$passrep || !$newpass || !$login || !$nickname /*|| !$name*/) { $message = 'Ќе заполнено одно или несколько из об€зательных полей ввода. ¬ведите необходимые данные'; }
    elseif ($newpass!=$passrep) { $message =  'ѕовтор парол€ некорректен.'; }
    elseif (!LoginCorrect($login)) { $message =  'Ќеверно введен login. ƒлина строки не должна превышать 50 символов. '; }
    elseif (!PassCorrect($newpass)) { $message =  'Ќеверно введен пароль. ƒлина строки не должна превышать 20 символов. '; }
    elseif (!NicknameCorrect($nickname)) { $message =  'Ќеверно введен nick. ƒлина строки не должна превышать 50 символов. '; }
    elseif (!CheckSym($passrep) ||
        !CheckSym($newpass) ||
        !CheckSym($login) ||
        !CheckSym($nickname)) {
            $message =  'ќдно из полей ввода содержит недопустимые символы.';
    }
} //конец проверки присланных данных

// коррекци€ данных и попытка создани€ пользовател€
if (isset($regbtn) && !isset($message)) {
    //попытка зарегистрировать данные
    $q = $mysqli_->prepare('INSERT INTO `user`(login, `password`, nickname, regdate, teamid) VALUES(?, password(?), ?, NOW(), -1)');
    $q->bind_param('sss', $login, $newpass, $nickname);
    $success = $q->execute() || 0 == $q->affected_rows;
    $userId = $mysqli_->insert_id;
    $q->close();
    if (!$success) {
        $message = '–егистраци€ не удалась. ¬озможно, пользователь с таким nickname\'ом уже существует.';
    } else {
        // создаем одноименную команду
        // сначала пытаемс€ вытащить команду с таким же name как nickname пользовател€
        $q = $mysqli_->prepare('SELECT teamname from Teams where teamname = ?');
        $q->bind_param('s', $nickname);
        if ($q->execute()) {
            $fetched = $q->fetch();
            $q->close();
            // запрос на добавление команды
            $q = $mysqli_->prepare('INSERT INTO teams(teamname, education, city) VALUES(?, \'\', \'\')');
            $teamname = $nickname.($fetched ? time() : '');
            $q->bind_param('s', $teamname);
            $q->execute();
            $teamId = $mysqli_->insert_id;
            $q->close();
            // запрос на добавление пользовател€
            $q = $mysqli_->prepare('INSERT INTO members(userid, teamid) VALUES(?, ?)');
            $q->bind_param('ii', $userId, $teamId);
            $q->execute();
            $q->close();
        } else {
            $q->close();
        }
    }
    //конец попытки изменить данные
} // конец попытки создани€ пользовател€

// успешна€ регистраци€
if (isset($regbtn) && !isset($message)) {
    data('message', '–егистраци€ прошла успешно');
    template('message', $data);
} else { // прочие случаи
    if (isset($message)) { data('message', $message); }
    data('login', isset($login) ? $login : '');
    data('nickname', isset($nickname) ? $nickname : '');
    data('teamname', isset($teamname) ? $teamname : '');
    template('register', $data);
}
?>
