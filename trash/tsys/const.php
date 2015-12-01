<?php

  /*define("DBName","contest");
  define("HostName","194.135.83.90"); //194.135.83.90
  define("UserName","contest");
  define("ServerRoot", "http://www.icl.ru/turnir/contest/");
  define("Password","contest");*/

  /*define("DBName","contest");
  define("HostName","localhost"); //194.135.83.90
  define("UserName","root");
  define("ServerRoot", "http://localhost/turnir/contest/");
  define("Password","wordplay");*/

  define("DBName","contest");
  //define("HostName","192.168.16.203"); //194.135.83.90
  define("HostName","194.135.83.90"); //194.135.83.90
  define("UserName","contest");
  //define("ServerRoot", "http://localhost/turnir/contest/");
  define("ServerRoot", "http://localhost/turnir/contest/");
  define("Password","contest");

  /*define("DBName","contest2");
  define("HostName","localhost"); //194.135.83.90
  define("UserName","contest");
  define("ServerRoot", "http://localhost/turnir/contest/");
  //define("ServerRoot", "http://www.icl.ru/turnir/contest/");
  define("Password","contestpass");*/
  
  /*define("DBName","contest_sand");
  define("HostName","localhost"); //194.135.83.90
  define("UserName","contest");
  define("ServerRoot", "http://www.icl.ru/turnir/contest/");
  define("Password","contestpass");*/

  define('_system_site_title', 'Турнир ICL-2008');

  define("IsLoginNeed", 1);
  define('_permission_is_user_data_secure', true);
  define('_permission_allow_register_new_user', false);
  define('_permission_allow_change_info', false);
  define('_permission_allow_print', true);
  define('_permission_allow_view_news', false);
  
  define('_settings_show_main_site_url', false);
  define('_settings_show_time_left', true);
  define('_settings_show_monitor_comment', false);
  define('_settings_show_submit_info', false);
  
  define('_invalid_params', 'Параметры запроса содержат некорректную информацию.');
  define('_no_task_found', 'Не найдена задача в базе.');
  define('_mysql_error', 'Произошла ошибка при выполнении запроса. Попробуйте повторить попытку позже.');
  define('_no_task_id', 'Не указан идентификатор задачи.');
  define('_no_question', 'Не указан вопрос.');
  define('_incorrect_symbols', 'Вопрос содержит некорректные символы.');
  define('_question_added', 'Вопрос успешно добавлен.');
  define('_authorization_need', 'Необходима авторизация.');
  define('_no_question_id', 'Не найден идентификатор вопроса.');
  define('_no_question_found', 'Не найден вопрос в базе.');
  define('_question_edited', 'Вопрос успешно отредактирован.');
  define('_question_set_public', 'Статус вопроса успешно изменен.');
  define('_question_deleted', 'Вопрос успешно удален.');
  define('_no_task_list_found', 'Не найден список задач в базе.');
  define('_no_problem_id', 'Не указан идентификатор задачи.');
  define('_no_source', 'Не задан исходный текст решения');
  define('_source_too_large', 'Размер исходного текста превышает 64 Кб.');
  define('_source_added', 'Исходный текст успешно отправлен на печать.');
  define('_no_current_contest', 'Текущий турнир не установлен.');
  define('_no_print_id', 'Не задан идентификатор задания печати.');
  define('_no_print_found', 'Не найдено задание печати в базе.');
  define('_print_deleted', 'Задание печати успешно удалено.');
  define('_print_cleared', 'Задание печати отправлено заново.');

  define("_error_no_mysql_connection", '<p>Не удалось установить соединение с базой данных. Это могло произойти по одной из следующих причин:</p>
          <ul>
            <li>Сайт в настоящее время перегружен.</li>
            <li>В настоящее время производится обновление базы данных и/или сайта.</li>
          </ul>
          <p>Пожалуйста, повторите попытку запроса позже.</p>');
  define('_error_no_contest_info_loaded', '<p>Не удалось извлечь из базы данных информацию о текущем турнире.</p>');
  define("_error_no_sessions_available", '<p>Некорректно работает механизм сессий. Пожалуйста, проверьте, включена ли в вашем браузере поддержка cookies.</p>');
  define("_warning_need_login", '<p>Для просмотра этой информации вам необходимо <a href="./index.php">войти в систему</a>.</p>');
  define('_warning_no_rights_for_this_operation', 'Недостаточно прав для выполнения данной операции.');
  
  define('_invalid_params_code', 3000);
  define('_no_task_found_code', 3001);
  define('_mysql_error_code', 3002);
  define('_no_task_id_code', 3003);
  define('_no_question_code', 3004);
  define('_incorrect_symbols_code', 3005);
  define('_question_added_code', 3006);
  define('_authorization_need_code', 3007);
  define('_no_question_id_code', 3008);
  define('_no_question_found_code', 3009);
  define('_question_edited_code', 3010);
  define('_question_set_public_code', 3011);
  define('_question_deleted_code', 3012);
  define('_no_task_list_found_code', 3013);
  define('_no_problem_id_code', 3014);
  define('_no_source_code', 3015);
  define('_source_too_large_code', 3016);
  define('_source_added_code', 3017);
  define('_no_current_contest_code', 3018);
  define('_no_print_id_code', 3019);
  define('_no_print_found_code', 3020);
  define('_print_deleted_code', 3021);
  define('_print_cleared_code', 3022);
  
  define("error_no_mysql_connection_code", 1000);
  define("error_no_contest_info_loaded_code", 1001);
  define("error_no_sessions_available_code", 1002);
  define("warning_need_login_code", 1003);
  define('warning_no_rights_for_this_operation_code', 1004);

  $messages = array(
    _invalid_params_code=>_invalid_params,
    _no_task_found_code=>_no_task_found,
    _mysql_error_code=>_mysql_error,
    _no_task_id_code=>_no_task_id,
    _no_question_code=>_no_question,
    _incorrect_symbols_code=>_incorrect_symbols,
    _question_added_code=>_question_added,
    _authorization_need_code=>_authorization_need,
    _no_question_id_code=>_no_question_id,
    _no_question_found_code=>_no_question_found,
    _question_edited_code=>_question_edited,
    _question_set_public_code=>_question_set_public,
    _question_deleted_code=>_question_deleted,
    _no_task_list_found_code=>_no_task_list_found,
    _no_problem_id_code=>_no_problem_id,
    _no_source_code=>_no_source,
    _source_too_large_code=>_source_too_large,
    _source_added_code=>_source_added,
    _no_current_contest_code=>_no_current_contest,
    _no_print_id_code=>_no_print_id,
    _no_print_found_code=>_no_print_found,
    _print_deleted_code=>_print_deleted,
    _print_cleared_code=>_print_cleared,
    
    error_no_mysql_connection_code=>_error_no_mysql_connection,
    error_no_contest_info_loaded_code=>_error_no_contest_info_loaded,
    error_no_sessions_available_code=>_error_no_sessions_available,
    warning_need_login_code=>_warning_need_login,
    warning_no_rights_for_this_operation_code=>_warning_no_rights_for_this_operation
  );
  
  define("antiSpamTimeOut", 1);


?>
