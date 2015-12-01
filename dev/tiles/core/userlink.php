<?php function userlink($username, $userid) { ?>
<?php global $curuserid; ?>
<?php global $is_admin; ?>
<?php global $authorized; ?>
<?php global $messagecount; ?>
<?php if ($userid == $curuserid && (_permission_allow_change_info || $is_admin == 1)): // если разрешено изменять данные ?>          
    <a href="./changeinfo.php" title="изменить данные"><?=$username?></a>
    <?php if (_permission_allow_mail && $userid == $curuserid): ?>
        <a href="./mail.php" title="сообщения"><img src="./graphic/mail.png" alt="mail" width="12" height="8" style="vertical-align:middle;" /></a>
        <span class="message-count"><?php if ($messagecount > 0):?>(<?=$messagecount?>)<?php endif; ?></span>
    <?php endif; ?>
<?php elseif (_permission_allow_view_user_info || 1 == $is_admin || $userid == $curuserid ): ?>
    <a href="./userinfo.php?userid=<?=$userid?>" title="просмотр данных"><?=$username?></a>
    <?php if (_permission_allow_mail && $userid == $curuserid): ?>
        <a href="./mail.php" title="сообщения"><img src="./graphic/mail.png" alt="mail" width="12" height="8" style="vertical-align:middle;" /></a>
        <span class="message-count"><?php if ($messagecount > 0):?>(<?=$messagecount?>)<?php endif; ?></span>
    <?php endif; ?>
<?php else: ?>
    <?=$username?>
    <?php if (_permission_allow_mail && $userid == $curuserid): ?>
        <a href="./mail.php" title="сообщения"><img src="./graphic/mail.png" alt="mail" width="12" height="8" style="vertical-align:middle;" /></a>
        <span class="message-count"><?php if ($messagecount > 0):?>(<?=$messagecount?>)<?php endif; ?></span>
    <?php endif; ?>
<?php endif; ?>
<?php if (_permission_allow_mail && $userid != $curuserid && 1 == $authorized): ?>
    <a href="javascript:void(0);" title="отправить сообщение" onclick="showMessage('<?=$username?>', <?=$userid?>)"><img src="./graphic/mail.png" alt="mail" width="12" height="8" style="vertical-align:middle;" /></a>
<?php endif; ?>
<?php } ?>
