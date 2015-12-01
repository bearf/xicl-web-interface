<?php function stuff($content_tile_name = null) { ?>
<?php global $data; ?>
<?php global $authorized; ?>
<?php global $curuserid; ?>
<?php global $curnickname; ?>
<?php global $is_admin; ?>
<?php global $teaminvited; ?>
<?php global $teamordersent; ?>
    <span style="float:right">12+</span>
    <?php if ('нет контеста' == _data('contestname') || 'contest' == get_site_branch($content_tile_name)): ?>
        &nbsp;&nbsp;::<a href="./contest.php" title="сменить текущий контест"><?=_data('contestname')?></a>
    <?php else: ?>
        &nbsp;&nbsp;::<a href="./problemset.php" title="задачи текущего контеста"><?=_data('contestname')?></a>
    <?php endif; ?>
    <?=_has('timeleft') ? ' - '._data('timeleft') : ''?>
    <?php if (1 == $authorized): ?>
        &nbsp;&nbsp;::<?php userlink($curnickname, $curuserid); ?>
        &nbsp;&nbsp;::<a href="./notifylist.php">уведомления</a><?=0 < _data('notifycount') ? '('._data('notifycount').')' : ''?>
        <?php if (_settings_show_tournament_menu): ?>
            &nbsp;&nbsp;::<a href="./team/update/">команда</a>
        <?php endif ?>
        &nbsp;&nbsp;::<a href="./logout.php">выйти</a>
    <?php else: ?> 
        &nbsp;&nbsp;::<a href="./login.php?firstattempt=true">вход</a>
        <?php if (_permission_allow_register_new_user || ($is_admin == 1)): // если разрешено показывать регистрацию или мы в админском режиме - показываем ?>          
            &nbsp;&nbsp;::<a href="./register.php">регистрация</a>
        <?php endif; ?>
    <?php endif; ?>
<?php } ?>
