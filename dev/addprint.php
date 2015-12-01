<?php
  /*
   * Контроллер ввода вопроса
   */

require_once('./config/require.php');

if (1 != $authorized) { authorize(); }
// todo: check this condition
if (-1 == $curcontest) { fail(_error_no_current_contest_code); }

// параметры
if (!isset($problemId)) { fail(_error_no_problem_id_code); }
$problemId = trim($problemId);    
// проверяем на непустоту исходника
if (!isset($source) || '' == $source) { redirect(ServerRoot.'printform.php?problemId='.$problemId.'&outcode='._error_source_is_empty_code); }
// проверяем на размер исходника
if (strlen($source) > 65535) { redirect(ServerRoot.'printform.php?problemId='.$problemId.'&outcode='._error_source_is_too_large_code); }
      
// запрос на проверку существования задания
// todo: что если нет контеста
$query = $mysqli_->prepare('select name from task T inner join volume V on T.taskId=V.taskId where V.problemId=? and V.contestId=?');
$query->bind_param('si', $problemId, $curcontest);
if (!$query->execute()) { fail(_error_mysql_query_error_code); } // auto-close of query 
if (!$query->fetch()) { fail(_error_no_task_found_code); } // auto-close of query 
$query->close();
// конец проверки существования вопроса

// добавляем исходник
$source = get_magic_quotes_gpc() ? stripslashes($source) : $source;
$query = $mysqli_->prepare('insert into print(dateTime, userId, problemId, contestId, source) values(now(), ?, ?, ?, ?)');
$query->bind_param('isis', $curuserid, $problemId, $curcontest, 
	$source);
if (!$query->execute() || 0 == $query->affected_rows) { fail(_error_mysql_query_error_code); } // auto-close of query 
$query->close();
redirect(ServerRoot.'message.php?code='._success_print_added_code);
?>
