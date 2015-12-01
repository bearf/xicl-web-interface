<?php
  /*
   *  онтроллер ввода вопроса
   */

require_once('./config/require.php');

if (1 != $is_admin) { authorize(); }
if (!isset($questionId)) { fail(_error_no_question_id_code); }

// параметры
if (!isset($comment)) { $comment = ''; }
// провер€ем на корректность символов
if (!CheckSym($comment)) { redirect(ServerRoot.'editqform.php?questionId='.$questionId.'&code='._error_incorrect_symbols_code.'&comment='.$comment.'&result='.$result); }
// заполн€ем значени€ чекбоксов
$result = isset($yes) ? 1 : (isset($no) ? 2 : (isset($nocomment) ? 3 : 0));
      
// запрос на проверку существовани€ вопроса
$query = $mysqli_->prepare('select questionId from questions where questionId=?');
$query->bind_param('i', $questionId);
if (!$query->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
if (!$query->fetch()) { fail(_error_no_question_found_code); } // auto-close query
$query->close();
// конец проверки существовани€ вопроса
  
// добавл€ем вопрос
$query = $mysqli_->prepare('update questions set comment=?, result=? where questionId=?');
$query->bind_param('sii', stripslashes($comment), $result, $questionId);
if (!$query->execute()) { fail(_error_mysql_query_error_code); }
// todo: affected rows
$query->close();
redirect(ServerRoot.'editqform.php?questionId='.$questionId.'&code='._success_question_edited_code);
?>
