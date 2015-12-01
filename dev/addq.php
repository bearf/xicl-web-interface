<?php
  /*
   * Контроллер ввода вопроса
   */

require_once('./config/require.php');

if (1 != $authorized) { authorize(); } 
if (!isset($taskId)) { fail(_error_no_task_id_code); }
if (!isset($question) || '' === $question) { redirect(ServerRoot.'addqform.php?taskId='.$taskId.'&code='._error_no_question_body_code); }
if (!CheckSym($question)) { redirect(ServerRoot.'addqform.php?taskId='.$taskId.'&code='._error_incorrect_symbols_code.'&question='.$question); }

// запрос на проверку существования вопроса
$query = $mysqli_->prepare('select name from task where taskId=?');
$query->bind_param('i', $taskId);
if (!$query->execute()) { fail(_error_mysql_query_error_code); } // auto-close of query
if (!$query->fetch()) { fail(_error_no_task_found_code); } // auto-close of query
$query->close();
// конец проверки существования вопроса
  
$query = $mysqli_->prepare('insert into questions(dateTime, userId, taskId, question) values(now(), ?, ?, ?)');
$query->bind_param('iis', $curuserid, $taskId, stripslashes($question));
if (!$query->execute() || 0 == $query->affected_rows) { fail(_error_mysql_query_error_code); } // auto-close of query
$query->close();
redirect(ServerRoot.'message.php?code='._success_question_added_code);
?>
