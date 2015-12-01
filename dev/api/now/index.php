<?php
require_once dirname(__FILE__) . '/../../config/require.php';

Header('Content-Type: application/json; charset=cp1251');

if (isset($_GET['callback'])) echo $_GET['callback'] . '(';
	echo Butler::getDBFacade()->now();
if (isset($_GET['callback'])) echo ')';
