<?php
    include "../config.php";
    $sql1 = "SELECT * FROM funds";
    $result1 = mysqli_query($conn, $sql1);
    $row1 = mysqli_fetch_assoc($result1);
    $X = $row1['X'];
    $sql2 = "UPDATE max_X SET maximum_X_value = {$X}";
    mysqli_query($conn, $sql2);
    date_default_timezone_set("Asia/Kolkata");
    $date = date('Y-m-d');
    $sql3 = "SELECT * FROM funds_inconsistency_stopper WHERE click_date = '{$date}'";
    $result3 = mysqli_query($conn, $sql3);
    if($result3){
        if(mysqli_num_rows($result3) == 0){
            $sql4 = "INSERT INTO funds_inconsistency_stopper (click_date) VALUES ('{$date}')";
            mysqli_query($conn, $sql4);
            echo 1;
        }
    }
    mysqli_close($conn);
?>
