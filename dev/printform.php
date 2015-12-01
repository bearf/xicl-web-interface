<?php
require_once('./config/require.php');
  
if (1 != $authorized) { authorize(); }
//проверка на разрешение к печати
if (!_permission_allow_print && 1 != $is_admin) { fail(_error_no_permission_for_print); }

//устанавливаем исходник в пустой
if (!isset($source)) { $source = ''; }
      
//запрос названий задач...
$query = $mysqli_->prepare('SELECT LPAD(V.ProblemID, 6, \' \') AS `ProblemID`, T.TaskID, T.Name'
    .' FROM (volume V INNER JOIN task T ON T.TaskID=V.TaskID)'
    .' WHERE V.ContestID=?'
    .' ORDER BY ProblemID');
$query->bind_param('i', $curcontest);
$query->bind_result($ProblemID, $TaskID, $Name);
if (!$query->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
$tasks = array();
while ($query->fetch()) {
    $instance = array();
    $instance['ProblemID'] = $ProblemID;
    $instance['TaskID'] = $TaskID;
    $instance['Name'] = $Name;
    $instance = (object) $instance;
    array_push($tasks, $instance);
}
$query->close();

data('tasks', $tasks);
data('source', $source);
if (isset($problemId)) { data('problemId', $problemId); }
if (isset($outcode)) { data('outcode', $outcode); }
if (isset($code)) { data('code', $code); } // todo: WTF?

template('printform', $data);
?>
