<?php
require_once('./config/require.php');

if (isset($selcontest)) {
    $q = $mysqli_->prepare('SELECT ContestID, Name FROM Cntest WHERE ContestID=? AND NOW()>=Start AND NOW()<=Finish');
    $q->bind_param('i', $selcontest);
    $q->bind_result($curcontest, $Name); // notice: first letter is capital
    if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
    if (!$q->fetch()) { fail(_error_requested_contest_is_not_available_code); } // auto-close query
    $message = 'Контест &quot;'.$Name.'&quot; установлен как текущий.';
    $q->close();
} 

// теперь запрашиваем лист всех турниров
$q = 'select ContestID, Name, Start, Finish, Status'
    .' from ('
        .'SELECT C1.ContestID, C1.Name, C1.Start, C1.Finish, 1 as status from cntest C1 where NOW()>=C1.Start AND NOW()<=C1.Finish'
        .' union SELECT C2.ContestID, C2.Name, C2.Start, C2.Finish, 2 as status from cntest C2 where NOW()>C2.Finish'
        .' union SELECT C3.ContestID, C3.Name, C3.Start, C3.Finish, 3 as status from cntest C3 where NOW() < C3.Start'
    .') S order by contestid';
$r = $mysqli_->query($q);
if (!$r) { fail(_error_mysql_query_error_code); } // auto-close query
$contests = array();
while ($f = $r->fetch_object()) { array_push($contests, $f); }
$r->close();

if (isset($message)) { data('message', $message); }
data('contests', $contests);

template('contest', $data);
?>
