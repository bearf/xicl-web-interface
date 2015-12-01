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
    _error_no_mysql_connection_code=>'Невозможно соединиться с базой данных',
    _error_no_sessions_available_code=>'Не работает механизм сессий'

    ,   _error_request_method_post_expected     => 'Ожидался POST-запрос'

    ,_error_authorization_required_code=>'Для просмотра этой страницы необходима авторизация',
    _error_no_current_contest_code=>'Эта страница доступна для просмотра только при наличии активного контеста',
    _error_no_problem_id_code=>'Не указан идентификатор задачи',
    _error_mysql_query_error_code=>'Произошла ошибка при выполнении запроса к базе данных',
    _error_no_task_found_code=>'В базе данных отсутствует запрашиваемая задача',
    _error_source_is_empty_code=>'Не задан исходный текст решения',
    _error_source_is_too_large_code=>'Размер исходного текста превышает допустимое ограничение',
    _error_no_task_id_code=>'Не указан идентификатор задачи', // todo: duplicate with _error_no_problem_id_code
    _error_no_question_body_code=>'Вы пытаетесь задать пустой вопрос',
    _error_incorrect_symbols_code=>'Введенные данные содержат некорректные символы',
    _error_no_print_id_code=>'Не указан идентификатор задания печати',
    _error_no_print_found_code=>'В базе данных отсутствует запрашиваемое задание печати',
    _error_no_question_id_code=>'Не указан идентификатор вопроса',
    _error_no_question_found_code=>'В базе данных отсутствует запрашиваемый вопрос',
    _error_no_submit_id_code=>'Не указан номер решения',
    _error_no_submit_found_code=>'В базе данных отсутствует запрашиваемое решение',
    _error_submit_source_is_empty_code=>'У запрашиваемого решения пустой исходный код',
    
    _error_no_user_found_code=>'В базе данных отсутствует запрашиваемый пользователь',
    _error_requested_contest_is_not_available_code=>'Запрашиваемый контест в настоящее время недоступен',
    _error_no_faq_id_code=>'Не указан идентификатор вопроса',
    _error_faq_answer_is_empty_code=>'Не указан ответ на вопрос',
    _error_no_faq_found_code=>'В базе данных отсутствует запрашиваемый вопрос',
    _error_no_content_for_page_found_code=>'В базе данных отсутствует контент для запрашиваемой страницы', 
    _error_no_permission_for_print=>'У вас нет прав на добавление задания в очередь печати',
    _error_no_problem_id_code=>'Не указан идентификатор задачи',
    _error_no_contest_found_code=>'В базе данных отсутствует запрашиваемый контест',
    _error_no_lang_found_code=>'В базе данных отсутствует запрашиваемый язык программирования',
    _error_no_user_id_code=>'Не задан идентификатор пользователя',
    _error_work_on_site_code=>'В настоящее время проводятся работы по обновлению сайта. Отборочный тур Турнира ICL 2010 будет запущен после их окончания. Предполагаемый срок - 15 февраля, 18.00',
    _error_no_team_id_code=>'Не указан идентификатор команды',
    _error_no_team_found_code=>'В базе данных отсутствует запрашиваемая команда',
    _error_team_name_is_required_code=>'При создании новой команды нужно указать ее имя',
    _error_cannot_create_team_code=>'Не удалось создать команду. Возможно, ее имя не уникально',
    _error_cannot_found_created_team_code=>'Критическая ошибка. Не удалось найти вновь созданную команду в базе',
    _error_team_with_such_name_already_exists_code=>'Команда с таким именем уже существует',
    _error_no_notify_id_code=>'Не указан идентификатор уведомления',
    _error_no_notify_found_code=>'В базе данных отсутствует запрашиваемое уведомление',
    _error_notify_is_empty_code=>'Вы пытаетесь отправить пустое уведомление',
    _error_notify_is_too_large_code=>'Размер текста уведомления превышает '._param_notify_max_length.' символов',
    _error_notify_header_is_empty_code=>'Не указан заголовок уведомления',
    _error_team_already_invited_code=>'Эта команда уже приглашена',
    _error_team_not_invited_code=>'Эта команда не получала приглашения',
    _error_you_have_no_team_code=>'Вы не можете отправить заявку, так как у Вас не указана команда',
    _error_your_team_is_not_invited_code=>'Вы не можете отправить заявку, так как Ваша команда не приглашена',
    _error_no_permission_for_mail=>'Отправка и чтение сообщений запрещены',
    _error_function_is_restricted=>'Указанная функция в настоящий момент недоступна',
    
    
    _error_500_code=>'<span class="important"><span class="strong">500</span> внутренняя ошибка сервера</span>',
    _error_404_code=>'<span class="important"><span class="strong">404</span> страница не найдена</span>',
    _error_403_code=>'<span class="important"><span class="strong">403</span> доступ запрещен</span>',
    _error_401_code=>'<span class="important"><span class="strong">401</span> необходима аутентификация</span>',
    _error_400_code=>'<span class="important"><span class="strong">400</span> bad request</span>',
    
    _success_print_added_code=>'Задание успешно добавлено в очередь печати',
    _success_question_added_code=>'Вопрос успешно добавлен',
    _success_print_cleared_code=>'Задание печати поставлено в очередь заново',
    _success_print_deleted_code=>'Задание печати успешно удалено',
    _success_question_deleted_code=>'Вопрос успешно удален',
    _success_question_edited_code=>'Ответ на вопрос успешно отредактирован',
    _success_question_set_public_code=>'Статус вопроса успешно изменен',
    _success_notify_added_code=>'Уведомление успешно отослано',
    _success_team_invited_code=>'Команда успешно приглашена',
    _success_team_declined_code=>'Приглашение успешно отменено',
    _success_order_sent=>'Ваша заявка успешно отправлена',

    _header_you_are_invited_code=>'Вы приглашены',
    _header_you_are_fired_code=>'Приглашение отменено',

    _message_you_are_invited_code=>'Ваша команда прошла в основной тур Турнира ICL-2012. В срок до 11 марта включительно отправьте подтверждение вашего участия <a href="mailto:ksf@icl.kazan.ru">Светлане Николаевой</a>. Также просим вас с 15 по 22 марта включительно воспользоваться <a href="./order.php">формой заявки</a> и отправить нам анкетные данные вашей команды',
    _message_you_are_fired_code=>'Нам очень жаль, но по некоторым причинам приглашение на основной тур Турнира ICL-2012 Вашей команде отменено. Свяжитесь с контактными лицами для выяснения подробностей'
    
    ,   _error_order_is_closed_for_reconstruction   =>  'Функция будет открыта с 15 по 22 марта 2012 года. Вы можете перейти на <a href="./">главную страницу</a> или <a href="./qualification.php#standing">страницу результатов отборочного тура</a>.'

    ,   _error_persinfo_already_exists              =>  'Ваши персональные данные уже созданы. Вы можете <a href="./changeinfo.php">изменить их</a>.'
    ,   _error_persinfo_has_been_not_created        =>  'Не удалось создать персональные данные. Попробуйте повторить попытку позднее'
    ,   _success_persinfo_has_been_created          =>  'Ваши персональные данные были успешно созданы. Вы можете изменить их.'
    ,   _error_persinfo_does_not_exists             =>  'Ваши персональные данные еще не созданы. Вы можете <a href="./changeinfo.php">создать их</a>.'
    ,   _error_persinfo_has_not_been_updated        =>  'Не удалось изменить персональные данные. Повторите попытку позднее.'
    ,   _success_persinfo_has_been_updated          =>  'Ваши персональные данные были успешно изменены.'
    ,   _error_persinfo_has_been_not_deleted        =>  'Не удалось удалить персональные данные. Попробуйте повторить попытку позднее.'
    ,   _success_persinfo_has_been_deleted          =>  'Ваши персональные данные были успешно удалены.'

    ,   _error_cannot_update_team_you_have_no_team  =>  'Вы не можете редактировать данные команды, поскольку не прикреплены ни к одной из команд.'
    ,   _error_team_has_not_been_updated            =>  'Не удалось обновить данные команды. Попробуйте повторить попытку позднее.'
    ,   _success_team_has_been_updated              =>  'Данные команды были успешно обновлены.'

    ,   _error_no_permission_to_view_team_info      =>  'У вас нет разрешений на просмотр информации о команде.'
    ,   _error_no_permission_to_update_team_info    =>  'У вас нет разрешений на изменение информации о команде.'

    ,   _error_submit_cannot_perform_on_other_division  => 'Вы не можете решать задачи из чужого дивизиона.'

);
?>
