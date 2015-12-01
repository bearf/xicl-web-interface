<?php
define('_error_no_mysql_connection_code', 1000);
define('_error_no_sessions_available_code', 1002);
define('_error_request_method_post_expected', 1003);

define('_error_authorization_required_code', 3007);
define('_error_no_current_contest_code', 3018);
define('_error_no_problem_id_code', 3014);
define('_error_mysql_query_error_code', 3002);
define('_error_no_task_found_code', 3001);
define('_error_source_is_empty_code', 3015);
define('_error_source_is_too_large_code', 3016);
define('_error_no_task_id_code', 3003);
define('_error_no_question_body_code', 3004);
define('_error_incorrect_symbols_code', 3005);
define('_error_no_print_id_code', 3019);
define('_error_no_print_found_code', 3020);
define('_error_no_question_id_code', 3008);
define('_error_no_question_found_code', 3009);

define('_error_no_user_found_code', 3023);
define('_error_requested_contest_is_not_available_code', 3024);
define('_error_no_faq_id_code', 3025);
define('_error_faq_answer_is_empty_code', 3026);
define('_error_no_faq_found_code', 3027);
define('_error_no_content_for_page_found_code', 3028);
define('_error_no_permission_for_print', 3029);
define('_error_no_problem_id_code', 3030);
define('_error_no_contest_found_code', 3031);
define('_error_no_lang_found_code', 3032);
define('_error_no_user_id_code', 3033);
define('_error_work_on_site_code', 3034);
define('_error_no_team_id_code', 3040);
define('_error_no_team_found_code', 3041);
define('_error_team_name_is_required_code', 3042);
define('_error_cannot_create_team_code', 3043);
define('_error_cannot_found_created_team_code', 3044);
define('_error_team_with_such_name_already_exists_code', 3045);
define('_error_no_notify_id_code', 3046);
define('_error_no_notify_found_code', 3047);
define('_error_notify_is_empty_code', 3048);
define('_error_notify_is_too_large_code', 3049);
define('_error_notify_header_is_empty_code', 3050);
define('_error_team_already_invited_code', 3051);
define('_error_team_not_invited_code', 3052);
define('_error_you_have_no_team_code', 3053);
define('_error_your_team_is_not_invited_code', 3054);
define('_error_no_permission_for_mail', 3055);
define('_error_function_is_restricted', 3056);
define('_error_no_submit_id_code', 3057);
define('_error_no_submit_found_code', 3058);
define('_error_order_is_closed_for_reconstruction', 3059);

define('_error_persinfo_already_exists', 3060);
define('_error_persinfo_has_been_not_created', 3061);
define('_error_persinfo_does_not_exists', 3062);
define('_error_persinfo_has_not_been_updated', 3063);
define('_error_persinfo_has_been_not_deleted', 3064);

define('_success_persinfo_has_been_created', 3100);
define('_success_persinfo_has_been_updated', 3101);
define('_success_persinfo_has_been_deleted', 3102);

define('_error_cannot_update_team_you_have_no_team', 3201);
define('_error_team_has_not_been_updated', 3202);
define('_error_no_permission_to_view_team_info', 3203);
define('_error_no_permission_to_update_team_info', 3204);

define('_success_team_has_been_updated', 3251);

define('_error_500_code', 3035);
define('_error_404_code', 3036);
define('_error_403_code', 3037);
define('_error_401_code', 3038);
define('_error_400_code', 3039);

define('_success_print_added_code', 4000);
define('_success_question_added_code', 4001);
define('_success_print_cleared_code', 4002);
define('_success_print_deleted_code', 4003);
define('_success_question_deleted_code', 4004);
define('_success_question_edited_code', 4005);
define('_success_question_set_public_code', 4006);
define('_success_notify_added_code', 4007);
define('_success_team_invited_code', 4008);
define('_success_team_declined_code', 4009);
define('_success_order_sent', 4010);

define('_header_you_are_invited_code', 5000);
define('_header_you_are_fired_code', 5001);

define('_message_you_are_invited_code', 6000);
define('_message_you_are_fired_code', 6001);

define('_error_submit_cannot_perform_on_other_division', 6100);

