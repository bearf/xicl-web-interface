<?php
require_once('./config/require.php');

// todo: check usecase when only one contest and it's not started
// todo: check updating attempts after successful submit
// todo: check auto-completing the fields after login was performed

//проверяем параметр contest
if (!isset($contest)) { $contest = $curcontest; }

//проверяем, существует ли запрашиваемый турнир
$q = $mysqli_->prepare('SELECT ContestID, Name, Contest_Kind FROM cntest WHERE ContestID=? and start<NOW() and finish>NOW()');
$q->bind_param('i', $contest);
$q->bind_result($requested_id, $requested_contest_name, $kind);
if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
if (!$q->fetch()) { fail(_error_requested_contest_is_not_available_code); } // auto-close query
$q->close();
// конец загрузки информации о турнире

// нажата кнопка submit
if (isset($btn_submit) && (!isset($changeLang) || 'true' !== $changeLang)) {
    // зашыта от спама
    if (time() - $submit_lastaccess < antiSpamTimeOut) {
        $msg = 'В целях защиты от спама не разрешается делать попытки с интервалом между ними менее '.antiSpamTimeOut.' секунд. Попробуйте повторить запрос позже.';
    } else {
        $submit_lastaccess = time();
    } // конец зашыты от спама

    // проверяем required-параметры
    if (!isset($msg)) {
        // contest is always set
        if (!isset($problem) || !$problem) { $msg = 'Не заданы все необходимые параметры.'; }
        if (!isset($lang) || !$lang) { $msg = 'Не заданы все необходимые параметры.'; }
        if (!isset($login) || !$login) { $msg = 'Не заданы все необходимые параметры.'; }
        if (!isset($pass) || !$pass) { $msg = 'Не заданы все необходимые параметры.'; }
    } // конец проверки на required-параметры

    // пытаемся достать инфу по задаче
    if (!isset($msg)) {
        $q = $mysqli_->prepare('SELECT TaskID, Division FROM volume WHERE ProblemID=? AND ContestID=?');
        $q->bind_param('si', $problem, $contest);
        $q->bind_result($_, $division);
        if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
        if (!$q->fetch()) { fail(_error_no_task_found_code); } // auto-close query
        $q->close();
    } // конец инфы по задаче

    // пытаемся достать инфу о языке
    // todo: foreign keys
    if (!isset($msg)) {
        $q = $mysqli_->prepare('SELECT COUNT(*) FROM Lang WHERE LangID=?');
        $q->bind_param('i', $lang);
        $q->bind_result($tmp_lang);
        if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
        if (!$q->fetch()) { fail(_error_no_lang_found_code); } // auto-close query
        $q->close();
    } // конец добычи инфы о языке

    // добываем информацию о пользователе
    if (!isset($msg)) {
        $q = $mysqli_->prepare('SELECT id, division FROM `user` WHERE login=? AND `password`=password(?)');
        $q->bind_param('ss', $login, $pass);
        $q->bind_result($user, $userDivision);
        if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
        if (!$q->fetch()) { fail(_error_no_user_found_code); } // auto-close query
        $q->close();
    } // конец добычи информации о пользователе

    if (!isset($msg) && $userDivision != '' && $division != '' && $userDivision != $division) {
        fail(_error_submit_cannot_perform_on_other_division);
    }

    // проверка на размер файла
    if (!isset($msg) && strlen($solve) > 65536) {
        $msg = 'Размер исходного файла превышает 64KB.';
    }

    // пытаемся послать
    if (!isset($msg)) {
        // пытаемся достать номер попытки
        $q = $mysqli_->prepare('SELECT MAX(Attempt) AS `Attempt` FROM submit WHERE UserID=? AND ProblemID=? AND ContestID=?');
        $q->bind_param('isi', $user, $problem, $contest);
        $q->bind_result($attempt);
        if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
        $attempt = $q->fetch() ? $attempt + 1 : 1;
        $q->close();

        $forward = getenv('HTTP_X_FROWARDED_FROM') ? getenv('HTTP_X_FROWARDED_FROM') : 'no HTTP_X_FROWARDED_FROM';
        $ip = getenv('REMOTE_ADDR');

        $q = $mysqli_->prepare('INSERT INTO'
            .' submit(ProblemID, ContestID, UserID, Attempt, SubmitTime, LangID, Source, Forwarded, IP)'
            .' VALUES(?, ?, ?, ?, NOW(), ?, ?, ?, ?)');
        // todo: check if s for source?
        $solve = get_magic_quotes_gpc() ? stripslashes($solve) : $solve;
        $q->bind_param('siiiisss', $problem, $contest, $user, $attempt, $lang, 
            $solve, 
            $forward, $ip);
        if ($q->execute() && 1 == $q->affected_rows) {
            $q->close();

            $submitId = $mysqli_->insert_id;
            $q = $mysqli_->prepare('INSERT INTO'
                .' testing(submitId, scheduled)'
                .' VALUES(?, NOW())');
            $q->bind_param('i', $submitId);
            if ($q->execute() && 1 == $q->affected_rows) {
                $q->close();
                $msg = 'Файл успешно отправлен на проверку! Выберите пункт меню <a href="status.php?contest='.$contest.'">статус посылок</a> для просмотра результатов.';
            } else {
                $msg = 'Посылка решения не удалась. Попробуйте еще раз.';
                $q->close();
            }
        } else {
            $msg = 'Посылка решения не удалась. Попробуйте еще раз.';
            $q->close();
        } // end of checking for submit
    } // end of checking of senderror
} else if (isset($authorized) && $authorized == 1) {
    $login = $curlogin;
    $pass = $curpass;
}

data('msg', isset($msg) ? $msg : '');
data('login', isset($login) ? $login : '');
data('pass', isset($pass) ? $pass : '');
data('lang', isset($lang) ? $lang : '');
data('contest', isset($contest) ? $contest : '');
data('problem', isset($problem) ? $problem : '');
data('solve', isset($solve) ? $solve : '');

// инфа о доступных контестах
$r = $mysqli_->query('SELECT ContestID, Name FROM cntest WHERE NOW()>=Start AND NOW()<=Finish order by ContestID');
if (!$r) { fail(_error_mysql_query_error_code); } // auto-close query
$contests = array();
while ($f = $r->fetch_object()) { array_push($contests, $f); }
$r->close();
data('contests', $contests);
// конец запроса инфы о доступных контестах

// запрос инфы о доступных языках
$r = $mysqli_->query('SELECT L.LangID, L.Desc, L.Ext FROM lang L where L.langID=1 or L.langID>6 order by LangID');
//$r = $mysqli_->query('SELECT L.LangID, L.Desc, L.Ext FROM lang L order by LangID');
if (!$r) { fail(_error_mysql_query_error_code); } // auto-close query
$langs = array();
while ($f = $r->fetch_object()) { array_push($langs, $f); }
$r->close();
data('langs', $langs);
// конец запроса инфы о доступных языках

template('submit', $data);
?>

