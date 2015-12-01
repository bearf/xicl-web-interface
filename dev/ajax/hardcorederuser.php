<?php
    session_start();
    if (isset($_GET['leftuser'])) {
        $_SESSION['leftuser'] = $_GET['leftuser'];
    }
    if (isset($_GET['rightuser'])) {
        $_SESSION['rightuser'] = $_GET['rightuser'];
    }
?>{ result:true }