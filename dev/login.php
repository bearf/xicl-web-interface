<?php
require_once('./config/require.php');

if (isset($firstattempt)) { authorize(); }

// попытка войти
if (isset($login) && isset($password)) {
    if (time() - $login_lastaccess < antiSpamTimeOut) {
        $header = 'Не удалось войти в систему';
        $message = 'В целях защиты от спама не разрешается делать попытки входа в систему с интервалом между ними менее '.antiSpamTimeOut.' секунд. Попробуйте повторить запрос позже.';
    } else {
        $q = $mysqli_->prepare('SELECT id,nickname,is_admin FROM `user` WHERE login=? AND `password`=password(?)');
        $q->bind_param('ss', $login, $password);
        $q->bind_result($curuserid, $curnickname, $is_admin);
        if ($q->execute() && $q->fetch()) {
            $authorized = 1;
            $curlogin = $login;
            $curpass = $password;
            $message = 'Вход в систему произведен успешно';
        } else {
            $message = 'Не удалось выполнить операцию сверки имени пользователя и пароля. Возможно, login или пароль введены неверно. Попробуйте повторить запрос позже.';
        }
        $q->close();
    }
    $login_lastaccess = time();
} else {
    $message = "Не указаны все необходимые параметры. Попробуйте повторить запрос позже.";
} // конец попытки войти

data('message', $message);
authorize($data);
?>
