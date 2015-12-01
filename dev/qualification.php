<?php
require_once('./config/require.php');
require_once('./commands/standing.php');

// функция закрыта
if (!_settings_show_tournament_menu) { fail(_error_function_is_restricted); }
// todo: test all usecases

// todo: hard-coded!
//standing(
//    12,
//    1,
//    2000
//);

if (isset($code)) { data('message', $messages[$code]); }

template('qualification', $data);
?>
