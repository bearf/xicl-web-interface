<?php

require_once dirname(__FILE__) . '/../../config/require.php';

// только авторизованные пользователи могут делать это =)
if (1 != $authorized) { authorize(); }

if (!isset($_POST['btnCreatePersInfo'])) {
    fail(_error_request_method_post_expected);
}

if (!checkCreatePersonalInfo($curuserid)) {
    fail(_error_persinfo_already_exists);
}

if (!createPersonalInfo($curuserid)) {
    data('message', $messages[_error_persinfo_has_been_not_created]);
} else {
    data('message', $messages[_success_persinfo_has_been_created]);
    data('persInfo', selectPersonalInfo($curuserid));
}

data('changepassword', '0');

getUserInfo($curuserid);

template('changeinfo', $data);

