<?php
require_once('./config/require.php');

// check for taskId - mandatory param
if (!isset($taskId)) { fail(_error_no_task_id_code); }
  
// query!
// get task name
$q = $mysqli_->prepare('select name from task where taskId=?');
$q->bind_param('i', $taskId);
$q->bind_result($taskName);
if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
if (!$q->fetch()) { fail(_error_no_task_found_code); } // auto-close query
$q->close();
// end of getting task name
  
// если есть админская авторизация - показываем все-все вопросы
if (1 == $is_admin) { $show_all = ' or true'; } else { $show_all = ''; }
// get info about questions
$q = $mysqli_->prepare('select Q.questionId as `questionId`, Q.dateTime as `dateTime`, Q.userId as `userId`, U.nickname as `userNickName`,'
    .' Q.question as `question`, Q.result as `result`, Q.comment as `comment`, Q.isPublic as `isPublic` '
    .' from (questions Q left join `user` U on Q.userId=U.Id) '
    .' where Q.taskId=? and (Q.userId=? or Q.isPublic=1'.$show_all.')'
    .' order by dateTime');
$q->bind_param('ii', $taskId, $curuserid);
$q->bind_result($_questionId, $dateTime, $userId, $userNickName, $question, $result, $comment, $isPublic);
if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
$questions = array();
while ($q->fetch()) {
    $instance = array();
    $instance['questionId'] = $_questionId;
    $instance['dateTime'] = $dateTime;
    $instance['userId'] = $userId;
    $instance['userNickName'] = $userNickName;
    $instance['question'] = $question;
    $instance['result'] = $result;
    $instance['comment'] = $comment;
    $instance['isPublic'] = $isPublic;
    $instance = (object) $instance;
    array_push($questions, $instance);
}
$q->close();

data('taskId', $taskId);
data('taskName', $taskName);
data('questions', $questions);

template('questions', $data);
?>
