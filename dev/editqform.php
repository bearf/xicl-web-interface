<?php
require_once('./config/require.php');
  
if (1 != $is_admin) { authorize(); }

if (!isset($questionId)) { fail(_error_no_question_id_code); }
      
//запрос на добычу вопроса и всего, что с ним связано
$query = $mysqli_->prepare('select taskId, question, result, comment from questions where questionId=?');
$query->bind_param('i', $questionId);
$query->bind_result($taskId, $question, $result, $comment);
if (!$query->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
if (!$query->fetch()) { fail(_error_no_question_found_code); } // auto-close query
$query->close();

data('taskId', $taskId);
data('question', $question);
data('result', $result);
data('comment', $comment);
data('yes_checkbox_value', $result == 1 ? ' checked="checked" ' : '');
data('no_checkbox_value', $result == 2 ? ' checked="checked" ' : '');
data('nocomment_checkbox_value', $result == 3 ? ' checked="checked" ' : '');
  
//запрос на добычу названия задачи
$query = $mysqli_->prepare('select name from task where taskId=?');
$query->bind_param('i', $taskId);
$query->bind_result($taskName);
if (!$query->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
if (!$query->fetch()) { fail(_error_no_task_found_code); } // auto-close query
$query->close();

data('taskName', $taskName);
if (isset($code)) { data('code', $code); }
data('questionId', $questionId);

template('editqform', $data);
?>
