<?php
    session_start();
    if (!isset($_SESSION['admin_name'])) {
        header("Location: http://localhost/Mini-Project/");
        die();
    }
?>