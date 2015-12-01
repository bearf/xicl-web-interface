<?php
function getUserInfo($userId) {
    global $mysqli_, $data;
    $q = $mysqli_->prepare('SELECT T.teamname, M.teamid, U.nickname, U.name, U.country, U.city, U.studyplace, U.class as clss, U.email, U.allowpublish, U.info'
        .' FROM (`user` U left join members M on U.id = M.userid) left join teams T on M.teamId = T.teamId'
        .' WHERE id=?');
    $q->bind_param('i', $userId);
    if (!$q->execute()) { fail(_error_no_user_found_code); } // auto-close query
    $meta = $q->result_metadata();
    while ($field = $meta->fetch_field()) {
        $var = $field->name;
        $$var = null;
        $parameters[] = &$$var;
    }
    call_user_func_array(array($q, 'bind_result'), $parameters);
    //    $q->bind_result(
    //            $arr[0]
    //        ,   $arr[1]
    //        ,   $arr[2]
    //        ,   $arr[3]
    //        ,   $arr[4]
    //        ,   $arr[5]
    //        ,   $arr[6]
    //        ,   $arr[7]
    //        ,   $arr[8]
    //        ,   $arr[9]
    //    );
    if (!$q->fetch()) { fail(_error_no_user_found_code); } // auto-close query
    $q->close();

    data('teamid', $teamid);
    data('teamname', $teamname);
    data('allowpublish', $allowpublish);
    data('name', $name);
    data('nickname', $nickname);
    data('country', $country);
    data('city', $city);
    data('studyplace', $studyplace);
    data('clss', $clss);
    data('email', $email);
    data('info', $info);
} ?>
