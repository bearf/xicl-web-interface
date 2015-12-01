<?php require_once('./config/require.php'); ?>
<?php redirect(ServerRoot.'notifylist.php?code='.notify(
    isset($touser) ? $touser : -1
    ,isset($toteam) ? $toteam : -1
    ,isset($header) ? $header : ''
    ,isset($notify) ? $notify : ''
)); ?>
