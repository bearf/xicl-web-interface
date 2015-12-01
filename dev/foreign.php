<?php
require_once('./config/require.php');

if (!_settings_show_tournament_menu) { redirect(ServerRoot.'problemset.php'); }

template('foreign', $data);
?>
