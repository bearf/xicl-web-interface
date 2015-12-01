<?php

require_once('./config/require.php');
require_once('./commands/source.php');

source(
    $_GET['submitid']
);

template('source', $data);

?>
