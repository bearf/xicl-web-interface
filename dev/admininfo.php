<?php
require_once('./config/require.php');

// todo: admin authorize
if (1 != $is_admin) { authorize(); }

$r = $mysqli_->query('select Q.questionId as `questionId`, Q.dateTime as `dateTime`, Q.userId as `userId`, U.nickname as `userNickName`,'
        .' Q.taskId as `taskId`, Q.question as `question`, Q.result as `result`, Q.comment as `comment`, Q.isPublic as `isPublic`, T.Name as `taskName`'
    .' from (questions Q left join `user` U on Q.userId=U.Id ) inner join Task T on Q.taskId=T.taskId'
    .' where result=0 and comment is null'
    .' order by dateTime desc');
if (!$r) { fail(_error_mysql_query_error_code); } // auto-close query
$new_questions = array();
while ($f = $r->fetch_object()) { array_push($new_questions, $f); }
$r->close();

$r = $mysqli_->query('select Q.questionId as `questionId`, Q.dateTime as `dateTime`, Q.userId as `userId`, U.nickname as `userNickName`,'
        .' Q.taskId as `taskId`, Q.question as `question`, Q.result as `result`, Q.comment as `comment`, Q.isPublic as `isPublic`, T.Name as `taskName`'
    .' from (questions Q left join `user` U on Q.userId=U.Id) inner join Task T on Q.taskId=T.taskId'
    .' where result>0 or comment is not null' 
    .' order by dateTime desc');
if (!$r) { fail(_error_mysql_query_error_code); } // auto-close query
$old_questions = array();
while ($f = $r->fetch_object()) { array_push($old_questions, $f); }
$r->close();

$r = $mysqli_->query('select P.printId as `printId`, P.dateTime as `dateTime`, P.userId as `userId`, U.nickname as `userNickName`,'
    .' P.contestId as `contestId`, P.problemId as `problemId`, P.isPrinted as `isPrinted`, T.Name as `taskName`'
    .' from ((print P left join `user` U on P.userId=U.Id) inner join volume V on P.contestId=V.contestId and P.problemId=V.problemId ) inner join task T on V. taskId=T.TaskId'
    .' order by dateTime desc');
if (!$r) { fail(_error_mysql_query_error_code); } // auto-close query
$print_queue = array();
while ($f = $r->fetch_object()) { array_push($print_queue, $f); }
$r->close();

data('new_questions', $new_questions); 
data('old_questions', $old_questions); 
data('print_queue', $print_queue); 

template('admininfo', $data);
?>
