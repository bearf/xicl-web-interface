<?php
error_reporting(E_ERROR);

// ?????????? ??????? ? ??????? ?????????? ???????
require_once dirname(__FILE__) . '/../template/fail.php';
require_once dirname(__FILE__) . '/../template/authorize.php';
require_once dirname(__FILE__) . '/../template/template.php';
require_once dirname(__FILE__) . '/../system/redirect.php';

// ?????????? ?????
require_once dirname(__FILE__) . '/../tiles/core/before.php';
require_once dirname(__FILE__) . '/../tiles/core/top.php';
require_once dirname(__FILE__) . '/../tiles/core/stuff.php';
require_once dirname(__FILE__) . '/../tiles/core/toolbar.php';
require_once dirname(__FILE__) . '/../tiles/core/login.php';
require_once dirname(__FILE__) . '/../tiles/core/timeleft.php';
require_once dirname(__FILE__) . '/../tiles/core/menu.php';
require_once dirname(__FILE__) . '/../tiles/core/support.php';
require_once dirname(__FILE__) . '/../tiles/core/contacts.php';
require_once dirname(__FILE__) . '/../tiles/core/after.php';

// ????? - ??????? 
require_once dirname(__FILE__) . '/../tiles/core/userlink.php';

// ???????
require_once dirname(__FILE__) . '/../commands/addnotify.php';
require_once dirname(__FILE__) . '/../commands/invite.php';
require_once dirname(__FILE__) . '/../commands/getUserInfo.php';
require_once dirname(__FILE__) . '/../commands/getInvitedTeams.php';
require_once dirname(__FILE__) . '/../commands/checkCreatePersonalInfo.php';
require_once dirname(__FILE__) . '/../commands/createPersonalInfo.php';
require_once dirname(__FILE__) . '/../commands/selectPersonalInfo.php';
require_once dirname(__FILE__) . '/../commands/updatePersonalInfo.php';
require_once dirname(__FILE__) . '/../commands/deletePersonalInfo.php';
require_once dirname(__FILE__) . '/../commands/getTeam.php';
require_once dirname(__FILE__) . '/../commands/queryUsers.php';
require_once dirname(__FILE__) . '/../commands/updateTeam.php';
require_once dirname(__FILE__) . '/../commands/getStatus.php';
require_once dirname(__FILE__) . '/../commands/getUsers.php';
require_once dirname(__FILE__) . '/../commands/getProblems.php';

require_once dirname(__FILE__) . '/../config/config.php';
require_once dirname(__FILE__) . '/../config/validation.php';
require_once dirname(__FILE__) . '/../config/messages.php';

require_once dirname(__FILE__) . '/../system/sessions.php';
require_once dirname(__FILE__) . '/../system/mysql.php';
require_once dirname(__FILE__) . '/../system/functions.php';

require_once dirname(__FILE__) . '/../system/errors.php';

require_once dirname(__FILE__) . '/../system/Butler.php';

require_once dirname(__FILE__) . '/../orm/shells/PersonalInfo.php';
require_once dirname(__FILE__) . '/../orm/shells/Team.php';
require_once dirname(__FILE__) . '/../orm/shells/Member.php';
require_once dirname(__FILE__) . '/../orm/shells/User.php';
require_once dirname(__FILE__) . '/../orm/shells/Status.php';
require_once dirname(__FILE__) . '/../orm/shells/Problem.php';

require_once dirname(__FILE__) . '/../template/inputs/combobox.php';

//    require_once dirname(__FILE__) . '/../system/html/url.manager.php';
require_once dirname(__FILE__) . '/../database/DBFacade.php';
require_once dirname(__FILE__) . '/../system/database/MySQLWrapper.php';
//    require_once dirname(__FILE__) . '/../system/exceptions/exception.manager.php';
//    require_once dirname(__FILE__) . '/../system/engine/tiler.php';
//    require_once dirname(__FILE__) . '/../system/engine/tile.manager.php';
//    require_once dirname(__FILE__) . '/../system/engine/runner.manager.php';
require_once dirname(__FILE__) . '/../orm/QueryManager.php';
require_once dirname(__FILE__) . '/../orm/ORMManager.php';
//    require_once dirname(__FILE__) . '/../system/session/session.manager.php';
//    require_once dirname(__FILE__) . '/engine/scenario.php';
//    require_once dirname(__FILE__) . '/../config/local.config.php';
//    require_once dirname(__FILE__) . '/../config/production.config.php';





