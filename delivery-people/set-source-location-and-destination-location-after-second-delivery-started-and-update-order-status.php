<?php
    session_start();
    include "../config.php";
    $delivery_person_id = $_SESSION['delivery_person_id'];
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $user_name = mysqli_real_escape_string($conn, $_POST['username']);
    $latitude_to_deliver = mysqli_real_escape_string($conn, $_POST['latitudetodeliver']);
    $longitude_to_deliver = mysqli_real_escape_string($conn, $_POST['longitudetodeliver']);
    $sql1 = "SELECT * FROM location_tracker WHERE delivery_person_id = {$delivery_person_id}";
    $result1 = mysqli_query($conn, $sql1);
    if(mysqli_num_rows($result1) == 1){
        $row1 = mysqli_fetch_assoc($result1);
        $latitude_to_start_delivery_from = $row1['latitude_of_destination'];
        $longitude_to_start_delivery_from = $row1['longitude_of_destination'];
        $sql2 = "UPDATE location_tracker SET latitude_of_source = {$latitude_to_start_delivery_from}, longitude_of_source = {$longitude_to_start_delivery_from}, latitude_of_destination = {$latitude_to_deliver}, longitude_of_destination = {$longitude_to_deliver}  WHERE  delivery_person_id={$delivery_person_id}";
        if(mysqli_query($conn, $sql2)){
            $sql3 = "UPDATE orders SET order_status = 'STARTED DELIVERY PROCESS' 
                     WHERE order_id IN (SELECT o.order_id FROM orders o JOIN delivery_people_responsibilities d ON o.order_id = d.order_id JOIN users u ON u.user_id = o.user_id WHERE DATE(FROM_UNIXTIME(o.unix_timestamp DIV 1000)) = '{$date}' AND o.order_status != 'COMPLETED DELIVERY' AND u.user_name = '{$user_name}' AND d.delivery_person_id = {$delivery_person_id})";
            mysqli_query($conn, $sql3);
            echo 1;
        }
    }
?>