<?php
require_once('./config/require.php');

if (!_permission_allow_view_user_info && 1 != $is_admin) { authorize(); }

if (!isset($userid)) { fail(_error_no_user_id_code); }

$q = $mysqli_->prepare('SELECT U.ID, U.Name, U.Nickname, U.Country, U.City, U.Studyplace, U.Class, U.Email, U.Allowpublish, U.Info, T.TeamName, T.teamId'
    .' FROM (`User` U left join Members M on U.id = M.userid) left join Teams T on M.TeamID=T.TeamID'
    .' WHERE U.ID=?');
$q->bind_param('i', $userid);
$q->bind_result($ID, $Name, $Nickname, $Country, $City, $Studyplace, $Class, $Email, $Allowpublish, $Info, $TeamName, $teamId);
if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
if (!$q->fetch()) { fail(_error_no_user_found_code); } // auto-close query

$instance = array();
$instance['ID'] = $ID;
$instance['Name'] = $Name;
$instance['Nickname'] = $Nickname;
$instance['Country'] = $Country;
$instance['City'] = $City;
$instance['Studyplace'] = $Studyplace;
$instance['Class'] = $Class;
$instance['Email'] = $Email;
$instance['Allowpublish'] = $Allowpublish;
$instance['Info'] = $Info;
$instance['TeamName'] = $TeamName;
$instance['teamId'] = $teamId;
$instance = (object) $instance;
$q->close();

data('instance', $instance);

if (!checkCreatePersonalInfo($userid) && 1 == $is_admin) {
    data('persInfo', selectPersonalInfo($userid));
}

template('userinfo', $data);
?>
