<?php
    include "../config.php";
    $deliverypersonid = $_GET['deliverypersonid'];
    $sql = "DELETE FROM delivery_people WHERE delivery_person_id = {$deliverypersonid}";
    if(mysqli_query($conn, $sql)){
        mysqli_close($conn);
        header("Location: http://localhost/MINI-PROJECT/admin/delete-delivery-person-home-page.php");
    }
    else{
        echo "<p style='font-size:10px; color:red; text-align:center; margin:10px 0;'>Deletion failed 🥺</p>";
        mysqli_close($conn);
    }
?>    