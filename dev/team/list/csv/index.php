<?php

require_once dirname(__FILE__) . '/../../../config/require.php';

// только админы могут просматривать список приглашенных команды
if (1 != $is_admin) { authorize(); }

Header('Content-Type: text/csv; charset=cp1251');
Header('Content-disposition: attachment;filename=teams.csv');

$teams = getInvitedTeams();

/**
 * @param  $team Team
 * @param  $role string
 * @return string
 */
function getBase($team, $role) {
    return implode(';', array(
         $team->getName(),
         $team->getCity(),
         $team->getEducation(),
         $role
    ));
}

/**
 * @param  $team Team
 * @param  $role string
 * @param  $info PersonalInfo
 * @return string
 */
function outInfo($team, $role, $info) {
    return implode(';', array(
        getBase($team, $role),

        implode(' ', array($info->getSurname(), $info->getName(), $info->getPatrName())),
        $info->getBirthDate(),

        $info->getPassportNo(),
        $info->getPassportDate(),
        $info->getPassportIssue(),
        $info->getPTPN(),

        $info->getPhone(),

        $info->getRegion(),
        $info->getPostIndex(),
        $info->getCity(),
        $info->getAddress()
    )).'';
}

/**
 * @param  $team Team
 * @param  $role string
 * @param  $user User
 * @return string string
 */
function outUser($team, $role, $user) {
    return implode(';', array(
        getBase($team, $role),
        $user->getNickName(),
        implode(';', array('', '' , '', '' , '', '', '', '', '', ''))
    )).'';
}

function outName($team, $role, $name) {
    return implode(';', array(
        getBase($team, $role),
        $name,
        implode(';', array('', '' , '', '' , '', '', '', '', '', ''))
    )).'';
}

/**
 * @param  $team Team
 * @param  $role string
 * @param  $member Member
 * @return string
 */
function outTeamMember($team, $role, $member) {
    if (!$member->getUser()->isEmpty()) {
        if (!$member->getUser()->getPersonalInfo()->isEmpty()) {
            return outInfo($team, $role, $member->getUser()->getPersonalInfo());
        } else {
            return outUser($team, $role, $member->getUser());
        }
    } elseif ($member->getName()) {
        return outName($team, $role, $member->getName());
    }
}

/**
 * @param  $team Team
 * @return string
 */
function outTeam($team) {
    $members = array();
    if (!$team->getContestantA()->isEmpty()) { $members[] = outTeamMember($team, 'Участник 1', $team->getContestantA()); }
    if (!$team->getContestantB()->isEmpty()) { $members[] = outTeamMember($team, 'Участник 2', $team->getContestantB()); }
    if (!$team->getContestantC()->isEmpty()) { $members[] = outTeamMember($team, 'Участник 3', $team->getContestantC()); }
    if (!$team->getCoach()->isEmpty()) { $members[] = outTeamMember($team, 'Тренер', $team->getCoach()); }
    if (!$team->getHead()->isEmpty()) { $members[] = outTeamMember($team, 'Руководитель', $team->getHead()); }
    return implode('\n', $members);
}

$data = array();
$data[] = implode(';', array(
    'Команда', 'Город', 'Место учебы',
    'Роль', 'ФИО', 'Дата рождения',
    'Серия и № паспорта', 'Когда выдан паспорт', 'Кем выдан паспорт', 'ИНН',
    'Контактный телефон',
    'Регион', 'Почтовый индекс', 'Город', 'Адрес',
));
foreach ($teams as $_ => $team) {
    $data[] = outTeam($team);
}

$data = implode('\n', $data);
$data = explode('\n', $data);

$fp = fopen('php://output', 'w');
foreach ($data as $_ => $member) {
    $fields = explode(';', $member);
    fputcsv($fp, $fields, ';');
}
fclose($fp);

