<?php

require_once dirname(__FILE__) . '/../../config/require.php';

// только авторизованные пользователи могут делать это =)
if (1 != $authorized) { authorize(); }

if (!isset($_POST['btnDeletePersInfo'])) {
    fail(_error_request_method_post_expected);
}

if (checkCreatePersonalInfo($curuserid)) {
    fail(_error_persinfo_does_not_exists);
}

if (!deletePersonalInfo($_POST)) {
    data('message', $messages[_error_persinfo_has_been_not_deleted]);
    data('persInfo', selectPersonalInfo($curuserid));
} else {
    data('message', $messages[_success_persinfo_has_been_deleted]);
}

data('changepassword', '0');

getUserInfo($curuserid);

template('changeinfo', $data);

