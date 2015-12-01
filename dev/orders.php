<?php
require_once('./config/require.php');

// функция закрыта
if (!_settings_show_tournament_menu) { fail(_error_function_is_restricted); }
if (1 != $is_admin) { authorize(); }

$q = 'select O.orderId, T.teamId, T.teamname, O.orderdate, O.studyplace from (orders O inner join teams T on O.teamid=T.teamid) order by T.teamname, O.orderId';
$r = $mysqli_->query($q);
if (!$r) { fail(_error_mysql_query_error_code); } // auto-close query
$orders = array();
while ($f = $r->fetch_object()) { array_push($orders, $f); }
$r->close();

data('orders', $orders);
template('orders', $data);
?>
