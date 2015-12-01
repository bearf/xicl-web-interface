<?php 
function redirect($URL_with_params) { 
    // удалить выходные данные, которые уже были созданы
    if (ob_get_length()) { ob_clean(); }
    header('Location: '.$URL_with_params);
    die(); // stop script to prevent output
} 
?>
