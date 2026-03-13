<?php
    session_start();
    if (!isset($_SESSION['delivery_person_name'])) {
        header("Location: http://localhost/Mini-Project/");
        die();
    }
?>