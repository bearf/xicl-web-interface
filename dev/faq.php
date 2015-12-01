<?php
require_once('./config/require.php');

// функция закрыта
if (!_settings_show_tournament_menu) { fail(_error_function_is_restricted); }

$rowcount = 0;
$pagesize = 10;
if (!isset($question)) { $question = ''; };
if (!isset($page)) { $page = 1; };
$first = ($page-1)*$pagesize;
$r = $mysqli_->query('SELECT faqid, date, question, answer FROM faq ORDER BY faqid DESC');
if (!$r) { fail(_error_mysql_query_error_code); } // auto-close query
$faq = array(); $index = 0; 
$rowcount = $r->num_rows;
$pagecount = $rowcount / $pagesize;
if ($rowcount % $pagesize != 0) { $pagecount += 1; }
while ($f = $r->fetch_object()) { 
    $index++;
    if ($index > $first && $index < $first + $pagesize + 1) { array_push($faq, $f); }
}
$r->close();

data('faq', $faq);
data('pagecount', $pagecount);
data('page', $page);
data('question', $question);

template('faq', $data);
?>

