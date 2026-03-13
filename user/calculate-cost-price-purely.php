<?php
    include "../config.php";
    $itemid = mysqli_real_escape_string($conn, $_POST['item_id']);
    $sql = "SELECT * FROM items WHERE item_id = {$itemid}";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $cost_price = mysqli_real_escape_string($conn, $row['only_cost_price']);
        }
        $total_cost_price = $_POST['quantity_total'] * $cost_price;
        echo "Cost Price is ₹<span>".$total_cost_price."</span>";
    }
    else{
        echo "Item Deleted";
    }
?>