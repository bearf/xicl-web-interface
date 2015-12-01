<?php
  /*
   * Контроллер ввода вопроса
   */

require_once('./config/require.php');

if (1 != $is_admin) { authorize(); }
if (!isset($questionId)) { fail(_error_no_question_id_code); }

// запрос на проверку существования вопроса
$query = $mysqli_->prepare('select questionId from questions where questionId=?');
$query->bind_param('i', $questionId);
if (!$query->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
if (!$query->fetch()) { fail(_error_no_question_found_code); } // auto-close query
$query->close();
// конец проверки существования вопроса
  
// производим вопрос
$query = $mysqli_->prepare('delete from questions where questionId=?');
$query->bind_param('i', $questionId);
if (!$query->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
if (0 == $query->affected_rows) { fail(_error_mysql_query_error_code); } // auto-close query
$query->close();
redirect(ServerRoot.'message.php?code='._success_question_deleted_code);
?>
