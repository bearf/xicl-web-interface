<?php
// установить функцию error_handler как обработчик ошибок по умолчанию
set_error_handler('error_handler', E_ALL);
//функци€ обработки ошибок
function error_handler($errNo, $errStr, $errFile, $errLine) {
    // удалить выходные данные, которые уже были созданы
    if (ob_get_length()) { ob_clean(); }
    // вывести сообщение об ошибке
    fail('¬нутренн€€ ошибка сценари€:<br /> file: '.$errFile.'<br />line: '.$errLine.'<br />msg: '.$errStr);
}
?>
