<?php
require_once('./config/require.php');
require_once('./commands/standing.php');

// todo: test all usecases

standing(
        isset($contest) ? $contest : $curcontest
    ,   isset($page) ? $page : 1
    ,   46
    ,   true
);

template('standing', $data);
?>
