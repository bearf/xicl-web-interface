<?php 
function redirect($URL_with_params) { 
    // ������� �������� ������, ������� ��� ���� �������
    if (ob_get_length()) { ob_clean(); }
    header('Location: '.$URL_with_params);
    die(); // stop script to prevent output
} 
?>
