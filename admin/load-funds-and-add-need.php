<?php
    include "../config.php";
    $personorgroupname = mysqli_real_escape_string($conn, $_POST['person-or-group-name']);
    $personorgroupneed = mysqli_real_escape_string($conn, $_POST['person-or-group-need']);
    $currentdate = mysqli_real_escape_string($conn, $_POST['current-date']);
    $estimate = mysqli_real_escape_string($conn, $_POST['estimate']);
    $sql = "INSERT INTO needs (person_or_group_name, person_or_group_need, need_date, estimate) VALUES ('{$personorgroupname}', '{$personorgroupneed}', '{$currentdate}', '{$estimate}')";
    if(mysqli_query($conn, $sql)){
        echo "Need Added successfully 😃. Add more if there";
    }
    else{
        echo "Need not added successfully 🥺";
    }
    $sql_query = "SELECT * FROM funds";
    $query_result = mysqli_query($conn, $sql_query);
    if(mysqli_num_rows($query_result) == 1){
        $sql1 = "SELECT estimate FROM needs WHERE need_date = '{$currentdate}' AND person_or_group_name = '{$personorgroupname}' AND person_or_group_need = '{$personorgroupneed}'";
        $result1 = mysqli_query($conn, $sql1);
        if($result1){
            $row1 = mysqli_fetch_assoc($result1);
            $need_estimate = mysqli_real_escape_string($conn, $row1['estimate']);
        }
        $sql2 =  "SELECT * FROM funds";
        $result2 = mysqli_query($conn, $sql2);
        if($result2){
            $row2 = mysqli_fetch_assoc($result2);
            $X = mysqli_real_escape_string($conn, $row2['X']);
            $new_X = $X + $need_estimate;
        }
        $sql3 = "UPDATE max_X SET maximum_X_value = {$new_X}";
        $result3 = mysqli_query($conn, $sql3);
        if($result3){
            $sql4 = "UPDATE funds SET X={$new_X}, Y=0";
            mysqli_query($conn, $sql4);
        }
    }
?>