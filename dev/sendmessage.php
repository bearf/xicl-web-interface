<?php
require_once('./config/require.php');

if (1 != $authorized) { die('1'); }
if (!_permission_allow_mail && 1 != $is_admin) { die('1.1'); }
if (!isset($touser)) { die('2'); }
if (!isset($text)) { die('3'); }

$q = $mysqli_->prepare('insert into `messages`(touser, `message`, `header`, `from`, `date`) values(?, ?, \'\', ?, now())');
$q->bind_param('isi', $touser, stripslashes(iconv("utf-8", "cp1251", $text)), $curuserid);
if (!$q->execute()) { die('4'); }
if (0 == $q->affected_rows) { die('5'); }
$q->close();

?><result>true</result>
