<?php
    session_start();
    if (!isset($_SESSION['restaurant_staff_name'])) {
        header("Location: http://localhost/Mini-Project/");
        die();
    }
?>