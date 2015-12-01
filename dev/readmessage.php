<?php
require_once('./config/require.php');

if (1 != $authorized) { die('1'); }
if (!_permission_allow_mail && 1 != $is_admin) { die('1.1'); }
if (!isset($messageid)) { die('2'); }

$q = $mysqli_->prepare('select count(*) from `reads` where userid=? and messageid=?');
$q->bind_param('ii', $curuserid, $messageid);
$q->bind_result($tmp);
if (!$q->execute()) { die('3'); }
$q->fetch();
$q->close();
if (0 == $tmp) {
    $q = $mysqli_->prepare('insert into `reads`(date, userid, messageid) values(now(), ?, ?)');
    $q->bind_param('ii', $curuserid, $messageid);
    if (!$q->execute()) { die('4'); }
    if (0 == $q->affected_rows) { die('5'); }
    $q->close();
} else {
    die('6');
}
?><result>true</result>
