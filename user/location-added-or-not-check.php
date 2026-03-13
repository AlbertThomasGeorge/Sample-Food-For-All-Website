<?php
    session_start();
    include "../config.php";
    $username = mysqli_real_escape_string($conn, $_SESSION['user_name']);
    $sql = "SELECT * FROM users WHERE user_name = '{$username}' AND latitude IS NULL AND longitude IS NULL";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 0){
        echo "Edit Location to deliver <br>if not in restaurant";
    }
    else{
        echo "Add Location to deliver <br>if not in restaurant";
    }
?>