<?php
// ���������� ������� error_handler ��� ���������� ������ �� ���������
set_error_handler('error_handler', E_ALL);
//������� ��������� ������
function error_handler($errNo, $errStr, $errFile, $errLine) {
    // ������� �������� ������, ������� ��� ���� �������
    if (ob_get_length()) { ob_clean(); }
    // ������� ��������� �� ������
    fail('���������� ������ ��������:<br /> file: '.$errFile.'<br />line: '.$errLine.'<br />msg: '.$errStr);
}
?>
