<?php
    include "../config.php";
    $sql = "SELECT delivery_person_id, delivery_person_name FROM delivery_people";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode($output);
    }
    else{
        echo json_encode(["message" => "No Record Found", "status" => false]);
    }
?>