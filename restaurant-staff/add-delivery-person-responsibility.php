<?php
    include "../config.php";
    if(isset($_GET['order_id']) && isset($_GET['delivery_person_id'])){
        $order_id = mysqli_real_escape_string($conn, $_GET['order_id']);
        $delivery_person_id = mysqli_real_escape_string($conn, $_GET['delivery_person_id']);
        $sql1 = "INSERT INTO delivery_people_responsibilities (order_id, delivery_person_id) VALUES ($order_id, $delivery_person_id)";
        if(mysqli_query($conn, $sql1)){
            $sql2 = "UPDATE orders SET order_status = 'ASSIGNED DELIVERER' WHERE order_id = {$order_id}";
            if(mysqli_query($conn, $sql2)){
                echo 1;
            }
            else{
                echo 0;
            }
        }
        else{
            echo 0;
        }
    }
    else{
        echo 0;
    }
?>