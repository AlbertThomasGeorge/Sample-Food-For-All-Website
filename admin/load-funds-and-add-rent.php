<?php
    include "../config.php";
    $rent_for_today = mysqli_real_escape_string($conn, $_POST['rent-for-today-if-there']);
    $current_date = mysqli_real_escape_string($conn, $_POST['current-date']);
    $sql = "INSERT INTO rent (rent_per_day, date_of_rent) VALUES ({$rent_for_today}, '{$current_date}')";
    mysqli_query($conn, $sql);
    $sql_query = "SELECT * FROM funds";
    $query_result = mysqli_query($conn, $sql_query);
    if(mysqli_num_rows($query_result) == 1){
        $sql1 = "SELECT rent_per_day FROM rent WHERE date_of_rent = '{$current_date}'";
        $result1 = mysqli_query($conn, $sql1);
        if($result1){
            if(mysqli_num_rows($result1) == 1){
                $row1 = mysqli_fetch_assoc($result1);
                $rent_for_current_date = $row1['rent_per_day'];
                $sql2 =  "SELECT * FROM funds";
                $result2 = mysqli_query($conn, $sql2);
                if($result2){
                    $row2 = mysqli_fetch_assoc($result2);
                    $X = mysqli_real_escape_string($conn, $row2['X']);
                    $new_X = $X + $rent_for_current_date;
                }
                $sql3 = "UPDATE max_X SET maximum_X_value = {$new_X}";
                $result3 = mysqli_query($conn, $sql3);
                if($result3){
                    $sql4 = "UPDATE funds SET X={$new_X}, Y=0";
                    mysqli_query($conn, $sql4);
                }
            }
        }
    }
?>