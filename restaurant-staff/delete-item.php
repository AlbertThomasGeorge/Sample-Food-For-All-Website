<?php
    include "../config.php";
    $itemid = $_GET['itemid'];
    $imagefile = $_GET['itemfile'];
    $sql = "DELETE FROM items WHERE item_id = {$itemid}";
    if(mysqli_query($conn, $sql)){
        unlink("../admin/images of items/".$imagefile);
        mysqli_close($conn);
        header("Location: http://localhost/MINI-PROJECT/restaurant-staff/delete-item-home-page.php");
    }
    else{
        echo "<p style='font-size:10px; color:red; text-align:center; margin:10px 0;'>Deletion failed 🥺</p>";
        mysqli_close($conn);
    }
?>