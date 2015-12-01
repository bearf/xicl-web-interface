<?php require_once('./config/require.php'); ?>
<?php
// функция закрыта
if (!_settings_show_tournament_menu) { fail(_error_function_is_restricted); }
?>
<?php redirect(ServerRoot.'qualification.php?code='.invite(
    isset($teamid) ? $teamid : -1
    ,isset($mode) ? $mode : 1
)); ?>
