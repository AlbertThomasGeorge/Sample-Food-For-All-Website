<?php
    include "../config.php";
    $sql = "SELECT * FROM funds";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);
        $X = mysqli_real_escape_string($conn, $row['X']);
        echo $X;
    }
    else{
        echo 0;
    }
?>  