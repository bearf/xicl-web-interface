<?php
require_once('./config/require.php');

if (1 != $authorized) { $authorize(); }
if (!isset($notifyid)) { fail(_error_no_notify_id_code); }

$q = $mysqli_->prepare('select count(*) from `messages` M where M.kind=2 and M.messageid=? and (M.touser=? and M.touser<>-1 or M.toteam=? and M.toteam<>-1 or M.touser=-1 and M.toteam=-1) and M.date > (select U.regdate from `user` U where U.id=?)');
$q->bind_param('iiii', $notifyid, $curuserid, $curteamid, $curuserid);
$q->bind_result($count);
if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
$q->fetch();
$q->close();
if (0 == $count) { fail(_error_no_notify_found_code); }
$q = $mysqli_->prepare('select count(*) from `reads` R where R.messageid=? and R.userid=?');
$q->bind_param('ii', $notifyid, $curuserid);
$q->bind_result($read);
if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
$q->fetch();
$q->close();

if (0 == $read):
    $q = $mysqli_->prepare('insert into `reads`(date, messageid, userid) values(now(), ?, ?)');
    $q->bind_param('ii', $notifyid, $curuserid);
    if (!$q->execute() || 1 != $q->affected_rows) { fail(_error_mysql_query_error_code); } // auto-close query
    $q->close();
endif;

redirect(ServerRoot.'notifylist.php'.(isset($page) ? '?page='.$page : ''));
?>
