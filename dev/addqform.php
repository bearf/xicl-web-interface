<?php
require_once('./config/require.php');

if (1 != $authorized) { authorize(); }

//проверка на корректность параметров 
if (!isset($question)) { $question = ''; }
if (!isset($taskId)) { fail(_error_no_task_id_code); }

//запрос на добычу названия задачи
$query = $mysqli_->prepare('select name from task where taskId=?');
$query->bind_param('i', $taskId);
$query->bind_result($taskName);
if (!$query->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
if (!$query->fetch()) { fail(_error_no_task_found_code); } // auto-close query
$query->close();

data('taskName', $taskName);
data('question', $question);
data('taskId', $taskId);
if (isset($code)) { data('code', $code); }

template('addqform', $data);
?>
