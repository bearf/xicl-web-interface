<?php

require_once dirname(__FILE__) . '/../../../config/require.php';

Header('Content-Type: text/html; charset=cp1251');

// только авторизованные пользователи могут делать это =)
if (1 != $authorized) { authorize(); }

$users = queryUsers(isset($_GET['nickname']) ? iconv("utf-8", "cp1251", $_GET['nickname']) : '');

$json = array();
foreach($users as $_ => $user) {
    $json[] = implode(' ', array('{',
        implode(',', array(
            sprintf('"label":"%s"', $user->getNickName()),
            sprintf('"data":{"value":%d}', $user->getId())
        )),
    '}'));
}

echo sprintf("[%s]", implode(',', $json));