$messages = array(
    _error_no_mysql_connection_code=>'���������� ����������� � ����� ������',
    _error_no_sessions_available_code=>'�� �������� �������� ������'

    ,   _error_request_method_post_expected     => '�������� POST-������'

    ,_error_authorization_required_code=>'��� ��������� ���� �������� ���������� �����������',
    _error_no_current_contest_code=>'��� �������� �������� ��� ��������� ������ ��� ������� ��������� ��������',
    _error_no_problem_id_code=>'�� ������ ������������� ������',
    _error_mysql_query_error_code=>'��������� ������ ��� ���������� ������� � ���� ������',
    _error_no_task_found_code=>'� ���� ������ ����������� ������������� ������',
    _error_source_is_empty_code=>'�� ����� �������� ����� �������',
    _error_source_is_too_large_code=>'������ ��������� ������ ��������� ���������� �����������',
    _error_no_task_id_code=>'�� ������ ������������� ������', // todo: duplicate with _error_no_problem_id_code
    _error_no_question_body_code=>'�� ��������� ������ ������ ������',
    _error_incorrect_symbols_code=>'��������� ������ �������� ������������ �������',
    _error_no_print_id_code=>'�� ������ ������������� ������� ������',
    _error_no_print_found_code=>'� ���� ������ ����������� ������������� ������� ������',
    _error_no_question_id_code=>'�� ������ ������������� �������',
    _error_no_question_found_code=>'� ���� ������ ����������� ������������� ������',
    _error_no_submit_id_code=>'�� ������ ����� �������',
    _error_no_submit_found_code=>'� ���� ������ ����������� ������������� �������',
    _error_submit_source_is_empty_code=>'� �������������� ������� ������ �������� ���',
    
    _error_no_user_found_code=>'� ���� ������ ����������� ������������� ������������',
    _error_requested_contest_is_not_available_code=>'������������� ������� � ��������� ����� ����������',
    _error_no_faq_id_code=>'�� ������ ������������� �������',
    _error_faq_answer_is_empty_code=>'�� ������ ����� �� ������',
    _error_no_faq_found_code=>'� ���� ������ ����������� ������������� ������',
    _error_no_content_for_page_found_code=>'� ���� ������ ����������� ������� ��� ������������� ��������', 
    _error_no_permission_for_print=>'� ��� ��� ���� �� ���������� ������� � ������� ������',
    _error_no_problem_id_code=>'�� ������ ������������� ������',
    _error_no_contest_found_code=>'� ���� ������ ����������� ������������� �������',
    _error_no_lang_found_code=>'� ���� ������ ����������� ������������� ���� ����������������',
    _error_no_user_id_code=>'�� ����� ������������� ������������',
    _error_work_on_site_code=>'� ��������� ����� ���������� ������ �� ���������� �����. ���������� ��� ������� ICL 2010 ����� ������� ����� �� ���������. �������������� ���� - 15 �������, 18.00',
    _error_no_team_id_code=>'�� ������ ������������� �������',
    _error_no_team_found_code=>'� ���� ������ ����������� ������������� �������',
    _error_team_name_is_required_code=>'��� �������� ����� ������� ����� ������� �� ���',
    _error_cannot_create_team_code=>'�� ������� ������� �������. ��������, �� ��� �� ���������',
    _error_cannot_found_created_team_code=>'����������� ������. �� ������� ����� ����� ��������� ������� � ����',
    _error_team_with_such_name_already_exists_code=>'������� � ����� ������ ��� ����������',
    _error_no_notify_id_code=>'�� ������ ������������� �����������',
    _error_no_notify_found_code=>'� ���� ������ ����������� ������������� �����������',
    _error_notify_is_empty_code=>'�� ��������� ��������� ������ �����������',
    _error_notify_is_too_large_code=>'������ ������ ����������� ��������� '._param_notify_max_length.' ��������',
    _error_notify_header_is_empty_code=>'�� ������ ��������� �����������',
    _error_team_already_invited_code=>'��� ������� ��� ����������',
    _error_team_not_invited_code=>'��� ������� �� �������� �����������',
    _error_you_have_no_team_code=>'�� �� ������ ��������� ������, ��� ��� � ��� �� ������� �������',
    _error_your_team_is_not_invited_code=>'�� �� ������ ��������� ������, ��� ��� ���� ������� �� ����������',
    _error_no_permission_for_mail=>'�������� � ������ ��������� ���������',
    _error_function_is_restricted=>'��������� ������� � ��������� ������ ����������',
    
    
    _error_500_code=>'<span class="important"><span class="strong">500</span> ���������� ������ �������</span>',
    _error_404_code=>'<span class="important"><span class="strong">404</span> �������� �� �������</span>',
    _error_403_code=>'<span class="important"><span class="strong">403</span> ������ ��������</span>',
    _error_401_code=>'<span class="important"><span class="strong">401</span> ���������� ��������������</span>',
    _error_400_code=>'<span class="important"><span class="strong">400</span> bad request</span>',
    
    _success_print_added_code=>'������� ������� ��������� � ������� ������',
    _success_question_added_code=>'������ ������� ��������',
    _success_print_cleared_code=>'������� ������ ���������� � ������� ������',
    _success_print_deleted_code=>'������� ������ ������� �������',
    _success_question_deleted_code=>'������ ������� ������',
    _success_question_edited_code=>'����� �� ������ ������� ��������������',
    _success_question_set_public_code=>'������ ������� ������� �������',
    _success_notify_added_code=>'����������� ������� ��������',
    _success_team_invited_code=>'������� ������� ����������',
    _success_team_declined_code=>'����������� ������� ��������',
    _success_order_sent=>'���� ������ ������� ����������',

    _header_you_are_invited_code=>'�� ����������',
    _header_you_are_fired_code=>'����������� ��������',

    _message_you_are_invited_code=>'���� ������� ������ � �������� ��� ������� ICL-2012. � ���� �� 11 ����� ������������ ��������� ������������� ������ ������� <a href="mailto:ksf@icl.kazan.ru">�������� ����������</a>. ����� ������ ��� � 15 �� 22 ����� ������������ ��������������� <a href="./order.php">������ ������</a> � ��������� ��� �������� ������ ����� �������',
    _message_you_are_fired_code=>'��� ����� ����, �� �� ��������� �������� ����������� �� �������� ��� ������� ICL-2012 ����� ������� ��������. ��������� � ����������� ������ ��� ��������� ������������'
    
    ,   _error_order_is_closed_for_reconstruction   =>  '������� ����� ������� � 15 �� 22 ����� 2012 ����. �� ������ ������� �� <a href="./">������� ��������</a> ��� <a href="./qualification.php#standing">�������� ����������� ����������� ����</a>.'

    ,   _error_persinfo_already_exists              =>  '���� ������������ ������ ��� �������. �� ������ <a href="./changeinfo.php">�������� ��</a>.'
    ,   _error_persinfo_has_been_not_created        =>  '�� ������� ������� ������������ ������. ���������� ��������� ������� �������'
    ,   _success_persinfo_has_been_created          =>  '���� ������������ ������ ���� ������� �������. �� ������ �������� ��.'
    ,   _error_persinfo_does_not_exists             =>  '���� ������������ ������ ��� �� �������. �� ������ <a href="./changeinfo.php">������� ��</a>.'
    ,   _error_persinfo_has_not_been_updated        =>  '�� ������� �������� ������������ ������. ��������� ������� �������.'
    ,   _success_persinfo_has_been_updated          =>  '���� ������������ ������ ���� ������� ��������.'
    ,   _error_persinfo_has_been_not_deleted        =>  '�� ������� ������� ������������ ������. ���������� ��������� ������� �������.'
    ,   _success_persinfo_has_been_deleted          =>  '���� ������������ ������ ���� ������� �������.'

    ,   _error_cannot_update_team_you_have_no_team  =>  '�� �� ������ ������������� ������ �������, ��������� �� ����������� �� � ����� �� ������.'
    ,   _error_team_has_not_been_updated            =>  '�� ������� �������� ������ �������. ���������� ��������� ������� �������.'
    ,   _success_team_has_been_updated              =>  '������ ������� ���� ������� ���������.'

    ,   _error_no_permission_to_view_team_info      =>  '� ��� ��� ���������� �� �������� ���������� � �������.'
    ,   _error_no_permission_to_update_team_info    =>  '� ��� ��� ���������� �� ��������� ���������� � �������.'

    ,   _error_submit_cannot_perform_on_other_division  => '�� �� ������ ������ ������ �� ������ ���������.'

);
?>
