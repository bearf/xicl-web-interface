<?php
require_once('./config/require.php');

if (!_settings_show_tournament_menu) { redirect(ServerRoot.'problemset.php'); }

//пытаемся достать контент из базы
//$r = $mysqli_->query('select `about` from `about`');
//if (!$r) { fail(_error_mysql_query_error_code); } // auto-close query
//if (0 == $r->num_rows) { fail(_error_no_content_for_page_found_code); } // auto-close query
//$f = $r->fetch_object();
//$r->close();

//data('content', $f->about);

template('index', $data);
?>
