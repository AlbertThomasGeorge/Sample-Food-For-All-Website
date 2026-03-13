<?php
    include "config.php";
    $sql1 = "SELECT * FROM max_X";
    $result1 = mysqli_query($conn, $sql1);
    if(mysqli_num_rows($result1) == 0){
        $sql2 = "INSERT INTO max_X (maximum_X_value) VALUES (0)";
        mysqli_query($conn, $sql2);
    }
    date_default_timezone_set("Asia/Kolkata");
    $date = date('Y-m-d');
    $sql3 = "SELECT * FROM needs";
    $result3 = mysqli_query($conn, $sql3);
    if(mysqli_num_rows($result3) == 0){
        $sql4 = "SELECT * FROM funds";
        $result4 = mysqli_query($conn, $sql4);
        if(mysqli_num_rows($result4) == 0){
            $sql5 = "INSERT INTO funds (X, Y) VALUES (0, 0)";
            mysqli_query($conn, $sql5);
        }
    }
?>