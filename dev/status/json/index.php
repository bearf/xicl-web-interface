<?php

require_once dirname(__FILE__) . '/../../config/require.php';
// ?????? ?????? ????? ????????????? ???????? ???????

Header('Content-Type: application/json; charset=cp1251');
Header('Content-disposition: attachment;filename=status.json');

$contest = isset($_GET['contest']) ? $_GET['contest'] : $curcontest;

$status = getStatus(
	$contest,
	isset($_GET['since']) ? $_GET['since'] : NULL
);
$problems = getProblems($contest);

$list = array(); $i = 0;
foreach($status as $_ => $status) {
	if (isset($_GET['since']) && $_GET['since'] > 0 && ++$i === 10) {
		break;
	}
	if (!$is_admin && $status->getTime() > 4*3600) {
		break;
	}
	$item = array();
	$item[] = '"submitId":' . $status->getSubmitId();
	$item[] = '"userId":' . $status->getUserId();
	$item[] = '"taskId":' . $status->getTaskId();
	$item[] = '"time":' . $status->getTime();
	$item[] = '"timestamp":' . $status->getTimestamp();
	$item[] = '"result":"' . ('OK' === $status->getResult() ? 'AC' : $status->getResult()) . '"';
    $item[] = '"task":"' . $status->getTask() . '"';
    $item[] = '"nickname":"' . $status->getNickname() . '"';
    $s = str_replace( "\n", '', $status->getInfo() );
    $s = str_replace( "\r", '', $s );
    $item[] = '"info":"' . $s . '"';
    $item[] = '"studyplace":"' . str_replace( "\"", "\\\"", $status->getStudyplace()) . '"';
    $item[] = '"city":"' . $status->getCity() . '"';
    $item[] = '"division":"' . $status->getDivision() . '"';
    $item[] = '"tatarstan":"' . $status->isTatarstan() . '"';

	$list[] = '{' . implode($item, ',') . '}';
}

$problemList = array();
foreach($problems as $_ => $problem) {
	$problemList[] = '{"letter":"' . $problem->getLetter() . '", "publishedAfter":' . $problem->getPublishedAfter() . '}';
}

$query = 'select TIMESTAMPDIFF(SECOND, start, now()) as `gone`, TIMESTAMPDIFF(SECOND, now(), finish) as `left`, TIMESTAMPDIFF(SECOND, start, finish) as `duration` from cntest where contestid='.$contest;
$contestrec = $mysqli_->query($query);
if (0 < $contestrec->num_rows) {
    $contestf = $contestrec->fetch_object();
}

if (isset($_GET['callback'])) echo $_GET['callback'] . '(';
echo '{';
echo '"tasks":[' . implode($problemList, ',') . '],';
echo '"submits":[' . implode($list, ',') . '],';
echo '"left":' . $contestf->left . ',';
echo '"gone":' . $contestf->gone . ',';
echo '"duration":' . $contestf->duration;
echo '}';
if (isset($_GET['callback'])) echo ')';
