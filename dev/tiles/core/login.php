<?php function login() { ?>
<?php global $authorized; ?>
<?php global $is_admin; ?>
<?php global $curnickname; ?>
<?php global $curuserid; ?>
<?php global $messagecount; ?>
<?php global $mysqli_; ?>
<?php
// запрос количества сообщений
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
endif; // конец запроса количества уведомлений
?>
<?php if ($authorized == 1): ?>
<form name="frmLogout" action="logout.php" method="post" style="text-align:right;">
    <?php userlink($curnickname, $curuserid); ?>
    &nbsp;&nbsp;::<a href="javascript:void(0);" onclick="document.frmLogout.submit();">выйти</a>
</form>
<?php else: ?>
<form name="frmLogin" action="login.php" method="post" style="text-align:right;">
    <span style="float:left;width:40px;text-align:left;">login</span>&nbsp;<input type="text" name="login" maxlength="50" /><br /><br />
    <span style="float:left;width:40px;text-align:left;">пароль</span>&nbsp;<input type="password" name="password" maxlength="20" /><br /><br />
    ::<input type="submit" id="btnsubmit" class="submit" name="btnsubmit" value="войти" />
    <?php if (_permission_allow_register_new_user || ($is_admin == 1)): // если разрешено показывать регистрацию или мы в админском режиме - показываем ?>          
    &nbsp;::<a href="./register.php">регистрация</a>
    <?php endif; //конец проверки возможности показа регистрации ?>
</form>
<?php endif; ?>
<?php } ?>
