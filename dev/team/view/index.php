<?php

require_once dirname(__FILE__) . '/../../config/require.php';

if (!_permission_allow_view_team_info && 1 != $is_admin) { authorize(); }

data('team', getTeam($_GET['teamid']));

template('teamview', $data);

