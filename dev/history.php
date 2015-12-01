<?php 
require_once('./config/require.php'); 

// функция закрыта
if (!_settings_show_tournament_menu) { fail(_error_function_is_restricted); }

template('history', null) ?>
