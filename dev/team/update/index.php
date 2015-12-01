<?php

require_once dirname(__FILE__) . '/../../config/require.php';

// только авторизованные пользователи могут делать это =)
if (1 != $authorized) { authorize(); }

if (-1 == $curteamid) {
    fail(_error_cannot_update_team_you_have_no_team);
}

if (isset($_POST['btnTeamUpdate'])) {
    $mapping = $_POST;
    $members = array('contestanta', 'contestantb', 'contestantc', 'head', 'coach');
    foreach ($members as $_ => $member) {
        if (isset($mapping['team'.$member.'memberuserid']) && !$mapping['team'.$member.'memberuserid']) {
            $mapping['team'.$member.'membername'] = $mapping['team'.$member.'memberuseridEdit'];
        } elseif (isset($mapping['team'.$member.'memberuserid'])) {
            $mapping['team'.$member.'memberusernickname'] = $mapping['team'.$member.'memberuseridEdit'];
        }
    }

    if (!updateTeam($mapping)) {
        data('message', $messages[_error_team_has_not_been_updated]);
        data('team', Butler::getORMManager()->createTeam()->wrap($mapping)->safe()->unsafe());
    } else {
        data('message', $messages[_success_team_has_been_updated]);
        data('team', getTeam($curteamid));
    }
} else {
    data('team', getTeam($curteamid));
}

template('teamupdate', $data);

