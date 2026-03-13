<?php
    include "../config.php";
    $restaurantstaffid = $_GET['restaurantstaffid'];
    $sql = "DELETE FROM restaurant_staff WHERE restaurant_staff_id = {$restaurantstaffid}";
    if(mysqli_query($conn, $sql)){
        mysqli_close($conn);
        header("Location: http://localhost/MINI-PROJECT/admin/delete-restaurant-staff-home-page.php");
    }
    else{
        echo "<p style='font-size:10px; color:red; text-align:center; margin:10px 0;'>Deletion failed 🥺</p>";
        mysqli_close($conn);
    }
?>