$data = array();

function _data($index) {
    global $data;
    return _has($index) ? $data[$index] : '';
}

function _has($index) {
    global $data;
    return array_key_exists($index, $data);
}

function data($index, $value) {
    global $data;
    $data[$index] = $value;
}

$q = $mysqli_->prepare('SELECT Name FROM Cntest WHERE ContestID=? and start<=NOW() and finish>=NOW()');
$q->bind_param('i', $curcontest);
$q->bind_result($contestname);
if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
//if (!$q->fetch()) { fail(_error_requested_contest_is_not_available_code); } // auto-close query
if (!$q->fetch()) { $contestname = 'нет контеста'; } // auto-close query
data('contestname', $contestname);
$q->close();

$r = $mysqli_->query('select now() as time');
if (!$r) { fail(_error_mysql_query_error_code); } // auto-close query
$srvtime = $r->fetch_object()->time;
data('srvtime', $srvtime);
$r->close();

if (_settings_show_time_left) {
    $query = 'select timediff(finish, now()) as `left` from cntest where contestid='.$curcontest.' and now()>=start and now()<=finish';
    $contestrec = $mysqli_->query($query);
    if (!$contestrec) { fail(_error_mysql_query_error_code); } // auto-close query
    if (0 < $contestrec->num_rows) {
        $contestf = $contestrec->fetch_object();
        data('timeleft', 'до конца: '.$contestf->left);
    }
}

// ?????? ??????? ? ???????? ??????? ???????
if (1 == $authorized):
    $q = $mysqli_->prepare('select M.teamid from members M where M.userid=? LIMIT 1');
    $q->bind_param('i', $curuserid);
    $q->bind_result($curteamid);
    if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
    if (!$q->fetch()) { $curteamid = -1; }
    $q->close();
    $q = $mysqli_->prepare('select T.teamname from `teams` T where T.teamid=?');
    $q->bind_param('i', $curteamid);
    $q->bind_result($curteamname);
    if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
    if (!$q->fetch()) { $curteamname = ''; }
    $q->close();
else: // ????? ??? ???????
    $curteamid = -1;
endif; // ????? ??????? ??????? ??????? ???????

// ?????? ?????????? ???????????
if (1 == $authorized):
    $q = $mysqli_->prepare('select count(*) from `messages` M inner join `reads` R on M.messageid=R.messageid and R.userid=? where M.kind=2 and (M.touser=? and M.touser<>-1 or M.toteam=? and M.toteam<>-1 or M.touser=-1 and M.toteam=-1) and M.date > (select U.regdate from `user` U where U.id=?)');
    $q->bind_param('iiii', $curuserid, $curuserid, $curteamid, $curuserid);
    $q->bind_result($tmp1);
    if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
    $q->fetch(); // always one row
    $q->close();
    $q = $mysqli_->prepare('select count(*) from `messages` M where M.kind=2 and (M.touser=? and M.touser<>-1 or M.toteam=? and M.toteam<>-1 or M.touser=-1 and M.toteam=-1) and M.date > (select U.regdate from `user` U where U.id=?)');
    $q->bind_param('iii', $curuserid, $curteamid, $curuserid);
    $q->bind_result($tmp2);
    if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
    $q->fetch(); // always one row
    data('notifycount', $tmp2-$tmp1);
    $q->close();
endif; // ????? ??????? ?????????? ???????????

// ?????? ?????????? ?????????
if (1 == $authorized):
    $q = $mysqli_->prepare('select count(*) from `messages` M inner join `reads` R on M.messageid=R.messageid and R.userid=? where M.kind=1 and M.touser=?');
    $q->bind_param('ii', $curuserid, $curuserid);
    $q->bind_result($tmp1);
    if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
    $q->fetch(); // always one row
    $q->close();
    $q = $mysqli_->prepare('select count(*) from `messages` M where M.kind=1 and M.touser=?');
    $q->bind_param('i', $curuserid);
    $q->bind_result($tmp2);
    if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
    $q->fetch(); // always one row
    $messagecount = $tmp2-$tmp1;
    $q->close();
endif; // ????? ??????? ?????????? ???????????

// ????????, ??? ??????? ??????????
$teaminvited = 0;
if (1 == $authorized):
    $q = $mysqli_->prepare('select count(*) from teams where teamid=? and invited=1');
    $q->bind_param('i', $curteamid);
    $q->bind_result($teaminvited);
    if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
    $q->fetch();
    $q->close();
endif;

?>
