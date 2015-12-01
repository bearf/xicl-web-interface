<?php
  /*
   * Контроллер ввода вопроса
   */

require_once('./config/require.php');

if (1 != $is_admin) { authorize(); }
      
if (!isset($printId)) { fail(_error_no_print_id_code); }
  
// запрос на проверку существования задания печати
// todo: что если нет контеста
$query = $mysqli_->prepare('select * from print where printid=?');
$query->bind_param('i', $printId);
if (!$query->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
if (!$query->fetch()) { fail(_error_no_print_found_code); } // auto-close query
$query->close();
// конец проверки существования задания печати

// производим запрос
$query = $mysqli_->prepare('delete from print where printId=?');
$query->bind_param('i', $printId);
if (!$query->execute() || 0 == $query->affected_rows) { fail(_error_mysql_query_error_code); } // auto-close query
$query->close();
redirect(ServerRoot.'message.php?code='._success_print_deleted_code);
?>
