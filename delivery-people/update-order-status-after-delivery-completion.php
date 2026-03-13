<?php
    session_start();
    include "../config.php";
    $delivery_person_id = $_SESSION['delivery_person_id'];
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $user_name = mysqli_real_escape_string($conn, $_POST['username']);
    $sql = "UPDATE orders SET order_status = 'COMPLETED DELIVERY' WHERE order_id IN (SELECT o.order_id FROM orders o JOIN delivery_people_responsibilities d ON o.order_id = d.order_id JOIN users u ON u.user_id = o.user_id WHERE DATE(FROM_UNIXTIME(o.unix_timestamp DIV 1000)) = '{$date}' AND o.order_status != 'COMPLETED DELIVERY' AND u.user_name = '{$user_name}' AND d.delivery_person_id = {$delivery_person_id})";
    if(mysqli_query($conn, $sql)){
        echo 1;
    }
?>