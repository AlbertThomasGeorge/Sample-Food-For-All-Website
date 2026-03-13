<?php
    session_start();
    include "../config.php";
    $delivery_person_id = $_SESSION['delivery_person_id'];
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $sql1 = "SELECT o.order_status, u.user_name, o.latitude_to_deliver, o.longitude_to_deliver, COUNT(o.order_id) as total_orders 
             FROM orders o JOIN delivery_people_responsibilities d ON o.order_id = d.order_id JOIN users u ON u.user_id = o.user_id 
             WHERE DATE(FROM_UNIXTIME(o.unix_timestamp DIV 1000)) = '{$date}' AND d.delivery_person_id = {$delivery_person_id}
             GROUP BY o.latitude_to_deliver, o.longitude_to_deliver, u.user_name, o.order_status";
    $result1 = mysqli_query($conn, $sql1);
    if(mysqli_num_rows($result1) > 0){
        $output1 = mysqli_fetch_all($result1, MYSQLI_ASSOC);
        $sql2 = "SELECT * FROM location_tracker WHERE delivery_person_id = {$delivery_person_id}";
        $result2 = mysqli_query($conn, $sql2);
        if(mysqli_num_rows($result2) == 1){
            $output2 = mysqli_fetch_all($result2, MYSQLI_ASSOC);
            $response['orders'] = $output1;
            $response['location_tracker'] = $output2; 
        }
        else if(mysqli_num_rows($result2) == 0){
            $sql3 = "SELECT * FROM location_of_restaurant";
            $result3 = mysqli_query($conn, $sql3);
            if($result3){
                $row3 = mysqli_fetch_assoc($result3);
                $latitude_of_restaurant = $row3['latitude'];
                $longitude_of_restaurant = $row3['longitude'];
                $sql4 = "INSERT INTO location_tracker (delivery_person_id, latitude_of_source, longitude_of_source) VALUES ({$delivery_person_id}, {$latitude_of_restaurant}, {$longitude_of_restaurant})";
                mysqli_query($conn, $sql4);
                $sql5 = "SELECT * FROM location_tracker WHERE delivery_person_id = {$delivery_person_id}";
                $result5 = mysqli_query($conn, $sql5);
                $output2 = mysqli_fetch_all($result5, MYSQLI_ASSOC);
                $response['orders'] = $output1;
                $response['location_tracker'] = $output2; 
            }
        }
        $sql6 = "SELECT * FROM location_of_restaurant";
        $result6 = mysqli_query($conn, $sql6);
        if($result6){
            $output3 = mysqli_fetch_all($result6, MYSQLI_ASSOC);
            $response['location_of_restaurant'] = $output3;
        }
        echo json_encode($response);
    }
    else{
        echo json_encode(['message' => 'no record found', 'status' => false]);
    }
?>