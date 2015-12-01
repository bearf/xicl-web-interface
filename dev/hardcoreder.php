<?php
require_once('./config/require.php');

// todo: check usecase when only one contest and it's not started
// todo: check updating attempts after successful submit
// todo: check auto-completing the fields after login was performed

if (!$authorized || 1 != $is_admin) { authorize(); }

// запрос инфы о доступных пользователях
//$r = $mysqli_->query('SELECT L.LangID, L.Desc, L.Ext FROM lang L where L.langID=1 or L.langID>6 order by LangID');
$r = $mysqli_->query('SELECT U.ID, U.NickName FROM User U order by U.ID');
if (!$r) { fail(_error_mysql_query_error_code); } // auto-close query
$users = array();
while ($f = $r->fetch_object()) { array_push($users, $f); }
$r->close();
data('users', $users);
// конец запроса инфы о доступных пользователях

template('hardcoreder', $data);
?>

