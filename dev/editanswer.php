<?php
require_once('./config/require.php');

// ������� �������
if (!_settings_show_tournament_menu) { fail(_error_function_is_restricted); }
if (1 != $is_admin) { authorize(); }
if (!isset($faqid)) { fail(_error_no_faq_id_code); }

// ���-�� ��������
if (isset($submit)):
    // ������ isset, answer ����� ���� ������ �������
    if (!isset($answer)) { fail(_error_faq_answer_is_empty_code); }
    $q = $mysqli_->prepare('update faq set answer=? where faqid=?');
    $q->bind_param('si', stripslashes($answer), $faqid);    
    // todo: ch��k ��� ������, ����� ������ ��������, �� ��������� ��������� ��� (����� �� ��� ������� � �����)
    if (!$q->execute() || 1 != $q->affected_rows) { fail(_error_mysql_query_error_code); } // auto-close query
    $q->close(); data('message', '����� ��� ������� �������');
//����������� ������
else:
    $q = $mysqli_->prepare('SELECT faqid, question, answer FROM faq WHERE faqid=?');
    $q->bind_param('i', $faqid);
    $q->bind_result($faqid, $question, $answer);
    if (!$q->execute()) { fail(_error_mysql_query_error_code); } // auto-close query
    if (!$q->fetch()) { fail(_error_no_faq_found_code); } // auto-close query
    $q->close();
endif; //����� �������� ����, ��� ����� ��������

data('faqid', $faqid);
data('question', $question);
data('answer', $answer);

template('editanswer', $data);
?>
