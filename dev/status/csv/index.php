<?php

require_once dirname(__FILE__) . '/../../config/require.php';

// ?????? ?????? ????? ????????????? ?????? ???????????? ???????
if (1 != $is_admin) { authorize(); }

Header('Content-Type: text/csv; charset=cp1251');
Header('Content-disposition: attachment;filename=status.csv');

$status = getStatus(isset($_GET['contest']) ? $_GET['contest'] : $curcontest);

/**
 * @param  $status Status
 * @return array
 */
function getStatusInfo($status) {
    $result = array();
    $result[] = $status->getSubmitId();
    $result[] = $status->getUserId();
    $result[] = $status->getTaskId();
    $result[] = $status->getLangId();
    $result[] = $status->getTime();
    $result[] = $status->getResult();
    $result[] = $status->getTask();
    $result[] = $status->getNickname();
    $result[] = $status->getCity();
    $result[] = $status->getStudyplace();
    $result[] = $status->getInfo();
    $result[] = $status->getDivision();
    return $result;
}


$fp = fopen('php://output', 'w');
foreach($status as $_ => $status) {
    fputcsv($fp, getStatusInfo($status), ';');
}
fclose($fp);
