<?php
require_once('./config/require.php');

// функция закрыта
if (!_settings_show_tournament_menu) { fail(_error_function_is_restricted); }
if (1 != $authorized) { authorize(); }
if (1 != $is_admin) { authorize(); }

// проверка на установленную команду 
if (1 != $is_admin && 1 > $curteamid) { fail(_error_you_have_no_team_code); }
// конец проверки  на установленную команду

// пробуем достать последнюю заявку
if (!isset($orderid)):
    $q = $mysqli_->prepare('select orderid from orders where teamid=? order by orderid desc limit 1');
    $q->bind_param('i', $curteamid);
    $q->bind_result($orderid);
    if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
    $q->fetch();
    $q->close();
endif;
// конец попытки достать последнюю заявку

if (isset($orderid)):
    $q_str = 'select O.teamid, O.orderdate'
        .',O.city,O.studyplace,O.address,O.phone,O.fax,O.codegamechallenge'
        .',O.contactname,O.contactphone,O.contactmail'
        .',O.contestant1name,O.contestant1studyplace,O.contestant1classcourse'
        .',O.contestant1birthdate,O.contestant1address,O.contestant1inn'
        .',O.contestant1passportno,O.contestant1passportplace,O.contestant1passportdate'
        .',O.contestant2name,O.contestant2studyplace,O.contestant2classcourse'
        .',O.contestant2birthdate,O.contestant2address,O.contestant2inn'
        .',O.contestant2passportno,O.contestant2passportplace,O.contestant2passportdate'
        .',O.contestant3name,O.contestant3studyplace,O.contestant3classcourse'
        .',O.contestant3birthdate,O.contestant3address,O.contestant3inn'
        .',O.contestant3passportno,O.contestant3passportplace,O.contestant3passportdate'
        .',O.headname,O.headpost'
        .',O.headbirthdate,O.headaddress,O.headinn'
        .',O.headpassportno,O.headpassportplace,O.headpassportdate'
        .',O.coachname,O.coachpost'
        .',O.coachbirthdate,O.coachaddress,O.coachinn'
        .',O.coachpassportno,O.coachpassportplace,O.coachpassportdate'
        .',T.teamname'
        .' from orders O inner join teams T on O.teamid=T.teamid where orderid=?';
    $q = $mysqli_->prepare($q_str);
    $q->bind_result($teamid,$orderdate
        ,$city,$studyplace,$address,$phone,$fax,$codegamechallenge
        ,$contactname,$contactphone,$contactmail
        ,$contestant1name,$contestant1studyplace,$contestant1classcourse
        ,$contestant1birthdate,$contestant1address,$contestant1inn
        ,$contestant1passportno,$contestant1passportplace,$contestant1passportdate
        ,$contestant2name,$contestant2studyplace,$contestant2classcourse
        ,$contestant2birthdate,$contestant2address,$contestant2inn
        ,$contestant2passportno,$contestant2passportplace,$contestant2passportdate
        ,$contestant3name,$contestant3studyplace,$contestant3classcourse
        ,$contestant3birthdate,$contestant3address,$contestant3inn
        ,$contestant3passportno,$contestant3passportplace,$contestant3passportdate
        ,$headname,$headpost
        ,$headbirthdate,$headaddress,$headinn
        ,$headpassportno,$headpassportplace,$headpassportdate
        ,$coachname,$coachpost
        ,$coachbirthdate,$coachaddress,$coachinn
        ,$coachpassportno,$coachpassportplace,$coachpassportdate
        ,$orderteamname);
    $q->bind_param('i', $orderid);
    if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
    $q->fetch();
    $q->close();
endif;

data('orderteamname', isset($orderteamname) ? $orderteamname : '');

data('city', isset($city) ? $city : '');
data('studyplace', isset($studyplace) ? $studyplace : '');
data('address', isset($address) ? $address : '');
data('phone', isset($phone) ? $phone : '');
data('fax', isset($fax) ? $fax : '');
data('contactname', isset($contactname) ? $contactname : '');
data('contactphone', isset($contactphone) ? $contactphone : '');
data('contactmail', isset($contactmail) ? $contactmail : '');

