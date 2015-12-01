<?php function source($submitid) { ?>
<?php global $is_admin; global $mysqli_;

if (1 != $is_admin) { authorize(); }

// параметры
if (!isset($submitid)) { fail(_error_no_submit_id_code); }

// читаем данные
$query = $mysqli_->prepare('select S.source, S.contestid, S.userid, U.nickname, L.desc, S.submittime, S.message from ((select Si.contestId, Si.userid, Si.langId, Si.source, Si.submittime, Si.message from `submit` Si where Si.submitID=?) S inner join `user` U on S.userid=U.id) inner join Lang L on S.langId=L.langId');
$query->bind_param('i', $submitid);
$query->bind_result($source, $contestid, $userid, $nickname, $language, $submitdate, $submitmessage);
if (!$query->execute()) { fail(_error_mysql_query_error_code); } // auto-close of query 
$query->store_result();
if (0 == $query->num_rows) { fail(_error_no_submit_found_code); } // auto-close of quert
$query->fetch();
if ('' == $source) { fail(_error_submit_source_is_empty_code); } // auto-close of query
$query->close();
// конец считывания данных

data('source', $source);
data('submitid', $submitid);
data('contestid', $contestid);
data('nickname', $nickname);
data('userid', $userid);
data('language', $language);
data('submitdate', $submitdate);
data('submitmessage', $submitmessage);
data('top', isset($_GET['top']) ? $_GET['top'] : -1);
data('topuserid', isset($_GET['userid']) ? $_GET['userid'] : -1);

} ?>
