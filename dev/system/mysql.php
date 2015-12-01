<?php
//fail(_error_work_on_site_code);
global $mysqli_; 
// todo: persistent connections
$mysqli_ = new mysqli(HostName, UserName, Password, DBName);
if (mysqli_connect_errno()) { die(mysqli_error());fail(_error_no_mysql_connection_code); }
$mysqli_->query( 'set names cp1251' );
?>
