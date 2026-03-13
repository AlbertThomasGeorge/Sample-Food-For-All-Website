<?php
    session_start();
    include "../config.php";
    $delivery_person_id = $_SESSION['delivery_person_id'];
    $sql1 = "SELECT * FROM location_tracker WHERE delivery_person_id = {$delivery_person_id}";
    $result1 = mysqli_query($conn, $sql1);
    if(mysqli_num_rows($result1) == 1){
        $sql2 = "SELECT * FROM location_of_restaurant";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
        $latitude_of_restaurant = $row2['latitude'];
        $longitude_of_restaurant = $row2['longitude'];
        $sql3 = "UPDATE location_tracker SET latitude_of_source = {$latitude_of_restaurant}, longitude_of_source = {$longitude_of_restaurant}, latitude_of_destination = NULL, longitude_of_destination = NULL WHERE delivery_person_id = {$delivery_person_id}";
        if(mysqli_query($conn, $sql3)){
             echo 1;
        }
        else{
            echo 0;
        }  
    }
?>