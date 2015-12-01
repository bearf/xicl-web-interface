<?php

require_once dirname(__FILE__) . '/../../config/require.php';

// только авторизованные пользователи могут делать это =)
if (1 != $authorized) { authorize(); }

if (!_permission_allow_update_team_info && 1 != $is_admin) {
    fail(_error_no_permission_to_update_team_info);
}

if (!isset($_POST['btnUpdatePersInfo'])) {
    fail(_error_request_method_post_expected);
}

if (!updatePersonalInfo($_POST)) {
    data('message', $messages[_error_persinfo_has_not_been_updated]);
    data('persInfo', Butler::getORMManager()->createPersonalInfo()->wrap($_POST));
} else {
    data('message', $messages[_success_persinfo_has_been_updated]);
    data('persInfo', selectPersonalInfo($curuserid));
}

data('changepassword', '0');

getUserInfo($curuserid);

template('changeinfo', $data);

