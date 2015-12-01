<?php
require_once('./config/require.php');

// функция закрыта
if (!_settings_show_tournament_menu) { fail(_error_function_is_restricted); }

//пытаемся достать контент из базы
//$r = $mysqli_->query('select `rules` from `rules`');
//if (!$r) { fail(_error_mysql_query_error_code); } // auto-close query
//if (0 == $r->num_rows) { fail(_error_no_content_for_page_found_code); } // auto-close query
//$f = $r->fetch_object();
//$r->close();

//data('content', $f->rules);

template('rules', $data);
?>
