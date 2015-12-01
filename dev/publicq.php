<?php
  /*
   * ���������� ����� �������
   */

require_once('./config/require.php');

if (1 != $is_admin) { authorize(); }
if (!isset($questionId)) { fail(_error_no_question_id_code); }

// ���������
$public = isset($public) && $public ? 1 : 0;

// ������ �� �������� ������������� �������
$query = $mysqli_->prepare('select questionId from questions where questionId=?');
$query->bind_param('i', $questionId);
if (!$query->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
if (!$query->fetch()) { fail(_error_no_question_found_code); } // auto-close query
$query->close();
// ����� �������� ������������� �������
  
// ���������� ������
$query = $mysqli_->prepare('update questions set isPublic=? where questionId=?');
$query->bind_param('ii', $public, $questionId);
if (!$query->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
// todo: affected rows
$query->close();
redirect(ServerRoot.'message.php?code='._success_question_set_public_code);
// todo: ���������� ����-���� � ������ ����� )
?>