data('contestant1name', isset($contestant1name) ? $contestant1name : '');
data('contestant1studyplace', isset($contestant1studyplace) ? $contestant1studyplace : '');
data('contestant1classcourse', isset($contestant1classcourse) ? $contestant1classcourse : '');
data('contestant1birthdate', isset($contestant1birthdate) ? $contestant1birthdate : '');
data('contestant1address', isset($contestant1address) ? $contestant1address : '');
data('contestant1inn', isset($contestant1inn) ? $contestant1inn : '');
data('contestant1passportno', isset($contestant1passportno) ? $contestant1passportno : '');
data('contestant1passportplace', isset($contestant1passportplace) ? $contestant1passportplace : '');
data('contestant1passportdate', isset($contestant1passportdate) ? $contestant1passportdate : '');

data('contestant2name', isset($contestant2name) ? $contestant2name : '');
data('contestant2studyplace', isset($contestant2studyplace) ? $contestant2studyplace : '');
data('contestant2classcourse', isset($contestant2classcourse) ? $contestant2classcourse : '');
data('contestant2birthdate', isset($contestant2birthdate) ? $contestant2birthdate : '');
data('contestant2address', isset($contestant2address) ? $contestant2address : '');
data('contestant2inn', isset($contestant2inn) ? $contestant2inn : '');
data('contestant2passportno', isset($contestant2passportno) ? $contestant2passportno : '');
data('contestant2passportplace', isset($contestant2passportplace) ? $contestant2passportplace : '');
data('contestant2passportdate', isset($contestant2passportdate) ? $contestant2passportdate : '');

data('contestant3name', isset($contestant3name) ? $contestant3name : '');
data('contestant3studyplace', isset($contestant3studyplace) ? $contestant3studyplace : '');
data('contestant3classcourse', isset($contestant3classcourse) ? $contestant3classcourse : '');
data('contestant3birthdate', isset($contestant3birthdate) ? $contestant3birthdate : '');
data('contestant3address', isset($contestant3address) ? $contestant3address : '');
data('contestant3inn', isset($contestant3inn) ? $contestant3inn : '');
data('contestant3passportno', isset($contestant3passportno) ? $contestant3passportno : '');
data('contestant3passportplace', isset($contestant3passportplace) ? $contestant3passportplace : '');
data('contestant3passportdate', isset($contestant3passportdate) ? $contestant3passportdate : '');

data('headname', isset($headname) ? $headname : '');
data('headpost', isset($headpost) ? $headpost : '');
data('headbirthdate', isset($headbirthdate) ? $headbirthdate : '');
data('headaddress', isset($headaddress) ? $headaddress : '');
data('headinn', isset($headinn) ? $headinn : '');
data('headpassportno', isset($headpassportno) ? $headpassportno : '');
data('headpassportplace', isset($headpassportplace) ? $headpassportplace : '');
data('headpassportdate', isset($headpassportdate) ? $headpassportdate : '');

data('coachname', isset($coachname) ? $coachname : '');
data('coachpost', isset($coachpost) ? $coachpost : '');
data('coachbirthdate', isset($coachbirthdate) ? $coachbirthdate : '');
data('coachaddress', isset($coachaddress) ? $coachaddress : '');
data('coachinn', isset($coachinn) ? $coachinn : '');
data('coachpassportno', isset($coachpassportno) ? $coachpassportno : '');
data('coachpassportplace', isset($coachpassportplace) ? $coachpassportplace : '');
data('coachpassportdate', isset($coachpassportdate) ? $coachpassportdate : '');

data('codegamechallenge', isset($codegamechallenge) && $codegamechallenge ? 1 : 0);

//template('index', $data);
require_once('./tiles/order.print.php');
?>

