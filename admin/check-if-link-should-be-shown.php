<?php
    include "../config.php";
    date_default_timezone_set("Asia/Kolkata");
    $date = date('Y-m-d');
    $sql1 = "SELECT * FROM needs WHERE need_date = '{$date}'";
    $result1 = mysqli_query($conn, $sql1);
    $sql2 = "SELECT * FROM funds_inconsistency_stopper";
    $result2 = mysqli_query($conn, $sql2);
    if(mysqli_num_rows($result2) == 0){
        if(mysqli_num_rows($result1) == 0){
            echo "<div class='reestimate-funds-if-no-needs-today'><a class='reestimater-of-funds' href=''>Reestimate funds by clicking here if no needs exist today</a></div>";
        }
        else{
            echo 0;
        }
    }
    else{
        if(mysqli_num_rows($result1) == 0){
            $sql3 = "SELECT * FROM funds_inconsistency_stopper WHERE click_date = '{$date}'";
            $result3 = mysqli_query($conn, $sql3);
            if(mysqli_num_rows($result3) == 1){
                echo 0;
            }
            else{
                echo "<div class='reestimate-funds-if-no-needs-today'><a class='reestimater-of-funds' href=''>Reestimate funds by clicking here if no needs exist today</a></div>";
            }
        }
        else{
            echo 0;
        }
    }
    mysqli_close($conn);
?>