<?php
require_once('./config/require.php');

if (isset($code)) { data('message', $messages[$code]); }

template('message', $data);
?>

