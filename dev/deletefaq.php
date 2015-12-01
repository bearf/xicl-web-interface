<?php
//todo: ������ �� �����
require_once('./config/require.php');

// ������� �������
if (!_settings_show_tournament_menu) { fail(_error_function_is_restricted); }
//������������ �� �����������
if (1 != $is_admin) { authorize(); }
if (!isset($faqid)) { fail(_error_no_faq_id_code); }

//������ ���
$q = $mysqli_->prepare('delete from faq where faqid=?');
$q->bind_param('i', $faqid);
if (!$q->execute() || 1 != $q->affected_rows) { fail(_error_mysql_query_error_code); } // auto-close query
$q->close();
    
data('message', '������ ��� ������� ������');
template('message', $data);
?>

