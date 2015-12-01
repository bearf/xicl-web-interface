<?php
require_once('./config/require.php');

if (!_permission_allow_mail) { fail(_error_no_permission_for_mail); }
if (1 != $authorized) { authorize(); }

// todo: through LIMIT sql-expression
$pagesize = 40;
if (!isset($page)) { $page = 1; }

$qs = 'select * from ('
    .'select M.messageId, M.date, M.from, U.nickname, M.header, M.message, (select count(*) from `reads` R where R.userid=M.touser and R.messageid=M.messageId) as `read`, 1 as inbound from messages M inner join `user` U on M.from=U.id where M.kind=1 and M.touser=?'
    .' union select M.messageId, M.date, M.touser, U.nickname, M.header, M.message, (select count(*) from `reads` R where R.userid=M.touser and R.messageid=M.messageId) as `read`, 0 as inbound from messages M inner join `user` U on M.touser=U.id where M.kind=1 and M.from=?'
    .') S order by S.messageId desc';
$q = $mysqli_->prepare($qs); $q->bind_param('ii', $curuserid, $curuserid);
$q->bind_result($messageid, $date, $from, $nickname, $header, $text, $read, $inbound);
if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
$mail = array(); $count = 0;
while ($q->fetch()) { 
    if (++$count > ($page-1)*$pagesize && $count < $page*$pagesize+1) {
        $f = array();
        $f['messageid'] = $messageid;
        $f['date'] = $date;
        $f['from'] = $from;
        $f['nickname'] = $nickname;
        $f['header'] = $header;
        $f['text'] = $text;
        $f['read'] = $read;
        $f['inbound'] = $inbound;
        array_push($mail, (object) $f); 
    }
}
$pagecount = $count / $pagesize;
if ($count % $pagesize != 0) { $pagecount += 1; }
$q->close();

data('page', $page);
data('pagecount', $pagecount);
data('mail', $mail);
template('mail', $data);
?>
