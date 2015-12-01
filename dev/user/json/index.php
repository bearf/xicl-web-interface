<?php

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once dirname(__FILE__) . '/../../config/require.php';
// ?????? ?????? ????? ????????????? ???????? ???????
// fixme: must be for admins only

$users = getUsers();

$list = array(); 
foreach($users as $_ => $user) {
	$item = array();
	$item[] = '"userId":' . $user->getId();
    $item[] = '"nickname":"' . $user->getNickname() . '"';
    $s = str_replace( "\n", '', $user->getInfo() );
    $s = str_replace( "\r", '', $s );
    $item[] = '"info":"' . $s . '"';
    $item[] = '"studyplace":"' . str_replace( "\"", "\\\"", $user->getStudyplace()) . '"';
    $item[] = '"city":"' . $user->getCity() . '"';
    $item[] = '"division":"' . $user->getDivision() . '"';
    $item[] = '"tatarstan":"' . $user->isTatarstan() . '"';

	$list[] = '{' . implode($item, ',') . '}';
}

Header('Content-Type: application/json; charset=cp1251');

if (isset($_GET['callback'])) echo $_GET['callback'] . '(';
echo '[';
echo implode($list, ',');
echo ']';
if (isset($_GET['callback'])) echo ')';
