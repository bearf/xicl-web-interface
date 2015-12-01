<?php
require_once('./config/require.php');

$is_admin = 0;
$authorized = 0;
$curlogin = '';
$curuserid = -1;
$curpass = '';
$curnickname = '';

data('message', '¬ыход из системы произведен успешно');
authorize('message');
?>

